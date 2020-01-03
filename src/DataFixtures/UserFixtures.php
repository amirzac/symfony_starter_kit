<?php

namespace App\DataFixtures;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;

class UserFixtures extends BaseFixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder){
        $this->passwordEncoder = $passwordEncoder;
    }

    public function loadData(ObjectManager $manager)
    {
        $this->createMany(10, 'simple_users', function($i) use ($manager) {
            $user = new User();
            $user->setPassword($this->passwordEncoder->encodePassword($user, 'secret'));
            $user->setEmail($this->faker->email);
            return $user;
        });

        $this->createMany(3, 'admin_users', function($i) {
            $user = new User();
            $user->setEmail(sprintf('admin%d@user.com', $i));
            $user->setPassword($this->passwordEncoder->encodePassword($user, 'secret'));
            $user->setRoles([
                'ROLE_ADMIN'
            ]);
            return $user;
        });

        $manager->flush();
    }
}
