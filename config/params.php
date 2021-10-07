<?php

/*return [
    'adminEmail' => 'admin@example.com',
    'senderEmail' => 'noreply@example.com',
    'senderName' => 'Example.com mailer',
];*/

return [
    'adminEmail' => 'admin@artlist.pro',
    'supportEmail' => 'admin@artlist.pro',
    'locationApiKey'=>'20b96dca8b9a5d37b0355e9461c66e76eed30a2274422fa6213d9de6ffb2b34e',
    'linkImage'=>'http://artlist/images/photos/1/87/',
    'competition_ava'=>'http://artlist/images/competitions/logotips/',
    'competition'=>'http://artlist/images/competitions/',
    'avatar'=>'http://artlist/',
    //'genre_root'=>'/uploads/genre/',
    'genre_root'=>'/web/artlist/img/uploads/genre/',
    //'recaptcha2key'=>'6Lfrpe4UAAAAAJySpnk9I6DiOIHLLHI7fAOKtfjm',
    //'recaptcha2secret'=>'6Lfrpe4UAAAAAGbgb_oht7QLJrAyjmD0PCSH1N55',
    'recaptcha2key'=>'6LcNT3AUAAAAAOahmGPWxOGmh9NCj_fsUabCZ328',
    'recaptcha2secret'=>'6LcNT3AUAAAAAEIOwGylO6ZmP6HzQAPtOnvcSpMU',


//    'keyGoogleCaptcha'=>'6LeZvYAUAAAAAB0U7rgKwBLteoLmTOz2pjIeDnlO',
//    'secretGoogleCaptcha'=>'6LeZvYAUAAAAAPxrc4n0Ww4fToLKOoF6MnTRIEX_',
    'defCityId'=>8,
    'all'=>[
        'pro'=>[
            'a'=>10,
            'f'=>50,
            'v'=>5,
            'p'=>1
        ],
        'simple'=>[
            'a'=>1,
            'f'=>20,
            'v'=>1,
            'p'=>0
        ],
    ],
    'model'=>[
        'simple'=>[
            'a'=>0,
            'f'=>50,
            'v'=>5,
            'p'=>0
        ],

    ],
    'video'=>[
        'pro'=>[
            'a'=>0,
            'f'=>0,
            'v'=>20,
            'p'=>1
        ],
        'simple'=>[
            'a'=>0,
            'f'=>0,
            'v'=>3,
            'p'=>0
        ],
    ],
    'genres' => [
        1,  // Портретная съемка
        2,  // Студийная фотосъемка
        3,  // Свадебная фотосъемка
        4,  // Детская фотосъемка
        // 5,  // Фотокнига
        //  6,  // Фотосъемка ню
        7,  // Lovestory
        8,  // Выездная фотосъемка
        9,  // Фотосъемка для портфолио
        10, // Предметная фотосъемка
        11, // Семейная фотосъемка
        12, // Фотосъемка беременных
        13, // Корпоративная фотосъемка
        //   14, // Фоторетушь
        //   15, // Фотоколлаж
        //  16, // Фотосъемка животных
        //  17, // Интерьерная съемка
        //  18  // Репортажная фотосъемка
    ],
    'minPhotosForActive' => 5, // Минимальное кол-во фотографий в альбоме для того чтобы он считался активным
];

