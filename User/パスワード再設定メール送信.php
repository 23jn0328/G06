<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>割り勘アプリ</title>
  <link rel="stylesheet" href="パスワード再設定メール送信.css">
</head>
<body>
  <div id="main-container">
  <div id="logo">
            <a href="イベントの閲覧と選択.php">
                <img src="img/image.png" alt="WARIPAYロゴ">
            </a>
  </div>
  <div class="container">
    <h1>登録したメールアドレスで再設定</h1>
    <hr>
    <!-- メールアドレス入力 -->
    <div class="form-group">
      <label for="email">メールアドレスを入力</label>
      <div class="input-group">
        <input type="email" id="email" placeholder="メールアドレスを入力">
        <button class="btn">送信</button>
      </div>
    </div>

    <!-- メッセージ表示 -->
    <p class="message">
      <nobr>確認コードが届かない場合、メールアドレスが正しいかご確認ください。</nobr>
      <a href="#">再送信</a>
    </p>

    <!-- 確認コード入力 -->
    <div class="form-group">
      <label for="code">確認コード</label>
      <input type="text" id="code" placeholder="コードを入力">
      <button class="btn">確認コードを送信</button>
    </div>

    <!-- 確認コード送信ボタン -->
    
  </div>
  </div>
</body>
</html>
