<?php
/**
 * RssReadersController Test Case
 *
 * @author Kosuke Miura <k_miura@zenk.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 */

App::uses('RssReadersController', 'RssReaders.Controller');

/**
 * Summary for RssReadersController Test Case
 */
class RssReadersControllerTest extends ControllerTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.rss_readers.rss_reader',
		'plugin.rss_readers.block',
		'plugin.rss_readers.frame',
		'plugin.rss_readers.site_setting',
		'plugin.rss_readers.box',
		'plugin.rss_readers.plugin',
		'plugin.rss_readers.language'
	);

/**
 * test index
 *
 * @author Kosuke Miura <k_miura@zenk.co.jp>
 * @return void
 */
	public function testIndex() {
		$frameId = 1;
		$this->testAction('/rss_readers/rss_readers/index/' . $frameId . '/', array('method' => 'get'));
		$this->assertTextNotContains('ERROR', $this->view);
	}

/**
 * test index case rss_reader status approving
 *
 * @author Kosuke Miura <k_miura@zenk.co.jp>
 * @return void
 */
	public function testIndexApprovingData() {
		// statusが申請中状態の表示
		$frameId = 2;
		$this->testAction('/rss_readers/rss_readers/index/' . $frameId . '/', array('method' => 'get'));
		$this->assertTextNotContains('ERROR', $this->view);
	}

/**
 * test index case rss_reader status drafting
 *
 * @author Kosuke Miura <k_miura@zenk.co.jp>
 * @return void
 */
	public function testIndexDraftingData() {
		// statusが下書き状態の表示
		$frameId = 3;
		$this->testAction('/rss_readers/rss_readers/index/' . $frameId . '/', array('method' => 'get'));
		$this->assertTextNotContains('ERROR', $this->view);
	}

/**
 * test index case rss_reader status disapproving
 *
 * @author Kosuke Miura <k_miura@zenk.co.jp>
 * @return void
 */
	public function testIndexDisapprovingData() {
		// statusが下書き状態の表示
		$frameId = 4;
		$this->testAction('/rss_readers/rss_readers/index/' . $frameId . '/', array('method' => 'get'));
		$this->assertTextNotContains('ERROR', $this->view);
	}

/**
 * test index case not exit rss_reader
 *
 * @author Kosuke Miura <k_miura@zenk.co.jp>
 * @return void
 */
	public function testIndexNotExistData() {
		// RssReaderが存在しない場合の表示
		$frameId = 5;
		$this->testAction('/rss_readers/rss_readers/index/' . $frameId . '/', array('method' => 'get'));
		$this->assertTextNotContains('ERROR', $this->view);
	}

}