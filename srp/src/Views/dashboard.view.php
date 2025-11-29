<?php
$pageTitle = 'SRP Traffic Control';
require __DIR__ . '/components/header.php';
?>
<div x-data="dash" x-cloak>
<header class="sticky top-0 z-50 w-full border-b bg-background/95 backdrop-blur supports-[backdrop-filter]:bg-background/60">
    <div class="flex h-12 max-w-4xl mx-auto items-center px-5">
        <div class="mr-3 hidden md:flex">
            <a href="/" class="mr-4 flex items-center space-x-2">
                <img src="/assets/icons/fox.svg" alt="Fox head logo" class="h-8 w-8" width="32" height="32">
                <div class="flex flex-col leading-tight">
                <span class="font-semibold text-sm tracking-tight">SRP Smart Redirect Platform</span>
                 <span class="text-[11px] text-muted-foreground">No "smart" buzzword without actual routing logic.</span>
                 </div>
            </a>
        </div>

        <button @click="mobileMenuOpen = !mobileMenuOpen" class="mr-2 md:hidden btn btn-ghost btn-icon" aria-label="Toggle navigation">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>

        <div class="flex md:hidden items-center space-x-2">
            <img src="/assets/icons/fox.svg" alt="Fox head logo" class="h-4 w-4" width="16" height="16">
            <span class="font-semibold text-xs tracking-tight">SRP</span>
        </div>

        <div class="flex flex-1 items-center justify-end space-x-2">
            <div class="flex items-center space-x-2 rounded-md px-2 sm:px-2.5 py-1 transition-colors duration-200"
                 :class="cfg.system_on ? (muteStatus.isMuted ? 'bg-amber-500 text-white shadow-sm' : 'bg-primary text-primary-foreground shadow-sm') : 'border'">
                <div class="h-1.5 w-1.5 rounded-full transition-all duration-200"
                     :class="cfg.system_on ? (muteStatus.isMuted ? 'bg-white animate-pulse' : 'bg-emerald-500 animate-pulse') : 'bg-gray-400'"></div>
                <span class="text-[11px] font-medium hidden sm:inline"
                      x-text="cfg.system_on ? (muteStatus.isMuted ? 'Muted' : 'Active') : 'Offline'"></span>
            </div>

            <button type="button"
                    @click="toggleAutoRefresh()"
                    class="btn btn-ghost btn-icon"
                    :title="autoRefreshEnabled ? 'Pause auto-refresh' : 'Resume auto-refresh'"
                    aria-label="Toggle auto-refresh">
                <svg x-show="autoRefreshEnabled" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <svg x-show="!autoRefreshEnabled" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </button>

            <form method="post" action="/logout.php" class="hidden sm:block">
                <input type="hidden" name="_csrf_token"
                       value="<?= htmlspecialchars($csrfToken ?? '', ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5, 'UTF-8'); ?>">
                <button type="submit" class="btn btn-secondary btn-sm">Logout</button>
            </form>

            <form method="post" action="/logout.php" class="sm:hidden">
                <input type="hidden" name="_csrf_token"
                       value="<?= htmlspecialchars($csrfToken ?? '', ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5, 'UTF-8'); ?>">
                <button type="submit" class="btn btn-ghost btn-icon" aria-label="Logout">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v1" />
                    </svg>
                </button>
            </form>
        </div>
    </div>
</header>

<!-- Toast & Confirm Modal -->
<?php require __DIR__ . '/components/toast.php'; ?>

<main class="flex-1 w-full">
    <?php require __DIR__ . '/components/dashboard-content.php'; ?>
</main>
</div>

<script nonce="<?= htmlspecialchars($cspNonce ?? '', ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5, 'UTF-8'); ?>">
document.addEventListener('alpine:init', () => {
    Alpine.data('dash', () => ({
        // Navigation
        activeTab: 'overview',
        mobileMenuOpen: false,

        // Configuration
        cfg: {
            system_on: false,
            redirect_url: [],
            country_filter_mode: 'all',
            country_filter_list: '',
            updated_at: 0,
            postback_url: '',
            default_payout: 0
        },

        // Environment Config
        envConfig: {
            // Database
            DB_HOST: 'localhost',
            DB_NAME: '',
            DB_USER: '',
            DB_PASS: '',
            DB_PORT: '3306',
            DB_CHARSET: 'utf8mb4',

            // Domain Configuration
            APP_NAME: 'Smart Redirect Platform',
            APP_URL: '',
            APP_PANEL_URL: '',
            BRAND_DOMAIN: '',
            TRACKING_PRIMARY_DOMAIN: '',
            TRACKING_DOMAIN: '',
            TRACKING_REDIRECT_URL: '',
            TRACKING_DECISION_API: '',
            TRACKING_POSTBACK_URL: '',

            // API Keys
            API_KEY_INTERNAL: '',
            API_KEY_EXTERNAL: '',
            SRP_API_URL: 'https://api.qvtrk.com/decision.php',
            SRP_API_KEY: '',

            // Application Settings
            APP_ENV: 'production',
            SRP_ENV: 'production',
            APP_DEBUG: 'false',
            APP_TIMEZONE: 'UTC',
            MAINTENANCE_MODE: 'false',
            MAINTENANCE_MESSAGE: 'System under maintenance. Please try again later.',

            // Session & Security
            SESSION_LIFETIME: '7200',
            SESSION_NAME: 'SRP_SESSION',
            SESSION_SECRET: '',
            RATE_LIMIT_ATTEMPTS: '5',
            RATE_LIMIT_WINDOW: '900',
            SECURE_COOKIES: 'true',
            HTTP_ONLY: 'true',
            SAME_SITE: 'Strict',
            TRUST_CF_HEADERS: 'true',

            // Feature Flags
            BRAND_ENABLE_LANDING_PAGE: 'true',
            BRAND_ENABLE_DOCUMENTATION: 'true',
            BRAND_ENABLE_API_DOCS: 'true',
            TRACKING_ENABLE_VPN_CHECK: 'true',
            TRACKING_ENABLE_GEO_FILTER: 'true',
            TRACKING_ENABLE_DEVICE_FILTER: 'true',
            TRACKING_ENABLE_AUTO_MUTE: 'true',
            RATE_LIMIT_TRACKING_ENABLED: 'true',

            // Postback Configuration
            POSTBACK_TIMEOUT: '5',
            POSTBACK_MAX_RETRIES: '3',
            POSTBACK_RETRY_DELAY: '60',
            POSTBACK_HMAC_SECRET: '',
            POSTBACK_REQUIRE_API_KEY: 'true',
            POSTBACK_API_KEY: '',
            POSTBACK_FORWARD_ENABLED: 'false',
            POSTBACK_FORWARD_URL: '',
            DEFAULT_PAYOUT: '0.00',

            // Path Configuration
            APP_ROOT: '',
            LOG_PATH: '',

            // External Services
            VPN_CHECK_URL: 'https://blackbox.ipinfo.app/lookup/',
            VPN_CHECK_TIMEOUT: '2'
        },
        showApiKey: false,
        showInternalKey: false,
        showExternalKey: false,
        showSrpKey: false,
        isSavingEnv: false,
        isSyncingEnv: false,
        isTestingDb: false,
        isTestingSrp: false,

        // Logs
        logs: [],

        // Flash messages
        flash: '',
        flashType: 'info',

        // Decision Tester
        testerOpen: false,
        testInput: {
            country: '',
            device: 'mobile',
            vpn: 'no'
        },
        testResult: null,

        // Loading states
        isSavingCfg: false,
        savingCfgCount: 0,
        isClearingLogs: false,
        flashAction: '',

        // Auto-refresh control
        autoRefreshEnabled: true,
        autoRefreshInterval: 5000, // 5 seconds instead of 3
        refreshIntervalId: null,
        lastRefreshTime: 0,
        isRefreshing: false,

        // Mute status
        muteStatus: {
            isMuted: false,
            timeRemaining: '',
            cyclePosition: 0
        },

        // Stats
        totalDecisionA: 0,
        totalDecisionB: 0,
        statsResetAt: 0,

        // Postback
        postbackTest: {
            country: 'US',
            trafficType: 'WAP',
            payout: 0
        },
        postbackTestResult: null,
        isTestingPostback: false,
        postbackLogs: [],
        receivedPostbacks: [],
        postbackLoadErrors: 0,
        maxPostbackErrors: 3,

        // Daily/Weekly Payout Stats
        dailyStats: [],
        statsSummary: {
            total_postbacks: 0,
            total_payout: 0,
            avg_daily_payout: 0,
            days_count: 0,
            period_days: 30
        },
        statsLoading: false,
        statsPeriod: 30,
        statsView: 'weekly', // 'daily' or 'weekly'

        init() {
            this.refresh();
            this.loadEnvConfig();
            this.loadPostbackLogs();
            this.loadReceivedPostbacks();
            this.loadDailyStats();
            this.updateMuteStatus();
            this.startAutoRefresh();

            // Set intervals with error handling
            this.postbackInterval = setInterval(() => {
                if (this.postbackLoadErrors < this.maxPostbackErrors) {
                    this.loadPostbackLogs();
                }
            }, 10000); // Changed to 10s

            setInterval(() => this.loadReceivedPostbacks(), 10000); // Changed to 10s
            setInterval(() => this.loadDailyStats(), 60000); // Refresh stats every 60s
            setInterval(() => this.updateMuteStatus(), 1000);
        },

        startAutoRefresh() {
            if (this.refreshIntervalId) {
                clearInterval(this.refreshIntervalId);
            }
            this.refreshIntervalId = setInterval(() => {
                if (this.autoRefreshEnabled) {
                    this.refresh();
                }
            }, this.autoRefreshInterval);
        },

        toggleAutoRefresh() {
            this.autoRefreshEnabled = !this.autoRefreshEnabled;
            if (this.autoRefreshEnabled) {
                this.refresh(); // Immediate refresh when enabled
            }
        },

        csrf() {
            const el = document.querySelector('meta[name="csrf-token"]');
            return el && el.content ? el.content : '';
        },

        setFlash(message, type = 'info') {
            this.flashType = type;
            this.flash = message;

            if (!message || type === 'confirm') {
                return;
            }

            setTimeout(() => {
                if (this.flash === message && this.flashType === type) {
                    this.flash = '';
                }
            }, 4000);
        },

        /**
         * Safe response handler untuk AJAX calls
         * Handles both JSON and non-JSON responses gracefully
         */
        async safeJsonParse(response) {
            // Clone response karena body hanya bisa dibaca sekali
            const contentType = response.headers.get('content-type') || '';
            const isJson = contentType.includes('application/json');

            // Jika bukan JSON, return error object
            if (!isJson) {
                let errorMsg = 'Server returned non-JSON response';

                // Try to get text content untuk error message
                try {
                    const text = await response.text();
                    if (text.includes('DB_CONNECTION_ERROR')) {
                        errorMsg = 'Database connection error. Please contact administrator.';
                    } else if (text.includes('System Error')) {
                        errorMsg = 'System error occurred. Please try again later.';
                    } else if (response.status === 500) {
                        errorMsg = 'Internal server error';
                    } else if (response.status === 404) {
                        errorMsg = 'Endpoint not found';
                    }
                } catch (e) {
                    // Ignore text parsing error
                }

                return {
                    ok: false,
                    error: errorMsg,
                    code: 'NON_JSON_RESPONSE'
                };
            }

            // Try parsing JSON
            try {
                return await response.json();
            } catch (e) {
                return {
                    ok: false,
                    error: 'Invalid JSON response from server',
                    code: 'JSON_PARSE_ERROR'
                };
            }
        },

        async refresh() {
            // Prevent multiple simultaneous refreshes
            if (this.isRefreshing) {
                return;
            }

            this.isRefreshing = true;

            try {
                const r = await fetch('data.php', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                // Check HTTP status
                if (!r.ok) {
                    let errorMsg = 'HTTP ' + r.status;
                    const errorData = await this.safeJsonParse(r);
                    if (errorData && errorData.error) {
                        errorMsg = errorData.error;
                    }
                    // Only show error if not auto-refreshing in background
                    if (!this.autoRefreshEnabled || this.lastRefreshTime === 0) {
                        this.setFlash('Failed to load data: ' + errorMsg, 'error');
                    }
                    return;
                }

                const data = await this.safeJsonParse(r);

                if (data && data.ok) {
                    data.cfg.system_on = Boolean(Number(data.cfg.system_on));
                    // Ensure redirect_url is always an array
                    if (!Array.isArray(data.cfg.redirect_url)) {
                        data.cfg.redirect_url = [];
                    }

                    // Smooth update - only update if changed to reduce DOM updates
                    const cfgChanged = JSON.stringify(this.cfg) !== JSON.stringify(data.cfg);
                    const logsChanged = JSON.stringify(this.logs) !== JSON.stringify(data.logs);

                    if (cfgChanged) {
                        this.cfg = data.cfg;
                        this.totalDecisionA = Number(data.cfg.total_decision_a) || 0;
                        this.totalDecisionB = Number(data.cfg.total_decision_b) || 0;
                        this.statsResetAt = Number(data.cfg.stats_reset_at) || 0;
                    }

                    if (logsChanged) {
                        this.logs = Array.isArray(data.logs) ? data.logs : [];
                    }

                    this.lastRefreshTime = Date.now();
                } else if (data && data.error) {
                    // Only show error if not auto-refreshing in background
                    if (!this.autoRefreshEnabled || this.lastRefreshTime === 0) {
                        this.setFlash('Failed to load data: ' + data.error, 'error');
                    }
                }
            } catch (e) {
                // Only show error on first load or when auto-refresh is disabled
                if (!this.flash && (!this.autoRefreshEnabled || this.lastRefreshTime === 0)) {
                    console.error('Dashboard refresh error:', e);
                    this.setFlash('Failed to refresh dashboard data: ' + e.message, 'error');
                }
            } finally {
                this.isRefreshing = false;
            }
        },

        async save() {
            // Validate all URLs
            if (Array.isArray(this.cfg.redirect_url)) {
                for (let i = 0; i < this.cfg.redirect_url.length; i++) {
                    const url = this.cfg.redirect_url[i];
                    if (url && !url.startsWith('https://')) {
                        this.setFlash('All redirect URLs must start with https://', 'error');
                        return;
                    }
                }
            }

            this.savingCfgCount += 1;
            this.isSavingCfg = true;

            try {
                const r = await fetch('data.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-Token': this.csrf()
                    },
                    body: JSON.stringify({
                        system_on: this.cfg.system_on,
                        redirect_url: this.cfg.redirect_url,
                        country_filter_mode: this.cfg.country_filter_mode,
                        country_filter_list: this.cfg.country_filter_list
                    })
                });

                if (!r.ok) {
                    this.setFlash('Failed to save configuration (HTTP ' + r.status + ')', 'error');
                    return;
                }

                this.setFlash('Configuration saved', 'info');
            } catch (e) {
                this.setFlash('Failed to save configuration', 'error');
            } finally {
                this.savingCfgCount -= 1;
                if (this.savingCfgCount <= 0) {
                    this.savingCfgCount = 0;
                    this.isSavingCfg = false;
                }
            }
        },

        addRedirectUrl() {
            if (!Array.isArray(this.cfg.redirect_url)) {
                this.cfg.redirect_url = [];
            }
            this.cfg.redirect_url.push('');
        },

        removeRedirectUrl(index) {
            if (Array.isArray(this.cfg.redirect_url)) {
                this.cfg.redirect_url.splice(index, 1);
                this.save();
            }
        },

        updateRedirectUrl(index, value) {
            if (Array.isArray(this.cfg.redirect_url)) {
                this.cfg.redirect_url[index] = value;
            }
        },

        clearLogs() {
            if (this.isClearingLogs) {
                return;
            }

            this.flashAction = 'clearLogs';
            this.setFlash('Clear all traffic logs? This action cannot be undone.', 'confirm');
        },

        cancelFlashAction() {
            this.flash = '';
            this.flashType = 'info';
            this.flashAction = '';
        },

        async confirmFlashAction() {
            if (this.flashType !== 'confirm') {
                return;
            }

            if (this.flashAction === 'clearLogs') {
                await this.performClearLogs();
            }
        },

        async performClearLogs() {
            if (this.isClearingLogs) {
                return;
            }

            this.isClearingLogs = true;

            try {
                const r = await fetch('data.php', {
                    method: 'DELETE',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-Token': this.csrf()
                    }
                });

                const result = await this.safeJsonParse(r);

                if (result && result.ok) {
                    this.logs = [];
                    const deleted = typeof result.deleted === 'number' ? result.deleted : 0;
                    this.setFlash('Successfully deleted ' + deleted + ' log entries', 'info');
                } else {
                    this.setFlash('Failed to clear logs', 'error');
                }
            } catch (e) {
                this.setFlash('Failed to clear logs', 'error');
            } finally {
                this.isClearingLogs = false;
                this.flashAction = '';
            }
        },

        isCountryAllowed(countryCode) {
            const code = (countryCode || '').toUpperCase().trim();
            const mode = this.cfg.country_filter_mode || 'all';

            if (mode === 'all') {
                return true;
            }

            const raw = this.cfg.country_filter_list || '';
            const parts = raw.split(',');
            const list = [];
            for (let i = 0; i < parts.length; i += 1) {
                const p = parts[i].trim().toUpperCase();
                if (p !== '') {
                    list.push(p);
                }
            }

            const inList = list.length === 0 ? true : list.indexOf(code) !== -1;

            if (mode === 'whitelist') {
                return inList;
            }
            if (mode === 'blacklist') {
                return !inList;
            }

            return true;
        },

        runTest() {
            const normalizedCountry = (this.testInput.country || '').toUpperCase().trim();
            this.testInput.country = normalizedCountry;

            let decision = 'B';
            let reason = '';
            let selectedUrl = null;

            if (!this.cfg.system_on) {
                decision = 'B';
                reason = 'System is OFF';
            } else if (!Array.isArray(this.cfg.redirect_url) || this.cfg.redirect_url.length === 0) {
                decision = 'B';
                reason = 'No redirect URLs configured';
            } else if (this.testInput.vpn === 'yes') {
                decision = 'B';
                reason = 'VPN / proxy detected';
            } else if (!this.isCountryAllowed(normalizedCountry)) {
                decision = 'B';
                reason = 'Country not allowed by current filter mode';
            } else if (this.testInput.device !== 'mobile') {
                decision = 'B';
                reason = 'Non-mobile device falls back';
            } else {
                decision = 'A';
                // Randomly select one URL from the array
                const validUrls = this.cfg.redirect_url.filter(url => url && url.trim() !== '');
                if (validUrls.length > 0) {
                    const randomIndex = Math.floor(Math.random() * validUrls.length);
                    selectedUrl = validUrls[randomIndex];
                    reason = 'System ON, allowed country, mobile device, no VPN. Randomly selected URL: ' + selectedUrl;
                } else {
                    decision = 'B';
                    reason = 'No valid redirect URLs configured';
                }
            }

            this.testResult = {
                decision: decision,
                reason: reason,
                url: selectedUrl
            };
        },

        fmt(t) {
            return t ? new Date(t * 1000).toLocaleString() : '';
        },

        formatNumber(num) {
            if (typeof num !== 'number') {
                num = Number(num) || 0;
            }
            return num.toLocaleString();
        },

        formatWeekRange(startDate, endDate) {
            if (!startDate || !endDate) {
                return startDate || '';
            }

            const start = new Date(startDate);
            const end = new Date(endDate);

            const startDay = start.getDate();
            const endDay = end.getDate();

            const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            const startMonth = monthNames[start.getMonth()];
            const endMonth = monthNames[end.getMonth()];
            const year = start.getFullYear();

            // Same month: "3 - 9 Jan"
            if (start.getMonth() === end.getMonth() && start.getFullYear() === end.getFullYear()) {
                return `${startDay} - ${endDay} ${startMonth}`;
            }

            // Different months: "30 Jan - 5 Feb"
            return `${startDay} ${startMonth} - ${endDay} ${endMonth}`;
        },

        getStatsResetInfo() {
            if (!this.statsResetAt || this.statsResetAt === 0) {
                return 'Not set';
            }

            const now = Math.floor(Date.now() / 1000);
            const oneMonthInSeconds = 30 * 24 * 60 * 60; // 30 days
            const nextResetAt = this.statsResetAt + oneMonthInSeconds;
            const daysRemaining = Math.ceil((nextResetAt - now) / (24 * 60 * 60));

            if (daysRemaining <= 0) {
                return 'Resetting soon...';
            }

            return daysRemaining + ' day(s) remaining';
        },

        updateMuteStatus() {
            const currentMinute = Math.floor(Date.now() / 1000 / 60);
            const currentSecond = Math.floor(Date.now() / 1000);
            const cyclePosition = currentMinute % 5;

            this.muteStatus.cyclePosition = cyclePosition;
            this.muteStatus.isMuted = cyclePosition >= 2;

            let secondsInCycle = currentSecond % 300;
            let secondsRemaining;

            if (this.muteStatus.isMuted) {
                secondsRemaining = 300 - secondsInCycle;
                const mins = Math.floor(secondsRemaining / 60);
                const secs = secondsRemaining % 60;
                this.muteStatus.timeRemaining = `Unmute in ${mins}m ${secs}s`;
            } else {
                secondsRemaining = 120 - secondsInCycle;
                const mins = Math.floor(secondsRemaining / 60);
                const secs = secondsRemaining % 60;
                this.muteStatus.timeRemaining = `Mute in ${mins}m ${secs}s`;
            }
        },

        // Environment Config Methods
        async loadEnvConfig() {
            try {
                const r = await fetch('env-config.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ action: 'get' })
                });

                const data = await this.safeJsonParse(r);

                if (data && data.ok) {
                    this.envConfig = data.config;
                }
            } catch (e) {
                console.error('Failed to load environment config:', e);
            }
        },

        async saveEnvConfig() {
            if (this.isSavingEnv) {
                return;
            }

            this.isSavingEnv = true;

            try {
                const r = await fetch('env-config.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        action: 'update',
                        config: this.envConfig
                    })
                });

                const data = await this.safeJsonParse(r);

                if (data && data.ok) {
                    this.setFlash('Environment configuration saved successfully', 'info');
                } else {
                    this.setFlash(data.error || 'Failed to save configuration', 'error');
                }
            } catch (e) {
                this.setFlash('Failed to save environment configuration', 'error');
            } finally {
                this.isSavingEnv = false;
            }
        },

        async testDatabaseConnection() {
            if (this.isTestingDb) {
                return;
            }

            this.isTestingDb = true;

            try {
                const r = await fetch('env-config.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        action: 'test_db',
                        host: this.envConfig.DB_HOST,
                        database: this.envConfig.DB_NAME,
                        username: this.envConfig.DB_USER,
                        password: this.envConfig.DB_PASS
                    })
                });

                const data = await this.safeJsonParse(r);

                if (data && data.ok) {
                    this.setFlash('Database connection successful', 'info');
                } else {
                    this.setFlash(data.message || 'Database connection failed', 'error');
                }
            } catch (e) {
                this.setFlash('Failed to test database connection', 'error');
            } finally {
                this.isTestingDb = false;
            }
        },

        async testSrpConnection() {
            if (this.isTestingSrp) {
                return;
            }

            this.isTestingSrp = true;

            try {
                const r = await fetch('env-config.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        action: 'test_srp',
                        api_url: this.envConfig.SRP_API_URL,
                        api_key: this.envConfig.SRP_API_KEY
                    })
                });

                const data = await this.safeJsonParse(r);

                if (data && data.ok) {
                    this.setFlash('SRP API connection successful', 'info');
                } else {
                    this.setFlash(data.message || 'SRP API connection failed', 'error');
                }
            } catch (e) {
                this.setFlash('Failed to test SRP API connection', 'error');
            } finally {
                this.isTestingSrp = false;
            }
        },

        async syncFromEnv() {
            if (this.isSyncingEnv) {
                return;
            }

            this.isSyncingEnv = true;

            try {
                const r = await fetch('env-config.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        action: 'sync_from_env'
                    })
                });

                const data = await this.safeJsonParse(r);

                if (data && data.ok) {
                    this.setFlash('Configuration synced from .env to database successfully', 'info');
                    // Reload config after sync
                    await this.loadEnvConfig();
                } else {
                    this.setFlash(data.message || 'Failed to sync configuration from .env', 'error');
                }
            } catch (e) {
                this.setFlash('Failed to sync configuration from .env', 'error');
            } finally {
                this.isSyncingEnv = false;
            }
        },

        generateApiKey(type) {
            const chars = '0123456789abcdef';
            let key = '';
            for (let i = 0; i < 64; i++) {
                key += chars.charAt(Math.floor(Math.random() * chars.length));
            }

            if (type === 'internal') {
                this.envConfig.API_KEY_INTERNAL = key;
            } else if (type === 'external') {
                this.envConfig.API_KEY_EXTERNAL = key;
            }
        },

        generateSessionSecret() {
            const chars = '0123456789abcdefghijklmnopqrstuvwxyz';
            let secret = '';
            for (let i = 0; i < 32; i++) {
                secret += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            this.envConfig.SESSION_SECRET = secret;
        },

        // Postback Methods
        async savePostback() {
            if (!Array.isArray(this.cfg.redirect_url)) {
                this.cfg.redirect_url = [];
            }

            // Save current state for rollback
            const previousUrl = this.cfg.postback_url;
            const previousPayout = this.cfg.default_payout;

            this.savingCfgCount += 1;
            this.isSavingCfg = true;

            try {
                const r = await fetch('postback-config.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-Token': this.csrf()
                    },
                    body: JSON.stringify({
                        postback_url: this.cfg.postback_url,
                        default_payout: Number(this.cfg.default_payout) || 0
                    })
                });

                if (!r.ok) {
                    // Rollback state on error
                    this.cfg.postback_url = previousUrl;
                    this.cfg.default_payout = previousPayout;

                    // Try to get error message from response
                    let errorMsg = 'Failed to save postback configuration (HTTP ' + r.status + ')';
                    const errorData = await this.safeJsonParse(r);
                    if (errorData && errorData.error) {
                        errorMsg = errorData.error;
                    }
                    this.setFlash(errorMsg, 'error');
                    return;
                }

                this.setFlash('Postback configuration saved', 'info');
            } catch (e) {
                // Rollback state on exception
                this.cfg.postback_url = previousUrl;
                this.cfg.default_payout = previousPayout;

                this.setFlash('Failed to save postback configuration: ' + e.message, 'error');
            } finally {
                this.savingCfgCount -= 1;
                if (this.savingCfgCount <= 0) {
                    this.savingCfgCount = 0;
                    this.isSavingCfg = false;
                }
            }
        },

        getPostbackExample() {
            if (!this.cfg.postback_url) {
                return 'Configure postback URL template above';
            }

            return this.cfg.postback_url
                .replace('{country}', 'US')
                .replace('{traffic_type}', 'WAP')
                .replace('{payout}', (Number(this.cfg.default_payout) || 0).toFixed(2));
        },

        async testPostback() {
            if (this.isTestingPostback) {
                return;
            }

            // Check if URL is configured
            const url = (this.cfg.postback_url || '').trim();
            if (url === '') {
                this.postbackTestResult = {
                    success: false,
                    message: 'Please configure postback URL first'
                };
                this.setFlash('Please configure postback URL before testing', 'error');
                return;
            }

            if (!url.startsWith('http://') && !url.startsWith('https://')) {
                this.postbackTestResult = {
                    success: false,
                    message: 'Postback URL must start with http:// or https://'
                };
                this.setFlash('Postback URL must start with http:// or https://', 'error');
                return;
            }

            // Auto-enable postback if not enabled
            if (!this.cfg.postback_enabled) {
                this.cfg.postback_enabled = true;
                await this.savePostback();
                // Brief delay to ensure setting is saved
                await new Promise(resolve => setTimeout(resolve, 300));
            }

            this.isTestingPostback = true;
            this.postbackTestResult = null;

            try {
                const r = await fetch('postback-config.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-Token': this.csrf()
                    },
                    body: JSON.stringify({
                        action: 'test',
                        country: this.postbackTest.country.toUpperCase(),
                        traffic_type: this.postbackTest.trafficType,
                        payout: Number(this.postbackTest.payout) || Number(this.cfg.default_payout) || 0
                    })
                });

                if (!r.ok) {
                    // Try to get error message from response
                    let errorMsg = 'HTTP ' + r.status;
                    const errorData = await this.safeJsonParse(r);
                    if (errorData && errorData.error) {
                        errorMsg = errorData.error;
                    }
                    this.postbackTestResult = {
                        success: false,
                        message: errorMsg
                    };
                    return;
                }

                const data = await this.safeJsonParse(r);

                if (data && data.ok) {
                    this.postbackTestResult = {
                        success: true,
                        message: data.message || 'Test postback sent successfully'
                    };
                    this.loadPostbackLogs();
                } else {
                    this.postbackTestResult = {
                        success: false,
                        message: data.error || 'Failed to send test postback'
                    };
                }
            } catch (e) {
                this.postbackTestResult = {
                    success: false,
                    message: 'Failed to send test postback: ' + e.message
                };
            } finally {
                this.isTestingPostback = false;
            }
        },

        async loadPostbackLogs() {
            // Stop retrying if too many errors
            if (this.postbackLoadErrors >= this.maxPostbackErrors) {
                return;
            }

            try {
                const r = await fetch('postback-config.php?action=logs', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const data = await this.safeJsonParse(r);

                if (data && data.ok) {
                    // Reset error counter on success
                    this.postbackLoadErrors = 0;

                    // Only update if changed to reduce DOM updates
                    const newLogs = Array.isArray(data.logs) ? data.logs : [];
                    if (JSON.stringify(this.postbackLogs) !== JSON.stringify(newLogs)) {
                        this.postbackLogs = newLogs;
                    }
                } else {
                    // Increment error counter
                    this.postbackLoadErrors++;
                    if (this.postbackLoadErrors >= this.maxPostbackErrors) {
                        console.error('Postback logs failed multiple times. Stopping retry. Error:', data.error);
                        // Show alert to user once
                        if (this.postbackLoadErrors === this.maxPostbackErrors) {
                            this.setFlash('Database table missing. Please run URGENT_FIX_NOW.sql in phpMyAdmin', 'error');
                        }
                    }
                }
            } catch (e) {
                // Increment error counter
                this.postbackLoadErrors++;
                console.error('Failed to load postback logs:', e);
            }
        },

        async loadReceivedPostbacks() {
            try {
                const r = await fetch('postback-config.php?action=received', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const data = await this.safeJsonParse(r);

                if (data && data.ok) {
                    // Only update if changed to reduce DOM updates
                    const newPostbacks = Array.isArray(data.logs) ? data.logs : [];
                    if (JSON.stringify(this.receivedPostbacks) !== JSON.stringify(newPostbacks)) {
                        this.receivedPostbacks = newPostbacks;
                    }
                }
            } catch (e) {
                // Silent fail for background refresh
                console.error('Failed to load received postbacks:', e);
            }
        },

        async loadDailyStats() {
            if (this.statsLoading) {
                return;
            }

            this.statsLoading = true;

            try {
                const r = await fetch(`postback-config.php?action=stats&days=${this.statsPeriod}&view=${this.statsView}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const data = await this.safeJsonParse(r);

                if (data && data.ok) {
                    this.dailyStats = Array.isArray(data.stats) ? data.stats : [];
                    if (data.summary) {
                        this.statsSummary = data.summary;
                    }
                }
            } catch (e) {
                // Silent fail for background refresh
                console.error('Failed to load daily stats:', e);
            } finally {
                this.statsLoading = false;
            }
        },

        async changeStatsPeriod(days) {
            this.statsPeriod = days;
            await this.loadDailyStats();
        },

        async changeStatsView(view) {
            this.statsView = view;
            await this.loadDailyStats();
        },

        // Statistics Methods
        getCountryStats() {
            const stats = {};
            this.logs.forEach(log => {
                const country = log.country_code || 'XX';
                if (!stats[country]) {
                    stats[country] = {
                        country: country,
                        total: 0,
                        decisionA: 0,
                        decisionB: 0
                    };
                }
                stats[country].total++;
                if (log.decision === 'A') {
                    stats[country].decisionA++;
                } else {
                    stats[country].decisionB++;
                }
            });

            return Object.values(stats).sort((a, b) => b.total - a.total);
        },

        getUniqueCountries() {
            const countries = new Set();
            this.logs.forEach(log => {
                if (log.country_code) {
                    countries.add(log.country_code);
                }
            });
            return Array.from(countries).sort();
        }
    }));
});
</script>

<?php require __DIR__ . '/components/footer.php'; ?>
