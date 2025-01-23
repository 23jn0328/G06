<?php
session_start();
require_once 'HappenDAO.php';
require_once 'HappenDetailDAO.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $happenID = $_POST['happenID'];
    $happenName = $_POST['happenName'];
    $happenDate = $_POST['happenDate'];
    $payEMID = $_POST['payEMID'];
    $totalMoney = intval($_POST['totalMoney']);

    $happenDao = new HappenDao();
    $result = $happenDao->update_happen($happenID, $payEMID, $happenName, $totalMoney, $happenDate);

    if ($result) {
        echo "更新に成功しました。";
    } else {
        echo "更新に失敗しました。";
    }
}
?>
