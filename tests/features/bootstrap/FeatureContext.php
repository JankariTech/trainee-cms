<?php

use Behat\Gherkin\Node\TableNode;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\MinkExtension\Context\RawMinkContext;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends RawMinkContext implements Context 
{
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    }

    /**
     * @Given I am on the login page
     */
    public function iAmOnTheLoginPage()
    {
        $this->visitPath("/admin.php");
    }

    /**
     * @When I login with username :user and password :passwd
     */
    public function iLoginWithUsernameAndPassword($user, $passwd)
    {
        $page=$this->getSession()->getPage();
        $page->fillField("username", $user);
        $page->fillField("password", $passwd);
        $page->find('xpath', '//div/input[@name="login"]')->click();
    }

    /**
     * @Then I should be redirected to the page with the title :title
     */
    public function iShouldBeRedirectedToThePageWithTheTitle($title)
    {
        $titleElement = $this->getSession()->getPage()->find('xpath', '//title');
        if ($titleElement->getHtml()!=$title) {
            throw new \Exception("title does not match the expected");
        }
       }

    /**
     * @Then an error message should be displayed with the text :errorMessage
     */
    public function anErrorMessageShouldBeDisplayedWithTheText($errorMessage)
    {
        $realErrorMessage = $this->getSession()->getPage()->find('xpath', '//div[@class="errorMessage"]');
        if ($realErrorMessage->getHtml()!=$errorMessage) {
            throw new \Exception("error message does not match the expected");
        }
    }
    /**
     * @Given I am logged in as an admin
     */
    public function iAmLoggedInAsAnAdmin()
    {   $this->visitPath("/admin.php");
        $this->iLoginWithUsernameAndPassword("admin","mypass");
    }
    /**
     * @When I goto Add a New Article
     */
    public function iGotoAddaNewArticle()
    {
        $this->getSession()->getPage()->find('xpath','//a[@href="admin.php?action=newArticle"]')->click();
    }

    /**
     * @Given I fill in the following details
     */
    public function iFillTheFollowingDetails(TableNode $table)
    {
        $page=$this->getSession()->getPage();
        $tableArray = $table->getColumnsHash();
        $page->fillField("title",$tableArray[0]["title"]);
        $page->fillField("summary", $tableArray[0]["summary"]);
        $page->fillField("content", $tableArray[0]["content"]);
        $page->fillField("publicationDate", $tableArray[0]["date"]);
    }
    /**
     * @Given I save the changes
     */
    public function iSaveTheChanges()
    {
     $page=$this->getSession()->getPage();
     $page->find('xpath', '//div/input[@name="saveChanges"]')->click();
     }
    /**
     * @Given I am on the New Article page
     */
    public function iAmOnTheNewArticlePage()
    {
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
     * @Then the article with the title :title should be listed
     */
    public function theArticleShouldBeListed($title)
    {
        $titleElement = $this->getSession()->getPage()->find('xpath', '//td[text()[normalize-space()="'.$title.'"]]');
        if($titleElement === NULL)
        {
            throw new \Exception("Article does not exist");
        }

    }

    /**
     * @When I open the article with the title :title
     */
    public function iOpenTheArticleWithTheTitle($title)
    {
       $this->getSession()->getPage()->find("xpath",'//td[text()[normalize-space() = "'.$title.'"]]')->click();
    }
    
    /**
     * @Then I click the delete link
     */
    public function iClickTheDeleteLink()
    {
        $this->getSession()->getPage()->find('xpath','//a[contains(text(),"Delete")]')->click();
    }
    /**
     * @Then A message box must appear with the message :comment
     */
    public function aMessageBoxMustAppearWithTheMessage($comment)
    {
        $message = $this->getSession()->getDriver()->getWebDriverSession()->getAlert_text();
        if ($message!==$comment) {
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
     */
    public function aMessageShouldBeDisplayedWithTheText($message)
    {
        $realMessage = $this->getSession()->getPage()->find('xpath', '//div[@class="statusMessage"]');
        if ($realMessage->getHtml()!==$message) {
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
