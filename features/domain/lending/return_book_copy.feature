Feature: Accepting borrowed book copy
  In order to ensure continuous books circulation in library
  As a librarian
  I need to be able to accept borrowed books copies from the readers

  Background:
    Given there is reader with email "john.kowalsky@mail.com", name "John", surname "Kowalsky" and phone "123456789"
    And there is registered a book of "George R.R. Martin" titled "A Dance with Dragons" with ISBN "978-0553801477"
    And there is 1 book copy of ISBN "978-0553801477" available for loan

  Scenario: Accept lent book copy from a reader
    Given there is a loan for a book of ISBN "978-0553801477" to reader with email "john.kowalsky@mail.com" till "tommorow"
    When I accept a book copy identified by ISBN "978-0553801477" from a reader with email "john.kowalsky@mail.com" without remarks
    Then this book copy should be returned
    And there should be 1 book copy of ISBN "978-0553801477" available for loan

  Scenario: Accept lent book copy from a reader after loan expiration date
    Given there is a loan for a book of ISBN "978-0553801477" to reader with email "john.kowalsky@mail.com" till "yesterday"
    When I accept a book copy identified by ISBN "978-0553801477" from a reader with email "john.kowalsky@mail.com" with remarks:
    """
    Reader returned book for the first time after deadline.
    The book is in good state.
    """
    Then this book copy should be returned
    And there should be 1 book copy of ISBN "978-0553801477" available for loan

  Scenario: Attempt to accept lent book copy from a reader after loan expiration date with no remarks
    Given there is a loan for a book of ISBN "978-0553801477" to reader with email "john.kowalsky@mail.com" till "yesterday"
    When I accept a book copy identified by ISBN "978-0553801477" from a reader with email "john.kowalsky@mail.com" without remarks
    Then I should be notified that adding remarks to overdue loan is required
    And this book copy should not be returned
    And there should be no book copies of ISBN "978-0553801477" available for loan