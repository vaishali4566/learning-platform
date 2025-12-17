<!DOCTYPE html>
<html>
<head>
    <style>
        body { text-align: center; font-family: Arial, sans-serif; padding: 100px; }
        .certificate { border: 10px solid #00C2FF; padding: 50px; border-radius: 20px; }
        h1 { font-size: 48px; color: #00C2FF; }
        h2 { font-size: 32px; margin-top: 20px; }
        p { font-size: 20px; margin-top: 40px; }
    </style>
</head>
<body>
    <div class="certificate">
    <h1>Certificate of Completion</h1>
    <h2>{{ $course_name }}</h2>
    <p>Presented to</p>
    <h2>{{ $user_name }}</h2>
    <p>on {{ $issued_at }}</p>
    <p>Certificate Number: {{ $certificate_number }}</p>
</div>

</body>
</html>
