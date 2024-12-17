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
        // 次のEMIDを生成する
        public function getNextEMID(): string 
        {
            $dbh = DAO::get_db_connect();

            // 最大のEMIDの数字部分を取得
            $sql = "SELECT MAX(CAST(SUBSTRING(EMID, 3) AS UNSIGNED)) as EID FROM イベントメンバー";
            $stmt = $dbh->query($sql);
            if ($stmt === false) {
                throw new Exception("Failed to fetch last event ID");
            }
            
            $lastEvent = $stmt->fetch(PDO::FETCH_ASSOC);

            // 新しいEMIDを生成
            if ($lastEvent && isset($lastEvent['EID'])) {
                // 数字部分を取得し、1を加える
                $lastID = (int)$lastEvent['EID'];  
                $newID = 'EM' . str_pad($lastID + 1, 6, '0', STR_PAD_LEFT); // 新しいEMIDを生成
            } else {
                // 最初の場合、EM000001を返す
                $newID = 'EM000001';
            }

            return $newID;
        }

        // イベントメンバー情報をデータベースから取得するメソッド
        public function getEventMembersByEvent(string $EID): array
        {
            $dbh = DAO::get_db_connect();

            // SQL文: イベントメンバーを取得
            $sql = "SELECT em.event_id AS EID, 
                            em.name AS EventMemberName
                    FROM イベントメンバー em
                    WHERE em.event_id = :eventId
                    ORDER BY em.id ASC";

            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(':eventId', $EID, PDO::PARAM_STR);
            $stmt->execute();

            // 結果を取得
            $eventMembers = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $member = new EventMember();
                $member->EMID = $this->getNextEMID(); // 毎回新しいIDを生成
                $member->EID = $row['EID'];
                $member->EventMemberName = $row['EventMemberName'];

                $eventMembers[] = $member;
            }

            return $eventMembers;
        }


        // イベントメンバー情報をデータベースに保存
        public function saveEventMember(EventMember $eventMember): bool
        {
            $dbh = DAO::get_db_connect();

            // イベントメンバーをINSERT
            $sql = "INSERT INTO イベントメンバー (EMID, event_id, name) 
                    VALUES (:EMID, :EID, :EventMemberName)";

            $stmt = $dbh->prepare($sql);

            $stmt->bindParam(':EMID', $eventMember->EMID, PDO::PARAM_STR);
            $stmt->bindParam(':EID', $eventMember->EID, PDO::PARAM_STR);
            $stmt->bindParam(':EventMemberName', $eventMember->EventMemberName, PDO::PARAM_STR);

            return $stmt->execute();
        }

        //イベントメンバーを削除
        public function delete_EventMember(string $EMID)
        {
        $dbh = DAO::get_db_connect();

        $sql = "DELETE FROM イベントメンバー WHERE EMID = :EMID";
        $stmt = $dbh->prepare($sql);
        $stmt->execute([
            ':EMID' => $EMID,
        ]);
        }
    }
?>
