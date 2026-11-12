<?php

declare(strict_types=1);

namespace PrimaAssicurazioni\PatchManager\Request;

use PhpCollection\Sequence;
use PrimaAssicurazioni\PatchManager\Exception\InvalidJsonRequestContent;
use PrimaAssicurazioni\PatchManager\Exception\MissingOperationNameRequest;
use PrimaAssicurazioni\PatchManager\Exception\MissingOperationRequest;

class Operations
{
    public const string OP_KEY_NAME = 'op';

    private Adapter $adapter;

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * @throws InvalidJsonRequestContent
     * @throws MissingOperationNameRequest
     * @throws MissingOperationRequest
     */
    public function all(): Sequence
    {
        $operationsJson = $this->parseJson($this->adapter->getRequestBody());
        $operations = $this->toSequence($operationsJson);
        $operationsWithoutOpKey = $operations->filterNot(fn ($operationData) => array_key_exists(self::OP_KEY_NAME, $operationData));

        if (!$operationsWithoutOpKey->isEmpty()) {
            /** @var array<string, mixed> $operationData */
            $operationData = $operationsWithoutOpKey->first()->get();

            throw new MissingOperationNameRequest($operationData);
        }

        return $operations;
    }

    /**
     * @throws InvalidJsonRequestContent
     * @throws MissingOperationRequest
     * @return array<string, mixed>
     */
    private function parseJson(?string $string): array
    {
        if (is_null($string)) {
            throw new InvalidJsonRequestContent();
        }

        try {
            $json = json_decode($string, associative: true, flags: JSON_THROW_ON_ERROR);

            // we need this control because json_decode('2', true, 512, JSON_THROW_ON_ERROR) returns a valid result: int(2)
            if (!is_array($json)) {
                throw new MissingOperationRequest();
            }

            return $json;
        } catch (\JsonException $e) {
            throw new InvalidJsonRequestContent();
        }
    }

    /**
     * @param array<int|string, mixed> $operations
     */
    private function toSequence(array $operations): Sequence
    {
        $operations = $this->isAssociative($operations) ? [$operations] : $operations;

        return new Sequence($operations);
    }

    /**
     * @param array<int|string, mixed> $arr
     */
    private function isAssociative(array $arr): bool
    {
        return array_keys($arr) !== range(0, count($arr) - 1);
    }
}
