<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office" lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="format-detection" content="telephone=no, date=no, address=no, email=no">
    <title>{{ $subject }}</title>

    <!--[if mso]>
    <xml>
        <o:OfficeDocumentSettings>
            <o:AllowPNG/>
            <o:PixelsPerInch>96</o:PixelsPerInch>
        </o:OfficeDocumentSettings>
    </xml>
    <![endif]-->

    <style type="text/css">
        /* Reset */
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; border: 0; outline: none; text-decoration: none; }

        /* Client resets */
        body { margin: 0 !important; padding: 0 !important; width: 100% !important; }
        a[x-apple-data-detectors] { color: inherit !important; text-decoration: none !important; }

        /* Responsive */
        @media only screen and (max-width: 600px) {
            .email-wrapper { width: 100% !important; }
            .email-container { width: 100% !important; }
            .stack-column { display: block !important; width: 100% !important; }
        }
    </style>
</head>

<body style="margin: 0; padding: 0; background-color: #f1f5f9; width: 100%;">

    {{-- Outer wrapper --}}
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #f1f5f9;">
        <tr>
            <td style="padding: 40px 16px;">

                {{-- Email container --}}
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="560" align="center" style="margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; border: 1px solid #e2e8f0;" class="email-container">

                    {{-- ========== HEADER ========== --}}
                    <!--[if mso]>
                    <tr>
                        <td style="background-color: #0f3460; padding: 0;">
                            <v:rect xmlns:v="urn:schemas-microsoft-com:vml" fill="true" stroke="false" style="width:560px;">
                                <v:fill type="gradient" color="#1a1a2e" color2="#0f3460" angle="135"/>
                                <v:textbox inset="0,0,0,0">
                    <![endif]-->
                    <tr>
                        <td align="center" style="background-color: #0f3460; padding: 40px 32px 32px;">
                            <!--[if mso]><table role="presentation" border="0" cellspacing="0" cellpadding="0" width="100%"><tr><td align="center"><![endif]-->
                            <p style="margin: 0; font-family: Arial, sans-serif; font-size: 24px; font-weight: bold; color: #ffffff; letter-spacing: -0.5px; line-height: 1.2;">
                                {{ config('app.name') }}
                            </p>
                            <p style="margin: 8px 0 0; font-family: Arial, sans-serif; font-size: 13px; color: #93c5fd; line-height: 1.4;">
                                Welcome to your account
                            </p>
                            <!--[if mso]></td></tr></table><![endif]-->
                        </td>
                    </tr>
                    <!--[if mso]>
                                </v:textbox>
                            </v:rect>
                        </td>
                    </tr>
                    <![endif]-->

                    {{-- ========== BODY ========== --}}
                    <tr>
                        <td style="padding: 36px 32px 28px; font-family: Arial, sans-serif;">

                            {{-- Greeting --}}
                            <p style="margin: 0 0 12px; font-size: 18px; font-weight: bold; color: #1e293b; line-height: 1.4;">
                                Hi {{ $name }}, welcome aboard! 👋
                            </p>

                            {{-- Intro text --}}
                            <p style="margin: 0 0 28px; font-size: 15px; color: #64748b; line-height: 1.7;">
                                Your account has been created successfully. Here are your login details:
                            </p>

                            {{-- Credentials box --}}
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; margin-bottom: 28px;">
                                <tr>
                                    <td style="padding: 20px 24px;">

                                        {{-- Email row --}}
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                            <tr>
                                                <td>
                                                    <p style="margin: 0 0 4px; font-family: Arial, sans-serif; font-size: 11px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.8px; color: #94a3b8;">
                                                        Email Address
                                                    </p>
                                                    <p style="margin: 0; font-family: Arial, sans-serif; font-size: 15px; font-weight: bold; color: #1e293b; word-break: break-all;">
                                                        {{ $email }}
                                                    </p>
                                                </td>
                                            </tr>
                                        </table>

                                        @isset($password)
                                        {{-- Divider --}}
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 16px 0;">
                                            <tr>
                                                <td style="height: 1px; background-color: #e2e8f0; font-size: 0; line-height: 0;">&nbsp;</td>
                                            </tr>
                                        </table>

                                        {{-- Password row --}}
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                            <tr>
                                                <td>
                                                    <p style="margin: 0 0 4px; font-family: Arial, sans-serif; font-size: 11px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.8px; color: #94a3b8;">
                                                        Password
                                                    </p>
                                                    <p style="margin: 0; font-family: 'Courier New', Courier, monospace; font-size: 15px; font-weight: bold; color: #1e293b; letter-spacing: 2px;">
                                                        **************
                                                    </p>
                                                </td>
                                            </tr>
                                        </table>
                                        @endisset

                                    </td>
                                </tr>
                            </table>

                            {{-- Divider --}}
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin-bottom: 24px;">
                                <tr>
                                    <td style="height: 1px; background-color: #e2e8f0; font-size: 0; line-height: 0;">&nbsp;</td>
                                </tr>
                            </table>

                            {{-- CTA text --}}
                            <p style="margin: 0 0 24px; font-family: Arial, sans-serif; font-size: 15px; color: #64748b; line-height: 1.7;">
                                Click the button below to log in to your account and start exploring.
                            </p>

                            {{-- CTA Button --}}
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0">
                                <tr>
                                    <td style="border-radius: 6px; background-color: #0f3460;">
                                        <!--[if mso]>
                                        <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="{{ $login_url }}" style="height:46px;v-text-anchor:middle;width:200px;" arcsize="13%" stroke="f" fillcolor="#0f3460">
                                            <w:anchorlock/>
                                            <center style="color:#ffffff;font-family:Arial,sans-serif;font-size:15px;font-weight:bold;">
                                                Log In to Your Account
                                            </center>
                                        </v:roundrect>
                                        <![endif]-->
                                        <!--[if !mso]><!-->
                                        <a href="{{ $login_url }}"
                                           target="_blank"
                                           style="display: inline-block; padding: 13px 28px; font-family: Arial, sans-serif; font-size: 15px; font-weight: bold; color: #ffffff; text-decoration: none; border-radius: 6px; background-color: #0f3460; mso-hide: all;">
                                            Log In to Your Account
                                        </a>
                                        <!--<![endif]-->
                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>

                    {{-- ========== FOOTER ========== --}}
                    <tr>
                        <td style="padding: 24px 32px; background-color: #f8fafc; border-top: 1px solid #e2e8f0;">
                            <p style="margin: 0; font-family: Arial, sans-serif; font-size: 12px; color: #94a3b8; line-height: 1.6; text-align: center;">
                                If you did not create an account, no further action is required.<br>
                                &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                            </p>
                        </td>
                    </tr>

                </table>
                {{-- End email container --}}

            </td>
        </tr>
    </table>
    {{-- End outer wrapper --}}

</body>

</html>
