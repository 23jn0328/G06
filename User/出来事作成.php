<?php
require_once 'DAO.php';  // DAOクラスの読み込み
require_once 'HappenDao.php';  // HappenDaoクラスの読み込み

// セッション開始
session_start();
if (!isset($_SESSION['member_id'])) {
    // ログインしていない場合はログインページへリダイレクト
    header('Location: ログイン.php');
    exit;
}

$user_id = $_SESSION['member_id'];

// URLからイベントIDを取得し、セッションに保存
$eventID = $_GET['eventID'] ?? null;
if (!$eventID) {
    echo "イベントIDが指定されていません。";
    exit;
}

$_SESSION['eventID'] = $eventID; // イベントIDをセッションに保存

$happenDao = new HappenDao();
$user_name = $happenDao->getEventHostName($eventID);
$memberList = $happenDao->get_member_list($eventID);
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
    <form action="config_happen.php" method="POST">
        <div class="container">
            <label for="event-name" id="happenName" class="bold-text">出来事名</label>
            <input type="text" id="event-name" name="happenName" placeholder="出来事名を入力" required>
            
            <label for="event-date" id="happenDate" class="bold-text">出来事日時</label>
            <input type="date" id="event-date" name="happenDate" placeholder="出来事日時を入力" required>
            
            <label for="member-selection" class="bold-text">メンバー選択</label>
            <div class="checkbox-group" id="member-selection">
            <input type="checkbox" name="members[]" value="<?= $user_id ?>" onclick="calculatePerPerson()">
            <?= htmlspecialchars($user_name, ENT_QUOTES, 'UTF-8') ?>
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
                <option value="<?= $user_id ?>">
                    <?= htmlspecialchars($user_name, ENT_QUOTES, 'UTF-8') ?>
                </option>
                <?php foreach ($memberList as $member): ?>
                    <option value="<?= $member['EMID'] ?>">
                        <?= htmlspecialchars($member['EventMemberName'], ENT_QUOTES, 'UTF-8') ?>
                    </option>
                <?php endforeach; ?>
            </select>
            
            <label for="amount" class="bold-text">金額</label>
            <input type="number" id="amount" name="totalMoney" placeholder="¥" oninput="calculatePerPerson()" required>
            
            <label for="per-person" class="bold-text">一人当たり</label>
            <input type="text" id="per-person" name="smoney" placeholder="¥" readonly>

            <input type="hidden" name="eventID" value="<?= htmlspecialchars($eventID, ENT_QUOTES, 'UTF-8') ?>">
        </div>
        <div class="buttons">
            <!-- 修正：onclickのクオートの修正 -->
            <button type="submit" class="button button-create" id="add-button" onclick="location.href='config_happen.php?eventID=<?= $eventID ?>'">作成</button>
            <button type="button" class="button button-back" onclick="history.back()">戻る</button>
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
            
            if (selectedCount > 0 && !isNaN(amount) && amount > 0) {
                const perPersonAmount = Math.ceil(amount / selectedCount); // 小数点以下切り上げ
                perPersonField.value = `${perPersonAmount}`;
            } else {
                perPersonField.value = '';
            }
        }
        
        // ページ読み込み時に初期化
        window.onload = calculatePerPerson;
    </script>
</div>
</body>
</html>
