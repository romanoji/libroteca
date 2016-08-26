Feature: Sending notifications to readers before loan expiration
  In order to remind readers of loan expiration date
  System should be able to send email notifications to them

  Scenario: Sending notification on 2 days before an loan expiration date
    Given there is reader with email "john.kowalsky@mail.com"
    And there is registered a book of "George R.R. Martin" titled "A Dance with Dragons" with ISBN "978-0553801477"
    And there is 1 copy of book with ISBN "978-0553801477" in library
    And there is a loan for a book of ISBN "978-0553801477" to reader with email "andy.novak@mail.com" till "day after tommorow"
    Then the reader should be notified on email "john.kowalsky@mail.com" about loan expiration date