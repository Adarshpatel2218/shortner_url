<!DOCTYPE html>
<html>
<head>
    <title>{{ $title ?? 'Error' }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .box {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            max-width: 400px;
        }

        h1 {
            color: #dc3545;
            margin-bottom: 10px;
        }

        p {
            color: #555;
        }

        .btn {
            margin-top: 15px;
            display: inline-block;
            padding: 8px 15px;
            background: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<div class="box">
    <h1>{{ $title ?? 'Error' }}</h1>
    <p>{{ $message ?? 'Something went wrong.' }}</p>

    <a href="/" class="btn">Go Home</a>
</div>

</body>
</html>