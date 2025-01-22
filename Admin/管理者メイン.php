<?php
require_once 'config.php';
require_once 'MemberDAO.php';
session_start();

if (!isset($_SESSION['adminID'])) {
    header('Location: 管理者ログイン画面.php');
    exit();
}

$dao = new MemberDAO();
$users = $dao->getAllUsersWithEventCounts();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理者メイン</title>
    <link rel="stylesheet" href="管理者メイン.css">
</head>
<body>
    <div class="container">
        <h1>管理者メイン画面</h1>
        <table>
            <thead>
                <tr>
                    <th>会員ID</th>
                    <th>イベント作成数</th>
                    <th>会員詳細</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['ID']) ?></td>
                        <td><?= htmlspecialchars($user['イベント作成数']) ?></td>
                        <td><a href="管理者詳細.php?userID=<?= urlencode($user['ID']) ?>" class="detail-btn">詳細</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="managerID">
            管理者ID: <?= htmlspecialchars($_SESSION['adminID']) ?>
            <a href="logout.php" style="color: blue;">ログアウト</a>
        </div>
    </div>
</body>
</html>
