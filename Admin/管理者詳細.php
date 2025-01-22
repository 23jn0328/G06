<?php
require_once 'config.php';
require_once 'EventDAO.php';
session_start();

if (!isset($_SESSION['adminID'])) {
    header('Location: 管理者ログイン画面.php');
    exit();
}

$userID = $_GET['userID'] ?? null;
if (!$userID) {
    echo "ユーザーIDが指定されていません。";
    exit();
}

$dao = new EventDAO();
$userDetails = $dao->getUserDetails($userID);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理者詳細</title>
    <link rel="stylesheet" href="管理者詳細.css">
</head>
<body>
    <div class="container">
        <div class="user-info-delete">
            <div class="user-info">
                <p>会員ID: <?= htmlspecialchars($userDetails['会員ID'] ?? '不明') ?></p>
                <p>メールアドレス: <?= htmlspecialchars($userDetails['メールアドレス'] ?? '不明') ?></p>
            </div>
            <form method="POST" action="deleteUser.php">
                <input type="hidden" name="userID" value="<?= htmlspecialchars($userID) ?>">
                <button type="submit" class="delete-btn">アカウント削除</button>
            </form>
        </div>
        <table>
            <thead>
                <tr>
                    <th>イベント名</th>
                    <th>出来事作成数</th>
                    <th>イベント作成日時</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($userDetails['events'])): ?>
                    <?php foreach ($userDetails['events'] as $event): ?>
                        <tr>
                            <td><?= htmlspecialchars($event['イベント名'] ?? '不明') ?></td>
                            <td><?= htmlspecialchars($event['出来事数'] ?? '0') ?></td>
                            <td><?= htmlspecialchars($event['作成日時'] ?? '不明') ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3">イベントがありません。</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <a href="管理者メイン.php" class="returnbtn">戻る</a>
    </div>
</body>
</html>
