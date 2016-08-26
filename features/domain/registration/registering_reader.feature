Feature: Registering reader
  In order to allow readers to borrow books from library
  As a librarian
  I need to be able to register readers in library

  Scenario: Registering a new reader in library with required information
    Given reader wants to register in library
    When I register reader by his name "John", surname "Kowalsky", email "john.kowalsky@mail.com" and phone "123456789"
    Then the reader should be registered

  Scenario: Trying to register reader with an email already in use
    Given there is reader with email "john.kowalsky@mail.com", name "John", surname "Kowalsky" and phone "123456789"
    When I try to register reader by his name "John", surname "Kowalsky", email "john.kowalsky@mail.com" and phone "987654321"
    Then I should be notified that specified email is already in use
    And the reader should not be registered

  Scenario: Trying to register reader with a phone already in use
    Given there is reader with email "john.kowalsky@mail.com", name "John", surname "Kowalsky" and phone "123456789"
    When I try to register reader by his name "John", surname "Kowalsky", email "kowalsky.john@mail.com" and phone "123456789"
    Then I should be notified that specified phone is already in use
    And the reader should not be registered

  Scenario: Trying to register reader with an email of invalid format
    Given reader wants to register in library
    When I try to register reader by his name "John", surname "Kowalsky", email "jk.@mail" and phone "987654321"
    Then I should be notified that specified email has invalid format
    And the reader should not be registered

  Scenario: Trying to register reader with a phone of invalid format
    Given reader wants to register in library
    When I try to register reader by his name "John", surname "Kowalsky", email "john.kowalsky@mail.com" and phone "I23A567B9"
    Then I should be notified that specified phone has invalid format
    And the reader should not be registered