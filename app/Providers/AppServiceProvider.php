<?php

namespace App\Providers;

use App\Services\CompanyService;
use App\Services\CompanySymbolService;
use App\Services\ICompanyService;
use App\Services\ICompanySymbolService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ICompanySymbolService::class, CompanySymbolService::class);
        $this->app->bind(ICompanyService::class, CompanyService::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
