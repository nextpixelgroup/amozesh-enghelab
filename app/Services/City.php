<?php

namespace App\Services;
class City
{
    /**
     * داده‌های اصلی تقسیمات کشوری
     * ساختار: [slug_ostan => ['label' => 'نام فارسی', 'cities' => [slug_shahr => 'نام فارسی']]]
     */
    private static array $data = [
            'alborz' => [
                'label' => 'البرز',
                'cities' => [
                    'karaj' => 'کرج', 'fardis' => 'فردیس', 'kamalshahr' => 'کمال‌شهر', 'nazarabad' => 'نظرآباد',
                    'mohammadshahr' => 'محمدشهر', 'hashtgerd' => 'هشتگرد', 'mahsht' => 'ماهدشت', 'meshkindasht' => 'مشکین‌دشت',
                    'chaharbagh' => 'چهارباغ', 'shahr_e_jadid_e_hashtgerd' => 'شهر جدید هشتگرد', 'eshtehard' => 'اشتهارد',
                    'garmdarreh' => 'گرمدره', 'taleqan' => 'طالقان', 'kouhsar' => 'کوهسار', 'tankaman' => 'تنکمان',
                    'asara' => 'آسارا', 'palangabad' => 'پلنگ‌آباد'
                ]
            ],
            'ardabil' => [
                'label' => 'اردبیل',
                'cities' => [
                    'ardabil' => 'اردبیل', 'parsabad' => 'پارس‌آباد', 'meshgin_shahr' => 'مشگین‌شهر', 'khalkhal' => 'خلقال',
                    'germi' => 'گرمی', 'bileh_savar' => 'بیله‌سوار', 'namin' => 'نمین', 'jafarabad' => 'جعفرآباد',
                    'kiivi' => 'گیوی', 'abi_beyglu' => 'آبی‌بیگلو', 'sareyn' => 'سرعین', 'nir' => 'نیر',
                    'aslanduz' => 'اصلاندوز', 'hashtjin' => 'هشجین', 'anbaran' => 'عنبران', 'taze_kand' => 'تازه کند',
                    'kolowr' => 'کلور', 'lahrud' => 'لاهرود', 'hir' => 'هیر', 'razi' => 'رضی', 'fakhravar' => 'فخرآباد'
                ]
            ],
            'east_azerbaijan' => [
                'label' => 'آذربایجان شرقی',
                'cities' => [
                    'tabriz' => 'تبریز', 'maragheh' => 'مراغه', 'marand' => 'مرند', 'miyaneh' => 'میانه',
                    'ahar' => 'اهر', 'bonab' => 'بناب', 'sarab' => 'سراب', 'sahand' => 'سهند',
                    'jolfa' => 'جلفا', 'shabestar' => 'شبستر', 'azarshahr' => 'آذرشهر', 'malekan' => 'ملکان',
                    'kaleibar' => 'کلیبر', 'varzaqan' => 'ورزقان', 'harris' => 'هریس', 'osku' => 'اسکو',
                    'bostanabad' => 'بستان‌آباد', 'ajab_shir' => 'عجب‌شیر', 'hashtrud' => 'هشترود', 'khosrowshah' => 'خسروشاه',
                    'ilkhchi' => 'ایلخچی', 'basmenj' => 'باسمنج', 'mamqan' => 'ممقان', 'gugan' => 'گوگان',
                    'yamchi' => 'یامچی', 'khajeh' => 'خواجه', 'shendabad' => 'شندآباد', 'sofirlan' => 'صوفیان'
                ]
            ],
            'west_azerbaijan' => [
                'label' => 'آذربایجان غربی',
                'cities' => [
                    'urmia' => 'ارومیه', 'khoy' => 'خوی', 'miandoab' => 'میاندوآب', 'mahabad' => 'مهاباد',
                    'bukan' => 'بوکان', 'piranshahr' => 'پیرانشهر', 'salmas' => 'سلماس', 'naghadeh' => 'نقده',
                    'maku' => 'ماکو', 'sardasht' => 'سردشت', 'shahin_dezh' => 'شاهین‌دژ', 'takab' => 'تکاب',
                    'oshnavieh' => 'شنوایه', 'shot' => 'شوط', 'chaldoran' => 'چالدران', 'qarah_zia_od_din' => 'قره‌ضیاءالدین',
                    'poldasht' => 'پلدشت', 'bazargan' => 'بازرگان', 'mohammadyar' => 'محمدیار', 'firurq' => 'فیرورق'
                ]
            ],
            'isfahan' => [
                'label' => 'اصفهان',
                'cities' => [
                    'isfahan' => 'اصفهان', 'kashan' => 'کاشان', 'khomeini_shahr' => 'خمینی‌شهر', 'najaafabad' => 'نجف‌آباد',
                    'shahin_shahr' => 'شاهین‌شهر', 'shahreza' => 'شهرضا', 'fuladshahr' => 'فولادشهر', 'baharestan' => 'بهارستان',
                    'mobarakeh' => 'مبارکه', 'aran_bidgol' => 'آران و بیدگل', 'golpayegan' => 'گلپایگان', 'zarrin_shahr' => 'زرین‌شهر',
                    'dorcheh' => 'درچه', 'falavarjan' => 'فلاورجان', 'semirom' => 'سمیرم', 'naein' => 'نائین',
                    'khansar' => 'خوانسار', 'qahderijan' => 'قهدریجان', 'ardestan' => 'اردستان', 'fereydunshahr' => 'فریدون‌شهر',
                    'natanz' => 'نطنز', 'gaz' => 'گزبرخوار', 'kelishad' => 'کلیشاد و سودرجان', 'abrisham' => 'ابریشم',
                    'khur_biabanak' => 'خور', 'chadegan' => 'چادگان', 'tir_o_karvan' => 'تیران', 'daran' => 'داران'
                ]
            ],
            'ilam' => [
                'label' => 'ایلام',
                'cities' => [
                    'ilam' => 'ایلام', 'dehloran' => 'دهلران', 'eyvan' => 'ایوان', 'abdanan' => 'آبدانان',
                    'darreh_shahr' => 'دره‌شهر', 'mehran' => 'مهران', 'sarableh' => 'سرابله', 'arkvaz' => 'ارکواز',
                    'asemanabad' => 'آسمان‌آباد', 'chavar' => 'چوار', 'badreh' => 'بدره', 'pahleh' => 'پهله',
                    'meymeh_ilam' => 'میمه', 'lumar' => 'لومار', 'towhid' => 'توحید'
                ]
            ],
            'bushehr' => [
                'label' => 'بوشهر',
                'cities' => [
                    'bushehr' => 'بوشهر', 'borazjan' => 'برازجان', 'bandar_ganaveh' => 'بندر گناوه', 'bandar_kangan' => 'بندر کنگان',
                    'khormuj' => 'خورموج', 'jam' => 'جم', 'bandar_deylam' => 'بندر دیلم', 'bandar_deyr' => 'بندر دیر',
                    'ali_shahr' => 'عالی‌شهر', 'abpakhsh' => 'آب‌پخش', 'nakhl_taqi' => 'نخل تقی', 'chah_mobarak' => 'چاه‌مبارک',
                    'ahram' => 'اهرم', 'bank' => 'بنک', 'asaluyeh' => 'عسلویه', 'kakaki' => 'کاِکی',
                    'vahdatiyeh' => 'وحدتیه', 'shabankareh' => 'شبانکاره', 'bandar_rig' => 'بندر ریگ'
                ]
            ],
            'tehran' => [
                'label' => 'تهران',
                'cities' => [
                    'tehran' => 'تهران', 'eslamshahr' => 'اسلام‌شهر', 'shahriar' => 'شهریار', 'qods' => 'شهر قدس',
                    'malard' => 'ملارد', 'golestan' => 'گلستان', 'pakdasht' => 'پاکدشت', 'qarchak' => 'قرچک',
                    'varamin' => 'ورامین', 'nasim_shahr' => 'نسیم‌شهر', 'andisheh' => 'اندیشه', 'robat_karim' => 'رباط‌کریم',
                    'parand' => 'پرند', 'baghestan' => 'باغستان', 'pardis' => 'پردیس', 'bumahen' => 'بومهن',
                    'baqershahr' => 'باقرشهر', 'pishva' => 'پیشوا', 'salehieh' => 'صالحیه', 'sabashahr' => 'صباشهر',
                    'damavand' => 'دماوند', 'kahrizak' => 'کهریزک', 'ferdowsieh' => 'فردوسیه', 'vahidieh' => 'وحیدیه',
                    'safadasht' => 'صفادشت', 'nasiroshahr' => 'نصیرشهر', 'shahedshahr' => 'شاهدشهر', 'rudehen' => 'رودهن',
                    'hasanabad' => 'حسن‌آباد', 'firuzkuh' => 'فیروزکوه', 'lavasan' => 'لواسان', 'fasham' => 'فشم',
                    'shemshak' => 'شمشک', 'ahmadabad_mostowfi' => 'احمدآباد مستوفی', 'sharifabad' => 'شریف‌آباد'
                ]
            ],
            'chaharmahal_and_bakhtiari' => [
                'label' => 'چهارمحال و بختیاری',
                'cities' => [
                    'shahrekord' => 'شهرکرد', 'borujen' => 'بروجن', 'lordegan' => 'لردگان', 'farrokhshahr' => 'فرخ‌شهر',
                    'farsan' => 'فارسان', 'hefshejan' => 'هفشجان', 'juneqan' => 'جونقان', 'saman' => 'سامان',
                    'feradunshahr' => 'فرادنبه', 'ben' => 'بن', 'kian' => 'کیان', 'soureshjan' => 'سورشجان',
                    'boldaji' => 'بلداجی', 'ardal' => 'اردل', 'babaheydar' => 'باباحیدر', 'shalamzar' => 'شلمزار',
                    'gandoman' => 'گندمان', 'gahru' => 'گهرو', 'koohrang' => 'چلگرد (کوهرنگ)'
                ]
            ],
            'south_khorasan' => [
                'label' => 'خراسان جنوبی',
                'cities' => [
                    'birjand' => 'بیرجند', 'qaen' => 'قائن', 'tabas' => 'طبس', 'ferdows' => 'فردوس',
                    'nehbandan' => 'نهبندان', 'bashruyeh' => 'بشرویه', 'sarayan' => 'سرایان', 'sarbisheh' => 'سربیشه',
                    'eslamiyeh' => 'اسلامیه', 'hajjiabad' => 'حاجی‌آباد', 'khusf' => 'خوسف', 'mood' => 'مود',
                    'asadiyeh' => 'اسدیه', 'arisk' => 'آیسک', 'nimbeluk' => 'نیمبلوک'
                ]
            ],
            'khorasan_razavi' => [
                'label' => 'خراسان رضوی',
                'cities' => [
                    'mashhad' => 'مشهد', 'nishapur' => 'نیشابور', 'sabzevar' => 'سبزوار', 'torbat_heydariyeh' => 'تربت حیدریه',
                    'kashmar' => 'کاشمر', 'quchan' => 'قوچان', 'torbat_jam' => 'تربت جام', 'taybad' => 'تایباد',
                    'chenaran' => 'چناران', 'sarakhs' => 'سرخس', 'gonabad' => 'گناباد', 'fariman' => 'فریمان',
                    'golbahar' => 'گلبهار', 'darjaz' => 'درگز', 'khaf' => 'خواف', 'bardaskan' => 'بردسکن',
                    'torqabeh' => 'طرقبه', 'shandiz' => 'شاندیز', 'feizabad' => 'فیض‌آباد', 'neqab' => 'نقاب',
                    'bajestan' => 'بجستان', 'kalat' => 'کلات', 'khaliabad' => 'خلیل‌آباد', 'rhtkhvar' => 'رشتخوار'
                ]
            ],
            'north_khorasan' => [
                'label' => 'خراسان شمالی',
                'cities' => [
                    'bojnurd' => 'بجنورد', 'shirvan' => 'شیروان', 'esfarayen' => 'اسفراین', 'ashkhaneh' => 'آشخانه',
                    'jajarm' => 'جاجرم', 'garmeh' => 'گرمه', 'faruj' => 'فاروج', 'raz' => 'راز',
                    'ivar' => 'ایور', 'darq' => 'درق'
                ]
            ],
            'khuzestan' => [
                'label' => 'خوزستان',
                'cities' => [
                    'ahvaz' => 'اهواز', 'dezful' => 'دزفول', 'abadan' => 'آبادان', 'bandar_mahshahr' => 'بندر ماهشهر',
                    'andimeshk' => 'اندیمشک', 'khorramshahr' => 'خرمشهر', 'behbahan' => 'بهبهان', 'izeh' => 'ایذه',
                    'shushtar' => 'شوشتر', 'masjed_soleyman' => 'مسجد سلیمان', 'bandar_imam_khomeini' => 'بندر امام خمینی',
                    'ramhormoz' => 'رامهرمز', 'omididiyeh' => 'امیدیه', 'shush' => 'شوش', 'shadegan' => 'شادگان',
                    'susangerd' => 'سوسنگرد', 'chendran' => 'چمران', 'hendijan' => 'هندیجان', 'baghmalek' => 'باغ‌ملک',
                    'ramshir' => 'رامشیر', 'gotvand' => 'گتوند', 'hoveyzeh' => 'هویزه', 'lali' => 'لالی',
                    'haftkel' => 'هفتکل', 'bavi' => 'باوی (ملاثانی)', 'karun' => 'کارون'
                ]
            ],
            'zanjan' => [
                'label' => 'زنجان',
                'cities' => [
                    'zanjan' => 'زنجان', 'abhar' => 'ابهر', 'khorramdarreh' => 'خرمدره', 'qeydar' => 'قیدار',
                    'hidaj' => 'هیدج', 'sain_qaleh' => 'صائین‌قلعه', 'abbar' => 'آب‌بر', 'soltaniyeh' => 'سلطانیه',
                    'sajas' => 'سجاس', 'mahnshan' => 'ماهنام', 'zarrin_rud' => 'زرین‌رود', 'garmab' => 'گرماب'
                ]
            ],
            'semnan' => [
                'label' => 'سمنان',
                'cities' => [
                    'semnan' => 'سمنان', 'shahrud' => 'شاهرود', 'damghan' => 'دامغان', 'garmsar' => 'گرمسار',
                    'mehdishahr' => 'مهدی‌شهر', 'eyvanki' => 'ایوانکی', 'shahmirzad' => 'شهمیرزاد', 'bastam' => 'بسطام',
                    'aradan' => 'آرادان', 'sorkheh' => 'سرخه', 'kalateh_khij' => 'کلاته خیج', 'meyami' => 'میامی'
                ]
            ],
            'sistan_and_baluchestan' => [
                'label' => 'سیستان و بلوچستان',
                'cities' => [
                    'zahedan' => 'زاهدان', 'zabol' => 'زابل', 'iranshahr' => 'ایرانشهر', 'chabahar' => 'چابهار',
                    'saravan' => 'سراوان', 'khash' => 'خاش', 'konarak' => 'کنارک', 'jalq' => 'جالق',
                    'nik_shahr' => 'نیک‌شهر', 'pishin' => 'پیشین', 'suran' => 'سوران', 'zahak' => 'زهک',
                    'fanuj' => 'فنوج', 'sarbaz' => 'سرباز', 'mirjaveh' => 'میرجاوه', 'qasr_qand' => 'قصرقند',
                    'rasak' => 'راسک', 'bampur' => 'بمپور', 'delgan' => 'دلگان'
                ]
            ],
            'fars' => [
                'label' => 'فارس',
                'cities' => [
                    'shiraz' => 'شیراز', 'marvdasht' => 'مرودشت', 'jahrom' => 'جهرم', 'fasa' => 'فسا',
                    'kazerun' => 'کازرون', 'sadra' => 'صدرا', 'darab' => 'داراب', 'firuzabad' => 'فیروزآباد',
                    'lar' => 'لار', 'abadeh' => 'آباده', 'nurabad_fars' => 'نورآباد (ممسنی)', 'neyriz' => 'نی‌ریز',
                    'eqlid' => 'اقلید', 'estahban' => 'استهبان', 'gerash' => 'گراش', 'zarqan' => 'زرقان',
                    'kavar' => 'کوار', 'lamerd' => 'لامرد', 'safashahr' => 'صفاشهر', 'qaemiyeh' => 'قائمیه',
                    'hajjiabad_fars' => 'حاجی‌آباد (زرین‌دشت)', 'farashband' => 'فراشبند', 'qir' => 'قیر',
                    'evaz' => 'اوز', 'khonj' => 'خنج', 'kharameh' => 'خرامه', 'sarvestan' => 'سروستان',
                    'arsanjan' => 'ارسنجان', 'saadat_shahr' => 'سعادت‌شهر'
                ]
            ],
            'qazvin' => [
                'label' => 'قزوین',
                'cities' => [
                    'qazvin' => 'قزوین', 'alvand' => 'الوند', 'takestan' => 'تاکستان', 'abyek' => 'آبیک',
                    'mohammadyeh' => 'محمدیه', 'bidestan' => 'بیدستان', 'mahmudabad_nemuneh' => 'محمودآباد نمونه',
                    'buin_zahra' => 'بوئین‌زهرا', 'shal' => 'شال', 'esfarvarin' => 'اسفرورین', 'danesfahan' => 'دانصفهان',
                    'ziaabad' => 'ضیاءآباد', 'avaj' => 'آوج'
                ]
            ],
            'qom' => [
                'label' => 'قم',
                'cities' => [
                    'qom' => 'قم', 'qanavat' => 'قنوات', 'jafariyeh' => 'جعفریه', 'kahak' => 'کهک',
                    'dastjerd' => 'دستجرد', 'salafchegan' => 'سلفچگان'
                ]
            ],
            'kurdistan' => [
                'label' => 'کردستان',
                'cities' => [
                    'sanandaj' => 'سنندج', 'saqqez' => 'سقز', 'marivan' => 'مریوان', 'baneh' => 'بانه',
                    'qorveh' => 'قروه', 'kamyaran' => 'کامیاران', 'bijar' => 'بیجار', 'divandarreh' => 'دیواندره',
                    'dehgolan' => 'دهگلان', 'kanisur' => 'کانی‌سور', 'sarvabad' => 'سروآباد'
                ]
            ],
            'kerman' => [
                'label' => 'کرمان',
                'cities' => [
                    'kerman' => 'کرمان', 'sirjan' => 'سیرجان', 'rafsanjan' => 'رفسنجان', 'jiroft' => 'جیرفت',
                    'bam' => 'بم', 'zarand' => 'زرند', 'kahnuj' => 'کهنوج', 'shahr_e_babak' => 'شهربابک',
                    'baft' => 'بافت', 'bardsir' => 'بردسیر', 'barwat' => 'بروات', 'ravar' => 'راور',
                    'mohammadabad_kerman' => 'محمدآباد', 'najaf_shahr' => 'نجف‌شهر', 'mahan' => 'ماهان',
                    'anbarabad' => 'عنبرآباد', 'manujan' => 'منوجان', 'anar' => 'انار', 'rudbar' => 'رودبار جنوب',
                    'rabor' => 'رابر', 'qaleh_ganj' => 'قلعه‌گنج', 'kuhbanan' => 'کوهبنان', 'fahraj' => 'فهرج'
                ]
            ],
            'kermanshah' => [
                'label' => 'کرمانشاه',
                'cities' => [
                    'kermanshah' => 'کرمانشاه', 'eslamabad_e_gharb' => 'اسلام‌آباد غرب', 'javanrud' => 'جوانرود',
                    'kangavar' => 'کنگاور', 'sonqor' => 'سنقر', 'harsin' => 'هرسین', 'sahneh' => 'صحنه',
                    'paveh' => 'پاوه', 'ravansar' => 'روانسر', 'gilangharb' => 'گیلانغرب', 'qasr_e_shirin' => 'قصر شیرین',
                    'tazehabad' => 'تازه‌آباد', 'sarpol_e_zahab' => 'سرپل ذهاب', 'kerend_e_gharb' => 'کرند غرب'
                ]
            ],
            'kohgiluyeh_and_boyer_ahmad' => [
                'label' => 'کهگیلویه و بویراحمد',
                'cities' => [
                    'yasuj' => 'یاسوج', 'dogonbadan' => 'دوگنبدان (گچساران)', 'dehdasht' => 'دهدشت', 'likak' => 'لیکک',
                    'chamran_kohgiluyeh' => 'چرام', 'landeh' => 'لنده', 'basht' => 'باشت', 'sisakht' => 'سی‌سخت',
                    'suq' => 'سوق', 'dishmok' => 'دیشموک', 'qaleh_raisi' => 'قلعه رئیسی', 'pataveh' => 'پاتاوه'
                ]
            ],
            'golestan' => [
                'label' => 'گلستان',
                'cities' => [
                    'gorgan' => 'گرگان', 'gonbad_e_kavus' => 'گنبد کاووس', 'bandar_torkaman' => 'بندر ترکمن',
                    'aliabad_e_katul' => 'علی‌آباد کتول', 'azadshahr' => 'آزادشهر', 'kordkuy' => 'کردکوی',
                    'kalaleh' => 'کلاله', 'aq_qala' => 'آق‌قلا', 'minudasht' => 'مینودشت', 'galikash' => 'گالیکش',
                    'bandar_gaz' => 'بندر گز', 'gomishan' => 'گمیشان', 'siminshahr' => 'سیمین‌شهر', 'ramian' => 'رامیان',
                    'fazelabad' => 'فاضل‌آباد'
                ]
            ],
            'gilan' => [
                'label' => 'گیلان',
                'cities' => [
                    'rasht' => 'رشت', 'bandar_anzali' => 'بندر انزلی', 'lahijan' => 'لاهیجان', 'langarud' => 'لنگرود',
                    'talesh' => 'تالش (هشتپر)', 'astara' => 'آستارا', 'sowme_eh_sara' => 'صومعه‌سرا',
                    'astana_ye_ashrafiyeh' => 'آستانه اشرفیه', 'rudsar' => 'رودسر', 'fuman' => 'فومن',
                    'khomam' => 'خمام', 'siyahkal' => 'سیاهکل', 'rezvanshahr' => 'رضوانشهر', 'masal' => 'ماسال',
                    'manjil' => 'منجیل', 'amlash' => 'املش', 'kiashahr' => 'کیاشهر', 'rudbar_gilan' => 'رودبار',
                    'shaft' => 'شفت', 'kelachay' => 'کلاچای', 'lushan' => 'لوشان', 'kuchesfahan' => 'کوچصفهان'
                ]
            ],
            'lorestan' => [
                'label' => 'لرستان',
                'cities' => [
                    'khorramabad' => 'خرم‌آباد', 'borujerd' => 'بروجرد', 'dorud' => 'دورود', 'aligudarz' => 'الیگودرز',
                    'kuhdasht' => 'کوهدشت', 'nurabad_lorestan' => 'نورآباد', 'azna' => 'ازنا', 'aleshtar' => 'الشتر',
                    'poldokhtar' => 'پلدختر', 'kunani' => 'کونانی', 'mamulan' => 'معمولان', 'cheqabol' => 'چقابل'
                ]
            ],
            'mazandaran' => [
                'label' => 'مازندران',
                'cities' => [
                    'sari' => 'ساری', 'babol' => 'بابل', 'amol' => 'آمل', 'qaem_shahr' => 'قائم‌شهر',
                    'behshahr' => 'بهشهر', 'chalus' => 'چالوس', 'neka' => 'نکا', 'babolsar' => 'بابلسر',
                    'tonekabon' => 'تنکابن', 'nowshahr' => 'نوشهر', 'fereydunkenar' => 'فریدونکنار', 'ramsar' => 'رامسر',
                    'juybar' => 'جویبار', 'mahmudabad' => 'محمودآباد', 'amir_kola' => 'امیرکلا', 'nur' => 'نور',
                    'galugah' => 'گلوگاه', 'katalem' => 'کتالم و سادات‌شهر', 'kelardasht' => 'کلاردشت',
                    'abbasabad' => 'عباس‌آباد', 'rostamkola' => 'رستمکلا', 'chamestan' => 'چمستان'
                ]
            ],
            'markazi' => [
                'label' => 'مرکزی',
                'cities' => [
                    'arak' => 'اراک', 'saveh' => 'ساوه', 'khomein' => 'خمین', 'mahallat' => 'محلات',
                    'delijan' => 'دلیجان', 'mamuniyeh' => 'مأمونیه', 'shazand' => 'شازند', 'mohajeran' => 'مهاجران',
                    'tafresh' => 'تفرش', 'ashtian' => 'آشتیان', 'khondab' => 'خنداب', 'komijan' => 'کمیجان',
                    'parandak' => 'پرندک', 'zaviyeh' => 'زاویه', 'nimvar' => 'نیم‌ور'
                ]
            ],
            'hormozgan' => [
                'label' => 'هرمزگان',
                'cities' => [
                    'bandar_abbas' => 'بندرعباس', 'minab' => 'میناب', 'qeshm' => 'قشم', 'rudan' => 'رودان',
                    'bandar_lengeh' => 'بندر لنگه', 'kish' => 'کیش', 'hajjiabad_hormozgan' => 'حاجی‌آباد',
                    'kong' => 'کنگ', 'bandar_khamir' => 'بندر خمیر', 'jask' => 'جاسک', 'parsian' => 'پارسیان',
                    'bastak' => 'بستک', 'dargahan' => 'درگهان', 'bika' => 'بیکا', 'fin' => 'فین'
                ]
            ],
            'hamadan' => [
                'label' => 'همدان',
                'cities' => [
                    'hamadan' => 'همدان', 'malayer' => 'ملایر', 'nahavand' => 'نهاوند', 'asadabad' => 'اسدآباد',
                    'tuyserkan' => 'تویسرکان', 'bahar' => 'بهار', 'kabudarahang' => 'کبودرآهنگ', 'lalejin' => 'لالجین',
                    'famenin' => 'فامنین', 'razan' => 'رزان', 'maryanaj' => 'مریانج', 'qorveh_darjazin' => 'قروه درجزین',
                    'juraqan' => 'جورقان'
                ]
            ],
            'yazd' => [
                'label' => 'یزد',
                'cities' => [
                    'yazd' => 'یزد', 'meybod' => 'میبد', 'ardakan' => 'اردکان', 'bafq' => 'بافق',
                    'mehriz' => 'مهریز', 'abarkuh' => 'ابرکوه', 'ashkezar' => 'اشکذر', 'taft' => 'تفت',
                    'shahediyeh' => 'شاهدیه', 'herat_yazd' => 'هرات', 'zarach' => 'زارچ', 'marvast' => 'مروست',
                    'bahabad' => 'بهاباد', 'mehrdasht' => 'مهردشت'
                ]
            ]
        ];

    /**
     * Get list of all cities in a flat array (slug => label)
     * Optional: Filter by Province Slug
     */
    public static function getCities($provinceSlug = null)
    {
        if ($provinceSlug) {
            return isset(self::$divisions[$provinceSlug]) ? self::$divisions[$provinceSlug]['cities'] : [];
        }

        $allCities = [];
        foreach (self::$divisions as $province) {
            $allCities = array_merge($allCities, $province['cities']);
        }
        return $allCities;
    }


    /**
     * دریافت کل آرایه داده‌ها
     * @return array
     */
    public static function getAll(): array
    {
        return self::$data;
    }

    /**
     * دریافت لیست استان‌ها به صورت [slug => label]
     * مناسب برای پر کردن دراپ‌داون لیست استان
     * @return array
     */
    public static function getProvincesList(): array
    {
        $list = [];
        foreach (self::$data as $slug => $info) {
            $list[$slug] = $info['label'];
        }
        return $list;
    }

    /**
     * دریافت لیست شهرهای یک استان خاص
     * @param string $provinceSlug اسلاگ استان (مثلا: isfahan)
     * @return array|null آرایه [slug => label] شهرها یا null اگر استان یافت نشد
     */
    public static function getCitiesByProvince(string $provinceSlug): ?array
    {
        if (isset(self::$data[$provinceSlug])) {
            return self::$data[$provinceSlug]['cities'];
        }
        return null;
    }

    /**
     * دریافت نام فارسی یک استان یا شهر بر اساس اسلاگ
     *
     * @param string $slug اسلاگ مورد نظر
     * @param string $type نوع جستجو: 'province' یا 'city' یا 'auto' (پیش‌فرض auto)
     * @return string|null نام فارسی یا null
     */
    public static function getLabel(string $slug, string $type = 'auto'): ?string
    {
        // جستجو در استان‌ها
        if ($type === 'province' || $type === 'auto') {
            if (isset(self::$data[$slug])) {
                return self::$data[$slug]['label'];
            }
        }

        // جستجو در شهرها (فقط اگر نوع city یا auto باشد)
        if ($type === 'city' || $type === 'auto') {
            foreach (self::$data as $province) {
                if (isset($province['cities'][$slug])) {
                    return $province['cities'][$slug];
                }
            }
        }

        return null;
    }

    /**
     * جستجوی پیشرفته موقعیت مکانی
     * این تابع هوشمند است: هم با نام فارسی شهر و هم با اسلاگ انگلیسی شهر کار می‌کند.
     *
     * @param string $input نام شهر (فارسی) یا اسلاگ شهر (انگلیسی)
     * @return array|null آرایه حاوی اطلاعات کامل یا null
     */
    public static function findLocationInfo(string $input): ?array
    {
        $input = trim($input); // حذف فضاهای خالی

        foreach (self::$data as $pSlug => $pInfo) {
            $cities = $pInfo['cities'];

            // 1. بررسی آیا ورودی یک Slug انگلیسی شهر است؟
            if (array_key_exists($input, $cities)) {
                return [
                    'province_slug'  => $pSlug,
                    'province_label' => $pInfo['label'],
                    'city_slug'      => $input,
                    'city_label'     => $cities[$input],
                    'found_by'       => 'slug'
                ];
            }

            // 2. بررسی آیا ورودی نام فارسی شهر است؟
            // از array_search برای پیدا کردن کلید (Slug) بر اساس مقدار (Label) استفاده می‌کنیم
            $foundSlug = array_search($input, $cities);
            if ($foundSlug !== false) {
                return [
                    'province_slug'  => $pSlug,
                    'province_label' => $pInfo['label'],
                    'city_slug'      => $foundSlug,
                    'city_label'     => $input,
                    'found_by'       => 'label'
                ];
            }
        }

        return null;
    }
}
