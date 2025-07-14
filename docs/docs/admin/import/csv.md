---
sidebar_position: 1
---

# CSV-Import

Um Entleiher zu importieren, muss eine CSV-Datei nach dem folgenden Muster erstellt werden (bspw. über das Schulverwaltugnsprogramm):

```csv
ID;Vorname;Nachname;Klasse;E-Mail
...
```

Als `ID` muss eine eindeutige ID vergeben werden, die sich zu keiner Zeit ändern darf. Diese wird in der Regel durch
das Schulverwaltungsprogramm vergeben. Anhand der `ID` können bestehende Entleiher bei erneutem Import erkannt werden,
um bei Bedarf Daten aktualisieren zu können.

Eine `E-Mail`-Adresse wird benötigt, um (a) Benutzer einem Entleiher zuzuordnen und (b) E-Mails zu verschicken (sobald dies
implementiert ist).

