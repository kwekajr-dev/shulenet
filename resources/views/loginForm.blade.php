<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - School Management System</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
            position: relative;
        }
        
        /* Video background container */
        .video-background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            overflow: hidden;
        }
        
        /* Video styling */
        .video-background video {
            min-width: 100%;
            min-height: 100%;
            width: auto;
            height: auto;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
        
        /* Overlay to darken the video for better text contrast */
        .video-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4);
            z-index: 0;
        }
        
        /* Text overlay elements */
        .text-overlay {
            position: absolute;
            top: 20px;
            left: 0;
            width: 100%;
            text-align: center;
            color: white;
            font-family: "Lucida Sans", "Lucida Sans Regular", "Lucida Grande",
                "Lucida Sans Unicode", Geneva, Verdana, sans-serif;
            z-index: 0;
        }
        
        .text-overlay h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }
        
        .text-overlay p {
            font-size: 1.2rem;
            max-width: 800px;
            margin: 0 auto;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
        }
        
        /* Positioning text elements */
        .top-text {
            top: 40px;
        }
        
        .bottom-text {
            top: auto;
            bottom: 40px;
        }
        
        /* Form container adjustments for better visibility on video */
        .form-container {
            width: 350px;
            height: 440px;
            background-color: rgba(255, 255, 255, 0.95);
            box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
            border-radius: 10px;
            box-sizing: border-box;
            padding: 20px 30px;
            z-index: 1;
        }

        .title {
            text-align: center;
            font-family: "Lucida Sans", "Lucida Sans Regular", "Lucida Grande",
                "Lucida Sans Unicode", Geneva, Verdana, sans-serif;
            margin: 10px 0 30px 0;
            font-size: 28px;
            font-weight: 800;
        }

        .form {
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 18px;
            margin-bottom: 15px;
        }

        .input {
            border-radius: 20px;
            border: 1px solid #c0c0c0;
            outline: 0 !important;
            box-sizing: border-box;
            padding: 12px 15px;
        }

        .page-link {
            text-decoration: underline;
            margin: 0;
            text-align: end;
            color: #747474;
            text-decoration-color: #747474;
        }

        .page-link-label {
            cursor: pointer;
            font-family: "Lucida Sans", "Lucida Sans Regular", "Lucida Grande",
                "Lucida Sans Unicode", Geneva, Verdana, sans-serif;
            font-size: 9px;
            font-weight: 700;
        }

        .page-link-label:hover {
            color: #000;
        }

        .form-btn {
            padding: 10px 15px;
            font-family: "Lucida Sans", "Lucida Sans Regular", "Lucida Grande",
                "Lucida Sans Unicode", Geneva, Verdana, sans-serif;
            border-radius: 20px;
            border: 0 !important;
            outline: 0 !important;
            background: teal;
            color: white;
            cursor: pointer;
            box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
        }

        .form-btn:active {
            box-shadow: none;
        }

        .sign-up-label {
            margin: 0;
            font-size: 10px;
            color: #747474;
            font-family: "Lucida Sans", "Lucida Sans Regular", "Lucida Grande",
                "Lucida Sans Unicode", Geneva, Verdana, sans-serif;
        }

        .sign-up-link {
            margin-left: 1px;
            font-size: 11px;
            text-decoration: underline;
            text-decoration-color: teal;
            color: teal;
            cursor: pointer;
            font-weight: 800;
            font-family: "Lucida Sans", "Lucida Sans Regular", "Lucida Grande",
                "Lucida Sans Unicode", Geneva, Verdana, sans-serif;
        }

        .buttons-container {
            width: 100%;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            margin-top: 20px;
            gap: 15px;
        }

        .apple-login-button,
        .google-login-button {
            border-radius: 20px;
            box-sizing: border-box;
            padding: 10px 15px;
            box-shadow: rgba(0, 0, 0, 0.16) 0px 10px 36px 0px,
                rgba(0, 0, 0, 0.06) 0px 0px 0px 1px;
            cursor: pointer;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: "Lucida Sans", "Lucida Sans Regular", "Lucida Grande",
                "Lucida Sans Unicode", Geneva, Verdana, sans-serif;
            font-size: 11px;
            gap: 5px;
        }

        .apple-login-button {
            background-color: #000;
            color: #fff;
            border: 2px solid #000;
        }

        .google-login-button {
            border: 2px solid #747474;
        }

        .apple-icon,
        .google-icon {
            font-size: 18px;
            margin-bottom: 1px;
        }
        
        /* Additional styles for error messages */
        .error-message {
            color: #d9534f;
            font-size: 12px;
            margin-top: 5px;
            display: none;
            font-family: "Lucida Sans", "Lucida Sans Regular", "Lucida Grande",
                "Lucida Sans Unicode", Geneva, Verdana, sans-serif;
        }
        
        .input-error {
            border-color: #d9534f;
        }
        h1{
            color: #fff;
            font-family: "Lucida Sans", "Lucida Sans Regular", "Lucida Grande",
                "Lucida Sans Unicode", Geneva, Verdana, sans-serif;
        }
    </style>
</head>
<body>
    <!-- Video Background -->
    <div class="video-background">
        <video autoplay muted loop playsinline id="bgVideo">
            <source src="images/back.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        <div class="video-overlay"></div>
    </div>
    
    <!-- Text Overlays -->
    <div class="text-overlay top-text">
        <h1>School Management System</h1>
        
    </div>
    
    <div class="text-overlay bottom-text">
        <p>© 2023 School Management System | Secure Login Portal</p>
    </div>

    <!-- Login Form Container -->
    <div class="form-container">
        <p class="title">Welcome back</p>
        
        <!-- Display error message if any -->
        <div id="errorMessage" class="error-message"></div>
        
        <form class="form" id="loginForm" method="POST" action="{{ route('loginUser') }}">
            @csrf <!-- CSRF Token for Laravel -->
            
            <input type="email" class="input" name="email" placeholder="Email" value="{{ old('email') }}" required>
            <input type="password" class="input" name="password" placeholder="Password" required>
            
            <p class="page-link">
                <span class="page-link-label">Forgot Password?</span>
            </p>
            
            <button type="submit" class="form-btn" id="loginBtn">Log in</button>
        </form>
        
        <p class="sign-up-label">
            Don't have an account?<span class="sign-up-link" id="signUpLink">Sign up</span>
        </p>
        
        <div class="buttons-container">
            <div class="apple-login-button">
                <svg stroke="currentColor" fill="currentColor" stroke-width="0" class="apple-icon" viewBox="0 0 1024 1024" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                    <path d="M747.4 535.7c-.4-68.2 30.5-119.6 92.9-157.5-34.9-50-87.7-77.5-157.3-82.8-65.9-5.2-138 38.4-164.4 38.4-27.9 0-91.7-36.6-141.9-36.6C273.1 298.8 163 379.8 163 544.6c0 48.7 8.9 99 26.7 150.8 23.8 68.2 109.6 235.3 199.1 232.6 46.8-1.1 79.9-33.2 140.8-33.2 59.1 0 89.7 33.2 141.9 33.2 90.3-1.3 167.9-153.2 190.5-221.6-121.1-57.1-114.6-167.2-114.6-170.7zm-105.1-305c50.7-60.2 46.1-115 44.6-134.7-44.8 2.6-96.6 30.5-126.1 64.8-32.5 36.8-51.6 82.3-47.5 133.6 48.4 3.7 92.6-21.2 129-63.7z"></path>
                </svg>
                <span>Log in with Apple</span>
            </div>
            <div class="google-login-button">
                <svg stroke="currentColor" fill="currentColor" stroke-width="0" version="1.1" x="0px" y="0px" class="google-icon" viewBox="0 0 48 48" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                    <path fill="#FFC107" d="M43.611,20.083H42V20H24v8h11.303c-1.649,4.657-6.08,8-11.303,8c-6.627,0-12-5.373-12-12
                    c0-6.627,5.373-12,12-12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C12.955,4,4,12.955,4,24
                    c0,11.045,8.955,20,20,20c11.045,0,20-8.955,20-20C44,22.659,43.862,21.35,43.611,20.083z"></path>
                    <path fill="#FF3D00" d="M6.306,14.691l6.571,4.819C14.655,15.108,18.961,12,24,12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657
                    C34.046,6.053,29.268,4,24,4C16.318,4,9.656,8.337,6.306,14.691z"></path>
                    <path fill="#4CAF50" d="M24,44c5.166,0,9.86-1.977,13.409-5.192l-6.19-5.238C29.211,35.091,26.715,36,24,36
                    c-5.202,0-9.619-3.317-11.283-7.946l-6.522,5.025C9.505,39.556,16.227,44,24,44z"></path>
                    <path fill="#1976D2" d="M43.611,20.083H42V20H24v8h11.303c-0.792,2.237-2.231,4.166-4.087,5.571
                    c0.001-0.001,0.002-0.001,0.003-0.002l6.19,5.238C36.971,39.205,44,34,44,24C44,22.659,43.862,21.35,43.611,20.083z"></path>
                </svg>
                <span>Log in with Google</span>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loginForm = document.getElementById('loginForm');
            const errorMessage = document.getElementById('errorMessage');
            const loginBtn = document.getElementById('loginBtn');
            const signUpLink = document.getElementById('signUpLink');
            
            // Check if there are error messages from server in URL parameters
            const urlParams = new URLSearchParams(window.location.search);
            const error = urlParams.get('error');
            const success = urlParams.get('success');
            
            if (error) {
                showError(error);
            }
            
            if (success) {
                // For success messages, you might want to show a different style
                errorMessage.style.color = '#28a745';
                errorMessage.textContent = success;
                errorMessage.style.display = 'block';
                
                // Hide success message after 3 seconds
                setTimeout(() => {
                    errorMessage.style.display = 'none';
                }, 3000);
            }
            
            // Form submission
            loginForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Basic validation
                const email = loginForm.email.value;
                const password = loginForm.password.value;
                
                if (!email || !password) {
                    showError('Please fill in all fields');
                    return;
                }
                
                if (!isValidEmail(email)) {
                    showError('Please enter a valid email address');
                    return;
                }
                
                // If validation passes, submit the form
                this.submit();
            });
            
            // Sign up link
            signUpLink.addEventListener('click', function() {
                window.location.href = "#";
            });
            
            // Forgot password
            document.querySelector('.page-link-label').addEventListener('click', function() {
                alert('Password reset functionality would be implemented here.');
            });
            
            // Social login buttons
            document.querySelectorAll('.apple-login-button, .google-login-button').forEach(btn => {
                btn.addEventListener('click', function() {
                    alert('Social login would be implemented here.');
                });
            });
            
            // Helper functions
            function isValidEmail(email) {
                const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return re.test(email);
            }
            
            function showError(message) {
                errorMessage.textContent = message;
                errorMessage.style.display = 'block';
                
                // Hide error after 5 seconds
                setTimeout(() => {
                    errorMessage.style.display = 'none';
                }, 5000);
            }
            
            // Fallback if video doesn't load
            setTimeout(function() {
                const video = document.getElementById('bgVideo');
                if (video && video.readyState === 0) {
                    console.log('Video might not be loading, check the path: images/back2.mp4');
                }
            }, 2000);
        });
    </script>
</body>
</html>