---
sidebar_position: 20
---

# Updates

:::warning Achtung
Bitte immer zunächst prüfen, ob es eine entsprechende `UPDATE-X.XX.md` gibt, in der möglicherweise auf Inkompatibilitäten
oder Hinweise zum Update veröffentlicht werden.
:::

## Datensicherung

Bitte zunächst eine [Datensicherung](backup) anfertigen.

:::warning Achtung
Um ein Backup einzuspielen, muss die entsprechende Version des Quelltextes bekannt sein. Der folgende Befehl liefert
den aktuellen Git Commit-Hash, sodass dieser später wiederhergestellt werden kann:

```bash
$ git log --pretty=format:'%H' -n 1
```
:::

## Hintergrunddienste stoppen

Sofern die Hintergrunddienste z.B. mittels systemd realisiert werden, sollten diese zunächst gestoppt werden:

```bash
$ systemctl --user stop library-scheduler.service
$ systemctl --user stop library-background.service
```

## Quelltext aktualisieren

Der Quelltext wird mittels Git aktualisiert:

```bash
$ git pull
$ git checkout -b 1.0.0
```

Dabei ist `1.0.0` durch die entsprechende Version zu ersetzen.

## Abhängigkeiten aktualisieren

```bash
$ composer install --no-dev --classmap-authoritative --no-scripts
$ npm install
```

## CSS- und JavaScript-Dateien erstellen

```bash
$ npm run build
$ php bin/console assets:install
```

## Aktualisierung der Anwendung und Datenbank

```bash
# Cache leeren und aufwärmen
$ php bin/console cache:clear
# Datenbank migrieren
$ php bin/console doctrine:migrations:migrate --no-interaction
# Anwendung installieren (führt ggf. durch das Update neue Schritte aus - bisherige Schritte werden übersprungen)
$ php bin/console app:setup
```

:::success Erfolg
Die Anwendung ist nun aktualisiert.
:::

## Hintergrunddienste starten

Sofern die Hintergrunddienste z.B. mittels systemd realisiert werden, sollten diese wieder gestartet werden:

```bash
$ systemctl --user start library-background.service
$ systemctl --user start libray-scheduler.service
```

