# KeSelect Livewire 3 Dropdown Picker

![Untitled](https://github.com/user-attachments/assets/5d3abf64-997d-4947-9df6-723f04196fa4)


KeSelect is a TALL Stack dropdown picker with eloquent search autofill.

I created this component because it was quite hard to find anything around for the TALL Stack. The component mimics the main functions of a lot of the other available plugins like Select2, SelectJS, TomSelect, SelectizeJS and so forth. I just didn't like going down the path of installing multiple other packages and jQuery (no thanks).

## Requirement

- PHP v8.1
- Laravel v10
- Livewire v3
- TailwindCSS
- AlpineJS

KeSelect uses plain Laravel, Livewire, AlpineJS and TailwindCSS to achieve a simple dropdown select functionality that grabs data directly from the backend. The component can be customized normally in a "laravelly" way as you like.

## Installation

You can install KeSelect using composer.

```bash
composer require jeremykes/keselect
```

## Quick Start

After installing the component, include it in your blade application and you are good to go. For example (assuming we have a variable ```$search_columns = ['name', 'description', 'title'];```:

```php
<livewire:ke-select :searchableModel="'Customers'" :searchableColumns="$search_columns" />
```
There are two *required* variables for the component; **searchableModel** and **searchableColumns**. Their description can be found below.

## Documentation

### Property Explanation
- ```:searchableModel``` (required) - this name must exactly match the Model you would like to perform the search on. If it doesn't exist, the component will throw an error.  For example if you had a Customers Model: ```:searchableModel="'Customers'"```.
- ```:searchableColumns``` (required) - this is an array of *column names* in your Model that you would like the search to be performed on. If any of the columns do not exist in your Model, the component will throw an error. An example format of this is: ```['name', 'description']```.
- ```:minSearchLength``` (optional) - this is the minimum number of characters that will be entered before the search is fired. The default is 3. For example if you wanted the search to fire after the 5th character: ```:minSearchLength="'5'"```.
- ```:primaryDisplay``` (optional) - this is a column value that you want to be highlighted in the search results. As in the GIF above, the ```primaryDisplay``` column value is bolded on the first line while all other column values are listed underneath in a slightly smaller font. This is also the value that will be shown in the input form if selected. If nothing is defined, the first value in the ```searchableColumns``` array will be used as the ```primaryDisplay```. For example if you wanted *description* to be the highlighted value: ```:primaryDisplay="'description'"```.
- ```:optionID``` (optional) - if your Model ID is not ```id``` (example you use ```UUID``` instead), then you need to define that so that the proper ID is referenced. If no ```optionID``` is provided, the default will be assumed as ```id```. If the component can't find either definitions existing in the Model, an error will be thrown. For example if you use ```UUID``` instead, you will define it as: ```:optionID="'UUID'"```.

In the component ```selectedOptionId``` is Modeled out of the component so that it can be referenced in the parent component. This is to allow the ID to be available to the parent once that relevant option is selected from the dropdown search results. You can reference it in the parent component like so:

```php
<livewire:ke-select :searchableModel="'Customers'" :searchableColumns="$search_columns" wire:model.live="selectedCustomerId" />
```

This is the model declaration:
```php
namespace App\Livewire\Components;

use Livewire\Attributes\Modelable;
use Livewire\Attributes\On;
use Livewire\Component;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;

class KeSelect extends Component
{
    #[Modelable] public $selectedOptionId = null;
    public $selectedOption = null;
    public $options = [];
    public $search = '';
    public $minSearchLength = 3;

    public $searchableModel;
    public $searchableColumns;
    public $primaryDisplay;
    public $optionID;
    public $searchDisplay;
    public $modelName;

```

### Styling

Included out of the box is both TailwindCSS light and dark theme. The styles are pretty standard and can be customized as necessary.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Future

### Multi-Select

I have not added Multi-select to the Component but will do so in the near future. I will see how I go.

### Testing

I also have not done extensive testing on this component so please use at own risk.

## Final Thoughts

This was a fun little weekend project so if you want to get in touch for a collab or anything let me know!  I hope this component is super useful to you.


