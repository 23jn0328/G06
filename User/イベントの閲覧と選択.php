<?php
// 必要なファイルの読み込み
require_once 'DAO.php';


// ユーザーIDの取得（ログイン後にセッションで保持していると仮定）
session_start();
if (!isset($_SESSION['member_id'])) {
    // ログインしていない場合はログインページへリダイレクト
    header('Location: ログイン.php');
    exit;
}

$user_id = $_SESSION['member_id'];

// 仮のメンバーID
// $user_id = 'M000002';

try {
    // データベース接続
    $dbh = DAO::get_db_connect();

    // ユーザーの登録イベントを取得
    $sql = "SELECT EID, EventName, EventStart FROM イベント WHERE ID = :user_id ORDER BY EventStart ASC";
    $stmt = $dbh->prepare($sql);
    
    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_STR);
    $stmt->execute();

    // 結果を取得
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    <title>イベントリスト</title>
    <link rel="stylesheet" href="イベントの閲覧と選択.css">
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

        <!-- イベント作成ボタン -->
        <button class="gradient-btn" onclick="location.href='イベント作成.php'">イベントを作成</button>

        <!-- イベントリスト -->
        <div class="event-list">
            <?php if (!empty($events)): ?>
                <?php foreach ($events as $event): ?>
                    <div class="event-item" onclick="location.href='出来事の閲覧と選択.php?eventID=<?= htmlspecialchars($event['EID'], ENT_QUOTES, 'UTF-8') ?>'">
                        <div class="event-name"><?= htmlspecialchars($event['EventName'], ENT_QUOTES, 'UTF-8') ?></div>
                        <div class="event-date">開始日時: <?= htmlspecialchars($event['EventStart'], ENT_QUOTES, 'UTF-8') ?></div>
                        <button class="manage-btn" onclick="goManageEvent(event, '<?= htmlspecialchars($event['EID'], ENT_QUOTES, 'UTF-8') ?>')">管理</button>
                        <button class="share-btn" onclick="goShareEvent(event, '<?= htmlspecialchars($event['EID'], ENT_QUOTES, 'UTF-8') ?>')">共有</button>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>現在、表示するイベントはありません。</p>
            <?php endif; ?>
        </div>
    </div>
    <script>
        function goManageEvent(event, EID) {
            event.stopPropagation();
            location.href = "イベント管理.php?eid=" + EID;
        }

        function goShareEvent(event, EID) {
            event.stopPropagation();
            const shareUrl = `${window.location.origin}/イベント管理.php?eid=${EID}`;
            if (navigator.share) {
                navigator.share({
                    title: "イベント共有",
                    text: "このイベントをチェックしてください！",
                    url: shareUrl
                })
                .then(() => console.log("シェア成功！"))
                .catch((error) => console.error("シェア失敗", error));
            } else {
                alert("このブラウザは共有機能をサポートしていません。リンクをコピーしてください: " + shareUrl);
            }
        }
    </script>
</body>
</html>
