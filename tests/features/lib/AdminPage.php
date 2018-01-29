<?php
/**
 * JankariTech
 *
 * @author Trainee <jankaritech@gmail.com>
 */
namespace Page;

use Page\CMSPage;

/**
 * PageObject for the AdminPage
 *
 * @author Trainee <jankaritech@gmail.com>
 *
 */
class AdminPage extends CMSPage{
	
	protected $xpathOfAddNewArticle = '//a[@href="admin.php?action=newArticle"]';
	protected $xpathOfOpenArticle = '//td[text()[normalize-space() = "%s"]]';
	protected $xpathOfTitle = '//tr/td[2][text()[normalize-space() = "%s"]]';
	protected $xpathOfDate = '//tr/td[1][text()[normalize-space() = "%s"]]';
	
	/**
	 * @return void
	 */
	function addNewArticle() {
		$this->find('xpath', $this->xpathOfAddNewArticle)->click();
	}
	
	/**
	 * 
	 * @param string $title
	 * 
	 * @return void
	 */
	function openArticle($title) {
		$titleof = sprintf($this->xpathOfOpenArticle, $title);
		$this->find("xpath", $titleof)->click();
		
	}
	/**
	 * 
	 * @param string $title
	 * @return NULL|string
	 */
	function getTitle($title) {
		$articleTitle = sprintf($this->xpathOfTitle, $title);
		$titleElement = ($this->find('xpath', $articleTitle));
		if ($titleElement === null) {
			return null;
		} else {
			return trim($titleElement->getHtml());
		}
	}
		
	/**
	 * 
	 * @param string $date
	 * @return NULL|string
	 */
	function getDate($date) {
		$articleDate = sprintf($this->xpathOfDate, $date);
		$dateElement = $this->find('xpath', $articleDate);
		if ($dateElement === null) {
			return null;
		} else {
			return trim($dateElement->getHtml());
		}
	}
} 