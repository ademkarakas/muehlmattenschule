import { SiteLayout } from '@/layouts/site-layout';
import type { SharedInertiaProps } from '@/types/school';
import { Head, usePage } from '@inertiajs/react';
import type { ReactElement } from 'react';

/**
 * Umfassende Datenschutzerklärung der Mühlmattenschule Freiburg-Hochdorf
 * nach aktuellem Rechtsstand (DSGVO, TDDDG, LDSG Baden-Württemberg, SchulG BW).
 */
export default function DatenschutzPage(): ReactElement {
    const { school } = usePage<SharedInertiaProps>().props;

    return (
        <SiteLayout>
            <Head title="Datenschutzerklärung" />

            <div className="mx-auto max-w-4xl px-4 py-10">
                <header className="mb-8">
                    <span className="text-xs font-extrabold uppercase tracking-wider text-brand-700">
                        Datenschutz & Rechtliches · Stand: Aktuelle Gesetzgebung (DSGVO / TDDDG / LDSG BW)
                    </span>
                    <h1 className="mt-1 text-3xl sm:text-4xl font-extrabold text-slate-900">
                        Datenschutzerklärung
                    </h1>
                    <p className="mt-2 text-sm text-slate-600">
                        Informationen zur Verarbeitung personenbezogener Daten gemäß Art. 13 und Art. 14 der
                        Datenschutz-Grundverordnung (DSGVO), dem Landesdatenschutzgesetz Baden-Württemberg (LDSG BW)
                        sowie dem Telekommunikation-Digitale-Dienste-Datenschutz-Gesetz (TDDDG).
                    </p>
                </header>

                <div className="rounded-3xl border border-slate-200 bg-white p-6 md:p-10 space-y-8 text-sm leading-relaxed text-slate-700 shadow-xs">
                    {/* 1. Verantwortliche Stelle */}
                    <section className="space-y-2">
                        <h2 className="text-lg font-extrabold text-slate-900">
                            1. Name und Anschrift der verantwortlichen Stelle
                        </h2>
                        <p>
                            Verantwortlich im Sinne der Datenschutz-Grundverordnung (Art. 4 Nr. 7 DSGVO) und des
                            Landesdatenschutzgesetzes Baden-Württemberg (LDSG BW) ist die:
                        </p>
                        <div className="rounded-2xl border border-brand-100 bg-brand-50/60 p-4 text-slate-800">
                            <p className="font-extrabold text-brand-900">
                                {school.name} – {school.subtitle}
                            </p>
                            <p>{school.street}</p>
                            <p>{school.zip_city}</p>
                            <p className="mt-2">
                                <strong>Vertreten durch die Schulleitung:</strong> Rektorin Diana Blum
                            </p>
                            <p>
                                <strong>Telefon:</strong> {school.phone} · <strong>Telefax:</strong> +49 7665 932081
                            </p>
                            <p>
                                <strong>E-Mail:</strong>{' '}
                                <a href={`mailto:${school.email}`} className="font-semibold text-brand-700 underline">
                                    {school.email}
                                </a>
                            </p>
                        </div>
                        <p className="text-xs text-slate-500">
                            Schulträger für die äußeren Schulangelegenheiten (§ 27 SchulG BW): Stadt Freiburg im
                            Breisgau, Amt für Schule und Bildung, Fehrenbachallee 12, 79106 Freiburg im Breisgau.
                        </p>
                    </section>

                    {/* 2. Behördlicher Datenschutzbeauftragter */}
                    <section className="space-y-2">
                        <h2 className="text-lg font-extrabold text-slate-900">
                            2. Kontaktdaten der/des behördlichen Datenschutzbeauftragten (Art. 37 DSGVO)
                        </h2>
                        <p>
                            Unsere behördliche Datenschutzbeauftragte bzw. unseren behördlichen
                            Datenschutzbeauftragten erreichen Sie unter:
                        </p>
                        <div className="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                            <p className="font-bold text-slate-900">
                                Staatliches Schulamt Freiburg – Datenschutzbeauftragte/r
                            </p>
                            <p>Ottmannstraße 22, 79100 Freiburg im Breisgau</p>
                            <p className="mt-1">
                                <strong>E-Mail:</strong>{' '}
                                <a
                                    href="mailto:datenschutz@ssa-fr.kv.bwl.de"
                                    className="font-semibold text-brand-700 underline"
                                >
                                    datenschutz@ssa-fr.kv.bwl.de
                                </a>
                            </p>
                        </div>
                    </section>

                    {/* 3. Rechtsgrundlagen */}
                    <section className="space-y-2">
                        <h2 className="text-lg font-extrabold text-slate-900">
                            3. Zwecke und Rechtsgrundlagen der Datenverarbeitung
                        </h2>
                        <p>
                            Wir verarbeiten personenbezogene Daten beim Betrieb dieser Schulwebsite ausschließlich im
                            Einklang mit den geltenden datenschutzrechtlichen Bestimmungen:
                        </p>
                        <ul className="list-disc pl-5 space-y-1.5">
                            <li>
                                <strong>Wahrnehmung einer Aufgabe im öffentlichen Interesse (Art. 6 Abs. 1 UAbs. 1 lit. e DSGVO i.V.m. Art. 6 Abs. 3 DSGVO, § 4 LDSG BW sowie SchulG BW):</strong>{' '}
                                Bereitstellung dieses Informationsportals zur Erfüllung unseres öffentlichen Bildungs-
                                und Erziehungsauftrags sowie zur Unterrichtung von Eltern, Schülerinnen und Schülern und
                                der interessierten Öffentlichkeit über das Schulleben.
                            </li>
                            <li>
                                <strong>Einwilligung (Art. 6 Abs. 1 UAbs. 1 lit. a DSGVO):</strong> Soweit wir für
                                bestimmte Verarbeitungsvorgänge (insbesondere die Veröffentlichung von Personenfotos
                                aus dem Schulleben oder aus Klassenaktivitäten) eine ausdrückliche Einwilligung der
                                Erziehungsberechtigten bzw. der betroffenen Personen einholen, bildet Art. 6 Abs. 1
                                UAbs. 1 lit. a DSGVO die Rechtsgrundlage.
                            </li>
                            <li>
                                <strong>Erfüllung rechtlicher Verpflichtungen (Art. 6 Abs. 1 UAbs. 1 lit. c DSGVO):</strong>{' '}
                                Soweit die Verarbeitung zur Erfüllung gesetzlicher Pflichten (z. B. Telemedien-/Digitale-Dienste-Recht)
                                erforderlich ist.
                            </li>
                        </ul>
                    </section>

                    {/* 4. Hosting & Server-Log-Dateien */}
                    <section className="space-y-2">
                        <h2 className="text-lg font-extrabold text-slate-900">
                            4. Hosting in Deutschland und Bereitstellung der Website (Server-Logfiles)
                        </h2>
                        <p>
                            Diese Website wird bei der <strong>IONOS SE</strong> (Elgendorfer Str. 57, 56410 Montabaur)
                            ausschließlich in zertifizierten Rechenzentren innerhalb der Bundesrepublik Deutschland bzw.
                            der Europäischen Union betrieben. Mit dem Anbieter besteht ein Vertrag über die
                            Auftragsverarbeitung gemäß Art. 28 DSGVO.
                        </p>
                        <p>
                            Bei jedem Aufruf unserer Internetseite erfasst das System automatisiert technisch notwendige
                            Daten und Informationen vom Computersystem des aufrufenden Endgeräts (sogenannte
                            Server-Log-Dateien):
                        </p>
                        <ul className="list-disc pl-5 space-y-1">
                            <li>Browsertyp und verwendete Browserversion</li>
                            <li>Verwendetes Betriebssystem des zugreifenden Systems</li>
                            <li>Datum und Uhrzeit des Abrufs sowie aufgerufene Unterseite</li>
                            <li>Zuvor besuchte Seite (Referrer-URL, sofern vom Browser übermittelt)</li>
                            <li>IP-Adresse des anfragenden Endgeräts in gekürzter/anonymisierter Form</li>
                        </ul>
                        <p>
                            Die vorübergehende Speicherung dieser Daten ist technisch erforderlich, um die Auslieferung
                            der Website an Ihr Endgerät zu ermöglichen, die Funktionsfähigkeit und IT-Sicherheit zu
                            gewährleisten sowie etwaige Cyberangriffe abzuwehren (Rechtsgrundlage: Art. 6 Abs. 1 UAbs. 1
                            lit. e DSGVO i.V.m. § 4 LDSG BW). Eine Zusammenführung mit anderen Datenquellen oder eine
                            Auswertung zu Marketingzwecken findet nicht statt.
                        </p>
                    </section>

                    {/* 5. SSL-/TLS-Verschlüsselung */}
                    <section className="space-y-2">
                        <h2 className="text-lg font-extrabold text-slate-900">5. SSL- bzw. TLS-Verschlüsselung</h2>
                        <p>
                            Diese Seite nutzt aus Sicherheitsgründen und zum Schutz der Übertragung vertraulicher
                            Inhalte eine aktuelle SSL- bzw. TLS-Verschlüsselung (erkennbar an <code>https://</code> und
                            dem Schloss-Symbol in der Adresszeile Ihres Browsers). Dadurch können Daten, die Sie an uns
                            übermitteln, nicht von Dritten mitgelesen werden.
                        </p>
                    </section>

                    {/* 6. Cookies & TDDDG */}
                    <section className="space-y-2">
                        <h2 className="text-lg font-extrabold text-slate-900">
                            6. Technisch notwendige Cookies & Endeinrichtungen (§ 25 Abs. 2 Nr. 2 TDDDG)
                        </h2>
                        <p>
                            Unsere Schulwebsite verzichtet vollständig auf Analyse-, Tracking- oder Werbe-Cookies,
                            Browser-Fingerprinting sowie auf externe Schriftarten (keine Einbindung von Google Fonts von
                            Drittservern) oder Social-Media-Tracker.
                        </p>
                        <p>
                            Es werden ausschließlich technisch unbedingt erforderliche Sitzungs-Cookies (Session-Cookie
                            und <code>XSRF-TOKEN</code> zum Schutz vor Cross-Site-Request-Forgery-Angriffen) eingesetzt,
                            die beim Schließen des Browsers bzw. nach Ablauf der Sitzung automatisch gelöscht werden.
                            Der Einsatz dieser technisch notwendigen Cookies ist gemäß{' '}
                            <strong>§ 25 Abs. 2 Nr. 2 TDDDG</strong> (Telekommunikation-Digitale-Dienste-Datenschutz-Gesetz)
                            sowie Art. 6 Abs. 1 UAbs. 1 lit. e DSGVO i.V.m. § 4 LDSG BW ohne gesonderte Einwilligung
                            zulässig, da er ausschließlich der sicheren technischen Bereitstellung des von Ihnen
                            ausdrücklich gewünschten digitalen Dienstes dient.
                        </p>
                    </section>

                    {/* 7. Kontaktaufnahme & Krankmeldungen */}
                    <section className="space-y-2">
                        <h2 className="text-lg font-extrabold text-slate-900">
                            7. Kontaktaufnahme per E-Mail, Telefon oder Telefax
                        </h2>
                        <p>
                            Wenn Sie mit unserem Sekretariat, der Schulleitung, den Lehrkräften oder der
                            Schulsozialarbeit per E-Mail, Telefon oder Telefax Kontakt aufnehmen (z. B. für allgemeine
                            Anfragen oder schulische Mitteilungen), werden die von Ihnen mitgeteilten personenbezogenen
                            Daten (Name, Kontaktdaten, Name und Klasse des Kindes sowie Inhalt der Nachricht)
                            ausschließlich zum Zweck der Bearbeitung Ihres Anliegens verarbeitet.
                        </p>
                        <p>
                            Rechtsgrundlage ist Art. 6 Abs. 1 UAbs. 1 lit. e DSGVO i.V.m. § 4 LDSG BW und dem
                            Schulgesetz Baden-Württemberg. Die Daten werden gelöscht, sobald sie für die Erreichung des
                            Zweckes ihrer Erhebung nicht mehr erforderlich sind und keine gesetzlichen
                            Aufbewahrungsfristen entgegenstehen.
                        </p>
                    </section>

                    {/* 8. Veröffentlichung von Fotos & Schülerarbeiten */}
                    <section className="space-y-2">
                        <h2 className="text-lg font-extrabold text-slate-900">
                            8. Veröffentlichung von Fotos, Berichten und Personenabbildungen (Art. 6 Abs. 1 lit. a DSGVO & §§ 22, 23 KUG)
                        </h2>
                        <p>
                            Auf unserer Website berichten wir in den Bereichen „Aktuelles“, „Nachrichten & Klassen“ und
                            „Fotogalerie“ über das Schulleben, Ausflüge, Projekte und Feste. Dabei gilt:
                        </p>
                        <ul className="list-disc pl-5 space-y-1.5">
                            <li>
                                Fotos, auf denen Schülerinnen und Schüler individuell erkennbar abgebildet sind, werden{' '}
                                <strong>ausschließlich nach vorheriger schriftlicher Einwilligung</strong> der
                                Erziehungsberechtigten gemäß Art. 6 Abs. 1 UAbs. 1 lit. a DSGVO veröffentlicht.
                            </li>
                            <li>
                                Unser Redaktionssystem erzwingt vor jeder Veröffentlichung eines Fotoalbums technisch die
                                Bestätigung, dass sämtliche erforderlichen Einwilligungserklärungen vorliegen. Zudem
                                werden Kamerametadaten und Standortdaten (EXIF-/GPS-Daten) bei der Bildverarbeitung
                                entfernt.
                            </li>
                            <li>
                                <strong>Jederzeitiges Widerrufsrecht (Art. 7 Abs. 3 DSGVO):</strong> Eine erteilte
                                Einwilligung kann jederzeit ohne Angabe von Gründen mit Wirkung für die Zukunft
                                schriftlich oder per E-Mail gegenüber der Schulleitung (
                                <a href={`mailto:${school.email}`} className="text-brand-700 underline">
                                    {school.email}
                                </a>
                                ) widerrufen werden. Im Falle eines Widerrufs wird das betreffende Bild unverzüglich von
                                der Website entfernt. Durch den Widerruf wird die Rechtmäßigkeit der bis zum Widerruf
                                erfolgten Verarbeitung nicht berührt.
                            </li>
                        </ul>
                    </section>

                    {/* 9. Schul-App & Externe Links */}
                    <section className="space-y-2">
                        <h2 className="text-lg font-extrabold text-slate-900">
                            9. Eltern-App „Stay Informed“ und externe Verlinkungen (OpenStreetMap)
                        </h2>
                        <p>
                            <strong>Eltern-App „Stay Informed“:</strong> Für den digitalen Versand von Elternbriefen und
                            Terminen nutzt die Mühlmattenschule auf freiwilliger Basis (Art. 6 Abs. 1 UAbs. 1 lit. a
                            DSGVO) die Schul-App der Stay Informed GmbH (firmensitz in Freiburg im Breisgau), mit der
                            ein Auftragsverarbeitungsvertrag nach Art. 28 DSGVO besteht. Beim reinen Besuch unserer
                            Schulwebsite findet keinerlei Datenübermittlung an die Stay Informed GmbH statt.
                        </p>
                        <p>
                            <strong>Externe Links (z. B. OpenStreetMap auf der Kontaktseite):</strong> Aus
                            Datenschutzgründen betten wir keine externen Kartendienste direkt per iFrame in unsere
                            Website ein. Auf unserer Kontaktseite befindet sich lediglich ein externer Textlink zu
                            OpenStreetMap (OpenStreetMap Foundation). Erst wenn Sie aktiv auf diesen Link klicken,
                            verlassen Sie unsere Website, und Ihre IP-Adresse wird an die Server von OpenStreetMap
                            übertragen.
                        </p>
                    </section>

                    {/* 10. Betroffenenrechte */}
                    <section className="space-y-2">
                        <h2 className="text-lg font-extrabold text-slate-900">
                            10. Ihre Rechte als betroffene Person (Art. 15 bis 21 DSGVO)
                        </h2>
                        <p>
                            Ihnen sowie Ihrem Kind stehen gegenüber der Schule bei Vorliegen der gesetzlichen
                            Voraussetzungen folgende Rechte nach der DSGVO zu:
                        </p>
                        <ul className="list-disc pl-5 space-y-1.5">
                            <li>
                                <strong>Recht auf Auskunft (Art. 15 DSGVO):</strong> Sie haben das Recht, Auskunft über
                                die von uns verarbeiteten personenbezogenen Daten zu verlangen.
                            </li>
                            <li>
                                <strong>Recht auf Berichtigung (Art. 16 DSGVO):</strong> Sie können unverzüglich die
                                Berichtigung unrichtiger oder die Vervollständigung Ihrer bei uns gespeicherten Daten
                                verlangen.
                            </li>
                            <li>
                                <strong>Recht auf Löschung (Art. 17 DSGVO):</strong> Sie können die Löschung Ihrer
                                personenbezogenen Daten verlangen, soweit nicht die Verarbeitung zur Erfüllung einer
                                rechtlichen Verpflichtung oder einer Aufgabe im öffentlichen Interesse erforderlich ist.
                            </li>
                            <li>
                                <strong>Recht auf Einschränkung der Verarbeitung (Art. 18 DSGVO):</strong> Sie haben das
                                Recht, unter den gesetzlichen Voraussetzungen die Einschränkung der Verarbeitung zu
                                verlangen.
                            </li>
                            <li>
                                <strong>Recht auf Datenübertragbarkeit (Art. 20 DSGVO):</strong> Sofern die Verarbeitung
                                auf einer Einwilligung beruht und mithilfe automatisierter Verfahren erfolgt.
                            </li>
                            <li>
                                <strong>Recht auf Widerruf einer Einwilligung (Art. 7 Abs. 3 DSGVO):</strong> Sie haben
                                das Recht, eine einmal erteilte Einwilligung jederzeit mit Wirkung für die Zukunft zu
                                widerrufen.
                            </li>
                        </ul>

                        <div className="mt-3 rounded-2xl border border-amber-300 bg-amber-50 p-4 text-amber-950">
                            <p className="font-extrabold">
                                Hinweis auf Ihr Widerspruchsrecht gemäß Art. 21 DSGVO
                            </p>
                            <p className="mt-1 text-xs sm:text-sm leading-relaxed">
                                Soweit Ihre personenbezogenen Daten auf Grundlage von Art. 6 Abs. 1 UAbs. 1 lit. e DSGVO
                                (Wahrnehmung einer Aufgabe im öffentlichen Interesse) verarbeitet werden, haben Sie das
                                Recht, gemäß Art. 21 DSGVO aus Gründen, die sich aus Ihrer besonderen Situation ergeben,
                                jederzeit gegen die Verarbeitung Widerspruch einzulegen. Wir verarbeiten die
                                personenbezogenen Daten dann nicht mehr, es sei denn, wir können zwingende schutzwürdige
                                Gründe für die Verarbeitung nachweisen, die Ihre Interessen, Rechte und Freiheiten
                                überwiegen.
                            </p>
                        </div>
                    </section>

                    {/* 11. Beschwerderecht */}
                    <section className="space-y-2">
                        <h2 className="text-lg font-extrabold text-slate-900">
                            11. Beschwerderecht bei der Datenschutz-Aufsichtsbehörde (Art. 77 DSGVO)
                        </h2>
                        <p>
                            Unbeschadet eines anderweitigen verwaltungsrechtlichen oder gerichtlichen Rechtsbehelfs
                            steht Ihnen gemäß Art. 77 DSGVO das Recht auf Beschwerde bei der zuständigen
                            Datenschutz-Aufsichtsbehörde zu, wenn Sie der Ansicht sind, dass die Verarbeitung der Sie
                            oder Ihr Kind betreffenden personenbezogenen Daten gegen die DSGVO verstößt:
                        </p>
                        <div className="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                            <p className="font-bold text-slate-900">
                                Der Landesbeauftragte für den Datenschutz und die Informationsfreiheit
                                Baden-Württemberg (LfDI)
                            </p>
                            <p>Hausanschrift: Lautenschlagerstraße 20, 70173 Stuttgart</p>
                            <p>Postanschrift: Postfach 10 29 32, 70025 Stuttgart</p>
                            <p className="mt-1">
                                <strong>Telefon:</strong> 0711 / 61 55 41-0 · <strong>E-Mail:</strong>{' '}
                                <a
                                    href="mailto:poststelle@lfdi.bwl.de"
                                    className="font-semibold text-brand-700 underline"
                                >
                                    poststelle@lfdi.bwl.de
                                </a>
                            </p>
                        </div>
                    </section>
                </div>
            </div>
        </SiteLayout>
    );
}
