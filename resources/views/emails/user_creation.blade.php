<html>
<head>
    <title>New User Creation Request</title>
</head>
<body>
    <h1>New User Creation Request</h1>
    <p>A new user creation request has been submitted. Please click the link below to complete the process:</p>
    <a href="{{ url('/user-details/' . $slug . '?token=' . $token) }}">Complete User Creation</a>
</body>
</html>