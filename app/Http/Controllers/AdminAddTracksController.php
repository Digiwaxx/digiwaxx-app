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
use Redirect;
use Session;
//use Mail;
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





class AdminAddTracksController extends Controller
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





	public function admin_add_track_new(Request $request)

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
		// $output['logos'] = $this->admin_model->getLogos_trm("");
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

		$output['pageTitle'] = 'Add New Track';

		$output['logo'] = $get_logo;

		$output['welcome_name'] = $admin_name;

		$output['user_role'] = $user_role;

		return view("admin.add_track_new", $output);
	}

	public function save_admin_add_track_new(Request $request)
	{
		try {
			$response = array();

			// $isDuplicate = Tracks::where('artist', $request->artist)
			//             ->where('title', $request->title)
			//             ->first();

			$isDuplicate = Tracks::where('artist', $request->artist)
				->where('title', $request->title)->get();
			// print_r($isDuplicate);
			// die;
			if (count($isDuplicate) !== 0) {


				// print_r($isDuplicate);

				// $track_id = $isDuplicate->id;

				$responseRet = array('status' => 'duplicate', 'artist' => $request->artist, 'title' => $request->title, 'duplicateId' => '', 'message' => 'The track with this artist and title already exists.');

				// $responseRet = array('status'=>'duplicate', 'duplicateId'=>$track_id, 'message'=>'The track with this artist and title already exists.');

				// return response()->json($responseRet);

				return response()->json($responseRet);

				// return back()->withErrors(['artist' => 'The track with this artist and title already exists.']);
			}

			$Tracks = new Tracks;
			$Tracks->added = date('Y-m-d H:i:s');
			$Tracks->edited = date('Y-m-d H:i:s');
			$Tracks->created_at = date('Y-m-d H:i:s');
			$Tracks->updated_at = date('Y-m-d H:i:s');
			$trackType = 'track';

			if ($request->has('artist') && !empty($request->artist)) {
				$Tracks->artist = $request->artist;
			}
			if ($request->has('title') && !empty($request->title)) {
				$Tracks->title = $request->title;
			}
			if ($request->has('time') && !empty($request->time)) {
				$Tracks->time = $request->time;
			}
			if ($request->has('status') && !empty($request->status)) {
				$Tracks->status = $request->status;
			}

			$is_saved = $Tracks->save();

			if ($is_saved) {

				$trackid = $Tracks->id;
				$TracksUpdate = Tracks::where('id', '=',  $trackid)->first();


				if ($request->hasFile('pageImage')) {

					// SECURITY FIX: Validate file upload properly
					$image = $request->file('pageImage');

					// Validate file type - only allow images
					$allowedMimes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
					$allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

					if (!in_array($image->getMimeType(), $allowedMimes)) {
						throw new \Exception('Invalid file type. Only images are allowed.');
					}

					// Validate file extension
					$extension = strtolower($image->getClientOriginalExtension());
					if (!in_array($extension, $allowedExtensions)) {
						throw new \Exception('Invalid file extension.');
					}

					// Validate file size (max 5MB)
					if ($image->getSize() > 5242880) {
						throw new \Exception('File size must be less than 5MB.');
					}

					// Generate secure random filename instead of using client-provided name
					$imageName = uniqid('track_') . '_' . time() . '.' . $extension;
					$filepath = $image->getRealPath();

					$pcloudFolder = new Folder($this->pCloudApp);
					// $folderId = $pcloudFolder->createfolderifnotexists("Tracks Meta");
					$parentFolderId = env('PCLOUD_AUDIO_PATH');

					$pcloudFile = new File($this->pCloudApp);
					$folderName = (string)$trackid;
					$folderId = $pcloudFolder->createFolderIfNotExists($folderName, $parentFolderId);

					$fileMetadata = $pcloudFile->upload($filepath, $folderId, $imageName);

					$pcloudFileName = $fileMetadata->metadata->name;
					$pcloudFileId = $fileMetadata->metadata->fileid;
					$parentfolderid = $fileMetadata->metadata->parentfolderid;

					$TracksUpdate->img = $pcloudFileName;
					$TracksUpdate->imgpage = $pcloudFileName;
					$TracksUpdate->pCloudFileID = $pcloudFileId;
					$TracksUpdate->pCloudParentFolderID = $parentfolderid;
					$TracksUpdate->order_position = $trackid;

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
		// echo ("Checking"); 
		try {
			$response = array();

			$id = $request->input('delTrackId');
			// die;
			// echo($request->input('artist'));
			// echo($request->input('title'));

			// $track = Tracks::find($id);

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
			// echo ("Form Not Saved , ".$e);
			// $response['message'] = 'Not Saved'.$e;
			// return ($response);
			$responseRet = array('status' => 0, 'insertId' => '', 'message' => $e->getMessage());

			echo json_encode($responseRet);
			exit();
		}
	}



	public function admin_add_audio_files(Request $request)
	{
		try {
			$trackid = Crypt::decryptString($request->id);
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
			$output['pageTitle'] = 'Manage Mp3';
			$output['logo'] = $get_logo;
			$output['welcome_name'] = $admin_name;
			$output['user_role'] = $user_role;
			$output['track_id'] = ($trackid);
			// echo($trackid);
			// echo('$id=='.$id);
			$output['test'] = 'test';



			// echo($a); 

			$track = DB::table('tracks')->where('id', strval($trackid))->first();

			// print_r($track);


			$all_mp3 = DB::table('tracks_mp3s')->where('track', $trackid)->get();

			$versions = [
				"Acapella", "Clean", "Clean Accapella", "Clean (16 Bar Intro)", "Dirty", "Dirty Accapella",
				"Dirty (16 Bar Intro)", "Instrumental", "Main", "TV Track"
			];

			foreach ($all_mp3 as $mp3) {
				// echo($mp3->id);



				$is_present = in_array($mp3->version, $versions);

				if ($is_present) {
					$mp3->other_version = '';
				} else {
					$mp3->other_version = urldecode($mp3->version);
				}

				if (ctype_digit($mp3->location)) {
					$mp3->audio_url = route('audio_stream_pcloud', ['id'=>Crypt::encryptString($mp3->location)]);
					$mp3->audio_delete_url =  route('remove_track_pcloud', ['id'=>Crypt::encryptString($mp3->location)]);
				} else {
					$path_audio_url = "https://app.digiwaxx.com/AUDIO/" . $mp3->location;
					$path_audio_delete_url = "https://app.digiwaxx.com/AUDIO/" . $mp3->location;

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


			// return view("admin.add_audio_files", $output);
			if ($track) {
				return view("admin.add_audio_files", $output);
			} else {
				// return "<h2>Track not Found</h2>";
				return view("admin.not_found", $output);
			}
		} catch (DecryptException $e) {
			// return redirect()->back()->with('error', 'Invalid or tampered ID');
			return "<h2>Url Not Found</h2>";
		}
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

	public function upload(Request $request)
	{
		// echo("---".$request->track_id);
		// Path for guest upload
		$path = public_path('uploads/digi_pcloud');
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
		$output['pageTitle'] = 'Manage Mp3';
		$output['logo'] = $get_logo;
		$output['welcome_name'] = $admin_name;
		$output['user_role'] = $user_role;
		if ($request->hasFile('files')) {
			/*          $rules = array('fileupload'  => 'image');
			$data = array('image' => $request->file('fileupload'));
			// Validation
			$validation = Validator::make($data, $rules);
		if ($validation->fails()){
		  return Response::json('error', 400);
		}*/
			$data = array();
			$files =  $request->file('files');

			foreach ($files as $file) {
				// SECURITY FIX: Validate audio files
				$allowedAudioMimes = ['audio/mpeg', 'audio/mp3', 'audio/wav', 'audio/x-wav', 'audio/aac'];
				$allowedAudioExtensions = ['mp3', 'wav', 'aac'];

				if (!in_array($file->getMimeType(), $allowedAudioMimes)) {
					throw new \Exception('Invalid audio file type. Only MP3, WAV, and AAC files are allowed.');
				}

				$extension = strtolower($file->getClientOriginalExtension());
				if (!in_array($extension, $allowedAudioExtensions)) {
					throw new \Exception('Invalid audio file extension.');
				}

				// Validate file size (max 50MB for audio)
				if ($file->getSize() > 52428800) {
					throw new \Exception('Audio file size must be less than 50MB.');
				}

				// Generate secure filename
				$filename = uniqid('audio_') . '_' . time() . '.' . $extension;

				// pcloud
				$filepath = $file->getRealPath();

				$pcloudFolder = new Folder($this->pCloudApp);

				// $folderId = $pcloudFolder->createfolderifnotexists("Tracks Meta");

				$parentFolderId =  env('PCLOUD_AUDIO_PATH');

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

				// echo($file_size);

				// print_r($fileMetadata);

				//	$trackSrc = $pcloudFile->getLink($pcloudFileId);

				## $trackSrc = $this->pcloudStreamAudioUri($pcloudFileId);

				// $pcloudFilePath = $pcloudFile->getPath($pcloudFileId);

				// echo($trackSrc);

				## echo $trackSrc;die;

				// pcloud

				if ($fileMetadata) {

					$added_date = date('Y-m-d H:i:s');

					$mp3_insert = DB::table('tracks_mp3s')->insertGetId([
						'track' => $trackid,
						'location' => $pcloudFileId,
						'title' => $pcloudFileName,
						'added' =>  $added_date,
						'addedby' =>  $admin_id,
					]);

					$all_mp3 = DB::table('tracks_mp3s')->where('track', $trackid)->get();

					// print_r($all_mp3);

					if ($mp3_insert) {
						// if($all_mp3){
						// 	echo('check');
						// 	$insertedId = 80822;
						// 	$info = new StdClass;
						// 	$info->name = 'new (16).mp3';
						// 	$info->mp3_id = $insertedId;
						// 	$info->id = 'new (16).mp3';
						// 	$info->size = 1.09;
						// 	$info->type = 'mp3';
						// 	$info->url = URL::to('admin/audio_stream_pcloud/' . 51044768587);
						// 	$info->audio_url = URL::to('admin/audio_stream_pcloud/' . 51044768587);;
						// 	$info->deleteUrl = URL::to('admin/remove_track_pcloud/' .  51044768587);
						// 	$info->deleteType = 'GET';
						// 	$info->error = null;
						// 	$data[] = $info;
						// }
						$insertedId = $mp3_insert;
						// echo "Inserted ID: $insertedId";
						$info = new StdClass;
						$info->name = $pcloudFileName;
						$info->mp3_id = $insertedId;
						$info->id = $pcloudFileName;
						$info->size = $file_size;
						$info->type = 'mp3';
						// $info->url = $trackSrc;
						$info->url = URL::to('admin/audio_stream_pcloud/' . Crypt::encryptString($pcloudFileId));
						$info->audio_url = URL::to('admin/audio_stream_pcloud/' . Crypt::encryptString($pcloudFileId));;
						$info->deleteUrl = URL::to('admin/remove_track_pcloud/' .  Crypt::encryptString($pcloudFileId));
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

		return view('admin.add_audio_files', $output);
	}


	// function delete($file)
	// {
	// 	$f_id = (int)$file;
	// 	$pcloudFile = new File($this->pCloudApp);

	// 	$fileMetadata = $pcloudFile->delete($f_id);

	// 	DB::table('tracks_mp3s')->where('location', $f_id)->delete();
	// 	return true;
	// }



	function delete($file)
	{
		// echo ($file);
		$f_id = (int)Crypt::decryptString($file);
		$pcloudFile = new File($this->pCloudApp);

		$fileMetadata = $pcloudFile->delete($f_id);

		DB::table('tracks_mp3s')->where('location', $f_id)->delete();

		/////////////////////////////
		// if (@$_GET['id']) {
		// 	$path = 'public/uploads/post';
		// 	$thumbs = $path . '/thumbs';
		// } else {
		// 	$path = public_path('uploads/digi_pcloud');
		// 	$thumbs = $path . '/thumbs';
		// }
		// if (file_exists($path . '/' . $file)) {
		// 	$success = unlink($path . '/' . $file);
		// 	## $success = unlink($thumbs.'/'.$file);
		// } else {
		// 	$success = false;
		// }
		// $info = new StdClass;
		// $info->sucess = $success;
		// $info->path = URL::to($path . '/' . $file);
		// $info->file = is_file($path . '/' . $file);
		/////////////////////////////

		return true;
	}

	public function pcloudStreamAudioUri($file_id)
	{

		// echo($file_id);
		// die;

		$fileID = (int)Crypt::decryptString($file_id);
		$pcloudFile = new File($this->pCloudApp);
		$filename = $pcloudFile->getLink($fileID);
		$fileInfo = $pcloudFile->getInfo($fileID);

		// echo($filename);die;

		if ($filename) {
			$fname = $fileInfo->metadata->name;
			$fsize = $fileInfo->metadata->size;
			ob_start();
			header('Content-Type: audio/mpeg');
			header('Content-Disposition: inline;filename="' . $fname . '"');
			header('Content-length: ' . $fsize);
			header('Cache-Control: no-cache');
			header("Content-Transfer-Encoding: chunked");
			// header('Accept-Ranges: bytes');

			/* ob_clean();        
            ob_start();
            ob_end_clean(); */

            //if (ob_get_contents()) ob_end_clean();

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

	public function save_mp3_track(Request $request)
	{
		// echo ("Save Mp3");
		// echo ($request->version);

		$versions = $request->input('version');

		try {
			$response = array();
			foreach ($versions as $mp3_id => $versionName) {

				$preview = 0;

				// echo 'Mp3 ID: ' . $mp3_id . ', Version Name: ' . $versionName;

				// $preview_input = 'preview'.(string)$mp3_id;
				// echo('<br>'.$preview_input);

				if ($request->has('preview') && $request->preview == $mp3_id) {
					// echo("Yes1");
					// echo($request->preview);
					$preview = 1;
				}

				if (!empty($request->otherversion[$mp3_id])) {
					$versionName = $request->otherversion[$mp3_id];
					// echo("Yes1");
					// echo($request->otherversion[$mp3_id]);
					// die;
				}


				$mp3_track = DB::table('tracks_mp3s')
					->where('id', $mp3_id)
					->update(['version' => $versionName, 'preview' => $preview]);

				if ($mp3_track) {
					// echo ("Mp3 Track Saved Successfully");
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

	public function admin_add_track_step3(Request $request)

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
			// $output['logos'] = $this->admin_model->getLogos_trm("");
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



			$track_data = DB::table('tracks')
				->where('id', Crypt::decryptString($request->id))
				->first();

			$track_data_logos = [];
			if (isset($track_data->logos) && !empty($track_data->logos)) {

				$track_data_logos = explode(",", $track_data->logos);
				// print_r($track_data_logos);

				// $output['track_data_logos'] = $track_data_logos;

			}
			// print_r($track_data);

			$all_logos = DB::table('logos')->select('*')->whereNotNull('company')->where('company', '!=', '')->orderBy('company', 'asc')->get();

			$logo_fileid = [];

			foreach ($all_logos as $logo) {
				$logo->company = urldecode($logo->company);

				if (in_array($logo->id, $track_data_logos)) {
					if ($logo->pCloudFileID_logo != null) {
						array_push($logo_fileid, "https://app.digiwaxx.com/admin/pcloud_fetch_image/" . $logo->pCloudFileID_logo);
					} else if ($logo->img != null) {
						$path = asset('public/Logos/' . $logo->img);
						if (file_exists($path)) {
							array_push($logo_fileid, $path);
						}
					}
				}
			}

			// print_r($logo_fileid);

			$output['pageTitle'] = 'Add New Track';
			$output['logo'] = $get_logo;
			$output['welcome_name'] = $admin_name;
			$output['user_role'] = $user_role;
			$output['track_id'] = $request->id;
			$output['all_logos'] = $all_logos;
			$output['track_data'] = $track_data;
			$output['track_data_logos'] = $track_data_logos;
			$output['logo_fileid'] = $logo_fileid;

			return view("admin.add_track_step3", $output);
		} catch (DecryptException $e) {
			// return redirect()->back()->with('error', 'Invalid or tampered ID');
			return "<h2>Url Not Found</h2>";
		}
	}

	public function save_admin_add_track_step3(Request $request)
	{
		$track_id = Crypt::decryptString($request->id);

		$admin_id = Auth::user()->id;

		try {
			$response = array();

			$Tracks = Tracks::find($track_id);
			$Tracks->updated_at = date('Y-m-d H:i:s');
			$Tracks->edited = date('Y-m-d H:i:s');
			$Tracks->addedby = $admin_id;
			$Tracks->editedby = $admin_id;
			$trackType = 'track';
 
 			if ($request->has('client') && !empty($request->client)) {
			    ## echo $request->client;die('--EDED');
				$trakclient = $request->client;
				
				DB::select("UPDATE `tracks` SET client = '" . $trakclient . "' WHERE id = '" . $track_id . "'");
			}else{
			    DB::select("UPDATE `tracks` SET client = '' WHERE id = '" . $track_id . "'");
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
					'track' => $track_id,
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
				
				$image = $request->file('logoImage');
				$imageName = $image->getClientOriginalName();
				$filepath = $image->getRealPath();

				$pcloudFolder = new Folder($this->pCloudApp);
				// $folderId = $pcloudFolder->createfolderifnotexists("Tracks Meta");
				$parentFolderId =  env('PCLOUD_AUDIO_PATH');

				$pcloudFile = new File($this->pCloudApp);
				$folderName = (string)$track_id;
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
					// print_r($request->logos);
					$track_logos = implode(",", $request->logos);
					// echo($track_logos);die;
					$Tracks->logos = $track_logos;
				}
			}

			$is_saved = $Tracks->save();

			// echo $track_id;die();


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

	public function admin_edit_track(Request $request)
	{
		try {
			$track_id = Crypt::decryptString($request->id);

			$Track = Tracks::where('id', $track_id)->first();
			// echo($Track->pCloudFileID);
			// dd($Track);

			$all_mp3 = DB::table('tracks_mp3s')->where('track', $track_id)->get();

			$versions = [
				"Acapella", "Clean", "Clean Accapella", "Clean (16 Bar Intro)", "Dirty", "Dirty Accapella",
				"Dirty (16 Bar Intro)", "Instrumental", "Main", "TV Track"
			];

			foreach ($all_mp3 as $mp3) {
				// echo($mp3->id);



				$is_present = in_array($mp3->version, $versions);

				if ($is_present) {
					$mp3->other_version = '';
				} else {
					$mp3->other_version = urldecode($mp3->version);
				}

				if (ctype_digit($mp3->location)) {
					$mp3->audio_url = route('audio_stream_pcloud', ['id'=>Crypt::encryptString($mp3->location)]) ;
					$mp3->audio_delete_url = route('remove_track_pcloud', ['id'=>Crypt::encryptString($mp3->location)]);
					// "https://app.digiwaxx.com/admin/remove_track_pcloud/" .  Crypt::encryptString($mp3->location);
				} else {
					$path_audio_url = "https://app.digiwaxx.com/AUDIO/" . $mp3->location;
					$path_audio_delete_url = "https://app.digiwaxx.com/AUDIO/" . $mp3->location;

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
			// $output['logos'] = $this->admin_model->getLogos_trm("");
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
			$output['pageTitle'] = 'Edit Track';
			$output['logo'] = $get_logo;
			$output['welcome_name'] = $admin_name;
			$output['user_role'] = $user_role;
			$output['Track'] = $Track;
			$output['track_id'] = $track_id;

			$output['all_mp3'] = $all_mp3;

			//echo '<pre>';print_r($output);die;

			return view("admin.edit_track", $output);
		} catch (DecryptException $e) {
			// return redirect()->back()->with('error', 'Invalid or tampered ID');
			return "<h2>Url Not Found</h2>";
		}
	}

	public function save_admin_edit_track(Request $request)
	{
		try {
			$track_id = Crypt::decryptString($request->track_id);
			$response = array();

			$isDuplicate = Tracks::where('artist', $request->artist)
				->where('title', $request->title)->get();

			if (count($isDuplicate) !== 0) {
				foreach ($isDuplicate as $track) {
					// echo($track->id."<br>".$track_id);
					if ($track->id != $track_id) {
						$responseRet = array('status' => 'duplicate', 'artist' => $request->artist, 'title' => $request->title, 'duplicateId' => '', 'message' => 'The track with this artist and title already exists.');
						return response()->json($responseRet);
					}
				}
			}

			$Tracks = Tracks::where('id', $track_id)->first();
			// $Tracks->added = date('Y-m-d H:i:s');
			$Tracks->edited = date('Y-m-d H:i:s');
			// $Tracks->created_at = date('Y-m-d H:i:s');
			$Tracks->updated_at = date('Y-m-d H:i:s');
			$trackType = 'track';

			if ($request->has('artist') && !empty($request->artist)) {
				$Tracks->artist = $request->artist;
			}
			if ($request->has('title') && !empty($request->title)) {
				$Tracks->title = $request->title;
			}
			if ($request->has('time') && !empty($request->time)) {
				$Tracks->time = $request->time;
			}
			if ($request->has('status') && !empty($request->status)) {
				$Tracks->status = $request->status;
			}

			$is_saved = $Tracks->save();

			if ($is_saved) {

				$trackid = $Tracks->id;
				$TracksUpdate = Tracks::where('id', '=',  $trackid)->first();


				if ($request->hasFile('pageImage')) {

					// SECURITY FIX: Validate file upload properly
					$image = $request->file('pageImage');

					// Validate file type - only allow images
					$allowedMimes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
					$allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

					if (!in_array($image->getMimeType(), $allowedMimes)) {
						throw new \Exception('Invalid file type. Only images are allowed.');
					}

					// Validate file extension
					$extension = strtolower($image->getClientOriginalExtension());
					if (!in_array($extension, $allowedExtensions)) {
						throw new \Exception('Invalid file extension.');
					}

					// Validate file size (max 5MB)
					if ($image->getSize() > 5242880) {
						throw new \Exception('File size must be less than 5MB.');
					}

					// Generate secure random filename instead of using client-provided name
					$imageName = uniqid('track_') . '_' . time() . '.' . $extension;
					$filepath = $image->getRealPath();

					$pcloudFolder = new Folder($this->pCloudApp);
					// $folderId = $pcloudFolder->createfolderifnotexists("Tracks Meta");
					$parentFolderId =  env('PCLOUD_AUDIO_PATH');

					$pcloudFile = new File($this->pCloudApp);
					$folderName = (string)$trackid;
					$folderId = $pcloudFolder->createFolderIfNotExists($folderName, $parentFolderId);

					$fileMetadata = $pcloudFile->upload($filepath, $folderId, $imageName);

					$pcloudFileName = $fileMetadata->metadata->name;
					$pcloudFileId = $fileMetadata->metadata->fileid;
					$parentfolderid = $fileMetadata->metadata->parentfolderid;

					$TracksUpdate->img = $pcloudFileName;
					$TracksUpdate->imgpage = $pcloudFileName;
					$TracksUpdate->pCloudFileID = $pcloudFileId;
					$TracksUpdate->pCloudParentFolderID = $parentfolderid;

					$TracksUpdate->save();
				} else {
					// $TracksUpdate->img = $pcloudFileName;
					// $TracksUpdate->imgpage = $pcloudFileName;
					// $TracksUpdate->pCloudFileID = $pcloudFileId;
					// $TracksUpdate->pCloudParentFolderID = $parentfolderid;
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
}
