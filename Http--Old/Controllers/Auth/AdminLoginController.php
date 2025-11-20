<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Auth;
Use Redirect;
use Session;
use Validator;

// for mail sending
use Illuminate\Support\Facades\Mail;
use App\Mail\AdminForgetNotification;

class AdminLoginController extends Controller
{

  public function __construct()
  {
    $this->middleware('guest:admin', ['except' => ['admin_logout']]);
      
  }

    
  public function admin_showloginPage(){


    return view('/admin/admin_login');


}

public function validate_admin_login(Request $request){
if ($request->isMethod('post')) {
  //  return true;

  $this->validate($request,[ 
    // 'email' => 'required|email',
    'password' => 'required|min:6',

  ]);

  $email_get = $request->email;
  if(!empty($request->email)){
    $query = DB::table('admins')

            ->select(DB::raw('admins.email as email, admins.id as id, admins.user_role as user_role'))
            ->where('admins.email', '=', $email_get)->orWhere('admins.uname','=',$email_get);           
   }

       $result = $query->first();

//   if(Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password], $request->remember) ){
  if(Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password], $request->remember) || Auth::guard('admin')->attempt(['uname' => $request->email, 'password' => $request->password], $request->remember)){

      // update last login date 

      $query = DB::table('admins')
      ->where('email', $email_get)
      ->update(['lastlogon' => date("Y-m-d h:i:s")]);

      // set cookies while login 
      setcookie('adminId', $result->id, 0, "/");
      setcookie('user_role', $result->user_role, 0, "/");
      Session::put('admin_Id', $result->id);

      return redirect()->intended(route('admin_dashboard'));

  }

  return redirect()->back()->with('danger', 'Invalid Credentials');  
  //return redirect()->back()->withInput($request->only('email', 'remember'));
 }else{
        abort(404);
 }

}

  public function admin_logout(Request $request) { 


            Auth::guard('admin')->logout();

            $request->session()->flush(); 
            
            $request->session()->regenerate();  
            
            if (isset($_COOKIE['adminId'])) {
                unset($_COOKIE['adminId']); 
                setcookie('adminId', null, -1, '/');
            }

            if (isset($_COOKIE['user_role'])) {
              unset($_COOKIE['user_role']); 
              setcookie('user_role', null, -1, '/');
          }
            
           return redirect('/admin');
    }


      public function AdminForgetNotification_function(Request $request)
      {   
                //  print_r("dfdfdfd");die;
  
                  // $return_status = array(
                  //     'status' => FALSE,
                  //     'message' => trans('custom.fail_mail'),
                  //     'data' => array()
                  // );
  
                 // $this->validate($request, ['forget_email' => 'required|email']);
  
                   $to_email = $request->forget_email;

                   if(!empty($to_email)){
                    $query = DB::table('admins')
    
                            ->select(DB::raw('admins.email as email, admins.id as id'))
                            ->where('admins.email', '=', $to_email);           
                   }
               
                       $result = $query->first();

                    if(!empty($result)){

                          // print_r($result);die;

                            $to_email = $request->forget_email;
                            // $to_email ='school_test007@yopmail.com';
                          
                            $m_sub = 'Mail Notification To Reset Password';

                            // url for reset admin password
                          $url_for_reset = '<span><a class="btn btn-info" href="' . route('admin_reset_password_mail', ['ad_mail' => $result->id]) . '" title="Click Me To Reset Password">' . 'Click Here' . '</a></span>';

                          $m_msg = 'Click on the folowing link to reset your password<br><br>'.$url_for_reset;
            
                              $mail_data = [
                                  'm_sub' => $m_sub,
                                  'm_msg' => $m_msg,
                              ];
            
                                $sendInvoiceMail = Mail::to($to_email);
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

                                return redirect()->back()->with('success_avail_email', 'Mail sent Successfully. Please check your Email '); 

                  }
                else{

                  return redirect()->back()->with('danger_no_email', 'Entered Email Not Exist'); 
                
                }

                     // print_r($return_status);
  
                      // return response()->json(//returning JSON to blueimp
                      //     $return_status
              // );

               
      }

      public function admin_reset_password_mail($ad_mail = NULL){

        $data = [
          'ad_mail'     => $ad_mail,
         
        ];
    
        return view('admin_reset_password_form_view', $data);

    }


    public function submit_reset_admin_password(Request $request){

      $id = $request->ad_id;
      $get_new_password= $request->new_password;

      $this->validate($request,[ 

        'new_password' => 'required|min:6',
    
      ]);
  
      if(!empty($id)){

        $query = DB::table('admins')
                        ->where('id', $id)
                        ->update(['password' => bcrypt($get_new_password)]);


          return redirect('admin')->with('password_changed', 'Password Reset Successfully');

        }    

  }




   
}
