<?php

require_once 'EventDAO.php';
header('Content-Type: application/json');
try {
    
    // POSTデータを取得
    $eventName = $_POST['event-name'];
    $eventDate = new DateTime($_POST['event-date']);
    $memberName = $_POST['member-name'];
    $userID = "M000002";  // 仮のユーザーID

    // EventDAOのインスタンスを作成
    $eventDAO = new EventDAO();

    // イベントを追加
    $eventId = $eventDAO->add_event($userID, $eventName, $eventDate);
    
    // 成功レスポンスを返す
    echo json_encode(['success' => true, 'eventId' => $eventId]);

} catch (Exception $e) {
    // エラーレスポンスを返す
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

