<?php
session_start();
require_once 'MemberDAO.php';

$Adress = ''; // 初期化
$errs = []; // エラー配列を初期化

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $Adress = trim($_POST['Adress'] ?? ''); // 安全に取得
    $Pw = trim($_POST['Pw'] ?? ''); // 安全に取得
    //var_dump($_POST);
    // メールアドレスのバリデーション
    if ($Adress === '') {
        $errs[] = 'メールアドレスを入力してください。';
    } elseif (!filter_var($Adress, FILTER_VALIDATE_EMAIL)) {
        $errs[] = 'メールアドレスの形式に誤りがあります。';
    }

    // パスワードのバリデーション
    if ($Pw === '') {
        $errs[] = 'パスワードを入力してください。';
    }

    // エラーがなければログイン処理
    if (empty($errs)) {
        $memberDAO = new MemberDAO();
        $member = $memberDAO->get_member($Adress, $Pw); // パスワードの処理は別途対応

        if ($member !== false) {

           
            $_SESSION['member_id'] = $member->ID;
            //var_dump($_SESSION['ID']);
            // セッションを使わずリダイレクト
            header('Location: イベントの閲覧と選択.php');
            exit;
        } else {
            $errs[] = 'メールアドレスまたはパスワードに誤りがあります。';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>わりペイ - ログイン</title>
    <link rel="stylesheet" href="ログイン.css">
</head>
<body>
    <div id="main-container">
        
        <div class="container">
        <div id="logo">
            <a href="イベントの閲覧と選択.php">
                <img src="img/image.png" alt="WARIPAYロゴ">
            </a>
        </div>
            <hr>
            <!-- エラー表示 -->
            <?php if (!empty($errs)): ?>
                <ul class="error-list">
                    <?php foreach ($errs as $err): ?>
                        <li><?= htmlspecialchars($err, ENT_QUOTES, 'UTF-8'); ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <form action="" method="POST"> 
                <input type="text" name="Adress" value="<?= htmlspecialchars($Adress, ENT_QUOTES, 'UTF-8') ?>" placeholder="メールアドレス" required>
                <input type="password" name="Pw" placeholder="パスワード" required>
                <button type="submit" class="login-button">ログイン</button>
                <button type="button" class="register-button" onclick="location.href='会員登録.php'">新規登録</button>
                <div class="passwasu">
                    <a href="パスワード再設定メール送信.php">パスワードを忘れた方はこちら</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
