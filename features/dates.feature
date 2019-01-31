Feature: Dates intervals
  In order to see intervals into reports
  As a user
  I need to be able to get intervals as needed

  Rules:
  - Intervals can be year week, month week, quarter weeks
  - I can check if a state represents an end in a lifecycle

  Background:
    Given there is the following dates:
      | Label     | Date       |
      | January01 | 2019-01-01 |
      | January31 | 2019-01-31 |
      | WON       | 2019-01-01 |
      | LOST      | 2019-01-01 |
      | LOST      | 2019-01-01 |
      | LOST      | 2019-01-01 |
      | LOST      | 2019-01-01 |

  Scenario: Get weeks in month by date
    Given I have a
    When I change its state to DEALING
    Then sales must be in DEALING state

  Scenario: Get week by year week and year month on December's last week
    Given There is a sale in LOST state
    When I change its state to DEALING
    Then sales must be in LOST state
    And I should receive an error message

  Scenario: Get week by year week and year month on January's first week
    Given There is a sale in LOST state
    When I change its state to DEALING
    Then sales must be in LOST state
    And I should receive an error message

  Scenario: Get weeks by quarter
    Given There is a sale in DEALING state
    When I check its state
    Then I should see it is not finished