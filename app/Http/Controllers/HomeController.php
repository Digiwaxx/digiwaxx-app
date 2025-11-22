<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Route;
Use Redirect;
use Session;
use Mail;

class HomeController extends Controller
{
	protected $frontend_model;
	
	public function __construct(){
		$this->frontend_model = new \App\Models\Frontend\FrontEndUser;
	}
	
    public function home()
    {
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
		
		$sortOrder = "DESC";
		$sortBy = "tracks.id";
		
		$sort =  $sortBy . " " . $sortOrder;
		
		$output['tracks'] = $this->frontend_model->getAllTracks_trm();
		
		//$output['tracks'] = $this->frontend_model->getAllPriorityTracks_trm($sort);
		//pArr($output['tracks']);die();
      return view('home', $output);
    }
}