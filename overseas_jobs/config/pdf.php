<?php

return [
	'mode'                  => 'utf-8',
	'format'                => 'A4',
	'author'                => '',
	'subject'               => '',
	'keywords'              => '',
	'creator'               => 'Laravel Pdf',
	'display_mode'          => 'fullpage',
	// 'tempDir'               => '../temp/',
	// 'font_path' => '../resources/fonts/',
	'tempDir'               => base_path('../temp/'),
	'font_path' => base_path('resources/fonts/'),
	'font_data' => [
		'mspgothic' => [
			'R' => 'MS PGothic.ttf',
			//'useOTL' => 0xFF,    // required for complicated langs like Persian, Arabic and Chinese
			// 'useKashida' => 75,  // required for complicated langs like Persian, Arabic and Chinese
		],
		'msmincho' => [
			'R' => 'MSMincho-Regular.ttf',
			//'useOTL' => 0xFF,    // required for complicated langs like Persian, Arabic and Chinese
			// 'useKashida' => 75,  // required for complicated langs like Persian, Arabic and Chinese
		],
		'notosanscjktc' => [
			'R' => 'NotoSansCJKtc-Regular.ttf',    // regular font
		],
		'notosanscjkjp' => [
			'R' => 'NotoSansCJKjp-Regular.otf',    // regular font
		],
		'notosans' => [
			'R' => 'NotoSans-Regular.ttf',
		],
	],
];
