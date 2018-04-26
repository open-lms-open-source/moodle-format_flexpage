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

$string['pluginname'] = 'Flexpageフォーマット';
$string['defaultcoursepagename'] = 'デフォルトFlexpage (変更)';
$string['pagenotfound'] = 'ID = {$a} のFlexpageは、このページに存在しません。';
$string['addmenu'] = '追加';
$string['managemenu'] = '管理';
$string['addactivityaction'] = '活動を追加する';
$string['addpagesaction'] = 'Flexpageを追加する';
$string['managepagesaction'] = 'すべてのFlexpageを管理する';
$string['editpageaction'] = 'Flexpage設定を管理する';
$string['editpage'] = 'Flexpage設定';
$string['movebefore'] = '次の前';
$string['moveafter'] = '次の後';
$string['movechild'] = '次の最初の子として';
$string['ajaxexception'] = 'アプリケーションエラー : {$a}';
$string['addedpages'] = '追加したFlexpage : {$a}';
$string['addpages'] = 'Flexpageを追加する';
$string['error'] = 'エラー';
$string['genericasyncfail'] = '不明な理由によりリクエストに失敗しました。再度お試しください。';
$string['close'] = '閉じる';
$string['gotoa'] = '"{$a}" に移動する';
$string['movepageaction'] = 'Flexpageを移動する';
$string['movepage'] = 'Flexpageを移動する';
$string['movepagea'] = 'Flexpage <strong>{$a}</strong> を移動する';
$string['movedpage'] = '移動したFlexpage "{$a->movepage}" {$a->move} Flexpage "{$a->refpage}"';
$string['addactivity'] = '活動を追加する';
$string['addactivity_help'] = '[<strong>活動を追加する</strong>]ウィンドウの上部にあるボタンのいずれかを選択して、新しい活動をFlexpageに配置する位置を選択します。次に、コースとFlexpageに追加する活動またはリソースを選択します。';
$string['addto'] = '領域に追加する :';
$string['addexistingactivity'] = '既存の活動を追加する';
$string['addexistingactivity_help'] = '[<strong>既存の活動を追加する</strong>] ウィンドウの上部にあるボタンのいずれかを選択して、既存の活動をFlexpageに配置する位置を選択します。次に、このFlexpageに追加する活動の横にあるチェックボックスをオンにします。最後に、ウィンドウの下部にある[活動を追加する]ボタンをクリックして、操作を完了します。';
$string['addexistingactivityaction'] = '既存の活動を追加する';
$string['addactivities'] = '活動を追加する';
$string['addblock'] = 'ブロックを追加する';
$string['addblock_help'] = '[<strong>ブロックを追加する</strong>]ウィンドウの上部にあるボタンのいずれかを選択して、ブロックをFlexpageに配置する位置を選択します。次に、コースに追加するブロックの名前をクリックします。';
$string['addblockaction'] = 'ブロックを追加する';
$string['block'] = 'ブロック';
$string['displayhidden'] = '非表示';
$string['displayvisible'] = '表示 (メニューには非表示)';
$string['displayvisiblemenu'] = '表示 (メニューに表示)';
$string['navnone'] = 'ナビゲーションなし';
$string['navprev'] = '前のFlexpageのみ';
$string['navnext'] = '次のFlexpageのみ';
$string['navboth'] = '前と次のFlexpageの両方';
$string['navigation'] = 'ナビゲーション';
$string['navigation_help'] = 'このページに次または前のボタンを表示する場合に使用します。ボタンをクリックすると、ユーザは次または前に使用可能なFlexpageに移動します。';
$string['display'] = '表示';
$string['name'] = '名称';
$string['name_help'] = 'Flexpageの名前です。この名前は、メニューおよびリンク内でコースユーザに表示されます。';
$string['formnamerequired'] = 'Flexpageの[名称]は必須フィールドです。';
$string['regionwidths'] = 'ブロック領域の幅';
$string['regionwidths_help'] = 'ブロックの各領域の幅をピクセル単位で指定できます。たとえば、左を200、メインを500、右を200に設定します。利用可能な領域と名称は、テーマによって異なる場合があります。';
$string['managepages'] = 'Flexpageを管理する';
$string['managepages_help'] = 'このウィンドウでは、すべてのFlexpageのインデックスを表示したり、個別のFlexpageをすばやく管理、移動、または移動したり、表示設定を変更したり、ナビゲーション設定をコントロールしたりできます。';
$string['pagename'] = 'Flexpage名';
$string['deletepage'] = 'Flexpageを削除する';
$string['deletepageaction'] = 'Flexpageを削除する';
$string['areyousuredeletepage'] = 'Flexpage <strong>{$a}</strong> を完全に削除しますか？';
$string['deletedpagex'] = 'Flexpage "{$a}"が削除されました';
$string['flexpage:managepages'] = 'Flexpageを管理する';
$string['pagexnotavailable'] = '{$a} は利用できません。';
$string['pagenotavailable'] = '利用できません。';
$string['pagenotavailable_help'] = 'このFlexpageは利用できません。このFlexpageを表示するために満たす必要がある条件の一覧が以下に表示されることがあります。';
$string['sectionname'] = 'セクション';
$string['page'] = 'Flexpage';
$string['copydotdotdot'] = 'コピー...';
$string['nextpage'] = '次へ >';
$string['next'] = '次へ';
$string['previouspage'] = '< 前に戻る';
$string['previous'] = '前に戻る';
$string['themelayoutmissing'] = '現在のテーマでは、Flexpageがサポートされていません。テーマ (有効にした場合は、プロファイル内のコーステーマまたは推奨テーマ) を"{$a}"レイアウトがあるテーマに変更してください。';
$string['deletemodwarn'] = 'この活動を削除する場合、すべてのFlexpageから削除されます。';
$string['continuedotdotdot'] = '続ける...';
$string['warning'] = '警告';
$string['actionbar'] = '操作バー';
$string['availablefrom'] = 'アクセスを許可する開始日';
$string['availablefrom_help'] = 'この日付からコースユーザは、このFlexpageを利用できるようになります。';
$string['availableuntil'] = 'アクセスを許可する終了日';
$string['availableuntil_help'] = 'この日付までコースユーザは、このFlexpageを利用することができます。';
$string['showavailability'] = 'アクセス可能に前の処理';
$string['showavailability_help'] = 'ユーザがFlexpageを利用できない場合に、この設定では、このFlexpageの制限付き情報を表示するか、何も表示しないかを指定します。';
$string['nomoveoptionserror'] = 'このFlexpageは、配置できる位置がないため移動できません。このFlexpagesの前または後にFlexpageを追加してください。';
$string['frontpage'] = 'フロントページでFlexpageを使用する';
$string['frontpagedesc'] = 'フロントページでFlexpageフォーマットが有効になります。';
$string['hidefromothers'] = 'Flexpageを隠す';
$string['showfromothers'] = 'Flexpageを表示する';
$string['jumptoflexpage'] = 'Flexpageにジャンプする';
$string['preventactivityview'] = 'この活動は、現在利用できないFlexpageにあるためアクセスできません。';
$string['showavailability_hide'] = 'Flexpageを完全に隠す';
$string['showavailability_show'] = 'Flexpageを制限付きの情報と共にグレイアウトで表示する';
$string['display_help'] = 'このFlexpageが次に該当する場合に設定します :
<ol>
    <li>編集者以外は完全に非表示にする。</li>
    <li>コースユーザには表示するが、Flexpageメニューとコースナビゲーションには表示しない。</li>
    <li>コースユーザに表示し、Flexpageメニューとコースナビゲーションに表示する。</li>
</ol>';
$string['addpages_help'] = 'ここでは、新しいFlexpageをコースに追加できます。フォーム上の左から右に移動します :
 <ol>
    <li>Flexpageの名前を入力します (空白の名前は追加されません)。</li>
    <li>次の<em>2つ</em>のドロップダウンで、新しいFlexpageを追加するFlexpageのインデックス内の位置を指定します。これにより、
     新しいFlexpageを別のFlexpageの前、後、または子 (サブFlexpage) として追加で来ます。</li>
    <li>(任意) 最後のドロップダウンでは、既存のFlexpageを選択して、新たに作成したFlexpageにコピーできます。</li>
</ol>
複数のFlexpageを同時に追加するには、[+]アイコンをクリックして、新しい行を入力します。[+]アイコンを何度もクリックしすぎると、Flexpageの名前が見えなくなり、追加されなくなります。';
$string['actionbar_help'] = '
<p>Flexpageを使用すると、コースデザイナはコース内に複数のFlexpageを作成できます。Fexpageごとに独自のコンテンツまたは共有コンテンツを配置できます。</p>

<p>操作バーでは、ドロップダウンメニューのFlexpage名をクリックして、コース内のさまざまなFlexpageに
 ジャンプできます。</p>

<p>操作バーの[<strong>追加</strong>]メニューアイテムでは、次の操作を実行できます :
    <ul>
        <li>Flexpageを追加するには、ドロップダウンメニューから[<strong>Flexpageを追加する</strong>]リンクを選択します。新しいFlexpageを
        追加する場合、Flexpageのインデックスに配置する位置を指定できます。Flexpageは、他のFlexpages (サブFlexpage) の子にすることができます。また、他のFlexpageの前または後に配置することもできます。新しいFlexpageを空白にしたり、既存のFlexpageのコピーを使用したりすることもできます。複数のFlexpageをコースに追加するには、[<strong>Flexpageを追加する</strong>]ウィンドウの右側にある[+]アイコンをクリックします。</li>
        <li>このFlexpageに新しい活動を追加するには、ドロップダウンメニューから[<strong>活動を追加する</strong>]を選択します。
         [<strong>活動を追加する</strong>]ウィンドウの上部にあるボタンのいずれかを選択して、新しい活動をFlexpageに配置する位置を選択します。次に、コースとFlexpageに追加する活動またはリソースを選択します。</li>
        <li>このFlexpageに既存の活動を追加するには、ドロップダウンメニューから[<strong>既存の活動を追加する</strong>]を選択します。
        [<strong>既存の活動を追加する</strong>]ウィンドウの上部にあるボタンのいずれかを選択して、Flexpageに既存の活動を配置する位置を選択します。次に、このFlexpageに追加する活動の横にあるチェックボックスをオンにします。最後に、ウィンドウの下部にある[活動を追加する]ボタンをクリックして、操作を完了します。</li>
        <li>このFlexpageにブロックを追加するには、ドロップダウンメニューから[<strong>ブロックを追加する</strong>]を選択します。
        [<strong>ブロックを追加する</strong>]ウィンドウの上部にあるボタンのいずれかを選択して、Flexpageにブロックを配置する位置を選択します。次に、コースに追加するブロックの名前をクリックします。</li>
        <li>このFlexpageに既存のメニューを追加するには、ドロップダウンメニューから[<strong>既存メニューを追加する</strong>]を選択します。
         [<strong>既存のメニューを追加する</strong>]ウィンドウの上部にあるボタンのいずれかを選択して、Flexpageにブロックを配置する位置を選択します。次に、コースに追加するメニューの名前をクリックします。</li>
    </ul>
</p>

<p>操作バーの[<strong>管理</strong>]メニューアイテムでは、次の操作を実行できます :
    <ul>
        <li>このFlexpageの設定を行うには、ドロップダウンメニューから[<strong>Flexpage設定を管理する</strong>]リンクをクリック
        します。このウィンドウでは、Flexpageの名前を編集したり、Flexpageの領域の幅を変更したり、Flexpageを表示するか、非表示にするか、メニューに表示するかを指定したり、前と次のナビゲーションボタンをFlexpageに配置するかどうかを指定したりできます。</li>
        <li>Flexpageを移動するには、ドロップダウンメニューから[<strong>Flexpageを移動する</strong>]リンクをクリックします。このウィンドウでは、
         Flexpageを別のFlexpageの子にするかどうか、インデックスで別のFlexpageの前または後に配置するかどうかを選択できます。</li>
        <li>Flexpageを削除するには、ドロップダウンメニューから[<strong>Flexpageを削除する</strong>]リンクをクリックします。このウィンドウでは、
        現在のFlexpageを削除することを確認できます。</li>
        <li>複数のFlexpageの設定を管理するには、ドロップダウンメニューから[<strong>すべてのFlexpageを管理する</strong>]リンクをクリック
         します。このウィンドウでは、すべてのFlexpageのインデックスを表示したり、個別のFlexpageをすばやく管理、移動、または削除したり、表示設定を変更したり、ナビゲーション設定を変更したりできます。</li>
        <li>コースおよびメニューのタブを管理するには、ドロップダウンメニューから[<strong>すべてのメニューを管理する</strong>]リンクを
        クリックします。このウィンドウでは、メニューを作成したり、メニューをすばやく編集または削除したり、メニュー内のリンクを管理したりできます。</li>
    </ul>
</p>';
$string['none'] = '(なし)';
$string['grade_atleast'] = '次の値以上';
$string['grade_upto'] = '次の値未満';
$string['contains'] = '次の文字を含む';
$string['doesnotcontain'] = '次の文字を含まない';
$string['isempty'] = '空白';
$string['isequalto'] = 'が次の文字と等しい';
$string['isnotempty'] = '空白ではない';
$string['endswith'] = '次の文字で終わる';
$string['startswith'] = '次の文字で始まる';
$string['completion_complete'] = '完了マークされる必要あり';
$string['completion_fail'] = '不合格で完了する必要あり';
$string['completion_incomplete'] = '完了マークされない必要あり';
$string['completion_pass'] = '合格で完了する必要あり';
$string['completioncondition'] = '活動完了の条件';
$string['completioncondition_help'] = 'この設定では、Flexpageにアクセスするために満たす必要がある活動完了の条件を指定します。活動完了の条件を設定するには、事前に完了トラッキングを設定する必要がある点に注意してください。

必要に応じて、複数の活動完了の条件を設定することができます。その場合、すべての活動完了の条件が満たされた場合にのみ、Flexpageへのアクセスが許可されます。';
$string['gradecondition'] = '評定条件';
$string['gradecondition_help'] = 'この設定では、Flexpageにアクセスするために満たす必要がある評定条件を指定します。

必要に応じて、複数の評定条件を設定することができます。その場合、すべての評定条件が満たされた場合にのみ、Flexpageへのアクセスが許可されます。';
$string['userfield'] = 'ユーザフィールド';
$string['userfield_help'] = 'ユーザプロファイルのフィールドに基づいてアクセスを制限できます。';
$string['releasecode'] = 'リリースコード';
$string['releasecode_help'] = 'このコースアイテムは、ここで入力したリリースコードを学生が取得するまで、学生が利用することはできません。';
