<?php
require_once dirname(__DIR__) . '/includes/functions.php';

$log_data = easy_bots_parse_log_monthly();
?>

<?php if (!empty($log_data)) : ?>
    <?php
    $labels_json = json_encode(array_keys($log_data));
    $values_json = json_encode(array_values($log_data));
    ?>
    <div id="easy-bots-chart-monthly" class="easy-bots-chart-container">
        <canvas id="chartMonthly" style="max-width: 700px;"></canvas>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const labels = <?php echo $labels_json; ?>;
        const data = <?php echo $values_json; ?>;
        if (typeof createLineChart === 'function') {
            createLineChart('chartMonthly', labels, data, 'Visites bots par mois');
        } else {
            console.error('createLineChart() non trouvée — charts.js est-il bien chargé ?');
        }
    });
    </script>
<?php else : ?>
    <p style="color:#888; font-style:italic;">Aucune donnée à afficher pour ce mois.</p>
<?php endif; ?>
