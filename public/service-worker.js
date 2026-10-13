/**
 * Tomfic Capture PWA — service worker
 *
 * Estrategia por recurso:
 *  - App shell de captura (Capture.vue + assets build): precache + stale-while-revalidate.
 *  - API de captura (counting-rounds/*): network-only (los datos son transaccionales,
 *    la cola offline la maneja el cliente en localStorage y sincroniza por POST idempotente).
 *
 * El guardado offline y la sincronización viven en el front (capture-offline.js),
 * no aquí: el SW solo garantiza que el shell y las fuentes arranquen sin wifi.
 */
const CACHE_NAME = 'tomfic-shell-v1';

// El shell: la página de captura consume tokens/JS del build; lo agregamos por
// patrón en runtime (on fetch) a la caché la primera vez que se sirven.
const SHELL_CACHE_PATTERNS = [
    /\/build\/assets\/.*\.(js|css)$/,
    /manifest\.json$/,
    /images\/brand\/logo.+/,
];

self.addEventListener('install', (event) => {
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((keys) =>
            Promise.all(keys.filter((k) => k !== CACHE_NAME).map((k) => caches.delete(k)))
        ).then(() => self.clients.claim())
    );
});

self.addEventListener('fetch', (event) => {
    const { request } = event;

    if (request.method !== 'GET') {
        return; // mutaciones (capturas, closes): red siempre.
    }

    const url = new URL(request.url);

    // Solo rutas del mismo origen.
    if (url.origin !== self.location.origin) {
        return;
    }

    const isBuildAsset = SHELL_CACHE_PATTERNS.some((re) => re.test(url.pathname));

    if (isBuildAsset) {
        // stale-while-revalidate de assets de build y logo.
        event.respondWith(
            caches.match(request).then((cached) => {
                const fetchPromise = fetch(request)
                    .then((response) => {
                        if (response && response.status === 200 && response.type === 'basic') {
                            const copy = response.clone();
                            caches.open(CACHE_NAME).then((cache) => cache.put(request, copy));
                        }
                        return response;
                    })
                    .catch(() => cached);
                return cached || fetchPromise;
            })
        );
        return;
    }

    const isCaptureRoute = /\/counting-rounds\/\d+/.test(url.pathname)
        || /\/my\/counting-rounds/.test(url.pathname);

    if (isCaptureRoute) {
        // Network-first con fallback a caché para reabrir la pantalla sin wifi:
        // el estado real de la ronda vuelve a sincronizarse cuando hay red de nuevo.
        event.respondWith(
            fetch(request)
                .then((response) => {
                    if (response && response.status === 200 && response.type === 'basic') {
                        const copy = response.clone();
                        caches.open(CACHE_NAME).then((cache) => cache.put(request, copy));
                    }
                    return response;
                })
                .catch(() => caches.match(request))
        );
    }
});
