<?php

if (!class_exists('Easy_Bots')) {

class Easy_Bots {

    const LOG_DIR = 'logs/';
    const LOG_FILE = 'bot-log.txt';

    public static function init() {
        add_action('init', [__CLASS__, 'detect_and_log_bot']);
    }

    // Détection du bot et journalisation.

    public static function detect_and_log_bot() {
    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
    $user_agent = trim($user_agent);

    if (!$user_agent) return;

    $log_file = self::get_log_file_path();

    // Créer le dossier logs si nécessaire
    $log_dir = dirname($log_file);
    if (!file_exists($log_dir)) {
        mkdir($log_dir, 0755, true);
    }

    $bot_list = self::get_bot_list();
    $blocked_bots = get_option('easy_bots_blocked_list', []);



    foreach ($bot_list as $bot) {
        if (stripos($user_agent, $bot) !== false) {


            $blocked_bots_lower = array_map('strtolower', $blocked_bots);
            if (in_array(strtolower($bot), $blocked_bots_lower)) {


                $entry = sprintf(
                    "%s | [BLOCKED] %s | IP: %s | UA: %s\n",
                    date('Y-m-d H:i:s'),
                    $bot,
                    $_SERVER['REMOTE_ADDR'],
                    $user_agent
                );

                $fp = fopen($log_file, 'a');
                if ($fp) {
                    fwrite($fp, $entry);
                    fflush($fp);
                    fclose($fp);
                }

                wp_die(__('Accès bloqué pour les robots.', 'easy-bots'), 403);
            }

            // Bot détecté mais pas bloqué
            $entry = sprintf(
                "%s | %s | IP: %s | UA: %s\n",
                date('Y-m-d H:i:s'),
                $bot,
                $_SERVER['REMOTE_ADDR'],
                $user_agent
            );
            file_put_contents($log_file, $entry, FILE_APPEND);
            break;
        }
    }
}



    /**
     * Retourne le contenu complet du fichier de log.
     * Renvoie un texte prêt à afficher dans un textarea.
     */
    public static function display_log() {
    $log_file = self::get_log_file_path();

    if (!file_exists($log_file)) {
        return 'Aucune activité enregistrée.';
    }

    $rules = get_option('easy_bots_blocked_list', []);
    $rules = array_map('strtolower', $rules);


    $log_content = file_get_contents($log_file);
    $lines = explode("\n", $log_content);
    $lines = array_filter($lines);
    $lines = array_reverse($lines);

    $parsed_log = array_map(function($line) use ($rules) {
        $line = trim($line);
        if (empty($line)) return null;

        // Éviter de rajouter plusieurs fois | BLOCKED
        if (stripos($line, '| BLOCKED') !== false) {
            return $line;
        }

        foreach ($rules as $rule) {
            if (stripos($line, $rule) !== false) {
                return $line . ' | BLOCKED';
            }
        }

        return $line;
    }, $lines);

    $parsed_log = array_filter($parsed_log);
    return implode("\n", $parsed_log);
}

    // Retourne le chemin complet vers le fichier de log.

    private static function get_log_file_path() {
        return plugin_dir_path(__DIR__) . self::LOG_DIR . self::LOG_FILE;
    }


    //Retourne la liste des bots connus.
    // À adapter ou remplacer selon les besoins.

    public static function get_bot_list() {
        return [
            'googlebot',
            'bingbot',
            'ahrefsbot',
            'discordbot',
            'python-requests',
            // Ajout des bots à détecter
        ];
    }

}

}

// Initialisation
add_action('plugins_loaded', ['Easy_Bots', 'init']);

// Sauvegarde des options admin
add_action('admin_post_easy_bots_save_settings', 'easy_bots_save_settings');
function easy_bots_save_settings() {



    if (!current_user_can('manage_options')) {

        wp_die('Non autorisé');
    }

    if (!isset($_POST['easy_bots_nonce']) || !wp_verify_nonce($_POST['easy_bots_nonce'], 'easy_bots_save_action')) {

        wp_die('Nonce invalide');
    }

        if (!wp_verify_nonce($_POST['easy_bots_nonce'], 'easy_bots_save_action')) {

        wp_die('Nonce invalide');
    }



    if (isset($_POST['blocked_bots'])) {

    } else {

    }

    $blocked_bots = isset($_POST['blocked_bots']) ? array_map('sanitize_text_field', $_POST['blocked_bots']) : [];


    update_option('easy_bots_blocked_list', $blocked_bots);



    wp_redirect(admin_url('admin.php?page=easy-bots&saved=1'));
    exit;
}

add_action('init', function () {
    if (easy_bots_is_blocked()) {

        wp_die('Accès refusé aux robots');
    }
});

add_action('admin_post_easy_bots_clear_logs', 'easy_bots_clear_logs_handler');

function easy_bots_clear_logs_handler() {
    // Vérifie capacité admin
    if (!current_user_can('manage_options')) {
        wp_die('Non autorisé');
    }

    // Vérifie nonce
    if (!isset($_POST['easy_bots_clear_logs_nonce']) || !wp_verify_nonce($_POST['easy_bots_clear_logs_nonce'], 'easy_bots_clear_logs_action')) {
        wp_die('Nonce invalide');
    }

    // Chemin vers le fichier log
    $log_file = plugin_dir_path(__DIR__) . 'logs/bot-log.txt';

    if (file_exists($log_file)) {
        // Vide le contenu du fichier
        file_put_contents($log_file, '');
    }

    // Redirige vers la page admin avec un paramètre de succès
    wp_redirect(admin_url('admin.php?page=easy-bots&cleared=1'));
    exit;
}

