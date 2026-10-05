<?php

namespace App\Libraries;

use Illuminate\Contracts\View\Engine;

class CI4PhpEngine implements Engine
{
    public function get($path, array $data = [])
    {
        $renderer = new CI4ViewRenderer($path, $data);
        return $renderer->render();
    }
}
