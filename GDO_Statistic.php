<?php
namespace GDO\Statistics;

use GDO\Core\GDO;
use GDO\Date\GDT_Date;
use GDO\DB\GDT_UInt;
use GDO\Core\Method;
use GDO\Net\GDT_Url;
use GDO\Date\Time;

final class GDO_Statistic extends GDO
{
	public function gdoEngine() { return GDO::MYISAM; }
	
	public function gdoCached() { return false; }
	
	public function gdoColumns()
	{
		return array(
			GDT_Date::make('ph_day')->primary(),
			GDT_Url::make('ph_url')->primary()->max(220),
			GDT_UInt::make('ph_hits')->initial('1'),
		);
	}
	
	public static function pagehit(Method $method)
	{
		$day = Time::getDateWithoutTime();
		$url = $_SERVER['REQUEST_URI'];

		if ($row = self::table()->getById($day, $url))
		{
			$row->increase('ph_hits');
		}
		else
		{
			self::table()->blank(array(
				'ph_day' => $day,
				'ph_url' => $url,
			))->insert();
		}
	}
	
	public static function totalHits()
	{
		return self::table()->select('SUM(ph_hits)')->first()->exec()->fetchValue();
	}
	
	public static function todayHits()
	{
		$day = Time::getDateWithoutTime();
		return self::table()->select('SUM(ph_hits)')->where("ph_day='{$day}'")->first()->exec()->fetchValue();
	}
	
}
