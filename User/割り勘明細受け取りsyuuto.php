<?php
    require_once 'HappenDetailDAO.php';
    require_once 'HappenDAO.php';

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>割り勘明細 - わりペイ</title>
    <link rel="stylesheet" href="割り勘明細受け取り.css">
</head>
<body>

    <!-- メインコンテナ -->
    <div id="main-container">
    <header>
                <div id="logo">
                    <a href="イベントの閲覧と選択.php">
                        <img src="img/image.png" alt="WARIPAYロゴ">
                    </a>
                </div>
            </header>
        <!-- スクロール可能な明細エリア -->

        <h2>受け取り金額はありません</h2>

        <div id="scrollable-content">
            
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
