<?php
require_once 'HappenDAO.php';

$happenDao = new HappenDAO();
$memberList = $happenDao->get_member_list();
?>

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
    <from action="config_happen" method="POST">
        <div class="container">
            <label for="event-name" id="happenName" class="bold-text">出来事名</label>
            <input type="text" id="event-name" name="happenName" placeholder="出来事名を入力" required>
            
            <label for="event-date" id="happenDate" class="bold-text">出来事日時</label>
            <input type="date" id="event-date" name="happenDate" placeholder="出来事日時を入力" required>
            
            <label for="member-selection" class="bold-text">メンバー選択</label>
            <div class="checkbox-group" id="member-selection">
                <?php foreach ($memberList as $member): ?>
                    <label>
                        <input type="checkbox" name="members[]" value="<?= $member['EMID'] ?>" onclick="calculatePerPerson()">
                            <?= htmlspecialchars($member['EventMemberName'], ENT_QUOTES, 'UTF-8') ?>
                    </label>
                <?php endforeach; ?>
            </div>
            
            <label for="payer" class="bold-text">払ったメンバー</label>
            <select id="payer" name="payer" required>
                <option value="" disabled selected>選択してください</option>
                    <?php foreach ($memberList as $member): ?>
                        <option value="<?= $member['EMID'] ?>">
                            <?= htmlspecialchars($member['EventMemberName'], ENT_QUOTES, 'UTF-8') ?>
                </option>
                <?php endforeach; ?>
            </select>
            
            <label for="amount" class="bold-text">金額</label>
            <input type="number" id="amount" name="totalMoney" placeholder="¥" oninput="calculatePerPerson()" required>
            
            <label for="per-person" class="bold-text">一人当たり</label>
            <input type="text" id="per-person" placeholder="¥" readonly>

            <input type="hidden" name="eventID" id="eventID" value="イベントのID">
        </div>
        <div class="buttons">
            <button type="submit"class="button button-create" id="add-button">作成</button>
            <button type="button"class="button button-back" onclick="history.back()">戻る</button>
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
            
            if (!isNaN(amount) && amount > 0) {
                const perPersonAmount = Math.ceil(amount / selectedCount); // 小数点以下切り上げ
                perPersonField.value = `¥${perPersonAmount}`;
            } else {
                perPersonField.value = '';
            }
        }
        //document.getElementById('add-button').addEventListener('click',function(){
            //window.location.href='出来事の閲覧と選択.php';
        //});
    </script>
</div>
</body>
</html>
