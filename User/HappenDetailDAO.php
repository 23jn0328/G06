<?php
// 必要なファイルをインクルード
require_once 'DAO.php';

class HappenDetail 
{
    public string $HSID; // 出来事詳細ID
    public string $HID; // 出来事ID
    public string $MotoKid; // 請求元会員ID
    public string $SakiKID; // 請求先会員ID
    public string $MotoEMID; // 請求元イベントメンバーID
    public string $SakiEMID; // 請求先イベントメンバーID
    public string $SMoney; // 詳細金額
    public string $payertoPayID;
    public string $storePayerID;
}

class HappenDetailDAO
{
    // 出来事詳細情報を取得
    public function get_happendetails(string $HID): array
    { 
        $dbh = DAO::get_db_connect();
        $sql = "SELECT HSID, HID, MotoKid, SakiKID, MotoEMID, SakiEMID, SMoney FROM 出来事詳細 WHERE HID = :HID";
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':HID', $HID, PDO::PARAM_STR);
        
        if (!$stmt->execute()) {
            throw new Exception("Failed to fetch happen details for HID: $HID");
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
        return $lastID ? 'HS' . str_pad((int)substr($lastID['HSID'], 2) + 1, 6, '0', STR_PAD_LEFT) : 'HS000001';
    }

    // 出来事詳細を更新または挿入
    public function Save_Or_Update_MemberPayment($HID, $payertoPayIDList, $storePayerID ,$smoney)
    {
        
        $dbh = DAO::get_db_connect();
        $MotoKID = null;
        $MotoEMID = null;
        $SakiKID = null;
        $SakiEMID = null;

        if (isset($_POST['eventID'])) {
            $eventID = $_POST['eventID'];

        } else {
            echo 'イベントIDが指定されていません。';
            return;
        }
        
        $eventID = $_POST['eventID'];
       
        foreach ($payertoPayIDList as $payertoPayID) {
            
            //$sql = "SELECT HSID FROM 出来事詳細 WHERE HID = :HID AND (MotoKID = :kid OR MotoEMID = :emid)";
            //$stmt = $dbh->prepare($sql);
            //$stmt->bindParam(':kid', $member, PDO::PARAM_STR);
            //$stmt->bindParam(':emid', $member, PDO::PARAM_STR);
            //$stmt->bindParam(':HID', $HID, PDO::PARAM_STR);
            //$stmt->execute();
            
            //if ($stmt->fetch(PDO::FETCH_ASSOC)) {
                // 既存データがある場合は削除
                // $sql = "UPDATE 出来事詳細 
                //         SET MotoKid = :MotoKid, SakiKID = :SakiKID, MotoEMID = :MotoEMID, 
                //             SakiEMID = :SakiEMID, SMoney = :SMoney
                //         WHERE HSID = :HSID AND HID = :HID";
                 $HSID = $this->NewHappenDetailID(); // 新しいHSIDを生成
                
                if (preg_match('/^M\d+$/', $payertoPayID)) { // 会員が返済する場合
                    $MotoKID = $payertoPayID;
                   
                } else { //イベントメンバーが返済する場合
                   
                    $MotoEMID = $payertoPayID;
                    
                }

                // 支払者(店に支払う人)の判定
                if (preg_match('/^M\d+$/', $storePayerID)) { // 会員
                    $SakiKID = $storePayerID;
                   
                } else { // 非会員
                    $SakiEMID = $storePayerID;
                    
                }

                if ($MotoKID == null) {//支払元（誰かに払う人）がイベントメンバーの場合 会員が立て替えた場合
                   

                    if($SakiEMID == null){//支払先(店に払う人)が会員
                        $sql = "INSERT INTO 出来事詳細 (HSID, HID, MotoEMID, SakiKID,SMoney)
                        VALUES (:HSID, :HID, :MotoEMID, :SakiKID, :SMoney)";
                        $stmt = $dbh->prepare($sql);

                        $stmt->bindParam(':MotoEMID', $MotoEMID, PDO::PARAM_STR);
                        $stmt->bindParam(':SakiKID', $SakiKID, PDO::PARAM_STR);

                    }else{// 支払先(店に払う人)がイベントメンバー
                        $sql = "INSERT INTO 出来事詳細 (HSID, HID, MotoEMID, SakiEMID,SMoney) VALUES (:HSID, :HID, :notPayer,:SakiEMID, :SMoney)";
                        $stmt = $dbh->prepare($sql);

                        $stmt->bindParam(':notPayer', $MotoEMID, PDO::PARAM_STR);
                        $stmt->bindParam(':SakiEMID', $SakiEMID, PDO::PARAM_STR);

                    }

                    $stmt->bindParam(':HSID', $HSID, PDO::PARAM_STR);
                    $stmt->bindParam(':HID', $HID, PDO::PARAM_STR);
                    $smoney = (int)$smoney; // 数値型にキャスト
                    $stmt->bindParam(':SMoney', $smoney, PDO::PARAM_INT);
                    $stmt->execute();
                } else {//支払元（誰かに払う人）が会員の場合

                    if(preg_match('/^M\d+$/', $storePayerID)) {//支払先(店に払う人)が会員
                        $sql = "INSERT INTO 出来事詳細 (HSID, HID, MotoKID, SakiKID,SMoney)
                        VALUES (:HSID, :HID, :notPayer, :SakiKID, :SMoney)";
                        $stmt = $dbh->prepare($sql);

                        $stmt->bindParam(':notPayer', $MotoKID, PDO::PARAM_STR);
                        $stmt->bindParam(':SakiKID', $SakiKID, PDO::PARAM_STR);

                    }else{// 支払先(店に払う人)がイベントメンバー
                        $sql = "INSERT INTO 出来事詳細 (HSID, HID, MotoKID, SakiEMID , SMoney)
                        VALUES (:HSID, :HID, :notPayer,:SakiEMID, :SMoney)";
                        $stmt = $dbh->prepare($sql);

                        $stmt->bindParam(':notPayer', $MotoKID, PDO::PARAM_STR);
                        $stmt->bindParam(':SakiEMID', $SakiEMID, PDO::PARAM_STR);

                    }

                    $stmt->bindParam(':HSID', $HSID, PDO::PARAM_STR);
                    $stmt->bindParam(':HID', $HID, PDO::PARAM_STR);
                    $smoney = (int)$smoney; // 数値型にキャスト
                    $stmt->bindParam(':SMoney', $smoney, PDO::PARAM_INT);
                    $stmt->execute();
                }

                
            //} else {
                // データがない場合は挿入payer
                /*
                $HSID = $this->NewHappenDetailID(); // 新しいHSIDを生成
                $MotoKID = null;
                $MotoEMID = null;

                $HSID = $this->NewHappenDetailID();
                
                if ($payertoPayID && preg_match('/^EM\d+$/', $payertoPayID)) {
                    $MotoKID = preg_match('/^M\d+$/', $payertoPayID) ? $payertoPayID : null;
                    $MotoEMID = $MotoKID ? null : $payertoPayID;
                }
                
                
                
                $sql = "INSERT INTO 出来事詳細 (HSID, HID, MotoKID, MotoEMID, SMoney)
                        VALUES (:HSID, :HID, :MotoKID, :MotoEMID, :SMoney)";
                $stmt = $dbh->prepare($sql);
                $stmt->bindParam(':HSID', $HSID, PDO::PARAM_STR);
                $stmt->bindParam(':HID', $HID, PDO::PARAM_STR);
                $stmt->bindParam(':MotoKID', $MotoKID, PDO::PARAM_STR);
                $stmt->bindParam(':MotoEMID', $MotoEMID, PDO::PARAM_STR);
                $stmt->bindValue(':SMoney', (int)$smoney, PDO::PARAM_INT);
                $stmt->execute();
                */
            //}
            
        //}

        $MotoKID = null;
        $MotoEMID = null;
        $SakiKID = null;
        $SakiEMID = null;
    }
}
}