<?php

$properties = array();

$tmp = array(
	'id' => array(
		'type' => 'textfield',
		'value' => '',
	),
	'args' => array(
		'type' => 'textfield',
		'value' => '',
	),
	'text' => array(
		'type' => 'textfield',
		'value' => '',
	),
	'tpl' => array(
		'type' => 'textfield',
		'value' => '@INLINE [[+url]]',
	),
);

foreach ($tmp as $k => $v) {
	$properties[] = array_merge(
		array(
			'name' => $k,
			'desc' => PKG_NAME_LOWER . '_prop_' . $k,
			'lexicon' => PKG_NAME_LOWER . ':properties',
		), $v
	);
}

return $properties;
