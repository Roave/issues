<?php

class Issues_Profiler
{

    /**
     * Timers for code profiling
     *
     * @var array
     */
    static private $_timers = array();
    static private $_enabled = false;
    static private $_memory_get_usage = false;

    public static function enable()
    {
        self::$_enabled = true;
        self::$_memory_get_usage = function_exists('memory_get_usage');
    }

    public static function disable()
    {
        self::$_enabled = false;
    }

    public static function reset($timerName)
    {
        self::$_timers[$timerName] = array(
        	'start'=>false,
        	'count'=>0,
        	'sum'=>0,
        	'realmem'=>0,
        	'emalloc'=>0,
        );
    }

    public static function resume($timerName)
    {
        if (!self::$_enabled) {
            return;
        }

        if (empty(self::$_timers[$timerName])) {
            self::reset($timerName);
        }
        if (self::$_memory_get_usage) {
        	self::$_timers[$timerName]['realmem_start'] = memory_get_usage(true);
        	self::$_timers[$timerName]['emalloc_start'] = memory_get_usage();
        }
        self::$_timers[$timerName]['start'] = microtime(true);
        self::$_timers[$timerName]['count'] ++;
    }

    public static function start($timerName)
    {
        self::resume($timerName);
    }

    public static function pause($timerName)
    {
        if (!self::$_enabled) {
            return;
        }

        if (empty(self::$_timers[$timerName])) {
            self::reset($timerName);
        }
        if (false!==self::$_timers[$timerName]['start']) {
            self::$_timers[$timerName]['sum'] += microtime(true)-self::$_timers[$timerName]['start'];
            self::$_timers[$timerName]['start'] = false;
            if (self::$_memory_get_usage) {
	            self::$_timers[$timerName]['realmem'] += memory_get_usage(true)-self::$_timers[$timerName]['realmem_start'];
    	        self::$_timers[$timerName]['emalloc'] += memory_get_usage()-self::$_timers[$timerName]['emalloc_start'];
            }
        }
    }

    public static function stop($timerName)
    {
        self::pause($timerName);
    }

    public static function fetch($timerName, $key='sum')
    {
        if (empty(self::$_timers[$timerName])) {
            return false;
        } elseif (empty($key)) {
            return self::$_timers[$timerName];
        }
        switch ($key) {
            case 'sum':
                $sum = self::$_timers[$timerName]['sum'];
                if (self::$_timers[$timerName]['start']!==false) {
                    $sum += microtime(true)-self::$_timers[$timerName]['start'];
                }
                return $sum;

            case 'count':
                $count = self::$_timers[$timerName]['count'];
                return $count;

            case 'realmem':
            	if (!isset(self::$_timers[$timerName]['realmem'])) {
            		self::$_timers[$timerName]['realmem'] = -1;
            	}
            	return self::$_timers[$timerName]['realmem'];

            case 'emalloc':
            	if (!isset(self::$_timers[$timerName]['emalloc'])) {
            		self::$_timers[$timerName]['emalloc'] = -1;
            	}
            	return self::$_timers[$timerName]['emalloc'];

            default:
                if (!empty(self::$_timers[$timerName][$key])) {
                    return self::$_timers[$timerName][$key];
                }
        }
        return false;
    }

    public static function getTimers()
    {
        return self::$_timers;
    }

    /**
     * Output SQl Zend_Db_Profiler
     *
     */
    public static function getSqlProfiler($res) {
        if(!$res){
            return '';
        }
        $out = '';
        $profiler = $res->getProfiler();
        if($profiler->getEnabled()) {
            $totalTime    = $profiler->getTotalElapsedSecs();
            $queryCount   = $profiler->getTotalNumQueries();
            $longestTime  = 0;
            $longestQuery = null;

            foreach ($profiler->getQueryProfiles() as $query) {
                if ($query->getElapsedSecs() > $longestTime) {
                    $longestTime  = $query->getElapsedSecs();
                    $longestQuery = $query->getQuery();
                }
            }

            $out .= 'Executed ' . $queryCount . ' queries in ' . $totalTime . ' seconds' . "<br>";
            $out .= 'Average query length: ' . $totalTime / $queryCount . ' seconds' . "<br>";
            $out .= 'Queries per second: ' . $queryCount / $totalTime . "<br>";
            $out .= 'Longest query length: ' . $longestTime . "<br>";
            $out .= 'Longest query: <br>' . $longestQuery . "<hr>";
        }
        return $out;
    }
    
    public static function _toHTML(){
        $timers = self::getTimers();

        $out = '<div id="profiler-section" style="z-index:10000;bottom:5px;left:5px;opacity:1;background:white;width:auto;padding:5px;border:2px solid #CCC;" onmouseover="this.style.opacity=1" onmouseout="this.style.opacity=1">';
        $out .= '<pre>Memory usage:<br/> real: '.memory_get_usage(true).', emalloc: '.memory_get_usage().'</pre>';
        $out .= '<table border="1" cellspacing="0" cellpadding="2" width="100%">';
        $out .= '<tr><th>Code Profiler</th><th>Time</th><th>Cnt</th><th>RealMem</th><th>Emalloc</th></tr>';
        foreach ($timers as $name=>$timer) {
            $sum = self::fetch($name,'sum');
            $count = self::fetch($name,'count');
            $realmem = self::fetch($name,'realmem');
            $emalloc = self::fetch($name,'emalloc');
            if ($sum<.0010 && $count<10 && $realmem==0) {
                continue;
            }
            $out .= '<tr><td align="left">'.$name.'</td><td>'
                .number_format($sum,4).'</td><td>'
                .$count.'</td><td>'
                .number_format($realmem).'</td><td>'
                .number_format($emalloc).'</td></tr>'
                .'</td></tr>';
        }
        $out .= '</table>';
        
        $profiler = Issues_Model_Mapper_DbAbstract::getDefaultAdapter()->getProfiler();
        $totalTime    = $profiler->getTotalElapsedSecs();
        $queryCount   = $profiler->getTotalNumQueries();
        $longestTime  = 0;
        $longestQuery = null;
        if($profiler->getTotalNumQueries() > 0){
            foreach ($profiler->getQueryProfiles() as $query) {
                if ($query->getElapsedSecs() > $longestTime) {
                    $longestTime  = $query->getElapsedSecs();
                    $longestQuery = $query->getQuery();
                }
            }

            $out .= '<table border="1" cellspacing="0" cellpadding="2" width="100%">';
            $out .= '<tr><td width="40%"><b>Queries executed:</td><td>'. $queryCount .'</td></tr>';
            $out .= '<tr><td><b>All queries length</td><td>'. $totalTime .'</td></tr>';
            $out .= '<tr><td><b>Average query length:</td><td>'. $totalTime / $queryCount .'</td></tr>';
            $out .= '<tr><td><b>Queries per second:</td><td>'. $queryCount / $totalTime .'</td></tr>';
            $out .= '<tr><td valign="top"><b>Longest query:</td><td>'. $longestQuery .'</td></tr>';
            $out .= '<tr><td><b>Longest query time:</td><td>'. $longestTime .'</td></tr>';
            $out .= '</table>';

            $out .= '<table border="1" cellspacing="0" cellpadding="2" width="100%">';
            $out .= '<tr><th>Query</th><th>Elapsed Time</th></tr>';
            foreach ($profiler->getQueryProfiles() as $query) {
                $out .= '<tr><td>';
                $out .= $query->getQuery();
                $out .= '</td><td>';
                $out .= $query->getElapsedSecs();
                $out .= '</td></tr>';
            }
            $out .= '</table>';
        } else {
            $out .= "No database hits... everything loaded from cache.";
        }
        
        $out .= '</div>';
        
        return $out;
    }
}
