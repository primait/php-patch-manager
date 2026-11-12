<?php

declare(strict_types=1);

namespace PrimaAssicurazioni\PatchManager\Bundle;

use PrimaAssicurazioni\PatchManager\Bundle\DependencyInjection\PatchManagerCompilerPass;
use PrimaAssicurazioni\PatchManager\PatchOperationHandler;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class PatchManagerBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container): void
    {
        $container
            ->registerForAutoconfiguration(PatchOperationHandler::class)
            ->addTag('patch_manager.handler');

        $container->addCompilerPass(new PatchManagerCompilerPass());
    }
}
