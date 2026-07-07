<?php

// Platform-wide configuration values for BizHub.

return [
    'platform_contacts' => [
        'legal_name' => env('BIZHUB_LEGAL_NAME', 'Индивидуальный предприниматель / ООО «BizHub»'),
        'unp' => env('BIZHUB_UNP', '000000000'),
        'address' => env('BIZHUB_ADDRESS', '220000, Беларусь, г. Минск'),
        'email' => env('BIZHUB_EMAIL', 'info@bizhub.by'),
        'phone' => env('BIZHUB_PHONE', ''),
        'support_hours' => env('BIZHUB_SUPPORT_HOURS', 'Пн–Пт, 9:00 – 18:00'),
        'legal_support_terms' => env('BIZHUB_LEGAL_SUPPORT_TERMS', 'Юридическое сопровождение оказывается партнёрами платформы на основании отдельного договора.'),
    ],

    'prohibited_patterns' => [
        'займ', 'микрозайм', 'кредит', 'деньги в долг', 'ломбард',
        'криптовалют', 'форекс', 'бинарные опционы',
        'букмекер', 'ставки на спорт', 'лотере', 'азартные игры',
        'финансовая пирамида', 'пирамида', 'mlm',
    ],

    'representative_default_note' => 'Объявление размещено от имени владельца на основании публичной информации.',
];
