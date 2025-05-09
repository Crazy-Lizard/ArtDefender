<?php

namespace App\Livewire;

use Livewire\Component;

class ToggleSwitch extends Component
{
    // public $activeState = 'low'; // low, medium, high
    // public $itemsLow;
    // public $itemsMedium;
    // public $itemsHigh;

    // public function mount($lowItems = [], $mediumItems = [], $highItems = [])
    // {
    //     $this->itemsLow = (array)$lowItems;
    //     $this->itemsMedium = (array)$mediumItems;
    //     $this->itemsHigh = (array)$highItems;
    // }

    // public function setState($state)
    // {
    //     $this->activeState = $state;
    // }

    
    public $status = 'low'; // Возможные значения: low, medium, high

    public function cycleStatus()
    {
        $this->status = match($this->status) {
            'low' => 'medium',
            'medium' => 'high',
            'high' => 'low',
            default => 'low'
        };
    }


    // public $activeState;
    // public $states;
    // public $content;

    // public function mount(array $states, array $content, $initialState = null)
    // {
    //     $this->states = $states;
    //     $this->content = $content;
    //     $this->activeState = $initialState ?? $states[0];
    // }

    // public function setState($state)
    // {
    //     $this->activeState = $state;
    // }

    public function render()
    {
        return view('livewire.toggle-switch');
    }
}
