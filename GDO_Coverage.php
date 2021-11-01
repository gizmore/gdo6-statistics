<?php
namespace GDO\Statistics;

use GDO\Core\GDO;
use GDO\DB\GDT_String;
use GDO\DB\GDT_UInt;

/**
 * Count method invocations to identify dead or hot code.
 * @author gizmore
 */
final class GDO_Coverage extends GDO
{
	public function gdoEngine() { return GDO::MYISAM; }
	
	public function gdoCached() { return false; }
	
	public function gdoColumns()
	{
		return [
			GDT_String::make('coverage_method')->caseS()->ascii()->max(96),
		];
	}
	
	/**
	 * Count up
	 * @param string $classname
	 */
	public static function called($classname)
	{
		
	}
	
}
