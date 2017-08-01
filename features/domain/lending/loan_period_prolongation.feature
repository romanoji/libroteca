Feature: Prolongation of book loan period
  In order to give readers second chance to finish reading borrowed books
  As a librarian
  I need to be able to prolong book loan period

  Remarks:
    - loan period can be prolonged by at most 30 days (1 month)
    - default loan prolongation is 14 days (2 weeks)

  Background:
    Given there is reader with email "john.kowalsky@mail.com", name "John", surname "Kowalsky" and phone "123456789"
    And there is registered a book "A Dance with Dragons" of "George R.R. Martin" with ISBN "978-0553801477"
    And there is 1 book copy with ISBN "978-0553801477" in the library

  Scenario: Prolonging a book loan period by 2 weeks
    Given there is a loan for a book copy with ISBN "978-0553801477" to reader with email "john.kowalsky@mail.com" till "tomorrow"
    When I prolong loan period on this book copy by 14 days
    Then this loan should be prolonged by 14 days
    And this loan should expire 15 days later

  Scenario: Attempt to prolong a book loan period second time
    Given there is a loan for a book copy with ISBN "978-0553801477" to reader with email "john.kowalsky@mail.com" till "tomorrow"
    And this loan has already been prolonged for 14 days
    When I prolong loan period on this book copy by 30 days
    Then I should be notified that the loan period has been already prolonged
    And this loan should be prolonged by 14 days

  Scenario: Attempt to prolong a book loan period after loan expiration date
    Given there is a loan for a book copy with ISBN "978-0553801477" to reader with email "john.kowalsky@mail.com" till "yesterday"
    When I prolong loan period on this book copy by 14 days
    Then I should be notified that the loan period has already expired
    And this loan period should not be prolonged

  Scenario: Attempt to prolong a book loan period by 60 days
    Given there is a loan for a book copy with ISBN "978-0553801477" to reader with email "john.kowalsky@mail.com" till "tomorrow"
    When I prolong loan period on this book copy by 60 days
    Then I should be notified that the loan prolongation is at most 30 days
    And this loan period should not be prolonged

#  Scenario: Attempt to prolong a book loan, which has already ended
