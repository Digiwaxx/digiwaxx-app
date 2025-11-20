<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use DB;
use Carbon\Carbon;
use App\Models\Frontend\FrontEndUser;
use Mail;

class ForgotPasswordController extends Controller
{
    public function getEmail(Request $request)
    {
	   $output = array();
	   $result='';
	   $class='';
	   
	   
	   if(isset($_GET['verify']) && !empty($_GET['verify'])){
	       $result="Your verification link has been expired.";
	       $class="alert alert-warning";
	   }
	   
	   $output['result']=$result;
	   $output['class']=$class;
	   
	   	$logo_data = array(
			'logo_id' => 1,
			);

		$logo_details = DB::table('website_logo')
		->where($logo_data)
		->first();  
		
// 		$get_logo = $logo_details->logo;
        $get_logo = $logo_details->pCloudFileID; //pCloudFileID
		//print_r($logo_details->logo);die;
		// echo $get_logo;die('---');
		$output['pageTitle'] = 'Forgot Password';
		$output['logo'] = $get_logo;
	   
	   if ($request->input('mailSent')) {
			$output['alert_class'] = 'alert-success';

			$output['alert_message'] = 'A fresh verification link has been sent to your email address!';
		} else if ($request->input('error')) {

			$output['alert_class'] = 'alert-danger';

			$output['alert_message'] = 'Error occured, please try again!';
		} else if ($request->input('invalidEmail')) {

			$output['alert_class'] = 'alert-danger';

			$output['alert_message'] = 'Entered email is not registered!';
		}else if ($request->input('invalidCode')) {

			$output['alert_class'] = 'alert-danger';

			$output['alert_message'] = 'Invalid or Expired Token!. Please try to reset password again.';
		}
		
		
		
// 		if($_SERVER['REMOTE_ADDR'] = '122.185.217.118'){
//              pArr($output);die();
//         }
       return view('mails.password.email', $output);
    }

    public function postEmail(Request $request)
    {	
		$userType = '';
		if($request->input('sendMail')){
			if(!empty($request->input('user'))){
				$userType = $request->input('user');
			}
			
			$result = FrontEndUser::forgotPassword($request->input('email'), $userType);
			//die();
			if ($result['numRows'] > 0) {
				if ($result['insertId'] > 0) {
					$email = urldecode($result['data'][0]->email);
					if(!empty($result['data'][0]->name))
					{                    
					  $name = urldecode($result['data'][0]->name);                    
					}                 
					else if(!empty($result['data'][0]->fname))
					{
					  $name = urldecode($result['data'][0]->fname);
					}
					$data = array('emailId' => $email, 'token' => $result['code'],'name' => $name);
					Mail::send('mails.password.verify',['data' => $data], function ($message) use ($data) {
					  $message->to($data['emailId']);
					  $message->subject('Reset Password Notification');
					  $message->from('business@digiwaxx.com','DigiWaxx');
				   });
				   return redirect()->intended('/forgot-password?mailSent=1');
					exit;
				}else{
					return redirect()->intended('/forgot-password?error=1');
					exit;
				}
			}else{
				return redirect()->intended('/forgot-password?invalidEmail=1');
                exit;
			}
		}
		die('--ININ');
        $request->validate([
            'email' => 'required|email|exists:users',
        ]);

        $token = Str::random(60);

        DB::table('password_resets')->insert(
            ['email' => $request->email, 'token' => $token, 'created_at' => Carbon::now()]
        );

        Mail::send('auth.password.verify',['token' => $token], function($message) use ($request) {
                  $message->to($request->email);
                  $message->subject('Reset Password Notification');
               });

        return back()->with('message', 'We have e-mailed your password reset link!');
    }
	
}