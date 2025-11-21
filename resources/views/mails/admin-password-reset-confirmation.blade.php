<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Password Reset Successful - Digiwaxx</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f4f4f4;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f4f4; padding: 20px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">

                    <!-- Header with Success Theme -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); padding: 40px 30px; text-align: center;">
                            <div style="font-size: 48px; margin-bottom: 10px;">✓</div>
                            <h1 style="margin: 0; color: #ffffff; font-size: 28px; font-weight: bold;">Admin Password Reset Successful</h1>
                            <p style="margin: 10px 0 0 0; color: #ffffff; opacity: 0.9; font-size: 14px;">Your administrator password has been changed</p>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <p style="margin: 0 0 15px 0; color: #333333; font-size: 16px; line-height: 1.6;">
                                Hello <strong>{{ $data['name'] }}</strong>,
                            </p>

                            <p style="margin: 0 0 25px 0; color: #666666; font-size: 15px; line-height: 1.6;">
                                This email confirms that your <strong>administrator password</strong> has been successfully reset for your Digiwaxx account.
                            </p>

                            <!-- Success Info Box -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #d4edda; border-radius: 8px; padding: 20px; margin-bottom: 25px;">
                                <tr>
                                    <td>
                                        <p style="margin: 0 0 10px 0; color: #155724; font-weight: bold; font-size: 14px;">
                                            ✓ Password Changed Successfully
                                        </p>
                                        <p style="margin: 0; color: #155724; line-height: 1.6; font-size: 13px;">
                                            Your admin password was changed on <strong>{{ date('F j, Y \a\t g:i A') }}</strong>.
                                            You can now use your new password to log in to the admin panel.
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Login Button -->
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center" style="padding: 0 0 30px 0;">
                                        <a href="{{ url('/admin') }}"
                                           style="display: inline-block; padding: 15px 40px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #ffffff; text-decoration: none; border-radius: 8px; font-weight: bold; font-size: 16px;">
                                            Login to Admin Panel
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <!-- Security Alert -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #fff3cd; border-radius: 8px; padding: 20px; margin-top: 20px;">
                                <tr>
                                    <td>
                                        <p style="margin: 0 0 10px 0; color: #856404; font-weight: bold; font-size: 14px;">
                                            ⚠️ Didn't Change Your Password?
                                        </p>
                                        <p style="margin: 0; color: #856404; line-height: 1.6; font-size: 13px;">
                                            If you did not request this password change, your account may be compromised.
                                            Please contact the system administrator immediately at <a href="mailto:business@digiwaxx.com" style="color: #856404; font-weight: bold;">business@digiwaxx.com</a>
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Security Best Practices -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f8f9fa; border-radius: 8px; padding: 20px; margin-top: 20px;">
                                <tr>
                                    <td>
                                        <p style="margin: 0 0 10px 0; color: #333; font-weight: bold; font-size: 14px;">
                                            🛡️ Administrator Security Tips
                                        </p>
                                        <ul style="margin: 0; padding-left: 20px; color: #666; line-height: 1.8; font-size: 13px;">
                                            <li>Never share your admin credentials with anyone</li>
                                            <li>Use a password manager to store your password securely</li>
                                            <li>Enable two-factor authentication when available</li>
                                            <li>Log out of the admin panel when not in use</li>
                                            <li>Monitor admin account activity regularly</li>
                                        </ul>
                                    </td>
                                </tr>
                            </table>

                            <!-- Account Details -->
                            <p style="margin: 30px 0 10px 0; color: #999999; font-size: 13px; line-height: 1.6;">
                                <strong>Account Email:</strong> {{ $data['emailId'] }}<br>
                                <strong>Password Changed:</strong> {{ date('F j, Y \a\t g:i A') }}<br>
                                <strong>IP Address:</strong> {{ request()->ip() }}
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8f9fa; padding: 30px; text-align: center; border-top: 1px solid #e0e0e0;">
                            <p style="margin: 0 0 10px 0; color: #666666; font-size: 14px;">
                                <strong>Digiwaxx</strong> - Music Distribution Platform
                            </p>
                            <p style="margin: 0 0 15px 0; color: #999999; font-size: 12px;">
                                This is an automated security notification for administrator accounts.
                            </p>
                            <p style="margin: 0; color: #999999; font-size: 12px;">
                                © {{ date('Y') }} Digiwaxx. All rights reserved.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
