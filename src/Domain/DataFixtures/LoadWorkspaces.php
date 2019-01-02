<?php

namespace App\Domain\DataFixtures;


use App\Domain\Entity\Workspace;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadWorkspaces extends Fixture implements OrderedFixtureInterface {

  /**
   * @inheritDoc
   */
  public function load(ObjectManager $manager) {
    $workspace1 = new Workspace();
    $workspace1->setName('Writing');
    $workspace1->setDescription('Info for writing Workspace');

    $manager->persist($workspace1);
    $manager->flush();

    $this->addReference('workspace-writing', $workspace1);
  }

  /**
   * @inheritDoc
   */
  public function getOrder() {
    return 10;
  }

}
