<?php
namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use Session;

class MemberAllDB extends Model
{
    use HasFactory;

    public function test_mem_fun() {

        return true;

    }
	public function getMemberInfo($memberId){

       $queryRes = DB::select("SELECT * FROM  members

    left join members_dj_mixer on members.id = members_dj_mixer.member

   where members.id = ?", [$memberId]);

        $result['numRows'] = count($queryRes);

        $result['data']  = $queryRes;

        return $result;
    }
	
	public function getNotifications(){

       $queryRes = DB::select("SELECT distinct tracks_mp3s.track, tracks_mp3s.bpm, tracks.id, tracks.thumb, tracks.artist, tracks.title, tracks.album, tracks.time, tracks.label, tracks.img, tracks.imgpage, tracks.added, tracks.bpm, tracks.producer, tracks.link, tracks.videoURL FROM  tracks left join tracks_mp3s on tracks.id = tracks_mp3s.track order by tracks_mp3s.id desc limit 0, 5");

        $result['numRows'] = count($queryRes);

        $result['data']  = $queryRes;

        return $result;
    }
	
	
	public function getMemberProductionInfo($memberId){

        $queryRes = DB::select("SELECT productiontype_artist, productiontype_producer, productiontype_choreographer, productiontype_sound, production_name FROM  members_production_talent where member = ?", [$memberId]);

        $result['numRows'] = count($queryRes);

        $result['data']  = $queryRes;

        return $result;
    }
  	public function getMemberSpecialInfo($memberId){

        $queryRes = DB::select("SELECT servicestype_corporate, servicestype_graphicdesign, servicestype_webdesign, servicestype_other, services_name, services_website FROM  members_special_services where member = ?", [$memberId]);

        $result['numRows'] = count($queryRes);

        $result['data']  = $queryRes;

        return $result;
    }
  	public function getMemberPromoterInfo($memberId){

        $queryRes = DB::select("SELECT promotertype_indy, promotertype_club, promotertype_event, promotertype_street, promoter_name, promoter_department, promoter_website FROM members_promoter where member = ?", [$memberId]);

        $result['numRows'] = count($queryRes);

        $result['data']  = $queryRes;

        return $result;
    }
   	public function getMemberClothingInfo($memberId){

        $queryRes = DB::select("SELECT clothing_name, clothing_department FROM members_clothing_apparel where member = ?", [$memberId]);

        $result['numRows'] = count($queryRes);

        $result['data']  = $queryRes;

        return $result;
    }
   	public function getMemberManagementInfo($memberId){

        $queryRes = DB::select("SELECT managementtype_artist, managementtype_tour, managementtype_personal, managementtype_finance, management_name, management_who, management_industry FROM members_management where member = ?", [$memberId]);

        $result['numRows'] = count($queryRes);

        $result['data']  = $queryRes;

        return $result;
    }
    public function getMemberRecordInfo($memberId){

        $queryRes = DB::select("SELECT labeltype_major, labeltype_indy, labeltype_distribution, label_name, label_department FROM members_record_label where member = ?", [$memberId]);

        $result['numRows'] = count($queryRes);

        $result['data']  = $queryRes;

        return $result;
    }
    public function getMemberMediaInfo($memberId){

        $queryRes = DB::select("SELECT mediatype_tvfilm, mediatype_publication, mediatype_newmedia, mediatype_newsletter, media_name, media_website, media_department FROM members_mass_media where member = ?", [$memberId]);

        $result['numRows'] = count($queryRes);

        $result['data']  = $queryRes;

        return $result;
    }
    public function getMemberRadioInfo($memberId){

        $queryRes = DB::select("SELECT * FROM members_radio_station where member = ?", [$memberId]);

        $result['numRows'] = count($queryRes);

        $result['data']  = $queryRes;

        return $result;
    }
    public function getMemberSocialInfo($memberId){

        $queryRes = DB::select("SELECT * FROM  member_social_media where memberId = ?", [$memberId]);

        $result['numRows'] = count($queryRes);

        $result['data']  = $queryRes;

        return $result;
    }
     public function getStaffSelectedTracks($start, $limit){

        $queryRes = DB::select("SELECT tracks.id, tracks.artist, tracks.album, tracks.title, tracks.imgpage, tracks.thumb,tracks.pCloudFileID FROM staff_selection

   left join tracks on staff_selection.trackId = tracks.id

   order by staff_selection.staffTrackId desc limit $start, $limit");

        $result['numRows'] = count($queryRes);

        $result['data']  = $queryRes;

        return $result;
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
	
	
    public function getClientTrackReview($tid, $memId){

        $queryRes = DB::select("SELECT * FROM tracks_reviews where member = ? and track = ?", [$memId, $tid]);

        $result['numRows'] = count($queryRes);

        $result['data']  = $queryRes;

        return $result;
    }
	
    public function getTrackMp3s($trackId){

        $queryRes = DB::select("SELECT tracks_mp3s.id, tracks.client, tracks.artist, tracks.title, tracks_mp3s.version, tracks_mp3s.preview, tracks_mp3s.location FROM tracks left join tracks_mp3s ON tracks.id = tracks_mp3s.track where tracks_mp3s.track = ? order by tracks_mp3s.preview desc", [$trackId]);

        $result['numRows'] = count($queryRes);

        $result['data']  = $queryRes;

        return $result;
    }
	
	public function getTrackMp3s1($trackId){

        $queryRes = DB::select("SELECT tracks_mp3s.id, tracks.client, tracks.artist, tracks.title, tracks_mp3s.version, tracks_mp3s.location FROM

   tracks left join tracks_mp3s ON tracks.id = tracks_mp3s.track where tracks_mp3s.track = ? order by tracks_mp3s.preview desc", [$trackId]);

        $result['numRows'] = count($queryRes);

        $result['data']  = $queryRes;

        return $result;
    }
	
    public function getYouSelectedTracks($start, $limit){

        $queryRes = DB::select("SELECT tracks.id, tracks.artist, tracks.album, tracks.title, tracks.imgpage, tracks.thumb FROM tracks

   limit $start, $limit");

        $result['numRows'] = count($queryRes);

        $result['data']  = $queryRes;

        return $result;
    }
	
	public function getYouSelectedTracks1($memberId, $start, $limit){
		//pArr($memberId);die('--YSYS');
		$query1 = DB::select("SELECT DISTINCT track_member_downloads.trackId, track_member_downloads.mp3Id, tracks_mp3s.version, track_member_downloads.downloadId  FROM track_member_downloads left join tracks_mp3s on track_member_downloads.mp3Id = tracks_mp3s.id where track_member_downloads.memberId = ? order by track_member_downloads.downloadId desc limit $start, $limit", [$memberId]);

			$numRows1 = count($query1);

			$data1  = $query1;
			
			if ($numRows1 > 0) {

				$tracks = array();

				$versions = array();

				foreach ($data1 as $dat) {

					$tracks[] = $dat->trackId;

					$mp3Ids[] = $dat->mp3Id;

					$versions[] = "'" . addslashes($dat->version) . "'";
				}

				$downloaded_versions = implode(',', $versions);

				$downloaded_versions = '(' . $downloaded_versions . ')';

				$query2 = DB::select("SELECT DISTINCT tracks.id, tracks_mp3s.id, tracks_mp3s.version,  tracks.artist, tracks.album, tracks.title, tracks.imgpage, tracks.thumb FROM tracks_mp3s

	 left join tracks on tracks_mp3s.track = tracks.id

	 where tracks_mp3s.version IN $downloaded_versions order by tracks_mp3s.id desc limit $start, $limit");

				$result['numRows'] = count($query2);

				$result['data']   = $query2;

				/*$query = $this->db->query("SELECT tracks.id, tracks.artist, tracks.album, tracks.title, tracks.imgpage, tracks.thumb FROM you_selection

	   left join tracks on you_selection.trackId = tracks.id

	   order by you_selection.youTrackId desc limit $start, $limit");

	   $result['numRows'] = $query->num_rows();

	   $result['data']  = $query->result();*/
			} else {

				$query = DB::select("SELECT tracks.id, tracks.artist, tracks.album, tracks.title, tracks.imgpage, tracks.thumb FROM you_selection

	   left join tracks on you_selection.trackId = tracks.id

	   order by you_selection.youTrackId desc limit $start, $limit");

				$result['numRows'] = count($query);

				$result['data']  = $query;
			}
		
			return $result;
	}
	
	public function getMemberUnreadInbox($memberId){

        $queryRes = DB::select("SELECT * FROM chat_messages where (receiverType = '2' AND receiverId = ?)  AND latest = '0' AND unread = '0' order by messageId desc", [$memberId]);

        $result['numRows'] = count($queryRes);

        $result['data']  = $queryRes;

        return $result;		
		
	}	
	public function getMemberUnreadInboxTotalCount($memberId){

        $queryRes = DB::select("SELECT * FROM chat_messages where (receiverType = '2' AND receiverId = ?)  AND latest = '0' AND unread = '0' order by messageId desc", [$memberId]);

        $result = count($queryRes);       

        return $result;		
		
	}
	public function getMemberUnreadInboxAll($memberId, $start, $limit){

        $queryRes = DB::select("SELECT * FROM chat_messages where (receiverType = '2' AND receiverId = ?)  AND latest = '0' AND unread = '0' order by messageId desc limit $start, $limit", [$memberId]);

        $result['numRows'] = count($queryRes);

        $result['data']  = $queryRes;     

        return $result;		
		
	}	
	public function getMemberSubscriptionStatus($memberId){

        $queryRes = DB::select("SELECT status, package_Id, subscription_Id FROM member_subscriptions where member_Id = ? and status = '1' order by subscription_Id desc limit 1", [$memberId]);

        $result['numRows'] = count($queryRes);

        $result['data']  = $queryRes;

        return $result;		
		
	}
	public function getMemberSubscription($memberId){

        $queryRes = DB::select("SELECT package_Id, subscribed_date_time FROM member_subscriptions where member_Id = ? and status = '1' order by subscription_Id desc limit 1", [$memberId]);

        $result['numRows'] = count($queryRes);

        $result['data']  = $queryRes;

        return $result;		
		
	}
	public function getMemberFooterTracks($memPkg){
		
		$where = "where tracks.deleted = '0'";
		if(!empty($memPkg)){
			if($memPkg < 3) {

				$where = "where tracks.deleted = '0' and tracks_mp3s.preview = '1'";
			}
		}
        $queryRes = DB::select("SELECT tracks_mp3s.id, tracks.client, tracks.artist, tracks.title, tracks.imgpage, tracks_mp3s.location FROM

	   tracks left join tracks_mp3s ON tracks.id = tracks_mp3s.track $where order by RAND() limit 0, 50");

        $result['numRows'] = count($queryRes);

        $result['data']  = $queryRes;

        return $result;
	}
	
	public function getMemberInboxTotalCount($memberId){

       $queryRes = DB::select("SELECT * FROM chat_messages where ((receiverType = '2' AND receiverId = ?) OR (senderType = '2' AND senderId = ?)) AND latest = '0' order by messageId desc", [$memberId, $memberId]);

        $result = count($queryRes);

        return $result;		
	}
		
	public function getMemberInbox($memberId){

       $queryRes = DB::select("SELECT * FROM chat_messages where ((receiverType = '2' AND receiverId = ?) OR (senderType = '2' AND senderId = ?)) AND latest = '0' order by messageId desc", [$memberId, $memberId]);

        $result['numRows'] = count($queryRes);

        $result['data']  = $queryRes;

        return $result;		
	}
			
	public function getMemberAllInbox($memberId, $start, $limit){

       $queryRes = DB::select("SELECT * FROM chat_messages where ((receiverType = '2' AND receiverId = ?) OR (senderType = '2' AND senderId = ?)) AND latest = '0' order by messageId desc limit $start, $limit", [$memberId, $memberId]);

        $result['numRows'] = count($queryRes);

        $result['data']  = $queryRes;

        return $result;		
	}
	
	public function getNumProducts($where, $sort){
		
       $queryRes = DB::select("SELECT admins.name, products.product_id, products.company_id, products.name, products.link, products.emailimg, products.product_details, products.product_technology, products.product_gender, products.launch_date, products.suggested_price, products.model FROM  products

   left join admins on products.addedby = admins.id

   $where order by $sort");

        $result = count($queryRes);

        return $result;		
	}
	
	
	public function getProducts($where, $sort, $start, $limit){
		
       $queryRes = DB::select("SELECT admins.name, products.product_id, products.company_id, products.name, products.link, products.emailimg, products.product_details, products.product_technology, products.product_gender, products.launch_date, products.suggested_price, products.model FROM  products

   left join admins on products.addedby = admins.id

   $where order by $sort limit $start, $limit");

        $result['numRows'] = count($queryRes);

        $result['data']  = $queryRes;

        return $result;		
	}
 	
	public function getClientInfo($clientId){

       $queryRes = DB::select("SELECT * FROM  clients where id = ?", [$clientId]);

        $result['numRows'] = count($queryRes);

        $result['data']  = $queryRes;

        return $result;		
	}
  	
	public function getClientImage($clientId){

       $queryRes = DB::select("SELECT * FROM  client_images where clientId = ? order by imageId desc limit 1", [$clientId]);

        $result['numRows'] = count($queryRes);
        if(!empty($queryRes[0]->pCloudFileID_client_image)){
            $queryRes['0']->image= $queryRes['0']->pCloudFileID_client_image;
        }

        $result['data']  = $queryRes;
       // pArr($queryRes);

        return $result;		
	}
   	
	public function getMemberStarredMessages($memberId){

       $queryRes = DB::select("SELECT chat_messages_starred.userId, chat_messages.messageId, chat_messages.senderType, chat_messages.senderId, chat_messages.receiverType, chat_messages.receiverId, chat_messages.message, chat_messages.dateTime FROM chat_messages

   left join chat_messages_starred on chat_messages.messageId = chat_messages_starred.messageId

   where ((chat_messages.receiverType = '2' AND chat_messages.receiverId = ?) OR (chat_messages.senderType = '2' AND chat_messages.senderId = ?)) AND chat_messages_starred.userType = '2' AND chat_messages_starred.userId = ? AND chat_messages.message != '' order by chat_messages_starred.messageId desc", [$memberId, $memberId, $memberId]);

        $result['numRows'] = count($queryRes);

        $result['data']  = $queryRes;

        return $result;		
	}    	
	public function getMemberStarredTotalCount($memberId){

       $queryRes = DB::select("SELECT chat_messages_starred.userId, chat_messages.messageId, chat_messages.senderType, chat_messages.senderId, chat_messages.receiverType, chat_messages.receiverId, chat_messages.message, chat_messages.dateTime FROM chat_messages

   left join chat_messages_starred on chat_messages.messageId = chat_messages_starred.messageId

   where ((chat_messages.receiverType = '2' AND chat_messages.receiverId = ?) OR (chat_messages.senderType = '2' AND chat_messages.senderId = ?)) AND chat_messages_starred.userType = '2' AND chat_messages_starred.userId = ? AND chat_messages.message != '' order by chat_messages_starred.messageId desc", [$memberId, $memberId, $memberId]);

        $result = count($queryRes);

        return $result;		
	}    	
	public function getMemberStarredAllMessages($memberId, $start, $limit){

       $queryRes = DB::select("SELECT chat_messages_starred.userId, chat_messages.messageId, chat_messages.senderType, chat_messages.senderId, chat_messages.receiverType, chat_messages.receiverId, chat_messages.message, chat_messages.dateTime FROM chat_messages

   left join chat_messages_starred on chat_messages.messageId = chat_messages_starred.messageId

   where ((chat_messages.receiverType = '2' AND chat_messages.receiverId = ?) OR (chat_messages.senderType = '2' AND chat_messages.senderId = ?)) AND chat_messages_starred.userType = '2' AND chat_messages_starred.userId = ? AND chat_messages.message != '' order by chat_messages_starred.messageId desc LIMIT $start, $limit", [$memberId, $memberId, $memberId]);

        $result['numRows'] = count($queryRes);

        $result['data']  = $queryRes;

        return $result;		
	}   	
	public function getMemberArchivedMessages($memberId){

       $queryRes = DB::select("SELECT chat_messages_archived.userId, chat_messages.messageId, chat_messages.senderType, chat_messages.senderId, chat_messages.receiverType, chat_messages.receiverId, chat_messages.message, chat_messages.dateTime FROM chat_messages

   left join chat_messages_archived on chat_messages.messageId = chat_messages_archived.messageId

   where ((chat_messages.receiverType = '2' AND chat_messages.receiverId = ?) OR (chat_messages.senderType = '2' AND chat_messages.senderId = ?)) AND chat_messages_archived.userType = '2' AND chat_messages_archived.userId = ? AND chat_messages.message != ''  order by chat_messages_archived.messageId desc", [$memberId, $memberId, $memberId]);

        $result['numRows'] = count($queryRes);

        $result['data']  = $queryRes;

        return $result;		
	}
	public function getMemberArchivedTotalCount($memberId){

       $queryRes = DB::select("SELECT chat_messages_archived.userId, chat_messages.messageId, chat_messages.senderType, chat_messages.senderId, chat_messages.receiverType, chat_messages.receiverId, chat_messages.message, chat_messages.dateTime FROM chat_messages

   left join chat_messages_archived on chat_messages.messageId = chat_messages_archived.messageId

   where ((chat_messages.receiverType = '2' AND chat_messages.receiverId = ?) OR (chat_messages.senderType = '2' AND chat_messages.senderId = ?)) AND chat_messages_archived.userType = '2' AND chat_messages_archived.userId = ? AND chat_messages.message != ''  order by chat_messages_archived.messageId desc", [$memberId, $memberId, $memberId]);

        $result = count($queryRes);

        return $result;		
	}
	public function getMemberArchivedAllMessages($memberId, $start, $limit){

       $queryRes = DB::select("SELECT chat_messages_archived.userId, chat_messages.messageId, chat_messages.senderType, chat_messages.senderId, chat_messages.receiverType, chat_messages.receiverId, chat_messages.message, chat_messages.dateTime FROM chat_messages

   left join chat_messages_archived on chat_messages.messageId = chat_messages_archived.messageId

   where ((chat_messages.receiverType = '2' AND chat_messages.receiverId = ?) OR (chat_messages.senderType = '2' AND chat_messages.senderId = ?)) AND chat_messages_archived.userType = '2' AND chat_messages_archived.userId = ? AND chat_messages.message != ''  order by chat_messages_archived.messageId desc LIMIT $start, $limit", [$memberId, $memberId, $memberId]);

        $result['numRows'] = count($queryRes);

        $result['data']  = $queryRes;

        return $result;		
	}

	public function getMemberArchivedConversation($memberId, $clientId){

       $queryRes = DB::select("SELECT * FROM chat_messages

   left join chat_messages_archived on chat_messages.messageId = chat_messages_archived.messageId

   where

   ((chat_messages.senderType = '2' AND chat_messages.senderId = ? AND chat_messages.receiverType = '1' AND chat_messages.receiverId = ?)

   OR

   (chat_messages.senderType = '1' AND chat_messages.senderId = ? AND chat_messages.receiverType = '2' AND chat_messages.receiverId = ?))

   AND chat_messages_archived.userType = '2' AND chat_messages_archived.userId = ?

   order by chat_messages.messageId desc", [$memberId, $clientId, $clientId, $memberId, $memberId]);

        $result['numRows'] = count($queryRes);

        $result['data']  = $queryRes;

        return $result;		
	}
	public function getMemberStarredConversation($memberId, $clientId){

       $queryRes = DB::select("SELECT * FROM chat_messages

   left join chat_messages_starred on chat_messages.messageId = chat_messages_starred.messageId

   where

   ((chat_messages.senderType = '2' AND chat_messages.senderId = ? AND chat_messages.receiverType = '1' AND chat_messages.receiverId = ?)

   OR

   (chat_messages.senderType = '1' AND chat_messages.senderId = ? AND chat_messages.receiverType = '2' AND chat_messages.receiverId = ?))

   AND chat_messages_starred.userType = '2' AND chat_messages_starred.userId = ?

   order by chat_messages.messageId desc", [$memberId, $clientId, $clientId, $memberId, $memberId]);

        $result['numRows'] = count($queryRes);

        $result['data']  = $queryRes;

        return $result;		
	}
	public function addTrackPlay($trackId, $memberId, $countryName, $countryCode){
		
		$insertDta = array(
			'trackId'=>$trackId,
			'memberId'=>$memberId,
			'countryName'=>$countryName,
			'countryCode'=>$countryCode,
			'dateTime'=>date("Y-m-d h:i:s"),
		);
		
		$insertId = DB::table('track_member_play')->insertGetId($insertDta);
		
		return $insertId;
	}
	public function getNumDashboardTracks($where, $sort){
		
		 if($sort == 'album ASC' || $sort == 'album DESC') {    
		  $query = DB::select("SELECT  albums.*, albums.id AS album_id, tracks.artist, tracks.album,tracks.producer, tracks.added AS track_added, tracks.id, tracks.imgpage, tracks.label 
									FROM tracks_album AS albums INNER JOIN tracks AS tracks ON albums.id = tracks.albumid 
									WHERE albums.deleted = '0' AND tracks.deleted = '0' AND tracks.active = '1'"); 
		 } else {
			$query = DB::select("SELECT DISTINCT tracks_mp3s.track, tracks.id 
										FROM tracks
										LEFT JOIN tracks_mp3s ON tracks.id = tracks_mp3s.track 
										LEFT JOIN genres ON genres.genreId = tracks.genreId 
										$where ORDER BY $sort");
		 }
		 return count($query);
	}
	public function getDashboardTracks($where, $sort, $start, $limit,$memId){
		
     if($sort == 'album ASC' || $sort == 'album DESC') {    
      //  if($sort == 'album ASC') { $added = 'added DESC'; } elseif($sort == 'album ASC') { $added = 'added ASC'; } else { }     
      $query = DB::select("SELECT albums.*, albums.id AS album_id, tracks.artist, tracks.album,tracks.producer, tracks.added AS track_added, tracks.id, tracks.imgpage, tracks.label FROM tracks_album AS albums INNER JOIN tracks AS tracks ON albums.id = tracks.albumid WHERE albums.deleted = '0' AND tracks.deleted = '0' AND tracks.active = '1' GROUP BY albums.id ORDER BY tracks.priority DESC, added DESC LIMIT $start, $limit"); 
     } else {
        $query = DB::select("SELECT DISTINCT genres.genre, tracks_mp3s.track, tracks_mp3s.bpm, tracks.id, tracks.artist, tracks.producer, tracks.title, tracks.album, tracks.time, tracks.label, tracks.img, tracks.imgpage, tracks.added, tracks.bpm, tracks.client 
                                    FROM tracks
                                    LEFT JOIN tracks_mp3s on tracks.id = tracks_mp3s.track
                                    LEFT JOIN genres on tracks.genreId = genres.genreId
                                    $where ORDER BY tracks.priority DESC, $sort LIMIT $start, $limit");
      }
        //         echo "SELECT distinct tracks_mp3s.track, tracks_mp3s.bpm, tracks.id, tracks.artist, tracks.title, tracks.album, tracks.time, tracks.label, tracks.img, tracks.imgpage, tracks.added, tracks.bpm, tracks.client FROM  tracks

        //   left join tracks_mp3s on tracks.id = tracks_mp3s.track

        //   $where order by $sort limit $start, $limit"; exit;

        $result['numRows'] = count($query);

        $result['data']  = $query;
		
        $result['downloaded']=array();
        if(!empty($memId) && $memId > 0){
            
            $track_ids= array();
            foreach ($result['data'] as $track) {
                $track_ids[]= $track->id;
            }
            
            if(!empty($track_ids) && $track_ids!=''){
                $query = DB::select("SELECT Distinct trackId FROM track_member_downloads WHERE trackId IN (".implode(",",$track_ids).")  AND memberId=".$memId);
                $track_ids= array();
                foreach ($query as $t) {
                    $track_ids[]=$t->trackId;
                }
            }
            $result['downloaded']  = $track_ids;
        }
        
        

        return $result;		
	}
	
 	public function getOwnArchivesListCount($where, $sort){
		$query = DB::select("SELECT Distinct tracks.id 
                                    FROM tracks
                                    LEFT JOIN tracks_mp3s ON tracks.id = tracks_mp3s.track LEFT JOIN tracks_reviews ON tracks_reviews.track = tracks.id
                                    $where ORDER BY $sort");
		 
		 return count($query);
	} 

 	public function getOwnArchivesList($where,$sort,$start,$limit){
		$query = DB::select("SELECT DISTINCT tracks_mp3s.track, tracks_mp3s.bpm, tracks.id, tracks.artist, tracks.producer, tracks.title, tracks.album, tracks.time, tracks.label, tracks.img, tracks.imgpage, tracks.added, tracks.bpm, tracks.client ,tracks.pCloudFileID ,tracks.pCloudParentFolderID 
                                    FROM tracks
                                    LEFT JOIN tracks_mp3s ON tracks.id = tracks_mp3s.track LEFT JOIN tracks_reviews ON tracks_reviews.track = tracks.id
                                    $where ORDER BY $sort LIMIT $start, $limit");
		 
		 $result['numRows'] = count($query);
         $result['data']  = $query;
         return $result;
	}

 	public function getClientSocialInfo($clientId){
		$query = DB::select("SELECT * FROM  client_social_media where clientId = ?", [$clientId]);
		 
		 $result['numRows'] = count($query);
         $result['data']  = $query;
         return $result;
	}

	
 	public function getNumMemberDigicoins($where, $sort){
		$query = DB::select("SELECT member_digicoin_id FROM  member_digicoins $where  order by $sort");
		 
		 return count($query);
	} 

 	public function getMemberDigicoins($where, $sort, $start, $limit){
		$query = DB::select("SELECT member_digicoins.type_id, member_digicoins.points, member_digicoins.date_time, tracks.artist, tracks.title FROM  member_digicoins

	  left join tracks on member_digicoins.track_id = tracks.id

		 $where  order by $sort limit $start, $limit");
		 
		 $result['numRows'] = count($query);
         $result['data']  = $query;
         return $result;
	}	
	

 	public function get_member_available_digicoins($member_id){
		$query = DB::select("SELECT available_points FROM member_digicoins_available where member_id = ? order by member_digicoin_available_id desc", [$member_id]);
		 
		 $result['numRows'] = count($query);
         $result['data']  = $query;
         return $result;
	}	

 	public function getAllStaffSelectedTracks(){
		$query = DB::select("SELECT tracks.id, tracks.artist, tracks.album, tracks.title, tracks.imgpage, tracks.thumb,tracks.pCloudFileID FROM staff_selection

   left join tracks on staff_selection.trackId = tracks.id

   order by staff_selection.staffTrackId desc");
		 
		 $result['numRows'] = count($query);
         $result['data']  = $query;
         return $result;
	}

 	public function get_num_product_orders($where, $sort){
		$query = DB::select("SELECT products.product_id FROM product_orders

  left join products on product_orders.product_id = products.product_id

  $where order by $sort");
		 
		 $result = count($query);
		 
         return $result;
	}
	
	
 	public function get_product_orders($where, $sort, $start, $limit){
		$query = DB::select("SELECT products.product_id, products.name, product_orders.order_fp, product_orders.order_date_time FROM product_orders

  left join products on product_orders.product_id = products.product_id

  $where order by $sort limit $start, $limit ");
		 
		 $result['numRows'] = count($query);
         $result['data']  = $query;
         return $result;
	}
	
	public function buyNow($member_id, $data){

        $query = DB::select("SELECT available_points FROM member_digicoins_available where member_id = ?", [$member_id]);

        $row['numRows'] = count($query);

        $row['data']  = $query;

        $result['remaining_points'] = 0;

        $result['available_points'] = 0;

        $result['order_id'] = 0;

        $result['status'] = 1;

        if ($row['numRows'] > 0) {

            $result['available_points'] = $row['data'][0]->available_points;

            if ($result['available_points'] >= $data['final_price']) {

                $result['status'] = 2;

                /* $this->db->query("insert into product_orders (`member_id`, `product_id`, `order_rp`, `order_fp`, `order_percentage`, `order_date_time`) values ('" . $member_id . "', '" . $data['product_id'] . "', '" . $data['retail_price'] . "', '" . $data['final_price'] . "', '" . $data['percentage'] . "', NOW())"); */
				
				$insert_data = array(
					'member_id' => $member_id,
					'product_id' => $data['product_id'],
					'order_rp' => $data['retail_price'],
					'order_fp' => $data['final_price'],
					'order_percentage' => $data['percentage'],
					'order_date_time' => NOW(),
				
				);
		 
				$insertId = DB::table('product_orders')->insertGetId($insert_data);

                $result['order_id'] = $insertId;

                $remaining_points = ($result['available_points']) - ($data['final_price']);

                DB::update("update member_digicoins_available set available_points = ?, latest_date_time = NOW() where member_id = ?", [$remaining_points, $member_id]);

                $result['remaining_points'] = $remaining_points;
            } else {

                $result['status'] = 3;
            }
        }

        return $result;
    }
	
	public function getProductReview($product_id, $member_id){

        $query = DB::select("SELECT *  FROM  product_text_answers

   where product_text_answers.product_id = ? and product_text_answers.member_id = ?

   order by product_text_answers.product_text_answer_id ASC", [$product_id, $member_id]);

        $result['text']['numRows'] = count($query);

        $result['text']['data']  = $query;

        $query1 = DB::select("SELECT *

   FROM  product_questions_answered

   left join product_answers on  product_questions_answered.answer_id = product_answers.answer_id

   where product_questions_answered.product_id = ? and product_questions_answered.member_id = ?  order by product_questions_answered.answer_id ASC", [$product_id, $member_id]);

        $result['options']['numRows'] = count($query1);

        $result['options']['data']  = $query1;

        return $result;
    }
	
	public function getProductPrices($where){

        $query = DB::select("SELECT * FROM product_price $where");

        $result['numRows'] = count($query);

        $result['data']  = $query;

        return $result;
    }
	
	public function getProductDiscounts($where){

        $query = DB::select("SELECT * FROM product_discount $where");

        $result['numRows'] = count($query);

        $result['data']  = $query;

        return $result;
    }
	
	public function getProductQuestions($pid){

        $query = DB::select("SELECT product_questions.question_id, product_questions.question, product_product_questions.type FROM  product_product_questions

   left join product_questions on product_product_questions.question_id = product_questions.question_id

   where product_product_questions.product_id = ?  order by product_product_questions.order ASC", [$pid]);

        $result['numRows'] = count($query);

        $result['data']  = $query;

        return $result;
    }
	
	public function getProductAnswers($pid, $qid){

        $query = DB::select("SELECT product_answers.answer_id, product_answers.answer, product_question_answers.product_question_answer_id

   FROM  product_question_answers

   left join product_answers on product_question_answers.answer_id = product_answers.answer_id

   where product_question_answers.product_id = ? and product_question_answers.question_id = ?  order by product_answers.answer ASC", [$pid, $qid]);

        $result['numRows'] = count($query);

        $result['data']  = $query;

        return $result;
    }
	
	public function addProductReview($data, $product_id, $member_id)

    {

        extract($data);
		$lastId = 0;
        if (isset($text)) {

            foreach ($text as $question_id => $value) {

                $insert_id = DB::table('product_text_answers')->insertGetId([
                    'product_id' => $product_id,
                    'question_id' => $question_id,
                    'member_id' => $member_id,
                    'answer' => $value[0]
                ]);

                $lastId = $insert_id;
            }
        }

        if (isset($radio)) {

            foreach ($radio as $question_id => $value) {

                $insert_id = DB::table('product_questions_answered')->insertGetId([
                    'product_id' => $product_id,
                    'question_id' => $question_id,
                    'answer_id' => $value[0],
                    'member_id' => $member_id,
                    'timeanswered' => NOW()
                ]);

                $lastId = $insert_id;
            }
        }

        if (isset($check)) {

            // print_r($check);

            foreach ($check as $question_id => $value) {

                foreach ($value as $val) {

                    $insert_id = DB::table('product_questions_answered')->insertGetId([
                        'product_id' => $product_id,
                        'question_id' => $question_id,
                        'answer_id' => $val,
                        'member_id' => $member_id,
                        'timeanswered' => NOW()
                    ]);

                    $lastId = $insert_id;
                }
            }
        }

        return $lastId;
    }

     // member R-S function starts here 
     function getNumTracks($where, $sort)

     {
 
         $query =  DB::select("SELECT tracks.id FROM  tracks $where order by $sort");
 
         $result = count($query);
 
         return $result;
 
     }
 
 
     function getNewestTracks($where, $sort, $start, $limit){
        //  echo $where;
        //  die();
 
        //  $query = DB::select("SELECT * FROM  tracks $where order by added DESC limit $start, $limit");

        $query =  DB::select("select tracks.*, tracks_album.album_page_image from  
        tracks left join tracks_album on tracks.albumid = tracks_album.id 
        $where order by $sort limit $start, $limit");
         //pArr($query);
         //die('--Model');
         
         $result['query']  = DB::select("SELECT * FROM  tracks $where order by $sort limit $start, $limit");
 
         /*  $query = DB::select("SELECT distinct tracks_mp3s.track, tracks_mp3s.bpm, tracks.id, tracks.artist, tracks.title, tracks.album, tracks.time, tracks.label, tracks.img, tracks.imgpage, tracks.added, tracks.bpm FROM  tracks
            left join tracks_mp3s on tracks.id = tracks_mp3s.track
            $where order by $sort limit $start, $limit");  */
 
         $result['numRows'] = count($query);
         $result['data']  = $query;
         
         
        //  $result['downloaded']=array();
        //  $member_session_id = Session::get('memberId');
        //  if(isset($member_session_id) && $member_session_id>0 ){
             
        //      $track_ids= array();
        //      foreach ($result['data'] as $track) {
        //          $track_ids[]= $track->id;
        //      }
             
        //      $query = DB::select("SELECT Distinct trackId FROM track_member_downloads WHERE trackId IN (".implode(",",$track_ids).")  AND memberId=".$member_session_id);
        //      $track_ids= array();
        //      foreach ($query as $t) {
        //          $track_ids[]=$t->trackId;
        //      }
        //      $result['downloaded']  = $track_ids;
        //  }
        
        
         $result['downloaded']=array();
         $member_session_id = Session::get('memberId');
         if(isset($member_session_id) && $member_session_id>0 ){
             
             $track_ids= array();
             foreach ($result['data'] as $track) {
 
                 if(empty($track->id)){
 
                     continue;
                 }
                 else{
                     $track_ids[]= $track->id;
 
                 }
                
             }
             
             if(!empty($track_ids) && $track_ids!=''){
                 $placeholders = implode(',', array_fill(0, count($track_ids), '?'));
                 $query =  DB::select("SELECT Distinct trackId FROM track_member_downloads WHERE trackId IN (".$placeholders.")  AND memberId=?", array_merge($track_ids, [$member_session_id]));
                 $track_ids= array();
                 foreach ($query as $t) {
                     $track_ids[]=$t->trackId;
                 }
             }
             $result['downloaded']  = $track_ids;
         }
        
         return $result;
     }
 
 
     function getClientTrackReview_fem($tid)
 
     {   
         $member_session_id = Session::get('memberId');
 
         $query = DB::select("SELECT * FROM tracks_reviews where member = ? and track = ?", [$member_session_id, $tid]);
 
         $result['numRows'] = count($query);
 
         $result['data']  = $query;
 
         return $result;
     }
 
     function getTrackMp3s1_fem($trackId)
 
     {
 
         $query = DB::select("SELECT tracks_mp3s.id, tracks.client, tracks.artist, tracks.title, tracks_mp3s.version, tracks_mp3s.location FROM
 
    tracks left join tracks_mp3s ON tracks.id = tracks_mp3s.track where tracks_mp3s.track = ? order by tracks_mp3s.preview desc", [$trackId]);
 
         $result['numRows'] = count($query);
 
         $result['data']  = $query;
 
         return $result;
     }
 
 
     function getStaffSelectedTracks_fem($start, $limit)
 
     {
 
         $query = DB::select("SELECT tracks.id, tracks.artist, tracks.album, tracks.title, tracks.imgpage, tracks.thumb,tracks.pCloudFileID FROM staff_selection
 
    left join tracks on staff_selection.trackId = tracks.id
 
    order by staff_selection.staffTrackId desc limit $start, $limit");
   
    
 
         $result['numRows'] = count($query);
 
         $result['data']  = $query;
 
         return $result;
     }
 
 
     function getTrackPlays_fem($trackId)
 
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
 
     function getMemberFooterTracks_fem()
 
     {
 
         $where = "where tracks.deleted = '0'";
 
         $member_session_pkg = Session::get('memberPackage');
 
         if ($member_session_pkg < 3) {
 
             $where = "where tracks.deleted = '0' and tracks_mp3s.preview = '1'";
         }
 
         //  tracks_mp3s.preview
 
         $query = DB::select("SELECT tracks_mp3s.id, tracks.client, tracks.artist, tracks.title, tracks.imgpage, tracks_mp3s.location FROM
 
        tracks left join tracks_mp3s ON tracks.id = tracks_mp3s.track $where order by RAND() limit 0, 50");
 
         $result['numRows'] = count($query);
 
         $result['data']  = $query;
 
         return $result;
     }
 
     function getMemberUnreadInbox_fem($memberId)
 
     {
 
         $query = DB::select("SELECT * FROM chat_messages where (receiverType = '2' AND receiverId = ?)  AND latest = '0' AND unread = '0' order by messageId desc", [$memberId]);
 
         $result['numRows'] = count($query);
 
         $result['data']  = $query;
 
         return $result;
     }
 
 
     function getMemberSubscriptionStatus_fem($memberId)
 
     {
 
         $query = DB::select("SELECT status, package_Id, subscription_Id FROM member_subscriptions where member_Id = ? and status = '1' order by subscription_Id desc limit 1", [$memberId]);
 
         $result['numRows'] = count($query);
 
         $result['data']  = $query;
 
         return $result;
     }
 
     function getBannerads($user_type, $position)
     {
         $query = DB::select("SELECT banner_ad FROM  banner_ads where user_type = ? and ad_position = ?", [$user_type, $position]);
         $result['numRows'] = count($query);
         $result['data']  = $query;
         return $result;
     }
 
     function getTrackMp3s_fem($trackId)
 
     {
 
         $query = DB::select("SELECT tracks_mp3s.id, tracks.client, tracks.artist, tracks.title, tracks_mp3s.version, tracks_mp3s.preview, tracks_mp3s.location FROM
 
    tracks left join tracks_mp3s ON tracks.id = tracks_mp3s.track where tracks_mp3s.track = ? order by tracks_mp3s.preview desc ", [$trackId]);
 
         $result['numRows'] = count($query);
 
         $result['data']  = $query;
 
         return $result;
     }
 
 
     function getYouSelectedTracks1_fem($memberId, $start, $limit)
 
     {
 
         $query1 =  DB::select("SELECT DISTINCT  track_member_downloads.trackId, track_member_downloads.mp3Id, tracks_mp3s.version  FROM track_member_downloads
 
         left join tracks_mp3s on track_member_downloads.mp3Id = tracks_mp3s.id
 
         where track_member_downloads.memberId = ?
 
         order by track_member_downloads.downloadId desc limit $start, $limit", [$memberId]);
 
                 $numRows1 = count($query1);
 
                 $data1  = $query1;
 
                 if ($numRows1 > 0) {
 
                     $tracks = array();
 
                     $versions = array();
 
                     foreach ($data1 as $dat) {
 
                         $tracks[] = $dat->trackId;
 
                         $mp3Ids[] = $dat->mp3Id;
 
                         $versions[] = "'" . addslashes($dat->version) . "'";
                     }
 
                     $downloaded_versions = implode(',', $versions);
 
                     $downloaded_versions = '(' . $downloaded_versions . ')';
 
                     $query2 =  DB::select("SELECT DISTINCT tracks.id, tracks_mp3s.version,  tracks.artist, tracks.album, tracks.title, tracks.imgpage, tracks.thumb FROM tracks_mp3s
 
         left join tracks on tracks_mp3s.track = tracks.id
 
         where tracks_mp3s.version IN $downloaded_versions order by tracks_mp3s.id desc limit $start, $limit");
 
                     $result['numRows'] = count($query2);
 
                     $result['data']   = $query2;
 
                     /*$query =  DB::select("SELECT tracks.id, tracks.artist, tracks.album, tracks.title, tracks.imgpage, tracks.thumb FROM you_selection
 
         left join tracks on you_selection.trackId = tracks.id
 
         order by you_selection.youTrackId desc limit $start, $limit");
 
         $result['numRows'] = count($query);
 
         $result['data']  = $query;*/
                 } else {
 
                     $query =  DB::select("SELECT tracks.id, tracks.artist, tracks.album, tracks.title, tracks.imgpage, tracks.thumb FROM you_selection
 
         left join tracks on you_selection.trackId = tracks.id
 
         order by you_selection.youTrackId desc limit $start, $limit");
 
             $result['numRows'] = count($query);
 
             $result['data']  = $query;
         }
 
         return $result;
     }
 
 
 
     function addReview($data, $tid, $countryName, $countryCode)
 
     {
 
         // $anotherFormat = implode(',', $data['anotherFormat']);
 
         // $query = DB::select("insert into tracks_reviews (`version`, `track`, `member`, `whereheard`,  `whatrate`,  `anotherformat`, `anotherformat_other`, `additionalcomments`, `added`, `godistance`, `godistanceyes`, `labelsupport`, `labelsupport_other`, `howsupport`, `howsupport_howsoon`, `likerecord`, `countryName`, `countryCode`) values ('2', '" . $tid . "', '" . $_SESSION['memberId'] . "',  '" . $data['whereHeard'] . "', '" . $data['whatRate'] . "',  '" . $anotherFormat . "', '" . $data['anotherFormatOther'] . "', '" . urlencode($data['comments']) . "', NOW(),  '" . $data['goDistance'] . "', '" . $data['goDistanceYes'] . "', '" . $data['labelSupport'] . "', '" . $data['labelSupportOther'] . "', '" . $data['howSupport'] . "', '" . $data['howSupportHowSoon'] . "', '" . $data['likeRecord'] . "', '" . $countryName . "', '" . $countryCode . "')");
         
         /* $query = DB::select('insert into tracks_reviews (`version`, `track`, `member`, `whereheard`,  `whatrate`,  `anotherformat`, `anotherformat_other`, `additionalcomments`, `added`, `godistance`, `godistanceyes`, `labelsupport`, `labelsupport_other`, `howsupport`, `howsupport_howsoon`, `likerecord`, `countryName`, `countryCode`) values (
             "2", 
             "' . $tid . '", 
             "' . $_SESSION['memberId'] . '",
             "' .$data['whereHeard'] . '",
             "' . $data['whatRate'] . '",
             "' . $anotherFormat . '",
             "' . $data['anotherFormatOther'] . '",
             "' .urlencode($data['comments']) . '",
             NOW(),
             "' . $data['goDistance'] . '",
             "' . $data['goDistanceYes'] . '",
             "' . $data['labelSupport'] .'",
             "' . $data['labelSupportOther'] . '",
             "' . $data['howSupport'] . '",
             "' . $data['howSupportHowSoon'] . '",
             "' . $data['likeRecord'] . '",
             "' . $countryName . '",
             "' . $countryCode . '")');
         */        $member_session_id = Session::get('memberId');

        // SECURITY FIX: Prevent duplicate reviews
        $existingReview = DB::table('tracks_reviews')
            ->where('track', $tid)
            ->where('member', $member_session_id)
            ->first();

        if ($existingReview) {
            return -1; // Already reviewed
        }

        // SECURITY FIX: Prevent self-reviews (DJs rating their own tracks)
        $track = DB::table('tracks')->where('id', $tid)->first();
        if (!$track) {
            return -2; // Track not found
        }

        // Check if this member is the track owner (client)
        $member = DB::table('members')->where('id', $member_session_id)->first();
        if ($member && isset($member->client_id) && $member->client_id == $track->client) {
            return -3; // Cannot review your own track
        }

        // SECURITY FIX: Validate input
        if (!isset($data['whatRate']) || !is_numeric($data['whatRate'])) {
            return -4; // Invalid rating
        }

        $rating = (int)$data['whatRate'];
        if ($rating < 1 || $rating > 5) {
            return -5; // Rating must be 1-5
        }

        if (!isset($data['comments'])) {
            $data['comments'] = '';
        }

        $comments = trim($data['comments']);
        if (strlen($comments) > 5000) {
            return -6; // Comment too long
        }

        $insertData = array(
            'version' => 2,
            'track' => $tid,
            'member' => $member_session_id,
            'whatrate' => $rating,
            'additionalcomments' => htmlspecialchars($comments, ENT_QUOTES, 'UTF-8'),
            'added' => NOW(),
            'countryName' => $countryName,
            'countryCode' => $countryCode,
        );

 
         $insertId = DB::table('tracks_reviews')->insertGetId($insertData);
         
         // $query = DB::select('insert into tracks_reviews (`version`, `track`, `member`,  `whatrate`, `additionalcomments`, `added`, `countryName`, `countryCode`) values (
         //     "2", 
         //     "' . $tid . '", 
         //     "' . $_SESSION['memberId'] . '",
         //     "' . $data['whatRate'] . '",
         //     "' .urlencode($data['comments']) . '",
         //     NOW(),
         //     "' . $countryName . '",
         //     "' . $countryCode . '")');
 
         // $insertId = $this->db->insert_id();
 
         // digi coins for review
 
         if ($insertId > 0) {
 
             // Check whether already downloaded or not
 
             $digi_coins = DB::select("SELECT member_digicoin_id FROM member_digicoins where member_id = ? and track_id = ? and type_id = '1'", [$member_session_id, $tid]);
 
             $digi_coins_numRows = count($digi_coins);
 
             if ($digi_coins_numRows < 1) {
 
                 // caliculate dj ponints for review
 
                 $query1 = DB::select("SELECT added FROM tracks where id = ?", [$tid]);
 
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
 
 
                 $digicoin_data = array(
                     'member_id' => $member_session_id,
                     'track_id' => $tid,
                     'type_id' => 1,
                     'points' => $points,
                     'date_time' =>  NOW(),
                 
                 );
         
                 $digicoin_id = DB::table('member_digicoins')->insertGetId($digicoin_data);
 
                 // DB::select("insert into member_digicoins (`member_id`, `track_id`, `type_id`, `points`, `date_time`) values ('" . $_SESSION['memberId'] . "', '" . $tid . "', '1', '" . $points . "', NOW())");
 
                 // $digicoin_id = $this->db->insert_id();
 
                 if ($digicoin_id > 0) {
 
                     $available_coins = DB::select("SELECT available_points FROM member_digicoins_available where member_id = ? order by member_digicoin_available_id desc", [$member_session_id]);
 
                     $available_coins_numRows = count($available_coins);
 
                     if ($available_coins_numRows > 0) {
 
                         $available_digicoins  = $available_coins;
 
                         $available_digicoins_increment = ($available_digicoins[0]->available_points) + $points;
 
                         DB::update("update member_digicoins_available set available_points = ?, latest_date_time = NOW() where member_id = ?", [$available_digicoins_increment, $member_session_id]);
                     } else {
 
 
                         $member_digicoins_available_data = array(
                             'member_id' => $member_session_id,
                             'available_points' => $points,
                             'latest_date_time' =>NOW(),
                         
                         );
                 
                         $member_digicoins_available_id = DB::table('member_digicoins_available')->insertGetId($member_digicoins_available_data);
 
                         // DB::select("insert into member_digicoins_available (`member_id`, `available_points`, `latest_date_time`) values ('" . $_SESSION['memberId'] . "', '" . $points . "', NOW())");
                     }
                 }
             }
         } // digi coins for review
 
         return $insertId;
     }
 
 
     function getReviewTracks($where, $sort, $start, $limit)
 
     {
        //  echo "SELECT * FROM  tracks $where order by $sort limit $start, $limit";
        //  die();
 
         $query = DB::select("SELECT * FROM  tracks $where order by $sort limit $start, $limit");
 
         $result['numRows'] = count($query);
 
         $result['data']  = $query;
 
         return $result;
     }
     
     
     
     function getmemtracks($memId){
		
		
        $query = DB::select("SELECT Distinct 'tracks.id', tracks.*, tracks_mp3s.location FROM  tracks inner join tracks_mp3s ON tracks.id = tracks_mp3s.track   where tracks.deleted = '0' AND tracks.active = '1'  AND tracks.status='publish' order by tracks.added DESC limit 0, 10");



        $result['numRows'] = count($query);

        $result['data']  = $query;

        return  $result;
    }
     
     
     
     
         function getReviewTracks1($where)
 
     {
        //  echo "SELECT * FROM  tracks $where order by $sort limit $start, $limit";
        //  die();
 
         $query = DB::select("SELECT * FROM  tracks $where");
 
         $result['numRows'] = count($query);
 
         $result['data']  = $query;
 
         return $result;
     }
 
     function getYouSelectedTracks_fem($start, $limit)
 
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
 
     function getLogos($where)
     {
         $query = DB::select("select id, url, company, img,pCloudFileID_logo from  logos $where");
         
         $result['numRows'] = count($query);
         $result['data']  = $query;
         return $result;
     }
 
     function addTrackPlay_fem($trackId, $memberId, $countryName, $countryCode)
 
     {
         $insert_data = array(
             'trackId' => $trackId,
             'memberId' => $memberId,
             'countryName' => $countryName,
             'countryCode' => $countryCode,
             'dateTime' =>  NOW(),
         
         );
 
         $insert_id = DB::table('track_member_play')->insertGetId($insert_data);
 
         // $query = DB::select("insert into track_member_play (`trackId`, `memberId`, `countryName`, `countryCode`, `dateTime`) values ('" . $trackId . "', '" . $memberId . "', '" . $countryName . "', '" . $countryCode . "', NOW())");
 
         return $insert_id;
     }
 
     function getNumDashboardTracks_fem($where, $sort)
 
     {  
     if($sort == 'album ASC' || $sort == 'album DESC') {    
    //   $query = DB::select("SELECT  albums.*, albums.id AS album_id, tracks.artist, tracks.album,tracks.producer, tracks.added AS track_added, tracks.id, tracks.imgpage, tracks.label 
    //                              FROM tracks_album AS albums INNER JOIN tracks AS tracks ON albums.id = tracks.albumid 
    //                              WHERE albums.deleted = '0' AND tracks.deleted = '0' AND tracks.active = '1' GROUP BY tracks.title");  // GROUP BY tracks.title,tracks.artist
        
        
             $query =  DB::select("SELECT albums.*, albums.id AS album_id, tracks.artist, tracks.album,tracks.producer, tracks.added AS track_added, tracks.id, tracks.imgpage, tracks.label
             ,tracks.pCloudFileID,tracks.pCloudParentFolderID FROM tracks_album AS albums LEFT JOIN tracks AS tracks ON albums.id = tracks.albumid
             LEFT JOIN tracks_mp3s on tracks.id = tracks_mp3s.track $where GROUP BY tracks.title");

                                 
                                 
      } else {
         $query = DB::select("SELECT DISTINCT tracks_mp3s.track, tracks.id 
                                     FROM tracks
                                     LEFT JOIN tracks_mp3s ON tracks.id = tracks_mp3s.track 
                                     LEFT JOIN genres ON genres.genreId = tracks.genreId 
                                     $where GROUP BY tracks.title ORDER BY $sort"); // GROUP BY tracks.title,tracks.artist 
 
         // $query = DB::select("SELECT DISTINCT tracks.title
         // FROM tracks
         // LEFT JOIN tracks_mp3s ON tracks.id = tracks_mp3s.track 
         // LEFT JOIN genres ON genres.genreId = tracks.genreId 
         // $where GROUP BY tracks.title ORDER BY $sort"); // GROUP BY tracks.title,tracks.artist 
      }
      
      // echo count($query);die();
         return count($query);
     }
 
     function getTopPriorityDashboardTracks($where, $sort, $start, $limit){

      
      
      
          //    $query = DB::select("select clients.uname, clients.name, tracks.id, tracks.artist, tracks.title, tracks.album, tracks.time, tracks.label, tracks.added, tracks.img, tracks.imgpage from  tracks 
          //    left join clients on tracks.client = clients.id where tracks.priority=1");
      
          $query = DB::select("select clients.uname, clients.name  , members.fname, tracks.id, tracks.artist, tracks.title, tracks.album, tracks.time, tracks.label, tracks.added, tracks.img, tracks.imgpage, tracks_album.album_page_image,tracks.pCloudFileID,tracks.pCloudParentFolderID, tracks.albumType  from  tracks 
          left join clients on tracks.client = clients.id left join members on tracks.member = members.id  left join tracks_album on tracks.albumid = tracks_album.id $where order by $sort limit $start, $limit");
             $result['numRows'] = count($query);
             $result['data']  = $query;
             
             $result['downloaded']=array();
             $member_session_id = Session::get('memberId');
             if(isset($member_session_id) && $member_session_id>0 ){
                 
                 $track_ids= array();
                 foreach ($result['data'] as $track) {
                     $track_ids[]= $track->id;
                 }
                 if(!empty($track_ids)){
                 $query = DB::select("SELECT Distinct trackId FROM track_member_downloads WHERE trackId IN (".implode(",",$track_ids).")  AND memberId=".$member_session_id);
                 $track_ids= array();
                 foreach ($query as $t) {
                     $track_ids[]=$t->trackId;
                 }
                 $result['downloaded']  = $track_ids;
              }
             }
            //  echo '<pre>';
            //  print_r($result);
            //  echo '</pre>';
            //  die();
             return $result;
         }
 
     function getClientSocialInfo_fem($clientId)
     {
 
         $query = DB::select("SELECT * FROM  client_social_media where clientId = '$clientId'");
 
         $result['numRows'] = count($query);
 
         $result['data']  = $query;
 
         return $result;
     }
     
//   function getMemberSocialInfo_fem($mem_id)
//      {
 
//          $query = DB::select("SELECT * FROM  member_social_media where memberId = '$mem_id'");
 
//          $result['numRows'] = count($query);
 
//          $result['data']  = $query;
 
//          return $result;
//      }
     
     function getMemberDetails($memberId)
     {
         $result['id'] = 0;
         $query = DB::select("SELECT id, uname, fname,age FROM members where id = ?", [$memberId]);
         $numRows = count($query);
         $data  = $query;
         if ($numRows > 0) {
             $result['id'] = $data[0]->id;
             $result['uname'] = $data[0]->uname;
             $result['fname'] = $data[0]->fname;
             $result['age'] = $data[0]->age;
         }
 
         $query1 = DB::select("SELECT image FROM member_images where memberId = ? order by imageId desc limit 1", [$memberId]);
         $numRows1 = count($query1);
         $data1  = $query1;
         if ($numRows1 > 0) {
             $result['image'] = $data1[0]->image;
         } else {
             $result['image'] = '';
         }
         return $result;
     }
 
     public function updateMemberAge($memberId, $age = null)
     {
         return $this->db->update('members', ['age' => $age], ['id' => $memberId]);
     }
 
 
     function getTopStreamingDashboardTracks($where, $sort, $start, $limit)

     {
  
        $query = DB::select("select clients.uname, clients.name, tracks.id, tracks.artist, tracks.title, tracks.album, tracks.time, tracks.label, tracks.added, tracks.img, tracks.imgpage, tracks_album.album_page_image,tracks.pCloudFileID,tracks.pCloudParentFolderID, tracks.albumType from  tracks 
        left join clients on tracks.client = clients.id right join top_streaming_tracks on tracks.id = top_streaming_tracks.trackId
        left join tracks_album on tracks.albumid = tracks_album.id 
         $where order by top_streaming_tracks.position limit $start, $limit");
  
          // $query = DB::select("select clients.uname, clients.name, tracks.id, tracks.artist, tracks.title, tracks.album, tracks.time, tracks.label, tracks.added, tracks.img, tracks.imgpage from  tracks 
          // left join clients on tracks.client = clients.id right join top_streaming_tracks on tracks.id = top_streaming_tracks.trackId
          // order by top_streaming_tracks.position,top_streaming_tracks.created_at ASC ");
  
         //         echo "SELECT distinct tracks_mp3s.track, tracks_mp3s.bpm, tracks.id, tracks.artist, tracks.title, tracks.album, tracks.time, tracks.label, tracks.img, tracks.imgpage, tracks.added, tracks.bpm, tracks.client FROM  tracks
  
         //   left join tracks_mp3s on tracks.id = tracks_mp3s.track
  
         //   $where order by $sort limit $start, $limit"; exit;
  
         $result['numRows'] = count($query);
  
         $result['data']  = $query;
  
        // dd($result);
         
         
         
         
         $result['downloaded']=array();
         $member_session_id = Session::get('memberId');
        //  if(isset($member_session_id) && $member_session_id>0 ){
             
        //      $track_ids= array();
        //      foreach ($result['data'] as $track) {
  
        //          if(empty($track->id)){
  
        //              continue;
        //          }
        //          else{
        //              $track_ids[]= $track->id;
  
        //          }
                
        //      }
  
        //   //  dd($track_ids);
        //   $result['downloaded']  = '';
  
        //   if(!empty($track_ids)){
        //      $query = DB::select("SELECT Distinct trackId FROM track_member_downloads WHERE trackId IN (".implode(",",$track_ids).")  AND memberId=".$member_session_id);
        //      $track_ids1= array();
        //      foreach ($query as $t) {
        //          $track_ids1[]=$t->trackId;
        //      }
        //      $result['downloaded']  = $track_ids1;
        //   } 
        //  }
         if(isset($member_session_id) && $member_session_id>0 ){
             
             $track_ids= array();
             foreach ($result['data'] as $track) {
 
                 if(empty($track->id)){
 
                     continue;
                 }
                 else{
                     $track_ids[]= $track->id;
 
                 }
                
             }
             
             if(!empty($track_ids) && $track_ids!=''){
                 $placeholders = implode(',', array_fill(0, count($track_ids), '?'));
                 $query =  DB::select("SELECT Distinct trackId FROM track_member_downloads WHERE trackId IN (".$placeholders.")  AND memberId=?", array_merge($track_ids, [$member_session_id]));
                 $track_ids= array();
                 foreach ($query as $t) {
                     $track_ids[]=$t->trackId;
                 }
             }
             $result['downloaded']  = $track_ids;
         }
         
         
         
         
  
         return $result;
     }
 
     
     function getDashboardTracks_fem($where, $sort, $start, $limit)
 
     {
        // echo $where;die();
       
      if($sort == 'album ASC' || $sort == 'album DESC') {    
       //  if($sort == 'album ASC') { $added = 'added DESC'; } elseif($sort == 'album ASC') { $added = 'added ASC'; } else { }    
    //   echo $where;die();
       
       //$query =  DB::select("SELECT albums.*, albums.id AS album_id, tracks.artist, tracks.album,tracks.producer, tracks.added AS track_added, tracks.id, tracks.imgpage, tracks.label ,tracks.pCloudFileID,tracks.pCloudParentFolderID FROM tracks_album AS albums INNER JOIN tracks AS tracks ON albums.id = tracks.albumid WHERE albums.deleted = '0' AND tracks.deleted = '0' AND tracks.active = '1' GROUP BY tracks.title,tracks.artist ORDER BY  $sort LIMIT $start, $limit");
     $query =  DB::select("SELECT albums.*, albums.id AS album_id, tracks.artist, tracks.album,tracks.producer, tracks.added AS track_added, tracks.id, tracks.imgpage, tracks.label ,tracks.pCloudFileID,tracks.pCloudParentFolderID,tracks.albumType FROM tracks_album AS albums LEFT JOIN tracks AS tracks ON albums.id = tracks.albumid LEFT JOIN tracks_mp3s on tracks.id = tracks_mp3s.track $where GROUP BY tracks.title,tracks.artist ORDER BY  $sort LIMIT $start, $limit");

    //   ORDER BY tracks.priority DESC, added DESC
      } else {
 
         // $query =  DB::select("SELECT DISTINCT genres.genre, tracks_mp3s.track, tracks_mp3s.bpm, tracks.id, tracks.artist, tracks.producer, tracks.title, tracks.album, tracks.time, tracks.label, tracks.img, tracks.imgpage, tracks.added, tracks.bpm, tracks.client 
         //                             FROM tracks
         //                             LEFT JOIN tracks_mp3s on tracks.id = tracks_mp3s.track
         //                             LEFT JOIN genres on tracks.genreId = genres.genreId
         //                             $where ORDER BY tracks.priority DESC, $sort LIMIT $start, $limit");
            
 
         $query =  DB::select("SELECT DISTINCT genres.genre, tracks_mp3s.track, tracks_mp3s.bpm, tracks.id, tracks.artist, tracks.producer, tracks.title, tracks.album, tracks.time, tracks.label, tracks.img, tracks.imgpage, tracks.added, tracks.bpm, tracks.client,tracks.member, tracks_album.album_page_image , tracks.order_position , tracks.pCloudFileID,tracks.pCloudParentFolderID,tracks.priority,tracks.albumType
                                     FROM tracks
                                     LEFT JOIN tracks_mp3s on tracks.id = tracks_mp3s.track
                                     LEFT JOIN genres on tracks.genreId = genres.genreId
                                     LEFT JOIN tracks_album on tracks.albumid = tracks_album.id
                                     $where GROUP BY tracks.title,tracks.artist ORDER BY  $sort LIMIT $start, $limit");
       }
            //       echo "SELECT distinct tracks_mp3s.track, tracks_mp3s.bpm, tracks.id, tracks.artist, tracks.title, tracks.album, tracks.time, tracks.label, tracks.img, tracks.imgpage, tracks.added, tracks.bpm, tracks.client FROM  tracks
 
            // left join tracks_mp3s on tracks.id = tracks_mp3s.track
 
            // $where order by $sort limit $start, $limit"; exit;
 
         $result['numRows'] = count($query);
         
     
         $result['data']  = $query;
        //  echo'<pre>';
        //  print_r($query);
        //  echo '</pre>';
        //  die();
         
         
         
         
         $result['downloaded']=array();
         $member_session_id = Session::get('memberId');
         if(isset($member_session_id) && $member_session_id>0 ){
             
             $track_ids= array();
             foreach ($result['data'] as $track) {
 
                 if(empty($track->id)){
 
                     continue;
                 }
                 else{
                     $track_ids[]= $track->id;
 
                 }
                
             }
             
             if(!empty($track_ids) && $track_ids!=''){
                 $placeholders = implode(',', array_fill(0, count($track_ids), '?'));
                 $query =  DB::select("SELECT Distinct trackId FROM track_member_downloads WHERE trackId IN (".$placeholders.")  AND memberId=?", array_merge($track_ids, [$member_session_id]));
                 $track_ids= array();
                 foreach ($query as $t) {
                     $track_ids[]=$t->trackId;
                 }
             }
             $result['downloaded']  = $track_ids;
         }
         
         
 
         return $result;
     }
 
 
     function getConversation($memberId, $clientId)
 
     {
 
         $query =  DB::select("SELECT * FROM chat_messages where
 
    (senderType = '2' AND senderId = '" . $memberId . "' AND receiverType = '1' AND receiverId = '" . $clientId . "')
 
    OR
 
    (senderType = '1' AND senderId = '" . $clientId . "' AND receiverType = '2' AND receiverId = '" . $memberId . "')
 
    order by messageId desc");
 
         $result['numRows'] = count($query);
 
         $result['data']  = $query;
 
         return $result;
     }
 
 
     function isMemberStarred($memberId, $messageId)
 
     {
 
         $query =  DB::select("SELECT autoId FROM chat_messages_starred where userType = '2' and userId = ? and messageId = ?", [$memberId, $messageId]);
 
         return count($query);
     }
 
 
     function isMemberArchived($memberId, $messageId)
 
     {
 
         $query =  DB::select("SELECT autoId FROM chat_messages_archived where userType = '2' and userId = ? and messageId = ?", [$memberId, $messageId]);
 
         return count($query);
     }
 
 
     function makeMemberMsgRead($clientId, $memberId)
 
     {
 
         $query =  DB::update("update chat_messages set unread = '1' where (receiverType = '2' AND receiverId = ?) AND (senderType = '1' AND senderId = ?) AND unread = '0'", [$memberId, $clientId]);
 
         return $query;
     }
 
     function sendMemberMessage($memberId, $cid, $message)
 
     {
 
/*          $query =  DB::select("update chat_messages set latest = '1' where
 
   (senderType = '2' AND senderId = '" . $memberId . "' AND receiverType = '1' AND receiverId = '" . $cid . "')
 
    OR
 
   (senderType = '1' AND senderId = '" . $cid . "' AND receiverType = '2' AND receiverId = '" . $memberId . "')"); */
   
		$query1 =  DB::select("SELECT * FROM chat_messages where senderType = '2' AND senderId = ? AND receiverType = '1' AND receiverId = ?", [$memberId, $cid]);

		$query2 =  DB::select("SELECT * FROM chat_messages where senderType = '1' AND senderId = ? AND receiverType = '2' AND receiverId = ?", [$cid, $memberId]);
		
		$updateData = array(
			'latest' => '1'
		);
		
       $numRows1 = count($query1);
	   
       $numRows2 = count($query2);

       if ($numRows1 > 0) {
			$resQry = DB::table('chat_messages')
			->where('senderType', 2)  
			->where('senderId', $memberId)  
			->where('receiverType', 1)  
			->where('receiverId', $cid)  
			->limit(1)  // optional - to ensure only one record is updated.
			->update($updateData);  // update the record in the DB.
	   }
	   
	   if ($numRows2 > 0) {
			$resQry = DB::table('chat_messages')
			->where('senderType', 1)  
			->where('senderId', $cid)  
			->where('receiverType', 2)  
			->where('receiverId', $memberId)  
			->limit(1)  // optional - to ensure only one record is updated.
			->update($updateData);  // update the record in the DB.
	   }
 
        $insert_data = array(
            'senderType' => 2,
            'senderId' => $memberId,
            'receiverType' => 1,
            'receiverId' => $cid,
            'message' => htmlspecialchars($message, ENT_QUOTES, 'UTF-8'),  // XSS protection
            'dateTime' =>  date("Y-m-d H:m:s"),
        
        );
		$insertId = DB::table('chat_messages')->insertGetId($insert_data);
		
         return $insertId;
     }
 
     function archiveMemberMessage($memberId, $messageId)
 
     {
 
         $query =  DB::select("SELECT autoId FROM chat_messages_archived where userType = '2' and userId = ? and messageId = ?", [$memberId, $messageId]);

       $numRows = count($query);

       if ($numRows > 0) {

           //$query1 =  DB::select("delete FROM  chat_messages_archived where userType = '2' and userId = '" . $memberId . "' and messageId = '" . $messageId . "'");
           $query1 = DB::table('chat_messages_archived')->where('userType', '2')->where('userId', $memberId)->where('messageId', $messageId)->delete();

           $result['result'] = $query1;

           $result['transaction'] = 0;
       } else {


        $insert_data = array(
            'userType' => 2,
            'userId' => $memberId,
            'messageId' => $messageId,
        
        );
 
        $insertId = DB::table('chat_messages_archived')->insertGetId($insert_data);
		
		$result['result'] = $insertId;

        $result['transaction'] = 1;
	   }
         return $result;
     }
 
 
     function starMemberMessage($memberId, $messageId)
 
     {
		//pArr("SELECT autoId FROM chat_messages_starred where userType = '2' and userId = '" . $memberId . "' and messageId = '" . $messageId . "'");die();
         $query =  DB::select("SELECT autoId FROM chat_messages_starred where userType = '2' and userId = ? and messageId = ?", [$memberId, $messageId]);

       $numRows = count($query);

       if ($numRows > 0) {

           //$query1 =  DB::select("delete FROM  chat_messages_starred where userType = '2' and userId = '" . $memberId . "' and messageId = '" . $messageId . "'");
		   
		   $query1 = DB::table('chat_messages_starred')->where('userType', '2')->where('userId', $memberId)->where('messageId', $messageId)->delete();

           $result['result'] = $query1;

           $result['transaction'] = 0;
       } else {

        $insert_data = array(
            'userType' => 2,
            'userId' => $memberId,
            'messageId' => $messageId,
        
        );
 
        $insertId = DB::table('chat_messages_starred')->insertGetId($insert_data);

        //    $query2 =  DB::select("insert into chat_messages_starred (`userType`, `userId`, `messageId`) values ('2', '" . $memberId . "', '" . $messageId . "')");

        //    $result['result'] = $this->db->insert_id();

        $result['result'] = $insertId;

        $result['transaction'] = 1;
       }
		//pArr($result);die();
         return $result;
     }
 
     function getClientInfo_fem($clientId)
 
     {
 
         $query =  DB::select("SELECT * FROM  clients where id = '$clientId'");
 
         $result['numRows'] = count($query);
 
         $result['data']  = $query;
 
         return $result;
     }
 
     function getClientImage_fem($clientId)
 
     {
 
         $query =  DB::select("SELECT * FROM  client_images where clientId = '$clientId' order by imageId desc limit 1");
 
         $result['numRows'] = count($query);
 
         $result['data']  = $query;
 
         return $result;
     }
 
     function confirmMemberCurrentPassword($password)

     {
         $memberId_from_session = Session::get('memberId');

         // SECURITY FIX: Use proper password verification that handles both MD5 and bcrypt
         // Fetch the stored password hash
         $user = DB::table('members')->where('id', $memberId_from_session)->first();

         if (!$user) {
             return 0;
         }

         // Use the migration helper to verify and upgrade if needed
         $isValid = \App\Helpers\PasswordMigrationHelper::verifyAndUpgrade(
             $password,
             $user->pword,
             'members',
             $memberId_from_session
         );

         return $isValid ? 1 : 0;
     }
 
 
     function updateMemberPassword($password)
 
     {   
         $memberId_from_session = Session::get('memberId');
 
         // $query = DB::table('members')
         //                 ->where('id', $memberId_from_session)
         //                 ->update(['pword' => bcrypt($password)]);
 
         // SECURITY FIX: Use bcrypt instead of MD5
        $password = \App\Helpers\PasswordMigrationHelper::hashPassword($password);
         $query = DB::update("update members set pword = ? where id = ?", [$password, $memberId_from_session]);
 
         return $query;
     }
 
     function getMemberInfo_fem($memberId)
     {
 
         $query = DB::select("SELECT * FROM  members
 
     left join members_dj_mixer on members.id = members_dj_mixer.member
 
    where members.id = '$memberId'");
 
         $result['numRows'] = count($query);
 
         $result['data']  = $query;
 
         return $result;
     }
 
 
     function addMemberImage($image, $memberId,$pcloudFileId,$parentfolderid)
 
     {
 
         $insert_data = array(
             'memberId' => $memberId,
             'image' => $image,
             'dateTime' =>  NOW(),
             'pCloudFileID_mem_image' =>$pcloudFileId,
             'pCloudParentFolderID_mem_image'=>$parentfolderid
         
         );
 
         $insert_id = DB::table('member_images')->insertGetId($insert_data);
 
         // $query = DB::select("INSERT INTO `member_images` (`memberId`, `image`, `dateTime`) VALUES ('" . $memberId . "', '" . $image . "', NOW())");
         // $insertId = $this->db->insert_id();
 
         return  $insert_id;
     }
 
     function updateMemberInfo($data, $memberId)
     {
        // echo "<pre>";
        // print_r($data);
        // echo "</pre>";
        // die;
        extract($data);
        $originalDateString = $data['dob'];
        $timestamp = strtotime($originalDateString);
        $formattedDate = date('Y-m-d', $timestamp);

        $data['dob'] = $formattedDate;

         if (!(isset($dj_mixer))) {
             $dj_mixer = 0;
         }
         if (!(isset($radio_station))) {
             $radio_station = 0;
         }
         if (!(isset($mass_media))) {
             $mass_media = 0;
         }
         if (!(isset($record_label))) {
             $record_label = 0;
         }
         if (!(isset($management))) {
             $management = 0;
         }
         if (!(isset($clothing_apparel))) {
             $clothing_apparel = 0;
         }
         if (!(isset($promoter))) {
             $promoter = 0;
         }
         if (!(isset($special_services))) {
             $special_services = 0;
         }
         if (!(isset($production_talent))) {
             $production_talent = 0;
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
 
         if(!empty($player)){
 
             $player = $player;
             
         }
         else{
 
             $player = '';
         }
 
         if(!empty($computer)){
 
             $computer = $computer;
             
         }
         else{
 
             $computer = '';
         }
 
         if(!empty($mixerType)){
 
             $mixerType = $mixerType;
             
         }
         else{
 
             $mixerType = '';
         }
 
         if(!empty($headphones)){
 
             $headphones = $headphones;
             
         }
         else{
 
             $headphones = '';
         }
 
 
         if(!empty($gameSystem)){
 
             $gameSystem = $gameSystem;
             
         }
         else{
 
             $gameSystem = '';
         }
 
         if(!empty($hatSize)){
 
             $hatSize = $hatSize;
             
         }
         else{
 
             $hatSize = '';
         }
 
         if(!empty($pantsSize)){
 
             $pantsSize = $pantsSize;
             
         }
         else{
 
             $pantsSize = '';
         }
 
         if(!empty($turntablesType)){
 
             $turntablesType = $turntablesType;
             
         }
         else{
 
             $turntablesType = '';
         }
 
         if(!empty($needlesType)){
 
             $needlesType = $needlesType;
             
         }
         else{
 
             $needlesType = '';
         }
 
         if(!empty($cellPhone)){
 
             $cellPhone = $cellPhone;
             
         }
         else{
 
             $cellPhone = '';
         }
 
         if(!empty($shirtSize)){
 
             $shirtSize = $shirtSize;
             
         }
         else{
 
             $shirtSize = '';
         }
 
         if(!empty($shoeSize)){
 
             $shoeSize = $shoeSize;
             
         }
         else{
 
             $shoeSize = '';
         }
 
         if(!empty($audioQuality)){
 
             $audioQuality = $audioQuality;
             
         }
         else{
 
             $audioQuality = '';
         }
 
         if (strpos($firstName, "'") !== false)    $firstName = str_replace("'", "\'", $firstName);
         if (strpos($lastName, "'") !== false)    $lastName = str_replace("'", "\'", $lastName);
         if (strpos($stageName, "'") !== false)    $stageName = str_replace("'", "\'", $stageName);
         $my_check=  DB::table('member_social_media')->where('memberId', '=', $memberId)->first();

        if(!empty($my_check)){
             $result = DB::select("update `members` set fname = '$firstName', lname = '$lastName', stagename = '$stageName', dob = '$formattedDate', email = '$email', address1 = '$address1', address2 = '$address2', city = '$city', state = '$state', country = '$country', zip = '$zip', phone = '$phone', player = '" . urlencode($player) . "', dj_mixer = '$dj_mixer', radio_station = '$radio_station', mass_media = '$mass_media', record_label = '$record_label', management = '$management', clothing_apparel = '$clothing_apparel', promoter = '$promoter', special_services = '$special_services', production_talent = '$production_talent', computer = '$computer', mixer_type = '$mixerType', headphones = '$headphones', game_system = '$gameSystem', hat_size = '$hatSize', pants_size = '$pantsSize', turntables_type = '$turntablesType', needles_type = '$needlesType', cell_phone = '$cellPhone', shirt_size = '$shirtSize', shoe_size = '$shoeSize', audioQuality = '$audioQuality', howheard = '$howheard', howheardvalue = '$howheardvalue' where id = '" . $memberId . "'");
     
             DB::select("update `member_social_media` set facebook = '" . $facebook . "', twitter = '" . $twitter . "', instagram = '" . $instagram . "', linkedin = '" . $linkedin . "' where memberId = '" . $memberId . "'");
        }
        else{
            $values = array('facebook' => $facebook,'twitter' => $twitter,'instagram'=>$instagram,'linkedin'=>$linkedin,'memberId'=>$memberId);
            // DB::table('member_social_media')->insert($values);
             $result = DB::select("update `members` set fname = '$firstName', lname = '$lastName', stagename = '$stageName', dob = '$formattedDate', email = '$email', address1 = '$address1', address2 = '$address2', city = '$city', state = '$state', country = '$country', zip = '$zip', phone = '$phone', player = '" . urlencode($player) . "', dj_mixer = '$dj_mixer', radio_station = '$radio_station', mass_media = '$mass_media', record_label = '$record_label', management = '$management', clothing_apparel = '$clothing_apparel', promoter = '$promoter', special_services = '$special_services', production_talent = '$production_talent', computer = '$computer', mixer_type = '$mixerType', headphones = '$headphones', game_system = '$gameSystem', hat_size = '$hatSize', pants_size = '$pantsSize', turntables_type = '$turntablesType', needles_type = '$needlesType', cell_phone = '$cellPhone', shirt_size = '$shirtSize', shoe_size = '$shoeSize', audioQuality = '$audioQuality', howheard = '$howheard', howheardvalue = '$howheardvalue' where id = '" . $memberId . "'");
            DB::table('member_social_media')->insert($values);
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

         $result = DB::select("UPDATE members_dj_mixer 
                                     SET djtype_commercialreporting = '" . (int)$djtype_commercialreporting. "', 
                                         djtype_commercialnonreporting = '" . (int)$djtype_commercialnonreporting. "', 
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
         
 
         $managementQuery = DB::select("SELECT id FROM members_radio_station where member = '" . $memberId . "'");
 
         $managementRows = count($managementQuery);
 
         if ($managementRows > 0) {
 
             DB::select("UPDATE members_radio_station 
                             SET radiotype_musicdirector = " . (int) $radioMusic . ", 
                                 radiotype_programdirector = " . (int) $radioProgram . ", 
                                 radiotype_jock = " . (int) $radioAir . ", 
                                 radiotype_promotion = " . (int) $radioPromotion . ", 
                                 radiotype_production = " . (int) $radioProduction . ", 
                                 radiotype_sales = " . (int) $radioSales . ", 
                                 radiotype_tech = " . (int) $radioIt . ",
                                 stationcallletters = '" . addslashes($stationCall) . "',
                                 stationfrequency = '" . addslashes($stationFrequency) . "', 
                                 stationname = '" . addslashes($stationName) . "',
                                 programdirector_stationcallletters = '" . addslashes($programCall) . "',
                                 programdirector_host = '" . addslashes($programHost) . "', 
                                 programdirector_showname = '" . addslashes($programName) . "', 
                                 programdirector_showtime = '" . addslashes($programTime) . "',
                                 programdirector_monday = " . (int) $programMonday . ",
                                 programdirector_tuesday = " . (int) $programTuesday . ",
                                 programdirector_wednesday = " . (int) $programWednesday . ", 
                                 programdirector_thursday = " . (int) $programThursday . ", 
                                 programdirector_friday = " . (int) $programFriday . ", 
                                 programdirector_saturday = " . (int) $programSaturday . ",
                                 programdirector_sunday = " . (int) $programSunday . ", 
                                 programdirector_varies = " . (int) $programVaries . ",
                                 musicdirector_stationcallletters = '" . addslashes($musicCall) . "',
                                 musicdirector_host = '" . addslashes($musicHost) . "',
                                 musicdirector_showname = '" . addslashes($musicName) . "',
                                 musicdirector_showtime = '" . addslashes($musicTime) . "',
                                 musicdirector_monday = " . (int) $musicMonday . ",
                                 musicdirector_tuesday = " . (int) $musicTuesday . ", 
                                 musicdirector_wednesday = " . (int) $musicWednesday . ",
                                 musicdirector_thursday = " . (int) $musicThursday . ", 
                                 musicdirector_friday = " . (int) $musicFriday . ", 
                                 musicdirector_saturday = " . (int) $musicSaturday . ",
                                 musicdirector_sunday = " . (int) $musicSunday . ",
                                 musicdirector_varies = " . (int) $musicVaries . ",
                                 onairpersonality_showname = '" . addslashes($airName) . "',
                                 onairpersonality_showtime = '" . addslashes($airTime) . "', 
                                 onairpersonality_monday = " . (int) $airMonday . ",
                                 onairpersonality_tuesday = " . (int) $airTuesday . ", 
                                 onairpersonality_wednesday = " . (int) $airWednesday . ", 
                                 onairpersonality_thursday = " . (int) $airThursday . ",
                                 onairpersonality_friday = " . (int) $airFriday . ", 
                                 onairpersonality_saturday = " . (int) $airSaturday . ", 
                                 onairpersonality_sunday = " . (int) $airSunday . ", 
                                 onairpersonality_varies = " . (int) $airVaries . "
                                 WHERE member = '" . (int) $memberId . "'");
         } else {
 
             DB::select("INSERT INTO `members_radio_station` (`member`, `radiotype_musicdirector`, `radiotype_programdirector`, `radiotype_jock`, `radiotype_promotion`, `radiotype_production`, `radiotype_sales`, `radiotype_tech`, `stationcallletters`, `stationfrequency`, `stationname`, `programdirector_stationcallletters`, `programdirector_host`, `programdirector_showname`, `programdirector_showtime`, `programdirector_monday`, `programdirector_tuesday`, `programdirector_wednesday`, `programdirector_thursday`, `programdirector_friday`, `programdirector_saturday`, `programdirector_sunday`, `programdirector_varies`, `musicdirector_stationcallletters`, `musicdirector_host`, `musicdirector_showname`, `musicdirector_showtime`, `musicdirector_monday`, `musicdirector_tuesday`, `musicdirector_wednesday`, `musicdirector_thursday`, `musicdirector_friday`, `musicdirector_saturday`, `musicdirector_sunday`, `musicdirector_varies`, `onairpersonality_showname`, `onairpersonality_showtime`, `onairpersonality_monday`, `onairpersonality_tuesday`, `onairpersonality_wednesday`, `onairpersonality_thursday`, `onairpersonality_friday`, `onairpersonality_saturday`, `onairpersonality_sunday`, `onairpersonality_varies`) 
                 VALUES (
                     '" . (int) $memberId . "',
                     " . (int) $radioMusic . ",
                     " . (int) $radioProgram . ",
                     " . (int) $radioAir . ",
                     " . (int) $radioPromotion . ",
                     " . (int) $radioProduction . ",
                     " . (int) $radioSales . ",
                     " . (int) $radioIt . ",
                     '" . addslashes($stationCall) . "',
                     '" . addslashes($stationFrequency) . "',
                     '" . addslashes($stationName) . "',
                     '" . addslashes($programCall) . "',
                     '" . addslashes($programHost) . "',
                     '" . addslashes($programName) . "',
                     '" . addslashes($programTime) . "',
                     " . (int) $programMonday . ",
                     " . (int) $programTuesday . ",
                     " . (int) $programWednesday . ",
                     " . (int) $programThursday . ",
                     " . (int) $programFriday . ",
                     " . (int) $programSaturday . ",
                     " . (int) $programSunday . ",
                     " . (int) $programVaries . ",
                     '" . addslashes($musicCall) . "',
                     '" . addslashes($musicHost) . "',
                     '" . addslashes($musicName) . "',
                     '" . addslashes($musicTime) . "',
                     " . (int) $musicMonday . ",
                     " . (int) $musicTuesday . ",
                     " . (int) $musicWednesday . ",
                     " . (int) $musicThursday . ",
                     " . (int) $musicFriday . ",
                     " . (int) $musicSaturday . ",
                     " . (int) $musicSunday . ",
                     " . (int) $musicVaries . ",
                     '" . addslashes($airName) . "',
                     '" . addslashes($airTime) . "',
                     " . (int) $airMonday . ",
                     " . (int) $airTuesday . ",
                     " . (int) $airWednesday . ",
                     " . (int) $airThursday . ",
                     " . (int) $airFriday . ",
                     " . (int) $airSaturday . ",
                     " . (int) $airSunday . ",
                     " . (int) $airVaries . ")");
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
 
         $managementQuery = DB::select("SELECT id FROM members_mass_media where member = '" . $memberId . "'");
 
         $managementRows = count($managementQuery);
 
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
 
         $managementQuery = DB::select("SELECT id FROM members_record_label where member = '" . $memberId . "'");
 
         $managementRows = count($managementQuery);
 
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
 
         $managementQuery = DB::select("SELECT id FROM members_management where member = '" . $memberId . "'");
 
         $managementRows = count($managementQuery);
 
         if ($managementRows > 0) {
 
             DB::select("update members_management set managementtype_artist = '$managementArtist', managementtype_tour = '$managementTour', managementtype_personal = '$managementPersonal', managementtype_finance = '$managementFinance', management_name = '$managementName', management_who = '$managementWho', management_industry = '$managementIndustry' where member = '" . $memberId . "'");
         } else {
 
             DB::select("insert into `members_management` (`member`, `managementtype_artist`, `managementtype_tour`, `managementtype_personal`, `managementtype_finance`, `management_name`, `management_who`, `management_industry`) values ('" . $memberId . "', '" . $managementArtist . "', '" . $managementTour . "', '" . $managementPersonal . "', '" . $managementFinance . "', '" . $managementName . "', '" . $managementWho . "', '" . $managementIndustry . "')");
         }
 
         // clothing
 
         $clothingQuery = DB::select("SELECT id FROM members_clothing_apparel where member = '" . $memberId . "'");
 
         $clothingRows = count($clothingQuery);
 
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
 
         $promoterQuery = DB::select("SELECT id FROM members_promoter where member = '" . $memberId . "'");
 
         $promoterRows = count($promoterQuery);
 
         if ($promoterRows > 0) {
 
             DB::select("update members_promoter set promotertype_indy = '$promoterIndy', promotertype_club = '$promoterClub', promotertype_event = '$promoterSpecial', promotertype_street = '$promoterStreet', promoter_name = '$promoterName', promoter_department = '$promoterDepartment', promoter_website = '$promoterWebsite' where member = '" . $memberId . "'");
         } else {
 
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
 
         $specialQuery = DB::select("SELECT id FROM members_special_services where member = '" . $memberId . "'");
 
         $specialRows = count($specialQuery);
 
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
 
         $productionQuery = DB::select("SELECT id FROM members_production_talent where member = '" . $memberId . "'");
 
         $productionRows = count($productionQuery);
 
         if ($productionRows > 0) {
 
             DB::select("update members_production_talent set productiontype_artist = '$productionArtist', productiontype_producer = '$productionProducer', productiontype_choreographer = '$productionChoregrapher', productiontype_sound = '$productionSound', production_name = '$productionName' where member = '" . $memberId . "'");
         } else {
 
             DB::select("insert into `members_production_talent` (`member`, `productiontype_artist`, `productiontype_producer`, `productiontype_choreographer`, `productiontype_sound`, `production_name`) values ('" . $memberId . "', '" . $productionArtist . "', '" . $productionProducer . "', '" . $productionChoregrapher . "', '" . $productionSound . "', '" . $productionName . "')");
         }
 
         return $result;
     }
 
     function getMemberProductionInfo_fem($memberId)
 
     {
 
         $query = DB::select("SELECT productiontype_artist, productiontype_producer, productiontype_choreographer, productiontype_sound, production_name FROM  members_production_talent where member = '$memberId'");
 
         $result['numRows'] = count($query);
 
         $result['data']  = $query;
 
         return $result;
     }
 
 
     function getMemberSpecialInfo_fem($memberId)
 
     {
 
         $query = DB::select("SELECT servicestype_corporate, servicestype_graphicdesign, servicestype_webdesign, servicestype_other, services_name, services_website FROM  members_special_services where member = '$memberId'");
 
         $result['numRows'] = count($query);
 
         $result['data']  = $query;
 
         return $result;
     }
 
     function getMemberPromoterInfo_fem($memberId)
 
     {
 
         $query = DB::select("SELECT promotertype_indy, promotertype_club, promotertype_event, promotertype_street, promoter_name, promoter_department, promoter_website FROM members_promoter where member = '$memberId'");
 
         $result['numRows'] = count($query);
 
         $result['data']  = $query;
 
         return $result;
     }
 
     function getMemberClothingInfo_fem($memberId)
 
     {
 
         $query = DB::select("SELECT clothing_name, clothing_department FROM members_clothing_apparel where member = '$memberId'");
 
         $result['numRows'] = count($query);
 
         $result['data']  = $query;
 
         return $result;
     }
 
     function getMemberManagementInfo_fem($memberId)
 
     {
 
         $query = DB::select("SELECT managementtype_artist, managementtype_tour, managementtype_personal, managementtype_finance, management_name, management_who, management_industry FROM members_management where member = '$memberId'");
 
         $result['numRows'] = count($query);
 
         $result['data']  = $query;
 
         return $result;
     }
 
     function getMemberRecordInfo_fem($memberId)
 
     {
 
         $query = DB::select("SELECT labeltype_major, labeltype_indy, labeltype_distribution, label_name, label_department FROM members_record_label where member = '$memberId'");
 
         $result['numRows'] = count($query);
 
         $result['data']  = $query;
 
         return $result;
     }
 
     function getMemberMediaInfo_fem($memberId)
 
     {
 
         $query = DB::select("SELECT mediatype_tvfilm, mediatype_publication, mediatype_newmedia, mediatype_newsletter, media_name, media_website, media_department FROM members_mass_media where member = '$memberId'");
 
         $result['numRows'] = count($query);
 
         $result['data']  = $query;
 
         return $result;
     }
 
     function getMemberRadioInfo_fem($memberId)
 
     {
 
         $query = DB::select("SELECT * FROM members_radio_station where member = '$memberId'");
 
         $result['numRows'] = count($query);
 
         $result['data']  = $query;
 
         return $result;
     }
 
     function getPaypalMemberInfo($txn_id)
 
     {
 
         $query = DB::select("SELECT clients.name, clients.email FROM  client_payments_paypal
 
    left join  client_subscriptions on client_payments_paypal.subscriptionId = client_subscriptions.subscriptionId
 
    left join  clients on client_subscriptions.clientId = clients.id
 
    where client_payments_paypal.txn_id = '$txn_id'");
 
         $result['numRows'] = count($query);
 
         $result['data']  = $query;
 
         return $result;
     }
 
     function getPaypalClientInfo($txn_id)
 
     {
 
         $query = DB::select("SELECT clients.name, clients.email FROM  client_payments_paypal
 
    left join  client_subscriptions on client_payments_paypal.subscriptionId = client_subscriptions.subscriptionId
 
    left join  clients on client_subscriptions.clientId = clients.id
 
    where client_payments_paypal.txn_id = '$txn_id'");
 
         $result['numRows'] = count($query);
 
         $result['data']  = $query;
 
         return $result;
     }
 
 
     function getMemberSocialInfo_fem($memberId)
 
     {
 
         $query = DB::select("SELECT * FROM  member_social_media where memberId = '$memberId'");
 
         $result['numRows'] = count($query);
 
         $result['data']  = $query;
 
         return $result;
     }
 
     function buy_digicoins($userId, $userType, $packageId)
 
     {
 
         $insert_data = array(
             'user_type' => $userType,
             'user_id' => $userId,
             'package_id' => $packageId,
             'buy_date_time' =>  NOW(),
         
         );
 
         $insert_id = DB::table('buy_digicoins')->insertGetId($insert_data);
 
         // $query = $this->db->query("insert into buy_digicoins (`user_type`, `user_id`, `package_id`, `buy_date_time`) values ('" . $userType . "', '" . $userId . "', '" . $packageId . "', NOW())");
 
         // $insert_id = $this->db->insert_id();
 
         return $insert_id;
     }
 
 
     function getMemberInbox_fem($memberId)
 
     {
 
         $query = DB::select("SELECT * FROM chat_messages where ((receiverType = '2' AND receiverId = '" . $memberId . "') OR (senderType = '2' AND senderId = '" . $memberId . "')) AND latest = '0' order by messageId desc");
 
         $result['numRows'] = count($query);
 
         $result['data']  = $query;
 
         return $result;
     }
 
 
     function getLogo()
     {
         $query = DB::select("SELECT logo  FROM   website_logo where logo_id = '1'");
         return $query;
     }
 
 
     function getTracks($where, $sort, $start, $limit)
 
     {
 
         //   $query = $this->db->query("SELECT * FROM  tracks $where order by $sort limit $start, $limit");
 
         /*
 
    echo "SELECT distinct tracks_mp3s.track, tracks_mp3s.bpm, tracks.id, tracks.artist, tracks.title, tracks.album, tracks.time, tracks.label, tracks.img, tracks.imgpage, tracks.added, tracks.bpm FROM  tracks
 
    left join tracks_mp3s on tracks.id = tracks_mp3s.track
 
    $where order by $sort limit $start, $limit";
 
    echo '<br />';*/
         /* $query = $this->db->query("SELECT distinct tracks_mp3s.track, tracks_mp3s.bpm, tracks.id, tracks.artist, tracks.title, tracks.album, tracks.time, tracks.label, tracks.img, tracks.imgpage, tracks.added, tracks.bpm, tracks.producer, tracks.link, tracks.videoURL FROM  tracks
 
    left join tracks_mp3s on tracks.id = tracks_mp3s.track
 
    $where order by $sort limit $start, $limit");*/
         $query = DB::select("SELECT distinct tracks_mp3s.track, tracks_mp3s.bpm, tracks.id, tracks.artist, tracks.title, tracks.album, tracks.time, tracks.label, tracks.img, tracks.imgpage, tracks.added, tracks.producer, tracks.link, tracks.videoURL FROM  tracks
 
    join tracks_mp3s on tracks.id = tracks_mp3s.track
 
    $where group by tracks.id order by $sort limit $start, $limit");
         $result['numRows'] = count($query);
 
         $result['data']  = $query;
 
         return $result;
     }
 
     function updateReview($data, $tid)
     {
 
         // $anotherFormat = implode(',', $data['anotherFormat']);
 
         /* $result = $this->db->query("update tracks_reviews set whereheard = '" . $data['whereHeard'] . "',  whatrate = '" . $data['whatRate'] . "',  anotherformat = '" . $anotherFormat . "', additionalcomments = '" . urlencode($data['comments']) . "',  godistance = '" . $data['goDistance'] . "', godistanceyes = '" . $data['goDistanceYes'] . "', labelsupport = '" . $data['labelSupport'] . "', labelsupport_other = '', howsupport = '" . $data['howSupport'] . "', howsupport_howsoon = '" . $data['howSupportHowSoon'] . "', likerecord = '" . $data['likeRecord'] . "' where track = '" . $tid . "' and id = '" . $data['reviewId'] . "' and member = '" . $_SESSION['memberId'] . "'"); */;
 
         $member_session_id = Session::get('memberId');
         
         $result = DB::select("update tracks_reviews set whatrate = '" . $data['whatRate'] . "',  additionalcomments = '" . urlencode($data['comments']) . "'  where track = '" . $tid . "' and id = '" . $data['reviewId'] . "' and member = '" . $member_session_id . "'");
 
         return $result;
     }
 
     function additionalThingsReview($data, $tid)
     {   
         if(!empty($data['additional_things'])){
             
             $request_additional_things = implode(',', $data['additional_things']);
             $rid = $data['request_id'];
             
             if($rid) {
                
                $result = DB::select("update tracks_reviews set request_additional_things = '" . $request_additional_things . "' where track = '" . $tid . "' and id = '" . $rid . "'");
     
                 return $result;
             }
 
         }
        
         return 0;
     }
 
 
     function getTrack($trackId)
 
     {
 
         $query = DB::select("SELECT * FROM  tracks where id = '$trackId'");
 
         $result['numRows'] = count($query);
 
         $result['data']  = $query;
 
         return $result;
     }
	 
	 function downloadIncrement($mp3Id, $trackId, $memberId, $countryName, $countryCode){

    $memberId_from_session = Session::get('memberId');

    // SECURITY FIX: Use Query Builder instead of raw SQL to prevent SQL injection
    $mp3 = DB::table('tracks_mp3s')->where('id', $mp3Id)->first();

    if (!$mp3) {
        return false;
    }

    $downloads = $mp3->downloads + 1;

    // SECURITY FIX: Use Query Builder for update
    DB::table('tracks_mp3s')
        ->where('id', $mp3Id)
        ->update(['downloads' => $downloads]);

    $insert_data = array(
        'memberId' => $memberId,
        'trackId' => $trackId,
        'mp3Id' => $mp3Id,
        'downloadedDateTime' => NOW(),
        'downloadedCountry' => $countryName,
        'downloadedCountryCode' =>  $countryCode,

    );

    // SECURITY FIX: Removed duplicate INSERT - was recording downloads twice
    $insert_id = DB::table('track_member_downloads')->insertGetId($insert_data);

    $insertId = $insert_id;

    // digi coins for download

    if ($insertId > 0) {

        // Check whether already downloaded or not
        // SECURITY FIX: Use Query Builder instead of raw SQL
        $digi_coins = DB::table('member_digicoins')
            ->where('member_id', $memberId_from_session)
            ->where('track_id', $trackId)
            ->where('type_id', 2)
            ->get();

        $digi_coins_numRows = count($digi_coins);

        if ($digi_coins_numRows < 1) {

            $insert_data = array(
                'member_id' => $memberId_from_session,
                'track_id' => $trackId,
                'mp3_id' => $mp3Id,
                'type_id' => 2,
                'points' => 2,
                'date_time' =>  NOW(),

            );

            $insert_id = DB::table('member_digicoins')->insertGetId($insert_data);

            $digicoin_id = $insert_id;

            if ($digicoin_id > 0) {

                // SECURITY FIX: Use Query Builder
                $available_coins = DB::table('member_digicoins_available')
                    ->where('member_id', $memberId_from_session)
                    ->orderBy('member_digicoin_available_id', 'desc')
                    ->get();

                $available_coins_numRows = count($available_coins);

                if ($available_coins_numRows > 0) {

                    $available_digicoins  = $available_coins;

                    $available_digicoins_increment = ($available_digicoins[0]->available_points) + 2;

                    // SECURITY FIX: Use Query Builder for update
                    DB::table('member_digicoins_available')
                        ->where('member_id', $memberId_from_session)
                        ->update([
                            'available_points' => $available_digicoins_increment,
                            'latest_date_time' => NOW()
                        ]);
                } else {

                    // SECURITY FIX: Use Query Builder for insert
                    DB::table('member_digicoins_available')->insert([
                        'member_id' => $memberId_from_session,
                        'available_points' => 2,
                        'latest_date_time' => NOW()
                    ]);
                }
            }
        }
    }  // digi coins for download

    return true;
}

/**
 * FEATURE FIX: Implement play tracking mechanism
 * Increments play count for a track and awards digi coins (once per member per track)
 */
function playIncrement($mp3Id, $trackId, $memberId, $countryName, $countryCode){

    $memberId_from_session = Session::get('memberId');

    // Validate inputs
    if (!$mp3Id || !$trackId || !$memberId_from_session) {
        return false;
    }

    // SECURITY FIX: Use Query Builder to prevent SQL injection
    $mp3 = DB::table('tracks_mp3s')->where('id', $mp3Id)->first();

    if (!$mp3) {
        return false;
    }

    // Increment play count
    $num_plays = $mp3->num_plays + 1;

    DB::table('tracks_mp3s')
        ->where('id', $mp3Id)
        ->update(['num_plays' => $num_plays]);

    // Record play in track_member_play table (for analytics)
    $insert_data = array(
        'memberId' => $memberId,
        'trackId' => $trackId,
        'mp3Id' => $mp3Id,
        'playedDateTime' => NOW(),
        'playedCountry' => $countryName,
        'playedCountryCode' => $countryCode,
    );

    $insert_id = DB::table('track_member_play')->insertGetId($insert_data);

    // Award digi coins for first play only
    if ($insert_id > 0) {

        // Check whether already played and awarded
        $digi_coins = DB::table('member_digicoins')
            ->where('member_id', $memberId_from_session)
            ->where('track_id', $trackId)
            ->where('type_id', 3) // type 3 = play
            ->get();

        $digi_coins_numRows = count($digi_coins);

        // Award coins only for first play
        if ($digi_coins_numRows < 1) {

            $insert_data = array(
                'member_id' => $memberId_from_session,
                'track_id' => $trackId,
                'mp3_id' => $mp3Id,
                'type_id' => 3, // play type
                'points' => 1,
                'date_time' => NOW(),
            );

            $digicoin_insert_id = DB::table('member_digicoins')->insertGetId($insert_data);

            if ($digicoin_insert_id > 0) {

                // Update available coins
                $available_coins = DB::table('member_digicoins_available')
                    ->where('member_id', $memberId_from_session)
                    ->orderBy('member_digicoin_available_id', 'desc')
                    ->first();

                if ($available_coins) {

                    $available_digicoins_increment = $available_coins->available_points + 1;

                    DB::table('member_digicoins_available')
                        ->where('member_id', $memberId_from_session)
                        ->update([
                            'available_points' => $available_digicoins_increment,
                            'latest_date_time' => NOW()
                        ]);
                } else {

                    DB::table('member_digicoins_available')->insert([
                        'member_id' => $memberId_from_session,
                        'available_points' => 1,
                        'latest_date_time' => NOW()
                    ]);
                }
            }
        }
    }

    return true;
}

function getMemberSubscriptionInfo_fem($memberId = NULL, $subscriptionId = NULL)
{

    $query = DB::select("SELECT b.packageTitle, a.package_Id, d.price, a.duration_Id, c.duration, a.subscribed_date_time
                                FROM member_subscriptions AS a
                                INNER JOIN member_packages_master AS b
                                ON a.package_Id = b.packageId
                                LEFT JOIN member_packages_duration AS c
                                ON a.duration_id = c.duration_id
                                LEFT JOIN member_packages_price AS d
                                ON a.package_Id = d.package_Id AND a.duration_Id = d.duration_Id
                                WHERE a.member_Id = '" . $memberId . "' AND a.subscription_Id = '" . $subscriptionId . "'");

    $result['numRows'] = count($query);

    $result['data']  = $query;

    return $result;
}

    // insert member didgicoins transaction data
	public function insertMemberDigicoinsTransaction($data)
	{

        $insertData = array(
            'subscriptionId' => $data['buyId'],
            'product_id' => $data['product_id'],
            'txn_id' => $data['txn_id'],
            'payment_gross' => $data['payment_gross'],
            'currency_code' => $data['currency_code'],
            'payer_email' => $data['payer_email'],
            'payment_status' => $data['payment_status'],
            'paypalPaidOn' => NOW(),
            'productType' => 2,
        );
    
        $insertId = DB::table('member_payments_paypal')->insertGetId($insertData);


	
	// $query1 = DB::select("insert into member_payments_paypal (`subscriptionId`, `product_id`, `txn_id`, `payment_gross`, `currency_code`, `payer_email`, `payment_status`, `paypalPaidOn`, `productType`) values ('". $data['buyId'] ."', '". $data['product_id'] ."', '". $data['txn_id'] ."', '". $data['payment_gross'] ."', '". $data['currency_code'] ."', '". $data['payer_email'] ."', '". $data['payment_status'] ."', NOW(), '2')");
	
	// $insertId = $this->db->insert_id();	
	if($insertId>0)
	{
  $query2 = DB::select("update buy_digicoins set payment_type = '2', payment_id = '". $insertId ."', status = '1' where buy_id = '". $data['buyId'] ."' and user_type = '2'");
  
  
  $query3 = DB::select("select user_id, package_id from buy_digicoins where payment_type = '2' and payment_id = '". $insertId ."' and buy_id = '". $data['buyId'] ."'");
  
  $row3['numRows'] = count($query3);
  $row3['data']  = $query3;
 
  if($row3['numRows']>0)
  { 
     
	 $points = 0;
	 if($row3['data'][0]->package_id==1)
	 {
	   $points = 50;	 
	 }
	 else if($row3['data'][0]->package_id==2)
	 {
	   $points = 80;	 
	 } 
	 else if($row3['data'][0]->package_id==3)
	 {
	   $points = 100;	 
	 }
   
    $query4 = DB::select("insert into member_digicoins (`member_id`, `type_id`, `points`, `date_time`, `buy_id`) values ('". $row3['data'][0]->user_id ."', '7', '". $points ."', NOW(), '". $data['buyId'] ."')");
	
	//
	
	$available_coins = DB::select("select available_points from member_digicoins_available where member_id = '". $row3['data'][0]->user_id ."' order by member_digicoin_available_id desc"); 
	
	
	
	   $available_coins_numRows = count($available_coins);
	   
	   if($available_coins_numRows>0)
	   {
	   $available_digicoins  = $available_coins;
	   $available_digicoins_increment = ($available_digicoins[0]->available_points)+$points;
	   DB::select("update member_digicoins_available set available_points = '". $available_digicoins_increment ."', latest_date_time = NOW() where member_id = '". $row3['data'][0]->user_id ."'");  	
	  }
	  else
	  {
	  DB::select("insert into member_digicoins_available (`member_id`, `available_points`, `latest_date_time`) values ('". $row3['data'][0]->user_id ."', '". $points ."', NOW())");
	 
	  } 
	
	 
  }

  return true;  
	}
	else
	{
	  return false;
	}
	
	}

       // insert client didgicoins transaction data
	public function insertClientDigicoinsTransaction($data = array()){
	
        //	$insert = $this->db->insert('payments',$data);

        $insertData = array(
            'subscriptionId' => $data['buyId'],
            'product_id' => $data['product_id'],
            'txn_id' => $data['txn_id'],
            'payment_gross' => $data['payment_gross'],
            'currency_code' => $data['currency_code'],
            'payer_email' => $data['payer_email'],
            'payment_status' => $data['payment_status'],
            'paypalPaidOn' => NOW(),
            'productType' => 2,
        );
    
        $insertId = DB::table('client_payments_paypal')->insertGetId($insertData);

        // $query2 = DB::select("insert into client_payments_paypal (`subscriptionId`, `product_id`, `txn_id`, `payment_gross`, `currency_code`, `payer_email`, `payment_status`, `paypalPaidOn`, `productType`) values ('". $data['buyId'] ."', '". $data['product_id'] ."', '". $data['txn_id'] ."', '". $data['payment_gross'] ."', '". $data['currency_code'] ."', '". $data['payer_email'] ."', '". $data['payment_status'] ."', NOW(), '2')");
        // $insertId = $this->db->insert_id();
        
        if($insertId>0)
        {
         
    $query2 = DB::select("update buy_digicoins set payment_type = '2', payment_id = '". $insertId ."', status = '1' where buy_id = '". $data['buyId'] ."' and user_type = '1'");  
        
    $query3 = DB::select("select user_id, package_id from buy_digicoins where payment_type = '2' and payment_id = '". $insertId ."' and buy_id = '". $data['buyId'] ."'");
      
      $row3['numRows'] = count($query3);
      $row3['data']  = $query3;
      
      if($row3['numRows']>0)
      { 
         
         $points = 0;
         if($row3['data'][0]->package_id==1)
         {
           $points = 50;	 
         }
         else if($row3['data'][0]->package_id==2)
         {
           $points = 80;	 
         } 
         else if($row3['data'][0]->package_id==3)
         {
           $points = 100;	 
         }
       
        $query4 = DB::select("insert into client_digicoins (`client_id`, `type_id`, `points`, `date_time`, `buy_id`) values ('". $row3['data'][0]->user_id ."', '7', '". $points ."', NOW(), '". $data['buyId'] ."')");
        
        //
        
        $available_coins = DB::select("select available_points from client_digicoins_available where client_id = '". $row3['data'][0]->user_id ."' order by client_digicoin_available_id desc"); 
        
        
        
           $available_coins_numRows = count($available_coins);
           
           if($available_coins_numRows>0)
           {
           $available_digicoins  = $available_coins;
           $available_digicoins_increment = ($available_digicoins[0]->available_points)+$points;
           DB::select("update client_digicoins_available set available_points = '". $available_digicoins_increment ."', latest_date_time = NOW() where client_id = '". $row3['data'][0]->user_id ."'");  	
          }
          else
          {
          DB::select("insert into client_digicoins_available (`client_id`, `available_points`, `latest_date_time`) values ('". $row3['data'][0]->user_id ."', '". $points ."', NOW())");
         
          } 
        
         
      }
    
         
        
        
        return true;  
        }
        else
        {
          return false;
        }
        }

        	// member transaction
	public function insertMemberTransaction($data)
	{

        $insertData = array(
            'subscriptionId' => $data['subscriptionId'],
            'product_id' => $data['product_id'],
            'txn_id' => $data['txn_id'],
            'payment_gross' => $data['payment_gross'],
            'currency_code' => $data['currency_code'],
            'payer_email' => $data['payer_email'],
            'payment_status' => $data['payment_status'],
            'paypalPaidOn' => NOW(),

        );
    
        $insertId = DB::table('member_payments_paypal')->insertGetId($insertData);
	
	// $query = DB::select("insert into member_payments_paypal (`subscriptionId`, `product_id`, `txn_id`, `payment_gross`, `currency_code`, `payer_email`, `payment_status`, `paypalPaidOn`) values ('". $data['subscriptionId'] ."', '". $data['product_id'] ."', '". $data['txn_id'] ."', '". $data['payment_gross'] ."', '". $data['currency_code'] ."', '". $data['payer_email'] ."', '". $data['payment_status'] ."', NOW())");
	
	// $insertId = $this->db->insert_id();	
	if($insertId>0)
	{
	 
	  
	  $query = DB::select("update member_subscriptions set paymentType = '2', paymentId = '". $insertId ."', status = '1' where subscription_Id = '". $data['subscriptionId'] ."'");
	   return true;  
	}
	else
	{
	  return false;
	}
	
	}
	
	
	//insert transaction data
	public function insertTransaction($data = array()){
	
	//	$insert = $this->db->insert('payments',$data);

    $insertData = array(
        'subscriptionId' => $data['subscriptionId'],
        'product_id' => $data['product_id'],
        'txn_id' => $data['txn_id'],
        'payment_gross' => $data['payment_gross'],
        'currency_code' => $data['currency_code'],
        'payer_email' => $data['payer_email'],
        'payment_status' => $data['payment_status'],
        'paypalPaidOn' => NOW(),
    );

    $insertId = DB::table('client_payments_paypal')->insertGetId($insertData);


	// $query2 = DB::select("insert into client_payments_paypal (`subscriptionId`, `product_id`, `txn_id`, `payment_gross`, `currency_code`, `payer_email`, `payment_status`, `paypalPaidOn`) values ('". $data['subscriptionId'] ."', '". $data['product_id'] ."', '". $data['txn_id'] ."', '". $data['payment_gross'] ."', '". $data['currency_code'] ."', '". $data['payer_email'] ."', '". $data['payment_status'] ."', NOW())");
	// $insertId = $this->db->insert_id();
	
	if($insertId>0)
	{
	 
	  
	  $query = DB::select("update client_subscriptions set paymentType = '2', paymentId = '". $insertId ."', status = '1' where subscriptionId = '". $data['subscriptionId'] ."'");
	   return true;  
	}
	else
	{
	  return false;
	}
	
	
	//	return $insert?true:false;
	}

    function getSubscriptionDetails_paypal($clientSubscriptionId)

    {

        $query = DB::select("SELECT packageId FROM client_subscriptions where subscriptionId = '$clientSubscriptionId'");

        $result['numRows'] = count($query);

        $result['data']  = $query;

        return $result;
    }

    function getClientSubscriptionInfo_cl_paypal($clientId, $subscriptionId)

    {

        $query =  DB::select("SELECT * FROM client_subscriptions where clientId = '" . $clientId . "' and subscriptionId = '" . $subscriptionId . "'");

        $result['numRows'] = count($query);

        $result['data']  = $query;

        return $result;
    }
    
  
      // member R-S function ends here
}
