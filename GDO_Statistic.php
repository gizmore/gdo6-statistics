<?php
namespace GDO\Statistics;

use GDO\Core\GDO;
use GDO\Core\Method;
use GDO\Date\GDT_Date;
use GDO\Date\Time;
use GDO\DB\GDT_String;
use GDO\DB\GDT_UInt;

/**
 * Statistics about called module methods each day.
 * 
 * @author gizmore
 * @version 6.10
 * @since 6.04
 */
final class GDO_Statistic extends GDO
{
	public function gdoEngine() { return GDO::MYISAM; }
	
	public function gdoCached() { return false; }
	
	public function gdoColumns()
	{
		return array(
			GDT_Date::make('ph_day')->primary(),
		    GDT_String::make('ph_module')->ascii()->caseS()->max(64)->primary(),
		    GDT_String::make('ph_method')->ascii()->caseS()->max(64)->primary(),
			GDT_UInt::make('ph_hits')->notNull()->initial('1'),
		);
	}
	
	public static function pagehit(Method $method)
	{
		$day = Time::getDateWithoutTime();
// 		$url = $_SERVER['REQUEST_URI'];

		if ($row = self::table()->getById($day, mo(), me()))
		{
			$row->increase('ph_hits');
		}
		else
		{
			self::table()->blank(array(
				'ph_day' => $day,
				'ph_module' => mo(),
			    'ph_method' => me(),
			))->insert();
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
	        $hits = self::table()->select('SUM(ph_hits)')->first()->exec()->fetchValue();
	    }
		return $hits;
	}
	
	public static function todayHits()
	{
		$day = Time::getDateWithoutTime();
		return self::table()->select('SUM(ph_hits)')->where("ph_day='{$day}'")->first()->exec()->fetchValue();
	}
	
}
