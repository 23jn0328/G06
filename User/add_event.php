
<?php
ini_set('display_errors',1);
error_reporting(E_ALL);
// 実行するためのエンドポイントをつくるよ！
require_once 'EventDAO.php';
//var_dump($_POST['event-name']);

try {
    // POSTデータを取得
    $eventName = $_POST['event-name'];
    $eventDate = new DateTime($_POST['event-date']);
    $memberName = $_POST['member-name'];
    //var_dump($_POST);
    //echo json_encode(['success' => true,'event-name' => $_POST['event-name'], 'eventDate' => $_POST['event-date']]);
    $userID = "U000001";  // 仮のユーザーID

    // EventDAOのインスタンスを作成
    $eventDAO = new EventDAO();

    // イベントを追加
    $eventId = $eventDAO->add_event($userID, $eventName, $eventDate);
    
    // 成功レスポンスを返す
    echo json_encode(['success' => true, 'eventId' => $eventId]);

} catch (Exception $e) {
    // エラーレスポンスを返す
    echo json_encode(['success' => false, 'error' => $e->getMessage(),'event-name' =>$_POST['event-name']
    ,'member-name' =>$_POST['member-name'],'event-date' =>$_POST['event-date']]);
}
    
?>