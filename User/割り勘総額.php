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

        <!-- メンバーリスト -->
        <ul class="member-list">
            <li class="member-item">
                <a>はやと</a>
                <div>
                <a href="割り勘明細受け取り.php"><span class="payment-amount">￥5000</span></a>
                <a href="割り勘明細.php"><span class="payment-amount2">¥2500</span></a>
                </div>
            </li>
            <li class="member-item">
                <a>しゅうと</a>
                <div>
                <a href="割り勘明細受け取り.php"><span class="payment-amount">￥7000</span></a>
                <a href="割り勘明細.php"><span class="payment-amount3">¥2500</span></a>
                </div>
            </li>
            <li class="member-item">
                <a>いくみ</a>
                <div>
                <a href="割り勘明細受け取り.php"><span class="payment-amount">￥7000</span></a>
                <a href="割り勘明細.php"><span class="payment-amount4">¥2500</span></a>
                </div>
            </li>
        </ul>

        <!-- 右下に配置された戻るボタン -->
        <a id="return-link" href="出来事の閲覧と選択.php">戻る</a>
    </div>

</body>
</html>
