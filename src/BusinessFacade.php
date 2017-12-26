<?php
namespace Solunes\Business;

use Illuminate\Support\Facades\Facade;

class BusinessFacade extends Facade
{
	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor()
	{
		return 'business';
	}
}