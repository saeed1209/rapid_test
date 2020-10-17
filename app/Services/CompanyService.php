<?php
/**
 * Created by PhpStorm.
 * User: sama
 * Date: 10/16/20
 * Time: 7:01 PM
 */

namespace App\Services;


use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CompanyService extends BaseService implements ICompanyService
{
    public function __construct()
    {
        parent::__construct([
                                "base_url"=>config('rapidapi.base_url'),
                                "headers"=>
                                    ['x-rapidapi-key'=> config('rapidapi.x-rapidapi-key'),
                                     'x-rapidapi-host'=> config('rapidapi.x-rapidapi-host')
                                    ]
                            ]);
    }

    public function getCompanyInfoByDateFilter($input)
    {
        $params = [
            'query' => [
                'period1' => Carbon::create($input['start_date'])->timestamp,
                'period2' => Carbon::create($input['end_date'])->timestamp,
                'symbol'=>$input['company_symbol']
            ]
        ];

        $response = $this->client->get( 'stock/v2/get-historical-data', $params);

        try {

            $response_body = json_decode($response->getBody(),true);
            $prices = $response_body['prices'] ?? [];

            $response = array_map(function($element) {
                $date = $element['date'] ?? null;
                $element['date'] = $date ? Carbon::createFromTimestamp($date)->format('Y-m-d') : null;
                return $element;
            },$prices);

            return $response;

        } catch (GuzzleException $e) {

            throw new HttpException($e->getCode(), $e->getMessage());
        }
    }

    public function showCompanyInfo($input)
    {
        // TODO: Implement showCompanyInfo() method.
    }
}