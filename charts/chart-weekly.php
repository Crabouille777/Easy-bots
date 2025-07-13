<?php
require_once dirname(__DIR__) . '/includes/functions.php';

$log_data = easy_bots_parse_log_weekly();
?>

<?php if (!empty($log_data)) : ?>
    <?php
    $labels_json = json_encode(array_keys($log_data));
    $values_json = json_encode(array_values($log_data));
    ?>
    <div id="easy-bots-chart-weekly" class="easy-bots-chart-container">
        <canvas id="chartWeekly" style="max-width: 700px;"></canvas>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const labels = <?php echo $labels_json; ?>;
        const data = <?php echo $values_json; ?>;
        if (typeof createLineChart === 'function') {
            createLineChart('chartWeekly', labels, data, 'Visites bots par semaine');
        } else {
            console.error('createLineChart() non trouvée — charts.js est-il bien chargé ?');
        }
    });
    </script>
<?php else : ?>
    <p style="color:#888; font-style:italic;">Aucune donnée à afficher pour cette semaine.</p>
<?php endif; ?>
