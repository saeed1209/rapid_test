<?php

namespace Tests\Feature;

use App\Presenters\CompanySymbolPresenter;
use App\Services\ICompanySymbolService;
use Illuminate\Http\Response;
use Tests\TestCase;

class CompanyTest extends TestCase
{


    public function testGetCompaniesSymbols()
    {
        $this->mockGetCompaniesSymbolNames();

        $result = $this->mockGetCompaniesSymbolNamesPresenter();

        $response = $this->get('/api/company/symbols');

        $response->assertStatus(Response::HTTP_OK)
                 ->assertJson(json_decode($result, true));

    }

    public function testCompaniesFilterValidationWithoutInputData()
    {
        $data = [];

        $response = $this->json('get', 'api/company/filter', $data, ['Accept' => 'application/json']);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson([
                "message"=> "The given data was invalid.",
                'errors' => [
                    'start_date' => ['The start date field is required.'],
                    'email' => ['The email field is required.'],
                    'end_date' => ['The end date field is required.'],
                    'company_symbol' => ['The company symbol field is required.'],
                ]
            ]);
    }

    public function testCompaniesFilterStartDateIsGreaterThanEndDateValidation()
    {
        $data = [
            "start_date"=>"2020-10-17",
            "end_date"=>"2020-10-16",
            "email"=>"sa@gmail.com",
            "company_symbol"=>"AAIT",
        ];

        $response = $this->json('get', 'api/company/filter', $data, ['Accept' => 'application/json']);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson([
                "message"=> "The given data was invalid.",
                'errors' => [
                    'start_date' => ['The start date must be a date before or equal to end date.'],
                    'end_date' => ['The end date must be a date after or equal to start date.'],
                ]
            ]);
    }

    public function testCompaniesFilterStartDateIsGreaterThanNowAndGreaterThanEndDateValidation()
    {
        $data = [
            "start_date"=>"2020-10-18",
            "end_date"=>"2020-10-16",
            "email"=>"sa@gmail.com",
            "company_symbol"=>"AAIT",
        ];

        $response = $this->json('get', 'api/company/filter', $data, ['Accept' => 'application/json']);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson([
                "message"=> "The given data was invalid.",
                'errors' => [
                    'start_date' => ['The start date must be a date before or equal to end date.',
                                        "The start date must be a date before or equal to now."],
                    'end_date' => ['The end date must be a date after or equal to start date.'],
                ]
            ]);
    }

    public function testCompaniesFilterEmailValidation()
    {
        $data = [
            "start_date"=>"2020-10-18",
            "end_date"=>"2020-10-16",
            "email"=>"sa@",
            "company_symbol"=>"AAIT",
        ];

        $response = $this->json('get', 'api/company/filter', $data, ['Accept' => 'application/json']);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson([
                "message"=> "The given data was invalid.",
                'errors' => [
                    'email' => ['The email must be a valid email address.'],
                ]
            ]);
    }


    public function testCompaniesFilterSymbolValidation()
    {
        $data = [
            "start_date"=>"2020-10-15",
            "end_date"=>"2020-10-17",
            "email"=>"sa@gmail.com",
            "company_symbol"=>"AAIT-1",
        ];

        $response = $this->json('get', 'api/company/filter', $data, ['Accept' => 'application/json']);

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJson([
                "message"=> "symbol does not exist in our application"
            ]);
    }

    public function testValidCompaniesFilterMethod()
    {
        $data = [
            "start_date"=>"2020-10-15",
            "end_date"=>"2020-10-17",
            "email"=>"sa@gmail.com",
            "company_symbol"=>"AAL",
        ];

        $this->mockGetCompaniesSymbolNames();

        $response = $this->json('get', 'api/company/filter', $data, ['Accept' => 'application/json']);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                "message"=>'Company information has been sent successfully'
            ]);
    }


    private function mockGetCompaniesSymbolNames()
    {
        $companies_mock_data = [
            'AATR' => 'iShares MSCI All Country Asia Information Technology Index Fund',
            'AAL' => 'American Airlines Group, Inc.'
        ];
        $this->mock(ICompanySymbolService::class,function ($mock) use ($companies_mock_data) {
            $mock->shouldReceive('getCompaniesSymbols')
                 ->andReturn($companies_mock_data);
        });
    }

    private function mockGetCompaniesSymbolNamesPresenter()
    {
        $companies_mock_data = json_encode([
            'data' => [
                [
                    'symbol' => 'AATR',
                    'name' => 'iShares MSCI All Country Asia Information Technology Index Fund'
                ],
                [
                    'symbol' => 'AAL',
                    'name' => 'American Airlines Group, Inc.'
                ]
            ],
            'message' => 'symbols have been retrieved successfully'
        ]);

        $this->mock(CompanySymbolPresenter::class,function ($mock) use ($companies_mock_data) {
            $mock->shouldReceive('initialize')
                 ->andReturn($companies_mock_data);
        });

        return $companies_mock_data;
    }
}
