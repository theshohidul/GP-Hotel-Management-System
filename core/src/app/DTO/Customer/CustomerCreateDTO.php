<?php
namespace App\DTO\Customer;

use App\DTO\AbstractDTO;
use Webmozart\Assert\Assert;

class CustomerCreateDTO extends AbstractDTO
{
    public string $first_name;
    public string $last_name;
    public string $email;
    public string $phone;
    public string $password;

    public function __construct(array $parameters = [])
    {
        parent::__construct($parameters);
        Assert::email($this->email);
        Assert::stringNotEmpty($this->first_name);
        Assert::stringNotEmpty($this->last_name);
        Assert::stringNotEmpty($this->phone);
        Assert::stringNotEmpty($this->password);
    }

}