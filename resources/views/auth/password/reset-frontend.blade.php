<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Digiwaxx</title>
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

        .success-msg {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .error-msg {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
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

        .input-group .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #999;
        }

        .input-group .toggle-password:hover {
            color: #667eea;
        }

        input[type="password"],
        input[type="text"] {
            width: 100%;
            padding: 15px 45px 15px 45px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s;
        }

        input[type="password"]:focus,
        input[type="text"]:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .password-requirements {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-top: 10px;
            font-size: 13px;
        }

        .password-requirements h4 {
            margin: 0 0 10px 0;
            color: #333;
            font-size: 14px;
        }

        .password-requirements ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .password-requirements li {
            padding: 5px 0;
            color: #666;
        }

        .password-requirements li i {
            width: 20px;
        }

        .password-requirements li.valid {
            color: #28a745;
        }

        .password-requirements li.invalid {
            color: #dc3545;
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
            <h1>Reset Password</h1>
            <p>Enter your new password</p>
        </div>

        <div class="content">
            @if(isset($alert_message))
            <div class="alert {{ $alert_class ?? 'success-msg' }}">
                @if($alert_class == 'success-msg')
                <i class="fas fa-check-circle"></i>
                @else
                <i class="fas fa-exclamation-triangle"></i>
                @endif
                <span>{{ $alert_message }}</span>
            </div>
            @endif

            @if(!isset($invalidCode) || $invalidCode == 0)
            <form action="/reset-password-submit" method="POST" id="resetForm">
                @csrf
                <input type="hidden" name="token" value="{{ $token ?? '' }}">

                <div class="form-group">
                    <label for="password">New Password</label>
                    <div class="input-group">
                        <i class="fas fa-lock"></i>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Enter new password"
                            required
                            minlength="8"
                        >
                        <i class="fas fa-eye toggle-password" onclick="togglePassword('password')"></i>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password_confirm">Confirm Password</label>
                    <div class="input-group">
                        <i class="fas fa-lock"></i>
                        <input
                            type="password"
                            id="password_confirm"
                            name="password_confirm"
                            placeholder="Confirm new password"
                            required
                            minlength="8"
                        >
                        <i class="fas fa-eye toggle-password" onclick="togglePassword('password_confirm')"></i>
                    </div>
                </div>

                <div class="password-requirements">
                    <h4><i class="fas fa-shield-alt"></i> Password Requirements:</h4>
                    <ul id="requirements">
                        <li id="req-length"><i class="fas fa-circle"></i> At least 8 characters</li>
                        <li id="req-uppercase"><i class="fas fa-circle"></i> One uppercase letter</li>
                        <li id="req-lowercase"><i class="fas fa-circle"></i> One lowercase letter</li>
                        <li id="req-number"><i class="fas fa-circle"></i> One number</li>
                        <li id="req-match"><i class="fas fa-circle"></i> Passwords match</li>
                    </ul>
                </div>

                <button type="submit" class="btn">
                    <i class="fas fa-key"></i> Reset Password
                </button>
            </form>

            <div class="links">
                <p>Remember your password? <a href="/login"><i class="fas fa-sign-in-alt"></i> Back to Login</a></p>
            </div>
            @endif
        </div>
    </div>

    <script>
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon = input.nextElementSibling;

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // Password validation
        const password = document.getElementById('password');
        const passwordConfirm = document.getElementById('password_confirm');

        function validatePassword() {
            const value = password.value;
            const confirmValue = passwordConfirm.value;

            // Length check
            const reqLength = document.getElementById('req-length');
            if (value.length >= 8) {
                reqLength.classList.add('valid');
                reqLength.classList.remove('invalid');
                reqLength.querySelector('i').classList.remove('fa-circle');
                reqLength.querySelector('i').classList.add('fa-check-circle');
            } else {
                reqLength.classList.remove('valid');
                reqLength.classList.add('invalid');
                reqLength.querySelector('i').classList.remove('fa-check-circle');
                reqLength.querySelector('i').classList.add('fa-circle');
            }

            // Uppercase check
            const reqUppercase = document.getElementById('req-uppercase');
            if (/[A-Z]/.test(value)) {
                reqUppercase.classList.add('valid');
                reqUppercase.classList.remove('invalid');
                reqUppercase.querySelector('i').classList.remove('fa-circle');
                reqUppercase.querySelector('i').classList.add('fa-check-circle');
            } else {
                reqUppercase.classList.remove('valid');
                reqUppercase.classList.add('invalid');
                reqUppercase.querySelector('i').classList.remove('fa-check-circle');
                reqUppercase.querySelector('i').classList.add('fa-circle');
            }

            // Lowercase check
            const reqLowercase = document.getElementById('req-lowercase');
            if (/[a-z]/.test(value)) {
                reqLowercase.classList.add('valid');
                reqLowercase.classList.remove('invalid');
                reqLowercase.querySelector('i').classList.remove('fa-circle');
                reqLowercase.querySelector('i').classList.add('fa-check-circle');
            } else {
                reqLowercase.classList.remove('valid');
                reqLowercase.classList.add('invalid');
                reqLowercase.querySelector('i').classList.remove('fa-check-circle');
                reqLowercase.querySelector('i').classList.add('fa-circle');
            }

            // Number check
            const reqNumber = document.getElementById('req-number');
            if (/[0-9]/.test(value)) {
                reqNumber.classList.add('valid');
                reqNumber.classList.remove('invalid');
                reqNumber.querySelector('i').classList.remove('fa-circle');
                reqNumber.querySelector('i').classList.add('fa-check-circle');
            } else {
                reqNumber.classList.remove('valid');
                reqNumber.classList.add('invalid');
                reqNumber.querySelector('i').classList.remove('fa-check-circle');
                reqNumber.querySelector('i').classList.add('fa-circle');
            }

            // Match check
            const reqMatch = document.getElementById('req-match');
            if (value && confirmValue && value === confirmValue) {
                reqMatch.classList.add('valid');
                reqMatch.classList.remove('invalid');
                reqMatch.querySelector('i').classList.remove('fa-circle');
                reqMatch.querySelector('i').classList.add('fa-check-circle');
            } else {
                reqMatch.classList.remove('valid');
                reqMatch.classList.add('invalid');
                reqMatch.querySelector('i').classList.remove('fa-check-circle');
                reqMatch.querySelector('i').classList.add('fa-circle');
            }
        }

        if (password && passwordConfirm) {
            password.addEventListener('input', validatePassword);
            passwordConfirm.addEventListener('input', validatePassword);

            // Form submission validation
            document.getElementById('resetForm').addEventListener('submit', function(e) {
                if (password.value !== passwordConfirm.value) {
                    e.preventDefault();
                    alert('Passwords do not match!');
                    return false;
                }

                if (password.value.length < 8) {
                    e.preventDefault();
                    alert('Password must be at least 8 characters long!');
                    return false;
                }
            });
        }
    </script>
</body>
</html>
