// Firebase Cloud Messaging - Non-blocking Lazy Load
window.addEventListener('load', () => {
    // Check if browser supports notifications & service workers
    if (!('Notification' in window) || !('serviceWorker' in navigator)) {
        return;
    }

    // Only load Firebase if user has already granted permission or explicitly requests it
    if (Notification.permission === 'granted') {
        initFirebaseMessaging();
    }
});

async function initFirebaseMessaging() {
    try {
        const { initializeApp } = await import("https://www.gstatic.com/firebasejs/10.13.2/firebase-app.js");
        const { getMessaging, getToken } = await import("https://www.gstatic.com/firebasejs/10.13.2/firebase-messaging.js");

        const firebaseConfig = {
            apiKey: "AIzaSyCSizDxcdeW6kDoqzZjcIXh3kgyHUwAnGM",
            authDomain: "notifdev-b86ea.firebaseapp.com",
            projectId: "notifdev-b86ea",
            storageBucket: "notifdev-b86ea.appspot.com",
            messagingSenderId: "96136881763",
            appId: "1:96136881763:web:136652eaa55c4d2288ba56"
        };

        const app = initializeApp(firebaseConfig);
        const messaging = getMessaging(app);

        const currentToken = await getToken(messaging, { 
            vapidKey: 'BMqMkl2XsxFEdpIwRmXicwKuAO8GKg2vXuo3NQ58M5w9dI-d1uC8dIadam3hGBHLEpCgCzx8CX9XuRXiYTucP_E' 
        });

        if (currentToken) {
            storeToken(currentToken);
        }
    } catch (err) {
        // Silently log in debug, don't block UI or throw alerts
        if (console && console.debug) {
            console.debug('FCM registration status:', err.message);
        }
    }
}

function storeToken(token) {
    const metaCsrf = document.querySelector('meta[name="csrf-token"]');
    if (!metaCsrf) return;
    const csrf = metaCsrf.getAttribute('content');

    // Route in Tokabe is /token-notif
    fetch(window.location.origin + '/token-notif', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrf,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ token: token })
    }).catch(() => {});
}