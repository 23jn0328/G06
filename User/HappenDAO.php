<?php
require_once 'DAO.php';

class Happen
{
    public string $HID; // 出来事ID
    public string $PayID; // 支払者会員ID
    public string $EID; // イベントID
    public string $PayEMID; // 支払者イベントメンバーID
    public string $HappenName; // 出来事名
    public int $TotalMoney; // 総額
    public DateTime $HappenDate; // 出来事日時
}

class HappenDao
{
    // イベントメンバー一覧を取得
    public function get_member_list(): array
    {
        $dbh = DAO::get_db_connect();

        $sql = "SELECT EMID, EventMemberName FROM イベントメンバー";
        $stmt = $dbh->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 出来事を追加
    public function add_happen(
        string $payID,
        string $eventID,
        string $payEMID,
        string $happenName,
        int $totalMoney,
        DateTime $happenDate
    ): string {
        $dbh = DAO::get_db_connect();

        // 最新の出来事IDを取得
        $sql = "SELECT MAX(HID) as HID FROM 出来事";
        $stmt = $dbh->query($sql);
        $lastHappen = $stmt->fetch(PDO::FETCH_ASSOC);

        $lastID = $lastHappen ? (int)substr($lastHappen['HID'], 1) : 0;
        $newID = 'H' . str_pad($lastID + 1, 6, '0', STR_PAD_LEFT);

        $sql = "INSERT INTO 出来事 (HID, PayID, EID, PayEMID, HappenName, TotalMoney, HappenDate)
                VALUES (:HID, :PayID, :EID, :PayEMID, :HappenName, :TotalMoney, :HappenDate)";
        $stmt = $dbh->prepare($sql);
        $stmt->execute([
            ':HID' => $newID,
            ':PayID' => $payID,
            ':EID' => $eventID,
            ':PayEMID' => $payEMID,
            ':HappenName' => $happenName,
            ':TotalMoney' => $totalMoney,
            ':HappenDate' => $happenDate->format("Y-m-d H:i:s"),
        ]);

        return $newID;
    }

    // イベントIDに基づいて出来事を取得
    public function get_happen_details_by_event_id(string $eventID): array
    {
        $dbh = DAO::get_db_connect();
    
        $sql = "SELECT HID, PayID, EID, PayEMID, HappenName, TotalMoney, HappenDate 
                FROM 出来事 
                WHERE EID = :EID 
                ORDER BY HappenDate ASC";
    
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':EID', $eventID, PDO::PARAM_STR);
        $stmt->execute();
    
        $happens = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $happen = new Happen();
            $happen->HID = $row['HID'];
            $happen->PayID = $row['PayID'];
            $happen->EID = $row['EID'];
    
            // PayEMIDがnullの場合、空文字列を代入
            $happen->PayEMID = $row['PayEMID'] ?? '';  // nullなら空文字列
    
            $happen->HappenName = $row['HappenName'];
            $happen->TotalMoney = (int)$row['TotalMoney'];
            $happen->HappenDate = new DateTime($row['HappenDate']);
    
            $happens[] = $happen;
        }
    
        return $happens;
    }
    

    // 出来事を削除
    public function delete_happen_by_id(string $happenID): bool
    {
        try {
            $dbh = DAO::get_db_connect();

            $sql = "DELETE FROM 出来事 WHERE HID = :HID";
            $stmt = $dbh->prepare($sql);
            $stmt->bindValue(':HID', $happenID, PDO::PARAM_STR);

            return $stmt->execute();
        } catch (Exception $e) {
            error_log('Error in delete_happen_by_id: ' . $e->getMessage());
            return false;
        }
    }
}

