Feature: Lending book
  In order to give readers opportunity to read books from library
  As a librarian
  I need to be able to lend books to readers

  Loan period options:
    - 2 weeks (14 days)
    - a month (30 days) (default)

  Background:
    Given there is reader with email "john.kowalsky@mail.com"
    And there are registered a books with copies:
      |         title        |           subtitle          |        author      |       isbn      | copies |
      |     Harry Potter     | and the Prisoner of Azkaban |    J.K. Rowling    |  9788478886555  |    1   |
      |     Harry Potter     |   and the Goblet of Fire    |    J.K. Rowling    |  0-7475-4624-X  |    2   |
      |   A Game of Thrones  |                             | George R.R. Martin |    0553573403   |    3   |
      |   A storm of swords  |                             | George R.R. Martin |    0553106635   |    2   |
      |   A Feast for Crows  |                             | George R.R. Martin |  0-00-224743-1  |    2   |
      | A Dance with Dragons |                             | George R.R. Martin |  978-0553801477 |    3   |

  Scenario: Lending a book copy to reader for a month
    When I lend book copy with ISBN "978-0553801477" to reader with email "john.kowalsky@mail.com" for 30 days
    Then reader with email "john.kowalsky@mail.com" should have that book copy lended for 30 days
    And there should be 2 book copies of ISBN "978-0553801477" available for loan

  Scenario: Trying to lend already borrowed book copy
    Given there is reader with email "andy.novak@mail.com"
    And there is a loan for a book of ISBN "0-00-224743-1" to reader with email "andy.novak@mail.com" for 30 days
    When I try to lend the same book copy to the reader with email "john.kowalsky@mail.com" for 30 days
    Then I should be notified that book copy is already borrowed
    And the book loan should not be successful
    And there should be 1 book copy of ISBN "0-00-224743-1" available for loan

  Scenario: Trying to lend a book copy to the reader with exceeded time to return previously borrowed books
    Given there is a expired loan for a book of ISBN "0-00-224743-1" to reader with email "john.kowalsky@mail.com"
    When I try to lend book copy with ISBN "978-0553801477" to the reader with email "john.kowalsky@mail.com" for 30 days
    Then I should be notified that reader has unreturned books and he/she must return them first to proceed with loan attempt
    And the book loan should not be successful
    And there should be 3 book copies of ISBN "978-0553801477" available for loan

#  Scenario: Trying to lend another copy of the same book to the reader
#    Given there is a loan for a book of ISBN "978-0553801477" to reader with email "john.kowalsky@mail.com"
#    When I try to lend other book copy with ISBN "978-0553801477" to the reader with email "john.kowalsky@mail.com" for 30 days
#    Then I should be notified that reader has that book borrowed
#    And the book loan should not be successful
#    And there should be 3 book copies of ISBN "978-0553801477" available for loan

    # TODO
#  Scenario: Trying to lend too many (6) book copies to the reader