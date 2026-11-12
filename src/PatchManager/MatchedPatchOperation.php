<?php

declare(strict_types=1);

namespace PrimaAssicurazioni\PatchManager;

use Symfony\Component\OptionsResolver\OptionsResolver;

class MatchedPatchOperation
{
    /** @var array<string, mixed> */
    private array $operationData;

    private PatchOperationHandler $handler;

    /**
     * @param array<string, mixed> $operationData
     */
    private function __construct(array $operationData, PatchOperationHandler $handler)
    {
        $this->operationData = $operationData;
        $this->handler = $handler;
    }

    /**
     * @param array<string, mixed> $operationData
     */
    public static function create(array $operationData, PatchOperationHandler $handler): MatchedPatchOperation
    {
        return new self($operationData, $handler);
    }

    public function matchFor(string $operationName): bool
    {
        return $operationName === $this->handler->getName();
    }

    /**
     * call handle on the handler
     */
    public function process(Patchable $patchable): void
    {
        $optionResolver = new OptionsResolver();
        $optionResolver->setRequired(['op']);
        $this->handler->configureOptions($optionResolver);
        $options = new OperationData($optionResolver->resolve($this->operationData));
        $this->handler->handle($patchable, $options);
    }

    public function getOpName(): string
    {
        return $this->handler->getName();
    }
}
