<?php
abstract class Issues_Application_Module_BootstrapAbstract
    extends Zend_Application_Module_Bootstrap
{
    protected function _initDiContainer()
    {
        $module = $this->_formatModuleName($this->getModuleName());
        $class  = $module . '_DiContainer';
        $r    = new ReflectionClass($this);
        $dir  = $r->getFileName();
        if (!class_exists($class, false)) {
            $file = dirname($dir) . '/DiContainer.php';

            if (file_exists($file)) {
                require_once $file;
            } else {
                continue;
            }
        }
        Zend_Registry::set($class, new $class());
    }

    protected function _formatModuleName($name)
    {
        $name = strtolower($name);
        $name = str_replace(array('-', '.'), ' ', $name);
        $name = ucwords($name);
        $name = str_replace(' ', '', $name);
        return $name;
    }
}
