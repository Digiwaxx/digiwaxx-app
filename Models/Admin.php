<?php

namespace App\Models; 

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Auth;

class Admin extends Authenticatable
{
    use Notifiable;

    protected $guard = 'admin';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'user_role',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'remember_token',
    ];


    public function check_model_fun() {

        return true;

    }

    function getNumAdmins($where, $sort)

    {

        $queryRes = DB::select("select * from  admins $where order by $sort");

        $result = count($queryRes);

		return $result;
    }

    function getAdmins($where, $sort, $start, $limit)

    {

        $query = DB::select("select * from  admins $where order by $sort limit $start, $limit");

        $result['numRows'] = count($query);

        $result['data']  = $query;

        return  $result;
    }
	/**
     * Count Total of Company Logos.
     *
     * @GS
     */
	public function getNumLogos($where,$sort){ 
	
		if(empty($where)){
			$whereAnd = ' WHERE logos.company != "" ';
		}else{
			$whereAnd = $where.' AND logos.company != "" ';
		}

		$query = DB::select("select * from  logos $whereAnd order by $sort");
		return count($query);
	}
	/**
     * Get All Company Logos.
     *
     * @GS
     */
	public function getLogos($where,$sort,$start,$limit){

		if(empty($where)){
			$whereAnd = ' WHERE logos.company != "" ';
		}else{
// 			$whereAnd =$where.'AND logos.company != "" ';
         $whereAnd=$where;
		}
	//	echo ("select * from  logos $whereAnd order by $sort limit $start, $limit");die();
          //  echo $whereAnd ."-". $start."-".$limit ; die();
		$query = DB::select("select * from  logos $whereAnd order by $sort limit $start, $limit");
		$result['numRows'] = count($query);

        $result['data']  = $query;

        return  $result;
	}
	/**
     * Delete a Company Logo.
     *
     * @GS
     */
	public function deleteLogo($did){
		$query = DB::select("select img from logos where id = '". $did ."'");  
		$data  = $query;
		$imgUrl =  public_path("Logos/".$data[0]->img);
		//echo $imgUrl;die('--');
		//unlink($imgUrl);
		// $result = $this->db->query("delete from  logos where id = '$did'");
		$result = DB::table('logos')->where('id', $did)->delete();
		return $result;
	}

    public function getReviewMembers($whereConditions, $graphId = null)
    {
        // SECURITY FIX: Use query builder instead of raw SQL with string concatenation
        $query = DB::table('tracks_reviews')
            ->leftJoin('members', 'tracks_reviews.member', '=', 'members.id')
            ->select('tracks_reviews.id', 'members.fname', 'members.stagename');

        // Apply where conditions safely
        foreach ($whereConditions as $column => $value) {
            if ($column === 'whereheard_or' && is_array($value)) {
                // Handle OR condition for whereheard
                $query->where(function($q) use ($value) {
                    $q->where('tracks_reviews.whereheard', $value[0])
                      ->orWhere('tracks_reviews.whereheard', $value[1]);
                });
            } else {
                // Add table prefix for safety
                $fullColumn = strpos($column, '.') === false ? 'tracks_reviews.' . $column : $column;
                $query->where($fullColumn, $value);
            }
        }

        $data = $query->get()->toArray();
        $result['numRows']  = count($data);
        $result['data']  = $data;
        return  $result;
    }

    public function getReview($reviewId)
    {
        // SECURITY FIX: Use query builder instead of raw SQL with concatenation
        // Cast to int for extra safety
        $reviewId = (int) $reviewId;

        $query = DB::table('tracks_reviews')
            ->leftJoin('members', 'tracks_reviews.member', '=', 'members.id')
            ->leftJoin('tracks', 'tracks_reviews.track', '=', 'tracks.id')
            ->where('tracks_reviews.id', $reviewId)
            ->get()
            ->toArray();

        $result['numRows']  = count($query);
        $result['data']  = $query;
        return  $result;
    }
	
	/**
     * Unlink a Company Logo.
     *
     * @GS
     */
	
	public function unlinkCompanyLogo($did){		
		$query = DB::select("select img from logos where id = '". $did ."'");  
		$data  = $query;
		if(!empty($data[0]->img)){
		$imgUrl =  public_path("Logos/".$data[0]->img);		
		@unlink($imgUrl);
		}		
		return $did;
	}
	/**
     * Update a Company Logo.
     *
     * @GS
     */
	public function updateLogo($data,$logoId,$loggedUserId){		
		
		$updateData = array(
			'company' => $data['company'],
			'url' => urlencode($data['link']),
			'edited' => NOW(),
			'editedby' => $loggedUserId
		);
		
		$resQry = DB::table('logos')
				->where('id', $logoId)  
				->limit(1)  // optional - to ensure only one record is updated.
				->update($updateData);  // update the record in the DB.
		if($resQry){
			return $logoId;
		}else{
			return '0';
		}
	}
	/**
     * Update Website Logo.
     *
     * @GS
     */
	public function updateWebsiteLogo($logo, $pcloudFileId, $parentfolderid){
		$updateData = array(
			'logo' => $logo,
			'pCloudFileID'=> $pcloudFileId,
			'pCloudParentFolderID'=> $parentfolderid
		);
		$resQry = DB::table('website_logo')
				->where('logo_id', 1)
				->limit(1)  // optional - to ensure only one record is updated.
				->update($updateData);  // update the record in the DB.
		if($resQry){
			return 1;
		}else{
			return '0';
		}
	}
	
   /**
     * Update WebPage Banner
     *
     * @GS
     */
	public function updateWebPageBanner($banner,$page_id, $pcloudFileId=NULL, $parentfolderid=NULL){
		$updateData = array(
			'banner_image' => $banner,
			'pCloudFileID'=> $pcloudFileId,
			'pCloudParentFolderID'=> $parentfolderid			
		);
		
		$resQry = DB::table('dynamic_pages')
				->where('page_id', $page_id)  
				->limit(1)  // optional - to ensure only one record is updated.
				->update($updateData);  // update the record in the DB.
		if($resQry){
			return 1;
		}else{
			return '0';
		}
	}
	/**
     * Update Company Logo Image based on LogoID.
     *
     * @GS
     */
	public function addLogoImage($logoId,$image){
		$updateData = array(
			'img' => $image
		);
		$resQry = DB::table('logos')
				->where('id', $logoId)  
				->limit(1)
				->update($updateData);
		return  $resQry;
	}
	
	/**
     * Add New Company Logo.
     *
     * @GS
     */
	public function addNewLogo($data,$loggedUserId){
		$insertData = array(
			'company' => $data['company'],
			'url' => urlencode($data['link']),
			'added' => date('Y-m-d H:i:s'),
			'addedby' => $loggedUserId
		);
		
		$insertId = DB::table('logos')->insertGetId($insertData);
		
		return  $insertId;
	}
	
	/**
     * Get Genres Total Count.
     *
     * @GS
     */
	public function getNumGenres($where,$sort){
		$queryRes = DB::select("select * from  genres $where order by $sort");

        $result = count($queryRes);

		return $result;
	}
	/**
     * Get Genres for Listing.
     *
     * @GS
     */
	public function getGenres($where,$sort,$start,$limit){
		$queryRes = DB::select("select * from  genres $where order by $sort limit $start, $limit");
		
        $result['numRows'] = count($queryRes);

        $result['data']  = $queryRes;

        return  $result;
	}
	/**
     * Get Sub-Genres Using Genre Id.
     *
     * @GS
     */
	public function getSubGenres($genreId){
		$queryRes = DB::select("select subGenreId, subGenre from  genres_sub where genreId = '". $genreId ."' order by subGenre asc");
		
        $result['numRows'] = count($queryRes);

        $result['data']  = $queryRes;

        return  $result;
	}
	
	/**
     * Add New Genre
     *
     * @GS
     */
	
	public function addGenre($genre){
		$insertData = array(
			'genre' => $genre						
		);		
		$insertId = DB::table('genres')->insertGetId($insertData);		
		return  $insertId;
	}
	
	/**
     * Add Sub-Genre to a Genre
     *
     * @GS
     */
	
	public function addSubGenre($genreId,$subGenre){
		$insertData = array(
			'genreId' => $genreId,
			'subGenre' => $subGenre			
		);		
		$insertId = DB::table('genres_sub')->insertGetId($insertData);		
		return  $insertId;
	}

	/**
     * Update Genre
     *
     * @GS
     */
	
	public function updateGenre($genre,$id){
		$updateData = array(
			'genre' => $genre
		);
		
		$resQry = DB::table('genres')
				->where('genreId', $id)  
				->limit(1)  // optional - to ensure only one record is updated.
				->update($updateData);  // update the record in the DB.
		if($resQry){
			return $id;
		}else{
			return '0';
		}
	}
	/**
     * Update SubGenre
     *
     * @GS
     */
	
	public function updateSubGenre($subGenreId,$subGenre){
		$updateData = array(
			'subGenre' => $subGenre
		);
		
		$resQry = DB::table('genres_sub')
				->where('subGenreId', $subGenreId)  
				->limit(1)  // optional - to ensure only one record is updated.
				->update($updateData);  // update the record in the DB.
		if($resQry){
			return $subGenreId;
		}else{
			return '0';
		}
	}
	/**
     * Delete a Genre.
     *
     * @GS
     */
	public function deleteGenre($did){
		$result = DB::table('genres')->where('genreId', $did)->delete();
		return $result;
	}
	/**
     * Delete a Sub-Genre.
     *
     * @GS
     */
	public function deleteSubGenre($did){
		$result = DB::table('genres_sub')->where('genreId', $did)->delete();
		return $result;
	}
	/**
     * Get Total Count Of Dj Tools.
     *
     * @GS
     */	
	public function getNumTools($where,$sort){
		$queryRes = DB::select("select tool_tracks.tool_track_id from tool_tracks LEFT JOIN admins on tool_tracks.added_by = admins.id $where order by $sort");

        $result = count($queryRes);

		return $result;
	}
	/**
     * Get Admin Dj Tools.
     *
     * @GS
     */	
	public function getTools($where,$sort,$start,$limit){
		$queryRes = DB::select("select tool_tracks.tool_track_id, tool_tracks.tool_track_tittle, tool_tracks.added_on, admins.name from  tool_tracks 

		 LEFT JOIN admins on tool_tracks.added_by = admins.id

		 $where  order by $sort limit $start, $limit");

        $result['numRows'] = count($queryRes);
		
		$result['data'] = $queryRes;

		return $result;
	}
	
	/**
     * Delete Dj Tools.
     *
     * @GS
     */
	public function deleteTool($did){
		$result = DB::table('tool_track_files')->where('tool_track_id', $did)->delete();
		$result = DB::table('tool_tracks')->where('tool_track_id', $did)->delete();
		return $result;
	}
	/**
     * Get Dj Tool.
     *
     * @GS
     */	
	public function getTool($tool_id){
		$queryRes = DB::select("select tool_tracks.tool_track_id, tool_tracks.tool_track_tittle, tool_tracks.added_on, admins.name from  tool_tracks 

		 left join admins on tool_tracks.added_by = admins.id

		 where tool_tracks.tool_track_id = '". $tool_id ."'");
		
        $result['numRows'] = count($queryRes);
		
		$result['data'] = $queryRes;

		return $result;		
	}
	
	/**
     * Get Dj Tool MP3s.
     *
     * @GS
     */	
	public function getMp3($tool_id){
		$queryRes = DB::select("select * from  tool_track_files where tool_track_id = '". $tool_id ."'");
		
        $result['numRows'] = count($queryRes);
		
		$result['data'] = $queryRes;

		return $result;		
	}
	/**
     * Update Dj Tool Meta Info.
     *
     * @GS
     */		
	public function updateTool($tittle,$tool_id){ 
		$updateData = array(
			'tool_track_tittle' => $tittle
		);
		
		$resQry = DB::table('tool_tracks')
				->where('tool_track_id', $tool_id)  
				->limit(1)  // optional - to ensure only one record is updated.
				->update($updateData);  // update the record in the DB.

		return $tool_id;
		
	}
	/**
     * Update/Add MP3s to Dj Tool.
     *
     * @GS
     */		
	public function addMp3($fileName,$lastId){
		$insData = array(
			'tool_track_id' => $lastId,
			'track_file' => $fileName
		);
		$insertId = DB::table('tool_track_files')->insertGetId($insData);
		return  $insertId;
		/* $query = DB::select("select count(*) as Total FROM tool_track_files where tool_track_id = '". $lastId ."'");  
		if($query[0]->Total > 0){
			
		$resQry = DB::table('tool_track_files')
				->where('tool_track_id', $lastId)  
				->limit(1)  // optional - to ensure only one record is updated.
				->update($insData);  // update the record in the DB.	
			return $lastId;		
		}else{
			$insertId = DB::table('tool_track_files')->insertGetId($insData);
			return  $insertId;
		} */		
	}
	
	/**
     * Unlink Dj Tool File if Exists
     *
     * @GS
     */
	
	public function unlinkDjToolFile($tool_id){		
		$query = DB::select("select * from  tool_track_files where tool_track_id = '". $tool_id ."'");  
		$data  = $query;
		if(!empty($data[0]->track_file)){
			$trkFileUrl =  public_path("tools/".$data[0]->track_file);		
			@unlink($trkFileUrl);
		}		
		return $tool_id;
	}
	/**
     * Add Dj Tool.
     *
     * @GS
     */	
	public function addTool($data, $addedBy){
		//extract($data);
		$insData = array(
			'tool_track_tittle' => $data['tittle'],
			'added_by' => $addedBy,
			'added_on' => date('Y-m-d H:i:s')
		);
		$insertId = DB::table('tool_tracks')->insertGetId($insData);
		return $insertId;		
	}
	/**
     * Count Total FAQs
     *
     * @GS
     */
	 
	public function getNumFaqs($where,$sort){
		$queryRes = DB::select("select * from  faqs $where order by $sort");

        $result = count($queryRes);

		return $result;
	}

	/**
     * GET FAQs
     *
     * @GS
     */
	 
	public function getFaqs($where,$sort,$start,$limit){
		$queryRes = DB::select("select * from  faqs left join admins on faqs.posted_by = admins.id $where order by $sort limit $start, $limit");

        $result['numRows'] = count($queryRes);
		
		$result['data'] = $queryRes;

		return $result;
	}
	/**
     * Update FAQ
     *
     * @GS
     */	
	public function updateFaq($data,$fid){		
		//extract($data);
		
		$updateData = array(
			'question' => addslashes($data['question']),
			'answer' => addslashes($data['answer']),
			'status' => addslashes($data['status'])
		);
		
		$resQry = DB::table('faqs')
				->where('faq_id', $fid)  
				->limit(1)  // optional - to ensure only one record is updated.
				->update($updateData);  // update the record in the DB.

		return $fid;
	}
	/**
     * Delete Faqs.
     *
     * @GS
     */
	public function deleteFaq($id){
		$result = DB::table('faqs')->where('faq_id', $id)->delete();
		return $result;
	}
	
	/**
     * Add Faq.
     *
     * @GS
     */
	public function addFaq($data, $addedBy){
		//extract($data);
		$insData = array(
			'question' => addslashes($data['question']),
			'answer' => addslashes($data['answer']),
			'status' => 1,
			'posted_date_time' => date('Y-m-d H:i:s'),
			'posted_by' => $addedBy
		);
		$insertId = DB::table('faqs')->insertGetId($insData);
		return $insertId;
	}
	/**
     * Get Number/Count Mails.
     *
     * @GS
     */	
	public function getNumMails($where,$sort){
		$queryRes = DB::select("select * from  mailouts $where order by $sort");

        $result = count($queryRes);

		return $result;		
	}
	/**
     * Get All Mails.
     *
     * @GS
     */	
	public function getMails($where,$sort,$start,$limit){
		$queryRes = DB::select("select * from  mailouts $where order by $sort limit $start, $limit");

        $result['numRows'] = count($queryRes);
		
		$result['data'] = $queryRes;
		
		return $result;		
	}
	/**
     * Get Mail Data.
     *
     * @GS
     */	
	public function getMail($mailId){
		
		$queryRes = DB::select("select * from  mailouts where id = '". $mailId ."'");

        $result['numRows'] = count($queryRes);
		
		$result['data'] = $queryRes;
		
		$query1 = DB::select("select * from  mails where mailout = '". $mailId ."'");

        $result['mailsSent'] = count($query1);
		
		$query2 = DB::select("select title from tracks where id = '". $result['data'][0]->track ."'");  

		$result['track']  = $query2;
		
		$query3 = DB::select("select * from  mails where mailout = '". $mailId ."' AND received!='0000-00-00 00:00:00'");  

		$result['numReceived']  = $query3;
		
		//echo'<pre>';print_r($result);die('--YSYS');
		return $result;
	}
	
  /**
	* Get Track Info in Send Mail View.
	*
	* @GS
	*/	
	public function getTrackInfo($trackId){
		
	   $queryRes = DB::select("select * from  tracks  where id = '". $trackId ."'");
		
	   $result['numRows'] = count($queryRes);

	   $result['data']  = $queryRes;

	   return $result;   		
	}
  /**
	* Get Track Versions in Send Mail View.
	*
	* @GS
	*/	
	public function getTrackVersions($trackId){
		
	   $query = DB::select("select * from  tracks_mp3s  where track = '". $trackId ."'"); 
		
	   $result['numRows'] = count($query);

	   $result['data']  = $query;

	   return $result; 
	}
  /**
	* Get Track Logos in Send Mail View.
	*
	* @GS
	*/	
	public function getTrackLogos($logoId){

	   // SECURITY FIX: Use Query Builder to prevent SQL injection
	   $query = DB::table('logos')->where('id', $logoId)->get()->toArray();

	   $result['numRows'] = count($query);

	   $result['data']  = $query;

	   return $result; 
	}
  /**
	* Get Template in Send Mail View.
	*
	* @GS
	*/	
	public function getTemplate($templateId){
		
	   $query = DB::select("select * from  templates  where id = '". $templateId ."'"); 

	   $result  = $query;

	   return $result; 
	}
  /**
	* Get Track Logos in Send Mail View.
	*
	* @GS
	*/	
	public function getTrackContacts($trackId){

	   // SECURITY FIX: Use Query Builder to prevent SQL injection
	   $query = DB::table('tracks_contacts')->where('track', $trackId)->get()->toArray();

	   $result['numRows'] = count($query);

	   $result['data']  = $query;

	   return $result; 
	}
  /**
	* Get Members in Send Mail View.
	*
	* @GS
	*/	
	public function getMembers($where){
		
	   $query = DB::select("SELECT * FROM members $where limit 0, 20"); 
		
	   $result['numRows'] = count($query);

	   $result['data']  = $query;

	   return $result; 
	}
  /**
	* Insert Mail Data in DB for Send Mail View.
	*
	* @GS
	*/	
	public function setMail($subject,$type,$message,$track,$template){
		$insData = array(
			'subject' => urlencode($subject),
			'started' => date('Y-m-d H:i:s'),
			'types' => $type,
			'message' => urlencode($message),
			'track' => $track,
			'template' => $template
		);
		$insertId = DB::table('mailouts')->insertGetId($insData);
		
		return $insertId;
	}
  /**
	* Insert Mail Data After send Mail in DB for Send Mail View.
	*
	* @GS
	*/	
	public function mailSend($mailId,$memberId){
		$insData = array(
			'mailout' => $mailId,
			'member' => $memberId,
			'sent' => date('Y-m-d H:i:s'),
			'received' => date('Y-m-d H:i:s')
		);
		$insertId = DB::table('mails')->insertGetId($insData);
		
		return $insertId;
	}
  /**
	* Close Mail for Send Mail View.
	*
	* @GS
	*/	
	public function closeMail($mailId){
		
		$query = DB::select("SELECT id FROM mails where mailout = '". $mailId ."'");
		
		$numRows  = count($query);
		
		$updateDta = array(
			'ended' => date('Y-m-d H:i:s'),
			'nummails' => $numRows,
		);
		
		$resQry = DB::table('mailouts')
				->where('id', $mailId)  
				->limit(1)  // optional - to ensure only one record is updated.
				->update($updateDta);  // update the record in the DB.
		return $mailId;
		
	}
  /**
	* Get Search Tracks in Send Mail View.
	*
	* @GS
	*/	
	
	public function getSearchTracks($where){

	// echo "select * from tracks $where  order by title asc";

	   $queryRes = DB::select("select id, title from tracks $where  order by title asc limit 80");  

	   $result['numRows'] = count($queryRes);

	   $result['data']  = $queryRes;

	   return $result;   

	 }
  /**
	* Get Tracks in Send Mail View.
	*
	* @GS
	*/	 
	public function getTracks(){

	// echo "select * from tracks $where  order by title asc";

	   $queryRes = DB::select("select * from tracks order by artist desc limit 1, 50");  

	   $result['numRows'] = count($queryRes);

	   $result['data']  = $queryRes;

	   return $result;   

	 }
  /**
	* Get Templates in Send Mail View.
	*
	* @GS
	*/	 
	 public function getTemplates(){
		 
	   $queryRes = DB::select("select * from templates WHERE id=1"); 
		
	   $result = $queryRes;

	   return $result; 
	 }
  /**
	* Get Count of Subscribers.
	*
	* @GS
	*/	 	 
	 public function getNumSubscribers($where,$sort){
		 $queryRes = DB::select("select * from  subscribers $where order by $sort");
		  $result = count($queryRes);
		  return $result;
	 }
  /**
	* Get Count of Newsletters.
	*
	* @GS
	*/	 	 
	 public function getNumNewsletters($where,$sort){
		 $queryRes = DB::select("select * from  newsletter $where ORDER BY $sort");
		  $result = count($queryRes);
		  return $result;
	 }
	 
  /**
	* Get All Subscribers.
	*
	* @GS
	*/	 	 
	 public function getSubscribers($where,$sort,$start,$limit){

	   $queryRes = DB::select("select * from  subscribers $where order by $sort  limit $start, $limit");  

	   $result['numRows'] = count($queryRes);

	   $result['data']  = $queryRes;

	   return $result;   

	 }
  /**
	* Get All Newsletters.
	*
	* @GS
	*/	 	 
	 public function getNewsletters($where,$sort,$start,$limit){

	   $queryRes = DB::select("select * from  newsletter $where ORDER BY $sort limit $start, $limit");  

	   $result['numRows'] = count($queryRes);

	   $result['data']  = $queryRes;

	   return $result;   

	 }
 /**
	* Get Newsletter Subscribers
	*
	* @GS
	*/	
	 public function getNewsletterSubscribers($where,$sort){
		 
		$queryRes = DB::select("select subscribers.email from  newsletter_subscribers

  left join subscribers on newsletter_subscribers.subscriber_id = subscribers.subscriberId

  $where ORDER BY $sort");  

	   $result['numRows'] = count($queryRes);

	   $result['data']  = $queryRes;

	   return $result; 
	 }
   /**
	* Delete Newsletter
	*
	* @GS
	*/	 
	 public function deleteNewsletter($newsletter_id){
		$result = DB::table('newsletter')->where('newsletter_id', $newsletter_id)->delete();
		return $result; 
	 }
   /**
	* SearchSubscribers
	*
	* @GS
	*/	 
	 public function getSearchSubscribers($where,$sort){
		$queryRes = DB::select("select * from  subscribers $where $sort");  

	   $result['numRows'] = count($queryRes);

	   $result['data']  = $queryRes;

	   return $result; 
	 }
   /**
	* set Newsletter Data
	*
	* @GS
	*/	 
	 public function setNewsletter($subject,$message,$type){
		 
		$insertData = array(
			'subject' => $subject,
			'message' => addslashes($message),
			'type_id' => $type,
			'date_time' => date('Y-m-d H:i:s')
		);
		$insertId = DB::table('newsletter')->insertGetId($insertData);
		
		return $insertId;
	 }
	 
  /**
	* Save Newsletter Data
	*
	* @GS
	*/	 
	 public function saveSubcriber($newsletter_id,$subscriber_id){
		 
		$insertData = array(
			'newsletter_id' => $newsletter_id,
			'subscriber_id' => $subscriber_id			
		);
		$insertId = DB::table('newsletter_subscribers')->insertGetId($insertData);
		
		return $insertId;
	 }
  /**
	* Delete Subscriber
	*
	* @GS
	*/	 	 
	 public function deleteSubscriber($subscriberId){
		$result = DB::table('subscribers')->where('subscriberId', $subscriberId)->delete();
		return $result; 
	 }
	
	/**
     * Get Count of Tracks.
     *
     * @GS
     */		
	public function getStaffSelectedNumTracks($where,$sort){
		
		$queryRes = DB::select("select * from  staff_selection left join tracks on staff_selection.trackId = tracks.id  $where order by $sort");

        $result = count($queryRes);

		return $result;			
	}
	/**
     * Get All Tracks.
     *
     * @GS
     */		
	public function getStaffSelectedTracks($where,$sort,$start,$limit){
		
		$queryRes = DB::select("select * from  staff_selection left join tracks on staff_selection.trackId = tracks.id $where order by $sort  limit $start, $limit");

        $result['numRows'] = count($queryRes);
		
		$result['data'] = $queryRes;
		
		return $result;		
	}
	/**
     * Delete Staff Track.
     *
     * @GS
     */			
	public function deleteStaffTrack($did){
		
		$result = DB::table('staff_selection')->where('staffTrackId', $did)->delete();
		
		return $result;
	}
	/**
     * Get Count of Countries.
     *
     * @GS
     */	
	public function getNumCountries($where,$sort){
		
		$queryRes = DB::select("select * from  country left join continents on country.continentId = continents.continentId $where order by $sort");

        $resultCont = count($queryRes);
		
		return $resultCont;			
	}
	/**
     * Get All Countries.
     *
     * @GS
     */	
	public function getCountries($where,$sort,$start,$limit){
		
		$queryRes = DB::select("select * from  country left join continents on country.continentId = continents.continentId $where order by $sort limit $start, $limit");

        $result['numRows'] = count($queryRes);
		
		$result['data'] = $queryRes;
		
		return $result;			
	}
	/**
     * Get All Continents.
     *
     * @GS
     */		
	public function getContinents(){
		
		$queryRes = DB::select("select * from continents order by continent asc");
		return $queryRes;
		
	}
	/**
     * Check If Country Exists.
     *
     * @GS
     */	
	public function checkIfCountryExists($data){
		
		extract($data);
		$contint = strtolower(trim($continent));
		$contryCode = strtolower(trim($country_code));
		$contry = strtolower(trim($country));		
		$queryRes = DB::select("select * from  country WHERE LOWER(`continentId`)='$contint' AND LOWER(`abbr`)='$contryCode' AND LOWER(`country`)='$contry'");
		$result = count($queryRes);
		return $result;
	}
	
	/**
     * Add New Country.
     *
     * @GS
     */		
	public function addCountry($data){
		
		extract($data);
		
		$insertData = array(
			'continentId' => $continent,
			'abbr' => $country_code,
			'country' => $country
		);
		
		$insertId = DB::table('country')->insertGetId($insertData);
		
		return $insertId;		
	}
	/**
     * Update Country.
     *
     * @GS
     */		
	public function updateCountry($data){
		
		extract($data);
		
		$updateDta = array(
			'continentId' => $continent,
			'abbr' => $country_code,
			'country' => $country
		);
		
		$resQry = DB::table('country')
			->where('countryId', $countryId)  
			->limit(1)  // optional - to ensure only one record is updated.
			->update($updateDta);  // update the record in the DB.
		
		$result['result'] = 1;
		
		$query1 = DB::select("select continent from continents where continentId = '$continent'");
		
		$row = $query1;
		
		$result['continent'] = $row[0]->continent;
		
		return $result;
		/* pArr($result);die(); */
		
	}
	/**
     * Delete Country.
     *
     * @GS
     */	
	public function deleteCountry($countryId){
		
		$result = DB::table('country')->where('countryId', $countryId)->delete();
		
		return $result;		
	}
	/**
     * Get Num Count of States.
     *
     * @GS
     */	
	public function getNumStates($where,$sort){
		
		$queryRes = DB::select("select states.stateId from  states left join country on states.countryId = country.countryId left join continents on country.continentId = continents.continentId $where order by $sort");

        $resultCont = count($queryRes);
		
		return $resultCont;		
	}
	
	/**
     * Get All States.
     *
     * @GS
     */	
	public function getStates($where,$sort,$start,$limit){
		
		$queryRes = DB::select("select states.stateId, states.name, states.countryId, country.country, continents.continentId, continents.continent from  states

		 left join country on states.countryId = country.countryId

		 left join continents on country.continentId = continents.continentId

		 $where order by $sort limit $start, $limit");

        $result['numRows'] = count($queryRes);
		
		$result['data']  = $queryRes;
		
		return $result;		
	}
	/**
     * Get Selected Countries.
     *
     * @GS
     */	
	public function getSelectedCountries($continentId){
		
		$queryRes = DB::select("select countryId, country from  country where continentId = '". $continentId ."'");

        $result['numRows'] = count($queryRes);
		
		$result['data']  = $queryRes;
		
		return $result;			
	}
	
	/**
     * Add New State.
     *
     * @GS
     */		
	public function addState($data){
		
		extract($data);
		
		$queryRes = DB::select("select * from  states where name = '$state'");

        $result = count($queryRes);
		
		if($result<1){
		
			$insertData = array(
				'countryId' => $countryId,
				'name' => $state
			);
			
			$insertId = DB::table('states')->insertGetId($insertData);
			
			$result = $insertId;
		}
		
		return $result;		
	}
	/**
     * Delete State.
     *
     * @GS
     */	
	public function deleteState($stateId){
		
		$result = DB::table('states')->where('stateId', $stateId)->delete();
		
		return $result;				
	}
	/**
     * Delete Mails.
     *
     * @GS
     */	
	public function deleteMailsFromAdmin($mailId){
		
		$result = DB::table('mailouts')->where('id', $mailId)->delete();
		
		return $result;				
	}
	/**
     * Update State.
     *
     * @GS
     */		
	public function updateState($data){
		
		extract($data);
		
		$updateDta = array(
			'countryId' => $countryId,
			'name' => $state
		);

		$resQry = DB::table('states')
			->where('stateId', $stateId)  
			->limit(1)  // optional - to ensure only one record is updated.
			->update($updateDta);  // update the record in the DB.
		$result['result'] = $stateId;	
		$result['continent'] = $continentId;	
		return $result;
	}
	
   /**
     * Get YouTube
     *
     * @GS
     */		
	public function getYoutube(){
		$queryRes = DB::select("select youtube from  youtube ");
// 		where youtube_id = '1'
		return $queryRes;
	}
   /**
     * Update YouTube
     *
     * @GS
     */	
	public function updateYoutube($youtube){
		$updateDta = array(
			'youtube' => $youtube
		);
		
		$resQry = DB::table('youtube')
			->where('youtube_id', 1)  
			->limit(1)  // optional - to ensure only one record is updated.
			->update($updateDta);  // update the record in the DB.
		return 1;
	}
	
		public function updateYoutube2($youtube){
		$updateDta = array(
			'youtube' => $youtube
		);
		
		$resQry = DB::table('youtube')
			->where('youtube_id', 2)  
			->limit(1)  // optional - to ensure only one record is updated.
			->update($updateDta);  // update the record in the DB.
		return 1;
	}
	
   /**
     * Get Page Text
     *
     * @GS
     */	
	public function getPageText($id)
	{

	 $query = DB::select("select * from  dynamic_pages where  page_id = '". $id ."'");   
	 $result  = $query;
	 return  $result;
	}
   /**
     * Update Page Text
     *
     * @GS
     */	
	public function updatePageText($text,$id){
		
		$updateDta = array(
			'page_content' => addslashes($text)
		);
		
		$resQry1 = DB::table('dynamic_pages')
			->where('page_id', $id)  
			->limit(1) 
			->update($updateDta);
			
		return 1;
	}
   /**
     * Get Banner Text
     *
     * @GS
     */	
	public function getBannerText($id){
	 $query = DB::select("select * from  banners where  pageId = '". $id ."'");   
	 $result  = $query;
	 return  $result;		
	}
   /**
     * Get Value Using Meta
     *
     * @GS
     */
	public function getContentUsingMeta($pageID, $metaKey){
	 $query = DB::select("select * from  pages_meta where  pageId = '". $pageID ."' AND meta_key = '". $metaKey ."'");   
	 $result  = $query;
	 return  $result;		
	}
	
	public function updateContentUsingMeta($pageID, $content, $metaKey){
		
		$resExists = $this->getContentUsingMeta($pageID, $metaKey);
		
		if(count($resExists) > 0){
			$updateDta = array(
				'meta_value' => addslashes($content)
			);
			
			$resQry = DB::table('pages_meta')
				->where('pageId', $pageID)  
				->where('meta_key', $metaKey)  
				->limit(1)  // optional - to ensure only one record is updated.
				->update($updateDta);  // update the record in the DB.
			return 1;
		}else{
			$insertData = array(
				'pageId' => $pageID,
				'meta_key' => $metaKey,
				'meta_value' => addslashes(urlencode($content))
			);
			
			$insertId = DB::table('pages_meta')->insertGetId($insertData);
			
			$result = $insertId;
			return $result;
		}
	}	


   /**
     * Update Banner Text
     *
     * @GS
     */		
	public function updateBannerText($text,$bannerId){
		
		$updateDta = array(
			'bannerText' => addslashes($text)
		);
		
		$resQry = DB::table('banners')
			->where('bannerId', $bannerId)  
			->limit(1)  // optional - to ensure only one record is updated.
			->update($updateDta);  // update the record in the DB.
		return 1;		
	}
	
   /**
     * Add Banner Text
     *
     * @GS
     */		
	public function addBannerText($text,$id){
		$resText = $this->getBannerText($id);
		if(count($resText) > 0){
			$updateDta = array(
				'bannerText' => addslashes(urlencode($text))
			);
			
			$resQry = DB::table('banners')
				->where('pageId', $id)  
				->limit(1)  // optional - to ensure only one record is updated.
				->update($updateDta);  // update the record in the DB.
			return 1;
		}else{
			$insertData = array(
				'bannerText' => addslashes(urlencode($text)),
				'pageId' => addslashes(urlencode($id)),
			);
			
			$insertId = DB::table('banners')->insertGetId($insertData);
			
			$result = $insertId;
			return $result;
		}		
	}
	
	public function getPageLinks($pageId){
	 $query = DB::select("select * from dynamic_links where pageId = '". $pageId ."' order by linkId asc");   
	 $result  = $query;
	 return  $result;		
	}
	
	public function getTopLinks(){
	 $query = DB::select("select * from dynamic_links where (linkId >= '3' AND linkId <= '5') order by pageId asc");   
	 $result  = $query;
	 return  $result;		
	}
	
	public function getBanner($page_id){
	 $query = DB::select("select banner_image, pCloudFileID from dynamic_pages where page_id = '". $page_id ."'");   
	 $result  = $query;
	 return  $result;		
	}
	
	public function updateTopLinks($data){
		
		$updateDtaDj = array(
			'linkHref' => $data['dj_link']
		);
		$updateDtaArt = array(
			'linkHref' => $data['artist_link']
		);
		$updateDtaBrd = array(
			'linkHref' => $data['brand_link']
		);
		
		$resQry1 = DB::table('dynamic_links')
			->where('linkId', 3)  
			->limit(1) 
			->update($updateDtaDj);
			
		$resQry2 = DB::table('dynamic_links')
			->where('linkId', 4)  
			->limit(1) 
			->update($updateDtaArt);
			
		$resQry3 = DB::table('dynamic_links')
			->where('linkId', 5)  
			->limit(1) 
			->update($updateDtaBrd);
			
		return 1;
	}
	
	public function updatePageLinks($data,$pageId){
		
		$count =  count($data['labels']);
		for($i=0;$i<$count;$i++){
			
		  $updateDta = array(
			'linkLabel' => addslashes($data['labels'][$i]),
			'linkHref' => addslashes($data['links'][$i])
			);
			
		  $resQry1 = DB::table('dynamic_links')
			->where('linkId', $data['linkIds'][$i])  
			->limit(1) 
			->update($updateDta);
			
		}
		return 1;
	}
	
		
	public function updateLinks($data,$pageId){
		
		$count =  count($data['links']);
		for($i=0;$i<$count;$i++){
			
		  $updateDta = array(
			'linkHref' => addslashes($data['links'][$i])
			);
			
		  $resQry1 = DB::table('dynamic_links')
			->where('pageId', $pageId)  
			->where('linkId', $data['linkIds'][$i])  
			->limit(1) 
			->update($updateDta);
			
		}
		return 1;
	}
   /**
     * Update Meta
     *
     * @GS
     */	
	public function updateMeta($data){
		
		$updateDta = array(
			'meta_tittle' => addslashes($data['meta_tittle']),
			'meta_keywords' => addslashes($data['meta_keywords']),
			'meta_description' => addslashes($data['meta_description']),
			'fb_title' => addslashes($data['fb_title']),
			'fb_description' => addslashes($data['fb_description'])
		);
		
		$resQry = DB::table('dynamic_pages')
			->where('page_id', $data['page_id'])  
			->limit(1)  // optional - to ensure only one record is updated.
			->update($updateDta);  // update the record in the DB.
			
		return 1;
		
	}
   /**
     * Add FB Image
     *
     * @GS
     */
	public function add_fb_image($page_id,$image){
		$updateDta = array(
			'fb_image' => $image
		);
		$resQry = DB::table('dynamic_pages')
			->where('page_id', $page_id)  
			->limit(1)  // optional - to ensure only one record is updated.
			->update($updateDta);  // update the record in the DB.
		return 1;
	}
	
	public function getNumMembers($where, $sort){
		
        $query = DB::select("select count(*) as total from  members $where order by $sort");

        $result = $query[0]->total;

        //$result['data']  = $query;
		
		 

        return $result;		
	}
	
    public function getMembershipDetails($memberId, $start, $limit){

        $query = DB::select("select *  from  member_subscriptions	where member_Id = '" . $memberId . "' and status = '1' order by subscription_Id desc limit $start, $limit");
        $query = DB::select("select *  from  member_subscriptions	where member_Id = '" . $memberId . "' and status = '1'");
        $result['numRows']  = count($query);

        $result['data']  = $query;

        return  $result;
    }
	
	public function getMembersAll($where, $sort, $start, $limit){
		
// 		echo "select * from  members $where order by $sort limit $start, $limit";
        $query = DB::select("select * from  members $where order by $sort limit $start, $limit");
        $result['numRows']  = count($query);

        $result['data']  = $query;

        return  $result;		
	}
	
	public function deleteMember($memId){
		
		$result = DB::table('members')->where('id', $memId)->delete();
		
		return $result;	
	}
	
	public function addMembership($memberId, $validity, $adminId, $startDate, $endDate){
		
        $insertId = 0;

        $today = date("Y-m-d");

        $query1 = DB::select("select subscription_Id, endDate from  member_subscriptions where member_Id = '" . $memberId . "' and endDate > '" . $today . "' order by subscription_Id desc limit 0, 1");
        $result1 = count($query1);
		
        if ($result1 < 1) {
			
			$insertData = array(
				'member_Id' => $memberId,
				'package_Id' => 3,
				'duration_Id' => $validity,
				'status' => 1,
				'subscribed_date_time' => date('Y-m-d H:i:s'),
				'admin_Id' => $adminId,
				'startDate' => $startDate,
				'endDate' => $endDate
			);
			
			$insertId = DB::table('member_subscriptions')->insertGetId($insertData);
			
        } else {
            $insertId = -1;
        }
        return $insertId;
    }
	
	public function removeMembership($memberId, $subscriptionId){
		$result = DB::table('member_subscriptions')->where('member_Id', $memberId)->where('subscription_Id', $subscriptionId)->delete();
		
		return $result;	
	}
	
	public function getViewMemberInfo($memId){
		
       $query = DB::select("select * from  members left join members_dj_mixer on members.id = members_dj_mixer.member where members.id = '$memId'");

        $result['numRows'] =  count($query);

        $result['data']  = $query;

        return $result;		
	}
	
	public function adminChangeMemberPassword($password, $memberId){
		$pas1=md5($password);
		$query = DB::select("update `members` set pword = '" . $pas1 . "' where id = '" . $memberId . "'");

        return $query;
		
	}
	
	public function checkTrackExists($postData){
		
		$phpObject = json_decode($postData,true);
		
		$artist = trim($phpObject['songArtist']);
		$title = trim($phpObject['trackTitle']);
		
		$chk_qry = DB::select("SELECT id, title FROM `tracks` WHERE artist = '" . urlencode($artist) . "' AND title = '" . urlencode($title) . "' AND deleted=0");
		$result['numRows'] = count($chk_qry);
		$result['data']  = $chk_qry;
        return json_encode($result);
		
	}
	
	public function checkDuplicateMemberEmail($data, $memberId){
        extract($data);
        $query = DB::select("select id from members where (email= '" . urlencode(trim($email)) . "' OR email= '" . trim($email) . "') AND id !='".$memberId."'");
		
		if(count($query)>0){
            return 0;
        }
        return 1;
        
/*         $result['numRows'] =  count($query);

        $result['data']  = $query;

        return $result;	 */
    }

	
	public function updateEditedMember($data, $memberId, $adminId){
		
        extract($data);

        //echo '<pre>';print_r($data);die;

        if (!(isset($djMixer))) {
            $djMixer = 0;
        }

        if (!(isset($radioStation))) {
            $radioStation = 0;
        }

        if (!(isset($massMedia))) {
            $massMedia = 0;
        }

        if (!(isset($recordLabel))) {
            $recordLabel = 0;
        }

        if (!(isset($management))) {
            $management = 0;
        }

        if (!(isset($clothingApparel))) {
            $clothingApparel = 0;
        }

        if (!(isset($promoter))) {
            $promoter = 0;
        }

        if (!(isset($specialServices))) {
            $specialServices = 0;
        }

        if (!(isset($productionTalent))) {
            $productionTalent = 0;
        }  

        if (!(isset($dob))) {
            $dob = '';
        }      

        $query = DB::select("update members set uname = '" . urlencode($userName) . "', fname = '" . urlencode($firstName) . "', lname = '" . urlencode($lastName) . "', stagename = '" . urlencode($stageName) . "', dob = '".$dob."', email = '" . urlencode(trim($email)) . "', address1 = '" . urlencode($address1) . "', address2 = '" . urlencode($address2) . "', city = '" . urlencode($city) . "', state = '" . urlencode($state) . "', country = '" . urlencode($country) . "', zip = '" . urlencode($zip) . "', phone = '" . urlencode($phone) . "',  dj_mixer='".$djMixer."', radio_station='".$radioStation."', edited = NOW(), editedby = '" . $adminId . "', sex = '" . urlencode($sex) . "', howheard = '" . $howheard . "', howheardvalue = '" . $howheardvalue . "' where id = '" . $memberId . "'");

        if (!(isset($djtype_commercialreporting))) {
            $djtype_commercialreporting = 0;
        }

        if (!(isset($djtype_commercialnonreporting))) {
            $djtype_commercialnonreporting = 0;
        }

        if (!(isset($djtype_club))) {
            $djtype_club = 0;
        }

        if (!(isset($djtype_mixtape))) {
            $djtype_mixtape = 0;
        }

        if (!(isset($djtype_satellite))) {
            $djtype_satellite = 0;
        }

        if (!(isset($djtype_internet))) {
            $djtype_internet = 0;
        }

        if (!(isset($djtype_college))) {
            $djtype_college = 0;
        }

        if (!(isset($djtype_pirate))) {
            $djtype_pirate = 0;
        }

        if (!(isset($djwith_mp3))) {
            $djwith_mp3 = 0;
        }

        if (!(isset($djwith_mp3_serato))) {
            $djwith_mp3_serato = 0;
        }

        if (!(isset($djwith_mp3_final))) {
            $djwith_mp3_final = 0;
        }

        if (!(isset($djwith_mp3_pcdj))) {
            $djwith_mp3_pcdj = 0;
        }

        if (!(isset($djwith_mp3_ipod))) {
            $djwith_mp3_ipod = 0;
        }

        if (!(isset($djwith_mp3_other))) {
            $djwith_mp3_other = 0;
        }

        if (!(isset($djwith_cd))) {
            $djwith_cd = 0;
        }

        if (!(isset($djwith_cd_stanton))) {
            $djwith_cd_stanton = 0;
        }

        if (!(isset($djwith_cd_numark))) {
            $djwith_cd_numark = 0;
        }

        if (!(isset($djwith_cd_american))) {
            $djwith_cd_american = 0;
        }

        if (!(isset($djwith_cd_vestax))) {
            $djwith_cd_vestax = 0;
        }

        if (!(isset($djwith_cd_technics))) {
            $djwith_cd_technics = 0;
        }

        if (!(isset($djwith_cd_gemini))) {
            $djwith_cd_gemini = 0;
        }

        if (!(isset($djwith_cd_denon))) {
            $djwith_cd_denon = 0;
        }

        if (!(isset($djwith_cd_gemsound))) {
            $djwith_cd_gemsound = 0;
        }

        if (!(isset($djwith_cd_pioneer))) {
            $djwith_cd_pioneer = 0;
        }

        if (!(isset($djwith_cd_tascam))) {
            $djwith_cd_tascam = 0;
        }

        if (!(isset($djwith_cd_other))) {
            $djwith_cd_other = 0;
        }

        if (!(isset($djwith_vinyl))) {
            $djwith_vinyl = 0;
        }

        if (!(isset($djwith_vinyl_12))) {
            $djwith_vinyl_12 = 0;
        }

        if (!(isset($djwith_vinyl_45))) {
            $djwith_vinyl_45 = 0;
        }

        if (!(isset($djwith_vinyl_78))) {
            $djwith_vinyl_78 = 0;
        }


        if (!(isset($clubdj_hiphop))) {
            $clubdj_hiphop = 0;
        }

        if (!(isset($clubdj_rb))) {
            $clubdj_rb = 0;
        }

        if (!(isset($clubdj_pop))) {
            $clubdj_pop = 0;
        }

        if (!(isset($clubdj_reggae))) {
            $clubdj_reggae = 0;
        }

        if (!(isset($clubdj_house))) {
            $clubdj_house = 0;
        }

        if (!(isset($clubdj_calypso))) {
            $clubdj_calypso = 0;
        }

        if (!(isset($clubdj_rock))) {
            $clubdj_rock = 0;
        }

        if (!(isset($clubdj_techno))) {
            $clubdj_techno = 0;
        }

        if (!(isset($clubdj_trance))) {
            $clubdj_trance = 0;
        }

        if (!(isset($clubdj_afro))) {
            $clubdj_afro = 0;
        }

        if (!(isset($clubdj_reggaeton))) {
            $clubdj_reggaeton = 0;
        }

        if (!(isset($clubdj_gogo))) {
            $clubdj_gogo = 0;
        }

        if (!(isset($clubdj_neosoul))) {
            $clubdj_neosoul = 0;
        }

        if (!(isset($clubdj_oldschool))) {
            $clubdj_oldschool = 0;
        }

        if (!(isset($clubdj_electronic))) {
            $clubdj_electronic = 0;
        }

        if (!(isset($clubdj_latin))) {
            $clubdj_latin = 0;
        }

        if (!(isset($clubdj_dance))) {
            $clubdj_dance = 0;
        }

        if (!(isset($clubdj_jazz))) {
            $clubdj_jazz = 0;
        }

        if (!(isset($clubdj_country))) {
            $clubdj_country = 0;
        }

        if (!(isset($clubdj_world))) {
            $clubdj_world = 0;
        }


        if (!(isset($clubdj_monday))) {
            $clubdj_monday = 0;
        }

        if (!(isset($clubdj_tuesday))) {
            $clubdj_tuesday = 0;
        }

        if (!(isset($clubdj_wednesday))) {
            $clubdj_wednesday = 0;
        }

        if (!(isset($clubdj_thursday))) {
            $clubdj_thursday = 0;
        }

        if (!(isset($clubdj_friday))) {
            $clubdj_friday = 0;
        }

        if (!(isset($clubdj_saturday))) {
            $clubdj_saturday = 0;
        }

        if (!(isset($clubdj_sunday))) {
            $clubdj_sunday = 0;
        }

        if (!(isset($clubdj_varies))) {
            $clubdj_varies = 0;
        }


        if (!(isset($commercialdj_monday))) {
            $commercialdj_monday = 0;
        }

        if (!(isset($commercialdj_tuesday))) {
            $commercialdj_tuesday = 0;
        }

        if (!(isset($commercialdj_wednesday))) {
            $commercialdj_wednesday = 0;
        }

        if (!(isset($commercialdj_thursday))) {
            $commercialdj_thursday = 0;
        }

        if (!(isset($commercialdj_friday))) {
            $commercialdj_friday = 0;
        }

        if (!(isset($commercialdj_saturday))) {
            $commercialdj_saturday = 0;
        }

        if (!(isset($commercialdj_sunday))) {
            $commercialdj_sunday = 0;
        }

        if (!(isset($commercialdj_varies))) {
            $commercialdj_varies = 0;
        }

        if (!(isset($noncommercialdj_monday))) {
            $noncommercialdj_monday = 0;
        }

        if (!(isset($noncommercialdj_tuesday))) {
            $noncommercialdj_tuesday = 0;
        }

        if (!(isset($noncommercialdj_wednesday))) {
            $noncommercialdj_wednesday = 0;
        }

        if (!(isset($noncommercialdj_thursday))) {
            $noncommercialdj_thursday = 0;
        }

        if (!(isset($noncommercialdj_friday))) {
            $noncommercialdj_friday = 0;
        }

        if (!(isset($noncommercialdj_saturday))) {
            $noncommercialdj_saturday = 0;
        }

        if (!(isset($noncommercialdj_sunday))) {
            $noncommercialdj_sunday = 0;
        }

        if (!(isset($noncommercialdj_varies))) {
            $noncommercialdj_varies = 0;
        }


        if (!(isset($satellitedj_monday))) {
            $satellitedj_monday = 0;
        }

        if (!(isset($satellitedj_tuesday))) {
            $satellitedj_tuesday = 0;
        }

        if (!(isset($satellitedj_wednesday))) {
            $satellitedj_wednesday = 0;
        }

        if (!(isset($satellitedj_thursday))) {
            $satellitedj_thursday = 0;
        }

        if (!(isset($satellitedj_friday))) {
            $satellitedj_friday = 0;
        }

        if (!(isset($satellitedj_saturday))) {
            $satellitedj_saturday = 0;
        }

        if (!(isset($satellitedj_sunday))) {
            $satellitedj_sunday = 0;
        }

        if (!(isset($satellitedj_varies))) {
            $satellitedj_varies = 0;
        }


        if (!(isset($internetdj_monday))) {
            $internetdj_monday = 0;
        }

        if (!(isset($internetdj_tuesday))) {
            $internetdj_tuesday = 0;
        }

        if (!(isset($internetdj_wednesday))) {
            $internetdj_wednesday = 0;
        }

        if (!(isset($internetdj_thursday))) {
            $internetdj_thursday = 0;
        }

        if (!(isset($internetdj_friday))) {
            $internetdj_friday = 0;
        }

        if (!(isset($internetdj_saturday))) {
            $internetdj_saturday = 0;
        }

        if (!(isset($internetdj_sunday))) {
            $internetdj_sunday = 0;
        }

        if (!(isset($internetdj_varies))) {
            $internetdj_varies = 0;
        }

        if (!(isset($collegedj_monday))) {
            $collegedj_monday = 0;
        }

        if (!(isset($collegedj_tuesday))) {
            $collegedj_tuesday = 0;
        }

        if (!(isset($collegedj_wednesday))) {
            $collegedj_wednesday = 0;
        }

        if (!(isset($collegedj_thursday))) {
            $collegedj_thursday = 0;
        }

        if (!(isset($collegedj_friday))) {
            $collegedj_friday = 0;
        }

        if (!(isset($collegedj_saturday))) {
            $collegedj_saturday = 0;
        }

        if (!(isset($collegedj_sunday))) {
            $collegedj_sunday = 0;
        }

        if (!(isset($collegedj_varies))) {
            $collegedj_varies = 0;
        }

        if (!(isset($piratedj_monday))) {
            $piratedj_monday = 0;
        }

        if (!(isset($piratedj_tuesday))) {
            $piratedj_tuesday = 0;
        }

        if (!(isset($piratedj_wednesday))) {
            $piratedj_wednesday = 0;
        }

        if (!(isset($piratedj_thursday))) {
            $piratedj_thursday = 0;
        }

        if (!(isset($piratedj_friday))) {
            $piratedj_friday = 0;
        }

        if (!(isset($piratedj_saturday))) {
            $piratedj_saturday = 0;
        }

        if (!(isset($piratedj_sunday))) {
            $piratedj_sunday = 0;
        }

        if (!(isset($piratedj_varies))) {
            $piratedj_varies = 0;
        }  

        $clubdj_partytype = '';    

		
        $managementQuery = DB::select("SELECT id FROM members_dj_mixer where member = '" . $memberId . "'");

        $managementRows = count($managementQuery);

			if($managementRows > 0) {
				$updateMemDjMixData = array(
					'djtype_commercialreporting' => (int) $djtype_commercialreporting,
					'djtype_commercialnonreporting' => (int) $djtype_commercialnonreporting,
					'djtype_club' => (int) $djtype_club,
					'djtype_mixtape' => (int) $djtype_mixtape,
					'djtype_satellite' => (int) $djtype_satellite,
					'djtype_internet' => (int) $djtype_internet,
					'djtype_college' => (int) $djtype_college,
					'djtype_pirate' => (int) $djtype_pirate,
					'djwith_mp3' => (int) $djwith_mp3,
					'djwith_mp3_serato' => (int) $djwith_mp3_serato,
					'djwith_mp3_final' => (int) $djwith_mp3_final,
					'djwith_mp3_pcdj' => (int) $djwith_mp3_pcdj,
					'djwith_mp3_ipod' => (int) $djwith_mp3_ipod,
					'djwith_mp3_other' => (int) $djwith_mp3_other,
					'djwith_cd' => (int) $djwith_cd,
					'djwith_cd_stanton' => (int) $djwith_cd_stanton,
					'djwith_cd_numark' => (int) $djwith_cd_numark,
					'djwith_cd_american' => (int) $djwith_cd_american,
					'djwith_cd_vestax' => (int) $djwith_cd_vestax,
					'djwith_cd_technics' => (int) $djwith_cd_technics,
					'djwith_cd_gemini' => (int) $djwith_cd_gemini,
					'djwith_cd_denon' => (int) $djwith_cd_denon,
					'djwith_cd_gemsound' => (int) $djwith_cd_gemsound,
					'djwith_cd_pioneer' => (int) $djwith_cd_pioneer,
					'djwith_cd_tascam' => (int) $djwith_cd_tascam,
					'djwith_cd_other' => (int) $djwith_cd_other,
					'djwith_vinyl' => (int) $djwith_vinyl,
					'djwith_vinyl_12' => (int) $djwith_vinyl_12,
					'djwith_vinyl_45' => (int) $djwith_vinyl_45,
					'djwith_vinyl_78' => (int) $djwith_vinyl_78,
					'clubdj_clubname' => addslashes($clubdj_clubname),
					'clubdj_capacity' => addslashes($clubdj_capacity),
					'clubdj_partytype' => addslashes($clubdj_partytype),
					'clubdj_hiphop' => (int) $clubdj_hiphop,
					'clubdj_rb' => (int) $clubdj_rb,
					'clubdj_pop' => (int) $clubdj_pop,
					'clubdj_reggae' => (int) $clubdj_reggae,
					'clubdj_house' => (int) $clubdj_house,
					'clubdj_calypso' => (int) $clubdj_calypso,
					'clubdj_rock' => (int) $clubdj_rock,
					'clubdj_techno' => (int) $clubdj_techno,
					'clubdj_trance' => (int) $clubdj_trance,
					'clubdj_afro' => (int) $clubdj_afro,
					'clubdj_reggaeton' => (int) $clubdj_reggaeton,
					'clubdj_gogo' => (int) $clubdj_gogo,
					'clubdj_neosoul' => (int) $clubdj_neosoul,
					'clubdj_oldschool' => (int) $clubdj_oldschool,
					'clubdj_electronic' => (int) $clubdj_electronic,
					'clubdj_latin' => (int) $clubdj_latin,
					'clubdj_dance' => (int) $clubdj_dance,
					'clubdj_jazz' => (int) $clubdj_jazz,
					'clubdj_country' => (int) $clubdj_country,
					'clubdj_world' => (int) $clubdj_world,
					'clubdj_monday' => (int) $clubdj_monday,
					'clubdj_tuesday' => (int) $clubdj_tuesday,
					'clubdj_wednesday' => (int) $clubdj_wednesday,
					'clubdj_thursday' => (int) $clubdj_thursday,
					'clubdj_friday' => (int) $clubdj_friday,
					'clubdj_saturday' => (int) $clubdj_saturday,
					'clubdj_sunday' => (int) $clubdj_sunday,
					'clubdj_varies' => (int) $clubdj_varies,
					'clubdj_city' => addslashes($clubdj_city),
					'clubdj_state' => addslashes($clubdj_state),
					'clubdj_intcountry' => addslashes($clubdj_intcountry)														
				);

				$resMemDjMixQry = DB::table('members_dj_mixer')
					->where('member', $memberId)
					->limit(1)
					->update($updateMemDjMixData);

			}else{

				$updateMemDjMixData = array(
					'member' => (int) $memberId,
					'djtype_commercialreporting' => (int) $djtype_commercialreporting,
					'djtype_commercialnonreporting' => (int) $djtype_commercialnonreporting,
					'djtype_club' => (int) $djtype_club,
					'djtype_mixtape' => (int) $djtype_mixtape,
					'djtype_satellite' => (int) $djtype_satellite,
					'djtype_internet' => (int) $djtype_internet,
					'djtype_college' => (int) $djtype_college,
					'djtype_pirate' => (int) $djtype_pirate,
					'djwith_mp3' => (int) $djwith_mp3,
					'djwith_mp3_serato' => (int) $djwith_mp3_serato,
					'djwith_mp3_final' => (int) $djwith_mp3_final,
					'djwith_mp3_pcdj' => (int) $djwith_mp3_pcdj,
					'djwith_mp3_ipod' => (int) $djwith_mp3_ipod,
					'djwith_mp3_other' => (int) $djwith_mp3_other,
					'djwith_cd' => (int) $djwith_cd,
					'djwith_cd_stanton' => (int) $djwith_cd_stanton,
					'djwith_cd_numark' => (int) $djwith_cd_numark,
					'djwith_cd_american' => (int) $djwith_cd_american,
					'djwith_cd_vestax' => (int) $djwith_cd_vestax,
					'djwith_cd_technics' => (int) $djwith_cd_technics,
					'djwith_cd_gemini' => (int) $djwith_cd_gemini,
					'djwith_cd_denon' => (int) $djwith_cd_denon,
					'djwith_cd_gemsound' => (int) $djwith_cd_gemsound,
					'djwith_cd_pioneer' => (int) $djwith_cd_pioneer,
					'djwith_cd_tascam' => (int) $djwith_cd_tascam,
					'djwith_cd_other' => (int) $djwith_cd_other,
					'djwith_vinyl' => (int) $djwith_vinyl,
					'djwith_vinyl_12' => (int) $djwith_vinyl_12,
					'djwith_vinyl_45' => (int) $djwith_vinyl_45,
					'djwith_vinyl_78' => (int) $djwith_vinyl_78,
					'clubdj_clubname' => addslashes($clubdj_clubname),
					'clubdj_capacity' => addslashes($clubdj_capacity),
					'clubdj_partytype' => addslashes($clubdj_partytype),
					'clubdj_hiphop' => (int) $clubdj_hiphop,
					'clubdj_rb' => (int) $clubdj_rb,
					'clubdj_pop' => (int) $clubdj_pop,
					'clubdj_reggae' => (int) $clubdj_reggae,
					'clubdj_house' => (int) $clubdj_house,
					'clubdj_calypso' => (int) $clubdj_calypso,
					'clubdj_rock' => (int) $clubdj_rock,
					'clubdj_techno' => (int) $clubdj_techno,
					'clubdj_trance' => (int) $clubdj_trance,
					'clubdj_afro' => (int) $clubdj_afro,
					'clubdj_reggaeton' => (int) $clubdj_reggaeton,
					'clubdj_gogo' => (int) $clubdj_gogo,
					'clubdj_neosoul' => (int) $clubdj_neosoul,
					'clubdj_oldschool' => (int) $clubdj_oldschool,
					'clubdj_electronic' => (int) $clubdj_electronic,
					'clubdj_latin' => (int) $clubdj_latin,
					'clubdj_dance' => (int) $clubdj_dance,
					'clubdj_jazz' => (int) $clubdj_jazz,
					'clubdj_country' => (int) $clubdj_country,
					'clubdj_world' => (int) $clubdj_world,
					'clubdj_monday' => (int) $clubdj_monday,
					'clubdj_tuesday' => (int) $clubdj_tuesday,
					'clubdj_wednesday' => (int) $clubdj_wednesday,
					'clubdj_thursday' => (int) $clubdj_thursday,
					'clubdj_friday' => (int) $clubdj_friday,
					'clubdj_saturday' => (int) $clubdj_saturday,
					'clubdj_sunday' => (int) $clubdj_sunday,
					'clubdj_varies' => (int) $clubdj_varies,
					'clubdj_city' => addslashes($clubdj_city),
					'clubdj_state' => addslashes($clubdj_state),
					'clubdj_intcountry' => addslashes($clubdj_intcountry)														
				);

				$insertMemDjMixId = DB::table('members_dj_mixer')->insert($updateMemDjMixData);

			}

        // radio station

        if (!(isset($airMonday))) {
            $airMonday = 0;
        }

        if (!(isset($airTuesday))) {
            $airTuesday = 0;
        }

        if (!(isset($airWednesday))) {
            $airWednesday = 0;
        }

        if (!(isset($airThursday))) {
            $airThursday = 0;
        }

        if (!(isset($airFriday))) {
            $airFriday = 0;
        }

        if (!(isset($airSaturday))) {
            $airSaturday = 0;
        }

        if (!(isset($airSunday))) {
            $airSunday = 0;
        }

        if (!(isset($airVaries))) {
            $airVaries = 0;
        }

        if (!(isset($musicMonday))) {
            $musicMonday = 0;
        }

        if (!(isset($musicTuesday))) {
            $musicTuesday = 0;
        }

        if (!(isset($musicWednesday))) {
            $musicWednesday = 0;
        }

        if (!(isset($musicThursday))) {
            $musicThursday = 0;
        }

        if (!(isset($musicFriday))) {
            $musicFriday = 0;
        }

        if (!(isset($musicSaturday))) {
            $musicSaturday = 0;
        }

        if (!(isset($musicSunday))) {
            $musicSunday = 0;
        }

        if (!(isset($musicVaries))) {
            $musicVaries = 0;
        }

        if (!(isset($programMonday))) {
            $programMonday = 0;
        }

        if (!(isset($programTuesday))) {
            $programTuesday = 0;
        }

        if (!(isset($programWednesday))) {
            $programWednesday = 0;
        }

        if (!(isset($programThursday))) {
            $programThursday = 0;
        }

        if (!(isset($programFriday))) {
            $programFriday = 0;
        }

        if (!(isset($programSaturday))) {
            $programSaturday = 0;
        }

        if (!(isset($programSunday))) {
            $programSunday = 0;
        }

        if (!(isset($programVaries))) {
            $programVaries = 0;
        }


        if (!(isset($radioMusic))) {
            $radioMusic = 0;
        }

        if (!(isset($radioProgram))) {
            $radioProgram = 0;
        }

        if (!(isset($radioAir))) {
            $radioAir = 0;
        }

        if (!(isset($radioPromotion))) {
            $radioPromotion = 0;
        }

        if (!(isset($radioProduction))) {
            $radioProduction = 0;
        }

        if (!(isset($radioSales))) {
            $radioSales = 0;
        }

        if (!(isset($radioIt))) {
            $radioIt = 0;
        }



        $queryMemRadioDta = DB::select("SELECT id FROM members_radio_station where member = '" .$memberId. "'");

        $memRadioDtaRows = count($queryMemRadioDta);

			if($memRadioDtaRows > 0) {

					$updateMemRadioData = array(
						'radiotype_musicdirector' => (int)$radioMusic,
						'radiotype_programdirector' => (int) $radioProgram,
						'radiotype_jock' => (int) $radioAir,
						'radiotype_promotion' => (int) $radioPromotion,
						'radiotype_production' => (int) $radioProduction,
						'radiotype_sales' => (int) $radioSales,
						'radiotype_tech' => (int) $radioIt,
						'stationcallletters' => addslashes($stationCall),
						'stationfrequency' => addslashes($stationFrequency),
						'stationname' => addslashes($stationName),
						'programdirector_stationcallletters' => addslashes($programCall),
						'programdirector_host' => addslashes($programHost),
						'programdirector_showname' => addslashes($programName),
						'programdirector_showtime' => addslashes($programTime),
						'programdirector_monday' => (int) $programMonday,
						'programdirector_tuesday' => (int) $programTuesday,
						'programdirector_wednesday' => (int) $programWednesday,
						'programdirector_thursday' => (int) $programThursday,
						'programdirector_friday' => (int) $programFriday,
						'programdirector_saturday' => (int) $programSaturday,
						'programdirector_sunday' => (int) $programSunday,
						'programdirector_varies' => (int) $programVaries,
						'musicdirector_stationcallletters' => addslashes($musicCall),
						'musicdirector_host' => addslashes($musicHost),
						'musicdirector_showname' => addslashes($musicName),
						'musicdirector_showtime' => addslashes($musicTime),
						'musicdirector_monday' => (int) $musicMonday,
						'musicdirector_tuesday' => (int) $musicTuesday,
						'musicdirector_wednesday' => (int) $musicWednesday,
						'musicdirector_thursday' => (int) $musicThursday,
						'musicdirector_friday' => (int) $musicFriday,
						'musicdirector_saturday' => (int) $musicSaturday,
						'musicdirector_sunday' => (int) $musicSunday,
						'musicdirector_varies' => (int) $musicVaries,
						'onairpersonality_showname' => addslashes($airName),
						'onairpersonality_showtime' => addslashes($airTime),
						'onairpersonality_monday' => (int) $airMonday,
						'onairpersonality_tuesday' => (int) $airTuesday,
						'onairpersonality_wednesday' => (int) $airWednesday,
						'onairpersonality_thursday' => (int) $airThursday,
						'onairpersonality_friday' => (int) $airFriday,
						'onairpersonality_saturday' => (int) $airSaturday,
						'onairpersonality_sunday' => (int) $airSunday,
						'onairpersonality_varies' => (int) $airVaries,
						
					);

				$resRadioQry = DB::table('members_radio_station')
					->where('member', $memberId)
					->limit(1)
					->update($updateMemRadioData);					

			}else{

					$updateMemRadioData = array(
						'member' => (int) $memberId,
						'radiotype_musicdirector' => (int)$radioMusic,
						'radiotype_programdirector' => (int) $radioProgram,
						'radiotype_jock' => (int) $radioAir,
						'radiotype_promotion' => (int) $radioPromotion,
						'radiotype_production' => (int) $radioProduction,
						'radiotype_sales' => (int) $radioSales,
						'radiotype_tech' => (int) $radioIt,
						'stationcallletters' => addslashes($stationCall),
						'stationfrequency' => addslashes($stationFrequency),
						'stationname' => addslashes($stationName),
						'programdirector_stationcallletters' => addslashes($programCall),
						'programdirector_host' => addslashes($programHost),
						'programdirector_showname' => addslashes($programName),
						'programdirector_showtime' => addslashes($programTime),
						'programdirector_monday' => (int) $programMonday,
						'programdirector_tuesday' => (int) $programTuesday,
						'programdirector_wednesday' => (int) $programWednesday,
						'programdirector_thursday' => (int) $programThursday,
						'programdirector_friday' => (int) $programFriday,
						'programdirector_saturday' => (int) $programSaturday,
						'programdirector_sunday' => (int) $programSunday,
						'programdirector_varies' => (int) $programVaries,
						'musicdirector_stationcallletters' => addslashes($musicCall),
						'musicdirector_host' => addslashes($musicHost),
						'musicdirector_showname' => addslashes($musicName),
						'musicdirector_showtime' => addslashes($musicTime),
						'musicdirector_monday' => (int) $musicMonday,
						'musicdirector_tuesday' => (int) $musicTuesday,
						'musicdirector_wednesday' => (int) $musicWednesday,
						'musicdirector_thursday' => (int) $musicThursday,
						'musicdirector_friday' => (int) $musicFriday,
						'musicdirector_saturday' => (int) $musicSaturday,
						'musicdirector_sunday' => (int) $musicSunday,
						'musicdirector_varies' => (int) $musicVaries,
						'onairpersonality_showname' => addslashes($airName),
						'onairpersonality_showtime' => addslashes($airTime),
						'onairpersonality_monday' => (int) $airMonday,
						'onairpersonality_tuesday' => (int) $airTuesday,
						'onairpersonality_wednesday' => (int) $airWednesday,
						'onairpersonality_thursday' => (int) $airThursday,
						'onairpersonality_friday' => (int) $airFriday,
						'onairpersonality_saturday' => (int) $airSaturday,
						'onairpersonality_sunday' => (int) $airSunday,
						'onairpersonality_varies' => (int) $airVaries,
						
					);

					$insertMemRadioId = DB::table('members_radio_station')->insert($updateMemRadioData); 

			}        


       /*  if ($managementRows > 0) {

            DB::select("UPDATE members_radio_station 
                            SET radiotype_musicdirector = ".(int) $radioMusic.", 
                                radiotype_programdirector = ".(int) $radioProgram.", 
                                radiotype_jock = ".(int) $radioAir.", 
                                radiotype_promotion = ".(int) $radioPromotion.", 
                                radiotype_production = ".(int) $radioProduction.", 
                                radiotype_sales = ".(int) $radioSales.", 
                                radiotype_tech = ".(int) $radioIt.",
                                stationcallletters = '".addslashes($stationCall)."',
                                stationfrequency = '".addslashes($stationFrequency)."', 
                                stationname = '".addslashes($stationName)."',
	                            programdirector_stationcallletters = '".addslashes($programCall)."',
	                            programdirector_host = '".addslashes($programHost)."', 
	                            programdirector_showname = '".addslashes($programName)."', 
	                            programdirector_showtime = '".addslashes($programTime)."',
	                            programdirector_monday = ".(int) $programMonday.",
	                            programdirector_tuesday = ".(int) $programTuesday.",
	                            programdirector_wednesday = ".(int) $programWednesday.", 
	                            programdirector_thursday = ".(int) $programThursday.", 
	                            programdirector_friday = ".(int) $programFriday.", 
	                            programdirector_saturday = ".(int) $programSaturday.",
	                            programdirector_sunday = ".(int) $programSunday.", 
	                            programdirector_varies = ".(int) $programVaries.",
	                            musicdirector_stationcallletters = '".addslashes($musicCall)."',
	                            musicdirector_host = '".addslashes($musicHost)."',
	                            musicdirector_showname = '".addslashes($musicName)."',
	                            musicdirector_showtime = '".addslashes($musicTime)."',
	                            musicdirector_monday = ".(int) $musicMonday.",
	                            musicdirector_tuesday = ".(int) $musicTuesday.", 
	                            musicdirector_wednesday = ".(int) $musicWednesday.",
	                            musicdirector_thursday = ".(int) $musicThursday.", 
	                            musicdirector_friday = ".(int) $musicFriday.", 
	                            musicdirector_saturday = ".(int) $musicSaturday.",
	                            musicdirector_sunday = ".(int) $musicSunday.",
	                            musicdirector_varies = ".(int) $musicVaries.",
	                            onairpersonality_showname = '".addslashes($airName)."',
	                            onairpersonality_showtime = '".addslashes($airTime)."', 
	                            onairpersonality_monday = ".(int) $airMonday.",
	                            onairpersonality_tuesday = ".(int) $airTuesday.", 
	                            onairpersonality_wednesday = ".(int) $airWednesday.", 
	                            onairpersonality_thursday = ".(int) $airThursday.",
	                            onairpersonality_friday = ".(int) $airFriday.", 
	                            onairpersonality_saturday = ".(int) $airSaturday.", 
	                            onairpersonality_sunday = ".(int) $airSunday.", 
	                            onairpersonality_varies = ".(int) $airVaries."
	                            WHERE member = '" . (int) $memberId . "'");
        } else {

            DB::select("INSERT INTO `members_radio_station` (`member`, `radiotype_musicdirector`, `radiotype_programdirector`, `radiotype_jock`, `radiotype_promotion`, `radiotype_production`, `radiotype_sales`, `radiotype_tech`, `stationcallletters`, `stationfrequency`, `stationname`, `programdirector_stationcallletters`, `programdirector_host`, `programdirector_showname`, `programdirector_showtime`, `programdirector_monday`, `programdirector_tuesday`, `programdirector_wednesday`, `programdirector_thursday`, `programdirector_friday`, `programdirector_saturday`, `programdirector_sunday`, `programdirector_varies`, `musicdirector_stationcallletters`, `musicdirector_host`, `musicdirector_showname`, `musicdirector_showtime`, `musicdirector_monday`, `musicdirector_tuesday`, `musicdirector_wednesday`, `musicdirector_thursday`, `musicdirector_friday`, `musicdirector_saturday`, `musicdirector_sunday`, `musicdirector_varies`, `onairpersonality_showname`, `onairpersonality_showtime`, `onairpersonality_monday`, `onairpersonality_tuesday`, `onairpersonality_wednesday`, `onairpersonality_thursday`, `onairpersonality_friday`, `onairpersonality_saturday`, `onairpersonality_sunday`, `onairpersonality_varies`) 
                VALUES (
                    '".(int) $memberId."',
                    ".(int) $radioMusic.",
                    ".(int) $radioProgram.",
                    ".(int) $radioAir.",
                    ".(int) $radioPromotion.",
                    ".(int) $radioProduction.",
                    ".(int) $radioSales.",
                    ".(int) $radioIt.",
                    '".addslashes($stationCall)."',
                    '".addslashes($stationFrequency)."',
                    '".addslashes($stationName)."',
                    '".addslashes($programCall)."',
                    '".addslashes($programHost)."',
                    '".addslashes($programName)."',
                    '".addslashes($programTime)."',
                    ".(int) $programMonday.",
                    ".(int) $programTuesday.",
                    ".(int) $programWednesday.",
                    ".(int) $programThursday.",
                    ".(int) $programFriday.",
                    ".(int) $programSaturday.",
                    ".(int) $programSunday.",
                    ".(int) $programVaries.",
                    '".addslashes($musicCall)."',
                    '".addslashes($musicHost)."',
                    '".addslashes($musicName)."',
                    '".addslashes($musicTime)."',
                    ".(int) $musicMonday.",
                    ".(int) $musicTuesday.",
                    ".(int) $musicWednesday.",
                    ".(int) $musicThursday.",
                    ".(int) $musicFriday.",
                    ".(int) $musicSaturday.",
                    ".(int) $musicSunday.",
                    ".(int) $musicVaries.",
                    '".addslashes($airName)."',
                    '".addslashes($airTime)."',
                    ".(int) $airMonday.",
                    ".(int) $airTuesday.",
                    ".(int) $airWednesday.",
                    ".(int) $airThursday.",
                    ".(int) $airFriday.",
                    ".(int) $airSaturday.",
                    ".(int) $airSunday.",
                    ".(int) $airVaries.")");
        } */

        // media

        if (!(isset($massTv))) {
            $massTv = 0;
        }

        if (!(isset($massPublication))) {
            $massPublication = 0;
        }

        if (!(isset($massDotcom))) {
            $massDotcom = 0;
        }

        if (!(isset($massNewsletter))) {
            $massNewsletter = 0;
        }



        $managementQuery = DB::select("select id from members_mass_media where member = '" . $memberId . "'");

        $managementRows = count($managementQuery);

        /* if ($managementRows > 0) {

            DB::select("update members_mass_media set mediatype_tvfilm = '$massTv', mediatype_publication = '$massPublication', mediatype_newmedia = '$massDotcom', mediatype_newsletter = '$massNewsletter', media_name = '$massName', media_website = '$massWebsite', media_department = '$massDepartment' where member = '" . $memberId . "'");
        } else {

            DB::select("insert into `members_mass_media` (`member`, `mediatype_tvfilm`, `mediatype_publication`, `mediatype_newmedia`, `mediatype_newsletter`, `media_name`, `media_website`, `media_department`) values ('" . $memberId . "', '" . $massTv . "', '" . $massPublication . "', '" . $massDotcom . "', '" . $massNewsletter . "', '" . $massName . "', '" . $massWebsite . "', '" . $massDepartment . "')");
        } */

        // record label

        if (!(isset($recordMajor))) {
            $recordMajor = 0;
        }

        if (!(isset($recordIndy))) {
            $recordIndy = 0;
        }

        if (!(isset($recordDistribution))) {
            $recordDistribution = 0;
        }





        $managementQuery = DB::select("select id from members_record_label where member = '" . $memberId . "'");

        $managementRows = count($managementQuery);

        /* if ($managementRows > 0) {

            DB::select("update members_record_label set labeltype_major = '$recordMajor', labeltype_indy = '$recordIndy', labeltype_distribution = '$recordDistribution', label_name = '$recordName', label_department = '$recordDepartment' where member = '" . $memberId . "'");
        } else {

            DB::select("insert into `members_record_label` (`member`, `labeltype_major`, `labeltype_indy`, `labeltype_distribution`, `label_name`, `label_department`) values ('" . $memberId . "', '" . $recordMajor . "', '" . $recordIndy . "', '" . $recordDistribution . "', '" . $recordName . "', '" . $recordDepartment . "')");
        } */

        // management

        if (!(isset($managementArtist))) {
            $managementArtist = 0;
        }

        if (!(isset($managementTour))) {
            $managementTour = 0;
        }

        if (!(isset($managementPersonal))) {
            $managementPersonal = 0;
        }

        if (!(isset($managementFinance))) {
            $managementFinance = 0;
        }



        $managementQuery = DB::select("select id from members_management where member = '" . $memberId . "'");

        $managementRows = count($managementQuery);

        /* if ($managementRows > 0) {

            DB::select("update members_management set managementtype_artist = '$managementArtist', managementtype_tour = '$managementTour', managementtype_personal = '$managementPersonal', managementtype_finance = '$managementFinance', management_name = '$managementName', management_who = '$managementWho', management_industry = '$managementIndustry' where member = '" . $memberId . "'");
        } else {

            DB::select("insert into `members_management` (`member`, `managementtype_artist`, `managementtype_tour`, `managementtype_personal`, `managementtype_finance`, `management_name`, `management_who`, `management_industry`) values ('" . $memberId . "', '" . $managementArtist . "', '" . $managementTour . "', '" . $managementPersonal . "', '" . $managementFinance . "', '" . $managementName . "', '" . $managementWho . "', '" . $managementIndustry . "')");
        } */
        // clothing
		
        $clothingQuery = DB::select("select id from members_clothing_apparel where member = '" . $memberId . "'");

        $clothingRows = count($clothingQuery);

        /* if ($clothingRows > 0) {

            DB::select("update members_clothing_apparel set clothing_name = '$clothingName', clothing_department = '$clothingDepartment' where member = '" . $memberId . "'");
        } else {

            DB::select("insert into `members_clothing_apparel` (`member`, `clothing_name`, `clothing_department`) values ('" . $memberId . "', '" . $clothingName . "', '" . $clothingDepartment . "')");
        } */

        // promoter

        if (!(isset($promoterIndy))) {
            $promoterIndy = 0;
        }

        if (!(isset($promoterClub))) {
            $promoterClub = 0;
        }

        if (!(isset($promoterSpecial))) {
            $promoterSpecial = 0;
        }

        if (!(isset($promoterStreet))) {
            $promoterStreet = 0;
        }

        $promoterQuery = DB::select("select id from members_promoter where member = '" . $memberId . "'");

        $promoterRows = count($promoterQuery);

        /* if ($promoterRows > 0) {
            DB::select("update members_promoter set promotertype_indy = '$promoterIndy', promotertype_club = '$promoterClub', promotertype_event = '$promoterSpecial', promotertype_street = '$promoterStreet', promoter_name = '$promoterName', promoter_department = '$promoterDepartment', promoter_website = '$promoterWebsite' where member = '" . $memberId . "'");
        } else {

            DB::select("insert into `members_promoter` (`member`, `promotertype_indy`, `promotertype_club`, `promotertype_event`, `promotertype_street`, `promoter_name`, `promoter_department`, `promoter_website`) values ('" . $memberId . "', '" . $promoterIndy . "', '" . $promoterClub . "', '" . $promoterSpecial . "', '" . $promoterStreet . "', '" . $promoterName . "', '" . $promoterDepartment . "', '" . $promoterWebsite . "')");
        } */
		
        // special

        if (!(isset($specialCorporate))) {
            $specialCorporate = 0;
        }

        if (!(isset($specialGraphic))) {
            $specialGraphic = 0;
        }

        if (!(isset($specialWeb))) {
            $specialWeb = 0;
        }

        if (!(isset($specialOther))) {
            $specialOther = 0;
        }

        $specialQuery = DB::select("select id from members_special_services where member = '" . $memberId . "'");

        $specialRows = count($specialQuery);

        /* if ($specialRows > 0){
            DB::select("update members_special_services set servicestype_corporate = '$specialCorporate', servicestype_graphicdesign = '$specialGraphic', servicestype_webdesign = '$specialWeb', servicestype_other = '$specialOther', services_name = '$specialName', services_website = '$specialWebsite' where member = '" . $memberId . "'");
        }else{
            DB::select("insert into `members_special_services` (`member`, `servicestype_corporate`, `servicestype_graphicdesign`, `servicestype_webdesign`, `servicestype_other`, `services_name`, `services_website`) values ('" . $memberId . "', '" . $specialCorporate . "', '" . $specialGraphic . "', '" . $specialWeb . "', '" . $specialOther . "', '" . $specialName . "', '" . $specialWebsite . "')");
        } */
        // production

        if (!(isset($productionArtist))) {
            $productionArtist = 0;
        }

        if (!(isset($productionProducer))) {
            $productionProducer = 0;
        }

        if (!(isset($productionChoregrapher))) {
            $productionChoregrapher = 0;
        }

        if (!(isset($productionSound))) {
            $productionSound = 0;
        }

        $productionQuery = DB::select("select id from members_production_talent where member = '" . $memberId . "'");

        $productionRows = count($productionQuery);

        /* if ($productionRows > 0) {

            $this->db->query("update members_production_talent set productiontype_artist = '$productionArtist', productiontype_producer = '$productionProducer', productiontype_choreographer = '$productionChoregrapher', productiontype_sound = '$productionSound', production_name = '$productionName' where member = '" . $memberId . "'");
        } else {

            $this->db->query("insert into `members_production_talent` (`member`, `productiontype_artist`, `productiontype_producer`, `productiontype_choreographer`, `productiontype_sound`, `production_name`) values ('" . $memberId . "', '" . $productionArtist . "', '" . $productionProducer . "', '" . $productionChoregrapher . "', '" . $productionSound . "', '" . $productionName . "')");
        } */

        return $query;
    }

	// Functions added by R-S starts here 
    function getAdminModules($adminId)

    {

        $query = DB::select("select moduleId  from   admin_modules where adminId = '" . $adminId . "'");

        $result['numRows'] =  count($query);

        $result['data']  = $query;

        return $result;
    }

    function updateAdmin($aId, $name, $email, $role)

    {       
        $updateData = array(
			'name' =>   urlencode($name),
            'email' => $email,
            'user_role' => $role,

		);
		$result = DB::table('admins')
				->where('id', $aId)  
				->update($updateData);
		return  $result;

    }

    function updateAdminPassword($aId, $password)
    {
        $secure_password = bcrypt($password);
        $updateData = array(
			'password' =>   $secure_password,

		);
		$result = DB::table('admins')
				->where('id', $aId)  
				->update($updateData);
		return  $result;

    }

    function updateModule($moduleId, $memberId, $type)
    {

        $insertData = array(
			'adminId' => $memberId,
			'moduleId' => $moduleId,
		);
		
        if ($type == 1) {

            $result = DB::table('admin_modules')->insertGetId($insertData);

        } else {

            $result = DB::table('admin_modules')
                        ->where('adminId', $memberId)
                        ->where('moduleId', $moduleId)
                        ->delete();
        }

         return  $result;
    }

    function deleteAdmin($adminId)

    {


        $result = DB::table('admins')
        ->where('id', $adminId)
        ->delete();

        return $result;
    }

    function addAdmin($data)

    {

        extract($data);
        
        $admin_id = Auth::user()->id;

        $query1 = DB::select("select *  from   admins where uname = '" . $username . "'");

        $userRows1 =  count($query1);

        $query2 = DB::select("select *  from   admins where email = '" . $email . "'");

        $userRows2 =  count($query2);


        if ($userRows1 < 1 && $userRows2 < 1) {
            $password = bcrypt($password);

            $insertData = array(
                'name' => urlencode($name),
                'uname' => urlencode($username),
                'password' => $password,
                'email' => $email,
                'user_role' => $role,
                'created_at' => NOW(),
                'updated_at' => NOW(),
                'lastlogon' => NOW(),
                'addedby' => $admin_id,
            );

            $insertId = DB::table('admins')->insertGetId($insertData);

            if ($insertId > 0 && isset($modules) && $role == 2) {

                $count = count($modules);

                for ($i = 0; $i < $count; $i++) {

                    $insertData = array(
                        'adminId' => $insertId,
                        'moduleId' => $modules[$i],
                    );

                    $query = DB::table('admin_modules')->insertGetId($insertData);

                }
            }

            $result['success'] = $insertId;

            $result['type'] = 0;
        } else {

            $result['success'] = 0;

            if ($userRows1 > 0) {

                $result['type'] = 1;
            }

            if ($userRows2 > 0) {

                $result['type'] = 2;
            }
        }

        return $result;
    }

    // Admin module quesries starts



    // Labels module quesries starts

    function getNumLabels($where,$sort)

	{

        $query = DB::select("select client_contacts.* from  client_contacts LEFT JOIN clients ON client_contacts.client_id = clients.id $where order by $sort");  

        // echo $query->toSql(); die;

        $result = count($query);

       // dd($query);

		return $result;

	}

    function getLabels($where,$sort,$start,$limit){
      
  
        $query = DB::select("select client_contacts.* from  client_contacts LEFT JOIN clients ON client_contacts.client_id = clients.id $where  order by $sort limit $start, $limit");  
        
        $result['numRows'] = count($query);
        $result['data']  = $query;


        $result['query']  = "select client_contacts.*, clients.name from  client_contacts LEFT JOIN clients ON client_contacts.client_id = clients.id $where order by $sort  limit $start, $limit";
    
        foreach($result['data'] as $row)
        {
            $query1 = DB::select("select name from  clients where id = '". $row->client_id ."' ");  
    	    $result['client'][$row->id]  = $query1;
        }

        //dd($result);
          return $result;
    }


    function deleteLabel($did)
	{

            $result =  DB::select("delete from  client_contacts where id = '$did'");  

            return $result;

	}

    function addLabel($data)
    {

        extract($data);

        $email1 = '';

        if(isset($email[1]))

        {

        $email1 = $email[1];

        }

        $email2 = '';

        if(isset($email[2]))

        {

        $email2 = $email[2];

        }

        
        $phone1 = '';

        if(isset($phone[1]))

        {

        $phone1 = $phone[1];

        }

        $phone2 = '';

        if(isset($phone[2]))

        {

        $phone2 = $phone[2];

        }
        $admin_id = Auth::user()->id;
        $insertData = array(
            'client_id' => $client,
            'title' => urlencode($title),
            'name' => urlencode($name),
            'email' => $email[0],
            'phone' => urlencode($phone[0]),
            'deleted' => 0,
            'email1' => $email1,
            'mobilePhone' => urlencode($mobile),
            'email2' => $email2,
            'phone1' => urlencode($phone1),
            'phone2' => urlencode($phone2),
            'admin_id' =>  $admin_id,
        );

        $insertId = DB::table('client_contacts')->insertGetId($insertData);

       // dd($insertId);
        

       // return  $result;
        return  $insertId;

    
        }

        function getClients()
        {
    
		$query = DB::select("select id, name from  clients where active = 1 order by name");
    
       $result['numRows'] = count($query);
    
       $result['data']  = $query;
    
          return $result;
    
        }


    // Labels module quesries ends



    // Albums module quesries starts

    function deleteAlbum($albumId)
    {
        // SECURITY FIX: Use Query Builder to prevent SQL injection
        DB::table('tracks_album')->where('id', $albumId)->update(['deleted' => '1']);
        DB::table('tracks')->where('albumid', $albumId)->update(['deleted' => '1']);
        return 1;
    }

    function getNumAlbum($where, $sort)
    {   
        // $query = DB::table('tracks_album')
        //         ->Join('tracks', 'tracks.albumid', '=', 'tracks_album.id')
        //         ->leftJoin('clients', 'clients.id', '=', 'tracks.client')

        //         ->select(DB::raw('clients.uname, clients.name, tracks.id, tracks.artist, tracks.title, tracks.album, tracks.time, tracks.label, tracks.added, tracks.img, tracks.imgpage'));
        //         $query->where($where);
        //         $query->groupBy('tracks_album.id');
        //         $query->orderBy($sort);

        //         // echo $query->toSql(); die;        

        // $result_get = $query->get();       
        // $result = $result_get->count();
        // return  $result;

        // $query = DB::select("select tracks_album.id, clients.uname, clients.name,tracks.product_name, tracks.id, tracks.artist, tracks.title, tracks.album, tracks.time, tracks.label, tracks.added, tracks.img, tracks.imgpage from  tracks_album INNER JOIN tracks ON tracks_album.id = tracks.albumid LEFT JOIN clients on tracks.client = clients.id $where  order by $sort");  

        // $result = count($query);
        // // print_r($result);die;
        // return  $result;

        $query = DB::select("SELECT clients.uname, clients.name, tracks.id, tracks.artist,tracks.product_name, tracks.title, tracks.album, tracks.time, tracks.label, tracks.added, tracks.img, tracks.imgpage 
        FROM `tracks_album` 
        INNER JOIN `tracks` ON tracks_album.id = tracks.albumid 
        LEFT JOIN `clients` on tracks.client = clients.id 
        $where 
        GROUP BY tracks_album.id 
        ORDER BY $sort");
        $result = count($query);
        return  $result;
    }

    function getAlbums($where, $sort, $start, $limit)
    {
        // $query = DB::select("SELECT clients.uname, clients.name, tracks.id,tracks.product_name, tracks.artist, tracks.title, tracks.album, tracks.time, tracks.label, tracks.added, tracks.img, tracks.imgpage, tracks.albumid FROM tracks_album INNER JOIN tracks ON tracks_album.id = tracks.albumid 
        // LEFT JOIN clients on tracks.client = clients.id $where ORDER BY $sort LIMIT $start, $limit");
        // $result['numRows']  = count($query);
        // $result['data']  = $query;
        // //dd($result);
        // return  $result;

        $query = DB::select("SELECT clients.uname, clients.name, tracks.id, tracks.product_name, tracks.artist, tracks.title, tracks.album, tracks.time, tracks.label, tracks.added, tracks.img, tracks.imgpage, tracks.albumid,tracks.pCloudFileID
        FROM `tracks_album` 
        INNER JOIN `tracks` ON tracks_album.id = tracks.albumid 
        LEFT JOIN `clients` on tracks.client = clients.id 
        $where 
        GROUP BY tracks_album.id
        ORDER BY $sort 
        LIMIT $start, $limit");
        $result['numRows']  = count($query);
        $result['data']  = $query;
        return  $result;
    }

    function getStaffTracks($trackId)
    {
        $query = DB::select("select sortOrder from  staff_selection where trackId = '$trackId' order by sortOrder desc limit 1");
        return count($query);
    }

    function getYouTracks($trackId)
    {
        $query = DB::select("select sortOrder from  you_selection where trackId = '$trackId' order by sortOrder desc limit 1");
        return count($query);
    }

    function updateAlbum($id, $data)
    {
        extract($data);
        $admin_id = Auth::user()->id;
        if (!empty($album)) {
           $query =  DB::select("UPDATE `tracks_album`
                            SET `title` = '" . urlencode($album) . "', 
                                `edited` = NOW(), 
                                `editedby` = " . $admin_id . "
                            WHERE id = " . $id);
        }
        
    }

    function updateAlbumTracks($data = NULL, $albumId= NULL)
    {
        extract($data);
        $admin_id = Auth::user()->id;
        if (isset($logos) && count($logos) > 1) {
            $logos = implode(',', $logos);
        } else if (isset($logos) && count($logos) == 1) {
            $logos = $logos[0];
        } else {
            $logos = '';
        }
        if (!(isset($whiteLabel))) {
            $whiteLabel = 0;
        }
        if (!(isset($paid))) {
            $paid = 0;
        }
        if (!(isset($graphics))) {
            $graphics = 0;
        }
        if (!(isset($invoiced))) {
            $invoiced = 0;
        }
		
		if(!empty($subGenre)){
            $subGenre = $subGenre;
        }
        else{
            $subGenre = 0;
        }

        $query = DB::select("UPDATE `tracks`
                                    SET `artist` = '" . urlencode($artist) . "', 
                                        `album` = '" . urlencode($album) . "',
                                        `time` = '" . urlencode($time) . "',
                                        `label` = '" . urlencode($company) . "',
                                        `link` = '" . urlencode($website) . "',
                                        `moreinfo` = '" . urlencode($moreInfo) . "',
                                        `producer` = '" . urlencode($producers) . "',
										`writer` = '" . urlencode($writer) . "',
                                        `edited` = NOW(),
                                        `editedby` = '" . $admin_id . "',
                                        `active` = '" . $availableMembers . "',
                                        `review` = '" . $reviewable . "',
                                        `logos` = '" . $logos . "',
                                        `client` = '" . $client . "',
                                        `whitelabel` = '" . $whiteLabel . "',
                                        `videoURL` = '" . $video . "',
                                        `embedvideoURL` = '" . $embedlink . "',
                                        `graphicscomplete` =  '" . $graphics . "',
                                        `albumType` =  '" . $albumType . "',
                                        `link1` =  '" . $website1 . "',
                                        `link2` =  '" . $website2 . "',
                                        `bpm` =  '" . $bpm . "',
                                        `facebookLink` =  '" . $facebookLink . "',
                                        `twitterLink` =  '" . $twitterLink . "',
                                        `instagramLink` =  '" . $instagramLink . "',
                                        `genreId` =  '" . $genre . "',
                                        `subGenreId` =  '" . $subGenre . "' 
                                    WHERE `albumid` = '" . $albumId . "'");

        

        return 1;
    }

    function getAlbumTracks($where)
    {
        $query = DB::select("SELECT * FROM `tracks` $where");
        return $query;
    }

    function updatePageImage($trackId, $img)
    {
        $query =  DB::select("UPDATE `tracks` set  `imgpage` = '" . $img . "' where id = '" . $trackId . "'");
        return 1;
    }
    
    function updatePageImage_admin($trackId, $img, $pcloudFileId,$parentfolderid)
    {
        $query =  DB::select("UPDATE `tracks` set  `imgpage` = '" . $img . "'  , pCloudFileID ='" .$pcloudFileId. "' , pCloudParentFolderID ='".$parentfolderid."' where id = '" . $trackId . "'");
        return 1;
    }
    
    function updatePageImage_in_album($albumId, $img)
    {
        $query =  DB::select("UPDATE `tracks_album` set  `album_page_image` = '" . $img . "' where id = '" . $albumId . "'");
        return 1;
    }
    
     function updatePageImage_in_album_admin($albumId, $img,$pcloudFileId,$parentfolderid)
    {
        $query =  DB::select("UPDATE `tracks_album` set  `album_page_image` = '" . $img . "', pCloudFileID_album ='" .$pcloudFileId. "' , pCloudParentFolderID_album ='".$parentfolderid."' where id = '" . $albumId . "'");
        return 1;
    }

    function addMp3_album($trackId, $audioFile, $version, $preview, $bpm = NULL, $key = NULL)
    {
        $admin_id = Auth::user()->id;
        $insertData = array(
            'track' => $trackId,
            'type' => 'track',
            'version' => urlencode($version),
            'location' => urlencode($audioFile),
            'preview' => urlencode($preview),
            'added' => NOW(),
            'addedby' => $admin_id,
        );

        $insertId = DB::table('tracks_mp3s')->insertGetId($insertData);
        
        // $query =  DB::select("INSERT INTO `tracks_mp3s` (`track`, `type`, `version`, `location`, `preview`, `added`, `addedby`) VALUES ('" . $trackId . "', 'track', '" . urlencode($version) . "', '" . urlencode($audioFile) . "', '" . $preview . "', NOW(), '" . $_COOKIE['adminId'] . "')");


        return $insertId;
    }


    function addTrack($index, $albumId, $data)
    {
        extract($data);
        $admin_id = Auth::user()->id;
        if (!(isset($whiteLabel))) {
            $whiteLabel = 0;
        }
        if (!(isset($graphics))) {
            $graphics = 0;
        }
        if (isset($logos) && count($logos) > 1) {
            $logos = implode(',', $logos);
        } else if (isset($logos) && count($logos) == 1) {
            $logos = $logos[0];
        } else {
            $logos = '';
        }

        if(!empty($subGenre)){
            $subGenre = $subGenre;
        }
        else{
            $subGenre = 0;
        }

        $insertData = array(
            'albumType' => $albumType ,
            'album' => urlencode($album),
            'albumid' => $albumId ,
            'time' => urlencode($time) ,
            'label' => urlencode($company) ,
            'moreinfo' => urlencode($moreInfo) ,
            'producer' => urlencode($producers) ,
            'writer' => urlencode($writer),
             'added' => NOW() ,
            'addedby' => $admin_id ,
            'active' => $availableMembers ,
            'review' => $reviewable ,
            'client' => $client ,
            'whitelabel' => $whiteLabel ,
            'type' => 'track' ,
            'graphicscomplete' => $graphics ,
            'graphicscomplete_date' => NOW() ,
            'genreId' => $genre ,
            'subGenreId' => $subGenre ,
            'bpm' => $bpm ,
            'artist' => urlencode($artist) ,
            'title' => urlencode($title[$index]) ,
            'link' => $website ,
            'link1' => $website1 ,
            'link2' => $website2 ,
            'videoURL' => $video ,
            'facebookLink' => $facebookLink ,
            'twitterLink' => $twitterLink ,
            'instagramLink' => $instagramLink ,
            'logos'=> $logos ,

           

        );

        $insertId = DB::table('tracks')->insertGetId($insertData);

        // DB::select("INSERT INTO `tracks` (`albumType`, `album`, `albumid`, `time`, `label`, `moreinfo`, `producer`, `writer`,  `added`, `addedby`, `active`, `review`, `client`, `whitelabel`, `madeAvailable`, `type`,  `graphicscomplete`,`graphicscomplete_date`, `genreId`, `subGenreId`, `bpm`, `artist`, `title`, `link`, `link1`, `link2`, `videoURL`, `facebookLink`, `twitterLink`, `instagramLink`, `logos`) 
        //                             VALUES ('" . $albumType . "', '" . urlencode($album) . "', " . (int) $albumId . ", '" . urlencode($time) . "', '" . urlencode($company) . "', '" . urlencode($moreInfo) . "', '" . urlencode($producers) . "', '" . urlencode($writer) . "', NOW(), '" . $_COOKIE['adminId'] . "', '" . $availableMembers . "', '" . $reviewable . "', '" . $client . "', '" . $whiteLabel . "', '0', 'track', '" . $graphics . "', NOW(), '" . $genre . "', '" . $subGenre . "', '" . $bpm . "', '" . urlencode($artist) . "', '" . urlencode($title[$index]) . "', '" . $website . "', '" . $website1 . "', '" . $website2 . "', '" . $video . "', '" . $facebookLink . "', '" . $twitterLink . "', '" . $instagramLink . "', '" . $logos . "')");


        return $insertId;
    }

    function deleteTrack($trackId) {
        $trackFolder = base_path('AUDIO/');
        $imageFolder = base_path('ImagesUp/');

        $query = DB::select("SELECT * FROM `tracks_mp3s` WHERE `track` = ". $trackId);
        $trackMp3s = $query;

        $query = DB::select("SELECT * FROM `tracks` WHERE `id` = ". $trackId);
        $track = $query;

        try {
            if(!empty($track->imgpage)) {
                if(file_exists($imageFolder . $track->imgpage)) {
                    unlink($imageFolder . $track->imgpage);
                }
            }
    
            if(!empty($track->img)) {
                if(file_exists($imageFolder . $track->img)) {
                    unlink($imageFolder . $track->img);
                }
            }
    
            foreach($trackMp3s as $trackMp3) {
                if(file_exists($trackFolder . $trackMp3->location)) {
                    unlink($trackFolder . $trackMp3->location);
                }
            }
    
            DB::select("DELETE FROM `tracks_mp3s` WHERE `track` = ". $trackId);
            DB::select("DELETE FROM `tracks` WHERE `id` = ". $trackId);

            return 1;
        } catch (Exception $e) {
            return 0;
        }
    }

    function deleteTrackVersion($trackVersionId) {
        $trackFolder = base_path('AUDIO/');

        $query = DB::select("SELECT * FROM `tracks_mp3s` WHERE `id` = ". $trackVersionId);
        $trackMp3s = $query;

        try {
    
            foreach($trackMp3s as $trackMp3) {
                if(file_exists($trackFolder . $trackMp3->location)) {
                    unlink($trackFolder . $trackMp3->location);
                }
            }
    
            DB::select("DELETE FROM `tracks_mp3s` WHERE `id` = ". $trackVersionId);

            return 1;
        } catch (Exception $e) {
            return 0;
        }
    }

    function addEmailImage($trackId, $img)
    {
        $query = DB::select("UPDATE `tracks` set  `img` = '" . $img . "' where id = '" . $trackId . "'");
        return 1;
    }

   

    function getTrackMp3s($trackId)
    {
        $query = DB::select("select id, version, location, preview, downloads, num_plays from tracks_mp3s where track = '" . $trackId . "'");
        $result['numRows']  = count($query);
        $result['data']  = $query;
        return  $result;
    }

    function getClientsList()
    {
         $query = DB::select("select clients.name,clients.id from clients where NULLIF(LTRIM(RTRIM(clients.name)), '') IS NOT NULL AND clients.active = 1 GROUP BY clients.email  order by clients.name"); 
		 
		  /*  $query = DB::select("select tracks.client, clients.name,clients.id from  tracks left join clients on tracks.client=clients.id where NULLIF(LTRIM(RTRIM(clients.name)), '') IS NOT NULL GROUP BY clients.email  order by clients.name"); */ 
         
       /* $query = DB::select("select tracks.client, clients.name,clients.id from  tracks left join clients on tracks.client=clients.id where tracks.approved = 1  AND  NULLIF(LTRIM(RTRIM(clients.name)), '') IS NOT NULL GROUP BY clients.email  order by clients.name"); */
       
        // $query = DB::select("select  clients.name,clients.id from  clients  where clients.active = 1  AND  NULLIF(LTRIM(RTRIM(clients.name)), '') IS NOT NULL GROUP BY clients.email  order by clients.name");
        $result['numRows']  = count($query);
        $result['data']  = $query;
      
        return  $result;
    }

   function getMembersList()
   
    {
        $limit="20000";
        $query = DB::select("select  tracks.member, members.fname,members.id from  tracks left join members on tracks.member=members.id where tracks.approved = 1  AND  NULLIF(LTRIM(RTRIM(members.fname)), '') IS NOT NULL GROUP BY members.email  order by members.fname");
         
        $result['numRows']  = count($query);
        $result['mem_data']  = $query;
        // print_r($query);
        // die();
       
        return  $result;
    }
    function getLogos_album($where)
    {
        $query = DB::select("select id, company, img from  logos WHERE company !=''");
        $result['numRows'] = count($query);
        $result['data']  = $query;
        return $result;
    }

    function getTrack($where = NULL)
    {
        // echo $where;
        // $query = DB::select("select genres.genreId, genres.genre, genres_sub.subGenreId, genres_sub.subGenre, clients.uname, clients.name, clients.id, tracks.id, tracks.artist, tracks.title, tracks.album, tracks.time, tracks.label, tracks.link, tracks.moreinfo, tracks.producer, tracks.img, tracks.imgpage, tracks.added, tracks.addedby, tracks.edited, tracks.editedby, tracks.active, tracks.review, tracks.logos, tracks.client, tracks.whitelabel, tracks.approved, tracks.videoURL, tracks.madeAvailable, tracks.paid, tracks.invoiced, tracks.graphicscomplete, tracks.bpm,tracks.skey, tracks.albumType, tracks.link1, tracks.link2, tracks.facebookLink, tracks.twitterLink, tracks.instagramLink from  tracks
        $query = DB::select("select genres.genreId, genres.genre, genres_sub.subGenreId, genres_sub.subGenre, clients.uname, clients.name, clients.id, tracks.id, tracks.artist, tracks.featured_artist_1, tracks.featured_artist_2, tracks.title, tracks.album, tracks.bpm, tracks.albumid, tracks.type, tracks.time, tracks.label, tracks.link, tracks.moreinfo,tracks.notes, tracks.producer, tracks.writer, tracks.img, tracks.imgpage, tracks.added, tracks.addedby, tracks.edited, tracks.editedby, tracks.active, tracks.review, tracks.logos, tracks.client, tracks.whitelabel, tracks.approved,tracks.contact_name,tracks.contact_email,tracks.contact_phone,tracks.relationship_to_artist,tracks.feedback1_contact_email,tracks.feedback2_contact_email,tracks.feedback3_contact_email, tracks.videoURL,tracks.embedvideoURL,tracks.madeAvailable, tracks.paid, tracks.invoiced, tracks.graphicscomplete, tracks.albumType, tracks.link1, tracks.link2, tracks.facebookLink, tracks.twitterLink, tracks.instagramLink, tracks.tiktokLink, tracks.snapchatLink, tracks.othersLink, tracks.status, tracks.memberPreviewAvailable , tracks.songkey , tracks.coverimage,tracks.pCloudFileID, tracks.pCloudParentFolderID,tracks.pCloudFileID_cover from tracks
		  left join clients on tracks.client = clients.id
		  left join genres on tracks.genreId = genres.genreId
          left join genres_sub on tracks.subGenreId = genres_sub.subGenreId
		  $where");
        $result['numRows']  = count($query);
        $result['data']  = $query;
        return  $result;
    }


    function addPageImage($trackId, $img)
    {
        $query = DB::select("UPDATE `tracks` set  `imgpage` = '" . $img . "' where id = '" . $trackId . "'");
        return 1;
    }
    
    
     function addPageImage_admin($trackId, $img, $pcloudFileId, $parentfolderid)
    {
        $query = DB::select("UPDATE `tracks` set  `imgpage` = '" . $img . "' , pCloudFileID ='" .$pcloudFileId. "' , pCloudParentFolderID ='".$parentfolderid."' where id = '" . $trackId . "'");
        return 1;
    }

    function getSearchClients($where)
    {
        $query = DB::select("select id, name from  clients $where order by name");
        $result['numRows']  =  count($query);
        $result['data']  = $query;
        return  $result;
    }

    function addAlbum($data)
    {
        extract($data);

        $insertId = 0;
        $admin_id = Auth::user()->id;
        $insertData = array(
            'title' => urlencode($album),
            'added' => NOW(),
            'addedby' => $admin_id,
        );

        $insertId = DB::table('tracks_album')->insertGetId($insertData);

        // $albumId = 0;

        // if (!empty($album)) {
        //     $this->db->query("INSERT INTO `tracks_album` (`title`, `added`, `addedby`) VALUES ('" . urlencode($album) . "', NOW(), '" . $_SESSION['adminId'] . "')");
        //     $albumId = $this->db->insert_id();
        // }

        return $insertId;
    }

	function getGenres_album()
    {
        $query = DB::select("select * from genres order by genre");
        $result['numRows'] = count($query);
        $result['data']  = $query;
        return $result;
    }


     // New functions R-S
     function deleteTrack_trm($trackId)
     {
          DB::select("update tracks set deleted = '1' where id = '" . $trackId . "'");
         return 1;
     }
 
     function changePriority_trm($check,$trackid)
     {
         if($check=='true'){
             $check=1;
         }else{
             $check=0;
         }
         $query =  DB::select("UPDATE `tracks` set  `priority` = '" . $check . "' where id = '" . $trackid . "'");
         return $query;
     }
 
     function changeHottest_trm($check,$trackid)
     {
         if($check=='true'){
             $check=1;
         }else{
             $check=0;
         }
         $query =  DB::select("UPDATE `tracks` set  `hottest` = '" . $check . "' where id = '" . $trackid . "'");
         return $query;
     }
 
     function changeTopStreaming_trm($check,$trackid)
     {   
         if($check=='true'){
             
             $query =  DB::select("select * from  top_streaming_tracks  where trackId = '$trackid'");
             if(count($query)==0){
 
                 $max = DB::table('top_streaming_tracks')->max('position');
                 
                 // $this->db->select_max('position');
                 // $max = $this->db->get('top_streaming_tracks')->row();  
                 $max= $max+1;
 
                 $insertData = array(
                     'trackId' => $trackid,
                     'position' => $max,
                 );
         
                 $insertId = DB::table('top_streaming_tracks')->insertGetId($insertData);
                 return $insertId;
             }else{
                 return "Track already in Top Streaming";
             }
             
             
         }else{
             $result =  DB::select("delete from top_streaming_tracks where trackId = '$trackid'");
             return $result;
         }
     }
   
     function changeTopStreamingOrder_trm($order){
         $countt=0;
         $order=explode(',',$order);
 
         foreach($order as $a){
             $query =  DB::select("UPDATE `top_streaming_tracks` set  `position` = '" . $countt . "' where trackId = '" . $a . "'");
             $countt++;
         }
         
     }
 
     function changeLogosOrder_trm($order,$a){
         $query =  DB::select("UPDATE `tracks` set  `logos` = '" . $order . "' where id = '" . $a . "'");
         return 1;
     }
 
     
     function assignStaff_trm($trackId)
     {
         $query =  DB::select("select sortOrder from  staff_selection order by sortOrder desc limit 1");
         $result = $query;
         if (isset($result[0]->sortOrder)) {
             $sortOrder = $result[0]->sortOrder + 1;
         } else {
             $sortOrder = 1;
         }
 
         $insertData = array(
             'trackId' => $trackId,
             'sortOrder' => $sortOrder,
             'addedOn' => NOW(),
         );
 
         $insertId = DB::table('staff_selection')->insertGetId($insertData);
         return $insertId;
     }
 
     function removeStaff_trm($trackId)
     {
         $query =  DB::select("DELETE FROM staff_selection WHERE trackId = '$trackId'");
         return $query;
     }
 
     function assignYou_trm($trackId)
     {
         $query =  DB::select("select sortOrder from  you_selection order by sortOrder desc limit 1");
         $result = $query;
         if (isset($result[0]->sortOrder)) {
             $sortOrder = $result[0]->sortOrder + 1;
         } else {
             $sortOrder = 1;
         }
 
         $insertData = array(
             'trackId' => $trackid,
             'sortOrder' => $sortOrder,
             'addedOn' => NOW(),
         );
 
         $insertId = DB::table('you_selection')->insertGetId($insertData);
        
         return $insertId;
     }
 
     function removeYou_trm($trackId)
     {
         $query =  DB::select("DELETE FROM you_selection WHERE trackId = '$trackId'");
         return $query;
     }
 
     function getNumTracks_trm($where, $sort)
     {
         $query =  DB::select("select tracks.id from  tracks
           left join clients on tracks.client = clients.id left join members on tracks.member=members.id
            $where order by $sort");
         $result = count($query);
         return  $result;
     }
 
     function getTracks_trm($where, $sort, $start, $limit)
     {
         //echo "<pre>"; print_r($where);die;
        //  $query =  DB::select("select clients.uname, clients.name, tracks.id, tracks.priority, tracks.hottest, tracks.artist,tracks.producer, tracks.title, tracks.featured_artist_1, tracks.featured_artist_2, tracks.album, tracks.time, tracks.label, tracks.added, tracks.img, tracks.imgpage from  tracks 
        //   left join clients on tracks.client = clients.id
        //   $where order by $sort limit $start, $limit");
        if($_SERVER['REMOTE_ADDR'] == '106.220.108.140'){
           // echo "<pre>"; print_r("select clients.uname, clients.name,members.fname, tracks.id, tracks.priority, tracks.hottest, tracks.artist,tracks.producer, tracks.title, tracks.featured_artist_1, tracks.featured_artist_2, tracks.album, tracks.time, tracks.label, tracks.added, tracks.img, tracks.imgpage, tracks.albumid, tracks_album.album_page_image, tracks.order_position, tracks.pCloudFileID, tracks.pCloudParentFolderID from tracks left join clients on tracks.client = clients.id  left join members on tracks.member = members.id left join tracks_album on tracks.albumid = tracks_album.id $where order by $sort limit $start, $limit");die;
        }
        
          $query =  DB::select("select clients.uname, clients.name,members.fname, tracks.id, tracks.priority, tracks.hottest, tracks.artist,tracks.producer, tracks.title, tracks.featured_artist_1, tracks.featured_artist_2, tracks.album, tracks.time, tracks.label, tracks.added, tracks.img, tracks.imgpage, tracks.albumid, tracks_album.album_page_image, tracks.order_position, tracks.pCloudFileID, tracks.pCloudParentFolderID from  
        tracks left join clients on tracks.client = clients.id  left join members on tracks.member = members.id 
        left join tracks_album on tracks.albumid = tracks_album.id 
        $where order by $sort limit $start, $limit");
        
        // echo '<pre>';
        // print_r($query);
        // die;
         $result['numRows']  = count($query);
         $result['data']  = $query;
         return  $result;
     }
 
     function getAllIds_trm(){
         $query =  DB::select("select trackId  from top_streaming_tracks");
         $result=$query;
         $list=array();
         foreach($result as $t){
             array_push($list,$t->trackId);
         } 
         return $list;
         
     }
 
     function getReviews_trm($trackId)
     {
         $query =DB::select("select * from tracks_reviews where track = '" . $trackId . "'");
         $result['numRows']  = count($query);
        // dd(  $result['numRows'] );
         $result['data']  = $query;
         return  $result;
     }
 
     function getLogos_trm($where)
     {   
         $query = DB::select("select id, company, img, pCloudFileID_logo, pCloudParentFolderID_logo from  logos $where");
         $result['numRows'] =count($query);
         $result['data']  = $query;
         return $result;
     }
 
     function deleteTrackAudio_trm($did)
     {
         $result = DB::select("delete from tracks_mp3s where id = '$did'");
         return $result;
     }
 
     function updateMp3s_trm($data, $trackId)
     {
         for ($i = 1; $i < $data['numMp3s']; $i++) {
             if (isset($data['preview']) && $data['preview'] == $data['mp' . $i]) {
                 $setPreview = 1;
             } else {
                 $setPreview = 0;
             }
             $query =  DB::select("update `tracks_mp3s` set version = '" . addslashes($data['version' . $i]) . "', preview = '" . addslashes($setPreview) . "' where id = '" . $data['mp' . $i] . "' and track = '" . $trackId . "'");
         }
         return 1;
     }
 
     function getReleaseTypes_trm()
     {
         $query = DB::select("select * from release_type Where status = 1");
         return $query;
     }
 
     function updateCoverImage_trm($trackId, $img,  $pcloudFileId,$parentfolderid)
     {
         $query = DB::select("UPDATE `tracks` set  `coverimage` = '" . $img . "'  , pCloudFileID_cover ='" .$pcloudFileId. "' , pCloudParentFolderID_cover ='".$parentfolderid."' where id = '" . $trackId . "'");
         return 1;
     }
 
     function addLogo_trm($company, $url)
     {
        $admin_id = Auth::user()->id;
         $insertData = array(
             'company' => $company,
             'url' => $url,
             'added' => NOW(),
             'addedby' => $admin_id,
         );
 
         $insertId = DB::table('logos')->insertGetId($insertData);
         return  $insertId;
     }
 
     function addLogoImage_trm($logoId, $image)
     {
         $query = DB::select("update logos set img = '" . $image . "' where id = '" . $logoId . "'");
         return $query;
     }
     
      function addLogoImage_trm_admin($logoId, $image,$pcloudFileId,$parentfolderid)
     {
         $query = DB::select("update logos set img = '" . $image . "' , pCloudFileID_logo ='" .$pcloudFileId. "' , pCloudParentFolderID_logo ='".$parentfolderid."' where id = '" . $logoId . "'");
         return $query;
     }
 
     function updateTrack_trm($data, $trackId)
     {
         extract($data);
         $admin_id = Auth::user()->id;
         if (isset($logos) && count($logos) > 1) {
             $logos = implode(',', $logos);
         } else if (isset($logos) && count($logos) == 1) {
             $logos = $logos[0];
         } else {
             $logos = '';
         }
         if (!(isset($whiteLabel))) {
             $whiteLabel = 0;
         }
         if (!(isset($paid))) {
             $paid = 0;
         }
         if (!(isset($graphics))) {
             $graphics = 0;
         }
         if (!(isset($invoiced))) {
             $invoiced = 0;
         }
 
         if(!empty($memberPreviewAvailable)){
             $memberPreviewAvailable = $memberPreviewAvailable;
         }
         else{
 
             $memberPreviewAvailable = '';
 
         }
 
         if(!empty($subGenreId)){
             $subGenreId = $subGenreId;
         }
         else{
 
             $subGenreId = '';
 
         }
 
 
         $chk_qry = DB::select("SELECT id FROM `tracks` WHERE artist = '" . urlencode($artist) . "' AND title = '" . urlencode($title) . "' AND id !=  '" .$trackId."' AND deleted=0");
 
         $trcks = count($chk_qry);
         if($trcks > 0){
             return 'track_exists';
         }
 
         $query = DB::select("update `tracks` set artist = '" . addslashes(urlencode($artist)) . "', title = '" . addslashes(urlencode($title)) . "', featured_artist_1 = '" . urlencode($featured_artist_1) . "', featured_artist_2 = '" . urlencode($featured_artist_2) . "', album = '" . urlencode($album) . "', time = '" . urlencode($time) . "', label = '" . urlencode($company) . "', link = '" . urlencode($website) . "',
         moreinfo = '" . urlencode($moreInfo) . "',
         notes = '" . urlencode($notes) . "',
         producer = '" . urlencode($producers) . "', writer = '" . urlencode($writer) . "', edited = NOW(), editedby = '" . $admin_id . "', active = '" . $availableMembers . "', review = '" . $reviewable . "', logos = '" . $logos . "', whitelabel = '" . $whiteLabel . "',  videoURL = '" . $video . "', embedvideoURL = '" . $embedlink . "', graphicscomplete =  '" . $graphics . "', albumType =  '" . $albumType . "', link1 =  '" . $website1 . "', link2 =  '" . $website2 . "', bpm =  '" . $bpm . "', facebookLink =  '" . $facebookLink . "', twitterLink =  '" . $twitterLink . "', instagramLink =  '" . $instagramLink . "', tiktokLink =  '" . $tiktokLink . "', snapchatLink =  '" . $snapchatLink . "', othersLink =  '" . $othersLink . "', genreId =  '" . $genre . "', status = '" .$status."', memberPreviewAvailable = '" .$memberPreviewAvailable."', contact_name = '" .addslashes($contact_name)."', contact_email = '" .$contact_email."', contact_phone = '" .$contact_phone."', relationship_to_artist = '" .$relationship_to_artist."',feedback1_contact_email = '" .$second_contact_email."', feedback2_contact_email = '" .$third_contact_email."', feedback3_contact_email = '" .$fourth_contact_email."', songkey = '" .$songkey."', client = '".$client."' where id = '" . $trackId . "'");

         // $query = DB::select("update `tracks` set artist = '" . urlencode($artist) . "', title = '" . urlencode($title) . "', featured_artist_1 = '" . urlencode($featured_artist_1) . "', featured_artist_2 = '" . urlencode($featured_artist_2) . "', album = '" . urlencode($album) . "', time = '" . urlencode($time) . "', label = '" . urlencode($company) . "', link = '" . urlencode($website) . "',
         // moreinfo = '" . urlencode($moreInfo) . "',
         // notes = '" . urlencode($notes) . "',
         // producer = '" . urlencode($producers) . "', writer = '" . urlencode($writer) . "', edited = NOW(), editedby = '" . $_COOKIE['adminId'] . "', active = '" . $availableMembers . "', review = '" . $reviewable . "', logos = '" . $logos . "', client = '" . $client . "', whitelabel = '" . $whiteLabel . "',  videoURL = '" . $video . "', graphicscomplete =  '" . $graphics . "', albumType =  '" . $albumType . "', link1 =  '" . $website1 . "', link2 =  '" . $website2 . "', bpm =  '" . $bpm . "', facebookLink =  '" . $facebookLink . "', twitterLink =  '" . $twitterLink . "', instagramLink =  '" . $instagramLink . "', genreId =  '" . $genre . "', subGenreId =  '" . $subGenre . "', status = '" .$status."', memberPreviewAvailable = '" .$memberPreviewAvailable."', contact_name = '" .$contact_name."', contact_email = '" .$contact_email."', contact_phone = '" .$contact_phone."', relationship_to_artist = '" .$relationship_to_artist."', songkey = '" .$songkey."' where id = '" . $trackId . "'");
 
         return 1;
     }
 
     function updateEmailImage_trm($trackId, $img)
     {
         $query = DB::select("UPDATE `tracks` set  `img` = '" . $img . "' where id = '" . $trackId . "'");
         return 1;
     }
 
     function getTrackReps_trm($trackId, $clientId)
     {
         $query = DB::select("select track_label_reps.label_rep_id, client_contacts.name, client_contacts.email from track_label_reps 
         left join client_contacts on track_label_reps.label_rep_id = client_contacts.id
         where track_label_reps.client_id = '" . $clientId . "' and track_label_reps.track_id = '" . $trackId . "'");
         $result['numRows']  = count($query);
         $result['data']  = $query;
         return  $result;
     }
 
     function addTrackupdate_trm($data, $track_id)
     {
         extract($data);
         $admin_id = Auth::user()->id;
 
         if(!empty($whiteLabel)){
 
             $whiteLabel = $whiteLabel;
         }
         else{
             $whiteLabel = 0;
         }
 
         if(!empty($graphics)){
 
             $graphics = $graphics;
         }
         else{
             $graphics = 0;
         }
 
         if(!empty($client)){
 
             $client = $client;
         }
         else{
             $client = 0;
         }
         
            if(!empty($subGenre)){
             $subGenre = $subGenre;
         }
         else{
             $subGenre = 0;
         }
 
       /*  $query = DB::select("UPDATE `tracks` SET artist = '" . $artist . "', featured_artist_1 = '" .  $featured_artist_1 . "', featured_artist_2 = '" .  $featured_artist_2 . "', title = '" .  $title . "', albumType = '" . $albumType . "', album = '" . $album . "', time = '" . $time . "', label = '" . $company . "', moreinfo = '" . $moreInfo . "', producer = '" . $producers . "', writer = '" . $writer . "', addedby = '" . $_COOKIE['adminId'] . "', active = '" . $availableMembers . "', review = '" . $reviewable . "', client = '" . $client . "', whitelabel = '" . $whiteLabel . "', type = '" . $type . "', graphicscomplete = '" . $graphics . "', genreId = '" . $genre . "', subGenreId = '" . $subGenre . "', bpm = '" . $bpm . "', status = '" . $status . "'  WHERE id = '" . $track_id . "'"); */
       
          $query = DB::select('UPDATE `tracks` SET artist = "'.$artist .'", featured_artist_1 = "'. $featured_artist_1  .'", featured_artist_2 = "'.  $featured_artist_2 .'", title = "'.  $title .'", albumType = "'. $albumType .'", album = "'. $album .'", time = "'.$time .'", label = "'. $company .'", moreinfo = "'. $moreInfo .'", producer = "'. $producers .'", writer = "'. $writer .'", addedby = "'. $admin_id.'", active = "'. $availableMembers .'", review = "'. $reviewable .'", client = "'. $client . '", whitelabel = "'. $whiteLabel .'", type = "'. $type .'", graphicscomplete = "'. $graphics .'", genreId = "'. $genre .'", subGenreId = "'. $subGenre .'", bpm ="'. $bpm .'", status = "'. $status .'"  WHERE id = "'. $track_id.'"');      
         return $query;
         
     }
 
     function addCoverImage_trm($trackId, $img, $pcloudFileId, $parentfolderid)
     {
        
         $query = DB::select("UPDATE `tracks` set  `coverimage` = '" . $img . "' , pCloudFileID_cover ='" .$pcloudFileId. "' , pCloudParentFolderID_cover ='".$parentfolderid."' where id = '" . $trackId . "'");
         return 1;
     }
 
 
     function addTrack_trm($data)
     {
        $admin_id = Auth::user()->id;
         extract($data);
       // echo "<pre>"; print_r($data); die;
         if (!(isset($whiteLabel))) {
             $whiteLabel = 0;
         }
         if (!(isset($graphics))) {
             $graphics = 0;
         }
 
         $albumId = 0;
/*          $chk_qry = DB::select("SELECT id FROM `tracks` WHERE artist = '" . urlencode($artist) . "' AND title = '" . urlencode($title) . "' AND deleted=0");
         $trcks = count($chk_qry);
         
         if($trcks > 0){
             return 'track_exists';
         } */
         // Inserting Album
         if ($type == 'album') {
             $insertData = array(
                 'title' => urlencode($album)  ,
                 'added' => OW(),
                 'addedby' => $admin_id ,
 
             );
     
             $insertIdd = DB::table('tracks_album')->insertGetId($insertData);
             $albumId = $insertIdd;
         }
         
         
        // $albumId = ((int) $albumId > 0) ? (int) $albumId : 'NULL';
 
         //	if(!(isset($invoiced))) { $invoiced = 0; } 	if(!(isset($paid))) { $paid = 0; }
 
         if(!empty($client)){
             $client = $client;
         }
         else{
             $client = 0;
         }
 
         if(!empty($subGenre)){
             $subGenre = $subGenre;
         }
         else{
             $subGenre = 0;
         }
 
         if(!empty($priorityType)){
             $priorityType = $priorityType;
         }
         else{
             $priorityType = '';
         }
 
         if(!empty($memberPreviewAvailable)){
             $memberPreviewAvailable = $memberPreviewAvailable;
         }
         else{
             $memberPreviewAvailable = '';
         }
 
 
         $insertData = array(
             'albumType' => $albumType ,
             'album' => urlencode($album),
             'albumid' => $albumId ,
             'time' => urlencode($time) ,
             'label' => urlencode($company) ,
             'moreinfo' => urlencode($moreInfo) ,
             'producer' => urlencode($producers) ,
             'writer' => urlencode($writer),
              'added' => NOW() ,
             'addedby' => $admin_id ,
             'active' => $availableMembers ,
             'review' => $reviewable ,
             'client' => $client ,
             'whitelabel' => $whiteLabel ,
             'type' => 'track' ,
             'graphicscomplete' => $graphics ,
             'graphicscomplete_date' => NOW() ,
             'genreId' => $genre ,
             'subGenreId' => $subGenre ,
             'bpm' => $bpm ,
             'songkey' => $songkey ,
             'artist' => urlencode($artist) ,
             'title' => urlencode($title) ,
 
              'featured_artist_1' => urlencode($featured_artist_1) ,
             'featured_artist_2' => urlencode($featured_artist_2) ,
              'priorityType' => urlencode($priorityType) ,
               'notes' => urlencode($notes) ,
               'status' => urlencode($status) ,
               'memberPreviewAvailable' => urlencode($memberPreviewAvailable) ,
               'contact_name' => urlencode($contact_name) ,
               'contact_email' => urlencode($contact_email) ,
               'contact_phone' => urlencode($contact_phone) ,
               'relationship_to_artist' => urlencode($relationship_to_artist) ,			   			   			   'feedback1_contact_email' => urlencode($second_contact_email) ,               'feedback2_contact_email' => urlencode($third_contact_email) ,               'feedback3_contact_email' => urlencode($fourth_contact_email) ,
 
 
         );
 
         $insertId = DB::table('tracks')->insertGetId($insertData);
 
       
         // $query = DB::select("INSERT INTO `tracks` (`artist`,`title`,`featured_artist_1`,`featured_artist_2`,`albumType`,`priorityType`, `album`, `albumid`, `time`, `label`, `moreinfo`, `notes`, `producer`, `writer`,  `added`, `addedby`, `active`, `review`, `client`, `whitelabel`, `madeAvailable`, `type`,  `graphicscomplete`,`graphicscomplete_date`, `genreId`, `subGenreId`, `bpm`, `status`,`memberPreviewAvailable`,`contact_name`,`contact_email`,`contact_phone`,`relationship_to_artist`,`songkey`) VALUES ('" . urlencode($artist) . "', '" . urlencode($title) . "','" . urlencode($featured_artist_1) . "','" . urlencode($featured_artist_2) . "', '" . $albumType . "', '" . urlencode($priorityType) . "','" . urlencode($album) . "', " . $albumId . ", '" . urlencode($time) . "', '" . urlencode($company) . "',
         // '" . urlencode($moreInfo) . "',
         // '" . urlencode($notes) . "',
         // '" . urlencode($producers) . "',
         // '" . urlencode($writer) . "', NOW(), '" . $_SESSION['adminId'] . "', '" . $availableMembers . "', '" . $reviewable . "', '" . $client . "', '" . $whiteLabel . "', '0', '" . $type . "', '" . $graphics . "', NOW(), '" . $genre . "', '" . $subGenre . "', '" . $bpm . "', '" . urlencode($status) . "','" . urlencode($memberPreviewAvailable) . "','" . urlencode($contact_name) . "','" . urlencode($contact_email) . "','" . urlencode($contact_phone) . "','" . urlencode($relationship_to_artist) . "','" . urlencode($songkey) . "')");
        
        // SECURITY FIX: Use Query Builder to prevent SQL injection
        DB::table('tracks')->where('id', $insertId)->update(['order_position' => $insertId]);
         return $insertId;
     }
 
     function addTrack1_trm($data, $track_id)
     {
         extract($data);
         if (isset($logos) && count($logos) > 1) {
             $logos = implode(',', $logos);
         } else if (isset($logos) && count($logos) == 1) {
             $logos = $logos[0];
         } else {
             $logos = '';
         }
          $query = DB::select("UPDATE `tracks` SET link = '" . $website . "', link1 = '" . $website1 . "', link2 = '" . $website2 . "', videoURL = '" . $video . "', embedvideoURL = '" . $embedlink . "', facebookLink = '" . $facebook . "', twitterLink = '" . $twitter . "', instagramLink = '" . $instagram . "', tiktokLink = '" . $tiktok . "', snapchatLink = '" . $snapchat . "', othersLink = '" . $others . "', logos = '" . $logos . "' WHERE id = '" . $track_id . "'");
 
         return $query;
     }
 
     function deleteSubmittedTrack_trm($trackId)
     {
         DB::select("update tracks_submitted set deleted = '1' where id = '" . $trackId . "'");
         return 1;
     }
 
     
     function getSubmittedTracks_trm($where, $start, $limit)
     {
         $query =DB::select("select genres.genreId,members.fname, genres.genre, genres_sub.subGenreId, genres_sub.subGenre, clients.uname, tracks_submitted.id, tracks_submitted.artist, tracks_submitted.title, tracks_submitted.producers, tracks_submitted.time, tracks_submitted.bpm, tracks_submitted.link, tracks_submitted.moreinfo, tracks_submitted.approved, tracks_submitted.added, tracks_submitted.deleted, tracks_submitted.album, tracks_submitted.imgpage, tracks_submitted.releasedate, tracks_submitted.amr1, tracks_submitted.amr2, tracks_submitted.amr3, tracks_submitted.amr4, tracks_submitted.version1, tracks_submitted.version2, tracks_submitted.version3, tracks_submitted.version4, tracks_submitted.label, tracks_submitted.genreId, tracks_submitted.subGenreId, tracks_submitted.albumType, tracks_submitted.link1, tracks_submitted.link2, tracks_submitted.facebookLink, tracks_submitted.twitterLink, tracks_submitted.instagramLink, tracks_submitted.contact_email, tracks_submitted.videoURL, tracks_submitted.embedvideoURL, tracks_submitted.pCloudFileID, tracks_submitted.pCloudParentFolderID  from  tracks_submitted 
          
          left join clients on tracks_submitted.client = clients.id
          left join members on tracks_submitted.member= members.id
          left join genres on tracks_submitted.genreId = genres.genreId
          left join genres_sub on tracks_submitted.subGenreId = genres_sub.subGenreId
          $where 
          order by tracks_submitted.id desc limit $start, $limit");
         $result['numRows']  = count($query);
         $result['data']  = $query;
         return  $result;
     }

     function getSubmittedTracksVersions_trm($where, $start, $limit)
     {

         $query =DB::select("SELECT tracks.*, tracks_submitted_versions.version_name, clients.uname FROM tracks JOIN tracks_submitted_versions ON (tracks.id = tracks_submitted_versions.track_id) LEFT JOIN clients on tracks.client = clients.id $where GROUP BY tracks.id ORDER BY tracks.id DESC limit $start, $limit");
         $result['numRows']  = count($query);
         $result['data']  = $query;
         return  $result;
     }

     function getSubmittedVersionsForTrack($where)
     {

         $query =DB::select("SELECT tracks.*, tracks_submitted_versions.id AS submitted_version_id, tracks_submitted_versions.version_name, tracks_submitted_versions.pcloud_fileId, clients.* FROM tracks JOIN tracks_submitted_versions ON (tracks.id = tracks_submitted_versions.track_id) LEFT JOIN clients on tracks.client = clients.id $where ORDER BY tracks_submitted_versions.id DESC");
         $result['numRows']  = count($query);
         $result['data']  = $query;
        //  dd($result);
         return  $result;
     }     
 
     function approveSubTrack_trm($trackSubId)
     {
         $approveQuery =DB::select("update tracks_submitted set approved = '1' where approved = '0' and id = '$trackSubId'");
         
         $query =DB::select("select * from  tracks_submitted where id = '$trackSubId' order by id desc");
         $result['numRows'] = count($query);
         $result['data']  = $query;
 
         if ($result['numRows'] > 0) {
 
             $release_date = explode(' ', $result['data'][0]->releasedate);
 
             $insertData = array(
 
                 'albumType' =>  $result['data'][0]->albumType ,
                 'album' =>  $result['data'][0]->album,
                 'time' =>  $result['data'][0]->time ,
                 'label' =>  $result['data'][0]->label ,
                 'moreinfo' =>  $result['data'][0]->moreinfo ,
                 'producer' =>  $result['data'][0]->producers ,
                 'added' =>  NOW(),
                 'active' =>  1 ,
                 'review' =>  0,
                 'client' =>  $result['data'][0]->client ,
                 'member' => $result['data'][0]->member,
                 'genreId' =>  $result['data'][0]->genreId ,
                 'subGenreId' =>  $result['data'][0]->subGenreId ,
                 'bpm' =>  $result['data'][0]->bpm ,
                 'artist' =>  $result['data'][0]->artist ,
                 'title' =>  $result['data'][0]->title ,
                 'link' =>  $result['data'][0]->link ,
                 'link1' =>  $result['data'][0]->link1 ,
                 'link2' =>  $result['data'][0]->link2 ,
                 'facebookLink' =>  $result['data'][0]->facebookLink ,
                 'twitterLink' =>  $result['data'][0]->twitterLink ,
                 'instagramLink' =>  $result['data'][0]->instagramLink,                 
                 'videoURL' =>  $result['data'][0]->videoURL ,
                 'embedvideoURL' =>  $result['data'][0]->embedvideoURL,
                 'contact_email' =>  $result['data'][0]->contact_email,
                 'imgpage'=>  $result['data'][0]->imgpage ,
                 'thumb'=>  $result['data'][0]->thumb ,
                 'approved'=>  $result['data'][0]->approved ,
                 'deleted'=>  0 ,
                 'edited' =>  NOW() ,
                 'madeAvailable'=>  NOW(),
                 'trackSubmittedId'=>  $trackSubId ,
                 'release_date'=>   $release_date[0] ,
                 'pCloudFileID'=> $result['data'][0]->pCloudFileID,
                  'pCloudParentFolderID'=> $result['data'][0]->pCloudParentFolderID,
                  'status'=>'publish',
 
     
     
     
                 );
     
             $trackId = DB::table('tracks')->insertGetId($insertData);
             
             DB::select("update tracks set order_position = '$trackId' where id = '$trackId'");
 
             // $query =DB::select("insert into tracks (`artist`, `title`, `albumType`, `album`, `time`, `label`, `link`, `link1`, `link2`, `moreinfo`, `producer`, `imgpage`, `thumb`, `added`,  `edited`,  `active`, `review`, `client`, `approved`, `deleted`, `madeAvailable`, `trackSubmittedId`, `release_date`, `genreId`, `subGenreId`, `bpm`, `facebookLink`, `twitterLink`, `instagramLink`) values ('" . $result['data'][0]->artist . "', '" . $result['data'][0]->title . "', '" . $result['data'][0]->albumType . "', '" . $result['data'][0]->album . "', '" . $result['data'][0]->time . "', '" . $result['data'][0]->label . "', '" . $result['data'][0]->link . "', '" . $result['data'][0]->link1 . "', '" . $result['data'][0]->link2 . "', '" . $result['data'][0]->moreinfo . "', '" . $result['data'][0]->producers . "', '" . $result['data'][0]->imgpage . "', '" . $result['data'][0]->thumb . "', NOW(), NOW(), '1', '0', '" . $result['data'][0]->client . "', '" . $result['data'][0]->approved . "', '0', NOW(), '" . $trackSubId . "', '" . $release_date[0] . "', '" . $result['data'][0]->genreId . "', '" . $result['data'][0]->subGenreId . "', '" . $result['data'][0]->bpm . "', '" . $result['data'][0]->facebookLink . "', '" . $result['data'][0]->twitterLink . "', '" . $result['data'][0]->instagramLink . "')");
             // $trackId = $this->db->insert_id();
 
             $preview1 = 1;
             $preview2 = 0;
             $preview3 = 0;
             $preview4 = 0;
             if (strcmp($result['data'][0]->version2, 'Clean') == 0) {
                 $preview1 = 0;
                 $preview2 = 1;
                 $preview3 = 0;
                 $preview4 = 0;
             } else if (strcmp($result['data'][0]->version3, 'Clean') == 0) {
                 $preview1 = 0;
                 $preview2 = 0;
                 $preview3 = 1;
                 $preview4 = 0;
             } else if (strcmp($result['data'][0]->version4, 'Clean') == 0) {
                 $preview1 = 0;
                 $preview2 = 0;
                 $preview3 = 0;
                 $preview4 = 1;
             }
             if (strlen($result['data'][0]->amr1) > 4) {
                 $trackLocation = $result['data'][0]->amr1;
                 $insertData1 = array(
 
                     'track' =>  $trackId ,
                     'type' =>  'approved',
                     'version' =>  $result['data'][0]->version1 ,
                     'location' => $trackLocation ,
                     'time' =>  $result['data'][0]->link ,
                     'bpm' =>  $result['data'][0]->bpm ,
                     'added' =>  NOW(),
                     'edited' =>  NOW() ,
                     'title' =>  $result['data'][0]->title1 ,
                     'preview' =>  $preview1 ,
         
                     );
         
                 $query = DB::table('tracks_mp3s')->insertGetId($insertData1);
 
                 // $query =DB::select("insert into tracks_mp3s (`track`, `type`, `version`, `location`, `time`, `bpm`, `preview`, `added`, `edited`, `title`) values ('" . $trackId . "', 'approved', '" . $result['data'][0]->version1 . "', '" . $trackLocation . "', '" . $result['data'][0]->link . "', '" . $result['data'][0]->bpm . "', '" . $preview1 . "', NOW(), NOW(), '" . $result['data'][0]->title1 . "')");
             }
             if (strlen($result['data'][0]->amr2) > 4) {
                 $trackLocation = $result['data'][0]->amr2;
 
                 $insertData1 = array(
 
                     'track' =>  $trackId ,
                     'type' =>  'approved',
                     'version' =>  $result['data'][0]->version2 ,
                     'location' => $trackLocation ,
                     'time' =>  $result['data'][0]->link ,
                     'bpm' =>  $result['data'][0]->bpm ,
                     'added' =>  NOW(),
                     'edited' =>  NOW() ,
                     'title' =>  $result['data'][0]->title2 ,
                     'preview' =>  $preview2 ,
         
                     );
         
                 $query = DB::table('tracks_mp3s')->insertGetId($insertData1);
 
 
 
                 // $query =DB::select("insert into tracks_mp3s (`track`, `type`, `version`, `location`, `time`, `bpm`, `preview`, `added`, `edited`, `title`) values ('" . $trackId . "', 'approved', '" . $result['data'][0]->version2 . "', '" . $trackLocation . "', '" . $result['data'][0]->link . "', '" . $result['data'][0]->bpm . "', '" . $preview2 . "', NOW(), NOW(), '" . $result['data'][0]->title2 . "')");
 
             }
             if (strlen($result['data'][0]->amr3) > 4) {
                 $trackLocation = $result['data'][0]->amr3;
 
                 $insertData1 = array(
 
                     'track' =>  $trackId ,
                     'type' =>  'approved',
                     'version' =>  $result['data'][0]->version3 ,
                     'location' => $trackLocation ,
                     'time' =>  $result['data'][0]->link ,
                     'bpm' =>  $result['data'][0]->bpm ,
                     'added' =>  NOW(),
                     'edited' =>  NOW() ,
                     'title' =>  $result['data'][0]->title3 ,
                     'preview' =>  $preview3 ,
         
                     );
         
                 $query = DB::table('tracks_mp3s')->insertGetId($insertData1);
 
                 // $query =DB::select("insert into tracks_mp3s (`track`, `type`, `version`, `location`, `time`, `bpm`, `preview`, `added`, `edited`, `title`) values ('" . $trackId . "', 'approved', '" . $result['data'][0]->version3 . "', '" . $trackLocation . "', '" . $result['data'][0]->link . "', '" . $result['data'][0]->bpm . "', '" . $preview3 . "', NOW(), NOW(), '" . $result['data'][0]->title3 . "')");
             }
             if (strlen($result['data'][0]->amr4) > 4) {
                 $trackLocation = $result['data'][0]->amr4;
 
                 $insertData1 = array(
 
                     'track' =>  $trackId ,
                     'type' =>  'approved',
                     'version' =>  $result['data'][0]->version4 ,
                     'location' => $trackLocation ,
                     'time' =>  $result['data'][0]->link ,
                     'bpm' =>  $result['data'][0]->bpm ,
                     'added' =>  NOW(),
                     'edited' =>  NOW() ,
                     'title' =>  $result['data'][0]->title4 ,
                     'preview' =>  $preview4 ,
         
                     );
         
                 $query = DB::table('tracks_mp3s')->insertGetId($insertData1);
 
                 // $query =DB::select("insert into tracks_mp3s (`track`, `type`, `version`, `location`, `time`, `bpm`, `preview`, `added`, `edited`, `title`) values ('" . $trackId . "', 'approved', '" . $result['data'][0]->version4 . "', '" . $trackLocation . "', '" . $result['data'][0]->link . "', '" . $result['data'][0]->bpm . "', '" . $preview4 . "', NOW(), NOW(), '" . $result['data'][0]->title4 . "')");
             }
         }
         return  $result;
     }
 
     function getNumSubmittedTracks_trm($where)
     {
         $query =DB::select("select * from  tracks_submitted where approved = '0' and deleted = '0' order by id desc");
         $result = count($query);
         return  $result;
     }
 
     function getNumSubmittedTracksVersions_trm($where)
     {
		// $query =DB::select("SELECT tracks.*, tracks_submitted_versions.version_name FROM tracks JOIN tracks_submitted_versions ON (tracks.id = tracks_submitted_versions.track_id) LEFT JOIN clients on tracks.client = clients.id $where");
        $query = DB::select("SELECT DISTINCT track_id FROM tracks_submitted_versions");
        $result = count($query);
        return  $result;
     } 
     
     function updateSubmittedTrack_trm($data, $trackId)
     {
         extract($data);
         $query = DB::select("update `tracks_submitted` set artist = '" . urlencode($artist) . "', title = '" . urlencode($title) . "', producers = '" . urlencode($producers) . "', time = '" . $time . "', bpm = '" . $bpm . "', album = '" . urlencode($album) . "', link = '" . urlencode($website) . "', moreinfo = '" . urlencode($moreInfo) . "',  genreId = '" . $genre . "', subGenreId = '" . $subGenre . "', label = '" . urlencode($company) . "', albumType = '" . urlencode($albumType) . "', link1 = '" . $website1 . "', link2 = '" . $website2 . "', facebookLink = '" . $facebookLink . "', twitterLink = '" . $twitterLink . "', instagramLink = '" . $instagramLink . "', contact_email = '" . $contact_email . "' where id = '" . $trackId . "'");
         return $query;
     }
 
     function updateSubmittedPageImage_trm($trackId, $img, $pcloudFileId, $parentfolderid)
     {
         $query = DB::select("UPDATE `tracks_submitted` set  `imgpage` = '" . $img . "' , pCloudFileID ='" .$pcloudFileId. "' , pCloudParentFolderID ='".$parentfolderid."' where id = '" . $trackId . "'");
         return 1;
     }
 
     function addClientTrackAmr1($id, $amrFile, $version)
     {
         $query = DB::select("update `tracks_submitted` set amr1 = '" . $amrFile . "', version1 = '" . $version . "' where id = '" . $id . "'");
         return 1;
     }
 
     function addClientTrackAmr2($id, $amrFile, $version)
     {
         $query = DB::select("update `tracks_submitted` set amr2 = '" . $amrFile . "', version2 = '" . $version . "' where id = '" . $id . "'");
         return 1;
     }
 
     function addClientTrackAmr3($id, $amrFile, $version)
     {
         $query = DB::select("update `tracks_submitted` set amr3 = '" . $amrFile . "', version3 = '" . $version . "' where id = '" . $id . "'");
         return 1;
     }
 
     function addClientTrackAmr4($id, $amrFile, $version)
     {
         $query = DB::select("update `tracks_submitted` set amr4 = '" . $amrFile . "', version4 = '" . $version . "' where id = '" . $id . "'");
         return 1;
     }
 
     function delete_streaming_tracks_trm($trackid){
 
         $result = DB::table('top_streaming_tracks')
                         ->where('trackId', $trackid)
                         ->delete();
 
        // $result = DB::select("delete from top_streaming_tracks where trackId = '$trackid'");
         return $result;
     }
 
     function getAllTracks_trm(){
         $list=$this->getAllIds_trm();
         
         $query = DB::select("select clients.uname, clients.name,members.fname, tracks.id, tracks.artist, tracks.title, tracks.album, tracks.time, tracks.label, tracks.added, tracks.img, tracks.imgpage,tracks.pCloudFileID from  tracks 
        left join clients on tracks.client = clients.id join top_streaming_tracks on tracks.id = top_streaming_tracks.trackId  left join members on tracks.member = members.id 
         order by top_streaming_tracks.position,top_streaming_tracks.created_at ASC");
         $result['numRows']  = count($query);
         $result['data']  = $query;
        //  print_r($query);
        //  die();
         return $query;
     }
 
 
     function getAllPriorityTracks_trm(){
 
 
         $query = DB::select("select clients.uname, clients.name,members.fname, tracks.id, tracks.artist, tracks.title, tracks.album, tracks.time, tracks.label, tracks.added, tracks.img, tracks.imgpage,tracks.pCloudFileID from  tracks 
         left join clients on tracks.client = clients.id left join members on tracks.member = members.id where tracks.priority=1 ORDER BY tracks.order_position DESC, tracks.added DESC");
         $result['numRows']  = count($query);
         $result['data']  = $query;
         return $query;
     }
 
 
     function getExportTracks_trm($where, $sort)
     {
         $query = DB::select("select clients.uname, clients.name, tracks.id, tracks.artist, tracks.title, tracks.album, tracks.time, tracks.label, tracks.added, tracks.img, tracks.imgpage, tracks.moreinfo, tracks.producer from  tracks 
          left join clients on tracks.client = clients.id
          $where order by $sort");
         $result['numRows']  = count($query);
         $result['data']  = $query;
         return  $result;
     }
 
     function getAllReleaseType_trm()
     {
         $query = DB::select("select * from release_type");
         $result = count($query);
         return $query; 
     }
 
     function addReleaseType_trm($data)
     {
         extract($data);
         $admin_id = Auth::user()->id;
         $insertData1 = array(
 
             'release_name' =>  urlencode($release_name) ,
             'status' =>  urlencode($status),
             'added' =>  NOW() ,
             'addedby' => $admin_id ,
             
 
             );
 
           $releaseId = DB::table('release_type')->insertGetId($insertData1);
         
             // $query = DB::select("INSERT INTO `release_type` (`release_name`, `status`,`added`, `addedby`) VALUES ('" . urlencode($release_name) . "','" . urlencode($status) . "', NOW(), '" . $_COOKIE['adminId'] . "')");
             // $releaseId = $this->db->insert_id();
             
             return $releaseId; 
     }


     function getClientTrackReview_trm($tid)

    {   
        if(!empty($_COOKIE['memberId'])){

            $get_member_id = $_COOKIE['memberId'];
        }
        else{

            $get_member_id = '';

        }

        $query = DB::select("SELECT * FROM tracks_reviews where member = '" . $get_member_id . "' and track = '" . $tid . "'");

        $result['numRows'] = count($query);

        $result['data']  = $query;

        return $result;
    }

    function getLogo_trm()
    {
        $query = DB::select("SELECT logo  FROM   website_logo where logo_id = '1'");
        return $query;
    }


    function addReview_trm($data, $tid, $countryName, $countryCode)

    {

        $anotherFormat = implode(',', $data['anotherFormat']);

        if(!empty($_COOKIE['memberId'])){

            $get_member_id = $_COOKIE['memberId'];
        }
        else{

            $get_member_id = '';

        }

        // $query = DB::select("insert into tracks_reviews (`version`, `track`, `member`, `whereheard`,  `whatrate`,  `anotherformat`, `anotherformat_other`, `additionalcomments`, `added`, `godistance`, `godistanceyes`, `labelsupport`, `labelsupport_other`, `howsupport`, `howsupport_howsoon`, `likerecord`, `countryName`, `countryCode`) values ('2', '" . $tid . "', '" . $_COOKIE['memberId'] . "',  '" . $data['whereHeard'] . "', '" . $data['whatRate'] . "',  '" . $anotherFormat . "', '" . $data['anotherFormatOther'] . "', '" . urlencode($data['comments']) . "', NOW(),  '" . $data['goDistance'] . "', '" . $data['goDistanceYes'] . "', '" . $data['labelSupport'] . "', '" . $data['labelSupportOther'] . "', '" . $data['howSupport'] . "', '" . $data['howSupportHowSoon'] . "', '" . $data['likeRecord'] . "', '" . $countryName . "', '" . $countryCode . "')");

        $insertData1 = array(

            'version' => '2'  ,
             'track' => $tid  ,
             'member' =>  $get_member_id ,
             'whereheard' =>  $data['whereHeard'] ,
              'whatrate' =>  $data['whatRate'] ,
              'anotherformat' =>  $anotherFormat ,
             'anotherformat_other' =>  $data['anotherFormatOther'] ,
             'additionalcomments' =>  $data['comments'] ,
             'added' =>   NOW() ,
             'godistance' =>  $data['goDistance'] ,
             'godistanceyes' =>  $data['goDistanceYes'] ,
             'labelsupport' =>  $data['labelSupport'] ,
             'labelsupport_other' =>  $data['labelSupportOther'] ,
             'howsupport' =>  $data['howSupport'] ,
             'howsupport_howsoon' =>  $data['howSupportHowSoon'] ,
             'likerecord' =>  $data['likeRecord'] ,
             'countryName' =>  $countryName ,
             'countryCode' => $countryCode , 

            );

          $insertId = DB::table('tracks_reviews')->insertGetId($insertData1);
        
        // $query = DB::select('insert into tracks_reviews (`version`, `track`, `member`, `whereheard`,  `whatrate`,  `anotherformat`, `anotherformat_other`, `additionalcomments`, `added`, `godistance`, `godistanceyes`, `labelsupport`, `labelsupport_other`, `howsupport`, `howsupport_howsoon`, `likerecord`, `countryName`, `countryCode`) values (
        //     "2", 
        //     "' . $tid . '", 
        //     "' . $_COOKIE['memberId'] . '",
        //     "' .$data['whereHeard'] . '",
        //     "' . $data['whatRate'] . '",
        //     "' . $anotherFormat . '",
        //     "' . $data['anotherFormatOther'] . '",
        //     "' .urlencode($data['comments']) . '",
        //     NOW(),
        //     "' . $data['goDistance'] . '",
        //     "' . $data['goDistanceYes'] . '",
        //     "' . $data['labelSupport'] .'",
        //     "' . $data['labelSupportOther'] . '",
        //     "' . $data['howSupport'] . '",
        //     "' . $data['howSupportHowSoon'] . '",
        //     "' . $data['likeRecord'] . '",
        //     "' . $countryName . '",
        //     "' . $countryCode . '")');

        // $insertId = $this->db->insert_id();

        // digi coins for review

        if ($insertId > 0) {

            // Check whether already downloaded or not

            $digi_coins = DB::select("SELECT member_digicoin_id FROM member_digicoins where member_id = '" . $get_member_id . "' and track_id = '" . $tid . "' and type_id = '1'");

            $digi_coins_numRows = count($digi_coins);

            if ($digi_coins_numRows < 1) {

                // caliculate dj ponints for review

                $query1 = DB::select("SELECT added FROM tracks where id = '" . $tid . "'");

                $added_result  = $query1;

                $added_result[0]->added;

                $today = date("Y-m-d H:m:s");

                $start = strtotime($added_result[0]->added);

                $end = strtotime($today);

                $days_between = ceil(abs($end - $start) / 86400);

                $points = 3;

                if ($days_between > 7) {

                    $points = 1;
                } else if ($days_between > 1) {

                    $points = 2;
                }

                DB::select("insert into member_digicoins (`member_id`, `track_id`, `type_id`, `points`, `date_time`) values ('" . $get_member_id . "', '" . $tid . "', '1', '" . $points . "', NOW())");

                $digicoin_id = $this->db->insert_id();

                if ($digicoin_id > 0) {

                    $available_coins = DB::select("SELECT available_points FROM member_digicoins_available where member_id = '" . $get_member_id . "' order by member_digicoin_available_id desc");

                    $available_coins_numRows = count($available_coins);

                    if ($available_coins_numRows > 0) {

                        $available_digicoins  = $available_coins;

                        $available_digicoins_increment = ($available_digicoins[0]->available_points) + $points;

                        DB::select("update member_digicoins_available set available_points = '" . $available_digicoins_increment . "', latest_date_time = NOW() where member_id = '" . $get_member_id . "'");
                    } else {

                        DB::select("insert into member_digicoins_available (`member_id`, `available_points`, `latest_date_time`) values ('" . $get_member_id . "', '" . $points . "', NOW())");
                    }
                }
            }
        } // digi coins for review

        return $insertId;
    }

    function getReviewTracks_trm($where, $sort, $start, $limit)

    {

        $query = DB::select("SELECT * FROM  tracks $where order by $sort limit $start, $limit");

        $result['numRows'] = count($query);

        $result['data']  = $query;

        return $result;
    }



    function getMemberInbox_trm($memberId)

    {

        $query = DB::select("SELECT * FROM chat_messages where ((receiverType = '2' AND receiverId = '" . $memberId . "') OR (senderType = '2' AND senderId = '" . $memberId . "')) AND latest = '0' order by messageId desc");

        $result['numRows'] = count($query);

        $result['data']  = $query;

        return $result;
    }

    
    function getStaffSelectedTracks_trm($start, $limit)

    {

        $query = DB::select("SELECT tracks.id, tracks.artist, tracks.album, tracks.title, tracks.imgpage, tracks.thumb FROM staff_selection

                            left join tracks on staff_selection.trackId = tracks.id

                            order by staff_selection.staffTrackId desc limit $start, $limit");

        $result['numRows'] = count($query);

        $result['data']  = $query;

        return $result;
    }


    function getTrackPlays_trm($trackId)

    {

        // plays and downloads

        $query = DB::select("SELECT downloads, num_plays FROM tracks_mp3s where track = '" . $trackId . "' order by preview desc");

        $numRows = count($query);

        $result['plays'] = 0;

        $result['downloads'] = 0;

        if ($numRows > 0) {

            $data = $query;

            foreach ($data as $track) {

                $result['plays'] += $track->num_plays;

                $result['downloads'] += $track->downloads;
            }
        }

        // rating

        $query = DB::select("SELECT whatrate FROM tracks_reviews where track = '" . $trackId . "' order by id desc");

        $ratingRows = count($query);

        $result['rating'] = 0;
        $rating = 0;

        if ($ratingRows > 0) {

            $data = $query;

            foreach ($data as $track) {

                $rating += $track->whatrate;
            }

            $result['rating'] = round(($rating / $ratingRows), 1);
        }

        return $result;
    }


    function getTrackMp3s_trm($trackId)

    {

        $query = DB::select("SELECT tracks_mp3s.id, tracks.client, tracks.artist, tracks.title, tracks_mp3s.version, tracks_mp3s.preview, tracks_mp3s.location,  tracks.pCloudFileID, tracks.pCloudParentFolderID FROM

        tracks left join tracks_mp3s ON tracks.id = tracks_mp3s.track where tracks_mp3s.track = '$trackId' order by tracks_mp3s.preview desc");

        $result['numRows'] = count($query);

        $result['data']  = $query;

        return $result;
    }


    function getYouSelectedTracks1($memberId, $start, $limit)

    {

        $query1 = DB::select("SELECT DISTINCT track_member_downloads.downloadId, track_member_downloads.trackId, track_member_downloads.mp3Id, tracks_mp3s.version  FROM track_member_downloads

        left join tracks_mp3s on track_member_downloads.mp3Id = tracks_mp3s.id

        where track_member_downloads.memberId = '" . $memberId . "'

        order by track_member_downloads.downloadId desc limit $start, $limit");

        $numRows1 = count($query1);

        $data1  = $query1;

        if ($numRows1 > 0) {

            $tracks = array();

            $versions = array();

            foreach ($data1 as $dat) {

                $tracks[] = $dat->trackId;

                $mp3Ids[] = $dat->mp3Id;

                $versions[] = "'" . $dat->version . "'";
            }

            $downloaded_versions = implode(',', $versions);

            $downloaded_versions = '(' . $downloaded_versions . ')';

            $query2 = DB::select("SELECT DISTINCT tracks.id, tracks_mp3s.version,  tracks.artist, tracks.album, tracks.title, tracks.imgpage, tracks.thumb FROM tracks_mp3s

            left join tracks on tracks_mp3s.track = tracks.id

            where tracks_mp3s.version IN $downloaded_versions order by tracks_mp3s.id desc limit $start, $limit");

            $result['numRows'] = count($query2);

            $result['data']   = $query2;

            /*$query = DB::select("SELECT tracks.id, tracks.artist, tracks.album, tracks.title, tracks.imgpage, tracks.thumb FROM you_selection

            left join tracks on you_selection.trackId = tracks.id

            order by you_selection.youTrackId desc limit $start, $limit");

            $result['numRows'] = count($query);

            $result['data']  = $query;*/
        } else {

            $query = DB::select("SELECT tracks.id, tracks.artist, tracks.album, tracks.title, tracks.imgpage, tracks.thumb FROM you_selection

   left join tracks on you_selection.trackId = tracks.id

   order by you_selection.youTrackId desc limit $start, $limit");

            $result['numRows'] = count($query);

            $result['data']  = $query;
        }

        return $result;
    }


    function getTrackMp3s1_trm($trackId)

    {

        $query = DB::select("SELECT tracks_mp3s.id, tracks.client, tracks.artist, tracks.title, tracks_mp3s.version, tracks_mp3s.location FROM

   tracks left join tracks_mp3s ON tracks.id = tracks_mp3s.track where tracks_mp3s.track = '$trackId' order by tracks_mp3s.preview desc");

        $result['numRows'] = count($query);

        $result['data']  = $query;

        return $result;
    }


    function getYouSelectedTracks_trm($start, $limit)

    {

        /* $query = DB::select("SELECT tracks.id, tracks.artist, tracks.album, tracks.title, tracks.imgpage, tracks.thumb FROM you_selection

   left join tracks on you_selection.trackId = tracks.id

   order by you_selection.youTrackId desc limit $start, $limit");

   */

        $query = DB::select("SELECT tracks.id, tracks.artist, tracks.album, tracks.title, tracks.imgpage, tracks.thumb FROM tracks

   limit $start, $limit");

        $result['numRows'] = count($query);

        $result['data']  = $query;

        return $result;
    }


    function getMemberFooterTracks_trm()

    {

        $where = "where tracks.deleted = '0'";

        if(!empty($_COOKIE['memberPackage'])){

            $memberPackage = $_COOKIE['memberPackage'];
        }
        else{

            $memberPackage = '';

        }

        if ($memberPackage < 3) {

            $where = "where tracks.deleted = '0' and tracks_mp3s.preview = '1'";
        }

        //  tracks_mp3s.preview

        $query = DB::select("SELECT tracks_mp3s.id, tracks.client, tracks.artist, tracks.title, tracks.imgpage, tracks_mp3s.location FROM

	   tracks left join tracks_mp3s ON tracks.id = tracks_mp3s.track $where order by RAND() limit 0, 50");

        $result['numRows'] = count($query);

        $result['data']  = $query;

        return $result;
    }


    
    function getMemberSubscriptionStatus_trm($memberId)

    {

        $query = DB::select("SELECT status, package_Id, subscription_Id FROM member_subscriptions where member_Id = '" . $memberId . "' and status = '1' order by subscription_Id desc limit 1");

        $result['numRows'] = count($query);

        $result['data']  = $query;

        return $result;
    }



    function getBanner_trm($page_id)
    {
        $query =  DB::select("SELECT banner_image  FROM   dynamic_pages where page_id = '" . $page_id . "'");
        return $query;
    }

    function getBannerText_trm($id)

    {

        $query =  DB::select("SELECT * FROM  banners where  pageId = '" . $id . "'");

        $result  = $query;
        //dd($result);
        return  $result;
    }


    function getTracks_member($where, $sort, $start, $limit)

    {

        //   $query =  DB::select("SELECT * FROM  tracks $where order by $sort limit $start, $limit");

        /*

   echo "SELECT distinct tracks_mp3s.track, tracks_mp3s.bpm, tracks.id, tracks.artist, tracks.title, tracks.album, tracks.time, tracks.label, tracks.img, tracks.imgpage, tracks.added, tracks.bpm FROM  tracks

   left join tracks_mp3s on tracks.id = tracks_mp3s.track

   $where order by $sort limit $start, $limit";

   echo '<br />';*/
        /* $query =  DB::select("SELECT distinct tracks_mp3s.track, tracks_mp3s.bpm, tracks.id, tracks.artist, tracks.title, tracks.album, tracks.time, tracks.label, tracks.img, tracks.imgpage, tracks.added, tracks.bpm, tracks.producer, tracks.link, tracks.videoURL FROM  tracks

   left join tracks_mp3s on tracks.id = tracks_mp3s.track

   $where order by $sort limit $start, $limit");*/


//         $query =  DB::select("SELECT distinct tracks_mp3s.track, tracks_mp3s.bpm, tracks.id, tracks.artist, tracks.title, tracks.album, tracks.time, tracks.label, tracks.img, tracks.imgpage, tracks.added, tracks.producer, tracks.link, tracks.videoURL FROM  tracks

//    join tracks_mp3s on tracks.id = tracks_mp3s.track

//    $where group by tracks.id order by $sort limit $start, $limit");


            $query =  DB::select("SELECT distinct tracks_mp3s.track, tracks_mp3s.bpm, tracks.id, tracks.artist, tracks.title, tracks.album, tracks.time, tracks.label, tracks.img, tracks.imgpage, tracks.added, tracks.producer, tracks.link, tracks.videoURL FROM  tracks

            join tracks_mp3s on tracks.id = tracks_mp3s.track

            $where order by $sort limit $start, $limit");
        $result['numRows'] = count($query);

        $result['data']  = $query;

        return $result;
    }


    function getTopDownloadTracks_trm($start)
    {
        $query1 =  DB::select("SELECT DISTINCT MAX(tracks_mp3s.downloads) as trackdownloads, tracks.id as trackid FROM tracks,tracks_mp3s WHERE tracks_mp3s.track=tracks.id AND tracks.id IS NOT NULL
        AND tracks_mp3s.id IS NOT NULL AND DATE_SUB(CURDATE(),INTERVAL 7 DAY) <= tracks.added and tracks.deleted=0 and tracks.status='publish' GROUP BY tracks.id ORDER BY trackdownloads DESC
        LIMIT $start, 4");

        // foreach($query1 as $q){

        //     $trackdownloads = $q->trackdownloads;

        // }
      

        $query =  DB::select("SELECT DISTINCT tracks.id as trackid, tracks.title, tracks.artist, tracks.imgpage, tracks_mp3s.downloads FROM tracks,tracks_mp3s WHERE tracks_mp3s.track=tracks.id AND tracks.id IS NOT NULL
        AND tracks_mp3s.id IS NOT NULL AND DATE_SUB(CURDATE(),INTERVAL 7 DAY) <= tracks.added and tracks.deleted=0 and tracks.status='publish'
        LIMIT $start, 4");
        $result['numRows'] =count($query);

        if(!empty($query1)){

            foreach($query1 as $q){

                $query->trackdownloads = $q->trackdownloads;
    
            }

        }
        $result['data']  = $query;
      //  dd($result);
        return $result;
    }

    function getNotifications_trm()
    {

        $query =  DB::select("SELECT id, artist, album, title, imgpage, thumb  FROM tracks  order by id desc limit 0, 5");
        $result['numRows'] = count($query);
        $result['data']  = $query;
        return $result;
    }


    function getYoutube_trm()
    {
        $query =  DB::select("SELECT youtube FROM  youtube where youtube_id = '1'");
        $result  = $query;
        return $result;
    }

    function getPageLinks_trm($pageId)
    {
        $query =  DB::select("SELECT * FROM dynamic_links where pageId = '" . $pageId . "' order by linkId asc");
        //   $result['numRows'] =  count($query);
        return $query;
    }


    function getPageMeta_trm($id)
    {
        $query =  DB::select("SELECT meta_tittle, meta_keywords, meta_description FROM  dynamic_pages where  page_id = '" . $id . "'");
        $result['numRows'] = count($query);
        $result['data']  = $query;

        return  $result;
    }

    // New 3 
    function getNumTopDownloadTracks($where, $sort)

    {

        $query = DB::select("SELECT DISTINCT tracks_mp3s.downloads, track_member_downloads.trackId, track_member_downloads.mp3Id, tracks.id, tracks.artist, tracks.title, tracks.album, tracks.label, tracks.imgpage, tracks.thumb, tracks.added FROM   track_member_downloads

   left join tracks_mp3s on track_member_downloads.mp3Id = tracks_mp3s.id

   left join tracks on track_member_downloads.trackId = tracks.id

   $where order by $sort");

        return  count($query);
    }



    function getTopDownloadChartTracks($where, $sort, $start, $limit)
    {

//         $query = DB::select("SELECT DISTINCT track_member_downloads.trackId, track_member_downloads.mp3Id, tracks.id, tracks.artist, tracks.title, tracks.album, tracks.label, tracks.imgpage, tracks.thumb, tracks.added, tracks_mp3s.downloads FROM   track_member_downloads

//    left join tracks_mp3s on track_member_downloads.mp3Id = tracks_mp3s.id
//    left join tracks on track_member_downloads.trackId = tracks.id
//    $where GROUP BY tracks.id order by $sort limit $start, $limit");


        $query = DB::select("SELECT DISTINCT track_member_downloads.trackId, track_member_downloads.mp3Id, tracks.id, tracks.artist, tracks.title, tracks.album, tracks.label, tracks.imgpage, tracks.thumb, tracks.added, tracks_mp3s.downloads FROM   track_member_downloads

        left join tracks_mp3s on track_member_downloads.mp3Id = tracks_mp3s.id
        left join tracks on track_member_downloads.trackId = tracks.id
        $where order by $sort limit $start, $limit");

        $result['numRows'] =  count($query);
        $result['data']  = $query;
        return $result;
    }


    function getNewestTracks($where, $sort, $start, $limit){

        $query = DB::select("SELECT * FROM  tracks $where order by added DESC limit $start, $limit");
        
        $result['query']  ="SELECT * FROM  tracks $where order by $sort limit $start, $limit";

        /*  $query = DB::select("SELECT distinct tracks_mp3s.track, tracks_mp3s.bpm, tracks.id, tracks.artist, tracks.title, tracks.album, tracks.time, tracks.label, tracks.img, tracks.imgpage, tracks.added, tracks.bpm FROM  tracks
           left join tracks_mp3s on tracks.id = tracks_mp3s.track
           $where order by $sort limit $start, $limit");  */

        $result['numRows'] =  count($query);
        $result['data']  = $query;
        
        
        $result['downloaded']=array();
        if(isset($_COOKIE['memberId']) && $_COOKIE['memberId']>0 ){
            
            $track_ids= array();
            foreach ($result['data'] as $track) {
                $track_ids[]= $track->id;
            }
            $query = DB::select("SELECT Distinct trackId FROM track_member_downloads WHERE trackId IN (".implode(",",$track_ids).")  AND memberId=".$_COOKIE['memberId']);
            $track_ids= array();
            foreach ($query->result() as $t) {
                $track_ids[]=$t->trackId;
            }
            $result['downloaded']  = $track_ids;
        }
        return $result;
    }

    function getSubscriptionStatus($clientId)

    {

        $query = DB::select("SELECT status, packageId FROM client_subscriptions where clientId = '" . $clientId . "' and status = '1' order by subscriptionId desc limit 1");

        $result['numRows'] =  count($query);

        $result['data']  = $query;

        return $result;
    }


    function getNumMemberDigicoins($where,$sort)

	{

		 $query =  DB::select("select buy_digicoins.buy_id, buy_digicoins.user_type, buy_digicoins.user_id, buy_digicoins.package_id, buy_digicoins.payment_type, buy_digicoins.buy_date_time from  buy_digicoins

		 $where  order by $sort");  

		 $result =  count($query);

		 return  $result;

	}

  
    function getNumViewMemberDigicoins($where, $sort)

	{

		 $query =  DB::select("select member_digicoin_id from  member_digicoins $where  order by $sort");  

		 $result =  count($query);

		 return  $result;

	}
	
	function getViewMemberDigicoins($where, $sort, $start, $limit){

		$query =  DB::select("select member_digicoins.type_id, member_digicoins.points, member_digicoins.date_time, tracks.artist, tracks.title from  member_digicoins left join tracks on member_digicoins.track_id = tracks.id $where order by $sort limit $start, $limit");  		

		 $result['numRows']  =  count($query);

		 $result['data']  = $query;
		 return  $result;

	}

    
	function getMemberDigicoins($where,$sort,$start,$limit)

	{

	$query =  DB::select("select buy_digicoins.buy_id, buy_digicoins.user_type, buy_digicoins.user_id, buy_digicoins.package_id, buy_digicoins.payment_type, buy_digicoins.buy_date_time from  buy_digicoins

		 $where  order by $sort limit $start, $limit");  

		

		 $result['numRows']  =  count($query);

		 $result['data']  = $query;
		 return  $result;

	}

    function getClientInfo($user_id)

        {

        $query =  DB::select("select name  from   clients where id = '". $user_id ."'");  

        $result  = $query;
        return $result;



        }

        function getMemberInfo($user_id)

        {

        $query =  DB::select("select fname  from   members where id = '". $user_id ."'");  

        $result  = $query;
        return $result;

        }


        function deleteProduct($did)

        {

        $result = DB::select("delete from  products where product_id = '". $did ."'");  

        return $result;

        }

        function getNumProducts($where,$sort)

        {

            

                $query = DB::select("select * from  products $where order by $sort");  

            

                $result =   count($query);

                return  $result;

        }

        function getProducts($where,$sort,$start,$limit)

        {
   

            $query = DB::select("select * from  products $where order by $sort limit $start, $limit");  

            $result['numRows']  =   count($query);

            $result['data']  = $query;

            return  $result;

        }


        function getProductPrices($where)

    {

    $query = DB::select("select * from product_price $where");  

    $result['numRows'] =   count($query);

    $result['data']  = $query;

    return $result;

    }

    // 02 sep 

    function getMemberInfo_prm($memberId)

	{

 

   $query =DB::select("select * from  members 
    left join members_dj_mixer on members.id = members_dj_mixer.member

   where members.id = '$memberId'");  

   $result['numRows'] = count($query);

   $result['data']  = $query;

	  return $result;

	}

    function getMemberProductionInfo($memberId)

	{

        $query =DB::select("select productiontype_artist, productiontype_producer, productiontype_choreographer, productiontype_sound, production_name from  members_production_talent where member = '$memberId'");  

        $result['numRows'] = count($query);

        $result['data']  = $query;

            return $result;

	}

    function getMemberSpecialInfo($memberId)

	{

 

   $query =DB::select("select servicestype_corporate, servicestype_graphicdesign, servicestype_webdesign, servicestype_other, services_name, services_website from  members_special_services where member = '$memberId'");  

   $result['numRows'] = count($query);

   $result['data']  = $query;

	  return $result;

	}

	

	function getMemberPromoterInfo($memberId)

	{

 

   $query =DB::select("select promotertype_indy, promotertype_club, promotertype_event, promotertype_street, promoter_name, promoter_department, promoter_website from members_promoter where member = '$memberId'");  

   $result['numRows'] = count($query);

   $result['data']  = $query;

	  return $result;

	}

	

	function getMemberClothingInfo($memberId)

	{

 

   $query =DB::select("select clothing_name, clothing_department from members_clothing_apparel where member = '$memberId'");  

   $result['numRows'] = count($query);

   $result['data']  = $query;

	  return $result;

	}

	

	

	function getMemberManagementInfo($memberId)

	{

 

   $query =DB::select("select managementtype_artist, managementtype_tour, managementtype_personal, managementtype_finance, management_name, management_who, management_industry from members_management where member = '$memberId'");  

   $result['numRows'] = count($query);

   $result['data']  = $query;

	  return $result;

	}





   function getMemberRecordInfo($memberId)

	{

 

   $query =DB::select("select labeltype_major, labeltype_indy, labeltype_distribution, label_name, label_department from members_record_label where member = '$memberId'");  

   $result['numRows'] = count($query);

   $result['data']  = $query;

	  return $result;

	}

	

	function getMemberMediaInfo($memberId)

	{

 

   $query =DB::select("select mediatype_tvfilm, mediatype_publication, mediatype_newmedia, mediatype_newsletter, media_name, media_website, media_department from members_mass_media where member = '$memberId'");  

   $result['numRows'] = count($query);

   $result['data']  = $query;

	  return $result;

	}

	

	

	function getMemberRadioInfo($memberId)

	{

 

   $query =DB::select("select * from members_radio_station where member = '$memberId'");  

   $result['numRows'] = count($query);

   $result['data']  = $query;

	  return $result;

	}

    function getMemberSocialInfo($memberId)

	{

  

   $query =DB::select("select * from  member_social_media where memberId = '$memberId'");  

   $result['numRows'] = count($query);

   $result['data']  = $query;

   return $result;

   }

   function getProductQuestions($pid)

{

  $query =DB::select("SELECT product_product_questions.question_id, product_product_questions.type, product_questions.question FROM

  						   product_product_questions

  						   INNER JOIN product_questions ON (product_product_questions.question_id = product_questions.question_id) WHERE product_id = '". $pid ."' ORDER BY product_product_questions.order");  

  $result['numRows'] = count($query);

  $result['data']  = $query;

  return $result;

}


function getTextData($pid,$qid)

{

	$query =DB::select("SELECT product_text_answer_id, product_id, question_id, member_id, answer, members.fname, 

	members.lname, members.stagename, members.city, members.state FROM product_text_answers JOIN members ON (product_text_answers.member_id =

    members.id) WHERE product_text_answers.product_id = '". $pid ."' AND product_text_answers.question_id = '". $qid ."'");

	

	$result['numRows'] = count($query);

    $result['data']  = $query;

    //dd($result);

    return $result;

}





function getGraphData($pid,$qid)

{  

 $query =DB::select("SELECT product_question_answers.answer_id,  product_answers.answer FROM

  								   product_question_answers 

								   INNER JOIN product_answers ON (product_question_answers.answer_id = product_answers.answer_id)

								   WHERE question_id = $qid AND product_question_answers.product_id = $pid 

								   ORDER BY product_question_answers.order,product_question_answers.answer_id");

	

	$result['numRows'] = count($query);

    $result['data']  = $query;

    return $result;			

}



function getGraphDataAnswers($pid,$qid,$aid)

{  

  //  dd($aid);

 $query =DB::select("SELECT COUNT(answer_id) as anscount FROM product_questions_answered 

                     WHERE product_id = '". $pid ."' AND question_id = '". $qid ."' AND answer_id = '". $aid ."' GROUP BY answer_id");

 	
   //print_r($query);die;
	$result['numRows'] = count($query);

    $result['data']  = $query;

   

    return $result;			

}

function deleteProductPrice($did)

  {

   $result =DB::select("delete from  product_price where price_id = '". $did ."'");  

   return $result;

  }


  function deleteProductDiscount($did)

  {

   $result =DB::select("delete from  product_discount where discount_id = '". $did ."'");  

   return $result;

  }


  function addDigicoins($data,$pid)

{ 

    $insertData = array(
        'product_id' => $pid,
        'digicoin_price' => $data['retailPrice'],
        'applies_from' => NOW(),
        'created_on' => NOW(),
       
    );

    $digicoin_id = DB::table('product_price')->insertGetId($insertData);

    // $query =DB::select("insert into product_price (`product_id`, `digicoin_price`, `applies_from`, `created_on`) VALUES ('". $pid ."', '". $data['retailPrice'] ."', NOW(), NOW())");    

    //     $digicoin_id = $this->db->insert_id();

	 return $digicoin_id;

}


function addDiscount($data,$pid)

{   
    $insertData = array(
        'product_id' => $pid,
        'discount_percentage' => $data['discount'],
        'applies_from' => NOW(),
        'applies_to' => NOW(),
        'created_on' => NOW(),
       
    );

     $discount_id = DB::table('product_discount')->insertGetId($insertData);

    // $query =DB::select("insert into product_discount (`product_id`, `discount_percentage`, `applies_from`, `applies_to`, `created_on`) VALUES ('". $pid ."', '". $data['discount'] ."', NOW(), NOW(), NOW())");    

    //     $discount_id = $this->db->insert_id();

	 return $discount_id;

}

function getProduct($where)

{

     $query =DB::select("select * from  products $where");  

     $result['numRows']  =  count($query);

     $result['data']  = $query;

     return  $result;

}

function getProductDiscounts($where)

{

  $query = DB::select("select * from product_discount $where");  

  $result['numRows'] =  count($query);

  $result['data']  = $query;

  return $result;

}

function deleteQuestion($question_id,$product_id)

{



 $result = DB::select("delete from  product_questions where question_id = '". $question_id ."'");  

 DB::select("delete from  product_product_questions where question_id = '". $question_id ."' and product_id = '". $product_id ."'"); 

 DB::select("delete from  product_question_answers where question_id = '". $question_id ."' and product_id = '". $product_id ."'");  

 return $result;

}

function getQuestions($where,$sort)

{

     $query = DB::select("select * from  product_product_questions 

     left join product_questions on product_product_questions.question_id = product_questions.question_id

     $where order by $sort");  

    

     $result['numRows']  =  count($query);

     $result['data']  = $query;

     return  $result;

}


function getQuestionOptions($where,$sort)

{

  $query = DB::select("SELECT product_question_answers.answer_id,  product_answers.answer FROM

                                   product_question_answers 

                                 INNER JOIN product_answers ON product_question_answers.answer_id = product_answers.answer_id

                                 $where ORDER BY $sort");

  $result['numRows'] =  count($query);

  $result['data']  = $query;

  return $result;			

}


function updateQuestion($data,$product_id,$question_id)

{

 extract($data);



 $result =  DB::select("update product_questions set question = '". addslashes($question) ."' where question_id = '". $question_id ."'");  	 

  DB::select("update product_product_questions set type =  '". $type ."' where product_id = '". $product_id ."' and question_id = '". $question_id ."'");   


  // answers

  if((strcmp($type,'radio')==0) || (strcmp($type,'check')==0))

  {

     if(isset($options))

     {

      $i = 0;
       foreach($options as $option)

       {

         echo $option; echo '<br />';

          if(isset($answer_ids[$i]))

          {

           DB::select("update product_answers set answer = '". $option ."' where answer_id = '". $answer_ids[$i] ."'");   

          }

          else

          {

            $insertData = array(
                'answer' => $option,
    
            );
        
             $answer_id = DB::table('product_answers')->insertGetId($insertData);

            // DB::select("insert into product_answers (`answer`) VALUES ('". $option ."')");   

            // $answer_id = $this->db->insert_id();

         

         if($answer_id>0)

        {

                $insertData = array(
                    'question_id' => $question_id,
                    'answer_id' => $answer_id,
                    'product_id' => $product_id,
                
                );
            
                DB::table('product_question_answers')->insertGetId($insertData);

                // DB::select("insert into product_question_answers (`question_id`, `answer_id`, `product_id`) VALUES ('". $question_id ."', '". $answer_id ."', '". $product_id ."')");          
            }

        }

         $i++;

       }

    }


  }

    return $result;

  }


  function updateProduct($data,$pid)

{

$admin_id = Auth::user()->id;

 extract($data);

if(empty($merch_digi)){
    $merch_digi='';
}


 $query =  DB::select("update products set company_id = '". $company ."', name = '". $productName ."', link = '". $link ."', moreinfo = '". addslashes($moreInfo) ."', added = NOW(), addedby = '". $admin_id ."',merch_status = '".$merch_digi."', active = '". $active ."', review = '". $reviewable ."', model = '". $model ."', product_details = '". $productDetails ."',  product_technology = '". $benefits ."', product_gender = '". $gender ."', division = '". $brand ."' where product_id = '". $pid ."'");  

	

  return $query;

}


function getCompanies()

{

  $query =  DB::select("select company_id, name  from product_companies order by name");  

  $result['numRows'] = count($query);

  $result['data']  = $query;

  return $result;

  

}


function addEmailImage_prm($productId,$img)

{

     $query =  DB::select("UPDATE `products` set  `emailimg` = '". $img ."' where product_id = '". $productId ."'");  

     return 1;



}

function addProduct($data)

{


 extract($data);
$admin_id = Auth::user()->id;

 if(!empty($active)) {

    $active = $active;


 }
 else{

    $active = 0;
 }

 if(!empty($reviewable)) {

    $reviewable = $reviewable;


 }
 else{

    $reviewable = 1;
 }


 if($active == 1) {
      $madeAvailable = NOW(); 
    } 
    else {
         $madeAvailable = ''; 
        }


        if(!empty($madeAvailable)) {

            $madeAvailable = $madeAvailable;
        
        
         }
         else{
        
            $madeAvailable = '';
         }



if(!empty($launchDate)) {

    $launchDate = explode('-',$launchDate);

    $launchDate = $launchDate[2].'-'.$launchDate[0].'-'.$launchDate[1];

    }
    else{

    $launchDate = '';
    }


    if(!empty($reviewable)) {

        $reviewable = $reviewable;
    
    
     }
     else{
    
        $reviewable = '';
     }
     
    if(!empty($merch_digi)) {

        $merch_digi = $merch_digi;
    
    
     }
     else{
    
        $merch_digi = '';
     }


     $insertData = array(
        'company_id' => $company,
        'name' => $productName,
        'link' => $link,
        'moreinfo' => addslashes($moreInfo),
        'added' => NOW(),
        'addedby' => $admin_id,
        'active' => $active,
        'review' => $reviewable,
        'model' => $model,
        'product_details' => $productDetails,
        'merch_status' => $merch_digi,
        'product_technology' => $benefits,
        'product_gender' => $gender,
        'launch_date' => $launchDate,
        'madeavailable' => $madeAvailable,
        'division' => $brand,
    
    );

    $productId = DB::table('products')->insertGetId($insertData);
  //  dd($productId);

        // $query =  DB::select("insert into products (`company_id`, `name`, `link`, `moreinfo`, `added`, `addedby`, `active`, `review`, `model`, `product_details`, `product_technology`, `product_gender`, `launch_date`, `madeavailable`, `division`) VALUES ('". $company ."', '". $productName ."', '". $link ."', '". addslashes($moreInfo) ."', NOW(), '". $_COOKIE['adminId'] ."', '". $active ."', '". $reviewable ."', '". $model ."', '". $productDetails ."', '". $benefits ."',	'". $gender ."', '". $launchDate ."', '". $madeAvailable ."', '". $brand ."')");  

	
   
        //   $productId = $this->db->insert_id();

  

  if($productId>0)

  {

 $query = DB::select("insert into product_price (`product_id`, `digicoin_price`, `applies_from`, `created_on`) VALUES ('". $productId ."', '". $retailPrice ."', NOW(), NOW())");    

  }

  

  return $productId;

}


function getNumOrders($where,$sort)

{


     $query = DB::select("select product_orders.order_id from  product_orders

     left join products on product_orders.product_id = products.product_id

     left join members on product_orders.member_id = members.id

     $where order by $sort");  

    

     $result = count($query);

     return  $result;

}



function getOrders($where,$sort,$start,$limit)

{

     $query = DB::select("select * from  product_orders 

     left join products on product_orders.product_id = products.product_id

     left join members on product_orders.member_id = members.id

     $where order by $sort limit $start, $limit");  

    

     $result['numRows']  = count($query);

     $result['data']  = $query;

     return  $result;

}


function addQuestion($data,$product_id)

{

 extract($data);

 $insertData = array(
    'question' => addslashes($question),

);

$question_id = DB::table('product_questions')->insertGetId($insertData);

//  $query = $this->db->query("insert into product_questions (`question`) VALUES ('". addslashes($question) ."')");  	 

//  $question_id = $this->db->insert_id();



if($question_id>0)

{

    DB::select("insert into product_product_questions (`product_id`, `question_id`, `type`) VALUES ('". $product_id ."', '". $question_id ."', '". $type ."')");   

  

  // answers

  if((strcmp($type,'radio')==0) || (strcmp($type,'check')==0))

  {

    

     if(isset($options))

     {

     

       foreach($options as $option)

       {

        //  echo $option; echo '<br />';

         
         $insertData = array(
            'answer' => $option,
        
        );
        
        $answer_id = DB::table('product_answers')->insertGetId($insertData);

        //  $this->db->query("insert into product_answers (`answer`) VALUES ('". $option ."')");   

        //  $answer_id = $this->db->insert_id();

         

         if($answer_id>0)

         {

            DB::select("insert into product_question_answers (`question_id`, `answer_id`, `product_id`) VALUES ('". $question_id ."', '". $answer_id ."', '". $product_id ."')");      

          

         }

       }


    }
  }
}

return $question_id;

  }

  function adc_getClientSocial($clientId)
  {

      $query = DB::select("select facebook, twitter, instagram, snapchat, tiktok, triller, twitch, mixcloud, reddit, linkedin from  client_social_media
       where clientId = '$clientId'");
      $result['numRows']  = count($query);
      $result['data']  = $query;
      return  $result;
  }
 
  function adc_getCountries()
  {
      $query = DB::select("select countryId, country from  country order by country asc");
      $result['numRows']  = count($query);
      $result['data']  = $query;
      return  $result;
  }
  function adc_getStates($cid)
  {
      $query = DB::select("select stateId, name from  states where countryId = '" . $cid . "' order by name asc");
      $result['numRows']  = count($query);
      $result['data']  = $query;
      return  $result;
  }
  function adc_verifyEmail($email)
  {
      //  echo "select id from clients where email = '". urlencode($email) ."'";  echo '<br>';
      $query1 = DB::select("select id from clients where email = '" . urlencode($email) . "'");
      $num1 = count($query1);
      //	 echo '<br>';echo '<br>';
      //	 echo "select id from members where email = '". urlencode($email) ."'";
      $query2 = DB::select("select id from members where email = '" . urlencode($email) . "'");
      $num2 = count($query2);
      // echo '<br>';
      if ($num1 > 0 || $num2 > 0) {
          $result = 1;
      } else {
          $result = 0;
      }
      return $result;
  }
  function adc_getNumClientPayments($where, $sort)
  {
      $query = DB::select("select client_subscriptions.subscriptionId from  client_subscriptions 
        left join clients on client_subscriptions.clientId = clients.id
        $where  order by $sort");
      $result = count($query);
      return  $result;
  }
  function adc_getClientPayments($where, $sort, $start, $limit)
  {
      $query = DB::select("select clients.uname, clients.name, clients.email, client_subscriptions.subscriptionId, client_subscriptions.clientId, client_subscriptions.packageId, client_subscriptions.paymentType, client_subscriptions.status, client_subscriptions.subscribedDateTime  from  client_subscriptions 
       left join clients on client_subscriptions.clientId = clients.id
       $where  order by $sort limit $start, $limit");
      /*$query = DB::select("select clients.uname, clients.name, client_subscriptions.clientSubscriptionId, client_subscriptions.packageId, client_subscriptions.email, client_subscriptions.billingName, client_subscriptions.billingAddressLine1, client_subscriptions.billingCity, client_subscriptions.billingState, client_subscriptions.billingCountry, client_subscriptions.billingCountryCode, client_subscriptions.billingZip, client_subscriptions.shippingName, client_subscriptions.shippingAddressLine1, client_subscriptions.shippingCity, client_subscriptions.shippingState, client_subscriptions.shippingCountry, client_subscriptions.shippingCountryCode, client_subscriptions.shippingZip, client_subscriptions.dateTime, client_subscriptions.paidOn  from  client_subscriptions 
       left join clients on client_subscriptions.clientId = clients.id
       $where  order by $sort limit $start, $limit");  
      */
      $result['numRows']  = count($query);
      $result['data']  = $query;
      return  $result;
  }
  function adc_getStripeDetails($subscriptionId)
  {
      $query = DB::select("select * from  client_payments_stripe where subscriptionId = '" . $subscriptionId . "'");
      return $query;
  }
  function adc_getPaypalDetails($subscriptionId)
  {
      $query = DB::select("select * from  client_payments_paypal where subscriptionId = '" . $subscriptionId . "'");
      return $query;
  }
  function adc_declineClient($clientId)
  {
      $result = DB::select("update clients set active = '-1'  where id = '" . $clientId . "'");
      return  $result;
  }
  function adc_deleteClient($clientId)
  {
      $result = DB::select("update clients set deleted = '1'  where id = '" . $clientId . "'");
      return  $result;
  }
  function adc_acceptClient($clientId)
  {
      $result = DB::select("update clients set active = '1'  where id = '" . $clientId . "'");
      $query = DB::select("select ccontact, email,name from  clients where id = '" . $clientId . "'");
      $response['data'] = $query;
      $response['response'] = 1;
      return  $response;
  }
  function checkIfApprovedClient($clientId){
	  // SECURITY FIX: Use Query Builder to prevent SQL injection
	  $result = DB::table('clients')->where('active', '!=', '1')->where('id', $clientId)->get();
	  $response['numRows'] = count($result);
	  $response['data'] = $result;
	  return $response;
  } 
  function checkIfApprovedMember($memId){
	  // SECURITY FIX: Use Query Builder to prevent SQL injection
	  $result = DB::table('members')->where('active', '!=', '1')->where('id', $memId)->get();
	  $response['numRows'] = count($result);
	  $response['data'] = $result;
	  return $response;
  }
  function adc_changeClientPassword($password, $clientId)
  {
      // SECURITY FIX: Use Query Builder to prevent SQL injection
      DB::table('clients')->where('id', $clientId)->update(['pword' => $password]);
      return 1;
  }
  function adc_updateClient($data, $clientId)
  {
      extract($data);

     $trackReviewsActivate = 0;
     
     if(isset($activate_track_review_feedback) && $activate_track_review_feedback == 1){
        $trackReviewsActivate = 1;
     }

      $admin_id = Auth::user()->id;
      $query = DB::select("update `clients` set name = '" . urlencode($companyName) . "', editedby = '" . $admin_id . "', edited = NOW(), ccontact = '" . urlencode($name) . "', address1 = '" . urlencode($address1) . "', address2 = '" . urlencode($address2) . "', city = '" . urlencode($city) . "', state = '" . urlencode($state) . "', country = '" . urlencode($country) . "', website = '" . urlencode($website) . "',  zip = '" . $zip . "', phone = '" . urlencode($phone) . "', mobile = '" . urlencode($mobileNo) . "', trackReviewEmailsActivated = '".$trackReviewsActivate."' where id = '" . $clientId . "'");
      return $query;
  }
  function adc_getAdmin($adminId)
  {
      $query = DB::select("select id, name from  admins where id = '$adminId'");
      return $query;
  }
  function adc_getClient($clientId)
  {
      $query = DB::select("select clients.id, clients.uname, clients.name, clients.editedby, clients.edited, clients.ccontact, clients.address1, clients.address2, clients.city, clients.state, clients.country, clients.email, clients.website, clients.zip, clients.phone, clients.mobile, clients.trackReviewEmailsActivated from  clients 
       
       
       where clients.id = '$clientId'");
      $result['numRows']  = count($query);
      $result['data']  = $query;
      return  $result;
  }
  function adc_getNumClients($where, $sort)
  {
	  //echo "select * from  clients $where  order by $sort";die();
      $query = DB::select("select * from  clients $where  AND clients.email !='' order by $sort");
      $result = count($query);
      return  $result;
  }
  function adc_getClients($where, $sort, $start, $limit)
  {
	 
	  
      $query = DB::select("select * from clients $where AND clients.email !='' order by $sort limit $start, $limit");
      $result['numRows']  = count($query);
      $result['data']  = $query;
      return  $result;
  }
  function adc_getClientIdByTrackName($track) {
      $query = DB::select("SELECT `client` FROM `tracks` WHERE `title` LIKE '%".urlencode($track)."%' GROUP BY `client`");
      return $query;
  }
  function adc_getClientIdByLabel($label) {
      $query = DB::select("SELECT `client_id` FROM `client_contacts` WHERE `title` LIKE '%".urlencode($label)."%' GROUP BY `client_id`");
      return $query;
  }
  function adc_getClientIdByArtist($artist) {
      $query = DB::select("SELECT `client` FROM `tracks` WHERE `artist` LIKE '%".urlencode($artist)."%' GROUP BY `client`");
      return $query;
  }
  function adc_getClientIdByGenre($genre) {
      $query = DB::select("SELECT `client` FROM `tracks` WHERE `genreId` = ".(int) $genre." GROUP BY `client`");
      return $query;
  }
  function adc_getGenres() {
      $query = DB::select("SELECT * FROM `genres`");
      return $query;
  }
  function adc_addClient($data)
  {
      extract($data);
      $query1 = DB::select("select * from  members where uname = '" . $username . "'");
      $userRows1  = count($query1);
      $query2 = DB::select("select * from  members where email = '" . urlencode($email) . "'");
      $userRows2  = count($query2);
      $query3 = DB::select("select * from  clients where uname = '" . $username . "'");
      $userRows3  = count($query3);
      $query4 = DB::select("select * from  clients where email = '" . urlencode($email) . "'");
      $userRows4  = count($query4);
      if ($userRows1 < 1 && $userRows2 < 1 && $userRows3 < 1 && $userRows4 < 1) {
          $country_query = DB::select("select country from  country where countryId = '" . $country . "'");
          $country_result  = $country_query;
          $state_query = DB::select("select name from  states where stateId = '" . $state . "'");
          $state_result  = $state_query;

          if(!empty($state_result[0]->name)){

              $state_result_name = $state_result[0]->name;
          }
          else{
              $state_result_name = '';
          }
          if(!empty($country_result[0]->country)){

              $country_result_country = $country_result[0]->country;
          }
          else{
              $country_result_country = '';
          }
          $admin_id = Auth::user()->id;

          $insertData = array(
              'uname' => urlencode(trim($username)),
              'pword' => trim($password),
              'ulevel' => 3,
              'name' => urlencode($companyName),
              'addedby' => $admin_id,
              'added' => date('Y-m-d H:i:s'),
              'ccontact' => urlencode($name),
              'address1' => urlencode($address1),
              'address2' => urlencode($address2),
              'city' => urlencode($city),
              'state' => $state_result_name,
              'country' => $country_result_country,
              'email' => urlencode(trim($email)),
              'website' => urlencode($website),
              'active' => 1,
              'deleted' => 0,
              'zip' => $zip,
              'phone' => urlencode($phoneNo),
              'howheard' => urlencode($howheard),
              'mobile' => urlencode($mobileNo) ,
              'resubmission' => 1,
              'confirmEmail' => 1,
              
          
          );
      
          $insertId = DB::table('clients')->insertGetId($insertData);
          $admin_id = Auth::user()->id;
          // $query = DB::select("INSERT INTO `clients` (`uname`, `pword`, `ulevel`, `name`, `addedby`, `added`, `ccontact`, `address1`, `address2`, `city`, `state`, `country`, `email`, `website`,  `active`, `deleted`, `zip`, `phone`, `mobile`, `howheard`, `resubmission`, `confirmEmail`) VALUES ('" . urlencode(trim($username)) . "', '" . trim($password) . "', '3', '" . urlencode($companyName) . "', '" . $admin_id . "', NOW(), '" . urlencode($name) . "', '" . urlencode($address1) . "', '" . urlencode($address2) . "', '" . urlencode($city) . "', '" . $state_result[0]->name . "', '" . $country_result[0]->country . "',  '" . urlencode(trim($email)) . "', '" . urlencode($website) . "', '1', '0', '" . $zip . "', '" . urlencode($phoneNo) . "', '" . urlencode($mobileNo) . "', '" . urlencode($howheard) . "', '1', '1')");
          // $insertId = $this->db->insert_id();



          DB::select("insert into `client_social_media` (`clientId`, `facebook`, `twitter`, `instagram`, `linkedin`) values ('" . $insertId . "', '" . $facebook . "', '" . $twitter . "', '" . $instagram . "', '" . $linkedin . "')");
          $result1['success'] = $insertId;
          $result1['type'] = 0;
      } else {
          $result1['success'] = 0;
          if ($userRows1 > 0 || $userRows3 > 0) {
              $result1['type'] = 1;
          } else if ($userRows2 > 0 || $userRows4 > 0) {
              $result1['type'] = 2;
          }
      }
      return $result1;
  }


  // function updateMemberPassword($password)

  // {   
  //     $memberId_from_session = Session::get('memberId');

  //     // $query = DB::table('members')
  //     //                 ->where('id', $memberId_from_session)
  //     //                 ->update(['pword' => bcrypt($password)]);

  //     $password = md5($password);
  //     $query = DB::select("update members set pword = '" . $password . "' where id = '" . $memberId_from_session . "'");

  //     return $query;
  // }

  function exportClients_adc()
  {
      $query = DB::select("SELECT * FROM  clients order by id desc");
      $result['numRows']  = count($query);
      $result['data']  = $query;
      return  $result;
  }

  // admin member functions 


  function ad_mem_getMemberSocial($memberId)
  {

      $query = DB::select("select facebook, twitter, snapchat, tiktok, triller, twitch , mixcloud, reddit, instagram, linkedin from  member_social_media 
 where memberId = '$memberId'");

      $result['numRows'] = count($query);
      $result['data']  = $query;
      return $result;
  }



  function ad_mem_changeMemberPassword($password, $memberId)

  {
      $passwordIs = md5($password);
      $query = DB::select("update `members` set pword = '" . $passwordIs . "' where id = '" . $memberId . "'");

      return $query;
  }



  function ad_mem_getLogo()
  {
      $query = DB::select("select logo  from   website_logo where logo_id = '1'");
      return $query;
  }

  function ad_mem_getCountries()
  {
      $query = DB::select("select countryId, country from  country order by country asc");

      $result['numRows']  = count($query);
      $result['data']  = $query;
      return  $result;
  }

  function ad_mem_getStates($cid)
  {
      $query = DB::select("select stateId, name from  states where countryId = '" . $cid . "' order by name asc");

      $result['numRows']  = count($query);
      $result['data']  = $query;
      return  $result;
  }



  function ad_mem_getNumMemberDigicoins($where, $sort)

  {

      //echo "select member_digicoin_id from  member_digicoins $where  order by $sort";

      //	echo '<br />';

      $query = DB::select("select member_digicoin_id from  member_digicoins $where  order by $sort");

      $result = count($query);

      return  $result;
  }





  function ad_mem_getMemberDigicoins($where, $sort, $start, $limit)

  {



      //	echo "select * from  member_digicoins $where  order by $sort limit $start, $limit";

      $query = DB::select("select member_digicoins.type_id, member_digicoins.points, member_digicoins.date_time, tracks.artist, tracks.title from  member_digicoins

    left join tracks on member_digicoins.track_id = tracks.id

       $where  order by $sort limit $start, $limit");



      $result['numRows']  = count($query);

      $result['data']  = $query;

      return  $result;
  }



  function ad_mem_getMembershipDetails($memberId, $start, $limit)

  {
      //  $memberId='92776';

      $query = DB::select("select *  from  member_subscriptions	where member_Id = '" . $memberId . "' and status = '1' order by subscription_Id desc limit $start, $limit");
      $query = DB::select("select *  from  member_subscriptions	where member_Id = '" . $memberId . "' and status = '1'");
      $result['numRows']  = count($query);

      $result['data']  = $query;

      // 		 return count($query)." ".$memberId;


      return  $result;
  }



  function ad_mem_addMembership($memberId, $validity, $adminId, $startDate, $endDate)

  {



      $insertId = 0;

      $today = date("Y-m-d");



      $query1 = DB::select("select subscription_Id, endDate from  member_subscriptions where member_Id = '" . $memberId . "' and endDate > '" . $today . "' order by subscription_Id desc limit 0, 1");



      $result1 = count($query1);



      if ($result1 < 1) {

          $insertData = array(
          'member_Id' => $memberId,
          'package_Id' => 3,
          'duration_Id' => $validity,
          'status' => 1,
          'subscribed_date_time' => NOW(),
          'admin_Id' => $adminId,
          'startDate' => $startDate,
           'endDate' => $endDate,
      );

      $insertId = DB::table('member_subscriptions')->insertGetId($insertData);

          // $query = DB::select("INSERT INTO `member_subscriptions` (`member_Id`, `package_Id`, `duration_Id`, `status`, `subscribed_date_time`, `admin_Id`, `startDate`, `endDate`) VALUES ('" . $memberId . "', '3', '" . $validity . "', '1', NOW(), '" . $adminId . "', '" . $startDate . "', '" . $endDate . "')");

          // $insertId = $this->db->insert_id();
      } else {

          $insertId = -1;
      }

      return $insertId;
  }

  function ad_mem_removeMembership($memberId, $subscriptionId)
  {
      // SECURITY FIX: Use Query Builder to prevent SQL injection
      DB::table('member_subscriptions')
          ->where('member_Id', $memberId)
          ->where('subscription_Id', $subscriptionId)
          ->delete();
      return 1;
  }



  function ad_mem_getStripeDetails($subscriptionId)

  {

      $query = DB::select("select * from  member_payments_stripe where subscriptionId = '" . $subscriptionId . "'");

      return $query;
  }



  function ad_mem_getPaypalDetails($subscriptionId)

  {

      $query = DB::select("select * from  member_payments_paypal where subscriptionId = '" . $subscriptionId . "'");

      return $query;
  }



  function ad_mem_getNumMemberPayments($where, $sort)

  {



      $query = DB::select("select member_subscriptions.subscription_Id from  member_subscriptions 

        left join members on member_subscriptions.member_Id = members.id

        $where  order by $sort");



      $result = count($query);

      return  $result;
  }





  function ad_mem_getMemberPayments($where, $sort, $start, $limit)

  {



      $query = DB::select("select members.uname, members.fname, members.email, member_subscriptions.subscription_Id, member_subscriptions.member_Id, member_subscriptions.package_Id, member_subscriptions.paymentType, member_subscriptions.status, member_subscriptions.duration_Id, member_subscriptions.subscribed_date_time  from  member_subscriptions

       left join members on member_subscriptions.member_Id = members.id

       $where  order by $sort limit $start, $limit");



      $result['numRows']  = count($query);

      $result['data']  = $query;

      return  $result;
  }



  function ad_mem_declineMember($memberId)

  {

      $result = DB::select("update members set active = '-1'  where id = '" . $memberId . "'");

      return  $result;
  }



  function ad_mem_deleteMember($memberId)

  {

      //  $result = DB::select("update members set deleted = '1'  where id = '". $memberId ."'");  
      $result = DB::select("delete from members where id = '" . $memberId . "'");

      return  $result;
  }





  function ad_mem_acceptMember($memberId)

  {

      $result = DB::select("update members set active = '1'  where id = '" . $memberId . "'");

      return  $result;
  }

  /*  frontenddb  */





  function ad_mem_getMemberProductionInfo($memberId)

  {



      $query = DB::select("select productiontype_artist, productiontype_producer, productiontype_choreographer, productiontype_sound, production_name from  members_production_talent where member = '$memberId'");

      $result['numRows'] = count($query);

      $result['data']  = $query;

      return $result;
  }



  function ad_mem_getMemberSpecialInfo($memberId)

  {



      $query = DB::select("select servicestype_corporate, servicestype_graphicdesign, servicestype_webdesign, servicestype_other, services_name, services_website from  members_special_services where member = '$memberId'");

      $result['numRows'] = count($query);

      $result['data']  = $query;

      return $result;
  }



  function ad_mem_getMemberPromoterInfo($memberId)

  {



      $query = DB::select("select promotertype_indy, promotertype_club, promotertype_event, promotertype_street, promoter_name, promoter_department, promoter_website from members_promoter where member = '$memberId'");

      $result['numRows'] = count($query);

      $result['data']  = $query;

      return $result;
  }



  function ad_mem_getMemberClothingInfo($memberId)

  {



      $query = DB::select("select clothing_name, clothing_department from members_clothing_apparel where member = '$memberId'");

      $result['numRows'] = count($query);

      $result['data']  = $query;

      return $result;
  }





  function ad_mem_getMemberManagementInfo($memberId)

  {



      $query = DB::select("select managementtype_artist, managementtype_tour, managementtype_personal, managementtype_finance, management_name, management_who, management_industry from members_management where member = '$memberId'");

      $result['numRows'] = count($query);

      $result['data']  = $query;

      return $result;
  }





  function ad_mem_getMemberRecordInfo($memberId)

  {



      $query = DB::select("select labeltype_major, labeltype_indy, labeltype_distribution, label_name, label_department from members_record_label where member = '$memberId'");

      $result['numRows'] = count($query);

      $result['data']  = $query;

      return $result;
  }



  function ad_mem_getMemberMediaInfo($memberId)

  {



      $query = DB::select("select mediatype_tvfilm, mediatype_publication, mediatype_newmedia, mediatype_newsletter, media_name, media_website, media_department from members_mass_media where member = '$memberId'");

      $result['numRows'] = count($query);

      $result['data']  = $query;

      return $result;
  }





  function ad_mem_getMemberRadioInfo($memberId)

  {



      $query = DB::select("select * from members_radio_station where member = '$memberId'");

      $result['numRows'] = count($query);

      $result['data']  = $query;

      return $result;
  }



  /* frontenddb */

  function ad_mem_getMemberInfo($memberId)

  {



      $query = DB::select("select * from  members 

 

  left join members_dj_mixer on members.id = members_dj_mixer.member

 where members.id = '$memberId'");

      $result['numRows'] = count($query);

      $result['data']  = $query;

      return $result;
  }



  function ad_mem_getNumMembers($where, $sort)

  {



      $query = DB::select("select * from  members $where  order by $sort");



      $result = count($query);

      return  $result;
  }





  function ad_mem_getMembers($where, $sort, $start, $limit)

  {

      $query = DB::select("select * from  members $where order by $sort limit $start, $limit");



      $result['numRows']  = count($query);

      $result['data']  = $query;

      return  $result;
  }



  function ad_mem_addMultipleMembers($data)

  {

     $admin_id = Auth::user()->id;

      $result = array();

      $saved = array();

      $exist = array();

      foreach ($data as $email) {



          $email = urlencode(trim($email));



          //  verify email exists or not

          $query1 = DB::select("select * from  members where email = '" . $email . "'");

          $userRows1  = count($query1);



          $query2 = DB::select("select * from  clients where email = '" . $email . "'");

          $userRows2  = count($query2);



          if ($userRows1 < 1 && $userRows2 < 1) {

            
              $password = rand(10000, 1000000);
              $password=trim($password);
              $password1=$password;
              $password=md5($password);

              $insertData = array(
                  'pword' => $password,
                  'email' => $email,
                  'added' => NOW(),
                  'addedby' => $admin_id,
                  'active' => 1,
                 
              );
      
              $insertId = DB::table('members')->insertGetId($insertData);



              // $query = DB::select("INSERT INTO `members` (`pword`, `email`, `added`, `addedby`, `active`) VALUES ('" . $password . "', '" . $email . "', NOW(), '" . $_COOKIE['adminId'] . "', '1')");

              // $insertId = $this->db->insert_id();



              if ($insertId > 0) {

                  $saved[$email] = $password1;
              }
          } else {

              $exist[] = $email;
          }
      }



      $result = array($saved, $exist);

      return $result;
  }





  function ad_mem_addMember($data)
  {

      extract($data);

     $admin_id = Auth::user()->id;

      $query1 = DB::select("select * from  members where uname = '" . $member_username . "'");

      $userRows1  = count($query1);



      $query2 = DB::select("select * from  members where email = '" . $email . "'");

      $userRows2  = count($query2);



      $query3 = DB::select("select * from  clients where uname = '" . $member_username . "'");

      $userRows3  = count($query3);



      $query4 = DB::select("select * from  clients where email = '" . $email . "'");

      $userRows4  = count($query4);



      if ($userRows1 < 1 && $userRows2 < 1 && $userRows3 < 1 && $userRows4 < 1) {







          if (!(isset($djMixer))) {
              $djMixer = 0;
          }

          if (!(isset($radioStation))) {
              $radioStation = 0;
          }

          if (!(isset($massMedia))) {
              $massMedia = 0;
          }

          if (!(isset($recordLabel))) {
              $recordLabel = 0;
          }

          if (!(isset($management))) {
              $management = 0;
          }

          if (!(isset($clothingApparel))) {
              $clothingApparel = 0;
          }

          if (!(isset($promoter))) {
              $promoter = 0;
          }

          if (!(isset($specialServices))) {
              $specialServices = 0;
          }

          if (!(isset($productionTalent))) {
              $productionTalent = 0;
          }



          $country_query = DB::select("select country from  country where countryId = '" . $country . "'");
          $country_result  = $country_query;

          $state_query = DB::select("select name from  states where stateId = '" . $state . "'");
          $state_result  = $state_query;

          if(!empty($state_result[0]->name)){

              $state_result_name = $state_result[0]->name;
          }
          else{
              $state_result_name = '';
          }
          if(!empty($country_result[0]->country)){

              $country_result_country = $country_result[0]->country;
          }
          else{
              $country_result_country = '';
          }

          if(!empty($age)){

              $age = $age;
          }
          else{
              $age = '';
          }

          if(!empty($player)){

              $player = $player;
          }
          else{
              $player = '';
          }

          if(!empty($website)){

              $website = $website;
          }
          else{
              $website = '';
          }

          if(!empty($computer )){

              $computer  = $computer ;
          }
          else{
              $computer  = '';
          }

          $package = (int) $package > 0 ? (int) $package : 2;


          $insertData = array(
              'packageId' => $package ,
              'uname' => urlencode($member_username) ,
              'pword' => $member_password ,
              'ulevel' => 1,
              'rating' => 0,
              'fname' =>  urlencode($firstName) ,
              'lname' => urlencode($lastName) ,
              'stagename' => urlencode($stageName) ,
              'email' => urlencode(trim($email)) ,
              'address1' => urlencode($address1) ,
              'address2' => urlencode($address2) ,
              'city' => urlencode($city) ,
              'state' => $state_result_name ,
              'country' => $country_result_country,
              'zip' =>  urlencode($zip) ,
              'phone' => urlencode($phone) ,
              'added' => NOW(),
              'addedby' => $admin_id ,
              'edited' => NOW(),
              'editedby' => $admin_id ,
              'age' => urlencode($age) ,
              'sex' => $sex ,
              'player' => urlencode($player) ,
              'active' => 1 ,
              'dj_mixer' => $djMixer ,
              'radio_station' => $radioStation ,
              'record_label' => $recordLabel ,
              'management' => $management ,
              'clothing_apparel' => $clothingApparel ,
              'mass_media' => $massMedia ,
              'promoter' => $promoter ,
              'production_talent' => $productionTalent ,
              'special_services' => $specialServices ,
              'website' => urlencode($website) ,
              'computer' => $computer ,
              'reviewPts' => urlencode($memberPoints) ,
              'howheard' => $howheard ,
              'howheardvalue' => $howheardvalue ,
              'resubmission' => 1,
             
          );
  
          $insertId = DB::table('members')->insertGetId($insertData);

          // $query = DB::select("INSERT INTO `members` (`packageId`, `uname`, `pword`, `ulevel`, `rating`, `fname`, `lname`, `stagename`, `email`, `address1`, `address2`, `city`, `state`, `country`, `zip`, `phone`,  `added`, `addedby`, `edited`, `editedby`, `age`, `sex`, `player`, `active`, `dj_mixer`, `radio_station`, `record_label`, `management`, `clothing_apparel`, `mass_media`, `promoter`, `production_talent`, `special_services`, `website`, `computer`, `reviewPts`, `howheard`, `howheardvalue`, `resubmission`) VALUES (" . $package . ", '" . urlencode($member_username) . "', '" . $member_password . "', '1', '0',  '" . urlencode($firstName) . "', '" . urlencode($lastName) . "', '" . urlencode($stageName) . "', '" . urlencode(trim($email)) . "', '" . urlencode($address1) . "', '" . urlencode($address2) . "', '" . urlencode($city) . "', '" . $state_result[0]->name . "', '" . $country_result[0]->country . "',  '" . urlencode($zip) . "', '" . urlencode($phone) . "', NOW(), '" . $_COOKIE['adminId'] . "', NOW(), '" . $_COOKIE['adminId'] . "', '" . urlencode($age) . "', '" . $sex . "', '" . urlencode($player) . "', '1', '" . $djMixer . "',	'" . $radioStation . "', '" . $recordLabel . "',	'" . $management . "',	'" . $clothingApparel . "',	'" . $massMedia . "', '" . $promoter . "', '" . $productionTalent . "', '" . $specialServices . "', '" . urlencode($website) . "', '" . $computer . "', '" . urlencode($memberPoints) . "', '" . $howheard . "', '" . $howheardvalue . "', '1')");



          // $insertId = $this->db->insert_id();

          if ($insertId > 0) {

              DB::select("insert into `member_social_media` (`memberId`, `facebook`, `twitter`, `instagram`, `linkedin`) values ('" . $insertId . "', '" . $facebook . "', '" . $twitter . "', '" . $instagram . "', '" . $linkedin . "')");






              if (!(isset($djMixer))) {
                  $djMixer = 0;
              }

              if (!(isset($radioStation))) {
                  $radioStation = 0;
              }

              if (!(isset($massMedia))) {
                  $massMedia = 0;
              }

              if (!(isset($recordLabel))) {
                  $recordLabel = 0;
              }

              if (!(isset($management))) {
                  $management = 0;
              }

              if (!(isset($clothingApparel))) {
                  $clothingApparel = 0;
              }

              if (!(isset($promoter))) {
                  $promoter = 0;
              }

              if (!(isset($specialServices))) {
                  $specialServices = 0;
              }

              if (!(isset($productionTalent))) {
                  $productionTalent = 0;
              }





              if (!(isset($djtype_commercialreporting))) {
                  $djtype_commercialreporting = 0;
              }

              if (!(isset($djtype_commercialnonreporting))) {
                  $djtype_commercialnonreporting = 0;
              }

              if (!(isset($djtype_club))) {
                  $djtype_club = 0;
              }

              if (!(isset($djtype_mixtape))) {
                  $djtype_mixtape = 0;
              }

              if (!(isset($djtype_satellite))) {
                  $djtype_satellite = 0;
              }

              if (!(isset($djtype_internet))) {
                  $djtype_internet = 0;
              }

              if (!(isset($djtype_college))) {
                  $djtype_college = 0;
              }

              if (!(isset($djtype_pirate))) {
                  $djtype_pirate = 0;
              }





              if (!(isset($djwith_mp3))) {
                  $djwith_mp3 = 0;
              }

              if (!(isset($djwith_mp3_serato))) {
                  $djwith_mp3_serato = 0;
              }

              if (!(isset($djwith_mp3_final))) {
                  $djwith_mp3_final = 0;
              }

              if (!(isset($djwith_mp3_pcdj))) {
                  $djwith_mp3_pcdj = 0;
              }

              if (!(isset($djwith_mp3_ipod))) {
                  $djwith_mp3_ipod = 0;
              }

              if (!(isset($djwith_mp3_other))) {
                  $djwith_mp3_other = 0;
              }

              if (!(isset($djwith_cd))) {
                  $djwith_cd = 0;
              }

              if (!(isset($djwith_cd_stanton))) {
                  $djwith_cd_stanton = 0;
              }

              if (!(isset($djwith_cd_numark))) {
                  $djwith_cd_numark = 0;
              }

              if (!(isset($djwith_cd_american))) {
                  $djwith_cd_american = 0;
              }

              if (!(isset($djwith_cd_vestax))) {
                  $djwith_cd_vestax = 0;
              }

              if (!(isset($djwith_cd_technics))) {
                  $djwith_cd_technics = 0;
              }

              if (!(isset($djwith_cd_gemini))) {
                  $djwith_cd_gemini = 0;
              }

              if (!(isset($djwith_cd_denon))) {
                  $djwith_cd_denon = 0;
              }

              if (!(isset($djwith_cd_gemsound))) {
                  $djwith_cd_gemsound = 0;
              }

              if (!(isset($djwith_cd_pioneer))) {
                  $djwith_cd_pioneer = 0;
              }

              if (!(isset($djwith_cd_tascam))) {
                  $djwith_cd_tascam = 0;
              }

              if (!(isset($djwith_cd_other))) {
                  $djwith_cd_other = 0;
              }

              if (!(isset($djwith_vinyl))) {
                  $djwith_vinyl = 0;
              }

              if (!(isset($djwith_vinyl_12))) {
                  $djwith_vinyl_12 = 0;
              }

              if (!(isset($djwith_vinyl_45))) {
                  $djwith_vinyl_45 = 0;
              }

              if (!(isset($djwith_vinyl_78))) {
                  $djwith_vinyl_78 = 0;
              }





              if (!(isset($clubdj_hiphop))) {
                  $clubdj_hiphop = 0;
              }

              if (!(isset($clubdj_rb))) {
                  $clubdj_rb = 0;
              }

              if (!(isset($clubdj_pop))) {
                  $clubdj_pop = 0;
              }

              if (!(isset($clubdj_reggae))) {
                  $clubdj_reggae = 0;
              }

              if (!(isset($clubdj_house))) {
                  $clubdj_house = 0;
              }

              if (!(isset($clubdj_calypso))) {
                  $clubdj_calypso = 0;
              }

              if (!(isset($clubdj_rock))) {
                  $clubdj_rock = 0;
              }

              if (!(isset($clubdj_techno))) {
                  $clubdj_techno = 0;
              }

              if (!(isset($clubdj_trance))) {
                  $clubdj_trance = 0;
              }

              if (!(isset($clubdj_afro))) {
                  $clubdj_afro = 0;
              }

              if (!(isset($clubdj_reggaeton))) {
                  $clubdj_reggaeton = 0;
              }

              if (!(isset($clubdj_gogo))) {
                  $clubdj_gogo = 0;
              }

              if (!(isset($clubdj_neosoul))) {
                  $clubdj_neosoul = 0;
              }

              if (!(isset($clubdj_oldschool))) {
                  $clubdj_oldschool = 0;
              }

              if (!(isset($clubdj_electronic))) {
                  $clubdj_electronic = 0;
              }

              if (!(isset($clubdj_latin))) {
                  $clubdj_latin = 0;
              }

              if (!(isset($clubdj_dance))) {
                  $clubdj_dance = 0;
              }

              if (!(isset($clubdj_jazz))) {
                  $clubdj_jazz = 0;
              }

              if (!(isset($clubdj_country))) {
                  $clubdj_country = 0;
              }

              if (!(isset($clubdj_world))) {
                  $clubdj_world = 0;
              }





              if (!(isset($clubdj_monday))) {
                  $clubdj_monday = 0;
              }

              if (!(isset($clubdj_tuesday))) {
                  $clubdj_tuesday = 0;
              }

              if (!(isset($clubdj_wednesday))) {
                  $clubdj_wednesday = 0;
              }

              if (!(isset($clubdj_thursday))) {
                  $clubdj_thursday = 0;
              }

              if (!(isset($clubdj_friday))) {
                  $clubdj_friday = 0;
              }

              if (!(isset($clubdj_saturday))) {
                  $clubdj_saturday = 0;
              }

              if (!(isset($clubdj_sunday))) {
                  $clubdj_sunday = 0;
              }

              if (!(isset($clubdj_varies))) {
                  $clubdj_varies = 0;
              }



              if (!(isset($commercialdj_monday))) {
                  $commercialdj_monday = 0;
              }

              if (!(isset($commercialdj_tuesday))) {
                  $commercialdj_tuesday = 0;
              }

              if (!(isset($commercialdj_wednesday))) {
                  $commercialdj_wednesday = 0;
              }

              if (!(isset($commercialdj_thursday))) {
                  $commercialdj_thursday = 0;
              }

              if (!(isset($commercialdj_friday))) {
                  $commercialdj_friday = 0;
              }

              if (!(isset($commercialdj_saturday))) {
                  $commercialdj_saturday = 0;
              }

              if (!(isset($commercialdj_sunday))) {
                  $commercialdj_sunday = 0;
              }

              if (!(isset($commercialdj_varies))) {
                  $commercialdj_varies = 0;
              }





              if (!(isset($noncommercialdj_monday))) {
                  $noncommercialdj_monday = 0;
              }

              if (!(isset($noncommercialdj_tuesday))) {
                  $noncommercialdj_tuesday = 0;
              }

              if (!(isset($noncommercialdj_wednesday))) {
                  $noncommercialdj_wednesday = 0;
              }

              if (!(isset($noncommercialdj_thursday))) {
                  $noncommercialdj_thursday = 0;
              }

              if (!(isset($noncommercialdj_friday))) {
                  $noncommercialdj_friday = 0;
              }

              if (!(isset($noncommercialdj_saturday))) {
                  $noncommercialdj_saturday = 0;
              }

              if (!(isset($noncommercialdj_sunday))) {
                  $noncommercialdj_sunday = 0;
              }

              if (!(isset($noncommercialdj_varies))) {
                  $noncommercialdj_varies = 0;
              }



              if (!(isset($satellitedj_monday))) {
                  $satellitedj_monday = 0;
              }

              if (!(isset($satellitedj_tuesday))) {
                  $satellitedj_tuesday = 0;
              }

              if (!(isset($satellitedj_wednesday))) {
                  $satellitedj_wednesday = 0;
              }

              if (!(isset($satellitedj_thursday))) {
                  $satellitedj_thursday = 0;
              }

              if (!(isset($satellitedj_friday))) {
                  $satellitedj_friday = 0;
              }

              if (!(isset($satellitedj_saturday))) {
                  $satellitedj_saturday = 0;
              }

              if (!(isset($satellitedj_sunday))) {
                  $satellitedj_sunday = 0;
              }

              if (!(isset($satellitedj_varies))) {
                  $satellitedj_varies = 0;
              }



              if (!(isset($internetdj_monday))) {
                  $internetdj_monday = 0;
              }

              if (!(isset($internetdj_tuesday))) {
                  $internetdj_tuesday = 0;
              }

              if (!(isset($internetdj_wednesday))) {
                  $internetdj_wednesday = 0;
              }

              if (!(isset($internetdj_thursday))) {
                  $internetdj_thursday = 0;
              }

              if (!(isset($internetdj_friday))) {
                  $internetdj_friday = 0;
              }

              if (!(isset($internetdj_saturday))) {
                  $internetdj_saturday = 0;
              }

              if (!(isset($internetdj_sunday))) {
                  $internetdj_sunday = 0;
              }

              if (!(isset($internetdj_varies))) {
                  $internetdj_varies = 0;
              }





              if (!(isset($collegedj_monday))) {
                  $collegedj_monday = 0;
              }

              if (!(isset($collegedj_tuesday))) {
                  $collegedj_tuesday = 0;
              }

              if (!(isset($collegedj_wednesday))) {
                  $collegedj_wednesday = 0;
              }

              if (!(isset($collegedj_thursday))) {
                  $collegedj_thursday = 0;
              }

              if (!(isset($collegedj_friday))) {
                  $collegedj_friday = 0;
              }

              if (!(isset($collegedj_saturday))) {
                  $collegedj_saturday = 0;
              }

              if (!(isset($collegedj_sunday))) {
                  $collegedj_sunday = 0;
              }

              if (!(isset($collegedj_varies))) {
                  $collegedj_varies = 0;
              }



              if (!(isset($piratedj_monday))) {
                  $piratedj_monday = 0;
              }

              if (!(isset($piratedj_tuesday))) {
                  $piratedj_tuesday = 0;
              }

              if (!(isset($piratedj_wednesday))) {
                  $piratedj_wednesday = 0;
              }

              if (!(isset($piratedj_thursday))) {
                  $piratedj_thursday = 0;
              }

              if (!(isset($piratedj_friday))) {
                  $piratedj_friday = 0;
              }

              if (!(isset($piratedj_saturday))) {
                  $piratedj_saturday = 0;
              }

              if (!(isset($piratedj_sunday))) {
                  $piratedj_sunday = 0;
              }

              if (!(isset($piratedj_varies))) {
                  $piratedj_varies = 0;
              }
              

              if(!empty($commercialdj_showname)){

                  $commercialdj_showname = $commercialdj_showname;
                  
              }
              else{
       
                  $commercialdj_showname = '';
              }
       
              if(!empty($commercialdj_call)){
       
                  $commercialdj_call = $commercialdj_call;
                  
              }
              else{
       
                  $commercialdj_call = '';
              }
       
              if(!empty($commercialdj_frequency)){
       
                  $commercialdj_frequency = $commercialdj_frequency;
                  
              }
              else{
       
                  $commercialdj_frequency = '';
              }
       
              if(!empty($commercialdj_monday)){
       
                  $commercialdj_monday = $commercialdj_monday;
                  
              }
              else{
       
                  $commercialdj_monday = '';
              }
       
              if(!empty($commercialdj_name)){
       
                  $commercialdj_name = $commercialdj_name;
                  
              }
              else{
       
                  $commercialdj_name = '';
              }
       
              if(!empty($commercialdj_showtime)){
       
                  $commercialdj_showtime = $commercialdj_showtime;
                  
              }
              else{
       
                  $commercialdj_showtime = '';
              }
       
              if(!empty($commercialdj_showtype)){
       
                  $commercialdj_showtype = $commercialdj_showtype;
                  
              }
              else{
       
                  $commercialdj_showtype = '';
              }
       
              if(!empty($noncommercialdj_showtype)){
       
                  $noncommercialdj_showtype = $noncommercialdj_showtype;
                  
              }
              else{
       
                  $noncommercialdj_showtype = '';
              }
       
              if(!empty($clubdj_clubname)){
       
                  $clubdj_clubname = $clubdj_clubname;
                  
              }
              else{
       
                  $clubdj_clubname = '';
              }
              if(!empty($clubdj_capacity)){
       
                  $clubdj_capacity = $clubdj_capacity;
                  
              }
              else{
       
                  $clubdj_capacity = '';
              }
              if(!empty($noncommercialdj_showtime)){
       
                  $noncommercialdj_showtime = $noncommercialdj_showtime;
                  
              }
              else{
       
                  $noncommercialdj_showtime = '';
              }
              if(!empty($noncommercialdj_frequency)){
       
                  $noncommercialdj_frequency = $noncommercialdj_frequency;
                  
              }
              else{
       
                  $noncommercialdj_frequency = '';
              }
              if(!empty($noncommercialdj_name)){
       
                  $noncommercialdj_name = $noncommercialdj_name;
                  
              }
              else{
       
                  $noncommercialdj_name = '';
              }
              if(!empty($noncommercialdj_call)){
       
                  $noncommercialdj_call = $noncommercialdj_call;
                  
              }
              else{
       
                  $noncommercialdj_call = '';
              }
              if(!empty($noncommercialdj_showname)){
       
                  $noncommercialdj_showname = $noncommercialdj_showname;
                  
              }
              else{
       
                  $noncommercialdj_showname = '';
              }
       
       
              if(!empty($mixtapedj_distribution)){
       
                  $mixtapedj_distribution = $mixtapedj_distribution;
                  
              }
              else{
       
                  $mixtapedj_distribution = '';
              }
              if(!empty($mixtapedj_schedule)){
       
                  $mixtapedj_schedule = $mixtapedj_schedule;
                  
              }
              else{
       
                  $mixtapedj_schedule = '';
              }
              if(!empty($mixtapedj_type)){
       
                  $mixtapedj_type = $mixtapedj_type;
                  
              }
              else{
       
                  $mixtapedj_type = '';
              }
              if(!empty($mixtapedj_name)){
       
                  $mixtapedj_name = $mixtapedj_name;
                  
              }
              else{
       
                  $mixtapedj_name = '';
              }
              if(!empty($clubdj_intcountry)){
       
                  $clubdj_intcountry = $clubdj_intcountry;
                  
              }
              else{
       
                  $clubdj_intcountry = '';
              }
              if(!empty($clubdj_state)){
       
                  $clubdj_state = $clubdj_state;
                  
              }
              else{
       
                  $clubdj_state = '';
              }
              if(!empty($clubdj_city)){
       
                  $clubdj_city = $clubdj_city;
                  
              }
              else{
       
                  $clubdj_city = '';
              }
       
              if(!empty($internetdj_showname)){
       
                  $internetdj_showname = $internetdj_showname;
                  
              }
              else{
       
                  $internetdj_showname = '';
              }
       
       
              if(!empty($internetdj_showtype)){
       
                  $internetdj_showtype = $internetdj_showtype;
                  
              }
              else{
       
                  $internetdj_showtype = '';
              }
              if(!empty($internetdj_stationwebsite)){
       
                  $internetdj_stationwebsite = $internetdj_stationwebsite;
                  
              }
              else{
       
                  $internetdj_stationwebsite = '';
              }
              if(!empty($satellitedj_showtime)){
       
                  $satellitedj_showtime = $satellitedj_showtime;
                  
              }
              else{
       
                  $satellitedj_showtime = '';
              }
              if(!empty($satellitedj_channelnumber)){
       
                  $satellitedj_channelnumber = $satellitedj_channelnumber;
                  
              }
              else{
       
                  $satellitedj_channelnumber = '';
              }
              if(!empty($satellitedj_channelname)){
       
                  $satellitedj_channelname = $satellitedj_channelname;
                  
              }
              else{
       
                  $satellitedj_channelname = '';
              }
              if(!empty($satellitedj_showname)){
       
                  $satellitedj_showname = $satellitedj_showname;
                  
              }
              else{
       
                  $satellitedj_showname = '';
              }
              if(!empty($satellitedj_stationname)){
       
                  $satellitedj_stationname = $satellitedj_stationname;
                  
              }
              else{
       
                  $satellitedj_stationname = '';
              }
       
              if(!empty($collegedj_showname)){
       
                  $collegedj_showname = $collegedj_showname;
                  
              }
              else{
       
                  $collegedj_showname = '';
              }
              if(!empty($collegedj_showtype)){
       
                  $collegedj_showtype = $collegedj_showtype;
                  
              }
              else{
       
                  $collegedj_showtype = '';
              }
              if(!empty($collegedj_stationfrequency)){
       
                  $collegedj_stationfrequency = $collegedj_stationfrequency;
                  
              }
              else{
       
                  $collegedj_stationfrequency = '';
              }
              if(!empty($collegedj_collegename)){
       
                  $collegedj_collegename = $collegedj_collegename;
                  
              }
              else{
       
                  $collegedj_collegename = '';
              }
              if(!empty($collegedj_callletters)){
       
                  $collegedj_callletters = $collegedj_callletters;
                  
              }
              else{
       
                  $collegedj_callletters = '';
              }
              if(!empty($internetdj_showtime)){
       
                  $internetdj_showtime = $internetdj_showtime;
                  
              }
              else{
       
                  $internetdj_showtime = '';
              }
       
              if(!empty($piratedj_showtime)){
       
                  $piratedj_showtime = $piratedj_showtime;
                  
              }
              else{
       
                  $piratedj_showtime = '';
              }
              if(!empty($piratedj_showname)){
       
                  $piratedj_showname = $piratedj_showname;
                  
              }
              else{
       
                  $piratedj_showname = '';
              }
              if(!empty($piratedj_stationfrequency)){
       
                  $piratedj_stationfrequency = $piratedj_stationfrequency;
                  
              }
              else{
       
                  $piratedj_stationfrequency = '';
              }
              if(!empty($collegedj_intcountry)){
       
                  $collegedj_intcountry = $collegedj_intcountry;
                  
              }
              else{
       
                  $collegedj_intcountry = '';
              }
              if(!empty($collegedj_state)){
       
                  $collegedj_state = $collegedj_state;
                  
              }
              else{
       
                  $collegedj_state = '';
              }
              if(!empty($collegedj_city)){
       
                  $collegedj_city = $collegedj_city;
                  
              }
              else{
       
                  $collegedj_city = '';
              }
              if(!empty($collegedj_showtime)){
       
                  $collegedj_showtime = $collegedj_showtime;
                  
              }
              else{
       
                  $collegedj_showtime = '';
              }
       
       
              if(!empty($programName)){
       
                  $programName = $programName;
                  
              }
              else{
       
                  $programName = '';
              }
              if(!empty($programHost)){
       
                  $programHost = $programHost;
                  
              }
              else{
       
                  $programHost = '';
              }
              if(!empty($programCall)){
       
                  $programCall = $programCall;
                  
              }
              else{
       
                  $programCall = '';
              }
              if(!empty($stationName)){
       
                  $stationName = $stationName;
                  
              }
              else{
       
                  $stationName = '';
              }
              if(!empty($stationFrequency)){
       
                  $stationFrequency = $stationFrequency;
                  
              }
              else{
       
                  $stationFrequency = '';
              }
              if(!empty($stationCall)){
       
                  $stationCall = $stationCall;
                  
              }
              else{
       
                  $stationCall = '';
              }

              $result = DB::select("INSERT INTO members_dj_mixer (`member`, `djtype_commercialreporting`, `djtype_commercialnonreporting`, `djtype_club`, `djtype_mixtape`, `djtype_satellite`, `djtype_internet`, `djtype_college`, `djtype_pirate`, `djwith_mp3`, `djwith_mp3_serato`, `djwith_mp3_final`, `djwith_mp3_pcdj`, `djwith_mp3_ipod`, `djwith_mp3_other`, `djwith_cd`, `djwith_cd_stanton`, `djwith_cd_numark`, `djwith_cd_american`, `djwith_cd_vestax`, `djwith_cd_technics`, `djwith_cd_gemini`, `djwith_cd_denon`, `djwith_cd_gemsound`, `djwith_cd_pioneer`, `djwith_cd_tascam`, `djwith_cd_other`, `djwith_vinyl`, `djwith_vinyl_12`, `djwith_vinyl_45`, `djwith_vinyl_78`, `commercialdj_showname`, `commercialdj_call`, `commercialdj_name`, `commercialdj_frequency`, `commercialdj_monday`, `commercialdj_tuesday`, `commercialdj_wednesday`, `commercialdj_thursday`, `commercialdj_friday`, `commercialdj_saturday`, `commercialdj_sunday`, `commercialdj_varies`, `commercialdj_showtime`, `commercialdj_showtype`, `noncommercialdj_showname`, `noncommercialdj_call`, `noncommercialdj_name`, `noncommercialdj_frequency`, `noncommercialdj_monday`, `noncommercialdj_tuesday`, `noncommercialdj_wednesday`, `noncommercialdj_thursday`, `noncommercialdj_friday`, `noncommercialdj_saturday`, `noncommercialdj_sunday`, `noncommercialdj_varies`, `noncommercialdj_showtime`, `noncommercialdj_showtype`, `clubdj_clubname`, `clubdj_capacity`, `clubdj_hiphop`, `clubdj_rb`, `clubdj_pop`, `clubdj_reggae`, `clubdj_house`, `clubdj_calypso`, `clubdj_rock`, `clubdj_techno`, `clubdj_trance`, `clubdj_afro`, `clubdj_reggaeton`, `clubdj_gogo`, `clubdj_neosoul`, `clubdj_oldschool`, `clubdj_electronic`, `clubdj_latin`, `clubdj_dance`, `clubdj_jazz`, `clubdj_country`, `clubdj_world`, `clubdj_monday`, `clubdj_tuesday`, `clubdj_wednesday`, `clubdj_thursday`, `clubdj_friday`, `clubdj_saturday`, `clubdj_sunday`, `clubdj_varies`, `clubdj_city`, `clubdj_state`, `clubdj_intcountry`, `mixtapedj_name`, `mixtapedj_type`, `mixtapedj_schedule`, `mixtapedj_distribution`, `satellitedj_stationname`, `satellitedj_showname`, `satellitedj_channelname`, `satellitedj_channelnumber`, `satellitedj_monday`, `satellitedj_tuesday`, `satellitedj_wednesday`, `satellitedj_thursday`, `satellitedj_friday`, `satellitedj_saturday`, `satellitedj_sunday`, `satellitedj_showtime`, `internetdj_stationwebsite`, `internetdj_showtype`, `internetdj_showname`, `internetdj_monday`, `internetdj_tuesday`, `internetdj_wednesday`, `internetdj_thursday`, `internetdj_friday`, `internetdj_saturday`, `internetdj_sunday`, `internetdj_varies`, `internetdj_showtime`, `collegedj_callletters`, `collegedj_collegename`, `collegedj_stationfrequency`, `collegedj_showtype`, `collegedj_showname`, `collegedj_monday`, `collegedj_tuesday`, `collegedj_wednesday`, `collegedj_thursday`, `collegedj_friday`, `collegedj_saturday`, `collegedj_sunday`, `collegedj_varies`, `collegedj_showtime`, `collegedj_city`, `collegedj_state`, `collegedj_intcountry`, `piratedj_stationfrequency`, `piratedj_showname`, `piratedj_monday`, `piratedj_tuesday`, `piratedj_wednesday`, `piratedj_thursday`, `piratedj_friday`, `piratedj_saturday`, `piratedj_sunday`, `piratedj_varies`, `piratedj_showtime`) 
              VALUES (
              '" . (int) $insertId . "', 
              '" . (int) $djtype_commercialreporting . "', 
              '" . (int) $djtype_commercialnonreporting . "', 
              '" . (int) $djtype_club . "', 
              '" . (int) $djtype_mixtape . "', 
              '" . (int) $djtype_satellite . "', 
              '" . (int) $djtype_internet . "', 
              '" . (int) $djtype_college . "', 
              '" . (int) $djtype_pirate . "', 
              '" . (int) $djwith_mp3 . "', 
              '" . (int) $djwith_mp3_serato . "', 
              '" . (int) $djwith_mp3_final . "', 
              '" . (int) $djwith_mp3_pcdj . "', 
              '" . (int) $djwith_mp3_ipod . "', 
              '" . (int) $djwith_mp3_other . "', 
              '" . (int) $djwith_cd . "', 
              '" . (int) $djwith_cd_stanton . "', 
              '" . (int) $djwith_cd_numark . "', 
              '" . (int) $djwith_cd_american . "', 
              '" . (int) $djwith_cd_vestax . "', 
              '" . (int) $djwith_cd_technics . "', 
              '" . (int) $djwith_cd_gemini . "', 
              '" . (int) $djwith_cd_denon . "', 
              '" . (int) $djwith_cd_gemsound . "', 
              '" . (int) $djwith_cd_pioneer . "', 
              '" . (int) $djwith_cd_tascam . "', 
              '" . (int) $djwith_cd_other . "', 
              '" . (int) $djwith_vinyl . "', 
              '" . (int) $djwith_vinyl_12 . "', 
              '" . (int) $djwith_vinyl_45 . "', 
              '" . (int) $djwith_vinyl_78 . "', 
              '" . addslashes($commercialdj_showname)."',
              '" . addslashes($commercialdj_call)."',
              '" . addslashes($commercialdj_name)."',
              '" . addslashes($commercialdj_frequency)."',
              '" . (int) $commercialdj_monday."', 
              '" . (int) $commercialdj_tuesday."', 
              '" . (int) $commercialdj_wednesday."', 
              '" . (int) $commercialdj_thursday."', 
              '" . (int) $commercialdj_friday."', 
              '" . (int) $commercialdj_saturday."', 
              '" . (int) $commercialdj_sunday."', 
              '" . (int) $commercialdj_varies."', 
              '" . addslashes($commercialdj_showtime)."', 
              '" . addslashes($commercialdj_showtype)."', 
              '" . addslashes($noncommercialdj_showname)."', 
              '" . addslashes($noncommercialdj_call)."', 
              '" . addslashes($noncommercialdj_name)."', 
              '" . addslashes($noncommercialdj_frequency)."', 
              '" . (int) $noncommercialdj_monday."', 
              '" . (int) $noncommercialdj_tuesday."', 
              '" . (int) $noncommercialdj_wednesday."', 
              '" . (int) $noncommercialdj_thursday."', 
              '" . (int) $noncommercialdj_friday."', 
              '" . (int) $noncommercialdj_saturday."', 
              '" . (int) $noncommercialdj_sunday."', 
              '" . (int) $noncommercialdj_varies."', 
              '" . addslashes($noncommercialdj_showtime)."', 
              '" . addslashes($noncommercialdj_showtype)."', 
              '" . addslashes($clubdj_clubname)."', 
              '" . addslashes($clubdj_capacity)."', 
              '" . (int) $clubdj_hiphop."', 
              '" . (int) $clubdj_rb."', 
              '" . (int) $clubdj_pop."', 
              '" . (int) $clubdj_reggae."', 
              '" . (int) $clubdj_house."', 
              '" . (int) $clubdj_calypso."', 
              '" . (int) $clubdj_rock."', 
              '" . (int) $clubdj_techno."', 
              '" . (int) $clubdj_trance."', 
              '" . (int) $clubdj_afro."', 
              '" . (int) $clubdj_reggaeton."', 
              '" . (int) $clubdj_gogo."', 
              '" . (int) $clubdj_neosoul."', 
              '" . (int) $clubdj_oldschool."', 
              '" . (int) $clubdj_electronic."', 
              '" . (int) $clubdj_latin."', 
              '" . (int) $clubdj_dance."', 
              '" . (int) $clubdj_jazz."', 
              '" . (int) $clubdj_country."', 
              '" . (int) $clubdj_world."', 
              '" . (int) $clubdj_monday."', 
              '" . (int) $clubdj_tuesday."', 
              '" . (int) $clubdj_wednesday."', 
              '" . (int) $clubdj_thursday."', 
              '" . (int) $clubdj_friday."', 
              '" . (int) $clubdj_saturday."', 
              '" . (int) $clubdj_sunday."', 
              '" . (int) $clubdj_varies."', 
              '" . addslashes($clubdj_city)."', 
              '" . addslashes($clubdj_state)."', 
              '" . addslashes($clubdj_intcountry)."', 
              '" . addslashes($mixtapedj_name)."', 
              '" . addslashes($mixtapedj_type)."', 
              '" . addslashes($mixtapedj_schedule)."', 
              '" . addslashes($mixtapedj_distribution)."', 
              '" . addslashes($satellitedj_stationname)."', 
              '" . addslashes($satellitedj_showname)."', 
              '" . addslashes($satellitedj_channelname)."', 
              '" . addslashes($satellitedj_channelnumber)."', 
              '" . (int) $satellitedj_monday."', 
              '" . (int) $satellitedj_tuesday."', 
              '" . (int) $satellitedj_wednesday."', 
              '" . (int) $satellitedj_thursday."', 
              '" . (int) $satellitedj_friday."', 
              '" . (int) $satellitedj_saturday."', 
              '" . (int) $satellitedj_sunday."', 
              '" . addslashes($satellitedj_showtime)."', 
              '" . addslashes($internetdj_stationwebsite)."', 
              '" . addslashes($internetdj_showtype)."', 
              '" . addslashes($internetdj_showname)."', 
              '" . (int) $internetdj_monday."', 
              '" . (int) $internetdj_tuesday."', 
              '" . (int) $internetdj_wednesday."', 
              '" . (int) $internetdj_thursday."', 
              '" . (int) $internetdj_friday."', 
              '" . (int) $internetdj_saturday."', 
              '" . (int) $internetdj_sunday."', 
              '" . (int) $internetdj_varies."', 
              '" . addslashes($internetdj_showtime)."', 
              '" . addslashes($collegedj_callletters)."', 
              '" . addslashes($collegedj_collegename)."', 
              '" . addslashes($collegedj_stationfrequency)."', 
              '" . addslashes($collegedj_showtype)."', 
              '" . addslashes($collegedj_showname)."', 
              '" . (int) $collegedj_monday."', 
              '" . (int) $collegedj_tuesday."', 
              '" . (int) $collegedj_wednesday."', 
              '" . (int) $collegedj_thursday."', 
              '" . (int) $collegedj_friday."', 
              '" . (int) $collegedj_saturday."', 
              '" . (int) $collegedj_sunday."', 
              '" . (int) $collegedj_varies."', 
              '" . addslashes($collegedj_showtime)."', 
              '" . addslashes($collegedj_city)."', 
              '" . addslashes($collegedj_state)."', 
              '" . addslashes($collegedj_intcountry)."', 
              '" . addslashes($piratedj_stationfrequency)."', 
              '" . addslashes($piratedj_showname)."', 
              '" . (int) $piratedj_monday."', 
              '" . (int) $piratedj_tuesday."', 
              '" . (int) $piratedj_wednesday."', 
              '" . (int) $piratedj_thursday."', 
              '" . (int) $piratedj_friday."', 
              '" . (int) $piratedj_saturday."', 
              '" . (int) $piratedj_sunday."', 
              '" . (int) $piratedj_varies."', 
              '" . addslashes($piratedj_showtime)."')");

              /*	  	  $result = DB::select("insert into  members_dj_mixer (`member`, `djtype_commercialreporting`, `djtype_commercialnonreporting`, `djtype_club`, `djtype_mixtape`, `djtype_satellite`, `djtype_internet`, `djtype_college`, `djtype_pirate`, `djwith_mp3`, `djwith_mp3_serato`, `djwith_mp3_final`, `djwith_mp3_pcdj`, `djwith_mp3_ipod`, `djwith_mp3_other`, `djwith_cd`, `djwith_vinyl`, `clubdj_clubname`, `clubdj_capacity`, `clubdj_hiphop`, `clubdj_rb`, `clubdj_pop`, `clubdj_reggae`, `clubdj_house`, `clubdj_calypso`, `clubdj_rock`, `clubdj_techno`, `clubdj_trance`, `clubdj_afro`, `clubdj_reggaeton`, `clubdj_gogo`, `clubdj_neosoul`, `clubdj_oldschool`, `clubdj_electronic`, `clubdj_latin`, `clubdj_dance`, `clubdj_jazz`, `clubdj_country`, `clubdj_world`, `clubdj_monday`, `clubdj_tuesday`, `clubdj_wednesday`, `clubdj_thursday`, `clubdj_friday`, `clubdj_saturday`, `clubdj_sunday`, `clubdj_varies`, `clubdj_city`, `clubdj_state`, `clubdj_intcountry`) values('". $insertId . "', '$djtype_commercialreporting',  '$djtype_commercialnonreporting', '$djtype_club', '$djtype_mixtape', '$djtype_satellite',  '$djtype_internet', '$djtype_college', '$djtype_pirate', '$djwith_mp3', '$djwith_mp3_serato', '$djwith_mp3_final', '$djwith_mp3_pcdj',  '$djwith_mp3_ipod', '$djwith_mp3_other', '$djwith_cd', '$djwith_vinyl', '$clubdj_clubname', '$clubdj_capacity', '$clubdj_hiphop', '$clubdj_rb', '$clubdj_pop', '$clubdj_reggae', '$clubdj_house', '$clubdj_calypso', '$clubdj_rock', '$clubdj_techno', '$clubdj_trance', '$clubdj_afro',  '$clubdj_reggaeton', '$clubdj_gogo', '$clubdj_neosoul', '$clubdj_oldschool', '$clubdj_electronic', '$clubdj_latin',  '$clubdj_dance', '$clubdj_jazz', '$clubdj_country', '$clubdj_world', '$clubdj_monday', '$clubdj_tuesday',  '$clubdj_wednesday', '$clubdj_thursday', '$clubdj_friday', '$clubdj_saturday',  '$clubdj_sunday', '$clubdj_varies', '$clubdj_city', '$clubdj_state', '$clubdj_intcountry')");  

*/





              // radio station

              if (!(isset($airMonday))) {
                  $airMonday = 0;
              }

              if (!(isset($airTuesday))) {
                  $airTuesday = 0;
              }

              if (!(isset($airWednesday))) {
                  $airWednesday = 0;
              }

              if (!(isset($airThursday))) {
                  $airThursday = 0;
              }

              if (!(isset($airFriday))) {
                  $airFriday = 0;
              }

              if (!(isset($airSaturday))) {
                  $airSaturday = 0;
              }

              if (!(isset($airSunday))) {
                  $airSunday = 0;
              }

              if (!(isset($airVaries))) {
                  $airVaries = 0;
              }





              if (!(isset($musicMonday))) {
                  $musicMonday = 0;
              }

              if (!(isset($musicTuesday))) {
                  $musicTuesday = 0;
              }

              if (!(isset($musicWednesday))) {
                  $musicWednesday = 0;
              }

              if (!(isset($musicThursday))) {
                  $musicThursday = 0;
              }

              if (!(isset($musicFriday))) {
                  $musicFriday = 0;
              }

              if (!(isset($musicSaturday))) {
                  $musicSaturday = 0;
              }

              if (!(isset($musicSunday))) {
                  $musicSunday = 0;
              }

              if (!(isset($musicVaries))) {
                  $musicVaries = 0;
              }





              if (!(isset($programMonday))) {
                  $programMonday = 0;
              }

              if (!(isset($programTuesday))) {
                  $programTuesday = 0;
              }

              if (!(isset($programWednesday))) {
                  $programWednesday = 0;
              }

              if (!(isset($programThursday))) {
                  $programThursday = 0;
              }

              if (!(isset($programFriday))) {
                  $programFriday = 0;
              }

              if (!(isset($programSaturday))) {
                  $programSaturday = 0;
              }

              if (!(isset($programSunday))) {
                  $programSunday = 0;
              }

              if (!(isset($programVaries))) {
                  $programVaries = 0;
              }





              if (!(isset($radioMusic))) {
                  $radioMusic = 0;
              }

              if (!(isset($radioProgram))) {
                  $radioProgram = 0;
              }

              if (!(isset($radioAir))) {
                  $radioAir = 0;
              }

              if (!(isset($radioPromotion))) {
                  $radioPromotion = 0;
              }

              if (!(isset($radioProduction))) {
                  $radioProduction = 0;
              }

              if (!(isset($radioSales))) {
                  $radioSales = 0;
              }

              if (!(isset($radioIt))) {
                  $radioIt = 0;
              }

              if(!empty($airTime)){

                  $airTime = $airTime;
                  
              }
              else{
       
                  $airTime = '';
              }
              if(!empty($airName)){
       
                  $airName = $airName;
                  
              }
              else{
       
                  $airName = '';
              }
              if(!empty($musicTime)){
       
                  $musicTime = $musicTime;
                  
              }
              else{
       
                  $musicTime = '';
              }
              if(!empty($musicName)){
       
                  $musicName = $musicName;
                  
              }
              else{
       
                  $musicName = '';
              }
              if(!empty($musicHost)){
       
                  $musicHost = $musicHost;
                  
              }
              else{
       
                  $musicHost = '';
              }
              if(!empty($musicCall)){
       
                  $musicCall = $musicCall;
                  
              }
              else{
       
                  $musicCall = '';
              }
             
              
              if(!empty($programTime)){
       
                  $programTime = $programTime;
                  
              }
              else{
       
                  $programTime = '';
              }
              

              DB::select("INSERT INTO `members_radio_station` (`member`, `radiotype_musicdirector`, `radiotype_programdirector`, `radiotype_jock`, `radiotype_promotion`, `radiotype_production`, `radiotype_sales`, `radiotype_tech`, `stationcallletters`, `stationfrequency`, `stationname`, `programdirector_stationcallletters`, `programdirector_host`, `programdirector_showname`, `programdirector_showtime`, `programdirector_monday`, `programdirector_tuesday`, `programdirector_wednesday`, `programdirector_thursday`, `programdirector_friday`, `programdirector_saturday`, `programdirector_sunday`, `programdirector_varies`, `musicdirector_stationcallletters`, `musicdirector_host`, `musicdirector_showname`, `musicdirector_showtime`, `musicdirector_monday`, `musicdirector_tuesday`, `musicdirector_wednesday`, `musicdirector_thursday`, `musicdirector_friday`, `musicdirector_saturday`, `musicdirector_sunday`, `musicdirector_varies`, `onairpersonality_showname`, `onairpersonality_showtime`, `onairpersonality_monday`, `onairpersonality_tuesday`, `onairpersonality_wednesday`, `onairpersonality_thursday`, `onairpersonality_friday`, `onairpersonality_saturday`, `onairpersonality_sunday`, `onairpersonality_varies`) 
              VALUES (
                  '".(int) $insertId."',
                  ".(int) $radioMusic.",
                  ".(int) $radioProgram.",
                  ".(int) $radioAir.",
                  ".(int) $radioPromotion.",
                  ".(int) $radioProduction.",
                  ".(int) $radioSales.",
                  ".(int) $radioIt.",
                  '".addslashes($stationCall)."',
                  '".addslashes($stationFrequency)."',
                  '".addslashes($stationName)."',
                  '".addslashes($programCall)."',
                  '".addslashes($programHost)."',
                  '".addslashes($programName)."',
                  '".addslashes($programTime)."',
                  ".(int) $programMonday.",
                  ".(int) $programTuesday.",
                  ".(int) $programWednesday.",
                  ".(int) $programThursday.",
                  ".(int) $programFriday.",
                  ".(int) $programSaturday.",
                  ".(int) $programSunday.",
                  ".(int) $programVaries.",
                  '".addslashes($musicCall)."',
                  '".addslashes($musicHost)."',
                  '".addslashes($musicName)."',
                  '".addslashes($musicTime)."',
                  ".(int) $musicMonday.",
                  ".(int) $musicTuesday.",
                  ".(int) $musicWednesday.",
                  ".(int) $musicThursday.",
                  ".(int) $musicFriday.",
                  ".(int) $musicSaturday.",
                  ".(int) $musicSunday.",
                  ".(int) $musicVaries.",
                  '".addslashes($airName)."',
                  '".addslashes($airTime)."',
                  ".(int) $airMonday.",
                  ".(int) $airTuesday.",
                  ".(int) $airWednesday.",
                  ".(int) $airThursday.",
                  ".(int) $airFriday.",
                  ".(int) $airSaturday.",
                  ".(int) $airSunday.",
                  ".(int) $airVaries.")");

              // media

              if (!(isset($massTv))) {
                  $massTv = 0;
              }

              if (!(isset($massPublication))) {
                  $massPublication = 0;
              }

              if (!(isset($massDotcom))) {
                  $massDotcom = 0;
              }

              if (!(isset($massNewsletter))) {
                  $massNewsletter = 0;
              }

              if(!empty($massDepartment)){

                  $massDepartment = $massDepartment;
                  
              }
              else{
       
                  $massDepartment = '';
              }
              if(!empty($massWebsite)){
       
                  $massWebsite = $massWebsite;
                  
              }
              else{
       
                  $massWebsite = '';
              }
              if(!empty($massName)){
       
                  $massName = $massName;
                  
              }
              else{
       
                  $massName = '';
              }



              DB::select("insert into `members_mass_media` (`member`, `mediatype_tvfilm`, `mediatype_publication`, `mediatype_newmedia`, `mediatype_newsletter`, `media_name`, `media_website`, `media_department`) values ('" . $insertId . "', '" . $massTv . "', '" . $massPublication . "', '" . $massDotcom . "', '" . $massNewsletter . "', '" . $massName . "', '" . $massWebsite . "', '" . $massDepartment . "')");





              // record label

              if (!(isset($recordMajor))) {
                  $recordMajor = 0;
              }

              if (!(isset($recordIndy))) {
                  $recordIndy = 0;
              }

              if (!(isset($recordDistribution))) {
                  $recordDistribution = 0;
              }

              if(!empty($recordDepartment)){

                  $recordDepartment = $recordDepartment;
                  
              }
              else{
       
                  $recordDepartment = '';
              }
              if(!empty($promoterName)){
       
                  $promoterName = $promoterName;
                  
              }
              else{
       
                  $promoterName = '';
              }
       
              if(!empty($promoterDepartment)){
       
                  $promoterDepartment = $promoterDepartment;
                  
              }
              else{
       
                  $promoterDepartment = '';
              }
              if(!empty($recordName)){
       
                  $recordName = $recordName;
                  
              }
              else{
       
                  $recordName = '';
              }
              if(!empty($clothingDepartment)){
       
                  $clothingDepartment = $clothingDepartment;
                  
              }
              else{
       
                  $clothingDepartment = '';
              }
              if(!empty($clothingName)){
       
                  $clothingName = $clothingName;
                  
              }
              else{
       
                  $clothingName = '';
              }
              if(!empty($managementIndustry)){
       
                  $managementIndustry = $managementIndustry;
                  
              }
              else{
       
                  $managementIndustry = '';
              }
              if(!empty($managementWho)){
       
                  $managementWho = $managementWho;
                  
              }
              else{
       
                  $managementWho = '';
              }
              if(!empty($managementName)){
       
                  $managementName = $managementName;
                  
              }
              else{
       
                  $managementName = '';
              }





              DB::select("insert into `members_record_label` (`member`, `labeltype_major`, `labeltype_indy`, `labeltype_distribution`, `label_name`, `label_department`) values ('" . $insertId . "', '" . $recordMajor . "', '" . $recordIndy . "', '" . $recordDistribution . "', '" . $recordName . "', '" . $recordDepartment . "')");







              // management

              if (!(isset($managementArtist))) {
                  $managementArtist = 0;
              }

              if (!(isset($managementTour))) {
                  $managementTour = 0;
              }

              if (!(isset($managementPersonal))) {
                  $managementPersonal = 0;
              }

              if (!(isset($managementFinance))) {
                  $managementFinance = 0;
              }

              if(!empty($managementName)){

                  $managementName = $managementName;
                  
              }
              else{
       
                  $managementName = '';
              }
              if(!empty($managementName)){
       
                  $managementName = $managementName;
                  
              }
              else{
       
                  $managementName = '';
              }
              if(!empty($managementName)){
       
                  $managementName = $managementName;
                  
              }
              else{
       
                  $managementName = '';
              }
              if(!empty($productionName)){
       
                  $productionName = $productionName;
                  
              }
              else{
       
                  $productionName = '';
              }
              if(!empty($specialWebsite)){
       
                  $specialWebsite = $specialWebsite;
                  
              }
              else{
       
                  $specialWebsite = '';
              }
              if(!empty($specialName)){
       
                  $specialName = $specialName;
                  
              }
              else{
       
                  $specialName = '';
              }
              if(!empty($promoterWebsite)){
       
                  $promoterWebsite = $promoterWebsite;
                  
              }
              else{
       
                  $promoterWebsite = '';
              }



              DB::select("insert into `members_management` (`member`, `managementtype_artist`, `managementtype_tour`, `managementtype_personal`, `managementtype_finance`, `management_name`, `management_who`, `management_industry`) values ('" . $insertId . "', '" . $managementArtist . "', '" . $managementTour . "', '" . $managementPersonal . "', '" . $managementFinance . "', '" . $managementName . "', '" . $managementWho . "', '" . $managementIndustry . "')");





              // clothing



              DB::select("insert into `members_clothing_apparel` (`member`, `clothing_name`, `clothing_department`) values ('" . $insertId . "', '" . $clothingName . "', '" . $clothingDepartment . "')");



              // promoter

              if (!(isset($promoterIndy))) {
                  $promoterIndy = 0;
              }

              if (!(isset($promoterClub))) {
                  $promoterClub = 0;
              }

              if (!(isset($promoterSpecial))) {
                  $promoterSpecial = 0;
              }

              if (!(isset($promoterStreet))) {
                  $promoterStreet = 0;
              }





              DB::select("insert into `members_promoter` (`member`, `promotertype_indy`, `promotertype_club`, `promotertype_event`, `promotertype_street`, `promoter_name`, `promoter_department`, `promoter_website`) values ('" . $insertId . "', '" . $promoterIndy . "', '" . $promoterClub . "', '" . $promoterSpecial . "', '" . $promoterStreet . "', '" . $promoterName . "', '" . $promoterDepartment . "', '" . $promoterWebsite . "')");





              // special

              if (!(isset($specialCorporate))) {
                  $specialCorporate = 0;
              }

              if (!(isset($specialGraphic))) {
                  $specialGraphic = 0;
              }

              if (!(isset($specialWeb))) {
                  $specialWeb = 0;
              }

              if (!(isset($specialOther))) {
                  $specialOther = 0;
              }





              DB::select("insert into `members_special_services` (`member`, `servicestype_corporate`, `servicestype_graphicdesign`, `servicestype_webdesign`, `servicestype_other`, `services_name`, `services_website`) values ('" . $insertId . "', '" . $specialCorporate . "', '" . $specialGraphic . "', '" . $specialWeb . "', '" . $specialOther . "', '" . $specialName . "', '" . $specialWebsite . "')");



              // production

              if (!(isset($productionArtist))) {
                  $productionArtist = 0;
              }

              if (!(isset($productionProducer))) {
                  $productionProducer = 0;
              }

              if (!(isset($productionChoregrapher))) {
                  $productionChoregrapher = 0;
              }

              if (!(isset($productionSound))) {
                  $productionSound = 0;
              }



              DB::select("insert into `members_production_talent` (`member`, `productiontype_artist`, `productiontype_producer`, `productiontype_choreographer`, `productiontype_sound`, `production_name`) values ('" . $insertId . "', '" . $productionArtist . "', '" . $productionProducer . "', '" . $productionChoregrapher . "', '" . $productionSound . "', '" . $productionName . "')");
          }

          $result1['success'] = $insertId;

          $result1['type'] = 0;
      } else {

          $result1['success'] = 0;



          if ($userRows1 > 0 || $userRows3 > 0) {

              $result1['type'] = 1;
          } else if ($userRows2 > 0 || $userRows4 > 0) {

              $result1['type'] = 2;
          }
      }

      return $result1;
  }


  function ad_mem_checkDuplicateMemberEmail($data, $memberId){
      extract($data);
      $query = DB::select("select id from members where email= '" . urlencode(trim($email)) . "'");
      
      if(count($query)>0){
          return 0;
      }
      return 1;
  }


  function ad_mem_updateMember($data, $memberId)

  {



      extract($data);

    $admin_id = Auth::user()->id;

      if (!(isset($djMixer))) {
          $djMixer = 0;
      }

      if (!(isset($radioStation))) {
          $radioStation = 0;
      }

      if (!(isset($massMedia))) {
          $massMedia = 0;
      }

      if (!(isset($recordLabel))) {
          $recordLabel = 0;
      }

      if (!(isset($management))) {
          $management = 0;
      }

      if (!(isset($clothingApparel))) {
          $clothingApparel = 0;
      }

      if (!(isset($promoter))) {
          $promoter = 0;
      }

      if (!(isset($specialServices))) {
          $specialServices = 0;
      }

      if (!(isset($productionTalent))) {
          $productionTalent = 0;
      }



      

      $query = DB::select("update members set uname = '" . urlencode($userName) . "', fname = '" . urlencode($firstName) . "', lname = '" . urlencode($lastName) . "', stagename = '" . urlencode($stageName) . "', email = '" . urlencode(trim($email)) . "', address1 = '" . urlencode($address1) . "', address2 = '" . urlencode($address2) . "', city = '" . urlencode($city) . "', state = '" . urlencode($state) . "', country = '" . urlencode($country) . "', zip = '" . urlencode($zip) . "', phone = '" . urlencode($phone) . "',  edited = NOW(), editedby = '" . $admin_id . "', age = '" . urlencode($age) . "', sex = '" . urlencode($sex) . "', player = '" . urlencode($player) . "', dj_mixer = '" . $djMixer . "', radio_station = '" . $radioStation . "', record_label = '" . $recordLabel . "', management = '" . $management . "', clothing_apparel = '" . $clothingApparel . "', mass_media = '" . $massMedia . "', promoter = '" . $promoter . "', production_talent = '" . $productionTalent . "', special_services = '" . $specialServices . "', website = '" . urlencode($website) . "', computer = '" . urlencode($computer) . "', reviewPts = '" . urlencode($memberPoints) . "', howheard = '" . $howheard . "', howheardvalue = '" . $howheardvalue . "' where id = '" . $memberId . "'");













      if (!(isset($djtype_commercialreporting))) {
          $djtype_commercialreporting = 0;
      }

      if (!(isset($djtype_commercialnonreporting))) {
          $djtype_commercialnonreporting = 0;
      }

      if (!(isset($djtype_club))) {
          $djtype_club = 0;
      }

      if (!(isset($djtype_mixtape))) {
          $djtype_mixtape = 0;
      }

      if (!(isset($djtype_satellite))) {
          $djtype_satellite = 0;
      }

      if (!(isset($djtype_internet))) {
          $djtype_internet = 0;
      }

      if (!(isset($djtype_college))) {
          $djtype_college = 0;
      }

      if (!(isset($djtype_pirate))) {
          $djtype_pirate = 0;
      }





      if (!(isset($djwith_mp3))) {
          $djwith_mp3 = 0;
      }

      if (!(isset($djwith_mp3_serato))) {
          $djwith_mp3_serato = 0;
      }

      if (!(isset($djwith_mp3_final))) {
          $djwith_mp3_final = 0;
      }

      if (!(isset($djwith_mp3_pcdj))) {
          $djwith_mp3_pcdj = 0;
      }

      if (!(isset($djwith_mp3_ipod))) {
          $djwith_mp3_ipod = 0;
      }

      if (!(isset($djwith_mp3_other))) {
          $djwith_mp3_other = 0;
      }

      if (!(isset($djwith_cd))) {
          $djwith_cd = 0;
      }

      if (!(isset($djwith_cd_stanton))) {
          $djwith_cd_stanton = 0;
      }

      if (!(isset($djwith_cd_numark))) {
          $djwith_cd_numark = 0;
      }

      if (!(isset($djwith_cd_american))) {
          $djwith_cd_american = 0;
      }

      if (!(isset($djwith_cd_vestax))) {
          $djwith_cd_vestax = 0;
      }

      if (!(isset($djwith_cd_technics))) {
          $djwith_cd_technics = 0;
      }

      if (!(isset($djwith_cd_gemini))) {
          $djwith_cd_gemini = 0;
      }

      if (!(isset($djwith_cd_denon))) {
          $djwith_cd_denon = 0;
      }

      if (!(isset($djwith_cd_gemsound))) {
          $djwith_cd_gemsound = 0;
      }

      if (!(isset($djwith_cd_pioneer))) {
          $djwith_cd_pioneer = 0;
      }

      if (!(isset($djwith_cd_tascam))) {
          $djwith_cd_tascam = 0;
      }

      if (!(isset($djwith_cd_other))) {
          $djwith_cd_other = 0;
      }

      if (!(isset($djwith_vinyl))) {
          $djwith_vinyl = 0;
      }

      if (!(isset($djwith_vinyl_12))) {
          $djwith_vinyl_12 = 0;
      }

      if (!(isset($djwith_vinyl_45))) {
          $djwith_vinyl_45 = 0;
      }

      if (!(isset($djwith_vinyl_78))) {
          $djwith_vinyl_78 = 0;
      }





      if (!(isset($clubdj_hiphop))) {
          $clubdj_hiphop = 0;
      }

      if (!(isset($clubdj_rb))) {
          $clubdj_rb = 0;
      }

      if (!(isset($clubdj_pop))) {
          $clubdj_pop = 0;
      }

      if (!(isset($clubdj_reggae))) {
          $clubdj_reggae = 0;
      }

      if (!(isset($clubdj_house))) {
          $clubdj_house = 0;
      }

      if (!(isset($clubdj_calypso))) {
          $clubdj_calypso = 0;
      }

      if (!(isset($clubdj_rock))) {
          $clubdj_rock = 0;
      }

      if (!(isset($clubdj_techno))) {
          $clubdj_techno = 0;
      }

      if (!(isset($clubdj_trance))) {
          $clubdj_trance = 0;
      }

      if (!(isset($clubdj_afro))) {
          $clubdj_afro = 0;
      }

      if (!(isset($clubdj_reggaeton))) {
          $clubdj_reggaeton = 0;
      }

      if (!(isset($clubdj_gogo))) {
          $clubdj_gogo = 0;
      }

      if (!(isset($clubdj_neosoul))) {
          $clubdj_neosoul = 0;
      }

      if (!(isset($clubdj_oldschool))) {
          $clubdj_oldschool = 0;
      }

      if (!(isset($clubdj_electronic))) {
          $clubdj_electronic = 0;
      }

      if (!(isset($clubdj_latin))) {
          $clubdj_latin = 0;
      }

      if (!(isset($clubdj_dance))) {
          $clubdj_dance = 0;
      }

      if (!(isset($clubdj_jazz))) {
          $clubdj_jazz = 0;
      }

      if (!(isset($clubdj_country))) {
          $clubdj_country = 0;
      }

      if (!(isset($clubdj_world))) {
          $clubdj_world = 0;
      }





      if (!(isset($clubdj_monday))) {
          $clubdj_monday = 0;
      }

      if (!(isset($clubdj_tuesday))) {
          $clubdj_tuesday = 0;
      }

      if (!(isset($clubdj_wednesday))) {
          $clubdj_wednesday = 0;
      }

      if (!(isset($clubdj_thursday))) {
          $clubdj_thursday = 0;
      }

      if (!(isset($clubdj_friday))) {
          $clubdj_friday = 0;
      }

      if (!(isset($clubdj_saturday))) {
          $clubdj_saturday = 0;
      }

      if (!(isset($clubdj_sunday))) {
          $clubdj_sunday = 0;
      }

      if (!(isset($clubdj_varies))) {
          $clubdj_varies = 0;
      }





      if (!(isset($commercialdj_monday))) {
          $commercialdj_monday = 0;
      }

      if (!(isset($commercialdj_tuesday))) {
          $commercialdj_tuesday = 0;
      }

      if (!(isset($commercialdj_wednesday))) {
          $commercialdj_wednesday = 0;
      }

      if (!(isset($commercialdj_thursday))) {
          $commercialdj_thursday = 0;
      }

      if (!(isset($commercialdj_friday))) {
          $commercialdj_friday = 0;
      }

      if (!(isset($commercialdj_saturday))) {
          $commercialdj_saturday = 0;
      }

      if (!(isset($commercialdj_sunday))) {
          $commercialdj_sunday = 0;
      }

      if (!(isset($commercialdj_varies))) {
          $commercialdj_varies = 0;
      }





      if (!(isset($noncommercialdj_monday))) {
          $noncommercialdj_monday = 0;
      }

      if (!(isset($noncommercialdj_tuesday))) {
          $noncommercialdj_tuesday = 0;
      }

      if (!(isset($noncommercialdj_wednesday))) {
          $noncommercialdj_wednesday = 0;
      }

      if (!(isset($noncommercialdj_thursday))) {
          $noncommercialdj_thursday = 0;
      }

      if (!(isset($noncommercialdj_friday))) {
          $noncommercialdj_friday = 0;
      }

      if (!(isset($noncommercialdj_saturday))) {
          $noncommercialdj_saturday = 0;
      }

      if (!(isset($noncommercialdj_sunday))) {
          $noncommercialdj_sunday = 0;
      }

      if (!(isset($noncommercialdj_varies))) {
          $noncommercialdj_varies = 0;
      }



      if (!(isset($satellitedj_monday))) {
          $satellitedj_monday = 0;
      }

      if (!(isset($satellitedj_tuesday))) {
          $satellitedj_tuesday = 0;
      }

      if (!(isset($satellitedj_wednesday))) {
          $satellitedj_wednesday = 0;
      }

      if (!(isset($satellitedj_thursday))) {
          $satellitedj_thursday = 0;
      }

      if (!(isset($satellitedj_friday))) {
          $satellitedj_friday = 0;
      }

      if (!(isset($satellitedj_saturday))) {
          $satellitedj_saturday = 0;
      }

      if (!(isset($satellitedj_sunday))) {
          $satellitedj_sunday = 0;
      }

      if (!(isset($satellitedj_varies))) {
          $satellitedj_varies = 0;
      }



      if (!(isset($internetdj_monday))) {
          $internetdj_monday = 0;
      }

      if (!(isset($internetdj_tuesday))) {
          $internetdj_tuesday = 0;
      }

      if (!(isset($internetdj_wednesday))) {
          $internetdj_wednesday = 0;
      }

      if (!(isset($internetdj_thursday))) {
          $internetdj_thursday = 0;
      }

      if (!(isset($internetdj_friday))) {
          $internetdj_friday = 0;
      }

      if (!(isset($internetdj_saturday))) {
          $internetdj_saturday = 0;
      }

      if (!(isset($internetdj_sunday))) {
          $internetdj_sunday = 0;
      }

      if (!(isset($internetdj_varies))) {
          $internetdj_varies = 0;
      }





      if (!(isset($collegedj_monday))) {
          $collegedj_monday = 0;
      }

      if (!(isset($collegedj_tuesday))) {
          $collegedj_tuesday = 0;
      }

      if (!(isset($collegedj_wednesday))) {
          $collegedj_wednesday = 0;
      }

      if (!(isset($collegedj_thursday))) {
          $collegedj_thursday = 0;
      }

      if (!(isset($collegedj_friday))) {
          $collegedj_friday = 0;
      }

      if (!(isset($collegedj_saturday))) {
          $collegedj_saturday = 0;
      }

      if (!(isset($collegedj_sunday))) {
          $collegedj_sunday = 0;
      }

      if (!(isset($collegedj_varies))) {
          $collegedj_varies = 0;
      }



      if (!(isset($piratedj_monday))) {
          $piratedj_monday = 0;
      }

      if (!(isset($piratedj_tuesday))) {
          $piratedj_tuesday = 0;
      }

      if (!(isset($piratedj_wednesday))) {
          $piratedj_wednesday = 0;
      }

      if (!(isset($piratedj_thursday))) {
          $piratedj_thursday = 0;
      }

      if (!(isset($piratedj_friday))) {
          $piratedj_friday = 0;
      }

      if (!(isset($piratedj_saturday))) {
          $piratedj_saturday = 0;
      }

      if (!(isset($piratedj_sunday))) {
          $piratedj_sunday = 0;
      }

      if (!(isset($piratedj_varies))) {
          $piratedj_varies = 0;
      }











      $managementQuery = DB::select("select id from members_dj_mixer where member = '" . $memberId . "'");

      $managementRows = $managemecount(ntQuery);





      if ($managementRows > 0) {

          $result = DB::select("UPDATE members_dj_mixer 
          SET djtype_commercialreporting = '" . (int) $djtype_commercialreporting . "', 
              djtype_commercialnonreporting = '" . (int) $djtype_commercialnonreporting . "', 
              djtype_club = '" . (int) $djtype_club . "', 
              djtype_mixtape = '" . (int) $djtype_mixtape . "', 
              djtype_satellite = '" . (int) $djtype_satellite . "', 
              djtype_internet = '" . (int) $djtype_internet . "', 
              djtype_college = '" . (int) $djtype_college . "', 
              djtype_pirate = '" . (int) $djtype_pirate . "', 
              djwith_mp3 = '" . (int) $djwith_mp3 . "', 
              djwith_mp3_serato = '" . (int) $djwith_mp3_serato . "', 
              djwith_mp3_final = '" . (int) $djwith_mp3_final . "', 
              djwith_mp3_pcdj = '" . (int) $djwith_mp3_pcdj . "', 
              djwith_mp3_ipod = '" . (int) $djwith_mp3_ipod . "', 
              djwith_mp3_other = '" . (int) $djwith_mp3_other . "', 
              djwith_cd = '" . (int) $djwith_cd . "',
              djwith_cd_stanton = '" . (int) $djwith_cd_stanton . "', 
              djwith_cd_numark = '" . (int) $djwith_cd_numark . "', 
              djwith_cd_american = '" . (int) $djwith_cd_american . "', 
              djwith_cd_vestax = '" . (int) $djwith_cd_vestax . "', 
              djwith_cd_technics = '" . (int) $djwith_cd_technics . "', 
              djwith_cd_gemini = '" . (int) $djwith_cd_gemini . "', 
              djwith_cd_denon = '" . (int) $djwith_cd_denon . "', 
              djwith_cd_gemsound = '" . (int) $djwith_cd_gemsound . "', 
              djwith_cd_pioneer = '" . (int) $djwith_cd_pioneer . "', 
              djwith_cd_tascam = '" . (int) $djwith_cd_tascam . "', 
              djwith_cd_other = '" . (int) $djwith_cd_other . "',
              djwith_vinyl = '" . (int) $djwith_vinyl . "', 
              djwith_vinyl_12 = '" . (int) $djwith_vinyl_12 . "',  
              djwith_vinyl_45 = '" . (int) $djwith_vinyl_45 . "',  
              djwith_vinyl_78 = '" . (int) $djwith_vinyl_78 . "',
              commercialdj_showname = '" . addslashes($commercialdj_showname) . "', 
              commercialdj_call = '" . addslashes($commercialdj_call) . "', 
              commercialdj_name = '" . addslashes($commercialdj_name) . "', 
              commercialdj_frequency = '" . addslashes($commercialdj_frequency) . "', 
              commercialdj_monday = '" . (int) $commercialdj_monday . "', 
              commercialdj_tuesday = '" . (int) $commercialdj_tuesday . "', 
              commercialdj_wednesday = '" . (int) $commercialdj_wednesday . "', 
              commercialdj_thursday = '" . (int) $commercialdj_thursday . "',
              commercialdj_friday = '" . (int) $commercialdj_friday . "', 
              commercialdj_saturday = '" . (int) $commercialdj_saturday . "', 
              commercialdj_sunday = '" . (int) $commercialdj_sunday . "', 
              commercialdj_varies = '" . (int) $commercialdj_varies . "', 
              commercialdj_showtime = '" . addslashes($commercialdj_showtime) . "', 
              commercialdj_showtype = '" . addslashes($commercialdj_showtype) . "',
              noncommercialdj_showname = '" . addslashes($noncommercialdj_showname) . "', 
              noncommercialdj_call = '" . addslashes($noncommercialdj_call) . "', 
              noncommercialdj_name = '" . addslashes($noncommercialdj_name) . "', 
              noncommercialdj_frequency = '" . addslashes($noncommercialdj_frequency) . "', 
              noncommercialdj_monday = '" . (int) $noncommercialdj_monday . "', 
              noncommercialdj_tuesday = '" . (int) $noncommercialdj_tuesday . "', 
              noncommercialdj_wednesday = '" . (int) $noncommercialdj_wednesday . "', 
              noncommercialdj_thursday = '" . (int) $noncommercialdj_thursday . "', 
              noncommercialdj_friday = '" . (int) $noncommercialdj_friday . "', 
              noncommercialdj_saturday = '" . (int) $noncommercialdj_saturday . "', 
              noncommercialdj_sunday = '" . (int) $noncommercialdj_sunday . "', 
              noncommercialdj_varies = '" . (int) $noncommercialdj_varies . "', 
              noncommercialdj_showtime = '" . addslashes($noncommercialdj_showtime) . "', 
              noncommercialdj_showtype = '" . addslashes($noncommercialdj_showtype) . "',
              clubdj_clubname = '" . addslashes($clubdj_clubname) . "', 
              clubdj_capacity = '" . addslashes($clubdj_capacity) . "', 
              clubdj_hiphop = '" . (int) $clubdj_hiphop . "', 
              clubdj_rb = '" . (int) $clubdj_rb . "', 
              clubdj_pop = '" . (int) $clubdj_pop . "', 
              clubdj_reggae = '" . (int) $clubdj_reggae . "', 
              clubdj_house = '" . (int) $clubdj_house . "', 
              clubdj_calypso = '" . (int) $clubdj_calypso . "', 
              clubdj_rock = '" . (int) $clubdj_rock . "', 
              clubdj_techno = '" . (int) $clubdj_techno . "', 
              clubdj_trance = '" . (int) $clubdj_trance . "', 
              clubdj_afro = '" . (int) $clubdj_afro . "', 
              clubdj_reggaeton = '" . (int) $clubdj_reggaeton . "', 
              clubdj_gogo = '" . (int) $clubdj_gogo . "', 
              clubdj_neosoul = '" . (int) $clubdj_neosoul . "', 
              clubdj_oldschool = '" . (int) $clubdj_oldschool . "', 
              clubdj_electronic = '" . (int) $clubdj_electronic . "', 
              clubdj_latin = '" . (int) $clubdj_latin . "', 
              clubdj_dance = '" . (int) $clubdj_dance . "', 
              clubdj_jazz = '" . (int) $clubdj_jazz . "', 
              clubdj_country = '" . (int) $clubdj_country . "', 
              clubdj_world = '" . (int) $clubdj_world . "', 
              clubdj_monday = '" . (int) $clubdj_monday . "', 
              clubdj_tuesday = '" . (int) $clubdj_tuesday . "', 
              clubdj_wednesday = '" . (int) $clubdj_wednesday . "', 
              clubdj_thursday = '" . (int) $clubdj_thursday . "', 
              clubdj_friday = '" . (int) $clubdj_friday . "', 
              clubdj_saturday = '" . (int) $clubdj_saturday . "', 
              clubdj_sunday = '" . (int) $clubdj_sunday . "', 
              clubdj_varies = '" . (int) $clubdj_varies . "', 
              clubdj_city = '" . addslashes($clubdj_city) . "', 
              clubdj_state = '" . addslashes($clubdj_state) . "', 
              clubdj_intcountry = '" . addslashes($clubdj_intcountry) . "',
              mixtapedj_name = '" . addslashes($mixtapedj_name) . "', 
              mixtapedj_type = '" . addslashes($mixtapedj_type) . "', 
              mixtapedj_schedule = '" . addslashes($mixtapedj_schedule) . "', 
              mixtapedj_distribution = '" . addslashes($mixtapedj_distribution) . "',
              satellitedj_stationname = '" . addslashes($satellitedj_stationname) . "', 
              satellitedj_showname = '" . addslashes($satellitedj_showname) . "', 
              satellitedj_channelname = '" . addslashes($satellitedj_channelname) . "', 
              satellitedj_channelnumber = '" . addslashes($satellitedj_channelnumber) . "', 
              satellitedj_monday = '" . (int) $satellitedj_monday . "', 
              satellitedj_tuesday = '" . (int) $satellitedj_tuesday . "', 
              satellitedj_wednesday = '" . (int) $satellitedj_wednesday . "', 
              satellitedj_thursday = '" . (int) $satellitedj_thursday . "', 
              satellitedj_friday = '" . (int) $satellitedj_friday . "', 
              satellitedj_saturday = '" . (int) $satellitedj_saturday . "', 
              satellitedj_sunday = '" . (int) $satellitedj_sunday . "', 
              satellitedj_showtime = '" . addslashes($satellitedj_showtime) . "',
              internetdj_stationwebsite = '" . addslashes($internetdj_stationwebsite) . "', 
              internetdj_showtype = '" . addslashes($internetdj_showtype) . "', 
              internetdj_showname = '" . addslashes($internetdj_showname) . "', 
              internetdj_monday = '" . (int) $internetdj_monday . "', 
              internetdj_tuesday = '" . (int) $internetdj_tuesday . "', 
              internetdj_wednesday = '" . (int) $internetdj_wednesday . "', 
              internetdj_thursday = '" . (int) $internetdj_thursday . "', 
              internetdj_friday = '" . (int) $internetdj_friday . "', 
              internetdj_saturday = '" . (int) $internetdj_saturday . "', 
              internetdj_sunday = '" . (int) $internetdj_sunday . "', 
              internetdj_varies = '" . (int) $internetdj_varies . "', 
              internetdj_showtime = '" . addslashes($internetdj_showtime) . "',
              collegedj_callletters = '" . addslashes($collegedj_callletters) . "', 
              collegedj_collegename = '" . addslashes($collegedj_collegename) . "', 
              collegedj_stationfrequency = '" . addslashes($collegedj_stationfrequency) . "', 
              collegedj_showtype = '" . addslashes($collegedj_showtype) . "', 
              collegedj_showname = '" . addslashes($collegedj_showname) . "', 
              collegedj_monday = '" . (int) $collegedj_monday . "', 
              collegedj_tuesday = '" . (int) $collegedj_tuesday . "', 
              collegedj_wednesday = '" . (int) $collegedj_wednesday . "', 
              collegedj_thursday = '" . (int) $collegedj_thursday . "', 
              collegedj_friday = '" . (int) $collegedj_friday . "', 
              collegedj_saturday = '" . (int) $collegedj_saturday . "', 
              collegedj_sunday = '" . (int) $collegedj_sunday . "', 
              collegedj_varies = '" . (int) $collegedj_varies . "', 
              collegedj_showtime = '" . addslashes($collegedj_showtime) . "', 
              collegedj_city = '" . addslashes($collegedj_city) . "', 
              collegedj_state = '" . addslashes($collegedj_state) . "', 
              collegedj_intcountry = '" . addslashes($collegedj_intcountry) . "',
              piratedj_stationfrequency = '" . addslashes($piratedj_stationfrequency) . "', 
              piratedj_showname = '" . addslashes($piratedj_showname) . "', 
              piratedj_monday = '" . (int) $piratedj_monday . "', 
              piratedj_tuesday = '" . (int) $piratedj_tuesday . "', 
              piratedj_wednesday = '" . (int) $piratedj_wednesday . "', 
              piratedj_thursday = '" . (int) $piratedj_thursday . "', 
              piratedj_friday = '" . (int) $piratedj_friday . "', 
              piratedj_saturday = '" . (int) $piratedj_saturday . "', 
              piratedj_sunday = '" . (int) $piratedj_sunday . "', 
              piratedj_varies = '" . (int) $piratedj_varies . "', 
              piratedj_showtime = '" . addslashes($piratedj_showtime) . "'
          WHERE member = '" . $memberId . "'");
      } else {

          $result = DB::select("INSERT INTO members_dj_mixer (`member`, `djtype_commercialreporting`, `djtype_commercialnonreporting`, `djtype_club`, `djtype_mixtape`, `djtype_satellite`, `djtype_internet`, `djtype_college`, `djtype_pirate`, `djwith_mp3`, `djwith_mp3_serato`, `djwith_mp3_final`, `djwith_mp3_pcdj`, `djwith_mp3_ipod`, `djwith_mp3_other`, `djwith_cd`, `djwith_cd_stanton`, `djwith_cd_numark`, `djwith_cd_american`, `djwith_cd_vestax`, `djwith_cd_technics`, `djwith_cd_gemini`, `djwith_cd_denon`, `djwith_cd_gemsound`, `djwith_cd_pioneer`, `djwith_cd_tascam`, `djwith_cd_other`, `djwith_vinyl`, `djwith_vinyl_12`, `djwith_vinyl_45`, `djwith_vinyl_78`, `commercialdj_showname`, `commercialdj_call`, `commercialdj_name`, `commercialdj_frequency`, `commercialdj_monday`, `commercialdj_tuesday`, `commercialdj_wednesday`, `commercialdj_thursday`, `commercialdj_friday`, `commercialdj_saturday`, `commercialdj_sunday`, `commercialdj_varies`, `commercialdj_showtime`, `commercialdj_showtype`, `noncommercialdj_showname`, `noncommercialdj_call`, `noncommercialdj_name`, `noncommercialdj_frequency`, `noncommercialdj_monday`, `noncommercialdj_tuesday`, `noncommercialdj_wednesday`, `noncommercialdj_thursday`, `noncommercialdj_friday`, `noncommercialdj_saturday`, `noncommercialdj_sunday`, `noncommercialdj_varies`, `noncommercialdj_showtime`, `noncommercialdj_showtype`, `clubdj_clubname`, `clubdj_capacity`, `clubdj_hiphop`, `clubdj_rb`, `clubdj_pop`, `clubdj_reggae`, `clubdj_house`, `clubdj_calypso`, `clubdj_rock`, `clubdj_techno`, `clubdj_trance`, `clubdj_afro`, `clubdj_reggaeton`, `clubdj_gogo`, `clubdj_neosoul`, `clubdj_oldschool`, `clubdj_electronic`, `clubdj_latin`, `clubdj_dance`, `clubdj_jazz`, `clubdj_country`, `clubdj_world`, `clubdj_monday`, `clubdj_tuesday`, `clubdj_wednesday`, `clubdj_thursday`, `clubdj_friday`, `clubdj_saturday`, `clubdj_sunday`, `clubdj_varies`, `clubdj_city`, `clubdj_state`, `clubdj_intcountry`, `mixtapedj_name`, `mixtapedj_type`, `mixtapedj_schedule`, `mixtapedj_distribution`, `satellitedj_stationname`, `satellitedj_showname`, `satellitedj_channelname`, `satellitedj_channelnumber`, `satellitedj_monday`, `satellitedj_tuesday`, `satellitedj_wednesday`, `satellitedj_thursday`, `satellitedj_friday`, `satellitedj_saturday`, `satellitedj_sunday`, `satellitedj_showtime`, `internetdj_stationwebsite`, `internetdj_showtype`, `internetdj_showname`, `internetdj_monday`, `internetdj_tuesday`, `internetdj_wednesday`, `internetdj_thursday`, `internetdj_friday`, `internetdj_saturday`, `internetdj_sunday`, `internetdj_varies`, `internetdj_showtime`, `collegedj_callletters`, `collegedj_collegename`, `collegedj_stationfrequency`, `collegedj_showtype`, `collegedj_showname`, `collegedj_monday`, `collegedj_tuesday`, `collegedj_wednesday`, `collegedj_thursday`, `collegedj_friday`, `collegedj_saturday`, `collegedj_sunday`, `collegedj_varies`, `collegedj_showtime`, `collegedj_city`, `collegedj_state`, `collegedj_intcountry`, `piratedj_stationfrequency`, `piratedj_showname`, `piratedj_monday`, `piratedj_tuesday`, `piratedj_wednesday`, `piratedj_thursday`, `piratedj_friday`, `piratedj_saturday`, `piratedj_sunday`, `piratedj_varies`, `piratedj_showtime`) 
          VALUES (
          '" . (int) $memberId . "', 
          '" . (int) $djtype_commercialreporting . "', 
          '" . (int) $djtype_commercialnonreporting . "', 
          '" . (int) $djtype_club . "', 
          '" . (int) $djtype_mixtape . "', 
          '" . (int) $djtype_satellite . "', 
          '" . (int) $djtype_internet . "', 
          '" . (int) $djtype_college . "', 
          '" . (int) $djtype_pirate . "', 
          '" . (int) $djwith_mp3 . "', 
          '" . (int) $djwith_mp3_serato . "', 
          '" . (int) $djwith_mp3_final . "', 
          '" . (int) $djwith_mp3_pcdj . "', 
          '" . (int) $djwith_mp3_ipod . "', 
          '" . (int) $djwith_mp3_other . "', 
          '" . (int) $djwith_cd . "', 
          '" . (int) $djwith_cd_stanton . "', 
          '" . (int) $djwith_cd_numark . "', 
          '" . (int) $djwith_cd_american . "', 
          '" . (int) $djwith_cd_vestax . "', 
          '" . (int) $djwith_cd_technics . "', 
          '" . (int) $djwith_cd_gemini . "', 
          '" . (int) $djwith_cd_denon . "', 
          '" . (int) $djwith_cd_gemsound . "', 
          '" . (int) $djwith_cd_pioneer . "', 
          '" . (int) $djwith_cd_tascam . "', 
          '" . (int) $djwith_cd_other . "', 
          '" . (int) $djwith_vinyl . "', 
          '" . (int) $djwith_vinyl_12 . "', 
          '" . (int) $djwith_vinyl_45 . "', 
          '" . (int) $djwith_vinyl_78 . "', 
          '" . addslashes($commercialdj_showname)."',
          '" . addslashes($commercialdj_call)."',
          '" . addslashes($commercialdj_name)."',
          '" . addslashes($commercialdj_frequency)."',
          '" . (int) $commercialdj_monday."', 
          '" . (int) $commercialdj_tuesday."', 
          '" . (int) $commercialdj_wednesday."', 
          '" . (int) $commercialdj_thursday."', 
          '" . (int) $commercialdj_friday."', 
          '" . (int) $commercialdj_saturday."', 
          '" . (int) $commercialdj_sunday."', 
          '" . (int) $commercialdj_varies."', 
          '" . addslashes($commercialdj_showtime)."', 
          '" . addslashes($commercialdj_showtype)."', 
          '" . addslashes($noncommercialdj_showname)."', 
          '" . addslashes($noncommercialdj_call)."', 
          '" . addslashes($noncommercialdj_name)."', 
          '" . addslashes($noncommercialdj_frequency)."', 
          '" . (int) $noncommercialdj_monday."', 
          '" . (int) $noncommercialdj_tuesday."', 
          '" . (int) $noncommercialdj_wednesday."', 
          '" . (int) $noncommercialdj_thursday."', 
          '" . (int) $noncommercialdj_friday."', 
          '" . (int) $noncommercialdj_saturday."', 
          '" . (int) $noncommercialdj_sunday."', 
          '" . (int) $noncommercialdj_varies."', 
          '" . addslashes($noncommercialdj_showtime)."', 
          '" . addslashes($noncommercialdj_showtype)."', 
          '" . addslashes($clubdj_clubname)."', 
          '" . addslashes($clubdj_capacity)."', 
          '" . (int) $clubdj_hiphop."', 
          '" . (int) $clubdj_rb."', 
          '" . (int) $clubdj_pop."', 
          '" . (int) $clubdj_reggae."', 
          '" . (int) $clubdj_house."', 
          '" . (int) $clubdj_calypso."', 
          '" . (int) $clubdj_rock."', 
          '" . (int) $clubdj_techno."', 
          '" . (int) $clubdj_trance."', 
          '" . (int) $clubdj_afro."', 
          '" . (int) $clubdj_reggaeton."', 
          '" . (int) $clubdj_gogo."', 
          '" . (int) $clubdj_neosoul."', 
          '" . (int) $clubdj_oldschool."', 
          '" . (int) $clubdj_electronic."', 
          '" . (int) $clubdj_latin."', 
          '" . (int) $clubdj_dance."', 
          '" . (int) $clubdj_jazz."', 
          '" . (int) $clubdj_country."', 
          '" . (int) $clubdj_world."', 
          '" . (int) $clubdj_monday."', 
          '" . (int) $clubdj_tuesday."', 
          '" . (int) $clubdj_wednesday."', 
          '" . (int) $clubdj_thursday."', 
          '" . (int) $clubdj_friday."', 
          '" . (int) $clubdj_saturday."', 
          '" . (int) $clubdj_sunday."', 
          '" . (int) $clubdj_varies."', 
          '" . addslashes($clubdj_city)."', 
          '" . addslashes($clubdj_state)."', 
          '" . addslashes($clubdj_intcountry)."', 
          '" . addslashes($mixtapedj_name)."', 
          '" . addslashes($mixtapedj_type)."', 
          '" . addslashes($mixtapedj_schedule)."', 
          '" . addslashes($mixtapedj_distribution)."', 
          '" . addslashes($satellitedj_stationname)."', 
          '" . addslashes($satellitedj_showname)."', 
          '" . addslashes($satellitedj_channelname)."', 
          '" . addslashes($satellitedj_channelnumber)."', 
          '" . (int) $satellitedj_monday."', 
          '" . (int) $satellitedj_tuesday."', 
          '" . (int) $satellitedj_wednesday."', 
          '" . (int) $satellitedj_thursday."', 
          '" . (int) $satellitedj_friday."', 
          '" . (int) $satellitedj_saturday."', 
          '" . (int) $satellitedj_sunday."', 
          '" . addslashes($satellitedj_showtime)."', 
          '" . addslashes($internetdj_stationwebsite)."', 
          '" . addslashes($internetdj_showtype)."', 
          '" . addslashes($internetdj_showname)."', 
          '" . (int) $internetdj_monday."', 
          '" . (int) $internetdj_tuesday."', 
          '" . (int) $internetdj_wednesday."', 
          '" . (int) $internetdj_thursday."', 
          '" . (int) $internetdj_friday."', 
          '" . (int) $internetdj_saturday."', 
          '" . (int) $internetdj_sunday."', 
          '" . (int) $internetdj_varies."', 
          '" . addslashes($internetdj_showtime)."', 
          '" . addslashes($collegedj_callletters)."', 
          '" . addslashes($collegedj_collegename)."', 
          '" . addslashes($collegedj_stationfrequency)."', 
          '" . addslashes($collegedj_showtype)."', 
          '" . addslashes($collegedj_showname)."', 
          '" . (int) $collegedj_monday."', 
          '" . (int) $collegedj_tuesday."', 
          '" . (int) $collegedj_wednesday."', 
          '" . (int) $collegedj_thursday."', 
          '" . (int) $collegedj_friday."', 
          '" . (int) $collegedj_saturday."', 
          '" . (int) $collegedj_sunday."', 
          '" . (int) $collegedj_varies."', 
          '" . addslashes($collegedj_showtime)."', 
          '" . addslashes($collegedj_city)."', 
          '" . addslashes($collegedj_state)."', 
          '" . addslashes($collegedj_intcountry)."', 
          '" . addslashes($piratedj_stationfrequency)."', 
          '" . addslashes($piratedj_showname)."', 
          '" . (int) $piratedj_monday."', 
          '" . (int) $piratedj_tuesday."', 
          '" . (int) $piratedj_wednesday."', 
          '" . (int) $piratedj_thursday."', 
          '" . (int) $piratedj_friday."', 
          '" . (int) $piratedj_saturday."', 
          '" . (int) $piratedj_sunday."', 
          '" . (int) $piratedj_varies."', 
          '" . addslashes($piratedj_showtime)."')");
      }

      // radio station

      if (!(isset($airMonday))) {
          $airMonday = 0;
      }

      if (!(isset($airTuesday))) {
          $airTuesday = 0;
      }

      if (!(isset($airWednesday))) {
          $airWednesday = 0;
      }

      if (!(isset($airThursday))) {
          $airThursday = 0;
      }

      if (!(isset($airFriday))) {
          $airFriday = 0;
      }

      if (!(isset($airSaturday))) {
          $airSaturday = 0;
      }

      if (!(isset($airSunday))) {
          $airSunday = 0;
      }

      if (!(isset($airVaries))) {
          $airVaries = 0;
      }





      if (!(isset($musicMonday))) {
          $musicMonday = 0;
      }

      if (!(isset($musicTuesday))) {
          $musicTuesday = 0;
      }

      if (!(isset($musicWednesday))) {
          $musicWednesday = 0;
      }

      if (!(isset($musicThursday))) {
          $musicThursday = 0;
      }

      if (!(isset($musicFriday))) {
          $musicFriday = 0;
      }

      if (!(isset($musicSaturday))) {
          $musicSaturday = 0;
      }

      if (!(isset($musicSunday))) {
          $musicSunday = 0;
      }

      if (!(isset($musicVaries))) {
          $musicVaries = 0;
      }





      if (!(isset($programMonday))) {
          $programMonday = 0;
      }

      if (!(isset($programTuesday))) {
          $programTuesday = 0;
      }

      if (!(isset($programWednesday))) {
          $programWednesday = 0;
      }

      if (!(isset($programThursday))) {
          $programThursday = 0;
      }

      if (!(isset($programFriday))) {
          $programFriday = 0;
      }

      if (!(isset($programSaturday))) {
          $programSaturday = 0;
      }

      if (!(isset($programSunday))) {
          $programSunday = 0;
      }

      if (!(isset($programVaries))) {
          $programVaries = 0;
      }





      if (!(isset($radioMusic))) {
          $radioMusic = 0;
      }

      if (!(isset($radioProgram))) {
          $radioProgram = 0;
      }

      if (!(isset($radioAir))) {
          $radioAir = 0;
      }

      if (!(isset($radioPromotion))) {
          $radioPromotion = 0;
      }

      if (!(isset($radioProduction))) {
          $radioProduction = 0;
      }

      if (!(isset($radioSales))) {
          $radioSales = 0;
      }

      if (!(isset($radioIt))) {
          $radioIt = 0;
      }









      $managementQuery = DB::select("select id from members_radio_station where member = '" . $memberId . "'");

      $managementRows = $managemecount(ntQuery);





      if ($managementRows > 0) {

          DB::select("UPDATE members_radio_station 
                          SET radiotype_musicdirector = ".(int) $radioMusic.", 
                              radiotype_programdirector = ".(int) $radioProgram.", 
                              radiotype_jock = ".(int) $radioAir.", 
                              radiotype_promotion = ".(int) $radioPromotion.", 
                              radiotype_production = ".(int) $radioProduction.", 
                              radiotype_sales = ".(int) $radioSales.", 
                              radiotype_tech = ".(int) $radioIt.",
                              stationcallletters = '".addslashes($stationCall)."',
                              stationfrequency = '".addslashes($stationFrequency)."', 
                              stationname = '".addslashes($stationName)."',
                              programdirector_stationcallletters = '".addslashes($programCall)."',
                              programdirector_host = '".addslashes($programHost)."', 
                              programdirector_showname = '".addslashes($programName)."', 
                              programdirector_showtime = '".addslashes($programTime)."',
                              programdirector_monday = ".(int) $programMonday.",
                              programdirector_tuesday = ".(int) $programTuesday.",
                              programdirector_wednesday = ".(int) $programWednesday.", 
                              programdirector_thursday = ".(int) $programThursday.", 
                              programdirector_friday = ".(int) $programFriday.", 
                              programdirector_saturday = ".(int) $programSaturday.",
                              programdirector_sunday = ".(int) $programSunday.", 
                              programdirector_varies = ".(int) $programVaries.",
                              musicdirector_stationcallletters = '".addslashes($musicCall)."',
                              musicdirector_host = '".addslashes($musicHost)."',
                              musicdirector_showname = '".addslashes($musicName)."',
                              musicdirector_showtime = '".addslashes($musicTime)."',
                              musicdirector_monday = ".(int) $musicMonday.",
                              musicdirector_tuesday = ".(int) $musicTuesday.", 
                              musicdirector_wednesday = ".(int) $musicWednesday.",
                              musicdirector_thursday = ".(int) $musicThursday.", 
                              musicdirector_friday = ".(int) $musicFriday.", 
                              musicdirector_saturday = ".(int) $musicSaturday.",
                              musicdirector_sunday = ".(int) $musicSunday.",
                              musicdirector_varies = ".(int) $musicVaries.",
                              onairpersonality_showname = '".addslashes($airName)."',
                              onairpersonality_showtime = '".addslashes($airTime)."', 
                              onairpersonality_monday = ".(int) $airMonday.",
                              onairpersonality_tuesday = ".(int) $airTuesday.", 
                              onairpersonality_wednesday = ".(int) $airWednesday.", 
                              onairpersonality_thursday = ".(int) $airThursday.",
                              onairpersonality_friday = ".(int) $airFriday.", 
                              onairpersonality_saturday = ".(int) $airSaturday.", 
                              onairpersonality_sunday = ".(int) $airSunday.", 
                              onairpersonality_varies = ".(int) $airVaries."
                              WHERE member = '" . (int) $memberId . "'");
      } else {

          DB::select("INSERT INTO `members_radio_station` (`member`, `radiotype_musicdirector`, `radiotype_programdirector`, `radiotype_jock`, `radiotype_promotion`, `radiotype_production`, `radiotype_sales`, `radiotype_tech`, `stationcallletters`, `stationfrequency`, `stationname`, `programdirector_stationcallletters`, `programdirector_host`, `programdirector_showname`, `programdirector_showtime`, `programdirector_monday`, `programdirector_tuesday`, `programdirector_wednesday`, `programdirector_thursday`, `programdirector_friday`, `programdirector_saturday`, `programdirector_sunday`, `programdirector_varies`, `musicdirector_stationcallletters`, `musicdirector_host`, `musicdirector_showname`, `musicdirector_showtime`, `musicdirector_monday`, `musicdirector_tuesday`, `musicdirector_wednesday`, `musicdirector_thursday`, `musicdirector_friday`, `musicdirector_saturday`, `musicdirector_sunday`, `musicdirector_varies`, `onairpersonality_showname`, `onairpersonality_showtime`, `onairpersonality_monday`, `onairpersonality_tuesday`, `onairpersonality_wednesday`, `onairpersonality_thursday`, `onairpersonality_friday`, `onairpersonality_saturday`, `onairpersonality_sunday`, `onairpersonality_varies`) 
              VALUES (
                  '".(int) $memberId."',
                  ".(int) $radioMusic.",
                  ".(int) $radioProgram.",
                  ".(int) $radioAir.",
                  ".(int) $radioPromotion.",
                  ".(int) $radioProduction.",
                  ".(int) $radioSales.",
                  ".(int) $radioIt.",
                  '".addslashes($stationCall)."',
                  '".addslashes($stationFrequency)."',
                  '".addslashes($stationName)."',
                  '".addslashes($programCall)."',
                  '".addslashes($programHost)."',
                  '".addslashes($programName)."',
                  '".addslashes($programTime)."',
                  ".(int) $programMonday.",
                  ".(int) $programTuesday.",
                  ".(int) $programWednesday.",
                  ".(int) $programThursday.",
                  ".(int) $programFriday.",
                  ".(int) $programSaturday.",
                  ".(int) $programSunday.",
                  ".(int) $programVaries.",
                  '".addslashes($musicCall)."',
                  '".addslashes($musicHost)."',
                  '".addslashes($musicName)."',
                  '".addslashes($musicTime)."',
                  ".(int) $musicMonday.",
                  ".(int) $musicTuesday.",
                  ".(int) $musicWednesday.",
                  ".(int) $musicThursday.",
                  ".(int) $musicFriday.",
                  ".(int) $musicSaturday.",
                  ".(int) $musicSunday.",
                  ".(int) $musicVaries.",
                  '".addslashes($airName)."',
                  '".addslashes($airTime)."',
                  ".(int) $airMonday.",
                  ".(int) $airTuesday.",
                  ".(int) $airWednesday.",
                  ".(int) $airThursday.",
                  ".(int) $airFriday.",
                  ".(int) $airSaturday.",
                  ".(int) $airSunday.",
                  ".(int) $airVaries.")");
      }


      // media

      if (!(isset($massTv))) {
          $massTv = 0;
      }

      if (!(isset($massPublication))) {
          $massPublication = 0;
      }

      if (!(isset($massDotcom))) {
          $massDotcom = 0;
      }

      if (!(isset($massNewsletter))) {
          $massNewsletter = 0;
      }


      $managementQuery = DB::select("select id from members_mass_media where member = '" . $memberId . "'");

      $managementRows = $managemecount(ntQuery);


      if ($managementRows > 0) {

          DB::select("update members_mass_media set mediatype_tvfilm = '$massTv', mediatype_publication = '$massPublication', mediatype_newmedia = '$massDotcom', mediatype_newsletter = '$massNewsletter', media_name = '$massName', media_website = '$massWebsite', media_department = '$massDepartment' where member = '" . $memberId . "'");
      } else {

          DB::select("insert into `members_mass_media` (`member`, `mediatype_tvfilm`, `mediatype_publication`, `mediatype_newmedia`, `mediatype_newsletter`, `media_name`, `media_website`, `media_department`) values ('" . $memberId . "', '" . $massTv . "', '" . $massPublication . "', '" . $massDotcom . "', '" . $massNewsletter . "', '" . $massName . "', '" . $massWebsite . "', '" . $massDepartment . "')");
      }



      // record label

      if (!(isset($recordMajor))) {
          $recordMajor = 0;
      }

      if (!(isset($recordIndy))) {
          $recordIndy = 0;
      }

      if (!(isset($recordDistribution))) {
          $recordDistribution = 0;
      }




      $managementQuery = DB::select("select id from members_record_label where member = '" . $memberId . "'");

      $managementRows = $managemecount(ntQuery);



      if ($managementRows > 0) {

          DB::select("update members_record_label set labeltype_major = '$recordMajor', labeltype_indy = '$recordIndy', labeltype_distribution = '$recordDistribution', label_name = '$recordName', label_department = '$recordDepartment' where member = '" . $memberId . "'");
      } else {

          DB::select("insert into `members_record_label` (`member`, `labeltype_major`, `labeltype_indy`, `labeltype_distribution`, `label_name`, `label_department`) values ('" . $memberId . "', '" . $recordMajor . "', '" . $recordIndy . "', '" . $recordDistribution . "', '" . $recordName . "', '" . $recordDepartment . "')");
      }


      // management

      if (!(isset($managementArtist))) {
          $managementArtist = 0;
      }

      if (!(isset($managementTour))) {
          $managementTour = 0;
      }

      if (!(isset($managementPersonal))) {
          $managementPersonal = 0;
      }

      if (!(isset($managementFinance))) {
          $managementFinance = 0;
      }



      $managementQuery = DB::select("select id from members_management where member = '" . $memberId . "'");

      $managementRows = $managemecount(ntQuery);


      if ($managementRows > 0) {

          DB::select("update members_management set managementtype_artist = '$managementArtist', managementtype_tour = '$managementTour', managementtype_personal = '$managementPersonal', managementtype_finance = '$managementFinance', management_name = '$managementName', management_who = '$managementWho', management_industry = '$managementIndustry' where member = '" . $memberId . "'");
      } else {

          DB::select("insert into `members_management` (`member`, `managementtype_artist`, `managementtype_tour`, `managementtype_personal`, `managementtype_finance`, `management_name`, `management_who`, `management_industry`) values ('" . $memberId . "', '" . $managementArtist . "', '" . $managementTour . "', '" . $managementPersonal . "', '" . $managementFinance . "', '" . $managementName . "', '" . $managementWho . "', '" . $managementIndustry . "')");
      }



      // clothing



      $clothingQuery = DB::select("select id from members_clothing_apparel where member = '" . $memberId . "'");

      $clothingRows = $clothicount(ngQuery);


      if ($clothingRows > 0) {

          DB::select("update members_clothing_apparel set clothing_name = '$clothingName', clothing_department = '$clothingDepartment' where member = '" . $memberId . "'");
      } else {

          DB::select("insert into `members_clothing_apparel` (`member`, `clothing_name`, `clothing_department`) values ('" . $memberId . "', '" . $clothingName . "', '" . $clothingDepartment . "')");
      }



      // promoter

      if (!(isset($promoterIndy))) {
          $promoterIndy = 0;
      }

      if (!(isset($promoterClub))) {
          $promoterClub = 0;
      }

      if (!(isset($promoterSpecial))) {
          $promoterSpecial = 0;
      }

      if (!(isset($promoterStreet))) {
          $promoterStreet = 0;
      }



      $promoterQuery = DB::select("select id from members_promoter where member = '" . $memberId . "'");

      $promoterRows = $promotcount(erQuery);



      if ($promoterRows > 0) {


          DB::select("update members_promoter set promotertype_indy = '$promoterIndy', promotertype_club = '$promoterClub', promotertype_event = '$promoterSpecial', promotertype_street = '$promoterStreet', promoter_name = '$promoterName', promoter_department = '$promoterDepartment', promoter_website = '$promoterWebsite' where member = '" . $memberId . "'");
      }
      
      else {

          DB::select("insert into `members_promoter` (`member`, `promotertype_indy`, `promotertype_club`, `promotertype_event`, `promotertype_street`, `promoter_name`, `promoter_department`, `promoter_website`) values ('" . $memberId . "', '" . $promoterIndy . "', '" . $promoterClub . "', '" . $promoterSpecial . "', '" . $promoterStreet . "', '" . $promoterName . "', '" . $promoterDepartment . "', '" . $promoterWebsite . "')");
      }



      // special

      if (!(isset($specialCorporate))) {
          $specialCorporate = 0;
      }

      if (!(isset($specialGraphic))) {
          $specialGraphic = 0;
      }

      if (!(isset($specialWeb))) {
          $specialWeb = 0;
      }

      if (!(isset($specialOther))) {
          $specialOther = 0;
      }



      $specialQuery = DB::select("select id from members_special_services where member = '" . $memberId . "'");

      $specialRows = $specicount(alQuery);



      if ($specialRows > 0) {

          DB::select("update members_special_services set servicestype_corporate = '$specialCorporate', servicestype_graphicdesign = '$specialGraphic', servicestype_webdesign = '$specialWeb', servicestype_other = '$specialOther', services_name = '$specialName', services_website = '$specialWebsite' where member = '" . $memberId . "'");
      } else {

          DB::select("insert into `members_special_services` (`member`, `servicestype_corporate`, `servicestype_graphicdesign`, `servicestype_webdesign`, `servicestype_other`, `services_name`, `services_website`) values ('" . $memberId . "', '" . $specialCorporate . "', '" . $specialGraphic . "', '" . $specialWeb . "', '" . $specialOther . "', '" . $specialName . "', '" . $specialWebsite . "')");
      }



      // production

      if (!(isset($productionArtist))) {
          $productionArtist = 0;
      }

      if (!(isset($productionProducer))) {
          $productionProducer = 0;
      }

      if (!(isset($productionChoregrapher))) {
          $productionChoregrapher = 0;
      }

      if (!(isset($productionSound))) {
          $productionSound = 0;
      }



      $productionQuery = DB::select("select id from members_production_talent where member = '" . $memberId . "'");

      $productionRows = $producticount(onQuery);



      if ($productionRows > 0) {

          DB::select("update members_production_talent set productiontype_artist = '$productionArtist', productiontype_producer = '$productionProducer', productiontype_choreographer = '$productionChoregrapher', productiontype_sound = '$productionSound', production_name = '$productionName' where member = '" . $memberId . "'");
      } else {

          DB::select("insert into `members_production_talent` (`member`, `productiontype_artist`, `productiontype_producer`, `productiontype_choreographer`, `productiontype_sound`, `production_name`) values ('" . $memberId . "', '" . $productionArtist . "', '" . $productionProducer . "', '" . $productionChoregrapher . "', '" . $productionSound . "', '" . $productionName . "')");
      }



      return $query;
  }



  function ad_mem_getMembership()

  {

      $queryRes = DB::select("select * from  membership");

     // $result = count($queryRes);

      return $queryRes;

      // $this->db->select("*");
      // $this->db->from("membership");
      // $result =   $this->db->get();

      // return  $result->result_array();
  }

     

      // Functions added by R-S ends here

      public function getNumTrackComments($trackId){
        $query = DB::select("SELECT tracks_reviews.id, tracks_reviews.whereheard, tracks_reviews.alreadyhave, tracks_reviews.willplay, tracks_reviews.whatrate, tracks_reviews.howsoon, tracks_reviews.howmanyplays, tracks_reviews.anotherformat, tracks_reviews.additionalcomments, tracks_reviews.formats_comradio, tracks_reviews.formats_satradio, tracks_reviews.formats_colradio, tracks_reviews.formats_internet, tracks_reviews.formats_clubs, tracks_reviews.formats_mixtapes, tracks_reviews.formats_musicvideo, tracks_reviews.godistance, tracks_reviews.godistanceyes, tracks_reviews.labelsupport, tracks_reviews.labelsupport_other, tracks_reviews.howsupport, tracks_reviews.howsupport_howsoon, tracks_reviews.likerecord, tracks_reviews.member,  members.stagename, members.city, members.state FROM tracks_reviews left join members on tracks_reviews.member = members.id where tracks_reviews.track = '" . $trackId . "' order by tracks_reviews.id desc");

        $resultCount = count($query);

        return  $resultCount;		
	}

    public function getTrackComments($trackId, $start, $limit){
        $query = DB::select("SELECT tracks_reviews.id, tracks_reviews.is_approved, tracks_reviews.whereheard, tracks_reviews.alreadyhave, tracks_reviews.willplay, tracks_reviews.whatrate, tracks_reviews.howsoon, tracks_reviews.howmanyplays, tracks_reviews.anotherformat, tracks_reviews.additionalcomments, tracks_reviews.formats_comradio, tracks_reviews.formats_satradio, tracks_reviews.formats_colradio, tracks_reviews.formats_internet, tracks_reviews.formats_clubs, tracks_reviews.formats_mixtapes, tracks_reviews.formats_musicvideo, tracks_reviews.godistance, tracks_reviews.godistanceyes, tracks_reviews.labelsupport, tracks_reviews.labelsupport_other, tracks_reviews.howsupport, tracks_reviews.howsupport_howsoon, tracks_reviews.likerecord, tracks_reviews.member,  members.stagename, members.city, members.state FROM tracks_reviews left join members on tracks_reviews.member = members.id where tracks_reviews.track = '" . $trackId . "' order by tracks_reviews.id desc limit $start, $limit");

        $result['numRows'] = count($query);

        $result['data']  = $query;

        return  $result;		
	}

    public function getTrackReviewsByReviewId($reviewId)

    {

        $query = DB::select("SELECT tracks_reviews.id, tracks_reviews.whereheard, tracks_reviews.alreadyhave, tracks_reviews.willplay, tracks_reviews.whatrate, tracks_reviews.howsoon, tracks_reviews.howmanyplays, tracks_reviews.anotherformat, tracks_reviews.additionalcomments, tracks_reviews.formats_comradio, tracks_reviews.formats_satradio, tracks_reviews.formats_colradio, tracks_reviews.formats_internet, tracks_reviews.formats_clubs, tracks_reviews.formats_mixtapes, tracks_reviews.formats_musicvideo, tracks_reviews.godistance, tracks_reviews.godistanceyes, tracks_reviews.labelsupport, tracks_reviews.labelsupport_other, tracks_reviews.howsupport, tracks_reviews.howsupport_howsoon, tracks_reviews.likerecord, tracks_reviews.member,  members.stagename, members.city, members.state FROM tracks_reviews left join members on tracks_reviews.member = members.id where tracks_reviews.id = '" . $reviewId . "' order by tracks_reviews.id desc");

        $result['numRows'] =  count($query);

        $result['data'] = $query;
       // echo '<pre>';print_r($result);die();
        return $result;
    }

    function removeTrackComment($commentId)

    {

        $result = 0;

        $query =  DB::select("SELECT tracks_reviews.id  FROM tracks_reviews

	  left join tracks on tracks_reviews.track = tracks.id

	  where tracks_reviews.id = '" . $commentId . "'");

        $numRows =  count($query);

        if ($numRows > 0) {

            $query =  DB::select("update tracks_reviews set additionalcomments = '' where id = '" . $commentId . "'");

            $result = 1;
        }

        return $result;
    }

    function approveTrackComment($commentId)

    {

        $result = 0;

        $query =  DB::select("SELECT tracks_reviews.id  FROM tracks_reviews

	  left join tracks on tracks_reviews.track = tracks.id

	  where tracks_reviews.id = '" . $commentId . "'");

        $numRows =  count($query);

        if ($numRows > 0) {

            $query =  DB::select("update tracks_reviews set is_approved = '1' where id = '" . $commentId . "'");

            $result = 1;
        }

        return $result;
    }
    function unapproveTrackComment($commentId)

    {

        $result = 0;

        $query =  DB::select("SELECT tracks_reviews.id  FROM tracks_reviews

	  left join tracks on tracks_reviews.track = tracks.id

	  where tracks_reviews.id = '" . $commentId . "'");

        $numRows =  count($query);

        if ($numRows > 0) {

            $query =  DB::select("update tracks_reviews set is_approved = '0' where id = '" . $commentId . "'");

            $result = 1;
        }

        return $result;
    }

    function getPeriodicDownloads($trackId) {
        $currentYear = date("Y");
        $firstDayOfMonth = date("Y-m-01");
        $lastDayOfMonth = date("Y-m-t");
        $firstDayOfWeek = date("Y-m-d", strtotime('last monday'));
        $lastDayOfWeek = date("Y-m-d", strtotime('next sunday'));
        $query = DB::select("
            SELECT
                `version`,
                SUM(CASE WHEN added >= CURDATE() - INTERVAL 7 DAY THEN downloads ELSE 0 END) AS weekly_downloads,
                SUM(CASE WHEN added >= DATE_FORMAT(NOW() - INTERVAL 1 MONTH, '%Y-%m-01') THEN downloads ELSE 0 END) AS monthly_downloads,
                SUM(CASE WHEN YEAR(added) = YEAR(CURDATE()) THEN downloads ELSE 0 END) AS yearly_downloads
            FROM
                `tracks_mp3s`
            WHERE
                `track` = ?
            GROUP BY
                `version`",
            [$trackId]);
            $result = $query[0];
        $downloadsByMp3Id = [];
        foreach ($query as $row) {

            $mp3Id = urldecode(trim($row->version));
            $downloadsByMp3Id[$mp3Id] = [
                'weekly_downloads' => $row->weekly_downloads,
                'monthly_downloads' => $row->monthly_downloads,
                'yearly_downloads' => $row->yearly_downloads
            ];
        }
        
        return $downloadsByMp3Id;
    }


	function getTrackReviews($trackId){
        $query = DB::select("SELECT tracks_reviews.id, tracks_reviews.whereheard, tracks_reviews.alreadyhave, tracks_reviews.willplay, tracks_reviews.whatrate, tracks_reviews.howsoon, tracks_reviews.howmanyplays, tracks_reviews.anotherformat, tracks_reviews.additionalcomments, tracks_reviews.formats_comradio, tracks_reviews.formats_satradio, tracks_reviews.formats_colradio, tracks_reviews.formats_internet, tracks_reviews.formats_clubs, tracks_reviews.formats_mixtapes, tracks_reviews.formats_musicvideo, tracks_reviews.godistance, tracks_reviews.godistanceyes, tracks_reviews.labelsupport, tracks_reviews.labelsupport_other, tracks_reviews.howsupport, tracks_reviews.howsupport_howsoon, tracks_reviews.likerecord, tracks_reviews.member,  members.stagename, members.city, members.state FROM tracks_reviews left join members on tracks_reviews.member = members.id where tracks_reviews.track = '" . $trackId . "' order by tracks_reviews.id desc");

        $result['numRows'] = count($query);

        $result['data']  = $query;

        return  $result;		
	}

    //new dashboard functions

    public function getLatestMembers() {
        $thisWeekStart = date('Y-m-d', strtotime('monday this week'));
        $thisWeekEnd = date('Y-m-d', strtotime('sunday this week'));

        $query = DB::select("SELECT members.id, members.fname, members.lname, members.added, members.active, member_images.pCloudFileID_mem_image
            FROM members
            LEFT JOIN (
                SELECT memberId, MAX(imageId) AS max_imageId
                FROM member_images
                GROUP BY memberId
            ) AS latest_images ON members.id = latest_images.memberId
            LEFT JOIN member_images ON latest_images.max_imageId = member_images.imageId
            WHERE members.deleted = 0 AND members.added BETWEEN '$thisWeekStart' AND '$thisWeekEnd'
            ORDER BY members.added DESC LIMIT 10");

        $result['numRows'] = count($query);
        $result['data'] = $query;

        return $result;
    }

    public function getLatestClients() {
        $thisWeekStart = date('Y-m-d', strtotime('monday this week'));
        $thisWeekEnd = date('Y-m-d', strtotime('sunday this week'));

        $query = DB::select("SELECT clients.id, clients.name, clients.added, clients.active, client_images.pCloudFileID_client_image
            FROM clients
            LEFT JOIN (
                SELECT clientId, MAX(imageId) AS max_imageId
                FROM client_images
                GROUP BY clientId
            ) AS latest_images ON clients.id = latest_images.clientId
            LEFT JOIN client_images ON latest_images.max_imageId = client_images.imageId
            WHERE clients.deleted = 0 AND clients.added BETWEEN '$thisWeekStart' AND '$thisWeekEnd'
            ORDER BY clients.added DESC LIMIT 10");

        $result['numRows'] = count($query);
        $result['data'] = $query;

        return $result;
    }

    public function getLatestSubmittedTracks() {
        $firstDayOfMonth = date('Y-m-01');
        $lastDayOfMonth = date('Y-m-t');
    
        $query = DB::select("SELECT ts.id, c.name AS client, c.id AS client_id, ts.artist, ts.title, ts.approved, ts.deleted, ts.added FROM tracks_submitted ts 
            JOIN clients c ON ts.client = c.id WHERE ts.deleted = 0 AND ts.approved = 0 AND ts.added BETWEEN ? AND ? ORDER BY 
            ts.added DESC limit 10", [$firstDayOfMonth, $lastDayOfMonth]);
        $result['numRows'] = count($query);
        $result['data'] = $query;
        return $result;
    }

    public function getTopPriorityTracks() {    
        $query = DB::select("SELECT clients.name, clients.id AS client_id, tracks.id AS track_id, tracks.artist, tracks.title, tracks.added FROM tracks 
        LEFT JOIN clients ON tracks.client = clients.id WHERE tracks.priority = 1 ORDER BY tracks.order_position DESC, tracks.added DESC LIMIT 10");
        $result['numRows'] = count($query);
        $result['data'] = $query;
        return $result;
    }

    public function getTopStreamingTracks() {
        $query = DB::select("SELECT clients.name, clients.id AS client_id, tracks.id AS track_id, tracks.artist, tracks.added, tracks.title FROM  tracks 
        LEFT JOIN clients ON tracks.client = clients.id JOIN top_streaming_tracks ON tracks.id = top_streaming_tracks.trackId
        ORDER BY top_streaming_tracks.position,top_streaming_tracks.created_at ASC LIMIT 10");
        $result['numRows'] = count($query);
        $result['data'] = $query;
        return $result;
    }

    public function getWeeklyTracksReviews() {
        $thisWeekStart = date('Y-m-d', strtotime('monday this week'));
        $thisWeekEnd = date('Y-m-d', strtotime('sunday this week'));
        $query = DB::select("SELECT tr.track, tr.member, tr.additionalcomments, tr.added, m.fname, m.lname, t.title FROM tracks_reviews tr JOIN members m ON tr.member = m.id JOIN tracks t ON tr.track = t.id WHERE tr.added BETWEEN '$thisWeekStart' AND '$thisWeekEnd' ORDER BY tr.added DESC;");
        $result['numRows'] = count($query);
        $result['data'] = $query;
        return $result;
    }

    public function getRecentActiveMembers() {
        $currentDate = date('Y-m-d');
        $query = DB::select("SELECT members.id, members.fname, members.lname, members.lastlogon, member_images.pCloudFileID_mem_image
        FROM members
        LEFT JOIN (
            SELECT memberId, MAX(imageId) AS max_imageId
            FROM member_images
            GROUP BY memberId
        ) AS latest_images ON members.id = latest_images.memberId
        LEFT JOIN member_images ON latest_images.max_imageId = member_images.imageId
        WHERE members.deleted = 0 AND DATE(members.lastlogon) = '$currentDate'
        ORDER BY members.lastlogon DESC LIMIT 9");
        $result['numRows'] = count($query);
        $result['data'] = $query;
        return $result;
    }

    public function getRecentActiveClients() {
        $currentDate = date('Y-m-d');
        $query = DB::select("SELECT clients.id, clients.name, clients.lastlogon, client_images.pCloudFileID_client_image
        FROM clients
        LEFT JOIN (
            SELECT clientId, MAX(imageId) AS max_imageId
            FROM client_images
            GROUP BY clientId
        ) AS latest_images ON clients.id = latest_images.clientId
        LEFT JOIN client_images ON latest_images.max_imageId = client_images.imageId
        WHERE clients.deleted = 0 AND DATE(clients.lastlogon) = '$currentDate'
        ORDER BY clients.lastlogon DESC LIMIT 9");
        $result['numRows'] = count($query);
        $result['data'] = $query;
        return $result;
    }

    public function getTracksAndUserStats() {
        $thisWeekStart = date('Y-m-d', strtotime('monday this week'));
        $thisWeekEnd = date('Y-m-d', strtotime('sunday this week'));

        $firstDayOfMonth = date('Y-m-01');
        $lastDayOfMonth = date('Y-m-t');

        $query = DB::select("SELECT 
                (SELECT COUNT(id) FROM tracks WHERE deleted = 0) AS total_tracks,
                (SELECT COUNT(id) FROM tracks WHERE priority = 1 AND deleted = 0) AS priority_tracks,
                (SELECT COUNT(id) FROM tracks WHERE approved = 1 AND deleted = 0) AS approved_tracks,
                (SELECT COUNT(id) FROM tracks_submitted WHERE deleted = 0) AS total_tracks_submitted,
                (SELECT COUNT(id) FROM top_streaming_tracks) AS total_top_streaming_tracks,
                (SELECT COUNT(id) FROM members WHERE deleted = 0 AND active = 1 AND uname != '' AND pword != '') AS approved_members,
                (SELECT COUNT(id) FROM members WHERE deleted = 0 AND active = 1 AND uname != '' AND pword != '' AND added BETWEEN '$thisWeekStart' AND '$thisWeekEnd') AS week_approved_members,
                (SELECT COUNT(id) FROM members WHERE deleted = 0 AND uname != '' AND pword != '' AND added BETWEEN '$firstDayOfMonth' AND '$lastDayOfMonth') AS month_register_members,
                (SELECT COUNT(id) FROM members WHERE deleted = 0 AND active = 1 AND uname != '' AND pword != '' AND added BETWEEN '$firstDayOfMonth' AND '$lastDayOfMonth') AS month_approved_members,
                (SELECT COUNT(id) FROM members WHERE deleted = 0 AND active = 0 AND uname != '' AND pword != '') AS unapproved_members,
                (SELECT COUNT(id) FROM clients WHERE deleted = 0 AND active = 1 AND uname != '' AND pword != '') AS approved_clients,
                (SELECT COUNT(id) FROM clients WHERE deleted = 0 AND active = 1 AND uname != '' AND pword != '' AND added BETWEEN '$thisWeekStart' AND '$thisWeekEnd') AS week_approved_clients,
                (SELECT COUNT(id) FROM clients WHERE deleted = 0 AND uname != '' AND pword != '' AND added BETWEEN '$firstDayOfMonth' AND '$lastDayOfMonth') AS month_register_clients,
                (SELECT COUNT(id) FROM clients WHERE deleted = 0 AND active = 1 AND uname != '' AND pword != '' AND added BETWEEN '$firstDayOfMonth' AND '$lastDayOfMonth') AS month_approved_clients,
                (SELECT COUNT(id) FROM clients WHERE deleted = 0 AND active = 0 AND uname != '' AND pword != '') AS unapproved_clients
            ");
        $result['data'] = $query;
        return $result;
    }
    //new dashboard functions end

}   // end of class block

