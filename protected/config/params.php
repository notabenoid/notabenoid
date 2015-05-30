<?php

return [
    'domain' => 'notabenoid.org',
    'hashCost' => 13,
    'adminEmail' => 'support@notabenoid.org',
    'commentEmail' => 'comment@notabenoid.org',
    'systemEmail' => 'no-reply@notabenoid.org',
    /*
     * Тип регистрации: OPEN - открытая, INVITE - по инвайтам
     */
    'registerType' => 'OPEN',

    'HTMLPurifierOptions' => [
        'HTML.Allowed' => 'a[href],b,strong,i,em,u,s,blockquote,table,tr,th,td,ul,ol,li,dl,dt,dd,br,img[src],small,sub,sup,font[color],span,abbr,*[title],code,tt',
    ],
    'sex' => ['m' => 'мужчина', 'f' => 'женщина', 'x' => 'существо'],
    'countries' => [
        '',
        1 => 'Россия', 2 => 'Украина', 3 => 'Белоруссия', 4 => 'Абхазия', 5 => 'Австралия', 6 => 'Австрия', 7 => 'Азербайджан', 8 => 'Албания', 9 => 'Алжир',
        10 => 'Ангола', 11 => 'Андорра', 12 => 'Антигуа и Барбуда', 13 => 'Аргентина', 14 => 'Армения', 15 => 'Афганистан', 16 => 'Багамы', 17 => 'Бангладеш', 18 => 'Барбадос', 19 => 'Бахрейн',
        20 => 'Белиз', 21 => 'Бельгия', 22 => 'Бенин', 23 => 'Болгария', 24 => 'Боливия', 25 => 'Босния и Герцеговина', 26 => 'Ботсвана', 27 => 'Бразилия', 28 => 'Бруней', 29 => 'Буркина-Фасо',
        30 => 'Бурунди', 31 => 'Бутан', 32 => 'Вазиристан', 33 => 'Вануату', 34 => 'Ватикан', 35 => 'Великобритания', 36 => 'Венгрия', 37 => 'Венесуэла', 38 => 'Восточный Тимор (Тимор-Лешти)', 39 => 'Вьетнам',
        40 => 'Габон', 41 => 'Гаити', 42 => 'Гайана', 43 => 'Гамбия', 44 => 'Гана', 45 => 'Гватемала', 46 => 'Гвинея', 47 => 'Гвинея-Бисау', 48 => 'Германия', 49 => 'Гондурас',
        50 => 'Гренада', 51 => 'Греция', 52 => 'Грузия', 53 => 'Дания', 54 => 'Джибути', 55 => 'Доминика', 56 => 'Доминиканская Республика', 57 => 'Египет', 58 => 'Замбия', 59 => 'Зимбабве',
        60 => 'Израиль', 61 => 'Индия', 62 => 'Индонезия', 63 => 'Иордания', 64 => 'Ирак', 65 => 'Иран', 66 => 'Ирландия', 67 => 'Исландия', 68 => 'Испания', 69 => 'Италия',
        70 => 'Йемен', 71 => 'Кабо-Верде', 72 => 'Казахстан', 73 => 'Камбоджа', 74 => 'Камерун', 75 => 'Канада', 76 => 'Катар', 77 => 'Кения', 78 => 'Кипр', 79 => 'Киргизия',
        80 => 'Кирибати', 81 => 'Китай', 82 => 'Коморские острова', 83 => 'Республика Конго', 84 => 'Конго, Демократическая Республика (Заир)', 85 => 'Колумбия', 86 => 'Корея (Северная)', 87 => 'Корея (Южная)', 88 => 'Косово', 89 => 'Коста-Рика',
        90 => 'Кот-д\'Ивуар', 91 => 'Куба', 92 => 'Кувейт', 93 => 'Лаос', 94 => 'Латвия', 95 => 'Лесото', 96 => 'Либерия', 97 => 'Ливан', 98 => 'Ливия', 99 => 'Литва',
        100 => 'Лихтенштейн', 101 => 'Люксембург', 102 => 'Маврикий', 103 => 'Мавритания', 104 => 'Мадагаскар', 105 => 'Македония', 106 => 'Малави', 107 => 'Малайзия', 108 => 'Мали', 109 => 'Мальдивы',
        110 => 'Мальта', 111 => 'Марокко', 112 => 'Маршалловы Острова', 113 => 'Мексика', 114 => 'Мозамбик', 115 => 'Молдавия', 116 => 'Монако', 117 => 'Монголия', 118 => 'Мьянма', 129 => 'Нагорно-Карабахская Республика',
        120 => 'Намибия', 121 => 'Науру', 122 => 'Непал', 123 => 'Нигер', 124 => 'Нигерия', 125 => 'Нидерланды', 126 => 'Никарагуа', 127 => 'Новая Зеландия', 128 => 'Норвегия', 139 => 'Объединённые Арабские Эмираты',
        130 => 'Оман', 131 => 'Пакистан', 132 => 'Палау', 133 => 'Панама', 134 => 'Папуа', 135 => 'Парагвай', 136 => 'Перу', 137 => 'Польша', 138 => 'Португалия', 149 => 'Приднестровская Молдавская Республика',
        140 => 'Пунтленд', 141 => 'Руанда', 142 => 'Румыния', 143 => 'Сальвадор', 144 => 'Самоа', 145 => 'Сан-Марино', 146 => 'Сан-Томе и Принсипи', 147 => 'Саудовская Аравия', 148 => 'Свазиленд', 159 => 'Сейшельские острова',
        150 => 'Сенегал', 151 => 'Сент-Винсент и Гренадины', 152 => 'Сент-Киттс и Невис', 153 => 'Сент-Люсия', 154 => 'Сербия', 155 => 'Силенд', 156 => 'Сингапур', 157 => 'Сирия', 158 => 'Словакия', 169 => 'Словения',
        160 => 'Соединённые Штаты Америки', 161 => 'Соломоновы Острова', 162 => 'Сомали', 163 => 'Сомалиленд', 164 => 'Судан', 165 => 'Суринам', 166 => 'Сьерра-Леоне', 167 => 'Таджикистан', 168 => 'Таиланд', 179 => 'Тайвань',
        170 => 'Тамил-Илам', 171 => 'Танзания', 172 => 'Того', 173 => 'Тонга', 174 => 'Тринидад и Тобаго', 175 => 'Тувалу', 176 => 'Тунис', 177 => 'Туркменистан', 178 => 'Турция', 189 => 'Турецкая Республика Северного Кипра',
        180 => 'Уганда', 181 => 'Узбекистан', 182 => 'Уругвай', 183 => 'Федеративные Штаты Микронезии', 184 => 'Фиджи', 185 => 'Филиппины', 186 => 'Финляндия', 187 => 'Франция', 188 => 'Хорватия', 199 => 'Центрально-Африканская Республика',
        190 => 'Чад', 191 => 'Черногория', 192 => 'Чехия', 193 => 'Чили', 194 => 'Швейцария', 195 => 'Швеция', 196 => 'Шри-Ланка', 197 => 'Эквадор', 198 => 'Экваториальная Гвинея', 199 => 'Эритрея',
        200 => 'Эстония', 201 => 'Эфиопия', 202 => 'Южно-Африканская Республика', 203 => 'Южная Осетия', 204 => 'Ямайка', 205 => 'Япония',
    ],
    'month_acc' => ['', 'января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря'],
    'month_in' => ['', 'январе', 'феврале', 'марте', 'апреле', 'мае', 'июне', 'июле', 'августе', 'сентябре', 'октябре', 'ноябре', 'декабре'],
    'encodings' => [
        // iconv-название => человеческое название
        'UTF-8' => 'UTF-8',
        'CP1251' => 'Windows-1251 (Кириллица Windows)',
        'CP1252' => 'Windows-1252 (Западноевропейская)',
        'KOI8-R' => 'KOI8-R (русская KOI8)',
        'KOI8-U' => 'KOI8-U (украинская KOI8)',
        'utf-16' => 'Unicode UTF-16 (16-битный юникод)',
        'MacCyrillic' => 'MacCyrillic (Кириллица Macintosh)',
        'MacCentralEurope' => 'MacCentralEurope (Центральная Европа Macintosh)',
    ],
    'book_types' => ['A' => 'текст', 'S' => 'субтитры'],
    'catalog_branches' => [1 => 'S', 2 => 'A', 3 => 'A'],
    'book_topics' => [
        'S' => [
            0 => 'Сериал',
            1 => 'Мультфильм',
            2 => 'Документальный фильм',
            3 => 'Фантастика',
            4 => 'Комедия',
            5 => 'Драма',
            6 => 'Боевик, приключения',
            7 => 'Ужасы, триллер',
            8 => 'Детектив',
            9 => 'Мелодрама',
            10 => 'Мюзикл',
        ],
        'A' => [
            0 => 'Классика',
            1 => 'Художественная литература',
            10 => 'Научная фантастика',
            2 => 'Техническая литература',
            7 => 'Для детей',
            3 => 'Поэзия',
            4 => 'Публицистика',
            5 => 'Научные статьи',
            6 => 'Коллективное творчество',
            8 => 'Комиксы',
            9 => 'Игры',
            11 => 'Стихи и песни',
        ],
    ],
    'ac_areas' => [
        'ac_read' => 'войти', 'ac_trread' => 'видеть все версии', 'ac_gen' => 'скачивать', 'ac_rate' => 'оценивать', 'ac_comment' => 'комментировать', 'ac_tr' => 'переводить',
        'ac_blog_r' => 'читать блог', 'ac_blog_c' => 'комментировать в блоге', 'ac_blog_w' => 'писать посты в блоге',
        'ac_announce' => 'создавать анонсы перевода', 'ac_membership' => 'управлять членством в группе перевода',
        'ac_chap_edit' => 'редактировать оригинал', 'ac_book_edit' => 'редактировать описание перевода',
    ],
    'ac_areas_chap' => ['ac_read' => 'читать', 'ac_trread' => 'видеть все версии', 'ac_gen' => 'скачивать', 'ac_rate' => 'оценивать', 'ac_comment' => 'комментировать', 'ac_tr' => 'переводить'],
    'ac_roles' => ['a' => 'все', 'g' => 'группа', 'm' => 'модераторы', 'o' => 'никто'],
    'ac_roles_title' => ['a' => 'все', 'g' => 'только члены группы перевода', 'm' => 'только модераторы', 'o' => 'только владелец'],

    'translation_statuses' => [
        0 => '',
        1 => 'идёт перевод',
        2 => 'перевод редактируется',
        3 => 'перевод готов',
    ],
    'translation_statuses_short' => [
        0 => '',
        1 => 'переводится',
        2 => 'редактируется',
        3 => 'готово',
    ],

    'blog_topics' => [
        'book' => [// 1 - 19
            1 => 'Обсуждение оригинала',
            2 => 'Перевод',
            3 => 'Общение',
        ],
        'common' => [// 40 - 79
            64 => 'Новости проекта',
            65 => 'Техподдержка',
            66 => 'Общение',
            67 => 'Юмор',
            69 => 'Как это перевести?',
        ],
        'announce' => [// 80 - 89
            81 => 'Ищем переводчиков',
            82 => 'Готово',
            89 => 'Всякое',
        ],
    ],

    'ENVIRONMENT' => 'production',
    'version' => '3.3',
];
