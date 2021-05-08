<?php
namespace GDO\Statistics;

use GDO\Core\GDO;
use GDO\Core\Method;
use GDO\Date\Time;
use GDO\DB\GDT_String;
use GDO\DB\GDT_UInt;
use GDO\Date\GDT_Date;
use GDO\DB\Cache;
use GDO\DB\GDT_Enum;

/**
 * Statistics about called module methods each day.
 * 
 * @author gizmore
 * @version 6.10.3
 * @since 6.8.0
 */
final class GDO_Statistic extends GDO
{
	public function gdoEngine() { return GDO::MYISAM; }
	
	public function gdoCached() { return false; }
	
	public function gdoColumns()
	{
	    return [
			GDT_Date::make('ph_day')->primary(),
	        GDT_Enum::make('ph_type')->primary()->enumValues('GET', 'POST'),
		    GDT_String::make('ph_module')->ascii()->caseS()->max(64)->primary(),
		    GDT_String::make('ph_method')->ascii()->caseS()->max(64)->primary(),
			GDT_UInt::make('ph_hits')->notNull()->initial('1'),
	    ];
	}
	
	public static function pagehit(Method $method)
	{
		$day = Time::getDateWithoutTime();
		$type = $_SERVER['REQUEST_METHOD'];
		$mo = $method->getModuleName();
		$me = $method->getMethodName();
		try
		{
		    if ($row = self::table()->getById($day, $type, $mo, $me))
		    {
		        return $row->increase('ph_hits');
		    }
		    else
		    {
		        $row = self::table()->blank([
		            'ph_day' => $day,
		            'ph_type' => $_SERVER['REQUEST_METHOD'],
		            'ph_module' => $mo,
		            'ph_method' => $me,
		            'ph_hits' => '1',
		        ])->insert();
		    }
		}
		catch (\Throwable $ex)
		{
		    return self::table()->blank([
		        'ph_day' => $day,
		        'ph_type' => $_SERVER['REQUEST_METHOD'],
		        'ph_module' => $mo,
		        'ph_method' => $me,
		        'ph_hits' => '1',
		    ]);
		}
	}
	
	/**
	 * Return total hits for the whole time in universe.
	 * Caches the result.
	 * @return string
	 */
	public static function totalHits()
	{
	    static $hits;
	    if ($hits === null)
	    {
	        if (false === ($hits = Cache::get('statistics_hits')))
	        {
    	        $hits = self::table()->select('SUM(ph_hits)')->
    	           exec()->fetchValue();
    	        Cache::set('statistics_hits', $hits, 60);
	        }
	    }
		return $hits;
	}
	
	public static function todayHits()
	{
	    static $hits;
	    if ($hits === null)
	    {
	        if (false === ($hits = Cache::get('statistics_hits_today')))
	        {
	            $day = Time::getDateWithoutTime();
	            $hits = self::table()->select('SUM(ph_hits)')->
	               where("ph_day='{$day}'")->exec()->fetchValue();
	            Cache::set('statistics_hits_today', $hits);
	        }
	    }
	    return $hits;
	}
	
}
