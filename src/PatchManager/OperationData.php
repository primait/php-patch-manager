<?php

declare(strict_types=1);

namespace PrimaAssicurazioni\PatchManager;

use PhpCollection\Map;
use PhpCollection\Sequence;
use PhpOption\Option;
use PrimaAssicurazioni\PatchManager\Request\Operations;

class OperationData extends Map
{
    /**
     * @return Option<string>
     */
    public function getOp(): Option
    {
        return $this->get(Operations::OP_KEY_NAME);
    }

    public function getData(): Map
    {
        $operationData = new Map($this->elements);
        if ($operationData->containsKey(Operations::OP_KEY_NAME)) {
            $operationData->remove(Operations::OP_KEY_NAME);
        }

        return $operationData;
    }

    /**
     * @param list<string> $keys
     */
    public function diffKeys(array $keys): Sequence
    {
        $filtered = array_filter(
            $this->getData()->keys(),
            static fn ($key) => !in_array($key, $keys)
        );

        return new Sequence($filtered);
    }
}
