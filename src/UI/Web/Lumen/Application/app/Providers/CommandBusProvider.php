<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Lumen\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Lumen\Application;
use League\Tactician;
use League\Tactician\Handler\CommandHandlerMiddleware;
use League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor;
use League\Tactician\Handler\CommandNameExtractor\CommandNameExtractor;
use League\Tactician\Handler\Locator\HandlerLocator;
use League\Tactician\Handler\MethodNameInflector\MethodNameInflector;
use RJozwiak\Libroteca\Application\Command\{
    EndBookLoanHandler, ImportBooksHandler, LendBookCopyHandler,
    ProlongBookLoanHandler, RegisterBookCopyHandler, RegisterBookHandler,
    RegisterReaderHandler, UpdateBookCopyRemarksHandler, UpdateBookHandler,
    SendNotificationToReaderHandler
};
use RJozwiak\Libroteca\Infrastructure\Application\CommandBus\Tactician\Locator\InMemoryHandlerLocator;
use RJozwiak\Libroteca\Infrastructure\Application\CommandBus\Tactician\MethodNameInflector\ExecuteInflector;

class CommandBusProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(Tactician\CommandBus::class, function (Application $app) {
            return new Tactician\CommandBus([
                $app->make(CommandHandlerMiddleware::class)
            ]);
        });

        $this->app->singleton(CommandHandlerMiddleware::class);

        $this->app->singleton(CommandNameExtractor::class, ClassNameExtractor::class);

        $this->app->singleton(HandlerLocator::class, function (Application $app) {
            return new InMemoryHandlerLocator([
                $app->make(LendBookCopyHandler::class),
                $app->make(ProlongBookLoanHandler::class),
                $app->make(EndBookLoanHandler::class),
                $app->make(RegisterBookHandler::class),
                $app->make(RegisterBookCopyHandler::class),
                $app->make(RegisterReaderHandler::class),
                $app->make(UpdateBookHandler::class),
                $app->make(UpdateBookCopyRemarksHandler::class),
                $app->make(ImportBooksHandler::class),
                $app->make(SendNotificationToReaderHandler::class)
            ]);
        });

        $this->app->singleton(MethodNameInflector::class, ExecuteInflector::class);
    }
}
