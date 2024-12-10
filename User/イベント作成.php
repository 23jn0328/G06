
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
            <input type="text" id="member-name" placeholder="メンバー名を入力"><br><br>

            <div class="buttons">
                <button class="button button-create" onclick="navigateToList()">作成</button>
                <button class="button button-back" onclick="history.back()">戻る</button>
            </div>
        </div>
    </div>

    <script>
        // 作成ボタンの画面遷移　こっから増田作
        function navigateToList() {
            // フォームの値をとってくるーー
            const EventName = document.getElementById('event-name').value;
            const EventDate = document.getElementById('event-date').value;
            const MemberName = document.getElementById('member-name').value;

            // いつものフォームデータとしてさくせいーー
            const formData = new FormData();
            formData.append('event-name', EventName);
            formData.append('event-date', EventDate);
            formData.append('member-name', MemberName);


            // POSTリクエストGO！
            fetch('config_event.php', {
                 method: 'POST',
                body: formData
            })
            // レスポンス文がjsonでかえってきはるもんで、その中身をはんてー
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    //console.log(data.event-name);
                 
                    window.location.href = "イベントの閲覧と選択.php";
                } else {
                    alert('イベントの作成に失敗したずら');
                }
            })
            .catch(error => {
                console.error('Error:', error);


                alert('エラーが発生したずら');
            });
        }
        
    </script>
</body>
</html>

    <script>
        // メンバーを追加する関数
    //     function addMember() {
    //         const memberNameInput = document.getElementById("memberName");
    //         const memberList = document.getElementById("memberList");

    //         if (memberNameInput.value.trim() !== "") {
    //             const memberItem = document.createElement("span");
    //             memberItem.className = "member-item";
    //             memberItem.textContent = memberNameInput.value;

    //             const removeBtn = document.createElement("span");
    //             removeBtn.className = "remove-btn";
    //             removeBtn.textContent = "×";
    //             removeBtn.onclick = () => memberItem.remove();
    //             memberItem.appendChild(removeBtn);

    //             memberList.appendChild(memberItem);

    //             memberNameInput.value = "";
    //         }
    //     }

    //     // イベントを作成してlocalStorageに保存
    //     document.getElementById("create-button").addEventListener('click', function() {
    //         const eventName = document.getElementById("event-name").value;
    //         const eventDate = document.getElementById("event-date").value;
    //         const members = Array.from(document.getElementById("memberList").children).map(member => member.textContent);

    //         if (eventName && eventDate && members.length > 0) {
    //             const newEvent = {
    //                 eventName: eventName,
    //                 eventDate: eventDate,
    //                 members: members
    //             };

    //             const events = JSON.parse(localStorage.getItem("events")) || [];
    //             events.push(newEvent);
    //             localStorage.setItem("events", JSON.stringify(events));

    //             // 作成後、イベント一覧ページに遷移
    //             window.location.href = 'イベントの閲覧と選択.html';
    //         } else {
    //             alert("すべてのフィールドを入力してください。");
    //         }
    //     });
    // </script>
</body>
</html>
