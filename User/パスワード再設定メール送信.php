<?php
// データベース接続
require 'MemberDAO.php';  // 必要なデータベース接続設定

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // メールアドレスの取得
    $Adress = $_POST['Adress'];

    // メールアドレスの検証
    if (!filter_var($Adress, FILTER_VALIDATE_EMAIL)) {
        die("無効なメールアドレスです。");
    }

    // OTPを生成（6桁のランダムな数値）
    $otp = random_int(100000, 999999); 
    $expires = date('Y-m-d H:i:s', strtotime('+10 minutes')); // 10分の有効期限

    // OTPをデータベースに保存（データベース接続の準備が整っている前提）
    $memberDAO = new MemberDAO();
    if($memberDAO->otparukana($Adress) == true){
      //kousinn
      $message = $memberDAO->otpkousin($Adress, $otp, $expires);

    }else{
      // insert
      // OTPが生成され、保存されたことをユーザーに通知
      $message = $memberDAO->otpmusoushin( $Adress,  $otp,  $expires);
    }
    // OTPが生成され、保存されたことをユーザーに通知
    mb_language("Japanese");
    mb_internal_encoding("UTF-8");
    // ワンタイムパスワードを含んだメール送信
    $to = $Adress;
    $subject = "ワンタイムパスワード(WARIPAY)";
    $headers = "From: WARIPAY@example.com";
    mb_send_mail($to, $subject, $message, $headers);


} else {
    // 初期状態（フォームが表示された状態）
    $message = '';
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
    <form action="" method="post">
    <!-- メールアドレス入力 -->
    <div class="form-group">
      <label for="email">メールアドレスを入力</label>
      <div class="input-group">
      <input type="email" id="email" name="Adress" placeholder="メールアドレスを入力" required>

        <button class="btn">送信</button>
      </div>
    </div>
    <!-- メッセージ表示 -->
    <?php if ($message): ?>
      <p><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></p>
    <?php endif; ?>
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
