<!DOCTYPE html>
<html>

<body>
	<table style="width:700px; margin: auto;">
		<tr>
			<td>
				<table style="width: 100%;border-collapse: collapse;">
					<tr>
						<td style="background: black; text-align: center; padding: 20px 10px; border-bottom: 3px solid #F564A9;">
							<a href="https://digiwaxx.com" target="_blank" style="text-decoration: none;">
								<img src="https://app.digiwaxx.com/assets_admin/assets/img/digiwaxx-emaillogo.png" style="width: 200px;">
							</a>
						</td>
					</tr>
				</table>
				<table style="width:100%; background: #0B1023; border-collapse: collapse; font-family: hellavita;">
					<tr>
						<td style="padding: 40px 30px 0px; color: #ffffff; font-family: helvetica; font-size: 16px;">
							<p style="margin-bottom: 20px;">Hi! <?php echo isset($email_data['djmember_name']) ? urldecode($email_data['djmember_name']) : ''; ?></p>
							<p>Artist/Promoter user <?php echo isset($email_data['client_name']) ? urldecode($email_data['client_name']) : ''; ?>  sent chat message for you at DigiWaxx.</p>
							<?php $link=$email_data['chat_link'];?>
							<p><a href="<?php echo $link;?>"><b>Click Here</b></a> to Chat.</p>

						</td>
					</tr>
					<tr>
						<td>
							<table style="width: 100%; border-collapse: collapse; background-color:#2c3242;">
								<tr>
									<td style="padding: 10px 30px; text-align:center; color: white; font-family: helvetica;">© Digiwaxx, LLC.</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</body>

</html>