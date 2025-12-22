<?php

namespace App\View\Components\Alert;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AlertModal extends Component
{
    public string $text;
    public string $title;
    /**
     * Create a new component instance.
     */
    public function __construct(string $text, string $title)
    {
        $this->text = $text;
        $this->title = $title;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.alert.alert-modal');
    }
}
