<?php
namespace GDO\Statistics;

use GDO\Core\GDO;
use GDO\DB\GDT_String;
use GDO\DB\GDT_UInt;
use GDO\DB\GDT_CreatedAt;

/**
 * Count method invocations to identify dead or hot code.
 * @author gizmore
 */
final class GDO_Coverage extends GDO
{
	public function gdoColumns()
	{
		return [
			GDT_String::make('coverage_method')->caseS()->ascii()->max(96)->primary(),
			GDT_UInt::make('coverage_calls'),
		];
	}
	
}
