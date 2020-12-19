<?php

namespace App\DTO\User;

use App\DTO\AbstractDTO;
use Webmozart\Assert\Assert;

class UserCreateDTO extends AbstractDTO
{
    public string $name;
    public string $email;
    public string $password;

    public function __construct(array $parameters = [])
    {
        parent::__construct($parameters);
        Assert::email($this->email);
    }

}