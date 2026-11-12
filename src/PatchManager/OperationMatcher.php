<?php

declare(strict_types=1);

namespace PrimaAssicurazioni\PatchManager;

use PhpCollection\AbstractSequence;
use PhpCollection\Sequence;
use PrimaAssicurazioni\PatchManager\Request\Operations;

/**
 * Match the correct handlers based on actual operations
 */
class OperationMatcher
{
    private Sequence $handlers;

    private Operations $operations;

    public function __construct(Operations $operations)
    {
        $this->handlers = new Sequence();
        $this->operations = $operations;
    }

    public function addHandler(PatchOperationHandler $handler): void
    {
        $this->handlers->add($handler);
    }

    /**
     * @param iterable<Patchable>|Patchable $subject a Patchable instance or a collection of instances
     *
     * @throws Exception\MissingOperationNameRequest
     * @throws Exception\MissingOperationRequest
     * @throws Exception\InvalidJsonRequestContent
     */
    public function getMatchedOperations(iterable|Patchable $subject): Sequence
    {
        $handlers = $this->handlers;

        return $this->operations
            ->all()
            ->foldLeft(
                new Sequence(),
                function (Sequence $matchedOperations, array $operationData) use ($handlers, $subject) {
                    $handler = $handlers->find(static fn (PatchOperationHandler $patchHandler) => $operationData[Operations::OP_KEY_NAME] === $patchHandler->getName());
                    if ($handler->isDefined()) {
                        /** @var PatchOperationHandler $patchOperationHandler */
                        $patchOperationHandler = $handler->get();

                        if (is_iterable($subject)) {
                            $canHandleAll = true;
                            foreach ($subject as $patchable) {
                                if (false === $patchOperationHandler->canHandle($patchable)) {
                                    $canHandleAll = false;

                                    break;
                                }
                            }

                            if ($canHandleAll) {
                                $matchedOperations->add(MatchedPatchOperation::create($operationData, $patchOperationHandler));
                            }

                        } elseif ($patchOperationHandler->canHandle($subject)) {
                            $matchedOperations->add(MatchedPatchOperation::create($operationData, $handler->get()));
                        }
                    }

                    return $matchedOperations;
                }
            );
    }

    /**
     * @param iterable<Patchable>|Patchable $subject a Patchable instance or a collection of instances
     * @throws Exception\InvalidJsonRequestContent
     * @throws Exception\MissingOperationNameRequest
     * @throws Exception\MissingOperationRequest
     */
    public function getUnmatchedOperations(iterable|Patchable $subject): AbstractSequence
    {
        $matchedOperations = $this->getMatchedOperations($subject);

        return $this->operations
            ->all()
            ->filterNot(
                static fn (array $operationData) => $matchedOperations
                ->find(static fn (MatchedPatchOperation $matched) => $matched->matchFor($operationData[Operations::OP_KEY_NAME]))
                ->isDefined()
            )
            ->map(static fn (array $operationData) => $operationData[Operations::OP_KEY_NAME]);
    }
}
