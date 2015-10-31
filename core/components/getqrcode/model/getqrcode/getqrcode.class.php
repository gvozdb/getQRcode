<?php

/**
 * The base class for getQRcode.
 */
class getQRcode {
	/* @var modX $modx */
	public $modx;


	/**
	 * @param modX $modx
	 * @param array $config
	 */
	function __construct(modX &$modx, array $config = array())
	{
		$this->modx =& $modx;

		$corePath = $this->modx->getOption('getqrcode_core_path', $config, $this->modx->getOption('core_path') . 'components/getqrcode/');
		$assetsUrl = $this->modx->getOption('getqrcode_assets_url', $config, $this->modx->getOption('assets_url') . 'components/getqrcode/');
		$connectorUrl = $assetsUrl . 'connector.php';

		$this->config = array_merge(array(
			'basePath' => $this->modx->getOption('base_path'),
			'baseUrl' => $this->modx->getOption('base_url'),
			'assetsUrl' => $assetsUrl,
			'cssUrl' => $assetsUrl . 'css/',
			'jsUrl' => $assetsUrl . 'js/',
			'imagesUrl' => $assetsUrl . 'images/',
			'getqrcodesUrl' => $this->modx->getOption('assets_url') . 'getqrcodes/',
			'getqrcodesPath' => $this->modx->getOption('assets_path') . 'getqrcodes/',
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

		$this->modx->addPackage('getqrcode', $this->config['modelPath']);
	}

}