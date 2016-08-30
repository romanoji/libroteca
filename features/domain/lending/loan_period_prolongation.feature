Feature: Prolongation of loan period on reader's request
  In order to give readers second chance to read borrowed by them books
  As a librarian
  I need to be able to prolong loan period

  Remarks:
    - loan period can be prolonged by at most 30 days (1 month)
    - default loan prolongation is 14 days (2 weeks)

  Background:
    Given there is reader with email "john.kowalsky@mail.com", name "John", surname "Kowalsky" and phone "123456789"
    And there is registered a book of "George R.R. Martin" titled "A Dance with Dragons" with ISBN "978-0553801477"
    And there is 1 book copy of ISBN "978-0553801477" available for loan

  Scenario: Prolonging loan period on reader's request by 2 weeks
    Given there is a loan for a book of ISBN "978-0553801477" to reader with email "john.kowalsky@mail.com" till "tommorow"
    When I prolong loan period on this book by 14 days
    Then this loan should expire 15 days later

  Scenario: Attempt to prolong loan period second time
    Given there is a loan for a book of ISBN "978-0553801477" to reader with email "john.kowalsky@mail.com" till "tommorow"
    And this loan has already been prolonged
    When I prolong loan period on this book by 30 days
    Then I should be notified that the loan period has been already prolonged
    And this loan period should not be prolonged

  Scenario: Attempt to prolong loan period after loan expiration date
    Given there is a loan for a book of ISBN "978-0553801477" to reader with email "john.kowalsky@mail.com" till "yesterday"
    When I prolong loan period on this book by 14 days
    Then I should be notified that the loan period has already expired
    And this loan period should not be prolonged

  Scenario: Attempt to prolong loan period by 60 days
    Given there is a loan for a book of ISBN "978-0553801477" to reader with email "john.kowalsky@mail.com" till "yesterday"
    When I prolong loan period on this book by 60 days
    Then I should be notified that the loan prolongation is at most 30 days
    And this loan period should not be prolonged