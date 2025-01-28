<?php
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
        </style>
        <!-- メンバーリスト -->
        <ul class="member-list">
            <li class="member-item">
                <a>ひかる</a>
                <div>
                <a href="割り勘明細受け取りhikaru.php"><span class="payment-amount">¥4000</span></a>
                <a href="割り勘明細hikaru.php"><span class="payment-amount2">¥2000</span></a>
                </div>
            </li>
            <li class="member-item">
                <a>はやと</a>
                <div>
                <a href="割り勘明細受け取りhayato.php"><span class="payment-amount">¥8000</span></a>
                <a href="割り勘明細hayato.php"><span class="payment-amount3">¥1000</span></a>
                </div>
            </li>
            <li class="member-item">
                <a>しゅうと</a>
                <div>
                <a href="割り勘明細受け取りsyuuto.php"><span class="payment-amount">¥0</span></a>
                <a href="割り勘明細syuuto.php"><span class="payment-amount4">¥3000</span></a>
                </div>
            </li>
            <li class="member-item">
                <a>れおん</a>
                <div>
                <a href="割り勘明細受け取りreon.php"><span class="payment-amount">¥0</span></a>
                <a href="割り勘明細reon.php"><span class="payment-amount4">¥3000</span></a>
                </div>
            </li>
            <li class="member-item">
                <a>いくみ</a>
                <div>
                <a href="割り勘明細受け取りikumi.php"><span class="payment-amount">¥0</span></a>
                <a href="割り勘明細ikumi.php"><span class="payment-amount4">¥3000</span></a>
                </div>
            </li>
        </ul>
    
        <!-- 右下に配置された戻るボタン -->
        <a id="return-link" href="出来事の閲覧と選択.php">戻る</a>
    </div>

</body>
</html>
