<?php
namespace AppBundle\Traits;

trait ControllerRouteTrait {

    protected static $routeNames = [];

    public function setRouteNames()
    {
        self::$routeNames = [
            "index" => self::ROUTE_PREFIX . "_index",
            "new" => self::ROUTE_PREFIX . "_new",
            "edit" => self::ROUTE_PREFIX . "_edit",
            "show" => self::ROUTE_PREFIX . "_show",
            "delete" => self::ROUTE_PREFIX . "_delete"
        ];
    }

    public static function getRouteNames()
    {
        return self::$routeNames;
    }
}
