<?php

namespace App\Domain\DataFixtures;


use App\Infrastructure\Component\Utility\Random;
use App\Domain\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Persistence\ObjectManager;
use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\ServiceSubscriberInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class LoadUsers extends Fixture implements ServiceSubscriberInterface, OrderedFixtureInterface
{

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     *
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
//        $roles = $this->container->get('doctrine')->getRepository(User::class)->findAll();
//        for ($i = 0; $i < 20; $i++) {
//
////            $roleKey = array_rand($roles, 1);
//
//            $user = new User();
//            $string = $this->container->get(Random::class)->word(7);
//            $user->setUsername($string);
//            $user->setEmail("{$string}@hotmail.com");
//            $password = $this->container->get(UserPasswordEncoderInterface::class)->encodePassword($user, md5('welkom'));
//            $user->setPassword($password);
////            $user->addRole($roles[$roleKey]);
//
//            $manager->persist($user);
//
//        }

        // Add extra users specifically for the purpose of testing.
      $user1 = new User();
      $user1->setUsername('John');
      $user1->setEmail('john@hotmail.com');
      $password = $this->container->get(UserPasswordEncoderInterface::class)->encodePassword($user1, md5('welkom'));
      $user1->setPassword($password);
      $manager->persist($user1);

      $user2 = new User();
      $user2->setUsername('Jack');
      $user2->setEmail('jack@hotmail.com');
      $password = $this->container->get(UserPasswordEncoderInterface::class)->encodePassword($user2, md5('welkom'));
      $user2->setPassword($password);
      $manager->persist($user2);

      $manager->flush();

      $this->addReference('user-john', $user1);
      $this->addReference('user-jack', $user2);
    }

    public static function getSubscribedServices()
    {
        return array(
            'doctrine' => ManagerRegistry::class,
            UserPasswordEncoderInterface::class => UserPasswordEncoderInterface::class,
            Random::class => Random::class,
        );
    }

  /**
   * Get the order of this fixture
   *
   * @return integer
   */
  public function getOrder() {
    return 30;
  }
}
