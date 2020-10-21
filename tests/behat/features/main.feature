Feature: To test investment process
  I should loan with tranches and try to invest so money as Investor
  Customers should be able to
  buy coffee at all times

  Scenario: Investment successful
    Given there is a loan 'Main' from '01/10/2020' till '15/11/2020'
    And to loan 'Main' assigned tranches:
      | id | rate | amount |
      | A  | 3    | 1000   |
      | B  | 6    | 1000   |
    And there is investor "1" with 1000 in wallet
    When investor '1' at '03/10/2020' invest 1000 to 'A' tranche of 'Main' loan
    Then paid amount of 'A' tranche of 'Main' loan should be 1000
    When run interest processing for investor '1' on '01/11/2020'
    Then in investor '1' wallet should be 28.06

    And there is investor "2" with 1000 in wallet
    When investor '2' at '04/10/2020' invest 1000 to 'A' tranche of 'Main' loan
    Then payment should be accepted with error 'Amount not accepted'
    And  paid amount of 'A' tranche of 'Main' loan should be 1000
    When run interest processing for investor '2' on '01/11/2020'
    Then in investor '2' wallet should be 1000

    And there is investor '3' with 1000 in wallet
    When investor '3' at '10/10/2020' invest 500 to 'B' tranche of 'Main' loan
    Then paid amount of 'B' tranche of 'Main' loan should be 500
    When run interest processing for investor '3' on '01/11/2020'
    Then in investor '3' wallet should be 521.29

    And there is investor '4' with 1000 in wallet
    When investor '2' at '25/10/2020' invest 1100 to 'A' tranche of 'Main' loan
    Then payment should be accepted with error 'Investor does not have enough money'
    And  paid amount of 'B' tranche of 'Main' loan should be 500
    When run interest processing for investor '4' on '01/11/2020'
    Then in investor '4' wallet should be 1000

  Scenario: Investor invest in few tranches
    Given there is a loan 'Main' from '01/10/2020' till '15/11/2020'
    And to loan 'Main' assigned tranches:
      | id | rate | amount |
      | A  | 3    | 1000   |
      | B  | 6    | 1000   |
    And there is investor '1' with 1500 in wallet
    When investor '1' at '03/10/2020' invest 1000 to 'A' tranche of 'Main' loan
    Then paid amount of 'A' tranche of 'Main' loan should be 1000
    When investor '1' at '10/10/2020' invest 500 to 'B' tranche of 'Main' loan
    Then paid amount of 'B' tranche of 'Main' loan should be 500

    When run interest processing for investor '1' on '01/11/2020'
    Then in investor '1' wallet should be 49.35
