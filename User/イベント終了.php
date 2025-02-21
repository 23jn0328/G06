<?php
require_once 'DAO.php';
require_once 'EventDAO.php';

session_start();

// セッションからメンバーリストを取得
$members = $_SESSION['event_members'] ?? [];
$creatorName = $_SESSION['creatorName'] ?? null;
$eventID = $_SESSION['eventID'] ?? null;  // ✅ イベントIDを取得

$motoKid = null;
if ($creatorName) {
    $dbh = DAO::get_db_connect();
    $stmt = $dbh->prepare("SELECT ID FROM 会員 WHERE UserName = :creatorName");
    $stmt->bindValue(':creatorName', $creatorName, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $motoKid = $result['ID'] ?? null;  // ✅ 作成者の支払者ID（motoKid）を取得
}

// イベント終了処理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $eventDAO = new EventDAO();
    $eventDAO->set_event_completed($eventID);
    header('Location: イベントの閲覧と選択.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>イベント終了 - わりペイ</title>
    <link rel="stylesheet" href="割り勘総額.css">
</head>
<body>

<div id="main-container">
    <!-- ロゴ -->
    <div id="logo">
        <a href="イベントの閲覧と選択.php">
            <img src="img/image.png" alt="WARIPAYロゴ">
        </a>
    </div>

    <ul class="member-list">
        <!-- 作成者 -->
        <?php if ($creatorName && isset($motoKid)): ?>
            <li class="member-item">
                <input type="checkbox" class="transaction-checkbox">
                <a><?= htmlspecialchars($creatorName, ENT_QUOTES, 'UTF-8') ?></a>
                <div>
                    <!-- ✅ 作成者の「明細」リンク -->
                    <a href="割り勘明細.php?eventId=<?= urlencode($eventID) ?>&motoKid=<?= urlencode($motoKid) ?>">
                        <span class="payment-amount2">明細</span>
                    </a>
                </div>
            </li>
        <?php endif; ?>

        <!-- メンバーリスト -->
        <?php if (!empty($members)): ?>
            <?php foreach ($members as $member): ?>
                <?php $motoEmid = $member['EMID'] ?? null; ?>
                <?php if ($motoEmid): ?>
                    <li class="member-item">
                        <input type="checkbox" class="transaction-checkbox">
                        <a><?= htmlspecialchars($member['EventMemberName'] ?? '不明なメンバー', ENT_QUOTES, 'UTF-8') ?></a>
                        <div>
                            <!-- ✅ メンバーごとの「明細」リンク -->
                            <a href="割り勘明細.php?eventId=<?= urlencode($eventID) ?>&motoEmid=<?= urlencode($motoEmid) ?>">
                                <span class="payment-amount2">明細</span>
                            </a>
                        </div>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php else: ?>
            <li>メンバーが見つかりません。</li>
        <?php endif; ?>
    </ul>

    <!-- イベント終了フォーム -->
    <form method="POST" action="">
        <input type="hidden" name="eventID" value="<?php echo $eventID; ?>" />
        <button type="submit" id="endButton" disabled>イベント終了</button>
    </form>

    <!-- 戻るボタン -->
    <a id="return-link" href="javascript:void(0);" onclick="history.back();">戻る</a>
</div>

<script>
    const checkboxes = document.querySelectorAll('.transaction-checkbox');
    const endButton = document.getElementById('endButton');

    function updateButtonState() {
        const allChecked = Array.from(checkboxes).every(checkbox => checkbox.checked);
        endButton.disabled = !allChecked;
    }

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateButtonState);
    });
</script>

</body>
</html>
