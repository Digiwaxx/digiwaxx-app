<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Hash;
use DB;

class RegisterController extends Controller
{
  public function register()
  {
	   $output = array();
	   
	   	$logo_data = array(
			'logo_id' => 1,
			);

		$logo_details = DB::table('website_logo')
		->where($logo_data)
		->first();  
		
// 		$get_logo = $logo_details->logo;	
        $get_logo = $logo_details->pCloudFileID; //pCloudFileID
		$output['pageTitle'] = 'Register';
		$output['logo'] = $get_logo;
		
    return view('auth.register', $output);
  }

  public function storeUser(Request $request)
  {
      $request->validate([
          'name' => 'required|string|max:255',
          'email' => 'required|string|email|max:255|unique:users',
          'password' => 'required|string|min:8|confirmed',
          'password_confirmation' => 'required',
      ]);

      User::create([
          'name' => $request->name,
          'email' => $request->email,
          'password' => Hash::make($request->password),
      ]);

      return redirect('login');
  }

}