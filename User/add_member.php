<?php
// add_member.php

require_once 'DAO.php';
require_once 'EventMemberDAO.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['event_id']) && isset($_POST['member_name'])) {
        $eventID = $_POST['event_id'];
        $memberName = $_POST['member_name'];

        // イベントメンバーDAOインスタンス作成
        $eventMemberDAO = new EventMemberDAO();

        // メンバーをデータベースに追加
        $result = $eventMemberDAO->add_member($eventID, $memberName);

        // 成功したら「success」を返し、失敗したら「error」を返す
        if ($result) {
            echo "success";
        } else {
            echo "error";
        }
    } else {
        echo "error";
    }
}
?>
