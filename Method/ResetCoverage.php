<?php
namespace GDO\Statistics\Method;

use GDO\Form\GDT_Submit;
use GDO\Form\MethodButtonBar;

final class ResetCoverage extends MethodButtonBar
{
	
	public function getButtons()
	{
		return [
			GDT_Submit::make('show')->label('show')->primary()->click([$this, 'onShowCoverage']),
			GDT_Submit::make('reset')->label('reset')->secondary()->click([$this, 'onResetCoverage']),
		];
	}
	
	public function onShowCoverage()
	{
		
	}
	
	public function onResetCoverage()
	{
		
	}
	
}
