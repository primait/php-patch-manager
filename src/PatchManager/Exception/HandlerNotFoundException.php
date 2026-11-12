<?php

declare(strict_types=1);

namespace PrimaAssicurazioni\PatchManager\Exception;

use PhpCollection\AbstractSequence;

class HandlerNotFoundException extends PatchManagerException
{
    public function __construct(AbstractSequence $unmatchedOperations)
    {
        $message = 'No handler was found ';
        if ($unmatchedOperations->count() > 1) {
            $message .= 'for the operators \''.implode(', ', $unmatchedOperations->all()).'\'. ';
        } else {
            $message .= 'for the operator \''.$unmatchedOperations->first()->get().'\'. ';
        }
        $message .= 'This is not allowed with strict_mode enabled';
        parent::__construct($message);
    }
}
