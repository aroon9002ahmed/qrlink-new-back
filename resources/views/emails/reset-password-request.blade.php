<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="x-apple-disable-message-reformatting">
    <title>Reset Your Password — {{ $appName }}</title>
    <style>
        /* Reset */
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; }
        table { border-collapse: collapse !important; }
        body { height: 100% !important; margin: 0 !important; padding: 0 !important; width: 100% !important; }
        a[x-apple-data-detectors] { color: inherit !important; text-decoration: none !important; }

        /* Fonts */
        @media screen {
            @font-face {
                font-family: 'Inter';
                font-style: normal;
                font-weight: 400;
                src: url(https://fonts.gstatic.com/s/inter/v13/UcCO3FwrK3iLTeHuS_fvQtMwCp50KnMw2boKoduKmMEVuLyfAZ9hiJ-Ek-_EeA.woff) format('woff');
            }
            @font-face {
                font-family: 'Inter';
                font-style: normal;
                font-weight: 600;
                src: url(https://fonts.gstatic.com/s/inter/v13/UcCO3FwrK3iLTeHuS_fvQtMwCp50KnMw2boKoduKmMEVuGKYAZ9hiJ-Ek-_EeA.woff) format('woff');
            }
            @font-face {
                font-family: 'Inter';
                font-style: normal;
                font-weight: 700;
                src: url(https://fonts.gstatic.com/s/inter/v13/UcCO3FwrK3iLTeHuS_fvQtMwCp50KnMw2boKoduKmMEVuFuYAZ9hiJ-Ek-_EeA.woff) format('woff');
            }
        }

        @media only screen and (max-width: 620px) {
            .email-container { width: 100% !important; }
            .px-mobile { padding-left: 24px !important; padding-right: 24px !important; }
        }
    </style>
</head>
<body style="background-color: #f4f4f5; margin: 0; padding: 0;">

    <!-- PREHEADER -->
    <div style="display:none; max-height:0; overflow:hidden; mso-hide:all;">
        Reset your {{ $appName }} account password — this link expires in 60 minutes.
        &nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;
    </div>

    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td style="padding: 40px 16px;" align="center">

                <!-- Email Container -->
                <table class="email-container" role="presentation" border="0" cellpadding="0" cellspacing="0" width="600"
                    style="max-width: 600px; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 32px rgba(0,0,0,0.10);">

                    <!-- HEADER BRAND BAR -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #0f0f0f 0%, #1a1a1a 100%); padding: 36px 48px;" align="center">
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td align="center">
                                        <!-- Brand Name -->
                                        <p style="margin: 0; font-family: 'Inter', Helvetica, Arial, sans-serif; font-size: 26px; font-weight: 700; color: #ffffff; letter-spacing: -0.5px;">
                                            &#9632;&nbsp; {{ $appName }}
                                        </p>
                                        <p style="margin: 6px 0 0; font-family: 'Inter', Helvetica, Arial, sans-serif; font-size: 13px; font-weight: 400; color: #a1a1aa; letter-spacing: 0.5px; text-transform: uppercase;">
                                            Secure Account Management
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- ACCENT BAR -->
                    <tr>
                        <td style="height: 4px; background: linear-gradient(90deg, #03cd69 0%, #00b359 50%, #03cd69 100%);"></td>
                    </tr>

                    <!-- ICON + TITLE SECTION -->
                    <tr>
                        <td class="px-mobile" style="background-color: #ffffff; padding: 48px 48px 0;" align="center">
                            <!-- Lock Icon -->
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="background-color: #f0fdf4; border-radius: 50%; width: 72px; height: 72px;" align="center" valign="middle">
                                        <p style="margin: 0; font-size: 32px; line-height: 72px;">🔐</p>
                                    </td>
                                </tr>
                            </table>
                            <h1 style="margin: 24px 0 8px; font-family: 'Inter', Helvetica, Arial, sans-serif; font-size: 28px; font-weight: 700; color: #0f0f0f; letter-spacing: -0.5px; line-height: 1.2;">
                                Password Reset Request
                            </h1>
                            <p style="margin: 0; font-family: 'Inter', Helvetica, Arial, sans-serif; font-size: 16px; color: #71717a; line-height: 1.5;">
                                Hi <strong style="color: #0f0f0f;">{{ $userName }}</strong>, we received a request to reset your password.
                            </p>
                        </td>
                    </tr>

                    <!-- DIVIDER -->
                    <tr>
                        <td class="px-mobile" style="padding: 32px 48px 0;">
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td style="border-top: 1px solid #f4f4f5;"></td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- BODY CONTENT -->
                    <tr>
                        <td class="px-mobile" style="padding: 32px 48px;" align="left">
                            <p style="margin: 0 0 20px; font-family: 'Inter', Helvetica, Arial, sans-serif; font-size: 16px; color: #3f3f46; line-height: 1.7;">
                                Click the button below to set a new password for your account. This link is valid for <strong>60 minutes</strong> and can only be used once.
                            </p>

                            <!-- CTA BUTTON -->
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td align="center" style="padding: 8px 0 24px;">
                                        <a href="{{ $resetUrl }}" target="_blank"
                                            style="display: inline-block; background: #0f0f0f; color: #ffffff; font-family: 'Inter', Helvetica, Arial, sans-serif; font-size: 15px; font-weight: 600; text-decoration: none; padding: 16px 40px; border-radius: 8px; letter-spacing: 0.3px;">
                                            Reset My Password &rarr;
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <!-- Security Notice -->
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td style="background-color: #fafafa; border: 1px solid #e4e4e7; border-radius: 8px; padding: 16px 20px;">
                                        <p style="margin: 0 0 6px; font-family: 'Inter', Helvetica, Arial, sans-serif; font-size: 13px; font-weight: 600; color: #0f0f0f;">
                                            🔒 Security Notice
                                        </p>
                                        <p style="margin: 0; font-family: 'Inter', Helvetica, Arial, sans-serif; font-size: 13px; color: #71717a; line-height: 1.6;">
                                            If you didn't request a password reset, you can safely ignore this email. Your password will remain unchanged and no action is needed.
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin: 24px 0 0; font-family: 'Inter', Helvetica, Arial, sans-serif; font-size: 13px; color: #a1a1aa; line-height: 1.6;">
                                If the button above doesn't work, copy and paste this link into your browser:<br>
                                <a href="{{ $resetUrl }}" style="color: #03cd69; word-break: break-all;">{{ $resetUrl }}</a>
                            </p>
                        </td>
                    </tr>

                    <!-- FOOTER -->
                    <tr>
                        <td style="background-color: #fafafa; border-top: 1px solid #f4f4f5; padding: 28px 48px;" align="center">
                            <p style="margin: 0 0 8px; font-family: 'Inter', Helvetica, Arial, sans-serif; font-size: 13px; color: #a1a1aa;">
                                &copy; {{ $year }} {{ $appName }}. All rights reserved.
                            </p>
                            <p style="margin: 0; font-family: 'Inter', Helvetica, Arial, sans-serif; font-size: 12px; color: #d4d4d8;">
                                This is an automated email — please do not reply.
                            </p>
                        </td>
                    </tr>

                </table>
                <!-- End Email Container -->

            </td>
        </tr>
    </table>

</body>
</html>
