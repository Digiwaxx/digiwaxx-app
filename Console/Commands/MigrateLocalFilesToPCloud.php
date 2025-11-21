<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Services\PCloudService;
use Exception;

/**
 * Migrate Local Files to pCloud
 *
 * This command migrates audio and image files from local storage to pCloud.
 *
 * Features:
 * - Scans tracks_mp3s table for local file paths
 * - Uploads files to pCloud
 * - Updates database with pCloud file IDs
 * - Optional dry-run mode
 * - Optional keep local files after migration
 * - Progress tracking and error handling
 *
 * Usage:
 *   php artisan pcloud:migrate-local-files                  # Full migration
 *   php artisan pcloud:migrate-local-files --dry-run        # Test without changes
 *   php artisan pcloud:migrate-local-files --keep-local     # Keep local files
 *   php artisan pcloud:migrate-local-files --limit=100      # Migrate first 100 files
 */
class MigrateLocalFilesToPCloud extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pcloud:migrate-local-files
                            {--dry-run : Preview what would be migrated without making changes}
                            {--keep-local : Keep local files after uploading to pCloud}
                            {--limit= : Limit number of files to migrate}
                            {--force : Skip confirmation prompt}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate local audio and image files to pCloud storage';

    /**
     * pCloud Service instance
     */
    protected $pCloud;

    /**
     * Statistics
     */
    protected $stats = [
        'total_found' => 0,
        'already_pcloud' => 0,
        'migrated' => 0,
        'failed' => 0,
        'skipped' => 0
    ];

    /**
     * Create a new command instance.
     */
    public function __construct(PCloudService $pCloud)
    {
        parent::__construct();
        $this->pCloud = $pCloud;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('╔══════════════════════════════════════════════════════════╗');
        $this->info('║  Digiwaxx - Migrate Local Files to pCloud               ║');
        $this->info('╚══════════════════════════════════════════════════════════╝');
        $this->newLine();

        // Check if dry run
        $dryRun = $this->option('dry-run');
        if ($dryRun) {
            $this->warn('🔍 DRY RUN MODE - No changes will be made');
            $this->newLine();
        }

        // Get local files that need migration
        $localFiles = $this->findLocalFiles();

        if ($localFiles->isEmpty()) {
            $this->info('✅ No local files found - all files are already in pCloud!');
            return 0;
        }

        $this->stats['total_found'] = $localFiles->count();

        // Apply limit if specified
        $limit = $this->option('limit');
        if ($limit) {
            $localFiles = $localFiles->take((int) $limit);
            $this->info("📊 Limiting migration to {$limit} files");
        }

        // Show summary
        $this->table(
            ['Metric', 'Count'],
            [
                ['Total local files found', $this->stats['total_found']],
                ['Files to migrate', $localFiles->count()]
            ]
        );
        $this->newLine();

        // Confirm before proceeding (unless --force)
        if (!$dryRun && !$this->option('force')) {
            if (!$this->confirm('Do you want to proceed with migration?', true)) {
                $this->warn('Migration cancelled.');
                return 0;
            }
            $this->newLine();
        }

        // Migrate each file
        $progressBar = $this->output->createProgressBar($localFiles->count());
        $progressBar->setFormat(' %current%/%max% [%bar%] %percent:3s%% - %message%');
        $progressBar->setMessage('Starting migration...');
        $progressBar->start();

        foreach ($localFiles as $file) {
            $progressBar->setMessage("Migrating: {$file->location}");

            try {
                $this->migrateFile($file, $dryRun);
                $progressBar->advance();
            } catch (Exception $e) {
                $this->stats['failed']++;
                Log::error('[MigrateToCloud] Failed to migrate file', [
                    'id' => $file->id,
                    'location' => $file->location,
                    'error' => $e->getMessage()
                ]);
                $progressBar->advance();
            }
        }

        $progressBar->finish();
        $this->newLine(2);

        // Show final statistics
        $this->showResults($dryRun);

        return 0;
    }

    /**
     * Find all local files that need migration
     *
     * @return \Illuminate\Support\Collection
     */
    protected function findLocalFiles()
    {
        $this->info('🔍 Scanning for local files...');

        // Find all files in tracks_mp3s where location is NOT numeric (not pCloud ID)
        $localFiles = DB::table('tracks_mp3s')
            ->whereRaw('location IS NOT NULL')
            ->whereRaw('location != ""')
            ->whereRaw('NOT (location REGEXP \'^[0-9]+$\')')  // Not just digits
            ->select('id', 'location', 'track_id')
            ->get();

        $this->info("   Found {$localFiles->count()} local files");
        $this->newLine();

        return $localFiles;
    }

    /**
     * Migrate a single file to pCloud
     *
     * @param object $fileRecord Database record
     * @param bool $dryRun
     * @return void
     * @throws Exception
     */
    protected function migrateFile($fileRecord, $dryRun = false)
    {
        // Check if file is already in pCloud (numeric ID)
        if (ctype_digit($fileRecord->location)) {
            $this->stats['already_pcloud']++;
            return;
        }

        // Construct local file path
        $localPath = $this->resolveLocalPath($fileRecord->location);

        // Check if local file exists
        if (!file_exists($localPath)) {
            $this->stats['skipped']++;
            Log::warning('[MigrateToCloud] Local file not found', [
                'id' => $fileRecord->id,
                'location' => $fileRecord->location,
                'resolved_path' => $localPath
            ]);
            return;
        }

        if ($dryRun) {
            $this->stats['migrated']++;
            return;
        }

        // Get file info
        $fileSize = filesize($localPath);
        $mimeType = mime_content_type($localPath);
        $fileName = basename($fileRecord->location);

        // Create UploadedFile instance
        $uploadedFile = new \Illuminate\Http\UploadedFile(
            $localPath,
            $fileName,
            $mimeType,
            null,
            true  // test mode
        );

        // Upload to pCloud
        $trackId = $fileRecord->track_id ?? null;
        $fileInfo = $this->pCloud->uploadAudio($uploadedFile, $trackId, 'admin');

        // Update database with pCloud file ID
        DB::table('tracks_mp3s')
            ->where('id', $fileRecord->id)
            ->update([
                'location' => (string) $fileInfo['file_id'],  // Store as pCloud file ID
                'updated_at' => now()
            ]);

        // Delete local file if not keeping
        if (!$this->option('keep-local')) {
            @unlink($localPath);
        }

        $this->stats['migrated']++;

        Log::info('[MigrateToCloud] File migrated successfully', [
            'id' => $fileRecord->id,
            'local_path' => $fileRecord->location,
            'pcloud_file_id' => $fileInfo['file_id'],
            'size' => $fileSize
        ]);
    }

    /**
     * Resolve local file path
     *
     * @param string $location Database location value
     * @return string Full path to local file
     */
    protected function resolveLocalPath($location)
    {
        // Remove any leading slashes
        $location = ltrim($location, '/');

        // Common paths to check
        $possiblePaths = [
            storage_path('app/audio/' . $location),
            storage_path('app/' . $location),
            public_path('AUDIO/' . $location),
            public_path($location),
            base_path($location),
            $location  // Absolute path
        ];

        // Return first existing path
        foreach ($possiblePaths as $path) {
            if (file_exists($path)) {
                return $path;
            }
        }

        // If none found, return first guess
        return $possiblePaths[0];
    }

    /**
     * Show migration results
     *
     * @param bool $dryRun
     * @return void
     */
    protected function showResults($dryRun = false)
    {
        $this->info('╔══════════════════════════════════════════════════════════╗');
        $this->info('║  Migration Results                                       ║');
        $this->info('╚══════════════════════════════════════════════════════════╝');
        $this->newLine();

        $this->table(
            ['Status', 'Count'],
            [
                ['Total files found', $this->stats['total_found']],
                ['Already in pCloud', $this->stats['already_pcloud']],
                [$dryRun ? 'Would migrate' : 'Successfully migrated', $this->stats['migrated']],
                ['Failed', $this->stats['failed']],
                ['Skipped (file not found)', $this->stats['skipped']]
            ]
        );

        $this->newLine();

        if ($this->stats['failed'] > 0) {
            $this->error("⚠️  {$this->stats['failed']} files failed to migrate. Check logs for details.");
        }

        if ($this->stats['migrated'] > 0) {
            if ($dryRun) {
                $this->info("✅ {$this->stats['migrated']} files ready to migrate.");
                $this->newLine();
                $this->comment('Run without --dry-run to perform actual migration:');
                $this->comment('   php artisan pcloud:migrate-local-files');
            } else {
                $this->info("✅ Successfully migrated {$this->stats['migrated']} files to pCloud!");

                if ($this->option('keep-local')) {
                    $this->newLine();
                    $this->comment('Local files were kept. To clean up:');
                    $this->comment('   php artisan pcloud:cleanup-local-files');
                }
            }
        }

        if ($this->stats['skipped'] > 0) {
            $this->newLine();
            $this->warn("⚠️  {$this->stats['skipped']} files were skipped (local file not found).");
            $this->comment('These records may need manual cleanup:');
            $this->comment('   SELECT * FROM tracks_mp3s WHERE location NOT REGEXP \'^[0-9]+$\';');
        }

        $this->newLine();
    }
}
