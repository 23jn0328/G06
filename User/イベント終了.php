<?php
require_once 'DetailDAO.php';
require_once 'EventDAO.php';  // EventDAOの読み込み
require_once 'HappenDAO.php';

$detailDAO = new DetailDAO();
$eventDAO = new EventDAO();  // EventDAOのインスタンス化

session_start();

// セッションからメンバーリストを取得
$members = $_SESSION['event_members'] ?? [];
$creatorName = $_SESSION['creatorName'] ?? null;
$eventID = $_GET['eventID'] ?? null;  // セッションからイベントIDを取得
var_dump($eventID);
if (!$creatorName) {
    echo "作成者名が見つかりません。";
    exit;
}

// イベント終了処理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $eventDAO->set_event_completed($eventID);  // set_event_completed関数を呼び出してイベントを完了にする
    header('Location: イベントの閲覧と選択.php');  // イベント完了後、イベント閲覧ページにリダイレクト
    exit();
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>割り勘総額 - わりペイ</title>
    <link rel="stylesheet" href="割り勘総額.css">
</head>
<style>
/* ======================================== */
/* 🌟 基本スタイル */
/* ======================================== */

body {
    font-family: Arial, sans-serif;
    background-color: #fff; /* 背景を白に */
    color: #333;
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    height: 100vh;
    margin: 0;
}

#logo img {
    max-width: 200px; /* ロゴサイズを調整 */
    height: auto;
    margin: 20px auto;
    display: block;
}

#main-container {
    width: 90%;
    max-width: 500px;
    background-color: #b0b0b0; /* 明るいグレー */
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    display: flex;
    flex-direction: column;
}

/* ======================================== */
/* 🌟 メンバーリスト（スクロールなし） */
/* ======================================== */

.member-list-container {
    width: 100%;
    padding: 10px;
    border-radius: 8px;
    background-color: #fafafa;
    border: 1px solid #ddd;
    margin-top: 15px;
}

.member-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.member-item {
    background: #ffffff;
    padding: 10px;
    margin: 5px 0;
    border-radius: 5px;
    box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
    display: flex;
    align-items: center;
    justify-content: space-between;
}

/* ======================================== */
/* 🌟 チェックボックスのデザイン */
/* ======================================== */

.transaction-checkbox {
    margin-right: 10px;
    transform: scale(1.2);
    accent-color: #0056b3; /* チェック時のカラーを統一 */
}

/* ======================================== */
/* 🌟 イベント終了ボタン */
/* ======================================== */

#endButton {
    width: 100%;
    padding: 10px;
    margin-top: 15px;
    font-size: 16px;
    font-weight: bold;
    background-color: #ccc;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: not-allowed;
    transition: 0.3s;
}

#endButton:enabled {
    background-color: #28a745;
    cursor: pointer;
}

#endButton:enabled:hover {
    background-color: #218838;
}

/* ======================================== */
/* 🌟 PayPayリンク & 戻るボタン */
/* ======================================== */

#link-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 10px;
    padding-top: 10px;
    border-top: 1px solid #ddd;
}

#paypay-link img {
    width: 100px;
    height: auto;
}

/* 戻るリンクのスタイル */
#return-link {
    color: #607d8b;
    text-decoration: none;
    font-weight: bold;
}

</style>
<body>

<div id="main-container">
    <!-- ロゴ -->
    <div id="logo">
        <a href="イベントの閲覧と選択.php">
            <img src="img/image.png" alt="WARIPAYロゴ">
        </a>
    </div>

    <!-- 説明テキスト -->
    <div id="text-center">
        <small>
            <span class="text-blue">青字</span>は受け取り金額
            <span class="mx-1">/</span>
            <span class="text-red">赤字</span>は支払い金額
        </small>
    </div>

    <ul class="member-list">
        <!-- 作成者 -->
        <?php if ($creatorName): ?>
            <li class="member-item">
                <!-- チェックボックスを追加 -->
                <input type="checkbox" class="transaction-checkbox" data-amount="<?php echo htmlspecialchars($totalAmount); ?>">
                <a><?= htmlspecialchars($creatorName, ENT_QUOTES, 'UTF-8') ?></a>
                <div>
                    <a href="割り勘明細受け取り.php">
                        <span class="payment-amount">受け取り</span>
                    </a>
                    <a href="割り勘明細.php">
                        <span class="payment-amount2">支払い</span>
                    </a>
                </div>
            </li>
        <?php endif; ?>

        <!-- メンバーリスト -->
        <?php if (!empty($members)): ?>
            <?php foreach ($members as $member): ?>
                <li class="member-item">
                    <!-- チェックボックスを追加 -->
                    <input type="checkbox" class="transaction-checkbox" data-amount="<?php echo htmlspecialchars($totalAmount); ?>">
                    <a><?= htmlspecialchars($member['EventMemberName'] ?? '不明なメンバー', ENT_QUOTES, 'UTF-8') ?></a>
                    <div>
                    <a href="割り勘明細受け取り.php">
                        <span class="payment-amount">受け取り</span>
                    </a>
                    <a href="割り勘明細.php">
                        <span class="payment-amount2">支払い</span>
                    </a>
                    </div>
                </li>
            <?php endforeach; ?>
        <?php else: ?>
            <li>メンバーが見つかりません。</li>
        <?php endif; ?>
    </ul>
    
    <!-- イベント終了フォーム -->
    <form method="POST" action="">
    <input type="hidden" name="eventID" value="<?php echo $eventID; ?>" />
        <button type="submit" id="endButton" disabled>イベント終了</button>
    </form>

    <!-- 戻るボタン -->
    <a id="return-link" href="javascript:void(0);" onclick="history.back();">戻る</a>
</div>


<script>
    const checkboxes = document.querySelectorAll('.transaction-checkbox');
    const endButton = document.getElementById('endButton');

    function updateButtonState() {
        // すべてのチェックボックスがチェックされている場合のみボタンを有効化
        const allChecked = Array.from(checkboxes).every(checkbox => checkbox.checked);
        endButton.disabled = !allChecked;
    }
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateButtonState);
    });
</script>

</body>
</html>
