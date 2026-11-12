<?php

declare(strict_types=1);

namespace PrimaAssicurazioni\PatchManager\Tests\FakeObjects;

use PrimaAssicurazioni\PatchManager\Patchable as IPatchable;

class SubjectA implements IPatchable
{
    private int $a = 1;
}
