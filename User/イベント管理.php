<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>わりペイ</title>
    <link rel="stylesheet" href="イベント管理.css">
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
           
            <label1>イベント管理</label1>
            <hr><br>

            <label>イベント名</label><br>
            <input type="text" name="txtEventName" placeholder="イベント名を入力" id="event-name"><br><br>

            <label>イベント開始日時</label><br>
            <input type="date" name="txtEventDate" id="event-date"><br><br>

            <label>メンバー名</label><br>
            <div class="member-input-containner">
                <input type="text" placeholder="メンバー名を入力" id="memberName">
                <button class="button" onclick="addMember()">追加</button>
            </div>
            <div class="member-list" id="memberList"></div>
        </div>

        <div class="buttons">
            <button class="button button-update" id="update-button">更新</button>
            <button class="button button-back" onclick="history.back()">戻る</button>
        </div>

        <div class="buttons">
            <button class="button button-delete" onclick="deleteEvent()">削除</button>
        </div>
    </div>

    <script>
        // ページ読み込み時にイベントデータを読み込み
        window.onload = function() {
            const events = JSON.parse(localStorage.getItem("events")) || [];
            const editIndex = localStorage.getItem("editEventIndex");

            if (editIndex !== null) {
                const eventToEdit = events[editIndex];
                document.getElementById("event-name").value = eventToEdit.eventName;
                document.getElementById("event-date").value = eventToEdit.eventDate;

                const memberList = document.getElementById("memberList");
                eventToEdit.members.forEach(member => {
                    createMemberItem(member, memberList);  // 既存メンバーをリストに追加
                });
            }
        };

        // メンバー追加処理
        function addMember() {
            const memberNameInput = document.getElementById("memberName");
            const memberList = document.getElementById("memberList");

            if (memberNameInput.value.trim() !== "") {
                createMemberItem(memberNameInput.value, memberList);
                memberNameInput.value = "";  // 入力フィールドをリセット
            }
        }

        // メンバーアイテムを作成し、削除ボタンを追加
        function createMemberItem(memberName, memberList) {
            const memberItem = document.createElement("span");
            memberItem.className = "member-item";
            memberItem.textContent = memberName;

            const removeBtn = document.createElement("span");
            removeBtn.className = "remove-btn";
            removeBtn.textContent = "×";
            removeBtn.onclick = function() {
                memberItem.remove();  // クリックしたらメンバーを削除
            };

            memberItem.appendChild(removeBtn);  // メンバーアイテムに削除ボタンを追加
            memberList.appendChild(memberItem);  // メンバーリストに追加
        }

        // イベント更新処理
        document.getElementById("update-button").addEventListener('click', function() {
            const eventName = document.getElementById("event-name").value;
            const eventDate = document.getElementById("event-date").value;
            const members = Array.from(document.getElementById("memberList").children).map(member => member.textContent);

            const events = JSON.parse(localStorage.getItem("events")) || [];
            const editIndex = localStorage.getItem("editEventIndex");

            if (editIndex !== null) {
                events[editIndex] = {
                    eventName: eventName,
                    eventDate: eventDate,
                    members: members
                };
                localStorage.setItem("events", JSON.stringify(events));
                localStorage.removeItem("editEventIndex"); // 編集インデックスを削除
                window.location.href = "イベントの閲覧と選択.html"; // イベントリストに戻る
            }
        });

        // イベント削除処理
        function deleteEvent() {
            const events = JSON.parse(localStorage.getItem("events")) || [];
            const editIndex = localStorage.getItem("editEventIndex");

            if (editIndex !== null) {
                events.splice(editIndex, 1);  // イベントを削除

                localStorage.setItem("events", JSON.stringify(events));
                localStorage.removeItem("editEventIndex");  // 編集インデックスを削除
                window.location.href = "イベントの閲覧と選択.html"; // イベントリストに戻る
            }
        }
    </script>
</body>
</html>