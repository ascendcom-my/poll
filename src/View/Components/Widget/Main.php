<?php

namespace Bigmom\Poll\View\Components\Widget;

use Illuminate\View\Component;

class Main extends Component
{
    public $questions;

    public $sanitized;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($questions)
    {
        $this->questions = $questions;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.vendor.bigmom.poll.widget.main');
    }
}
