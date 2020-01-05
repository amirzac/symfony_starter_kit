<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\SignUp\Request;

use App\Model\User\Entity\Role;
use App\Model\User\Entity\User;
use App\Model\User\Entity\Id;
use App\Model\User\Entity\Email;
use App\Model\User\Entity\UserRepository;
use App\Model\User\Service\ConfirmTokenizer;
use App\Model\User\Service\ConfirmTokenSender;
use App\Model\User\Service\PasswordHasher;
use App\Model\Flusher;

class Handler
{
    private $users;

    private $hasher;

    private $flusher;

    private $tokenizer;

    private $sender;

    public function __construct(
        UserRepository $users,
        PasswordHasher $hasher,
        Flusher $flusher,
        ConfirmTokenizer $tokenizer,
        ConfirmTokenSender $sender
    )
    {
        $this->users = $users;

        $this->hasher = $hasher;

        $this->flusher = $flusher;

        $this->tokenizer = $tokenizer;

        $this->sender = $sender;
    }

    public function handle(Command $command):void
    {
        $email = new Email($command->email);

        if($this->users->hasByEmail($email)) {
            throw new \DomainException('User already exists');
        }

        $user = new User(
            Id::next(),
            new \DateTimeImmutable(),
            $email, Role::user(),
            $this->hasher->hash($command->password),
            $token = $this->tokenizer->generate()
        );

        $this->users->add($user);

        $this->sender->send($email, $token);

        $this->flusher->flush();
    }
}

