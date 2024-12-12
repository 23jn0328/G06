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
    // イベントIDに基づいて出来事情報を取得
    public function get_happen_details_by_event_id(string $eventID): array
    {
        $dbh = DAO::get_db_connect();

        $sql = "SELECT HID, PayID, EID, PayEMID, HappenName, TotalMoney, HappenDate 
                    FROM 出来事 
                         WHERE EID = :EID ORDER BY HappenDate ASC";

        
        // クエリを準備して、パラメータをバインド
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':EID', $eventID, PDO::PARAM_STR);
        
        // 実行
        $stmt->execute();
        
        // 結果をHappenオブジェクトの配列として取得
        $happens = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $happen = new Happen();
            $happen->HID = $row['HID'];
            $happen->PayID = $row['PayID'];
            $happen->EID = $row['EID'];
            $happen->PayEMID = $row['PayEMID'];
            $happen->HappenName = $row['HappenName'];
            $happen->TotalMoney = (int) $row['TotalMoney'];
            $happen->HappenDate = new DateTime($row['HappenDate']);
            
            $happens[] = $happen;
        }
        
        return $happens;
    }
    public function delete_happen_by_id(string $happenID): bool
{
    try {
        $dbh = DAO::get_db_connect();

        $sql = "DELETE FROM 出来事 WHERE HID = :HID";
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':HID', $happenID, PDO::PARAM_STR);

        // 実行して成功したら true を返す
        return $stmt->execute();
    } catch (Exception $e) {
        // エラーログを出力
        error_log('Error in delete_happen_by_id: ' . $e->getMessage());
        return false; // エラーが発生したら false を返す
    }
}

}

