<?php



namespace App\Http\Controllers;

use Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Frontend\FrontEndUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Route;
use App\Models\Tracks;
use App\Models\Albums;
use Redirect;
use Session;
use pCloud;
use Rbaskam\LaravelPCloud\App;
use Rbaskam\LaravelPCloud\File;
use Rbaskam\LaravelPCloud\Folder;
use Exception;
use App\Models\Gallery;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use \stdClass;
use URL;
use Response;

// for mail sending
use Illuminate\Support\Facades\Mail;
use App\Mail\AdminForgetNotification;





class AlbumsController extends Controller
{
    protected $pCloudApp;
    protected $admin_model;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->admin_model = new \App\Models\Admin;
        $this->memberAllDB_model = new \App\Models\MemberAllDB;

        $this->pCloudApp = new App();
        $this->pCloudApp->setAccessToken(config('laravel-pcloud.access_token'));
        $this->pCloudApp->setLocationId(config('laravel-pcloud.location_id'));
    }

    public function renameFileIfExists($fileName, $folderId)
    {
        $pcloudFile = new File($this->pCloudApp);
        $extension = pathinfo($fileName, PATHINFO_EXTENSION);
        $baseName = pathinfo($fileName, PATHINFO_FILENAME);

        $i = 1;
        do {
            $newFileName = $baseName . '_' . $i . '.' . $extension;
            $i++;
        } while (!empty($pcloudFile->metadata($fileName, $folderId)));

        return $newFileName;
    }

    public function admin_add_new_album(Request $request)
    {
        $admin_name = Auth::user()->name;
        $admin_id = Auth::user()->id;
        $user_role = Auth::user()->user_role;
        $output = array();
        $logo_data = array(
            'logo_id' => 1,
        );

        // access modules
        $output['access'] = $this->admin_model->getAdminModules($admin_id);
        $clients = $this->admin_model->getClientsList();
        $output['clients'] = $clients['data'];
        $client_data = $output['clients'];

        $logo_details = DB::table('website_logo')
            ->where($logo_data)
            ->first();

        $get_logo = $logo_details->logo;

        $output['pageTitle'] = 'Add New Album';
        $output['logo'] = $get_logo;
        $output['welcome_name'] = $admin_name;
        $output['user_role'] = $user_role;

        return view("admin.add_new_album", $output);
    }


    public function save_admin_add_new_album(Request $request)
    {
        try {
            $response = array();

            $isDuplicate = Albums::where('title', $request->title)->get();
            if (count($isDuplicate) !== 0) {
                $responseRet = array('status' => 'duplicate', 'title' => $request->title, 'duplicateId' => '', 'message' => 'The Album with this title already exists.');

                return response()->json($responseRet);
            }

            $Albums = new Albums;
            $Albums->added = date('Y-m-d H:i:s');
            $Albums->edited = date('Y-m-d H:i:s');
            $Albums->created_at = date('Y-m-d H:i:s');
            $Albums->updated_at = date('Y-m-d H:i:s');

            if ($request->has('title') && !empty($request->title)) {
                $Albums->title = $request->title;
            }

            $is_saved = $Albums->save();

            if ($is_saved) {

                $albumid = $Albums->id;
                $AlbumsUpdate = Albums::where('id', '=',  $albumid)->first();

                if ($request->hasFile('pageImage')) {

                    $image = $request->file('pageImage');

                    // SECURITY FIX: Validate MIME type to prevent PHP shell uploads
                    $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                    $fileMime = $image->getMimeType();

                    if (!in_array($fileMime, $allowedMimes)) {
                        return redirect()->back()->with('error', 'Invalid file type. Only JPEG, PNG, and GIF images are allowed.');
                    }

                    // SECURITY FIX: Validate file extension
                    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                    $fileExtension = strtolower($image->getClientOriginalExtension());

                    if (!in_array($fileExtension, $allowedExtensions)) {
                        return redirect()->back()->with('error', 'Invalid file extension.');
                    }

                    // SECURITY FIX: Generate safe filename (prevent path traversal attacks)
                    $imageName = 'album_' . $albumid . '_' . uniqid() . '.' . $fileExtension;
                    $filepath = $image->getRealPath();

                    $pcloudFolder = new Folder($this->pCloudApp);
                    $parentFolderId = env('PCLOUD_ALBUMS_PATH');

                    $pcloudFile = new File($this->pCloudApp);
                    $folderName = (string)$albumid;
                    $folderId = $pcloudFolder->createFolderIfNotExists($folderName, $parentFolderId);

                    $fileMetadata = $pcloudFile->upload($filepath, $folderId, $imageName);

                    $pcloudFileName = $fileMetadata->metadata->name;
                    $pcloudFileId = $fileMetadata->metadata->fileid;
                    $parentfolderid = $fileMetadata->metadata->parentfolderid;

                    $AlbumsUpdate->album_page_image = $pcloudFileName;
                    $AlbumsUpdate->pCloudFileID_album = $pcloudFileId;
                    $AlbumsUpdate->pCloudParentFolderID_album = $parentfolderid;

                    $AlbumsUpdate->save();
                }

                if ($request->has('tracktitle') && !empty($request->tracktitle)) {
                    $titles = $request->tracktitle;

                    foreach ($titles as $title) {
                        if (!empty($title)) {
                            // echo ($title);
                            $Tracks = new Tracks;
                            $Tracks->added = date('Y-m-d H:i:s');
                            $Tracks->edited = date('Y-m-d H:i:s');
                            $Tracks->created_at = date('Y-m-d H:i:s');
                            $Tracks->updated_at = date('Y-m-d H:i:s');
                            $Tracks->title = $title;
                            $Tracks->album = $AlbumsUpdate->title;
                            $Tracks->albumid = $albumid;

                            $Tracks->img = $AlbumsUpdate->album_page_image;
                            $Tracks->imgpage = $AlbumsUpdate->album_page_image;
                            $Tracks->pCloudFileID = $AlbumsUpdate->pCloudFileID_album;
                            $Tracks->pCloudParentFolderID = $AlbumsUpdate->pCloudParentFolderID_album;

                            $is_save = $Tracks->save();

                            if ($is_save) {
                                // echo ("Saved");
                            }
                        }
                    }
                }
                $responseRet = array('status' => 1, 'insertId' => Crypt::encryptString($albumid), 'message' => 'Form Submited Successfully');

                return response()->json($responseRet);
            }
            return ($response);
        } catch (Exception $e) {
            $responseRet = array('status' => 0, 'insertId' => '', 'message' => $e->getMessage());

            echo json_encode($responseRet);
            exit();
        }
    }


    public function delete_duplicate(Request $request)
    {
        try {
            $response = array();
            $id = $request->input('delTrackId');

            $album = Albums::where('title',  $request->input('title'));

            if ($album) {
                $del = $album->delete();
                $responseRet = array('status' => 1, 'message' => 'Deleted Successfully');

                if ($del) {
                    return response()->json($responseRet);
                }
            }
        } catch (Exception $e) {
            $responseRet = array('status' => 0, 'insertId' => '', 'message' => $e->getMessage());
            echo json_encode($responseRet);
            exit();
        }
    }

    public function admin_edit_album(Request $request)
    {
        try {
            $album_id = Crypt::decryptString($request->id);

            $Album = Albums::where('id', $album_id)->first();

            $admin_name = Auth::user()->name;
            $admin_id = Auth::user()->id;
            $user_role = Auth::user()->user_role;
            $output = array();
            $logo_data = array(
                'logo_id' => 1,
            );
            // access modules
            $output['access'] = $this->admin_model->getAdminModules($admin_id);
            $clients = $this->admin_model->getClientsList();
            $output['clients'] = $clients['data'];
            $client_data = $output['clients'];

            $logo_details = DB::table('website_logo')
                ->where($logo_data)
                ->first();

            $track = DB::table('tracks')->where('albumid', $album_id)->where('deleted',0)->get();

            $track_mp3 = [];
            $t_sno = 0;

            foreach ($track as $t) {

                $t_mp3 = DB::table('tracks_mp3s')->where('track', $t->id)->get();

                $t_sno = $t_sno + 1;
                $count_track_mp3 = count($t_mp3);

                $t->sno = $t_sno;
                $t->rowspan = $count_track_mp3 + 1;

                $track_mp3[$t->id] = $t_mp3;

                foreach ($track_mp3[$t->id] as $mp3) {

                    if (ctype_digit($mp3->location)) {
                        $mp3->audio_url = route('audio_stream_pcloud', ['id' => Crypt::encryptString($mp3->location)]);
                        $mp3->audio_delete_url = route('remove_track_pcloud', ['id' => Crypt::encryptString($mp3->location)]);
                    } else {
                        // $path_audio_url = "https://digiwaxx.com/digiwaxx-dev/AUDIO/" . $mp3->location;
                        $path_audio_url =  env('APP_URL')."/AUDIO/" . $mp3->location;
                        $path_audio_delete_url = env('APP_URL')."/AUDIO/" . $mp3->location;

                        $path = asset('AUDIO/' . $mp3->location);

                        if (file_exists($path)) {
                            $mp3->audio_url = $path_audio_url;
                            $mp3->audio_delete_url = $path_audio_delete_url;
                        } else {
                            $mp3->audio_url = '';
                            $mp3->audio_delete_url = '';
                        }
                    }
                }
            }

            $get_logo = $logo_details->logo;
            $output['pageTitle'] = 'Edit Album';
            $output['logo'] = $get_logo;
            $output['welcome_name'] = $admin_name;
            $output['user_role'] = $user_role;
            $output['Album'] = $Album;
            $output['album_id'] = $album_id;
            $output['tracks'] = $track;
            $output['track_mp3'] = $track_mp3;

            return view("admin.edit_album", $output);
        } catch (DecryptException $e) {
            return "<h2>Url Not Found</h2>";
        }
    }


    public function save_admin_edit_album(Request $request)
    {
        try {
        $album_id = Crypt::decryptString($request->album_id);
        $response = array();

        $isDuplicate = Albums::where('title', $request->title)->get();

        if (count($isDuplicate) !== 0) {
            foreach ($isDuplicate as $ablum) {
                if ($ablum->id != $album_id) {
                    $responseRet = array('status' => 'duplicate', 'title' => $request->title, 'duplicateId' => '', 'message' => 'The Album with this title already exists.');
                    return response()->json($responseRet);
                }
            }
        }

        $Albums = Albums::where('id', $album_id)->first();
        $Albums->edited = date('Y-m-d H:i:s');
        $Albums->updated_at = date('Y-m-d H:i:s');

        if ($request->has('title') && !empty($request->title)) {
            $Albums->title = $request->title;
        }

        $is_saved = $Albums->save();

        if ($is_saved) {

            $albumid = $Albums->id;
            $AlbumsUpdate = Albums::where('id', '=',  $albumid)->first();

            if ($request->hasFile('pageImage')) {

                $image = $request->file('pageImage');

                // SECURITY FIX: Validate MIME type to prevent PHP shell uploads
                $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                $fileMime = $image->getMimeType();

                if (!in_array($fileMime, $allowedMimes)) {
                    return redirect()->back()->with('error', 'Invalid file type. Only JPEG, PNG, and GIF images are allowed.');
                }

                // SECURITY FIX: Validate file extension
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                $fileExtension = strtolower($image->getClientOriginalExtension());

                if (!in_array($fileExtension, $allowedExtensions)) {
                    return redirect()->back()->with('error', 'Invalid file extension.');
                }

                // SECURITY FIX: Generate safe filename (prevent path traversal attacks)
                $imageName = 'album_' . $albumid . '_' . uniqid() . '.' . $fileExtension;
                $filepath = $image->getRealPath();

                $pcloudFolder = new Folder($this->pCloudApp);
                $parentFolderId = env('PCLOUD_ALBUMS_PATH');

                $pcloudFile = new File($this->pCloudApp);
                $folderName = (string)$albumid;
                $folderId = $pcloudFolder->createFolderIfNotExists($folderName, $parentFolderId);

                $fileMetadata = $pcloudFile->upload($filepath, $folderId, $imageName);

                $pcloudFileName = $fileMetadata->metadata->name;
                $pcloudFileId = $fileMetadata->metadata->fileid;
                $parentfolderid = $fileMetadata->metadata->parentfolderid;

                $AlbumsUpdate->album_page_image = $pcloudFileName;
                $AlbumsUpdate->pCloudFileID_album = $pcloudFileId;
                $AlbumsUpdate->pCloudParentFolderID_album = $parentfolderid;

                $AlbumsUpdate->save();
            } else {
            }
            if ($request->has('tracktitle') && !empty($request->tracktitle)) {
                $titles = $request->tracktitle;

                if (count($titles) != 0) {

                    foreach ($titles as $title) {
                        if (!empty($title)) {
                            $Tracks = new Tracks;
                            $Tracks->added = date('Y-m-d H:i:s');
                            $Tracks->edited = date('Y-m-d H:i:s');
                            $Tracks->created_at = date('Y-m-d H:i:s');
                            $Tracks->updated_at = date('Y-m-d H:i:s');
                            $Tracks->title = $title;
                            $Tracks->album = $AlbumsUpdate->title;
                            $Tracks->albumid = $albumid;

                            $is_save = $Tracks->save();

                            if ($is_save) {
                                // echo ("Saved");
                            }
                        }
                    }
                }
            }

            $responseRet = array('status' => 1, 'insertId' => Crypt::encryptString($albumid), 'message' => 'Form Submited Successfully');

            return response()->json($responseRet);
        }

        return ($response);
        } catch (Exception $e) {
            $responseRet = array('status' => 0, 'insertId' => '', 'message' => $e->getMessage());

            echo json_encode($responseRet);
            exit();
        }
    }

    public function add_album_audio_files(Request $request)
    {
        try {
            $albumid = Crypt::decryptString($request->id);
            $admin_name = Auth::user()->name;
            $admin_id = Auth::user()->id;
            $user_role = Auth::user()->user_role;
            $logo_data = array(
                'logo_id' => 1,
            );
            $logo_details = DB::table('website_logo')
                ->where($logo_data)
                ->first();

            $get_logo = $logo_details->logo;
            $output = array();
            $output['pageTitle'] = 'Manage Album Mp3';
            $output['logo'] = $get_logo;
            $output['welcome_name'] = $admin_name;
            $output['user_role'] = $user_role;
            $output['album_id'] = ($albumid);
            $output['test'] = 'test';

            $album_id = $albumid; 

            $Album = Albums::where('id', '=',  $album_id)->first();

            $track = DB::table('tracks')->where('albumid', $album_id)->where('deleted',0)->get();


            $track_mp3 = [];

            $t_sno = 0;

            foreach ($track as $t) {

                $t_mp3 = DB::table('tracks_mp3s')->where('track', $t->id)->get();

                $t_sno = $t_sno + 1;
                $count_track_mp3 = count($t_mp3);

                $t->sno = $t_sno;
                $t->rowspan = $count_track_mp3 + 1;

                $track_mp3[$t->id] = $t_mp3;

                foreach ($track_mp3[$t->id] as $mp3) {

                    if (ctype_digit($mp3->location)) {
                        $mp3->audio_url = route('audio_stream_pcloud', ['id' => Crypt::encryptString($mp3->location)]);
                        $mp3->audio_delete_url = route('remove_track_pcloud', ['id' => Crypt::encryptString($mp3->location)]);
                    } else {
                        $path_audio_url = env('APP_URL')."/AUDIO/" . $mp3->location;
                        $path_audio_delete_url = env('APP_URL')."/AUDIO/" . $mp3->location;

                        $path = asset('AUDIO/' . $mp3->location);

                        if (file_exists($path)) {
                            $mp3->audio_url = $path_audio_url;
                            $mp3->audio_delete_url = $path_audio_delete_url;
                        } else {
                            $mp3->audio_url = '';
                            $mp3->audio_delete_url = '';
                        }
                    }
                }
            }

            $versions = [
                "Acapella", "Clean", "Clean Accapella", "Clean (16 Bar Intro)", "Dirty", "Dirty Accapella",
                "Dirty (16 Bar Intro)", "Instrumental", "Main", "TV Track"
            ];


            $all_mp3 = [];
            foreach ($all_mp3 as $mp3) {
                $is_present = in_array($mp3->version, $versions);

                if ($is_present) {
                    $mp3->other_version = '';
                } else {
                    $mp3->other_version = urldecode($mp3->version);
                }

                // $mp3->audio_url = "https://digiwaxx.com/digiwaxx-dev/admin/audio_stream_pcloud/".$mp3->location;

                if (ctype_digit($mp3->location)) {
                    // echo('Yes');
                    $mp3->audio_url = route('audio_stream_pcloud', ['id'=>Crypt::encryptString($mp3->location)]);
                    $mp3->audio_delete_url = route('remove_track_pcloud', ['id'=>Crypt::encryptString($mp3->location)]);
                } else {
                    $path_audio_url = env('APP_URL')."/AUDIO/" . $mp3->location;
                    $path_audio_delete_url = env('APP_URL')."/AUDIO/" . $mp3->location;

                    $path = asset('AUDIO/' . $mp3->location);

                    if (file_exists($path)) {
                        $mp3->audio_url = $path_audio_url;
                        $mp3->audio_delete_url = $path_audio_delete_url;
                    } else {
                        $mp3->audio_url = '';
                        $mp3->audio_delete_url = '';
                    }
                }
            }

            $output['tracks'] = $track;
            $output['track_mp3'] = $track_mp3;


            if ($track) {
                return view("admin.add_album_step2", $output);
            } else {
                return view("admin.not_found", $output);
            }
        } catch (DecryptException $e) {
            return "<h2>Url Not Found</h2>";
        }
    }

    public function save_mp3_album(Request $request)
    {
        $album_id = Crypt::decryptString($request->album_id);

        $Album = Albums::where('id', '=',  $album_id)->first();

        try {
            $response = array();

            $response = array('status' => 1, 'message' => 'Mp3 Track Saved Successfully');

            return response()->json($response);
        } catch (Exception $e) {
            $response = array('status' => 0, 'message' => $e->getMessage());

            echo json_encode($response);
            exit();
        }
    }


    public function admin_add_album_step3(Request $request)

    {
        try {
            $admin_name = Auth::user()->name;
            $admin_id = Auth::user()->id;
            $user_role = Auth::user()->user_role;
            $output = array();
            $logo_data = array(
                'logo_id' => 1,
            );
            // access modules
            $output['access'] = $this->admin_model->getAdminModules($admin_id);
            $clients = $this->admin_model->getClientsList();
            $output['clients'] = $clients['data'];
            $client_data = $output['clients'];
            $output['genres'] = $this->admin_model->getGenres_album();

            // get sub genres
            if (isset($_GET['getSubGenres']) && isset($_GET['genreId'])) {
                $subGenres = $this->admin_model->getSubGenres($_GET['genreId']);
                if ($subGenres['numRows'] > 0) {
                    $arr[] = array('id' => '0', 'name' => 'Select Sub Genre');
                    foreach ($subGenres['data'] as $genre) {
                        $arr[] = array('id' => $genre->subGenreId, 'name' => $genre->subGenre);
                    }
                } else {
                    $arr[] = array('id' => '0', 'name' => 'No Data found.');
                }
                echo json_encode($arr);
                exit;
            }

            $releasetypes = $this->admin_model->getReleaseTypes_trm();
            $output['releasetypes'] = $releasetypes;

            $logo_details = DB::table('website_logo')
                ->where($logo_data)
                ->first();

            $get_logo = $logo_details->logo;

            $album_id = Crypt::decryptString($request->id);

            $track_data = DB::table('tracks')
                ->where('albumid', $album_id)
                ->first();

            $track_data_logos = [];
            if (isset($track_data->logos) && !empty($track_data->logos)) {
                $track_data_logos = explode(",", $track_data->logos);
            }

            $all_logos = DB::table('logos')->select('*')->get();

            $logo_fileid = [];

            foreach ($all_logos as $logo) {
                $logo->company = urldecode($logo->company);

                if (in_array($logo->id, $track_data_logos)) {
                    if ($logo->pCloudFileID_logo != null) {
                        // array_push($logo_fileid, "https://digiwaxx.com/digiwaxx-dev/admin/pcloud_fetch_image/" . $logo->pCloudFileID_logo);
                        array_push($logo_fileid,route('pcloud_fetch_image', ['id'=> $logo->pCloudFileID_logo]));
                    } else if ($logo->img != null) {
                        $path = asset('public/Logos/' . $logo->img);
                        if (file_exists($path)) {
                            // array_push($logo_fileid, "https://digiwaxx.com/digiwaxx-dev/Logos/".$logo->img);
                            array_push($logo_fileid, $path);
                        }
                    }
                }
            }

            $output['pageTitle'] = 'Add Album Track Details';
            $output['logo'] = $get_logo;
            $output['welcome_name'] = $admin_name;
            $output['user_role'] = $user_role;
            $output['album_id'] = Crypt::encryptString($album_id);
            $output['all_logos'] = $all_logos;
            $output['track_data'] = $track_data;
            $output['track_data_logos'] = $track_data_logos;
            $output['logo_fileid'] = $logo_fileid;

            return view("admin.add_album_step3", $output);
        } catch (DecryptException $e) {
            return "<h2>Url Not Found</h2>";
        }
    }


    public function save_admin_add_album_step3(Request $request)
    {
        echo ("Submitting");
        $album_id = Crypt::decryptString($request->id);

        $admin_id = Auth::user()->id;

        try {
            $response = array();

            $Album = Albums::where('id', $album_id)->first();

            $Trackss = Tracks::where('albumid', $album_id)->get();

            $flag = 1;

            foreach ($Trackss as $Tracks) {
                $Tracks->updated_at = date('Y-m-d H:i:s');
                $Tracks->edited = date('Y-m-d H:i:s');
                $Tracks->addedby = $admin_id;
                $Tracks->editedby = $admin_id;
                $trackType = 'track';

                $Tracks->img = $Album->album_page_image;
                $Tracks->imgpage = $Album->album_page_image;
                $Tracks->pCloudFileID = $Album->pCloudFileID_album;
                $Tracks->pCloudParentFolderID = $Album->pCloudParentFolderID_album;

                if ($request->has('artist') && !empty($request->artist)) {
                    $Tracks->artist = $request->artist;
                }
                if ($request->has('time') && !empty($request->time)) {
                    $Tracks->time = $request->time;
                }
                if ($request->has('status') && !empty($request->status)) {
                    $Tracks->status = $request->status;
                }
                if ($request->has('featured_artist_1') && !empty($request->featured_artist_1)) {
                    $Tracks->featured_artist_1 = $request->featured_artist_1;
                }
                if ($request->has('featured_artist_2') && !empty($request->featured_artist_2)) {
                    $Tracks->featured_artist_2 = $request->featured_artist_2;
                }
                if ($request->has('type') && !empty($request->type)) {
                    $trackType = $request->type;
                }
                $Tracks->type = $trackType;
                if ($request->has('client') && !empty($request->client)) {
                    $Tracks->client = $request->client;
                }
                if ($request->has('company') && !empty($request->company)) {
                    $Tracks->label = $request->company;
                }
                if ($request->has('producers') && !empty($request->producers)) {
                    $Tracks->producer = $request->producers;
                }
                if ($request->has('albumType') && !empty($request->albumType)) {
                    $Tracks->albumType = $request->albumType;
                }
                if ($request->has('writer') && !empty($request->writer)) {
                    $Tracks->writer = $request->writer;
                }
                if ($request->has('album') && !empty($request->album)) {
                    $Tracks->album = $request->album;
                }
                if ($request->has('bpm') && !empty($request->bpm)) {
                    $Tracks->bpm = $request->bpm;
                }
                if ($request->has('genre') && !empty($request->genre)) {
                    $Tracks->genreId = $request->genre;
                    $Tracks->active = $request->availableMembers;
                    $Tracks->review = $request->reviewable;
                }
                if ($request->has('subGenre') && !empty($request->subGenre)) {
                    $Tracks->subGenreId = $request->subGenre;
                }
                if ($request->has('songkey') && !empty($request->songkey)) {
                    $Tracks->songkey = $request->songkey;
                }
                if ($request->has('moreInfo') && !empty($request->moreInfo)) {
                    $Tracks->moreInfo = $request->moreInfo;
                }
                if ($request->has('notes') && !empty($request->notes)) {
                    $Tracks->notes = $request->notes;
                }
                if ($request->has('contact_name') && !empty($request->contact_name)) {
                    $Tracks->contact_name = $request->contact_name;
                    // contact details
                    $contact_track = DB::table('tracks_contacts')->insert([
                        'track' => $Tracks->id,
                        'title' => $Tracks->title,
                        'company' => $request->company,
                        'name' => $request->contact_name,
                        'phone' =>  $request->contact_phone,
                        'mobile' =>  $request->contact_phone,
                        'email' =>  $request->contact_email,
                        'added' => date('Y-m-d H:i:s'),
                        'addedby' => $admin_id
                    ]);
                }
                if ($request->has('contact_email') && !empty($request->contact_email)) {
                    $Tracks->contact_email = $request->contact_email;
                }
                if ($request->has('contact_phone') && !empty($request->contact_phone)) {
                    $Tracks->contact_phone = $request->contact_phone;
                }
                if ($request->has('relationship_to_artist') && !empty($request->relationship_to_artist)) {
                    $Tracks->relationship_to_artist = $request->relationship_to_artist;
                }
                //Artist Information
                if ($request->has('video') && !empty($request->video)) {
                    $Tracks->videoURL = $request->video;
                }
                if ($request->has('website') && !empty($request->website)) {
                    $Tracks->link = $request->website;
                }
                if ($request->has('website1') && !empty($request->website1)) {
                    $Tracks->link1 = $request->website1;
                }
                if ($request->has('website2') && !empty($request->website2)) {
                    $Tracks->link2 = $request->website2;
                }
                if ($request->has('facebookLink') && !empty($request->facebookLink)) {
                    $Tracks->facebookLink = $request->facebookLink;
                }
                if ($request->has('twitterLink') && !empty($request->twitterLink)) {
                    $Tracks->twitterLink = $request->twitterLink;
                }
                if ($request->has('instagramLink') && !empty($request->instagramLink)) {
                    $Tracks->instagramLink = $request->instagramLink;
                }
                if ($request->has('tiktokLink') && !empty($request->tiktokLink)) {
                    $Tracks->tiktokLink = $request->tiktokLink;
                }
                if ($request->has('snapchatLink') && !empty($request->snapchatLink)) {
                    $Tracks->snapchatLink = $request->snapchatLink;
                }
                if ($request->has('othersLink') && !empty($request->othersLink)) {
                    $Tracks->othersLink = $request->othersLink;
                }
                if ($request->has('youtubeLink') && !empty($request->youtubeLink)) {
                    $Tracks->youtube_link = $request->youtubeLink;
                }


                if ($request->hasFile('logoImage')) {
                    // SECURITY FIX: Removed debug echo statement
                    $image = $request->file('logoImage');

                    // SECURITY FIX: Validate MIME type to prevent PHP shell uploads
                    $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                    $fileMime = $image->getMimeType();

                    if (!in_array($fileMime, $allowedMimes)) {
                        return redirect()->back()->with('error', 'Invalid file type. Only JPEG, PNG, and GIF images are allowed.');
                    }

                    // SECURITY FIX: Validate file extension
                    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                    $fileExtension = strtolower($image->getClientOriginalExtension());

                    if (!in_array($fileExtension, $allowedExtensions)) {
                        return redirect()->back()->with('error', 'Invalid file extension.');
                    }

                    // SECURITY FIX: Generate safe filename (prevent path traversal attacks)
                    $imageName = 'logo_track_' . $Tracks->id . '_' . uniqid() . '.' . $fileExtension;
                    $filepath = $image->getRealPath();

                    $pcloudFolder = new Folder($this->pCloudApp);
                    $parentFolderId = env('PCLOUD_AUDIO_PATH');

                    $pcloudFile = new File($this->pCloudApp);
                    $folderName = (string)$Tracks->id;
                    $folderId = $pcloudFolder->createFolderIfNotExists($folderName, $parentFolderId);

                    $fileMetadata = $pcloudFile->upload($filepath, $folderId, $imageName);

                    $pcloudFileName = $fileMetadata->metadata->name;
                    $pcloudFileId = $fileMetadata->metadata->fileid;
                    $parentfolderid = $fileMetadata->metadata->parentfolderid;

                    if ($request->has('logoCompany') && !empty($request->logoCompany)) {
                        $logos_insert = DB::table('logos')->insertGetId([
                            'company' => $request->logoCompany,
                            'img' => $pcloudFileName,
                            'url' => $request->logoLink,
                            'pCloudFileID_logo' => $pcloudFileId,
                            'pCloudParentFolderID_logo' => $parentfolderid,
                            'added' => date('Y-m-d H:i:s'),
                            'addedby' => $admin_id
                        ]);
                        if ($logos_insert) {
                            $logo_id = $logos_insert;
                            $Tracks->logos = $Tracks->logos . "," . $logo_id;
                        }
                    }
                } else {
                    if ($request->has('logos') && !empty($request->logos)) {
                        $track_logos = implode(",", $request->logos);
                        $Tracks->logos = $track_logos;
                    }
                }
                $is_saved = $Tracks->save();

                if ($is_saved) {
                    echo ("Saved" . $Tracks->id);
                } else {
                    $flag = 0;
                }
            }



            if ($flag === 1) { // BUGFIX: Changed assignment (=) to comparison (===)

                $responseRet = array('status' => 1, 'message' => 'Form Submitted Successfully');

                return response()->json($responseRet);
            }

            return ($response);
        } catch (Exception $e) {
            $responseRet = array('status' => 0, 'message' => $e->getMessage());

            echo json_encode($responseRet);
            exit();
        }
    }

    public function admin_add_track_audio_files(Request $request)
    {
        try {
            $trackid = Crypt::decryptString($request->id);
            $album_id = Crypt::decryptString($request->album_id);
            $admin_name = Auth::user()->name;
            $admin_id = Auth::user()->id;
            $user_role = Auth::user()->user_role;
            $logo_data = array(
                'logo_id' => 1,
            );
            $logo_details = DB::table('website_logo')
                ->where($logo_data)
                ->first();

            $get_logo = $logo_details->logo;
            $output = array();
            $output['pageTitle'] = 'Manage Album Track Mp3';
            $output['logo'] = $get_logo;
            $output['welcome_name'] = $admin_name;
            $output['user_role'] = $user_role;
            $output['track_id'] = ($trackid);
            $output['album_id'] = ($album_id);
            $output['test'] = 'test';

            $track = DB::table('tracks')->where('id', strval($trackid))->first();

            $all_mp3 = DB::table('tracks_mp3s')->where('track', $trackid)->get();

            $versions = [
                "Acapella", "Clean", "Clean Accapella", "Clean (16 Bar Intro)", "Dirty", "Dirty Accapella",
                "Dirty (16 Bar Intro)", "Instrumental", "Main", "TV Track"
            ];

            foreach ($all_mp3 as $mp3) {
                $is_present = in_array($mp3->version, $versions);

                if ($is_present) {
                    $mp3->other_version = '';
                } else {
                    $mp3->other_version = urldecode($mp3->version);
                }

                if (ctype_digit($mp3->location)) {
                    $mp3->audio_url = route('audio_stream_pcloud', ['id'=>Crypt::encryptString($mp3->location)]);
                    $mp3->audio_delete_url = route('remove_track_pcloud', ['id'=>Crypt::encryptString($mp3->location)]);
                } else {
                    $path_audio_url = env('APP_URL')."/AUDIO/" . $mp3->location;
                    $path_audio_delete_url = env('APP_URL')."/AUDIO/" . $mp3->location;

                    $path = asset('AUDIO/' . $mp3->location);

                    if (file_exists($path)) {
                        $mp3->audio_url = $path_audio_url;
                        $mp3->audio_delete_url = $path_audio_delete_url;
                    } else {
                        $mp3->audio_url = '';
                        $mp3->audio_delete_url = '';
                    } 
                }
            }

            $output['all_mp3'] = $all_mp3;
            $output['track_title'] = $track->title;



            if ($track) {
                return view("admin.manage_album_tracks", $output);
            } else {
                return view("admin.not_found", $output);
            }
        } catch (DecryptException $e) {
            return "<h2>Url Not Found</h2>";
        }
    }

    public function save_mp3_album_track(Request $request)
    {
        $versions = $request->input('version');

        try {
            $response = array();
            foreach ($versions as $mp3_id => $versionName) {

                $preview = 0;

                if ($request->has('preview') && $request->preview == $mp3_id) {
                    $preview = 1;
                }

                if (!empty($request->otherversion[$mp3_id])) {
                    $versionName = $request->otherversion[$mp3_id];
                }


                $mp3_track = DB::table('tracks_mp3s')
                    ->where('id', $mp3_id)
                    ->update(['version' => $versionName, 'preview' => $preview]);

                if ($mp3_track) {
                    $response = array('status' => 1, 'message' => 'Mp3 Track Saved Successfully');
                }
            }

            return response()->json($response);
        } catch (Exception $e) {
            $response = array('status' => 0, 'message' => $e->getMessage());

            echo json_encode($response);
            exit();
        }
    }

    public function delete_Track(Request $request)
    {
        try {
            $response = array();

            $trackId = Crypt::decryptString($request->input('trackId'));
            echo ("Delete " . $trackId);

            $Track = Tracks::where('id', $trackId)->update(['deleted' => 1]);

            if ($Track) {

                $mp3 = DB::table('tracks_mp3s')->where('track', $trackId);

                if ($mp3) {
                    $del_mp3 = $mp3->delete();
                }

                $responseRet = array('status' => 1, 'message' => 'Track Deleted Successfully');

                // if ($del) {
                    return response()->json($responseRet);
                // }
            }
        } catch (Exception $e) {
            $responseRet = array('status' => 0, 'insertId' => '', 'message' => $e->getMessage());

            echo json_encode($responseRet);
            exit();
        }
    }
}
