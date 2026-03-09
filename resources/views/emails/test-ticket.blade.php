<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket Notification Email</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        :root {
            --color-1: #f570b0;
            --color-2: #c8196d;
            --color-3: #6e6d76;
            --color-4: #0e0c1b;
            --color-5: #DFDFE2;
            --color-6: #F4F4F5;
            --color-7: #FBFBFB;
            --color-8: #ffffff;
        }

        body {
            background-color: #f5f5f5;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .email-container {
            max-width: 650px;
            background-color: var(--color-8);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .email-header {
            background: linear-gradient(135deg, var(--color-1), var(--color-2));
            padding: 30px;
            text-align: center;
            color: var(--color-8);
        }

        .logo {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 10px;
            letter-spacing: 1px;
        }

        .email-title {
            font-size: 22px;
            margin: 15px 0;
        }

        .ticket-status {
            display: inline-block;
            background-color: var(--color-8);
            color: var(--color-2);
            padding: 6px 15px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 14px;
            margin-top: 10px;
        }

        .email-body {
            padding: 30px;
        }

        .ticket-info {
            background-color: var(--color-7);
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
            border-left: 4px solid var(--color-1);
        }

        .info-row {
            display: flex;
            margin-bottom: 12px;
            padding-bottom: 12px;
            border-bottom: 1px solid var(--color-5);
        }

        .info-row:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }

        .info-label {
            width: 120px;
            color: var(--color-3);
            font-weight: 500;
        }

        .info-value {
            flex: 1;
            color: var(--color-4);
            font-weight: 600;
        }

        .message-box {
            background-color: var(--color-6);
            border-radius: 8px;
            padding: 20px;
            margin: 25px 0;
        }

        .message-title {
            color: var(--color-2);
            font-weight: 600;
            margin-bottom: 10px;
            font-size: 16px;
        }

        .message-content {
            color: var(--color-4);
            line-height: 1.6;
        }

        .action-button {
            display: block;
            background: linear-gradient(135deg, var(--color-1), var(--color-2));
            color: white;
            text-align: center;
            padding: 14px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            margin: 30px 0;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .action-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .signature-section {
            margin-top: 40px;
            padding-top: 25px;
            border-top: 1px solid var(--color-5);
        }

        .signature-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .signature-logo {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--color-3), var(--color-4));
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--color-8);
            font-weight: bold;
            margin-right: 15px;
            font-size: 14px;
            text-align: center;
        }

        .signature-name-title {
            flex: 1;
        }

        .signature-name {
            font-size: 20px;
            font-weight: 700;
            color: var(--color-4);
            margin-bottom: 4px;
        }

        .signature-title {
            font-size: 15px;
            color: var(--color-3);
            font-weight: 500;
        }

        .signature-contact-info {
            margin-bottom: 15px;
        }

        .signature-contact-item {
            display: flex;
            margin-bottom: 10px;
            align-items: center;
        }

        .signature-contact-icon {
            width: 24px;
            height: 24px;
            background-color: var(--color-1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            color: var(--color-8);
            font-size: 12px;
            font-weight: bold;
        }

        .signature-contact-text {
            color: var(--color-4);
            font-size: 15px;
        }

        .signature-contact-text a {
            color: var(--color-4);
            text-decoration: none;
        }

        .signature-contact-text a:hover {
            color: var(--color-2);
            text-decoration: underline;
        }

        .signature-divider {
            height: 1px;
            background: linear-gradient(to right, var(--color-1), var(--color-2), var(--color-3));
            margin: 20px 0;
            opacity: 0.6;
        }

        .signature-company-info {
            display: flex;
            align-items: center;
            margin-top: 20px;
        }

        .signature-company-name {
            font-weight: 700;
            color: var(--color-4);
            font-size: 18px;
            margin-right: 8px;
        }

        .signature-company-tagline {
            color: var(--color-3);
            font-size: 14px;
        }

        .signature-website {
            margin-top: 10px;
            display: block;
            color: var(--color-2);
            text-decoration: none;
            font-weight: 600;
        }

        .signature-website:hover {
            text-decoration: underline;
        }

        .email-footer {
            background-color: var(--color-4);
            color: var(--color-5);
            padding: 25px;
            text-align: center;
            font-size: 14px;
        }

        .footer-links {
            margin-top: 15px;
        }

        .footer-links a {
            color: var(--color-5);
            margin: 0 10px;
            text-decoration: none;
        }

        .social-icons {
            margin-top: 20px;
        }

        .social-icons a {
            display: inline-block;
            width: 36px;
            height: 36px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            line-height: 36px;
            text-align: center;
            color: var(--color-8);
            margin: 0 5px;
            text-decoration: none;
        }

        @media (max-width: 650px) {
            .email-container {
                border-radius: 0;
            }

            .info-row {
                flex-direction: column;
            }

            .info-label {
                width: 100%;
                margin-bottom: 5px;
            }

            .signature-header {
                flex-direction: column;
                text-align: center;
            }

            .signature-logo {
                margin-right: 0;
                margin-bottom: 15px;
            }

            .signature-contact-item {
                flex-direction: column;
                text-align: center;
            }

            .signature-contact-icon {
                margin-right: 0;
                margin-bottom: 8px;
            }
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="email-header">
            <div class="logo">SUPPORT SYSTEM</div>
            <h1 class="email-title">Support Ticket Update</h1>
            <div class="ticket-status">STATUS: OPEN</div>
        </div>

        <div class="email-body">
            <div class="ticket-info">
                <div class="info-row">
                    <div class="info-label">Ticket ID:</div>
                    <div class="info-value">#TK-2023-0876</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Date Created:</div>
                    <div class="info-value">October 15, 2023 at 2:30 PM</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Priority:</div>
                    <div class="info-value">High</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Category:</div>
                    <div class="info-value">Billing Issue</div>
                </div>
            </div>

            <div class="message-box">
                <div class="message-title">Latest Update:</div>
                <div class="message-content">
                    Hello Jane, we've received your inquiry about the billing discrepancy on your recent invoice. Our
                    team has reviewed your account and identified the issue. We've already processed a refund for the
                    overcharged amount, which should appear in your account within 5-7 business days.
                </div>
            </div>

            <div class="message-box">
                <div class="message-title">Your Original Message:</div>
                <div class="message-content">
                    "I noticed on my last invoice that I was charged twice for the premium service package. I only
                    signed up for one instance of this service. Can you please check and refund the duplicate charge?"
                </div>
            </div>

            <a href="#" class="action-button">VIEW TICKET STATUS</a>

            <p style="color: var(--color-3); text-align: center; font-size: 14px;">
                If you have any additional questions, simply reply to this email.
            </p>

            <!-- Signature Section -->
            <div class="signature-section">
                <div class="signature-header">
                    <div class="signature-logo">SCL</div>
                    <div class="signature-name-title">
                        <div class="signature-name">Babatunde Salawu</div>
                        <div class="signature-title">Lead IT Operations</div>
                    </div>
                </div>

                <div class="signature-contact-info">
                    <div class="signature-contact-item">
                        <div class="signature-contact-icon">E</div>
                        <div class="signature-contact-text">
                            <a href="mailto:shabatunde@syscodescoms.com">shabatunde@syscodescoms.com</a>
                        </div>
                    </div>

                    <div class="signature-contact-item">
                        <div class="signature-contact-icon">M</div>
                        <div class="signature-contact-text">
                            <a href="tel:08138504844">08138504844</a>
                        </div>
                    </div>

                    <div class="signature-contact-item">
                        <div class="signature-contact-icon">T</div>
                        <div class="signature-contact-text">
                            <a href="tel:08051835090">08051835090</a>
                        </div>
                    </div>

                    <div class="signature-contact-item">
                        <div class="signature-contact-icon">A</div>
                        <div class="signature-contact-text">
                            Syscodes Communications Limited, 3rd Floor, 19 Toyin st Allen, Neja.
                        </div>
                    </div>
                </div>

                <div class="signature-divider"></div>

                <div class="signature-company-info">
                    <div class="signature-company-name">Syscodes</div>
                    <div class="signature-company-tagline">Communications Limited</div>
                </div>

                <a href="https://www.syscodescomms.com" class="signature-website">www.syscodescomms.com</a>
            </div>
        </div>

        <div class="email-footer">
            <p>© 2023 Syscodes Communications Limited. All rights reserved.</p>

            <div class="footer-links">
                <a href="#">Privacy Policy</a>
                <a href="#">Terms of Service</a>
                <a href="#">Contact Us</a>
            </div>

            <div class="social-icons">
                <a href="#">F</a>
                <a href="#">T</a>
                <a href="#">I</a>
                <a href="#">L</a>
            </div>

            <p style="margin-top: 20px; color: var(--color-5); font-size: 12px;">
                You're receiving this email because you have an active ticket with our support team.
            </p>
        </div>
    </div>
</body>

</html>
