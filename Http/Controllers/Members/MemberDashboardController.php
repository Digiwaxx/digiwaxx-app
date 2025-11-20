<?php

namespace App\Http\Controllers\Members;
use App\Models\Frontend\FrontEndUser;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Route;
use Hash;
use Session;
use pCloud;
use File;
use Exception;
//use Mail;

use Srmklive\PayPal\Services\ExpressCheckout;

// for mail sending
use Illuminate\Support\Facades\Mail;
use App\Mail\AdminForgetNotification;

class MemberDashboardController extends Controller
{	
	protected $memberAllDB_model;


	public function __construct()
	{
		// if(empty(Session::get('memberId'))){
		// 	return redirect()->intended('login');
		// }

		$this->memberAllDB_model = new \App\Models\MemberAllDB;

	}

	// public function viewMemberNewestTracks_test(Request $request)
	// {
	// 	if(empty(Session::get('memberId'))){ 
	// 		return redirect()->intended('login');
	// 	}

	// 	// $check = $this->memberAllDB_model->test_mem_fun();
	// 	// print_r($check);die;

	// 	return view('members.dashboard.Member_dashboard_newest_tracks');
	// }
	public function viewMemberInfo(){
		$output = array();
		$logo_data = array(
			'logo_id' => 1,
			);

		$logo_details = DB::table('website_logo')
		->where($logo_data)
		->first();  
		
		$get_logo = $logo_details->logo;	
		$output['pageTitle'] = 'Digiwax Member Info';
		$output['logo'] = $get_logo;
		$output['active'] = 'meminfo';
		if(empty(Session::get('memberId'))){ 
			return redirect()->intended('login');
		}
		$memId = Session::get('memberId');
		$memPakage = Session::get('memberPackage');
		$output['memberInfo'] = $this->memberAllDB_model->getMemberInfo($memId); 
		$output['production'] = $this->memberAllDB_model->getMemberProductionInfo($memId); 
		$output['special'] = $this->memberAllDB_model->getMemberSpecialInfo($memId); 
		$output['promoter'] = $this->memberAllDB_model->getMemberPromoterInfo($memId);
        $output['clothing'] = $this->memberAllDB_model->getMemberClothingInfo($memId);
		$output['management'] = $this->memberAllDB_model->getMemberManagementInfo($memId); 	
		$output['record'] = $this->memberAllDB_model->getMemberRecordInfo($memId); 	
		$output['media'] = $this->memberAllDB_model->getMemberMediaInfo($memId); 	
		$output['radio'] = $this->memberAllDB_model->getMemberRadioInfo($memId); 	
		$output['socialInfo'] = $this->memberAllDB_model->getMemberSocialInfo($memId);
		
		$output['staffTracks'] = $this->memberAllDB_model->getStaffSelectedTracks(0,4);
		if($output['staffTracks']['numRows']>0){
		 foreach($output['staffTracks']['data'] as $track)
 		 {
		   $output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays($track->id); 
		   $row = $this->memberAllDB_model->getClientTrackReview($track->id, $memId); 
		   $output['reviews'][$track->id] = $row['numRows'];
		   $output['mp3s'][$track->id] = $this->memberAllDB_model->getTrackMp3s($track->id);
		}
	   }
	   
	   $output['youTracks'] = $this->memberAllDB_model->getYouSelectedTracks1($memId,0,4);
	   
	   if($output['youTracks']['numRows']>0){ 

			foreach($output['youTracks']['data'] as $track){

			   $output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays($track->id); 

			   $row = $this->memberAllDB_model->getClientTrackReview($track->id, $memId); 

			   $output['reviews'][$track->id] = $row['numRows'];
			   
			   $output['versions'][$track->id] = $this->memberAllDB_model->getTrackMp3s1($track->id);

			}
		}else{ 
			
			$output['youTracks'] = $this->memberAllDB_model->getYouSelectedTracks(0,4); 
			
			if($output['youTracks']['numRows']>0){

				foreach($output['youTracks']['data'] as $track){		

				   $output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays($track->id); 

				   $row = $this->memberAllDB_model->getClientTrackReview($track->id, $memId); 

				   $output['reviews'][$track->id] = $row['numRows'];		   

				   $output['versions'][$track->id] = $this->memberAllDB_model->getTrackMp3s($track->id);
				}
			}
		}
		
		$footerOutput['tracks'] = $this->memberAllDB_model->getMemberFooterTracks($memPakage);
		
		$unReadmessages = $this->memberAllDB_model->getMemberUnreadInbox($memId);
		$output['numMessages'] = $unReadmessages['numRows'];
		
		$headerOutput['numMessages'] = $unReadmessages['numRows'];
		$headerOutput['subscriptionStatus'] = 0;
		$headerOutput['package'] = '';
		
		$subscriptionInfo = $this->memberAllDB_model->getMemberSubscriptionStatus($memId);
		
		if($subscriptionInfo['numRows']>0){

		  $headerOutput['subscriptionStatus'] = 1;

		  //$_SESSION['memberPackage'] = $subscriptionInfo['data'][0]->package_Id;
		  Session::put('memberPackage', $subscriptionInfo['data'][0]->package_Id);
		   

		  if($subscriptionInfo['data'][0]->package_Id==1){

		    $headerOutput['packageId'] = 1;

			$headerOutput['package'] = 'Silver Subscription';

			// $headerOutput['displayDashboard'] = 0;

		  }else if($subscriptionInfo['data'][0]->package_Id==2){

		    $headerOutput['packageId'] = 2;

		    $headerOutput['package'] = 'Gold Subscription';

			//$headerOutput['displayDashboard'] = 1;

		  }else if($subscriptionInfo['data'][0]->package_Id==3){

		    $headerOutput['packageId'] = 3;

		    $headerOutput['package'] = 'Purple Subscription';

			//$headerOutput['displayDashboard'] = 1;
		  }

		}else{
		 Session::put('memberPackage', 1);
		}
		$headerOutput['tracks'] = $footerOutput['tracks'];
			$output['tracks']= $this->memberAllDB_model->getmemtracks($memId);
		
		return view('members.dashboard.MemberInfo', $output);
	}

	
	// announcemnt function start here
	
	public function member_list_announcement(){
	    $output = array();
		$logo_data = array(
			'logo_id' => 1,
			);

		$logo_details = DB::table('website_logo')
		->where($logo_data)
		->first();
	    
	    $get_logo = $logo_details->logo;	
		$output['pageTitle'] = 'Digiwax Member Messages';
		$output['logo'] = $get_logo;
		$output['active'] = 'announcements';
		
		$x= Session::get('memberId');
// 		echo $x;
// 		die();
		if(empty(Session::get('memberId'))){ 
	    	return redirect()->intended('login');
		}

		
		$memId = Session::get('memberId');
		$memPakage = Session::get('memberPackage');
		
		$output['memberInfo'] = $this->memberAllDB_model->getMemberInfo($memId); 
		$output['production'] = $this->memberAllDB_model->getMemberProductionInfo($memId); 
		$output['special'] = $this->memberAllDB_model->getMemberSpecialInfo($memId); 
		$output['promoter'] = $this->memberAllDB_model->getMemberPromoterInfo($memId);
        $output['clothing'] = $this->memberAllDB_model->getMemberClothingInfo($memId);
		$output['management'] = $this->memberAllDB_model->getMemberManagementInfo($memId); 	
		$output['record'] = $this->memberAllDB_model->getMemberRecordInfo($memId); 	
		$output['media'] = $this->memberAllDB_model->getMemberMediaInfo($memId); 	
		$output['radio'] = $this->memberAllDB_model->getMemberRadioInfo($memId); 	
		$output['socialInfo'] = $this->memberAllDB_model->getMemberSocialInfo($memId);
		
		
		$output['staffTracks'] = $this->memberAllDB_model->getStaffSelectedTracks(0,4);
		if($output['staffTracks']['numRows']>0){
		 foreach($output['staffTracks']['data'] as $track)
 		 {
		   $output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays($track->id); 
		   $row = $this->memberAllDB_model->getClientTrackReview($track->id, $memId); 
		   $output['reviews'][$track->id] = $row['numRows'];
		   $output['mp3s'][$track->id] = $this->memberAllDB_model->getTrackMp3s($track->id);
		}
	   }
	   
	   $output['youTracks'] = $this->memberAllDB_model->getYouSelectedTracks1($memId,0,4);
	   
	   if($output['youTracks']['numRows']>0){ 

			foreach($output['youTracks']['data'] as $track){

			   $output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays($track->id); 

			   $row = $this->memberAllDB_model->getClientTrackReview($track->id, $memId); 

			   $output['reviews'][$track->id] = $row['numRows'];
			   
			   $output['versions'][$track->id] = $this->memberAllDB_model->getTrackMp3s1($track->id);

			}
		}else{ 
			
			$output['youTracks'] = $this->memberAllDB_model->getYouSelectedTracks(0,4); 
			
			if($output['youTracks']['numRows']>0){

				foreach($output['youTracks']['data'] as $track){		

				   $output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays($track->id); 

				   $row = $this->memberAllDB_model->getClientTrackReview($track->id, $memId); 

				   $output['reviews'][$track->id] = $row['numRows'];		   

				   $output['versions'][$track->id] = $this->memberAllDB_model->getTrackMp3s($track->id);
				}
			}
		}
		
		$footerOutput['tracks'] = $this->memberAllDB_model->getMemberFooterTracks($memPakage);
		
		$unReadmessages = $this->memberAllDB_model->getMemberUnreadInbox($memId);
		$output['numMessages'] = $unReadmessages['numRows'];
		
		$headerOutput['numMessages'] = $unReadmessages['numRows'];
		$headerOutput['subscriptionStatus'] = 0;
		$headerOutput['package'] = '';
		
		$subscriptionInfo = $this->memberAllDB_model->getMemberSubscriptionStatus($memId);
		
		if($subscriptionInfo['numRows']>0){

		  $headerOutput['subscriptionStatus'] = 1;

		  //$_SESSION['memberPackage'] = $subscriptionInfo['data'][0]->package_Id;
		  Session::put('memberPackage', $subscriptionInfo['data'][0]->package_Id);
		   

		  if($subscriptionInfo['data'][0]->package_Id==1){

		    $headerOutput['packageId'] = 1;

			$headerOutput['package'] = 'Silver Subscription';

			// $headerOutput['displayDashboard'] = 0;

		  }else if($subscriptionInfo['data'][0]->package_Id==2){

		    $headerOutput['packageId'] = 2;

		    $headerOutput['package'] = 'Gold Subscription';

			//$headerOutput['displayDashboard'] = 1;

		  }else if($subscriptionInfo['data'][0]->package_Id==3){

		    $headerOutput['packageId'] = 3;

		    $headerOutput['package'] = 'Purple Subscription';

			//$headerOutput['displayDashboard'] = 1;
		  }

		}else{
		 Session::put('memberPackage', 1);
		}
			$headerOutput['tracks'] = $footerOutput['tracks'];
			
		//pagination
		
		$reminder = 0;
		$numPages = 0;
		$limit=5;
		
		$output['numRecords'] = $limit;

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
		
		$outputMsgTtlCount=DB::table('members_announcements')->where('ma_status','=','1')->count();
	
			
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
			$output['currentPage'] = 'member_list_announcement';
			
			
			
			
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
			
        $list_query=DB::select("SELECT ID,ma_title,ma_description,ma_availability,ma_created_on FROM  members_announcements where(ma_status = '1') order by ID desc limit $start,$limit");
        $avail='';
        $rigt_count=0;
        $new_count=0;
        $my_count=0;
        
        
        
        foreach ($list_query as $key=>$value1){
            if($value1->ma_availability != 'All'){
                
	          $str= $value1->ma_availability;
	          $id_arr['mem_count'][$my_count] = explode(",",$str);
	          
	           foreach ($id_arr['mem_count'][$my_count] as $key=>$value){
	               if(!empty($value)){
	                   
	               if($value==$x){
	                   $new_arr['new_mem'][$new_count]=$value1;
	                   $new_count++;
	               }

    	             
	            }
	           }
	           $my_count++;
            }
            else{
                 $new_arr['new_mem'][$new_count]=$value1;
	             $new_count++;
                
            }
            
        }
        

        
  
		
		$output['list_mem_announ']=$new_arr['new_mem'];
		$output['row_count']=$outputMsgTtlCount;
		$output['tracks']= $this->memberAllDB_model->getmemtracks($memId);
		
		return view('members.dashboard.list_announcement', $output);
	}
	
	
	public function member_single_announcement(Request $request,$id){
	    
	    $output = array();
		$logo_data = array(
			'logo_id' => 1,
			);

		$logo_details = DB::table('website_logo')
		->where($logo_data)
		->first();
	    
	    $get_logo = $logo_details->logo;	
		$output['pageTitle'] = 'Digiwax Member Messages';
		$output['logo'] = $get_logo;
		$output['active'] = 'announcements';
		
		if(empty(Session::get('memberId'))){ 
	    	return redirect()->intended('login');
		}

		
		$memId = Session::get('memberId');
		$memPakage = Session::get('memberPackage');
		
		$output['memberInfo'] = $this->memberAllDB_model->getMemberInfo($memId); 
		$output['production'] = $this->memberAllDB_model->getMemberProductionInfo($memId); 
		$output['special'] = $this->memberAllDB_model->getMemberSpecialInfo($memId); 
		$output['promoter'] = $this->memberAllDB_model->getMemberPromoterInfo($memId);
        $output['clothing'] = $this->memberAllDB_model->getMemberClothingInfo($memId);
		$output['management'] = $this->memberAllDB_model->getMemberManagementInfo($memId); 	
		$output['record'] = $this->memberAllDB_model->getMemberRecordInfo($memId); 	
		$output['media'] = $this->memberAllDB_model->getMemberMediaInfo($memId); 	
		$output['radio'] = $this->memberAllDB_model->getMemberRadioInfo($memId); 	
		$output['socialInfo'] = $this->memberAllDB_model->getMemberSocialInfo($memId);
		
		$output['staffTracks'] = $this->memberAllDB_model->getStaffSelectedTracks(0,4);
		if($output['staffTracks']['numRows']>0){
		 foreach($output['staffTracks']['data'] as $track)
 		 {
		   $output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays($track->id); 
		   $row = $this->memberAllDB_model->getClientTrackReview($track->id, $memId); 
		   $output['reviews'][$track->id] = $row['numRows'];
		   $output['mp3s'][$track->id] = $this->memberAllDB_model->getTrackMp3s($track->id);
		}
	   }
	   
	   $output['youTracks'] = $this->memberAllDB_model->getYouSelectedTracks1($memId,0,4);
	   
	   if($output['youTracks']['numRows']>0){ 

			foreach($output['youTracks']['data'] as $track){

			   $output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays($track->id); 

			   $row = $this->memberAllDB_model->getClientTrackReview($track->id, $memId); 

			   $output['reviews'][$track->id] = $row['numRows'];
			   
			   $output['versions'][$track->id] = $this->memberAllDB_model->getTrackMp3s1($track->id);

			}
		}else{ 
			
			$output['youTracks'] = $this->memberAllDB_model->getYouSelectedTracks(0,4); 
			
			if($output['youTracks']['numRows']>0){

				foreach($output['youTracks']['data'] as $track){		

				   $output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays($track->id); 

				   $row = $this->memberAllDB_model->getClientTrackReview($track->id, $memId); 

				   $output['reviews'][$track->id] = $row['numRows'];		   

				   $output['versions'][$track->id] = $this->memberAllDB_model->getTrackMp3s($track->id);
				}
			}
		}
		
		$footerOutput['tracks'] = $this->memberAllDB_model->getMemberFooterTracks($memPakage);
		
		$unReadmessages = $this->memberAllDB_model->getMemberUnreadInbox($memId);
		$output['numMessages'] = $unReadmessages['numRows'];
		
		$headerOutput['numMessages'] = $unReadmessages['numRows'];
		$headerOutput['subscriptionStatus'] = 0;
		$headerOutput['package'] = '';
		
		$subscriptionInfo = $this->memberAllDB_model->getMemberSubscriptionStatus($memId);
		
		if($subscriptionInfo['numRows']>0){

		  $headerOutput['subscriptionStatus'] = 1;

		  //$_SESSION['memberPackage'] = $subscriptionInfo['data'][0]->package_Id;
		  Session::put('memberPackage', $subscriptionInfo['data'][0]->package_Id);
		   

		  if($subscriptionInfo['data'][0]->package_Id==1){

		    $headerOutput['packageId'] = 1;

			$headerOutput['package'] = 'Silver Subscription';

			// $headerOutput['displayDashboard'] = 0;

		  }else if($subscriptionInfo['data'][0]->package_Id==2){

		    $headerOutput['packageId'] = 2;

		    $headerOutput['package'] = 'Gold Subscription';

			//$headerOutput['displayDashboard'] = 1;

		  }else if($subscriptionInfo['data'][0]->package_Id==3){

		    $headerOutput['packageId'] = 3;

		    $headerOutput['package'] = 'Purple Subscription';

			//$headerOutput['displayDashboard'] = 1;
		  }

		}else{
		 Session::put('memberPackage', 1);
		}
			$headerOutput['tracks'] = $footerOutput['tracks'];
	    
	    $single_announ=DB::table('members_announcements')->where('ID','=',$id)->select('ma_title','ma_description','ma_created_on')->get();
	    
	    $output['single_announ']=$single_announ;
	    $output['tracks']= $this->memberAllDB_model->getmemtracks($memId);
	    
	    return view('members.dashboard.list_single_announcement',$output);
	}
	
	
	public function viewMemberMessages(){
		$output = array();
		$logo_data = array(
			'logo_id' => 1,
			);

		$logo_details = DB::table('website_logo')
		->where($logo_data)
		->first();  
		
		$get_logo = $logo_details->logo;	
		$output['pageTitle'] = 'Digiwax Member Messages';
		$output['logo'] = $get_logo;
		$output['active'] = 'messages';
		if(empty(Session::get('memberId'))){ 
			return redirect()->intended('login');
		}
		
		$memId = Session::get('memberId');
		$memPakage = Session::get('memberPackage');
		
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
			
			$outputMsgTtlCount = $this->memberAllDB_model->getMemberInboxTotalCount($memId);
			
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
			$output['currentPage'] = 'Member_messages';

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
		
		$output['messages'] = $this->memberAllDB_model->getMemberAllInbox($memId, $start, $limit);
		$output['messages']['numRows'] = $outputMsgTtlCount;
		
		if($output['messages']['numRows']>0){		  

		   foreach($output['messages']['data'] as $message){		       

			   if($message->senderType==1){

				$clientId = $message->senderId;

			   }else{

			   $clientId = $message->receiverId;

			   }
			    $result = $this->memberAllDB_model->getClientInfo($clientId);   

				if($result['numRows']>0){
					
					  $output['senderDetails'][$message->messageId]['id']    =  $result['data'][0]->id;

					  $output['senderDetails'][$message->messageId]['uname'] =  $result['data'][0]->uname;

					  $output['senderDetails'][$message->messageId]['name'] =  $result['data'][0]->name;			   

				}

				$result = $this->memberAllDB_model->getClientImage($clientId);   

				if($result['numRows']>0){
                       
                       
				      $output['senderDetails'][$message->messageId]['image']    =  $result['data'][0]->image;

				}
		   }
		 }
		
		$output['staffTracks'] = $this->memberAllDB_model->getStaffSelectedTracks(0,4);
		if($output['staffTracks']['numRows']>0){
		 foreach($output['staffTracks']['data'] as $track)
 		 {
		   $output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays($track->id); 
		   $row = $this->memberAllDB_model->getClientTrackReview($track->id, $memId); 
		   $output['reviews'][$track->id] = $row['numRows'];
		   $output['mp3s'][$track->id] = $this->memberAllDB_model->getTrackMp3s($track->id);
		}
	   }
	   
	   $output['youTracks'] = $this->memberAllDB_model->getYouSelectedTracks1($memId,0,4);
	   
	   if($output['youTracks']['numRows']>0){ 

			foreach($output['youTracks']['data'] as $track){

			   $output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays($track->id); 

			   $row = $this->memberAllDB_model->getClientTrackReview($track->id, $memId); 

			   $output['reviews'][$track->id] = $row['numRows'];
			   
			   $output['versions'][$track->id] = $this->memberAllDB_model->getTrackMp3s1($track->id);

			}
		}else{ 
			
			$output['youTracks'] = $this->memberAllDB_model->getYouSelectedTracks(0,4); 
			
			if($output['youTracks']['numRows']>0){

				foreach($output['youTracks']['data'] as $track){		

				   $output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays($track->id); 

				   $row = $this->memberAllDB_model->getClientTrackReview($track->id, $memId); 

				   $output['reviews'][$track->id] = $row['numRows'];		   

				   $output['versions'][$track->id] = $this->memberAllDB_model->getTrackMp3s($track->id);
				}
			}
		}
		
		$footerOutput['tracks'] = $this->memberAllDB_model->getMemberFooterTracks($memPakage);
		
		$unReadmessages = $this->memberAllDB_model->getMemberUnreadInbox($memId);
		$output['numMessages'] = $unReadmessages['numRows'];
		
		$headerOutput['numMessages'] = $unReadmessages['numRows'];
		
		$starredMessg = $this->memberAllDB_model->getMemberStarredMessages($memId);
		$output['starredMessg'] = $starredMessg['numRows'];
		
		$archMessages = $this->memberAllDB_model->getMemberArchivedMessages($memId);
		$output['archMessages'] = $archMessages['numRows'];
		
		$headerOutput['subscriptionStatus'] = 0;
		$headerOutput['package'] = '';
		
		$subscriptionInfo = $this->memberAllDB_model->getMemberSubscriptionStatus($memId);
		
		if($subscriptionInfo['numRows']>0){

		  $headerOutput['subscriptionStatus'] = 1;

		  //$_SESSION['memberPackage'] = $subscriptionInfo['data'][0]->package_Id;
		  Session::put('memberPackage', $subscriptionInfo['data'][0]->package_Id);
		   

		  if($subscriptionInfo['data'][0]->package_Id==1){

		    $headerOutput['packageId'] = 1;

			$headerOutput['package'] = 'Silver Subscription';

			// $headerOutput['displayDashboard'] = 0;

		  }else if($subscriptionInfo['data'][0]->package_Id==2){

		    $headerOutput['packageId'] = 2;

		    $headerOutput['package'] = 'Gold Subscription';

			//$headerOutput['displayDashboard'] = 1;

		  }else if($subscriptionInfo['data'][0]->package_Id==3){

		    $headerOutput['packageId'] = 3;

		    $headerOutput['package'] = 'Purple Subscription';

			//$headerOutput['displayDashboard'] = 1;
		  }

		}else{
		 Session::put('memberPackage', 1);
		}
		$headerOutput['tracks'] = $footerOutput['tracks'];
		
		$output['tracks']= $this->memberAllDB_model->getmemtracks($memId);
		
// 		pArr($output);
// 		die();
		
		return view('members.dashboard.MemberMessages', $output);
	}

	
	public function viewMemberMessagesUnread(){
		$output = array();
		$logo_data = array(
			'logo_id' => 1,
			);

		$logo_details = DB::table('website_logo')
		->where($logo_data)
		->first();  
		
		$get_logo = $logo_details->logo;	
		$output['pageTitle'] = 'Digiwax Member Messages Unread';
		$output['logo'] = $get_logo;
		$output['active'] = 'messages';
		if(empty(Session::get('memberId'))){ 
			return redirect()->intended('login');
		}
		
		$memId = Session::get('memberId');
		$memPakage = Session::get('memberPackage');
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
			
			$outputMsgTtlCount = $this->memberAllDB_model->getMemberUnreadInboxTotalCount($memId);
			
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
			$output['currentPage'] = 'Member_messages_unread';

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
			
		$output['messages'] = $this->memberAllDB_model->getMemberUnreadInboxAll($memId, $start, $limit);
		
		$output['messages']['numRows'] = $outputMsgTtlCount;
		
		$output['numMessages'] = $output['messages']['numRows'];

	    $headerOutput['numMessages'] = $output['messages']['numRows'];
		
		if($output['messages']['numRows']>0){		  

		   foreach($output['messages']['data'] as $message){		       

			   if($message->senderType==1){

				$clientId = $message->senderId;

			   }else{

			   $clientId = $message->receiverId;

			   }
			    $result = $this->memberAllDB_model->getClientInfo($clientId);   

				if($result['numRows']>0){
					
					  $output['senderDetails'][$message->messageId]['id']    =  $result['data'][0]->id;

					  $output['senderDetails'][$message->messageId]['uname'] =  $result['data'][0]->uname;

					  $output['senderDetails'][$message->messageId]['name'] =  $result['data'][0]->name;			   

				}

				$result = $this->memberAllDB_model->getClientImage($clientId);   

				if($result['numRows']>0){

				      $output['senderDetails'][$message->messageId]['image']    =  $result['data'][0]->image;

				}
		   }
		 }
		
		$output['staffTracks'] = $this->memberAllDB_model->getStaffSelectedTracks(0,4);
		if($output['staffTracks']['numRows']>0){
		 foreach($output['staffTracks']['data'] as $track)
 		 {
		   $output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays($track->id); 
		   $row = $this->memberAllDB_model->getClientTrackReview($track->id, $memId); 
		   $output['reviews'][$track->id] = $row['numRows'];
		   $output['mp3s'][$track->id] = $this->memberAllDB_model->getTrackMp3s($track->id);
		}
	   }
	   
	   $output['youTracks'] = $this->memberAllDB_model->getYouSelectedTracks1($memId,0,4);
	   
	   if($output['youTracks']['numRows']>0){ 

			foreach($output['youTracks']['data'] as $track){

			   $output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays($track->id); 

			   $row = $this->memberAllDB_model->getClientTrackReview($track->id, $memId); 

			   $output['reviews'][$track->id] = $row['numRows'];
			   
			   $output['versions'][$track->id] = $this->memberAllDB_model->getTrackMp3s1($track->id);

			}
		}else{ 
			
			$output['youTracks'] = $this->memberAllDB_model->getYouSelectedTracks(0,4); 
			
			if($output['youTracks']['numRows']>0){

				foreach($output['youTracks']['data'] as $track){		

				   $output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays($track->id); 

				   $row = $this->memberAllDB_model->getClientTrackReview($track->id, $memId); 

				   $output['reviews'][$track->id] = $row['numRows'];		   

				   $output['versions'][$track->id] = $this->memberAllDB_model->getTrackMp3s($track->id);
				}
			}
		}
		
		$footerOutput['tracks'] = $this->memberAllDB_model->getMemberFooterTracks($memPakage);
		
		$unReadmessages = $this->memberAllDB_model->getMemberUnreadInbox($memId);
		$output['numMessages'] = $unReadmessages['numRows'];
		
		$headerOutput['numMessages'] = $unReadmessages['numRows'];
		
		$inboxMessages = $this->memberAllDB_model->getMemberInbox($memId); 
		$output['numInboxMessages'] = $inboxMessages['numRows'];
		
		$starredMessg = $this->memberAllDB_model->getMemberStarredMessages($memId);
		$output['starredMessg'] = $starredMessg['numRows'];
		
		$archMessages = $this->memberAllDB_model->getMemberArchivedMessages($memId);
		$output['archMessages'] = $archMessages['numRows'];
		
		$headerOutput['subscriptionStatus'] = 0;
		$headerOutput['package'] = '';
		
		$subscriptionInfo = $this->memberAllDB_model->getMemberSubscriptionStatus($memId);
		
		if($subscriptionInfo['numRows']>0){

		  $headerOutput['subscriptionStatus'] = 1;

		  //$_SESSION['memberPackage'] = $subscriptionInfo['data'][0]->package_Id;
		  Session::put('memberPackage', $subscriptionInfo['data'][0]->package_Id);
		   

		  if($subscriptionInfo['data'][0]->package_Id==1){

		    $headerOutput['packageId'] = 1;

			$headerOutput['package'] = 'Silver Subscription';

			// $headerOutput['displayDashboard'] = 0;

		  }else if($subscriptionInfo['data'][0]->package_Id==2){

		    $headerOutput['packageId'] = 2;

		    $headerOutput['package'] = 'Gold Subscription';

			//$headerOutput['displayDashboard'] = 1;

		  }else if($subscriptionInfo['data'][0]->package_Id==3){

		    $headerOutput['packageId'] = 3;

		    $headerOutput['package'] = 'Purple Subscription';

			//$headerOutput['displayDashboard'] = 1;
		  }

		}else{
		 Session::put('memberPackage', 1);
		}
		$headerOutput['tracks'] = $footerOutput['tracks'];
		
			$output['tracks']= $this->memberAllDB_model->getmemtracks($memId);
		
		return view('members.dashboard.MemberMessagesUnread', $output);
	}
	
	public function viewMemberMessagesStarred(){
		$output = array();
		$logo_data = array(
			'logo_id' => 1,
			);

		$logo_details = DB::table('website_logo')
		->where($logo_data)
		->first();  
		
		$get_logo = $logo_details->logo;	
		$output['pageTitle'] = 'Digiwax Member Messages Starred';
		$output['logo'] = $get_logo;
		$output['active'] = 'messages';
		if(empty(Session::get('memberId'))){ 
			return redirect()->intended('login');
		}
		
		$memId = Session::get('memberId');
		$memPakage = Session::get('memberPackage');
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
			
			$outputMsgTtlCount = $this->memberAllDB_model->getMemberStarredTotalCount($memId);
			
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
			$output['currentPage'] = 'Member_messages_starred';

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
			
		$output['messages'] = $this->memberAllDB_model->getMemberStarredAllMessages($memId, $start, $limit);
		$output['messages']['numRows'] = $outputMsgTtlCount;
		$output['msgs'] = array();

		$clients = array();	

		$i = 0;

		if($output['messages']['numRows']>0){
			
		   foreach($output['messages']['data'] as $message){

		   if($message->senderType==1){

			$clientId = $message->senderId;

		   }else{

		   $clientId = $message->receiverId;

		   }
		   
		   if(!(in_array($clientId,$clients))){		   

		   $clients[] = $clientId;

		   $output['msgs'][$i]['message'] = $message->message;

		   $output['msgs'][$i]['dateTime'] = $message->dateTime;

		   $output['msgs'][$i]['messageId'] = $message->messageId;
		   $output['msgs'][$i]['userId'] = $message->userId;
		   $output['msgs'][$i]['senderType'] = $message->senderType;
		   $output['msgs'][$i]['senderId'] = $message->senderId;
		   $output['msgs'][$i]['receiverType'] = $message->receiverType;
		   $output['msgs'][$i]['receiverId'] = $message->receiverId;

			       $result = $this->memberAllDB_model->getClientInfo($clientId);   

				   if($result['numRows']>0){				      

					  $output['senderDetails'][$message->messageId]['id']    =  $result['data'][0]->id;

					  $output['senderDetails'][$message->messageId]['uname'] =  $result['data'][0]->uname;

					  $output['senderDetails'][$message->messageId]['name'] =  $result['data'][0]->name;
				   }
				   $result = $this->memberAllDB_model->getClientImage($clientId);   

				   if($result['numRows']>0){

				      $output['senderDetails'][$message->messageId]['image']    =  $result['data'][0]->image;

				   }
				$i++; 
			 }
		   }
		 }
		//pArr($output['senderDetails']);die();
		$output['staffTracks'] = $this->memberAllDB_model->getStaffSelectedTracks(0,4);
		if($output['staffTracks']['numRows']>0){
		 foreach($output['staffTracks']['data'] as $track)
 		 {
		   $output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays($track->id); 
		   $row = $this->memberAllDB_model->getClientTrackReview($track->id, $memId); 
		   $output['reviews'][$track->id] = $row['numRows'];
		   $output['mp3s'][$track->id] = $this->memberAllDB_model->getTrackMp3s($track->id);
		}
	   }
	   
	   $output['youTracks'] = $this->memberAllDB_model->getYouSelectedTracks1($memId,0,4);
	   
	   if($output['youTracks']['numRows']>0){ 

			foreach($output['youTracks']['data'] as $track){

			   $output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays($track->id); 

			   $row = $this->memberAllDB_model->getClientTrackReview($track->id, $memId); 

			   $output['reviews'][$track->id] = $row['numRows'];
			   
			   $output['versions'][$track->id] = $this->memberAllDB_model->getTrackMp3s1($track->id);

			}
		}else{ 
			
			$output['youTracks'] = $this->memberAllDB_model->getYouSelectedTracks(0,4); 
			
			if($output['youTracks']['numRows']>0){

				foreach($output['youTracks']['data'] as $track){		

				   $output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays($track->id); 

				   $row = $this->memberAllDB_model->getClientTrackReview($track->id, $memId); 

				   $output['reviews'][$track->id] = $row['numRows'];		   

				   $output['versions'][$track->id] = $this->memberAllDB_model->getTrackMp3s($track->id);
				}
			}
		}
		
		$footerOutput['tracks'] = $this->memberAllDB_model->getMemberFooterTracks($memPakage);		 

		 $inboxMessages = $this->memberAllDB_model->getMemberInbox($memId); 

		  $output['numInboxMessages'] = $inboxMessages['numRows'];
		
		$unReadmessages = $this->memberAllDB_model->getMemberUnreadInbox($memId);
		$output['numMessages'] = $unReadmessages['numRows'];
		
		$archMessages = $this->memberAllDB_model->getMemberArchivedMessages($memId);
		$output['archMessages'] = $archMessages['numRows'];
		
		$headerOutput['numMessages'] = $unReadmessages['numRows'];
		$headerOutput['subscriptionStatus'] = 0;
		$headerOutput['package'] = '';
		
		$subscriptionInfo = $this->memberAllDB_model->getMemberSubscriptionStatus($memId);
		
		if($subscriptionInfo['numRows']>0){

		  $headerOutput['subscriptionStatus'] = 1;

		  //$_SESSION['memberPackage'] = $subscriptionInfo['data'][0]->package_Id;
		  Session::put('memberPackage', $subscriptionInfo['data'][0]->package_Id);
		   

		  if($subscriptionInfo['data'][0]->package_Id==1){

		    $headerOutput['packageId'] = 1;

			$headerOutput['package'] = 'Silver Subscription';

			// $headerOutput['displayDashboard'] = 0;

		  }else if($subscriptionInfo['data'][0]->package_Id==2){

		    $headerOutput['packageId'] = 2;

		    $headerOutput['package'] = 'Gold Subscription';

			//$headerOutput['displayDashboard'] = 1;

		  }else if($subscriptionInfo['data'][0]->package_Id==3){

		    $headerOutput['packageId'] = 3;

		    $headerOutput['package'] = 'Purple Subscription';

			//$headerOutput['displayDashboard'] = 1;
		  }

		}else{
		 Session::put('memberPackage', 1);
		}
		$headerOutput['tracks'] = $footerOutput['tracks'];
			$output['tracks']= $this->memberAllDB_model->getmemtracks($memId);
		//pArr($output);die();
		return view('members.dashboard.MemberMessagesStarred', $output);
	}	
	
	public function viewMemberMessagesArchived(){
		//set whatever level you want
		error_reporting(0);
	
		$output = array();
		$logo_data = array(
			'logo_id' => 1,
			);

		$logo_details = DB::table('website_logo')
		->where($logo_data)
		->first();  
		
		$get_logo = $logo_details->logo;	
		$output['pageTitle'] = 'Digiwax Member Messages Archived';
		$output['logo'] = $get_logo;
		$output['active'] = 'messages';
		if(empty(Session::get('memberId'))){ 
			return redirect()->intended('login');
		}
		
		$memId = Session::get('memberId');
		$memPakage = Session::get('memberPackage');

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
			
			$outputMsgTtlCount = $this->memberAllDB_model->getMemberArchivedTotalCount($memId);
			
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
			$output['currentPage'] = 'Member_messages_archived';

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
		
		$output['messages'] = $this->memberAllDB_model->getMemberArchivedAllMessages($memId, $start, $limit);
		$output['messages']['numRows'] = $outputMsgTtlCount;
		$output['msgs'] = array();

		$clients = array();	

		$i = 0;

		if($output['messages']['numRows']>0){
			
		   foreach($output['messages']['data'] as $message){

		   if($message->senderType==1){

			$clientId = $message->senderId;

		   }else{

		   $clientId = $message->receiverId;

		   }
		   
		   if(!(in_array($clientId,$clients))){		   

		   $clients[] = $clientId;

		   $output['msgs'][$i]['message'] = $message->message;

		   $output['msgs'][$i]['dateTime'] = $message->dateTime;

		   $output['msgs'][$i]['messageId'] = $message->messageId;

			       $result = $this->memberAllDB_model->getClientInfo($clientId);   

				   if($result['numRows']>0){				      

					  $output['senderDetails'][$message->messageId]['id']    =  $result['data'][0]->id;

					  $output['senderDetails'][$message->messageId]['uname'] =  $result['data'][0]->uname;

					  $output['senderDetails'][$message->messageId]['name'] =  $result['data'][0]->name;
				   }
				   $result = $this->memberAllDB_model->getClientImage($clientId);   

				   if($result['numRows']>0){

				      $output['senderDetails'][$message->messageId]['image']    =  $result['data'][0]->image;

				   }
				$i++; 
			 }
		   }
		 }
		
		$output['staffTracks'] = $this->memberAllDB_model->getStaffSelectedTracks(0,4);
		if($output['staffTracks']['numRows']>0){
		 foreach($output['staffTracks']['data'] as $track)
 		 {
		   $output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays($track->id); 
		   $row = $this->memberAllDB_model->getClientTrackReview($track->id, $memId); 
		   $output['reviews'][$track->id] = $row['numRows'];
		   $output['mp3s'][$track->id] = $this->memberAllDB_model->getTrackMp3s($track->id);
		}
	   }
	   
	   $output['youTracks'] = $this->memberAllDB_model->getYouSelectedTracks1($memId,0,4);
	   
	   if($output['youTracks']['numRows']>0){ 

			foreach($output['youTracks']['data'] as $track){

			   $output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays($track->id); 

			   $row = $this->memberAllDB_model->getClientTrackReview($track->id, $memId); 

			   $output['reviews'][$track->id] = $row['numRows'];
			   
			   $output['versions'][$track->id] = $this->memberAllDB_model->getTrackMp3s1($track->id);

			}
		}else{ 
			
			$output['youTracks'] = $this->memberAllDB_model->getYouSelectedTracks(0,4); 
			
			if($output['youTracks']['numRows']>0){

				foreach($output['youTracks']['data'] as $track){		

				   $output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays($track->id); 

				   $row = $this->memberAllDB_model->getClientTrackReview($track->id, $memId); 

				   $output['reviews'][$track->id] = $row['numRows'];		   

				   $output['versions'][$track->id] = $this->memberAllDB_model->getTrackMp3s($track->id);
				}
			}
		}
		
		$footerOutput['tracks'] = $this->memberAllDB_model->getMemberFooterTracks($memPakage);
		
		$unReadmessages = $this->memberAllDB_model->getMemberUnreadInbox($memId);
		$output['numMessages'] = $unReadmessages['numRows'];
		$headerOutput['numMessages'] = $unReadmessages['numRows'];
		
		$inboxMessages = $this->memberAllDB_model->getMemberInbox($memId); 
		$output['numInboxMessages'] = $inboxMessages['numRows'];
		
		$starredMessg = $this->memberAllDB_model->getMemberStarredMessages($memId);
		$output['starredMessg'] = $starredMessg['numRows'];
		
		
		$headerOutput['subscriptionStatus'] = 0;
		$headerOutput['package'] = '';
		
		$subscriptionInfo = $this->memberAllDB_model->getMemberSubscriptionStatus($memId);
		
		if($subscriptionInfo['numRows']>0){

		  $headerOutput['subscriptionStatus'] = 1;

		  //$_SESSION['memberPackage'] = $subscriptionInfo['data'][0]->package_Id;
		  Session::put('memberPackage', $subscriptionInfo['data'][0]->package_Id);
		   

		  if($subscriptionInfo['data'][0]->package_Id==1){

		    $headerOutput['packageId'] = 1;

			$headerOutput['package'] = 'Silver Subscription';

			// $headerOutput['displayDashboard'] = 0;

		  }else if($subscriptionInfo['data'][0]->package_Id==2){

		    $headerOutput['packageId'] = 2;

		    $headerOutput['package'] = 'Gold Subscription';

			//$headerOutput['displayDashboard'] = 1;

		  }else if($subscriptionInfo['data'][0]->package_Id==3){

		    $headerOutput['packageId'] = 3;

		    $headerOutput['package'] = 'Purple Subscription';

			//$headerOutput['displayDashboard'] = 1;
		  }

		}else{
		 Session::put('memberPackage', 1);
		}
		$headerOutput['tracks'] = $footerOutput['tracks'];
			$output['tracks']= $this->memberAllDB_model->getmemtracks($memId);
		//pArr($output);die();
		return view('members.dashboard.MemberMessagesArchived', $output);
	}	
	public function viewMemberMessageArchived(){
		$output = array();
		$logo_data = array(
			'logo_id' => 1,
			);

		$logo_details = DB::table('website_logo')
		->where($logo_data)
		->first();  
		
		$get_logo = $logo_details->logo;	
		$output['pageTitle'] = 'Digiwax Member Message Archived';
		$output['logo'] = $get_logo;
		if(empty(Session::get('memberId'))){ 
			return redirect()->intended('login');
		}
		
		$memId = Session::get('memberId');
		$memPakage = Session::get('memberPackage');
		
		if(!(isset($_GET['cid'])))

		{

		 return Redirect::to("Member_messages");   exit;

		}

		$output['cid'] = $_GET['cid'];

		$headerOutput['pageTitle'] = 'Digiwax Member Messages Archived';

		  

		

		$clientInfo = $this->memberAllDB_model->getClientInfo($_GET['cid']); 

		if($clientInfo['numRows']<1)

		{

		 return Redirect::to("Member_messages");   exit;

		}

		

		$output['clientName'] = $clientInfo['data'][0]->uname;

		$clientImg = $this->memberAllDB_model->getClientImage($_GET['cid']); 

		

		

		if($clientImg['numRows']>0 && strlen($clientImg['data'][0]->image)>4) 

		{

		  $output['clientImage'] = asset("client_images/".$_GET['cid']."/".$clientImg['data'][0]->image);

		}

		else

		{

		  $output['clientImage'] = asset('public/images/profile-pic.png'); // $clientImg['data'][0]->image;

		}

		

		  

		//  $messageInfo = $this->frontenddb->getMessage($_GET['pid']); 

		  

		 $output['conversation'] = $this->memberAllDB_model->getMemberArchivedConversation($memId,$_GET['cid']); 		
		
		$output['staffTracks'] = $this->memberAllDB_model->getStaffSelectedTracks(0,4);
		if($output['staffTracks']['numRows']>0){
		 foreach($output['staffTracks']['data'] as $track)
 		 {
		   $output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays($track->id); 
		   $row = $this->memberAllDB_model->getClientTrackReview($track->id, $memId); 
		   $output['reviews'][$track->id] = $row['numRows'];
		   $output['mp3s'][$track->id] = $this->memberAllDB_model->getTrackMp3s($track->id);
		}
	   }
	   
	   $output['youTracks'] = $this->memberAllDB_model->getYouSelectedTracks1($memId,0,4);
	   
	   if($output['youTracks']['numRows']>0){ 

			foreach($output['youTracks']['data'] as $track){

			   $output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays($track->id); 

			   $row = $this->memberAllDB_model->getClientTrackReview($track->id, $memId); 

			   $output['reviews'][$track->id] = $row['numRows'];
			   
			   $output['versions'][$track->id] = $this->memberAllDB_model->getTrackMp3s1($track->id);

			}
		}else{ 
			
			$output['youTracks'] = $this->memberAllDB_model->getYouSelectedTracks(0,4); 
			
			if($output['youTracks']['numRows']>0){

				foreach($output['youTracks']['data'] as $track){		

				   $output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays($track->id); 

				   $row = $this->memberAllDB_model->getClientTrackReview($track->id, $memId); 

				   $output['reviews'][$track->id] = $row['numRows'];		   

				   $output['versions'][$track->id] = $this->memberAllDB_model->getTrackMp3s($track->id);
				}
			}
		}
		
		$footerOutput['tracks'] = $this->memberAllDB_model->getMemberFooterTracks($memPakage);
		
		$unReadmessages = $this->memberAllDB_model->getMemberUnreadInbox($memId);
		$output['numMessages'] = $unReadmessages['numRows'];
		
		$headerOutput['numMessages'] = $unReadmessages['numRows'];
		$headerOutput['subscriptionStatus'] = 0;
		$headerOutput['package'] = '';
		
		$subscriptionInfo = $this->memberAllDB_model->getMemberSubscriptionStatus($memId);
		
		if($subscriptionInfo['numRows']>0){

		  $headerOutput['subscriptionStatus'] = 1;

		  //$_SESSION['memberPackage'] = $subscriptionInfo['data'][0]->package_Id;
		  Session::put('memberPackage', $subscriptionInfo['data'][0]->package_Id);
		   

		  if($subscriptionInfo['data'][0]->package_Id==1){

		    $headerOutput['packageId'] = 1;

			$headerOutput['package'] = 'Silver Subscription';

			// $headerOutput['displayDashboard'] = 0;

		  }else if($subscriptionInfo['data'][0]->package_Id==2){

		    $headerOutput['packageId'] = 2;

		    $headerOutput['package'] = 'Gold Subscription';

			//$headerOutput['displayDashboard'] = 1;

		  }else if($subscriptionInfo['data'][0]->package_Id==3){

		    $headerOutput['packageId'] = 3;

		    $headerOutput['package'] = 'Purple Subscription';

			//$headerOutput['displayDashboard'] = 1;
		  }

		}else{
		 Session::put('memberPackage', 1);
		}
		$headerOutput['tracks'] = $footerOutput['tracks'];
			$output['tracks']= $this->memberAllDB_model->getmemtracks($memId);
		
		return view('members.dashboard.MemberMessageArchived', $output);
	}

	
	public function viewMemberMessageStarred(){
		$output = array();
		$logo_data = array(
			'logo_id' => 1,
			);

		$logo_details = DB::table('website_logo')
		->where($logo_data)
		->first();  
		
		$get_logo = $logo_details->logo;	
		$output['pageTitle'] = 'Digiwax Member Message Starred';
		$output['logo'] = $get_logo;
		$output['active'] = 'messages';
		if(empty(Session::get('memberId'))){ 
			return redirect()->intended('login');
		}
		
		$memId = Session::get('memberId');
		$memPakage = Session::get('memberPackage');
		
		if(!(isset($_GET['cid'])))

		{

		 return Redirect::to("Member_messages");   exit;

		}

		$output['cid'] = $_GET['cid'];

		$headerOutput['pageTitle'] = 'Digiwax Member Messages Starred';

		  

		

		$clientInfo = $this->memberAllDB_model->getClientInfo($_GET['cid']); 

		if($clientInfo['numRows']<1)

		{

		 return Redirect::to("Member_messages");   exit;

		}

		

		$output['clientName'] = $clientInfo['data'][0]->uname;

		$clientImg = $this->memberAllDB_model->getClientImage($_GET['cid']); 
         
		

		

	//	if($clientImg['numRows']>0 && strlen($clientImg['data'][0]->image)>4) 

	//	{

		  $output['clientImage'] =  $clientImg['data'][0]->image;

// 		}

// 		else

// 		{

// 		  $output['clientImage'] = asset('public/images/profile-pic.png'); // $clientImg['data'][0]->image;

// 		}

		

		  

		//  $messageInfo = $this->frontenddb->getMessage($_GET['pid']); 

		  

		 $output['conversation'] = $this->memberAllDB_model->getMemberStarredConversation($memId,$_GET['cid']); 		
		
		$output['staffTracks'] = $this->memberAllDB_model->getStaffSelectedTracks(0,4);
		if($output['staffTracks']['numRows']>0){
		 foreach($output['staffTracks']['data'] as $track)
 		 {
		   $output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays($track->id); 
		   $row = $this->memberAllDB_model->getClientTrackReview($track->id, $memId); 
		   $output['reviews'][$track->id] = $row['numRows'];
		   $output['mp3s'][$track->id] = $this->memberAllDB_model->getTrackMp3s($track->id);
		}
	   }
	   
	   $output['youTracks'] = $this->memberAllDB_model->getYouSelectedTracks1($memId,0,4);
	   
	   if($output['youTracks']['numRows']>0){ 

			foreach($output['youTracks']['data'] as $track){

			   $output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays($track->id); 

			   $row = $this->memberAllDB_model->getClientTrackReview($track->id, $memId); 

			   $output['reviews'][$track->id] = $row['numRows'];
			   
			   $output['versions'][$track->id] = $this->memberAllDB_model->getTrackMp3s1($track->id);

			}
		}else{ 
			
			$output['youTracks'] = $this->memberAllDB_model->getYouSelectedTracks(0,4); 
			
			if($output['youTracks']['numRows']>0){

				foreach($output['youTracks']['data'] as $track){		

				   $output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays($track->id); 

				   $row = $this->memberAllDB_model->getClientTrackReview($track->id, $memId); 

				   $output['reviews'][$track->id] = $row['numRows'];		   

				   $output['versions'][$track->id] = $this->memberAllDB_model->getTrackMp3s($track->id);
				}
			}
		}
		
		$footerOutput['tracks'] = $this->memberAllDB_model->getMemberFooterTracks($memPakage);
		
		$unReadmessages = $this->memberAllDB_model->getMemberUnreadInbox($memId);
		$output['numMessages'] = $unReadmessages['numRows'];
		
		$headerOutput['numMessages'] = $unReadmessages['numRows'];
		$headerOutput['subscriptionStatus'] = 0;
		$headerOutput['package'] = '';
		
		$subscriptionInfo = $this->memberAllDB_model->getMemberSubscriptionStatus($memId);
		
		if($subscriptionInfo['numRows']>0){

		  $headerOutput['subscriptionStatus'] = 1;

		  //$_SESSION['memberPackage'] = $subscriptionInfo['data'][0]->package_Id;
		  Session::put('memberPackage', $subscriptionInfo['data'][0]->package_Id);
		   

		  if($subscriptionInfo['data'][0]->package_Id==1){

		    $headerOutput['packageId'] = 1;

			$headerOutput['package'] = 'Silver Subscription';

			// $headerOutput['displayDashboard'] = 0;

		  }else if($subscriptionInfo['data'][0]->package_Id==2){

		    $headerOutput['packageId'] = 2;

		    $headerOutput['package'] = 'Gold Subscription';

			//$headerOutput['displayDashboard'] = 1;

		  }else if($subscriptionInfo['data'][0]->package_Id==3){

		    $headerOutput['packageId'] = 3;

		    $headerOutput['package'] = 'Purple Subscription';

			//$headerOutput['displayDashboard'] = 1;
		  }

		}else{
		 Session::put('memberPackage', 1);
		}
		$headerOutput['tracks'] = $footerOutput['tracks'];
			$output['tracks']= $this->memberAllDB_model->getmemtracks($memId);
		
		return view('members.dashboard.MemberMessageArchived', $output);
	}	
	
	public function viewMemberTracksArchives(){
		$output = array();
		$logo_data = array(
			'logo_id' => 1,
			);

		$logo_details = DB::table('website_logo')
		->where($logo_data)
		->first();  
		
		$get_logo = $logo_details->logo;	
		$output['pageTitle'] = 'Digiwax Member Tracks Archives';
		$output['logo'] = $get_logo;
		$output['active'] = 'archives';
		if(empty(Session::get('memberId'))){ 
			return redirect()->intended('login');
		}
		
		if(!empty(Session::get('tempMemberId'))){

			 $output['welcomeMsg'] = 'Thank you for updating your information !';
			 
			 Session::forget('tempMemberId');
		}
		
		$memId = Session::get('memberId');
		$memPakage = Session::get('memberPackage');
		
		// save play record

		 if(isset($_GET['trackId']) && isset($_GET['recordPlay']) && !empty($memId)){
			 
		  $countryCode = ''; 

		  $countryName = '';		  

		  // get user location details:

		  //	function getLocationInfoByIp(){

		$client  = @$_SERVER['HTTP_CLIENT_IP'];

		$forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];

		$remote  = @$_SERVER['REMOTE_ADDR'];
		
		$result  = array('country'=>'', 'city'=>'');

		if(filter_var($client, FILTER_VALIDATE_IP)){
			$ip = $client;
		}else if(filter_var($forward, FILTER_VALIDATE_IP)){
			$ip = $forward;
		}else{
			$ip = $remote;
		}

		$ip_data = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$ip));  	

		if($ip_data && $ip_data->geoplugin_countryName != null){

			$countryCode = $ip_data->geoplugin_countryCode;
		
			$countryName = $ip_data->geoplugin_countryName;
		}
		   $this->memberAllDB_model->addTrackPlay($_GET['trackId'],$memId,$countryName,$countryCode); 

		   exit;

		 }
		 
		// generate where

		$where = 'where ';

		$whereItems[] = "tracks.deleted = '0'";

		$whereItems[] = "tracks.active = '1'";

		

		$output['searchArtist'] = '';

		$output['searchTitle'] = '';

		$output['searchLabel'] = '';

		$output['searchAlbum'] = '';

		$output['searchProducer'] = '';

		$output['searchClient'] = '';

		$output['searchYear'] = '';

		$output['searchMonth'] = '';
		
		if(isset($_GET['search'])){

		  if(isset($_GET['genre']) && strlen($_GET['genre'])>0){

		     $output['searchGenre'] = $_GET['genre'];

			 $whereItems[] = "tracks.genre = '". $_GET['genre'] ."'";

		   } 

		   if(isset($_GET['bpm']) && strlen($_GET['bpm'])>0){

		     $output['searchBpm'] = $_GET['bpm'];

			 $whereItems[] = "tracks_mp3s.bpm = '". $_GET['bpm'] ."'";

		   }		   

		   if(isset($_GET['version']) && strlen($_GET['version'])>0){

		     $output['searchVersion'] = $_GET['version'];

			 $whereItems[] = "tracks_mp3s.version like '%". $_GET['version'] ."%'";

		   }

		   if(isset($_GET['searchYear']) && strlen($_GET['searchYear'])>0 && isset($_GET['searchMonth']) && strlen($_GET['searchMonth'])>0 && $_GET['searchMonth']>0){

		     $output['searchYear'] = $_GET['searchYear'];

			 $output['searchMonth'] = $_GET['searchMonth'];

			 $searchYear = $_GET['searchYear'];

			 $searchMonth = $_GET['searchMonth'];

			 $whereItems[] = "tracks.added like '".$searchYear."-".$searchMonth."-%'";

		   }else if(isset($_GET['searchYear']) && strlen($_GET['searchYear'])>0){

		     $searchYear = $_GET['searchYear'];

		     $output['searchYear'] = $_GET['searchYear'];

			 $whereItems[] = "tracks.added like '".$searchYear."-%'";

		   }else if(isset($_GET['searchMonth']) && strlen($_GET['searchMonth'])>0 && $_GET['searchMonth']>0){

		     $searchMonth = $_GET['searchMonth'];

		     $output['searchMonth'] = $_GET['searchMonth'];

			 $whereItems[] = "tracks.added like '%-".$searchMonth."-%'";

		   }

		 /* if(isset($_GET['artist']) && strlen($_GET['artist'])>0)

		   {

		     $output['searchArtist'] = $_GET['artist'];

			 $whereItems[] = "tracks.artist = '". urlencode($_GET['artist']) ."'";

		   }

		   

		   if(isset($_GET['title']) && strlen($_GET['title'])>0)

		   {

		     $output['searchTitle'] = $_GET['title'];

			 $whereItems[] = "tracks.title = '". urlencode($_GET['title']) ."'";

		   }

		   

		   if(isset($_GET['label']) && strlen($_GET['label'])>0)

		   {

		     $output['searchLabel'] = $_GET['label'];

			 $whereItems[] = "tracks.label = '". urlencode($_GET['label']) ."'";

		   }

		   

		   if(isset($_GET['album']) && strlen($_GET['album'])>0)

		   {

		     $output['searchAlbum'] = $_GET['album'];

			 $whereItems[] = "tracks.album = '". urlencode($_GET['album']) ."'";

		   }

		   

		   if(isset($_GET['producer']) && strlen($_GET['producer'])>0)

		   {

		     $output['searchProducer'] = $_GET['producer'];

			 $whereItems[] = "tracks.producer = '". $_GET['producer'] ."'";

		   }

		   

		   if(isset($_GET['client']) && strlen($_GET['client'])>0)

		   {

		     $output['searchClient'] = $_GET['client'];

			 $whereItems[] = "tracks.client = '". $_GET['client'] ."'";

		   }*/
		}
		
		if(count($whereItems)>1){		

		   $whereString = implode(' AND ',$whereItems);

		   $where .= $whereString;

		}else if(count($whereItems)==1){

		   $where .= $whereItems[0];

		}else{
		  $where =  '';
		}
		
		// generate sort

		$sortOrder = "ASC";

		$sortBy = "tracks.title";

		$output['sortBy'] = 'song';

		$output['sortOrder'] = 1;

		

		if(isset($_GET['sortBy']) && isset($_GET['sortOrder']))

		{

		   $output['sortBy'] = $_GET['sortBy'];

		   $output['sortOrder'] = $_GET['sortOrder'];

		   

		   

		   if(strcmp($_GET['sortBy'],'artist')==0)

		   {

		    

			 $sortBy = "artist";

		   }

		   else if(strcmp($_GET['sortBy'],'song')==0)

		   {

		    

			 $sortBy = "title";

		   }

		    else if(strcmp($_GET['sortBy'],'label')==0)

		   {

		    

			 $sortBy = "label";

		   }

		   else if(strcmp($_GET['sortBy'],'date')==0)

		   {

		    

			 $sortBy = "tracks.added";

		   }

		   else if(strcmp($_GET['sortBy'],'album')==0)

		   {

		    

			 $sortBy = "album";

		   }

		   else if(strcmp($_GET['sortBy'],'bpm')==0)

		   {

		    

			 $sortBy = "tracks_mp3s.bpm";

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

	

	

	

	if($memPakage<2)

	{

	   $num_records = 20;

	}

	else

	{

	   $num_records = $this->memberAllDB_model->getNumDashboardTracks($where,$sort); 

	}

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

	 $output['currentPage'] = 'Member_tracks_archives'; 

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

		  

		

		$output['tracks'] = $this->memberAllDB_model->getDashboardTracks($where,$sort,$start,$limit,$memId); 

		if($output['tracks']['numRows'] > 0){
		     $arr1=$output['tracks']['data'];
				 $arr=json_decode(json_encode($arr1));
				
				// $arr= json_decode($arr1);
				 
				 foreach ($arr as $key=>$value){

				     $query_loc=$this->memberAllDB_model->getTrackMp3s_fem($value->id);
				    //  pArr($query_loc);
					$xx='';
				     foreach($query_loc['data'] as $key1=>$value1){
				        $xx= $value1->location;
				        
				     }
					if(!empty($xx)){
				     $arr[$key]->location=$xx;
					 }
				   
				 }
				 $output['tracks']['data']=$arr;
		}


        
        foreach($output['tracks']['data'] as $track)
        
        		{
        
        	//	 $output['memberReviews'] = $this->frontenddb->getClientTrackReview($track->id); 
        
        	//	 $output['downloads'][$track->id] = $output['memberReviews']['numRows'];
        
        		 
        
        		 $output['mp3s'][$track->id] = $this->memberAllDB_model->getTrackMp3s($track->id);
        
        		 
        
        		}
		

		  





  // generate sort url sting

  $sortExclude = array("sortOrder","sortBy","page");

  $getSortArray = array(); $urlSortString = '?';

 if(isset($_GET))

 {

    foreach($_GET as $key=>$value)

 	{

       if(!(in_array($key,$sortExclude)))

	   {

	     

		 $getSortArray[] = $key.'='.$value;

	    

	   }

 	}

	

	

	

	if(count($getSortArray)>1)

	{

	  $urlSortString .= implode('&',$getSortArray);

	}

	else if(count($getSortArray)==1)

	{

	  $urlSortString .= $getSortArray[0];

	}

	else

	{

	  $urlSortString = '';

	}

 }

 $output['urlSortString'] = $urlSortString; 	

 

 // generate search url sting

  $searchExclude = array("bpm","version","genre","page","records");

  $getSearchArray = array(); $urlSearchString = '?';

 if(isset($_GET))

 {

    foreach($_GET as $key=>$value)

 	{

	

	  

       if(!(in_array($key,$searchExclude)))

	   {

	     

		 $getSearchArray[] = $key.'='.$value;

	    

	   }

 	}

	

	if(count($getSearchArray)>1)

	{

	  $urlSearchString .= implode('&',$getSearchArray);

	}

	else if(count($getSearchArray)==1)

	{

	  $urlSearchString .= $getSearchArray[0];

	}

	else

	{

	  $urlSearchString = '';

	}

 }		

 $output['urlSearchString'] = $urlSearchString; 

 

 // generate record url sting

  $recordExclude = array("records");

  $getRecordArray = array(); $urlRecordString = '?';

 if(isset($_GET))

 {

    foreach($_GET as $key=>$value)

 	{

	

	  

       if(!(in_array($key,$recordExclude)))

	   {

	     

		 $getRecordArray[] = $key.'='.$value;

	    

	   }

 	}

	

	if(count($getRecordArray)>1)

	{

	  $urlRecordString .= implode('&',$getRecordArray);

	}

	else if(count($getRecordArray)==1)

	{

	  $urlRecordString .= $getRecordArray[0];

	}

	else

	{

	  $urlRecordString = '';

	}

 }


 $output['urlRecordString'] = $urlRecordString; 
		
		$output['staffTracks'] = $this->memberAllDB_model->getStaffSelectedTracks(0,4);
		if($output['staffTracks']['numRows']>0){
		 foreach($output['staffTracks']['data'] as $track)
 		 {
		   $output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays($track->id); 
		   $row = $this->memberAllDB_model->getClientTrackReview($track->id, $memId); 
		   $output['reviews'][$track->id] = $row['numRows'];
		   $output['mp3s'][$track->id] = $this->memberAllDB_model->getTrackMp3s($track->id);
		}
	   }
	   
	   $output['youTracks'] = $this->memberAllDB_model->getYouSelectedTracks1($memId,0,4);
	   
	   if($output['youTracks']['numRows']>0){ 

			foreach($output['youTracks']['data'] as $track){

			   $output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays($track->id); 

			   $row = $this->memberAllDB_model->getClientTrackReview($track->id, $memId); 

			   $output['reviews'][$track->id] = $row['numRows'];
			   
			   $output['versions'][$track->id] = $this->memberAllDB_model->getTrackMp3s1($track->id);

			}
		}else{ 
			
			$output['youTracks'] = $this->memberAllDB_model->getYouSelectedTracks(0,4); 
			
			if($output['youTracks']['numRows']>0){

				foreach($output['youTracks']['data'] as $track){		

				   $output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays($track->id); 

				   $row = $this->memberAllDB_model->getClientTrackReview($track->id, $memId); 

				   $output['reviews'][$track->id] = $row['numRows'];		   

				   $output['versions'][$track->id] = $this->memberAllDB_model->getTrackMp3s($track->id);
				}
			}
		}
		
		$footerOutput['tracks'] = $this->memberAllDB_model->getMemberFooterTracks($memPakage);
		
		$unReadmessages = $this->memberAllDB_model->getMemberUnreadInbox($memId);
		$output['numMessages'] = $unReadmessages['numRows'];
		
		$headerOutput['numMessages'] = $unReadmessages['numRows'];
		$headerOutput['subscriptionStatus'] = 0;
		$headerOutput['package'] = '';
		
		$subscriptionInfo = $this->memberAllDB_model->getMemberSubscriptionStatus($memId);
		
		if($subscriptionInfo['numRows']>0){

		  $headerOutput['subscriptionStatus'] = 1;

		  //$_SESSION['memberPackage'] = $subscriptionInfo['data'][0]->package_Id;
		  Session::put('memberPackage', $subscriptionInfo['data'][0]->package_Id);
		   

		  if($subscriptionInfo['data'][0]->package_Id==1){

		    $headerOutput['packageId'] = 1;

			$headerOutput['package'] = 'Silver Subscription';

			// $headerOutput['displayDashboard'] = 0;

		  }else if($subscriptionInfo['data'][0]->package_Id==2){

		    $headerOutput['packageId'] = 2;

		    $headerOutput['package'] = 'Gold Subscription';

			//$headerOutput['displayDashboard'] = 1;

		  }else if($subscriptionInfo['data'][0]->package_Id==3){

		    $headerOutput['packageId'] = 3;

		    $headerOutput['package'] = 'Purple Subscription';

			//$headerOutput['displayDashboard'] = 1;
		  }

		}else{
		 Session::put('memberPackage', 1);
		}
		$headerOutput['tracks'] = $footerOutput['tracks'];
	
		
		return view('members.dashboard.MemberTracksArchives', $output);
	}
	
		
	public function viewMemberTracksOwnArchives(){
		$output = array();
		$logo_data = array(
			'logo_id' => 1,
			);

		$logo_details = DB::table('website_logo')
		->where($logo_data)
		->first();  
		
		$get_logo = $logo_details->logo;	
		$output['pageTitle'] = 'Digiwax Member Tracks Own Archives';
		$output['logo'] = $get_logo;
		$output['active'] = 'mycrate';
		if(empty(Session::get('memberId'))){ 
			return redirect()->intended('login');
		}
		
		$memId = Session::get('memberId');
		$memPakage = Session::get('memberPackage');
		
				 // save play record
		 if(isset($_GET['trackId']) && isset($_GET['recordPlay']) && isset($_SESSION['memberId']))
		 {
		    $countryCode = '';
		    $countryName = '';


				  // get user location details:
				  // function getLocationInfoByIp(){

			$client  = @$_SERVER['HTTP_CLIENT_IP'];
			$forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
			$remote  = @$_SERVER['REMOTE_ADDR'];

			$result  = array('country'=>'', 'city'=>'');

			if(filter_var($client, FILTER_VALIDATE_IP)){
				$ip = $client;
			}elseif(filter_var($forward, FILTER_VALIDATE_IP)){
				$ip = $forward;
			}else{
				$ip = $remote;
			}

			$ip_data = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$ip));

			if($ip_data && $ip_data->geoplugin_countryName != null){
				$countryCode = $ip_data->geoplugin_countryCode;
				$countryName = $ip_data->geoplugin_countryName;
			}
		// }
				   $this->memberAllDB_model->addTrackPlay($_GET['trackId'],$memId,$countryName,$countryCode);
				   exit;
				 }

				// generate where

				$where = 'where ';
				$whereItems[] = "tracks.deleted = '0'";
				$whereItems[] = "tracks.active = '1'";
				$whereItems[] = "tracks.status = 'publish'";
				if(!empty($memId)){
				$whereItems[] = "tracks_reviews.member =".$memId;
				}

				$output['searchArtist'] = '';
				$output['searchTitle'] = '';
				$output['searchLabel'] = '';
				$output['searchAlbum'] = '';
				$output['searchProducer'] = '';
				$output['searchClient'] = '';
				$output['searchKey'] = '';

				if(isset($_GET['search']))
				{
				  if(isset($_GET['genre']) && strlen($_GET['genre'])>0)
				   {
					 $output['searchGenre'] = $_GET['genre'];
					 $whereItems[] = "tracks.genre = '". $_GET['genre'] ."'";
				   }

				   if(isset($_GET['bpm']) && strlen($_GET['bpm'])>0)
				   {
					 $output['searchBpm'] = $_GET['bpm'];
					 $whereItems[] = "tracks_mp3s.bpm = '". $_GET['bpm'] ."'";
				   }

				   if(isset($_GET['version']) && strlen($_GET['version'])>0)
				   {
					 $output['searchVersion'] = $_GET['version'];
					 $whereItems[] = "tracks_mp3s.version like '%". $_GET['version'] ."%'";
				   }

				   if(isset($_GET['searchKey']) && strlen($_GET['searchKey'])>0)
				   {
					 $output['searchKey'] = $_GET['searchKey'];
					 $searchKey = urlencode(trim($_GET['searchKey']));

			 $whereItems[] = "(tracks.artist like '%". $searchKey ."%' OR tracks.title like '%". $searchKey ."%' OR tracks.album like '%". $searchKey ."%' OR tracks.label like '%". $searchKey ."%' OR tracks.bpm like '%". $searchKey ."%')";
			 //print_r($whereItems);
				   }
				}

				if(count($whereItems)>1)
				{
				   $whereString = implode(' AND ',$whereItems);
				   $where .= $whereString;
				}
				else if(count($whereItems)==1)
				{
				   $where .= $whereItems[0];
				}
				else
				{
				  $where =  '';
				}

				// generate sort

				$sortOrder = "DESC";
				$sortBy = "tracks.id";
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

				   else if(strcmp($_GET['sortBy'],'song')==0)
				   {
					 $sortBy = "title";
				   }
					else if(strcmp($_GET['sortBy'],'label')==0)
				   {
					 $sortBy = "label";
				   }

				   else if(strcmp($_GET['sortBy'],'date')==0)
				   {
					 $sortBy = "tracks.added";
				   }
				   else if(strcmp($_GET['sortBy'],'album')==0)
				   {
					 $sortBy = "album";
				   }
				   else if(strcmp($_GET['sortBy'],'bpm')==0)
				   {
					 $sortBy = "tracks_mp3s.bpm";
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
				if ($sortBy == "title") $sort = "id"." ".$sortOrder;
				else $sort =  $sortBy." ".$sortOrder;

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

			  $num_records = $this->memberAllDB_model->getOwnArchivesListCount($where,$sort);
			  
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
			 $output['currentPage'] = 'Member_dashboard_all_tracks';

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
				
				$output['tracks'] = $this->memberAllDB_model->getOwnArchivesList($where,$sort,$start,$limit);
				
				if($output['tracks']['numRows']>0){
				    
				
				
// 		 echo "<pre>";print_r($output['tracks']);echo "</pre>"; die();

    				foreach($output['tracks']['data'] as $track)
        				{
        				 $output['memberReviews'] = $this->memberAllDB_model->getClientTrackReview($track->id, $memId);
        				 $output['downloads'][$track->id] = $output['memberReviews']['numRows'];
        				 $output['mp3s'][$track->id] = $this->memberAllDB_model->getTrackMp3s($track->id);
        				 $output['social'][$track->id] = $this->memberAllDB_model->getClientSocialInfo($track->client);
        				}
        				
        				 $arr1=$output['tracks']['data'];
    			    	 $arr=json_decode(json_encode($arr1));
    				
    				// $arr= json_decode($arr1);
    				 
        				 foreach ($arr as $key=>$value){
        
        				     $query_loc=$this->memberAllDB_model->getTrackMp3s_fem($value->id);
        				    //  pArr($query_loc);
							$xx='';
        				     foreach($query_loc['data'] as $key1=>$value1){
        				        $xx= $value1->location;
        				        
        				     }
							 if(!empty($xx)){
							 $arr[$key]->location=$xx;
							 }
        				   
        				 }
        				 $output['tracks']['data']=$arr;
    				
    		}
    				
				
				

		  // generate sort url sting

		  $sortExclude = array("sortOrder","sortBy","page");
		  $getSortArray = array(); $urlSortString = '?';

		 if(isset($_GET))
		 {
			foreach($_GET as $key=>$value)
			{
			   if(!(in_array($key,$sortExclude)))
			   {
				 $getSortArray[] = $key.'='.$value;
			   }
			}

			if(count($getSortArray)>1)
			{
			  $urlSortString .= implode('&',$getSortArray);
			}
			else if(count($getSortArray)==1)
			{
			  $urlSortString .= $getSortArray[0];
			}
			else
			{
			  $urlSortString = '';
			}
		 }

		 $output['urlSortString'] = $urlSortString;

		 // generate search url sting

		  $searchExclude = array("bpm","version","genre","page","records");
		  $getSearchArray = array(); $urlSearchString = '?';

		 if(isset($_GET))
		 {
			foreach($_GET as $key=>$value)
			{
			   if(!(in_array($key,$searchExclude)))
			   {
				 $getSearchArray[] = $key.'='.$value;
			   }
			}

			if(count($getSearchArray)>1)
			{
			  $urlSearchString .= implode('&',$getSearchArray);
			}
			else if(count($getSearchArray)==1)
			{
			  $urlSearchString .= $getSearchArray[0];
			}
			else
			{
			  $urlSearchString = '';
			}
		 }

		 $output['urlSearchString'] = $urlSearchString;

		 // generate record url sting

		  $recordExclude = array("records");
		  $getRecordArray = array(); $urlRecordString = '?';
		 if(isset($_GET))
		 {
			foreach($_GET as $key=>$value)
			{
			   if(!(in_array($key,$recordExclude)))
			   {
				 $getRecordArray[] = $key.'='.$value;
			   }
			}

			if(count($getRecordArray)>1)
			{
			  $urlRecordString .= implode('&',$getRecordArray);
			}
			else if(count($getRecordArray)==1)
			{
			  $urlRecordString .= $getRecordArray[0];
			}
			else
			{
			  $urlRecordString = '';
			}
		 }

		 $output['urlRecordString'] = $urlRecordString;
		
		$output['staffTracks'] = $this->memberAllDB_model->getStaffSelectedTracks(0,4);
		if($output['staffTracks']['numRows']>0){
		 foreach($output['staffTracks']['data'] as $track)
 		 {
		   $output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays($track->id); 
		   $row = $this->memberAllDB_model->getClientTrackReview($track->id, $memId); 
		   $output['reviews'][$track->id] = $row['numRows'];
		   $output['mp3s'][$track->id] = $this->memberAllDB_model->getTrackMp3s($track->id);
		}
	   }
	   
	   $output['youTracks'] = $this->memberAllDB_model->getYouSelectedTracks1($memId,0,4);
	   
	   if($output['youTracks']['numRows']>0){ 

			foreach($output['youTracks']['data'] as $track){

			   $output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays($track->id); 

			   $row = $this->memberAllDB_model->getClientTrackReview($track->id, $memId); 

			   $output['reviews'][$track->id] = $row['numRows'];
			   
			   $output['versions'][$track->id] = $this->memberAllDB_model->getTrackMp3s1($track->id);

			}
		}else{ 
			
			$output['youTracks'] = $this->memberAllDB_model->getYouSelectedTracks(0,4); 
			
			if($output['youTracks']['numRows']>0){

				foreach($output['youTracks']['data'] as $track){		

				   $output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays($track->id); 

				   $row = $this->memberAllDB_model->getClientTrackReview($track->id, $memId); 

				   $output['reviews'][$track->id] = $row['numRows'];		   

				   $output['versions'][$track->id] = $this->memberAllDB_model->getTrackMp3s($track->id);
				}
			}
		}
		
		$footerOutput['tracks'] = $this->memberAllDB_model->getMemberFooterTracks($memPakage);
		
		$unReadmessages = $this->memberAllDB_model->getMemberUnreadInbox($memId);
		$output['numMessages'] = $unReadmessages['numRows'];
		
		$headerOutput['numMessages'] = $unReadmessages['numRows'];
		$headerOutput['subscriptionStatus'] = 0;
		$headerOutput['package'] = '';
		
		$subscriptionInfo = $this->memberAllDB_model->getMemberSubscriptionStatus($memId);
		
		if($subscriptionInfo['numRows']>0){

		  $headerOutput['subscriptionStatus'] = 1;

		  //$_SESSION['memberPackage'] = $subscriptionInfo['data'][0]->package_Id;
		  Session::put('memberPackage', $subscriptionInfo['data'][0]->package_Id);
		   

		  if($subscriptionInfo['data'][0]->package_Id==1){

		    $headerOutput['packageId'] = 1;

			$headerOutput['package'] = 'Silver Subscription';

			// $headerOutput['displayDashboard'] = 0;

		  }else if($subscriptionInfo['data'][0]->package_Id==2){

		    $headerOutput['packageId'] = 2;

		    $headerOutput['package'] = 'Gold Subscription';

			//$headerOutput['displayDashboard'] = 1;

		  }else if($subscriptionInfo['data'][0]->package_Id==3){

		    $headerOutput['packageId'] = 3;

		    $headerOutput['package'] = 'Purple Subscription';

			//$headerOutput['displayDashboard'] = 1;
		  }

		}else{
		 Session::put('memberPackage', 1);
		}
		$headerOutput['tracks'] = $footerOutput['tracks'];
// 		pArr($output);
		
// 			$output['tracks']= $this->memberAllDB_model->getmemtracks($memId);

		
		
		return view('members.dashboard.MemberTracksOwnArchives', $output);
	}	
	
	public function viewNotifications(){
		$output = array();
		$logo_data = array(
			'logo_id' => 1,
			);

		$logo_details = DB::table('website_logo')
		->where($logo_data)
		->first();  
		
		$get_logo = $logo_details->logo;	
		$output['pageTitle'] = 'Notifications';
		$output['logo'] = $get_logo;
		$output['active'] = 'notifications';
		if(empty(Session::get('memberId'))){ 
			return redirect()->intended('login');
		}
		if(!empty(Session::get('tempMemberId'))){

			 $output['welcomeMsg'] = 'Thank you for updating your information !';
			 
			 Session::forget('tempMemberId');
		}
		$memId = Session::get('memberId');
		$memPakage = Session::get('memberPackage');
		
		$tracks = $this->memberAllDB_model->getNotifications();
		
		if($tracks['numRows']>0){
			foreach($tracks['data'] as $track){
			
			 if(strlen($track->thumb)<4)
			 {
			   $img = 'https://digiwaxx.com/ImagesUp/'.$track->imgpage;
			 }
			 else
			 {
			  $img = 'https://digiwaxx.com/thumbs/'.$track->thumb;
			 }
			 
			  $arr[] = array('id' => $track->id, 'artist' => urldecode($track->artist), 'title' => urldecode($track->title), 'thumb' => $img);
			}
		}
		
		echo json_encode($arr);
		
	}	
	public function viewMemberMyDigicoins(){
		$output = array();
		$logo_data = array(
			'logo_id' => 1,
			);

		$logo_details = DB::table('website_logo')
		->where($logo_data)
		->first();  
		
		$get_logo = $logo_details->logo;	
		$output['pageTitle'] = 'Digiwax Member Digicoins';
		$output['logo'] = $get_logo;
		$output['active'] = 'mydigicoins';
		if(empty(Session::get('memberId'))){ 
			return redirect()->intended('login');
		}
		if(!empty(Session::get('tempMemberId'))){

			 $output['welcomeMsg'] = 'Thank you for updating your information !';
			 
			 Session::forget('tempMemberId');
		}
		$memId = Session::get('memberId');
			$output['tracks']= $this->memberAllDB_model->getmemtracks($memId);
		$memPakage = Session::get('memberPackage');
		
		
		// generate where

		$where = 'where ';

		$whereItems[] = "member_digicoins.member_id = '". $memId ."'";

		

		$output['searchKey'] = '';

		   if(isset($_GET['searchKey']) && strlen($_GET['searchKey'])>0){

		     $output['searchKey'] = $_GET['searchKey'];

			 $searchKey = urlencode(trim($_GET['searchKey']));

			$whereItems[] = "products.name like '". $searchKey ."%'";

		   }
		   
		if(count($whereItems)>1){

		

		   $whereString = implode(' AND ',$whereItems);

		   $where .= $whereString;

		}else if(count($whereItems)==1){

		   $where .= $whereItems[0];

		}else{

		  $where =  '';

		}

		// generate sort

		$sortOrder = "DESC";

		$sortBy = "member_digicoins.member_digicoin_id";

		$output['sortBy'] = 'song';

		$output['sortOrder'] = 1;

		

		if(isset($_GET['sortBy']) && isset($_GET['sortOrder']))

		{

		   $output['sortBy'] = $_GET['sortBy'];

		   $output['sortOrder'] = $_GET['sortOrder'];

		   

		   

		   if(strcmp($_GET['sortBy'],'artist')==0)

		   {

		    

			 $sortBy = "artist";

		   }

		   else if(strcmp($_GET['sortBy'],'song')==0)

		   {

		    

			 $sortBy = "title";

		   }

		    else if(strcmp($_GET['sortBy'],'label')==0)

		   {

		    

			 $sortBy = "label";

		   }

		   else if(strcmp($_GET['sortBy'],'date')==0)

		   {

		    

			 $sortBy = "tracks.added";

		   }

		   else if(strcmp($_GET['sortBy'],'album')==0)

		   {

		    

			 $sortBy = "album";

		   }

		   else if(strcmp($_GET['sortBy'],'bpm')==0)

		   {

		    

			 $sortBy = "tracks_mp3s.bpm";

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

		if(isset($_GET['page']) && $_GET['page']>1){

			$start = ($_GET['page']*$limit)-$limit;  

		 }
		 if(isset($_GET['page'])){

			$currentPageNo = $_GET['page']; 

		 }
	   $num_records = $this->memberAllDB_model->getNumMemberDigicoins($where,$sort);	

	  $numPages = (int)($num_records/$limit); 

	  $reminder = ($num_records%$limit);	  

	  $output['num_records'] = $num_records;	 

		if($reminder>0){

		 $numPages = $numPages+1;

		}

	

		$output['numPages'] = $numPages;

		$output['start'] = $start;

		$output['currentPageNo'] = $currentPageNo;

		$output['currentPage'] = 'Member_my_digicoins';

		// generate url string

		$getArray = array(); $urlString = '?';

		if(isset($_GET)){

			foreach($_GET as $key=>$value){

				if(strcmp($key,'page')!=0){	     

				$getArray[] = $key.'='.$value;
				}

			}
			if(count($getArray)>1){

			  $urlString .= implode('&',$getArray);

			}else if(count($getArray)==1){

			  $urlString .= $getArray[0];

			}else{

			  $urlString = '';

			}

		 }	

		$output['urlString'] = $urlString; 	 

		// uncomment later

	 if(isset($_GET['page'])) {
		if(strlen($urlString)>3){
		   $param = '&';
		}else{
		  $param = '?';
		}

		if ($_GET['page'] > $numPages && $numPages > 0) {

  	     header("location: ".$output['currentPage'].$urlString.$param."page=" . $numPages);

         exit;
        }
    }
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

  // pagination
 $output['digicoins'] = $this->memberAllDB_model->getMemberDigicoins($where,$sort,$start,$limit);

		if($output['digicoins']['numRows']>0){

		  foreach($output['digicoins']['data'] as $digicoin){

		  // 		     if($digicoin->type_id)

		  }

		}

  // generate sort url sting

  $sortExclude = array("sortOrder","sortBy","page");

  $getSortArray = array(); $urlSortString = '?';

 if(isset($_GET)){

    foreach($_GET as $key=>$value){

       if(!(in_array($key,$sortExclude))){	     

		 $getSortArray[] = $key.'='.$value;	    

	   }

 	}
	if(count($getSortArray)>1){

	  $urlSortString .= implode('&',$getSortArray);

	}else if(count($getSortArray)==1){

	  $urlSortString .= $getSortArray[0];

	}else{

	  $urlSortString = '';

	}

 }

 $output['urlSortString'] = $urlSortString; 	

 

 // generate search url sting

  $searchExclude = array("bpm","version","genre","page","records");

  $getSearchArray = array(); $urlSearchString = '?';

 if(isset($_GET)){

    foreach($_GET as $key=>$value){
		
       if(!(in_array($key,$searchExclude))){	     

		 $getSearchArray[] = $key.'='.$value;	    

	   }

 	}	

	if(count($getSearchArray)>1){

	  $urlSearchString .= implode('&',$getSearchArray);

	}else if(count($getSearchArray)==1){

	  $urlSearchString .= $getSearchArray[0];

	}else{

	  $urlSearchString = '';

	}

 }		

 $output['urlSearchString'] = $urlSearchString; 

 // generate record url sting

  $recordExclude = array("records");

  $getRecordArray = array(); $urlRecordString = '?';

 if(isset($_GET)){

    foreach($_GET as $key=>$value){	  

       if(!(in_array($key,$recordExclude))){	     

		 $getRecordArray[] = $key.'='.$value;	    

	   }

 	}	

	if(count($getRecordArray)>1){

	  $urlRecordString .= implode('&',$getRecordArray);

	}else if(count($getRecordArray)==1){

	  $urlRecordString .= $getRecordArray[0];

	}else{

	  $urlRecordString = '';

	}

 }		

 $output['urlRecordString'] = $urlRecordString;  
 
 		$available_digicoins = $this->memberAllDB_model->get_member_available_digicoins($memId); 

		$output['available_digicoins'] = 0;

		if($available_digicoins['numRows']>0)

		{

		  $output['available_digicoins'] = $available_digicoins['data'][0]->available_points;

		}
		
		$output['staffTracks'] = $this->memberAllDB_model->getStaffSelectedTracks(0,4);
		if($output['staffTracks']['numRows']>0){
		 foreach($output['staffTracks']['data'] as $track)
 		 {
		   $output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays($track->id); 
		   $row = $this->memberAllDB_model->getClientTrackReview($track->id, $memId); 
		   $output['reviews'][$track->id] = $row['numRows'];
		   $output['mp3s'][$track->id] = $this->memberAllDB_model->getTrackMp3s($track->id);
		}
	   }
	   
	   $output['youTracks'] = $this->memberAllDB_model->getYouSelectedTracks1($memId,0,4);
	   
	   if($output['youTracks']['numRows']>0){ 

			foreach($output['youTracks']['data'] as $track){

			   $output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays($track->id); 

			   $row = $this->memberAllDB_model->getClientTrackReview($track->id, $memId); 

			   $output['reviews'][$track->id] = $row['numRows'];
			   
			   $output['versions'][$track->id] = $this->memberAllDB_model->getTrackMp3s1($track->id);

			}
		}else{ 
			
			$output['youTracks'] = $this->memberAllDB_model->getYouSelectedTracks(0,4); 
			
			if($output['youTracks']['numRows']>0){

				foreach($output['youTracks']['data'] as $track){		

				   $output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays($track->id); 

				   $row = $this->memberAllDB_model->getClientTrackReview($track->id, $memId); 

				   $output['reviews'][$track->id] = $row['numRows'];		   

				   $output['versions'][$track->id] = $this->memberAllDB_model->getTrackMp3s($track->id);
				}
			}
		}
		
		$footerOutput['tracks'] = $this->memberAllDB_model->getMemberFooterTracks($memPakage);
		
		$unReadmessages = $this->memberAllDB_model->getMemberUnreadInbox($memId);
		$output['numMessages'] = $unReadmessages['numRows'];
		
		$headerOutput['numMessages'] = $unReadmessages['numRows'];
		$headerOutput['subscriptionStatus'] = 0;
		$headerOutput['package'] = '';
		
		$subscriptionInfo = $this->memberAllDB_model->getMemberSubscriptionStatus($memId);
		
		if($subscriptionInfo['numRows']>0){

		  $headerOutput['subscriptionStatus'] = 1;

		  //$_SESSION['memberPackage'] = $subscriptionInfo['data'][0]->package_Id;
		  Session::put('memberPackage', $subscriptionInfo['data'][0]->package_Id);
		   

		  if($subscriptionInfo['data'][0]->package_Id==1){

		    $headerOutput['packageId'] = 1;

			$headerOutput['package'] = 'Silver Subscription';

			// $headerOutput['displayDashboard'] = 0;

		  }else if($subscriptionInfo['data'][0]->package_Id==2){

		    $headerOutput['packageId'] = 2;

		    $headerOutput['package'] = 'Gold Subscription';

			//$headerOutput['displayDashboard'] = 1;

		  }else if($subscriptionInfo['data'][0]->package_Id==3){

		    $headerOutput['packageId'] = 3;

		    $headerOutput['package'] = 'Purple Subscription';

			//$headerOutput['displayDashboard'] = 1;
		  }

		}else{
		 Session::put('memberPackage', 1);
		}
		$headerOutput['tracks'] = $footerOutput['tracks'];
		
		return view('members.dashboard.MemberMyDigicoins', $output);
	}	
	public function viewMemberStaffPicks(){
		$output = array();
		$logo_data = array(
			'logo_id' => 1,
			);

		$logo_details = DB::table('website_logo')
		->where($logo_data)
		->first();  
		
		$get_logo = $logo_details->logo;	
		$output['pageTitle'] = 'Digiwax Member Staff Picks';
		$output['logo'] = $get_logo;
		if(empty(Session::get('memberId'))){ 
			return redirect()->intended('login');
		}
		if(!empty(Session::get('tempMemberId'))){

			 $output['welcomeMsg'] = 'Thank you for updating your information !';
			 
			 Session::forget('tempMemberId');
		}
		$memId = Session::get('memberId');
		$memPakage = Session::get('memberPackage');
		
		$output['currentPage'] = 'Member_dashboard_top_downloads';
		
		$output['staffTracks'] = $this->memberAllDB_model->getAllStaffSelectedTracks();
		
		if($output['staffTracks']['numRows']>0){

		foreach($output['staffTracks']['data'] as $track){		

		   $output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays($track->id);

		   $row = $this->memberAllDB_model->getClientTrackReview($track->id, $memId); 

		   $output['reviews'][$track->id] = $row['numRows']; 		   

		    $output['mp3s'][$track->id] = $this->memberAllDB_model->getTrackMp3s($track->id);

		}
		
		 $arr1=$output['staffTracks']['data'];
				 $arr=json_decode(json_encode($arr1));
				
				// $arr= json_decode($arr1);
				 
				 foreach ($arr as $key=>$value){

				     $query_loc=$this->memberAllDB_model->getTrackMp3s_fem($value->id);
				    //  pArr($query_loc);
					$xx='';
				     foreach($query_loc['data'] as $key1=>$value1){
				        $xx= $value1->location;
				        
				     }
					 if(!empty($xx)){
				     $arr[$key]->location=$xx;
					 }
				   
				 }
				 $output['staffTracks']['data']=$arr;

		}
		
/* 		$output['staffTracks'] = $this->memberAllDB_model->getStaffSelectedTracks(0,4);
		if($output['staffTracks']['numRows']>0){
		 foreach($output['staffTracks']['data'] as $track)
 		 {
		   $output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays($track->id); 
		   $row = $this->memberAllDB_model->getClientTrackReview($track->id, $memId); 
		   $output['reviews'][$track->id] = $row['numRows'];
		   $output['mp3s'][$track->id] = $this->memberAllDB_model->getTrackMp3s($track->id);
		}
	   } */
	   
	   $output['youTracks'] = $this->memberAllDB_model->getYouSelectedTracks1($memId,0,4);
	   
	   if($output['youTracks']['numRows']>0){ 

			foreach($output['youTracks']['data'] as $track){

			   $output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays($track->id); 

			   $row = $this->memberAllDB_model->getClientTrackReview($track->id, $memId); 

			   $output['reviews'][$track->id] = $row['numRows'];
			   
			   $output['versions'][$track->id] = $this->memberAllDB_model->getTrackMp3s1($track->id);

			}
		}else{ 
			
			$output['youTracks'] = $this->memberAllDB_model->getYouSelectedTracks(0,4); 
			
			if($output['youTracks']['numRows']>0){

				foreach($output['youTracks']['data'] as $track){		

				   $output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays($track->id); 

				   $row = $this->memberAllDB_model->getClientTrackReview($track->id, $memId); 

				   $output['reviews'][$track->id] = $row['numRows'];		   

				   $output['versions'][$track->id] = $this->memberAllDB_model->getTrackMp3s($track->id);
				}
			}
		}
		
		$footerOutput['tracks'] = $this->memberAllDB_model->getMemberFooterTracks($memPakage);
		
		$unReadmessages = $this->memberAllDB_model->getMemberUnreadInbox($memId);
		$output['numMessages'] = $unReadmessages['numRows'];
		
		$headerOutput['numMessages'] = $unReadmessages['numRows'];
		$headerOutput['subscriptionStatus'] = 0;
		$headerOutput['package'] = '';
		
		$subscriptionInfo = $this->memberAllDB_model->getMemberSubscriptionStatus($memId);
		
		if($subscriptionInfo['numRows']>0){

		  $headerOutput['subscriptionStatus'] = 1;

		  //$_SESSION['memberPackage'] = $subscriptionInfo['data'][0]->package_Id;
		  Session::put('memberPackage', $subscriptionInfo['data'][0]->package_Id);
		   

		  if($subscriptionInfo['data'][0]->package_Id==1){

		    $headerOutput['packageId'] = 1;

			$headerOutput['package'] = 'Silver Subscription';

			// $headerOutput['displayDashboard'] = 0;

		  }else if($subscriptionInfo['data'][0]->package_Id==2){

		    $headerOutput['packageId'] = 2;

		    $headerOutput['package'] = 'Gold Subscription';

			//$headerOutput['displayDashboard'] = 1;

		  }else if($subscriptionInfo['data'][0]->package_Id==3){

		    $headerOutput['packageId'] = 3;

		    $headerOutput['package'] = 'Purple Subscription';

			//$headerOutput['displayDashboard'] = 1;
		  }

		}else{
		 Session::put('memberPackage', 1);
		}
		$headerOutput['tracks'] = $footerOutput['tracks'];
		
	$output['tracks']=$output['staffTracks'];
		return view('members.dashboard.MemberStaffPicks', $output);
	}

	public function viewMemberSelectedForYou(){
		$output = array();
		$logo_data = array(
			'logo_id' => 1,
			);

		$logo_details = DB::table('website_logo')
		->where($logo_data)
		->first();  
		
		$get_logo = $logo_details->logo;	
		$output['pageTitle'] = 'Digiwax Member Selected For You';
		$output['logo'] = $get_logo;
		if(empty(Session::get('memberId'))){ 
			return redirect()->intended('login');
		}
		if(!empty(Session::get('tempMemberId'))){

			 $output['welcomeMsg'] = 'Thank you for updating your information !';
			 
			 Session::forget('tempMemberId');
		}
		$memId = Session::get('memberId');
		$memPakage = Session::get('memberPackage');
		
		$output['currentPage'] = 'Member_dashboard_top_downloads';
		
		$output['staffTracks'] = $this->memberAllDB_model->getStaffSelectedTracks(0,4);
		if($output['staffTracks']['numRows']>0){
		 foreach($output['staffTracks']['data'] as $track)
 		 {
		   $output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays($track->id); 
		   $row = $this->memberAllDB_model->getClientTrackReview($track->id, $memId); 
		   $output['reviews'][$track->id] = $row['numRows'];
		   $output['mp3s'][$track->id] = $this->memberAllDB_model->getTrackMp3s($track->id);
		}
	   }
	   
	   $output['youTracks'] = $this->memberAllDB_model->getYouSelectedTracks1($memId,0,4);
	   
	   if($output['youTracks']['numRows']>0){ 

			foreach($output['youTracks']['data'] as $track){

			   $output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays($track->id); 

			   $row = $this->memberAllDB_model->getClientTrackReview($track->id, $memId); 

			   $output['reviews'][$track->id] = $row['numRows'];
			   
			   $output['versions'][$track->id] = $this->memberAllDB_model->getTrackMp3s1($track->id);

			}
		}else{ 
			
			$output['youTracks'] = $this->memberAllDB_model->getYouSelectedTracks(0,4); 
			
			if($output['youTracks']['numRows']>0){

				foreach($output['youTracks']['data'] as $track){		

				   $output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays($track->id); 

				   $row = $this->memberAllDB_model->getClientTrackReview($track->id, $memId); 

				   $output['reviews'][$track->id] = $row['numRows'];		   

				   $output['versions'][$track->id] = $this->memberAllDB_model->getTrackMp3s($track->id);
				}
			}
		}
		
		 $arr1=$output['youTracks']['data'];
				 $arr=json_decode(json_encode($arr1));
				
				// $arr= json_decode($arr1);
				 
				 foreach ($arr as $key=>$value){

				     $query_loc=$this->memberAllDB_model->getTrackMp3s_fem($value->id);
				    //  pArr($query_loc);
					$xx='';
				     foreach($query_loc['data'] as $key1=>$value1){
				        $xx= $value1->location;
				        
				     }
					 if(!empty($xx)){
				     $arr[$key]->location=$xx;
					 }
				   
				 }
				 $output['youTracks']['data']=$arr;
				 
				 $output['tracks']=$output['youTracks'];
		
		
		
		
		
		
		
		$footerOutput['tracks'] = $this->memberAllDB_model->getMemberFooterTracks($memPakage);
		
		$unReadmessages = $this->memberAllDB_model->getMemberUnreadInbox($memId);
		$output['numMessages'] = $unReadmessages['numRows'];
		
		$headerOutput['numMessages'] = $unReadmessages['numRows'];
		$headerOutput['subscriptionStatus'] = 0;
		$headerOutput['package'] = '';
		
		$subscriptionInfo = $this->memberAllDB_model->getMemberSubscriptionStatus($memId);
		
		if($subscriptionInfo['numRows']>0){

		  $headerOutput['subscriptionStatus'] = 1;

		  //$_SESSION['memberPackage'] = $subscriptionInfo['data'][0]->package_Id;
		  Session::put('memberPackage', $subscriptionInfo['data'][0]->package_Id);
		   

		  if($subscriptionInfo['data'][0]->package_Id==1){

		    $headerOutput['packageId'] = 1;

			$headerOutput['package'] = 'Silver Subscription';

			// $headerOutput['displayDashboard'] = 0;

		  }else if($subscriptionInfo['data'][0]->package_Id==2){

		    $headerOutput['packageId'] = 2;

		    $headerOutput['package'] = 'Gold Subscription';

			//$headerOutput['displayDashboard'] = 1;

		  }else if($subscriptionInfo['data'][0]->package_Id==3){

		    $headerOutput['packageId'] = 3;

		    $headerOutput['package'] = 'Purple Subscription';

			//$headerOutput['displayDashboard'] = 1;
		  }

		}else{
		 Session::put('memberPackage', 1);
		}
		$headerOutput['tracks'] = $footerOutput['tracks'];
		
		
// 		 if($_SERVER['REMOTE_ADDR'] = '122.185.217.118'){

//             pArr($output);
//             die();
//         }
		//pArr($output);die();
		return view('members.dashboard.MemberSelectedForYou', $output);
	}
	public function viewMemberOrders(){
		$output = array();
		$logo_data = array(
			'logo_id' => 1,
			);

		$logo_details = DB::table('website_logo')
		->where($logo_data)
		->first();  
		
		$get_logo = $logo_details->logo;	
		$output['pageTitle'] = 'Digiwax Member Orders';
		$output['logo'] = $get_logo;
		$output['active'] = 'orders';
		if(empty(Session::get('memberId'))){ 
			return redirect()->intended('login');
		}
		if(!empty(Session::get('tempMemberId'))){

			 $output['welcomeMsg'] = 'Thank you for updating your information !';
			 
			 Session::forget('tempMemberId');
		}
		$memId = Session::get('memberId');
		$memPakage = Session::get('memberPackage');
		
		$output['staffTracks'] = $this->memberAllDB_model->getStaffSelectedTracks(0,4);
		if($output['staffTracks']['numRows']>0){
		 foreach($output['staffTracks']['data'] as $track)
 		 {
		   $output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays($track->id); 
		   $row = $this->memberAllDB_model->getClientTrackReview($track->id, $memId); 
		   $output['reviews'][$track->id] = $row['numRows'];
		   $output['mp3s'][$track->id] = $this->memberAllDB_model->getTrackMp3s($track->id);
		}
	   }
	   
	   		// generate where

		$where = 'where ';

		$whereItems[] = "product_orders.member_id = '". $memId ."'";

		

		$output['searchKey'] = '';

		

		

		

		   if(isset($_GET['searchKey']) && strlen($_GET['searchKey'])>0)

		   {

		     $output['searchKey'] = $_GET['searchKey'];

			 $searchKey = urlencode(trim($_GET['searchKey']));

	 $whereItems[] = "products.name like '". $searchKey ."%'";

		   }

		

		

		if(count($whereItems)>1)

		{

		

		   $whereString = implode(' AND ',$whereItems);

		   $where .= $whereString;

		}

		else if(count($whereItems)==1)

		{

		   $where .= $whereItems[0];

		}

		else

		{

		  $where =  '';

		}

		

		

		// generate sort

		$sortOrder = "DESC";

		$sortBy = "product_orders.order_id";

		

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
		 
		 $num_records = $this->memberAllDB_model->get_num_product_orders($where,$sort);
		 
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

	 $output['currentPage'] = 'Member_orders';
	 
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

		 if(strlen($urlString)>3){

			   $param = '&';

			}else{

			  $param = '?';

			}

	  if($_GET['page'] > $numPages && $numPages > 0) {

			 return Redirect::to($output['currentPage'].$urlString.$param."page=" . $numPages);

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
	  
	  $output['orders'] = $this->memberAllDB_model->get_product_orders($where,$sort,$start,$limit);
	  
	    // generate sort url sting

	  $sortExclude = array("sortOrder","sortBy","page");

	  $getSortArray = array(); $urlSortString = '?';

	 if(isset($_GET))

	 {

		foreach($_GET as $key=>$value)

		{

		   if(!(in_array($key,$sortExclude)))

		   {

			 

			 $getSortArray[] = $key.'='.$value;

			

		   }

		}
		
		if(count($getSortArray)>1)

	{

	  $urlSortString .= implode('&',$getSortArray);

	}

	else if(count($getSortArray)==1)

	{

	  $urlSortString .= $getSortArray[0];

	}

	else

	{

	  $urlSortString = '';

	}

 }

 $output['urlSortString'] = $urlSortString; 
 
  // generate search url sting

  $searchExclude = array("bpm","version","genre","page","records");

  $getSearchArray = array(); $urlSearchString = '?';

 if(isset($_GET))

 {

    foreach($_GET as $key=>$value)

 	{

	

	  

       if(!(in_array($key,$searchExclude)))

	   {

	     

		 $getSearchArray[] = $key.'='.$value;

	    

	   }

 	}

	

	if(count($getSearchArray)>1)

	{

	  $urlSearchString .= implode('&',$getSearchArray);

	}

	else if(count($getSearchArray)==1)

	{

	  $urlSearchString .= $getSearchArray[0];

	}

	else

	{

	  $urlSearchString = '';

	}

 }		

 $output['urlSearchString'] = $urlSearchString;
 
 // generate record url sting

  $recordExclude = array("records");

  $getRecordArray = array(); $urlRecordString = '?';

 if(isset($_GET))

 {

    foreach($_GET as $key=>$value)

 	{

	

	  

       if(!(in_array($key,$recordExclude)))

	   {

	     

		 $getRecordArray[] = $key.'='.$value;

	    

	   }

 	}

	

	if(count($getRecordArray)>1)

	{

	  $urlRecordString .= implode('&',$getRecordArray);

	}

	else if(count($getRecordArray)==1)

	{

	  $urlRecordString .= $getRecordArray[0];

	}

	else

	{

	  $urlRecordString = '';

	}

 }		

 $output['urlRecordString'] = $urlRecordString; 
 
 
	   
	   $output['youTracks'] = $this->memberAllDB_model->getYouSelectedTracks1($memId,0,4);
	   
	   if($output['youTracks']['numRows']>0){ 

			foreach($output['youTracks']['data'] as $track){

			   $output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays($track->id); 

			   $row = $this->memberAllDB_model->getClientTrackReview($track->id, $memId); 

			   $output['reviews'][$track->id] = $row['numRows'];
			   
			   $output['versions'][$track->id] = $this->memberAllDB_model->getTrackMp3s1($track->id);

			}
		}else{ 
			
			$output['youTracks'] = $this->memberAllDB_model->getYouSelectedTracks(0,4); 
			
			if($output['youTracks']['numRows']>0){

				foreach($output['youTracks']['data'] as $track){		

				   $output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays($track->id); 

				   $row = $this->memberAllDB_model->getClientTrackReview($track->id, $memId); 

				   $output['reviews'][$track->id] = $row['numRows'];		   

				   $output['versions'][$track->id] = $this->memberAllDB_model->getTrackMp3s($track->id);
				}
			}
		}
		
		$footerOutput['tracks'] = $this->memberAllDB_model->getMemberFooterTracks($memPakage);
		
		$unReadmessages = $this->memberAllDB_model->getMemberUnreadInbox($memId);
		$output['numMessages'] = $unReadmessages['numRows'];
		
		$headerOutput['numMessages'] = $unReadmessages['numRows'];
		$headerOutput['subscriptionStatus'] = 0;
		$headerOutput['package'] = '';
		
		$subscriptionInfo = $this->memberAllDB_model->getMemberSubscriptionStatus($memId);
		
		if($subscriptionInfo['numRows']>0){

		  $headerOutput['subscriptionStatus'] = 1;

		  //$_SESSION['memberPackage'] = $subscriptionInfo['data'][0]->package_Id;
		  Session::put('memberPackage', $subscriptionInfo['data'][0]->package_Id);
		   

		  if($subscriptionInfo['data'][0]->package_Id==1){

		    $headerOutput['packageId'] = 1;

			$headerOutput['package'] = 'Silver Subscription';

			// $headerOutput['displayDashboard'] = 0;

		  }else if($subscriptionInfo['data'][0]->package_Id==2){

		    $headerOutput['packageId'] = 2;

		    $headerOutput['package'] = 'Gold Subscription';

			//$headerOutput['displayDashboard'] = 1;

		  }else if($subscriptionInfo['data'][0]->package_Id==3){

		    $headerOutput['packageId'] = 3;

		    $headerOutput['package'] = 'Purple Subscription';

			//$headerOutput['displayDashboard'] = 1;
		  }

		}else{
		 Session::put('memberPackage', 1);
		}
		$headerOutput['tracks'] = $footerOutput['tracks'];
			$output['tracks']= $this->memberAllDB_model->getmemtracks($memId);
		
		return view('members.dashboard.MemberOrders', $output);
	}


	public function viewProductsCallback(){
		$output = array();
		$logo_data = array(
			'logo_id' => 1,
			);

		$logo_details = DB::table('website_logo')
		->where($logo_data)
		->first();  
		
		$get_logo = $logo_details->logo;	
		$output['pageTitle'] = 'Digiwax Member Orders';
		$output['logo'] = $get_logo;
		$output['active'] = 'products';
		if(empty(Session::get('memberId'))){ 
			return redirect()->intended('login');
		}
		if(!empty(Session::get('tempMemberId'))){

			 $output['welcomeMsg'] = 'Thank you for updating your information !';
			 
			 Session::forget('tempMemberId');
		}
		$memId = Session::get('memberId');
			$output['tracks']= $this->memberAllDB_model->getmemtracks($memId);
		$memPakage = Session::get('memberPackage');
		
				 // purchase product

			 if(isset($_GET['purchase']) && isset($_GET['product_id']) && isset($_GET['retail_price']) && isset($_GET['final_price']) && isset($_GET['percentage']))

			 {

			 

			 $result = $this->memberAllDB_model->buyNow($memId,$_GET); 

			 

			 

				  if($result['status']==1)

				  {

					$arr = array('response' => 1, 'message' => 'No Available Digicoins to buy product !', 'balance' => $result['available_points']);

				  }

				  else if($result['status']==2 && $result['order_id']>0)

				  {

					$arr = array('response' => 2, 'message' => 'Your Order has been placed successfully !', 'balance' => $result['remaining_points']);

				  }

				  else if($result['status']==2 && $result['order_id']==0)

				  {

					$arr = array('response' => 3, 'message' => 'Error occured, please try again !', 'balance' => $result['available_points']);

				  }

				  else if($result['status']==3)

				  {

					$arr = array('response' => 4, 'message' => 'You dont have enough digicoins to buy product !', 'balance' => $result['available_points']);

				  }

			  echo json_encode($arr);

			  exit;

			 }

			 

			 

			 

		   

			 

			// generate where

			$where = 'where ';

			$whereItems[] = "products.active = '1'";

			

			$output['searchKey'] = '';

			

			

			

			   if(isset($_GET['searchKey']) && strlen($_GET['searchKey'])>0)

			   {

				 $output['searchKey'] = $_GET['searchKey'];

				 $searchKey = urlencode(trim($_GET['searchKey']));

				 $whereItems[] = "products.name like '". $searchKey ."%'";

					   }

					

					

					if(count($whereItems)>1)

					{

					

					   $whereString = implode(' AND ',$whereItems);

					   $where .= $whereString;

					}

					else if(count($whereItems)==1)

					{

					   $where .= $whereItems[0];

					}

					else

					{

					  $where =  '';

					}

					

					

					// generate sort

					$sortOrder = "ASC";

					$sortBy = "products.product_id";

					$output['sortBy'] = 'song';

					$output['sortOrder'] = 1;

					

					if(isset($_GET['sortBy']) && isset($_GET['sortOrder']))

					{

					   $output['sortBy'] = $_GET['sortBy'];

					   $output['sortOrder'] = $_GET['sortOrder'];

					   

					   

					   if(strcmp($_GET['sortBy'],'artist')==0)

					   {

						

						 $sortBy = "artist";

					   }

					   else if(strcmp($_GET['sortBy'],'song')==0)

					   {

						

						 $sortBy = "title";

					   }

						else if(strcmp($_GET['sortBy'],'label')==0)

					   {

						

						 $sortBy = "label";

					   }

					   else if(strcmp($_GET['sortBy'],'date')==0)

					   {

						

						 $sortBy = "tracks.added";

					   }

					   else if(strcmp($_GET['sortBy'],'album')==0)

					   {

						

						 $sortBy = "album";

					   }

					   else if(strcmp($_GET['sortBy'],'bpm')==0)

					   {

						

						 $sortBy = "tracks_mp3s.bpm";

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

				

				

				

				if($memPakage<2) 

				{

				   $num_records = 20;

				}

				else

				{

				   $num_records = $this->memberAllDB_model->getNumProducts($where,$sort); 

				}

				

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

				 $output['currentPage'] = 'Products';

				 

				 

				 

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

					  

					

					$output['products'] = $this->memberAllDB_model->getProducts($where,$sort,$start,$limit); 

						$today = date('Y-m-d');



			foreach($output['products']['data'] as $product)

					{

					 $output['reviewed'][$product->product_id] = $this->memberAllDB_model->getProductReview($product->product_id,$memId); 

					 $where1 = "where product_id = '". $product->product_id ."' and applies_from <= '". $today ."' order by applies_from desc limit 0, 1";

					 $output['price'][$product->product_id] = $this->memberAllDB_model->getProductPrices($where1); 

					 

					 

			$where2 = "where product_discount.product_id = '". $product->product_id ."' and product_discount.applies_from >= '". $today ."' and product_discount.applies_to <= '". $today ."'";	

					 $output['discounts'][$product->product_id] = $this->memberAllDB_model->getProductDiscounts($where2); 

					}

					  





			  // generate sort url sting

			  $sortExclude = array("sortOrder","sortBy","page");

			  $getSortArray = array(); $urlSortString = '?';

			 if(isset($_GET))

			 {

				foreach($_GET as $key=>$value)

				{

				   if(!(in_array($key,$sortExclude)))

				   {

					 

					 $getSortArray[] = $key.'='.$value;

					

				   }

				}

				

				

				

				if(count($getSortArray)>1)

				{

				  $urlSortString .= implode('&',$getSortArray);

				}

				else if(count($getSortArray)==1)

				{

				  $urlSortString .= $getSortArray[0];

				}

				else

				{

				  $urlSortString = '';

				}

			 }

			 $output['urlSortString'] = $urlSortString; 	

			 

			 // generate search url sting

			  $searchExclude = array("bpm","version","genre","page","records");

			  $getSearchArray = array(); $urlSearchString = '?';

			 if(isset($_GET))

			 {

				foreach($_GET as $key=>$value)

				{

				

				  

				   if(!(in_array($key,$searchExclude)))

				   {

					 

					 $getSearchArray[] = $key.'='.$value;

					

				   }

				}

				

				if(count($getSearchArray)>1)

				{

				  $urlSearchString .= implode('&',$getSearchArray);

				}

				else if(count($getSearchArray)==1)

				{

				  $urlSearchString .= $getSearchArray[0];

				}

				else

				{

				  $urlSearchString = '';

				}

			 }		

			 $output['urlSearchString'] = $urlSearchString; 

			 

			 // generate record url sting

			  $recordExclude = array("records");

			  $getRecordArray = array(); $urlRecordString = '?';

			 if(isset($_GET))

			 {

				foreach($_GET as $key=>$value)

				{

				

				  

				   if(!(in_array($key,$recordExclude)))

				   {

					 

					 $getRecordArray[] = $key.'='.$value;

					

				   }

				}

				

				if(count($getRecordArray)>1)

				{

				  $urlRecordString .= implode('&',$getRecordArray);

				}

				else if(count($getRecordArray)==1)

				{

				  $urlRecordString .= $getRecordArray[0];

				}

				else

				{

				  $urlRecordString = '';

				}

			 }		

			 $output['urlRecordString'] = $urlRecordString;
		
		$available_digicoins = $this->memberAllDB_model->get_member_available_digicoins($memId); 

		$output['available_digicoins'] = 0;

		if($available_digicoins['numRows']>0)

		{

		  $output['available_digicoins'] = $available_digicoins['data'][0]->available_points;

		}
		
		$output['staffTracks'] = $this->memberAllDB_model->getStaffSelectedTracks(0,4);
		if($output['staffTracks']['numRows']>0){
		 foreach($output['staffTracks']['data'] as $track)
 		 {
		   $output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays($track->id); 
		   $row = $this->memberAllDB_model->getClientTrackReview($track->id, $memId); 
		   $output['reviews'][$track->id] = $row['numRows'];
		   $output['mp3s'][$track->id] = $this->memberAllDB_model->getTrackMp3s($track->id);
		}
	   }
	   
	   $output['youTracks'] = $this->memberAllDB_model->getYouSelectedTracks1($memId,0,4);
	   
	   if($output['youTracks']['numRows']>0){ 

			foreach($output['youTracks']['data'] as $track){

			   $output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays($track->id); 

			   $row = $this->memberAllDB_model->getClientTrackReview($track->id, $memId); 

			   $output['reviews'][$track->id] = $row['numRows'];
			   
			   $output['versions'][$track->id] = $this->memberAllDB_model->getTrackMp3s1($track->id);

			}
		}else{ 
			
			$output['youTracks'] = $this->memberAllDB_model->getYouSelectedTracks(0,4); 
			
			if($output['youTracks']['numRows']>0){

				foreach($output['youTracks']['data'] as $track){		

				   $output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays($track->id); 

				   $row = $this->memberAllDB_model->getClientTrackReview($track->id, $memId); 

				   $output['reviews'][$track->id] = $row['numRows'];		   

				   $output['versions'][$track->id] = $this->memberAllDB_model->getTrackMp3s($track->id);
				}
			}
		}
		
		$footerOutput['tracks'] = $this->memberAllDB_model->getMemberFooterTracks($memPakage);
		
		$unReadmessages = $this->memberAllDB_model->getMemberUnreadInbox($memId);
		$output['numMessages'] = $unReadmessages['numRows'];
		
		$headerOutput['numMessages'] = $unReadmessages['numRows'];
		$headerOutput['subscriptionStatus'] = 0;
		$headerOutput['package'] = '';
		
		$subscriptionInfo = $this->memberAllDB_model->getMemberSubscriptionStatus($memId);
		
		if($subscriptionInfo['numRows']>0){

		  $headerOutput['subscriptionStatus'] = 1;

		  //$_SESSION['memberPackage'] = $subscriptionInfo['data'][0]->package_Id;
		  Session::put('memberPackage', $subscriptionInfo['data'][0]->package_Id);
		   

		  if($subscriptionInfo['data'][0]->package_Id==1){

		    $headerOutput['packageId'] = 1;

			$headerOutput['package'] = 'Silver Subscription';

			// $headerOutput['displayDashboard'] = 0;

		  }else if($subscriptionInfo['data'][0]->package_Id==2){

		    $headerOutput['packageId'] = 2;

		    $headerOutput['package'] = 'Gold Subscription';

			//$headerOutput['displayDashboard'] = 1;

		  }else if($subscriptionInfo['data'][0]->package_Id==3){

		    $headerOutput['packageId'] = 3;

		    $headerOutput['package'] = 'Purple Subscription';

			//$headerOutput['displayDashboard'] = 1;
		  }

		}else{
		 Session::put('memberPackage', 1);
		}
		$headerOutput['tracks'] = $footerOutput['tracks'];
	
		return view('members.dashboard.MemberProducts', $output);
	}

	public function viewProductCallback(){
		$output = array();
		$logo_data = array(
			'logo_id' => 1,
			);

		$logo_details = DB::table('website_logo')
		->where($logo_data)
		->first();  
		
		$get_logo = $logo_details->logo;	
		$output['pageTitle'] = 'Digiwax Member Orders';
		$output['logo'] = $get_logo;
		$output['active'] = 'products';
		if(empty(Session::get('memberId'))){ 
			return redirect()->intended('login');
		}
		if(!empty(Session::get('tempMemberId'))){

			 $output['welcomeMsg'] = 'Thank you for updating your information !';
			 
			 Session::forget('tempMemberId');
		}
		$memId = Session::get('memberId');
		$memPakage = Session::get('memberPackage');
		
		// submit review

		 if(isset($_POST['submitReview']) && isset($_GET['pid']) && isset($memId))

		 {

	  	   $result = $this->memberAllDB_model->addProductReview($_POST,$_GET['pid'],$memId); 

		   

		   if($result>0)

		   {

		     return Redirect::to("product?pid=".$_GET['pid']."&added=1");  

		   }

		   else

		   {

		     return Redirect::to("product?pid=".$_GET['pid']."&error=1");  

		   }

		   exit;

		 }

		// generate where

		$where = 'where ';

	//	$whereItems[] = "products.active = '1'";

		$whereItems[] = "products.product_id = '". $_GET['pid'] ."'";

		

		

		$output['searchKey'] = '';

		

		

		

		   if(isset($_GET['searchKey']) && strlen($_GET['searchKey'])>0)

		   {

		     $output['searchKey'] = $_GET['searchKey'];

			 $searchKey = urlencode(trim($_GET['searchKey']));

	 $whereItems[] = "products.name like '". $searchKey ."%'";

		   }

		

		

		if(count($whereItems)>1)

		{

		

		   $whereString = implode(' AND ',$whereItems);

		   $where .= $whereString;

		}

		else if(count($whereItems)==1)

		{

		   $where .= $whereItems[0];

		}

		else

		{

		  $where =  '';

		}

		

		

		// generate sort

		$sortOrder = "ASC";

		$sortBy = "products.product_id";

		$output['sortBy'] = 'song';

		$output['sortOrder'] = 1;

		

		if(isset($_GET['sortBy']) && isset($_GET['sortOrder']))

		{

		   $output['sortBy'] = $_GET['sortBy'];

		   $output['sortOrder'] = $_GET['sortOrder'];

		   

		   $sortBy = $_GET['sortBy'];

		   

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

		

   		$limit = 1;

		$start = 0; 

		  

		

		$output['products'] = $this->memberAllDB_model->getProducts($where,$sort,$start,$limit); 

		$output['questions'] = $this->memberAllDB_model->getProductQuestions($_GET['pid']); 

		

		if($output['questions']['numRows']>0)

		{

		  foreach($output['questions']['data'] as $question)

		  {

		  $output['answers'][$question->question_id] = $this->memberAllDB_model->getProductAnswers($_GET['pid'],$question->question_id);   

		  }

		}

		

		

		foreach($output['products']['data'] as $product)

		{

		 $output['reviewed'][$product->product_id] = $this->memberAllDB_model->getProductReview($product->product_id,$memId); 

		}
		
		
		$available_digicoins = $this->memberAllDB_model->get_member_available_digicoins($memId); 

		$output['available_digicoins'] = 0;

		if($available_digicoins['numRows']>0)

		{

		  $output['available_digicoins'] = $available_digicoins['data'][0]->available_points;

		}
		
		$output['staffTracks'] = $this->memberAllDB_model->getStaffSelectedTracks(0,4);
		if($output['staffTracks']['numRows']>0){
		 foreach($output['staffTracks']['data'] as $track)
 		 {
		   $output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays($track->id); 
		   $row = $this->memberAllDB_model->getClientTrackReview($track->id, $memId); 
		   $output['reviews'][$track->id] = $row['numRows'];
		   $output['mp3s'][$track->id] = $this->memberAllDB_model->getTrackMp3s($track->id);
		}
	   }
	   
	   $output['youTracks'] = $this->memberAllDB_model->getYouSelectedTracks1($memId,0,4);
	   
	   if($output['youTracks']['numRows']>0){ 

			foreach($output['youTracks']['data'] as $track){

			   $output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays($track->id); 

			   $row = $this->memberAllDB_model->getClientTrackReview($track->id, $memId); 

			   $output['reviews'][$track->id] = $row['numRows'];
			   
			   $output['versions'][$track->id] = $this->memberAllDB_model->getTrackMp3s1($track->id);

			}
		}else{ 
			
			$output['youTracks'] = $this->memberAllDB_model->getYouSelectedTracks(0,4); 
			
			if($output['youTracks']['numRows']>0){

				foreach($output['youTracks']['data'] as $track){		

				   $output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays($track->id); 

				   $row = $this->memberAllDB_model->getClientTrackReview($track->id, $memId); 

				   $output['reviews'][$track->id] = $row['numRows'];		   

				   $output['versions'][$track->id] = $this->memberAllDB_model->getTrackMp3s($track->id);
				}
			}
		}
		
		$footerOutput['tracks'] = $this->memberAllDB_model->getMemberFooterTracks($memPakage);
		
		$unReadmessages = $this->memberAllDB_model->getMemberUnreadInbox($memId);
		$output['numMessages'] = $unReadmessages['numRows'];
		
		$headerOutput['numMessages'] = $unReadmessages['numRows'];
		$headerOutput['subscriptionStatus'] = 0;
		$headerOutput['package'] = '';
		
		$subscriptionInfo = $this->memberAllDB_model->getMemberSubscriptionStatus($memId);
		
		if($subscriptionInfo['numRows']>0){

		  $headerOutput['subscriptionStatus'] = 1;

		  //$_SESSION['memberPackage'] = $subscriptionInfo['data'][0]->package_Id;
		  Session::put('memberPackage', $subscriptionInfo['data'][0]->package_Id);
		   

		  if($subscriptionInfo['data'][0]->package_Id==1){

		    $headerOutput['packageId'] = 1;

			$headerOutput['package'] = 'Silver Subscription';

			// $headerOutput['displayDashboard'] = 0;

		  }else if($subscriptionInfo['data'][0]->package_Id==2){

		    $headerOutput['packageId'] = 2;

		    $headerOutput['package'] = 'Gold Subscription';

			//$headerOutput['displayDashboard'] = 1;

		  }else if($subscriptionInfo['data'][0]->package_Id==3){

		    $headerOutput['packageId'] = 3;

		    $headerOutput['package'] = 'Purple Subscription';

			//$headerOutput['displayDashboard'] = 1;
		  }

		}else{
		 Session::put('memberPackage', 1);
		}
		
		if(isset($_GET['added']))				

			{

			  

			   $output['alert_class'] = 'success-msg';

			   $output['alert_message'] = 'Review submitted  successfully !';

			

			}

			else if(isset($_GET['error']))				

			{

			   $output['alert_class'] = 'error-msg';

			   $output['alert_message'] = 'Error occured, please try again.';

			}
		$headerOutput['tracks'] = $footerOutput['tracks'];
	
	
		return view('members.dashboard.MemberProduct', $output);
	}

	
	// member R-s controller function starts here 
	public function viewMemberNewestTracks(Request $request)
	{			
	
		       // header data pass starts

		if(empty(Session::get('memberId'))){ 
			return redirect()->intended('login');
		}

		$memID = Session::get('memberId');

		$memberDetailsConfirm = DB::table("members")->where("id", $memID)->first();

		if($memberDetailsConfirm->dj_mixer == 0 && $memberDetailsConfirm->radio_station == 0){
			//return redirect()->intended('Member_edit_profile');exit; 
		}
		
		$result='';
		$class='';
			
		if(isset($_GET['status']))	{
		    $value=$_GET['status'];
		    
		    if($value=='1'){
		        
		        $last_package=DB::table('package_user_details')->where('package_active',0)->where('user_id',$memID)->where('user_type',1)->orderBy('created','desc')->first();
		   
		        $pac_id=$last_package->package_id;
		        $last_name=DB::table('manage_packages')->where('id',$pac_id)->get();
		     
		        $class="alert alert-success";
		        $result="Your <b> ".$last_name[0]->package_type."</b> subscription has been expired .You have been assigned a Basic Subscription.";
		    }
		    
		    if($value==2){
		        $class="alert alert-success";
		        $result="You have been assigned a Basic Subscription.";
		    }
		}
	
			
					
			   $output = array();
		$output['result']=$result;
		$output['class']=$class;

		
            $query = DB::table('package_user_details')->select('package_id')->where('user_id','=',$memID)->where('user_type','=',1)->where('package_active',1)->get();
            $count = $query->count();
            if($count == 0 || $query[0]->package_id == 7){
                $output['member_package'] = 'Standard';
            }else{
                $output['member_package'] = 'Premium';
            }
            // dd($output['member_package']);

				 $logo_data = array(
					 'logo_id' => 1,
					 );
		
				 $logo_details = DB::table('website_logo')
				 ->where($logo_data)
				 ->first();  
				 
				 $get_logo = $logo_details->logo;
		
				  $output['pageTitle'] = 'Member Dashboard Newest Tracks';
				  $output['logo'] = $get_logo;
				  $output['active'] = 'tracks';
				
				 // fb logout link
				//  $this->load->library('facebook');
				//  $logout_link = url('Logout');
		 
				//  if (isset($_SESSION['fb_access_token'])) {
				// 	 $logout_link = $this->facebook->logout_url();
				//  }
				//  $output['logout_link'] = $logout_link;


				// header data pass ends here and again continue at "#moreHeaderData"
		

				//  $check = $this->memberAllDB_model->test_mem_fun();
     			//  print_r($check);die;
		
		 
		 
				 // generate where
				 $where = 'where ';
				 $whereItems[] = "tracks.deleted = '0'";
				 $whereItems[] = "tracks.active = '1'";
				 $whereItems[] = "tracks.status = 'publish'";
		 
				 $output['searchArtist'] = '';
				 $output['searchTitle'] = '';
				 $output['searchLabel'] = '';
				 $output['searchAlbum'] = '';
				 $output['searchProducer'] = '';
				 $output['searchClient'] = '';
		 
				 
				 if (isset($_GET['searchKey']) && strlen($_GET['searchKey']) > 0) {
					 $output['searchKey'] = $_GET['searchKey'];
					 
					  $searchKey = urlencode(trim($_GET['searchKey']));

			       $whereItems[] = "(tracks.artist LIKE '%" . urlencode($searchKey) . "%'
							   OR tracks.artist LIKE '%" . addslashes($searchKey) . "%' 
							   OR tracks.title LIKE '%" . urlencode($searchKey) . "%'
							   OR tracks.title LIKE '%" . addslashes($searchKey) . "%' 
							   OR tracks.album LIKE '%" . urlencode($searchKey) . "%' 
							   OR tracks.album LIKE '%" . addslashes($searchKey) . "%' 
							   OR tracks.label LIKE '%" . addslashes($searchKey) . "%' 
							   OR tracks.label LIKE '%" . urlencode($searchKey) . "%' 
							   OR tracks.bpm like '%" . addslashes($searchKey) . "%')";
							 //  OR tracks_mp3s.version LIKE '%" . urlencode($searchKey) . "%'
							 //  OR tracks_mp3s.version LIKE '%" . addslashes($searchKey) . "%')";
					 
					 
				// 	 header("location: " . url("Member_dashboard_all_tracks").'?search=&searchKey='.$_GET['searchKey']);
				 }
		 
				 if (isset($_GET['search'])) {
		 
				// 	 if (isset($_GET['artist']) && strlen($_GET['artist']) > 0) {
				// 		 $output['searchArtist'] = $_GET['artist'];
				// 		 $whereItems[] = "tracks.artist = '" . urlencode($_GET['artist']) . "'";
				// 	 }
		 
				// 	 if (isset($_GET['title']) && strlen($_GET['title']) > 0) {
				// 		 $output['searchTitle'] = $_GET['title'];
				// 		 $whereItems[] = "tracks.title = '" . urlencode($_GET['title']) . "'";
				// 	 }
		 
				// 	 if (isset($_GET['label']) && strlen($_GET['label']) > 0) {
				// 		 $output['searchLabel'] = $_GET['label'];
				// 		 $whereItems[] = "tracks.label = '" . urlencode($_GET['label']) . "'";
				// 	 }
		 
				// 	 if (isset($_GET['album']) && strlen($_GET['album']) > 0) {
				// 		 $output['searchAlbum'] = $_GET['album'];
				// 		 $whereItems[] = "tracks.album = '" . urlencode($_GET['album']) . "'";
				// 	 }
		 
				// 	 if (isset($_GET['producer']) && strlen($_GET['producer']) > 0) {
				// 		 $output['searchProducer'] = $_GET['producer'];
				// 		 $whereItems[] = "tracks.producer = '" . $_GET['producer'] . "'";
				// 	 }
		 
				// 	 if (isset($_GET['client']) && strlen($_GET['client']) > 0) {
				// 		 $output['searchClient'] = $_GET['client'];
				// 		 $whereItems[] = "tracks.client = '" . $_GET['client'] . "'";
				// 	 }
				 }
		 
		 
				 if (count($whereItems) > 1) {
		 
					 $whereString = implode(' AND ', $whereItems);
					 $where .= $whereString;
				 } else if (count($whereItems) == 1) {
					 $where .= $whereItems[0];
				 } else {
					 $where =  '';
				 }
		 
		 
				 // generate sort
				if (!isset($_GET['sortBy']) && !isset($_GET['sortOrder'])) {
					$sortOrder = "tracks.order_position DESC, tracks.added DESC";
					$output['sortBy'] = '';
		            $output['sortOrder'] = '';
				 }else{
				  $sortOrder = "DESC";
				 //$sortBy = "";
				 $sortBy = "id";
				 $output['sortBy'] = 'added';
				 $output['sortOrder'] = 2;
				 }
		 
				 if (isset($_GET['sortBy']) && isset($_GET['sortOrder'])) {
					 $output['sortBy'] = $_GET['sortBy'];
					 $output['sortOrder'] = $_GET['sortOrder'];
		 
		 
					 if (strcmp($_GET['sortBy'], 'artist') == 0) {
		 
						 $sortBy = "artist";
					 } else if (strcmp($_GET['sortBy'], 'title') == 0) {
		 
						 $sortBy = "title";
					 } else if (strcmp($_GET['sortBy'], 'label') == 0) {
		 
						 $sortBy = "label";
					 } else if (strcmp($_GET['sortBy'], 'added') == 0) {
		 
						 $sortBy = "id";
					 } else if (strcmp($_GET['sortBy'], 'album') == 0) {
		 
						 $sortBy = "album";
					 } else if (strcmp($_GET['sortBy'], 'trackLength') == 0) {
		 
						 $sortBy = "time";
					 } else if (strcmp($_GET['sortBy'], 'producers') == 0) {
		 
						 $sortBy = "producer";
					 } else if (strcmp($_GET['sortBy'], 'client') == 0) {
		 
						 $sortBy = "client";
					 } else if (strcmp($_GET['sortBy'], 'paid') == 0) {
		 
						 $sortBy = "paid";
					 } else if (strcmp($_GET['sortBy'], 'invoiced') == 0) {
		 
						 $sortBy = "invoiced";
					 } else if (strcmp($_GET['sortBy'], 'graphicsCompleted') == 0) {
		 
						 $sortBy = "graphicscomplete";
					 }
		 
		 
					 if ($_GET['sortOrder'] == 1) {
		 
						 $sortOrder = "ASC";
					 } else  if ($_GET['sortOrder'] == 2) {
		 
						 $sortOrder = "DESC";
					 }
				 }
				 if(!isset($_GET['sortBy']) && !isset($_GET['sortOrder'])) {
					$sort =  $sortOrder; 
				 }else{
					$sort =  $sortBy . " " . $sortOrder;
				 }

		 
		 
				 // pagination
				 $limit = 10;
				 if (isset($_GET['records'])) {
					 $limit = $_GET['records'];
				 }
				 $output['numRecords'] = $limit;
		 
				 $start = 0;
				 $currentPageNo = 1;
		 
				 if (isset($_GET['page']) && $_GET['page'] > 1) {
					 $start = ($_GET['page'] * $limit) - $limit;
				 }

				
		 
		 
				 if (isset($_GET['page'])) {
					 $currentPageNo = $_GET['page'];
				 }

				
		 
				 $num_records = $this->memberAllDB_model->getNumTracks($where, $sort);

				//  dd($output);

				 $numPages = (int) ($num_records / $limit);
				 $reminder = ($num_records % $limit);
				 $output['num_records'] = $num_records;
		 
				 if ($reminder > 0) {
					 $numPages = $numPages + 1;
				 }
		 
				 $output['numPages'] = $numPages;
				 $output['start'] = $start;
				 $output['currentPageNo'] = $currentPageNo;
				 $output['currentPage'] = 'Member_dashboard_newest_tracks';
		 
				// dd($output);
		 
				 // generate url string
				 $getArray = array();
				 $urlString = '?';
				 if (isset($_GET)) {
					 foreach ($_GET as $key => $value) {
						 if (strcmp($key, 'page') != 0) {
		 
							 $getArray[] = $key . '=' . $value;
						 }
					 }
		 
		 
		 
					 if (count($getArray) > 1) {
						 $urlString .= implode('&', $getArray);
					 } else if (count($getArray) == 1) {
						 $urlString .= $getArray[0];
					 } else {
						 $urlString = '';
					 }
				 }
				 $output['urlString'] = $urlString;
				 // uncomment later
				 if (isset($_GET['page'])) {
		 
					 if (strlen($urlString) > 3) {
						 $param = '&';
					 } else {
						 $param = '?';
					 }
					 if ($_GET['page'] > $numPages && $numPages > 0) {
						 header("location: " . $output['currentPage'] . $urlString . $param . "page=" . $numPages);
						 exit;
					 }
					 /*else if ($_GET['page'] < 0) {
			  
				  header("location: ".$output['currentPage'].$urlString.$param."page=1");
				  exit;
			  }*/
				 }
		 
				 /*  if(isset($_GET['page'])) {
			  if ($_GET['page'] > $numPages) {
				  header("location: ".$output['currentPage']."?page=" . $numPages);
				  exit;
			  } else if ($_GET['page'] < 1) {
				  header("location: ".$output['currentPage']."?page=1");
				  exit;
			  }
		  }*/
		 
		 
		 
				 if ($currentPageNo == 1) {
					 $output['firstPageLink'] = 'disabled';
					 $output['preLink'] = 'disabled';
					 $output['nextLink'] = '';
					 $output['lastPageLink'] = '';
				 } else if ($currentPageNo == $numPages) {
					 $output['firstPageLink'] = '';
					 $output['preLink'] = '';
					 $output['nextLink'] = 'disabled';
					 $output['lastPageLink'] = 'disabled';
				 } else {
					 $output['firstPageLink'] = '';
					 $output['preLink'] = '';
					 $output['nextLink'] = '';
					 $output['lastPageLink'] = '';
				 }
				 // pagination ends
		 
				 $output['tracks'] = $this->memberAllDB_model->getNewestTracks($where, $sort, $start, $limit);
		 
		 
		 
				 foreach ($output['tracks']['data'] as $track) {
					 $output['memberReviews'] = $this->memberAllDB_model->getClientTrackReview_fem($track->id);
					 $output['downloads'][$track->id] = $output['memberReviews']['numRows'];
		 
					 $output['mp3s'][$track->id] = $this->memberAllDB_model->getTrackMp3s_fem($track->id);
				 }
				 
				  $arr1=$output['tracks']['data'];
				 $arr=json_decode(json_encode($arr1));
				 //pArr($arr);die('--Controller');
				// $arr= json_decode($arr1);
				 
				 foreach ($arr as $key=>$value){

				     $query_loc=$this->memberAllDB_model->getTrackMp3s_fem($value->id);
				    //  pArr($query_loc);
					$xx='';
				     foreach($query_loc['data'] as $key1=>$value1){
				        $xx= $value1->location;
				        
				     }
					 if(!empty($xx)){
				        $arr[$key]->location=$xx;
					 }
				   
				 }
				 $output['tracks']['data']=$arr;
		 
		 
				 // right side bar
				 $output['staffTracks'] = $this->memberAllDB_model->getStaffSelectedTracks_fem(0, 4);
		 
				 if ($output['staffTracks']['numRows'] > 0) {
					 foreach ($output['staffTracks']['data'] as $track) {
		 
						 $output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays_fem($track->id);
						 $row = $this->memberAllDB_model->getClientTrackReview_fem($track->id);
						 $output['reviews'][$track->id] = $row['numRows'];
		 
						 $output['mp3s'][$track->id] = $this->memberAllDB_model->getTrackMp3s_fem($track->id);
					 }
				 }
		 
		 
				 $output['youTracks'] = $this->memberAllDB_model->getYouSelectedTracks1(Session::get('memberId'), 0, 4);
		 
		 
				 if ($output['youTracks']['numRows'] > 0) {
					 foreach ($output['youTracks']['data'] as $track) {
		 
						 $output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays_fem($track->id);
						 $row = $this->memberAllDB_model->getClientTrackReview_fem($track->id);
						 $output['reviews'][$track->id] = $row['numRows'];
		 
						 $output['versions'][$track->id] = $this->memberAllDB_model->getTrackMp3s1_fem($track->id);
					 }
				 } else {
		 
					 $output['youTracks'] = $this->memberAllDB_model->getYouSelectedTracks_fem(0, 4);
		 
					 if ($output['youTracks']['numRows'] > 0) {
						 foreach ($output['youTracks']['data'] as $track) {
		 
							 $output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays_fem($track->id);
							 $row = $this->memberAllDB_model->getClientTrackReview_fem($track->id);
							 $output['reviews'][$track->id] = $row['numRows'];
		 
							 $output['versions'][$track->id] = $this->memberAllDB_model->getTrackMp3s_fem($track->id);
						 }
					 }
				 }
		 
				 /*
				 $output['youTracks'] = $this->memberAllDB_model->getYouSelectedTracks_fem(0,4); 
				 
				 if($output['youTracks']['numRows']>0)
				 {
				 foreach($output['youTracks']['data'] as $track)
				 {
				 
					$output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays_fem($track->id); 
					$row = $this->memberAllDB_model->getClientTrackReview_fem($track->id); 
					$output['reviews'][$track->id] = $row['numRows'];
					
					$output['mp3s'][$track->id] = $this->memberAllDB_model->getTrackMp3s_fem($track->id);
				  
				 }
				 }*/
		 
				 // footer data pass starts
				 $footerOutput['tracks'] = $this->memberAllDB_model->getMemberFooterTracks_fem();
				
				 // footer data pass ends here 
		 
				 $unReadmessages = $this->memberAllDB_model->getMemberUnreadInbox_fem(Session::get('memberId'));
				 $output['numMessages'] = $unReadmessages['numRows'];


				// header data pass starts "#moreHeaderData"
				// $headerOutput['numMessages'] = $unReadmessages['numRows'];
		 
				 // subscription status
				 $output['subscriptionStatus'] = 0;
				 $output['package'] = '';
				 $subscriptionInfo = $this->memberAllDB_model->getMemberSubscriptionStatus_fem(Session::get('memberId'));
				 if ($subscriptionInfo['numRows'] > 0) {
					 $output['subscriptionStatus'] = 1;
		 
					 if ($subscriptionInfo['data'][0]->package_Id == 1) {
						 $output['packageId'] = 1;
						 $output['package'] = 'Silver Subscription';
						 // $output['displayDashboard'] = 0;
		 
		 
					 } else if ($subscriptionInfo['data'][0]->package_Id == 2) {
						 $output['packageId'] = 2;
						 $output['package'] = 'Gold Subscription';
						 //$output['displayDashboard'] = 1;
		 
		 
					 } else if ($subscriptionInfo['data'][0]->package_Id == 3) {
						 $output['packageId'] = 3;
						 $output['package'] = 'Purple Subscription';
						 //$output['displayDashboard'] = 1;
		 
		 
					 }
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
		 
				 // echo '<pre/>'; print_r($output); exit;
		 
				// $output['tracks'] = $footerOutput['tracks'];
				// $output['banner_ads'] = $this->memberAllDB_model->getBannerads(2, 1);

				 // header data pass ends here and again continue at "#moreHeaderData"


				 $output['banner_ads'] = $this->memberAllDB_model->getBannerads(2, 2);


				//  $this->load->view('header_member_top.php', $headerOutput);
				//  $this->load->view('member_dashboard_newest_tracks.php', $output);
				//  $this->load->view('footer_member_top.php', $footerOutput);

				//dd($output);

				return view('members.dashboard.Member_dashboard_newest_tracks', $output);  
	}

	public function Member_track_review(Request $request)
	{


		// header data pass starts

		if(empty(Session::get('memberId'))){ 
			return redirect()->intended('login');
		}

		$output = array();

		$logo_data = array(
			'logo_id' => 1,
			);

		$logo_details = DB::table('website_logo')
		->where($logo_data)
		->first();  
		
		$get_logo = $logo_details->logo;

		 $output['pageTitle'] = 'Member Track Review';
		 $output['logo'] = $get_logo;
	   
		// fb logout link
	   //  $this->load->library('facebook');
	   //  $logout_link = url('Logout');

	   //  if (isset($_SESSION['fb_access_token'])) {
	   // 	 $logout_link = $this->facebook->logout_url();
	   //  }
	   //  $output['logout_link'] = $logout_link;


	   // header data pass ends here and again continue at "#moreHeaderData"

		// fb logout link

		 

		$output['mp3s'] = $this->memberAllDB_model->getTrackMp3s_fem($_GET['tid']);


/*
			if(isset($_SESSION['memberPackage']) && $_SESSION['memberPackage'] > 2)
			{
				redirect(site_url('Member_track_download?tid='.$_GET['tid']));
				exit;
			}
*/

		// add review

		if(isset($_POST['submitReview']))

		{
         // echo '<pre>';print_r($_POST);die;
		 $countryCode = '';

		  $countryName = '';



		  // get user location details:

		  //	function getLocationInfoByIp(){

			$client  = @$_SERVER['HTTP_CLIENT_IP'];



			$forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];



			$remote  = @$_SERVER['REMOTE_ADDR'];



			$result  = array('country'=>'', 'city'=>'');



			if(filter_var($client, FILTER_VALIDATE_IP)){



				$ip = $client;



			}elseif(filter_var($forward, FILTER_VALIDATE_IP)){



				$ip = $forward;



			}else{



				$ip = $remote;



			}



			$ip_data = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$ip));





			if($ip_data && $ip_data->geoplugin_countryName != null){



				$countryCode = $ip_data->geoplugin_countryCode;



				$countryName = $ip_data->geoplugin_countryName;



			}

		// }


					$result = $this->memberAllDB_model->addReview($_POST,$_GET['tid'],$countryName,$countryCode);



			if ($result > 0) {

				/* *Sending mail to client when member reviews the track login - ANGAD */

				$tID = $_GET['tid'];
				$memID = Session::get('memberId');
				$member_details = DB::table("members")->where("id", $memID)->first();
				$member_socialMed = DB::table("member_social_media")->where("memberId", $memID)->first();
				$track_details = DB::table("tracks")->where("id", $tID)->first();
				$client_details = DB::select("SELECT C.email, C.id, COALESCE(T.id, 0) AS track_id FROM clients AS C LEFT JOIN tracks AS T ON C.id = T.client WHERE T.id = $tID AND C.email IS NOT NULL;");

				$labelContactEmail = !empty($track_details->contact_email) ? urldecode($track_details->contact_email) : '';
				$labelContactEmailfeedback1 = !empty($track_details->feedback1_contact_email) ? urldecode($track_details->feedback1_contact_email) : '';
				$labelContactEmailfeedback2 = !empty($track_details->feedback2_contact_email) ? urldecode($track_details->feedback2_contact_email) : '';
				$labelContactEmailfeedback3 = !empty($track_details->feedback3_contact_email) ? urldecode($track_details->feedback3_contact_email) : '';

				$client_emailIs = !empty($client_details) ? urldecode($client_details[0]->email) : '';
				

				## $client_email = !empty($labelContactEmail) ? urldecode($labelContactEmail) : $client_emailIs;

				$client_email = array();

				if(!empty($labelContactEmail)){
					$client_email[] = $labelContactEmail;
				}
				
				if(!empty($labelContactEmailfeedback1)){
					$client_email[] = $labelContactEmailfeedback1;
				}
				if(!empty($labelContactEmailfeedback2)){
					$client_email[] = $labelContactEmailfeedback2;
				}
				if(!empty($labelContactEmailfeedback3)){
					$client_email[] = $labelContactEmailfeedback3;
				}

				if($_SERVER['REMOTE_ADDR'] == '122.185.217.118'){

					/*$client_email = array();
					$client_email[] = "info@digiwaxx.com"; //testing purpose*/ 
					

				}

				$client_email = array_filter($client_email);
				// echo '<pre>';print_r($client_email);die;
				if(!empty($client_email) && count($client_email) > 0 ) {

					$memChkIsDjMixer = 'No';
					$djMixerClubname = '';
					$memChkRadioStation = 'No';
					$djRadioStationname = '';
					$memChkPlaylistCon = 'No';

					$memName = $member_details->fname . " " . $member_details->lname;
					$memEmail = $member_details->email;
					$memStageName = $member_details->stagename;
					$memaddress1 = $member_details->address1;
					$memaddress2 = $member_details->address2;
					$memcity = $member_details->city;
					$memstate = $member_details->state;
					$memcountry = $member_details->country;
					$memzip = $member_details->zip;
					$memIsDjMixer = $member_details->dj_mixer;
					$memRadioStation = $member_details->radio_station;
					$memPlaylistCon = $member_details->playlist_contributor;

					$djsName = $member_details->fname;

					if(!empty($memStageName)){
						$djsName = $memStageName;
					}

					$memberTypes = array();
					$djMembrType = "";
					if ($member_details->dj_mixer == 1) {
						$memberTypes[] =  "DJ Mixer";
					}
					if ($member_details->radio_station == 1) {
						$memberTypes[] = "Radio Station";
					}
					if ($member_details->record_label == 1) {
						$memberTypes[] = "Record Label";
					}
					if ($member_details->management == 1) {
						$memberTypes[] = "Management";
					}
					if ($member_details->clothing_apparel == 1) {
						$memberTypes[] = "Clothing Apparel";
					}
					if ($member_details->mass_media == 1) {
						$memberTypes[] = "Mass Media";
					}
					if ($member_details->production_talent == 1) {
						$memberTypes[] = "Production Talent";
					}
					if ($member_details->promoter == 1) {
						$memberTypes[] = "Promoter";
					}
					if ($member_details->special_services == 1) {
						$memberTypes[] = "Special Services";
					}
					$countMemberTypes = count($memberTypes);

					if($countMemberTypes > 0) {
						$djMembrType = $memberTypes[0];
					}		

					if($memIsDjMixer == 1){

						$memChkIsDjMixer = 'Yes';

						$djMixerDetails = DB::table("members_dj_mixer")->where("member", $memID)->select('clubdj_clubname')->first();

						if(!empty($djMixerDetails->clubdj_clubname)){
							$djMixerClubname = urldecode($djMixerDetails->clubdj_clubname);
						}
					}

					if($memRadioStation == 1){

						$memChkRadioStation = 'Yes';

						$djRadioDetails = DB::table("members_radio_station")->where("member", $memID)->select('stationname')->first();

						if(!empty($djRadioDetails->stationname)){
							$djRadioStationname = urldecode($djRadioDetails->stationname);
						}
					}

					if($memPlaylistCon == 1){
						$memChkPlaylistCon = 'Yes';
					}

					$trackTitle = $track_details->title;
					$trackArtist = $track_details->artist;

					$fbLink = '';
					$twtLink = '';
					$instaLink = '';
					$linkedin = '';

				    // Regular expression patterns for popular social media platforms
				   
				    $fbPattern = '/^(https?:\/\/)?(www\.)?facebook\.com\/([a-zA-Z0-9_\-]+)\/?$/i';
				    $twtPattern = '/^(https?:\/\/)?(www\.)?twitter\.com\/([a-zA-Z0-9_]+)\/?$/i';
				    $instaPattern = '/^(https?:\/\/)?(www\.)?instagram\.com\/([a-zA-Z0-9_]+)\/?$/i';
				    $linkedinPattern = '/^(https?:\/\/)?(www\.)?linkedin\.com\/in\/([a-zA-Z0-9_\-]+)\/?$/i';
				 				

					## DJ social media info
					if(isset($member_socialMed->facebook)){
						$fbLink = $member_socialMed->facebook;

						if (preg_match($fbPattern, $fbLink, $matches)) {
						}else{
							$fbLink1 = trim($member_socialMed->facebook);
							if(!empty($fbLink1)){
							$fbLink = 'https://www.facebook.com/'.$fbLink1;
							}
						}
					}
					if(isset($member_socialMed->twitter)){
						$twtLink = $member_socialMed->twitter;
						if (preg_match($twtPattern, $twtLink, $matches)) {
						}else{
							$twtLink1 = trim($member_socialMed->twitter);
							if(!empty($twtLink1)){
							$twtLink = 'https://www.twitter.com/'.$twtLink1;
							}
						}						
					}
					if(isset($member_socialMed->instagram)){
						$instaLink = $member_socialMed->instagram;
						if (preg_match($instaPattern, $instaLink, $matches)) {
						}else{
							$instaLink1 = trim($member_socialMed->instagram);
							if(!empty($instaLink1)){
							$instaLink = 'https://www.instagram.com/'.$instaLink1;
							}
						}						
					}
					if(isset($member_socialMed->linkedin)){
						$linkedin = $member_socialMed->linkedin;					
						if (preg_match($linkedinPattern, $linkedin, $matches)) {
						}else{
							$linkedin1 = trim($member_socialMed->linkedin);
							if(!empty($linkedin1)){
							$linkedin = 'https://www.linkedin.com/in/'.$linkedin1;
							}
						}						
					}

					$parts = array_filter([$memcity, $memstate, $memcountry]);
					$memFullAddress = implode(', ', $parts);

					$appUrl =  env('APP_URL');

					$rating = $_POST['whatRate'];
					$review = $_POST['comments'];
					$data = array('appUrl' => $appUrl ,'djMemId' => $memID, 'userEmailId' => $memEmail,'memFname'=> $member_details->fname, 'name' => $memName, 'memstagename' => $memStageName, 'trackname' => $trackTitle, 'rating' => $rating, 'review' => $review, 'client_email' => $client_email, 'tArtist' => $trackArtist, 'memaddress' => $memFullAddress, 'fb' => $fbLink, 'twt' => $twtLink, 'insta' => $instaLink, 'linkedin' => $linkedin,'djPlaylistContributor'=>$memChkPlaylistCon,'djMixer'=>$memChkIsDjMixer, 'djMixerClubname'=>$djMixerClubname,'djRadioStation'=>$memChkRadioStation,'djRadioStationname'=>$djRadioStationname, 'djMembrTypeIs'=>$djMembrType, 'trackIdIs' => $tID, 'djsName' => $djsName);

					Mail::send('mails.members.reviewAddedNew', ['data' => $data], function ($message) use ($data) {
						$message->to($data['client_email']);
						$message->subject('Digiwaxx DJ Feedback from '.urldecode($data['djsName']) );
						$message->from('business@digiwaxx.com', 'Digiwaxx');
					});
				}

				/**End Login */

				header("location: " . url("Member_track_review/share?tid=" . $_GET['tid'] . "&reviewAdded=1"));
				exit;
			}else{
				header("location: ".url("Member_track_review?tid=".$_GET['tid']."&error=1"));   exit;
			}



		}
		
		// generate where

		$where = 'where ';

		$whereItems[] = "tracks.deleted = '0'";

		$whereItems[] = "tracks.id = '". $_GET['tid'] ."'";



		$output['searchArtist'] = '';

		$output['searchTitle'] = '';

		$output['searchLabel'] = '';

		$output['searchAlbum'] = '';

		$output['searchProducer'] = '';

		$output['searchClient'] = '';





		if(isset($_GET['search']))

		{



		  if(isset($_GET['artist']) && strlen($_GET['artist'])>0)

		   {

		     $output['searchArtist'] = $_GET['artist'];

			 $whereItems[] = "tracks.artist = '". urlencode($_GET['artist']) ."'";

		   }



		   if(isset($_GET['title']) && strlen($_GET['title'])>0)

		   {

		     $output['searchTitle'] = $_GET['title'];

			 $whereItems[] = "tracks.title = '". urlencode($_GET['title']) ."'";

		   }



		   if(isset($_GET['label']) && strlen($_GET['label'])>0)

		   {

		     $output['searchLabel'] = $_GET['label'];

			 $whereItems[] = "tracks.label = '". urlencode($_GET['label']) ."'";

		   }



		   if(isset($_GET['album']) && strlen($_GET['album'])>0)

		   {

		     $output['searchAlbum'] = $_GET['album'];

			 $whereItems[] = "tracks.album = '". urlencode($_GET['album']) ."'";

		   }



		   if(isset($_GET['producer']) && strlen($_GET['producer'])>0)

		   {

		     $output['searchProducer'] = $_GET['producer'];

			 $whereItems[] = "tracks.producer = '". $_GET['producer'] ."'";

		   }



		   if(isset($_GET['client']) && strlen($_GET['client'])>0)

		   {

		     $output['searchClient'] = $_GET['client'];

			 $whereItems[] = "tracks.client = '". $_GET['client'] ."'";

		   }













		}





		if(count($whereItems)>1)

		{



		   $whereString = implode(' AND ',$whereItems);

		   $where .= $whereString;

		}

		else if(count($whereItems)==1)

		{

		   $where .= $whereItems[0];

		}

		else

		{

		  $where =  '';

		}





		// generate sort

		$sortOrder = "ASC";

		$sortBy = "title";

		$output['sortBy'] = 'song';

		$output['sortOrder'] = 1;



		if(isset($_GET['sortBy']) && isset($_GET['sortOrder']))

		{

		   $output['sortBy'] = $_GET['sortBy'];

		   $output['sortOrder'] = $_GET['sortOrder'];





		   if(strcmp($_GET['sortBy'],'artist')==0)

		   {



			 $sortBy = "artist";

		   }

		   else if(strcmp($_GET['sortBy'],'song')==0)

		   {



			 $sortBy = "title";

		   }

		    else if(strcmp($_GET['sortBy'],'label')==0)

		   {



			 $sortBy = "label";

		   }

		   else if(strcmp($_GET['sortBy'],'date')==0)

		   {



			 $sortBy = "added";

		   }

		   else if(strcmp($_GET['sortBy'],'album')==0)

		   {



			 $sortBy = "album";

		   }

		   else if(strcmp($_GET['sortBy'],'bpm')==0)

		   {



			 $sortBy = "time";

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

		/*

		if(isset($_GET['page']) && $_GET['page']>1)

		 {

			$start = ($_GET['page']*$limit)-$limit;

		 }





		 if(isset($_GET['page']))

		 {

			$currentPageNo = $_GET['page'];

		 }



      $num_records = $this->memberAllDB_model->getNumTracks($where,$sort);

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

	 $output['currentPage'] = 'Member_dashboard_all_tracks';









 */





  // pagination





        // if track id is invalid, takes to dashboard page

		$output['tracks'] = $this->memberAllDB_model->getReviewTracks($where,$sort,$start,$limit);

		$output['mp3s'] = $this->memberAllDB_model->getTrackMp3s_fem($_GET['tid']);

		if($output['tracks']['numRows']<1)

		{



		  header("location: Member_dashboard_newest_tracks");

		  exit;

		}
		
		   
        $arr1=$output['tracks']['data'];
				 $arr=json_decode(json_encode($arr1));
				
				// $arr= json_decode($arr1);
				 
				 foreach ($arr as $key=>$value){

				     $query_loc=$this->memberAllDB_model->getTrackMp3s_fem($value->id);
				    //  pArr($query_loc);
					$xx='';
				     foreach($query_loc['data'] as $key1=>$value1){
				        $xx= $value1->location;
				        
				     }
					 if(!empty($xx)){
				     $arr[$key]->location=$xx;
					 }
				   
				 }
				 $output['tracks']['data']=$arr;
	


		// if review is already posted, takes to download page

		$output['memberReview'] = $this->memberAllDB_model->getClientTrackReview_fem($_GET['tid']);

		if($output['memberReview']['numRows']>0)

		{



		  header("location: Member_track_download_front_end?tid=".$_GET['tid']);

		  exit;

		}
// 		/my change---------------------------------------------------------------------

// 		else{
		    
// 		  header("location: Member_track_download_front_end?tid=".$_GET['tid']);

// 		  exit;
// 		}





	     $unReadmessages = $this->memberAllDB_model->getMemberUnreadInbox_fem(Session::get('memberId'));

		 $output['numMessages'] = $unReadmessages['numRows'];

		 $headerOutput['numMessages'] = $unReadmessages['numRows'];



	  // right side bar

		$output['staffTracks'] = $this->memberAllDB_model->getStaffSelectedTracks_fem(0,4);



		if($output['staffTracks']['numRows']>0)

		{

		foreach($output['staffTracks']['data'] as $track)

		{



		   $output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays_fem($track->id);

		   $row = $this->memberAllDB_model->getClientTrackReview_fem($track->id);

		   $output['reviews'][$track->id] = $row['numRows'];



		   $output['mp3s'][$track->id] = $this->memberAllDB_model->getTrackMp3s_fem($track->id);



		}

		}



		/*$output['youTracks'] = $this->memberAllDB_model->getYouSelectedTracks_fem(0,4);



		if($output['youTracks']['numRows']>0)

		{

		foreach($output['youTracks']['data'] as $track)

		{



		   $output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays_fem($track->id);

		   $row = $this->memberAllDB_model->getClientTrackReview_fem($track->id);

		   $output['reviews'][$track->id] = $row['numRows'];



		   $output['mp3s'][$track->id] = $this->memberAllDB_model->getTrackMp3s_fem($track->id);



		}

		} */



		$output['youTracks'] = $this->memberAllDB_model->getYouSelectedTracks1_fem(Session::get('memberId'),0,4);





		if($output['youTracks']['numRows']>0)

		{

		foreach($output['youTracks']['data'] as $track)

		{



		   $output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays_fem($track->id);

		   $row = $this->memberAllDB_model->getClientTrackReview_fem($track->id);

		   $output['reviews'][$track->id] = $row['numRows'];



		   $output['versions'][$track->id] = $this->memberAllDB_model->getTrackMp3s1_fem($track->id);

		}



		}

		else

		{



		$output['youTracks'] = $this->memberAllDB_model->getYouSelectedTracks_fem(0,4);



		if($output['youTracks']['numRows']>0)

		{

		foreach($output['youTracks']['data'] as $track)

		{



		   $output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays_fem($track->id);

		   $row = $this->memberAllDB_model->getClientTrackReview_fem($track->id);

		   $output['reviews'][$track->id] = $row['numRows'];



		   $output['versions'][$track->id] = $this->memberAllDB_model->getTrackMp3s_fem($track->id);

		}

		}

		}







			$footerOutput['tracks'] = $this->memberAllDB_model->getMemberFooterTracks_fem();







			 // subscription status

		 $output['subscriptionStatus'] = 0;

		 $output['package'] = '';

		 $subscriptionInfo = $this->memberAllDB_model->getMemberSubscriptionStatus_fem(Session::get('memberId'));

		 if($subscriptionInfo['numRows']>0)

		 {

		  $output['subscriptionStatus'] = 1;



		  if($subscriptionInfo['data'][0]->package_Id==1)

		  {

		    $output['packageId'] = 1;

			$output['package'] = 'Silver Subscription';

			// $output['displayDashboard'] = 0;





		  }

		  else if($subscriptionInfo['data'][0]->package_Id==2)

		  {

		    $output['packageId'] = 2;

		    $output['package'] = 'Gold Subscription';

			//$output['displayDashboard'] = 1;





		  }

		  else if($subscriptionInfo['data'][0]->package_Id==3)

		  {

		    $output['packageId'] = 3;

		    $output['package'] = 'Purple Subscription';

			//$output['displayDashboard'] = 1;





		  }

		}


        if(isset($output['tracks']['data'][0]->logos) && !empty($output['tracks']['data'][0]->logos) ){
        $output['logos'] = $this->memberAllDB_model->getLogos("WHERE id IN (".$output['tracks']['data'][0]->logos.") ORDER BY FIELD(id,".$output['tracks']['data'][0]->logos.")");
        }



		// $this->load->view('header_member.php',$headerOutput);

		// $this->load->view('member_track_review.php',$output);

		// $this->load->view('footer_member1.php',$footerOutput);


		//dd($output);
// 		pArr($output);
// 		die();
		return view('members.dashboard.member_track_review', $output); 


	}


	public function Member_dashboard_top_priority(Request $request)
	{


		// header data pass starts

		if(empty(Session::get('memberId'))){ 
			return redirect()->intended('login');
		}

		$output = array();
        $memID = Session::get('memberId');
            $query = DB::table('package_user_details')->select('package_id')->where('user_id','=',$memID)->where('user_type','=',1)->where('package_active',1)->get();
            $count = $query->count();
            if($count == 0 || $query[0]->package_id == 7){
                $output['member_package'] = 'Standard';
            }else{
                $output['member_package'] = 'Premium';
            }
		$logo_data = array(
			'logo_id' => 1,
			);

		$logo_details = DB::table('website_logo')
		->where($logo_data)
		->first();  
		
		$get_logo = $logo_details->logo;

		 $output['pageTitle'] = 'Member Dashboard Prority Tracks';
		 $output['logo'] = $get_logo;
		 $output['active'] = 'tracks';
	   
		// fb logout link
	   //  $this->load->library('facebook');
	   //  $logout_link = url('Logout');

	   //  if (isset($_SESSION['fb_access_token'])) {
	   // 	 $logout_link = $this->facebook->logout_url();
	   //  }
	   //  $output['logout_link'] = $logout_link;


	   // header data pass ends here and again continue at "#moreHeaderData"
	   

		// fb logout link
		// $this->load->library('facebook');
		// $logout_link = url('Logout');


		// if(isset($_SESSION['fb_access_token']))
		//  {
		//    $logout_link = $this->facebook->logout_url();
		//  }
		//  $headerOutput['logout_link'] = $logout_link;
		
		$tempMemberId = Session::get('tempMemberId');
		$memberId_from_session = Session::get('memberId');
		if(isset($tempMemberId))
		{
		  $output['welcomeMsg'] = 'Thank you for updating your information !';
		// remove session
		  Session::forget('tempMemberId');
		}


	   // save play record
	   if(isset($_GET['trackId']) && isset($_GET['recordPlay']) && isset($memberId_from_session))
	   {
		  $countryCode = '';
		  $countryName = '';


		// get user location details:
		// function getLocationInfoByIp(){

		$client  = @$_SERVER['HTTP_CLIENT_IP'];
		$forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
		$remote  = @$_SERVER['REMOTE_ADDR'];

		$result  = array('country'=>'', 'city'=>'');

		if(filter_var($client, FILTER_VALIDATE_IP)){
			$ip = $client;
		}elseif(filter_var($forward, FILTER_VALIDATE_IP)){
			$ip = $forward;
		}else{
			$ip = $remote;
		}

		$ip_data = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$ip));

		if($ip_data && $ip_data->geoplugin_countryName != null){
			$countryCode = $ip_data->geoplugin_countryCode;
			$countryName = $ip_data->geoplugin_countryName;
		}
		// }
				$this->memberAllDB_model->addTrackPlay_fem($_GET['trackId'],$memberId_from_session,$countryName,$countryCode);
				exit;
			}

			// generate where

	  $where = 'where ';
	  $whereItems[] = "tracks.deleted = '0'";
	  $whereItems[] = "tracks.active = '1'";
	  $whereItems[] = "tracks.status = 'publish'";
	  $whereItems[] = "tracks.priority = '1'";

	  $output['searchArtist'] = '';
	  $output['searchTitle'] = '';
	  $output['searchLabel'] = '';
	  $output['searchAlbum'] = '';
	  $output['searchProducer'] = '';
	  $output['searchClient'] = '';
	  $output['searchKey'] = '';

	  if(isset($_GET['search']))
	  {
		if(isset($_GET['genre']) && strlen($_GET['genre'])>0)
		 {
		   $output['searchGenre'] = $_GET['genre'];
		   $whereItems[] = "tracks.genre = '". $_GET['genre'] ."'";
		 }

		 if(isset($_GET['bpm']) && strlen($_GET['bpm'])>0)
		 {
		   $output['searchBpm'] = $_GET['bpm'];
		   $whereItems[] = "tracks_mp3s.bpm = '". $_GET['bpm'] ."'";
		 }

		 if(isset($_GET['version']) && strlen($_GET['version'])>0)
		 {
		   $output['searchVersion'] = $_GET['version'];
		   $whereItems[] = "tracks_mp3s.version like '%". $_GET['version'] ."%'";
		 }

		 if(isset($_GET['searchKey']) && strlen($_GET['searchKey'])>0)
		 {
		   $output['searchKey'] = $_GET['searchKey'];
		   $searchKey = urlencode(trim($_GET['searchKey']));

   $whereItems[] = "(tracks.artist like '%". $searchKey ."%' OR tracks.title like '%". $searchKey ."%' OR tracks.album like '%". $searchKey ."%' OR tracks.label like '%". $searchKey ."%' OR tracks.bpm like '%". $searchKey ."%')";
   //print_r($whereItems);
		 }
	  }

	  if(count($whereItems)>1)
	  {
		 $whereString = implode(' AND ',$whereItems);
		 $where .= $whereString;
	  }
	  else if(count($whereItems)==1)
	  {
		 $where .= $whereItems[0];
	  }
	  else
	  {
		$where =  '';
	  }

	  // generate sort

	  $sortOrder = "DESC";
	  $sortBy = "tracks.id";
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

		 else if(strcmp($_GET['sortBy'],'song')==0)
		 {
		   $sortBy = "title";
		 }
		  else if(strcmp($_GET['sortBy'],'label')==0)
		 {
		   $sortBy = "label";
		 }

		 else if(strcmp($_GET['sortBy'],'date')==0)
		 {
		   $sortBy = "tracks.added";
		 }
		 else if(strcmp($_GET['sortBy'],'album')==0)
		 {
		   $sortBy = "album";
		 }
		 else if(strcmp($_GET['sortBy'],'bpm')==0)
		 {
		   $sortBy = "tracks_mp3s.bpm";
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
	  if ($sortBy == "title") $sort = "id"." ".$sortOrder;
	  else $sort =  $sortBy." ".$sortOrder;

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

	$num_records = $this->memberAllDB_model->getNumDashboardTracks_fem($where,$sort);
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
   $output['currentPage'] = 'Member_dashboard_top_priority';

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
	  $output['tracks'] = $this->memberAllDB_model->getTopPriorityDashboardTracks($where,$sort,$start,$limit);
		if(!empty($output['tracks'])){

			foreach($output['tracks']['data'] as $track)
			{
			 $output['memberReviews'] = $this->memberAllDB_model->getClientTrackReview_fem($track->id);
			 $output['downloads'][$track->id] = $output['memberReviews']['numRows'];
			 $output['mp3s'][$track->id] = $this->memberAllDB_model->getTrackMp3s_fem($track->id);

			 if(!empty($track->client)){
			  $output['social'][$track->id] = $this->memberAllDB_model->getClientSocialInfo_fem($track->client);
			 }
			 else{

				$output['social'][$track->id] = '';

			 }
			}
			
				 $arr1=$output['tracks']['data'];
				 $arr=json_decode(json_encode($arr1));
				
				// $arr= json_decode($arr1);
				 
				 foreach ($arr as $key=>$value){

				     $query_loc=$this->memberAllDB_model->getTrackMp3s_fem($value->id);
				    //  pArr($query_loc);
					$xx='';
				     foreach($query_loc['data'] as $key1=>$value1){
				        $xx= $value1->location;
				        
				     }
					 if(!empty($xx)){
				     $arr[$key]->location=$xx;
					 }
				   
				 }
				 $output['tracks']['data']=$arr;
			
		}
// 		pArr($output);
// 		die();
	

// generate sort url sting

$sortExclude = array("sortOrder","sortBy","page");
$getSortArray = array(); $urlSortString = '?';

if(isset($_GET))
{
  foreach($_GET as $key=>$value)
   {
	 if(!(in_array($key,$sortExclude)))
	 {
	   $getSortArray[] = $key.'='.$value;
	 }
   }

  if(count($getSortArray)>1)
  {
	$urlSortString .= implode('&',$getSortArray);
  }
  else if(count($getSortArray)==1)
  {
	$urlSortString .= $getSortArray[0];
  }
  else
  {
	$urlSortString = '';
  }
}

$output['urlSortString'] = $urlSortString;

// generate search url sting

$searchExclude = array("bpm","version","genre","page","records");
$getSearchArray = array(); $urlSearchString = '?';

if(isset($_GET))
{
  foreach($_GET as $key=>$value)
   {
	 if(!(in_array($key,$searchExclude)))
	 {
	   $getSearchArray[] = $key.'='.$value;
	 }
   }

  if(count($getSearchArray)>1)
  {
	$urlSearchString .= implode('&',$getSearchArray);
  }
  else if(count($getSearchArray)==1)
  {
	$urlSearchString .= $getSearchArray[0];
  }
  else
  {
	$urlSearchString = '';
  }
}

$output['urlSearchString'] = $urlSearchString;

// generate record url sting

$recordExclude = array("records");
$getRecordArray = array(); $urlRecordString = '?';
if(isset($_GET))
{
  foreach($_GET as $key=>$value)
   {
	 if(!(in_array($key,$recordExclude)))
	 {
	   $getRecordArray[] = $key.'='.$value;
	 }
   }

  if(count($getRecordArray)>1)
  {
	$urlRecordString .= implode('&',$getRecordArray);
  }
  else if(count($getRecordArray)==1)
  {
	$urlRecordString .= $getRecordArray[0];
  }
  else
  {
	$urlRecordString = '';
  }
}

$output['urlRecordString'] = $urlRecordString;

// right side bar

$output['staffTracks'] = $this->memberAllDB_model->getStaffSelectedTracks_fem(0,4);
	  if($output['staffTracks']['numRows']>0)
	  {
	  foreach($output['staffTracks']['data'] as $track)
	  {
		 $output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays_fem($track->id);
		 $row = $this->memberAllDB_model->getClientTrackReview_fem($track->id);
		 $output['reviews'][$track->id] = $row['numRows'];
		 $output['mp3s'][$track->id] = $this->memberAllDB_model->getTrackMp3s_fem($track->id);
	  }
	  }

	  $output['youTracks'] = $this->memberAllDB_model->getYouSelectedTracks1_fem($memberId_from_session,0,4);
	  if($output['youTracks']['numRows']>0)
	  {
	  foreach($output['youTracks']['data'] as $track)
	  {
		 $output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays_fem($track->id);
		 $row = $this->memberAllDB_model->getClientTrackReview_fem($track->id);
		 $output['reviews'][$track->id] = $row['numRows'];

		// $output['versions'][$track->id] = $this->memberAllDB_model->getTrackMp3s1_fem($track->id,$track->version);

		 $output['versions'][$track->id] = $this->memberAllDB_model->getTrackMp3s1_fem($track->id);
	  }
	  }
	  else
	  {
	  $output['youTracks'] = $this->memberAllDB_model->getYouSelectedTracks_fem(0,4);
	  if($output['youTracks']['numRows']>0)
	  {
	  foreach($output['youTracks']['data'] as $track)
	  {
		 $output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays_fem($track->id);
		 $row = $this->memberAllDB_model->getClientTrackReview_fem($track->id);
		 $output['reviews'][$track->id] = $row['numRows'];
		 $output['versions'][$track->id] = $this->memberAllDB_model->getTrackMp3s_fem($track->id);
	  }
	  }
	  }

	 $footerOutput['tracks'] = $this->memberAllDB_model->getMemberFooterTracks_fem();

	   $unReadMessages = $this->memberAllDB_model->getMemberUnreadInbox_fem($memberId_from_session);
	   $output['numMessages']  = $unReadMessages['numRows'];
	  // $headerOutput['numMessages']  = $unReadMessages['numRows'];

	   // subscription status

	   $output['subscriptionStatus'] = 0;
	   $output['package'] = '';
	   $subscriptionInfo = $this->memberAllDB_model->getMemberSubscriptionStatus_fem($memberId_from_session);


	   if($subscriptionInfo['numRows']>0)
	   {
		$output['subscriptionStatus'] = 1;
		if($subscriptionInfo['data'][0]->package_Id==1)
		{
		  $output['packageId'] = 1;
		  $output['package'] = 'Silver Subscription';
		  // $output['displayDashboard'] = 0;
		}
		else if($subscriptionInfo['data'][0]->package_Id==2)
		{
		  $output['packageId'] = 2;
		  $output['package'] = 'Gold Subscription';
		  //$output['displayDashboard'] = 1;
		}
		else if($subscriptionInfo['data'][0]->package_Id==3)
		{
		  $output['packageId'] = 3;
		  $output['package'] = 'Purple Subscription';
		  //$output['displayDashboard'] = 1;
		}
	  }

	  //$output['tracks'] = $footerOutput['tracks'];
	 // $output['banner_ads'] = $this->memberAllDB_model->getBannerads(2,1);
	  $output['banner_ads'] = $this->memberAllDB_model->getBannerads(2,2);


	  $udata=$this->memberAllDB_model->getMemberDetails($memberId_from_session);
	  //submit terms and codition modal
	  if(!empty($_GET['frm'])){

		if($_GET['frm']=='termsaccpt'){
			$this->memberAllDB_model->updateMemberAge($memberId_from_session,$this->input->post('age'));
			echo "true";
		 }


	  }
	 else{

		$output['memeberinfo']=$udata;
		$output['sg_priority']=1;
		//   $this->load->view('header_member_top.php',$headerOutput);
		//   $this->load->view('member_dashboard_top_streaming.php',$output);
		//   $this->load->view('footer_member_top.php',$footerOutput);

	return view('members.dashboard.Member_dashboard_top_priority', $output); 
	  }


	}


	public function Member_dashboard_top_streaming(Request $request)
	{


		// header data pass starts

		if(empty(Session::get('memberId'))){ 
			return redirect()->intended('login');
		}

		$output = array();
        $memID = Session::get('memberId');
            $query = DB::table('package_user_details')->select('package_id')->where('user_id','=',$memID)->where('user_type','=',1)->where('package_active',1)->get();
            $count = $query->count();
            if($count == 0 || $query[0]->package_id == 7){
                $output['member_package'] = 'Standard';
            }else{
                $output['member_package'] = 'Premium';
            }
		$logo_data = array(
			'logo_id' => 1,
			);

		$logo_details = DB::table('website_logo')
		->where($logo_data)
		->first();  
		
		$get_logo = $logo_details->logo;

		 $output['pageTitle'] = 'Member Dashboard Streaming Tracks';
		 $output['logo'] = $get_logo;
		 $output['active'] = 'tracks';
	   
		// fb logout link
	   //  $this->load->library('facebook');
	   //  $logout_link = url('Logout');

	   //  if (isset($_SESSION['fb_access_token'])) {
	   // 	 $logout_link = $this->facebook->logout_url();
	   //  }
	   //  $output['logout_link'] = $logout_link;


	   // header data pass ends here and again continue at "#moreHeaderData"

		// fb logout link
		// $this->load->library('facebook');
		// $logout_link = url('Logout');


		// if(isset($_SESSION['fb_access_token']))
		//  {
		//    $logout_link = $this->facebook->logout_url();
		//  }
		//  $headerOutput['logout_link'] = $logout_link;


		$tempMemberId = Session::get('tempMemberId');
		$memberId_from_session = Session::get('memberId');
		if(isset($tempMemberId))
		{
		  $output['welcomeMsg'] = 'Thank you for updating your information !';
		// remove session
		  Session::forget('tempMemberId');
		}

	   // save play record
	   if(isset($_GET['trackId']) && isset($_GET['recordPlay']) && isset($memberId_from_session))
	   {
		  $countryCode = '';
		  $countryName = '';


		// get user location details:
		// function getLocationInfoByIp(){

		$client  = @$_SERVER['HTTP_CLIENT_IP'];
		$forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
		$remote  = @$_SERVER['REMOTE_ADDR'];

		$result  = array('country'=>'', 'city'=>'');

		if(filter_var($client, FILTER_VALIDATE_IP)){
			$ip = $client;
		}elseif(filter_var($forward, FILTER_VALIDATE_IP)){
			$ip = $forward;
		}else{
			$ip = $remote;
		}

		$ip_data = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$ip));

		if($ip_data && $ip_data->geoplugin_countryName != null){
			$countryCode = $ip_data->geoplugin_countryCode;
			$countryName = $ip_data->geoplugin_countryName;
		}
		// }
				$this->memberAllDB_model->addTrackPlay_fem($_GET['trackId'],$memberId_from_session,$countryName,$countryCode);
				exit;
			}

			// generate where

			$where = 'where ';
			$whereItems[] = "tracks.deleted = '0'";
			$whereItems[] = "tracks.active = '1'";
			$whereItems[] = "tracks.status = 'publish'";

			$output['searchArtist'] = '';
			$output['searchTitle'] = '';
			$output['searchLabel'] = '';
			$output['searchAlbum'] = '';
			$output['searchProducer'] = '';
			$output['searchClient'] = '';
			$output['searchKey'] = '';

			if(isset($_GET['search']))
			{
				if(isset($_GET['genre']) && strlen($_GET['genre'])>0)
				{
				$output['searchGenre'] = $_GET['genre'];
				$whereItems[] = "tracks.genre = '". $_GET['genre'] ."'";
				}

				if(isset($_GET['bpm']) && strlen($_GET['bpm'])>0)
				{
				$output['searchBpm'] = $_GET['bpm'];
				$whereItems[] = "tracks_mp3s.bpm = '". $_GET['bpm'] ."'";
				}

				if(isset($_GET['version']) && strlen($_GET['version'])>0)
				{
				$output['searchVersion'] = $_GET['version'];
				$whereItems[] = "tracks_mp3s.version like '%". $_GET['version'] ."%'";
				}

				if(isset($_GET['searchKey']) && strlen($_GET['searchKey'])>0)
				{
				$output['searchKey'] = $_GET['searchKey'];
				$searchKey = urlencode(trim($_GET['searchKey']));
                //   $searchKey=trim($_GET['searchKey']);
		$whereItems[] = "(tracks.artist like '%". $searchKey ."%' OR tracks.title like '%". $searchKey ."%' OR tracks.album like '%". $searchKey ."%' OR tracks.label like '%". $searchKey ."%' OR tracks.bpm like '%". $searchKey ."%')";
		//print_r($whereItems);
				}
			}

			if(count($whereItems)>1)
			{
				$whereString = implode(' AND ',$whereItems);
				$where .= $whereString;
			}
			else if(count($whereItems)==1)
			{
				$where .= $whereItems[0];
			}
			else
			{
				$where =  '';
			}

			// generate sort
			
			if (!isset($_GET['sortBy']) && !isset($_GET['sortOrder'])) {
				$sortOrder = "tracks.order_position DESC, tracks.added DESC";
				$output['sortBy'] = '';
	            $output['sortOrder'] = '';
			 }else{
			  $sortOrder = "DESC";
			 $sortBy = "tracks.id";
			 $output['sortBy'] = 'added';
			 $output['sortOrder'] = 2;
			 }

			if(isset($_GET['sortBy']) && isset($_GET['sortOrder']))
			{
				$output['sortBy'] = $_GET['sortBy'];
				$output['sortOrder'] = $_GET['sortOrder'];

				if(strcmp($_GET['sortBy'],'artist')==0)
				{
				$sortBy = "artist";
				}

				else if(strcmp($_GET['sortBy'],'song')==0)
				{
				$sortBy = "title";
				}
				else if(strcmp($_GET['sortBy'],'label')==0)
				{
				$sortBy = "label";
				}

				else if(strcmp($_GET['sortBy'],'date')==0)
				{
				$sortBy = "tracks.added";
				}
				else if(strcmp($_GET['sortBy'],'album')==0)
				{
				$sortBy = "album";
				}
				else if(strcmp($_GET['sortBy'],'bpm')==0)
				{
				$sortBy = "tracks_mp3s.bpm";
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
			
              if (!isset($_GET['sortBy']) && !isset($_GET['sortOrder'])) {
        		  $sort =  $sortOrder; 
        	  }else{
        	  
        	   if ($sortBy == "title") $sort = "id" . " " . $sortOrder;
        	   else $sort =  $sortBy . " " . $sortOrder;
        	   
        	  }

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

			$num_records = $this->memberAllDB_model->getNumDashboardTracks_fem($where,$sort);
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
		$output['currentPage'] = 'Member_dashboard_top_streaming';

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
		
		     //if($_SERVER['REMOTE_ADDR'] = '2401:4900:1c2b:dd15:a525:866a:9558:73d5'){
                // //  echo $where;
                // $x= $_GET['searchKey'];
                // $y=trim($x);
                // $z=urlencode($y);
                // echo $z;
                
                //  die();
             //}



		
			$output['tracks'] = $this->memberAllDB_model->getTopStreamingDashboardTracks($where,$sort,$start,$limit);

			foreach($output['tracks']['data'] as $track)
			{
			$output['memberReviews'] = $this->memberAllDB_model->getClientTrackReview_fem($track->id);
			$output['downloads'][$track->id] = $output['memberReviews']['numRows'];
			$output['mp3s'][$track->id] = $this->memberAllDB_model->getTrackMp3s_fem($track->id);
			//$output['social'][$track->id] = $this->memberAllDB_model->getClientSocialInfo_fem($track->client);

			if(!empty($track->client)){
				$output['social'][$track->id] = $this->memberAllDB_model->getClientSocialInfo_fem($track->client);
			   }
			   else{
  
				  $output['social'][$track->id] = '';
  
			   }
			}
			
				 $arr1=$output['tracks']['data'];
				 $arr=json_decode(json_encode($arr1));
				
				// $arr= json_decode($arr1);
				 
				 foreach ($arr as $key=>$value){

				     $query_loc=$this->memberAllDB_model->getTrackMp3s_fem($value->id);
				    //  pArr($query_loc);
					$xx='';
				     foreach($query_loc['data'] as $key1=>$value1){
				        $xx= $value1->location;
				        
				     }
					 if(!empty($xx)){
				     $arr[$key]->location=$xx;
					 }
				   
				 }
				 $output['tracks']['data']=$arr;

		// generate sort url sting

		$sortExclude = array("sortOrder","sortBy","page");
		$getSortArray = array(); $urlSortString = '?';

		if(isset($_GET))
		{
		foreach($_GET as $key=>$value)
		{
			if(!(in_array($key,$sortExclude)))
			{
			$getSortArray[] = $key.'='.$value;
			}
		}

		if(count($getSortArray)>1)
		{
			$urlSortString .= implode('&',$getSortArray);
		}
		else if(count($getSortArray)==1)
		{
			$urlSortString .= $getSortArray[0];
		}
		else
		{
			$urlSortString = '';
		}
		}

		$output['urlSortString'] = $urlSortString;

		// generate search url sting

		$searchExclude = array("bpm","version","genre","page","records");
		$getSearchArray = array(); $urlSearchString = '?';

		if(isset($_GET))
		{
		foreach($_GET as $key=>$value)
		{
			if(!(in_array($key,$searchExclude)))
			{
			$getSearchArray[] = $key.'='.$value;
			}
		}

		if(count($getSearchArray)>1)
		{
			$urlSearchString .= implode('&',$getSearchArray);
		}
		else if(count($getSearchArray)==1)
		{
			$urlSearchString .= $getSearchArray[0];
		}
		else
		{
			$urlSearchString = '';
		}
		}

		$output['urlSearchString'] = $urlSearchString;

		// generate record url sting

		$recordExclude = array("records");
		$getRecordArray = array(); $urlRecordString = '?';
		if(isset($_GET))
		{
		foreach($_GET as $key=>$value)
		{
			if(!(in_array($key,$recordExclude)))
			{
			$getRecordArray[] = $key.'='.$value;
			}
		}

		if(count($getRecordArray)>1)
		{
			$urlRecordString .= implode('&',$getRecordArray);
		}
		else if(count($getRecordArray)==1)
		{
			$urlRecordString .= $getRecordArray[0];
		}
		else
		{
			$urlRecordString = '';
		}
		}

		$output['urlRecordString'] = $urlRecordString;

		// right side bar

		$output['staffTracks'] = $this->memberAllDB_model->getStaffSelectedTracks_fem(0,4);
			if($output['staffTracks']['numRows']>0)
			{
			foreach($output['staffTracks']['data'] as $track)
			{
				$output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays_fem($track->id);
				$row = $this->memberAllDB_model->getClientTrackReview_fem($track->id);
				$output['reviews'][$track->id] = $row['numRows'];
				$output['mp3s'][$track->id] = $this->memberAllDB_model->getTrackMp3s_fem($track->id);
			}
			}

			$output['youTracks'] = $this->memberAllDB_model->getYouSelectedTracks1_fem($memberId_from_session,0,4);
			if($output['youTracks']['numRows']>0)
			{
			foreach($output['youTracks']['data'] as $track)
			{
				$output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays_fem($track->id);
				$row = $this->memberAllDB_model->getClientTrackReview_fem($track->id);
				$output['reviews'][$track->id] = $row['numRows'];

				// $output['versions'][$track->id] = $this->memberAllDB_model->getTrackMp3s1_fem($track->id,$track->version);

				$output['versions'][$track->id] = $this->memberAllDB_model->getTrackMp3s1_fem($track->id);
			}
			}
			else
			{
			$output['youTracks'] = $this->memberAllDB_model->getYouSelectedTracks_fem(0,4);
			if($output['youTracks']['numRows']>0)
			{
			foreach($output['youTracks']['data'] as $track)
			{
				$output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays_fem($track->id);
				$row = $this->memberAllDB_model->getClientTrackReview_fem($track->id);
				$output['reviews'][$track->id] = $row['numRows'];
				$output['versions'][$track->id] = $this->memberAllDB_model->getTrackMp3s_fem($track->id);
			}
			}
			}

			$footerOutput['tracks'] = $this->memberAllDB_model->getMemberFooterTracks_fem();

			$unReadMessages = $this->memberAllDB_model->getMemberUnreadInbox_fem($memberId_from_session);
			$output['numMessages']  = $unReadMessages['numRows'];
			//$headerOutput['numMessages']  = $unReadMessages['numRows'];

			// subscription status

			$output['subscriptionStatus'] = 0;
			$output['package'] = '';
			$subscriptionInfo = $this->memberAllDB_model->getMemberSubscriptionStatus_fem($memberId_from_session);


			if($subscriptionInfo['numRows']>0)
			{
				$output['subscriptionStatus'] = 1;
				if($subscriptionInfo['data'][0]->package_Id==1)
				{
				$output['packageId'] = 1;
				$output['package'] = 'Silver Subscription';
				// $output['displayDashboard'] = 0;
				}
				else if($subscriptionInfo['data'][0]->package_Id==2)
				{
				$output['packageId'] = 2;
				$output['package'] = 'Gold Subscription';
				//$output['displayDashboard'] = 1;
				}
				else if($subscriptionInfo['data'][0]->package_Id==3)
				{
				$output['packageId'] = 3;
				$output['package'] = 'Purple Subscription';
				//$headerOutput['displayDashboard'] = 1;
				}
			}

			$headerOutput['tracks'] = $footerOutput['tracks'];
			$headerOutput['banner_ads'] = $this->memberAllDB_model->getBannerads(2,1);
			$output['banner_ads'] = $this->memberAllDB_model->getBannerads(2,2);


			$udata=$this->memberAllDB_model->getMemberDetails($memberId_from_session);
			
			
			$output['sg_topstrem']=1;
			
			
			//submit terms and codition modal


			if(!empty($_GET['frm'])){

				if($_GET['frm']=='termsaccpt'){
					$this->memberAllDB_model->updateMemberAge($memberId_from_session,$this->input->post('age'));
					echo "true";
				 }
		
		
			}	
			
			else{
			$output['memeberinfo']=$udata;

			//   $this->load->view('header_member_top.php',$headerOutput);
			//   $this->load->view('member_dashboard_top_streaming.php',$output);
			//   $this->load->view('footer_member_top.php',$footerOutput);

			return view('members.dashboard.member_dashboard_top_streaming', $output); 
			}

	

	}


	public function Member_track_label(Request $request)
	{


		// header data pass starts

		if(empty(Session::get('memberId'))){ 
			return redirect()->intended('login');
		}

		$output = array();

		$logo_data = array(
			'logo_id' => 1,
			);

		$logo_details = DB::table('website_logo')
		->where($logo_data)
		->first();  
		
		$get_logo = $logo_details->logo;

		 $output['pageTitle'] = 'Member Dashboard Streaming Tracks';
		 $output['logo'] = $get_logo;
	   
		// fb logout link
	   //  $this->load->library('facebook');
	   //  $logout_link = url('Logout');

	   //  if (isset($_SESSION['fb_access_token'])) {
	   // 	 $logout_link = $this->facebook->logout_url();
	   //  }
	   //  $output['logout_link'] = $logout_link;


	   // header data pass ends here and again continue at "#moreHeaderData"

	   $tempMemberId = Session::get('tempMemberId');
	   $memberId_from_session = Session::get('memberId');
	   if(isset($tempMemberId))
	   {
		 $output['welcomeMsg'] = 'Thank you for updating your information !';
	   // remove session
		 Session::forget('tempMemberId');
	   }
	   
	   // save play record

	   if(isset($_GET['trackId']) && isset($_GET['recordPlay']) && isset($memberId_from_session))

	   {

		 

		  $countryCode = ''; 

		$countryName = '';		

		

		// get user location details:

		//	function getLocationInfoByIp(){

  $client  = @$_SERVER['HTTP_CLIENT_IP'];



  $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];



  $remote  = @$_SERVER['REMOTE_ADDR'];



  $result  = array('country'=>'', 'city'=>'');



  if(filter_var($client, FILTER_VALIDATE_IP)){



	  $ip = $client;



  }elseif(filter_var($forward, FILTER_VALIDATE_IP)){



	  $ip = $forward;



  }else{



	  $ip = $remote;



  }



  $ip_data = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$ip));  

  

  

  if($ip_data && $ip_data->geoplugin_countryName != null){



	  $countryCode = $ip_data->geoplugin_countryCode;



	  $countryName = $ip_data->geoplugin_countryName;



  }

// }

	  

		 $this->memberAllDB_model->addTrackPlay_fem($_GET['trackId'],$memberId_from_session,$countryName,$countryCode); 

		 exit;

	   }

	  

	 

	   

	  // generate where

	  $where = 'where ';

	  $whereItems[] = "tracks.deleted = '0'";

	  $whereItems[] = "tracks.active = '1'";

	  

	  $output['searchArtist'] = '';

	  $output['searchTitle'] = '';

	  $output['searchLabel'] = '';

	  $output['searchAlbum'] = '';

	  $output['searchProducer'] = '';

	  $output['searchClient'] = '';

	  $output['searchKey'] = '';

	  

	  

	  $whereItems[] = "tracks.label like '%". urlencode($_GET['label']) ."%'";

	   

	  if(isset($_GET['search']))

	  {

		 

		if(isset($_GET['genre']) && strlen($_GET['genre'])>0)

		 {

		   $output['searchGenre'] = $_GET['genre'];

		   $whereItems[] = "tracks.genre = '". $_GET['genre'] ."'";

		 }

		 

		 if(isset($_GET['bpm']) && strlen($_GET['bpm'])>0)

		 {

		   $output['searchBpm'] = $_GET['bpm'];

		   $whereItems[] = "tracks_mp3s.bpm = '". $_GET['bpm'] ."'";

		 }

		 

		 if(isset($_GET['version']) && strlen($_GET['version'])>0)

		 {

		   $output['searchVersion'] = $_GET['version'];

		   $whereItems[] = "tracks_mp3s.version like '%". $_GET['version'] ."%'";

		 }

		 

		 if(isset($_GET['searchKey']) && strlen($_GET['searchKey'])>0)

		 {

		   $output['searchKey'] = $_GET['searchKey'];

		   $searchKey = urlencode(trim($_GET['searchKey']));

   $whereItems[] = "(tracks.artist like '%". $searchKey ."%' OR tracks.title like '%". $searchKey ."%' OR tracks.album like '%". $searchKey ."%' OR tracks.label like '%". $searchKey ."%' OR tracks.bpm like '%". $searchKey ."%')";

		 }

		 

	  

	  

	  }

	  

	  

	  

	  

	  

	  if(count($whereItems)>1)

	  {

	  

		 $whereString = implode(' AND ',$whereItems);

		 $where .= $whereString;

	  }

	  else if(count($whereItems)==1)

	  {

		 $where .= $whereItems[0];

	  }

	  else

	  {

		$where =  '';

	  }

	  

	  

	  // generate sort

	  $sortOrder = "ASC";

	  $sortBy = "tracks.title";

	  $output['sortBy'] = 'song';

	  $output['sortOrder'] = 1;

	  

	  if(isset($_GET['sortBy']) && isset($_GET['sortOrder']))

	  {

		 $output['sortBy'] = $_GET['sortBy'];

		 $output['sortOrder'] = $_GET['sortOrder'];

		 

		 

		 if(strcmp($_GET['sortBy'],'artist')==0)

		 {

		  

		   $sortBy = "artist";

		 }

		 else if(strcmp($_GET['sortBy'],'song')==0)

		 {

		  

		   $sortBy = "title";

		 }

		  else if(strcmp($_GET['sortBy'],'label')==0)

		 {

		  

		   $sortBy = "label";

		 }

		 else if(strcmp($_GET['sortBy'],'date')==0)

		 {

		  

		   $sortBy = "tracks.added";

		 }

		 else if(strcmp($_GET['sortBy'],'album')==0)

		 {

		  

		   $sortBy = "album";

		 }

		 else if(strcmp($_GET['sortBy'],'bpm')==0)

		 {

		  

		   $sortBy = "tracks_mp3s.bpm";

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

  

  

  
	   
  if(Session::get('memberPackage')<2)

  {

	 $num_records = 20;

  }

  else

  {

	 $num_records = $this->memberAllDB_model->getNumDashboardTracks_fem($where,$sort); 

  }

  

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

   $output['currentPage'] = 'Member_track_label';

   

   

   

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

		

	  $output['tracks'] = $this->memberAllDB_model->getDashboardTracks_fem($where,$sort,$start,$limit); 

	  



foreach($output['tracks']['data'] as $track)

	  {

	   $output['memberReviews'] = $this->memberAllDB_model->getClientTrackReview_fem($track->id); 

	   $output['downloads'][$track->id] = $output['memberReviews']['numRows'];

	   

	   $output['mp3s'][$track->id] = $this->memberAllDB_model->getTrackMp3s_fem($track->id);

	   

	  }

		





// generate sort url sting

$sortExclude = array("sortOrder","sortBy","page");

$getSortArray = array(); $urlSortString = '?';

if(isset($_GET))

{

  foreach($_GET as $key=>$value)

   {

	 if(!(in_array($key,$sortExclude)))

	 {

	   

	   $getSortArray[] = $key.'='.$value;

	  

	 }

   }

  

  

  

  if(count($getSortArray)>1)

  {

	$urlSortString .= implode('&',$getSortArray);

  }

  else if(count($getSortArray)==1)

  {

	$urlSortString .= $getSortArray[0];

  }

  else

  {

	$urlSortString = '';

  }

}

$output['urlSortString'] = $urlSortString; 	



// generate search url sting

$searchExclude = array("bpm","version","genre","page","records");

$getSearchArray = array(); $urlSearchString = '?';

if(isset($_GET))

{

  foreach($_GET as $key=>$value)

   {

  

	

	 if(!(in_array($key,$searchExclude)))

	 {

	   

	   $getSearchArray[] = $key.'='.$value;

	  

	 }

   }

  

  if(count($getSearchArray)>1)

  {

	$urlSearchString .= implode('&',$getSearchArray);

  }

  else if(count($getSearchArray)==1)

  {

	$urlSearchString .= $getSearchArray[0];

  }

  else

  {

	$urlSearchString = '';

  }

}		

$output['urlSearchString'] = $urlSearchString; 



// generate record url sting

$recordExclude = array("records");

$getRecordArray = array(); $urlRecordString = '?';

if(isset($_GET))

{

  foreach($_GET as $key=>$value)

   {

  

	

	 if(!(in_array($key,$recordExclude)))

	 {

	   

	   $getRecordArray[] = $key.'='.$value;

	  

	 }

   }

  

  if(count($getRecordArray)>1)

  {

	$urlRecordString .= implode('&',$getRecordArray);

  }

  else if(count($getRecordArray)==1)

  {

	$urlRecordString .= $getRecordArray[0];

  }

  else

  {

	$urlRecordString = '';

  }

}		

$output['urlRecordString'] = $urlRecordString; 







// right side bar

$output['staffTracks'] = $this->memberAllDB_model->getStaffSelectedTracks_fem(0,4); 

	  

	  if($output['staffTracks']['numRows']>0)

	  {

	  foreach($output['staffTracks']['data'] as $track)

	  {

	  

		 $output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays_fem($track->id); 

		 $row = $this->memberAllDB_model->getClientTrackReview_fem($track->id); 

		 $output['reviews'][$track->id] = $row['numRows'];

		 

		 $output['mp3s'][$track->id] = $this->memberAllDB_model->getTrackMp3s_fem($track->id);

	   

	  }

	  }

	  

	  

	  $output['youTracks'] = $this->memberAllDB_model->getYouSelectedTracks1_fem($memberId_from_session,0,4); 

	  

	  

	  if($output['youTracks']['numRows']>0)

	  {

	  foreach($output['youTracks']['data'] as $track)

	  {

	  

		 $output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays_fem($track->id); 

		 $row = $this->memberAllDB_model->getClientTrackReview_fem($track->id); 

		 $output['reviews'][$track->id] = $row['numRows'];

		 

		// $output['versions'][$track->id] = $this->memberAllDB_model->getTrackMp3s1_fem($track->id,$track->version);

		 $output['versions'][$track->id] = $this->memberAllDB_model->getTrackMp3s1_fem($track->id);

	  }

	  

	  }

	  else

	  {

	  

	  $output['youTracks'] = $this->memberAllDB_model->getYouSelectedTracks_fem(0,4); 

	  

	  if($output['youTracks']['numRows']>0)

	  {

	  foreach($output['youTracks']['data'] as $track)

	  {

	  

		 $output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays_fem($track->id); 

		 $row = $this->memberAllDB_model->getClientTrackReview_fem($track->id); 

		 $output['reviews'][$track->id] = $row['numRows'];

		 

		 $output['versions'][$track->id] = $this->memberAllDB_model->getTrackMp3s_fem($track->id);

	  }

	  }

	  }

	  

	  /*$output['youTracks'] = $this->memberAllDB_model->getYouSelectedTracks_fem(0,4); 

	  

	  if($output['youTracks']['numRows']>0)

	  {

	  foreach($output['youTracks']['data'] as $track)

	  {

	  

		 $output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays_fem($track->id); 

		 $row = $this->memberAllDB_model->getClientTrackReview_fem($track->id); 

		 $output['reviews'][$track->id] = $row['numRows'];

		 

		 $output['mp3s'][$track->id] = $this->memberAllDB_model->getTrackMp3s_fem($track->id);

	   

	  }

	  }*/



   $footerOutput['tracks'] = $this->memberAllDB_model->getMemberFooterTracks_fem(); 

   

   

	

	   $unReadMessages = $this->memberAllDB_model->getMemberUnreadInbox_fem($memberId_from_session); 

	   $output['numMessages']  = $unReadMessages['numRows'];

	   $headerOutput['numMessages']  = $unReadMessages['numRows'];

	   

	   

	   // subscription status

	   $output['subscriptionStatus'] = 0;

	   $output['package'] = '';

	   $subscriptionInfo = $this->memberAllDB_model->getMemberSubscriptionStatus_fem($memberId_from_session); 

	   

  

	   if($subscriptionInfo['numRows']>0)

	   {

		$output['subscriptionStatus'] = 1;

		 

		if($subscriptionInfo['data'][0]->package_Id==1)

		{

		  $output['packageId'] = 1;

		  $output['package'] = 'Silver Subscription';

		  // $output['displayDashboard'] = 0;

		  

		  

		}

		else if($subscriptionInfo['data'][0]->package_Id==2)

		{

		  $output['packageId'] = 2;

		  $output['package'] = 'Gold Subscription';

		  //$output['displayDashboard'] = 1;

		  

		  

		}

		else if($subscriptionInfo['data'][0]->package_Id==3)

		{

		  $output['packageId'] = 3;

		  $output['package'] = 'Purple Subscription';

		  //$output['displayDashboard'] = 1;

		  

		  

		}

	  }

				  

	//   $this->load->view('header_member.php',$headerOutput);

	//   $this->load->view('member_track_label.php',$output);

	//   $this->load->view('footer_member.php',$footerOutput);



	   return view('members.dashboard.member_track_label', $output);


	}


	public function Member_send_message(Request $request)
	{


		// header data pass starts

		if(empty(Session::get('memberId'))){ 
			return redirect()->intended('login');
		}

		$output = array();

		$logo_data = array(
			'logo_id' => 1,
			);

		$logo_details = DB::table('website_logo')
		->where($logo_data)
		->first();  
		
		$get_logo = $logo_details->logo;

		 $output['pageTitle'] = 'Member Dashboard Streaming Tracks';
		 $output['logo'] = $get_logo;
	   
		// fb logout link
	   //  $this->load->library('facebook');
	   //  $logout_link = url('Logout');

	   //  if (isset($_SESSION['fb_access_token'])) {
	   // 	 $logout_link = $this->facebook->logout_url();
	   //  }
	   //  $output['logout_link'] = $logout_link;


	   // header data pass ends here and again continue at "#moreHeaderData"

	
		date_default_timezone_set('America/Los_Angeles');
		

	   if(!(isset($_GET['cid'])))

	   {

		header("location: ".url("Member_messages"));   exit;

	   }

	   

	   $output['archiveMsgs'] = array();

	   $output['starMsgs'] = array();

	   $memberId_from_session = Session::get('memberId');

	   

	   // ajax response to load new msgs

		if(isset($_GET['getConversation']) && isset($_GET['cid']))

		{

		$output['conversation'] = $this->memberAllDB_model->getConversation($memberId_from_session,$_GET['cid']); 

	   

	   if(isset($output['conversation']['numRows']) && $output['conversation']['numRows']>0)

	   {

	   

		 foreach($output['conversation']['data'] as $message)

		 {

		 

			$clientStarred = $this->memberAllDB_model->isMemberStarred($memberId_from_session,$message->messageId); 

			if($clientStarred>0)

			{ $output['starMsgs'][] = $message->messageId; }

			

			$clientArchived = $this->memberAllDB_model->isMemberArchived($memberId_from_session,$message->messageId); 

			if($clientArchived>0)

			{ $output['archiveMsgs'][] = $message->messageId; }

		 }

		}

	   

	   

		//   $this->load->view('member_get_conversation.php',$output); 
		return view('members.dashboard.member_get_conversation', $output);

		  exit;

		}

	   

	   // make msgs read

	   $this->memberAllDB_model->makeMemberMsgRead($_GET['cid'],$memberId_from_session); 

	   

	   $output['cid'] = $_GET['cid'];

	   $headerOutput['pageTitle'] = 'Digiwax Member Info';



	   // save message

	   if(isset($_GET['message']) && isset($_GET['cid']))

	   {

	   $result = $this->memberAllDB_model->sendMemberMessage($memberId_from_session,$_GET['cid'],$_GET['message']); 

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

	   if(isset($_GET['messageId']) && isset($_GET['cid']) && isset($_GET['archive']))

	   {

	   $result = $this->memberAllDB_model->archiveMemberMessage($memberId_from_session,$_GET['messageId']); 

	   

	   

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

	   if(isset($_GET['messageId']) && isset($_GET['cid']) && isset($_GET['star']))

	   {

	   $result = $this->memberAllDB_model->starMemberMessage($memberId_from_session,$_GET['messageId']); 	   

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
   

	   $clientInfo = $this->memberAllDB_model->getClientInfo_fem($_GET['cid']); 

	   if($clientInfo['numRows']<1)

	   {

		header("location: ".url("Member_messages"));   exit;

	   }

	   

	   $output['clientName'] = $clientInfo['data'][0]->uname;

	   $clientImg = $this->memberAllDB_model->getClientImage($_GET['cid']); 

	   

	   

	   if($clientImg['numRows']>0 && strlen($clientImg['data'][0]->image)>4) 

	   {

		 $output['clientImage'] =  $clientImg['data'][0]->image;

 	   }

	   else

 {

 		 $output['clientImage'] = 'assets/img/profile-pic.png'; 
 		 // $clientImg['data'][0]->image;

   }

	   

		 

	   //  $messageInfo = $this->memberAllDB_model->getMessage($_GET['pid']); 

		 

	   $output['conversation'] = $this->memberAllDB_model->getConversation($memberId_from_session,$_GET['cid']); 

	   

   //	$output['archiveMsgs'] = array();

	   //$output['starMsgs'] = array();

		

	   if(isset($output['conversation']['numRows']) && $output['conversation']['numRows']>0)

	   {

	   

		 foreach($output['conversation']['data'] as $message)

		 {

		 

			$clientStarred = $this->memberAllDB_model->isMemberStarred($memberId_from_session,$message->messageId); 

			if($clientStarred>0)

			{ $output['starMsgs'][] = $message->messageId; }

			

			$clientArchived = $this->memberAllDB_model->isMemberArchived($memberId_from_session,$message->messageId); 

			if($clientArchived>0)

			{ $output['archiveMsgs'][] = $message->messageId; }

		 }

		}


	   // right side bar

	   $output['staffTracks'] = $this->memberAllDB_model->getStaffSelectedTracks_fem(0,4); 

	   

	   if($output['staffTracks']['numRows']>0)

	   {

	   foreach($output['staffTracks']['data'] as $track)

	   {

	   

		  $output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays_fem($track->id); 

		  $row = $this->memberAllDB_model->getClientTrackReview_fem($track->id); 

		  $output['reviews'][$track->id] = $row['numRows'];

		  

		  $output['mp3s'][$track->id] = $this->memberAllDB_model->getTrackMp3s_fem($track->id);

		

	   }

	   }

	   

	   /*$output['youTracks'] = $this->memberAllDB_model->getYouSelectedTracks_fem(0,4); 

	   

	   if($output['youTracks']['numRows']>0)

	   {

	   foreach($output['youTracks']['data'] as $track)

	   {

	   

		  $output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays_fem($track->id); 

		  $row = $this->memberAllDB_model->getClientTrackReview_fem($track->id); 

		  $output['reviews'][$track->id] = $row['numRows'];

		  

		  $output['mp3s'][$track->id] = $this->memberAllDB_model->getTrackMp3s_fem($track->id);

		

	   }

	   }*/

	   

	   $output['youTracks'] = $this->memberAllDB_model->getYouSelectedTracks1_fem($memberId_from_session,0,4); 

	   

	   

	   if($output['youTracks']['numRows']>0)

	   {

	   foreach($output['youTracks']['data'] as $track)

	   {

		  $output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays_fem($track->id); 

		  $row = $this->memberAllDB_model->getClientTrackReview_fem($track->id); 

		  $output['reviews'][$track->id] = $row['numRows'];

		  

		  $output['versions'][$track->id] = $this->memberAllDB_model->getTrackMp3s1_fem($track->id);

	   }

	   }

	   else

	   {

	   $output['youTracks'] = $this->memberAllDB_model->getYouSelectedTracks_fem(0,4); 

	   

	   if($output['youTracks']['numRows']>0)

	   {

	   foreach($output['youTracks']['data'] as $track)

	   {

	   

		  $output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays_fem($track->id); 

		  $row = $this->memberAllDB_model->getClientTrackReview_fem($track->id); 

		  $output['reviews'][$track->id] = $row['numRows'];

		  

		  $output['versions'][$track->id] = $this->memberAllDB_model->getTrackMp3s_fem($track->id);

	   }

	   }

	   }



	   $footerOutput['tracks'] = $this->memberAllDB_model->getMemberFooterTracks_fem(); 

	   

		$unReadmessages = $this->memberAllDB_model->getMemberUnreadInbox_fem($memberId_from_session); 

		$output['numMessages'] = $unReadmessages['numRows'];

		$headerOutput['numMessages'] = $unReadmessages['numRows'];

		

		

		 // subscription status

		$output['subscriptionStatus'] = 0;

		$output['package'] = '';

		$subscriptionInfo = $this->memberAllDB_model->getMemberSubscriptionStatus_fem($memberId_from_session); 

		if($subscriptionInfo['numRows']>0)

		{

		 $output['subscriptionStatus'] = 1;

		  

		 if($subscriptionInfo['data'][0]->package_Id==1)

		 {

		   $output['packageId'] = 1;

		   $output['package'] = 'Silver Subscription';

		   // $output['displayDashboard'] = 0;

		   

		   

		 }

		 else if($subscriptionInfo['data'][0]->package_Id==2)

		 {

		   $output['packageId'] = 2;

		   $output['package'] = 'Gold Subscription';

		   //$output['displayDashboard'] = 1;

		   

		   

		 }

		 else if($subscriptionInfo['data'][0]->package_Id==3)

		 {

		   $output['packageId'] = 3;

		   $output['package'] = 'Purple Subscription';

		   //$output['displayDashboard'] = 1;

		   

		   

		 }

	   }

		   

	   $headerOutput['tracks'] = $footerOutput['tracks'];

	   

	//    $this->load->view('header_member_top.php',$headerOutput);

	//    $this->load->view('member_send_message.php',$output);

	//    $this->load->view('footer_member_top.php',$footerOutput);

	  
	 
	   return view('members.dashboard.member_send_message', $output);



	}


	public function Member_dashboard_all_tracks(Request $request)
	{


		// header data pass starts

		if(empty(Session::get('memberId'))){ 
			return redirect()->intended('login');
		}

		$output = array();
        $memID = Session::get('memberId');
            $query = DB::table('package_user_details')->select('package_id')->where('user_id','=',$memID)->where('user_type','=',1)->where('package_active',1)->get();
            $count = $query->count();
            if($count == 0 || $query[0]->package_id == 7){
                $output['member_package'] = 'Standard';
            }else{
                $output['member_package'] = 'Premium';
            }
		$logo_data = array(
			'logo_id' => 1,
			);

		$logo_details = DB::table('website_logo')
		->where($logo_data)
		->first();  
		
		$get_logo = $logo_details->logo;

		 $output['pageTitle'] = 'Member Dashboard Streaming Tracks';
		 $output['logo'] = $get_logo;
		 $output['active'] = 'tracks';
	   
		// fb logout link
	   //  $this->load->library('facebook');
	   //  $logout_link = url('Logout');

	   //  if (isset($_SESSION['fb_access_token'])) {
	   // 	 $logout_link = $this->facebook->logout_url();
	   //  }
	   //  $output['logout_link'] = $logout_link;


	   // header data pass ends here and again continue at "#moreHeaderData"

	  
	   $tempMemberId = Session::get('tempMemberId');
	   $memberId_from_session = Session::get('memberId');
	   if(isset($tempMemberId))
	   {
		 $output['welcomeMsg'] = 'Thank you for updating your information !';
	   // remove session
		 Session::forget('tempMemberId');
	   }

	   if (isset($_GET['header_search_ajax']) && isset($_GET['key'])) {
		   $result = $this->memberAllDB_model->get_search_query($_GET['key']);
		   $markup='';
		   if(empty($result['data'])){
			   $markup= "<p class='no_data'>No data found for this keyword</p>";
		   }else{
			   foreach($result['data'] as $r){
				   $markup .= '<a href="/Member_track_review?tid='.$r->id.'" class="search_link">'.urldecode($r->title).' - by '.urldecode($r->artist).' ('.urldecode($r->album).')</a>';
			   }
		   }
		   echo $markup;
		   exit;
	   }

	   // save play record
	   if (isset($_GET['trackId']) && isset($_GET['recordPlay']) && isset($memberId_from_session)) {
		   $countryCode = '';
		   $countryName = '';


		   // get user location details:
		   // function getLocationInfoByIp(){

		   $client  = @$_SERVER['HTTP_CLIENT_IP'];
		   $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
		   $remote  = @$_SERVER['REMOTE_ADDR'];

		   $result  = array('country' => '', 'city' => '');

		   if (filter_var($client, FILTER_VALIDATE_IP)) {
			   $ip = $client;
		   } elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
			   $ip = $forward;
		   } else {
			   $ip = $remote;
		   }

		   $ip_data = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));

		   if ($ip_data && $ip_data->geoplugin_countryName != null) {
			   $countryCode = $ip_data->geoplugin_countryCode;
			   $countryName = $ip_data->geoplugin_countryName;
		   }
		   // }
		   $this->memberAllDB_model->addTrackPlay_fem($_GET['trackId'], $memberId_from_session, $countryName, $countryCode);
		   exit;
	   }

	   // generate where

	   $where = 'where ';
	   $whereItems[] = "tracks.deleted = '0'";
	   $whereItems[] = "tracks.active = '1'";
	   $whereItems[] = "tracks.status = 'publish'";
	   
	   if (isset($_GET['sortBy']) && strlen($_GET['sortBy']) > 0 && $_GET['sortBy'] == 'album') {
		   $whereItems[] = "tracks.album != ''";
	   }
	   
		if (isset($_GET['sortBy']) && strlen($_GET['sortBy']) > 0 && $_GET['sortBy'] == 'song') {
		  // $whereItems[] = "tracks.album = ''";
	   }


	   $output['searchArtist'] = '';
	   $output['searchTitle'] = '';
	   $output['searchLabel'] = '';
	   $output['searchAlbum'] = '';
	   $output['searchProducer'] = '';
	   $output['searchClient'] = '';
	   $output['searchKey'] = '';

	   if (isset($_GET['search'])) {
		   if (isset($_GET['genre']) && strlen($_GET['genre']) > 0) {
			   $output['searchGenre'] = $_GET['genre'];
			   $whereItems[] = "tracks.genre = '" . $_GET['genre'] . "'";
		   }

		   if (isset($_GET['bpm']) && strlen($_GET['bpm']) > 0) {
			   $output['searchBpm'] = $_GET['bpm'];
			   $whereItems[] = "tracks_mp3s.bpm = '" . $_GET['bpm'] . "'";
		   }

		   if (isset($_GET['version']) && strlen($_GET['version']) > 0) {
			   $output['searchVersion'] = $_GET['version'];
			   $whereItems[] = "tracks_mp3s.version like '%" . $_GET['version'] . "%'";
		   }

		   if (isset($_GET['searchKey']) && strlen($_GET['searchKey']) > 0) {
			   $output['searchKey'] = trim($_GET['searchKey']);
			   $searchKey = urlencode(trim($_GET['searchKey']));

			   $whereItems[] = "(tracks.artist LIKE '%" . urlencode($searchKey) . "%'
							   OR tracks.artist LIKE '%" . addslashes($searchKey) . "%' 
							   OR tracks.title LIKE '%" . urlencode($searchKey) . "%'
							   OR tracks.title LIKE '%" . addslashes($searchKey) . "%' 
							   OR tracks.album LIKE '%" . urlencode($searchKey) . "%' 
							   OR tracks.album LIKE '%" . addslashes($searchKey) . "%' 
							   OR tracks.label LIKE '%" . addslashes($searchKey) . "%' 
							   OR tracks.label LIKE '%" . urlencode($searchKey) . "%' 
							   OR tracks.bpm like '%" . addslashes($searchKey) . "%'
							   OR tracks_mp3s.version LIKE '%" . urlencode($searchKey) . "%'
							   OR tracks_mp3s.version LIKE '%" . addslashes($searchKey) . "%')";
			   //print_r($whereItems);
		   }
		   
		   
		   
	   }

	   if (count($whereItems) > 1) {
		   $whereString = implode(' AND ', $whereItems);
		   $where .= $whereString;
	   } else if (count($whereItems) == 1) {
		   $where .= $whereItems[0];
	   } else {
		   $where =  '';
	   }

	   // generate sort

	   if (!isset($_GET['sortBy']) && !isset($_GET['sortOrder'])) {
		   $sortOrder = "tracks.order_position DESC, tracks.added DESC";
		   $output['sortBy'] = '';
		   $output['sortOrder'] = '';
	   }else{
		$sortOrder = "DESC";
		$sortBy = "tracks.id";
		$output['sortBy'] = 'date';
		$output['sortOrder'] = 2;
	   }

	   if (isset($_GET['sortBy']) && isset($_GET['sortOrder'])) {
		   $output['sortBy'] = $_GET['sortBy'];
		   $output['sortOrder'] = $_GET['sortOrder'];

		   if (strcmp($_GET['sortBy'], 'artist') == 0) {
			   $sortBy = "artist";
		   } else if (strcmp($_GET['sortBy'], 'song') == 0) {
			   $sortBy = "title";
		   } else if (strcmp($_GET['sortBy'], 'label') == 0) {
			   $sortBy = "label";
		   } else if (strcmp($_GET['sortBy'], 'date') == 0) {
			   $sortBy = "tracks.added";
		   } else if (strcmp($_GET['sortBy'], 'album') == 0) {
			   $sortBy = "album";
			   
		   } else if (strcmp($_GET['sortBy'], 'bpm') == 0) {
			   $sortBy = "tracks_mp3s.bpm";
		   }
		   else if (strcmp($_GET['sortBy'], 'genre') == 0) {
			   $sortBy = "genres.genre";
		   }

		   if ($_GET['sortOrder'] == 1) {
			   $sortOrder = "ASC";
		   } else  if ($_GET['sortOrder'] == 2) {
			   $sortOrder = "DESC";
		   }
	   }
	   
      if (!isset($_GET['sortBy']) && !isset($_GET['sortOrder'])) {
		  $sort =  $sortOrder; 
	  }else{
	  
	   if ($sortBy == "title") $sort = "id" . " " . $sortOrder;
	   else $sort =  $sortBy . " " . $sortOrder;
	   
	  }
        
        // $sort = 'order_position ASC';
	   // pagination

	   $limit = 10;
	   if (isset($_GET['records'])) {
		   $limit = $_GET['records'];
	   }

	   $output['numRecords'] = $limit;

	   $start = 0;
	   $currentPageNo = 1;

	   if (isset($_GET['page']) && $_GET['page'] > 1) {
		   $start = ($_GET['page'] * $limit) - $limit;
	   }

	   if (isset($_GET['page'])) {
		   $currentPageNo = $_GET['page'];
	   }

	   $num_records = $this->memberAllDB_model->getNumDashboardTracks_fem($where, $sort);
	   $numPages = (int) ($num_records / $limit);
	   $reminder = ($num_records % $limit);
	   $output['num_records'] = $num_records;

	   if ($reminder > 0) {
		   $numPages = $numPages + 1;
	   }

	   $output['numPages'] = $numPages;
	   $output['start'] = $start;
	   $output['currentPageNo'] = $currentPageNo;
	   $output['currentPage'] = 'Member_dashboard_all_tracks';

	   // generate url string

	   $getArray = array();
	   $urlString = '?';

	   if (isset($_GET)) {
		   foreach ($_GET as $key => $value) {
			   if (strcmp($key, 'page') != 0) {
				   $getArray[] = $key . '=' . $value;
			   }
		   }

		   if (count($getArray) > 1) {
			   $urlString .= implode('&', $getArray);
		   } else if (count($getArray) == 1) {
			   $urlString .= $getArray[0];
		   } else {
			   $urlString = '';
		   }
	   }

	   $output['urlString'] = $urlString;

	   // uncomment later

	   if (isset($_GET['page'])) {
		   if (strlen($urlString) > 3) {
			   $param = '&';
		   } else {
			   $param = '?';
		   }

		   if ($_GET['page'] > $numPages && $numPages > 0) {
			   header("location: " . $output['currentPage'] . $urlString . $param . "page=" . $numPages);
			   exit;
		   }
	   }

	   if ($currentPageNo == 1) {
		   $output['firstPageLink'] = 'disabled';
		   $output['preLink'] = 'disabled';
		   $output['nextLink'] = '';
		   $output['lastPageLink'] = '';
	   } else if ($currentPageNo == $numPages) {
		   $output['firstPageLink'] = '';
		   $output['preLink'] = '';
		   $output['nextLink'] = 'disabled';
		   $output['lastPageLink'] = 'disabled';
	   } else {
		   $output['firstPageLink'] = '';
		   $output['preLink'] = '';
		   $output['nextLink'] = '';
		   $output['lastPageLink'] = '';
	   }

	   // pagination
	   $output['tracks'] = $this->memberAllDB_model->getDashboardTracks_fem($where, $sort, $start, $limit);
	   
	   //dd($output['tracks']);
	   //echo "<pre style='display:none;'>"; print_r($output['tracks']); echo "</pre>";

	   foreach ($output['tracks']['data'] as $track) {
		   $output['memberReviews'] = $this->memberAllDB_model->getClientTrackReview_fem($track->id);
		   $output['downloads'][$track->id] = $output['memberReviews']['numRows'];
		   $output['mp3s'][$track->id] = $this->memberAllDB_model->getTrackMp3s_fem($track->id);

		   if(!empty($track->client)){
			$output['social'][$track->id] = $this->memberAllDB_model->getClientSocialInfo_fem($track->client);
		   }
		   else{

			  $output['social'][$track->id] = '';

		   }
		  // $output['social'][$track->id] = $this->memberAllDB_model->getClientSocialInfo_fem($track->client);
	   }
	   
	   	        $arr1=$output['tracks']['data'];
				 $arr=json_decode(json_encode($arr1));
				
				// $arr= json_decode($arr1);
				 
				 foreach ($arr as $key=>$value){

				     $query_loc=$this->memberAllDB_model->getTrackMp3s_fem($value->id);
				    //  pArr($query_loc);
					$xx='';
				     foreach($query_loc['data'] as $key1=>$value1){
				        $xx= $value1->location;
				        
				     }
					 if(!empty($xx)){
				     $arr[$key]->location=$xx;
					 }
				   
				 }
				 $output['tracks']['data']=$arr;

	   // generate sort url sting

	   $sortExclude = array("sortOrder", "sortBy", "page");
	   $getSortArray = array();
	   $urlSortString = '?';

	   if (isset($_GET)) {
		   foreach ($_GET as $key => $value) {
			   if (!(in_array($key, $sortExclude))) {
				   $getSortArray[] = $key . '=' . $value;
			   }
		   }

		   if (count($getSortArray) > 1) {
			   $urlSortString .= implode('&', $getSortArray);
		   } else if (count($getSortArray) == 1) {
			   $urlSortString .= $getSortArray[0];
		   } else {
			   $urlSortString = '';
		   }
	   }

	   $output['urlSortString'] = $urlSortString;

	   // generate search url sting

	   $searchExclude = array("bpm", "version", "genre", "page", "records");
	   $getSearchArray = array();
	   $urlSearchString = '?';

	   if (isset($_GET)) {
		   foreach ($_GET as $key => $value) {
			   if (!(in_array($key, $searchExclude))) {
				   $getSearchArray[] = $key . '=' . $value;
			   }
		   }

		   if (count($getSearchArray) > 1) {
			   $urlSearchString .= implode('&', $getSearchArray);
		   } else if (count($getSearchArray) == 1) {
			   $urlSearchString .= $getSearchArray[0];
		   } else {
			   $urlSearchString = '';
		   }
	   }

	   $output['urlSearchString'] = $urlSearchString;

	   // generate record url sting

	   $recordExclude = array("records");
	   $getRecordArray = array();
	   $urlRecordString = '?';
	   if (isset($_GET)) {
		   foreach ($_GET as $key => $value) {
			   if (!(in_array($key, $recordExclude))) {
				   $getRecordArray[] = $key . '=' . $value;
			   }
		   }

		   if (count($getRecordArray) > 1) {
			   $urlRecordString .= implode('&', $getRecordArray);
		   } else if (count($getRecordArray) == 1) {
			   $urlRecordString .= $getRecordArray[0];
		   } else {
			   $urlRecordString = '';
		   }
	   }

	   $output['urlRecordString'] = $urlRecordString;

	   // right side bar

	   $output['staffTracks'] = $this->memberAllDB_model->getStaffSelectedTracks_fem(0, 4);
	   if ($output['staffTracks']['numRows'] > 0) {
		   foreach ($output['staffTracks']['data'] as $track) {
			   $output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays_fem($track->id);
			   $row = $this->memberAllDB_model->getClientTrackReview_fem($track->id);
			   $output['reviews'][$track->id] = $row['numRows'];
			   $output['mp3s'][$track->id] = $this->memberAllDB_model->getTrackMp3s_fem($track->id);
		   }
	   }

	   $output['youTracks'] = $this->memberAllDB_model->getYouSelectedTracks1_fem($memberId_from_session, 0, 4);
	   if ($output['youTracks']['numRows'] > 0) {
		   foreach ($output['youTracks']['data'] as $track) {
			   $output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays_fem($track->id);
			   $row = $this->memberAllDB_model->getClientTrackReview_fem($track->id);
			   $output['reviews'][$track->id] = $row['numRows'];

			   // $output['versions'][$track->id] = $this->memberAllDB_model->getTrackMp3s1_fem($track->id,$track->version);

			   $output['versions'][$track->id] = $this->memberAllDB_model->getTrackMp3s1_fem($track->id);
		   }
	   } else {
		   $output['youTracks'] = $this->memberAllDB_model->getYouSelectedTracks_fem(0, 4);
		   if ($output['youTracks']['numRows'] > 0) {
			   foreach ($output['youTracks']['data'] as $track) {
				   $output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays_fem($track->id);
				   $row = $this->memberAllDB_model->getClientTrackReview_fem($track->id);
				   $output['reviews'][$track->id] = $row['numRows'];
				   $output['versions'][$track->id] = $this->memberAllDB_model->getTrackMp3s_fem($track->id);
			   }
		   }
	   }

	   $footerOutput['tracks'] = $this->memberAllDB_model->getMemberFooterTracks_fem();

	   $unReadMessages = $this->memberAllDB_model->getMemberUnreadInbox_fem($memberId_from_session);
	   $output['numMessages']  = $unReadMessages['numRows'];
	  // $headerOutput['numMessages']  = $unReadMessages['numRows'];

	   // subscription status

	   $output['subscriptionStatus'] = 0;
	   $output['package'] = '';
	   $subscriptionInfo = $this->memberAllDB_model->getMemberSubscriptionStatus_fem($memberId_from_session);


	   if ($subscriptionInfo['numRows'] > 0) {
		   $output['subscriptionStatus'] = 1;
		   if ($subscriptionInfo['data'][0]->package_Id == 1) {
			   $output['packageId'] = 1;
			   $output['package'] = 'Silver Subscription';
			   // $output['displayDashboard'] = 0;
		   } else if ($subscriptionInfo['data'][0]->package_Id == 2) {
			   $output['packageId'] = 2;
			   $output['package'] = 'Gold Subscription';
			   //$output['displayDashboard'] = 1;
		   } else if ($subscriptionInfo['data'][0]->package_Id == 3) {
			   $output['packageId'] = 3;
			   $output['package'] = 'Purple Subscription';
			   //$output['displayDashboard'] = 1;
		   }
	   }

	  // $headerOutput['tracks'] = $footerOutput['tracks'];
	  // $headerOutput['banner_ads'] = $this->memberAllDB_model->getBannerads(2, 1);
	   $output['banner_ads'] = $this->memberAllDB_model->getBannerads(2, 2);


	   $udata = $this->memberAllDB_model->getMemberDetails($memberId_from_session);
	   //submit terms and codition modal
	   if(!empty($_GET['frm'])){

		if($_GET['frm']=='termsaccpt'){
			$this->memberAllDB_model->updateMemberAge($memberId_from_session,$this->input->post('age'));
			echo "true";
		 }


	}	
	   
	 
	   else {	
		   $output['memeberinfo'] = $udata;
		//    $this->load->view('header_member_top.php', $headerOutput);
		//    $this->load->view('member_dashboard_all_tracks.php', $output);
		//    $this->load->view('footer_member_top.php', $footerOutput);

		//dd($output);

		return view('members.dashboard.member_dashboard_all_tracks', $output);
	   }

	  

	}


	public function Member_change_password(Request $request)
	{


		// header data pass starts

		if(empty(Session::get('memberId'))){ 
			return redirect()->intended('login');
		}

		$output = array();

		$logo_data = array(
			'logo_id' => 1,
			);

		$logo_details = DB::table('website_logo')
		->where($logo_data)
		->first();  
		
		$get_logo = $logo_details->logo;

		 $output['pageTitle'] = 'Digiwax Member Change Password';
		 $output['logo'] = $get_logo;
	   
		// fb logout link
	   //  $this->load->library('facebook');
	   //  $logout_link = url('Logout');

	   //  if (isset($_SESSION['fb_access_token'])) {
	   // 	 $logout_link = $this->facebook->logout_url();
	   //  }
	   //  $output['logout_link'] = $logout_link;


	   // header data pass ends here and again continue at "#moreHeaderData"

	   $memberId_from_session = Session::get('memberId');

	  if(isset($_POST['changePassword']))

	  {



		 $confirmResult = $this->memberAllDB_model->confirmMemberCurrentPassword($_POST['currentPassword']); 



		 if($confirmResult>0)

		 {

	   

			 $result = $this->memberAllDB_model->updateMemberPassword($_POST['password']); 

			 

			 if($result>0)

			 {

			 header("location: ".url("Member_change_password?updated=1"));   exit;

			   

			 }

			 else

			 {

			 header("location: ".url("Member_change_password?error=1"));   exit;

			 }

		 }

		 else

		 {

			header("location: ".url("Member_change_password?wrongPassword=1"));   exit;

		 }
		 

	  }



	  if(isset($_GET['updated']))				

		  {

			   $my_details=$this->memberAllDB_model->getMemberInfo_fem($memberId_from_session)['data'][0];

			   // send mail starts
				if(!empty($my_details->email)){

						 $to_email =  urldecode($my_details->email);
						//dd($to_email);
					//	$to_email ='school_test007@yopmail.com';
					  
						$m_sub = 'Digiwaxx - Password Changed Notification';

					  $m_msg = "Hi ".$my_details->fname." ".$my_details->lname.",<br><br>This is to notify you that your account paasword has been changed on your request.<br><br>Digiwaxx Team";
		
						  $mail_data = [
							  'm_sub' => $m_sub,
							  'm_msg' => $m_msg,
						  ];

						  $cc_mails = 'admin@digiwaxx.com';
						  //$bcc_mails = '';
						  


						  	$sendInvoiceMail = Mail::to($to_email);

							// if(!empty($cc_mails)){
							// 	$sendInvoiceMail->cc($cc_mails);
							// }
				
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
				}
				// send mail ends


			//   $myy_info = urldecode($my_details->email);
			//   $this->load->library('email');
			//   $this->email->from('admin@digiwaxx.com', 'Digiwaxx');
			//   $this->email->cc('admin@digiwaxx.com');
			//   $this->email->to($myy_info);
			//   $this->email->set_mailtype("html");
			//   $this->email->subject('Digiwaxx - Password Changed Notification');
			//    $this->email->message("Hi ".$my_details->fname." ".$my_details->lname.",<br><br>This is to notify you that your account paasword has been changed on your request.<br><br>Digiwaxx Team");
			//   $this->email->send();

			 $output['alert_class'] = 'success-msg';

			 $output['alert_message'] = 'Password updated successfully !';

		  

		  }

		  else if(isset($_GET['error']))				

		  {

			 $output['alert_class'] = 'error-msg';

			 $output['alert_message'] = 'Error occured, please try again.';

		  }

		  else if(isset($_GET['wrongPassword']))				

		  {

			 $output['alert_class'] = 'error-msg';

			 $output['alert_message'] = 'Entered current password is incorrect.';

		  }

						  

						  

	 $unReadmessages = $this->memberAllDB_model->getMemberUnreadInbox_fem($memberId_from_session); 

	   $output['numMessages'] = $unReadmessages['numRows'];

	  // $headerOutput['numMessages'] = $unReadmessages['numRows'];

	   

	   

		// subscription status

	   $output['subscriptionStatus'] = 0;

	   $output['package'] = '';

	   $subscriptionInfo = $this->memberAllDB_model->getMemberSubscriptionStatus_fem($memberId_from_session); 

	   if($subscriptionInfo['numRows']>0)

	   {

		$output['subscriptionStatus'] = 1;

		 

		if($subscriptionInfo['data'][0]->package_Id==1)

		{

		 // $output['packageId'] = 1;

		  $output['package'] = 'Silver';

		  // $output['displayDashboard'] = 0;

		  

		  

		}

		else if($subscriptionInfo['data'][0]->package_Id==2)

		{

		 // $output['packageId'] = 2;

		  $output['package'] = 'Gold';

		  //$output['displayDashboard'] = 1;

		  

		  

		}

		else if($subscriptionInfo['data'][0]->package_Id==3)

		{

		 // $output['packageId'] = 2;

		  $output['package'] = 'Purple';

		  //$output['displayDashboard'] = 1;

		  

		  

		}

	  }

		  

	//   $this->load->view('header_member.php',$headerOutput);

	//   $this->load->view('member_change_password.php',$output);

	//   $this->load->view('footer_member.php');

	//dd($output);

	   return view('members.dashboard.member_change_password', $output);

	}
	
	
	
	  function uploadImage_to_pcloud($path,$file,$folderID)
     {	
        ## echo $path.'--'.$file.'---'.$folderID;die();
    	$fpath = base_path() . '/vendor/pcloud/pcloud-php-sdk/lib/pCloud/autoload.php';
      	 require_once($fpath);
    	 $file_path = $path.$file;
    	 
    	 
    	 
    	 $pCloudFile = new pCloud\File();
    	 $folder = $folderID;  // PCLOUD_FOLDER_ID
    	 $metadata = $pCloudFile->upload($file_path,$folder,$file);
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
   function delete_pcloud($fileid)
	{
		$fpath = base_path() . '/vendor/pcloud/pcloud-php-sdk/lib/pCloud/autoload.php';
		require_once($fpath);

		$pCloudFile = new pCloud\Folder();

		$metadata = $pCloudFile->deleteRecursive($fileid);
		//echo $file_path;print_r($metadata);die();
		return $metadata;
	}


	public function Member_edit_profile(Request $request)
	{

		// header data pass starts

		if(empty(Session::get('memberId'))){ 
			return redirect()->intended('login');
		}
   		$memId=Session::get('memberId');



		$output = array();

		$logo_data = array(
			'logo_id' => 1,
			);

		$logo_details = DB::table('website_logo')
		->where($logo_data)
		->first();  
		
		$get_logo = $logo_details->logo;

		 $output['pageTitle'] = 'Digiwax Member Edit';
		 $output['logo'] = $get_logo;
	   
		// fb logout link
	   //  $this->load->library('facebook');
	   //  $logout_link = url('Logout');

	   //  if (isset($_SESSION['fb_access_token'])) {
	   // 	 $logout_link = $this->facebook->logout_url();
	   //  }
	   //  $output['logout_link'] = $logout_link;


	   // header data pass ends here and again continue at "#moreHeaderData"

	   $memberId_from_session = Session::get('memberId');


	  if(isset($_POST['updateMember']))

	  {

		

		$file_path =base_path('member_images/' . $memberId_from_session);



		if (!file_exists($file_path)) 

		{

			mkdir($file_path);

		}


		//echo '<pre>';print_r($file_path);die;

			if(isset($_FILES['profileImage']['name']) && strlen($_FILES['profileImage']['name'])>3)

			{		

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
			  
				$destination_path = base_path("member_images/".$memberId_from_session."/");
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

				$image_name = $memberId_from_session."_".$artWorkImageName . '.'.$file_extension;
				// $image_name = preg_replace('/[^a-zA-Z0-9_.]/', '_', $image_name);

				$uploaded_data = $profileImage->move( $destination_path , $image_name );

				if( !empty( $uploaded_data )){
				    
				    
				      ## @GS pCLOUD IMAGE UPLOAD AND MAPPING
	               $pcloud_folder_query = DB::select("SELECT pCloudParentFolderID_mem_image  FROM member_images where memberId = '" . $memberId_from_session. "' order by imageId desc limit 1");    
	               $data_pcloud=$pcloud_folder_query;
	               if(!empty($data_pcloud[0]->pCloudParentFolderID_mem_image)){
	                   
	                    $folder = $data_pcloud[0]->pCloudParentFolderID_mem_image;  // PCLOUD_FOLDER_ID
	                    $folder = (int)$folder;
	                    $metadata_delete = $this->delete_pcloud($folder);
	                    $folderid='16072524897';
	                    $foldername = $memberId_from_session;
	                    $foldername = (string)$foldername;
	                    $folder=$this->pCloudCreateFolder($folderid, $foldername);
	                  
    				    
	               }
	               else{
	                   $folderid='16072524897';
	                   $foldername = $memberId_from_session;
	                   $foldername = (string)$foldername;
	                   //echo $foldername;die();
	                   
	                   $folder=$this->pCloudCreateFolder($folderid, $foldername);
	                   
	                   //echo'<pre>';print_r($folder);die();
	                   
	               }
                   
                   //if(empty($folder)){
                    //   $folder = '13216822134';
                  // }
                               
                    	$metadata = $this->uploadImage_to_pcloud($destination_path, $image_name, $folder);
                    	
                    	$pcloudFileId = $metadata->metadata->fileid;
                    	$parentfolderid = $metadata->metadata->parentfolderid;
                    	@unlink($destination_path.$image_name);
				    
				    
				    
				    
				  // die('file');

				  $this->memberAllDB_model->addMemberImage($image_name,$memberId_from_session,$pcloudFileId,$parentfolderid); 

					// $data = array('upload_data' => $this->upload->data());

					//$_SESSION['memberImage'] = $config['file_name'].'.'.$ext;
					
					if(!empty($pcloudFileId)){
					  $image_name=$pcloudFileId;
					}

					Session::put('memberImage', $image_name);

				}

				else{

				 // header("location: " . url("admin/album/edit/?aid=" . $_GET['aid'] . "&error=1"));
				}



					// $config['upload_path']          = "./member_images/".$memberId_from_session."/";

					// $config['allowed_types']        = 'gif|jpg|png|jpeg';

					// $config['max_size']             = 10000;

					// $config['max_width']            = 102400;

					// $config['max_height']           = 76800;

					// $config['file_name']            = $artWorkImageName;

					// $ext = explode('.',$_FILES['profileImage']['name']);

					// $count = count($ext);

					// $ext = $ext[$count-1];

					// $this->load->library('upload', $config);



					// if ( ! $this->upload->do_upload('profileImage'))

					// {

					// 		$error = array('error' => $this->upload->display_errors());

							

					// }

					// else

					// {

					

					// $this->memberAllDB_model->addMemberImage($config['file_name'].'.'.$ext,$memberId_from_session); 

					// $data = array('upload_data' => $this->upload->data());

					// 	$_SESSION['memberImage'] = $config['file_name'].'.'.$ext;

					// }

		}



				$result = $this->memberAllDB_model->updateMemberInfo($_POST,$memberId_from_session); 


				if($result>0)

				{

				header("location: Member_edit_profile?update=1");

				}

				else

				{

				header("location: Member_edit_profile?error=1");

				}

				

			}

			

			$output['memberInfo'] = $this->memberAllDB_model->getMemberInfo_fem($memberId_from_session); 

			

			$output['production'] = $this->memberAllDB_model->getMemberProductionInfo_fem($memberId_from_session); 

			

			$output['special'] = $this->memberAllDB_model->getMemberSpecialInfo($memberId_from_session); 

			

			$output['promoter'] = $this->memberAllDB_model->getMemberPromoterInfo_fem($memberId_from_session); 



			$output['clothing'] = $this->memberAllDB_model->getMemberClothingInfo_fem($memberId_from_session); 		

			

			$output['management'] = $this->memberAllDB_model->getMemberManagementInfo_fem($memberId_from_session); 	

			

			$output['record'] = $this->memberAllDB_model->getMemberRecordInfo_fem($memberId_from_session); 	

			

			$output['media'] = $this->memberAllDB_model->getMemberMediaInfo_fem($memberId_from_session); 	

			

			$output['radio'] = $this->memberAllDB_model->getMemberRadioInfo_fem($memberId_from_session); 	

			

			$output['socialInfo'] = $this->memberAllDB_model->getMemberSocialInfo_fem($memberId_from_session); 

			

		//	$output['clientImage'] = $this->memberAllDB_model->getClientImage_fem($clientId); 

		

		// right side bar

			$output['staffTracks'] = $this->memberAllDB_model->getStaffSelectedTracks_fem(0,4); 

			

			if($output['staffTracks']['numRows']>0)

			{

			foreach($output['staffTracks']['data'] as $track)

			{

			

				$output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays_fem($track->id); 

				$row = $this->memberAllDB_model->getClientTrackReview_fem($track->id); 

				$output['reviews'][$track->id] = $row['numRows'];

				

				$output['mp3s'][$track->id] = $this->memberAllDB_model->getTrackMp3s_fem($track->id);

			

			}

			}

			

		$output['youTracks'] = $this->memberAllDB_model->getYouSelectedTracks1_fem($memberId_from_session,0,4); 

			

			

			if($output['youTracks']['numRows']>0)

			{

			foreach($output['youTracks']['data'] as $track)

			{

			

				$output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays_fem($track->id); 

				$row = $this->memberAllDB_model->getClientTrackReview_fem($track->id); 

				$output['reviews'][$track->id] = $row['numRows'];

				

				$output['versions'][$track->id] = $this->memberAllDB_model->getTrackMp3s1_fem($track->id);

			}

			

			}

			else

			{

			

			$output['youTracks'] = $this->memberAllDB_model->getYouSelectedTracks_fem(0,4); 

			

			if($output['youTracks']['numRows']>0)

			{

			foreach($output['youTracks']['data'] as $track)

			{

			

				$output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays_fem($track->id); 

				$row = $this->memberAllDB_model->getClientTrackReview_fem($track->id); 

				$output['reviews'][$track->id] = $row['numRows'];

				

				$output['versions'][$track->id] = $this->memberAllDB_model->getTrackMp3s_fem($track->id);

			}

			}

			}

			

			

			/*$output['youTracks'] = $this->memberAllDB_model->getYouSelectedTracks_fem(0,4); 

			

			if($output['youTracks']['numRows']>0)

			{

			foreach($output['youTracks']['data'] as $track)

			{

			

				$output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays_fem($track->id); 

				$row = $this->memberAllDB_model->getClientTrackReview_fem($track->id); 

				$output['reviews'][$track->id] = $row['numRows'];

				

				$output['mp3s'][$track->id] = $this->memberAllDB_model->getTrackMp3s_fem($track->id);

			

			}

			}*/

			

			$footerOutput['tracks'] = $this->memberAllDB_model->getMemberFooterTracks_fem(); 

			

			

		$unReadmessages = $this->memberAllDB_model->getMemberUnreadInbox_fem($memberId_from_session); 

			$output['numMessages'] = $unReadmessages['numRows'];

			//$headerOutput['numMessages'] = $unReadmessages['numRows'];

			

				// subscription status

			$output['subscriptionStatus'] = 0;

			$output['package'] = '';

			$subscriptionInfo = $this->memberAllDB_model->getMemberSubscriptionStatus_fem($memberId_from_session); 
			
			if($subscriptionInfo['numRows']>0)

			{

				$output['subscriptionStatus'] = 1;

				

				if($subscriptionInfo['data'][0]->package_Id==1)

				{

				$output['packageId'] = 1;

				$output['package'] = 'Silver Subscription';

				// $output['displayDashboard'] = 0;

				

				

				}

				else if($subscriptionInfo['data'][0]->package_Id==2)

				{

				$output['packageId'] = 2;

				$output['package'] = 'Gold Subscription';

				//$output['displayDashboard'] = 1;

				

				

				}

				else if($subscriptionInfo['data'][0]->package_Id==3)

				{

				$output['packageId'] = 3;

				$output['package'] = 'Purple Subscription';

				//$output['displayDashboard'] = 1;

				

				

				}

			}

			

			//$headerOutput['tracks'] = $footerOutput['tracks'];	

			

	//   $this->load->view('header_member_top.php',$headerOutput);

	//   $this->load->view('member_edit_profile.php',$output);

	//   $this->load->view('footer_member_top.php',$footerOutput);
		$output['tracks']= $this->memberAllDB_model->getmemtracks($memId);

	   return view('members.dashboard.member_edit_profile', $output);



	}

	public function Buy_digicoins(Request $request)
	{
		//echo '---INNN----'.Session::get('clientId');die();
		// header data pass starts

		if(empty(Session::get('memberId')) && empty(Session::get('clientId'))){ 
			return redirect()->intended('login');
		}

		$output = array();

		$logo_data = array(
			'logo_id' => 1,
			);

		$logo_details = DB::table('website_logo')
		->where($logo_data)
		->first();  
		
		$get_logo = $logo_details->logo;

		 $output['pageTitle'] = 'Digiwax Buy Digicoins';
		 $output['logo'] = $get_logo;
	   
		// fb logout link
	   //  $this->load->library('facebook');
	   //  $logout_link = url('Logout');

	   //  if (isset($_SESSION['fb_access_token'])) {
	   // 	 $logout_link = $this->facebook->logout_url();
	   //  }
	   //  $output['logout_link'] = $logout_link;


	   // header data pass ends here and again continue at "#moreHeaderData"


	//    if((!(isset($memberId_from_session))) && (!(isset($clientId))))

	//    {

	// 	 header("location: ".url("Login"));   exit;

	//    }


	$memberId_from_session = Session::get('memberId');
	$clientId = Session::get('clientId');

	if(isset($memberId_from_session)){

		  $userId = $memberId_from_session;

		  $userType = 2;

		  

		  // subscription status

		$output['subscriptionStatus'] = 0;

		$output['package'] = '';

		$output['packageId'] = '';

		$subscriptionInfo = $this->memberAllDB_model->getMemberSubscriptionStatus_fem($memberId_from_session);   





		if($subscriptionInfo['numRows']>0)

		{

		 $output['subscriptionStatus'] = 1;

		 $output['packageId'] = $subscriptionInfo['data'][0]->package_Id;

		  

		 if($subscriptionInfo['data'][0]->package_Id==1)

		 {

		   $output['package'] = 'Silver Subscription';			

		 }

		 else if($subscriptionInfo['data'][0]->package_Id==2)

		 {

		   $output['package'] = 'Gold Subscription';

		 }

		 else if($subscriptionInfo['data'][0]->package_Id==3)

		 {

		   $output['package'] = 'Purple Subscription';

		 }

	   }

	   

	   }

		else if(isset($clientId))

	   {

		 $userId = $clientId;

		 $userType = 1;

	   

	   }

	    //pArr(Session::get('digicoin_package_stripe_price'));die();

	   // subscribe

	if(isset($_POST['silver']) && isset($userId) && isset($userType))

	   {




		 $result = $this->memberAllDB_model->buy_digicoins($userId,$userType,1); 


		 if($result>0)

		  {

			setcookie('buyId', $result, 0, "/");
			setcookie('digicoin_package_id', 1, 0, "/");
			setcookie('digicoin_package_price', 50, 0, "/");
			setcookie('digicoin_package_stripe_price', 5000, 0, "/");
			setcookie('digicoin_package_tittle', 'Silver', 0, "/");

			Session::put('buyId', $result);
			Session::put('digicoin_package_id', 1);
			Session::put('digicoin_package_price', 50);
			Session::put('digicoin_package_stripe_price', 5000);
			Session::put('digicoin_package_tittle', 'Silver');
			/* pArr(Session::get('buyId'));die(); */
			// $_SESSION['buyId'] = $result;

			// $_SESSION['digicoin_package_id'] = 1;

			// $_SESSION['digicoin_package_price'] = 50;

			// $_SESSION['digicoin_package_stripe_price'] = 5000;

			// $_SESSION['digicoin_package_tittle'] = 'Silver';

			return Redirect::to("Buy_digicoin_options");   exit;

		  }

		  else

		  {

			return Redirect::to("Buy_digicoins?error=1");   exit;

		  }

	   

	   }

	   else if(isset($_POST['gold']) && isset($userId) && isset($userType))

	   {



		 $result = $this->memberAllDB_model->buy_digicoins($userId,$userType,2); 

		 if($result>0)

		  {

			setcookie('buyId', $result, 0, "/");
			setcookie('digicoin_package_id', 2, 0, "/");
			setcookie('digicoin_package_price', 80, 0, "/");
			setcookie('digicoin_package_stripe_price', 8000, 0, "/");
			setcookie('digicoin_package_tittle', 'Gold', 0, "/");

			Session::put('buyId', $result);
			Session::put('digicoin_package_id', 2);
			Session::put('digicoin_package_price', 80);
			Session::put('digicoin_package_stripe_price', 8000);
			Session::put('digicoin_package_tittle', 'Gold');

			// $_SESSION['buyId'] = $result;

			// $_SESSION['digicoin_package_id'] = 2;

			// $_SESSION['digicoin_package_price'] = 80;

			// $_SESSION['digicoin_package_stripe_price'] = 8000;

			// $_SESSION['digicoin_package_tittle'] = 'Gold';

			return Redirect::to("Buy_digicoin_options");   exit;

		  }

		  else

		  {

			return Redirect::to("Buy_digicoins?error=1");   exit;

		  }

	   

	   }

	   else if(isset($_POST['purple']) && isset($userId) && isset($userType))

	   {



		 $result = $this->memberAllDB_model->buy_digicoins($userId,$userType,3); 

		 if($result>0)

		  {

			setcookie('buyId', $result, 0, "/");
			setcookie('digicoin_package_id', 3, 0, "/");
			setcookie('digicoin_package_price', 100, 0, "/");
			setcookie('digicoin_package_stripe_price', 10000, 0, "/");
			setcookie('digicoin_package_tittle', 'Purple', 0, "/");

			Session::put('buyId', $result);
			Session::put('digicoin_package_id', 3);
			Session::put('digicoin_package_price', 100);
			Session::put('digicoin_package_stripe_price', 10000);
			Session::put('digicoin_package_tittle', 'Purple');

			// $_SESSION['buyId'] = $result;

			// $_SESSION['digicoin_package_id'] = 3;

			// $_SESSION['digicoin_package_price'] = 100;

			// $_SESSION['digicoin_package_stripe_price'] = 10000;

			// $_SESSION['digicoin_package_tittle'] = 'Purple';

			return Redirect::to("Buy_digicoin_options");   exit;

		  }

		  else

		  {

			return Redirect::to("Buy_digicoins?error=1");   exit;

		  }

	   

	   }


	//    $this->load->view('header.php',$headerOutput);

	//    $this->load->view('buy_digicoins.php',$output);

	//    $this->load->view('footer.php');
	

	   return view('members.dashboard.buy_digicoins', $output);


	}


	public function Buy_digicoin_options(Request $request)
	{

		// header data pass starts

		if(empty(Session::get('memberId')) && empty(Session::get('clientId'))){ 
			return redirect()->intended('login');
		}

		$output = array();

		$logo_data = array(
			'logo_id' => 1,
			);

		$logo_details = DB::table('website_logo')
		->where($logo_data)
		->first();  
		
		$get_logo = $logo_details->logo;

		 $output['pageTitle'] = 'Digiwax Buy Digicoin Options';
		 $output['logo'] = $get_logo;
	   
		// fb logout link
	   //  $this->load->library('facebook');
	   //  $logout_link = url('Logout');

	   //  if (isset($_SESSION['fb_access_token'])) {
	   // 	 $logout_link = $this->facebook->logout_url();
	   //  }
	   //  $output['logout_link'] = $logout_link;


	   // header data pass ends here and again continue at "#moreHeaderData"
  
	   $memberId_from_session = Session::get('memberId');

	  // $digicoin_package_id = Session::get('digicoin_package_id');
	   
	   $digicoin_packageprice = Session::get('digicoin_package_stripe_price');
	   //$digicoin_packageprice = Session::get('digicoin_package_price');

	   $buyId = Session::get('buyId');
	   if(empty($buyId)){
		if(isset($_COOKIE['buyId'])){
			$buyId = $_COOKIE['buyId'];
		}
	   }
		if(isset($_COOKIE['digicoin_package_id'])){
			$digicoin_package_id = $_COOKIE['digicoin_package_id'];
		}

	   $clientId = Session::get('clientId');

		 /* pArr(Session::get('buyId'));die();  */

	   if(!(isset($buyId)))

	   {

		 return Redirect::to("Buy_digicoins");   exit;

	   }

	  


	   if(isset($memberId_from_session))

	   {

		  $userId = $memberId_from_session;

		  $userType = 2;

		  

			  // subscription status

		$output['subscriptionStatus'] = 0;

		$output['package'] = '';

		$subscriptionInfo = $this->memberAllDB_model->getMemberSubscriptionStatus_fem($memberId_from_session); 

		

   

		if($subscriptionInfo['numRows']>0)

		{

		 $output['subscriptionStatus'] = 1;

		  

		 if($subscriptionInfo['data'][0]->package_Id==1)

		 {

		   $output['packageId'] = 1;

		   $output['package'] = 'Silver Subscription';

		   //$output['displayDashboard'] = 0;

		 }

		 else if($subscriptionInfo['data'][0]->package_Id==2)

		 {

		   $output['packageId'] = 2;

		   $output['package'] = 'Gold Subscription';

		   //$output['displayDashboard'] = 1;

		 }

		 else if($subscriptionInfo['data'][0]->package_Id==3)

		 {

		   $output['packageId'] = 3;

		   $output['package'] = 'Purple Subscription';

		   //$output['displayDashboard'] = 1;

		 }

	   }



	   }

		else if(isset($clientId))

	   {

		 $userId = $clientId;

		 $userType = 1;

	   

	   }



   /*	

	   if(isset($_POST['update_options']))

	   {	

		 $result = $this->memberAllDB_model->updateMemberSubscribeDuration($_SESSION['subscriptionId'],$_POST['duration_Id']); 

		 if($result>0)

		  {

		  

			header("location: ".url("Member_payment"));   exit;

		  }

		  else

		  {

			header("location: ".url("Member_payment_options?error=1"));   exit;

		  }

	   

	   }*/

   //	$output['subscribeData'] = $this->memberAllDB_model->getMemberSubscriptionInfo($userId,$userType,$_SESSION['buyId']); 

	   

	   

	   if($digicoin_package_id==1)

	   {

		 $output['package'] = 'Silver';

	   }

	   else if($digicoin_package_id==2)

	   {

		 $output['package'] = 'Gold';

	   } 

	   else if($digicoin_package_id==3)

	   {

		 $output['package'] = 'Purple';

	   }

		   
		if(!empty($digicoin_packageprice)){
			$output['digiCoinPrice'] =  $digicoin_packageprice;
		}else{
			$output['digiCoinPrice'] =  0;
		}
	   $output['userType'] =  $userType;

/*		if($output['subscribeData']['numRows']!=1)

	   {

		 header("location: ".url("Member_subscriptions"));   exit;

	   }

			   

*/		

//		$output['prices'] = $this->memberAllDB_model->getMemberSubscriptionPrice($output['subscribeData']['data'][0]->package_Id,$output['subscribeData']['data'][0]->duration_Id);

	   

	   

	   
// for testting gateway

/* 		Session::put('digicoin_package_stripe_price', 100);
		Session::put('digicoin_package_price', 1); */


		//pArr($output);die();
	   return view('members.dashboard.buy_digicoin_options', $output);


 }



	public function PaypalBuyDigicoins(Request $request)
	{

		// header data pass starts

		if(empty(Session::get('memberId')) && empty(Session::get('clientId'))){ 
			return redirect()->intended('login');
		}

		$output = array();

		$logo_data = array(
			'logo_id' => 1,
			);

		$logo_details = DB::table('website_logo')
		->where($logo_data)
		->first();  
		
		$get_logo = $logo_details->logo;

		 $output['pageTitle'] = 'Digiwax Buy Digicoin Options';
		 $output['logo'] = $get_logo;
  
	   $memberId_from_session = Session::get('memberId');

	   $digicoin_package_id = Session::get('digicoin_package_id');

	   $buyId = Session::get('buyId');

	   $clientId = Session::get('clientId');

		 /* pArr(Session::get('buyId'));die();  */

	   if(!(isset($buyId)))

	   {

		 return Redirect::to("Buy_digicoins");   exit;

	   }

	  


	   if(isset($memberId_from_session))

	   {

		  $userId = $memberId_from_session;

		  $userType = 2;

		  

			  // subscription status

		$output['subscriptionStatus'] = 0;

		$output['package'] = '';

		$subscriptionInfo = $this->memberAllDB_model->getMemberSubscriptionStatus_fem($memberId_from_session); 

		

   

		if($subscriptionInfo['numRows']>0)

		{

		 $output['subscriptionStatus'] = 1;

		  

		 if($subscriptionInfo['data'][0]->package_Id==1)

		 {

		   $output['packageId'] = 1;

		   $output['package'] = 'Silver Subscription';

		   //$output['displayDashboard'] = 0;

		 }

		 else if($subscriptionInfo['data'][0]->package_Id==2)

		 {

		   $output['packageId'] = 2;

		   $output['package'] = 'Gold Subscription';

		   //$output['displayDashboard'] = 1;

		 }

		 else if($subscriptionInfo['data'][0]->package_Id==3)

		 {

		   $output['packageId'] = 3;

		   $output['package'] = 'Purple Subscription';

		   //$output['displayDashboard'] = 1;

		 }

	   }



	   }

		else if(isset($clientId))

	   {

		 $userId = $clientId;

		 $userType = 1;

	   

	   }

	   

	   

	   if($digicoin_package_id==1)

	   {

		 $output['package'] = 'Silver';

	   }

	   else if($digicoin_package_id==2)

	   {

		 $output['package'] = 'Gold';

	   } 

	   else if($digicoin_package_id==3)

	   {

		 $output['package'] = 'Purple';

	   }

		   

	   $output['userType'] =  $userType;



	   return view('members.dashboard.buy_digicoin_options', $output);


 }


 public function Member_track_review_view(Request $request)
	{

		// header data pass starts

		if(empty(Session::get('memberId'))){ 
			return redirect()->intended('login');
		}

		$output = array();

		$logo_data = array(
			'logo_id' => 1,
			);

		$logo_details = DB::table('website_logo')
		->where($logo_data)
		->first();  
		
		$get_logo = $logo_details->logo;

		 $output['pageTitle'] = 'Member Track Review View';
		 $output['logo'] = $get_logo;
	   
		// fb logout link
	   //  $this->load->library('facebook');
	   //  $logout_link = url('Logout');

	   //  if (isset($_SESSION['fb_access_token'])) {
	   // 	 $logout_link = $this->facebook->logout_url();
	   //  }
	   //  $output['logout_link'] = $logout_link;


	   // header data pass ends here and again continue at "#moreHeaderData"

	   $memberId_from_session = Session::get('memberId');

	  // add review

	  if(isset($_POST['submitReview']))

	  {

	  

	  

		   $result = $this->memberAllDB_model->addReview($_POST,$_GET['tid']); 

		   

		   if($result>0)

		   {

			  header("location: ".url("Member_track_download_front_end?tid=".$_GET['tid']."&reviewAdded=1"));   exit;

		   

		   }

		   else

		   {

			   header("location: ".url("Member_track_review?tid=".$_GET['tid']."&error=1"));   exit;

		   }

	  

	  }

	   

	  // generate where

	  $where = 'where ';

	  $whereItems[] = "tracks.deleted = '0'";

	  $whereItems[] = "tracks.id = '". $_GET['tid'] ."'";

	  

	  $output['searchArtist'] = '';

	  $output['searchTitle'] = '';

	  $output['searchLabel'] = '';

	  $output['searchAlbum'] = '';

	  $output['searchProducer'] = '';

	  $output['searchClient'] = '';

	  

	  

	  if(isset($_GET['search']))

	  {

		 

		if(isset($_GET['artist']) && strlen($_GET['artist'])>0)

		 {

		   $output['searchArtist'] = $_GET['artist'];

		   $whereItems[] = "tracks.artist = '". urlencode($_GET['artist']) ."'";

		 }

		 

		 if(isset($_GET['title']) && strlen($_GET['title'])>0)

		 {

		   $output['searchTitle'] = $_GET['title'];

		   $whereItems[] = "tracks.title = '". urlencode($_GET['title']) ."'";

		 }

		 

		 if(isset($_GET['label']) && strlen($_GET['label'])>0)

		 {

		   $output['searchLabel'] = $_GET['label'];

		   $whereItems[] = "tracks.label = '". urlencode($_GET['label']) ."'";

		 }

		 

		 if(isset($_GET['album']) && strlen($_GET['album'])>0)

		 {

		   $output['searchAlbum'] = $_GET['album'];

		   $whereItems[] = "tracks.album = '". urlencode($_GET['album']) ."'";

		 }

		 

		 if(isset($_GET['producer']) && strlen($_GET['producer'])>0)

		 {

		   $output['searchProducer'] = $_GET['producer'];

		   $whereItems[] = "tracks.producer = '". $_GET['producer'] ."'";

		 }

		 

		 if(isset($_GET['client']) && strlen($_GET['client'])>0)

		 {

		   $output['searchClient'] = $_GET['client'];

		   $whereItems[] = "tracks.client = '". $_GET['client'] ."'";

		 }

	   

	  

	  }

	  

	  

	  if(count($whereItems)>1)

	  {

	  

		 $whereString = implode(' AND ',$whereItems);

		 $where .= $whereString;

	  }

	  else if(count($whereItems)==1)

	  {

		 $where .= $whereItems[0];

	  }

	  else

	  {

		$where =  '';

	  }

	  

	  

	  // generate sort

	  $sortOrder = "ASC";

	  $sortBy = "title";

	  $output['sortBy'] = 'song';

	  $output['sortOrder'] = 1;

	  

	  if(isset($_GET['sortBy']) && isset($_GET['sortOrder']))

	  {

		 $output['sortBy'] = $_GET['sortBy'];

		 $output['sortOrder'] = $_GET['sortOrder'];

		 

		 

		 if(strcmp($_GET['sortBy'],'artist')==0)

		 {

		  

		   $sortBy = "artist";

		 }

		 else if(strcmp($_GET['sortBy'],'song')==0)

		 {

		  

		   $sortBy = "title";

		 }

		  else if(strcmp($_GET['sortBy'],'label')==0)

		 {

		  

		   $sortBy = "label";

		 }

		 else if(strcmp($_GET['sortBy'],'date')==0)

		 {

		  

		   $sortBy = "added";

		 }

		 else if(strcmp($_GET['sortBy'],'album')==0)

		 {

		  

		   $sortBy = "album";

		 }

		 else if(strcmp($_GET['sortBy'],'bpm')==0)

		 {

		  

		   $sortBy = "time";

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

	  /*

	  if(isset($_GET['page']) && $_GET['page']>1)

	   {

		  $start = ($_GET['page']*$limit)-$limit;  

	   }

	   

	  

	   if(isset($_GET['page']))

	   {

		  $currentPageNo = $_GET['page']; 

	   }

	  

	$num_records = $this->memberAllDB_model->getNumTracks($where,$sort); 

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

   $output['currentPage'] = 'Member_dashboard_all_tracks';

   

   

  



*/





// pagination





	  // if track id is invalid, takes to dashboard page

	  $output['tracks'] = $this->memberAllDB_model->getTracks($where,$sort,$start,$limit); 

	  $output['mp3s'] = $this->memberAllDB_model->getTrackMp3s_fem($_GET['tid']); 

	  if($output['tracks']['numRows']<1)

	  {

	  

		header("location: Member_dashboard_newest_tracks");

		exit;

	  }

	  

	  // if review is already posted, takes to download page

	  $output['memberReview'] = $this->memberAllDB_model->getClientTrackReview_fem($_GET['tid']);   

  /*	if($output['memberReview']['numRows']>0)

	  {

	  

		header("location: Member_track_download?tid=".$_GET['tid']);

		exit;

	  }*/

	  

	  

	  

	  if(isset($_GET['reviewUpdated']))

	  {

		

		$output['alert_message'] = "Review updated successfully!";

		$output['alert_class'] = "alert alert-success";

		

	  

	  }

	  

	  

	  $numMessages = $this->memberAllDB_model->getMemberInbox_fem($memberId_from_session); 

	$output['numMessages'] = $numMessages['numRows'];

	//$headerOutput['numMessages'] = $numMessages['numRows'];

	

	

	 // subscription status

	   $output['subscriptionStatus'] = 0;

	   $output['package'] = '';

	   $subscriptionInfo = $this->memberAllDB_model->getMemberSubscriptionStatus_fem($memberId_from_session); 

	   if($subscriptionInfo['numRows']>0)

	   {

		$output['subscriptionStatus'] = 1;

		 

		if($subscriptionInfo['data'][0]->package_Id==1)

		{

		  $output['packageId'] = 1;

		  $output['package'] = 'Silver Subscription';

		  // $output['displayDashboard'] = 0;

		  

		  

		}

		else if($subscriptionInfo['data'][0]->package_Id==2)

		{

		  $output['packageId'] = 2;

		  $output['package'] = 'Gold Subscription';

		  //$output['displayDashboard'] = 1;

		  

		  

		}

		else if($subscriptionInfo['data'][0]->package_Id==3)

		{

		  $output['packageId'] = 3;

		  $output['package'] = 'Purple Subscription';

		  //$output['displayDashboard'] = 1;

		  

		  

		}

	  }


	//   $this->load->view('header_member.php',$headerOutput);

	//   $this->load->view('member_track_review_view.php',$output);

	  //$this->load->view('footer_member.php');

	//  dd($output);

	   return view('members.dashboard.member_track_review_view', $output);

	}


	public function Member_track_download_front()
	{


		 // header data pass starts

		 if(empty(Session::get('memberId'))){ 
			return redirect()->intended('login');
		}

		$output = array();
		$track_added=array();
		 $output['pageTitle'] = 'Member Track Download';

		 $output['logo'] = $this->memberAllDB_model->getLogo();
		
	   
		// fb logout link
	   //  $this->load->library('facebook');
	   //  $logout_link = url('Logout');

	   //  if (isset($_SESSION['fb_access_token'])) {
	   // 	 $logout_link = $this->facebook->logout_url();
	   //  }
	   //  $output['logout_link'] = $logout_link;


	   // header data pass ends here and again continue at "#moreHeaderData"


		$memberId_from_session = Session::get('memberId');
		$memberPackage = Session::get('memberPackage');
            $output['member_package']='';
			if(!empty($memberId_from_session)){

				$get_member_id = $memberId_from_session;
		    	$query = DB::table('package_user_details')->select('package_id')->where('user_id','=',$memberId_from_session)->where('user_type','=',1)->where('package_active',1)->get();
		    	if(count($query)>0){
		    	    $output['member_package']=$query[0]->package_id;
		    	}
		    	else{
		    	    $output['member_package']='';
		    	}
			}
			else{
	
				$get_member_id = '';
	
			}

			if(!empty($memberPackage)){

				$get_member_pkg = $memberPackage;
			}
			else{
	
				$get_member_pkg = '';
	
			}
	

			// checking if user can download the song or not. All only if purple member or has reviewed
			$output['memberReview'] = $this->memberAllDB_model->getClientTrackReview_fem($_GET['tid']);


			if(!empty($_POST)  &&  $_POST['is_admin_view'] =="yes" && isset($_POST['adminID'])  ){

			}
			else{
					/* if(!$output['memberReview']['numRows'] AND $get_member_pkg < 3)
					{
					  header("location: Member_track_review?tid=".$_GET['tid']);
					  exit;
					} */
		
		
				//   if(isset($_COOKIE['fb_access_token']))
				//   {
				//      $logout_link = $this->facebook->logout_url();
				//    }
		
				//   $output['logout_link'] = $logout_link;
		
				  if(!(isset($get_member_id)))
				  {
					  header("location: ".url("Login"));
						exit;
				  }
			}



		// add review
	

		if(isset($_POST['submitReview']))

		{
		   


			 $result = $this->memberAllDB_model->addReview($_POST,$_GET['tid']);


            
			 if($result>0)

			 {

				header("location: ".url("Member_track_download_front_end?tid=".$_GET['tid']."&reviewAdded=1"));   exit;



			 }

			 else

			 {
			     //my change 2-----------------------------------------------------------------------------------------------------------
                	//	header("location: ".url("Member_track_download_front_end?tid=".$_GET['tid']."&reviewAdded=1"));   exit;
				  header("location: ".url("Member_track_review?tid=".$_GET['tid']."&error=1"));   exit;

			 }



		}



		// generate where

		$where = 'where ';

		$whereItems[] = "tracks.deleted = '0'";

		$whereItems[] = "tracks.id = '". $_GET['tid'] ."'";



		$output['searchArtist'] = '';

		$output['searchTitle'] = '';

		$output['searchLabel'] = '';

		$output['searchAlbum'] = '';

		$output['searchProducer'] = '';

		$output['searchClient'] = '';




		if(isset($_GET['search']))

		{



		  if(isset($_GET['artist']) && strlen($_GET['artist'])>0)

		   {

			 $output['searchArtist'] = $_GET['artist'];

			 $whereItems[] = "tracks.artist = '". urlencode($_GET['artist']) ."'";

		   }



		   if(isset($_GET['title']) && strlen($_GET['title'])>0)

		   {

			 $output['searchTitle'] = $_GET['title'];

			 $whereItems[] = "tracks.title = '". urlencode($_GET['title']) ."'";

		   }



		   if(isset($_GET['label']) && strlen($_GET['label'])>0)

		   {

			 $output['searchLabel'] = $_GET['label'];

			 $whereItems[] = "tracks.label = '". urlencode($_GET['label']) ."'";

		   }



		   if(isset($_GET['album']) && strlen($_GET['album'])>0)

		   {

			 $output['searchAlbum'] = $_GET['album'];

			 $whereItems[] = "tracks.album = '". urlencode($_GET['album']) ."'";

		   }



		   if(isset($_GET['producer']) && strlen($_GET['producer'])>0)

		   {

			 $output['searchProducer'] = $_GET['producer'];

			 $whereItems[] = "tracks.producer = '". $_GET['producer'] ."'";

		   }



		   if(isset($_GET['client']) && strlen($_GET['client'])>0)

		   {

			 $output['searchClient'] = $_GET['client'];

			 $whereItems[] = "tracks.client = '". $_GET['client'] ."'";

		   }


		}



		if(count($whereItems)>1)

		{




		   $whereString = implode(' AND ',$whereItems);

		   $where .= $whereString;

		}

		else if(count($whereItems)==1)

		{

		   $where .= $whereItems[0];

		}

		else

		{

		  $where =  '';

		}





		// generate sort

		$sortOrder = "ASC";

		$sortBy = "title";

		$output['sortBy'] = 'song';

		$output['sortOrder'] = 1;



		if(isset($_GET['sortBy']) && isset($_GET['sortOrder']))

		{

		   $output['sortBy'] = $_GET['sortBy'];

		   $output['sortOrder'] = $_GET['sortOrder'];





		   if(strcmp($_GET['sortBy'],'artist')==0)

		   {



			 $sortBy = "artist";

		   }

		   else if(strcmp($_GET['sortBy'],'song')==0)

		   {



			 $sortBy = "title";

		   }

			else if(strcmp($_GET['sortBy'],'label')==0)

		   {



			 $sortBy = "label";

		   }

		   else if(strcmp($_GET['sortBy'],'date')==0)

		   {



			 $sortBy = "added";

		   }

		   else if(strcmp($_GET['sortBy'],'album')==0)

		   {



			 $sortBy = "album";

		   }

		   else if(strcmp($_GET['sortBy'],'bpm')==0)

		   {



			 $sortBy = "time";

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

		/*

		if(isset($_GET['page']) && $_GET['page']>1)

		 {

			$start = ($_GET['page']*$limit)-$limit;

		 }





		 if(isset($_GET['page']))

		 {

			$currentPageNo = $_GET['page'];

		 }



	  $num_records = $this->memberAllDB_model->getNumTracks($where,$sort);

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

	 $output['currentPage'] = 'Member_dashboard_all_tracks';









 */





  // pagination





		// if track id is invalid, takes to dashboard page

		$output['tracks'] = $this->memberAllDB_model->getReviewTracks($where,$sort,$start,$limit);
	   // print_r( $output['tracks'] );
	   
	    


		if($output['tracks']['numRows']<1)

		{



		  header("location: Member_dashboard_newest_tracks");

		  exit;

		}

    
    $arr1=$output['tracks']['data'];
				 $arr=json_decode(json_encode($arr1));
				
				// $arr= json_decode($arr1);
				 
				 foreach ($arr as $key=>$value){

				     $query_loc=$this->memberAllDB_model->getTrackMp3s_fem($value->id);
				    //  pArr($query_loc);
					$xx='';
				     foreach($query_loc['data'] as $key1=>$value1){
				        $xx= $value1->location;
				        
				     }
					 if(!empty($xx)){
				     $arr[$key]->location=$xx;
					 }
				   
				 }
				 $output['tracks']['data']=$arr;




		$numMessages = $this->memberAllDB_model->getMemberInbox_fem($get_member_id);

	  $output['numMessages'] = $numMessages['numRows'];

	  $output['numMessages'] = $numMessages['numRows'];





	  // right side bar

		$output['staffTracks'] = $this->memberAllDB_model->getStaffSelectedTracks_fem(0,4);

   

		if($output['staffTracks']['numRows']>0)
		{

            

			foreach($output['staffTracks']['data'] as $track)
	
			{
	
	
	
			   $output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays_fem($track->id);
	
			   $row = $this->memberAllDB_model->getClientTrackReview_fem($track->id);
	
			   $output['reviews'][$track->id] = $row['numRows'];
	
	
	
			  // $output['mp3s'][$track->id] = $this->memberAllDB_model->getTrackMp3s_fem($track->id);
			 // $tid = $this->input->get('tid');
			 
			 
			  $tid = $_GET['tid'];
			  
			  
			  
			     
			     $query_track = $this->memberAllDB_model->getTrackMp3s_fem($tid);
			     //pArr($query_track);
			     //die();
			     
			     foreach($query_track['data'] as $value){
			         $xx= $value->id;
    			     
    			     if(!in_array($xx,$track_added)){
    
    			         $output['mp3s'][$track->id] = $query_track;
    			         $track_added[]=$xx;
    			     }
    			  
    			  
			     }
			     
			     //pArr($track_added);
			     //die();
			 
			 // dd( $output['mp3s'][$track->id]);
	
	
	
			}

		}
     
    //           $tid = $_GET['tid'];
			 // $output['mp3s'][$track->id] = $this->memberAllDB_model->getTrackMp3s_fem($tid);


		/*$output['youTracks'] = $this->memberAllDB_model->getYouSelectedTracks_fem(0,4);



		if($output['youTracks']['numRows']>0)

		{

		foreach($output['youTracks']['data'] as $track)

		{



		   $output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays_fem($track->id);

		   $row = $this->memberAllDB_model->getClientTrackReview_fem($track->id);

		   $output['reviews'][$track->id] = $row['numRows'];



		   $output['mp3s'][$track->id] = $this->memberAllDB_model->getTrackMp3s_fem($track->id);



		}

		} */



		$output['youTracks'] = $this->memberAllDB_model->getYouSelectedTracks1_fem($get_member_id,0,4);


		if($output['youTracks']['numRows']>0)

		{

		foreach($output['youTracks']['data'] as $track)

		{



		   $output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays_fem($track->id);

		   $row = $this->memberAllDB_model->getClientTrackReview_fem($track->id);

		   $output['reviews'][$track->id] = $row['numRows'];



		   $output['versions'][$track->id] = $this->memberAllDB_model->getTrackMp3s1_fem($track->id);

		}



		}

		else

		{



		$output['youTracks'] = $this->memberAllDB_model->getYouSelectedTracks_fem(0,4);



		if($output['youTracks']['numRows']>0)

		{

		foreach($output['youTracks']['data'] as $track)

		{



		   $output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays_fem($track->id);

		   $row = $this->memberAllDB_model->getClientTrackReview_fem($track->id);

		   $output['reviews'][$track->id] = $row['numRows'];



		   $output['versions'][$track->id] = $this->memberAllDB_model->getTrackMp3s_fem($track->id);

		}

		}

		}



		$output['tracks_footer'] = $this->memberAllDB_model->getMemberFooterTracks_fem();







		 // subscription status

		 $output['subscriptionStatus'] = 0;

		 $output['package'] = '';

		 $subscriptionInfo = $this->memberAllDB_model->getMemberSubscriptionStatus_fem($get_member_id);

		 if($subscriptionInfo['numRows']>0)

		 {

		  $output['subscriptionStatus'] = 1;



		  if($subscriptionInfo['data'][0]->package_Id==1)

		  {

			$output['packageId'] = 1;

			$output['package'] = 'Silver Subscription';

			// $output['displayDashboard'] = 0;





		  }

		  else if($subscriptionInfo['data'][0]->package_Id==2)

		  {

			$output['packageId'] = 2;

			$output['package'] = 'Gold Subscription';

			//$output['displayDashboard'] = 1;





		  }

		  else if($subscriptionInfo['data'][0]->package_Id==3)

		  {

			$output['packageId'] = 3;

			$output['package'] = 'Purple Subscription';

			//$output['displayDashboard'] = 1;





		  }

		}


		// echo '<pre/>'; print_r($output); exit;





		// $this->load->view('header_member.php',$output);

		// $this->load->view('member_track_download.php',$output);

		// $this->load->view('footer_member.php',$output);

// pArr($output);
// die();

		return view('admin/admin_member_track_frontend', $output);

	}


	public function Member_track_review_edit(Request $request)
	{

		// header data pass starts

		if(empty(Session::get('memberId'))){ 
			return redirect()->intended('login');
		}

		$output = array();

		$logo_data = array(
			'logo_id' => 1,
			);

		$logo_details = DB::table('website_logo')
		->where($logo_data)
		->first();  
		
		$get_logo = $logo_details->logo;

		 $output['pageTitle'] = 'Member Track Review Update';
		 $output['logo'] = $get_logo;
	   
		// fb logout link
	   //  $this->load->library('facebook');
	   //  $logout_link = url('Logout');

	   //  if (isset($_SESSION['fb_access_token'])) {
	   // 	 $logout_link = $this->facebook->logout_url();
	   //  }
	   //  $output['logout_link'] = $logout_link;


	   // header data pass ends here and again continue at "#moreHeaderData"

	   $memberId_from_session = Session::get('memberId');
	   
	
	  // update review

	  if(isset($_POST['updateReview']))

	  {

	  

	  

		   $result = $this->memberAllDB_model->updateReview($_POST,$_GET['tid']); 

		   

		   if($result>0)

		   {

			  header("location: ".url("Member_track_review_view?tid=".$_GET['tid']."&reviewUpdated=1"));   exit;

		   

		   }

		   else

		   {

			   header("location: ".url("Member_track_review_edit?tid=".$_GET['tid']."&error=1"));   exit;

		   }

	  

	  }

	   

	  // generate where

	  $where = 'where ';

	  $whereItems[] = "tracks.deleted = '0'";

	  $whereItems[] = "tracks.id = '". $_GET['tid'] ."'";

	  

	  $output['searchArtist'] = '';

	  $output['searchTitle'] = '';

	  $output['searchLabel'] = '';

	  $output['searchAlbum'] = '';

	  $output['searchProducer'] = '';

	  $output['searchClient'] = '';

	  

	  

	  if(isset($_GET['search']))

	  {

		 

		if(isset($_GET['artist']) && strlen($_GET['artist'])>0)

		 {

		   $output['searchArtist'] = $_GET['artist'];

		   $whereItems[] = "tracks.artist = '". urlencode($_GET['artist']) ."'";

		 }

		 

		 if(isset($_GET['title']) && strlen($_GET['title'])>0)

		 {

		   $output['searchTitle'] = $_GET['title'];

		   $whereItems[] = "tracks.title = '". urlencode($_GET['title']) ."'";

		 }

		 

		 if(isset($_GET['label']) && strlen($_GET['label'])>0)

		 {

		   $output['searchLabel'] = $_GET['label'];

		   $whereItems[] = "tracks.label = '". urlencode($_GET['label']) ."'";

		 }

		 

		 if(isset($_GET['album']) && strlen($_GET['album'])>0)

		 {

		   $output['searchAlbum'] = $_GET['album'];

		   $whereItems[] = "tracks.album = '". urlencode($_GET['album']) ."'";

		 }

		 

		 if(isset($_GET['producer']) && strlen($_GET['producer'])>0)

		 {

		   $output['searchProducer'] = $_GET['producer'];

		   $whereItems[] = "tracks.producer = '". $_GET['producer'] ."'";

		 }

		 

		 if(isset($_GET['client']) && strlen($_GET['client'])>0)

		 {

		   $output['searchClient'] = $_GET['client'];

		   $whereItems[] = "tracks.client = '". $_GET['client'] ."'";

		 }


	  }


	  if(count($whereItems)>1)

	  {

	  

		 $whereString = implode(' AND ',$whereItems);

		 $where .= $whereString;

	  }

	  else if(count($whereItems)==1)

	  {

		 $where .= $whereItems[0];

	  }

	  else

	  {

		$where =  '';

	  }

	  

	  

	  // generate sort

	  $sortOrder = "ASC";

	  $sortBy = "title";

	  $output['sortBy'] = 'song';

	  $output['sortOrder'] = 1;

	  

	  if(isset($_GET['sortBy']) && isset($_GET['sortOrder']))

	  {

		 $output['sortBy'] = $_GET['sortBy'];

		 $output['sortOrder'] = $_GET['sortOrder'];

		 

		 

		 if(strcmp($_GET['sortBy'],'artist')==0)

		 {

		  

		   $sortBy = "artist";

		 }

		 else if(strcmp($_GET['sortBy'],'song')==0)

		 {

		  

		   $sortBy = "title";

		 }

		  else if(strcmp($_GET['sortBy'],'label')==0)

		 {

		  

		   $sortBy = "label";

		 }

		 else if(strcmp($_GET['sortBy'],'date')==0)

		 {

		  

		   $sortBy = "added";

		 }

		 else if(strcmp($_GET['sortBy'],'album')==0)

		 {

		  

		   $sortBy = "album";

		 }

		 else if(strcmp($_GET['sortBy'],'bpm')==0)

		 {

		  

		   $sortBy = "time";

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

	  /*

	  if(isset($_GET['page']) && $_GET['page']>1)

	   {

		  $start = ($_GET['page']*$limit)-$limit;  

	   }

	   

	  

	   if(isset($_GET['page']))

	   {

		  $currentPageNo = $_GET['page']; 

	   }

	  

	$num_records = $this->memberAllDB_model->getNumTracks($where,$sort); 

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

   $output['currentPage'] = 'Member_dashboard_all_tracks';



*/



// pagination

	  // if track id is invalid, takes to dashboard page

	  $output['tracks'] = $this->memberAllDB_model->getTracks($where,$sort,$start,$limit); 

	  $output['mp3s'] = $this->memberAllDB_model->getTrackMp3s_fem($_GET['tid']); 

	  if($output['tracks']['numRows']<1)

	  {

	  

		header("location: Member_dashboard_newest_tracks");

		exit;

	  }

	  

	  // if review is already posted, takes to download page

	  $output['memberReview'] = $this->memberAllDB_model->getClientTrackReview_fem($_GET['tid']);   

  /*	if($output['memberReview']['numRows']>0)

	  {

	  

		header("location: Member_track_download?tid=".$_GET['tid']);

		exit;

	  }*/

	  

	  

	  

	  if(isset($_GET['error']))

	  {

		

		$output['alert_message'] = "Error occured, please try again!";

		$output['alert_class'] = "alert alert-danger";

		

	  

	  }

	  

	  

	  $numMessages = $this->memberAllDB_model->getMemberInbox_fem($memberId_from_session); 

	$output['numMessages'] = $numMessages['numRows'];

	//$headerOutput['numMessages'] = $numMessages['numRows'];


	 // subscription status

	   $output['subscriptionStatus'] = 0;

	   $output['package'] = '';

	   $subscriptionInfo = $this->memberAllDB_model->getMemberSubscriptionStatus_fem($memberId_from_session); 

	   if($subscriptionInfo['numRows']>0)

	   {

		$output['subscriptionStatus'] = 1;

		 

		if($subscriptionInfo['data'][0]->package_Id==1)

		{

		  $output['packageId'] = 1;

		  $output['package'] = 'Silver Subscription';

		  // $output['displayDashboard'] = 0;

		  

		  

		}

		else if($subscriptionInfo['data'][0]->package_Id==2)

		{

		  $output['packageId'] = 2;

		  $output['package'] = 'Gold Subscription';

		  //$output['displayDashboard'] = 1;

		  

		  

		}

		else if($subscriptionInfo['data'][0]->package_Id==3)

		{

		  $output['packageId'] = 3;

		  $output['package'] = 'Purple Subscription';

		  //$headerOutput['displayDashboard'] = 1;
	  

		}

	  }
 

	//   $this->load->view('header_member.php',$headerOutput);

	//   $this->load->view('member_track_review_edit.php',$output);

	  //$this->load->view('footer_member.php');

	 // dd($output);
	  return view('members.dashboard.member_track_review_edit', $output);
	  

	}


	public function Member_track_review_share(Request $request)
	{


		// header data pass starts

		if(empty(Session::get('memberId'))){ 
			return redirect()->intended('login');
		}

		$output = array();
		$track_added = array();

		$logo_data = array(
			'logo_id' => 1,
			);

		$logo_details = DB::table('website_logo')
		->where($logo_data)
		->first();  
		
		$get_logo = $logo_details->logo;

		 $output['pageTitle'] = 'Member Track Review Share';
		 $output['logo'] = $get_logo;
	   
		// fb logout link
	   //  $this->load->library('facebook');
	   //  $logout_link = url('Logout');

	   //  if (isset($_SESSION['fb_access_token'])) {
	   // 	 $logout_link = $this->facebook->logout_url();
	   //  }
	   //  $output['logout_link'] = $logout_link;


	   // header data pass ends here and again continue at "#moreHeaderData"


	
	   if(isset($_POST['additionalThingsReview']))
	   {
		   $result = $this->memberAllDB_model->additionalThingsReview($_POST,$_GET['tid']);
		   if($result>0) {
		       $request_additional_things = implode(',', $_POST['additional_things']);
		       
		       $trackInfoArr = $this->memberAllDB_model->getTrack($_GET['tid']);
		       $trackInfo = $trackInfoArr['data'];
		       $artist = '';
		       $trackTitle = '';
		       $labelContactEmail = '';
			   $labelContactEmailfeedback1 = '';
			   $labelContactEmailfeedback2 = '';
			   $labelContactEmailfeedback3 = '';
			   
		       foreach ($trackInfo as $key=>$value){
		           $artist = urldecode($value->artist);
		           $trackTitle = urldecode($value->title);
		           $labelContactEmail = urldecode($value->contact_email);
				   $labelContactEmailfeedback1 = urldecode($value->feedback1_contact_email);
		           $labelContactEmailfeedback2 = urldecode($value->feedback2_contact_email);
		           $labelContactEmailfeedback3 = urldecode($value->feedback3_contact_email);
		       }
		       
		       $memId = Session::get('memberId');
		       $memberInfo = $this->memberAllDB_model->getMemberInfo($memId);
		       $email = urldecode($memberInfo['data'][0]->email);
		       $memFname = urldecode($memberInfo['data'][0]->fname);
		       $memLname = urldecode($memberInfo['data'][0]->lname);
		       $memName = $memFname.' '.$memLname;
		       $emailofuser = $email;
		       $data = array('userEmailId' => $emailofuser, 'name' => $memName,'trackname' => $trackTitle,'trackArtist' => $artist,'additional_things_requested' => $request_additional_things,'clientEmailId' => $labelContactEmail, 'clientEmailIdfeedback1' => $labelContactEmailfeedback1, 'clientEmailIdfeedback2' =>  $labelContactEmailfeedback2, 'clientEmailIdfeedback3' =>  $labelContactEmailfeedback3);
            	Mail::send('mails.members.additionalServicesRequest',['data' => $data], function ($message) use ($data) {
            	  $message->to('music@digiwaxx.com');
            	  $message->subject('Additional Services Request at DigiWaxx');
            	  $message->from('business@digiwaxx.com','Digiwaxx');
               });

				if (!empty($labelContactEmail) || !empty($labelContactEmailfeedback1) || !empty($labelContactEmailfeedback2) || !empty($labelContactEmailfeedback3)) {
					$recipients = array_filter([
						$data['clientEmailId'],
						$data['clientEmailIdfeedback1'],
						$data['clientEmailIdfeedback2'],
						$data['clientEmailIdfeedback3']
					]);
					Mail::send('mails.members.additionalServicesRequest',['data' => $data], function ($message) use ($recipients) {
					  $message->to($recipients);
					  $message->subject('Additional Services Request at Digiwaxx');
					  $message->from('business@digiwaxx.com','Digiwaxx');
				    });
				}
			   header("location: ".url("Member_track_review/share?tid=".$_GET['tid']."&reviewupdated=1"));   exit;
			}
			else {
				header("location: ".url("Member_track_review?tid=".$_GET['tid']."&error=1"));   exit;
			}
	   }
   
   
			/*
   
				if(isset($_POST['twitter']))
   
				{
   
				$this->load->library(array('twconnect'));
   
				$ok = $this->twconnect->tw_post('statuses/update',["status" => $_POST['comments']]);
   
				echo '<br /><br /><br />';
   
				print_r($ok);
   
   
   
				}
   
				*/
   
   
   
	   $output['review'] = $this->memberAllDB_model->getClientTrackReview_fem($_GET['tid']);
   
   
   
	   if($output['review']['numRows']<1)
   
	   {
   
		  header("location: ".url("Member_track_review?tid=".$_GET['tid']));   exit;
   
	   }
   
   
   
	   $output['tracks'] = $this->memberAllDB_model->getTrack($_GET['tid']);
	   
	   
	   
   
	   //$output['mp3s'] = $this->memberAllDB_model->getTrackMp3s_fem($_GET['tid']);
   
   
        if(count($output['tracks'])>0){
               
             $arr1=$output['tracks']['data'];
				 $arr=json_decode(json_encode($arr1));
				
				// $arr= json_decode($arr1);
				 
				 foreach ($arr as $key=>$value){

				     $query_loc=$this->memberAllDB_model->getTrackMp3s_fem($value->id);
				    //  pArr($query_loc);
					$xx='';
				     foreach($query_loc['data'] as $key1=>$value1){
				        $xx= $value1->location;
				        
				     }
					 if(!empty($xx)){
				     $arr[$key]->location=$xx;
					 }
				   
				 }
				 $output['tracks']['data']=$arr;
        }
   
   
   
   
   
   
		// right side bar
   
		   $output['staffTracks'] = $this->memberAllDB_model->getStaffSelectedTracks_fem(0,4);
   
   
   
		   if($output['staffTracks']['numRows']>0)
   
		   {
   
		   foreach($output['staffTracks']['data'] as $track)
   
		   {
   
   
   
			  $output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays_fem($track->id);
   
			  $row = $this->memberAllDB_model->getClientTrackReview_fem($track->id);
   
			  $output['reviews'][$track->id] = $row['numRows'];
   
   
   
			//  $output['mp3s'][$track->id] = $this->memberAllDB_model->getTrackMp3s_fem($track->id);
			
		   	 $query_track = $this->memberAllDB_model->getTrackMp3s_fem($_GET['tid']);
			     //pArr($query_track);
			     //die();
			     
			     foreach($query_track['data'] as $value){
			         $xx= $value->id;
    			     
    			     if(!in_array($xx,$track_added)){
    
    			         $output['mp3s'][$track->id] = $query_track;
    			         $track_added[]=$xx;
    			     }
    			  
    			  
			     }
   
   
   
		   }
   
		   }
   
   
   
		   /*$output['youTracks'] = $this->memberAllDB_model->getYouSelectedTracks_fem(0,4);
   
   
   
		   if($output['youTracks']['numRows']>0)
   
		   {
   
		   foreach($output['youTracks']['data'] as $track)
   
		   {
   
   
   
			  $output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays_fem($track->id);
   
			  $row = $this->memberAllDB_model->getClientTrackReview_fem($track->id);
   
			  $output['reviews'][$track->id] = $row['numRows'];
   
   
   
			  $output['mp3s'][$track->id] = $this->memberAllDB_model->getTrackMp3s_fem($track->id);
   
   
   
		   }
   
		   } */
   
   
   
		   $output['youTracks'] = $this->memberAllDB_model->getYouSelectedTracks1_fem(Session::get('memberId'),0,4);
   
   
   
   
   
		   if($output['youTracks']['numRows']>0)
   
		   {
   
		   foreach($output['youTracks']['data'] as $track)
   
		   {
   
   
   
			  $output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays_fem($track->id);
   
			  $row = $this->memberAllDB_model->getClientTrackReview_fem($track->id);
   
			  $output['reviews'][$track->id] = $row['numRows'];
   
   
   
			  $output['versions'][$track->id] = $this->memberAllDB_model->getTrackMp3s1_fem($track->id);
   
		   }
   
   
   
		   }
   
		   else
   
		   {
   
   
   
		   $output['youTracks'] = $this->memberAllDB_model->getYouSelectedTracks_fem(0,4);
   
   
   
		   if($output['youTracks']['numRows']>0)
   
		   {
   
		   foreach($output['youTracks']['data'] as $track)
   
		   {
   
   
   
			  $output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays_fem($track->id);
   
			  $row = $this->memberAllDB_model->getClientTrackReview_fem($track->id);
   
			  $output['reviews'][$track->id] = $row['numRows'];
   
   
   
			  $output['versions'][$track->id] = $this->memberAllDB_model->getTrackMp3s_fem($track->id);
   
		   }
   
		   }
   
		   }
   
   
   
   
   
   
   
		   //	$footerOutput['tracks'] = $this->memberAllDB_model->getMemberFooterTracks_fem();
   
   
   
   
   
   
   
				// subscription status
   
			$output['subscriptionStatus'] = 0;
   
			$output['package'] = '';
   
			$subscriptionInfo = $this->memberAllDB_model->getMemberSubscriptionStatus_fem(Session::get('memberId'));
   
			if($subscriptionInfo['numRows']>0)
   
			{
   
			 $output['subscriptionStatus'] = 1;
   
   
   
			 if($subscriptionInfo['data'][0]->package_Id==1)
   
			 {
   
			   $output['packageId'] = 1;
   
			   $output['package'] = 'Silver Subscription';
   
			   // $output['displayDashboard'] = 0;
   
   
   
   
   
			 }
   
			 else if($subscriptionInfo['data'][0]->package_Id==2)
   
			 {
   
			   $output['packageId'] = 2;
   
			   $output['package'] = 'Gold Subscription';
   
			   //$output['displayDashboard'] = 1;
   
   
   
   
   
			 }
   
			 else if($subscriptionInfo['data'][0]->package_Id==3)
   
			 {
   
			   $output['packageId'] = 3;
   
			   $output['package'] = 'Purple Subscription';
   
			   //$output['displayDashboard'] = 1;
   
   
   
   
   
			 }
   
		   }
   
   
   
			if(isset($_GET['reviewAdded']))
   
			   {
   
   
   
				  $output['alert_class'] = 'success-msg';
   
				  $output['alert_message'] = 'Review posted successfully!';
   
   
   
			   }
			   else if(isset($_GET['reviewupdated']))
   
			   {
   
   
   
				  $output['alert_class'] = 'success-msg';
   
				  $output['alert_message'] = 'Additional things requested successfully!';
   
   
   
			   }
   
			   else if(isset($_GET['error']))
   
			   {
   
				  $output['alert_class'] = 'error-msg';
   
				  $output['alert_message'] = 'Error occured, please try again.';
   
			   }
   

   
	//    $this->load->view('header_member.php',$headerOutput);
   
	//    $this->load->view('member_track_share.php',$output);
   
	//    $this->load->view('footer_member1.php',$footerOutput);
	

	   return view('members.dashboard.member_track_review_share', $output);


	}

	/**
	 * FEATURE FIX: Track play count for audio playback
	 * This endpoint is called via AJAX when a member plays a track
	 */
	public function trackPlay(Request $request)
	{
		// Authentication check
		if(empty(Session::get('memberId'))){
			return response()->json(['error' => 'Unauthorized'], 401);
		}

		// Validate input
		$request->validate([
			'mp3Id' => 'required|integer',
			'trackId' => 'required|integer',
		]);

		$memberId = Session::get('memberId');
		$mp3Id = $request->input('mp3Id');
		$trackId = $request->input('trackId');

		// Get country information (optional - can be enhanced with IP geolocation)
		$countryName = $request->input('countryName', 'Unknown');
		$countryCode = $request->input('countryCode', 'XX');

		// Rate limiting: Check if this user played this track in last hour
		$recentPlay = DB::table('track_member_play')
			->where('memberId', $memberId)
			->where('mp3Id', $mp3Id)
			->where('playedDateTime', '>=', DB::raw('DATE_SUB(NOW(), INTERVAL 1 HOUR)'))
			->first();

		if ($recentPlay) {
			// Don't increment if played within last hour (prevents spam)
			return response()->json([
				'success' => true,
				'message' => 'Play already recorded recently'
			]);
		}

		// Increment play count
		$result = $this->memberAllDB_model->playIncrement($mp3Id, $trackId, $memberId, $countryName, $countryCode);

		if ($result) {
			return response()->json([
				'success' => true,
				'message' => 'Play count incremented'
			]);
		} else {
			return response()->json([
				'error' => 'Failed to record play'
			], 500);
		}
	}

	public function Download_member_track(Request $request)
	{
        //echo $_SERVER['DOCUMENT_ROOT'];die;

		// header data pass starts

		if(empty(Session::get('memberId'))){ 
			return redirect()->intended('login');
		}

		$output = array();

		$logo_data = array(
			'logo_id' => 1,
			);

		$logo_details = DB::table('website_logo')
		->where($logo_data)
		->first();  
		
		$get_logo = $logo_details->logo;

		 $output['pageTitle'] = 'Member Track Download';
		 $output['logo'] = $get_logo;
	   
		// fb logout link
	   //  $this->load->library('facebook');
	   //  $logout_link = url('Logout');

	   //  if (isset($_SESSION['fb_access_token'])) {
	   // 	 $logout_link = $this->facebook->logout_url();
	   //  }
	   //  $output['logout_link'] = $logout_link;


	   // header data pass ends here and again continue at "#moreHeaderData"

	   $memberId_from_session = Session::get('memberId');



        if (isset($_GET['mp3Id'])) {
            $countryCode = '';
            $countryName = '';

            // get user location details:
            //	function getLocationInfoByIp(){

            $client  = @$_SERVER['HTTP_CLIENT_IP'];
            $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
            $remote  = @$_SERVER['REMOTE_ADDR'];
            $result  = array('country' => '', 'city' => '');

            if (filter_var($client, FILTER_VALIDATE_IP)) {

                $ip = $client;
            } else if (filter_var($forward, FILTER_VALIDATE_IP)) {

                $ip = $forward;
            } else {
                $ip = $remote;
            }

            $ip_data = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));

            if ($ip_data && $ip_data->geoplugin_countryName != null) {
                $countryCode = $ip_data->geoplugin_countryCode;
                $countryName = $ip_data->geoplugin_countryName;
            }

            // }
            $result = $this->memberAllDB_model->downloadIncrement($_GET['mp3Id'], $_GET['trackId'], $memberId_from_session, $countryName, $countryCode);
        }
        
        if(isset($_GET['pcloud']) && $_GET['pcloud'] == true){
           // $path = $_SERVER["DOCUMENT_ROOT"];
		   //$path = url("/vendor/pcloud/pcloud-php-sdk/lib/pCloud/autoload.php");
           // require_once($path."/pcloud-sdk/autoload.php");
		 	// require_once($path);
		   //dd($path);
// dd(base_path());
        try {
		   $path = base_path() . '/vendor/pcloud/pcloud-php-sdk/lib/pCloud/autoload.php';
		   require_once($path);
            $pCloudFile = new pCloud\File();
            
            $fileID = (int)$_GET['track'];
            $filename = $pCloudFile->getLink($fileID);
			$file_path =$filename;
            $fileInfo = $pCloudFile->getInfo($fileID);
            $name = $fileInfo->metadata->name;
			$fsize = $fileInfo->metadata->size;

			// header('Content-Type: audio/mpeg');
			// header('Content-Disposition: inline;filename="'.$name.'"');
			// header('Content-length: '.$fsize);
			// header('Cache-Control: no-cache');
			// header("Content-Transfer-Encoding: chunked"); 
			// echo file_get_contents($file_path);
			
			// return response()->streamDownload(function () {
				// echo file_get_contents($file_path);
			// }, $name);
	
			// $c = curl_init();
			// curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
			// curl_setopt($c, CURLOPT_URL, $filename);
			// $data = curl_exec($c);
			// curl_close($c);
				if(!empty($_GET['title'])){

		    		$name = $_GET['title'] ;
		    		
		    		$name=str_replace('-',' ',$name);
			
				if(strpos($name, '.mp3')== false){
				    $name= $_GET['title'].".mp3";
				    
				}
				else{
				    $name=$_GET['title'];
				    
				}
				

			}
			else{

					$name = uniqid(rand()).$name;

			}
		
			$downloadFile = fopen($_SERVER['DOCUMENT_ROOT'].'/AUDIO_new/'.$name, "w");
			$handle = curl_init($file_path);
			// Tell cURL to write contents to the file.
			curl_setopt($handle, CURLOPT_FILE, $downloadFile);
			// Follow redirects.
			curl_setopt($handle, CURLOPT_FOLLOWLOCATION, true);
			// Do the request.
			curl_exec($handle);
			// Clean up.
			curl_close($handle);
			
			return response()->download($_SERVER['DOCUMENT_ROOT'].'/AUDIO_new/'.$name, $name)->deleteFileAfterSend(true);
        } catch (\Exception $e) {

                return 'Sorry! '.$e->getMessage();
            }	
           // $data = file_get_contents($filename);
		  // return \Storage::download($file_path, $name);
		   
		   //return Redirect::to($file_path);   exit;
		
		//   $file_pcloud = basename($file_path);
		//   header("Content-disposition:attachment; filename=$name");
		//   readfile($file_path);
		
			
        } else {
			$file_path =base_path('AUDIO/' . $_GET['track']);
			if(file_exists($file_path)){
			try {
            $data = file_get_contents(base_path('AUDIO/' . $_GET['track']));
			$title = "track";
			if(!empty($_GET['title'])){

				$name = $_GET['title'] . "." . strtolower(substr(strrchr($_GET['track'], '.'), 1));

			}
			else{

				$name = $title . "." . strtolower(substr(strrchr($_GET['track'], '.'), 1));

			}
           
            $name = str_replace(' ', '_', $name);
            $name = str_replace('"', '', $name);
        	$name=str_replace('-',' ',$name);
            
            
            	
				if(strpos($name, '.mp3')== false){
				    $name= $name.".mp3";
				    
				}
				else{
				    $name=$name;
				    
				}
            
			// force download
			return response()->download($file_path, $name);
			} catch (\Exception $e) {

                return 'Sorry! '.$e->getMessage();
            }
            
             }else{
                 echo "Sorry! The requested file doesn't exist on the server.";
             }
        }

}
function paypal_buy_digicoins($type)
{
	//dd("fdfd");


	//Set variables for paypal form
	// $cancelURL = url('/').'Paypal/cancel'; //payment cancel url
	// $notifyURL = url('/').'Paypal_digicoins_ipn'; //ipn url
	// $returnURL = url('/').'Paypal_digicoins_response'; //payment success url
	
	// $product['id'] = Session::get('digicoin_package_id');
	// $product['name'] = Session::get('digicoin_package_tittle');
	// $product['price'] =  Session::get('digicoin_package_price');

	// $product['id'] = $_COOKIE['digicoin_package_id'];
	// $product['name'] =  $_COOKIE['digicoin_package_tittle'];
	// $product['price'] =  $_COOKIE['digicoin_package_price'];
	// $logo = url('/').'assets/img/logo.png';
	
	
	// $this->paypal_lib->add_field('return', $returnURL);
	// $this->paypal_lib->add_field('cancel_return', $cancelURL);
	// $this->paypal_lib->add_field('notify_url', $notifyURL);
	// $this->paypal_lib->add_field('item_name', $product['name']);
	// $this->paypal_lib->add_field('item_number',  $product['id']);
	// $this->paypal_lib->add_field('amount',  $product['price']);		
	// $this->paypal_lib->image($logo);
	
	// // member
	// if($type==2)	
	// {
	// $this->paypal_lib->add_field('userType', 4);
	// $this->paypal_lib->add_field('custom', $_COOKIE['buyId'].'_4');
	// } 
	// else if($type==1) // client
	// { 
	// $this->paypal_lib->add_field('userType', 3);
	// $this->paypal_lib->add_field('custom', $_COOKIE['buyId'].'_3');
	// }
//	$this->paypal_lib->paypal_auto_form();
	

	// get data to pass paypal
	$product_id = $_COOKIE['digicoin_package_id'];
	$user_type = $type;
	setcookie('user_type_pay', $user_type, 0, "/");
	Session::put('user_type_pay', $user_type);
	
	$cart = $this->getCheckoutData($product_id);

	$p_proceed_flag = FALSE;
	$paypal_link = '';

	$provider = new ExpressCheckout;

		//dd(route('ipn_notify_url'));
		//$p_response = $this->provider->setNotifyURL(route('ipn_notify_url'))->setExpressCheckout($cart, FALSE);
		$response = $provider->setExpressCheckout($cart);
		$response = $provider->setExpressCheckout($cart, true);
		// dd($response);
		if(in_array(strtoupper($response['ACK']), ['SUCCESS', 'SUCCESSWITHWARNING'])){

			if(!empty($response['paypal_link'])){
				$p_proceed_flag = TRUE;
				$paypal_link = $response['paypal_link'];
				if (!session()->has('paypal_link')) {
					session()->put(['paypal_link' => $paypal_link]);
				}
			}
			
		}
		else{

			$full_err_msg = $response['L_LONGMESSAGE0'];
			$short_err_msg = $response['L_SHORTMESSAGE0'];


		}
	
		

	if($p_proceed_flag == TRUE){
		
		return Redirect::away($response['paypal_link']);
		//return redirect($response['paypal_link']);
	
	}
	else{
	

		return redirect()->route('Paypal_cancel')
			->withInput()
			->with('return_status', FALSE) // send back with flashed session data
			->with('return_message', $full_err_msg) // send back with flashed session data
			->with('return_data', array()); // send back with flashed session data
	}



}


function getCheckoutData($product_id = NULL)
{
	$data = [];
	if(!empty($product_id)){

		$product_name =  $_COOKIE['digicoin_package_tittle'];
		$product_price =  $_COOKIE['digicoin_package_price'];

		if (isset($_COOKIE['user_type_pay'])) {

			$user_type_pay = $_COOKIE['user_type_pay'];
		
		}
		else{
			
			$user_type_pay = Session::get('user_type_pay');
		}
		


		$data['items'] = [
			[
				'name'  => $product_name,
				'price' => $_COOKIE['digicoin_package_price'],
				'qty'   => 1,
			],
		]; 

		// member
		if($user_type_pay==2)	
		{

			$data['invoice_id'] = $_COOKIE['buyId'].'_4';     // must be unique for every transaction
						$data['userType'] = 4;
	
		} 
		else if($user_type_pay==1) // client
		{ 

			$data['invoice_id'] = $_COOKIE['buyId'].'_3';    // must be unique for every transaction 
						$data['userType'] = 3;
		
		}
		$data['custom'] = $product_id; 


		//dd($user_type_pay);


		// $ran_number = md5(rand(1000,10000));
		// $date_time_array = getdate(time());
		// $current_time =	$date_time_array["year"].$date_time_array["mon"].$date_time_array["mday"].$date_time_array["hours"].$date_time_array["minutes"].$date_time_array["seconds"];
	
	
		$data['total'] = $product_price;
		//	$data['invoice_id'] = $product_id.'_'.$ran_number.'_'.$current_time;  // must be unique for every transaction
		$data['invoice_description'] = "Order #$product_id Payment"; 
		$data['return_url'] = route('paypal_member_success');
		$data['cancel_url'] = route('Paypal_cancel');
		



	}
	// dd($data);
	// echo '<br>getCheckoutData: '.json_encode($data);
	return $data;
}

function Paypal_cancel(){
	
	return view('members.dashboard.paypal_cancel');
 }


 function paypal_member_success(Request $request){

			// header data pass starts

				if(empty(Session::get('memberId'))){ 
					return redirect()->intended('login');
				}

				$output = array();

				$logo_data = array(
					'logo_id' => 1,
					);

				$logo_details = DB::table('website_logo')
				->where($logo_data)
				->first();  
				
				$get_logo = $logo_details->logo;

				$output['pageTitle'] = 'Digiwax Member Payment';
				$output['logo'] = $get_logo;
			

			// header data pass ends here and again continue at "#moreHeaderData"

			 $memberId_from_session = Session::get('memberId');
			 $subscriptionId = Session::get('subscriptionId');
			 $output['wrapperClass'] = 'register';
			 $provider = new ExpressCheckout;


			// date_default_timezone_set('America/Los_Angeles');

			$token = $request->get('token');
			$PayerID = $request->get('PayerID');

			// Verify Express Checkout Token
			$response = $provider->getExpressCheckoutDetails($token);


			$product_id    = $response['PAYMENTREQUEST_0_CUSTOM'];
			Session::put('memberPackage', $product_id);
			$output['item_number'] = $product_id; 
			$cart = $this->getCheckoutData($product_id);

			// dd($response);

			if (in_array(strtoupper($response['ACK']), ['SUCCESS', 'SUCCESSWITHWARNING'])) {

				// Perform transaction on PayPal
				$paypalInfo = $provider->doExpressCheckoutPayment($cart, $token, $PayerID);
				
				// dd($paypalInfo);
				if (in_array(strtoupper($paypalInfo['ACK']), ['SUCCESS', 'SUCCESSWITHWARNING'])) {
	
					$output['txn_id'] = $paypalInfo["PAYMENTINFO_0_TRANSACTIONID"];
					$output['payment_amt'] = $paypalInfo["PAYMENTINFO_0_AMT"];
					$output['currency_code'] = $paypalInfo["PAYMENTINFO_0_CURRENCYCODE"];
					$output['status'] = $paypalInfo["PAYMENTINFO_0_PAYMENTSTATUS"];
					$output['error_code'] = $paypalInfo["PAYMENTINFO_0_ERRORCODE"];




					// storing response and sned mail starts here

					$token_response = $request->get('token');
					$PayerID_response = $request->get('PayerID');
	
					// Verify Express Checkout Token
					$paypalInfo_response = $provider->getExpressCheckoutDetails($token_response);
					// dd($paypalInfo_response);
	
					$product_id    = $paypalInfo_response['PAYMENTREQUEST_0_CUSTOM'];
					$subId = explode('_',$paypalInfo_response['INVNUM']);
	
					// Note: $subId[0] contains subsciption id or buy id and  $subId[1] conatins user type
					// usertype == 1 (client pkg), usertype == 2 (member pkg), usertype == 3 (client digicoins), usertype == 4 (member digicoins) 
	
					$data['product_id'] = $product_id; 	
					$data['subscriptionId'] = $subId[0];
					$data['buyId'] = $subId[0];
					$data['txn_id']	= $paypalInfo_response["PAYMENTREQUEST_0_TRANSACTIONID"];
					$data['payment_gross'] = $paypalInfo_response["PAYMENTREQUEST_0_AMT"];
					$data['currency_code'] = $paypalInfo_response["PAYMENTREQUEST_0_CURRENCYCODE"];
					$data['payer_email'] = $paypalInfo_response["PAYMENTREQUEST_0_SELLERPAYPALACCOUNTID"];
					$data['item_name']	= $paypalInfo_response["L_NAME0"];
	
					// if($paypalInfo_response["CHECKOUTSTATUS"] =='PaymentActionCompleted' || $paypalInfo_response["ACK"] =='Success' || $paypalInfo_response["ACK"] == 'SUCCESSWITHWARNING'){
					// 	$data['payment_status']	= 'Completed'; 
					// }
					// else{
					// 	$data['payment_status']	= 'Pending'; 
					// }

					$data['payment_status'] = $paypalInfo["PAYMENTINFO_0_PAYMENTSTATUS"];
					
	
	
					// member pkg
					if($subId['1']==2)
					{
							
				
					// $paypalURL = $this->paypal_lib->paypal_url;		
					// $result	= $this->paypal_lib->curlPost($paypalURL,$paypalInfo_response);
					
					//insert the transaction data into the database
					$transaction =  $this->memberAllDB_model->insertMemberTransaction($data);
						
						//	email
						if($transaction>0)
						{
	
							$package = $data['item_name'];
							$amount = $data['payment_gross']. ' $';
						
					
							$memberInfo = $this->memberAllDB_model->getPaypalMemberInfo($data['txn_id']); 

							if(!empty($memberInfo['data'][0]->email)){
								$email = urldecode($memberInfo['data'][0]->email);
		
								// send mail starts
								if(!empty($email)){
		
									
										// $email ='school_test007@yopmail.com';
									
										$m_sub = 'Member paypal payment confirmation at Digiwaxx';
										$name = urldecode($memberInfo['data'][0]->name);
		
										$message = 'Hi '.$name.',<br />
										<p>'.$package.' Package</p><p>Your payment of '.$amount.' is successfully done.</p>';
						
										$mail_data = [
											'm_sub' => $m_sub,
											'm_msg' => $message,
										];
		
											// $sendInvoiceMail = Mail::to($email);
											// $sendInvoiceMail->send(new AdminForgetNotification($mail_data));
						
										
								}
								// send mail ends
							}	
	
	
						}
			
					
					
					}
	
					elseif($subId['1']==1) // client pkg
					{
					// 	$paypalURL = $this->paypal_lib->paypal_url;		
					// $result	= $this->paypal_lib->curlPost($paypalURL,$paypalInfo_response);
						
						$this->memberAllDB_model->insertTransaction($data);
			
						$package = $data['item_name'];
						$amount = $data['payment_gross']. ' $';
		
						
						//	email
						$clientInfo = $this->memberAllDB_model->getPaypalClientInfo($data['txn_id']); 

						if(!empty($clientInfo['data'][0]->email)){	
							$email = urldecode($clientInfo['data'][0]->email);
		
							// send mail starts
							if(!empty($email)){
		
			
								// $email ='school_test007@yopmail.com';
							
								$m_sub = 'Client paypal payment confirmation at Digiwaxx';
								$name = urldecode($clientInfo['data'][0]->name);
		
								$message = 'Hi '.$name.',<br />
								<p>'.$package.' Package</p><p>Your payment of '.$amount.' is successfully done.</p>';	
				
									$mail_data = [
										'm_sub' => $m_sub,
										'm_msg' => $message,
									];
		
									// $sendInvoiceMail = Mail::to($email);
									// $sendInvoiceMail->send(new AdminForgetNotification($mail_data));
				
								
						}
						// send mail ends
					}	
				
					
					}
					else if($subId['1']==3) // client digicoins
					{
					
						
						$this->memberAllDB_model->insertClientDigicoinsTransaction($data);
						
						if(strcmp($data['payment_gross'],'750.00')==0) 
						{
							$package = 'ADVANCED'; $amount = '750 $';
						}
						else if(strcmp($data['payment_gross'],'500.00')==0) 
						{
							$package = 'BASIC'; $amount = '500 $';
						}
						
						//	email
						$clientInfo = $this->memberAllDB_model->getPaypalClientInfo($data['txn_id']); 
						
						if(!empty($clientInfo['data'][0]->email)){
								$email = urldecode($clientInfo['data'][0]->email);
							
			
								// send mail starts
								if(!empty($email)){
			
			
									// $email ='school_test007@yopmail.com';
								
									$m_sub = 'Client paypal payment for digicoins confirmation at Digiwaxx';
			
									$name = urldecode($clientInfo['data'][0]->name);
									$message = 'Hi '.$name.',<br />
												<p>Digicoins purchased : '.$amount.'</p>';
					
										$mail_data = [
											'm_sub' => $m_sub,
											'm_msg' => $message,
										];
			
										// $sendInvoiceMail = Mail::to($email);
										// $sendInvoiceMail->send(new AdminForgetNotification($mail_data));
					
									
							}
							// send mail ends
						}
				
					
					
					}
					else if($subId['1']==4) // member digicoins
					{
					
					
						//insert the transaction data into the database
						$transaction =  $this->memberAllDB_model->insertMemberDigicoinsTransaction($data);
						
						//	email

						$memberInfo = $this->memberAllDB_model->getPaypalMemberInfo($data['txn_id']); 
						//dd($memberInfo);
						if(!empty($memberInfo['data'][0]->email)){

							$email = urldecode($memberInfo['data'][0]->email);
	
							// send mail starts
							if(!empty($email)){
		
		
								// $email ='school_test007@yopmail.com';
							
								$m_sub = 'Member paypal payment for digicoins confirmation at Digiwaxx';
		
								$name = urldecode($clientInfo['data'][0]->name);
								$message = 'Hi '.$name.',<br />
										   <p>Digicoins purchased : '.$amount.'</p>';
				
									$mail_data = [
										'm_sub' => $m_sub,
										'm_msg' => $message,
									];
		
									// $sendInvoiceMail = Mail::to($email);
									// $sendInvoiceMail->send(new AdminForgetNotification($mail_data));
				
								
							}
						 // send mail ends

						}
					
	
						
					}
				// storing response and send mail ends here


						$output['subscriptionStatus'] = 0;
						$output['package'] = '';
						$result = $this->memberAllDB_model->getMemberSubscriptionInfo_fem($memberId_from_session,$subscriptionId); 
				
							if($result['numRows']==1)
							{
									$output['subscriptionStatus'] = 1;
								//  $output['displayDashboard'] = 0;
							
									if($result['data'][0]->package_Id==2)
									{
									
									$output['packageId'] = 2;
									$output['package'] = 'GOLD';
									}
									else if($result['data'][0]->package_Id==3)
									{
									
									$output['packageId'] = 3;
									$output['package'] = 'PURPLE';
								//  $output['displayDashboard'] = 1;
									}
							}

							if (isset($_COOKIE['digicoin_package_tittle'])) {
			
								unset($_COOKIE['digicoin_package_tittle']);	
							}
							if (isset($_COOKIE['digicoin_package_price'])) {
								
								unset($_COOKIE['digicoin_package_price']);	
							}
							if (isset($_COOKIE['buyId'])) {
								
								unset($_COOKIE['buyId']);	
							}
							if (isset($_COOKIE['digicoin_package_id'])) {
								
								unset($_COOKIE['digicoin_package_id']);	
							}
							if (isset($_COOKIE['user_type_pay'])) {
								
								unset($_COOKIE['user_type_pay']);	
							}
		
		
						   return view('members.dashboard.member_paypal_success', $output);
		
			

				} // end of payment success inner
			else{


				return view('members.dashboard.paypal_cancel');
			}



			} // end of payment success outer

			else{


				return view('members.dashboard.paypal_cancel');
			}

	   
	}


 function paypal_buy_client($type)
 {
		// 		$userID = 1; //current user id
		
		// 		$returnURL = base_url().'Paypal/client_success'; //payment success url
				
		// //		 $result = $this->frontenddb->getClientSubscriptionInfo($_SESSION['clientId'],$_SESSION['subscriptionId']);
		// 		 $result = $this->frontenddb->getSubscriptionDetails($_SESSION['subscriptionId']);
		
		// 		  if($result['numRows']==1)
		// 		  {
				
		// 				 if($result['data'][0]->packageId==1)
		// 				 {
		// 				  $product['id'] = $result['data'][0]->packageId;
		// 				  $product['price'] = '350';
		// 				  $product['name'] = 'BASIC';
		// 				 }
		// 				 elseif($result['data'][0]->packageId==2)
		// 				 {
		// 				  $product['id'] = $result['data'][0]->packageId;
		// 				  $product['price'] = '500';
		// 				  $product['name'] = 'REGULAR';
		// 				 }
		// 				 elseif($result['data'][0]->packageId==3)
		// 				 {
		// 					 $product['id'] = $result['data'][0]->packageId;
		// 					 $product['price'] = '750';
		// 					 $product['name'] = 'STANDARD';
		// 				 }
		// 				 elseif ($result['data'][0]->packageId==4)
		// 				 {
		// 					 $product['id'] = $result['data'][0]->packageId;
		// 					 $product['price'] = '1000';
		// 					 $product['name'] = 'ADVANCE';
		// 				 }
				
		// 		 // for payment testing 
		// 				$logo = base_url().'assets/img/logo.png';
				
				
				
		// 		$this->paypal_lib->add_field('return', $returnURL);
		// 		$this->paypal_lib->add_field('cancel_return', $cancelURL);
		// 		$this->paypal_lib->add_field('notify_url', $notifyURL);
		// 		$this->paypal_lib->add_field('item_name', $product['name']);
		// 		$this->paypal_lib->add_field('custom', $_SESSION['subscriptionId'].'_1');
		// 		$this->paypal_lib->add_field('userType', 4);
		// 		$this->paypal_lib->add_field('item_number',  $product['id']);
		// 		$this->paypal_lib->add_field('amount',  $product['price']);		
		// 		$this->paypal_lib->image($logo);
	
		//$this->paypal_lib->paypal_auto_form();
	 

	 // get data to pass paypal
	 $subscriptionId = $_COOKIE['subscriptionId'];
	 $user_type = $type;
	 setcookie('user_type_pay_client', $user_type, 0, "/");
	 Session::put('user_type_pay_client', $user_type);
	 $cart = $this->getCheckoutData_client($subscriptionId);

	 $p_proceed_flag = FALSE;
	 $paypal_link = '';

	 $provider = new ExpressCheckout;

		 //dd(route('ipn_notify_url'));
		 //$p_response = $this->provider->setNotifyURL(route('ipn_notify_url'))->setExpressCheckout($cart, FALSE);
		 $response = $provider->setExpressCheckout($cart);
		 $response = $provider->setExpressCheckout($cart, true);
		//  dd($response);
		 if(in_array(strtoupper($response['ACK']), ['SUCCESS', 'SUCCESSWITHWARNING'])){

			 if(!empty($response['paypal_link'])){
				 $p_proceed_flag = TRUE;
				 $paypal_link = $response['paypal_link'];
				 if (!session()->has('paypal_link')) {
					 session()->put(['paypal_link' => $paypal_link]);
				 }
			 }
			 
		 }
		 else{

			 $full_err_msg = $response['L_LONGMESSAGE0'];
			 $short_err_msg = $response['L_SHORTMESSAGE0'];


		 }
	 
		 

	 if($p_proceed_flag == TRUE){
		 
		return Redirect::away($response['paypal_link']);
		// return redirect($response['paypal_link']);
	 
	 }
	 else{
	 

		 return redirect()->route('Paypal_cancel')
			 ->withInput()
			 ->with('return_status', FALSE) // send back with flashed session data
			 ->with('return_message', $full_err_msg) // send back with flashed session data
			 ->with('return_data', array()); // send back with flashed session data
	 }



 }

 function getCheckoutData_client($subscriptionId = NULL)
 {
	 $data = [];
	 if(!empty($subscriptionId)){
	
		$result = $this->memberAllDB_model->getSubscriptionDetails_paypal($subscriptionId);
		
				  if($result['numRows']==1)
				  {
				  
						 if($result['data'][0]->packageId==1)
						 {
						  $product['id'] = $result['data'][0]->packageId;
						  $product['price'] = '350';
						  $product['name'] = 'BASIC';
						 }
						 elseif($result['data'][0]->packageId==2)
						 {
						  $product['id'] = $result['data'][0]->packageId;
						  $product['price'] = '500';
						  $product['name'] = 'REGULAR';
						 }
						 elseif($result['data'][0]->packageId==3)
						 {
							 $product['id'] = $result['data'][0]->packageId;
							 $product['price'] = '750';
							 $product['name'] = 'STANDARD';
						 }
						 elseif ($result['data'][0]->packageId==4)
						 {
							 $product['id'] = $result['data'][0]->packageId;
							 $product['price'] = '1000';
							 $product['name'] = 'ADVANCE';
						 }


							 if (isset($_COOKIE['user_type_pay_client'])) {

								$user_type_pay_client = $_COOKIE['user_type_pay_client'];
							
							}
							else{
								
								$user_type_pay_client = Session::get('useruser_type_pay_client_type_pay');
							}
							 $id_unique = $product['id'];
							 //dd($result);

						// for testing price 
						// $product['price'] = 1;

						$data['items'] = [
							[
								'name'  => $product['name'],
								'price' => $product['price'],
								'qty'   => 1,
							],
						]; 
		
		 

						$data['invoice_id'] = $_COOKIE['subscriptionId'].'_1';
						$data['userType'] = 4;
						$data['custom'] = $product['id'];
		 
		

					// $ran_number = md5(rand(1000,10000));
					// $date_time_array = getdate(time());
					// $current_time =	$date_time_array["year"].$date_time_array["mon"].$date_time_array["mday"].$date_time_array["hours"].$date_time_array["minutes"].$date_time_array["seconds"];


					$data['total'] = $product['price'];
				//$data['invoice_id'] = $product['id'].'_'.$ran_number.'_'.$current_time;
					$data['invoice_description'] = "Order #$id_unique Payment"; 
					$data['return_url'] = route('paypal_client_success');
					$data['cancel_url'] = route('Paypal_cancel');
		 

		}
 
	 }
	 // dd($data);
	 // echo '<br>getCheckoutData: '.json_encode($data);
	 return $data;
}

function paypal_client_success(Request $request){

				if(empty(Session::get('clientId'))){
					return redirect()->intended('login');
				}

				$output = array();

				$logo_data = array(
					'logo_id' => 1,
					);

				$logo_details = DB::table('website_logo')
				->where($logo_data)
				->first();  
				
				$get_logo = $logo_details->logo;

				$output['logo'] = $get_logo;
				
				
				$clientId = Session::get('clientId');
				$output['sessClientID'] = $clientId;
		
		
			//	date_default_timezone_set('America/Los_Angeles');

		
				//  header data ends here
			

		

			$clientId = Session::get('clientId');
			//$subscriptionId = Session::get('subscriptionId');
			$subscriptionId = $_COOKIE['subscriptionId'];
			$output['wrapperClass'] = 'register';
			$provider = new ExpressCheckout;


			$token = $request->get('token');
			$PayerID = $request->get('PayerID');

			// Verify Express Checkout Token
			$response = $provider->getExpressCheckoutDetails($token);
			$product_id    = $response['PAYMENTREQUEST_0_CUSTOM'];
			Session::put('memberPackage', $product_id);
			$output['item_number'] = $product_id; 
			$cart = $this->getCheckoutData_client($product_id);

			// dd($response);

			if (in_array(strtoupper($response['ACK']), ['SUCCESS', 'SUCCESSWITHWARNING'])) {

				// Perform transaction on PayPal
				$paypalInfo = $provider->doExpressCheckoutPayment($cart, $token, $PayerID);
				
				// dd($paypalInfo);
				if (in_array(strtoupper($paypalInfo['ACK']), ['SUCCESS', 'SUCCESSWITHWARNING'])) {
	
					$output['txn_id'] = $paypalInfo["PAYMENTINFO_0_TRANSACTIONID"];
					$output['payment_amt'] = $paypalInfo["PAYMENTINFO_0_AMT"];
					$output['currency_code'] = $paypalInfo["PAYMENTINFO_0_CURRENCYCODE"];
					$output['status'] = $paypalInfo["PAYMENTINFO_0_PAYMENTSTATUS"];
					$output['error_code'] = $paypalInfo["PAYMENTINFO_0_ERRORCODE"];

					// storing response and sned mail starts here

					$token_response = $request->get('token');
					$PayerID_response = $request->get('PayerID');
	
					// Verify Express Checkout Token
					$paypalInfo_response = $provider->getExpressCheckoutDetails($token_response);
					// dd($paypalInfo_response);
	
					$product_id    = $paypalInfo_response['PAYMENTREQUEST_0_CUSTOM']; 
					$subId = explode('_',$paypalInfo_response['INVNUM']);
	
					// Note: $subId[0] contains subsciption id or buy id and  $subId[1] conatins user type
					// usertype == 1 (client pkg), usertype == 2 (member pkg), usertype == 3 (client digicoins), usertype == 4 (member digicoins) 
	
					$data['product_id'] = $product_id; 	
					$data['subscriptionId'] = $subId[0];
					$data['buyId'] = $subId[0];
					$data['txn_id']	= $paypalInfo_response["PAYMENTREQUEST_0_TRANSACTIONID"];
					$data['payment_gross'] = $paypalInfo_response["PAYMENTREQUEST_0_AMT"];
					$data['currency_code'] = $paypalInfo_response["PAYMENTREQUEST_0_CURRENCYCODE"];
					$data['payer_email'] = $paypalInfo_response["PAYMENTREQUEST_0_SELLERPAYPALACCOUNTID"];
					$data['item_name']	= $paypalInfo_response["L_NAME0"];
	
					// if($paypalInfo_response["CHECKOUTSTATUS"] =='PaymentActionCompleted' || $paypalInfo_response["ACK"] =='Success' || $paypalInfo_response["ACK"] == 'SUCCESSWITHWARNING'){
					// 	$data['payment_status']	= 'Completed'; 
					// }
					// else{
					// 	$data['payment_status']	= 'Pending'; 
					// }

					$data['payment_status'] = $paypalInfo["PAYMENTINFO_0_PAYMENTSTATUS"];
					
	
	
					// member pkg
					if($subId['1']==2)
					{
							
				
					// $paypalURL = $this->paypal_lib->paypal_url;		
					// $result	= $this->paypal_lib->curlPost($paypalURL,$paypalInfo_response);
					
					//insert the transaction data into the database
					$transaction =  $this->memberAllDB_model->insertMemberTransaction($data);
						
						//	email
						if($transaction>0)
						{
	
							$package = $data['item_name'];
							$amount = $data['payment_gross']. ' $';
						
					
							$memberInfo = $this->memberAllDB_model->getPaypalMemberInfo($data['txn_id']); 

							if(!empty($memberInfo['data'][0]->email)){
								$email = urldecode($memberInfo['data'][0]->email);
		
								// send mail starts
								if(!empty($email)){
		
									
										// $email ='school_test007@yopmail.com';
									
										$m_sub = 'Member paypal payment confirmation at Digiwaxx';
										$name = urldecode($memberInfo['data'][0]->name);
		
										$message = 'Hi '.$name.',<br />
										<p>'.$package.' Package</p><p>Your payment of '.$amount.' is successfully done.</p>';
						
										$mail_data = [
											'm_sub' => $m_sub,
											'm_msg' => $message,
										];
		
											// $sendInvoiceMail = Mail::to($email);
											// $sendInvoiceMail->send(new AdminForgetNotification($mail_data));
						
										
								}
								// send mail ends
							}	
	
	
						}
			
					
					
					}
	
					elseif($subId['1']==1) // client pkg
					{
					// 	$paypalURL = $this->paypal_lib->paypal_url;		
					// $result	= $this->paypal_lib->curlPost($paypalURL,$paypalInfo_response);
						
						$this->memberAllDB_model->insertTransaction($data);
			
						$package = $data['item_name'];
						$amount = $data['payment_gross']. ' $';
		
						
						//	email
						$clientInfo = $this->memberAllDB_model->getPaypalClientInfo($data['txn_id']); 

						if(!empty($clientInfo['data'][0]->email)){	

							$email = urldecode($clientInfo['data'][0]->email);
		
							// send mail starts
							if(!empty($email)){
		
			
								// $email ='school_test007@yopmail.com';
							
								$m_sub = 'Client paypal payment confirmation at Digiwaxx';
								$name = urldecode($clientInfo['data'][0]->name);
		
								$message = 'Hi '.$name.',<br />
								<p>'.$package.' Package</p><p>Your payment of '.$amount.' is successfully done.</p>';	
				
									$mail_data = [
										'm_sub' => $m_sub,
										'm_msg' => $message,
									];
		
									// $sendInvoiceMail = Mail::to($email);
									// $sendInvoiceMail->send(new AdminForgetNotification($mail_data));
				
								
						}
						// send mail ends
					}	
				
					
					}
					else if($subId['1']==3) // client digicoins
					{
					
						
						$this->memberAllDB_model->insertClientDigicoinsTransaction($data);
						
						if(strcmp($data['payment_gross'],'750.00')==0) 
						{
							$package = 'ADVANCED'; $amount = '750 $';
						}
						else if(strcmp($data['payment_gross'],'500.00')==0) 
						{
							$package = 'BASIC'; $amount = '500 $';
						}
						
						//	email
						$clientInfo = $this->memberAllDB_model->getPaypalClientInfo($data['txn_id']); 
						
						if(!empty($clientInfo['data'][0]->email)){
								$email = urldecode($clientInfo['data'][0]->email);
							
			
								// send mail starts
								if(!empty($email)){
			
			
									// $email ='school_test007@yopmail.com';
								
									$m_sub = 'Client paypal payment for digicoins confirmation at Digiwaxx';
			
									$name = urldecode($clientInfo['data'][0]->name);
									$message = 'Hi '.$name.',<br />
												<p>Digicoins purchased : '.$amount.'</p>';
					
										$mail_data = [
											'm_sub' => $m_sub,
											'm_msg' => $message,
										];
			
										// $sendInvoiceMail = Mail::to($email);
										// $sendInvoiceMail->send(new AdminForgetNotification($mail_data));
					
									
							}
							// send mail ends
						}
				
					
					
					}
					else if($subId['1']==4) // member digicoins
					{
					
					
						//insert the transaction data into the database
						$transaction =  $this->memberAllDB_model->insertMemberDigicoinsTransaction($data);
						
						//	email

						$memberInfo = $this->memberAllDB_model->getPaypalMemberInfo($data['txn_id']); 
						//dd($memberInfo);
						if(!empty($memberInfo['data'][0]->email)){

							$email = urldecode($memberInfo['data'][0]->email);
	
							// send mail starts
							if(!empty($email)){
		
		
								// $email ='school_test007@yopmail.com';
							
								$m_sub = 'Member paypal payment for digicoins confirmation at Digiwaxx';
		
								$name = urldecode($clientInfo['data'][0]->name);
								$message = 'Hi '.$name.',<br />
										   <p>Digicoins purchased : '.$amount.'</p>';
				
									$mail_data = [
										'm_sub' => $m_sub,
										'm_msg' => $message,
									];
		
									// $sendInvoiceMail = Mail::to($email);
									// $sendInvoiceMail->send(new AdminForgetNotification($mail_data));
				
								
							}
						 // send mail ends

						}
					
	
						
					}
					// storing response and send mail ends here

				
						$output['subscriptionStatus'] = 0;
						$output['package'] = '';
			

									$result = $this->memberAllDB_model->getClientSubscriptionInfo_cl_paypal($clientId,$_COOKIE['subscriptionId']); 
					
									if($result['numRows']==1)
								{
								$output['subscriptionStatus'] = 1;
								$output['displayDashboard'] = 0;
								
										if($result['data'][0]->packageId==1)
										{
										$output['packageId'] = 1;
										$output['package'] = 'BASIC';
										}
										elseif($result['data'][0]->packageId==2)
										{
										$output['packageId'] = 2;
										$output['package'] = 'ADVANCED';
										$output['displayDashboard'] = 1;
										}
								}
						
							
								if (isset($_COOKIE['user_type_pay_client'])) {
									
									unset($_COOKIE['user_type_pay_client']);	
								}
								if (isset($_COOKIE['subscriptionId'])) {
									
									unset($_COOKIE['subscriptionId']);	
								}


							return view('clients.dashboard.client_paypal_success', $output);
			

				} // end of succes reponse inner
				else{


					return view('members.dashboard.paypal_cancel');
				}


			} // end of succes reponse outer
			else{


				return view('members.dashboard.paypal_cancel');
			}


	
}



// ipn reference code 
function ipn_notify_url_ref(Request $request){

// Import the namespace Srmklive\PayPal\Services\ExpressCheckout first in your controller.
$provider = new ExpressCheckout;
if (!($provider instanceof ExpressCheckout)) {
	$provider = new ExpressCheckout();
}

$request->merge(['cmd' => '_notify-validate']);
$post = $request->all();        

$response_ipn = (string) $provider->verifyIPN($post);

if ($response_ipn === 'VERIFIED') {                      


// 	$token = $request->get('token');
// 	$PayerID = $request->get('PayerID');

// 	// Verify Express Checkout Token
// 	$paypalInfo = $provider->getExpressCheckoutDetails($token);
// 	// dd($paypalInfo);

// 	$product_id    = $paypalInfo['PAYMENTREQUEST_0_CUSTOM']; 
// 	$subId = explode('_',$paypalInfo['INVNUM']);

// 	// Note: $subId[0] contains subsciption id or buy id and  $subId[1] conatins user type
// 	// usertype == 1 (client pkg), usertype == 2 (member pkg), usertype == 3 (client digicoins), usertype == 4 (member digicoins) 

// 	$data['product_id'] = $product_id; 	
// 	$data['subscriptionId'] = $subId[0];
// $data['buyId'] = $subId[0];
// 	$data['txn_id']	= $paypalInfo["PAYMENTREQUEST_0_TRANSACTIONID"];
// 	$data['payment_gross'] = $paypalInfo["PAYMENTREQUEST_0_AMT"];
// 	$data['currency_code'] = $paypalInfo["PAYMENTREQUEST_0_CURRENCYCODE"];
// 	$data['payer_email'] = $paypalInfo["PAYMENTREQUEST_0_SELLERPAYPALACCOUNTID"];
// 	$data['item_name']	= $paypalInfo["L_NAME0"];

// 	if($paypalInfo["CHECKOUTSTATUS"] =='PaymentActionCompleted' || $paypalInfo["ACK"] =='Success' || $paypalInfo["ACK"] == 'SUCCESSWITHWARNING'){
// 		$data['payment_status']	= 'Completed'; 
// 	}
// 	else{
// 		$data['payment_status']	= 'Pending'; 
// 	}
	


// 	// member pkg
// 	if($subId['1']==2)
// 	{
			

// 	// $paypalURL = $this->paypal_lib->paypal_url;		
// 	// $result	= $this->paypal_lib->curlPost($paypalURL,$paypalInfo);
	
// 	//insert the transaction data into the database
// 	$transaction =  $this->memberAllDB_model->insertMemberTransaction($data);
		
// 		//	email
// 		if($transaction>0)
// 		{

// 			$package = $data['item_name'];
// 			$amount = $data['payment_gross']. ' $';
		
	
// 			$memberInfo = $this->memberAllDB_model->getPaypalMemberInfo($data['txn_id']); 

// 			if(!empty($memberInfo['data'][0]->email)){
// 					$email = urldecode($memberInfo['data'][0]->email);

// 					// send mail starts
// 					if(!empty($email)){

						
// 							// $email ='school_test007@yopmail.com';
						
// 							$m_sub = 'Member paypal payment confirmation at Digiwaxx';
// 							$name = urldecode($memberInfo['data'][0]->name);

// 							$message = 'Hi '.$name.',<br />
// 							<p>'.$package.' Package</p><p>Your payment of '.$amount.' is successfully done.</p>';
			
// 							$mail_data = [
// 								'm_sub' => $m_sub,
// 								'm_msg' => $message,
// 							];

// 								$sendInvoiceMail = Mail::to($email);
// 								$sendInvoiceMail->send(new AdminForgetNotification($mail_data));
			
							
// 					}
// 					// send mail ends
// 			}	


// 		}

	
	
// 	}

// 	elseif($subId['1']==1) // client pkg
// 	{
// 	// 	$paypalURL = $this->paypal_lib->paypal_url;		
// 	// $result	= $this->paypal_lib->curlPost($paypalURL,$paypalInfo);
		
// 		$this->memberAllDB_model->insertTransaction($data);

// 		$package = $data['item_name'];
// 		$amount = $data['payment_gross']. ' $';

		
// 		//	email
// 		$clientInfo = $this->memberAllDB_model->getPaypalClientInfo($data['txn_id']); 
		
// 		if(!empty($clientInfo['data'][0]->email)){
// 				$email = urldecode($clientInfo['data'][0]->email);

// 				// send mail starts
// 				if(!empty($email)){


// 					// $email ='school_test007@yopmail.com';
				
// 					$m_sub = 'Client paypal payment confirmation at Digiwaxx';
// 					$name = urldecode($clientInfo['data'][0]->name);

// 					$message = 'Hi '.$name.',<br />
// 					<p>'.$package.' Package</p><p>Your payment of '.$amount.' is successfully done.</p>';	
	
// 						$mail_data = [
// 							'm_sub' => $m_sub,
// 							'm_msg' => $message,
// 						];

// 						$sendInvoiceMail = Mail::to($email);
// 						$sendInvoiceMail->send(new AdminForgetNotification($mail_data));
	
					
// 			}
// 			// send mail ends
// 		}	

	
// 	}
// 	else if($subId['1']==3) // client digicoins
// 	{
	
		
// 		$this->memberAllDB_model->insertClientDigicoinsTransaction($data);
		
// 		if(strcmp($data['payment_gross'],'750.00')==0) 
// 		{
// 			$package = 'ADVANCED'; $amount = '750 $';
// 		}
// 		else if(strcmp($data['payment_gross'],'500.00')==0) 
// 		{
// 			$package = 'BASIC'; $amount = '500 $';
// 		}
		
// 		//	email
// 		$clientInfo = $this->memberAllDB_model->getPaypalClientInfo($data['txn_id']); 
		
// 		if(!empty($clientInfo['data'][0]->email)){
// 				$email = urldecode($clientInfo['data'][0]->email);
			

// 				// send mail starts
// 				if(!empty($email)){


// 					// $email ='school_test007@yopmail.com';
				
// 					$m_sub = 'Client paypal payment for digicoins confirmation at Digiwaxx';

// 					$name = urldecode($clientInfo['data'][0]->name);
// 					$message = 'Hi '.$name.',<br />
// 								<p>Digicoins purchased : '.$amount.'</p>';
	
// 						$mail_data = [
// 							'm_sub' => $m_sub,
// 							'm_msg' => $message,
// 						];

// 						$sendInvoiceMail = Mail::to($email);
// 						$sendInvoiceMail->send(new AdminForgetNotification($mail_data));
	
					
// 			}
// 			// send mail ends
// 		}	

	
	
// 	}
// 	else if($subId['1']==4) // member digicoins
// 	{
	
	
// 	//insert the transaction data into the database
// 	$transaction =  $this->memberAllDB_model->insertMemberDigicoinsTransaction($data);
		
// 		//	email

	
// 		$memberInfo = $this->memberAllDB_model->getPaypalMemberInfo($data['txn_id']); 

// 		if(!empty($memberInfo['data'][0]->email)){
// 				$email = urldecode($memberInfo['data'][0]->email);

// 				// send mail starts
// 				if(!empty($email)){


// 					// $email ='school_test007@yopmail.com';
				
// 					$m_sub = 'Member paypal payment for digicoins confirmation at Digiwaxx';

// 					$name = urldecode($clientInfo['data'][0]->name);
// 					$message = 'Hi '.$name.',<br />
// 							<p>Digicoins purchased : '.$amount.'</p>';
	
// 						$mail_data = [
// 							'm_sub' => $m_sub,
// 							'm_msg' => $message,
// 						];

// 						$sendInvoiceMail = Mail::to($email);
// 						$sendInvoiceMail->send(new AdminForgetNotification($mail_data));
	
					
// 			}
// 			// send mail ends
// 		}

		
// }

// $content = array(
// 	 'introline'  => 'IPN notification',
// 	 'username'   => $response_ipn,
// 	 'password'   => json_encode($post),
// 	 'actionText' => 'Login',
// 	 'actionUrl'  => route('login'),
//  );
//Sending mail to user
// Mail::to('no-reply@digiwaxx.io')->send(new AdminForgetNotification($content));
// Mail::to('school_test007@yopmail.com')->send(new AdminForgetNotification($content));
}

}	

// working ipn code
function ipn_notify_url(Request $request){

// Import the namespace Srmklive\PayPal\Services\ExpressCheckout first in your controller.
$provider = new ExpressCheckout;
if (!($provider instanceof ExpressCheckout)) {
	$provider = new ExpressCheckout();
}

$request->merge(['cmd' => '_notify-validate']);
$post = $request->all();        

$response_ipn = (string) $provider->verifyIPN($post);

if ($response_ipn === 'VERIFIED') {  
	
	// code goes here for store response and send mail


}	
	
	
// $content = array(
// 	 'introline'  => 'IPN notification',
// 	 'username'   => $response_ipn,
// 	 'password'   => json_encode($post),
// 	 'actionText' => 'Login',
// 	 'actionUrl'  => route('login'),
//  );
//Sending mail to user
// Mail::to('no-reply@digiwaxx.io')->send(new AdminForgetNotification($content));
// Mail::to('school_test007@yopmail.com')->send(new AdminForgetNotification($content));
}



// upload media functions start here

	
	
	public function member_uploadmedia(Request $request){

	    
    	if(empty(Session::get('memberId'))){ 
    	    return redirect()->intended('login');
		}else{
		    $memID= Session::get('memberId');
		}
		
		$mem_data =  DB::select("SELECT email, uname FROM members where id = '" . $memID . "'");
		$mem_email = $mem_data[0]->email;
		$member_uname = $mem_data[0]->uname;
// 		dd($member_uname);
	
	    $output = array();
		$logo_data = array(
			'logo_id' => 1,
			);

		$logo_details = DB::table('website_logo')
		->where($logo_data)
		->first();
	    
	    $get_logo = $logo_details->logo;	
		$output['pageTitle'] = 'Digiwax Member Upload Music';
		$output['logo'] = $get_logo;
		$output['active'] = 'uploadmedia';
		
		$memPakage = Session::get('memberPackage');
		
				// get sub genres
    
    if (isset($_GET['getSubGenres']) && isset($_GET['genreId'])) {
    
        $genreId = $_GET['genreId'];
        $query = DB::select("SELECT subGenreId, subGenre FROM genres_sub where genreId = '" . $genreId . "' order by subGenre");
        $result1['numRows'] = count($query);
        $result1['data']  = $query;
        $subGenres = $result1;
    
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
		
		
    $query = DB::select("SELECT * FROM genres order by genre");
    $result['numRows'] = count($query);
    $result['data']  = $query;	
    
	$output['genres'] = $result;	

    if(isset($_POST['addTrack']))
		{
		    
		 $getString = '';
		 foreach($_POST as $key => $value)
		 { 
		   $getString .= '&'.$key.'='.$value;
		 }
		

		// track validation
	   if(!(isset($_FILES['amr1'])) || (strlen($_FILES['amr1']['name'])<4)) 
		 {
		   header("location: ".url("member_uploadmedia?invalidTrack=1".$getString));   exit;
		 }

	    
	    $data=$_POST;
	    
	    ///////////// query insert data /////////////////////
    	    extract($data);
    	   // dd($producer);
            $release_date = $data['year'] . '-' . $data['month'] . '-' . $data['day'];
    
            if(!empty($feat_artist_1)){
    
                $feat_artist_1 = $feat_artist_1;
            }
            else{
                $feat_artist_1 = '';
            }
    
            if(!empty($feat_artist_2)){
    
                $feat_artist_2 = $feat_artist_2;
            }
            else{
                $feat_artist_2 = '';
            }
    
            if(!empty($producer)){
    
                $producer = $producer;
            }
            else{
                $producers = '';
            }    
    
    
            $insert_data = array(
                'member' => $memID,
                'artist' => urlencode($artist),
                'title' => urlencode($title),
                'feat_artist_1' => urlencode($feat_artist_1),
                'feat_artist_2' => urlencode($feat_artist_2),
                'producers' => urlencode($producer),
                'time' => urlencode($trackTime),
                'bpm' => urlencode($bpm),
                'albumType' => urlencode($albumType),
                'album' => urlencode($album),
                'priorityType' => urlencode($priorityType),
                'releasedate' => urlencode($release_date),
                'link' => urlencode($website),
                'link1' => urlencode($website1),
                'link2' => urlencode($website2),
                'moreinfo' => urlencode($trackInfo),
                'approved' => 0,
                'added' => NOW(),
                'genreId' => urlencode($genre),
                'subGenreId' => urlencode($subGenre),
                'facebookLink' => urlencode($facebookLink),
                'twitterLink' => urlencode($twitterLink),
                'instagramLink' => urlencode($instagramLink),
    
            
            );
     
            $result = DB::table('tracks_submitted')->insertGetId($insert_data);
    
            
            /////////// query end ///////////////////

	   

		// art work image upload

		if(isset($_FILES['artWork']['name']) && strlen($_FILES['artWork']['name'])>4)
		{

        // dd($_FILES['artWork']);
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
            
            // dd($destination_path);
			$image_name = $memID . '_' . $artWorkImageName .'.'. $file_extension;

			$uploaded_data = $artWork->move( $destination_path , $image_name );

			if( !empty( $uploaded_data )){
			// die('file');
			
			    ////////////query insert image ////////////////////////
				
		        $query = DB::select("update `tracks_submitted` set imgpage = '" . $image_name . "' where id = '" . $result . "' and member = '" . $memID . "'");
				
				///////////query end ////////////////////

			}

			else{

			//header("location: " . url("admin/album/edit/?aid=" . $_GET['aid'] . "&error=1"));
			}

  		 }



	   
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
		
				header("location: " . url("admin/track_manage_mp3?tid=" . $memID . "&noaudio=1"));
				exit;

        	}

			$fileName = md5(rand(1000, 10000));
			$fileNameToStore = $memID.'_'.$i.'_'.$fileName.'.mp3';
// 			dd($destination_path,$fileNameToStore);

			$uploaded_data = $audio_single->move( $destination_path , $fileNameToStore );
		
		  //  print_r($uploaded_data);

			if( !empty( $uploaded_data )){
				// $version = $_POST['version' . $i];
				// if (strlen($_POST['version' . $i]) < 3) {
				// 	$version = $_POST['otherVersion' . $i];
				// }
				$track_title = $_POST['track_title'.$i];
				$version = $_POST['version'];
				$metadata = $this->add_to_pcloud($destination_path,$fileNameToStore);
                // dd($metadata);
                 $query = DB::select("update `tracks_submitted` set amr$i = '" . $metadata->metadata->fileid . "', version$i = '" . $version . "', title$i = '" . $track_title . "' where id = '" . $result . "' and member = '" . $memID . "'"); 	
				

			}			  
			   
		}
	}


		  if($result>0)
		  {

				//send mail starts
				
				$email = $mem_email; 
				// $email = 'a@yopmail.com';
				if(!empty($email)){

					 $to_email =  urldecode($email);
					// $to_email ='school_test007@yopmail.com';
				  
					$m_sub = 'Thank You for Your Track Upload';

				 	$m_msg = "Hi ".$member_uname.",<br /><p>Thanks for uploading tracks on digiwaxx. Your track has been successfully uploaded. Digiwaxx Media... Still Breakin' Boundaries!!!</p>";
	
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

					
					$sendInvoiceMail->send(new AdminForgetNotification($mail_data));


				 	header("location: ".url("member_upload_media_preview?tId=".$result));   exit;
				}
		  }
		  else
		  {
		   header("location: ".url("member_uploadmedia?error=1"));   exit;
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
    		  $output['alert_message'] = 'Image dimension should not be less than 300*300!';
    	   }
    		
    		
		return view('members.dashboard.upload-media', $output);
	}
	
	function add_to_pcloud($path,$file)
	{	
	    
	   $fpath = base_path() . '/vendor/pcloud/pcloud-php-sdk/lib/pCloud/autoload.php';
		  require_once($fpath);
   


		$file_path = $path.$file;
		
		$pCloudFile = new pCloud\File();
		$folder = 5241532702;  // PCLOUD_FOLDER_ID
		$metadata = $pCloudFile->upload($file_path,$folder,$file);
		return $metadata;
	}
	
	public function member_upload_media_preview(Request $request)
	{
	    
    	if(empty(Session::get('memberId'))){ 
    	    return redirect()->intended('login');
		}else{
		    $memID= Session::get('memberId');
		}
		
		$output = array();
		 $output['pageTitle'] = 'Digiwax Member Submit Track Preview';
		$output['packageId'] = 2;
		

		$subscriptionId = Session::get('subscriptionId');
		$output['sessMemberID'] = $memID;


		date_default_timezone_set('America/Los_Angeles');
		 
		  // logo 
		$logo_data = array(
			'logo_id' => 1,
			);
		$logo_details = DB::table('website_logo')
		->where($logo_data)
		->first();
	    
	    $get_logo = $logo_details->logo;
		$output['logo'] = $get_logo;

		$mem_data =  DB::select("SELECT email, uname FROM members where id = '" . $memID . "'");
		$mem_email = $mem_data[0]->email;
		$member_uname = $mem_data[0]->uname; 
		
		//echo "<pre>"; print_r($clientdata); die;

		$headerOutput['wrapperClass'] = 'register';

		$output['wrapperClass'] = 'register';

		//  header data ends here

        $trackId = $_GET['tId'];
		if(isset($_GET['tId']) && isset($_POST['confirmPreview']))

		{

		  $result = $result = DB::select("update tracks_submitted set previewTrack = '1' where id = '$trackId' and previewTrack = '0'"); 

		  

		  if($result>0)

		  {

			 header("location: ".url("Member_dashboard_newest_tracks?trackAdded=success"));   exit;

		  }

		  else

		  {

			 header("location: ".url("member_upload_media_preview?tId=".$_GET['tId']."&error=1"));   exit;

		  }

		}

		

		$where = "where id = '". $_GET['tId'] ."' and previewTrack = '0'";

		$query = DB::select("SELECT genres.genreId, genres.genre, genres_sub.subGenreId, genres_sub.subGenre, tracks_submitted.id, tracks_submitted.artist, tracks_submitted.title, tracks_submitted.producers, tracks_submitted.time, tracks_submitted.bpm, tracks_submitted.albumType, tracks_submitted.album, tracks_submitted.releasedate, tracks_submitted.link, tracks_submitted.link1, tracks_submitted.link2, tracks_submitted.moreinfo, tracks_submitted.approved, tracks_submitted.added, tracks_submitted.deleted, tracks_submitted.imgpage, tracks_submitted.thumb, tracks_submitted.label, tracks_submitted.facebookLink, tracks_submitted.twitterLink, tracks_submitted.instagramLink FROM  tracks_submitted

        left join genres on tracks_submitted.genreId = genres.genreId

        left join genres_sub on tracks_submitted.subGenreId = genres_sub.subGenreId

        $where");

        $output['track']['data'] = $query;
        $output['track']['numRows'] = count($query);
		if($output['track']['numRows']<1)

		{

		  // header("location: ".url("Client_tracks"));   exit; 

		}

		return view('members.dashboard.upload_media_preview', $output);

	}


    public function upload_media_edit(Request $request){
        
        
        if (empty(Session::get('memberId'))) {
            return redirect()->intended('login');
        } else {
            $memID = Session::get('memberId');
        }
        
        // if(!empty(Session::get('tempClientId'))){
        
        //         $output['welcomeMsg'] = 'Thank you for updating your information !';
        
        //         Session::forget('tempClientId');
        // }
        
        $output = array();
        $output['pageTitle'] = 'Digiwax Member Edit Track';
        $output['packageId'] = 2;
        
        $subscriptionId = Session::get('subscriptionId');
        
        $output['sessMemID'] = $memID;
        
        
        date_default_timezone_set('America/Los_Angeles');
        
        // logo 
        $logo_data = array(
            'logo_id' => 1,
        );
        $logo_details = DB::table('website_logo')
            ->where($logo_data)
            ->first();
        
        $get_logo = $logo_details->logo;
        $output['logo'] = $get_logo;
        
        $mem_data =  DB::select("SELECT email, uname FROM members where id = '" . $memID . "'");
        $mem_email = $mem_data[0]->email;
        $member_uname = $mem_data[0]->uname;
        
        // $headerOutput['wrapperClass'] = 'register';
        
        // $output['wrapperClass'] = 'client';
        
        //  header data ends here
        
        $headerOutput['packageId'] = 2;
        $headerOutput['displayDashboard'] = 1;
        
        $data=$_POST;
        if (isset($_POST['updateSubmittedTrack']) && isset($_GET['tId'])) {
        
            $trackId = $_GET['tId'];
            extract($data);
        
            $releasedate = $data['year'] . '-' . $data['month'] . '-' . $data['day'];
        
            $result = DB::select("update  tracks_submitted set  artist = '" . urlencode($artist) . "', title = '" . urlencode($title) . "', producers = '" . urlencode($producer) . "', time = '" . urlencode($trackTime) . "', link =  '" . urlencode($website) . "',  album = '" . urlencode($album) . "',  releasedate = '" . $releasedate . "', moreinfo = '" . urlencode($trackInfo) . "', genreId = '" . $genre . "', subGenreId = '" . $subGenre . "', bpm = '" . $bpm . "', albumType = '" . $albumType . "', link1 = '" . $website1 . "', link2 = '" . $website2 . "', facebookLink = '" . $facebookLink . "', twitterLink = '" . $twitterLink . "' where id = '" . $trackId . "' and member = '" . $memID . "'");
        
        
            // art work image upload
        
            if (isset($_FILES['artWork']['name']) && strlen($_FILES['artWork']['name']) > 4) {
        
                $date_time_array = getdate(time());
        
                $artWorkImageName =    $date_time_array["year"] . $date_time_array["mon"] . $date_time_array["mday"] . $date_time_array["hours"] . $date_time_array["minutes"] . $date_time_array["seconds"];
        
                $artWork = $request->file('artWork');
        
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
        
                //Display File Mime Type
                $file_mime_type = $artWork->getMimeType();
                // echo 'File Mime Type: '.$file_mime_type;
        
                $destination_path = base_path('ImagesUp/');
                // die($destination_path);
        
                //Display Destination Path
                if (empty($destination_path)) {
                    $destination_path = public_path('uploads/');
                } else {
                    $destination_path = $destination_path;
                }
        
                $image_name = $memID . '_' . $artWorkImageName . '.' . $file_extension;
        
                $uploaded_data = $artWork->move($destination_path, $image_name);
        
                if (!empty($uploaded_data)) {
                    // die('file');
                    $id = $_GET['tId'];
        
                    $query = DB::select("update tracks_submitted set imgpage = '" . $image . "' where id = '" . $id . "' and member = '" . $memID . "'");

                } else {
        
                    //header("location: " . url("admin/album/edit/?aid=" . $_GET['aid'] . "&error=1"));
                }
            }
        
        
            if ($result > 0) {
        
                header("location: " . url("member_upload_media_preview?tId=" . $_GET['tId'] . "&updated=1"));
                exit;
            } else {
        
                header("location: " . url("upload_media_edit?tId=" . $_GET['tId'] . "&error=1"));
                exit;
            }
        }
        
        
        
        if (isset($_GET['updated'])) {
        
        
        
            $output['alert_class'] = 'success-msg';
        
            $output['alert_message'] = 'Track updated  successfully !';
        } else if (isset($_GET['error'])) {
        
            $output['alert_class'] = 'error-msg';
        
            $output['alert_message'] = 'Error occured, please try again.';
        }
        
        
        
        $where = "where id = '" . $_GET['tId'] . "'";

		$query = DB::select("SELECT genres.genreId, genres.genre, genres_sub.subGenreId, genres_sub.subGenre, tracks_submitted.id, tracks_submitted.artist, tracks_submitted.title, tracks_submitted.producers, tracks_submitted.time, tracks_submitted.bpm, tracks_submitted.albumType, tracks_submitted.album, tracks_submitted.releasedate, tracks_submitted.link, tracks_submitted.link1, tracks_submitted.link2, tracks_submitted.moreinfo, tracks_submitted.approved, tracks_submitted.added, tracks_submitted.deleted, tracks_submitted.imgpage, tracks_submitted.thumb, tracks_submitted.label, tracks_submitted.facebookLink, tracks_submitted.twitterLink, tracks_submitted.instagramLink FROM  tracks_submitted

        left join genres on tracks_submitted.genreId = genres.genreId

        left join genres_sub on tracks_submitted.subGenreId = genres_sub.subGenreId

        $where");
        // dd($query);

        $output['track']['data'] = $query;
        $output['track']['numRows'] = count($query);
        
        
        
        $query = DB::select("SELECT * FROM genres order by genre");
        $result['numRows'] = count($query);
        $result['data']  = $query;
    	$output['genres'] = $result;
        
        // dd($output['track']);
        $genreId = $output['track']['data'][0]->genreId;
        $query = DB::select("SELECT subGenreId, subGenre FROM genres_sub where genreId = '" . $genreId . "' order by subGenre");
        $result1['numRows'] = count($query);
        $result1['data']  = $query;
        $output['subGenres'] = $result1;
        
        return view('members.dashboard.upload-media-edit', $output);

    }
    
    
    
    // my tracks functions start
    
    public function member_my_tracks(Request $request){
        
        // header data pass starts

			   if(empty(Session::get('memberId'))){ 
				return redirect()->intended('login');
			}
                $memID = Session::get('memberId');
		
			   $output = array();

				 $logo_data = array(
					 'logo_id' => 1,
					 );
		
				 $logo_details = DB::table('website_logo')
				 ->where($logo_data)
				 ->first();  
				 
				 $get_logo = $logo_details->logo;
		
				  $output['pageTitle'] = 'Member Dashboard My Tracks';
				  $output['logo'] = $get_logo;
				  $output['active'] = 'mytracks';
				
				 // fb logout link
				//  $this->load->library('facebook');
				//  $logout_link = url('Logout');
		 
				//  if (isset($_SESSION['fb_access_token'])) {
				// 	 $logout_link = $this->facebook->logout_url();
				//  }
				//  $output['logout_link'] = $logout_link;


				// header data pass ends here and again continue at "#moreHeaderData"
		

				//  $check = $this->memberAllDB_model->test_mem_fun();
     			//  print_r($check);die;
		
		 
		 
				 // generate where
				 /*
				 $where = 'where ';
				 $whereItems[] = "tracks.deleted = '0'";
				 $whereItems[] = "tracks.active = '1'";
		 
				 $output['searchArtist'] = '';
				 $output['searchTitle'] = '';
				 $output['searchLabel'] = '';
				 $output['searchAlbum'] = '';
				 $output['searchProducer'] = '';
				 $output['searchClient'] = '';
		 
				 
				 if (isset($_GET['searchKey']) && strlen($_GET['searchKey']) > 0) {
					 $output['searchKey'] = $_GET['searchKey'];
					 header("location: " . url("Member_dashboard_all_tracks").'?search=&searchKey='.$_GET['searchKey']);
				 }
		 
				 if (isset($_GET['search'])) {
		 
					 if (isset($_GET['artist']) && strlen($_GET['artist']) > 0) {
						 $output['searchArtist'] = $_GET['artist'];
						 $whereItems[] = "tracks.artist = '" . urlencode($_GET['artist']) . "'";
					 }
		 
					 if (isset($_GET['title']) && strlen($_GET['title']) > 0) {
						 $output['searchTitle'] = $_GET['title'];
						 $whereItems[] = "tracks.title = '" . urlencode($_GET['title']) . "'";
					 }
		 
					 if (isset($_GET['label']) && strlen($_GET['label']) > 0) {
						 $output['searchLabel'] = $_GET['label'];
						 $whereItems[] = "tracks.label = '" . urlencode($_GET['label']) . "'";
					 }
		 
					 if (isset($_GET['album']) && strlen($_GET['album']) > 0) {
						 $output['searchAlbum'] = $_GET['album'];
						 $whereItems[] = "tracks.album = '" . urlencode($_GET['album']) . "'";
					 }
		 
					 if (isset($_GET['producer']) && strlen($_GET['producer']) > 0) {
						 $output['searchProducer'] = $_GET['producer'];
						 $whereItems[] = "tracks.producer = '" . $_GET['producer'] . "'";
					 }
		 
					 if (isset($_GET['client']) && strlen($_GET['client']) > 0) {
						 $output['searchClient'] = $_GET['client'];
						 $whereItems[] = "tracks.client = '" . $_GET['client'] . "'";
					 }
				 }
		 
		 
				 if (count($whereItems) > 1) {
		 
					 $whereString = implode(' AND ', $whereItems);
					 $where .= $whereString;
				 } else if (count($whereItems) == 1) {
					 $where .= $whereItems[0];
				 } else {
					 $where =  '';
				 }
		 
		 
				 // generate sort
				 $sortOrder = "DESC";
				 $sortBy = "id";
				 $output['sortBy'] = 'added';
				 $output['sortOrder'] = 2;
		 
				 if (isset($_GET['sortBy']) && isset($_GET['sortOrder'])) {
					 $output['sortBy'] = $_GET['sortBy'];
					 $output['sortOrder'] = $_GET['sortOrder'];
		 
		 
					 if (strcmp($_GET['sortBy'], 'artist') == 0) {
		 
						 $sortBy = "artist";
					 } else if (strcmp($_GET['sortBy'], 'title') == 0) {
		 
						 $sortBy = "title";
					 } else if (strcmp($_GET['sortBy'], 'label') == 0) {
		 
						 $sortBy = "label";
					 } else if (strcmp($_GET['sortBy'], 'added') == 0) {
		 
						 $sortBy = "id";
					 } else if (strcmp($_GET['sortBy'], 'album') == 0) {
		 
						 $sortBy = "album";
					 } else if (strcmp($_GET['sortBy'], 'trackLength') == 0) {
		 
						 $sortBy = "time";
					 } else if (strcmp($_GET['sortBy'], 'producers') == 0) {
		 
						 $sortBy = "producer";
					 } else if (strcmp($_GET['sortBy'], 'client') == 0) {
		 
						 $sortBy = "client";
					 } else if (strcmp($_GET['sortBy'], 'paid') == 0) {
		 
						 $sortBy = "paid";
					 } else if (strcmp($_GET['sortBy'], 'invoiced') == 0) {
		 
						 $sortBy = "invoiced";
					 } else if (strcmp($_GET['sortBy'], 'graphicsCompleted') == 0) {
		 
						 $sortBy = "graphicscomplete";
					 }
		 
		 
					 if ($_GET['sortOrder'] == 1) {
		 
						 $sortOrder = "ASC";
					 } else  if ($_GET['sortOrder'] == 2) {
		 
						 $sortOrder = "DESC";
					 }
				 }
				 $sort =  $sortBy . " " . $sortOrder;

		 
		 
				 // pagination
				 $limit = 10;
				 if (isset($_GET['records'])) {
					 $limit = $_GET['records'];
				 }
				 $output['numRecords'] = $limit;
		 
				 $start = 0;
				 $currentPageNo = 1;
		 
				 if (isset($_GET['page']) && $_GET['page'] > 1) {
					 $start = ($_GET['page'] * $limit) - $limit;
				 }

				
		 
		 
				 if (isset($_GET['page'])) {
					 $currentPageNo = $_GET['page'];
				 }

				
		 
				 $num_records = $this->memberAllDB_model->getNumTracks($where, $sort);

				//  dd($output);

				 $numPages = (int) ($num_records / $limit);
				 $reminder = ($num_records % $limit);
				 $output['num_records'] = $num_records;
		 
				 if ($reminder > 0) {
					 $numPages = $numPages + 1;
				 }
		 
				 $output['numPages'] = $numPages;
				 $output['start'] = $start;
				 $output['currentPageNo'] = $currentPageNo;
				 $output['currentPage'] = 'Member_dashboard_newest_tracks';
		 
				// dd($output);
		 
				 // generate url string
				 $getArray = array();
				 $urlString = '?';
				 if (isset($_GET)) {
					 foreach ($_GET as $key => $value) {
						 if (strcmp($key, 'page') != 0) {
		 
							 $getArray[] = $key . '=' . $value;
						 }
					 }
		 
		 
		 
					 if (count($getArray) > 1) {
						 $urlString .= implode('&', $getArray);
					 } else if (count($getArray) == 1) {
						 $urlString .= $getArray[0];
					 } else {
						 $urlString = '';
					 }
				 }
				 $output['urlString'] = $urlString;
				 // uncomment later
				 if (isset($_GET['page'])) {
		 
					 if (strlen($urlString) > 3) {
						 $param = '&';
					 } else {
						 $param = '?';
					 }
					 if ($_GET['page'] > $numPages && $numPages > 0) {
						 header("location: " . $output['currentPage'] . $urlString . $param . "page=" . $numPages);
						 exit;
					 }
					 /*else if ($_GET['page'] < 0) {
			  
				  header("location: ".$output['currentPage'].$urlString.$param."page=1");
				  exit;
				  
			  }
				 }
		 
				   if(isset($_GET['page'])) {
			  if ($_GET['page'] > $numPages) {
				  header("location: ".$output['currentPage']."?page=" . $numPages);
				  exit;
			  } else if ($_GET['page'] < 1) {
				  header("location: ".$output['currentPage']."?page=1");
				  exit;
			  }
		  }
		 
		 
		 
				 if ($currentPageNo == 1) {
					 $output['firstPageLink'] = 'disabled';
					 $output['preLink'] = 'disabled';
					 $output['nextLink'] = '';
					 $output['lastPageLink'] = '';
				 } else if ($currentPageNo == $numPages) {
					 $output['firstPageLink'] = '';
					 $output['preLink'] = '';
					 $output['nextLink'] = 'disabled';
					 $output['lastPageLink'] = 'disabled';
				 } else {
					 $output['firstPageLink'] = '';
					 $output['preLink'] = '';
					 $output['nextLink'] = '';
					 $output['lastPageLink'] = '';
				 }
				 
			*/	 
				 // pagination ends
		 
				//  $output['tracks'] = $this->memberAllDB_model->getNewestTracks($where, $sort, $start, $limit);      ----if needed pagination, sorting, search functionality
				
				$where="where member=$memID";
				$sort="";
				$start="";
				$limit="";
				
     	$output['tracks'] = $this->memberAllDB_model->getReviewTracks1($where);
		      //   $query['data'] = DB::table('tracks')->where('member', '=', $memID)->get();

		      //   $query['numRows'] = count($query);
        //         //  $query['data']  = $query;
		         
        //          $output['tracks'] = $query;
        
          $arr1=$output['tracks']['data'];
          
           if($output['tracks']['numRows'] >1){
				 $arr=json_decode(json_encode($arr1));
				
				// $arr= json_decode($arr1);
				
				 
				 foreach ($arr as $key=>$value){

				     $query_loc=$this->memberAllDB_model->getTrackMp3s_fem($value->id);
				    //  pArr($query_loc);
					$xx='';
				     foreach($query_loc['data'] as $key1=>$value1){
				        $xx= $value1->location;
				        
				     }
					 if(!empty($xx)){
				     $arr[$key]->location=$xx;
					 }
				   
				 }
				 $output['tracks']['data']=$arr;
           }
				 
				 
				 
                    if($output['tracks']['numRows'] == 0){
                        $output['no_records_found'] = 1;
                    }
		 
				 foreach ($output['tracks']['data'] as $track) {
				// 	 $output['memberReviews'] = $this->memberAllDB_model->getClientTrackReview_fem($track->id);
					 $query1 = DB::select("SELECT * FROM tracks_reviews where member = '" . $memID . "' and track = '" . $track->id . "'");
					 
                     $result1['numRows'] = count($query1);
                     $result1['data']  = $query1;
                     $output['memberReviews'] = $result1;
                     
					 $output['downloads'][$track->id] = $output['memberReviews']['numRows'];
		 
				 }
		 
		 
				 // right side bar
				 $output['staffTracks'] = $this->memberAllDB_model->getStaffSelectedTracks_fem(0, 4);
		 
				 if ($output['staffTracks']['numRows'] > 0) {
					 foreach ($output['staffTracks']['data'] as $track) {
		 
						 $output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays_fem($track->id);
						 $row = $this->memberAllDB_model->getClientTrackReview_fem($track->id);
						 $output['reviews'][$track->id] = $row['numRows'];
		 
						 $output['mp3s'][$track->id] = $this->memberAllDB_model->getTrackMp3s_fem($track->id);
					 }
				 }
		 
		 
				 $output['youTracks'] = $this->memberAllDB_model->getYouSelectedTracks1_fem(Session::get('memberId'), 0, 4);
		 
		 
				 if ($output['youTracks']['numRows'] > 0) {
					 foreach ($output['youTracks']['data'] as $track) {
		 
						 $output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays_fem($track->id);
						 $row = $this->memberAllDB_model->getClientTrackReview_fem($track->id);
						 $output['reviews'][$track->id] = $row['numRows'];
		 
						 $output['versions'][$track->id] = $this->memberAllDB_model->getTrackMp3s1_fem($track->id);
					 }
				 } else {
		 
					 $output['youTracks'] = $this->memberAllDB_model->getYouSelectedTracks_fem(0, 4);
		 
					 if ($output['youTracks']['numRows'] > 0) {
						 foreach ($output['youTracks']['data'] as $track) {
		 
							 $output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays_fem($track->id);
							 $row = $this->memberAllDB_model->getClientTrackReview_fem($track->id);
							 $output['reviews'][$track->id] = $row['numRows'];
		 
							 $output['versions'][$track->id] = $this->memberAllDB_model->getTrackMp3s_fem($track->id);
						 }
					 }
				 }
		 
				 /*
				 $output['youTracks'] = $this->memberAllDB_model->getYouSelectedTracks_fem(0,4); 
				 
				 if($output['youTracks']['numRows']>0)
				 {
				 foreach($output['youTracks']['data'] as $track)
				 {
				 
					$output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays_fem($track->id); 
					$row = $this->memberAllDB_model->getClientTrackReview_fem($track->id); 
					$output['reviews'][$track->id] = $row['numRows'];
					
					$output['mp3s'][$track->id] = $this->memberAllDB_model->getTrackMp3s_fem($track->id);
				  
				 }
				 }*/
		 
				 // footer data pass starts
				 $footerOutput['tracks'] = $this->memberAllDB_model->getMemberFooterTracks_fem();
				
				 // footer data pass ends here 
		 
				 $unReadmessages = $this->memberAllDB_model->getMemberUnreadInbox_fem(Session::get('memberId'));
				 $output['numMessages'] = $unReadmessages['numRows'];


				// header data pass starts "#moreHeaderData"
				// $headerOutput['numMessages'] = $unReadmessages['numRows'];
		 
				 // subscription status
				 $output['subscriptionStatus'] = 0;
				 $output['package'] = '';
				 $subscriptionInfo = $this->memberAllDB_model->getMemberSubscriptionStatus_fem(Session::get('memberId'));
				 if ($subscriptionInfo['numRows'] > 0) {
					 $output['subscriptionStatus'] = 1;
		 
					 if ($subscriptionInfo['data'][0]->package_Id == 1) {
						 $output['packageId'] = 1;
						 $output['package'] = 'Silver Subscription';
						 // $output['displayDashboard'] = 0;
		 
		 
					 } else if ($subscriptionInfo['data'][0]->package_Id == 2) {
						 $output['packageId'] = 2;
						 $output['package'] = 'Gold Subscription';
						 //$output['displayDashboard'] = 1;
		 
		 
					 } else if ($subscriptionInfo['data'][0]->package_Id == 3) {
						 $output['packageId'] = 3;
						 $output['package'] = 'Purple Subscription';
						 //$output['displayDashboard'] = 1;
		 
		 
					 }
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
		 
				 // echo '<pre/>'; print_r($output); exit;
		 
				// $output['tracks'] = $footerOutput['tracks'];
				// $output['banner_ads'] = $this->memberAllDB_model->getBannerads(2, 1);

				 // header data pass ends here and again continue at "#moreHeaderData"


				 $output['banner_ads'] = $this->memberAllDB_model->getBannerads(2, 2);


				//  $this->load->view('header_member_top.php', $headerOutput);
				//  $this->load->view('member_dashboard_newest_tracks.php', $output);
				//  $this->load->view('footer_member_top.php', $footerOutput);

				//dd($output);

				return view('members.dashboard.my_tracks', $output);  
    }



    //check if same name tracks exist
    public function checkTrackExists(){
	
	    $memID = Session::get('memberId');
	   // return $memID;
    // 	$result = $this->admin_model->checkTrackExists($_POST['myData']);
    	
    	$phpObject = json_decode($_POST['myData'],true);
		$artist = trim($phpObject['songArtist']);
		$title = trim($phpObject['trackTitle']);
		
		$chk_qry = DB::select("SELECT id, title FROM `tracks` WHERE artist = '" . urlencode($artist) . "' AND title = '" . urlencode($title) . "' AND deleted=0");
		$result1['numRows'] = count($chk_qry);
		$result1['data']  = $chk_qry;
        // return json_encode($result);

    	
    	$chk_qry1 = DB::select("SELECT id, title FROM `tracks` WHERE artist = '" . urlencode($artist) . "' AND title = '" . urlencode($title) . "' AND deleted=0 AND member= '".$memID."'");
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

    public function delete_track(){
        
        $trackId = json_decode($_POST['delTrackId'],true);
        
        $q= DB::select("update tracks set deleted = '1' where id = '" . $trackId . "'");
        
        if($q){
            $result['status'] = 'success';
            return $result;
        }
    }

    public function members_view_package(Request $request){

		   if(empty(Session::get('memberId'))){ 
			return redirect()->intended('login');
		    }
            $memID = Session::get('memberId');
	
		   $output = array();

			 $logo_data = array(
				 'logo_id' => 1,
				 );
				 
				 
			$class='';
			$result='';
			if(isset($_GET['status'])){
			    
			    $get_success=$_GET['status'];
			    if($get_success==1){
			        $class="alert alert-success";
			        $result="SUBSCRIPTION PURCHASED SUCCESSFULLY";
			        
			    }
			    if($get_success==2){
			        $class="alert alert-warning";
			        $result="Request Declined.";
			        
			    }
			}
			
			$output['class']=$class;
			$output['result']=$result;
			 $my_details=$this->memberAllDB_model->getMemberInfo_fem($memID)['data'][0];
			 $output['email']=urldecode($my_details->email);
			
	
			 $logo_details = DB::table('website_logo')
			 ->where($logo_data)
			 ->first();  
			 
			 $get_logo = $logo_details->logo;
	
			  $output['pageTitle'] = 'Member Dashboard My Package';
			  $output['logo'] = $get_logo;
                
            // $all_pack=DB::table('manage_packages')->orderBy('package_price')->where('available_to',1)->get();
            // $output['all_pack']=$all_pack;
                
	         $query = DB::table('package_user_details')->where('user_id', '=', $memID)->where('user_type','=',1)->where('package_active','=',1)->select('package_id','package_start_date','package_expiry_date')->get();
	         if(count($query)>0){
	             $output['active_id']=$query;
	         }
	         else{
	             $output['active_id']=0;
	         }
	         $query1=DB::table('manage_packages')->orderBy('package_price')->where('available_to',1)->where('package_status',1)->get();
	         
	                   
              foreach($query1 as $key=>$value){
                        if($value->id < $query[0]->package_id){
                            $query1[$key]->button ='downgrade';
                        } else if($value->id == $query[0]->package_id){
                            $query1[$key]->button ='equal';
                          }
                        else{
                             $query1[$key]->button='upgrade';
                        }
                 }

	         
	         
	         $output['package_details']=$query1;
	         
	         
	      
	         
	       //  //if no records found
        //      $output['package_details'] = $query;
        //      $count=count($query);
        //      if($count==0){
                 
        //          $output['no_records_found'] = 1;
                 
        //      }else{
                              
        //          $package_query = DB::table('manage_packages')->where('id','=',$output['package_details'][0]->package_id)->get();
        //     	 $output['package_details'][0]->package_name = $package_query[0]->package_name;
        //     	 $output['package_details'][0]->package_type = $package_query[0]->package_type;
        //      }

            //  dd($output['package_details']);
	 
	 
			 // right side bar
			 $output['staffTracks'] = $this->memberAllDB_model->getStaffSelectedTracks_fem(0, 4);
	 
			 if ($output['staffTracks']['numRows'] > 0) {
				 foreach ($output['staffTracks']['data'] as $track) {
	 
					 $output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays_fem($track->id);
					 $row = $this->memberAllDB_model->getClientTrackReview_fem($track->id);
					 $output['reviews'][$track->id] = $row['numRows'];
	 
					 $output['mp3s'][$track->id] = $this->memberAllDB_model->getTrackMp3s_fem($track->id);
				 }
			 }
	 
	 
			 $output['youTracks'] = $this->memberAllDB_model->getYouSelectedTracks1_fem(Session::get('memberId'), 0, 4);
	 
	 
			 if ($output['youTracks']['numRows'] > 0) {
				 foreach ($output['youTracks']['data'] as $track) {
	 
					 $output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays_fem($track->id);
					 $row = $this->memberAllDB_model->getClientTrackReview_fem($track->id);
					 $output['reviews'][$track->id] = $row['numRows'];
	 
					 $output['versions'][$track->id] = $this->memberAllDB_model->getTrackMp3s1_fem($track->id);
				 }
			 } else {
	 
				 $output['youTracks'] = $this->memberAllDB_model->getYouSelectedTracks_fem(0, 4);
	 
				 if ($output['youTracks']['numRows'] > 0) {
					 foreach ($output['youTracks']['data'] as $track) {
	 
						 $output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays_fem($track->id);
						 $row = $this->memberAllDB_model->getClientTrackReview_fem($track->id);
						 $output['reviews'][$track->id] = $row['numRows'];
	 
						 $output['versions'][$track->id] = $this->memberAllDB_model->getTrackMp3s_fem($track->id);
					 }
				 }
			 }
	 
			 /*
			 $output['youTracks'] = $this->memberAllDB_model->getYouSelectedTracks_fem(0,4); 
			 
			 if($output['youTracks']['numRows']>0)
			 {
			 foreach($output['youTracks']['data'] as $track)
			 {
			 
				$output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays_fem($track->id); 
				$row = $this->memberAllDB_model->getClientTrackReview_fem($track->id); 
				$output['reviews'][$track->id] = $row['numRows'];
				
				$output['mp3s'][$track->id] = $this->memberAllDB_model->getTrackMp3s_fem($track->id);
			  
			 }
			 }*/
	 
			 // footer data pass starts
			 $footerOutput['tracks'] = $this->memberAllDB_model->getMemberFooterTracks_fem();
			
			 // footer data pass ends here 
	 
			 $unReadmessages = $this->memberAllDB_model->getMemberUnreadInbox_fem(Session::get('memberId'));
			 $output['numMessages'] = $unReadmessages['numRows'];


			// header data pass starts "#moreHeaderData"
			// $headerOutput['numMessages'] = $unReadmessages['numRows'];
	 
			 // subscription status
			 $output['subscriptionStatus'] = 0;
			 $output['package'] = '';
			 $subscriptionInfo = $this->memberAllDB_model->getMemberSubscriptionStatus_fem(Session::get('memberId'));
			 if ($subscriptionInfo['numRows'] > 0) {
				 $output['subscriptionStatus'] = 1;
	 
				 if ($subscriptionInfo['data'][0]->package_Id == 1) {
					 $output['packageId'] = 1;
					 $output['package'] = 'Silver Subscription';
					 // $output['displayDashboard'] = 0;
	 
	 
				 } else if ($subscriptionInfo['data'][0]->package_Id == 2) {
					 $output['packageId'] = 2;
					 $output['package'] = 'Gold Subscription';
					 //$output['displayDashboard'] = 1;
	 
	 
				 } else if ($subscriptionInfo['data'][0]->package_Id == 3) {
					 $output['packageId'] = 3;
					 $output['package'] = 'Purple Subscription';
					 //$output['displayDashboard'] = 1;
	 
	 
				 }
			 }


			 $output['banner_ads'] = $this->memberAllDB_model->getBannerads(2, 2);
			 
		

			return view('members.dashboard.my_package_view', $output); 
    }
    
    

public function upgrade_package(Request $request)
{

    if (empty(Session::get('memberId'))) {
        return redirect()->intended('login');
    }

    $memID = Session::get('memberId');
    $output = array();



    if (isset($_GET['package_id'])) {

        $id = $_GET['package_id'];

        $query = DB::table('manage_packages')->where('id', $id)->get();
        $package_amount = '';
        if (count($query) > 0) {

            $package_id = $query[0]->id;
            $package_amount = $query[0]->package_price;


            $update_status = DB::table('package_user_details')->where('user_id', $memID)->where('user_type', 1)->update([

                'package_active' => 0

            ]);

            $my_details = $this->memberAllDB_model->getMemberInfo_fem($memID)['data'][0];
            $uname = $my_details->uname;


            $start_date = '';
            $exp_date = '';
            $payment_method = '';
            $payment_amount = '';
            $curTime = new \DateTime();
            $created_at = $curTime->format("Y-m-d H:i:s");
            $start_date = date('Y-m-d', strtotime("+1 day", strtotime($created_at)));

            if ($package_id == 8) {

                $exp_date = date('Y-m-d', strtotime("+30 day", strtotime($start_date)));
                $payment_method = 'stripe';
                $payment_amount = $package_amount;
            }

            if ($package_id == 9) {

                $exp_date = date('Y-m-d', strtotime("+180 day", strtotime($start_date)));
                $payment_method = 'stripe';
                $payment_amount = $package_amount;
            }
            if ($package_id == 10) {
                $exp_date = date('Y-m-d', strtotime("+365 day", strtotime($start_date)));
                $payment_method = 'stripe';
                $payment_amount = $package_amount;
            }


            $insert_data = DB::table('package_user_details')->insert([
                'package_id' => $package_id,
                'user_id' => $memID,
                'user_name' => $uname,
                'user_type' => 1,
                'payment_status' => 1,
                'package_start_date' => $start_date,
                'package_expiry_date' => $exp_date,
                'payment_method' => $payment_method,
                'payment_amount' => $payment_amount,
                'package_active' => 1


            ]);



            $result = FrontEndUser::addMember4($request, Session::get('memberId'));
            if ($result['numRows'] > 0) {
                $email = urldecode($result['data'][0]->email);

                $nameofuser = urldecode($result['data'][0]->fname);


                $emailofuser = $email;


                $query = DB::table("package_user_details")->where('user_id', '=', $memID)->where('package_active', 1)->get();


                if ($query) {
                    foreach ($query as $value) {
                        $id = $value->package_id;
                        $method = $value->payment_method;
                        $amount = $value->payment_amount;
                        $s_date = $value->package_start_date;
                        $e_date = $value->package_expiry_date;
                    }
                    $query1 = DB::table('manage_packages')->where('id', '=', $id)->get();
                    foreach ($query1 as $value) {
                        $title = $value->package_type;
                    }

                    if (!empty($emailofuser)) {
                        $data = array('emailId' => $emailofuser, 'name' => $nameofuser, 'title' => $title, 'method' => $method, 'amount' => $amount, 'start' => $s_date, 'expiry' => $e_date);
                        Mail::send('mails.package.update_package', ['data' => $data], function ($message) use ($data) {
                            $message->to($data['emailId']);
                            $message->subject('Package Updated Successfully');
                            $message->from('business@digiwaxx.com', 'Digiwaxx');
                        });
                    }
                    // return response()->json('success');
                }





                return redirect()->intended('member_manage_subscription?status=1');
            }
        } else {
            return redirect()->intended('member_manage_subscription?status=2');
        }
    } else {
        return redirect()->intended('member_manage_subscription');
    }
}

    
    public function package_history(Request $request){
        
          if(empty(Session::get('memberId'))){ 
			return redirect()->intended('login');
		    }
            $memID = Session::get('memberId');
	
		   $output = array();

			 $logo_data = array(
				 'logo_id' => 1,
				 );
				 
				 

			 //$my_details=$this->memberAllDB_model->getMemberInfo_fem($memID)['data'][0];
			 //$output['email']=urldecode($my_details->email);
			
	
			 $logo_details = DB::table('website_logo')
			 ->where($logo_data)
			 ->first();  
			 
			 $get_logo = $logo_details->logo;
	
			  $output['pageTitle'] = 'Payment History';
			  $output['logo'] = $get_logo;
			  
			  
			   $output['staffTracks'] = $this->memberAllDB_model->getStaffSelectedTracks_fem(0, 4);
	 
			 if ($output['staffTracks']['numRows'] > 0) {
				 foreach ($output['staffTracks']['data'] as $track) {
	 
					 $output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays_fem($track->id);
					 $row = $this->memberAllDB_model->getClientTrackReview_fem($track->id);
					 $output['reviews'][$track->id] = $row['numRows'];
	 
					 $output['mp3s'][$track->id] = $this->memberAllDB_model->getTrackMp3s_fem($track->id);
				 }
			 }
	 
	 
			 $output['youTracks'] = $this->memberAllDB_model->getYouSelectedTracks1_fem(Session::get('memberId'), 0, 4);
	 
	 
			 if ($output['youTracks']['numRows'] > 0) {
				 foreach ($output['youTracks']['data'] as $track) {
	 
					 $output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays_fem($track->id);
					 $row = $this->memberAllDB_model->getClientTrackReview_fem($track->id);
					 $output['reviews'][$track->id] = $row['numRows'];
	 
					 $output['versions'][$track->id] = $this->memberAllDB_model->getTrackMp3s1_fem($track->id);
				 }
			 } else {
	 
				 $output['youTracks'] = $this->memberAllDB_model->getYouSelectedTracks_fem(0, 4);
	 
				 if ($output['youTracks']['numRows'] > 0) {
					 foreach ($output['youTracks']['data'] as $track) {
	 
						 $output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays_fem($track->id);
						 $row = $this->memberAllDB_model->getClientTrackReview_fem($track->id);
						 $output['reviews'][$track->id] = $row['numRows'];
	 
						 $output['versions'][$track->id] = $this->memberAllDB_model->getTrackMp3s_fem($track->id);
					 }
				 }
			 }
	 
			 /*
			 $output['youTracks'] = $this->memberAllDB_model->getYouSelectedTracks_fem(0,4); 
			 
			 if($output['youTracks']['numRows']>0)
			 {
			 foreach($output['youTracks']['data'] as $track)
			 {
			 
				$output['trackData'][$track->id] = $this->memberAllDB_model->getTrackPlays_fem($track->id); 
				$row = $this->memberAllDB_model->getClientTrackReview_fem($track->id); 
				$output['reviews'][$track->id] = $row['numRows'];
				
				$output['mp3s'][$track->id] = $this->memberAllDB_model->getTrackMp3s_fem($track->id);
			  
			 }
			 }*/
	 
			 // footer data pass starts
			 $footerOutput['tracks'] = $this->memberAllDB_model->getMemberFooterTracks_fem();
			
			 // footer data pass ends here 
	 
			 $unReadmessages = $this->memberAllDB_model->getMemberUnreadInbox_fem(Session::get('memberId'));
			 $output['numMessages'] = $unReadmessages['numRows'];


			// header data pass starts "#moreHeaderData"
			// $headerOutput['numMessages'] = $unReadmessages['numRows'];
	 
			 // subscription status
			 $output['subscriptionStatus'] = 0;
			 $output['package'] = '';
			 $subscriptionInfo = $this->memberAllDB_model->getMemberSubscriptionStatus_fem(Session::get('memberId'));
			 if ($subscriptionInfo['numRows'] > 0) {
				 $output['subscriptionStatus'] = 1;
	 
				 if ($subscriptionInfo['data'][0]->package_Id == 1) {
					 $output['packageId'] = 1;
					 $output['package'] = 'Silver Subscription';
					 // $output['displayDashboard'] = 0;
	 
	 
				 } else if ($subscriptionInfo['data'][0]->package_Id == 2) {
					 $output['packageId'] = 2;
					 $output['package'] = 'Gold Subscription';
					 //$output['displayDashboard'] = 1;
	 
	 
				 } else if ($subscriptionInfo['data'][0]->package_Id == 3) {
					 $output['packageId'] = 3;
					 $output['package'] = 'Purple Subscription';
					 //$output['displayDashboard'] = 1;
	 
	 
				 }
			 }
			 
			 //$payment_detail=DB::table('package_user_details')->where('user_id',$memID)->where('user_type',1)->get();
			 $payment_detail=DB::select("select * from package_user_details left join manage_packages on package_user_details.package_id =  manage_packages.id where  package_user_details.user_id=$memID AND  package_user_details.user_type=1 AND  package_user_details.payment_status=1 AND package_user_details.payment_amount > 0 ORDER By package_user_details.id DESC");
			if(count($payment_detail)>0){
			    $output['pay_detail']=$payment_detail;
			}
			else{
			      $output['pay_detail']='';
			    
			}
			  $output['banner_ads'] = $this->memberAllDB_model->getBannerads(2, 2);
			 
			 return view('members.dashboard.payment_history',$output);


			
		
        
        
        
    }
    
    
	// member R-s controller function ends here 
	
	
}
