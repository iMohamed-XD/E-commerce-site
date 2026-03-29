<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class LegalPage extends Component
{
    public $title;
    public $sections;
    public $ctaText;
    public $ctaLink;
    /**
     * Create a new component instance.
     */
    public function __construct($title, $sections = [], $ctaText = null, $ctaLink = null)
    {
        $this->title = $title;
        $this->sections = $sections;
        $this->ctaText = $ctaText;
        $this->ctaLink = $ctaLink;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.legal-page');
    }
}
