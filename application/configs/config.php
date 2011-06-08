<?php
$config['appnamespace'] = 'Issues';
$config['autoloadernamespaces'][] = 'Issues';
$config['resources']['layout']['layoutPath']  = APPLICATION_PATH . '/layouts';
$config['resources']['layout']['layout'] = 'layout';
$config['resources']['modules'] = array();
$config['bootstrap']['path']    = APPLICATION_PATH . '/Bootstrap.php';
$config['bootstrap']['class']   = 'Bootstrap';

$config['resources']['frontController']['moduleDirectory'] = APPLICATION_PATH . '/modules';
$config['resources']['frontController']['defaultModule'] = 'default';
$config['resources']['frontController']['throwExceptions'] = true;
$config['resources']['frontController']['prefixDefaultModule'] = true;

$file = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'config.development.php';
if (file_exists($file)) {
    include_once $file;
}

return $config;
