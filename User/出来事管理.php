<?php
session_start();

require_once 'config.php';
require_once 'HappenDAO.php';

$eventID=$_SESSION['eventID'];

$happenDAO=new HappenDAO();
$memberList=$happenDAO->get_member_list($eventID);

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>わりぺイ</title>
    <link rel="stylesheet" href="出来事管理style.css">
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
        <form action="update_happen.php" method="POST">

        <label for="event-name">出来事名</label>
        <input type="text" id="event-name" name="happenName" placeholder="出来事名を入力">
        
        <label for="event-date">出来事日時</label>
        <input type="date" id="event-date" name="happenDate" placeholder="出来事日時を入力">
        
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
    
    </div>

    <div class="buttons">
        <button class="button button-create" id="create-button">更新</button>
        <button class="button button-back" onclick="history.back()">戻る</button>
    </div>
    </form>

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
    
</script>
</div>

</body>
</html>
