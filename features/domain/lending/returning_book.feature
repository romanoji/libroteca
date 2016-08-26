Feature: Accepting borrowed book
  In order to ensure continuous flow of the books in library
  As a librarian
  I need to be able to accept borrowed books from the readers

  Overdue interest - 0.25€/$/£ per day

  Background:
    Given there is reader with email "john.kowalsky@mail.com"
    And there is registered a book of "George R.R. Martin" titled "A Dance with Dragons" with ISBN "978-0553801477"
    And there is 1 copy of book with ISBN "978-0553801477" in library

  Scenario: Accepting a lended book from a reader
#    Given reader with email "john.kowalsky@mail.com" has borrowed a book of ISBN "978-0553801477" for 14 days
    Given there is a loan for a book of ISBN "978-0553801477" to reader with email "john.kowalsky@mail.com" for 14 days
    When the reader with email "john.kowalsky@mail.com" return borrowed book of ISBN "978-0553801477"
    Then the book copy should be returned
    And there should be 1 book copy of ISBN "978-0553801477" available for loan

  Scenario: Accepting a lended book with loan payment due to an overdue
#    Given reader with email "john.kowalsky@mail.com" has borrowed a book of ISBN "978-0553801477" till "yesterday"
    Given there is a loan for a book of ISBN "978-0553801477" to reader with email "john.kowalsky@mail.com" till "yesterday"
    When the reader with email "john.kowalsky@mail.com" return borrowed book of ISBN "978-0553801477"
    And the reader pays for an overdue
    Then the book copy should be returned
    And there should be 1 book copy of ISBN "978-0553801477" available for loan

  Scenario: Not accepting a lended book without paying for an overdue
#    Given reader with email "john.kowalsky@mail.com" has borrowed a book of ISBN "978-0553801477" till "yesterday"
    Given there is a loan for a book of ISBN "978-0553801477" to reader with email "john.kowalsky@mail.com" till "yesterday"
    When the reader with email "john.kowalsky@mail.com" return borrowed book of ISBN "978-0553801477"
    But the readers do not pay for an overdue
    Then the book copy should not be returned
    And I should be notified that reader has to pay 0.25€/$/£ for an overdue
    And there should be no book copy of ISBN "978-0553801477" available for loan