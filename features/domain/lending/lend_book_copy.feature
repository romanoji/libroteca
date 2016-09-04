Feature: Lending book copy
  In order to give readers opportunity to read books from library
  As a librarian
  I need to be able to lend books copies to readers

  Remarks:
#    - reader can borrow up to 5 books copies at the same time
    - loan period upper limit is 60 days (2 months)
    - loan period is 30 days by default.

  Background:
    Given there is reader with email "john.kowalsky@mail.com", name "John", surname "Kowalsky" and phone "123456789"
    And there are registered a books copies:
      |                    title                 |       authors      |       isbn      | copies |
      | Harry Potter and the Prisoner of Azkaban |    J.K. Rowling    |  9788478886555  |    1   |
      |    Harry Potter and the Goblet of Fire   |    J.K. Rowling    |  0-7475-4624-X  |    2   |
      |            A Game of Thrones             | George R.R. Martin |    0553573403   |    3   |
      |            A storm of swords             | George R.R. Martin |    0553106635   |    2   |
      |            A Feast for Crows             | George R.R. Martin |  0-00-224743-1  |    2   |
      |          A Dance with Dragons            | George R.R. Martin |  978-0553801477 |    3   |

  Scenario: Lending a book copy to reader for a month
    When I lend book copy with ISBN "978-0553801477" to the reader with email "john.kowalsky@mail.com" for 30 days
    Then reader with email "john.kowalsky@mail.com" should have that book copy lent for 30 days
    And there should be 2 book copies of ISBN "978-0553801477" available for loan

  Scenario: Trying to lend already lent book copy
    Given there is reader with email "andy.novak@mail.com", name "Andy", surname "Novak" and phone "135792468"
    And there is a loan for a book of ISBN "0-00-224743-1" to reader with email "andy.novak@mail.com" for 30 days
    When I try to lend the same book copy to the reader with email "john.kowalsky@mail.com" for 30 days
    Then I should be notified that book copy is already lent
    And the book loan should not be successful
    And there should be 1 book copy of ISBN "0-00-224743-1" available for loan

  Scenario: Trying to lend a book copy to the reader with exceeded time to return previously lent books
    Given there is a loan for a book of ISBN "0-00-224743-1" to reader with email "john.kowalsky@mail.com" till "yesterday"
    When I lend book copy with ISBN "978-0553801477" to the reader with email "john.kowalsky@mail.com" for 30 days
    Then I should be notified that reader must return books first to proceed with loan attempt
    And the book loan should not be successful
    And there should be 3 book copies of ISBN "978-0553801477" available for loan

  Scenario: Trying to lend a book copy to a reader for 3 months
    When I lend book copy with ISBN "978-0553801477" to the reader with email "john.kowalsky@mail.com" for 90 days
    Then I should be notified that loan period can last at most for 60 days
    And the book loan should not be successful
    And there should be 3 book copies of ISBN "978-0553801477" available for loan

#  Scenario: Trying to lend too many book copies to the reader
#    When I lend books copies to reader with email "john.kowalsky@mail.com":
#      |       isbn      |  for N days  |
#      |  9788478886555  |      30      |
#      |  0-7475-4624-X  |      60      |
#      |    0553573403   |      60      |
#      |    0553106635   |      60      |
#      |  0-00-224743-1  |      60      |
#      |  978-0553801477 |      60      |

#  Scenario: Setting book copy as unavailable for loan
#
#  Scenario: Trying to lend a book copy unavailable for loan
#
#  Scenario: Trying to set already lent book copy as unavailable for loan
