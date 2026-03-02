/**
 * Session Manager
 * Handles idle time tracking (5 minutes) and time-of-day phase changes.
 */

(function () {
    // Configuration
    const IDLE_TIMEOUT_MS = 5 * 60 * 1000; // 5 minutes
    const CHECK_INTERVAL_MS = 1000; // Check every second

    let idleTime = 0;
    let currentPhase = getTimePhase();

    // Reset idle timer on user activity
    function resetIdleTimer() {
        idleTime = 0;
    }

    // Attach event listeners for activity
    const activityEvents = ['mousemove', 'keypress', 'click', 'scroll', 'touchstart'];
    activityEvents.forEach(event => {
        document.addEventListener(event, resetIdleTimer, { passive: true });
    });

    // Get current time phase
    function getTimePhase() {
        const hour = new Date().getHours();
        if (hour >= 5 && hour < 11) return 'pagi';
        if (hour >= 11 && hour < 15) return 'siang';
        if (hour >= 15 && hour < 19) return 'sore';
        return 'malam';
    }

    // Main loop
    setInterval(() => {
        idleTime += CHECK_INTERVAL_MS;

        // Check idle logout
        if (idleTime >= IDLE_TIMEOUT_MS) {
            // Redirect to logout
            window.location.href = 'auth/logout.php?reason=timeout';
            return;
        }

        // Check time phase change logout
        const newPhase = getTimePhase();
        if (newPhase !== currentPhase) {
            // Time phase changed (e.g. morning to afternoon), force re-login to update UI theme
            window.location.href = 'auth/logout.php?reason=timechange';
        }

    }, CHECK_INTERVAL_MS);

    console.log('Session manager initialized. Timeout: ' + (IDLE_TIMEOUT_MS / 1000 / 60) + ' minutes.');

    // Tab close beacon logout
    let isInternalNavigation = false;

    // Detect internal navigation clicks
    document.addEventListener('click', function (e) {
        let target = e.target.closest('a');
        if (target && target.href && !target.href.startsWith('javascript:') && !target.target) {
            isInternalNavigation = true;
        }
    });

    // Detect form submissions
    document.addEventListener('submit', function () {
        isInternalNavigation = true;
    });

    window.addEventListener('pagehide', function () {
        if (!isInternalNavigation) {
            navigator.sendBeacon('/survey-kependudukan/auth/logout_beacon.php');
        }
    });
})();
