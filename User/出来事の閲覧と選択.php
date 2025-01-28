<?php
require_once 'DAO.php';  // DAOクラスの読み込み
require_once 'HappenDao.php';  // HappenDaoクラスの読み込み
require_once 'EventDAO.php';  // EventDAOクラスの読み込み

// セッション開始とイベントIDの取得
session_start();
if (!isset($_SESSION['member_id'])) {
    // ログインしていない場合はログインページへリダイレクト
    header('Location: ログイン.php');
    exit;
}

$happenDao = new HappenDao();

$user_id = $_SESSION['member_id'];

// URLからイベントIDを取得
$eventID = $_GET['eventID'] ?? null;
if (!$eventID) {
    echo "イベントIDが指定されていません。";
    exit;
}

try {
    // データベース接続
    $dbh = DAO::get_db_connect();

    // イベント名と作成者IDを取得
    $sql = "SELECT EventName, ID FROM イベント WHERE EID = :eventID";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':eventID', $eventID, PDO::PARAM_STR);
    $stmt->execute();
    $event = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$event) {
        echo "イベントが見つかりません。";
        exit;
    }

    // 作成者名を取得
    $sqlCreator = "SELECT UserName FROM 会員 WHERE ID = :creatorID";
    $stmtCreator = $dbh->prepare($sqlCreator);
    $stmtCreator->bindParam(':creatorID', $event['ID'], PDO::PARAM_STR);
    $stmtCreator->execute();
    $creator = $stmtCreator->fetch(PDO::FETCH_ASSOC);

    
    // イベントメンバー一覧の取得
    $members = $happenDao->get_member_list($eventID);

    // 出来事一覧の取得
    $happens = $happenDao->get_happen_details_by_event_id($eventID);
} catch (PDOException $e) {
    echo "エラー: " . $e->getMessage();
    exit;
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WARIPAY</title>
    <link rel="stylesheet" href="出来事の閲覧と選択style.css">
</head>
<style>
    /* 全体設定 */
#logo img {
    max-width: 250px; /* ロゴの最大幅を少し小さく調整 */
    height: auto; /* アスペクト比を維持 */
    margin: 10px auto; /* 上下に少し余白を追加 */
    display: block; /* 中央揃え */
}
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #fffcfc;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
}

/* メインコンテナ */
#main-container {
    width: 500px;
    background-color: #b0b0b0;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    text-align: center;
}

/* アプリタイトル */
.app-title {
    font-size: 50px;
    font-weight: bold;
    color: #ffffff;
    margin-bottom: 10px;
}

/* イベント名 */
.event-name {
    font-size: 20px;
    color: #333333;
    margin-bottom: 10px;
}

/* メンバーリスト */
.member-list {
    list-style-type: none; /* リストのデフォルトの丸を削除 */
    padding: 0;
    margin: 0;
    display: flex; /* 横並び */
    gap: 15px; /* アイテム間のスペース */
    flex-wrap: wrap; /* アイテムが収まりきらない場合、次の行に折り返す */
    justify-content: center; /* アイテムを中央揃え */
}

/* メンバーアイテム */
.member-item {
    background-color: #f0f0f0; /* アイテムの背景色 */
    padding: 5px 10px; /* アイテムの内側に余白 */
    border-radius: 5px; /* アイテムに角丸を追加 */
    font-size: 14px;
    white-space: nowrap; /* メンバー名が長くても1行で表示 */
    text-align: center; /* メンバー名を中央に配置 */
    min-width: 80px; /* 最小幅 */
    border: 3px solid rgb(151, 151, 201); /* 境界線を太くして確認 */
}




/* 出来事の追加ボタン */
.add-event-button {
    width: 100%;
    padding: 10px;
    background-color: #607d8b;
    color: #ffffff;
    font-size: 16px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    margin-bottom: 20px;
    transition: background-color 0.3s;
}

.add-event-button:hover {
    background-color: #607d8b;
}

/* 各費用項目 */
.expense-item {
    background-color: #ffffff;
    border-radius: 10px;
    padding: 15px;
    margin-bottom: 15px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    text-align: left;
}

.expense-title {
    font-size: 18px;
    color: #333333;
    margin: 0 0 5px 0;
}

.payer {
    font-size: 14px;
    color: #666666;
    margin: 0 0 10px 0;
}

/* ボタングループ */
.button-group {
    display: flex;
    gap: 10px;
    margin-bottom: 10px;
}

.person-button {
    flex: 1;
    padding: 8px;
    background-color: #f2f2f2;
    color: #333333;
    border: 1px solid #cccccc;
    border-radius: 5px;
    font-size: 14px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.person-button:hover {
    background-color: #e6e6e6;
}

.edit-button {
    width: 40px;
    padding: 8px;
    background-color: #cccccc;
    color: #333333;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 14px;
    transition: background-color 0.3s;
}

.edit-button:hover {
    background-color: #b3b3b3;
}

/* 金額表示 */
.amount {
    font-size: 16px;
    font-weight: bold;
    color: #333333;
    text-align: right;
}

/* 割り勘総額ボタン */
.summary-button {
    width: 100%;
    padding: 10px;
    background-color: #607d8b;
    color: #ffffff;
    font-size: 16px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    margin-bottom: 10px;
    transition: background-color 0.3s;
}

.summary-button:hover {
    background-color: #607d8b;
}

/* イベント終了ボタン */
.end-event-button {
    width: 100%;
    padding: 10px;
    background-color: #607d8b;
    color: #ffffff;
    font-size: 16px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.end-event-button:hover {
    background-color: #607d8b;
}


</style>

<body>
    <div id="main-container">
        <!-- アプリタイトル -->
        <header>
            <div id="logo">
                <a href="イベントの閲覧と選択.php">
                    <img src="img/image.png" alt="WARIPAYロゴ">
                </a>
            </div>
        </header>

        <!-- イベント名 -->
        <h2 class="event-name">イベント名: <?= htmlspecialchars($event['EventName'], ENT_QUOTES, 'UTF-8') ?></h2>

        <p>イベントメンバー：</p>
        <ul class="member-list">
            <?php if ($creator): ?>
                <li class="member-item"><?= htmlspecialchars($creator['UserName'], ENT_QUOTES, 'UTF-8') ?></li>
            <?php endif; ?>

            <?php if (!empty($members)): ?>
                <?php foreach ($members as $member): ?>
                    <li class="member-item"><?= htmlspecialchars($member['EventMemberName'], ENT_QUOTES, 'UTF-8') ?></li>
                <?php endforeach; ?>
            <?php else: ?>
                <li>メンバーはまだ追加されていません。</li>
            <?php endif; ?>
        </ul>

        <!-- 出来事の追加ボタン -->
        <button class="add-event-button" onclick="location.href='出来事作成.php?eventID=<?= htmlspecialchars($eventID, ENT_QUOTES, 'UTF-8') ?>'">出来事の追加</button>

        <!-- 各費用項目 -->
        <?php if (!empty($happens)): ?>
            <?php foreach ($happens as $happen): ?>
                <div class="expense-item">
                    <h3 class="expense-title"><?= htmlspecialchars($happen['HappenName'], ENT_QUOTES, 'UTF-8') ?></h3>

                    <p class="payer"><?= htmlspecialchars($happen['PayerName'] ?? '', ENT_QUOTES, 'UTF-8') ?> が立て替え</p>


                    <div class="button-group">
                <?php if (!empty($happen['members'])): ?>
                    <?php foreach ($happen['members'] as $member_id): ?>         
                        <li><?= htmlspecialchars($member[''], ENT_QUOTES, 'UTF-8') ?></li>
                    <?php endforeach; ?>
                <?php endif; ?>
            
                        
                        <button class="edit-button" onclick="location.href='出来事管理.php?happenID=<?= htmlspecialchars($happen['HID'], ENT_QUOTES, 'UTF-8') ?>'">🖊</button>
                    </div>
                    <div class="amount">￥<?= number_format($happen['TotalMoney']) ?></div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>現在、表示する出来事はありません。</p>
        <?php endif; ?>

        <!-- 割り勘総額ボタン -->
        <button class="summary-button" onclick="location.href='割り勘総額.php'">割り勘総額</button>

        <!-- イベント終了ボタン -->
        <button class="end-event-button" onclick="location.href='イベント終了.php'">イベント終了</button>
    </div>
</body>
</html>
