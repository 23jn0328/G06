<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>割り勘明細 - わりペイ</title>
    <link rel="stylesheet" href="割り勘明細.css">
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
        <div id="scrollable-content">
            <!-- メンバーごとの支払情報 -->
            <div class="payment-card">
                <h2>しゅうと ➔ はやと <span class="payment-amount">￥5000</span></h2>
                <div class="event-item">
                    <div class="event-name">レンタカー代</div>
                    <div class="event-amount">￥2000</div>
                </div>
                <div class="event-date">2024年10月15日 13時</div>

                <div class="event-item">
                    <div class="event-name">食事</div>
                    <div class="event-amount">￥3000</div>
                </div>
                <div class="event-date">2024年10月15日 18時</div>
            </div>

            <div class="payment-card">
                <h2>しゅうと ➔ ひかる <span class="payment-amount">￥10000</span></h2>
                <div class="event-item">
                    <div class="event-name">ホテル代</div>
                    <div class="event-amount">￥10000</div>
                </div>
                <div class="event-date">2024年10月15日 11時</div>
            </div>

            <div class="payment-card">
                <h2>しゅうと ➔ れおん <span class="payment-amount">￥10000</span></h2>
                <div class="event-item">
                    <div class="event-name">タクシー代</div>
                    <div class="event-amount">￥10000</div>
                </div>
                <div class="event-date">2024年10月15日 17時</div>
            </div>

            <!-- 新規追加データ -->
            <div class="payment-card">
                <h2>しゅうと ➔ いくみ <span class="payment-amount">￥6000</span></h2>
                <div class="event-item">
                    <div class="event-name">食事</div>
                    <div class="event-amount">￥3000</div>
                </div>
                <div class="event-date">2024年10月15日</div>

                <div class="event-item">
                    <div class="event-name">温泉代</div>
                    <div class="event-amount">￥3000</div>
                </div>
                <div class="event-date">2024年10月16日</div>
            </div>

            <div class="payment-card">
                <h2>しゅうと ➔ ますだ <span class="payment-amount">￥6000</span></h2>
                <div class="event-item">
                    <div class="event-name">ガソリン代</div>
                    <div class="event-amount">￥4000</div>
                </div>
                <div class="event-date">2024年10月26日</div>

                <div class="event-item">
                    <div class="event-name">ホテル代</div>
                    <div class="event-amount">￥2000</div>
                </div>
                <div class="event-date">2024年10月27日</div>
            </div>
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
