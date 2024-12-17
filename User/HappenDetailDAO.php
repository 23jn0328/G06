<?php
    require_once 'DAO.php';

class HappenDetail 
{
    public string $HSID; //出来事詳細ID
    public string $HID; //出来事ID
    public string $MotoKid; //請求元会員ID
    public string $SakiKID; //請求先会員ID
    public string $MotoEMID; //請求元イベントメンバーID
    public string $SakiEMID; //請求先イベントメンバーID
    public string $SMoney; //詳細金額
}
class HappenDetailDAO
{
    //出来事詳細情報を取得
    public function get_happendetails(string  $HID):array
    { 
            // データベース接続
        $dbh = DAO::get_db_connect();

        // 出来事詳細データを取得
        $sql = "SELECT HSID, HID, MotoKid, SakiKID, MotoEMID, SakiEMID, SMoney 
                FROM 出来事詳細
                WHERE HID = :HID";
        $stmt = $dbh->prepare($sql); 
        $stmt->bindValue(':HID', $HID, PDO::PARAM_STR);

        if (!$stmt->execute()) {
            throw new Exception("Failed to fetch happen details for HID: $HID");
        }
        // 結果を配列にマッピング
        $details = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $detail = new HappenDetail();
            $detail->HSID = $row['HSID'];
            $detail->HID = $row['HID'];
            $detail->MotoKid = $row['MotoKid'];
            $detail->SakiKID = $row['SakiKID'];
            $detail->MotoEMID = $row['MotoEMID'];
            $detail->SakiEMID = $row['SakiEMID'];
            $detail->SMoney = $row['SMoney'];
            $details[] = $detail;
        }
 
        return $details;
    }

    // HSIDを生成
    public function NewHappenDetailID(): string
    {
        $dbh = DAO::get_db_connect();

        // 最後のHSIDを取得
        $sql = "SELECT TOP 1 HSID FROM 出来事詳細 ORDER BY HSID DESC";
        $stmt = $dbh->query($sql);

        if ($stmt === false) {
            throw new Exception("Failed to fetch last HSID");
        }

        $lastID = $stmt->fetch(PDO::FETCH_ASSOC);

        // 新しいIDを生成
        if ($lastID) {
            $lastIDNum = (int)substr($lastID['HSID'], 2); // 'HS'を除去
            return 'HS' . str_pad($lastIDNum + 1, 6, '0', STR_PAD_LEFT);
        }

        return 'HS000001'; // IDがまだない場合の初期値
    }

    //出来事詳細情報をデータベースに保存
    public function save_happendetails(HappenDetail $happenDetail): bool
    {
        $dbh = DAO::get_db_connect();
        $sql = "INSERT INTO 出来事詳細 (HSID, HID, MotoKid, SakiKID, MotoEMID, SakiEMID, SMoney)
                VALUES (:HSID, :HID, :MotoKid, :SakiKID, :MotoEMID, :SakiEMID, :SMoney)";
        $stmt = $dbh->prepare($sql);

        $stmt->bindParam(':HSID', $happenDetail->HSID);
        $stmt->bindParam(':HID', $happenDetail->HID);
        $stmt->bindParam(':MotoKid', $happenDetail->MotoKid);
        $stmt->bindParam(':SakiKID', $happenDetail->SakiKID);
        $stmt->bindParam(':MotoEMID', $happenDetail->MotoEMID);
        $stmt->bindParam(':SakiEMID', $happenDetail->SakiEMID);
        $stmt->bindParam(':SMoney', $happenDetail->SMoney);

        return $stmt->execute();
    }
    
    //HSIDを削除
    public function delete_Happendetails(string $HSID)
    {
        $dbh = DAO::get_db_connect();
        $sql="DELETE FROM 出来事詳細 WHERE HSID = :HSID";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':HSID', $HSID, PDO::PARAM_STR);

        $stmt->execute();
    }
}


