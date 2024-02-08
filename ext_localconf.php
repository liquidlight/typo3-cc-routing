<?php

defined('TYPO3') || die('Access denied.');

$GLOBALS['TYPO3_CONF_VARS']['SYS']['routing']['aspects']['CcPersistedPatternMapper'] =
	\CoelnConcept\CcRouting\Routing\Aspect\PersistedPatternMapper::class;
