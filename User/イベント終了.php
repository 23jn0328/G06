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
                <span>はやと → ひかる</span>
                <span class="amount">5000円</span>
                <input type="checkbox" class="checkbox">
            </div>
            <div class="transaction">
                <span>れおん → じゅうと</span>
                <span class="amount">5000円</span>
                <input type="checkbox" class="checkbox">
            </div>
            <div class="transaction">
                <span>じゅうと → いくみ</span>
                <span class="amount">5000円</span>
                <input type="checkbox" class="checkbox">
            </div>
            <div class="transaction">
                <span>はやと → れおん</span>
                <span class="amount">5000円</span>
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
