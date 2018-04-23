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

$string['pluginname'] = 'Formato flexpage';
$string['defaultcoursepagename'] = 'Flexpage predefinita (cambia)';
$string['pagenotfound'] = 'La flexpage con ID = {$a} non esiste in questo corso.';
$string['addmenu'] = 'Aggiungi';
$string['managemenu'] = 'Gestisci';
$string['addactivityaction'] = 'Aggiungi attività';
$string['addpagesaction'] = 'Aggiungi flexpage';
$string['managepagesaction'] = 'Gestisci tutte le flexpage';
$string['editpageaction'] = 'Gestisci impostazioni flexpage';
$string['editpage'] = 'Impostazioni flexpage';
$string['movebefore'] = 'prima';
$string['moveafter'] = 'dopo';
$string['movechild'] = 'come primo figlio di';
$string['ajaxexception'] = 'Errore di applicazione: {$a}';
$string['addedpages'] = 'Flexpage aggiunte: {$a}';
$string['addpages'] = 'Aggiungi flexpage';
$string['error'] = 'Errore';
$string['genericasyncfail'] = 'Richiesta non riuscita per motivo sconosciuto. Riprova.';
$string['close'] = 'Chiudi';
$string['gotoa'] = 'Vai a "{$a}"';
$string['movepageaction'] = 'Sposta flexpage';
$string['movepage'] = 'Sposta flexpage';
$string['movepagea'] = 'Sposta flexpage <strong>{$a}</strong>';
$string['movedpage'] = 'Spostata la flexpage "{$a->movepage}" {$a->move} flexpage "{$a->refpage}"';
$string['addactivity'] = 'Aggiungi attività';
$string['addactivity_help'] = 'Scegli dove posizionare la nuova attività sulla flexpage selezionando uno dei pulsanti nella parte superiore della finestra <strong>Aggiungi attività</strong>. In seguito, seleziona l\'attività o la risorsa che intendi aggiungere al corso e alla flexpage.';
$string['addto'] = 'Aggiungi ad area geografica:';
$string['addexistingactivity'] = 'Aggiungi attività esistente';
$string['addexistingactivity_help'] = 'Scegli dove posizionare l\'attività esistente sulla flexpage selezionando uno dei pulsanti nella parte superiore della finestra <strong>Aggiungi attività esistente</strong>. In seguito, posiziona un segno di spunta accanto alle attività che desideri aggiungere a questa flexpage. Infine, fai clic sul pulsante "Aggiungi attività" nella parte inferiore della finestra per completare l\'operazione.';
$string['addexistingactivityaction'] = 'Aggiungi attività esistente';
$string['addactivities'] = 'Aggiungi attività';
$string['addblock'] = 'Aggiungi un blocco';
$string['addblock_help'] = 'Scegli dove posizionare il blocco sulla flexpage selezionando uno dei pulsanti nella parte superiore della finestra <strong>Aggiungi blocco</strong>. In seguito, fai clic sul nome del blocco che desideri aggiungere al corso.';
$string['addblockaction'] = 'Aggiungi un blocco';
$string['block'] = 'Blocco';
$string['displayhidden'] = 'Nascosto';
$string['displayvisible'] = 'Visibile ma non nei menu';
$string['displayvisiblemenu'] = 'Visibile e nei menu';
$string['navnone'] = 'Nessuna navigazione';
$string['navprev'] = 'Solo flexpage precedente';
$string['navnext'] = 'Solo flexpage successiva';
$string['navboth'] = 'Flexpage precedente e successivo';
$string['navigation'] = 'Navigazione';
$string['navigation_help'] = 'Utilizzato per mostrare i pulsanti successivo/precedente su questa flexpage. I pulsanti indirizzano l\'utente alla flexpage successiva/precedente disponibile.';
$string['display'] = 'Visualizza';
$string['name'] = 'Nome';
$string['name_help'] = 'Questo è il nome della tua flexpage che verrà visualizzato dagli utenti del corso nei menu e nei preferiti.';
$string['formnamerequired'] = 'Il nome della flexpage è un campo obbligatorio.';
$string['regionwidths'] = 'Blocca larghezze aree';
$string['regionwidths_help'] = 'Uno può indicare (in pixel) la larghezza di ogni area di blocchi. Ad esempio potresti impostare 200 a sinistra, 500 al centro e 200 a destra. Tieni presente che le aree disponibili e i relativi nomi possono cambiare in base al tema.';
$string['managepages'] = 'Gestisci flexpage';
$string['managepages_help'] = 'Da questa finestra, puoi visualizzare l\'indice di tutte le flexpage, puoi gestire in modo rapido, spostare o eliminare una singola flexpage, modificare le impostazioni di visualizzazione e controllare quelle di navigazione.';
$string['pagename'] = 'Nome flexpage';
$string['deletepage'] = 'Elimina flexpage';
$string['deletepageaction'] = 'Elimina flexpage';
$string['areyousuredeletepage'] = 'Eliminare flexpage in modo definitivo <strong>{$a}</strong>?';
$string['deletedpagex'] = 'Flexpage "{$a}" eliminata';
$string['flexpage:managepages'] = 'Gestisci flexpage';
$string['pagexnotavailable'] = '{$a} non è disponibile';
$string['pagenotavailable'] = 'Non disponibile';
$string['pagenotavailable_help'] = 'Questa flexpage non è disponibile. Di seguito, viene fornito l\'elenco delle condizioni da soddisfare per visualizzare la flexpage.';
$string['sectionname'] = 'Sezione';
$string['page'] = 'Flexpage';
$string['copydotdotdot'] = 'Copia...';
$string['nextpage'] = 'Successivo >';
$string['next'] = 'Successivo/a';
$string['previouspage'] = '< Precedente';
$string['previous'] = 'Precedente';
$string['themelayoutmissing'] = 'Il tema corrente non supporta Flexpage. Modifica il tema (oppure se attivata, il tema del corso o il tema preferito del profilo) e scegline uno con layout "{$a}".';
$string['deletemodwarn'] = 'L\'eliminazione di questa attività comporterà la rimozione della stessa da tutte le flexpage.';
$string['continuedotdotdot'] = 'Continua...';
$string['warning'] = 'Attenzione';
$string['actionbar'] = 'Barra delle azioni';
$string['availablefrom'] = 'Consenti accesso da';
$string['availablefrom_help'] = 'A partire da questa data, la flexpage sarà disponibile per gli utenti del corso.';
$string['availableuntil'] = 'Consenti accesso fino a';
$string['availableuntil_help'] = 'A partire da questa data, la flexpage sarà disponibile per gli utenti del corso.';
$string['showavailability'] = 'Prima di poter accedervi';
$string['showavailability_help'] = 'Se la flexpage non è disponibile per l\'utente, questa impostazione stabilisce se visualizzare o meno le informazioni sulle restrizioni della flexpage.';
$string['nomoveoptionserror'] = 'Impossibile spostare questa flexpage perché non ci sono posizioni disponibili per questa flexpage. Prima o dopo questa flexpage, prova ad aggiungerne di nuove.';
$string['frontpage'] = 'Utilizza Flexpage nella pagina home';
$string['frontpagedesc'] = 'Ciò consente di utilizzare il formato Flexpage nella pagina home.';
$string['hidefromothers'] = 'Nascondi flexpage';
$string['showfromothers'] = 'Mostra flexpage';
$string['jumptoflexpage'] = 'Passa a una flexpage';
$string['preventactivityview'] = 'Impossibile accedere a questa attività perché si trova su una flexpage momentaneamente non disponibile.';
$string['showavailability_hide'] = 'Nascondi tutta la flexpage';
$string['showavailability_show'] = 'Visualizza la flexpage non attiva con informazioni sulle restrizioni';
$string['display_help'] = 'Configura se questa flexpage è:
<ol>
    <li>Completamente nascosta agli utenti che non ricoprono il ruolo di editor.</li>
    <li>Visibile agli utenti del corso, ma non viene visualizzato nei menu di Flexpage e di navigazione del corso.</li>
    <li>Visibile agli utenti del corso e viene visualizzato nei menu Flexpage e di navigazione del corso.</li>
</ol>';
$string['addpages_help'] = 'Da qui puoi aggiungere nuove flexpage al corso.  Da sinistra a destra sul modulo:
 <ol> 
    <li>Inserisci il nome della tua flexpage (i nomi vuoti non vengono aggiunti).</li>
    <li>I <em>due</em> elenchi a discesa riportati di seguito determinano la posizione in cui viene aggiunta la nuova flexpage all\'interno dell\'indice delle flexpage.  Pertanto, puoi
     aggiungere la nuova flexpage prima, dopo o come elemento secondario (flexpage secondaria) di un\'altra flexpage.</li>
    <li>(Facoltativo) Nell\'ultimo menu a discesa, puoi scegliere una flexpage esistente da copiare nella flexpage appena creata.</li>
</ol>
Per aggiungere più di una flexpage alla volta, fai clic sull\'icona "+" e compila la nuova riga. Facendo clic sull\'icona "+" troppe volte, i nomi della flexpage si cancellano senza essere aggiunti.';
$string['actionbar_help'] = '
<p>Con Flexpage, i designer del corso possono creare più flexpage durante il corso. Ogni flexpage può avere contenuti univoci o condivisi.</p>

<p>Con la barra delle azioni, si può passare alle varie flexpage del corso facendo clic sul nome della flexpage nel menu a discesa.</p>

<p>Azioni disponibili in <strong>Add</strong> Voce di menu barra delle azioni:
    <ul>
        <li>Per aggiungere una flexpage, seleziona il link <strong>Aggiungi flexpage</strong> dal menu a discesa. Quando aggiungi nuove flexpage
       ,  nell\'indice delle flexpage specificherai dove vengono posizionate. Le flexpage possono essere elementi secondari di altre flexpage (flexpage secondarie) o semplicemente possono trovarsi prima o dopo altre flexpage. Inoltre, le nuove flexpage possono essere vuote o copie di una flexpage esistente. Per aggiungere più flexpage a un corso, premi l\'icona "+" a destra della finestra <strong>Aggiungi flexpage</strong> .</li>
        <li>Per aggiungere una nuova attività a questa flexpage, seleziona <strong>Aggiungi attività</strong> dal menu a discesa. Scegli dove posizionare la nuova attività nella flexpage, selezionando uno dei pulsanti nella parte superiore della finestra <strong>Aggiungi attività</strong>. In seguito, scegli l\'attività o la risorsa che intendi aggiungere al corso e alla flexpage.</li>
        <li>Per aggiungere un\'attività esistente a questa flexpage, seleziona <strong>Aggiungi attività esistente</strong> dal menu a discesa.
        Scegli dove posizionare l\'attività esistente nella flexpage, selezionando uno dei pulsanti nella parte superiore della finestra <strong>Aggiungi attività esistente</strong>. In seguito, applica un segno di spunta accanto alle attività da aggiungere a questa flexpage. Infine, fai clic sul pulsante "Aggiungi attività" nella parte inferiore della finestra per completare l\'azione.</li>
        <li>Per aggiungere un blocco a questa flexpage, seleziona <strong>Aggiungi blocco</strong> dal menu a discesa. Scegli dove posizionare il blocco sulla flexpage, selezionando uno dei pulsanti nella parte superiore della finestra <strong>Aggiungi blocco</strong>. In seguito, fai clic sul nome del blocco da aggiungere al corso.</li>
        <li>Per aggiungere un menu esistente a questa flexpage, seleziona <strong>Aggiungi menu esistente</strong> dal menu a discesa. Scegli dove posizionare il blocco nella flexpage, selezionando uno dei pulsanti nella parte superiore della finestra <strong>Aggiungi menu esistente</strong>. Poi, fai clic sul nome del menu da aggiungere al corso.</li>
    </ul>
</p>

<p>Azioni disponibili in <strong>Gestisci</strong> voce di menu barra delle azioni:
    <ul>
        <li>Per configurare le impostazioni per questa flexpage, fai clic sul link <strong>Gestisci impostazioni flexpage</strong> dal menu
        a discesa. Da questa finestra, puoi modificare il nome della flexpage e la larghezza delle aree della flexpage, puoi scegliere che la flexpage risulti nascosta, visibile o visibile nei menu e puoi indicare se le flexpage devono avere i pulsanti di navigazione "precedente e successivo".</li>
        <li>Per spostare una flexpage, fai clic sul link <strong>Sposta flexpage</strong> dal menu a discesa. Da questa finestra, puoi determinare se la flexpage è un elemento secondario di un\'altra flexpage o se nell\'indice si trova prima o dopo un\'altra flexpage.</li>
        <li> Per eliminare una flexpage, fai clic sul link <strong>Elimina flexpage</strong> dal menu a discesa. Da questa finestra, puoi confermare l\'eliminazione della flexpage corrente.</li>
        <li> Per gestire le impostazioni di più flexpage, fai clic sul link <strong>Gestisci tutte le flexpage</strong> dal menu a discesa. Da questa finestra, puoi visualizzare l\'indice di tutte le flexpage, puoi gestire, spostare o eliminare singole flexpage in modo rapido, puoi modificare le impostazioni di visualizzazione e gestire le impostazioni di navigazione.</li>
        <li>Per gestire le schede del corso e gli altri menu, fai clic sul link <strong>Gestisci tutti i menu</strong> dal menu a discesa. Da questa finestra puoi creare menu, modificarli, eliminarli e gestire i link all\'interno dei menu.</li>
    </ul>
</p>';
$string['none'] = '(nessuno)';
$string['grade_atleast'] = 'deve essere almeno';
$string['grade_upto'] = 'e minore di';
$string['contains'] = 'contiene';
$string['doesnotcontain'] = 'non contiene';
$string['isempty'] = 'è vuoto';
$string['isequalto'] = 'è uguale a';
$string['isnotempty'] = 'non è vuoto';
$string['endswith'] = 'termina con';
$string['startswith'] = 'inizia con';
$string['completion_complete'] = 'deve essere contrassegnata come completata';
$string['completion_fail'] = 'deve essere contrassegnata come completata senza la sufficienza';
$string['completion_incomplete'] = 'non deve essere contrassegnata come completata';
$string['completion_pass'] = 'deve essere contrassegnata come completata con la sufficienza';
$string['completioncondition'] = 'Condizione di completamento dell\'attività';
$string['completioncondition_help'] = 'Questa impostazione determina tutte le condizioni di completamento dell\'attività che devono essere soddisfatte per poter accedere alla flexpage. Tenere presente che il tracciamento per il completamento deve essere innanzitutto impostato prima che possa essere impostata la condizione per il completamento di un\'attività.

Se lo si desidera, è possibile impostare più condizioni per il completamento dell\'attività. In tal caso, l\'accesso alla flexpage sarà consentito solo se TUTTE le condizioni di completamento dell\'attività vengono soddisfatte.';
$string['gradecondition'] = 'Condizione voto';
$string['gradecondition_help'] = 'Questa impostazione determina tutte le condizioni del voto da soddisfare per poter accedere alla flexpage.

Se lo si desidera, è possibile impostare più condizioni. In tal caso, la flexpage consentirà l\'accesso solo quando TUTTE le condizioni del voto vengono soddisfatte.';
$string['userfield'] = 'Campo Utente';
$string['userfield_help'] = 'È possibile limitare l\'accesso in funzione dei campi presenti nel profilo utente.';
$string['releasecode'] = 'Codice di rilascio';
$string['releasecode_help'] = 'Questo elemento del corso non sarà disponibile per gli studenti finché lo studente non ottiene il codice di rilascio qui inserito.';
