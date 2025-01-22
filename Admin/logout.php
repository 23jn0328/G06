<?php
session_start();

// セッションを破棄してログイン画面にリダイレクト
session_unset();
session_destroy();

header('Location: 管理者ログイン画面.php');
exit();
?>
