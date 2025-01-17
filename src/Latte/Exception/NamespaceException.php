<?php

namespace Exception;

class NamespaceException extends \Exception
{

    public static function invalidNamespaceTemplate(string $namespace_template): self
    {
        return new self(sprintf(
            'Invalid template parameter provided "%s" as it does not follow the naming convention "namespace::template" (namespace:: optional)',
            $namespace_template
        ));
    }
}