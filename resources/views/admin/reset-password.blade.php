<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Admin Password - Digiwaxx</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

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
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 520px;
            width: 100%;
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 30px;
            text-align: center;
            color: white;
        }

        .header i {
            font-size: 48px;
            margin-bottom: 15px;
            opacity: 0.9;
        }

        .header h1 {
            font-size: 28px;
            margin-bottom: 8px;
            font-weight: 600;
        }

        .header p {
            font-size: 14px;
            opacity: 0.9;
        }

        .content {
            padding: 40px 30px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
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
            color: #667eea;
            font-size: 16px;
        }

        .input-group input {
            width: 100%;
            padding: 14px 50px 14px 45px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 15px;
            transition: all 0.3s ease;
        }

        .input-group input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #999;
            font-size: 16px;
            transition: color 0.3s ease;
        }

        .toggle-password:hover {
            color: #667eea;
        }

        .password-requirements {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 25px;
        }

        .password-requirements h4 {
            color: #333;
            font-size: 14px;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .password-requirements ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .password-requirements li {
            padding: 8px 0;
            color: #666;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
        }

        .password-requirements li i {
            width: 16px;
            font-size: 14px;
            color: #ddd;
        }

        .password-requirements li.valid {
            color: #28a745;
        }

        .password-requirements li.valid i {
            color: #28a745;
        }

        .password-requirements li.invalid {
            color: #dc3545;
        }

        .password-requirements li.invalid i {
            color: #dc3545;
        }

        .btn {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }

        .btn:active:not(:disabled) {
            transform: translateY(0);
        }

        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .admin-badge {
            display: inline-block;
            background: rgba(102, 126, 234, 0.1);
            color: #667eea;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 20px;
        }

        @media (max-width: 480px) {
            .container {
                border-radius: 0;
            }

            .header {
                padding: 30px 20px;
            }

            .content {
                padding: 30px 20px;
            }

            .header h1 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <i class="fas fa-shield-alt"></i>
            <h1>Reset Admin Password</h1>
            <p>Create a strong new password for your administrator account</p>
        </div>

        <div class="content">
            <div class="admin-badge">
                <i class="fas fa-user-shield"></i> Administrator Account
            </div>

            <form id="resetForm" action="/admin/reset-password-submit" method="POST">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="form-group">
                    <label for="password">New Password</label>
                    <div class="input-group">
                        <i class="fas fa-lock"></i>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Enter your new password"
                            required
                            minlength="12"
                        >
                        <i class="fas fa-eye toggle-password" onclick="togglePassword('password')"></i>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirm New Password</label>
                    <div class="input-group">
                        <i class="fas fa-lock"></i>
                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            placeholder="Confirm your new password"
                            required
                            minlength="12"
                        >
                        <i class="fas fa-eye toggle-password" onclick="togglePassword('password_confirmation')"></i>
                    </div>
                </div>

                <div class="password-requirements">
                    <h4><i class="fas fa-shield-alt"></i> Administrator Password Requirements:</h4>
                    <ul id="requirements">
                        <li id="req-length"><i class="fas fa-circle"></i> At least 12 characters (admin accounts require stronger passwords)</li>
                        <li id="req-uppercase"><i class="fas fa-circle"></i> One uppercase letter</li>
                        <li id="req-lowercase"><i class="fas fa-circle"></i> One lowercase letter</li>
                        <li id="req-number"><i class="fas fa-circle"></i> One number</li>
                        <li id="req-special"><i class="fas fa-circle"></i> One special character (!@#$%^&*)</li>
                        <li id="req-match"><i class="fas fa-circle"></i> Passwords match</li>
                    </ul>
                </div>

                <button type="submit" id="submitBtn" class="btn" disabled>
                    <i class="fas fa-check-circle"></i> Reset Admin Password
                </button>
            </form>
        </div>
    </div>

    <script>
        const password = document.getElementById('password');
        const passwordConfirm = document.getElementById('password_confirmation');
        const submitBtn = document.getElementById('submitBtn');

        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = field.nextElementSibling;

            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        function validatePassword() {
            const value = password.value;
            const confirmValue = passwordConfirm.value;
            let allValid = true;

            // Length check (12+ for admin)
            const reqLength = document.getElementById('req-length');
            if (value.length >= 12) {
                reqLength.classList.add('valid');
                reqLength.classList.remove('invalid');
                reqLength.querySelector('i').classList.remove('fa-circle');
                reqLength.querySelector('i').classList.add('fa-check-circle');
            } else {
                reqLength.classList.remove('valid');
                reqLength.classList.add('invalid');
                reqLength.querySelector('i').classList.remove('fa-check-circle');
                reqLength.querySelector('i').classList.add('fa-circle');
                allValid = false;
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
                allValid = false;
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
                allValid = false;
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
                allValid = false;
            }

            // Special character check
            const reqSpecial = document.getElementById('req-special');
            if (/[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(value)) {
                reqSpecial.classList.add('valid');
                reqSpecial.classList.remove('invalid');
                reqSpecial.querySelector('i').classList.remove('fa-circle');
                reqSpecial.querySelector('i').classList.add('fa-check-circle');
            } else {
                reqSpecial.classList.remove('valid');
                reqSpecial.classList.add('invalid');
                reqSpecial.querySelector('i').classList.remove('fa-check-circle');
                reqSpecial.querySelector('i').classList.add('fa-circle');
                allValid = false;
            }

            // Match check
            const reqMatch = document.getElementById('req-match');
            if (value === confirmValue && value.length > 0) {
                reqMatch.classList.add('valid');
                reqMatch.classList.remove('invalid');
                reqMatch.querySelector('i').classList.remove('fa-circle');
                reqMatch.querySelector('i').classList.add('fa-check-circle');
            } else {
                reqMatch.classList.remove('valid');
                reqMatch.classList.add('invalid');
                reqMatch.querySelector('i').classList.remove('fa-check-circle');
                reqMatch.querySelector('i').classList.add('fa-circle');
                allValid = false;
            }

            // Enable/disable submit button
            submitBtn.disabled = !allValid;
        }

        password.addEventListener('input', validatePassword);
        passwordConfirm.addEventListener('input', validatePassword);

        // Prevent form submission if validation fails
        document.getElementById('resetForm').addEventListener('submit', function(e) {
            if (submitBtn.disabled) {
                e.preventDefault();
                alert('Please ensure all password requirements are met.');
            }
        });
    </script>
</body>
</html>
