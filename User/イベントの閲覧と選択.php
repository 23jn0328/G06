<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>イベントリスト</title>
    <link rel="stylesheet" href="イベントの閲覧と選択.css">
</head>
<body>

    <div class="container">
    <header>
        <div id="logo">
            <a href="イベントの閲覧と選択.php">
                <img src="img/image.png" alt="WARIPAYロゴ">
            </a>
        </div>
    </header>   
       
       
        
        <!-- イベント作成ボタン -->
        <button class="gradient-btn" onclick="location.href='イベント作成.php'">イベントを作成</button>

        <!-- イベントリスト -->
        <div class="event-list">
            <div class="event-item" onclick="location.href='出来事の閲覧と選択.php'">
                <div class="event-name">沖縄旅行</div>
                <div class="event-date">開始日時: 2024年1月1日</div>
            </div>
            <div class="event-item" onclick="location.href='出来事の閲覧と選択.php'">
                <div class="event-name">合宿</div>
                <div class="event-date">開始日時: 2024年2月1日</div>
            </div>
        </div>
        <div class="event-list">
            <div class="event-item" onclick="location.href='出来事の閲覧と選択.php'">
                <div class="event-name">沖縄旅行</div>
                <div class="event-date">開始日時: 2024年1月1日</div>
            </div>
            <div class="event-item" onclick="location.href='出来事の閲覧と選択.php'">
                <div class="event-name">合宿</div>
                <div class="event-date">開始日時: 2024年2月1日</div>
            </div>
        </div>
        <div class="event-list">
            <div class="event-item" onclick="location.href='出来事の閲覧と選択.php'">
                <div class="event-name">沖縄旅行</div>
                <div class="event-date">開始日時: 2024年1月1日</div>
            </div>
            <div class="event-item" onclick="location.href='出来事の閲覧と選択.php'">
                <div class="event-name">合宿</div>
                <div class="event-date">開始日時: 2024年2月1日</div>
            </div>
        </div>
    </div>
</body>
</html>
