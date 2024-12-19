<?php
require 'MemberDAO.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $Pw = $_POST['password'];
    $confirm_Pw = $_POST['confirm_password'];

    if ($Pw !== $confirm_Pw) {
        die("パスワードが一致しません。");
    }
    echo "パスワードが正常に更新されました。";
    header('Location:イベントの閲覧と選択');
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="パスワード再設定.css">
    <title>パスワードの再設定</title>
</head>
<body>
    <div id="main-container">
    <div class="container">
    <div id="logo">
                <img src="img/image.png" alt="WARIPAYロゴ">
            </a>
        </div>
        <h1>パスワードの再設定</h1>
        <form action="" method="POST">
       
            <input type="password"id="password" name="password" placeholder="新しいパスワードを入力" required> 

            <input type="password" id="confirm_password" name="confirm_password" placeholder="確認用パスワードを入力" required>
            <button type="btn" type="submit">パスワードの再設定</button>
        </form>
    </div>
    </div>
</body>
</html>
