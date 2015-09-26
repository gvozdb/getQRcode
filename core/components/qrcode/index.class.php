<?php

/**
 * Class QRcodeMainController
 */
abstract class QRcodeMainController extends modExtraManagerController {
	/** @var QRcode $QRcode */
	public $QRcode;


	/**
	 * @return void
	 */
	public function initialize() {
		$corePath = $this->modx->getOption('qrcode_core_path', null, $this->modx->getOption('core_path') . 'components/qrcode/');
		require_once $corePath . 'model/qrcode/qrcode.class.php';

		$this->QRcode = new QRcode($this->modx);

		parent::initialize();
	}


	/**
	 * @return array
	 */
	public function getLanguageTopics() {
		return array('qrcode:default');
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
class IndexManagerController extends QRcodeMainController {

	/**
	 * @return string
	 */
	public static function getDefaultController() {
		return 'home';
	}
}