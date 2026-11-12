<?php

declare(strict_types=1);

namespace PrimaAssicurazioni\PatchManager\Tests\Bundle;

use PHPUnit\Framework\Attributes\Test;
use PrimaAssicurazioni\PatchManager\Bundle\DependencyInjection\PatchManagerCompilerPass;
use PrimaAssicurazioni\PatchManager\Bundle\PatchManagerBundle;
use PrimaAssicurazioni\PatchManager\PatchOperationHandler;
use PrimaAssicurazioni\PatchManager\Tests\PatchManagerTestCase;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class PatchManagerBundleTest extends PatchManagerTestCase
{
    #[Test]
    public function buildShouldSucceed(): void
    {
        $cb = $this->prophesize(ContainerBuilder::class);
        $cb->addCompilerPass(new PatchManagerCompilerPass())->shouldBeCalled()->willReturn($cb->reveal());
        $cb->registerForAutoconfiguration(PatchOperationHandler::class)->shouldBeCalled()->willReturn(new ChildDefinition(''));
        $bundle = new PatchManagerBundle();
        $bundle->build($cb->reveal());
    }
}
