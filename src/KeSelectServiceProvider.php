<?php

namespace KeSelect;

use App\Livewire\Components\KeSelect;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;
use Livewire\Livewire;

class KeSelectServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'ke-select');
    }

    public function register()
    {
        $this->callAfterResolving(BladeCompiler::class, function () {
            if (class_exists(Livewire::class)) {
                Livewire::component('ke-select', KeSelect::class);
            }
            Livewire::component('ke-select', KeSelect::class);
        });
    }
}
