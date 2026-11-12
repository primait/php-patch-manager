<?php

declare(strict_types=1);

namespace PrimaAssicurazioni\PatchManager\Bundle\RequestAdapter;

use PrimaAssicurazioni\PatchManager\Request\Adapter;
use Symfony\Component\HttpFoundation\RequestStack;

class RequestStackAdapter implements Adapter
{
    private RequestStack $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function getRequestBody(): ?string
    {
        $request = $this->requestStack->getCurrentRequest();

        return is_null($request) ? null : $request->getContent();
    }
}
