<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>わりペイ - 登録画面</title>
    <link rel="stylesheet" href="会員登録.css">

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
            <input type="password" placeholder="パスワード再入力" required>
            <button type="submit" id="touroku-button">登録</button>      
        </form>
    </div>
    </div>
    <script>
        document.getElementById('touroku-button').addEventListener('click', function () {
            window.location.href = 'イベント作成.html';
        });
    </script>
</body>
</html>
