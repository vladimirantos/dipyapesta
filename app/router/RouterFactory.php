<?php

namespace App;

use Nette,
    Nette\Application\Routers\RouteList,
    Nette\Application\Routers\Route,
    Nette\Application\Routers\SimpleRouter;

/**
 * Router factory.
 */
class RouterFactory {

    private $products = array(
        'balsamica' => "208",
        'dipy' => "203",
        'pesta' => "202"
    );

    /**
     * @return \Nette\Application\IRouter
     */
    public function createRouter() {
        $router = new RouteList();

        $admin = new RouteList('Admin');
        $admin[] = new Route('admin/<presenter>/<action>[/<id>]', 'Homepage:default');
        $router[] = $admin;

        $router[] = new Route('<presenter>/<action>/<id>', array(
            'presenter' => "products",
            'action' => "detail",
            'id' => array(
                Route::FILTER_TABLE => ($this->products)
            ),
        ));
        $router[] = new Route('<presenter>/<action>[/<id>]', 'Homepage:default');



        return $router;
    }

}
