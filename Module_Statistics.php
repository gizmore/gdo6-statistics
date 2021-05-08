<?php
namespace GDO\Statistics;

use GDO\Core\GDO_Module;
use GDO\Core\GDT_Response;
use GDO\Core\Method;
use GDO\DB\GDT_Checkbox;
use GDO\UI\GDT_Tooltip;
use GDO\UI\GDT_Page;

/**
 * Gather statistics about usage of modules and methods.
 * 
 * @author gizmore
 * @version 6.10.3
 * @since 6.8.0
 */
final class Module_Statistics extends GDO_Module
{
	public function getClasses()
	{
	    return [
	        GDO_Statistic::class,
	    ];
	}
	
	public function onLoadLanguage() { return $this->loadLanguage('lang/statistics'); }
	
	public function getConfig()
	{
		return [
			GDT_Checkbox::make('statistics_bottombar')->initial('1'),
		];
	}
	public function cfgBottomBar() { return $this->getConfigValue('statistics_bottombar'); }
	
	public function hookAfterRequest(Method $method)
	{
		if (!$method->isAjax())
		{
			GDO_Statistic::pagehit($method);
		}
	}
	
	public function onInitSidebar()
	{
		if ($this->cfgBottomBar())
		{
		    $bar = GDT_Page::$INSTANCE->bottomNav;
			$total = GDO_Statistic::totalHits();
			$today = GDO_Statistic::todayHits();
			$bar->addField(
			    GDT_Tooltip::make()->icon('trophy')->
			        tooltip('statistics_hitcounter', [$total, $today]));
		}
	}
	
}
