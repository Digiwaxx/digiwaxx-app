<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Redirect;
use Hash;
use Session;
use App\Models\Frontend\FrontEndUser;
use Mail;
use DB;

class ClientRegisterController extends Controller
{
    public function register(Request $request)
	{
		
		$newPaidClient=null;
    	if(!$newPaidClient){
			ob_start();
		 //session_start();
		}
        error_reporting(0);
		
		$output = array();
		$output['continents'] = FrontEndUser::getContinents();
		$output['logo'] = FrontEndUser::getLogo();
		$output['countries'] = FrontEndUser::getCountries('');

		
		$logo_data = array(
			'logo_id' => 1,
			);

		$logo_details = DB::table('website_logo')
		->where($logo_data)
		->first();  
		
		$get_logo = $logo_details->logo;		
		$output['pageTitle'] = 'Client Registration';
		$output['logo'] = $get_logo;
		
		if(isset($_GET['package_id'])){
		    
		    $pck_id=$_GET['package_id'];
		    
		    $pack_id=DB::table('manage_packages')->where('id','=',$pck_id)->where('available_to','=',2)->get();
	
		    if(count($pack_id)>0){
		        Session::put('package_id', $_GET['package_id']);
		        
		    }
		    else{
		        return redirect('client_subscriptions'); 
		    }
		  
		}
		else{
		   	return redirect('client_subscriptions'); 
		}
		
		
		
		
		
		
		
		if ($request->input('addClient')) {
			//echo "<pre>"; print_r($request->input()); echo "</pre>"; die;
            $errorString = "&company=".$request->input('company')."&name=".$request->input('name')."&address1=".$request->input('address1')."&address2=".$request->input('address2')."&city=".$request->input('city')."&zip=".$request->input('zip')."&country=".$request->input('country')."&state=".$request->input('state');
			
			if($newPaidClient){
				$result = FrontEndUser::addNewClientInfo($request->input(), $newPaidClient,Session::get('package_id'));
			} else {
				$result = FrontEndUser::addClient1($request->input(),Session::get('package_id'));
				
				if($request->input('company')){
                    // echo "<pre>"; print_r($result); echo "</pre>"; die;
                }
				if($result > 0){
					Session::put('newClientId', $result);
					mkdir("../client_images/".$result);
				}
			}
			
			if ($request->input('company')) {
				Session::put('sess-client-company', $request->input('company'));
			}

			if ($request->input('name')) {
				Session::put('sess-client-name', $request->input('name'));
			}

			if ($request->input('address1')) {
			   Session::put('sess-client-address1', $request->input('address1'));
			}

			if ($request->input('address2')) {
				Session::put('sess-client-address2', $request->input('address2'));
			}

			if ($request->input('city')) {
				Session::put('sess-client-city', $request->input('city'));
			}

			if ($request->input('zip')) {
				Session::put('sess-client-zip', $request->input('zip'));
			}

            if ($result > 0) {
                return Redirect::to('/Client_registration_step2');
                exit;
            } else {
                return Redirect::to('/Client_registration_step1?error=1'.$errorString);
                exit;

            }

        }
    //   echo '<pre>';
    //   print_r($output);
    //   echo '</pre>';
		
		return view('clients.Client_registration_step1', $output);
	}
	
	 public function getCountries()
    {
        error_reporting(0);

        $where = "where continentId = '" . $_GET['continentId'] . "'";

        $countries = FrontEndUser::getCountries($where);	

        if (count($countries) > 0) {

            foreach ($countries as $country) {



                $arr[] = array('id' => $country->countryId, 'name' => $country->country);
            }
        } else {

            $arr[] = array('id' => '', 'name' => 'No Data found.');
        }



        echo json_encode($arr);
    }
	
	public function getStatesById()
    {

        error_reporting(0);
        $states = FrontEndUser::getStatesById($_GET['country']);
        if ($states['numRows'] > 0) {

            foreach ($states['data'] as $state) {
                $arr[] = array('id' => $state->name, 'state' => $state->name);
            }
        } else {

            $arr[] = array('id' => '', 'state' => 'No Data found.');
        }
        echo json_encode($arr);
        exit;
    }
	
	public function registerstep2(Request $request)
	{
		if(!isset($_SESSION['paymentDone'])){
		// header("location: ".base_url("Signup"));
		}
		
		$clientId = Session::get('newClientId');
		if(empty($clientId)){
			return redirect()->intended('Client_registration_step1');
		}
		
		if (empty(Session::get('package_id')) ){
           return redirect()->intended('Client_registration_step1');
        }
		
		$clientInfo = FrontEndUser::getClientInfo($clientId);
		
		$email = urldecode($clientInfo['data'][0]->email);
		$output = array();
		$output['formData']['email'] = '';
		if($email){
			$output['formData']['email'] = $email;
		}
		$logo_data = array(
			'logo_id' => 1,
			);

		$logo_details = DB::table('website_logo')
		->where($logo_data)
		->first();  
		
		$get_logo = $logo_details->logo;		
		$output['pageTitle'] = 'Client Registration';
		$output['logo'] = $get_logo;
		
		
		
		$package_id=Session::get('package_id');
		$package_details=DB::table("manage_packages")->where('id','=',$package_id)->get();
		$output['package_details']=$package_details;
		
		
		
		
		date_default_timezone_set('Africa/Lagos');
		if($request->input('addClient2'))
		 {
			 $errorString = "&package_id=".$request->input('package_id')."&email=".$request->input('email')."&phone=".$request->input('phone')."&mobile=".$request->input('mobile')."&website=".$request->input('website')."&username=".$request->input('username')."&password=".base64_encode($request->input('password'))."&howHeard=".$request->input('howHeard')."&facebook=".$request->input('facebook')."&twitter=".$request->input('twitter')."&instagram=".$request->input('instagram')."&linkedin=".$request->input('linkedin');
			 
			  if($email){
				 $result = FrontEndUser::addNewClientInfo2($request,Session::get('newClientId'));
			  }else {
				 $result = FrontEndUser::addClient2($request,Session::get('newClientId'));
			 }
			 
			 if ($request->input('email')) {
				Session::put('sess-client-email', $request->input('email'));
			}
			if ($request->input('phone')) {
				Session::put('sess-client-phone', $request->input('phone'));
			}
			if ($request->input('mobile')) {
				Session::put('sess-client-mobile', $request->input('mobile'));
			}
			if ($request->input('website')) {
				Session::put('sess-client-website', $request->input('website'));
			}
			if ($request->input('username')) {
				Session::put('sess-client-username', $request->input('username'));
			}
			if ($request->input('howHeard')) {
				Session::put('sess-client-howHeard', $request->input('howHeard'));
			}
			if ($request->input('facebook')) {
				Session::put('sess-client-facebook', $request->input('facebook'));
			}
			if ($request->input('twitter')) {
				Session::put('sess-client-twitter', $request->input('twitter'));
			}
			if ($request->input('instagram')) {
				Session::put('sess-client-instagram', $request->input('instagram'));
			}
			if ($request->input('linkedin')) {
				Session::put('sess-client-linkedin', $request->input('linkedin'));
			}
			 
			 if($result['numRows']>0)
			   {

				//$emailLink = url('Client_registration_step4?id='.$result['data'][0]->id);
				
				// $emailLink = "https://digiwaxx.com/Client_registration_step4?id=".$result['data'][0]->id;
				 /* $message = "Hi ".$result["data"][0]->ccontact.",<br />
				<p>Thank you for your client registration. <a href='".$emailLink."'>Click here</a> to confirm your account.
				Digiwaxx Media... Still Breakin' Boundaries!!!</p>"; */
				 //echo "<pre>"; print_r($message); echo "</pre>";
				//echo $message; die;
			  
				// email			
				/* $this->load->library('email');
				
				$this->email->from('info@digiwaxx.com', 'Digiwaxx');
				$this->email->to($request->input('email']);
				$this->email->set_mailtype("html");
				$this->email->subject('Thank You for Your Client Registration');
				$this->email->message($message);
				$this->email->send(); */

				// mail chimp integration
				//$this->load->library('MCAPI');
				//$retval = $this->mcapi->listSubscribe('bf21d55a8b',$request->input('email']);				
				return redirect()->intended('Client_registration_step3');   exit;
			   }
			   else  if($result['numRows']==-1)
			   {
				 return redirect()->intended('Client_registration_step2?emailExists=1'.$errorString);   exit;
			   }
			   else  
			   {
				return redirect()->intended('Client_registration_step2?error=1'.$errorString);   exit;
			   }
		 }		
		return view('clients.Client_registration_step2', $output);
	}
	public function registerstep3(Request $request)
	{
		$clientId = Session::get('newClientId');
		if(empty($clientId)){
			return redirect()->intended('Client_registration_step1');exit;
		}
		
		if (empty(Session::get('package_id')) ){
           return redirect()->intended('Client_registration_step1');
        }
        
       $package_id=Session::get('package_id');
		$package_details=DB::table("manage_packages")->where('id','=',$package_id)->get();
	
		
		$output = array();
		$logo_data = array(
			'logo_id' => 1,
			);

		$logo_details = DB::table('website_logo')
		->where($logo_data)
		->first();  
			$output['package_details']=$package_details;
		$get_logo = $logo_details->logo;		
		$output['pageTitle'] = 'Client Registration';
		$output['logo'] = $get_logo;
		
		
	
		
		
		 $start_date='';
		  $exp_date='';
    	  $payment_method='Stripe';
	     $payment_amount='';
	     
	     
	    foreach($package_details as $key=>$value){
		    $payment_amount=$value->package_price;
		    $package_type=$value->package_type;
		}
	     
	     
		 $curTime = new \DateTime();
         $created_at = $curTime->format("Y-m-d H:i:s");
         
          $clientInfo = FrontEndUser::getClientInfo($clientId);
          $email = urldecode($clientInfo['data'][0]->email);
          $output['email']=$email;
		
		if($request->input('addMember4') || !empty(Session::get('packagepaymentDone'))){
		    
		    $start_date= date('Y-m-d', strtotime("+1 day", strtotime($created_at)));
		    
		     if($package_type=='Monthly'){
		       
		        $exp_date= date('Y-m-d', strtotime("+30 day", strtotime($start_date)));
		      
		        
		     }
		      
		     if($package_type=='Half Yearly'){
		       
		        $exp_date= date('Y-m-d', strtotime("+180 day", strtotime($start_date)));
		        
		        
		     }
		       if($package_type=='Yearly'){
		     
		        $exp_date= date('Y-m-d', strtotime("+365 day", strtotime($start_date)));
		      
		      
		        
		     }

		    
		    
		    	     
		       	$update_query=DB::table('package_user_details')->where('user_id',$clientId)->update([
			        'package_id'=>$package_id,
                    'payment_status'=>1	,
                    'package_start_date'=>$start_date,
                    'package_expiry_date' =>$exp_date,
                    'payment_method'=>$payment_method,
                    'payment_amount'=>$payment_amount,
                    'package_active'=>1
			    ]);
			    
			      $clientInfo = FrontEndUser::getClientInfo($clientId);
			      
			      if ($clientInfo['numRows'] > 0) { 
		
                    		$email = urldecode($clientInfo['data'][0]->email);
                    		$name=  urldecode($clientInfo['data'][0]->name);
        			    
        			    
        			     
                        $nameofuser=$name;
        
                     
                        $emailofuser = $email;
                        
                         	    $query=DB::table("package_user_details")->where('user_id','=',$clientId)->get();
                	    
                	    
                	    if($query){
                	        foreach($query as $value){
                	           $id=$value->package_id;
                	           $method=$value->payment_method;
                	           $amount=$value->payment_amount;
                	            
                	        }
                	        $query1=DB::table('manage_packages')->where('id','=',$id)->get();
                	        foreach($query1 as $value){
                	            $title=$value->package_type;
                	        }
                	       
                	        $code = md5(time());
                	        $string = array('id'=>$clientId, 'code'=>$code,'type'=>'2');
                	        $encode_string=base64_encode(serialize($string));
                	        
                	        $update_token=DB::table('clients')->where('id','=',$clientId)->update(['veri_token'=>$code]);
                	        $my_ver_link= route("verify_mail",['mtoken'=>$encode_string]);
                	        
                			if(!empty($emailofuser)){
                				$data = array('emailId' => $emailofuser, 'name' => $nameofuser,'title' =>$title,'string'=>$encode_string,'veri'=>$my_ver_link);
                				Mail::send('mails.package.confirm_package',['data' => $data], function ($message) use ($data) {
                				  $message->to($data['emailId']);
                				  $message->subject('Registerd Successfully');
                				  $message->from('business@digiwaxx.com','Digiwaxx');
                			   });
                			}
                			
                			
                 	$admin_email='kawani@digiwaxx.com';
        			
        			if(!empty($admin_email)){
        				$data = array('emailId' => $admin_email, 'name' => $nameofuser,'title' =>$title,'email_user'=>$emailofuser);
        				Mail::send('mails.package.admin_confirm_package',['data' => $data], function ($message) use ($data) {
        				  $message->to($data['emailId']);
        				  $message->subject('Registerd Successfully');
        				  $message->from('business@digiwaxx.com','Digiwaxx');
        			   });
        			}
                			
                			
                			
                	       // return response()->json('success');
                	    }
                	        return redirect()->intended("Client_registration_step4");
                           exit;
		            	}else{
			            	 return redirect()->intended("Client_registration_step3?error=1");
                                exit;
		                	}
		   
		      }
		    
		
		
		
// 		Session::flush();
		return view('clients.Client_registration_step3', $output);
	}
	
	
	
	
		public function registerstep4(Request $request){ 
		if(empty(Session::get('newClientId'))){
			return redirect()->intended('Client_registration_step1');
		}
		
		if (empty(Session::get('package_id')) ){
           return redirect()->intended('Client_registration_step1');
        }
		
		$output = array();
		$logo_data = array(
			'logo_id' => 1,
			);

		$logo_details = DB::table('website_logo')
		->where($logo_data)
		->first();  
		
		$get_logo = $logo_details->logo;		
		$output['pageTitle'] = 'Client Registration';
		$output['logo'] = $get_logo;
		
		
		Session::flush();
		return view('clients.Client_registration_step4', $output);
	} 
	
	
	//display packages ---------------------------------------------------------
	    public function client_package_selection_registration(){
		
		$output = array();
		$packages = array();
		$logo_data = array(
			'logo_id' => 1,
			);

		$logo_details = DB::table('website_logo')
		->where($logo_data)
		->first();  
		
	
		
		$get_logo = $logo_details->logo;		
		$output['pageTitle'] = 'Client Package Registration';
		$output['logo'] = $get_logo;
		
		$query = DB::table('manage_packages')->select('id','package_name','package_type','package_price','package_features as features')->where('available_to','=',2)->orderBy("priority")->get();
        $output['packages'] = $query;
        
        // foreach($output['packages'] as $pack){
        //     $p_id = $pack->id;
        //     $query1 = DB::table('manage_packages_features')->select('package_features')->where('package_id','=',$p_id)->get(); 
        //     $arr = $query1;
        //     $pack->features = $arr;
        // }
//         echo '<pre>';
//         print_r($output['packages']);
// 		die;
        return view('clients.Client_registration_package_select', $output);
    }
}
