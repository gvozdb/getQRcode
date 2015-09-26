<?php

/**
 * The home manager controller for QRcode.
 *
 */
class QRcodeHomeManagerController extends QRcodeMainController {
	/* @var QRcode $QRcode */
	public $QRcode;


	/**
	 * @param array $scriptProperties
	 */
	public function process(array $scriptProperties = array()) {
	}


	/**
	 * @return null|string
	 */
	public function getPageTitle() {
		return $this->modx->lexicon('qrcode');
	}


	/**
	 * @return void
	 */
	public function loadCustomCssJs() {
		
	}


	/**
	 * @return string
	 */
	public function getTemplateFile() {
		
	}
}