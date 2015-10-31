<?php
/** @noinspection PhpIncludeInspection */
require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/config.core.php';
/** @noinspection PhpIncludeInspection */
require_once MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php';
/** @noinspection PhpIncludeInspection */
require_once MODX_CONNECTORS_PATH . 'index.php';
/** @var getQRcode $getQRcode */
$getQRcode = $modx->getService('getqrcode', 'getQRcode', $modx->getOption('getqrcode_core_path', null, $modx->getOption('core_path') . 'components/getqrcode/') . 'model/getqrcode/');
$modx->lexicon->load('getqrcode:default');

// handle request
$corePath = $modx->getOption('getqrcode_core_path', null, $modx->getOption('core_path') . 'components/getqrcode/');
$path = $modx->getOption('processorsPath', $getQRcode->config, $corePath . 'processors/');
$modx->request->handleRequest(array(
	'processors_path' => $path,
	'location' => '',
));