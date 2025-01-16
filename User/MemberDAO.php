
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
            //DBに接続する
            $dbh = DAO::get_db_connect();

            //メールアドレスが一致する会員データを取得する
            $sql = "SELECT * FROM 会員 WHERE Adress = :Adress ";

            $stmt = $dbh->prepare($sql);

            //SQLに変数の値を当てはめる
            $stmt->bindValue(':Adress', $Adress, PDO::PARAM_STR);

            //SQLを実行する
            $stmt->execute();

            //１件分のデータをMemberクラスのオブジェクトとして取得する
            $member = $stmt->fetchObject('Member');

            //会員データが取得できたとき
            if ($member !== false){
                return $member;
            }

            return false;
        }

        public function get_member_by_id($id)
        {
            $dbh = DAO::get_db_connect();

        // クエリの準備
            $sql = "SELECT * FROM 会員 WHERE ID = :id";
            $stmt = $dbh->prepare($sql);

        // IDをバインド
            $stmt->bindValue(':id', $id, PDO::PARAM_STR);

        // クエリ実行
            $stmt->execute();

        // 結果を取得して返す
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        //会員データを登録する
        public function insert(Member $member)
        {  
            // データベース接続の取得   
            $dbh = DAO::get_db_connect();
        
            // 最新のIDを取得
            $sql = "SELECT TOP 1 ID FROM 会員 ORDER BY ID DESC"; // 最新のIDを取得するためにORDER BYを使用
            $stmt = $dbh->query($sql);
        
            if ($stmt === false) {
                throw new Exception("Failed to fetch last ID");
            }
        
            $lastID = $stmt->fetch(PDO::FETCH_ASSOC);
        
            // 新しいIDを生成
            if ($lastID) {
                // IDから最初の文字'M'を除いて数値部分を取得し、+1して新しいIDを作成
                $lastIDNum = (int)substr($lastID['ID'], 1); // 'M'を除去
                $newID = 'M' . str_pad($lastIDNum + 1, 6, '0', STR_PAD_LEFT);
            } else {
                $newID = 'M000001';  // 会員がまだいない場合
            }
        
            // SQLクエリの準備
            $sql = "INSERT INTO 会員 (Adress, UserName, Pw, ID)
                    VALUES (:Adress, :UserName, :Pw, :ID)";
        
            $stmt = $dbh->prepare($sql);
        
            // プレースホルダに値をバインド
            $stmt->bindValue(':Adress', $member->Adress, PDO::PARAM_STR);
            $stmt->bindValue(':UserName', $member->UserName, PDO::PARAM_STR);
            $stmt->bindValue(':Pw', $member->Pw,PDO::PARAM_STR);
            $stmt->bindValue(':ID', $newID, PDO::PARAM_STR); // 新しいIDをバインド
        
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

        public function otpmusushin(string $Adress, string $otp, string $expires){
            //DBに接続する
            $dbh = DAO::get_db_connect();
            // OTPをデータベースに保存（データベース接続の準備が整っている前提）
            $stmt = $dbh->prepare("INSERT INTO otp_codes (Adress, otp, expires_at) VALUES (:Adress, :otp, :expires)");
            $stmt->execute(['Adress' => $Adress, 'otp' => $otp, 'expires' => $expires]);

            // OTPが生成され、保存されたことをユーザーに通知
            $message = "ワンタイムパスワードを発行しました。送信された確認コードは: $otp です。10分間有効です。";
            return $message;
    
        }
        public function otparukana(string $Adress){
            //DBに接続する
            $dbh = DAO::get_db_connect();
            //せれくと
            $stmt = $dbh->prepare("SELECT * FROM otp_codes WHERE Adress = :Adress AND expires_at > GETDATE()");
            $stmt->execute(['Adress' => $Adress]);

            if ($stmt->fetch() !== false){
                return true;   //あればtrue

            }
            else{
                return false;       //なければ false
            }
        }
        public function otpkousin(string $Adress, string $otp, string $expires){
            // update

            $message = "ワンタイムパスワードを発行しました。送信された確認コードは: $otp です。10分間有効です。";
            return $message;
        }


    }


