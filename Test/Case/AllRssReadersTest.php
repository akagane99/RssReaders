<?php
/**
 * RssReaders All Test Case
 *
 * @author Kosuke Miura <k_miura@zenk.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

/**
 * RssReaders All Test Case
 *
 * @author Kosuke Miura <k_miura@zenk.co.jp>
 * @package NetCommons\RssReaders\Test
 * @codeCoverageIgnore
 */
class AllRssReadersTest extends CakeTestSuite {

/**
 * All test suite
 *
 * @author Kosuke Miura <k_miura@zenk.co.jp>
 * @return CakeTestSuite
 */
	public static function suite() {
		$plugin = preg_replace('/^All([\w]+)Test$/', '$1', __CLASS__);

		$suite = new CakeTestSuite(sprintf('All %s Plugin tests', $plugin));
		$suite->addTestDirectoryRecursive(CakePlugin::path($plugin) . 'Test' . DS . 'Case' . DS . 'Model');
		$suite->addTestDirectoryRecursive(CakePlugin::path($plugin) . 'Test' . DS . 'Case' . DS . 'Controller');
		$suite->addTestDirectoryRecursive(CakePlugin::path($plugin) . 'Test' . DS . 'Case' . DS . 'View');

		return $suite;
	}
}