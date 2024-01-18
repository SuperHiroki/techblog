<!doctype html>
<html>
<head>
    <title>APIトークン廃棄</title>
</head>
<body>
    <div>
        APIトークンを廃棄するリダイレクトページ
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        localStorage.setItem('apiToken', null);
        window.location.href = '{{ route("home") }}';
    });
    </script>
</body>
</html>
