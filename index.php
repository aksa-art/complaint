<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Complaint System</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            background: linear-gradient(135deg, #000000 0%, #1a0033 100%);
            min-height: 100vh;
            margin: 0;
            font-family: 'Quicksand', Arial, sans-serif;
        }
        .grid-bg {
            position: fixed;
            top: 0; left: 0; width: 100vw; height: 100vh;
            z-index: 0;
            pointer-events: none;
        }
        .main-container {
            position: relative;
            z-index: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
    </style>
    </head>
<body>
                <div class="role-bg-overlay"></div>
                <div class="role-container">
                    <h1 class="role-title-gradient">COMPLAINT SYSTEM</h1>
                    <div class="role-subtitle">SELECT YOUR ROLE</div>
                    <div class="role-divider"></div>
                    <div class="role-cards">
                        <a href="login.php#admin" class="role-card role-card-admin">
                            <div class="role-card-icon">
                                <svg width="90" height="90" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="32" cy="32" r="30" stroke="#00e6ff" stroke-width="3" fill="none"/>
                                    <path d="M32 16 L48 24 L48 40 Q32 52 16 40 L16 24 Z" stroke="#00e6ff" stroke-width="2.5" fill="none"/>
                                </svg>
                            </div>
                            <div class="role-card-title">ADMIN</div>
                            <div class="role-card-desc">System Administrator Access</div>
                            <ul class="role-card-list">
                                <li>Manage Complaints</li>
                                <li>View Analytics</li>
                                <li>Provide Feedback</li>
                            </ul>
                        </a>
                        <a href="login.php#student" class="role-card role-card-student">
                            <div class="role-card-icon">
                                <svg width="90" height="90" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="32" cy="32" r="30" stroke="#d946ef" stroke-width="3" fill="none"/>
                                    <path d="M32 24a8 8 0 1 0 0-16 8 8 0 0 0 0 16zm0 4c-8 0-16 6-16 12v4a4 4 0 0 0 4 4h24a4 4 0 0 0 4-4v-4c0-6-8-12-16-12z" stroke="#d946ef" stroke-width="2.5" fill="none"/>
                                </svg>
                            </div>
                            <div class="role-card-title">STUDENT</div>
                            <div class="role-card-desc">Student Portal Access</div>
                            <ul class="role-card-list">
                                <li>Submit Complaints</li>
                                <li>Track Status</li>
                                <li>View History</li>
                            </ul>
                        </a>
                    </div>
                    <div class="role-footer">SECURE • ENCRYPTED • CONFIDENTIAL</div>
                </div>
    <script src="js/index.js"></script>
    </body>
</html>