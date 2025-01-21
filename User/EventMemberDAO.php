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
            $sql = "SELECT MAX((SUBSTRING(EMID, 3,6) )) as max_id FROM イベントメンバー";
            $stmt = $dbh->query($sql);
            if ($stmt === false) {
                throw new Exception("Failed to fetch last EMID");
            }

            $lastID = $stmt->fetch(PDO::FETCH_ASSOC)['max_id'];

            // 新しいEMIDを生成
            if ($lastID !== null) {
                $newID = 'EM' . str_pad((int)$lastID + 1, 6, '0', STR_PAD_LEFT);
            } else {
                // テーブルが空の場合
                $newID = 'EM000001';
            }

            return $newID;
        }

        // イベントメンバー情報をデータベースから取得するメソッド
        public function getEventMembersByEvent(string $EID): array
        {
            try {
                $dbh = DAO::get_db_connect();

                // SQL文: イベントメンバーを取得
                $sql = "SELECT em.EMID, em.EID, em.EventMemberName
                        FROM イベントメンバー em
                        WHERE em.EID = :eventId
                        ORDER BY em.EMID ASC";

                $stmt = $dbh->prepare($sql);
                $stmt->bindParam(':eventId', $EID, PDO::PARAM_STR);
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

            } catch (PDOException $e) {
                throw new Exception("Failed to fetch event members: " . $e->getMessage());
            }
        }

        // イベントメンバー情報をデータベースに保存
        public function saveEventMember(EventMember $eventMember): bool
        {
            $dbh = DAO::get_db_connect();

            // イベントメンバーをINSERT
            $sql = "INSERT INTO イベントメンバー (EMID, EID, EventMemberName) 
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
