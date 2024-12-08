<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>わりペイ - ログイン</title>
    <link rel="stylesheet" href="ログイン.css">
</head>
<body>
    <div id="main-container">
    <div id="logo">
            <a href="イベントの閲覧と選択.php">
                <img src="img/image.png" alt="WARIPAYロゴ">
            </a>
    </div>
    <div class="container">
       
        <hr>
        <form>
            <input type="text" placeholder="メールアドレス" required>
            <input type="password" placeholder="パスワード" required>
            <button type="submit" class="login-button" id="login">ログイン</button>
            <button type="button" class="register-button" id="new-button">新規登録</button>
            <div class="passwasu"><a href="パスワード再設定メール送信.php">パスワードを忘れた方はこちら</a></div>
        </form>
    </div>
    </div>
    <script>
    
        document.getElementById('new-button').addEventListener('click', function () {
            window.location.href = '会員登録.php';
        });
        document.getElementById('login').addEventListener('click', function () {
            window.location.href = 'イベントの閲覧と選択.php';
        });
    </script>
</body>
</html>
