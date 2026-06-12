<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="x-apple-disable-message-reformatting">
    <title>Password Reset Successful — {{ $appName }}</title>
    <style>
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; }
        table { border-collapse: collapse !important; }
        body { height: 100% !important; margin: 0 !important; padding: 0 !important; width: 100% !important; }
        a[x-apple-data-detectors] { color: inherit !important; text-decoration: none !important; }

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
        Your {{ $appName }} password has been successfully changed. You can now log in with your new password.
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

                    <!-- SUCCESS ACCENT BAR (green) -->
                    <tr>
                        <td style="height: 4px; background: linear-gradient(90deg, #03cd69 0%, #00b359 50%, #03cd69 100%);"></td>
                    </tr>

                    <!-- SUCCESS ICON + TITLE -->
                    <tr>
                        <td class="px-mobile" style="background-color: #ffffff; padding: 48px 48px 0;" align="center">
                            <!-- Checkmark Icon with green glow -->
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="background-color: #f0fdf4; border: 2px solid #bbf7d0; border-radius: 50%; width: 72px; height: 72px;" align="center" valign="middle">
                                        <p style="margin: 0; font-size: 34px; line-height: 72px;">✅</p>
                                    </td>
                                </tr>
                            </table>
                            <h1 style="margin: 24px 0 8px; font-family: 'Inter', Helvetica, Arial, sans-serif; font-size: 28px; font-weight: 700; color: #0f0f0f; letter-spacing: -0.5px; line-height: 1.2;">
                                Password Successfully Reset
                            </h1>
                            <p style="margin: 0; font-family: 'Inter', Helvetica, Arial, sans-serif; font-size: 16px; color: #71717a; line-height: 1.5;">
                                Hi <strong style="color: #0f0f0f;">{{ $userName }}</strong>, your password has been updated successfully.
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
                            <p style="margin: 0 0 24px; font-family: 'Inter', Helvetica, Arial, sans-serif; font-size: 16px; color: #3f3f46; line-height: 1.7;">
                                You can now sign in to your account using your new password. If you did not make this change, please contact our support team immediately.
                            </p>

                            <!-- CTA BUTTON -->
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td align="center" style="padding: 0 0 28px;">
                                        <a href="{{ $loginUrl }}" target="_blank"
                                            style="display: inline-block; background: #03cd69; color: #0f0f0f; font-family: 'Inter', Helvetica, Arial, sans-serif; font-size: 15px; font-weight: 700; text-decoration: none; padding: 16px 40px; border-radius: 8px; letter-spacing: 0.3px;">
                                            Sign In Now &rarr;
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <!-- Security Tips -->
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td style="background-color: #fafafa; border: 1px solid #e4e4e7; border-radius: 8px; padding: 20px 24px;">
                                        <p style="margin: 0 0 12px; font-family: 'Inter', Helvetica, Arial, sans-serif; font-size: 13px; font-weight: 700; color: #0f0f0f;">
                                            🛡️ Keep your account secure
                                        </p>
                                        <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
                                            <tr>
                                                <td style="padding: 4px 0;">
                                                    <p style="margin: 0; font-family: 'Inter', Helvetica, Arial, sans-serif; font-size: 13px; color: #71717a; line-height: 1.6;">
                                                        ✔ &nbsp;Use a unique password you don't use elsewhere<br>
                                                        ✔ &nbsp;Never share your password with anyone<br>
                                                        ✔ &nbsp;Contact support if you notice suspicious activity
                                                    </p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
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
