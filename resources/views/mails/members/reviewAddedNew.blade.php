<!DOCTYPE html>
<html>
<body>
	<table style="width:700; margin: auto;">
		<tr>
			<td>
			<table style="width: 100%;border-collapse: collapse;">
				<tr>
					<td style="background: black; text-align: center; padding: 20px 10px; border-bottom: 3px solid #F564A9; ">
						<a href="https://digiwaxx.com" target="_blank" style="text-decoration: none;">
							<img src="<?php echo isset($data['appUrl']) ? urldecode($data['appUrl']) : ''; ?>/assets_admin/assets/img/digiwaxx-emaillogo.png" style="width: 200px;">
						</a>
					</td>
				</tr>
			</table>
			<table style="width:100%; background: #0B1023;border-collapse: collapse; font-family:hellavita">
				<tr>
					<td style="padding: 40px 30px 0px;">
						<table  style="width: 100%;  border-collapse:separate; border-spacing:03px 03px; font-size: 14px;">
							<tr>
								<td style="background:#2c3242; color:#53d0f8; font-family: helvetica;padding:10px;font-weight: 600;">Track Name:</td>
								<td style="background:#2c3242; color:#C7C7C7; font-family: helvetica;padding:10px;font-weight:400; "><?php echo isset($data['trackname']) ? urldecode($data['trackname']) : ''; ?></td>
							</tr>
							<tr>
								<td style="background:#2c3242; color:#53d0f8; font-family: helvetica;padding:10px;font-weight: 600;">Track Artist:</td>
								<td style="background:#2c3242; color:#C7C7C7; font-family: helvetica;padding:10px;font-weight: 400;"><?php echo isset($data['tArtist']) ? urldecode($data['tArtist']) : ''; ?></td>
							</tr>
							<tr>
								<td style="background:#2c3242; color:#53d0f8; font-family: helvetica;padding:10px;font-weight: 600;">DJ email:</td>
								<td style="background:#2c3242; color:#C7C7C7; font-family: helvetica;padding:10px;font-weight:400; "><a href="mailto:<?php echo urldecode($data['userEmailId']); ?>"><?php echo urldecode($data['userEmailId']); ?></a></td>
							</tr>
							<tr>
								<td style="background:#2c3242; color:#53d0f8; font-family: helvetica;padding:10px;font-weight: 600;">DJ Stagename:</td>
								<td style="background:#2c3242; color:#C7C7C7; font-family: helvetica;padding:10px;font-weight:400; "><?php echo isset($data['memstagename']) ? urldecode($data['memstagename']) : ''; ?></td>
							</tr>
							<tr>
								<td style="background:#2c3242; color:#53d0f8; font-family: helvetica;padding:10px;font-weight: 600;">DJ Type:</td>
								<td style="background:#2c3242; color:#C7C7C7; font-family: helvetica;padding:10px;font-weight:400; "><?php echo isset($data['djMembrTypeIs']) ? urldecode($data['djMembrTypeIs']) : ''; ?></td>
							</tr>
							<tr>
								<td style="background:#2c3242; color:#53d0f8; font-family: helvetica;padding:10px;font-weight: 600;">Playlist Contibuter:</td>
								<td style="background:#2c3242; color:#C7C7C7; font-family: helvetica;padding:10px;font-weight:400; "><?php echo isset($data['djPlaylistContributor']) ? urldecode($data['djPlaylistContributor']) : ''; ?></td>
							</tr>
							<tr>
								<td style="background:#2c3242; color:#53d0f8; font-family: helvetica;padding:10px;font-weight: 600;">DJ Mixer Clubname:</td>
								<td style="background:#2c3242; color:#C7C7C7; font-family: helvetica;padding:10px;font-weight:400; "><?php echo isset($data['djMixerClubname']) ? urldecode($data['djMixerClubname']) : ''; ?></td>
							</tr>
							<tr>
								<td style="background:#2c3242; color:#53d0f8; font-family: helvetica;padding:10px;font-weight: 600;">Radio Station Name:</td>
								<td style="background:#2c3242; color:#C7C7C7; font-family: helvetica;padding:10px;font-weight:400; "><?php echo isset($data['djRadioStationname']) ? urldecode($data['djRadioStationname']) : ''; ?></td>
							</tr>
							<tr>
								<td style="background:#2c3242; color:#53d0f8; font-family: helvetica;padding:10px;font-weight: 600;">DJ Location:</td>
								<td style="background:#2c3242; color:#C7C7C7; font-family: helvetica;padding:10px;font-weight:400; "><?php echo isset($data['memaddress']) ? urldecode($data['memaddress']) : ''; ?></td>
							</tr>
							<tr>
								<td style="background:#2c3242; color:#53d0f8; font-family: helvetica;padding:10px;font-weight: 600;">Social Media:</td>
								<td style="background:#2c3242; color:#C7C7C7; font-family: helvetica;padding:10px;font-weight:400; "><?php if(isset($data['fb']) && !empty($data['fb'])){ ?><p><strong>Facebook: <?php echo isset($data['fb']) ? urldecode($data['fb']) : ''; ?>&nbsp;</strong></p><?php } if(isset($data['twt']) && !empty($data['twt'])){ ?><p><strong>Twitter: <?php echo isset($data['twt']) ? urldecode($data['twt']) : ''; ?>&nbsp;</strong></p><?php } if(isset($data['insta']) && !empty($data['insta'])){ ?> <p><strong>Instagram: <?php echo isset($data['insta']) ? urldecode($data['insta']) : ''; ?>&nbsp;</strong></p><?php } if(isset($data['linkedin']) && !empty($data['linkedin'])){ ?><p><strong>Linkedin: <?php echo isset($data['linkedin']) ? urldecode($data['linkedin']) : ''; ?>&nbsp;</strong></p><?php } ?></td>
							</tr>							
							<tr>
								<td style="background:#2c3242; color:#53d0f8; font-family: helvetica;padding:10px;font-weight: 600;">Feedback:</td>
								<td style="background:#2c3242; color:#C7C7C7; font-family: helvetica;padding:10px;font-weight:400; "><?php echo isset($data['review']) ? urldecode($data['review']) : ''; ?></td>
							</tr>
							<tr>
								<td style="background:#2c3242; color:#53d0f8; font-family: helvetica;padding:10px;font-weight: 600;">Rating</td>
								<td style="background:#2c3242; color:#C7C7C7; font-family: helvetica;padding:10px;font-weight:400; "> <?php echo isset($data['rating']) ? urldecode($data['rating']) : ''; ?> </td>
							</tr>
							<tr>
							  <td colspan="2" style="background:#2c3242; color:#53d0f8; font-family: helvetica;padding:10px;font-weight: 600;"><div align="center">For more info on this service email <a href="mailto:business@digiwaxx.com" style="color: #C7C7C7;text-decoration: none;">business@digiwaxx.com</a> or call <a href="tel:8006651259" style="color: #C7C7C7;text-decoration: none;">(800) 665-1259</a></td>
							</tr>							
						</table>
						<table style="width: 99%;border-collapse: collapse; ">
							<tr>
								<td  style="background:#0B1023;text-align:end; font-weight: 600; padding: 40px 10px 60px 10px;">
									<a href="<?php echo isset($data['appUrl']) ? urldecode($data['appUrl']) : ''; ?>/Client_messages_conversation?mid=<?php echo urldecode($data['djMemId']); ?>" target="_blank" style="background:#5BC5F1; color: white;padding:12px 20px;text-decoration: none;font-family: helvetica; ">Connect With <?php if(!empty($data['djsName'])){ echo urldecode($data['djsName']); }else{ echo urldecode($data['name']); } ?> Direct!</a>
								</td>
								<td  style="background:#0B1023; text-align:start; font-weight: 600;  padding: 40px 10px 60px 10px;">
									<a href="<?php echo isset($data['appUrl']) ? urldecode($data['appUrl']) : ''; ?>/Client_track_review?tId=<?php echo urldecode($data['trackIdIs']); ?>" target="_blank" style="color:white; background: #F564A9;padding:12px 20px;text-decoration: none;font-family: helvetica;">Click here to view full report.</a>
								</td>
							</tr>
						</table>
						
					</td>
					<table style="width: 100%; border-collapse: collapse; background-color:#2c3242;">
						<tr>
							<td style="text-align: center; padding: 20px 0px;">
								<a href="https://digiwaxx.com/" target="_blank" style="text-decoration: none;">
									<img src="<?php echo isset($data['appUrl']) ? urldecode($data['appUrl']) : ''; ?>/assets_admin/assets/img/digiwaxx-emaillogo.png" style="width: 180px;">
								</a>
							</td>
						</tr>
						<tr>
							<td style="padding: 20px 0px; text-align: center;">
								<span style="margin: 0px 10px;">
									<a href="https://www.facebook.com/digiwaxx" target="_blank" style="text-decoration: none;">
										<img src="<?php echo isset($data['appUrl']) ? urldecode($data['appUrl']) : ''; ?>/assets_admin/assets/img/facebook-app-symbol.png" style="width:24px;">
										</a>
								</span>
								<span style="margin: 0px 10px;">
									<a href="https://twitter.com/Digiwaxx" target="_blank" style="text-decoration: none;">
										<img src="<?php echo isset($data['appUrl']) ? urldecode($data['appUrl']) : ''; ?>/assets_admin/assets/img/twitter.png" style="width:24px;">
										</a>
								</span>
								<span style="margin: 0px 10px;">
									<a href="https://www.linkedin.com/company/digiwaxx-media" target="_blank" style="text-decoration: none;">
										<img src="<?php echo isset($data['appUrl']) ? urldecode($data['appUrl']) : ''; ?>/assets_admin/assets/img/linkedin.png" style="width:24px;">
										</a>
								</span>
								<span style="margin: 0px 10px;">
									<a href="https://www.instagram.com/digiwaxx" target="_blank" style="text-decoration: none;">
									<img src="<?php echo isset($data['appUrl']) ? urldecode($data['appUrl']) : ''; ?>/assets_admin/assets/img/instagram.png" style="width:24px;">
									</a>
								</span>
							</td>
						</tr>
						<tr>
							<td style=" padding: 10px 30px; text-align:center; color: white;font-family: helvetica;">© Digiwaxx, LLC.</td>
						</tr>
					</table>
				</tr>
			</table>
		</td>
		</tr>
	</table>
</body>
</html>