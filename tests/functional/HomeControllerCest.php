<?php namespace App\Tests;
use App\Tests\FunctionalTester;

class HomeControllerCest
{
    public function _before(FunctionalTester $I)
    {
    }

    // tests
    public function siteNameTest(FunctionalTester $I)
    {
        $I->wantTo('see the site name on the home page');
        $I->amOnPage('/');
        $I->see('Drupal to Symfony');
    }

    public function languageSwitcherTest(FunctionalTester $I)
    {
        $I->wantTo('see the Dutch language switcher on the home page');
        $I->amOnPage('/');
        $I->seeLink('NL', '/nl');
    }
}
