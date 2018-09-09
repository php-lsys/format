<?php
namespace LSYS\FormatData;
/**
 * @method \LSYS\FormatData format_data()
 */
class DI extends \LSYS\DI{
    /**
     * @return static
     */
    public static function get(){
        $di=parent::get();
        !isset($di->format_data)&&$di->format_data(new \LSYS\DI\SingletonCallback(function (){
            return new \LSYS\FormatData();
        }));
        return $di;
    }
}