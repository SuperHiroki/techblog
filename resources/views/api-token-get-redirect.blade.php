<!doctype html>
<html>
<head>
    <title>APIトークン取得</title>
</head>
<body>
    <div>
        APIトークンを取得するリダイレクトページ
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const apiToken = "{{ session('api_token') }}";
        if (apiToken) {
            console.log('PPPPPPPPPPPPPPPP API Token: ' + apiToken);
            localStorage.setItem('apiToken', apiToken);

            // APIトークンを保存した後にホームページにリダイレクト
            window.location.href = '{{ route("home") }}';
        } else {
            // トークンが存在しない場合のエラー処理
            console.error('APIトークンが見つかりませんでした。');
            // 他の適切な処理をここに追加
        }
    });
    </script>
</body>
</html>
