<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        die("パスワードが一致しません。");
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("UPDATE users SET password = :password WHERE email = :email");
    $stmt->execute(['password' => $hashed_password, 'email' => $email]);

    echo "パスワードが正常に更新されました。";
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
    <div id="logo">
            <a href="イベントの閲覧と選択.php">
                <img src="img/image.png" alt="WARIPAYロゴ">
            </a>
        </div>
    <div class="container">
        <h1>パスワードの再設定</h1>
        <form action="update_password.php" method="post">
       
            <input type="password"id="password" name="password" placeholder="新しいパスワードを入力" required> 

            <input type="password" id="confirm_password" name="confirm_password" placeholder="確認用パスワードを入力" required>
            <button type="btn" type="submit">パスワードの再設定</button>
        </form>
    </div>
    </div>
</body>
</html>
