<!DOCTYPE html>
<html>

<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="800" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr bgcolor="#660033">
      <td height="13" colspan="3" valign="top"></td>
  </tr>
    <tr>
      <td width="23" valign="top" bgcolor="#660033">&nbsp;</td>
      <td width="754" valign="top" bgcolor="#EAEAEA" style="padding:9px;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="2"><table width="500" border="0" align="center" cellpadding="2" cellspacing="2">
            <tr>
              <td colspan="2" bgcolor="#D8D8D8"><div align="center"><img src="<?php echo isset($data['appUrl']) ? urldecode($data['appUrl']) : ''; ?>/assets_admin/assets/img/logo.png" border="0"></div></td>
              </tr>
            <tr>
              <td width="50%" bgcolor="#D8D8D8"><font color="#660033" size="2" face="Arial, Helvetica, Verdana"><strong>Track Name:</strong></font></td>
              <td width="50%" bgcolor="#D8D8D8"><font color="#333333" size="2" face="Arial, Helvetica, Verdana"><strong><?php echo isset($data['trackname']) ? urldecode($data['trackname']) : ''; ?></strong></font></td>
            </tr>
            <tr>
              <td width="50%" bgcolor="#D8D8D8"><font color="#660033" size="2" face="Arial, Helvetica, Verdana"><strong>Track Artist:</strong></font></td>
              <td width="50%" bgcolor="#D8D8D8"><font color="#333333" size="2" face="Arial, Helvetica, Verdana"><strong><?php echo isset($data['tArtist']) ? urldecode($data['tArtist']) : ''; ?>&nbsp;</strong></font></td>
            </tr>
            <tr>
              <td width="50%" bgcolor="#D8D8D8"><font color="#660033" size="2" face="Arial, Helvetica, Verdana"><strong>DJ eMail:</strong></font></td>
              <td width="50%" bgcolor="#D8D8D8"><font color="#333333" size="2" face="Arial, Helvetica, Verdana"><strong><a href="mailto:<?php echo urldecode($data['userEmailId']); ?>"><?php echo urldecode($data['userEmailId']); ?></a>&nbsp;</strong></font></td>
            </tr>
            <tr>
              <td width="50%" bgcolor="#D8D8D8"><font color="#660033" size="2" face="Arial, Helvetica, Verdana"><strong>DJ Stagename:</strong></font></td>
              <td width="50%" bgcolor="#D8D8D8"><font color="#333333" size="2" face="Arial, Helvetica, Verdana"><strong><?php echo isset($data['memstagename']) ? urldecode($data['memstagename']) : ''; ?>&nbsp;</strong></font></td>
            </tr>
            <?php if(!empty($data['djMembrTypeIs'])){ ?>
            <tr>
              <td width="50%" bgcolor="#D8D8D8"><font color="#660033" size="2" face="Arial, Helvetica, Verdana"><strong>DJ Type:</strong></font></td>
              <td width="50%" bgcolor="#D8D8D8"><font color="#333333" size="2" face="Arial, Helvetica, Verdana"><strong><?php echo isset($data['djMembrTypeIs']) ? urldecode($data['djMembrTypeIs']) : ''; ?>&nbsp;</strong></font></td>
            </tr>
            <?php } ?>
            <tr>
              <td width="50%" bgcolor="#D8D8D8"><font color="#660033" size="2" face="Arial, Helvetica, Verdana"><strong>Playlist Contributor:</strong></font></td>
              <td width="50%" bgcolor="#D8D8D8"><font color="#333333" size="2" face="Arial, Helvetica, Verdana"><strong><?php echo isset($data['djPlaylistContributor']) ? urldecode($data['djPlaylistContributor']) : ''; ?>&nbsp;</strong></font></td>
            </tr>
            <?php if(isset($data['djMixer']) && $data['djMixer'] == 'Yes'){ ?>
            <tr>
              <td width="50%" bgcolor="#D8D8D8"><font color="#660033" size="2" face="Arial, Helvetica, Verdana"><strong>Dj Mixer Clubname:</strong></font></td>
              <td width="50%" bgcolor="#D8D8D8"><font color="#333333" size="2" face="Arial, Helvetica, Verdana"><strong><?php echo isset($data['djMixerClubname']) ? urldecode($data['djMixerClubname']) : ''; ?>&nbsp;</strong></font></td>
            </tr> 
           <?php
            }
            if(isset($data['djRadioStation']) && $data['djRadioStation'] == 'Yes'){
           ?>          
            <tr>
              <td width="50%" bgcolor="#D8D8D8"><font color="#660033" size="2" face="Arial, Helvetica, Verdana"><strong>Radio Station name:</strong></font></td>
              <td width="50%" bgcolor="#D8D8D8"><font color="#333333" size="2" face="Arial, Helvetica, Verdana"><strong><?php echo isset($data['djRadioStationname']) ? urldecode($data['djRadioStationname']) : ''; ?>&nbsp;</strong></font></td>
            </tr> 
           <?php } ?>

            <tr>
              <td width="50%" bgcolor="#D8D8D8"><font color="#660033" size="2" face="Arial, Helvetica, Verdana"><strong>DJ Location:</strong></font></td>
              <td width="50%" bgcolor="#D8D8D8"><font color="#333333" size="2" face="Arial, Helvetica, Verdana"><strong><?php echo isset($data['memaddress']) ? urldecode($data['memaddress']) : ''; ?>&nbsp;</strong></font></td>
            </tr>
            <tr>
              <td width="50%" bgcolor="#D8D8D8"><font color="#660033" size="2" face="Arial, Helvetica, Verdana"><strong>Feedback:</strong></font></td>
              <td width="50%" bgcolor="#D8D8D8"><font color="#333333" size="2" face="Arial, Helvetica, Verdana"><strong><?php echo isset($data['review']) ? urldecode($data['review']) : ''; ?>&nbsp;</strong></font></td>
            </tr>
            <tr>
              <td width="50%" bgcolor="#D8D8D8"><font color="#660033" size="2" face="Arial, Helvetica, Verdana"><strong>Rating:</strong></font></td>
              <td width="50%" bgcolor="#D8D8D8"><font color="#333333" size="2" face="Arial, Helvetica, Verdana"><strong><?php echo isset($data['rating']) ? urldecode($data['rating']) : ''; ?>&nbsp;</strong></font></td>
            </tr>
            <tr>
              <td width="50%" bgcolor="#D8D8D8"><font color="#660033" size="2" face="Arial, Helvetica, Verdana"><strong>Social Media:</strong></font></td>
              <td width="50%" bgcolor="#D8D8D8"><font color="#333333" size="2" face="Arial, Helvetica, Verdana"><?php if(isset($data['fb']) && !empty($data['fb'])){ ?><p><strong>Facebook: <?php echo isset($data['fb']) ? urldecode($data['fb']) : ''; ?>&nbsp;</strong></p><?php } if(isset($data['twt']) && !empty($data['twt'])){ ?><p><strong>Twitter: <?php echo isset($data['twt']) ? urldecode($data['twt']) : ''; ?>&nbsp;</strong></p><?php } if(isset($data['insta']) && !empty($data['insta'])){ ?> <p><strong>Instagram: <?php echo isset($data['insta']) ? urldecode($data['insta']) : ''; ?>&nbsp;</strong></p><?php } if(isset($data['linkedin']) && !empty($data['linkedin'])){ ?><p><strong>Linkedin: <?php echo isset($data['linkedin']) ? urldecode($data['linkedin']) : ''; ?>&nbsp;</strong></p><?php } ?></font></td>
            </tr>            
            <tr bgcolor="#D8D8D8">
              <td height="40" colspan="2"><div align="center"><font color="#333333" size="2" face="Arial, Helvetica, Verdana"><strong><a href="<?php echo isset($data['appUrl']) ? urldecode($data['appUrl']) : ''; ?>/Client_messages_conversation?mid=<?php echo urldecode($data['djMemId']); ?>" target="_blank"> Connect with <?php if(!empty($data['djsName'])){ echo urldecode($data['djsName']); }else{ echo urldecode($data['name']); } ?> Direct!</a></strong></font></div></td>
            </tr>
            <tr bgcolor="#D8D8D8">
              <td height="40" colspan="2"><div align="center"><font color="#333333" size="2" face="Arial, Helvetica, Verdana"><strong><a href="<?php echo isset($data['appUrl']) ? urldecode($data['appUrl']) : ''; ?>/Client_track_review?tId=<?php echo urldecode($data['trackIdIs']); ?>" target="_blank">Click here to view full report</a></strong></font></div></td>
            </tr>            
            <tr>
              <td colspan="2"><div align="center"><font size="1" face="Arial, Helvetica, Verdana"><strong>For more info on this service email <a href="mailto:business@digiwaxx.com">business@digiwaxx.com</a>                or call <a href="tel:8006651259">(800) 665-1259</a></strong></font></div></td>
            </tr>
          </table></td>
        </tr>
      </table></td>
      <td width="23" valign="top" bgcolor="#660033">&nbsp;</td>
  </tr>
    <tr bgcolor="#660033">
        <td height="13" colspan="3" valign="top"></td>
    </tr>
</table>
</body>

</html>
