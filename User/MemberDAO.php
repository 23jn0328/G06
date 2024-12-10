
<?php
    require_once 'DAO.php';

    class Member
    {
        public string $ID;    //会員ID
        public string $Adress;       //メールアドレス
        public string $UserName;    //userName
        public string $Pw;    //パスワード
    }

    class MemberDAO
    {
        // DBからメールアドレスとパスワードが一致する会員データを取得する
        public function get_member(string $Adress, string $Pw)
        {
             $sql = "SELECT EID FROM イベント ORDER BY EID DESC LIMIT 1";
        $stmt = $this->dbh->query($sql);
        if ($stmt === false) {
            throw new Exception("Failed to fetch last event ID");
        }
        $lastEvent = $stmt->fetch(PDO::FETCH_ASSOC);

        // 新しいイベントIDを生成
        if ($lastEvent) {
            $lastID = (int)substr($lastEvent['EID'], 1);
            $newID = 'E' . str_pad($lastID + 1, 6, '0', STR_PAD_LEFT);
        } else {
            $newID = 'E000001';
        }
            //DBに接続する
            $dbh = DAO::get_db_connect();

            //メールアドレスが一致する会員データを取得する
            $sql = "SELECT * FROM ID WHERE Adress = :Adress";

            $stmt = $dbh->prepare($sql);

            //SQLに変数の値を当てはめる
            $stmt->bindValue(':Adress', $Adress, PDO::PARAM_STR);

            //SQLを実行する
            $stmt->execute();

            //１件分のデータをMemberクラスのオブジェクトとして取得する
            $member = $stmt->fetchObject('Member');

            //会員データが取得できたとき
            if ($member !== false){
                //パスワードが一致するか検証
                if (Pw_verify($Pw, $member->Pw)){
                    //会員データを返す
                    return $member;
                }
            }

            return false;
        }

        //会員データを登録する
        public function insert(Member $member){       
        $sql = "SELECT ID FROM 会員 ORDER BY ID DESC LIMIT 1";
        $stmt = $this->dbh->query($sql);
    if ($stmt === false) {
        throw new Exception("Failed to fetch last ID");
    }
    $lastID = $stmt->fetch(PDO::FETCH_ASSOC);

    // 新しいイベントIDを生成
    if ($lastID) {
        $lastID = (int)substr($lastID['ID'], 1);
        $newID = 'M' . str_pad($lastID + 1, 6, '0', STR_PAD_LEFT);
    } else {
        $newID = 'M000001';
    }
        // データベース接続の取得
        $dbh = DAO::get_db_connect();

        // SQLクエリの準備
        $sql = "INSERT INTO 会員 (Adress, UserName,Pw)
                VALUES (:Adress, :UserName,:Pw)";

        $stmt = $dbh->prepare($sql);

        // パスワードをハッシュ化
        $hashedPw = password_hash($member->Pw, PASSWORD_DEFAULT); // 関数名修正: password_hash()

        // プレースホルダに値をバインド
        $stmt->bindValue(':Adress', $member->Adress, PDO::PARAM_STR);
        $stmt->bindValue(':UserName', $member->UserName, PDO::PARAM_STR);
        $stmt->bindValue(':ID', $member->ID, PDO::PARAM_STR);
        $stmt->bindValue(':Pw', $hashedPw, PDO::PARAM_STR); // ハッシュ化したパスワードをバインド

        // SQLを実行
        $stmt->execute();

}

        //指定したメールアドレスの会員データが存在すればtrueを返す
        public function Adress_exists(string $Adress)
        {
            //DBに接続する
            $dbh = DAO::get_db_connect();

            $sql = "SELECT * 
                    FROM Member 
                    WHERE Adress = :Adress";

            $stmt = $dbh->prepare($sql);

            //SQLに変数の値を当てはめる
            $stmt->bindValue(':Adress', $Adress, PDO::PARAM_STR);

            //SQLを実行する
            $stmt->execute();

            if ($stmt->fetch() !== false){
                return true;    //同じ人がいる
            }
            else{
                return false;   //同じ人がいない
            }
        }
    }