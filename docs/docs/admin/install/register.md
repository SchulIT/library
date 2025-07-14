---
sidebar_position: 4
---

# Anwendung im Single Sign-On registrieren

Damit sich Benutzer anmelden können, muss die Anwendung im Single Sign-On registriert werden. 

## SAML-Zertifikat erstellen

Es wird ein selbst-signiertes Zertifikat mittels OpenSSL erzeugt. Dazu das folgende Kommando ausführen:

```bash
$ php bin/console app:create-certificate --type saml
```

Anschließend werden einige Daten abgefragt. Diese können abgesehen vom `commonName` frei gewählt werden:

* `countryName`, `stateOrProvinceName`, `localityName` geben den Standort der Schule an
* `organizationName` entspricht dem Namen der Schule
* `organizationalUnitName` entspricht der Fachabteilung der Schule, welche für die Administration zuständig ist
* `commonName` Domainname des ICC, bspw. `icc.schulit.de`
* `emailAddress` entspricht der E-Mail-Adresse des Administrators

:::info
Das Zertifikat ist standardmäßig 10 Jahre gültig.
:::

## Dienst beim Single Sign-On registrieren

:::warning Hinweis
Der folgende Schritt muss im Single Sign-On erledigt werden.
:::

### Dienst registrieren

Unter *Verwaltung ➜ Dienste* einen neuen SAML-Dienst erstellen.

Einige Metadaten lassen sich automatisiert laden, indem man zunächst die Metadaten-XML `https://library.schulit.de/saml/metadata.xml`
(`library.schulit.de` durch die Library-Domain ersetzen) einträgt und auf *Herunterladen* klicken.

Anschließend müssen noch der Name und eine passende Beschreibung eingetragen werden.

### Attribut für Rolle erstellen

Mittels Rollen wird konfiguriert, was Benutzer im ICC dürfen und was nicht. Diese werden als Attribut im Single Sign-On
gespeichert und entsprechend beim Anmelden am ICC weitergeleitet.

Unter *Verwaltung ➜ Attribute* ein neues Attribut erstellen.

| Option                                 | Wert                              |
|----------------------------------------|-----------------------------------|
| Name                                   | library-roles                     |
| Anzeigename                            | Rollen Schulbücher                |
| Beschreibung                           | *beliebig*                        |
| Benutzer können dieses Attribut ändern | ❌ Häckchen nicht setzen           |
| SAML Attribut-Name                     | urn:roles                         |
| Typ                                    | Auswahlfeld                       |
| Dienste                                | Hier den Library-Dienst auswählen |

Unter *Optionen* muss die Option *Mehrfach-Auswahl möglich* deaktiviert bleiben.

Folgende Optionen eintragen:

| Schlüssel                         | Wert                                                 |
|-----------------------------------|------------------------------------------------------|
| ROLE_USER                         | Benutzer (nur lesender Zugriff auf eigene Daten)     |
| ROLE_ADMIN                        | Benutzer mit Administrator-Rechten                   |
| ROLE_BORROWERS_ADMIN              | Benutzer darf Entleiher administrieren               |
| ROLE_BOOKS_ADMIN                  | Benutzer darf Bücher verwalten (ohne Ausleihe)       |
| ROLE_LENDER                       | Benutzer darf Ausleihen und Rückgaben durchfürhen    |

Eine genaue Erklärung zu den Rollen und wie diese zugewiesen werden sollten, gibt es im Abschnitt [Benutzerrollen](../roles).

### Single Sign-On hinterlegen

Damit die Software den Single Sign-On kennt, muss noch eine XML-Datei hinterlegt werden.

Unter *Verwaltung ➜ IdP Details* den XML-Teil in die Zwischenablage kopieren und den Inhalt in der Datei `saml/idp.xml`
im Library-Ordner hinterlegen.