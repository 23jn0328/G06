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
    // イベントメンバー一覧を取得
    public function get_member_list(string $eventID): array
    {
        $dbh = DAO::get_db_connect();
        $sql = "SELECT EMID, EventMemberName FROM イベントメンバー WHERE EID = :eID";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':eID', $eventID, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // イベントIDから主催者IDを取得
    public function getEventHostID($eventID) {
        $dbh = DAO::get_db_connect();
        $sql = "SELECT ID FROM イベント WHERE EID = :eventID"; // ここでイベント主催者IDを取得
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':eventID', $eventID, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['ID'] : null;
    }

    public function getEventHostName($eventID) {
        $dbh = DAO::get_db_connect();
        $sql = "SELECT UserName FROM 会員 inner join  イベント on イベント.ID = 会員.ID WHERE EID = :eventID"; // ここでイベント主催者IDを取得
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':eventID', $eventID, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['UserName'] : null;
    }

    // 支払者情報を登録（PayIDまたはPayEMID） $smoney = 一人分の金額
    public function registerPayer($happenID, $payer, $smoney , $type) {
        $pdo = DAO::get_db_connect();
        $sql = "SELECT MAX(HSID) as HSID FROM 出来事詳細";
        $stmt = $pdo->query($sql);
        $lastHappen = $stmt->fetch(PDO::FETCH_ASSOC);
         $lastID = $lastHappen ? (int)substr($lastHappen['HSID'], 2) : 0;
        var_dump((int)substr($lastHappen['HSID'], 2));
        $newID = 'HS' . str_pad($lastID + 1, 6, '0', STR_PAD_LEFT);
        if ($type === 'PayID') {
            $sql = "INSERT INTO 出来事詳細 (HSID,HID, SakiKID, SMoney) VALUES (:hsID,:hID, :payer, :smoney)";
        } else {
            $sql = "INSERT INTO 出来事詳細 (HSID, HID, SakiEMID, SMoney) VALUES (:hsID,:hID, :payer, :smoney)";
        }
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':hsID', $newID, PDO::PARAM_STR);
        $stmt->bindParam(':hID', $happenID, PDO::PARAM_STR);
        $stmt->bindParam(':payer', $payer, PDO::PARAM_STR);
        $stmt->bindParam(':smoney', $smoney, PDO::PARAM_INT);
        $stmt->execute();
    }

    // 出来事を追加
    // 出来事を追加
public function add_happen(
    string $payID = null,
    string $eventID,
    string $payEMID = null,
    string $happenName,
    int $totalMoney,
    string $happenDate,
    int $smoney // 一人分の支払金額
): string {
    $dbh = DAO::get_db_connect();
    $hostID = $this->getEventHostID($eventID); // イベント主催者IDを取得

    if (!$hostID) {
        echo "イベント主催者が見つかりません。";
        exit; // 主催者がいない場合は処理を終了
    }

    // PayIDが会員IDとして存在するか確認
    if ($payID !== null) {
        $sql = "SELECT COUNT(*) FROM 会員 WHERE ID = :payID";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':payID', $payID, PDO::PARAM_STR);
        $stmt->execute();
        if ($stmt->fetchColumn() == 0) {
            echo "無効なPayIDです。";
            exit; // PayIDが無効な場合は処理を終了
        }
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

    // 非会員の場合、PayIDをNULLにし、PayEMIDを設定
    if ($payEMID !== null) {
        $payID = null; // PayIDはNULLに設定
    }

    $stmt->execute([
        ':HID' => $newID,
        ':PayID' => $payID,  // 会員ID（会員の場合に設定）
        ':EID' => $eventID,
        ':PayEMID' => $payEMID,  // 非会員ID（非会員の場合に設定）
        ':HappenName' => $happenName,
        ':TotalMoney' => $totalMoney,
        ':HappenDate' => $happenDate,
    ]);

    // 主催者がチェックされている場合はPayIDに登録、それ以外はPayEMIDに登録
    if ($payID == $hostID) {
        $this->registerPayer($newID, $payID, $smoney, 'PayID');
    } else {
        $this->registerPayer($newID, $payEMID, $smoney, 'PayEMID');
    }

    return $newID;
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

?>