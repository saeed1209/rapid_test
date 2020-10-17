<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\API\V1\FilterRequest;
use App\Notifications\SendMailNotification;
use App\Presenters\CompanySymbolPresenter;
use App\Services\ICompanyService;
use App\Services\ICompanySymbolService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Notification;

class CompanyController extends AppBaseController implements ShouldQueue
{

    public $companySymbolService;
    public $companyService;


    public function __construct(ICompanySymbolService $companySymbolService,
                                ICompanyService $companyService)
    {
        $this->companySymbolService = $companySymbolService;
        $this->companyService = $companyService;
    }


    public function getCompaniesSymbols()
    {

        $symbol_names = $this->getSymbolNames();

        $result = CompanySymbolPresenter::initialize($symbol_names);

        return $this->sendResponse($result, Response::HTTP_OK,
                            'symbols have been retrieved successfully');
    }


    public function filter(FilterRequest $request)
    {
        extract($request->all());

        $symbol_names = $this->getSymbolNames();

        if(!$this->symbolValidate($symbol_names, $company_symbol))
        {
            return $this->sendError(
                'symbol does not exist in our application',
                Response::HTTP_NOT_FOUND
            );
        }

        $result = $this->companyService->getCompanyInfoByDateFilter($request->all());


        Notification::route('mail', $email)->notify(
            new SendMailNotification(
                $symbol_names[$company_symbol],
                $start_date,
                $end_date
            ));

        return $this->sendResponse($result, Response::HTTP_OK,
                                    'Company information has been sent successfully');

    }


    private function symbolValidate($symbols, $company_symbol)
    {
        if(!$symbols || ($symbols && !array_key_exists($company_symbol,$symbols)))
        {
            return false;
        }
        return true;
    }

    private function getSymbolNames()
    {
        return Cache::remember('symbols', config('cache.ttl'), function() {
            return $this->companySymbolService->getCompaniesSymbols();
        });
    }
}
