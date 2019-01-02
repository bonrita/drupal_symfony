<?php namespace App\Tests;

use App\Domain\Entity\Workspace;
use App\Tests\UnitTester;
use Codeception\Test\Unit;

class WorkspaceControllerTest extends \Codeception\TestCase\Test {

  /**
   * @var \UnitTester
   */
  protected $tester;

  public function _before() {
  }

  // tests
  public function testShowAction() {
    $workspaceId = $this->tester->grabFromRepository(Workspace::class, 'id', ['name' => 'writing']);
    $tt = 0;
  }
}
