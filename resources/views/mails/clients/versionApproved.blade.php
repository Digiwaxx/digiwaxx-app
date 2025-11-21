<!DOCTYPE html>
<html>

<body>
    <table style="width:700px; margin: auto;">
        <tr>
            <td>
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="background: black; text-align: center; padding: 20px 10px; border-bottom: 3px solid #F564A9;">
                            <a href="https://digiwaxx.com" target="_blank" style="text-decoration: none;">
                                <img src="https://dev.digiwaxx.com/assets_admin/assets/img/digiwaxx-emaillogo.png" style="width: 200px;">
                            </a>
                        </td>
                    </tr>
                </table>

                <table style="width:100%; background: #0B1023; border-collapse: collapse; font-family: helvetica;">
                    <tr>
                        <td style="padding: 40px 30px; color: #ffffff; font-size: 16px;">
                            <p style="margin-bottom: 20px;">Congratulations! <?php echo isset($data['client_name']) ? urldecode($data['client_name']) : ''; ?></p>

                            <p style="margin-bottom: 20px;">
                                The following version<?php echo count($data['approved_versions']) > 1 ? 's' : ''; ?> of your track <strong><?php echo urldecode($data['track_name']); ?></strong> <?php echo count($data['approved_versions']) > 1 ? 'have' : 'has'; ?> been <strong>APPROVED</strong> by our admin team and <?php echo count($data['approved_versions']) > 1 ? 'are' : 'is'; ?> now live on Digiwaxx.
                            </p>

                            <p style="margin-bottom: 20px;">
                                Please check the details:
                            </p>

                            <table style="width: 100%; border-collapse: separate; border-spacing: 3px 3px; font-size: 14px; margin-bottom: 30px;">
                                <tr>
                                    <td style="background:#2c3242; color:#53d0f8; padding:10px; font-weight: 600;">Track Name:</td>
                                    <td style="background:#2c3242; color:#C7C7C7; padding:10px; font-weight: 400;"><?php echo urldecode($data['track_name']); ?></td>
                                </tr>
                                <tr>
                                    <td style="background:#2c3242; color:#53d0f8; padding:10px; font-weight: 600;">Artist Name:</td>
                                    <td style="background:#2c3242; color:#C7C7C7; padding:10px; font-weight: 400;"><?php echo urldecode($data['artist']); ?></td>
                                </tr>
                                <tr>
                                    <td style="background:#2c3242; color:#53d0f8; padding:10px; font-weight: 600;">Approved Version<?php echo count($data['approved_versions']) > 1 ? 's' : ''; ?>:</td>
                                    <td style="background:#2c3242; color:#C7C7C7; padding:10px; font-weight: 400;">
                                        <?php
                                        if (!empty($data['approved_versions']) && is_array($data['approved_versions'])) {
                                            echo implode(', ', array_map('urldecode', $data['approved_versions']));
                                        } else {
                                            echo 'N/A';
                                        }
                                        ?>
                                    </td>
                                </tr>
                            </table>


                            <p style="margin-bottom: 30px;">
                                You can now view your track by clicking the button below:
                            </p>

                            <p style="text-align: center;">
                                <a href="https://dev.digiwaxx.com/Client_edit_track?tId=<?php echo $data['track_id'] ?>" style="background-color: #53d0f8; color: #0B1023; padding: 12px 24px; text-decoration: none; font-size: 14px; border-radius: 5px; display: inline-block;" target="_blank">
                                    View Track
                                </a>
                            </p>

                            <p style="margin-top: 40px; font-size: 14px; color: #C7C7C7;">
                                Thank you for being part of the Digiwaxx community. We’re proud to support your music journey!
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