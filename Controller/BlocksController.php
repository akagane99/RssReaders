<?php
/**
 * Blocks Controller
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Kotaro Hokada <kotaro.hokada@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('RssReadersAppController', 'RssReaders.Controller');

/**
 * Blocks Controller
 *
 * @author Kotaro Hokada <kotaro.hokada@gmail.com>
 * @package NetCommons\RssReaders\Controller
 */
class BlocksController extends RssReadersAppController {

/**
 * layout
 *
 * @var array
 */
	public $layout = 'NetCommons.setting';

/**
 * use models
 *
 * @var array
 */
	public $uses = array(
		'Blocks.Block',
	);

/**
 * use components
 *
 * @var array
 */
	public $components = array(
		'NetCommons.NetCommonsBlock',
		'NetCommons.NetCommonsRoomRole' => array(
			//コンテンツの権限設定
			'allowedActions' => array(
				'blockEditable' => array('index', 'add', 'edit', 'delete')
			),
		),
		'Paginator',
	);

/**
 * use helpers
 *
 * @var array
 */
	public $helpers = array(
		'NetCommons.Date',
	);

/**
 * beforeFilter
 *
 * @return void
 */
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->deny('index');

		$results = $this->camelizeKeyRecursive($this->NetCommonsFrame->data);
		$this->set($results);

		//タブの設定
		$this->initTabs('block_index', 'block_settings');
	}

/**
 * index
 *
 * @return void
 * @throws Exception
 */
	public function index() {
		$this->Paginator->settings = array(
			'RssReader' => array(
				'order' => array('RssReader.id' => 'desc'),
				'conditions' => array(
					'Block.language_id' => $this->viewVars['languageId'],
					'Block.room_id' => $this->viewVars['roomId'],
					'Block.plugin_key ' => $this->params['plugin'],
					'RssReader.is_latest' => true,
				),
			)
		);

		try {
			$rssReaders = $this->Paginator->paginate('RssReader');
		} catch (Exception $ex) {
			if (isset($this->request['paging']) && $this->params['named']) {
				$this->redirect('/rss_readers/blocks/index/' . $this->viewVars['frameId']);
				return;
			}
			CakeLog::error($ex);
			throw $ex;
		}

		if (! $rssReaders) {
			$this->view = 'Blocks/not_found';
			return;
		}

		$results = array(
			'rss_readers' => $rssReaders
		);
		$results = $this->camelizeKeyRecursive($results);
		$this->set($results);
	}

/**
 * add
 *
 * @return void
 */
	public function add() {
		$this->view = 'Blocks/edit';

		$this->set('blockId', null);
		$rssReader = $this->RssReader->create(
			array(
				'id' => null,
				'key' => null,
				'block_id' => null,
				//'name' => __d('rss_readers', 'New rss %s ', date('YmdHis')),
			)
		);
		$block = $this->Block->create(
			array('id' => null, 'key' => null)
		);

		$data = array();
		if ($this->request->isPost()) {
			$data = $this->__parseRequestData();
			$data['RssReader']['status'] = NetCommonsBlockComponent::STATUS_PUBLISHED;

			$rssReader = $this->RssReader->saveRssReader($data);

			if ($this->handleValidationError($this->RssReader->validationErrors)) {
				if (! $this->request->is('ajax')) {
					$this->redirect('/rss_readers/blocks/index/' . $this->viewVars['frameId']);
				}
				return;
			}

			$data['Block']['id'] = null;
			$data['Block']['key'] = null;
			unset($data['Frame']);
		}

		$data = Hash::merge($rssReader, $block, $data);
		$results = $this->camelizeKeyRecursive($data);
		$this->set($results);
	}

/**
 * edit
 *
 * @return void
 */
	public function edit() {
		if (! $this->NetCommonsBlock->validateBlockId()) {
			$this->throwBadRequest();
			return false;
		}
		$this->set('blockId', (int)$this->params['pass'][1]);

		if (! $this->initRssReader()) {
			return;
		}

		if ($this->request->isPost()) {
			$data = $this->__parseRequestData();

			$this->RssReader->saveRssReader($data);
			if ($this->handleValidationError($this->RssReader->validationErrors)) {
				if (! $this->request->is('ajax')) {
					$this->redirect('/rss_readers/blocks/index/' . $this->viewVars['frameId']);
				}
				return;
			}

			$results = $this->camelizeKeyRecursive($data);
			$this->set($results);
		}
	}

/**
 * delete
 *
 * @throws BadRequestException
 * @return void
 */
	public function delete() {
		if (! $this->NetCommonsBlock->validateBlockId()) {
			$this->throwBadRequest();
			return false;
		}
		$this->set('blockId', (int)$this->params['pass'][1]);

		if (! $this->initRssReader()) {
			return;
		}

		if ($this->request->isDelete()) {
			if ($this->RssReader->deleteRssReader($this->data)) {
				if (! $this->request->is('ajax')) {
					$this->redirect('/rss_readers/blocks/index/' . $this->viewVars['frameId']);
				}
				return;
			}
		}

		$this->throwBadRequest();
	}

/**
 * Parse data from request
 *
 * @return array
 */
	private function __parseRequestData() {
		$data = $this->data;
		if ($data['Block']['public_type'] === Block::TYPE_LIMITED) {
			//$data['Block']['from'] = implode('-', $data['Block']['from']);
			//$data['Block']['to'] = implode('-', $data['Block']['to']);
		} else {
			unset($data['Block']['from'], $data['Block']['to']);
		}

		return $data;
	}

}