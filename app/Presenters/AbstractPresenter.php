<?php
/**
 * Created by PhpStorm.
 * User: sama
 * Date: 10/17/20
 * Time: 3:32 PM
 */

namespace App\Presenters;


abstract class AbstractPresenter
{
    const FILTER_NULL_VALUES = false;
    /**
     * @return array
     */
    public function toArray()
    {
        $array = [];
        try {
            $reflection = new \ReflectionClass($this);

            foreach ($reflection->getProperties() as $property) {
                $property->setAccessible(true);
                $val = $property->getValue($this);
                if (!self::FILTER_NULL_VALUES || $val !== null) {
                    $array[$property->getName()] = $val;
                }
            }
        } catch (\ReflectionException $exception) {
        }
        return $array;
    }
}