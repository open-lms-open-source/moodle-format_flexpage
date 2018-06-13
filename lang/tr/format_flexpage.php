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

$string['pluginname'] = 'Flexpage biçimi';
$string['defaultcoursepagename'] = 'Varsayılan flexpage (Beni değiştir)';
$string['pagenotfound'] = 'Bu kursta {$a} kodlu flexpage yok.';
$string['addmenu'] = 'Ekle';
$string['managemenu'] = 'Yönet';
$string['addactivityaction'] = 'Etkinlik ekle';
$string['addpagesaction'] = 'Flexpage ekle';
$string['managepagesaction'] = 'Tüm flexpage\'leri yönet';
$string['editpageaction'] = 'Flexpage ayarlarını yönet';
$string['editpage'] = 'Flexpage ayarları';
$string['movebefore'] = 'önce';
$string['moveafter'] = 'sonra';
$string['movechild'] = 'şunun alt öğesi olarak:';
$string['ajaxexception'] = 'Uygulama hatası: {$a}';
$string['addedpages'] = 'Eklenen flexpage sayısı: {$a}';
$string['addpages'] = 'Flexpage ekle';
$string['error'] = 'Hata';
$string['genericasyncfail'] = 'İsteğiniz, bilinmeyen bir nedenle yerine getirilemedi, lütfen yeniden deneyin.';
$string['close'] = 'Kapat';
$string['gotoa'] = '"{$a}" hedefine git';
$string['movepageaction'] = 'Flexpage\'i taşı';
$string['movepage'] = 'Flexpage\'i taşı';
$string['movepagea'] = '<strong>{$a}</strong> kodlu flexpage\'i taşı';
$string['movedpage'] = '"{$a->movepage}" kodlu flexpage, "{$a->refpage}" kodlu flexpage\'e {$a->move} taşındı.';
$string['addactivity'] = 'Etkinlik ekle';
$string['addactivity_help'] = 'Yeni etkinliği flexpage\'de hangi konuma yerleştirmek istediğinizi <strong>Etkinlik ekle</strong> penceresinin üst kısmında yer alan düğmelerden birini seçerek belirleyin. Ardından kursa ve flexpage\'e eklemek istediğiniz etkinliği veya kaynağı seçin.';
$string['addto'] = 'Şu bölgeye ekle:';
$string['addexistingactivity'] = 'Mevcut etkinliği ekle';
$string['addexistingactivity_help'] = 'Mevcut etkinliği flexpage’de nereye yerleştireceğinizi, <strong>Mevcut etkinliği ekle</strong> penceresinin üst kısmında yer alan düğmelerden birini seçerek belirleyin. Daha sonra, bu flexpage\'e eklemek istediğiniz etkinliklerin yanına işaret koyun. Son olarak işlemi tamamlamak için pencerenin altındaki \'Etkinlikler ekle\' düğmesini tıklatın.';
$string['addexistingactivityaction'] = 'Mevcut etkinliği ekle';
$string['addactivities'] = 'Etkinlikler ekle';
$string['addblock'] = 'Blok ekle';
$string['addblock_help'] = 'Bloku flexpage\'de hangi konuma yerleştireceğinizi <strong>Blok ekle</strong> penceresinin üst kısmında yer alan düğmelerden birini seçerek belirleyin. Daha sonra kursa eklemek istediğiniz blokun adını tıklatın.';
$string['addblockaction'] = 'Blok ekle';
$string['block'] = 'Blok';
$string['displayhidden'] = 'Gizli';
$string['displayvisible'] = 'Görünür ancak menülerde değil';
$string['displayvisiblemenu'] = 'Görünür ve menülerde';
$string['navnone'] = 'Gezinti yok';
$string['navprev'] = 'Yalnızca önceki flexpage';
$string['navnext'] = 'Yalnızca sonraki flexpage';
$string['navboth'] = 'Hem önceki hem de sonraki flexpage';
$string['navigation'] = 'Gezinti';
$string['navigation_help'] = 'Bu flexpage\'deki sonraki ve/veya önceki düğmelerini görüntülemek için kullanılır. Düğmeler, kullanıcıyı mevcut durumdaki sonraki/önceki flexpage\'e götürür.';
$string['display'] = 'Görünen';
$string['name'] = 'Ad';
$string['name_help'] = 'Bu, flexpage\'inizin adı olup menülerde ve benzeri yerlerde kurs kullanıcılarına görünecektir.';
$string['formnamerequired'] = 'Flexpage adı gerekli bir alandır.';
$string['regionwidths'] = 'Blok bölgesi genişlikleri';
$string['regionwidths_help'] = 'Her blok bölgesinin ne genişlikte olabileceği piksel cinsinden belirtilebilir. Örneğin sol taraf 200, ana bölüm 500 ve sağ taraf 200 olarak ayarlanabilir. Lütfen kullanılabilir bölgelerin ve adlarının temadan temaya değiştirilebildiğini göz önünde bulundurun.';
$string['managepages'] = 'Flexpage\'leri yönet';
$string['managepages_help'] = 'Bu pencereden tüm flexpage\'lerin dizinini görüntüleyebilir; belirli bir flexpage\'i hızlıca yönetebilir, taşıyabilir veya silebilir; görüntüleme ayarlarını değiştirebilir ve gezinme ayarlarını kontrol edebilirsiniz.';
$string['pagename'] = 'Flexpage adı';
$string['deletepage'] = 'Flexpage\'i sil';
$string['deletepageaction'] = 'Flexpage\'i sil';
$string['areyousuredeletepage'] = '<strong>{$a}</strong> kodlu flexpage\'i tamamen silmek istediğinize emin misiniz?';
$string['deletedpagex'] = '"{$a}" kodlu flexpage silindi';
$string['flexpage:managepages'] = 'Flexpage\'leri yönet';
$string['pagexnotavailable'] = '{$a} kullanılabilir durumda değil';
$string['pagenotavailable'] = 'Mevcut değil';
$string['pagenotavailable_help'] = 'Bu flexpage, sizin erişiminize açık değil. Flexpage\'i görüntülemek için karşılamanız gereken koşulların listesi aşağıdadır.';
$string['sectionname'] = 'Bölüm';
$string['page'] = 'Flexpage';
$string['copydotdotdot'] = 'Kopyala...';
$string['nextpage'] = 'Sonraki >';
$string['next'] = 'Sonraki';
$string['previouspage'] = '< Önceki';
$string['previous'] = 'Önceki';
$string['themelayoutmissing'] = 'Geçerli temanız flexpage\'i desteklemiyor. Lütfen temayı (veya etkinleştirildiyse kurs temasını veya profilinizdeki tercih ettiğiniz temayı) "{$a}" sayfa düzenine sahip olanla değiştirin.';
$string['deletemodwarn'] = 'Bu etkinlik burada silindiği takdirde tüm flexpage\'lerden de silinir.';
$string['continuedotdotdot'] = 'Devam et...';
$string['warning'] = 'Uyarı';
$string['actionbar'] = 'Eylem çubuğu';
$string['availablefrom'] = 'Şu tarihten itibaren erişime izin ver:';
$string['availablefrom_help'] = 'Bu flexpage, bu tarihten sonra kurs kullanıcılarının erişimine açık olacaktır.';
$string['availableuntil'] = 'Şu tarihe kadar erişime izin ver:';
$string['availableuntil_help'] = 'Bu flexpage, bu tarihe kadar kurs kullanıcılarının erişimine açık olacaktır.';
$string['showavailability'] = 'Buna erişilebilmesi için önce';
$string['showavailability_help'] = 'Flexpage, kullanıcının erişimine açık değilse bu ayar, ya bu flexpage\'in kısıtlama bilgilerinin gösterileceğine ya da hiçbir şeyin gösterilmeyeceğine dair tercihi belirler.';
$string['nomoveoptionserror'] = 'Yerleştirmeye uygun bir konum olmadığından bu flexpage\'i taşıyamazsınız. Bu flexpage\'in öncesine veya sonrasına yeni flexpage\'ler eklemeyi deneyin.';
$string['frontpage'] = 'Ön sayfada flexpage kullan';
$string['frontpagedesc'] = 'Bu ayar, ön sayfada flexpage biçimini etkinleştirir.';
$string['hidefromothers'] = 'Flexpage\'i gizle';
$string['showfromothers'] = 'Flexpage\'i göster';
$string['jumptoflexpage'] = 'Bir flexpage\'e atla';
$string['preventactivityview'] = 'Şu anda sizin erişiminize açık olmayan bir flexpage\'de yer aldığından bu etkinliğe erişemezsiniz.';
$string['showavailability_hide'] = 'Flexpage\'i tümüyle gizle';
$string['showavailability_show'] = 'Flexpage\'i, kısıtlama bilgileriyle birlikte flu göster';
$string['display_help'] = 'Şu ayarlardan birini seçerek bu flexpage\'i yapılandır:
<ol>
<li>Düzenleyici olmayanlar için tümüyle gizli.</li>
<li>Kurs kullanıcıları tarafından görülebilir ancak flexpage menülerinde ve kurs gezintisinde görünmez.</li>
<li>Hem kurs kullanıcıları tarafından görülebilir hem de flexpage menülerinde ve kurs gezintisinde görünür.</li>
</ol>';
$string['addpages_help'] = 'Buradan kursunuza yeni flexpage\'ler ekleyebilirsiniz. Formda soldan sağa giderek:
<ol>
<li>Flexpage\'inizin adını girin (boş adlar eklenmez).</li>
<li>Sonraki <em>iki</em> aşağı açılan menü, yeni flexpage\'inizin flexpage\'ler dizini içinde nereye ekleneceğini belirler. Dolayısıyla
yeni flexpage\'inizi başka bir flexpage\'den önce, sonra veya onun bir alt öğesi (alt flexpage) olarak ekleyebilirsiniz.</li>
<li>(İsteğe bağlı) Son aşağı açılan menüde yeni oluşturduğunuz flexpage\'e kopyalamak üzere mevcut bir flexpage\'i seçebilirsiniz.</li>
</ol>
Bir kerede birden çok flexpage eklemek için "+" simgesini tıklatın ve yeni satıra girişinizi yapın. "+" simgesini birden çok kez tıklatırsanız flexpage adlarını boş bıraktığınız takdirde, adı olmayan flexpage\'ler eklenmeyecektir.';
$string['actionbar_help'] = '
<p>Kurs tasarımcıları, flexpage sayesinde kurs içinde birden çok flexpage oluşturabilir. Her flexpage, benzersiz veya paylaşılan içerik barındırabilir.</p>

<p>Eylem çubuğu ile kurs içindeki farklı flexpage\'lere 
aşağı açılan menüdeki flexpage\'in adını tıklatarak gidilebilir.</p>

<p><strong>Ekle</strong> Eylem çubuğu menü öğesi altındaki kullanılabilir eylemler:
<ul>
<li>Flexpage eklemek için aşağı açılan menüden <strong>Flexpage ekle</strong> bağlantısını seçin. Yeni flexpage\'ler eklediğinizde,
bunların, flexpage\'lerin dizininde nereye konumlandırılacağını belirlemek isteyebilirsiniz. Flexpage\'ler, diğer flexpage\'lerin alt öğeleri (alt flexpage\'ler) olabilir. Alternatif olarak basit şekilde diğer flexpage\'lerden önce veya sonra yerleştirilebilirler. Yeni flexpage\'ler boş olabileceği gibi mevcut bir flexpage\'in kopyası da olabilir. Bir kursa birden çok flexpage eklemek için <strong>Flexpage ekle</strong> penceresinin sağ tarafındaki "+" simgesine basın.</li>
<li>Bu flexpage\'e yeni etkinlik eklemek için aşağı açılan menüden <strong>Etkinlik ekle</strong> komutunu seçin. Yeni etkinliği flexpage\'de hangi konuma
yerleştirmek istediğinizi <strong>Etkinlik ekle</strong> penceresinin üst kısmında yer alan düğmelerden birini seçerek belirleyin. Daha sonra kursa ve flexpage\'e eklemek istediğiniz etkinliği veya kaynağı seçin.</li>
<li>Bu flexpage\'e mevcut etkinliklerden birini eklemek için aşağı açılan menüden <strong>Mevcut etkinliği ekle</strong>\'yi seçin.
Mevcut etkinliği flexpage’de nereye yerleştireceğinizi, <strong>Mevcut etkinliği ekle</strong> penceresinin üst kısmında yer alan düğmelerden birini seçerek belirleyin. Daha sonra, bu flexpage\'e eklemek istediğiniz etkinliklerin yanına işaret koyun. Son olarak işlemi tamamlamak için pencerenin en altındaki "Etkinlikleri ekle" düğmesini tıklatın.</li>
<li>Bu flexpage\'e blok eklemek için aşağı açılan menüden <strong>Blok ekle</strong> komutunu seçin. Bloku flexpage\'de 
hangi konuma yerleştireceğinizi <strong>Blok ekle</strong> penceresinin üst kısmında yer alan düğmelerden birini seçerek belirleyin. Daha sonra kursa eklemek istediğiniz blokun adını tıklatın.</li>
<li>Bu flexpage\'e mevcut bir menüyü eklemek için aşağı açılan menüden <strong>Mevcut menüyü ekle</strong> komutunu seçin. Bloku flexpage\'de
hangi konuma yerleştireceğinizi <strong>Mevcut menüyü ekle</strong> penceresinin üst kısmında yer alan düğmelerden birini seçerek belirleyin. Daha sonra kursa eklemek istediğiniz menünün adını tıklatın.</li>
</ul>
</p>

<p><strong>Yönet</strong> Eylem çubuğu menü öğesi altında kullanılabilir eylemler:
<ul>
<li>Bu flexpage\'in ayarlarını yapılandırmak için aşağı açılan 
menüden <strong>Flexpage ayarlarını yönet</strong> bağlantısını tıklatın. Bu pencereden flexpage\'lerde "önceki ve sonraki" gezinme düğmeleri olup olmayacağını belirtmenin yanında flexpage\'in adını düzenleyebilir, flexpage bölgelerinin genişliğini değiştirebilir, flexpage\'in gizli, görünür veya menülerde görünür olup olmayacağını belirtebilirsiniz.</li>
<li>Bir flexpage\'i taşımak için aşağı açılan menüden <strong>Flexpage\'i taşı </strong> bağlantısını tıklatın. Bu pencereden bir flexpage\'in 
başka bir flexpage\'in alt öğesi olup olmadığını veya bir dizinde başka bir flexpage\'den önce mi sonra mı geldiğini seçebilirsiniz.</li>
<li>Bir flexpage\'i silmek için aşağı açılan menüden <strong>Flexpage\'i sil</strong> bağlantısını tıklatın. Bu pencereden
geçerli flexpage\'i silmek istediğinizi onaylayabilirsiniz.</li>
<li>Birden çok flexpage\'e ilişkin ayarları yönetmek için aşağı açılan
menüden <strong>Tüm flexpage\'leri yönet</strong> bağlantısını tıklatın. Bu pencereden gezinme ayarlarını kontrol etmenin yanında tüm flexpage\'lerin dizinini görüntüleyebilir, belirli flexpage\'leri hızlıca yönetebilir, taşıyabilir veya silebilir, görüntüleme ayarlarını değiştirebilirsiniz.</li>
<li>Kursunuzun sekmelerini yönetmek için diğer menülerde olduğu gibi
aşağı açılan menüden <strong>Tüm menüleri yönet</strong> bağlantısını tıklatın. Bu pencereden menüler oluşturabilir, bunun yanında menüleri hızlıca düzenleyebilir, silebilir ve menülerdeki bağlantıları yönetebilirsiniz.</li>
</ul>
</p>';
$string['none'] = '(yok)';
$string['grade_atleast'] = 'en az olması gereken';
$string['grade_upto'] = 've küçüktür';
$string['contains'] = 'içerir';
$string['doesnotcontain'] = 'içermiyor';
$string['isempty'] = 'boş';
$string['isequalto'] = 'eşittir';
$string['isnotempty'] = 'boş değil';
$string['endswith'] = 'ile biter';
$string['startswith'] = 'ile başlar';
$string['completion_complete'] = 'tamamlandı olarak işaretlenmiş olmalı';
$string['completion_fail'] = 'kırık notla tamamlanmış olmalı';
$string['completion_incomplete'] = 'tamamlandı olarak işaretlenmemiş olmalı';
$string['completion_pass'] = 'geçme notuyla tamamlanmış olmalı';
$string['completioncondition'] = 'Etkinlik tamamlama koşulu';
$string['completioncondition_help'] = 'Bu ayar, flexpage\'e erişmek için karşılanması gereken etkinlik tamamlama koşullarını belirler. Bir etkinlik tamamlama koşulu ayarlanabilmesi için önce tamamlama izlemenin ayarlanması gerektiğini unutmayın.

İstenirse birden çok etkinlik tamamlama koşulu ayarlanabilir. Birden çok koşul olursa flexpage\'e erişime yalnızca TÜM etkinlik tamamlama koşulları karşılandığında izin verilir.';
$string['gradecondition'] = 'Not koşulu';
$string['gradecondition_help'] = 'Bu ayar, flexpage\'e erişmek için karşılanması gereken not koşullarını belirler.

İstenirse birden çok not koşulu ayarlanabilir. Birden çok koşul olursa flexpage, yalnızca TÜM not koşulları karşılandığında erişime izin verir.';
$string['userfield'] = 'Kullanıcı alanı';
$string['userfield_help'] = 'Kullanıcılar profilindeki herhangi bir alana dayalı olarak erişimi kısıtlayabilirsiniz.';
$string['releasecode'] = 'Sürüm kodu';
$string['releasecode_help'] = 'Bu kurs öğesi öğrenciler buraya girilen sürüm kodunu edinene kadar onlara açık olmayacaktır.';
