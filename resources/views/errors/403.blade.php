<!DOCTYPE html>
<html>
<head>
    <title>صفحه یافت نشد - 404</title>
    <link href="https://cdn.jsdelivr.net/gh/rastikerdar/sahel-font@v3.4.0/dist/font-face.css" rel="stylesheet" type="text/css" />
    <style>
        body {
            font-family: Sahel, Tahoma, sans-serif;
            text-align: center;
            padding: 50px 20px;
            direction: rtl;
            background-color: #f8f9fa;
            color: #343a40;
        }
        .error-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 40px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        h1 {
            font-size: 5rem;
            color: #dc3545;
            margin: 0;
            line-height: 1;
        }
        h2 {
            font-size: 1.8rem;
            margin: 20px 0;
            color: #495057;
        }
        p {
            font-size: 1.1rem;
            margin-bottom: 30px;
            color: #6c757d;
        }
        .btn {
            display: inline-block;
            padding: 10px 25px;
            background-color: #0d6efd;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background-color: #0b5ed7;
            color: white;
            text-decoration: none;
        }
    </style>
</head>
<body>
<div class="error-container">
    <h1>404</h1>
    <h2>صفحه مورد نظر یافت نشد</h2>
    <p>متاسفانه صفحه‌ای که به دنبال آن هستید وجود ندارد یا حذف شده است.</p>
    <a href="{{ url('/') }}" class="btn">بازگشت به صفحه اصلی</a>
</div>
</body>
</html>
