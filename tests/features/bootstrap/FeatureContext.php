<?php

use Behat\Gherkin\Node\TableNode;
use Behat\Behat\Context\Context;
use Behat\MinkExtension\Context\RawMinkContext;
use Page\LoginPage;
use Page\ArticlePage;
use Page\EditArticlePage;
use Page\CMSPage;
use Page\AdminPage;

require_once 'bootstrap.php';

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends RawMinkContext implements Context {
	protected $loginPage;
	
	/**
	 * 
	 * @var ArticlePage $articlePage
	 */
	protected $articlePage;
	protected $editPage;
	protected $adminPage;
	/**
	 * Initializes context.
	 *
	 * Every scenario gets its own context instance.
	 * You can also pass arbitrary arguments to the
	 * context constructor through behat.yml.
	 *
	 * @param LoginPage $loginPage
	 * @param ArticlePage $articlePage
	 * @param EditArticlePage $editPage
	 * @param AdminPage $adminPage
	 * @return void
	 */
	public function __construct(LoginPage $loginPage, ArticlePage $articlePage, EditArticlePage $editPage, AdminPage $adminPage) {
		$this->loginPage = $loginPage;
		$this->articlePage = $articlePage;
		$this->editPage = $editPage;
		$this->adminPage = $adminPage;
	}

	/**
	 * @Given I am on the login page
	 * 
	 * @return void
	 */
	public function iAmOnTheLoginPage() {
		$this->loginPage->open();
	}
	
	/**
	 * @When I login with username :user and password :passwd
	 * 
	 * @param string $user
	 * @param string $passwd
	 * @return void
	 */
	public function iLoginWithUsernameAndPassword($user, $passwd) {
		$this->loginPage->loginAs($user, $passwd);
	}

	/**
	 * @Then I should be redirected to the page with the title :title
	 * 
	 * @param string $title
	 * 
	 * @return void
	 */
	public function iShouldBeRedirectedToThePageWithTheTitle($title) {
		$titleElement = $this->articlePage->getPageTitle();
		if ($titleElement->getHtml() != $title) {
			throw new \Exception("title does not match the expected");
		}
	}
	
	/**
	 * @Then an error message should be displayed with the text :errorMessage
	 * 
	 * @param string $errorMessage
	 * @return void
	 */
	public function anErrorMessageShouldBeDisplayedWithTheText($errorMessage) {
		$error = $this->loginPage->getErrorMessage();
		if ($error !== $errorMessage) {
			throw new \Exception("error message does not match the expected");
		}
	}

	/**
	 * @Given I am logged in as an admin
	 * 
	 * @return void
	 */
	public function iAmLoggedInAsAnAdmin() {
		$this->loginPage->open();
		$this->iLoginWithUsernameAndPassword("admin", "mypass");
	}
	/**
	 * @When I goto Add a New Article
	 * 
	 * @return void
	 */
	public function iGotoAddaNewArticle() {
		$this->adminPage->addNewArticle();
	}
	
	/**
	 * @Given I fill in the following details
	 * 
	 * @param TableNode $table
	 * @return void
	 */
	public function iFillTheFollowingDetails(TableNode $table) {
		$tableArray = $table->getColumnsHash();
		$this->articlePage->setTitle($tableArray[0]["title"]);
		$this->articlePage->setSummary($tableArray[0]["summary"]);
		$this->articlePage->setContent($tableArray[0]["content"]);
		$this->articlePage->setDate($tableArray[0]["date"]);
	}
	/**
	 * @Given I save the changes
	 * 
	 * @return void
	 */
	public function iSaveTheChanges() {
		$this->articlePage->save();
	}
	/**
	 * @Given I am on the New Article page
	 * 
	 * @return void
	 */
	public function iAmOnTheNewArticlePage() {
		$this->visitPath("/admin.php?action=newArticle");
	}

    /**
     * @Then a notification should be displayed with the text :notification
     */
    public function aNotificationShouldBeDisplayedWithTheText($notification)
    {
    	$realNotification = $this->getSession()->getPage()->find('xpath', '//div[@class="statusMessage"]');
    	if ($realNotification->getHtml()!=$notification) {
    		throw new \Exception("notification does not match the expected");
    	}
    }

	/**
	 * @Then the article with the title :title and date :date should be listed
	 */
	public function theArticleShouldBeListed($title, $date) {
		$titleElement = $this->adminPage->getTitle ($title);
		$dateElement = $this->adminPage->getDate ($date);
		if ($dateElement === NULL || $dateElement !== $date) {
			throw new \Exception ("Expected article with date does not exist");
		}
		if ($titleElement === NULL || $titleElement !== $title) {
			throw new \Exception ("Expected article with title does not exist");
		}
	}

	/**
	 * @When I open the article with the title :title
	 * 
	 * @param string $title
	 * 
	 * @return void
	 */
	public function iOpenTheArticleWithTheTitle($title) {
		$this->adminPage->openArticle($title);
	}

	/**
	 * @Then I click the delete link
	 * 
	 * @return void
	 */
	public function iClickTheDeleteLink() {
		$this->editPage->deleteArticle();
	}

	/**
	 * @Then A message box must appear with the message :comment
	 *
	 * @param string $comment
	 *
	 * @return void
	 */
	public function aMessageBoxMustAppearWithTheMessage($comment) {
		$message = $this->editPage->getAlertMessage();
		if ($message !== $comment) {
			throw new \Exception("message does not match the expected");
		}
	}
	
    /**
     * @When I confirm the delete action
     */
    public function iConfirmTheDeleteAction()
    {
        $this->getSession()->getDriver()->getWebDriverSession()->accept_alert();
    }

	/**
	 * @Then a message should be displayed with the text :message
	 * 
	 * @param string $message
	 * 
	 * @return void
	 */
	public function aMessageShouldBeDisplayedWithTheText($message) {
		$realMessage = $this->loginPage->getStatusMessage();
		if ($realMessage !== $message) {
			throw new \Exception("notification does not match the expected");
		}
	}
    /**
     * @Then the article with the title :title should not be listed
     */
    public function theArticleWithTheTitleShouldNotBeListed($title)
    {
       $titleField=$this->getSession()->getPage()->find("xpath",'//td[text()[normalize-space() = "'.$title.'"]]');
       if ($titleField!==null) {
           throw new \Exception("The article with the $title is not supposed to be listed but is listed");
       }
    }
    /**
     * @Given an article with the following details exists
     */
    public function anArticleWithTheFollowingDetailsExists(TableNode $table)
    {
        $this->iAmOnTheNewArticlePage();
        $this->iFillTheFollowingDetails($table);
        $this->iSaveTheChanges();
    }
}
