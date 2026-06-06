<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject }}</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f4f6f9;
            font-family: 'Segoe UI', Arial, sans-serif;
            color: #333333;
        }

        .wrapper {
            width: 100%;
            padding: 40px 16px;
            background-color: #f4f6f9;
        }

        .container {
            max-width: 560px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        }

        .header {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            padding: 40px 32px 32px;
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-size: 26px;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: -0.5px;
        }

        .header p {
            margin: 8px 0 0;
            font-size: 14px;
            color: rgba(255, 255, 255, 0.7);
        }

        .body {
            padding: 36px 32px;
        }

        .greeting {
            font-size: 18px;
            font-weight: 600;
            color: #1a1a2e;
            margin: 0 0 12px;
        }

        .text {
            font-size: 15px;
            line-height: 1.7;
            color: #555555;
            margin: 0 0 24px;
        }

        .credentials-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 20px 24px;
            margin: 0 0 28px;
        }

        .credentials-box .label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #94a3b8;
            margin-bottom: 4px;
        }

        .credentials-box .value {
            font-size: 15px;
            font-weight: 600;
            color: #1a1a2e;
            word-break: break-all;
        }

        .credentials-box .row {
            margin-bottom: 16px;
        }

        .credentials-box .row:last-child {
            margin-bottom: 0;
        }

        .divider {
            height: 1px;
            background-color: #e2e8f0;
            margin: 0 0 24px;
        }

        .btn {
            display: inline-block;
            padding: 13px 28px;
            background: linear-gradient(135deg, #0f3460, #16213e);
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            letter-spacing: 0.2px;
        }

        .footer {
            padding: 24px 32px;
            background-color: #f8fafc;
            border-top: 1px solid #e2e8f0;
            text-align: center;
        }

        .footer p {
            font-size: 13px;
            color: #94a3b8;
            margin: 0;
            line-height: 1.6;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="container">

            {{-- Header --}}
            <div class="header">
                <h1>{{ config('app.name') }}</h1>
                <p>Welcome to your account</p>
            </div>

            {{-- Body --}}
            <div class="body">
                <p class="greeting">Hi {{ $name }}, welcome aboard! 👋</p>
                <p class="text">
                    Your account has been created successfully. Here are your login details:
                </p>

                {{-- Credentials --}}
                <div class="credentials-box">
                    <div class="row">
                        <div class="label">Email Address</div>
                        <div class="value">{{ $email }}</div>
                    </div>
                    @isset($password)
                        <div class="row">
                            <div class="label">Password</div>
                            <div class="value">**************</div>
                        </div>
                    @endisset
                </div>

                <div class="divider"></div>

                <p class="text">
                    Click the button below to log in to your account and start exploring.
                </p>

                <a href="{{ $login_url }}" class="btn">Log In to Your Account</a>
            </div>

            {{-- Footer --}}
            <div class="footer">
                <p>
                    If you did not create an account, no further action is required.<br>
                    &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                </p>
            </div>

        </div>
    </div>
</body>

</html>
