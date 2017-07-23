Feature: Sending notifications to readers before loan expiration
  In order to remind readers of loan expiration date
  System should be able to send email notifications to them 2 days before loan expiration

  Scenario: Send notification on 2 days before an loan expiration date
    Given there is reader with email "john.kowalsky@mail.com", name "John", surname "Kowalsky" and phone "123456789"
    And there is registered a book "A Feast for Crows" of "George R.R. Martin" with ISBN "0-00-224743-1"
    And there is 1 book copy with ISBN "0-00-224743-1" available for loan
    And there is registered a book "A Dance with Dragons" of "George R.R. Martin" with ISBN "978-0553801477"
    And there is 1 book copy with ISBN "978-0553801477" available for loan
    And there is a loan for a book copy with ISBN "978-0553801477" to reader with email "john.kowalsky@mail.com" till "day after tomorrow"
    And there is a loan for a book copy with ISBN "0-00-224743-1" to reader with email "john.kowalsky@mail.com" till "day after tomorrow"
    Then the reader should be notified on email "john.kowalsky@mail.com" about loan expiration with message:
      """
      <title>
      =======
      <name>,

      Loan of book(s) that you've borrowed in our library is about to be expired.
      Please return them before <deadline>.
      Below you will find details about your current loans:
      <loans details>

      Regards,
      Your favourite <library>
      """
