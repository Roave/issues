<?php
abstract class Issues_Model_Mapper_DbAbstract
{

    /**
     * Database adapter for read queries
     *
     * @var Zend_Db_Adapter_Abstract
     */
    protected $_readAdapter;

    /**
     * Database adapter for write queries
     *
     * @var Zend_Db_Adapter_Abstract
     */
    protected $_writeAdapter;

    /**
     * The roles of the current user
     * 
     * @var array
     */
    protected $_roles = array();

    /**
     * Storage
     */
    protected $_storage = array();

    /**
     * Default database adapter
     *
     * @var Zend_Db_Adapter_Abstract
     */
    protected static $_defaultAdapter;

    /**
     * Table name
     *
     * @var string
     */
    protected $_name;


    /**
     * Constructor
     *
     * This method is final, because it should not be used to initialize, use
     * {@link _init()} instead
     *
     * @param Zend_Db_Adapter_Abstract $writeAdapter
     * @param Zend_Db_Adapter_Abstract $readAdapter
     *
     * @throws Issues_Model_Mapper_RuntimeException If there is no adapter defined
     *
     * @return void
     */
    final public function __construct(Zend_Db_Adapter_Abstract $writeAdapter = null, Zend_Db_Adapter_Abstract $readAdapter = null)
    {
        if (null === $writeAdapter) {
            if (null === ($writeAdapter = self::getDefaultAdapter())) {
                throw new Issues_Model_Mapper_Exception('There was no adapter defined');
            }
        }

        if (null === $readAdapter) {
            $readAdapter = $writeAdapter;
        }

        $this->_readAdapter = $readAdapter;
        $this->_writeAdapter = $writeAdapter;

        $this->_init();
    }

    /**
     * Init hook
     *
     * @return void
     */
    protected function _init()
    {}

    /**
     * Get the database adapter for read queries
     *
     * @return Zend_Db_Adapter_Abstract
     */
    public function getReadAdapter()
    {
        return $this->_readAdapter;
    }

    /**
     * Get the database adapter for write queries
     *
     * @return Zend_Db_Adapter_Abstract
     */
    public function getWriteAdapter()
    {
        return $this->_writeAdapter;
    }

    /**
     * Get the table name
     *
     * @return string
     */
    public function getTableName()
    {
        return $this->_name;
    }

    /**
     * _addAclJoins
     * 
     * @param Zend_Db_Select $sql 
     * @param string $alias the name/alias of the main table in the query
     * @return Zend_Db_Select modified query
     */
    protected function _addAclJoins(Zend_Db_Select $sql, $alias = null, $primaryKey = null)
    {
        $roles = Zend_Registry::get('Default_DiContainer')
            ->getUserService()
            ->getIdentity()
            ->getRoles();

        if ($alias === null) {
            $alias = $this->getTableName();
        }

        $table = $this->getTableName();

        if ($primaryKey === null) {
            $primaryKey = $table . '_id';
        }

        $sql->joinLeft('acl_resource_record', "`acl_resource_record`.`resource_type` = '{$table}' AND `acl_resource_record`.`resource_id` = {$alias}.{$primaryKey}", array())
            ->where("(($alias.private = ?", 1)                 // note the extra parentheses here
            ->where("acl_resource_record.role_id IN (?))", $roles)  // they're important. don't touch
            ->orWhere("$alias.private = ?)", 0);

        return $sql;
    }

    // static functions

    /**
     * Set the default database adapter
     *
     * @param Zend_Db_Adapter_Abstract $adapter
     *
     * @return void
     */
    public static function setDefaultAdapter(Zend_Db_Adapter_Abstract $adapter)
    {
        self::$_defaultAdapter = $adapter;
    }

    /**
     * Get the default database adapter
     *
     * @return Zend_Db_Adapter_Abstract
     */
    public static function getDefaultAdapter()
    {
        return self::$_defaultAdapter;
    }


    /**
     * Convert DB rows to model classes 
     * 
     * @param array $rows 
     * @param string $class 
     * @return array
     */
    protected function _rowsToModels($rows, $class = false)
    {
        if ($class == false) $class = $this->_modelClass;
        if (!$rows) return array();
        foreach ($rows as $i => $row) {
            $rows[$i] = $this->_rowToModel($row, $class);
        }
        return $rows;
    }

    /**
     * Convert DB row to model classes 
     * 
     * @param array $row 
     * @param string $class 
     * @return mixed
     */
    protected function _rowToModel($row, $class = false)
    {
        if ($class == false) $class = $this->_modelClass;
        $model = ($row) ? new $class($row) : false;
        if ($model) $this->_addModelToCache($model);
        return $model;
    }

    protected function _setCachedModel($model, $keys)
    {
        if (is_array($keys)) {
            foreach ($keys as $key) {
                $this->_storage[$key] = $model;
            }
        } else {
            $this->_storage[$keys] = $model;
        }
    }

    protected function _getCachedModel($key)
    {
        if (!isset($this->_storage[$key])) {
            return false;
        }  
        return $this->_storage[$key];
    }

    /**
     * _addModelToCache 
     *
     * Override this method to enable model caching
     * 
     * @param mixed $model 
     * @return void
     */
    protected function _addModelToCache($model)
    {
        return false;
    }
}

