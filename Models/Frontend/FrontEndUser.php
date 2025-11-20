<?php

namespace App\Models\frontend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use Session;

class FrontEndUser extends Model
{
   
   use HasFactory;
	
	 public static function changeDateFormate($date,$date_format){

		return \Carbon\Carbon::createFromFormat('Y-m-d', $date)->format($date_format);    

	}   

	 public static function productImagePath($image_name)

	{

		return public_path('images/products/'.$image_name);

	}
	 public static function getLogo()
	{
		//$query = $this->db->query("SELECT logo  FROM   website_logo where logo_id = '1'");
		$results = DB::select('SELECT logo  FROM website_logo where logo_id = :id', ['id' => 1]);
		return $results;
	}

	 public static function getClientInfo($clientId)
	{

		$queryRes = DB::select("SELECT * FROM  clients where id = ?", [$clientId]);

		$result['numRows'] = count($queryRes);

		$result['data']  = $queryRes;

		return $result;
	}

		/**
		 * getContinents
		 *
		 * @param
		 * @return
		 */
	 public static function getContinents()
		{
		   // $query = $this->db->query("SELECT * FROM `continents`");
			
			$output = DB::table('continents')->get()->toArray();

			$result['numRows'] = count($output);

			$result['data']  = $output;
// print_r($result);
// die();
			return  $result;
		}

	 public static function getCountries($where)
	{
		//$query = $this->db->query("SELECT logo  FROM   website_logo where logo_id = '1'");
		//echo "SELECT * FROM `country` $where ORDER BY TRIM(`country`)";die('--');
		$results = DB::select("SELECT * FROM `country` $where ORDER BY TRIM(`country`)");
		return $results;
	}
	
	public static function isExpiredConfirmCode($code){
		$query = DB::select("SELECT userId, userType  FROM   forgot_password where code = ? and status = '0'", [$code]);

		$result['numRows'] = count($query);
		$result['data']  = $query;

		return $result;
	}

	 public static function getStatesById($country)
		{

			$result1 = array();

			// $query = $this->db->query("SELECT countryId FROM country WHERE country = " .  (int) $country);

			// $rows = $query->num_rows();

			// if ($rows > 0) {

			//     $countries  = $query->result();

			//     $country = $countries[0]->countryId;

			$queryRes = DB::select("SELECT `stateId`, `name` FROM `states` WHERE `countryId` = " .  (int) $country ." ORDER BY `states`.`name` ASC");

			$result1['numRows'] = count($queryRes);

			$result1['data']  = $queryRes;
			// }

			return  $result1;
		}
		
		 public static function addNewClientInfo($data, $newClientId,$package_id)
		{
			extract($data);
			//echo "<pre>"; print_r($company); echo "</pre>"; die('--1');
			$qData = array(
				'name' => urlencode($company),
				'ccontact' => urlencode($name),
				'address1' => urlencode($address1),
				'city' => urlencode($city),
				'state' => urlencode($state),
				'country' => urlencode($country),
				'zip' => $zip
			);
			
			$resQry = DB::table('clients')
			->where('id', $newClientId)  // find your user by their email
			->limit(1)  // optional - to ensure only one record is updated.
			->update($qData);  // update the record in the DB.
			
		   // $this->db->where('id', $newClientId);
		   
		   	 $query=DB::table('package_user_details')->where('user_id','=',$newClientId)->update([
				    'user_name'=> urlencode($company),
				    'package_id' => 13,
				    'user_type' =>2,
				    
				    ]);

			return $resQry;
		}
		
		 public static function addClient1($data,$package_id)

		{

			extract($data);
			//echo "<pre>"; print_r($company); echo "</pre>"; die('--2');
			$qData = array(
				'ulevel' => 3,
				'name' => urlencode($company),
				'lastlogon' => date("Y-m-d h:i:s"),
				'ccontact' => urlencode($name),
				'address1' => urlencode($address1),
				'address2' => urlencode($address2),
				'city' => urlencode($city),
				'state' => urlencode($state),
				'country' => urlencode($country),
				'zip' => $zip,
				'resubmission' => 1
			);

			/* $query = $this->db->query("INSERT INTO `clients` (`ulevel`, `name`, `lastlogon`, `ccontact`, `address1`, `address2`, `city`, `state`, `country`, `zip`, `resubmission`) VALUES ('3', '" . urlencode($company) . "', NOW(), '" . urlencode($name) . "', '" . urlencode($address1) . "' , '" . urlencode($address2) . "'  , '" . urlencode($city) . "', '" . urlencode($state) . "', '" . urlencode($country) . "', '" . $zip . "', '1')"); */
			//echo'<pre>';print_r($qData);die('---YSYS');
			$insertId = DB::table('clients')->insertGetId($qData);
			
			$query=DB::table('package_user_details')->insert([
				    'user_id' => $insertId,
				    'package_id' => 13,
				    'user_type' =>2,
				    'user_name' => urlencode($company)
				    
				    ]);
				// dd($query);    
			//dd($insertId);
			return $insertId;
		}
		
		 public static function addClient2($request, $newClientId)
		{
			$email = $request->input('email');
			$username = $request->input('username');
			$password = $request->input('password');
			$website = $request->input('website');
			$phone = $request->input('phone');
			$mobile = $request->input('mobile');
			$howHeard = $request->input('howHeard');

			$query1 = DB::select("SELECT id FROM clients where email = ?", [urlencode($email)]);

			$numRows1 = count($query1);

			$query2 = DB::select("SELECT id FROM members where email = ?", [urlencode($email)]);

			$numRows2 = count($query2);
			
			if ($numRows1 > 0) {

				$result['numRows'] = -1;
			} else if ($numRows2 > 0) {

				$result['numRows'] = -1;
			} else {
				
				$password = md5($password);		
				
				$updateData = array(
					'uname' => $username,
					'pword' => $password,
					'email' => urlencode($email),
					'website' => $website,
					'phone' => $phone,
					'mobile' => $mobile,
					'howheard' => $howHeard
					
				);
				
				$socialMedData = array(
					'clientId' => $newClientId,
					'facebook' => $request->input('facebook'),
					'twitter' => $request->input('twitter'),
					'instagram' => $request->input('instagram'),
					'snapchat' => $request->input('snapchat'),
					'tiktok' => $request->input('tiktok'),
					'triller' => $request->input('triller'),
					'twitch' => $request->input('twitch'),
					'mixcloud' => $request->input('mixcloud'),
					'reddit' => $request->input('reddit'),
					'linkedin' => $request->input('linkedin')
					
				);
				
				$resQry = DB::table('clients')
				->where('id', $newClientId)  // find your user by their email
				->limit(1)  // optional - to ensure only one record is updated.
				->update($updateData);  // update the record in the DB.
				
				$insertId = DB::table('client_social_media')->insert($socialMedData);

				$queryResDta = DB::select("SELECT id, ccontact, email FROM  clients where id = ?", [$newClientId]);

				$result['numRows'] = count($queryResDta);

				$result['data']  = $queryResDta;
			}
			//print_array($result);die('--1213');
			return  $result;
			
		}
		
		 public static function addNewClientInfo2($request, $newClientId)
		{
			$email = $request->input('email');
			$username = $request->input('username');
			$password = $request->input('password');
			$website = $request->input('website');
			$phone = $request->input('phone');
			$mobile = $request->input('mobile');
			$howHeard = $request->input('howHeard');
			
			$password = md5($password);		
				
			$updateData = array(
				'uname' => $username,
				'pword' => $password,
				'email' => urlencode($email),
				'website' => $website,
				'phone' => $phone,
				'mobile' => $mobile,
				'howheard' => $howHeard
				
			);
			
			$socialMedData = array(
				'clientId' => $newClientId,
				'facebook' => $request->input('facebook'),
				'twitter' => $request->input('twitter'),
				'instagram' => $request->input('instagram'),
				'snapchat' => $request->input('snapchat'),
				'tiktok' => $request->input('tiktok'),
				'triller' => $request->input('triller'),
				'twitch' => $request->input('twitch'),
				'mixcloud' => $request->input('mixcloud'),
				'reddit' => $request->input('reddit'),
				'linkedin' => $request->input('linkedin')
				
			);
			$resQry = DB::table('clients')
				->where('id', $newClientId)  // find your user by their email
				->limit(1)  // optional - to ensure only one record is updated.
				->update($updateData);  // update the record in the DB.
				
				$insertId = DB::table('client_social_media')->insert($socialMedData);

				$queryResDta = DB::select("SELECT id, ccontact, email FROM  clients where id = ?", [$newClientId]);

				$result['numRows'] = count($queryResDta);

				$result['data']  = $queryResDta;
				//print_array($result);die('--1213');
				return $result;
		}
		
		 public static function addNewPaidMember($request, $memberId,$package_id)
		{
            $dateString = $request->input('dob');
            $timestamp = strtotime($dateString);
            $dob = date('Y-m-d', $timestamp);
				$updateData = array(
				'uname' => urlencode($request->input('username')),
				'pword' => md5($request->input('password')),
				'fname' => $request->input('firstName'),
				'lname' => $request->input('lastName'),
				'phone' => $request->input('phone'),
            'dob' => $dob,
				'added' => date("Y-m-d h:i:s"),
				'lastlogon' => date("Y-m-d h:i:s")
			);
            // print_r($updateData);die;
			//echo $memberId;die('uiui');
			$resQry = DB::table('members')
				->where('id', $memberId)
				->limit(1)
				->update($updateData); 
				
			 $query=DB::table('package_user_details')->where('user_id','=',$memberId)->update([
				    'user_name'=> urlencode($request->input('username')),
				    'package_id' => 7,
				    'user_type' =>1,
				    
				    ]);
			return $memberId;
		}
		 public static function addMember1($request,$package_id)
		{
			$email = $request->input('email');
			$username = $request->input('username');
			$password = $request->input('password');
			$firstName = $request->input('firstName');
			$lastName = $request->input('lastName');
			$phone = $request->input('phone');
			$howHeard = $request->input('howHeard');

            //dob
            $dateString = $request->input('dob');
            $timestamp = strtotime($dateString);
            $dob = date('Y-m-d', $timestamp);

			$query1 = DB::select("SELECT id FROM clients where email = ?", [urlencode($email)]);

			$numRows1 = count($query1);

			$query2 = DB::select("SELECT id FROM members where email = ?", [urlencode($email)]);

			$numRows2 = count($query2);
			//echo ("SELECT id FROM members where email = '" . urlencode($email) . "'").'---'.$numRows2;die('uiui');
			if ($numRows1 > 0) {

				$result = -1;
			} else if ($numRows2 > 0) {

				$result = -1;
			} else {
				$password = md5($password);
				
				$qData = array(				
					'uname' => $username,
					'pword' => $password,
					'ulevel' => 1,
					'fname' => $firstName,
					'lname' => $lastName,
					'email' => urlencode($email),
					'phone' => $phone,
               'dob' => $dob,
					'added' => date("Y-m-d h:i:s"),
					'lastlogon' => date("Y-m-d h:i:s"),
					'active' => 0,
					'resubmission' => 1,
					
				);
				$result = DB::table('members')->insertGetId($qData);
				 $query=DB::table('package_user_details')->insert([
				    'user_id' => $result,
				    'package_id' => 7,
				    'user_type' =>1,
				    'user_name' =>$username
				    
				    ]);
			}
			//echo $result;die('uiui');
			return $result;
		}
		
		 public static function addMember2($request, $newMemberId){
			
			$country = $request->input('country');
			
			$address1 = $request->input('address1');
			$city = $request->input('city');
			$state = $request->input('state');
			$country = $request->input('country');
			$continent = $request->input('continent');
			$zip = $request->input('zip');
			
			$countryName = '';

			if ((int) $country > 0) {
				
				$countryDat = DB::select("SELECT * FROM country  where countryId = " . (int) $country);
				//print_array($countryDat);die('--YSYS');
				if(!empty($countryDat) && count($countryDat) > 0) {
					foreach($countryDat as $valC){
						$countryName = $valC->country;
					}
				}
			}
			$updateData = array(
				'address1' => $address1,
				'city' => $city,
				'state' => $state,
				'country' => $country,
				'continent' => $continent,
				'zip' => $zip
				
			);
			$socialMedData = array(
				'memberId' => $newMemberId,
				'facebook' => addslashes($request->input('facebook')),
				'twitter' => addslashes($request->input('twitter')),
				'instagram' => addslashes($request->input('instagram')),
				'snapchat' => addslashes($request->input('snapchat')),
				'tiktok' => addslashes($request->input('tiktok')),
				'triller' => addslashes($request->input('triller')),
				'twitch' => addslashes($request->input('twitch')),
				'mixcloud' => addslashes($request->input('mixcloud')),
				'reddit' => addslashes($request->input('reddit')),
				'linkedin' => addslashes($request->input('linkedin'))
				
			);
			$resQry = DB::table('members')
				->where('id', $newMemberId)
				->limit(1)
				->update($updateData);
				
			$insertId = DB::table('member_social_media')->insert($socialMedData);

				$queryResDta = DB::select("SELECT id, fname, email FROM  members where id = ?", [$newMemberId]);

				$result['numRows'] = count($queryResDta);

				$result['data']  = $queryResDta;
				return  $result;	
			//print_array($countryName);die('--YSYS');
		}

		 public static function addMember3($request, $newMemberId)
		{

			if ($request->input('djMixer')) {
				$djMixer = 1;
			} else {
				$djMixer = 0;
			}

			if($request->input('radioStation')) {
				$radioStation = 1;
			} else {
				$radioStation = 0;
			}

			if($request->input('djtype_club')) {
				$djtype_club = 1;
			} else {
				$djtype_club = 0;
			}

			if($request->input('djtype_mixtape')) {
				$djtype_mixtape = 1;
			} else {
				$djtype_mixtape = 0;
			}

			if($request->input('djtype_satellite')) {
				$djtype_satellite = 1;
			} else {
				$djtype_satellite = 0;
			}	

			if($request->input('djtype_internet')) {
				$djtype_internet = 1;
			} else {
				$djtype_internet = 0;
			}

			if($request->input('djtype_college')) {
				$djtype_college = 1;
			} else {
				$djtype_college = 0;
			}			

			if($request->input('djtype_pirate')) {
				$djtype_pirate = 1;
			} else {
				$djtype_pirate = 0;
			}

			if($request->input('djwith_mp3')) {
				$djwith_mp3 = 1;
			} else {
				$djwith_mp3 = 0;
			}

			if($request->input('djwith_mp3_serato')) {
				$djwith_mp3_serato = 1;
			} else {
				$djwith_mp3_serato = 0;
			}

			if($request->input('djwith_mp3_final')) {
				$djwith_mp3_final = 1;
			} else {
				$djwith_mp3_final = 0;
			}

			if($request->input('djwith_mp3_pcdj')) {
				$djwith_mp3_pcdj = 1;
			} else {
				$djwith_mp3_pcdj = 0;
			}

			if($request->input('djwith_mp3_ipod')) {
				$djwith_mp3_ipod = 1;
			} else {
				$djwith_mp3_ipod = 0;
			}

			if($request->input('djwith_mp3_other')) {
				$djwith_mp3_other = 1;
			} else {
				$djwith_mp3_other = 0;
			}

			if($request->input('djwith_cd')) {
				$djwith_cd = 1;
			} else {
				$djwith_cd = 0;
			}

			if($request->input('djwith_cd_stanton')) {
				$djwith_cd_stanton = 1;
			} else {
				$djwith_cd_stanton = 0;
			}

			if($request->input('djwith_cd_numark')) {
				$djwith_cd_numark = 1;
			} else {
				$djwith_cd_numark = 0;
			}						

			if($request->input('djwith_cd_american')) {
				$djwith_cd_american = 1;
			} else {
				$djwith_cd_american = 0;
			}

			if($request->input('djwith_cd_vestax')) {
				$djwith_cd_vestax = 1;
			} else {
				$djwith_cd_vestax = 0;
			}

			if($request->input('djwith_cd_technics')) {
				$djwith_cd_technics = 1;
			} else {
				$djwith_cd_technics = 0;
			}						

			if($request->input('djwith_cd_gemini')) {
				$djwith_cd_gemini = 1;
			} else {
				$djwith_cd_gemini = 0;
			}

			if($request->input('djwith_cd_denon')) {
				$djwith_cd_denon = 1;
			} else {
				$djwith_cd_denon = 0;
			}

			if($request->input('djwith_cd_gemsound')) {
				$djwith_cd_gemsound = 1;
			} else {
				$djwith_cd_gemsound = 0;
			}						

			if($request->input('djwith_cd_pioneer')) {
				$djwith_cd_pioneer = 1;
			} else {
				$djwith_cd_pioneer = 0;
			}

			if($request->input('djwith_cd_tascam')) {
				$djwith_cd_tascam = 1;
			} else {
				$djwith_cd_tascam = 0;
			}

			if($request->input('djwith_cd_other')) {
				$djwith_cd_other = 1;
			} else {
				$djwith_cd_other = 0;
			}						

			if($request->input('djwith_vinyl')) {
				$djwith_vinyl = 1;
			} else {
				$djwith_vinyl = 0;
			}


			if($request->input('djwith_vinyl_12')) {
				$djwith_vinyl_12 = 1;
			} else {
				$djwith_vinyl_12 = 0;
			}

			if($request->input('djwith_vinyl_45')) {
				$djwith_vinyl_45 = 1;
			} else {
				$djwith_vinyl_45 = 0;
			}						

			if($request->input('djwith_vinyl_78')) {
				$djwith_vinyl_78 = 1;
			} else {
				$djwith_vinyl_78 = 0;
			}

			if($request->input('radioPromotion')) {
				$radioPromotion = 1;
			} else {
				$radioPromotion = 0;
			}

			if($request->input('radioProduction')) {
				$radioProduction = 1;
			} else {
				$radioProduction = 0;
			}

			if($request->input('radioSales')) {
				$radioSales = 1;
			} else {
				$radioSales = 0;
			}

			if($request->input('radioIt')) {
				$radioIt = 1;
			} else {
				$radioIt = 0;
			}

			if($request->input('stationCall') && !empty($request->input('stationCall'))){
				$stationCall = $request->input('stationCall');
			}else{
				$stationCall = '';
			}

			if($request->input('stationName') && !empty($request->input('stationName'))){
				$stationName = $request->input('stationName');
			}else{
				$stationName = '';
			}

			if($request->input('stationFrequency') && !empty($request->input('stationFrequency'))){
				$stationFrequency = $request->input('stationFrequency');
			}else{
				$stationFrequency = '';
			}

			if($request->input('radioMusic')) {
				$radioMusic = 1;
			} else {
				$radioMusic = 0;
			}

			if($request->input('radioProgram')) {
				$radioProgram = 1;
			} else {
				$radioProgram = 0;
			}

			if($request->input('radioAir')) {
				$radioAir = 1;
			} else {
				$radioAir = 0;
			}

			if($request->input('radioPromotion')) {
				$radioPromotion = 1;
			} else {
				$radioPromotion = 0;
			}

			if($request->input('radioSales')) {
				$radioSales = 1;
			} else {
				$radioSales = 0;
			}

			if($request->input('radioIt')) {
				$radioIt = 1;
			} else {
				$radioIt = 0;
			}

			## Music Director

			if($request->input('musicCall') && !empty($request->input('musicCall'))){
				$musicCall = $request->input('musicCall');
			}else{
				$musicCall = '';
			}

			if($request->input('musicHost') && !empty($request->input('musicHost'))){
				$musicHost = $request->input('musicHost');
			}else{
				$musicHost = '';
			}

			if($request->input('musicName') && !empty($request->input('musicName'))){
				$musicName = $request->input('musicName');
			}else{
				$musicName = '';
			}

			if($request->input('musicTime') && !empty($request->input('musicTime'))){
				$musicTime = $request->input('musicTime');
			}else{
				$musicTime = '';
			}

			if($request->input('musicMonday')) {
				$musicMonday = 1;
			} else {
				$musicMonday = 0;
			}	
			
			if($request->input('musicTuesday')) {
				$musicTuesday = 1;
			} else {
				$musicTuesday = 0;
			}					

			if($request->input('musicWednesday')) {
				$musicWednesday = 1;
			} else {
				$musicWednesday = 0;
			}

			if($request->input('musicThursday')) {
				$musicThursday = 1;
			} else {
				$musicThursday = 0;
			}


			if($request->input('musicFriday')) {
				$musicFriday = 1;
			} else {
				$musicFriday = 0;
			}

			if($request->input('musicSaturday')) {
				$musicSaturday = 1;
			} else {
				$musicSaturday = 0;
			}


			if($request->input('musicSunday')) {
				$musicSunday = 1;
			} else {
				$musicSunday = 0;
			}

			if($request->input('musicVaries')) {
				$musicVaries = 1;
			} else {
				$musicVaries = 0;
			}

			if($request->input('clubdj_clubname') && !empty($request->input('clubdj_clubname'))){
				$clubdj_clubname = $request->input('clubdj_clubname');
			}else{
				$clubdj_clubname = '';
			}			

			if($request->input('clubdj_capacity') && !empty($request->input('clubdj_capacity'))){
				$clubdj_capacity = $request->input('clubdj_capacity');
			}else{
				$clubdj_capacity = '';
			}

			if($request->input('clubdj_partytype') && !empty($request->input('clubdj_partytype'))){
				$clubdj_partytype = $request->input('clubdj_partytype');
			}else{
				$clubdj_partytype = '';
			}

			if($request->input('clubdj_hiphop')) {
				$clubdj_hiphop = 1;
			} else {
				$clubdj_hiphop = 0;
			}

			if($request->input('clubdj_rb')) {
				$clubdj_rb = 1;
			} else {
				$clubdj_rb = 0;
			}

			if($request->input('clubdj_pop')) {
				$clubdj_pop = 1;
			} else {
				$clubdj_pop = 0;
			}

			if($request->input('clubdj_reggae')) {
				$clubdj_reggae = 1;
			} else {
				$clubdj_reggae = 0;
			}

			if($request->input('clubdj_house')) {
				$clubdj_house = 1;
			} else {
				$clubdj_house = 0;
			}

			if($request->input('clubdj_calypso')) {
				$clubdj_calypso = 1;
			} else {
				$clubdj_calypso = 0;
			}	

			if($request->input('clubdj_rock')) {
				$clubdj_rock = 1;
			} else {
				$clubdj_rock = 0;
			}	

			if($request->input('clubdj_techno')) {
				$clubdj_techno = 1;
			} else {
				$clubdj_techno = 0;
			}	

			if($request->input('clubdj_trance')) {
				$clubdj_trance = 1;
			} else {
				$clubdj_trance = 0;
			}	

			if($request->input('clubdj_afro')) {
				$clubdj_afro = 1;
			} else {
				$clubdj_afro = 0;
			}	

			if($request->input('clubdj_reggaeton')) {
				$clubdj_reggaeton = 1;
			} else {
				$clubdj_reggaeton = 0;
			}

			if($request->input('clubdj_gogo')) {
				$clubdj_gogo = 1;
			} else {
				$clubdj_gogo = 0;
			}

			if($request->input('clubdj_neosoul')) {
				$clubdj_neosoul = 1;
			} else {
				$clubdj_neosoul = 0;
			}

			if($request->input('clubdj_oldschool')) {
				$clubdj_oldschool = 1;
			} else {
				$clubdj_oldschool = 0;
			}

			if($request->input('clubdj_electronic')) {
				$clubdj_electronic = 1;
			} else {
				$clubdj_electronic = 0;
			}			


			if($request->input('clubdj_latin')) {
				$clubdj_latin = 1;
			} else {
				$clubdj_latin = 0;
			}

			if($request->input('clubdj_dance')) {
				$clubdj_dance = 1;
			} else {
				$clubdj_dance = 0;
			}

			if($request->input('clubdj_jazz')) {
				$clubdj_jazz = 1;
			} else {
				$clubdj_jazz = 0;
			}			
			
			if($request->input('clubdj_country')) {
				$clubdj_country = 1;
			} else {
				$clubdj_country = 0;
			}

			if($request->input('clubdj_world')) {
				$clubdj_world = 1;
			} else {
				$clubdj_world = 0;
			}

			## Club DJ Show Days

			if($request->input('clubdj_monday')) {
				$clubdj_monday = 1;
			} else {
				$clubdj_monday = 0;
			}

			if($request->input('clubdj_tuesday')) {
				$clubdj_tuesday = 1;
			} else {
				$clubdj_tuesday = 0;
			}			
			
			if($request->input('clubdj_wednesday')) {
				$clubdj_wednesday = 1;
			} else {
				$clubdj_wednesday = 0;
			}

			if($request->input('clubdj_thursday')) {
				$clubdj_thursday = 1;
			} else {
				$clubdj_thursday = 0;
			}						

			if($request->input('clubdj_friday')) {
				$clubdj_friday = 1;
			} else {
				$clubdj_friday = 0;
			}			
			
			if($request->input('clubdj_saturday')) {
				$clubdj_saturday = 1;
			} else {
				$clubdj_saturday = 0;
			}

			if($request->input('clubdj_sunday')) {
				$clubdj_sunday = 1;
			} else {
				$clubdj_sunday = 0;
			}

			if($request->input('clubdj_varies')) {
				$clubdj_varies = 1;
			} else {
				$clubdj_varies = 0;
			}

			if($request->input('clubdj_city') && !empty($request->input('clubdj_city'))){
				$clubdj_city = $request->input('clubdj_city');
			}else{
				$clubdj_city = '';
			}

			if($request->input('clubdj_state') && !empty($request->input('clubdj_state'))){
				$clubdj_state = $request->input('clubdj_state');
			}else{
				$clubdj_state = '';
			}

			if($request->input('clubdj_intcountry') && !empty($request->input('clubdj_intcountry'))){
				$clubdj_intcountry = $request->input('clubdj_intcountry');
			}else{
				$clubdj_intcountry = '';
			}										

			## Program Director

			if($request->input('programCall') && !empty($request->input('programCall'))){
				$programCall = $request->input('programCall');
			}else{
				$programCall = '';
			}

			if($request->input('programHost') && !empty($request->input('programHost'))){
				$programHost = $request->input('programHost');
			}else{
				$programHost = '';
			}

			if($request->input('programName') && !empty($request->input('programName'))){
				$programName = $request->input('programName');
			}else{
				$programName = '';
			}

			if($request->input('programTime') && !empty($request->input('programTime'))){
				$programTime = $request->input('programTime');
			}else{
				$programTime = '';
			}	

			if($request->input('programMonday')) {
				$programMonday = 1;
			} else {
				$programMonday = 0;
			}	
			
			if($request->input('programTuesday')) {
				$programTuesday = 1;
			} else {
				$programTuesday = 0;
			}					

			if($request->input('programWednesday')) {
				$programWednesday = 1;
			} else {
				$programWednesday = 0;
			}

			if($request->input('programThursday')) {
				$programThursday = 1;
			} else {
				$programThursday = 0;
			}


			if($request->input('programFriday')) {
				$programFriday = 1;
			} else {
				$programFriday = 0;
			}

			if($request->input('programSaturday')) {
				$programSaturday = 1;
			} else {
				$programSaturday = 0;
			}


			if($request->input('programSunday')) {
				$programSunday = 1;
			} else {
				$programSunday = 0;
			}

			if($request->input('programVaries')) {
				$programVaries = 1;
			} else {
				$programVaries = 0;
			}					

			## On-Air Personality/Jock

			if($request->input('airName') && !empty($request->input('airName'))){
				$airName = $request->input('airName');
			}else{
				$airName = '';
			}

			if($request->input('airTime') && !empty($request->input('airTime'))){
				$airTime = $request->input('airTime');
			}else{
				$airTime = '';
			}

			if($request->input('airVaries')){
				$airVaries = $request->input('airVaries');
			}else{
				$airVaries = '';
			}

			if($request->input('airMonday')) {
				$airMonday = 1;
			} else {
				$airMonday = 0;
			}	
			
			if($request->input('airTuesday')) {
				$airTuesday = 1;
			} else {
				$airTuesday = 0;
			}					

			if($request->input('airWednesday')) {
				$airWednesday = 1;
			} else {
				$airWednesday = 0;
			}

			if($request->input('airThursday')) {
				$airThursday = 1;
			} else {
				$airThursday = 0;
			}


			if($request->input('airFriday')) {
				$airFriday = 1;
			} else {
				$airFriday = 0;
			}

			if($request->input('airSaturday')) {
				$airSaturday = 1;
			} else {
				$airSaturday = 0;
			}


			if($request->input('airSunday')) {
				$airSunday = 1;
			} else {
				$airSunday = 0;
			}

			$djtype_commercialreporting = 0;	

			 $djtype_commercialnonreporting = 0;		

			$resultMemRadio = array();

			$resultDjMixer = array();

			$queryMemRadioDta = DB::select("SELECT id FROM members_radio_station where member = ?", [$newMemberId]);

			$resultMemRadio['numRows'] = count($queryMemRadioDta);

			$resultMemRadio['data']  = $queryMemRadioDta;

			$queryMemDjMixDta = DB::select("SELECT id FROM members_dj_mixer where member = ?", [$newMemberId]);

			$resultDjMixer['numRows'] = count($queryMemDjMixDta);

			$resultDjMixer['data']  = $queryMemDjMixDta;

			if(count($queryMemDjMixDta) > 0) {
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
					->where('member', $newMemberId)
					->limit(1)
					->update($updateMemDjMixData);

			}else{

				$updateMemDjMixData = array(
					'member' => (int) $newMemberId,
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

			if(count($queryMemRadioDta) > 0) {

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
					->where('member', $newMemberId)
					->limit(1)
					->update($updateMemRadioData);					

			}else{

					$updateMemRadioData = array(
						'member' => (int) $newMemberId,
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

			if ($request->input('$massMedia')) {
				$massMedia = 1;
			} else {
				$massMedia = 0;
			}

			if ($request->input('recordLabel')) {
				$recordLabel = 1;
			} else {
				$recordLabel = 0;
			}

			if ($request->input('management')) {
				$management = 1;
			} else {
				$management = 0;
			}

			if ($request->input('turntablist')) {
				$turntablist = 1;
			} else {
				$turntablist = 0;
			}

			if ($request->input('weddingDj')) {
				$weddingDj = 1;
			} else {
				$weddingDj = 0;
			}

			if ($request->input('clothing')) {
				$clothing = 1;
			} else {
				$clothing = 0;
			}

			if ($request->input('promoter')) {
				$promoter = 1;
			} else {
				$promoter = 0;
			}

			if ($request->input('specialServices')) {
				$specialServices = 1;
			} else {
				$specialServices = 0;
			}

			if ($request->input('production')) {
				$production = 1;
			} else {
				$production = 0;
			}
			
			if (strpos($request->input('stageName'), "'") !== false)    $stageName = str_replace("'", "\'", $request->input('stageName'));
			
			$updateData = array(
				'stagename' => $request->input('stageName'),
				'age' => $request->input('age'),
				'sex' => $request->input('sex'),
				'website' => $request->input('website'),
				'dj_mixer' => $request->input('djMixer'),
				'radio_station' => $request->input('radioStation'),
				'record_label' => $request->input('recordLabel'),
				'management' => $request->input('management'),
				'clothing_apparel' => $request->input('clothing'),
				'mass_media' => $request->input('massMedia'),
				'promoter' => $request->input('promoter'),
				'production_talent' => $request->input('production'),
				'howheard' => $request->input('howheard'),
				'howheardvalue' => $request->input('howheardvalue'),
				'playlist_contributor' => $request->input('playlist_contributor'),
				
			);
			
			$resQry = DB::table('members')
				->where('id', $newMemberId)
				->limit(1)
				->update($updateData);
					// playlist contributor
			if ($request->input('playlist_contributor') == 1) {

				//$this->db->query("DELETE FROM `members_playlist_contributors` WHERE `member_id` = " . (int) $newMemberId);
				DB::table('members_playlist_contributors')->where('member_id', (int)$newMemberId)->delete();
				$playlists = $request->input('playlists');
				$playlist_count = count($playlists);
				for ($i = 0; $i < $playlist_count; $i++) {
					$insPlayList = array(
						'member_id' => $newMemberId,
						'playlist_id' => $playlists[$i]					
					);
					$insertId = DB::table('members_playlist_contributors')->insert($insPlayList);                
				}

			}
			$result = 1;

			// }

			return  $result;
		}
		

		 public static function addMember4($request,$newMemberId){
			//print_array( $request );die('---YSYSYS');
			$updateData = array(
				'computer' => $request->input('computerType'),
				'mixer_type' => $request->input('mixerType'),
				'headphones' => $request->input('headphones'),
				'game_system' => $request->input('gameSystem'),
				'hat_size' => $request->input('hatSize'),
				'pants_size' => $request->input('pantsSize'),
				'turntables_type' => $request->input('turntablesType'),
				'needles_type' => $request->input('needlesType'),
				'player' => $request->input('mp3Player'),
				'cell_phone' => $request->input('cellPhone'),
				'shirt_size' => $request->input('shirtSize'),
				'shoe_size' => $request->input('shoeSize'),
				'audioQuality' => $request->input('audioQuality')			
			);
			
			$resQry = DB::table('members')
				->where('id', $newMemberId)
				->limit(1)
				->update($updateData);
				
			
				
			$queryResDta = DB::select("SELECT id, fname, email FROM  members where id = ?", [$newMemberId]);

			$result['numRows'] = count($queryResDta);

			$result['data']  = $queryResDta;
			
			return  $result;	
		}
		
		 public static function forgotPassword($email, $userType){
			
			$result['numRows'] = 0;
			$result['insertId'] = '';
			if(!empty($email) && !empty($userType) && in_array($userType, array(1,2))){
				if($userType == 1){
					$queryResDta = DB::select("SELECT `id`, `uname`, `name`, `email` FROM `clients` WHERE `email` = ? OR `email` = ?", [urlencode($email), $email]);

					$result['numRows'] = count($queryResDta);

					$result['data']  = $queryResDta;
					
					$result['insertId'] = 0;
					
					if ($result['numRows'] > 0) {
						$token = Str::random(20);
						//$result['code'] = rand(9999, 1000000);
						$result['code'] = $token;
						
						$insArr = array(
							'userId' => $result['data'][0]->id,
							'userType' => '1',				
							'status' => '0',
							'code' => $result['code']
						);
						
						$insertId = DB::table('forgot_password')->insertGetId($insArr);
						$result['insertId'] = $insertId;				
					}
				}else{
					$queryMemDta = DB::select("SELECT `id`, `uname`, `fname`, `email` FROM `members` WHERE `email` = ? OR `email` = ?", [urlencode($email), $email]);
					$result['numRows'] = count($queryMemDta);
					$result['data']  = $queryMemDta;
					if ($result['numRows'] > 0) {
						$token = Str::random(20);
						//$result['code'] = rand(9999, 1000000);
						$result['code'] = $token;
						
						$insArr = array(
							'userId' => $result['data'][0]->id,
							'userType' => '2',				
							'status' => '0',
							'code' => $result['code']
						);
						
						$insertId = DB::table('forgot_password')->insertGetId($insArr);
						$result['insertId'] = $insertId;
					}
				}
				
			}
			return  $result;
		}
		
		public static function resetPassword($password, $code){
			$queryResDta = DB::select("SELECT userId, userType  FROM forgot_password where code = ? and status = '0'", [$code]);
			$result['numRows'] = count($queryResDta);
			$data  = $queryResDta;
			
			$result['update'] = 0;
			if ($result['numRows'] == 1) {
				if ($data[0]->userType == 1) {
					$table = 'clients';
				} else if ($data[0]->userType == 2) {
					$table = 'members';
				}
				
				$updateUserData = array(
					'pword'=> md5($password)
				);
				$updatePwdData = array(
					'status'=> 1
				);
				
				$resQry = DB::table($table)
					->where('id', $data[0]->userId)
					->limit(1)
					->update($updateUserData);
					
				$resPwdQry = DB::table('forgot_password')
					->where('code', $code)
					->where('status', 0)
					->limit(1)
					->update($updatePwdData);
				//$query = $this->db->query("delete FROM  remember where userId = '" . $data[0]->userId . "' and typeId = '" . $data[0]->userType . "'");
				if ($data[0]->userType == 1) {

					$query = DB::select("SELECT name, email  FROM   $table   where id = ?", [$data[0]->userId]);
				} else if ($data[0]->userType == 2) {

					$query = DB::select("SELECT fname, email  FROM   $table   where id = ?", [$data[0]->userId]);
				}
				$result['data'] = $query;
				$result['update'] = 1;
				//pArr($result);die('---');
			}
			return $result;
		}
		
		public function getBanner($page_id){
		 $query = DB::select("select banner_image,pCloudFileID from dynamic_pages where page_id = ?", [$page_id]);   
		 $result  = $query;
		 return  $result;		
		}
	  /**
		* Get Banner Text
		*
		* @GS
		*/	
		public function getBannerText($id){
			$query = DB::select("select * from  banners where  pageId = ?", [$id]);   
			$result  = $query;
			return  $result;		
		}
		
		public function getPageLinks($pageId){
		 $query = DB::select("select * from dynamic_links where pageId = ? order by linkId asc", [$pageId]);   
		 $result  = $query;
		 return  $result;		
		}
		public function getTopLinks(){
		 $query = DB::select("select * from dynamic_links where (linkId >= '3' AND linkId <= '5') order by pageId asc");   
		 $result  = $query;
		 return  $result;		
		}
	  /**
		 * Get YouTube
		 *
		 * @GS
		 */		
		public function getYoutube(){
			$queryRes = DB::select("select youtube from  youtube ");
// 			where youtube_id = '1'
			return $queryRes;
		}
		
	   /**
		 * Get Page Text
		 *
		 * @GS
		 */	
		public function getPageText($id)
		{

		 $query = DB::select("select * from  dynamic_pages where  page_id = ?", [$id]);   
		 $result  = $query;
		 return  $result;
		}
		public function getContentUsingMeta($pageID, $metaKey){
		 $query = DB::select("select * from  pages_meta where  pageId = ? AND meta_key = ?", [$pageID, $metaKey]);   
		 $result  = $query;
		 return  $result;		
		}
		
		public function getAllIds_trm(){
			 $query =  DB::select("select trackId from top_streaming_tracks");
			 $result=$query;
			 $list=array();
			 foreach($result as $t){
				 array_push($list,$t->trackId);
			 } 
			 return $list;
			 
		 }
		 public function getAllTracks_trm(){
				 $list=$this->getAllIds_trm();
				 
				 $query = DB::select("select clients.uname, clients.name, tracks.id, tracks.artist, tracks.title, tracks.album, tracks.time, tracks.label, tracks.added, tracks.img, tracks.imgpage from  tracks 
				 join clients on tracks.client = clients.id join top_streaming_tracks on tracks.id = top_streaming_tracks.trackId
				 order by top_streaming_tracks.position,top_streaming_tracks.created_at ASC LIMIT 0,30");
				 $result['numRows']  = count($query);
				 $result['data']  = $query;
				 return $result;
			 }
			 
		public function getAllTracks_custom_optimized(){
		    	 $list=$this->getAllIds_trm();
				 
				 $query = DB::select("select  tracks.id, tracks.title,  tracks.imgpage,tracks.pCloudFileID, tracks.pCloudParentFolderID from  tracks 
				 join clients on tracks.client = clients.id join top_streaming_tracks on tracks.id = top_streaming_tracks.trackId
				 order by top_streaming_tracks.position,top_streaming_tracks.created_at ASC LIMIT 0,30");
				 $result['numRows']  = count($query);
				 $result['data']  = $query;
				 
				//  if($_SERVER['REMOTE_ADDR'] = '122.185.217.118'){
    //                 echo'<pre>';print_r($query);die();
    //                 }

				 return $result;
		    
		}	 
		public function getAllPriorityTracks_trm($sort){	 
	 
			 $query = DB::select("select clients.uname, clients.name, tracks.id, tracks.artist, tracks.title, tracks.album, tracks.time, tracks.label, tracks.added, tracks.img, tracks.imgpage from  tracks 
			 left join clients on tracks.client = clients.id where tracks.priority=1 order by $sort");
			 $result['numRows']  = count($query);
			 $result['data']  = $query;
			 return $query;
		 }		
		public function getPageMeta_trm($id){
			$query =  DB::select("SELECT meta_tittle, meta_keywords, meta_description FROM  dynamic_pages where  page_id = ?", [$id]);
			$result['numRows'] = count($query);
			$result['data']  = $query;

			return  $result;
		}
		    // New 3 
		public function getNumTopDownloadTracks($where, $sort){

			$query = DB::select("SELECT DISTINCT track_member_downloads.trackId,  COUNT(track_member_downloads.trackId) AS downloads, track_member_downloads.mp3Id, tracks.id, tracks.title, track_member_downloads.downloadedDateTime, tracks.added, tracks.artist, tracks.album, tracks.label, tracks.imgpage, tracks.thumb FROM   track_member_downloads

   left join tracks_mp3s on track_member_downloads.mp3Id = tracks_mp3s.id

   left join tracks on track_member_downloads.trackId = tracks.id

   $where GROUP BY tracks.id order by $sort");

			return  count($query);
		}
		
		  public function getTopDownloadChartTracks($where, $sort, $start, $limit){

			//         $query = DB::select("SELECT DISTINCT track_member_downloads.trackId, track_member_downloads.mp3Id, tracks.id, tracks.artist, tracks.title, tracks.album, tracks.label, tracks.imgpage, tracks.thumb, tracks.added, tracks_mp3s.downloads FROM   track_member_downloads

			//    left join tracks_mp3s on track_member_downloads.mp3Id = tracks_mp3s.id
			//    left join tracks on track_member_downloads.trackId = tracks.id
			//    $where GROUP BY tracks.id order by $sort limit $start, $limit");

				$query = DB::select("SELECT DISTINCT track_member_downloads.trackId, tracks.pCloudFileID,  COUNT(track_member_downloads.trackId) AS downloads, track_member_downloads.mp3Id, tracks.id, tracks.title, track_member_downloads.downloadedDateTime, tracks.added, tracks.artist, tracks.album, tracks.label, tracks.imgpage, tracks.thumb FROM   track_member_downloads

   left join tracks_mp3s on track_member_downloads.mp3Id = tracks_mp3s.id
   left join tracks on track_member_downloads.trackId = tracks.id
   $where GROUP BY tracks.id order by $sort limit $start, $limit");

				$result['numRows'] =  count($query);
				$result['data']  = $query;
				return $result;
			}


			public function getNewestTracks($where, $sort, $start, $limit){

				$query = DB::select("SELECT * FROM  tracks $where order by added DESC limit " . (int)$start . ", " . (int)$limit);
				
				$result['query']  ="SELECT * FROM  tracks $where order by $sort limit $start, $limit";

				/*  $query = DB::select("SELECT distinct tracks_mp3s.track, tracks_mp3s.bpm, tracks.id, tracks.artist, tracks.title, tracks.album, tracks.time, tracks.label, tracks.img, tracks.imgpage, tracks.added, tracks.bpm FROM  tracks
				   left join tracks_mp3s on tracks.id = tracks_mp3s.track
				   $where order by $sort limit $start, $limit");  */

				$result['numRows'] =  count($query);
				$result['data']  = $query;
				
				
				$result['downloaded']=array();
				if(!empty(Session::get('memberId'))){

					$myMemId = Session::get('memberId');
					
					$track_ids= array();
					foreach ($result['data'] as $track) {
						$track_ids[]= (int)$track->id;
					}
					if(count($track_ids) > 0){


						$dwdTrackIds = implode(",",$track_ids);

						//echo '<pre>';print_r($dwdTrackIds);die();

						$query = DB::select("SELECT Distinct trackId FROM track_member_downloads WHERE trackId IN (".$dwdTrackIds.")  AND memberId = ?", [(int)$myMemId]);
						$track_ids= array();						

						if(count($query)){
							foreach ($query as $t) {
								$track_ids[] = $t->trackId;
							}
						}
					}
					$result['downloaded']  = $track_ids;
				}
				return $result;
			}
	        public function getNumTracks_trm($where, $sort){
				 $query =  DB::select("select tracks.id from  tracks
				   left join clients on tracks.client = clients.id
					$where order by $sort");
				 $result = count($query);
				 return  $result;
			 }
			public function getSubscriptionStatus($clientId){

				$query = DB::select("SELECT status, packageId FROM client_subscriptions where clientId = ? and status = '1' order by subscriptionId desc limit 1", [$clientId]);

				$result['numRows'] =  count($query);

				$result['data']  = $query;

				return $result;
			} 
		
		  public function getBannerText_trm($id){

			$query =  DB::select("SELECT * FROM  banners where  pageId = ?", [$id]);

			$result  = $query;
			//dd($result);
			return  $result;
		}
		
		public function getNotifications_trm(){

			$query =  DB::select("SELECT id, artist, album, title, imgpage, thumb  FROM tracks  order by id desc limit 0, 5");
			$result['numRows'] = count($query);
			$result['data']  = $query;
			return $result;
		}
		
		public function confirmStripeDigicoinsPayment($userId,$userType,$postDta){
			//pArr($postDta);die();
			extract($postDta);			
			if ($userType == 1) {
				$insertArr = array(
					'subscriptionId'=> ($buyId !='') ? $buyId:'',
					'token'=> (isset($stripeToken) && $stripeToken !='') ? $stripeToken:'',
					'tokenType'=> (isset($stripeTokenType) && $stripeTokenType !='') ? $stripeTokenType:'',
					'email'=> (isset($stripeEmail) && $stripeEmail !='') ? $stripeEmail:'',
					'billingName'=> (isset($stripeBillingName) && $stripeBillingName !='') ? $stripeBillingName:'',
					'billingAddressLine1'=> (isset($stripeBillingAddressLine1) && $stripeBillingAddressLine1 !='') ? $stripeBillingAddressLine1:'',
					'billingCity'=> (isset($stripeBillingAddressCity) && $stripeBillingAddressCity !='') ? $stripeBillingAddressCity:'',
					'billingState'=> (isset($stripeBillingAddressState) && $stripeBillingAddressState !='') ? $stripeBillingAddressState:'',
					'billingCountry'=> (isset($stripeBillingAddressCountry) && $stripeBillingAddressCountry !='') ? $stripeBillingAddressCountry:'',
					'billingCountryCode'=> (isset($stripeBillingAddressCountryCode) && $stripeBillingAddressCountryCode !='') ? $stripeBillingAddressCountryCode:'',
					'billingZip'=> (isset($stripeBillingAddressZip) && $stripeBillingAddressZip !='') ? $stripeBillingAddressZip:'',
					'shippingName'=> (isset($stripeShippingName) && $stripeShippingName !='') ? $stripeShippingName:'',
					'shippingAddressLine1'=> (isset($stripeShippingAddressLine1) && $stripeShippingAddressLine1 !='') ? $stripeShippingAddressLine1:'',
					'shippingCity'=> (isset($stripeShippingAddressCity) && $stripeShippingAddressCity !='') ? $stripeShippingAddressCity:'',
					'shippingState'=> (isset($stripeShippingAddressState) && $stripeShippingAddressState !='') ? $stripeShippingAddressState:'',
					'shippingCountry'=> (isset($stripeShippingAddressCountry) && $stripeShippingAddressCountry !='') ? $stripeShippingAddressCountry:'',
					'shippingCountryCode'=> (isset($stripeShippingAddressCountryCode) && $stripeShippingAddressCountryCode !='') ? $stripeShippingAddressCountryCode:'',
					'shippingZip'=> (isset($stripeShippingAddressZip) && $stripeShippingAddressZip !='') ? $stripeShippingAddressZip:'',
					'stripePaidOn'=> date('Y-m-d H:i:s'),
					'productType'=> 2
				);
				$insertId = DB::table('client_payments_stripe')->insertGetId($insertArr);			
				
				/* $query = $this->db->query("update buy_digicoins set payment_type = '1', payment_id = '" . $insertId . "', status = '1' where buy_id = '" . $buyId . "' and user_type = '1' and user_id = '" . $userId . "'"); */
				
				if ($insertId > 0) {
					
					$updateArr = array(
						'payment_type'=> 1,
						'payment_id'=> $insertId,
						'status'=> 1
					);
					
					$resQry = DB::table('buy_digicoins')
						->where('buy_id', $buyId)
						->where('user_type', 1)
						->where('user_id', $userId)
						->limit(1)
						->update($updateArr);
						
					$query3 = DB::select("SELECT user_id, package_id FROM buy_digicoins where payment_type = '1' and payment_id = ? and buy_id = ?", [$insertId, $buyId]);

					$row3['numRows'] = count($query3);
	
					$row3['data']  = $query3;
					
					if ($row3['numRows'] > 0) {
						
						$points = 0;

						if ($row3['data'][0]->package_id == 1) {

							$points = 50;
						} else if ($row3['data'][0]->package_id == 2) {

							$points = 80;
						} else if ($row3['data'][0]->package_id == 3) {

							$points = 100;
						}
						

						$clientDigiCArr = array(
							'client_id'=> $row3['data'][0]->user_id,
							'type_id'=> 6,
							'points'=> $points,
							'date_time'=> date('Y-m-d H:i:s'),
							'buy_id'=> $buyId,
						);
						
						$insertCId = DB::table('client_digicoins')->insertGetId($clientDigiCArr);
						
						$available_coins = DB::select("SELECT available_points FROM client_digicoins_available where client_id = ? order by client_digicoin_available_id desc", [$row3['data'][0]->user_id]);
						
						$available_coins_numRows = count($available_coins);
						
						if ($available_coins_numRows > 0) {
							
							$available_digicoins  = $available_coins;

							$available_digicoins_increment = ($available_digicoins[0]->available_points) + $points;							
							
							$updateAvlDigiArr = array(
								'available_points'=> $available_digicoins_increment,
								'latest_date_time'=> date('Y-m-d H:i:s')
								
							);
							
							$resUpAvlDigi = DB::table('client_digicoins_available')
								->where('client_id', $row3['data'][0]->user_id)
								->limit(1)
								->update($updateAvlDigiArr);
							
						}else{
							$addAvlDigiArr = array(
								'client_id' => $row3['data'][0]->user_id,
								'available_points'=> $points,
								'latest_date_time'=> date('Y-m-d H:i:s')								
							);
							
							$insertClientDigiId = DB::table('client_digicoins_available')->insertGetId($addAvlDigiArr);
						}
					}
					
				}
			}else{
				$insertArr = array(
					'subscriptionId'=> ($buyId !='') ? $buyId:'',
					'token'=> (isset($stripeToken) && $stripeToken !='') ? $stripeToken:'',
					'tokenType'=> (isset($stripeTokenType) && $stripeTokenType !='') ? $stripeTokenType:'',
					'email'=> (isset($stripeEmail) && $stripeEmail !='') ? $stripeEmail:'',
					'billingName'=> (isset($stripeBillingName) && $stripeBillingName !='') ? $stripeBillingName:'',
					'billingAddressLine1'=> (isset($stripeBillingAddressLine1) && $stripeBillingAddressLine1 !='') ? $stripeBillingAddressLine1:'',
					'billingCity'=> (isset($stripeBillingAddressCity) && $stripeBillingAddressCity !='') ? $stripeBillingAddressCity:'',
					'billingState'=> (isset($stripeBillingAddressState) && $stripeBillingAddressState !='') ? $stripeBillingAddressState:'',
					'billingCountry'=> (isset($stripeBillingAddressCountry) && $stripeBillingAddressCountry !='') ? $stripeBillingAddressCountry:'',
					'billingCountryCode'=> (isset($stripeBillingAddressCountryCode) && $stripeBillingAddressCountryCode !='') ? $stripeBillingAddressCountryCode:'',
					'billingZip'=> (isset($stripeBillingAddressZip) && $stripeBillingAddressZip !='') ? $stripeBillingAddressZip:'',
					'shippingName'=> (isset($stripeShippingName) && $stripeShippingName !='') ? $stripeShippingName:'',
					'shippingAddressLine1'=> (isset($stripeShippingAddressLine1) && $stripeShippingAddressLine1 !='') ? $stripeShippingAddressLine1:'',
					'shippingCity'=> (isset($stripeShippingAddressCity) && $stripeShippingAddressCity !='') ? $stripeShippingAddressCity:'',
					'shippingState'=> (isset($stripeShippingAddressState) && $stripeShippingAddressState !='') ? $stripeShippingAddressState:'',
					'shippingCountry'=> (isset($stripeShippingAddressCountry) && $stripeShippingAddressCountry !='') ? $stripeShippingAddressCountry:'',
					'shippingCountryCode'=> (isset($stripeShippingAddressCountryCode) && $stripeShippingAddressCountryCode !='') ? $stripeShippingAddressCountryCode:'',
					'shippingZip'=> (isset($stripeShippingAddressZip) && $stripeShippingAddressZip !='') ? $stripeShippingAddressZip:'',
					'stripePaidOn'=> date('Y-m-d H:i:s'),
					'productType'=> 2
				);
				$insertId = DB::table('member_payments_stripe')->insertGetId($insertArr);
				
				if ($insertId > 0) {
					
					$updateArr = array(
						'payment_type'=> 1,
						'payment_id'=> $insertId,
						'status'=> 1
					);
					
					$resQry = DB::table('buy_digicoins')
						->where('buy_id', $buyId)
						->where('user_type', 2)
						->where('user_id', $userId)
						->limit(1)
						->update($updateArr);
						
					$query3 = DB::select("SELECT user_id, package_id FROM buy_digicoins where payment_type = '1' and payment_id = ? and buy_id = ?", [$insertId, $buyId]);

					$row3['numRows'] = count($query3);
	
					$row3['data']  = $query3;
					
					if ($row3['numRows'] > 0) {
						
						$points = 0;

						if ($row3['data'][0]->package_id == 1) {

							$points = 50;
						} else if ($row3['data'][0]->package_id == 2) {

							$points = 80;
						} else if ($row3['data'][0]->package_id == 3) {

							$points = 100;
						}
						

						$memberDigiCArr = array(
							'member_id'=> $row3['data'][0]->user_id,
							'type_id'=> 6,
							'points'=> $points,
							'date_time'=> date('Y-m-d H:i:s'),
							'buy_id'=> $buyId,
						);
						
						$insertMemId = DB::table('member_digicoins')->insertGetId($memberDigiCArr);
						
						$available_coins = DB::select("SELECT available_points FROM member_digicoins_available where member_id = ? order by member_digicoin_available_id desc", [$row3['data'][0]->user_id]);
						
						$available_coins_numRows = count($available_coins);
						
						if ($available_coins_numRows > 0) {
							
							$available_digicoins  = $available_coins;

							$available_digicoins_increment = ($available_digicoins[0]->available_points) + $points;							
							
							$updateAvlDigiArr = array(
								'available_points'=> $available_digicoins_increment,
								'latest_date_time'=> date('Y-m-d H:i:s')
								
							);
							
							$resUpAvlDigi = DB::table('member_digicoins_available')
								->where('member_id', $row3['data'][0]->user_id)
								->limit(1)
								->update($updateAvlDigiArr);
							
						}else{
							$addAvlDigiArr = array(
								'member_id' => $row3['data'][0]->user_id,
								'available_points'=> $points,
								'latest_date_time'=> date('Y-m-d H:i:s')								
							);
							
							$insertMemDigiId = DB::table('member_digicoins_available')->insertGetId($addAvlDigiArr);
						}
					}
					
				}
				
			}
			
			return 1;
		}
		
	public static function reAddMember2($data, $memberId){

        extract($data);

        $countryName = '';
        $continent = 0;
		
        if ((int) $country > 0) {
            $query = DB::select("SELECT * FROM country  where countryId = " . (int) $country);
            $countryData = $query;
			
            if (isset($countryData) && !empty($countryData[0]->country)) {
                $countryName = $countryData[0]->country;
                $continent = $countryData[0]->continentId;
            }
        }
		
        $query = DB::select("update `members` set address1 = ?, city = ?, state =  ?, country = ?, continent = ?, zip = ? where id = ?", [$address1, $city, $state, $countryName, $continent, $postalCode, $memberId]);

        $result = 1;

        $social_query = DB::select("SELECT memberSocialId FROM member_social_media where memberId = ?", [$memberId]);

        $social_num_rows = count($social_query);

        if ($social_num_rows > 0) {

            DB::select("update member_social_media set facebook = ?, twitter = ?, instagram = ?, linkedin = ? where memberId = ?", [$facebook, $twitter, $instagram, $linkedin, $memberId]);
        } else {

            DB::select("insert into member_social_media (`memberId`, `facebook`, `twitter`, `instagram`, `linkedin`) values (?, ?, ?, ?, ?)", [$memberId, $facebook, $twitter, $instagram, $linkedin]);
        }

        return  $result;
    }
	
	public static function reAddMember3($data, $newMemberId){
        extract($data);

        /*

	$query1 = $this->db->query("SELECT id FROM clients where email = '". urlencode($email) ."'");

    $numRows1 = $query1->num_rows();

	$query2 = $this->db->query("SELECT id FROM members where email = '". urlencode($email) ."'");

    $numRows2 = $query2->num_rows();

   if($numRows1>0)

   {

	 $result = -1;

   }

   else if($numRows2>0)

   {

	 $result = -1;

   }

	else

	{*/

        if (isset($djMixer)) {
            $djMixer = 1;
        } else {
            $djMixer = 0;
        }

        if (isset($radioStation)) {
            $radioStation = 1;
        } else {
            $radioStation = 0;
        }

        if (isset($massMedia)) {
            $massMedia = 1;
        } else {
            $massMedia = 0;
        }

        if (isset($recordLabel)) {
            $recordLabel = 1;
        } else {
            $recordLabel = 0;
        }

        if (isset($management)) {
            $management = 1;
        } else {
            $management = 0;
        }

        if (isset($turntablist)) {
            $turntablist = 1;
        } else {
            $turntablist = 0;
        }

        if (isset($weddingDj)) {
            $weddingDj = 1;
        } else {
            $weddingDj = 0;
        }

        if (isset($clothing)) {
            $clothing = 1;
        } else {
            $clothing = 0;
        }

        if (isset($promoter)) {
            $promoter = 1;
        } else {
            $promoter = 0;
        }

        if (isset($specialServices)) {
            $specialServices = 1;
        } else {
            $specialServices = 0;
        }

        if (isset($production)) {
            $production = 1;
        } else {
            $production = 0;
        }

        // if (strpos($firstName, "'") !== false)    $firstName = str_replace("'", "\'", $firstName);
        // if (strpos($lastName, "'") !== false)    $lastName = str_replace("'", "\'", $lastName);
        // if (strpos($stageName, "'") !== false)    $stageName = str_replace("'", "\'", $stageName);

        $query = DB::select("UPDATE `members`
                                    SET `fname` = ?,
                                        `lname` =  ?,
                                        `stagename` = ?,
                                        `email` = ?,
                                        `sex` = ?,
                                        `phone` = ?,
                                        `playlist_contributor` = ?
                                    WHERE `id` = ?", [addslashes($firstName), addslashes($lastName), addslashes($stageName), addslashes(urlencode($email)), addslashes($sex), addslashes($phone), (int) $playlist_contributor, (int) $newMemberId]);

        // playlist contributor
        DB::select("DELETE FROM `members_playlist_contributors` WHERE `member_id` = " . (int) $newMemberId);
        if ($playlist_contributor == 1) {
            $playlist_count = count($playlists);
            for ($i = 0; $i < $playlist_count; $i++) {
                DB::select("INSERT INTO `members_playlist_contributors` (`member_id`, `playlist_id`) VALUE (" . (int) $newMemberId . ", " . (int) $playlists[$i] . ")");
            }
        }
        // dj mixer

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

        $managementQuery = DB::select("SELECT id FROM members_dj_mixer where member = ?", [$newMemberId]);

        $managementRows = count($managementQuery);

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

        // if (strpos($clubdj_clubname, "'") !== false)    $clubdj_clubname = str_replace("'", "\'", $clubdj_clubname);
        // if (strpos($internetdj_showtime, "'") !== false)    $internetdj_showtime = str_replace("'", "\'", $internetdj_showtime);
        // if (strpos($satellitedj_showtime, "'") !== false)    $satellitedj_showtime = str_replace("'", "\'", $satellitedj_showtime);
        // if (strpos($collegedj_showtime, "'") !== false)       $collegedj_showtime = str_replace("'", "\'", $collegedj_showtime);

        /* if ($managementRows > 0) {
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
            WHERE member = '" . $newMemberId . "'");
        } else {

            $result = DB::select("INSERT INTO members_dj_mixer (`member`, `djtype_commercialreporting`, `djtype_commercialnonreporting`, `djtype_club`, `djtype_mixtape`, `djtype_satellite`, `djtype_internet`, `djtype_college`, `djtype_pirate`, `djwith_mp3`, `djwith_mp3_serato`, `djwith_mp3_final`, `djwith_mp3_pcdj`, `djwith_mp3_ipod`, `djwith_mp3_other`, `djwith_cd`, `djwith_cd_stanton`, `djwith_cd_numark`, `djwith_cd_american`, `djwith_cd_vestax`, `djwith_cd_technics`, `djwith_cd_gemini`, `djwith_cd_denon`, `djwith_cd_gemsound`, `djwith_cd_pioneer`, `djwith_cd_tascam`, `djwith_cd_other`, `djwith_vinyl`, `djwith_vinyl_12`, `djwith_vinyl_45`, `djwith_vinyl_78`, `commercialdj_showname`, `commercialdj_call`, `commercialdj_name`, `commercialdj_frequency`, `commercialdj_monday`, `commercialdj_tuesday`, `commercialdj_wednesday`, `commercialdj_thursday`, `commercialdj_friday`, `commercialdj_saturday`, `commercialdj_sunday`, `commercialdj_varies`, `commercialdj_showtime`, `commercialdj_showtype`, `noncommercialdj_showname`, `noncommercialdj_call`, `noncommercialdj_name`, `noncommercialdj_frequency`, `noncommercialdj_monday`, `noncommercialdj_tuesday`, `noncommercialdj_wednesday`, `noncommercialdj_thursday`, `noncommercialdj_friday`, `noncommercialdj_saturday`, `noncommercialdj_sunday`, `noncommercialdj_varies`, `noncommercialdj_showtime`, `noncommercialdj_showtype`, `clubdj_clubname`, `clubdj_capacity`, `clubdj_hiphop`, `clubdj_rb`, `clubdj_pop`, `clubdj_reggae`, `clubdj_house`, `clubdj_calypso`, `clubdj_rock`, `clubdj_techno`, `clubdj_trance`, `clubdj_afro`, `clubdj_reggaeton`, `clubdj_gogo`, `clubdj_neosoul`, `clubdj_oldschool`, `clubdj_electronic`, `clubdj_latin`, `clubdj_dance`, `clubdj_jazz`, `clubdj_country`, `clubdj_world`, `clubdj_monday`, `clubdj_tuesday`, `clubdj_wednesday`, `clubdj_thursday`, `clubdj_friday`, `clubdj_saturday`, `clubdj_sunday`, `clubdj_varies`, `clubdj_city`, `clubdj_state`, `clubdj_intcountry`, `mixtapedj_name`, `mixtapedj_type`, `mixtapedj_schedule`, `mixtapedj_distribution`, `satellitedj_stationname`, `satellitedj_showname`, `satellitedj_channelname`, `satellitedj_channelnumber`, `satellitedj_monday`, `satellitedj_tuesday`, `satellitedj_wednesday`, `satellitedj_thursday`, `satellitedj_friday`, `satellitedj_saturday`, `satellitedj_sunday`, `satellitedj_showtime`, `internetdj_stationwebsite`, `internetdj_showtype`, `internetdj_showname`, `internetdj_monday`, `internetdj_tuesday`, `internetdj_wednesday`, `internetdj_thursday`, `internetdj_friday`, `internetdj_saturday`, `internetdj_sunday`, `internetdj_varies`, `internetdj_showtime`, `collegedj_callletters`, `collegedj_collegename`, `collegedj_stationfrequency`, `collegedj_showtype`, `collegedj_showname`, `collegedj_monday`, `collegedj_tuesday`, `collegedj_wednesday`, `collegedj_thursday`, `collegedj_friday`, `collegedj_saturday`, `collegedj_sunday`, `collegedj_varies`, `collegedj_showtime`, `collegedj_city`, `collegedj_state`, `collegedj_intcountry`, `piratedj_stationfrequency`, `piratedj_showname`, `piratedj_monday`, `piratedj_tuesday`, `piratedj_wednesday`, `piratedj_thursday`, `piratedj_friday`, `piratedj_saturday`, `piratedj_sunday`, `piratedj_varies`, `piratedj_showtime`) 
            VALUES (
            '" . (int) $newMemberId . "', 
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
            '" . addslashes($commercialdj_showname) . "',
            '" . addslashes($commercialdj_call) . "',
            '" . addslashes($commercialdj_name) . "',
            '" . addslashes($commercialdj_frequency) . "',
            '" . (int) $commercialdj_monday . "', 
            '" . (int) $commercialdj_tuesday . "', 
            '" . (int) $commercialdj_wednesday . "', 
            '" . (int) $commercialdj_thursday . "', 
            '" . (int) $commercialdj_friday . "', 
            '" . (int) $commercialdj_saturday . "', 
            '" . (int) $commercialdj_sunday . "', 
            '" . (int) $commercialdj_varies . "', 
            '" . addslashes($commercialdj_showtime) . "', 
            '" . addslashes($commercialdj_showtype) . "', 
            '" . addslashes($noncommercialdj_showname) . "', 
            '" . addslashes($noncommercialdj_call) . "', 
            '" . addslashes($noncommercialdj_name) . "', 
            '" . addslashes($noncommercialdj_frequency) . "', 
            '" . (int) $noncommercialdj_monday . "', 
            '" . (int) $noncommercialdj_tuesday . "', 
            '" . (int) $noncommercialdj_wednesday . "', 
            '" . (int) $noncommercialdj_thursday . "', 
            '" . (int) $noncommercialdj_friday . "', 
            '" . (int) $noncommercialdj_saturday . "', 
            '" . (int) $noncommercialdj_sunday . "', 
            '" . (int) $noncommercialdj_varies . "', 
            '" . addslashes($noncommercialdj_showtime) . "', 
            '" . addslashes($noncommercialdj_showtype) . "', 
            '" . addslashes($clubdj_clubname) . "', 
            '" . addslashes($clubdj_capacity) . "', 
            '" . (int) $clubdj_hiphop . "', 
            '" . (int) $clubdj_rb . "', 
            '" . (int) $clubdj_pop . "', 
            '" . (int) $clubdj_reggae . "', 
            '" . (int) $clubdj_house . "', 
            '" . (int) $clubdj_calypso . "', 
            '" . (int) $clubdj_rock . "', 
            '" . (int) $clubdj_techno . "', 
            '" . (int) $clubdj_trance . "', 
            '" . (int) $clubdj_afro . "', 
            '" . (int) $clubdj_reggaeton . "', 
            '" . (int) $clubdj_gogo . "', 
            '" . (int) $clubdj_neosoul . "', 
            '" . (int) $clubdj_oldschool . "', 
            '" . (int) $clubdj_electronic . "', 
            '" . (int) $clubdj_latin . "', 
            '" . (int) $clubdj_dance . "', 
            '" . (int) $clubdj_jazz . "', 
            '" . (int) $clubdj_country . "', 
            '" . (int) $clubdj_world . "', 
            '" . (int) $clubdj_monday . "', 
            '" . (int) $clubdj_tuesday . "', 
            '" . (int) $clubdj_wednesday . "', 
            '" . (int) $clubdj_thursday . "', 
            '" . (int) $clubdj_friday . "', 
            '" . (int) $clubdj_saturday . "', 
            '" . (int) $clubdj_sunday . "', 
            '" . (int) $clubdj_varies . "', 
            '" . addslashes($clubdj_city) . "', 
            '" . addslashes($clubdj_state) . "', 
            '" . addslashes($clubdj_intcountry) . "', 
            '" . addslashes($mixtapedj_name) . "', 
            '" . addslashes($mixtapedj_type) . "', 
            '" . addslashes($mixtapedj_schedule) . "', 
            '" . addslashes($mixtapedj_distribution) . "', 
            '" . addslashes($satellitedj_stationname) . "', 
            '" . addslashes($satellitedj_showname) . "', 
            '" . addslashes($satellitedj_channelname) . "', 
            '" . addslashes($satellitedj_channelnumber) . "', 
            '" . (int) $satellitedj_monday . "', 
            '" . (int) $satellitedj_tuesday . "', 
            '" . (int) $satellitedj_wednesday . "', 
            '" . (int) $satellitedj_thursday . "', 
            '" . (int) $satellitedj_friday . "', 
            '" . (int) $satellitedj_saturday . "', 
            '" . (int) $satellitedj_sunday . "', 
            '" . addslashes($satellitedj_showtime) . "', 
            '" . addslashes($internetdj_stationwebsite) . "', 
            '" . addslashes($internetdj_showtype) . "', 
            '" . addslashes($internetdj_showname) . "', 
            '" . (int) $internetdj_monday . "', 
            '" . (int) $internetdj_tuesday . "', 
            '" . (int) $internetdj_wednesday . "', 
            '" . (int) $internetdj_thursday . "', 
            '" . (int) $internetdj_friday . "', 
            '" . (int) $internetdj_saturday . "', 
            '" . (int) $internetdj_sunday . "', 
            '" . (int) $internetdj_varies . "', 
            '" . addslashes($internetdj_showtime) . "', 
            '" . addslashes($collegedj_callletters) . "', 
            '" . addslashes($collegedj_collegename) . "', 
            '" . addslashes($collegedj_stationfrequency) . "', 
            '" . addslashes($collegedj_showtype) . "', 
            '" . addslashes($collegedj_showname) . "', 
            '" . (int) $collegedj_monday . "', 
            '" . (int) $collegedj_tuesday . "', 
            '" . (int) $collegedj_wednesday . "', 
            '" . (int) $collegedj_thursday . "', 
            '" . (int) $collegedj_friday . "', 
            '" . (int) $collegedj_saturday . "', 
            '" . (int) $collegedj_sunday . "', 
            '" . (int) $collegedj_varies . "', 
            '" . addslashes($collegedj_showtime) . "', 
            '" . addslashes($collegedj_city) . "', 
            '" . addslashes($collegedj_state) . "', 
            '" . addslashes($collegedj_intcountry) . "', 
            '" . addslashes($piratedj_stationfrequency) . "', 
            '" . addslashes($piratedj_showname) . "', 
            '" . (int) $piratedj_monday . "', 
            '" . (int) $piratedj_tuesday . "', 
            '" . (int) $piratedj_wednesday . "', 
            '" . (int) $piratedj_thursday . "', 
            '" . (int) $piratedj_friday . "', 
            '" . (int) $piratedj_saturday . "', 
            '" . (int) $piratedj_sunday . "', 
            '" . (int) $piratedj_varies . "', 
            '" . addslashes($piratedj_showtime) . "')");
        } */

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

        $managementQuery = DB::select("SELECT id FROM members_radio_station where member = ?", [$newMemberId]);

        $managementRows = count($managementQuery);
/*         if (strpos($programTime, "'") !== false)       $programTime = str_replace("'", "\'", $programTime);
        if (strpos($airTime, "'") !== false)       $airTime = str_replace("'", "\'", $airTime);
        if (strpos($musicTime, "'") !== false)       $musicTime = str_replace("'", "\'", $musicTime); */
        /* if ($managementRows > 0) {

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
                                WHERE member = '" . (int) $newMemberId . "'");
        } else {

            DB::select("INSERT INTO `members_radio_station` (`member`, `radiotype_musicdirector`, `radiotype_programdirector`, `radiotype_jock`, `radiotype_promotion`, `radiotype_production`, `radiotype_sales`, `radiotype_tech`, `stationcallletters`, `stationfrequency`, `stationname`, `programdirector_stationcallletters`, `programdirector_host`, `programdirector_showname`, `programdirector_showtime`, `programdirector_monday`, `programdirector_tuesday`, `programdirector_wednesday`, `programdirector_thursday`, `programdirector_friday`, `programdirector_saturday`, `programdirector_sunday`, `programdirector_varies`, `musicdirector_stationcallletters`, `musicdirector_host`, `musicdirector_showname`, `musicdirector_showtime`, `musicdirector_monday`, `musicdirector_tuesday`, `musicdirector_wednesday`, `musicdirector_thursday`, `musicdirector_friday`, `musicdirector_saturday`, `musicdirector_sunday`, `musicdirector_varies`, `onairpersonality_showname`, `onairpersonality_showtime`, `onairpersonality_monday`, `onairpersonality_tuesday`, `onairpersonality_wednesday`, `onairpersonality_thursday`, `onairpersonality_friday`, `onairpersonality_saturday`, `onairpersonality_sunday`, `onairpersonality_varies`) 
                VALUES (
                    '" . (int) $newMemberId . "',
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

        $managementQuery = DB::select("SELECT id FROM members_mass_media where member = ?", [$newMemberId]);

        $managementRows = count($managementQuery);

        /* if ($managementRows > 0) {

            DB::select("update members_mass_media set mediatype_tvfilm = ?, mediatype_publication = ?, mediatype_newmedia = ?, mediatype_newsletter = ?, media_name = ?, media_website = ?, media_department = ? where member = ?", [$massTv, $massPublication, $massDotcom, $massNewsletter, $massName, $massWebsite, $massDepartment, $newMemberId]);
        } else {

            DB::select("insert into `members_mass_media` (`member`, `mediatype_tvfilm`, `mediatype_publication`, `mediatype_newmedia`, `mediatype_newsletter`, `media_name`, `media_website`, `media_department`) values (?, ?, ?, ?, ?, ?, ?, ?)", [$newMemberId, $massTv, $massPublication, $massDotcom, $massNewsletter, $massName, $massWebsite, $massDepartment]);
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

        $managementQuery = DB::select("SELECT id FROM members_record_label where member = ?", [$newMemberId]);

        $managementRows = count($managementQuery);

        /* if ($managementRows > 0) {

            DB::select("update members_record_label set labeltype_major = ?, labeltype_indy = ?, labeltype_distribution = ?, label_name = ?, label_department = ? where member = ?", [$recordMajor, $recordIndy, $recordDistribution, $recordName, $recordDepartment, $newMemberId]);
        } else {

            DB::select("insert into `members_record_label` (`member`, `labeltype_major`, `labeltype_indy`, `labeltype_distribution`, `label_name`, `label_department`) values (?, ?, ?, ?, ?, ?)", [$newMemberId, $recordMajor, $recordIndy, $recordDistribution, $recordName, $recordDepartment]);
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

        $managementQuery = DB::select("SELECT id FROM members_management where member = ?", [$newMemberId]);

        $managementRows = count($managementQuery);

        /* if ($managementRows > 0) {

            DB::select("update members_management set managementtype_artist = ?, managementtype_tour = ?, managementtype_personal = ?, managementtype_finance = ?, management_name = ?, management_who = ?, management_industry = ? where member = ?", [$managementArtist, $managementTour, $managementPersonal, $managementFinance, $managementName, $managementWho, $managementIndustry, $newMemberId]);
        } else {

            DB::select("insert into `members_management` (`member`, `managementtype_artist`, `managementtype_tour`, `managementtype_personal`, `managementtype_finance`, `management_name`, `management_who`, `management_industry`) values (?, ?, ?, ?, ?, ?, ?, ?)", [$newMemberId, $managementArtist, $managementTour, $managementPersonal, $managementFinance, $managementName, $managementWho, $managementIndustry]);
        } */

        // clothing

        $clothingQuery = DB::select("SELECT id FROM members_clothing_apparel where member = ?", [$newMemberId]);

        $clothingRows = count($clothingQuery);

        /* if ($clothingRows > 0) {

            DB::select("update members_clothing_apparel set clothing_name = ?, clothing_department = ? where member = ?", [$clothingName, $clothingDepartment, $newMemberId]);
        } else {

            DB::select("insert into `members_clothing_apparel` (`member`, `clothing_name`, `clothing_department`) values (?, ?, ?)", [$newMemberId, $clothingName, $clothingDepartment]);
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

        $promoterQuery = DB::select("SELECT id FROM members_promoter where member = ?", [$newMemberId]);

        $promoterRows = count($promoterQuery);

 /*        if (strpos($promoterName, "'") !== false)    $promoterName = str_replace("'", "\'", $promoterName); */

        /* if ($promoterRows > 0) {

            DB::select("update members_promoter set promotertype_indy = ?, promotertype_club = ?, promotertype_event = ?, promotertype_street = ?, promoter_name = ?, promoter_department = ?, promoter_website = ? where member = ?", [$promoterIndy, $promoterClub, $promoterSpecial, $promoterStreet, $promoterName, $promoterDepartment, $promoterWebsite, $newMemberId]);

            //$this->db->query("update members_promoter set promotertype_indy = '$promoterIndy', promotertype_club = '$promoterClub', promotertype_event = '$promoterSpecial', promotertype_street = '$promoterStreet', promoter_name = '$this->db->escape($promoterName)', promoter_department = '$promoterDepartment', promoter_website = '$promoterWebsite' where member = '". $newMemberId . "'");
        } else {

            DB::select("insert into `members_promoter` (`member`, `promotertype_indy`, `promotertype_club`, `promotertype_event`, `promotertype_street`, `promoter_name`, `promoter_department`, `promoter_website`) values (?, ?, ?, ?, ?, ?, ?, ?)", [$newMemberId, $promoterIndy, $promoterClub, $promoterSpecial, $promoterStreet, $promoterName, $promoterDepartment, $promoterWebsite]);

            //$this->db->query("insert into `members_promoter` (`member`, `promotertype_indy`, `promotertype_club`, `promotertype_event`, `promotertype_street`, `promoter_name`, `promoter_department`, `promoter_website`) values ('". $newMemberId ."', '". $promoterIndy ."', '". $promoterClub ."', '". $promoterSpecial ."', '". $promoterStreet ."', '". $this->db->escape($promoterName) ."', '". $promoterDepartment ."', '". $promoterWebsite ."')");
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

        $specialQuery = DB::select("SELECT id FROM members_special_services where member = ?", [$newMemberId]);

        $specialRows = count($specialQuery);

        /* if ($specialRows > 0) {

            DB::select("update members_special_services set servicestype_corporate = ?, servicestype_graphicdesign = ?, servicestype_webdesign = ?, servicestype_other = ?, services_name = ?, services_website = ? where member = ?", [$specialCorporate, $specialGraphic, $specialWeb, $specialOther, $specialName, $specialWebsite, $newMemberId]);
        } else {

            DB::select("insert into `members_special_services` (`member`, `servicestype_corporate`, `servicestype_graphicdesign`, `servicestype_webdesign`, `servicestype_other`, `services_name`, `services_website`) values (?, ?, ?, ?, ?, ?, ?)", [$newMemberId, $specialCorporate, $specialGraphic, $specialWeb, $specialOther, $specialName, $specialWebsite]);
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

        $productionQuery = DB::select("SELECT id FROM members_production_talent where member = ?", [$newMemberId]);

        $productionRows = count($productionQuery);

        /* if ($productionRows > 0) {

            DB::select("update members_production_talent set productiontype_artist = ?, productiontype_producer = ?, productiontype_choreographer = ?, productiontype_sound = ?, production_name = ? where member = ?", [$productionArtist, $productionProducer, $productionChoregrapher, $productionSound, $productionName, $newMemberId]);
        } else {

           DB::select("insert into `members_production_talent` (`member`, `productiontype_artist`, `productiontype_producer`, `productiontype_choreographer`, `productiontype_sound`, `production_name`) values (?, ?, ?, ?, ?, ?)", [$newMemberId, $productionArtist, $productionProducer, $productionChoregrapher, $productionSound, $productionName]);
        } */

        $result = 1;

        // }

        return  $result;
    }
	
	public static function reAddMember4($data, $newMemberId)
    {

        extract($data);
        if (!(isset($computerType))) {
            $computerType = '';
        }
        if (!(isset($mixerType))) {
            $mixerType = '';
        }
        if (!(isset($headphones))) {
            $headphones = '';
        }
        if (!(isset($gameSystem))) {
            $gameSystem = '';
        }
        if (!(isset($hatSize))) {
            $hatSize = '';
        }
        if (!(isset($pantsSize))) {
            $pantsSize = '';
        }
        if (!(isset($turntablesType))) {
            $turntablesType = '';
        }
        if (!(isset($needlesType))) {
            $needlesType = '';
        }
        if (!(isset($cellPhone))) {
            $cellPhone = '';
        }
        if (!(isset($mp3Player))) {
            $mp3Player = '';
        }
        if (!(isset($shirtSize))) {
            $shirtSize = '';
        }
        if (!(isset($shoeSize))) {
            $shoeSize = '';
        }
        if (!(isset($audioQuality))) {
            $audioQuality = '';
        }

        $query = DB::select("UPDATE `members` 
                                    SET `computer` = '" . addslashes($computerType) . "', 
                                        `mixer_type` = '" . addslashes($mixerType) . "', 
                                        `headphones` = '" . addslashes($headphones) . "', 
                                        `game_system` = '" . addslashes($gameSystem) . "', 
                                        `hat_size` = '" . addslashes($hatSize) . "', 
                                        `pants_size` = '" . addslashes($pantsSize) . "', 
                                        `turntables_type` = '" . addslashes($turntablesType) . "', 
                                        `needles_type` = '" . addslashes($needlesType) . "', 
                                        `player` = '" . addslashes($mp3Player) . "', 
                                        `cell_phone` = '" . addslashes($cellPhone) . "', 
                                        `shirt_size` = '" . addslashes($shirtSize) . "', 
                                        `shoe_size` = '" . addslashes($shoeSize) . "', 
                                        `audioQuality` = '" . addslashes($audioQuality) . "',
                                        `resubmission` = '1'
                                    WHERE `id` = " . (int) $newMemberId);

        $query = DB::select("SELECT `id`, `fname`, `email` FROM `members` WHERE `id` = " . (int) $newMemberId);

        $result['numRows'] = count($query);

        $result['data']  = $query;

        return  $result;
    }

}
