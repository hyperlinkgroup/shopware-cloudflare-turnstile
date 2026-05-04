# SsqCloudflareTurnstile – Cloudflare Turnstile für Shopware 6

[![Latest Version on Packagist](https://img.shields.io/packagist/v/hyperlink/cloudflare-turnstile.svg)](https://packagist.org/packages/hyperlink/cloudflare-turnstile)
[![Shopware 6.5](https://img.shields.io/badge/Shopware-6.5.x-189EFF.svg)](https://www.shopware.com)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](LICENSE)

Integriert [Cloudflare Turnstile](https://www.cloudflare.com/products/turnstile/) als datenschutzfreundliche CAPTCHA-Lösung in Shopware 6.

---

## Funktionsumfang

- **Cloudflare Turnstile** als CAPTCHA-Option unter *Einstellungen → Shop → Stammdaten → CAPTCHA*
- Konfigurierbarer **Widget-Modus**: Managed, Nicht-interaktiv oder Unsichtbar
- Konfigurierbares **Theme**: Automatisch, Hell oder Dunkel
- **Credentials-Test** direkt im Admin-Panel
- **Fail-open**: Bei Cloudflare-API-Ausfall werden Anfragen standardmäßig durchgelassen (konfigurierbar)
- Lokalisierte Fehlermeldungen (Deutsch & Englisch)
- Funktioniert auf allen Shopware-Formularen (Checkout, Kontakt, Registrierung, Newsletter etc.)

---

## Voraussetzungen

- Shopware 6.5.x
- PHP 8.1+
- Ein [Cloudflare Turnstile](https://dash.cloudflare.com/?to=/:account/turnstile)-Konto mit Site Key und Secret Key

---

## Installation

### Variante 1: Installation via Composer (empfohlen)

```bash
composer require hyperlink/cloudflare-turnstile
bin/console plugin:refresh
bin/console plugin:install --activate SsqCloudflareTurnstile
bin/console cache:clear
```

### Variante 2: Manuell in den Plugin-Ordner kopieren

1. **Repository klonen oder herunterladen:**

   ```bash
   git clone git@github.com:hyperlink/cloudflare-turnstile.git custom/plugins/SsqCloudflareTurnstile
   ```

2. **Plugin erkennen, installieren und aktivieren:**

   ```bash
   bin/console plugin:refresh
   bin/console plugin:install --activate SsqCloudflareTurnstile
   bin/console cache:clear
   ```

3. **Administration-Assets veröffentlichen** (optional – das Plugin enthält bereits eine vorgebaute JS-Datei):

   ```bash
   bin/console assets:install
   ```

### Variante 3: Installation via ZIP-Upload im Admin-Panel

1. **ZIP-Datei erstellen** (falls noch nicht vorhanden):

   ```bash
   cd custom/plugins
   zip -r SsqCloudflareTurnstile.zip SsqCloudflareTurnstile/ \
     -x "SsqCloudflareTurnstile/.git/*" \
     -x "SsqCloudflareTurnstile/vendor/*"
   ```

   Alternativ: Den neuesten Release als ZIP von der [Releases-Seite](https://github.com/hyperlink/cloudflare-turnstile/releases) herunterladen.

2. **Im Admin-Panel hochladen:**
   - Navigiere zu **Erweiterungen → Meine Erweiterungen**
   - Klicke oben rechts auf **„Erweiterung hochladen"**
   - Wähle die `SsqCloudflareTurnstile.zip` aus
   - Klicke auf **„Installieren"** und anschließend auf **„Aktivieren"**

---

## Konfiguration im Admin-Panel

1. Navigiere zu **Einstellungen → Shop → Stammdaten**
2. Scrolle nach unten zum Abschnitt **„CAPTCHA"**
3. Wähle im Multi-Select **„Cloudflare Turnstile"** aus
4. Es erscheinen folgende Konfigurationsfelder:

| Feld | Beschreibung |
|------|-------------|
| **Site Key** | Der öffentliche Schlüssel aus deinem [Cloudflare Dashboard](https://dash.cloudflare.com/?to=/:account/turnstile) |
| **Secret Key** | Der geheime Schlüssel aus deinem Cloudflare Dashboard |
| **API-Zugangsdaten überprüfen** | Button zum Testen, ob die eingegebenen Keys gültig sind |
| **Widget-Modus** | `Managed` (empfohlen) – Cloudflare entscheidet automatisch · `Nicht-interaktiv` – Widget ohne Nutzerinteraktion · `Unsichtbar` – kein sichtbares Widget |
| **Darstellung** | `Automatisch` · `Hell` · `Dunkel` |
| **Bei API-Ausfall Anfragen durchlassen** | Wenn aktiviert (Standard), werden Anfragen bei einem Cloudflare-API-Ausfall durchgelassen, damit der Shop weiter funktioniert. Wenn deaktiviert, werden Anfragen blockiert, bis die API wieder erreichbar ist. |

5. Klicke auf **„Speichern"**

---

## Cloudflare Turnstile Keys erstellen

1. Melde dich im [Cloudflare Dashboard](https://dash.cloudflare.com/) an
2. Navigiere zu **Turnstile** in der linken Sidebar
3. Klicke auf **„Widget hinzufügen"**
4. Gib einen Namen und die Domain deines Shops ein
5. Wähle den gewünschten Widget-Typ
6. Nach der Erstellung erhältst du **Site Key** und **Secret Key**

---

## Deinstallation

```bash
bin/console plugin:deactivate SsqCloudflareTurnstile
bin/console plugin:uninstall SsqCloudflareTurnstile
bin/console cache:clear
```

Bei der Deinstallation werden alle gespeicherten Konfigurationsdaten (inkl. Secret Keys) aus der Datenbank entfernt, sofern nicht „Benutzerdaten behalten" gewählt wird.

---

## Technische Details

- Das Plugin registriert sich über den Tag `shopware.storefront.captcha` im Shopware Captcha-Framework
- Die Captcha-Validierung erfolgt serverseitig über die [Cloudflare Siteverify API](https://developers.cloudflare.com/turnstile/get-started/server-side-validation/)
- Bei API-Ausfällen greift ein konfigurierbarer **Fail-open/Fail-closed**-Mechanismus: Standardmäßig werden Anfragen durchgelassen (Fail-open), damit der Shop weiter funktioniert. Kann im Admin unter der Checkbox „Bei API-Ausfall Anfragen durchlassen" deaktiviert werden, um stattdessen Anfragen zu blockieren (Fail-closed).
- Die Konfiguration wird in `core.basicInformation.activeCaptchasV2.cloudflareTurnstile` in der `system_config`-Tabelle gespeichert

---

## Mitwirken

Beiträge sind willkommen! Alle Details findest du in der [CONTRIBUTING.md](CONTRIBUTING.md).

---

## Lizenz

Dieses Projekt ist lizenziert unter der [MIT-Lizenz](LICENSE).
