<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>割り勘精算画面</title>
    <link rel="stylesheet" href="イベント終了.css">
</head>
<style>
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f2f2f2;
    color: #333;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
}

.container {
    width: 95%;
    max-width: 500px;
    padding: 30px;
    background-color: #fff;
    border-radius: 15px;
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
}

h2 {
    text-align: center;
    margin-bottom: 30px;
    font-size: 22px;
    color: #444;
}

.transaction-list {
    margin-bottom: 30px;
}

.transaction {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px 0;
    border-bottom: 2px solid #ddd;
}

.transaction:last-child {
    border-bottom: none;
}

.transaction span {
    font-size: 18px;
    flex: 2;
}

.transaction .amount {
    flex: 1;
    text-align: right;
    font-size: 20px;
    font-weight: bold;
    color: #4caf50;
}

.transaction input[type="checkbox"] {
    flex: 0;
    width: 25px;
    height: 25px;
    margin-left: 15px;
    cursor: pointer;
}

.end-button {
    width: 100%;
    padding: 20px;
    font-size: 18px;
    color: #fff;
    background-color: #aaa;
    border: none;
    border-radius: 10px;
    cursor: not-allowed;
    transition: background-color 0.3s;
}

.end-button.enabled {
    background-color: #4caf50;
    cursor: pointer;
}

.end-button.enabled:hover {
    background-color: #45a049;
}
</style>
<body>
    <div class="container">
        <h2>割り勘精算</h2>
        <div class="transaction-list">
            <div class="transaction">
                <span>ひかる → はやと</span>
                <span class="amount">2000円</span>
                <input type="checkbox" class="checkbox">
            </div>
            <div class="transaction">
                <span>はやと → ひかる</span>
                <span class="amount">1000円</span>
                <input type="checkbox" class="checkbox">
            </div>
            <div class="transaction">
                <span>しゅうと → はやと</span>
                <span class="amount">2000円</span>
                <input type="checkbox" class="checkbox">
            </div>
            <div class="transaction">
                <span>れおん → はやと</span>
                <span class="amount">2000円</span>
                <input type="checkbox" class="checkbox">
            </div>
            <div class="transaction">
                <span>れおん → ひかる</span>
                <span class="amount">1000円</span>
                <input type="checkbox" class="checkbox">
            </div>
            <div class="transaction">
                <span>いくみ → はやと</span>
                <span class="amount">2000円</span>
                <input type="checkbox" class="checkbox">
            </div>
            <div class="transaction">
                <span>いくみ → ひかる</span>
                <span class="amount">1000円</span>
                <input type="checkbox" class="checkbox">
            </div>
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
            window.location.href = '完了マーク付きイベントの閲覧と選択.php';
        });
    </script>
</body>
</html>