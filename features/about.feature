Feature: home page
  In order to see the home page contents As a user
  I am able to visit home page

  @javascript
  Scenario: Visiting home page
    Given I am on "/"
    Then I should see "Drupal to Symfony"

#  Scenario: Visiting home page for an existing user Given I am on "/about/john"
#    Then I should see "He is a cool guy"
#
#  Scenario: Visiting about page for non existing user Given I am on "/about/jim"
#    Then I should see "Not Found"