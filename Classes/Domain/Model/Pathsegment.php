<?php

namespace CoelnConcept\CcRouting\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/***
 *
 * This file is part of the "CC Routing" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2020
 *
 ***/
/**
 * Pathsegment
 */
class Pathsegment extends AbstractEntity
{
	/**
	 * uid of dataset
	 *
	 * @var int
	 * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty")
	 */
	protected $dataUid = 0;

	/**
	 * pathsegment
	 *
	 * @var string
	 * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty")
	 */
	protected $pathsegment = '';

	/**
	 * tablename
	 *
	 * @var string
	 * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty")
	 */
	protected $tablename = '';

	/**
	 * Returns the dataUid
	 *
	 * @return int $dataUid
	 */
	public function getDataUid()
	{
		return $this->dataUid;
	}

	/**
	 * Sets the dataUid
	 *
	 * @param int $dataUid
	 * @return void
	 */
	public function setDataUid($dataUid)
	{
		$this->dataUid = $dataUid;
	}

	/**
	 * Returns the pathsegment
	 *
	 * @return string $pathsegment
	 */
	public function getPathsegment()
	{
		return $this->pathsegment;
	}

	/**
	 * Sets the pathsegment
	 *
	 * @param string $pathsegment
	 * @return void
	 */
	public function setPathsegment($pathsegment)
	{
		$this->pathsegment = $pathsegment;
	}

	/**
	 * Returns the tablename
	 *
	 * @return string $tablename
	 */
	public function getTablename()
	{
		return $this->tablename;
	}

	/**
	 * Sets the tablename
	 *
	 * @param string $tablename
	 * @return void
	 */
	public function setTablename($tablename)
	{
		$this->tablename = $tablename;
	}
}
