@insulated
Feature: Add new articles
As an admin
I want to add new article
So that I can give extra information to the user

	Background:
		Given I am logged in as an admin

	Scenario: redirect to the New Article page
		Given I goto Add a New Article
		And I should be redirected to the page with the title "New Article"

	Scenario Outline: Add Articles using date before 2070 and after 2070
		Given I am on the New Article page
		And I fill in the following details
		|title  |summary  |content  |date  |
		|<title>|<summary>|<content>|<date>|
		And I save the changes
		Then I should be redirected to the page with the title "All Articles"
		And the article with the title "<title>" and date "<displaydate>" should be listed
		And a message should be displayed with the text "Your changes have been saved."
		Examples:
		|title|summary|content      |date       |displaydate|
		|test234|ram kills rawan|main characters are ram, laxman, sita and ravan|02/03/1971|3 Feb 1971|
		| Flood|Terai  |10 people Died | 01/01/1969 |1 Jan 1969 |

