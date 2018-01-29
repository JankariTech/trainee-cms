<?php
/**
 * JankariTech
 * 
 * @author sushil <sushilkc1997@gmail.com>
 */
namespace Page;

use Page\CMSPage;

/**
 * PageObject for the LoginPage
 * 
 * @author sushil <sushilkc1997@gmail.com>
 *
 */
class LoginPage extends CMSPage {
	protected $loginBtnXpath = '//div/input[@name="login"]';
	protected $userInputBoxName = "username";
	protected $passwordInputBoxName = "password";
	protected $errorMessageXpath = '//div[@class="errorMessage"]';
	protected $path = '/admin.php';
	protected $xpathOfStatusMessage = '//div[@class="statusMessage"]';
	
	/**
	 *
	 * @param string $username
	 *
	 * @return void
	 */
	function typeUserName($username) {
		$this->fillField($this->userInputBoxName, $username);
	}
	
	/**
	 * 
	 * @param string $password
	 * 
	 * @return void
	 */
	function typePassword($password) {
		$this->fillField($this->passwordInputBoxName, $password);
	}
	
	/**
	 * @return void
	 */
	function login() {
		$this->find('xpath', $this->loginBtnXpath)->click();
	}
	
	/**
	 * 
	 * @param string $username
	 * @param string $password
	 * 
	 * @return void
	 */
	function loginAs($username, $password) {
		$this->typeUserName($username);
		$this->typePassword($password);
		$this->login();
	}
	
	/**
	 * 
	 * @return string
	 */
	function getErrorMessage() {
		$realErrorMessage = $this->find('xpath', $this->errorMessageXpath);
		return $realErrorMessage->getHtml();
	}
	
	/**
	 *
	 * @return string
	 */
	function getStatusMessage() {
		$realStatusMessage = $this->find('xpath', $this->xpathOfStatusMessage);
		return $realStatusMessage->getHtml();
	}
}