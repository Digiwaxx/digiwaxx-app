<? include("../Includes/functions.php"); ?>
<? securityCheck(array("admin")); ?>
<?

	if ($submit){
	
		//check for all fields
		if(!$emails){ $error .= " Not all fields have been completed.<br>"; }
		
		$emails = str_replace(",","\n",$emails);
		$emails = explode("\n",$emails);
		$emails = array_unique($emails);
		
		//get template info
		$template_result = db_query("SELECT * FROM templates WHERE id='".$template."'");
		$template_info = mysql_fetch_array($template_result);

		//get track info
		$track_result = db_query("SELECT * FROM tracks WHERE id='".$id."'");
		$track_info = mysql_fetch_array($track_result);
		
		//verify that the track img matches the template
		//$img_dims = getImgDimensions($img_path.$track_info["img"]);
		//if($img_dims["height"]!=$template_info["imgheight"]){ $error.= "Track image height does not match this template. The track image must have a height of ".$template_info["imgheight"]." to be able to use this template."; }
		//if($img_dims["width"]!=$template_info["imgwidth"]){ $error.= "Track image width does not match this template. The track image must have a width of ".$template_info["imgwidth"]." to be able to use this template."; }
		
		if(!$error){
		
			require("../Includes/class.phpmailer.php");
			
			//sort out track information
			$redirect = "&redirectpage=track&redirectid=".$id;
			//$emailimg = "<a href=\"".clean($track_info["link"])."\"><img src=\"http://www.digiwaxx.com/ImagesUp/".$track_info["img"]."\" border=\"0\"></a>";
			$artist = clean($track_info["artist"]);
			$title = clean($track_info["title"]);
			$subjectartist = $track_info["artist"];
			$subjecttitle = $track_info["title"];
			$producer = clean($track_info["producer"]);
			$time = clean($track_info["time"]);
			$label = clean($track_info["label"]);
			$album = clean($track_info["album"]);
			$productgender=clean($track_info["product_gender"]);
			$producttechnology=clean($track_info["product_technology"]);
			$productdetails=clean($track_info["product_details"]);
			$productmodel=clean($track_info["model"]);
			$productname=clean($track_info["product_name"]);
			if($track_info["radio"]){ $versions.="Radio "; }
			if($track_info["dirty"]){ $versions.="Dirty "; }
			if($track_info["clean"]){ $versions.="Clean "; }
			if($track_info["instrumental"]){ $versions.="Instrumental "; }
			
			//display all the available versions
			$version_result = db_query("SELECT * FROM tracks_mp3s WHERE track='".$id."' ORDER BY pos");
			
			$version="<ul>";
				
			while($version_info = mysql_fetch_array($version_result)){
				$versions .= "<li>".clean($version_info["version"])."</li>";
			}
			
			$versions = substr($versions,0,-2);
			$version .= "</ul>";
			
			$logo_array = explode(",",$track_info["logos"]);
			foreach($logo_array as $logo){
				$result = db_query("SELECT * FROM logos WHERE id='".$logo."'");
				$logo_info = mysql_fetch_array($result);
				if($logo_info["img"]){
					$logo_line .= "<span style=\"padding:5px;\">";
					if($logo_info["url"]){ $logo_line .= "<a href=\"".clean($logo_info["url"])."\" target=\"_blank\">";}
					$logo_line .= "<img src=\"http://www.digiwaxx.com/Logos/".$logo_info["img"]."\" border=\"0\">";
					if($logo_info["url"]){ $logo_line .= "</a>";}
					$logo_line .= "</span><br>";
				}
			}
			
			//organize and display contacts
			$contacts_result = db_query("SELECT * FROM tracks_contacts WHERE track='".$id."'");
			while($contact_info = mysql_fetch_array($contacts_result)){
				if($contact_info["company"]){ $contacts.= clean($contact_info["company"])."<br>"; }
				$contacts.= clean($contact_info["name"])."<br>";
				if($contact_info["title"]){ $contacts.= clean($contact_info["title"])."<br>"; }
				if($contact_info["email"]){ $contacts.= clean($contact_info["email"])."<br>"; }
				if($contact_info["phone"]){ $contacts.= "Phone: ".clean($contact_info["phone"])."<br>"; }
				if($contact_info["mobile"]){ $contacts.= "Mobile: ".clean($contact_info["mobile"])."<br>"; }
				$contacts.= "<br><br>";
			}
			
				//find label reps
				$labelreps_result = db_query("SELECT * FROM track_label_reps WHERE track_id='".$id."'");
				while($labelreps = mysql_fetch_array($labelreps_result)){
					$client_company = db_query("SELECT * FROM clients WHERE id='".$labelreps["client_id"]."'");
					$client_company_info = mysql_fetch_array($client_company);
					
					$client_company_name = $client_company_info["name"];
					
					$labelrep = db_query("SELECT * FROM client_contacts WHERE deleted ='"."0"."' AND id='".$labelreps["label_rep_id"]."'");
					$labelrep_info = mysql_fetch_array($labelrep);
					
					//if($client_company_name){ $contacts.= clean($client_company_name)."<br>"; }
					$contacts.= clean($labelrep_info["name"])."<br>";
					if($labelrep_info["title"]){ $contacts.= clean($labelrep_info["title"])."<br>"; }
					if($labelrep_info["email"]){ $contacts.= "<a href=\"mailto:".clean($labelrep_info["email"])."\">".clean($labelrep_info["email"])."</a><br>"; }
					if($labelrep_info["phone"]){ $contacts.= "Phone: ".clean($labelrep_info["phone"])."<br>"; }
					if($labelrep_info["mobile"]){ $contacts.= "Mobile: ".clean($labelrep_info["mobile"])."<br>"; }
					$contacts.= "<br><br>";
				}
			
			//is there more info?
			if($track_info["moreinfo"]){
				$moreinfo .= "<tr bgcolor=\"#D8D8D8\">";
				$moreinfo .= "	<td height=\"40\" colspan=\"2\"><div align=\"center\"><font color=\"#333333\" size=\"2\" face=\"Arial, Helvetica, Verdana\">".cleanNonHtml($track_info["moreinfo"])."</strong></font></div></td>";
				$moreinfo .= "</tr>";
				
			}else{
			
				$moreinfo="";
				
			}
			
			ob_end_flush();
		
			$i=1;
			$total = count($emails);
			$fromname="Digital Waxx Service";
			$fromemail="info@digiwaxx.com";
        	$subject = "DIGITAL WAXX PRESS RELEASE  |  ".cleanNonHtml($subjecttitle);
					
			foreach($emails as $email){
										
					if($email["uname"]){
						//user is registered
						$link = "http://www.digiwaxx.com/Members/login_form.php?".$redirect;
					}else{
						//user is unregistered
						$link = "http://www.digiwaxx.com/Members/register.php?".$redirect;
					}
										
					//load the templates
					if($track_info["type"]=="product"){
						$html = file_get_contents("Templates/".substr($template_info["filename"],0,-4)."_product.php");
					}else{
						$html = file_get_contents("Templates/".$template_info["filename"]);
					}
					
					//add the test message
					$html = "<font color=\"#ff0000\" size=\"3\" face=\"Arial, Helvetica, Verdana\"><b>THIS IS YOUR DIGIWAXX PROOF. <br/>If you would like make changes, please contact your account executive directly.<br>
DO NOT REPLY to this e-mail.</b></font><br/>".$html;
					
					//replace appropriate areas of the template
					$html = str_replace("*img*",$emailimg,$html);
					//$html = str_replace("*link*",$link,$html);
					$html = str_replace("*artist*",$artist,$html);
					$html = str_replace("*title*",$title,$html);
					$html = str_replace("*producer*",$producer,$html);
					$html = str_replace("*time*",$time,$html);
					$html = str_replace("*label*",$label,$html);
					$html = str_replace("*album*",$album,$html);
					$html = str_replace("*versions*",$versions,$html);
					$html = str_replace("*label*",$label,$html);
					$html = str_replace("*contact*",$contacts,$html);
					$html = str_replace("*memberid*",$email["id"],$html);
					$html = str_replace("*logo*",$logo_line,$html);
					$html = str_replace("*productname*",$productname,$html);
					$html = str_replace("*productmodel*",$productmodel,$html);
					$html = str_replace("*productdetails*",$productdetails,$html);
					$html = str_replace("*producttechnology*",$producttechnology,$html);
					$html = str_replace("*productgender*",$productgender,$html);
					$html = str_replace("*moreinforow*",$moreinfo,$html);
				
					$mail = new PHPMailer();
					$mail->From     = $fromemail;
					$mail->Sender     = $fromemail;
					$mail->FromName = $fromname; 
					$mail->IsHTML(true);
					$mail->Subject  =  $subject;
					$mail->Body =  $html;
					$mail->AltBody  =  $text_only;
			
					$mail->AddAddress(stripslashes(urldecode($email)));
					$mail->Send();
					$mail->ClearAddresses();
    				$mail->ClearAttachments();
					
					$html="";
					
					echo $i."/".$total." ".$email." sent succesfully<br>";
					flush();
					
					$i++;
					
					set_time_limit(1);
				
			}
		}
				
	}
		
			
?>
<? include("header.php"); ?>
<form name="pword" action="#" method="POST">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr bgcolor="#F8F8F8">
    <td width="40"><img src="Images/box_topleft.gif" width="40" height="40"></td>
    <td height="24" valign="bottom" background="Images/box_top.gif" bgcolor="#F8F8F8"><img src="Images/title_sendpreviewmails.gif" width="192" height="13"></td>
    <td width="40"><img src="Images/box_topright.gif" width="40" height="40"></td>
  </tr>
  <tr bgcolor="#F8F8F8">
    <td width="40" background="Images/box_left.gif">&nbsp;</td>
    <td bgcolor="#F8F8F8"><table width="94%" border="0" align="center" cellpadding="2" cellspacing="2">
      <tr>
        <td height="17" colspan="2" valign="bottom" class="contentgray" style="color:red;"><?= $error; ?>
</td>
      </tr>
      <tr>
        <td height="5" colspan="2"><hr width="100%" size="1" noshade style="background-color:gray;">
        </td>
      </tr>
            <tr bgcolor="#F0F0F0">
        <td height="17" bgcolor="#F0F0F0" class="content" style="padding: 2 5 2 5;">Track:</td>
        <td height="17" valign="top" class="contentgray" style="padding: 2 5 2 5;"><a href="track_manage.php?view=1&id=<?= $id; ?>">
          <?= getTrackName($id); ?>
        </a> </td>
      </tr>
            <tr>
              <td bgcolor="#F0F0F0" class="content" style="padding: 2 5 2 5;">Template:</td>
              <td bgcolor="#F0F0F0" style="padding: 2 5 2 5;"><select name="template" id="template" class="input_normal">
                <?  
					
					$result = db_query("SELECT * FROM templates");
							
					while($template_list = mysql_fetch_array($result)){
							echo 	"<option value=\"".$template_list["id"]."\"";
							if ($template_list["id"]==$template){ echo " selected";} 
							echo ">".clean($template_list["name"])."</option>";	
					}
			
					?>
              </select></td>
            </tr>
          <tr>
        <td bgcolor="#F0F0F0" class="content" style="padding: 2 5 2 5;">E-Mail
          Addresses:*<br>
          (One per line)</td>
        <td bgcolor="#F0F0F0" style="padding: 2 5 2 5;"><textarea name="emails" cols="40" rows="20" class="input_textfield" id="emails"></textarea>
        </td>
      </tr>
      <tr>
        <td height="17" colspan="2" valign="top" class="contentgray">
          <input type="hidden" name="id" value="<? echo $id; ?>">
      * = Required Field</td>
      </tr>
      <tr>
        <td height="40" colspan="2"><input type="submit" class="button_normal" value="Submit" name="submit">
        </td>
      </tr>
    </table></td>
    <td width="40" background="Images/box_right.gif">&nbsp;</td>
  </tr>
  <tr bgcolor="#F8F8F8">
    <td width="40"><img src="Images/box_botleft.gif" width="40" height="40"></td>
    <td height="24" background="Images/box_bottom.gif">&nbsp;</td>
    <td width="40"><img src="Images/box_botright.gif" width="40" height="40"></td>
  </tr>
</table>
</form>
<? include("footer.php"); ?>