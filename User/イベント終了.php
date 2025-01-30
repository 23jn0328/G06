<?php
    require_once 'EventMemberDAO.php';
    require_once 'HappenDAO.php';
    require_once 'HappenDetailDAO.php';
    require_once 'DAO.php';
    require_once 'MemberDAO.php';

    $eventMemberDAO = new EventMember();
    $happenDAO = new HappenDAO();
    $happenDetailDAO = new HappenDetailDAO();
    $memberDAO = new MemberDAO();
    
    # 支払者IDを取得してIDが設定されていなければエラー文
    $payer_id = $_GET['payer_id'] ?? null;
    
    # 仮のID（実際のIDは$_GETから取得）
//    $payer_id = "M000001"; // この行は仮のIDを使っている場合のみ
    if (!$payer_id) {
        echo "支払者IDが指定されていません。";
        exit;
    }

    # 支払者情報を取得して見つからない場合エラー文
    $payer = $memberDAO->get_member_by_id($payer_id);
    if (!$payer) {
        echo "支払者が見つかりません。";
        exit;
    }

    function get_name($pay_id, $pay_emid, $memberDAO, $eventMemberDAO){
        if ($pay_id) {
            $payer = $memberDAO->get_member_by_id($pay_id);
            return $payer['UserName'] ?? '不明なユーザー';
        } elseif ($pay_emid) {
            $payer = $eventMemberDAO->get_event_member_by_id($pay_emid);
            return $payer['EventMemberName'] ?? '不明なメンバー';
        }
        return '不明なユーザー';
    }

    # イベントIDを取得（支払者のIDではなくイベントIDを基にする）
    $event_id = $_GET['event_id'] ?? null;
    if (!$event_id) {
        echo "イベントIDが指定されていません。";
        exit;
    }

    # イベントに関連する支払い情報を取得（イベントIDを使う場合）
    $happen_list = $happenDAO->get_happen_details_by_event_id($event_id);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="イベントの終了.css">
    <title>割り勘精算画面</title>
</head>
<body>
    <div class="container">
        <h2>割り勘精算</h2>
        <div class="transaction-list">
            <?php foreach ($happen_list as $happen): ?>
                <?php
                    $payer_name = get_name($happen['PayID'], $happen['PayEMID'], $memberDAO, $eventMemberDAO);
                    $receiver_name = get_name($happen['ReceiverPayID'], $happen['ReceiverPayEMID'], $memberDAO, $eventMemberDAO);  // 受け取る側の名前
                    $amount = $happen['Amount']; // 支払額
                ?>
                <div class="transaction">
                    <span><?= $payer_name ?> → <?= $receiver_name ?></span>
                    <span class="amount"><?= $amount ?>円</span>
                    <input type="checkbox" class="checkbox">
                </div>
            <?php endforeach; ?>
        </div>
        <button id="endButton" class="end-button" disabled>精算を完了する</button>
    </div>

    <script>
        const checkboxes = document.querySelectorAll('.checkbox');
        const endButton = document.getElementById('endButton');

        function updateButtonState() {
            const allChecked = Array.from(checkboxes).every(checkbox => checkbox.checked);
            if (allChecked) {
                endButton.classList.add('enabled');
                endButton.disabled = false;
            } else {
                endButton.classList.remove('enabled');
                endButton.disabled = true;
            }
        }

        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateButtonState);
        });
        
        document.getElementById('endButton').addEventListener('click', function () {
            // ここで精算完了の処理をサーバーに送信する
            window.location.href = 'イベントの閲覧と選択.php'; 
        });
    </script>
</body>
</html>
