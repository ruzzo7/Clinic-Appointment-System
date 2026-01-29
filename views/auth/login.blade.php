<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Clinic Appointment System</title>
    <link rel="stylesheet" href="<?php echo asset('css/style.css'); ?>">
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

        .login-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            max-width: 450px;
            width: 100%;
            animation: slideUp 0.5s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }

        .login-header h1 {
            font-size: 28px;
            margin-bottom: 10px;
        }

        .login-header p {
            opacity: 0.9;
            font-size: 14px;
        }

        .login-body {
            padding: 40px 30px;
        }

        .alert {
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 25px;
            font-size: 14px;
            animation: fadeIn 0.3s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .alert-error {
            background: #fee;
            color: #c33;
            border-left: 4px solid #c33;
        }

        .alert-success {
            background: #efe;
            color: #3c3;
            border-left: 4px solid #3c3;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 600;
            font-size: 14px;
        }

        .form-group input {
            width: 100%;
            padding: 14px 18px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 15px;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .form-group input:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }

        .form-group input::placeholder {
            color: #999;
        }

        .btn-login {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .login-footer {
            margin-top: 25px;
            text-align: center;
            padding-top: 25px;
            border-top: 1px solid #e0e0e0;
        }

        .login-footer p {
            color: #666;
            font-size: 13px;
        }

        .demo-credentials {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
            border-left: 4px solid #667eea;
        }

        .demo-credentials h4 {
            color: #667eea;
            margin-bottom: 10px;
            font-size: 14px;
        }

        .demo-credentials p {
            color: #666;
            font-size: 13px;
            margin: 5px 0;
        }

        .demo-credentials code {
            background: white;
            padding: 2px 8px;
            border-radius: 4px;
            color: #764ba2;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1>🏥 Clinic System</h1>
            <p>Sign in to manage appointments</p>
        </div>
        
        <div class="login-body">
            <?php if ($flash): ?>
                <div class="alert alert-<?php echo $flash['type']; ?>">
                    <?php echo $flash['message']; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="" id="loginForm">
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                
                <div class="form-group">
                    <label for="username">Username or Email</label>
                    <input 
                        type="text" 
                        id="username" 
                        name="username" 
                        placeholder="Enter your username or email"
                        required 
                        autofocus
                    >
                    <span class="error-message" id="username-error"></span>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        placeholder="Enter your password"
                        required
                    >
                    <span class="error-message" id="password-error"></span>
                </div>

                <button type="submit" class="btn-login">Sign In</button>
            </form>

            <div class="demo-credentials">
                <h4>📝 Demo Credentials</h4>
                <p><strong>Admin:</strong> <code>admin</code> / <code>admin123</code></p>
                <p><strong>Staff:</strong> <code>staff</code> / <code>admin123</code></p>
            </div>

            <div class="login-footer">
                <p>&copy; 2026 Clinic Appointment System. All rights reserved.</p>
            </div>
        </div>
    </div>

    <script src="<?php echo asset('js/form-validator.js'); ?>"></script>
    <script>
        // Initialize live form validation for login
        const loginValidator = new FormValidator('loginForm', {
            validateOnInput: true,
            validateOnBlur: true,
            showSuccessIcons: false, // Don't show success icons on login form
            debounceDelay: 300
        });

        // Add custom validators
        loginValidator.addValidator('username', (value) => {
            if (value.length < 3) {
                return 'Username must be at least 3 characters';
            }
            return true;
        });

        loginValidator.addValidator('password', (value) => {
            if (value.length < 6) {
                return 'Password must be at least 6 characters';
            }
            return true;
        });

        // Add validation hints
        document.getElementById('username').setAttribute('title', 'Enter your username or email');
        document.getElementById('password').setAttribute('title', 'Enter your password');
    </script>
</body>
</html>
