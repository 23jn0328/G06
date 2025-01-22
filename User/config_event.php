<?php
session_start();
require_once 'EventDAO.php';
require_once 'EventMemberDAO.php';

header('Content-Type: application/json');
$emid = '';
try {
    
    // POSTデータを取得
    $eventName = $_POST['event-name'];
    $eventDate = new DateTime($_POST['event-date']);
    $memberNames = $_POST['member-name'];
    $userID = $_SESSION['member_id'];  // 仮のユーザーID



    // EventDAOのインスタンスを作成
    $eventDAO = new EventDAO();
    
    // イベントを追加
    $eventId = $eventDAO->add_event($userID, $eventName, $eventDate);

    $eventMemberDAO = new EventMemberDAO();

    // メンバーを登録
    foreach ($memberNames as $memberName) {
        $eventMember = new EventMember();
        $emid = $eventMemberDAO->getNextEMID(); 
        $eventMember->EMID = $emid; // 新しいEMIDを生成
        $eventMember->EID = $eventId; // 登録されたイベントID
        $eventMember->EventMemberName = $memberName;
        //var_dump($eventMember);
        // メンバーを保存
        $eventMemberDAO->saveEventMember($eventMember);
    }
    
    // 成功レスポンスを返す
    echo json_encode(['success' => true, 'eventId' => $eventId]);

} catch (Exception $e) {
    // エラーレスポンスを返す
    echo json_encode(['success' => false, 'error' => $emid]);
}

