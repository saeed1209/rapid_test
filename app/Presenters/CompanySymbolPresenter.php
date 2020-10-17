<?php
/**
 * Created by PhpStorm.
 * User: sama
 * Date: 10/17/20
 * Time: 3:33 PM
 */

namespace App\Presenters;


class CompanySymbolPresenter extends AbstractPresenter
{
    protected $symbol;
    protected $name;

    public static function initialize($data)
    {
        $transformed = array_map(function($key, $value) {
            $instance = [];
            $instance['symbol']=$key;
            $instance['name']= $value;
            return $instance;
        }, array_keys($data), $data);

        return $transformed;

    }
}