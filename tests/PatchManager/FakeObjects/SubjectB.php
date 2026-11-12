<?php

declare(strict_types=1);

namespace PrimaAssicurazioni\PatchManager\Tests\FakeObjects;

use PrimaAssicurazioni\PatchManager\Patchable as IPatchable;

class SubjectB implements IPatchable
{
    private int $a = 1;
}
