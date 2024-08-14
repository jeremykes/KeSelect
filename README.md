# KeSelect Livewire 3 Dropdown Picker

KeSelect is a TALL Stack dropdown picker with Eloquent Search autofill.

I created this Component because it was quite hard to find anything created for the TALL Stack. The component mimics the main functions of a lot of the other available plugins like Select2, SelectJS, TomSelect, SelectizeJS and so forth.

## Requirement

- php v8.1
- laravel v10
- livewire v3

KeSelect uses plain Livewire, AlpineJS and TailwindCSS to achieve a simple dropdown select functionality that grabs data directly from the backend. The component can be customized normally in a "laravelly" way to do more.

## Installation

You can install KeSelect using composer.

```bash
composer require jeremykes/keselect
```

## Quick Start

After installing the component, include it in your blade application and you are good to go.

```php
<livewire:ke-select :searchableModel="'Customers'" :searchableColumns="$search_columns" />
```
