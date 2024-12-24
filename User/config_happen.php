<?php
require_once 'HappenDAO.php';
require_once 'HappenDetailDAO.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // POSTデータの取得
    $payer = $_POST['payer'];
    $EventID = $_POST['eventID'];
    $HappenName = $_POST['happenName'];
    $TotalMoney = (int)($_POST['totalMoney']);
    $HappenDate = new DateTime($_POST['happenDate']);
    $members = $_POST['members'];

    // 支払者を解析
    $PayID = null;
    $PayEMID = null;
    if (preg_match('/^EID(\d+)$/', $payer, $matches)) {
        $PayID = $matches[1];
    } else{
        $PayEMID = $matches[1];
    } 

    $HappenDate = new DateTime($HappenDate);
    $HappenDao = new HappenDao();
        // データベースに出来事を追加
        $newHappenID = $happenDao->add_happen(
            $PayID,
            $EventID,
            $PayEMID,
            $HappenName,
            $TotalMoney,
            $HappenDate
        );

     // 出来事にメンバーを関連付ける
     foreach ($members as $member) {
        $HappenDao->add_happen_member($newHappenID, $member);
    }

    header('Location: 出来事の閲覧と選択.php');
    exit;
}
?>
