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
            }
        } catch (PDOException $e) {
            // エラーメッセージを表示して終了
            echo $e->getMessage();
            die();
        }

        // DB 接続オブジェクトを返す
        return self::$dbh;
    }
}
