<?php
// DB 接続設定の読み込み
require_once 'config.php';

class DAO {
    // DB 接続オブジェクト
    private static $dbh;

    // DB に接続するメソッド
    public static function get_db_connect() {
        try {
            if (self::$dbh === null) {
                // DB に接続する
                self::$dbh = new PDO(DSN, DB_USER, DB_PASSWORD);
                self::$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
        } catch (PDOException $e) {
            // エラーメッセージを表示して終了
            echo "データベース接続エラー: " . $e->getMessage();
            die();
        }

        // DB 接続オブジェクトを返す
        return self::$dbh;
    }
}
