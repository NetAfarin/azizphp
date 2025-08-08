<?php

namespace App\Middlewares;

use App\Exceptions\RateLimitExceededException;
use Redis;

class RateLimiterMiddleware
{
    protected int $maxRequests = 10;
    protected int $decaySeconds = 30;
    protected Redis $redis;

    public function __construct()
    {
        $this->redis = new Redis();
        $this->redis->connect('127.0.0.1', 6379);
    }

    public function handle($request, $next)
    {
        if (isset($_SESSION['user_id'])) {
            $key = 'rate_limit:user:' . $_SESSION['user_id'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
            $key = 'rate_limit:ip:' . $ip;
        }

        if (!$this->allowRequest($key)) {
            return $this->tooManyRequestsResponse($key);
        }

        return $next($request);
    }

    protected function getTokenFromHeader(): ?string
    {
        $headers = getallheaders();
        if (!isset($headers['Authorization'])) return null;

        $auth = $headers['Authorization'];
        if (str_starts_with($auth, 'Bearer ')) {
            return substr($auth, 7);
        }
        return null;
    }

    protected function allowRequest(string $key): bool
    {
        $count = $this->redis->get($key);

        if ($count === false) {
            $this->redis->set($key, 1, $this->decaySeconds); // expire after decaySeconds
            return true;
        }

        if ($count >= $this->maxRequests) {
            return false;
        }

        $this->redis->incr($key);
        return true;
    }

    protected function tooManyRequestsResponse(string $key)
    {
        $ttl = $this->redis->ttl($key);

        if ($ttl < 0) {
            $ttl = 0;
        }
        http_response_code(429);
        header("Retry-After: $ttl");
        $lang = $_SESSION['lang'];
        $isApiRequest = isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false;

        if ($isApiRequest) {
            header('Content-Type: application/json');
            echo json_encode([
                'error' => __('redis_heading'),
                'retry_after_seconds' => __('redis_retry', ['rtime' => $ttl]),
                'message' => __('redis_message')
            ]);
        } else {
            header('Content-Type: text/html; charset=utf-8');

            $t = [
                'title' => __('redis_title'),
                'heading' => __('redis_heading'),
                'message' => __('redis_message'),
                'retry' => __('redis_retry', ['rtime' => $ttl]),
            ];
            $dir = ($lang === 'fa') ? 'rtl' : 'ltr';
            $textAlign = $dir === 'rtl' ? 'right' : 'left';
            echo <<<HTML
            <!DOCTYPE html>
            <html lang="$lang" dir="$dir">
            <head>
                <meta charset="UTF-8" />
                <title>{$t['title']}</title>
                <style> body { font-family: Tahoma, sans-serif; background-color: #f8d7da; color: #721c24; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; direction: $dir; text-align: $textAlign; } .container { background-color: #f5c6cb; border: 1px solid #f1b0b7; padding: 30px 40px; border-radius: 8px; text-align: center; max-width: 400px; box-shadow: 0 0 10px rgba(114, 28, 36, 0.3); } h1 { margin-bottom: 20px; } p { font-size: 1.1rem; margin: 10px 0; } .retry { font-weight: bold; font-size: 1.3rem; color: #491217; } </style>
            </head>
            <body>
             <div class="container"> <h1>{$t['heading']}</h1> <p>{$t['message']}</p> <p class="retry">{$t['retry']}</p> </div> 
             </body> </html>
HTML;
        }
        exit;
    }

    protected function unauthorizedResponse()
    {
        http_response_code(401); // Unauthorized
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Authorization token required']);
        exit;
    }
}


