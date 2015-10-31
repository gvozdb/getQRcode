<?php
/* Сниппет вывода QR кода для страницы */

$id			= $modx->getOption('id', $scriptProperties, '' ); // id ресурса (чтобы генерировать не ссылку, надо передать в этот параметр "ne" или "no")
$args		= $modx->getOption('args', $scriptProperties, '' ); // get параметры в виде "hello=world&hello2=world2"
$text		= $modx->getOption('text', $scriptProperties, '' ); // текст для QR кода (сработает, если id ресурса = "ne" или "no")
$params_str	= $modx->getOption('params', $scriptProperties, '' ); // параметры для обработки QR кода в виде"ecc=H&pixels=4&frame=2&type=png"
$tpl		= $modx->getOption('tpl', $scriptProperties, '@INLINE [[+url]]' ); // шаблон для вывода

if( empty($id) )
{
	$id = is_object($modx->resource) ? $modx->resource->id : 0;
}

if( $id == 'no' || $id == 'ne' )
{
	$id = 0;
}

if( !$id && empty($text) ) {return;}


if( (int)$id > 0 )
{
	$text = $modx->makeUrl( $id, '', $args, 'full' );
}


parse_str( $params_str, $params_new );

$params = array_merge( array(
		'ecc'		=> 'L',
		'pixels'	=> '4',
		'frame'		=> '2',
		'type'		=> 'png',
	),
	$params_new
);


$response = $modx->runProcessor('item/generate', array(
		'text'			=> $text,
		'resource_id'	=> $id,
		'params'		=> $params,
	),
	array('processors_path' => MODX_CORE_PATH.'components/getqrcode/processors/mgr/')
);


if( empty($response->response) || !$response->response['success'] ) {return;}
else {
	/*return $modx->getChunk( $tpl, array(
			'url'	=> $response->response['object']['url'],
		));*/
	return $response->response['object']['url'];
}