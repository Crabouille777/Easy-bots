<?php


// Inclure la liste complète des bots
include_once EASY_BOTS_PATH . 'includes/bot-list.php'; 

global $easy_bots_signatures;
$easy_bots_signatures = easy_bots_full_list();


function easy_bots_is_bot($user_agent = null) {
    
    global $easy_bots_signatures;
    if (!$user_agent) {
        $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
    }

    $user_agent = strtolower($user_agent);
    $matches = [];

    foreach ($easy_bots_signatures as $keyword) {
        if (strpos($user_agent, $keyword) !== false) {
            $matches[] = $keyword;
        }
    }

    // Si plusieurs correspondances, retourne la plus spécifique (plus longue)
    if (!empty($matches)) {
        usort($matches, function($a, $b) {
            return strlen($b) - strlen($a);
        });
        return [
            'bot_name' => $matches[0],
            'matches'  => $matches
        ];
    }

    return false;
}

function easy_bots_detect_and_log_visit() {
    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
    $result = easy_bots_is_bot($user_agent);

    if ($result) {
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        easy_bots_log_bot($result['bot_name'], $user_agent, $ip, $result['matches']);
    }
}

add_action('init', 'easy_bots_detect_and_log_visit');

function easy_bots_is_blocked($user_agent = '') {
    if (empty($user_agent)) {
        $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
    }

    $blocked_bots = get_option('easy_bots_blocked_list', []);
    $user_agent = strtolower($user_agent);

    foreach ($blocked_bots as $bot) {
        if (stripos($user_agent, $bot) !== false) {

            return true;
        }
    }

    return false;
}


function easy_bots_log_bot($bot_name, $user_agent, $ip) {

    $log_entry = date('Y-m-d H:i:s') . " | $bot_name | $ip | $user_agent" . PHP_EOL;
    $log_file = EASY_BOTS_PATH . 'logs/bot-log.txt';

    if (!file_exists(dirname($log_file))) {
        mkdir(dirname($log_file), 0755, true);
    }

    file_put_contents($log_file, $log_entry, FILE_APPEND);
}

function easy_bots_get_list() {
    include_once EASY_BOTS_PATH . 'includes/bot-list.php';
    return easy_bots_full_list();
}
function easy_bots_parse_log() {
    $log_file = EASY_BOTS_PATH . 'logs/bot-log.txt';
    $data = [];

    if (!file_exists($log_file)) {
        return $data;
    }

    $lines = file($log_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Format attendu : YYYY-MM-DD HH:MM:SS | bot_name | IP | user-agent
        if (preg_match('/^(\d{4}-\d{2}-\d{2})/', $line, $matches)) {
            $date = $matches[1];
            if (!isset($data[$date])) {
                $data[$date] = 0;
            }
            $data[$date]++;
        }
    }

    ksort($data);
    return $data;
}

function easy_bots_parse_log_daily() {
    $log_file = EASY_BOTS_PATH . 'logs/bot-log.txt';
    $data = [];

    if (!file_exists($log_file)) {
        return $data;
    }

    $lines = file($log_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Format attendu : YYYY-MM-DD HH:MM:SS | bot_name | IP | user-agent
        if (preg_match('/^\d{4}-\d{2}-\d{2} (\d{2}):\d{2}:\d{2} \|/', $line, $matches)) {
            $hour = $matches[1] . ':00';
            if (!isset($data[$hour])) {
                $data[$hour] = 0;
            }
            $data[$hour]++;
        }
    }

    // heures manquantes pour avoir toutes les 24 heures
    for ($h = 0; $h < 24; $h++) {
        $key = sprintf('%02d:00', $h);
        if (!isset($data[$key])) {
            $data[$key] = 0;
        }
    }

    ksort($data);
    return $data;
}

function easy_bots_parse_log_weekly() {
    $log_file = EASY_BOTS_PATH . 'logs/bot-log.txt';
    $data = [
        'Lun' => 0,
        'Mar' => 0,
        'Mer' => 0,
        'Jeu' => 0,
        'Ven' => 0,
        'Sam' => 0,
        'Dim' => 0,
    ];

    if (!file_exists($log_file)) {
        return $data;
    }

    $lines = file($log_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (preg_match('/^(\d{4}-\d{2}-\d{2})/', $line, $matches)) {
            $date = $matches[1];
            $dt = new DateTime($date);

            // Récupérer le jour de la semaine en numéro (1 = lundi, 7 = dimanche)
            $day_num = (int)$dt->format('N');

            // Tableau des noms des jours en français abrégé
            $jours = ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'];

            $jour = $jours[$day_num - 1];

            $data[$jour]++;
        }
    }

    return $data;
}


function easy_bots_parse_log_monthly() {
    $log_file = EASY_BOTS_PATH . 'logs/bot-log.txt';

    $data = [
        'Janv' => 0,
        'Févr' => 0,
        'Mars' => 0,
        'Avr'  => 0,
        'Mai'  => 0,
        'Juin' => 0,
        'Juil' => 0,
        'Août' => 0,
        'Sept' => 0,
        'Oct'  => 0,
        'Nov'  => 0,
        'Déc'  => 0,
    ];

    if (!file_exists($log_file)) {
        return $data;
    }

    $lines = file($log_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        if (preg_match('/^(\d{4})-(\d{2})-(\d{2})/', $line, $matches)) {
            $month_num = (int)$matches[2]; // Mois en nombre 1..12

            $months = array_keys($data); // Les clés du tableau data (les abréviations)

            // Attention : $month_num est de 1 à 12, les indices PHP commencent à 0 donc $month_num-1
            $month_key = $months[$month_num - 1];

            $data[$month_key]++;
        }
    }

    return $data;
}

