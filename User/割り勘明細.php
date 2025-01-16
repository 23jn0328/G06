<?php

    require_once 'DAO.php';
    require_once 'MemberDAO.php';
    require_once 'HappenDAO.php';
    require_once 'HappenDetailDAO.php';
    require_once 'EventMemberDAO.php';

    $memberDAO = new MemberDAO();
    $happenDAO = new HappenDAO();
    $happenDetailDAO = new HappenDetailDAO();
    $eventMemberDAO = new EventMemberDAO();

    #支払者IDを取得してIDが設定されていなければエラー文
    $payer_id = $_GET['payer_id'] ?? null;

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
    <title>割り勘明細 - わりペイ</title>
    <link rel="stylesheet" href="割り勘明細.css">
</head>
<body>

    <!-- メインコンテナ -->
    <div id="main-container">
    <header>
            <div id="logo">
                <a href="イベントの閲覧と選択.php">
                    <img src="img/image.png" alt="WARIPAYロゴ">
                </a>
            </div>
    </header>

    <!-- スクロールエリア -->
    <div id="scrollabel-content">

        <?php foreach ($happen_list as $happen): ?>
            <?php

            #支払者と支払先の名前を取得
            $payer_name = get_name($happen['PayID'],$happen['PayEMID'],$memberDAO,$eventMemberDAO);
            $payee_name = get_name($happen['SakiKID'],$happen['SakiEMID'],$memberDAO,$eventMemberDAO);

            #出来事詳細情報取得
            $details = $happenDetailDAO->get_happendetails($happen['HID']);

            #合計金額計算
            $total_amount = array_sum(array_column(details,'SMoney'));

            ?>

            <div class="payment-card">
            <h2><?= htmlspecialchars($payer_name) ?> ➔ <?= htmlspecialchars($payee_name) ?> <span class="payment-amount">￥<?= number_format($total_amount) ?></span></h2>
                <?php foreach ($details as $detail): ?>
                    <div class="event-item">
                        <div class="event-name"><?= htmlspecialchars($detail['HappenName']) ?></div>
                        <div class="event-amount">￥<?= number_format($detail['SMoney']) ?></div>
                    </div>

                    <div class="event-date"><?= htmlspecialchars($detail['Happendate']) ?></div>
            
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </div>

        <!-- 固定されたPayPayリンクボタン -->
        <div id="link-container">
            <!-- 左寄せのPayPayリンク -->
            <a id="paypay-link" href="https://paypay.ne.jp/" target="_blank">
                <img src="https://image.paypay.ne.jp/page/notice-merchant/entry/20181016/159/img_logo_1.jpg" alt="PayPay">
            </a>
            <!-- 右寄せの戻るリンク -->
            <a id="return-link" href="割り勘総額.php">戻る</a>
        </div>

    </div>

</body>
</html>
