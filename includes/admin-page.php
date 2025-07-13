<?php
if (!defined('ABSPATH')) exit;

add_action('admin_menu', 'easy_bots_add_admin_menu');

add_action('admin_enqueue_scripts', 'easy_bots_enqueue_admin_scripts');
add_action('admin_enqueue_scripts', 'easy_bots_enqueue_admin_assets');

function easy_bots_enqueue_admin_scripts($hook) {

    if ($hook !== 'toplevel_page_easy-bots') {
        return;
    }

    wp_enqueue_script(
        'chartjs',
        'https://cdn.jsdelivr.net/npm/chart.js', 
        [],
        null,
        true
    );
    // test error_log bot-log.txt
$logs_by_category = [
    'Réseaux sociaux' => [
        
    ],
];
// Fin test error_log bot-log.txt

wp_register_script('easy-bots-admin', plugins_url('../assets/js/admin.js', __FILE__), ['jquery'], '1.0', true);
wp_localize_script('easy-bots-admin', 'logsByCategory', $logs_by_category);


    wp_enqueue_script(
        'easy-bots-charts',
        plugins_url('../assets/js/charts.js', __FILE__),
        ['chartjs'],
        '1.0',
        true
    );

    wp_enqueue_script(
    'easy-bots-admin',
    plugins_url('../assets/js/admin.js', __FILE__),
    ['jquery'],
    '1.0',
    true
);

}



function easy_bots_enqueue_admin_assets($hook) {

    if (strpos($hook, 'easy-bots') === false) {
        return;
    }

    wp_enqueue_style(
        'easy-bots-charts',
        plugins_url('../assets/css/charts.css', __FILE__),
        [],
        '1.0'
    );
}

function easy_bots_add_admin_menu() {
    add_menu_page(
        'Easy-bots',
        'Easy-bots',
        'manage_options',
        'easy-bots',
        'easy_bots_render_admin_page',
        'dashicons-shield-alt',
        80
    );
}


function easy_bots_get_categorized_list() {
    return [
        'Réseaux sociaux' => [
            'facebookexternalhit', 'facebot', 'twitterbot', 'linkedinbot', 'slackbot',
            'discordbot', 'telegrambot', 'skypeuripreview', 'whatsapp', 'viber',
            'pinterest', 'redditbot', 'tumblr', 'nuzzel', 'bitlybot', 'vkshare'
        ],
        'Grands moteurs de recherche' => [
            'googlebot', 'bingbot', 'yandex', 'baiduspider', 'duckduckbot',
            'sogou', 'exabot', 'ia_archiver', 'applebot', 'Sogou web spider',
            'seznambot', 'qwantify', 'mj12bot', 'youdaobot', 'bytespider', '360spider', 
            'haosouspider', 'sosospider', '360spider', 'haosouspider', 'sosospider', 'bingpreview',
            'adidxbot',
        ],
        'Sécurité, analyse, pentest' => [
            'zgrab', 'nmap', 'masscan', 'shodan', 'qualys', 'censys', 'wprecon', 'SecurityTrails', 'ZoomEye', 'Hunter', 'DirBuster',
            'wpscan', 'sitelockspider', 'acunetix', 'netsparker', 'censys.io crawler', 'leakix bot', 'Dirsearch', 'whatweb', 'Better Uptime',
            'Freshping', 'Hexometer', 'Pingometer', 'Nodeping', 'HetrixTools', 'CalibreApp', 'Catchpoint', 'BrowserStack', 'HeadlessChrome',
            'Playwright', 'puppeteer', 'ScreenerBot', 'SEOkicks-Robot', 'jaeles', 'arachni', 'metasploit', 'nessusagent', 'acunetix-standard',
            'netsparker-agent', 'sqlmap', 'hydra', 'dirbuster/', 'dirsearch/', 'zaproxy', 'burpcollaborator', 'commix', 'nikto scanner', 'arachni-scanner',
            'wapiti', 'crawler-joomscan','scrapy/', 'python-httplib', 'mechanize', 'requests', 'httpx', 'fetchbot', 'axios/', 'okhttp/',
        'got (node.js)', 'node-fetch',
        ],
        'Bots IA' => [
        'quora link preview', 'NeevaBot', 'QuivrBot', 'KomoSearch', 'komosearchbot', 'WriterAIBot', 'KagiBot', 'kagibot', 'youchatbot', 
        'BlackbirdBot', 'yeti bot', 'Yeti/1.0', 'metaphor-bot', 'metaphor', 'readwise', 'newsblur', 
        'meta-externalagent/1.1', 'FacebookBot/1.0', 'gptbot', 'chatgpt-user', 'claudebot', 'duckassistbot', 
        'perplexitybot', 'img2dataset', 'dingtalkbot', 'ChatGPT-User/1.0', 'chatgpt-openai', 
        'ChatGPT-User/2.0', 'openai-embed', 'openai-image', 'GigaBot', 'anthropic-ai', 'ClaudeBot/1.0', 'claude-web/1.0',
        'PerplexityBot/1.0', 'Perplexity-User/1.0', 'MistralAI-User/1.0', 'Google-Extended/1.0', 'ccbot', 'CCBot/1.0',
        'bytespider/1.0', 'bytespider', 'OAI-SearchBot', 'cohere-ai/1.0', 'AI2Bot/1.0', 'Diffbot/0.1', 'omgili/1.0',
        'YouBot', 'Amazonbot/0.1', 'applebot', 'Applebot-Extended/1.0', 'TimpiBot/0.8', 'ernie bot', 'qwen', 'hunyuan', 'YisouSpider', 'Barkrowler',
        'Bytespider-image', 'ai-applied-ai-crawler', 'huggingface', 'midjourney', 'nlpcloud', 'anybot/',
        'doubao', 'deepseek', 'moonshot', 'minimax', 'zhipu', 'modelscope', 'baichuan', 'pi.ai', 'suno.ai', 'magickpen', 'yarn-ai',
        'openrouter.ai', 'deepai', 'modelscopebot', 'openllm',
        ],
        'SEO, marketing, analyse' => [
            'semrushbot', 'ahrefsbot', 'dotbot', 'rogerbot', 'linkdexbot',
            'screaming frog', 'ScreamingFrogSEOSpider', 'serpstatbot', 'seokicks-robot', 'sitecheckerbot',
            'netcraftsurveyagent', 'uptime', 'pingdom', 'gtmetrix', 'siteimprove',
            'contentking', 'blexbot', 'petalbot', 'dataforseo',
            'adsbot-google', 'AdsBot-Google-Mobile', 'crawlsonbot', 'megaindex',
            'coccocbot', 'seekport', 'domainstatsbot', 'serpapi', 'archivebot', 'seznam', 'metauri', 
            'trendictionbot', 'proxycurl', 'linkpreview', 'web-sniffer', 'snowplow', 'segmentbot', 'keenbot', 'quantcastbot', 
            'domaincrawler', 'trendkite', 'riddler', 'crawler4j', 'robot', 'ahrefssiteaudit', 'seoscanners', 'webmeup', 'seekport bot', 
            'sistrix', 'openlinkprofiler', 'searchmetricsbot', 'majestic12', 'backlinkcrawler', 'barkrowler/1.0', 
        ],
        'Surveillance, cloud, etc...' => [
        'microsoftpreview', 'msbot','amazonbot', 'cloudflare', 'cloudflare-amp-fetcher', 'cloudflare-health-check', 'cloudflare-custom-hostname',
        'bushbaby', 'chrome-ppp', 'downtimedetector', 'downnotifier.com', 'gotmonitor', 'hosttracker', 'ips-agent', 'miniflux', 'monibot',
        'monitoring360bot', 'monstabot', 'nxbot', 'pingomatic', 'updown.io', 'urlcheckr', 'watchmouse',
        'google-structured-data-testing-tool', 'turnitinbot', 'gobuster', 'pricedroneshoppingbot', 'luminati', 'brightdata', 'oxylabs',
        'smartproxy', 'proxycrawl', 'scrapinghub', 'scraperapi', 'crawlera', 'residential-proxy',
        ],
        'Moteurs de base' => [
            'bot', 'crawl', 'slurp', 'spider', 'crawler', 'scanner', 'fetch', 'monitor',
            'scrapy', 'Scrapy', 'python', 'curl', 'wget', 'Wget/', 'axios', 'httpclient', 'http_request2',
            'java', 'libwww', 'perl', 'go-http-client', 'Go-http-client/', 'node-superagent',
            'phpspider', 'okhttp', 'aiohttp', 'aiohttp-client', 'JetBrains IDE crawler', 'httrack', 'hts', 'webcopier',
        ],
        'Archives et prévisualisation' => [
            'archive.org_bot', 'ia_archiver', 'waybackarchive', 'urlresolver',
            'pagepeeker', 'webpreview', 'outbrain'
        ],
        'Suspects, spam, Ddos etc...' => [
        'yandexbot', 'mail.ru_bot', 'zmap', 'fofa', 'python-requests', 'python-urllib', 'libwww-perl', 'nessus', 'paros',
        'nikto', 'openvas', 'virustotal', 'semaltbot', 'dataprovider spider', 'sbl-bot', 'mauibot', 'reqwest',
        'alphabot', 'NPBot', 'Hotjar', 'Mixpanel', 'CrazyEgg', 'FullStory', 'inspectlet', 'LuckyOrange', 'Smartlook', 'PlausibleBot', 
    ],
        'Outils divers' => [
            'uptimerobot', 'uptimia', 'datadog', 'statuscake', 'newrelicpinger',
            'hubspot', 'mailchimp', 'clicky', 'pingdom.com bot', 'site24x7', 'downnotifier', 'check-host',
            'ghostinspector', 'calibreapp', 'loader.io', 'webpage-test', 'googleweblight', 'speedcurve',
        'dotcom-monitor', 'uptimebot',
        ],
    ];
}


function easy_bots_render_admin_page() {

    if (isset($_POST['easy_bots_save']) && check_admin_referer('easy_bots_save_action', 'easy_bots_nonce')) {
        $blocked_raw = $_POST['blocked_bots'] ?? [];
        $blocked = array_map('sanitize_text_field', $blocked_raw);
        update_option('easy_bots_blocked_list', $blocked);
        echo '<div class="notice notice-success is-dismissible"><p>Configuration enregistrée.</p></div>';
    }

    $blocked = get_option('easy_bots_blocked_list', []);
    $log_file = defined('EASY_BOTS_PATH') ? EASY_BOTS_PATH . 'logs/bot-log.txt' : '';
    $log_content = ($log_file && file_exists($log_file)) ? file_get_contents($log_file) : 'Aucune activité bot enregistrée.';

    // Test lecture bot-log.txt
    if ($log_file && file_exists($log_file)) {

    if (strpos($log_content, 'Googlebot') !== false) {

    }
} else {

}
// Fin du test lecture bot-log.txt

    $categories = easy_bots_get_categorized_list();
    ?>
    <div class="wrap">
        <h1>Easy-bots</h1>

        <h2 class="nav-tab-wrapper">
            <a href="#" class="nav-tab nav-tab-active" id="tab-stats">Statistiques</a>
            <a href="#" class="nav-tab" id="tab-block">Blocage</a>
        </h2>

        <div id="content-stats" class="easy-bots-tab-content" style="display:block;">
            <h2>Journal des bots détectés</h2>

            <label for="easy-bots-category-filter"><strong>Filtrer par catégorie :</strong></label>
            <select id="easy-bots-category-filter">
                <?php foreach (array_keys($categories) as $cat): ?>
                    <option value="<?php echo esc_attr($cat); ?>"><?php echo esc_html($cat); ?></option>
                <?php endforeach; ?>
            </select>

            <h2 class="nav-tab-wrapper" id="easy-bots-subtabs">
    <a href="#" class="nav-tab nav-tab-active easy-bots-subtab" data-target="daily">Statistiques quotidiennes</a>
    <a href="#" class="nav-tab easy-bots-subtab" data-target="weekly">Statistiques hebdomadaires</a>
    <a href="#" class="nav-tab easy-bots-subtab" data-target="monthly">Statistiques mensuelles</a>
</h2>



            <div id="easy-bots-tab-daily" class="easy-bots-subtab-content" style="display: block;">
                <?php require_once plugin_dir_path(__FILE__) . '/../charts/chart-daily.php'; ?>
            </div>
            <div id="easy-bots-tab-weekly" class="easy-bots-subtab-content" style="display: none;">
                <?php require_once plugin_dir_path(__FILE__) . '/../charts/chart-weekly.php'; ?>
            </div>
            <div id="easy-bots-tab-monthly" class="easy-bots-subtab-content" style="display: none;">
                <?php require_once plugin_dir_path(__FILE__) . '/../charts/chart-monthly.php'; ?>
            </div>

            <?php
$log_file = plugin_dir_path(__FILE__) . '../logs/bot-log.txt';
$log_content = file_exists($log_file) ? file_get_contents($log_file) : '';
?>
<h2>Journal d’activité des bots</h2>

<textarea readonly rows="15"
    id="easy-bots-log-textarea"
    style="width:100%; font-family:monospace; margin-top:20px;"
    data-full="<?php echo esc_attr(Easy_Bots::display_log()); ?>">
<?php echo esc_textarea(Easy_Bots::display_log()); ?>
</textarea>

<form method="post" action="<?php echo admin_url('admin-post.php'); ?>" style="margin-top:20px;">
    <?php wp_nonce_field('easy_bots_clear_logs_action', 'easy_bots_clear_logs_nonce'); ?>
    <input type="hidden" name="action" value="easy_bots_clear_logs">
    <button type="submit" class="button button-secondary">
        Supprimer tous les logs
    </button>
</form>


<script>
    window.easyBotsByCategory = <?php echo json_encode(easy_bots_get_categorized_list()); ?>;
</script>


        </div>

        <div id="content-block" class="easy-bots-tab-content" style="display:none;">
            <h2>Configuration des bots à bloquer</h2>

            <label for="easy-bots-block-filter"><strong>Filtrer par catégorie :</strong></label>
            <select id="easy-bots-block-filter">
                <option value="all">Toutes les catégories</option>
                <?php foreach (array_keys($categories) as $cat): ?>
                    <option value="<?php echo esc_attr($cat); ?>"><?php echo esc_html($cat); ?></option>
                <?php endforeach; ?>
            </select>

            <br><br>
            <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
    <input type="hidden" name="action" value="easy_bots_save_settings">

                <?php wp_nonce_field('easy_bots_save_action', 'easy_bots_nonce'); ?>
                <?php
                // === DEBUG : Vérifie que tous les bots du formulaire sont bien dans la liste principale ===
$all_form_bots = [];
foreach ($categories as $bots) {
    $all_form_bots = array_merge($all_form_bots, $bots);
}

$bot_list = easy_bots_get_list();
$bot_list = array_map('strtolower', $bot_list); // normalisation
$all_form_bots = array_map('strtolower', $all_form_bots);

foreach ($all_form_bots as $bot_from_form) {
    if (!in_array($bot_from_form, $bot_list)) {

    }
}
// Fin debug
                $index = 0;
                foreach ($categories as $category => $bots):
                    $group_id = 'bot-group-' . $index++;
                    ?>
                    <div class="bot-category-group" data-category="<?php echo esc_attr($category); ?>">
                        <h3><?php echo esc_html($category); ?></h3>
                        <button type="button" class="button toggle-category" data-target="<?php echo esc_attr($group_id); ?>">Tout cocher / décocher</button><br><br>
                        <div id="<?php echo esc_attr($group_id); ?>">
                            <table class="widefat striped"><tbody>
                                <?php foreach ($bots as $bot): ?>
                                    <tr>
                                        <td width="30"><input type="checkbox" name="blocked_bots[]" value="<?php echo esc_attr($bot); ?>" <?php checked(in_array($bot, $blocked)); ?>></td>
                                        <td><code><?php echo esc_html($bot); ?></code></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody></table><br>
                        </div>
                    </div>
                <?php endforeach; ?>
                <p><input type="submit" class="button button-primary" name="easy_bots_save" value="Enregistrer la configuration"></p>
            </form>
        </div>
    </div>

    <style>
        /* Styles tableau et affichage */
        .bot-category-group {
            margin-bottom: 30px;
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 4px;
            background: #fafafa;
        }
        .toggle-category {
            margin-bottom: 10px;
        }
        .easy-bots-tab-content {
            margin-top: 20px;
        }
    </style>

    

    <?php
}
