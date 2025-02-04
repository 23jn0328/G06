<?php
require_once 'DetailDAO.php';

$detailDAO = new DetailDAO();

// 仮のデータ（実際は前ページから取得）
$eventId = 'E000001';
$motoEmid = 'EM000001'; // 仮のイベントメンバーID
$motoKid = null; // 仮の会員ID（会員としての支払いがある場合）

// 支払いコンテナを取得
$containers = $detailDAO->getPaymentContainers($eventId);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>割り勘明細 - わりペイ</title>
    <link rel="stylesheet" href="割り勘明細.css"> 
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
            // イベント内の支払者ごとの情報をまとめるために配列を準備
            $paymentSummary = [];

            foreach ($containers as $container) : 
                // 受取者のIDを取得 (SakiEMIDが優先、なければSakiKID)
                $sakiId = $container['SakiEMID'] ?? $container['SakiKID'];

                if ($sakiId === null) {
                    continue; // 受取者が NULL の場合スキップ
                }

                // 支払者と受取者のペアごとに金額を集計
                if ($motoEmid !== null) {
                    $totalAmount = $detailDAO->getTotalAmountByMotoEMID($motoEmid, $sakiId);
                    $details = $detailDAO->getPaymentDetails($motoEmid, $sakiId);
                } elseif ($motoKid !== null) {
                    $totalAmount = $detailDAO->getTotalAmountByMotoKID($motoKid, $sakiId);
                    $details = $detailDAO->getPaymentDetails($motoKid, $sakiId);
                } else {
                    continue; // NULL の場合、スキップ
                }

                // 支払者と受取者の名前を取得
                $motoUserName = $detailDAO->getUserNameByID($motoEmid ?? $motoKid);
                $sakiUserName = $detailDAO->getUserNameByID($sakiId);

                // 既に支払者-受取者ペアが配列に存在する場合、金額を足し合わせる
                if (isset($paymentSummary[$motoUserName][$sakiUserName])) {
                    $paymentSummary[$motoUserName][$sakiUserName]['totalAmount'] += $totalAmount;
                } else {
                    $paymentSummary[$motoUserName][$sakiUserName] = [
                        'totalAmount' => $totalAmount,
                        'details' => $details
                    ];
                }

            endforeach;

            // 支払者-受取者ペアごとの金額を表示
            foreach ($paymentSummary as $motoUserName => $sakiData) :
                foreach ($sakiData as $sakiUserName => $data) :
            ?>
                <div class="payment-card">
                    <h2><?php echo htmlspecialchars($motoUserName); ?> ➡ <?php echo htmlspecialchars($sakiUserName); ?> 
                        <span class="payment-amount">￥<?php echo number_format($data['totalAmount']); ?></span>
                    </h2>
                    <?php foreach ($data['details'] as $detail) : ?>
                        <div class="event-item">
                            <div class="event-name"><?php echo htmlspecialchars($detail['HappenName']); ?></div>
                            <div class="event-amount">￥<?php echo number_format($detail['SMoney']); ?></div>
                            <div class="event-date"><?php echo htmlspecialchars($detail['HappenDate']); ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php 
                endforeach;
            endforeach;
            ?>
        </div>
    </div>
</body>
</html>
