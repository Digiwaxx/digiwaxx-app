<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Services\TrackReportGenerator;
use Barryvdh\DomPDF\Facade\Pdf;

class TrackReportController extends Controller
{
    /**
     * Download a generated report using secure token
     *
     * @param string $token
     * @return \Illuminate\Http\Response
     */
    public function download($token)
    {
        // Decode token (format: base64(trackId|timestamp))
        $decoded = base64_decode($token);
        $parts = explode('|', $decoded);

        if (count($parts) != 2) {
            abort(404, 'Invalid download link');
        }

        $trackId = (int) $parts[0];
        $timestamp = (int) $parts[1];

        // Check if token is expired (valid for 7 days)
        if (time() - $timestamp > (7 * 24 * 60 * 60)) {
            return view('reports.expired');
        }

        // Verify track exists
        $track = DB::table('tracks')->where('id', $trackId)->first();
        if (!$track) {
            abort(404, 'Track not found');
        }

        // Generate HTML report
        $html = TrackReportGenerator::generateHTMLReport($trackId);

        // Generate PDF
        $pdf = Pdf::loadHTML($html);
        $pdf->setPaper('a4', 'portrait');

        $filename = 'Track_Report_' . $track->artist . '_' . $track->title . '_' . date('Y-m-d') . '.pdf';
        $filename = preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $filename);

        // Log the download
        DB::table('email_notification_logs')->insert([
            'client_id' => $track->client ?? 0,
            'track_id' => $trackId,
            'notification_type' => 'report_download',
            'status' => 'opened',
            'recipient_email' => Auth::check() ? Auth::user()->email : 'anonymous',
            'sent_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return $pdf->download($filename);
    }

    /**
     * Generate a new report for a track
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function generate(Request $request, $id)
    {
        // Verify user owns this track or is admin
        $track = DB::table('tracks')->where('id', $id)->first();

        if (!$track) {
            return response()->json(['error' => 'Track not found'], 404);
        }

        $user = Auth::user();

        // Check ownership (assuming client_id is stored in user or member table)
        $clientId = $user->client_id ?? $user->id ?? null;

        if ($track->client != $clientId && !$this->isAdmin($user)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Generate report data
        $reportData = TrackReportGenerator::generateReportData($id);

        if (!$reportData) {
            return response()->json(['error' => 'Unable to generate report'], 500);
        }

        // Create report record
        $reportId = DB::table('generated_reports')->insertGetId([
            'client_id' => $clientId,
            'track_id' => $id,
            'report_type' => $request->input('type', 'full'),
            'format' => $request->input('format', 'pdf'),
            'date_range_start' => $request->input('start_date'),
            'date_range_end' => $request->input('end_date'),
            'file_path' => '',  // Will be updated after generation
            'generated_at' => now(),
            'expires_at' => now()->addDays(30),
            'download_count' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Generate download URL
        $downloadUrl = TrackReportGenerator::getReportDownloadUrl($id);

        return response()->json([
            'success' => true,
            'report_id' => $reportId,
            'download_url' => $downloadUrl,
            'message' => 'Report generated successfully'
        ]);
    }

    /**
     * Show report generation options modal
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function showOptions($id)
    {
        $track = DB::table('tracks')->where('id', $id)->first();

        if (!$track) {
            abort(404, 'Track not found');
        }

        return view('reports.options', compact('track'));
    }

    /**
     * Show report history for current user
     *
     * @return \Illuminate\Http\Response
     */
    public function history()
    {
        $user = Auth::user();
        $clientId = $user->client_id ?? $user->id ?? null;

        $reports = DB::table('generated_reports as gr')
            ->leftJoin('tracks as t', 'gr.track_id', '=', 't.id')
            ->where('gr.client_id', $clientId)
            ->select(
                'gr.*',
                't.title as track_title',
                't.artist as track_artist'
            )
            ->orderBy('gr.generated_at', 'desc')
            ->paginate(20);

        return view('reports.history', compact('reports'));
    }

    /**
     * Delete a generated report
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $user = Auth::user();
        $clientId = $user->client_id ?? $user->id ?? null;

        $report = DB::table('generated_reports')
            ->where('id', $id)
            ->where('client_id', $clientId)
            ->first();

        if (!$report) {
            return response()->json(['error' => 'Report not found'], 404);
        }

        // Delete file if exists
        if (!empty($report->file_path) && file_exists(storage_path($report->file_path))) {
            unlink(storage_path($report->file_path));
        }

        // Delete record
        DB::table('generated_reports')->where('id', $id)->delete();

        return response()->json(['success' => true, 'message' => 'Report deleted']);
    }

    /**
     * Check if user is admin
     *
     * @param object $user
     * @return bool
     */
    private function isAdmin($user)
    {
        // Adjust this based on your admin check logic
        return isset($user->is_admin) && $user->is_admin == 1;
    }
}
