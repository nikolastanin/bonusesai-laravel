<?php

namespace App\Livewire\Chat;

use Livewire\Component;

class JsonSnippetRenderer extends Component
{
    public $data;

    public function mount($data)
    {
        $this->data = is_string($data) ? json_decode($data, true) : $data;
    }

    public function render()
    {
        return view('livewire.chat.json-snippet-renderer');
    }
}