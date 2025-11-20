<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Route;
use Hash;
use Session;
use File;
use pCloud;
// use Intervention\Image\ImageManagerStatic as Image;

// for mail sending
use Illuminate\Support\Facades\Mail;
use App\Mail\AdminForgetNotification;

class ClientDashboardController extends Controller
{
	protected $clientAllDB_model;

	public function __construct()
	{
		// if(empty(Session::get('memberId'))){
		// 	return redirect()->intended('login');
		// }

		$this->clientAllDB_model = new \App\Models\ClientAllDB;
		$this->memberAllDB_model = new \App\Models\MemberAllDB;

	}
	
	public function viewClient_dashboard(Request $request)
	{
		if(empty(Session::get('clientId'))){
			return redirect()->intended('login');
		}
		if(!empty(Session::get('tempClientId'))){

			 $output['welcomeMsg'] = 'Thank you for updating your information !';
			 
			 Session::forget('tempClientId');
		}
		$output = array();
        $output['pageTitle'] = 'Digiwax Client Dashboard';
        $output['packageId'] = 2;
		
        $output['displayDashboard'] = 1;
		
		$clientId = Session::get('clientId');
		$output['sessClientID'] = $clientId;
		
        $countriesWhere = '';
        $countries = $this->clientAllDB_model->getCountries($countriesWhere);
        $countryCodes = array();
        foreach ($countries['data'] as $country) {
            $countryCodes[] = $country->abbr;
        }
        $countryCodes = array_unique(array_filter($countryCodes));
		
        //Removed subscription option
        // $subscriptionInfo = $this->frontenddb->getSubscriptionStatus($_SESSION['clientId']);
        // if ($subscriptionInfo['numRows'] > 0) {
        //     $headerOutput['subscriptionStatus'] = 1;
        //     if ($subscriptionInfo['data'][0]->packageId == 1) {
        //         $headerOutput['packageId'] = 1;
        //         $headerOutput['package'] = 'BASIC SUBSCRIPTION';
        //         $headerOutput['displayDashboard'] = 0;
        //         header("location: " . url("Client_tracks"));
        //         exit;
        //     } else if ($subscriptionInfo['data'][0]->packageId == 2) {
        //         $headerOutput['packageId'] = 2;
        //         $headerOutput['package'] = 'ADVANCED SUBSCRIPTION';
        //         $headerOutput['displayDashboard'] = 1;
        //     }
        // } else {
        //     $headerOutput['packageId'] = 0;
        //     $headerOutput['subscriptionStatus'] = 0;
        //     $headerOutput['package'] = '';
        //     $headerOutput['displayDashboard'] = 0;
        //     header("location: " . url("Client_tracks"));
        //     exit;
        // }
		
        //	$table = 'tracks_reviews'; 
        $textItems = array();
        // foreach ($countryCodes as $code) {
        //     $where = "where tracks.client = '" . $_SESSION['clientId'] . "' and
        //     (
        //     ( tracks_reviews.countryCode = '" . $code . "' )
        //     or
        //     ( tracks_reviews.additionalcomments != '' and tracks_reviews.countryCode = '" . $code . "' )
        //     or
        //     ( track_member_downloads.downloadedCountryCode = '" . $code . "' )
        //     or	
        //     ( track_member_play.countryCode = '" . $code . "' )
        //     )
        //     ";
        //     // $data = $this->frontenddb->getDashboardDataCount((int) $_SESSION['clientId'], $code);
        //     // $data = $this->frontenddb->getDashboardData($where);

        //     if ($data > 0) {
        //         $textItems[] = "'" . $code . "': '#edd2e3'";
        //     }
        // }

        if (count($textItems) > 1) {
            $output['text'] = implode(',', $textItems);
        } else if (count($textItems) == 1) {
            $output['text'] = $textItems[0];
        } else {
            $output['text'] = '';
        }

		$output['rightTracks'] = $this->clientAllDB_model->getRightTracks($clientId);
		
		if($output['rightTracks']['numRows'] > 0){
			foreach ($output['rightTracks']['data'] as $rightTrack) {
				 $output['trackData'][$rightTrack->id] = $this->clientAllDB_model->getTrackPlays($rightTrack->id);
			}
		}
		
        $output['tracks'] = $this->clientAllDB_model->getClientFooterTracks($clientId);
        $unReadMessages = $this->clientAllDB_model->getClientUnreadInbox($clientId);
        $output['numMessages']  = $unReadMessages['numRows'];
        $headerOutput['numMessages']  = $unReadMessages['numRows'];
        $headerOutput['tracks'] = $output['tracks'];

        $udata = $this->clientAllDB_model->getClientsDetails($clientId);
		$output['memeberinfo'] = $udata;
		
		return view('clients.dashboard.Client_dashboard', $output);
	}
	
	
	
	
	public function viewClientEditProfile(Request $request){
		
		if(empty(Session::get('clientId'))){
			return redirect()->intended('login');
		}
		if(!empty(Session::get('tempClientId'))){

			 $output['welcomeMsg'] = 'Thank you for updating your information !';
			 
			 Session::forget('tempClientId');
		}
		$output = array();
        $output['pageTitle'] = 'Digiwax Client Info';
		
        $output['packageId'] = 2;
        $output['displayDashboard'] = 1;
		
		$clientId = Session::get('clientId');
		$output['sessClientID'] = $clientId;
		
		$output['rightTracks'] = $this->clientAllDB_model->getRightTracks($clientId);
		if($output['rightTracks']['numRows'] > 0){
			foreach ($output['rightTracks']['data'] as $rightTrack) {
				 $output['trackData'][$rightTrack->id] = $this->clientAllDB_model->getTrackPlays($rightTrack->id);
			}
		}
		
		$output['clientInfo'] = $this->clientAllDB_model->getClientInfo($clientId); 

		$output['clientImage'] = $this->clientAllDB_model->getClientImage($clientId); 

		$output['socialInfo'] = $this->clientAllDB_model->getClientSocialInfo($clientId);
		
		if(isset($_POST['updateClient'])){
			
			  if (!file_exists("./client_images/".$clientId."/")){

				  mkdir("./client_images/".$clientId);

				}
				
				if(isset($_FILES['profileImage']['name']) && strlen($_FILES['profileImage']['name'])>3){		

					$date_time_array = getdate(time());

					$artWorkImageName =	$date_time_array["year"].$date_time_array["mon"].$date_time_array["mday"].$date_time_array["hours"].$date_time_array["minutes"].$date_time_array["seconds"];

					$profileImage = $request->file('profileImage') ;

					//Display File Name
					$filename = $profileImage->getClientOriginalName();
					// echo 'File Name: '.$filename;
				  
					
					//Display File Extension
					$file_extension = $profileImage->getClientOriginalExtension();
					// echo 'File Extension: '.$file_extension;
				  
					
					//Display File Real Path
					$real_path = $profileImage->getRealPath();
					// echo 'File Real Path: '.$real_path;
				  
					
					//Display File Size
					$file_size = $profileImage->getSize();
					// echo 'File Size: '.$file_size;
				  
					
					//Display File Mime Type
					$file_mime_type = $profileImage->getMimeType();
					// echo 'File Mime Type: '.$file_mime_type;
				  
					$destination_path = base_path("./client_images/".$clientId."/");
					//die($destination_path);

					//Display Destination Path
					if(empty($destination_path)){
					  $destination_path = public_path('uploads/');
					} else {
					  $destination_path = $destination_path;
					}

					// // echo 'File Destination Path: '.$destination_path;
					// if(!File::isDirectory($destination_path)) {
					//     File::makeDirectory($destination_path, 0777, true, true);
					// }

					$image_name = $clientId."_".$artWorkImageName . '.'.$file_extension;
				    

					// $image_name = preg_replace('/[^a-zA-Z0-9_.]/', '_', $image_name);

					$uploaded_data = $profileImage->move( $destination_path , $image_name );

					if( !empty( $uploaded_data )){
					    
					    $pcloud_folder_query = DB::select("SELECT pCloudParentFolderID_client_image  FROM client_images where clientId = '" . $clientId. "' order by imageId desc limit 1");    
				    
    				    $data_pcloud=$pcloud_folder_query;
    				    
    	               if(!empty($data_pcloud[0]->pCloudParentFolderID_client_image)){
    	                   
    	                    $folder = $data_pcloud[0]->pCloudParentFolderID_client_image;  // PCLOUD_FOLDER_ID
                        // $metadata1=$this->pCloudCreateFolder()
    
        				    
    	               }
    	               else{
    	                   $folderid='13231321329'; // pcloud folder id
    	                   $foldername = $clientId;
    	                   $foldername = (string)$foldername;
    	                   //echo $foldername;die();
    	                   
    	                   $folder=$this->pCloudCreateFolder($folderid, $foldername);
    	                   
    	               }
    	               
    	                $metadata = $this->uploadImage_to_pcloud($destination_path, $image_name, $folder);
                    	
                    	$pcloudFileId = $metadata->metadata->fileid;
                    	$parentfolderid = $metadata->metadata->parentfolderid;
                    	@unlink($destination_path.$image_name);
				    

						$this->clientAllDB_model->addClientImage($image_name,$clientId,$pcloudFileId,$parentfolderid); 
						
						if(!empty($pcloudFileId)){
    					  $image_name = $pcloudFileId;
    					}
						Session::put('clientImage', $image_name);

					}
				}
				$result = $this->clientAllDB_model->updateClientInfo($_POST,$clientId);		   

				if($result>0){

					return Redirect::to("Client_edit_profile?updated=1");   exit;			 

			   }else{

					return Redirect::to("Client_edit_profile?error=1");   exit;

			   }
				
		 }
		 
		 if(isset($_GET['updated'])){

			  

			   $output['alert_class'] = 'success-msg';

			   $output['alert_message'] = 'Client Information updated successfully !';

			

		}else if(isset($_GET['error'])){

			   $output['alert_class'] = 'error-msg';

			   $output['alert_message'] = 'Error occured, please try again.';

		}		
		
		$output['tracks'] = $this->clientAllDB_model->getClientFooterTracks($clientId);
		
		return view('clients.dashboard.ClientEditProfile', $output);
	}
	
	public function viewClientChangePasssword(){
		if(empty(Session::get('clientId'))){
			return redirect()->intended('login');
		}
		if(!empty(Session::get('tempClientId'))){

			 $output['welcomeMsg'] = 'Thank you for updating your information !';
			 
			 Session::forget('tempClientId');
		}
		$output = array();
        $output['pageTitle'] = 'Digiwax Client Change Passsword';
		
        $output['packageId'] = 2;
        $output['displayDashboard'] = 1;
		
		$clientId = Session::get('clientId');
		$output['sessClientID'] = $clientId;
		$output['rightTracks'] = $this->clientAllDB_model->getRightTracks($clientId);
		if($output['rightTracks']['numRows'] > 0){
			foreach ($output['rightTracks']['data'] as $rightTrack) {
				 $output['trackData'][$rightTrack->id] = $this->clientAllDB_model->getTrackPlays($rightTrack->id);
			}
		}
		
		
		if(isset($_POST['changePassword'])){		 

		   $confirmResult = $this->clientAllDB_model->confirmClientCurrentPassword($_POST['currentPassword'], $clientId); 
		   

		   if($confirmResult>0){		 

			   $result = $this->clientAllDB_model->updateClientPassword($_POST['password'], $clientId); 			   

			   if($result>0){
				   
				    $my_details=$this->clientAllDB_model->getClientInfo($clientId)['data'][0];
					if(!empty($my_details->email)){
						$to_email = urldecode($my_details->email);
						//$to_email = 'gssingh@yopmail.com'; // Comment or Remove it for move to production mode				
						$m_sub = 'Digiwaxx - Password Change Notification';
						
						$m_msg = "Hi ".urldecode($my_details->name).",<br><br>This is to notify you that your account paasword has been changed on your request.<br><br>Digiwaxx Admin Team";
						$mail_data = [
							  'm_sub' => $m_sub,
							  'm_msg' => $m_msg,
						  ];
						
						$sendInvoiceMail = Mail::to($to_email);
						$sendInvoiceMail->send(new AdminForgetNotification($mail_data));
						/* $this->email->send(); */
					}

					return Redirect::to("Client_change_password?updated=1");   exit;			 

			   }else{

					return Redirect::to("Client_change_password?error=1");   exit;

			   }
		   }else{

				return Redirect::to("Client_change_password?wrongPassword=1");   exit;

		   }

		   

		}
		
		if(isset($_GET['updated'])){

			   $output['alert_class'] = 'success-msg';

			   $output['alert_message'] = 'Password updated successfully !';

			

		}else if(isset($_GET['error'])){

			   $output['alert_class'] = 'error-msg';

			   $output['alert_message'] = 'Error occured, please try again.';

			}

			else if(isset($_GET['wrongPassword']))				

			{

			   $output['alert_class'] = 'error-msg';

			   $output['alert_message'] = 'Entered current password is incorrect.';

			}
			
			
			$output['tracks'] = $this->clientAllDB_model->getClientFooterTracks($clientId);
		
		return view('clients.dashboard.ClientChangePasssword', $output);
		
	}
	
	public function viewClienDashboardRated(){
		if(empty(Session::get('clientId'))){
			return redirect()->intended('login');
		}
		if(!empty(Session::get('tempClientId'))){

			 $output['welcomeMsg'] = 'Thank you for updating your information !';
			 
			 Session::forget('tempClientId');
		}
		$output = array();
        $output['pageTitle'] = 'Digiwax Client Dashboard Rated';
		
        $output['packageId'] = 2;
        $output['displayDashboard'] = 1;
		
		$clientId = Session::get('clientId');
		$output['sessClientID'] = $clientId;
		$output['rightTracks'] = $this->clientAllDB_model->getRightTracks($clientId);
		
		if($output['rightTracks']['numRows'] > 0){
			foreach ($output['rightTracks']['data'] as $rightTrack) {
				 $output['trackData'][$rightTrack->id] = $this->clientAllDB_model->getTrackPlays($rightTrack->id);
			}
		}
		
		/**
         * removed subscription option
		$subscriptionInfo = $this->clientAllDB_model->getSubscriptionStatus($clientId); 
		if($subscriptionInfo['numRows']>0) {
		    $headerOutput['subscriptionStatus'] = 1;
            if($subscriptionInfo['data'][0]->packageId==1) {
    		    $headerOutput['packageId'] = 1;
    			$headerOutput['package'] = 'BASIC SUBSCRIPTION';
    			$headerOutput['displayDashboard'] = 0;
        		return Redirect::to("Client_tracks");   
        		exit;
		    }
		    else if($subscriptionInfo['data'][0]->packageId==2) {
		        $headerOutput['packageId'] = 2;
			    $headerOutput['package'] = 'ADVANCED SUBSCRIPTION';
			    $headerOutput['displayDashboard'] = 1;
		    }
		}
		else {
		   $headerOutput['packageId'] = 0;
		   $headerOutput['subscriptionStatus'] = 0;
		   $headerOutput['package'] = '';
		   $headerOutput['displayDashboard'] = 0;
		   return Redirect::to("Client_tracks");   
		   exit;
		}
        */
		
		$countriesWhere = '';
		$countries = $this->clientAllDB_model->getCountries($countriesWhere); 
		$countryCodes = array();
		foreach($countries['data'] as $country) {
          $countryCodes[] = $country->abbr;
		}
		
		$table = 'tracks_reviews';
		$textItems = array();
/* 		foreach($countryCodes as $code) {
		    $where = "where tracks.client = '". $clientId ."' and tracks_reviews.countryCode = '". $code ."'";
			$data = $this->clientAllDB_model->getDashboardRatedData($table,$where); 
		    if($data>0) {
			    $textItems[] = "'".$code."': '#e084bc'"; 
			}
		} */
		
        if(count($textItems)>1) {
    		$output['text'] = implode(',',$textItems);
		} 
		else if(count($textItems)==1) {
		    $output['text'] = $textItems[0];
		}
		else {
		    $output['text'] = '';
		 }		
		$output['tracks'] = $this->clientAllDB_model->getClientFooterTracks($clientId);
        $unReadMessages = $this->clientAllDB_model->getClientUnreadInbox($clientId);
        $output['numMessages']  = $unReadMessages['numRows'];
        $headerOutput['numMessages']  = $unReadMessages['numRows'];
        $headerOutput['tracks'] = $output['tracks'];
		
		return view('clients.dashboard.ClienDashboardRated', $output);
		
	}	
	
	public function viewClienDashboardDownloaded(){
		if(empty(Session::get('clientId'))){
			return redirect()->intended('login');
		}
		if(!empty(Session::get('tempClientId'))){

			 $output['welcomeMsg'] = 'Thank you for updating your information !';
			 
			 Session::forget('tempClientId');
		}
		$output = array();
        $output['pageTitle'] = 'Digiwax Client Dashboard Downloaded';
		
        $output['packageId'] = 2;
        $output['displayDashboard'] = 1;
		
		$clientId = Session::get('clientId');
		$output['sessClientID'] = $clientId;
		$output['rightTracks'] = $this->clientAllDB_model->getRightTracks($clientId);
		if($output['rightTracks']['numRows'] > 0){
			foreach ($output['rightTracks']['data'] as $rightTrack) {
				 $output['trackData'][$rightTrack->id] = $this->clientAllDB_model->getTrackPlays($rightTrack->id);
			}
		}
		
		$countriesWhere = '';
		$countries = $this->clientAllDB_model->getCountries($countriesWhere); 
		$countryCodes = array();
		foreach($countries['data'] as $country) {
          $countryCodes[] = $country->abbr;
		}
		
		$table = 'track_member_downloads';

		$textItems = array();
/* 		foreach($countryCodes as $code) {
		    $where = "where tracks.client = '". $clientId ."' and track_member_downloads.downloadedCountryCode = '". $code ."'";
			$data = $this->clientAllDB_model->getDashboardDownloadData($table,$where); 
		    if($data>0) {
			  $textItems[] = "'".$code."': '#fc048c'"; 
			} 
		} */

		if(count($textItems)>1) {
		    $output['text'] = implode(',',$textItems);
		 } 
		 else if(count($textItems)==1) {
		    $output['text'] = $textItems[0];
		 }
		 else {
		    $output['text'] = '';
		 }		
		
		$output['tracks'] = $this->clientAllDB_model->getClientFooterTracks($clientId);
        $unReadMessages = $this->clientAllDB_model->getClientUnreadInbox($clientId);
        $output['numMessages']  = $unReadMessages['numRows'];
        $headerOutput['numMessages']  = $unReadMessages['numRows'];
        $headerOutput['tracks'] = $output['tracks'];
		
		return view('clients.dashboard.ClienDashboardDownloaded', $output);
		
	}	
	public function viewClienDashboardCommented(){
		if(empty(Session::get('clientId'))){
			return redirect()->intended('login');
		}
		if(!empty(Session::get('tempClientId'))){

			 $output['welcomeMsg'] = 'Thank you for updating your information !';
			 
			 Session::forget('tempClientId');
		}
		$output = array();
        $output['pageTitle'] = 'Digiwax Client Dashboard Commented';
		
        $output['packageId'] = 2;
        $output['displayDashboard'] = 1;
		
		$clientId = Session::get('clientId');
		$output['sessClientID'] = $clientId;
		$output['rightTracks'] = $this->clientAllDB_model->getRightTracks($clientId);
		if($output['rightTracks']['numRows'] > 0){
			foreach ($output['rightTracks']['data'] as $rightTrack) {
				 $output['trackData'][$rightTrack->id] = $this->clientAllDB_model->getTrackPlays($rightTrack->id);
			}
		}
		
		$countriesWhere = '';
		$countries = $this->clientAllDB_model->getCountries($countriesWhere); 
		$countryCodes = array();
		foreach($countries['data'] as $country) {
          $countryCodes[] = $country->abbr;
		}
		
		$table = 'tracks_reviews';

		$textItems = array();
/* 		foreach($countryCodes as $code) {
		    $where = "where tracks.client = '". $clientId ."' and tracks_reviews.additionalcomments != '' and tracks_reviews.countryCode = '". $code ."'";
			$data = $this->clientAllDB_model->getDashboardDownloadData($table,$where); 
		    if($data>0) {
			  $textItems[] = "'".$code."': '#fc048c'"; 
			} 
		} */

		if(count($textItems)>1) {
		    $output['text'] = implode(',',$textItems);
		 } 
		 else if(count($textItems)==1) {
		    $output['text'] = $textItems[0];
		 }
		 else {
		    $output['text'] = '';
		 }		
		
		$output['tracks'] = $this->clientAllDB_model->getClientFooterTracks($clientId);
        $unReadMessages = $this->clientAllDB_model->getClientUnreadInbox($clientId);
        $output['numMessages']  = $unReadMessages['numRows'];
        $headerOutput['numMessages']  = $unReadMessages['numRows'];
        $headerOutput['tracks'] = $output['tracks'];
		
		return view('clients.dashboard.ClienDashboardCommented', $output);
		
	}
	
	

	public function viewClienDashboardPlayed(){
		if(empty(Session::get('clientId'))){
			return redirect()->intended('login');
		}
		if(!empty(Session::get('tempClientId'))){

			 $output['welcomeMsg'] = 'Thank you for updating your information !';
			 
			 Session::forget('tempClientId');
		}
		$output = array();
        $output['pageTitle'] = 'Digiwax Client Dashboard Played';
		
        $output['packageId'] = 2;
        $output['displayDashboard'] = 1;
		
		$clientId = Session::get('clientId');
		$output['sessClientID'] = $clientId;
		$output['rightTracks'] = $this->clientAllDB_model->getRightTracks($clientId);
		if($output['rightTracks']['numRows'] > 0){
			foreach ($output['rightTracks']['data'] as $rightTrack) {
				 $output['trackData'][$rightTrack->id] = $this->clientAllDB_model->getTrackPlays($rightTrack->id);
			}
		}
		
		$countriesWhere = '';
		$countries = $this->clientAllDB_model->getCountries($countriesWhere); 
		$countryCodes = array();
		foreach($countries['data'] as $country) {
          $countryCodes[] = $country->abbr;
		}
		
		$table = 'track_member_play';

		$textItems = array();
/* 		foreach($countryCodes as $code) {
		    $where = "where tracks.client = '". $clientId ."' and track_member_play.countryCode = '". $code ."'";
			$data = $this->clientAllDB_model->getDashboardDownloadData($table,$where); 
		    if($data>0) {
			  $textItems[] = "'".$code."': '#fc048c'"; 
			} 
		} */

		if(count($textItems)>1) {
		    $output['text'] = implode(',',$textItems);
		 } 
		 else if(count($textItems)==1) {
		    $output['text'] = $textItems[0];
		 }
		 else {
		    $output['text'] = '';
		 }		
		
		$output['tracks'] = $this->clientAllDB_model->getClientFooterTracks($clientId);
        $unReadMessages = $this->clientAllDB_model->getClientUnreadInbox($clientId);
        $output['numMessages']  = $unReadMessages['numRows'];
        $headerOutput['numMessages']  = $unReadMessages['numRows'];
        $headerOutput['tracks'] = $output['tracks'];
		
		return view('clients.dashboard.ClienDashboardPlayed', $output);
		
	}

	public function viewClientTrackReview(){
		if(empty(Session::get('clientId'))){
			return redirect()->intended('login');
		}
		if(empty($_GET['tId'])){
			return redirect()->intended('Client_tracks');
		}
		if(!empty(Session::get('tempClientId'))){

			 $output['welcomeMsg'] = 'Thank you for updating your information !';
			 
			 Session::forget('tempClientId');
		}
		$output = array();
        $output['pageTitle'] = 'Digiwax Client Track Review';
		
		$clientId = Session::get('clientId');
		$output['sessClientID'] = $clientId;
		
		$output['currentPage'] = 'Client_track_review?tId='.$_GET['tId'];
		
		// pagination

		 if(isset($_GET['commentPage'])){

		 $limit = 100;

		/*if(isset($_GET['records']))

		{

		  $limit = $_GET['records'];

		}

		$output['numRecords'] = $limit;

		*/

		$start = 0; 	

		$currentPageNo = 1;		

		if(isset($_GET['commentPage']) && $_GET['commentPage']>1){

			$start = ($_GET['commentPage']*$limit)-$limit;  

		}
		if(isset($_GET['commentPage'])){

			$currentPageNo = $_GET['commentPage']; 

		}	

		$num_records = $this->clientAllDB_model->getNumTrackComments($_GET['tId']); 

		$numPages = (int)($num_records/$limit); 

		$reminder = ($num_records%$limit);	 

		if($reminder>0){

			 $numPages = $numPages+1;

		}	

		$output['numPages'] = $numPages;

		$output['start'] = $start;

		$output['currentPageNo'] = $currentPageNo; 

	 if($currentPageNo==1){

	  $output['firstPageLink'] = 'disabled';

	  $output['preLink'] = 'disabled';  

	  $output['nextLink'] = '';

	  $output['lastPageLink'] = '';  

	}else if($currentPageNo==$numPages){

	  $output['firstPageLink'] = '';

	  $output['preLink'] = '';

	  $output['nextLink'] = 'disabled';

	  $output['lastPageLink'] = 'disabled';
	}else{

      $output['firstPageLink'] = '';

	  $output['preLink'] = '';

	  $output['nextLink'] = '';

	  $output['lastPageLink'] = '';
   }   

		$output['comments'] = $this->clientAllDB_model->getTrackComments($_GET['tId'],$start,$limit); 

	
		return view('clients.dashboard.ClientTrackReviewComments', $output);
		
		exit;
	}
	
		// remove comment

		 if(isset($_GET['removeComment']) && isset($_GET['commentId'])){		 

		    $result = $this->clientAllDB_model->removeTrackComment($_GET['commentId'],$_SESSION['clientId']);			

			$arr = array('status' => $result);

			echo json_encode($arr);			

			exit;
		 }
		 
		// reviwe info

		 if(isset($_GET['popup']) && isset($_GET['reviewId'])){

			$popupOutput['reviews'] = $this->clientAllDB_model->getTrackReviewsByReviewId($_GET['reviewId']);			

			return view('clients.dashboard.track_review_popup', $popupOutput);	

			exit;		 

		 }
		 
		 		 // member info

		 if(isset($_GET['memberId'])){

			$memberOutput['members'] = $this->memberAllDB_model->getMemberInfo($_GET['memberId']); 		

			$memberOutput['production'] = $this->memberAllDB_model->getMemberProductionInfo($_GET['memberId']); 		

			$memberOutput['special'] = $this->memberAllDB_model->getMemberSpecialInfo($_GET['memberId']); 		

			$memberOutput['promoter'] = $this->memberAllDB_model->getMemberPromoterInfo($_GET['memberId']); 

			$memberOutput['clothing'] = $this->memberAllDB_model->getMemberClothingInfo($_GET['memberId']); 		

			$memberOutput['management'] = $this->memberAllDB_model->getMemberManagementInfo($_GET['memberId']); 			

			$memberOutput['record'] = $this->memberAllDB_model->getMemberRecordInfo($_GET['memberId']); 			

			$memberOutput['media'] = $this->memberAllDB_model->getMemberMediaInfo($_GET['memberId']); 			

			$memberOutput['radio'] = $this->memberAllDB_model->getMemberRadioInfo($_GET['memberId']); 			

			$memberOutput['socialInfo'] = $this->memberAllDB_model->getMemberSocialInfo($_GET['memberId']);			

			return view('MemberInfoDisplay',$memberOutput);			

			exit;		 

		 }
		 
		 $output['reviews'] = $this->clientAllDB_model->getTrackReviews($_GET['tId']); 
		 
		// comments

		 $limit = 60;		

		/*if(isset($_GET['records']))

		{

		  $limit = $_GET['records'];

		}

		$output['numRecords'] = $limit;

		*/

		$start = 0; 

		$currentPageNo = 1;

		/*

		$currentPageNo = 1;

		

		if(isset($_GET['page']) && $_GET['page']>1)

		 {

			$start = ($_GET['page']*$limit)-$limit;  

		 }

		 

		

		 if(isset($_GET['page']))

		 {

			$currentPageNo = $_GET['page']; 

		 }*/

	

      $num_records = $this->clientAllDB_model->getNumTrackComments($_GET['tId']); 

	  $numPages = (int)($num_records/$limit); 

	  $reminder = ($num_records%$limit);
	  
	 if($reminder>0){

		 $numPages = $numPages+1;

	 }	

	 $output['numPages'] = $numPages;

	 $output['start'] = $start;

	 $output['currentPageNo'] = $currentPageNo;
	 
	  if($currentPageNo==1)

  {

	  $output['firstPageLink'] = 'disabled';

	  $output['preLink'] = 'disabled';  

	  $output['nextLink'] = '';

	  $output['lastPageLink'] = '';

	  

	  

  }

  else if($currentPageNo==$numPages)

  {

	  $output['firstPageLink'] = '';

	  $output['preLink'] = '';

	  $output['nextLink'] = 'disabled';

	  $output['lastPageLink'] = 'disabled';

	  

	  

  }

  else

  {

      $output['firstPageLink'] = '';

	  $output['preLink'] = '';

	  $output['nextLink'] = '';

	  $output['lastPageLink'] = '';

  }
	$output['comments'] = $this->clientAllDB_model->getTrackComments($_GET['tId'],$start,$limit); 
	
	    $output['packageId'] = 2;
        $output['displayDashboard'] = 1;
        $output['displayReviews'] = 1;
		$output['tId'] = $_GET['tId'];

		//dd($output);

		$output['rightTracks'] = $this->clientAllDB_model->getRightTracks_cld(); 
	
			
	
			foreach($output['rightTracks']['data'] as $rightTrack)
	
			{
	
				$output['trackData'][$rightTrack->id] = $this->clientAllDB_model->getTrackPlays_cld($rightTrack->id); 
	
			}
		
		return view('clients.dashboard.ClientTrackReview', $output);
		
	}

	// New client controller function R-s starts here

	public function Client_tracks(){

		//dd('ggdfdfd');

		// fb logout link
		//   $this->load->library('facebook');
		//   $logout_link = url('Logout');

		  

		//   if(isset($_SESSION['fb_access_token']))
		//    {
		//      $logout_link = $this->facebook->logout_url();	
		//    }
		   
		//   $headerOutput['logout_link'] = $logout_link;

		if(empty(Session::get('clientId'))){
			return redirect()->intended('login');
		}
		if(!empty(Session::get('tempClientId'))){

			 $output['welcomeMsg'] = 'Thank you for updating your information !';
			 
			 Session::forget('tempClientId');
		}
		$output = array();
        $output['pageTitle'] = 'Digiwax Client Tracks';
        $output['packageId'] = 2;
		
        $output['displayDashboard'] = 1;
		
		$clientId = Session::get('clientId');
		$output['sessClientID'] = $clientId;

		  
		   // logo 
		 $headerOutput['logo'] = $this->clientAllDB_model->getLogo_cld(); 

		$headerOutput['wrapperClass'] = 'client';

		// removing client Subscription
		// $subscriptionInfo = $this->clientAllDB_model->getSubscriptionStatus_cld($clientId); 

		// if($subscriptionInfo['numRows']>0)

		// {

		//   $headerOutput['subscriptionStatus'] = 1;

		//   if($subscriptionInfo['data'][0]->packageId==1)

		//   {

		//     $headerOutput['packageId'] = 1;

		//     $headerOutput['package'] = 'BASIC';

		// 	$headerOutput['displayDashboard'] = 0;

			

		// 	// 			$output['packageId'] = 1;

		//   }

		//   else if($subscriptionInfo['data'][0]->packageId==2)

		//   {

		//     $headerOutput['packageId'] = 2;

		//     $headerOutput['package'] = 'ADVANCED';

		// 	$headerOutput['displayDashboard'] = 1;

			

		// 	// $output['packageId'] = 2;

		//   }

		// }

		// else

		// {

		//    $headerOutput['packageId'] = 0;

		//    $headerOutput['subscriptionStatus'] = 0;

		//    $headerOutput['package'] = '';

		//    $headerOutput['displayDashboard'] = 0;

		   

		//    // $output['packageId'] = 0;

		// }
         

        $headerOutput['packageId'] = 2;
        $headerOutput['displayDashboard'] = 1;
		 

		

		// generate where

		/*$where = "where client =  '".$clientId."' and deleted = '0' and approved = '1'";

		$where1 = "where client =  '".$clientId."' and deleted = '0' and approved = '0'";

		*/

		

		$where = "where tracks.client =  '".$clientId."' and tracks.deleted = '0'";

		

		

		

		// generate sort

		

		

		$sortOrder = "DESC";

		$sortBy = "tracks.added";

		$output['sortBy'] = 'date';

		$output['sortOrder'] = 2;

		

		if(isset($_GET['sortBy']) && isset($_GET['sortOrder']))

		{

		   $output['sortBy'] = $_GET['sortBy'];

		   $output['sortOrder'] = $_GET['sortOrder'];

		   

		   

		   if(strcmp($_GET['sortBy'],'artist')==0)

		   {

		    

			 $sortBy = "tracks.artist";

		   }

		   else if(strcmp($_GET['sortBy'],'title')==0)

		   {

		    

			 $sortBy = "tracks.title";

		   }

		    else if(strcmp($_GET['sortBy'],'date')==0)

		   {

		    

			 $sortBy = "tracks.added";

		   }

		   

		   

		   if($_GET['sortOrder']==1)

		   {

		    

			 $sortOrder = "ASC";

		   }

		   else  if($_GET['sortOrder']==2)

		   {

		    

			 $sortOrder = "DESC";

		   }

		  

		  

		  

		  

		}

		$sort =  $sortBy." ".$sortOrder;

	

		// pagination

		$limit = 10;

		if(isset($_GET['records']))

		{

		  $limit = $_GET['records'];

		}

		$output['numRecords'] = $limit;

   		

		$start = 0; 

		$currentPageNo = 1;

		

		if(isset($_GET['page']) && $_GET['page']>1)

		 {

			$start = ($_GET['page']*$limit)-$limit;  

		 }

		 

		

		 if(isset($_GET['page']))

		 {

			$currentPageNo = $_GET['page']; 

		 }

		 

      $num_records = $this->clientAllDB_model->getNumClientTracks_cld($where,$sort); 

	  $numPages = (int)($num_records/$limit); 

	  $reminder = ($num_records%$limit);

	 

	 if($reminder>0)

	 {

		 $numPages = $numPages+1;

	 }

	

	 $output['numPages'] = $numPages;

	 $output['start'] = $start;

	 $output['currentPageNo'] = $currentPageNo;

	 $output['currentPage'] = 'Client_tracks';

	 

	 

	  if(isset($_GET['page'])) {

     if ($_GET['page'] > $numPages) {

         header("location: ".$output['currentPage']."?page=" . $numPages);

         exit;

     } else if ($_GET['page'] < 1) {

         header("location: ".$output['currentPage']."?page=1");

         exit;

     }

 }

 

 

 

  if($currentPageNo==1)

  {

	  $output['firstPageLink'] = 'disabled';

	  $output['preLink'] = 'disabled';  

	  $output['nextLink'] = '';

	  $output['lastPageLink'] = '';

	  

	  

  }

  else if($currentPageNo==$numPages)

  {

	  $output['firstPageLink'] = '';

	  $output['preLink'] = '';

	  $output['nextLink'] = 'disabled';

	  $output['lastPageLink'] = 'disabled';

	  

	  

  }

  else

  {

      $output['firstPageLink'] = '';

	  $output['preLink'] = '';

	  $output['nextLink'] = '';

	  $output['lastPageLink'] = '';

  }

  // pagination

  				   // generate url string

					 $getArray = array(); $urlString = '?';

					 if(isset($_GET))
					 {
					 foreach($_GET as $key=>$value)
					 {
						 if(strcmp($key,'page')!=0)
						 {
						 $getArray[] = $key.'='.$value;
						 }
					 }
		 
					 if(count($getArray)>1)
					 {
						 $urlString .= implode('&',$getArray);
					 }
					 else if(count($getArray)==1)
					 {
						 $urlString .= $getArray[0];
					 }
					 else
					 {
						 $urlString = '';
					 }
					 }
		 
					 $output['urlString'] = $urlString;
		 
					 // uncomment later
		 
					 if(isset($_GET['page'])) {
					 if(strlen($urlString)>3)
						 {
							 $param = '&';
						 }
						 else
						 {
							 $param = '?';
						 }
		 
					 if ($_GET['page'] > $numPages && $numPages > 0) {
							 header("location: ".$output['currentPage'].$urlString.$param."page=" . $numPages);
						 exit;
					 }
					 }

		

		$output['tracks'] = $this->clientAllDB_model->getClientTracks_cld($where,$start,$limit,$sort); 

		foreach($output['tracks']['data'] as $track)

		{

		  if($track->approved==1)

		  {

			$output['trackData'][$track->id] = $this->clientAllDB_model->getTrackPlays_cld($track->id); 

		  }

		  

		//  $output['feedback_videos'][$track->id] = $this->clientAllDB_model->getClientTrackFeedbackVideos($track->id); 

		}

		

		

		$output['rightTracks'] = $this->clientAllDB_model->getRightTracks($clientId); 

		

		foreach($output['rightTracks']['data'] as $rightTrack)

		{

			$output['trackData'][$rightTrack->id] = $this->clientAllDB_model->getTrackPlays_cld($rightTrack->id); 

		}


		$footerOutput['tracks'] = $this->clientAllDB_model->getClientFooterTracks_cld($clientId); 


       $unReadMessages = $this->clientAllDB_model->getClientUnreadInbox_cld($clientId); 

		 $output['numMessages']  = $unReadMessages['numRows'];

		 $headerOutput['numMessages']  = $unReadMessages['numRows'];


		  if(isset($_GET['trackAdded']))				

			{

			  

			   $output['alert_class'] = 'success-msg';

			   $output['alert_message'] = 'Thank you for submitting. Your track is now being processed and reviewed by our Admin Team';

			

			}

			else if(isset($_GET['error']))				

			{

			   $output['alert_class'] = 'error-msg';

			   $output['alert_message'] = 'Error occured, please try again.';

			}
			

		$headerOutput['tracks'] = $footerOutput['tracks'];
		$headerOutput['banner_ads'] = $this->clientAllDB_model->getBannerads_cld(1,1);
		foreach($output['tracks']['data'] as $data){
    		$query1 = DB::select("SELECT * FROM tracks_reviews where track = '" . $data->id . "'");
    		$data->review_count = count($query1);
    		
		}
// 		dd($output['tracks']['data']);

			

		// $this->load->view('header_client_top.php',$headerOutput);
		// $this->load->view('client_tracks.php',$output);
		// $this->load->view('footer_client_top.php',$footerOutput);

		//dd($output);

		return view('clients.dashboard.client_tracks', $output);
		
		
	}

// -----------------------------------------------Pcloud Image Upload------------------------------------------------
    function uploadImage_to_pcloud($path,$file,$folderID)
     {	
    	$fpath = base_path() . '/vendor/pcloud/pcloud-php-sdk/lib/pCloud/autoload.php';
      	 require_once($fpath);
    	 $file_path = $path.$file;
    	 
    	 
    	 
    	 $pCloudFile = new pCloud\File();
    	 $folder = $folderID;  // PCLOUD_FOLDER_ID
    	 $metadata = $pCloudFile->upload($file_path,$folder,$file);
    	 //echo $file_path;print_r($metadata);die();
    	 return $metadata;
    	 
    	
     }
     
     
     
     
       ## to delete image from p cloud
      function delete_pcloud($fileid){
         $fpath = base_path() . '/vendor/pcloud/pcloud-php-sdk/lib/pCloud/autoload.php';
      	 require_once($fpath);
    	 
    	 $pCloudFile = new pCloud\File();
    
    	 $metadata = $pCloudFile->delete($fileid);
    	 //echo $file_path;print_r($metadata);die();
    	 return $metadata;
    	 
      }
     

     
     function pCloudCreateFolder($folderid, $foldername)
     {	
        try {
    	$fpath = base_path() . '/vendor/pcloud/pcloud-php-sdk/lib/pCloud/autoload.php';
      	 require_once($fpath);
      	 
        $pCloudFolder = new pCloud\Folder();
        
        $resExe = $pCloudFolder->create($foldername, $folderid);
        return $resExe;
    //	echo '<pre>';print_r($resExe);die(); 
        } catch (Exception $e) {
        //	echo $e->getMessage();
        }
    
     } 




	public function Client_submit_track(Request $request){

		//dd('ggdfdfd');

		// fb logout link
		//   $this->load->library('facebook');
		//   $logout_link = url('Logout');

		  

		//   if(isset($_SESSION['fb_access_token']))
		//    {
		//      $logout_link = $this->facebook->logout_url();	
		//    }
		   
		//   $headerOutput['logout_link'] = $logout_link;

		if(empty(Session::get('clientId'))){
			return redirect()->intended('login');
		}
		if(!empty(Session::get('tempClientId'))){

			 $output['welcomeMsg'] = 'Thank you for updating your information !';
			 
			 Session::forget('tempClientId');
		}
		$output = array();
        $output['pageTitle'] = 'Digiwax Client Submit Track';
        $output['packageId'] = 2;
		
        $output['displayDashboard'] = 1;
		
		$clientId = Session::get('clientId');
		$output['sessClientID'] = $clientId;


		date_default_timezone_set('America/Los_Angeles');
		 
		  // logo 
		$headerOutput['logo'] = $this->clientAllDB_model->getLogo_cld(); 

		$clientdata = $this->clientAllDB_model->getClientsDetails_cld($clientId); 
		
		//echo "<pre>"; print_r($clientdata); die;

		$headerOutput['wrapperClass'] = 'client';

		//  header data ends here

		// get sub genres

		if(isset($_GET['getSubGenres']) && isset($_GET['genreId']))

		{

		

		$subGenres = $this->clientAllDB_model->getSubGenres_cld($_GET['genreId']); 

		

	   if($subGenres['numRows']>0)

	   {

	   $arr[] = array('id'=>'0', 'name'=>'Select Sub Genre');

	   foreach($subGenres['data'] as $genre) {

	   

		$arr[] = array('id'=>$genre->subGenreId, 'name'=>$genre->subGenre);



	   }

	   }

	   else

	   {

		$arr[] = array('id'=>'0', 'name'=>'No Data found.');

	   }

	   

	echo json_encode($arr);

		

		exit;

		}




	   
	//removing client Subscription
	//    $subscriptionInfo = $this->clientAllDB_model->getSubscriptionStatus($clientId); 

	//    if($subscriptionInfo['numRows']>0)

	//    {

	// 	 $headerOutput['subscriptionStatus'] = 1;

		  

	// 	 if($subscriptionInfo['data'][0]->packageId==1)

	// 	 {

	// 	   $headerOutput['packageId'] = 1;

	// 	   $headerOutput['package'] = 'BASIC SUBSCRIPTION';

	// 	   $headerOutput['displayDashboard'] = 0;

		   

		   

	// 	 }

	// 	 else if($subscriptionInfo['data'][0]->packageId==2)

	// 	 {

	// 	   $headerOutput['packageId'] = 2;

	// 	   $headerOutput['package'] = 'ADVANCED SUBSCRIPTION';

	// 	   $headerOutput['displayDashboard'] = 1;

		   

		   

	// 	 }

	//    }

	//    else

	//    {

	// 	  $headerOutput['packageId'] = 0;

	// 	  $headerOutput['subscriptionStatus'] = 0;

	// 	  $headerOutput['package'] = '';

	// 	  $headerOutput['displayDashboard'] = 0;

			

		 

	//    }

		

	   $headerOutput['packageId'] = 2;
	   $headerOutput['displayDashboard'] = 1;

		/*			   

		echo ini_get('upload_max_filesize').'<br/>';



		echo ini_get("upload_max_size").'<br/>';

			*/

	

		if(isset($_POST['addTrack']))
		{
		   /* if($_SERVER['REMOTE_ADDR'] == '106.206.241.86'){
			   $resTrackExist = $this->clientAllDB_model->checkClientTrackExists($_POST);
			   echo'<pre>';print_r($_POST);die('---GGSGSGSGSG');
		   } */
		 $getString = '';
		 foreach($_POST as $key => $value)
		 { 
		   $getString .= '&'.$key.'='.$value;
		 }


		// image validation
		// $imgDimensions =  getimagesize($_FILES['artWork']['tmp_name']);
		// if(($imgDimensions[0]<300) || ($imgDimensions[1]<300)) 
		// {
		//   header("location: ".url("client_submit_track?invalidSize=1".$getString));   exit;
		// }

		

		

		// track validation
	   if(!(isset($_FILES['amr1'])) || (strlen($_FILES['amr1']['name'])<4)) 
		 {
		   header("location: ".url("client_submit_track?invalidTrack=1".$getString));   exit;
		 }

	   

		$result = $this->clientAllDB_model->addClientTrack_cld($_POST,$clientId); 

	   

		// art work image upload

		if(isset($_FILES['artWork']['name']) && strlen($_FILES['artWork']['name'])>4)
		{


		$date_time_array = getdate(time());

		$artWorkImageName =	$date_time_array["year"].$date_time_array["mon"].$date_time_array["mday"].$date_time_array["hours"].$date_time_array["minutes"].$date_time_array["seconds"];

			$artWork = $request->file('artWork') ;
		
			//Display File Name
			$filename = $artWork->getClientOriginalName();
			// echo 'File Name: '.$filename;
		
			
			//Display File Extension
			$file_extension = $artWork->getClientOriginalExtension();
			// echo 'File Extension: '.$file_extension;
		
			
			//Display File Real Path
			$real_path = $artWork->getRealPath();
			// echo 'File Real Path: '.$real_path;
		
			
			//Display File Size
			$file_size = $artWork->getSize();
			// echo 'File Size: '.$file_size;
						// image validation
			// if(($file_size<300)) 
			// {
			// header("location: ".url("client_submit_track?invalidSize=1".$getString));   exit;
			// }
			
			if(($file_size>2000000)) 
			{
			header("location: ".url("Client_submit_track?invalidSize=1".$getString));   exit;
			}
		
			
			//Display File Mime Type
			$file_mime_type = $artWork->getMimeType();
			// echo 'File Mime Type: '.$file_mime_type;
		
			$destination_path = base_path('ImagesUp/');
		// die($destination_path);

			//Display Destination Path
			if(empty($destination_path)){
			$destination_path = public_path('uploads/');
			} else {
			$destination_path = $destination_path;
			}

			// // echo 'File Destination Path: '.$destination_path;
			// if(!File::isDirectory($destination_path)) {
			//     File::makeDirectory($destination_path, 0777, true, true);
			// }

			$image_name = $clientId . '_' . $artWorkImageName .'.'. $file_extension;
			// $image_name = preg_replace('/[^a-zA-Z0-9_.]/', '_', $image_name);

			$uploaded_data = $artWork->move( $destination_path , $image_name );
			
// 			---------------pcloud add image-----------------------------------------
             $folder= '13187487324';
             $metadata = $this->uploadImage_to_pcloud($destination_path, $image_name, $folder);
    
        	$pcloudFileId = $metadata->metadata->fileid;
        	$parentfolderid = $metadata->metadata->parentfolderid;
        	@unlink($destination_path.$image_name);
             

			if( !empty( $uploaded_data )){
			// die('file');

				$this->clientAllDB_model->addClientTrackArtWork_cld($result,$image_name,$clientId,$pcloudFileId,$parentfolderid); 

				// Image manipaulation/ intervention starts here
				// $thumbName = $clientId.'_'.$artWorkImageName.'.'.$file_extension;

				// // open an image file
				// 	$img = Image::make('ImagesUp/'.$thumbName);

				// 	// resize image instance
				// 	$img->resize(300, 300);

				// 	// insert a watermark
				// 	//$img->insert('public/watermark.png');

				// 	// save image in desired format
				// 	$img->save('thumbs/'.$thumbName);

				// 	$this->clientAllDB_model->addClientTrackThumb_cld($result,$clientId.'_'.$artWorkImageName.'_thumb'.'.'.$file_extension,$clientId); 

						// Image manipaulation/ intervention starts here

					


					// $thumbName = $clientId.'_'.$artWorkImageName.'.'.$file_extension;

					// $source_path = './ImagesUp/'.$thumbName; //$result,$config['file_name'];

					// $target_path = './thumbs/';

					// $config_manip = array(

					// 	'image_library' => 'gd2',

					// 	'source_image' => $source_path,

					// 	'new_image' => $target_path,

					// 	'maintain_ratio' => FALSE,

					// 	'create_thumb' => TRUE,

					// 	'thumb_marker' => '_thumb',

					// 	'width' => 300,

					// 	'height' => 300

					// );

					// 	print_r($config_manip);

					// 	$this->load->library('image_lib', $config_manip);

					// 	if (!$this->image_lib->resize()) {
					// 		echo $this->image_lib->display_errors();		
					// 		exit;
					// 	}

					// 	else

					// 	{

					// 		$this->clientAllDB_model->addClientTrackThumb_cld($result,$clientId.'_'.$artWorkImageName.'_thumb'.'.'.$ext,$clientId); 	

					// 	}  

						// clear //
					//	$this->image_lib->clear();

			}

			else{

			//header("location: " . url("admin/album/edit/?aid=" . $_GET['aid'] . "&error=1"));
			}


			//    $config['upload_path']          = './ImagesUp/';

			//    $config['allowed_types']        = 'gif|jpg|png';

			//    $config['max_size']             = 100000000000;

			//  //  $config['max_width']            = 1500;

			//   // $config['max_height']           = 1500;

			//    $config['file_name']           = $clientId.'_'.$artWorkImageName;

			   

			// 	$ext = explode('.',$_FILES['artWork']['name']);

			// 	$count = count($ext);

			// 	$ext = $ext[$count-1];



			//  //  $this->load->library('upload', $config);

			//  $this->upload->initialize($config);



			//    if ( ! $this->upload->do_upload('artWork'))
			//    {
			// 		   $error = array('error' => $this->upload->display_errors());
			// 		   //    $this->load->view('upload_form', $error);
			//    }
			//    else
			//    {

				//  $data = array('upload_data' => $this->upload->data());
				//  $this->clientAllDB_model->addClientTrackArtWork_cld($result,$config['file_name'].'.'.$ext,$clientId); 	
					 //  $this->load->view('Client_submit_track', $data);
					 // resize image:

					//  $thumbName = $clientId.'_'.$artWorkImageName.'.'.$ext;

					// $source_path = './ImagesUp/'.$thumbName; //$result,$config['file_name'];

					// $target_path = './thumbs/';

					// $config_manip = array(

					// 	'image_library' => 'gd2',

					// 	'source_image' => $source_path,

					// 	'new_image' => $target_path,

					// 	'maintain_ratio' => FALSE,

					// 	'create_thumb' => TRUE,

					// 	'thumb_marker' => '_thumb',

					// 	'width' => 300,

					// 	'height' => 300

					// );

					// 	print_r($config_manip);

					// 	$this->load->library('image_lib', $config_manip);

					// 	if (!$this->image_lib->resize()) {
					// 		echo $this->image_lib->display_errors();		
					// 		exit;
					// 	}

					// 	else

					// 	{

					// 		$this->clientAllDB_model->addClientTrackThumb_cld($result,$clientId.'_'.$artWorkImageName.'_thumb'.'.'.$ext,$clientId); 	

					// 	}  

					// 	// clear //
					// 	$this->image_lib->clear();
   				//}
  		 }

	   

  /*
  if(isset($_FILES['multiple_tracks'])) 
   {
   
   
   
   $countTracks = count($_FILES['multiple_tracks']['name']);	
   for($i=0;$i<$countTracks;$i++)
	   {
   
	$date_time_array = getdate(time());

   $amrfile1 =	$date_time_array["year"].$date_time_array["mon"].$date_time_array["mday"].$date_time_array["hours"].$date_time_array["minutes"].$date_time_array["seconds"];
   
   
			   $config['upload_path']          = './AUDIO/';
			   $config['allowed_types']        = 'gif|jpg|png|mp3';
			   $config['max_size']             = 9999999999999;

			   $fileName = md5(rand(1000,10000));
			   $config['file_name']           = $clientId.'_'.$i.'_'.$fileName.'.mp3';

			   $ext = explode('.',$_FILES['multiple_tracks']['name'][$i]);
			   $count = count($ext);
			   $ext = $ext[$count-1];				

			   $this->upload->initialize($config);	
			   
			   $_FILES['userfile']['name']= $_FILES['multiple_tracks']['name'][$i];
			   $_FILES['userfile']['type']= $_FILES['multiple_tracks']['type'][$i];
			   $_FILES['userfile']['tmp_name']= $_FILES['multiple_tracks']['tmp_name'][$i];
			   $_FILES['userfile']['error']= $_FILES['multiple_tracks']['error'][$i];
			   $_FILES['userfile']['size']= $_FILES['multiple_tracks']['size'][$i];   
	   
			   
			   if ( ! $this->upload->do_upload())
			   {
					   $error = array('error' => $this->upload->display_errors());
					   // print_r($error);
			   }
			   else
			   {
				 $j = $i+1;
				 $version = $_POST['version'];
				 $data = array('upload_data' => $this->upload->data());
				 $functionName = "addClientTrackAmr".$j;
				 $this->clientAllDB_model->$functionName($result,$config['file_name'],$clientId,$version); 
			   }
			   
			   
			   

   }
   }
   */


	   
	   $countVersion =  $_POST['numVersion'];
	   for($i=1;$i<=$countVersion;$i++)
	   {
	   if(isset($_FILES['amr'.$i])) 
		{
			$date_time_array = getdate(time());

			$amrfile1 =	$date_time_array["year"].$date_time_array["mon"].$date_time_array["mday"].$date_time_array["hours"].$date_time_array["minutes"].$date_time_array["seconds"];


			$audio_single = $request->file('amr'.$i) ;
			$destination_path = base_path('AUDIO/');

			  //Display Destination Path
			  if(empty($destination_path)){
				$destination_path = public_path('uploads/');
			} else {
				$destination_path = $destination_path;
			}

			$audio_ext = $audio_single->getClientOriginalExtension();

			if($audio_ext != 'mp3'){
		
				header("location: " . url("admin/track_manage_mp3?tid=" . $clientId . "&noaudio=1"));
				exit;

        	}

			$fileName = md5(rand(1000, 10000));
			$fileNameToStore = $clientId.'_'.$i.'_'.$fileName.'.mp3';

			$uploaded_data = $audio_single->move( $destination_path , $fileNameToStore );
		

			if( !empty( $uploaded_data )){
				// $version = $_POST['version' . $i];
				// if (strlen($_POST['version' . $i]) < 3) {
				// 	$version = $_POST['otherVersion' . $i];
				// }
				$track_title = $_POST['track_title'.$i];
				$version = $_POST['version'];
				$metadata = $this->add_to_pcloud($destination_path,$fileNameToStore);

				 $functionName = "addClientTrackAmr_cld".$i;
				 $this->clientAllDB_model->$functionName($result,$metadata->metadata->fileid,$clientId,$version,$track_title); 	
				

			}



		// 	   $config['upload_path']          = './AUDIO/';
		// 	   $config['allowed_types']        = 'gif|jpg|png|mp3';
		// 	   $config['max_size']             = 9999999999999;
		//    //  $config['max_width']            = 1024;
		//    //  $config['max_height']           = 768;
		//    //	$config['file_name']           = $clientId.'_'.$i.'_'.$amrfile1;

			   

		// 		$fileName = md5(rand(1000,10000));
		// 		$config['file_name']           = $clientId.'_'.$i.'_'.$fileName.'.mp3';

		// 		$ext = explode('.',$_FILES['amr'.$i]['name']);
		// 		$count = count($ext);
		// 		$ext = $ext[$count-1];
					   
		// 		$filename = $_FILES['amr' . $i]['name'];
		// 		$filepath = $_FILES['amr' . $i]['tmp_name'];
		// 	   try{
		// 		$metadata = $this->add_to_pcloud($filepath,$filename);
		// 	   } catch(Exception $e) {
		// 		 echo $e->getMessage();
		// 	   }
			  
		// 		 $version = $_POST['version'];
		// 		 $track_title = $_POST['track_title'.$i];
		// 		 $data = array('upload_data' => $this->upload->data());
		// 		 $functionName = "addClientTrackAmr_cld".$i;
		// 		 $this->clientAllDB_model->$functionName($result,$metadata->metadata->fileid,$clientId,$version,$track_title); 
			  
			   
		 }
		 }

		  if($result>0)
		  {

				//send mail starts
				$email = $clientdata['email']; 
				if(!empty($email)){

						 $to_email =  urldecode($email);
						// $to_email ='school_test007@yopmail.com';
					  
						$m_sub = 'Thank You for Your Track Upload';

					 	$m_msg = "Hi ".$clientdata["uname"].",<br /><p>Thanks for uploading tracks on digiwaxx. Your track has been successfully uploaded. Digiwaxx Media... Still Breakin' Boundaries!!!</p>";
		
						  $mail_data = [
							  'm_sub' => $m_sub,
							  'm_msg' => $m_msg,
						  ];

						  $cc_mails = 'admin@digiwaxx.com';
						  //$bcc_mails = '';
						  
						  	$sendInvoiceMail = Mail::to($to_email);

							if(!empty($cc_mails)){
								$sendInvoiceMail->cc($cc_mails);
							}
				
							// if(!empty($bcc_mails)){
							// 	$sendInvoiceMail->bcc($bcc_mails);
							// }
		
							
							$sendInvoiceMail->send(new AdminForgetNotification($mail_data));
		
							// if(count(Mail::failures()) > 0){
							//     $errors = 'Failed to send invoice email, please try again.';
							//     $return_status['message'] = $errors;
							// }
							// else{
							//     $return_status['status'] = TRUE;
							//     $return_status['message'] = 'Mail Success';
							//     $return_status['data'] = $mail_data;
							// }

							header("location: ".url("client_submit_track_preview?tId=".$result));   exit;
				}
				//send mail ends
			  
						// 	$email = $clientdata['email']; 
							
						// 	$message = "Hi ".$clientdata["uname"].",<br /><p>Thanks for uploading tracks on digiwaxx. Your track has been successfully uploaded. Digiwaxx Media... Still Breakin' Boundaries!!!</p>";
						// 	//echo $message; die;
						
						// // email			
						// $this->load->library('email');
						
						// $this->email->from('info@digiwaxx.com', 'Digiwaxx');
						// $this->email->to($email);
						// $this->email->set_mailtype("html");
						// $this->email->subject('Thank You for Your Track Upload');
						// $this->email->message($message);
						// $this->email->send();     
						// 	header("location: ".url("client_submit_track_preview?tId=".$result));   exit;
		  }
		  else
		  {
		   header("location: ".url("client_submit_track?error=1"));   exit;
		  }
		}	
		

		if(isset($_GET['added']))				

		   {
			   
			   
		
			  $output['alert_class'] = 'success-msg';

			  $output['alert_message'] = 'Track submitted  successfully !';

		   

		   }

		   else if(isset($_GET['error']))				

		   {

			  $output['alert_class'] = 'error-msg';

			  $output['alert_message'] = 'Error occured, please try again.';

		   }

		   else  if(isset($_GET['invalidSize']))				

		   {

			 

			  $output['alert_class'] = 'success-msg';

			  $output['alert_message'] = 'Image size should not be more than 2 MB!';

		   

		   }

		   

		   

	   $output['genres'] = $this->clientAllDB_model->getGenres_cld(); 

	   

	   $footerOutput['tracks'] = $this->clientAllDB_model->getClientFooterTracks_cld($clientId); 

	   

	   

	   $output['rightTracks'] = $this->clientAllDB_model->getRightTracks_cld(); 

	   

	   foreach($output['rightTracks']['data'] as $rightTrack)

	   {

		   $output['trackData'][$rightTrack->id] = $this->clientAllDB_model->getTrackPlays_cld($rightTrack->id); 

	   }



		  $unReadMessages = $this->clientAllDB_model->getClientUnreadInbox_cld($clientId); 

		$output['numMessages']  = $unReadMessages['numRows'];

		$headerOutput['numMessages']  = $unReadMessages['numRows'];


	   $headerOutput['tracks'] = $footerOutput['tracks'];
	   
	   $output['tracks'] = $this->clientAllDB_model->getClientFooterTracks($clientId);
	   

	

	//    $this->load->view('header_client_top.php',$headerOutput);

	//    $this->load->view('client_submit_track.php',$output);

	//    $this->load->view('footer_client_top.php',$footerOutput);

	  // dd($output);
// 	  pArr($output);
// 	  die();

		return view('clients.dashboard.client_submit_track', $output);


	}


		/*****************************************************************
	 *   SONG KEY 
	 * ***************************************************************/
	function buildMultiPartRequest($ch, $boundary, $fields, $files) {
		$delimiter = '-------------' . $boundary;
		$data = '';
		foreach ($fields as $name => $content) {
			$data .= "--" . $delimiter . "\r\n"
				. 'Content-Disposition: form-data; name="' . $name . "\"\r\n\r\n"
				. $content . "\r\n";
		}
		foreach ($files as $name => $content) {
			$data .= "--" . $delimiter . "\r\n"
				. 'Content-Disposition: form-data; name="' . $name . '"; filename="' . $name . '"' . "\r\n\r\n"
				. $content . "\r\n";
		}
		$data .= "--" . $delimiter . "--\r\n";
		curl_setopt_array($ch, [
			CURLOPT_POST => true,
			CURLOPT_HTTPHEADER => [
				'Content-Type: multipart/form-data; boundary=' . $delimiter,
				'Content-Length: ' . strlen($data)
			],
			CURLOPT_POSTFIELDS => $data
		]);
		return $ch;
	}
	
		
		public function get_audio_bitrate( Request $request){

		 
 			$filename = $_FILES['file']['tmp_name'];    //echo "<pre/>"; print_r($filename); die('data');
	
			if (!file_exists($filename)) { 
				return false;
			}
	
			$bitRates = array(
							  array(0,0,0,0,0),
							  array(32,32,32,32,8),
							  array(64,48,40,48,16),
							  array(96,56,48,56,24),
							  array(128,64,56,64,32),
							  array(160,80,64,80,40),
							  array(192,96,80,96,48),
							  array(224,112,96,112,56),
							  array(256,128,112,128,64),
							  array(288,160,128,144,80),
							  array(320,192,160,160,96),
							  array(352,224,192,176,112),
							  array(384,256,224,192,128),
							  array(416,320,256,224,144),
							  array(448,384,320,256,160),
							  array(-1,-1,-1,-1,-1),
							);
			$sampleRates = array(
								 array(11025,12000,8000), //mpeg 2.5
								 array(0,0,0),
								 array(22050,24000,16000), //mpeg 2
								 array(44100,48000,32000), //mpeg 1
								);
			$bToRead = 1024 * 12;
	
			$fileData = array('bitRate' => 0, 'sampleRate' => 0);
			$fp = fopen($filename, 'r');
			if (!$fp) {
				return false;
			}
			//seek to 8kb before the end of the file
			fseek($fp, -1 * $bToRead, SEEK_END);
			$data = fread($fp, $bToRead);
	
			$bytes = unpack('C*', $data);
			$frames = array();
			$lastFrameVerify = null;
	
			for ($o = 1; $o < count($bytes) - 4; $o++) {
	
				//http://mpgedit.org/mpgedit/mpeg_format/MP3Format.html
				//header is AAAAAAAA AAABBCCD EEEEFFGH IIJJKLMM
				if (($bytes[$o] & 255) == 255 && ($bytes[$o+1] & 224) == 224) {
					$frame = array();
					$frame['version'] = ($bytes[$o+1] & 24) >> 3; //get BB (0 -> 3)
					$frame['layer'] = abs((($bytes[$o+1] & 6) >> 1) - 4); //get CC (1 -> 3), then invert
					$srIndex = ($bytes[$o+2] & 12) >> 2; //get FF (0 -> 3)
					$brRow = ($bytes[$o+2] & 240) >> 4; //get EEEE (0 -> 15)
					$frame['padding'] = ($bytes[$o+2] & 2) >> 1; //get G
					if ($frame['version'] != 1 && $frame['layer'] > 0 && $srIndex < 3 && $brRow != 15 && $brRow != 0 &&
						(!$lastFrameVerify || $lastFrameVerify === $bytes[$o+1])) {
						//valid frame header
	
						//calculate how much to skip to get to the next header
						$frame['sampleRate'] = $sampleRates[$frame['version']][$srIndex];
						if ($frame['version'] & 1 == 1) {
							$frame['bitRate'] = $bitRates[$brRow][$frame['layer']-1]; //v1 and l1,l2,l3
						} else {
							$frame['bitRate'] = $bitRates[$brRow][($frame['layer'] & 2 >> 1)+3]; //v2 and l1 or l2/l3 (3 is the offset in the arrays)
						}
	
						if ($frame['layer'] == 1) {
							$frame['frameLength'] = (12 * $frame['bitRate'] * 1000 / $frame['sampleRate'] + $frame['padding']) * 4;
						} else {
							$frame['frameLength'] = 144 * $frame['bitRate'] * 1000 / $frame['sampleRate'] + $frame['padding'];
						}
	
						$frames[] = $frame;
						$lastFrameVerify = $bytes[$o+1];
						$o += floor($frame['frameLength'] - 1);
					} else {
						$frames = array();
						$lastFrameVerify = null;
					}
				}
				if (count($frames) < 3) { //verify at least 3 frames to make sure its an mp3
					continue;
				}
	
				$header = array_pop($frames);
				//$fileData['sampleRate'] = $header['sampleRate'];
				$fileData['bitRate'] = $header['bitRate'];
				
				$target_path = "./AUDIO/";
	
				$target_path = $target_path . $_FILES['file']['name'];
				move_uploaded_file($_FILES['file']['tmp_name'], $target_path);
				
				$url ='https://digiwaxx.io/AUDIO/' . rawurlencode($_FILES['file']['name']); 
				$ch = curl_init('https://tunebatanalytics.com/upload/');
				@ $ch = $this->buildMultiPartRequest($ch, uniqid(),
				[], ['file' => file_get_contents($url)]);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				$result = curl_exec($ch);
				$result = json_decode($result, true);
				
    			$bpm='';
    			$key='';
    			if(!empty($result['BPM'])){
    			 $bpm = $result['BPM'];   
    			}
				
				if(!empty($result['Key'])){
				   $key = $result['Key']; 
				}
				
				
				
				unlink($target_path);
	
				break;
			}
			
		   echo json_encode(["bitRate"=>$fileData['bitRate'],"bpm" => $bpm,"key"=>$key]);
		   //echo  $fileData['bitRate'];
		}
		
		public function deleteEditTrack()
		{
			$delId = $this->input->post('delId');
			
			$this->db->where('id', $delId);
			$this->db->delete('tracks_mp3s'); 
			
			echo json_encode(1);
		}
		
		
		// function add_to_pcloud($path,$file)
		// {
		// 	$fpath = $_SERVER["DOCUMENT_ROOT"];
		// 	require_once($fpath."/pcloud-sdk/autoload.php");
			
		// 	$pCloudFile = new pCloud\File();
		// 	$folder = PCLOUD_FOLDER_ID;
		// 	$metadata = $pCloudFile->upload($path,$folder,$file);
			
		// 	return $metadata;
		// }

		function add_to_pcloud($path,$file)
		{	
			//dd($path);
		   // $fpath = $_SERVER["DOCUMENT_ROOT"];
		   // require_once($fpath."/pcloud-sdk/autoload.php");
	   
		   //$fpath = url("/vendor/pcloud/pcloud-php-sdk/lib/pCloud/autoload.php");
		   // require_once($path."/pcloud-sdk/autoload.php");
		   $fpath = base_path() . '/vendor/pcloud/pcloud-php-sdk/lib/pCloud/autoload.php';
			  require_once($fpath);
	   
			 // $url_path  = url('AUDIO/'.$file);
			//  dd($url_path);
			$file_path = $path.$file;
		   // dd($file_path);
			
			$pCloudFile = new pCloud\File();
			$folder = 5241532702;  // PCLOUD_FOLDER_ID
			$metadata = $pCloudFile->upload($file_path,$folder,$file);
			return $metadata;
		}


		public function Tag_your_music(Request $request){

			//dd('ggdfdfd');
	
			// fb logout link
			//   $this->load->library('facebook');
			//   $logout_link = url('Logout');
	
			  
	
			//   if(isset($_SESSION['fb_access_token']))
			//    {
			//      $logout_link = $this->facebook->logout_url();	
			//    }
			   
			//   $headerOutput['logout_link'] = $logout_link;
	
			if(empty(Session::get('clientId'))){
				return redirect()->intended('login');
			}
			if(!empty(Session::get('tempClientId'))){
	
				 $output['welcomeMsg'] = 'Thank you for updating your information !';
				 
				 Session::forget('tempClientId');
			}
			$output = array();
			 $output['pageTitle'] = 'Digiwax Client Tracks';
			$output['packageId'] = 2;
			
			$clientId = Session::get('clientId');
			$output['sessClientID'] = $clientId;
	
	
			date_default_timezone_set('America/Los_Angeles');
			 
			  // logo 
			$headerOutput['logo'] = $this->clientAllDB_model->getLogo_cld(); 
	
			$clientdata = $this->clientAllDB_model->getClientsDetails_cld($clientId); 
			
			//echo "<pre>"; print_r($clientdata); die;
	
			$headerOutput['wrapperClass'] = 'client';
	
			//  header data ends here


	
	
			// save tag
			if(isset($_GET['saveTag']) && isset($_GET['tid']) && isset($_GET['tag']))
			{
			
			$result = $this->clientAllDB_model->saveTag_cld($_GET['tid'],$_GET['tag']); 
			 
			if($result>0)
			{
			  $arr[] = array('status'=>'1', 'message'=>'TAG added successfully !');
			}
			else
			{
			 $arr[] = array('status'=>'0', 'message'=>'Error occured, please try again !');
			}
		 echo json_encode($arr);		
			 exit;
			}
			 

	
			$subscriptionInfo = $this->clientAllDB_model->getSubscriptionStatus_cld($clientId); 
	
			
	
			if($subscriptionInfo['numRows']>0)
	
			{
	
			  $headerOutput['subscriptionStatus'] = 1;
	
			  if($subscriptionInfo['data'][0]->packageId==1)
	
			  {
	
				$headerOutput['packageId'] = 1;
	
				$headerOutput['package'] = 'BASIC SUBSCRIPTION';
	
				$headerOutput['displayDashboard'] = 0;
	
				
	
				// 			$output['packageId'] = 1;
	
			  }
	
			  else if($subscriptionInfo['data'][0]->packageId==2)
	
			  {
	
				$headerOutput['packageId'] = 2;
	
				$headerOutput['package'] = 'ADVANCED SUBSCRIPTION';
	
				$headerOutput['displayDashboard'] = 1;
	
				
	
				// $output['packageId'] = 2;
	
			  }
	
			}
	
			else
	
			{
	
			   $headerOutput['packageId'] = 0;
	
			   $headerOutput['subscriptionStatus'] = 0;
	
			   $headerOutput['package'] = '';
	
			   $headerOutput['displayDashboard'] = 0;
	
			   
	
			   // $output['packageId'] = 0;
	
			}
	
			 
	
			
	
			// generate where
	
			/*$where = "where client =  '".$clientId."' and deleted = '0' and approved = '1'";
	
			$where1 = "where client =  '".$clientId."' and deleted = '0' and approved = '0'";
	
			*/
	
			
	
			$where = "where client =  '".$clientId."' and deleted = '0'";
	
			
	
			
	
			
	
			// generate sort
	
			
	
			
	
			$sortOrder = "DESC";
	
			$sortBy = "added";
	
			$output['sortBy'] = 'date';
	
			$output['sortOrder'] = 2;
	
			
	
			if(isset($_GET['sortBy']) && isset($_GET['sortOrder']))
	
			{
	
			   $output['sortBy'] = $_GET['sortBy'];
	
			   $output['sortOrder'] = $_GET['sortOrder'];
	
			   
	
			   
	
			   if(strcmp($_GET['sortBy'],'artist')==0)
	
			   {
	
				
	
				 $sortBy = "artist";
	
			   }
	
			   else if(strcmp($_GET['sortBy'],'title')==0)
	
			   {
	
				
	
				 $sortBy = "title";
	
			   }
	
				else if(strcmp($_GET['sortBy'],'date')==0)
	
			   {
	
				
	
				 $sortBy = "added";
	
			   }
	
			   
	
			   
	
			   if($_GET['sortOrder']==1)
	
			   {
	
				
	
				 $sortOrder = "ASC";
	
			   }
	
			   else  if($_GET['sortOrder']==2)
	
			   {
	
				
	
				 $sortOrder = "DESC";
	
			   }
	
			  
	
			  
	
			  
	
			  
	
			}
	
			$sort =  $sortBy." ".$sortOrder;
	
			
	
			
	
			
	
			// pagination
	
			$limit = 10;
	
			if(isset($_GET['records']))
	
			{
	
			  $limit = $_GET['records'];
	
			}
	
			$output['numRecords'] = $limit;
	
			   
	
			$start = 0; 
	
			$currentPageNo = 1;
	
			
	
			if(isset($_GET['page']) && $_GET['page']>1)
	
			 {
	
				$start = ($_GET['page']*$limit)-$limit;  
	
			 }
	
			 
	
			
	
			 if(isset($_GET['page']))
	
			 {
	
				$currentPageNo = $_GET['page']; 
	
			 }
	
			 
	
		  $num_records = $this->clientAllDB_model->getNumClientTracks_cld($where,$sort); 
	
		  $numPages = (int)($num_records/$limit); 
	
		  $reminder = ($num_records%$limit);
	
		 
	
		 if($reminder>0)
	
		 {
	
			 $numPages = $numPages+1;
	
		 }
	
		
	
		 $output['numPages'] = $numPages;
	
		 $output['start'] = $start;
	
		 $output['currentPageNo'] = $currentPageNo;
	
		 $output['currentPage'] = 'Client_tracks';
	
		 
	
		 
	
		  if(isset($_GET['page'])) {
	
		 if ($_GET['page'] > $numPages) {
	
			 header("location: ".$output['currentPage']."?page=" . $numPages);
	
			 exit;
	
		 } else if ($_GET['page'] < 1) {
	
			 header("location: ".$output['currentPage']."?page=1");
	
			 exit;
	
		 }
	
	 }
	
	 
	
	 
	
	 
	
	  if($currentPageNo==1)
	
	  {
	
		  $output['firstPageLink'] = 'disabled';
	
		  $output['preLink'] = 'disabled';  
	
		  $output['nextLink'] = '';
	
		  $output['lastPageLink'] = '';
	
		  
	
		  
	
	  }
	
	  else if($currentPageNo==$numPages)
	
	  {
	
		  $output['firstPageLink'] = '';
	
		  $output['preLink'] = '';
	
		  $output['nextLink'] = 'disabled';
	
		  $output['lastPageLink'] = 'disabled';
	
		  
	
		  
	
	  }
	
	  else
	
	  {
	
		  $output['firstPageLink'] = '';
	
		  $output['preLink'] = '';
	
		  $output['nextLink'] = '';
	
		  $output['lastPageLink'] = '';
	
	  }
	
	  // pagination
	
			
	
			$output['tracks'] = $this->clientAllDB_model->getClientTracks_cld($where,$start,$limit,$sort); 
	
			foreach($output['tracks']['data'] as $track)
	
			{
	
			  if($track->approved==1)
	
			  {
	
				$output['trackData'][$track->id] = $this->clientAllDB_model->getTrackPlays_cld($track->id); 
	
			  }
	
			  
	
			//  $output['feedback_videos'][$track->id] = $this->clientAllDB_model->getClientTrackFeedbackVideos($track->id); 
	
			}
	
			
	
			
	
			$output['rightTracks'] = $this->clientAllDB_model->getRightTracks_cld(); 
	
			
	
			foreach($output['rightTracks']['data'] as $rightTrack)
	
			{
	
				$output['trackData'][$rightTrack->id] = $this->clientAllDB_model->getTrackPlays_cld($rightTrack->id); 
	
			}
	
			
	
			
	
			$footerOutput['tracks'] = $this->clientAllDB_model->getClientFooterTracks_cld($clientId); 
	
			
	
		   $unReadMessages = $this->clientAllDB_model->getClientUnreadInbox_cld($clientId); 
	
			 $output['numMessages']  = $unReadMessages['numRows'];
	
			 $headerOutput['numMessages']  = $unReadMessages['numRows'];
	
			 
	
			
	
			 
	
			 
	
			
	
			 
	
			 
	
			 
	
			  if(isset($_GET['trackAdded']))				
	
				{
	
				  
	
				   $output['alert_class'] = 'success-msg';
	
				   $output['alert_message'] = 'Thank you for submitting. Your track is now being processed and reviewed by our Admin Team';
	
				
	
				}
	
				else if(isset($_GET['error']))				
	
				{
	
				   $output['alert_class'] = 'error-msg';
	
				   $output['alert_message'] = 'Error occured, please try again.';
	
				}
				
	
			$headerOutput['tracks'] = $footerOutput['tracks'];
			$headerOutput['banner_ads'] = $this->clientAllDB_model->getBannerads_cld(1,1); 
				
	
			// $this->load->view('header_client_top.php',$headerOutput);
			// $this->load->view('tag_your_music.php',$output);
			// $this->load->view('footer_client_top.php',$footerOutput);

			//dd($output);
			return view('clients.dashboard.tag_your_music', $output);

		}


		public function Client_my_digicoins(Request $request){

			//dd('ggdfdfd');
	
			// fb logout link
			//   $this->load->library('facebook');
			//   $logout_link = url('Logout');
	
			  
	
			//   if(isset($_SESSION['fb_access_token']))
			//    {
			//      $logout_link = $this->facebook->logout_url();	
			//    }
			   
			//   $headerOutput['logout_link'] = $logout_link;
	
			if(empty(Session::get('clientId'))){
				return redirect()->intended('login');
			}
			if(!empty(Session::get('tempClientId'))){
	
				 $output['welcomeMsg'] = 'Thank you for updating your information !';
				 
				 Session::forget('tempClientId');
			}
			$output = array();
			 $output['pageTitle'] = 'Client My Digicoins';
			$output['packageId'] = 2;
			
			$clientId = Session::get('clientId');
			$output['sessClientID'] = $clientId;
	
	
			date_default_timezone_set('America/Los_Angeles');
			 
			  // logo 
			$headerOutput['logo'] = $this->clientAllDB_model->getLogo_cld(); 
	
			$clientdata = $this->clientAllDB_model->getClientsDetails_cld($clientId); 
			
			//echo "<pre>"; print_r($clientdata); die;
	
			$headerOutput['wrapperClass'] = 'client';
	
			//  header data ends here
	
			
			// removed subscription option
	
			// $subscriptionInfo = $this->clientAllDB_model->getSubscriptionStatus_cld($clientId); 
	
			
	
			// //print_r($subscriptionInfo);
	
			// //print_r($_SESSION);
	
			
	
			// if($subscriptionInfo['numRows']>0)
	
			// {
	
			//   $headerOutput['subscriptionStatus'] = 1;
	
			   
	
			//   if($subscriptionInfo['data'][0]->packageId==1)
	
			//   {
	
			// 	$headerOutput['packageId'] = 1;
	
			// 	$headerOutput['package'] = 'BASIC SUBSCRIPTION';
	
			// 	$headerOutput['displayDashboard'] = 0;
	
				
	
				
	
			//   }
	
			//   else if($subscriptionInfo['data'][0]->packageId==2)
	
			//   {
	
			// 	$headerOutput['packageId'] = 2;
	
			// 	$headerOutput['package'] = 'ADVANCED SUBSCRIPTION';
	
			// 	$headerOutput['displayDashboard'] = 1;
	
				
	
				
	
			//   }
	
			// }
	
			// else
	
			// {
	
			//    $headerOutput['packageId'] = 0;
	
			//    $headerOutput['subscriptionStatus'] = 0;
	
			//    $headerOutput['package'] = '';
	
			//    $headerOutput['displayDashboard'] = 0;
	
				 
	
			  
	
			// }
			
	
			$headerOutput['packageId'] = 2;
			$headerOutput['displayDashboard'] = 1;
			 
	
			
	
			// generate where
	
			/*$where = "where client =  '".$clientId."' and deleted = '0' and approved = '1'";
	
			$where1 = "where client =  '".$clientId."' and deleted = '0' and approved = '0'";
	
			*/
	
			
	
			$where = "where client_id =  '".$clientId."'";
	
			
	
			
	
			
	
			// generate sort
	
			
	
			
	
			$sortOrder = "DESC";
	
			$sortBy = "client_digicoin_id";
	
			$output['sortBy'] = 'client_digicoin_id';
	
			
	
			$sort =  $sortBy." ".$sortOrder;
	
			
	
			
	
			
	
			// pagination starts
	
			$limit = 10;
	
			if(isset($_GET['records']))
	
			{
	
			  $limit = $_GET['records'];
	
			}
	
			$output['numRecords'] = $limit;
	
			   
	
			$start = 0; 
	
			$currentPageNo = 1;
	
			
	
			if(isset($_GET['page']) && $_GET['page']>1)
	
			 {
	
				$start = ($_GET['page']*$limit)-$limit;  
	
			 }
	
			 
	
			
	
			 if(isset($_GET['page']))
	
			 {
	
				$currentPageNo = $_GET['page']; 
	
			 }
	
			 
	
		  $num_records = $this->clientAllDB_model->getNumClientDigicoins_cld($where,$sort); 
	
	
	
	
	
		  $numPages = (int)($num_records/$limit); 
	
		  $reminder = ($num_records%$limit);
		  $output['num_records'] = $num_records;
	
		 
	
		 if($reminder>0)
	
		 {
	
			 $numPages = $numPages+1;
	
		 }
	
		
	
		 $output['numPages'] = $numPages;
	
		 $output['start'] = $start;
	
		 $output['currentPageNo'] = $currentPageNo;
	
		 $output['currentPage'] = 'Client_my_digicoins';
	
		 
	
		 
	
		  if(isset($_GET['page'])) {
	
		 if ($_GET['page'] > $numPages) {
	
			 header("location: ".$output['currentPage']."?page=" . $numPages);
	
			 exit;
	
		 } else if ($_GET['page'] < 1) {
	
			 header("location: ".$output['currentPage']."?page=1");
	
			 exit;
	
		 }
	
	 }
	
	 
	
	 
	
	 
	
	  if($currentPageNo==1)
	
	  {
	
		  $output['firstPageLink'] = 'disabled';
	
		  $output['preLink'] = 'disabled';  
	
		  $output['nextLink'] = '';
	
		  $output['lastPageLink'] = '';
	
		  
	
		  
	
	  }
	
	  else if($currentPageNo==$numPages)
	
	  {
	
		  $output['firstPageLink'] = '';
	
		  $output['preLink'] = '';
	
		  $output['nextLink'] = 'disabled';
	
		  $output['lastPageLink'] = 'disabled';
	
		  
	
		  
	
	  }
	
	  else
	
	  {
	
		  $output['firstPageLink'] = '';
	
		  $output['preLink'] = '';
	
		  $output['nextLink'] = '';
	
		  $output['lastPageLink'] = '';
	
	  }
	
	  // pagination ends
	
			
	
			$output['digicoins'] = $this->clientAllDB_model->getClientDigicoins_cld($where,$sort,$start,$limit); 
	
		/*	 
	
			foreach($output['tracks']['data'] as $track)
	
			{
	
			  if($track->approved==1)
	
			  {
	
				$output['trackData'][$track->id] = $this->clientAllDB_model->getTrackPlays($track->id); 
	
			  }
	
			  
	
			  $output['feedback_videos'][$track->id] = $this->clientAllDB_model->getClientTrackFeedbackVideos($track->id); 
	
			}
	
			*/
	
			
	
			$output['rightTracks'] = $this->clientAllDB_model->getRightTracks_cld(); 
	
			
	
			foreach($output['rightTracks']['data'] as $rightTrack)
	
			{
	
				$output['trackData'][$rightTrack->id] = $this->clientAllDB_model->getTrackPlays_cld($rightTrack->id); 
	
			}
	
			
	
			
	
			$footerOutput['tracks'] = $this->clientAllDB_model->getClientFooterTracks_cld($clientId); 
	
			
	
		   $unReadMessages = $this->clientAllDB_model->getClientUnreadInbox_cld($clientId); 
	
			 $output['numMessages']  = $unReadMessages['numRows'];
	
			 $headerOutput['numMessages']  = $unReadMessages['numRows'];
	
			 
	
			
	
			 
	
			 
	
			$available_digicoins = $this->clientAllDB_model->get_client_available_digicoins_cld($clientId); 
	
			$output['available_digicoins'] = 0;
	
			if($available_digicoins['numRows']>0)
	
			{
	
			  $output['available_digicoins'] = $available_digicoins['data'][0]->available_points;
	
			}
	
			
	
			 
	
			 
	
			 
	
			  if(isset($_GET['trackAdded']))				
	
				{
	
				  
	
				   $output['alert_class'] = 'success-msg';
	
				   $output['alert_message'] = 'Thank you for submitting. Your track is now being processed and reviewed by our Admin Team';
	
				
	
				}
	
				else if(isset($_GET['error']))				
	
				{
	
				   $output['alert_class'] = 'error-msg';
	
				   $output['alert_message'] = 'Error occured, please try again.';
	
				}
	
				
	
				
	
			$headerOutput['tracks'] = $footerOutput['tracks'];
	
			
	
			// $this->load->view('header_client_top.php',$headerOutput);
	
			// $this->load->view('client_my_digicoins.php',$output);
	
			// $this->load->view('footer_client_top.php',$footerOutput);

			//dd($output);
			
			$output['tracks'] = $this->clientAllDB_model->getClientFooterTracks($clientId);

			return view('clients.dashboard.client_my_digicoins', $output);

		}


		public function Client_label_reps(Request $request){

			//dd('ggdfdfd');
	
			// fb logout link
			//   $this->load->library('facebook');
			//   $logout_link = url('Logout');
	
			  
	
			//   if(isset($_SESSION['fb_access_token']))
			//    {
			//      $logout_link = $this->facebook->logout_url();	
			//    }
			   
			//   $headerOutput['logout_link'] = $logout_link;
	
			if(empty(Session::get('clientId'))){
				return redirect()->intended('login');
			}
			if(!empty(Session::get('tempClientId'))){
	
				 $output['welcomeMsg'] = 'Thank you for updating your information !';
				 
				 Session::forget('tempClientId');
			}
			$output = array();
			 $output['pageTitle'] = 'Digiwax Client label Reps';
			$output['packageId'] = 2;
			
			$clientId = Session::get('clientId');
			$output['sessClientID'] = $clientId;
	
	
			date_default_timezone_set('America/Los_Angeles');
			 
			  // logo 
			$headerOutput['logo'] = $this->clientAllDB_model->getLogo_cld(); 
	
			$clientdata = $this->clientAllDB_model->getClientsDetails_cld($clientId); 
			
			//echo "<pre>"; print_r($clientdata); die;
	
			$headerOutput['wrapperClass'] = 'client';
	
			//  header data ends here


		
			
			  // removed subscription option
			// $subscriptionInfo = $this->clientAllDB_model->getSubscriptionStatus($clientId); 
	
			// if($subscriptionInfo['numRows']>0)
	
			// {
	
			//   $headerOutput['subscriptionStatus'] = 1;
	
			   
	
			//   if($subscriptionInfo['data'][0]->packageId==1)
	
			//   {
	
			// 	$headerOutput['packageId'] = 1;
	
			// 	$headerOutput['package'] = 'BASIC';
	
			// 	$headerOutput['displayDashboard'] = 0;
	
				
	
				
	
			//   }
	
			//   else if($subscriptionInfo['data'][0]->packageId==2)
	
			//   {
	
			// 	$headerOutput['packageId'] = 2;
	
			// 	$headerOutput['package'] = 'ADVANCED';
	
			// 	$headerOutput['displayDashboard'] = 1;
	
				
	
				
	
			//   }
	
			// }
	
			// else
	
			// {
	
			//    $headerOutput['packageId'] = 0;
	
			//    $headerOutput['subscriptionStatus'] = 0;
	
			//    $headerOutput['package'] = '';
	
			//    $headerOutput['displayDashboard'] = 0;
	
				 
	
			  
	
			// }

	
			$headerOutput['packageId'] = 2;
			$headerOutput['displayDashboard'] = 1;
	
			 if(isset($_GET['did']))
	
			 {
	
			 
	
			   $result = $this->clientAllDB_model->deleteClientLabelRep_cld($_GET['did'],$clientId); 
	
			   
	
			   
	
			   if($result > 0)
	
			   {
	
				  header("location: ".url("Client_label_reps?deleted=1"));   exit;
	
			   }
	
			   else
	
			   {
	
				  header("location: ".url("Client_label_reps?deleteError=1"));   exit;
	
			   }	
	
  
	
			 }


			 $where = "where client_id =  '".$clientId."'";
	
	
			// generate sort

			$sortOrder = "DESC";
	
			$sortBy = "id";
	
			$output['sortBy'] = 'id';
	
			
	
			$sort =  $sortBy." ".$sortOrder;
	
			
	
			
	
			
	
			// pagination starts
	
			$limit = 10;
	
			if(isset($_GET['records']))
	
			{
	
			  $limit = $_GET['records'];
	
			}
	
			$output['numRecords'] = $limit;
	
			   
	
			$start = 0; 
	
			$currentPageNo = 1;
	
			
	
			if(isset($_GET['page']) && $_GET['page']>1)
	
			 {
	
				$start = ($_GET['page']*$limit)-$limit;  
	
			 }
	
			 
	
			
	
			 if(isset($_GET['page']))
	
			 {
	
				$currentPageNo = $_GET['page']; 
	
			 }
	
			 
	
		  $num_records = $this->clientAllDB_model->getNumClientLabelReps_cld($where,$sort); 
	
	
	
	
	
		  $numPages = (int)($num_records/$limit); 
	
		  $reminder = ($num_records%$limit);
		  $output['num_records'] = $num_records;
	
		 
	
		 if($reminder>0)
	
		 {
	
			 $numPages = $numPages+1;
	
		 }
	
		
	
		 $output['numPages'] = $numPages;
	
		 $output['start'] = $start;
	
		 $output['currentPageNo'] = $currentPageNo;
	
		 $output['currentPage'] = 'Client_label_reps';
	
		 
	
		 
	
		  if(isset($_GET['page'])) {
	
		 if ($_GET['page'] > $numPages) {
	
			 header("location: ".$output['currentPage']."?page=" . $numPages);
	
			 exit;
	
		 } else if ($_GET['page'] < 1) {
	
			 header("location: ".$output['currentPage']."?page=1");
	
			 exit;
	
		 }
	
	 }
	
	 
	
	 
	
	 
	
	  if($currentPageNo==1)
	
	  {
	
		  $output['firstPageLink'] = 'disabled';
	
		  $output['preLink'] = 'disabled';  
	
		  $output['nextLink'] = '';
	
		  $output['lastPageLink'] = '';
	
		  
	
		  
	
	  }
	
	  else if($currentPageNo==$numPages)
	
	  {
	
		  $output['firstPageLink'] = '';
	
		  $output['preLink'] = '';
	
		  $output['nextLink'] = 'disabled';
	
		  $output['lastPageLink'] = 'disabled';
	
		  
	
		  
	
	  }
	
	  else
	
	  {
	
		  $output['firstPageLink'] = '';
	
		  $output['preLink'] = '';
	
		  $output['nextLink'] = '';
	
		  $output['lastPageLink'] = '';
	
	  }
	
	  // pagination ends
	
			
	
			$output['reps'] = $this->clientAllDB_model->getClientLabelReps_cld($where,$sort,$start,$limit); 

			//$output['reps'] = $this->clientAllDB_model->getClientLabelReps_cld($clientId); 
	
			
	
			$output['rightTracks'] = $this->clientAllDB_model->getRightTracks_cld(); 
	
			
	
			foreach($output['rightTracks']['data'] as $rightTrack)
	
			{
	
				$output['trackData'][$rightTrack->id] = $this->clientAllDB_model->getTrackPlays_cld($rightTrack->id); 
	
			}
	
			   
	
			$footerOutput['tracks'] = $this->clientAllDB_model->getClientFooterTracks_cld($clientId); 
	
			
	
			
	
			$unReadMessages = $this->clientAllDB_model->getClientUnreadInbox_cld($clientId); 
	
			 $output['numMessages']  = $unReadMessages['numRows'];
	
			 $headerOutput['numMessages']  = $unReadMessages['numRows'];
	
			 
	
			$headerOutput['tracks'] = $footerOutput['tracks'];
			
			$output['tracks'] = $this->clientAllDB_model->getClientFooterTracks($clientId);
	
			
	
			// $this->load->view('header_client_top.php',$headerOutput);
	
			// $this->load->view('client_label_reps.php',$output);
	
			// $this->load->view('footer_client_top.php',$footerOutput);

			//dd($output);

			return view('clients.dashboard.client_label_reps', $output);

		}

		public function Client_add_label_reps(Request $request){

			//dd('ggdfdfd');
	
			// fb logout link
			//   $this->load->library('facebook');
			//   $logout_link = url('Logout');
	
			  
	
			//   if(isset($_SESSION['fb_access_token']))
			//    {
			//      $logout_link = $this->facebook->logout_url();	
			//    }
			   
			//   $headerOutput['logout_link'] = $logout_link;
	
			if(empty(Session::get('clientId'))){
				return redirect()->intended('login');
			}
			if(!empty(Session::get('tempClientId'))){
	
				 $output['welcomeMsg'] = 'Thank you for updating your information !';
				 
				 Session::forget('tempClientId');
			}
			$output = array();
			 $output['pageTitle'] = 'Digiwax Client Add Label Reps';
			$output['packageId'] = 2;
			
			$clientId = Session::get('clientId');
			$output['sessClientID'] = $clientId;
	
	
			date_default_timezone_set('America/Los_Angeles');
			 
			  // logo 
			$headerOutput['logo'] = $this->clientAllDB_model->getLogo_cld(); 
	
			$clientdata = $this->clientAllDB_model->getClientsDetails_cld($clientId); 
			
			//echo "<pre>"; print_r($clientdata); die;
	
			$headerOutput['wrapperClass'] = 'client';
	
			//  header data ends here
   
			
   
			$subscriptionInfo = $this->clientAllDB_model->getSubscriptionStatus_cld($clientId); 
   
			if($subscriptionInfo['numRows']>0)
   
			{
   
			 $headerOutput['subscriptionStatus'] = 1;
   
			  
   
			 if($subscriptionInfo['data'][0]->packageId==1)
   
			 {
   
			   $headerOutput['packageId'] = 1;
   
			   $headerOutput['package'] = 'BASIC';
   
			   $headerOutput['displayDashboard'] = 0;
   
			   
   
			   
   
			 }
   
			 else if($subscriptionInfo['data'][0]->packageId==2)
   
			 {
   
			   $headerOutput['packageId'] = 2;
   
			   $headerOutput['package'] = 'ADVANCED';
   
			   $headerOutput['displayDashboard'] = 1;
   
			   
   
			   
   
			 }
   
		   }
   
		   else
   
		   {
   
			  $headerOutput['packageId'] = 0;
   
			  $headerOutput['subscriptionStatus'] = 0;
   
			  $headerOutput['package'] = '';
   
			  $headerOutput['displayDashboard'] = 0;
   
				
   
			 
   
		   }
   
		   
   
		   
   
		   if(isset($_POST['addLabelRep']))
   
		   {
   
			
   
			
   
			 $result = $this->clientAllDB_model->addClientLabelRep_cld($_POST,$clientId); 
   
			  
   
			  if($result>0)
   
			  {
   
			   
   
			   
   
			   header("location: ".url("Client_add_label_reps?added=1"));   exit;
   
				
   
				
   
			  }
   
			  else
   
			  {
   
			   
   
			   header("location: ".url("Client_add_label_reps?error=1"));   exit;
   
			  }
   
			}
   
   
   
   
   
   
   
			  if(isset($_GET['added']))				
   
			   {
   
				 
   
				  $output['alert_class'] = 'success-msg';
   
				  $output['alert_message'] = 'Label Rep added successfully !';
   
			   
   
			   }
   
			   else if(isset($_GET['error']))				
   
			   {
   
				  $output['alert_class'] = 'error-msg';
   
				  $output['alert_message'] = 'Error occured, please try again.';
   
			   }
   
		   
   
		   
   
		   $output['rightTracks'] = $this->clientAllDB_model->getRightTracks_cld(); 
   
		   
   
		   foreach($output['rightTracks']['data'] as $rightTrack)
   
		   {
   
			   $output['trackData'][$rightTrack->id] = $this->clientAllDB_model->getTrackPlays_cld($rightTrack->id); 
   
		   }
   
		   
   
		   $footerOutput['tracks'] = $this->clientAllDB_model->getClientFooterTracks_cld($clientId); 
   
		   
   
		   $unReadMessages = $this->clientAllDB_model->getClientUnreadInbox_cld($clientId); 
   
			$output['numMessages']  = $unReadMessages['numRows'];
   
			$headerOutput['numMessages']  = $unReadMessages['numRows'];
   
			
   
			
   
		   $headerOutput['tracks'] = $footerOutput['tracks'];
   
		   
   
		//    $this->load->view('header_client_top.php',$headerOutput);
   
		//    $this->load->view('client_add_label_reps.php',$output);
   
		//    $this->load->view('footer_client_top.php',$footerOutput);

			//dd($output);
			 $output['tracks'] = $this->clientAllDB_model->getClientFooterTracks($clientId);

		   return view('clients.dashboard.client_add_label_reps', $output);

		}


		public function Client_edit_label_reps(Request $request){

			//dd('ggdfdfd');
	
			// fb logout link
			//   $this->load->library('facebook');
			//   $logout_link = url('Logout');
	
			  
	
			//   if(isset($_SESSION['fb_access_token']))
			//    {
			//      $logout_link = $this->facebook->logout_url();	
			//    }
			   
			//   $headerOutput['logout_link'] = $logout_link;
	
			if(empty(Session::get('clientId'))){
				return redirect()->intended('login');
			}
			if(!empty(Session::get('tempClientId'))){
	
				 $output['welcomeMsg'] = 'Thank you for updating your information !';
				 
				 Session::forget('tempClientId');
			}
			$output = array();
			 $output['pageTitle'] = 'Digiwax Client Edit Label Reps';
			$output['packageId'] = 2;
			
			$clientId = Session::get('clientId');
			$output['sessClientID'] = $clientId;
	
	
			date_default_timezone_set('America/Los_Angeles');
			 
			  // logo 
			$headerOutput['logo'] = $this->clientAllDB_model->getLogo_cld(); 
	
			$clientdata = $this->clientAllDB_model->getClientsDetails_cld($clientId); 
			
			//echo "<pre>"; print_r($clientdata); die;
	
			$headerOutput['wrapperClass'] = 'client';
	
			//  header data ends here

   
			// removed subscription option
		//    $subscriptionInfo = $this->clientAllDB_model->getSubscriptionStatus($clientId); 
   
		//    if($subscriptionInfo['numRows']>0)
   
		//    {
   
		// 	 $headerOutput['subscriptionStatus'] = 1;
   
			  
   
		// 	 if($subscriptionInfo['data'][0]->packageId==1)
   
		// 	 {
   
		// 	   $headerOutput['packageId'] = 1;
   
		// 	   $headerOutput['package'] = 'BASIC';
   
		// 	   $headerOutput['displayDashboard'] = 0;
   
			   
   
			   
   
		// 	 }
   
		// 	 else if($subscriptionInfo['data'][0]->packageId==2)
   
		// 	 {
   
		// 	   $headerOutput['packageId'] = 2;
   
		// 	   $headerOutput['package'] = 'ADVANCED';
   
		// 	   $headerOutput['displayDashboard'] = 1;
   
			   
   
			   
   
		// 	 }
   
		//    }
   
		//    else
   
		//    {
   
		// 	  $headerOutput['packageId'] = 0;
   
		// 	  $headerOutput['subscriptionStatus'] = 0;
   
		// 	  $headerOutput['package'] = '';
   
		// 	  $headerOutput['displayDashboard'] = 0;
   
				
   
			 
   
		//    }
		
   
		   $headerOutput['packageId'] = 2;
		   $headerOutput['displayDashboard'] = 1;
   
		   
   
			
   
		   if(isset($_POST['updateLabelRep']) && isset($_GET['repId']))
   
		   {
   
			
   
			  $result = $this->clientAllDB_model->updateClientLabelRep_cld($_POST,$_GET['repId'],$clientId); 
   
			  
   
			  
   
			  if($result > 0)
   
			  {
   
				 header("location: ".url("Client_edit_label_reps?repId=".$_GET['repId']."&updated=1"));   exit;
   
			  }
   
			  else
   
			  {
   
				 header("location: ".url("Client_edit_label_reps?repId=".$_GET['repId']."&error=1"));   exit;
   
			  }	
   
			  
   
			
   
			 
   
			}
   
			
   
			
   
		   $output['reps'] = $this->clientAllDB_model->getClientLabelRep_cld($_GET['repId'],$clientId);
   
		   
   
		   if($output['reps']['numRows']<1)
   
		   {
   
			  header("location: ".url("Client_label_reps"));   exit;
   
			  exit;
   
		   }
   
		   
   
	   
   
	   
   
			if(isset($_GET['updated']))				
   
			   {
   
				 
   
				  $output['alert_class'] = 'success-msg';
   
				  $output['alert_message'] = 'Label Rep updated successfully !';
   
			   
   
			   }
   
			   else if(isset($_GET['error']))				
   
			   {
   
				  $output['alert_class'] = 'error-msg';
   
				  $output['alert_message'] = 'Error occured, please try again.';
   
			   }
   
			  
   
			  
   
		   $output['rightTracks'] = $this->clientAllDB_model->getRightTracks_cld(); 
   
		   
   
		   foreach($output['rightTracks']['data'] as $rightTrack)
   
		   {
   
			   $output['trackData'][$rightTrack->id] = $this->clientAllDB_model->getTrackPlays_cld($rightTrack->id); 
   
		   }
   
		   
   
		   $footerOutput['tracks'] = $this->clientAllDB_model->getClientFooterTracks_cld($clientId); 
   
		   
   
		   
   
		   $unReadMessages = $this->clientAllDB_model->getClientUnreadInbox_cld($clientId); 
   
			$output['numMessages']  = $unReadMessages['numRows'];
   
			$headerOutput['numMessages']  = $unReadMessages['numRows'];
   
			
   
		   $headerOutput['tracks'] = $footerOutput['tracks'];
   
		   
   
		//    $this->load->view('header_client_top.php',$headerOutput);
   
		//    $this->load->view('client_edit_label_reps.php',$output);
   
		//    $this->load->view('footer_client_top.php',$footerOutput);
   
         $output['tracks'] = $this->clientAllDB_model->getClientFooterTracks($clientId);

			return view('clients.dashboard.client_edit_label_reps', $output);


		}


		public function Client_info(Request $request){

			//dd('ggdfdfd');
	
			// fb logout link
			//   $this->load->library('facebook');
			//   $logout_link = url('Logout');
	
			  
	
			//   if(isset($_SESSION['fb_access_token']))
			//    {
			//      $logout_link = $this->facebook->logout_url();	
			//    }
			   
			//   $headerOutput['logout_link'] = $logout_link;
	
			if(empty(Session::get('clientId'))){
				return redirect()->intended('login');
			}
			if(!empty(Session::get('tempClientId'))){
	
				 $output['welcomeMsg'] = 'Thank you for updating your information !';
				 
				 Session::forget('tempClientId');
			}
			$output = array();
			 $output['pageTitle'] = 'Digiwax Client Info';
			$output['packageId'] = 2;
			
			$clientId = Session::get('clientId');
			$output['sessClientID'] = $clientId;
	
	
			date_default_timezone_set('America/Los_Angeles');
			 
			  // logo 
			$headerOutput['logo'] = $this->clientAllDB_model->getLogo_cld(); 
	
			$clientdata = $this->clientAllDB_model->getClientsDetails_cld($clientId); 
			
			//echo "<pre>"; print_r($clientdata); die;
	
			$headerOutput['wrapperClass'] = 'client';
	
			//  header data ends here

   
			 // removed subscription option
   
		//    $subscriptionInfo = $this->clientAllDB_model->getSubscriptionStatus($clientId); 
   
		//    if($subscriptionInfo['numRows']>0)
   
		//    {
   
		// 	 $headerOutput['subscriptionStatus'] = 1;
   
			  
   
		// 	 if($subscriptionInfo['data'][0]->packageId==1)
   
		// 	 {
   
		// 	   $headerOutput['packageId'] = 1;
   
		// 	   $headerOutput['package'] = 'BASIC';
   
		// 	   $headerOutput['displayDashboard'] = 0;
   
			   
   
			   
   
		// 	 }
   
		// 	 else if($subscriptionInfo['data'][0]->packageId==2)
   
		// 	 {
   
		// 	   $headerOutput['packageId'] = 2;
   
		// 	   $headerOutput['package'] = 'ADVANCED';
   
		// 	   $headerOutput['displayDashboard'] = 1;
   
			   
   
			   
   
		// 	 }
   
		//    }
   
		//    else
   
		//    {
   
		// 	  $headerOutput['packageId'] = 0;
   
		// 	  $headerOutput['subscriptionStatus'] = 0;
   
		// 	  $headerOutput['package'] = '';
   
		// 	  $headerOutput['displayDashboard'] = 0;
   
				
   
			 
   
		//    }
   
   
		   $headerOutput['packageId'] = 2;
		   $headerOutput['displayDashboard'] = 1;
		   
   
		   $output['clientInfo'] = $this->clientAllDB_model->getClientInfo_cld($clientId); 
   
		   $output['clientImage'] = $this->clientAllDB_model->getClientImage_cld($clientId); 
   
		   $output['socialInfo'] = $this->clientAllDB_model->getClientSocialInfo_cld($clientId); 
   
		   
   
		   
   
		   
   
		   
   
		   $output['rightTracks'] = $this->clientAllDB_model->getRightTracks_cld(); 
   
		   
   
		   foreach($output['rightTracks']['data'] as $rightTrack)
   
		   {
   
			   $output['trackData'][$rightTrack->id] = $this->clientAllDB_model->getTrackPlays_cld($rightTrack->id); 
   
		   }
   
		   
   
		   
   
		   $footerOutput['tracks'] = $this->clientAllDB_model->getClientFooterTracks_cld($clientId); 
   
		   
   
		   
   
		   $unReadMessages = $this->clientAllDB_model->getClientUnreadInbox_cld($clientId); 
   
			$output['numMessages']  = $unReadMessages['numRows'];
   
			$headerOutput['numMessages']  = $unReadMessages['numRows'];
   
		   
   
		   
   
		   $headerOutput['tracks'] = $footerOutput['tracks'];
   
		   
   
		//    $this->load->view('header_client_top.php',$headerOutput);
   
		//    $this->load->view('client_info.php',$output);
   
		//    $this->load->view('footer_client_top.php',$footerOutput);

		//   dd($output);
		$output['tracks'] = $this->clientAllDB_model->getClientFooterTracks($clientId);
			return view('clients.dashboard.client_info', $output);


		}

		public function Client_messages(Request $request){

			//dd('ggdfdfd');
	
			// fb logout link
			//   $this->load->library('facebook');
			//   $logout_link = url('Logout');
	
			  
	
			//   if(isset($_SESSION['fb_access_token']))
			//    {
			//      $logout_link = $this->facebook->logout_url();	
			//    }
			   
			//   $headerOutput['logout_link'] = $logout_link;
	
			if(empty(Session::get('clientId'))){
				return redirect()->intended('login');
			}
			if(!empty(Session::get('tempClientId'))){
	
				 $output['welcomeMsg'] = 'Thank you for updating your information !';
				 
				 Session::forget('tempClientId');
			}
			$output = array();
			 $output['pageTitle'] = 'Digiwax Client Messages';
			$output['packageId'] = 2;
			
			$clientId = Session::get('clientId');
			$output['sessClientID'] = $clientId;
			
			// pagination
			$reminder = 0;
			$numPages = 0;
			$limit = 10;
			if(isset($_GET['records']))
			{
			$limit = $_GET['records'];
			}

			$output['numRecords'] = $limit;

			$start = 0;
			$currentPageNo = 1;

			if(isset($_GET['page']) && $_GET['page']>1)
			{
			  $start = ($_GET['page']*$limit)-$limit;
			}

			if(isset($_GET['page']))
			{
			  $currentPageNo = $_GET['page'];
			}
			
			$outputMsgTtlCount = $this->clientAllDB_model->getClientInboxTotalCount($clientId);
			
			if($outputMsgTtlCount>0){
				
			$num_records = $outputMsgTtlCount;
			
			$numPages = (int)($num_records/$limit);
			$reminder = ($num_records%$limit);
			$output['num_records'] = $num_records;
			
			}

			if($reminder>0)
			{
			   $numPages = $numPages+1;
			}

			$output['numPages'] = $numPages;
			$output['start'] = $start;
			$output['currentPageNo'] = $currentPageNo;
			$output['currentPage'] = 'Client_messages';

			   // generate url string

			$getArray = array(); $urlString = '?';

			if(isset($_GET))
			{
			  foreach($_GET as $key=>$value)
			   {
				 if(strcmp($key,'page')!=0)
				 {
				   $getArray[] = $key.'='.$value;
				 }
			   }

			  if(count($getArray)>1)
			  {
				$urlString .= implode('&',$getArray);
			  }
			  else if(count($getArray)==1)
			  {
				$urlString .= $getArray[0];
			  }
			  else
			  {
				$urlString = '';
			  }
			}

			$output['urlString'] = $urlString;

			   // uncomment later

			   if(isset($_GET['page'])) {
			   if(strlen($urlString)>3)
				  {
					 $param = '&';
				  }
				  else
				  {
					$param = '?';
				  }

			if ($_GET['page'] > $numPages && $numPages > 0) {
					 return Redirect::to($output['currentPage'].$urlString.$param."page=" . $numPages);
				   exit;
			   }
			}	
	
			date_default_timezone_set('America/Los_Angeles');
			 
			  // logo 
			$headerOutput['logo'] = $this->clientAllDB_model->getLogo_cld(); 
	
			$clientdata = $this->clientAllDB_model->getClientsDetails_cld($clientId); 
			
			//echo "<pre>"; print_r($clientdata); die;
	
			$headerOutput['wrapperClass'] = 'client';
	
			//  header data ends here

   
			
   
			$output['messages'] = $this->clientAllDB_model->getClientInboxAllMessages($clientId, $start, $limit); 
			$output['messages']['numRows'] = $outputMsgTtlCount;
			
   
			if($output['messages']['numRows']>0)
   
			{
   
			 
   
			  foreach($output['messages']['data'] as $message)
   
			  {
   
				  
   
				  if($message->senderType==1)
   
				  {
   
				   $memberId = $message->receiverId;
   
				  }
   
				  else 
   
				  {
   
				  $memberId = $message->senderId;
   
				  }
   
					  $result = $this->clientAllDB_model->getMemberDetails_cld($memberId);   
   
					  if($result['id']>0)
					  {
   
						 $output['senderDetails'][$message->messageId]['id']    =  $result['id'];
						 $output['senderDetails'][$message->messageId]['image'] =  $result['image'];
						 $output['senderDetails'][$message->messageId]['uname'] =  $result['uname'];
						 $output['senderDetails'][$message->messageId]['name']  =  $result['fname'];		   
					  }
   
			  }
			}
   
			
   
		   //  $output['unreadMessages'] = $this->clientAllDB_model->getClientUnreadInbox($clientId);  
   
		   //removed subscription option
		//    $subscriptionInfo = $this->clientAllDB_model->getSubscriptionStatus($clientId); 
   
		//    if($subscriptionInfo['numRows']>0)
   
		//    {
   
		// 	 $headerOutput['subscriptionStatus'] = 1;
   
			  
   
		// 	 if($subscriptionInfo['data'][0]->packageId==1)
		// 	 {
   
		// 	   $headerOutput['packageId'] = 1;
		// 	   $headerOutput['package'] = 'BASIC SUBSCRIPTION';
		// 	   $headerOutput['displayDashboard'] = 0;			
   
		// 	   // $output['displayMsgs'] = 0;
		// 	 }
   
		// 	 else if($subscriptionInfo['data'][0]->packageId==2)
		// 	 {
   
		// 	   $headerOutput['packageId'] = 2;
		// 	   $headerOutput['package'] = 'ADVANCED SUBSCRIPTION';
		// 	   $headerOutput['displayDashboard'] = 1;			
   
		// 	   // $output['displayMsgs'] = 1;
   
		// 	 }
   
		//    }
		//    else
		//    {
   
		// 	  $headerOutput['packageId'] = 0;
		// 	  $headerOutput['subscriptionStatus'] = 0;
		// 	  $headerOutput['package'] = '';
		// 	  $headerOutput['displayDashboard'] = 0;
   
				
   
		// 	//  $output['displayMsgs'] = 0;
   
		//    }
		   
   
		   $headerOutput['packageId'] = 2;
		   $headerOutput['displayDashboard'] = 1;
			

			$unReadMessages = $this->clientAllDB_model->getClientUnreadInbox_cld($clientId); 
   
			$output['numMessages']  = $unReadMessages['numRows'];
   
			$headerOutput['numMessages']  = $unReadMessages['numRows'];

			$output['rightTracks'] = $this->clientAllDB_model->getRightTracks_cld(); 
   
		   
   
		   foreach($output['rightTracks']['data'] as $rightTrack)
   
		   {
   
			   $output['trackData'][$rightTrack->id] = $this->clientAllDB_model->getTrackPlays_cld($rightTrack->id); 
   
		   }
		   
		   $output['tracks'] = $this->clientAllDB_model->getClientFooterTracks($clientId);
   
			
   
	   /*	if(($subscriptionInfo['numRows']>0) && ($subscriptionInfo['data'][0]->packageId==2))
   
		   {
   
			 $output['displayMsgs'] = 1;
   
		   }
   
		   else
   
		   {
   
			 $output['displayMsgs'] = 0;
   
		   }
   
			 */
   
   
		//    $this->load->view('header_client.php',$headerOutput);
		//    $this->load->view('client_messages.php',$output);
		//    $this->load->view('footer_client.php');


			  //dd($output);
			return view('clients.dashboard.client_messages', $output);


		}

		public function Client_messages_unread(Request $request){
			
			if(empty(Session::get('clientId'))){
				return redirect()->intended('login');
			}
			if(!empty(Session::get('tempClientId'))){
	
				 $output['welcomeMsg'] = 'Thank you for updating your information !';
				 
				 Session::forget('tempClientId');
			}
			$output = array();
			 $output['pageTitle'] = 'Digiwax Client Unread Messages';
			$output['packageId'] = 2;
			
			$clientId = Session::get('clientId');
			$output['sessClientID'] = $clientId;
			// pagination
			$reminder = 0;
			$numPages = 0;
			$limit = 10;
			if(isset($_GET['records']))
			{
			$limit = $_GET['records'];
			}

			$output['numRecords'] = $limit;

			$start = 0;
			$currentPageNo = 1;

			if(isset($_GET['page']) && $_GET['page']>1)
			{
			  $start = ($_GET['page']*$limit)-$limit;
			}

			if(isset($_GET['page']))
			{
			  $currentPageNo = $_GET['page'];
			}
			
			$outputMsgTtlCount = $this->clientAllDB_model->getClientUnreadInboxTotalCount($clientId);
			
			if($outputMsgTtlCount>0){
				
			$num_records = $outputMsgTtlCount;
			
			$numPages = (int)($num_records/$limit);
			$reminder = ($num_records%$limit);
			$output['num_records'] = $num_records;
			
			}

			if($reminder>0)
			{
			   $numPages = $numPages+1;
			}

			$output['numPages'] = $numPages;
			$output['start'] = $start;
			$output['currentPageNo'] = $currentPageNo;
			$output['currentPage'] = 'Client_messages_unread';

			   // generate url string

			$getArray = array(); $urlString = '?';

			if(isset($_GET))
			{
			  foreach($_GET as $key=>$value)
			   {
				 if(strcmp($key,'page')!=0)
				 {
				   $getArray[] = $key.'='.$value;
				 }
			   }

			  if(count($getArray)>1)
			  {
				$urlString .= implode('&',$getArray);
			  }
			  else if(count($getArray)==1)
			  {
				$urlString .= $getArray[0];
			  }
			  else
			  {
				$urlString = '';
			  }
			}

			$output['urlString'] = $urlString;

			   // uncomment later

			   if(isset($_GET['page'])) {
			   if(strlen($urlString)>3)
				  {
					 $param = '&';
				  }
				  else
				  {
					$param = '?';
				  }

			if ($_GET['page'] > $numPages && $numPages > 0) {
					 return Redirect::to($output['currentPage'].$urlString.$param."page=" . $numPages);
				   exit;
			   }
			}	
	
			date_default_timezone_set('America/Los_Angeles');
			 
			  // logo 
			$headerOutput['logo'] = $this->clientAllDB_model->getLogo_cld(); 
	
			$clientdata = $this->clientAllDB_model->getClientsDetails_cld($clientId); 
			
			//echo "<pre>"; print_r($clientdata); die;
	
			$headerOutput['wrapperClass'] = 'client';
	
			//  header data ends here

			$inboxMessages = $this->clientAllDB_model->getClientInbox_cld($clientId); 
   
			$output['numInboxMessages'] = $inboxMessages['numRows'];
   
			 
   
			$output['messages'] = $this->clientAllDB_model->getClientUnreadInboxAll($clientId, $start, $limit);

			$output['messages']['numRows'] = $outputMsgTtlCount;
   
			$output['numMessages']  = $output['messages']['numRows'];
   
			$headerOutput['numMessages']  = $output['messages']['numRows'];

			$output['rightTracks'] = $this->clientAllDB_model->getRightTracks_cld(); 
   
		   
   
		   foreach($output['rightTracks']['data'] as $rightTrack)
   
		   {
   
			   $output['trackData'][$rightTrack->id] = $this->clientAllDB_model->getTrackPlays_cld($rightTrack->id); 
   
		   }
   
			
   
			if($output['messages']['numRows']>0)
   
			{
   
			 
   
			  foreach($output['messages']['data'] as $message)
   
			  {
   
				  
   
				  if($message->senderType==1)
   
				  {
   
				   $memberId = $message->receiverId;
   
				  }
   
				  else 
   
				  {
   
				  $memberId = $message->senderId;
   
				  }
   
				  
   
				 
   
					  $result = $this->clientAllDB_model->getMemberDetails_cld($memberId);   
   
					  if($result['id']>0)
   
					  {
   
						 
   
						 $output['senderDetails'][$message->messageId]['id']    =  $result['id'];
   
						 $output['senderDetails'][$message->messageId]['image'] =  $result['image'];
   
						 $output['senderDetails'][$message->messageId]['uname'] =  $result['uname'];
   
						 $output['senderDetails'][$message->messageId]['name']  =  $result['fname'];
   
					  
   
					  }
	   
   
			  }
   
			  
   
			}
		
   
		   $headerOutput['packageId'] = 2;
		   $headerOutput['displayDashboard'] = 1;
		   
             $output['tracks'] = $this->clientAllDB_model->getClientFooterTracks($clientId);
			// dd($output);
			 return view('clients.dashboard.client_messages_unread', $output);

		}

		public function Client_messages_starred(Request $request){

			//dd('ggdfdfd');
			
			// fb logout link
			//   $this->load->library('facebook');
			//   $logout_link = url('Logout');
			
			  
			
			//   if(isset($_SESSION['fb_access_token']))
			//    {
			//      $logout_link = $this->facebook->logout_url();	
			//    }
			   
			//   $headerOutput['logout_link'] = $logout_link;
			
			if(empty(Session::get('clientId'))){
				return redirect()->intended('login');
			}
			if(!empty(Session::get('tempClientId'))){
			
				 $output['welcomeMsg'] = 'Thank you for updating your information !';
				 
				 Session::forget('tempClientId');
			}
			$output = array();
			 $output['pageTitle'] = 'Digiwax Client Starred Messages';
			$output['packageId'] = 2;
			
			$clientId = Session::get('clientId');
			$output['sessClientID'] = $clientId;
			// pagination
			$reminder = 0;
			$numPages = 0;
			$limit = 10;
			if(isset($_GET['records']))
			{
			$limit = $_GET['records'];
			}

			$output['numRecords'] = $limit;

			$start = 0;
			$currentPageNo = 1;

			if(isset($_GET['page']) && $_GET['page']>1)
			{
			  $start = ($_GET['page']*$limit)-$limit;
			}

			if(isset($_GET['page']))
			{
			  $currentPageNo = $_GET['page'];
			}
			
			$outputMsgTtlCount = $this->clientAllDB_model->getClientStarredTotalCount($clientId);
			
			if($outputMsgTtlCount>0){
				
			$num_records = $outputMsgTtlCount;
			
			$numPages = (int)($num_records/$limit);
			$reminder = ($num_records%$limit);
			$output['num_records'] = $num_records;
			
			}

			if($reminder>0)
			{
			   $numPages = $numPages+1;
			}

			$output['numPages'] = $numPages;
			$output['start'] = $start;
			$output['currentPageNo'] = $currentPageNo;
			$output['currentPage'] = 'Client_messages_starred';

			   // generate url string

			$getArray = array(); $urlString = '?';

			if(isset($_GET))
			{
			  foreach($_GET as $key=>$value)
			   {
				 if(strcmp($key,'page')!=0)
				 {
				   $getArray[] = $key.'='.$value;
				 }
			   }

			  if(count($getArray)>1)
			  {
				$urlString .= implode('&',$getArray);
			  }
			  else if(count($getArray)==1)
			  {
				$urlString .= $getArray[0];
			  }
			  else
			  {
				$urlString = '';
			  }
			}

			$output['urlString'] = $urlString;

			   // uncomment later

			   if(isset($_GET['page'])) {
			   if(strlen($urlString)>3)
				  {
					 $param = '&';
				  }
				  else
				  {
					$param = '?';
				  }

			if ($_GET['page'] > $numPages && $numPages > 0) {
					 return Redirect::to($output['currentPage'].$urlString.$param."page=" . $numPages);
				   exit;
			   }
			}			
			
			date_default_timezone_set('America/Los_Angeles');
			 
			  // logo 
			$headerOutput['logo'] = $this->clientAllDB_model->getLogo_cld(); 
			
			$clientdata = $this->clientAllDB_model->getClientsDetails_cld($clientId); 
			
			//echo "<pre>"; print_r($clientdata); die;
			
			$headerOutput['wrapperClass'] = 'client';
			
			//  header data ends here
			
			$output['msgs'] = array();
			
			$members = array();
			
			
			
			$output['messages'] = $this->clientAllDB_model->getClientStarredMessagesAll($clientId, $start, $limit); 
			
			$output['messages']['numRows'] = $outputMsgTtlCount;
			
			 $i = 0; 
			
			if($output['messages']['numRows']>0)
			
			{
			
			 
			
			  foreach($output['messages']['data'] as $message)
			
			  {
			
				  
			
				  if($message->senderType==1)
			
				  {
			
				   $memberId = $message->receiverId;
			
				  }
			
				  else 
			
				  {
			
				  $memberId = $message->senderId;
			
				  }
			
				  
			
				  
			
					if(!(in_array($memberId,$members)))
			
			  {
			
			  
			
			  $members[] = $memberId;
			
			  $output['msgs'][$i]['message'] = $message->message;
			
			  $output['msgs'][$i]['dateTime'] = $message->dateTime;
			
			  $output['msgs'][$i]['messageId'] = $message->messageId;
			
					
			
					  $result = $this->clientAllDB_model->getMemberDetails_cld($memberId);   
			
					  if($result['id']>0)
			
					  {
			
						 
			
						 $output['senderDetails'][$message->messageId]['id']    =  $result['id'];
			
						 $output['senderDetails'][$message->messageId]['image'] =  $result['image'];
			
						 $output['senderDetails'][$message->messageId]['uname'] =  $result['uname'];
			
						 $output['senderDetails'][$message->messageId]['name']  =  $result['fname'];
			
			
					  }
			
			
				   $i++;
			
				  }
			
			  }
			
			
			}
			
			
			 
			// removed subscription option
			
			//    $subscriptionInfo = $this->clientAllDB_model->getSubscriptionStatus($clientId); 
			
			//    if($subscriptionInfo['numRows']>0)
			
			//    {
			
			// 	 $headerOutput['subscriptionStatus'] = 1;
			
			  
			
			// 	 if($subscriptionInfo['data'][0]->packageId==1)
			
			// 	 {
			
			// 	   $headerOutput['packageId'] = 1;
			
			// 	   $headerOutput['package'] = 'BASIC SUBSCRIPTION';
			
			// 	   $headerOutput['displayDashboard'] = 0;
			
			   
			
			// 	   // $output['displayMsgs'] = 0;
			
			// 	 }
			
			// 	 else if($subscriptionInfo['data'][0]->packageId==2)
			
			// 	 {
			
			// 	   $headerOutput['packageId'] = 2;
			
			// 	   $headerOutput['package'] = 'ADVANCED SUBSCRIPTION';
			
			// 	   $headerOutput['displayDashboard'] = 1;
			
			   
			
			//    //	$output['displayMsgs'] = 1;
			
			// 	 }
			
			//    }
			
			//    else
			
			//    {
			
			// 	  $headerOutput['packageId'] = 0;
			
			// 	  $headerOutput['subscriptionStatus'] = 0;
			
			// 	  $headerOutput['package'] = '';
			
			// 	  $headerOutput['displayDashboard'] = 0;
			
				
			
			// 	//  $output['displayMsgs'] = 0;
			
			//    }
			
			
			$headerOutput['packageId'] = 2;
			$headerOutput['displayDashboard'] = 1;
			
			
			$unReadMessages = $this->clientAllDB_model->getClientUnreadInbox_cld($clientId); 
			
			$output['numMessages']  = $unReadMessages['numRows'];
			
			$headerOutput['numMessages']  = $unReadMessages['numRows'];
			
			$output['rightTracks'] = $this->clientAllDB_model->getRightTracks_cld(); 
			
			
			
			foreach($output['rightTracks']['data'] as $rightTrack)
			
			{
			
			   $output['trackData'][$rightTrack->id] = $this->clientAllDB_model->getTrackPlays_cld($rightTrack->id); 
			
			}
			
			
			
			$inboxMessages = $this->clientAllDB_model->getClientInbox_cld($clientId); 
			
			$output['numInboxMessages'] = $inboxMessages['numRows'];
			
			
			//    $this->load->view('header_client.php',$headerOutput);
			
			//    $this->load->view('client_messages_starred.php',$output);
			
			//    $this->load->view('footer_client.php');
			
			// dd($output);
			
			 $output['tracks'] = $this->clientAllDB_model->getClientFooterTracks($clientId);
			
			 return view('clients.dashboard.client_messages_starred', $output);
			
			}
			
			public function Client_messages_archived(Request $request){
			
			//dd('ggdfdfd');
			
			// fb logout link
			//   $this->load->library('facebook');
			//   $logout_link = url('Logout');
			
			  
			
			//   if(isset($_SESSION['fb_access_token']))
			//    {
			//      $logout_link = $this->facebook->logout_url();	
			//    }
			   
			//   $headerOutput['logout_link'] = $logout_link;
			
			if(empty(Session::get('clientId'))){
				return redirect()->intended('login');
			}
			if(!empty(Session::get('tempClientId'))){
			
				 $output['welcomeMsg'] = 'Thank you for updating your information !';
				 
				 Session::forget('tempClientId');
			}
			$output = array();
			 $output['pageTitle'] = 'Digiwax Client Messages';
			$output['packageId'] = 2;
			
			$clientId = Session::get('clientId');
			$output['sessClientID'] = $clientId;
			// pagination
			$reminder = 0;
			$numPages = 0;
			$limit = 10;
			if(isset($_GET['records']))
			{
			$limit = $_GET['records'];
			}

			$output['numRecords'] = $limit;

			$start = 0;
			$currentPageNo = 1;

			if(isset($_GET['page']) && $_GET['page']>1)
			{
			  $start = ($_GET['page']*$limit)-$limit;
			}

			if(isset($_GET['page']))
			{
			  $currentPageNo = $_GET['page'];
			}
			
			$outputMsgTtlCount = $this->clientAllDB_model->getClientArchivedMsgesTotalCount($clientId);
			
			if($outputMsgTtlCount>0){
				
			$num_records = $outputMsgTtlCount;
			
			$numPages = (int)($num_records/$limit);
			$reminder = ($num_records%$limit);
			$output['num_records'] = $num_records;
			
			}

			if($reminder>0)
			{
			   $numPages = $numPages+1;
			}

			$output['numPages'] = $numPages;
			$output['start'] = $start;
			$output['currentPageNo'] = $currentPageNo;
			$output['currentPage'] = 'Client_messages_starred';

			   // generate url string

			$getArray = array(); $urlString = '?';

			if(isset($_GET))
			{
			  foreach($_GET as $key=>$value)
			   {
				 if(strcmp($key,'page')!=0)
				 {
				   $getArray[] = $key.'='.$value;
				 }
			   }

			  if(count($getArray)>1)
			  {
				$urlString .= implode('&',$getArray);
			  }
			  else if(count($getArray)==1)
			  {
				$urlString .= $getArray[0];
			  }
			  else
			  {
				$urlString = '';
			  }
			}

			$output['urlString'] = $urlString;

			   // uncomment later

			   if(isset($_GET['page'])) {
			   if(strlen($urlString)>3)
				  {
					 $param = '&';
				  }
				  else
				  {
					$param = '?';
				  }

			if ($_GET['page'] > $numPages && $numPages > 0) {
					 return Redirect::to($output['currentPage'].$urlString.$param."page=" . $numPages);
				   exit;
			   }
			}			
			
			date_default_timezone_set('America/Los_Angeles');
			 
			  // logo 
			$headerOutput['logo'] = $this->clientAllDB_model->getLogo_cld(); 
			
			$clientdata = $this->clientAllDB_model->getClientsDetails_cld($clientId); 
			
			//echo "<pre>"; print_r($clientdata); die;
			
			$headerOutput['wrapperClass'] = 'client';
			
			//  header data ends here
			
			
			 $output['msgs'] = array();
			
			 $members = array();
			
			
			 $output['messages'] = $this->clientAllDB_model->getClientArchivedMessagesAll($clientId, $start, $limit); 
			 $output['messages']['numRows'] = $outputMsgTtlCount;
			
			  $i = 0; 
			
			 if($output['messages']['numRows']>0)
			
			 {
			
			  
			
			   foreach($output['messages']['data'] as $message)
			
			   {
			
				   
			
				   if($message->senderType==1)
			
				   {
			
					$memberId = $message->receiverId;
			
				   }
			
				   else 
			
				   {
			
				   $memberId = $message->senderId;
			
				   }
			
				   
			
				   
			
					 if(!(in_array($memberId,$members)))
			
			   {
			
			   
			
			   $members[] = $memberId;
			
			   $output['msgs'][$i]['message'] = $message->message;
			
			   $output['msgs'][$i]['dateTime'] = $message->dateTime;
			
			   $output['msgs'][$i]['messageId'] = $message->messageId;
			
					 
			
					   $result = $this->clientAllDB_model->getMemberDetails_cld($memberId);   
			
					   if($result['id']>0)
			
					   {
			
						  
			
						  $output['senderDetails'][$message->messageId]['id']    =  $result['id'];
			
						  $output['senderDetails'][$message->messageId]['image'] =  $result['image'];
			
						  $output['senderDetails'][$message->messageId]['uname'] =  $result['uname'];
			
						  $output['senderDetails'][$message->messageId]['name']  =  $result['fname'];
			
			
					   }
			
			
					$i++;
			
				   }
			
			   }
			
			   
			
			 }
			
			
			// removed subscription option
			
			// $subscriptionInfo = $this->clientAllDB_model->getSubscriptionStatus($clientId); 
			
			// if($subscriptionInfo['numRows']>0)
			
			// {
			
			//   $headerOutput['subscriptionStatus'] = 1;
			
			   
			
			//   if($subscriptionInfo['data'][0]->packageId==1)
			
			//   {
			
			// 	$headerOutput['packageId'] = 1;
			
			// 	$headerOutput['package'] = 'BASIC SUBSCRIPTION';
			
			// 	$headerOutput['displayDashboard'] = 0;
			
				
			
			// 	// $output['displayMsgs'] = 0;
			
			//   }
			
			//   else if($subscriptionInfo['data'][0]->packageId==2)
			
			//   {
			
			// 	$headerOutput['packageId'] = 2;
			
			// 	$headerOutput['package'] = 'ADVANCED SUBSCRIPTION';
			
			// 	$headerOutput['displayDashboard'] = 1;
			
				
			
			// //	$output['displayMsgs'] = 1;
			
			//   }
			
			// }
			
			// else
			
			// {
			
			//    $headerOutput['packageId'] = 0;
			
			//    $headerOutput['subscriptionStatus'] = 0;
			
			//    $headerOutput['package'] = '';
			
			//    $headerOutput['displayDashboard'] = 0;
			
				 
			
			// //   $output['displayMsgs'] = 0;
			
			// }
			
			
			$headerOutput['packageId'] = 2;
			$headerOutput['displayDashboard'] = 1;
			
			
			 $headerOutput['pageTitle'] = 'Digiwax Client Messages';
			
			 $headerOutput['wrapperClass'] = 'client';
			
			 
			
			 $unReadMessages = $this->clientAllDB_model->getClientUnreadInbox_cld($clientId); 
			
			 $output['numMessages']  = $unReadMessages['numRows'];
			
			 $headerOutput['numMessages']  = $unReadMessages['numRows'];
			
			 $output['rightTracks'] = $this->clientAllDB_model->getRightTracks_cld(); 
			
			
			
			foreach($output['rightTracks']['data'] as $rightTrack)
			
			{
			
			   $output['trackData'][$rightTrack->id] = $this->clientAllDB_model->getTrackPlays_cld($rightTrack->id); 
			
			}
			
			 
			
			 $inboxMessages = $this->clientAllDB_model->getClientInbox_cld($clientId); 
			
			 $output['numInboxMessages'] = $inboxMessages['numRows'];
			
			// $this->load->view('header_client.php',$headerOutput);
			
			// $this->load->view('client_messages_archived.php',$output);
			
			// $this->load->view('footer_client.php');
			
			// dd($output);
			
			
			 $output['tracks'] = $this->clientAllDB_model->getClientFooterTracks($clientId);
			 return view('clients.dashboard.client_messages_archived', $output);
			
			}
	
/* ---------Archived Message */

			
			public function Client_message_archived(Request $request){
			
			//dd('ggdfdfd');
			
			// fb logout link
			//   $this->load->library('facebook');
			//   $logout_link = url('Logout');
			
			  
			
			//   if(isset($_SESSION['fb_access_token']))
			//    {
			//      $logout_link = $this->facebook->logout_url();	
			//    }
			   
			//   $headerOutput['logout_link'] = $logout_link;
			
			if(empty(Session::get('clientId'))){
				return redirect()->intended('login');
			}
			if(!empty(Session::get('tempClientId'))){
			
				 $output['welcomeMsg'] = 'Thank you for updating your information !';
				 
				 Session::forget('tempClientId');
			}
			
			if(!isset($_GET['mid']) || empty($_GET['mid'])){
				return Redirect::to("Client_messages");   exit;
			}
			$output = array();
			 $output['pageTitle'] = 'Digiwax Client Messages';
			$output['packageId'] = 2;
			
			$clientId = Session::get('clientId');
			$output['sessClientID'] = $clientId;
			
			
			date_default_timezone_set('America/Los_Angeles');
			 
			  // logo 
			$headerOutput['logo'] = $this->clientAllDB_model->getLogo_cld(); 
			
			$clientdata = $this->clientAllDB_model->getClientsDetails_cld($clientId); 

			
			$headerOutput['wrapperClass'] = 'client';
			

			
			
			 $output['msgs'] = array();
			
			 $members = array();
			
			
			$output['conversation'] = $this->clientAllDB_model->getClientArchivedConversation($clientId,$_GET['mid']);
			
		    $result = $this->clientAllDB_model->getMemberDetails_cld($_GET['mid']);   

			if($result['id']>0){
				      

					  $output['memberId']    =  $result['id'];

					  $output['memberImage'] =  $result['image'];

					  $output['memberUname'] =  $result['uname'];

					  $output['memberName']  =  $result['fname'];	   

			}
			
			$headerOutput['packageId'] = 2;
			$headerOutput['displayDashboard'] = 1;
			
			
			 $headerOutput['pageTitle'] = 'Digiwax Client Messages';
			
			 $headerOutput['wrapperClass'] = 'client';
			
			 
			
			 $unReadMessages = $this->clientAllDB_model->getClientUnreadInbox_cld($clientId); 
			
			 $output['numMessages']  = $unReadMessages['numRows'];
			
			 $headerOutput['numMessages']  = $unReadMessages['numRows'];
			
			 $output['rightTracks'] = $this->clientAllDB_model->getRightTracks_cld(); 
			
			
			
			foreach($output['rightTracks']['data'] as $rightTrack)
			
			{
			
			   $output['trackData'][$rightTrack->id] = $this->clientAllDB_model->getTrackPlays_cld($rightTrack->id); 
			
			}
			
			 
			
			 $inboxMessages = $this->clientAllDB_model->getClientInbox_cld($clientId); 
			
			 $output['numInboxMessages'] = $inboxMessages['numRows'];
			

			 return view('clients.dashboard.client_message_archived', $output);
			
			}
			
			public function Client_message_starred(Request $request){
			
			//dd('ggdfdfd');
			
			// fb logout link
			//   $this->load->library('facebook');
			//   $logout_link = url('Logout');
			
			  
			
			//   if(isset($_SESSION['fb_access_token']))
			//    {
			//      $logout_link = $this->facebook->logout_url();	
			//    }
			   
			//   $headerOutput['logout_link'] = $logout_link;
			
			if(empty(Session::get('clientId'))){
				return redirect()->intended('login');
			}
			if(!empty(Session::get('tempClientId'))){
			
				 $output['welcomeMsg'] = 'Thank you for updating your information !';
				 
				 Session::forget('tempClientId');
			}
			
			if(!isset($_GET['mid']) || empty($_GET['mid'])){
				return Redirect::to("Client_messages");   exit;
			}
			$output = array();
			 $output['pageTitle'] = 'Digiwax Client Messages';
			$output['packageId'] = 2;
			
			$clientId = Session::get('clientId');
			$output['sessClientID'] = $clientId;
			
			
			date_default_timezone_set('America/Los_Angeles');
			 
			  // logo 
			$headerOutput['logo'] = $this->clientAllDB_model->getLogo_cld(); 
			
			$clientdata = $this->clientAllDB_model->getClientsDetails_cld($clientId); 

			
			$headerOutput['wrapperClass'] = 'client';
			

			
			
			 $output['msgs'] = array();
			
			 $members = array();
			
			
			$output['conversation'] = $this->clientAllDB_model->getClientStarredConversation($clientId,$_GET['mid']);
			
		    $result = $this->clientAllDB_model->getMemberDetails_cld($_GET['mid']);   

			if($result['id']>0){
				      

					  $output['memberId']    =  $result['id'];

					  $output['memberImage'] =  $result['image'];

					  $output['memberUname'] =  $result['uname'];

					  $output['memberName']  =  $result['fname'];	   

			}
			
			$headerOutput['packageId'] = 2;
			$headerOutput['displayDashboard'] = 1;
			
			
			 $headerOutput['pageTitle'] = 'Digiwax Client Messages';
			
			 $headerOutput['wrapperClass'] = 'client';
			
			 
			
			 $unReadMessages = $this->clientAllDB_model->getClientUnreadInbox_cld($clientId); 
			
			 $output['numMessages']  = $unReadMessages['numRows'];
			
			 $headerOutput['numMessages']  = $unReadMessages['numRows'];
			
			 $output['rightTracks'] = $this->clientAllDB_model->getRightTracks_cld(); 
			
			
			
			foreach($output['rightTracks']['data'] as $rightTrack)
			
			{
			
			   $output['trackData'][$rightTrack->id] = $this->clientAllDB_model->getTrackPlays_cld($rightTrack->id); 
			
			}
			
			 
			
			 $inboxMessages = $this->clientAllDB_model->getClientInbox_cld($clientId); 
			
			 $output['numInboxMessages'] = $inboxMessages['numRows'];
			

			 return view('clients.dashboard.client_message_archived', $output);
			
			}
			

/* ---------Archived Message End */
	
			public function Client_messages_members(Request $request){
			
			//dd('ggdfdfd');
			
			// fb logout link
			//   $this->load->library('facebook');
			//   $logout_link = url('Logout');
			
			  
			
			//   if(isset($_SESSION['fb_access_token']))
			//    {
			//      $logout_link = $this->facebook->logout_url();	
			//    }
			   
			//   $headerOutput['logout_link'] = $logout_link;
			
			if(empty(Session::get('clientId'))){
				return redirect()->intended('login');
			}
			if(!empty(Session::get('tempClientId'))){
			
				 $output['welcomeMsg'] = 'Thank you for updating your information !';
				 
				 Session::forget('tempClientId');
			}
			$output = array();
			 $output['pageTitle'] = 'Digiwax Client Messages';
			$output['packageId'] = 2;
			
			$clientId = Session::get('clientId');
			$output['sessClientID'] = $clientId;
			
			
			date_default_timezone_set('America/Los_Angeles');
			 
			  // logo 
			$headerOutput['logo'] = $this->clientAllDB_model->getLogo_cld(); 
			
			$clientdata = $this->clientAllDB_model->getClientsDetails_cld($clientId); 
			
			//echo "<pre>"; print_r($clientdata); die;
			
			$headerOutput['wrapperClass'] = 'client';
			
			//  header data ends here
			
			// pagination
			$reminder = 0;
			$numPages = 0;
			
			$limit = 10;
			if(isset($_GET['records']))
			{
			$limit = $_GET['records'];
			}

			$output['numRecords'] = $limit;

			$start = 0;
			$currentPageNo = 1;

			if(isset($_GET['page']) && $_GET['page']>1)
			{
			  $start = ($_GET['page']*$limit)-$limit;
			}

			if(isset($_GET['page']))
			{
			  $currentPageNo = $_GET['page'];
			}
			
			
			$output['messages'] = $this->clientAllDB_model->getClientInbox_cld($clientId); 
			
			//$output['members'] = $this->clientAllDB_model->getClientMembersWhoReviewed_cld($clientId);
			$num_records = $this->clientAllDB_model->getClientMembersWhoReviewedCount($clientId);
			
			$numPages = (int)($num_records/$limit);
			$reminder = ($num_records%$limit);
			$output['num_records'] = $num_records;

			if($reminder>0)
			{
			   $numPages = $numPages+1;
			}

			$output['numPages'] = $numPages;
			$output['start'] = $start;
			$output['currentPageNo'] = $currentPageNo;
			$output['currentPage'] = 'Client_messages_members';
			
			   // generate url string

$getArray = array(); $urlString = '?';

if(isset($_GET))
{
  foreach($_GET as $key=>$value)
   {
	 if(strcmp($key,'page')!=0)
	 {
	   $getArray[] = $key.'='.$value;
	 }
   }

  if(count($getArray)>1)
  {
	$urlString .= implode('&',$getArray);
  }
  else if(count($getArray)==1)
  {
	$urlString .= $getArray[0];
  }
  else
  {
	$urlString = '';
  }
}

$output['urlString'] = $urlString;

   // uncomment later

   if(isset($_GET['page'])) {
   if(strlen($urlString)>3)
	  {
		 $param = '&';
	  }
	  else
	  {
		$param = '?';
	  }

if ($_GET['page'] > $numPages && $numPages > 0) {
		 header("location: ".$output['currentPage'].$urlString.$param."page=" . $numPages);
	   exit;
   }
}
			
			$output['members'] = $this->clientAllDB_model->getClientMembersWhoReviewed_cld($clientId, $start,$limit);
            
            if($output['members']['numRows']>0){
                foreach ($output['members']['data'] as $key=>$value){
                    $memberId= $value->id;
                    $result = $this->clientAllDB_model->getMemberDetails_cld($memberId);
                    	if ($result['id'] > 0) {
            			 //   $output['memberId']    =  $result['id'];
            			    $value->image =  $result['image'];
            			 //   $output['memberUname'] =  $result['uname'];
            			 //   $output['memberName']  =  $result['fname'];
            			}
                     
                 }
            }
            



			$headerOutput['packageId'] = 2;
			$headerOutput['displayDashboard'] = 1;
			$unReadMessages = $this->clientAllDB_model->getClientUnreadInbox_cld($clientId);
			$output['numMessages']  = $unReadMessages['numRows'];
			$headerOutput['numMessages']  = $unReadMessages['numRows'];
			
			$output['rightTracks'] = $this->clientAllDB_model->getRightTracks_cld(); 
			
			
			
			foreach($output['rightTracks']['data'] as $rightTrack)
			
			{
			
			   $output['trackData'][$rightTrack->id] = $this->clientAllDB_model->getTrackPlays_cld($rightTrack->id); 
			
			}
			
			// $this->load->view('header_client.php', $headerOutput);
			// $this->load->view('client_messages_members.php', $output);
			// $this->load->view('footer_client.php');
			
			// dd($output);
			 $output['tracks'] = $this->clientAllDB_model->getClientFooterTracks($clientId);
		
			 return view('clients.dashboard.client_messages_members', $output);
			
			}
			
			
			public function Client_messages_conversation(Request $request){
			
			//dd('ggdfdfd');
			
			// fb logout link
			//   $this->load->library('facebook');
			//   $logout_link = url('Logout');
			
			  
			
			//   if(isset($_SESSION['fb_access_token']))
			//    {
			//      $logout_link = $this->facebook->logout_url();	
			//    }
			   
			//   $headerOutput['logout_link'] = $logout_link;
			
			if(empty(Session::get('clientId'))){
				return redirect()->intended('login');
			}
			if(!empty(Session::get('tempClientId'))){
			
				 $output['welcomeMsg'] = 'Thank you for updating your information !';
				 
				 Session::forget('tempClientId');
			}
			$output = array();
			 $output['pageTitle'] = 'Digiwax Client Messages Conversation';
			$output['packageId'] = 2;
			
			$clientId = Session::get('clientId');
			$output['sessClientID'] = $clientId;
			
			
			date_default_timezone_set('America/Los_Angeles');
			 
			  // logo 
			$headerOutput['logo'] = $this->clientAllDB_model->getLogo_cld(); 
			
			$clientdata = $this->clientAllDB_model->getClientsDetails_cld($clientId); 
			
			//echo "<pre>"; print_r($clientdata); die;
			
			$headerOutput['wrapperClass'] = 'client';
			
			//  header data ends here
			
			
			
			$output['mid'] = $_GET['mid'];
			
			$output['archiveMsgs'] = array();
			
			$output['starMsgs'] = array();
			
			
			
			// ajax response to load new msgs
			
			if(isset($_GET['getConversation']) && isset($_GET['mid']))
			
			{
			
			
			
			$output['conversation'] = $this->clientAllDB_model->getConversation_cld($_GET['mid'],$clientId); 
			
			
			
			if(isset($output['conversation']['numRows']) && $output['conversation']['numRows']>0)
			
			{
			
			
			
			 foreach($output['conversation']['data'] as $message)
			
			 {
			
			 
			
				$clientStarred = $this->clientAllDB_model->isClientStarred_cld($clientId,$message->messageId); 
			
				if($clientStarred>0)
			
				{ $output['starMsgs'][] = $message->messageId; }
			
				
			
				$clientArchived = $this->clientAllDB_model->isClientArchived_cld($clientId,$message->messageId); 
			
				if($clientArchived>0)
			
				{ $output['archiveMsgs'][] = $message->messageId; } 
			
			 }
			
			}
			
			
			
			  //$this->load->view('client_get_conversation.php',$output); 
			  return view('clients.dashboard.client_get_conversation', $output);
			
			  exit;
			
			}
			
			
			
			// send message
			
			if(isset($_GET['message']) && isset($_GET['mid']))
			
			{
			
			$result = $this->clientAllDB_model->sendClientMessage_cld($clientId,$_GET['message'],$_GET['mid']); 
			
			$date = date('M d, Y');
			
			
			
			if($result>0)
			
			{
			
			 $response = array('response'=>1, 'dt' => $date);
			
			}
			
			else
			
			{
			
			 $response = array('response'=>0);
			
			}
			
			
			
			echo json_encode($response);
			
			
			
			exit;
			
			}	
			
			
			
			// archive message
			
			if(isset($_GET['messageId']) && isset($_GET['mid']) && isset($_GET['archive']))
			
			{
			
			$result = $this->clientAllDB_model->archiveClientMessage_cld($clientId,$_GET['messageId']); 
			
			
			
			
			
			if($result['result']>0 && $result['transaction']>0)
			
			{
			
			 $response = array('response'=>1, 'message'=>'message archived');
			
			}
			
			else if($result['result']>0 && $result['transaction']==0)
			
			{
			
			 $response = array('response'=>1, 'message'=>'message un archive');
			
			}
			
			else
			
			{
			
			 $response = array('response'=>0);
			
			}
			
			
			
			echo json_encode($response);
			
			
			
			exit;
			
			}
			
			
			
			// star message
			
			if(isset($_GET['messageId']) && isset($_GET['mid']) && isset($_GET['star']))
			
			{
			
			$result = $this->clientAllDB_model->starClientMessage_cld($clientId,$_GET['messageId']); 
			
			
			
			
			
			if($result['result']>0 && $result['transaction']>0)
			
			{
			
			 $response = array('response'=>1, 'message'=>'message marked star');
			
			}
			
			else if($result['result']>0 && $result['transaction']==0)
			
			{
			
			 $response = array('response'=>1, 'message'=>'message marked unstar');
			
			}
			
			else
			
			{
			
			 $response = array('response'=>0);
			
			}
			
			
			
			echo json_encode($response);
			
			
			
			exit;
			
			}
			
			
			
			
			$output['memberInfo'] = $this->clientAllDB_model->getMemberDetails_cld($_GET['mid']); 
			
			if($output['memberInfo']['id']<1)
			
			{
			
			header("location: ".url("Client_messages"));   exit;
			
			}
			
			
			
			// make msgs read
			
			$this->clientAllDB_model->makeClientMsgRead_cld($_GET['mid'],$clientId); 
			
			 
			
			$output['conversation'] = $this->clientAllDB_model->getConversation_cld($_GET['mid'],$clientId); 
			
			
			
			if(isset($output['conversation']['numRows']) && $output['conversation']['numRows']>0)
			
			{
			
			
			
			 foreach($output['conversation']['data'] as $message)
			
			 {
			
			 
			
				$clientStarred = $this->clientAllDB_model->isClientStarred_cld($clientId,$message->messageId); 
			
				if($clientStarred>0)
			
				{ $output['starMsgs'][] = $message->messageId; }
			
				
			
				$clientArchived = $this->clientAllDB_model->isClientArchived_cld($clientId,$message->messageId); 
			
				if($clientArchived>0)
			
				{ $output['archiveMsgs'][] = $message->messageId; }
			
			 }
			
			}
			
			
			
			
			
			// removed subscription option
			//    $subscriptionInfo = $this->clientAllDB_model->getSubscriptionStatus($clientId); 
			
			//    if($subscriptionInfo['numRows']>0)
			
			//    {
			
			// 	 $headerOutput['subscriptionStatus'] = 1;
			
			 
			
			// 	 if($subscriptionInfo['data'][0]->packageId==1)
			
			// 	 {
			
			// 	   $headerOutput['package'] = 'BASIC SUBSCRIPTION';
			
			// 	 }
			
			// 	 else if($subscriptionInfo['data'][0]->packageId==2)
			
			// 	 {
			
			// 	   $headerOutput['package'] = 'ADVANCED SUBSCRIPTION';
			
			// 	 }
			
			 
			
			//    }
			
			//    else
			
			//    {
			
			// 	  $headerOutput['subscriptionStatus'] = 0;
			
			// 	  $headerOutput['package'] = '';
			
			//    }
			
			
			$unReadMessages = $this->clientAllDB_model->getClientUnreadInbox_cld($clientId); 
			
			$output['numMessages']  = $unReadMessages['numRows'];
			
			$headerOutput['numMessages']  = $unReadMessages['numRows'];
			
			$output['rightTracks'] = $this->clientAllDB_model->getRightTracks_cld(); 
			
			
			
			foreach($output['rightTracks']['data'] as $rightTrack)
			
			{
			
			   $output['trackData'][$rightTrack->id] = $this->clientAllDB_model->getTrackPlays_cld($rightTrack->id); 
			
			}
			
			
			//    $this->load->view('header_client.php',$headerOutput);
			
			//    $this->load->view('client_messages_conversation.php',$output);
			
			//    $this->load->view('footer_client.php');
			
			
			// dd($output);
			 $output['tracks'] = $this->clientAllDB_model->getClientFooterTracks($clientId);
			 return view('clients.dashboard.client_messages_conversation', $output);
			
			}
			
			public function Client_payment1(Request $request){
			
			//dd('ggdfdfd');
			
			// fb logout link
			//   $this->load->library('facebook');
			//   $logout_link = url('Logout');
			
			  
			
			//   if(isset($_SESSION['fb_access_token']))
			//    {
			//      $logout_link = $this->facebook->logout_url();	
			//    }
			   
			//   $headerOutput['logout_link'] = $logout_link;
			
			if(empty(Session::get('clientId'))){
				return redirect()->intended('login');
			}
			if(!empty(Session::get('tempClientId'))){
			
				 $output['welcomeMsg'] = 'Thank you for updating your information !';
				 
				 Session::forget('tempClientId');
			}
			$output = array();
			 $output['pageTitle'] = 'Digiwax Client Payment';
			$output['packageId'] = 2;
			
			$clientId = Session::get('clientId');
			$output['sessClientID'] = $clientId;
			
			
			date_default_timezone_set('America/Los_Angeles');
			 
			  // logo 
			$headerOutput['logo'] = $this->clientAllDB_model->getLogo_cld(); 
			
			$clientdata = $this->clientAllDB_model->getClientsDetails_cld($clientId); 
			
			//echo "<pre>"; print_r($clientdata); die;
			
			$headerOutput['wrapperClass'] = 'register';
			
			$output['wrapperClass'] = 'register';
			
			//  header data ends here
			
			$headerOutput['packageId'] = 2;
			$headerOutput['displayDashboard'] = 1;
			
			$selPack =	$request->all();
			//dd($selPack);
			$result = '';
			if($selPack)
			{
				if (isset($selPack['basic']))
				{
					$result = $this->clientAllDB_model->subscribeClient_cld(1);
				} elseif (isset($selPack['regular']))
				{
					$result = $this->clientAllDB_model->subscribeClient_cld(2);
				} elseif (isset($selPack['standard']))
				{
					$result = $this->clientAllDB_model->subscribeClient_cld(3);
				} elseif (isset($selPack['advance']))
				{
					$result = $this->clientAllDB_model->subscribeClient_cld(4);
				} elseif (isset($selPack['silver-artist']))
				{
					$result = $this->clientAllDB_model->subscribeClient_cld(5);
				} elseif (isset($selPack['gold-artist']))
				{
					$result = $this->clientAllDB_model->subscribeClient_cld(6);
				}elseif (isset($selPack['purple-artist']))
				{
					$result = $this->clientAllDB_model->subscribeClient_cld(7);
				}elseif (isset($selPack['silver-label']))
				{
					$result = $this->clientAllDB_model->subscribeClient_cld(8);
				} elseif (isset($selPack['gold-label']))
				{
					$result = $this->clientAllDB_model->subscribeClient_cld(9);
				}elseif (isset($selPack['purple-label']))
				{
					$result = $this->clientAllDB_model->subscribeClient_cld(10);
				}
				if ($result > 0)
				{
				//	$_SESSION['subscriptionId'] = $result;
					Session::put('subscriptionId', $result);
					setcookie('subscriptionId', $result, 0, "/");
					header("location: ".url("Client_payment4"));
					exit;
				} else
				{
					header("location: ".url("Client_payment1?error=1"));
					exit;
				}
			
			}
			
			$output['rightTracks'] = $this->clientAllDB_model->getRightTracks($clientId);
			
			if($output['rightTracks']['numRows'] > 0){
			foreach ($output['rightTracks']['data'] as $rightTrack) {
				 $output['trackData'][$rightTrack->id] = $this->clientAllDB_model->getTrackPlays($rightTrack->id);
			}
		}
			
			// $this->load->view('header_client.php', $headerOutput);
			// $this->load->view('client_payment1.php');
			
			// dd($output);
			return view('clients.dashboard.client_payment1', $output);
			
			}	
			
			
			public function Client_payment4(Request $request){
			
			//dd('ggdfdfd');
			
			// fb logout link
			//   $this->load->library('facebook');
			//   $logout_link = url('Logout');
			
			  
			
			//   if(isset($_SESSION['fb_access_token']))
			//    {
			//      $logout_link = $this->facebook->logout_url();	
			//    }
			   
			//   $headerOutput['logout_link'] = $logout_link;
			
			if(empty(Session::get('clientId'))){
				return redirect()->intended('login');
			}
			if(!empty(Session::get('tempClientId'))){
			
				 $output['welcomeMsg'] = 'Thank you for updating your information !';
				 
				 Session::forget('tempClientId');
			}
			$output = array();
			 $output['pageTitle'] = 'Digiwax Client Payment';
			$output['packageId'] = 2;
			
			$clientId = Session::get('clientId');

			
			//$subscriptionId = Session::get('subscriptionId');
			$subscriptionId = $_COOKIE['subscriptionId'];
			
			$output['sessClientID'] = $clientId;
			
			
			date_default_timezone_set('America/Los_Angeles');
			 
			  // logo 
			$headerOutput['logo'] = $this->clientAllDB_model->getLogo_cld(); 
			
			$clientdata = $this->clientAllDB_model->getClientsDetails_cld($clientId); 
			
			//echo "<pre>"; print_r($clientdata); die;
			
			$headerOutput['wrapperClass'] = 'register';
			
			$output['wrapperClass'] = 'register';
			
			//  header data ends here
			
			
			$headerOutput['packageId'] = 2;
			$headerOutput['displayDashboard'] = 1;
			
			//require_once('Config.php');
			
			$output['subscriptionDetails'] = $this->clientAllDB_model->getSubscriptionDetails_cld($subscriptionId);
			if ($output['subscriptionDetails']['numRows'] < 1)
			{
				header("location: ".url("Client_payment1"));
				exit;
			}
			if (isset($clientId)){
				$clientInfo = $this->clientAllDB_model->getClientInfo_cld($clientId);
				$output['clientemail'] = urldecode($clientInfo['data'][0]->email);
			}
			$packId = $output['subscriptionDetails']['data'][0]->packageId;
			switch ($packId){
				case 1:
					$output['cost'] = '35000';
					$output['displayCost'] = '350';
					// $output['title'] = 'BASIC';
					// $output['cost'] = '20000';
					// $output['displayCost'] = '200';
					$output['title'] = 'REGULAR';
					$output['packtype'] = 'one-time';
				break;
				case 2:
					$output['cost'] = '50000';
					$output['displayCost'] = '500';
					// $output['title'] = 'REGULAR';
					// $output['cost'] = '35000';
					// $output['displayCost'] = '350';
					$output['title'] = 'BASIC';
					$output['packtype'] = 'one-time';
				break;
				case 3:
					$output['cost'] = '75000';
					$output['displayCost'] = '750';
					// $output['title'] = 'STANDARD';
					// $output['cost'] = '50000';
					// $output['displayCost'] = '500';
					$output['title'] = 'ADVANCE';
					$output['packtype'] = 'one-time';
				break;
				case 4:
					$output['cost'] = '100000';
					$output['displayCost'] = '1000';
					// $output['title'] = 'ADVANCE';
					// $output['cost'] = '75000';
					// $output['displayCost'] = '750';
					$output['title'] = 'ADVANCE PLUS';
					$output['packtype'] = 'one-time';
				break;
				case 5:
					/*$output['cost'] = '24900';
					$output['displayCost'] = '249';
					$output['title'] = 'SILVER';*/
					$output['cost'] = '15000';
					$output['displayCost'] = '150';
					$output['title'] = 'SILVER';
					$output['packtype'] = 'subs';
					break;
				case 6:
					/*$output['cost'] = '45000';
					$output['displayCost'] = '450';*/
					$output['cost'] = '32500';
					$output['displayCost'] = '325';
					$output['title'] = 'GOLD';
					$output['packtype'] = 'subs';
					break;
				case 7:
					/*$output['cost'] = '75000';
					$output['displayCost'] = '750';
					$output['title'] = 'PURPLE';*/
					$output['cost'] = '45000';
					$output['displayCost'] = '450';
					$output['title'] = 'PLATINUM';
					$output['packtype'] = 'subs';
					break;
				case 8:
					/*$output['cost'] = '99900';
					$output['displayCost'] = '999';*/
					$output['cost'] = '75000';
					$output['displayCost'] = '750';
					$output['title'] = 'SILVER';
					$output['packtype'] = 'subs';
					break;
				case 9:
					/*$output['cost'] = '165000';
					$output['displayCost'] = '1650';*/
					$output['cost'] = '140000';
					$output['displayCost'] = '1400';
					$output['title'] = 'GOLD';
					$output['packtype'] = 'subs';
					break;
				case 10:
					/*$output['cost'] = '199900';
					$output['displayCost'] = '1999';*/
					$output['cost'] = '175000';
					$output['displayCost'] = '1750';
					$output['title'] = 'PURPLE';
					$output['packtype'] = 'subs';
					break;
				default:
					header("location: ".url("Client_payment1"));
			}
			
			$output['rightTracks'] = $this->clientAllDB_model->getRightTracks($clientId);
			
			
			$output['subscriptionId'] = $subscriptionId;
			// $this->load->view('header_client.php', $headerOutput);
			// $this->load->view('client_payment4.php', $output);
			//dd($output);
			return view('clients.dashboard.client_payment4', $output);
			
			}

			public function client_submit_track_preview(Request $request){

				//dd('ggdfdfd');
		
				// fb logout link
				//   $this->load->library('facebook');
				//   $logout_link = url('Logout');
		
				  
		
				//   if(isset($_SESSION['fb_access_token']))
				//    {
				//      $logout_link = $this->facebook->logout_url();	
				//    }
				   
				//   $headerOutput['logout_link'] = $logout_link;
		
				if(empty(Session::get('clientId'))){
					return redirect()->intended('login');
				}
				if(!empty(Session::get('tempClientId'))){
		
					 $output['welcomeMsg'] = 'Thank you for updating your information !';
					 
					 Session::forget('tempClientId');
				}
				$output = array();
				 $output['pageTitle'] = 'Digiwax Client Submit Track Preview';
				$output['packageId'] = 2;
				
				$clientId = Session::get('clientId');
	
				$subscriptionId = Session::get('subscriptionId');
				$output['sessClientID'] = $clientId;
		
		
				date_default_timezone_set('America/Los_Angeles');
				 
				  // logo 
				$headerOutput['logo'] = $this->clientAllDB_model->getLogo_cld(); 
		
				$clientdata = $this->clientAllDB_model->getClientsDetails_cld($clientId); 
				
				//echo "<pre>"; print_r($clientdata); die;
		
				$headerOutput['wrapperClass'] = 'register';
	
				$output['wrapperClass'] = 'register';
		
				//  header data ends here
	
	   
				if(isset($_GET['tId']) && isset($_POST['confirmPreview']))
	   
				{
	   
				  $result = $this->clientAllDB_model->confirmSubmittedTrack_cld($_GET['tId']); 
	   
				  
	   
				  if($result>0)
	   
				  {
	   
					 header("location: ".url("Client_tracks?trackAdded=success"));   exit;
	   
				  }
	   
				  else
	   
				  {
	   
					 header("location: ".url("client_submit_track_preview?tId=".$_GET['tId']."&error=1"));   exit;
	   
				  }
	   
				}
	   
				
	   
				$where = "where id = '". $_GET['tId'] ."' and previewTrack = '0'";
	   
				$output['track'] = $this->clientAllDB_model->getSubmittedTrack_cld($where); 
	   
				if($output['track']['numRows']<1)
	   
				{
	   
				   header("location: ".url("Client_tracks"));   exit; 
	   
				}
	   
				
	   
			   //removing client Subscription
	   
			// 	$subscriptionInfo = $this->clientAllDB_model->getSubscriptionStatus($clientId); 
	   
			//    if($subscriptionInfo['numRows']>0)
	   
			//    {
	   
			// 	 $headerOutput['subscriptionStatus'] = 1;
	   
				  
	   
			// 	 if($subscriptionInfo['data'][0]->packageId==1)
	   
			// 	 {
	   
			// 	   $headerOutput['packageId'] = 1;
	   
			// 	   $headerOutput['package'] = 'BASIC SUBSCRIPTION';
	   
			// 	   $headerOutput['displayDashboard'] = 0;
	   
				   
	   
				   
	   
			// 	 }
	   
			// 	 else if($subscriptionInfo['data'][0]->packageId==2)
	   
			// 	 {
	   
			// 	   $headerOutput['packageId'] = 2;
	   
			// 	   $headerOutput['package'] = 'ADVANCED SUBSCRIPTION';
	   
			// 	   $headerOutput['displayDashboard'] = 1;
	   
				   
	   
				   
	   
			// 	 }
	   
			//    }
	   
			//    else
	   
			//    {
	   
			// 	  $headerOutput['packageId'] = 0;
	   
			// 	  $headerOutput['subscriptionStatus'] = 0;
	   
			// 	  $headerOutput['package'] = '';
	   
			// 	  $headerOutput['displayDashboard'] = 0;
	   
					
	   
				 
	   
			//    }
	   
	
	   
			   $headerOutput['packageId'] = 2;
			   $headerOutput['displayDashboard'] = 1;
			   
	   
				$footerOutput['tracks'] = $this->clientAllDB_model->getClientFooterTracks($clientId); 
	   
			   
	   
			   $output['rightTracks1'] = $this->clientAllDB_model->getRightTracks_cld(); 
	   
			   foreach($output['rightTracks1']['data'] as $rightTrack)
	   
			   {
	   
				   $output['trackData'][$rightTrack->id] = $this->clientAllDB_model->getTrackPlays_cld($rightTrack->id); 
	   
			   }
	   
	
		   
	   
				$unReadMessages = $this->clientAllDB_model->getClientUnreadInbox_cld($clientId); 
	   
				$output['numMessages']  = $unReadMessages['numRows'];
	   
				$headerOutput['numMessages']  = $unReadMessages['numRows'];
	
				$output['rightTracks'] = $this->clientAllDB_model->getRightTracks_cld(); 
	
			
	
				foreach($output['rightTracks']['data'] as $rightTrack)
		
				{
		
					$output['trackData'][$rightTrack->id] = $this->clientAllDB_model->getTrackPlays_cld($rightTrack->id); 
		
				}
				
				  $output['tracks'] = $this->clientAllDB_model->getClientFooterTracks($clientId);
	   
	
			//    $this->load->view('header_client.php',$headerOutput);
	   
			//    $this->load->view('client_submit_track_preview.php',$output);
	   
			//    $this->load->view('footer_client.php',$footerOutput);
	
			//dd($output);
	
				return view('clients.dashboard.client_submit_track_preview', $output);
	
			}
	
			public function Client_submit_track_edit(Request $request){
	
				//dd('ggdfdfd');
		
				// fb logout link
				//   $this->load->library('facebook');
				//   $logout_link = url('Logout');
		
				  
		
				//   if(isset($_SESSION['fb_access_token']))
				//    {
				//      $logout_link = $this->facebook->logout_url();	
				//    }
				   
				//   $headerOutput['logout_link'] = $logout_link;
		
				if(empty(Session::get('clientId'))){
					return redirect()->intended('login');
				}
				if(!empty(Session::get('tempClientId'))){
		
					 $output['welcomeMsg'] = 'Thank you for updating your information !';
					 
					 Session::forget('tempClientId');
				}
				$output = array();
				 $output['pageTitle'] = 'Digiwax Client Edit Track';
				$output['packageId'] = 2;
				
				$clientId = Session::get('clientId');
	
				$subscriptionId = Session::get('subscriptionId');
				$output['sessClientID'] = $clientId;
		
		
				date_default_timezone_set('America/Los_Angeles');
				 
				  // logo 
				$headerOutput['logo'] = $this->clientAllDB_model->getLogo_cld(); 
		
				$clientdata = $this->clientAllDB_model->getClientsDetails_cld($clientId); 
				
				//echo "<pre>"; print_r($clientdata); die;
		
				$headerOutput['wrapperClass'] = 'register';
	
				$output['wrapperClass'] = 'client';
		
				//  header data ends here
	
	   
			  // removing client Subscription
	   
			// 	 $subscriptionInfo = $this->clientAllDB_model->getSubscriptionStatus($clientId); 
	   
			//    if($subscriptionInfo['numRows']>0)
	   
			//    {
	   
			// 	 $headerOutput['subscriptionStatus'] = 1;
	   
				  
	   
			// 	 if($subscriptionInfo['data'][0]->packageId==1)
	   
			// 	 {
	   
			// 	   $headerOutput['packageId'] = 1;
	   
			// 	   $headerOutput['package'] = 'BASIC SUBSCRIPTION';
	   
			// 	   $headerOutput['displayDashboard'] = 0;
	   
				   
	   
				   
	   
			// 	 }
	   
			// 	 else if($subscriptionInfo['data'][0]->packageId==2)
	   
			// 	 {
	   
			// 	   $headerOutput['packageId'] = 2;
	   
			// 	   $headerOutput['package'] = 'ADVANCED SUBSCRIPTION';
	   
			// 	   $headerOutput['displayDashboard'] = 1;
	   
				   
	   
				   
	   
			// 	 }
	   
			//    }
	   
			//    else
	   
			//    {
	   
			// 	  $headerOutput['packageId'] = 0;
	   
			// 	  $headerOutput['subscriptionStatus'] = 0;
	   
			// 	  $headerOutput['package'] = '';
	   
			// 	  $headerOutput['displayDashboard'] = 0;
	   
					
	   
				 
	   
			//    }
				
	   
			   $headerOutput['packageId'] = 2;
			   $headerOutput['displayDashboard'] = 1;
	   
	   
				/*			   
				
				echo ini_get('upload_max_filesize').'<br/>';
				
				
				
				echo ini_get("upload_max_size").'<br/>';
				
						*/
	   
	
				if(isset($_POST['updateSubmittedTrack']) && isset($_GET['tId']))
	   
				{
	   
	
	   
				 // image validation
	   
			// 	  if(isset($_FILES['artWork']['name']) && strlen($_FILES['artWork']['name'])>4)
	   
			//    {
	   
			// 	$imgDimensions =  getimagesize($_FILES['artWork']['tmp_name']);
	   
			// 	if(($imgDimensions[0]<300) || ($imgDimensions[1]<300)) 
	   
			// 	{
	   
			// 	  header("location: ".url("Client_submit_track_edit?tId=".$_GET['tId']."&invalidSize=1".$getString));   exit;
	   
			// 	}
	   
			// 	}
		
	   
				$result = $this->clientAllDB_model->updateClientSubmittedTrack_cld($_POST,$clientId,$_GET['tId']); 
	   
	
	   
				 // art work image upload
	   
				if(isset($_FILES['artWork']['name']) && strlen($_FILES['artWork']['name'])>4)
	   
				{
	   
				$date_time_array = getdate(time());
	   
					$artWorkImageName =	$date_time_array["year"].$date_time_array["mon"].$date_time_array["mday"].$date_time_array["hours"].$date_time_array["minutes"].$date_time_array["seconds"];
	
							$artWork = $request->file('artWork') ;
			
							//Display File Name
							$filename = $artWork->getClientOriginalName();
							// echo 'File Name: '.$filename;
						
							
							//Display File Extension
							$file_extension = $artWork->getClientOriginalExtension();
							// echo 'File Extension: '.$file_extension;
						
							
							//Display File Real Path
							$real_path = $artWork->getRealPath();
							// echo 'File Real Path: '.$real_path;
						
							
							//Display File Size
							$file_size = $artWork->getSize();
							// echo 'File Size: '.$file_size;
	
							// image validation
							// if(($file_size<300)) 
							// {
							// header("location: ".url("client_submit_track?invalidSize=1".$getString));   exit;
							// }
						
							
							//Display File Mime Type
							$file_mime_type = $artWork->getMimeType();
							// echo 'File Mime Type: '.$file_mime_type;
						
							$destination_path = base_path('ImagesUp/');
						// die($destination_path);
	
							//Display Destination Path
							if(empty($destination_path)){
							$destination_path = public_path('uploads/');
							} else {
							$destination_path = $destination_path;
							}
	
							// // echo 'File Destination Path: '.$destination_path;
							// if(!File::isDirectory($destination_path)) {
							//     File::makeDirectory($destination_path, 0777, true, true);
							// }
	
							$image_name = $clientId . '_' . $artWorkImageName .'.'. $file_extension;
							// $image_name = preg_replace('/[^a-zA-Z0-9_.]/', '_', $image_name);
	
							$uploaded_data = $artWork->move( $destination_path , $image_name );
							
							 $folder= '13187487324';
                             $metadata = $this->uploadImage_to_pcloud($destination_path, $image_name, $folder);
                    
                        	$pcloudFileId = $metadata->metadata->fileid;
                        	$parentfolderid = $metadata->metadata->parentfolderid;
                        	@unlink($destination_path.$image_name);
             
							
	
							if(!empty( $uploaded_data )){
							// die('file');
		
								$this->clientAllDB_model->updateClientSubmittedTrackArtWork($_GET['tId'],$image_name,$clientId,$pcloudFileId,$parentfolderid); 
	
							}
	
							else{
	
							//header("location: " . url("admin/album/edit/?aid=" . $_GET['aid'] . "&error=1"));
							}
	
	
	   
					//    $config['upload_path']          = './ImagesUp/';
	   
					//    $config['allowed_types']        = 'gif|jpg|png';
	   
					//    $config['max_size']             = 100;
	   
					//  //  $config['max_width']            = 1024;
	   
					// //   $config['max_height']           = 768;
	   
					//    $config['file_name']           = $clientId.'_'.$artWorkImageName;
	   
					   
	   
					// 	$ext = explode('.',$_FILES['artWork']['name']);
	   
					// 	$count = count($ext);
	   
					// 	$ext = $ext[$count-1];
	   
	   
	   
					//  //  $this->load->library('upload', $config);
	   
					//  $this->upload->initialize($config);
	   
	   
	   
					//    if ( ! $this->upload->do_upload('artWork'))
	   
					//    {
	   
					// 		   $error = array('error' => $this->upload->display_errors());
	   
					// 		   //    $this->load->view('upload_form', $error);
	   
					//    }
	   
					//    else
	   
					//    {
	   
					// 	 $data = array('upload_data' => $this->upload->data());
	   
					// 	 $this->clientAllDB_model->updateClientSubmittedTrackArtWork_cld($_GET['tId'],$config['file_name'].'.'.$ext,$clientId); 	
	   
	   
	   
					// 		 //  $this->load->view('Client_submit_track', $data);
	   
					//    }
	   
	
	   
					   }
	   
			   
	   
		   /*
	   
			$countVersion =  $_POST['numVersion'];
	   
			   
	   
				// amr audio files upload
	   
			   for($i=1;$i<=$countVersion;$i++)
	   
			   {
	   
			   
	   
				if(isset($_FILES['amr'.$i])) 
	   
				{
	   
				
	   
		   
	   
			   
	   
				$date_time_array = getdate(time());
	   
		   $amrfile1 =	$date_time_array["year"].$date_time_array["mon"].$date_time_array["mday"].$date_time_array["hours"].$date_time_array["minutes"].$date_time_array["seconds"];
	   
			   
	   
			   
	   
					   $config['upload_path']          = './amr/';
	   
					   $config['allowed_types']        = 'gif|jpg|png|mp3';
	   
					   $config['max_size']             = 9999999999999;
	   
					   $config['max_width']            = 1024;
	   
					   $config['max_height']           = 768;
	   
					   $config['file_name']           = $clientId.'_'.$i.'_'.$amrfile1;
	   
					   
	   
						$ext = explode('.',$_FILES['amr'.$i]['name']);
	   
						$count = count($ext);
	   
						$ext = $ext[$count-1];
	   
	   
	   
					  // $this->load->library('upload', $config);
	   
					   
	   
					   $this->upload->initialize($config);
	   
	   
	   
					   if ( ! $this->upload->do_upload('amr'.$i))
	   
					   {
	   
							   $error = array('error' => $this->upload->display_errors());
	   
						   //    $this->load->view('upload_form', $error);
	   
					   }
	   
					   else
	   
					   {
	   
					   
	   
						 $version = $_POST['version'.$i];
	   
						 $data = array('upload_data' => $this->upload->data());
	   
						 $functionName = "addClientTrackAmr".$i;
	   
						 $this->clientAllDB_model->$functionName($result,$config['file_name'].'.'.$ext,$clientId,$version); 	
	   
	   
	   
							 //  $this->load->view('Client_submit_track', $data);
	   
					   }
	   
					   
	   
				   
	   
		   
	   
				 }
	   
				 }
	   
				 */
	   
	
				  
	   
				  if($result>0)
	   
				  {
	   
				  header("location: ".url("client_submit_track_preview?tId=".$_GET['tId']."&updated=1"));   exit;
	   
					
	   
				  }
	   
				  else
	   
				  {
	   
				  header("location: ".url("client_submit_track_edit?tId=".$_GET['tId']."&error=1"));   exit;
	   
				  }
	   
				  
	   
					   
	   
				}		
	   
		
	   
				if(isset($_GET['updated']))				
	   
				   {
	   
					 
	   
					  $output['alert_class'] = 'success-msg';
	   
					  $output['alert_message'] = 'Track updated  successfully !';
	   
				   
	   
				   }
	   
				   else if(isset($_GET['error']))				
	   
				   {
	   
					  $output['alert_class'] = 'error-msg';
	   
					  $output['alert_message'] = 'Error occured, please try again.';
	   
				   }
	   
			   
	   
			   $where = "where id = '". $_GET['tId'] ."'";
	   
			   $output['track'] = $this->clientAllDB_model->getSubmittedTrack_cld($where); 
	   
			   $footerOutput['tracks'] = $this->clientAllDB_model->getClientFooterTracks($clientId); 
	   
			   
	   
			   
	   
			   $output['rightTracks'] = $this->clientAllDB_model->getRightTracks_cld(); 
	   
			   foreach($output['rightTracks']['data'] as $rightTrack)
	   
			   {
	   
				   $output['trackData'][$rightTrack->id] = $this->clientAllDB_model->getTrackPlays_cld($rightTrack->id); 
	   
			   }
	   
	
	   
			 $unReadMessages = $this->clientAllDB_model->getClientUnreadInbox_cld($clientId); 
	   
				$output['numMessages']  = $unReadMessages['numRows'];
	   
				$headerOutput['numMessages']  = $unReadMessages['numRows'];
	   
				
	   
				$output['genres'] = $this->clientAllDB_model->getGenres_cld(); 
	   
				$output['subGenres'] = $this->clientAllDB_model->getSubGenres_cld($output['track']['data'][0]->genreId); 
	   
		   
			//    $this->load->view('header_client.php',$headerOutput);
	   
			//    $this->load->view('client_submit_track_edit.php',$output);
	   
			//    $this->load->view('footer_client.php',$footerOutput);
		//	dd($output);
	
				return view('clients.dashboard.client_submit_track_edit', $output);
	
			}

	function track_review_members_list()

	{ 
		if(empty(Session::get('clientId'))){
			return redirect()->intended('login');
		}
		if(!empty(Session::get('tempClientId'))){
		
			 $output['welcomeMsg'] = 'Thank you for updating your information !';
			 
			 Session::forget('tempClientId');
		}
		$output = array();
		$output['pageTitle'] = 'Digiwax Client track review';
		
		$clientId = Session::get('clientId');
		$output['sessClientID'] = $clientId;
		
		
		date_default_timezone_set('America/Los_Angeles');
		 
		  // logo 
		$output['logo'] = $this->clientAllDB_model->getLogo_cld(); 
		
		$clientdata = $this->clientAllDB_model->getClientsDetails_cld($clientId); 
		
		//echo "<pre>"; print_r($clientdata); die;
		
		$headerOutput['wrapperClass'] = 'client';
		
		//  header data ends here


	

		 $pageTitle = array();

		 $footerOutput = array();
 

		//$where = "where tracks.id = '". $_GET['tid'] ."'";

		//$output['tracks'] = $this->tracksdb->getTrack($where); 

		

		if($_GET['graphId']==1)

		{

			$where = "tracks_reviews.track = '". $_GET['tid'] ."' and tracks_reviews.whatrate = '". $_GET['val'] ."'";

			

			$pageTitle[1] = 'Members list who choose '.$_GET['label'].' for - WHAT DO YOU THINK ABOUT THIS SONG?';

		}

		else if($_GET['graphId']==2)

		{

		

		   $value = explode('-OR-',$_GET['val']);

		   

		   $where = "tracks_reviews.track = '". $_GET['tid'] ."'";

		   $where .= " AND (tracks_reviews.whereheard = '".$value[0]."' OR tracks_reviews.whereheard = '". $value[1] ."')";

		   

		    $pageTitle[2] = 'Members list who choose '.$_GET['label'].' for - WHERE DID YOU HEAR THIS SONG FIRST?';

		  

		}

		else if($_GET['graphId']==3)

		{

		 	$where = "tracks_reviews.track = '". $_GET['tid'] ."' and tracks_reviews.alreadyhave = '". $_GET['val'] ."'";

		    $pageTitle[3] = 'Members list who choose '.$_GET['label'].' for - DO YOU ALREADY HAVE THIS SONG?';

		}

		else if($_GET['graphId']==4)

		{

		 	$where = "tracks_reviews.track = '". $_GET['tid'] ."' and tracks_reviews.willplay = '". $_GET['val'] ."'";

		    $pageTitle[4] = 'Members list who choose '.$_GET['label'].' for - WILL YOU PLAY THIS SONG?';

		}

		else if($_GET['graphId']==5)

		{

		 	$where = "tracks_reviews.track = '". $_GET['tid'] ."' and tracks_reviews.howsoon = '". $_GET['val'] ."'";

		    $pageTitle[5] = 'Members list who choose '.$_GET['label'].' for - WILL YOU PLAY THIS SONG?';

		}

		else if($_GET['graphId']==6)

		{

		 	$where = "tracks_reviews.track = '". $_GET['tid'] ."' and tracks_reviews.howmanyplays = '". $_GET['val'] ."'";

		    $pageTitle[6] = 'Members list who choose '.$_GET['label'].' for - HOW MANY TIMES WILL YOU PLAY THIS SONG (per week)?';

		}

		else if($_GET['graphId']==7)

		{

		 	$where = "tracks_reviews.track = '". $_GET['tid'] ."' and tracks_reviews.".$_GET['val']." = '1'";

		    $pageTitle[7] = 'Members list who choose '.$_GET['label'].' for - WHAT FORMATS DO YOU THINK WILL HELP BREAK THIS SONG IN YOUR MARKET(check all that apply)?';

		}

		else if($_GET['graphId']==8)

		{

		 	$where = "tracks_reviews.track = '". $_GET['tid'] ."' and tracks_reviews.godistance = '". $_GET['val'] ."'";

		    $pageTitle[8] = 'Members list who choose '.$_GET['label'].' for - DO YOU THINK THIS RECORD WILL GET ANY SUPPORT?';

		}

		

		else if($_GET['graphId']==9)

		{

		 	$where = "tracks_reviews.track = '". $_GET['tid'] ."' and tracks_reviews.labelsupport = '". $_GET['val'] ."'";

		    $pageTitle[9] = 'Members list who choose '.$_GET['label'].' for - HOW SHOULD THE LABEL SUPPORT THIS PROJECT?';

		}

		

		else if($_GET['graphId']==10)

		{

		 	$where = "tracks_reviews.track = '". $_GET['tid'] ."' and tracks_reviews.howsupport = '". $_GET['val'] ."'";

		    $pageTitle[10] = 'Members list who choose '.$_GET['label'].' for - HOW WILL YOU SUPPORT THIS PROJECT?';

		}

		else if($_GET['graphId']==11)

		{

		 	$where = "tracks_reviews.track = '". $_GET['tid'] ."' and tracks_reviews.likerecord = '". $_GET['val'] ."'";

		    $pageTitle[11] = 'Members list who choose '.$_GET['label'].' for - WHAT DO YOU LIKE MOST ABOUT THIS RECORD?';

		}

		else if($_GET['graphId']==12)

		{

		 	$where = "tracks_reviews.track = '". $_GET['tid'] ."' and tracks_reviews.anotherformat = '". $_GET['val'] ."'";

		    $pageTitle[12] = 'Members list who choose '.$_GET['label'].' for - DO YOU WANT THIS SONG IN ANOTHER FORMAT?';

		}
	

		$output['members'] = $this->clientAllDB_model->getReviewMembers($where); 

		$output['pageTitle'] = $pageTitle[$_GET['graphId']];


		 $output['tid'] = $_GET['tid'];

		 $output['graphId'] = $_GET['graphId'];

		 $output['val'] = $_GET['val'];

		 $output['label'] = $_GET['label'];

		 $output['rightTracks'] = $this->clientAllDB_model->getRightTracks_cld(); 
	   
		 foreach($output['rightTracks']['data'] as $rightTrack)
 
		 {
 
			 $output['trackData'][$rightTrack->id] = $this->clientAllDB_model->getTrackPlays_cld($rightTrack->id); 
 
		 }


		// $this->load->view('header_client.php',$headerOutput);

		// $this->load->view('track_review_members_list',$output);

		// $this->load->view('footer_client.php',$footerOutput);

	//	dd($output);

		return view('clients.dashboard.track_review_members_list', $output);

	}

	public function Client_track_review_member()

	{

		if(empty(Session::get('clientId'))){
			return redirect()->intended('login');
		}
		if(!empty(Session::get('tempClientId'))){
		
			 $output['welcomeMsg'] = 'Thank you for updating your information !';
			 
			 Session::forget('tempClientId');
		}
		$output = array();
		$output['pageTitle'] = 'Digiwax Client track review';
		
		$clientId = Session::get('clientId');
		$output['sessClientID'] = $clientId;
		
		
		date_default_timezone_set('America/Los_Angeles');
		 
		  // logo 
		$output['logo'] = $this->clientAllDB_model->getLogo_cld(); 
		
		$clientdata = $this->clientAllDB_model->getClientsDetails_cld($clientId); 
		
		//echo "<pre>"; print_r($clientdata); die;
		
		$headerOutput['wrapperClass'] = 'client';
		
		//  header data ends here

	

		 // fb logout link

		//   $this->load->library('facebook');

		//   $logout_link = url('Logout');

		  

		//   if(isset($_SESSION['fb_access_token']))

		//    {

		//      $logout_link = $this->facebook->logout_url();	

		//    }

		//   $headerOutput['logout_link'] = $logout_link;

		$output['rightTracks'] = $this->clientAllDB_model->getRightTracks_cld(); 
	   
		foreach($output['rightTracks']['data'] as $rightTrack)

		{

			$output['trackData'][$rightTrack->id] = $this->clientAllDB_model->getTrackPlays_cld($rightTrack->id); 

		}



	

		 // remove comment

		 if(isset($_GET['removeComment']) && isset($_GET['commentId']))

		 {

		 

		    $result = $this->clientAllDB_model->removeTrackComment($_GET['commentId'],$_SESSION['clientId']); 
		

			$arr = array('status' => $result);

			echo json_encode($arr);

			

			exit;		 

		 }

		 

		 // reviwe info

		 if(isset($_GET['popup']) && isset($_GET['reviewId']))

		 {

		$popupOutput['reviews'] = $this->clientAllDB_model->getTrackReviewsByReviewId($_GET['reviewId']);

				

			//$this->load->view('track_review_popup.php',$popupOutput);	

			return view('clients.dashboard.track_review_popup', $popupOutput);
			exit;			

				 

		 }

		 

		 // member info

		 if(isset($_GET['memberId']))

		 {

				$output['members'] = $this->memberAllDB_model->getMemberInfo($_GET['memberId']); 		

				$output['production'] = $this->memberAllDB_model->getMemberProductionInfo($_GET['memberId']); 		

				$output['special'] = $this->memberAllDB_model->getMemberSpecialInfo($_GET['memberId']); 		

				$output['promoter'] = $this->memberAllDB_model->getMemberPromoterInfo($_GET['memberId']); 

				$output['clothing'] = $this->memberAllDB_model->getMemberClothingInfo($_GET['memberId']); 		

				$output['management'] = $this->memberAllDB_model->getMemberManagementInfo($_GET['memberId']); 			

				$output['record'] = $this->memberAllDB_model->getMemberRecordInfo($_GET['memberId']); 			

				$output['media'] = $this->memberAllDB_model->getMemberMediaInfo($_GET['memberId']); 			

				$output['radio'] = $this->memberAllDB_model->getMemberRadioInfo($_GET['memberId']); 			

				$output['socialInfo'] = $this->memberAllDB_model->getMemberSocialInfo($_GET['memberId']); 

				
	 

		 }else{
			 abort(404);
		 }

		   


		//removed subscription option

		// $subscriptionInfo = $this->frontenddb->getSubscriptionStatus($_SESSION['clientId']); 

		// if($subscriptionInfo['numRows']>0)

		// {

		//   $headerOutput['subscriptionStatus'] = 1;

		   

		//   if($subscriptionInfo['data'][0]->packageId==1)

		//   {

		//     $headerOutput['packageId'] = 1;

		// 	$headerOutput['package'] = 'BASIC SUBSCRIPTION';

		// 	$headerOutput['displayDashboard'] = 0;

			

		// 	 $output['displayReviews'] = 0;

		//   }

		//   else if($subscriptionInfo['data'][0]->packageId==2)

		//   {

		//     $headerOutput['packageId'] = 2;

		// 	$headerOutput['package'] = 'ADVANCED SUBSCRIPTION';

		// 	$headerOutput['displayDashboard'] = 1;

			

		// 	$output['displayReviews'] = 1;

		//   }

		// }

		// else

		// {

		//    $headerOutput['packageId'] = 0;

		//    $headerOutput['subscriptionStatus'] = 0;

		//    $headerOutput['package'] = '';

		//    $headerOutput['displayDashboard'] = 0;

			 

		//    $output['displayReviews'] = 0;

		

        $headerOutput['packageId'] = 2;
        $headerOutput['displayDashboard'] = 1;
        $output['displayReviews'] = 1;
		



		return view('clients.dashboard.Client_track_review_member', $output);

	}

	public function Client_track_download()
	{


		$output = array();
	//	$output['pageTitle'] = 'Digiwax Client track review';
		
		$clientId = Session::get('clientId');
		$output['sessClientID'] = $clientId;
		
		
		date_default_timezone_set('America/Los_Angeles');
		 
		  // logo 
		$output['logo'] = $this->clientAllDB_model->getLogo_cld(); 
		
		$clientdata = $this->clientAllDB_model->getClientsDetails_cld($clientId); 
		
		//echo "<pre>"; print_r($clientdata); die;
		
		$headerOutput['wrapperClass'] = 'client';
		
		//  header data ends here

		// fb logout link
		//   $this->load->library('facebook');
		//   $logout_link = url('Logout');

		//   if(isset($_SESSION['fb_access_token']))
		//    {
		//      $logout_link = $this->facebook->logout_url();	
		//    }

		//   $headerOutput['logout_link'] = $logout_link;

		

		$track = $this->clientAllDB_model->getTrack($_GET['tId']); 

		$file_path = url('Client_track_data?tId='.$_GET['tId'].'&clientId='.$clientId);
		

       // $data = file_get_contents(url('Client_track_data?tId='.$_GET['tId'].'&clientId='.$clientId));

	   	$c = curl_init();
			curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($c, CURLOPT_URL, $file_path);
			$data = curl_exec($c);
			curl_close($c);

	//	dd($data);

		$filepath_pass= base_path(). "/Client_track_download_file/Client_track_download.txt";

		$write_in_file = fopen($filepath_pass,"w");
		fwrite($write_in_file,$data);
		fclose($write_in_file);
        $name = ucfirst(urldecode($track['data'][0]->title).'.html');
        
		  // force download after write in file
		 
		  // dd($filepath_pass);
		   return response()->download($filepath_pass, $name);

    }

	public function Client_track_data()

	{

		
		$output = array();
		
		 
		  // logo 
		$output['logo'] = $this->clientAllDB_model->getLogo_cld(); 
	
		
		//  header data ends here


        $output['reviews'] = $this->clientAllDB_model->getTrackReviews($_GET['tId']); 

		

		// comments

		$limit = 60;

		$start = 0; 

		

      $num_records = $this->clientAllDB_model->getNumTrackComments($_GET['tId']); 

	  $numPages = (int)($num_records/$limit); 

	  $reminder = ($num_records%$limit);

	 

	 if($reminder>0)

	 {

		 $numPages = $numPages+1;

	 }

	 $output['comments'] = $this->clientAllDB_model->getTrackComments($_GET['tId'],$start,$num_records); 



		   // removing client Subscription

		// $subscriptionInfo = $this->clientAllDB_model->getSubscriptionStatus($_GET['clientId']); 

		// if($subscriptionInfo['numRows']>0)

		// {

		//   $headerOutput['subscriptionStatus'] = 1;

		   

		//   if($subscriptionInfo['data'][0]->packageId==1)

		//   {

		//     $headerOutput['packageId'] = 1;

		// 	$headerOutput['package'] = 'BASIC SUBSCRIPTION';

		// 	$headerOutput['displayDashboard'] = 0;

			

		// 	 $output['displayReviews'] = 0;

		//   }

		//   else if($subscriptionInfo['data'][0]->packageId==2)

		//   {

		//     $headerOutput['packageId'] = 2;

		// 	$headerOutput['package'] = 'ADVANCED SUBSCRIPTION';

		// 	$headerOutput['displayDashboard'] = 1;

			

		// 	$output['displayReviews'] = 1;

		//   }

		// }

		// else

		// {

		//    $headerOutput['packageId'] = 0;

		//    $headerOutput['subscriptionStatus'] = 0;

		//    $headerOutput['package'] = '';

		//    $headerOutput['displayDashboard'] = 0;

			 

		//    $output['displayReviews'] = 0;

		// }
        

        $headerOutput['packageId'] = 2;
        $headerOutput['displayDashboard'] = 1;
		 

	

        $output['displayReviews'] = 1;

        $output['tId'] = $_GET['tId'];

		

		$output['track'] = $this->clientAllDB_model->getTrack($_GET['tId']); 

		$output['rightTracks'] = $this->clientAllDB_model->getRightTracks_cld(); 
	   
		foreach($output['rightTracks']['data'] as $rightTrack)

		{

			$output['trackData'][$rightTrack->id] = $this->clientAllDB_model->getTrackPlays_cld($rightTrack->id); 

		}



		//$this->load->view('client_track_data.php',$output);
		//dd($output);

		return view('clients.dashboard.client_track_data', $output);

	

	}

	public function Client_track_feedback_video()

	{

		if(empty(Session::get('clientId'))){
			return redirect()->intended('login');
		}
		if(!empty(Session::get('tempClientId'))){

			 $output['welcomeMsg'] = 'Thank you for updating your information !';
			 
			 Session::forget('tempClientId');
		}
		$output = array();
        $output['pageTitle'] = 'Digiwax Client Submit Track';
        $output['packageId'] = 2;
		
        $output['displayDashboard'] = 1;
		
		$clientId = Session::get('clientId');
		$output['sessClientID'] = $clientId;


		date_default_timezone_set('America/Los_Angeles');
		 
		  // logo 
		$headerOutput['logo'] = $this->clientAllDB_model->getLogo_cld(); 

		$clientdata = $this->clientAllDB_model->getClientsDetails_cld($clientId); 
		
		//echo "<pre>"; print_r($clientdata); die;

		$headerOutput['wrapperClass'] = 'client';

		//  header data ends here
		

		// fb logout link

		//   $this->load->library('facebook');

		//   $logout_link = url('Logout');

		  

		//   if(isset($_SESSION['fb_access_token']))

		//    {

		//      $logout_link = $this->facebook->logout_url();	

		//    }

		//   $headerOutput['logout_link'] = $logout_link;

		 
		 // submit tip

		 if( isset($_GET['tId']) && isset($_GET['tip']) && isset($_GET['video_id']) && isset($_GET['submit_tip']) )

		 {

		 

		  $result = $this->clientAllDB_model->addVideoTipToMember($_GET['tId'],$_GET['tip'],$_GET['video_id']);  

		  

		  if($result>0)

	     {

			$arr = array('response' => 1, 'message' => 'Tip sent successfully !');

			echo json_encode($arr);

		 }

		 else

		 {

			$arr = array('response' => 0, 'message' => 'Error occured, please try again!');

			echo json_encode($arr);

		 }

		 exit;

		 }
			// removing client Subscription

		// $subscriptionInfo = $this->clientAllDB_model->getSubscriptionStatus($_SESSION['clientId']); 

		

		// //print_r($subscriptionInfo);

		// //print_r($_SESSION);

		

		

		// if($subscriptionInfo['numRows']>0)

		// {

		//   $headerOutput['subscriptionStatus'] = 1;

		   

		//   if($subscriptionInfo['data'][0]->packageId==1)

		//   {

		//     $headerOutput['packageId'] = 1;

		//     $headerOutput['package'] = 'BASIC SUBSCRIPTION';

		// 	$headerOutput['displayDashboard'] = 0;

			

			

		//   }

		//   else if($subscriptionInfo['data'][0]->packageId==2)

		//   {

		//     $headerOutput['packageId'] = 2;

		//     $headerOutput['package'] = 'ADVANCED SUBSCRIPTION';

		// 	$headerOutput['displayDashboard'] = 1;

			

			

		//   }

		// }

		// else

		// {

		//    $headerOutput['packageId'] = 0;

		//    $headerOutput['subscriptionStatus'] = 0;

		//    $headerOutput['package'] = '';

		//    $headerOutput['displayDashboard'] = 0;

			 

		  

		// }

		

        $headerOutput['displayDashboard'] = 1;


        // generate where

		/*$where = "where client =  '".$_SESSION['clientId']."' and deleted = '0' and approved = '1'";

		$where1 = "where client =  '".$_SESSION['clientId']."' and deleted = '0' and approved = '0'";

		*/

		

		$where = "where track_video_reviews.track_id = '". $_GET['tId'] ."' and tracks.client =  '".$clientId."'";

		

		

		// generate sort

		

		

		$sortOrder = "DESC";

		$sortBy = "track_video_reviews.video_review_id";

		$output['sortBy'] = 'artist';

		

		if(isset($_GET['sortBy']) && isset($_GET['sortOrder']))

		{

		   $output['sortBy'] = $_GET['sortBy'];

		   $output['sortOrder'] = $_GET['sortOrder'];

		   

		   

		   if(strcmp($_GET['sortBy'],'artist')==0)

		   {

		    

			 $sortBy = "artist";

		   }

		   else if(strcmp($_GET['sortBy'],'title')==0)

		   {

		    

			 $sortBy = "title";

		   }

		    else if(strcmp($_GET['sortBy'],'date')==0)

		   {

		    

			 $sortBy = "added";

		   }

		   

		   

		   if($_GET['sortOrder']==1)

		   {

		    

			 $sortOrder = "ASC";

		   }

		   else  if($_GET['sortOrder']==2)

		   {

		    

			 $sortOrder = "DESC";

		   }

		  

		  

		  

		  

		}

		$sort =  $sortBy." ".$sortOrder;

		

		

		

		// pagination

		$limit = 10;

		if(isset($_GET['records']))

		{

		  $limit = $_GET['records'];

		}

		$output['numRecords'] = $limit;

   		

		$start = 0; 

		$currentPageNo = 1;

		

		if(isset($_GET['page']) && $_GET['page']>1)

		 {

			$start = ($_GET['page']*$limit)-$limit;  

		 }

		 

		

		 if(isset($_GET['page']))

		 {

			$currentPageNo = $_GET['page']; 

		 }

		 

		 

		

		 

      $num_records = $this->clientAllDB_model->getNumTrackFeedbackVideos($where,$sort); 

	  $numPages = (int)($num_records/$limit); 

	  $reminder = ($num_records%$limit);

	 

	 if($reminder>0)

	 {

		 $numPages = $numPages+1;

	 }

	

	 $output['numPages'] = $numPages;

	 $output['start'] = $start;

	 $output['currentPageNo'] = $currentPageNo;

	 $output['currentPage'] = 'Client_tracks';

	 

	 

	  if(isset($_GET['page'])) {

     if ($_GET['page'] > $numPages) {

         header("location: ".$output['currentPage']."?page=" . $numPages);

         exit;

     } else if ($_GET['page'] < 1) {

         header("location: ".$output['currentPage']."?page=1");

         exit;

     }

 }

 

 

 

  if($currentPageNo==1)

  {

	  $output['firstPageLink'] = 'disabled';

	  $output['preLink'] = 'disabled';  

	  $output['nextLink'] = '';

	  $output['lastPageLink'] = '';

	  

	  

  }

  else if($currentPageNo==$numPages)

  {

	  $output['firstPageLink'] = '';

	  $output['preLink'] = '';

	  $output['nextLink'] = 'disabled';

	  $output['lastPageLink'] = 'disabled';

	  

	  

  }

  else

  {

      $output['firstPageLink'] = '';

	  $output['preLink'] = '';

	  $output['nextLink'] = '';

	  $output['lastPageLink'] = '';

  }

  // pagination

		

		$output['videos'] = $this->clientAllDB_model->getTrackFeedbackVideos($where,$sort,$start,$limit); 

		foreach($output['videos']['data'] as $video)

		{

		  if($track->approved==1)

		  {

			$output['trackData'][$video->track_id] = $this->clientAllDB_model->getTrackPlays($video->track_id); 

		  }

		  

		 // tips

		 $output['tipData'][$video->video_review_id] = $this->clientAllDB_model->getVideoTips($video->video_review_id);  

		  

		}

		

		

		$output['rightTracks'] = $this->clientAllDB_model->getRightTracks_cld(); 

		

		foreach($output['rightTracks']['data'] as $rightTrack)

		{

			$output['trackData'][$rightTrack->id] = $this->clientAllDB_model->getTrackPlays($rightTrack->id); 

		}

		

		

		$footerOutput['tracks'] = $this->clientAllDB_model->getClientFooterTracks($clientId); 

		

       $unReadMessages = $this->clientAllDB_model->getClientUnreadInbox($clientId); 

		 $output['numMessages']  = $unReadMessages['numRows'];

		 $headerOutput['numMessages']  = $unReadMessages['numRows'];

		 

		 

		  if(isset($_GET['trackAdded']))				

			{

			  

			   $output['alert_class'] = 'success-msg';

			   $output['alert_message'] = 'Thank you for submitting. Your track is now being processed and reviewed by our Admin Team';

			

			}

			else if(isset($_GET['error']))				

			{

			   $output['alert_class'] = 'error-msg';

			   $output['alert_message'] = 'Error occured, please try again.';

			}

		

		// $this->load->view('header_client.php',$headerOutput);

		// $this->load->view('client_track_feedback_video.php',$output);

		// $this->load->view('footer_client.php',$footerOutput);

		//dd($output);
			$output['track'] = $this->clientAllDB_model->getTrack($_GET['tId']);
		$output['tracks']=$output['track'];

		return view('clients.dashboard.client_track_feedback_video', $output);

	}

	public function Client_edit_track(Request $request)

	{

		if(empty(Session::get('clientId'))){
			return redirect()->intended('login');
		}
		if(!empty(Session::get('tempClientId'))){

			 $output['welcomeMsg'] = 'Thank you for updating your information !';
			 
			 Session::forget('tempClientId');
		}
		$output = array();
        $output['pageTitle'] = 'Digiwax Client Edit Track';

        $output['displayDashboard'] = 1;

		$output['packageId'] = 2;
		
		$clientId = Session::get('clientId');
		$output['sessClientID'] = $clientId;

		date_default_timezone_set('America/Los_Angeles');
		 
		  // logo 
		$headerOutput['logo'] = $this->clientAllDB_model->getLogo_cld(); 

		$clientdata = $this->clientAllDB_model->getClientsDetails_cld($clientId); 
		
		//echo "<pre>"; print_r($clientdata); die;

		$headerOutput['wrapperClass'] = 'client';

		//  header data ends here


	//	 error_reporting(E_ALL);

		 

		 // fb logout link

		//   $this->load->library('facebook');

		//   $logout_link = url('Logout');

		  

		//   if(isset($_SESSION['fb_access_token']))

		//    {

		//      $logout_link = $this->facebook->logout_url();	

		//    }

		//   $headerOutput['logout_link'] = $logout_link;
 

		// phpinfo();

		 





        // removed subscription option
	// $subscriptionInfo = $this->frontenddb->getSubscriptionStatus($_SESSION['clientId']); 

	// 	if($subscriptionInfo['numRows']>0)

	// 	{

	// 	  $headerOutput['subscriptionStatus'] = 1;

		   

	// 	  if($subscriptionInfo['data'][0]->packageId==1)

	// 	  {

	// 	    $headerOutput['packageId'] = 1;

	// 		$headerOutput['package'] = 'BASIC';

	// 		$headerOutput['displayDashboard'] = 0;

			

			

	// 	  }

	// 	  else if($subscriptionInfo['data'][0]->packageId==2)

	// 	  {

	// 	    $headerOutput['packageId'] = 2;

	// 		$headerOutput['package'] = 'ADVANCED';

	// 		$headerOutput['displayDashboard'] = 1;

			

			

	// 	  }

	// 	}

	// 	else

	// 	{

	// 	   $headerOutput['packageId'] = 0;

	// 	   $headerOutput['subscriptionStatus'] = 0;

	// 	   $headerOutput['package'] = '';

	// 	   $headerOutput['displayDashboard'] = 0;

			 

		  

	// 	}
    	$pcloud_image_id='';



		 if(isset($_POST['updateTrack']) && isset($_GET['tId']))

		 {

		 

		 $result = $this->clientAllDB_model->updateClientTrack($_POST,$clientId,$_GET['tId']); 

		 

		 

		  // art work image upload

		 if(isset($_FILES['artWork']['name']) && strlen($_FILES['artWork']['name'])>4)

		 {
		             $query=DB::table('tracks')->select('pCloudFileID')->where('id',$_GET['tId'])->get();
            
                       if(!empty($query[0]->pCloudFileID)){
                            $pcloud_image_id=(int)$query[0]->pCloudFileID;
                            
                            
                            if(!empty($pcloud_image_id)){
                               $delete_metadata = $this->delete_pcloud($pcloud_image_id);   
                            }
                       }

					 $date_time_array = getdate(time());

					$artWorkImageName =	$date_time_array["year"].$date_time_array["mon"].$date_time_array["mday"].$date_time_array["hours"].$date_time_array["minutes"].$date_time_array["seconds"];

					$artWork = $request->file('artWork') ;
					
					//Display File Name
					$filename = $artWork->getClientOriginalName();
					// echo 'File Name: '.$filename;
				
					
					//Display File Extension
					$file_extension = $artWork->getClientOriginalExtension();
					// echo 'File Extension: '.$file_extension;
				
					
					//Display File Real Path
					$real_path = $artWork->getRealPath();
					// echo 'File Real Path: '.$real_path;
				
					
					//Display File Size
					$file_size = $artWork->getSize();
					// echo 'File Size: '.$file_size;

					// image validation
					// if(($file_size<300)) 
					// {
					// header("location: ".url("client_submit_track?invalidSize=1".$getString));   exit;
					// }
				
					
					//Display File Mime Type
					$file_mime_type = $artWork->getMimeType();
					// echo 'File Mime Type: '.$file_mime_type;
				
					$destination_path = base_path('ImagesUp/');
				// die($destination_path);

					//Display Destination Path
					if(empty($destination_path)){
					$destination_path = public_path('uploads/');
					} else {
					$destination_path = $destination_path;
					}

					// // echo 'File Destination Path: '.$destination_path;
					// if(!File::isDirectory($destination_path)) {
					//     File::makeDirectory($destination_path, 0777, true, true);
					// }

					$image_name = $clientId . '_' . $artWorkImageName .'.'. $file_extension;
					// $image_name = preg_replace('/[^a-zA-Z0-9_.]/', '_', $image_name);

					$uploaded_data = $artWork->move( $destination_path , $image_name );
					 $folder= '13187487324';
                     $metadata = $this->uploadImage_to_pcloud($destination_path, $image_name, $folder);
            
                	$pcloudFileId = $metadata->metadata->fileid;
                	$parentfolderid = $metadata->metadata->parentfolderid;
                	@unlink($destination_path.$image_name);
					

					if( !empty( $uploaded_data )){
					// die('file');

						$this->clientAllDB_model->updateClientTrackArtWork($_GET['tId'],$image_name,$clientId,$pcloudFileId,$parentfolderid); 	


					}

					else{

					//header("location: " . url("admin/album/edit/?aid=" . $_GET['aid'] . "&error=1"));
					}




		        // $config['upload_path']          = './ImagesUp/';

                // $config['allowed_types']        = 'gif|jpg|png';

                // $config['max_size']             = 100;

                // $config['max_width']            = 1024;

                // $config['max_height']           = 768;

				// $config['file_name']           = $clientId.'_'.$artWorkImageName;

				

				//  $ext = explode('.',$_FILES['artWork']['name']);

				//  $count = count($ext);

				//  $ext = $ext[$count-1];



              //  $this->load->library('upload', $config);

			//   $this->upload->initialize($config);



            //     if ( ! $this->upload->do_upload('artWork'))

            //     {

            //             $error = array('error' => $this->upload->display_errors());



            //         //    $this->load->view('upload_form', $error);

            //     }

            //     else

            //     {

            //       $data = array('upload_data' => $this->upload->data());

            //       $this->clientAllDB_model->updateClientTrackArtWork($_GET['tId'],$config['file_name'].'.'.$ext,$clientId); 	



            //           //  $this->load->view('Client_submit_track', $data);

            //     }



				}

		

	/*

	 $countVersion =  $_POST['numVersion'];

		

		 // amr audio files upload

		for($i=1;$i<=$countVersion;$i++)

		{

		

		 if(isset($_FILES['amr'.$i])) 

		 {


		 $date_time_array = getdate(time());

			$amrfile1 =	$date_time_array["year"].$date_time_array["mon"].$date_time_array["mday"].$date_time_array["hours"].$date_time_array["minutes"].$date_time_array["seconds"];


		        $config['upload_path']          = './amr/';

                $config['allowed_types']        = 'gif|jpg|png|mp3';

                $config['max_size']             = 9999999999999;

                $config['max_width']            = 1024;

                $config['max_height']           = 768;

				$config['file_name']           = $clientId.'_'.$i.'_'.$amrfile1;

				

				 $ext = explode('.',$_FILES['amr'.$i]['name']);

				 $count = count($ext);

				 $ext = $ext[$count-1];



               // $this->load->library('upload', $config);

				

				$this->upload->initialize($config);



                if ( ! $this->upload->do_upload('amr'.$i))

                {

                        $error = array('error' => $this->upload->display_errors());

					//    $this->load->view('upload_form', $error);

                }

                else

                {

				

				  $version = $_POST['version'.$i];

                  $data = array('upload_data' => $this->upload->data());

				  $functionName = "addClientTrackAmr".$i;

                  $this->clientAllDB_model->$functionName($result,$config['file_name'].'.'.$ext,$clientId,$version); 	



                      //  $this->load->view('Client_submit_track', $data);

                }

				

			

	

		  }

		  }

		  */
   

		   if($result>0)

		   {

		  // header("location: ".url("client_edit_track?tId=".$_GET['tId']."&updated=1"));   exit;
		   return Redirect::to("Client_edit_track?tId=".$_GET['tId']."&updated=1");   exit;	

		     

		   }

		   else

		   {

		    //header("location: ".url("client_edit_track?tId=".$_GET['tId']."&error=1"));   exit;
			return Redirect::to("Client_edit_track?tId=".$_GET['tId']."&error=1");   exit;

		   }

		   

				

		 }		

		 

		 

		 

		 if(isset($_GET['updated']))				

			{

			  

			   $output['alert_class'] = 'success-msg';

			   $output['alert_message'] = 'Track updated  successfully !';

			

			}

			else if(isset($_GET['error']))				

			{

			   $output['alert_class'] = 'error-msg';

			   $output['alert_message'] = 'Error occured, please try again.';

			}

		

		$output['track'] = $this->clientAllDB_model->getTrack($_GET['tId']);
		$output['tracks']=$output['track'];

		$footerOutput['tracks'] = $this->clientAllDB_model->getClientFooterTracks($clientId); 

		

		

		$output['rightTracks'] = $this->clientAllDB_model->getRightTracks_cld(); 

		foreach($output['rightTracks']['data'] as $rightTrack)

		{

			$output['trackData'][$rightTrack->id] = $this->clientAllDB_model->getTrackPlays($rightTrack->id); 

		}

		

	

	

	   $unReadMessages = $this->clientAllDB_model->getClientUnreadInbox($clientId); 

		 $output['numMessages']  = $unReadMessages['numRows'];

		 $headerOutput['numMessages']  = $unReadMessages['numRows'];

	

	   $output['genres'] = $this->clientAllDB_model->getGenres_cld(); 
	   
	   if(!empty($output['track']['data'][0]->genreId)){

	   $output['subGenres'] = $this->clientAllDB_model->getSubGenres_cld($output['track']['data'][0]->genreId); 
	   }
	   else{
	       $output['subGenres']='';
	   }

		$headerOutput['tracks'] = $footerOutput['tracks'];
	 

		// $this->load->view('header_client_top.php',$headerOutput);

		// $this->load->view('client_edit_track.php',$output);

		// $this->load->view('footer_client_top.php',$footerOutput);
		
// 		pArr($output);
// 		die();

		return view('clients.dashboard.client_edit_track', $output);

	}


    public function checkTrackExistsclient(){
        $clientID = Session::get('clientId');
	   // return $memID;
    // 	$result = $this->admin_model->checkTrackExists($_POST['myData']);
    	
    	$phpObject = json_decode($_POST['myData'],true);
		$artist = trim($phpObject['songArtist']);
		$title = trim($phpObject['trackTitle']);
		
		$chk_qry = DB::select("SELECT id, title FROM `tracks` WHERE artist = '" . urlencode($artist) . "' AND title = '" . urlencode($title) . "' AND deleted=0");
		$result1['numRows'] = count($chk_qry);
		$result1['data']  = $chk_qry;
        // return json_encode($result);

    	
    	$chk_qry1 = DB::select("SELECT id, title FROM `tracks` WHERE artist = '" . urlencode($artist) . "' AND title = '" . urlencode($title) . "' AND deleted=0 AND client= '".$clientID."'");
        $result1['numRows1'] = count($chk_qry1);
		$result1['data1']  = $chk_qry1;
    	
    	$result = $result1;
    	
    	/* echo'<pre>';print_r($result);die('---GSGS'); */
    	if($result['numRows1'] > 0 ){
    	    $result['status'] = 'exists both';
    		$result = json_encode($result);
    		return $result;
    	}
    	elseif($result['numRows'] > 0) {
    		$result['status'] = 'exists';
    		$result = json_encode($result);
    		return $result;
    	}else{
    		$result['status'] = 'success';
    		$result = json_encode($result);
    		return $result;
    	} 
    }

    public function delete_track_client(){
        
        $trackId = json_decode($_POST['delTrackId'],true);
        $q= DB::select("update tracks set deleted = '1' where id = '" . $trackId . "'");
        
        if($q){
            $result['status'] = 'success';
            return $result;
        }
    }

    public function client_my_package(Request $request){
    	
    	if(empty(Session::get('clientId'))){
			return redirect()->intended('login');
		}else{
		    $clientID = Session::get('clientId');
		}
		if(!empty(Session::get('tempClientId'))){

			 $output['welcomeMsg'] = 'Thank you for updating your information !';
			 
			 Session::forget('tempClientId');
		}
		$output = array();
        $output['pageTitle'] = 'Digiwax Client Subscription Details';

        $output['displayDashboard'] = 1;

		$output['packageId'] = 2;
		
		$clientId = Session::get('clientId');
		$output['sessClientID'] = $clientId;

		date_default_timezone_set('America/Los_Angeles');
		 
		  // logo 
		$headerOutput['logo'] = $this->clientAllDB_model->getLogo_cld(); 

		$clientdata = $this->clientAllDB_model->getClientsDetails_cld($clientId); 

		$headerOutput['wrapperClass'] = 'client';

        // dd($clientID);
		//  header data ends here
		$query = DB::table('package_user_details')->where('user_id', '=', $clientID)->where('user_type','=',2)->get();
	       
	       
	    //if no records found
         $output['package_details'] = $query;
         $count=count($query);
         if($count==0){
             
             $output['no_records_found'] = 1;
             
         }else{
                          
             $package_query = DB::table('manage_packages')->where('id','=',$output['package_details'][0]->package_id)->get();
        	 $output['package_details'][0]->package_name = $package_query[0]->package_name;
        	 $output['package_details'][0]->package_type = $package_query[0]->package_type;
         }
    	 
    
    	 return view('clients.dashboard.my_package_view', $output);
    }
    
    public function client_payment_history(Request $request){
            	if(empty(Session::get('clientId'))){
			return redirect()->intended('login');
		}else{
		    $clientID = Session::get('clientId');
		}
		if(!empty(Session::get('tempClientId'))){

			 $output['welcomeMsg'] = 'Thank you for updating your information !';
			 
			 Session::forget('tempClientId');
		}
		$output = array();
        $output['pageTitle'] = 'Digiwax Client Subscription Details';

        $output['displayDashboard'] = 1;

		$output['packageId'] = 2;
		
		$clientId = Session::get('clientId');
		$output['sessClientID'] = $clientId;

		date_default_timezone_set('America/Los_Angeles');
		 
		  // logo 
		$headerOutput['logo'] = $this->clientAllDB_model->getLogo_cld(); 

		$clientdata = $this->clientAllDB_model->getClientsDetails_cld($clientId); 

		$headerOutput['wrapperClass'] = 'client';
		
		
				 $payment_detail=DB::select("select * from package_user_details left join manage_packages on package_user_details.package_id =  manage_packages.id where  package_user_details.user_id=$clientID AND  package_user_details.user_type=2 AND  package_user_details.payment_status=1 AND package_user_details.payment_amount > 0 ORDER By package_user_details.id DESC");
			if(count($payment_detail)>0){
			    $output['pay_detail']=$payment_detail;
			}
			else{
			      $output['pay_detail']='';
			    
			}
			return view('clients.dashboard.payment_history', $output);
		
		
    }

			// New client controller function R-s ends
}
