<?php
// Unsure if we need these
// $config['bootstrap']['path']    = LIBRARY_PATH . '/Zend/Application/Bootstrap/Bootstrap.php';
// $config['bootstrap']['class']   = 'Zend_Application_Bootstrap_Bootstrap';

$config['resources']['layout']['layoutPath']  = APPLICATION_PATH . '/layouts';
$config['resources']['layout']['layout'] = 'layout';
$config['resources']['modules'] = array();

$config['resources']['frontController']['moduleDirectory'] = APPLICATION_PATH . '/modules';
$config['resources']['frontController']['defaultModule'] = 'default';
$config['resources']['frontController']['throwExceptions'] = true;
$config['resources']['frontController']['prefixDefaultModule'] = true;

return $config;

