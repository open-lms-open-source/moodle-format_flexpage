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

$string['pluginname'] = 'تنسيق Flexpage';
$string['defaultcoursepagename'] = 'flexpage الافتراضي (تغييري)';
$string['pagenotfound'] = 'flexpage بالمعرف = {$a} غير موجود في هذا المقرر الدراسي.';
$string['addmenu'] = 'إضافة';
$string['managemenu'] = 'إدارة';
$string['addactivityaction'] = 'إضافة نشاط';
$string['addpagesaction'] = 'إضافة تنسيقات flexpage';
$string['managepagesaction'] = 'إدارة كل تنسيقات flexpage';
$string['editpageaction'] = 'إدارة إعدادات flexpage';
$string['editpage'] = 'إعدادات Flexpage';
$string['movebefore'] = 'قبل';
$string['moveafter'] = 'بعد';
$string['movechild'] = 'كأول عنصر فرعي لـ';
$string['ajaxexception'] = 'خطأ في التطبيق: {$a}';
$string['addedpages'] = 'تنسيقات flexpage المضافة: {$a}';
$string['addpages'] = 'إضافة تنسيقات flexpage';
$string['error'] = 'خطأ';
$string['genericasyncfail'] = 'فشل الطلب لسبب غير معروف، يرجى محاولة تنفيذ الإجراء مرة أخرى.';
$string['close'] = 'إغلاق';
$string['gotoa'] = 'انتقال إلى "{$a}"';
$string['movepageaction'] = 'نقل flexpage';
$string['movepage'] = 'نقل flexpage';
$string['movepagea'] = 'نقل flexpage <strong>{$a}</strong>';
$string['movedpage'] = 'flexpage المنقول "{$a->movepage}" {$a->move} flexpage "{$a->refpage}"';
$string['addactivity'] = 'إضافة نشاط';
$string['addactivity_help'] = 'اختر المكان الذي تريد وضع النشاط الجديد فيه على flexpage عن طريق تحديد أحد الأزرار الموجودة في أعلى النافذة <strong>إضافة نشاط</strong>. ثم، اختر النشاط أو المورد الذي ترغب في إضافته إلى المقرر الدراسي أو flexpage.';
$string['addto'] = 'إضافة إلى المنطقة:';
$string['addexistingactivity'] = 'إضافة نشاط موجود';
$string['addexistingactivity_help'] = 'اختر المكان الذي تريد وضع النشاط الموجود فيه على flexpage عن طريق تحديد أحد الأزرار الموجودة في أعلى نافذة <strong>إضافة نشاط موجود</strong>. ثم، قم بوضع علامة بجانب الأنشطة التي ترغب في إضافتها إلى flexpage. وأخيرًا، انقر فوق الزر "إضافة أنشطة" الموجود في أسفل النافذة لإكمال الإجراء.';
$string['addexistingactivityaction'] = 'إضافة نشاط موجود';
$string['addactivities'] = 'إضافة أنشطة';
$string['addblock'] = 'إضافة كتلة';
$string['addblock_help'] = 'اختر المكان الذي تريد وضع الكتلة فيه على flexpage عن طريق تحديد أحد الأزرار الموجودة في أعلى النافذة <strong>إضافة كتلة</strong>. ثم، انقر فوق اسم الكتلة التي ترغب في إضافتها إلى المقرر الدراسي.';
$string['addblockaction'] = 'إضافة كتلة';
$string['block'] = 'حظر';
$string['displayhidden'] = 'مخفي';
$string['displayvisible'] = 'مرئي لكن ليس في القوائم';
$string['displayvisiblemenu'] = 'مرئي وفي القوائم';
$string['navnone'] = 'بدون تنقل';
$string['navprev'] = 'flexpage السابق فقط';
$string['navnext'] = 'flexpage التالي فقط';
$string['navboth'] = 'كل من flexpage السابق والتالي';
$string['navigation'] = 'التنقل';
$string['navigation_help'] = 'يُستخدم لعرض زري التالي و/أو السابق على flexpage هذا.  هذان الزران ينقلان المستخدم إلى flexpage المتاح التالي/السابق.';
$string['display'] = 'عرض';
$string['name'] = 'الاسم';
$string['name_help'] = 'ذلك هو اسم flexpage الخاص بك وسوف يظهر لمستخدم المقرر الدراسي في القوائم وما شابه.';
$string['formnamerequired'] = 'اسم flexpage هو حقل مطلوب.';
$string['regionwidths'] = 'عرض منطقة الكتلة';
$string['regionwidths_help'] = 'يمكن تحديد مقدار العرض الممكن لكل منطقة من الكتل بالبيكسل.  على سبيل المثال يمكن تعيين اليسار إلى 200 والرئيسي إلى 500 واليمين إلى 200.  ويرجى ملاحظة أنه بالرغم من ذلك يمكن تغيير المناطق المتاحة وأسمائها من شكل إلى شكل.';
$string['managepages'] = 'إدارة تنسيقات flexpage';
$string['managepages_help'] = 'من تلك النافذة، يمكنك عرض فهرس كل تنسيقات flexpage، وإدارة أو نقل أو حذف تنسيقات flexpage الفردية بسرعة؛ وتغيير إعدادات العرض؛ والتحكم في إعدادات التنقل.';
$string['pagename'] = 'اسم Flexpage';
$string['deletepage'] = 'حذف flexpage';
$string['deletepageaction'] = 'حذف flexpage';
$string['areyousuredeletepage'] = 'هل تريد بالتأكيد حذف flexpage <strong>{$a}</strong> بصفة دائمة؟';
$string['deletedpagex'] = 'flexpage المحذوف "{$a}"';
$string['flexpage:managepages'] = 'إدارة تنسيقات flexpage';
$string['pagexnotavailable'] = '{$a} غير متاح';
$string['pagenotavailable'] = 'غير متاح';
$string['pagenotavailable_help'] = 'flexpage هذا غير متاح لك.  فيما يلي قائمة بالشروط التي عليك استيفاؤها من أجل عرض flexpage.';
$string['sectionname'] = 'القسم';
$string['page'] = 'Flexpage';
$string['copydotdotdot'] = 'نسخ...';
$string['nextpage'] = 'التالي >';
$string['next'] = 'التالي';
$string['previouspage'] = '< السابق';
$string['previous'] = 'السابق';
$string['themelayoutmissing'] = 'الشكل الحالي الخاص بك لا يدعم Flexpage.  يرجى تغيير الشكل (أو في حالة التمكين، شكل المقرر الدراسي أو الشكل المفضل في ملف تعريفك) إلى شكل يمتلك التخطيط "{$a}".';
$string['deletemodwarn'] = 'إذا تم حذف هذا النشاط، فسوف تتم إزالته من كل تنسيقات flexpage.';
$string['continuedotdotdot'] = 'متابعة...';
$string['warning'] = 'تحذير';
$string['actionbar'] = 'شريط الإجراءات';
$string['availablefrom'] = 'السماح بالوصول من';
$string['availablefrom_help'] = 'سيكون flexpage هذا متاح لمستخدمي المقرر الدراسي بعد هذا التاريخ.';
$string['availableuntil'] = 'السماح بالوصول حتى';
$string['availableuntil_help'] = 'سيكون flexpage هذا متاح لمستخدمي المقرر الدراسي قبل هذا التاريخ.';
$string['showavailability'] = 'قبل هذا يمكن الوصول إليه';
$string['showavailability_help'] = 'إذا كان flexpage غير متاح للمستخدم، فإن هذا الإعداد يحدد إذا ما كان يتم عرض معلومات تقييد flexpage هذا أو عدم عرض شيء على الإطلاق.';
$string['nomoveoptionserror'] = 'لا يمكنك نقل flexpage هذا لعدم توفر مواضع لوضع flexpage هذا بها.  حاول إضافة تنسيقات flexpage جديدة قبل أو بعد flexpage هذا.';
$string['frontpage'] = 'استخدام Flexpage بالصفحة الأمامية';
$string['frontpagedesc'] = 'سيؤدي ذلك إلى تمكين تنسيق Flexpage بالصفحة الأمامية.';
$string['hidefromothers'] = 'إخفاء flexpage';
$string['showfromothers'] = 'إظهار flexpage';
$string['jumptoflexpage'] = 'انتقال إلى flexpage';
$string['preventactivityview'] = 'لا يمكنك الوصول إلى هذا النشاط بعد لأنه موجود في flexpage غير متاح لك حاليًا.';
$string['showavailability_hide'] = 'إخفاء flexpage بالكامل';
$string['showavailability_show'] = 'إظهار flexpage بلون باهت مع معلومات التقييد';
$string['display_help'] = 'تكوين إذا ما كان flexpage هذا:
<ol>
<li>مخفي تمامًا عن غير المحررين.</li>
<li>مرئي لمستخدمي المقرر الدراسي، لكن لا يظهر في قوائم Flexpage والتنقل عبر المقررات الدراسية.</li>
<li>مرئي لمستخدمي المقرر الدراسي ويظهر في قوائم Flexpage والتنقل عبر المقررات الدراسية.</li>
</ol>';
$string['addpages_help'] = 'من هنا يمكنك إضافة تنسيقات flexpage جديدة إلى مقررك الدراسي.  الانتقال من اليسار إلى اليمين على النموذج:
<ol>
<li>أدخل اسم flexpage الخاص بك (لا تتم إضافة الأسماء الفارغة).</li>
<li>القائمتان المنسدلتان <em>التاليتان</em> تحددان مكان إضافة flexpage الجديد في فهرس تنسيقات flexpage .  وبذلك، يمكنك 
إضافة flexpage الجديد الخاص بك قبل flexpage آخر أو بعده أو كفرع له (flexpage فرعي).</li>
<li>(اختياري) في القائمة المنسدلة الأخيرة، يمكنك اختيار flexpage موجود لنسخه إلى flexpage المنشأ حديثًا الخاص بك.</li>
</ol>
لإضافة أكثر من flexpage في الوقت ذاته، انقر فوق الرمز "+" واملأ الصف الجديد.  إذا نقرت فوق الرمز "+" عدة مرات، فلا يؤدي ذلك إلا إلى تعويض أسماء flexpage بفراغات ولن تتم إضافتها.';
$string['actionbar_help'] = '
<p>باستخدام Flexpage، يمكن لمصممي المقررات الدراسية إنشاء تنسيقات flexpage متعددة داخل المقرر الدراسي. يمكن لكل flexpage أن يمتلك محتوى فريدًا أو مشتركًا فيها.</p>

<p>باستخدام شريط الإجراءات، يمكن الانتقال إلى تنسيقات flexpage المختلفة في المقرر الدراسي من خلال النقر فوق اسم flexpage في
القائمة المنسدلة.</p>

<p>الإجراءات المتاحة أسفل عنصر قائمة شريط الإجراءات <strong>إضافة</strong>:
<ul>
<li>لإضافة flexpage، حدد الرابط <strong>إضافة تنسيقات flexpage</strong> من القائمة المنسدلة. عند إضافة تنسيقات flexpage جديدة
، سوف ترغب في تحديد مكان وضعها في فهرس تنسيقات flexpage. يمكن لتنسيقات Flexpage أن تكون فروعًا لتنسيقات flexpages أخرى (flexpages فرعية). أو يمكن وضعها ببساطة قبل أو بعد تنسيقات flexpages الأخرى. كما يمكن أن تكون تنسيقات flexpages الجديدة فارغة أو نسخة من flexpage موجود. لإضافة تنسيقات flexpage متعددة إلى مقرر دراسي، اضغط على الرمز "+" بالجانب الأيسر من النافذة <strong>إضافة flexpages</strong>.</li>
<li>لإضافة نشاط جديد إلى flexpage هذا، حدد <strong>إضافة نشاط</strong> من القائمة المنسدلة.  اختر المكان
الذي تريد وضع النشاط الجديد فيه على flexpage عن طريق تحديد أحد الأزرار الموجودة في أعلى النافذة <strong>إضافة نشاط</strong>. ثم، اختر النشاط أو المورد الذي ترغب في إضافته إلى المقرر الدراسي أو flexpage.</li>
<li>لإضافة نشاط موجود إلى flexpage هذا، حدد <strong>إضافة نشاط موجود</strong> من القائمة المنسدلة.
اختر المكان الذي تريد وضع النشاط الموجود فيه على flexpage عن طريق تحديد أحد الأزرار الموجودة في أعلى نافذة <strong>إضافة نشاط موجود</strong>. ثم، قم بوضع علامة بجانب الأنشطة التي ترغب في إضافتها إلى flexpage. وأخيرًا، انقر فوق الزر "إضافة أنشطة" الموجود في أسفل النافذة لإكمال الإجراء.</li>
<li>لإضافة كتلة إلى flexpage هذا، حدد <strong>إضافة كتلة</strong> من القائمة المنسدلة. اختر المكان
الذي تريد وضع الكتلة فيه على flexpage عن طريق تحديد أحد الأزرار الموجودة في أعلى النافذة <strong>إضافة كتلة</strong>. ثم، انقر فوق اسم الكتلة التي ترغب في إضافتها إلى المقرر الدراسي.</li>
<li>لإضافة قائمة موجودة إلى flexpage هذا، حدد <strong>إضافة قائمة موجودة</strong> من القائمة المنسدلة. اختر المكان
الذي تريد وضع الكتلة فيه على flexpage عن طريق تحديد أحد الأزرار الموجودة في أعلى النافذة <strong>إضافة قائمة موجودة</strong>. ثم، انقر فوق اسم القائمة التي ترغب في إضافتها إلى المقرر الدراسي.</li>
</ul>
</p>

<p>الإجراءات المتاحة أسفل عنصر قائمة شريط الإجراءات <strong>إدارة</strong>:
<ul>
<li>لتكوين إعدادات flexpage هذا، انقر فوق الرابط <strong>إدارة إعدادات flexpage</strong> من
القائمة المنسدلة. من هذه النافذة، يمكنك تحرير اسم flexpage؛ وتغيير عرض مناطق flexpage؛ والإشارة إلى ما إذا يجب أن يكون flexpage مخفيًا أم مرئيًا أم مرئيًا في القوائم؛ وكذلك تحديد ما إذا يجب أن يتضمن flexpages زري التنقل ."السابق والتالي" أم لا.</li>
<li>لنقل flexpage، انقر فوق الرابط <strong>نقل flexpage</strong> من القائمة المنسدلة. من هذه النافذة، يمكنك
اختيار إذا ما كان flexpage فرعًا لـ flexpage آخر أم لا، أو إذا ما كان قبل أو بعد flexpage آخر في الفهرس.</li>
<li>لحذف flexpage، انقر فوق الرابط <strong>حذف flexpage</strong> من القائمة المنسدلة. من هذه النافذة، يمكنك
التأكيد على أنك تريد حذف flexpage الحالي.</li>
<li>لإدارة إعدادات تنسيقات flexpage متعددة، انقر فوق الرابط <strong>إدارة كل تنسيقات flexpage</strong> من
القائمة المنسدلة. من تلك النافذة، يمكنك عرض فهرس كل تنسيقات flexpage؛ وإدارة أو نقل أو حذف تنسيقات flexpage الفردية بسرعة؛ وتغيير إعدادات العرض؛ والتحكم في إعدادات التنقل.</li>
<li>لإدارة علامات التبويب لمقررك الدراسي، وكذلك القوائم الأخرى، انقر فوق الرابط <strong>إدارة كل القوائم</strong> من 
القائمة المنسدلة. من هذه النافذة، يمكنك إنشاء قوائم، وكذلك تحرير القوائم وحذفها وإدارة الروابط في القوائم بسرعة.</li>
</ul>
</p>';
$string['none'] = '(لا شيء)';
$string['grade_atleast'] = 'يجب أن لا يقل عن';
$string['grade_upto'] = 'وأقل من';
$string['contains'] = 'يحتوي على';
$string['doesnotcontain'] = 'لا يحتوي على';
$string['isempty'] = 'فارغ';
$string['isequalto'] = 'يساوي';
$string['isnotempty'] = 'غير فارغ';
$string['endswith'] = 'ينتهي بـ';
$string['startswith'] = 'يبدأ بـ';
$string['completion_complete'] = 'يجب وضع علامة مكتمل عليه';
$string['completion_fail'] = 'يجب إكماله بتقدير راسب';
$string['completion_incomplete'] = 'لا يجب وضع علامة مكتمل عليه';
$string['completion_pass'] = 'يجب إكماله بتقدير ناجح';
$string['completioncondition'] = 'شرط اكتمال النشاط';
$string['completioncondition_help'] = 'هذا الإعداد يحدد شروط اكتمال النشاط التي يجب استيفاؤها من أجل الوصول إلى flexpage. لاحظ أنه يجب تعيين تتبع الإكمال أولاً قبل التمكن من تعيين شرط اكتمال النشاط.

كما يمكن تعيين شروط متعددة لاكتمال النشاط حسب الرغبة.  إذا كان الأمر كذلك، فسوف يكون الوصول إلى flexpage مسموحًا فقط عند استيفاء كل شروط اكتمال النشاط.';
$string['gradecondition'] = 'شرط التقدير';
$string['gradecondition_help'] = 'هذا الإعداد يحدد شروط التقدير التي يجب استيفاؤها من أجل الوصول إلى flexpage.

ويمكن تعيين شروط تقدير متعددة حسب الرغبة. إذا كان الأمر كذلك، فلن يسمح flexpage بالوصول إلا عند استيفاء كل شروط التقدير.';
$string['userfield'] = 'حقل المستخدم';
$string['userfield_help'] = 'يمكنك تقييد الوصول على أساس أي حقل من ملف تعريف المستخدمين.';
$string['releasecode'] = 'رمز النشر';
$string['releasecode_help'] = 'لن يكون عنصر المقرر الدراسي هذا متاحًا للطلاب حتى يحصل الطالب على رمز النشر المدخل هنا.';
