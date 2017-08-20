<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Lumen\Providers;

use Illuminate\Support\ServiceProvider;
use RJozwiak\Libroteca\Domain\Model\Book\BookRepository;
use RJozwiak\Libroteca\Domain\Model\BookCopy\BookCopyRepository;
use RJozwiak\Libroteca\Domain\Model\BookLoan\BookLoanRepository;
use RJozwiak\Libroteca\Domain\Model\Reader\ReaderRepository;
use RJozwiak\Libroteca\Infrastructure\Domain\Model\Book\LumenBookRepository;
use RJozwiak\Libroteca\Infrastructure\Domain\Model\BookCopy\LumenBookCopyRepository;
use RJozwiak\Libroteca\Infrastructure\Domain\Model\BookLoan\LumenBookLoanRepository;
use RJozwiak\Libroteca\Infrastructure\Domain\Model\Reader\LumenReaderRepository;
use RJozwiak\Libroteca\Infrastructure\Serialization\Basic\Deserializer;
use RJozwiak\Libroteca\Infrastructure\Serialization\Basic\ObjectDeserializer;

class RepositoriesProvider extends ServiceProvider
{
    public function register() : void
    {
        $this->app->singleton(Deserializer::class, ObjectDeserializer::class);

        $this->app->singleton(BookRepository::class, LumenBookRepository::class);

        $this->app->singleton(BookCopyRepository::class, LumenBookCopyRepository::class);

        $this->app->singleton(ReaderRepository::class, LumenReaderRepository::class);

        $this->app->singleton(BookLoanRepository::class, LumenBookLoanRepository::class);
    }
}
