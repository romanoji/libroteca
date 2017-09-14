<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Lumen\Providers;

use Illuminate\Support\ServiceProvider;
use RJozwiak\Libroteca\Application\Command\{
    EndBookLoanHandler, ImportBooksHandler, LendBookCopyHandler, SendNotificationToReaderHandler,
    ProlongBookLoanHandler, RegisterBookCopyHandler, RegisterBookHandler,
    RegisterReaderHandler, UpdateBookCopyRemarksHandler, UpdateBookHandler
};

class CommandHandlersProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(LendBookCopyHandler::class);

        $this->app->singleton(ProlongBookLoanHandler::class);

        $this->app->singleton(EndBookLoanHandler::class);

        $this->app->singleton(RegisterBookHandler::class);

        $this->app->singleton(RegisterBookCopyHandler::class);

        $this->app->singleton(RegisterReaderHandler::class);

        $this->app->singleton(UpdateBookHandler::class);

        $this->app->singleton(UpdateBookCopyRemarksHandler::class);

        $this->app->singleton(ImportBooksHandler::class);

        $this->app->singleton(SendNotificationToReaderHandler::class);
    }
}
