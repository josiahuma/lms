<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Certificate</title>
    <style>
        body {
            text-align: center;
            padding: 100px;
            font-family: 'Helvetica Neue', sans-serif;
        }
        .certificate {
            border: 10px solid #ccc;
            padding: 50px;
        }
        h1 {
            font-size: 40px;
            margin-bottom: 0;
        }
        h2 {
            margin-top: 10px;
            font-size: 24px;
        }
        p {
            margin-top: 40px;
            font-size: 18px;
        }
    </style>
</head>
<body>
    <div class="certificate">
        <h1>Certificate of Completion</h1>
        <h2>This is to certify that</h2>
        <h1>{{ $user->name }}</h1>
        <h2>has successfully completed the course</h2>
        <h1>"{{ $course->title }}"</h1>
        <p>Issued on: {{ \Carbon\Carbon::now()->format('F j, Y') }}</p>
    </div>
</body>
</html>
