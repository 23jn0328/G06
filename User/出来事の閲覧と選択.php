<?php
    require_once 'DAO.php';  // DAOクラスの読み込み
    require_once 'HappenDao.php';  // HappenDaoクラスの読み込み

    // セッション開始とイベントIDの取得
    session_start();
    if (!isset($_SESSION['member_id'])) {
        // ログインしていない場合はログインページへリダイレクト
        header('Location: ログイン.php');
        exit;
    }

    $user_id = $_SESSION['member_id'];

    // URLからイベントIDを取得
    $eventID = $_GET['eventID'] ?? null;
    if (!$eventID) {
        echo "イベントIDが指定されていません。";
        exit;
    }

    // HappenDaoインスタンス作成
    $happenDao = new HappenDao();

    // イベントメンバー一覧の取得
    $members = $happenDao->get_member_list();

    // 出来事一覧の取得
    $happens = $happenDao->get_happen_details_by_event_id($eventID);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WARIPAY</title>
    <link rel="stylesheet" href="出来事の閲覧と選択style.css">
</head>

        
    
<body>
    <div id="main-container">
            <!-- アプリタイトル -->
            <header>
                <div id="logo">
                    <a href="イベントの閲覧と選択.php">
                        <img src="img/image.png" alt="WARIPAYロゴ">
                    </a>
                </div>
            </header>
            
            
            <!-- イベント名 -->
            <h2 class="event-name">イベント名: <?= htmlspecialchars($eventID, ENT_QUOTES, 'UTF-8') ?></h2>

            <p>イベントメンバー：</p>
                <ul class="member-list">
                    <?php if (!empty($members)): ?>
                        <?php foreach ($members as $member): ?>
                            <li class="member-item"><?= htmlspecialchars($member['EventMemberName'], ENT_QUOTES, 'UTF-8') ?></li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>メンバーはまだ追加されていません。</p>
                    <?php endif; ?>
                </ul>
                






            <!-- 出来事の追加ボタン -->
            <button class="add-event-button" onclick="location.href='出来事作成.php?eventID=<?php echo $eventID; ?>'">出来事の追加</button>            <!-- 各費用項目 -->
            <?php if (!empty($happens)): ?>
            <?php foreach ($happens as $happen): ?>
                <div class="expense-item">
                    <h3 class="expense-title"><?= htmlspecialchars($happen->HappenName, ENT_QUOTES, 'UTF-8') ?></h3>
                    <p class="payer"><?= htmlspecialchars($happen->PayID, ENT_QUOTES, 'UTF-8') ?> が立て替え</p>
                    <div class="button-group">
                        <button class="person-button"><?= htmlspecialchars($happen->PayEMID, ENT_QUOTES, 'UTF-8') ?></button>
                        <button class="edit-button" onclick="location.href='出来事管理.php?happenID=<?= htmlspecialchars($happen->HID, ENT_QUOTES, 'UTF-8') ?>'">🖊</button>
                    </div>
                    <div class="amount">￥<?= number_format($happen->TotalMoney) ?></div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>現在、表示する出来事はありません。</p>
        <?php endif; ?>
        <!-- 割り勘総額ボタン -->
        <button class="summary-button" onclick="location.href='割り勘総額.php'">割り勘総額</button>
        <!-- イベント終了ボタン -->
        <button class="end-event-button" onclick="location.href='イベント終了.php'">イベント終了</button>
    </div>
</body>
</html>
