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
                            <img src="<?php echo isset($data['appUrl']) ? urldecode($data['appUrl']).'/assets_admin/assets/img/digiwaxx-emaillogo.png' : ''; ?>" style="width: 200px;">
                        </a>
                    </td>
                </tr>
            </table>
            <table style="width:100%; background: #0B1023;border-collapse: collapse; font-family:hellavita">
                <tr>
                    <td style="padding: 40px 30px 0px;">
                        <table  style="width: 100%;  border-collapse:separate; border-spacing:03px 03px; font-size: 14px;">
                            <tr>
                              <td colspan="2" style="background:#2c3242; color:#53d0f8; font-family: helvetica;padding:10px;font-weight: 600;"><div align="center"><h3>Hi, {{ $data['name'] }}</h3><p>You Have Been Successfully Registered.</p><p>You have subscribed {{$data['title']}} package.</p><p>Thank You</p></div></td>
                            </tr>                                                                                                               
                        </table>
                        <table style="width: 99%;border-collapse: collapse; ">
                            <tr>
                                <td colspan="2" style="background:#0B1023;text-align:center; font-weight: 600; padding: 30px 10px 30px 10px;">
                                    <a href="<?php echo $data['veri'];?>" style="background:#5BC5F1; color: white;padding:12px 20px;text-decoration: none;font-family: helvetica; " target="_blank">Click to activate your account.</a>
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