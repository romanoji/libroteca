Feature: Book registration
  In order to give readers opportunity to borrow a books
  As a librarian
  I need first to be able to register books in a library catalog

  # TODO: split into "book info" and "book copy" with remarks

  Scenario: Register a new book copy
    Given there was no book previously registered with ISBN "9781568650548" in library
    When I register a book of "Larry Niven, Jerry Pournelle" titled "The Mote in God's Eye" with ISBN "9781568650548"
    Then the book should be registered in library
    And there should be 1 book copy of ISBN "9781568650548" available for loan

  Scenario: Register a new book copy without ISBN
    When I register a book of "Adam Mickiewicz" titled "Pan Tadeusz"
    Then the book should be registered in library
    And there should be 1 book copy of "Adam Mickiewicz" titled "Pan Tadeusz" available for loan

  Scenario: Register an another book copy
    Given there is registered a book of "George R.R. Martin" titled "A Dance with Dragons" with ISBN "978-0553801477"
    And there are 2 book copies of ISBN "978-0553801477" available for loan
    When I register copy of a book with an ISBN "978-0553801477"
    Then there should be 3 book copies of ISBN "978-0553801477" available for loan

  Scenario: Register a book copy with no copies of the book in library
    Given there is registered a book of "George R.R. Martin" titled "A Dance with Dragons" with ISBN "978-0553801477"
    But there are no book copies of ISBN "978-0553801477" available for loan
    When I register copy of a book with an ISBN "978-0553801477"
    Then there should be 1 book copy of ISBN "978-0553801477" available for loan
    
  Scenario: Trying to register a new book with ISBN already in use
    Given there is registered a book of "George R.R. Martin" titled "A Dance with Dragons" with ISBN "978-0553801477"
    And there is 1 book copy of ISBN "978-0553801477" available for loan
    When I register a book of "George R.R. Martin" titled "A Dance with Dragons" with ISBN "978-0553801477"
    Then I should be notified that book with provided ISBN is already registered in library
    And there should be 1 book copy of ISBN "978-0553801477" available for loan

  Scenario: Trying to register a new book providing incomplete ISBN
    When I register a book of "J.K. Rowling" titled "Harry Potter and the Goblet of Fire" with ISBN "0-7475"
    Then I should be notified that ISBN is incomplete
    And the book should not be registered in library
    And there are no copies of this book in library

  Scenario Outline: Trying to register a new book providing invalid ISBN
    When I register a book of "<authors>" titled "<title>" with ISBN "<isbn>"
    Then I should be notified that ISBN is invalid
    And the book should not be registered in library
    And there are no copies of this book in library

    Examples:
      |                title                |       authors      |       isbn      |
      | Harry Potter and the Goblet of Fire |    J.K. Rowling    |  O-7A75-A6ZA-X  |
      |        A Dance with Dragons         | George R.R. Martin |  978-0553801471 |
      |          A Feast for Crows          | George R.R. Martin |  0-00-224743-1  |