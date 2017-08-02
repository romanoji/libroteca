Feature: Accepting borrowed book copy
  In order to ensure continuous books circulation in library
  As a librarian
  I need to be able to accept borrowed books copies from the readers

  Background:
    Given there is reader with email "john.kowalsky@mail.com", name "John", surname "Kowalsky" and phone "123456789"
    And there is registered a book "A Dance with Dragons" of "George R.R. Martin" with ISBN "978-0553801477"
    And there is 1 book copy with ISBN "978-0553801477" in the library

  Scenario: Accept lent book copy from a reader
    Given there is a loan for a book copy with ISBN "978-0553801477" to reader with email "john.kowalsky@mail.com" till "tomorrow"
    When I accept this book copy without remarks
    Then the book loan should has ended
    And there should be 1 book copy with ISBN "978-0553801477" available for loan

  Scenario: Accept lent book copy from a reader after loan expiration date
    Given there is a loan for a book copy with ISBN "978-0553801477" to reader with email "john.kowalsky@mail.com" till "yesterday"
    When I accept this book copy with remarks:
    """
    Reader returned book for the first time after deadline.
    The book is at good state.
    """
    Then the book loan should has ended
    And there should be 1 book copy with ISBN "978-0553801477" available for loan

  Scenario: Attempt to accept lent book copy from a reader after loan expiration date with no remarks
    Given there is a loan for a book copy with ISBN "978-0553801477" to reader with email "john.kowalsky@mail.com" till "yesterday"
    When I accept this book copy without remarks
    Then I should be notified that adding remarks to overdue loan is required
    And the book loan should has not ended
    And there should be no book copies with ISBN "978-0553801477" available for loan
