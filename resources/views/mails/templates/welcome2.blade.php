<?php

?>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="800" border="0" align="center" cellpadding="0" cellspacing="0">
	<tr>
		<td height="78" colspan="3" background="http://www.digiwaxx.com/Admin/Images/email_bg.gif" style="padding-left:20px;">
			<img src="http://www.digiwaxx.com/Admin/Images/email_title.gif" width="342" height="78" alt="">		</td>
	</tr>
	<tr>
	  <td height="2" colspan="3" valign="top"><img src="http://www.digiwaxx.com/Admin/Images/spacer.gif" width="1" height="1"></td>
  </tr>
	<tr bgcolor="#660033">
	  <td height="13" colspan="3" valign="top"><img src="http://www.digiwaxx.com/Admin/Images/spacer.gif" width="1" height="1"></td>
  </tr>
	<tr>
	  <td width="23" valign="top" bgcolor="#660033">&nbsp;</td>
      <td width="754" valign="top" bgcolor="#EAEAEA" style="padding:9px;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="315" valign="top"><?php echo $data['img'] ?></td>
          <td valign="top"><a href="*link*"><img src="http://www.digiwaxx.com/Admin/Images/email_letter.jpg" border="0"></a></td>
        </tr>
        <tr>
          <td colspan="2"><table width="500" border="0" align="center" cellpadding="2" cellspacing="2">
            <tr>
              <td colspan="2" bgcolor="#D8D8D8"><div align="center"><?php echo isset($data['logo']) ? $data['logo'] : ''; ?></div></td>
              </tr>
            <tr>
              <td width="50%" bgcolor="#D8D8D8"><font color="#660033" size="2" face="Arial, Helvetica, Verdana"><strong>Artist(s):</strong></font></td>
              <td width="50%" bgcolor="#D8D8D8"><font color="#333333" size="2" face="Arial, Helvetica, Verdana"><strong><?php echo isset($data['artist']) ? $data['artist'] : ''; ?></strong></font></td>
            </tr>
            <tr>
              <td width="50%" bgcolor="#D8D8D8"><font color="#660033" size="2" face="Arial, Helvetica, Verdana"><strong>Title:</strong></font></td>
              <td width="50%" bgcolor="#D8D8D8"><font color="#333333" size="2" face="Arial, Helvetica, Verdana"><strong><?php echo isset($data['title']) ? $data['title'] : ''; ?>&nbsp;</strong></font></td>
            </tr>
            <tr>
              <td width="50%" bgcolor="#D8D8D8"><font color="#660033" size="2" face="Arial, Helvetica, Verdana"><strong>Producer(s):</strong></font></td>
              <td width="50%" bgcolor="#D8D8D8"><font color="#333333" size="2" face="Arial, Helvetica, Verdana"><strong><?php echo isset($data['producer']) ? $data['producer'] : ''; ?>&nbsp;</strong></font></td>
            </tr>
            <tr>
              <td width="50%" bgcolor="#D8D8D8"><font color="#660033" size="2" face="Arial, Helvetica, Verdana"><strong>Time:</strong></font></td>
              <td width="50%" bgcolor="#D8D8D8"><font color="#333333" size="2" face="Arial, Helvetica, Verdana"><strong><?php echo isset($data['time']) ? $data['time'] : ''; ?>&nbsp;</strong></font></td>
            </tr>
            <tr>
              <td width="50%" bgcolor="#D8D8D8"><font color="#660033" size="2" face="Arial, Helvetica, Verdana"><strong>Label:</strong></font></td>
              <td width="50%" bgcolor="#D8D8D8"><font color="#333333" size="2" face="Arial, Helvetica, Verdana"><strong><?php echo isset($data['label']) ? $data['label'] : ''; ?>&nbsp;</strong></font></td>
            </tr>
            <tr>
              <td width="50%" bgcolor="#D8D8D8"><font color="#660033" size="2" face="Arial, Helvetica, Verdana"><strong>Album:</strong></font></td>
              <td width="50%" bgcolor="#D8D8D8"><font color="#333333" size="2" face="Arial, Helvetica, Verdana"><strong><?php echo isset($data['album']) ? $data['album'] : ''; ?>&nbsp;</strong></font></td>
            </tr>
            <tr>
              <td width="50%" bgcolor="#D8D8D8"><font color="#660033" size="2" face="Arial, Helvetica, Verdana"><strong>Available Versions:</strong></font></td>
              <td width="50%" bgcolor="#D8D8D8"><font color="#333333" size="2" face="Arial, Helvetica, Verdana"><strong><?php echo isset($data['versions']) ? $data['versions'] : ''; ?>&nbsp;</strong></font></td>
            </tr>
            <tr>
              <td valign="top" bgcolor="#D8D8D8"><font color="#660033" size="2" face="Arial, Helvetica, Verdana"><strong>For
                    Vinyl, CD's, Freestyles, Drops, and more please reach out
                    to these contacts:<br>
                    <br>
Label Contact Information:</strong></font></td>
              <td valign="top" bgcolor="#D8D8D8"><font color="#333333" size="2" face="Arial, Helvetica, Verdana"><strong><?php echo isset($data['contact']) ? $data['contact']: ''; ?>&nbsp;</strong></font></td>
            </tr>
			<?php echo isset($data['moreinforow']) ? $data['moreinforow'] : ''; ?>
            <tr bgcolor="#D8D8D8">
              <td height="40" colspan="2"><div align="center"><font color="#333333" size="2" face="Arial, Helvetica, Verdana"><strong><a href="http://gpdemoprojects.com/working/digiwaxx/Login">Already
                        Registered? Click Here to Log in to your Account.</a></strong></font></div></td>
              </tr>
            <tr>
              <td colspan="2"><div align="center"><font size="1" face="Arial, Helvetica, Verdana"><strong><a href="http://gpdemoprojects.com/working/digiwaxx/Unsubscribe_member.php?id=<?php echo isset($data['member']) ? $data['member'] : ''; ?>&key=<?php echo isset($data['key']) ? $data['key'] : ''; ?>">Click Here to Unsubscribe
                from Digital Waxx Service</a><br>
                or paste this string in your browser window:<br>
                http://www.digiwaxx.com/Members/unsubscribe.php?id=<?php echo isset($data['member']) ? $data['member'] : ''; ?>&key=<?php echo isset($data['key']) ? $data['key'] : ''; ?>
              </strong></font></div></td>
            </tr>
            <tr>
              <td colspan="2"><div align="center"><font size="1" face="Arial, Helvetica, Verdana"><strong>For more info on this service email <a href="mailto:info@digiwaxx.com">info@digiwaxx.com</a>                or call 866.599.0186 x1</strong></font></div></td>
            </tr>
          </table></td>
        </tr>
      </table></td>
      <td width="23" valign="top" bgcolor="#660033">&nbsp;</td>
  </tr>
	<tr bgcolor="#660033">
		<td height="13" colspan="3" valign="top"><img src="http://www.digiwaxx.com/Admin/Images/spacer.gif" width="1" height="1"></td>
	</tr>
</table>
