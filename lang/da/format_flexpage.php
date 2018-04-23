<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * @copyright  Copyright (c) 2018 Blackboard Inc. (http://www.blackboard.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'Flexpage-format';
$string['defaultcoursepagename'] = 'Standard-flexpage (Skift mig)';
$string['pagenotfound'] = 'Flexpage med id\'et = {$a} findes ikke i dette kursus.';
$string['addmenu'] = 'Tilføj';
$string['managemenu'] = 'Administrer';
$string['addactivityaction'] = 'Tilføj aktivitet';
$string['addpagesaction'] = 'Tilføj flexpages';
$string['managepagesaction'] = 'Administrer alle flexpages';
$string['editpageaction'] = 'Administrer flexpage-indstillinger';
$string['editpage'] = 'Flexpage-indstillinger';
$string['movebefore'] = 'før';
$string['moveafter'] = 'efter';
$string['movechild'] = 'som første underordnede af';
$string['ajaxexception'] = 'Programfejl: {$a}';
$string['addedpages'] = 'Tilføjede flexpages: {$a}';
$string['addpages'] = 'Tilføj flexpages';
$string['error'] = 'Fejl';
$string['genericasyncfail'] = 'Anmodningen mislykkedes af en ukendt årsag, prøv handlingen igen.';
$string['close'] = 'Luk';
$string['gotoa'] = 'Gå til "{$a}"';
$string['movepageaction'] = 'Flyt flexpage';
$string['movepage'] = 'Flyt flexpage';
$string['movepagea'] = 'Flyt flexpage <strong>{$a}</strong>';
$string['movedpage'] = 'Flyttet flexpage "{$a->movepage}" {$a->move} flexpage "{$a->refpage}"';
$string['addactivity'] = 'Tilføj aktivitet';
$string['addactivity_help'] = 'Vælg, hvor du vil placere den nye aktivitet på flexpagen ved at vælge en af knapperne øverst i vinduet <strong>Tilføj aktivitet</strong>. Vælg derefter aktiviteten eller ressourcen, som du vil føje til kurset og flexpagen.';
$string['addto'] = 'Føj til område:';
$string['addexistingactivity'] = 'Tilføj eksisterende aktivitet';
$string['addexistingactivity_help'] = 'Vælg, hvor du vil placere den eksisterende aktivitet på flexpagen ved at vælge en af knapperne øverst i vinduet <strong>Tilføj eksisterende aktivitet</strong>. Afkryds derefter felterne ved siden af aktiviteterne, som du vil føje til denne flexpage. Klik til slut på knappen "Tilføj aktiviteter" nederst i vinduet for at udføre handlingen.';
$string['addexistingactivityaction'] = 'Tilføj eksisterende aktivitet';
$string['addactivities'] = 'Tilføj aktiviteter';
$string['addblock'] = 'Tilføj blok';
$string['addblock_help'] = 'Vælg, hvor du vil placere den blokken på flexpagen ved at vælge en af knapperne øverst i vinduet <strong>Tilføj blok</strong>. Klik derefter på navnet på den blok, du vil føje til kurset.';
$string['addblockaction'] = 'Tilføj blok';
$string['block'] = 'Blok';
$string['displayhidden'] = 'Skjult';
$string['displayvisible'] = 'Synlig, men ikke i menuer';
$string['displayvisiblemenu'] = 'Synlig og i menuer';
$string['navnone'] = 'Ingen navigation';
$string['navprev'] = 'Kun forrige flexpage';
$string['navnext'] = 'Kun næste flexpage';
$string['navboth'] = 'Både forrige og næste flexpage';
$string['navigation'] = 'Navigation';
$string['navigation_help'] = 'Bruges til at vise knapperne næste og/eller forrige på denne flexpage.  Knapperne tager brugeren til den næste/forrige tilgængelige flexpage.';
$string['display'] = 'Vis';
$string['name'] = 'Navn';
$string['name_help'] = 'Dette er navnet på din flexpage, og den vises til kursusbrugere i menuer og lignende.';
$string['formnamerequired'] = 'Flexpage-navnet er et påkrævet felt.';
$string['regionwidths'] = 'Bredder for blokområde';
$string['regionwidths_help'] = 'Du kan angive i pixels, hvor bredt hvert blokområde kan være.  Et eksempel ville være at angive venstre til 200, hoved til 500 og højre til 200. Bemærk, at tilgængelige områder og deres navne kan ændre sig fra tema til tema.';
$string['managepages'] = 'Administrer flexpages';
$string['managepages_help'] = 'Fra dette vindue kan du få vist indekset over alle flexpages samt hurtigt administrere, flytte eller slette en individuel flexpage, ændre visningsindstillinger og kontrollere navigationsindstillinger.';
$string['pagename'] = 'Flexpage-navn';
$string['deletepage'] = 'Slet flexpage';
$string['deletepageaction'] = 'Slet flexpage';
$string['areyousuredeletepage'] = 'Er du sikker på, at du vil slette flexpage <strong>{$a}</strong> permanent?';
$string['deletedpagex'] = 'Slettede flexpage "{$a}"';
$string['flexpage:managepages'] = 'Administrer flexpages';
$string['pagexnotavailable'] = '{$a} er ikke tilgængelig';
$string['pagenotavailable'] = 'Ikke tilgængelig';
$string['pagenotavailable_help'] = 'Denne flexpage er ikke tilgængelig for dig.  Nedenfor kan der være en liste af betingelser, du skal opfylde for at kunne få vist flexpagen.';
$string['sectionname'] = 'Sektion';
$string['page'] = 'Flexpage';
$string['copydotdotdot'] = 'Kopiér ...';
$string['nextpage'] = 'Næste >';
$string['next'] = 'Næste';
$string['previouspage'] = '< Forrige';
$string['previous'] = 'Forrige';
$string['themelayoutmissing'] = 'Dit aktuelle tema understøtter ikke Flexpage.  Skift temaet (eller hvis aktiveret, kursustemaet eller dit foretrukne tema i din profil) til et, der har et "{$a}"-layout.';
$string['deletemodwarn'] = 'Hvis denne aktivitet slettes, fjernes den fra alle flexpages.';
$string['continuedotdotdot'] = 'Fortsæt ...';
$string['warning'] = 'Advarsel';
$string['actionbar'] = 'Handlingsmenu';
$string['availablefrom'] = 'Tillad adgang fra';
$string['availablefrom_help'] = 'Denne flexpage vil være tilgængelig for kursusbrugere efter denne dato.';
$string['availableuntil'] = 'Tillad adgang indtil';
$string['availableuntil_help'] = 'Denne flexpage vil være tilgængelig for kursusbrugere før denne dato.';
$string['showavailability'] = 'Før dette kan tilgås';
$string['showavailability_help'] = 'Hvis flexpagen er utilgængelig for brugeren, bestemmer denne indstilling, om der vises begrænsningsoplysninger eller slet ingenting for denne flexpage.';
$string['nomoveoptionserror'] = 'Du kan ikke flytte denne flexpage, da der ikke er nogen tilgængelige positioner til placering af denne flexpage.  Prøv at tilføje nye flexpages før eller efter denne flexpage.';
$string['frontpage'] = 'Brug Flexpage på forside';
$string['frontpagedesc'] = 'Dette aktiverer Flexpage-formatet på forsiden.';
$string['hidefromothers'] = 'Skjul flexpage';
$string['showfromothers'] = 'Vis flexpage';
$string['jumptoflexpage'] = 'Spring til en flexpage';
$string['preventactivityview'] = 'Du har ikke adgang til denne aktivitet endnu, da den er på en flexpage, der i øjeblikket ikke er tilgængelig for dig.';
$string['showavailability_hide'] = 'Skjul flexpage helt';
$string['showavailability_show'] = 'Vis flexpage nedtonet med begrænsningsoplysninger';
$string['display_help'] = 'Konfigurer, hvis denne flexpage er:
<ol>
<li>Helt skjult for ikke-redaktører.</li>
<li>Synlig for kursusbrugere, men vises ikke i Flexpage-menuer og kursusnavigation.</li>
<li>Synlig for kursusbrugere og vises i Flexpage-menuer og kursusnavigation.</li>
</ol>';
$string['addpages_help'] = 'Herfra kan du føje nye flexpages til dit kursus.  Fra venstre til højre på formularen:
<ol>
<li>Indtast navnet på din flexpage (tomme navne tilføjes ikke).</li>
<li>De næste <em>to</em> rullemenuer bestemmer, hvor i indekset for flexpages din nye flexpage tilføjes.  Så du kan
tilføje din nye flexpage før, efter eller som underordnet (under-flexpage) til en anden flexpage.</li>
<li>(Valgfri) I den sidste rullemenu kan du vælge en eksisterende flexpage, der skal kopieres ind i din netop oprettede flexpage.</li>
</ol>
Klik på ikonet "+" for at tilføje flere end en flexpage ad gangen, og udfyld den nye række.  Hvis du klikker på ikonet "+" for mange gange, lader du bare være med at udfylde flexpage-navnene, så tilføjes de ikke.';
$string['actionbar_help'] = '
<p>Med Flexpage kan kursusdesignere oprette flere flexpages inden for et kursus. Hver flexpage kan indeholde unikt eller delt indhold.</p>

<p>Med Handlingsmenuen kan du springe til forskellige flexpages inden for dette kursus ved at klikke på navnet på flexpagen i 
rullemenuen.</p>

<p>Tilgængelige handlinger under menuelementet <strong>Tilføj</strong> i Handlingsmenuen:
<ul>
<li>Vælg linket <strong>Tilføj aktiviteter</strong> fra rullemenuen for at tilføje en flexpage. Når du tilføjer flexpages
, skal du bestemme, hvor de skal placeres i indekset for flexpages. Flexpages kan være underordnet andre flexpages (under-flexpages). Eller de kan ganske enkelt være placeret før eller efter andre flexpages. Nye flexpages kan også være tomme, eller en kopi af en eksisterende flexpage. Tryk på ikonet "+" på højre side af vinduet <strong>Tilføj flexpages</strong> for at føje flere flexpages til et kursus.</li>
<li>Vælg <strong>Tilføj aktivitet</strong> fra rullemenuen for at føje en ny aktivitet til denne flexpage.  Vælg, hvor
du vil placere den nye aktivitet på flexpagen ved at vælge en af knapperne øverst i vinduet <strong>Tilføj aktivitet</strong>. Vælg derefter aktiviteten eller ressourcen, som du vil føje til kurset og flexpagen.</li>
<li>Vælg <strong>Tilføj eksisterende aktivitet</strong> fra rullemenuen for at føje en eksisterende aktivitet til denne flexpage.
Vælg, hvor du vil placere den eksisterende aktivitet på flexpagen ved at vælge en af knapperne øverst i vinduet <strong>Tilføj eksisterende aktivitet</strong>. Afkryds derefter felterne ved siden af aktiviteterne, som du vil føje til denne flexpage. Klik til slut på knappen "Tilføj aktiviteter" nederst i vinduet for at udføre handlingen.</li>
<li>Vælg <strong>Tilføj blok</strong> fra rullemenuen for at føje en blok til denne flexpage. Vælg, hvor du vil
placere den blokken på flexpagen ved at vælge en af knapperne øverst i vinduet <strong>Tilføj blok</strong>. Klik derefter på navnet på den blok, du vil føje til kurset.</li>
<li>Vælg <strong>Tilføj eksisterende menu</strong> fra rullemenuen for at føje en eksisterende menu til denne flexpage. Vælg
, hvor du vil placere blokken på flexpagen ved at vælge en af knapperne øverst i vinduet <strong>Tilføj eksisterende menu</strong>. Klik derefter på navnet på den menu, du vil føje til kurset.</li>
</ul>
</p>

<p>Tilgængelige handlinger under menuelementet <strong>Administrer</strong> i Handlingsmenuen:
<ul>
<li>Klik på linket <strong>Administrer flexpage-indstillinger</strong> fra 
rullemenuen for at konfigurere indstillingerne for denne flexpage. Fra dette vindue kan du redigere flexpage-navnet, ændre bredden på flexpage-områder, angive om flexpagen skal være skjult, synlig eller synlig i menuer, samt bestemme om flexpages skal have navigationsknapperne "forrige og næste".</li>
<li>Klik på linket <strong>Flyt flexpage</strong> fra rullemenuen for at flytte en flexpage. Fra dette vindue kan du
vælge, om flexpagen er underordnet en anden flexpage, eller om den er før eller efter en anden flexpage i indekset.</li>
<li>Klik på linket <strong>Slet flexpage</strong> fra rullemenuen for at slette en flexpage. Fra dette vindue kan du
bekræfte, at du vil slette den aktuelle flexpage.</li>
<li>Klik på linket <strong>Administrer alle flexpages</strong> fra rullemenuen
for at administrere indstillingerne for flere flexpages. Fra dette vindue kan du få vist indekset over alle flexpages samt hurtigt administrere, flytte eller slette en individuelle flexpages, ændre visningsindstillinger og kontrollere navigationsindstillinger.</li>
<li>Klik på linket <strong>Administrer alle menuer</strong> fra 
rullemenuen for at administrere faner for dit kursus samt andre menuer. Fra dette vindue kan du oprette menuer samt hurtigt redigere menuer, slette menuer og administrere links i menuer.</li>
</ul>
</p>';
$string['none'] = '(Ingen)';
$string['grade_atleast'] = 'skal være mindst';
$string['grade_upto'] = 'og mindre end';
$string['contains'] = 'indeholder';
$string['doesnotcontain'] = 'indeholder ikke';
$string['isempty'] = 'er tom';
$string['isequalto'] = 'er lig med';
$string['isnotempty'] = 'er ikke tom';
$string['endswith'] = 'ender på';
$string['startswith'] = 'begynder med';
$string['completion_complete'] = 'skal markeres som fuldført';
$string['completion_fail'] = 'skal være fuldført med karakter for ikke bestået';
$string['completion_incomplete'] = 'må ikke markeres som fuldført';
$string['completion_pass'] = 'skal være fuldført med karakter for bestået';
$string['completioncondition'] = 'Betingelse for gennemførelse af aktivitet';
$string['completioncondition_help'] = 'Denne indstilling bestemmer betingelser for gennemførelse af aktivitet, der skal opfyldes for at få adgang til flexpagen. Bemærk, at sporing af gennemførelse skal først angives for en betingelse, før gennemførelse af en aktivitet kan angives.

Flere betingelser for gennemførelse af aktivitet kan angives, hvis det ønskes.  Er dette tilfældet, tillades adgang til flexpagen kun, når ALLE betingelser for gennemførelse af aktivitet er opfyldt.';
$string['gradecondition'] = 'Karakterbetingelse';
$string['gradecondition_help'] = 'Denne indstilling bestemmer karakterbetingelser, der skal opfyldes for at få adgang til flexpagen.

Flere karakterbetingelser kan angives, hvis det ønskes. Er dette tilfældet, tillader flexpagen kun adgang, når ALLE karakterbetingelser er opfyldt.';
$string['userfield'] = 'Brugerfelt';
$string['userfield_help'] = 'Du kan begrænse adgang baseret på felt i brugerens profil.';
$string['releasecode'] = 'Frigivelseskode';
$string['releasecode_help'] = 'Dette kursuselement er ikke tilgængeligt for studerende, før den studerende opnår frigivelseskoden, der er indtastet her.';
