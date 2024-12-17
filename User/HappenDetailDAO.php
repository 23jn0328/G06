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

        // 支払い詳細を取得し、支払者・請求先の名前も取得するメソッド
        public function getPayDetails($HID) {
            $dbh = DAO::get_db_connect();
            
            // 出来事詳細テーブルから支払元会員ID、支払先会員ID、支払金額を取得
            $sql = "SELECT MotoKid, SakiKID, MotoEMID, SakiEMID, SMoney FROM 出来事詳細 WHERE HID = :HID";
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(':HID', $HID, PDO::PARAM_STR);
            $stmt->execute();
            
            $payments = [];
            
            // 支払元・支払先の名前をイベントメンバーから取得
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $payerName = $this->getMemberNameById($row['MotoEMID']);  // 支払者の名前
                $recipientName = $this->getMemberNameById($row['SakiEMID']);  // 請求先の名前
                
                $payments[] = [
                    'payer_name' => $payerName,  // 支払者名
                    'recipient_name' => $recipientName,  // 請求先名
                    'amount' => $row['SMoney'],    // 支払金額
                ];
            }
            
            return $payments;  // 支払金額の配列と名前
        }

        // イベントメンバーIDからメンバー名を取得するメソッド
        public function getMemberNameById($EMID) {
            $dbh = DAO::get_db_connect();
            
            // イベントメンバーテーブルからメンバー名を取得
            $sql = "SELECT MemberName FROM イベントメンバー WHERE EMID = :EMID";
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(':EMID', $EMID, PDO::PARAM_STR);
            $stmt->execute();
            
            $memberName = '';
            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $memberName = $row['MemberName'];
            }
            
            return $memberName;
        }  
    }


