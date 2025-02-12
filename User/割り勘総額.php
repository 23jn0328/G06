<?php
session_start();

// セッションからメンバーリストを取得
$members = $_SESSION['event_members'] ?? [];
$creatorName = $_SESSION['creatorName'] ?? null;

if (!$creatorName) {
    echo "作成者名が見つかりません。";
    exit;
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>割り勘総額 - わりペイ</title>
    <link rel="stylesheet" href="割り勘総額.css">
</head>
<style>
    /* メンバーリストの親要素（スクロール可能にする） */
.member-list-container {
    flex-grow: 1;
    overflow-y: auto !important; /* 強制的にスクロール可能にする */
    height: 100%; /* 高さを明示的に設定 */
    max-height: 73vh; /* 上限を設定 */
    scrollbar-width: thin;
    scrollbar-color: #888 #f0f0f0;
}

/* Webkit系ブラウザのスクロールバー設定 */
.member-list-container::-webkit-scrollbar {
    width: 8px;
}

.member-list-container::-webkit-scrollbar-thumb {
    background-color: #888;
    border-radius: 4px;
}

.member-list-container::-webkit-scrollbar-track {
    background-color: #f0f0f0;
}
</style>
<body>

<div id="main-container">
    <!-- ロゴ -->
    <div id="logo">
        <a href="イベントの閲覧と選択.php">
            <img src="img/image.png" alt="WARIPAYロゴ">
        </a>
    </div>

    <!-- 説明テキスト -->
    <div id="text-center">
        <small>
            <span class="text-blue">青字</span>は受け取り金額
            <span class="mx-1">/</span>
            <span class="text-red">赤字</span>は支払い金額
        </small>
    </div>

    <!-- メンバーリスト（スクロール可能） -->
    <div class="member-list-container">
        <ul class="member-list">
            <!-- 作成者 -->
            <?php if ($creatorName): ?>
                <li class="member-item">
                    <a><?= htmlspecialchars($creatorName, ENT_QUOTES, 'UTF-8') ?></a>
                    <div>
                    <a href="割り勘明細受け取り.php?eventId=<?php echo urlencode($eventId); ?>&motoId=<?php echo urlencode($motoEmid); ?>$sakiId=<?php echo urlencode($sakiid); ?>">
                            <span class="payment-amount">¥4000</span>
                        </a>
                        <a href="割り勘明細.php?eventId=<?php echo urlencode($eventId); ?>&motoId=<?php echo urlencode($motoEmid); ?>$sakiId=<?php echo urlencode($sakiid); ?>">
                            <span class="payment-amount2">¥2000</span>
                        </a>
                    </div>
                </li>
            <?php endif; ?>

            <!-- メンバーリスト -->
            <?php if (!empty($members)): ?>
                <?php foreach ($members as $member): ?>
                    <li class="member-item">
                        <a><?= htmlspecialchars($member['EventMemberName'] ?? '不明なメンバー', ENT_QUOTES, 'UTF-8') ?></a>
                        <div>
                        <a href="割り勘明細受け取り.php?eventId=<?php echo urlencode($eventId); ?>&motoId=<?php echo urlencode($motoEmid); ?>$sakiId=<?php echo urlencode($sakiid); ?>">
                                <span class="payment-amount">¥4000</span>
                            </a>
                            <a href="割り勘明細.php?eventId=<?php echo urlencode($eventId); ?>&motoId=<?php echo urlencode($motoEmid); ?>$sakiId=<?php echo urlencode($sakiid); ?>">
                                <span class="payment-amount2">¥2000</span>
                            </a>
                        </div>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li>メンバーが見つかりません。</li>
            <?php endif; ?>
        </ul>
    </div>

    <!-- 戻るボタン -->
    <a id="return-link" href="javascript:void(0);" onclick="history.back();">戻る</a>




</div>

<!-- JavaScript -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    let members = document.querySelectorAll(".member-item");

    members.forEach((member, index) => {
        member.style.animationDelay = `${index * 0.1}s`; // 0.1秒ずつ遅らせる
    });
});
</script>

</body>
</html>
