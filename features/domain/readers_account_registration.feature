Feature: Reader's account registration
  In order to allow readers to borrow books from library
  As a librarian
  I need to be able to register reader's account

  Scenario: Registering a new reader's account
    Given reader wants to register an account
    When I copy down his name "John"
    And I copy down his surname "Kowalsky"
    And I copy down his email "john.kowalsky@mail.com"
    And I copy down his phone "123-456-789"
    And I register an reader's account from given data
    Then the account should be registered

  Scenario: Trying to register reader's account with an email already in use
    Given there is an account with email "john.kowalsky@mail.com"
    And with name "John" and surname "Kowalsky"
    And with an phone number "123-456-789"
    When I try to register another account with email "john.kowalsky@mail.com"
    Then I should be notified that specified email is already in use
    And the account should not be created

  Scenario: Trying to register reader's account with a phone already in use
    Given there is an account with email "john.kowalsky@mail.com"
    And with name "John" and surname "Kowalsky"
    And with an phone number "123-456-789"
    When I try to register another account with phone "123-456-789"
    Then I should be notified that specified phone is already in use
    And the account should not be created

  # TODO
#  Scenario: Trying to register reader's account with an non-existing email