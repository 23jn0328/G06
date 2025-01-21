
<?php 
// 必要なファイルの読み込み
require_once 'DAO.php';


// ユーザーIDの取得（ログイン後にセッションで保持していると仮定）
session_start();
if (!isset($_SESSION['member_id'])) {
    // ログインしていない場合はログインページへリダイレクト
    header('Location: ログイン.php');
    exit;
}

$user_id = $_SESSION['member_id'];

// 仮のメンバーID
// $user_id = 'M000002';

try {
    // データベース接続
    $dbh = DAO::get_db_connect();

    // ユーザーの登録イベントを取得
    $sql = "SELECT UserName FROM 会員 WHERE ID = :user_id ";
    $stmt = $dbh->prepare($sql);
    
    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_STR);
    $stmt->execute();

    // 結果を取得
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $userName = $user['UserName'];
} catch (PDOException $e) {
    echo "データベースエラー: " . $e->getMessage();
    exit;
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>イベント作成 - WARIPAY</title>
    <link rel="stylesheet" href="イベント作成.css">
</head>
<body>
    <div id="main-container">
    <header>
        <div id="logo">
            <a href="イベントの閲覧と選択.php">
                <img src="img/image.png" alt="WARIPAYロゴ">
            </a>
        </div>
    </header>

        <div class="container">
            <label>イベント作成</label>
            <hr><br>

            <label for="event-name">イベント名</label><br>
            <input type="text" id="event-name" placeholder="イベント名を入力"><br><br>

            <label for="event-date">イベント開始日時</label><br>
            <input type="date" id="event-date"><br><br>

            <label for="member-name">メンバー名</label><br>
            <input type="text" id="member-name" placeholder="メンバー名を入力">
            <button type="button" onclick="addMember()">追加</button><br><br>

            <!-- メンバーリスト -->
            <div id="member-list"></div>

            <div class="buttons">
                <button class="button button-create" onclick="navigateToList()">作成</button>
                <button class="button button-back" onclick="history.back()">戻る</button>
            </div>
        </div>
    </div>

    <script>
       // 初期データ（PHPから渡されたユーザー名）
       const initialUserName = "<?php echo htmlspecialchars($userName, ENT_QUOTES, 'UTF-8'); ?>";

// ページロード時にユーザー名を追加
window.onload = function() {
    if (initialUserName) {
        addMemberToList(initialUserName);
    }
};

// メンバーをリストに追加する関数
function addMember() {
    const memberNameInput = document.getElementById("member-name");
    const memberName = memberNameInput.value.trim();

    if (memberName !== "") {
        addMemberToList(memberName);
        memberNameInput.value = ""; // 入力フィールドをリセット
    }
}

// メンバーリストに名前を追加する関数
function addMemberToList(name) {
    const memberList = document.getElementById("member-list");

    // メンバーアイテムを作成
    const memberItem = document.createElement("div");
    memberItem.className = "member-item";
    memberItem.textContent = name;

    // メンバーリストに追加
    memberList.appendChild(memberItem);
}

// 作成ボタンの画面遷移
function navigateToList() {
    // フォームの値を取得
    const EventName = document.getElementById('event-name').value;
    const EventDate = document.getElementById('event-date').value;
    const memberItems = document.querySelectorAll("#member-list .member-item");

    // メンバー名を配列にまとめる
    const MemberNames = Array.from(memberItems).map(item => item.textContent);

    // フォームデータとして作成
    const formData = new FormData();
    formData.append('event-name', EventName);
    formData.append('event-date', EventDate);
    MemberNames.forEach((name, index) => {
        formData.append('member-name[' + index + ']', name);
    });
    
    // POSTリクエスト
    fetch('config_event.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = "イベントの閲覧と選択.php";
        } else {
            alert('イベントの作成に失敗');
        }
    })
    .catch(error => {
        alert(error);
    });
}
    </script>
</body>
</html>
