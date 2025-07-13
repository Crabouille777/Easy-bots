<?php
/*
 * Plugin Name: Easy-bots
 * Description: Gestionnaire des visites de bots incluant un journal d'activités, un blocage et des statistiques temporelles.
 * Version: 1.0
 * Author: Crabouille777
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: easy-bots
 *
 * Note de l'auteur :
 * Ce plugin est distribué gratuitement dans un esprit de partage.
 * Merci de ne pas le vendre ou monétiser sous une forme quelconque.
 */

defined('ABSPATH') or die('No script kiddies please!');

// constantes
define('EASY_BOTS_PATH', plugin_dir_path(__FILE__));
define('EASY_BOTS_URL', plugin_dir_url(__FILE__));

// fichiers
require_once EASY_BOTS_PATH . 'includes/bot-list.php';
require_once EASY_BOTS_PATH . 'includes/functions.php';
require_once EASY_BOTS_PATH . 'includes/class-easy-bots.php';
require_once EASY_BOTS_PATH . 'includes/admin-page.php';

// Initialisation du plugin
add_action('init', ['Easy_Bots', 'init']);

// Ajouter lien "Paramètres" sur la page des plugins
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'easy_bots_plugin_action_links');
function easy_bots_plugin_action_links($links) {
    $settings_link = '<a href="admin.php?page=easy-bots">Paramètres</a>';
    array_unshift($links, $settings_link);
    return $links;
}

