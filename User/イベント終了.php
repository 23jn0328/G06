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
    #支払者IDを取得してIDが設定されていなければエラー文
    $payer_id = $_GET['payer_id'] ?? null;

    #仮のID
    $payer_id = "M000001";

    if(!$payer_id){
        echo "支払者IDをいれろ";
        exit;
    }

    #支払者情報を取得して見つからない場合エラー文
    $payer = $memberDAO->get_member_by_id($payer_id);
    if(!$payer){
        echo "支払者が見つかりません。";
        exit;
    }

    function get_name($pay_id, $pay_emid, $memberDAO, $eventMemberDAO){
        if($pay_id){
            $payer = $memberDAO->get_member_by_id($pay_id);
            return $payer['UserName'] ?? '不明なユーザー';
        }elseif ($pay_emid){
            $payer = $eventMemberDAO->get_event_member_by_id($pay_emid);
            return $payer['EventMemberName'] ?? '不明なメンバー';
        }
        return '不明なユーザー';
    }

    $happen_list = $happenDAO->get_happen_details_by_event_id($payer_id);


?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>割り勘精算画面</title>
    <link rel="stylesheet" href="イベント終了.css">
</head>
<body>
    <div id="main-container">
        <div id="logo">
            <a href="イベントの閲覧と選択.php">
                <img src="img/image.png" alt="WARIPAYロゴ">
            </a>
        </div>
       
        <div class="transaction-list">
         
            
        
        <div class="transaction">
                <span>ひかる → はやと</span>
                <span class="amount">1000円</span>
                <input type="checkbox" class="checkbox">
            </div>
            <div class="transaction">
                <span>れおん → ひかる</span>
                <span class="amount">1000円</span>
                <input type="checkbox" class="checkbox">
            </div>
            <div class="transaction">
                <span>れおん → はやと</span>
                <span class="amount">2000円</span>
                <input type="checkbox" class="checkbox">
            </div>
            <div class="transaction">
                <span>しゅうと → ひかる</span>
                <span class="amount">1000円</span>
                <input type="checkbox" class="checkbox">
            </div>
            <div class="transaction">
                <span>しゅうと → はやと</span>
                <span class="amount">2000円</span>
                <input type="checkbox" class="checkbox">
            </div>
            <div class="transaction">
                <span>いくみ → ひかる</span>
                <span class="amount">1000円</span>
                <input type="checkbox" class="checkbox">
            </div>
            <div class="transaction">
                <span>いくみ → はやと</span>
                <span class="amount">2000円</span>
                <input type="checkbox" class="checkbox">
            </div>
        </div>
        <button id="endButton" class="end-button" disabled>精算を完了する</button>


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
            window.location.href = 'イベントの閲覧と選択.php';
        });
    </script>
    </div>
</body>
</html>
