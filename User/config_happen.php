<?php
require_once 'HappenDAO.php';
require_once 'HappenDetailDAO.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // POSTデータの取得
    $payer = $_POST['payer'];
    $EventID = $_POST['eventID'];
    $HappenName = $_POST['happenName'];
    var_dump($EventID);
    
    // 金額を数値に変換
    $TotalMoney = $_POST['totalMoney'];
    if (is_numeric($TotalMoney)) {
        $TotalMoney = intval($TotalMoney);  // 明示的に整数型に変換
    } else {
        echo "金額は数値でなければなりません。";
        exit;
    }

    $HappenDate = $_POST['happenDate'];
    $members = $_POST['members'];

    // 支払者を解析
    $PayID = '';  // 空文字で初期化
    $PayEMID = null;
    if (preg_match('/^EM(\d+)$/', $payer, $matches)) {
        $PayEMID = $matches[1];
    } else {
        $PayEMID = '';  // 空文字で設定（または適切なユーザーIDを設定）
    }

    // もし $PayEMID が null のままであれば、支払ったのは会員なのでID（M000??）を代入
    if ($PayEMID === '') {
        $PayID = $payer;  // PayID（支払った会員）にpayerをそのままいれる
    }

    // 日付の形式が正しいか確認
    $HappenDate = DateTime::createFromFormat('Y-m-d', $HappenDate);
    if (!$HappenDate) {
        echo "日付の形式が正しくありません。";
        exit;
    }

    // イベントIDが正しい形式かどうかの確認
    if (!preg_match('/^E\d+$/', $EventID)) {
        echo "イベントIDは'E'に続いて数字である必要があります。";
        var_dump($_GET);
        exit;
    }

    // HappenDaoをインスタンス化
    $HappenDao = new HappenDao();
    // データベースに出来事を追加
    $newHappenID = $HappenDao->add_happen(
        $PayID,  // 空文字または適切なID
        $EventID,  // 数値形式のイベントID
        $PayEMID,
        $HappenName,
        $TotalMoney,  // 修正後の整数型の金額
        $HappenDate->format('Y-m-d H:i:s')  // DateTimeオブジェクトを文字列に変換
    );

    // 出来事にメンバーを関連付ける
    foreach ($members as $member) {
        $HappenDao->add_happen_member($newHappenID, $member);
    }

    // リダイレクト
    header('Location: 出来事の閲覧と選択.php');
    exit;
}
?>
