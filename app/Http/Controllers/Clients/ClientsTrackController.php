<?php



namespace App\Http\Controllers\Clients;

use Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Frontend\FrontEndUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Route;
use App\Models\TracksSubmitted;
use App\Models\Tracks;
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

class ClientsTrackController extends Controller
{
    protected $pCloudApp;
    protected $clientAllDB_model;

	public function __construct()
	{
		$this->clientAllDB_model = new \App\Models\ClientAllDB;
		$this->memberAllDB_model = new \App\Models\MemberAllDB;
        
		$this->pCloudApp = new App();
		$this->pCloudApp->setAccessToken(config('laravel-pcloud.access_token'));
		$this->pCloudApp->setLocationId(config('laravel-pcloud.location_id'));
	}

    public function client_add_new_track(Request $request)
	{
		if (empty(session('clientId'))) {
			return redirect()->intended('login');
		}
		if (!empty(session('tempClientId'))) {
			$output['welcomeMsg'] = 'Thank you for updating your information !';
			session()->forget('tempClientId');
		}
		$output = array();
		$output['pageTitle'] = 'Digiwax Client Submit Track';
		$output['packageId'] = 2;
		$output['displayDashboard'] = 1;

		$clientId = session('clientId');
		$output['sessClientID'] = $clientId;

		date_default_timezone_set('America/Los_Angeles');

		// logo 
		$headerOutput['logo'] = $this->clientAllDB_model->getLogo_cld();
		$clientdata = $this->clientAllDB_model->getClientsDetails_cld($clientId);
		$headerOutput['wrapperClass'] = 'client';

        // SECURITY FIX: Use Laravel request instead of $_GET superglobal
        if ($request->has('getSubGenres') && $request->has('genreId')) {
			$genreId = (int) $request->input('genreId'); // Cast to int for safety
			$subGenres = $this->clientAllDB_model->getSubGenres_cld($genreId);
			if ($subGenres['numRows'] > 0) {
				$arr[] = array('id' => '0', 'name' => 'Select Sub Genre');
				foreach ($subGenres['data'] as $genre) {
					$arr[] = array('id' => $genre->subGenreId, 'name' => $genre->subGenre);
				}
			}
            else {
				$arr[] = array('id' => '0', 'name' => 'No Data found.');
			}
			return response()->json($arr);
		}

        
		$output['genres'] = $this->clientAllDB_model->getGenres_cld();

        return view('clients.dashboard.client_new_track', $output);
    }

    public function save_client_add_track_new(Request $request)
	{
        // echo("Submitting...");die;
		try {
			$response = array();

			$isDuplicate = Tracks::where('artist', $request->artist)
				->where('title', $request->title)->get();
			if (count($isDuplicate) !== 0) {
				$responseRet = array('status' => 'duplicate', 'artist' => $request->artist, 'title' => $request->title, 'duplicateId' => '', 'message' => 'The track with this artist and title already exists.');

				return response()->json($responseRet);
			}

			$Tracks = new TracksSubmitted;
			$Tracks->added = date('Y-m-d H:i:s');
			$Tracks->created_at = date('Y-m-d H:i:s');
			$Tracks->updated_at = date('Y-m-d H:i:s');

			if ($request->has('artist') && !empty($request->artist)) {
				$Tracks->artist = $request->artist;
			}
			if ($request->has('title') && !empty($request->title)) {
				$Tracks->title = $request->title;
			}
			if ($request->has('time') && !empty($request->time)) {
				$Tracks->time = $request->time;
			}
            if ($request->has('client_id') && !empty($request->client_id)) {
				$Tracks->client = $request->client_id;
			}

			$is_saved = $Tracks->save();

			if ($is_saved) {

				$trackid = $Tracks->id;
				$TracksUpdate = TracksSubmitted::where('id', '=',  $trackid)->first();

				if ($request->hasFile('pageImage')) {

					$image = $request->file('pageImage');
					$imageName = $image->getClientOriginalName();
					$filepath = $image->getRealPath();

					$pcloudFolder = new Folder($this->pCloudApp);
					// $folderId = $pcloudFolder->createfolderifnotexists("Tracks Meta");
					$parentFolderId = env('PCLOUD_CLIENT_AUDIO_PATH');

					$pcloudFile = new File($this->pCloudApp);
					$folderName = (string)$trackid;
					$folderId = $pcloudFolder->createFolderIfNotExists($folderName, $parentFolderId);

					$fileMetadata = $pcloudFile->upload($filepath, $folderId, $imageName);

					$pcloudFileName = $fileMetadata->metadata->name;
					$pcloudFileId = $fileMetadata->metadata->fileid;
					$parentfolderid = $fileMetadata->metadata->parentfolderid;

					$TracksUpdate->imgpage = $pcloudFileName;
					$TracksUpdate->pCloudFileID = $pcloudFileId;
					$TracksUpdate->pCloudParentFolderID = $parentfolderid;
					// $TracksUpdate->order_position = $trackid;

					$TracksUpdate->save();
				}
				// blade file-// window.lsData['step2'] = "{{ route('add_audio_files',['id'=>JSON.parse($responseRet['insertId'])]) }}";

				$responseRet = array('status' => 1, 'insertId' => Crypt::encryptString($trackid), 'message' => 'Form Submited Successfully');

				// echo json_encode($responseRet);
				// exit();
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

			$track = Tracks::where('artist',  $request->input('artist'))
				->where('title',  $request->input('title'));

			if ($track) {
				$del = $track->delete();
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

    public function client_track_step2(Request $request)
	{
        $trackid = Crypt::decryptString($request->id);
		if (empty(session('clientId'))) {
			return redirect()->intended('login');
		}
		if (!empty(session('tempClientId'))) {
			$output['welcomeMsg'] = 'Thank you for updating your information !';
			session()->forget('tempClientId');
		}
		$output = array();
		$output['pageTitle'] = 'Digiwax Client Submit Track';
		$output['packageId'] = 2;
		$output['displayDashboard'] = 1;
        $output['displayDashboard'] = 1;
        $output['track_id'] = ($trackid);


		$clientId = session('clientId');
		$output['sessClientID'] = $clientId;

		date_default_timezone_set('America/Los_Angeles');

		// logo 
		$headerOutput['logo'] = $this->clientAllDB_model->getLogo_cld();
		$clientdata = $this->clientAllDB_model->getClientsDetails_cld($clientId);
		$headerOutput['wrapperClass'] = 'client';


        return view('clients.dashboard.client_track_step2', $output);
    }


    public function upload(Request $request)
	{
		// SECURITY FIX: Validate file uploads to prevent RCE
		$request->validate([
			'files.*' => [
				'required',
				'file',
				'mimes:mp3,wav,flac,aac,ogg,m4a,wma',
				'max:102400', // 100MB max
			],
		], [
			'files.*.mimes' => 'Only audio files are allowed (mp3, wav, flac, aac, ogg, m4a, wma)',
			'files.*.max' => 'Audio file size must not exceed 100MB',
		]);

		$path = public_path('uploads/digi_pcloud');
		// $admin_name = Auth::user()->name;
		// $admin_id = Auth::user()->id;
		// $user_role = Auth::user()->user_role;
		// $logo_data = array(
		// 	'logo_id' => 1,
		// );
		// $logo_details = DB::table('website_logo')
		// 	->where($logo_data)
		// 	->first();

		// $get_logo = $logo_details->logo;
		$output = array();
		// $output['pageTitle'] = 'Manage Mp3';
		// $output['logo'] = $get_logo;
		// $output['welcome_name'] = $admin_name;
		// $output['user_role'] = $user_role;
		if ($request->hasFile('files')) {
			$data = array();
			$files =  $request->file('files');

            $file_no = 0;

			foreach ($files as $file) {

				// SECURITY FIX: Additional MIME type validation
				$allowedMimes = ['audio/mpeg', 'audio/mp3', 'audio/wav', 'audio/x-wav', 'audio/flac', 'audio/aac', 'audio/ogg', 'audio/mp4', 'audio/x-m4a', 'audio/x-ms-wma'];
				$allowedExtensions = ['mp3', 'wav', 'flac', 'aac', 'ogg', 'm4a', 'wma'];

				if (!in_array($file->getMimeType(), $allowedMimes)) {
					return Response::json(['error' => 'Invalid file type. Only audio files are allowed.'], 422);
				}

				$extension = strtolower($file->getClientOriginalExtension());
				if (!in_array($extension, $allowedExtensions)) {
					return Response::json(['error' => 'Invalid file extension. Only audio files are allowed.'], 422);
				}

				// SECURITY FIX: Validate file size (100MB max)
				if ($file->getSize() > 104857600) {
					return Response::json(['error' => 'File size must be less than 100MB.'], 422);
				}

				// SECURITY FIX: Sanitize filename to prevent path traversal
				$originalFilename = $file->getClientOriginalName();
				$filename = preg_replace('/[^a-zA-Z0-9_\-\.]/', '_', pathinfo($originalFilename, PATHINFO_FILENAME));
				$filename = substr($filename, 0, 100) . '.' . $extension; // Limit length and add extension

				// pcloud
				$filepath = $file->getRealPath();

				$pcloudFolder = new Folder($this->pCloudApp);

				// $folderId = $pcloudFolder->createfolderifnotexists("Tracks Meta");

				$parentFolderId =  env('PCLOUD_CLIENT_AUDIO_PATH');

				$pcloudFile = new File($this->pCloudApp);

				// $trackid = ($request->track_id);
				$trackid = Crypt::decryptString($request->track_id);
				// echo($trackid);die;

				$folderName = (string)$trackid; ////////////////////////////

				$folderId = $pcloudFolder->createFolderIfNotExists($folderName, $parentFolderId);

				$fileMetadata = $pcloudFile->upload($filepath, $folderId, $filename); //------------

				// print_r($fileMetadata);
				// die;

				$filetitle = '';

				$pcloudFileName = $fileMetadata->metadata->name;

				$pcloudFileId = $fileMetadata->metadata->fileid;

				$parentfolderid = $fileMetadata->metadata->parentfolderid;

				if (isset($fileMetadata->metadata->title)) {
					$filetitle = $fileMetadata->metadata->title;
				} else {
					$filetitle = $fileMetadata->metadata->name;
				}


				$file_size = $fileMetadata->metadata->size;

				$f_size = round($file_size / 1000, 2);

				
				if ($fileMetadata) {
                    $file_no ++ ;
                    // echo($file_no);
                    $amr = 'amr'.$file_no;
                    $t_title = 'title'.$file_no;
                    // echo('amr'.$file_no."=".$pcloudFileId);
                    // $tracks_amr = TracksSubmitted::find($trackid)->first();
                    // $amr_array = array(
                    //     'amr1'=>$tracks_amr->amr1,
                    //     'amr2'=>$tracks_amr->amr2,
                    //     'amr3'=>$tracks_amr->amr3,
                    //     'amr4'=>$tracks_amr->amr4,
                    //     'amr5'=>$tracks_amr->amr5,
                    //     'amr6'=>$tracks_amr->amr6,
                    // );

                    

                    $Tracks = TracksSubmitted::find($trackid);
                    $Tracks->updated_at = date('Y-m-d H:i:s');

                    if (empty($Tracks->amr1)) {
                        $Tracks->amr1 = $pcloudFileId;
                        $Tracks->title1 = $pcloudFileName;
                        $insert_id = '1';
                    } 
                    elseif (empty($Tracks->amr2)) {
                        $Tracks->amr2 = $pcloudFileId;
                        $Tracks->title2 = $pcloudFileName;
                        $insert_id = '2';
                    } 
                    elseif (empty($Tracks->amr3)) {
                        $Tracks->amr3 = $pcloudFileId;
                        $Tracks->title3 = $pcloudFileName;
                        $insert_id = '3';
                    } 
                    elseif (empty($Tracks->amr4)) {
                        $Tracks->amr4 = $pcloudFileId;
                        $Tracks->title4 = $pcloudFileName;
                        $insert_id = '4';
                    } 
                    elseif (empty($Tracks->amr5)) {
                        $Tracks->amr5 = $pcloudFileId;
                        $Tracks->title5 = $pcloudFileName;
                        $insert_id = '5';
                    } 
                    elseif (empty($Tracks->amr6)) {
                        $Tracks->amr6 = $pcloudFileId;
                        $Tracks->title6 = $pcloudFileName;
                        $insert_id = '6';
                    }

                    // $Tracks->$amr = $pcloudFileId;
                    // $Tracks->$t_title = $pcloudFileName;

                    $is_saved = $Tracks->save();

					if ($is_saved) {
						$insertedId = $insert_id;
						$info = new StdClass;
						$info->name = $pcloudFileName;
						$info->mp3_id = $insertedId;
						$info->id = $pcloudFileName;
						$info->size = $file_size;
						$info->type = 'mp3';
						// $info->url = $trackSrc;
						$info->url = URL::to('audio_stream_pcloud_client/' . Crypt::encryptString($pcloudFileId));
						$info->audio_url = URL::to('audio_stream_pcloud_client/' . Crypt::encryptString($pcloudFileId));;
						$info->deleteUrl = URL::to('remove_track_pcloud_client/' .  Crypt::encryptString($pcloudFileId).'/' .Crypt::encryptString($trackid) );
						// $info->deleteUrl = URL::to('admin/remove_track_pcloud');
						// 	//$info->delete_method = 'GET';
						$info->deleteType = 'GET';
						$info->error = null; 
						$data[] = $info;
					}
				} else {
				}
			}
			return Response::json(array('files' => $data));
		}

		return view('clients.dashboard.client_track_step2', $output);
	}

    public function uploadMultipleFile(Request $request)
	{
		$image_code = '';
		foreach ($request->file('file') as $image) {
			$new_name = rand() . '.' . $image->getClientOriginalExtension();
			$image->move(public_path('images'), $new_name);
			$image_code .= '<div class="col-md-3" style="margin-bottom:24px;"><img src="/images/' . $new_name . '" class="img-thumbnail" /></div>';
			Gallery::insert(['title' => $new_name]);
		}

		$res = array(
			'success'  => 'Images uploaded successfully',
			'image'   => $image_code
		);

		return response()->json($res);
	}

    public function pcloudStreamAudioUri($file_id)
	{
		$fileID = (int)Crypt::decryptString($file_id);
		$pcloudFile = new File($this->pCloudApp);
		$filename = $pcloudFile->getLink($fileID);
		$fileInfo = $pcloudFile->getInfo($fileID);

		if ($filename) {
			$fname = $fileInfo->metadata->name;
			$fsize = $fileInfo->metadata->size;
			ob_start();
			header('Content-Type: audio/mpeg');
			header('Content-Disposition: inline;filename="' . $fname . '"');
			header('Content-length: ' . $fsize);
			header('Cache-Control: no-cache');
			header("Content-Transfer-Encoding: chunked");

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $filename);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$output = curl_exec($ch);
			curl_close($ch);
			echo $output;
			ob_flush();
		} else {
			header("HTTP/1.0 404 Not Found");
		}
	}

    function delete(Request $request)
	{
		$f_id = (int)Crypt::decryptString($request->id);
        $track_id = Crypt::decryptString($request->track_id);

        // SECURITY FIX: Verify track ownership before allowing deletion
        $Tracks = TracksSubmitted::find($track_id);

        if(!$Tracks){
            return response()->json(['error' => 'Track not found'], 404);
        }

        $clientId = Session::get('clientId');
        if($Tracks->client != $clientId){
            return response()->json(['error' => 'Unauthorized - You can only delete your own tracks'], 403);
        }

		$pcloudFile = new File($this->pCloudApp);

		$fileMetadata = $pcloudFile->delete($f_id);
        $Tracks->updated_at = date('Y-m-d H:i:s');

        if (!empty($Tracks->amr1) && ($Tracks->amr1 == $f_id)) {
            $Tracks->amr1 = "";
            $Tracks->title1 = "";
            $Tracks->version1 = "";
        } 
        elseif (!empty($Tracks->amr2) && ($Tracks->amr2 == $f_id)) {
            $Tracks->amr2 = "";
            $Tracks->title2 = "";
            $Tracks->version2 = "";
        } 
        elseif (!empty($Tracks->amr3) && ($Tracks->amr3 == $f_id)) {
            $Tracks->amr3 = "";
            $Tracks->title3 = "";
            $Tracks->version3 = "";
        } 
        elseif (!empty($Tracks->amr4) && ($Tracks->amr4 == $f_id)) {
            $Tracks->amr4 = "";
            $Tracks->title4 = "";
            $Tracks->version4 = "";
        } 
        elseif (!empty($Tracks->amr5) && ($Tracks->amr5 == $f_id)) {
            $Tracks->amr5 = "";
            $Tracks->title5 = "";
            $Tracks->version5 = "";
        } 
        elseif (!empty($Tracks->amr6) && ($Tracks->amr6 == $f_id)) {
            $Tracks->amr6 = "";
            $Tracks->title6 = "";
            $Tracks->version6 = "";
        }

        // $Tracks->$amr = $pcloudFileId;
        // $Tracks->$t_title = $pcloudFileName;

        $is_saved = $Tracks->save();

        if ($is_saved) {
            return true;
        }

	}

    public function save_client_mp3_track(Request $request)
	{

        $trackid = Crypt::decryptString($request->track_id);
		$versions = $request->input('version');
        // echo ("Save Mp3".$trackid);
		try {
			$response = array();
			foreach ($versions as $mp3_id => $versionName) {

				$preview = 0;

                $version_db = 'version'.$mp3_id;

				if ($request->has('preview') && $request->preview == $mp3_id) {
					$preview = $mp3_id;
				}

				if (!empty($request->otherversion[$mp3_id])) {
					$versionName = $request->otherversion[$mp3_id];
				}


				$mp3_track = DB::table('tracks_submitted')
					->where('id', $trackid)
					->update([$version_db => $versionName, 'previewTrack' => $preview]);

				if ($mp3_track) {
					$response = array('status' => 1, 'message' => 'Mp3 Track Saved Successfully');
				}
			}
			// $response = array('status' => 1, 'message' => 'Mp3 Track Saved Successfully');

			return response()->json($response);
		} catch (Exception $e) {
			$response = array('status' => 0, 'message' => $e->getMessage());

			echo json_encode($response);
			exit();
		}
	}

    public function client_add_track_step3(Request $request)
	{
		if (empty(session('clientId'))) {
			return redirect()->intended('login');
		}
		if (!empty(session('tempClientId'))) {
			$output['welcomeMsg'] = 'Thank you for updating your information !';
			session()->forget('tempClientId');
		}
		$output = array();
		$output['pageTitle'] = 'Digiwax Client Submit Track';
		$output['packageId'] = 2;
		$output['displayDashboard'] = 1;

		$clientId = session('clientId');
		$output['sessClientID'] = $clientId;

		date_default_timezone_set('America/Los_Angeles');

		// logo 
		$headerOutput['logo'] = $this->clientAllDB_model->getLogo_cld();
		$clientdata = $this->clientAllDB_model->getClientsDetails_cld($clientId);
		$headerOutput['wrapperClass'] = 'client';

        // SECURITY FIX: Use Laravel request instead of $_GET superglobal
        if ($request->has('getSubGenres') && $request->has('genreId')) {
			$genreId = (int) $request->input('genreId'); // Cast to int for safety
			$subGenres = $this->clientAllDB_model->getSubGenres_cld($genreId);
			if ($subGenres['numRows'] > 0) {
				$arr[] = array('id' => '0', 'name' => 'Select Sub Genre');
				foreach ($subGenres['data'] as $genre) {
					$arr[] = array('id' => $genre->subGenreId, 'name' => $genre->subGenre);
				}
			}
            else {
				$arr[] = array('id' => '0', 'name' => 'No Data found.');
			}
			return response()->json($arr);
		}

        $track_data = DB::table('tracks_submitted')
				->where('id', Crypt::decryptString($request->id))
				->first();

        $output['track_id'] = $request->id;
        $output['track_data'] = $track_data;
		$output['genres'] = $this->clientAllDB_model->getGenres_cld();

        return view('clients.dashboard.client_track_step3', $output);
    }

	public function save_client_add_track_step3(Request $request)
	{
        
		$track_id = Crypt::decryptString($request->id);
        // echo("Saving".$track_id);die;
		try {
			$response = array();

			$Tracks = TracksSubmitted::find($track_id);
			$Tracks->updated_at = date('Y-m-d H:i:s');
			$trackType = 'track';


			if ($request->has('feat_artist_1') && !empty($request->feat_artist_1)) {
				$Tracks->feat_artist_1 = $request->feat_artist_1;
			}
			if ($request->has('feat_artist_2') && !empty($request->feat_artist_2)) {
				$Tracks->feat_artist_2 = $request->feat_artist_2;
			}
			// if ($request->has('type') && !empty($request->type)) {
			// 	$trackType = $request->type;
			// }
			// $Tracks->type = $trackType;
			// if ($request->has('client') && !empty($request->client)) {
			// 	$Tracks->client = $request->client;
			// }
			if ($request->has('company') && !empty($request->company)) {
				$Tracks->label = $request->company;
			}
			if ($request->has('producers') && !empty($request->producers)) {
				$Tracks->producers = $request->producers;
			}
			if ($request->has('albumType') && !empty($request->albumType)) {
				$Tracks->albumType = $request->albumType;
			}
			// if ($request->has('writer') && !empty($request->writer)) {
			// 	$Tracks->writer = $request->writer;
			// }
			if ($request->has('album') && !empty($request->album)) {
				$Tracks->album = $request->album;
			}
            if ($request->has('priorityType') && !empty($request->priorityType)) {
				$Tracks->priorityType = $request->priorityType;
			}
			if ($request->has('bpm') && !empty($request->bpm)) {
				$Tracks->bpm = $request->bpm;
			}
			if ($request->has('genre') && !empty($request->genre)) {
				$Tracks->genreId = $request->genre;
				// $Tracks->active = $request->availableMembers;
				// $Tracks->review = $request->reviewable;
			}
			if ($request->has('subGenre') && !empty($request->subGenre)) {
				$Tracks->subGenreId = $request->subGenre;
			}
			// if ($request->has('songkey') && !empty($request->songkey)) {
			// 	$Tracks->songkey = $request->songkey;
			// }
			if ($request->has('moreInfo') && !empty($request->moreInfo)) {
				$Tracks->moreinfo = $request->moreInfo;
			}
			// if ($request->has('notes') && !empty($request->notes)) {
			// 	$Tracks->notes = $request->notes;
			// }
			// if ($request->has('contact_name') && !empty($request->contact_name)) {
			// 	$Tracks->contact_name = $request->contact_name;
			// 	// contact details
			// 	$contact_track = DB::table('tracks_contacts')->insert([
			// 		'track' => $track_id,
			// 		'title' => $Tracks->title,
			// 		'company' => $request->company,
			// 		'name' => $request->contact_name,
			// 		'phone' =>  $request->contact_phone,
			// 		'mobile' =>  $request->contact_phone,
			// 		'email' =>  $request->contact_email,
			// 		'added' => date('Y-m-d H:i:s'),
			// 		'addedby' => $admin_id
			// 	]);
			// }
			// if ($request->has('contact_email') && !empty($request->contact_email)) {
			// 	$Tracks->contact_email = $request->contact_email;
			// }
			// if ($request->has('contact_phone') && !empty($request->contact_phone)) {
			// 	$Tracks->contact_phone = $request->contact_phone;
			// }
			// if ($request->has('relationship_to_artist') && !empty($request->relationship_to_artist)) {
			// 	$Tracks->relationship_to_artist = $request->relationship_to_artist;
			// }
			//Artist Information
			// if ($request->has('video') && !empty($request->video)) {
			// 	$Tracks->videoURL = $request->video;
			// }
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
			// if ($request->has('tiktokLink') && !empty($request->tiktokLink)) {
			// 	$Tracks->tiktokLink = $request->tiktokLink;
			// }
			// if ($request->has('snapchatLink') && !empty($request->snapchatLink)) {
			// 	$Tracks->snapchatLink = $request->snapchatLink;
			// }
			// if ($request->has('othersLink') && !empty($request->othersLink)) {
			// 	$Tracks->othersLink = $request->othersLink;
			// }
			// if ($request->has('youtubeLink') && !empty($request->youtubeLink)) {
			// 	$Tracks->youtube_link = $request->youtubeLink;
			// }


			// if ($request->hasFile('logoImage')) {
			// 	echo ('Yes Image');
			// 	$image = $request->file('logoImage');
			// 	$imageName = $image->getClientOriginalName();
			// 	$filepath = $image->getRealPath();

			// 	$pcloudFolder = new Folder($this->pCloudApp);
			// 	// $folderId = $pcloudFolder->createfolderifnotexists("Tracks Meta");
			// 	$parentFolderId =  env('PCLOUD_AUDIO_PATH');

			// 	$pcloudFile = new File($this->pCloudApp);
			// 	$folderName = (string)$track_id;
			// 	$folderId = $pcloudFolder->createFolderIfNotExists($folderName, $parentFolderId);

			// 	$fileMetadata = $pcloudFile->upload($filepath, $folderId, $imageName);

			// 	$pcloudFileName = $fileMetadata->metadata->name;
			// 	$pcloudFileId = $fileMetadata->metadata->fileid;
			// 	$parentfolderid = $fileMetadata->metadata->parentfolderid;

			// 	if ($request->has('logoCompany') && !empty($request->logoCompany)) {
			// 		$logos_insert = DB::table('logos')->insertGetId([
			// 			'company' => $request->logoCompany,
			// 			'img' => $pcloudFileName,
			// 			'url' => $request->logoLink,
			// 			'pCloudFileID_logo' => $pcloudFileId,
			// 			'pCloudParentFolderID_logo' => $parentfolderid,
			// 			'added' => date('Y-m-d H:i:s'),
			// 			'addedby' => $admin_id
			// 		]);
			// 		if ($logos_insert) {
			// 			$logo_id = $logos_insert;
			// 			$Tracks->logos = $Tracks->logos . "," . $logo_id;
			// 		}
			// 	}
			// } else {
			// 	if ($request->has('logos') && !empty($request->logos)) {
			// 		// print_r($request->logos);
			// 		$track_logos = implode(",", $request->logos);
			// 		// echo($track_logos);die;
			// 		$Tracks->logos = $track_logos;
			// 	}
			// }



			$is_saved = $Tracks->save();

			if ($is_saved) {

				$responseRet = array('status' => 1, 'message' => 'Form Submited Successfully');

				return response()->json($responseRet);
			}

			return ($response);
		} catch (Exception $e) {
			// echo ("Form Not Saved , ".$e);
			// $response['message'] = 'Not Saved'.$e;
			// return ($response);
			$responseRet = array('status' => 0, 'message' => $e->getMessage());

			echo json_encode($responseRet);
			exit();
		}
	}

	public function client_submitted_tracks(Request $request)
	{
		if (empty(session('clientId'))) {
			return redirect()->intended('login');
		}
		if (!empty(session('tempClientId'))) {
			$output['welcomeMsg'] = 'Thank you for updating your information !';
			session()->forget('tempClientId');
		}
		$output = array();
		$output['pageTitle'] = 'Digiwax Client Submit Track';
		$output['packageId'] = 2;
		$output['displayDashboard'] = 1;

		$clientId = session('clientId');
		$output['sessClientID'] = $clientId;

		date_default_timezone_set('America/Los_Angeles');

		// logo 
		$headerOutput['logo'] = $this->clientAllDB_model->getLogo_cld();
		$clientdata = $this->clientAllDB_model->getClientsDetails_cld($clientId);
		$headerOutput['wrapperClass'] = 'client';

		// echo($clientId);

		$tracks = DB::table('tracks_submitted')
					->where('client', $clientId)->get();
		
		// print_r($tracks);

		foreach ($tracks as $track) {
			if(isset($track->pCloudFileID) && !empty($track->pCloudFileID)){
				$track->image = route('client_pcloud_fetch_image', ['id'=> $track->pCloudFileID ]) ;
			}else{
				$track->image = '';
			}
			
			$track->track_genre = '';
			if(!empty($track->genreId)){
				$genre = DB::table('genres')
					->where('genreId', $track->genreId)
					->first();
				$track->track_genre = $genre->genre;
			}

			$track->track_subgenre = '';
			if(!empty($track->subGenreId)){
				$subgenre = DB::table('genres_sub')
					->where('subGenreId', $track->subGenreId)
					->first();
				$track->track_subgenre = $subgenre->subGenre;
			}

			if(!empty($track->amr1)){
				$track->amr1 = route('audio_stream_pcloud_client', ['id'=>Crypt::encryptString($track->amr1)]);
			}
			if(!empty($track->amr2)){
				$track->amr2 = route('audio_stream_pcloud_client', ['id'=>Crypt::encryptString($track->amr2)]);
			}
			if(!empty($track->amr3)){
				$track->amr3 = route('audio_stream_pcloud_client', ['id'=>Crypt::encryptString($track->amr3)]);
			}
			if(!empty($track->amr4)){
				$track->amr4 = route('audio_stream_pcloud_client', ['id'=>Crypt::encryptString($track->amr4)]);
			}
			if(!empty($track->amr5)){
				$track->amr5 = route('audio_stream_pcloud_client', ['id'=>Crypt::encryptString($track->amr5)]);
			}
			if(!empty($track->amr6)){
				$track->amr6 = route('audio_stream_pcloud_client', ['id'=>Crypt::encryptString($track->amr6)]);
			}
		}

		$output['client_tracks'] = $tracks;

        return view('clients.dashboard.client_submitted_tracks', $output);
    }


	public function pcloudFetchImageUri($file_id)
	{

		// echo($file_id);
		// die;

		$fileID = (int)$file_id;
		$pcloudFile = new File($this->pCloudApp);
		$filename = $pcloudFile->getLink($fileID);
		$fileInfo = $pcloudFile->getInfo($fileID);

		// echo($filename);die;

		if ($filename) {
			$fname = $fileInfo->metadata->name;
			$fsize = $fileInfo->metadata->size;
			ob_start();
			header('Content-Type: image/*');
			header('Content-Disposition: inline;filename="' . $fname . '"');
			header('Content-length: ' . $fsize);
			header('Cache-Control: no-cache');
			header("Content-Transfer-Encoding: chunked");
			// header('Accept-Ranges: bytes');

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $filename);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$output = curl_exec($ch);
			curl_close($ch);
			echo $output;

			ob_flush();
		} else {
			header("HTTP/1.0 404 Not Found");
		}
	}


}