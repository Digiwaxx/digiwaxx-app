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
								<img src="https://dev.digiwaxx.com/assets_admin/assets/img/digiwaxx-emaillogo.png" style="width: 200px;">
							</a>
						</td>
					</tr>
				</table>
				<table style="width:100%; background: #0B1023; border-collapse: collapse; font-family: hellavita;">
					<tr>
						<td style="padding: 40px 30px 0px; color: #ffffff; font-family: helvetica; font-size: 16px;">
							<p style="margin-bottom: 20px;">Hi! <?php echo isset($email_data['client_name']) ? urldecode($email_data['client_name']) : ''; ?></p>
							<p style="margin-bottom: 20px;">Thank you for submitting your track versions to Digiwaxx!</p>
							<p style="margin-bottom: 30px;">Below are the details of the versions submitted:</p>

							<table style="width: 100%; border-collapse: separate; border-spacing: 3px 3px; font-size: 14px;">
								<tr>
									<td style="background:#2c3242; color:#53d0f8; font-family: helvetica; padding:10px; font-weight: 600;">Track Name:</td>
									<td style="background:#2c3242; color:#C7C7C7; font-family: helvetica; padding:10px; font-weight:400;"><?php echo isset($email_data['track_title']) ? urldecode($email_data['track_title']) : ''; ?></td>
								</tr>
								<tr>
									<td style="background:#2c3242; color:#53d0f8; font-family: helvetica; padding:10px; font-weight: 600;">Track Artist:</td>
									<td style="background:#2c3242; color:#C7C7C7; font-family: helvetica; padding:10px; font-weight: 400;"><?php echo isset($email_data['artist']) ? urldecode($email_data['artist']) : ''; ?></td>
								</tr>
								<tr>
									<td style="background:#2c3242; color:#53d0f8; font-family: helvetica; padding:10px; font-weight: 600;">Submitted Versions:</td>
									<td style="background:#2c3242; color:#C7C7C7; font-family: helvetica; padding:10px; font-weight: 400;">
										<?php
										if (!empty($email_data['submitted_versions']) && is_array($email_data['submitted_versions'])) {
											echo implode(', ', array_map('urldecode', $email_data['submitted_versions']));
										} else {
											echo 'N/A';
										}
										?>
									</td>
								</tr>
							</table>

							<p style="margin-top: 30px; color: #ffffff; font-family: helvetica; font-size: 14px;">
								Please note that the submitted versions are currently <strong>Pending for Approval</strong>. You will be notified once they have been reviewed and approved.
							</p>
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