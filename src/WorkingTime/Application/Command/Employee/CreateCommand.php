<?php

declare(strict_types=1);

namespace App\WorkingTime\Application\Command\Employee;

use Symfony\Component\Validator\Constraints as Assert;

class CreateCommand
{
    #[Assert\NotBlank]
    #[Assert\Uuid]
    public string $uuid;

    #[Assert\NotBlank]
    #[Assert\Length(max: 32)]
    public string $firstname;

    #[Assert\NotBlank]
    #[Assert\Length(max: 32)]
    public string $lastname;
}
