<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Lumen\Providers;

use Illuminate\Support\ServiceProvider;
use RJozwiak\Libroteca\Application\Query\BookCopyQueryService;
use RJozwiak\Libroteca\Application\Query\BookLoanQueryService;
use RJozwiak\Libroteca\Application\Query\BookQueryService;
use RJozwiak\Libroteca\Application\Query\ReaderQueryService;
use RJozwiak\Libroteca\Infrastructure\Application\Query\Lumen\LumenBookCopyQueryService;
use RJozwiak\Libroteca\Infrastructure\Application\Query\Lumen\LumenBookLoanQueryService;
use RJozwiak\Libroteca\Infrastructure\Application\Query\Lumen\LumenBookQueryService;
use RJozwiak\Libroteca\Infrastructure\Application\Query\Lumen\LumenReaderQueryService;

class QueryServicesProvider extends ServiceProvider
{
    public function register() : void
    {
        $this->app->singleton(ReaderQueryService::class, LumenReaderQueryService::class);

        $this->app->singleton(BookQueryService::class, LumenBookQueryService::class);

        $this->app->singleton(BookCopyQueryService::class, LumenBookCopyQueryService::class);

        $this->app->singleton(BookLoanQueryService::class, LumenBookLoanQueryService::class);
    }
}
