<?php
$tabs = [
    [
        'id' => 'overview',
        'label' => 'Overview',
        'icon' => 'overview'
    ],
    [
        'id' => 'routing',
        'label' => 'Routing Config',
        'icon' => 'routing'
    ],
    [
        'id' => 'env-config',
        'label' => 'Environment',
        'icon' => 'settings'
    ],
    [
        'id' => 'postback',
        'label' => 'Postback',
        'icon' => 'postback'
    ],
    [
        'id' => 'statistics',
        'label' => 'Statistics',
        'icon' => 'statistics'
    ],
    [
        'id' => 'logs',
        'label' => 'Traffic Logs',
        'icon' => 'logs',
        'badge' => 'logs.length'
    ],
    [
        'id' => 'api-docs',
        'label' => 'API Docs',
        'icon' => 'docs'
    ],
];

function renderTabIcon(string $icon): string
{
    return match ($icon) {
        'overview' => '<svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">'
            . '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"'
            . ' d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 0 0-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 0 12 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>'
            . '</svg>',
        'routing' => '<svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">'
            . '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"'
            . ' d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>'
            . '</svg>',
        'settings' => '<svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">'
            . '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"'
            . ' d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>'
            . '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>'
            . '</svg>',
        'postback' => '<svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">'
            . '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>'
            . '</svg>',
        'statistics' => '<svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">'
            . '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"'
            . ' d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>'
            . '</svg>',
        'logs' => '<svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">'
            . '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>'
            . '</svg>',
        'docs' => '<svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">'
            . '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"'
            . ' d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>'
            . '</svg>',
        default => '',
    };
}
?>

<!-- Tabs Navigation -->
<div class="border-b bg-card/70 backdrop-blur supports-[backdrop-filter]:bg-card/60">
    <div class="max-w-4xl mx-auto px-5">
        <nav class="flex items-center gap-1 overflow-x-auto py-2" aria-label="Tabs">
            <div class="flex min-w-full sm:min-w-0 gap-1">
                <?php foreach ($tabs as $tab): ?>
                    <button
                        type="button"
                        @click="activeTab = '<?= $tab['id']; ?>'"
                        :class="activeTab === '<?= $tab['id']; ?>' ? 'bg-primary/5 text-foreground border-primary' : 'text-muted-foreground hover:text-foreground border-transparent'"
                        class="group inline-flex items-center gap-2 rounded-md border px-3 py-2 text-xs font-medium transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2">
                        <span class="text-muted-foreground group-hover:text-foreground">
                            <?= renderTabIcon($tab['icon']); ?>
                        </span>
                        <span class="hidden md:inline" x-text="'<?= $tab['label']; ?>'"></span>
                        <?php if (!empty($tab['badge'])): ?>
                            <span class="hidden md:inline rounded-full bg-muted px-1.5 py-0.5 text-[9px] font-medium"
                                  x-text="<?= $tab['badge']; ?>"></span>
                        <?php endif; ?>
                    </button>
                <?php endforeach; ?>
            </div>
        </nav>
    </div>
</div>
