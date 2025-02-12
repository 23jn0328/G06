<?php
session_start();
require_once 'HappenDao.php';
require_once 'HappenDetailDAO.php';  // 追加

// ログインチェック
if (!isset($_SESSION['member_id'])) {
    header('Location: ログイン.php');
    exit;
}

// `happenID` と `eventID` をPOSTで取得
$happenID = $_POST['happenID'] ?? null;
$eventID = $_POST['eventID'] ?? null;

if (!$happenID || !$eventID) {
    echo "無効なリクエストです。";
    exit;
}

$happenDao = new HappenDao();
$happenDetailDao = new HappenDetailDAO(); // 追加

try {
    // HappenDetail のデータを削除
    $happenDetailDao->deleteHappenDetailsByHID($happenID);

    // Happen のデータを削除
    $happenDao->deleteHappen($happenID);
    
    // 削除後、一覧ページにリダイレクト
    header("Location: 出来事の閲覧と選択.php?eventID=" . htmlspecialchars($eventID, ENT_QUOTES, 'UTF-8'));
    exit;
} catch (Exception $e) {
    echo "エラー: " . $e->getMessage();
    exit;
}
?>
