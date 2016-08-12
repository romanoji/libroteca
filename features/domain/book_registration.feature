Feature: Book registration
  In order to give the opportunity to borrow a books
  As a librarian
  I need at first to be able to register books in a library

  Scenario: Registering a new book
    When I decide to register book with a title "Harry Potter"
    And with a subtitle "and the Goblet of Fire"
    And with an author "J.K. Rowling"
    And identified by ISBN "0-7475-4624-X"
    And when I confirm registration
    Then the book should be registered
    And there should be 1 copy of that book in library

  Scenario: Registering a new book without ISBN
    When I decide to register book with a title "Pan Tadeusz"
    And with an author "Adam Mickiewicz"
    But without specified ISBN
    And when I confirm registration
    Then the book should be registered
    And there should be 1 copy of that book in library

  Scenario: Registering a book copy
    Given there is a book with a title "A Dance with Dragons"
    And with an author "George R. R. Martin"
    And identified by ISBN "978-0553801477"
    And there are 2 copies of that book in library
    When I register another copy of that book
    Then there should be 3 copies of that book in library

  Scenario: Trying to register a new book providing incomplete ISBN
    When I try to register book with a title "Harry Potter"
    And with an author "J.K. Rowling"
    And I specify incomplete ISBN "0-7475"
    Then I should be notified that ISBN is incomplete
    And the book should not be registered in library

  Scenario Outline: Trying to register a new book providing invalid ISBN
    When I try to register book with a title <title>
    And with an author <author>
    And I specify invalid ISBN <isbn>
    Then I should be notified that ISBN is invalid
    And the book should not be registered in library

    Examples:
      |         title        |        author       |       isbn      |
      |     Harry Potter     |     J.K. Rowling    |  O-7A75-A6ZA-X  |
      | A Dance with Dragons | George R. R. Martin |  978-0553801471 |
      |   A Feast for Crows  | George R. R. Martin |  0-00-224743-1  |

  Scenario: Trying to register a new book providing an existing ISBN
    Given there is a book with a title "A Dance with Dragons"
    And with an author "George R. R. Martin"
    And identified by ISBN "978-0553801477"
    When I try to register another book with ISBN "978-0553801477"
    Then I should be notified that book with provided ISBN is already registered in library
    And there should be only one book with ISBN "978-0553801477"

  # TODO
#  Scenario: Registering a new book with minimum details
#  Scenario: Registering a new book with all details