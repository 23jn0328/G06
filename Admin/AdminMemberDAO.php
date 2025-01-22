<?php
require_once 'DAO.php';

class AdminMemberDAO extends DAO {
    /**
     * 管理者IDとパスワードの検証
     *
     * @param string $adminID 管理者ID
     * @param string $password パスワード
     * @return bool 認証が成功したかどうか
     */
    public function validateAdmin($adminID, $password) {
        try {
            // データベース接続を取得
            $dbh = DAO::get_db_connect();

            // 管理者IDとパスワードをチェックするSQL
            $sql = "SELECT COUNT(*) AS count FROM 管理者 WHERE KanriID = :adminID AND KanriPW = :password";
            $stmt = $dbh->prepare($sql);
            $stmt->bindValue(':adminID', $adminID, PDO::PARAM_STR);
            $stmt->bindValue(':password', $password, PDO::PARAM_STR);

            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            // 結果が1件以上の場合は認証成功
            return $result['count'] > 0;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
}
