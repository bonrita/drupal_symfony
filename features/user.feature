Feature: user pages
  In order to see information about users
  I am able to visit user pages

  Scenario: Viewing a user's home page
    Given I am on "/user/1"
    When I follow "Edit"
    Then I should see "Email"