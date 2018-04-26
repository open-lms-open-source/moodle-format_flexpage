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
$string['defaultcoursepagename'] = 'Flexpage par défaut (modifier)';
$string['pagenotfound'] = 'L\'élément Flexpage correspondant à l\'identifiant {$a} n\'existe pas dans ce cours.';
$string['addmenu'] = 'Ajouter';
$string['managemenu'] = 'Gérer';
$string['addactivityaction'] = 'Ajouter une activité';
$string['addpagesaction'] = 'Ajouter des éléments Flexpage';
$string['managepagesaction'] = 'Gérer tous les éléments Flexpage';
$string['editpageaction'] = 'Gérer les paramètres Flexpage';
$string['editpage'] = 'Paramètres Flexpage';
$string['movebefore'] = 'avant';
$string['moveafter'] = 'après';
$string['movechild'] = 'en tant que premier enfant de';
$string['ajaxexception'] = 'Erreur d\'application : {$a}';
$string['addedpages'] = 'Éléments Flexpage ajoutés : {$a}';
$string['addpages'] = 'Ajouter des éléments Flexpage';
$string['error'] = 'Erreur';
$string['genericasyncfail'] = 'La requête a échoué pour une raison inconnue, réessayez cette action.';
$string['close'] = 'Fermer';
$string['gotoa'] = 'Aller à {$a}';
$string['movepageaction'] = 'Déplacer l\'élément Flexpage';
$string['movepage'] = 'Déplacer l\'élément Flexpage';
$string['movepagea'] = 'Déplacer l\'élément Flexpage <strong>{$a}</strong>';
$string['movedpage'] = 'L\'élément Flexpage "{$a->movepage}" a été déplacé vers {$a->move} "{$a->refpage}"';
$string['addactivity'] = 'Ajouter une activité';
$string['addactivity_help'] = 'Choisissez l\'endroit où placer la nouvelle activité sur l\'élément Flexpage en sélectionnant l\'un des boutons en haut de la fenêtre <strong>Ajouter une activité</strong>. Choisissez ensuite l\'activité ou la ressource à ajouter au cours ou à l\'élément Flexpage.';
$string['addto'] = 'Ajouter à la zone :';
$string['addexistingactivity'] = 'Ajouter une activité existante';
$string['addexistingactivity_help'] = 'Choisissez l\'endroit où placer l\'activité existante sur l\'élément Flexpage en sélectionnant l\'un des boutons en haut de la fenêtre <strong>Ajouter une activité existante</strong>. Cochez ensuite les activités que vous voulez ajouter. Cliquez enfin sur le bouton Ajouter les activités en bas de la fenêtre pour terminer.';
$string['addexistingactivityaction'] = 'Ajouter une activité existante';
$string['addactivities'] = 'Ajouter les activités';
$string['addblock'] = 'Ajouter un bloc';
$string['addblock_help'] = 'Choisissez l\'endroit où placer le bloc sur l\'élément Flexpage en sélectionnant l\'un des boutons en haut de la fenêtre <strong>Ajouter un bloc</strong>. Cliquez ensuite sur le nom du bloc que vous voulez ajouter au cours.';
$string['addblockaction'] = 'Ajouter un bloc';
$string['block'] = 'Bloc';
$string['displayhidden'] = 'Masqué';
$string['displayvisible'] = 'Visible mais pas dans les menus';
$string['displayvisiblemenu'] = 'Visible et dans les menus';
$string['navnone'] = 'Pas de navigation';
$string['navprev'] = 'Uniquement l\'élément Flexpage précédent';
$string['navnext'] = 'Uniquement l\'élément Flexpage suivant';
$string['navboth'] = 'L\'élément Flexpage précédent et suivant';
$string['navigation'] = 'Navigation';
$string['navigation_help'] = 'Affiche les boutons suivant/précédent sur cet élément Flexpage afin d\'accéder au suivant/précédent.';
$string['display'] = 'Afficher';
$string['name'] = 'Nom';
$string['name_help'] = 'Nom de votre élément Flexpage, il s\'affiche dans les menus et autres, pour les utilisateurs de cours.';
$string['formnamerequired'] = 'Le champ du nom Flexpage est obligatoire.';
$string['regionwidths'] = 'Bloquer les largeurs de zone';
$string['regionwidths_help'] = 'Il est possible d\'indiquer la largeur de chaque zone de blocs en pixels. Par exemple, vous pouvez en attribuer 200 pour la gauche, 500 pour le centre et 200 pour la droite. Sachez toutefois que les zones disponibles et leur nom varient d\'un thème à l\'autre.';
$string['managepages'] = 'Gérer les éléments Flexpage';
$string['managepages_help'] = 'Dans cette fenêtre, vous pouvez voir l\'index de tous les éléments Flexpage et rapidement gérer, déplacer ou supprimer l\'un d\'eux, mais aussi modifier les paramètres d\'affichage ou contrôler les paramètres de navigation.';
$string['pagename'] = 'Nom Flexpage';
$string['deletepage'] = 'Supprimer l\'élément Flexpage';
$string['deletepageaction'] = 'Supprimer l\'élément Flexpage';
$string['areyousuredeletepage'] = 'Voulez-vous vraiment supprimer l\'élément Flexpage <strong>{$a}</strong> définitivement ?';
$string['deletedpagex'] = 'Élément Flexpage "{$a}" supprimé';
$string['flexpage:managepages'] = 'Gérer les éléments Flexpage';
$string['pagexnotavailable'] = '{$a} n\'est pas disponible';
$string['pagenotavailable'] = 'Non disponible';
$string['pagenotavailable_help'] = 'Cet élément Flexpage n\'est pas disponible pour vous. Vous trouverez ci-dessous la liste des conditions à remplir pour pouvoir l\'afficher.';
$string['sectionname'] = 'Section';
$string['page'] = 'Flexpage';
$string['copydotdotdot'] = 'Copie...';
$string['nextpage'] = 'Suivant >';
$string['next'] = 'Suivant';
$string['previouspage'] = '< Précédent';
$string['previous'] = 'Précédent';
$string['themelayoutmissing'] = 'Votre thème actuel ne prend pas en charge les éléments Flexpage. Changez-le (ou s\'il est disponible, modifiez le thème du cours ou votre thème favori dans votre profil) au profit d\'un thème au format "{$a}".';
$string['deletemodwarn'] = 'Si vous supprimez cette activité, elle sera supprimée de tous les éléments Flexpage.';
$string['continuedotdotdot'] = 'Continuer...';
$string['warning'] = 'Avertissement';
$string['actionbar'] = 'Barre d\'outils';
$string['availablefrom'] = 'Disponible dès le';
$string['availablefrom_help'] = 'Cet élément Flexpage sera à disposition des utilisateurs du cours après cette date.';
$string['availableuntil'] = 'Disponible jusqu\'au';
$string['availableuntil_help'] = 'Cet élément Flexpage sera à disposition des utilisateurs du cours jusqu\'à cette date.';
$string['showavailability'] = 'Avant de pouvoir y accéder';
$string['showavailability_help'] = 'Si l\'élément Flexpage n\'est pas à disposition de l\'utilisateur, ce paramètre détermine si les informations de restriction le concernant s\'affichent ou non.';
$string['nomoveoptionserror'] = 'Vous ne pouvez pas déplacer cet élément Flexpage car il n\'y a pas d\'emplacement disponible pour l\'accueillir. Essayez d\'en ajouter d\'autres avant ou après.';
$string['frontpage'] = 'Utilisez un élément Flexpage sur la page d\'accueil';
$string['frontpagedesc'] = 'Cela permet d\'activer le format Flexpage sur la page d\'accueil.';
$string['hidefromothers'] = 'Masquer l\'élément Flexpage';
$string['showfromothers'] = 'Afficher l\'élément Flexpage';
$string['jumptoflexpage'] = 'Passer à l\'élément Flexpage';
$string['preventactivityview'] = 'Vous ne pouvez pas encore accéder à cette activité car elle se trouve sur un élément Flexpage qui n\'est pas disponible pour vous pour le moment.';
$string['showavailability_hide'] = 'Masquer complètement l\'élément Flexpage';
$string['showavailability_show'] = 'Mettre l\'élément Flexpage en grisé, en indiquant les informations sur la restriction';
$string['display_help'] = 'Configurez si cet élément Flexpage est :
<ol>
    <li>Complètement masqué pour les non éditeurs.</li>
    <li>Visible pour les utilisateurs de cours, sans apparaître dans les menus Flexpage et la navigation de cours.</li>
    <li>Visible pour les utilisateurs de cours et affiché dans les menus Flexpage et la navigation de cours.</li>
</ol>';
$string['addpages_help'] = 'Ici vous pouvez ajouter de nouveaux éléments Flexpage à votre cours. De gauche à droite dans le formulaire :
 <ol>
    <li>Saisissez le nom de votre élément Flexpage (les noms vides ne sont pas ajoutés).</li>
    <li>Les <em>deux</em> listes déroulantes suivantes déterminent où ajouter le nouvel élément dans l\'index. Vous pouvez donc le positionner avant ou après un autre élément, ou en faire un enfant (sous-élément Flexpage) d\'un autre élément.</li>
    <li>(Facultatif) Dans la dernière liste déroulante, vous pouvez choisir un élément Flexpage existant à copier dans le nouveau.</li>
</ol>
Pour ajouter plusieurs éléments Flexpage en même temps, cliquez sur l\'icône « + » et remplissez la nouvelle ligne. Si vous cliquez trop de fois sur l\'icône « + », ignorez les noms des éléments Flexpage pour qu\'ils ne soient pas ajoutés.';
$string['actionbar_help'] = '
<p>Grâce à Flexpage, les concepteurs de cours peuvent créer plusieurs éléments Flexpage dans un cours. On retrouve sur chacun du contenu unique ou partagé.</p>

<p>La barre Actions permet de passer à d\'autres éléments Flexpage dans un cours, en cliquant sur un nom dans le menu déroulant.</p>

<p>Actions disponibles à partir du bouton <strong>Ajouter</strong> de la barre Actions :
    <ul>
        <li>Pour ajouter un élément Flexpage, sélectionnez le lien <strong>Ajouter des éléments Flexpage</strong> dans le menu déroulant. Lorsque vous ajoutez de nouveaux éléments Flexpage, vous pouvez déterminer leur emplacement dans l\'index. Il est également possible de créer des éléments Flexpage enfants (sous-éléments Flexpage) ou de les placer tout simplement avant ou après d\'autres éléments. Les nouveaux éléments Flexpage peuvent être la copie d\'un élément existant ou être vides. Pour ajouter plusieurs éléments Flexpage, cliquez sur l\'icône « + » sur la droite de la fenêtre <strong>Ajouter des éléments Flexpages</strong>.</li>
        <li>Pour ajouter une nouvelle activité à l\'élément Flexpage, sélectionnez <strong>Ajouter une activité</strong> dans le menu déroulant. Sélectionnez son emplacement dans l\'élément Flexpage à l\'aide des boutons situés en haut de la fenêtre <strong>Ajouter une activité</strong>. Cochez ensuite l\'activité ou ressource à ajouter au cours et à l\'élément Flexpage.</li>
        <li>Pour ajouter une activité existante, sélectionnez <strong>Ajouter une activité existante</strong> dans le menu déroulant. Sélectionnez son emplacement dans l\'élément Flexpage à l\'aide des boutons situés en haut de la fenêtre <strong>Ajouter une activité existante</strong>. Cochez ensuite les activités à ajouter. Cliquez ensuite sur le bouton Ajouter des activités en bas de la fenêtre pour terminer l\'action.</li>
        <li>Pour ajouter un bloc, sélectionnez <strong>Ajouter un bloc</strong> dans le menu déroulant. Sélectionnez son emplacement dans l\'élément Flexpage à l\'aide des boutons situés en haut de la fenêtre <strong>Ajouter un bloc</strong>. Cliquez ensuite sur le nom du bloc à ajouter au cours.</li>
        <li>Pour ajouter un menu existant, sélectionnez <strong>Ajouter un menu existant</strong> dans le menu déroulant. Sélectionnez son emplacement dans l\'élément à l\'aide des boutons situés en haut de la fenêtre <strong>Ajouter un menu existant</strong>. Cliquez ensuite sur le nom du menu à ajouter au cours.</li>
    </ul>
</p>

<p>Actions disponibles à partir du bouton <strong>Gérer</strong> de la barre Actions :
    <ul>
        <li>Pour configurer les paramètres de cet élément Flexpage, cliquez sur <strong>Gérer les paramètres Flexpage</strong> du menu déroulant. Dans la fenêtre qui s\'ouvre, vous pouvez modifier le nom de l\'élément Flexpage, changer la largeur des zones Flexpage, indiquer si un élément doit être masqué, visible ou visible dans les menus, mais aussi déterminer s\'il convient d\'ajouter des boutons de navigation Précédent et Suivant.</li>
        <li>Pour déplacer un élément Flexpage, cliquez sur <strong>Déplacer un élément Flexpage</strong> dans le menu déroulant. Dans la fenêtre qui s\'ouvre, choisissez si l\'élément Flexpage est l\'enfant d\'un autre élément ou s\'il doit être placé avant ou après un autre élément dans l\'index.</li>
        <li>Pour supprimer un élément Flexpage, cliquez sur <strong>Supprimer un élément Flexpage</strong> dans le menu déroulant. Dans la fenêtre qui s\'ouvre, confirmez la suppression.</li>
        <li>Pour gérer les paramètres de plusieurs éléments Flexpage, cliquez sur <strong>Gérer tous les éléments Flexpage</strong> dans le menu déroulant. Dans la fenêtre qui s\'ouvre, vous pouvez consulter l\'index de tous les éléments Flexpage, gérer, déplacer ou supprimer rapidement des éléments Flexpage, modifier des paramètres d\'affichage mais aussi contrôler les paramètres de navigation.</li>
        <li>Pour gérer les onglets de votre cours ou les autres menus, cliquez sur <strong>Gérer tous les menus</strong> du menu déroulant. Dans la fenêtre qui s\'ouvre, vous pouvez créer des menus, ainsi que modifier et supprimer rapidement des menus et gérer les liens qui s\'y trouvent.</li>
    </ul>
</p>';
$string['none'] = '(aucun)';
$string['grade_atleast'] = 'doit être supérieur à';
$string['grade_upto'] = 'et inférieur à';
$string['contains'] = 'contient';
$string['doesnotcontain'] = 'ne contient pas';
$string['isempty'] = 'est vide';
$string['isequalto'] = 'est égal à';
$string['isnotempty'] = 'n\'est pas vide';
$string['endswith'] = 'se termine par';
$string['startswith'] = 'commence par';
$string['completion_complete'] = 'doit être marqué comme terminé';
$string['completion_fail'] = 'doit être terminé avec une note d\'échec';
$string['completion_incomplete'] = 'ne doit pas être marqué comme terminé';
$string['completion_pass'] = 'doit être terminé avec une note de réussite';
$string['completioncondition'] = 'Condition d\'achèvement d\'activité';
$string['completioncondition_help'] = 'Ce réglage détermine les conditions d\'achèvement d\'activité devant être remplies pour accéder à l\'élément Flexpage. Le suivi d\'achèvement doit d\'abord être activé avant de pouvoir ajouter des conditions d\'achèvement.

Plusieurs conditions d\'achèvement peuvent être ajoutées, si nécessaire. Dans ce cas, l\'accès à l\'élément Flexpage ne sera permis que si toutes les conditions sont remplies.';
$string['gradecondition'] = 'Condition de note';
$string['gradecondition_help'] = 'Ce réglage détermine les conditions sur les notes à obtenir pour accéder à l\'élément Flexpage.

Plusieurs conditions de note peuvent être ajoutées, si nécessaire. Dans ce cas, l\'accès à l\'élément Flexpage ne sera permis que si toutes les conditions sont remplies.';
$string['userfield'] = 'Champ utilisateur';
$string['userfield_help'] = 'Vous pouvez restreindre l\'accès sur la base de n\'importe quel champ du profil utilisateur.';
$string['releasecode'] = 'Code de déblocage';
$string['releasecode_help'] = 'Cet élément de cours ne sera pas disponible pour les étudiants tant qu\'ils n\'acquièrent pas le code de déblocage saisi ici.';
