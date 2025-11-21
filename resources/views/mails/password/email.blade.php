<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Digiwaxx</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            max-width: 500px;
            width: 100%;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 30px;
            text-align: center;
            color: white;
        }

        .header img {
            max-width: 200px;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 28px;
            margin-bottom: 10px;
        }

        .header p {
            opacity: 0.9;
            font-size: 14px;
        }

        .content {
            padding: 40px 30px;
        }

        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .alert-warning {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }

        .form-group {
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 600;
            font-size: 14px;
        }

        .input-group {
            position: relative;
        }

        .input-group i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
        }

        input[type="email"] {
            width: 100%;
            padding: 15px 15px 15px 45px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s;
        }

        input[type="email"]:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .radio-group {
            display: flex;
            gap: 20px;
            margin-top: 10px;
        }

        .radio-option {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .radio-option input[type="radio"] {
            width: 20px;
            height: 20px;
            cursor: pointer;
        }

        .radio-option label {
            margin: 0;
            font-weight: normal;
            cursor: pointer;
        }

        .btn {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: transform 0.2s;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .btn:active {
            transform: translateY(0);
        }

        .links {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
        }

        .links a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }

        .links a:hover {
            text-decoration: underline;
        }

        .info-box {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .info-box h3 {
            color: #333;
            margin-bottom: 10px;
            font-size: 16px;
        }

        .info-box p {
            color: #666;
            line-height: 1.6;
            font-size: 14px;
        }

        @media (max-width: 768px) {
            .container {
                margin: 20px;
            }

            .content {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            @if(isset($logo) && $logo)
            <img src="{{ url('/member/pcloud/stream/' . $logo) }}" alt="Digiwaxx">
            @else
            <h1>DIGIWAXX</h1>
            @endif
            <h1>Forgot Password</h1>
            <p>Reset your password securely</p>
        </div>

        <div class="content">
            @if(isset($alert_message))
            <div class="alert {{ $alert_class ?? 'alert-info' }}">
                @if($alert_class == 'alert-success')
                <i class="fas fa-check-circle"></i>
                @elseif($alert_class == 'alert-danger' || isset($class) && $class == 'alert alert-warning')
                <i class="fas fa-exclamation-triangle"></i>
                @else
                <i class="fas fa-info-circle"></i>
                @endif
                <span>{{ $alert_message }}</span>
            </div>
            @endif

            @if(isset($result) && $result)
            <div class="alert {{ $class ?? 'alert-info' }}">
                <i class="fas fa-exclamation-triangle"></i>
                <span>{{ $result }}</span>
            </div>
            @endif

            <div class="info-box">
                <h3><i class="fas fa-key"></i> Password Reset</h3>
                <p>Enter your email address and select your account type. We'll send you a secure link to reset your password.</p>
            </div>

            <form action="/forgot-password" method="POST">
                @csrf

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <div class="input-group">
                        <i class="fas fa-envelope"></i>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            placeholder="Enter your email address"
                            required
                            value="{{ old('email') }}"
                        >
                    </div>
                </div>

                <div class="form-group">
                    <label>Account Type</label>
                    <div class="radio-group">
                        <div class="radio-option">
                            <input type="radio" id="client" name="user" value="1" checked>
                            <label for="client">Artist/Label (Client)</label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" id="member" name="user" value="2">
                            <label for="member">DJ (Member)</label>
                        </div>
                    </div>
                </div>

                <button type="submit" name="sendMail" value="1" class="btn">
                    <i class="fas fa-paper-plane"></i> Send Reset Link
                </button>
            </form>

            <div class="links">
                <p>Remember your password? <a href="/login"><i class="fas fa-sign-in-alt"></i> Back to Login</a></p>
            </div>
        </div>
    </div>
</body>
</html>
