<?php
require 'config.php'; // データベース接続設定

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $otp = $_POST['otp'];

    $stmt = $pdo->prepare("SELECT * FROM otp_codes WHERE email = :email AND otp = :otp AND expires_at > NOW()");
    $stmt->execute(['email' => $email, 'otp' => $otp]);
    $otpData = $stmt->fetch();

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("無効なメールアドレスです。");
    }

    $otp = random_int(100000, 999999); // 6桁の確認コード
    $expires = date('Y-m-d H:i:s', strtotime('+10 minutes')); // 有効期限10分


    
    // OTPをデータベースに保存
    $stmt = $pdo->prepare("INSERT INTO otp_codes (email, otp, expires_at) VALUES (:email, :otp, :expires) ON DUPLICATE KEY UPDATE otp = :otp, expires_at = :expires");
    $stmt->execute(['email' => $email, 'otp' => $otp, 'expires' => $expires]);

    // メール送信
    mail($email, "確認コード", "あなたの確認コードは: $otp です。このコードは10分間有効です。");

    header("Location: verify_otp.php?email=" . urlencode($email));
    exit;


    if ($otpData) {
      header("Location: reset_password.php?email=" . urlencode($email));
      exit;
  } else {
      die("無効または期限切れの確認コードです。");
  }

}
?>

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
  <div class="container">
  <div id="logo">
            <a href="イベントの閲覧と選択.php">
                <img src="img/image.png" alt="WARIPAYロゴ">
            </a>
  </div>
    <h1>登録したメールアドレスで再設定</h1>
    <form action="send_otp.php" method="post">
    <!-- メールアドレス入力 -->
    <div class="form-group">
      <label for="email">メールアドレスを入力</label>
      <div class="input-group">
      <input type="email" id="email" name="email" placeholder="メールアドレスを入力" required>
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
