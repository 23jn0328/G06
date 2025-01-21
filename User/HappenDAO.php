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
    public string $HappenDate; // 出来事日時
}

class HappenDao
{
    // イベントメンバー一覧を取得
    public function get_member_list(string $eventID): array
    {
        $dbh = DAO::get_db_connect();

        $sql = "SELECT EMID, EventMemberName FROM イベントメンバー where EID = :eID";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':eID', $eventID, PDO::PARAM_STR);

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
        string $happenDate
    ): string {
        $dbh = DAO::get_db_connect();
        //$sql = "SELECT COUNT(*) FROM 出来事 WHERE PayEMID = :PayEMID";
        //$stmt = $dbh->prepare($sql);
        //$stmt->bindParam(':PayEMID', $payEMID, PDO::PARAM_STR);
        //$stmt->execute();
        //$count = $stmt->fetchColumn();

        // PayEMID が dbo.イベントメンバー に存在するか確認
        $sql = "SELECT EMID FROM イベントメンバー WHERE EMID = :payEMID";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':payEMID', $payEMID, PDO::PARAM_STR);
        $stmt->execute();
        $member = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if (!$member) {
            echo "指定されたPayEMIDはイベントメンバーに存在しません。";
            exit; // 存在しない場合、処理を終了
        }
    
        // 最新の出来事IDを取得
        $sql = "SELECT MAX(HID) as HID FROM 出来事";
        $stmt = $dbh->query($sql);
        $lastHappen = $stmt->fetch(PDO::FETCH_ASSOC);
    
        $lastID = $lastHappen ? (int)substr($lastHappen['HID'], 1) : 0;
        $newID = 'H' . str_pad($lastID + 1, 6, '0', STR_PAD_LEFT);
    
        // 出来事を挿入
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
            ':HappenDate' => $happenDate, // 日付は文字列で渡される場合
        ]);
    
        return $newID;
    }
    

    // イベントIDに基づいて出来事を取得
    public function get_happen_details_by_event_id($eventID) {
        // DB接続を取得
        $dbh = DAO::get_db_connect();
    
        // SQL文の準備
        $sql = "SELECT HID, PayID, EID, PayEMID, HappenName, TotalMoney, HappenDate
                FROM 出来事
                WHERE EID = :eventID
                ORDER BY HappenDate DESC";
    
        // SQLの実行
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':eventID', $eventID, PDO::PARAM_STR);
        $stmt->execute();
    
        // 結果を返す
        return $stmt->fetchAll(PDO::FETCH_OBJ);
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
        //出来事更新
        public function update_happen(
            string $happenID,
            string $payID,
            string $payEMID,
            int $totalMoney,
            string $happenName,
            string $happenDate
        ): bool {
            try {
                // データベース接続
                $dbh = DAO::get_db_connect();
        
                // 更新するSQL文を準備
                $sql = "UPDATE 出来事 
                        SET PayID = :PayID, 
                            PayEMID = :PayEMID, 
                            TotalMoney = :TotalMoney, 
                            HappenName = :HappenName,
                            HappenDate = :HappenDate
                        WHERE HID = :HID";
        
                $stmt = $dbh->prepare($sql);
        
                // パラメータをバインド
                $stmt->bindValue(':PayID', $payID, PDO::PARAM_STR);
                $stmt->bindValue(':PayEMID', $payEMID, PDO::PARAM_STR);
                $stmt->bindValue(':TotalMoney', $totalMoney, PDO::PARAM_INT);
                $stmt->bindValue(':HappenName', $happenName, PDO::PARAM_STR);
                $stmt->bindValue(':HappenDate', $happenDate, PDO::PARAM_STR);
                $stmt->bindValue(':HID', $happenID, PDO::PARAM_STR);
        
                // 実行して結果を返す
                return $stmt->execute();
            } catch (PDOException $e) {
                // エラーログを記録
                error_log('Error in update_happen: ' . $e->getMessage());
                return false;
            }
        }
    
}
