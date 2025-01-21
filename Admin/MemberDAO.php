<?php
require_once 'DAO.php';

class Member {
    public string $ID;          // 会員ID
    public string $Adress;      // メールアドレス
    public string $UserName;    // ユーザー名
    public string $Pw;          // パスワード
}

class MemberDAO extends DAO {
    /**
     * 全ユーザーとイベント作成数を取得
     *
     * @return array ユーザーIDとイベント作成数を含む配列
     */
    public function getAllUsersWithEventCounts() {
        try {
            $dbh = DAO::get_db_connect();
            $sql = "
                SELECT m.ID, COUNT(e.EID) AS イベント作成数
                FROM 会員 m
                LEFT JOIN イベント e ON m.ID = e.ID
                GROUP BY m.ID
                ORDER BY m.ID
            ";
            $stmt = $dbh->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }

    /**
     * 会員情報の取得
     */
    public function get_member(string $Adress, string $Pw) {
        try {
            $dbh = DAO::get_db_connect();
            $sql = "SELECT * FROM 会員 WHERE Adress = :Adress AND Pw = :Pw";
            $stmt = $dbh->prepare($sql);
            $stmt->bindValue(':Adress', $Adress, PDO::PARAM_STR);
            $stmt->bindValue(':Pw', $Pw, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchObject('Member');
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }
    }

    public function deleteUser($userID) {
        try {
            $dbh = DAO::get_db_connect();
    
            // トランザクションを開始
            $dbh->beginTransaction();
    
            // 1. 出来事テーブルのデータを削除
            $sqlHappen = "
                DELETE FROM 出来事
                WHERE PayEMID IN (
                    SELECT EID FROM イベント WHERE ID = :userID
                )
            ";
            $stmtHappen = $dbh->prepare($sqlHappen);
            $stmtHappen->execute([':userID' => $userID]);
    
            // 2. イベントメンバーテーブルのデータを削除
            $sqlEventMembers = "
                DELETE FROM イベントメンバー
                WHERE EID IN (
                    SELECT EID FROM イベント WHERE ID = :userID
                )
            ";
            $stmtEventMembers = $dbh->prepare($sqlEventMembers);
            $stmtEventMembers->execute([':userID' => $userID]);
    
            // 3. イベントテーブルのデータを削除
            $sqlEvents = "DELETE FROM イベント WHERE ID = :userID";
            $stmtEvents = $dbh->prepare($sqlEvents);
            $stmtEvents->execute([':userID' => $userID]);
    
            // 4. 会員テーブルのデータを削除
            $sqlMember = "DELETE FROM 会員 WHERE ID = :userID";
            $stmtMember = $dbh->prepare($sqlMember);
            $stmtMember->execute([':userID' => $userID]);
    
            // トランザクションをコミット
            $dbh->commit();
        } catch (PDOException $e) {
            // エラーが発生した場合はロールバック
            $dbh->rollBack();
            throw $e;
        }
    }
    
    
    
    
}
