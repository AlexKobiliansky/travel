<?php
namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use AppBundle\Entity\User;
use Faker;

class UserFixtures extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        /**
         * i - number of users created
         */
        for ($i=1; $i<=10; $i++) {
            $faker = Faker\Factory::create();

            $user = new User();
            $user->setLogin($faker->unique()->userName);
            $user->setPassword($faker->password);
            $user->setName($faker->firstName);
            $user->setSurname($faker->lastName);
            $user->setEmail($faker->email);
            $user->setPhone($faker->phoneNumber);
            $user->setAddress($faker->streetAddress);
            $user->setAvatar("avatar{$i}.jpg");
            $user->setDateOfBirth($faker->dateTimeThisYear);

            $manager->persist($user);
            $this->addReference("user-{$i}", $user);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }
}
