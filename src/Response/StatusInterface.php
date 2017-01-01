<?php

namespace Bauhaus\Http\Response;

interface StatusInterface
{
    public function code(): int;
    public function reasonPhrase(): string;
}
