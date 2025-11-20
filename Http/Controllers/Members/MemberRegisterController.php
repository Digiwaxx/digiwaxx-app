<?php

namespace App\Http\Controllers\Members;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Redirect;
use App\Models\Frontend\FrontEndUser;
use Hash;
use Session;
use Mail;
use DB;

class MemberRegisterController extends Controller
{
    public function register(Request $request)
	{
	   $output = array();
	   
	   	$logo_data = array(
			'logo_id' => 1,
			);

		$logo_details = DB::table('website_logo')
		->where($logo_data)
		->first();  
		
		$get_logo = $logo_details->logo;		
		$output['pageTitle'] = 'Member Registration';
		$output['logo'] = $get_logo;
		
		if(isset($_GET['package_id'])){
		    
		    $pck_id=$_GET['package_id'];
		    
		    $pack_id=DB::table('manage_packages')->where('id','=',$pck_id)->where('available_to','=',1)->get();
	
		    if(count($pack_id)>0){
		        Session::put('package_id', $_GET['package_id']);
		        
		    }
		    else{
		        return redirect('member_subscriptions'); 
		    }
		  
		}
		else{
		   	return redirect('member_subscriptions'); 
		}
        

		if($request->input('addMember'))
		{
			//echo '---TSTSTST';die('uiui');
			$errorString = "&package_id=".$request->input('package_id')."&firstName=".$request->input('firstName')."&lastName=".$request->input('lastName')."&phone=".$request->input('phone')."&dob=".$request->input('dob')."&email=".$request->input('email')."&username=".$request->input('username')."&password=".base64_encode($request->input('password'));
			
			/* START Mailchimp Integration Code 
		    $emailaddress = $request->input('email');
		    $list_id = '5b0f660d11';
        	$result = $this->mailchimp->post("lists/$list_id/members", [
                'email_address' => $emailaddress,
                'merge_fields' => ['FNAME'=> $_POST['firstName'], 'LNAME'=> $_POST['lastName']],
                'status'        => 'subscribed',
            ]); */
			/* END Mailchimp Integration Code */
			
			if ($request->input('firstName')) {
				Session::put('sess-member-firstName', $request->input('firstName'));
			}

			if ($request->input('lastName')) {
				Session::put('sess-member-lastName', $request->input('lastName'));
			}

			if ($request->input('phone')) {
			   Session::put('sess-member-phone', $request->input('phone'));
			}

			if ($request->input('email')) {
				Session::put('sess-member-email', $request->input('email'));
			}
			if ($request->input('dob')) {
				Session::put('sess-member-dob', $request->input('dob'));
			}
			if ($request->input('username')) {
				Session::put('sess-member-username', $request->input('username'));
			}
			//echo Session::get('newMemberId');die('---YSYSY');
			if(Session::get('newMemberId')){
				$result = FrontEndUser::addNewPaidMember($request, Session::get('newMemberId'),Session::get('package_id'));
			}else{
				$result = FrontEndUser::addMember1($request,Session::get('package_id'));
			}
			
			if($result>0)
			{

			Session::put('newMemberId', $result);

			//mkdir("./member_images/".$result); hellosgtech@gmail.com

		    return redirect()->intended("Member_registration_step2");   exit;
		   }
		   else if($result==-1)

		   {		     
			 return redirect()->intended('Member_registration_step1?emailExists=1'.$errorString);exit;

		   }
		   else
		   {	     
			 return redirect()->intended('Member_registration_step1?error=1'.$errorString);exit;
		   }
			
		}
		return view('members.Member_registration_step1', $output);
	}
	
	public function registerstep2(Request $request){
		$output = array();
		
	   	$logo_data = array(
			'logo_id' => 1,
			);

		$logo_details = DB::table('website_logo')
		->where($logo_data)
		->first();  
		
		$get_logo = $logo_details->logo;		
		$output['pageTitle'] = 'Member Registration';
		$output['logo'] = $get_logo;
		
		$output['cotinents'] = FrontEndUser::getContinents();
        $output['countries'] = FrontEndUser::getCountries('');
        
		if(empty(Session::get('newMemberId')) ){
			return redirect()->intended('Member_registration_step1');
		}
		
		if (empty(Session::get('package_id'))){
		   
          return redirect()->intended('Member_registration_step1');
        }
        
		if ($request->input('addMember2')) {

            $errorString = "&address1=" . $request->input('address1') . "&continent=" . $request->input('continent') . "&country=" . $request->input('country') . "&state=" . $request->input('state') . "&city=" . $request->input('city') . "&postalCode=" . $request->input('postalCode') . "&facebook=" . $request->input('facebook') . "&twitter=" . $request->input('twitter') . "&instagram=" . $request->input('instagram') . "&linkedin=" . $request->input('linkedin');
			
			if ($request->input('address1')) {
				Session::put('sess-member-address1', $request->input('address1'));
			}

			if ($request->input('state')) {
				Session::put('sess-member-state', $request->input('state'));
			}

			if ($request->input('city')) {
			   Session::put('sess-member-city', $request->input('city'));
			}

			if ($request->input('postalCode')) {
				Session::put('sess-member-postalCode', $request->input('postalCode'));
			}

			if ($request->input('facebook')) {
				Session::put('sess-member-facebook', $request->input('facebook'));
			}
			if ($request->input('twitter')) {
				Session::put('sess-member-twitter', $request->input('twitter'));
			}
			if ($request->input('instagram')) {
				Session::put('sess-member-instagram', $request->input('instagram'));
			}
			if ($request->input('linkedin')) {
				Session::put('sess-member-linkedin', $request->input('linkedin'));
			}
			
			$result = FrontEndUser::addMember2($request, Session::get('newMemberId'));
			//print_array($result);die('--YSYS');
			if ($result['numRows'] > 0) {

				$email = urldecode($result['data'][0]->email);
				/* $emailLink = base_url('Member_registration_step6?id='.$result['data'][0]->id);
				$message = 'Hi '.$result['data'][0]->fname.',<br />
				<p><a href="'.$emailLink.'">Click here</a> to confirm your account.</p>';


			   // email			
				$this->load->library('email');
				$this->email->from('info@digiwaxx.com', 'Digiwaxx');
				$this->email->to($email);
				$this->email->set_mailtype("html");
				$this->email->subject('Member signup at DIGIWAXX');
				$this->email->message($message);
				$this->email->send(); */
				 return redirect()->intended('Member_registration_step3');
                exit;
			}else {
                return redirect()->intended('Member_registration_step2?error=1' . $errorString);
                exit;
            }
		}
		return view('members.Member_registration_step2', $output);
	}
	public function registerstep3(Request $request){
		$output = array();
		$logo_data = array(
			'logo_id' => 1,
			);

		$logo_details = DB::table('website_logo')
		->where($logo_data)
		->first();  
		
		$get_logo = $logo_details->logo;		
		$output['pageTitle'] = 'Member Registration';
		$output['logo'] = $get_logo;
		
		if(empty(Session::get('newMemberId'))){
			return redirect()->intended('Member_registration_step1');
		}
		
		if (empty(Session::get('package_id')) ){
           return redirect()->intended('Member_registration_step1');
        }
		
		
		if ($request->input('addMember')) {
		 {
			$errorString = "&age=".$request->input('age')."&sex=".$request->input('sex')."&stageName=".$request->input('stageName')."&website=".$request->input('website')."&artist=".$request->input('artist')."&howheard=".$request->input('howheard');

			if ($request->input('sex')) {
				Session::put('sess-member-sex', $request->input('sex'));
			}
			if ($request->input('stageName')) {
				Session::put('sess-member-stageName', $request->input('stageName'));
			}
			if ($request->input('howheard')) {
				Session::put('sess-member-howheard', $request->input('howheard'));
			}
			if ($request->input('artist')) {
				Session::put('sess-member-artist', $request->input('artist'));
			}
			if ($request->input('playlist_contributor')) {
				Session::put('sess-member-contributor', $request->input('playlist_contributor'));
			}
			
			if ($request->input('playlists')) {				
				$playlists = $request->input('playlists');
				//echo'<pre>';print_r($playlists);die('---YSYSYS');
				$playlist_count = count($playlists);
				for ($i = 0; $i < $playlist_count; $i++) {	
					 Session::forget('sess-member-contributor-'.$playlists[$i]);
					 Session::put('sess-member-contributor-'.$playlists[$i], $playlists[$i]);                
				}
				
			 
			}
			
			$result = FrontEndUser::addMember3($request,Session::get('newMemberId')); 

		   if($result>0)

		   {

		    return redirect()->intended("Member_registration_step4");   exit;  

		   }

		   else

		   {

		    return redirect()->intended("Member_registration_step3?error=1".$errorString);   exit;

		   }
		 }
		
		}
		$output['stagename'] = $request->input('stagename');
		return view('members.Member_registration_step3', $output);
	}

	public function registerstep4(Request $request){
		$output = array();
		
		$logo_data = array(
			'logo_id' => 1,
			);

		$logo_details = DB::table('website_logo')
		->where($logo_data)
		->first();  
		
		$get_logo = $logo_details->logo;		
		$output['pageTitle'] = 'Member Registration';
		$output['logo'] = $get_logo;
		
		if(empty(Session::get('newMemberId'))){
			return redirect()->intended('Member_registration_step1');
		}
		
		if (empty(Session::get('package_id')) ){
           return redirect()->intended('Member_registration_step1');
        }
        
		$package_id=Session::get('package_id');
		$package_details=DB::table("manage_packages")->where('id','=',$package_id)->get();
		$output['package_details']=$package_details;
		foreach($package_details as $key=>$value){
		    $package_amount=$value->package_price;
		}
// 		$package_amount=DB::table("manage_packages")->where('id','=',$package_id)->select('package_price')->get();
		
		$result = FrontEndUser::addMember4($request, Session::get('newMemberId'));
			if ($result['numRows'] > 0) {                
                $email = urldecode($result['data'][0]->email);
			    
			}
			$output['email']=$email;
		
		if ($request->input('addMember4') || !empty(Session::get('packagepaymentDone'))) {
		 {
		     $mem_id=Session::get('newMemberId');
		     
		     $start_date='';
		     $exp_date='';
		     $payment_method='';
		     $payment_amount='';
		     $curTime = new \DateTime();
             $created_at = $curTime->format("Y-m-d H:i:s");
		     $start_date= date('Y-m-d', strtotime("+1 day", strtotime($created_at)));
		     if($package_id==8){
		        $start_date= date('Y-m-d', strtotime("+1 day", strtotime($created_at)));
		        $exp_date= date('Y-m-d', strtotime("+30 day", strtotime($start_date)));
		        $payment_method='stripe';
		        $payment_amount=$package_amount;
		        
		     }
		      
		     if($package_id==9){
		        $start_date= date('Y-m-d', strtotime("+1 day", strtotime($created_at)));
		        $exp_date= date('Y-m-d', strtotime("+180 day", strtotime($start_date)));
		        $payment_method='stripe';
		        $payment_amount=$package_amount;
		        
		     }
		       if($package_id==10){
		        $start_date= date('Y-m-d', strtotime("+1 day", strtotime($created_at)));
		        $exp_date= date('Y-m-d', strtotime("+365 day", strtotime($start_date)));
		        $payment_method='stripe';
		        $payment_amount=$package_amount;
		        
		     }
		     
		     
		       	$update_query=DB::table('package_user_details')->where('user_id',$mem_id)->update([
			        'package_id'=>$package_id,
                    'payment_status'=>1	,
                    'package_start_date'=>$start_date,
                    'package_expiry_date' =>$exp_date,
                    'payment_method'=>$payment_method,
                    'payment_amount'=>$payment_amount,
                    'package_active'=>1
			    ]);
			    
			 //print_array( Session::get('newMemberId') );die('---ssss');
			$result = FrontEndUser::addMember4($request, Session::get('newMemberId'));
			if ($result['numRows'] > 0) {                
                $email = urldecode($result['data'][0]->email);
             
                $nameofuser=urldecode($result['data'][0]->fname);

             
                $emailofuser = $email;
                
	
        	    $query=DB::table("package_user_details")->where('user_id','=',$mem_id)->where('package_active',1)->get();
        	    
        	    
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
        	        $string = array('id'=>$mem_id, 'code'=>$code,'type'=>'1');
        	        $encode_string=base64_encode(serialize($string));
        	        
        	        $update_token=DB::table('members')->where('id','=',$mem_id)->update(['veri_token'=>$code]); 

        	        // $output['memberInfo'] = $this->memberAllDB_model->getMemberInfo_fem($mem_id);       	        
        	       
        	        $my_ver_link= route("verify_mail",['mtoken'=>$encode_string]);
        	        
        	        $appUrl =  env('APP_URL');
        	        
        			if(!empty($emailofuser)){

        				$data = array('appUrl' => $appUrl, 'emailId' => $emailofuser, 'name' => $nameofuser,'title' =>$title,'string'=>$encode_string,'veri'=>$my_ver_link);
        				Mail::send('mails.package.confirm_package',['data' => $data], function ($message) use ($data) {
        				  $message->to($data['emailId']);
        				  $message->subject('Registerd Successfully');
        				  $message->from('business@digiwaxx.com','Digiwaxx');        				
        			   });

        			}
        			
        			if($_SERVER['REMOTE_ADDR'] == '122.185.217.118'){
        				$admin_email='gurpreet@orientaloutsourcing.com';
        			}else{
        				$admin_email='music@digiwaxx.com';
        			} 

					$member_details = DB::table("members")->where("id", $mem_id)->first();

					$member_socialMed = DB::table("member_social_media")->where("memberId", $mem_id)->first();

				    $fbPattern = '/^(https?:\/\/)?(www\.)?facebook\.com\/([a-zA-Z0-9_\-]+)\/?$/i';
				    $twtPattern = '/^(https?:\/\/)?(www\.)?twitter\.com\/([a-zA-Z0-9_]+)\/?$/i';
				    $instaPattern = '/^(https?:\/\/)?(www\.)?instagram\.com\/([a-zA-Z0-9_]+)\/?$/i';
				    $linkedinPattern = '/^(https?:\/\/)?(www\.)?linkedin\.com\/in\/([a-zA-Z0-9_\-]+)\/?$/i';

					$fbLink = '';
					$twtLink = '';
					$instaLink = '';
					$linkedin = '';				    					

					$memChkIsDjMixer = 'No';
					$djMixerClubname = '';
					$memChkRadioStation = 'No';
					$djRadioStationname = '';
					$memChkPlaylistCon = 'No';

					$memChkIsDjTypClub = 'No';

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
					//$memIsDjTypClub = $member_details->djtype_club;
					$memRadioStation = $member_details->radio_station;
					$memPlaylistCon = $member_details->playlist_contributor;

					$djsName = $member_details->fname;

					if(!empty($memStageName)){
						$djsName = $memStageName;
					}

					if($memIsDjMixer == 1){
						if($memIsDjMixer == 1){
							$memChkIsDjMixer = 'Yes';
						}

						$djMixerDetails = DB::table("members_dj_mixer")->where("member", $mem_id)->select('clubdj_clubname')->first();

						if(!empty($djMixerDetails->clubdj_clubname)){
							$djMixerClubname = urldecode($djMixerDetails->clubdj_clubname);
						}

					}					

					if($memRadioStation == 1){
						$memChkRadioStation = 'Yes';
					}

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
        			
        			if(!empty($admin_email)){
        				$data = array('appUrl' => $appUrl, 'djMemId' => $mem_id ,'emailId' => $admin_email, 'name' => $memName,'title' =>$title,'email_user'=>$emailofuser, 'memstagename' => $memStageName, 'memaddress' => $memFullAddress, 'fb' => $fbLink, 'twt' => $twtLink, 'insta' => $instaLink, 'linkedin' => $linkedin,'djPlaylistContributor'=>$memChkPlaylistCon,'djMixer'=>$memChkIsDjMixer, 'djMixerClubname'=>$djMixerClubname,'djRadioStation'=>$memChkRadioStation, 'djsName' => $djsName);
        				Mail::send('mails.package.admin_confirm_package',['data' => $data], function ($message) use ($data) {
        				  $message->to($data['emailId']);
        				  $message->subject('New Dj Member Sign up Notification');
        				  $message->from('business@digiwaxx.com','Digiwaxx');
        			   });
        			}
        			
        	       // return response()->json('success');
        	    }

                /* $htmlTemplate =  file_get_contents(base_url("templates/newsletter/registration.php"));
                $htmlTemplate = str_replace("*message*", $message, $htmlTemplate);


                $this->load->library('email');
                $this->email->from('no-reply@digiwaxx.com', 'Digiwaxx');
                $this->email->to($email);
                $this->email->set_mailtype("html");
                $this->email->subject('Member signedup at Digiwaxx');


                $this->email->message($htmlTemplate);
                $this->email->send();
                
                
                $this->email->from('no-reply@digiwaxx.com', 'Digiwaxx');
                $this->email->to('admin@digiwaxx.com');
                $this->email->set_mailtype("html");
                $this->email->subject('New Member Sign up Notification');
                $this->email->message("A new member has signed up on digiwaxx as a Memeber with email : ".$email);
                $this->email->send();

                echo '<br />';
                echo $email;
                echo '<br />';
                // mail chimp integration
                $this->load->library('MCAPI');
                $retval = $this->mcapi->listSubscribe('57f11536f6', $email); */
                return redirect()->intended("Member_registration_step5");
                exit;
			}else{
				 return redirect()->intended("Member_registration_step4?error=1");
                exit;
			}
		 }		
		}
		return view('members.Member_registration_step4', $output);
	}

	public function registerstep5(Request $request){ 
		if(empty(Session::get('newMemberId'))){
			return redirect()->intended('Member_registration_step1');
		}
		
		if (empty(Session::get('package_id')) ){
           return redirect()->intended('Member_registration_step1');
        }
		
		$output = array();
		$logo_data = array(
			'logo_id' => 1,
			);

		$logo_details = DB::table('website_logo')
		->where($logo_data)
		->first();  
		
		$get_logo = $logo_details->logo;		
		$output['pageTitle'] = 'Member Registration';
		$output['logo'] = $get_logo;
		
		
		Session::flush();
		return view('members.Member_registration_step5', $output);
	} 
	
	///verify mail
	public function verify_mail($mtoken){
        $data = unserialize(base64_decode($mtoken));
	   if($data['type']=='1'){
	       
	       $check=DB::table('members')->where('id','=',$data['id'])->where('veri_token','=',$data['code'])->update(['active'=>1]);
	       if($check){
	           $clear_token=DB::table('members')->where('id','=',$data['id'])->where('veri_token','=',$data['code'])->update(['veri_token'=>'']);
	            return redirect()->intended('login?success=1');
	       }else{
	           //return redirect('forget-password');
	            return redirect()->intended("forgot-password?verify=2");
	       }
	       
	       
	   }
	   
	    if($data['type']=='2'){
	       
	       $check=DB::table('clients')->where('id','=',$data['id'])->where('veri_token','=',$data['code'])->update(['active'=>1]);
	       if($check){
	           $clear_token=DB::table('clients')->where('id','=',$data['id'])->where('veri_token','=',$data['code'])->update(['veri_token'=>'']);
	           return redirect()->intended('login?success=1');
	       }else{
	           //return redirect('forget-password');
	             return redirect()->intended("forgot-password?verify=2");
	       }
	       
	       
	   }
	   
	   
	}
	

    // Registraion package selection step
    
    public function package_selection_registration(){
		
		$output = array();
		$packages = array();
		$logo_data = array(
			'logo_id' => 1,
			);

		$logo_details = DB::table('website_logo')
		->where($logo_data)
		->first();  
		
		$get_logo = $logo_details->logo;		
		$output['pageTitle'] = 'Member Registration';
		$output['logo'] = $get_logo;
		
		$query = DB::table('manage_packages')->select('id','package_name','package_type','package_price','package_features as features')->where('package_status','=',1)->where('available_to','=',1)->orderBy("priority")->get();
		$pck_count=count($query);
		
		$output['pack_count']=$pck_count;
		if($pck_count==1){
		    $output['pck_class']='col-lg-12 col-md-6 col-sm-6 pb-2';
		    
		}
		else if($pck_count==2){
		    $output['pck_class']='col-lg-6 col-md-6 col-sm-6 pb-2';
		    
		}
		else if ($pck_count==3){
		    $output['pck_class']='col-lg-4 col-md-6 col-sm-6 pb-2';
		    
		}
		else{
		    $output['pck_class']='col-lg-3 col-md-6 col-sm-6 pb-2';
		    
		}
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
        return view('members.Member_registration_package_select', $output);
    }
}

