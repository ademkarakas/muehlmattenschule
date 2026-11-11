# Kurzanleitung für das Redaktionsteam – Mühlmattenschule (`/admin`)

Willkommen im Verwaltungsbereich der Website der **Mühlmattenschule Freiburg-Hochdorf**. Über das Admin-Panel können Schulleitung, Sekretariat und Lehrkräfte sämtliche Inhalte ohne Programmierkenntnisse pflegen.

---

## 1. Anmeldung & Rollenübersicht

1. Öffnen Sie im Browser die Adresse **`/admin`** (z. B. `https://muehlmattenschule.de/admin`).
2. Melden Sie sich mit Ihrer dienstlichen E-Mail-Adresse und Ihrem Passwort an.
3. Rechts oben im Kopfbereich finden Sie jederzeit den Button **„Zur Website“**, um die öffentliche Ansicht in einem neuen Tab zu prüfen.

### Benutzerrollen im Überblick
| Rolle | Berechtigungen |
|---|---|
| **Lehrkraft** | Eigene Beiträge/Klassenaktivitäten (`Beiträge`) und `Fotoalben` für die eigene Schulklasse anlegen und bearbeiten. |
| **Sekretariat** | Alle Beiträge, Termine, Ankündigungen, Fotoalben, Schulseiten, Kollegium und Elternbriefe/Downloads verwalten. |
| **Schulleitung** | Vollzugriff auf alle redaktionellen Inhalte sowie auf die `Schul-Einstellungen` (inkl. Wartungsmodus). |
| **Administrator** | Vollzugriff inklusive `Benutzer & Rollen`. |

---

## 2. Neuen Beitrag oder Klassenbericht veröffentlichen (`Inhalte → Beiträge`)

1. Klicken Sie links im Menü auf **„Beiträge“** und oben rechts auf **„Neuer Beitrag“**.
2. **Titel & Kategorie:** Geben Sie eine aussagekräftige Überschrift ein und wählen Sie eine Rubrik (z. B. *Schulleben*, *Klassen & Ausflüge*, *Sport & Wettbewerbe*). Die gewählte Rubrik erscheint automatisch im Seitenleisten-Widget **„Beiträge nach Rubriken“**.
3. **Schulklasse / Jahrgang:** Optional können Sie eine Klasse eintragen (z. B. `Klasse 2b` oder `Klasse 4a & 4b`), damit Eltern gezielt nach Berichten dieser Klasse filtern können.
4. **Titelbild & Fotoalbum:** Laden Sie ein Titelbild hoch (EXIF-/GPS-Daten werden automatisch datenschutzkonform entfernt) und verknüpfen Sie bei Bedarf ein bestehendes Fotoalbum.
5. **Status:** Wählen Sie **„Veröffentlicht“** (sofort sichtbar) oder **„Entwurf“** (nur intern gespeichert). Nach dem Speichern können Sie oben rechts auf **„Im Web ansehen“** klicken.

---

## 3. Fotoalbum anlegen (`Medien & Dokumente → Fotoalben`)

1. Klicken Sie auf **„Fotoalben“ → „Neues Fotoalbum“**.
2. Tragen Sie **Albumtitel**, **Veranstaltungsdatum** und optional die **Schulklasse** ein.
3. Ziehen Sie im Feld **„Fotos“** bis zu **120 Bilder gleichzeitig** per Drag & Drop in das Upload-Feld. Das erste Bild dient automatisch als Titelbild des Albums.
4. **Wichtig (DSGVO-Pflichtfeld):** Aktivieren Sie den Schalter **„Einwilligung zur Fotoveröffentlichung liegt für alle abgebildeten Personen vor (DSGVO)“**. Ohne diese Bestätigung wird das Album niemals öffentlich angezeigt.
5. Setzen Sie den Status auf **„Veröffentlicht“** und speichern Sie.

---

## 4. Termine & Ankündigungen pflegen (`Inhalte → Termine` / `Ankündigungen`)

### Schultermine (`Termine`)
- Unter **„Termine“ → „Neuer Termin“** tragen Sie Titel, Start-/Enddatum, Veranstaltungsort, **Termin-Kategorie** (*Ferien / Schulfrei*, *Eltern & Gremien*, *Schulfest & Projekttag*, *Unterricht & Klasse*) sowie optional die betroffene **Klasse** ein.
- Alle veröffentlichten Termine erscheinen automatisch im rechten Startseiten-Widget **„Nächste Termine“** sowie unter `/termine` und können von Eltern mit einem Klick als **iCal-Kalenderdatei (`.ics`)** ins Smartphone übernommen werden.

### Eilmeldungen auf der Startseite (`Ankündigungen`)
- Unter **„Ankündigungen“** können Sie wichtige Hinweise (z. B. *Hitzefrei*, *Krankheitswelle*, *Anmeldewoche*) direkt unter dem Kopfbild der Startseite einblenden.
- Mit dem Schalter **„Auf Startseite aktiv“** in der Tabelle schalten Sie Meldungen mit einem Klick ein oder aus.

---

## 5. Schulseiten & Mega-Menü bearbeiten (`Schule & Seiten → Schulseiten`)

1. Unter **„Schulseiten“** finden Sie alle Unterseiten der drei Hauptmenüs:
   - **Unsere Schule** (Überblick & Profil, Unser Team, Gremien & Partner)
   - **Konzepte & Projekte** (Pädagogisches Konzept, Projekte & Förderung)
   - **Angebote & Betreuung** (Betreuung & Schulweg, AGs & Leseförderung)
2. Jede Seite besteht aus flexiblen **Inhaltsblöcken**, die Sie beliebig hinzufügen und per Drag & Drop umsortieren können:
   - **Textabschnitt (`rich_text`):** Überschrift und formatierter Text
   - **Hervorgehobene Infobox (`info_box`):** Türkisfarbener Hinweiskasten
   - **Großes Seitenbild (`image_banner`)** & **Fotogalerie (`image_gallery`)**
   - **Personenliste (`people_list`):** Automatische Darstellung aus dem Bereich *Kollegium & Kontakte*
   - **Akkordeon / Häufige Fragen (`accordion`)** & **Download-Liste (`downloads_box`)**

---

## 6. Elternbriefe & Formulare hochladen (`Medien & Dokumente → Elternbriefe & Formulare`)

1. Klicken Sie auf **„Elternbriefe & Formulare“ → „Neues Dokument“**.
2. Geben Sie den Titel (z. B. *Ferienplan 2026/27*) und die Kategorie (*Formulare*, *Elternbriefe*, *Betreuung*, *Schulordnung*) an.
3. Laden Sie die PDF-Datei hoch und aktivieren Sie **„Veröffentlicht“**.

---

## 7. Schul-Einstellungen & Wartungsmodus (`Schule & Seiten → Schul-Einstellungen`)

Unter **„Schul-Einstellungen“** verwalten Schulleitung und Administratoren in vier übersichtlichen Reitern:
1. **Stammdaten & Kontakt:** Schulname, Adresse, Telefon, Fax, E-Mail und Öffnungszeiten des Sekretariats.
2. **Startseite & Hero-Slider:** Begrüßungstitel, Einführungstext und die wechselnden großen Fotos auf der Startseite.
3. **Unterrichts- & Pausenzeiten:** Die Läuteordnung für das EduPage-Widget **„Unterrichts- & Pausenzeiten“** auf der Startseite.
4. **Wartungsmodus (`Wartungsmodus`):**
   - Wenn Sie den Schalter **„Website in den Wartungsmodus versetzen“** aktivieren (oder oben rechts den Schnell-Button nutzen), sehen normale Besucherinnen und Besucher eine freundliche, informative **Wartungsseite im Mühlmattenschule-Design** mit den Kontaktdaten des Sekretariats, den Öffnungszeiten und einer direkten Telefon-/E-Mail-Möglichkeit zur **Krankmeldung**.
   - **Wichtig für Sie:** Solange Sie im `/admin`-Bereich angemeldet sind, können Sie die gesamte Website weiterhin normal betrachten (oben erscheint lediglich ein dezenter Hinweisbalken *„Wartungsmodus aktiv – Vorschau für angemeldete Administratoren“*).

