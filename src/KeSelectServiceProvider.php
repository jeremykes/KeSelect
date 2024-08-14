<?php

namespace KeSelect;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class KeSelectServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Livewire::component('ke-select', KeSelect::class);
    }

    public function register()
    {
        //
    }
}
