<?php
require_once 'DAO.php';

class EventMember
{
    public string $EMID;   
    public string $EID;       
    public string $EventMemberName;  
}

class EventMemberDAO
{
    public function getEventMembersByEvent(string $EID): array
    {
        $dbh = DAO::get_db_connect();

        // SQL文: イベントメンバーを取得
        $sql = "SELECT em.id AS EMID, 
                       em.event_id AS EID, 
                       em.name AS EventMemberName
                FROM [イベントメンバー] em
                WHERE em.event_id = :eventId
                ORDER BY em.id ASC"; // EMIDで順序付け

        // ステートメントの準備
        $stmt = $dbh->prepare($sql);

        // パラメータをバインド
        $stmt->bindParam(':eventId', $EID, PDO::PARAM_STR);

        // 実行
        $stmt->execute();

        // 結果を取得
        $eventMembers = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $member = new EventMember();
            $member->EMID = $row['EMID'];
            $member->EID = $row['EID'];
            $member->EventMemberName = $row['EventMemberName'];
            $eventMembers[] = $member;
        }

        return $eventMembers;
    }
}
