<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WARIPAY</title>
    <link rel="stylesheet" href="出来事の閲覧と選択style.css">
</head>
<body>
    <div id="main-container">
            <!-- アプリタイトル -->
            <header>
                <div id="logo">
                    <a href="イベントの閲覧と選択.php">
                        <img src="img/image.png" alt="WARIPAYロゴ">
                    </a>
                </div>
            </header>
            <!-- イベント名 -->
            <h2 class="event-name">旅行</h2>
            <!-- メンバーリスト -->
            <div class="member-list">Aさん・Bさん・Cさん</div>
            <!-- 出来事の追加ボタン -->
            <button class="add-event-button" onclick="location.href='出来事作成.php'">出来事の追加</button>
            <!-- 各費用項目 -->
            <div class="expense-item">
                <h3 class="expense-title">タクシー代</h3>
                <p class="payer">Aさんが立て替え</p>
                <div class="button-group">
                    <button class="person-button">Aさん</button>
                    <button class="person-button">Bさん</button>
                    <button class="person-button">Cさん</button>
                    <button class="edit-button" onclick="location.href='出来事管理.php'">🖊</button>
                </div>
                <div class="amount">￥6000</div>
            </div>

            <div class="expense-item">
                <h3 class="expense-title">昼飯代</h3>
                <p class="payer">Bさんが立て替え</p>
                <div class="button-group">
                    <button class="person-button">Aさん</button>
                    <button class="person-button">Cさん</button>
                    <button class="edit-button" onclick="location.href='出来事管理.php'">🖊</button>
                </div>
                <div class="amount">￥6000</div>
            </div>
        <!-- 割り勘総額ボタン -->
        <button class="summary-button" onclick="location.href='割り勘総額.php'">割り勘総額</button>
        <!-- イベント終了ボタン -->
        <button class="end-event-button" onclick="location.href='イベント終了.php'">イベント終了</button>
    </div>
</body>
</html>
