# Automated checks (20 captures)

Leads, not verdicts: open the screenshot before filing a finding.

> **AUTH? signedin redirected on 4/20 routes; session may be stale**

Variants: `light`, `dark` (colorScheme=dark).

Texts not checked for contrast (over a background image or unresolvable colour): 0. Check those by eye.

Flags are prefixed with the standards they evidence: WCAG 2.2 success criterion, Nielsen heuristic (H1–H10) or Best practice. Times in ms; performance numbers are unthrottled lab measurements from one load (unless a variant throttles), not field data; INP is not measured. A focus indicator means a style change was detected, not that it is sufficient: confirm ≥ 3:1 by eye (1.4.11).

| Role | Variant | Route | Width | HTTP | Height | Perf (ms) | Standards failed | Flags | Screenshot |
|---|---|---|---|---|---|---|---|---|---|
| signedin | light | `/login` | 1440 | 200 | 1009 | LCP 6980 · CLS 0 · TTFB 3118 · DCL 6483 | Best practice, WCAG 1.4.3, H1 | redirected → /dashboard · [Best practice] heading skip: h1→h3 "Recent Orders" · [1.4.3] 47 low-contrast texts (worst 1.09:1 "Total Products") · nav without skip link (info: main landmark present) · [H1] slow LCP 6980 ms · slow TTFB 3118 ms | `ux-audit-paso6/signedin-light-login-1440-4d0a80.png` |
| signedin | light | `/dashboard` | 1440 | 200 | 1009 | LCP 1404 · CLS 0 · TTFB 1065 · DCL 1255 | Best practice, WCAG 1.4.3, H1 | [Best practice] heading skip: h1→h3 "Recent Orders" · [1.4.3] 47 low-contrast texts (worst 1.09:1 "Total Products") · nav without skip link (info: main landmark present) · [H1] slow TTFB 1065 ms | `ux-audit-paso6/signedin-light-dashboard-1440-66ee6a.png` |
| signedin | light | `/products` | 1440 | 200 | 900 | LCP 1420 · CLS 0 · TTFB 885 · DCL 1121 | WCAG 1.4.3, H1 | [1.4.3] 20 low-contrast texts (worst 1.84:1 "« Previous") · nav without skip link (info: main landmark present) · [H1] slow TTFB 885 ms | `ux-audit-paso6/signedin-light-products-1440-86554f.png` |
| signedin | light | `/orders` | 1440 | 200 | 900 | LCP 1184 · CLS 0 · TTFB 934 · DCL 1057 | WCAG 1.4.3, H1 | [1.4.3] 6 low-contrast texts (worst 1.86:1 "Search Orders") · nav without skip link (info: main landmark present) · [H1] slow TTFB 934 ms | `ux-audit-paso6/signedin-light-orders-1440-5e9413.png` |
| signedin | light | `/settings/account` | 1440 | 200 | 900 | LCP 1144 · CLS 0 · TTFB 911 · DCL 1034 | Best practice, WCAG 1.4.3, WCAG 1.3.5, H1, WCAG 1.4.10 | [Best practice] heading skip: h1→h3 "Profile Information" · [1.4.3] 10 low-contrast texts (worst 1.09:1 "Profile Information") · nav without skip link (info: main landmark present) · [1.3.5] 2 personal-data inputs without autocomplete (input[type=text]#name, input[type=email]#email); lead: verify the field asks about the user (1.3.5) · [H1] slow TTFB 911 ms · [1.4.10] overflow at 320px (397px wide; button.border-b-2) | `ux-audit-paso6/signedin-light-settings_account-1440-1d15a7.png` |
| signedin | light | `/login` | 1280 | 200 | 1009 | LCP 2208 · CLS 0 · TTFB 1661 · DCL 2017 | Best practice, WCAG 1.4.3, H1 | redirected → /dashboard · [Best practice] heading skip: h1→h3 "Recent Orders" · [1.4.3] 47 low-contrast texts (worst 1.09:1 "Total Products") · nav without skip link (info: main landmark present) · [H1] slow TTFB 1661 ms | `ux-audit-paso6/signedin-light-login-1280-4d0a80.png` |
| signedin | light | `/dashboard` | 1280 | 200 | 1009 | LCP 1408 · CLS 0 · TTFB 1147 · DCL 1284 | Best practice, WCAG 1.4.3, H1 | [Best practice] heading skip: h1→h3 "Recent Orders" · [1.4.3] 47 low-contrast texts (worst 1.09:1 "Total Products") · nav without skip link (info: main landmark present) · [H1] slow TTFB 1147 ms | `ux-audit-paso6/signedin-light-dashboard-1280-66ee6a.png` |
| signedin | light | `/products` | 1280 | 200 | 996 | LCP 1240 · CLS 0 · TTFB 923 · DCL 1082 | WCAG 1.4.3, H1 | [1.4.3] 20 low-contrast texts (worst 1.84:1 "« Previous") · nav without skip link (info: main landmark present) · [H1] slow TTFB 923 ms | `ux-audit-paso6/signedin-light-products-1280-86554f.png` |
| signedin | light | `/orders` | 1280 | 200 | 900 | LCP 1172 · CLS 0 · TTFB 916 · DCL 1065 | WCAG 1.4.3, H1 | [1.4.3] 6 low-contrast texts (worst 1.86:1 "Search Orders") · nav without skip link (info: main landmark present) · [H1] slow TTFB 916 ms | `ux-audit-paso6/signedin-light-orders-1280-5e9413.png` |
| signedin | light | `/settings/account` | 1280 | 200 | 900 | LCP 1544 · CLS 0 · TTFB 1264 · DCL 1387 | Best practice, WCAG 1.4.3, WCAG 1.3.5, H1, WCAG 1.4.10 | [Best practice] heading skip: h1→h3 "Profile Information" · [1.4.3] 10 low-contrast texts (worst 1.09:1 "Profile Information") · nav without skip link (info: main landmark present) · [1.3.5] 2 personal-data inputs without autocomplete (input[type=text]#name, input[type=email]#email); lead: verify the field asks about the user (1.3.5) · [H1] slow TTFB 1264 ms · [1.4.10] overflow at 320px (397px wide; button.border-b-2) | `ux-audit-paso6/signedin-light-settings_account-1280-1d15a7.png` |
| signedin | dark | `/login` | 1440 | 200 | 1009 | LCP 2408 · CLS 0 · TTFB 1904 · DCL 2213 | Best practice, WCAG 1.4.3, H1 | redirected → /dashboard · [Best practice] heading skip: h1→h3 "Recent Orders" · [1.4.3] 47 low-contrast texts (worst 1.09:1 "Total Products") · nav without skip link (info: main landmark present) · [H1] slow TTFB 1904 ms | `ux-audit-paso6/signedin-dark-login-1440-754c57.png` |
| signedin | dark | `/dashboard` | 1440 | 200 | 1009 | LCP 1700 · CLS 0 · TTFB 1309 · DCL 1484 | Best practice, WCAG 1.4.3, H1 | [Best practice] heading skip: h1→h3 "Recent Orders" · [1.4.3] 47 low-contrast texts (worst 1.09:1 "Total Products") · nav without skip link (info: main landmark present) · [H1] slow TTFB 1309 ms | `ux-audit-paso6/signedin-dark-dashboard-1440-629797.png` |
| signedin | dark | `/products` | 1440 | 200 | 900 | LCP 1368 · CLS 0 · TTFB 1067 · DCL 1208 | WCAG 1.4.3, H1 | [1.4.3] 20 low-contrast texts (worst 1.84:1 "« Previous") · nav without skip link (info: main landmark present) · [H1] slow TTFB 1067 ms | `ux-audit-paso6/signedin-dark-products-1440-b42e0b.png` |
| signedin | dark | `/orders` | 1440 | 200 | 900 | LCP 2672 · CLS 0 · TTFB 2320 · DCL 2510 | WCAG 1.4.3, H1 | [1.4.3] 6 low-contrast texts (worst 1.86:1 "Search Orders") · nav without skip link (info: main landmark present) · [H1] slow LCP 2672 ms · slow TTFB 2320 ms | `ux-audit-paso6/signedin-dark-orders-1440-93cbc3.png` |
| signedin | dark | `/settings/account` | 1440 | 200 | 900 | LCP 1936 · CLS 0 · TTFB 1673 · DCL 1812 | Best practice, WCAG 1.4.3, WCAG 1.3.5, H1, WCAG 1.4.10 | [Best practice] heading skip: h1→h3 "Profile Information" · [1.4.3] 10 low-contrast texts (worst 1.09:1 "Profile Information") · nav without skip link (info: main landmark present) · [1.3.5] 2 personal-data inputs without autocomplete (input[type=text]#name, input[type=email]#email); lead: verify the field asks about the user (1.3.5) · [H1] slow TTFB 1673 ms · [1.4.10] overflow at 320px (397px wide; button.border-b-2) | `ux-audit-paso6/signedin-dark-settings_account-1440-a53785.png` |
| signedin | dark | `/login` | 1280 | 200 | 1009 | LCP 3860 · CLS 0 · TTFB 3278 · DCL 3647 | Best practice, WCAG 1.4.3, H1 | redirected → /dashboard · [Best practice] heading skip: h1→h3 "Recent Orders" · [1.4.3] 47 low-contrast texts (worst 1.09:1 "Total Products") · nav without skip link (info: main landmark present) · [H1] slow LCP 3860 ms · slow TTFB 3278 ms | `ux-audit-paso6/signedin-dark-login-1280-754c57.png` |
| signedin | dark | `/dashboard` | 1280 | 200 | 1009 | LCP 2284 · CLS 0 · TTFB 1967 · DCL 2135 | Best practice, WCAG 1.4.3, H1 | [Best practice] heading skip: h1→h3 "Recent Orders" · [1.4.3] 47 low-contrast texts (worst 1.09:1 "Total Products") · nav without skip link (info: main landmark present) · [H1] slow TTFB 1967 ms | `ux-audit-paso6/signedin-dark-dashboard-1280-629797.png` |
| signedin | dark | `/products` | 1280 | 200 | 996 | LCP 2116 · CLS 0 · TTFB 1743 · DCL 1913 | WCAG 1.4.3, H1 | [1.4.3] 20 low-contrast texts (worst 1.84:1 "« Previous") · nav without skip link (info: main landmark present) · [H1] slow TTFB 1743 ms | `ux-audit-paso6/signedin-dark-products-1280-b42e0b.png` |
| signedin | dark | `/orders` | 1280 | 200 | 900 | LCP 2216 · CLS 0 · TTFB 1837 · DCL 2027 | WCAG 1.4.3, H1 | [1.4.3] 6 low-contrast texts (worst 1.86:1 "Search Orders") · nav without skip link (info: main landmark present) · [H1] slow TTFB 1837 ms | `ux-audit-paso6/signedin-dark-orders-1280-93cbc3.png` |
| signedin | dark | `/settings/account` | 1280 | 200 | 900 | LCP 1476 · CLS 0 · TTFB 1198 · DCL 1339 | Best practice, WCAG 1.4.3, WCAG 1.3.5, H1, WCAG 1.4.10 | [Best practice] heading skip: h1→h3 "Profile Information" · [1.4.3] 10 low-contrast texts (worst 1.09:1 "Profile Information") · nav without skip link (info: main landmark present) · [1.3.5] 2 personal-data inputs without autocomplete (input[type=text]#name, input[type=email]#email); lead: verify the field asks about the user (1.3.5) · [H1] slow TTFB 1198 ms · [1.4.10] overflow at 320px (397px wide; button.border-b-2) | `ux-audit-paso6/signedin-dark-settings_account-1280-a53785.png` |

## Summary by check (20 audited captures)

Only captures where the check actually ran are counted; "not run" means it was off, errored or could not run (never a pass).

| Check | Standards | Captures checked | Captures failing | Pass rate |
|---|---|---|---|---|
| Capture error (`error`) | info | 20 | 0 | 100% |
| Redirected (auth?) (`redirect`) | info | 20 | 4 | 80% |
| HTTP status >= 400 (`httpError`) | info | 20 | 0 | 100% |
| Checks that errored (not run) (`checkErrors`) | info | 20 | 0 | 100% |
| Heading structure (best practice; lead for 1.3.1/2.4.6) (`headings`) | Best practice | 20 | 12 | 40% |
| Horizontal overflow at capture width (`overflowX`) | H8 | 20 | 0 | 100% |
| Controls without accessible name (`unnamedControls`) | WCAG 4.1.2 | 20 | 0 | 100% |
| Images without alt (`imgAlt`) | WCAG 1.1.1 | 20 | 0 | 100% |
| Text contrast (`contrast`) | WCAG 1.4.3 | 20 | 20 | 0% |
| Texts not contrast-checked (`contrastSkipped`) | info | 20 | 0 | 100% |
| Target size < 24×24 without the spacing exception (`targetSize`) | WCAG 2.5.8 | 20 | 0 | 100% |
| <html lang> present (`lang`) | WCAG 3.1.1 | 20 | 0 | 100% |
| Descriptive <title> (`pageTitle`) | WCAG 2.4.2 | 20 | 0 | 100% |
| Duplicate ids referenced by label/aria-* (`duplicateIds`) | WCAG 4.1.2 | 20 | 0 | 100% |
| Main landmark or skip link (`bypassBlocks`) | WCAG 2.4.1 | 20 | 0 | 100% |
| Nav without skip link (main present) (`skipLink`) | info | 20 | 20 | 0% |
| Placeholder as the only label (`placeholderOnly`) | WCAG 3.3.2, H6 | 20 | 0 | 100% |
| Visible * without required/aria-required (`requiredUnmarked`) | WCAG 1.3.1, WCAG 3.3.2 | 20 | 0 | 100% |
| Personal-data inputs without autocomplete (lead) (`autocomplete`) | WCAG 1.3.5 | 20 | 4 | 80% |
| Visible focus indicator (change detected) (`focusVisible`) | WCAG 2.4.7 | 20 | 0 | 100% |
| Focus not obscured by fixed/sticky (`focusObscured`) | WCAG 2.4.11 | 20 | 0 | 100% |
| Tab walk stopped at an editable widget (`focusStopped`) | info | 20 | 0 | 100% |
| LCP > 2500 ms, CLS > 0.1 or TTFB > 800 ms (unthrottled lab) (`performance`) | H1 | 20 | 20 | 0% |
| Infinite animation ignores reduced motion (best practice; 2.3.3 is AAA) (`motion`) | Best practice | 20 | 0 | 100% |
| Reflow at 320 px (non-table overflow) (`reflow`) | WCAG 1.4.10 | 20 | 4 | 80% |
| Reflow overflow only from wide <table> (lead: check exception) (`reflowTable`) | info | 20 | 0 | 100% |
| Screenshot clipped at maxHeight (`clipped`) | info | 20 | 0 | 100% |
| Console errors (`consoleErrors`) | info | 20 | 0 | 100% |

Focus checks: on (up to 25 Tab stops; stops at editable widgets) · Reduced-motion check: on · Reflow at 320 px: on.
