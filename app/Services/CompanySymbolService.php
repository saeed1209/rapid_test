<?php
/**
 * Created by PhpStorm.
 * User: sama
 * Date: 10/16/20
 * Time: 7:00 PM
 */

namespace App\Services;


use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CompanySymbolService extends BaseService implements ICompanySymbolService
{
    public function __construct()
    {
        parent::__construct([
                                'base_url'=>config('pkgstoreapi.base_url')
                            ]);
    }

    public function getCompaniesSymbols()
    {
        try {

            $response = $this->client->get( "nasdaq-listed_json.json");

            $company_array = json_decode($response->getBody(),true);

            return $this->getCompanySymbolNameArray($company_array);

        } catch (GuzzleException $e) {

            throw new HttpException($e->getCode(), $e->getMessage());

        }

    }

    public function getCompanySymbolNameArray($company_symbol_name_array)
    {
        return array_column($company_symbol_name_array, 'Company Name', 'Symbol');
    }
}