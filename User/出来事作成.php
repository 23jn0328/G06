<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WARIPAY</title>
    <link rel="stylesheet" href="出来事作成style.css">
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
        <label for="event-name" class="bold-text">出来事名</label>
        <input type="text" id="event-name" placeholder="出来事名を入力">
        
        <label for="event-date" class="bold-text">出来事日時</label>
        <input type="date" id="event-date" placeholder="出来事日時を入力">
        
        <label for="member-selection" class="bold-text">メンバー選択</label>
        <div class="checkbox-group" id="member-selection">
            <label><input type="checkbox" name="member" value="A" onclick="calculatePerPerson()"> A</label>
            <label><input type="checkbox" name="member" value="B" onclick="calculatePerPerson()"> B</label>
            <label><input type="checkbox" name="member" value="C" onclick="calculatePerPerson()"> C</label>
            <label><input type="checkbox" name="member" value="D" onclick="calculatePerPerson()"> D</label>
        </div>
        
        <label for="payer" class="bold-text">払ったメンバー</label>
        <select id="payer">
            <option value="A">A</option>
            <option value="B">B</option>
            <option value="C">C</option>
            <option value="D">D</option>
        </select>
        
        <label for="amount" class="bold-text">金額</label>
        <input type="number" id="amount" placeholder="¥" oninput="calculatePerPerson()">
        
        <label for="per-person" class="bold-text">一人当たり</label>
        <input type="text" id="per-person" placeholder="¥" readonly>
    </div>
    <div class="buttons">
        <button class="button button-create" id="add-button">作成</button>
        <button class="button button-back" onclick="history.back()">戻る</button>
    </div>

    <script>
        function calculatePerPerson() {
            const amount = parseFloat(document.getElementById('amount').value);
            const checkboxes = document.querySelectorAll('#member-selection input[type="checkbox"]');
            let selectedCount = 0;

            checkboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    selectedCount++;
                }
            });

            const perPersonField = document.getElementById('per-person');
            
            if (selectedCount > 0 && !isNaN(amount)) {
                const perPersonAmount = Math.ceil(amount / selectedCount);
                perPersonField.value = `¥${perPersonAmount}`;
            } else {
                perPersonField.value = '';
            }
        }

        document.getElementById('add-button').addEventListener('click', function() {
            window.location.href = '出来事の閲覧と選択.php';
        });
    </script>
</div>
</body>
</html>
