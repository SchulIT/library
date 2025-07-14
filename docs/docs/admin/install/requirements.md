---
sidebar_position: 1
---

# Voraussetzungen

Grundsätzlich muss SSH-Zugriff auf den Server vorhanden sein. Da die Software mit Hintergrundaufgaben arbeitet,
muss der Server die Ausführung von Hintergrundprozessen (bspw. mittels `systemd`) unterstützen.

## Software

* Webserver
  * Apache 2.4+ oder
  * nginx
* PHP 8.3+ mit folgenden Erweiterungen
  * bcmath
  * ctype
  * curl
  * date
  * dom
  * filter
  * gd
  * iconv
  * imagick
  * json
  * libxml
  * mbstring
  * openssl
* MariaDB 10.4+ (ein kompatibles MySQL kann funktionieren, ist jedoch nicht getestet)
* Composer 2+
* Git (zum Einspielen des Quelltextes)
* NodeJS >= 18 inkl. NPM (zum Erstellen der JavaScript- und CSS-Dateien)

Die Software muss auf einer Subdomain betrieben werden. Das Betreiben in einem Unterverzeichnis wird nicht unterstützt.

:::tip Hinweis
Theoretisch ist es auch ohne Git und NodeJS möglich, die Software zu installieren. Dazu kann der Quelltext mittels GitHub
heruntergeladen werden. Die Assets müssen dann jedoch auf einer Maschine erzeugt werden, wo Node und NPM verfügbar sind.
Dann muss das gesamte `/public/build`-Verzeichnis nach dem Erstellen der Assets auf den Webspace kopiert werden.
:::

## Installation auf Webspaces

Aktuell ist die Software mit ziemlich großer Wahrscheinlichkeit nicht auf Webspaces nutzbar, da dort Hintergrundaufgaben
(mittels Supervisor) nicht unterstützt werden.