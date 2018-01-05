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
     * @Then a notification should be displayed with the text :notification
     */
    public function aNotificationShouldBeDisplayedWithTheText($notification)
    {
        $realNotification = $this->getSession()->getPage()->find('xpath', '//div[@class="errorMessage"]');
        if ($realNotification->getHtml()!=$notification) {
            throw new \Exception("notification does not match the expected");
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
     foreach($table as $row){
         var_dump($row);
     }
    }

    /**
     * @Given I save the changes
     */
    public function iSaveTheChanges()
    {
        throw new PendingException();
    }

    /**
     * @Then the article should be listed
     */
    public function theArticleShouldBeListed()
    {
        throw new PendingException();
    }

    

    /**
     * @Given I am on the New Article page
     */
    public function iAmOnThePage()
    {
        $this->getSession()->getPage()->find('xpath','//a[@href="admin.php?action=newArticle"]')->click();
        sleep(5);
    }
}
