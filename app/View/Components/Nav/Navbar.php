<?php

namespace App\View\Components\Nav;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Navbar extends Component
{
    public string $type;
    public string $projectId;
    /**
     * Create a new component instance.
     */
    public function __construct(string $type = 'projects', string $projectId = '0')
    {
        $this->type = $type;
        $this->projectId = $projectId;
    }


    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.nav.navbar');
    }
}
