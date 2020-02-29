<?php
namespace GDO\Statistics;

use GDO\Core\GDO_Module;
use GDO\Core\Method;
use GDO\DB\GDT_Checkbox;
use GDO\UI\GDT_Bar;
use GDO\UI\GDT_Tooltip;

final class Module_Statistics extends GDO_Module
{
	public function getClasses()
	{
		return array(
			"GDO\\Statistics\\GDO_Statistic",
		);
	}
	
	public function onLoadLanguage() { return $this->loadLanguage('lang/statistics'); }
	
	public function getConfig()
	{
		return array(
			GDT_Checkbox::make('statistics_bottombar')->initial('1'),
		);
	}
	
	public function cfgBottomBar() { return $this->getConfigValue('statistics_bottombar'); }
	
	public function hookBeforeExecute(Method $method)
	{
		if (!$method->isAjax())
		{
			GDO_Statistic::pagehit($method);
		}
	}
	
	public function hookBottomBar(GDT_Bar $bar)
	{
		if ($this->cfgBottomBar())
		{
			$total = GDO_Statistic::totalHits();
			$today = GDO_Statistic::todayHits();
			$bar->addField(GDT_Tooltip::make()->icon('trophy')->tooltip('statistics_hitcounter', [$total, $today]));
		}
	}
	
}
