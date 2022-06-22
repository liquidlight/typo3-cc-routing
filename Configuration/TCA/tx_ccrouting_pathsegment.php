<?php

return [
	'ctrl' => [
		'title' => 'LLL:EXT:cc_routing/Resources/Private/Language/locallang_db.xlf:tx_ccrouting_pathsegment',
		'label' => 'data_uid',
		'label_alt' => 'tablename,pathsegment',
		'label_alt_force' => true,
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'delete' => 'deleted',
		'enablecolumns' => [
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		],
		'searchFields' => 'pathsegment,tablename',
		'iconfile' => 'EXT:cc_routing/Resources/Public/Icons/tx_ccrouting_pathsegment.png',
	],
	'interface' => [
		'showRecordFieldList' => 'data_uid, pathsegment, tablename',
	],
	'types' => [
		'1' => ['showitem' => 'data_uid, pathsegment, tablename, --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access, starttime, endtime'],
	],
	'columns' => [
		'starttime' => [
			'exclude' => true,
			'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.starttime',
			'config' => [
				'type' => 'input',
				'renderType' => 'inputDateTime',
				'eval' => 'datetime,int',
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
				'type' => 'input',
				'renderType' => 'inputDateTime',
				'eval' => 'datetime,int',
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
				'type' => 'input',
				'size' => 10,
				'eval' => 'int,required',
			],
		],
		'pathsegment' => [
			'exclude' => false,
			'label' => 'LLL:EXT:cc_routing/Resources/Private/Language/locallang_db.xlf:tx_ccrouting_pathsegment.pathsegment',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,required',
			],
		],
		'tablename' => [
			'exclude' => false,
			'label' => 'LLL:EXT:cc_routing/Resources/Private/Language/locallang_db.xlf:tx_ccrouting_pathsegment.tablename',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,required',
			],
		],

	],
];
