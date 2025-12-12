<?php

if (!function_exists('asset')) {
    function asset(string $relativePath): string
    {
        $fullPath = BASE_PATH . '\public\\' . ltrim($relativePath, '/');
        $version = file_exists($fullPath) ? filemtime($fullPath) : time();
        return BASE_URL . '/' . ltrim($relativePath, '/') . '?v=' . $version;
    }
}

if (!function_exists('redirect')) {
    function redirect(string $url, bool $superAdmin = false): void
    {
        header('Location: ' . BASE_URL . $url);
        exit;
    }
}

if (!function_exists('old')) {
    function old(string $key, string $default = '')
    {
        return $_SESSION['_old'][$key] ?? $default;
    }
}

if (!function_exists('save_old_input')) {
    function save_old_input(): void
    {
        $_SESSION['_old'] = $_POST;
    }
}

if (!function_exists('clear_old_input')) {
    function clear_old_input(): void
    {
        unset($_SESSION['_old']);
    }
}


if (!function_exists('vd')) {
    function vd($input = null, bool $jsonPretty = false): void
    {
        $convert = function ($item) use (&$convert) {
            if (is_array($item)) {
                return array_map($convert, $item);
            }
            if (is_object($item)) {
                if (method_exists($item, 'toArray')) {
                    return $item->toArray();
                }
                return (array)$item;
            }
            return $item;
        };

        $data = $convert($input);

        echo '<pre>';
        if ($jsonPretty) {
            echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        } else {
            var_dump($data);
        }
        echo '</pre>';
        die();
    }
}
if (!function_exists('getUrl')) {
    function getUrl(): string
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $scriptName = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
        if (str_ends_with($scriptName, '/public')) {
            $scriptName = substr($scriptName, 0, -7);
        }
        if ($scriptName !== '/' && str_starts_with($uri, $scriptName)) {
            $uri = substr($uri, strlen($scriptName));
        }

        $uri = rtrim($uri, '/');
        if ($uri === '') {
            $uri = '/';
        }
        return $uri;
    }
}
if (!function_exists('rotateArray')) {
    function rotateArray(array $arr, int $shift = 1): array
    {
        $count = count($arr);
        if ($count === 0) return $arr;
        $shift = $shift % $count;
        if ($shift > 0) {
            return array_merge(array_slice($arr, $shift), array_slice($arr, 0, $shift));
        } elseif ($shift < 0) {
            $shift = abs($shift);
            return array_merge(array_slice($arr, -$shift), array_slice($arr, 0, $count - $shift));
        }
        return $arr;
    }
}
function json_response($data, $status = 200)
{
    http_response_code($status);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

function weekDay($lang = 'fa')
{
    if ($lang === 'en') {
        return [
            "1" => "Saturday",
            "2" => "Sunday",
            "3" => "Monday",
            "4" => "Tuesday",
            "5" => "Wednesday",
            "6" => "Thursday",
            "7" => "Friday",
        ];
    } else {
        return [
            "1" => "شنبه",
            "2" => "یک شنبه",
            "3" => "دو شنبه",
            "4" => "سه شنبه",
            "5" => "چهارشنبه",
            "6" => "پنج شنبه",
            "7" => "جمعه",
        ];
    }
}

function renderPagination($totalPages, $currentPage, $perPage, $search = '', $sortBy = '', $sortOrder = '', $filter = '', $lang = "fa"): string
{
    $final = '<ul class="pagination">';
    $pages = [];
    $pages[] = 1;
    if ($currentPage == 1) {
        $start = 2;
        $end = min(3, $totalPages - 1);
    } elseif ($currentPage == 2) {
        $start = 2;
        $end = min(3, $totalPages - 1);
    } elseif ($currentPage == $totalPages) {
        $start = max(2, $totalPages - 2);
        $end = $totalPages - 1;
    } else {
        $start = max(2, $currentPage - 1);
        $end = min($totalPages - 1, $currentPage + 1);
    }
    if ($start > 2) $pages[] = '...';
    for ($i = $start; $i <= $end; $i++) $pages[] = $i;
    if ($end < $totalPages - 1) $pages[] = '...';
    if ($totalPages > 1) $pages[] = $totalPages;
    $prevPage = max(1, $currentPage - 1);
    $nextPage = min($totalPages, $currentPage + 1);
    $baseParams = [
        'per_page' => $perPage,
    ];
    $prevLink = "?page=$prevPage&per_page=$perPage";
    if (!empty($search)) $prevLink .= "&search=" . urlencode($search);
    if (!empty($sortBy)) $prevLink .= "&sortby=" . urlencode($sortBy) . "&sortorder=$sortOrder";
    if (!empty($filter)) $prevLink .= "&filter=$filter";
    $iconPre = ($lang == 'fa') ? 'fa-chevron-right' : 'fa-chevron-left';
    $iconNext = ($lang == 'fa') ? 'fa-chevron-left' : 'fa-chevron-right';
    if ($currentPage == 1) {
        $final .= "<li><a class='disable' href='javascript:void(0)'><i class='fa-solid $iconPre'></i></a></li>";
    } else {
        $final .= "<li><a class='enable' href='$prevLink'><i class='fa-solid $iconPre'></i></a></li>";
    }
    foreach ($pages as $p) {
        if ($p === '...') {
            $final .= "<li class='dots'>...</li>";
        } else if ($p == $currentPage) {
            $final .= "<li class='active-page'><a href='javascript:void(0)'>$p</a></li>";
        } else {
            $active = ($p == $currentPage) ? 'active-page' : '';
            $link = "?page=$p&per_page=$perPage";
            if (!empty($search)) $link .= "&search=" . urlencode($search);
            if (!empty($sortBy)) $link .= "&sortby=" . urlencode($sortBy) . "&sortorder=$sortOrder";
            if (!empty($filter)) $link .= "&filter=$filter";
            $final .= "<li class='$active'><a href='$link'>$p</a></li>";
        }
    }
    $nextLink = "?page=$nextPage&per_page=$perPage";
    if (!empty($search)) $nextLink .= "&search=" . urlencode($search);
    if (!empty($sortBy)) $nextLink .= "&sortby=" . urlencode($sortBy) . "&sortorder=$sortOrder";
    if (!empty($filter)) $nextLink .= "&filter=$filter";
    if ($currentPage == $totalPages) {
        $final .= "<li><a class='disable' href='javascript:void(0)'><i class='fa-solid $iconNext'></i></a></li>";
    } else {
        $final .= "<li><a class='enable' href='$nextLink'><i class='fa-solid $iconNext'></i></a></li>";
    }
    $final .= '</ul>';
    return $final;
}

function toJalali($datetime, $separator = "/")
{
    if (
        empty($datetime) ||
        $datetime === "0000-00-00 00:00:00" ||
        $datetime === "0000-00-00" ||
        $datetime === "00:00:00"
    ) {
        return [
            'date' => '',
            'time' => ''
        ];
    }
    if (!$datetime instanceof DateTime) {
        $datetime = new DateTime($datetime);
    }
    $gregorianDate = $datetime->format('Y-m-d');
    $time = $datetime->format('H:i');
    list($gy, $gm, $gd) = explode('-', $gregorianDate);
    $jalaliDateArray = gregorian_to_jalali($gy, $gm, $gd);
    $year = str_pad($jalaliDateArray[0], 4, '0', STR_PAD_LEFT);
    $month = str_pad($jalaliDateArray[1], 2, '0', STR_PAD_LEFT);
    $day = str_pad($jalaliDateArray[2], 2, '0', STR_PAD_LEFT);
    $jalaliDate = "{$year}$separator{$month}$separator{$day}";
    return [
        'date' => $jalaliDate,
        'time' => $time
    ];
}

function convertToEnglish($text)
{
    $persianNumbers = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
    $arabicNumbers = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
    $englishNumbers = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

    $result = $text;
    $result = str_replace($persianNumbers, $englishNumbers, $result);
    $result = str_replace($arabicNumbers, $englishNumbers, $result);

    return $result;
}

enum Type
{
    case ERROR;
    case SUCCESS;
}

function showToast(Type $type = Type::SUCCESS, string $text = ''): string
{
    $bg = $type === Type::SUCCESS ? 'text-bg-success' : 'text-bg-danger';

    return "
        <div class='toast-container position-fixed top-0 end-0 p-3'>
            <div id='showToast' class='toast align-items-center $bg border-0' role='alert' aria-live='assertive' aria-atomic='true'>
                <div class='d-flex'>
                    <div class='toast-body'>$text</div>
                    <button type='button' class='btn-close btn-close-white me-2 m-auto' data-bs-dismiss='toast'></button>
                </div>
            </div>
        </div>
    ";
}

function getMiladiBirthDate(string $birthDate): string
{
    $date = convertToEnglish($birthDate);
    $newDate = preg_split('/[-\/]/', $date);
    $year = $newDate[0];
    $month = $newDate[1];
    $day = $newDate[2];
    $dateArray = jalali_to_gregorian($year, $month, $day);
    $miladiBirthDate = sprintf('%04d-%02d-%02d', $dateArray[0], $dateArray[1], $dateArray[2]);
    return $miladiBirthDate;
}