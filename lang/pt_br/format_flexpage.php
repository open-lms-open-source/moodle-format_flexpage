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

$string['pluginname'] = 'Formato da Flexpage';
$string['defaultcoursepagename'] = 'Flexpage padrão (Alterar)';
$string['pagenotfound'] = 'O id de largura da flexpage = {$a} não existe neste curso.';
$string['addmenu'] = 'Adicionar';
$string['managemenu'] = 'Gerenciar';
$string['addactivityaction'] = 'Adicionar atividade';
$string['addpagesaction'] = 'Adicionar flexpages';
$string['managepagesaction'] = 'Gerenciar todas as flexpages';
$string['editpageaction'] = 'Gerenciar configurações da flexpage';
$string['editpage'] = 'Configurações da flexpage';
$string['movebefore'] = 'antes';
$string['moveafter'] = 'após';
$string['movechild'] = 'como primeiro elemento filho';
$string['ajaxexception'] = 'Erro no aplicativo: {$a}';
$string['addedpages'] = 'Flexpages adicionadas: {$a}';
$string['addpages'] = 'Adicionar flexpages';
$string['error'] = 'Erro';
$string['genericasyncfail'] = 'A solicitação apresentou falha por um motivo desconhecido. Tente executar a ação novamente.';
$string['close'] = 'Fechar';
$string['gotoa'] = 'Ir para "{$a}"';
$string['movepageaction'] = 'Mover flexpage';
$string['movepage'] = 'Mover flexpage';
$string['movepagea'] = 'Mover flexpage <strong>{$a}</strong>';
$string['movedpage'] = 'Flexpage movida "{$a->movepage}" {$a->move} flexpage "{$a->refpage}"';
$string['addactivity'] = 'Adicionar atividade';
$string['addactivity_help'] = 'Escolha onde você deseja colocar a nova atividade na flexpage selecionando um dos botões na parte superior da janela <strong>Adicionar atividade</strong>. Em seguida, escolha a atividade ou o recurso que você deseja adicionar ao curso e à flexpage.';
$string['addto'] = 'Adicionar à região:';
$string['addexistingactivity'] = 'Adicionar atividade existente';
$string['addexistingactivity_help'] = 'Escolha onde você deseja colocar a atividade existente na flexpage selecionando um dos botões na parte superior da janela <strong>Adicionar atividade existente</strong>. Em seguida, marque as caixas de seleção das atividades que deseja adicionar a esta flexpage. Por fim, clique no botão "Adicionar atividades" na parte inferior da janela para concluir a ação.';
$string['addexistingactivityaction'] = 'Adicionar atividade existente';
$string['addactivities'] = 'Adicionar atividades';
$string['addblock'] = 'Adicionar bloco';
$string['addblock_help'] = 'Escolha onde você deseja colocar o bloco na flexpage selecionando um dos botões na parte superior da janela <strong>Adicionar bloco</strong>. Em seguida, clique no nome do bloco que deseja adicionar ao curso.';
$string['addblockaction'] = 'Adicionar bloco';
$string['block'] = 'Bloco';
$string['displayhidden'] = 'Oculto';
$string['displayvisible'] = 'Visível, mas não nos menus';
$string['displayvisiblemenu'] = 'Visível e nos menus';
$string['navnone'] = 'Não há navegação';
$string['navprev'] = 'Flexpage anterior somente';
$string['navnext'] = 'Próxima flexpage somente';
$string['navboth'] = 'Flexpage anterior e próxima';
$string['navigation'] = 'Navegação';
$string['navigation_help'] = 'Usada para exibir os botões próximo e/ou anterior nesta flexpage. Os botões levam o usuário à flexpage anterior/próxima disponível.';
$string['display'] = 'Exibir';
$string['name'] = 'Nome';
$string['name_help'] = 'Este é o nome de sua flexpage e será exibido aos usuários do curso nos menus e equivalentes.';
$string['formnamerequired'] = 'O nome da flexpage é um campo obrigatório.';
$string['regionwidths'] = 'Larguras da região do bloco';
$string['regionwidths_help'] = 'É possível especificar o limite da largura de cada região de blocos. Um exemplo seria definir o limite esquerdo em 200, o central em 500 e o direito em 200.  Mas observe que as regiões disponíveis e seus nomes podem variar de acordo com o tema.';
$string['managepages'] = 'Gerenciar flexpages';
$string['managepages_help'] = 'Nessa janela, você pode visualizar o índice de todas as flexpages; gerenciar, mover ou excluir rapidamente uma flexpage individual; alterar configurações de exibição; e controlar configurações de navegação.';
$string['pagename'] = 'Nome da flexpage';
$string['deletepage'] = 'Excluir flexpage';
$string['deletepageaction'] = 'Excluir flexpage';
$string['areyousuredeletepage'] = 'Tem certeza que deseja excluir permanentemente a flexpage <strong>{$a}</strong>?';
$string['deletedpagex'] = 'Flexpage "{$a}" excluída';
$string['flexpage:managepages'] = 'Gerenciar flexpages';
$string['pagexnotavailable'] = '{$a} não está disponível';
$string['pagenotavailable'] = 'Não disponível';
$string['pagenotavailable_help'] = 'Esta flexpage não está disponível para você. Veja abaixo se há uma lista de condições que você deve satisfazer para visualizar a flexpage.';
$string['sectionname'] = 'Seção';
$string['page'] = 'Flexpage';
$string['copydotdotdot'] = 'Copiar...';
$string['nextpage'] = 'Próximo >';
$string['next'] = 'Próximo';
$string['previouspage'] = '< Anterior';
$string['previous'] = 'Anterior';
$string['themelayoutmissing'] = 'O seu tema atual não é compatível com a Flexpage.  Altere o tema (ou, caso habilitado, o tema do curso ou o tema preferido em seu perfil) para um que possua um layout "{$a}".';
$string['deletemodwarn'] = 'Se esta atividade for excluída, ela será removida de todas as flexpages.';
$string['continuedotdotdot'] = 'Continuar...';
$string['warning'] = 'Aviso';
$string['actionbar'] = 'Barra de ação';
$string['availablefrom'] = 'Permitir o acesso de';
$string['availablefrom_help'] = 'Esta flexpage ficará disponível aos usuários do curso a partir desta data.';
$string['availableuntil'] = 'Permitir o acesso até';
$string['availableuntil_help'] = 'Esta flexpage ficará disponível aos usuários do curso antes desta data.';
$string['showavailability'] = 'Antes de ser acessado';
$string['showavailability_help'] = 'Se a flexpage não estiver disponível para o usuário, esta configuração determinará se as informações de restrição dessa flexpage serão exibidas ou se nada será mostrado.';
$string['nomoveoptionserror'] = 'Você não pode mover esta flexpage porque não há posições disponíveis para colocá-la. Tente adicionar novas flexpages antes ou depois desta.';
$string['frontpage'] = 'Usar Flexpage na primeira página';
$string['frontpagedesc'] = 'Isto habilita o formato da Flexpage na primeira página.';
$string['hidefromothers'] = 'Ocultar flexpage';
$string['showfromothers'] = 'Mostrar flexpage';
$string['jumptoflexpage'] = 'Saltar para uma flexpage';
$string['preventactivityview'] = 'Você não pode acessar esta atividade ainda porque está em uma flexpage que não está disponível para você no momento.';
$string['showavailability_hide'] = 'Ocultar flexpage por inteiro';
$string['showavailability_show'] = 'Mostrar flexpage esmaecida, com informações de restrição';
$string['display_help'] = 'Configure se esta flexpage está:
<ol>
    <li>Completamente oculta para não editores.</li>
    <li>Visível para usuários do curso, mas não aparece nos Menus da flexpage e na navegação do curso.</li>
    <li>Visível para usuários do curso e aparece nos Menus da flexpage e na navegação do curso.</li>
</ol>';
$string['addpages_help'] = 'A partir daqui, você pode adicionar novas flexpages ao seu curso.  Da esquerda para a direita no formulário:
 <ol>
    <li>Insira o nome da sua flexpage (nomes em branco não são adicionados).</li>
    <li>Os próximos <em>dois</em> menus suspensos determinam o local do índice das flexpages no qual a sua nova flexpage será adicionada.  Assim, você pode
     adicionar a sua nova flexpage antes, depois ou como filha (subflexpage) de outra flexpage.</li>
    <li>(Opcional) No último menu suspenso, é possível escolher uma flexpage existente para copiar para a sua flexpage recém-criada.</li>
</ol>
Para adicionar mais de uma flexpage de uma só vez, clique no ícone "+" e preencha a nova linha.  Se você clicar no ícone "+" muitas vezes, deixe os nomes das flexpages em branco para que não sejam adicionadas.';
$string['actionbar_help'] = '
<p>Com a Flexpage, os desenvolvedores de cursos podem criar várias flexpages dentro do curso. Cada flexpage pode ter conteúdo exclusivo ou compartilhado.</p>

<p>Com a Barra de ação, é possível saltar para diferentes flexpages dentro do curso clicando no nome da flexpage no menu suspenso.</p>

<p>Ações disponíveis no item de menu da Barra de ação <strong>Adicionar</strong>:
    <ul>
        <li>Para adicionar uma flexpage, selecione o link <strong>Adicionar flexpages</strong> no menu suspenso. Quando adicionar novas flexpages, determine onde elas estarão localizadas no índice das flexpages. As flexpages podem ser filhas ou outras flexpages (subflexpages). Ou também podem simplesmente ser colocadas antes ou depois de outras flexpages. Novas flexpages também podem estar em branco ou ser cópias de flexpages existentes. Para adicionar várias flexpages a um curso, pressione o ícone "+" no lado direito da janela <strong>Adicionar flexpages</strong>.</li>
        <li>Para adicionar uma nova atividade a essa flexpage, selecione o link <strong>Adicionar atividade</strong> no menu suspenso. Escolha onde deseja colocar a nova atividade na flexpage selecionando um dos botões na parte superior da janela <strong>Adicionar atividade</strong>. Em seguida, escolha a atividade ou o recurso que deseja adicionar ao curso e à flexpage.</li>
        <li>Para adicionar uma atividade existente a essa flexpage, selecione o link <strong>Adicionar atividade existente</strong> no menu suspenso. Escolha onde deseja colocar a atividade existente na flexpage selecionando um dos botões na parte superior da janela <strong>Adicionar atividade existente</strong>. Em seguida, marque as caixas de seleção das atividades que deseja adicionar a essa flexpage. Por fim, clique no botão "Adicionar atividades" na parte inferior da janela para concluir a ação.</li>
        <li>Para adicionar um bloco a essa flexpage, selecione <strong>Adicionar bloco</strong> no menu suspenso. Escolha onde deseja colocar o bloco na flexpage selecionando um dos botões na parte superior da janela <strong>Adicionar bloco</strong>. Em seguida, clique no nome do bloco que deseja adicionar ao curso.</li>
        <li>Para adicionar um menu existente a essa flexpage, selecione <strong>Adicionar menu existente</strong> no menu suspenso. Escolha onde deseja colocar o bloco na flexpage selecionando um dos botões na parte superior da janela <strong>Adicionar menu existente</strong>. Em seguida, clique no nome do menu que deseja adicionar ao curso.</li>
    </ul>
</p>

<p>Ações disponíveis no item de menu da Barra de ação <strong>Gerenciar</strong>:
    <ul>
        <li>Para definir as configurações dessa flexpage, clique no link <strong>Gerenciar configurações da flexpage</strong> no menu suspenso. Nessa janela, você pode editar o nome da flexpage, alterar as larguras das regiões da flexpage, indicar se a flexpage deve ficar oculta ou visível nos menus e ainda determinar se as flexpages devem possuir os botões de navegação "anterior" e "próximo".</li>
        <li>Para mover uma flexpage, clique no link <strong>Mover flexpage</strong> no menu suspenso. Nessa janela, você pode escolher se a flexpage é filha de outra flexpage ou se ela está antes ou depois de outra flexpage no índice.</li>
        <li>Para excluir uma flexpage, clique no link <strong>Excluir flexpage</strong> no menu suspenso. Nessa janela, você pode confirmar que deseja excluir a flexpage atual.</li>
        <li>Para gerenciar as configurações de várias flexpages, clique no link <strong>Gerenciar todas as flexpages</strong> no menu suspenso. Nessa janela, você pode visualizar o índice de todas as flexpages e rapidamente gerenciar, mover ou excluir flexpages individuais, além de controlar as configurações de navegação.</li>
        <li>Para gerenciar as guias do seu curso, clique no link <strong>Gerenciar todos os menus</strong> no menu suspenso. Nessa janela, você pode criar menus, editar e excluir menus rapidamente e ainda gerenciar links dentro dos menus.</li>
    </ul>
</p>';
$string['none'] = '(nenhum)';
$string['grade_atleast'] = 'deve ser pelo menos';
$string['grade_upto'] = 'e menos de';
$string['contains'] = 'contém';
$string['doesnotcontain'] = 'não contém';
$string['isempty'] = 'está vazio';
$string['isequalto'] = 'é igual a';
$string['isnotempty'] = 'não está vazio';
$string['endswith'] = 'termina com';
$string['startswith'] = 'começa com';
$string['completion_complete'] = 'deve ser marcada como concluída';
$string['completion_fail'] = 'deve ser concluída com nota de reprovação';
$string['completion_incomplete'] = 'não deve ser marcada como concluída';
$string['completion_pass'] = 'deve ser concluída com nota de aprovação';
$string['completioncondition'] = 'Condição de conclusão da atividade';
$string['completioncondition_help'] = 'Essa configuração determina quaisquer condições de conclusão de atividade que devem ser atendidas para acessar a flexpage. Observe que o acompanhamento de conclusão deve ser definido antes que uma condição de conclusão de atividade possa ser definida.

Múltiplas condições de conclusão de atividade podem ser definidas caso desejado. Se for o caso, o acesso à seção só será permitido quando TODAS as condições de conclusão de atividade forem atendidas.';
$string['gradecondition'] = 'Condição da nota';
$string['gradecondition_help'] = 'Essa configuração determina quaisquer condições de notas que devam ser atendidas para acessar a flexpage.

Múltiplas condições de notas podem ser definidas, caso desejado. Se for o caso, a flexpage só permitirá o acesso quando TODAS as condições de notas forem atendidas.';
$string['userfield'] = 'Campo do usuário';
$string['userfield_help'] = 'você pode restringir o acesso com base em qualquer campo do perfil de usuários.';
$string['releasecode'] = 'Código da versão';
$string['releasecode_help'] = 'Este item do curso não ficará disponível para os alunos até que eles adquiram o código da versão inserido aqui.';
