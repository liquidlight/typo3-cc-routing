<?php

defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
	function () {
		\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_ccrouting_pathsegment');
		\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
			'tx_ccrouting_pathsegment',
			'EXT:cc_routing/Resources/Private/Language/locallang_csh_tx_ccrouting_pathsegment.xlf'
		);
	}
);
