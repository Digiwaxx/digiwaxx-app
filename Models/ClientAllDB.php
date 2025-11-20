<?php
namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use Session;

class ClientAllDB extends Model
{
    use HasFactory;

	public function getCountries($where){
		
        $query = DB::select("SELECT * FROM `country` $where ORDER BY TRIM(`country`)");

        $result['numRows'] = count($query);

        $result['data']  = $query;

        return  $result;
    }
	
	public function getDashboardDownloadData($table, $where){
		$query = DB::select("SELECT * FROM $table left join tracks on track_member_downloads.trackId = tracks.id $where limit 0, 1");
		return count($query);
	}
	
	public function getDashboardRatedData($table, $where){
		$query = DB::select("SELECT * FROM $table left join tracks on  tracks_reviews.track = tracks.id

	$where limit 0, 1");
		return count($query);
	}
	
	public function getDashboardPlayedData($table, $where){
		//echo "SELECT * FROM $table $where limit 0, 1"; echo '<br />';
		$query = DB::select("SELECT * FROM $table

	left join tracks on  track_member_play.trackId = tracks.id

	$where limit 0, 1");
		return count($query);
	}

	public function getRightTracks($cId){

		//echo "SELECT * FROM  tracks  where client = '" . $cId . "' order by id desc limit 0, 5";die();

        $query = DB::select("SELECT * FROM  tracks  where client = ? AND deleted = '0' order by id desc limit 0, 5", [$cId]);

        $result['numRows'] = count($query);

        $result['data']  = $query;

        return  $result;
    }
	
	public function getTrackPlays($trackId){

        // plays and downloads

        $query = DB::select("SELECT downloads, num_plays FROM tracks_mp3s where track = ? order by preview desc", [$trackId]);

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

        $query = DB::select("SELECT whatrate FROM tracks_reviews where track = ? order by id desc", [$trackId]);

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
	
	public function getClientFooterTracks($clientId){

        $query = DB::select("SELECT tracks_mp3s.id, tracks.client, tracks.artist, tracks.title, tracks.imgpage, tracks_mp3s.location,tracks.pCloudFileID, tracks.pCloudParentFolderID FROM

   tracks left join tracks_mp3s ON tracks.id = tracks_mp3s.track where tracks.client = ? order by tracks_mp3s.preview desc limit 0, 10", [$clientId]);


        $result['numRows'] = count($query);

        $result['data']  = $query;

        return  $result;
    }
	
	public function getClientUnreadInbox($clientId){

        $query = DB::select("SELECT * FROM chat_messages where (receiverType = '1' AND receiverId = ?) AND latest = '0' AND unread = '0' order by messageId desc", [$clientId]);

        $result['numRows'] = count($query);

        $result['data']  = $query;

        return  $result;
    }
	
	public function getTrackReviews($trackId){
        $query = DB::select("SELECT tracks_reviews.id, tracks_reviews.whereheard, tracks_reviews.alreadyhave, tracks_reviews.willplay, tracks_reviews.whatrate, tracks_reviews.howsoon, tracks_reviews.howmanyplays, tracks_reviews.anotherformat, tracks_reviews.additionalcomments, tracks_reviews.formats_comradio, tracks_reviews.formats_satradio, tracks_reviews.formats_colradio, tracks_reviews.formats_internet, tracks_reviews.formats_clubs, tracks_reviews.formats_mixtapes, tracks_reviews.formats_musicvideo, tracks_reviews.godistance, tracks_reviews.godistanceyes, tracks_reviews.labelsupport, tracks_reviews.labelsupport_other, tracks_reviews.howsupport, tracks_reviews.howsupport_howsoon, tracks_reviews.likerecord, tracks_reviews.member,  members.stagename, members.city, members.state FROM tracks_reviews left join members on tracks_reviews.member = members.id where tracks_reviews.track = ? order by tracks_reviews.id desc", [$trackId]);

        $result['numRows'] = count($query);

        $result['data']  = $query;

        return  $result;
	}
		
	public function getNumTrackComments($trackId){
        $query = DB::select("SELECT tracks_reviews.id, tracks_reviews.whereheard, tracks_reviews.alreadyhave, tracks_reviews.willplay, tracks_reviews.whatrate, tracks_reviews.howsoon, tracks_reviews.howmanyplays, tracks_reviews.anotherformat, tracks_reviews.additionalcomments, tracks_reviews.formats_comradio, tracks_reviews.formats_satradio, tracks_reviews.formats_colradio, tracks_reviews.formats_internet, tracks_reviews.formats_clubs, tracks_reviews.formats_mixtapes, tracks_reviews.formats_musicvideo, tracks_reviews.godistance, tracks_reviews.godistanceyes, tracks_reviews.labelsupport, tracks_reviews.labelsupport_other, tracks_reviews.howsupport, tracks_reviews.howsupport_howsoon, tracks_reviews.likerecord, tracks_reviews.member,  members.stagename, members.city, members.state FROM tracks_reviews left join members on tracks_reviews.member = members.id where tracks_reviews.track = ? order by tracks_reviews.id desc", [$trackId]);

        $resultCount = count($query);

        return  $resultCount;
	}
		
	public function getTrackComments($trackId, $start, $limit){
        $query = DB::select("SELECT tracks_reviews.id, tracks_reviews.whereheard, tracks_reviews.alreadyhave, tracks_reviews.willplay, tracks_reviews.whatrate, tracks_reviews.howsoon, tracks_reviews.howmanyplays, tracks_reviews.anotherformat, tracks_reviews.additionalcomments, tracks_reviews.formats_comradio, tracks_reviews.formats_satradio, tracks_reviews.formats_colradio, tracks_reviews.formats_internet, tracks_reviews.formats_clubs, tracks_reviews.formats_mixtapes, tracks_reviews.formats_musicvideo, tracks_reviews.godistance, tracks_reviews.godistanceyes, tracks_reviews.labelsupport, tracks_reviews.labelsupport_other, tracks_reviews.howsupport, tracks_reviews.howsupport_howsoon, tracks_reviews.likerecord, tracks_reviews.member,  members.stagename, members.city, members.state FROM tracks_reviews

	left join members on tracks_reviews.member = members.id

	where tracks_reviews.track = ? order by tracks_reviews.id desc limit $start, $limit", [$trackId]);

        $result['numRows'] = count($query);

        $result['data']  = $query;

        return  $result;
	}
	
    public function getClientsDetails($clientId)
    {
        $result['id'] = 0;
        $query = DB::select("SELECT id, uname,name,age,email FROM clients where id = ?", [$clientId]);
        $numRows = count($query);
        $data  = $query;
        if ($numRows > 0) {
            $result['id'] = $data[0]->id;
            $result['uname'] = $data[0]->uname;
            $result['fname'] = $data[0]->name;
            $result['age'] = $data[0]->age;
            $result['email'] = $data[0]->email;
        }

        $query1 = DB::select("SELECT image FROM client_images where clientId = ? order by imageId desc limit 1", [$clientId]);
        $numRows1 = count($query1);
        $data1  = $query1;
        if ($numRows1 > 0) {
            $result['image'] = $data1[0]->image;
        } else {
            $result['image'] = '';
        }
        return $result;
    }

	public function getClientInfo($clientId){

        $query = DB::select("SELECT * FROM  clients where id = ?", [$clientId]);

        $result['numRows'] = count($query);

        $result['data']  = $query;

        return  $result;
    }

	public function getClientImage($clientId){

        $query = DB::select("SELECT * FROM  client_images where clientId = ? order by imageId desc limit 1", [$clientId]);

        $result['numRows'] = count($query);

        $result['data']  = $query;

        return  $result;
    }

	public function getClientSocialInfo($clientId){

        $query = DB::select("SELECT * FROM  client_social_media where clientId = ?", [$clientId]);

        $result['numRows'] = count($query);

        $result['data']  = $query;

        return  $result;
    }
	
	public function addClientImage($image, $clientId,$pcloudFileId,$parentfolderid){
		
		$insertDta = array(
			'clientId'=>$clientId,
			'image'=>$image,
			'dateTime'=>date("Y-m-d h:i:s"),
			'pCloudParentFolderID_client_image'=>$parentfolderid,
			'pCloudFileID_client_image'=>$pcloudFileId
		);
		
		$insertId = DB::table('client_images')->insertGetId($insertDta);

        return  $insertId;
    }
	
    public function updateClientInfo($data, $clientId){

        extract($data);

        $trackReviewsActivate = 0;
        if(isset($activate_track_review_feedback) && $activate_track_review_feedback == 1){
            $trackReviewsActivate = 1;
        }

        $query = DB::select("update `clients` set name = ?, ccontact = ?, address1 = ?, address2 = ?, city = ?, state = ?, zip = ?, phone = ?, mobile = ?, email = ?, website = ?, trackReviewEmailsActivated = ? where id = ?", [urlencode($company), urlencode($name), urlencode($address1), urlencode($address2), urlencode($city), urlencode($state), $zip, $phone, $mobile, $email, $website, $trackReviewsActivate, $clientId]);

		$checkRecord = DB::select("SELECT * FROM client_social_media WHERE clientId = ?", [$clientId]);
		if(count($checkRecord) > 0){
			DB::select("update client_social_media set facebook = ?, twitter = ?, instagram = ?, linkedin = ? where clientId = ?", [$facebook, $twitter, $instagram, $linkedin, $clientId]);
		}else{
			$insertDta = array(
				'clientId'=>$clientId,
				'facebook'=>$facebook,
				'twitter'=>$twitter,
				'instagram'=>$instagram,
				'linkedin'=>$linkedin,
			);
			$insertId = DB::table('client_social_media')->insertGetId($insertDta);
		}
        return 1;
    }
	
	public function confirmClientCurrentPassword($password, $clientId){
        $rpass = $password;
        $password = md5($password);
        //
        $query = DB::select("SELECT * FROM clients where id = ? AND ( pword = ? OR pword = ?)", [$clientId, $rpass, $password]);

        return count($query);
    }

	public function updateClientPassword($password, $clientId){
        $password = md5($password);
        $query = DB::select("update clients set pword = ? where id = ?", [$password, $clientId]);
        return $clientId;
    }
	public function getSubscriptionStatus($clientId){

        $query = DB::select("SELECT status, packageId FROM client_subscriptions where clientId = ? and status = '1' order by subscriptionId desc limit 1", [$clientId]);

        $result['numRows'] = count($query);

        $result['data']  = $query;

        return $result;
    }
	public function getClientArchivedConversation($clientId, $memberId){

			$query = DB::select("SELECT * FROM chat_messages

	   left join chat_messages_archived on chat_messages.messageId = chat_messages_archived.messageId

	   where

	   ((chat_messages.senderType = '2' AND chat_messages.senderId = ? AND chat_messages.receiverType = '1' AND chat_messages.receiverId = ?)

	   OR

	   (chat_messages.senderType = '1' AND chat_messages.senderId = ? AND chat_messages.receiverType = '2' AND chat_messages.receiverId = ?))

	   AND chat_messages_archived.userType = '1' AND chat_messages_archived.userId = ?

	   order by chat_messages.messageId desc", [$memberId, $clientId, $clientId, $memberId, $clientId]);

			$result['numRows'] = count($query);

			$result['data']  = $query;

			return $result;
		}

		public function getClientStarredConversation($clientId, $memberId){

			$query = DB::select("SELECT * FROM chat_messages

   left join chat_messages_starred on chat_messages.messageId = chat_messages_starred.messageId

   where

   ((chat_messages.senderType = '2' AND chat_messages.senderId = ? AND chat_messages.receiverType = '1' AND chat_messages.receiverId = ?)

   OR

   (chat_messages.senderType = '1' AND chat_messages.senderId = ? AND chat_messages.receiverType = '2' AND chat_messages.receiverId = ?))

   AND chat_messages_starred.userType = '1' AND chat_messages_starred.userId = ?

   order by chat_messages.messageId desc", [$memberId, $clientId, $clientId, $memberId, $clientId]);

			$result['numRows'] = count($query);

			$result['data']  = $query;

			return $result;
		}
        // New client queries R-s starts
        function getLogo_cld()
        {
            $query = DB::select("SELECT logo  FROM   website_logo where logo_id = '1'");
            return $query;
        }
    
        function getSubscriptionStatus_cld($clientId)

        {

            $query = DB::select("SELECT status, packageId FROM client_subscriptions where clientId = ? and status = '1' order by subscriptionId desc limit 1", [$clientId]);

            $result['numRows'] = count($query);

            $result['data']  = $query;

            return $result;
        }
    
        function getNumClientTracks_cld($where, $sort)
    
        {
    
            $query = DB::select("SELECT * FROM  tracks $where order by $sort");
    
            return count($query);
        }
    
        function getClientTracks_cld($where, $start, $limit, $sort)
    
        {
    
            $query = DB::select("SELECT tracks.* , tracks_mp3s.location FROM  tracks left join tracks_mp3s ON tracks.id = tracks_mp3s.track $where GROUP BY tracks.id order by $sort limit $start, $limit");
    // pArr($query);
    // die();
            /*
    
       $query = DB::select("(SELECT id, client, artist, title, imgpage, approved, added FROM  tracks $where  )
    
       UNION
    
       (   SELECT id, client, artist, title, imgpage, approved, added FROM  tracks_submitted $where1  )
    
        order by $sort limit $start, $limit
    
       ");  */
    
            $result['numRows'] = count($query);
    
            $result['data']  = $query;
    
            return $result;
        }
    
        function getTrackPlays_cld($trackId)

        {

            // plays and downloads

            $query = DB::select("SELECT downloads, num_plays FROM tracks_mp3s where track = ? order by preview desc", [$trackId]);

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

            $query = DB::select("SELECT whatrate FROM tracks_reviews where track = ? order by id desc", [$trackId]);

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
    
        function getClientFooterTracks_cld($clientId)

        {

            $query = DB::select("SELECT tracks_mp3s.id, tracks.client, tracks.artist, tracks.title, tracks.imgpage, tracks_mp3s.location FROM

       tracks left join tracks_mp3s ON tracks.id = tracks_mp3s.track where tracks.client = ? order by tracks_mp3s.preview desc limit 0, 10", [$clientId]);

            $result['numRows'] = count($query);

            $result['data']  = $query;

            return $result;
        }

        function getClientUnreadInbox_cld($clientId)

        {

            $query = DB::select("SELECT * FROM chat_messages where (receiverType = '1' AND receiverId = ?) AND latest = '0' AND unread = '0' order by messageId desc", [$clientId]);

            $result['numRows'] = count($query);

            $result['data']  = $query;

            return $result;
        }

        function getClientUnreadInboxTotalCount($clientId)

        {

            $query = DB::select("SELECT * FROM chat_messages where (receiverType = '1' AND receiverId = ?) AND latest = '0' AND unread = '0' order by messageId desc", [$clientId]);

            $result = count($query);

            return $result;
        }

        function getClientUnreadInboxAll($clientId, $start, $limit){

            $query = DB::select("SELECT * FROM chat_messages where (receiverType = '1' AND receiverId = ?) AND latest = '0' AND unread = '0' order by messageId desc LIMIT $start, $limit", [$clientId]);

            $result['numRows'] = count($query);

            $result['data']  = $query;

            return $result;
        }
    
        function getBannerads_cld($user_type, $position)
        {
            $query = DB::select("SELECT banner_ad FROM  banner_ads where user_type = ? and ad_position = ?", [$user_type, $position]);
            $result['numRows'] = count($query);
            $result['data']  = $query;
            return $result;
        }

        function getRightTracks_cld()

        {

            $clientId = Session::get('clientId');

            // where client = '". $_SESSION['clientId'] ."'

            $query = DB::select("SELECT * FROM  tracks  where client = ? order by id desc limit 0, 5", [$clientId]);

            $result['numRows'] = count($query);

            $result['data']  = $query;

            return $result;
        }
    
        public function getClientsDetails_cld($clientId)
        {
            $result['id'] = 0;
            $query = DB::select("SELECT id, uname,name,age,email FROM clients where id = ?", [$clientId]);
            $numRows = count($query);
            $data  = $query;
            if ($numRows > 0) {
                $result['id'] = $data[0]->id;
                $result['uname'] = $data[0]->uname;
                $result['fname'] = $data[0]->name;
                $result['age'] = $data[0]->age;
                $result['email'] = $data[0]->email;
            }

            $query1 = DB::select("SELECT image FROM client_images where clientId = ? order by imageId desc limit 1", [$clientId]);
            $numRows1 = count($query1);
            $data1  = $query1;
            if ($numRows1 > 0) {
                $result['image'] = $data1[0]->image;
            } else {
                $result['image'] = '';
            }
            return $result;
        }
    
        function getSubGenres_cld($genreId)

        {

            $query = DB::select("SELECT subGenreId, subGenre FROM genres_sub where genreId = ? order by subGenre", [$genreId]);

            $result['numRows'] = count($query);

            $result['data']  = $query;

            return $result;
        }
    
    
        function addClientTrack_cld($data, $clientId)
        {
            extract($data);
            $release_date = $data['year'] . '-' . $data['month'] . '-' . $data['day'];

            if(!empty($feat_artist_1)){

                $feat_artist_1 = $feat_artist_1;
            }
            else{
                $feat_artist_1 = '';
            }
    
            if(!empty($feat_artist_2)){
    
                $feat_artist_2 = $feat_artist_2;
            }
            else{
                $feat_artist_2 = '';
            }
    
            if(!empty($producers)){
    
                $producers = $producers;
            }
            else{
                $producers = '';
            }    
    
    
            $insert_data = array(
                'client' => $clientId,
                'artist' => urlencode($artist),
                'title' => urlencode($title),
                'feat_artist_1' => urlencode($feat_artist_1),
                'feat_artist_2' => urlencode($feat_artist_2),
                'producers' => urlencode($producers),
                'writer' => urlencode($writer),
                'songkey' => urlencode($songkey),
                'time' => urlencode($trackTime),
                'bpm' => htmlspecialchars($bpm),
                'albumType' => urlencode($albumType),
                'album' => urlencode($album),
                'priorityType' => urlencode($priorityType),
                'releasedate' => urlencode($release_date),
                'link' => urlencode($website),
                'link1' => urlencode($website1),
                'link2' => urlencode($website2),
                'moreinfo' => urlencode($trackInfo),
                'approved' => 0,
                'added' => NOW(),
                'genreId' => urlencode($genre),
                'subGenreId' => urlencode($subGenre),
                'facebookLink' => urlencode($facebookLink),
                'twitterLink' => urlencode($twitterLink),
                'instagramLink' => urlencode($instagramLink),
                'snapchatLink' => urlencode($snapchatLink),
                'tiktokLink' => urlencode($tiktokLink),
                'otherLink' => urlencode($otherLink),
                'videoURL' => urlencode($videoURL),
                'applemusicLink' => urlencode($applemusicLink),
                'amazonLink' => urlencode($amazonLink),
                'spotifyLink' => urlencode($spotifyLink),
                'embedvideoURL' => urlencode($embedvideoURL),                
                'contact_name' => trim($contact_name),
                'contact_email' => trim($contact_email),
                'contact_email_2' => trim($contact_email_2),
                'contact_email_3' => trim($contact_email_3),
                'contact_email_4' => trim($contact_email_4),
                'contact_phone' => trim($contact_phone),
                'relationship_to_artist' => trim($relationship_to_artist),
            );
     
            $insert_id = DB::table('tracks_submitted')->insertGetId($insert_data);
    
            // $query = DB::select("INSERT INTO `tracks_submitted` (`client`, `artist`, `title`,`feat_artist_1`,`feat_artist_2`, `producers`, `time`, `bpm`, `albumType`, `album`,`priorityType`, `releasedate`, `link`, `link1`, `link2`, `moreinfo`, `approved`, `added`, `genreId`, `subGenreId`, `facebookLink`, `twitterLink`, `instagramLink`) VALUES ('" . $clientId . "', '" . urlencode($artist) . "', '" . urlencode($title) . "', '" . urlencode($feat_artist_1) . "', '" . urlencode($feat_artist_2) . "','" . urlencode($producer) . "', '" . urlencode($trackTime) . "', '" . urlencode($bpm) . "', '" . $albumType . "', '" . urlencode($album) . "','" . urlencode($priorityType) . "', '" . $release_date . "', '" . urlencode($website) . "', '" . urlencode($website1) . "', '" . urlencode($website2) . "', '" . urlencode($trackInfo) . "', '0', NOW(), '" . $genre . "', '" . $subGenre . "', '" . $facebookLink . "', '" . $twitterLink . "', '" . $instagramLink . "')");
            // $insertId = $this->db->insert_id();
    
    
            return  $insert_id;
        }
    
        function addClientTrackArtWork_cld($id, $image, $clientId,$pcloudFileId,$parentfolderid)

        {

            //echo "update `tracks_submitted` set imgpage = '". $image ."' where id = '". $id ."' and client = '". $clientId ."'";

            $query = DB::select("update `tracks_submitted` set imgpage = ? , pCloudFileID = ? , pCloudParentFolderID = ? where id = ? and client = ?", [$image, $pcloudFileId, $parentfolderid, $id, $clientId]);

            //exit;

            return 1;
        }

        function addClientTrackThumb_cld($id, $image, $clientId)

        {

            //echo "update `tracks_submitted` set imgpage = '". $image ."' where id = '". $id ."' and client = '". $clientId ."'";

            $query = DB::select("update `tracks_submitted` set thumb = ? where id = ? and client = ?", [$image, $id, $clientId]);

            //exit;

            return 1;
        }
    
        function addClientTrackAmr_cld1($id, $amrFile, $clientId, $version, $track_title)
        {
            //	echo "update `tracks_submitted` set amr1 = '". $amrFile ."' where id = '". $id ."' and client = '". $clientId ."'";
            //	echo '<br />';
             // $query = DB::select("update `tracks_submitted` set amr1 = '" . $amrFile . "', version1 = '" . $version . "', title1 = '" . mysqli_real_escape_string($track_title) . "' where id = '" . $id . "' and client = '" . $clientId . "'");

        // $query = DB::select("update `tracks_submitted` set amr1 = '" . $amrFile . "', version1 = '" . $version . "', title1 = '" .$track_title. "' where id = '" . $id . "' and client = '" . $clientId . "'");
        $query = DB::select("update `tracks_submitted` set amr1 = ?, version1 = ?, title1 = ? where id = ? and client = ?", [$amrFile, $version, $track_title, $id, $clientId]);

        return 1;
        }

        function addClientTrackAmr_cld2($id, $amrFile, $clientId, $version, $track_title)
        {
            $query = DB::select("update `tracks_submitted` set amr2 = ?, version2 = ?, title2 = ? where id = ? and client = ?", [$amrFile, $version, $track_title, $id, $clientId]);

            return 1;
        }

        function addClientTrackAmr_cld3($id, $amrFile, $clientId, $version, $track_title)

        {

            $query = DB::select("update `tracks_submitted` set amr3 = ?, version3 = ?, title3 = ? where id = ? and client = ?", [$amrFile, $version, $track_title, $id, $clientId]);

            return 1;
        }

        function addClientTrackAmr_cld4($id, $amrFile, $clientId, $version, $track_title)

        {

            $query = DB::select("update `tracks_submitted` set amr4 = ?, version4 = ?, title4 = ? where id = ? and client = ?", [$amrFile, $version, $track_title, $id, $clientId]);

            return 1;
        }

          function addClientTrackAmr_cld5($id, $amrFile, $clientId, $version, $track_title)

        {

            $query = DB::select("update `tracks_submitted` set amr5 = ?, version5 = ?, title5 = ? where id = ? and client = ?", [$amrFile, $version, $track_title, $id, $clientId]);

            return 1;
        }

          function addClientTrackAmr_cld6($id, $amrFile, $clientId, $version, $track_title)

        {

            $query = DB::select("update `tracks_submitted` set amr6 = ?, version6 = ?, title6 = ? where id = ? and client = ?", [$amrFile, $version, $track_title, $id, $clientId]);

            return 1;
        }
    
    
        function getGenres_cld()
    
        {
    
            $query = DB::select("SELECT * FROM genres order by genre");
    
            $result['numRows'] = count($query);
    
            $result['data']  = $query;
    
            return $result;
        }
    
        function saveTag_cld($tid, $tag)
        {
            $query = DB::select("update tracks set tagging = ? where id = ?", [$tag, $tid]);
            // return $query;
            return 1;
        }
    
        function getNumClientDigicoins_cld($where, $sort)
    
        {
    
            $query = DB::select("SELECT client_digicoin_id FROM  client_digicoins $where  order by $sort");
    
            $result = count($query);
    
            return  $result;
        }
    
        function getClientDigicoins_cld($where, $sort, $start, $limit)
    
        {
    
            $query = DB::select("SELECT client_digicoin_id, type_id, points, date_time, buy_id  FROM  client_digicoins
    
             $where  order by $sort limit $start, $limit");
    
            $result['numRows']  = count($query);
    
            $result['data']  = $query;
    
            return  $result;
        }
    
        function getNumMemberDigicoins_cld($where, $sort)
    
        {
    
            $query = DB::select("SELECT member_digicoin_id FROM  member_digicoins $where  order by $sort");
    
            $result = count($query);
    
            return  $result;
        }
    
        function getMemberDigicoins_cld($where, $sort, $start, $limit)
    
        {
    
            $query = DB::select("SELECT member_digicoins.type_id, member_digicoins.points, member_digicoins.date_time, tracks.artist, tracks.title FROM  member_digicoins
    
          left join tracks on member_digicoins.track_id = tracks.id
    
             $where  order by $sort limit $start, $limit");
    
            $result['numRows']  = count($query);
    
            $result['data']  = $query;
    
            return  $result;
        }
    
        function get_client_available_digicoins_cld($client_id)

        {

            $query = DB::select("SELECT available_points FROM client_digicoins_available where client_id = ? order by client_digicoin_available_id desc", [$client_id]);

            $result['numRows'] = count($query);

            $result['data']  = $query;

            return $result;
        }

        function deleteClientLabelRep_cld($did, $clientId)

        {

            $query = DB::select("delete FROM  client_contacts where id = ? and  client_id = ?", [$did, $clientId]);

            return 1;
        }

        function getClientLabelRep_cld($id, $clientId)

        {

            $query = DB::select("SELECT * FROM  client_contacts where id = ? and client_id = ?", [$id, $clientId]);

            $result['numRows'] = count($query);

            $result['data']  = $query;

            return $result;
        }


        function getClientLabelReps_cld($where, $sort, $start, $limit)
        // function getClientLabelReps_cld($clientId)
    
        {   

            $query = DB::select("SELECT * FROM  client_contacts
    
            $where  order by $sort limit $start, $limit");
    
            //$query = DB::select("SELECT * FROM  client_contacts where client_id = '$clientId' order by id desc");
    
            $result['numRows'] = count($query);
    
            $result['data']  = $query;
    
            return $result;
        }

        function getNumClientLabelReps_cld($where, $sort)
    
        {
    
            $query = DB::select("SELECT * FROM  client_contacts $where  order by $sort");
    
            $result = count($query);
    
            return  $result;
        }
    
        function addClientLabelRep_cld($data, $clientId)
    
        {
    
            extract($data);
    
            $email1 = '';
    
            if (isset($email[1])) {
    
                $email1 = $email[1];
            }
    
            $email2 = '';
    
            if (isset($email[2])) {
    
                $email2 = $email[2];
            }
    
            $phone1 = '';
    
            if (isset($phone[1])) {
    
                $phone1 = $phone[1];
            }
    
            $phone2 = '';
    
            if (isset($phone[2])) {
    
                $phone2 = $phone[2];
            }
    
    
            $insert_data = array(
                'client_id' => $clientId,
                'title' => urlencode($title),
                'name' => urlencode($name),
                'email' => urlencode($email[0]),
                'phone' => urlencode($phone[0]),
                'deleted' => 0,
                'mobilePhone' => urlencode($mobile),
                'email1' => urlencode($email1),
                'email2' => urlencode($email2),
                'phone1' => urlencode($phone1),
                'phone2' => urlencode($phone2),
               
        
            );
     
            $insert_id = DB::table('client_contacts')->insertGetId($insert_data);
    
            // $query = DB::select("insert into `client_contacts` (`client_id`, `title`, `name`, `email`, `phone`, `deleted`, `mobilePhone`, `email1`, `email2`, `phone1`, `phone2`)
    
            // values ('" . $clientId . "', '" . urlencode($title) . "', '" . urlencode($name) . "', '" . urlencode($email[0]) . "', '" . urlencode($phone[0]) . "', '0', '" . urlencode($mobile) . "', '" . urlencode($email1) . "', '" . urlencode($email2) . "', '" . urlencode($phone1) . "', '" . urlencode($phone2) . "')");
    
            // $insert_id = $this->db->insert_id();
    
            return  $insert_id;
        }
    
        function updateClientLabelRep_cld($data, $id, $clientId)

        {

            extract($data);

            $email1 = '';

            if (isset($email[1])) {

                $email1 = $email[1];
            }

            $email2 = '';

            if (isset($email[2])) {

                $email2 = $email[2];
            }

            $phone1 = '';

            if (isset($phone[1])) {

                $phone1 = $phone[1];
            }

            $phone2 = '';

            if (isset($phone[2])) {

                $phone2 = $phone[2];
            }

            $query = DB::select("update `client_contacts` set title = ?, name = ?, email = ?, phone = ?, mobilePhone = ?, email1 = ?, email2 = ?, phone1 = ?, phone2 = ? where id = ? and client_id = ?", [urlencode($title), urlencode($name), urlencode($email[0]), urlencode($phone[0]), urlencode($mobile), urlencode($email1), urlencode($email2), urlencode($phone1), urlencode($phone2), $id, $clientId]);

            return 1;
        }
    
        public function getClientInfo_cld($clientId){

            $query = DB::select("SELECT * FROM  clients where id = ?", [$clientId]);

            $result['numRows'] = count($query);

            $result['data']  = $query;

            return  $result;
        }

        public function getClientImage_cld($clientId){

            $query = DB::select("SELECT * FROM  client_images where clientId = ? order by imageId desc limit 1", [$clientId]);

            $result['numRows'] = count($query);

            $result['data']  = $query;

            return  $result;
        }

        public function getClientSocialInfo_cld($clientId){

            $query = DB::select("SELECT * FROM  client_social_media where clientId = ?", [$clientId]);

            $result['numRows'] = count($query);

            $result['data']  = $query;

            return  $result;
        }
    
        function getClientInbox_cld($clientId)

        {

            $query =  DB::select("SELECT * FROM chat_messages where ((receiverType = '1' AND receiverId = ?) OR (senderType = '1' AND senderId = ?)) AND latest = '0' order by messageId desc", [$clientId, $clientId]);

            $result['numRows'] = count($query);

            $result['data']  = $query;

            return $result;
        }

        function getClientInboxTotalCount($clientId)

        {

            $query =  DB::select("SELECT * FROM chat_messages where ((receiverType = '1' AND receiverId = ?) OR (senderType = '1' AND senderId = ?)) AND latest = '0' order by messageId desc", [$clientId, $clientId]);

            $result = count($query);

            return $result;
        }

        function getClientInboxAllMessages($clientId, $start, $limit)

        {
		/* 	echo "SELECT * FROM chat_messages where ((receiverType = '1' AND receiverId = '" . $clientId . "') OR (senderType = '1' AND senderId = '" . $clientId . "')) AND latest = '0' order by messageId desc LIMIT $start, $limit";die(); */
            $query =  DB::select("SELECT * FROM chat_messages where ((receiverType = '1' AND receiverId = ?) OR (senderType = '1' AND senderId = ?)) AND latest = '0' order by messageId desc LIMIT $start, $limit", [$clientId, $clientId]);

            $result['numRows'] = count($query);

            $result['data']  = $query;

            return $result;
        }
    
        function getMemberDetails_cld($memberId)
        {
            $result['id'] = 0;
            $query =  DB::select("SELECT * FROM members where id = ?", [$memberId]);
            $numRows = count($query);
            $data  = $query;
            if ($numRows > 0) {
                $result['id'] = $data[0]->id;
                $result['uname'] = $data[0]->uname;
                $result['fname'] = $data[0]->fname;
                $result['age'] = $data[0]->age;
                $result['email'] = $data[0]->email;
            }

            $query1 =  DB::select("SELECT image,pCloudFileID_mem_image FROM member_images where memberId = ? order by imageId desc limit 1", [$memberId]);
            $numRows1 = count($query1);
            $data1  = $query1;
            if ($numRows1 > 0) {
                if(!empty($data1[0]->pCloudFileID_mem_image)){
                     $result['image'] = $data1[0]->pCloudFileID_mem_image;
                }else{
                    $result['image'] = $data1[0]->image;
                }

            } else {
                $result['image'] = '';
            }
            return $result;
        }
    
        function getClientStarredMessages_cld($clientId)

        {

            $query = DB::select("SELECT chat_messages_starred.userId, chat_messages.messageId, chat_messages.senderType, chat_messages.senderId, chat_messages.receiverType, chat_messages.receiverId, chat_messages.message, chat_messages.dateTime FROM chat_messages

            left join chat_messages_starred on chat_messages.messageId = chat_messages_starred.messageId

            where ((chat_messages.receiverType = '1' AND chat_messages.receiverId = ?) OR (chat_messages.senderType = '1' AND chat_messages.senderId = ?)) AND chat_messages_starred.userType = '1' AND chat_messages_starred.userId = ? order by chat_messages_starred.messageId desc", [$clientId, $clientId, $clientId]);

            $result['numRows'] = count($query);

            $result['data']  = $query;

            return $result;
        }

        function getClientStarredTotalCount($clientId)

        {

            $query = DB::select("SELECT chat_messages_starred.userId, chat_messages.messageId, chat_messages.senderType, chat_messages.senderId, chat_messages.receiverType, chat_messages.receiverId, chat_messages.message, chat_messages.dateTime FROM chat_messages

            left join chat_messages_starred on chat_messages.messageId = chat_messages_starred.messageId

            where ((chat_messages.receiverType = '1' AND chat_messages.receiverId = ?) OR (chat_messages.senderType = '1' AND chat_messages.senderId = ?)) AND chat_messages_starred.userType = '1' AND chat_messages_starred.userId = ? order by chat_messages_starred.messageId desc", [$clientId, $clientId, $clientId]);

            $result = count($query);

            return $result;
        }

        function getClientStarredMessagesAll($clientId, $start, $limit)

        {

            $query = DB::select("SELECT chat_messages_starred.userId, chat_messages.messageId, chat_messages.senderType, chat_messages.senderId, chat_messages.receiverType, chat_messages.receiverId, chat_messages.message, chat_messages.dateTime FROM chat_messages

            left join chat_messages_starred on chat_messages.messageId = chat_messages_starred.messageId

            where ((chat_messages.receiverType = '1' AND chat_messages.receiverId = ?) OR (chat_messages.senderType = '1' AND chat_messages.senderId = ?)) AND chat_messages_starred.userType = '1' AND chat_messages_starred.userId = ? order by chat_messages_starred.messageId desc LIMIT $start, $limit", [$clientId, $clientId, $clientId]);

            $result['numRows'] = count($query);

            $result['data']  = $query;

            return $result;
        }
    
        function getClientArchivedMessages_cld($clientId)

        {

            $query = DB::select("SELECT chat_messages_archived.userId, chat_messages.messageId, chat_messages.senderType, chat_messages.senderId, chat_messages.receiverType, chat_messages.receiverId, chat_messages.message, chat_messages.dateTime FROM chat_messages

       left join chat_messages_archived on chat_messages.messageId = chat_messages_archived.messageId

       where ((chat_messages.receiverType = '1' AND chat_messages.receiverId = ?) OR (chat_messages.senderType = '1' AND chat_messages.senderId = ?)) AND chat_messages_archived.userType = '1' AND chat_messages_archived.userId = ? order by chat_messages_archived.messageId desc", [$clientId, $clientId, $clientId]);

            $result['numRows'] = count($query);

            $result['data']  = $query;

            return $result;
        }

        function getClientArchivedMsgesTotalCount($clientId)

        {

            $query = DB::select("SELECT chat_messages_archived.userId, chat_messages.messageId, chat_messages.senderType, chat_messages.senderId, chat_messages.receiverType, chat_messages.receiverId, chat_messages.message, chat_messages.dateTime FROM chat_messages

       left join chat_messages_archived on chat_messages.messageId = chat_messages_archived.messageId

       where ((chat_messages.receiverType = '1' AND chat_messages.receiverId = ?) OR (chat_messages.senderType = '1' AND chat_messages.senderId = ?)) AND chat_messages_archived.userType = '1' AND chat_messages_archived.userId = ? order by chat_messages_archived.messageId desc", [$clientId, $clientId, $clientId]);

            $result = count($query);

            return $result;
        }

        function getClientArchivedMessagesAll($clientId, $start, $limit)

        {

            $query = DB::select("SELECT chat_messages_archived.userId, chat_messages.messageId, chat_messages.senderType, chat_messages.senderId, chat_messages.receiverType, chat_messages.receiverId, chat_messages.message, chat_messages.dateTime FROM chat_messages

       left join chat_messages_archived on chat_messages.messageId = chat_messages_archived.messageId

       where ((chat_messages.receiverType = '1' AND chat_messages.receiverId = ?) OR (chat_messages.senderType = '1' AND chat_messages.senderId = ?)) AND chat_messages_archived.userType = '1' AND chat_messages_archived.userId = ? order by chat_messages_archived.messageId desc LIMIT $start, $limit", [$clientId, $clientId, $clientId]);

            $result['numRows'] = count($query);

            $result['data']  = $query;

            return $result;
        }
		
		function getClientMembersWhoReviewedCount($clientId)
        {

            $query = DB::select("SELECT d.*
                                        FROM tracks AS a
                                        INNER JOIN clients AS b
                                        ON a.client = b.id AND a.client = ?
                                        INNER JOIN tracks_reviews AS c
                                        ON a.id = c.track
                                        INNER JOIN members AS d
                                        ON c.member = d.id
                                        GROUP BY d.id", [$clientId]);

            $result = count($query);

            return $result;
        }

        function getClientMembersWhoReviewed_cld($clientId,$start,$limit)
        {

            $query = DB::select("SELECT d.*
                                        FROM tracks AS a
                                        INNER JOIN clients AS b
                                        ON a.client = b.id AND a.client = ?
                                        INNER JOIN tracks_reviews AS c
                                        ON a.id = c.track
                                        INNER JOIN members AS d
                                        ON c.member = d.id
                                        GROUP BY d.id limit $start, $limit", [$clientId]);

            $result['numRows'] = count($query);
            $result['data']  = $query;

            return $result;
        }
    
        function getConversation_cld($memberId, $clientId)

        {

            // SECURITY FIX: Verify logged-in client owns this conversation
            $loggedInClientId = Session::get('clientId');
            if ($clientId != $loggedInClientId) {
                // Client trying to read someone else's messages - deny access
                $result['numRows'] = 0;
                $result['data'] = [];
                return $result;
            }

            // SECURITY FIX: Use Query Builder to prevent SQL injection
            $query = DB::table('chat_messages')
                ->where(function($q) use ($memberId, $clientId) {
                    $q->where('senderType', 2)
                      ->where('senderId', $memberId)
                      ->where('receiverType', 1)
                      ->where('receiverId', $clientId);
                })
                ->orWhere(function($q) use ($memberId, $clientId) {
                    $q->where('senderType', 1)
                      ->where('senderId', $clientId)
                      ->where('receiverType', 2)
                      ->where('receiverId', $memberId);
                })
                ->orderBy('messageId', 'desc')
                ->get()
                ->toArray();

            $result['numRows'] = count($query);

            $result['data'] = $query;

            return $result;
        }
    
        function isClientStarred_cld($clientId, $messageId)

        {

            // SECURITY FIX: Use Query Builder to prevent SQL injection
            $query = DB::table('chat_messages_starred')
                ->where('userType', 1)
                ->where('userId', $clientId)
                ->where('messageId', $messageId)
                ->get();

            return count($query);
        }

        function isClientArchived_cld($clientId, $messageId)

        {

            // SECURITY FIX: Use Query Builder to prevent SQL injection
            $query = DB::table('chat_messages_archived')
                ->where('userType', 1)
                ->where('userId', $clientId)
                ->where('messageId', $messageId)
                ->get();

            return count($query);
        }
    
        function subscribeClient_cld($packageId)
    
        {   
            $clientId = Session::get('clientId');
            $insert_data = array(
                'clientId' => $clientId,
                'packageId' => $packageId,
                'subscribedDateTime' => NOW(),  
        
            );
     
            $insert_id = DB::table('client_subscriptions')->insertGetId($insert_data);
    
            // $query = $this->db->query("insert into client_subscriptions (`clientId`, `packageId`, `subscribedDateTime`) values ('" . $_SESSION['clientId'] . "',  '" . $packageId . "', NOW())");
    
            // $insert_id = $this->db->insert_id();
    
            return $insert_id;
        }
    
        function getSubscriptionDetails_cld($clientSubscriptionId)

        {

            $query =  DB::select("SELECT packageId FROM client_subscriptions where subscriptionId = ?", [$clientSubscriptionId]);

            $result['numRows'] = count($query);

            $result['data']  = $query;

            return $result;
        }

        function makeClientMsgRead_cld($memberId, $clientId)

        {

            $query = DB::select("update chat_messages set unread = '1' where (receiverType = '1' AND receiverId = ?) AND (senderType = '2' AND senderId = ?) AND unread = '0'", [$clientId, $memberId]);

            return $query;
        }
    
        function sendClientMessage_cld($clientId, $message, $mid)

        {

            $query =  DB::select("update chat_messages set latest = '1' where

            (senderType = '2' AND senderId = ? AND receiverType = '1' AND receiverId = ?)

            OR

            (senderType = '1' AND senderId = ? AND receiverType = '2' AND receiverId = ?)", [$mid, $clientId, $clientId, $mid]);


            $insert_data = array(
                'senderType' => 1,
                'senderId' => $clientId,
                'receiverType' => 2,
                'receiverId' => $mid,
                'message' => htmlspecialchars($message, ENT_QUOTES, 'UTF-8'),  // XSS protection
                'dateTime' => NOW(),

            );

            $insert_id = DB::table('chat_messages')->insertGetId($insert_data);



            // $query =  DB::select("insert into chat_messages (`senderType`, `senderId`, `receiverType`, `receiverId`, `message`, `dateTime`) values ('1', '" . $clientId . "', '2', '" . $mid . "',  '" . addslashes($message) . "',  NOW())");

            // $insert_id = $this->db->insert_id();

            return $insert_id;
        }
    
        function archiveClientMessage_cld($clientId, $messageId)

        {

            $query =  DB::select("SELECT autoId FROM chat_messages_archived where userType = '1' and userId = ? and messageId = ?", [$clientId, $messageId]);

            $numRows = count($query);

            if ($numRows > 0) {

                $query1 =  DB::select("delete FROM  chat_messages_archived where userType = '1' and userId = ? and messageId = ?", [$clientId, $messageId]);

                $result['result'] = $query1;

                $result['transaction'] = 0;
            } else {

                $insert_data = array(
                    'userType' => 1,
                    'userId' => $clientId,
                    'messageId' => $messageId,


                );

                $insert_id = DB::table('chat_messages_archived')->insertGetId($insert_data);

                // $query2 =  DB::select("insert into chat_messages_archived (`userType`, `userId`, `messageId`) values ('1', '" . $clientId . "', '" . $messageId . "')");

                $result['result'] = $insert_id;

                $result['transaction'] = 1;
            }

            return $result;
        }

        function starClientMessage_cld($clientId, $messageId)

        {

            $query = DB::select("SELECT autoId FROM chat_messages_starred where userType = '1' and userId = ? and messageId = ?", [$clientId, $messageId]);

            $numRows = count($query);

            if ($numRows > 0) {

                $query1 = DB::select("delete FROM  chat_messages_starred where userType = '1' and userId = ? and messageId = ?", [$clientId, $messageId]);

                $result['result'] = $query1;

                $result['transaction'] = 0;
            } else {

                $insert_data = array(
                    'userType' => 1,
                    'userId' => $clientId,
                    'messageId' => $messageId,


                );

                $insert_id = DB::table('chat_messages_starred')->insertGetId($insert_data);

                // $query2 = $this->db->query("insert into chat_messages_starred (`userType`, `userId`, `messageId`) values ('1', '" . $clientId . "', '" . $messageId . "')");

                $result['result'] = $insert_id;

                $result['transaction'] = 1;
            }

            return $result;
        }
        function confirmSubmittedTrack_cld($trackId)

    {

        $result = DB::select("update tracks_submitted set previewTrack = '1' where id = ? and previewTrack = '0'", [$trackId]);

        return $result;
    }

    function getSubmittedTrack_cld($where)

    {

        $query = DB::select("SELECT genres.genreId, genres.genre, genres_sub.subGenreId, genres_sub.subGenre, tracks_submitted.id, tracks_submitted.artist, tracks_submitted.title, tracks_submitted.producers, tracks_submitted.contact_email, tracks_submitted.time, tracks_submitted.bpm, tracks_submitted.albumType, tracks_submitted.album, tracks_submitted.releasedate, tracks_submitted.link, tracks_submitted.link1, tracks_submitted.link2, tracks_submitted.moreinfo, tracks_submitted.approved, tracks_submitted.added, tracks_submitted.deleted, tracks_submitted.imgpage, tracks_submitted.thumb, tracks_submitted.label, tracks_submitted.facebookLink, tracks_submitted.twitterLink, tracks_submitted.instagramLink, tracks_submitted.applemusicLink, tracks_submitted.amazonLink, tracks_submitted.spotifyLink, tracks_submitted.videoURL, tracks_submitted.embedvideoURL, tracks_submitted.pCloudFileID,tracks_submitted.pCloudParentFolderID, tracks_submitted.amr1, tracks_submitted.amr2, tracks_submitted.amr3, tracks_submitted.amr4, tracks_submitted.amr5, tracks_submitted.amr6, tracks_submitted.writer, tracks_submitted.songkey, tracks_submitted.snapchatLink, tracks_submitted.tiktokLink, tracks_submitted.otherLink, tracks_submitted.contact_name, tracks_submitted.contact_email_2, tracks_submitted.contact_email_3, tracks_submitted.contact_email_4, tracks_submitted.contact_phone, tracks_submitted.relationship_to_artist FROM  tracks_submitted
        left join genres on tracks_submitted.genreId = genres.genreId
        left join genres_sub on tracks_submitted.subGenreId = genres_sub.subGenreId
        $where");

        $result['numRows'] = count($query);

        $result['data']  = $query;

        return $result;
    }

    function updateClientSubmittedTrack_cld($data, $clientId, $trackId)

    {

        extract($data);

        $releasedate = $data['year'] . '-' . $data['month'] . '-' . $data['day'];

        $query = DB::select("update  tracks_submitted set  artist = ?, title = ?, producers = ?, writer = ? , songkey = ? , contact_email = ? , time = ?, link =  ?,  album = ?,  releasedate = ?, moreinfo = ?, genreId = ?, subGenreId = ?, bpm = ?, albumType = ?, link1 = ?, link2 = ?, facebookLink = ?, twitterLink = ?, instagramLink = ?, applemusicLink = ?, amazonLink = ?, spotifyLink = ?, snapchatLink = ?, tiktokLink = ?, otherLink = ?, contact_name = ?, contact_email_2 = ?, contact_email_3 = ?, contact_email_4 = ?, contact_phone = ?, relationship_to_artist = ?, videoURL = ?, embedvideoURL = ? where id = ? and client = ?", [urlencode($artist), urlencode($title), urlencode($producer), $writer, $songkey, $contact_email, urlencode($trackTime), urlencode($website), urlencode($album), $releasedate, urlencode($trackInfo), $genre, $subGenre, $bpm, $albumType, $website1, $website2, $facebookLink, $twitterLink, $instagramLink, $applemusicLink, $amazonLink, $spotifyLink, $snapchatLink, $tiktokLink, $otherLink, $contact_name, $contact_email_2, $contact_email_3, $contact_email_4, $contact_phone, $relationship_to_artist, $videoURL, $embedvideoURL, $trackId, $clientId]);

        return  $query;
    }

    function updateClientSubmittedTrackArtWork($id, $image, $clientId,$pcloudFileId,$parentfolderid)

    {

        //echo "update `tracks_submitted` set imgpage = '". $image ."' where id = '". $id ."' and client = '". $clientId ."'";

        $query = DB::select("update tracks_submitted set imgpage = ? , pCloudFileID = ? , pCloudParentFolderID = ? where id = ? and client = ?", [$image, $pcloudFileId, $parentfolderid, $id, $clientId]);

        //exit;

        return 1;
    }

    function getReviewMembers($where)

    {

        $query = DB::select("SELECT tracks_reviews.id, tracks_reviews.member, members.fname, members.stagename  FROM tracks_reviews

		 left join members on tracks_reviews.member = members.id

		 where $where");

        $result['numRows']  = count($query);

        $result['data']  = $query;

        return  $result;
    }

    function getTrackReviewsByReviewId($reviewId)

    {

        $query = DB::select("SELECT tracks_reviews.id, tracks_reviews.whereheard, tracks_reviews.alreadyhave, tracks_reviews.willplay, tracks_reviews.whatrate, tracks_reviews.howsoon, tracks_reviews.howmanyplays, tracks_reviews.anotherformat, tracks_reviews.additionalcomments, tracks_reviews.formats_comradio, tracks_reviews.formats_satradio, tracks_reviews.formats_colradio, tracks_reviews.formats_internet, tracks_reviews.formats_clubs, tracks_reviews.formats_mixtapes, tracks_reviews.formats_musicvideo, tracks_reviews.godistance, tracks_reviews.godistanceyes, tracks_reviews.labelsupport, tracks_reviews.labelsupport_other, tracks_reviews.howsupport, tracks_reviews.howsupport_howsoon, tracks_reviews.likerecord, tracks_reviews.member,  members.stagename, members.city, members.state FROM tracks_reviews

	left join members on tracks_reviews.member = members.id

	where tracks_reviews.id = ? order by tracks_reviews.id desc", [$reviewId]);

        $result['numRows'] =  count($query);

        $result['data'] = $query;

        return $result;
    }

    function removeTrackComment($commentId, $clientId)

    {

        $result = 0;

        $query =  DB::select("SELECT tracks_reviews.id  FROM tracks_reviews left join tracks on tracks_reviews.track = tracks.id where tracks_reviews.id = ? and tracks.client = ?", [$commentId, $clientId]);

        $numRows =  count($query);

        if ($numRows > 0) {

            $query =  DB::select("update tracks_reviews set additionalcomments = '' where id = ?", [$commentId]);

            $result = 1;
        }

        return $result;
    }
    function getTrack($trackId)

    {

        $query =  DB::select("SELECT tracks.*, tracks_mp3s.location FROM  tracks  left join tracks_mp3s ON tracks.id = tracks_mp3s.track where tracks.id = ?", [$trackId]);

        $result['numRows'] =  count($query);

        $result['data']  = $query;

        return $result;
    }

    function getTrackSubmittedVersions($trackId){
        $query =  DB::select("SELECT tracks.id, tracks.artist, tracks.title, tracks_submitted_versions.pcloud_fileId, tracks_submitted_versions.version_name FROM  tracks  left join tracks_submitted_versions ON tracks.id = tracks_submitted_versions.track_id where tracks.id = ? AND tracks_submitted_versions.approved=0 AND tracks_submitted_versions.deleted=0", [$trackId]);

        $result['numRows'] =  count($query);

        $result['data']  = $query;

        ##dd($result);

        return $result;
    }

    function getVideoTips($video_id)

    {

        $query = DB::select("SELECT * FROM  member_digicoins where video_review_id = ?", [$video_id]);

        $result['numRows']  = count($query);

        $result['data']  = $query;

        return $result;
    }

    function getTrackFeedbackVideos($where, $sort, $start, $limit)

    {

        $query = DB::select("SELECT track_video_reviews.video_review_id, track_video_reviews.track_id, track_video_reviews.member_id, track_video_reviews.video_file, track_video_reviews.date_time, tracks.artist, tracks.title, tracks.album, tracks.label, tracks.time, members.fname, members.lname  FROM  track_video_reviews

	left join tracks on track_video_reviews.track_id = tracks.id

	left join members on track_video_reviews.member_id = members.id

	$where order by $sort limit $start, $limit");

        $result['numRows']  = count($query);

        $result['data']  = $query;

        return  $result;
    }

    function getNumTrackFeedbackVideos($where, $sort)

    {

        $query = DB::select("SELECT track_video_reviews.video_review_id FROM  track_video_reviews

	left join tracks on track_video_reviews.track_id = tracks.id $where order by $sort");

        $result = count($query);

        return  $result;
    }

    function addVideoTipToMember($track_id, $tip, $video_review_id)

    {

        $query = DB::select("SELECT * FROM  track_video_reviews where track_id = ? and video_review_id = ?", [$track_id, $video_review_id]);

        $row['numRows']  = count($query);

        $row['data']  = $query;

        $digicoin_id = 0;

        if ($row['numRows'] > 0) {

            $insert_data = array(
                'member_id' => $row['data'][0]->member_id,
                'track_id' => $track_id,
                'type_id' => 5,
                'date_time' => NOW(),
                'points' => $tip,
                'video_review_id' => $video_review_id,



            );

            $insert_id = DB::table('member_digicoins')->insertGetId($insert_data);

            // DB::select("insert into member_digicoins (`member_id`, `track_id`, `type_id`, `video_review_id`, `points`, `date_time`) values ('" . $row['data'][0]->member_id . "', '" . $track_id . "', '5', '" . $video_review_id . "', '" . $tip . "', NOW())");

            $digicoin_id = $insert_id;

            if ($digicoin_id > 0) {

                $available_coins = DB::select("SELECT available_points FROM member_digicoins_available where member_id = ? order by member_digicoin_available_id desc", [$row['data'][0]->member_id]);

                $available_coins_numRows = count($available_coins);

                if ($available_coins_numRows > 0) {

                    $available_digicoins  = $available_coins;

                    $available_digicoins_increment = ($available_digicoins[0]->available_points) + $tip;

                    DB::select("update member_digicoins_available set available_points = ?, latest_date_time = NOW() where member_id = ?", [$available_digicoins_increment, $row['data'][0]->member_id]);
                } else {

                    DB::select("insert into member_digicoins_available (`member_id`, `available_points`, `latest_date_time`) values (?, ?, NOW())", [$row['data'][0]->member_id, $tip]);
                }
            }
        }

        return $digicoin_id;
    }

    
    function addVideoReview($track_id, $video_file, $member_id)

    {


        $insert_data = array(
            'track_id' => $track_id,
            'member_id' => $member_id,
            'video_file' => $video_file,
            'date_time' => NOW(),

           
    
        );
 
        $insert_id = DB::table('track_video_reviews')->insertGetId($insert_data);

        // $query = DB::select("insert into track_video_reviews (`track_id`, `member_id`, `video_file`, `date_time`) values ('" . $track_id . "', '" . $member_id . "', '" . $video_file . "', NOW())");

        // return $this->db->insert_id();

        return $insert_id;
    }

    function updateClientTrack($data, $clientId, $trackId)
    {
        extract($data);
        $release_date = $data['year'] . '-' . $data['month'] . '-' . $data['day'];

        $query = DB::select("update  tracks  set client = ?, artist = ?, title = ?, producer = ?, writer = ? , songkey = ? , time = ?, link =  ?,  album = ?,  release_date = ?, moreinfo = ?, genreId = ?, subGenreId = ?, bpm = ?, videoURL = ?, embedvideoURL = ?, facebookLink = ?, twitterLink = ?, contact_email = ?, instagramLink = ?, applemusicLink = ?, amazonLink = ?, spotifyLink = ?, snapchatLink = ?, tiktokLink = ?, otherLink = ?, contact_name = ?, contact_email_2 = ?, contact_email_3 = ?, contact_email_4 = ?, contact_phone = ?, relationship_to_artist = ? where id = ?", [$clientId, urlencode($artist), urlencode($title), urlencode($producer), $writer, $songkey, urlencode($trackTime), urlencode($website), urlencode($album), $release_date, urlencode($trackInfo), $genre, $subGenre, $bpm, $videoURL, $embedvideoURL, $facebookLink, $twitterLink, $contact_email, $instagramLink, $applemusicLink, $amazonLink, $spotifyLink, $snapchatLink, $tiktokLink, $otherLink, $contact_name, $contact_email_2, $contact_email_3, $contact_email_4, $contact_phone, $relationship_to_artist, $trackId]);
        return  $query;
    }

    function updateClientTrackArtWork($id, $image, $clientId,$pcloudFileId,$parentfolderid)

    {

        //echo "update `tracks_submitted` set imgpage = '". $image ."' where id = '". $id ."' and client = '". $clientId ."'";

        $query = DB::select("update tracks set imgpage = ?  , pCloudFileID = ? , pCloudParentFolderID = ?  where id = ? and client = ?", [$image, $pcloudFileId, $parentfolderid, $id, $clientId]);

        //exit;

        return 1;
    }
    
         // New client querie functions R-s ends here

    // New client querie functions R-s ends here

    function new_track_version_submit($trackMainId, $fileName, $version, $pcloudFileId){

        if(!empty($pcloudFileId) && !empty($trackMainId)){

            $query = DB::select("SELECT * FROM  tracks_submitted_versions  where track_id = ? AND pcloud_fileId = ? AND deleted = '0'", [$trackMainId, $pcloudFileId]);

            $insertData = array(
                'track_id' => $trackMainId,
                'version_name' => urlencode($version),
                'pcloud_fileId' => $pcloudFileId,
                'track_title' => $fileName
            );

            if(count($query) == 0){
                $insertId = DB::table('tracks_submitted_versions')->insertGetId($insertData);
                return $insertId;
            }else{
                 return true;
            }


        }

        return true;
    }

    public function getNumSubmittedTracksVersions_trm($where){
        $query = DB::select("SELECT DISTINCT tracks_submitted_versions.track_id FROM tracks JOIN tracks_submitted_versions ON (tracks.id = tracks_submitted_versions.track_id) LEFT JOIN clients on tracks.client = clients.id $where GROUP BY tracks.id ORDER BY tracks.id DESC");
        $result = count($query);
        return  $result;
     }

     public function getSubmittedTracksVersions_trm($where, $start, $limit){
         $query =DB::select("SELECT tracks.*, tracks_submitted_versions.version_name, clients.uname FROM tracks JOIN tracks_submitted_versions ON (tracks.id = tracks_submitted_versions.track_id) LEFT JOIN clients on tracks.client = clients.id $where GROUP BY tracks.id ORDER BY tracks.id DESC limit $start, $limit");
         $result['numRows']  = count($query);
         $result['data']  = $query;
         return  $result;
     }

     public function getSubmittedVersionsForTrack($where){
         $query =DB::select("SELECT tracks.*, tracks_submitted_versions.id AS submitted_version_id, tracks_submitted_versions.version_name, tracks_submitted_versions.pcloud_fileId, clients.* FROM tracks JOIN tracks_submitted_versions ON (tracks.id = tracks_submitted_versions.track_id) LEFT JOIN clients on tracks.client = clients.id $where ORDER BY tracks_submitted_versions.id DESC");
         $result['numRows']  = count($query);
         $result['data']  = $query;
         return  $result;
     } 

    public function getPeriodicDownloads($trackId) {
        $currentYear = date("Y");
        $firstDayOfMonth = date("Y-m-01");
        $lastDayOfMonth = date("Y-m-t");
        $firstDayOfWeek = date("Y-m-d", strtotime('last monday'));
        $lastDayOfWeek = date("Y-m-d", strtotime('next sunday'));
        $query = DB::select("
            SELECT
                `version`, `downloads`, `num_plays`,
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
                'downloads' => $row->downloads,
                'num_plays' => $row->num_plays,
                'weekly_downloads' => $row->weekly_downloads,
                'monthly_downloads' => $row->monthly_downloads,
                'yearly_downloads' => $row->yearly_downloads
            ];
        }
        
        return $downloadsByMp3Id;
    }        

}