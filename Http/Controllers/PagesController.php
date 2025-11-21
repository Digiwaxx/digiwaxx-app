<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Route;
use App\Models\Frontend\Faq;
use Illuminate\Support\Facades\Redirect;
use Hash;
use Session;
use Mail;

class PagesController extends Controller
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

	public function homePage()
	{
		$logo_data = array(
			'logo_id' => 1,
		);
		$music_details = DB::table('tracks')->orderBy('added', 'DESC')->where('status', '=', 'publish')->where('deleted', '=', 0)->take(10)->select('artist', 'title', 'imgpage', 'id', 'pCloudFileID', 'pCloudParentFolderID')->get();
		$mygetdate = \Carbon\Carbon::today()->subDays(7);

		//  $max_downloads = DB::table('tracks_mp3s')->rightJoin('tracks', 'tracks_mp3s.track', '=', 'tracks.id')->where('status','=','publish')->orderBy('downloads','DESC')->take(10)->select('tracks.artist','tracks.title','tracks.album','tracks.imgpage','downloads','tracks.link')->get();
		//  $max_news= DB::table('news_details')->orderBy('added','DESC')->where('approved','=','1')->select('title','image','id')->get();   
		$max_news = DB::table('news_details')->orderBy('added', 'DESC')->where('approved', '=', '1')->select('title', 'image', 'id', 'pCloudFileID', 'pCloudParentFolderID')->get();

		$sneakers = DB::table('product_sneaker_details')->orderBy('created_on', 'ASC')->where('status', 1)->select('name', 'price', 'img_path', 'pCloudFileID', 'pCloudParentFolderID')->get();

		date_default_timezone_set('America/Los_Angeles');

		$year = date('Y');

		$month = date('m');

		$date = date('d');

		$monday = date('Y-m-d', strtotime('monday this week'));

		$sunday = date('Y-m-d', strtotime('sunday this week'));

		// $where = "where tracks.deleted = '0'  and track_member_downloads.downloadedDateTime like '". $year.'-'.$month ."%'";
		$where = "where tracks.deleted = '0'  and track_member_downloads.downloadedDateTime > '" . $monday . "' AND track_member_downloads.downloadedDateTime < '" . $sunday . "' ";
		//$where = "where tracks.deleted = '0'  and track_member_downloads.downloadedDateTime like '". $year."%'";


		$sort = 'tracks_mp3s.downloads desc';
		$limit = 10;

		// $max_reviews=DB::select("SELECT tracks_reviews.track,tracks_reviews.additionalcomments,tracks.album,tracks.artist,tracks.title,tracks.imgpage from tracks_reviews left join tracks on tracks_reviews.track = tracks.id where tracks_reviews.added > '". $monday ."' AND tracks_reviews.added< '". $sunday ."' AND tracks.deleted=0 AND tracks.status='publish' AND tracks.approved=1");
		//  $output['max_reviews']=$max_reviews;
		$output['max_reviews'] = '';

		// SECURITY FIX MEDIUM: Use parameterized queries to prevent SQL injection
	// Variables $monday and $sunday are PHP-generated (safe), but using bindings is best practice
	$max_downloads = DB::select("SELECT DISTINCT track_member_downloads.trackId,  COUNT(track_member_downloads.trackId) AS downloads, tracks.id, tracks.title, tracks.album, tracks.imgpage,tracks.pCloudFileID,tracks.pCloudParentFolderID FROM   track_member_downloads

               left join tracks_mp3s on track_member_downloads.mp3Id = tracks_mp3s.id
               left join tracks on track_member_downloads.trackId = tracks.id
               WHERE tracks.deleted = '0' AND track_member_downloads.downloadedDateTime > ? AND track_member_downloads.downloadedDateTime < ?
               GROUP BY tracks.id order by tracks_mp3s.downloads desc limit ?", [$monday, $sunday, $limit]);

		//   pArr($max_downloads);
		//   die();



		$videos_query = DB::table('digiwaxx_videos')->where('status', 1)->select('video_url', 'title')->get();
		$output['videos'] = $videos_query;


		$logo_details = DB::table('website_logo')
			->where($logo_data)
			->first();

		// 		$get_logo = $logo_details->logo;
		$get_logo = $logo_details->pCloudFileID; //pCloudFileID
		$output['pageTitle'] = 'Logos';
		$output['logo'] = $get_logo;

		$output['banner'] = $this->frontend_model->getBanner(2);
		$output['bannerText'] = $this->frontend_model->getBannerText(2);
		$output['pageLinks'] = $this->frontend_model->getPageLinks(2);
		$output['topLinks'] = $this->frontend_model->getTopLinks();
		$output['youtube'] =  $this->frontend_model->getYoutube();
		$output['new_music'] = $music_details;
		$output['max_downloads'] = $max_downloads;
		$output['max_news'] = $max_news;
		$output['sneaker'] = $sneakers;
		$sortOrder = "DESC";
		$sortBy = "tracks.id";

		$sort =  $sortBy . " " . $sortOrder;

		// 		$output['tracks'] = $this->frontend_model->getAllTracks_trm();
		$output['tracks'] = $this->frontend_model->getAllTracks_custom_optimized();

		//$output['tracks'] = $this->frontend_model->getAllPriorityTracks_trm($sort);
		//pArr($output['tracks']);die();

		// 		$query = DB::table('products')->rightJoin('product_price','products.product_id', '=', 'product_price.product_id')->where('products.merch_status','=',1)->take(4)->select('products.name','products.emailimg','product_price.digicoin_price')->get();

		// 		$output['product_query']=$query;
		// 		dd($query);
		// 		pArr($output);
		//         die();	

		//   if($_SERVER['REMOTE_ADDR'] = '2401:4900:1c2a:2ada:851d:f624:6e11:fe55'){


		// echo'<pre>';print_r($output);die();
		// }





		return view('home', $output);
	}


	// ------------------------------------------------------News functionality starts here--------------------------------


	public function displayNews(Request $request, $id)
	{
		$logo_data = array(
			'logo_id' => 1,
		);
		$logo_details = DB::table('website_logo')
			->where($logo_data)
			->first();

		$display_news = DB::table('news_details')->where('id', '=', $id)->get();
		// 		print_r($display_news);
		// 		die();

		// 		$get_logo = $logo_details->logo;	
		$get_logo = $logo_details->pCloudFileID; //pCloudFileID
		$output['pageTitle'] = 'News';
		$output['logo'] = $get_logo;
		$output['banner'] = $this->frontend_model->getBanner(2);
		$output['display_news'] = $display_news;
		return view('news/display_news', $output);
	}


	public function all_news(Request $request)
	{
		$logo_data = array(
			'logo_id' => 1,
		);
		$logo_details = DB::table('website_logo')
			->where($logo_data)
			->first();

		$reminder = 0;
		$numPages = 0;
		$limit = 9;

		$output['numRecords'] = $limit;

		$output['numRecords'] = $limit;

		$start = 0;
		$currentPageNo = 1;

		if (isset($_GET['page']) && $_GET['page'] > 1) {
			$start = ($_GET['page'] * $limit) - $limit;
		}

		if (isset($_GET['page'])) {
			$currentPageNo = $_GET['page'];
		}

		$outputMsgTtlCount = DB::table('news_details')->where('approved', '=', 1)->orderBy('added', 'DESC')->count();


		if ($outputMsgTtlCount > 0) {

			$num_records = $outputMsgTtlCount;

			$numPages = (int)($num_records / $limit);
			$reminder = ($num_records % $limit);
			$output['num_records'] = $num_records;
		}

		if ($reminder > 0) {
			$numPages = $numPages + 1;
		}

		$output['numPages'] = $numPages;
		$output['start'] = $start;
		$output['currentPageNo'] = $currentPageNo;
		$output['currentPage'] = 'news';




		$getArray = array();
		$urlString = '?';

		if (isset($_GET)) {
			foreach ($_GET as $key => $value) {
				if (strcmp($key, 'page') != 0) {
					$getArray[] = $key . '=' . $value;
				}
			}

			if (count($getArray) > 1) {
				$urlString .= implode('&', $getArray);
			} else if (count($getArray) == 1) {
				$urlString .= $getArray[0];
			} else {
				$urlString = '';
			}
		}

		$output['urlString'] = $urlString;

		// uncomment later

		if (isset($_GET['page'])) {
			if (strlen($urlString) > 3) {
				$param = '&';
			} else {
				$param = '?';
			}

			if ($_GET['page'] > $numPages && $numPages > 0) {
				header("location: " . $output['currentPage'] . $urlString . $param . "page=" . $numPages);

				exit;
			}
		}



		// SECURITY FIX HIGH: Convert to Query Builder to prevent SQL injection in pagination
	// $start and $limit are calculated from $_GET['page'] and are vulnerable
	$display_news = DB::table('news_details')
		->where('approved', '=', 1)
		->orderBy('added', 'desc')
		->offset($start)
		->limit($limit)
		->get();

		// 	$get_logo = $logo_details->logo;
		$get_logo = $logo_details->pCloudFileID; //pCloudFileID
		$output['pageTitle'] = 'News';
		$output['logo'] = $get_logo;
		$output['banner'] = $this->frontend_model->getBanner(2);
		$output['display_news'] = $display_news;


		return view('news.news', $output);
	}


	public function all_videos(Request $request)
	{
		$logo_data = array(
			'logo_id' => 1,
		);
		$logo_details = DB::table('website_logo')
			->where($logo_data)
			->first();

		$reminder = 0;
		$numPages = 0;
		$limit = 9;

		$output['numRecords'] = $limit;

		$output['numRecords'] = $limit;

		$start = 0;
		$currentPageNo = 1;

		if (isset($_GET['page']) && $_GET['page'] > 1) {
			$start = ($_GET['page'] * $limit) - $limit;
		}

		if (isset($_GET['page'])) {
			$currentPageNo = $_GET['page'];
		}

		$outputMsgTtlCount = DB::table('digiwaxx_videos')->where('status', '=', 1)->orderBy('created_at', 'DESC')->count();


		if ($outputMsgTtlCount > 0) {

			$num_records = $outputMsgTtlCount;

			$numPages = (int)($num_records / $limit);
			$reminder = ($num_records % $limit);
			$output['num_records'] = $num_records;
		}

		if ($reminder > 0) {
			$numPages = $numPages + 1;
		}

		$output['numPages'] = $numPages;
		$output['start'] = $start;
		$output['currentPageNo'] = $currentPageNo;
		$output['currentPage'] = 'videos';




		$getArray = array();
		$urlString = '?';

		if (isset($_GET)) {
			foreach ($_GET as $key => $value) {
				if (strcmp($key, 'page') != 0) {
					$getArray[] = $key . '=' . $value;
				}
			}

			if (count($getArray) > 1) {
				$urlString .= implode('&', $getArray);
			} else if (count($getArray) == 1) {
				$urlString .= $getArray[0];
			} else {
				$urlString = '';
			}
		}

		$output['urlString'] = $urlString;

		// uncomment later

		if (isset($_GET['page'])) {
			if (strlen($urlString) > 3) {
				$param = '&';
			} else {
				$param = '?';
			}

			if ($_GET['page'] > $numPages && $numPages > 0) {
				header("location: " . $output['currentPage'] . $urlString . $param . "page=" . $numPages);
				exit;
			}
		}



		// SECURITY FIX HIGH: Convert to Query Builder to prevent SQL injection in pagination
	// $start and $limit are calculated from $_GET['page'] and are vulnerable
	$display_news = DB::table('digiwaxx_videos')
		->where('status', '=', 1)
		->orderBy('created_at', 'desc')
		->offset($start)
		->limit($limit)
		->get();

		// 	$get_logo = $logo_details->logo;	
		$get_logo = $logo_details->pCloudFileID; //pCloudFileID
		$output['pageTitle'] = 'Videos';
		$output['logo'] = $get_logo;
		$output['banner'] = $this->frontend_model->getBanner(2);
		$output['videos'] = $display_news;


		return view('videos', $output);
	}





	//  ------------------------------   //Forums function start here  ----------------------------



	public function list_forums(Request $request)
	{



		$id = Session::get('memberId');
		$logo_data = array(
			'logo_id' => 1,
		);

		$logo_details = DB::table('website_logo')
			->where($logo_data)
			->first();

		$reminder = 0;
		$numPages = 0;
		$limit = 3;

		$output['numRecords'] = $limit;

		$output['numRecords'] = $limit;

		$start = 0;
		$currentPageNo = 1;

		if (isset($_GET['page']) && $_GET['page'] > 1) {
			$start = ($_GET['page'] * $limit) - $limit;
		}

		if (isset($_GET['page'])) {
			$currentPageNo = $_GET['page'];
		}

		$outputMsgTtlCount = DB::table('forum_article')->where('art_status', '=', '1')->count();


		if ($outputMsgTtlCount > 0) {

			$num_records = $outputMsgTtlCount;

			$numPages = (int)($num_records / $limit);
			$reminder = ($num_records % $limit);
			$output['num_records'] = $num_records;
		}

		if ($reminder > 0) {
			$numPages = $numPages + 1;
		}

		$output['numPages'] = $numPages;
		$output['start'] = $start;
		$output['currentPageNo'] = $currentPageNo;
		$output['currentPage'] = 'forums';




		$getArray = array();
		$urlString = '?';

		if (isset($_GET)) {
			foreach ($_GET as $key => $value) {
				if (strcmp($key, 'page') != 0) {
					$getArray[] = $key . '=' . $value;
				}
			}

			if (count($getArray) > 1) {
				$urlString .= implode('&', $getArray);
			} else if (count($getArray) == 1) {
				$urlString .= $getArray[0];
			} else {
				$urlString = '';
			}
		}

		$output['urlString'] = $urlString;

		// uncomment later

		if (isset($_GET['page'])) {
			if (strlen($urlString) > 3) {
				$param = '&';
			} else {
				$param = '?';
			}

			if ($_GET['page'] > $numPages && $numPages > 0) {
				header("location: " . $output['currentPage'] . $urlString . $param . "page=" . $numPages);
				exit;
			}
		}


		// SECURITY FIX HIGH: Convert to Query Builder to prevent SQL injection in pagination
	// $start and $limit are calculated from $_GET['page'] and are vulnerable
	$get_query = DB::table('forum_article')
		->where('art_status', '=', 1)
		->orderBy('art_id', 'desc')
		->offset($start)
		->limit($limit)
		->get();

		$array = json_decode(json_encode($get_query), true);

		foreach ($array as $key => $value) {
			if ($value['created_user_type'] == '2') {
				$mem_name = DB::table('members')->where('id', '=', $value['art_created_by'])->select('fname', 'uname')->get();
				// print_r($mem_name);
				foreach ($mem_name as $key1 => $value1) {
					$array[$key]['fname'] = $value1->fname;
					$array[$key]['uname'] = $value1->uname;
				}
			}
			if ($value['created_user_type'] == '3') {
				$mem_name = DB::table('clients')->where('id', '=', $value['art_created_by'])->select('name', 'uname')->get();
				// print_r($mem_name);
				foreach ($mem_name as $key1 => $value1) {
					$array[$key]['fname'] = $value1->name;
					$array[$key]['uname'] = $value1->uname;
				}
			}
		}

		$arr = json_encode($array);
		// print_r($array);

		// 	$get_logo = $logo_details->logo;	
		$get_logo = $logo_details->pCloudFileID; //pCloudFileID
		$output['pageTitle'] = 'Forums';
		$output['logo'] = $get_logo;
		$output['banner'] = $this->frontend_model->getBanner(2);
		$output['bannerText'] = $this->frontend_model->getBannerText(2);
		$output['pageLinks'] = $this->frontend_model->getPageLinks(2);
		$output['topLinks'] = $this->frontend_model->getTopLinks();
		$output['youtube'] =  $this->frontend_model->getYoutube();
		$output['id'] = $id;

		$output['get_article'] = $arr;
		$output['row_count'] = $outputMsgTtlCount;

		return view('forums.list_forum', $output);
	}

	public function addArticle(Request $request)
	{

		if (empty(Session::get('memberId')) && empty(Session::get('clientId'))) {
			return redirect()->intended('login');
		}


		if (!empty(Session::get('memberId'))) {
			$user_role = '2';
			$id = Session::get('memberId');
		}

		if (!empty(Session::get('clientId'))) {
			$user_role = '3';
			$id = Session::get('clientId');
		}






		$logo_data = array(
			'logo_id' => 1,
		);

		$logo_details = DB::table('website_logo')
			->where($logo_data)
			->first();


		// 		$get_logo = $logo_details->logo;	
		$get_logo = $logo_details->pCloudFileID; //pCloudFileID
		$output['pageTitle'] = 'Add Article';
		$output['logo'] = $get_logo;
		$output['banner'] = $this->frontend_model->getBanner(2);
		$output['bannerText'] = $this->frontend_model->getBannerText(2);
		$output['pageLinks'] = $this->frontend_model->getPageLinks(2);
		$output['topLinks'] = $this->frontend_model->getTopLinks();
		$output['youtube'] =  $this->frontend_model->getYoutube();
		$output['id'] = $id;
		$output['user_role'] = $user_role;

		return view('forums/add_article', $output);
	}

	public function addArticleDetails(Request $request)
	{
		$curTime = new \DateTime();
		$created_at = $curTime->format("Y-m-d H:i:s");

		$insert_article = DB::table('forum_article')->insert([

			'art_title'       => $request->post('article_title'),
			'art_desc'        => $request->post('art_desc'),
			'art_status'      => 1,
			'art_views'       => 0,
			'art_created_at'  => $created_at,
			'created_user_type' => $request->post('user_role'),
			'art_created_by'  => $request->post('mem_id'),



		]);

		return redirect('add-article');
	}



	public function single_forumDev(Request $request)
	{
		$id1 = '';
		$user_role = '';


		if (!empty(Session::get('memberId'))) {
			$user_role = '2';
			$id1 = Session::get('memberId');
		}

		if (!empty(Session::get('clientId'))) {
			$user_role = '3';
			$id1 = Session::get('clientId');
		}


		$logo_data = array(
			'logo_id' => 1,
		);

		$logo_details = DB::table('website_logo')
			->where($logo_data)
			->first();


		// 		$get_logo = $logo_details->logo;	
		$get_logo = $logo_details->pCloudFileID; //pCloudFileID
		$output['pageTitle'] = 'Single Forum View';
		$output['logo'] = $get_logo;
		$output['banner'] = $this->frontend_model->getBanner(2);
		$output['bannerText'] = $this->frontend_model->getBannerText(2);
		$output['pageLinks'] = $this->frontend_model->getPageLinks(2);
		$output['topLinks'] = $this->frontend_model->getTopLinks();
		$output['youtube'] =  $this->frontend_model->getYoutube();
		$output['id'] = $id1;
		//   		$output['art_id']=$id;
		$output['user_role'] = $user_role;


		return view('forums/single_forumDev', $output);
	}

	public function single_forum(Request $request, $id)
	{
		$id1 = '';
		$user_role = '';


		if (!empty(Session::get('memberId'))) {
			$user_role = '2';
			$id1 = Session::get('memberId');
		}

		if (!empty(Session::get('clientId'))) {
			$user_role = '3';
			$id1 = Session::get('clientId');
		}


		$logo_data = array(
			'logo_id' => 1,
		);

		$logo_details = DB::table('website_logo')
			->where($logo_data)
			->first();


		// 		$get_logo = $logo_details->logo;	
		$get_logo = $logo_details->pCloudFileID; //pCloudFileID
		$output['pageTitle'] = 'Add Comment';
		$output['logo'] = $get_logo;
		$output['banner'] = $this->frontend_model->getBanner(2);
		$output['bannerText'] = $this->frontend_model->getBannerText(2);
		$output['pageLinks'] = $this->frontend_model->getPageLinks(2);
		$output['topLinks'] = $this->frontend_model->getTopLinks();
		$output['youtube'] =  $this->frontend_model->getYoutube();
		$output['id'] = $id1;
		$output['art_id'] = $id;
		$output['user_role'] = $user_role;



		$fetch_view = DB::table('forum_article')->where('art_id', '=', $id)->get();



		foreach ($fetch_view as $key => $value) {
			$x = $value->art_views;
		}

		$view = $x + 1;

		$update_announ = DB::table('forum_article')->where('art_id', '=', $id)->update([
			'art_views' => $view,


		]);



		$get_query = DB::table('forum_article')->where('art_id', '=', $id)->get();

		$array = json_decode(json_encode($get_query), true);

		foreach ($array as $key => $value) {
			if ($value['created_user_type'] == '2') {
				$mem_name = DB::table('members')->where('id', '=', $value['art_created_by'])->select('fname', 'uname')->get();
				// print_r($mem_name);
				foreach ($mem_name as $key1 => $value1) {
					$array[$key]['fname'] = $value1->fname;
					$array[$key]['uname'] = $value1->uname;
				}
			}
			if ($value['created_user_type'] == '3') {
				$mem_name = DB::table('clients')->where('id', '=', $value['art_created_by'])->select('name', 'uname')->get();
				// print_r($mem_name);
				foreach ($mem_name as $key1 => $value1) {
					$array[$key]['fname'] = $value1->name;
					$array[$key]['uname'] = $value1->uname;
				}
			}
		}

		$arr = json_encode($array);

		// SECURITY FIX P0-CRITICAL: Convert to Query Builder to prevent SQL injection
	// $id comes from route parameter and was vulnerable to SQL injection
	$other_comment = DB::table('forum_article_comments')
		->where('art_id', '=', $id)
		->where('delete_status', '=', 0)
		->where('comment_status', '=', 1)
		->orderBy('created_at', 'asc')
		->get();
		$array1 = json_decode(json_encode($other_comment), true);

		foreach ($array1 as $key => $value) {
			if ($value['user_type'] == '2') {
				$mem_name = DB::table('members')->where('id', '=', $value['comment_posted_by'])->select('fname', 'uname', 'email')->get();
				// print_r($mem_name);
				foreach ($mem_name as $key1 => $value1) {
					$array1[$key]['fname'] = $value1->fname;
					$array1[$key]['uname'] = $value1->uname;
					$array1[$key]['email'] = $value1->email;
				}
			}
			if ($value['user_type'] == '3') {
				$mem_name = DB::table('clients')->where('id', '=', $value['comment_posted_by'])->select('name', 'uname', 'email')->get();
				// print_r($mem_name);
				foreach ($mem_name as $key1 => $value1) {
					$array1[$key]['fname'] = $value1->name;
					$array1[$key]['uname'] = $value1->uname;
					$array1[$key]['email'] = $value1->email;
				}
			}
		}

		if (!empty($id1)) {
			// SECURITY FIX P0-CRITICAL: Convert to Query Builder to prevent SQL injection
		// $id and $id1 are user-controlled and were vulnerable to SQL injection
		$like_fetch_user = DB::table('forum_article_likes')
			->where('art_id', '=', $id)
			->where('user_id', '=', $id1)
			->get();
			$liked_by_user = count($like_fetch_user);
			$output['liked_by_user'] = $liked_by_user;
		}

		$total_likes = DB::table('forum_article_likes')->where('art_id', '=', $id)->count();
		$output['total_likes'] = $total_likes;

		$comment_count = count($array1);
		$comments = json_encode($array1);


		$output['fetch_view'] = $arr;
		$output['comment_count'] = $comment_count;
		$output['comments'] = $comments;



		return view('forums/single_forum', $output);
	}

	public function add_comment(Request $request)
	{


		parse_str($_POST['data'], $search_array);

		$curTime = new \DateTime();
		$created_at = $curTime->format("Y-m-d H:i:s");

		$insert_comment = DB::table('forum_article_comments')->insert([

			'art_id'       => $search_array['art_id'],
			'comment_posted' => $search_array['comment_posted'],
			'comment_posted_by' => $search_array['user_id'],
			'user_type'    => $search_array['user_role'],
			'comment_status' => 1,
			'delete_status' => 0,
			'created_at'    => $created_at

		]);

		$comment_by = $search_array['user_id'];
		$user_type = $search_array['user_role'];
		$comment_posted = $search_array['comment_posted'];
		$art_id = $search_array['art_id'];

		$user_name = '';
		$user_email = '';

		if ($user_type == 2) {
			$mem_name = DB::table('members')->where('id', '=', $comment_by)->select('fname', 'uname', 'email')->get();

			foreach ($mem_name as $key1 => $value1) {
				$user_name = $value1->fname;
				//  $array1[$key]['uname']=$value1->uname;
				$user_email = $value1->email;
			}
		}

		if ($user_type == 3) {
			$mem_name = DB::table('clients')->where('id', '=', $comment_by)->select('name', 'uname', 'email')->get();

			foreach ($mem_name as $key1 => $value1) {
				$user_name = $value1->name;
				//  $array1[$key]['uname']=$value1->uname;
				$user_email = $value1->email;
			}
		}

		if ($comment_by > 0) {

			//  $email = urldecode($user_email);
			$email = 'gssgtech@yopmail.com'; // Comment or Remove it for move to production mode

			if (!empty($user_name)) {
				$name = urldecode($user_name);
			}

			$data = array('emailId' => $email, 'name' => $name, 'pwd' => $comment_posted, 'artid' => $art_id);
			Mail::send('mails.forums.postcomment', ['data' => $data], function ($message) use ($data) {
				$message->to($data['emailId']);
				$message->subject('User has posted a comment.');
				$message->from('business@digiwaxx.com', 'Digiwaxx');
			});
		}



		if ($insert_comment) {
			return response()->json('success');
		}
	}

	public function like_article(Request $request)
	{
		$art_id = $request->art_id;
		$user_id = $request->user_id;
		$user_role = $request->user_role;

		$insert_query = DB::table('forum_article_likes')->insert([
			'art_id' => $art_id,
			'user_id' => $user_id,
			'user_role' => $user_role

		]);

		$total_likes = DB::table('forum_article_likes')->where('art_id', '=', $art_id)->count();

		if ($insert_query) {
			return response()->json($total_likes);
		}
	}

	public function dislike_article(Request $request)
	{
		$art_id = $request->art_id;
		$user_id = $request->user_id;


		// SECURITY FIX P0-CRITICAL: Convert to Query Builder to prevent SQL injection
	// $art_id and $user_id come from request and were vulnerable to SQL injection
	$delete_query = DB::table('forum_article_likes')
		->where('art_id', '=', $art_id)
		->where('user_id', '=', $user_id)
		->delete();

		$total_likes = DB::table('forum_article_likes')->where('art_id', '=', $art_id)->count();
		if ($delete_query) {
			return response()->json($total_likes);
		}
	}



	// ----------------------------------------------------Forum ends here-------------------------------

	public function viewContactPage(Request $request)
	{
		$output = array();
		$logo_data = array(
			'logo_id' => 1,
		);

		$logo_details = DB::table('website_logo')
			->where($logo_data)
			->first();
		$from_email = '';
		// 		$get_logo = $logo_details->logo;	
		$get_logo = $logo_details->pCloudFileID; //pCloudFileID
		$output['pageTitle'] = 'Contact Us';
		$output['logo'] = $get_logo;

		if ($request->input('sendMessage')) {
			// reset password mail
			// 		 if($_SERVER['REMOTE_ADDR'] = '2401:4900:1c2a:5cc:85bf:3a91:264:40b1'){
			//             	 pArr($_POST);
			// echo $_POST['email'];
			// 		 die();
			// }


			$recaptcha = $_POST['g-recaptcha-response'];
			$res = $this->reCaptcha($recaptcha);
			$from_email = $_POST['email'];

			$email = 'business@digiwaxx.com';
			// 			$email = 'sgtech@yopmail.com';
			//$email = 'sgtechqa@gmail.com'; 

			// SECURITY FIX: Escape all user input to prevent XSS attacks
		$mssge = '<table width="600" border="0" align="center" cellpadding="0" cellspacing="0" class="two-left-inner">
            <tr>
              <td align="left" valign="top"><p style="font-family:Open Sans, sans-serif, Verdana; font-size:14px; font-weight:600; color:#fff; line-height: 25px; margin-bottom:0">Email:</p></td><td><p style="font-family:Open Sans, sans-serif, Verdana; font-size:14px; font-weight:400; color:#fff; line-height: 25px; margin-bottom:0">' . htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8') . '</p></td>
            </tr>
             <tr>
              <td align="left" valign="top"><p style="font-family:Open Sans, sans-serif, Verdana; font-size:14px; font-weight:600; color:#fff; line-height: 25px; margin-bottom:0">Subject:</p></td><td><p style="font-family:Open Sans, sans-serif, Verdana; font-size:14px; font-weight:400; color:#fff; line-height: 25px; margin-bottom:0">' . htmlspecialchars($_POST['subject'], ENT_QUOTES, 'UTF-8') . '</p></td>
            </tr>
             <tr>
              <td align="left" valign="top"><p style="font-family:Open Sans, sans-serif, Verdana; font-size:14px; font-weight:600; color:#fff; line-height: 25px; margin-bottom:0">Message:</p></td><td><p style="font-family:Open Sans, sans-serif, Verdana; font-size:14px; font-weight:400; color:#fff; line-height: 25px; margin-bottom:0">' . htmlspecialchars($_POST['message'], ENT_QUOTES, 'UTF-8') . '</h3></td>
            </tr>
          </table>';

			$data = array('emailId' => $email, 'message' => $mssge, 'from_email' => $from_email);
			//echo'<pre>';print_r($data);die();
			if ($res['success']) {
				Mail::send('mails.templates.contactEnquiryUpdated', ['data' => $data], function ($message) use ($data) {
					$message->to($data['emailId']);
					$message->subject('Enquiry from website');
					$message->from($data['from_email'], 'Digiwaxx');
				});
			} else {
				return redirect()->intended("Contactus?error");
				exit;
			}

			return redirect()->intended("Contactus?msgSent=1");
			exit;
		}
		if ($request->input('msgSent')) {
			$output['alert_class'] = 'success-msg';
			$output['alert_message'] = 'Message has been sent successfully!';
		} else if ($request->input('error')) {
			$output['alert_class'] = 'error-msg';

			$output['alert_message'] = 'Error occured, please try again!';
		}
		$output['bannerText'] = $this->frontend_model->getBannerText(6);
		$output['banner'] = $this->frontend_model->getBanner(6);
		$output['getInTouchText'] = $this->frontend_model->getContentUsingMeta(6, 'getintouch');
		return view('pages.ContactUs', $output);
	}
	public function reCaptcha($recaptcha)
	{
		// SECURITY FIX: Moved reCAPTCHA secret to environment variable
		// The previous hardcoded secret has been exposed in git history
		// and MUST be regenerated at Google Cloud Console
		$secret = env('RECAPTCHA_SECRET_KEY');

		if (empty($secret)) {
			\Log::error('reCAPTCHA secret key not configured in environment');
			return ['success' => false, 'error-codes' => ['missing-secret-key']];
		}

		$ip = $_SERVER['REMOTE_ADDR'];

		$postvars = array("secret" => $secret, "response" => $recaptcha, "remoteip" => $ip);
		$url = "https://www.google.com/recaptcha/api/siteverify";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postvars);
		$data = curl_exec($ch);
		curl_close($ch);

		return json_decode($data, true);
	}
	public function viewFaqPage(Request $request)
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
		$output['pageTitle'] = 'FAQs';
		$output['logo'] = $get_logo;

		$resFaqs = Faq::getFaqs();
		//pArr($resFaqs);die('--YSYS');
		$output['faqs'] = $resFaqs;
		return view('pages.Faqs', $output);
	}
	public function viewDigiwaxxRadioPage(Request $request)
	{
		$output = array();
		$output['bannerText'] = '<h1>&nbsp;</h1><h1>&nbsp;</h1><h1>Digiwaxx Radio &amp; Playlists</h1><p><span style="font-size:16px">Content Coming Soon!</span></p>';

		$logo_data = array(
			'logo_id' => 1,
		);

		$logo_details = DB::table('website_logo')
			->where($logo_data)
			->first();

		// 		$get_logo = $logo_details->logo;	
		$get_logo = $logo_details->pCloudFileID; //pCloudFileID
		$output['pageTitle'] = 'DigiWaxx Radio';
		$output['logo'] = $get_logo;
		$output['bannerText'] = $this->frontend_model->getBannerText(5);
		return view('pages.DigiwaxxRadio', $output);
	}
	public function viewWhatWeDoPage(Request $request)
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
		$output['pageTitle'] = 'What We Do';
		$output['logo'] = $get_logo;
		$output['content'] = $this->frontend_model->getPageText(10);
		return view('pages.WhatWeDo', $output);
	}
	public function viewWhyJoinPage(Request $request)
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
		$output['pageTitle'] = 'Why Join';
		$output['logo'] = $get_logo;
		$output['content'] = $this->frontend_model->getPageText(14);
		$output['banner'] = $this->frontend_model->getBanner(14);
		return view('pages.WhyJoin', $output);
	}
	public function viewPrivacyPolicyPage(Request $request)
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
		$output['pageTitle'] = 'Privacy Policy';
		$output['logo'] = $get_logo;
		$output['content'] = $this->frontend_model->getPageText(18);
		$output['banner'] = $this->frontend_model->getBanner(18);
		$output['bannerText'] = 'Digiwaxx Privacy Policy';
		return view('pages.PrivacyPolicy', $output);
	}

	public function viewPressPage(Request $request)
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
		$output['pageTitle'] = 'Press';
		$output['logo'] = $get_logo;
		$output['content'] = $this->frontend_model->getPageText(7);
		return view('pages.PressPage', $output);
	}
	public function viewWallOfScratchPage(Request $request)
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
		$output['pageTitle'] = 'Press';
		$output['logo'] = $get_logo;
		$output['content'] = $this->frontend_model->getPageText(9);
		return view('pages.WallOfScratch', $output);
	}
	public function viewSponsorAdvertisePage(Request $request)
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
		$output['pageTitle'] = 'Sponsor Advertise';
		$output['logo'] = $get_logo;
		$output['bannerText'] = $this->frontend_model->getBannerText(15);
		$output['banner'] = $this->frontend_model->getBanner(15);
		$output['content'] = $this->frontend_model->getPageText(15);
		if ($request->input('sendMessage')) {
			//$email = 'business@digiwaxx.com';

			$email = 'sgtech@yopmail.com';

			// SECURITY FIX: Escape all user input to prevent XSS attacks
		$mssge = '<h3><br>New <strong>Sponsorship and Advertising Inquiry</strong></h3>
            <table border="1" bordercolor="#000000" style="background-color: #ffffff; width: 100%; max-width: 600px; border-collapse: collapse;">
                <tbody>
                    <tr>
                    <td colspan="2" style="border: 1px solid #dddddd; text-align: center; padding: 12px;"><b>Inquiry Details</b></td>
                    </tr>
                    <tr>
                    <td style="border: 1px solid #dddddd;  text-align: left; padding: 8px; width: 180px" ><b>Email:</b></td>
                    <td style="border: 1px solid #dddddd;  text-align: left; padding: 8px;" >' . htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8') . '</td>
                    </tr>
                    <tr>
                    <td style="border: 1px solid #dddddd;  text-align: left; padding: 8px; width: 180px" ><b>Subject:</b></td>
                    <td style="border: 1px solid #dddddd;  text-align: left; padding: 8px;" >' . htmlspecialchars($_POST['subject'], ENT_QUOTES, 'UTF-8') . '</td>
                    </tr>
                    <tr>
                    <td style="border: 1px solid #dddddd;  text-align: left; padding: 8px; width: 180px" ><b>Message:</b></td>
                    <td style="border: 1px solid #dddddd;  text-align: left; padding: 8px;" >' . htmlspecialchars($_POST['message'], ENT_QUOTES, 'UTF-8') . '</td>
                    </tr>

                </tbody>
            </table>';

			$data = array('emailId' => $email, 'message' => $mssge);
			//echo'<pre>';print_r($data);die();
			Mail::send('mails.templates.contactEnquiry', ['data' => $data], function ($message) use ($data) {
				$message->to($data['emailId']);
				$message->subject('Enquiry from Sponsorship and Advertising');
				$message->from('business@digiwaxx.com', 'Digiwaxx');
			});

			/* $this->load->library('email');
            $this->email->from('info@projectwaxx.com', 'Digiwaxx');
            $this->email->to($email);
            $this->email->set_mailtype("html");
            $this->email->subject('Enquiry from Sponsorship and Advertising');

            
            // SECURITY FIX: Escape all user input to prevent XSS attacks
            $message=

            '<h3><br>New <strong>Sponsorship and Advertising Inquiry</strong></h3>
            <table border="1" bordercolor="#000000" style="background-color: #ffffff; width: 100%; max-width: 600px; border-collapse: collapse;">
                <tbody>
                    <tr>
                    <td colspan="2" style="border: 1px solid #dddddd; text-align: center; padding: 12px;"><b>Inquiry Details</b></td>
                    </tr>
                    <tr>
                    <td style="border: 1px solid #dddddd;  text-align: left; padding: 8px; width: 180px" ><b>Email:</b></td>
                    <td style="border: 1px solid #dddddd;  text-align: left; padding: 8px;" >'.htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8').'</td>
                    </tr>
                    <tr>
                    <td style="border: 1px solid #dddddd;  text-align: left; padding: 8px; width: 180px" ><b>Subject:</b></td>
                    <td style="border: 1px solid #dddddd;  text-align: left; padding: 8px;" >'.htmlspecialchars($_POST['subject'], ENT_QUOTES, 'UTF-8').'</td>
                    </tr>
                    <tr>
                    <td style="border: 1px solid #dddddd;  text-align: left; padding: 8px; width: 180px" ><b>Message:</b></td>
                    <td style="border: 1px solid #dddddd;  text-align: left; padding: 8px;" >'.htmlspecialchars($_POST['message'], ENT_QUOTES, 'UTF-8').'</td>
                    </tr>

                </tbody>
            </table>';


            $this->email->message($message);
			$this->email->send(); */

			return redirect()->intended('SponsorAdvertise?msgSent=1');
			exit;
		}

		if ($request->input('msgSent')) {
			$output['alert_class'] = 'success-msg';
			$output['alert_message'] = 'Message has been sent successfully!';
		} else if ($request->input('error')) {
			$output['alert_class'] = 'error-msg';
			$output['alert_message'] = 'Error occured, please try again!';
		}
		return view('pages.SponsorAdvertise', $output);
	}
	public function viewPromoteYourProjectPage(Request $request)
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
		$output['pageTitle'] = 'Promote Your Project';
		$output['logo'] = $get_logo;
		$output['banner'] = $this->frontend_model->getBanner(3);
		$output['bannerText'] = $this->frontend_model->getBannerText(3);
		$output['promote1'] = $this->frontend_model->getBannerText(8);
		$output['promote2'] = $this->frontend_model->getBannerText(9);
		$output['promote3'] = $this->frontend_model->getBannerText(10);
		return view('pages.PromoteYourProject', $output);
	}
	public function viewHelpPage(Request $request)
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
		$output['pageTitle'] = 'Help';
		$output['logo'] = $get_logo;
		$output['banner'] = $this->frontend_model->getBanner(17);
		$output['content'] = $this->frontend_model->getPageText(17);
		$output['bannerText'] = $this->frontend_model->getBannerText(17);
		return view('pages.Help', $output);
	}
	public function viewFreePromoPage(Request $request)
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
		$output['pageTitle'] = 'Free Promo';
		$output['logo'] = $get_logo;

		return view('pages.FreePromo', $output);
	}
	public function viewChartsPageBackUp(Request $request)
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
		$output['pageTitle'] = 'Charts';
		$output['logo'] = $get_logo;
		return view('pages.Charts', $output);
	}
	public function viewImDjPage(Request $request)
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
		$output['pageTitle'] = 'Im Dj';
		$output['logo'] = $get_logo;
		$output['pageLinks'] = $this->frontend_model->getPageLinks(16);
		return view('pages.ImDj', $output);
	}
	public function viewImArtistPage(Request $request)
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
		$output['pageTitle'] = 'Im Artist';
		$output['logo'] = $get_logo;
		//$output['pageLinks'] = $this->frontend_model->getPageLinks(16); 
		return view('pages.ImArtist', $output);
	}
	public function viewClientsPage(Request $request)
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
		$output['pageTitle'] = 'Clients';
		$output['logo'] = $get_logo;
		$output['content'] = $this->frontend_model->getPageText(8);
		return view('pages.ClientsPage', $output);
	}
	public function viewEventsPage(Request $request)
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
		$output['pageTitle'] = 'Events';
		$output['logo'] = $get_logo;
		$output['content'] = $this->frontend_model->getPageText(12);
		return view('pages.EventsPage', $output);
	}

	public function viewTestimonialsPage(Request $request)
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
		$output['pageTitle'] = 'Events';
		$output['logo'] = $get_logo;
		$output['content'] = $this->frontend_model->getPageText(13);
		return view('pages.TestimonialsPage', $output);
	}

	public function viewChartsPage(Request $request)
	{

		// ob_start();

		$output = array();
		$logo_data = array(
			'logo_id' => 1,
		);

		$logo_details = DB::table('website_logo')
			->where($logo_data)
			->first();

		// 		$get_logo = $logo_details->logo;	
		$get_logo = $logo_details->pCloudFileID; //pCloudFileID


		$output['pageTitle'] = 'Charts';
		$output['logo'] = $get_logo;

		// meta content
		$meta =  $this->frontend_model->getPageMeta_trm(4);

		$output['pageTitle'] = 'Digiwaxx';
		$output['meta_keywords'] = '';
		$output['meta_description'] = '';

		if ($meta['numRows'] > 0) {
			$output['pageTitle'] = $meta['data'][0]->meta_tittle;
			$output['meta_keywords'] = $meta['data'][0]->meta_keywords;
			$output['meta_description'] = $meta['data'][0]->meta_description;
		}


		date_default_timezone_set('America/Los_Angeles');

		$year = date('Y');

		$month = date('m');

		$date = date('d');

		$monday = date('Y-m-d', strtotime('monday this week'));

		$sunday = date('Y-m-d', strtotime('sunday this week'));


		// SECURITY NOTE MEDIUM: These WHERE clauses use PHP-generated date variables (not user input)
	// However, they are passed to model methods that use DB::select() with string concatenation
	// TODO: Refactor frontend_model methods to use Query Builder or parameterized queries
	$where1 = "where tracks.deleted = '0' AND tracks.status = 'publish' AND tracks.active = '1' AND track_member_downloads.downloadedDateTime > '" . $monday . "' AND track_member_downloads.downloadedDateTime < '" . $sunday . "' ";

		$where2 = "where tracks.deleted = '0' AND tracks.status = 'publish' AND tracks.active = '1' AND track_member_downloads.downloadedDateTime like '" . $year . '-' . $month . "%'";

		$where3 = "where tracks.deleted = '0' AND tracks.status = 'publish' AND tracks.active = '1' AND track_member_downloads.downloadedDateTime like '" . $year . "%'";

		$limit = 25;

		//$sort = 'tracks_mp3s.downloads desc';   
		$sort = 'downloads desc';

		$where4 = "where tracks.deleted = '0'  AND tracks.status = 'publish' AND tracks.active = '1' AND DATE_SUB(CURDATE(),INTERVAL 60 DAY) <= tracks.added";

		$sort4 = 'tracks.id desc';

		$limit4 = 25;

		// downloads pagination ajax request

		if ((isset($_GET['page']))  && ($_GET['page'] > 0) && (isset($_GET['type'])) && ($_GET['type'] > 0) && ($_GET['type'] < 4)) {

		// SECURITY FIX HIGH: Validate and sanitize user input to prevent SQL injection
		$type = (int) $_GET['type'];

			if ($type == 1) {

				$where = "where tracks.deleted = '0' AND tracks.status = 'publish' AND tracks.active = '1' AND track_member_downloads.downloadedDateTime > '" . $monday . "' AND track_member_downloads.downloadedDateTime < '" . $sunday . "' ";
			} else if ($type == 2) {

				$where = "where tracks.deleted = '0' AND tracks.status = 'publish' AND tracks.active = '1' AND track_member_downloads.downloadedDateTime like '" . $year . '-' . $month . "%'";
			} else if ($type == 3) {

				$where = "where tracks.deleted = '0' AND tracks.status = 'publish' AND tracks.active = '1' AND track_member_downloads.downloadedDateTime like '" . $year . "%'";
			}



			$sort = 'downloads desc';

			$start = ($_GET['page'] * $limit) - $limit;

			$num_records = $this->frontend_model->getNumTopDownloadTracks($where, $sort);



			$numPages = (int)($num_records / $limit);

			$reminder = ($num_records % $limit);





			if ($reminder > 0) {

				$numPages = $numPages + 1;
			}



			$currentPageNo = $_GET['page'];

			$downloads = $this->frontend_model->getTopDownloadChartTracks($where, $sort, $start, $limit);

			/*if($downloads['numRows']>0)

        {

        foreach($downloads['data'] as $track)

        {

            $trackInfo[$track->id] = $this->admin_model->getTrackPlays($track->id);

        }

        }

    */
			if ($numPages > 1) {



				$firstLink = $currentPageNo - 1;

				if ($_GET['page'] == 1) {

					$firstLink = $currentPageNo;
				}



				$lastLink = $currentPageNo + 1;

				if ($_GET['page'] == $numPages) {

					$lastLink = $currentPageNo;
				}



?>

				<div class="fby-blk clearfix">
					<div style="float:right;">

						<div class="pgm">

							<?php echo $start + 1; ?> - <?php echo $start + $limit; ?> OF <?php echo $num_records; ?>

						</div>



						<div class="tnav clearfix">

							<span><a href="javascript:void()" onclick="getCharts('<?php echo $firstLink; ?>','<?php echo $_GET['type']; ?>','<?php echo $_GET['divId']; ?>')"><i class="fa  fa-angle-double-left"></i></a></span>

							<span class="num"><?php echo $currentPageNo; ?></span>

							<span><a href="javascript:void()" onclick="getCharts('<?php echo $lastLink; ?>','<?php echo $_GET['type']; ?>','<?php echo $_GET['divId']; ?>')"><i class="fa  fa-angle-double-right"></i></a></span>

						</div>

					</div>
				</div>



			<?php }



			$i = $start + 1;
			foreach ($downloads['data'] as $track) {

				if (!empty($track->pCloudFileID)) {
					$trkId = (int)$track->pCloudFileID;

					$img = url('/pCloudImgDownload.php?fileID=' . $trkId);
				} else if (strlen($track->thumb) > 4) {
					if (file_exists(public_path('thumbs/' . $track->thumb))) {
						$img = asset('public/thumbs/' . $track->thumb);
					} else {
						$img = asset('public/images/noimage-avl.jpg');
					}
				} else if (strlen($track->imgpage) > 4) {
					if (file_exists(public_path('ImagesUp/' . $track->imgpage))) {
						$img = asset('public/ImagesUp/' . $track->imgpage);
					} else {
						$img = asset('public/images/noimage-avl.jpg');
					}
				} else {
					$img = asset('public/images/noimage-avl.jpg');
				}

			?>



				<div class="record">

					<div class="row">

						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 c1">

							<?php echo $i; 
							?>

						</div>



						<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 c2">

							<?php if (!empty(Session::get('memberId'))) { ?>
								<a href="<?php echo url('Member_track_review?tid=' . $track->id); ?>"><img class="img-responsive" src="<?php echo $img; ?>"></a>
							<?php } else { ?>
								<span><img class="img-responsive" src="<?php echo $img; ?>"></span>
							<?php } ?>

						</div>



						<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5 c3">

							<p><?php echo stripslashes(urldecode($track->title)); ?></p>

							<p class="alb"><?php echo stripslashes(urldecode($track->artist)); ?></p>

						</div>



						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 c4">

							<p>
								<?php if(isset($track->downloads)){
							 		// echo $track->downloads;
							 	 }
								?>									
								</p>

							<?php if (!empty(Session::get('clientId'))) { ?>

								<p class="dwd"><a href="<?php echo url('Member_track_review?tid=' . $track->id); ?>">

										<i class="fa fa-arrow-circle-o-down"></i></a></p>

							<?php } ?>

						</div>

					</div>

				</div><!-- eof record -->

			<?php $i++;
			}

			exit;
		}



		// newest pagination ajax request

		if ((isset($_GET['page']))  && ($_GET['page'] > 0) && (isset($_GET['type'])) && ($_GET['type'] == 4)) {





			$start4 = ($_GET['page'] * $limit4) - $limit4;

			$num_records4 = $this->frontend_model->getNumTracks_trm($where4, $sort4);



			$numPages4 = (int)($num_records4 / $limit4);

			$reminder4 = ($num_records4 % $limit4);





			if ($reminder4 > 0) {

				$numPages4 = $numPages4 + 1;
			}



			$currentPageNo4 = $_GET['page'];



			$newest = $this->frontend_model->getNewestTracks($where4, $sort4, $start4, $limit4);



			if ($numPages4 > 1) {



				$firstLink4 = $currentPageNo4 - 1;

				if ($_GET['page'] == 1) {

					$firstLink4 = $currentPageNo4;
				}



				$lastLink4 = $currentPageNo4 + 1;

				if ($_GET['page'] == $numPages4) {

					$lastLink4 = $currentPageNo4;
				}



			?>

				<div class="fby-blk clearfix">
					<div style="float:right;">

						<div class="pgm">

							<?php echo $start4 + 1; ?> - <?php echo $start4 + $limit4; ?> OF <?php echo $num_records4; ?>

						</div>



						<div class="tnav clearfix">

							<span><a href="javascript:void()" onclick="getCharts('<?php echo $firstLink4; ?>','4','nr-tab')"><i class="fa  fa-angle-double-left"></i></a></span>

							<span class="num"><?php echo $currentPageNo4; ?></span>

							<span><a href="javascript:void()" onclick="getCharts('<?php echo $lastLink4; ?>','4','nr-tab')"><i class="fa  fa-angle-double-right"></i></a></span>

						</div>

					</div>
				</div>



			<?php }



			$i = 1;
			foreach ($newest['data'] as $track) {
				if (!empty($track->pCloudFileID)) {
					$trkId = (int)$track->pCloudFileID;

					$img = url('/pCloudImgDownload.php?fileID=' . $trkId);
				} else if (strlen($track->thumb) > 4) {
					if (file_exists(public_path('thumbs/' . $track->thumb))) {
						$img = asset('public/thumbs/' . $track->thumb);
					} else {
						$img = asset('public/images/noimage-avl.jpg');
					}
				} else if (strlen($track->imgpage) > 4) {
					if (file_exists(public_path('ImagesUp/' . $track->imgpage))) {
						$img = asset('public/ImagesUp/' . $track->imgpage);
					} else {
						$img = asset('public/images/noimage-avl.jpg');
					}
				} else {
					$img = asset('public/images/noimage-avl.jpg');
				}

			?>



				<div class="record">

					<div class="row">
						<div class="col-sm-1 col-1 c1">
							<?php echo $i; ?>
						</div>
						<div class="col-lg-3 col-lg-offset-1 col-md-3 col-md-offset-1 col-sm-3 col-sm-offset-1 col-xs-3 col-xs-offset-1 c2">
							<?php if (!empty(Session::get('memberId'))) { ?>
								<a href="<?php echo url('Member_track_review?tid=' . $track->id); ?>"><img class="img-responsive" src="<?php echo $img; ?>"></a>
							<?php } else { ?>
								<span><img class="img-responsive" src="<?php echo $img; ?>"></span>
							<?php } ?>

						</div>



						<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8 c3">

							<p><?php echo stripslashes(urldecode($track->title)); ?></p>

							<p class="alb"><?php echo stripslashes(urldecode($track->artist)); ?></p>

						</div>

						<div class="col-sm-1 col-1 c4 pl-0">
							<?php if (isset($track->downloads)) { ?>
								<p><?php // echo $track->downloads; ?></p>
								<?php if (!empty(Session::get('memberId'))) { ?>
									<p class="dwd"><a href="<?php echo url('Member_track_review?tid=' . $track->id); ?>">
											<i class="fa fa-arrow-circle-o-down"></i></a></p>
								<?php } ?>
							<?php } ?>
						</div>

					</div>

				</div><!-- eof record -->

<?php $i++;
			}
			exit;
		}



		if (isset($_COOKIE['clientId'])) {

			$subscriptionInfo = $this->frontend_model->getSubscriptionStatus($_COOKIE['clientId']);

			if ($subscriptionInfo['numRows'] > 0) {

				$output['subscriptionStatus'] = 1;



				if ($subscriptionInfo['data'][0]->packageId == 1) {

					$output['package'] = 'BASIC SUBSCRIPTION';

					$output['displayDashboard'] = 0;
				} else if ($subscriptionInfo['data'][0]->packageId == 2) {

					$output['package'] = 'ADVANCED SUBSCRIPTION';

					$output['displayDashboard'] = 1;
				}
			} else {

				$output['subscriptionStatus'] = 0;

				$output['package'] = '';

				$output['displayDashboard'] = 0;
			}
		} else {

			$output['packageId'] = 0;

			$output['subscriptionStatus'] = 0;

			$output['package'] = '';

			$output['displayDashboard'] = 0;
		}





		$output['bannerText'] = $this->frontend_model->getBannerText_trm(4);
		// dd($output['bannerText']);




		$start = 0;



		// weekly top downloads



		$num_records1 = $this->frontend_model->getNumTopDownloadTracks($where1, $sort);



		$numPages1 = (int)($num_records1 / $limit);

		$reminder1 = ($num_records1 % $limit);



		if ($reminder1 > 0) {

			$numPages1 = $numPages1 + 1;
		}



		$output['numPages1'] = $numPages1;

		$output['start1'] = $start;

		$output['limit1'] = $limit;

		$output['num_records1'] = $num_records1;

		$output['currentPageNo1'] = 1;



		$output['weekDownloads'] = $this->frontend_model->getTopDownloadChartTracks($where1, $sort, $start, $limit);

		/*if($output['weekDownloads']['numRows']>0)

        {

        foreach($output['weekDownloads']['data'] as $track)

        {

            $output['trackInfo'][$track->id] = $this->admin_model->getTrackPlays($track->id);

        }

        }*/



		// monthly top downloads



		$num_records2 = $this->frontend_model->getNumTopDownloadTracks($where2, $sort);



		$numPages2 = (int)($num_records2 / $limit);

		$reminder2 = ($num_records2 % $limit);



		if ($reminder2 > 0) {

			$numPages2 = $numPages2 + 1;
		}



		$output['numPages2'] = $numPages2;

		$output['start2'] = $start;

		$output['limit2'] = $limit;

		$output['num_records2'] = $num_records2;

		$output['currentPageNo2'] = 1;



		$output['monthDownloads'] = $this->frontend_model->getTopDownloadChartTracks($where2, $sort, $start, $limit);

		/* if($output['monthDownloads']['numRows']>0)

        {

        foreach($output['monthDownloads']['data'] as $track)

        {

            $output['trackInfo'][$track->id] = $this->admin_model->getTrackPlays($track->id);

        }

        }

        */



		// yearly top downloads



		$num_records3 = $this->frontend_model->getNumTopDownloadTracks($where3, $sort);



		$numPages3 = (int)($num_records3 / $limit);

		$reminder3 = ($num_records3 % $limit);



		if ($reminder3 > 0) {

			$numPages3 = $numPages3 + 1;
		}



		$output['numPages3'] = $numPages3;

		$output['start3'] = $start;

		$output['limit3'] = $limit;

		$output['num_records3'] = $num_records3;

		$output['currentPageNo3'] = 1;



		$output['yearDownloads'] = $this->frontend_model->getTopDownloadChartTracks($where3, $sort, $start, $limit);

		/* if($output['yearDownloads']['numRows']>0)

        {

        foreach($output['yearDownloads']['data'] as $track)

        {

            $output['trackInfo'][$track->id] = $this->admin_model->getTrackPlays($track->id);

        }

        }*/







		// newest

		$start4 = 0;

		$num_records4 = $this->frontend_model->getNumTracks_trm($where4, $sort4);



		$numPages4 = (int)($num_records4 / $limit4);

		$reminder4 = ($num_records4 % $limit4);





		if ($reminder4 > 0) {

			$numPages4 = $numPages4 + 1;
		}



		$output['numPages4'] = $numPages4;

		$output['start4'] = $start4;

		$output['limit4'] = $limit4;

		$output['num_records4'] = $num_records4;

		$output['currentPageNo4'] = 1;





		$output['newest'] = $this->frontend_model->getNewestTracks($where4, $sort4, $start4, $limit4);
		$output['notifications'] = $this->frontend_model->getNotifications_trm();

		return view('pages.charts', $output);
	}


	public function subscription_expiry_confirmation_mail(Request $request)
	{

		//for dynamic
		// $current_date= date("Y-m-d");
		// $five_days = date('Y-m-d', strtotime("+5 day", strtotime($current_date)));

		//static check
		$five_days = '2022-04-16';


		$query = DB::table('package_user_details as a')->leftJoin('manage_packages as b', 'a.package_id', '=', 'b.id')->leftJoin('members as c', 'a.user_id', '=', 'c.id')->select('a.user_id', 'a.package_expiry_date', 'a.user_type', 'b.package_type', 'c.uname', 'c.email')->where('a.user_type', 1)->where('a.package_active', 1)->where('a.package_expiry_date', $five_days)->where('a.expiration_mail_status', 0)->where('c.email', 'aa%40yopmail.com')->get();


		foreach ($query as $value) {
			$emailofuser = urldecode($value->email);

			$nameofuser = urldecode($value->uname);
			$title = $value->package_type;
			$e_date = $value->package_expiry_date;
			$user_id = $value->user_id;


			if (!empty($emailofuser)) {
				$data = array('emailId' => $emailofuser, 'name' => $nameofuser, 'title' => $title, 'expiry' => $e_date);
				Mail::send('mails.package.expire_package', ['data' => $data], function ($message) use ($data) {
					$message->to($data['emailId']);
					$message->subject('Subscription about to expire');
					$message->from('business@digiwaxx.com', 'DigiWaxx');
				});

				$query2 = DB::table('package_user_details')->where('user_id', $user_id)->where('package_active', 1)->where('user_type', 1)->update(['expiration_mail_status' => 1]);
			}
		}
	}
}
