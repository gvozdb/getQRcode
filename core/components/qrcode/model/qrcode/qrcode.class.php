<?php

/**
 * The base class for QRcode.
 */
class QRcode {
	/* @var modX $modx */
	public $modx;


	/**
	 * @param modX $modx
	 * @param array $config
	 */
	function __construct(modX &$modx, array $config = array())
	{
		$this->modx =& $modx;

		$corePath = $this->modx->getOption('qrcode_core_path', $config, $this->modx->getOption('core_path') . 'components/qrcode/');
		$assetsUrl = $this->modx->getOption('qrcode_assets_url', $config, $this->modx->getOption('assets_url') . 'components/qrcode/');
		$connectorUrl = $assetsUrl . 'connector.php';

		$this->config = array_merge(array(
			'basePath' => $this->modx->getOption('base_path'),
			'baseUrl' => $this->modx->getOption('base_url'),
			'assetsUrl' => $assetsUrl,
			'cssUrl' => $assetsUrl . 'css/',
			'jsUrl' => $assetsUrl . 'js/',
			'imagesUrl' => $assetsUrl . 'images/',
			'qrcodesUrl' => $this->modx->getOption('assets_url') . 'qrcodes/',
			'qrcodesPath' => $this->modx->getOption('assets_path') . 'qrcodes/',
			'connectorUrl' => $connectorUrl,

			'corePath' => $corePath,
			'modelPath' => $corePath . 'model/',
			'chunksPath' => $corePath . 'elements/chunks/',
			'templatesPath' => $corePath . 'elements/templates/',
			'chunkSuffix' => '.chunk.tpl',
			'snippetsPath' => $corePath . 'elements/snippets/',
			'processorsPath' => $corePath . 'processors/',
			'libPath' => $corePath . 'lib/',
		), $config);

		$this->modx->addPackage('qrcode', $this->config['modelPath']);
	}

}