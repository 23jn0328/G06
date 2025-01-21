
<?php 
/*
session_start();
require_once 'EventMemberDAO.php';
require_once 'EventDAO.php';

$eventMemberDAO = new EventMemberDAO();
$emid = $eventMemberDAO->getNextEMID();
    // EventDAOのインスタンスを作成
    $eventDAO = new EventDAO();


   // イベントを追加
   $date = new DateTime('2025-01-17');
//$formatted_date = $date->format('Y-m-d H:i:s');

$eventId = $eventDAO->add_event($_SESSION['member_id'], "ev100", $date);

$eventMember = new EventMember();
$emid = $eventMemberDAO->getNextEMID(); 
$eventMember->EMID = $emid; // 新しいEMIDを生成
$eventMember->EID = $eventId; // 登録されたイベントID
$eventMember->EventMemberName = "ev100-1";

$eventMemberDAO->saveEventMember($eventMember);

$emid = $eventMemberDAO->getNextEMID(); 
$eventMember->EMID = $emid; // 新しいEMIDを生成
$eventMember->EID = $eventId; // 登録されたイベントID
$eventMember->EventMemberName = "ev100-2";
$eventMemberDAO->saveEventMember($eventMember);

*/
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
        // メンバーを追加する関数
        function addMember() {
            const memberNameInput = document.getElementById("member-name");
            const memberList = document.getElementById("member-list");

            if (memberNameInput.value.trim() !== "") {
                // メンバーアイテムを作成
                const memberItem = document.createElement("div");
                memberItem.className = "member-item";
                memberItem.textContent = memberNameInput.value;

                // メンバーリストに追加
                memberList.appendChild(memberItem);

                // 入力フィールドをリセット
                memberNameInput.value = "";
            }
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
                //console.error('Error:', error);
                alert(error);
            });
            
        }
    </script>
</body>
</html>
