<?php
require_once 'DAO.php';

class Event
{
    public string $EID; // イベントID
    public string $ID; // 会員ID
    public string $EventName; // イベント名
    public DateTime $EventDate; // イベント日時
    public DateTime $EventStart; // イベント開始日時
}

class EventDAO
{
    
    // イベントの追加
    public function add_event(string $userID, string $eventName, DateTime $eventDate): string
    {
        $dbh = DAO::get_db_connect();

        // 最新のイベントIDを取得
        $sql = "SELECT MAX(EID) as EID FROM イベント";
        $stmt = $dbh->query($sql);
        if ($stmt === false) {
            throw new Exception("Failed to fetch last event ID");
        }
        $lastEvent = $stmt->fetch(PDO::FETCH_ASSOC);

        // 新しいイベントIDを生成
        if ($lastEvent) {
            $lastID = (int)substr($lastEvent['EID'], 1);
            $newID = 'E' . str_pad($lastID + 1, 6, '0', STR_PAD_LEFT);
        } else {
            $newID = 'E000001';
        }

        // 現在の日時を取得
        $eventStart = new DateTime();

        // データベースに挿入
        $sql = "INSERT INTO イベント (EID, ID, EventName, EventDate, EventStart) VALUES (:EID, :ID, :EventName, :EventDate, :EventStart)";
        $stmt = $dbh->prepare($sql);
        $stmt->execute([
            ':EID' => $newID,
            ':ID' => $userID,
            ':EventName' => $eventName,
            ':EventDate' => $eventDate->format("Y-m-d H:i:s"),
            ':EventStart' => $eventStart->format("Y-m-d H:i:s"),
        ]);

        return $newID;
    }

    // イベントの変更
    public function update_event(string $eventID, string $eventName, DateTime $eventStart)
    {
        $dbh = DAO::get_db_connect();

        $sql = "UPDATE イベント SET EventName = :EventName, EventStart = :EventStart WHERE EID = :EID";
        $stmt = $dbh->prepare($sql);
        $stmt->execute([
            ':EventName' => $eventName,
            ':EventStart' => $eventStart->format('Y-m-d H:i:s'),
            ':EID' => $eventID,
        ]);
    }

    // イベントの削除
    public function delete_event(string $eventID)
    {
        $dbh = DAO::get_db_connect();
    
        // トランザクション開始
        $dbh->beginTransaction();
    
        try {
            // 子テーブル（イベントメンバー）の関連データを削除
            $sql = "DELETE FROM イベントメンバー WHERE EID = :EID";
            $stmt = $dbh->prepare($sql);
            $stmt->execute([':EID' => $eventID]);
    
            // イベントの削除
            $sql = "DELETE FROM イベント WHERE EID = :EID";
            $stmt = $dbh->prepare($sql);
            $stmt->execute([':EID' => $eventID]);
    
            // トランザクションコミット
            $dbh->commit();
        } catch (Exception $e) {
            // ロールバック
            $dbh->rollBack();
            throw $e;
        }
    }
    

    // イベントの取得
    public function get_event(string $eventID): ?Event
    {
        $dbh = DAO::get_db_connect();

        $sql = "SELECT * FROM イベント WHERE EID = :EID";
        $stmt = $dbh->prepare($sql);
        $stmt->execute([':EID' => $eventID]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $event = new Event();
            $event->EID = $row['EID'];
            $event->ID = $row['ID'];
            $event->EventName = $row['EventName'];
            $event->EventDate = new DateTime($row['EventDate']);
            $event->EventStart = new DateTime($row['EventStart']);
            return $event;
        }

        return null;
    }
}
