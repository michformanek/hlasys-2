<?php

namespace App;

use Drahak\Restful\Application\Routes\CrudRoute;
use Drahak\Restful\Application\Routes\ResourceRoute;
use Nette;
use Nette\Application\Routers\RouteList;
use Nette\Application\Routers\Route;


class RouterFactory
{
	use Nette\StaticClass;

	/**
	 * @return Nette\Application\IRouter
	 */
	public static function createRouter()
	{
		$router = new RouteList;
        $router[] = new CrudRoute('api/v1/<presenter>[/<id>[/<relation>[/<relationId>]]]', array(
            'module' => 'Api',
        ));
        $router[] = new Route('<presenter>/<action>[/<id>]', 'Homepage:default');
        return $router;
	}

}
