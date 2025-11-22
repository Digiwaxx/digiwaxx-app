<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Route;
use App\Models\Frontend\Faq;
use Illuminate\Support\Facades\Redirect;
use Hash;
use Session;
use Mail;

class NewsController extends Controller
{
	protected $frontend_model;
	/**
      * Create a new controller instance.
	  *
	  * @return void
	  */
	public function __construct()
	{

		$this->frontend_model = new \App\Models\Frontend\FrontEndUser;
		
	}
	
	public function list_news(Request $request){
		$output=array();
			$admin_name = Auth::user()->name;
			$user_role = Auth::user()->user_role;
	    $get_news=DB::table('news_details')->select('title','description','added')->get();
        // print_r($get_news);
        // die();
        $output['get_news']=$get_news;
        $output['pageTitle'] = 'News';
				$output['welcome_name'] = $admin_name;
		$output['user_role'] = $user_role;
	    return view('admin/list_news',$output);
	}
	
	public function add_news_view(){
	    $output['pageTitle'] = 'Add News';
	    return view('admin/list_news_add',$output);  
	}
	
	public function add_news(Request $request){
	    $curTime = new \DateTime();
        $created_at = $curTime->format("Y-m-d H:i:s");
	    
	   $insert_relations = DB::table('news_details')->insert([
                    'Title'       => $request->post('news_title'),
                    'Description' => $request->post('news_description'),
                    'Image'       => $request->post('news_image'),
                    'Added'       => $created_at
                   
                ]);
                
        return redirect('admin/news');        
	  
	}

}
