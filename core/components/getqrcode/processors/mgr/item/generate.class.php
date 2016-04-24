<?php

/* Процессор получения QR кода */

/* Пример:
$response = $this->modx->runProcessor('item/generate', array(
			'text'			=> 'test lala',
			// >> не обязательно
			'resource_id'	=> 0,
			'params'		=> array(
					'ecc'		=> 'H',
					'pixels'	=> '4',
					'frame'		=> '2',
					'size'		=> '150',
					'type'		=> 'png',
				),
			// << не обязательно
		),
		array('processors_path' => MODX_CORE_PATH.'components/getqrcode/processors/mgr/')
	);

if( empty($response) )
{
	return 'error';
}
else {
	print_r( $response->response );
}
*/

class getQRcodeItemGenerateProcessor extends modObjectProcessor
{
	public $objectType = 'getQRcodeItem';
	public $classKey = 'getQRcodeItem';
	//public $languageTopics = array('getqrcode');
	//public $permission = 'save';
	
	private $params = array();
	private $text = '';
	private $filepath = '';
	
	private $_qr = 0;
	
	
	public function initialize()
	{
		if( !$this->checkPermissions() )
		{
			return $this->modx->lexicon('access_denied');
		}
		
		$corePath = $this->modx->getOption('getqrcode_core_path', null, $this->modx->getOption('core_path') . 'components/getqrcode/');
		$assetsUrl = $this->modx->getOption('getqrcode_assets_url', null, $this->modx->getOption('assets_url') . 'components/getqrcode/');

		$this->config = array_merge(array(
			'basePath' => $this->modx->getOption('base_path'),
			'baseUrl' => $this->modx->getOption('base_url'),
			'assetsUrl' => $assetsUrl,
			'getqrcodesUrl' => $this->modx->getOption('assets_url') . 'getqrcodes/',
			'getqrcodesPath' => $this->modx->getOption('assets_path') . 'getqrcodes/',

			'corePath' => $corePath,
			'modelPath' => $corePath . 'model/',
			'libPath' => $corePath . 'lib/',
		));
		
		$this->modx->addPackage('getqrcode', $this->config['modelPath']);
		
		return true;
	}
	
	
	public function process()
	{
		$text			= $this->getProperty( 'text', '' );
		$resource_id	= $this->getProperty( 'resource_id', 0 );
		$params			= $this->getProperty( 'params', array(
				'ecc'		=> 'H',
				'pixels'	=> '4',
				'frame'		=> '2',
				'size'		=> '150',
				'type'		=> 'png',
			));
		
		if( empty($text) )		{ return $this->failure('Ошибка. Короткий текст.'); }
		
		if( empty($params) || !is_array($params) || !count($params) )
		{
			$params = array(
				'ecc'		=> 'H',
				'pixels'	=> '4',
				'frame'		=> '2',
				'size'		=> '150',
				'type'		=> 'png',
			);
		}
		
		if( empty($params['ecc']) )		$params['ecc']		= 'H';
		if( empty($params['pixels']) )	$params['pixels']	= '4';
		if( empty($params['frame']) )	$params['frame']	= '2';
		if( empty($params['size']) )	$params['size']		= '150';
		if( empty($params['type']) )	$params['type']		= 'png';
		
		
		$gen = true;
		$insert = true;
		
		$slashes = array('////','///','//');
		
		$hash			= md5( $text ); // хеш, нужен для названия файла
		$path			= str_replace( $slashes, '/', $this->config['getqrcodesPath'] .'/'. $params['type'] .'/' );
		$pathUrl		= str_replace( $slashes, '/', $this->config['getqrcodesUrl'] .'/'. $params['type'] .'/' );
		$file			= $hash .'.'. $params['type'];
		$filepath		= $path . $file;
		$filepathUrl	= $pathUrl . $file;
		
		if( !file_exists($path) )
		{
			mkdir( $path, 0755, true );
		}
		
		
		// >> выборка
		$q = $this->modx->newQuery( $this->classKey );
		$q->select(
			array(
				$this->classKey .'.id',
				$this->classKey .'.resource_id',
				$this->classKey .'.path',
				$this->classKey .'.file',
				$this->classKey .'.url',
				$this->classKey .'.type',
				$this->classKey .'.hash',
			)
		);
		$q->where( array(
			$this->classKey .'.text = "'. $text .'"'.
			' AND '.
			$this->classKey .'.type = "'. $params['type'] .'"'.
			' AND '.
			$this->classKey .'.hash = "'. $hash .'"'.
		''));
		$q->limit(1);
		$s = $q->prepare(); //return $q->toSQL();
		$s->execute();
		$row = $s->fetch(PDO::FETCH_ASSOC);
		unset($q, $s); //return print_r( $row, 1 );
		// << выборка
		
		
		// >> если в базе есть запись
		if( is_array($row) && count($row) )
		{
			$filepath		= str_replace( $slashes, '/', $this->config['basePath'] . $row['url'] );
			$filepathUrl	= str_replace( $slashes, '/', '/'. $row['url'] );
			
			$insert = false;
			
			if( file_exists($filepath) )
			{
				$gen = false;
			}
		}
		// << если в базе есть запись
		
		
		// >> если в базе нет записи и передан id ресурса
		else if( ( !is_array($row) || !count($row) ) && $resource_id )
		{
			$q = $this->modx->newQuery( $this->classKey );
			$q->select(
				array(
					$this->classKey .'.id',
					$this->classKey .'.path',
					$this->classKey .'.file',
					$this->classKey .'.url',
					$this->classKey .'.type',
					$this->classKey .'.hash',
				)
			);
			$q->where( array(
				$this->classKey .'.resource_id = "'. $resource_id .'"'.
			''));
			$q->limit(1);
			$s = $q->prepare(); //return $q->toSQL();
			$s->execute();
			$row = $s->fetch(PDO::FETCH_ASSOC);
			unset($q, $s); //return print_r( $row, 1 );
			
			
			// >> если нашли по id ресурса
			if( is_array($row) && count($row) )
			{
				$filepathUnlink = str_replace( $slashes, '/', $this->config['basePath'] . $row['url'] );
				
				@unlink( $filepathUnlink ); // удаляем старый файл, если есть
				
				// удаляем запись из базы
				$q = $this->modx->newQuery( $this->classKey );
				$q->command('delete');
				$q->where( array(
					$this->classKey .'.id' => $row['id'],
				));
				$q->prepare(); //return $q->toSQL();
				$q->stmt->execute();
				unset($q);
			}
			// << если нашли по id ресурса
		}
		// << если в базе нет записи и передан id ресурса

		
		// >> генерируем QR код
		if( $gen )
		{
			/* Слабенький класс, работающий через CURL с http://chart.apis.google.com/chart
			if( !is_object($this->_qr) )
			{
				require_once( $this->config['libPath'] .'qrcode/qrcode.php' );
			}
			
			$this->_qr = new qrcode();
			
			$this->_qr->text( $text );
			
			$qr_image = $this->_qr->get_image( $params['size'], $params['ecc'] );
			
			if( empty($qr_image) )
			{
				return $this->failure( 'error' );
			}
			
			$this->_qr->save_image( $qr_image, $filepath );
			*/
			
			
			if( !class_exists('QRcode', false) )
			{
				include $this->config['libPath'] .'phpqrcode/qrlib.php';
			}
			
			QRcode::{$params['type']}( $text, $filepath, $params['ecc'], $params['pixels'], $params['frame'] );
		}
		// << генерируем QR код
		
		
		// >> добавляем в базу запись
		if( $insert )
		{
			$getqrcodeObj = $this->modx->newObject( $this->classKey, array(
				'resource_id'	=> $resource_id,
				'text'			=> $text,
				'description'	=> '',
				'path'			=> $pathUrl,
				'file'			=> $file,
				'url'			=> $filepathUrl,
				'type'			=> $params['type'],
				'hash'			=> $hash,
			) );
			$getqrcodeObj->save();
		}
		// << добавляем в базу запись
		
		
		$data = array(
			'url'	=> $filepathUrl,
		);
		
		
		return $this->success( 'ok', $data );
	}
}

return 'getQRcodeItemGenerateProcessor';
