<?php
session_start();

// セッションからメンバーリストを取得
$members = $_SESSION['event_members'] ?? [];
$creatorName = $_SESSION['creatorName'] ?? null;

if(!$creatorName) {
    echo "作成者名が見つかりません。";
    exit;
}

    /*require_once 'HappenDetailDAO.php';

    // HIDを取得
    $HID = $_GET['HID'];  // URLのパラメータやフォームからHIDを取得

    // HappenDetailDAOインスタンスを作成
    $happenDetailDAO = new HappenDetailDAO();

    // 支払金額の詳細を取得
    $paymentDetails = $happenDetailDAO->getPaymentDetails($HID);

    // メンバー名を取得するためのメソッド
    function getMemberName($EMID) {
        global $happenDetailDAO;
        return $happenDetailDAO->getMemberNameById($EMID);
}*/
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>割り勘総額 - わりペイ</title>
    <link rel="stylesheet" href="割り勘総額.css">
</head>
<body>
    <!-- 全画面に広がるメインコンテナ -->
    <div id="main-container">
    <div id="logo">
            <a href="イベントの閲覧と選択.php">
                <img src="img/image.png" alt="WARIPAYロゴ">
            </a>
    </div>
    <div id="text-center">
    <small>
        <span class="text-blue">青字</span>は受け取り金額
        <span class="mx-1">/</span>
        <span class="text-red">赤字</span>は支払い金額
    </small>
    </div>
    <style> /*cssファイルで適用されないから<style>タグで適用させてる*/
                .text-blue {
            color: blue !important;
        }

        .text-red {
            color: red !important;
        }
        #text-center{
            display: block;
            text-align: center;
        }
        .member-list .payment-amount {
            color: blue !important; /* 確実に青字にする */
        }
        .payment-amount,
        .payment-amount2,
        .payment-amount3,
        .payment-amount4{
            text-decoration: underline;
        }
        .payment-amount:hover,
        .payment-amount2:hover,
        .payment-amount3:hover,
        .payment-amount4:hover{
            text-decoration: underline;
        }
        .member-item:nth-child(4) {
         animation-delay: 0.4s;
        }
        .member-item:nth-child(5) {
         animation-delay: 0.5s;
        }
        </style>
        <!-- メンバーリスト -->
        
        <ul class="member-list">
        
        <?php if ($creatorName): ?>
            <li class="member-item">
                <a><?= htmlspecialchars($creatorName, ENT_QUOTES, 'UTF-8') ?></a>
                <div>
                    <a href="割り勘明細受け取り.php"><span class="payment-amount">¥4000</span></a>
                    <a href="割り勘明細.php"><span class="payment-amount2">¥2000</span></a>
                </div>
            </li>
        <?php endif; ?>

        <?php if (!empty($members)): ?>
        <?php foreach ($members as $member): ?>
            <li class="member-item">
                <a><?= htmlspecialchars($member['EventMemberName'] ?? '不明なメンバー', ENT_QUOTES, 'UTF-8') ?></a>
                <div>
                    <a href="割り勘明細受け取り.php"><span class="payment-amount">¥4000</span></a>
                    <a href="割り勘明細.php"><span class="payment-amount2">¥2000</span></a>
                </div>
            </li>
        <?php endforeach; ?>
        <?php else: ?>
        <li>メンバーが見つかりません。</li>
        <?php endif; ?>
    </ul>
        <!-- 右下に配置された戻るボタン -->
        <a id="return-link" href="出来事の閲覧と選択.php?eventID=E000121">戻る</a>
    </div>

</body>
</html>
