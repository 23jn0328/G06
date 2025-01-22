<?php
require_once 'config.php';
require_once 'MemberDAO.php';
require_once 'AdminMemberDAO.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $adminID = $_POST['adminID'];
    $password = $_POST['password'];

    $dao = new AdminMemberDAO();
    $isValid = $dao->validateAdmin($adminID, $password);

    if ($isValid) {
        $_SESSION['adminID'] = $adminID;
        header('Location: 管理者メイン.php');
        exit();
    } else {
        $error = "IDまたはパスワードが間違っています。";
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理者ログイン</title>
    <link rel="stylesheet" href="管理者ログイン.css">
</head>
<body>
    <div class="login-container">
        <h1>管理者ログイン画面</h1>
        <form method="POST">
            <label for="adminID">管理者ID</label>
            <input type="text" id="adminID" name="adminID" required>
            <label for="password">パスワード</label>
            <input type="password" id="password" name="password" required>
            <button type="submit" class="login-button">ログイン</button>
            <?php if (!empty($error)) { echo "<p style='color: red;'>$error</p>"; } ?>
        </form>
    </div>
</body>
</html>
