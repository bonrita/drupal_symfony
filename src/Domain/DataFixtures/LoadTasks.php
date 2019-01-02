<?php

namespace App\Domain\DataFixtures;


use App\Domain\Entity\Task;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadTasks extends Fixture implements OrderedFixtureInterface {

  /**
   * Load data fixtures with the passed EntityManager
   *
   * @param ObjectManager $manager
   */
  public function load(ObjectManager $manager) {
    $task1 = new Task();
    $task1->setTitle('writing chapter 1');
    $task1->setDescription('descriptions for writing ch1');
    $task1->setDueDate(new \DateTime('2019-01-29'));
    $task1->setProject($manager->merge($this->getReference('project-symfony')));
    $task1->setUser($manager->merge($this->getReference('user-john')));

    $task2 = new Task();
    $task2->setTitle('reviewing chapter 1');
    $task2->setDescription('descriptions for reviewing ch1');
    $task2->setDueDate(new \DateTime('2019-02-16'));
    $task2->setProject($manager->merge($this->getReference('project-symfony')));
    $task2->setUser($manager->merge($this->getReference('user-jack')));

    $task3 = new Task();
    $task3->setTitle('editing chapter 1');
    $task3->setDescription('descriptions for editing ch1');
    $task3->setDueDate(new \DateTime('2019-02-18'));
    $task3->setProject($manager->merge($this->getReference('project-symfony')));
    $task3->setUser($manager->merge($this->getReference('user-jack')));

    $manager->persist($task1);
    $manager->persist($task2);
    $manager->persist($task3);
    $manager->flush();
  }

  /**
   * Get the order of this fixture
   *
   * @return integer
   */
  public function getOrder() {
    return 40;
  }

}
