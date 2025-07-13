jQuery(function($) {

    // === Onglets principaux ===
    function switchMainTabs(tab) {
        if (tab === 'stats') {
            $('#tab-stats').addClass('nav-tab-active');
            $('#tab-block').removeClass('nav-tab-active');
            $('#content-stats').show();
            $('#content-block').hide();
        } else if (tab === 'block') {
            $('#tab-block').addClass('nav-tab-active');
            $('#tab-stats').removeClass('nav-tab-active');
            $('#content-block').show();
            $('#content-stats').hide();
        }
    }

    $('#tab-stats').on('click', function(e) {
        e.preventDefault();
        switchMainTabs('stats');
    });

    $('#tab-block').on('click', function(e) {
        e.preventDefault();
        switchMainTabs('block');
    });

    // === Sous-onglets Statistiques ===
    const subTabs = $('.easy-bots-subtab');

    function showChartForTab(target) {
        if (typeof createLineChart === 'function') {
            if (window.easyBotsData && window.easyBotsData[target]) {
                const data = window.easyBotsData[target];
                createLineChart('chart-' + target, data.labels, data.values, data.label);
            }
        }
    }

    subTabs.on('click', function(e) {
        e.preventDefault();
        const target = $(this).data('target');
        switchMainTabs('stats');
        subTabs.removeClass('nav-tab-active');
        $(this).addClass('nav-tab-active');
        $('.easy-bots-subtab-content').hide();
        $('#easy-bots-tab-' + target).show();
        showChartForTab(target);
    });

    const defaultSubTab = subTabs.filter('.nav-tab-active');
    if (defaultSubTab.length) {
        defaultSubTab.trigger('click');
    } else {
        subTabs.first().trigger('click');
    }

    // === Filtrage des catégories dans le blocage ===
    const blockFilter = $('#easy-bots-block-filter');
    const blockGroups = $('.bot-category-group');

    function updateBlockFilterDisplay() {
        const selected = blockFilter.val();
        blockGroups.each(function() {
            const cat = $(this).data('category');
            $(this).toggle(selected === 'all' || selected === cat);
        });
    }

    blockFilter.on('change', updateBlockFilterDisplay);
    updateBlockFilterDisplay();

    // === Toggle catégorie : tout cocher/décocher ===
    $('.toggle-category').on('click', function() {
        const groupId = $(this).data('target');
        const group = $('#' + groupId);
        const checkboxes = group.find('input[type="checkbox"]');
        const allChecked = checkboxes.length === checkboxes.filter(':checked').length;
        checkboxes.prop('checked', !allChecked);
    });

    // === Filtrage dynamique du textarea des logs ===
    const categoryFilter = $('#easy-bots-category-filter');
    const textarea = $('#easy-bots-log-textarea');
    const fullLog = textarea.data('full') || '';
    const botsByCategory = window.easyBotsByCategory || {};

    function filterLogsByCategory(category) {
        if (!textarea.length) return;

        if (category === 'all') {
            textarea.val(fullLog);
            return;
        }

        const bots = (botsByCategory[category] || []).map(b => b.toLowerCase());
        const lines = fullLog.split("\n");

        const filtered = lines.filter(line => {
            const parts = line.split('|');
            if (parts.length < 2) return false;
            const botName = parts[1].trim().toLowerCase();
            return bots.includes(botName);
        });

        textarea.val(filtered.join("\n"));
    }

    categoryFilter.on('change', function () {
        filterLogsByCategory($(this).val());
    });

    // Initialisation filtrage logs
    filterLogsByCategory(categoryFilter.val());

});
