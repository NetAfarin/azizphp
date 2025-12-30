<?php
require_once BASE_PATH . '/app/Helpers/lang.php';
require_once BASE_PATH . '/app/Helpers/flash.php';
require_once BASE_PATH . '/app/Helpers/functions.php';
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>خطا در اتصال به پایگاه داده</title>

   <link href="<?= asset('css/style.css')?>" rel="stylesheet" />
    <link href="<?= asset('css/theme.css')?>" rel="stylesheet" />
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #E9F3F9;
            min-height: 100vh;
            display: flex;
            align-items: center;
        }

        .error-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem;
            animation: fadeIn 0.8s ease-out;
        }

        .error-card {
            background-color: #fff;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            border: none;
        }

        .error-header {
            background: var(--bs-primary);
            color: white;
            padding: 2rem 2rem 1.5rem;
            text-align: center;
        }

        .error-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
            display: inline-block;
        }

        .error-body {
            padding: 2.5rem;
            color: #333;
        }

        .error-title {
            font-weight: 700;
            color: #ff416c;
            margin-bottom: 1rem;
        }

        .error-details {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 1.5rem;
            margin-top: 1.5rem;
            border-right: 5px solid #ff4b2b;
        }

        .error-details h6 {
            color: #495057;
            font-weight: 600;
        }

        .btn-retry {
            background: #e83e8c;
            color: white;
            padding: 0.75rem 2rem;
            font-weight: 600;
            border: none;
            border-radius: 50px;
            transition: all 0.3s ease;
        }

        .btn-retry:hover {
            transform: translateY(-3px);
            color: white;
        }

        .btn-contact {
            background-color: #f8f9fa;
            color: #495057;
            padding: 0.75rem 2rem;
            font-weight: 600;
            border: 2px solid #dee2e6;
            border-radius: 50px;
            transition: all 0.3s ease;
        }

        .btn-contact:hover {
            background-color: #e9ecef;
            border-color: #adb5bd;
        }

        .contact-info {
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid #eee;
        }

        .contact-item {
            display: flex;
            align-items: center;
            margin-bottom: 0.75rem;
        }

        .contact-icon {
            width: 40px;
            height: 40px;
            background-color: #f8f9fa;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: 1rem;
            color: #ff416c;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .pulse {
            animation: pulse 2s infinite;
        }

        /* حالت تاریک */
        @media (prefers-color-scheme: dark) {
            body {
              background-color: #E9F3F9;
             }

            .error-card {
                background-color: #2d3748;
                color: #e2e8f0;
            }

            .error-body {
                color: #e2e8f0;
            }

            .error-details {
                background-color: #4a5568;
                color: #e2e8f0;
            }

            .error-details h6 {
                color: #cbd5e0;
            }

            .btn-contact {
                background-color: #4a5568;
                color: #e2e8f0;
                border-color: #718096;
            }

            .btn-contact:hover {
                background-color: #718096;
            }

            .contact-icon {
                background-color: #4a5568;
                color: #ff7b9c;
            }
        }
    </style>
</head>
<body>
<div class="container error-container">
    <div class="error-card">
        <div class="error-header">
            <div class="error-icon pulse">
                <i class="bi bi-database-exclamation"></i>
            </div>
            <h1>خطا در اتصال به پایگاه داده</h1>
        </div>

        <div class="error-body">
            <h2 class="error-title">اتصال به پایگاه داده برقرار نیست!</h2>
            <p class="lead">
                متأسفانه سرور در حال حاضر نمی‌تواند به پایگاه داده متصل شود. این مشکل ممکن است موقت باشد و به زودی برطرف شود.
            </p>


            <div class="row space-between mt-4">
                <div class="col-md-6 mb-3 mb-md-0">
                    <button class="btn btn-retry w-100" id="retryBtn">
                        <i class="bi bi-arrow-clockwise me-2"></i> تلاش مجدد
                    </button>
                </div>
                <div class="col-md-6">
                    <button class="btn btn-contact w-100" id="contactBtn">
                        <i class="bi bi-telephone-outbound me-2"></i> تماس با پشتیبانی
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function() {
        $('#retryBtn').on('click', function() {
            location.reload();
        });

        $('#contactBtn').on('click', function() {
            const $contactBtn = $(this);

            $contactBtn.html('<i class="bi bi-check-circle me-2"></i> درخواست ثبت شد');
            $contactBtn.prop('disabled', true);
            $contactBtn.removeClass('btn-contact');
            $contactBtn.addClass('btn-success');

            showAlert('درخواست شما برای تماس پشتیبانی ثبت شد. همکاران ما در اولین فرصت با شما تماس خواهند گرفت.', 'success');
            setTimeout(function() {
                $contactBtn.html('<i class="bi bi-telephone-outbound me-2"></i> تماس با پشتیبانی');
                $contactBtn.prop('disabled', false);
                $contactBtn.removeClass('btn-success');
                $contactBtn.addClass('btn-contact');
            }, 5000);
        });
        function showAlert(message, type) {
            const alertDiv = $('<div>', {
                class: `alert alert-${type} alert-dismissible fade show position-fixed`,
                css: {
                    top: '20px',
                    left: '50%',
                    transform: 'translateX(-50%)',
                    zIndex: '9999',
                    minWidth: '300px',
                    textAlign: 'center'
                },
                role: 'alert',
                html: `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `
            });

            $('body').append(alertDiv);
            setTimeout(function() {
                alertDiv.remove();
            }, 5000);
        }

        const $errorIcon = $('.error-icon');
        setTimeout(() => {
            $errorIcon.removeClass('pulse');
            setTimeout(() => {
                $errorIcon.addClass('pulse');
            }, 100);
        }, 2000);
    });</script>
</body>
</html>