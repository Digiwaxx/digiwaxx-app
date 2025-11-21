<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset Confirmation - Digiwaxx</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f4f4;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f4f4; padding: 20px;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); padding: 40px 30px; text-align: center;">
                            <div style="font-size: 48px; margin-bottom: 10px;">✓</div>
                            <h1 style="margin: 0; color: #ffffff; font-size: 28px; font-weight: bold;">Password Reset Successful</h1>
                            <p style="margin: 10px 0 0 0; color: #ffffff; opacity: 0.9; font-size: 14px;">Your password has been changed</p>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <h2 style="margin: 0 0 20px 0; color: #333333; font-size: 22px;">Hello {{ $data['name'] ?? 'User' }},</h2>

                            <p style="margin: 0 0 20px 0; color: #666666; line-height: 1.6; font-size: 16px;">
                                Your password has been successfully reset for your Digiwaxx account.
                            </p>

                            <p style="margin: 0 0 30px 0; color: #666666; line-height: 1.6; font-size: 16px;">
                                You can now log in to your account using your new password.
                            </p>

                            <!-- Login Button -->
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center" style="padding: 0 0 30px 0;">
                                        <a href="{{ url('/login') }}"
                                           style="display: inline-block; padding: 15px 40px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #ffffff; text-decoration: none; border-radius: 8px; font-weight: bold; font-size: 16px;">
                                            Login to Your Account
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <!-- Account Details (Optional - Hidden by default) -->
                            <!--
                            <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f8f9fa; border-radius: 8px; padding: 20px; margin-bottom: 30px;">
                                <tr>
                                    <td>
                                        <p style="margin: 0 0 10px 0; color: #333333; font-weight: bold; font-size: 14px;">
                                            Your Login Credentials
                                        </p>
                                        <p style="margin: 0; color: #666666; line-height: 1.6; font-size: 14px;">
                                            <strong>Email:</strong> {{ $data['emailId'] }}<br>
                                            <strong>Password:</strong> [Your new password]
                                        </p>
                                    </td>
                                </tr>
                            </table>
                            -->

                            <!-- Security Notice -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #d4edda; border-radius: 8px; padding: 20px; margin-bottom: 20px;">
                                <tr>
                                    <td>
                                        <p style="margin: 0 0 10px 0; color: #155724; font-weight: bold; font-size: 14px;">
                                            🔒 Security Reminder
                                        </p>
                                        <p style="margin: 0; color: #155724; line-height: 1.6; font-size: 13px;">
                                            We recommend that you keep your password secure and don't share it with anyone. If you didn't make this change, please contact support immediately.
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin: 0; color: #666666; line-height: 1.6; font-size: 14px;">
                                If you have any questions or concerns about your account security, please don't hesitate to contact our support team at <a href="mailto:business@digiwaxx.com" style="color: #667eea; text-decoration: none;">business@digiwaxx.com</a>
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8f9fa; padding: 30px; text-align: center; border-top: 1px solid #e0e0e0;">
                            <p style="margin: 0 0 10px 0; color: #666666; font-size: 14px;">
                                <strong>Digiwaxx</strong> - Professional DJ Pool
                            </p>
                            <p style="margin: 0; color: #999999; font-size: 12px;">
                                © {{ date('Y') }} Digiwaxx. All rights reserved.
                            </p>
                            <p style="margin: 10px 0 0 0; color: #999999; font-size: 12px;">
                                <a href="{{ url('/') }}" style="color: #667eea; text-decoration: none;">Visit Website</a> |
                                <a href="mailto:business@digiwaxx.com" style="color: #667eea; text-decoration: none;">Contact Support</a>
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
