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
     <form action="send_otp.php" method="post">
   
    <!-- メールアドレス入力 -->
    
      <label for="email">メールアドレスを入力</label>
      <div class="input-group">
        <input type="email" id="email" placeholder="メールアドレスを入力" required>
        <button class="btn">送信</button>
      </div>
    </div>
</FROM>

    <!-- メッセージ表示 -->
    <p class="message">
      <nobr>確認コードが届かない場合、メールアドレスが正しいかご確認ください。</nobr>
      <a href="#">再送信</a>
    </p>

    <!-- 確認コード入力 -->
   <div class="container">
      <h1>確認コード入力</h1>
      <form action="verify_otp.php" method="post">
        <input type="hidden" name="email" value="<?php echo htmlspecialchars($_GET['email']); ?>">
        <label for="otp">確認コード</label>
        <input type="text" id="otp" name="otp" placeholder="確認コードを入力" required>
        <button class="btn" type="submit">確認</button>
      </form>
    </div>
  </div>
  </div>
  </div>
</body>
</html>
