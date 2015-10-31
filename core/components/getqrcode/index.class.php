<?php

/**
 * Class getQRcodeMainController
 */
abstract class getQRcodeMainController extends modExtraManagerController {
	/** @var getQRcode $getQRcode */
	public $getQRcode;


	/**
	 * @return void
	 */
	public function initialize() {
		$corePath = $this->modx->getOption('getqrcode_core_path', null, $this->modx->getOption('core_path') . 'components/getqrcode/');
		require_once $corePath . 'model/getqrcode/getqrcode.class.php';

		$this->getQRcode = new getQRcode($this->modx);

		parent::initialize();
	}


	/**
	 * @return array
	 */
	public function getLanguageTopics() {
		return array('getqrcode:default');
	}


	/**
	 * @return bool
	 */
	public function checkPermissions() {
		return true;
	}
}


/**
 * Class IndexManagerController
 */
class IndexManagerController extends getQRcodeMainController {

	/**
	 * @return string
	 */
	public static function getDefaultController() {
		return 'home';
	}
}