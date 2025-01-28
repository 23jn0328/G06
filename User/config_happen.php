<?php
require_once 'HappenDAO.php';
require_once 'HappenDetailDAO.php';
session_start();

// セッションから eventID を取得
$eventID = $_POST['eventID'] ?? null;

if (!$eventID) {
    echo "イベントIDがセッションに保存されていません。";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // POSTデータの取得
    $payer = $_POST['payer'];
    $EventID = $eventID; // セッションから取得したeventIDを使用
    $HappenName = $_POST['happenName'];
    $SMoney = $_POST['smoney'];

    // 金額を数値に変換
    $TotalMoney = $_POST['totalMoney'];
    if (is_numeric($TotalMoney) && is_numeric($SMoney)) {
        $TotalMoney = intval($TotalMoney);  // 明示的に整数型に変換
        $SMoney = intval($SMoney);  // 明示的に整数型に変換
    } else {
        echo "金額は数値でなければなりません。";
        exit;
    }

    $HappenDate = $_POST['happenDate'];
    $members = $_POST['members'];

    // 支払者を解析
    $PayID = null;
    $PayEMID = null;
    if (preg_match('/^EM(\d+)$/', $payer, $matches)) {
        $PayEMID = $matches[1];
    } else {
        $PayEMID = null; 
        $PayID = $payer;
    }

    if ($PayEMID === null) {
        $PayID = $payer;
    } else {
        $PayEMID = $payer;
    }

    // 日付の形式が正しいか確認
    $HappenDate = DateTime::createFromFormat('Y-m-d', $HappenDate);
    if (!$HappenDate) {
        echo "日付の形式が正しくありません。";
        exit;
    }

    // HappenDaoをインスタンス化
    $HappenDao = new HappenDao();
    $newHappenID = $HappenDao->add_happen(
        $PayID, 
        $EventID,  // セッションから取得したeventIDを使用
        $PayEMID,
        $HappenName,
        $TotalMoney,  
        $HappenDate->format('Y-m-d H:i:s'),
        $SMoney
    );

    $happenDetailDao = new HappenDetailDAO();
    $happenDetailDao->Save_Or_Update_MemberPayment(
        $newHappenID, 
        $members,  
        $payer,     
        $SMoney
    );

    if (empty($members)) {
        echo "メンバーが選択されていません。";
        exit;
    }

    echo "受け取ったイベントID: " . htmlspecialchars($eventID, ENT_QUOTES, 'UTF-8');
    header('Location: 出来事の閲覧と選択.php?eventID=' . urlencode($eventID));

    exit;
}
?>
