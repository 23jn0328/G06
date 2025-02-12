<?php
require_once 'DetailDAO.php';
require_once 'EventDAO.php';  // EventDAOの読み込み
$detailDAO = new DetailDAO();

session_start();
$eventId = $_GET['eventId'] ?? null;
$motoEmid = $_GET['motoEmid'] ?? null;
$motoKid = $_GET['sakiEmid'] ?? null;

// 仮のデータ（実際は前ページから取得）
$eventId = 'E000152';
$motoEmid = NULL; // 仮のイベントメンバーID
$motoKid = 'M000040'; // 仮の会員ID（会員としての支払いがある場合）

// 支払いコンテナを取得
$containers = $detailDAO->getPaymentContainers($eventId);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // POSTからイベントIDを取得
    $eventId = $_POST['eventId'] ?? null;

    if ($eventId !== null) {
        // EventDAOのインスタンスを生成
        $eventDAO = new EventDAO();

        // イベントを終了させる
        $eventDAO->set_event_completed($eventId);
        
        // 終了後リダイレクトなどのフィードバック処理（ここではそのまま戻る例）
        header('Location: イベントの閲覧と選択.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>割り勘精算 - わりペイ</title>
    <style>
        #logo img {
            max-width: 250px; /* ロゴの最大幅を少し小さく調整 */
            height: auto; /* アスペクト比を維持 */
            margin: 10px auto; /* 上下に少し余白を追加 */
            display: block; /* 中央揃え */
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .header {
            font-size: 50px;
            font-weight: bold;
            text-align: center;
            margin: 20px 0;
            color: #ffffff;
        }

        .container {
            width: 90%;
            max-width: 450px;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            color: #333;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: left;
            border: 1px solid #ccc;
        }

        h1 {
            font-size: 24px;
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        label {
            font-size: 14px;
            margin-bottom: 5px;
            display: block;
            color: #333;
        }

        input[type="text"], input[type="date"], .member-input-containner input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ccc;
            box-sizing: border-box;
            font-size: 14px;
        }

        button.button {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-bottom: 10px;
        }

        .button-add {
            background-color: #555;
        }

        .button-create {
            background-color: #607d8b;
        }

        .button-back {
            background-color: #7a7a7a;
        }

        .member-input-containner {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }

        .member-input-containner button {
            width: 30%;
            background-color: #555;
            color: white;
            cursor: pointer;
            padding: 10px;
            border-radius: 5px;
        }

        .member-list {
            margin-top: 15px;
            display: flex;
            flex-wrap: wrap;
        }

        .member-item {
            background-color: #555;
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            margin: 5px;
            display: flex;
            align-items: center;
        }

        .remove-btn {
            color: #fff;
            margin-left: 5px;
            cursor: pointer;
        }

        .remove-btn:hover {
            color: #f44336;
        }

        .buttons {
            display: flex;
            justify-content: space-between;
        }

        .button {
            width: 48%;
        }

        hr {
            margin: 20px 0;
            border: 1px solid #eee;
        }

        #main-container {
            width: 90%;
            max-width: 500px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            height: auto;
            background-color: #b0b0b0;
            border-radius: 10px;
            color: #333;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: left;
            border: 1px solid #ccc;
        }

        #endButton {
            font-weight: bold;  /* 文字を太くする */
            font-size: 18px;    /* 文字サイズを少し大きく */
            padding: 15px 30px; /* ボタンの上下左右に余白を増やして大きくする */
            border-radius: 10px; /* 角を丸くする */
            background-color: #607d8b; /* ボタンの背景色 */
            color: white; /* 文字の色 */
            border: none; /* 枠線を消す */
            cursor: not-allowed; /* 無効時のカーソル */
            transition: background-color 0.3s;
        }

        #endButton:enabled {
            background-color: #4caf50; /* ボタンが有効のときの色 */
            cursor: pointer;
        }

        #endButton:enabled:hover {
            background-color: #607d8b; /* ホバー時の色 */
        }

        .transaction {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px 0; /* 上下の余白を少し調整 */
            border-bottom: 2px solid #ddd;
        }

        .transaction span {
            font-size: 18px;
            flex: 2;
        }

        .transaction .amount {
            flex: 1;
            text-align: right;
            font-size: 20px;
            font-weight: bold;
            color: #4caf50;
            display: flex;
            align-items: center; /* チェックボックスと金額を横並びに */
        }

        .transaction input[type="checkbox"] {
            width: 30px;  /* チェックボックスを少し大きく */
            height: 30px; /* チェックボックスを少し大きく */
            margin-left: 15px; /* 左の余白 */
            cursor: pointer;
            flex: 0;
        }

        .end-button {
            width: 100%;
            padding: 15px; /* ボタンの高さを調整 */
            font-size: 18px;
            color: #fff;
            background-color: #aaa;
            border: none;
            border-radius: 10px;
            cursor: not-allowed;
            transition: background-color 0.3s;
        }

        .end-button.enabled {
            background-color: #4caf50;
            cursor: pointer;
        }

        .end-button.enabled:hover {
            background-color: #607d8b;
        }

    </style>
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
        <div id="scrollable-content">
            <?php
            // 表示済みのペアを格納する配列
            $displayedPairs = [];

            foreach ($containers as $container) : 
                // 受取者のIDを取得 (SakiEMIDが優先、なければSakiKID)
                $sakiId = $container['SakiEMID'] ?? $container['SakiKID'];
                $motoId = $container['MotoEMID'] ?? $container['MotoKID'];

                if($motoEmid === $sakiId){
                    continue;
                }
                if($motoKid === $sakiId){
                    continue;
                }
                if ($sakiId === null) {
                    continue; // 受取者が NULL の場合スキップ
                }

                // 支払者と受取者のペアごとに金額を集計
                if ($motoEmid !== null) {
                    if (strpos($sakiId, 'M') === 0) {
                        $totalAmount = $detailDAO->getTotalAmountBySakiKID($motoEmid, $sakiId);
                    }                    
                    else{
                        $totalAmount = $detailDAO->getTotalAmountByMotoEMID($motoEmid, $sakiId);
                    }
                } elseif ($motoKid !== null) {
                    $totalAmount = $detailDAO->getTotalAmountByMotoKID($motoKid, $sakiId);
                } else {
                    continue; // NULL の場合、スキップ
                }

                // 支払者と受取者の名前を取得
                $motoUserName = $detailDAO->getUserNameByID($motoEmid ?? $motoKid);
                $sakiUserName = $detailDAO->getUserNameByID($sakiId);

                // 既に同じ支払者 ➡ 受取者ペアが表示済みならスキップ
                $pairKey = "{$motoUserName}_{$sakiUserName}";
                if (isset($displayedPairs[$pairKey])) {
                    continue;
                }

                // 表示済みとして登録
                $displayedPairs[$pairKey] = true;
            ?>
                <div class="payment-card">
                    <h2><?php echo htmlspecialchars($motoUserName); ?> ➡ <?php echo htmlspecialchars($sakiUserName); ?> 
                        <span class="payment-amount">￥<?php echo number_format($totalAmount); ?></span>
                    </h2>
                    <!-- チェックボックスを追加 -->
                    <input type="checkbox" class="transaction-checkbox" data-amount="<?php echo htmlspecialchars($totalAmount); ?>">
                </div>
            <?php endforeach; ?>
        </div>
        <form method="POST" action="イベント終了.php">
            <input type="hidden" name="eventId" value="<?php echo $eventId; ?>" />
            <button type="submit" id="endButton" disabled>イベント終了</button>
        </form>
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
