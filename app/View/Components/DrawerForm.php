<?php


namespace App\View\Components;

use Illuminate\View\Component;

class DrawerForm extends Component
{
    public $id, $title, $action, $inputId, $inputLabel, $cancelHandler;

    public function __construct($id, $title, $action, $inputId, $inputLabel, $cancelHandler)
    {
        $this->id = $id;
        $this->title = $title;
        $this->action = $action;
        $this->inputId = $inputId;
        $this->inputLabel = $inputLabel;
        $this->cancelHandler = $cancelHandler;
    }

    public function render()
    {
        return view('components.drawer-form');
    }
}
