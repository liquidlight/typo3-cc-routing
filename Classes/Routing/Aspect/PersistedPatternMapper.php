<?php

declare(strict_types=1);

namespace CoelnConcept\CcRouting\Routing\Aspect;

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

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Restriction\DeletedRestriction;
use TYPO3\CMS\Core\Database\Query\Restriction\EndTimeRestriction;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Site\SiteLanguageAwareTrait;

/**
 * Very useful for building an a path segment from a combined value of the database.
 * Please note: title is not prepared for slugs and used raw.
 *
 * Example:
 *   routeEnhancers:
 *     CcExamplePlugin:
 *       type: Extbase
 *       extension: CcExample
 *       plugin: Fe
 *       routes:
 *         - { routePath: '{title}',_controller: 'View::show',_arguments: {'title': 'item'} }
 *       defaultController: 'View::show'
 *       aspects:
 *         title:
 *           type: CcPersistedPatternMapper
 *           tableName: 'tx_ccexample_item'
 *           routeFieldResult: '{title}-{location}'
 *           routeFieldHandles: 'asciiTranslit,toLowerCase,specialCharsRemove,trim,filter'
 *           filter: '/^(.{0,40})/'
 *   #        specialCharsRemoveSearch: '/[^a-zA-Z0-9_]+/'
 *   #        specialCharsRemoveReplace: '-'
 *
 * @internal might change its options in the future, be aware that there might be modifications.
 */
class PersistedPatternMapper extends \TYPO3\CMS\Core\Routing\Aspect\PersistedPatternMapper
{
	use SiteLanguageAwareTrait;

	protected const PATHSEGMENT_TABLENAME = 'tx_ccrouting_pathsegment';

	/**
	 * @var int
	 */
	public $time = 0;

	/**
	 * {@inheritdoc}
	 */
	public function __construct(array $settings)
	{
		$this->time = time();

		$tableName = $settings['tableName'] ?? null;
		$routeFieldResult = $settings['routeFieldResult'] ?? null;
		$routeFieldResultNames = [];

		if (!is_string($tableName)) {
			throw new \InvalidArgumentException('tableName must be string', 1537277173);
		}
		if (!is_string($routeFieldResult)) {
			throw new \InvalidArgumentException('routeFieldResult must be string', 1537277175);
		}
		if (!preg_match_all(static::PATTERN_RESULT, $routeFieldResult, $routeFieldResultNames)) {
			throw new \InvalidArgumentException('routeFieldResult must contain substitutable field names', 1537962752);
		}

		$this->settings = $settings;
		$this->tableName = $tableName;
		$this->routeFieldResult = $routeFieldResult;
		$this->routeFieldResultNames = $routeFieldResultNames['fieldName'] ?? [];
		$this->languageFieldName = $GLOBALS['TCA'][$this->tableName]['ctrl']['languageField'] ?? null;
		$this->languageParentFieldName = $GLOBALS['TCA'][$this->tableName]['ctrl']['transOrigPointerField'] ?? null;
		if (version_compare(\TYPO3\CMS\Core\Utility\VersionNumberUtility::getNumericTypo3Version(), '10.4.0', '>=')) {
			$this->slugUniqueInSite = $this->hasSlugUniqueInSite($this->tableName, ...$this->routeFieldResultNames);
		}

		$this->settings['routeFieldHandles'] = GeneralUtility::trimExplode(',', $this->settings['routeFieldHandles'], true);
		$this->settings['specialCharsRemoveSearch'] = $this->settings['specialCharsRemoveSearch'] ?? '/[^a-zA-Z0-9_]+/';
		$this->settings['specialCharsRemoveReplace'] = $this->settings['specialCharsRemoveReplace'] ?? '-';
		$this->settings['filter'] = $this->settings['filter'] ?? '/(.*)/';

		$extConf = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Configuration\ExtensionConfiguration::class)->get('cc_routing') ?: [];

		$this->settings = array_merge($extConf, $this->settings);
	}

	/**
	 * {@inheritdoc}
	 * @param string $value
	 * @param ?int $uid
	 */
	public function resolve(string $value, ?int $uid=null): ?string
	{
		$values = [
			'pathsegment' => $value,
			'tablename' => $this->tableName,
		];
		if (version_compare(\TYPO3\CMS\Core\Utility\VersionNumberUtility::getNumericTypo3Version(), '10.4.0', '>=')) {
			$result = $this->findByRouteFieldValues($values);
		} else {
			$result = $this->getPersistenceDelegate()->resolve($values);
		}
		if (isset($result['data_uid'])) {
			if ($uid == 0 || $uid == $result['data_uid']) {
				$this->refresh($result);
			}
			return (string)$result['data_uid'];
		}
		return null;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function createRouteResult(?array $result): ?string
	{
		if ($result === null) {
			return $result;
		}
		$substitutes = [];
		foreach ($this->routeFieldResultNames as $fieldName) {
			if (!isset($result[$fieldName])) {
				return null;
			}
			$routeFieldName = '{' . $fieldName . '}';
			$substitutes[$routeFieldName] = $this->handleRouteValues((string)$result[$fieldName]);
		}
		$pathsegment = str_replace(
			array_keys($substitutes),
			array_values($substitutes),
			$this->routeFieldResult
		);

		$uid = intval($result['uid']);
		if (!$uid) {
			return null;
		}

		$pathsegment_base = $pathsegment;
		$i = 0;
		do {
			$pathsegment = $pathsegment_base . ($i ? '-' . $i : '');
			$data_uid = intval($this->resolve($pathsegment, $uid));

			if (!$data_uid) {
				$this->insert($uid, $pathsegment);
				$data_uid = $uid;
			}

			$i++;
		} while ($data_uid != $uid);

		return $pathsegment;
	}

	/**
	 * @param string|null $value
	 * @return string|null
	 * @throws \InvalidArgumentException
	 */
	protected function handleRouteValues($value): ?string
	{

		// Liquid Light hack
		$value = (string)$value;

		foreach ($this->settings['routeFieldHandles'] as $handle) {
			switch ($handle) {
				case 'trim':
					$value = trim($value);
					if ($this->settings['specialCharsRemoveReplace']) {
						$value = trim($value, $this->settings['specialCharsRemoveReplace']);
					}
				break;
				case 'toLowerCase':
					$value = strtolower($value);
				break;
				case 'asciiTranslit':
					$value = iconv('UTF-8', 'ASCII//TRANSLIT', $value);
				break;
				case 'specialCharsRemove':
					$value = preg_replace($this->settings['specialCharsRemoveSearch'], $this->settings['specialCharsRemoveReplace'], $value);
				break;
				case 'filter':
					$matches = [];
					preg_match($this->settings['filter'], $value, $matches);
					$value = strval($matches[0]);
				break;
			}
		}

		return $value;
	}

	/**
	 * @param int $uid
	 * @param string $pathsegment
	 * @return int
	 */
	protected function insert(int $uid, string $pathsegment): int
	{
		$connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable(static::PATHSEGMENT_TABLENAME);
		$connection->insert(
			static::PATHSEGMENT_TABLENAME,
			[
				'tstamp' => $this->time,
				'crdate' => $this->time,
				'endtime' => $this->settings['expire'] ? strtotime('+' . $this->settings['expire'] . ' days', $this->time) : 0,
				'data_uid' => $uid,
				'pathsegment' => $pathsegment,
				'tablename' => $this->tableName,
			]
		);
		return intval($connection->lastInsertId(static::PATHSEGMENT_TABLENAME));
	}

	/**
	 * @param ?array $result
	 * @return
	 */
	protected function refresh(?array $result)
	{
		if (!$result) {
			return;
		}

		if ((!$this->settings['expire'] && $result['endtime']) || ($this->settings['expire'] && $result['endtime'] < strtotime('+' . max(intval($this->settings['expire'])-intval($this->settings['refresh']), 0) . ' days', $this->time))) {
			$connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable(static::PATHSEGMENT_TABLENAME);
			$connection->update(
				static::PATHSEGMENT_TABLENAME,
				[
					'tstamp' => $this->time,
					'endtime' => $this->settings['expire'] ? strtotime('+' . $this->settings['expire'] . ' days', $this->time) : 0,
				],
				['uid'=>$result['uid']]
			);
		}
	}

	/**
	 * {@inheritdoc}
	 */
	protected function findByIdentifier(string $value): ?array
	{
		$queryBuilder = $this->createQueryBuilder();
		$queryBuilder->getRestrictions()->removeAll()->add(GeneralUtility::makeInstance(DeletedRestriction::class));
		$result = $queryBuilder
			->select('*')
			->where($queryBuilder->expr()->eq(
				'uid',
				$queryBuilder->createNamedParameter($value, \PDO::PARAM_INT)
			))
			->execute()
			->fetch()
		;
		return $result !== false ? $result : null;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function findByRouteFieldValues(array $values): ?array
	{
		$queryBuilder = $this->createQueryBuilder()->resetQueryPart('from')->from(static::PATHSEGMENT_TABLENAME);
		$queryBuilder->getRestrictions()->removeByType(EndTimeRestriction::class);

		$constraints = [];
		foreach ($values as $fieldName => $fieldValue) {
			$constraints[] = $queryBuilder->expr()->eq(
				$fieldName,
				$queryBuilder->createNamedParameter($fieldValue, \PDO::PARAM_STR)
			);
		}

		$result = $queryBuilder
			->select('*')
			->where(...$constraints)
			->addOrderBy('endtime', '=0 DESC')
			->addOrderBy('endtime', 'DESC')
			->setMaxResults(1)
			->execute()
			->fetch()
		;
		// return first result record
		return $result ?: null;
	}

	/**
	 * @return \TYPO3\CMS\Core\Routing\Aspect\PersistenceDelegate
	 * @deprecated since v1.2, will be removed in v2.0
	 */
	protected function getPersistenceDelegate(): \TYPO3\CMS\Core\Routing\Aspect\PersistenceDelegate
	{
		if ($this->persistenceDelegate !== null) {
			return $this->persistenceDelegate;
		}
		$queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
			->getQueryBuilderForTable(static::PATHSEGMENT_TABLENAME)
			->from($this->tableName)
		;

		$resolveModifier = function (QueryBuilder $queryBuilder, array $values) {
			$queryBuilder->getRestrictions()->removeByType(EndTimeRestriction::class);

			return $queryBuilder->resetQueryPart('from')->from(static::PATHSEGMENT_TABLENAME)->select('*')->where(
				...$this->createFieldConstraints($queryBuilder, $values, true)
			)->addOrderBy('endtime', '=0 DESC')->addOrderBy('endtime', 'DESC')->setMaxResults(1);
		};
		$generateModifier = function (QueryBuilder $queryBuilder, array $values) {
			$queryBuilder->getRestrictions()->removeAll()->add(GeneralUtility::makeInstance(DeletedRestriction::class));
			return $queryBuilder->resetQueryPart('from')->from($this->tableName)->select('*')->where(
				...$this->createFieldConstraints($queryBuilder, $values)
			);
		};

		return $this->persistenceDelegate = new \TYPO3\CMS\Core\Routing\Aspect\PersistenceDelegate(
			$queryBuilder,
			$resolveModifier,
			$generateModifier
		);
	}
}
