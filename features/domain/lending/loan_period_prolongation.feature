Feature: Prolongation of loan period on reader's request
  In order to give readers second chance to read borrowed by them books
  As a librarian
  I need to be able to extend loan period

  Extending loan period options:
  - 2 weeks (14 days) (default)
  - a month (30 days)

  Background:
    Given there is reader with email "john.kowalsky@mail.com"
    And there is registered a book of "George R.R. Martin" titled "A Dance with Dragons" with ISBN "978-0553801477"
    And there is 1 copy of book with ISBN "978-0553801477" in library

  Scenario: Extending loan period on reader's request by 2 weeks
    Given there is a loan for a book of ISBN "978-0553801477" to reader with email "andy.novak@mail.com" till "tommorow"
    When I extend loan period on that book by 14 days
    Then this loan should expire 15 days later

  Scenario: Attempt to extend loan period second time
    Given there is a loan for a book of ISBN "978-0553801477" to reader with email "andy.novak@mail.com" till "tommorow"
    And this loan has been extended
    When I extend loan period on that book by 30 days
    Then I should be notified that the loan period has been already extended
    And this loan period should not be extended

  Scenario: Attempt to extend loan period after loan expiration date
    Given there is a loan for a book of ISBN "978-0553801477" to reader with email "andy.novak@mail.com" till "yesterday"
    When I extend loan period on that book by 14 days
    Then I should be notified that the loan period has already expired
    And this loan period should not be extended