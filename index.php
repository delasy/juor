<?php

$url = $_SERVER['REQUEST_URI'];
$url = explode('?', $url);
$url = $url[0];

if ($url === '/') {
    $url = '/index.php';
}

define('URL', substr($url, 1));
define('_ROOT', $_SERVER['DOCUMENT_ROOT'] . '/');
define('_STATIC', _ROOT . 'static/');
define('_VIEW', _ROOT . 'view/');

$file = _STATIC . URL;
$template = _VIEW . URL . '.php';
$error = _VIEW . 'error.php';

if (file_exists($file)) {
    header('Content-Type: ' . mime_content_type($file));
    header('Content-Length: ' . filesize($file));
    readfile($file);
} else if (file_exists($template)) {
    include_once($template);
} else {
    include_once($error);
}
