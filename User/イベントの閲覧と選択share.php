<?php
// 必要なファイルの読み込み
require_once 'DAO.php';

session_start();
if (!isset($_SESSION['member_id'])) {
    // ログインしていない場合はログインページへリダイレクト
    header('Location: ログイン.php');
    exit;
}

$user_id = $_SESSION['member_id'];

try {
    // データベース接続
    $dbh = DAO::get_db_connect();

    // ユーザーが共有されたイベントを取得（EIDは共有URLから渡される）
    if (isset($_GET['eventID'])) {
        $event_id = $_GET['eventID'];

        $sql = "
            SELECT EID, EventName, EventStart 
            FROM イベント 
            WHERE EID = :event_id";
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':event_id', $event_id, PDO::PARAM_STR);
        $stmt->execute();

        // 結果を取得
        $event = $stmt->fetch(PDO::FETCH_ASSOC);
    }
} catch (PDOException $e) {
    echo "データベースエラー: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>共有イベント</title>
    <link rel="stylesheet" href="イベントの閲覧と選択share.css">
</head>
<body>
    <div class="container">
        <header>
            <div id="logo">
                <a href="イベントの閲覧と選択.php">
                    <img src="img/image.png" alt="WARIPAYロゴ">
                </a>
            </div>
        </header>

        <!-- 共有イベント詳細 -->
<div class="event-details">
    <?php if (!empty($event)): ?>
        <div class="event-item">
            <!-- クリック可能なリンク -->
            <a href="出来事の閲覧と選択share.php?eventID=<?= htmlspecialchars($event['EID'], ENT_QUOTES, 'UTF-8') ?>" class="event-link">
                <div class="event-name"><?= htmlspecialchars($event['EventName'], ENT_QUOTES, 'UTF-8') ?></div>
                <div class="event-date">開始日時: <?= htmlspecialchars($event['EventStart'], ENT_QUOTES, 'UTF-8') ?></div>
            </a>
        </div>
    <?php else: ?>
        <p>現在、表示するイベントはありません。</p>
    <?php endif; ?>
</div>

    </div>
</body>
</html>
