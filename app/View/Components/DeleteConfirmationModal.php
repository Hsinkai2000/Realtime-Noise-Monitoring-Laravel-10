<?php

namespace App\View\Components;

use Closure;
use App\Models\Project;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DeleteConfirmationModal extends Component
{
    public string $type;
    /**
     * Create a new component instance.
     */
    public function __construct(
        string $type = ''
    ) {
        $this->type = $type;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View | Closure | string
    {
        return view('components.delete-confirmation-modal');
    }
}