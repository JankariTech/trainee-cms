@insulated
Feature: Add new articles
As an admin
I want to add new article
So that I can give extra information to the user

	Scenario: redirect to the New Article page
		Given I am logged in as an admin
		When I goto Add a New Article
		Then I should be redirected to the page with the title "New Article"

	Scenario: Adding a new article
		Given I am logged in as an admin
		And I am on the New Article page
		And I fill in the following details
		|title|summary|content|date|
		|mahavarat|ram kills rawan|main characters are ram, laxman, sita and ravan|12/02/1960|
		And I save the changes
		Then I should be redirected to the page with the title "All Articles"
		And the article should be listed
		And a notification should be displayed with the text "Your changes have been saved."