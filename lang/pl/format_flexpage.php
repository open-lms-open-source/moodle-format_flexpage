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

$string['pluginname'] = 'Format Flexpage';
$string['defaultcoursepagename'] = 'Domyślna strona Flexpage (wprowadź zmiany)';
$string['pagenotfound'] = 'Ten kurs nie zawiera strony Flexpage o identyfikatorze = {$a}.';
$string['addmenu'] = 'Dodaj';
$string['managemenu'] = 'Zarządzaj';
$string['addactivityaction'] = 'Dodaj aktywność';
$string['addpagesaction'] = 'Dodaj strony Flexpage';
$string['managepagesaction'] = 'Zarządzaj wszystkimi stronami Flexpage';
$string['editpageaction'] = 'Zarządzaj ustawieniami stron Flexpage';
$string['editpage'] = 'Ustawienia strony Flexpage';
$string['movebefore'] = 'przed';
$string['moveafter'] = 'po';
$string['movechild'] = 'jako pierwsza jednostka podrzędna';
$string['ajaxexception'] = 'Błąd aplikacji: {$a}';
$string['addedpages'] = 'Dodane strony Flexpage: {$a}';
$string['addpages'] = 'Dodaj strony Flexpage';
$string['error'] = 'Błąd';
$string['genericasyncfail'] = 'Żądanie zostało odrzucone z nieznanej przyczyny. Spróbuj ponownie wykonać działanie.';
$string['close'] = 'Zamknij';
$string['gotoa'] = 'Przejdź do „{$a}”';
$string['movepageaction'] = 'Przenieś stronę Flexpage';
$string['movepage'] = 'Przenieś stronę Flexpage';
$string['movepagea'] = 'Przenieś stronę Flexpage <strong>{$a}</strong>';
$string['movedpage'] = 'Przeniesiona strona Flexpage „{$a->movepage}” {$a->move} strona Flexpage „{$a->refpage}”';
$string['addactivity'] = 'Dodaj aktywność';
$string['addactivity_help'] = 'Należy wybrać miejsce do umieszczenia nowej aktywności na stronie Flexpage za pomocą jednego z przycisków w górnej części okna <strong>Dodaj aktywność</strong>. Następnie należy wybrać aktywność lub zasób, które zostaną dodane do kursu oraz strony Flexpage.';
$string['addto'] = 'Dodaj do regionu:';
$string['addexistingactivity'] = 'Dodaj istniejącą aktywność';
$string['addexistingactivity_help'] = 'Należy wybrać miejsce do umieszczenia nowej aktywności na stronie Flexpage za pomocą jednego z przycisków w górnej części okna <strong>Dodaj istniejącą aktywność</strong>. Następnie należy zaznaczyć pole wyboru obok aktywności, które mają zostać dodane do danej strony Flexpage. Na końcu należy kliknąć przycisk „Dodaj aktywności” w dolnej części okna, aby zakończyć działanie.';
$string['addexistingactivityaction'] = 'Dodaj istniejącą aktywność';
$string['addactivities'] = 'Dodaj aktywności';
$string['addblock'] = 'Dodaj blok';
$string['addblock_help'] = 'Należy wybrać miejsce do umieszczenia nowej aktywności na stronie Flexpage za pomocą jednego z przycisków w górnej części okna <strong>Dodaj blok</strong>. Następnie należy kliknąć nazwę bloku, który ma zostać dodany do kursu.';
$string['addblockaction'] = 'Dodaj blok';
$string['block'] = 'Blok';
$string['displayhidden'] = 'Ukryty';
$string['displayvisible'] = 'Widoczny, ale niedostępny w menu';
$string['displayvisiblemenu'] = 'Widoczny i dostępny w menu';
$string['navnone'] = 'Brak nawigacji';
$string['navprev'] = 'Tylko poprzednia strona Flexpage';
$string['navnext'] = 'Tylko następna strona Flexpage';
$string['navboth'] = 'Poprzednia i następna strona Flexpage';
$string['navigation'] = 'Nawigacja';
$string['navigation_help'] = 'Pozwala wyświetlić na danej stronie Flexpage przyciski przejścia do następnej i/lub poprzedniej strony. Za pomocą przycisków użytkownik może przejść do kolejnej/poprzedniej dostępnej strony Flexpage.';
$string['display'] = 'Wyświetl';
$string['name'] = 'Nazwa';
$string['name_help'] = 'To nazwa strony Flexpage widoczna dla użytkowników kursu w menu itp.';
$string['formnamerequired'] = 'Nazwa strony Flexpage jest wymagana.';
$string['regionwidths'] = 'Szerokości regionów bloków';
$string['regionwidths_help'] = 'Można określić szerokość poszczególnych regionów bloków w pikselach. Na przykład szerokość lewego bloku może wynosić 200 pikseli, głównego — 500 pikseli, a prawego — 200 pikseli. Należy pamiętać, że dostępne regiony oraz ich nazwy mogą się zmieniać w zależności od motywu.';
$string['managepages'] = 'Zarządzaj stronami Flexpage';
$string['managepages_help'] = 'Z poziomu tego okna można wyświetlać indeks wszystkich stron Flexpage, korzystać z funkcji szybkiego zarządzania, przenosić lub usuwać pojedyncze strony Flexpage, a także zmienić ustawienia wyświetlania i kontrolować ustawienia nawigacji.';
$string['pagename'] = 'Nazwa strony Flexpage';
$string['deletepage'] = 'Usuń stronę Flexpage';
$string['deletepageaction'] = 'Usuń stronę Flexpage';
$string['areyousuredeletepage'] = 'Czy na pewno chcesz trwale usunąć stronę Flexpage <strong>{$a}</strong>?';
$string['deletedpagex'] = 'Usunięto stronę Flexpage „{$a}”';
$string['flexpage:managepages'] = 'Zarządzaj stronami Flexpage';
$string['pagexnotavailable'] = '{$a} jest niedostępna';
$string['pagenotavailable'] = 'Niedostępne';
$string['pagenotavailable_help'] = 'Użytkownik nie ma dostępu do tej strony Flexpage. Poniżej znajduje się lista warunków, jakie należy spełnić, aby wyświetlić tę stronę Flexpage.';
$string['sectionname'] = 'Sekcja';
$string['page'] = 'Strona Flexpage';
$string['copydotdotdot'] = 'Kopiuj...';
$string['nextpage'] = 'Następna >';
$string['next'] = 'Następne';
$string['previouspage'] = '< Poprzednia';
$string['previous'] = 'Poprzednie';
$string['themelayoutmissing'] = 'Bieżący motyw nie obsługuje formatu Flexpage. Zmień motyw (lub jeśli jest włączony, motyw kursu lub preferowany motyw profilu) na taki, który obsługuje układ „{$a}”.';
$string['deletemodwarn'] = 'Usunięcie tej aktywności spowoduje jej usunięcie ze wszystkich stron Flexpage.';
$string['continuedotdotdot'] = 'Kontynuuj...';
$string['warning'] = 'Ostrzeżenie';
$string['actionbar'] = 'Pasek działań';
$string['availablefrom'] = 'Zezwalaj na dostęp od';
$string['availablefrom_help'] = 'Ta strona Flexpage będzie dostępna dla użytkowników kursu po tej dacie.';
$string['availableuntil'] = 'Zezwalaj na dostęp do';
$string['availableuntil_help'] = 'Ta strona Flexpage będzie dostępna dla użytkowników kursu przed tą datą.';
$string['showavailability'] = 'Przed udostępnieniem tego elementu';
$string['showavailability_help'] = 'To ustawienie określa, czy w przypadku braku dostępu do strony Flexpage dla użytkownika będzie wyświetlana informacja o ograniczeniach tej strony.';
$string['nomoveoptionserror'] = 'Nie można przenieść tej strony Flexpage, ponieważ nie ma dostępnych pozycji, w których można by ją umieścić. Spróbuj dodać nowe strony Flexpage przed tą stroną lub po niej.';
$string['frontpage'] = 'Użyj formatu Flexpage na stronie tytułowej';
$string['frontpagedesc'] = 'Ta opcja pozwoli użyć formatu Flexpage na stronie tytułowej.';
$string['hidefromothers'] = 'Ukryj stronę Flexpage';
$string['showfromothers'] = 'Pokaż stronę Flexpage';
$string['jumptoflexpage'] = 'Przejdź do strony Flexpage';
$string['preventactivityview'] = 'Ta aktywność jest jeszcze niedostępna, ponieważ znajduje się ona na stronie Flexpage, do której użytkownik nie ma dostępu.';
$string['showavailability_hide'] = 'Ukryj całkowicie stronę Flexpage';
$string['showavailability_show'] = 'Pokaż stronę Flexpage jako nieaktywną, z ograniczeniem informacji';
$string['display_help'] = 'Skonfiguruj tę opcję, jeśli dana strona Flexpage jest:
<ol>
    <li>Całkowicie ukryta dla użytkowników innych niż edytorzy.</li>
    <li>Widoczna dla użytkowników kursu, ale niewyświetlana w menu modułu Flexpage ani w nawigacji kursu.</li>
    <li>Widoczna dla użytkowników kursu i wyświetlana w menu modułu Flexpage oraz w nawigacji kursu.</li>
</ol>';
$string['addpages_help'] = 'Z tego poziomu można dodawać nowe strony Flexpage do kursu. Na formularzu, począwszy od lewej strony:
 <ol>
    <li>Wprowadzić nazwę strony Flexpage (puste nazwy nie będą dodawane).</li>
    <li>Za pomocą kolejnych <em>dwóch</em> list rozwijanych można określić miejsce dodania nowej strony Flexpage w indeksie stron Flexpage. Nową stronę Flexpage można dodać przed inną stroną, po niej lub jako jej element podrzędny (podstronę).</li>
    <li>(Opcjonalnie) Z ostatniej listy rozwijanej można wybrać istniejącą stronę Flexpage, która zostanie skopiowana do nowo utworzonej strony Flexpage.</li>
</ol>
Aby jednocześnie dodać wiele stron Flexpage, należy kliknąć ikonę „+” i wypełnić nowy wiersz. W przypadku użycia ikony „+” zbyt wiele razy należy usunąć zawartość nazw pustych stron. Wówczas takie strony nie zostaną dodane.';
$string['actionbar_help'] = '
<p>Moduł Flexpage umożliwia projektantom kursów tworzenie wielu stron Flexpage w ramach kursu. Każda strona Flexpage zawiera niepowtarzalną lub współdzieloną zawartość.</p>

<p>Za pomocą paska działań można przechodzić do różnych stron Flexpage w obrębie kursu przez kliknięcie nazwy strony w menu rozwijanym.</p>

<p>Działania dostępne po wybraniu opcji <strong>Dodaj</strong> z menu paska działań:
    <ul>
        <li>Aby dodać stronę Flexpage, należy wybrać z menu rozwijanego łącze <strong>Dodaj strony Flexpage</strong>. Po dodaniu nowych stron należy określić ich lokalizację w indeksie stron Flexpage. Strony Flexpage mogą być elementami podrzędnymi innych stron (podstronami). Można je również umieszczać przed innymi stronami lub po nich. Nowe strony Flexpage mogą być puste lub zawierać kopię istniejącej strony Flexpage. Aby dodać wiele stron Flexpage do kursu, należy nacisnąć ikonę „+” z prawej strony okna <strong>Dodaj strony Flexpage</strong>.</li>
        <li>Aby dodać nową aktywność do danej strony Flexpage, należy wybrać z menu rozwijanego łącze <strong>Dodaj aktywność</strong>. Wybrać miejsce do umieszczenia nowej aktywności na stronie przez wybranie jednego z przycisków w górnej części okna <strong>Dodaj aktywność</strong>. Następnie wybrać aktywność lub zasób, które mają zostać dodane do kursu i strony Flexpage.</li>
        <li>Aby dodać istniejącą aktywność do danej strony Flexpage, należy wybrać z menu rozwijanego łącze <strong>Dodaj istniejącą aktywność</strong>. Wybrać miejsce do umieszczenia istniejącej aktywności na stronie Flexpage przez wybranie jednego z przycisków w górnej części okna <strong>Dodaj istniejącą aktywność</strong>. Następnie należy zaznaczyć pole wyboru obok aktywności, które mają zostać dodane do danej strony Flexpage. Na końcu należy kliknąć przycisk „Dodaj aktywności” w dolnej części okna, aby zakończyć działanie.</li>
        <li>Aby dodać blok do danej strony Flexpage, należy wybrać z menu rozwijanego łącze <strong>Dodaj blok</strong>. Należy wybrać miejsce do umieszczenia nowego bloku na stronie Flexpage przez wybranie jednego z przycisków w górnej części okna <strong>Dodaj blok</strong>. Następnie należy kliknąć nazwę bloku, który ma zostać dodany do kursu.</li>
        <li>Aby dodać istniejące menu do danej strony Flexpage, należy wybrać z rozwijanego menu łącze <strong>Dodaj istniejące menu</strong>. Należy wybrać miejsce do umieszczenia nowego menu na stronie Flexpage przez wybranie jednego z przycisków w górnej części okna <strong>Dodaj istniejące menu</strong>. Następnie należy kliknąć nazwę menu, które ma zostać dodane do kursu.</li>
    </ul>
</p>

<p>Działania dostępne po wybraniu opcji <strong>Zarządzaj</strong> z menu paska działań:
    <ul>
        <li>Aby skonfigurować ustawienia tej strony Flexpage, należy wybrać z menu rozwijanego łącze <strong>Zarządzaj ustawieniami stron Flexpage</strong>. Z poziomu tego okna można edytować nazwę strony Flexpage, zmienić szerokości obszarów strony Flexpage, określić, czy strona Flexpage ma być ukryta, widoczna czy widoczna w menu, a także zdefiniować, czy na stronach Flexpage będą widoczne przyciski nawigacyjne „poprzednia i następna”.</li>
        <li>Aby przenieść stronę Flexpage, należy wybrać z menu rozwijanego łącze <strong>Przenieś stronę Flexpage</strong>. Z poziomu tego okna można określić, czy dana strona Flexpage będzie elementem podrzędnym innej strony Flexpage czy zostanie umieszczona przed taką stroną Flexpage lub po niej w indeksie.</li>
        <li>Aby usunąć stronę Flexpage, należy wybrać z menu rozwijanego łącze <strong>Usuń stronę Flexpage</strong>. Z poziomu tego okna można potwierdzić chęć usunięcia bieżącej strony Flexpage.</li>
        <li>Aby zarządzać ustawieniami wielu stron Flexpage, należy wybrać z menu rozwijanego łącze <strong>Zarządzaj wszystkimi stronami Flexpage</strong>. Z poziomu tego okna można wyświetlić indeks stron Flexpage, skorzystać z funkcji szybkiego zarządzania, przenosić lub usuwać pojedyncze strony, zmieniać ustawienia wyświetlania oraz sterować ustawieniami nawigacji.</li>
        <li>Aby zarządzać kartami kursu oraz innymi menu, wybrać z menu rozwijanego łącze <strong>Zarządzaj wszystkimi menu</strong>. Z poziomu tego okna można tworzyć, edytować, usuwać menu oraz zarządzać łączami w menu.</li>
    </ul>
</p>';
$string['none'] = '(żaden)';
$string['grade_atleast'] = 'musi być co najmniej';
$string['grade_upto'] = 'i mniej niż';
$string['contains'] = 'zawiera';
$string['doesnotcontain'] = 'nie zawiera';
$string['isempty'] = 'jest pusty';
$string['isequalto'] = 'równa się';
$string['isnotempty'] = 'nie jest pusty';
$string['endswith'] = 'kończy się na';
$string['startswith'] = 'rozpoczyna się od';
$string['completion_complete'] = 'musi być oznaczona jako ukończona';
$string['completion_fail'] = 'musi być zakończona z oceną negatywną';
$string['completion_incomplete'] = 'nie może być oznaczona jako ukończona';
$string['completion_pass'] = 'musi być zakończona z oceną pozytywną';
$string['completioncondition'] = 'Warunek ukończenia aktywności';
$string['completioncondition_help'] = 'To ustawienie określa warunki ukończenia dowolnej aktywności, które muszą być spełnione, aby można było przejść na stronę Flexpage. Pamiętaj, że przed ustawieniem warunku ukończenia aktywności należy skonfigurować śledzenie ukończenia.

W razie potrzeby można ustawić wiele warunków ukończenia aktywności. W takim wypadku dostęp do strony Flexpage może być dozwolony tylko wówczas, gdy spełnione zostaną WSZYSTKIE warunki.';
$string['gradecondition'] = 'Warunki oceny';
$string['gradecondition_help'] = 'To ustawienie określa warunki oceny, które muszą być spełnione, aby można było uzyskać dostęp do strony Flexpage.

W razie potrzeby można ustawić wiele warunków. W takim wypadku dostęp do strony Flexpage może być tylko dozwolony tylko wówczas, gdy spełnione zostaną WSZYSTKIE warunki.';
$string['userfield'] = 'Pole użytkownika';
$string['userfield_help'] = 'Możesz ograniczyć dostęp oparty na dowolnym polu z profilu użytkownika.';
$string['releasecode'] = 'Kod udostępniania';
$string['releasecode_help'] = 'Ten przedmiot kursu będzie niedostępny dla studentów, dopóki nie uzyskają oni wprowadzonego tutaj kodu udostępniania.';
