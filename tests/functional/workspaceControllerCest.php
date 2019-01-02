<?php namespace App\Tests;
use App\Tests\FunctionalTester;

class workspaceControllerCest
{
    public function _before(FunctionalTester $I)
    {
    }

    // tests
    public function tryToTest(FunctionalTester $I)
    {
    }

    public function testShowAction(FunctionalTester $I) {
      $I->wantTo('too see inside the "writing" workspace');
      $I->amOnPage('/workspace/writing');
      $I->see('Symfony book');
    }
}
