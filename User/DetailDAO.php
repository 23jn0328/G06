<?php
require_once 'DAO.php';

class DetailDAO {

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
    public function getPaymentDetails($motoId, $sakiId) {
        if ($motoId === null || $sakiId === null) {
            return [];
        }
    
        $dbh = DAO::get_db_connect();
    
        // デバッグ: 受け取った値をログに記録
        error_log("getPaymentDetails() - MotoID: $motoId, SakiID: $sakiId");
    
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
        $stmt->bindValue(':saki_id', $sakiId, PDO::PARAM_STR);
        $stmt->execute();
    
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        // デバッグ: SQL 実行結果をログに記録
        error_log("getPaymentDetails() - Result: " . json_encode($result));
    
        return $result;
    }

    /**
     * MotoEMIDを基準に合計金額を取得
     * @param string|null $motoId
     * @param string|null $sakiId
     * @return int
     */
    public function getTotalAmountByMotoEMID($motoId, $sakiId) {
        if ($motoId === null || $sakiId === null) {
            return 0;
        }
    
        $dbh = DAO::get_db_connect();
    
        // デバッグ用: 代入されている値をログに記録
        error_log("getTotalAmountByMotoEMID() - MotoID: $motoId, SakiID: $sakiId");
    
        $sql = "SELECT COALESCE(SUM(hd.SMoney), 0) AS total_amount
                FROM 出来事詳細 hd
                WHERE hd.MotoEMID = :moto_id
                  AND hd.SakiEMID = :saki_id";
    
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':moto_id', $motoId, PDO::PARAM_STR);
        $stmt->bindValue(':saki_id', $sakiId, PDO::PARAM_STR);
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
    public function getTotalAmountByMotoKID($motoId, $sakiId) {
        if ($motoId === null || $sakiId === null) {
            return 0;
        }

        $dbh = DAO::get_db_connect();

        $sql = "SELECT COALESCE(SUM(hd.SMoney), 0) AS total_amount
            FROM 出来事詳細 hd
            WHERE hd.MotoKID = :moto_id
                AND hd.SakiKID = :saki_id
        ";

        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':moto_id', $motoId, PDO::PARAM_STR);
        $stmt->bindValue(':saki_id', $sakiId, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total_amount'] ?? 0;
    }

    public function getUserNameByID($id) {

        var_dump($id);

        $dbh = DAO::get_db_connect();
        
        if (strpos($id, 'M') === 0) {
            // 会員テーブルから取得
            $sql = "SELECT UserName FROM 会員 WHERE ID = :id";
        } elseif (strpos($id, 'EM') === 0) {
            // イベントメンバー テーブルから取得
            $sql = "SELECT EventMemberName FROM イベントメンバー WHERE EMID = :id";
        } else {
            return "不明";
        }

        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        var_dump($result); // デバッグ: SQLの実行結果を確認

        return $result ? ($result['UserName'] ?? $result['EventMemberName'] ?? "不明") : "不明";
    }
}
?>
