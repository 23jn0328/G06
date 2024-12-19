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
        // 出来事詳細情報を取得
    public function get_happendetails(string $HID): array
    { 
        $dbh = DAO::get_db_connect();

        $sql = "SELECT HSID, HID, MotoKid, SakiKID, MotoEMID, SakiEMID, SMoney 
                FROM 出来事詳細
                WHERE HID = :HID";
        $stmt = $dbh->prepare($sql); 
        $stmt->bindValue(':HID', $HID, PDO::PARAM_STR);

        if (!$stmt->execute()) {
            throw new Exception("Failed to fetch happen details for HID: $HID");
        }

        $details = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $details[] = $row; // 配列に直接マッピング
        }

        return $details;
    }

    // 新しいHSIDを生成
    public function NewHappenDetailID(): string
    {
        $dbh = DAO::get_db_connect();

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

    // 出来事詳細を更新または挿入するメソッド
    public function Save_Or_Update_MemberPayment($HID, $members) 
    {
        $dbh = DAO::get_db_connect();

        foreach ($members as $member) {
            // 既存データを確認
            $sql = "SELECT HSID FROM 出来事詳細 WHERE HSID = :HSID AND HID = :HID";
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(':HSID', $member['HSID'], PDO::PARAM_STR);
            $stmt->bindParam(':HID', $HID, PDO::PARAM_STR);
            $stmt->execute();

            if ($stmt->fetch(PDO::FETCH_ASSOC)) {
                // 既存データがある場合は更新
                $sql = "UPDATE 出来事詳細 
                        SET MotoKid = :MotoKid, SakiKID = :SakiKID, MotoEMID = :MotoEMID, 
                            SakiEMID = :SakiEMID, SMoney = :SMoney
                        WHERE HSID = :HSID AND HID = :HID";
            } else {
                // データがない場合は挿入
                $sql = "INSERT INTO 出来事詳細 (HSID, HID, MotoKid, SakiKID, MotoEMID, SakiEMID, SMoney)
                        VALUES (:HSID, :HID, :MotoKid, :SakiKID, :MotoEMID, :SakiEMID, :SMoney)";
            }

            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(':HSID', $member['HSID']);
            $stmt->bindParam(':HID', $HID);
            $stmt->bindParam(':MotoKid', $member['MotoKid']);
            $stmt->bindParam(':SakiKID', $member['SakiKID']);
            $stmt->bindParam(':MotoEMID', $member['MotoEMID']);
            $stmt->bindParam(':SakiEMID', $member['SakiEMID']);
            $stmt->bindParam(':SMoney', $member['SMoney']);
            $stmt->execute();
        }
    }
}

