Feature: Book registration
  In order to give readers opportunity to borrow a books
  As a librarian
  I need first to be able to register books in a library

  Scenario: Registering a new book
    Given there is no book registered with ISBN "0-7475-4624-X" in library
    When I have a book with a title "Harry Potter"
    And with a subtitle "and the Goblet of Fire"
    And with an author "J.K. Rowling"
    And identified by ISBN "0-7475-4624-X"
    And I register this book
    Then the book should be registered in library
    And there should be 1 book copy in library

  Scenario: Registering a new book without ISBN
    When I have a book with a title "Pan Tadeusz"
    And with an author "Adam Mickiewicz"
    But without specified ISBN
    And I register this book
    Then the book should be registered in library
    And there should be 1 book copy in library

  Scenario: Registering an another book copy
    Given there is registered a book of "George R.R. Martin" titled "A Dance with Dragons" with ISBN "978-0553801477"
    And there are already 2 book copies in library
    When I register another copy of this book
#    When I have a book with an ISBN "978-0553801477"
#    And I register this book copy
    Then there should be 3 book copies in library

  Scenario: Registering a book copy with no copies in library
    Given there is registered a book of "George R.R. Martin" titled "A Dance with Dragons" with ISBN "978-0553801477"
    But there are no book copies in library
    When I register copy of this book
#    When I have a book with an ISBN "978-0553801477"
#    And I register this book
    Then there should be 1 book copy in library

  Scenario: Trying to register a new book with ISBN already in use
    Given there is registered a book of "George R.R. Martin" titled "A Dance with Dragons" with ISBN "978-0553801477"
    When I have a book with an ISBN "978-0553801477"
    And I register this book
    Then I should be notified that book with provided ISBN is already registered in library

  Scenario: Trying to register a new book providing incomplete ISBN
    When I have a book with a title "Harry Potter"
    And with an author "J.K. Rowling"
    And I specify incomplete ISBN "0-7475"
    And I try to register this book
    Then I should be notified that ISBN is incomplete
    And the book should not be registered in library
    And there are no copies of this book in library

  Scenario Outline: Trying to register a new book providing invalid ISBN
    When I have a book with a title <title>
    And with a subtitle <subtitle>
    And with an author <author>
    And I specify invalid ISBN <isbn>
    And I try to register this book
    Then I should be notified that ISBN is invalid
    And the book should not be registered in library
    And there are no copies of this book in library

    Examples:
      |         title        |         subtitle         |       author       |       isbn      |
      |     Harry Potter     |  and the Goblet of Fire  |    J.K. Rowling    |  O-7A75-A6ZA-X  |
      | A Dance with Dragons |                          | George R.R. Martin |  978-0553801471 |
      |   A Feast for Crows  |                          | George R.R. Martin |  0-00-224743-1  |