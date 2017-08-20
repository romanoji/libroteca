<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Lumen\Providers;

use Illuminate\Support\ServiceProvider;
use RJozwiak\Libroteca\Application\Command\ImportBooks\ImportFileLoader;
use RJozwiak\Libroteca\Infrastructure\Application\Command\CsvFileLoader;

class ApplicationServicesProvider extends ServiceProvider
{
    public function register() : void
    {
        $this->app->singleton(ImportFileLoader::class, CsvFileLoader::class);
    }
}
