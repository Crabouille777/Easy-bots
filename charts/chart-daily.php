<?php
require_once dirname(__DIR__) . '/includes/functions.php';

$log_data = easy_bots_parse_log_daily(); 

// Définition des labels horaires (00:00 à 23:00)
$hours = [];
for ($h = 0; $h < 24; $h++) {
    $hours[] = sprintf('%02d:00', $h);
}

// Si $log_data est vide, création d'un tableau avec 0 pour chaque heure
if (empty($log_data) || !is_array($log_data)) {
    $log_data = array_fill_keys($hours, 0);
}

// Préparation des valeurs dans l'ordre des heures
$values = [];
foreach ($hours as $hour) {
    $values[] = isset($log_data[$hour]) ? intval($log_data[$hour]) : 0;
}

$labels_json = json_encode($hours);
$values_json = json_encode($values);
?>

<div id="easy-bots-chart-daily" class="easy-bots-chart-container">
    <canvas id="chartDaily" style="max-width: 700px;"></canvas>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const labels = <?php echo $labels_json; ?>;
    const data = <?php echo $values_json; ?>;
    if (typeof createLineChart === 'function') {
        createLineChart('chartDaily', labels, data, 'Visites bots par jour');
    } else {
        console.error('createLineChart() non trouvée — charts.js est-il bien chargé ?');
    }
});
</script>
