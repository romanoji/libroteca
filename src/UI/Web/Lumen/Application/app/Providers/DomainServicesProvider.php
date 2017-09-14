<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Lumen\Providers;

use Illuminate\Support\ServiceProvider;
use RJozwiak\Libroteca\Application\Command\ImportBooks\ImportFileLoader;
use RJozwiak\Libroteca\Domain\Model\Book\ISBN\ISBNFactory;
use RJozwiak\Libroteca\Domain\Model\BookLoan\BookLoanFactory;
use RJozwiak\Libroteca\Domain\Model\Reader\MembershipBasedNotifierProvider;
use RJozwiak\Libroteca\Domain\Model\Reader\Notification\NotifierProvider;
use RJozwiak\Libroteca\Infrastructure\Application\Command\CsvFileLoader;

class DomainServicesProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(ISBNFactory::class);

        $this->app->singleton(BookLoanFactory::class);

        $this->app->singleton(NotifierProvider::class, MembershipBasedNotifierProvider::class);

        $this->app->singleton(ImportFileLoader::class, CsvFileLoader::class);
    }
}
