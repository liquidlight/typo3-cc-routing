<?php

$EM_CONF[$_EXTKEY] = [
	'title' => 'CC Routing',
	'description' => 'Extended routing with database persistence',
	'category' => 'fe',
	'author' => 'Liquid Light',
	'author_email' => 'info@liquidlight.co.uk',
	'author_company' => 'Liquid Light Ltd',
	'state' => 'stable',
	'uploadfolder' => 0,
	'createDirs' => '',
	'clearCacheOnLoad' => 0,
	'version' => '3.0.0',
	'constraints' => [
		'depends' => [
			'typo3' => '12.4.0-13.4.99',
		],
		'conflicts' => [],
		'suggests' => [],
	],
];
