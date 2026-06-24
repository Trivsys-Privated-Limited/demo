<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login Now!</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --orange: #0D9488;
            --orange-dark: #0D9488;
            --text-dark: #1a1a1a;
            --text-mid: #555;
            --text-light: #aaa;
            --input-bg: #F5F5F5;
            --border: #e5e5e5;
            --white: #ffffff;
        }

        body {
            font-family: 'Nunito', sans-serif;
            background-color: var(--orange);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .phone-wrapper {
            width: 375px;
            min-height: 400px;
            background: var(--white);
            border-radius: 40px;
            overflow: hidden;
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.25);
            padding: 0 0 30px 0;
            animation: slideUp 0.5s cubic-bezier(.22, .68, 0, 1.2) both;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Status bar */
        .status-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 14px 24px 0;
            font-size: 13px;
            font-weight: 700;
            color: var(--text-dark);
        }

        .status-bar .icons {
            display: flex;
            gap: 6px;
            align-items: center;
        }

        .status-bar .icons svg {
            width: 16px;
            height: 16px;
        }

        /* Content */
        .content {
            padding: 18px 28px 0;
        }

        /* Avatar */
        .avatar-wrap {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: linear-gradient(145deg, #ffe0a0, #0D9488);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 18px;
            box-shadow: 0 4px 15px rgba(245, 166, 35, 0.4);
            overflow: hidden;
        }

        .avatar-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .avatar-emoji {
            font-size: 36px;
            line-height: 1;
        }

        .welcome-text {
            font-size: 22px;
            font-weight: 400;
            color: var(--text-dark);
            margin-bottom: 2px;
        }

        .brand-name {
            font-size: 28px;
            font-weight: 800;
            color: var(--text-dark);
            margin-bottom: 28px;
        }

        .brand-name span {
            color: var(--orange);
        }

        /* Form */
        .form-group {
            margin-bottom: 16px;
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 8px;
        }

        .input-wrap {
            position: relative;
        }

        .form-input {
            width: 100%;
            background: var(--input-bg);
            border: none;
            border-radius: 14px;
            padding: 14px 18px;
            font-size: 15px;
            font-family: 'Nunito', sans-serif;
            color: var(--text-mid);
            outline: none;
            transition: box-shadow 0.2s;
        }

        .form-input:focus {
            box-shadow: 0 0 0 2px var(--orange);
        }

        .toggle-pw {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: var(--text-light);
            padding: 4px;
            display: flex;
            align-items: center;
        }

        /* Remember + Forgot */
        .row-opts {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .remember-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: var(--text-mid);
            cursor: pointer;
            user-select: none;
        }

        .remember-label input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: var(--orange);
            cursor: pointer;
        }

        .forgot-link {
            font-size: 13px;
            font-weight: 700;
            color: var(--orange);
            text-decoration: none;
        }

        .forgot-link:hover {
            text-decoration: underline;
        }

        /* Login button */
        .btn-login {
            width: 100%;
            background: linear-gradient(90deg, #0D9488, #f0c060);
            border: none;
            border-radius: 30px;
            padding: 15px;
            font-size: 16px;
            font-weight: 800;
            font-family: 'Nunito', sans-serif;
            color: var(--white);
            cursor: pointer;
            letter-spacing: 0.3px;
            box-shadow: 0 6px 20px rgba(245, 166, 35, 0.45);
            transition: transform 0.15s, box-shadow 0.15s;
            margin-bottom: 20px;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 28px rgba(245, 166, 35, 0.5);
        }

        .btn-login:active {
            transform: translateY(0);
            box-shadow: 0 4px 12px rgba(245, 166, 35, 0.3);
        }

        /* Divider */
        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 18px;
        }

        .divider-line {
            flex: 1;
            height: 1px;
            background: var(--border);
        }

        .divider-text {
            font-size: 13px;
            color: var(--text-light);
            white-space: nowrap;
        }

        /* Social buttons */
        .social-row {
            display: flex;
            gap: 12px;
            margin-bottom: 28px;
        }

        .btn-social {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            border: 1.5px solid var(--border);
            border-radius: 14px;
            padding: 12px;
            background: var(--white);
            font-size: 14px;
            font-weight: 700;
            font-family: 'Nunito', sans-serif;
            color: var(--text-dark);
            cursor: pointer;
            transition: background 0.15s, border-color 0.15s;
        }

        .btn-social:hover {
            background: #fafafa;
            border-color: #ccc;
        }

        .btn-social svg {
            width: 20px;
            height: 20px;
        }

        /* Sign up */
        .signup-row {
            text-align: center;
            font-size: 13px;
            color: var(--text-light);
        }

        .signup-row a {
            color: var(--orange);
            font-weight: 800;
            text-decoration: none;
        }

        .signup-row a:hover {
            text-decoration: underline;
        }

        /* Home bar */
        .home-bar {
            width: 120px;
            height: 5px;
            background: #ccc;
            border-radius: 3px;
            margin: 22px auto 0;
        }
    </style>
</head>

<body>
    <div class="phone-wrapper">

        <div class="content">
            <!-- Avatar -->
            <div class="avatar-wrap">
                <span class="avatar-emoji">👨‍🍳</span>
            </div>

            <p class="welcome-text">Welcome to</p>
            <!-- Site Name -->
            <h1 class="brand-name">Scan<span>Dine</span></h1>

            <form action="{{ route('login.store') }}" method="POST" autocomplete="off">
                @csrf
                <p>
                    @if (session('error'))
                        <span class="text-danger">{{ session('error') }}</span>
                    @endif
                </p>

                <!-- Email -->
                <div class="form-group">
                    <label class="form-label" for="email">Email</label>
                    <div class="input-wrap">
                        <input class="form-input" type="email" name="email" id="email"
                            placeholder="abc@gamil.com">
                    </div>
                    @error('email')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label class="form-label" for="password">Password</label>
                    <div class="input-wrap">
                        <input class="form-input" type="password" id="password" name="password"
                            style="letter-spacing:3px;" placeholder="Hello World!">
                        <button class="toggle-pw" type="button" onclick="togglePw()" aria-label="Toggle password">
                            <svg id="pw-icon" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path
                                    d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19M1 1l22 22" />
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Login button -->
                <button class="btn-login" type="submit">Log in</button>

            </form>

        </div>
    </div>

    <script>
        function togglePw() {
            const input = document.getElementById('password');
            const icon = document.getElementById('pw-icon');
            if (input.type === 'password') {
                input.type = 'text';
                input.style.letterSpacing = 'normal';
                icon.innerHTML = `<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>`;
            } else {
                input.type = 'password';
                input.style.letterSpacing = '3px';
                icon.innerHTML =
                    `<path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19M1 1l22 22"/>`;
            }
        }
    </script>
</body>

</html>
