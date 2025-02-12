<?php
require_once 'DAO.php';

class DetailrecDAO {

    /**
     * 支払いコンテナを取得
     * MotoEMID or MotoKID ごとに SakiEMID or SakiKID をまとめる
     * @param string $eventId
     * @return array
     */
    public function getPaymentContainers($eventId) {
        $dbh = DAO::get_db_connect();

        $sql = "SELECT DISTINCT
                hd.MotoEMID, hd.MotoKID, hd.SakiEMID, hd.SakiKID
            FROM 出来事詳細 hd
            INNER JOIN 出来事 h ON hd.HID = h.HID
            WHERE h.EID = :event_id
        ";
        
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':event_id', $eventId, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * 支払者と受取者の間の出来事明細を取得
     * @param string|null $motoId
     * @param string|null $sakiId
     * @return array
     */
    //支払先がイベントメンバーで支払元もイベントメンバー
    public function getPaymentDetailsByMotoEMID($sakiEmid, $motoId) {
        if ($motoId === null || $sakiEmid === null) {
            return [];
        }
    
        $dbh = DAO::get_db_connect();
    
    
        // MotoEMID の場合のみ SakiEMID をマッチさせる
        $sql = "SELECT 
                    h.HappenName,
                    h.HappenDate,
                    hd.SMoney
                FROM 出来事詳細 hd
                INNER JOIN 出来事 h ON hd.HID = h.HID
                WHERE (hd.MotoEMID = :moto_id AND hd.SakiEMID = :saki_id)";
    
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':moto_id', $motoId, PDO::PARAM_STR);
        $stmt->bindValue(':saki_id', $sakiEmid, PDO::PARAM_STR);
        $stmt->execute();
    
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        // デバッグ: SQL 実行結果をログに記録
        error_log("getPaymentDetails() - Result: " . json_encode($result));
    
        return $result;
    }

        //支払先がイベントメンバーで支払元が会員
        public function getPaymentDetailsBySakiKID($sakiEmid, $motoId) {
        if ($motoId === null || $sakiEmid === null) {
            return [];
        }
    
        $dbh = DAO::get_db_connect();
    
    
        // MotoEMID の場合のみ SakiEMID をマッチさせる
        $sql = "SELECT 
                    h.HappenName,
                    h.HappenDate,
                    hd.SMoney
                FROM 出来事詳細 hd
                INNER JOIN 出来事 h ON hd.HID = h.HID
                WHERE (hd.MotoKID = :moto_id AND hd.SakiEMID = :saki_id)";
    
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':moto_id', $motoId, PDO::PARAM_STR);
        $stmt->bindValue(':saki_id', $sakiEmid, PDO::PARAM_STR);
        $stmt->execute();
    
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        // デバッグ: SQL 実行結果をログに記録
        error_log("getPaymentDetails() - Result: " . json_encode($result));
    
        return $result;
    }
    
    ///支払先が会員で支払元がイベントメンバー
    public function getPaymentDetailsByMotoKID($sakiKid, $motoId) {
        if ($motoId === null || $sakiKid === null) {
            return [];
        }
    
        $dbh = DAO::get_db_connect();
    
        $sql = "SELECT 
                    h.HappenName,
                    h.HappenDate,
                    hd.SMoney
                FROM 出来事詳細 hd
                INNER JOIN 出来事 h ON hd.HID = h.HID
                WHERE (hd.MotoEMID = :moto_id 
                  AND hd.SakiKID = :saki_id)";
    
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':moto_id', $motoId, PDO::PARAM_STR);
        $stmt->bindValue(':saki_id', $sakiKid, PDO::PARAM_STR);
        $stmt->execute();
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * MotoEMIDを基準に合計金額を取得
     * @param string|null $motoId
     * @param string|null $sakiId
     * @return int
     */
    public function getTotalAmountByMotoEMID($sakiEmid, $motoId) {
        if ($motoId === null || $sakiEmid === null) {
            return 0;
        }
    
        $dbh = DAO::get_db_connect();
    
        // デバッグ用: 代入されている値をログに記録

    
        $sql = "SELECT COALESCE(SUM(hd.SMoney), 0) AS total_amount
                FROM 出来事詳細 hd
                WHERE hd.MotoEMID = :moto_id
                  AND hd.SakiEMID = :saki_id";
    
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':moto_id', $motoId, PDO::PARAM_STR);
        $stmt->bindValue(':saki_id', $sakiEmid, PDO::PARAM_STR);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // デバッグ用: SQL 実行結果をログに記録
        error_log("getTotalAmountByMotoEMID() - Result: " . json_encode($result));
    
        return $result['total_amount'] ?? 0;
    }

    public function getTotalAmountBySakiKID($sakiEmid, $motoId) {
        if ($motoId === null || $sakiEmid === null) {
            return 0;
        }
    
        $dbh = DAO::get_db_connect();
    
        // デバッグ用: 代入されている値をログに記録

    
        $sql = "SELECT COALESCE(SUM(hd.SMoney), 0) AS total_amount
                FROM 出来事詳細 hd
                WHERE hd.MotoKID = :moto_id
                  AND hd.SakiEMID = :saki_id";
    
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':moto_id', $motoId, PDO::PARAM_STR);
        $stmt->bindValue(':saki_id', $sakiEmid, PDO::PARAM_STR);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // デバッグ用: SQL 実行結果をログに記録
        error_log("getTotalAmountByMotoEMID() - Result: " . json_encode($result));
    
        return $result['total_amount'] ?? 0;
    }

    /**
     * MotoKIDを基準に合計金額を取得
     * @param string|null $motoId
     * @param string|null $sakiId
     * @return int
     */
    public function getTotalAmountByMotoKID($sakiKid, $motoId) {
        if ($motoId === null || $sakiKid === null) {
            return 0;
        }

        $dbh = DAO::get_db_connect();

        $sql = "SELECT COALESCE(SUM(hd.SMoney), 0) AS total_amount
            FROM 出来事詳細 hd
            WHERE hd.MotoEMID = :moto_id
                AND hd.SakiKID = :saki_id";

        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':moto_id', $motoId, PDO::PARAM_STR);
        $stmt->bindValue(':saki_id', $sakiKid, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total_amount'] ?? 0;
    }

    public function getUserNameByID($id) {

        $dbh = DAO::get_db_connect();
        
        if (strpos($id, 'M') === 0) {
            // 会員テーブルから取得
            $sql = "SELECT UserName FROM 会員 WHERE ID = :id";
        } elseif (strpos($id, 'EM') === 0) {
            // イベントメンバー テーブルから取得
            $sql = "SELECT EventMemberName FROM イベントメンバー WHERE EMID = :id";
        } else {
            return "こんにちは";
        }

        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ? ($result['UserName'] ?? $result['EventMemberName'] ?? "不明") : "不明";
    }
}
?>
