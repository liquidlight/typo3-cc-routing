<?php

return [
	'ctrl' => [
		'title' => 'LLL:EXT:cc_routing/Resources/Private/Language/locallang_db.xlf:tx_ccrouting_pathsegment',
		'label' => 'data_uid',
		'label_alt' => 'tablename,pathsegment',
		'label_alt_force' => true,
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'delete' => 'deleted',
		'enablecolumns' => [
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		],
		'searchFields' => 'pathsegment,tablename',
		'iconfile' => 'EXT:cc_routing/Resources/Public/Icons/tx_ccrouting_pathsegment.png',
		'security' => [
			'ignorePageTypeRestriction' => true,
		],
	],
	'types' => [
		'1' => ['showitem' => 'data_uid, pathsegment, tablename, --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access, starttime, endtime'],
	],
	'columns' => [
		'starttime' => [
			'exclude' => true,
			'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.starttime',
			'config' => [
				'type' => 'datetime',
				'default' => 0,
				'behaviour' => [
					'allowLanguageSynchronization' => true,
				],
			],
		],
		'endtime' => [
			'exclude' => true,
			'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.endtime',
			'config' => [
				'type' => 'datetime',
				'default' => 0,
				'behaviour' => [
					'allowLanguageSynchronization' => true,
				],
			],
		],

		'data_uid' => [
			'exclude' => false,
			'label' => 'LLL:EXT:cc_routing/Resources/Private/Language/locallang_db.xlf:tx_ccrouting_pathsegment.data_uid',
			'config' => [
				'type' => 'number',
				'size' => 10,
				'required' => true,
			],
		],
		'pathsegment' => [
			'exclude' => false,
			'label' => 'LLL:EXT:cc_routing/Resources/Private/Language/locallang_db.xlf:tx_ccrouting_pathsegment.pathsegment',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim',
				'required' => true,
			],
		],
		'tablename' => [
			'exclude' => false,
			'label' => 'LLL:EXT:cc_routing/Resources/Private/Language/locallang_db.xlf:tx_ccrouting_pathsegment.tablename',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim',
				'required' => true,
			],
		],

	],
];
