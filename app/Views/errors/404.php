<?php
$lang = APP_LANG;
$dir = APP_DIRECTION;
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>" dir="<?= $dir ?>">
<head>
    <meta charset="UTF-8">
    <title>صفحه پیدا نشد</title>
    <link href="<?= asset($dir === 'rtl' ? "css/bootstrap5.3.8.rtl.min.css" : "css/bootstrap5.3.8.min.css"); ?>"
          rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            text-align: center;
        }

        .countdown {
            font-size: 24px;
            font-weight: bold;
            color: #dc3545;
            margin-top: 20px;
        }

        .error-code {
            font-size: 12rem;
            font-weight: 900;
            background: linear-gradient(to right, #dc3545, #DC354550);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
            100% {
                transform: scale(1);
            }
        }

    </style>
    <script>
        //var countdown = 9;
        //var redirectText = "<?php //= __('redirecting'); ?>//";
        //
        //function updateCountdown() {
        //    document.getElementById("timer").textContent = redirectText + " (" + countdown + ") ";
        //    countdown--;
        //    if (countdown < 0) {
        //        window.location.href = "<?php //= BASE_URL  ?>///user/login" + "?lang=<?php //=  APP_LANG ?>//";
        //    }
        //}
        //
        //setInterval(updateCountdown, 1000);
    </script>
</head>
<body>

<div class="error-page">
    <div class="error-container p-4">
        <h1 class="error-code mb-0">404</h1>
        <h2 class="display-6 error-message mb-3"><?= __('page_not_found'); ?></h2>
        <p class="lead error-message mb-4"><?= __('link_changed'); ?></p>
        <p class="error-message mb-4"><?= __('redirecting_message'); ?></p>
        <p id="timer" class="countdown"></p>
        <div class="d-flex justify-content-center gap-3">
            <a href="<?= BASE_URL ?>/" class="btn btn-outline-dark"><?= __('home') ?></a>
        </div>
    </div>
</div>
</body>
</html>
