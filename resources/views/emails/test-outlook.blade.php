<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket Confirmation - Syscodes Support</title>
    <!--[if mso]>
    <xml>
        <o:OfficeDocumentSettings>
            <o:AllowPNG/>
            <o:PixelsPerInch>96</o:PixelsPerInch>
        </o:OfficeDocumentSettings>
    </xml>
    <![endif]-->
</head>

<body style="margin: 0; padding: 0; background-color: #f5f5f5; font-family: Arial, sans-serif;">
    <center>
        <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
            <!-- Header -->
            <tr>
                <td align="center" bgcolor="#c8196d" style="padding: 30px; background: #c8196d; color: #ffffff;">
                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                        <tr>
                            <td align="center"
                                style="font-size: 28px; font-weight: bold; letter-spacing: 1px; padding-bottom: 10px; color: #ffffff;">
                                SYSCODES SUPPORT
                            </td>
                        </tr>
                        <tr>
                            <td align="center" style="font-size: 22px; padding: 15px 0; color: #ffffff;">
                                Ticket Opening Mail
                            </td>
                        </tr>
                        <tr>
                            <td align="center">
                                <div
                                    style="display: inline-block; background-color: #ffffff; color: #c8196d; padding: 6px 15px; border-radius: 20px; font-weight: bold; font-size: 14px; margin-top: 10px;">
                                    STATUS: OPEN
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <!-- Body -->
            <tr>
                <td align="center" bgcolor="#ffffff" style="padding: 30px;">
                    <!-- Greeting -->
                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                        <tr>
                            <td style="color: #0e0c1b; line-height: 1.6; padding-bottom: 20px;">
                                <p>Dear {{ $clientName ?? 'Sir/Ma' }},</p>
                                <p>Thank you for contacting Syscodes Support. We've received your request and our team
                                    is already working on it.</p>
                            </td>
                        </tr>
                    </table>

                    <!-- Confirmation Message -->
                    <table border="0" cellpadding="0" cellspacing="0" width="100%"
                        style="background-color: #FBFBFB; border-radius: 8px; padding: 20px; margin-bottom: 25px; border-left: 4px solid #f570b0;">
                        <tr>
                            <td align="center" style="color: #0e0c1b;">
                                Your ticket has been successfully registered in our system and is being reviewed by our
                                technical team.
                            </td>
                        </tr>
                    </table>

                    <!-- Ticket Info -->
                    <table border="0" cellpadding="0" cellspacing="0" width="100%"
                        style="background-color: #FBFBFB; border-radius: 8px; padding: 20px; margin-bottom: 25px; border-left: 4px solid #f570b0;">
                        <tr>
                            <td>
                                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                    <tr>
                                        <td width="120"
                                            style="color: #6e6d76; font-weight: 500; padding-bottom: 12px; border-bottom: 1px solid #DFDFE2;">
                                            Reference ID:</td>
                                        <td
                                            style="color: #0e0c1b; font-weight: 600; padding-bottom: 12px; border-bottom: 1px solid #DFDFE2;">
                                            {{ $verificationId ?? '#TK-N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td width="120"
                                            style="color: #6e6d76; font-weight: 500; padding: 12px 0; border-bottom: 1px solid #DFDFE2;">
                                            Issue Reported:</td>
                                        <td
                                            style="color: #0e0c1b; font-weight: 600; padding: 12px 0; border-bottom: 1px solid #DFDFE2;">
                                            {{ $clientComplaint ?? 'Not Specified' }}</td>
                                    </tr>
                                    <tr>
                                        <td width="120" style="color: #6e6d76; font-weight: 500; padding: 12px 0;">
                                            Reported On:</td>
                                        <td style="color: #0e0c1b; font-weight: 600; padding: 12px 0;">
                                            {{ $startDate ?? now()->format('F j, Y') }} at
                                            {{ $startTime ?? now()->format('g:i A') }}</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>

                    <!-- Next Steps -->
                    <table border="0" cellpadding="0" cellspacing="0" width="100%"
                        style="background-color: #F4F4F5; border-radius: 8px; padding: 20px; margin: 25px 0;">
                        <tr>
                            <td align="center"
                                style="color: #c8196d; font-weight: 600; font-size: 18px; padding-bottom: 15px;">
                                What to Expect Next
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <ol style="padding-left: 20px; color: #0e0c1b; line-height: 1.5;">
                                    <li style="margin-bottom: 10px;">Our support team will review your complaint shortly
                                    </li>
                                    <li style="margin-bottom: 10px;">You'll receive updates on your ticket progress</li>
                                    <li style="margin-bottom: 10px;">We may contact you if additional information is
                                        needed</li>
                                    <li>Once resolved, we'll notify you.</li>
                                </ol>
                            </td>
                        </tr>
                    </table>

                    <!-- Contact Box -->
                    <table border="0" cellpadding="0" cellspacing="0" width="100%"
                        style="background-color: #F4F4F5; border-radius: 8px; padding: 20px; margin: 25px 0; text-align: center;">
                        <tr>
                            <td style="color: #c8196d; font-weight: 600; font-size: 18px; padding-bottom: 15px;">
                                Need Immediate Assistance?
                            </td>
                        </tr>
                        <tr>
                            <td style="color: #0e0c1b; line-height: 1.8;">
                                <p>Call: <a href="tel:+2348186249685"
                                        style="color: #c8196d; text-decoration: none; font-weight: 600;">+234 818 624
                                        9685</a></p>
                                <p>Email: <a href="mailto:support@syscodescomms.com"
                                        style="color: #c8196d; text-decoration: none; font-weight: 600;">support@syscodescomms.com</a>
                                </p>
                            </td>
                        </tr>
                    </table>

                    <!-- Button -->
                    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin: 30px 0;">
                        <tr>
                            <td align="center">
                                <a href="{{ $ticketUrl ?? '#' }}"
                                    style="display: block; background: #c8196d; color: white; text-align: center; padding: 14px; border-radius: 6px; text-decoration: none; font-weight: 600;">VIEW
                                    TICKET STATUS</a>
                            </td>
                        </tr>
                    </table>

                    <!-- Signature Section -->
                    <table border="0" cellpadding="0" cellspacing="0" width="100%"
                        style="margin-top: 40px; padding-top: 25px; border-top: 1px solid #DFDFE2;">
                        <tr>
                            <td>
                                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                    <tr>
                                        <td width="60" valign="top" style="padding-right: 15px;">
                                            <div
                                                style="width: 60px; height: 60px; background: #6e6d76; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #ffffff; font-weight: bold; font-size: 14px; text-align: center;">
                                                SCL
                                            </div>
                                        </td>
                                        <td valign="top">
                                            <div
                                                style="font-size: 20px; font-weight: 700; color: #0e0c1b; margin-bottom: 4px;">
                                                Babatunde Salawu</div>
                                            <div style="font-size: 15px; color: #6e6d76; font-weight: 500;">Lead IT
                                                Operations</div>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-top: 20px;">
                                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                    <tr>
                                        <td width="24" valign="top" style="padding-right: 12px;">
                                            <div
                                                style="width: 24px; height: 24px; background-color: #f570b0; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #ffffff; font-size: 12px; font-weight: bold;">
                                                E</div>
                                        </td>
                                        <td valign="middle">
                                            <a href="mailto:shabatunde@syscodescoms.com"
                                                style="color: #0e0c1b; text-decoration: none; font-size: 15px;">shabatunde@syscodescoms.com</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="24" valign="top" style="padding: 10px 12px 0 0;">
                                            <div
                                                style="width: 24px; height: 24px; background-color: #f570b0; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #ffffff; font-size: 12px; font-weight: bold;">
                                                M</div>
                                        </td>
                                        <td valign="middle" style="padding-top: 10px;">
                                            <a href="tel:08138504844"
                                                style="color: #0e0c1b; text-decoration: none; font-size: 15px;">08138504844</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="24" valign="top" style="padding: 10px 12px 0 0;">
                                            <div
                                                style="width: 24px; height: 24px; background-color: #f570b0; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #ffffff; font-size: 12px; font-weight: bold;">
                                                T</div>
                                        </td>
                                        <td valign="middle" style="padding-top: 10px;">
                                            <a href="tel:08051835090"
                                                style="color: #0e0c1b; text-decoration: none; font-size: 15px;">08051835090</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="24" valign="top" style="padding: 10px 12px 0 0;">
                                            <div
                                                style="width: 24px; height: 24px; background-color: #f570b0; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #ffffff; font-size: 12px; font-weight: bold;">
                                                A</div>
                                        </td>
                                        <td valign="middle" style="padding-top: 10px;">
                                            <div style="color: #0e0c1b; font-size: 15px;">Syscodes Communications
                                                Limited, 3rd Floor, 19 Toyin st Allen, Neja.</div>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 20px 0;">
                                <div style="height: 1px; background: #c8196d; margin: 20px 0; opacity: 0.6;"></div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                    <tr>
                                        <td>
                                            <span
                                                style="font-weight: 700; color: #0e0c1b; font-size: 18px; margin-right: 8px;">Syscodes</span>
                                            <span style="color: #6e6d76; font-size: 14px;">Communications
                                                Limited</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding-top: 10px;">
                                            <a href="https://www.syscodescomms.com"
                                                style="display: block; color: #c8196d; text-decoration: none; font-weight: 600;">www.syscodescomms.com</a>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <!-- Footer -->
            <tr>
                <td align="center" bgcolor="#0e0c1b" style="padding: 25px; color: #DFDFE2; font-size: 14px;">
                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                        <tr>
                            <td align="center">
                                © {{ date('Y') }} Syscodes Communications Limited. All rights reserved.
                            </td>
                        </tr>
                        <tr>
                            <td align="center" style="padding-top: 15px;">
                                <a href="#"
                                    style="color: #DFDFE2; margin: 0 10px; text-decoration: none;">Privacy Policy</a>
                                <a href="#" style="color: #DFDFE2; margin: 0 10px; text-decoration: none;">Terms
                                    of Service</a>
                                <a href="#"
                                    style="color: #DFDFE2; margin: 0 10px; text-decoration: none;">Contact Us</a>
                            </td>
                        </tr>
                        <tr>
                            <td align="center" style="padding-top: 20px;">
                                <a href="#"
                                    style="display: inline-block; width: 36px; height: 36px; background-color: rgba(255, 255, 255, 0.1); border-radius: 50%; line-height: 36px; text-align: center; color: #ffffff; margin: 0 5px; text-decoration: none;">F</a>
                                <a href="#"
                                    style="display: inline-block; width: 36px; height: 36px; background-color: rgba(255, 255, 255, 0.1); border-radius: 50%; line-height: 36px; text-align: center; color: #ffffff; margin: 0 5px; text-decoration: none;">T</a>
                                <a href="#"
                                    style="display: inline-block; width: 36px; height: 36px; background-color: rgba(255, 255, 255, 0.1); border-radius: 50%; line-height: 36px; text-align: center; color: #ffffff; margin: 0 5px; text-decoration: none;">I</a>
                                <a href="#"
                                    style="display: inline-block; width: 36px; height: 36px; background-color: rgba(255, 255, 255, 0.1); border-radius: 50%; line-height: 36px; text-align: center; color: #ffffff; margin: 0 5px; text-decoration: none;">L</a>
                            </td>
                        </tr>
                        <tr>
                            <td align="center" style="padding-top: 20px; color: #DFDFE2; font-size: 12px;">
                                You're receiving this email because you recently submitted a support request.
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </center>
</body>

</html>
