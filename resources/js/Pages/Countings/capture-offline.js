/**
 * Captura offline (tomfic Capture.vue) — cola local + sincronización idempotente.
 *
 * Se inyecta como módulo desde Capture.vue. La cola vive en localStorage con
 * clave por ronda. Cada pendiente guarda SU payload y se reenvía por el endpoint
 * real de captura; la unique(counting_round_id, product_id) del backend garantiza
 * que el reenvío REPLACES en vez de duplicar (idempotencia estructural).
 *
 * Reglas de merge (decision del debate 04b, B4): SIN client_uuid único; el
 * anti-overwrite es last-writer-wins por (round, product) — nunca hay dos
 * writers a la misma ronda-al-producto (asignación única por ronda).
 */

const QUEUE_KEY_PREFIX = 'tomfic.captura.queue.';

function queueKey(roundId) {
    return `${QUEUE_KEY_PREFIX}${roundId}`;
}

export function loadQueue(roundId) {
    try {
        return JSON.parse(localStorage.getItem(queueKey(roundId)) || '[]');
    } catch {
        return [];
    }
}

export function saveQueue(roundId, queue) {
    localStorage.setItem(queueKey(roundId), JSON.stringify(queue));
}

/** Encola una captura cuando la red falla. Devuelve # pendientes. */
export function enqueue(roundId, payload) {
    const queue = loadQueue(roundId);
    // Un pending por (round, product): el último manda (rescan local = replace).
    const idx = queue.findIndex((q) => q.product_id === payload.product_id);
    if (idx >= 0) {
        queue[idx] = { ...payload, queued_at: Date.now() };
    } else {
        queue.push({ ...payload, queued_at: Date.now() });
    }
    saveQueue(roundId, queue);
    return queue.length;
}

/** Sincroniza toda la cola. Devuelve # sincronizados. Falla silenciosamente los pendientes (siguen en cola). */
export async function sync(roundId, csrfToken) {
    const queue = loadQueue(roundId);
    if (!queue.length) return 0;

    const remaining = [];
    let synced = 0;

    for (const item of queue) {
        try {
            const res = await fetch('/counting-rounds/' + roundId + '/capture', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken,
                },
                credentials: 'same-origin',
                body: JSON.stringify({
                    product_id: item.product_id,
                    units: item.units,
                    boxes: item.boxes,
                }),
            });
            if (res.ok) {
                synced++;
            } else if (res.status === 422 || res.status === 404) {
                // La ronda se cerró o el producto ya no existe: soltar (no reintentar infinito).
                // Lo Visible: cuenta como resuelto con error observable en consola.
                console.warn('[capture-offline] item descartado', item, res.status);
                synced++;
            } else {
                remaining.push(item); // 5xx u otros: reintenta después
            }
        } catch {
            remaining.push(item);
        }
    }

    saveQueue(roundId, remaining);
    return synced;
}

export function pendingCount(roundId) {
    return loadQueue(roundId).length;
}
