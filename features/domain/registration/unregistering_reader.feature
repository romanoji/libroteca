Feature: Unregistering reader
  In order to give readers possibility to unregister
  As a librarian
  I need to be able to unregister reader

  Scenario: Unregistering reader with no books borrowed
    Given there is reader with email "john.kowalsky@mail.com", name "John", surname "Kowalsky" and phone "123-456-789"
    And the reader has not borrowed any book copy
    When I unregister the reader with email "john.kowalsky@mail.com"
    Then the reader with email "john.kowalsky@mail.com" should be unregistered

  Scenario: Trying to unregister reader with at least 1 unreturned book
    Given there is registered a book of "George R.R. Martin" titled "A Dance with Dragons" with ISBN "978-0553801477"
    And there is 1 copy of book with ISBN "978-0553801477" in library
    And there is reader with email "john.kowalsky@mail.com", name "John", surname "Kowalsky" and phone "123-456-789"
    And there is a loan for a book of ISBN "978-0553801477" to reader with email "john.kowalsky@mail.com" for 30 days
#    And the reader with email "john.kowalsky@mail.com" has borrowed a copy of book with ISBN "978-0553801477"
    When I try to unregister the reader with email "john.kowalsky@mail.com"
    Then I should be notified that the reader has unreturned books and must return them first
    And the reader with email "john.kowalsky@mail.com" should not be unregistered