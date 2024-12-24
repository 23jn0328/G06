<?php
// セッション開始
session_start();

// 必要なファイルをインクルード
require_once 'config.php';
require_once 'EventDAO.php';

// セッションからログインユーザーのIDを取得
if (!isset($_SESSION['member_id'])) {
    header('Location: ログイン.php'); // 未ログインならログイン画面へリダイレクト
    exit;
}
$loggedInMID = $_SESSION['member_id'];

// イベントIDを取得
if (isset($_GET['eid'])) { // 'eid'に変更
    $eventID = $_GET['eid'];
} else {
    echo "イベントIDが指定されていません。";
    exit;
}

// DAOクラスをインスタンス化
$eventDAO = new EventDAO();

// イベントデータの取得
$event = $eventDAO->get_event($eventID);
if (!$event) {
    echo "指定されたイベントが存在しません。";
    exit;
}

// 更新処理
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $eventName = trim($_POST['event_name']);
    $eventStart = trim($_POST['event_start']);

    if (empty($eventName) || empty($eventStart)) {
        echo "<script>alert('全ての必須項目を入力してください。');</script>";
    } else {
        // イベント情報の更新
        $eventDAO->update_event($eventID, $eventName, new DateTime($eventStart));

        echo "<script>alert('更新が完了しました。'); window.location.href='イベントの閲覧と選択.php';</script>";
        exit;
    }
}

// 削除処理
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    // イベントを削除
    $eventDAO->delete_event($eventID);

    echo "<script>alert('イベントを削除しました。'); window.location.href='イベントの閲覧と選択.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>イベント管理</title>
    <link rel="stylesheet" href="イベント管理.css">
</head>
<body>
<div id="main-container">
    <header>
        <div id="logo">
            <a href="イベントの閲覧と選択.php">
                <img src="img/image.png" alt="WARIPAYロゴ">
            </a>
        </div>
    </header>
    <form method="POST">
        <label for="event_name">イベント名</label>
        <input type="text" id="event_name" name="event_name" value="<?php echo htmlspecialchars($event->EventName); ?>">

        <label for="event-date">イベント開始日時</label>
        <input type="date" id="event-date" name="event_start" value="<?php echo htmlspecialchars($event->EventStart->format('Y-m-d')); ?>">

        <label for="member-name">メンバー名</label>
        <input type="text" id="member-name" placeholder="メンバー名を入力">
        <button type="button" onclick="addMember()">追加</button>

        <div id="member-list">
            <!-- メンバーリスト表示エリア -->
        </div>

        <div class="buttons">
            <button type="submit" name="update" class="button button-update">更新</button>
            <button type="button" class="button button-back" onclick="window.location.href='イベントの閲覧と選択.php';">戻る</button>
        </div>
        
        <button type="submit" name="delete" class="button button-delete" onclick="return confirm('本当にこのイベントを削除しますか？');">削除</button>
    </form>
</div>

<script>
// メンバー追加機能
function addMember() {
    const memberName = document.getElementById('member-name').value;
    if (memberName.trim() === "") {
        alert("メンバー名を入力してください");
        return;
    }

    const memberList = document.getElementById('member-list');
    const memberItem = document.createElement('div');
    memberItem.classList.add('member-item');
    memberItem.textContent = memberName;

    const removeBtn = document.createElement('span');
    removeBtn.classList.add('remove-btn');
    removeBtn.textContent = "削除";
    removeBtn.onclick = function() {
        memberItem.remove();
    };
    
    memberItem.appendChild(removeBtn);
    memberList.appendChild(memberItem);

    document.getElementById('member-name').value = ''; // 入力欄をクリア
}
</script>
</body>
</html>
