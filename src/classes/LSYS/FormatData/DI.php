<?php
namespace LSYS\FormatData;
/**
 * @method \LSYS\FormatData formatData()
 */
class DI extends \LSYS\DI{
    /**
     * @return static
     */
    public static function get(){
        $di=parent::get();
        !isset($di->formatData)&&$di->formatData(new \LSYS\DI\SingletonCallback(function (){
            return new \LSYS\FormatData();
        }));
        return $di;
    }
}