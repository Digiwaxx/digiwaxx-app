<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use App\Models\Frontend\FrontEndUser;
use Session;

class LoginController extends Controller
{
	protected $clientAllDB_model;
	protected $memberAllDB_model;

	public function __construct()
	{
		// if(empty(Session::get('memberId'))){
		// 	return redirect()->intended('login');
		// }

		$this->clientAllDB_model = new \App\Models\ClientAllDB;
		$this->memberAllDB_model = new \App\Models\MemberAllDB;

	}
	
    public function login(Request $request)
    {
		//pArr($result);die('YSYS');
		$output = array();
		
		$logo_data = array(
			'logo_id' => 1,
			);

		$logo_details = DB::table('website_logo')
		->where($logo_data)
		->first();  
		
// 		$get_logo = $logo_details->logo;
       $get_logo = $logo_details->pCloudFileID; //pCloudFileID

		$output['pageTitle'] = 'Login';
		$output['logo'] = $get_logo;
		
		if(isset($_GET['success'])){
		    $output['success']="Thanks! Your email account has been verified.";
		}
		
		if(Session::get('clientId')){
			return redirect()->intended('Client_dashboard');
		}else if(Session::get('tempClientId')){
			//return redirect()->intended('Client_resubmission_step1');
			return redirect()->intended('Client_dashboard');
		}else if(Session::get('memberId')){
            			$memID= Session::get('memberId');
				        $query=DB::table('package_user_details')->where('user_type',1)->where('user_id',$memID)->where('package_active',1)->get();
                        if(count($query)>0){
                            
 
                        $start_date=$query[0]->package_start_date;
                        $exp_date=$query[0]->package_expiry_date;
                        
                            
                        $current_date= date("Y-m-d");
                         if($query[0]->package_id==7){
                              return redirect()->intended('Member_dashboard_newest_tracks');
                        }
                        
                       else if($current_date >= $start_date && $current_date <= $exp_date ){
                           return redirect()->intended('Member_dashboard_newest_tracks');
                        }
                        else{
                          $query1=DB::table('package_user_details')->where('user_type',1)->where('user_id',$memID)->update([
                            'package_active' => 0
                              ]);
                              
                        $get_free=DB::table('manage_packages')->where('available_to',1)->where('package_price',0)->get();
                        $get_uname=DB::table('members')->where('id',$memID)->get();

                              
                          $query2=DB::table('package_user_details')->insert([
                              
                                                'package_id' => $get_free[0]->id,
                                                'user_id' => $memID,
                                                'user_name' => $get_uname[0]->uname,
                                                'user_type' => 1,
                                                'payment_status' => 1,
                                                'package_start_date' => $current_date,
                                                'package_expiry_date' => '',
                                                'payment_method' => '',
                                                'payment_amount' => '',
                                                'package_active' => 1
                              
                              ]); 
                              
                           return redirect()->intended('Member_dashboard_newest_tracks?status=1');  
                        }
             

                        }else{
                            
                            $current_date= date("Y-m-d");    
                                
                            $get_free=DB::table('manage_packages')->where('available_to',1)->where('package_price',0)->get();
                            $get_uname=DB::table('members')->where('id',$memID)->get();

                              
                          $query2=DB::table('package_user_details')->insert([
                              
                                                'package_id' => $get_free[0]->id,
                                                'user_id' => $memID,
                                                'user_name' => $get_uname[0]->uname,
                                                'user_type' => 1,
                                                'payment_status' => 1,
                                                'package_start_date' => $current_date,
                                                'package_expiry_date' => '',
                                                'payment_method' => '',
                                                'payment_amount' => '',
                                                'package_active' => 1
                              
                              ]); 
                             
                            return redirect()->intended('Member_dashboard_newest_tracks?status=2');    
                            
                        }
            
            
            
			return redirect()->intended('Member_dashboard_newest_tracks');
		}else if(Session::get('tempMemberId')){
			//return redirect()->intended('Member_resubmission_step2');
			return redirect()->intended('Member_dashboard_newest_tracks');
		}else{
			if (!empty($request->input('error')) && $request->input('error') == 1) {
            $output['alert_message'] = 'Invalid login credentials!';
            $output['alert_class'] = 'alert alert-danger';
			} else if (!empty($request->input('error')) && $request->input('error') == 2) {
				$output['alert_message'] = 'Email not confirmed yet!';
				$output['alert_class'] = 'alert alert-danger';
			} else if (!empty($request->input('error')) && $request->input('error') == 3) {
				$output['alert_message'] = 'Your account email is not verified!';
				$output['alert_class'] = 'alert alert-danger';
			} else if (!empty($request->input('error')) && $request->input('error') == 4) {
				$output['alert_message'] = 'Email not confirmed yet!';
				$output['alert_class'] = 'alert alert-danger';
			} else if (!empty($request->input('error')) && $request->input('error') == 5) {
				$output['alert_message'] = 'We did not process the request as the email already exists, please login!';
				$output['alert_class'] = 'alert alert-danger';
			}else if (!empty($request->input('reset')) && $request->input('reset') == 1) {
				$output['alert_message'] = 'Your password has been changed,please login!';
				$output['alert_class'] = 'alert alert-success';
			}
			return view('auth.login', $output);
		}
    }

    public function authenticate(Request $request)
    {
		// SECURITY FIX: Removed global variables, using local scope
		$username = trim($request->input('email'));
		$password = trim($request->input('password'));
		$membertype = $request->input('membertype');
		
	
		
		
		if (Session::get('clientId')) {
            return redirect()->intended("Client_dashboard");
            exit;
        } else if(Session::get('tempClientId')) {
            $tmpClientId = Session::get('tempClientId');
            Session::put('clientId', $tmpClientId);
            
            ## DISABLE CLIENT PROFILE RESUBMISSION PROCESS
            //return redirect()->intended("Client_resubmission_step1");
            return redirect()->intended("Client_dashboard");            
            exit;
        } else if (Session::get('memberId')) {
            return redirect()->intended("Member_dashboard_newest_tracks");
            exit;
        } else if (Session::get('tempMemberId')) {
            $tmpMemId = Session::get('tempMemberId');
            Session::put('memberId', $tmpMemId);
            
            ## DISABLE MEMBER PROFILE RESUBMISSION PROCESS
            //return redirect()->intended("Member_resubmission_step2");
            return redirect()->intended("Member_dashboard_newest_tracks");            
            exit;
        }
        
        
		if($membertype == 'client'){

			// SECURITY FIX: Find user first, then verify password with auto-upgrade from MD5 to bcrypt
			// Using parameterized whereRaw for case-insensitive username matching
			$users = DB::table('clients')
				->where(function($query) use ($username) {
					$query->whereRaw('LOWER(uname) = ?', [strtolower(trim($username))])
						  ->orWhere('email', trim($username));
				})
				->where('deleted', 0)
				->get()
				->toArray();

			// Verify password using migration helper (supports both MD5 and bcrypt)
			$passwordValid = false;
			if (!empty($users) && count($users) > 0) {
				$passwordValid = \App\Helpers\PasswordMigrationHelper::verifyAndUpgrade(
					trim($password),
					$users[0]->pword,
					'clients',
					$users[0]->id
				);
			}

			if(!empty($users) && count($users)>0 && $passwordValid){
								
				$result['type'] = 1;
				$result['numRows'] = count($users);
				$result['data'] = $users;
				$upData = array(
					'userLocation' => $_SERVER['REMOTE_ADDR'],
					'lastlogon' => date('Y-m-d H:i:s'),
					
				);
				
				$resQry = DB::table('clients')
				->where('id', $users[0]->id)  // find your user by their email
				->limit(1)  // optional - to ensure only one record is updated.
				->update($upData);  // update the record in the DB.
				
				//echo'<pre>';print_r($users[0]->uname);die('---YSYSYS');
				//Session::put('loggedin_user', $users[0]->uname);				
				/* return redirect()->intended('home');
				exit; */
				
			}else{
				return redirect('login?type='.$membertype)->with('error', 'Oppes! You have entered invalid credentials');
				exit;
			}		
		}else if($membertype == 'member'){

			// SECURITY FIX: Find user first, then verify password with auto-upgrade from MD5 to bcrypt
			// Using parameterized whereRaw for case-insensitive username matching
			$users = DB::table('members')
				->where(function($query) use ($username) {
					$query->whereRaw('LOWER(uname) = ?', [strtolower(trim($username))])
						  ->orWhere('email', trim($username));
				})
				->where('deleted', 0)
				->get()
				->toArray();

			// Verify password using migration helper (supports both MD5 and bcrypt)
			$passwordValid = false;
			if (!empty($users) && count($users) > 0) {
				$passwordValid = \App\Helpers\PasswordMigrationHelper::verifyAndUpgrade(
					trim($password),
					$users[0]->pword,
					'members',
					$users[0]->id
				);
			}

			if(!empty($users) && count($users)>0 && $passwordValid){
				$result['type'] = 2;
				$result['numRows'] = count($users);
				$result['data'] = $users;
				$upData = array(
					'userLocation' => $_SERVER['REMOTE_ADDR'],
					'lastlogon' => date('Y-m-d H:i:s'),
					
				);
				
				$resQry = DB::table('members')
				->where('id', $users[0]->id)  // find your user by their email
				->limit(1)  // optional - to ensure only one record is updated.
				->update($upData);  // update the record in the DB.
				
				//echo'<pre>';print_r($users[0]->uname);die('---YSYSYS');
				//Session::put('loggedin_user', $users[0]->uname);				
				/* return redirect()->intended('home');
				exit; */
			}else{
				return redirect('login?type='.$membertype)->with('error', 'Oppes! You have entered invalid credentials');
				exit;
			}
		}
// 		pArr($result);die('YSYS');
		if ($result['numRows'] == 1) {
			if ($result['type'] == 1) {
				
				if ($result['data'][0]->active == 0) {
					$page = "login?error=3";					
				}else{
					Session::put('clientPackage', 0);
					$subscriptionInfo = $this->clientAllDB_model->getSubscriptionStatus($result['data'][0]->id);
					if ($subscriptionInfo['numRows'] > 0) {
						Session::put('clientPackage', $subscriptionInfo['data'][0]->packageId);
					}
					
					Session::put('clientName', urldecode($result['data'][0]->name) );
                    Session::put('clientImage', !empty($result['image']) ? $result['image'] : '');
					
					if ($result['data'][0]->resubmission == 0) {
				// 		$page = "Client_resubmission_step1";
				        $page = "Client_dashboard";
						
						## DISABLE CLIENT PROFILE RESUBMISSION PROCESS
						
						//Session::put('tempClientId', $result['data'][0]->id); 
						Session::put('clientId', $result['data'][0]->id);						
					} else {
						$page = "Client_dashboard";
						Session::put('clientId', $result['data'][0]->id);
						Session::put('clientName', $result['data'][0]->name);
						// SECURITY FIX: Use query builder to prevent SQL injection
						$query1 = DB::table('client_images')
							->select('image', 'pCloudFileID_client_image')
							->where('clientId', $result['data'][0]->id)
							->orderBy('imageId', 'desc')
							->limit(1)
							->get()
							->toArray();
                         $numRows1 = count($query1);
                         $data1  = $query1;
                         if ($numRows1 > 0) {
                             if(!empty($data1[0]->pCloudFileID_client_image )){
                                 $result['image'] = $data1[0]->pCloudFileID_client_image;
                             }
                             else if(!empty($data1[0]->image)){
                                 $result['image'] = $data1[0]->image;
                             }
                             else{
                                 $result['image']='';
                             }
                             
                         } else {
                             $result['image'] = '';
                         }
                        //  echo $result['image'];
                        //  die();
                         Session::put('clientImage', $result['image']);
                         
                         
					}
				}
				
			}else if ($result['type'] == 2) {
				
				if ($result['data'][0]->active == 0) {
					$page = "login?error=3";
				} else {
					// subscription status:
					 $subscription = $this->memberAllDB_model->getMemberSubscription($result['data'][0]->id);
					 //pArr($subscription);die();
					if ($subscription['numRows'] > 0) {
						Session::put('memberPackage', $subscription['data'][0]->package_Id);
					} else {
						Session::put('memberPackage', 0);
					}
					if ($result['data'][0]->resubmission == 0) {
						$addedDate = explode(' ', $result['data'][0]->added);
						$addedYear = $addedDate[0];
						$addedMonth = $addedDate[1];
				// 		$page = "Member_resubmission_step2";
				        $page = "Member_dashboard_newest_tracks";
						if ($addedYear < 2017) {
				// 			$page = "Member_resubmission_step2";
			            	 $page = "Member_dashboard_newest_tracks";
						} else if ($addedYear == 2017) {
							$monthsArray = array(0 => '01', 1 => '02', 2 => '03', 3 => '04', 4 => '05', 5 => '06');

							if (in_array($addedMonth, $monthsArray)) {
								// $page = "Member_resubmission_step2";
								 $page = "Member_dashboard_newest_tracks";
							} else {
								// $page = "Member_resubmission_step3";
								 $page = "Member_dashboard_newest_tracks";
							}
						} else {
				// 			$page = "Member_resubmission_step3";
				            $page = "Member_dashboard_newest_tracks";
						}

						## DISABLE Member PROFILE RESUBMISSION PROCESS
						
						//Session::put('tempMemberId', $result['data'][0]->id);
						Session::put('memberId', $result['data'][0]->id);
						Session::put('memberName', urldecode($result['data'][0]->fname));
						//Session::put('memberImage', $result['image']);
					} else {
						$page = "Member_dashboard_newest_tracks";
						Session::put('memberId', $result['data'][0]->id);
						Session::put('memberName', urldecode($result['data'][0]->fname));
						// SECURITY FIX: Use query builder to prevent SQL injection
						$query1 = DB::table('member_images')
							->select('image', 'pCloudFileID_mem_image')
							->where('memberId', $result['data'][0]->id)
							->orderBy('imageId', 'desc')
							->limit(1)
							->get()
							->toArray();
                         $numRows1 = count($query1);
                         $data1  = $query1;
                         if ($numRows1 > 0) {
                             if(!empty($data1[0]->pCloudFileID_mem_image )){
                                 $result['image'] = $data1[0]->pCloudFileID_mem_image;
                             }else{
                                 $result['image'] = $data1[0]->image;
                             }
                             
                         } else {
                             $result['image'] = '';
                         }
                         
						Session::put('memberImage', $result['image']);
						
						$memID= $result['data'][0]->id;
				        $query=DB::table('package_user_details')->where('user_type',1)->where('user_id',$memID)->where('package_active',1)->get();
                        if(count($query)>0){
                            
 
                        $start_date=$query[0]->package_start_date;
                        $exp_date=$query[0]->package_expiry_date;
                        
                            
                        $current_date= date("Y-m-d");
                        if($query[0]->package_id==7){
                             $page = "Member_dashboard_newest_tracks";
                            
                        }
                        
                       else if($current_date >= $start_date && $current_date <= $exp_date ){
                           $page = "Member_dashboard_newest_tracks";
                        }
                        else{
                          $query1=DB::table('package_user_details')->where('user_type',1)->where('user_id',$memID)->update([
                            'package_active' => 0
                              ]);
                              
                        $get_free=DB::table('manage_packages')->where('available_to',1)->where('package_price',0)->get();
                        $get_uname=DB::table('members')->where('id',$memID)->get();

                              
                          $query2=DB::table('package_user_details')->insert([
                              
                                                'package_id' => $get_free[0]->id,
                                                'user_id' => $memID,
                                                'user_name' => $get_uname[0]->uname,
                                                'user_type' => 1,
                                                'payment_status' => 1,
                                                'package_start_date' => $current_date,
                                                'package_expiry_date' => '',
                                                'payment_method' => '',
                                                'payment_amount' => '',
                                                'package_active' => 1
                              
                              ]); 
                              
                            $page = "Member_dashboard_newest_tracks?status=1";  
                        }
             

                        }else{
                            
                        $current_date= date("Y-m-d");    
                            
                        $get_free=DB::table('manage_packages')->where('available_to',1)->where('package_price',0)->get();
                        $get_uname=DB::table('members')->where('id',$memID)->get();

                              
                          $query2=DB::table('package_user_details')->insert([
                              
                                                'package_id' => $get_free[0]->id,
                                                'user_id' => $memID,
                                                'user_name' => $get_uname[0]->uname,
                                                'user_type' => 1,
                                                'payment_status' => 1,
                                                'package_start_date' => $current_date,
                                                'package_expiry_date' => '',
                                                'payment_method' => '',
                                                'payment_amount' => '',
                                                'package_active' => 1
                              
                              ]); 
                             
                            $page = "Member_dashboard_newest_tracks?status=2";    
                            
                        }
					}
				}
				
			}else{
				return redirect('login?error=9')->with('error', 'Oppes! You have entered invalid credentials');
				exit;
			}
			return redirect($page);
			exit;
		} else {
			return redirect('login?type='.$membertype)->with('error', 'Oppes! You have entered invalid credentials');
			exit;
		}
		
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            return redirect()->intended('home');
        }

        
    }
	
	public function MemberResubmissionStep2(){
		$output = array();
		
		
		if (empty(Session::get('tempMemberId'))) {
            return redirect()->intended("login");
            exit;
        }
		
		$logo_data = array(
			'logo_id' => 1,
			);

		$logo_details = DB::table('website_logo')
		->where($logo_data)
		->first();  
		
		$get_logo = $logo_details->logo;	
		$output['logo'] = $get_logo;
		$memId = Session::get('tempMemberId');
		
		// fb logout link

/* 	  $this->load->library('facebook');
	  $logout_link = url('Logout');
	  if(isset($_SESSION['fb_access_token'])){

		 $logout_link = $this->facebook->logout_url();	

	   }
	  $output['logout_link'] = $logout_link; */
	
	  $output['pageTitle'] = 'Member Resubmission Step 2';
	  
		$output['memberInfo'] = $this->memberAllDB_model->getMemberInfo($memId);
		$output['memberSocialInfo'] = $this->memberAllDB_model->getMemberSocialInfo($memId);
		$output['cotinents'] = FrontEndUser::getContinents();
        $output['countries'] = FrontEndUser::getCountries(''); 
		
		if(isset($_POST['addMember2'])){
		 

		  $result = FrontEndUser::reAddMember2($_POST,$memId); 

		  if($result>0){		    

		    return Redirect::to("Member_resubmission_step3");   exit;

		  }else{		    

		    return Redirect::to("Member_resubmission_step2?error=1");   exit;

		   }

		 }
		
		$output['subscriptionStatus'] = 0;
		$output['package'] = '';
		
		$subscriptionInfo = $this->memberAllDB_model->getMemberSubscriptionStatus($memId);
		
		if($subscriptionInfo['numRows']>0){

		  $output['subscriptionStatus'] = 1;

		  //$_SESSION['memberPackage'] = $subscriptionInfo['data'][0]->package_Id;
		  Session::put('memberPackage', $subscriptionInfo['data'][0]->package_Id);
		   

		  if($subscriptionInfo['data'][0]->package_Id==1){

		    $output['packageId'] = 1;

			$output['package'] = 'Silver Subscription';

			// $output['displayDashboard'] = 0;

		  }else if($subscriptionInfo['data'][0]->package_Id==2){

		    $output['packageId'] = 2;

		    $output['package'] = 'Gold Subscription';

			//$output['displayDashboard'] = 1;

		  }else if($subscriptionInfo['data'][0]->package_Id==3){

		    $output['packageId'] = 3;

		    $output['package'] = 'Purple Subscription';

			//$output['displayDashboard'] = 1;
		  }

		}
		//echo'<pre>';print_r($output); die('---');
		return view('members.MemberResubmissionStep2', $output);
	  
	}
	
	public function MemberResubmissionStep3(){
		$output = array();
		
		if (empty(Session::get('tempMemberId'))) {
            return redirect()->intended("login");
            exit;
        }
		
		$logo_data = array(
			'logo_id' => 1,
			);

		$logo_details = DB::table('website_logo')
		->where($logo_data)
		->first();  
		
		$get_logo = $logo_details->logo;	
		$output['logo'] = $get_logo;
		$memId = Session::get('tempMemberId');
		
		$output['pageTitle'] = 'Member Resubmission Step 3';
		
		if(isset($_POST['addMember'])){
		   $result = FrontEndUser::reAddMember3($_POST,$memId); 

		   if($result>0)
		   {  
		    return Redirect::to("Member_resubmission_step4");   exit;
		   }
		   else
		   {
		    return Redirect::to("Member_resubmission_step3?error=1");   exit;
		   }
		 }

		$output['memberInfo'] = $this->memberAllDB_model->getMemberInfo($memId); 	
		$output['production'] = $this->memberAllDB_model->getMemberProductionInfo($memId); 	
		$output['special'] = $this->memberAllDB_model->getMemberSpecialInfo($memId); 
		$output['promoter'] = $this->memberAllDB_model->getMemberPromoterInfo($memId); 
        $output['clothing'] = $this->memberAllDB_model->getMemberClothingInfo($memId); 	
		$output['management'] = $this->memberAllDB_model->getMemberManagementInfo($memId); 	
		$output['record'] = $this->memberAllDB_model->getMemberRecordInfo($memId); 	
		$output['media'] = $this->memberAllDB_model->getMemberMediaInfo($memId); 	
		$output['radio'] = $this->memberAllDB_model->getMemberRadioInfo($memId); 
		 
		$output['subscriptionStatus'] = 0;
		$output['package'] = '';
		
		$subscriptionInfo = $this->memberAllDB_model->getMemberSubscriptionStatus($memId);
		
		if($subscriptionInfo['numRows']>0){

		  $output['subscriptionStatus'] = 1;

		  //$_SESSION['memberPackage'] = $subscriptionInfo['data'][0]->package_Id;
		  Session::put('memberPackage', $subscriptionInfo['data'][0]->package_Id);
		   

		  if($subscriptionInfo['data'][0]->package_Id==1){

		    $output['packageId'] = 1;

			$output['package'] = 'Silver Subscription';

			// $output['displayDashboard'] = 0;

		  }else if($subscriptionInfo['data'][0]->package_Id==2){

		    $output['packageId'] = 2;

		    $output['package'] = 'Gold Subscription';

			//$output['displayDashboard'] = 1;

		  }else if($subscriptionInfo['data'][0]->package_Id==3){

		    $output['packageId'] = 3;

		    $output['package'] = 'Purple Subscription';

			//$output['displayDashboard'] = 1;
		  }

		}
		
		return view('members.MemberResubmissionStep3', $output);
	}
	
	public function MemberResubmissionStep4(){
		$output = array();
		
		if (empty(Session::get('tempMemberId'))) {
            return redirect()->intended("login");
            exit;
        }
		
		$logo_data = array(
			'logo_id' => 1,
			);

		$logo_details = DB::table('website_logo')
		->where($logo_data)
		->first();  
		
		$get_logo = $logo_details->logo;	
		$output['logo'] = $get_logo;
		$memId = Session::get('tempMemberId');
		
		$output['pageTitle'] = 'Member Resubmission Step 4';
		
		 if(isset($_POST['addMember4'])){		 

		   $result = FrontEndUser::reAddMember4($_POST,$memId); 

		   if($result['numRows']>0){		   

		 /*  $email = urldecode($result['data'][0]->email);		   

		   // email			

			$this->load->library('email');			

			$this->email->from('no-reply@digiwaxx.com', 'Digiwaxx');

			$this->email->to($email);

			$this->email->set_mailtype("html");

			$this->email->subject('Member signup at DIGIWAXX');			

			$message = 'Hi '.$result['data'][0]->fname.',<br />			

			<p><a href="http://projects.globalpointinc.com/demo/digiwaxx_development/Member_registration_step6?id='.$result['data'][0]->id.'">Click here</a> to confirm your account.</p>';

			$this->email->message($message);		

			$this->email->send();

		   */			

			Session::put('memberId',Session::get('tempMemberId'));

		    return Redirect::to("Member_dashboard_all_tracks");   exit;	     

		   }else{		    

		    return Redirect::to("Member_resubmission_step4?error=1");   exit;

		   }

		 }
		


		$output['memberInfo'] = $this->memberAllDB_model->getMemberInfo($memId); 	
		$output['production'] = $this->memberAllDB_model->getMemberProductionInfo($memId); 	
		$output['special'] = $this->memberAllDB_model->getMemberSpecialInfo($memId); 
		$output['promoter'] = $this->memberAllDB_model->getMemberPromoterInfo($memId); 
        $output['clothing'] = $this->memberAllDB_model->getMemberClothingInfo($memId); 	
		$output['management'] = $this->memberAllDB_model->getMemberManagementInfo($memId); 	
		$output['record'] = $this->memberAllDB_model->getMemberRecordInfo($memId); 	
		$output['media'] = $this->memberAllDB_model->getMemberMediaInfo($memId); 	
		$output['radio'] = $this->memberAllDB_model->getMemberRadioInfo($memId); 
		 
		$output['subscriptionStatus'] = 0;
		$output['package'] = '';
		
		$subscriptionInfo = $this->memberAllDB_model->getMemberSubscriptionStatus($memId);
		
		if($subscriptionInfo['numRows']>0){

		  $output['subscriptionStatus'] = 1;

		  //$_SESSION['memberPackage'] = $subscriptionInfo['data'][0]->package_Id;
		  Session::put('memberPackage', $subscriptionInfo['data'][0]->package_Id);
		   

		  if($subscriptionInfo['data'][0]->package_Id==1){

		    $output['packageId'] = 1;

			$output['package'] = 'Silver Subscription';

			// $output['displayDashboard'] = 0;

		  }else if($subscriptionInfo['data'][0]->package_Id==2){

		    $output['packageId'] = 2;

		    $output['package'] = 'Gold Subscription';

			//$output['displayDashboard'] = 1;

		  }else if($subscriptionInfo['data'][0]->package_Id==3){

		    $output['packageId'] = 3;

		    $output['package'] = 'Purple Subscription';

			//$output['displayDashboard'] = 1;
		  }

		}
		
		return view('members.MemberResubmissionStep4', $output);
	}
	
	public function ClientResubmissionStep1(){
		$output = array();
		
		if (empty(Session::get('tempClientId'))) {
            return redirect()->intended("login");
            exit;
        }
		
		$logo_data = array(
			'logo_id' => 1,
			);

		$logo_details = DB::table('website_logo')
		->where($logo_data)
		->first();  
		
		$get_logo = $logo_details->logo;	
		$output['logo'] = $get_logo;
		$clientId = Session::get('tempClientId');
		
		$output['pageTitle'] = 'Client Resubmission Step 1';
		
		return view('members.ClientResubmissionStep1', $output);
	}

    public function logout() {
      //Auth::logout();
	  Session::flush();
      return redirect('login');
    }

    // SECURITY: testWebLogin function removed - debug code should never be in production
}