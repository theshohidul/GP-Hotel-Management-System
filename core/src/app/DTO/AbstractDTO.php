<?php

namespace App\DTO;

abstract class AbstractDTO
{
    public function __construct(array $parameters = [])
    {
        $class = new \ReflectionClass(static::class);

        foreach ($class->getProperties(\ReflectionProperty::IS_PUBLIC) as $reflectionProperty) {
            $property = $reflectionProperty->getName();
            if (isset($parameters[$property]))
            {
                $this->{$property} = $parameters[$property];
            }else
            {
                $this->{$property} = NULL;
            }
        }
    }

}