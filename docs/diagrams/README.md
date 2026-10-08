# Diagramas de Inventoros — índice

Fuente: `docs/diagrams/inventoros-diagrams.html` (Mermaid, 14 bloques, verificados 14/14 válidos).

| # | Diagrama | Qué explica |
|---|---|---|
| 1 | Arquitectura C4 | Navegador Vue/Inertia → Controllers → Services → DB, Jobs, plugins, externos |
| 2 | ERD | Las 20+ entidades y sus relaciones (org, warehouse, producto/variantes, order, PO, RMA, WO) |
| 3 | Order lifecycle | pending → processing → shipped → delivered; cancel con guard anti-phantom-stock |
| 4 | Stock tracking | none/batch/serial → bins por ubicación + observers → webhooks/notifs |
| 5 | Supply chain | Reorder points automáticos → PO por supplier → receive parcial → invoice |
| 6 | RMA | pending → approved → received (por unidad) → completed (restock condicional) |
| 7 | Transfers | draft → in_transit → completed/cancelled (multi-almacén) |
| 8 | Work orders | ensamblaje: consume BOM, produce, cancel restaura; bloqueo por stock negativo |
| 9 | Report builder | config → preview → SavedReport → CSV export async |
| 10 | Import CSV | validación → job async → upsert → activity log |
| 11 | Webhooks | evento → do_action → HMAC POST → retry backoff |
| 12 | Plugins | upload → activate → add_action → slots de UI |
| 13 | Seguridad | auth → 2FA TOTP → permission middleware → org-scoping → warehouse access |
| 14 | DevOps | installer wizard → docker/cPanel → queue worker → scheduler de 5 commands → self-update firmado |
| 15 | Mapa de navegación UI | 93 páginas agrupadas por sección del sidebar con su permiso |
| 16 | Matriz roles→funcionalidad | Qué puede hacer cada perfil (view/create/edit/admin) |
| 17 | ⭐ Flujo central: Conteos y Auditoría | El corazón de tomfic: crear auditoría (snapshot congelado) → contar → discrepancia → ajuste trazeable |
| 18 | Tipos de auditoría | full / cycle / spot — cuándo usar cada uno |
| 19 | Ajustes manuales | fuera de auditoría: +/- con motivo |
| 20 | Bin-level stock | dónde viven los números: Product.stock global vs ProductLocationStock por bin + reconciliation |
| 21 | ⭐ Órdenes con aprobación | Doble ciclo: comercial (approval) + físico (ship con bins/serials/lotes) y las 3 guards de stock |
| 22 | ⭐ Cycle counting ABC | Cómo se clasifica A/B/C por valor+rotación y se arma el plan rotativo por zonas |
| 23 | Scheduler | Los 5 commands programados diarios |

## Flujos de negocio cubiertos
- **Ventas:** crear orden (stock por bin) → aprobación → envío (consume + serials/lotes) → factura → RMA
- **Compras:** sugerencia por reorder point → PO → recepción parcial con lotes → invoice
- **Producción:** work orders ensamblando kits con BOM → stock de componentes y de producto terminado
- **Movimientos internos:** transfers entre almacenes; audits/cycle counting; adjustments con reason codes
- **Administración:** usuarios/roles/permisos (30+ granulares), 2FA, plugins, webhooks, activity log, report builder, import/export, self-update firmado
