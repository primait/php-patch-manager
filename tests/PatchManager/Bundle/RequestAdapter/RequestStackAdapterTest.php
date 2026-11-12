<?php

declare(strict_types=1);

namespace PrimaAssicurazioni\PatchManager\Tests\Bundle\RequestAdapter;

use PHPUnit\Framework\Attributes\Test;
use PrimaAssicurazioni\PatchManager\Bundle\RequestAdapter\RequestStackAdapter;
use PrimaAssicurazioni\PatchManager\Request\Operations;
use PrimaAssicurazioni\PatchManager\Tests\PatchManagerTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class RequestStackAdapterTest extends PatchManagerTestCase
{
    #[Test]
    public function callShouldBeResolved(): void
    {
        $currentRequest = $this->prophesize(Request::class);
        $currentRequest->getContent()->willReturn('{"op":"data"}');
        $requestStack = $this->prophesize(RequestStack::class);
        $requestStack->getCurrentRequest()->willReturn($currentRequest->reveal());
        $adapter = new RequestStackAdapter($requestStack->reveal());

        $operations = new Operations($adapter);

        $this->assertCount(1, $operations->all());

        /** @var array $first */
        $first = $operations->all()->get(0);
        $this->assertEquals('data', $first['op']);
    }
}
