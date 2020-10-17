<?php
/**
 * Created by PhpStorm.
 * User: sama
 * Date: 10/16/20
 * Time: 7:00 PM
 */

namespace App\Services;


interface ICompanyService
{
    public function getCompanyInfoByDateFilter($input);
}