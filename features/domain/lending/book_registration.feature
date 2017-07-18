Feature: Book registration
  In order to give readers opportunity to borrow a books
  As a librarian
  I need first to be able to register books in the library catalog

  Scenario: Register a new book with ISBN
    Given there is no book with ISBN "9781568650548" registered in library
    When I register a book "The Mote in God's Eye" of "Larry Niven, Jerry Pournelle" with ISBN "9781568650548"
    Then the book should be registered in library

  Scenario: Register a new book without ISBN
    When I register a book "Pan Tadeusz" of "Adam Mickiewicz"
    Then the book should be registered in library

  Scenario: Trying to register a new book with ISBN already in use
    Given there is registered a book "A Dance with Dragons" of "George R.R. Martin" with ISBN "978-0553801477"
    When I register a book "a dance with dragons" of "G.R.R. Martin" with ISBN "978-0553801477"
    Then I should be notified that book with provided ISBN is already registered in library
    And the book "A Dance with Dragons" of "George R.R. Martin" with ISBN "978-0553801477" should be registered in library

  Scenario: Trying to register a new book providing ISBN of invalid length
    When I register a book "Harry Potter and the Goblet of Fire" of "J.K. Rowling" with ISBN "0-7475"
    Then I should be notified that ISBN has invalid length
    And the book should not be registered in library

  Scenario Outline: Trying to register a new book providing invalid ISBN
    When I register a book "<title>" of "<authors>" with ISBN "<isbn>"
    Then I should be notified that ISBN has invalid format
    And the book should not be registered in library

    Examples:
      |                title                |       authors      |       isbn      |
      | Harry Potter and the Goblet of Fire |    J.K. Rowling    |  O-7A75-A6ZA-X  |
      |        A Dance with Dragons         | George R.R. Martin |  978-0553801471 |
      |          A Feast for Crows          | George R.R. Martin |  0-00-224743-1  |

  Scenario: Register a book copy without any copies of the book in a library
    Given there is registered a book "A Dance with Dragons" of "George R.R. Martin" with ISBN "978-0553801477"
    But there are no book copies with ISBN "978-0553801477" available for loan
    When I register book copy by ISBN "978-0553801477"
    Then there should be 1 book copy with ISBN "978-0553801477" available for loan

  Scenario: Register an another book copy in a library
    Given there is registered a book "A Dance with Dragons" of "George R.R. Martin" with ISBN "978-0553801477"
    And there are 2 book copies with ISBN "978-0553801477" available for loan
    When I register book copy by ISBN "978-0553801477"
    Then there should be 3 book copies with ISBN "978-0553801477" available for loan
