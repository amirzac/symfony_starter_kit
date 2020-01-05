<?php

namespace App\DataFixtures;

use App\Model\User\Entity\Email;
use App\Model\User\Entity\Id;
use App\Model\User\Entity\Role;
use App\Model\User\Service\PasswordHasher;
use Doctrine\Common\Persistence\ObjectManager;
use App\Model\User\Entity\User;

class UserFixtures extends BaseFixture
{
    private $hasher;

    public function __construct(PasswordHasher $hasher){
        $this->hasher = $hasher;
    }

    public function loadData(ObjectManager $manager):void
    {
        $hash = $this->hasher->hash('secret');

        $this->createMany(10, 'simple_users', function($i) use ($hash) {
            $user = new User(
                Id::next(),
                new \DateTimeImmutable(),
                new Email($this->faker->email),
                Role::user(),
                $hash,
                'token'
            );

            $user->confirmSignUp();

            return $user;
        });

        $this->createMany(3, 'admin_users', function($i) use ($hash) {
            $user = new User(
                Id::next(),
                new \DateTimeImmutable(),
                new Email(sprintf("admin%s@user.com", $i)),
                Role::admin(),
                $hash,
                'token'
            );

            $user->confirmSignUp();

            return $user;
        });

        $manager->flush();
    }
}
