<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Your Admin Password - Digiwaxx</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f4f4f4;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f4f4; padding: 20px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">

                    <!-- Header with Admin Badge -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 40px 30px; text-align: center;">
                            <div style="font-size: 48px; margin-bottom: 10px;">🔐</div>
                            <h1 style="margin: 0; color: #ffffff; font-size: 28px; font-weight: bold;">Admin Password Reset</h1>
                            <p style="margin: 10px 0 0 0; color: #ffffff; opacity: 0.9; font-size: 14px;">Security request for administrator account</p>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <p style="margin: 0 0 15px 0; color: #333333; font-size: 16px; line-height: 1.6;">
                                Hello <strong>{{ $data['name'] }}</strong>,
                            </p>

                            <p style="margin: 0 0 25px 0; color: #666666; font-size: 15px; line-height: 1.6;">
                                We received a request to reset the password for your <strong>administrator account</strong> at Digiwaxx. Click the button below to create a new password.
                            </p>

                            <!-- Reset Button -->
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center" style="padding: 0 0 30px 0;">
                                        <a href="{{ url('/admin/reset-password/' . $data['token']) }}"
                                           style="display: inline-block; padding: 15px 40px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #ffffff; text-decoration: none; border-radius: 8px; font-weight: bold; font-size: 16px;">
                                            Reset Admin Password
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <!-- Alternative Link -->
                            <p style="margin: 0 0 25px 0; color: #666666; font-size: 13px; line-height: 1.6;">
                                If the button doesn't work, copy and paste this link into your browser:
                            </p>
                            <p style="margin: 0 0 30px 0; padding: 15px; background-color: #f8f9fa; border-radius: 6px; word-break: break-all;">
                                <a href="{{ url('/admin/reset-password/' . $data['token']) }}" style="color: #667eea; text-decoration: none; font-size: 13px;">
                                    {{ url('/admin/reset-password/' . $data['token']) }}
                                </a>
                            </p>

                            <!-- Security Notice -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #fff3cd; border-radius: 8px; padding: 20px; margin-top: 30px;">
                                <tr>
                                    <td>
                                        <p style="margin: 0 0 10px 0; color: #856404; font-weight: bold; font-size: 14px;">
                                            ⚠️ Security Notice
                                        </p>
                                        <p style="margin: 0; color: #856404; line-height: 1.6; font-size: 13px;">
                                            This password reset link will expire in <strong>1 hour</strong> for security reasons.
                                            If you didn't request this password reset, please ignore this email and contact the system administrator immediately.
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Additional Security Info -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f8f9fa; border-radius: 8px; padding: 20px; margin-top: 20px;">
                                <tr>
                                    <td>
                                        <p style="margin: 0 0 10px 0; color: #333; font-weight: bold; font-size: 14px;">
                                            🛡️ Administrator Security Reminder
                                        </p>
                                        <ul style="margin: 0; padding-left: 20px; color: #666; line-height: 1.8; font-size: 13px;">
                                            <li>Never share your admin password with anyone</li>
                                            <li>Use a strong, unique password (min 12 characters)</li>
                                            <li>Enable two-factor authentication when available</li>
                                            <li>Don't use this password on other websites</li>
                                        </ul>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8f9fa; padding: 30px; text-align: center; border-top: 1px solid #e0e0e0;">
                            <p style="margin: 0 0 10px 0; color: #666666; font-size: 14px;">
                                <strong>Digiwaxx</strong> - Music Distribution Platform
                            </p>
                            <p style="margin: 0 0 15px 0; color: #999999; font-size: 12px;">
                                This is an automated security email for administrator accounts.
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
