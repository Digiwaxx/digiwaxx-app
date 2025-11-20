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
Use Redirect;
use Session;
//use Mail;
//use pCloud\App;
use pCloud;

// for mail sending
use Illuminate\Support\Facades\Mail;
use App\Mail\AdminForgetNotification;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;


class AdminController extends Controller
{

protected $admin_model;
protected $pCloudApp;


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

	//$this->pCloudApp = new App();
	//$this->pCloudApp->setAccessToken(config('laravel-pcloud.access_token'));
	//$this->pCloudApp->setLocationId(config('laravel-pcloud.location_id'));
	
}

/**
 * Show the application dashboard.
 *
 * @return \Illuminate\Http\Response
 */

public function admin_dashboard(Request $request)
{  

	// dd(Auth::user()->user_role);

	$admin_name = Auth::user()->name;
	$user_role = Auth::user()->user_role;
	
	$logo_data = array(
		'logo_id' => 1,
		);

	$logo_details = DB::table('website_logo')
	->where($logo_data)
	->first();  
	
	$get_logo = $logo_details->logo;
	//  print_r($logo_details->logo);die;

	$data = [
		'pageTitle'  => 'Admin',
		'logo'   => $get_logo,
		'welcome_name' => $admin_name,
		'user_role' => $user_role,
];

	$data['dashboard_members'] = $this->admin_model->getLatestMembers();
	$data['dashboard_clients'] = $this->admin_model->getLatestClients();
	$data['dashboard_submitted_tracks'] = $this->admin_model->getLatestSubmittedTracks();
	$data['dashboard_top_priority_tracks'] = $this->admin_model->getTopPriorityTracks();
	$data['dashboard_top_streaming_tracks'] = $this->admin_model->getTopStreamingTracks();
	$data['dashboard_weekly_tracks_reviews'] = $this->admin_model->getWeeklyTracksReviews();
	$data['dashboard_recent_active_members'] = $this->admin_model->getRecentActiveMembers();
	$data['dashboard_recent_active_clients'] = $this->admin_model->getRecentActiveClients();
	$data['dashboard_track_and_user_stats'] = $this->admin_model->getTracksAndUserStats();
	

	return view('/admin/admin_dashboard', $data);  

}

// testing function
public function admin_dashboard_oo(Request $request)
{  

	// dd(Auth::user()->user_role);

	$admin_name = Auth::user()->name;
	$user_role = Auth::user()->user_role;
	
	$logo_data = array(
		'logo_id' => 1,
		);

	$logo_details = DB::table('website_logo')
	->where($logo_data)
	->first();  
	
	$get_logo = $logo_details->logo;
	//  print_r($logo_details->logo);die;

	$data = [
		'pageTitle'  => 'Admin',
		'logo'   => $get_logo,
		'welcome_name' => $admin_name,
		'user_role' => $user_role,
	];

	// dd(Auth::guest()) ;
	$data['dashboard_members'] = $this->admin_model->getLatestMembers();
	$data['dashboard_clients'] = $this->admin_model->getLatestClients();
	$data['dashboard_submitted_tracks'] = $this->admin_model->getLatestSubmittedTracks();
	$data['dashboard_top_priority_tracks'] = $this->admin_model->getTopPriorityTracks();
	$data['dashboard_top_streaming_tracks'] = $this->admin_model->getTopStreamingTracks();
	$data['dashboard_weekly_tracks_reviews'] = $this->admin_model->getWeeklyTracksReviews();
	$data['dashboard_recent_active_members'] = $this->admin_model->getRecentActiveMembers();
	$data['dashboard_recent_active_clients'] = $this->admin_model->getRecentActiveClients();
	$data['dashboard_track_and_user_stats'] = $this->admin_model->getTracksAndUserStats();
	

	return view('/admin/admin_dashboard_oo', $data);  

}
// testing function end

/**
 * Show the All Company Logos.
 *
 * @GS
 */

public function admin_listCompanyLogos(Request $request){
	$output = array();
	// header data pass starts
	$admin_name = Auth::user()->name;
	$user_role = Auth::user()->user_role;
	
		$logo_data = array(
			'logo_id' => 1,
			);

		$logo_details = DB::table('website_logo')
		->where($logo_data)
		->first();  
		
		$get_logo = $logo_details->logo;
		//print_r($logo_details->logo);die;
		// echo $get_logo;die('---');
		$output['pageTitle'] = 'Logos';
		$output['logo'] = $get_logo;
		$output['welcome_name'] = $admin_name;
		$output['user_role'] = $user_role;
		
		// SECURITY FIX: Delete logo with proper validation
		if($request->has('did')){
			// Validate input - must be positive integer
			$logoId = filter_var($request->get('did'), FILTER_VALIDATE_INT);

			if ($logoId === false || $logoId <= 0) {
				return Redirect::to("admin/logos?error=invalid_id");
			}

			// TODO: Add authorization check here
			// $this->authorize('delete', Logo::find($logoId));

			$result = $this->admin_model->deleteLogo($logoId);
			if($result>0){
				return Redirect::to("admin/logos?delete=success");
			}else{
				return Redirect::to("admin/logos?error=1");
			}
		}
		
		// generate where
		$where = 'where ';
		$whereItems = array();
		//$whereItems[] = "admin_id != '0'";
		// SECURITY FIX: Sanitize search input
		$output['searchCompany'] = '';
		if($request->has('company') && strlen($request->get('company'))>0){
				$searchTerm = htmlspecialchars(trim($request->get('company')), ENT_QUOTES, 'UTF-8');
				$output['searchCompany'] = $searchTerm;
				// Note: This will be properly sanitized in the model with query builder
				$whereItems[] = ['company', 'LIKE', '%' . $searchTerm . '%'];
		}
		if(count($whereItems)>1){
			$whereString = implode(' AND ',$whereItems);
			$where .= $whereString;
		}
		else if(count($whereItems)==1){
			$where .= $whereItems[0]; 
		}else{
			$where =  '';
		}
		// SECURITY FIX: Validate sort parameters
		$sortOrder = "ASC";
		$sortBy = "company";
		$output['sortBy'] = 'company';
		$output['sortOrder'] = 1;

		// Whitelist allowed sort columns
		$allowedSortColumns = ['added' => 'id', 'company' => 'company'];

		if($request->has('sortBy')){
			$requestedSort = $request->get('sortBy');
			if(array_key_exists($requestedSort, $allowedSortColumns)){
				$output['sortBy'] = $requestedSort;
				$sortBy = $allowedSortColumns[$requestedSort];
			}
		}

		// Validate sort order
		if($request->has('sortOrder')){
			$requestedOrder = (int) $request->get('sortOrder');
			if($requestedOrder === 1){
				$sortOrder = "ASC";
				$output['sortOrder'] = 1;
			}else if($requestedOrder === 2){
				$sortOrder = "DESC";
				$output['sortOrder'] = 2;
			}
		}
		$sort =  $sortBy." ".$sortOrder;
		
		// generate link
		$output['link_string'] = '?';
		if(isset($_GET) && count($_GET)>0){
			$link_items = array();
			$link_string =  '?';
			$exclude_variables = array("page");
			foreach($_GET as $key=>$value){

				if(!(in_array($key,$exclude_variables))){
				$link_items[] = $key.'='.$value; 
				}

			}			

		if(count($link_items)>1){ 
			$link_string = implode('&',$link_items);
			$link_string = '?'.$link_string.'&'; 
		}else if(count($link_items)==1){
			$link_string = '?'.$link_items[0].'&'; 
		}
		$output['link_string'] = $link_string;
		}

		
		// pagination

		$limit = 10;
		if(isset($_GET['numRecords'])){
			$limit = $_GET['numRecords'];
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
			
			$num_records = $this->admin_model->getNumLogos($where,$sort); 
			
			$numPages = (int)($num_records/$limit); 

			$reminder = ($num_records%$limit);
			if($reminder>0)
			{
				$numPages = $numPages+1;

			}
			$output['numPages'] = $numPages;

		$output['start'] = $start;

		$output['currentPageNo'] = $currentPageNo;

		$output['currentPage'] = 'logos';
		if(isset($_GET['page'])) { 
			
			if ($_GET['page'] > $numPages) {

				return Redirect::to($output['currentPage'].$link_string."page=" . $numPages);

				exit;

			} else if ($_GET['page'] < 1) {

				return Redirect::to($output['currentPage'].$link_string."page=1");

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
		
		$output['logos'] =  $this->admin_model->getLogos($where,$sort,$start,$limit);
		if(isset($_GET['added'])){
			$output['alert_message'] = 'Logo added successfully!';
			$output['alert_class'] = 'alert alert-success';
		}else if(isset($_GET['error'])){
			$output['alert_message'] = 'Error occured, please try again!';
			$output['alert_class'] = 'alert alert-danger';

		}else if(isset($_GET['delete'])){
			$output['alert_message'] = 'Logo deleted successfully!';
			$output['alert_class'] = 'alert alert-success';
	}
		//echo'<pre>';print_r($output);die('--YSYS');
		return view('/admin/admin_company_logos', $output);
}

public function track_review_members_list(Request $request){

	ob_start();
	$output = array();
	$pageTitle = array();

	$where = '';

	// SECURITY FIX: Validate and sanitize all user inputs
	$trkid = (int) $request->get('tid'); // Cast to integer for safety
	$graphId = (int) $request->get('graphId');
	$label = htmlspecialchars($request->get('label'), ENT_QUOTES, 'UTF-8');
	$valIs = $request->get('val');

	// SECURITY FIX: Build query using array conditions instead of string concatenation
	$whereConditions = ['track' => $trkid];

        if ($graphId == 1) {
            $whereConditions['whatrate'] = $valIs;
            $pageTitle[1] = 'Members list who choose ' . $label . ' for - WHAT DO YOU THINK ABOUT THIS SONG?';
        } else if ($graphId == 2) {
            $value = explode('-OR-', $valIs);
            // Pass as array for OR condition handling in model
            $whereConditions['whereheard_or'] = [$value[0], $value[1]];
            $pageTitle[2] = 'Members list who choose ' . $label . ' for - WHERE DID YOU HEAR THIS SONG FIRST?';
        } else if ($graphId == 3) {
            $whereConditions['alreadyhave'] = $valIs;
            $pageTitle[3] = 'Members list who choose ' . $label . ' for - DO YOU ALREADY HAVE THIS SONG?';
        } else if ($graphId == 4) {
            $whereConditions['willplay'] = $valIs;
            $pageTitle[4] = 'Members list who choose ' . $label . ' for - WILL YOU PLAY THIS SONG?';
        } else if ($graphId == 5) {
            $whereConditions['howsoon'] = $valIs;
            $pageTitle[5] = 'Members list who choose ' . $label . ' for - WILL YOU PLAY THIS SONG?';
        } else if ($graphId == 6) {
            $whereConditions['howmanyplays'] = $valIs;
            $pageTitle[6] = 'Members list who choose ' . $label . ' for - HOW MANY TIMES WILL YOU PLAY THIS SONG (per week)?';
        } else if ($graphId == 7) {
            // SECURITY: Whitelist allowed column names to prevent SQL injection
            $allowedColumns = ['format1', 'format2', 'format3', 'format4', 'format5'];
            if (in_array($valIs, $allowedColumns)) {
                $whereConditions[$valIs] = '1';
            }
            $pageTitle[7] = 'Members list who choose ' . $label . ' for - WHAT FORMATS DO YOU THINK WILL HELP BREAK THIS SONG IN YOUR MARKET(check all that apply)?';
        } else if ($graphId == 8) {
            $whereConditions['godistance'] = $valIs;
            $pageTitle[8] = 'Members list who choose ' . $label . ' for - DO YOU THINK THIS RECORD WILL GET ANY SUPPORT?';
        } else if ($graphId == 9) {
            $whereConditions['labelsupport'] = $valIs;
            $pageTitle[9] = 'Members list who choose ' . $label . ' for - HOW SHOULD THE LABEL SUPPORT THIS PROJECT?';
        } else if ($graphId == 10) {
            $whereConditions['howsupport'] = $valIs;
            $pageTitle[10] = 'Members list who choose ' . $label . ' for - HOW WILL YOU SUPPORT THIS PROJECT?';
        } else if ($graphId == 11) {
            $whereConditions['likerecord'] = $valIs;
            $pageTitle[11] = 'Members list who choose ' . $label . ' for - WHAT DO YOU LIKE MOST ABOUT THIS RECORD?';
        } else if ($graphId == 12) {
            $whereConditions['anotherformat'] = $valIs;
            $pageTitle[12] = 'Members list who choose ' . $label . ' for - DO YOU WANT THIS SONG IN ANOTHER FORMAT?';
        }

        $output['members'] = $this->admin_model->getReviewMembers($whereConditions, $graphId);

        $output['pageTitle'] = $pageTitle[$graphId]; // SECURITY FIX: Use validated $graphId
        $output['tid'] = $trkid;
        $output['graphId'] = $graphId;
        $output['val'] = $valIs;
        $output['label'] = $label;
       return view('/admin/track_review_member_list', $output);       

	
}

public function track_member_review(Request $request){

	ob_start();
	$output = array();
	$pageTitle = array();

	$where = '';

	$trkid = $request->get('tid');
	$graphId = $request->get('graphId');
	$label = $request->get('label');
	$valIs = $request->get('val');
	$revId = $request->get('rid');

   	$output['review'] = $this->admin_model->getReview($revId);
   	$output['tid'] = $trkid;
  	$output['revId'] = $revId;
   	$output['graphId'] = $graphId;
   	$output['val'] = $valIs;
   	$output['label'] = $label;
     return view('/admin/track_member_review', $output);       

	
}
/**
 * View a Company Logo. 
 *
 * @GS
 */ 
public function admin_viewCompanyLogo(Request $request){
	$output = array();
	// header data pass starts
	$admin_name = Auth::user()->name;
	$user_role = Auth::user()->user_role;
	
		$logo_data = array(
			'logo_id' => 1,
			);

		$logo_details = DB::table('website_logo')
		->where($logo_data)
		->first();  
		
		$get_logo = $logo_details->logo;
	//  print_r($logo_details->logo);die;
		// echo $get_logo;die('---');
		$output['pageTitle'] = 'Logos';
		$output['logo'] = $get_logo;
		$output['welcome_name'] = $admin_name;
		$output['user_role'] = $user_role;
		
		// generate where

		$where = 'where ';

		$whereItems = array();

		$whereItems[] = "id = '". $_GET['lid'] ."'";
		
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

		$sortBy = "id";

		$output['sortBy'] = 'registered';

		$output['sortOrder'] = 2;

		$sort =  $sortBy." ".$sortOrder;
		$start = 0;
		$limit = 1;


		
			$output['logos'] =  $this->admin_model->getLogos($where,$sort,$start,$limit);
			if(isset($_GET['added'])){
				$output['alert_message'] = 'Logo added successfully!';
				$output['alert_class'] = 'alert alert-success';
			}else if(isset($_GET['error'])){
				$output['alert_message'] = 'Error occured, please try again!';
				$output['alert_class'] = 'alert alert-danger';

			}else if(isset($_GET['delete'])){
				$output['alert_message'] = 'Logo deleted successfully!';
				$output['alert_class'] = 'alert alert-success';
		}
		//echo'<pre>';print_r($output);die('--YSYS');
		return view('/admin/admin_company_viewlogo', $output);
}
/**
 * Edit/Update a Company Logo.
 *
 * @GS
 */ 
public function admin_editCompanyLogo(Request $request){
	$output = array();
	// header data pass starts
	$admin_name = Auth::user()->name;
	$user_role = Auth::user()->user_role;
	$loggedUserId = Auth::user()->id;
	
		$logo_data = array(
			'logo_id' => 1,
			);

		$logo_details = DB::table('website_logo')
		->where($logo_data)
		->first();  
		
		$get_logo = $logo_details->logo;		
		$output['pageTitle'] = 'Logos';
		$output['logo'] = $get_logo;
		$output['welcome_name'] = $admin_name;
		$output['user_role'] = $user_role;		  
		
	$pcloud_image_id='';
	if($request->get('lid') && !empty($request->get('lid'))){
		
		
			// generate where
			$logoId = $request->get('lid');
			
		if ($request->isMethod('post')) {
			$requestDta = $request->all();
			$resultID =  $this->admin_model->updateLogo($requestDta,$logoId,$loggedUserId);	// Update the Logo Data
			if($resultID>0){
				if($request->hasFile('image')){ // Check if File is added'''
				
				$query=DB::table('logos')->select('pCloudFileID_logo')->where('id',$logoId)->get();
            
               if(!empty($query[0]->pCloudFileID_logo)){
                    $pcloud_image_id=(int)$query[0]->pCloudFileID_logo;
                    
                    
                    if(!empty($pcloud_image_id)){
                     $delete_metadata = $this->delete_pcloud($pcloud_image_id);   
                    }
               }
				
						$resultID =  $this->admin_model->unlinkCompanyLogo($resultID);
						$fileLogo = $request->file('image') ;
						$image_name = $fileLogo->getClientOriginalName();
						$filename = pathinfo($image_name,PATHINFO_FILENAME);
						$image_ext = $fileLogo->getClientOriginalExtension();
						//$fileNameToStore = $filename.'-'.time().'.'.$image_ext;
						$date_time_array = getdate(time());
						$fileNameToStore =	$date_time_array["year"].$date_time_array["mon"].$date_time_array["mday"].$date_time_array["hours"].$date_time_array["minutes"].$date_time_array["seconds"].'.'.$image_ext;					   
				// 		$destinationPath = public_path().'/Logos' ;
				// 		$fileLogo->move($destinationPath,$fileNameToStore);
				// 		//$path =  $request->file('image')->storeAs('public/Logos',$fileNameToStore);
				// 	$this->admin_model->addLogoImage($logoId,$fileNameToStore);  
				
				
				    	$destinationPath = base_path('Logos/'); //.'/Logos' ;
        				
        			 // echo	$destinationPath;die();
        				
        				$pcloudFileId='';
        				$parentfolderid='';
        				
        				
        			$upload_data  =	$fileLogo->move($destinationPath,$fileNameToStore);
        			  
        			  if(!empty($upload_data)){
        				 
        				$folder = 13199825142;  // PCLOUD_FOLDER_ID
                
                    	$metadata = $this->uploadImage_to_pcloud($destinationPath, $fileNameToStore, $folder);
                    	
                    	$pcloudFileId = $metadata->metadata->fileid;
                    	$parentfolderid = $metadata->metadata->parentfolderid;
                    	@unlink($destinationPath.$fileNameToStore);
                    	
        			  }	
        				
				
				
				
				
				
				//$path =  $request->file('image')->storeAs('public/Logos',$fileNameToStore);
				$this->admin_model->addLogoImage_trm_admin($resultID,$fileNameToStore,$pcloudFileId,$parentfolderid);  
				
				}
				
				return Redirect::to("admin/logo_edit?lid=".$logoId."&updated=1");   exit;
			}else{
				return Redirect::to("admin/logo_edit?lid=".$logoId."&error=1");   exit;
			}
		}

		$where = 'where ';

		$whereItems = array();

		$whereItems[] = "id = '". $request->get('lid') ."'";
		
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

		$sortBy = "id";
		
		$sort =  $sortBy." ".$sortOrder;
		
		$output['currentPage'] = 'logos';
		
		$output['logos'] =  $this->admin_model->getLogos($where,$sort,0,1);			
	}		
	
	
	if($request->get('updated')){

		$output['alert_message'] = 'Logo updated successfully!';

		$output['alert_class'] = 'alert alert-success';  

	}else if($request->get('error')){

		$output['alert_message'] = 'Error occured, please try again!';

		$output['alert_class'] = 'alert alert-danger';

	}		
	return view('/admin/admin_company_editlogo', $output);
}

/**
 * Add New a Company Logo.
 *
 * @GS
 */
	public function admin_addCompanyLogo(Request $request){
	$output = array();
	// header data pass starts
	$admin_name = Auth::user()->name;
	$user_role = Auth::user()->user_role;
	$loggedUserId = Auth::user()->id;
	
	$logo_data = array(
			'logo_id' => 1,
			);

		$logo_details = DB::table('website_logo')
		->where($logo_data)
		->first();  
		
		$get_logo = $logo_details->logo;
	
	$output['pageTitle'] = 'Add Logo';
	$output['logo'] = $get_logo;
	$output['welcome_name'] = $admin_name;
	$output['user_role'] = $user_role;
	
	if ($request->isMethod('post')) {
	$requestDta = $request->all();
	$resultID =  $this->admin_model->addNewLogo($requestDta, $loggedUserId); // Add Logo Meta Info in DB and Get InsertID
	if($resultID>0){
		if($request->hasFile('image')){ // Check if File is added				   
				$fileLogo = $request->file('image') ;
				$image_name = $fileLogo->getClientOriginalName();
				$filename = pathinfo($image_name,PATHINFO_FILENAME);
				$image_ext = $fileLogo->getClientOriginalExtension();
				//$fileNameToStore = $filename.'-'.time().'.'.$image_ext;
				$date_time_array = getdate(time());
				$fileNameToStore =	$date_time_array["year"].$date_time_array["mon"].$date_time_array["mday"].$date_time_array["hours"].$date_time_array["minutes"].$date_time_array["seconds"].'.'.$image_ext;					   
				$destinationPath = base_path('Logos/'); //.'/Logos' ;
				
			 // echo	$destinationPath;die();
				
				$pcloudFileId='';
				$parentfolderid='';
				
				
			$upload_data  =	$fileLogo->move($destinationPath,$fileNameToStore);
			  
			  if(!empty($upload_data)){
				 
				$folder = 13199825142;  // PCLOUD_FOLDER_ID
        
            	$metadata = $this->uploadImage_to_pcloud($destinationPath, $fileNameToStore, $folder);
            	
            	$pcloudFileId = $metadata->metadata->fileid;
            	$parentfolderid = $metadata->metadata->parentfolderid;
            	@unlink($destinationPath.$fileNameToStore);
            	
			  }	
				
				
				
				
				
				
				//$path =  $request->file('image')->storeAs('public/Logos',$fileNameToStore);
				$this->admin_model->addLogoImage_trm_admin($resultID,$fileNameToStore,$pcloudFileId,$parentfolderid);  
		}
		
		return Redirect::to("admin/logo_add?added=1");   exit;
	}else{
		return Redirect::to("admin/logo_add?error=1");   exit;
	}
	}
	
	if($request->get('added')){

		$output['alert_message'] = 'Logo added successfully!';

		$output['alert_class'] = 'alert alert-success';  

	}else if($request->get('error')){

		$output['alert_message'] = 'Error occured, please try again!';

		$output['alert_class'] = 'alert alert-danger';

	}
	
	return view('/admin/admin_company_addlogo', $output);
	}
/**
 * List Genres.
 *
 * @GS
 */	 
	public function admin_listGenres(Request $request){
	$output = array();
	// header data pass starts
	$admin_name = Auth::user()->name;
	$user_role = Auth::user()->user_role;
	$loggedUserId = Auth::user()->id;
	
	$logo_data = array(
			'logo_id' => 1,
			);

		$logo_details = DB::table('website_logo')
		->where($logo_data)
		->first();  
		
		$get_logo = $logo_details->logo;
	
	$output['pageTitle'] = 'Genres';
	$output['logo'] = $get_logo;
	$output['welcome_name'] = $admin_name;
	$output['user_role'] = $user_role;
	
// delete sub genre

		if(isset($_GET['did']))

		{

	$result = $this->admin_model->deleteGenre($_GET['did']); 	 

	

	if($result>0)

	{

	header("location: genres?delete=success");

	}

	else

	{

	header("location: genres?error=1");

	}

	

		

		}

		

		

		// add genre

		if(isset($_POST['addGenre']))

		{

		

	$result = $this->admin_model->addGenre($_POST['genre']); 	 

		

	if($result>0)

	{

	header("location: genres?added=success");

	}

	else

	{

	header("location: genres?error=1");

	}

		exit;

		}

		

		
	$ajaxOutput['start'] = 0; 
	
		// delete sub genre

		if(isset($_GET['deleteSubGenre']) && isset($_GET['subDid']))

		{

		

	$result = $this->admin_model->deleteSubGenre($_GET['subDid']); 	 

		

		if($result>0)

		{

		$ajaxOutput['status'] = 'Sub Genre deleted successfully !';

		$ajaxOutput['textColor'] = '#090';

		}

		else

		{ 

		$ajaxOutput['status'] = 'Error occured, please try again !';  

		$ajaxOutput['textColor'] = '#ff3342';

		}

		

		$ajaxOutput['genreId'] = $_GET['genreId'];

		$ajaxOutput['subGenres'] =  $this->admin_model->getSubGenres($_GET['genreId']); 

		return view('/admin/admin_list_sub_genres',$ajaxOutput);

		exit;

		}

		

		// update sub genre

		if(isset($_GET['updateSubGenre']) && isset($_GET['subGenreId']) && isset($_GET['subGenre']))

		{

		

		$result = $this->admin_model->updateSubGenre($_GET['subGenreId'],$_GET['subGenre']);

					

		if($result>0)

		{

		$arr = array('response' => 1, 'message' => 'Sub Genre updated successfully !');

		echo json_encode($arr);

		exit;

		}

		else

		{

		$arr = array('response' => 0, 'message' => 'Error occured, please try again!');

		echo json_encode($arr);

		exit;

		}

		exit;

		}

		

		// update genre

		if(isset($_GET['updateGenre']))

		{

		

		$result = $this->admin_model->updateGenre($_GET['genre'],$_GET['id']);

					

		if($result>0)

		{

		$arr = array('response' => 1, 'message' => 'Genre updated successfully !');

		echo json_encode($arr);

		exit;

		}

		else

		{

		$arr = array('response' => 0, 'message' => 'Error occured, please try again!');

		echo json_encode($arr);

		exit;

		}

		exit;

		}

		

		// display sub genres

		if(isset($_GET['id']) && isset($_GET['subGenres']) && isset($_GET['view']))

		{

		

		$ajaxOutput['subGenres'] =  $this->admin_model->getSubGenres($_GET['id']); 

		$ajaxOutput['genreId'] = $_GET['id'];

		return view('/admin/admin_list_view_sub_genres',$ajaxOutput);

		exit;

		}

		

		// display sub genres for edit

		if(isset($_GET['id']) && isset($_GET['subGenres']) && isset($_GET['edit']))

		{

		

		$ajaxOutput['subGenres'] =  $this->admin_model->getSubGenres($_GET['id']); 

		$ajaxOutput['genreId'] = $_GET['id'];

		return view('/admin/admin_edit_sub_genres',$ajaxOutput);

		exit;

		}

		

		// add sub genre

		if(isset($_GET['genreId']) && isset($_GET['subGenre']) && isset($_GET['addSubGenre']))

		{

		$result =  $this->admin_model->addSubGenre($_GET['genreId'],$_GET['subGenre']); 

		

		if($result>0)

		{

		$ajaxOutput['status'] = 'Sub Genre added successfully !';

		$ajaxOutput['textColor'] = '#090';

		}

		else

		{ 

		$ajaxOutput['status'] = 'Error occured, please try again !'; 

		$ajaxOutput['textColor'] = '#ff3342'; 

		}

		

		$ajaxOutput['genreId'] = $_GET['genreId'];

		$ajaxOutput['subGenres'] =  $this->admin_model->getSubGenres($_GET['genreId']); 
		/* echo'<pre>';print_r($ajaxOutput);die('--YSYS'); */
		return view('/admin/admin_list_sub_genres',$ajaxOutput);

		exit;

		}

		

	// generate where

	$where = 'where ';

	$whereItems = array();

	//$whereItems[] = "admin_id != '0'";

	

	$output['searchCompany'] = '';

	

	

		if(isset($_GET['genre']) && strlen($_GET['genre'])>0)

		{

			$output['searchGenre'] = $_GET['genre'];

			$whereItems[] = "genres.genre = '". $_GET['genre'] ."'";

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

	$sortBy = "genre";

	$output['sortBy'] = 'genre';

	$output['sortOrder'] = 1;

	

	if(isset($_GET['sortBy']))

	{

		$output['sortBy'] = $_GET['sortBy'];

	

		if(strcmp($_GET['sortBy'],'genreId')==0)

		{

			$sortBy = "genreId";

		}

		else if(strcmp($_GET['sortBy'],'genre')==0)

		{

			$sortBy = "genre";

		}

	}

	

	// sort order	  		   

	if(isset($_GET['sortOrder']) && $_GET['sortOrder']==1)

	{

	

		$sortOrder = "ASC";

		$output['sortOrder'] = $_GET['sortOrder'];

	}

	else  if(isset($_GET['sortOrder']) && $_GET['sortOrder']==2)

	{

	

		$sortOrder = "DESC";

		$output['sortOrder'] = $_GET['sortOrder'];

	}

	$sort =  $sortBy." ".$sortOrder;

	

	// generate link

	$output['link_string'] = '?';

	if(isset($_GET) && count($_GET)>0)

	{

		$link_items = array();

		$link_string =  '?';

		$exclude_variables = array("page");

		

		foreach($_GET as $key=>$value)

		{

			if(!(in_array($key,$exclude_variables)))

			{

			$link_items[] = $key.'='.$value; 

			}

		}

		

	if(count($link_items)>1)

	{ 

		$link_string = implode('&',$link_items);

		$link_string = '?'.$link_string.'&'; 

	}

	else if(count($link_items)==1)

	{

		$link_string = '?'.$link_items[0].'&'; 

	}

	

	$output['link_string'] = $link_string;

	}

	

	// pagination

	$limit = 10;

	if(isset($_GET['numRecords']))

	{

		$limit = $_GET['numRecords'];

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

	

	$num_records = $this->admin_model->getNumGenres($where,$sort); 

	$numPages = (int)($num_records/$limit); 

	$reminder = ($num_records%$limit);

	

	if($reminder>0)

	{

		$numPages = $numPages+1;

	}



	$output['numPages'] = $numPages;

	$output['start'] = $start;

	$output['currentPageNo'] = $currentPageNo;

	$output['currentPage'] = 'genres';

	

	

	if(isset($_GET['page'])) {

	if ($_GET['page'] > $numPages) {

		header("location: ".$output['currentPage'].$link_string."page=" . $numPages); 

		exit;

	} else if ($_GET['page'] < 1) {

		header("location: ".$output['currentPage'].$link_string."page=1");

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

		

	

$output['genres'] =  $this->admin_model->getGenres($where,$sort,$start,$limit); 







		if(isset($_GET['added']))

		{

			$output['alert_message'] = 'Genre added successfully!';

			$output['alert_class'] = 'alert alert-success';

			

		}

		else if(isset($_GET['error']))

		{

			$output['alert_message'] = 'Error occured, please try again!';

			$output['alert_class'] = 'alert alert-danger';

		}

		else if(isset($_GET['delete']))

		{

			$output['alert_message'] = 'Genre deleted successfully!';

			$output['alert_class'] = 'alert alert-success';

		}

	return view('/admin/admin_list_genres', $output);
	}
	
/**
 * List Dj Tools.
 *
 * @GS
 */	 
	public function admin_listTools(Request $request){
	$output = array();
	// header data pass starts
	$admin_name = Auth::user()->name;
	$user_role = Auth::user()->user_role;
	$loggedUserId = Auth::user()->id;
	
	$logo_data = array(
			'logo_id' => 1,
			);

		$logo_details = DB::table('website_logo')
		->where($logo_data)
		->first();  
		
		$get_logo = $logo_details->logo;
	
	$output['pageTitle'] = 'DJ Tools';
	$output['logo'] = $get_logo;
	$output['welcome_name'] = $admin_name;
	$output['user_role'] = $user_role;
	
	// delete member

	if(isset($_GET['did'])){
		$result = $this->admin_model->deleteTool($_GET['did']); 
		if($result>0){
			return Redirect::to("admin/tools/tools?delete=success");   exit;
		}else{
			return Redirect::to("admin/tools/tools?error=1");   exit;
		}
	}
	
	// generate where 
	$where = 'where ';
	$whereItems = array();
	$output['searchTittle'] = '';
	$output['searchName'] = '';
	
	if(isset($_GET['search'])){	   

		if(isset($_GET['tittle']) && strlen($_GET['tittle'])>0){

			$output['searchTittle'] = $_GET['tittle'];

			$whereItems[] = "tool_tracks.tool_track_tittle like '%". $_GET['tittle'] ."%'";

		}		   

		if(isset($_GET['name']) && strlen($_GET['name'])>0){

			$output['searchName'] = $_GET['name'];

			$whereItems[] = "admins.name = '". $_GET['name'] ."'";

		}
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

	$sortBy = "tool_tracks.tool_track_id";

	$output['sortBy'] = 'added_on';

	$output['sortOrder'] = 2;
	
	if(isset($_GET['sortBy']) && isset($_GET['sortOrder'])){

		$output['sortBy'] = $_GET['sortBy'];

		$output['sortOrder'] = $_GET['sortOrder'];
		

		if(strcmp($_GET['sortBy'],'tittle')==0){

			$sortBy = "tool_tracks.tool_track_tittle";

		}else if(strcmp($_GET['sortBy'],'added_on')==0){

			$sortBy = "tool_tracks.added_on";

		}else if(strcmp($_GET['sortBy'],'added_by')==0){

			$sortBy = "admins.name";

		}	   

		if($_GET['sortOrder']==1){   

			$sortOrder = "ASC";

		}else  if($_GET['sortOrder']==2){	    

			$sortOrder = "DESC";

		}
	}

	$sort =  $sortBy." ".$sortOrder;
	
	// pagination

	$limit = 10;

	if(isset($_GET['records'])){

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
	
	$num_records = $this->admin_model->getNumTools($where,$sort); 

	$numPages = (int)($num_records/$limit); 

	$reminder = ($num_records%$limit);
	
	if($reminder>0){
		
		$numPages = $numPages+1;
	}
	
	$output['numPages'] = $numPages;

	$output['start'] = $start;

	$output['currentPageNo'] = $currentPageNo;

	$output['currentPage'] = 'tools/'; 
	
	if(isset($_GET['page'])) {
		if ($_GET['page'] > $numPages) {

			return Redirect::to("admin/tools/tools/?page=" . $numPages);

			exit;

		} else if ($_GET['page'] < 1) {

			return Redirect::to("admin/tools/tools/?page=1"); 

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
	$output['tools'] = $this->admin_model->getTools($where,$sort,$start,$limit); 
	/* echo '<pre>';print_r($output);die('---YSYSYS'); */
	//$output['clients'] = $result['data'];
	
	if(isset($_GET['delete'])){

		$output['alert_message'] = "Client deleted succesfully!";

		$output['alert_class'] = "alert alert-success";

	}else  if(isset($_GET['error'])){

		$output['alert_message'] = "Error occured, please try again!";

		$output['alert_class'] = "alert alert-danger";

	}
	
	return view('/admin/admin_list_djtools',$output);
	}
/**
 * Edit/Update Dj Tools.
 *
 * @GS
 */		 
public function admin_editDjTool(Request $request){
	$output = array();
	// header data pass starts
	$admin_name = Auth::user()->name;
	$user_role = Auth::user()->user_role;
	$loggedUserId = Auth::user()->id;
	
	$logo_data = array(
			'logo_id' => 1,
			);

		$logo_details = DB::table('website_logo')
		->where($logo_data)
		->first();  
		
		$get_logo = $logo_details->logo;
	
	$output['pageTitle'] = 'Edit Dj Tool';
	$output['logo'] = $get_logo;
	$output['welcome_name'] = $admin_name;
	$output['user_role'] = $user_role;
	
			// add tool

	if(isset($_POST['updateDjTool'])){		 

	$lastId = $this->admin_model->updateTool($_POST['tittle'],$_GET['tid']);  

	if($lastId>0){
		
				// audio files upload

		$fileCount = $_POST['divId'];

		$preview = 0;
		
		for($i=1; $i<=$fileCount; $i++){
			/* if(isset($_FILES['audio'.$i]['name']) && strlen($_FILES['audio'.$i]['name'])>4){
			$fileName = md5(rand(1000,10000));
			$config['file_name'] = $lastId.'_'.$fileName.'.mp3';
			$ext = explode('.',$_FILES['audio'.$i]['name']);
			$count = count($ext);
			$ext = $ext[$count-1];
			echo '<pre>';print_r($fileName.'--'.$ext);die('--NAME');
			} */
			if(!empty($request->file('audio'.$i))){ // Check if File is added	
			
				/* $this->admin_model->unlinkDjToolFile($_GET['tid']); // Unlink Dj Tool File if Exists */
				
				$fileAudio = $request->file('audio'.$i) ;
				$audio_name = $fileAudio->getClientOriginalName(); 
				
				$filename = pathinfo($audio_name,PATHINFO_FILENAME);
				$audio_ext = $fileAudio->getClientOriginalExtension();
				//$fileNameToStore = $filename.'-'.time().'.'.$audio_ext;
				$date_time_array = getdate(time());
				$fileNameDynamic =	$date_time_array["year"].$date_time_array["mon"].$date_time_array["mday"].$date_time_array["hours"].$date_time_array["minutes"].$date_time_array["seconds"].'.'.$audio_ext;					   
				$destinationPath = public_path().'/tools' ;
				$fileName = md5(rand(1000,10000));
				$fileNameToStore = $lastId.'_'.$fileNameDynamic;
				$fileAudio->move($destinationPath,$fileNameToStore);
				//$path =  $request->file('image')->storeAs('public/Logos',$fileNameToStore);
				//$this->admin_model->addLogoImage($resultID,$fileNameToStore); 
				$this->admin_model->addMp3($fileNameToStore,$_GET['tid']);					
		}			  
		}

		return Redirect::to("admin/tools/edit_tool?tid=".$_GET['tid']."&updated=1");   exit;

	}else{

		return Redirect::to("admin/tools/edit_tool?tid=".$_GET['tid']."&error=1");   exit;
	}

	}

	$output['tools'] = $this->admin_model->getTool($_GET['tid']); 

	$output['audios'] = $this->admin_model->getMp3($_GET['tid']); 
	/* echo '<pre>';print_r($output);die('--NAME'); */
	if(isset($_GET['updated'])){	  

		$output['alert_class'] = 'alert alert-success';

		$output['alert_message'] = 'Updated successfully !';

	

	}else if(isset($_GET['error'])){

		$output['alert_class'] = 'alert alert-danger';

		$output['alert_message'] = 'Error occured, please try again.';

	}else if(isset($_GET['delete'])){

		$output['alert_class'] = 'alert alert-success';

		$output['alert_message'] = 'Deleted successfully !';

	}
	
	return view('/admin/admin_edit_djtools',$output);
	
}

/**
 * Add DjTool.
 *
 * @GS
 */
public function admin_addDjTool(Request $request){
	$output = array();
	// header data pass starts
	$admin_name = Auth::user()->name;
	$user_role = Auth::user()->user_role;
	$loggedUserId = Auth::user()->id;
	
	$logo_data = array(
			'logo_id' => 1,
			);

		$logo_details = DB::table('website_logo')
		->where($logo_data)
		->first();  
		
		$get_logo = $logo_details->logo;
	
	$output['pageTitle'] = 'Digiwaxx Admin - Add Dj Tool';
	$output['logo'] = $get_logo;
	$output['welcome_name'] = $admin_name;
	$output['user_role'] = $user_role;

		 // add tool

	 if(isset($_POST['addDjTool'])){	 

	   $lastId = $this->admin_model->addTool($_POST, $loggedUserId); 

	   if($lastId>0){

			// audio files upload

			$fileCount = $_POST['divId'];

			$preview = 0;
			
				for($i=1; $i<=$fileCount; $i++){					
					if(!empty($request->file('audio'.$i))){ // Check if File is added	
					
						/* $this->admin_model->unlinkDjToolFile($_GET['tid']); // Unlink Dj Tool File if Exists */
						
						$fileAudio = $request->file('audio'.$i) ;
						$audio_name = $fileAudio->getClientOriginalName(); 
						
						$filename = pathinfo($audio_name,PATHINFO_FILENAME);
						$audio_ext = $fileAudio->getClientOriginalExtension();
						//$fileNameToStore = $filename.'-'.time().'.'.$audio_ext;
						$date_time_array = getdate(time());
						$fileNameDynamic =	$date_time_array["year"].$date_time_array["mon"].$date_time_array["mday"].$date_time_array["hours"].$date_time_array["minutes"].$date_time_array["seconds"].'.'.$audio_ext;					   
						$destinationPath = public_path().'/tools' ;
						$fileName = md5(rand(1000,10000));
						$fileNameToStore = $lastId.'_'.$fileNameDynamic;
						$fileAudio->move($destinationPath,$fileNameToStore);
						//$path =  $request->file('image')->storeAs('public/Logos',$fileNameToStore);
						//$this->admin_model->addLogoImage($resultID,$fileNameToStore); 
						$this->admin_model->addMp3($fileNameToStore,$lastId);					
				}			  
			}

		   return Redirect::to("admin/tools/add_tool?success=1");   exit;
	   }else{
			return Redirect::to("admin/tools/add_tool?error=1");   exit;
	   }

	 }
	if(isset($_GET['success'])){	  

		   $output['alert_class'] = 'alert alert-success';

		   $output['alert_message'] = 'Dj Tool Added successfully !';	

	}else if(isset($_GET['error'])){
		
		   $output['alert_class'] = 'alert alert-danger';

		   $output['alert_message'] = 'Error occured, please try again.';
	}	 
	
	return view('/admin/admin_addDjTool',$output);
}
 /**
 * List FAQs.
 *
 * @GS
 */		 
public function admin_listFaqs(Request $request){
	
	$output = array();
	// header data pass starts
	$admin_name = Auth::user()->name;
	$user_role = Auth::user()->user_role;
	$loggedUserId = Auth::user()->id;
	
	$logo_data = array(
			'logo_id' => 1,
			);

		$logo_details = DB::table('website_logo')
		->where($logo_data)
		->first();  
		
		$get_logo = $logo_details->logo;
	
	$output['pageTitle'] = 'Digiwaxx Admin - Faqs';
	$output['logo'] = $get_logo;
	$output['welcome_name'] = $admin_name;
	$output['user_role'] = $user_role;
	
	if(isset($_GET['did'])){
	$result = $this->admin_model->deleteFaq($_GET['did']);
	if($result>0){
		return Redirect::to("admin/faqs?delete=success");exit;
	}else{
		return Redirect::to("admin/faqs?error=1");exit;
	}
	}
	
	// generate sort

	$sortOrder = "DESC";

	$sortBy = "faqs.faq_id";

	$output['sortOrder'] = 2;		

	// sort order	  		   

	if(isset($_GET['sortOrder']) && $_GET['sortOrder']==1){		

		$sortOrder = "ASC";

		$output['sortOrder'] = $_GET['sortOrder'];

	}else  if(isset($_GET['sortOrder']) && $_GET['sortOrder']==2){		

		$sortOrder = "DESC";

		$output['sortOrder'] = $_GET['sortOrder'];

	}
	$sort =  $sortBy." ".$sortOrder; 

	// generate where  

	$where = 'where ';

	$whereItems = array();
	

	if(count($whereItems)>1){
		
		$whereString = implode(' AND ',$whereItems);

		$where .= $whereString;
	}else if(count($whereItems)==1){
		
		$where .= $whereItems[0]; 

	}else{

		$where =  '';

	}
	//	$where;
	
	// generate link

	$output['link_string'] = '?';

	if(isset($_GET) && count($_GET)>0){

		$link_items = array();

		$link_string =  '?';

		$exclude_variables = array("page");
		

		foreach($_GET as $key=>$value){

			if(!(in_array($key,$exclude_variables))){
				
			$link_items[] = $key.'='.$value; 
			
			}
		}
		

	if(count($link_items)>1){ 

		$link_string = implode('&',$link_items);

		$link_string = '?'.$link_string.'&'; 

	}else if(count($link_items)==1){

		$link_string = '?'.$link_items[0].'&'; 

	}		

	$output['link_string'] = $link_string;

	}

	$output['link_string'];
	
	// pagination

	$limit = 10;

	if(isset($_GET['numRecords'])){

		$limit = $_GET['numRecords'];

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

	$num_records = $this->admin_model->getNumFaqs($where,$sort); 

	$numPages = (int)($num_records/$limit); 

	$reminder = ($num_records%$limit);

	

	if($reminder>0){

		$numPages = $numPages+1;

	}



	$output['numPages'] = $numPages;

	$output['start'] = $start;

	$output['currentPageNo'] = $currentPageNo;

	$output['currentPage'] = 'countries';


	if(isset($_GET['page'])) {

		if ($_GET['page'] > $numPages) {

			return Redirect::to("admin/".$output['currentPage'].$link_string."page=" . $numPages);

			exit;

		} else if ($_GET['page'] < 1) {

			return Redirect::to("admin/".$output['currentPage'].$link_string."page=1");

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
	if(isset($_GET['added'])){			  

			$output['alert_class'] = 'alert alert-success';

			$output['alert_message'] = 'FAQ added  successfully !';
	}else if(isset($_GET['error'])){

			$output['alert_class'] = 'alert alert-danger';

			$output['alert_message'] = 'Error occured, please try again.';

	}else if(isset($_GET['delete'])){

			$output['alert_class'] = 'alert alert-success';

			$output['alert_message'] = 'FAQ deleted successfully.';
	}
	
	$output['faqs'] = $this->admin_model->getFaqs($where,$sort,$start,$limit);
		
	return view('/admin/admin_listFaqs',$output);
}
/**
 * Edit FAQs.
 *
 * @GS
 */		
public function admin_editFaqs(){
	$output = array();
	// header data pass starts
	$admin_name = Auth::user()->name;
	$user_role = Auth::user()->user_role;
	$loggedUserId = Auth::user()->id;
	
	$logo_data = array(
			'logo_id' => 1,
			);

		$logo_details = DB::table('website_logo')
		->where($logo_data)
		->first();  
		
		$get_logo = $logo_details->logo;
	
	$output['pageTitle'] = 'Digiwaxx Admin - Edit Faq';
	$output['logo'] = $get_logo;
	$output['welcome_name'] = $admin_name;
	$output['user_role'] = $user_role;
	
	if(isset($_POST['updateFaq']) && isset($_GET['fid'])){	 

	$result = $this->admin_model->updateFaq($_POST,$_GET['fid']); 	   

	if($result>0){

		return Redirect::to("admin/faq_edit?fid=".$_GET['fid']."&updated=success");

	}else{

		return Redirect::to("admin/faq_edit?fid=".$_GET['fid']."&error=1");

	}
	exit;

	}
	
		$where = "where faqs.faq_id = '". $_GET['fid'] ."'";

		$sort = "faqs.faq_id desc";

		$start = 0;

		$limit = 1;

		$output['faqs'] = $this->admin_model->getFaqs($where,$sort,$start,$limit); 
		
		if(isset($_GET['updated'])){  

			$output['alert_class'] = 'alert alert-success';

			$output['alert_message'] = 'FAQ updated  successfully !';
	}else if(isset($_GET['error'])){

			$output['alert_class'] = 'alert alert-danger';

			$output['alert_message'] = 'Error occured, please try again.';
	}
		
	return view('/admin/admin_editFaqs',$output);
}

	/**
	* View FAQ.
	*
	* @GS
	*/		
public function admin_viewFaq(){
	$output = array();
	// header data pass starts
	$admin_name = Auth::user()->name;
	$user_role = Auth::user()->user_role;
	$loggedUserId = Auth::user()->id;
	
	$logo_data = array(
			'logo_id' => 1,
			);

		$logo_details = DB::table('website_logo')
		->where($logo_data)
		->first();  
		
		$get_logo = $logo_details->logo;
	
	$output['pageTitle'] = 'Digiwaxx Admin - View Faq';
	$output['logo'] = $get_logo;
	$output['welcome_name'] = $admin_name;
	$output['user_role'] = $user_role;
	
	$where = "where faqs.faq_id = '". $_GET['fid'] ."'";

		$sort = "faqs.faq_id desc";

		$start = 0;

		$limit = 1;

		$output['faqs'] = $this->admin_model->getFaqs($where,$sort,$start,$limit);
		
	return view('/admin/admin_viewFaq',$output);
}
	/**
	* Add New FAQ.
	*
	* @GS
	*/	
public function admin_addNewFaq(){
	$output = array();
	// header data pass starts
	$admin_name = Auth::user()->name;
	$user_role = Auth::user()->user_role;
	$loggedUserId = Auth::user()->id;
	
	$logo_data = array(
			'logo_id' => 1,
			);

		$logo_details = DB::table('website_logo')
		->where($logo_data)
		->first();  
		
		$get_logo = $logo_details->logo;
	
	$output['pageTitle'] = 'Digiwaxx Admin - Add Faq';
	$output['logo'] = $get_logo;
	$output['welcome_name'] = $admin_name;
	$output['user_role'] = $user_role;
	
	// add fqa
	 if(isset($_POST['addFaq'])){

	   $result = $this->admin_model->addFaq($_POST,$loggedUserId);	   

	   if($result>0){
		  return Redirect::to("admin/faqs?added=success");
	   }else{
		 return Redirect::to("admin/faqs?error=1");
	   } 
	  exit;
	}
	
	return view('/admin/admin_addNewFaq',$output);
}

	/**
	* List Mails.
	*
	* @GS
	*/	
	public function admin_listMails(){
		$output = array();
		// header data pass starts
		$admin_name = Auth::user()->name;
		$user_role = Auth::user()->user_role;
		$loggedUserId = Auth::user()->id;
		
		$logo_data = array(
				'logo_id' => 1,
				);

			$logo_details = DB::table('website_logo')
			->where($logo_data)
			->first();  
			
			$get_logo = $logo_details->logo;
		
		$output['pageTitle'] = 'Digiwaxx Admin - Mails';
		$output['logo'] = $get_logo;
		$output['welcome_name'] = $admin_name;
		$output['user_role'] = $user_role;
	
		if(isset($_GET['did'])){
			$result = $this->admin_model->deleteMailsFromAdmin($_GET['did']);
			if($result>0){
				return Redirect::to("admin/mails?delete=success");exit;
			}else{
				return Redirect::to("admin/mails?error=1");exit;
			}
		} 
		
		// generate where

		$where = 'where ';

		$whereItems = array();

		

		$output['searchCompany'] = '';

		$output['searchUsername'] = '';

		$output['searchName'] = '';

		$output['searchEmail'] = '';

		$output['searchPhone'] = '';

		$output['searchCity'] = '';

		$output['searchState'] = '';

		$output['searchZip'] = '';
		
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

		$sortBy = "id";

		$output['sortBy'] = 'registered';

		$output['sortOrder'] = 2;		

		if(isset($_GET['sortBy']) && isset($_GET['sortOrder'])){

		   $output['sortBy'] = $_GET['sortBy'];

		   $output['sortOrder'] = $_GET['sortOrder'];		   

		   if(strcmp($_GET['sortBy'],'started')==0){		    

			 $sortBy = "id";

		   }else if(strcmp($_GET['sortBy'],'subject')==0){		    

			 $sortBy = "subject";

		   }

		   if($_GET['sortOrder']==1){

			 $sortOrder = "ASC";
			 
		   }else  if($_GET['sortOrder']==2){	    

			 $sortOrder = "DESC";

		   }

		}
		$sort =  $sortBy." ".$sortOrder;
		
		// pagination

		$limit = 10;

		if(isset($_GET['records'])){

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
		
        $num_records = $this->admin_model->getNumMails($where,$sort); 

	    $numPages = (int)($num_records/$limit); 

	    $reminder = ($num_records%$limit);
		
		if($reminder>0){
			 $numPages = $numPages+1;
		}		

		 $output['numPages'] = $numPages;

		 $output['start'] = $start;

		 $output['currentPageNo'] = $currentPageNo;

		 $output['currentPage'] = 'mails';
		 
		if(isset($_GET['page'])) {			
		 if ($_GET['page'] > $numPages) {
			 return Redirect::to("admin/".$output['currentPage']."?page=" . $numPages);	exit;		 
		 } else if ($_GET['page'] < 1) {
			 return Redirect::to("admin/".$output['currentPage']."?page=1");exit;
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
	
			if(isset($_GET['error']) && $_GET['delete'] == 1){

					$output['alertClass'] = 'alert alert-danger';

					$output['alertMessage'] = 'Error occured, please try again.';

			}else if(isset($_GET['delete']) && $_GET['delete'] == 'success'){

					$output['alertClass'] = 'alert alert-success';

					$output['alertMessage'] = 'Mail deleted successfully.';
			}
		
	    $output['mails'] =  $this->admin_model->getMails($where,$sort,$start,$limit);
		/* echo'<pre>';print_r($output);die('---'); */
		return view('/admin/admin_listMails',$output);
	}
	
  /**
	* Send Mail View.
	*
	* @GS
	*/
	
	public function admin_sendMail(){
		
		$output = array();
		// header data pass starts
		$admin_name = Auth::user()->name;
		$user_role = Auth::user()->user_role;
		$loggedUserId = Auth::user()->id;
		
		$logo_data = array(
				'logo_id' => 1,
				);

			$logo_details = DB::table('website_logo')
			->where($logo_data)
			->first();  
			
			$get_logo = $logo_details->logo;
		
		$output['pageTitle'] = 'Digiwaxx Admin - Send Mail';
		$output['logo'] = $get_logo;
		$output['welcome_name'] = $admin_name;
		$output['user_role'] = $user_role;
		
		// get search tracks

		if(isset($_GET['searchKey']) && isset($_GET['trackSearch'])){

		  $searchWhere =  "WHERE title  REGEXP '^[^a-z0-9]'";

		  if(strlen($_GET['searchKey'])>0){

		   $searchWhere = "WHERE title like '". $_GET['searchKey'] ."%'";

		  }	 

		  $result =  $this->admin_model->getSearchTracks($searchWhere); 	  

		 if($result['numRows']>0){
			 
			 foreach($result['data'] as $data){

			   $arr[] = array('id' => $data->id, 'title' => urldecode($data->title));

			 } 
			 echo json_encode($arr);
		 }
		  exit;
		}
		
		if(isset($_POST['sendMail'])){
			// track info
			$track =  $this->admin_model->getTrackInfo($_POST['track']); 
			
			if($track['numRows']>0){
				
		    }

			$destinationPath = public_path();
			$emailImgUrl = $destinationPath.'/ImagesUp/'.$track['data'][0]->imgpage;
			$emailImgUrlTo = url('public/ImagesUp/'.$track['data'][0]->imgpage);
			$emailLink = $track['data'][0]->link;
			if (file_exists($emailImgUrl)){
				$emailImgDimensions = getimagesize($emailImgUrl);
				$imgWidth = $emailImgDimensions[0];
				$imgHeight = $emailImgDimensions[1];
				if($imgWidth > 315){
					$imgWidth = 315;
				}
				$emailimg = '<a href="'.$emailLink.'"><img src='.$emailImgUrlTo.' width="'.$imgWidth.'" height="'.$imgHeight.'" border=\"0\"></a>';
			}
			$artist = urldecode($track['data'][0]->artist);

			$title = urldecode($track['data'][0]->title);

			$producer = urldecode($track['data'][0]->producer);

			$time = urldecode($track['data'][0]->time);

			$label = urldecode($track['data'][0]->label);

			$album = urldecode($track['data'][0]->album);

			$branddivision = urldecode($track['data'][0]->brand_division);

			$suggestedprice = urldecode($track['data'][0]->suggested_price);

			$launchdate = urldecode($track['data'][0]->launch_date);

			$productgender = urldecode($track['data'][0]->product_gender);

			$producttechnology = urldecode($track['data'][0]->product_technology);

			$productdetails = urldecode($track['data'][0]->product_details);

			$productmodel = urldecode($track['data'][0]->model);

			$productname = urldecode($track['data'][0]->product_name);

		    $amrfile = $track['data'][0]->amrfilename;

			$subject = "Digital Waxx  2.0 |  ".$artist." |  ".$title;
			
			$versions =  $this->admin_model->getTrackVersions($_POST['track']);
			
			$trackVersions = "";
			
			if($versions['numRows']>0){		    

				$trackVersions .= "<ul>"; 

				foreach($versions['data'] as $version){

				  $trackVersions .=  "<li>".urldecode($version->version)."</li>";

				}
				$trackVersions .= "</ul>"; 

			}
			
			// logos

		    $logo_line = '';

		    $logo_array = explode(",",$track['data'][0]->logos);
			
			foreach($logo_array as $logoId){				    

			  $trackLogos =  $this->admin_model->getTrackLogos($logoId); 
				
			    if($trackLogos['numRows']>0){

				   if(strlen($trackLogos['data'][0]->img)){ 				    

					$logoSrc = url("public/Logos/".$trackLogos['data'][0]->img);
					/* echo'<pre>';print_r($logoSrc);die('--YSYS'); */
					$logo_line .= "<span style=\"padding:5px;\">";

				

					if(strlen($trackLogos['data'][0]->url)) { $logo_line .= "<a href=\"".$trackLogos['data'][0]->url."\" target=\"_blank\">";}

					

					$logo_line .= "<img src=".$logoSrc." border=\"0\">";

				

					if(strlen($trackLogos['data'][0]->url)) { $logo_line .= "</a>";}

					

					

					$logo_line .= "</span><br>";

					}
				}

			}
			
			//organize and display contacts

			$contacts = '';

			$trackContacts =  $this->admin_model->getTrackContacts($_POST['track']); 			
			/* echo'<pre>';print_r($trackContacts);die('--YSYS'); */
			if($trackContacts['numRows']>0){

				foreach($trackContacts['data'] as $trackContact){

					$email = urldecode($trackContact->email);				

					if(strlen($trackContact->company)>0) { $contacts.= urldecode($trackContact->company)."<br>"; }

					if(strlen($trackContact->name)>0) { $contacts.= urldecode($trackContact->name)."<br>"; }

					if(strlen($trackContact->title)>0) { $contacts.= urldecode($trackContact->title)."<br>"; }

					if(strlen($email)>0) { $contacts.= "<a href=\"mailto:".$email."\">".$email."</a><br>"; }

					if(strlen($trackContact->phone)>0) { $contacts.= urldecode($trackContact->phone)."<br>"; }

					if(strlen($trackContact->mobile)>0) { $contacts.= urldecode($trackContact->mobile)."<br>"; }

					$contacts.= "<br><br>";
				}

		   }
		   
		     //is there more info?

		     $moreinfo = '';

			  if(strlen($track['data'][0]->moreinfo)>0){

						$moreinfo .= "<tr bgcolor=\"#D8D8D8\">";

						$moreinfo .= "	<td height=\"40\" colspan=\"2\"><div align=\"center\"><font color=\"#333333\" size=\"2\" face=\"Arial, Helvetica, Verdana\">".$track['data'][0]->moreinfo."</strong></font></div></td>";

						$moreinfo .= "</tr>";

			}

			if(strcmp($track['data'][0]->type,"product")==0){

				$subject = "Digital Waxx  2.0 |  ".$branddivision." |  ".$productname;

			}
			if(strcmp($track['data'][0]->type,"track")==0){

				$subject = "Digital Waxx  2.0 |  ".$artist." |  ".$title;

			}
			
			// get template

		   $html =  $this->admin_model->getTemplate($_POST['template']); 
		   
		   $error = 0;

		   $errorUrl = '';

		   if(isset($html[0]->imgwidth) && $imgWidth > $html[0]->imgwidth){

		    $errorUrl .= "&width=1";

			$error = 1;	

		  }
		  if(isset($html[0]->imgheight) && $imgHeight > $html[0]->imgheight){

		    $errorUrl .= "&height=1";

		    $error = 1;

		  }
		  //$error = 0;

		  if($error==0){
			  
		   if($html[0]->id==5){
			   
		     $htmlTemplate =  curl_getFileContents(url("resources/views/admin/mails/Product/".$html[0]->filename));
		     $htmlFileEmail =  "Product.".str_replace('.blade.php','',$html[0]->filename);

		   }else{
			   
		     $htmlTemplate =  curl_getFileContents(url("resources/views/admin/mails/".$html[0]->filename));
		     $htmlFileEmail =  str_replace('.blade.php','',$html[0]->filename);

		   }
			
		  
		  //replace appropriate areas of the template

		   $htmlTemplate = str_replace("*img*",urldecode($emailimg),$htmlTemplate);
		   
		   $dataEmail['img'] = urldecode($emailimg);

		   //$htmlTemplate = str_replace("*link*",urldecode($track['data'][0]->link),$htmlTemplate);

		  $htmlTemplate = str_replace("*artist*",$artist,$htmlTemplate);
		  $dataEmail['artist'] = $artist;

		  $htmlTemplate = str_replace("*title*",urldecode($title),$htmlTemplate);
		  $dataEmail['title'] = urldecode($title);

		  $htmlTemplate = str_replace("*producer*",urldecode($producer),$htmlTemplate);
		  $dataEmail['producer'] = urldecode($producer);

		  $htmlTemplate = str_replace("*time*",urldecode($time),$htmlTemplate);
		  $dataEmail['time'] = urldecode($time);

		$htmlTemplate = str_replace("*label*",urldecode($label),$htmlTemplate);
		$dataEmail['label'] = urldecode($label);

		$htmlTemplate = str_replace("*album*",urldecode($album),$htmlTemplate);
		$dataEmail['album'] = urldecode($album);

		$htmlTemplate = str_replace("*versions*",$trackVersions,$htmlTemplate);
		$dataEmail['versions'] = $trackVersions;

	//	$htmlTemplate = str_replace("*label*",$label,$htmlTemplate);

		$htmlTemplate = str_replace("*contact*",$contacts,$htmlTemplate);
		$dataEmail['contact'] = $contacts;

	//	$htmlTemplate = str_replace("*memberid*",$email["id"],$htmlTemplate);

		$htmlTemplate = str_replace("*logo*",$logo_line,$htmlTemplate);
		$dataEmail['logo'] = $logo_line;

	//  $htmlTemplate = str_replace("*key*",$email["pword"],$htmlTemplate);

		$htmlTemplate = str_replace("*moreinforow*",urldecode($moreinfo),$htmlTemplate);
		$dataEmail['moreinforow'] = urldecode($moreinfo);

		$htmlTemplate = str_replace("*branddivision*",$branddivision,$htmlTemplate);
		$dataEmail['branddivision'] = $branddivision;

		$htmlTemplate = str_replace("*productname*",$productname,$htmlTemplate);
		$dataEmail['productname'] = $productname;

		$htmlTemplate = str_replace("*productmodel*",$productmodel,$htmlTemplate);
		$dataEmail['productmodel'] = $productmodel;

		$htmlTemplate = str_replace("*launchdate*",$launchdate,$htmlTemplate);
		$dataEmail['launchdate'] = $launchdate;

		$htmlTemplate = str_replace("*suggestedprice*",$suggestedprice,$htmlTemplate);
		$dataEmail['suggestedprice'] = $suggestedprice;

		$htmlTemplate = str_replace("*productdetails*",$productdetails,$htmlTemplate);
		$dataEmail['productdetails'] = $productdetails;

		$htmlTemplate = str_replace("*producttechnology*",$producttechnology,$htmlTemplate);
		$dataEmail['producttechnology'] = $producttechnology;

		$htmlTemplate = str_replace("*productgender*",$productgender,$htmlTemplate);
		$dataEmail['productgender'] = $productgender;
		
		/* pArr($_POST['sendTo']);die('--WMK'); */
		 // email			

		// $this->load->library('email');

		 

		 /*$this->email->clear();

		 $this->email->from('no-reply@digiwaxx.com', 'Digiwaxx');

		 $this->email->set_mailtype("html");

		 $this->email->subject($subject);

		*/
		
		if($_POST['sendTo']==1){

		     $where = 'where ';

		     $whereItems[] = "deleted = '0'";

		     if(isset($_POST['dj_mixer'])) { $whereItems[] = "dj_mixer = '1'";  }

			 if(isset($_POST['radio_station'])) { $whereItems[] = "radio_station = '1'";  }

			 if(isset($_POST['mass_media'])) { $whereItems[] = "mass_media = '1'";  }

			 if(isset($_POST['record_label'])) { $whereItems[] = "record_label = '1'";  }

			 if(isset($_POST['management'])) { $whereItems[] = "management = '1'";  }

			 if(isset($_POST['clothing_apparel'])) { $whereItems[] = "clothing_apparel = '1'";  }

			 if(isset($_POST['promoter'])) { $whereItems[] = "promoter = '1'";  }

			 if(isset($_POST['special_services'])) { $whereItems[] = "special_services = '1'";  }

			 if(isset($_POST['production_talent'])) { $whereItems[] = "production_talent = '1'";  }

			if(count($whereItems)>1){			

			   $whereString = implode(' AND ',$whereItems);

			   $where .= $whereString;

			}else if(count($whereItems)==1){

			   $where .= $whereItems[0];

			}else{

			  $where =  '';

			}
			 $members =  $this->admin_model->getMembers($where); 
			 
			
			
			 if($members['numRows']>0)

			 {
			    if(isset($_POST['typesAll'])) {
					$type = 'all';
				}else{
					$type = '';
				}
			 

			   $mailId =  $this->admin_model->setMail($subject,$type,$_POST['message'],$_POST['track'],$_POST['template']); 
				
			   foreach($members['data']  as $member)

			   {
				   if(strlen($member->email)>4)

				   {
					$htmlTemplate = str_replace("*memberid*",$member->id,$htmlTemplate);

					$htmlTemplate = str_replace("*key*",'key',$htmlTemplate);					
					
					$dataEmail['memberid'] = $member->id;
					$dataEmail['key'] = 'key';
					$dataEmail['emailId'] = urldecode($member->email);
					$dataEmail['member'] = $member->id;
					$dataEmail['subject'] = $subject;
					//pArr(urldecode($dataEmail['emailId']));die('--WMK'); //'sgtechqa@yopmail.com'  // urldecode($dataEmail['emailId'])
					Mail::send("mails.templates.$htmlFileEmail",['data' => $dataEmail], function ($message) use ($dataEmail) {
					  $message->to($dataEmail['emailId']);
					  $message->subject($dataEmail['subject']);
					  $message->from('no-reply@digiwaxx.com','Digiwaxx');
					});
					 				

					$insrtID = $this->admin_model->mailSend($mailId,$member->id); 
					 
					}

			   }

			   $this->admin_model->closeMail($mailId); 

			 }

		    return Redirect::to('admin/send_mail?mailSent=1');    

		 }else if($_POST['sendTo']==3){
			 
			 	$htmlTemplate = str_replace("*memberid*",'s',$htmlTemplate);
				
				$dataEmail['memberid'] = 's';

				$htmlTemplate = str_replace("*key*",'key',$htmlTemplate);
				
				$dataEmail['key'] = 'key';

				$multipleEmails = str_replace(",","\n",$_POST['multipleEmails']);

				$multipleEmails = explode("\n",$multipleEmails);
				//pArr($multipleEmails);die('--WMK');
				$mailId =  $this->admin_model->setMail($subject,'',$_POST['message'],$_POST['track'],$_POST['template']);
		   

				foreach($multipleEmails as $emailId){				
				
				 $dataEmail['emailId'] = urldecode($emailId);
				 $dataEmail['subject'] = $subject;
				 
				 Mail::send("mails.templates.$htmlFileEmail",['data' => $dataEmail], function ($message) use ($dataEmail) {
					  $message->to($dataEmail['emailId']);
					  $message->subject($dataEmail['subject']);
					  $message->from('no-reply@digiwaxx.com','Digiwaxx');
					});

				} 

				$this->admin_model->closeMail($mailId);

				return Redirect::to('admin/send_mail?mailSent=1');

		 }else{
		 		return Redirect::to('admin/send_mail?error=1');

		 }

		}else{
			  return Redirect::to('admin/send_mail?error=1'.$errorUrl);
		}
			 /*  echo'<pre>';print_r($emailImgDimensions);die('--YSYS'); */
	    }
		
				// generate where

		$where = 'where ';

		$whereItems[] = "members.deleted = '0'";

		$whereItems[] = "members.active = '1'";

		

		$output['searchCompany'] = '';

		$output['searchUsername'] = '';

		$output['searchName'] = '';

		$output['searchEmail'] = '';

		$output['searchPhone'] = '';

		$output['searchCity'] = '';

		$output['searchState'] = '';

		$output['searchZip'] = '';
		
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

		$sortBy = "id";

		$output['sortBy'] = 'registered';

		$output['sortOrder'] = 2;
		
		$output['tracks'] =  $this->admin_model->getTracks(); 

		$output['templates'] =  $this->admin_model->getTemplates(); 
		
		return view('/admin/admin_sendMail',$output);	
	}
		
  /**
	* Mail View.
	*
	* @GS
	*/
	
	public function admin_viewMail(){
		
		$output = array();
		// header data pass starts
		$admin_name = Auth::user()->name;
		$user_role = Auth::user()->user_role;
		$loggedUserId = Auth::user()->id;
		
		if(!isset($_GET['mid']) && empty($_GET['mid'])){
			return Redirect::to('admin/mails');
		}
		
		$logo_data = array(
				'logo_id' => 1,
				);

			$logo_details = DB::table('website_logo')
			->where($logo_data)
			->first();  
			
			$get_logo = $logo_details->logo;
		
		$output['pageTitle'] = 'Digiwaxx Admin - View Mail';
		$output['logo'] = $get_logo;
		$output['welcome_name'] = $admin_name;
		$output['user_role'] = $user_role;
		
		$output['mails'] =  $this->admin_model->getMail($_GET['mid']);
		
		return view('/admin/admin_viewMail',$output);
	}
	
  /**
	* View Newsletters.
	*
	* @GS
	*/
	
	public function admin_NewsletterView(){
	  	$output = array();
		// header data pass starts
		$admin_name = Auth::user()->name;
		$user_role = Auth::user()->user_role;
		$loggedUserId = Auth::user()->id;
		
		$logo_data = array(
				'logo_id' => 1,
				);

			$logo_details = DB::table('website_logo')
			->where($logo_data)
			->first();  
			
			$get_logo = $logo_details->logo;
		
		$output['pageTitle'] = 'Digiwaxx Admin - View Newsletter'; 
		$output['logo'] = $get_logo;
		$output['welcome_name'] = $admin_name;
		$output['user_role'] = $user_role;
		
		$where = "where newsletter_id = '". $_GET['nlid'] ."'";

	   $sort = "newsletter_id";

			

		$output['newsletter'] =  $this->admin_model->getNewsletters($where,$sort,0,1);

		

		

		$where = "where newsletter_subscribers.newsletter_id = '". $_GET['nlid'] ."'";

		$sort = "subscribers.email asc";

		$output['subscribers'] =  $this->admin_model->getNewsletterSubscribers($where,$sort); 
		
		return view('/admin/admin_NewsletterView',$output);
		
	}
  /**
	* List Newsletters.
	*
	* @GS
	*/
	
	public function admin_listNewsletters(){
	  	$output = array();
		// header data pass starts
		$admin_name = Auth::user()->name;
		$user_role = Auth::user()->user_role;
		$loggedUserId = Auth::user()->id;
		
		$logo_data = array(
				'logo_id' => 1,
				);

			$logo_details = DB::table('website_logo')
			->where($logo_data)
			->first();  
			
			$get_logo = $logo_details->logo;
		
		$output['pageTitle'] = 'Digiwaxx Admin - Newsletters';
		$output['logo'] = $get_logo;
		$output['welcome_name'] = $admin_name;
		$output['user_role'] = $user_role;
		
		// delete member

		 if(isset($_GET['did'])){		 

		   $result = $this->admin_model->deleteNewsletter($_GET['did']); 			

			if($result>0){

			     return Redirect::to("admin/newsletters?delete=success");   exit;

			}else{

			     return Redirect::to("admin/newsletters?error=1");   exit;

			}
		}
		
				// generate where

		$where = 'where ';

		$whereItems = array();

		//$whereItems[] = "admin_id != '0'";

		

		$output['searchCompany'] = '';

		$output['searchUsername'] = '';

		$output['searchName'] = '';

		$output['searchEmail'] = '';

		$output['searchPhone'] = '';

		$output['searchCity'] = '';

		$output['searchState'] = '';

		$output['searchZip'] = '';

		

		

	  if(isset($_GET['search']))

		{

		   

		   if(isset($_GET['email']) && strlen($_GET['email'])>0)

		   {

		     $output['searchEmail'] = $_GET['email'];

			 $whereItems[] = "subscribers.email = '". $_GET['email'] ."'";

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

		$sortBy = "newsletter_id";

		$output['sortBy'] = 'registered';

		$output['sortOrder'] = 2;

		

		if(isset($_GET['sortBy']) && isset($_GET['sortOrder']))

		{

		   $output['sortBy'] = $_GET['sortBy'];

		   $output['sortOrder'] = $_GET['sortOrder'];

		   

		   

		   if(strcmp($_GET['sortBy'],'newsletter_id')==0)

		   {

		    

			 $sortBy = "newsletter_id";

		   }

		   else if(strcmp($_GET['sortBy'],'subject')==0)

		   {

		    

			 $sortBy = "subject";

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

		

		/*echo '11--'.$where;

		echo '<br />';

		echo '22--'.$sort;*/
		
	 $num_records = $this->admin_model->getNumNewsletters($where,$sort); 

	  $numPages = (int)($num_records/$limit); 

	  $reminder = ($num_records%$limit);

	 

	 if($reminder>0)

	 {

		 $numPages = $numPages+1;

	 }

	

	 $output['numPages'] = $numPages;

	 $output['start'] = $start;

	 $output['currentPageNo'] = $currentPageNo;

	 $output['currentPage'] = 'newsletters';
	 
	 if(isset($_GET['page'])) {

			 if ($_GET['page'] > $numPages) {

				 header("location: ".$output['currentPage']."?page=" . $numPages);

				 exit;

			 } else if ($_GET['page'] < 1) {

				 header("location: ".$output['currentPage']."?page=1");

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
		$output['newsletters'] =  $this->admin_model->getNewsletters($where,$sort,$start,$limit); 

		if(isset($_GET['delete'])){

		  $output['alertMessage'] = 'Newsletter deleted successfully!';

		  $output['alertClass'] = 'alert alert-success';

		}else if(isset($_GET['error'])){

		  $output['alertMessage'] = 'Error occured, please try again!';

		  $output['alertClass'] = 'alert alert-danger';

		}
		
		return view('/admin/admin_listNewsletters',$output);
	}
	
  /**
	* List Subscribers.
	*
	* @GS
	*/
	
	public function admin_sendNewsletter(){
		
		$output = array();
		// header data pass starts
		$admin_name = Auth::user()->name;
		$user_role = Auth::user()->user_role;
		$loggedUserId = Auth::user()->id;
		
		$logo_data = array(
				'logo_id' => 1,
				);

			$logo_details = DB::table('website_logo')
			->where($logo_data)
			->first();  
			
			$get_logo = $logo_details->logo;
		
		$output['pageTitle'] = 'Digiwaxx Admin - Send Subscribers';
		$output['logo'] = $get_logo;
		$output['welcome_name'] = $admin_name;
		$output['user_role'] = $user_role;
		
		// get search tracks

		 if(isset($_GET['searchKey']) && isset($_GET['subscriberSearch'])){
			 
		  $searchWhere =  "";

		  if(strlen($_GET['searchKey'])>0){

		   $searchWhere = "WHERE email like '". $_GET['searchKey'] ."%'";

		  }

		  $searchSort = "order by email asc";

		  $result =  $this->admin_model->getSearchSubscribers($searchWhere,$searchSort); 

		   if($result['numRows']>0){
			 

			 foreach($result['data'] as $data){

			   $arr[] = array('id' => $data->subscriberId, 'title' => $data->email);

			 } 
			 echo json_encode($arr);

		   }
		  exit;

		 }
		 
		 if(isset($_POST['sendNewsletter'])){

		

		    /*  $htmlTemplate =  file_get_contents(base_url("templates/newsletter/newsletter.php")); */
			 
			 $htmlTemplate =  curl_getFileContents(url("resources/views/mails/templates/newsletter/newsletter"));

			 $htmlTemplate = str_replace("*message*",$_POST['message'],$htmlTemplate);
			 
			 $dataEmail['message'] = $_POST['message'];			 

		 // email			

		

		 if($_POST['sendTo']==1){

				 $where = ''; 		 

				 $sort = "order by email asc";
		 }else if($_POST['sendTo']==2){
			 
				if(count($_POST['selectedSubscribers'])==1){

					$where =  "where subscriberId = '". $_POST['selectedSubscribers'][0] ."'";

				 }else if(count($_POST['selectedSubscribers'])>1){

				  $selectedSubscribers = implode(',',$_POST['selectedSubscribers']);

				  $selectedSubscribers = '('.$selectedSubscribers.')';

				  $where =  "where subscriberId IN $selectedSubscribers";

				 }
				  $sort = "order by email asc";
		 }
		 
		 $email_ids =  $this->admin_model->getSearchSubscribers($where,$sort); 

		 $dataEmail['subject'] = $_POST['subject'];

		 $newsletter_id =  $this->admin_model->setNewsletter($_POST['subject'],$_POST['message'],$_POST['sendTo']); 

		   

	    foreach($email_ids['data'] as $email_id){
			$dataEmail['memberid'] = $email_id->subscriberId;
			$dataEmail['key'] = 'key';
			$dataEmail['emailId'] = urldecode($email_id->email);
			 $dataEmail['subject'] = $_POST['subject'];
			 //pArr($dataEmail);die('---WMK');
			 Mail::send("mails.templates.newsletter.newsletter",['data' => $dataEmail], function ($message) use ($dataEmail) {
				  $message->to($dataEmail['emailId']);
				  $message->subject($dataEmail['subject']);
				  $message->from('no-reply@digiwaxx.com','Digiwaxx');
				});

		 /* $this->email->clear();

		 $this->email->message($htmlTemplate);

		 $this->email->from('no-reply@digiwaxx.com', 'Digiwaxx');

		 $this->email->set_mailtype("html");

		 $this->email->subject($_POST['subject']);

		 $this->email->to($email_id->email);

		 $this->email->send(); */

		 

		 $this->admin_model->saveSubcriber($newsletter_id,$email_id->subscriberId); 

	    } 

		//   $this->mailsdb->closeMail($mailId); 

		 return Redirect::to('admin/send_newsletter?mailSent=1');

		 }
		 
		 	if(isset($_GET['mailSent']))

			{

			  $output['alertMessage'] = 'Newsletter sent successfully!';

			  $output['alertClass'] = 'alert alert-success';

			}

			else if(isset($_GET['error']))

			{

			  $output['alertMessage'] = 'Error occured, please try again!';

			  $output['alertClass'] = 'alert alert-danger';

			}
		
		return view('/admin/admin_sendNewsletter',$output);
	}

  /**
	* List Subscribers.
	*
	* @GS
	*/
	
	public function admin_listSubscribers(){
		
		$output = array();
		// header data pass starts
		$admin_name = Auth::user()->name;
		$user_role = Auth::user()->user_role;
		$loggedUserId = Auth::user()->id;
		
		$logo_data = array(
				'logo_id' => 1,
				);

			$logo_details = DB::table('website_logo')
			->where($logo_data)
			->first();  
			
			$get_logo = $logo_details->logo;
		
		$output['pageTitle'] = 'Digiwaxx Admin - View Subscribers';
		$output['logo'] = $get_logo;
		$output['welcome_name'] = $admin_name;
		$output['user_role'] = $user_role;

		// delete member

		if(isset($_GET['did'])){		 

		    $result = $this->admin_model->deleteSubscriber($_GET['did']);			

			if($result>0){
			     return Redirect::to("admin/subscribers?delete=success");   exit;
			}else{
			     return Redirect::to("admin/subscribers?error=1");   exit;
			}
		}
		
			// generate where

		$where = 'where ';

		$whereItems = array();

		//$whereItems[] = "admin_id != '0'";

		

		$output['searchCompany'] = '';

		$output['searchUsername'] = '';

		$output['searchName'] = '';

		$output['searchEmail'] = '';

		$output['searchPhone'] = '';

		$output['searchCity'] = '';

		$output['searchState'] = '';

		$output['searchZip'] = '';
		
		if(isset($_GET['search'])){		   

		   if(isset($_GET['email']) && strlen($_GET['email'])>0){

		     $output['searchEmail'] = $_GET['email'];

			 $whereItems[] = "subscribers.email = '". $_GET['email'] ."'";

		   }
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

		$sortBy = "subscriberId";

		$output['sortBy'] = 'registered';

		$output['sortOrder'] = 2;
		
		if(isset($_GET['sortBy']) && isset($_GET['sortOrder'])){

		   $output['sortBy'] = $_GET['sortBy'];

		   $output['sortOrder'] = $_GET['sortOrder'];

		   if(strcmp($_GET['sortBy'],'started')==0){		    

			 $sortBy = "id";

		   }else if(strcmp($_GET['sortBy'],'subject')==0){		    

			 $sortBy = "subject";

		   }

		   if($_GET['sortOrder']==1){		    

			 $sortOrder = "ASC";

		   }else  if($_GET['sortOrder']==2){

			 $sortOrder = "DESC";

		   }
		}
		
		$sort =  $sortBy." ".$sortOrder;
		
		// pagination

		$limit = 10;

		if(isset($_GET['records'])){

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
		
	  $num_records = $this->admin_model->getNumSubscribers($where,$sort); 

	  $numPages = (int)($num_records/$limit); 

	  $reminder = ($num_records%$limit);

		 if($reminder>0){

			 $numPages = $numPages+1;

		 }		

		 $output['numPages'] = $numPages;

		 $output['start'] = $start;

		 $output['currentPageNo'] = $currentPageNo;

		 $output['currentPage'] = 'subscribers';
		 
		if(isset($_GET['page'])) {

			 if ($_GET['page'] > $numPages) {

				 return Redirect::to("admin/".$output['currentPage']."?page=" . $numPages);

				 exit;

			 } else if ($_GET['page'] < 1) {

				 return Redirect::to("admin/".$output['currentPage']."?page=1");

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
  
  $output['subscribers'] =  $this->admin_model->getSubscribers($where,$sort,$start,$limit); 

	

	

	if(isset($_GET['delete']))

	{

	  $output['alertMessage'] = 'Subscriber deleted successfully!';

	  $output['alertClass'] = 'alert alert-success';

	}

	else if(isset($_GET['error']))

	{

	  $output['alertMessage'] = 'Error occured, please try again!';

	  $output['alertClass'] = 'alert alert-danger';

	}
		
		return view('/admin/admin_listSubscribers',$output);
	}

	/**
	* List Staff Selected.
	*
	* @GS
	*/	
	public function admin_ListStaffSelected(){
		$output = array();
		// header data pass starts
		$admin_name = Auth::user()->name;
		$user_role = Auth::user()->user_role;
		$loggedUserId = Auth::user()->id;
		
		$logo_data = array(
				'logo_id' => 1,
				);

			$logo_details = DB::table('website_logo')
			->where($logo_data)
			->first();  
			
			$get_logo = $logo_details->logo;
		
		$output['pageTitle'] = 'Digiwaxx Admin - Staff Selected';
		$output['logo'] = $get_logo;
		$output['welcome_name'] = $admin_name;
		$output['user_role'] = $user_role;
		
		 // delete member

		 if(isset($_GET['did'])){		 

		    $result = $this->admin_model->deleteStaffTrack($_GET['did']);		

			if($result>0){
			     return Redirect::to("admin/admin_staff_selected?delete=success");   exit;
			}else{
			     return Redirect::to("admin/admin_staff_selected?error=1");   exit;
			}
		 }
		// generate where

		$where = 'where ';

		$whereItems = array();

		//$whereItems[] = "admin_id != '0'";

		
		$output['searchTitle'] = '';
		$output['searchCompany'] = '';

		$output['searchUsername'] = '';

		$output['searchName'] = '';

		$output['searchEmail'] = '';

		$output['searchPhone'] = '';

		$output['searchCity'] = '';

		$output['searchState'] = '';

		$output['searchZip'] = '';
		
		if(isset($_GET['search'])){	   

		   if(isset($_GET['title']) && strlen($_GET['title'])>0){

		     $output['searchTitle'] = $_GET['title'];

			 $whereItems[] = "tracks.title = '". urlencode($_GET['title']) ."'";

		   }
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

		$sortBy = "staff_selection.sortOrder";

		$output['sortBy'] = 'registered';

		$output['sortOrder'] = 2;
		
		if(isset($_GET['sortBy']) && isset($_GET['sortOrder'])){

		   $output['sortBy'] = $_GET['sortBy'];

		   $output['sortOrder'] = $_GET['sortOrder'];

		   if(strcmp($_GET['sortBy'],'started')==0){		    

			 $sortBy = "id";

		   }else if(strcmp($_GET['sortBy'],'subject')==0){		    

			 $sortBy = "subject";

		   }

		   if($_GET['sortOrder']==1){

			 $sortOrder = "ASC";

		   }else  if($_GET['sortOrder']==2){
			   
			 $sortOrder = "DESC";
		   }

		}

		$sort =  $sortBy." ".$sortOrder;
		
		// pagination

		$limit = 10;

		if(isset($_GET['records'])){

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
		
		$num_records = $this->admin_model->getStaffSelectedNumTracks($where,$sort); 

		$numPages = (int)($num_records/$limit); 

		$reminder = ($num_records%$limit);
		
		if($reminder>0){

			 $numPages = $numPages+1;
		}
		$output['numPages'] = $numPages;

		$output['start'] = $start;

		$output['currentPageNo'] = $currentPageNo;

		$output['currentPage'] = 'admin_staff_selected';
		
		if(isset($_GET['page'])) {
			
			 if ($_GET['page'] > $numPages) {

				 return Redirect::to("admin/".$output['currentPage']."?page=" . $numPages);

				 exit;

			 } else if ($_GET['page'] < 1) {

				 return Redirect::to("admin/".$output['currentPage']."?page=1");

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
	  
	  $output['tracks'] =  $this->admin_model->getStaffSelectedTracks($where,$sort,$start,$limit);
	  
		 if(isset($_GET['delete'])){

			  $output['alertMessage'] = 'Track removed from Staff selection section!';

			  $output['alertClass'] = 'alert alert-success';

		 }else if(isset($_GET['error'])){

			  $output['alertMessage'] = 'Error occured, please try again!';

			  $output['alertClass'] = 'alert alert-danger';

		}
		
		return view('/admin/admin_listStaffSelected',$output);
		
	}
	/**
     * List Countries.
     *
     * @GS
     */		
	public function admin_ListCountries(){
		
		$output = array();
		// header data pass starts
		$admin_name = Auth::user()->name;
		$user_role = Auth::user()->user_role;
		$loggedUserId = Auth::user()->id;
		
		$logo_data = array(
				'logo_id' => 1,
				);

			$logo_details = DB::table('website_logo')
			->where($logo_data)
			->first();  
			
			$get_logo = $logo_details->logo;
		
		$output['pageTitle'] = 'Digiwaxx Admin - Countries';
		$output['logo'] = $get_logo;
		$output['welcome_name'] = $admin_name;
		$output['user_role'] = $user_role;
		
		// edit country

		 if(isset($_GET['edit']) && isset($_GET['continent']) && isset($_GET['country']) && isset($_GET['countryId'])){		 
		  
			   $result = $this->admin_model->updateCountry($_GET); 

			   if($result['result']>0){

				 $arr = array('response' => 1, 'continent' => $result['continent']);

			   }else{

				 $arr = array('response' => 0, 'continent' => ''); 

			   } 
			   echo json_encode($arr);

			   exit;
		  
		 }
		 // Delete Country
		if(isset($_GET['did'])){

			 $result = $this->admin_model->deleteCountry($_GET['did']); 		   

			 if($result>0){

				  return Redirect::to("admin/countries?delete=success");

			 }else{

				 return Redirect::to("admin/countries?error=1");

			 }
		 }
		 
		 // add country

		 if(isset($_POST['addCountry'])){
			 
			$chkCountry = $this->admin_model->checkIfCountryExists($_POST);
			
			if($chkCountry>0){
				return Redirect::to("admin/countries?error=2");
			}else{
			   $result = $this->admin_model->addCountry($_POST);   

			   if($result>0){

				  return Redirect::to("admin/countries?added=success");

			   }else{

				 return Redirect::to("admin/countries?error=1");

			   } 
			}
		}
		// generate sort

		$sortOrder = "DESC";

		$sortBy = "country.country";

		$output['sortBy'] = 'country';

		$output['sortOrder'] = 2;
			
	   // sort by	   

	   if(isset($_GET['sortBy']) && strcmp($_GET['sortBy'],'country')==0){		

		 $sortBy = "country.country";

		 $output['sortBy'] = $_GET['sortBy'];

	   }else if(isset($_GET['sortBy']) && strcmp($_GET['sortBy'],'continent')==0){

		 $sortBy = "continents.continent";

		 $output['sortBy'] = $_GET['sortBy'];

	   }
	   // sort order	  		   

	   if(isset($_GET['sortOrder']) && $_GET['sortOrder']==1){
		   
		 $sortOrder = "ASC";

		 $output['sortOrder'] = $_GET['sortOrder'];

	   }else  if(isset($_GET['sortOrder']) && $_GET['sortOrder']==2){
		 $sortOrder = "DESC";

		 $output['sortOrder'] = $_GET['sortOrder'];

	   }		 

	   $sort =  $sortBy." ".$sortOrder;
	   
	   // generate where  

		$where = 'where ';
		$whereItems = array();
		
		$output['searchContinent'] = '';
		$output['searchCountry'] = '';

		/*if(isset($_GET['search']))

		{*/

		  if(isset($_GET['searchContinent']) && $_GET['searchContinent']>0){

		     $output['searchContinent'] = $_GET['searchContinent'];

			 $whereItems[] = "country.continentId = '". $_GET['searchContinent'] ."'";

		  }
		  if(isset($_GET['searchCountry']) && strlen($_GET['searchCountry'])>0){

		     $output['searchCountry'] = trim($_GET['searchCountry']);

			 $whereItems[] = "country.country like '%". trim($_GET['searchCountry']) ."%'";

		   }

	  /*  }*/
	  
	  	if(count($whereItems)>1){

		   $whereString = implode(' AND ',$whereItems);

		   $where .= $whereString;

		}else if(count($whereItems)==1){

		   $where .= $whereItems[0]; 

		}else{

		   $where =  '';

		}

		//	$where;
		
			// generate link

		$output['link_string'] = '?';

		if(isset($_GET) && count($_GET)>0)

		{

		  $link_items = array();

		  $link_string =  '?';

		  $exclude_variables = array("page");

		  

		    foreach($_GET as $key=>$value)

			{

			  if(!(in_array($key,$exclude_variables)))

			  {

			    $link_items[] = $key.'='.$value; 

			  }

			}

			

		if(count($link_items)>1)

		{ 

		   $link_string = implode('&',$link_items);

		   $link_string = '?'.$link_string.'&'; 

		}

		else if(count($link_items)==1)

		{

		   $link_string = '?'.$link_items[0].'&'; 

		}

		

		$output['link_string'] = $link_string;

		}
  
		$output['link_string']; 
		
		// pagination

		$limit = 10;

		if(isset($_GET['numRecords'])){

		  $limit = $_GET['numRecords'];

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
		 
		$num_records = $this->admin_model->getNumCountries($where,$sort); 

	    $numPages = (int)($num_records/$limit); 

	    $reminder = ($num_records%$limit);	 

	    if($reminder>0){

		  $numPages = $numPages+1;

		}

		$output['numPages'] = $numPages;

		$output['start'] = $start;

		$output['currentPageNo'] = $currentPageNo;

		$output['currentPage'] = 'countries';
		
		if(isset($_GET['page'])) {

			 if ($_GET['page'] > $numPages) {

				 return Redirect::to("admin/".$output['currentPage'].$link_string."page=" . $numPages);

				 exit;

			 } else if ($_GET['page'] < 1) {

				 return Redirect::to("admin/".$output['currentPage'].$link_string."page=1");

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
  
  		 if(isset($_GET['added']))				

			{

			  

			   $output['alert_class'] = 'alert alert-success';

			   $output['alert_message'] = 'Country added  successfully !';

			

			}

			else if(isset($_GET['error']))				

			{

			   $output['alert_class'] = 'alert alert-danger';
			   
			   if($_GET['error'] == 2){
				   
				   $output['alert_message'] = 'Country already exists, please try again.';
				   
			   }else{

				   $output['alert_message'] = 'Error occured, please try again.';
			   }

			}

			else if(isset($_GET['delete']))				

			{

			   $output['alert_class'] = 'alert alert-success';

			   $output['alert_message'] = 'Country deleted successfully.';

			}
			
			$output['countries'] = $this->admin_model->getCountries($where,$sort,$start,$limit); 		

			$output['continents'] = $this->admin_model->getContinents(); 
		    /* pArr($output);die(); */
			
		return view('/admin/admin_ListCountries',$output);
	}

	/**
     * List States.
     *
     * @GS
     */		
	public function admin_ListStates(){
		
		$output = array();
		// header data pass starts
		$admin_name = Auth::user()->name;
		$user_role = Auth::user()->user_role;
		$loggedUserId = Auth::user()->id;
		
		$logo_data = array(
				'logo_id' => 1,
				);

			$logo_details = DB::table('website_logo')
			->where($logo_data)
			->first();  
			
			$get_logo = $logo_details->logo;
		
		$output['pageTitle'] = 'Digiwaxx Admin - States';
		$output['logo'] = $get_logo;
		$output['welcome_name'] = $admin_name;
		$output['user_role'] = $user_role;
		
		// get continent countries 

		if(isset($_GET['getContinentCountries']) && isset($_GET['continentId'])){		 

		   $result = $this->admin_model->getSelectedCountries($_GET['continentId']); 
		   

		   if($result['numRows']>0){

			 foreach($result['data'] as $country) {

				$arr[] = array('countryId'=>$country->countryId, 'country'=>$country->country);
			}

		   }else{

			  $arr[] = array('countryId'=>'', 'country'=>'No Data found.');

		   }
            echo json_encode($arr);
			exit;
		}
		
		  // add state

		 if(isset($_GET['add']) && isset($_GET['continentId']) && isset($_GET['countryId']) && isset($_GET['state']))

		 {

		 

		   $result = $this->admin_model->addState($_GET); 

		   

		   if($result>10)

		   {

		     $arr = array('response' => 1);



		   }

		   else

		   {

		     $arr = array('response' => 0); 

		   } 

		   

             echo json_encode($arr);

		   exit;

		 }
		 
		  // edit state

		 if(isset($_GET['edit']) && isset($_GET['continentId']) && isset($_GET['countryId']) && isset($_GET['stateId']) && isset($_GET['state']))

		 {

		 

		   $result = $this->admin_model->updateState($_GET); 

		   

		   if($result['result']>0)

		   {

		     $arr = array('response' => 1, 'continent' => $result['continent']);



		   }

		   else

		   {

		     $arr = array('response' => 0, 'continent' => ''); 

		   } 

		   

             echo json_encode($arr);

		   exit;

		 }
		 // Delete
		 if(isset($_GET['did']))

		 {

		 

		 $result = $this->admin_model->deleteState($_GET['did']); 

		   

		   if($result>0)

		   {

		      return Redirect::to("admin/states?delete=success");

		   }

		   else

		   {

		     return Redirect::to("admin/states?error=1");

		   }

		 }
		 
		 	 // add State

		 if(isset($_POST['addCountry']))

		 {

		 

		   $result = $this->admin_model->addCountry($_POST); 

		   

		   if($result>0)

		   {

		      return Redirect::to("admin/countries?added=success");

		   }

		   else

		   {

		     return Redirect::to("admin/countries?error=1");

		   } 

		 

		 }
		 
		 		// generate sort

		$sortOrder = "DESC";

		$sortBy = "states.name";

		$output['sortBy'] = 'state';

		$output['sortOrder'] = 2;

		

		if(isset($_GET['sortBy']) && isset($_GET['sortOrder'])) 

		{
  
		   $output['sortBy'] = $_GET['sortBy'];

		   $output['sortOrder'] = $_GET['sortOrder'];

		   

		   

		if(strcmp($_GET['sortBy'],'country')==0)

		   {

		    

			 $sortBy = "country.country";

		   }

		   else if(strcmp($_GET['sortBy'],'continent')==0)

		   {

		    

			 $sortBy = "continents.continent";

		   }else if(strcmp($_GET['sortBy'],'state')==0)

		   {

		    

			 $sortBy = "states.name";

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
		
		$where = '';

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
		 
      $num_records = $this->admin_model->getNumStates($where,$sort); 

	  $numPages = (int)($num_records/$limit); 

	  $reminder = ($num_records%$limit);

	 

	 if($reminder>0)

	 {

		 $numPages = $numPages+1;

	 }

	

	 $output['numPages'] = $numPages;

	 $output['start'] = $start;

	 $output['currentPageNo'] = $currentPageNo;

	 $output['currentPage'] = 'states';

	 	// generate link

	$output['link_string'] = '?';

	if(isset($_GET) && count($_GET)>0)

	{

		$link_items = array();

		$link_string =  '?';

		$exclude_variables = array("page");

		

		foreach($_GET as $key=>$value)

		{

			if(!(in_array($key,$exclude_variables)))

			{

			$link_items[] = $key.'='.$value; 

			}

		}

		

	if(count($link_items)>1)

	{ 

		$link_string = implode('&',$link_items);

		$link_string = '?'.$link_string.'&'; 

	}

	else if(count($link_items)==1)

	{

		$link_string = '?'.$link_items[0].'&'; 

	}

	

	$output['link_string'] = $link_string;

	}

  $output['currentPage'] = 'states';

	 

/* 	  if(isset($_GET['page'])) {

		 if ($_GET['page'] > $numPages) {

			 return Redirect::to("admin/".$output['currentPage']."?page=" . $numPages);

			 exit;

		 } else if ($_GET['page'] < 1) {

			 return Redirect::to("admin/".$output['currentPage']."?page=1");

			 exit;

		 }

	 } */

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
		  
		  		 if(isset($_GET['added']))				

			{

			  

			   $output['alert_class'] = 'alert alert-success';

			   $output['alert_message'] = 'State added  successfully !';

			

			}

			else if(isset($_GET['error']))				

			{

			   $output['alert_class'] = 'alert alert-danger';

			   $output['alert_message'] = 'Error occured, please try again.';

			}

			else if(isset($_GET['delete']))				

			{

			   $output['alert_class'] = 'alert alert-success'; 

			   $output['alert_message'] = 'State deleted successfully.';

			}
			
		$output['states'] = $this->admin_model->getStates($where,$sort,$start,$limit); 

		

		foreach($output['states']['data'] as $state)

		{

		

		 $output['selectedCountries'][$state->stateId] = $this->admin_model->getSelectedCountries($state->continentId);

		  

		}

		

	//	$output['countries'] = $this->countriesdb->getStates($where,$sort,$start,$limit); 

		

		$output['continents'] = $this->admin_model->getContinents();

		return view('/admin/admin_ListStates',$output);
	}

	/**
     * List YouTube.
     *
     * @GS
     */		
	public function admin_ListYoutube(){
		$output = array();
		// header data pass starts
		$admin_name = Auth::user()->name;
		$user_role = Auth::user()->user_role;
		$loggedUserId = Auth::user()->id;
		
		$logo_data = array(
				'logo_id' => 1,
				);

			$logo_details = DB::table('website_logo')
			->where($logo_data)
			->first();  
			
			$get_logo = $logo_details->logo;
		
		$output['pageTitle'] = 'Digiwaxx Admin - Youtube';
		$output['logo'] = $get_logo;
		$output['welcome_name'] = $admin_name;
		$output['user_role'] = $user_role;
		
		// update youtube
		 if(isset($_POST['updateYoutube']))
		 {
			 $result = $this->admin_model->updateYoutube($_POST['youtube']);
			 if($result>0)
			 {
				return Redirect::to("admin/youtube?updated=1");   
				
			 }
			 else
			 {
				return Redirect::to("admin/youtube?error=1"); 
				
			 }
			 exit;
		 }
		 
		  if(isset($_POST['updateYoutube2']))
		 {
			 $result = $this->admin_model->updateYoutube2($_POST['youtube2']);
			 if($result>0)
			 {
				return Redirect::to("admin/youtube?updated=1");   
				
			 }
			 else
			 {
				return Redirect::to("admin/youtube?error=1"); 
				
			 }
			 exit;
		 }
		 
		 
		
		$output['youtube'] =  $this->admin_model->getYoutube(); 

		if(isset($_GET['updated']))
		{
		  $output['alert_message'] = 'Youtube updated successfully!';
		  $output['alert_class'] = 'alert alert-success';
		}
		else if(isset($_GET['error']))
		{
		  $output['alert_message'] = 'Error occured, please try again!';
		  $output['alert_class'] = 'alert alert-danger';
		}
		
		return view('/admin/admin_ListYoutube',$output);
		
	}
	/**
     * List SEO.
     *
     * @GS
     */	
	public function admin_ListSEO(Request $request){ 
		
		$output = array();
		// header data pass starts
		$admin_name = Auth::user()->name;
		$user_role = Auth::user()->user_role;
		$loggedUserId = Auth::user()->id;
		
		$logo_data = array(
				'logo_id' => 1,
				);

			$logo_details = DB::table('website_logo')
			->where($logo_data)
			->first();  
			
			$get_logo = $logo_details->logo;
		
		$output['pageTitle'] = 'Digiwaxx Admin - SEO';
		$output['logo'] = $get_logo;
		$output['welcome_name'] = $admin_name;
		$output['user_role'] = $user_role;
		if(isset($_GET['meta']) && isset($_GET['page_id'])){
			$meta = $this->admin_model->getPageText($_GET['page_id']); 
			$myArr = array("tittle" => $meta[0]->meta_tittle, "keywords" => $meta[0]->meta_keywords, "description" => $meta[0]->meta_description, "fb_title" => $meta[0]->fb_title, "fb_description" => $meta[0]->fb_description, "fb_image" => $meta[0]->fb_image);
			echo json_encode($myArr);
			exit;
		}
		 if(isset($_POST['update_meta'])){ 
		   // update image		 
			 if($request->hasFile('fb_image')){ // Check if File is added				   
					$fileLogo = $request->file('fb_image') ;
					$image_name = $fileLogo->getClientOriginalName();
					$filename = pathinfo($image_name,PATHINFO_FILENAME);
					$image_ext = $fileLogo->getClientOriginalExtension();
					//$fileNameToStore = $filename.'-'.time().'.'.$image_ext;
					$date_time_array = getdate(time());
					$fileNameToStore =	$_POST['page_id'].'_'.$filename.'.'.$image_ext;					   
					$destinationPath = public_path().'/seo' ;
					$fileLogo->move($destinationPath,$fileNameToStore);
					//$path =  $request->file('image')->storeAs('public/Logos',$fileNameToStore);
					$this->admin_model->add_fb_image($_POST['page_id'],$fileNameToStore);   
			}  

		   $result = $this->admin_model->updateMeta($_POST); 
      	   if($result>0)
		   {
         	   return Redirect::to("admin/seo?page_id=".$_POST['page_id']."&success=1");   exit;
		   }
		   else
		   {
		       return Redirect::to("admin/seo?page_id=".$_POST['page_id']."&error=1");   exit;

		   }
		 }
		 
	   $page_id = 4;
	   
	   if(isset($_GET['page_id']))
	   {
	     $page_id = $_GET['page_id'];
	   }

	   $output['meta'] = $this->admin_model->getPageText($page_id);
	   
	   if(isset($_GET['success'])){
			   $output['alert_class'] = 'alert alert-info';
			   $output['alert_message'] = 'Updated successfully !';
	   }else if(isset($_GET['error'])){
			   $output['alert_class'] = 'alert alert-danger';
			   $output['alert_message'] = 'Error occured, please try again.';
	   }
		
		return view('/admin/admin_ListSEO',$output);
	}
	/**
     * List Admin/Pages.
     *
     * @GS
     */		
	public function admin_ListPages(){ 
		
		$output = array();
		// header data pass starts
		$admin_name = Auth::user()->name;
		$user_role = Auth::user()->user_role;
		$loggedUserId = Auth::user()->id;
		
		$logo_data = array(
				'logo_id' => 1,
				);

			$logo_details = DB::table('website_logo')
			->where($logo_data)
			->first();  
			
			$get_logo = $logo_details->logo;
		
		$output['pageTitle'] = 'Digiwaxx Admin - Pages';
		$output['logo'] = $get_logo;
		$output['welcome_name'] = $admin_name;
		$output['user_role'] = $user_role;
		
		if(isset($_POST['addBannerText'])){

		     $result = $this->admin_model->addBannerText($_POST['description'],'1'); 

			 if($result>0){
				 
			   return Redirect::to("admin/pages?bannerSuccess=1");   exit;
			   
			}else{

			   return Redirect::to("admin/pages?error=1");   exit;

			}

		}
		
		if(isset($_POST['addBannerText1'])){
			
		   $result = $this->admin_model->addBannerText($_POST['description1'],'7');

		   if($result>0){

		     return Redirect::to("admin/pages?content1Success=1");  exit;

		   }else{

		     return Redirect::to("admin/pages?error=1"); exit;

		   }
		}
		
		$output['bannerText'] = $this->admin_model->getBannerText(1); 

		$output['content1'] = $this->admin_model->getBannerText(7);

		 if(isset($_GET['bannerSuccess'])){

			   $output['alert_class'] = 'alert alert-info';

			   $output['alert_message'] = 'Banner text updated successfully !';
		}else  if(isset($_GET['content1Success'])){

			  

			   $output['alert_class1'] = 'alert alert-info';

			   $output['alert_message1'] = 'Content 1 text updated successfully !';

			

		}else if(isset($_GET['error'])){

			   $output['alert_class'] = 'alert alert-danger';

			   $output['alert_message'] = 'Error occured, please try again.';

		}
		
		return view('/admin/admin_ListPages',$output);
	}
	/**
     * WhatIsDigiwaxx Page.
     *
     * @GS
     */		
	public function admin_PageWhatIsDigiwaxx(Request $request){
		
		$output = array();
		// header data pass starts
		$admin_name = Auth::user()->name;
		$user_role = Auth::user()->user_role;
		$loggedUserId = Auth::user()->id;
		
		$logo_data = array(
				'logo_id' => 1,
				);

			$logo_details = DB::table('website_logo')
			->where($logo_data)
			->first();  
			
			//$get_logo = $logo_details->logo; //pCloudFileID
			
			$get_logo = $logo_details->pCloudFileID; //pCloudFileID
		
		$output['pageTitle'] = 'Digiwaxx Admin - What is Digiwaxx Content';
		$output['logo'] = $get_logo;
		$output['welcome_name'] = $admin_name;
		$output['user_role'] = $user_role;
		
		if(isset($_POST['addBannerText']))
		 {
		   $result = $this->admin_model->updateBannerText($_POST['description'],'2');		   

		   if($result>0)
		   {
		      return Redirect::to("admin/pages/what_is_digiwaxx?bannerSuccess=1");   exit;
		   }
		   else
		   {
		     return Redirect::to("admin/pages/what_is_digiwaxx?error=1");   exit;
		   }
		 }
		 
		 if(isset($_POST['addBannerText1']))
		 {
		   $result = $this->admin_model->updateBannerText($_POST['description1'],'11');		   

		   if($result>0)
		   {
		     return Redirect::to("admin/pages/what_is_digiwaxx?content1Success=1");   exit;
		   }
		   else
		   {
		     return Redirect::to("admin/pages/what_is_digiwaxx?error=1");   exit;
		   }
		 }
		 
		 // update top links
		 if(isset($_POST['updateTopLinks'])){
		   $result = $this->admin_model->updateTopLinks($_POST);		   

		   if($result>0)
		   {
			return Redirect::to("admin/pages/what_is_digiwaxx?updateTopLinksSuccess=1");   exit;
		   }
		   else
		   {
			return Redirect::to("admin/pages/what_is_digiwaxx?topLinkError=1");   exit;
		   }
		}

		// update links
		 if(isset($_POST['updateLinks'])){
		   $result = $this->admin_model->updatePageLinks($_POST,'2');		   

		   if($result>0)
		   {
			return Redirect::to("admin/pages/what_is_digiwaxx?updateLinksSuccess=1");   exit;
		   }
		   else
		   {
			return Redirect::to("admin/pages/what_is_digiwaxx?linkError=1");   exit;
		   }
		 }
		 $upload_success = 1;
		 $pcloud_image_id='';
		 $pcloud_image_id_banner='';
		 if(isset($_POST['updateImgs'])){
			 if($request->hasFile('logo')){ // Check if File is added		

				$date_time_array = getdate(time());
				
				$query=DB::table('website_logo')->select('pCloudFileID')->where('logo_id',1)->get();
            
                $pcloud_image_id=(int)$query[0]->pCloudFileID;
                
                
                if(!empty($pcloud_image_id)){
                 $delete_metadata = $this->delete_pcloud($pcloud_image_id);   
                }

				$date_time = $date_time_array["year"].$date_time_array["mon"].$date_time_array["mday"].$date_time_array["hours"].$date_time_array["minutes"].$date_time_array["seconds"];				
				$fileLogo = $request->file('logo') ;
				$image_name = $fileLogo->getClientOriginalName();
				$filename = pathinfo($image_name,PATHINFO_FILENAME);
				$image_ext = $fileLogo->getClientOriginalExtension();
				
				$fileNameToStore =	$date_time.'_'.$filename.'.'.$image_ext;
				
				//$fileNameToStore =	$filename.'.'.$image_ext;
				
				$destinationPath = public_path().'/images/' ;
				$fileLogo->move($destinationPath,$fileNameToStore);
				
                ## @GS pCLOUD IMAGE UPLOAD AND MAPPING
                
                $folder = 13157076166;  // PCLOUD_FOLDER_ID
        
            	$metadata = $this->uploadImage_to_pcloud($destinationPath, $fileNameToStore, $folder);
            	
            	$pcloudFileId = $metadata->metadata->fileid;
            	$parentfolderid = $metadata->metadata->parentfolderid;
            	@unlink($destinationPath.$fileNameToStore);
        
                ## @GS pCLOUD IMAGE UPLOAD AND MANAGE MAPPING
                
				$resOut = $this->admin_model->updateWebsiteLogo($fileNameToStore, $pcloudFileId, $parentfolderid);
				if($resOut == 1){
					$upload_success = 1;
				}else{
					$upload_success = 0;
				}				
			}
			
			if($request->hasFile('banner')){ // Check if File is added		

				$date_time_array = getdate(time());
				$query=DB::table('dynamic_pages')->select('pCloudFileID')->where('page_id',2)->get();
            
                $pcloud_image_id_banner=(int)$query[0]->pCloudFileID;
                
                
                if(!empty($pcloud_image_id_banner)){
                 $delete_metadata = $this->delete_pcloud($pcloud_image_id_banner);   
                }

				$date_time = $date_time_array["year"].$date_time_array["mon"].$date_time_array["mday"].$date_time_array["hours"].$date_time_array["minutes"].$date_time_array["seconds"];				
				$fileLogo = $request->file('banner') ;
				$image_name = $fileLogo->getClientOriginalName();
				$filename = pathinfo($image_name,PATHINFO_FILENAME);
				$image_ext = $fileLogo->getClientOriginalExtension();
				
				$fileNameToStore1 =	$date_time.'_'.$filename.'.'.$image_ext;
				$destinationPath = public_path().'/images/' ;
				$fileLogo->move($destinationPath,$fileNameToStore1);
				
                ## @GS pCLOUD IMAGE UPLOAD AND MAPPING
                
                $folder = 13157076166;  // PCLOUD_FOLDER_ID
        
            	$metadata = $this->uploadImage_to_pcloud($destinationPath, $fileNameToStore1, $folder);
            	
            	$pcloudFileId = $metadata->metadata->fileid;
            	$parentfolderid = $metadata->metadata->parentfolderid;
            	@unlink($destinationPath.$fileNameToStore1);
        
                ## @GS pCLOUD IMAGE UPLOAD AND MANAGE MAPPING				

				$resOut = $this->admin_model->updateWebPageBanner($fileNameToStore1,2, $pcloudFileId, $parentfolderid);
				if($resOut == 1){
					$upload_success = 1;
				}else{
					$upload_success = 0;
				}				
			}
			
			  if($upload_success>0){
				return Redirect::to("admin/pages/what_is_digiwaxx?updateImgSuccess=1");exit;
			   }
			   else
			   {
				return Redirect::to("admin/pages/what_is_digiwaxx?imgError=1");exit;
			   }
		}
		
		$output['bannerText'] = $this->admin_model->getBannerText(2); 
		$output['pageLinks'] = $this->admin_model->getPageLinks(2); 
		$output['topLinks'] = $this->admin_model->getTopLinks(); 	
	    //$output['logo'] = $this->admin_model->getLogo(); 
		$output['banner'] = $this->admin_model->getBanner(2);
		 //pArr($output);die('--YSYSY');
		  if(isset($_GET['bannerSuccess']))	
			{
               $output['alert_class'] = 'alert alert-info';
			   $output['alert_message'] = 'Banner text updated successfully !';
			}
			else if(isset($_GET['content1Success']))				
			{
			   $output['alert_class1'] = 'alert alert-info';
			   $output['alert_message1'] = 'content updated successfully !';
			}
			else if(isset($_GET['updateLinksSuccess']))				
			{
			   $output['alert_class2'] = 'alert alert-info';
			   $output['alert_message2'] = 'Links updated successfully !';
			}
			else if(isset($_GET['updateImgSuccess']))				
			{
			   $output['alert_class3'] = 'alert alert-info';
			   $output['alert_message3'] = 'Images updated successfully !';
			}
			else if(isset($_GET['imgError']))				
			{
			   $output['alert_class3'] = 'alert alert-danger';
			   $output['alert_message3'] = 'Error occured, please try again.';
			}
			else if(isset($_GET['linkError']))				
			{
			   $output['alert_class2'] = 'alert alert-danger';
			   $output['alert_message2'] = 'Error occured, please try again.';
			}
			else if(isset($_GET['error']))				
			{
			   $output['alert_class'] = 'alert alert-danger';
			   $output['alert_message'] = 'Error occured, please try again.';
			}
			else if(isset($_GET['updateTopLinksSuccess']))				
			{
			   $output['alert_class4'] = 'alert alert-info';
			   $output['alert_message4'] = 'Top Links updated successfully !';
			}
			else if(isset($_GET['topLinkError']))				
			{
			   $output['alert_class4'] = 'alert alert-danger';
			   $output['alert_message4'] = 'Error occured, please try again.';
			}
		
		return view('/admin/admin_PageWhatIsDigiwaxx',$output);
	}
	/**
     * Charts Page.
     *
     * @GS
     */	
	public function admin_PageCharts(){
		
		$output = array();
		// header data pass starts
		$admin_name = Auth::user()->name;
		$user_role = Auth::user()->user_role;
		$loggedUserId = Auth::user()->id;
		
		$logo_data = array(
				'logo_id' => 1,
				);

			$logo_details = DB::table('website_logo')
			->where($logo_data)
			->first();  
			
			$get_logo = $logo_details->logo;
		
		$output['pageTitle'] = 'Digiwaxx Admin - Charts Content';
		$output['logo'] = $get_logo;
		$output['welcome_name'] = $admin_name;
		$output['user_role'] = $user_role;
		
		if(isset($_POST['addBannerText'])){

			$result = $this->admin_model->addBannerText($_POST['description'],'4'); 
			if($result>0)
			{
				return Redirect::to("admin/pages/charts?bannerSuccess=1");   exit;
			}
			else
			{
				return Redirect::to("admin/pages/charts?error=1");   exit;
			}
		}
		
		$output['bannerText'] = $this->admin_model->getBannerText(4);
		
		 if(isset($_GET['bannerSuccess']))	
			{
			   $output['alert_class'] = 'alert alert-info';
               $output['alert_message'] = 'Banner text updated successfully !';
			}
			else if(isset($_GET['error']))				
			{
			   $output['alert_class'] = 'alert alert-danger';
               $output['alert_message'] = 'Error occured, please try again.';
			}
		
		return view('/admin/admin_PageCharts',$output);
	}
	
	/**
     * Digiwaxx Radio Page.
     *
     * @GS
     */	
	public function admin_PageDigiwaxxRadio(){
		
		$output = array();
		// header data pass starts
		$admin_name = Auth::user()->name;
		$user_role = Auth::user()->user_role;
		$loggedUserId = Auth::user()->id; 
		
		$logo_data = array(
				'logo_id' => 1,
				);

			$logo_details = DB::table('website_logo')
			->where($logo_data)
			->first();  
			
			$get_logo = $logo_details->logo;
		
		$output['pageTitle'] = 'Digiwaxx Admin - Digiwaxx Radio Content';
		$output['logo'] = $get_logo;
		$output['welcome_name'] = $admin_name;
		$output['user_role'] = $user_role;
		
		if(isset($_POST['addBannerText'])){

			$result = $this->admin_model->addBannerText($_POST['description'],'5'); 
			if($result>0)
			{
				return Redirect::to("admin/pages/digiwaxx_radio?bannerSuccess=1");   exit;
			}
			else
			{
				return Redirect::to("admin/pages/digiwaxx_radio?error=1");   exit;
			}
		}
		
		$output['bannerText'] = $this->admin_model->getBannerText(5);
		
		 if(isset($_GET['bannerSuccess']))	
			{
			   $output['alert_class'] = 'alert alert-info';
               $output['alert_message'] = 'Banner text updated successfully !';
			}
			else if(isset($_GET['error']))				
			{
			   $output['alert_class'] = 'alert alert-danger';
               $output['alert_message'] = 'Error occured, please try again.';
			}
		
		return view('/admin/admin_PageDigiwaxxRadio',$output);
	}
	
	/**
     * Promote Your Project Page.
     *
     * @GS
     */	
	public function admin_PagePromoteYourProject(Request $request){
		
		$output = array();
		// header data pass starts
		$admin_name = Auth::user()->name;
		$user_role = Auth::user()->user_role;
		$loggedUserId = Auth::user()->id; 
		
		$logo_data = array(
				'logo_id' => 1,
				);

			$logo_details = DB::table('website_logo')
			->where($logo_data)
			->first();  
			
		//$get_logo = $logo_details->logo;
		
		$get_logo = $logo_details->pCloudFileID;
		
		$upload_success = 0;
		$output['pageTitle'] = 'Digiwaxx Admin - Promote Your Project';
		$output['logo'] = $get_logo;
		$output['welcome_name'] = $admin_name;
		$output['user_role'] = $user_role;
		
		if(isset($_POST['addBannerText'])){

			$result = $this->admin_model->addBannerText($_POST['description'],'3'); 
			if($result>0)
			{
				return Redirect::to("admin/pages/promote_your_project?bannerSuccess=1");   exit;
			}
			else
			{
				return Redirect::to("admin/pages/promote_your_project?error=1");   exit;
			}
		}
		if(isset($_POST['updatePromote1'])){

			$result = $this->admin_model->addBannerText($_POST['promote1'],'8'); 
			if($result>0)
			{
				return Redirect::to("admin/pages/promote_your_project?bannerSuccess=1");   exit;
			}
			else
			{
				return Redirect::to("admin/pages/promote_your_project?error=1");   exit;
			}
		}

		if(isset($_POST['updatePromote2'])){

			$result = $this->admin_model->addBannerText($_POST['promote2'],'9'); 
			if($result>0)
			{
				return Redirect::to("admin/pages/promote_your_project?bannerSuccess=1");   exit;
			}
			else
			{
				return Redirect::to("admin/pages/promote_your_project?error=1");   exit;
			}
		}
		
		if(isset($_POST['updatePromote3'])){

			$result = $this->admin_model->addBannerText($_POST['promote3'],'10'); 
			if($result>0)
			{
				return Redirect::to("admin/pages/promote_your_project?bannerSuccess=1");   exit;
			}
			else
			{
				return Redirect::to("admin/pages/promote_your_project?error=1");   exit;
			}
		}
		$pcloud_image_id='';
		if($request->hasFile('banner')){ // Check if File is added		

				$date_time_array = getdate(time());
                	$query=DB::table('dynamic_pages')->select('pCloudFileID')->where('page_id',3)->get();
            
                $pcloud_image_id=(int)$query[0]->pCloudFileID;
                
                
                if(!empty($pcloud_image_id)){
                 $delete_metadata = $this->delete_pcloud($pcloud_image_id);   
                }
				$date_time = $date_time_array["year"].$date_time_array["mon"].$date_time_array["mday"].$date_time_array["hours"].$date_time_array["minutes"].$date_time_array["seconds"];				
				$fileLogo = $request->file('banner') ;
				$image_name = $fileLogo->getClientOriginalName();
				$filename = pathinfo($image_name,PATHINFO_FILENAME);
				$image_ext = $fileLogo->getClientOriginalExtension();
				
				$fileNameToStore1 =	$date_time.'_'.$filename.'.'.$image_ext;
				
				$destinationPath = public_path().'/images/' ;
				$fileLogo->move($destinationPath,$fileNameToStore1);
				
				 ## @GS pCLOUD IMAGE UPLOAD AND MAPPING

                $folder = 13157076166;  // PCLOUD_FOLDER_ID

                $metadata = $this->uploadImage_to_pcloud($destinationPath, $fileNameToStore1, $folder);

                $pcloudFileId = $metadata->metadata->fileid;
                $parentfolderid = $metadata->metadata->parentfolderid;
                @unlink($destinationPath.$fileNameToStore1);

				$resOut = $this->admin_model->updateWebPageBanner($fileNameToStore1,3,$pcloudFileId,$parentfolderid);
				if($resOut == 1){
					$upload_success = 1;
				}else{
					$upload_success = 0;
				}	
				if($upload_success>0){
					return Redirect::to("admin/pages/promote_your_project?updateImgSuccess=1");   exit;
				}else
					{
					return Redirect::to("admin/pages/promote_your_project?imgError=1");   exit;
				}
			}
		
		$output['bannerText'] = $this->admin_model->getBannerText(3);
		$output['banner'] = $this->admin_model->getBanner(3);
		$output['promote1'] = $this->admin_model->getBannerText(8);
		$output['promote2'] = $this->admin_model->getBannerText(9);
		$output['promote3'] = $this->admin_model->getBannerText(10);
		
		 if(isset($_GET['bannerSuccess']))	
			{
			   $output['alert_class'] = 'alert alert-info';
               $output['alert_message'] = 'Banner text updated successfully !';
			}
			else if(isset($_GET['error']))				
			{
			   $output['alert_class'] = 'alert alert-danger';
               $output['alert_message'] = 'Error occured, please try again.';
			}else if(isset($_GET['promoteSuccess'])){

			   $output['alert_class'] = 'alert alert-info';

			   $output['alert_message'] = 'Text updated successfully !';
			}else if(isset($_GET['updateImgSuccess'])){
			   $output['alert_class3'] = 'alert alert-info';
			   $output['alert_message3'] = 'Images updated successfully !';
			}else if(isset($_GET['imgError'])){
			   $output['alert_class3'] = 'alert alert-danger';
			   $output['alert_message3'] = 'Error occured, please try again.';
			}
		
		return view('/admin/admin_PagePromoteYourProject',$output);
	}
	
	/**
     * Contact Us Page.
     *
     * @GS
     */	
	public function admin_PageContactUs(Request $request){
		
		$output = array();
		// header data pass starts
		$admin_name = Auth::user()->name;
		$user_role = Auth::user()->user_role;
		$loggedUserId = Auth::user()->id;
		
		$logo_data = array(
				'logo_id' => 1,
				);

			$logo_details = DB::table('website_logo')
			->where($logo_data)
			->first();  
			
			$get_logo = $logo_details->logo;
		
		$output['pageTitle'] = 'Digiwaxx Admin - Contact Us Content';
		$output['logo'] = $get_logo;
		$output['welcome_name'] = $admin_name;
		$output['user_role'] = $user_role;
		
		if(isset($_POST['addBannerText'])){

			$result = $this->admin_model->addBannerText($_POST['description'],'6'); 
			if($result>0)
			{
				return Redirect::to("admin/pages/contact_us?bannerSuccess=1");   exit;
			}
			else
			{
				return Redirect::to("admin/pages/contact_us?error=1");   exit;
			}
		}
		
		if(isset($_POST['addGetInTouchText'])){

			$result = $this->admin_model->updateContentUsingMeta('6',$_POST['getintouch'],'getintouch'); 
			if($result>0)
			{
				return Redirect::to("admin/pages/contact_us?bannerSuccess=1");   exit;
			}
			else
			{
				return Redirect::to("admin/pages/contact_us?error=1");   exit;
			}
		}
		$pcloud_image_id='';
		  if($request->hasFile('banner')){ // Check if File is added		

				$date_time_array = getdate(time());
				$query=DB::table('dynamic_pages')->select('pCloudFileID')->where('page_id',6)->get();
            
               if(!empty($query[0]->pCloudFileID)){
                    $pcloud_image_id=(int)$query[0]->pCloudFileID;
                    
                    
                    if(!empty($pcloud_image_id)){
                     $delete_metadata = $this->delete_pcloud($pcloud_image_id);   
                    }
               }

				$date_time = $date_time_array["year"].$date_time_array["mon"].$date_time_array["mday"].$date_time_array["hours"].$date_time_array["minutes"].$date_time_array["seconds"];				
				$fileLogo = $request->file('banner') ;
				$image_name = $fileLogo->getClientOriginalName();
				$filename = pathinfo($image_name,PATHINFO_FILENAME);
				$image_ext = $fileLogo->getClientOriginalExtension();
				//$fileNameToStore = $filename.'-'.time().'.'.$image_ext;				
				$fileNameToStore1 =	$date_time.'_'.$filename.'.'.$image_ext;
				//pArr($fileNameToStore1);die('---GSGSG');				
				$destinationPath = public_path().'/images/' ;
				$fileLogo->move($destinationPath,$fileNameToStore1);

                 ## @GS pCLOUD IMAGE UPLOAD AND MAPPING

                $folder = 13157076166;  // PCLOUD_FOLDER_ID

                $metadata = $this->uploadImage_to_pcloud($destinationPath, $fileNameToStore1, $folder);

                $pcloudFileId = $metadata->metadata->fileid;
                $parentfolderid = $metadata->metadata->parentfolderid;
                @unlink($destinationPath.$fileNameToStore1);

                ## @GS pCLOUD IMAGE UPLOAD AND MANAGE MAPPING

				$resOut = $this->admin_model->updateWebPageBanner($fileNameToStore1,6,$pcloudFileId,$parentfolderid);
				if($resOut == 1){
					$upload_success = 1;
				}else{
					$upload_success = 0;
				}	
				if($upload_success>0){
					return Redirect::to("admin/pages/contact_us?updateImgSuccess=1");   exit;
				}else
					{
					return Redirect::to("admin/pages/contact_us?imgError=1");   exit;
				}
			}
		
		$output['bannerText'] = $this->admin_model->getBannerText(6);
		$output['getInTouchText'] = $this->admin_model->getContentUsingMeta(6, 'getintouch');
		$output['banner'] = $this->admin_model->getBanner(6);
		/* echo'<pre>';print_r($output);die(); */
		 if(isset($_GET['bannerSuccess']))	
			{
			   $output['alert_class'] = 'alert alert-info';
               $output['alert_message'] = 'Text updated successfully !';
			}
			else if(isset($_GET['error']))				
			{
			   $output['alert_class'] = 'alert alert-danger';
               $output['alert_message'] = 'Error occured, please try again.';
			}else if(isset($_GET['updateImgSuccess'])){
			   $output['alert_class3'] = 'alert alert-info';
			   $output['alert_message3'] = 'Images updated successfully !';
			}else if(isset($_GET['imgError'])){
			   $output['alert_class3'] = 'alert alert-danger';
			   $output['alert_message3'] = 'Error occured, please try again.';
			}
		
		return view('/admin/admin_PageContactUs',$output);
	}

	/**
     * Press Page.
     *
     * @GS
     */	
	public function admin_PagePress(){
		
		$output = array();
		// header data pass starts
		$admin_name = Auth::user()->name;
		$user_role = Auth::user()->user_role;
		$loggedUserId = Auth::user()->id;
		
		$logo_data = array(
				'logo_id' => 1,
				);

			$logo_details = DB::table('website_logo')
			->where($logo_data)
			->first();  
			
			$get_logo = $logo_details->logo;
		
		$output['pageTitle'] = 'Digiwaxx Admin - Press Page Content';
		$output['logo'] = $get_logo;
		$output['welcome_name'] = $admin_name;
		$output['user_role'] = $user_role;
		
		if(isset($_POST['addPageText'])){

			$result = $this->admin_model->updatePageText($_POST['content'],'7'); 
			if($result>0)
			{
				return Redirect::to("admin/pages/press_page?bannerSuccess=1");   exit;
			}
			else
			{
				return Redirect::to("admin/pages/press_page?error=1");   exit;
			}
		}
		
		$output['content'] = $this->admin_model->getPageText(7);
		
		 if(isset($_GET['bannerSuccess']))	
			{
			   $output['alert_class'] = 'alert alert-info';
               $output['alert_message'] = 'Content updated successfully !';
			}
			else if(isset($_GET['error']))				
			{
			   $output['alert_class'] = 'alert alert-danger';
               $output['alert_message'] = 'Error occured, please try again.';
			}
		
		return view('/admin/admin_PagePress',$output);
	}

	/**
     * Clients Page.
     *
     * @GS
     */	
	public function admin_PageClientsPage(){
		
		$output = array();
		// header data pass starts
		$admin_name = Auth::user()->name;
		$user_role = Auth::user()->user_role;
		$loggedUserId = Auth::user()->id;
		
		$logo_data = array(
				'logo_id' => 1,
				);

			$logo_details = DB::table('website_logo')
			->where($logo_data)
			->first();  
			
			$get_logo = $logo_details->logo;
		
		$output['pageTitle'] = 'Digiwaxx Admin - Clients Page Content';
		$output['logo'] = $get_logo;
		$output['welcome_name'] = $admin_name;
		$output['user_role'] = $user_role;
		
		if(isset($_POST['addPageText'])){

			$result = $this->admin_model->updatePageText($_POST['content'],'8'); 
			if($result>0)
			{
				return Redirect::to("admin/pages/clients_page?bannerSuccess=1");   exit;
			}
			else
			{
				return Redirect::to("admin/pages/clients_page?error=1");   exit;
			}
		}
		
		$output['content'] = $this->admin_model->getPageText(8);
		
		 if(isset($_GET['bannerSuccess']))	
			{
			   $output['alert_class'] = 'alert alert-info';
               $output['alert_message'] = 'Content updated successfully !';
			}
			else if(isset($_GET['error']))				
			{
			   $output['alert_class'] = 'alert alert-danger';
               $output['alert_message'] = 'Error occured, please try again.';
			}
		
		return view('/admin/admin_PageClientsPage',$output);
	}
	
	/**
     * WallOfScratch Page.
     *
     * @GS
     */	
	public function admin_PageWallOfScratch(){
		
		$output = array();
		// header data pass starts
		$admin_name = Auth::user()->name;
		$user_role = Auth::user()->user_role;
		$loggedUserId = Auth::user()->id;
		
		$logo_data = array(
				'logo_id' => 1,
				);

			$logo_details = DB::table('website_logo')
			->where($logo_data)
			->first();  
			
			$get_logo = $logo_details->logo;
		
		$output['pageTitle'] = 'Digiwaxx Admin - Wall Of Scratch Content';
		$output['logo'] = $get_logo;
		$output['welcome_name'] = $admin_name;
		$output['user_role'] = $user_role;
		
		if(isset($_POST['addPageText'])){

			$result = $this->admin_model->updatePageText($_POST['content'],'9'); 
			if($result>0)
			{
				return Redirect::to("admin/pages/wall_of_scratch?success=1");   exit;
			}
			else
			{
				return Redirect::to("admin/pages/wall_of_scratch?error=1");   exit;
			}
		}
		
		$output['content'] = $this->admin_model->getPageText(9);
		
		 if(isset($_GET['success']))	
			{
			   $output['alert_class'] = 'alert alert-info';
               $output['alert_message'] = 'Content updated successfully !';
			}
			else if(isset($_GET['error']))				
			{
			   $output['alert_class'] = 'alert alert-danger';
               $output['alert_message'] = 'Error occured, please try again.';
			}
		
		return view('/admin/admin_PageWallOfScratch',$output);
	}

	/**
     * What We Do Page.
     *
     * @GS
     */	
	public function admin_PageWhatWeDo(){
		
		$output = array();
		// header data pass starts
		$admin_name = Auth::user()->name;
		$user_role = Auth::user()->user_role;
		$loggedUserId = Auth::user()->id;
		
		$logo_data = array(
				'logo_id' => 1,
				);

			$logo_details = DB::table('website_logo')
			->where($logo_data)
			->first();  
			
			$get_logo = $logo_details->logo;
		
		$output['pageTitle'] = 'Digiwaxx Admin - What We Do Content';
		$output['logo'] = $get_logo;
		$output['welcome_name'] = $admin_name;
		$output['user_role'] = $user_role;
		
		if(isset($_POST['addPageText'])){

			$result = $this->admin_model->updatePageText($_POST['content'],'10'); 
			if($result>0)
			{
				return Redirect::to("admin/pages/what_we_do?success=1");   exit;
			}
			else
			{
				return Redirect::to("admin/pages/what_we_do?error=1");   exit;
			}
		}
		
		$output['content'] = $this->admin_model->getPageText(10);
		
		 if(isset($_GET['success']))	
			{
			   $output['alert_class'] = 'alert alert-info';
               $output['alert_message'] = 'Content updated successfully !';
			}
			else if(isset($_GET['error']))				
			{
			   $output['alert_class'] = 'alert alert-danger';
               $output['alert_message'] = 'Error occured, please try again.';
			}
		
		return view('/admin/admin_PageWhatWeDo',$output);
	}

	/**
     * Free Promo Page.
     *
     * @GS
     */	
	public function admin_PageFreePromo(){
		
		$output = array();
		// header data pass starts
		$admin_name = Auth::user()->name;
		$user_role = Auth::user()->user_role;
		$loggedUserId = Auth::user()->id;
		
		$logo_data = array(
				'logo_id' => 1,
				);

			$logo_details = DB::table('website_logo')
			->where($logo_data)
			->first();  
			
			$get_logo = $logo_details->logo;
		
		$output['pageTitle'] = 'Digiwaxx Admin - Free Promo Content';
		$output['logo'] = $get_logo;
		$output['welcome_name'] = $admin_name;
		$output['user_role'] = $user_role;
		
		if(isset($_POST['addPageText'])){

			$result = $this->admin_model->updatePageText($_POST['content'],'11'); 
			if($result>0)
			{
				return Redirect::to("admin/pages/free_promo?success=1");   exit;
			}
			else
			{
				return Redirect::to("admin/pages/free_promo?error=1");   exit;
			}
		}
		
		$output['content'] = $this->admin_model->getPageText(11);
		
		 if(isset($_GET['success']))	
			{
			   $output['alert_class'] = 'alert alert-info';
               $output['alert_message'] = 'Content updated successfully !';
			}
			else if(isset($_GET['error']))				
			{
			   $output['alert_class'] = 'alert alert-danger';
               $output['alert_message'] = 'Error occured, please try again.';
			}
		
		return view('/admin/admin_PageFreePromo',$output);
	}

	/**
     * Events Page.
     *
     * @GS
     */	
	public function admin_PageEvents(){
		
		$output = array();
		// header data pass starts
		$admin_name = Auth::user()->name;
		$user_role = Auth::user()->user_role;
		$loggedUserId = Auth::user()->id;
		
		$logo_data = array(
				'logo_id' => 1,
				);

			$logo_details = DB::table('website_logo')
			->where($logo_data)
			->first();  
			
			$get_logo = $logo_details->logo;
		
		$output['pageTitle'] = 'Digiwaxx Admin - Events Content';
		$output['logo'] = $get_logo;
		$output['welcome_name'] = $admin_name;
		$output['user_role'] = $user_role;
		
		if(isset($_POST['addPageText'])){

			$result = $this->admin_model->updatePageText($_POST['content'],'12'); 
			if($result>0)
			{
				return Redirect::to("admin/pages/events?success=1");   exit;
			}
			else
			{
				return Redirect::to("admin/pages/events?error=1");   exit;
			}
		}
		
		$output['content'] = $this->admin_model->getPageText(12);
		
		 if(isset($_GET['success']))	
			{
			   $output['alert_class'] = 'alert alert-info';
               $output['alert_message'] = 'Content updated successfully !';
			}
			else if(isset($_GET['error']))				
			{
			   $output['alert_class'] = 'alert alert-danger';
               $output['alert_message'] = 'Error occured, please try again.';
			}
		
		return view('/admin/admin_PageEvents',$output);
	}

	/**
     * Testimonials Page.
     *
     * @GS
     */	
	public function admin_PageTestimonials(){
		
		$output = array();
		// header data pass starts
		$admin_name = Auth::user()->name;
		$user_role = Auth::user()->user_role;
		$loggedUserId = Auth::user()->id;
		
		$logo_data = array(
				'logo_id' => 1,
				);

			$logo_details = DB::table('website_logo')
			->where($logo_data)
			->first();  
			
			$get_logo = $logo_details->logo;
		
		$output['pageTitle'] = 'Digiwaxx Admin - Testimonials Content';
		$output['logo'] = $get_logo;
		$output['welcome_name'] = $admin_name;
		$output['user_role'] = $user_role;
		
		if(isset($_POST['addPageText'])){

			$result = $this->admin_model->updatePageText($_POST['content'],'13'); 
			if($result>0)
			{
				return Redirect::to("admin/pages/testimonials?success=1");   exit;
			}
			else
			{
				return Redirect::to("admin/pages/testimonials?error=1");   exit;
			}
		}
		
		$output['content'] = $this->admin_model->getPageText(13);
		
		 if(isset($_GET['success']))	
			{
			   $output['alert_class'] = 'alert alert-info';
               $output['alert_message'] = 'Content updated successfully !';
			}
			else if(isset($_GET['error']))				
			{
			   $output['alert_class'] = 'alert alert-danger';
               $output['alert_message'] = 'Error occured, please try again.';
			}
		
		return view('/admin/admin_PageTestimonials',$output);
	}

	/**
     * Why Join Page.
     *
     * @GS
     */	
	public function admin_PageWhyJoin(Request $request){
		
		$output = array();
		// header data pass starts
		$admin_name = Auth::user()->name;
		$user_role = Auth::user()->user_role;
		$loggedUserId = Auth::user()->id;
		
		$logo_data = array(
				'logo_id' => 1,
				);

			$logo_details = DB::table('website_logo')
			->where($logo_data)
			->first();  
			
			$get_logo = $logo_details->logo;
		
		$output['pageTitle'] = 'Digiwaxx Admin - Why Join Content';
		$output['logo'] = $get_logo;
		$output['welcome_name'] = $admin_name;
		$output['user_role'] = $user_role;
		
		if(isset($_POST['addPageText'])){

			$result = $this->admin_model->updatePageText($_POST['content'],'14'); 
			if($result>0)
			{
				return Redirect::to("admin/pages/why_join?success=1");   exit;
			}
			else
			{
				return Redirect::to("admin/pages/why_join?error=1");   exit;
			}
		}
		$pcloud_image_id='';
		
			if($request->hasFile('banner')){ // Check if File is added	
			

				$date_time_array = getdate(time()); 
				
				$query=DB::table('dynamic_pages')->select('pCloudFileID')->where('page_id',14)->get();
            
               if(!empty($query[0]->pCloudFileID)){
                    $pcloud_image_id=(int)$query[0]->pCloudFileID;
                    
                    
                    if(!empty($pcloud_image_id)){
                     $delete_metadata = $this->delete_pcloud($pcloud_image_id);   
                    }
               }

				$date_time = $date_time_array["year"].$date_time_array["mon"].$date_time_array["mday"].$date_time_array["hours"].$date_time_array["minutes"].$date_time_array["seconds"];				
				$fileLogo = $request->file('banner') ;
				$image_name = $fileLogo->getClientOriginalName();
				$filename = pathinfo($image_name,PATHINFO_FILENAME);
				$image_ext = $fileLogo->getClientOriginalExtension();
				//$fileNameToStore = $filename.'-'.time().'.'.$image_ext;				
				$fileNameToStore1 =	$date_time.'_'.$filename.'.'.$image_ext;
				
				$destinationPath = public_path().'/images/' ;
				$fileLogo->move($destinationPath,$fileNameToStore1);

            	 ## @GS pCLOUD IMAGE UPLOAD AND MAPPING
            
            	$folder = 13157076166;  // PCLOUD_FOLDER_ID
            
            	$metadata = $this->uploadImage_to_pcloud($destinationPath, $fileNameToStore1, $folder);
            
            	$pcloudFileId = $metadata->metadata->fileid;
            	$parentfolderid = $metadata->metadata->parentfolderid;
            	@unlink($destinationPath.$fileNameToStore1);
            
            	## @GS pCLOUD IMAGE UPLOAD AND MANAGE MAPPING

				$resOut = $this->admin_model->updateWebPageBanner($fileNameToStore1,14,$pcloudFileId,$parentfolderid);
				if($resOut == 1){
					$upload_success = 1;
				}else{
					$upload_success = 0;
				}	
				if($upload_success>0){
					return Redirect::to("admin/pages/why_join?updateImgSuccess=1");   exit;
				}else
					{
					return Redirect::to("admin/pages/why_join?imgError=1");   exit;
				}
			}
		
		$output['content'] = $this->admin_model->getPageText(14);
		$output['banner'] = $this->admin_model->getBanner(14);
		
		 if(isset($_GET['success']))	
			{
			   $output['alert_class'] = 'alert alert-info';
               $output['alert_message'] = 'Content updated successfully !';
			}
			else if(isset($_GET['error']))				
			{
			   $output['alert_class'] = 'alert alert-danger';
               $output['alert_message'] = 'Error occured, please try again.';
			}else if(isset($_GET['updateImgSuccess'])){
			   $output['alert_class3'] = 'alert alert-info';
			   $output['alert_message3'] = 'Images updated successfully !';
			}else if(isset($_GET['imgError'])){
			   $output['alert_class3'] = 'alert alert-danger';
			   $output['alert_message3'] = 'Error occured, please try again.';
			}
		
		return view('/admin/admin_PageWhyJoin',$output);
	}
	/**
     * Privacy Policy Page.
     *
     * @GS
     */	
	public function admin_PagePrivacyPolicy(Request $request){
		
		$output = array();
		// header data pass starts
		$admin_name = Auth::user()->name;
		$user_role = Auth::user()->user_role;
		$loggedUserId = Auth::user()->id;
		
		$logo_data = array(
				'logo_id' => 1,
				);

			$logo_details = DB::table('website_logo')
			->where($logo_data)
			->first();  
			
			$get_logo = $logo_details->logo;
		
		$output['pageTitle'] = 'Digiwaxx Admin - Digiwaxx Privacy Policy';
		$output['logo'] = $get_logo;
		$output['welcome_name'] = $admin_name;
		$output['user_role'] = $user_role;
		
		if(isset($_POST['addPageText'])){

			$result = $this->admin_model->updatePageText($_POST['content'],'14'); 
			if($result>0)
			{
				return Redirect::to("admin/pages/privacy_policy?success=1");   exit;
			}
			else
			{
				return Redirect::to("admin/pages/privacy_policy?error=1");   exit;
			}
		}
		
			if($request->hasFile('banner')){ // Check if File is added		

				$date_time_array = getdate(time());

				$date_time = $date_time_array["year"].$date_time_array["mon"].$date_time_array["mday"].$date_time_array["hours"].$date_time_array["minutes"].$date_time_array["seconds"];				
				$fileLogo = $request->file('banner') ;
				$image_name = $fileLogo->getClientOriginalName();
				$filename = pathinfo($image_name,PATHINFO_FILENAME);
				$image_ext = $fileLogo->getClientOriginalExtension();
				//$fileNameToStore = $filename.'-'.time().'.'.$image_ext;				
				$fileNameToStore1 =	$date_time.'_'.$filename.'.'.$image_ext;
				//pArr($fileNameToStore1);die('---GSGSG');				
				$destinationPath = public_path().'/images' ;
				$fileLogo->move($destinationPath,$fileNameToStore1);
				//$path =  $request->file('image')->storeAs('public/Logos',$fileNameToStore);
				$resOut = $this->admin_model->updateWebPageBanner($fileNameToStore1,14);
				if($resOut == 1){
					$upload_success = 1;
				}else{
					$upload_success = 0;
				}	
				if($upload_success>0){
					return Redirect::to("admin/pages/privacy_policy?updateImgSuccess=1");   exit;
				}else
					{
					return Redirect::to("admin/pages/privacy_policy?imgError=1");   exit;
				}
			}
		
		$contentPage = $this->admin_model->getPageText(18);
		$output['content'] = $contentPage;
		$output['bannerText'] = 'Digiwaxx Privacy Policy';
		$output['banner'] = $this->admin_model->getBanner(18);
		
		 if(isset($_GET['success']))	
			{
			   $output['alert_class'] = 'alert alert-info';
               $output['alert_message'] = 'Content updated successfully !';
			}
			else if(isset($_GET['error']))				
			{
			   $output['alert_class'] = 'alert alert-danger';
               $output['alert_message'] = 'Error occured, please try again.';
			}else if(isset($_GET['updateImgSuccess'])){
			   $output['alert_class3'] = 'alert alert-info';
			   $output['alert_message3'] = 'Images updated successfully !';
			}else if(isset($_GET['imgError'])){
			   $output['alert_class3'] = 'alert alert-danger';
			   $output['alert_message3'] = 'Error occured, please try again.';
			}
		
		return view('/admin/admin_PagePrivacyPolicy',$output);
	}

	/**
     * Sponsor Advertise Page.
     *
     * @GS
     */	
	public function admin_PageSponsorAdvert(Request $request){
		
		$output = array();
		// header data pass starts
		$admin_name = Auth::user()->name;
		$user_role = Auth::user()->user_role;
		$loggedUserId = Auth::user()->id;
		
		$logo_data = array(
				'logo_id' => 1,
				);

			$logo_details = DB::table('website_logo')
			->where($logo_data)
			->first();  
			
			$get_logo = $logo_details->logo;
		
		$output['pageTitle'] = 'Digiwaxx Admin - Why Join Content';
		$output['logo'] = $get_logo;
		$output['welcome_name'] = $admin_name;
		$output['user_role'] = $user_role;
		
		if(isset($_POST['addPageText'])){

			$result = $this->admin_model->updatePageText($_POST['content'],'15'); 
			if($result>0)
			{
				return Redirect::to("admin/pages/sponsor_advertise?success=1");   exit;
			}
			else
			{
				return Redirect::to("admin/pages/sponsor_advertise?error=1");   exit;
			}
		}
		
		if(isset($_POST['updateBannerText'])){

			$result = $this->admin_model->addBannerText($_POST['bannerText'],'15'); 
			if($result>0)
			{
				return Redirect::to("admin/pages/sponsor_advertise?bannerSuccess=1");   exit;
			}
			else
			{
				return Redirect::to("admin/pages/sponsor_advertise?error=1");   exit;
			}
		}
		$pcloud_image_id='';
		if($request->hasFile('banner')){ // Check if File is added		

				$date_time_array = getdate(time());
                	$query=DB::table('dynamic_pages')->select('pCloudFileID')->where('page_id',15)->get();
            
               if(!empty($query[0]->pCloudFileID)){
                    $pcloud_image_id=(int)$query[0]->pCloudFileID;
                    
                    
                    if(!empty($pcloud_image_id)){
                     $delete_metadata = $this->delete_pcloud($pcloud_image_id);   
                    }
               }
				$date_time = $date_time_array["year"].$date_time_array["mon"].$date_time_array["mday"].$date_time_array["hours"].$date_time_array["minutes"].$date_time_array["seconds"];				
				$fileLogo = $request->file('banner') ;
				$image_name = $fileLogo->getClientOriginalName();
				$filename = pathinfo($image_name,PATHINFO_FILENAME);
				$image_ext = $fileLogo->getClientOriginalExtension();
				//$fileNameToStore = $filename.'-'.time().'.'.$image_ext;				
				$fileNameToStore1 =	$date_time.'_'.$filename.'.'.$image_ext;
				
				$destinationPath = public_path().'/images/' ;
				$fileLogo->move($destinationPath,$fileNameToStore1);
				
            	 ## @GS pCLOUD IMAGE UPLOAD AND MAPPING
            
            	$folder = 13157076166;  // PCLOUD_FOLDER_ID
            
            	$metadata = $this->uploadImage_to_pcloud($destinationPath, $fileNameToStore1, $folder);
            
            	$pcloudFileId = $metadata->metadata->fileid;
            	$parentfolderid = $metadata->metadata->parentfolderid;
            	@unlink($destinationPath.$fileNameToStore1);
            
            	## @GS pCLOUD IMAGE UPLOAD AND MANAGE MAPPING				

				$resOut = $this->admin_model->updateWebPageBanner($fileNameToStore1,15,$pcloudFileId,$parentfolderid);
				if($resOut == 1){
					$upload_success = 1;
				}else{
					$upload_success = 0;
				}	
				if($upload_success>0){
					return Redirect::to("admin/pages/sponsor_advertise?updateImgSuccess=1");   exit;
				}else
					{
					return Redirect::to("admin/pages/sponsor_advertise?imgError=1");   exit;
				}
			}
		
		$output['content'] = $this->admin_model->getPageText(15);
		$output['bannerText'] = $this->admin_model->getBannerText(15);
		$output['banner'] = $this->admin_model->getBanner(15); 
		 if(isset($_GET['success']))	
			{
			   $output['alert_class'] = 'alert alert-info';
               $output['alert_message'] = 'Content updated successfully !';
			}else if(isset($_GET['bannerSuccess']))	
			{
			   $output['alert_class'] = 'alert alert-info';
               $output['alert_message'] = 'Content updated successfully !';
			}
			else if(isset($_GET['error']))				
			{
			   $output['alert_class'] = 'alert alert-danger';
               $output['alert_message'] = 'Error occured, please try again.';
			}else if(isset($_GET['updateImgSuccess'])){
			   $output['alert_class3'] = 'alert alert-info';
			   $output['alert_message3'] = 'Images updated successfully !';
			}else if(isset($_GET['imgError'])){
			   $output['alert_class3'] = 'alert alert-danger';
			   $output['alert_message3'] = 'Error occured, please try again.';
			}
		
		return view('/admin/admin_PageSponsorAdvert',$output);
	}


	/**
     * Help Page.
     *
     * @GS
     */	
	public function admin_PageHelp(Request $request){
		
		$output = array();
		// header data pass starts
		$admin_name = Auth::user()->name;
		$user_role = Auth::user()->user_role;
		$loggedUserId = Auth::user()->id;
		
		$logo_data = array(
				'logo_id' => 1,
				);

			$logo_details = DB::table('website_logo')
			->where($logo_data)
			->first();  
			
			$get_logo = $logo_details->logo;
		
		$output['pageTitle'] = 'Digiwaxx Admin - Help Content';
		$output['logo'] = $get_logo;
		$output['welcome_name'] = $admin_name;
		$output['user_role'] = $user_role;
		
		if(isset($_POST['addPageText'])){

			$result = $this->admin_model->updatePageText($_POST['content'],'17'); 
			if($result>0)
			{
				return Redirect::to("admin/pages/help?success=1");   exit;
			}
			else
			{
				return Redirect::to("admin/pages/help?error=1");   exit;
			}
		}
		
		if(isset($_POST['updateBannerText']))
		 {
		   $result = $this->admin_model->addBannerText($_POST['bannerText'],'17'); 
           if($result>0){
    		      return Redirect::to("admin/pages/help?bannerSuccess=1");   exit;
		   }else{
				return Redirect::to("admin/pages/help?bannerError=1");   exit;
		   }
	    }
		$pcloud_image_id='';
			if($request->hasFile('banner')){ // Check if File is added		

				$date_time_array = getdate(time());
					$query=DB::table('dynamic_pages')->select('pCloudFileID')->where('page_id',17)->get();
            
               if(!empty($query[0]->pCloudFileID)){
                    $pcloud_image_id=(int)$query[0]->pCloudFileID;
                    
                    
                    if(!empty($pcloud_image_id)){
                     $delete_metadata = $this->delete_pcloud($pcloud_image_id);   
                    }
               }

				$date_time = $date_time_array["year"].$date_time_array["mon"].$date_time_array["mday"].$date_time_array["hours"].$date_time_array["minutes"].$date_time_array["seconds"];				
				$fileLogo = $request->file('banner') ;
				$image_name = $fileLogo->getClientOriginalName();
				$filename = pathinfo($image_name,PATHINFO_FILENAME);
				$image_ext = $fileLogo->getClientOriginalExtension();
				//$fileNameToStore = $filename.'-'.time().'.'.$image_ext;				
				$fileNameToStore1 =	$date_time.'_'.$filename.'.'.$image_ext;
				
				$destinationPath = public_path().'/images/' ;
				$fileLogo->move($destinationPath,$fileNameToStore1);

            	 ## @GS pCLOUD IMAGE UPLOAD AND MAPPING
            
            	$folder = 13157076166;  // PCLOUD_FOLDER_ID
            
            	$metadata = $this->uploadImage_to_pcloud($destinationPath, $fileNameToStore1, $folder);
            
            	$pcloudFileId = $metadata->metadata->fileid;
            	$parentfolderid = $metadata->metadata->parentfolderid;
            	@unlink($destinationPath.$fileNameToStore1);
            
            	## @GS pCLOUD IMAGE UPLOAD AND MANAGE MAPPING
            
            	$resOut = $this->admin_model->updateWebPageBanner($fileNameToStore1,17,$pcloudFileId,$parentfolderid);
				if($resOut == 1){
					$upload_success = 1;
				}else{
					$upload_success = 0;
				}	
				if($upload_success>0){
					return Redirect::to("admin/pages/help?updateImgSuccess=1");   exit;
				}else
					{
					return Redirect::to("admin/pages/help?imgError=1");   exit;
				}
			}
		
		$output['content'] = $this->admin_model->getPageText(17);		
		$output['bannerText'] = $this->admin_model->getBannerText(17);
		$output['banner'] = $this->admin_model->getBanner(17); 
		
			if(isset($_GET['success']))	
			{
			   $output['alert_class1'] = 'alert alert-info';
               $output['alert_message1'] = 'Content updated successfully !';
			}
			else if(isset($_GET['error']))				
			{
			   $output['alert_class1'] = 'alert alert-danger';
               $output['alert_message1'] = 'Error occured, please try again.';
			}
            else if(isset($_GET['bannerSuccess']))	
			{
			   $output['alert_class'] = 'alert alert-info';
               $output['alert_message'] = 'Banner updated successfully !';
			}
			else if(isset($_GET['bannerEror']))				
			{
			   $output['alert_class'] = 'alert alert-danger';
               $output['alert_message'] = 'Error occured, please try again.';
			}else if(isset($_GET['updateImgSuccess'])){
			   $output['alert_class3'] = 'alert alert-info';
			   $output['alert_message3'] = 'Images updated successfully !';
			}else if(isset($_GET['imgError'])){
			   $output['alert_class3'] = 'alert alert-danger';
			   $output['alert_message3'] = 'Error occured, please try again.';
			}
		
		return view('/admin/admin_PageHelp',$output);
	}

	/**
     * Im Dj Page.
     *
     * @GS
     */	
	public function admin_PageImDj(){
		
		$output = array();
		// header data pass starts
		$admin_name = Auth::user()->name;
		$user_role = Auth::user()->user_role;
		$loggedUserId = Auth::user()->id;
		
		$logo_data = array(
				'logo_id' => 1,
				);

			$logo_details = DB::table('website_logo')
			->where($logo_data)
			->first();  
			
			$get_logo = $logo_details->logo;
		
		$output['pageTitle'] = "Digiwaxx Admin - I'm a DJ Page Content";
		$output['logo'] = $get_logo;
		$output['welcome_name'] = $admin_name;
		$output['user_role'] = $user_role;
		
		 // update links
		if(isset($_POST['updateLinks'])){
			
		   $result = $this->admin_model->updateLinks($_POST,'16');		   

		   if($result>0)
		   {
				return Redirect::to("admin/pages/im_dj?success=1");   exit;
		   }
		   else
		   {
				return Redirect::to("admin/pages/im_dj?error=1");   exit;
		   }
		 }
		
		$output['pageLinks'] = $this->admin_model->getPageLinks(16); 
		
		if(isset($_GET['success']))	
			{
			   $output['alert_class'] = 'alert alert-info';
               $output['alert_message'] = 'Links updated successfully !';
			}
			else if(isset($_GET['error']))				
			{
			   $output['alert_class'] = 'alert alert-danger';
               $output['alert_message'] = 'Error occured, please try again.';
			}
		
		return view('/admin/admin_PageImDj',$output);
	}

	public function adminMembersListing(){
		
		$output = array();
		// header data pass starts
		$admin_name = Auth::user()->name;
		$user_role = Auth::user()->user_role;
		$loggedUserId = Auth::user()->id;
		
		$logo_data = array(
				'logo_id' => 1,
				);

			$logo_details = DB::table('website_logo')
			->where($logo_data)
			->first();  
			
			$get_logo = $logo_details->logo;
		
		$output['pageTitle'] = "Digiwaxx Admin - Members";
		$output['logo'] = $get_logo;
		$output['welcome_name'] = $admin_name;
		$output['user_role'] = $user_role;

		//echo $loggedUserId;die('---');
        // add member ship
        if (isset($_GET['addMembership']) && isset($_GET['memberId']) && isset($_GET['validity'])) {
            $startDate = date('Y-m-d');
            if ($_GET['validity'] == 1) {
                $endDate = date("Y-m-d", strtotime(" +29 day"));
            } else if ($_GET['validity'] == 2) {
                $endDate = date("Y-m-d", strtotime(" +179 day"));
            } else if ($_GET['validity'] == 3) {
                $endDate = date("Y-m-d", strtotime(" +364 day"));
            } else if ($_GET['validity'] == 4) {
                $endDate = date("Y-m-d", strtotime(" +89 day"));
            }
            $result = $this->admin_model->addMembership($_GET['memberId'], $_GET['validity'], $loggedUserId, $startDate, $endDate);
            $this->memeberSubscriptions($_GET['memberId']);

            if ($result > 0) {
                echo '<p style="color:#090;">Membership added successfully !</p>';
            } else if ($result == 0) {
                echo '<p style="color:#FF0000;">Error occured, please try again!</p>';
            } else if ($result == -1) {
                echo '<p style="color:#FF0000;">Already in subscribed!</p>';
            }

            exit;
        }
		
		
		
        // delete member
        if (isset($_GET['did'])) {
            $result = $this->admin_model->deleteMember($_GET['did']);
            if ($result > 0) {
				if (isset($_GET['mode']) && $_GET['mode'] == 'pending_members') {
                    return Redirect::to("admin/pending_members?delete=success");
                    exit;
                } 
				else {
                    return Redirect::to("admin/members?delete=success");
                    exit;
                }
            } 
			else {
                if (isset($_GET['mode']) && $_GET['mode'] == 'pending_members') {
                    return Redirect::to("admin/pending_members?error=1");
                    exit;
                } 
				else {
                    return Redirect::to("admin/members?error=1");
                    exit;
                }
            }
        }
		// Remove Membership
        if (isset($_GET['mid']) && isset($_GET['sid']) && isset($_GET['mode']) && $_GET['mode'] == 'deleteMemberSubsciption') {

            $result = $this->admin_model->removeMembership($_GET['mid'], $_GET['sid']);

            $this->memeberSubscriptions($_GET['mid']);
            if ($result > 0) {
                echo '<p style="color:#090;">Membership removed successfully!</p>';
            } else {
                echo '<p style="color:#FF0000;">Error occured, please try again!</p>';
            }
            exit;
        }
		
	    // generate where
        $where = 'where ';
        $whereItems[] = "members.deleted = '0'";
        $whereItems[] = "members.active = '1'";
        $output['searchCompany'] = '';
        $output['searchUsername'] = '';
        $output['searchName'] = '';
        $output['searchEmail'] = '';
        $output['searchPhone'] = '';
        $output['searchCity'] = '';
        $output['searchState'] = '';
        $output['searchZip'] = '';
        $output['searchdjMixer'] = '';
        $output['searchradioStation'] = '';
        $output['searchmassMedia'] = '';
        $output['searchrecordLabel'] = '';
        $output['searchmanagement'] = '';
        $output['searchclothingApparel'] = '';
        $output['searchpromoter'] = '';
        $output['searchspecialServices'] = '';
        $output['searchproductionTalent'] = '';
		
       if (isset($_GET['search'])) {
            if (isset($_GET['firstName']) && strlen($_GET['firstName']) > 0) {
                $output['searchFirstName'] = $_GET['firstName'];
                $whereItems[] = "(members.fname LIKE '%" . urlencode(trim($_GET['firstName'])) . "%' OR members.fname LIKE '%" . trim($_GET['firstName']) . "%')";
            }
            if (isset($_GET['lastName']) && strlen($_GET['lastName']) > 0) {
                $output['searchLastName'] = $_GET['lastName'];
                $whereItems[] = "(members.lname LIKE '%" . urlencode(trim($_GET['lastName'])) . "%' OR members.lname LIKE '%" .trim($_GET['lastName']). "%')";
            }
            if (isset($_GET['stageName']) && strlen($_GET['stageName']) > 0) {
                $output['searchStageName'] = $_GET['stageName'];
                $whereItems[] = "(members.stagename LIKE '%" . trim($_GET['stageName']) . "%' OR members.stagename LIKE '%" . urlencode(trim($_GET['stageName'])) . "%' )";
                //  $whereItems[] = "members.stagename LIKE '%" . urlencode($_GET['stageName']) . "%'";
            }
            if (isset($_GET['email']) && strlen($_GET['email']) > 0) {
                $output['searchEmail'] = $_GET['email'];
                $whereItems[] = "(members.email LIKE '%" . urlencode(trim($_GET['email'])) . "%' OR members.email LIKE '%" . trim($_GET['email']) . "%')";
            }
            if (isset($_GET['phone']) && strlen($_GET['phone']) > 0) {
                $output['searchPhone'] = $_GET['phone'];
                $whereItems[] = "members.phone LIKE '%" . trim($_GET['phone']) . "%'";
            }
            if (isset($_GET['username']) && strlen($_GET['username']) > 0) {
                $output['searchUsername'] = $_GET['username'];
                $whereItems[] = "(members.uname LIKE '%" . urlencode(trim($_GET['username'])) . "%' OR members.uname LIKE '%" . trim($_GET['username']) . "%')";
            }
            if (isset($_GET['city']) && strlen($_GET['city']) > 0) {
                $output['searchCity'] = $_GET['city'];
                $whereItems[] = "members.city LIKE '%" . trim($_GET['city']) . "%'";
            }
            if (isset($_GET['state']) && strlen($_GET['state']) > 0) {
                $output['searchState'] = $_GET['state'];
                $whereItems[] = "members.state LIKE '%" . trim($_GET['state']) . "%'";
            }
            if (isset($_GET['zip']) && strlen($_GET['zip']) > 0) {
                $output['searchZip'] = $_GET['zip'];
                $whereItems[] = "members.zip LIKE '%" . trim($_GET['zip']) . "%'";
            }

            if (isset($_GET['djMixer']) && $_GET['djMixer'] == 1) {
                $output['searchdjMixer'] = $_GET['djMixer'];
                $whereItems[] = "members.dj_mixer = 1";
            }
            if (isset($_GET['radioStation']) && $_GET['radioStation'] == 1) {
                $output['searchradioStation'] = $_GET['radioStation'];
                $whereItems[] = "members.radio_station = 1";
            }
            if (isset($_GET['massMedia']) && $_GET['massMedia'] == 1) {
                $output['searchmassMedia'] = $_GET['massMedia'];
                $whereItems[] = "members.mass_media = 1";
            }
            if (isset($_GET['recordLabel']) && $_GET['recordLabel'] == 1) {
                $output['searchrecordLabel'] = $_GET['recordLabel'];
                $whereItems[] = "members.record_label = 1";
            }
            if (isset($_GET['management']) && $_GET['management'] == 1) {
                $output['searchmanagement'] = $_GET['management'];
                $whereItems[] = "members.management = 1";
            }
            if (isset($_GET['clothingApparel']) && $_GET['clothingApparel'] == 1) {
                $output['searchclothingApparel'] = $_GET['clothingApparel'];
                $whereItems[] = "members.clothing_apparel = 1";
            }
            if (isset($_GET['promoter']) && $_GET['promoter'] == 1) {
                $output['searchpromoter'] = $_GET['promoter'];
                $whereItems[] = "members.promoter = 1";
            }
            if (isset($_GET['specialServices']) && $_GET['specialServices'] == 1) {
                $output['searchspecialServices'] = $_GET['specialServices'];
                $whereItems[] = "members.special_services = 1";
            }
            if (isset($_GET['productionTalent']) && $_GET['productionTalent'] == 1) {
                $output['searchproductionTalent'] = $_GET['productionTalent'];
                $whereItems[] = "members.production_talent = 1";
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
        $output['sortBy'] = 'registered';
        $output['sortOrder'] = 2;
        if (isset($_GET['sortBy'])) {
            $output['sortBy'] = $_GET['sortBy'];
            if (strcmp($_GET['sortBy'], 'registered') == 0) {
                $sortBy = "id";
            } else if (strcmp($_GET['sortBy'], 'firstName') == 0) {
                $sortBy = "fname";
            } else if (strcmp($_GET['sortBy'], 'lastName') == 0) {
                $sortBy = "lname";
            } else if (strcmp($_GET['sortBy'], 'stageName') == 0) {
                $sortBy = "stagename";
            } else if (strcmp($_GET['sortBy'], 'email') == 0) {
                $sortBy = "email";
            } else if (strcmp($_GET['sortBy'], 'username') == 0) {
                $sortBy = "uname";
            } else if (strcmp($_GET['sortBy'], 'phone') == 0) {
                $sortBy = "phone";
            } else if (strcmp($_GET['sortBy'], 'city') == 0) {
                $sortBy = "city";
            } else if (strcmp($_GET['sortBy'], 'state') == 0) {
                $sortBy = "title";
            } else if (strcmp($_GET['sortBy'], 'lastLogin') == 0) {
                $sortBy = "state";
            }
        }
        // sort order	  		   
        if (isset($_GET['sortOrder']) && $_GET['sortOrder'] == 1) {
            $sortOrder = "ASC";
            $output['sortOrder'] = $_GET['sortOrder'];
        } else  if (isset($_GET['sortOrder']) && $_GET['sortOrder'] == 2) {
            $sortOrder = "DESC";
            $output['sortOrder'] = $_GET['sortOrder'];
        }
        $sort =  $sortBy . " " . $sortOrder;
		
        // generate link
        $output['link_string'] = '?';
        if (isset($_GET) && count($_GET) > 0) {
            $link_items = array();
            $link_string =  '?';
            $exclude_variables = array("page");
            foreach ($_GET as $key => $value) {
                if (!(in_array($key, $exclude_variables))) {
                    $link_items[] = $key . '=' . $value;
                }
            }
            if (count($link_items) > 1) {
                $link_string = implode('&', $link_items);
                $link_string = '?' . $link_string . '&';
            } else if (count($link_items) == 1) {
                $link_string = '?' . $link_items[0] . '&';
            }
            $output['link_string'] = $link_string;
        }
        // pagination
        $limit = 10;
        if (isset($_GET['numRecords'])) {
            $limit = $_GET['numRecords'];
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
        $num_records = $this->admin_model->getNumMembers($where, $sort);
        $numPages = (int) ($num_records / $limit);
        $reminder = ($num_records % $limit);
        if ($reminder > 0) {
            $numPages = $numPages + 1;
        }
        $output['numPages'] = $numPages;
        $output['start'] = $start;
        $output['currentPageNo'] = $currentPageNo;
        $output['currentPage'] = 'members';
        if (isset($_GET['page'])) {
            if ($_GET['page'] > $numPages) {
                return Redirect::to($output['currentPage'] . $link_string . "page=" . $numPages);
                exit;
            } else if ($_GET['page'] < 1) {
                return Redirect::to($output['currentPage'] . $link_string . "page=1");
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
        $output['members']  = $this->admin_model->getMembersAll($where, $sort, $start, $limit);

        if ($output['members']['numRows'] > 0) {
            foreach ($output['members']['data'] as $member) {
                $output['membershipDetails'][$member->id]  = $this->admin_model->getMembershipDetails($member->id, 0, 10);
                //  $output['membershipDetails'][$member->id] = [$member->id];
            }
            
            //package details
            foreach ($output['members']['data'] as $member) {
                $package = DB::table('package_user_details')->select('manage_packages.package_type')->leftJoin("manage_packages", "manage_packages.id", "=", "package_user_details.package_id")->where('package_user_details.user_id','=',$member->id)->where('package_user_details.user_type','=',1)->get();
                // dd($package);
                $count = $package->count();
                if($count == 0){
                    // dd($package,$member->id);
                    $member->member_package = 'Standard';
                }else{
                    $member->member_package = $package[0]->package_type;
                }
            }
        // dd($output['members']);            
        }
        if (isset($_GET['delete'])) {
            $output['alert_message'] = "Member deleted succesfully!";
            $output['alert_class'] = "alert alert-success";
        } else  if (isset($_GET['error'])) {
            $output['alert_message'] = "Error occured, please try again!";
            $output['alert_class'] = "alert alert-danger";
        }
		
		return view('/admin/MembersAllListing',$output);
	}
	

	public function adminMemberDigicoins(){
		
		if(!isset($_GET['mid'])){
			return Redirect::to('admin/members');
		}
		$memId = $_GET['mid'];
		$output = array();
		// header data pass starts
		$admin_name = Auth::user()->name;
		$user_role = Auth::user()->user_role;
		$loggedUserId = Auth::user()->id;
		
		$logo_data = array(
				'logo_id' => 1,
				);

			$logo_details = DB::table('website_logo')
			->where($logo_data)
			->first();  
			
			$get_logo = $logo_details->logo;
		
		$output['pageTitle'] = "Digiwaxx Admin - Member DigiCoins";
		$output['logo'] = $get_logo;
		$output['welcome_name'] = $admin_name;
		$output['user_role'] = $user_role;
		$output['memId'] = $memId;
		
        // generate where 
        $where = 'where ';
        $whereItems = array();
        $whereItems[] = "member_digicoins.member_id = '" . $_GET['mid'] . "'";
        $output['searchPackage'] = '';
        $output['searchUsername'] = '';
        if (isset($_GET['search'])) {
            if (isset($_GET['package']) && $_GET['package'] > 0) {
                $output['searchPackage'] = $_GET['package'];
                $whereItems[] = "member_subscriptions.package_Id = '" . $_GET['package'] . "'";
            }
            if (isset($_GET['username']) && strlen($_GET['username']) > 0) {
                $output['searchUsername'] = $_GET['username'];
                $whereItems[] = "members.uname = '" . $_GET['username'] . "'";
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
        $sortBy = "member_digicoins.member_digicoin_id";
        //$output['sortBy'] = 'paidOn';
        $output['sortOrder'] = 2;
        if (isset($_GET['sortOrder'])) {
            $output['sortOrder'] = $_GET['sortOrder'];
            if ($_GET['sortOrder'] == 1) {
                $sortOrder = "ASC";
            } else  if ($_GET['sortOrder'] == 2) {
                $sortOrder = "DESC";
            }
        }
		
		if (isset($_GET['sortBy'])) {
			$sortBy = "member_digicoins.member_digicoin_id";
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
        $num_records = $this->admin_model->getNumViewMemberDigicoins($where, $sort);
        $numPages = (int) ($num_records / $limit);
        $reminder = ($num_records % $limit);
        if ($reminder > 0) {
            $numPages = $numPages + 1;
        }
        $output['numPages'] = $numPages;
        $output['start'] = $start;
        $output['currentPageNo'] = $currentPageNo;
        $output['currentPage'] = 'member_digicoins';
        if (isset($_GET['page'])) {
            if ($_GET['page'] > $numPages) {
                return Redirect::to("admin/".$output['currentPage'] . "?page=" . $numPages);
                exit;
            } else if ($_GET['page'] < 1) {
                return Redirect::to("admin/".$output['currentPage'] . "?page=1");
                exit;
            }
        }
        if($currentPageNo == 1){
            $output['firstPageLink'] = 'disabled';
            $output['preLink'] = 'disabled';
            $output['nextLink'] = '';
            $output['lastPageLink'] = '';
        }else if($currentPageNo == $numPages) {
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
		
		$output['digicoins'] = $this->admin_model->getViewMemberDigicoins($where, $sort, $start, $limit);
		
		return view('/admin/MembersDigicoins',$output);
	}
	

	public function adminMemberChangePassword(){
		
		if(!isset($_GET['mid'])){
			return Redirect::to('admin/members');
		}
		$memId = $_GET['mid'];
		$output = array();
		// header data pass starts
		$admin_name = Auth::user()->name; 
		$user_role = Auth::user()->user_role;
		$loggedUserId = Auth::user()->id;
		
		$logo_data = array(
				'logo_id' => 1,
				);

			$logo_details = DB::table('website_logo')
			->where($logo_data)
			->first();  
			
			$get_logo = $logo_details->logo;
		
		$output['pageTitle'] = "Digiwaxx Admin - Member Change Password";
		$output['logo'] = $get_logo;
		$output['welcome_name'] = $admin_name;
		$output['user_role'] = $user_role;
		$output['memId'] = $memId;
		
		$output['members'] = $this->admin_model->getViewMemberInfo($memId);
		
		if (isset($_POST['changePassword']) && isset($_GET['mid'])) {
		    
            $result = $this->admin_model->adminChangeMemberPassword($_POST['password'], $memId);
            if ($result > 0) {
            $name = urldecode($output['members']['data'][0]->fname);
            $email = urldecode($output['members']['data'][0]->email);
           // $email = 'sgtechqa@yopmail.com';

            $message = 'Hi '.$name.',<br><br>Your password has been changed by the admin team.<br><br>YOUR NEW PASSWORD IS <strong>'.$_POST["password"].'</strong><br><br>
    		If you DID NOT REQUEST this change, please contact us immediately by responding to this email business@digiwaxx.com, contact us via our chat or call us at 866.665.1259.
            <br><br>Thank you for being a quality member and keep breaking boundaries!<br><br>Digiwaxx Admin Team';
               /*  $this->email->message($message);
                $this->email->send(); */
				
					$data = array('emailId' => $email, 'name' => $name, 'message'=> $message);
				
					Mail::send('mails.password.adminMemberReset',['data' => $data], function ($message) use ($data) {
					  $message->to($data['emailId']);
					  $message->subject('Password changed at Digiwaxx');
					  $message->from('business@digiwaxx.com','Digiwaxx');
				   });
				   
                return Redirect::to("admin/member_change_password?mid=" . $_GET['mid'] . "&success=1");
                exit;
            } else {
                return Redirect::to("admin/member_change_password?mid=" . $_GET['mid'] . "&error=1");
                exit;
            }
        }
		
	    if (isset($_GET['success'])) {
            $output['alert_message'] = 'password updated successfully!';
            $output['alert_class'] = 'alert alert-success';
        } else if (isset($_GET['error'])) {
            $output['alert_message'] = 'Error occured, please try again!';
            $output['alert_class'] = 'alert alert-danger';
        }
		
		return view('/admin/MemberChangePassword',$output);
	}	

	public function adminMemberEditInfo(Request $request){
		
		if(!isset($_GET['mid'])){
			return Redirect::to('admin/members');
		}
		$memId = $_GET['mid'];
		$output = array();
		// header data pass starts
		$admin_name = Auth::user()->name; 
		$user_role = Auth::user()->user_role;
		$loggedUserId = Auth::user()->id;
		
		$logo_data = array(
				'logo_id' => 1,
				);

			$logo_details = DB::table('website_logo')
			->where($logo_data)
			->first();  
			
			$get_logo = $logo_details->logo;
		
		$output['pageTitle'] = "Digiwaxx Admin - Edit Member";
		$output['logo'] = $get_logo;
		$output['welcome_name'] = $admin_name;
		$output['user_role'] = $user_role;
		$output['memId'] = $memId;
		
		if (isset($_POST['updateMember']) && !empty($memId)) {          
            
            $result = $this->admin_model->checkDuplicateMemberEmail($_POST, $memId);
			
			//pArr($result);die();
            if ($result == 0) {
                return Redirect::to("admin/member_edit?mid=" . $memId . "&error=1&emailexist=1");
                exit;
            }
            
            $result = $this->admin_model->updateEditedMember($_POST, $memId, $loggedUserId);
            if ($result > 0) {
                /* $name = $_POST['firstName'];   
		   $email = $_POST['email'];   
			
			$this->load->library('email');
			$this->email->from('no-reply@digiwaxx.com', 'Digiwaxx');
			$this->email->to($email);
			$this->email->set_mailtype("html");
			$this->email->subject('Member account created at Digiwaxx');
			
			
			$message = 'Hi '.$name.',<br />
			<p>Member account created, following are the login credentials,</p>
			<p>username : '.$_POST["userName"].'</p>
			<p>password : '.$_POST["password"].'</p>';			
			
			$this->email->message($message);			
			$this->email->send();
			*/
                return Redirect::to("admin/member_edit?mid=" . $memId . "&success=1");
                exit;
            } else {
                return Redirect::to("admin/member_edit?mid=" . $memId . "&error=1");
                exit;
            }
        }
		
		$output['members'] = $this->admin_model->getViewMemberInfo($memId);
		  
        //$output['memberInfo'] = $this->frontenddb->getMemberInfo($memId); 
        $output['production'] = $this->admin_model->getMemberProductionInfo($memId);
        $output['special'] = $this->admin_model->getMemberSpecialInfo($memId);
        $output['promoter'] = $this->admin_model->getMemberPromoterInfo($memId);
        $output['clothing'] = $this->admin_model->getMemberClothingInfo($memId);
        $output['management'] = $this->admin_model->getMemberManagementInfo($memId);
        $output['record'] = $this->admin_model->getMemberRecordInfo($memId);
        $output['media'] = $this->admin_model->getMemberMediaInfo($memId);
        $output['radio'] = $this->admin_model->getMemberRadioInfo($memId);
        $output['social'] = $this->admin_model->getMemberSocialInfo($memId);
		
		if (isset($_GET['success'])) {
            $output['alert_message'] = 'Member updated successfully!';
            $output['alert_class'] = 'alert alert-success';
        } else if (isset($_GET['error'])) {
            $output['alert_message'] = 'Error occured, please try again!';
            $output['alert_class'] = 'alert alert-danger';
        }
		
		//pArr($output['media']);die();
		
		return view('/admin/MemberEditInfo',$output);
		
	}

	public function adminMemberViewInfo(Request $request){
		
		if(!isset($_GET['mid'])){
			return Redirect::to('admin/members');
		}
		$memId = $_GET['mid'];
		$output = array();
		// header data pass starts
		$admin_name = Auth::user()->name;
		$user_role = Auth::user()->user_role;
		$loggedUserId = Auth::user()->id;
		
		$logo_data = array(
				'logo_id' => 1,
				);

			$logo_details = DB::table('website_logo')
			->where($logo_data)
			->first();  
			
			$get_logo = $logo_details->logo;
		
		$output['pageTitle'] = "Digiwaxx Admin - View Member";
		$output['logo'] = $get_logo;
		$output['welcome_name'] = $admin_name;
		$output['user_role'] = $user_role;
		$output['memId'] = $memId;
		
		$output['members'] = $this->admin_model->getViewMemberInfo($memId);
		  
        //$output['memberInfo'] = $this->frontenddb->getMemberInfo($memId); 
        $output['production'] = $this->admin_model->getMemberProductionInfo($memId);
        $output['special'] = $this->admin_model->getMemberSpecialInfo($memId);
        $output['promoter'] = $this->admin_model->getMemberPromoterInfo($memId);
        $output['clothing'] = $this->admin_model->getMemberClothingInfo($memId);
        $output['management'] = $this->admin_model->getMemberManagementInfo($memId);
        $output['record'] = $this->admin_model->getMemberRecordInfo($memId);
        $output['media'] = $this->admin_model->getMemberMediaInfo($memId);
        $output['radio'] = $this->admin_model->getMemberRadioInfo($memId);
        $output['social'] = $this->admin_model->getMemberSocialInfo($memId);
        
        $query = DB::table('package_user_details')->select('package_user_details.*','manage_packages.package_name','manage_packages.package_type')->leftJoin("manage_packages", "manage_packages.id", "=", "package_user_details.package_id")->where('package_user_details.user_id','=',$memId)->where('package_user_details.package_active',1)->get();
        // dd($query);
        $count = $query->count();
        // dd($count);
        if($count == 0){
            $output['member_package_details'] = 'Standard';
            $output['package_count'] = $count;
        }else{
            $output['member_package_details'] = $query[0];
            $output['package_count'] = $count;

        }
// 		dd($output['member_package_details']);
		//pArr($output['media']);die();
		
		return view('/admin/MemberViewInfo',$output);
	}
	
	public function memeberSubscriptions($memberId){
        $result1 = $this->admin_model->getMembershipDetails($memberId, 0, 10);
	?>
        <table id="sample-table-1" class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th class="text-center" width="100">
                        S. No.
                    </th>
                    <!--<th>Status</th>-->
                    <th>Duration</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result1['numRows'] > 0) {
                    $x = 1;
                    foreach ($result1['data'] as $row) {
                ?>
                        <tr>
                            <td class="text-center"><?php echo $x; ?></td>
                            <td><?php
                                $duration = '';
                                if ($row->duration_Id == 1) {
                                    $duration = '1 Month';
                                } else if ($row->duration_Id == 2) {
                                    $duration = '6 Months';
                                } else if ($row->duration_Id == 3) {
                                    $duration = '1 year';
                                } else if ($row->duration_Id == 4) {
                                    $duration = '3 Months';
                                }

                                echo $duration;
                                ?>
                            </td>
                            <td><?php
                                $startDate = explode('-', $row->startDate);
                                echo $startDate = $startDate[2] . '-' . $startDate[1] . '-' . $startDate[0];
                                ?>
                            </td>
                            <td><?php
                                $endDate = explode('-', $row->endDate);
                                echo $endDate = $endDate[2] . '-' . $endDate[1] . '-' . $endDate[0];
                                ?>
                            </td>
                            <td class="text-center">
                                <button title="Delete Member" onclick="deleteMemberSubsciption('members', <?php echo $row->member_Id; ?>, <?php echo $row->subscription_Id; ?>,'Are you sure to cancel this subscription?')" class="btn btn-xs btn-danger">
                                    <i class="ace-icon fa fa-trash-o bigger-120"></i>
                                </button>
                            </td>
                        </tr>
                    <?php $x++;
                    }
                } else { ?>
                    <tr>
                        <td colspan="4">No Data found.</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
<?php
    }
	
		 //  Controller functions added by R-S starts here 
		public function admin_listing(Request $request)
		{  

			// $check = $this->admin_model->check_model_fun();
				// print_r($check);die;

				// header data pass starts
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
				//  print_r($logo_details->logo);die;

					$output['pageTitle'] = 'Admin';
					$output['logo'] = $get_logo;
					$output['welcome_name'] = $admin_name;
					$output['user_role'] = $user_role;



				// access modules
				$output['access'] = $this->admin_model->getAdminModules($admin_id);

				// header data pass ends here


			// update modules
				if (isset($_GET['moduleId']) && isset($_GET['memberId']) && isset($_GET['type'])) {
					$this->admin_model->updateModule($_GET['moduleId'], $_GET['memberId'], $_GET['type']);
				}

				$output['currentPage'] = 'listing';

				
				// delete member
				if (isset($_GET['did'])) {
					$result = $this->admin_model->deleteAdmin($_GET['did']);
					if ($result > 0) {
						header("location: " . url("admin/listing?delete=success"));
						exit;
					} else {
						header("location: " . url("admin/listing?error=1"));
						exit;
					}
				}
				// edit admin
				if (isset($_GET['edit']) && isset($_GET['aId']) && isset($_GET['name']) && in_array((int) $_GET['role'], array(1, 2))) {
					$result = $this->admin_model->updateAdmin($_GET['aId'], $_GET['name'], $_GET['email'], $_GET['role']);
					if ($result > 0) {
						$arr = array('response' => 1);
						echo json_encode($arr);
						exit;
					} else {
						$arr = array('response' => 0);
						echo json_encode($arr);
						exit;
					}
				}
				// change password
				if (isset($_GET['changePassword']) && isset($_GET['aId']) && isset($_GET['password'])) {
					$result = $this->admin_model->updateAdminPassword($_GET['aId'], $_GET['password']);
					if ($result > 0) {
						$arr = array('response' => 1);
						echo json_encode($arr);
						exit;
					} else {
						$arr = array('response' => 0);
						echo json_encode($arr);
						exit;
					}
				}
				// generate where
				$where = 'where ';
				$whereItems = array();
				$output['searchUsername'] = '';
				$output['searchName'] = '';
				if (isset($_GET['name']) && strlen($_GET['name']) > 0) {
					$output['searchName'] = $_GET['name'];
					$whereItems[] = "admins.name = '" . urlencode($_GET['name']) . "'";
				}
				if (isset($_GET['username']) && strlen($_GET['username']) > 0) {
					$output['searchUsername'] = $_GET['username'];
					$whereItems[] = "admins.uname = '" . $_GET['username'] . "'";
				}
				if (count($whereItems) > 1) {
					$whereString = implode(' AND ', $whereItems);
					$where .= $whereString;
				} else if (count($whereItems) == 1) {
					$where .= $whereItems[0];
				} else {
					$where = '';
				}
				// generate sort
				$sortOrder = "DESC";
				$sortBy = "id";
				$output['sortBy'] = 'added';
				$output['sortOrder'] = 2;
				if (isset($_GET['sortBy']) && strcmp($_GET['sortBy'], 'added') == 0) {
					$sortBy = "id";
					$output['sortBy'] = $_GET['sortBy'];
				} else if (isset($_GET['sortBy']) && strcmp($_GET['sortBy'], 'name') == 0) {
					$sortBy = "name";
					$output['sortBy'] = $_GET['sortBy'];
				} else if (isset($_GET['sortBy']) && strcmp($_GET['sortBy'], 'username') == 0) {
					$sortBy = "uname";
					$output['sortBy'] = $_GET['sortBy'];
				}
				// sort order
				if (isset($_GET['sortOrder']) && $_GET['sortOrder'] == 1) {
					$sortOrder = "ASC";
					$output['sortOrder'] = $_GET['sortOrder'];
				} else if (isset($_GET['sortOrder']) && $_GET['sortOrder'] == 2) {
					$sortOrder = "DESC";
					$output['sortOrder'] = $_GET['sortOrder'];
				}
				$sort = $sortBy . " " . $sortOrder;
				// generate link
				$output['link_string'] = '?';
				if (isset($_GET) && count($_GET) > 0) {
					$link_items = array();
					$link_string = '?';
					$exclude_variables = array("page");
					foreach ($_GET as $key => $value) {
						if (!(in_array($key, $exclude_variables))) {
							$link_items[] = $key . '=' . $value;
						}
					}
					if (count($link_items) > 1) {
						$link_string = implode('&', $link_items);
						$link_string = '?' . $link_string . '&';
					} else if (count($link_items) == 1) {
						$link_string = '?' . $link_items[0] . '&';
					}
					$output['link_string'] = $link_string;
				}
				// pagination
				$limit = 10;
				if (isset($_GET['numRecords'])) {
					$limit = $_GET['numRecords'];
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
				$num_records = $this->admin_model->getNumAdmins($where, $sort);
				$numPages = (int) ($num_records / $limit);
				$reminder = ($num_records % $limit);
				if ($reminder > 0) {
					$numPages = $numPages + 1;
				}
				$output['numPages'] = $numPages;
				$output['start'] = $start;
				$output['currentPageNo'] = $currentPageNo;
				// $output['currentPage'] = 'admins';
				if (isset($_GET['page'])) {
					if ($_GET['page'] > $numPages) {
						header("location: " . $output['currentPage'] . $link_string . "page=" . $numPages);
						exit;
					} else if ($_GET['page'] < 1) {
						header("location: " . $output['currentPage'] . $link_string . "page=1");
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
				$result = $this->admin_model->getAdmins($where, $sort, $start, $limit);
				$output['members'] = $result;
				foreach ($output['members']['data'] as $member) {
					$result = $this->admin_model->getAdminModules($member->id);
					if ($result['numRows'] > 0) {
						$output['modules'][$member->id] = $result['data'];
					}
				}
				if (isset($_GET['delete'])) {
					$output['alertMessage'] = 'Record deleted successfully !';
					$output['alertClass'] = 'alert alert-success';
				} else if (isset($_GET['error'])) {
					$output['alertMessage'] = 'Error occured, please try again !';
					$output['alertClass'] = 'alert alert-danger';
				}
				 	//pArr($output);die();
					return view('/admin/admins', $output);  

		}


		public function add_admin(Request $request)
		{

			// header data pass starts
			$admin_name = Auth::user()->name;
			$user_role = Auth::user()->user_role;
			$admin_id = Auth::user()->id;
			
			$logo_data = array(
				'logo_id' => 1,
				);

			$logo_details = DB::table('website_logo')
			->where($logo_data)
			->first();  
			
			$get_logo = $logo_details->logo;
			//  print_r($logo_details->logo);die;

				$output['pageTitle'] = 'Admin';
				$output['logo'] = $get_logo;
				$output['welcome_name'] = $admin_name;
				$output['user_role'] = $user_role;

				// access modules
			$output['access'] = $this->admin_model->getAdminModules($admin_id);
			
			// header data pass ends here

			if (isset($_POST['submit_addAdmin'])) {
			//   die('here');
				$result = $this->admin_model->addAdmin($_POST);
				if ($result['type'] > 0) {
					if ($result['type'] == 1) {
						setcookie('alertMessage', 'Username already, exists !', 0, "/");
					} else if ($result['type'] == 2) {
						setcookie('alertMessage', 'Email already, exists !', 0, "/");

					}
					setcookie('alertClass', 'alert alert-danger', 0, "/");
					header("location: " . url("admin/add/admin?error=1"));
					exit;

				} else if ($result['type'] == 0 && $result['success'] > 0) {

					setcookie('alertClass', 'alert alert-success', 0, "/");
					setcookie('alertMessage', 'Admin created successfully !', 0, "/");
					header("location: " . url("admin/add/admin?success=1"));
					exit;
				} else if ($result['type'] == 0 && $result['success'] < 1) {

					setcookie('alertClass', 'alert alert-danger', 0, "/");
					setcookie('alertMessage', 'Error occured, please try again !', 0, "/");
					header("location: " . url("admin/add/admin?error=1"));
					exit;
				}
			}
			if (isset($_COOKIE['alertMessage'])) {
				$output['alertMessage'] = $_COOKIE['alertMessage'];
				$output['alertClass'] = $_COOKIE['alertClass'];
				unset($_COOKIE['alertMessage']);
				unset($_COOKIE['alertClass']);
				setcookie('alertMessage', null, -1, '/');
				setcookie('alertClass', null, -1, '/');
			}

			return view('/admin/add_admin', $output);  
		}




		// Label controller functions starts

		public function admin_labels_listing(Request $request)
		{  
			// print_r("here");die;

			// header data pass starts
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
			//  print_r($logo_details->logo);die;

			$output['pageTitle'] = 'Labels';
			$output['logo'] = $get_logo;
			$output['welcome_name'] = $admin_name;
			$output['user_role'] = $user_role;



		// access modules
			$output['access'] = $this->admin_model->getAdminModules($admin_id);

			// header data pass ends here

			// delete member

			if(isset($_GET['did']))

			{

			$result = $this->admin_model->deleteLabel($_GET['did']); 	 

				if ($result > 0) {
					header("location: " . url("admin/labels?delete=success"));
					exit;
				} else {
					header("location: " . url("admin/labels?error=1"));
					exit;
				}

			}  

			// generate where

			$where = 'where ';

			$whereItems = array();

			//$whereItems[] = "admin_id != '0'";

			$output['name'] = '';
			$output['email'] = '';
			$output['client'] = '';

			if (isset($_GET['name']) && strlen($_GET['name']) > 0) {
					$output['name'] = $_GET['name'];
					$whereItems[] = "client_contacts.name LIKE '%" . urlencode($_GET['name']) . "%'";
				}
				
				if (isset($_GET['email']) && strlen($_GET['email']) > 0) {
					$output['email'] = $_GET['email'];
					$whereItems[] = "client_contacts.email LIKE '%" . urlencode($_GET['email']) . "%'";
				}
				
				if (isset($_GET['client']) && strlen($_GET['client']) > 0) {
					$output['client'] = $_GET['client'];
					$whereItems[] = "clients.name LIKE '%" . urlencode($_GET['client']) . "%'";
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

			$sortBy = "id";

			$output['sortBy'] = 'registered';

			$output['sortOrder'] = 2;

			

			if(isset($_GET['sortBy']) && isset($_GET['sortOrder']))

			{

			$output['sortBy'] = $_GET['sortBy'];

			$output['sortOrder'] = $_GET['sortOrder'];

			if(strcmp($_GET['sortBy'],'started')==0)

			{

			$sortBy = "id";

			}

			else if(strcmp($_GET['sortBy'],'subject')==0)

			{

			$sortBy = "subject";

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

			$num_records = $this->admin_model->getNumLabels($where,$sort); 

			$numPages = (int)($num_records/$limit); 

			$reminder = ($num_records%$limit);

		if($reminder>0)

		{

			$numPages = $numPages+1;

		}

		$output['numPages'] = $numPages;

		$output['start'] = $start;

		$output['currentPageNo'] = $currentPageNo;

		$output['currentPage'] = 'labels';

		$excludeParameters = array();

		$getString = array();

		// current page

		if(isset($_GET))  

		{

			foreach($_GET as $key=>$value)

			{

			if(!(in_array($key,$excludeParameters)))

			{

				

			$getString = $key.'='.$value;

			

			}

			} 

			//echo $countString = count($getString);

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

		$output['labels'] =  $this->admin_model->getLabels($where,$sort,$start,$limit); 

			if(isset($_GET['added']))

			{

				$output['alert_message'] = 'Label rep added successfully!';

				$output['alert_class'] = 'alert alert-success';

				

			}

			else if(isset($_GET['error']))

			{

				$output['alert_message'] = 'Error occured, please try again!';

				$output['alert_class'] = 'alert alert-danger';

			}

			else if(isset($_GET['delete']))

			{

				$output['alert_message'] = 'Label deleted successfully!';

				$output['alert_class'] = 'alert alert-success';

			}

			return view('/admin/labels', $output);  

		}


		public function admin_add_labels(Request $request){

		//  dd('ss');

		// header data pass starts
		$admin_name = Auth::user()->name;
		$user_role = Auth::user()->user_role;
		$admin_id = Auth::user()->id;

			$logo_data = array(
				'logo_id' => 1,
				);

			$logo_details = DB::table('website_logo')
			->where($logo_data)
			->first();  
			
			$get_logo = $logo_details->logo;
			$output = array();
		//  print_r($logo_details->logo);die;

			$output['pageTitle'] = 'Add Label';
			$output['logo'] = $get_logo;
			$output['welcome_name'] = $admin_name;
			$output['user_role'] = $user_role;

			// access modules
			$output['access'] = $this->admin_model->getAdminModules($admin_id);

			// header data pass ends here


		if(isset($_POST['addLabel']))
		{

			$result =  $this->admin_model->addLabel($_POST); 

			if($result>0)

			{

			header("location: ".url("admin/add/label?added=1"));   exit;

			}

			else

			{

			header("location: ".url("admin/add/label?error=1"));   exit;

			}

		}

			$output['clients'] =  $this->admin_model->getClients(); 

			if(isset($_GET['added']))

			{

			$output['alert_message'] = 'Label added successfully!';

			$output['alert_class'] = 'alert alert-success';

			

			}

			else if(isset($_GET['error']))

			{

			$output['alert_message'] = 'Error occured, please try again!';

			$output['alert_class'] = 'alert alert-danger';

			}


			return view('/admin/add_labels', $output);  

		}

		// Label controller functions starts

		public function admin_albums_listing(Request $request)
		{  
			//print_r("here");die;

			// header data pass starts
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

			$output['pageTitle'] = 'Albums';
			$output['logo'] = $get_logo;
			$output['welcome_name'] = $admin_name;
			$output['user_role'] = $user_role;

		// access modules
			$output['access'] = $this->admin_model->getAdminModules($admin_id);

			// header data pass ends here

			$output['currentPage'] = 'albums';

			// delete member
			if (isset($_GET['did'])) {
				$result = $this->admin_model->deleteAlbum($_GET['did']);
				if ($result > 0) {
					// header("location: " . $output['currentPage'] . "?delete=success");
					header("location: " . url("admin/albums?delete=success"));
					exit;
				}
				else {
					header("location: " . url("admin/albums?error=1"));
					exit;
				}
			}

			// // choose section
			// if (isset($_GET['choosen']) && isset($_GET['section']) && isset($_GET['trackId']) && isset($_GET['status'])) {
			//     // staff
			//     if ($_GET['section'] == 1) {
			//         if ($_GET['status'] == 1) {
			//             $result = $this->tracksdb->assignStaff($_GET['trackId']);
			//             if ($result > 0) {
			//                 $arr = array('response' => 1, 'message' => 'Track selected for staff section');
			//                 echo json_encode($arr);
			//                 exit;
			//             } else {
			//                 $arr = array('response' => 0, 'message' => 'Error occured, please try again!');
			//                 echo json_encode($arr);
			//                 exit;
			//             }
			//         } else if ($_GET['status'] == 0) {
			//             $result = $this->tracksdb->removeStaff($_GET['trackId']);
			//             if ($result > 0) {
			//                 $arr = array('response' => 1, 'message' => 'Track removed from staff section');
			//                 echo json_encode($arr);
			//                 exit;
			//             } else {
			//                 $arr = array('response' => 0, 'message' => 'Error occured, please try again!');
			//                 echo json_encode($arr);
			//                 exit;
			//             }
			//         }
			//     } else if ($_GET['section'] == 2) {
			//         if ($_GET['status'] == 1) {
			//             $result = $this->tracksdb->assignYou($_GET['trackId']);
			//             if ($result > 0) {
			//                 $arr = array('response' => 1, 'message' => 'Track selected for you section');
			//                 echo json_encode($arr);
			//                 exit;
			//             } else {
			//                 $arr = array('response' => 0, 'message' => 'Error occured, please try again!');
			//                 echo json_encode($arr);
			//                 exit;
			//             }
			//         } else if ($_GET['status'] == 0) {
			//             $result = $this->tracksdb->removeYou($_GET['trackId']);
			//             if ($result > 0) {
			//                 $arr = array('response' => 1, 'message' => 'Track removed from you section');
			//                 echo json_encode($arr);
			//                 exit;
			//             } else {
			//                 $arr = array('response' => 0, 'message' => 'Error occured, please try again!');
			//                 echo json_encode($arr);
			//                 exit;
			//             }
			//         }
			//     }
			// }

			// generate where
			$where = 'WHERE ';
			$whereItems[] = "tracks.deleted = '0'";
			$whereItems[] = "tracks_album.deleted = '0'";
			$output['searchArtist'] = '';
			$output['searchTitle'] = '';
			$output['searchLabel'] = '';
			$output['searchAlbum'] = '';
			$output['searchProducer'] = '';
			$output['searchClient'] = '';
			if (isset($_GET['artist']) && strlen($_GET['artist']) > 0) {
				$output['searchArtist'] = trim($_GET['artist']);
				$whereItems[] = "tracks.artist LIKE '%" . urlencode(trim($_GET['artist'])) . "%'";
			}
			if (isset($_GET['title']) && strlen($_GET['title']) > 0) {
				$output['searchTitle'] = trim($_GET['title']);
				$whereItems[] = "tracks.title = '" . urlencode(trim($_GET['title'])) . "'";
			}
			if (isset($_GET['label']) && strlen($_GET['label']) > 0) {
				$output['searchLabel'] = $_GET['label'];
				$whereItems[] = "tracks.label = '" . urlencode($_GET['label']) . "'";
			}
			if (isset($_GET['album']) && strlen($_GET['album']) > 0) {
				$output['searchAlbum'] = trim($_GET['album']);
				$whereItems[] = "tracks.album LIKE '%" . urlencode(trim($_GET['album'])) . "%'";  
			}
			if (isset($_GET['producer']) && strlen($_GET['producer']) > 0) {
				$output['searchProducer'] = $_GET['producer'];
				$whereItems[] = "tracks.producer = '" . $_GET['producer'] . "'";
			}
			if (isset($_GET['client']) && strlen($_GET['client']) > 0) {
				$output['searchClient'] = trim($_GET['client']);
				$whereItems[] = "clients.uname LIKE '%" . urlencode(trim($_GET['client'])) . "%'";
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
			$sortBy = "tracks.id";
			$output['sortBy'] = 'added';
			$output['sortOrder'] = 2;
			if (isset($_GET['sortBy'])) {
				$output['sortBy'] = $_GET['sortBy'];
				if (strcmp($_GET['sortBy'], 'artist') == 0) {
					$sortBy = "tracks.artist";
				} else if (strcmp($_GET['sortBy'], 'title') == 0) {
					$sortBy = "tracks.title";
				} else if (strcmp($_GET['sortBy'], 'label') == 0) {
					$sortBy = "tracks.label";
				} else if (strcmp($_GET['sortBy'], 'added') == 0) {
					$sortBy = "tracks.id";
				} else if (strcmp($_GET['sortBy'], 'album') == 0) {
					$sortBy = "tracks.album";
				} else if (strcmp($_GET['sortBy'], 'trackLength') == 0) {
					$sortBy = "tracks.time";
				} else if (strcmp($_GET['sortBy'], 'producers') == 0) {
					$sortBy = "tracks.producer";
				} else if (strcmp($_GET['sortBy'], 'client') == 0) {
					$sortBy = "tracks.client";
				} else if (strcmp($_GET['sortBy'], 'paid') == 0) {
					$sortBy = "tracks.paid";
				} else if (strcmp($_GET['sortBy'], 'invoiced') == 0) {
					$sortBy = "tracks.invoiced";
				} else if (strcmp($_GET['sortBy'], 'graphicsCompleted') == 0) {
					$sortBy = "tracks.graphicscomplete";
				}
			}

			// sort order	  		   
			if (isset($_GET['sortOrder']) && $_GET['sortOrder'] == 1) {
				$sortOrder = "ASC";
				$output['sortOrder'] = $_GET['sortOrder'];
			} else  if (isset($_GET['sortOrder']) && $_GET['sortOrder'] == 2) {
				$sortOrder = "DESC";
				$output['sortOrder'] = $_GET['sortOrder'];
			}
			$sort =  $sortBy . " " . $sortOrder;

			// generate link
			$output['link_string'] = '?';
			if (isset($_GET) && count($_GET) > 0) {
				$link_items = array();
				$link_string =  '?';
				$exclude_variables = array("page");
				foreach ($_GET as $key => $value) {
					if (!(in_array($key, $exclude_variables))) {
						$link_items[] = $key . '=' . $value;
					}
				}
				if (count($link_items) > 1) {
					$link_string = implode('&', $link_items);
					$link_string = '?' . $link_string . '&';
				} else if (count($link_items) == 1) {
					$link_string = '?' . $link_items[0] . '&';
				}
				$output['link_string'] = $link_string;
			}

			// pagination
			$limit = 10;
			if (isset($_GET['numRecords'])) {
				$limit = $_GET['numRecords'];
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
			$num_records = $this->admin_model->getNumAlbum($where, $sort);
			$numPages = (int) ($num_records / $limit);
			$reminder = ($num_records % $limit);
			if ($reminder > 0) {
				$numPages = $numPages + 1;
			}
			$output['numPages'] = $numPages;
			$output['start'] = $start;
			$output['currentPageNo'] = $currentPageNo;
			if (isset($_GET['page'])) {
				if ($_GET['page'] > $numPages) {
					header("location: " . $output['currentPage'] . $link_string . "page=" . $numPages);
					exit;
				} else if ($_GET['page'] < 1) {
					header("location: " . $output['currentPage'] . $link_string . "page=1");
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
			$result = $this->admin_model->getAlbums($where, $sort, $start, $limit);
			$output['tracks'] = $result['data'];
			$staffSelection = array();
			$youSelection = array();
			if ($result['numRows'] > 0) {
				foreach ($result['data'] as $track) {
					$staffResult = $this->admin_model->getStaffTracks($track->id);
					if ($staffResult > 0) {
						$staffSelection[] = $track->id;
					}
					$youResult = $this->admin_model->getYouTracks($track->id);
					if ($youResult > 0) {
						$youSelection[] = $track->id;
					}
				}
			}
			$output['youSelection'] =    $youSelection;
			$output['staffSelection'] = $staffSelection;
			if (isset($_GET['delete'])) {
				$output['alert_class'] = 'alert alert-success';
				$output['alert_message'] = 'Record deleted successfully.';
			} else if (isset($_GET['error'])) {
				$output['alert_class'] = 'alert alert-danger';
				$output['alert_message'] = 'Error occured, please try again.';
			}

			return view('/admin/albums', $output);  
		}


 // New Functions
 function admin_albums_edit(Request $request)
 {  
   //die('here');

	   // header data pass starts
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
	   // print_r($logo_details->logo);die;
 
		   $output = array();
 
		   $output['pageTitle'] = 'Albums';
		   $output['logo'] = $get_logo;
		   $output['welcome_name'] = $admin_name;
		   $output['user_role'] = $user_role;
	 
	   // access modules
	   $output['access'] = $this->admin_model->getAdminModules($admin_id);
	   
		// header data pass ends

	 date_default_timezone_set('America/Los_Angeles');
	 $date_time_array = getdate(time());
	 $imgName =    $date_time_array["year"] . $date_time_array["mon"] . $date_time_array["mday"] . $date_time_array["hours"] . $date_time_array["minutes"] . $date_time_array["seconds"];

	 // $this->load->library('upload');
	 if (isset($_POST['updateAlbum']) && !empty($_POST['album']) && !empty($_POST['aid'])) {

		 // echo '<pre/>'; print_r($_POST); exit;

		 $get_res = $this->admin_model->updateAlbum($_POST['aid'], $_POST);
		 

		 // // logos
		 // if (isset($_FILES['logoImage']['name']) && strlen($_FILES['logoImage']['name']) > 4) {
		 //     $logoId =  $this->tracksdb->addLogo($_POST['logoCompany'], $_POST['logoLink'], $_FILES['logoImage']);
		 //     if ($logoId > 0) {
		 //         $config['upload_path']          = './Logos/';
		 //         $config['allowed_types']     = 'gif|jpg|png';
		 //         $config['file_name']         = $logoId . '_' . $_FILES['logoImage']['name'];
		 //         // $ext = explode('.',$_FILES['pageImage']['name']);
		 //         // $count = count($ext);
		 //         // $ext = $ext[$count-1];
		 //         $this->upload->initialize($config);
		 //         if (!$this->upload->do_upload('logoImage')) {
		 //             $error = array('error' => $this->upload->display_errors());
		 //         } else {
		 //             //$data = array('upload_data' => $this->upload->data());
		 //             //$this->tracksdb->addPageImage($result,$config['file_name'].'.'.$ext); 
		 //             $this->tracksdb->addLogoImage($logoId, $logoId . '_' . $_FILES['logoImage']['name']);
		 //         }
		 //     }
		 //     $_POST['logos'][] = $logoId;
		 // }
	   //  dd($_POST);
		 $result = $this->admin_model->updateAlbumTracks($_POST, $_POST['aid']);
		$adding_title_track = $_POST['title'];


		 if ($result > 0) {

			 if (isset($_FILES['pageImage']['name']) && strlen($_FILES['pageImage']['name']) > 4 && empty($adding_title_track[1])) {

				 $tracks = $this->admin_model->getAlbumTracks(' WHERE albumid = '.$_POST['aid']);

				//  foreach($tracks as $track) {

				if (!empty($tracks)) {

						$count_trk = count($tracks);

						for($i =0; $i<1;$i++){    // store image for first track only

						//	dd($tracks[$i]->id);

						
						$pageImage_pi = $request->file('pageImage') ;
   
						//Display File Name
						$filename_pi = $pageImage_pi->getClientOriginalName();
						// echo 'File Name: '.$filename_pi;
					  
						
						//Display File Extension
						$file_extension_pi = $pageImage_pi->getClientOriginalExtension();
						// echo 'File Extension: '.$file_extension_pi;
					  
						
						//Display File Real Path
						$real_path_pi = $pageImage_pi->getRealPath();
						// echo 'File Real Path: '.$real_path_pi;
					  
						
						//Display File Size
					   // $file_size_pi = $pageImage_pi->getSize();
						// echo 'File Size: '.$file_size_pi;
					  
						
						//Display File Mime Type
					   // $file_mime_type_pi = $pageImage_pi->getMimeType();
						// echo 'File Mime Type: '.$file_mime_type_pi;
					  
						$destination_path_pi = base_path('ImagesUp/');
					   // die($destination_path_pi);
	
						//Display Destination Path
						if(empty($destination_path_pi)){
						  $destination_path_pi = public_path('uploads/');
						} else {
						  $destination_path_pi = $destination_path_pi;
						}
	
						// // echo 'File Destination Path: '.$destination_path_pi;
						// if(!File::isDirectory($destination_path_pi)) {
						//     File::makeDirectory($destination_path_pi, 0777, true, true);
						// }
	
						$image_name_pi = $tracks[$i]->id . '_pi' . $imgName . '.'.$file_extension_pi;
						// $image_name_pi = preg_replace('/[^a-zA-Z0-9_.]/', '_', $image_name_pi);
	
						$uploaded_data = $pageImage_pi->move( $destination_path_pi , $image_name_pi );
                          
                          
                          $folder= '13187487324';
                         $metadata = $this->uploadImage_to_pcloud($destination_path_pi, $image_name_pi, $folder);
                
                    	$pcloudFileId = $metadata->metadata->fileid;
                    	$parentfolderid = $metadata->metadata->parentfolderid;
                    	@unlink($destination_path_pi.$image_name_pi);
              
                          
                          
                          
                          
						if( !empty( $uploaded_data )){
						 // die('file');
 
						  $this->admin_model->updatePageImage_admin($tracks[$i]->id, $image_name_pi,$pcloudFileId,$parentfolderid);
						  $this->admin_model->updatePageImage_in_album_admin($tracks[$i]->albumid, $image_name_pi,$pcloudFileId,$parentfolderid);
 
						}
 
						else{
 
						  header("location: " . url("admin/album/edit/?aid=" . $_GET['aid'] . "&error=1"));
						}

					}


				}
		 
				//  } // end of foreach
			   
			   }


								 
			   // may be remove in future 

			   // email image upload
			   if (isset($_FILES['emailImage']['name']) && strlen($_FILES['emailImage']['name']) > 4 && empty($adding_title_track[1])) {

				 $tracks_em = $this->admin_model->getAlbumTracks(' WHERE albumid = '.$_POST['aid']);

				//  foreach($tracks_em as $track) {

					if (!empty($tracks_em)) {

						$count_trk = count($tracks_em);

						for($i =0; $i<1;$i++){    // store image for first track only

						//	dd($tracks_em[$i]->id);

							$emailImage_ei = $request->file('emailImage') ;

							//Display File Name
							$filename_ei = $emailImage_ei->getClientOriginalName();
							// echo 'File Name: '.$filename_ei;
						
							
							//Display File Extension
							$file_extension_ei = $emailImage_ei->getClientOriginalExtension();
							// echo 'File Extension: '.$file_extension_ei;
						
							
							//Display File Real Path
							$real_path_ei = $emailImage_ei->getRealPath();
							// echo 'File Real Path: '.$real_path_ei;
						
							
							//Display File Size
						// $file_size_ei = $emailImage_ei->getSize();
							// echo 'File Size: '.$file_size_ei;
						
							
							//Display File Mime Type
							// $file_mime_type_ei = $emailImage_ei->getMimeType();
							// echo 'File Mime Type: '.$file_mime_type_ei;
						
							$destination_path_ei = base_path('ImagesUp/');
							// die($destination_path_ei);

							//Display Destination Path
							if(empty($destination_path_ei)){
							$destination_path_ei = public_path('uploads/');
							} else {
							$destination_path_ei = $destination_path_ei;
							}

							// // echo 'File Destination Path: '.$destination_path_ei;
							// if(!File::isDirectory($destination_path_ei)) {
							//     File::makeDirectory($destination_path_ei, 0777, true, true);
							// }

							$image_name_ei = $tracks_em[$i]->id . '_' . $imgName . '.'.$file_extension_ei;
							// $image_name_ei = preg_replace('/[^a-zA-Z0-9_.]/', '_', $image_name_ei);

							$uploaded_data = $emailImage_ei->move( $destination_path_ei , $image_name_ei );

							if( !empty( $uploaded_data )){
							// die('file');
							$this->admin_model->addEmailImage($tracks_em[$i]->id, $image_name_ei);

							}

							else{

							header("location: " . url("admin/album/edit/?aid=" . $_GET['aid'] . "&error=1"));
							}

							// $config['upload_path']          = './ImagesUp/';
							// $config['allowed_types']        = 'gif|jpg|png';
							// //  $config['max_size']             = 100;
							// //  $config['max_width']            = 1024;
							// //   $config['max_height']           = 768;
							// $config['file_name']           = $trackId . '_' . $imgName;
							// $ext = explode('.', $_FILES['emailImage']['name']);
							// $count = count($ext);
							// $ext = $ext[$count - 1];
							// //  $this->load->library('upload', $config);
							// $this->upload->initialize($config);
							// if (!$this->upload->do_upload('emailImage')) {
							//     $error = array('error' => $this->upload->display_errors());
							//     //    $this->load->view('upload_form', $error);
							// } else {
							//     $data = array('upload_data' => $this->upload->data());
							//     $this->admin_model->addEmailImage($trackId, $config['file_name'] . '.' . $ext);
							//     //  $this->load->view('Client_submit_track', $data);
							// }
						// }

					 	}
					}

				}


			 if(!empty($_FILES['tvAudio']) && $_POST['tvTrackId']) {
				 // audio files upload
				 $preview = 0;
			   
				 foreach ($_FILES['tvAudio']['name'] as $i => $audio) {
					 if (isset($audio) && strlen($audio) > 4 && (int) $_POST['tvTrackId'][$i] > 0) {

							$tvAudio = $request->file('tvAudio') ;
							$tvAudio_single = $tvAudio[$i];
							 $destination_path = base_path('AUDIO/');

							 $audio_ext = $tvAudio_single->getClientOriginalExtension();
							
							 if($audio_ext != 'mp3'){
							   
								 header("location: " . url("admin/album/edit/?aid=" . $_GET['aid'] . "&notvaudio=1"));
								 exit;

							 }
		 
							 //Display Destination Path
							 if(empty($destination_path)){
								 $destination_path = public_path('uploads/');
							 } else {
								 $destination_path = $destination_path;
							 }

							   $fileName = md5(rand(1000, 10000));
		 
								// Define new $_FILES array - $_FILES['file']
								 $_FILES['file']['name'] = $_FILES['tvAudio']['name'][$i];
								 $_FILES['file']['type'] = $_FILES['tvAudio']['type'][$i];
								 $_FILES['file']['tmp_name'] = $_FILES['tvAudio']['tmp_name'][$i];
								 $_FILES['file']['error'] = $_FILES['tvAudio']['error'][$i];
								 $_FILES['file']['size'] = $_FILES['tvAudio']['size'][$i];

								 $file_name = $_POST['tvTrackId'][$i] . '_' . $fileName . '.mp3';

								 $uploaded_data = $tvAudio_single->move( $destination_path , $file_name );
							 

								 if( !empty( $uploaded_data )){
									 $version = $_POST['tvVersion'][$i];
									 if (strlen($_POST['tvVersion'][$i]) < 3) {
										 $version = $_POST['tvOtherVersion'][$i];
									 }
									 $this->admin_model->addMp3_album($_POST['tvTrackId'][$i], $file_name, $version, $preview);

								 }

								 else{

									 header("location: " . url("admin/album/edit/?aid=" . $_GET['aid'] . "&error=1"));
								 }
				 


								 // $config['upload_path']          = './AUDIO/';
								 // $config['allowed_types']        = 'mp3';
								 // $config['max_size']             = 9999999999999;
								 // $fileName = md5(rand(1000, 10000));
								 // $config['file_name']           = $_POST['tvTrackId'][$i] . '_' . $fileName . '.mp3';
								 // $ext = explode('.', $audio);
								 // $count = count($ext);
								 // $ext = $ext[$count - 1];
								 // $this->upload->initialize($config);

								 // // Define new $_FILES array - $_FILES['file']
								 // $_FILES['file']['name'] = $_FILES['tvAudio']['name'][$i];
								 // $_FILES['file']['type'] = $_FILES['tvAudio']['type'][$i];
								 // $_FILES['file']['tmp_name'] = $_FILES['tvAudio']['tmp_name'][$i];
								 // $_FILES['file']['error'] = $_FILES['tvAudio']['error'][$i];
								 // $_FILES['file']['size'] = $_FILES['tvAudio']['size'][$i];

								 // if (!$this->upload->do_upload('file')) {
								 //     $error = array('error' => $this->upload->display_errors());
								 // } else {
								 //     $data = array('upload_data' => $this->upload->data());
								 //     $version = $_POST['version'][$i][$j];
								 //     if (strlen($_POST['version'][$i][$j]) < 3) {
								 //         $version = $_POST['otherVersion'][$i][$j];
								 //     }
								 //     $this->admin_model->addMp3_album($_POST['tvTrackId'][$i], $config['file_name'], $version, $preview);
								 // }

						 
					   // }   
				   }
					 
				 }
			 }


			 foreach ($_POST['title'] as $i => $title) {

				 if (!empty($title)) {

					 $trackId = $this->admin_model->addTrack($i, $_POST['aid'], $_POST);

					 if ($trackId > 0) {

					   //  $_COOKIE['album_id'] = $trackId;
						 setcookie('album_id', $trackId, 0, "/");

						 $date_time_array = getdate(time());
						 $imgName = $date_time_array["year"] . $date_time_array["mon"] . $date_time_array["mday"] . $date_time_array["hours"] . $date_time_array["minutes"] . $date_time_array["seconds"];

						 // email image upload
						 if (isset($_FILES['emailImage']['name']) && strlen($_FILES['emailImage']['name']) > 4) {

						   $emailImage = $request->file('emailImage') ;
   
						   //Display File Name
						   $filename = $emailImage->getClientOriginalName();
						   // echo 'File Name: '.$filename;
						 
						   
						   //Display File Extension
						   $file_extension = $emailImage->getClientOriginalExtension();
						   // echo 'File Extension: '.$file_extension;
						 
						   
						   //Display File Real Path
						   $real_path = $emailImage->getRealPath();
						   // echo 'File Real Path: '.$real_path;
						 
						   
						   //Display File Size
						 //  $file_size = $emailImage->getSize();
						   // echo 'File Size: '.$file_size;
						 
						   
						   //Display File Mime Type
						//   $file_mime_type = $emailImage->getMimeType();
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
	   
						   $image_name = $trackId . '_' . $imgName .'.'. $file_extension;
						   // $image_name = preg_replace('/[^a-zA-Z0-9_.]/', '_', $image_name);
	   
						   $uploaded_data = $emailImage->move( $destination_path , $image_name );
 
						   if( !empty( $uploaded_data )){
							// die('file');
							 $this->admin_model->addEmailImage($trackId, $image_name);
 
						   }
 
						   else{
 
							 header("location: " . url("admin/album/edit/?aid=" . $_GET['aid'] . "&error=1"));
						   }

							 // $config['upload_path']          = './ImagesUp/';
							 // $config['allowed_types']        = 'gif|jpg|png';
							 // //  $config['max_size']             = 100;
							 // //  $config['max_width']            = 1024;
							 // //   $config['max_height']           = 768;
							 // $config['file_name']           = $trackId . '_' . $imgName;
							 // $ext = explode('.', $_FILES['emailImage']['name']);
							 // $count = count($ext);
							 // $ext = $ext[$count - 1];
							 // //  $this->load->library('upload', $config);
							 // $this->upload->initialize($config);
							 // if (!$this->upload->do_upload('emailImage')) {
							 //     $error = array('error' => $this->upload->display_errors());
							 //     //    $this->load->view('upload_form', $error);
							 // } else {
							 //     $data = array('upload_data' => $this->upload->data());
							 //     $this->admin_model->addEmailImage($trackId, $config['file_name'] . '.' . $ext);
							 //     //  $this->load->view('Client_submit_track', $data);
							 // }
						 }

						 // pg image upload
						 if (isset($_FILES['pageImage']['name']) && strlen($_FILES['pageImage']['name']) > 4) {

						   $pageImage = $request->file('pageImage') ;
   
						   //Display File Name
						   $filename = $pageImage->getClientOriginalName();
						   // echo 'File Name: '.$filename;
						 
						   
						   //Display File Extension
						   $file_extension = $pageImage->getClientOriginalExtension();
						   // echo 'File Extension: '.$file_extension;
						 
						   
						   //Display File Real Path
						   $real_path = $pageImage->getRealPath();
						   // echo 'File Real Path: '.$real_path;
						 
						   
						   //Display File Size
						  // $file_size = $pageImage->getSize();
						   // echo 'File Size: '.$file_size;
						 
						   
						   //Display File Mime Type
						 //  $file_mime_type = $pageImage->getMimeType();
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
	   
						   $image_name = $trackId . '_' . $imgName .'.'. $file_extension;
						   // $image_name = preg_replace('/[^a-zA-Z0-9_.]/', '_', $image_name);
	   
						   $uploaded_data = $pageImage->move( $destination_path , $image_name );
						   
						   
						    $folder= '13187487324';
                            $metadata = $this->uploadImage_to_pcloud($destination_path, $image_name, $folder);
                        
                        	$pcloudFileId = $metadata->metadata->fileid;
                        	$parentfolderid = $metadata->metadata->parentfolderid;
                        	@unlink($destination_path.$image_name);
              
                          
                          
                          
                          
					
						   
						   
 
						   if( !empty( $uploaded_data )){
							// die('file');
 
							 $this->admin_model->addPageImage_admin($trackId, $image_name,$pcloudFileId,$parentfolderid);
 
						   }
 
						   else{
 
							 header("location: " . url("admin/album/edit/?aid=" . $_GET['aid'] . "&error=1"));
						   }

							 // $config['upload_path']          = './ImagesUp/';
							 // $config['allowed_types']        = 'gif|jpg|png|jpeg|svg|tiff';
							 // $config['file_name']           = $trackId . '_' . $imgName . 'pg';
							 // $ext = explode('.', $_FILES['pageImage']['name']);
							 // $count = count($ext);
							 // $ext = $ext[$count - 1];
							 // //  $this->load->library('upload', $config);
							 // $this->upload->initialize($config);
							 // if (!$this->upload->do_upload('pageImage')) {
							 //     $error = array('error' => $this->upload->display_errors());
							 //     //    $this->load->view('upload_form', $error);
							 // } else {
							 //     $data = array('upload_data' => $this->upload->data());
							 //     $this->admin_model->addPageImage($trackId, $config['file_name'] . '.' . $ext);
							 //     //  $this->load->view('Client_submit_track', $data);
							 // }
						 }

						 // audio files upload
						 $preview = 1;
						 //$fileCount = $_POST['divId'];
						 foreach ($_FILES['audio']['name'][$i] as $j => $audio) {
							 if ($i > 1) {
								 $preview = 0;
							 }

							 if (isset($_FILES['audio']['name'][$i][$j]) && strlen($_FILES['audio']['name'][$i][$j]) > 4) {
							 
							  $audio = $request->file('audio') ;
							  $audio_single = $audio[$i][$j];
							 
							   
							 //  for($i=1; $i<=$fileCount; $i++){
								
								if(!empty($audio_single)){ // Check if File is added	
									
									$fileAudio = $audio_single;
									$audio_name = $fileAudio->getClientOriginalName(); 
									
									$filename = pathinfo($audio_name,PATHINFO_FILENAME);
									$audio_ext = $fileAudio->getClientOriginalExtension();


									if($audio_ext != 'mp3'){
							   
									 header("location: " . url("admin/album/edit/?aid=" . $_GET['aid'] . "&noaudio=1"));
									 exit;
 
								   }

									//$fileNameToStore = $filename.'-'.time().'.'.$audio_ext;
									$date_time_array = getdate(time());
									$fileNameDynamic =	$date_time_array["year"].$date_time_array["mon"].$date_time_array["mday"].$date_time_array["hours"].$date_time_array["minutes"].$date_time_array["seconds"].'.'.$audio_ext;					   
									$destinationPath =  base_path('AUDIO/');
									//dd($destinationPath);
									$fileName = md5(rand(1000,10000));
									  //$fileNameToStore = $fileName.'_'.$fileNameDynamic;
									  $fileNameToStore = $trackId.'_'.$fileName.'.mp3';

									$uploaded_data = $fileAudio->move($destinationPath,$fileNameToStore);
									//dd($uploaded_data);

									$version = $_POST['version'][$i][$j];
									if (strlen($_POST['version'][$i][$j]) < 3) {
										$version = $_POST['otherVersion'][$i][$j];
									}
									if( !empty( $uploaded_data )){

											//    $_FILES['file']['name'] = $_FILES['audio']['name'][$i][$j];
											//   $_FILES['file']['tmp_name'] = $_FILES['audio']['tmp_name'][$i][$j];
										  	//   $filename = $_FILES['file']['name'];
							                //   $filepath = $_FILES['file']['tmp_name'];
											//  $metadata = $this->add_to_pcloud($filepath,$fileNameToStore);

											$metadata = $this->add_to_pcloud($destinationPath,$fileNameToStore);
										//	dd($metadata);

										 $this->admin_model->addMp3_album($trackId, $metadata->metadata->fileid, $version, $preview);
									}
									else{

									   header("location: " . url("admin/album/edit/?aid=" . $_GET['aid'] . "&error=1"));

									}
												   
							   }	
							   
							  // }


							   //   $config['upload_path']          = './AUDIO/';
							   //   $config['allowed_types']        = 'mp3';
							   //   $config['max_size']             = 9999999999999;
							   //   $fileName = md5(rand(1000, 10000));
							   //   $config['file_name']           = $trackId . '_' . $fileName . '.mp3';
							   //   $ext = explode('.', $_FILES['audio']['name'][$i][$j]);
							   //   $count = count($ext);
							   //   $ext = $ext[$count - 1];
							   //   $this->upload->initialize($config);

							   //   // Define new $_FILES array - $_FILES['file']
							   //   $_FILES['file']['name'] = $_FILES['audio']['name'][$i][$j];
							   //   $_FILES['file']['type'] = $_FILES['audio']['type'][$i][$j];
							   //   $_FILES['file']['tmp_name'] = $_FILES['audio']['tmp_name'][$i][$j];
							   //   $_FILES['file']['error'] = $_FILES['audio']['error'][$i][$j];
							   //   $_FILES['file']['size'] = $_FILES['audio']['size'][$i][$j];
					 
							   //   $filename = $_FILES['file']['name'];
							   //   $filepath = $_FILES['file']['tmp_name'];
								 
								 // $metadata = $this->add_to_pcloud($filepath,$filename);

							   //   // if (!$this->upload->do_upload('file')) {
							   //       // $error = array('error' => $this->upload->display_errors());
							   //   // } else {
							   //       // $data = array('upload_data' => $this->upload->data());
							   //   $version = $_POST['version'][$i][$j];
							   //   if (strlen($_POST['version'][$i][$j]) < 3) {
							   //       $version = $_POST['otherVersion'][$i][$j];
							   //   }
							   //   $this->admin_model->addMp3_album($trackId, $metadata->metadata->fileid, $version, $preview);
								 // }
							 }
						 }
					 }
				 }
			 }
			 header("location: " . url("admin/album/edit/?aid=" . $_GET['aid'] . "&update=1"));
			 exit;
		 } else {
			 header("location: " . url("admin/album/edit/?aid=" . $_GET['aid'] . "&error=1"));
			 exit;
		 }
	 }

	 if(isset($_GET['delTrackId'])) {
		 echo $this->admin_model->deleteTrack($_GET['delTrackId']); exit;
	 }
	 
	 if(isset($_GET['delTrackVersionId'])) {
		 echo $this->admin_model->deleteTrackVersion($_GET['delTrackVersionId']); exit;
	 }

	 $where = "where tracks.albumid = '" . $_GET['aid'] . "'";
	 $output['tracks'] = $this->admin_model->getTrack($where);
  //  dd($output['tracks']['data'][0]);
	 $output['logos'] = $this->admin_model->getLogos_album("");
	 //	print_r($output['logos']);
	 // $output['getTrackReps'] = $this->tracksdb->getTrackReps($_GET['tid'], $output['tracks']['data'][0]->client);
	 // $output['trackReps'] = array();
	 // if ($output['getTrackReps']['numRows'] > 0) {
	 //     $i = 0;
	 //     foreach ($output['getTrackReps']['data'] as $rep) {
	 //         $output['trackReps'][$i]['name'] = $rep->name;
	 //         $output['trackReps'][$i]['email'] = $rep->email;
	 //         $i++;
	 //     }
	 // }
	 $clients = $this->admin_model->getClientsList();
	 $output['clients'] = $clients['data'];

	 if (isset($_GET['success'])) {
		 $output['alert_class'] = 'alert alert-success';
		 $output['alert_message'] = 'Album added successfully !';
	 }
	 if (isset($_GET['update'])) {
		 $output['alert_class'] = 'alert alert-success';
		 $output['alert_message'] = 'Album updated successfully.';
	 } else if (isset($_GET['error'])) {
		
			 $output['alert_class'] = 'alert alert-danger';
			 $output['alert_message'] = 'Error occured, please try again.';
	 }

	 else if (isset($_GET['notvaudio'])) {

		 $output['alert_class'] = 'alert alert-danger';
		 $output['alert_message'] = 'File not accepted. Not an audio file'; 
	   
	 }

	 else if (isset($_GET['noaudio'])) {

		 $output['alert_class'] = 'alert alert-danger';
		 $output['alert_message'] = 'File not accepted. Not an audio file'; 
	   
	 }

	 if (!empty($output['tracks']['data'])) {
		 foreach ($output['tracks']['data'] as $i => $track) {
			 $output['tracks']['data'][$i]->audios = $this->admin_model->getTrackMp3s($track->id);
		 }
	 }
	 $output['genres'] = $this->admin_model->getGenres_album();
	 $output['subGenres'] = !empty($output['tracks']['data']) ? $this->admin_model->getSubGenres($output['tracks']['data'][0]->genreId) : array();
	 // echo '<pre/>'; print_r($output['tracks']); exit; 

   //  dd($output);
   //  return view('admin/includes/header', $output); 
	 return view('/admin/album_edit', $output);  
 }


 function admin_add_album(Request $request)
 {
	
	 //die('here');

	  // header data pass starts
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
	  // print_r($logo_details->logo);die;

		  $output = array();

		  $output['pageTitle'] = 'Add Album';
		  $output['logo'] = $get_logo;
		  $output['welcome_name'] = $admin_name;
		  $output['user_role'] = $user_role;
	
	  // access modules
	  $output['access'] = $this->admin_model->getAdminModules($admin_id);

	  $output['tracks']['data'][0] = '';
	  $output['subGenres']['numRows'] = '';
	  $output['tracks']['logos'] = '';
	  
	   // header data pass ends

	 // search logos
	 if (isset($_GET['listBy']) && isset($_GET['orderBy']) && isset($_GET['searchText'])) {
		 if ($_GET['listBy'] == 1 && $_GET['orderBy'] == 1) {
			 $where = " order by company asc ";
		 } else if ($_GET['listBy'] == 1 && $_GET['orderBy'] == 2) {
			 $where = " order by company desc ";
		 } else if ($_GET['listBy'] == 2 && $_GET['orderBy'] == 1) {
			 $where = " order by id asc ";
		 } else if ($_GET['listBy'] == 2 && $_GET['orderBy'] == 2) {
			 $where = " order by id desc ";
		 } else if ($_GET['listBy'] == 3) {
			 $where = " where company like '" . $_GET['searchText'] . "%' order by company asc ";
		 }
		 $ajaxOutput['logos'] = $this->admin_model->getLogos_album($where);
		 if ($ajaxOutput['logos']['numRows'] > 0) {
			 foreach ($ajaxOutput['logos']['data'] as $logo) {
				 echo '<option value="' . $logo->id . '">' . urldecode($logo->company) . '</option>';
			 }
		 } else {
			 echo '<option value="">No data found.</option>';
		 }
		 exit;
	 }
   //  $this->load->library('upload');
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
	 // get search tracks
	 if (isset($_GET['searchKey']) && isset($_GET['clientSearch'])) {
		 $searchWhere =  "WHERE name  REGEXP '^[^a-z0-9]'";
		 if (strlen($_GET['searchKey']) > 0) {
			 $searchWhere = "WHERE name like '" . $_GET['searchKey'] . "%'";
		 }
		 $result =  $this->admin_model->getSearchClients($searchWhere);
		 if ($result['numRows'] > 0) {
			 foreach ($result['data'] as $data) {
				 $arr[] = array('id' => $data->id, 'name' => urldecode($data->name));
			 }
			 echo json_encode($arr);
		 }
		 exit;
	 }
	 if (isset($_POST['saveAlbum']) && !empty($_POST['title'])) {
		 // echo '<pre/>'; print_r($_POST); print_r($_FILES); exit;
		 $albumId = $this->admin_model->addAlbum($_POST);
		 // echo 'er'; exit;
		 if ($albumId > 0) {
		   //  $_COOKIE['album_id'] = $albumId;
		   setcookie('album_id', $albumId, 0, "/");

			 foreach ($_POST['title'] as $i => $title) {

				 $trackId = $this->admin_model->addTrack($i, $albumId, $_POST);

				 if ($trackId > 0) {

					// $_COOKIE['album_id'] = $trackId;
					setcookie('album_id', $trackId, 0, "/");

					 $date_time_array = getdate(time());
					 $imgName = $date_time_array["year"] . $date_time_array["mon"] . $date_time_array["mday"] . $date_time_array["hours"] . $date_time_array["minutes"] . $date_time_array["seconds"];

					 // email image upload
					 if (isset($_FILES['emailImage']['name']) && strlen($_FILES['emailImage']['name']) > 4) {


					   $emailImage = $request->file('emailImage') ;
   
					   //Display File Name
					   $filename = $emailImage->getClientOriginalName();
					   // echo 'File Name: '.$filename;
					 
					   
					   //Display File Extension
					   $file_extension = $emailImage->getClientOriginalExtension();
					   // echo 'File Extension: '.$file_extension;
					 
					   
					   //Display File Real Path
					   $real_path = $emailImage->getRealPath();
					   // echo 'File Real Path: '.$real_path;
					 
					   
					   //Display File Size
					   $file_size = $emailImage->getSize();
					   // echo 'File Size: '.$file_size;
					 
					   
					   //Display File Mime Type
					   $file_mime_type = $emailImage->getMimeType();
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
   
					   $image_name = $trackId . '_' . $imgName .'.'. $file_extension;
					   // $image_name = preg_replace('/[^a-zA-Z0-9_.]/', '_', $image_name);
   
					   $uploaded_data = $emailImage->move( $destination_path , $image_name );

					   if( !empty( $uploaded_data )){
						// die('file');
						 $this->admin_model->addEmailImage($trackId, $image_name);

					   }

					   else{

						 header("location: " . url("admin/album/edit/?aid=" . $_GET['aid'] . "&error=1"));
					   }

						 // $config['upload_path']          = './ImagesUp/';
						 // $config['allowed_types']        = 'gif|jpg|png';
						 // //  $config['max_size']             = 100;
						 // //  $config['max_width']            = 1024;
						 // //   $config['max_height']           = 768;
						 // $config['file_name']           = $trackId . '_' . $imgName;
						 // $ext = explode('.', $_FILES['emailImage']['name']);
						 // $count = count($ext);
						 // $ext = $ext[$count - 1];
						 // //  $this->load->library('upload', $config);
						 // $this->upload->initialize($config);
						 // if (!$this->upload->do_upload('emailImage')) {
						 //     $error = array('error' => $this->upload->display_errors());
						 //     //    $this->load->view('upload_form', $error);
						 // } else {
						 //     $data = array('upload_data' => $this->upload->data());
						 //     $this->admin_model->addEmailImage($trackId, $config['file_name'] . '.' . $ext);
						 //     //  $this->load->view('Client_submit_track', $data);
						 // }
					 }

					 // pg image upload
					 if (isset($_FILES['pageImage']['name']) && strlen($_FILES['pageImage']['name']) > 4) {

					   $pageImage = $request->file('pageImage') ;
   
					   //Display File Name
					   $filename = $pageImage->getClientOriginalName();
					   // echo 'File Name: '.$filename;
					 
					   
					   //Display File Extension
					   $file_extension = $pageImage->getClientOriginalExtension();
					   // echo 'File Extension: '.$file_extension;
					 
					   
					   //Display File Real Path
					   $real_path = $pageImage->getRealPath();
					   // echo 'File Real Path: '.$real_path;
					 
					   
					   //Display File Size
					   $file_size = $pageImage->getSize();
					   // echo 'File Size: '.$file_size;
					 
					   
					   //Display File Mime Type
					   $file_mime_type = $pageImage->getMimeType();
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
   
					   $image_name = $trackId . '_' . $imgName . '.'.$file_extension;
					   
					   
					  
					   // $image_name = preg_replace('/[^a-zA-Z0-9_.]/', '_', $image_name);
   
					   $uploaded_data = $pageImage->move( $destination_path , $image_name );
					   
					    $folder= '13187487324';
                        $metadata = $this->uploadImage_to_pcloud($destination_path, $image_name, $folder);
                
                    	$pcloudFileId = $metadata->metadata->fileid;
                    	$parentfolderid = $metadata->metadata->parentfolderid;
                    	@unlink($destination_path.$image_name);
             
					   
					   
					   
					   
					   

					   if( !empty( $uploaded_data )){
						// die('file');

						 $this->admin_model->addPageImage_admin($trackId, $image_name,$pcloudFileId, $parentfolderid);
						 $this->admin_model->updatePageImage_in_album_admin($albumId, $image_name,$pcloudFileId, $parentfolderid);

					   }

					   else{

						 header("location: " . url("admin/album/edit/?aid=" . $_GET['aid'] . "&error=1"));
					   }

						 // $config['upload_path']          = './ImagesUp/';
						 // $config['allowed_types']        = 'gif|jpg|png|jpeg|svg|tiff';
						 // $config['file_name']           = $trackId . '_' . $imgName . 'pg';
						 // $ext = explode('.', $_FILES['pageImage']['name']);
						 // $count = count($ext);
						 // $ext = $ext[$count - 1];
						 // //  $this->load->library('upload', $config);
						 // $this->upload->initialize($config);
						 // if (!$this->upload->do_upload('pageImage')) {
						 //     $error = array('error' => $this->upload->display_errors());
						 //     //    $this->load->view('upload_form', $error);
						 // } else {
						 //     $data = array('upload_data' => $this->upload->data());
						 //     $this->admin_model->addPageImage($trackId, $config['file_name'] . '.' . $ext);
						 //     //  $this->load->view('Client_submit_track', $data);
						 // }
					 }

					 // audio files upload
					 $preview = 1;
					 foreach ($_FILES['audio']['name'][$i] as $j => $audio) {
						 if ($i > 1) {
							 $preview = 0;
						 }
						 if (isset($_FILES['audio']['name'][$i][$j]) && strlen($_FILES['audio']['name'][$i][$j]) > 4) {

							 $audio = $request->file('audio') ;
							 $audio_single = $audio[$i][$j];
						   // $audio = $request->file('audio') ;
							
						  //  for($i=1; $i<=$fileCount; $i++){
							 
							 if(!empty($audio_single)){ // Check if File is added	
								 
								 $fileAudio = $audio_single ;
								 $audio_name = $fileAudio->getClientOriginalName(); 
								 
								 $filename = pathinfo($audio_name,PATHINFO_FILENAME);
								 $audio_ext = $fileAudio->getClientOriginalExtension();

								 if($audio_ext != 'mp3'){
							   
									 header("location: " . url("admin/add/album?noaudio=1"));
									 exit;
 
								   }

								 
								 //$fileNameToStore = $filename.'-'.time().'.'.$audio_ext;
								 $date_time_array = getdate(time());
								 $fileNameDynamic =	$date_time_array["year"].$date_time_array["mon"].$date_time_array["mday"].$date_time_array["hours"].$date_time_array["minutes"].$date_time_array["seconds"].'.'.$audio_ext;					   
								 $destinationPath =  base_path('AUDIO/');
								 $fileName = md5(rand(1000,10000));
								 $fileNameToStore = $trackId.'_'.$fileName.'.mp3';

								 $uploaded_data = $fileAudio->move($destinationPath,$fileNameToStore);

								 $version = $_POST['version'][$i][$j];
								 if (strlen($_POST['version'][$i][$j]) < 3) {
									 $version = $_POST['otherVersion'][$i][$j];
								 }
								 if( !empty( $uploaded_data )){

									  $metadata = $this->add_to_pcloud($destinationPath,$fileNameToStore);

									  $this->admin_model->addMp3_album($trackId, $metadata->metadata->fileid, $version, $preview);
								 }
								 else{

									 header("location: " . url("admin/add/album?error=1"));

								 }
										
							}	
							
							$i++;


						   //   $config['upload_path']          = './AUDIO/';
						   //   $config['allowed_types']        = 'mp3';
						   //   $config['max_size']             = 9999999999999;
						   //   $fileName = md5(rand(1000, 10000));
						   //   $config['file_name']           = $trackId . '_' . $fileName . '.mp3';
						   //   $ext = explode('.', $_FILES['audio']['name'][$i][$j]);
						   //   $count = count($ext);
						   //   $ext = $ext[$count - 1];
						   //   $this->upload->initialize($config);

						   //   // Define new $_FILES array - $_FILES['file']
						   //   $_FILES['file']['name'] = $_FILES['audio']['name'][$i][$j];
						   //   $_FILES['file']['type'] = $_FILES['audio']['type'][$i][$j];
						   //   $_FILES['file']['tmp_name'] = $_FILES['audio']['tmp_name'][$i][$j];
						   //   $_FILES['file']['error'] = $_FILES['audio']['error'][$i][$j];
						   //   $_FILES['file']['size'] = $_FILES['audio']['size'][$i][$j];
					 
						   //   $filename = $_FILES['file']['name'];
						   //   $filepath = $_FILES['file']['tmp_name'];
							 
							 // $metadata = $this->add_to_pcloud($filepath,$filename);

						   //   // if (!$this->upload->do_upload('file')) {
						   //       // $error = array('error' => $this->upload->display_errors());
						   //   // } else {
						   //       // $data = array('upload_data' => $this->upload->data());
						   //   $version = $_POST['version'][$i][$j];
						   //   if (strlen($_POST['version'][$i][$j]) < 3) {
						   //       $version = $_POST['otherVersion'][$i][$j];
						   //   }
						   //   $this->admin_model->addMp3_album($trackId, $metadata->metadata->fileid, $version, $preview);
							 // }
						 }
					 }
				 }
			 }
			 setcookie('album_id', null, -1, '/');
			 header("location: " . url("admin/album/edit?aid=" . $albumId . "&success=1"));
			 exit;
		 } else {
			 header("location: " . url("admin/add/album?error=1"));
			 exit;
		 }
	 }
	 $clients = $this->admin_model->getClientsList();
	 $output['clients'] = $clients['data'];
	 $output['logos'] = $this->admin_model->getLogos_album("");
	 if (isset($_GET['error'])) {
		 $output['alert_class'] = 'alert alert-danger';
		 $output['alert_message'] = 'Error occured, please try again.';
	 }

	 else if (isset($_GET['noaudio'])) {

		 $output['alert_class'] = 'alert alert-danger';
		 $output['alert_message'] = 'File not accepted. Not an audio file'; 
	   
	 }

	 return view('admin/add_album', $output);  
 }

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


 function admin_tracks_listing(Request $request){
	 
	 // die('here');

	  // header data pass starts
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
	  // print_r($logo_details->logo);die;

		  $output = array();

		  $output['pageTitle'] = 'Tracks';
		  $output['logo'] = $get_logo;
		  $output['welcome_name'] = $admin_name;
		  $output['user_role'] = $user_role;
	
	  // access modules
	  $output['access'] = $this->admin_model->getAdminModules($admin_id);

	   // header data pass ends

	  $output['currentPage'] = 'tracks';

	 // delete member
	 if (isset($_GET['did'])) {
		 $result = $this->admin_model->deleteTrack_trm($_GET['did']);
		 if ($result > 0) {
			 header("location: " . $output['currentPage'] . "?delete=success");
			 exit;
		 }
	 }
	 
	 if (isset($_POST['delTrackId'])) {
		 $delTrak = $_POST['delTrackId'];
		 $resultIs = $this->admin_model->deleteTrack_trm($delTrak);
		 //$result['trackId'] = $delTrak;
		 if ($resultIs > 0) {
			 $result['status'] = 'success';
		 }else{
			 $result['status'] = 'error';
		 }
		 $result['trackId'] = $delTrak;
		 //echo'<pre>';print_r($result);die();
		 echo json_encode($result);
		 exit;
	 }

	 if (isset($_GET['setting_priority']) && isset($_GET['check']) && isset($_GET['trackId'])) {
		 $result = $this->admin_model->changePriority_trm($_GET['check'], $_GET['trackId']);
	   //  print_r($result);
		 exit;
	 }
	 
	 if (isset($_GET['setting_hottest']) && isset($_GET['check']) && isset($_GET['trackId'])) {
		 $result = $this->admin_model->changeHottest_trm($_GET['check'], $_GET['trackId']);
	   //  print_r($result);
		 exit;
	 }
	 
	 if (isset($_GET['setting_top_streaming']) && isset($_GET['check']) && isset($_GET['trackId'])) {
		 $result = $this->admin_model->changeTopStreaming_trm($_GET['check'], $_GET['trackId']);
	   //  print_r($result);
		 exit;
	 }
	 if (isset($_GET['setting_top_streaming_order']) && isset($_GET['streaming_order'])) {
		 $result = $this->admin_model->changeTopStreamingOrder_trm($_GET['streaming_order']);
	   //  print_r($result);
		 exit;
	 }
	 
	 if (isset($_GET['setting_logos_order']) && isset($_GET['logos'])) {
		 $result = $this->admin_model->changeLogosOrder_trm($_GET['logos'],$_GET['tid']);
		// print_r($result);
		 exit;
	 }
	 
	 
	 
	 


	 // choose section
	 if (isset($_GET['choosen']) && isset($_GET['section']) && isset($_GET['trackId']) && isset($_GET['status'])) {

		 // staff
		 if ($_GET['section'] == 1) {
			 if ($_GET['status'] == 1) {
				 $result = $this->admin_model->assignStaff_trm($_GET['trackId']);
				 if ($result > 0) {
					 $arr = array('response' => 1, 'message' => 'Track selected for staff section');
					 echo json_encode($arr);
					 exit;
				 } else {
					 $arr = array('response' => 0, 'message' => 'Error occured, please try again!');
					 echo json_encode($arr);
					 exit;
				 }
			 } else if ($_GET['status'] == 0) {
				 $result = $this->admin_model->removeStaff_trm($_GET['trackId']);
				 if ($result > 0) {
					 $arr = array('response' => 1, 'message' => 'Track removed from staff section');
					 echo json_encode($arr);
					 exit;
				 } else {
					 $arr = array('response' => 0, 'message' => 'Error occured, please try again!');
					 echo json_encode($arr);
					 exit;
				 }
			 }
		 } else if ($_GET['section'] == 2) {
			 if ($_GET['status'] == 1) {
				 $result = $this->admin_model->assignYou_trm($_GET['trackId']);
				 if ($result > 0) {
					 $arr = array('response' => 1, 'message' => 'Track selected for you section');
					 echo json_encode($arr);
					 exit;
				 } else {
					 $arr = array('response' => 0, 'message' => 'Error occured, please try again!');
					 echo json_encode($arr);
					 exit;
				 }
			 } else if ($_GET['status'] == 0) {
				 $result = $this->admin_model->removeYou_trm($_GET['trackId']);
				 if ($result > 0) {
					 $arr = array('response' => 1, 'message' => 'Track removed from you section');
					 echo json_encode($arr);
					 exit;
				 } else {
					 $arr = array('response' => 0, 'message' => 'Error occured, please try again!');
					 echo json_encode($arr);
					 exit;
				 }
			 }
		 }
	 }

	 // generate where
	 $where = 'where ';
	 $whereItems[] = "tracks.deleted = '0'";
	 $output['searchArtist'] = '';
	 $output['searchTitle'] = '';
	 $output['searchLabel'] = '';
	 $output['searchAlbum'] = '';
	 $output['searchProducer'] = '';
	 $output['searchClient'] = '';
	 $output['searchmember'] = '';
	 $output['searchStatus'] = '';
	 if (isset($_GET['artist']) && strlen($_GET['artist']) > 0) {
		 $output['searchArtist'] = $_GET['artist'];
		 $whereItems[] = "tracks.artist LIKE '%" . urlencode($_GET['artist']) . "%'";
	 }
	 if (isset($_GET['title']) && strlen($_GET['title']) > 0) {
		 $output['searchTitle'] = $_GET['title'];
		 $whereItems[] = "tracks.title LIKE '%" . urlencode($_GET['title']) . "%'";
	 }
	 if (isset($_GET['label']) && strlen($_GET['label']) > 0) {
		 $output['searchLabel'] = $_GET['label'];
		 $whereItems[] = "tracks.label LIKE '%" . urlencode($_GET['label']) . "%'";
	 }
	 if (isset($_GET['album']) && strlen($_GET['album']) > 0) {
		 $output['searchAlbum'] = $_GET['album'];
		 $whereItems[] = "tracks.album LIKE '%" . urlencode($_GET['album']) . "%'";
	 }
	 if (isset($_GET['producer']) && strlen($_GET['producer']) > 0) {
		 $output['searchProducer'] = $_GET['producer'];
		 $whereItems[] = "tracks.producer LIKE '%" . $_GET['producer'] . "%'";
	 }
	 
	 if (isset($_GET['status']) && strlen($_GET['status']) > 0) {
		 $output['searchStatus'] = $_GET['status'];
		 $whereItems[] = "tracks.status = '" . $_GET['status'] . "'";
	 }
	 
	 if (isset($_GET['client']) && (int) $_GET['client'] > 0) {
		 $output['searchClient'] = (int) $_GET['client'];
		 $whereItems[] = "clients.id = " . (int) $_GET['client'];
	 }
		if (isset($_GET['member']) && (int) $_GET['member'] > 0) {
		 $output['searchmember'] = (int) $_GET['member'];
		 $whereItems[] = "members.id = " . (int) $_GET['member'];
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
	 $sortBy = "tracks.id";
	 $output['sortBy'] = 'order_position'; // added
	 $output['sortOrder'] = 2;
	 if (isset($_GET['sortBy'])) {
		 $output['sortBy'] = $_GET['sortBy'];
		 if (strcmp($_GET['sortBy'], 'artist') == 0) {
			 $sortBy = "tracks.artist";
		 } else if (strcmp($_GET['sortBy'], 'title') == 0) {
			 $sortBy = "tracks.title";
		 } else if (strcmp($_GET['sortBy'], 'label') == 0) {
			 $sortBy = "tracks.label";
		 } else if (strcmp($_GET['sortBy'], 'added') == 0) {
			 $sortBy = "tracks.id";
		 } else if (strcmp($_GET['sortBy'], 'album') == 0) {
			 $sortBy = "tracks.album";
		 } else if (strcmp($_GET['sortBy'], 'trackLength') == 0) {
			 $sortBy = "tracks.time";
		 } else if (strcmp($_GET['sortBy'], 'producers') == 0) {
			 $sortBy = "tracks.producer";
		 } else if (strcmp($_GET['sortBy'], 'client') == 0) {
			 $sortBy = "tracks.client";
		 } else if (strcmp($_GET['sortBy'], 'paid') == 0) {
			 $sortBy = "tracks.paid";
		 } else if (strcmp($_GET['sortBy'], 'invoiced') == 0) {
			 $sortBy = "tracks.invoiced";
		 } else if (strcmp($_GET['sortBy'], 'graphicsCompleted') == 0) {
			 $sortBy = "tracks.graphicscomplete";
		 }else if (strcmp($_GET['sortBy'], 'custom') == 0) {
			 $sortBy = "tracks.order_position";
		 }
	 } 
 	 else{
 		     $sortBy = 'tracks.order_position';
 		 }

	 // sort order
	 if (isset($_GET['sortOrder']) && $_GET['sortOrder'] == 1) {
		 $sortOrder = "ASC";
		 $output['sortOrder'] = $_GET['sortOrder'];
	 } else  if (isset($_GET['sortOrder']) && $_GET['sortOrder'] == 2) {
		 $sortOrder = "DESC";
		 $output['sortOrder'] = $_GET['sortOrder'];
	 }
// 	 elseif(!isset($_GET['sortOrder'])){
// 	     $sortOrder = "ASC";
// 	 }
	 $sort =  $sortBy . " " . $sortOrder;
	 //if(!isset($_GET['sortBy'])) {
 	  //  $sort = 'tracks.order_position DESC, tracks.added DESC';
	 //}

	 // generate link
	 $output['link_string'] = '?';
	 if (isset($_GET) && count($_GET) > 0) {
		 $link_items = array();
		 $link_string =  '?';
		 $exclude_variables = array("page");
		 foreach ($_GET as $key => $value) {
			 if (!(in_array($key, $exclude_variables))) {
				 $link_items[] = $key . '=' . $value;
			 }
		 }
		 if (count($link_items) > 1) {
			 $link_string = implode('&', $link_items);
			 $link_string = '?' . $link_string . '&';
		 } else if (count($link_items) == 1) {
			 $link_string = '?' . $link_items[0] . '&';
		 }
		 $output['link_string'] = $link_string;
	 }

	 // pagination
	 $limit = 10;
	 if (isset($_GET['numRecords'])) {
		 $limit = $_GET['numRecords'];
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
	 $num_records = $this->admin_model->getNumTracks_trm($where, $sort);
	 $numPages = (int) ($num_records / $limit);
	 $reminder = ($num_records % $limit);
	 if ($reminder > 0) {
		 $numPages = $numPages + 1;
	 }
	 $output['numPages'] = $numPages;
	 $output['start'] = $start;
	 $output['currentPageNo'] = $currentPageNo;
	 if (isset($_GET['page'])) {
		 if ($_GET['page'] > $numPages) {
			 header("location: " . $output['currentPage'] . $link_string . "page=" . $numPages);
			 exit;
		 } else if ($_GET['page'] < 1) {
			 header("location: " . $output['currentPage'] . $link_string . "page=1");
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
	 $result = $this->admin_model->getTracks_trm($where, $sort, $start, $limit);
	 
	 $price = array();


    $array = $result['data'];
    
    //$price = array_column($array, 'order_position');
    //array_multisort($price, SORT_ASC, $array);
    
//   echo '<pre>';
// 	 print_r($array);
// 	 print_r($result['data']);
// 	 die;
	 $output['tracks'] = $array;
	 

	 //if($_SERVER['REMOTE_ADDR'] == '106.220.108.140'){
	 // echo "<pre>";print_r($output['tracks']); echo "</pre>";die;
	 //}

	 $top_streaming = $this->admin_model->getAllIds_trm();
	 // print_r($top_streaming);
	 $sg_Count = 0;
	 foreach ($output['tracks'] as $t) {
		 if (in_array($t->id, $top_streaming)) {
			 // echo $t->id;
			 $output['tracks'][$sg_Count]->top_streaming = 1;
		 } else {
			 $output['tracks'][$sg_Count]->top_streaming = 0;
		 }
		 $sg_Count++;
	 }


	 $staffSelection = array();
	 $youSelection = array();
	 if ($result['numRows'] > 0) {
		 foreach ($result['data'] as $track) {
			 $staffResult = $this->admin_model->getStaffTracks($track->id);
			 if ($staffResult > 0) {
				 $staffSelection[] = $track->id;
			 }
			 $youResult = $this->admin_model->getYouTracks($track->id);
			 if ($youResult > 0) {
				 $youSelection[] = $track->id;
			 }
		 }
	 }

	 foreach ($output['tracks'] as $i => $track) {
		 $output['tracks'][$i]->mp3s = $this->admin_model->getTrackMp3s($track->id);
	 }

	 // Passing all clients
	 $output['clients'] = $this->admin_model->getClientsList();
	 $output['members']=$this->admin_model->getMembersList();

	 $output['youSelection'] =    $youSelection;
	 $output['staffSelection'] = $staffSelection;
	 if (isset($_GET['delete'])) {
		 $output['alert_class'] = 'alert alert-success';
		 $output['alert_message'] = 'Track deleted successfully.';
	 } else if (isset($_GET['error'])) {
		 $output['alert_class'] = 'alert alert-danger';
		 $output['alert_message'] = 'Error occured, please try again.';
	 } else if (isset($_GET['success'])) {
		 $output['alert_class'] = 'alert alert-success';
		 $output['alert_message'] = 'Track added successfully !';
		
	 } 
	 
	 return view('admin/tracks', $output);
 }

function track_review(Request $request)
 {
	$currTrakId = $request->get('tid');
 
	// header data pass starts
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

		$output['pageTitle'] = 'Track Review';
		$output['logo'] = $get_logo;
		$output['welcome_name'] = $admin_name;
		$output['user_role'] = $user_role;

		$output['currentPage'] = 'track_review?tid='.$currTrakId;

		// pagination

		 if(isset($_GET['commentPage'])){

		 $limit = 20;

		$start = 0; 	

		$currentPageNo = 1;		

		if(isset($_GET['commentPage']) && $_GET['commentPage']>1){

			$start = ($_GET['commentPage']*$limit)-$limit;  

		}
		if(isset($_GET['commentPage'])){

			$currentPageNo = $_GET['commentPage']; 

		}	

		$num_records = $this->admin_model->getNumTrackComments($_GET['tid']); 

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

		$output['comments'] = $this->admin_model->getTrackComments($_GET['tid'],$start,$limit); 

	  
		return view('admin/TrackReviewComments', $output);
		
		exit;
	}

	// remove comment

	 if(isset($_GET['removeComment']) && isset($_GET['commentId'])){		 

	    $result = $this->admin_model->removeTrackComment($_GET['commentId']);			

		$arr = array('status' => $result);

		echo json_encode($arr);			

		exit;
	 }	


	 if(isset($_GET['approveComment']) && isset($_GET['commentId'])){		 

	    $result = $this->admin_model->approveTrackComment($_GET['commentId']);			

		$arr = array('status' => $result);

		echo json_encode($arr);			

		exit;
	 }

	 if(isset($_GET['unapproveComment']) && isset($_GET['commentId'])){		 

	    $result = $this->admin_model->unapproveTrackComment($_GET['commentId']);			

		$arr = array('status' => $result);

		echo json_encode($arr);			

		exit;
	 }
	// review info

	 if(isset($_GET['popup']) && isset($_GET['reviewId'])){

		$popupOutput['reviews'] = $this->admin_model->getTrackReviewsByReviewId($_GET['reviewId']);			

		return view('admin.track_review_popup', $popupOutput);	

		exit;		 

	 }			
  
	  // access modules
	  $output['access'] = $this->admin_model->getAdminModules($admin_id);

	 // header data pass ends
	 
	 $where = "where tracks.id = '" . $_GET['tid'] . "'";
	 $output['tracks'] = $this->admin_model->getTrack($where);
	 $output['audios'] = $this->admin_model->getTrackMp3s($_GET['tid']);

	 $output['periodicdownloads'] = $this->admin_model->getPeriodicDownloads($_GET['tid']);
	 //$output['reviews'] = $this->admin_model->getReviews_trm($_GET['tid']);

	 $output['reviews'] = $this->admin_model->getTrackReviews($_GET['tid']);

	 $num_records = $this->admin_model->getNumTrackComments($currTrakId);
	 $start = 0;
	 $limit = 20;
	 $currentPageNo = 1;

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

	 $comments = $this->admin_model->getTrackComments($currTrakId,$start,$limit);

	 $output['comments'] = $comments;	 

	 // for comments display 
     $output['packageId'] = 2;
     $output['displayDashboard'] = 1;
     $output['displayReviews'] = 1;
	 $output['tId'] = $currTrakId;

	// echo '<pre>';print_r($output);die();

	 return view('admin/track_review', $output);
 }

 function track_view(Request $request)
 {
	// header data pass starts
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
	// print_r($logo_details->logo);die;

		$output = array();

		$output['pageTitle'] = 'View Track';
		$output['logo'] = $get_logo;
		$output['welcome_name'] = $admin_name;
		$output['user_role'] = $user_role;
  
	// access modules
	$output['access'] = $this->admin_model->getAdminModules($admin_id);

	 // header data pass ends
	 

	 $output['logos'] = array();
	 $output['logos']['numRows'] = '';
	 $where = "where tracks.id = '" . $_GET['tid'] . "'";
	 $output['tracks'] = $this->admin_model->getTrack($where);
	 if ($output['tracks']['numRows'] > 0) {
		 if (strlen($output['tracks']['data'][0]->logos) > 0) {
			 $logosWhere = "where id IN (" . $output['tracks']['data'][0]->logos . ")";
			 $output['logos'] = $this->admin_model->getLogos_trm($logosWhere);
		 }
	 }
	 $output['audios'] = $this->admin_model->getTrackMp3s($_GET['tid']);
	 if (isset($_COOKIE['track_id'])) {
		 unset($_COOKIE['track_id']);
		 setcookie('track_id', null, -1, '/');
		 $output['alert_class'] = 'alert alert-success';
		 $output['alert_message'] = 'Track added successfully!';
	 }
	 return view('admin/track_view', $output);
 }


 function track_manage_mp3(Request $request)
 {

	 // header data pass starts
	$admin_name = Auth::user()->name;
	$admin_id = Auth::user()->id;
	$user_role = Auth::user()->user_role;
	$result =0;
	
	  $logo_data = array(
		  'logo_id' => 1,
		  );

	  $logo_details = DB::table('website_logo')
	  ->where($logo_data)
	  ->first();  
	  
	  $get_logo = $logo_details->logo;
	// print_r($logo_details->logo);die;

		$output = array();

		$output['pageTitle'] = 'Manage Track';
		$output['logo'] = $get_logo;
		$output['welcome_name'] = $admin_name;
		$output['user_role'] = $user_role;
  
	// access modules
	$output['access'] = $this->admin_model->getAdminModules($admin_id);

	 // header data pass ends

	 $output['currentPage'] = 'track_manage_mp3';


	 // delete member
	 if (isset($_GET['did'])) {
		 $result = $this->admin_model->deleteTrackAudio_trm($_GET['did']);
		 if ($result > 0) {
			 header("location: " . $output['currentPage'] . "?tid=" . $_GET['tid'] . "&delete=success");
			 exit;
		 }
	 }
	 
	 if (isset($_POST['addFiles'])) {
	    // pArr($_POST);die();
	   //  pArr($_FILES);die();
		 // audio files upload
		 $fileCount = $_POST['divId'];
		 $preview = 0;
		 for ($i = 1; $i <= $fileCount; $i++) {
			 if (isset($_FILES['audio' . $i]['name']) && strlen($_FILES['audio' . $i]['name']) > 4) {

				 $audio_single = $request->file('audio'.$i) ;
				 //dd($audio_single);
				 //$audio_single = $audio[$i];
				 $destination_path = base_path('AUDIO/');

				 //Display Destination Path
				 if(empty($destination_path)){
					 $destination_path = public_path('uploads/');
				 } else {
					 $destination_path = $destination_path;
				 }

						 $audio_ext = $audio_single->getClientOriginalExtension();

						 if($audio_ext != 'mp3'){
					 
							 header("location: " . url("admin/track_manage_mp3?tid=" . $_GET['tid'] . "&noaudio=1"));
							 exit;

						 }

					 $fileName = md5(rand(1000, 10000));

					 // Define new $_FILES array - $_FILES['file']
					
					 $filename = $_FILES['audio' . $i]['name'];

					 $file_name = $_GET['tid'] . '_' . $fileName . '.mp3';

					 $uploaded_data = $audio_single->move( $destination_path , $file_name );
				 

					 if( !empty( $uploaded_data )){
						 $version = $_POST['version' . $i];
						 if (strlen($_POST['version' . $i]) < 3) {
							 $version = $_POST['otherVersion' . $i];
						 }
						 $this->admin_model->addMp3_album($_GET['tid'], $file_name, $version, $preview);
				// 		 header("location: " . url("admin/track_manage_mp3?tid=" . $_GET['tid'] . "&success=1"));
				// 		 exit;
				          $result = 2;
				

					 }

					 else{

						 header("location: " . url("admin/track_manage_mp3?tid=" . $_GET['tid'] . "&error=1"));
						 exit;
					 }
			 }
			
		 }
// 		 $result = 2;
		 if ($result > 0) {
			 header("location: " . url("admin/track_manage_mp3?tid=" . $_GET['tid'] . "&added=1"));
			 exit;
		 } else {
			 header("location: " . url("admin/track_manage_mp3?tid=" . $_GET['tid'] . "&error=1"));
			 exit;
		 }
	 }
	 if (isset($_POST['updateMp3s'])) {
		 $result = $this->admin_model->updateMp3s_trm($_POST, $_GET['tid']);
		 if ($result > 0) {
			 header("location: " . url("admin/track_manage_mp3?tid=" . $_GET['tid'] . "&update=1"));
			 exit;
		 } else {
			 header("location: " . url("admin/track_manage_mp3?tid=" . $_GET['tid'] . "&error=1"));
			 exit;
		 }
	 }
	 $where = "where tracks.id = '" . $_GET['tid'] . "'";
	 $output['tracks'] = $this->admin_model->getTrack($where);
	 $output['audios'] = $this->admin_model->getTrackMp3s($_GET['tid']);

	 if (isset($_GET['success']) || isset($_GET['added'])) {
		 $output['alert_class'] = 'alert alert-success';
		 $output['alert_message'] = 'File updated successfully.';
	 } 

	 if (isset($_GET['update'])) {
		 $output['alert_class'] = 'alert alert-success';
		 $output['alert_message'] = 'Track updated successfully.';
	 } else if (isset($_GET['error'])) {
		 $output['alert_class'] = 'alert alert-danger';
		 $output['alert_message'] = 'Error occured, please try again.';
	 }

	 else if (isset($_GET['noaudio'])) {

		 $output['alert_class'] = 'alert alert-danger';
		 $output['alert_message'] = 'File not accepted. Not an audio file'; 
		 
		 }
// 		 pArr($output);
// 		 die();
		 
 
	 return view('admin/track_manage_mp3', $output);
 }

 function track_edit(Request $request)
 {   
	error_reporting(0);  
	  // header data pass starts
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
	 //print_r($_REQUEST);die;

		$output = array();

		$output['pageTitle'] = 'Edit Track';
		$output['logo'] = $get_logo;
		$output['welcome_name'] = $admin_name;
		$output['user_role'] = $user_role;
  
	// access modules
	$output['access'] = $this->admin_model->getAdminModules($admin_id);

	 // header data pass ends


	 date_default_timezone_set('America/Los_Angeles');
	 $date_time_array = getdate(time());
	 $imgName =    $date_time_array["year"] . $date_time_array["mon"] . $date_time_array["mday"] . $date_time_array["hours"] . $date_time_array["minutes"] . $date_time_array["seconds"];

	 
	 $releasetypes = $this->admin_model->getReleaseTypes_trm();
	 
	 $output['currentPage']='track_edit';
	 
	 	 if (isset($_GET['did'])) {
		 $result = $this->admin_model->deleteTrackAudio_trm($_GET['did']);
		 if ($result > 0) {
			 header("location: " . $output['currentPage'] . "?tid=" . $_GET['tid'] . "&delete=success");
			 exit;
		 }
	 }
	 
	 
	 
	 $output['releasetypes'] = $releasetypes;
	 if (!isset($_REQUEST['tid']) || empty($_REQUEST['tid'])){
		 return Redirect::to('admin/tracks');
	 }
	 
	 if (isset($_REQUEST['deleteEmailImage'])) {
		 $this->admin_model->addEmailImage($_REQUEST['tid'], '');
		 exit;
	 }

	 if (isset($_REQUEST['deletePageImage'])) {
		 $this->admin_model->addPageImage_admin($_REQUEST['tid'], '','','');
		 exit;
	 }
	 
	$pcloud_image_id='';
	$pcloud_image_id_cover='';
	
	 if (isset($_POST['updateTrack'])) {
		 if (isset($_FILES['pageImage']['name']) && strlen($_FILES['pageImage']['name']) > 4) {
		     
					   $pageImage = $request->file('pageImage') ;
   
					   //Display File Name
					   $filename_pgi = $pageImage->getClientOriginalName();
					   // echo 'File Name: '.$filename_pgi;
					 
					   
					   //Display File Extension
					   $file_extension_pgi = $pageImage->getClientOriginalExtension();
					   // echo 'File Extension: '.$file_extension_pgi;
					 
					   
					   //Display File Real Path
					   $real_path_pgi = $pageImage->getRealPath();
					   // echo 'File Real Path: '.$real_path_pgi;
					 
					   
        				 //Display File Size
        				 $file_size = $pageImage->getSize();
        				 
    				 	if(($file_size>2000000)) {
        				 	  header("location: " . url("admin/track_edit/?tid=" . $_GET['tid'] ."&invalidSize=1")); exit;
    				 	}

        		       $query=DB::table('tracks')->select('pCloudFileID')->where('id',$_GET['tid'])->get();
                    
                       if(!empty($query[0]->pCloudFileID)){
                            $pcloud_image_id=(int)$query[0]->pCloudFileID;
                            
                            
                            if(!empty($pcloud_image_id)){
                             $delete_metadata = $this->delete_pcloud($pcloud_image_id);   
                            }
                       }
					   
					   //Display File Mime Type
					 //  $file_mime_type = $pageImage->getMimeType();
					   // echo 'File Mime Type: '.$file_mime_type;
					 
					   $destination_path_pgi = base_path('ImagesUp/');
					  // die($destination_path_pgi);
   
					   //Display Destination Path
					   if(empty($destination_path_pgi)){
						 $destination_path_pgi = public_path('uploads/');
					   } else {
						 $destination_path_pgi = $destination_path_pgi;
					   }
   
					   // // echo 'File Destination Path: '.$destination_path_pgi;
					   // if(!File::isDirectory($destination_path_pgi)) {
					   //     File::makeDirectory($destination_path_pgi, 0777, true, true);
					   // }
   
					   $image_name_pgi = $_GET['tid'] . '_pg' . $imgName . '.'.$file_extension_pgi;
					   // $image_name_pgi = preg_replace('/[^a-zA-Z0-9_.]/', '_', $image_name_pgi);
   
					   $uploaded_data_pgi = $pageImage->move( $destination_path_pgi , $image_name_pgi );
					   
					   $folder= '13187487324'; //pcloud folder id
                       $metadata = $this->uploadImage_to_pcloud($destination_path_pgi, $image_name_pgi, $folder);
                    
                       $pcloudFileId = $metadata->metadata->fileid;
                       $parentfolderid = $metadata->metadata->parentfolderid;
                       @unlink($destination_path_pgi.$image_name_pgi);
					   

					   if( !empty( $uploaded_data_pgi )){
						// die('file');

						 $this->admin_model->updatePageImage_admin($_GET['tid'], $image_name_pgi, $pcloudFileId,$parentfolderid);
 

					   }

					   else{

						 header("location: " . url("admin/track_edit/?aid=" . $_GET['aid'] . "&error=1"));
						 exit;
					   }
		 }
		 
		 if (isset($_FILES['coverimage']['name']) && strlen($_FILES['coverimage']['name']) > 4) {

			 $coverimage = $request->file('coverimage') ;
			 
			 
		    	$query=DB::table('tracks')->select('pCloudFileID_cover')->where('id',$_GET['tid'])->get();
            
               if(!empty($query[0]->pCloudFileID_cover)){
                    $pcloud_image_id_cover=(int)$query[0]->pCloudFileID_cover;
                    
                    
                    if(!empty($pcloud_image_id_cover)){
                     $delete_metadata = $this->delete_pcloud($pcloud_image_id_cover);   
                    }
               }
			 
   
					   //Display File Name
					   $filename_coi = $coverimage->getClientOriginalName();
					   // echo 'File Name: '.$filename_coi;
					 
					   
					   //Display File Extension
					   $file_extension_coi = $coverimage->getClientOriginalExtension();
					   // echo 'File Extension: '.$file_extension_coi;
					 
					   
					   //Display File Real Path
					   $real_path_coi = $coverimage->getRealPath();
					   // echo 'File Real Path: '.$real_path_coi;
					 
					   
					   //Display File Size
					  // $file_size = $coverimage->getSize();
					   // echo 'File Size: '.$file_size;
					 
					   
					   //Display File Mime Type
					 //  $file_mime_type = $coverimage->getMimeType();
					   // echo 'File Mime Type: '.$file_mime_type;
					 
					   $destination_path_coi = base_path('ImagesUp/');
					  // die($destination_path_coi);
   
					   //Display Destination Path
					   if(empty($destination_path_coi)){
						 $destination_path_coi = public_path('uploads/');
					   } else {
						 $destination_path_coi = $destination_path_coi;
					   }
   
					   // // echo 'File Destination Path: '.$destination_path_coi;
					   // if(!File::isDirectory($destination_path_coi)) {
					   //     File::makeDirectory($destination_path_coi, 0777, true, true);
					   // }
   
					   $image_name_coi = $_GET['tid'] . '_ci' . $imgName . '.'.$file_extension_coi;
					   // $image_name_coi = preg_replace('/[^a-zA-Z0-9_.]/', '_', $image_name_coi);
   
					   $uploaded_data_coi = $coverimage->move( $destination_path_coi , $image_name_coi );
					   
					   $folder= '13187487324';
                       $metadata = $this->uploadImage_to_pcloud($destination_path_coi, $image_name_coi, $folder);
            
                	   $pcloudFileId = $metadata->metadata->fileid;
                	   $parentfolderid = $metadata->metadata->parentfolderid;
                       @unlink($destination_path_coi.$image_name_coi);

					   if( !empty( $uploaded_data_coi )){
						// die('file');

						 $this->admin_model->updateCoverImage_trm($_GET['tid'], $image_name_coi, $pcloudFileId,$parentfolderid);
 

					   }

					   else{

						 header("location: " . url("admin/track_edit/?aid=" . $_GET['aid'] . "&error=1"));
						 exit;
					   }
		 }
		 // logos
        //if (isset($_POST['logos'])){
            
        //}
		 //$_POST['logos'] = array();
		 if (isset($_FILES['logoImage']['name']) && strlen($_FILES['logoImage']['name']) > 4) {
			 $logoId =  $this->admin_model->addLogo_trm($_POST['logoCompany'], $_POST['logoLink'], $_FILES['logoImage']);
			 if ($logoId > 0) {

					$logoImage = $request->file('logoImage') ;

				  //Display File Name
				  $filename_loi = $logoImage->getClientOriginalName();
				  // echo 'File Name: '.$filename_loi;
				
				  
				  //Display File Extension
				  $file_extension_loi = $logoImage->getClientOriginalExtension();
				  // echo 'File Extension: '.$file_extension_loi;
				
				  
				  //Display File Real Path
				  $real_path_loi = $logoImage->getRealPath();
				  // echo 'File Real Path: '.$real_path_loi;
				
				  
				  //Display File Size
				//  $file_size = $logoImage->getSize();
				  // echo 'File Size: '.$file_size;
				
				  
				  //Display File Mime Type
				//  $file_mime_type = $logoImage->getMimeType();
				  // echo 'File Mime Type: '.$file_mime_type;
				
				  $destination_path_loi = base_path('Logos/');
				 // die($destination_path_loi);

				  //Display Destination Path
				  if(empty($destination_path_loi)){
					$destination_path_loi = public_path('uploads/');
				  } else {
					$destination_path_loi = $destination_path_loi;
				  }
				  
				  

				  // // echo 'File Destination Path: '.$destination_path_loi;
				  // if(!File::isDirectory($destination_path_loi)) {
				  //     File::makeDirectory($destination_path_loi, 0777, true, true);
				  // }

				  $image_name_loi = $logoId . '_li' . $imgName . '.'.$file_extension_loi;
				  // $image_name_loi = preg_replace('/[^a-zA-Z0-9_.]/', '_', $image_name_loi);

				  $uploaded_data_loi = $logoImage->move( $destination_path_loi , $image_name_loi );
				  
				  $folder= '13199825142';
                  $metadata = $this->uploadImage_to_pcloud($destination_path_loi, $image_name_loi, $folder);
        
                  $pcloudFileId = $metadata->metadata->fileid;
            	  $parentfolderid = $metadata->metadata->parentfolderid;
            	  @unlink($destination_path_loi.$image_name_loi);

				  if( !empty( $uploaded_data_loi )){
				   // die('file');

					$this->admin_model->addLogoImage_trm_admin($logoId, $image_name_loi,  $pcloudFileId,$parentfolderid);


				  }

				  else{

					header("location: " . url("admin/track_edit/?aid=" . $_GET['aid'] . "&error=1"));
					exit;
				  }
			 }
			 $_POST['logos'][] = $logoId;
		 }
		 
		 
		 $new_logo=array();
		 
		 $sg_logos = $this->admin_model->getTrack("where tracks.id = ".$_GET['tid']);
		 
		 $sg_logos = explode(",",$sg_logos['data'][0]->logos);

		 if(isset($_POST['logos']) && !empty($_POST['logos'])){
    		 foreach($sg_logos as  $l){
    			 if (in_array($l, $_POST['logos'])){
    				 array_push($new_logo,$l);
    			 }
    		 }
    		 
    		 foreach( $_POST['logos'] as  $l){
    			 if (!in_array( $l, $new_logo )){
    				 array_push($new_logo,$l);
    			 }
    		 }
		 }
		 
		  //echo "POST DATA <pre>"; print_r($_POST); echo "</pre>";
		  //echo "Requested <pre>"; print_r($_POST['logos']); echo "</pre>"; 
		  //echo "Already <pre>"; print_r($sg_logos); echo "</pre>";
		  //echo "New <pre>"; print_r($new_logo); echo "</pre>";die;
		 
		 $_POST['logos']=$new_logo;
		 
		 
		 $result = $this->admin_model->updateTrack_trm($_POST, $_GET['tid']);
		 if ($result > 0) {
			 $date_time_array = getdate(time());
			 $imgName =    $date_time_array["year"] . $date_time_array["mon"] . $date_time_array["mday"] . $date_time_array["hours"] . $date_time_array["minutes"] . $date_time_array["seconds"];
			 // email image upload
			 if (isset($_FILES['emailImage']['name']) && strlen($_FILES['emailImage']['name']) > 4) {

					  $emailImage = $request->file('emailImage') ;
   
					   //Display File Name
					   $filename = $emailImage->getClientOriginalName();
					   // echo 'File Name: '.$filename;
					 
					   
					   //Display File Extension
					   $file_extension = $emailImage->getClientOriginalExtension();
					   // echo 'File Extension: '.$file_extension;
					 
					   
					   //Display File Real Path
					   $real_path = $emailImage->getRealPath();
					   // echo 'File Real Path: '.$real_path;
					 
					   
					   //Display File Size
					  // $file_size = $emailImage->getSize();
					   // echo 'File Size: '.$file_size;
					 
					   
					   //Display File Mime Type
					  // $file_mime_type = $emailImage->getMimeType();
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
   
					   $image_name = $_GET['tid'] . '_' . $imgName . '.'.$file_extension;
					   // $image_name = preg_replace('/[^a-zA-Z0-9_.]/', '_', $image_name);
   
					   $uploaded_data = $emailImage->move( $destination_path , $image_name );

					   if( !empty( $uploaded_data )){
						// die('file');

						 $this->admin_model->updateEmailImage_trm($_GET['tid'], $image_name);
 

					   }

					   else{

						 header("location: " . url("admin/track_edit/?aid=" . $_GET['aid'] . "&error=1"));
						 exit;
					   }

						 // $config['upload_path']          = './ImagesUp/';
						 // $config['allowed_types']        = 'gif|jpg|png';
						 // $config['max_size']             = 100;
						 // $config['max_width']            = 1024;
						 // $config['max_height']           = 768;
						 // $config['file_name']           = $_GET['tid'] . '_' . $imgName;
						 // $ext = explode('.', $_FILES['emailImage']['name']);
						 // $count = count($ext);
						 // $ext = $ext[$count - 1];
						 // //  $this->load->library('upload', $config);
						 // $this->upload->initialize($config);
						 // if (!$this->upload->do_upload('emailImage')) {
						 //     $error = array('error' => $this->upload->display_errors());
						 //     //    $this->load->view('upload_form', $error);
						 // } else {
						 //     $data = array('upload_data' => $this->upload->data());
						 //     $this->admin_model->updateEmailImage_trm($_GET['tid'], $config['file_name'] . '.' . $ext);
						 //     //  $this->load->view('Client_submit_track', $data);
						 // }
			 }
			 // pg image upload
			 if (isset($_FILES['pageImage']['name']) && strlen($_FILES['pageImage']['name']) > 4) {
				 //	 $date_time_array = getdate(time());
				 //$emailImageName =	$date_time_array["year"].$date_time_array["mon"].$date_time_array["mday"].$date_time_array["hours"].$date_time_array["minutes"].$date_time_array["seconds"];

				//  $pageImage = $request->file('pageImage') ;
   
				//  //Display File Name
				//  $filename = $pageImage->getClientOriginalName();
				//  // echo 'File Name: '.$filename;
			   
				 
				//  //Display File Extension
				//  $file_extension = $pageImage->getClientOriginalExtension();
				//  // echo 'File Extension: '.$file_extension;
			   
				 
				//  //Display File Real Path
				//  $real_path = $pageImage->getRealPath();
				//  // echo 'File Real Path: '.$real_path;
			   
				 
				//  //Display File Size
				// // $file_size = $pageImage->getSize();
				//  // echo 'File Size: '.$file_size;
			   
				 
				//  //Display File Mime Type
				// // $file_mime_type = $pageImage->getMimeType();
				//  // echo 'File Mime Type: '.$file_mime_type;
			   
				//  $destination_path = base_path('ImagesUp/');
				// // die($destination_path);

				//  //Display Destination Path
				//  if(empty($destination_path)){
				//    $destination_path = public_path('uploads/');
				//  } else {
				//    $destination_path = $destination_path;
				//  }

				//  // // echo 'File Destination Path: '.$destination_path;
				//  // if(!File::isDirectory($destination_path)) {
				//  //     File::makeDirectory($destination_path, 0777, true, true);
				//  // }

				//  $image_name = $_GET['tid'] . '_' . $imgName . '.'.$file_extension;
				//  // $image_name = preg_replace('/[^a-zA-Z0-9_.]/', '_', $image_name);

				//  $uploaded_data = $pageImage->move( $destination_path , $image_name );

				//  if( !empty( $uploaded_data )){
				//   // die('file');

				//    $this->admin_model->updatePageImage($_GET['tid'], $image_name);


				//  }

				//  else{

				//    header("location: " . url("admin/track_edit/?aid=" . $_GET['aid'] . "&error=1"));
				//    exit;
				//  }

				 // $config['upload_path']          = './ImagesUp/';
				 // $config['allowed_types']        = 'gif|jpg|png';
				 // $config['max_size']             = 100;
				 // $config['max_width']            = 1024;
				 // $config['max_height']           = 768;
				 // $config['file_name']           = $_GET['tid'] . '_' . $imgName . 'pg';
				 // $ext = explode('.', $_FILES['pageImage']['name']);
				 // $count = count($ext);
				 // $ext = $ext[$count - 1];
				 // //  $this->load->library('upload', $config);
				 // $this->upload->initialize($config);
				 // if (!$this->upload->do_upload('pageImage')) {
				 //     $error = array('error' => $this->upload->display_errors());
				 //     //    $this->load->view('upload_form', $error);
				 // } else {
				 //     $data = array('upload_data' => $this->upload->data());
				 //     $this->admin_model->updatePageImage($_GET['tid'], $config['file_name'] . '.' . $ext);
				 //     //  $this->load->view('Client_submit_track', $data);
				 // }
			 }
			 // audio files upload
			 $fileCount = $_POST['divId'];
			 $preview = 1;
			 for ($i = 1; $i <= $fileCount; $i++) {
				 if ($i > 1) {
					 $preview = 0;
				 }
				 if (isset($_FILES['audio' . $i]['name']) && strlen($_FILES['audio' . $i]['name']) > 4) {
					 //	 $date_time_array = getdate(time());
					 //$emailImageName =	$date_time_array["year"].$date_time_array["mon"].$date_time_array["mday"].$date_time_array["hours"].$date_time_array["minutes"].$date_time_array["seconds"];

						 $audio_single = $request->file('audio'.$i) ;
					 //dd($audio_single);
					 //$audio_single = $audio[$i];
					 $destination_path = base_path('AUDIO/');

					 //Display Destination Path
					 if(empty($destination_path)){
						 $destination_path = public_path('uploads/');
					 } else {
						 $destination_path = $destination_path;
					 }

							 $audio_ext = $audio_single->getClientOriginalExtension();

							 if($audio_ext != 'mp3'){

								 header("location: " . url("admin/track_edit?tid=" . $_GET['tid'] . "&noaudio=1"));
								 exit;

							 }

					 $fileName = md5(rand(1000, 10000));
					 $fileNameToStore = $_GET['tid'].'_'.$fileName.'.mp3';

					 // Define new $_FILES array - $_FILES['file']

				// 	 $metadata = $this->add_to_pcloud($destination_path, $fileNameToStore);

					 $uploaded_data = $audio_single->move( $destination_path , $fileNameToStore );
				 

					 if( !empty( $uploaded_data )){
						 $version = $_POST['version' . $i];
						 if (strlen($_POST['version' . $i]) < 3) {
							 $version = $_POST['otherVersion' . $i];
						 }
						 
						  //$metadata = $this->add_to_pcloud($destination_path, $fileNameToStore);

						 
						 $this->admin_model->addMp3_album($_GET['tid'], $metadata->metadata->fileid, $version, $preview);
						 exit;

					 }

					 else{

						 header("location: " . url("admin/track_edit?tid=" . $_GET['tid'] . "&error=1"));
						 exit;
					 }

					 // $config['upload_path']          = './AUDIO/';
					 // $config['allowed_types']        = 'mp3';
					 // $config['max_size']             = 9999999999999;
					 // $config['max_width']            = 1024;
					 // $config['max_height']           = 768;
					 // $fileName = md5(rand(1000, 10000));
					 // //echo '<br />';
					 // //$config['file_name']           = $_GET['tid'].'_'.$_FILES['audio'.$i]['name'];
					 // $config['file_name']           = $_GET['tid'] . '_' . $fileName . '.mp3';
					 // // 				$config['file_name']           = $_GET['tid'].'_'.$_FILES['audio'.$i]['name'];
					 // $ext = explode('.', $_FILES['audio' . $i]['name']);
					 // $count = count($ext);
					 // $ext = $ext[$count - 1];
					 // //  $this->load->library('upload', $config);
					 // // $this->upload->initialize($config);

					 // $filename = $_FILES['audio' . $i]['name'];
					 // $filepath = $_FILES['audio' . $i]['tmp_name'];

					 // $metadata = $this->add_to_pcloud($filepath, $filename);

					 // // if ( ! $this->upload->do_upload($_FILES['audio']['name'][$i]))
					 // // if (!$this->upload->do_upload('audio' . $i)) {
					 // //     $error = array('error' => $this->upload->display_errors());
					 // //     print_r($error);
					 // // } else {
					 // //     $data = array('upload_data' => $this->upload->data());
					 // //     $config['file_name'] = str_replace(' ', '_', $config['file_name']);

					 // $version = $_POST['version' . $i];
					 // if (strlen($_POST['version' . $i]) < 3) {
					 //     $version = $_POST['otherVersion' . $i];
					 // }
					 // $this->admin_model->addMp3_album($_GET['tid'], $metadata->metadata->fileid, $version, $preview);

					 // }
				 }
			 }
			 header("location: " . url("admin/track_edit?tid=" . $_GET['tid'] . "&update=1"));
			 exit;
		 } else {
			 header("location: " . url("admin/track_edit?tid=" . $_GET['tid'] . "&error_track=1"));
			 exit;
		 }
	 }
	 $where = "where tracks.id = '" . $_GET['tid'] . "'";
	 $output['tracks'] = $this->admin_model->getTrack($where);
	 $output['logos'] = $this->admin_model->getLogos_trm("");
	 //	print_r($output['logos']);
	 $output['getTrackReps'] = $this->admin_model->getTrackReps_trm($_GET['tid'], $output['tracks']['data'][0]->client);
	 $output['trackReps'] = array();
	 if ($output['getTrackReps']['numRows'] > 0) {
		 $i = 0;
		 foreach ($output['getTrackReps']['data'] as $rep) {
			 $output['trackReps'][$i]['name'] = $rep->name;
			 $output['trackReps'][$i]['email'] = $rep->email;
			 $i++;
		 }
	 }
	 $clients = $this->admin_model->getClientsList();
	 $output['clients'] = $clients['data'];
	 if (isset($_GET['update'])) {
		 $output['alert_class'] = 'alert alert-success';
		 $output['alert_message'] = 'Track updated successfully.'; 
	 } else if (isset($_GET['error'])) {
		 $output['alert_class'] = 'alert alert-danger';
		 $output['alert_message'] = 'Error occured, please try again.';
	 }
	 else if (isset($_GET['error_track'])) {
		$output['alert_class'] = 'alert alert-danger';
		$output['alert_message'] = 'Error occured, Track already exist.';
	}

	 else if (isset($_GET['noaudio'])) {

		 $output['alert_class'] = 'alert alert-danger';
		 $output['alert_message'] = 'File not accepted. Not an audio file'; 
		 
		 }else  if(isset($_GET['invalidSize'])){

			 

			  $output['alert_class'] = 'alert alert-danger';

			  $output['alert_message'] = 'Image size should not be more than 2 MB!';

		   

		   }


	 $output['audios'] = $this->admin_model->getTrackMp3s($_GET['tid']);
	 $output['genres'] = $this->admin_model->getGenres_album();
	 $output['subGenres'] = $this->admin_model->getSubGenres($output['tracks']['data'][0]->genreId);
// 	 PArr($output);
// 	 die();



	 return view('admin/track_edit', $output);
 }

 public function checkClientTrackExists(){
	
	$result = $this->admin_model->checkTrackExists($_POST['myData']);
	$result = json_decode($result, true);
	/* echo'<pre>';print_r($result);die('---GSGS'); */
	if($result['numRows'] > 0) {
		$result['status'] = 'exists';
		$result = json_encode($result);
		return $result;
	}else{
		$result['status'] = 'success';
		$result = json_encode($result);
		return $result;
	} 
 }

 function admin_add_track(Request $request)
 {
	 // header data pass starts
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
	// print_r($logo_details->logo);die;

		$output = array();

		$output['pageTitle'] = 'Add Track - Step 1';
		$output['logo'] = $get_logo;
		$output['welcome_name'] = $admin_name;
		$output['user_role'] = $user_role;
  
		 // access modules
		 $output['access'] = $this->admin_model->getAdminModules($admin_id);

	 // header data pass ends

	 $error_arr = array();
	 
	 $releasetypes = $this->admin_model->getReleaseTypes_trm();
	 
	 $output['releasetypes'] = $releasetypes;
	 
	 $clients = $this->admin_model->getClientsList();
	 $output['clients'] = $clients['data'];
	 $output['logos'] = $this->admin_model->getLogos_trm("");
	
	 // search logos
	 if (isset($_GET['listBy']) && isset($_GET['orderBy']) && isset($_GET['searchText'])) {
		 if ($_GET['listBy'] == 1 && $_GET['orderBy'] == 1) {
			 $where = " order by company asc ";
		 } else if ($_GET['listBy'] == 1 && $_GET['orderBy'] == 2) {
			 $where = " order by company desc ";
		 } else if ($_GET['listBy'] == 2 && $_GET['orderBy'] == 1) {
			 $where = " order by id asc ";
		 } else if ($_GET['listBy'] == 2 && $_GET['orderBy'] == 2) {
			 $where = " order by id desc ";
		 } else if ($_GET['listBy'] == 3) {
			 $where = " where company like '" . $_GET['searchText'] . "%' order by company asc ";
		 }
		 $ajaxOutput['logos'] = $this->admin_model->getLogos_trm($where);
		 if ($ajaxOutput['logos']['numRows'] > 0) {
			 foreach ($ajaxOutput['logos']['data'] as $logo) {
				 echo '<option value="' . $logo->id . '">' . urldecode($logo->company) . '</option>';
			 }
		 } else {
			 echo '<option value="">No data found.</option>';
		 }
		 exit;
	 }

	 $output['subGenres']['numRows'] = '';

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
	 // get search tracks
	 if (isset($_GET['searchKey']) && isset($_GET['clientSearch'])) {
		 $searchWhere =  "WHERE name  REGEXP '^[^a-z0-9]'";
		 if (strlen($_GET['searchKey']) > 0) {
			 $searchWhere = "WHERE name like '" . $_GET['searchKey'] . "%'";
		 }
		 $result =  $this->admin_model->getSearchClients($searchWhere);
		 if ($result['numRows'] > 0) {
			 foreach ($result['data'] as $data) {
				 $arr[] = array('id' => $data->id, 'name' => urldecode($data->name));
			 }
			 echo json_encode($arr);
		 }
		 exit;
	 }
	 //print_r($_POST);die();
	 if (isset($_POST['addTrack'])) {
	     //echo 'Yesy';die();
        $_POST['admin_id'] = $admin_id;
		 $track_exist = 0;

		 //if (isset($_COOKIE['track_id'])){
		 if (Session::get('track_id')) {

			 $this->admin_model->addTrackupdate_trm($_POST, Session::get('track_id'));
			 $result=Session::get('track_id');
			 $resultvar = 1;
		 } 
		 else{
			 
		  $result = $this->admin_model->addTrack_trm($_POST);
			 
	 //	echo "<pre>"; print_r($_POST);die;
			 $resultvar = 2;

			 $track_exist = 1;

			 if($result == 'track_exists'){

				 header("location: " . url("admin/add_track?error=1"));
				 exit;

				 //dd("exist");
			 }
		 }
		 
		 if ($result > 0) {
			 if($resultvar == 2){
				 //setcookie('track_id', $result, 0, "/");
                 Session::put('track_id', $result);
				// print_r($_COOKIE['track_id']);die;
			 }
			
			 $date_time_array = getdate(time());
			 $imgName =    $date_time_array["year"] . $date_time_array["mon"] . $date_time_array["mday"] . $date_time_array["hours"] . $date_time_array["minutes"] . $date_time_array["seconds"];
			 $imgName1 =    $date_time_array["year"] . $date_time_array["mon"] . $date_time_array["mday"] . $date_time_array["hours"] . $date_time_array["minutes"];
			 // email image upload
			 if (isset($_FILES['emailImage']['name']) && strlen($_FILES['emailImage']['name']) > 4) {

				 $emailImage = $request->file('emailImage') ;
   
				 //Display File Name
				 $filename = $emailImage->getClientOriginalName();
				 // echo 'File Name: '.$filename;
			   
				 
				 //Display File Extension
				 $file_extension = $emailImage->getClientOriginalExtension();
				 // echo 'File Extension: '.$file_extension;
			   
				 
				 //Display File Real Path
				 $real_path = $emailImage->getRealPath();
				 // echo 'File Real Path: '.$real_path;
			   
				 
				 //Display File Size
				 $file_size = $emailImage->getSize();
				 // echo 'File Size: '.$file_size;
			   
				 
				 //Display File Mime Type
				 $file_mime_type = $emailImage->getMimeType();
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

				 $image_name = $result . '_' . $imgName . '.'.$file_extension;
				 // $image_name = preg_replace('/[^a-zA-Z0-9_.]/', '_', $image_name);

				 $uploaded_data = $emailImage->move( $destination_path , $image_name );

				 if( !empty( $uploaded_data )){
				  // die('file');

				   $this->admin_model->addEmailImage($result, $image_name);


				 }

				 else{

				   header("location: " . url("admin/add_track/?aid=" . $_GET['aid'] . "&error=1"));
				   exit;
				  
				 }

			 }
			 // pg image upload
			 if (isset($_FILES['pageImage']['name']) && strlen($_FILES['pageImage']['name']) > 4) {

				 $pageImage = $request->file('pageImage') ;
   
				 //Display File Name
				 $filename = $pageImage->getClientOriginalName();
				 // echo 'File Name: '.$filename;
			   
				 
				 //Display File Extension
				 $file_extension = $pageImage->getClientOriginalExtension();
				 // echo 'File Extension: '.$file_extension;
			   
				 
				 //Display File Real Path
				 $real_path = $pageImage->getRealPath();
				 // echo 'File Real Path: '.$real_path;
			   
				 
				 //Display File Size
				 $file_size = $pageImage->getSize();
				 
				 	if(($file_size>2000000)) {
				 	    
				 	    
				 	  
    				 	  header("location: " . url("admin/add_track/?invalidSize=1"));
    				 	   exit;
				 
				 	 
				 	}
				 
				 
				 // echo 'File Size: '.$file_size;
			   
				 
				 //Display File Mime Type
				 $file_mime_type = $pageImage->getMimeType();
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

				 $image_name = $result . '_' . $imgName . '.'.$file_extension;
				 // $image_name = preg_replace('/[^a-zA-Z0-9_.]/', '_', $image_name);

				 $uploaded_data = $pageImage->move( $destination_path , $image_name );
				 
				 ////////////////////////////////Pcloud image 
				  $folder= '13187487324';    //folder id
                  $metadata = $this->uploadImage_to_pcloud($destination_path, $image_name, $folder);
            
                  $pcloudFileId = $metadata->metadata->fileid;
                  $parentfolderid = $metadata->metadata->parentfolderid;
                  @unlink($destination_path.$image_name);

				 if( !empty( $uploaded_data )){
				  // die('file');

				   $this->admin_model->addPageImage_admin($result, $image_name, $pcloudFileId, $parentfolderid);


				 }

				 else{

				   header("location: " . url("admin/add_track/?aid=" . $_GET['aid'] . "&error=1"));
				   exit;
				  
				 }

			 }
			 
			 
			 
			 if (isset($_FILES['coverimage']['name']) && strlen($_FILES['coverimage']['name']) > 4) {

				 $coverimage = $request->file('coverimage') ;
   
				 //Display File Name
				 $filename = $coverimage->getClientOriginalName();
				 // echo 'File Name: '.$filename;
			   
				 
				 //Display File Extension
				 $file_extension = $coverimage->getClientOriginalExtension();
				 // echo 'File Extension: '.$file_extension;
			   
				 
				 //Display File Real Path
				 $real_path = $coverimage->getRealPath();
				 // echo 'File Real Path: '.$real_path;
			   
				 
				 //Display File Size
				 $file_size = $coverimage->getSize();
				 // echo 'File Size: '.$file_size;
			   
				 
				 //Display File Mime Type
				 $file_mime_type = $coverimage->getMimeType();
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

				 $image_name = $result . '_' . $imgName1 . '.'.$file_extension;
				 // $image_name = preg_replace('/[^a-zA-Z0-9_.]/', '_', $image_name);

				 $uploaded_data = $coverimage->move( $destination_path , $image_name );
				 ////////////////////////////////Pcloud image 
				  $folder= '13187487324';    //folder id
                  $metadata = $this->uploadImage_to_pcloud($destination_path, $image_name, $folder);
            
                  $pcloudFileId = $metadata->metadata->fileid;
                  $parentfolderid = $metadata->metadata->parentfolderid;
                  @unlink($destination_path.$image_name);

				 if( !empty( $uploaded_data )){
				  // die('file');

				   $this->admin_model->addCoverImage_trm($result, $image_name, $pcloudFileId, $parentfolderid);


				 }

				 else{

				   header("location: " . url("admin/add_track/?aid=" . $_GET['aid'] . "&error=1"));
				   exit;
				  
				 }
			 }
			 // audio files upload
			 $fileCount = $_POST['divId'];
			 $preview = 1;
			 for ($i = 1; $i <= $fileCount; $i++) {
				 if ($i > 1) {
					 $preview = 0;
				 }
				 if (isset($_FILES['audio' . $i]['name']) && strlen($_FILES['audio' . $i]['name']) > 4) {

					 $audio_single = $request->file('audio'.$i) ;
					 //dd($audio_single);
					 //$audio_single = $audio[$i];
					 $destination_path = base_path('AUDIO/');

					 //Display Destination Path
					 if(empty($destination_path)){
						 $destination_path = public_path('uploads/');
					 } else {
						 $destination_path = $destination_path;
					 }

					 $audio_ext = $audio_single->getClientOriginalExtension();

					 if($audio_ext != 'mp3'){

						 header("location: " . url("admin/add_track?noaudio=1"));
						 exit;

					 }

					 $fileName = md5(rand(1000, 10000));
					 $fileNameToStore = $result.'_'.$fileName.'.mp3';
				

					 $metadata = $this->add_to_pcloud($destination_path, $fileNameToStore);

					 $uploaded_data = $audio_single->move($destination_path, $fileNameToStore );
				 

					 if( !empty( $uploaded_data )){
						 $version = $_POST['version' . $i];
						 if (strlen($_POST['version' . $i]) < 3) {
							 $version = $_POST['otherVersion' . $i];
						 }
						 $this->admin_model->addMp3_album($result, $metadata->metadata->fileid, $version, $preview);

					 }

					 else{

						 header("location: " . url("admin/add_track?error=1"));
						 exit;
					 }
				 }
			 }
			 
			 return Redirect::to("admin/add_track1?success=1");
			 exit;
		 } else {
		     
			 header("location: " . url("admin/add_track?error=1"));
			 exit;
		 }
	 }
     
	 if (isset($_GET['success'])) {
		 $output['alert_class'] = 'alert alert-success';
		 $output['alert_message'] = 'Track added successfully !';
	 } else if (isset($_GET['error'])) {

		 $track_exist = 1;

		 if($track_exist == 1){

			 $output['alert_class'] = 'alert alert-danger';
			 $output['alert_message'] = 'Track already exist';
		 }
		 else{

			 $output['alert_class'] = 'alert alert-danger';
			 $output['alert_message'] = 'Error occured, please try again.';


		 }
	   
	 }
	 else if (isset($_GET['noaudio'])) {

		 $output['alert_class'] = 'alert alert-danger';
		 $output['alert_message'] = 'File not accepted. Not an audio file'; 
		 
		 }
		 
		 
		 
		   else  if(isset($_GET['invalidSize']))				

		   {

			 

			  $output['alert_class'] = 'alert alert-danger';

			  $output['alert_message'] = 'Image size should not be more than 2 MB!'; 

		   

		   }

	 
	  $output['trackData'] = '';
	 if (isset($_GET['uid'])) {
		  $userinput = urldecode($_GET['uid']);
		  $idNum = str_replace("'", "", $userinput);
			 
		 //if(isset($_COOKIE['track_id']) && $idNum == ($_COOKIE['track_id'])){
		 if (Session::get('track_id') && ( $idNum == Session::get('track_id') )) {	
		     $trakIdIs = Session::get('track_id');
			 $trackDetails = $this->admin_model->getTrack(' WHERE tracks.id = ' . (int)$trakIdIs);
			 $output['trackData'] = current($trackDetails['data']);
		 }

	 }

	 else{

		 $data = (object)array();

		 $data->artist = '';
		 $data->title = '';
		 $data->featured_artist_1 = '';
		 $data->featured_artist_2 = '';
		 $data->feat_artist_1 = '';
		 $data->feat_artist_2 = '';
		 $data->type = '';
		 $data->client = '';
		 $data->label = '';
		 $data->producer = '';
		 $data->albumType = '';
		 $data->writer = '';
		 $data->relationship_to_artist = '';
		 $data->contact_phone = '';
		 
		 $data->memberPreviewAvailable = '';
		 $data->graphicscomplete = '';
		 $data->whitelabel = '';
		 $data->review = '';
		 $data->active = '';
		 $data->imgpage = '';
		 $data->coverimage = '';
		 $data->img = '';
		 $data->status = '';
		 $data->notes = '';
		 $data->moreinfo = '';
		 $data->subGenreId = '';
		 $data->genreId = '';

		 $data->time = '';
		 $data->bpm = '';
		 $data->album = '';
		 $data->contact_name = ''; 
		 $data->contact_email = ''; 
		$data->second_contact_email = '';					$data->third_contact_email = '';				$data->fourth_contact_email = '';

		$output['trackData'] = $data;



	 }

	 return view('admin/add_track', $output);
 }

 function admin_add_track1(Request $request)
 {
	 // header data pass starts
	 $admin_name = Auth::user()->name;
	 $admin_id = Auth::user()->id;
	 $user_role = Auth::user()->user_role;
	 
	 $_POST['admin_id'] = $admin_id;
	   $logo_data = array(
		   'logo_id' => 1,
		   );

	   $logo_details = DB::table('website_logo')
	   ->where($logo_data)
	   ->first();  
	   
	   $get_logo = $logo_details->logo;
	 // print_r($logo_details->logo);die;

		 $output = array();
		 
		 $output['pass_track_id'] = '';
		 //if (isset($_COOKIE['track_id'])) {
		 if (Session::get('track_id') && !empty(Session::get('track_id'))) {
			 $output['pass_track_id'] = Session::get('track_id');
		//	 dd($output['pass_track_id'] );
		 }
        
		 $output['pageTitle'] = 'Add Track - Step 2';
		 $output['logo'] = $get_logo;
		 $output['welcome_name'] = $admin_name;
		 $output['user_role'] = $user_role;
   
		  // access modules
		  $output['access'] = $this->admin_model->getAdminModules($admin_id);

	  // header data pass ends

	 
	 if (isset($_POST['addback'])){
	     $trakIdFromSess = Session::get('track_id');
		 header("location: " . url("admin/add_track?uid='" .$trakIdFromSess. "'"));
		 exit;
	 }
	 
	 if(Session::get('track_id')){
	     $trakIdFrmSess = Session::get('track_id');
		 $trackDetails = $this->admin_model->getTrack(' WHERE tracks.id = ' . (int)$trakIdFrmSess);
		
		 if (!(!empty($trackDetails['data']) && is_object(current($trackDetails['data'])) && current($trackDetails['data'])->id == (int)$trakIdFrmSess)) {
			 header("location: " . url("admin/add_track"));
			 exit;
		 }

		 $output['trackData'] = current($trackDetails['data']);	     
	 }else{
		 header("location: " . url("admin/add_track"));
		 exit;	     
	 }
    

	 // search logos
	 if (isset($_GET['listBy']) && isset($_GET['orderBy']) && isset($_GET['searchText'])) {
		 if ($_GET['listBy'] == 1 && $_GET['orderBy'] == 1) {
			 $where = " order by company asc ";
		 } else if ($_GET['listBy'] == 1 && $_GET['orderBy'] == 2) {
			 $where = " order by company desc ";
		 } else if ($_GET['listBy'] == 2 && $_GET['orderBy'] == 1) {
			 $where = " order by id asc ";
		 } else if ($_GET['listBy'] == 2 && $_GET['orderBy'] == 2) {
			 $where = " order by id desc ";
		 } else if ($_GET['listBy'] == 3) {
			 $where = " where company like '" . $_GET['searchText'] . "%' order by company asc ";
		 }
		 $ajaxOutput['logos'] = $this->admin_model->getLogos_trm($where);
		 if ($ajaxOutput['logos']['numRows'] > 0) {
			 foreach ($ajaxOutput['logos']['data'] as $logo) {
				 echo '<option value="' . $logo->id . '">' . urldecode($logo->company) . '</option>';
			 }
		 } else {
			 echo '<option value="">No data found.</option>';
		 }
		 exit;
	 }

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
	 // get search tracks
	 if (isset($_GET['searchKey']) && isset($_GET['clientSearch'])) {
		 $searchWhere =  "WHERE name  REGEXP '^[^a-z0-9]'";
		 if (strlen($_GET['searchKey']) > 0) {
			 $searchWhere = "WHERE name like '" . $_GET['searchKey'] . "%'";
		 }
		 $result =  $this->admin_model->getSearchClients($searchWhere);
		 if ($result['numRows'] > 0) {
			 foreach ($result['data'] as $data) {
				 $arr[] = array('id' => $data->id, 'name' => urldecode($data->name));
			 }
			 echo json_encode($arr);
		 }
		 exit;
	 }
	 if (isset($_POST['addTrack'])) {
		 
		 // echo '<pre>';print_r($output);die('---GSGS');
		 // logos
		 if (isset($_FILES['logoImage']['name']) && strlen($_FILES['logoImage']['name']) > 4) {
			 $logoId =  $this->admin_model->addLogo_trm($_POST['logoCompany'], $_POST['logoLink'], $_FILES['logoImage']);
			 if ($logoId > 0) {

				 $logoImage = $request->file('logoImage') ;
   
				 //Display File Name
				 $filename = $logoImage->getClientOriginalName();
				 // echo 'File Name: '.$filename;
			   
				 
				 //Display File Extension
				 $file_extension = $logoImage->getClientOriginalExtension();
				 // echo 'File Extension: '.$file_extension;
			   
				 
				 //Display File Real Path
				 $real_path = $logoImage->getRealPath();
				 // echo 'File Real Path: '.$real_path;
			   
				 
				 //Display File Size
				 $file_size = $logoImage->getSize();
				 // echo 'File Size: '.$file_size;
			   
				 
				 //Display File Mime Type
				 $file_mime_type = $logoImage->getMimeType();
				 // echo 'File Mime Type: '.$file_mime_type;
			   
				 $destination_path = base_path('Logos/');
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

				 $image_name = $logoId . '_' . $_FILES['logoImage']['name'] . '.'.$file_extension;
				 // $image_name = preg_replace('/[^a-zA-Z0-9_.]/', '_', $image_name);

				 $uploaded_data = $logoImage->move($destination_path , $image_name);
				 
				 ////////////////////////////////Pcloud image 
				  $folder= '13199825142';    //folder id
                  $metadata = $this->uploadImage_to_pcloud($destination_path, $image_name, $folder);
            
                  $pcloudFileId = $metadata->metadata->fileid;
                  $parentfolderid = $metadata->metadata->parentfolderid;
                  @unlink($destination_path.$image_name);
				 

				 if( !empty( $uploaded_data )){
				  // die('file');

				   $this->admin_model->addLogoImage_trm_admin($logoId, $image_name,$pcloudFileId,$parentfolderid);


				 }

				 else{

				   header("location: " . url("admin/add_track/?aid=" . $_GET['aid'] . "&error=1"));
				   exit;
				  
				 }
			 }
			 $_POST['logos'][] = $logoId;
		 }
		 $sessTrakID = Session::get('track_id');
		 $result = $this->admin_model->addTrack1_trm($_POST, $sessTrakID);
		 if ($result > 0) {

			 // audio files upload
			 $fileCount = (int) $_POST['divId'];
			 if($fileCount > 0) {
				 $preview = 1;
				 for ($i = 1; $i <= $fileCount; $i++) {
					 if ($i > 1) {
						 $preview = 0;
					 }
					 if (isset($_FILES['audio' . $i]['name']) && strlen($_FILES['audio' . $i]['name']) > 4) {

						 $audio_single = $request->file('audio'.$i) ;
						 //dd($audio_single);
						 //$audio_single = $audio[$i];
						 $destination_path = base_path('AUDIO/');
 
						 //Display Destination Path
						 if(empty($destination_path)){
							 $destination_path = public_path('uploads/');
						 } else {
							 $destination_path = $destination_path;
						 }

						 $audio_ext = $audio_single->getClientOriginalExtension();

						 if($audio_ext != 'mp3'){

							 header("location: " . url("admin/add_track1?noaudio=1"));
							 exit;

						 }
 
						 $fileName = md5(rand(1000, 10000));
						 $fileNameToStore = $sessTrakID.'_'.$fileName.'.mp3';
                        
                        // echo $fileNameToStore;
                        // die();
                        
                        
                        $uploaded_data = $audio_single->move( $destination_path , $fileNameToStore );
						
 
						 
					 
 
						 if( !empty( $uploaded_data )){
							 $version = $_POST['version' . $i];
							 if (strlen($_POST['version' . $i]) < 3) {
								 $version = $_POST['otherVersion' . $i];
							 }

							 $bpm =  $_POST['bpm'];
							 $key = $_POST['key'];
							 
							  $metadata = $this->add_to_pcloud($destination_path, $fileNameToStore);

							 $this->admin_model->addMp3_album($sessTrakID, $metadata->metadata->fileid, $version, $preview, $bpm, $key);
 
						 }
 
						 else{
 
							 header("location: " . url("admin/add_track1?error=1"));
							 exit;
						 }
					 }
				 }
			 }
			 
			 $store_cookie_before_remove = Session::get('track_id');
			 //unset($_COOKIE['track_id']);
			 //setcookie('track_id', null, -1, '/');
			 Session::forget('track_id');
			 
			 //header("location: " . url("admin/?tid=" . $store_cookie_before_remove . "&success=1"));
			 
			 //return Redirect::to("admin/?tid=" . $store_cookie_before_remove . "&success=1");
			 return Redirect::to("admin/tracks?success=1");
			 die;
			 // header("location: " . url("admin/add_track2?success=1"));
			 // exit;
		 }
		 
		 if ($result == 'track_exists') {
			 return Redirect::to("admin/add_track1?error=1&exists=1");
			 exit;
		 } else {
			 return Redirect::to("admin/add_track1?error=1");
			 exit;
		 }
	 }

	 $clients = $this->admin_model->getClientsList();
	 $output['clients'] = $clients['data'];
	 $output['logos'] = $this->admin_model->getLogos_trm("");
	 
	 if (isset($_GET['success'])) {
		 $output['alert_class'] = 'alert alert-success';
		 $output['alert_message'] = 'Track added successfully !';
		 //unset($_COOKIE['track_id']);
	 } else if (isset($_GET['error'])) {
		 $output['alert_class'] = 'alert alert-danger';
		 $output['alert_message'] = 'Error occured, please try again.';
	 }
	 else if (isset($_GET['noaudio'])) {

		 $output['alert_class'] = 'alert alert-danger';
		 $output['alert_message'] = 'File not accepted. Not an audio file'; 
		 
		 }

		 

	 if (isset($_GET['exists'])) {
		 $output['track_exists'] = TRUE;
	 }
	 else{
		 $output['track_exists'] = FALSE;
	 }

	 return view('admin/add_track1', $output);
 }

 function submitted_tracks()
 {
	 // header data pass starts
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
	 // print_r($logo_details->logo);die;

		 $output = array();

		 $output['pageTitle'] = 'Submitted Tracks';
		 $output['logo'] = $get_logo;
		 $output['welcome_name'] = $admin_name;
		 $output['user_role'] = $user_role;
   
		  // access modules
		  $output['access'] = $this->admin_model->getAdminModules($admin_id);

	  // header data pass ends

	 // delete track
	 if (isset($_GET['did'])) {
		 $result = $this->admin_model->deleteSubmittedTrack_trm($_GET['did'], $admin_id);
		 if ($result > 0) {
			 header("location: " . url("admin/submitted_tracks?delete=success"));
			 exit;
		 } else {
		   
			 header("location: " . url("admin/submitted_tracks?error=success"));
			 exit;
		 }
	 }
	 // preivew track
	 if (isset($_GET['modalView']) && isset($_GET['trackSubId'])) {
		 $modalWhere = "where tracks_submitted.approved = '0' and tracks_submitted.deleted = '0'  and tracks_submitted.id = '" . $_GET['trackSubId'] . "'";
		 $modalOutput['result'] = $this->admin_model->getSubmittedTracks_trm($modalWhere, 0, 1);
		 // $this->load->view('submitted_track_preview', $modalOutput);
		 return view('admin/submitted_track_preview', $modalOutput);
		 exit;
	 }
	 // approve submitted track
	 if (isset($_GET['approveTrackSubId'])) {
		 $approve = $this->admin_model->approveSubTrack_trm($_GET['approveTrackSubId']);
		 if ($approve > 0) {
			 header("location: " . url("admin/submitted_tracks?approved=1"));
			 exit;
		 } else {
			 header("location: " . url("admin/submitted_tracks?approvedError=1"));
			 exit;
		 }
	 }
	 $output['currentPage'] = 'submitted_tracks';
	 $where = "where tracks_submitted.approved = '0' and tracks_submitted.deleted = '0'";
	 // pagination
	 $start = 0;
	 $limit = 10;
	 $currentPageNo = 1;
	 if (isset($_GET['page']) && $_GET['page'] > 1) {
		 $start = ($_GET['page'] * $limit) - $limit;
	 }
	 if (isset($_GET['page'])) {
		 $currentPageNo = $_GET['page'];
	 }
	 $num_records = $this->admin_model->getNumSubmittedTracks_trm($where);
	 $numPages = (int) ($num_records / $limit);
	 $reminder = ($num_records % $limit);
	 if ($reminder > 0) {
		 $numPages = $numPages + 1;
	 }
	 $output['numPages'] = $numPages;
	 $output['start'] = $start;
	 $output['currentPageNo'] = $currentPageNo;
	 if (isset($_GET['page'])) {
		 if ($_GET['page'] > $numPages) {
			 header("location: " . $output['currentPage'] . "?page=" . $numPages);
			 exit;
		 } else if ($_GET['page'] < 1) {
			 header("location: " . $output['currentPage'] . "?page=1");
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
	 $result = $this->admin_model->getSubmittedTracks_trm($where, $start, $limit);
	 $output['tracks'] = $result['data'];
	 if (isset($_GET['approved'])) {
		 $output['alert_class'] = 'alert alert-info';
		 $output['alert_message'] = 'Track approved successfully !';
	 } else if (isset($_GET['approvedError']) || isset($_GET['error'])) {
		 $output['alert_class'] = 'alert alert-danger';
		 $output['alert_message'] = 'Error occured, please try again.';
	 } else if (isset($_GET['delete'])) {
		 $output['alert_class'] = 'alert alert-success';
		 $output['alert_message'] = 'Track deleted successfully.';
	 }

    // echo '<pre>';
    // print_r($output);
    // echo '</pre>';

	 return view('admin/submitted_tracks', $output);
 }

 function submitted_tracks_versions()
 {
	 // header data pass starts
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
	 // print_r($logo_details->logo);die;

		 $output = array();

		 $output['pageTitle'] = 'Submitted Tracks Versions';
		 $output['logo'] = $get_logo;
		 $output['welcome_name'] = $admin_name;
		 $output['user_role'] = $user_role;
   
		  // access modules
		  $output['access'] = $this->admin_model->getAdminModules($admin_id);

	  // header data pass ends

	 // delete track
	//  if (isset($_GET['did'])) {
	// 	 $result = $this->admin_model->deleteSubmittedTrack_trm($_GET['did'], $admin_id);
	// 	 if ($result > 0) {
	// 		 header("location: " . url("admin/submitted_tracks?delete=success"));
	// 		 exit;
	// 	 } else {
		   
	// 		 header("location: " . url("admin/submitted_tracks?error=success"));
	// 		 exit;
	// 	 }
	//  }
	 ## preivew track versions
	 if (isset($_GET['modalView']) && isset($_GET['trackSubId']) && $_GET['action'] == 'preview') {
	 	 $modalWhere = " WHERE tracks_submitted_versions.approved = '0' AND tracks_submitted_versions.deleted = '0' AND tracks.id = '" . $_GET['trackSubId'] . "'";
	 	//  echo $modalWhere;die();
	 	 $modalOutput['result'] = $this->admin_model->getSubmittedVersionsForTrack($modalWhere);
		//  print_r($modalOutput['result']);die();
	 	 return view('admin/submitted_track_versions_preview', $modalOutput);
	 	 exit;
	  }
	 // approve Submitted Track  Versions
	//  if (isset($_GET['approveVersionsTrackId'])) {
	// 	 $approve = $this->admin_model->approveSubmittedTrackVersions($_GET['approveVersionsTrackId']);
	// 	 if ($approve > 0) {
	// 		 header("location: " . url("admin/submitted_tracks_versions?approved=1"));
	// 		 exit;
	// 	 } else {
	// 		 header("location: " . url("admin/submitted_tracks_versions?approvedError=1"));
	// 		 exit;
	// 	 }
	//  }
	 $output['currentPage'] = 'submitted_tracks_versions';
	 $where = " WHERE tracks_submitted_versions.approved = '0' and tracks_submitted_versions.deleted = '0'";
	 // pagination
	 $start = 0;
	 $limit = 10;
	 $currentPageNo = 1;
	 if (isset($_GET['page']) && $_GET['page'] > 1) {
		 $start = ($_GET['page'] * $limit) - $limit;
	 }
	 if (isset($_GET['page'])) {
		 $currentPageNo = $_GET['page'];
	 }
	 $num_records = $this->admin_model->getNumSubmittedTracksVersions_trm($where);
	 $numPages = (int) ($num_records / $limit);
	 $reminder = ($num_records % $limit);
	 if ($reminder > 0) {
		 $numPages = $numPages + 1;
	 }
	 $output['numPages'] = $numPages;
	 $output['start'] = $start;
	 $output['currentPageNo'] = $currentPageNo;
	 $output['numRecords'] = $num_records;
	 if (isset($_GET['page'])) {
		 if ($_GET['page'] > $numPages) {
			 header("location: " . $output['currentPage'] . "?page=" . $numPages);
			 exit;
		 } else if ($_GET['page'] < 1) {
			 header("location: " . $output['currentPage'] . "?page=1");
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
	 $result = $this->admin_model->getSubmittedTracksVersions_trm($where, $start, $limit);
	 $output['tracks'] = $result['data'];
	 
	 if (isset($_GET['approved'])) {
		 $output['alert_class'] = 'alert alert-info';
		 $output['alert_message'] = 'Track approved successfully !';
	 } else if (isset($_GET['approvedError']) || isset($_GET['error'])) {
		 $output['alert_class'] = 'alert alert-danger';
		 $output['alert_message'] = 'Error occured, please try again.';
	 } else if (isset($_GET['delete'])) {
		 $output['alert_class'] = 'alert alert-success';
		 $output['alert_message'] = 'Track deleted successfully.';
	 }

    // echo '<pre>';
    // print_r($output);
    // echo '</pre>';

	 return view('admin/submitted_tracks_versions', $output);
 }

 function submitted_tracks_versions_get(Request $request){

 	$versionTrackId = $request->get('trackSubId');
 	if(!empty($versionTrackId)){
 		$where = " WHERE tracks_submitted_versions.approved = '0' and tracks_submitted_versions.deleted = '0'";
 		$result = $this->admin_model->getSubmittedVersionsForTrack($where, $versionTrackId);
 		echo '<pre>';print_r($result);die();
 	}

 	echo $versionTrackId;die();
 }

function submitted_tracks_versions_edit() {
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

	$output['pageTitle'] = 'Edit Track Versions';
	$output['logo'] = $get_logo;
	$output['welcome_name'] = $admin_name;
	$output['user_role'] = $user_role;
  
	// access modules
	$output['access'] = $this->admin_model->getAdminModules($admin_id);
	$output['logos'] = array();
	$output['logos']['numRows'] = '';
	$track_id = $_GET['tid'];
	$Where = " WHERE tracks_submitted_versions.approved = '0' AND tracks_submitted_versions.deleted = '0' AND tracks.id = '" . $track_id . "'";
	$output['result'] = $this->admin_model->getSubmittedVersionsForTrack($Where);
	return view('admin/submitted_tracks_versions_edit', $output);
	exit;
 }

function approveVersion(Request $request)
{
	$request->validate([
		'id' => 'required|integer',
		'contact_email' => 'required|email',
		'client_name' => 'required|string',
		'track_name' => 'required|string',
	]);

	$version = DB::table('tracks_submitted_versions')
            ->where('id', $request->id)
            ->first();

	if (!$version) {
		return response()->json(['message' => 'Submitted version not found.'], 404);
	}

    DB::table('tracks_submitted_versions')
        ->where('id', $request->id)
        ->update(['approved' => 1]);
	
	DB::table('tracks_mp3s')->insert([
		'track'    => $version->track_id,
		'type'     => 'approved',
		'version'  => $version->version_name,
		'location' => $version->pcloud_fileId,
		'title'    => $version->track_title,
		'added' => $version->added_on,
	]);

	$approved_versions = [];
	if (!empty($request->id)) {
		$approved_versions[] = $version->version_name;
		$data = [
			'contact_email' => $request->contact_email, 
			'client_name' => $request->client_name,
			'approved_versions' => $approved_versions,
			'track_id' => $version->track_id,
			'track_name' => $request->track_name,
			'artist' => $request->artist,
		];

		try {
			Mail::send('mails.clients.versionApproved', ['data' => $data], function ($message) use ($data) {
				 $message->to($data['contact_email']);				
				$message->subject('Track Version Approved');
				$message->from('business@digiwaxx.com', 'Digiwaxx');
			});
		} catch(\Exception $e) {
			echo $e->getMessage();
		}
	}

    return response()->json(['message' => 'Version approved successfully.']);
}

function approveAllVersions(Request $request)
{
    $request->validate([
        'ids' => 'required|array',
        'ids.*' => 'integer',
		'contact_email' => 'required|email',
        'client_name' => 'required|string',
        'track_name' => 'required|string',
        'artist' => 'required|string',
    ]);

    $versions = DB::table('tracks_submitted_versions')
        ->whereIn('id', $request->ids)
        ->get();

	if ($versions->isEmpty()) {
		return response()->json(['message' => 'No submitted versions found.'], 404);
	}

    foreach ($versions as $version) {
        DB::table('tracks_submitted_versions')
            ->where('id', $version->id)
            ->update(['approved' => 1]);

        DB::table('tracks_mp3s')->insert([
            'track'    => $version->track_id,
            'type'     => 'approved',
            'version'  => $version->version_name,
            'location' => $version->pcloud_fileId,
            'title'    => $version->track_title,
            'added'    => $version->added_on,
        ]);
		$approved_versions[] = $version->version_name;
    }

	$data = [
        'contact_email'     => $request->contact_email,
        'client_name'       => $request->client_name,
        'approved_versions' => $approved_versions,
        'track_id'          => $versions[0]->track_id ?? null,
        'track_name'        => $request->track_name,
        'artist'            => $request->artist,
    ];

	try {
        Mail::send('mails.clients.versionApproved', ['data' => $data], function ($message) use ($data) {
            $message->to($data['contact_email']);			
            $message->subject('Track Versions Approved');
            $message->from('business@digiwaxx.com', 'Digiwaxx');
        });
    } catch(\Exception $e) {
        echo ('Mail sending failed: ' . $e->getMessage());
    }

    return response()->json(['message' => 'All versions approved successfully.']);
}


function deleteVersion(Request $request)
{
    $request->validate([
        'id' => 'required|integer',
    ]);

    DB::table('tracks_submitted_versions')
        ->where('id', $request->id)
        ->update(['deleted' => 1]);

    return response()->json(['message' => 'Version deleted successfully.']);
}

function deleteAllVersions(Request $request)
{
    $request->validate([
        'ids' => 'required|array',
        'ids.*' => 'integer',
    ]);

    DB::table('tracks_submitted_versions')
        ->whereIn('id', $request->ids)
        ->update(['deleted' => 1]);

    return response()->json(['message' => 'All Versions deleted successfully.']);
}


 function submitted_track_edit(Request $request)
 {   

	 // header data pass starts
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
	 // print_r($logo_details->logo);die;

		 $output = array();

		 $output['pageTitle'] = 'Edit Submitted Tracks';
		 $output['logo'] = $get_logo;
		 $output['welcome_name'] = $admin_name;
		 $output['user_role'] = $user_role;
		 $output['subGenres'] = '';
   
		  // access modules
		  $output['access'] = $this->admin_model->getAdminModules($admin_id);

	  // header data pass ends

      $pcloud_image_id='';
	 date_default_timezone_set('America/Los_Angeles');
 
	 if (isset($_POST['updateSubmittedTrack'])) {
		 $result = $this->admin_model->updateSubmittedTrack_trm($_POST, $_GET['tid']);
		 if ($result > 0) {
			 $date_time_array = getdate(time());
			 $imgName =    $date_time_array["year"] . $date_time_array["mon"] . $date_time_array["mday"] . $date_time_array["hours"] . $date_time_array["minutes"] . $date_time_array["seconds"];
			 // pg image upload
			 if (isset($_FILES['pageImage']['name']) && strlen($_FILES['pageImage']['name']) > 4) {
			     
			 $query=DB::table('tracks_submitted')->select('pCloudFileID')->where('id',$_GET['tid'])->get();
            
               if(!empty($query[0]->pCloudFileID)){
                    $pcloud_image_id=(int)$query[0]->pCloudFileID;
                    
                    
                    if(!empty($pcloud_image_id)){
                     $delete_metadata = $this->delete_pcloud($pcloud_image_id);   
                    }
               }
				 //	 $date_time_array = getdate(time());
				 //$emailImageName =	$date_time_array["year"].$date_time_array["mon"].$date_time_array["mday"].$date_time_array["hours"].$date_time_array["minutes"].$date_time_array["seconds"];

				 
				 $pageImage = $request->file('pageImage') ;
   
				 //Display File Name
				 $filename = $pageImage->getClientOriginalName();
				 // echo 'File Name: '.$filename;
			   
				 
				 //Display File Extension
				 $file_extension = $pageImage->getClientOriginalExtension();
				 // echo 'File Extension: '.$file_extension;
			   
				 
				 //Display File Real Path
				 $real_path = $pageImage->getRealPath();
				 // echo 'File Real Path: '.$real_path;
			   
				 
				 //Display File Size
				 $file_size = $pageImage->getSize();
				 // echo 'File Size: '.$file_size;
			   
				 
				 //Display File Mime Type
				 $file_mime_type = $pageImage->getMimeType();
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

				 $image_name = $_GET['tid'] . '_' . $imgName . '.'.$file_extension;
				 // $image_name = preg_replace('/[^a-zA-Z0-9_.]/', '_', $image_name);

				 $uploaded_data = $pageImage->move( $destination_path , $image_name );
				 
				  $folder= '13187487324';
                   $metadata = $this->uploadImage_to_pcloud($destination_path, $image_name, $folder);
                    
                    $pcloudFileId = $metadata->metadata->fileid;
                    $parentfolderid = $metadata->metadata->parentfolderid;
                    @unlink($destination_path.$image_name);
				 
				 

				 if( !empty( $uploaded_data )){
				  // die('file');

				   $this->admin_model->updateSubmittedPageImage_trm($_GET['tid'], $image_name,$pcloudFileId,$parentfolderid);


				 }

				 else{

				   header("location: " . url("admin/submitted_track_edit/?tid=" . $_GET['tid'] . "&error=1"));
				   exit;
				  
				 }

						 // $config['upload_path']          = './ImagesUp/';
						 // $config['allowed_types']        = 'gif|jpg|png';
						 // $config['max_size']             = 100;
						 // $config['max_width']            = 1024;
						 // $config['max_height']           = 768;
						 // $config['file_name']           = $_GET['tid'] . '_' . $imgName . 'pg';
						 // $ext = explode('.', $_FILES['pageImage']['name']);
						 // $count = count($ext);
						 // $ext = $ext[$count - 1];
						 // //  $this->load->library('upload', $config);
						 // $this->upload->initialize($config);
						 // if (!$this->upload->do_upload('pageImage')) {
						 //     $error = array('error' => $this->upload->display_errors());
						 //     print_r($error);
						 //     //    $this->load->view('upload_form', $error);
						 // } else {
						 //     $data = array('upload_data' => $this->upload->data());
						 //     $this->admin_model->updateSubmittedPageImage_trm($_GET['tid'], $config['file_name'] . '.' . $ext);
						 //     print_r($data);
						 //     //  $this->load->view('Client_submit_track', $data);
						 // }
			 }
			 // audio files upload
			 $fileCount = $_POST['divId'];
			 $preview = 1;
			 for ($i = 1; $i <= $fileCount; $i++) {
				 if ($i > 1) {
					 $preview = 0;
				 }
				 if (isset($_FILES['audio' . $i]['name']) && strlen($_FILES['audio' . $i]['name']) > 4) {
					 //	 $date_time_array = getdate(time());
					 //$emailImageName =	$date_time_array["year"].$date_time_array["mon"].$date_time_array["mday"].$date_time_array["hours"].$date_time_array["minutes"].$date_time_array["seconds"];

					 
					 $audio_single = $request->file('audio'.$i) ;
					 //dd($audio_single);
					 //$audio_single = $audio[$i];
					 $destination_path = base_path('AUDIO/');

					 //Display Destination Path
					 if(empty($destination_path)){
						 $destination_path = public_path('uploads/');
					 } else {
						 $destination_path = $destination_path;
					 }

					 $audio_ext = $audio_single->getClientOriginalExtension();

					 if($audio_ext != 'mp3'){

						 header("location: " . url("admin/submitted_track_edit?tid=" . $_GET['tid'] . "&noaudio=1"));
						 exit;

					 }

					 $fileName = md5(rand(1000, 10000));
					 $fileNameToStore = $_GET['tid'].'_'.$fileName.'.mp3';

				// 	 $metadata = $this->add_to_pcloud($destination_path, $fileNameToStore);

					 $uploaded_data = $audio_single->move( $destination_path , $fileNameToStore );
				 

					 if( !empty( $uploaded_data )){
						 $version = $_POST['version' . $i];
						 if (strlen($_POST['version' . $i]) < 3) {
							 $version = $_POST['otherVersion' . $i];
						 }
						  $metadata = $this->add_to_pcloud($destination_path, $fileNameToStore);

						 $functionName = "addClientTrackAmr" . $i;
						 $this->admin_model->$functionName($_GET['tid'], $metadata->metadata->fileid, $version);

					 }

					 else{

						 header("location: " . url("admin/submitted_track_edit?tid=" . $_GET['tid'] . "&error=1"));
						 exit;
					 }

							 // $config['upload_path']          = './AUDIO/';
							 // $config['allowed_types']        = 'mp3';
							 // $config['max_size']             = 9999999999999;
							 // $config['max_width']            = 1024;
							 // $config['max_height']           = 768;
							 // $fileName = md5(rand(1000, 10000));
							 // //echo '<br />';
							 // //$config['file_name']           = $_GET['tid'].'_'.$_FILES['audio'.$i]['name'];
							 // $config['file_name']           = $_GET['tid'] . '_' . $fileName . '.mp3';
							 // // 				$config['file_name']           = $_GET['tid'].'_'.$_FILES['audio'.$i]['name'];
							 // $ext = explode('.', $_FILES['audio' . $i]['name']);
							 // $count = count($ext);
							 // $ext = $ext[$count - 1];
							 // //  $this->load->library('upload', $config);
							 // // $this->upload->initialize($config);

							 // $filename = $_FILES['audio' . $i]['name'];
							 // $filepath = $_FILES['audio' . $i]['tmp_name'];

							 // $metadata = $this->add_to_pcloud($filepath, $filename);

							 // // if ( ! $this->upload->do_upload($_FILES['audio']['name'][$i]))
							 // // if (!$this->upload->do_upload('audio' . $i)) {
							 // //     $error = array('error' => $this->upload->display_errors());
							 // //     print_r($error);
							 // // } else {
							 // //  $data = array('upload_data' => $this->upload->data());
							 // //  $config['file_name'] = str_replace(' ', '_',$config['file_name']);
							 // //  $this->tracksdb->addSubmittedMp3($_GET['tid'],$config['file_name'],$_POST['version'.$i],$preview);

							 // $version = $_POST['version' . $i];
							 // $data = array('upload_data' => $this->upload->data());
							 // $config['file_name'] = str_replace(' ', '_', $config['file_name']);
							 // $functionName = "addClientTrackAmr" . $i;
							 // $this->admin_model->$functionName($_GET['tid'], $metadata->metadata->fileid, $version);
					 // }
				 }
			 }
			 header("location: " . url("admin/submitted_track_edit?tid=" . $_GET['tid'] . "&update=1"));
			 exit;
		 } else {
			 header("location: " . url("admin/submitted_track_edit?tid=" . $_GET['tid'] . "&error=1"));
			 exit;
		 }
	 }
	 $where = "where tracks_submitted.approved = '0' and tracks_submitted.deleted = '0' and tracks_submitted.id = '" . $_GET['tid'] . "'";
	 $output['result'] = $this->admin_model->getSubmittedTracks_trm($where, 0, 1);
	 // echo'<pre>';print_r($output['result']);die();
	 $output['genres'] = $this->admin_model->getGenres_album();
	 if($output['result']['numRows'] > 0){
		$output['subGenres'] = $this->admin_model->getSubGenres($output['result']['data'][0]->genreId);
	 }
	 if (isset($_GET['update'])) {
		 $output['alert_class'] = 'alert alert-info';
		 $output['alert_message'] = 'Track updated successfully!';
	 } else if (isset($_GET['error'])) {
		 $output['alert_class'] = 'alert alert-danger';
		 $output['alert_message'] = 'Error occured, please trya again!';
	 }
	 else if (isset($_GET['noaudio'])) {

		 $output['alert_class'] = 'alert alert-danger';
		 $output['alert_message'] = 'File not accepted. Not an audio file'; 
		 
		 }
		 

	 return view('admin/submitted_track_edit', $output);

 }


 function top_streaming()
 {   
	 // header data pass starts
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
	 // print_r($logo_details->logo);die;

		 $output = array();

		 $output['pageTitle'] = 'Top Streaming Tracks';
		 $output['logo'] = $get_logo;
		 $output['welcome_name'] = $admin_name;
		 $output['user_role'] = $user_role;
   
		  // access modules
		  $output['access'] = $this->admin_model->getAdminModules($admin_id);

	  // header data pass ends

   
	 
	 if (isset($_GET['delete_streaming'])) {
		 $output['deleted'] = $this->admin_model->delete_streaming_tracks_trm($_GET['delete_streaming']);
		 $output['alert_message'] = "Track deleted from Top Streaming List";
		 $output['alert_class'] = "alert alert-success";
		 
	 }
	 
	 $output['tracks'] = $this->admin_model->getAllTracks_trm();
	 // echo "<pre>";print_r($output['tracks']);echo "</pre>";

	 return view('admin/top_streaming', $output);

 }

 function top_priority(){

	  // header data pass starts
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
	  // print_r($logo_details->logo);die;

		  $output = array();

		  $output['pageTitle'] = 'Top Priority Tracks';
		  $output['logo'] = $get_logo;
		  $output['welcome_name'] = $admin_name;
		  $output['user_role'] = $user_role;
	
		   // access modules
		   $output['access'] = $this->admin_model->getAdminModules($admin_id);

	   // header data pass ends
 
	 
	 if (isset($_GET['remove_priority'])){
		 $output['deleted'] = $this->admin_model->changePriority_trm(false,$_GET['remove_priority']);
		 $output['alert_message'] = "Track deleted from Top Priority List";
		 $output['alert_class'] = "alert alert-success";
		 
	 }
	 
	 $output['tracks'] = $this->admin_model->getAllPriorityTracks_trm();
	 // echo "<pre>";print_r($output['tracks']);echo "</pre>";


	 return view('admin/top_priority', $output);
 }


 function export_tracks()
 {   

	 // header data pass starts
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
	 // print_r($logo_details->logo);die;

		 $output = array();

		 $output['pageTitle'] = 'Export Tracks';
		 $output['logo'] = $get_logo;
		 $output['welcome_name'] = $admin_name;
		 $output['user_role'] = $user_role;
   
		  // access modules
		  $output['access'] = $this->admin_model->getAdminModules($admin_id);

	  // header data pass ends

	 // generate where
	 $where = 'where ';
	 $whereItems[] = "tracks.deleted = '0'";
	 $output['searchFromDate'] = '';
	 $output['searchToDate'] = '';
	 if (isset($_GET['search'])) {
		 if (isset($_GET['fromDate']) && strlen($_GET['fromDate']) > 0) {
			 $output['searchFromDate'] = $_GET['fromDate'];
			 //$whereItems[] = "tracks.added = '". urlencode($_GET['fromDate']) ."'";
			 $whereItems[] = "tracks.added > '" . $_GET['fromDate'] . "'";
		 }
		 if (isset($_GET['toDate']) && strlen($_GET['toDate']) > 0) {
			 $output['searchToDate'] = $_GET['toDate'];
			 $whereItems[] = "tracks.added < '" . $_GET['toDate'] . "'";
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
		 $sortOrder = "ASC";
		 $sortBy = "tracks.added";
		 $sort =  $sortBy . " " . $sortOrder;
		 // $num_records = $this->Tracksdb->getNumTracks($where, $sort);
		 // $numPages = (int) ($num_records / $limit);
		 // $reminder = ($num_records % $limit);
		 // if ($reminder > 0) {
		 //     $numPages = $numPages + 1;
		 // }
		 $result = $this->admin_model->getExportTracks_trm($where, $sort);
		 $output['tracks'] = $result['data'];

		 // download csv file:
		 $file_path = 'excel/tracks.csv';
		 $fp = fopen($file_path, 'w');
		 if ($result['numRows'] > 0) {
			 $row1[] = 'S. No.';
			 $row1[] = 'Artist';
			 $row1[] = 'Title';
			 $row1[] = 'Album';
			 $row1[] = 'Client';
			 $row1[] = 'Label';
			 $row1[] = 'More information';
			 $row1[] = 'Producer';
			 $row1[] = 'Image path';
			 $row1[] = 'Added';
			 $row1[] = 'Version';
			 $row1[] = 'mp3 path';
			 fputcsv($fp, $row1);
			 unset($row1);
			 $i = 1;
			 foreach ($result['data'] as $row) {
				 $mp = $this->admin_model->getTrackMp3s($row->id);
				 $row1[] = $i;
				 $row1[] = urldecode($row->artist);
				 $row1[] = ucfirst(urldecode($row->title));
				 $row1[] = ucfirst(urldecode($row->album));
				 $row1[] = urldecode($row->uname);
				 $row1[] = urldecode($row->label);
				 $row1[] = urldecode($row->moreinfo);
				 $row1[] = urldecode($row->producer);
				 $row1[] = asset('ImagesUp/' . $row->imgpage);
				 //$row1[] = asset('ImagesUp/'.$row->imgpage);
				 $row1[] = $row->added;
				 if ($mp['numRows'] > 0) {
					 $row1[] = urldecode($mp['data'][0]->version);
					 $row1[] = asset('AUDIO/' . $mp['data'][0]->location);
				 } else {
					 $row1[] = '';
					 $row1[] = '';
				 }
				 fputcsv($fp, $row1);
				 unset($row1);
				 $i++;
			 }
		 }
		 fclose($fp);
	 }
	 if (isset($_GET['delete'])) {
		 $output['alert_class'] = 'alert alert-success';
		 $output['alert_message'] = 'Track deleted successfully.';
	 } else if (isset($_GET['error'])) {
		 $output['alert_class'] = 'alert alert-danger';
		 $output['alert_message'] = 'Error occured, please try again.';
	 }


	 return view('admin/export_tracks', $output);
 }


 
 function download_tracks_data()
 {
	 dd('testing');
	// $this->load->helper('download');
	// force_download('excel/tracks.csv', NULL);
	
 }

 function manage_releasetype() {


	 // header data pass starts
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
	 // print_r($logo_details->logo);die;

		 $output = array();

		 $output['pageTitle'] = 'Manage Release Type';
		 $output['logo'] = $get_logo;
		 $output['welcome_name'] = $admin_name;
		 $output['user_role'] = $user_role;
   
		  // access modules
		  $output['access'] = $this->admin_model->getAdminModules($admin_id);

	  // header data pass ends
	
	 $releasetype = $this->admin_model->getAllReleaseType_trm();
	 
	 $output['releasetype'] = $releasetype;

	//echo "<pre>"; print_r($projectTracks); die;
	

	return view('admin/manage_releasetype', $output);
}

	 function add_releasetype() {

	 // header data pass starts
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
	 // print_r($logo_details->logo);die;

		 $output = array();

		 $output['pageTitle'] = 'Add Release Type';
		 $output['logo'] = $get_logo;
		 $output['welcome_name'] = $admin_name;
		 $output['user_role'] = $user_role;
   
		  // access modules
		  $output['access'] = $this->admin_model->getAdminModules($admin_id);

	  // header data pass ends
	 
	 
	 if (isset($_POST['addReleaseType'])) { 
		 
		 // echo "<pre>"; print_r($_POST);
		 $result = $this->admin_model->addReleaseType_trm($_POST);
		 if($result > 0) {
			 header("location: " . url("admin/add_releasetype?success=1"));
			 exit;
		 } else {
				 header("location: " . url("admin/add_releasetype?error=1"));
			 exit;
		 }
	 }
	 //echo "<pre>"; print_r($projectTracks); die;

	 if (isset($_GET['success'])) {
		 $output['alert_message'] = 'Record added successfully!';
		 $output['alert_class'] = 'alert alert-success';
	 }
	 

	 return view('admin/add_releasetype', $output);

	 }

	 public function Member_track_download()
        {
      
      	 //$trackid = Crypt::decryptString($_GET['tid']);
      	 //$trackid = $_GET['tid'];

      	 //$_GET['tid'] = $trackid;
    		
             // fb logout link
    
            //   $this->load->library('facebook');
    
            //   $logout_link = url('Logout');

                    $get_member_id = '';
        
                
        
                    $get_member_pkg = '';
        
               
                
                $track_added=array();
        
    
                // checking if user can download the song or not. All only if purple member or has reviewed
                $output['memberReview'] = $this->admin_model->getClientTrackReview_trm($_GET['tid']);
    
    
                if(!empty($_POST)  &&  $_POST['is_admin_view'] =="yes" && isset($_POST['adminID'])  ){
    
                }
                else{
                        if(!$output['memberReview']['numRows'] AND $get_member_pkg < 3)
                        {
                          header("location: Member_track_review?tid=".$_GET['tid']);
                          exit;
                        }
            
            
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
    		   
             $output['pageTitle'] = 'Member Track Download';
             // logo
             $output['logo'] = $this->admin_model->getLogo_trm();
    
    
            // add review
    
            if(isset($_POST['submitReview']))
    
            {
    
    
                 $result = $this->admin_model->addReview_trm($_POST,$_GET['tid']);
    
    
    
                 if($result>0)
    
                 {
    
                    header("location: ".url("Member_track_download?tid=".$_GET['tid']."&reviewAdded=1"));   exit;
    
    
    
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
    
            // if track id is invalid, takes to dashboard page
    
            $output['tracks'] = $this->admin_model->getReviewTracks_trm($where,$sort,$start,$limit);
           // print_r( $output['tracks'] );
    
            if($output['tracks']['numRows']<1)
    
            {
    
    
    
              header("location: Member_dashboard_newest_tracks");
    
              exit;
    
            }
    
            $arr1=$output['tracks']['data'];
				 $arr=json_decode(json_encode($arr1));
				
				// $arr= json_decode($arr1);
				$xx='';
			
				 
				 foreach ($arr as $key=>$value){

				     $query_loc=$this->memberAllDB_model->getTrackMp3s_fem($value->id);
				    //  pArr($query_loc);
				     foreach($query_loc['data'] as $key1=>$value1){
				        $xx= $value1->location;
				        
				     }
				     $arr[$key]->location=$xx;
				   
				 }
				 $output['tracks']['data']=$arr;
    
    
    
    
            $numMessages = $this->admin_model->getMemberInbox_trm($get_member_id);
    
          $output['numMessages'] = $numMessages['numRows'];
    
          $output['numMessages'] = $numMessages['numRows'];
    
    
    
    
    
          // right side bar
    
            $output['staffTracks'] = $this->admin_model->getStaffSelectedTracks_trm(0,4);
    
    
    
            if($output['staffTracks']['numRows']>0)
            {
    
                foreach($output['staffTracks']['data'] as $track)
        
                {
        
        
        
                   $output['trackData'][$track->id] = $this->admin_model->getTrackPlays_trm($track->id);
        
                   $row = $this->admin_model->getClientTrackReview_trm($track->id);
        
                   $output['reviews'][$track->id] = $row['numRows'];
        
        
        
                  // $output['mp3s'][$track->id] = $this->frontenddb->getTrackMp3s($track->id);
                 // $tid = $this->input->get('tid');
                  $tid = $_GET['tid'];
                //   $output['mp3s'][$track->id] = $this->admin_model->getTrackMp3s_trm($tid);
                 // dd( $output['mp3s'][$track->id]);
                 
                 
                 $query_track=$this->admin_model->getTrackMp3s_trm($tid);
                 		     foreach($query_track['data'] as $value){
			         $xx= $value->id;
    			     
    			     if(!in_array($xx,$track_added)){
    
    			         $output['mp3s'][$track->id] = $query_track;
    			         $track_added[]=$xx;
    			     }
    			  
    			  
			     }
        
        
        
                }
    
            }
    
    
    
            $output['youTracks'] = $this->admin_model->getYouSelectedTracks1($get_member_id,0,4);
    
    
            if($output['youTracks']['numRows']>0)
    
            {
    
            foreach($output['youTracks']['data'] as $track)
    
            {
    
    
    
               $output['trackData'][$track->id] = $this->admin_model->getTrackPlays_trm($track->id);
    
               $row = $this->admin_model->getClientTrackReview_trm($track->id);
    
               $output['reviews'][$track->id] = $row['numRows'];
    
    
    
               $output['versions'][$track->id] = $this->admin_model->getTrackMp3s1_trm($track->id);
    
            }
    
    
    
            }
    
            else
    
            {
    
    
    
            $output['youTracks'] = $this->admin_model->getYouSelectedTracks_trm(0,4);
    
    
    
            if($output['youTracks']['numRows']>0)
    
            {
    
            foreach($output['youTracks']['data'] as $track)
    
            {
    
    
    
               $output['trackData'][$track->id] = $this->admin_model->getTrackPlays_trm($track->id);
    
               $row = $this->admin_model->getClientTrackReview_trm($track->id);
    
               $output['reviews'][$track->id] = $row['numRows'];
    
    
    
               $output['versions'][$track->id] = $this->admin_model->getTrackMp3s_trm($track->id);
    
            }
    
            }
    
            }
    
    
    
            $output['tracks_footer'] = $this->admin_model->getMemberFooterTracks_trm();
    
    
    
    
    
    
    
             // subscription status
    
             $output['subscriptionStatus'] = 0;
    
             $output['package'] = '';
    
             $subscriptionInfo = $this->admin_model->getMemberSubscriptionStatus_trm($get_member_id);
    
             if($subscriptionInfo['numRows']>0)
    
             {
    
              $output['subscriptionStatus'] = 1;
    
    
    
              if($subscriptionInfo['data'][0]->package_Id==1)
    
              {
    
                $output['packageId'] = 1;
    
                $output['package'] = 'Silver Subscription';
    
    
              }
    
              else if($subscriptionInfo['data'][0]->package_Id==2)
    
              {
    
                $output['packageId'] = 2;
    
                $output['package'] = 'Gold Subscription';
    
              }
    
              else if($subscriptionInfo['data'][0]->package_Id==3)
    
              {
    
                $output['packageId'] = 3;
    
                $output['package'] = 'Purple Subscription';
    
              }
    
            }
    
    
            // echo '<pre/>'; print_r($output); exit;
    
    
    
    
    
            // $this->load->view('header_member.php',$output);
    
            // $this->load->view('member_track_download.php',$output);
    
            // $this->load->view('footer_member.php',$output);
           
            // $output['member_package']=7;
            // $output['member_package']='1';    --------------------uncomment when subscription enabled---------------------------
          
           

            return view('admin/admin_member_track_frontend', $output);
    
        }

        public function WhatIsDigiwaxx()
        {
            // ob_start();
            //  $this->load->library('session');
             $output = array();
    
             // fb logout link
            //   $this->load->library('facebook');
            //   $logout_link = base_url('Logout');
    
    
            //   if(isset($_COOKIE['fb_access_token']))
            //    {
            //      $logout_link = $this->facebook->logout_url();
            //    }
            //   $output['logout_link'] = $logout_link;
    
        //	 $this->load->model("frontenddb");
    
             // logo

             $logo_data = array(
                'logo_id' => 1,
                );
    
            $logo_details = DB::table('website_logo')
            ->where($logo_data)
            ->first();  
            
            $get_logo = $logo_details->logo;

             $output['logo'] = $get_logo;
             $output['banner'] = $this->admin_model->getBanner_trm(2);
    
    
             // meta content
           $meta =  $this->admin_model->getPageMeta_trm(2);
    
            $output['pageTitle'] = 'Digiwaxx';
            $output['meta_keywords'] = '';
            $output['meta_description'] = '';
    
            if($meta['numRows']>0)
            {
            $output['pageTitle'] = $meta['data'][0]->meta_tittle;
            $output['meta_keywords'] = $meta['data'][0]->meta_keywords;
            $output['meta_description'] = $meta['data'][0]->meta_description;
            }
    
    
            $output['bannerText'] = $this->admin_model->getBannerText_trm(2);
    
            $start = 0;
            $limit = 4;
            $where = "and tracks.deleted=0 and tracks.status='publish'";
            $sort = "tracks.id DESC";
    
            $output['newest_tracks1'] = $this->admin_model->getTracks_member($where,$sort,$start,$limit);
            $output['newest_tracks2'] = $this->admin_model->getTracks_member($where,$sort,4,4);
            $output['newest_tracks3'] = $this->admin_model->getTracks_member($where,$sort,8,4);
            
    // 		echo '<pre/>'; print_r($output); exit;
    
            // this week downloads
             date_default_timezone_set('America/Los_Angeles');
    
             $year = date('Y');
             $month = date('m');
             $date = date('d');
    
             $monday = date( 'Y-m-d', strtotime( 'monday this week' ) );
             $sunday = date( 'Y-m-d', strtotime( 'sunday this week' ) );
    
             $where1 = "where tracks.deleted = '0'  and track_member_downloads.downloadedDateTime > '". $monday ."' AND track_member_downloads.downloadedDateTime < '". $sunday ."' ";
    
             $output['downloads1'] = $this->admin_model->getTopDownloadTracks_trm(0);
             $output['downloads2'] = $this->admin_model->getTopDownloadTracks_trm(4);
             $output['downloads3'] = $this->admin_model->getTopDownloadTracks_trm(8);
            
             $output['notifications'] = $this->admin_model->getNotifications_trm();
             $output['youtube'] =  $this->admin_model->getYoutube_trm();
             $output['pageLinks'] = $this->admin_model->getPageLinks_trm(2);
    
            // $this->load->view('header',$output);
            // $this->load->view('what_is_digiwaxx',$output);
            // $this->load->view('footer');
    
            return view('pages.what_is_digiwaxx', $output);
    
        }


    public function viewChartsPage()

{

        // ob_start();

        $output = array();

      
       // fb logout link

        // $this->load->library('facebook');

        // $logout_link = base_url('Logout');

        

        // if(isset($_COOKIE['fb_access_token']))

        // {

        //     $logout_link = $this->facebook->logout_url();	

        // }

        // $output['logout_link'] = $logout_link;

        
        
        // logo 
        $output['logo'] = $this->admin_model->getLogo_trm(); 

    // meta content
    $meta =  $this->admin_model->getPageMeta_trm(4);
    
    $output['pageTitle'] = 'Digiwaxx';
    $output['meta_keywords'] = '';
    $output['meta_description'] = '';
    
    if($meta['numRows']>0)
    {
    $output['pageTitle'] = $meta['data'][0]->meta_tittle;
    $output['meta_keywords'] = $meta['data'][0]->meta_keywords;
    $output['meta_description'] = $meta['data'][0]->meta_description;
    }
        

        date_default_timezone_set('America/Los_Angeles');

        $year = date('Y');

        $month = date('m');

        $date = date('d');

        $monday = date( 'Y-m-d', strtotime( 'monday this week' ) );

        $sunday = date( 'Y-m-d', strtotime( 'sunday this week' ) );
      

        $where1 = "where tracks.deleted = '0'  and track_member_downloads.downloadedDateTime > '". $monday ."' AND track_member_downloads.downloadedDateTime < '". $sunday ."' ";

        $where2 = "where tracks.deleted = '0'  and track_member_downloads.downloadedDateTime like '". $year.'-'.$month ."%'";

        $where3 = "where tracks.deleted = '0'  and track_member_downloads.downloadedDateTime like '". $year."%'";

        $limit = 6;

        $sort = 'tracks_mp3s.downloads desc';   

        $where4 = "where tracks.deleted = '0'  and DATE_SUB(CURDATE(),INTERVAL 60 DAY) <= tracks.added";

        $sort4 = 'tracks.id desc';

        $limit4 = 6;

        // downloads pagination ajax request

        if((isset($_GET['page']))  && ($_GET['page']>0) && (isset($_GET['type'])) && ($_GET['type']>0) && ($_GET['type']<4))

        {

        if($_GET['type']==1)

        {

        $where = "where tracks.deleted = '0'  and track_member_downloads.downloadedDateTime > '". $monday ."' AND track_member_downloads.downloadedDateTime < '". $sunday ."' ";

        

        

        }

        else if($_GET['type']==2)

        {

    $where = "where tracks.deleted = '0'  and track_member_downloads.downloadedDateTime like '". $year.'-'.$month ."%'";

        

        }

        else if($_GET['type']==3)

        {

    $where = "where tracks.deleted = '0'  and track_member_downloads.downloadedDateTime like '". $year."%'";	

        

        }	

    

    $sort = 'tracks_mp3s.downloads desc';

    $start = ($_GET['page']*$limit)-$limit;  

    $num_records = $this->admin_model->getNumTopDownloadTracks($where,$sort); 

    

    $numPages = (int)($num_records/$limit); 

    $reminder = ($num_records%$limit);

    

    

    if($reminder>0)

    {

        $numPages = $numPages+1;

    }

    

    $currentPageNo = $_GET['page']; 

    $downloads = $this->admin_model->getTopDownloadChartTracks($where,$sort,$start,$limit); 

    /*if($downloads['numRows']>0)

        {

        foreach($downloads['data'] as $track)

        {

            $trackInfo[$track->id] = $this->admin_model->getTrackPlays($track->id);

        }

        }

    */
                if($numPages>1) {

                        

                        $firstLink = $currentPageNo-1;

                        if($_GET['page']==1)

                        {

                        $firstLink = $currentPageNo;

                        }

                        

                        $lastLink = $currentPageNo+1;

                        if($_GET['page']==$numPages)

                        {

                        $lastLink = $currentPageNo;

                        }

                        

                        ?>

                        <div class="fby-blk clearfix"><div style="float:right;">

            <div class="pgm">

                <?php echo $start+1; ?> - <?php echo $start+$limit; ?> OF <?php echo $num_records; ?>

            </div>

            

            <div class="tnav clearfix">

            <span><a href="javascript:void()" onclick="getCharts('<?php echo $firstLink; ?>','<?php echo $_GET['type']; ?>','<?php echo $_GET['divId']; ?>')"><i class="fa  fa-angle-double-left"></i></a></span>

                        <span class="num"><?php echo $currentPageNo; ?></span>

                <span><a href="javascript:void()" onclick="getCharts('<?php echo $lastLink; ?>','<?php echo $_GET['type']; ?>','<?php echo $_GET['divId']; ?>')"><i class="fa  fa-angle-double-right"></i></a></span>

            </div>

            </div></div>

            

            <?php } 

    

            $i=$start+1; foreach($downloads['data'] as $track) { 

						if(strlen($track->thumb)>4)
							 {
								if (file_exists(public_path('thumbs/'.$track->thumb))){
									$img = asset('public/thumbs/'.$track->thumb);  
								 }else{
									$img = asset('public/images/tdpic1.jpg'); 
								 }								 
							   
							 }
							 else if(strlen($track->imgpage)>4)
							 {
								 if (file_exists(public_path('ImagesUp/'.$track->imgpage))){
									$img = asset('public/ImagesUp/'.$track->imgpage);  
								 }else{
									$img = asset('public/images/tdpic1.jpg'); 
								 }
							   
							 }
							 else 
							 {
							   $img = asset('public/images/tdpic1.jpg');
							 }

                            ?>

                                    

                                    <div class="record">

                                        <div class="row">

                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 c1">

                                                <?php if($i<10) { $i = '0'.$i; } echo $i; ?>

                                            </div>

                                            

                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 c2">

                                                <a href="<?php echo url("Member_track_review?tid=".$track->id); ?>">

                                                    <img class="img-responsive" src="<?php echo $img; ?>">

                                                </a>

                                            </div>

                                            

                                            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5 c3">

                                                <p><?php echo urldecode($track->title); ?></p>

                                                <p class="alb"><?php echo urldecode($track->album); ?></p>

                                            </div>

                                            

                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 c4">

                                                <p><?php echo $track->downloads; // echo $downloads['downloadsData'][$track->mp3Id][0]->downloads; ?></p>

                                                <?php if(!(isset($_COOKIE['clientId']))) { ?>

                                                <p class="dwd"><a href="<?php echo url('Member_track_review?tid='.$track->id); ?>">

                                                    <i class="fa fa-arrow-circle-o-down"></i></a></p>

                                                <?php } ?>

                                            </div>

                                        </div>

                                        </div><!-- eof record -->

                                        <?php $i++; }                             

                                        

        

        exit;

        }

        

        // newest pagination ajax request

        if((isset($_GET['page']))  && ($_GET['page']>0) && (isset($_GET['type'])) && ($_GET['type']==4))

        {

    

    

    $start4 = ($_GET['page']*$limit4)-$limit4;  

    $num_records4 = $this->admin_model->getNumTracks_trm($where4,$sort4); 

    

    $numPages4 = (int)($num_records4/$limit4); 

    $reminder4 = ($num_records4%$limit4);

    

    

    if($reminder4>0)

    {

        $numPages4 = $numPages4+1;

    }

    

    $currentPageNo4 = $_GET['page']; 

    

    $newest = $this->admin_model->getNewestTracks($where4,$sort4,$start4,$limit4); 

    

                                if($numPages4>1) {

                                        

                                        $firstLink4 = $currentPageNo4-1;

                                        if($_GET['page']==1)

                                        {

                                        $firstLink4 = $currentPageNo4;

                                        }

                                        

                                        $lastLink4 = $currentPageNo4+1;

                                        if($_GET['page']==$numPages4)

                                        {

                                        $lastLink4 = $currentPageNo4;

                                        }

                                        

                                        ?>

                                        <div class="fby-blk clearfix"><div style="float:right;">

                            <div class="pgm">

                                <?php echo $start4+1; ?> - <?php echo $start4+$limit4; ?> OF <?php echo $num_records4; ?>

                            </div>

                            

                            <div class="tnav clearfix">

                            <span><a href="javascript:void()" onclick="getCharts('<?php echo $firstLink4; ?>','4','nr-tab')"><i class="fa  fa-angle-double-left"></i></a></span>

                                        <span class="num"><?php echo $currentPageNo4; ?></span>

                                <span><a href="javascript:void()" onclick="getCharts('<?php echo $lastLink4; ?>','4','nr-tab')"><i class="fa  fa-angle-double-right"></i></a></span>

                            </div>

                            </div></div>

                            

                            <?php } 

    

        $i=1; foreach($newest['data'] as $track) { 

 						if(strlen($track->thumb)>4)
							 {
								if (file_exists(public_path('thumbs/'.$track->thumb))){
									$img = asset('public/thumbs/'.$track->thumb);  
								 }else{
									$img = asset('public/images/tdpic1.jpg'); 
								 }								 
							   
							 }
							 else if(strlen($track->imgpage)>4)
							 {
								 if (file_exists(public_path('ImagesUp/'.$track->imgpage))){
									$img = asset('public/ImagesUp/'.$track->imgpage);  
								 }else{
									$img = asset('public/images/tdpic1.jpg'); 
								 }
							   
							 }
							 else 
							 {
							   $img = asset('public/images/tdpic1.jpg');
							 }

                            ?>

                            

                                    <div class="record">

                                        <div class="row">

                                            <div class="col-lg-3 col-lg-offset-1 col-md-3 col-md-offset-1 col-sm-3 col-sm-offset-1 col-xs-3 col-xs-offset-1 c2">

                                            

                                            

                                            

                                            <a href="#"><img class="img-responsive" src="<?php echo $img; ?>"></a>

                                            </div>

                                            

                                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8 c3">

                                                <p><?php echo urldecode($track->title); ?></p>

                                                <p class="alb"><?php echo urldecode($track->album); ?></p>

                                            </div>

                                        </div>

                                        </div><!-- eof record -->

                                        <?php $i++; }  

                                        

        

        exit;

        } 



    if(isset($_COOKIE['clientId']))

    { 

    $subscriptionInfo = $this->admin_model->getSubscriptionStatus($_COOKIE['clientId']); 

    if($subscriptionInfo['numRows']>0)

    {

        $output['subscriptionStatus'] = 1;

        

        if($subscriptionInfo['data'][0]->packageId==1)

        {

        $output['package'] = 'BASIC SUBSCRIPTION';

        $output['displayDashboard'] = 0;

        

        

        }

        else if($subscriptionInfo['data'][0]->packageId==2)

        {

        $output['package'] = 'ADVANCED SUBSCRIPTION';

        $output['displayDashboard'] = 1;

        

        

        }

    }

    else

    {

        $output['subscriptionStatus'] = 0;

        $output['package'] = '';

        $output['displayDashboard'] = 0;

            

        

    }

    }

    else

    {

        $output['packageId'] = 0;

        $output['subscriptionStatus'] = 0;

        $output['package'] = '';

        $output['displayDashboard'] = 0;

            

        

    }



    

        $output['bannerText'] = $this->admin_model->getBannerText_trm(4); 
       // dd($output['bannerText']);
        

        

        $start = 0; 

        

    // weekly top downloads

    

    $num_records1 = $this->admin_model->getNumTopDownloadTracks($where1,$sort); 

    

    $numPages1 = (int)($num_records1/$limit); 

    $reminder1 = ($num_records1%$limit);

    

    if($reminder1>0)

    {

        $numPages1 = $numPages1+1;

    }



    $output['numPages1'] = $numPages1;

    $output['start1'] = $start;

    $output['limit1'] = $limit;

    $output['num_records1'] = $num_records1;

    $output['currentPageNo1'] = 1;

        

        $output['weekDownloads'] = $this->admin_model->getTopDownloadChartTracks($where1,$sort,$start,$limit);

        /*if($output['weekDownloads']['numRows']>0)

        {

        foreach($output['weekDownloads']['data'] as $track)

        {

            $output['trackInfo'][$track->id] = $this->admin_model->getTrackPlays($track->id);

        }

        }*/

        

    // monthly top downloads

        

    $num_records2 = $this->admin_model->getNumTopDownloadTracks($where2,$sort); 

    

    $numPages2 = (int)($num_records2/$limit); 

    $reminder2 = ($num_records2%$limit);

    

    if($reminder2>0)

    {

        $numPages2 = $numPages2+1;

    }



    $output['numPages2'] = $numPages2;

    $output['start2'] = $start;

    $output['limit2'] = $limit;

    $output['num_records2'] = $num_records2;

    $output['currentPageNo2'] = 1;

        

    $output['monthDownloads'] = $this->admin_model->getTopDownloadChartTracks($where2,$sort,$start,$limit);

    /* if($output['monthDownloads']['numRows']>0)

        {

        foreach($output['monthDownloads']['data'] as $track)

        {

            $output['trackInfo'][$track->id] = $this->admin_model->getTrackPlays($track->id);

        }

        }

        */

    

    // yearly top downloads

    

    $num_records3 = $this->admin_model->getNumTopDownloadTracks($where3,$sort); 

    

    $numPages3 = (int)($num_records3/$limit); 

    $reminder3 = ($num_records3%$limit);

    

    if($reminder3>0)

    {

        $numPages3 = $numPages3+1;

    }



    $output['numPages3'] = $numPages3;

    $output['start3'] = $start;

    $output['limit3'] = $limit;

    $output['num_records3'] = $num_records3;

    $output['currentPageNo3'] = 1;

        

        $output['yearDownloads'] = $this->admin_model->getTopDownloadChartTracks($where3,$sort,$start,$limit);

    /* if($output['yearDownloads']['numRows']>0)

        {

        foreach($output['yearDownloads']['data'] as $track)

        {

            $output['trackInfo'][$track->id] = $this->admin_model->getTrackPlays($track->id);

        }

        }*/

    

    

    

    // newest

    $start4 = 0;

    $num_records4 = $this->admin_model->getNumTracks_trm($where4,$sort4); 

    

    $numPages4 = (int)($num_records4/$limit4); 

    $reminder4 = ($num_records4%$limit4);

    

    

    if($reminder4>0)

    {

        $numPages4 = $numPages4+1;

    }



    $output['numPages4'] = $numPages4;

    $output['start4'] = $start4;

    $output['limit4'] = $limit4;

    $output['num_records4'] = $num_records4;

    $output['currentPageNo4'] = 1;

    

    

    $output['newest'] = $this->admin_model->getNewestTracks($where4,$sort4,$start4,$limit4);  
    $output['notifications'] = $this->admin_model->getNotifications_trm();  
    
    //pArr($output);die('---YSYSY');
    return view('pages.charts', $output);

    // $this->load->view('header.php',$output);
    // $this->load->view('charts.php',$output);
    // $this->load->view('footer.php');

}


function Digicoins()

{

     // header data pass starts
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
     // print_r($logo_details->logo);die;

         $output = array();

         $output['pageTitle'] = 'Member Digicoins';
         $output['logo'] = $get_logo;
         $output['welcome_name'] = $admin_name;
         $output['user_role'] = $user_role;
   
          // access modules
          $output['access'] = $this->admin_model->getAdminModules($admin_id);

      // header data pass ends
        

            // generate where 

            $where = 'where ';

            $whereItems = array();

            $whereItems[] = "buy_digicoins.status = '1'";

            

            $output['searchPackage'] = '';

            $output['searchUsername'] = '';

            

            if(isset($_GET['search']))

            {

            

            if(isset($_GET['package']) && $_GET['package']>0)

            {

                $output['searchPackage'] = $_GET['package'];

                $whereItems[] = "member_subscriptions.package_Id = '". $_GET['package'] ."'";

            }

            

            if(isset($_GET['username']) && strlen($_GET['username'])>0)

            {

                $output['searchUsername'] = $_GET['username'];

                $whereItems[] = "members.uname = '". $_GET['username'] ."'";

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

            $sortBy = "buy_digicoins.buy_id";

            //$output['sortBy'] = 'paidOn';

            $output['sortOrder'] = 2;

            

            if(isset($_GET['sortOrder']))

            {

            $output['sortOrder'] = $_GET['sortOrder'];

            

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

            

            

            

        $num_records = $this->admin_model->getNumMemberDigicoins($where,$sort); 

        $numPages = (int)($num_records/$limit); 

        $reminder = ($num_records%$limit);

        

        if($reminder>0)

        {

            $numPages = $numPages+1;

        }



        $output['numPages'] = $numPages;

        $output['start'] = $start;

        $output['currentPageNo'] = $currentPageNo;

        $output['currentPage'] = 'digicoins';

        

        

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

            

        $output['digicoins'] = $this->admin_model->getMemberDigicoins($where,$sort,$start,$limit); 

        

        

        if($output['digicoins']['numRows']>0)

        {

            foreach($output['digicoins']['data'] as $digicoin)

            {

            if($digicoin->user_type==1)

            {

                $user_detais = $this->admin_model->getClientInfo($digicoin->user_id); 
                
                if(!empty($user_detais)){

                    $output['user_details'][$digicoin->buy_id] = $user_detais[0]->name;

                }

                else{

                    $output['user_details'][$digicoin->buy_id] = '';
                }

             

            }

            else if($digicoin->user_type==2)

            {

                $user_detais = $this->admin_model->getMemberInfo($digicoin->user_id);  


                if(!empty($user_detais)){

                    $output['user_details'][$digicoin->buy_id] = $user_detais[0]->fname;

                }
                else{

                    $output['user_details'][$digicoin->buy_id]  = '';

                }

               

            }

            }

        }

        // $this->load->view('admin/header',$headerOutput);

        // $this->load->view('digicoins',$output);

        // $this->load->view('admin/footer');

        return view('admin/digicoins', $output);

} 


    function products_lisitng()

    {

        // dd('dsdssa');

    // header data pass starts
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
        // print_r($logo_details->logo);die;

            $output = array();

            $output['pageTitle'] = 'Products';
            $output['logo'] = $get_logo;
            $output['welcome_name'] = $admin_name;
            $output['user_role'] = $user_role;
    
            // access modules
            $output['access'] = $this->admin_model->getAdminModules($admin_id);

        // header data pass ends

            
            // delete product

            if(isset($_GET['did']))

            {

        $result = $this->admin_model->deleteProduct($_GET['did']); 	 

        

        if($result>0)

        {

        header("location: store?delete=success");

        }

        else

        {

            header("location: store?error=1");

        }

        

        exit;

        }

            

            // generate where

            $where = 'where ';

            $whereItems = array();

            

            $whereItems[] = "deleted = '0'";

            

            $output['searchProductName'] = '';

            

            

        if(isset($_GET['search']))

            {

            

            if(isset($_GET['product']) && strlen($_GET['product'])>0)

            {

                $output['searchProduct'] = $_GET['product'];

                $whereItems[] = "products.name like '%". $_GET['product'] ."%'";

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

            $sortBy = "products.product_id";

            $output['sortBy'] = 'products.product_id';

            $output['sortOrder'] = 2;

            

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

            

            

            

            // pagination

            $start = 0; 

            $limit = 10;

            if(isset($_GET['records']))

            {

            $limit = $_GET['records'];

            }

            $output['numRecords'] = $limit;

            

            $currentPageNo = 1;

            

            if(isset($_GET['page']) && $_GET['page']>1)

            {

                $start = ($_GET['page']*$limit)-$limit;  

            }

            

            

            if(isset($_GET['page']))

            {

                $currentPageNo = $_GET['page']; 

            }

            

        $num_records = $this->admin_model->getNumProducts($where,$sort); 

        $numPages = (int)($num_records/$limit); 

        $reminder = ($num_records%$limit);

        

        if($reminder>0)

        {

            $numPages = $numPages+1;

        }



        $output['numPages'] = $numPages;

        $output['start'] = $start;

        $output['currentPageNo'] = $currentPageNo;

        $output['currentPage'] = 'store';

        

        

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

            $result = $this->admin_model->getProducts($where,$sort,$start,$limit); 

            $output['products'] = $result['data'];

        

            $today = date('Y-m-d');

            

            if($result['numRows']>0)

            {

                foreach($result['data'] as $product)

                {

                

                $where = "where product_id = '". $product->product_id ."' and applies_from <= '". $today ."' order by applies_from desc limit 0, 1";

                // $output['digicoins'][$product->product_id] =  $this->admin_model->getProductPrices($where); 

                 $getProductPrices_results=  $this->admin_model->getProductPrices($where); 
                // dd($getProductPrices_results);
                    if(!empty($getProductPrices_results)){

                        $output['digicoins'][$product->product_id] = $getProductPrices_results;
                    }
                    else{

                        $output['digicoins'][$product->product_id] = '';

                    }

                }

            }

            

            

            if(isset($_GET['delete']))				

                {

                

                $output['alert_class'] = 'alert alert-success';

                $output['alert_message'] = 'Product deleted successfully.';

                

                }

                else if(isset($_GET['error']))				

                {

                $output['alert_class'] = 'alert alert-danger';

                $output['alert_message'] = 'Error occured, please try again.';

                }

            

                //    $this->load->view('admin/header',$headerOutput);

                //    $this->load->view('products',$output);

                //    $this->load->view('admin/footer');

                return view('admin/products', $output);

}



    function product_review_report()

	{   

        // header data pass starts
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
        // print_r($logo_details->logo);die;

            $output = array();

            $output['pageTitle'] = 'Product Review Report';
            $output['logo'] = $get_logo;
            $output['welcome_name'] = $admin_name;
            $output['user_role'] = $user_role;
        
        // access modules
        $output['access'] = $this->admin_model->getAdminModules($admin_id);

            // header data pass ends
		

		 // $this->load->model("tracksdb");


		if(isset($memId))

		{


		$ajaxOutput['members'] = $this->admin_model->getMemberInfo_prm($memId); 		

		$ajaxOutput['production'] = $this->admin_model->getMemberProductionInfo($memId); 		

		$ajaxOutput['special'] = $this->admin_model->getMemberSpecialInfo($memId); 		

		$ajaxOutput['promoter'] = $this->admin_model->getMemberPromoterInfo($memId); 

        $ajaxOutput['clothing'] = $this->admin_model->getMemberClothingInfo($memId); 		

		$ajaxOutput['management'] = $this->admin_model->getMemberManagementInfo($memId); 			

		$ajaxOutput['record'] = $this->admin_model->getMemberRecordInfo($memId); 			

		$ajaxOutput['media'] = $this->admin_model->getMemberMediaInfo($memId); 			

		$ajaxOutput['radio'] = $this->admin_model->getMemberRadioInfo($memId); 			

		$ajaxOutput['socialInfo'] = $this->admin_model->getMemberSocialInfo($memId); 

		

        //	$this->load->view('member.php',$ajaxOutput);	
        
        return view('admin/member', $ajaxOutput);

		 exit;

		}

	    $output['questions'] = $this->admin_model->getProductQuestions($_GET['pid']); 

	   

	    if($output['questions']['numRows']>0) {

		foreach($output['questions']['data'] as $question)

		{

		

		

		 if(strcmp($question->type,'text')==0)

		 {

	       $output['textData'][$question->question_id] = $this->admin_model->getTextData($_GET['pid'],$question->question_id); 	   

		 }

		 else

		 {

		   $output['graphData'][$question->question_id] = $this->admin_model->getGraphData($_GET['pid'],$question->question_id); 	   

		   

		   

		   if($output['graphData'][$question->question_id]['numRows']>0)

		   {

		      foreach($output['graphData'][$question->question_id]['data'] as $answer)

			  {

				 $output['answersData'][$answer->answer_id] = $this->admin_model->getGraphDataAnswers($_GET['pid'],$question->question_id,$answer->answer_id); 	   

			  }

		   

		   }

		 }

		}

		}
	   

	//    $this->load->view('admin/header',$headerOutput);

	//    $this->load->view('product_review_report',$output);

	//    $this->load->view('admin/footer');

       return view('admin/product_review_report', $output);

	

	}


    function product_digicoins()

	{   

         // header data pass starts
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
         // print_r($logo_details->logo);die;
 
             $output = array();
 
             $output['pageTitle'] = 'Product Digicoins';
             $output['logo'] = $get_logo;
             $output['welcome_name'] = $admin_name;
             $output['user_role'] = $user_role;
         
         // access modules
         $output['access'] = $this->admin_model->getAdminModules($admin_id);
 
             // header data pass ends
		 

		 // delete digicoins

		 if(isset($_GET['did']) && isset($_GET['del']) && $_GET['del']==1)

		 {

		 $result = $this->admin_model->deleteProductPrice($_GET['did']); 	 

			  if($result>0)

			  {

			   header("location: product_digicoins?pid=".$_GET['pid']."&delete_price=success");

			  }

			  else

			  {

			   header("location: product_digicoins?pid=".$_GET['pid']."&error=1");

			  }

		 }



         // delete discount

		 if(isset($_GET['did']) && isset($_GET['del']) && $_GET['del']==2)

		 {

		 $result = $this->admin_model->deleteProductDiscount($_GET['did']); 	 

			  if($result>0)

			  {

			   header("location: product_digicoins?pid=".$_GET['pid']."&delete_discount=success");

			  }

			  else

			  {

			   header("location: product_digicoins?pid=".$_GET['pid']."&error=1");

			  }

		 }

		 

		 // add digicoins

		 if(isset($_POST['addDigicoins']) && isset($_GET['pid']))

		 {

		 

		   $result = $this->admin_model->addDigicoins($_POST,$_GET['pid']); 

		   

		   if($result>0)

		   {

		     header("location: ".url("admin/store/product_digicoins?pid=".$_GET['pid']."&success=1"));   

		   }

		   else

		   {

		     header("location: ".url("admin/store/product_digicoins?pid=".$_GET['pid']."&error=1"));   

		   }

		   exit;

		 }

		 

		 // add discount

		 if(isset($_POST['addDiscount']) && isset($_GET['pid']))

		 {

		 

		   $result = $this->admin_model->addDiscount($_POST,$_GET['pid']); 

		   

		   if($result>0)

		   {

		     header("location: ".url("admin/store/product_digicoins?pid=".$_GET['pid']."&discount_success=1"));   

		   }

		   else

		   {

		     header("location: ".url("admin/store/product_digicoins?pid=".$_GET['pid']."&error=1"));   

		   }

		   exit;

		 }

		

		

		

	   

	   	$where = "where products.product_id = '". $_GET['pid'] ."'";

		$output['product'] = $this->admin_model->getProduct($where); 

		

		$where1 = "where product_price.product_id = '". $_GET['pid'] ."'";	

	    $output['prices'] = $this->admin_model->getProductPrices($where1); 

		

		$where2 = "where product_discount.product_id = '". $_GET['pid'] ."'";	

	    $output['discounts'] = $this->admin_model->getProductDiscounts($where2); 

		

	   

	   	    if(isset($_GET['success']))				

			{

			  

			   $output['alert_class'] = 'alert alert-success';

			   $output['alert_message'] = 'Digicoins added successfully.';

			

			}

			else if(isset($_GET['discount_success']))				

			{

			  

			   $output['alert_class'] = 'alert alert-success';

			   $output['alert_message'] = 'Discount added successfully.';

			

			}

			else if(isset($_GET['delete_price']))				

			{

			  

			   $output['alert_class'] = 'alert alert-success';

			   $output['alert_message'] = 'Digicoin deleted successfully.';

			

			}

			else if(isset($_GET['delete_discount']))				

			{

			  

			   $output['alert_class'] = 'alert alert-success';

			   $output['alert_message'] = 'Discount deleted successfully.';

			

			}

			else if(isset($_GET['error']))				

			{

			   $output['alert_class'] = 'alert alert-danger';

			   $output['alert_message'] = 'Error occured, please try again.';

			}

		

		

	  $output['today'] = date('Y-m-d');

	   

	//    $this->load->view('admin/header',$headerOutput);

	//    $this->load->view('product_digicoins',$output);

	//    $this->load->view('admin/footer');

       return view('admin/product_digicoins', $output);

    }

    function product_review_options()

	{   

         // header data pass starts
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
         // print_r($logo_details->logo);die;
 
             $output = array();
 
             $output['pageTitle'] = 'Product Review Options';
             $output['logo'] = $get_logo;
             $output['welcome_name'] = $admin_name;
             $output['user_role'] = $user_role;
         
         // access modules
         $output['access'] = $this->admin_model->getAdminModules($admin_id);
 
             // header data pass ends

	   

		 // delete question

		 if(isset($_GET['did']) && isset($_GET['pid'])) 

		 {

		 

		 $result = $this->admin_model->deleteQuestion($_GET['did'],$_GET['pid']); 	 

			  if($result>0)

			  {

			//    header("location: product_review_options?pid=".$_GET['pid']."&delete=success");
               header("location: " . url("admin/store/product_review_options?pid=".$_GET['pid']."&delete=success"));

			  }

			  else

			  {

			//    header("location: product_review_options?pid=".$_GET['pid']."&error=1");
               header("location: " . url("admin/store/product_review_options?pid=".$_GET['pid']."&error=1"));

			  }

		 }


		// generate where

		$where = 'where ';

		$whereItems = array();

		$whereItems[] = "product_product_questions.product_id = '". $_GET['pid'] ."'";

		

		$output['searchProductName'] = '';

		

	  if(isset($_GET['search']))

		{

		  if(isset($_GET['productName']) && strlen($_GET['productName'])>0)

		   {

		     $output['searchProductName'] = $_GET['productName'];

			 $whereItems[] = "products.name like '%". $_GET['productName'] ."%'";

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

		$sortBy = "product_product_questions.order";

		

		$output['sortBy'] = 'order_id';

		$output['sortOrder'] = 2;

		

		/*

		if(isset($_GET['sortBy']) && isset($_GET['sortOrder']))

		{

		   $output['sortBy'] = $_GET['sortBy'];

		   $output['sortOrder'] = $_GET['sortOrder'];

		   

		   

		   if(strcmp($_GET['sortBy'],'product_id')==0)

		   {

		    

			 $sortBy = "product_id";

		   }

		   else if(strcmp($_GET['sortBy'],'name')==0)

		   {

		    

			 $sortBy = "name";

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

		*/

		$sort =  $sortBy." ".$sortOrder;

		

		

		

		// pagination

   		$start = 0; 

		$limit = 10;

		$currentPageNo = 1;

		

		if(isset($_GET['page']) && $_GET['page']>1)

		 {

			$start = ($_GET['page']*$limit)-$limit;  

		 }



		 if(isset($_GET['page']))

		 {

			$currentPageNo = $_GET['page']; 

		 }



	 $output['currentPage'] = 'product_review_options';


		$output['questions'] = $this->admin_model->getQuestions($where,$sort,$start,$limit); 

		

	     if(isset($_GET['added']))				

			{

			  

			   $output['alert_class'] = 'alert alert-success';

			   $output['alert_message'] = 'Question added successfully.';

			

			}

			else  if(isset($_GET['delete']))				

			{

			  

			   $output['alert_class'] = 'alert alert-success';

			   $output['alert_message'] = 'Question deleted successfully.';

			

			}

			else if(isset($_GET['error']))				

			{

			   $output['alert_class'] = 'alert alert-danger';

			   $output['alert_message'] = 'Error occured, please try again.';

			}

	//    $this->load->view('admin/header',$headerOutput);

	//    $this->load->view('product_review_options',$output);

	//    $this->load->view('admin/footer');

    return view('admin/product_review_options', $output);

	

	}


    function view_question()

	{


        // header data pass starts
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
        // print_r($logo_details->logo);die;

            $output = array();

            $output['pageTitle'] = 'Product Review Options';
            $output['logo'] = $get_logo;
            $output['welcome_name'] = $admin_name;
            $output['user_role'] = $user_role;
        
        // access modules
        $output['access'] = $this->admin_model->getAdminModules($admin_id);

            // header data pass ends
		 

		// generate where

		$where = 'where ';

		$whereItems = array();

		$whereItems[] = "product_product_questions.product_id = '". $_GET['pid'] ."' and product_product_questions.question_id = '". $_GET['qid'] ."'";

		

	

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

		$sort = "product_product_questions.order DESC";



		

   		//	 $output['currentPage'] = 'product_review_options';

	 

	 $output['questions'] = $this->admin_model->getQuestions($where,$sort); 

	 

	 // get options

	 if((strcmp($output['questions']['data'][0]->type,'check')==0) || (strcmp($output['questions']['data'][0]->type,'radio')==0)) { 

	 

	   $where1 = "where product_question_answers.question_id = '". $_GET['qid'] ."' and product_question_answers.product_id = '". $_GET['pid'] ."'";

	   $sort1 = "product_answers.answer ASC";

	   $output['options'] = $this->admin_model->getQuestionOptions($where1,$sort1); 

	   

	 }

		

	//    $this->load->view('admin/header',$headerOutput);

	//    $this->load->view('view_question',$output);

	//    $this->load->view('admin/footer');

       return view('admin/view_question', $output);
	}


    function edit_question()

	{   
        // header data pass starts
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
        // print_r($logo_details->logo);die;

            $output = array();

            $output['pageTitle'] = 'Product Review Options';
            $output['logo'] = $get_logo;
            $output['welcome_name'] = $admin_name;
            $output['user_role'] = $user_role;
        
        // access modules
        $output['access'] = $this->admin_model->getAdminModules($admin_id);

            // header data pass ends
        

		 if(isset($_POST['updateQuestion']))	 

		 {

		   $result = $this->admin_model->updateQuestion($_POST,$_GET['pid'],$_GET['qid']); 

		   

		   if($result>0)

		   {

		     header("location: ".url("admin/store/edit_question?pid=".$_GET['pid']."&qid=".$_GET['qid']."&updated=1"));   exit;

		   }

		   else

		   {

		     header("location: ".url("admin/store/edit_question?pid=".$_GET['pid']."&qid=".$_GET['qid']."&error=1"));   exit;

		   }

		   

		 }		 

		 

		// generate where

		$where = 'where ';

		$whereItems = array();

		$whereItems[] = "product_product_questions.product_id = '". $_GET['pid'] ."' and product_product_questions.question_id = '". $_GET['qid'] ."'";

		

	

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

		$sort = "product_product_questions.order DESC";



		

   		//	 $output['currentPage'] = 'product_review_options';

	 

	 $output['questions'] = $this->admin_model->getQuestions($where,$sort); 

	 

	 // get options

	 if((strcmp($output['questions']['data'][0]->type,'check')==0) || (strcmp($output['questions']['data'][0]->type,'radio')==0)) { 

	 

	   $where1 = "where product_question_answers.question_id = '". $_GET['qid'] ."' and product_question_answers.product_id = '". $_GET['pid'] ."'";

	   $sort1 = "product_answers.answer ASC";

	   $output['options'] = $this->admin_model->getQuestionOptions($where1,$sort1); 

	   

	 }

	 

	 

	      if(isset($_GET['updated']))				

			{

			  

			   $output['alert_class'] = 'alert alert-success';

			   $output['alert_message'] = 'Question updated successfully.';

			

			}

			else if(isset($_GET['error']))				

			{

			   $output['alert_class'] = 'alert alert-danger';

			   $output['alert_message'] = 'Error occured, please try again.';

			}

		

		

	//    $this->load->view('admin/header',$headerOutput);

	//    $this->load->view('edit_question',$output);

	//    $this->load->view('admin/footer');

    return view('admin/edit_question', $output);

	}


    function edit_product(Request $request)

	{   

         // header data pass starts
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
         // print_r($logo_details->logo);die;
 
             $output = array();
 
             $output['pageTitle'] = 'Edit Product';
             $output['logo'] = $get_logo;
             $output['welcome_name'] = $admin_name;
             $output['user_role'] = $user_role;
         
         // access modules
         $output['access'] = $this->admin_model->getAdminModules($admin_id);
 
             // header data pass ends



		 if(isset($_POST['updateProduct']) && isset($_GET['pid']))

		 {

		
		   $result = $this->admin_model->updateProduct($_POST,$_GET['pid']); 


		   if($result>0)

		   {


		    $date_time_array = getdate(time());

		   $imgName =	$date_time_array["year"].$date_time_array["mon"].$date_time_array["mday"].$date_time_array["hours"].$date_time_array["minutes"].$date_time_array["seconds"];

		

		 // email image upload

		 if(isset($_FILES['emailImage']['name']) && strlen($_FILES['emailImage']['name'])>4)

		 {       

                    $emailImage = $request->file('emailImage') ;
      
                    //Display File Name
                    $filename = $emailImage->getClientOriginalName();
                    // echo 'File Name: '.$filename;
                  
                    
                    //Display File Extension
                    $file_extension = $emailImage->getClientOriginalExtension();
                    // echo 'File Extension: '.$file_extension;
                  
                    
                    //Display File Real Path
                    //$real_path = $emailImage->getRealPath();
                    // echo 'File Real Path: '.$real_path;
                  
                    
                    //Display File Size
                  //  $file_size = $emailImage->getSize();
                    // echo 'File Size: '.$file_size;
                  
                    
                    //Display File Mime Type
                  //  $file_mime_type = $emailImage->getMimeType();
                    // echo 'File Mime Type: '.$file_mime_type;
                  
                    $destination_path = base_path('product_images/');
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

                    $image_name = $_GET['pid'] . '_' . $imgName . '.'.$file_extension;
                    // $image_name = preg_replace('/[^a-zA-Z0-9_.]/', '_', $image_name);

                    $uploaded_data = $emailImage->move( $destination_path , $image_name );

                    if( !empty( $uploaded_data )){
                     // die('file');

                      $this->admin_model->addEmailImage_prm($_GET['pid'], $image_name);


                    }

                    else{

                      header("location: " . url("admin/store/edit_product?aid=" . $_GET['pid'] . "&error=1"));
                      exit;
                     
                    }

                        //     $config['upload_path']          = './product_images/';

                        //     $config['allowed_types']        = 'gif|jpg|png';

                        //     $config['max_size']             = 100;

                        //     $config['max_width']            = 1024;

                        //     $config['max_height']           = 768;

                        //     $config['file_name']           = $_GET['pid'].'_'.$imgName;

                            

                        //     $ext = explode('.',$_FILES['emailImage']['name']);

                        //     $count = count($ext);

                        //     $ext = $ext[$count-1];



                        // //  $this->load->library('upload', $config);

                        // $this->upload->initialize($config);



                        //     if ( ! $this->upload->do_upload('emailImage'))

                        //     {

                        //             $error = array('error' => $this->upload->display_errors());



                        //         //    $this->load->view('upload_form', $error);

                        //     }

                        //     else

                        //     {

                        //     $data = array('upload_data' => $this->upload->data());

                        //     $this->admin_model->addEmailImage($_GET['pid'],$config['file_name'].'.'.$ext); 	



                        //         //  $this->load->view('Client_submit_track', $data);

                        //     }

				}

		   header("location: ".url("admin/store/edit_product?pid=".$_GET['pid']."&success=1"));   exit;

		     

		   }

		   else

		   {

		   header("location: ".url("admin/store/edit_product?pid=".$_GET['pid']."&error=1"));   exit;

		   }

		   

		 }

		

		

	    $output['companies'] = $this->admin_model->getCompanies(); 

	   

	   	$where = "where products.product_id = '". $_GET['pid'] ."'";

		$output['product'] = $this->admin_model->getProduct($where); 

	

	   

	   

	   	    if(isset($_GET['success']))				

			{

			  

			   $output['alert_class'] = 'alert alert-success';

			   $output['alert_message'] = 'Product Updated successfully.';

			

			}

			else if(isset($_GET['error']))				

			{

			   $output['alert_class'] = 'alert alert-danger';

			   $output['alert_message'] = 'Error occured, please try again.';

			}

	   

	//    $this->load->view('admin/header',$headerOutput);

	//    $this->load->view('edit_product',$output);

	//    $this->load->view('admin/footer');

       return view('admin/edit_product', $output);

    }


    function add_product(Request $request)

	{

	     // header data pass starts
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
         // print_r($logo_details->logo);die;
 
             $output = array();
 
             $output['pageTitle'] = 'Add Product';
             $output['logo'] = $get_logo;
             $output['welcome_name'] = $admin_name;
             $output['user_role'] = $user_role;
         
         // access modules
         $output['access'] = $this->admin_model->getAdminModules($admin_id);
 
             // header data pass ends


		 if(isset($_POST['addProduct']))

		 {			
			$date_time_array = getdate(time());

			$imgName =	$date_time_array["year"].$date_time_array["mon"].$date_time_array["mday"].$date_time_array["hours"].$date_time_array["minutes"].$date_time_array["seconds"];
			
			

		// email image upload

		 if(isset($_FILES['emailImage']['name']) && strlen($_FILES['emailImage']['name'])>4)

		 {      

                        $emailImage = $request->file('emailImage') ;
                
                        //Display File Name
                        $filename = $emailImage->getClientOriginalName();
                        // echo 'File Name: '.$filename;
                    
                        
                        //Display File Extension
                        $file_extension = $emailImage->getClientOriginalExtension();
                        // echo 'File Extension: '.$file_extension;
						

						if($file_extension != 'png' && $file_extension != 'gif' && $file_extension != 'jpg' && $file_extension != 'jpeg'){

							header("location: ".url("admin/store/add_product?noimageformat=1"));   exit;

					   }

						//    adding product 

						$result = $this->admin_model->addProduct($_POST); 
                    
                        
                        //Display File Real Path
                        //$real_path = $emailImage->getRealPath();
                        // echo 'File Real Path: '.$real_path;
                    
                        
                        //Display File Size
                    //  $file_size = $emailImage->getSize();
                        // echo 'File Size: '.$file_size;
                    
                        
                        //Display File Mime Type
                    //  $file_mime_type = $emailImage->getMimeType();
                        // echo 'File Mime Type: '.$file_mime_type;
                    
                        $destination_path = base_path('product_images/');
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

                        $image_name = $result . '_' . $imgName . '.'.$file_extension;
                        // $image_name = preg_replace('/[^a-zA-Z0-9_.]/', '_', $image_name);

                        $uploaded_data = $emailImage->move( $destination_path , $image_name );

                        if( !empty( $uploaded_data )){
                        // die('file');

                        $this->admin_model->addEmailImage_prm($result, $image_name);


                        }

                        else{

                            header("location: ".url("admin/store/add_product?error=1"));   exit;
                      
                        
                        }

	
                            //     $config['upload_path']          = './product_images/';

                            //     $config['allowed_types']        = 'gif|jpg|png';

                            //     $config['max_size']             = 100;

                            //     $config['max_width']            = 1024;

                            //     $config['max_height']           = 768;

                            //     $config['file_name']           = $result.'_'.$imgName;

                                

                            //     $ext = explode('.',$_FILES['emailImage']['name']);

                            //     $count = count($ext);

                            //     $ext = $ext[$count-1];



                            // //  $this->load->library('upload', $config);

                            // $this->upload->initialize($config);



                            //     if ( ! $this->upload->do_upload('emailImage'))

                            //     {

                            //             $error = array('error' => $this->upload->display_errors());



                            //         //    $this->load->view('upload_form', $error);

                            //     }

                            //     else

                            //     {

                            //     $data = array('upload_data' => $this->upload->data());

                            //     $this->admin_model->addEmailImage($result,$config['file_name'].'.'.$ext); 	



                            //         //  $this->load->view('Client_submit_track', $data);

                            //     }


				}



		   if($result>0)

		   {

		   header("location: ".url("admin/store/add_product?success=1"));   exit;

		     

		   }

		   else

		   {

		   header("location: ".url("admin/store/add_product?error=1"));   exit;

		   }
	   

		 }


		//$clients = $this->tracksdb->getClientsList(); 

	   $output['companies'] = $this->admin_model->getCompanies(); 


	   	    if(isset($_GET['success']))				

			{

			  

			   $output['alert_class'] = 'alert alert-success';

			   $output['alert_message'] = 'Product added successfully.';

			

			}

			else if(isset($_GET['error']))				

			{

			   $output['alert_class'] = 'alert alert-danger';

			   $output['alert_message'] = 'Error occured, please try again.';

			}

			else if (isset($_GET['noimageformat'])) {

				$output['alert_class'] = 'alert alert-danger';
				$output['alert_message'] = 'Only these image formats accepted: jpg, jpeg, gif, png'; 
			  
			}

	//    $this->load->view('admin/header',$headerOutput);

	//    $this->load->view('add_product',$output);

	//    $this->load->view('admin/footer');

       return view('admin/add_product', $output);

    }

    function product_orders()

	{


         // header data pass starts
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
         // print_r($logo_details->logo);die;
 
             $output = array();
 
             $output['pageTitle'] = 'Product Orders';
             $output['logo'] = $get_logo;
             $output['welcome_name'] = $admin_name;
             $output['user_role'] = $user_role;
         
         // access modules
         $output['access'] = $this->admin_model->getAdminModules($admin_id);
 
             // header data pass ends


		// generate where

		$where = 'where ';

		$whereItems = array();

		

		$output['searchProductName'] = '';

		

		

	  if(isset($_GET['search']))

		{

		

		  if(isset($_GET['product']) && strlen($_GET['product'])>0)

		   {

		     $output['searchProduct'] = $_GET['product'];

			 $whereItems[] = "products.name like '%". $_GET['product'] ."%'";

		   }

		   

		   if(isset($_GET['member']) && strlen($_GET['member'])>0)

		   {

		     $output['searchMember'] = $_GET['member'];

			 $whereItems[] = "members.fname like '%". $_GET['member'] ."%'";

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

		$sortBy = "product_orders.order_id";

		$output['sortBy'] = 'product_orders.order_id';

		$output['sortOrder'] = 2;

		

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


		// pagination

   		$start = 0; 

		$limit = 10;

		if(isset($_GET['records']))

		{

		  $limit = $_GET['records'];

		}

		$output['numRecords'] = $limit;

		

		$currentPageNo = 1;

		

		if(isset($_GET['page']) && $_GET['page']>1)

		 {

			$start = ($_GET['page']*$limit)-$limit;  

		 }

		 

		

		 if(isset($_GET['page']))

		 {

			$currentPageNo = $_GET['page']; 

		 }

		

      $num_records = $this->admin_model->getNumOrders($where,$sort); 

	  $numPages = (int)($num_records/$limit); 

	  $reminder = ($num_records%$limit);

	 

	 if($reminder>0)

	 {

		 $numPages = $numPages+1;

	 }

	

	 $output['numPages'] = $numPages;

	 $output['start'] = $start;

	 $output['currentPageNo'] = $currentPageNo;

	 $output['currentPage'] = 'product_orders';

	 

	 

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

		  

		

		

		$output['orders'] = $this->admin_model->getOrders($where,$sort,$start,$limit); 

		

       

	    $today = date('Y-m-d');

	    

		/*if($result['numRows']>0)

		{

			foreach($result['data'] as $product)

			{

			  

			   $where = "where product_id = '". $product->product_id ."' and applies_from <= '". $today ."' order by applies_from desc limit 0, 1";

			   $output['digicoins'][$product->product_id] =  $this->admin_model->getProductPrices($where); 

			}

	    }

		*/

		

	     if(isset($_GET['delete']))				

			{

			  

			   $output['alert_class'] = 'alert alert-success';

			   $output['alert_message'] = 'Product deleted successfully.';

			

			}

			else if(isset($_GET['error']))				

			{

			   $output['alert_class'] = 'alert alert-danger';

			   $output['alert_message'] = 'Error occured, please try again.';

			}


	//    $this->load->view('admin/header',$headerOutput);

	//    $this->load->view('orders',$output);

	//    $this->load->view('admin/footer');

       return view('admin/orders', $output);

	}


    
	function add_product_question()

	{   

         // header data pass starts
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
         // print_r($logo_details->logo);die;
 
             $output = array();
 
             $output['pageTitle'] = 'Product Review Options';
             $output['logo'] = $get_logo;
             $output['welcome_name'] = $admin_name;
             $output['user_role'] = $user_role;
         
         // access modules
         $output['access'] = $this->admin_model->getAdminModules($admin_id);
 
             // header data pass ends
	

	     if(isset($_POST['addQuestion']))	 

		 {

		   $result = $this->admin_model->addQuestion($_POST,$_GET['pid']); 

		   

		   if($result>0)

		   {

		    // header("location: ".url("admin/store/product_review_options?pid=".$_GET['pid']."&added=1"));   exit;
			return Redirect::to("admin/store/product_review_options?pid=".$_GET['pid']."&added=1");exit;

		   }

		   else

		   {

		     header("location: ".url("admin/store/product_review_options?pid=".$_GET['pid']."&error=1"));   exit;

		   }

		   

		 }

		 

	//	$sort =  $sortBy." ".$sortOrder;		

	//	$output['questions'] = $this->productsdb->getQuestions($where,$sort,$start,$limit); 


	//    $this->load->view('admin/header',$headerOutput);

	//    $this->load->view('add_product_question',$output);

	//    $this->load->view('admin/footer');

       return view('admin/add_product_question', $output);

	

	}

	function admin_clients_listing()
 
	{   

		 // header data pass starts
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
		 // print_r($logo_details->logo);die;
 
			 $output = array();
 
			 $output['pageTitle'] = 'Clients';
			 $output['logo'] = $get_logo;
			 $output['welcome_name'] = $admin_name;
			 $output['user_role'] = $user_role;
		 
		 // access modules
		 $output['access'] = $this->admin_model->getAdminModules($admin_id);
 
			 // header data pass ends

		   
			 // delete member
	 
			 if (isset($_GET['did'])) {
				 $result = $this->admin_model->adc_deleteClient($_GET['did']);
				 if ($result > 0) {
					 header("location: " . url("admin/clients?delete=success"));
					 exit;
				 } else {
					 header("location: " . url("admin/clients?error=1"));
					 exit;
				 }
			 }
			 // generate where 
			 $where = 'where ';
			 $whereItems[] = "clients.deleted = '0'";
			 $whereItems[] = "clients.active = '1'";
			 $output['searchCompany'] = '';
			 $output['searchUsername'] = '';
			 $output['searchName'] = '';
			 $output['searchEmail'] = '';
			 $output['searchPhone'] = '';
			 $output['searchCity'] = '';
			 $output['searchState'] = '';
			 $output['searchZip'] = '';
			 $output['searchTrackName'] = '';
			 $output['searchLabel'] = '';
			 $output['searchArtist'] = '';
			 $output['searchGenre'] = 0;
			 if (isset($_GET['company']) && strlen($_GET['company']) > 0) {
				 $output['searchCompany'] = $_GET['company'];
				 $whereItems[] = "(clients.name LIKE '%" . urlencode($_GET['company']) . "%' OR clients.name LIKE '%" . $_GET['company'] . "%')";
			 }
			 if (isset($_GET['username']) && strlen($_GET['username']) > 0) {
				 $output['searchUsername'] = $_GET['username'];
				 $whereItems[] = "(clients.uname LIKE '%" . urlencode($_GET['username']) . "%' OR clients.uname LIKE '%" . $_GET['username'] . "%')";
			 }
			 if (isset($_GET['name']) && strlen($_GET['name']) > 0) {
				 $output['searchName'] = $_GET['name'];
				 $whereItems[] = "(clients.ccontact LIKE '%" . urlencode($_GET['name']) . "%' OR clients.ccontact LIKE '%" . $_GET['name'] . "%')";
			 }
			 if (isset($_GET['email']) && strlen($_GET['email']) > 0) {
				 $output['searchEmail'] = $_GET['email'];
				 $whereItems[] = "(clients.email LIKE '%" . urlencode($_GET['email']) . "%' OR clients.email LIKE '%" . $_GET['email'] . "%')";
			 }
			 if (isset($_GET['phone']) && strlen($_GET['phone']) > 0) {
				 $output['searchPhone'] = $_GET['phone'];
				 $whereItems[] = "(clients.phone LIKE '%" . urlencode($_GET['phone']) . "%' OR clients.phone LIKE '%" . $_GET['phone'] . "%')";
			 }
			 if (isset($_GET['city']) && strlen($_GET['city']) > 0) {
				 $output['searchCity'] = $_GET['city'];
				 $whereItems[] = "(clients.city LIKE '%" . urlencode($_GET['city']) . "%' OR clients.city LIKE '%" . $_GET['city'] . "%')";
			 }
			 if (isset($_GET['state']) && strlen($_GET['state']) > 0) {
				 $output['searchState'] = $_GET['state'];
				 $whereItems[] = "(clients.state LIKE '%" . urlencode($_GET['state']) . "%' OR clients.state LIKE '%" . $_GET['state'] . "%')";
			 }
			 if (isset($_GET['zip']) && strlen($_GET['zip']) > 0) {
				 $output['searchZip'] = $_GET['zip'];
				 $whereItems[] = "(clients.zip LIKE '%" . urlencode($_GET['zip']) . "%' OR clients.zip LIKE '%" . $_GET['zip'] . "%')";
			 }
			 if (isset($_GET['track_name']) && strlen($_GET['track_name']) > 0) {
				 $output['searchTrackName'] = $_GET['track_name'];
				 $clientIds = $this->admin_model->adc_getClientIdByTrackName($_GET['track_name']);
				 $clientIds = array_filter(array_unique(array_column($clientIds, 'client')));
				 if(count($clientIds)) {
					 $whereItems[] = "clients.id IN (".implode(',', $clientIds).")";
				 }
			 }
			 if (isset($_GET['label']) && strlen($_GET['label']) > 0) {
				 $output['searchLabel'] = $_GET['label'];
				 $clientIds = $this->admin_model->adc_getClientIdByLabel($_GET['label']);
				 $clientIds = array_filter(array_unique(array_column($clientIds, 'client_id')));
				 if(count($clientIds)) {
					 $whereItems[] = "clients.id IN (".implode(',', $clientIds).")";
				 }
			 }
			 if (isset($_GET['artist']) && strlen($_GET['artist']) > 0) {
				 $output['searchArtist'] = $_GET['artist'];
				 $clientIds = $this->admin_model->adc_getClientIdByArtist($_GET['artist']);
				 $clientIds = array_filter(array_unique(array_column($clientIds, 'client')));
				 if(count($clientIds)) {
					 $whereItems[] = "clients.id IN (".implode(',', $clientIds).")";
				 }
			 }
			 if (isset($_GET['genre']) && (int) $_GET['genre'] > 0) {
				 $output['searchGenre'] = (int) $_GET['genre'];
				 $clientIds = $this->admin_model->adc_getClientIdByGenre($_GET['genre']);
				 $clientIds = array_filter(array_unique(array_column($clientIds, 'client')));
				 if(count($clientIds)) {
					 $whereItems[] = "clients.id IN (".implode(',', $clientIds).")";
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
			 if (isset($_GET['sortBy'])) {
				 $output['sortBy'] = $_GET['sortBy'];
				 if (strcmp($_GET['sortBy'], 'name') == 0) {
					 $sortBy = "ccontact";
				 } else if (strcmp($_GET['sortBy'], 'username') == 0) {
					 $sortBy = "uname";
				 } else if (strcmp($_GET['sortBy'], 'added') == 0) {
					 $sortBy = "id";
				 } else if (strcmp($_GET['sortBy'], 'lastLogin') == 0) {
					 $sortBy = "lastlogon";
				 } else if (strcmp($_GET['sortBy'], 'company') == 0) {
					 $sortBy = "name";
				 } else if (strcmp($_GET['sortBy'], 'city') == 0) {
					 $sortBy = "city";
				 } else if (strcmp($_GET['sortBy'], 'state') == 0) {
					 $sortBy = "state";
				 } else if (strcmp($_GET['sortBy'], 'zip') == 0) {
					 $sortBy = "zip";
				 } else if (strcmp($_GET['sortBy'], 'email') == 0) {
					 $sortBy = "email";
				 } else if (strcmp($_GET['sortBy'], 'phone') == 0) {
					 $sortBy = "phone";
				 }
			 }
			 // sort order	  		   
			 if (isset($_GET['sortOrder']) && $_GET['sortOrder'] == 1) {
				 $sortOrder = "ASC";
				 $output['sortOrder'] = $_GET['sortOrder'];
			 } else  if (isset($_GET['sortOrder']) && $_GET['sortOrder'] == 2) {
				 $sortOrder = "DESC";
				 $output['sortOrder'] = $_GET['sortOrder'];
			 }
			 $sort =  $sortBy . " " . $sortOrder;
			 // generate link
	 
			 $output['link_string'] = '?';
			 if (isset($_GET) && count($_GET) > 0) {
				 $link_items = array();
				 $link_string =  '?';
				 $exclude_variables = array("page");
				 foreach ($_GET as $key => $value) {
					 if (!(in_array($key, $exclude_variables))) {
						 $link_items[] = $key . '=' . $value;
					 }
				 }
				 if (count($link_items) > 1) {
					 $link_string = implode('&', $link_items);
					 $link_string = '?' . $link_string . '&';
				 } else if (count($link_items) == 1) {
					 $link_string = '?' . $link_items[0] . '&';
				 }
				 $output['link_string'] = $link_string;
			 }
			 $output['link_string'];
			 // pagination
	 
			 $limit = 10;
			 if (isset($_GET['numRecords'])) {
				 $limit = $_GET['numRecords'];
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
			 $num_records = $this->admin_model->adc_getNumClients($where, $sort);
			 $numPages = (int) ($num_records / $limit);
			 $reminder = ($num_records % $limit);
			 if ($reminder > 0) {
				 $numPages = $numPages + 1;
			 }
			 $output['numPages'] = $numPages;
			 $output['start'] = $start;
			 $output['currentPageNo'] = $currentPageNo;
			 $output['currentPage'] = 'clients';
			 if (isset($_GET['page'])) {
				 if ($_GET['page'] > $numPages) {
					 header("location: " . $output['currentPage'] . $link_string . "page=" . $numPages);
					 exit;
				 } else if ($_GET['page'] < 1) {
					 header("location: " . $output['currentPage'] . $link_string . "page=1");
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
	 
	 
			 $output['clients'] = $this->admin_model->adc_getClients($where, $sort, $start, $limit);
			 if (isset($_GET['delete'])) {
				 $output['alert_message'] = "Client deleted succesfully!";
				 $output['alert_class'] = "alert alert-success";
			 } else  if (isset($_GET['error'])) {
				 $output['alert_message'] = "Error occured, please try again!";
				 $output['alert_class'] = "alert alert-danger";
			 }
			 $output['genres'] = $this->admin_model->adc_getGenres();

		   //   $this->load->view('admin/header', $headerOutput);
		   //   $this->load->view('clients', $output);
		   //   $this->load->view('admin/footer');
			 
			 //dd($output['clients']['data']);
			foreach ($output['clients']['data'] as $client) {
                $package = DB::table('package_user_details')->select('manage_packages.package_type')->leftJoin("manage_packages", "manage_packages.id", "=", "package_user_details.package_id")->where('package_user_details.user_id','=',$client->id)->where('package_user_details.user_type','=',2)->get();
                // dd($package);
                $count = $package->count();
                if($count == 0){
                    // dd($package,$member->id);
                    $client->member_package = '-';
                }else{
                    $client->member_package = $package[0]->package_type;
                }
            }
			 
			 
		 //  dd($output);

	   return view('admin/clients', $output);

	

	}

	function client_change_password()
	{
		 // header data pass starts
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
		 // print_r($logo_details->logo);die;
 
			 $output = array();
 
			 $output['pageTitle'] = 'Change Password Client';
			 $output['logo'] = $get_logo;
			 $output['welcome_name'] = $admin_name;
			 $output['user_role'] = $user_role;
		 
		 // access modules
		 $output['access'] = $this->admin_model->getAdminModules($admin_id);
 
			 // header data pass ends

		if (isset($_POST['changePassword'])) {
			$result = $this->admin_model->adc_changeClientPassword($_POST['password'], $_GET['cid']);
			if ($result > 0) {


				// mail to client starts here (uncomment this on live)

			   //  $clientInfo = $this->admin_model->adc_getClient($_GET['cid']);
			   //  $clientName = urldecode($clientInfo['data'][0]->name);
			   //  $clientEmail = urldecode($clientInfo['data'][0]->email);
			   //  $get_password = $_POST['password'];
			   // //	$clientEmail ='school_test007@yopmail.com';
			   
			   //     $m_sub = 'Password changed at Digiwaxx';

			   //     $m_msg = "Hi '.$clientName.',<br><br>Your password has been changed by the admin team.<br><br>YOUR NEW PASSWORD IS: '.$get_password.'<br><br>
			   //     If you DID NOT REQUEST this change, please contact us immediately by responding to this email admin@digiwaxx.com, contact us via our chat or call us at 866.665.1259.
			   //     <br><br>Thank you for being a quality member and keep breaking boundaries!<br><br>Digiwaxx Admin Team";
				   
				   
			   //     $mail_data = [
			   //         'm_sub' => $m_sub,
			   //         'm_msg' => $m_msg,
			   //     ];

			   //     $cc_mails = 'admin@digiwaxx.com';
			   //     //$bcc_mails = '';
				   


			   //     $sendInvoiceMail = Mail::to($clientEmail);

			   //     if(!empty($cc_mails)){
			   //         $sendInvoiceMail->cc($cc_mails);
			   //     }
		   
			   //         // if(!empty($bcc_mails)){
			   //         // 	$sendInvoiceMail->bcc($bcc_mails);
			   //         // }
   
					   
			   //         $sendInvoiceMail->send(new AdminForgetNotification($mail_data));

					   // email ends here



		   //                 $this->load->library('email');
		   //                 $clientName = urldecode($clientInfo['data'][0]->name);
		   //                 $clientEmail = urldecode($clientInfo['data'][0]->email);
		   //                 // $this->email->from('no-reply@digiwaxx.com', 'Digiwaxx');
						   
		   //                 $this->email->from('admin@digiwaxx.com', 'Digiwaxx');
		   //                 $this->email->cc('admin@digiwaxx.com');
						   
		   //                 $this->email->to($clientEmail);
		   //                 $this->email->set_mailtype("html");
		   //                 $this->email->subject('Password changed at Digiwaxx');
		   // //                 echo $message = 'Hi ' . $clientName . ',<br /><p>New password: ' . $_POST["password"] . '</p>
		   // // 			<p>Your password has been changed by admin team at Digiwaxx.</p>';
		   //                 echo $message = 'Hi '.$clientName.',<br><br>Your password has been changed by the admin team.<br><br>YOUR NEW PASSWORD IS: '.$_POST["password"].'<br><br>
		   //                 If you DID NOT REQUEST this change, please contact us immediately by responding to this email admin@digiwaxx.com, contact us via our chat or call us at 866.665.1259.
		   //                 <br><br>Thank you for being a quality member and keep breaking boundaries!<br><br>Digiwaxx Admin Team';
		   
					   
		   //                 $this->email->message($message);
		   //                 $this->email->send();



				header("location: " . url("admin/client_change_password?cid=" . $_GET['cid'] . "&update=1"));
				exit;
			} else {
				header("location: " . url("admin/client_change_password?cid=" . $_GET['cid'] . "&error=1"));
				exit;
			}
		}
		$output['clients'] = $this->admin_model->adc_getClient($_GET['cid']);
		$output['adminInfo'] = $this->admin_model->adc_getAdmin($output['clients']['data'][0]->editedby);
		if (isset($_GET['update'])) {
			$output['alert_class'] = 'alert alert-success';
			$output['alert_message'] = 'Password updated successfully!';
		} else if (isset($_GET['error'])) {
			$output['alert_class'] = 'alert alert-danger';
			$output['alert_message'] = 'Error occurred, please try again!';
		}
	   //  $this->load->view('admin/header', $headerOutput);
	   //  $this->load->view('change_password_client', $output);
	   //  $this->load->view('admin/footer');

	  // dd($output);

		return view('admin/change_password_client', $output);
	}


	function client_view()
	{  
	   // header data pass starts
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
		 // print_r($logo_details->logo);die;
 
			 $output = array();
 
			 $output['pageTitle'] = 'View Client';
			 $output['logo'] = $get_logo;
			 $output['welcome_name'] = $admin_name;
			 $output['user_role'] = $user_role;
		 
		   // access modules
		   $output['access'] = $this->admin_model->getAdminModules($admin_id);
 
			 // header data pass ends
			   
				$output['clients'] = $this->admin_model->adc_getClient($_GET['cid']);
				$output['social'] = $this->admin_model->adc_getClientSocial($_GET['cid']);

			   //  $this->load->view('admin/header', $headerOutput);
			   //  $this->load->view('view_client', $output);
			   //  $this->load->view('admin/footer');
        $cid = $_GET['cid'];
        $query = DB::table('package_user_details')->select('package_user_details.*','manage_packages.package_name','manage_packages.package_type')->leftJoin("manage_packages", "manage_packages.id", "=", "package_user_details.package_id")->where('package_user_details.user_id','=',$cid)->get();
        // dd($query);
        $count = $query->count();
        // dd($count);
        if($count == 0){
            $output['client_package_details'] = 'Standard';
            $output['package_count'] = $count;
        }else{
            $output['client_package_details'] = $query[0];
            $output['package_count'] = $count;

        }
				//   dd($output['client_package_details']);

					return view('admin/view_client', $output);
	}

	function client_edit(Request $request)
	{  
		 // header data pass starts
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
		 // print_r($logo_details->logo);die;
 
			 $output = array();
 
			 $output['pageTitle'] = 'Edit Client';
			 $output['logo'] = $get_logo;
			 $output['welcome_name'] = $admin_name;
			 $output['user_role'] = $user_role;
		 
		   // access modules
		   $output['access'] = $this->admin_model->getAdminModules($admin_id);
 
			 // header data pass ends
	 
		if (isset($_POST['update'])) {
			$result = $this->admin_model->adc_updateClient($_POST, $_GET['cid']);
			if ($result > 0) {
				header("location: " . url("admin/client_edit?cid=" . $_GET['cid'] . "&update=1"));
				exit;
			} else {
				header("location: " . url("admin/client_edit?cid=" . $_GET['cid'] . "&error=1"));
				exit;
			}
		}
		$output['clients'] = $this->admin_model->adc_getClient($_GET['cid']);
		if(!empty($output['clients']['data'][0])){
	    	$output['adminInfo'] = $this->admin_model->adc_getAdmin($output['clients']['data'][0]->editedby);
		}else{
		    $output['adminInfo']= '';
		}
		if (isset($_GET['update'])) {
			$output['alert_class'] = 'alert alert-success';
			$output['alert_message'] = 'Client info updated successfully!';
		} else if (isset($_GET['error'])) {
			$output['alert_class'] = 'alert alert-danger';
			$output['alert_message'] = 'Error occurred, please try again!';
		}
	   //  $this->load->view('admin/header', $headerOutput);
	   //  $this->load->view('edit_client', $output);
	   //  $this->load->view('admin/footer');

		//dd($output);

		return view('admin/edit_client', $output);
	}

	function client_payments()
	{  

	   // header data pass starts
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
	   // print_r($logo_details->logo);die;

		   $output = array();

		   $output['pageTitle'] = 'Payment Clients';
		   $output['logo'] = $get_logo;
		   $output['welcome_name'] = $admin_name;
		   $output['user_role'] = $user_role;
	   
		 // access modules
		 $output['access'] = $this->admin_model->getAdminModules($admin_id);

		   // header data pass ends
	  
		// generate where 
		$where = 'where ';
		$whereItems = array();
		$whereItems[] = "client_subscriptions.status = '1'";
		$output['searchPackage'] = '';
		$output['searchUsername'] = '';
		if (isset($_GET['package']) && $_GET['package'] > 0) {
			$output['searchPackage'] = $_GET['package'];
			$whereItems[] = "client_subscriptions.packageId = '" . $_GET['package'] . "'";
		}
		if (isset($_GET['username']) && strlen($_GET['username']) > 0) {
			$output['searchUsername'] = $_GET['username'];
			$whereItems[] = "clients.uname = '" . $_GET['username'] . "'";
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
		$sortBy = "client_subscriptions.subscriptionId";
		$output['sortBy'] = 'paidOn';
		$output['sortOrder'] = 2;
		if (isset($_GET['sortBy'])) {
			$output['sortBy'] = $_GET['sortBy'];
			if (strcmp($_GET['sortBy'], 'username') == 0) {
				$sortBy = "clients.uname";
			} else if (strcmp($_GET['sortBy'], 'package') == 0) {
				$sortBy = "client_subscriptions.packageId";
			} else if (strcmp($_GET['sortBy'], 'paidOn') == 0) {
				$sortBy = "client_subscriptions.subscriptionId";
			}
		}
		// sort order	  		   
		if (isset($_GET['sortOrder']) && $_GET['sortOrder'] == 1) {
			$sortOrder = "ASC";
			$output['sortOrder'] = $_GET['sortOrder'];
		} else  if (isset($_GET['sortOrder']) && $_GET['sortOrder'] == 2) {
			$sortOrder = "DESC";
			$output['sortOrder'] = $_GET['sortOrder'];
		}
		$sort =  $sortBy . " " . $sortOrder;
		// generate link

		$output['link_string'] = '?';
		if (isset($_GET) && count($_GET) > 0) {
			$link_items = array();
			$link_string =  '?';
			$exclude_variables = array("page");
			foreach ($_GET as $key => $value) {
				if (!(in_array($key, $exclude_variables))) {
					$link_items[] = $key . '=' . $value;
				}
			}
			if (count($link_items) > 1) {
				$link_string = implode('&', $link_items);
				$link_string = '?' . $link_string . '&';
			} else if (count($link_items) == 1) {
				$link_string = '?' . $link_items[0] . '&';
			}
			$output['link_string'] = $link_string;
		}
		// pagination

		$limit = 10;
		if (isset($_GET['numRecords'])) {
			$limit = $_GET['numRecords'];
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
		$num_records = $this->admin_model->adc_getNumClientPayments($where, $sort);
		$numPages = (int) ($num_records / $limit);
		$reminder = ($num_records % $limit);
		if ($reminder > 0) {
			$numPages = $numPages + 1;
		}
		$output['numPages'] = $numPages;
		$output['start'] = $start;
		$output['currentPageNo'] = $currentPageNo;
		$output['currentPage'] = 'client_payments';
		if (isset($_GET['page'])) {
			if ($_GET['page'] > $numPages) {
				header("location: " . $output['currentPage'] . $link_string . "page=" . $numPages);
				exit;
			} else if ($_GET['page'] < 1) {
				header("location: " . $output['currentPage'] . $link_string . "page=1");
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


		$output['payments'] = $this->admin_model->adc_getClientPayments($where, $sort, $start, $limit);
	   //  $this->load->view('admin/header', $headerOutput);
	   //  $this->load->view('payments', $output);
	   //  $this->load->view('admin/footer');
	   //dd($output);

		return view('admin/client_payments', $output);
	}


	function client_payment_view()
	{  
		 // header data pass starts
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
	   // print_r($logo_details->logo);die;

		   $output = array();

		   $output['pageTitle'] = 'View Client Payment';
		   $output['logo'] = $get_logo;
		   $output['welcome_name'] = $admin_name;
		   $output['user_role'] = $user_role;
	   
		 // access modules
		 $output['access'] = $this->admin_model->getAdminModules($admin_id);

		   // header data pass ends
	   
   
		// generate where 
		$where = 'where ';
		$whereItems = array();
		$whereItems[] = "client_subscriptions.status = '1'";
		$whereItems[] = "client_subscriptions.subscriptionId = '" . $_GET['pid'] . "'";
		$output['searchPackage'] = '';
		$output['searchUsername'] = '';
		if (isset($_GET['search'])) {
			if (isset($_GET['package']) && $_GET['package'] > 0) {
				$output['searchPackage'] = $_GET['package'];
				$whereItems[] = "client_subscriptions.packageId = '" . $_GET['package'] . "'";
			}
			if (isset($_GET['username']) && strlen($_GET['username']) > 0) {
				$output['searchUsername'] = $_GET['username'];
				$whereItems[] = "clients.uname = '" . $_GET['username'] . "'";
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
		$sortBy = "client_subscriptions.subscriptionId";
		$output['sortBy'] = 'paidOn';
		$output['sortOrder'] = 2;
		if (isset($_GET['sortBy']) && isset($_GET['sortOrder'])) {
			$output['sortBy'] = $_GET['sortBy'];
			$output['sortOrder'] = $_GET['sortOrder'];
			if (strcmp($_GET['sortBy'], 'username') == 0) {
				$sortBy = "clients.uname";
			} else if (strcmp($_GET['sortBy'], 'package') == 0) {
				$sortBy = "client_subscriptions.packageId";
			} else if (strcmp($_GET['sortBy'], 'paidOn') == 0) {
				$sortBy = "client_subscriptions.clientSubscriptionId";
			} else if (strcmp($_GET['sortBy'], 'lastLogin') == 0) {
				$sortBy = "";
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
		$start = 0;
		$output['currentPage'] = 'client_payments';
		// pagination


		$output['payments'] = $this->admin_model->adc_getClientPayments($where, $sort, $start, $limit);
		if ($output['payments']['numRows'] > 0) {
			foreach ($output['payments']['data'] as $payment) {
				if ($payment->paymentType == 1) {
					$output['stripeData'] = $this->admin_model->adc_getStripeDetails($payment->subscriptionId);
				} else if ($payment->paymentType == 2) {
					$output['paypalData'] = $this->admin_model->adc_getPaypalDetails($payment->subscriptionId);
				}
			}
		}
	   //  $this->load->view('admin/header', $headerOutput);
	   //  $this->load->view('payment_view', $output);
	   //  $this->load->view('admin/footer');

		return view('admin/client_payment_view', $output);
	}


	function add_client(Request $request)
	{  
		 // header data pass starts
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
	   // print_r($logo_details->logo);die;

		   $output = array();

		   $output['pageTitle'] = 'Add Client';
		   $output['logo'] = $get_logo;
		   $output['welcome_name'] = $admin_name;
		   $output['user_role'] = $user_role;
	   
		 // access modules
		 $output['access'] = $this->admin_model->getAdminModules($admin_id);

		   // header data pass ends
	  
		if (isset($_GET['gs']) && isset($_GET['cid'])) {
			$states = $this->admin_model->adc_getStates($_GET['cid']);
			$states_array[] = array('state_id' => 0, 'state' => 'Select State');
			if ($states['numRows'] > 0) {
				foreach ($states['data'] as $state) {
					$states_array[] = array('state_id' => $state->stateId, 'state' => $state->name);
				}
			}
			echo json_encode($states_array);
			exit;
		}
		// verify email

		if (isset($_GET['email']) && isset($_GET['verifyEmail'])) {
			$result = $this->admin_model->adc_verifyEmail($_GET['email']);
			if ($result > 0) {
				$arr = array('response' => 1, 'message' => 'Email already exists!');
				echo json_encode($arr);
				exit;
			} else {
				$arr = array('response' => 0, 'message' => '');
				echo json_encode($arr);
				exit;
			}
			exit;
		}
		// add client

		if (isset($_POST['addClient'])) {
			$result = $this->admin_model->adc_addClient($_POST);
			if ($result['type'] > 0) {
				if ($result['type'] == 1) {
					Session::put('alertMessage', $_POST['username'] . " username already, exists !");
				} else if ($result['type'] == 2) {
				   
					Session::put('alertMessage', $_POST['email'] . " email already, exists !");
				}
				Session::put('alertClass', 'alert alert-danger');
				header("location: " . url("admin/add_client?error=1"));
				exit;
			} else if ($result['type'] == 0 && $result['success'] > 0) {
			   

				 // mail to client starts here (uncomment this on live)

			   //   $name = $_POST['companyName'];
			   //   $email = $_POST['email'];

			   // //	$clientEmail ='school_test007@yopmail.com';
			   
			   //     $m_sub = 'Client account created at Digiwaxx';

			   //     $m_msg ='Hi ' . $name . ',<br />
			   //      <p>Client account created, following are the login credentials,</p>
			   //     <p>username : ' . $_POST["username"] . '</p>
			   //     <p>password : ' . $_POST["password"] . '</p>';

			   //      $htmlTemplate =  file_get_contents(base_path("templates/newsletter/registration.php"));
			   //      $htmlTemplate = str_replace("*message*", $m_msg, $htmlTemplate);
				   
				   
			   //     $mail_data = [
			   //         'm_sub' => $m_sub,
			   //         'm_msg' => $htmlTemplate,
			   //     ];

			   //   //  $cc_mails = 'admin@digiwaxx.com';
			   //     //$bcc_mails = '';
				   


			   //     $sendInvoiceMail = Mail::to($email);

			   //     // if(!empty($cc_mails)){
			   //     //     $sendInvoiceMail->cc($cc_mails);
			   //     // }
		   
			   //         // if(!empty($bcc_mails)){
			   //         // 	$sendInvoiceMail->bcc($bcc_mails);
			   //         // }
   
					   
			   //         $sendInvoiceMail->send(new AdminForgetNotification($mail_data));

			   //         // email ends here


			 


		   //      $this->load->library('email');
		   //      $this->email->from('no-reply@digiwaxx.com', 'Digiwaxx');
		   //      $this->email->to($email);
		   //      $this->email->set_mailtype("html");
		   //      $this->email->subject('Client account created at Digiwaxx');
		   //      $message = 'Hi ' . $name . ',<br />
		   //  <p>Client account created, following are the login credentials,</p>
		   //  <p>username : ' . $_POST["username"] . '</p>
		   //  <p>password : ' . $_POST["password"] . '</p>';
		   //      $htmlTemplate =  file_get_contents(base_path("templates/newsletter/registration.php"));
		   //      $htmlTemplate = str_replace("*message*", $message, $htmlTemplate);
		   //      $this->email->message($htmlTemplate);
		   //      $this->email->send();

			 //  // mail chimp integration
			   //  $this->load->library('MCAPI');
			   //  $retval = $this->mcapi->listSubscribe('bf21d55a8b', $email);
                $emailofuser=$_POST['email'];
                $nameofuser=$_POST['companyName'];
                $passofuser=$_POST['password'];
                $unameofuser=$_POST['username'];
                if(!empty($emailofuser)){
    				$data = array('emailId' => $emailofuser, 'name' => $nameofuser, 'pwd'=>$passofuser,'uname' => $unameofuser);
    				Mail::send('mails.clients.addClient',['data' => $data], function ($message) use ($data) {
    				  $message->to($data['emailId']);
    				  $message->subject('New Client Account Created at Digiwaxx by Admin');
    				  $message->from('business@digiwaxx.com','Digiwaxx');
    			   });
                }
                
			  

				Session::put('alertMessage', 'Client added successfully !');
				Session::put('alertClass', 'alert alert-success');
				// $output['alert_message']='Client added successfully !';
				// $output['alert_class']='alert alert-success';
				header("location: " . url("admin/add_client?success=1"));
				exit;
			} else if ($result['type'] == 0 && $result['success'] < 1) {

				Session::put('alertMessage', 'Error occured, please try again !');
				Session::put('alertClass', 'alert alert-danger');
				// $output['alert_message']='Error occured, please try again !';
				// $output['alert_class']='alert alert-danger';
				header("location: " . url("admin/add_client?error=1"));
				exit;
			}
		}
		if(isset($_GET['success'])){
		    if($_GET['success']==1){
		        $output['alert_message']='Client added successfully !';
				$output['alert_class']='alert alert-success';
		    }else{
		        $output['alert_message']='';
				$output['alert_class']='';
		    }
		}
		else if(isset($_GET['error'])){
		    if($_GET['error']==1){
		        $output['alert_message']='Error occured, please try again !';
				$output['alert_class']='alert alert-danger';
		    }else{
		        $output['alert_message']='';
				$output['alert_class']='';
		    }
		}else{
		    $output['alert_message']='';
			$output['alert_class']='';
		}
// 		if(!empty(Session::get('alertMessage'))){
// 			$output['alert_message'] = Session::get('alertMessage');
// 			$output['alert_class'] = Session::get('alertClass');
   
// 			Session::forget('alertMessage');
// 			Session::forget('alertClass');
// 		}
		$output['countries'] = $this->admin_model->adc_getCountries();
		$output['states'] = $this->admin_model->adc_getStates(1);

	   //  $this->load->view('admin/header', $headerOutput);
	   //  $this->load->view('add_client', $output);
	   //  $this->load->view('admin/footer');

	   //dd($output);

		return view('admin/add_client', $output);
	}

	public function Exportclients()
	{

	   
		 
		// ini_set("memory_limit","2048M");
		 $output['clients']  = $this->admin_model->exportClients_adc();
		 
		 
		 // download csv file:  
		    $file_path = 'excel/clients.csv';
		    if (!is_dir(dirname($file_path))) {
			 mkdir(dirname($file_path), 0777, true);
			}
		    $fp = fopen($file_path, 'w');
		   
		   if($output['clients']['numRows']>0)
		   {
		   
				   $row1[] = 'S. No.';
				   $row1[] = 'Username';
				   $row1[] = 'Company';
				   $row1[] = 'Name';
				   $row1[] = 'Email';
				   $row1[] = 'Address1';
				   $row1[] = 'Address2';
				   $row1[] = 'City';
				   $row1[] = 'State';
				   $row1[] = 'Country';
				   $row1[] = 'Website';
				   $row1[] = 'Zip';
				   $row1[] = 'Mobile';
				   $row1[] = 'Howheard';
				   
				   
				   fputcsv($fp, $row1);
				   unset($row1);
				   $i = 1;	 
			   foreach($output['clients']['data'] as $row)
			   {
			   
			   
				   $row1[] = $i;
				   $row1[] = urldecode($row->uname);
				   $row1[] = ucfirst(urldecode($row->name));
				   $row1[] = ucfirst(urldecode($row->ccontact));
				   $row1[] = urldecode($row->email);
				   $row1[] = urldecode($row->address1);
				   $row1[] = urldecode($row->address2);
				   $row1[] = urldecode($row->city);
				   $row1[] = urldecode($row->state);
				   $row1[] = urldecode($row->country);
				   $row1[] = urldecode($row->website);
				   $row1[] = urldecode($row->zip);
				   $row1[] = urldecode($row->mobile);
				   $row1[] = urldecode($row->howheard);
				   
				   
				   fputcsv($fp, $row1);
				   unset($row1);
				   $i++;
			   }
			   
		   
		   
		   }  
			   fclose($fp);

			   // force download after write in file
			   $file= base_path(). "/excel/clients.csv";
			  // dd($file);
			   return response()->download($file, "clients1.csv");
					   
		
	   //  $this->load->helper('download');		
	   //  force_download('excel/clients.csv', NULL);
		exit;
	}

	function pending_clients()
	{  
		 // header data pass starts
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
	   // print_r($logo_details->logo);die;

		   $output = array();

		   $output['pageTitle'] = 'Pending Client Requests';
		   $output['logo'] = $get_logo;
		   $output['welcome_name'] = $admin_name;
		   $output['user_role'] = $user_role;
	   
		 // access modules
		 $output['access'] = $this->admin_model->getAdminModules($admin_id);

		   // header data pass ends

		   // generate where 
		   $where = 'where ';
		   $whereItems[] = "clients.deleted = '0'";
		   $whereItems[] = "clients.active < '1'";
		   $output['searchCompany'] = '';
		   $output['searchUsername'] = '';
		   $output['searchName'] = '';
		   $output['searchEmail'] = '';
		   $output['searchPhone'] = '';
		   $output['searchCity'] = '';
		   $output['searchState'] = '';
		   $output['searchZip'] = '';
		   $whereItems[] = "clients.active = '0'";
		   $whereItems[] = "clients.deleted = '0'";
			 if (isset($_GET['company']) && strlen($_GET['company']) > 0) {
				 $output['searchCompany'] = $_GET['company'];
				 $whereItems[] = "(clients.name LIKE '%" . urlencode($_GET['company']) . "%' OR clients.name LIKE '%" . $_GET['company'] . "%')";
			 }
			 if (isset($_GET['username']) && strlen($_GET['username']) > 0) {
				 $output['searchUsername'] = $_GET['username'];
				 $whereItems[] = "(clients.uname LIKE '%" . urlencode($_GET['username']) . "%' OR clients.uname LIKE '%" . $_GET['username'] . "%')";
			 }
			 if (isset($_GET['name']) && strlen($_GET['name']) > 0) {
				 $output['searchName'] = $_GET['name'];
				 $whereItems[] = "(clients.ccontact LIKE '%" . urlencode($_GET['name']) . "%' OR clients.ccontact LIKE '%" . $_GET['name'] . "%')";
			 }
			 if (isset($_GET['email']) && strlen($_GET['email']) > 0) {
				 $output['searchEmail'] = $_GET['email'];
				 $whereItems[] = "(clients.email LIKE '%" . urlencode($_GET['email']) . "%' OR clients.email LIKE '%" . $_GET['email'] . "%')";
			 }
			 if (isset($_GET['phone']) && strlen($_GET['phone']) > 0) {
				 $output['searchPhone'] = $_GET['phone'];
				 $whereItems[] = "(clients.phone LIKE '%" . urlencode($_GET['phone']) . "%' OR clients.phone LIKE '%" . $_GET['phone'] . "%')";
			 }
			 if (isset($_GET['city']) && strlen($_GET['city']) > 0) {
				 $output['searchCity'] = $_GET['city'];
				 $whereItems[] = "(clients.city LIKE '%" . urlencode($_GET['city']) . "%' OR clients.city LIKE '%" . $_GET['city'] . "%')";
			 }
			 if (isset($_GET['state']) && strlen($_GET['state']) > 0) {
				 $output['searchState'] = $_GET['state'];
				 $whereItems[] = "(clients.state LIKE '%" . urlencode($_GET['state']) . "%' OR clients.state LIKE '%" . $_GET['state'] . "%')";
			 }
			 if (isset($_GET['zip']) && strlen($_GET['zip']) > 0) {
				 $output['searchZip'] = $_GET['zip'];
				 $whereItems[] = "(clients.zip LIKE '%" . urlencode($_GET['zip']) . "%' OR clients.zip LIKE '%" . $_GET['zip'] . "%')";
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
		   if (isset($_GET['sortBy'])) {
			   $output['sortBy'] = $_GET['sortBy'];
			   if (strcmp($_GET['sortBy'], 'name') == 0) {
				   $sortBy = "clients.ccontact";
			   } else if (strcmp($_GET['sortBy'], 'username') == 0) {
				   $sortBy = "clients.uname";
			   } else if (strcmp($_GET['sortBy'], 'added') == 0) {
				   $sortBy = "clients.id";
			   } else if (strcmp($_GET['sortBy'], 'company') == 0) {
				   $sortBy = "clients.name";
			   } else if (strcmp($_GET['sortBy'], 'city') == 0) {
				   $sortBy = "clients.city";
			   } else if (strcmp($_GET['sortBy'], 'state') == 0) {
				   $sortBy = "clients.state";
			   } else if (strcmp($_GET['sortBy'], 'zip') == 0) {
				   $sortBy = "clients.zip";
			   } else if (strcmp($_GET['sortBy'], 'email') == 0) {
				   $sortBy = "clients.email";
			   } else if (strcmp($_GET['sortBy'], 'phone') == 0) {
				   $sortBy = "clients.phone";
			   }
		   }
		   // sort order	  		   
		   if (isset($_GET['sortOrder']) && $_GET['sortOrder'] == 1) {
			   $sortOrder = "ASC";
			   $output['sortOrder'] = $_GET['sortOrder'];
		   } else  if (isset($_GET['sortOrder']) && $_GET['sortOrder'] == 2) {
			   $sortOrder = "DESC";
			   $output['sortOrder'] = $_GET['sortOrder'];
		   }
		   $sort =  $sortBy . " " . $sortOrder;
		   // generate link
   
		   $output['link_string'] = '?';
		   if (isset($_GET) && count($_GET) > 0) {
			   $link_items = array();
			   $link_string =  '?';
			   $exclude_variables = array("page");
			   foreach ($_GET as $key => $value) {
				   if (!(in_array($key, $exclude_variables))) {
					   $link_items[] = $key . '=' . $value;
				   }
			   }
			   if (count($link_items) > 1) {
				   $link_string = implode('&', $link_items);
				   $link_string = '?' . $link_string . '&';
			   } else if (count($link_items) == 1) {
				   $link_string = '?' . $link_items[0] . '&';
			   }
			   $output['link_string'] = $link_string;
		   }
		   $output['link_string'];
		   // pagination
   
		   $limit = 10;
		   if (isset($_GET['numRecords'])) {
			   $limit = $_GET['numRecords'];
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
		   $num_records = $this->admin_model->adc_getNumClients($where, $sort);
		   $numPages = (int) ($num_records / $limit);
		   $reminder = ($num_records % $limit);
		   if ($reminder > 0) {
			   $numPages = $numPages + 1;
		   }
		   $output['numPages'] = $numPages;
		   $output['start'] = $start;
		   $output['currentPageNo'] = $currentPageNo;
		   $output['currentPage'] = 'pending_clients';
		   if (isset($_GET['page'])) {
			   if ($_GET['page'] > $numPages) {
				   header("location: " . $output['currentPage'] . $link_string . "page=" . $numPages);
				   exit;
			   } else if ($_GET['page'] < 1) {
				   header("location: " . $output['currentPage'] . $link_string . "page=1");
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
   
   
		   $output['clients'] = $this->admin_model->adc_getClients($where, $sort, $start, $limit);
		   // $output['clients'] = $result['data'];
		   // $this->load->view('admin/header', $headerOutput);
		   // $this->load->view('pending_clients', $output);
		   // $this->load->view('admin/footer');

		   //  dd($output);
		    if (isset($_GET['accepted']) || isset($_GET['multipleaccepted'])) {
			   $output['alert_message'] = 'Client accepted successfully!';
			   $output['alert_class'] = 'alert alert-success';
		   } else if (isset($_GET['declined'])) {
			   $output['alert_message'] = 'Client declined successfully!';
			   $output['alert_class'] = 'alert alert-success';
		   } else if (isset($_GET['deleted'])) {
			   $output['alert_message'] = 'Client deleted successfully!';
			   $output['alert_class'] = 'alert alert-success';
		   } else if (isset($_GET['error'])) {
			   $output['alert_message'] = 'Error occured, please try again!';
			   $output['alert_class'] = 'alert alert-danger';
		   } else{
		       $output['alert_message']='';
		       $output['alert_class']='';
		   }

		return view('admin/pending_clients', $output);
	  

	}

	function manage_pending_client()
	{  
		 // header data pass starts
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
	  if(isset($_GET['cid']) && !empty($_GET['cid'])){
		  $apprvSts = $this->admin_model->checkIfApprovedClient($_GET['cid']);
		  if($apprvSts['numRows'] == 0){
			return Redirect::to("admin/pending_clients");exit;  
		  }		   
	  }
	   // print_r($logo_details->logo);die;

		   

		   $output['pageTitle'] = 'Clients';
		   $output['logo'] = $get_logo;
		   $output['welcome_name'] = $admin_name;
		   $output['user_role'] = $user_role;
	   
		 // access modules
		 $output['access'] = $this->admin_model->getAdminModules($admin_id);

		   // header data pass ends

		   if (isset($_GET['acceptIds']) ) {
			   $acceptIds=$_GET['acceptIds'];
			   $acceptIds=explode(',',$acceptIds);
			   foreach($acceptIds as $a){
			     $result = $this->admin_model->adc_acceptClient($a);

			   if ($result['response'] > 0) {

				   // mail to client starts here (uncomment this on live)

				   // $message = 'Hi ' . $result['data'][0]->ccontact . ',<br /><p>Your account is approved by admin, you may login.</p>';
				   // $htmlTemplate =  file_get_contents(base_path("templates/newsletter/registration.php"));
				   // $htmlTemplate = str_replace("*message*", $message, $htmlTemplate);
				   // // email			
				    $email = urldecode($result['data'][0]->email);


				   // //	$email ='school_test007@yopmail.com';
				   
				   //     $m_sub = 'Client Account approval from Digiwaxx Admin';
					   
					   
				   //     $mail_data = [
				   //         'm_sub' => $m_sub,
				   //         'm_msg' => $htmlTemplate,
				   //     ];

				   // //  $cc_mails = 'admin@digiwaxx.com';
				   //     //$bcc_mails = '';
					   


				   //     $sendInvoiceMail = Mail::to($email);

				   //     // if(!empty($cc_mails)){
				   //     //     $sendInvoiceMail->cc($cc_mails);
				   //     // }
			   
				   //         // if(!empty($bcc_mails)){
				   //         // 	$sendInvoiceMail->bcc($bcc_mails);
				   //         // }
	   
						   
				   //         $sendInvoiceMail->send(new AdminForgetNotification($mail_data));

			   //         // email ends here



				   // $this->load->library('email');
				   // $this->email->from('no-reply@digiwaxx.com', 'Digiwaxx');
				   // $this->email->to($email);
				   // $this->email->set_mailtype("html");
				   // $this->email->subject('Client Account approval from Digiwaxx Admin');
				   // $this->email->message($htmlTemplate);
				   // $this->email->send();
				   if(!empty($email)){  
        					 $emailofuser=$email;
                            $nameofuser=$result['data'][0]->name;
                           
                            if(!empty($emailofuser)){
                				$data = array('emailId' => $emailofuser, 'name' => $nameofuser);
                				Mail::send('mails.clients.approveClient',['data' => $data], function ($message) use ($data) {
                				  $message->to($data['emailId']);
                				  $message->subject('Client Account Approved at Digiwaxx by Admin');
                				  $message->from('business@digiwaxx.com','Digiwaxx');
                			   });
                            }
					 }
				   
			   }
			   
			   }
			   header("location: " . url("admin/pending_clients?multipleaccepted=1"));
			   die;
		   }
		   
		   
		   
		   
		   if (isset($_GET['acceptId']) && isset($_GET['cid']) && ($_GET['cid'] == $_GET['acceptId'])) {
			   $result = $this->admin_model->adc_acceptClient($_GET['cid']);
			   if ($result['response'] > 0) {
					  
				

				   // mail to client starts here (uncomment this on live)

				   // $message = 'Hi ' . $result['data'][0]->ccontact . ',<br /><p>Your account is approved by admin, you may login.</p>';
				   // $htmlTemplate =  file_get_contents(base_path("templates/newsletter/registration.php"));
				   // $htmlTemplate = str_replace("*message*", $message, $htmlTemplate);
				   // // email			
				    $email = urldecode($result['data'][0]->email);


				   // //	$email ='school_test007@yopmail.com';
				   
				   //     $m_sub = 'Client Account approval from Digiwaxx Admin';
					   
					   
				   //     $mail_data = [
				   //         'm_sub' => $m_sub,
				   //         'm_msg' => $htmlTemplate,
				   //     ];

				   // //  $cc_mails = 'admin@digiwaxx.com';
				   //     //$bcc_mails = '';
					   


				   //     $sendInvoiceMail = Mail::to($email);

				   //     // if(!empty($cc_mails)){
				   //     //     $sendInvoiceMail->cc($cc_mails);
				   //     // }
			   
				   //         // if(!empty($bcc_mails)){
				   //         // 	$sendInvoiceMail->bcc($bcc_mails);
				   //         // }
	   
						   
				   //         $sendInvoiceMail->send(new AdminForgetNotification($mail_data));

			   //         // email ends here



					   // $this->load->library('email');
					   // $this->email->from('no-reply@digiwaxx.com', 'Digiwaxx');
					   // $this->email->to($email);
					   // $this->email->set_mailtype("html");
					   // $this->email->subject('Client Account approval from Digiwaxx Admin');
					   // $this->email->message($htmlTemplate);
					   // $this->email->send();
					   
					   ## email approval
					 if(!empty($email)){  
        					 $emailofuser=$email;
                            $nameofuser=$result['data'][0]->name;
                           
                            if(!empty($emailofuser)){
                				$data = array('emailId' => $emailofuser, 'name' => $nameofuser);
                				Mail::send('mails.clients.approveClient',['data' => $data], function ($message) use ($data) {
                				  $message->to($data['emailId']);
                				  $message->subject('Client Account Approved at Digiwaxx by Admin');
                				  $message->from('business@digiwaxx.com','Digiwaxx');
                			   });
                            }
					 }
					   
				   header("location: " . url("admin/manage_pending_client?cid=" . $_GET['cid'] . "&accepted=1"));
				   exit;
			   } else {
				   header("location: " . url("admin/manage_pending_client?cid=" . $_GET['cid'] . "&error=1"));
				   exit;
			   }
		   }
		   if (isset($_GET['declineId']) && isset($_GET['cid']) && ($_GET['cid'] == $_GET['declineId'])) {
			   $result = $this->admin_model->adc_declineClient($_GET['cid']);
			   if ($result > 0) {
				   header("location: " . url("admin/manage_pending_client?cid=" . $_GET['cid'] . "&declined=1"));
				   exit;
			   } else {
				   header("location: " . url("admin/manage_pending_client?cid=" . $_GET['cid'] . "&error=1"));
				   exit;
			   }
		   }
		   
		   if (isset($_GET['declineIds'])) {
			   $deleteIds=$_GET['declineIds'];
			   $deleteIds=explode(',',$deleteIds);
			   foreach($deleteIds as $a){
				   $result = $this->admin_model->adc_declineClient($a);
			   }
			   header("location: " . url("admin/pending_clients?multipledeclined=1"));
			   exit;
			   
		   }
		   
		   if (isset($_GET['deleteIds'])) {
			   
			   $deleteIds=$_GET['deleteIds'];
			   $deleteIds=explode(',',$deleteIds);
			   foreach($deleteIds as $a){
				   $result = $this->admin_model->adc_deleteClient($a);
			   }
			   header("location: " . url("admin/pending_clients?multipledeleted=1"));
			   exit;
		   }
		   if (isset($_GET['deleteId']) && isset($_GET['cid']) && ($_GET['cid'] == $_GET['deleteId'])) {
			   $result = $this->admin_model->adc_deleteClient($_GET['cid']);
			   if ($result > 0) {
				   header("location: " . url("admin/pending_clients?cid=" . $_GET['cid'] . "&deleted=1"));
				   exit;
			   } else {
				   header("location: " . url("admin/manage_pending_client?cid=" . $_GET['cid'] . "&error=1"));
				   exit;
			   }
		   }
		   $output['clients'] = $this->admin_model->adc_getClient($_GET['cid']);
		   $output['social'] = $this->admin_model->adc_getClientSocial($_GET['cid']);
		   if (isset($_GET['accepted'])) {
			   $output['alert_message'] = 'Client accepted successfully!';
			   $output['alert_class'] = 'alert alert-success';
		   } else if (isset($_GET['declined'])) {
			   $output['alert_message'] = 'Client declined successfully!';
			   $output['alert_class'] = 'alert alert-success';
		   } else if (isset($_GET['deleted'])) {
			   $output['alert_message'] = 'Client deleted successfully!';
			   $output['alert_class'] = 'alert alert-success';
		   } else if (isset($_GET['error'])) {
			   $output['alert_message'] = 'Error occured, please try again!';
			   $output['alert_class'] = 'alert alert-danger';
		   }
		   // $this->load->view('admin/header', $headerOutput);
		   // $this->load->view('manage_pending_client', $output);
		   // $this->load->view('admin/footer');

		  //   dd($output);

		return view('admin/manage_pending_client', $output);


	}

	function add_member(Request $request)
	{  
		 // header data pass starts
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
	   // print_r($logo_details->logo);die;

		   $output = array();

		   $output['pageTitle'] = 'Add Member';
		   $output['logo'] = $get_logo;
		   $output['welcome_name'] = $admin_name;
		   $output['user_role'] = $user_role;
	   
		 // access modules
		 $output['access'] = $this->admin_model->getAdminModules($admin_id);

		   // header data pass ends

		   date_default_timezone_set('America/Los_Angeles');

		
		   if (isset($_GET['gs']) && isset($_GET['cid'])) {
			   $states = $this->admin_model->ad_mem_getStates($_GET['cid']);
			   $states_array[] = array('state_id' => 0, 'state' => 'Select State');
			   if ($states['numRows'] > 0) {
				   foreach ($states['data'] as $state) {
					   $states_array[] = array('state_id' => $state->stateId, 'state' => $state->name);
				   }
			   }
			   echo json_encode($states_array);
			   exit;
		   }
		   
		   	if (isset($_GET['email']) && isset($_GET['verifyEmail'])) {
			$result = $this->admin_model->adc_verifyEmail($_GET['email']);
			if ($result > 0) {
				$arr = array('response' => 1, 'message' => 'Email already exists!');
				echo json_encode($arr);
				exit;
			} else {
				$arr = array('response' => 0, 'message' => '');
				echo json_encode($arr);
				exit;
			}
			exit;
		}
		   
		  $var=0;
		   if (isset($_POST['addMember'])) {
			   $result = $this->admin_model->ad_mem_addMember($_POST);
			   if ($result['type'] > 0) {
				   if ($result['type'] == 1) {
					   Session::put('alertMessage', $_POST['member_username'] . " username already, exists !");
					   $var=3;
				   } else if ($result['type'] == 2) {
					   
					   Session::put('alertMessage', $_POST['email'] . " email already, exists !");
					   $var=1;
				   }
				   $_SESSION['alertClass'] = 'alert alert-danger';
				   Session::put('alertClass', $_POST['email'] . " alert alert-danger");
				   header("location: " . url("admin/add_member?error=$var"));
				   exit;
			   } else if ($result['type'] == 0 && $result['success'] > 0) {
				   $name = $_POST['firstName'];
				   $email = $_POST['email'];

					// mail to client starts here (uncomment this on live)

				   //  $message = 'Hi ' . $name . ',<br />
				   //  <p>Member account created, following are the login credentials,</p>
				   //  <p>username : ' . $_POST["userName"] . '</p>
				   //  <p>password : ' . $_POST["password"] . '</p>';
				   //  $htmlTemplate =  file_get_contents(base_path("templates/newsletter/registration.php"));
				   //  $htmlTemplate = str_replace("*message*", $message, $htmlTemplate);
				   // // email			


				   // //	$email ='school_test007@yopmail.com';
				   
				   //     $m_sub = 'Member account created at Digiwaxx';
					   
					   
				   //     $mail_data = [
				   //         'm_sub' => $m_sub,
				   //         'm_msg' => $htmlTemplate,
				   //     ];

				   // //  $cc_mails = 'admin@digiwaxx.com';
				   //     //$bcc_mails = '';
					   


				   //     $sendInvoiceMail = Mail::to($email);

				   //     // if(!empty($cc_mails)){
				   //     //     $sendInvoiceMail->cc($cc_mails);
				   //     // }
			   
				   //         // if(!empty($bcc_mails)){
				   //         // 	$sendInvoiceMail->bcc($bcc_mails);
				   //         // }
	   
						   
				   //         $sendInvoiceMail->send(new AdminForgetNotification($mail_data));

				   //     // email ends here



				   // $this->load->library('email');
				   // $this->email->from('no-reply@digiwaxx.com', 'Digiwaxx');
				   // $this->email->to($email);
				   // $this->email->set_mailtype("html");
				   // $this->email->subject('Member account created at Digiwaxx');
				   // $message = 'Hi ' . $name . ',<br />
				   // <p>Member account created, following are the login credentials,</p>
				   // <p>username : ' . $_POST["userName"] . '</p>
				   // <p>password : ' . $_POST["password"] . '</p>';
				   // $htmlTemplate =  file_get_contents(base_path("templates/newsletter/registration.php"));
				   // $htmlTemplate = str_replace("*message*", $message, $htmlTemplate);
				   // $this->email->message($htmlTemplate);
				   // $this->email->send();
				   // // mail chimp integration
				   // $this->load->library('MCAPI');
				   // $retval = $this->mcapi->listSubscribe('57f11536f6', $email);
				    $emailofuser=$_POST['email'];
                    $nameofuser=$_POST['firstName'];
                    $passofuser=$_POST['member_password'];
                    $unameofuser=$_POST['member_username'];
                    if(!empty($emailofuser)){
        				$data = array('emailId' => $emailofuser, 'name' => $nameofuser, 'pwd'=>$passofuser,'uname' => $unameofuser);
        				Mail::send('mails.members.addMember',['data' => $data], function ($message) use ($data) {
        				  $message->to($data['emailId']);
        				  $message->subject('New Member Account Created at Digiwaxx by Admin');
        				  $message->from('business@digiwaxx.com','Digiwaxx');
        			   });
                    }


				   Session::put('alertMessage', 'Member added successfully !');
				   Session::put('alertClass', 'alert alert-success');
				   header("location: " . url("admin/add_member?success=1"));
				   exit;
			   } else if ($result['type'] == 0 && $result['success'] < 1) {

				   Session::put('alertMessage', 'Error occured, please try again !');
				   Session::put('alertClass', 'alert alert-danger');
				   header("location: " . url("admin/add_member?error=2"));
				   exit;
			   }
		   }
		   
        if(isset($_GET['success'])){
		    if($_GET['success']==1){
		        $output['alert_message']='Member added successfully !';
				$output['alert_class']='alert alert-success';
		    }else{
		        $output['alert_message']='';
				$output['alert_class']='';
		    }
		}
		else if(isset($_GET['error'])){
		    if($_GET['error']==2){
		        $output['alert_message']='Error occured, please try again !';
				$output['alert_class']='alert alert-danger';
		    }
		    else if($_GET['error']==1){
		        $output['alert_message']=' email already, exists !';
				$output['alert_class']='alert alert-danger';
		     }else if($_GET['error']==3){
		        $output['alert_message']= ' username already, exists !';
				$output['alert_class']='alert alert-danger';
		     }
		     else{
		        $output['alert_message']='';
				$output['alert_class']='';
		    }
		}else{
		    $output['alert_message']='';
			$output['alert_class']='';
		}
		  // if(!empty(Session::get('alertMessage'))){
		       
			 //  $output['alert_message'] = Session::get('alertMessage');
			 //  $output['alert_class'] = Session::get('alertClass');
	  
			 //  Session::forget('alertMessage');
			 //  Session::forget('alertClass');
		  // }
		   //$result = $this->admin_model->ad_mem_getMembers($where,$start,$limit); 
		   //$output['members'] = $result['data'];
		   $output['countries'] = $this->admin_model->ad_mem_getCountries();
		   $output['states'] = $this->admin_model->ad_mem_getStates(1);
		   $output['memberships'] = $this->admin_model->ad_mem_getMembership();

		   // $this->load->view('admin/header', $headerOutput);
		   // $this->load->view('add_member', $output);
		   // $this->load->view('admin/footer');


		   //  dd($output);

		   return view('admin/add_member', $output);



	}

	function member_payments()
	{  
		 // header data pass starts
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
	   // print_r($logo_details->logo);die;

		   $output = array();

		   $output['pageTitle'] = 'Payment Members';
		   $output['logo'] = $get_logo;
		   $output['welcome_name'] = $admin_name;
		   $output['user_role'] = $user_role;
	   
		 // access modules
		 $output['access'] = $this->admin_model->getAdminModules($admin_id);

		   // header data pass ends

		   // generate where 
		   $where = 'where ';
		   $whereItems = array();
		   $whereItems[] = "member_subscriptions.status = '1'";
		   $output['searchPackage'] = '';
		   $output['searchUsername'] = '';
		   if (isset($_GET['package']) && $_GET['package'] > 0) {
			   $output['searchPackage'] = $_GET['package'];
			   $whereItems[] = "member_subscriptions.package_Id = '" . $_GET['package'] . "'";
		   }
		   if (isset($_GET['username']) && strlen($_GET['username']) > 0) {
			   $output['searchUsername'] = $_GET['username'];
			   $whereItems[] = "members.uname = '" . $_GET['username'] . "'";
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
		   $sortBy = "member_subscriptions.subscription_Id";
		   $output['sortBy'] = 'paidOn';
		   $output['sortOrder'] = 2;
		   if (isset($_GET['sortBy'])) {
			   $output['sortBy'] = $_GET['sortBy'];
			   if (strcmp($_GET['sortBy'], 'username') == 0) {
				   $sortBy = "members.uname";
			   } else if (strcmp($_GET['sortBy'], 'package') == 0) {
				   $sortBy = "member_subscriptions.package_Id";
			   } else if (strcmp($_GET['sortBy'], 'paidOn') == 0) {
				   $sortBy = "member_subscriptions.subscription_Id";
			   }
		   }
		   // sort order	  		   
		   if (isset($_GET['sortOrder']) && $_GET['sortOrder'] == 1) {
			   $sortOrder = "ASC";
			   $output['sortOrder'] = $_GET['sortOrder'];
		   } else  if (isset($_GET['sortOrder']) && $_GET['sortOrder'] == 2) {
			   $sortOrder = "DESC";
			   $output['sortOrder'] = $_GET['sortOrder'];
		   }
		   $sort =  $sortBy . " " . $sortOrder;
		   // generate link
		   $output['link_string'] = '?';
		   if (isset($_GET) && count($_GET) > 0) {
			   $link_items = array();
			   $link_string =  '?';
			   $exclude_variables = array("page");
			   foreach ($_GET as $key => $value) {
				   if (!(in_array($key, $exclude_variables))) {
					   $link_items[] = $key . '=' . $value;
				   }
			   }
			   if (count($link_items) > 1) {
				   $link_string = implode('&', $link_items);
				   $link_string = '?' . $link_string . '&';
			   } else if (count($link_items) == 1) {
				   $link_string = '?' . $link_items[0] . '&';
			   }
			   $output['link_string'] = $link_string;
		   }
		   // pagination
		   $limit = 10;
		   if (isset($_GET['numRecords'])) {
			   $limit = $_GET['numRecords'];
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
		   $num_records = $this->admin_model->ad_mem_getNumMemberPayments($where, $sort);
		   $numPages = (int) ($num_records / $limit);
		   $reminder = ($num_records % $limit);
		   if ($reminder > 0) {
			   $numPages = $numPages + 1;
		   }
		   $output['numPages'] = $numPages;
		   $output['start'] = $start;
		   $output['currentPageNo'] = $currentPageNo;
		   $output['currentPage'] = 'member_payments';
		   if (isset($_GET['page'])) {
			   if ($_GET['page'] > $numPages) {
				   header("location: " . $output['currentPage'] . $link_string . "page=" . $numPages);
				   exit;
			   } else if ($_GET['page'] < 1) {
				   header("location: " . $output['currentPage'] . $link_string . "page=1");
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
		   $output['payments'] = $this->admin_model->ad_mem_getMemberPayments($where, $sort, $start, $limit);
		   if ($output['payments']['numRows'] > 0) {
			   foreach ($output['payments']['data'] as $payment) {
				   if ($payment->paymentType == 1) {
					   $output['stripeData'] = $this->admin_model->ad_mem_getStripeDetails($payment->subscription_Id);
				   } else if ($payment->paymentType == 2) {
					   $output['paypalData'] = $this->admin_model->ad_mem_getPaypalDetails($payment->subscription_Id);
				   }
			   }
		   }
		   // $this->load->view('admin/header', $headerOutput);
		   // $this->load->view('payments', $output);
		   // $this->load->view('admin/footer');

		   
			//dd($output);

		   return view('admin/payments', $output);

	}

	function member_payment_view()
	{  
		 // header data pass starts
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
	   // print_r($logo_details->logo);die;

		   $output = array();

		   $output['pageTitle'] = 'View Member Payment';
		   $output['logo'] = $get_logo;
		   $output['welcome_name'] = $admin_name;
		   $output['user_role'] = $user_role;
	   
		 // access modules
		 $output['access'] = $this->admin_model->getAdminModules($admin_id);

		   // header data pass ends

		   // generate where 
		   $where = 'where ';
		   $whereItems = array();
		   $whereItems[] = "member_subscriptions.status = '1'";
		   $whereItems[] = "member_subscriptions.subscription_Id = '" . $_GET['pid'] . "'";
		   $output['searchPackage'] = '';
		   $output['searchUsername'] = '';
		   if (isset($_GET['search'])) {
			   if (isset($_GET['package']) && $_GET['package'] > 0) {
				   $output['searchPackage'] = $_GET['package'];
				   $whereItems[] = "member_subscriptions.package_Id = '" . $_GET['package'] . "'";
			   }
			   if (isset($_GET['username']) && strlen($_GET['username']) > 0) {
				   $output['searchUsername'] = $_GET['username'];
				   $whereItems[] = "clients.uname = '" . $_GET['username'] . "'";
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
		   $sortBy = "member_subscriptions.subscription_Id";
		   $output['sortBy'] = 'paidOn';
		   $output['sortOrder'] = 2;
		   if (isset($_GET['sortBy']) && isset($_GET['sortOrder'])) {
			   $output['sortBy'] = $_GET['sortBy'];
			   $output['sortOrder'] = $_GET['sortOrder'];
			   if (strcmp($_GET['sortBy'], 'username') == 0) {
				   $sortBy = "clients.uname";
			   } else if (strcmp($_GET['sortBy'], 'package') == 0) {
				   $sortBy = "member_subscriptions.package_Id";
			   } else if (strcmp($_GET['sortBy'], 'paidOn') == 0) {
				   $sortBy = "member_subscriptions.subscription_Id";
			   } else if (strcmp($_GET['sortBy'], 'lastLogin') == 0) {
				   $sortBy = "";
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
		   $start = 0;
		   $output['currentPage'] = 'client_payments';
		   // pagination
		   $output['payments'] = $this->admin_model->ad_mem_getMemberPayments($where, $sort, $start, $limit);
		   if ($output['payments']['numRows'] > 0) {
			   foreach ($output['payments']['data'] as $payment) {
				   if ($payment->paymentType == 1) {
					   $output['stripeData'] = $this->admin_model->ad_mem_getStripeDetails($payment->subscription_Id);
				   } else if ($payment->paymentType == 2) {
					   $output['paypalData'] = $this->admin_model->ad_mem_getPaypalDetails($payment->subscription_Id);
				   }
			   }
		   }
		   // $this->load->view('admin/header', $headerOutput);
		   // $this->load->view('payment_view', $output);
		   // $this->load->view('admin/footer');

			// dd($output);

			return view('admin/payment_view', $output);
	}

	function pending_members()
	{  
		 // header data pass starts
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

		   $output['pageTitle'] = 'Pending Member Requests';
		   $output['logo'] = $get_logo;
		   $output['welcome_name'] = $admin_name;
		   $output['user_role'] = $user_role;
		   
		    $output['searchCompany'] = '';
		   $output['searchUsername'] = '';
		   $output['searchFirstName'] = '';
		   $output['searchLastName'] = '';
		   $output['searchStageName'] = '';
		   $output['searchEmail'] = '';
		   $output['searchPhone'] = '';
		   $output['searchCity'] = '';
		   $output['searchState'] = '';
		   $output['searchZip'] = '';
	   
		 // access modules
		 $output['access'] = $this->admin_model->getAdminModules($admin_id);

		   // header data pass ends

		   if(isset($_GET['actionmanager']) && $_GET['actionmanager'] == 'deleteall'){
			   if($_GET['item'][0] == 'on'){
				   $chkData = $_GET['item'];
				   $chkData =  array_slice($chkData,1);	
			   
			   }else{
				   $chkData = $_GET['item'];
			   }
			   if(!empty($chkData)){
				   foreach($chkData as $chkid){
					   $result = $this->admin_model->ad_mem_deleteMember($chkid);
				   }
				 
				   Session::put('alertMessage', 'Members deleted successfully !');
				   Session::put('alertClass', 'alert alert-success');
				   header("location: " . url("admin/pending_members?delete=success"));
				   exit;
			   }
			   else {
				   
				   Session::put('alertMessage', 'Error occured, please try again !');
				   Session::put('alertClass', 'alert alert-danger');
				   header("location: " . url("admin/pending_members?error=1"));
				   exit;
			   }
		   }
		   
		   
		   
		   if(isset($_GET['actionmanager']) && $_GET['actionmanager'] == 'approveall'){
			   if($_GET['item'][0] == 'on'){
				   $chkData = $_GET['item'];
				   $chkData =  array_slice($chkData,1);	
			   
			   }else{
				   $chkData = $_GET['item'];
			   }
			   
			   if(!empty($chkData)){
				   foreach($chkData as $chkid){
					   $result = $this->admin_model->ad_mem_acceptMember($chkid);

					   //dd($_GET);
					   //New Code
					   $output['members'] = $this->admin_model->ad_mem_getMemberInfo($chkid);
					   
					   // print_r($output['members']);die;
					   $current_email=urldecode($output['members']['data'][0]->email);


					   // mail to client starts here (uncomment this on live)

					   // $message = "Dear ".$output['members']['data'][0]->fname." ".$output['members']['data'][0]->lname.",<br><br>
					   // Based on your recent application we would like to officially WELCOME YOU AS AN OFFICIAL MEMBER OF DIGIWAXX!!!<br><br>
					   // As a member you will have continuous access to our extensive music library, access to industry and record label contacts and much more in return for your honest feedback and review on various music projects. <br><br>
					   // Our job is to work with you as a DJ, Programmer, Music Supervisor or a selected Music Influencer to listen, survey and give honest creative feedback that will help to grow their careers organically. In return we offer you the opportunity to download FREE MUSIC on our exclusive platform.<br><br> 
					   // Your feedback is sent only to the artist Record Label that you leave a review for and will not travel outside of here unless you choose to share your opinions socially. <br><br>
					   // In addition, we look forward to learning from you on special projects, ideas on how to grow our network and any other thoughts that you may have. <br><br>
					   // If you have any questions or needs please to not hesitate to contact us at admin@digiwaxx.comor call us directly at 866.665.1259. We look forward to working with you once again.<br><br> WELCOME TO DIGIWAXX!!! <br><br>
					   // ";
					   
						
					   // //	$email ='school_test007@yopmail.com';
					   
					   //     $m_sub = 'Digiwaxx - Account Approved';
						   
						   
					   //     $mail_data = [
					   //         'm_sub' => $m_sub,
					   //         'm_msg' => $message,
					   //     ];

					   //   $cc_mails = 'admin@digiwaxx.com';
					   //     //$bcc_mails = '';
						   


					   //     $sendInvoiceMail = Mail::to($current_email);

					   //     if(!empty($cc_mails)){
					   //         $sendInvoiceMail->cc($cc_mails);
					   //     }
				   
					   //         // if(!empty($bcc_mails)){
					   //         // 	$sendInvoiceMail->bcc($bcc_mails);
					   //         // }
		   
							   
					   //         $sendInvoiceMail->send(new AdminForgetNotification($mail_data));

					   //         $ss= 00;

					   //         if(count(Mail::failures()) > 0){
					   //             $ss= 22;
					   //         }
					   //         else{
					   //             $ss=11;
					   //         }

						// email ends here



		   //             $this->load->library('email');
		   // // 			$this->email->from('info@digiwaxx.com', 'Digiwaxx');
		   
		   //             $this->email->from('admin@digiwaxx.com', 'Digiwaxx');
		   //             $this->email->cc('admin@digiwaxx.com');
		   
		   //             $this->email->to($current_email);
		   //             $this->email->set_mailtype("html");
		   //             $this->email->subject('Digiwaxx - Account Approved');
		   //             $this->email->message("Dear ".$output['members']['data'][0]->fname." ".$output['members']['data'][0]->lname.",<br><br>
		   //             Based on your recent application we would like to officially WELCOME YOU AS AN OFFICIAL MEMBER OF DIGIWAXX!!!<br><br>
		   //             As a member you will have continuous access to our extensive music library, access to industry and record label contacts and much more in return for your honest feedback and review on various music projects. <br><br>
		   //             Our job is to work with you as a DJ, Programmer, Music Supervisor or a selected Music Influencer to listen, survey and give honest creative feedback that will help to grow their careers organically. In return we offer you the opportunity to download FREE MUSIC on our exclusive platform.<br><br> 
		   //             Your feedback is sent only to the artist Record Label that you leave a review for and will not travel outside of here unless you choose to share your opinions socially. <br><br>
		   //             In addition, we look forward to learning from you on special projects, ideas on how to grow our network and any other thoughts that you may have. <br><br>
		   //             If you have any questions or needs please to not hesitate to contact us at admin@digiwaxx.comor call us directly at 866.665.1259. We look forward to working with you once again.<br><br> WELCOME TO DIGIWAXX!!! <br><br>
		   //             ");
					   // $ss= 00;
					   // if($this->email->send()){  $ss= 11; }else{$ss=22;}
					   //New Code End here
					   
					   ##approval mail
                       $emailofuser=$current_email;
                       $nameofuser=urldecode($output['members']['data'][0]->fname);
                   
                    if(!empty($emailofuser)){
        				$data = array('emailId' => $emailofuser, 'name' => $nameofuser);
        				Mail::send('mails.members.approveMember',['data' => $data], function ($message) use ($data) {
        				  $message->to($data['emailId']);
        				  $message->subject('Member Account Approved at Digiwaxx by Admin');
        				  $message->from('business@digiwaxx.com','Digiwaxx');
        			   });
                    }					   
				   }
				   
					Session::put('alertMessage', 'Members verified successfully!');
					Session::put('alertClass', 'alert alert-success');
					header("location: " . url("admin/pending_members?accepted=1"));
					exit;
			   }
			   else {      
				   Session::put('alertMessage', 'Error occured, please try again !');
				   Session::put('alertClass', 'alert alert-danger');
				   header("location: " . url("admin/pending_members?error=1"));
				   exit;
			   }
   
			   
		   }
		   if(!empty(Session::get('alertMessage'))){
	   
			   $output['alert_message'] = Session::get('alertMessage');
			   $output['alert_class'] = Session::get('alertClass');
	  
			   Session::forget('alertMessage');
			   Session::forget('alertClass');
		   }
		   
		   
		   // delete member
		   if (isset($_GET['did'])) {
		   }
		   // generate where
		   $where = 'where ';
		   $whereItems[] = "members.deleted = '0'";
		   $whereItems[] = "members.active = '0'";
		   $output['searchCompany'] = '';
		   $output['searchUsername'] = '';
		   $output['searchName'] = '';
		   $output['searchEmail'] = '';
		   $output['searchPhone'] = '';
		   $output['searchCity'] = '';
		   $output['searchState'] = '';
		   $output['searchZip'] = '';
		   if (isset($_GET['search'])) {
			   if (isset($_GET['firstName']) && strlen($_GET['firstName']) > 0) {
				   $output['searchFirstName'] = $_GET['firstName'];
				   $whereItems[] = "members.fname = '" . urlencode($_GET['firstName']) . "'";
			   }
			   if (isset($_GET['lastName']) && strlen($_GET['lastName']) > 0) {
				   $output['searchLastName'] = $_GET['lastName'];
				   $whereItems[] = "members.lname = '" . urlencode($_GET['lastName']) . "'";
			   }
			   if (isset($_GET['stageName']) && strlen($_GET['stageName']) > 0) {
				   $output['searchStageName'] = $_GET['stageName'];
				   $whereItems[] = "members.stagename = '" . urlencode($_GET['stageName']) . "'";
			   }
			   if (isset($_GET['email']) && strlen($_GET['email']) > 0) {
				   $output['searchEmail'] = $_GET['email'];
				   $whereItems[] = "members.email = '" . urlencode($_GET['email']) . "'";
			   }
			   if (isset($_GET['phone']) && strlen($_GET['phone']) > 0) {
				   $output['searchPhone'] = $_GET['phone'];
				   $whereItems[] = "members.phone = '" . $_GET['phone'] . "'";
			   }
			   if (isset($_GET['username']) && strlen($_GET['username']) > 0) {
				   $output['searchUsername'] = $_GET['username'];
				   $whereItems[] = "members.uname = '" . $_GET['username'] . "'";
			   }
			   if (isset($_GET['city']) && strlen($_GET['city']) > 0) {
				   $output['searchCity'] = $_GET['city'];
				   $whereItems[] = "members.city = '" . $_GET['city'] . "'";
			   }
			   if (isset($_GET['state']) && strlen($_GET['state']) > 0) {
				   $output['searchState'] = $_GET['state'];
				   $whereItems[] = "members.state = '" . $_GET['state'] . "'";
			   }
			   if (isset($_GET['zip']) && strlen($_GET['zip']) > 0) {
				   $output['searchZip'] = $_GET['zip'];
				   $whereItems[] = "members.zip = '" . $_GET['zip'] . "'";
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
		   $output['sortBy'] = 'registered';
		   $output['sortOrder'] = 2;
		   if (isset($_GET['sortBy']) && isset($_GET['sortOrder'])) {
			   $output['sortBy'] = $_GET['sortBy'];
			   $output['sortOrder'] = $_GET['sortOrder'];
			   if (strcmp($_GET['sortBy'], 'registered') == 0) {
				   $sortBy = "id";
			   } else if (strcmp($_GET['sortBy'], 'firstName') == 0) {
				   $sortBy = "fname";
			   } else if (strcmp($_GET['sortBy'], 'lastName') == 0) {
				   $sortBy = "lname";
			   } else if (strcmp($_GET['sortBy'], 'stageName') == 0) {
				   $sortBy = "stagename";
			   } else if (strcmp($_GET['sortBy'], 'email') == 0) {
				   $sortBy = "email";
			   } else if (strcmp($_GET['sortBy'], 'username') == 0) {
				   $sortBy = "uname";
			   } else if (strcmp($_GET['sortBy'], 'phone') == 0) {
				   $sortBy = "phone";
			   } else if (strcmp($_GET['sortBy'], 'city') == 0) {
				   $sortBy = "city";
			   } else if (strcmp($_GET['sortBy'], 'state') == 0) {
				   $sortBy = "title";
			   } else if (strcmp($_GET['sortBy'], 'lastLogin') == 0) {
				   $sortBy = "state";
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
		   $num_records = $this->admin_model->ad_mem_getNumMembers($where, $sort);
		   $numPages = (int) ($num_records / $limit);
		   $reminder = ($num_records % $limit);
		   if ($reminder > 0) {
			   $numPages = $numPages + 1;
		   }
		   $output['numPages'] = $numPages;
		   $output['start'] = $start;
		   $output['currentPageNo'] = $currentPageNo;
		   $output['currentPage'] = 'pending_members';
		   if (isset($_GET['page'])) {
			   if ($_GET['page'] > $numPages) {
				   header("location: " . $output['currentPage'] . "?page=" . $numPages);
				   exit;
			   } else if ($_GET['page'] < 1) {
				   header("location: " . $output['currentPage'] . "?page=1");
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
		   $result = $this->admin_model->ad_mem_getMembers($where, $sort, $start, $limit);
		   $output['members'] = $result['data'];
		  // if (isset($_GET['delete'])) {
			 
			 //  Session::put('alertMessage', 'Member deleted successfully !');
			 //  Session::put('alertClass', 'alert alert-success');
		  // } 
		  // else  if (isset($_GET['error'])) {
			 //  Session::put('alertMessage', 'Error occured, please try again !');
			 //  Session::put('alertClass', 'alert alert-danger');
		  // }
		  
		   if (isset($_GET['accepted'])) {
			   $output['alert_message'] = 'Member verified successfully!';
			   $output['alert_class'] = 'alert alert-success';
		   } else if (isset($_GET['declined'])) {
			   $output['alert_message'] = 'Member declined successfully!';
			   $output['alert_class'] = 'alert alert-success';
		   } else if (isset($_GET['delete'])) {
			   $output['alert_message'] = 'Member deleted successfully!';
			   $output['alert_class'] = 'alert alert-success';
		   } else if (isset($_GET['error'])) {
			   $output['alert_message'] = 'Error occured, please try again!';
			   $output['alert_class'] = 'alert alert-danger';
		   }
		   else{
		       $output['alert_message']='';
		       $outputp['alert_class']='';
		   }
		  
		   // $this->load->view('admin/header', $headerOutput);
		   // $this->load->view('pending_members', $output);
		   // $this->load->view('admin/footer');

		   //  dd($output);

			return view('admin/pending_members', $output);

	}

	function manage_pending_member()
	{  
		 // header data pass starts
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
	   // print_r($logo_details->logo);die;

		   $output = array();
		   
		   	if(isset($_GET['mid']) && !empty($_GET['mid'])){
				  $apprvSts = $this->admin_model->checkIfApprovedMember($_GET['mid']);
				  /* print_r($apprvSts);die; */
				  if($apprvSts['numRows'] == 0){
					return Redirect::to("admin/pending_members");exit;  
				  }		   
		   }

		   $output['pageTitle'] = 'Manage Member';
		   $output['logo'] = $get_logo;
		   $output['welcome_name'] = $admin_name;
		   $output['user_role'] = $user_role;
	   
		 // access modules
		 $output['access'] = $this->admin_model->getAdminModules($admin_id);

		   // header data pass ends

		  
		   if (isset($_GET['acceptId']) && isset($_GET['mid']) && ($_GET['mid'] == $_GET['acceptId'])) {
			   $result = $this->admin_model->ad_mem_acceptMember($_GET['mid']);
			   if ($result > 0) {
   
				   //New Code
				   $output['members'] = $this->admin_model->ad_mem_getMemberInfo($_GET['mid']);
				   
				   // print_r($output['members']);die;
				   $current_email=urldecode($output['members']['data'][0]->email);

				   // mail to client starts here (uncomment this on live)

					   // $message = "Dear ".$output['members']['data'][0]->fname." ".$output['members']['data'][0]->lname.",<br><br>
					   // Based on your recent application we would like to officially WELCOME YOU AS AN OFFICIAL MEMBER OF DIGIWAXX!!!<br><br>
					   // As a member you will have continuous access to our extensive music library, access to industry and record label contacts and much more in return for your honest feedback and review on various music projects. <br><br>
					   // Our job is to work with you as a DJ, Programmer, Music Supervisor or a selected Music Influencer to listen, survey and give honest creative feedback that will help to grow their careers organically. In return we offer you the opportunity to download FREE MUSIC on our exclusive platform.<br><br> 
					   // Your feedback is sent only to the artist Record Label that you leave a review for and will not travel outside of here unless you choose to share your opinions socially. <br><br>
					   // In addition, we look forward to learning from you on special projects, ideas on how to grow our network and any other thoughts that you may have. <br><br>
					   // If you have any questions or needs please to not hesitate to contact us at admin@digiwaxx.comor call us directly at 866.665.1259. We look forward to working with you once again.<br><br> WELCOME TO DIGIWAXX!!! <br><br>
					   // ";
					   
						
					   // //	$email ='school_test007@yopmail.com';
					   
					   //     $m_sub = 'Digiwaxx - Account Approved';
						   
						   
					   //     $mail_data = [
					   //         'm_sub' => $m_sub,
					   //         'm_msg' => $message,
					   //     ];

					   //   $cc_mails = 'admin@digiwaxx.com';
					   //     //$bcc_mails = '';
						   


					   //     $sendInvoiceMail = Mail::to($current_email);

					   //     if(!empty($cc_mails)){
					   //         $sendInvoiceMail->cc($cc_mails);
					   //     }
				   
					   //         // if(!empty($bcc_mails)){
					   //         // 	$sendInvoiceMail->bcc($bcc_mails);
					   //         // }
		   
							   
					   //         $sendInvoiceMail->send(new AdminForgetNotification($mail_data));

					   //         $ss= 00;

					   //         if(count(Mail::failures()) > 0){
					   //             $ss= 22;
					   //         }
					   //         else{
					   //             $ss=11;
					   //         }

						// email ends here







				   
	   //             $this->load->library('email');
	   // // 			$this->email->from('info@digiwaxx.com', 'Digiwaxx');
	   
	   //             $this->email->from('admin@digiwaxx.com', 'Digiwaxx');
	   //             $this->email->cc('admin@digiwaxx.com');
	   
	   //             $this->email->to($current_email);
	   //             $this->email->set_mailtype("html");
	   //             $this->email->subject('Digiwaxx - Account Approved');
	   //             $this->email->message("Dear ".$output['members']['data'][0]->fname." ".$output['members']['data'][0]->lname.",<br><br>
	   //             Based on your recent application we would like to officially WELCOME YOU AS AN OFFICIAL MEMBER OF DIGIWAXX!!!<br><br>
	   //             As a member you will have continuous access to our extensive music library, access to industry and record label contacts and much more in return for your honest feedback and review on various music projects. <br><br>
	   //             Our job is to work with you as a DJ, Programmer, Music Supervisor or a selected Music Influencer to listen, survey and give honest creative feedback that will help to grow their careers organically. In return we offer you the opportunity to download FREE MUSIC on our exclusive platform.<br><br> 
	   //             Your feedback is sent only to the artist Record Label that you leave a review for and will not travel outside of here unless you choose to share your opinions socially. <br><br>
	   //             In addition, we look forward to learning from you on special projects, ideas on how to grow our network and any other thoughts that you may have. <br><br>
	   //             If you have any questions or needs please to not hesitate to contact us at admin@digiwaxx.comor call us directly at 866.665.1259. We look forward to working with you once again.<br><br> WELCOME TO DIGIWAXX!!! <br><br>
	   //             ");
	   //             $ss= 00;
	   //             if($this->email->send()){  $ss= 11; }else{$ss=22;}
   
				   //New Code End
				   
				   
				   ## Approval Mail
				    $emailofuser=$current_email;
                    $nameofuser=urldecode($output['members']['data'][0]->fname);
                   
                    if(!empty($emailofuser)){
        				$data = array('emailId' => $emailofuser, 'name' => $nameofuser);
        				Mail::send('mails.members.approveMember',['data' => $data], function ($message) use ($data) {
        				  $message->to($data['emailId']);
        				  $message->subject('Member Account Approved at Digiwaxx by Admin');
        				  $message->from('business@digiwaxx.com','Digiwaxx');
        			   });
                    }
				   
				   
				   
				   header("location: " . url("admin/manage_pending_member?mid=" . $_GET['mid'] . "&accepted=1"));
				   exit;
			   } else {
				   header("location: " . url("admin/manage_pending_member?mid=" . $_GET['mid'] . "&error=1"));
				   exit;
			   }
		   }
		   if (isset($_GET['declineId']) && isset($_GET['mid']) && ($_GET['mid'] == $_GET['declineId'])) {
			   $result = $this->admin_model->ad_mem_declineMember($_GET['mid']);
			   if ($result > 0) {
				   
				   
				   header("location: " . url("admin/manage_pending_member?mid=" . $_GET['mid'] . "&declined=1"));
				   exit;
			   } else {
				   header("location: " . url("admin/manage_pending_member?mid=" . $_GET['mid'] . "&error=1"));
				   exit;
			   }
		   }
		   if (isset($_GET['deleteId']) && isset($_GET['mid']) && ($_GET['mid'] == $_GET['deleteId'])) {
			   $result = $this->admin_model->ad_mem_deleteMember($_GET['mid']);
			   if ($result > 0) {
				   header("location: " . url("admin/manage_pending_member?mid=" . $_GET['mid'] . "&deleted=1"));
				   exit;
			   } else {
				   header("location: " . url("admin/manage_pending_member?mid=" . $_GET['mid'] . "&error=1"));
				   exit;
			   }
		   }
		   if (isset($_GET['accepted'])) {
			   $output['alert_message'] = 'Member verified successfully!';
			   $output['alert_class'] = 'alert alert-success';
		   } else if (isset($_GET['declined'])) {
			   $output['alert_message'] = 'Member declined successfully!';
			   $output['alert_class'] = 'alert alert-success';
		   } else if (isset($_GET['deleted'])) {
			   $output['alert_message'] = 'Member deleted successfully!';
			   $output['alert_class'] = 'alert alert-success';
		   } else if (isset($_GET['error'])) {
			   $output['alert_message'] = 'Error occured, please try again!';
			   $output['alert_class'] = 'alert alert-danger';
		   }
		   $output['members'] = $this->admin_model->ad_mem_getMemberInfo($_GET['mid']);
		   $output['social'] = $this->admin_model->ad_mem_getMemberSocial($_GET['mid']);
		   // $this->load->view('admin/header', $headerOutput);
		   // $this->load->view('manage_member', $output);
		   // $this->load->view('admin/footer');

			 //  dd($output);

			 return view('admin/manage_member', $output);

	}

	function add_multiple_member()
	{  
		 // header data pass starts
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
	   // print_r($logo_details->logo);die;

		   $output = array();

		   $output['pageTitle'] = 'Add Multiple Member';
		   $output['logo'] = $get_logo;
		   $output['welcome_name'] = $admin_name;
		   $output['user_role'] = $user_role;
	   
		 // access modules
		 $output['access'] = $this->admin_model->getAdminModules($admin_id);

		   // header data pass ends

	   
		   if (isset($_POST['addMultipleMembers'])) {
			 
			   $result = $this->admin_model->ad_mem_addMultipleMembers($_POST['email']);
			   // existing emailids
			   $existCount = count($result[1]);
			   foreach ($result[1] as $key => $value) {
				   // $_SESSION['exist_emails'][] = $value;
				   Session::put('exist_emails[]', $value);
			   }
			   $count = count($result[0]);
			   if ($count > 0) {
				   // mail chimp integration
				  // $this->load->library('MCAPI');
				  //pArr($result[0]);die();
    				   foreach ($result[0] as $email => $password) {
    					   $email = urldecode($email);
    					   $pass= urldecode($password);
    					   
    					 
    					    $message = 'Hi member,<br><br>Your DJ Member account has been created by the admin team.<br><br>
    					                Your login credentials are :<br><br>
    					                Email: <strong>'.$email.'</strong> <br><br>
    					                Password: <strong>'.$pass.'</strong><br><br>
                                		Thank you for being a quality member and keep breaking boundaries!<br><br>Digiwaxx Admin Team';
                   /*  $this->email->message($message);
                    $this->email->send(); */
    				
    					$data = array('emailId' => $email, 'message'=> $message);
    				
    					Mail::send('mails.password.adminMemberReset',['data' => $data], function ($message) use ($data) {
    					  $message->to($data['emailId']);
    					  $message->subject('Member Account Registered at Digiwaxx');
    					  $message->from('business@digiwaxx.com','Digiwaxx');
    				   });
    			 }
    			 header("location: " . url("admin/add_multiple_member?response=4"));
			       exit;
    			 
			   }else{
			       header("location: " . url("admin/add_multiple_member?response=3"));
			       exit;
			   }

			
		   }
		   if(isset($_GET['response']) && $_GET['response']==3){
		       $output['alertMessage'] ='Email already exsist.';
			   $output['alertClass'] = 'alert alert-danger';
		   }
		   if(isset($_GET['response']) && $_GET['response']==4){
		       $output['alertMessage'] ='Only new members added successfully!';
			   $output['alertClass'] = 'alert alert-success';
		   }
		   
		   // $this->load->view('admin/header', $headerOutput);
		   // $this->load->view('add_multiple_member', $output);
		   // $this->load->view('admin/footer');

			  //dd($output);
            //print_r($output);
    //          $output['alertMessage'] ='Error occured, please try again!';
			 //  $output['alertClass'] = 'alert alert-danger';
			return view('admin/add_multiple_member', $output);

	
	}


			//  Controller functions added by R-S ends here 
	public function admin_sort_tracks(Request $request){
    //   $response = array(
    //       'status' => 'success',
    //       'array' => $request->array_id,
    //       'dragfrom' => $request->dragged_from,
    //       'attr_value' => $request->dragged_data_id,
    //       'dragto' => $request->dragged_to,
    //       'page' => $request->page
    //   ); 
      $arr = $request->array_id;
      $order_range = ($request->page-1)*$request->limit;
      $array_decode = json_decode(json_encode($arr),true);
      
      $totalPageEle = ($request->page)*$request->limit;
      /*--- Custom by GS ---*/
      
      $draggedFrom = $request->dragged_from;
      $draggedTo = $request->dragged_to;
      $whichTrackIs = $request->dragged_data_id;
      
      $getTrackPositionInDB = DB::table('tracks')->select('order_position')->where('id', '=', $whichTrackIs)->first();
      $currentElePosInDB = $getTrackPositionInDB->order_position;
      $myTrakArr = array();
      foreach($array_decode as $valArr){
            $getTrackPositionInDB1 = DB::table('tracks')->select('order_position')->where('id', '=', $valArr)->first();
            $currentElePosInDB1 = $getTrackPositionInDB1->order_position;
            if(!empty($currentElePosInDB) && $currentElePosInDB > 0){
                $myTrakArr[$valArr] = $currentElePosInDB1;
            }else{
                $myTrakArr[$valArr] = $valArr;
            }
      }
     
      if(!empty($currentElePosInDB) && $currentElePosInDB > 0){
          $trkposIs = $currentElePosInDB;
      }else{
          $trkposIs = $whichTrackIs;
      }
      
      $howManySteps = '';
      $productsArrangeArr = array();
      ## DownShifted - Minus
       
      if($draggedFrom < $draggedTo){
          
          $howManySteps = $draggedTo - $draggedFrom;
          $newPosition = $trkposIs-$howManySteps;
          $productsArrangeArr[$whichTrackIs] = $newPosition;
          for($step=$draggedFrom;$step<$draggedTo;$step++){
              $trackIs = $array_decode[$step];
              $addTo = $draggedTo-$step;
              if(isset($productsArrangeArr[$trackIs])){
                  continue;
              }
              $productsArrangeArr[$trackIs] = $newPosition+$addTo;
          }
          
      }else{
          
          $howManySteps = $draggedFrom - $draggedTo;
          $newPosition = $trkposIs+$howManySteps;
          $productsArrangeArr[$whichTrackIs] = $newPosition;
          for($stp=$draggedTo;$stp<=$draggedFrom;$stp++){
              $trackIss = $array_decode[$stp];
              $minusTo = $draggedFrom-$stp;
              if(isset($productsArrangeArr[$trackIss])){
                  continue;
              }
              $newPosition = ((int)$myTrakArr[$trackIss])-1;
              $productsArrangeArr[$trackIss] = $newPosition;
          }          
      }
      
      //$getTrackPositionInDB = DB::table('tracks')->select('order_position')->where('id', '=', $whichTrackIs)->first();
      //$currentElePosInDB = $getTrackPositionInDB->order_position;
     // echo'<pre>';print_r($productsArrangeArr);die();  
      //echo $newPosition;die();
      
      /*--- Custom by GS ---*/
      if(count($productsArrangeArr) > 0){
      foreach($productsArrangeArr as $trackId => $trkPosIs){
          //$order_pos = $pos + $order_range;
          $order_position_insert = DB::table('tracks')
                                        ->where('id', '=', $trackId)
                                        ->update(array('order_position' => $trkPosIs));
          
      }
      }
      return response()->json('success'); 
   }
   
   
   
   
   
 
 //--------------Forum functionality starts here---------------------------------------------------------------
 
 
 
  
 public function list_forums(Request $request){
    $output=array();
    $admin_name = Auth::user()->name;
    $user_role = Auth::user()->user_role;
    $output['pageTitle'] = 'Forums';
    $output['user_role'] = $user_role;
    
    $output['currentPage'] = 'forums';
    	 // pagination
    	 $start = 0;
    	 $limit = 10;
    	 
    	 if(isset($_GET['numRecords'])){
    	     $limit = $_GET['numRecords'];
    	 }
    	 $output['numRecords'] = $limit;
    	 $currentPageNo = 1;
    	 if (isset($_GET['page']) && $_GET['page'] > 1) {
    		 $start = ($_GET['page'] * $limit) - $limit;
    	 }
    	 if (isset($_GET['page'])) {
    		 $currentPageNo = $_GET['page'];
    	 }
    	 $query = DB::table('forum_article')->get();
    	 $num_records = count($query);
    	 $numPages = (int) ($num_records / $limit);
    	 $reminder = ($num_records % $limit);
    	 if ($reminder > 0) {
    		 $numPages = $numPages + 1;
    	 }
    	 $output['numPages'] = $numPages;
    	 $output['start'] = $start;
    	 $output['currentPageNo'] = $currentPageNo;
    	 if (isset($_GET['page'])) {
    		 if ($_GET['page'] > $numPages) {
    			 header("location: " . $output['currentPage'] . "?page=" . $numPages);
    			 exit;
    		 } else if ($_GET['page'] < 1) {
    			 header("location: " . $output['currentPage'] . "?page=1");
    			 exit;
    		 }
    	 }
    // 	 dd($currentPageNo,$numPages);
    	 if ($currentPageNo == 1) {
    	     
    	     if($currentPageNo == $numPages){  
    	       //  dd('11111');
    	         $output['firstPageLink'] = 'disabled';
    		     $output['preLink'] = 'disabled';
    	         $output['nextLink'] = 'disabled';
    		     $output['lastPageLink'] = 'disabled';
    	     }else{
        		 $output['firstPageLink'] = 'disabled';
        		 $output['preLink'] = 'disabled';
        		 $output['nextLink'] = '';
        		 $output['lastPageLink'] = '';
    	     }
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
		
		
    
// 	$get_announ=DB::select("select id,ma_title,ma_description,ma_availability,ma_status,ma_created_on from members_announcements order by id desc limit $start,$limit");

    
    $get_query= DB::select("select* from forum_article order by art_id desc limit $start,$limit");
   
    $array = json_decode(json_encode($get_query), true);
    
    foreach($array as $key=>$value){
        if($value['created_user_type']=='2'){
           $mem_name= DB::table('members')->where('id','=',$value['art_created_by'])->select('fname','uname','email')->get();
            // print_r($mem_name);
            foreach ($mem_name as $key1=>$value1){
                 $array[$key]['fname']=$value1->fname;
                 $array[$key]['uname']=$value1->uname;
                 $array[$key]['email']=$value1->email;

    
            }
            
       }
        if($value['created_user_type']=='3'){
           $mem_name= DB::table('clients')->where('id','=',$value['art_created_by'])->select('name','uname','email')->get();
            // print_r($mem_name);
            foreach ($mem_name as $key1=>$value1){
                 $array[$key]['fname']=$value1->name;
                 $array[$key]['uname']=$value1->uname;
                $array[$key]['email']=$value1->email;

    
            }
            
       }
       
       $xx=$value['art_id'];
         
          $get_com=DB::select("select* from forum_article_comments where art_id=$xx AND delete_status=0 ");
       $array[$key]['comment_count']=count($get_com);

       
    }

   
    
    $output['get_article']=$array;
     
     return view('admin/list_forum',$output);
     
 }
 
 public function approve_forum(Request $request){
        $id = $request->id;

	    $query = DB::table('forum_article')->where('art_id', $id)->update(array('art_status' => 1));
	    
	    if($query){
	        return response()->json('success');
	    }
     
 }
 
 public function forum_disable(Request $request){
       $id = $request->id;
	    $query = DB::table('forum_article')->where('art_id', $id)->update(array('art_status' => 0));
	    
	    if($query){
	        return response()->json('success');
	    }
     
 }
 
 
 public function delete_forum(Request $request){
     
        $id = $request->id;
  
	   // $query = DB::table('forum_article')->delete($id);
	   $query =  DB::table('forum_article')->where('art_id', $id)->delete();
	    
	    if($query){
	        return response()->json('success');
	    }
     
 }
 
 public function edit_forum(Request $request , $id){
     $output=array();
    $admin_name = Auth::user()->name;
    $user_role = Auth::user()->user_role;
    $output['pageTitle'] = 'Edit Forum';
    $output['user_role'] = $user_role;
    
    $forum_det=DB::table('forum_article')->where('art_id','=',$id)->get();
    
    $array = json_decode(json_encode($forum_det), true);
    
    foreach($array as $key=>$value){
        if($value['created_user_type']=='2'){
           $mem_name= DB::table('members')->where('id','=',$value['art_created_by'])->select('fname','uname','email')->get();
            // print_r($mem_name);
            foreach ($mem_name as $key1=>$value1){
                 $array[$key]['fname']=$value1->fname;
                 $array[$key]['uname']=$value1->uname;
                 $array[$key]['email']=$value1->email;
    
            }
            
       }
        if($value['created_user_type']=='3'){
           $mem_name= DB::table('clients')->where('id','=',$value['art_created_by'])->select('name','uname','email')->get();
            // print_r($mem_name);
            foreach ($mem_name as $key1=>$value1){
                 $array[$key]['fname']=$value1->name;
                 $array[$key]['uname']=$value1->uname;
                 $array[$key]['email']=$value1->email;
    
            }
            
       }

       
    }
    $arr=json_encode($array);
    
    $output['forum_det']=$arr;
    
     return view('admin.list_forum_edit',$output);
    
 }
 
 public function forum_edit(Request $request){
     
     $update_announ=DB::table('forum_article')->where('art_id','=',$request->post('forum_id'))->update([
            'art_title' => $request->post('edit_art_title'),
            'art_desc' => $request->post('edit_art_desc'),
            
         
            ]);
            return redirect('admin/forums');
     
 }
 
 public function view_forum(Request $request,$id){
     $output=array();
    $admin_name = Auth::user()->name;
    $user_role = Auth::user()->user_role;
    $output['pageTitle'] = 'Edit Forum';
    $output['user_role'] = $user_role;
    
    $fetch=DB::table('forum_article')->where('art_id','=',$id)->get();
    $array = json_decode(json_encode($fetch), true);
    
    foreach($array as $key=>$value){
        if($value['created_user_type']=='2'){
           $mem_name= DB::table('members')->where('id','=',$value['art_created_by'])->select('fname','uname')->get();
            // print_r($mem_name);
            foreach ($mem_name as $key1=>$value1){
                 $array[$key]['fname']=$value1->fname;
                 $array[$key]['uname']=$value1->uname;
    
            }
            
       }
        if($value['created_user_type']=='3'){
           $mem_name= DB::table('clients')->where('id','=',$value['art_created_by'])->select('name','uname')->get();
            // print_r($mem_name);
            foreach ($mem_name as $key1=>$value1){
                 $array[$key]['fname']=$value1->name;
                 $array[$key]['uname']=$value1->uname;
    
            }
            
       }

    }
   
     $arr= json_encode($array);
     $output['fetch_art']=$arr;
     
     return view('admin.list_forum_view',$output);
 }
 
 
 public function list_comment(Request $request , $id){
     
    $output=array();
    $admin_name = Auth::user()->name;
    $user_role = Auth::user()->user_role;
    $output['pageTitle'] = 'COMMENTS';
    $output['user_role'] = $user_role;
    
    
     $output['currentPage'] = $id;
    	 // pagination
    	 $start = 0;
    	 $limit = 5;
    	 
    	 if(isset($_GET['numRecords'])){
    	     $limit = $_GET['numRecords'];
    	 }
    	 $output['numRecords'] = $limit;
    	 $currentPageNo = 1;
    	 if (isset($_GET['page']) && $_GET['page'] > 1) {
    		 $start = ($_GET['page'] * $limit) - $limit;
    	 }
    	 if (isset($_GET['page'])) {
    		 $currentPageNo = $_GET['page'];
    	 }
    	 
    	 $query = DB::select("select* from forum_article_comments where art_id=$id AND delete_status=0 ");
    	 $num_records = count($query);
    	 $numPages = (int) ($num_records / $limit);
    	 $reminder = ($num_records % $limit);
    	 if ($reminder > 0) {
    		 $numPages = $numPages + 1;
    	 }
    	 $output['numPages'] = $numPages;
    	 $output['start'] = $start;
    	 $output['currentPageNo'] = $currentPageNo;
    	 if (isset($_GET['page'])) {
    		 if ($_GET['page'] > $numPages) {
    			 header("location: " . $output['currentPage'] . "?page=" . $numPages);
    			 exit;
    		 } else if ($_GET['page'] < 1) {
    			 header("location: " . $output['currentPage'] . "?page=1");
    			 exit;
    		 }
    	 }
    // 	 dd($currentPageNo,$numPages);
    	 if ($currentPageNo == 1) {
    	     
    	     if($currentPageNo == $numPages){  
    	       //  dd('11111');
    	         $output['firstPageLink'] = 'disabled';
    		     $output['preLink'] = 'disabled';
    	         $output['nextLink'] = 'disabled';
    		     $output['lastPageLink'] = 'disabled';
    	     }else{
        		 $output['firstPageLink'] = 'disabled';
        		 $output['preLink'] = 'disabled';
        		 $output['nextLink'] = '';
        		 $output['lastPageLink'] = '';
    	     }
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
		
		
		
    
    
    
     $fetch_det= DB::select("select* from forum_article_comments where art_id=$id AND delete_status=0 order by id desc limit $start,$limit ");
     $array = json_decode(json_encode($fetch_det), true);
    
    foreach($array as $key=>$value){
        if($value['user_type']=='2'){
           $mem_name= DB::table('members')->where('id','=',$value['comment_posted_by'])->select('fname','uname','email')->get();
            // print_r($mem_name);
            foreach ($mem_name as $key1=>$value1){
                 $array[$key]['fname']=$value1->fname;
                 $array[$key]['uname']=$value1->uname;
                  $array[$key]['email']=$value1->email;
    
            }
            
       }
        if($value['user_type']=='3'){
           $mem_name= DB::table('clients')->where('id','=',$value['comment_posted_by'])->select('name','uname','email')->get();
            // print_r($mem_name);
            foreach ($mem_name as $key1=>$value1){
                 $array[$key]['fname']=$value1->name;
                 $array[$key]['uname']=$value1->uname;
                  $array[$key]['email']=$value1->email;
    
            }
            
       }

    }
     
     $arr=json_encode($array);
     
     $forum_det=DB::table('forum_article')->where('art_id','=',$id)->get();
    
    $array1 = json_decode(json_encode($forum_det), true);
    
    foreach($array1 as $key=>$value){
        if($value['created_user_type']=='2'){
           $mem_name= DB::table('members')->where('id','=',$value['art_created_by'])->select('fname','uname','email')->get();
            // print_r($mem_name);
            foreach ($mem_name as $key1=>$value1){
                 $array1[$key]['fname']=$value1->fname;
                 $array1[$key]['uname']=$value1->uname;
                 $array1[$key]['email']=$value1->email;
    
            }
            
       }
        if($value['created_user_type']=='3'){
           $mem_name= DB::table('clients')->where('id','=',$value['art_created_by'])->select('name','uname','email')->get();
            // print_r($mem_name);
            foreach ($mem_name as $key1=>$value1){
                 $array1[$key]['fname']=$value1->name;
                 $array1[$key]['uname']=$value1->uname;
                 $array1[$key]['email']=$value1->email;
    
            }
            
       }

       
    }
    
    $arr1=json_encode($array1);
    
     
     
     $output['comments']=$arr;
     $output['article_id']=$id;
     $output['article_data']=$arr1;
     
     
     
     
     return view('admin.list_forum_comment',$output);
     
 }
 
 
 public function list_single_comment(Request $request,$id){
      $output=array();
    $admin_name = Auth::user()->name;
    $user_role = Auth::user()->user_role;
    $output['pageTitle'] = 'ARTICLE COMMENT';
    $output['user_role'] = $user_role;
    
         $fetch_det= DB::select("select* from forum_article_comments where id=$id  ");
     $array = json_decode(json_encode($fetch_det), true);
    
    foreach($array as $key=>$value){
        if($value['user_type']=='2'){
           $mem_name= DB::table('members')->where('id','=',$value['comment_posted_by'])->select('fname','uname','email')->get();
            // print_r($mem_name);
            foreach ($mem_name as $key1=>$value1){
                 $array[$key]['fname']=$value1->fname;
                 $array[$key]['uname']=$value1->uname;
                  $array[$key]['email']=$value1->email;
    
            }
            
       }
        if($value['user_type']=='3'){
           $mem_name= DB::table('clients')->where('id','=',$value['comment_posted_by'])->select('name','uname','email')->get();
            // print_r($mem_name);
            foreach ($mem_name as $key1=>$value1){
                 $array[$key]['fname']=$value1->name;
                 $array[$key]['uname']=$value1->uname;
                  $array[$key]['email']=$value1->email;
    
            }
            
       }
       
       $art_created=$value['art_id'];
    }   
       
      $forum_det=DB::table('forum_article')->where('art_id','=',$art_created)->get();
    
    $array1 = json_decode(json_encode($forum_det), true);
    
    foreach($array1 as $key=>$value){
        if($value['created_user_type']=='2'){
           $mem_name= DB::table('members')->where('id','=',$value['art_created_by'])->select('fname','uname','email')->get();
            // print_r($mem_name);
            foreach ($mem_name as $key1=>$value1){
                 $array1[$key]['fname']=$value1->fname;
                 $array1[$key]['uname']=$value1->uname;
                 $array1[$key]['email']=$value1->email;
    
            }
            
       }
        if($value['created_user_type']=='3'){
           $mem_name= DB::table('clients')->where('id','=',$value['art_created_by'])->select('name','uname','email')->get();
            // print_r($mem_name);
            foreach ($mem_name as $key1=>$value1){
                 $array1[$key]['fname']=$value1->name;
                 $array1[$key]['uname']=$value1->uname;
                 $array1[$key]['email']=$value1->email;
    
            }
            
       }

       
    }

    
    
    $arr=json_encode($array);
    $arr1=json_encode($array1);
    
    $output['comment_details']=$arr;
    $output['article_details']=$arr1;
     
    //  pArr($output);
    //  die();
     
     
     return view('admin.list_single_comment',$output);
 }
 
 public function comment_approve(Request $request){
        $id = $request->id;
		if(!empty($request->usernameis)){
			$nameofuser = $request->usernameis;
		}
		if(!empty($request->useremailis)){
			$emailofuser = $request->useremailis;
		}
		$emailofuser = 'gssgtech@yopmail.com';
		//echo $emailofuser;die();
	    $query = DB::table('forum_article_comments')->where('id', $id)->update(array('comment_status' => 1));
	    
	    if($query){
			if(!empty($emailofuser)){
				$data = array('emailId' => $emailofuser, 'name' => $nameofuser, 'pwd'=> $request->input('password'));
				Mail::send('mails.forums.adminApproveArticleComment',['data' => $data], function ($message) use ($data) {
				  $message->to($data['emailId']);
				  $message->subject('Article comment is approved at Digiwaxx');
				  $message->from('business@digiwaxx.com','Digiwaxx');
			   });
			}
	        return response()->json('success');
	    }
     
 }
 
 public function comment_disapprove(Request $request){
      $id = $request->id;
	    $query = DB::table('forum_article_comments')->where('id', $id)->update(array('comment_status' => 0));
	    
	    if($query){
	        return response()->json('success');
	    }
     
     
 }
 
 public function comment_delete(Request $request){
         $id = $request->id;
	    $query = DB::table('forum_article_comments')->where('id', $id)->update(array('delete_status' => 1));
	    
	    if($query){
	        return response()->json('success');
	    }
     
 }
 
  public function comment_undelete(Request $request){
         $id = $request->id;
	    $query = DB::table('forum_article_comments')->where('id', $id)->update(array('delete_status' => 0));
	    
	    if($query){
	        return response()->json('success');
	    }
     
 }
   
   
   
   
   
   
   
   
//   -------------Announcement Functions Starts Here------------

     public function fetch_mem(Request $request){
         $id = $request->search;
         $search_query = DB::table('members')
                ->where('fname', 'like', '%'.$id.'%')->take(20)->select('id','fname','lname','uname')
                ->get();
                
        //  print_r($search_query);
        //  die();
         
       return response()->json($search_query);  
    }


    public function list_announcement(Request $request){
        $output=array();
		$admin_name = Auth::user()->name;
		$user_role = Auth::user()->user_role;
		$output['pageTitle'] = 'Announcement';
		$output['welcome_name'] = $admin_name;
		$output['user_role'] = $user_role;
		
		 $output['currentPage'] = 'announcement';
    	 // pagination
    	 $start = 0;
    	 $limit = 10;
    	 
    	 if(isset($_GET['numRecords'])){
    	     $limit = $_GET['numRecords'];
    	 }
    	 $output['numRecords'] = $limit;
    	 $currentPageNo = 1;
    	 if (isset($_GET['page']) && $_GET['page'] > 1) {
    		 $start = ($_GET['page'] * $limit) - $limit;
    	 }
    	 if (isset($_GET['page'])) {
    		 $currentPageNo = $_GET['page'];
    	 }
    	 $query = DB::table('news_details')->get();
    	 $num_records = count($query);
    	 $numPages = (int) ($num_records / $limit);
    	 $reminder = ($num_records % $limit);
    	 if ($reminder > 0) {
    		 $numPages = $numPages + 1;
    	 }
    	 $output['numPages'] = $numPages;
    	 $output['start'] = $start;
    	 $output['currentPageNo'] = $currentPageNo;
    	 if (isset($_GET['page'])) {
    		 if ($_GET['page'] > $numPages) {
    			 header("location: " . $output['currentPage'] . "?page=" . $numPages);
    			 exit;
    		 } else if ($_GET['page'] < 1) {
    			 header("location: " . $output['currentPage'] . "?page=1");
    			 exit;
    		 }
    	 }
    // 	 dd($currentPageNo,$numPages);
    	 if ($currentPageNo == 1) {
    	     
    	     if($currentPageNo == $numPages){  
    	       //  dd('11111');
    	         $output['firstPageLink'] = 'disabled';
    		     $output['preLink'] = 'disabled';
    	         $output['nextLink'] = 'disabled';
    		     $output['lastPageLink'] = 'disabled';
    	     }else{
        		 $output['firstPageLink'] = 'disabled';
        		 $output['preLink'] = 'disabled';
        		 $output['nextLink'] = '';
        		 $output['lastPageLink'] = '';
    	     }
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
		
		
		
		
		$get_announ=DB::select("select id,ma_title,ma_description,ma_availability,ma_status,ma_created_on from members_announcements order by id desc limit $start,$limit");
		
		
// 		$get_announ=DB::table('members_announcements')->select('id','ma_title','ma_description','ma_availability','ma_status','ma_created_on')->get();
		$output['get_announ']=$get_announ;
	    return view('admin/list_announcement',$output);
        
    }
    
  
     public function add_announcement_view(Request $request){
         $output=array();
         $admin_name = Auth::user()->name;
    	 $user_role = Auth::user()->user_role;
    	 $output['pageTitle'] = 'Add Announcement';
    	 $output['user_role'] = $user_role;
    	 
    	 return view('admin/list_announcement_add',$output);  
         
         
     }
     
     
     
     public function add_announcement(Request $request){
        $curTime = new \DateTime();
        $avail='';
        $created_at = $curTime->format("Y-m-d H:i:s");
        // print_r($request->post('hidden_selected_mem_id'));
        // die();
        
        if(!empty($request->post('hidden_selected_mem_id'))){
            foreach($request->post('hidden_selected_mem_id') as $key=>$value){
                $avail .=$value;
                $avail .=',';
                
            }
        }
         else{
                $avail .=$request->post('ann_avail');
            }
        
        
        $announ_insert= DB::table('members_announcements')->insert([
            'ma_title' => $request->post('ann_title'),
            'ma_description' => $request->post('ann_description'),
            'ma_availability' => $avail,
            'ma_created_on' => $created_at
            ]);
            
        return redirect('admin/announcement');    
         
     }
     
     
     
     public function approve_announcement(Request $request){
         $id = $request->id;
	    $query = DB::table('members_announcements')->where('id', $id)->update(array('ma_status' => 1));
	    
	    if($query){
	        return response()->json('success');
	    }
         
     }
    
    
     
     public function announcement_disable(Request $request){
         $id = $request->id;
	    $query = DB::table('members_announcements')->where('id', $id)->update(array('ma_status' => 0));
	    
	    if($query){
	        return response()->json('success');
	    }
         
         
     }
     
     
     
     public function delete_announcement(Request $request){
        $id = $request->id;
	    $query = DB::table('members_announcements')->delete($id);
	    
	    if($query){
	        return response()->json('success');
	    }
         
         
     }
     
     public function edit_announcement(Request $request,$id){
         $output=array();
		$admin_name = Auth::user()->name;
		$user_role = Auth::user()->user_role;
	    $output['pageTitle'] = 'Edit Announcement';
	    $output['user_role'] = $user_role;
	    $output['announcement_id'] = $id;
	    $all_check=0;
	    $range=0;
	    
	    $announ = DB::table('members_announcements')->select('ma_availability','ma_description','ma_title')->where('id', '=', $id)->get();
	   // print_r($announ);
	    foreach ($announ as $key=>$value){
	      if ($value->ma_availability!='All')
	      {
	          
	          $str= $value->ma_availability;
	          $id_arr = explode(",",$str);
	            array_pop($id_arr);
	            $all_check=1;
	         
	      }
	    }
	    
	    if($all_check==1){
	        foreach($id_arr as $id){
	            $search_mem['mem_details'][$range] = DB::table('members')
                ->where('id', '=', $id)->select('id','fname','lname','uname')
                ->get();
                $range++;
                
	        }
	    $output['mem_details']=$search_mem['mem_details'];
	    }
	    

	    $output['announ_edit'] = $announ;
	    return view('admin/list_announcement_edit',$output); 
         
     }
     
     public function announcement_edit(Request $request){
         
         $id=$request->post('announ_id');
         $avail='';
   
         if(!empty($request->post('hidden_selected_mem_id'))){
            foreach($request->post('hidden_selected_mem_id') as $key=>$value){
                $avail .=$value;
                $avail .=',';
                
            }
        }
         else{
                $avail .=$request->post('edit_ann_avail');
            }

         
         $update_announ=DB::table('members_announcements')->where('id','=',$id)->update([
            'ma_title' => $request->post('edit_announ_title'),
            'ma_description' => $request->post('edit_announ_description'),
            'ma_availability' => $avail,
         
            ]);
            return redirect('admin/announcement');
         
     }

	public function csvExportDjMember(Request $request){

	   $dtFormat = date('Ymd');
	   $fileName = 'dj-members-'.$dtFormat.'.csv';
	   $tasks = '';

	   $qMembers = DB::select("SELECT fname, lname, email, dob, stagename, address1, address2, city, state, country, zip  FROM members WHERE uname !='' AND email !='' AND email IS NOT NULL AND active = 1 ORDER BY id DESC");
	   //echo '<pre>';print_r($qMembers);die();
 		
	        $headers = array(
	            "Content-type"        => "text/csv",
	            "Content-Disposition" => "attachment; filename=$fileName",
	            "Pragma"              => "no-cache",
	            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
	            "Expires"             => "0"
	        );
	       
	        $columns = array('Name', 'EMail', 'Birthdate', 'Stagename', 'Address', 'City', 'State', 'Country', 'Zip');
	        //echo '<pre>';print_r($qMembers);die();
	        $callback = function() use($qMembers, $columns) {
	            $file = fopen('php://output', 'w');
	            fputcsv($file, $columns);

	            foreach ($qMembers as $member) {
	            	 $memberContry = '';
	            	 $memberKontry = urldecode($member->country);

	            	 if ( !empty($memberKontry) && ctype_digit(strval($memberKontry)) ) {
	            	 	$memberKontry = $memberContry;
					    $getKontryQ =  DB::table('country')->select('country')->where('countryId', '=', $memberKontry)->get();
					    
					    foreach ($getKontryQ as $key=>$value){
						    	if(!empty($value->country)){
						    		$memberKontry	= $value->country;

						    		$memberContry = $memberKontry;
						     }
					    }	            	 	
	            	 }
	            	 $row = array();
	                $row['Name']  = urldecode($member->fname).' '.urldecode($member->lname);
	                $row['EMail']    = urldecode($member->email);
	                $row['Birthdate']  = urldecode($member->dob);
	                $row['Stagename']  = urldecode($member->stagename);
	                $row['Address'] = urldecode($member->address1).' '.urldecode($member->address2);
	                $row['City']  = urldecode($member->city);
	                $row['State']  = urldecode($member->state);
	                $row['Country'] = $memberContry;
	                $row['Zip'] = urldecode($member->zip);
	                //echo '<pre>';print_r($row);die();
	                fputcsv($file, array($row['Name'], $row['EMail'], $row['Birthdate'], $row['Stagename'], $row['Address'], $row['City'], $row['State'], $row['Country'], $row['Zip']));
	            }

	             fclose($file);
	        };

	        return response()->stream($callback, 200, $headers);
	}
     
     public function view_announcement (Request $request,$id){
        $output=array();
		$admin_name = Auth::user()->name;
		$user_role = Auth::user()->user_role;
	    $output['pageTitle'] = 'View Announcement';
	    $output['user_role'] = $user_role;
	    $all_check=0;
	    $range=0;
	    
	    
	    $view_announ =  DB::table('members_announcements')->select('ma_availability','ma_description','ma_title','ma_created_on')->where('id', '=', $id)->get();
	    
	    foreach ($view_announ as $key=>$value){
	      if ($value->ma_availability!='All')
	      {
	          
	          $str= $value->ma_availability;
	          $id_arr = explode(",",$str);
	            array_pop($id_arr);
	            $all_check=1;
	         
	      }
	    }
	    
	    if($all_check==1){
	        foreach($id_arr as $id){
	            $search_mem['mem_details'][$range] = DB::table('members')
                ->where('id', '=', $id)->select('id','fname','lname','uname')
                ->get();
                $range++;
                
	        }
	        
	       // print_r($search_mem);
	       // die();
	    $output['mem_details']=$search_mem['mem_details'];
	    }
	    
	    
	    $output['view_announ'] = $view_announ;
	    return view('admin/list_announcement_view',$output); 
         
         
     }
       
       /* ------NEWS Functions Starts Here-------- */
   
   	public function list_news(Request $request){
   	    

   	    
   	    
   	    
		$output=array();
		$admin_name = Auth::user()->name;
		$user_role = Auth::user()->user_role;
		
	     $output['currentPage'] = 'news';
    	 // pagination
    	 $start = 0;
    	 $limit = 10;
    	 
    	 if(isset($_GET['numRecords'])){
    	     $limit = $_GET['numRecords'];
    	 }
    
    	 $output['numRecords'] = $limit;
    	 $currentPageNo = 1;
    	 if (isset($_GET['page']) && $_GET['page'] > 1) {
    		 $start = ($_GET['page'] * $limit) - $limit;
    	 }
    	 if (isset($_GET['page'])) {
    		 $currentPageNo = $_GET['page'];
    	 }
    	 $query = DB::table('news_details')->get();
    	 $num_records = count($query);
    	 $numPages = (int) ($num_records / $limit);
    	 $reminder = ($num_records % $limit);
    	 if ($reminder > 0) {
    		 $numPages = $numPages + 1;
    	 }
    	 $output['numPages'] = $numPages;
    	 $output['start'] = $start;
    	 $output['currentPageNo'] = $currentPageNo;
    	 if (isset($_GET['page'])) {
    		 if ($_GET['page'] > $numPages) {
    			 header("location: " . $output['currentPage'] . "?page=" . $numPages);
    			 exit;
    		 } else if ($_GET['page'] < 1) {
    			 header("location: " . $output['currentPage'] . "?page=1");
    			 exit;
    		 }
    	 }
    // 	 dd($currentPageNo,$numPages);
    	 if ($currentPageNo == 1) {
    	     
    	     if($currentPageNo == $numPages){  
    	       //  dd('11111');
    	         $output['firstPageLink'] = 'disabled';
    		     $output['preLink'] = 'disabled';
    	         $output['nextLink'] = 'disabled';
    		     $output['lastPageLink'] = 'disabled';
    	     }else{
        		 $output['firstPageLink'] = 'disabled';
        		 $output['preLink'] = 'disabled';
        		 $output['nextLink'] = '';
        		 $output['lastPageLink'] = '';
    	     }
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
		
		
		
	    $get_news=DB::select(" select id,title,description,added,approved from news_details ORDER BY added desc limit $start, $limit");
       
        $output['get_news']=$get_news;
        $output['pageTitle'] = 'News';
		$output['welcome_name'] = $admin_name;
		$output['user_role'] = $user_role;
// 		pArr($output);
// 		die();
	    return view('admin/list_news',$output);
	}
	
	public function add_news_view(){
	    $output=array();
		$admin_name = Auth::user()->name;
		$user_role = Auth::user()->user_role;
	    $output['pageTitle'] = 'Add News';
	    $output['user_role'] = $user_role;
	    return view('admin/list_news_add',$output);  
	}
	
	public function edit_news(Request $request,$id){
	    $output=array();
		$admin_name = Auth::user()->name;
		$user_role = Auth::user()->user_role;
	    $output['pageTitle'] = 'Edit News';
	    $output['user_role'] = $user_role;
	    $output['news_id'] = $id;
	    
	    $news = DB::table('news_details')->select('title','description','Image','pCloudFileID')->where('id', '=', $id)->get();
	    $output['news_details'] = $news;
	    return view('admin/list_news_edit',$output);  
	}
	
	public function add_news(Request $request){
	    
	   // dd($request->all());
	    
	    $curTime = new \DateTime();
        $created_at = $curTime->format("Y-m-d H:i:s");
           	    
        $path = public_path('image_news');
    
        if(!File::isDirectory($path)){
    
            File::makeDirectory($path, 0777, true, true);
            
    
        }
	    
	    if(!empty($request->news_image)){
	    $imgName = time().'.'.$request->news_image->extension();
	    }
	    else{
	        $imgName = '';
	    }
	    if($imgName != ''){
	        
	    $destinationPath = public_path().'/image_news/' ;
            
        $request->validate([
            'news_image' => 'image|mimes:jpeg,png,jpg',
        ]);
        
        $request->news_image->move(public_path('image_news'),$imgName);
        
        ## @GS pCLOUD IMAGE UPLOAD AND MAPPING

    	$folder = 13186221368;  // PCLOUD_FOLDER_ID
    
    	$metadata = $this->uploadImage_to_pcloud($destinationPath, $imgName, $folder);
    
    	$pcloudFileId = $metadata->metadata->fileid;
    	$parentfolderid = $metadata->metadata->parentfolderid;
    	@unlink($destinationPath.$imgName);
    
    	## @GS pCLOUD IMAGE UPLOAD AND MANAGE MAPPING
        
	   $insert_relations = DB::table('news_details')->insert([
                    'Title'       => $request->post('news_title'),
                    'Description' => $request->post('description'),
                    'Image'       => $imgName,
                    'pCloudFileID'       => $pcloudFileId,
                    'pCloudParentFolderID'       => $parentfolderid,
                    'Added'       => $created_at
                   
                ]);
        
        }else{
	   $insert_relations = DB::table('news_details')->insert([
                    'Title'       => $request->post('news_title'),
                    'Description' => $request->post('description'),
                    'Added'       => $created_at
                   
                ]);
        }

        
        return redirect('admin/news');        
	  
	}
	
	public function view_news(Request $request,$id){
	    
	    $output=array();
		$admin_name = Auth::user()->name;
		$user_role = Auth::user()->user_role;
	    $output['pageTitle'] = 'View News';
	    $output['user_role'] = $user_role;
	    $output['news_id'] = $id;
	    $news = DB::table('news_details')->select('title','description','Image','pCloudFileID')->where('id', '=', $id)->get();
	    $output['news_details'] = $news;
	    return view('admin/list_news_view',$output); 
	    
	}
	
	public function news_edit(Request $request){
	    
	    $curTime = new \DateTime();
	    $edited_at = $curTime->format("Y-m-d H:i:s");
	     $path = public_path('image_news');
    
        if(!File::isDirectory($path)){
    
            File::makeDirectory($path, 0777, true, true);

        }
	    if(!empty($request->news_image)){
	    $imgName = time().'.'.$request->news_image->extension();
	    }
	    else{
	        $imgName = '';
	    }
	    $u_title = $request->news_title;
	    $u_desc = $request->description;
	    $n_id = $request->news_id;
	    $u_img = $imgName;
	    
	    $pcloud_image_id='';
        
        if($imgName != ''){
            
            $query=DB::table('news_details')->select('pCloudFileID')->where('id',$n_id)->get();
            
            $pcloud_image_id=(int)$query[0]->pCloudFileID;
            
            
            if(!empty($pcloud_image_id)){
             $delete_metadata = $this->delete_pcloud($pcloud_image_id);   
            }
                
    	    $destinationPath = public_path().'/image_news/' ;
                
            $request->validate([
                'news_image' => 'image|mimes:jpeg,png,jpg',
            ]);
            
            $request->news_image->move(public_path('image_news'),$imgName);
            
            ## @GS pCLOUD IMAGE UPLOAD AND MAPPING
    
        	$folder = 13186221368;  // PCLOUD_FOLDER_ID
        
        	$metadata = $this->uploadImage_to_pcloud($destinationPath, $imgName, $folder);
        
        	$pcloudFileId = $metadata->metadata->fileid;
        	$parentfolderid = $metadata->metadata->parentfolderid;
        	@unlink($destinationPath.$imgName);
        
        	## @GS pCLOUD IMAGE UPLOAD AND MANAGE MAPPING
            
    	    $insert_relations = DB::table('news_details')->where('id', $n_id)->update([
                'title'       => $u_title,
                'description' => $u_desc,
                'Image'       => $u_img,
                'pCloudFileID'       => $pcloudFileId,
                'pCloudParentFolderID'       => $parentfolderid,            
                'edited'       => $edited_at
               
            ]);
        
        }else{
            $insert_relations = DB::table('news_details')->where('id', $n_id)->update([
            'title'       => $u_title,
            'description' => $u_desc,
            'edited'       => $edited_at
           
        ]);
        }
	    return redirect('admin/news');   
	}
	
	public function delete_news(Request $request){
	    $id = $request->id;
	    $query = DB::table('news_details')->delete($id);
	    
	    if($query){
	        return response()->json('success');
	    }
	}
	
	public function approve_news(Request $request){
	    $id = $request->id;
	    $query = DB::table('news_details')->where('id', $id)->update(array('approved' => 1));
	    
	    if($query){
	        return response()->json('success');
	    }
	}
	
	public function news_disable(Request $request){
	    $id = $request->id;
	    $query = DB::table('news_details')->where('id', $id)->update(array('approved' => 0));
	    
	    if($query){
	        return response()->json('success');
	    }
	}
	
	/* ------NEWS Functions Ends Here-------- */
	
	
	
	//WEBSITE PAGES
	
	public function view_website_pages(){
	    $output=array();
		$admin_name = Auth::user()->name;
		$user_role = Auth::user()->user_role;
	    $output['pageTitle'] = 'Pages List';
	    $output['user_role'] = $user_role;
	    return view('admin/website_pages_view',$output); 
	}
	
	
	
	
	// ---------------------------------------------------------Package functionality starts here -------------------------------------------------------
    public function add_package(Request $request){
          $output=array();
             $admin_name = Auth::user()->name;
        	 $user_role = Auth::user()->user_role;
        	 $output['pageTitle'] = 'Add Announcement';
        	 $output['user_role'] = $user_role;
        	  return view('admin/add_package',$output);  
    }
    
    public function insert_package(Request $request){
       $query_id=DB::table('manage_packages')->insertGetId([
           
           'package_name' => $request->post('package_name'),
           'package_type' => $request->post('package_type'),
           'available_to' => $request->post('package_available'),
           'package_status'=>0,
           'package_price' =>$request->post('package_price')
           
           
           ]);
         
         foreach($request->post('features') as $key=>$value) { 
           
              $add_features=DB::table('manage_packages_features')->insert([
                    'package_id'=>$query_id,
                    'package_features'=>$value
                  ]);
         }  
         
         if($request->post('package_available')==1){
             return redirect('admin/member_packages'); ;
         }else{
            //  return view('admin/client_packages');
             return redirect('admin/member_packages');
         }
    }
    
    public function member_packages(Request $request){
        
        $output=array();
             $admin_name = Auth::user()->name;
        	 $user_role = Auth::user()->user_role;
        	 $output['pageTitle'] = 'Member Packages';
        	 $output['user_role'] = $user_role;
        	 
        	 $get_packages= DB::table("manage_packages")->where('available_to','=',1)->orderBy('package_price', 'DESC')->get();
        	 $output['get_packages']=$get_packages;
        	 
        return view('admin/member_packages',$output);
    }
    
    public function client_packages(Request $request){
            
            $output=array();
            $admin_name = Auth::user()->name;
        	$user_role = Auth::user()->user_role;
        	$output['pageTitle'] = 'Client Packages';
        	$output['user_role'] = $user_role;
        	 
        	$get_packages= DB::table("manage_packages")->where('available_to','=',2)->get();
        	$output['get_packages']=$get_packages;
        	 
        return view('admin/client_packages',$output);
    }
    
    public function edit_package_view($id){
         $output=array();
             $admin_name = Auth::user()->name;
        	 $user_role = Auth::user()->user_role;
        	 $output['pageTitle'] = 'Edit Package';
        	 $output['user_role'] = $user_role;
        	 
        	 $get_package_details=DB::select("select manage_packages.package_name,manage_packages.package_type,manage_packages.package_price,available_to,package_features as feature from manage_packages  where manage_packages.id=$id");
        // 	 $get_package_feature=DB::select("select package_features as feature from manage_packages where id=$id");
        
        	  
        	 $output['package_id']=$id;
        	 $output['package_details']=$get_package_details;
        	
        	 
        	 
        // 	 echo '<pre>';
        // 	 print_r($output);
        // 	 echo '</pre>';
        // 	 die();
        	 
        return view('admin/edit_package_view',$output);
    }
    
    public function update_package(Request $request){
        $id=$request->post('package_id');
        $feature=json_encode($request->post('features'));
        // dd($_POST);
        if(!empty($request->post('package_type'))){
            
            $update_package=DB::table('manage_packages')->where('id','=',$id)->update([
                'package_name' =>$request->post('package_name'),
                'package_type' =>$request->post('package_type'),
                'package_price' =>$request->post('package_price'),
                'package_features' =>$feature
                ]);
                
        }else{
            
            $update_package=DB::table('manage_packages')->where('id','=',$id)->update([
                'package_name' =>$request->post('package_name'),
                'package_price' =>$request->post('package_price'),
                'package_features' =>$feature
                ]);
                
        }    
            
            
        if($request->post('available_to')==1){
            return redirect('admin/member_packages'); 
         }else{
             return redirect('admin/client_packages');
         }
        
    }
    
    
    public function approve_package(Request $request){
        $id=$request->id;
        
         $query = DB::table('manage_packages')->where('id', $id)->update(array('package_status' => 1));
	    
	    if($query){
	        return response()->json('success');
	    }
        
        
    }
    
    public function package_disable(Request $request){
          $id=$request->id;
        
         $query = DB::table('manage_packages')->where('id', $id)->update(array('package_status' => 0));
	    
	    if($query){
	        return response()->json('success');
	    }
        
    }
    
    public function user_packages_details(Request $request){
        $output=array();
        $admin_name = Auth::user()->name;
    	$user_role = Auth::user()->user_role;
    	$output['pageTitle'] = 'Package Details';
    	$output['user_role'] = $user_role;
    	
    	$output['currentPage'] = 'user_packages_details';
    	
    	
    	 // pagination
    	 $start = 0;
    	 $limit = 10;
    	 
    	 if(isset($_GET['numRecords'])){
    	     $limit = $_GET['numRecords'];
    	 }
    	 $output['numRecords'] = $limit;
    	 $currentPageNo = 1;
    	 if (isset($_GET['page']) && $_GET['page'] > 1) {
    		 $start = ($_GET['page'] * $limit) - $limit;
    	 }
    	 if (isset($_GET['page'])) {
    		 $currentPageNo = $_GET['page'];
    	 }
    	$query = DB::table('package_user_details')->get();
    	 $num_records = count($query);
    	 $numPages = (int) ($num_records / $limit);
    	 $reminder = ($num_records % $limit);
    	 if ($reminder > 0) {
    		 $numPages = $numPages + 1;
    	 }
    	 $output['numPages'] = $numPages;
    	 $output['start'] = $start;
    	 $output['currentPageNo'] = $currentPageNo;
    	 if (isset($_GET['page'])) {
    		 if ($_GET['page'] > $numPages) {
    			 header("location: " . $output['currentPage'] . "?page=" . $numPages);
    			 exit;
    		 } else if ($_GET['page'] < 1) {
    			 header("location: " . $output['currentPage'] . "?page=1");
    			 exit;
    		 }
    	 }
    // 	 dd($currentPageNo,$numPages);
    	 if ($currentPageNo == 1) {
    	     
    	     if($currentPageNo == $numPages){  
    	       //  dd('11111');
    	         $output['firstPageLink'] = 'disabled';
    		     $output['preLink'] = 'disabled';
    	         $output['nextLink'] = 'disabled';
    		     $output['lastPageLink'] = 'disabled';
    	     }else{
        		 $output['firstPageLink'] = 'disabled';
        		 $output['preLink'] = 'disabled';
        		 $output['nextLink'] = '';
        		 $output['lastPageLink'] = '';
    	     }
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
    	 
    	// search functionality 
		$where = '';
    	if (isset($_GET['user']) && strlen($_GET['user']) > 0) {
    		$output['searchUser'] = $_GET['user'];
    	    $where .= " where user_name LIKE '%" . urlencode($_GET['user']) . "%'";
    	}else{
    	    $where = '';
    	}
		
		
    	
    	$query=DB::select("select * from package_user_details $where order by id desc limit $start,$limit");
    	$output['user_details'] = $query;
    // 	dd($query);
    	
    	foreach($output['user_details'] as  $v){
    	    
    	    $q1 = DB::table('manage_packages')->select('package_name','package_type')->where('id','=',$v->package_id)->get();
    	    $v->package_name = $q1[0]->package_name;
    	    $v->package_type = $q1[0]->package_type;
    	    
    	}
    // 	dd($output['user_details']);
    
        return view('admin/view_user_packages',$output);
    }
    
    public function view_single_user_package(Request $request){
        // dd('11');
        $output=array();
        
     
   
        
        $admin_name = Auth::user()->name;
    	$user_role = Auth::user()->user_role;
    	$output['pageTitle'] = 'Package Details';
    	$output['user_role'] = $user_role;
    	
    	
    		$class='';
			$result='';
			if(isset($_GET['status'])){
			    
			    $get_success=$_GET['status'];
			    if($get_success==1){
			        $class="alert alert-success";
			        $result="SUBSCRIPTION UPDATED SUCCESSFULLY";
			        
			    }
			}
			
			$output['class']=$class;
			$output['result']=$result;
    	
    	
    	$pac_id = $_GET['p_id'];
    	$query = DB::table('package_user_details')->where('id','=',$pac_id)->get();
    	
    	$pck_id=$query[0]->package_id;
    	
    	$output['details'] = $query;
    	if($output['details'][0]->user_type == 1){
    	    
    	    $q = DB::table('members')->where('id','=',$output['details'][0]->user_id)->get();
    	    $output['details'][0]->full_name = $q[0]->fname." ".$q[0]->lname;
    	    $output['details'][0]->email = $q[0]->email;
    	    $output['details'][0]->phone = $q[0]->phone;
    	    
    	    
    	    $query2=DB::table('manage_packages')->where('available_to',1)->where('package_status',1)->where('id','!=',$pck_id)->orderBy('package_price', 'ASC')->get();
    	    $output['sel_pck']=$query2;
    	    $output['user_type']=1;
    	     $output['user_id']=$output['details'][0]->user_id;
    
    	}else{
    	    
    	    $q = DB::table('clients')->where('id','=',$output['details'][0]->user_id)->get();
    	    $output['details'][0]->full_name = $q[0]->name;
    	    $output['details'][0]->email = $q[0]->email;
    	    $output['details'][0]->phone = $q[0]->phone;
    	    
    	     $output['sel_pck']='';
    	     $output['user_type']=2;
    	     $output['user_id']=$output['details'][0]->user_id;

    	}
    	
    	$package_query = DB::table('manage_packages')->where('id','=',$output['details'][0]->package_id)->get();
    	$output['details'][0]->package_name = $package_query[0]->package_name;
    	$output['details'][0]->package_type = $package_query[0]->package_type;
    // 	dd($output['details']);
    	return view('admin/view_single_user_package',$output);
    }
    
    public function update_package_user(Request $request){
     
  
     $admin_id= Session::get('admin_Id');
     $admin_details=DB::table('admins')->select('name','email')->where('id',$admin_id)->get();
     $admin_name=urldecode($admin_details[0]->name);
     $admin_email=urldecode($admin_details[0]->email);
  
  
  
        if($request->post('user_type')==1){
            
            
          
          
            $memID=$request->post('user_id');
              
             $my_details = $this->memberAllDB_model->getMemberInfo_fem($memID)['data'][0];
            $uname = $my_details->uname;
            
            
             $update_status = DB::table('package_user_details')->where('user_id', $memID)->where('user_type', 1)->update([

                'package_active' => 0

            ]);
            
            $package_id=$request->post('package_id');
            
            
             $query = DB::table('manage_packages')->where('id', '=', $package_id)->get();
             $package_amount = $query[0]->package_price;
            
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
                'payment_method' => '',
                'payment_amount' => '',
                'package_active' => 1


            ]);
            
            
           $result = FrontEndUser::addMember4($request, $memID);
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
                        $redirect=$value->id;
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
                    
                    
                    if (!empty($admin_email)) {
                        $data = array('emailId' => $admin_email, 'name' => $admin_name, 'title' => $title);
                        Mail::send('mails.package.admin_update_package', ['data' => $data], function ($message) use ($data) {
                            $message->to($data['emailId']);
                            $message->subject('Package Updated Successfully');
                            $message->from('business@digiwaxx.com', 'Digiwaxx');
                        });
                    }
                    // return response()->json('success');
                }
                
            }
            return redirect()->intended("admin/view_single_user_package?p_id=$redirect&status=1");
            
        }
       
        
    }
    
    // -------------------------------------Sneaker Functionality Starts here-----------------------------------------------
    
    public function add_sneaker(Request $request){
        
        
	    $output=array();
		$admin_name = Auth::user()->name;
		$user_role = Auth::user()->user_role;
	    $output['pageTitle'] = 'Add Sneaker';
	    $output['user_role'] = $user_role;
        return view('admin/add_sneaker',$output);
    }
    
    public function addsneaker(Request $request){
         
	    $curTime = new \DateTime();
        $created_at = $curTime->format("Y-m-d H:i:s");
        
         $path = public_path('image_sneaker');
    
        if(!File::isDirectory($path)){
    
            File::makeDirectory($path, 0777, true, true);

        }
	    
	    if(!empty($request->sneaker_img)){
	    $imgName = time().'.'.$request->sneaker_img->extension();
	    }
	    else{
	        $imgName = '';
	    }
	    if($imgName != ''){
            
        $request->validate([
            'sneaker_img' => 'image|mimes:jpeg,png,jpg',
        ]);
        
        $request->sneaker_img->move(public_path('image_sneaker'),$imgName);
        
        $destinationPath = public_path().'/image_sneaker/' ;
        
        ## @GS pCLOUD IMAGE UPLOAD AND MAPPING

    	$folder = 13186687906;  // PCLOUD_FOLDER_ID
    
    	$metadata = $this->uploadImage_to_pcloud($destinationPath, $imgName, $folder);
    
    	$pcloudFileId = $metadata->metadata->fileid;
    	$parentfolderid = $metadata->metadata->parentfolderid;
    	@unlink($destinationPath.$imgName);
    
    	## @GS pCLOUD IMAGE UPLOAD AND MANAGE MAPPING
      
	   $insert_relations = DB::table('product_sneaker_details')->insert([
                    'name'       => $request->post('sneaker_title'),
                    'description' => $request->post('description'),
                    'img_path'       => $imgName,
                    'pCloudFileID'       => $pcloudFileId,
                    'pCloudParentFolderID'       => $parentfolderid,                    
                    'created_on'       => $created_at,
                    'status'    =>$request->post('sneaker_status'),
                    'price'=> $request->post('sneaker_price')
                   
                ]);
        

        }else{
	   $insert_relations = DB::table('product_sneaker_details')->insert([
                    'name' => $request->post('sneaker_title'),
                    'description' => $request->post('description'),
                    'created_on'=> $created_at,
                    'status'=>$request->post('sneaker_status'),
                    'price'=>$request->post('sneaker_price')
                   
                ]);
        }

        
        return redirect('admin/sneaker'); 
    }
    
    public function sneaker(Request $request){
        $output=array();
		$admin_name = Auth::user()->name;
		$user_role = Auth::user()->user_role;
		
	     $output['currentPage'] = 'sneaker';
    	 // pagination
    	 $start = 0;
    	 $limit = 10;
    	 
    	$where = 'where ';

		$whereItems = array();

    	 
        if(isset($_GET['search']))

            {

                if(isset($_GET['product']) && strlen($_GET['product'])>0)
    
                {
    
                    $output['searchProduct'] = $_GET['product'];
    
                    $whereItems[] = "product_sneaker_details.name like '%". $_GET['product'] ."%'";
    
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
    	 
    	
    	 
    	 
    	 if(isset($_GET['numRecords'])){
    	     $limit = $_GET['numRecords'];
    	 }
    	 $output['numRecords'] = $limit;
    	 $currentPageNo = 1;
    	 if (isset($_GET['page']) && $_GET['page'] > 1) {
    		 $start = ($_GET['page'] * $limit) - $limit;
    	 }
    	 if (isset($_GET['page'])) {
    		 $currentPageNo = $_GET['page'];
    	 }
    // 	 $query = DB::table('product_sneaker_details')->where('name',)->get();
    	 $query= DB::select("select * from product_sneaker_details  $where ORDER BY  created_on DESC  limit $start, $limit ");
    	 $num_records = count($query);
    	 $numPages = (int) ($num_records / $limit);
    	 $reminder = ($num_records % $limit);
    	 if ($reminder > 0) {
    		 $numPages = $numPages + 1;
    	 }
    	 $output['numPages'] = $numPages;
    	 $output['start'] = $start;
    	 $output['currentPageNo'] = $currentPageNo;
    	 if (isset($_GET['page'])) {
    		 if ($_GET['page'] > $numPages) {
    			 header("location: " . $output['currentPage'] . "?page=" . $numPages);
    			 exit;
    		 } else if ($_GET['page'] < 1) {
    			 header("location: " . $output['currentPage'] . "?page=1");
    			 exit;
    		 }
    	 }
    // 	 dd($currentPageNo,$numPages);
    	 if ($currentPageNo == 1) {
    	     
    	     if($currentPageNo == $numPages){  
    	       //  dd('11111');
    	         $output['firstPageLink'] = 'disabled';
    		     $output['preLink'] = 'disabled';
    	         $output['nextLink'] = 'disabled';
    		     $output['lastPageLink'] = 'disabled';
    	     }else{
        		 $output['firstPageLink'] = 'disabled';
        		 $output['preLink'] = 'disabled';
        		 $output['nextLink'] = '';
        		 $output['lastPageLink'] = '';
    	     }
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
		
		
		
	    $get_news=DB::select(" select * from product_sneaker_details  $where ORDER BY  created_on DESC  limit $start, $limit ");
	  
        $output['get_sneaker']=$get_news;
        $output['pageTitle'] = 'Sneakers';
		$output['welcome_name'] = $admin_name;
		$output['user_role'] = $user_role;

	
       
// 	pArr($output);die();
		
	    return view('admin/sneaker_list_view',$output);
        
        
        
    }
    
    public function sneaker_approve(Request $request){
        $id = $request->id;

	    $query = DB::table('product_sneaker_details')->where('id', $id)->update(array('status' => 1));
	    
	    if($query){
	        return response()->json('success');
	    }
        
    }
    
    
     public function sneaker_disable(Request $request){
        $id = $request->id;

	    $query = DB::table('product_sneaker_details')->where('id', $id)->update(array('status' => 0));
	     
	    if($query){
	        return response()->json('success');
	    }
        
    }
    
    public function  sneaker_delete (Request $request){
        
         $id = $request->id;

	    $query = DB::table('product_sneaker_details')->where('id', $id)->delete();
	     
	    if($query){
	        return response()->json('success');
	    }
        
    }
    
    public function update_sneaker(Request $request,$id){
         $output=array();
         
         
		$admin_name = Auth::user()->name;
		$user_role = Auth::user()->user_role;
	    $output['pageTitle'] = 'Update Sneaker';
	    $output['user_role'] = $user_role;
	    
	    $result='';
	    $class='';
	    if(isset($_GET['status'])){
	        $status=$_GET['status'];
	        if($status==1){
	            $class="alert alert-success";
	            $result="Sneaker updated successfully";
	        }
	    }
	    $output['result']=$result;
	    $output['class']=$class;
	    
	    $query= DB::table('product_sneaker_details')->where('id', $id)->get();
	    $output['sneaker']=$query;
	    
        return view('admin/update_sneaker',$output);
    }
    
    public function sneaker_edit(Request $request){
        
	    $curTime = new \DateTime();
        $created_at = $curTime->format("Y-m-d H:i:s");
         $id=$request->post('sneaker_id');
         
         $path = public_path('image_sneaker');
         $pcloud_image_id='';
    
        if(!File::isDirectory($path)){
    
            File::makeDirectory($path, 0777, true, true);

        }
	    
	    if(!empty($request->sneaker_img)){
	    $imgName = time().'.'.$request->sneaker_img->extension();
	    }
	    else{
	        $imgName = '';
	    }
	    if($imgName != ''){
            
        $request->validate([
            'sneaker_img' => 'image|mimes:jpeg,png,jpg',
        ]);
        
        
        
        $query=DB::table('product_sneaker_details')->select('pCloudFileID')->where('id',$id)->get();
            
            $pcloud_image_id=(int)$query[0]->pCloudFileID;
            
            
            if(!empty($pcloud_image_id)){
             $delete_metadata = $this->delete_pcloud($pcloud_image_id);   
            }
        
        
        
        $request->sneaker_img->move(public_path('image_sneaker'),$imgName);
        
        
        
         $destinationPath = public_path().'/image_sneaker/' ;
        
        ## @GS pCLOUD IMAGE UPLOAD AND MAPPING

    	$folder = 13186687906;  // PCLOUD_FOLDER_ID
    
    	$metadata = $this->uploadImage_to_pcloud($destinationPath, $imgName, $folder);
    
    	$pcloudFileId = $metadata->metadata->fileid;
    	$parentfolderid = $metadata->metadata->parentfolderid;
    	@unlink($destinationPath.$imgName);
        
        
	   $insert_relations = DB::table('product_sneaker_details')->where('id',$id)->update([
                    'name'       => $request->post('sneaker_title'),
                    'description' => $request->post('description'),
                    'img_path'       => $imgName,
                    'pCloudFileID'       => $pcloudFileId,
                    'pCloudParentFolderID'       => $parentfolderid,  
                    'status'    =>$request->post('sneaker_status'),
                    'price'=> $request->post('sneaker_price')
                   
                ]);
        
       
        }else{
	   $insert_relations = DB::table('product_sneaker_details')->where('id',$id)->update([
                    'name' => $request->post('sneaker_title'),
                    'description' => $request->post('description'),
                    'status'=>$request->post('sneaker_status'),
                    'price'=>$request->post('sneaker_price')
                   
                ]);
        }
        
        return redirect()->intended("admin/update_sneaker/$id?status=1");
        
    }
    
    public function sneaker_view(Request $request,$id){
          $output=array();
         
         
		$admin_name = Auth::user()->name;
		$user_role = Auth::user()->user_role;
	    $output['pageTitle'] = 'Sneaker Preview';
	    $output['user_role'] = $user_role;

	    
	    $query= DB::table('product_sneaker_details')->where('id', $id)->get();
	    $output['sneaker']=$query;
	    
        return view('admin/sneaker_view',$output);
    }
    
    
    // ------------------------------------------Video functionality starts here------------------------------------------------------------
    
    public function add_video(Request $request){
                  $output=array();
         
         
		$admin_name = Auth::user()->name;
		$user_role = Auth::user()->user_role;
	    $output['pageTitle'] = 'Add Video';
	    $output['user_role'] = $user_role;

	    
        return view('admin/add_video',$output);
        
    }
    
    public function addvideo(Request $request){
        $curTime = new \DateTime();
        $created_at = $curTime->format("Y-m-d H:i:s");
	    
	    $insert_query=DB::table('digiwaxx_videos')->insert([
	        'title'=>$request->post('video_title'),
	        'video_url'=>$request->post('video_link'),
	        'status' =>$request->post('video_status'),
	        'created_at'=>$created_at
	        ]);
	        
	   if($insert_query){
	        return redirect('admin/add_video'); 
	   }
	   
    }
    
    public function videos(Request $request){
    	$output=array();
		$admin_name = Auth::user()->name;
		$user_role = Auth::user()->user_role;
		
	     $output['currentPage'] = 'videos';
    	 // pagination
    	 $start = 0;
    	 $limit = 10;
    	 
    	 if(isset($_GET['numRecords'])){
    	     $limit = $_GET['numRecords'];
    	 }
    
    	 $output['numRecords'] = $limit;
    	 $currentPageNo = 1;
    	 if (isset($_GET['page']) && $_GET['page'] > 1) {
    		 $start = ($_GET['page'] * $limit) - $limit;
    	 }
    	 if (isset($_GET['page'])) {
    		 $currentPageNo = $_GET['page'];
    	 }
    	 $query = DB::table('digiwaxx_videos')->get();
    	 $num_records = count($query);
    	 $numPages = (int) ($num_records / $limit);
    	 $reminder = ($num_records % $limit);
    	 if ($reminder > 0) {
    		 $numPages = $numPages + 1;
    	 }
    	 $output['numPages'] = $numPages;
    	 $output['start'] = $start;
    	 $output['currentPageNo'] = $currentPageNo;
    	 if (isset($_GET['page'])) {
    		 if ($_GET['page'] > $numPages) {
    			 header("location: " . $output['currentPage'] . "?page=" . $numPages);
    			 exit;
    		 } else if ($_GET['page'] < 1) {
    			 header("location: " . $output['currentPage'] . "?page=1");
    			 exit;
    		 }
    	 }
    // 	 dd($currentPageNo,$numPages);
    	 if ($currentPageNo == 1) {
    	     
    	     if($currentPageNo == $numPages){  
    	       //  dd('11111');
    	         $output['firstPageLink'] = 'disabled';
    		     $output['preLink'] = 'disabled';
    	         $output['nextLink'] = 'disabled';
    		     $output['lastPageLink'] = 'disabled';
    	     }else{
        		 $output['firstPageLink'] = 'disabled';
        		 $output['preLink'] = 'disabled';
        		 $output['nextLink'] = '';
        		 $output['lastPageLink'] = '';
    	     }
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
		
		
		
	    $get_videos=DB::select(" select* from digiwaxx_videos ORDER BY created_at desc limit $start, $limit");
       
        $output['get_videos']=$get_videos;
        $output['pageTitle'] = 'Videos';
		$output['welcome_name'] = $admin_name;
		$output['user_role'] = $user_role;
	
	
        return view('admin/list_videos',$output);
    }
    
     public function video_approve(Request $request){
        $id = $request->id;

	    $query = DB::table('digiwaxx_videos')->where('id', $id)->update(array('status' => 1));
	    
	    if($query){
	        return response()->json('success');
	    }
        
    }
    
    
     public function video_disable(Request $request){
        $id = $request->id;

	    $query = DB::table('digiwaxx_videos')->where('id', $id)->update(array('status' => 0));
	     
	    if($query){
	        return response()->json('success');
	    }
        
    }
    
    public function  video_delete (Request $request){
        
         $id = $request->id;

	    $query = DB::table('digiwaxx_videos')->where('id', $id)->delete();
	     
	    if($query){
	        return response()->json('success');
	    }
        
    }
    
    public function update_video(Request $request,$id){
         $output=array();
         
         
		$admin_name = Auth::user()->name;
		$user_role = Auth::user()->user_role;
	    $output['pageTitle'] = 'Update Video Details';
	    $output['user_role'] = $user_role;
	    
	    $result='';
	    $class='';
	    if(isset($_GET['status'])){
	        $status=$_GET['status'];
	        if($status==1){
	            $class="alert alert-success";
	            $result="Video Details updated successfully";
	        }
	    }
	    $output['result']=$result;
	    $output['class']=$class;
	    
	    $query= DB::table('digiwaxx_videos')->where('id', $id)->get();
	    $output['videos']=$query;
	    
        return view('admin/update_video',$output);
        
    }
    
    public function video_edit(Request $request){
         $id=$request->post('video_id');
	    
	 
	   $insert_relations = DB::table('digiwaxx_videos')->where('id',$id)->update([
                    'title' => $request->post('video_title'),
                    'video_url' => $request->post('video_link'),
                    'status'=>$request->post('video_status'),
                  
                ]);
        
        
        return redirect()->intended("admin/update_video/$id?status=1");

        
    }
    
    public function view_video(Request $request,$id){
        
         $output=array();
         
         
		$admin_name = Auth::user()->name;
		$user_role = Auth::user()->user_role;
	    $output['pageTitle'] = 'Video Preview';
	    $output['user_role'] = $user_role;

	    
	    $query= DB::table('digiwaxx_videos')->where('id', $id)->get();
	    $output['videos']=$query;
	    
        return view('admin/video_view',$output);
        
    }
    
    
    
            //   ----------------------------------------------------Pcloud image upload---------------------------------------------------
      function testPcloudImgUpload(Request $request){
        //$query= DB::select("");
      	$output = array();
      	// return view("admin.devpcloudupload", $output);
      if ($request->hasFile('fileToUpload')) {
		$fileChk = $request->file('fileToUpload');
		$fileChkName = $fileChk->getClientOriginalName();
		$fileChkpath = $fileChk->getRealPath();

		$folderName = 'GGSSTT';  
		$parentFolderId = 17391936585;
		$foldObj = new Folder();   
		$folderId = $foldObj->createFolderIfNotExists($folderName, $parentFolderId); 	
      	echo '<pre>';print_r($folderId);'Yes__INNN';die;
      }
      
      return view("admin.devpcloudupload", $output);
      	echo 'Yes__INNN';
        exit();
    	$destinationPath =  base_path('/assets/images/');
    	$fileName = md5(rand(1000,10000));
    	$fileNameToStore = 'One.jpg';
        $folder = 13157076166;  // PCLOUD_FOLDER_ID
    	$metadata = $this->uploadImage_to_pcloud($destinationPath, $fileNameToStore, $folder);
    	
    	$pcloudFileId = $metadata->metadata->fileid;
    	$parentfolderid = $metadata->metadata->parentfolderid;
    	@unlink($destinationPath.$fileNameToStore);
    	echo'<pre>';print_r($metadata);
     }
    
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
     
     function testPcloudFolderCreate()
     {
        //exit(); 
        $foldername = "folderName";
        $folderid = 13148570724;
        
        $this->pCloudCreateFolder($folderid, $foldername); 
     }
     
    //  function pCloudCreateFolder($folderid, $foldername)
    //  {	
    //     try {
    // 	$fpath = base_path() . '/vendor/pcloud/pcloud-php-sdk/lib/pCloud/autoload.php';
    //   	 require_once($fpath);
      	 
    //     $pCloudFolder = new pCloud\Folder();
        
    //     $listFolderRes = $pCloudFolder->getContent(13229391393);

    //     echo '<pre>';print_r($listFolderRes );die();
        
    //     $resExe = $pCloudFolder->create($foldername, $folderid);
    // 	echo '<pre>';print_r($resExe);die(); 
    //     } catch (Exception $e) {
            
    //     	echo $e->getMessage(); 
    //     }
    	
    //  }
     
     ## For LOGOS Mapping
     
     function localMediaToPcloudMapping(){
         exit();
        $query= DB::select("SELECT id, img FROM `logos` WHERE pCloudFileID_logo IS NULL");
        $totalRes = count($query);
        if($totalRes > 0){
            foreach($query as $val){
                $vidImg = $val->id;
            	$destinationPath =  base_path('/Logos/');
            	$fileNameToStore = $val->img;
                $folder = 13199825142;  // PCLOUD_FOLDER_ID
                
                $checkFilePath = $destinationPath.$fileNameToStore;
                if (file_exists($checkFilePath)){
                    //echo'<pre>';print_r($val);die();
                	$metadata = $this->uploadImage_to_pcloud($destinationPath, $fileNameToStore, $folder);
                	$pcloudFileId = $metadata->metadata->fileid;
                	$parentfolderid = $metadata->metadata->parentfolderid;
                	DB::select("UPDATE `logos` SET pCloudFileID_logo = '".$pcloudFileId."', pCloudParentFolderID_logo = '".$parentfolderid."' WHERE id = '".$vidImg."'");
                	//@unlink($destinationPath.$fileNameToStore);                    
                    
                }else{
                    continue;
                }
            }
        }
        
        echo 'Success! Local images migrated to pCloud!';
     }
     
     
     ## FOR MEMBER IMAGES MAPPING
     
     function member_localMediaToPcloudMapping(){
         exit();
         // $query= DB::select("SELECT clientId,image,imageId FROM `client_images` WHERE pCloudFileID_client_image IS NULL");
         $query= DB::select("SELECT memberId,image,imageId FROM `member_images` WHERE pCloudFileID_mem_image IS NULL");
         $totalRes=count($query);
         if($totalRes>0){
             foreach($query as $value){
                 $id=$value->imageId;
                 $mem_ID=$value->memberId;
                 $destinationPath= base_path("./member_images/".$mem_ID."/");
                 $fileNameToStore=$value->image;
                 
                 
	                   
                 
                 $checkFilePath=$destinationPath.$fileNameToStore;
                 
                  if(file_exists($checkFilePath)){
                      
                     
                      $folderid='16072524897';//pcloud folder ID.
                      $folder='';
                      $foldername = $mem_ID;
                      $foldername = (string)$foldername;
                       //echo $foldername;die();
                   
                      $folder_ex_query=DB::select("SELECT pCloudParentFolderID_mem_image from member_images where memberId =$mem_ID AND pCloudParentFolderID_mem_image IS NOT NULL LIMIT 1 ") ;
                      if(!empty($folder_ex_query)){
                          if(!empty($folder_ex_query[0]->pCloudParentFolderID_mem_image)){
                              $folder=$folder_ex_query[0]->pCloudParentFolderID_mem_image;
                              
                      
                          }else{
                              $folder=$this->pCloudCreateFolder($folderid, $foldername);
                          }
                      }else{
                         $folder=$this->pCloudCreateFolder($folderid, $foldername); 
                      }
                       
                      $metadata = $this->uploadImage_to_pcloud($destinationPath, $fileNameToStore, $folder);
                      
                      
                      $pcloudFileId = $metadata->metadata->fileid;
                      $parentfolderid = $metadata->metadata->parentfolderid;
                      DB::select("UPDATE `member_images` SET pCloudFileID_mem_image = '".$pcloudFileId."', pCloudParentFolderID_mem_image = '".$parentfolderid."' WHERE imageId = '".$id."'");
                      
                  }
                  else{
                      continue;
                  }
             }
         }
            
            echo 'success! Local images migrated to pCloud!++'; 
           
         
     }
     
     
     
          
     ## FOR PAGE IMAGE MAPPING
     
          
     function pageimage_localMediaToPcloudMapping(){
         exit();
        $query= DB::select("SELECT id, imgpage FROM `tracks` WHERE imgpage <> '' AND imgpage IS NOT NULL AND pCloudFileID IS NULL LIMIT 1000");
        $totalRes = count($query);
        if($totalRes > 0){
            foreach($query as $val){
                $vidImg = $val->id;
            	$destinationPath = base_path('ImagesUp/');
            	$fileNameToStore = $val->imgpage;
                $folder = 13187487324;  // PCLOUD_FOLDER_ID
                
                $checkFilePath = $destinationPath.$fileNameToStore;
                if (file_exists($checkFilePath)){
                    // echo "hello";
                    // die();
                    //echo'<pre>';print_r($val);die();
                	$metadata = $this->uploadImage_to_pcloud($destinationPath, $fileNameToStore, $folder);
                	$pcloudFileId = $metadata->metadata->fileid;
                	$parentfolderid = $metadata->metadata->parentfolderid;
                	DB::select("UPDATE `tracks` SET pCloudFileID = '".$pcloudFileId."', pCloudParentFolderID= '".$parentfolderid."' WHERE id = '".$vidImg."'");
                	//echo $vidImg;die();
                	//@unlink($destinationPath.$fileNameToStore);                    
                    
                }else{
                    continue;
                }
            }
        }
        
        echo 'Success! Local images migrated to pCloud!';
     }
     
     
     
     ## FOR COVER IMAGE MAPPING
     
          
     function coverimage_localMediaToPcloudMapping(){
        $query= DB::select("SELECT id, coverimage FROM `tracks` WHERE coverimage <> '' AND coverimage IS NOT NULL AND pCloudFileID_cover IS NULL LIMIT 1000");
        $totalRes = count($query);
        if($totalRes > 0){
            foreach($query as $val){
                $vidImg = $val->id;
            	$destinationPath = base_path('ImagesUp/');
            	$fileNameToStore = $val->coverimage;
                $folder = 13187487324;  // PCLOUD_FOLDER_ID
                
                $checkFilePath = $destinationPath.$fileNameToStore;
                if (file_exists($checkFilePath)){
                    // echo "hello";
                    // die();
                    //echo'<pre>';print_r($val);die();
                	$metadata = $this->uploadImage_to_pcloud($destinationPath, $fileNameToStore, $folder);
                	$pcloudFileId = $metadata->metadata->fileid;
                	$parentfolderid = $metadata->metadata->parentfolderid;
                	DB::select("UPDATE `tracks` SET pCloudFileID_cover = '".$pcloudFileId."', pCloudParentFolderID_cover= '".$parentfolderid."' WHERE id = '".$vidImg."'");
                	//echo $vidImg;die();
                	//@unlink($destinationPath.$fileNameToStore);                    
                    
                }else{
                    continue;
                }
            }
        }
        
        echo 'Success! Local images migrated to pCloud!';
     }
     
     
     
     
     ## will created pcloud folder dynamically.
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
     
     
     ## test function to delete images from p cloud
     
      function testdeletefile(){
          $test_fileid=40188629766;
          $metadata = $this->delete_pcloud($test_fileid);
          if(!empty($metadata)){
              echo "deleted";
          }
          
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
     
    function emptyCallBack(){
        echo 'Sorry! No Route defined!';
    }


}  

