<?php
$config['resources']['db']['adapter']               = 'PDO_MYSQL';
$config['resources']['db']['isdefaulttableadapter'] = true;
$config['resources']['db']['params']['host']        = 'localhost';
$config['resources']['db']['params']['dbname']      = 'issues';
$config['resources']['db']['params']['username']    = 'issues';
$config['resources']['db']['params']['password']    = 'changeme';
$config['resources']['db']['params']['charset']     = 'UTF8';
// Hint: head -c 128 /dev/urandom | base64 --wrap=0
$config['hash_salt'] = 'Zz85xsj8u2/b4FaOSCU4A7b52bK0CNkAXGCABWwEM7S1Rj8LhsrWbWpn50OcY9hI7Rro7h85bjm86TDSdIjuWqpfbX13bK+lhhWbMbHLW+9bP+dQCqwa1e3ZzuqVfRmg4KeVcTLckT7hXvR10UcBJ4wF5PYktvPAgl71ahTH+X8=';
