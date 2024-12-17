<?php
    require_once 'HappenDetailDAO.php';

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
                <a href="割り勘明細.php">はやと</a>
                <span class="payment-amount">￥5000</span>
            </li>
            <li class="member-item">
                <a href="割り勘明細.php">しゅうと</a>
                <span class="payment-amount">￥7000</span>
            </li>
            <li class="member-item">
                <a href="割り勘明細.php">いくみ</a>
                <span class="payment-amount">￥7000</span>
            </li>
        </ul>

        <!-- 右下に配置された戻るボタン -->
        <a id="return-link" href="出来事の閲覧と選択.php">戻る</a>
    </div>

</body>
</html>
