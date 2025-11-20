<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\User;
use Hash;
use App\Models\Frontend\FrontEndUser;
use Illuminate\Support\Facades\Redirect;
use Mail;

class ResetPasswordController extends Controller
{
    public function getPassword($token) {

       return view('auth.password.reset', ['token' => $token]);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required',

        ]);

        $updatePassword = DB::table('password_resets')
                            ->where(['email' => $request->email, 'token' => $request->token])
                            ->first();

        if(!$updatePassword)
            return back()->withInput()->with('error', 'Invalid token!');

          $user = User::where('email', $request->email)
                      ->update(['password' => Hash::make($request->password)]);

          DB::table('password_resets')->where(['email'=> $request->email])->delete();

          return redirect('/login')->with('message', 'Your password has been changed!');

    }
	public function getResetPassword($token = null)
    {
			$output = array();
			$output['token'] = $token;
			if(is_null($token)) {
				  return redirect()->intended('/login');   exit;
			}
			 
			$logo_data = array(
			'logo_id' => 1,
			);

			$logo_details = DB::table('website_logo')
			->where($logo_data)
			->first();  
			
// 			$get_logo = $logo_details->logo;
            $get_logo = $logo_details->pCloudFileID; //pCloudFileID
			$output['logo'] = $get_logo;
			
			$invalidCode = '';
			
		 $iSCodeExpired = FrontEndUser::isExpiredConfirmCode($token);
		 
		 if($iSCodeExpired['numRows']==1){
			$invalidCode = 0; 
			$output['invalidCode'] = 0;
		 }else{
			$invalidCode = 1; 
			
			$output['invalidCode'] = 1;
			$output['alert_class'] = 'error-msg';
			$output['alert_message'] = 'Invalid or Expired Token!. Please try to reset password again.';
			//return redirect('forgot-password')->with('error', 'Invalid Token!');
			return Redirect::to('forgot-password?invalidCode=1');
		 }
		 
		 if(isset($_GET['reset'])){			  

			   $output['alert_class'] = 'success-msg';

			   $output['alert_message'] = 'Password has been reset successfully!';			

		}else if(isset($_GET['error'])){

			   $output['alert_class'] = 'error-msg';

			   $output['alert_message'] = 'Error occured, please try again!';
		}
			
		//pArr($output);die();	 
		return view('auth.password.reset-frontend',  $output);
    }
	public function updateResetPassword(Request $request)
    {
		$output = array();
		if($request->input('password') && $request->input('token')){
			//die('--ININI');
			$result = FrontEndUser::resetPassword($request->input('password'),$request->input('token'));
			if($result['numRows']==1){
				if($result['update']==1){
					$email = urldecode($result['data'][0]->email);
					//$email = 'gssgtech@yopmail.com'; // Comment or Remove it for move to production mode
					if(isset($result['data'][0]->name))
					{
					 $name = urldecode($result['data'][0]->name);
					 }
					 else if(isset($result['data'][0]->fname))
					{
					 $name = urldecode($result['data'][0]->fname);
					 }
					 
					 $data = array('emailId' => $email, 'name' => $name, 'pwd'=> $request->input('password'));
						Mail::send('mails.password.confirmemail',['data' => $data], function ($message) use ($data) {
						  $message->to($data['emailId']);
						  $message->subject('Confirmation of reset password at Digiwaxx');
						  $message->from('business@digiwaxx.com','DigiWaxx');
					   });					
					return Redirect::to('login?reset=1');
				}else{					
					return Redirect::to('forgot-password?error=1');
				}
			}else{				
				
				return Redirect::to('forgot-password?invalidCode=1');
			}
		}
		//die('--Out');
		return redirect('/login')->with('message', 'Your password has been changed!');
    }
}