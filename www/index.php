<?php

if (isset($_REQUEST['__url'])) {
	$page = explode('/',$_REQUEST['__url']);
} else {
	$page = [];
}

header('Location: http://beta.crunchr.co/i/'.$page[0].'/'.$page[1]);
exit;

$cache = './cache/';
$pubcache = '/cache/';
$source = './image/';

$image = array_pop($page);

if (file_exists($cache.$image)) {
	header('HTTP/1.1 301 Moved Permanently');
	header('Location: '.$pubcache.$image);
	exit;
}

$im = explode('.',$image);
$params['format'] = $im[1];
$imgName = $im[0];

$exts = ['jpg','jpeg','png','gif'];

foreach ($exts as $ext) {
	$im = $source.$imgName;

	if (file_exists($im.'.'.$ext)) {
		$file = $imgName.'.'.$ext;
		break;
	}
}

if (!$file) {
	die('404');
}


if ($page[0]) {
	$page[0] = explode('x',$page[0]);
	$params['height'] = $page[0][1];
	$params['width'] = $page[0][0];
} else {
	$params['height'] = 100;
	$params['width'] = 100;
}

$params['crop'] = $_REQUEST['crop'] == 1 ? 1 : 0;
$params['gravity'] = 'center';
if ($params['format'] != 'jpg' && $params['format'] != 'png') {
	$params['format'] = 'jpg';
}
$params['quality'] = 70;

$params['img']			= $file;
$params['cache'] 		= $cache;
$params['path'] 		= $source;


require_once '../include/Thumb.php';

$thumb = new Thumb($params);
$url = $pubcache.$thumb->getFileName();
header('HTTP/1.1 301 Moved Permanently');
header('Location: '.$url);
exit;	
