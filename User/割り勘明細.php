<?php
require_once 'DetailDAO.php';

$detailDAO = new DetailDAO();

session_start();
$eventId = $_SESSION['eventID'] ?? null;
$motoEmid = $_GET['motoEmid'] ?? null;
$motoKid = $_GET['motoKid'] ?? null;

var_dump($eventId);
var_dump($motoEmid);
var_dump($motoKid);


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
            // 表示済みのペアを格納する配列
            $displayedPairs = [];
            $total = 0;

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
                            $details = $detailDAO->getPaymentDetailsBySakiKID($motoEmid, $sakiId);
                        }                    
                        else{
                            $totalAmount = $detailDAO->getTotalAmountByMotoEMID($motoEmid, $sakiId);
                            $details = $detailDAO->getPaymentDetailsByMotoEMID($motoEmid, $sakiId);
                        }
                    } elseif ($motoKid !== null) {
                        $totalAmount = $detailDAO->getTotalAmountByMotoKID($motoKid, $sakiId);
                        $details = $detailDAO->getPaymentDetailsByMotoKID($motoKid, $sakiId);
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


                    $total += $totalAmount;

                    // 表示済みとして登録
                    $displayedPairs[$pairKey] = true;

            ?>
                <div class="payment-card">
                    <h2><?php echo htmlspecialchars($motoUserName); ?> ➡ <?php echo htmlspecialchars($sakiUserName); ?> 
                        <span class="payment-amount">￥<?php echo number_format($totalAmount); ?></span>
                    </h2>
                    <?php foreach ($details as $detail) : ?>
                        <div class="event-item">
                            <div class="event-name"><?php echo htmlspecialchars($detail['HappenName']); ?></div>
                            <div class="event-amount">￥<?php echo number_format($detail['SMoney']); ?></div>
                            <div class="event-date"><?php echo htmlspecialchars($detail['HappenDate']); ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>

            <h2>総額：<?php echo $total ?><h2>
        </div>
                <!-- 固定されたPayPayリンクボタン -->
                <div id="link-container">
            <!-- 左寄せのPayPayリンク -->
            <a id="paypay-link" href="https://paypay.ne.jp/" target="_blank">
                <img src="https://image.paypay.ne.jp/page/notice-merchant/entry/20181016/159/img_logo_1.jpg" alt="PayPay">
            </a>
            <!-- 右寄せの戻るリンク -->
            <a id="return-link" href="割り勘総額.php">戻る</a>
            
        </div>
    </div>
</body>
</html>
