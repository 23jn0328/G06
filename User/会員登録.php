<?php
require_once 'MemberDAO.php';

// POSTメソッドでリクエストされたとき
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 入力された会員データを受け取る
    $Pw = $_POST['Pw'];
    $Adress = $_POST['Adress'];
    $UserName = $_POST['UserName'];
    
    $memberDAO = new MemberDAO();

    $member = new Member();
    //$member->ID = $ID;
    $member->Pw = $Pw;  
    $member->Adress = $Adress;
    $member->UserName = $UserName;

    // DBに会員データを登録する
    $memberDAO->insert($member);

    // 遷移
    header('Location: ログイン.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>わりペイ - 登録画面</title>
    <link rel="stylesheet" href="会員登録.css">

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
        <form action="" method="POST">
            <input type="text" name="Adress" value="<?=@$Adress ?>"placeholder="メールアドレス"required>
            <input type="password" name="Pw" value="<?=@$Pw ?>" placeholder="パスワード" required>
            <input type="password" name="Pw2"placeholder="パスワード再入力" required>
            <input type="text" name="UserName" value="<?=@$UserName ?>"placeholder="名前" required>
            <button type="submit" id="touroku-button">登録</button>      
        </form>
    </div>
    </div>
</body>
</html>
