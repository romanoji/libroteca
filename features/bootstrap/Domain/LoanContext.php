<?php

namespace Domain;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Behat\Tester\Exception\PendingException;

class LoanContext implements Context, SnippetAcceptingContext
{
    public function __construct()
    {
    }

    /**
     * @Given there is a loan for a book of ISBN :isbn to reader with email :email for :days days
     */
    public function thereIsALoanForABookOfIsbnToReaderWithEmailForDays($isbn, $email, $days)
    {
        throw new PendingException();
    }

    /**
     * @Given there is a loan for a book of ISBN :isbn to reader with email :email till :till
     */
    public function thereIsALoanForABookOfIsbnToReaderWithEmailTill($isbn, $email, $till)
    {
        throw new PendingException();
    }

    /**
     * @Given this loan has already been prolonged
     */
    public function thisLoanHasAlreadyBeenProlonged()
    {
        throw new PendingException();
    }

    /**
     * @When I lend book copy with ISBN :isbn to the reader with email :email for :days days
     */
    public function iLendBookCopyWithIsbnToTheReaderWithEmailForDays($isbn, $email, $days)
    {
        throw new PendingException();
    }

    /**
     * @When I try to lend the same book copy to the reader with email :email for :days days
     */
    public function iTryToLendTheSameBookCopyToTheReaderWithEmailForDays($email, $days)
    {
        throw new PendingException();
    }

    /**
     * @When I prolong loan period on this book by :days days
     */
    public function iProlongLoanPeriodOnThisBookByDays($days)
    {
        throw new PendingException();
    }

    /**
     * @When I accept a book copy identified by ISBN :isbn from a reader with email :email without remarks
     */
    public function iAcceptABookCopyIdentifiedByIsbnFromAReaderWithEmailWithoutRemarks($isbn, $email)
    {
        throw new PendingException();
    }

    /**
     * @When I accept a book copy identified by ISBN :isbn from a reader with email :email with remarks:
     */
    public function iAcceptABookCopyIdentifiedByIsbnFromAReaderWithEmailWithRemarks(
        $isbn,
        $email,
        PyStringNode $remarks
    ) {
        throw new PendingException();
    }

    /**
     * @Then reader with email :email should have that book copy lent for :days days
     */
    public function readerWithEmailShouldHaveThatBookCopyLentForDays($email, $days)
    {
        throw new PendingException();
    }

    /**
     * @Then the book loan should not be successful
     */
    public function theBookLoanShouldNotBeSuccessful()
    {
        throw new PendingException();
    }

    /**
     * @Then this loan should expire :days days later
     */
    public function thisLoanShouldExpireDaysLater($days)
    {
        throw new PendingException();
    }

    /**
     * @Then this loan period should not be prolonged
     */
    public function thisLoanPeriodShouldNotBeProlonged()
    {
        throw new PendingException();
    }

    /**
     * @Then this book copy should be returned
     */
    public function thisBookCopyShouldBeReturned()
    {
        throw new PendingException();
    }

    /**
     * @Then this book copy should not be returned
     */
    public function thisBookCopyShouldNotBeReturned()
    {
        throw new PendingException();
    }

    /**
     * @Then the reader should be notified on email :email about loan expiration with message:
     */
    public function theReaderShouldBeNotifiedOnEmailAboutLoanExpirationWithMessage(
        $email,
        PyStringNode $message
    ) {
        throw new PendingException();
    }

    /**
     * @Then I should be notified that book copy is already borrowed
     */
    public function iShouldBeNotifiedThatBookCopyIsAlreadyBorrowed()
    {
        throw new PendingException();
    }

    /**
     * @Then I should be notified that reader must return books first to proceed with loan attempt
     */
    public function iShouldBeNotifiedThatReaderMustReturnBooksFirstToProceedWithLoanAttempt()
    {
        throw new PendingException();
    }

    /**
     * @Then I should be notified that the loan period has been already prolonged
     */
    public function iShouldBeNotifiedThatTheLoanPeriodHasBeenAlreadyProlonged()
    {
        throw new PendingException();
    }

    /**
     * @Then I should be notified that the loan period has already expired
     */
    public function iShouldBeNotifiedThatTheLoanPeriodHasAlreadyExpired()
    {
        throw new PendingException();
    }

    /**
     * @Then I should be notified that loan period can last at most for :days days
     */
    public function iShouldBeNotifiedThatLoanPeriodCanLastAtMostForDays($days)
    {
        throw new PendingException();
    }

    /**
     * @Then I should be notified that the loan prolongation is at most :days days
     */
    public function iShouldBeNotifiedThatTheLoanProlongationIsAtMostDays($days)
    {
        throw new PendingException();
    }

    /**
     * @Then I should be notified that adding remarks to overdue loan is required
     */
    public function iShouldBeNotifiedThatAddingRemarksToOverdueLoanIsRequired()
    {
        throw new PendingException();
    }
}
