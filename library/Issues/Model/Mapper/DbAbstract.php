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
     * @param string $tableAlias the name/alias of the main table in the query
     * @return Zend_Db_Select modified query
     */
    protected function _addAclJoins(Zend_Db_Select $sql, $tableAlias = null)
    {
        $roles = Zend_Registry::get('Default_DiContainer')
            ->getUserService()
            ->getIdentity()
            ->getRoles();

        if ($tableAlias === null) {
            $table = $this->getTableName();
        } else {
            $table = $tableAlias;
        }

        $sql->joinLeft(
                'acl_resource_record',
                "acl_resource_record.resource_type = '$table' "
                    . "AND acl_resource_record.resource_id = $table.{$table}_id",
                array())
            ->where('(private = ?', 1)
            ->where('role_id IN (?))', $roles)
            ->orWhere('private = ?', 0);

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

}

