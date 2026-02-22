<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy - WallpaperCave</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/style.css'])
    
    <style>
        body {
            background: #0a0a0a;
            color: #fff;
            font-family: 'Inter', sans-serif;
        }
        
        .privacy-container {
            max-width: 900px;
            margin: 100px auto 50px;
            padding: 40px;
            background: #141414;
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        
        .privacy-container h1 {
            font-size: 36px;
            font-weight: 700;
            margin-bottom: 10px;
            color: #f5c542;
        }
        
        .privacy-container .last-updated {
            color: #ffffff99;
            margin-bottom: 30px;
            font-size: 14px;
        }
        
        .privacy-container h2 {
            font-size: 24px;
            font-weight: 600;
            margin-top: 30px;
            margin-bottom: 15px;
            color: #fff;
        }
        
        .privacy-container h3 {
            font-size: 18px;
            font-weight: 600;
            margin-top: 20px;
            margin-bottom: 10px;
            color: #f5c542;
        }
        
        .privacy-container p {
            line-height: 1.8;
            margin-bottom: 15px;
            color: #ffffffcc;
        }
        
        .privacy-container ul {
            margin-bottom: 15px;
            padding-left: 25px;
        }
        
        .privacy-container ul li {
            margin-bottom: 8px;
            color: #ffffffcc;
            line-height: 1.6;
        }
        
        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: linear-gradient(135deg, #f5c542, #e6b10e);
            border: none;
            border-radius: 8px;
            color: #000;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            margin-bottom: 30px;
        }
        
        .back-btn:hover {
            background: linear-gradient(135deg, #f9d97c, #f5c542);
            transform: translateY(-2px);
            color: #000;
        }
    </style>
</head>
<body>
    <div class="privacy-container">
        <a href="{{ url('/') }}" class="back-btn">
            <i class="fa-solid fa-arrow-left"></i> Back to Home
        </a>
        
        <h1>Privacy Policy</h1>
        <p class="last-updated">Last updated: {{ date('F d, Y') }}</p>
        
        <p>Welcome to WallpaperCave. We respect your privacy and are committed to protecting your personal data. This privacy policy will inform you about how we handle your personal data when you visit our website and tell you about your privacy rights.</p>
        
        <h2>1. Information We Collect</h2>
        <p>We may collect, use, store and transfer different kinds of personal data about you:</p>
        <ul>
            <li><strong>Identity Data:</strong> Name, username, or similar identifier</li>
            <li><strong>Contact Data:</strong> Email address</li>
            <li><strong>Technical Data:</strong> IP address, browser type and version, device information</li>
            <li><strong>Usage Data:</strong> Information about how you use our website, products and services</li>
            <li><strong>Content Data:</strong> Wallpapers you upload, likes, downloads, and comments</li>
        </ul>
        
        <h2>2. How We Use Your Information</h2>
        <p>We use your personal data for the following purposes:</p>
        <ul>
            <li>To register you as a new user and manage your account</li>
            <li>To provide and maintain our service</li>
            <li>To process and store your uploaded wallpapers</li>
            <li>To track your likes, downloads, and preferences</li>
            <li>To communicate with you about updates and changes</li>
            <li>To improve our website and user experience</li>
            <li>To detect and prevent fraud or abuse</li>
        </ul>
        
        <h2>3. Data Storage and Security</h2>
        <p>We implement appropriate security measures to protect your personal information:</p>
        <ul>
            <li>Your password is encrypted using industry-standard hashing algorithms</li>
            <li>Uploaded wallpapers are stored securely on GitHub</li>
            <li>We use secure connections (HTTPS) to protect data in transit</li>
            <li>Access to personal data is restricted to authorized personnel only</li>
        </ul>
        
        <h2>4. Cookies and Tracking</h2>
        <p>We use cookies and similar tracking technologies to:</p>
        <ul>
            <li>Maintain your login session</li>
            <li>Remember your preferences</li>
            <li>Analyze website traffic and usage patterns</li>
            <li>Improve website functionality</li>
        </ul>
        <p>You can control cookies through your browser settings, but disabling them may affect website functionality.</p>
        
        <h2>5. Third-Party Services</h2>
        <p>We use the following third-party services:</p>
        <ul>
            <li><strong>GitHub:</strong> For wallpaper image storage and hosting</li>
            <li><strong>Facebook:</strong> For social media integration and wallpaper sharing</li>
        </ul>
        <p>These services have their own privacy policies governing their use of your information.</p>
        
        <h2>6. Your Rights</h2>
        <p>You have the following rights regarding your personal data:</p>
        <ul>
            <li><strong>Access:</strong> Request access to your personal data</li>
            <li><strong>Correction:</strong> Request correction of inaccurate data</li>
            <li><strong>Deletion:</strong> Request deletion of your data</li>
            <li><strong>Data Portability:</strong> Request transfer of your data to another service</li>
            <li><strong>Objection:</strong> Object to processing of your personal data</li>
        </ul>
        
        <h2>7. Data Retention</h2>
        <p>We retain your personal data only for as long as necessary to fulfill the purposes outlined in this privacy policy. When you delete your account, we will delete or anonymize your personal data, except where we are required to retain it for legal purposes.</p>
        
        <h2>8. Children's Privacy</h2>
        <p>Our service is not directed to individuals under the age of 13. We do not knowingly collect personal information from children. If you are a parent or guardian and believe your child has provided us with personal data, please contact us.</p>
        
        <h2>9. Changes to This Privacy Policy</h2>
        <p>We may update our Privacy Policy from time to time. We will notify you of any changes by posting the new Privacy Policy on this page and updating the "Last updated" date.</p>
        
        <h2>10. Contact Us</h2>
        <p>If you have any questions about this Privacy Policy, please contact us:</p>
        <ul>
            <li>Email: privacy@wallpapercave.com</li>
            <li>Website: {{ url('/') }}</li>
        </ul>
        
        <div class="mt-5">
            <a href="{{ url('/') }}" class="back-btn">
                <i class="fa-solid fa-arrow-left"></i> Back to Home
            </a>
        </div>
    </div>
</body>
</html>
