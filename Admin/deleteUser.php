<?php
require_once 'config.php';
require_once 'MemberDAO.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userID = $_POST['userID'] ?? null;

    if (!$userID) {
        echo "ユーザーIDが指定されていません。";
        exit();
    }

    try {
        $memberDAO = new MemberDAO();
        $memberDAO->deleteUser($userID);

        // 削除成功後に管理メイン画面にリダイレクト
        header('Location: 管理者メイン.php');
        exit();
    } catch (Exception $e) {
        echo "エラーが発生しました: " . $e->getMessage();
    }
}
?>
