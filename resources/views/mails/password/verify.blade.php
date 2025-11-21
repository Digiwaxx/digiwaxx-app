<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Your Password - Digiwaxx</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f4f4;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f4f4; padding: 20px;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 40px 30px; text-align: center;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 28px; font-weight: bold;">Password Reset Request</h1>
                            <p style="margin: 10px 0 0 0; color: #ffffff; opacity: 0.9; font-size: 14px;">Digiwaxx - Professional DJ Pool</p>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <h2 style="margin: 0 0 20px 0; color: #333333; font-size: 22px;">Hello {{ $data['name'] ?? 'User' }},</h2>

                            <p style="margin: 0 0 20px 0; color: #666666; line-height: 1.6; font-size: 16px;">
                                You are receiving this email because we received a password reset request for your account.
                            </p>

                            <p style="margin: 0 0 30px 0; color: #666666; line-height: 1.6; font-size: 16px;">
                                Click the button below to reset your password:
                            </p>

                            <!-- Reset Button -->
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center" style="padding: 0 0 30px 0;">
                                        <a href="{{ url('/reset-password/' . $data['token']) }}"
                                           style="display: inline-block; padding: 15px 40px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #ffffff; text-decoration: none; border-radius: 8px; font-weight: bold; font-size: 16px;">
                                            Reset Password
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin: 0 0 20px 0; color: #666666; line-height: 1.6; font-size: 14px;">
                                If you did not request a password reset, no further action is required. Your password will remain unchanged.
                            </p>

                            <!-- Security Notice -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #fff3cd; border-radius: 8px; padding: 20px; margin-top: 30px;">
                                <tr>
                                    <td>
                                        <p style="margin: 0 0 10px 0; color: #856404; font-weight: bold; font-size: 14px;">
                                            ⚠️ Security Notice
                                        </p>
                                        <p style="margin: 0; color: #856404; line-height: 1.6; font-size: 13px;">
                                            This password reset link will expire in 24 hours. If you didn't request this reset, please contact support immediately.
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Alternative Link -->
                            <p style="margin: 30px 0 0 0; padding-top: 20px; border-top: 1px solid #e0e0e0; color: #999999; line-height: 1.6; font-size: 12px;">
                                If the button above doesn't work, copy and paste this link into your browser:<br>
                                <a href="{{ url('/reset-password/' . $data['token']) }}" style="color: #667eea; word-break: break-all;">{{ url('/reset-password/' . $data['token']) }}</a>
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
