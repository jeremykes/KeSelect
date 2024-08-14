<?php

namespace App\Livewire\Components;

use Livewire\Attributes\Modelable;
use Livewire\Attributes\On;
use Livewire\Component;

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
    public $searchDisplay;

    /**
     * Initializes the component by preparing the search display.
     *
     * @return void
     */
    public function mount()
    {
        $this->searchDisplay = $this->prepareSearchDisplay();
    }

    /**
     * Handles the search update event by updating the options based on the search query.
     *
     * @return void
     */
    #[On('searchUpdated')]
    public function updatedSearch()
    {
        if (strlen($this->search) >= $this->minSearchLength) {
            $this->options = $this->fetchOptions($this->search);
        } else {
            $this->options = [];
        }
    }

    /**
     * Prepares the search display by constructing an array of display values.
     *
     * The first record (0) in the search display array is used as the key (ID)
     * for the record while the second record (1) is used as the main title. Any
     * additional records after that are appended to the display string. The ID 
     * of the column is always included to ensure the correct record is returned.
     * 
     * THIS ARRAY NAMES MUST EXACTLY MATCH THE COLUMNS IN THE SEARCH MODEL 
     * OTHERWISE COMPONENT WILL BREAK.
     * 
     * @return array The prepared display array containing the primary display and searchable columns.
     */
    public function prepareSearchDisplay()
    {
        $display_array = [];
        $display_array[] = 'id';

        foreach ($this->searchableColumns as $value) {
            $display_array[] = $value;
        }

        $this->primaryDisplay = $display_array[1];

        return $display_array;
    }

    /**
     * Selects an option by setting the selected option ID and value, and then resets the options and search.
     *
     * @param int $id The ID of the selected option.
     * @param mixed $value The value of the selected option.
     * @return void
     */
    public function selectOption($id, $value)
    {
        $this->selectedOptionId = $id;
        $this->selectedOption = $value;
        $this->reset('options', 'search');
    }

    /**
     * Removes the currently selected option by resetting its ID and value.
     *
     * @return void
     */
    public function removeSelectedOption()
    {
        $this->selectedOptionId = null;
        $this->selectedOption = null;
    }

    private function fetchOptions($searchTerm)
    {
        $modelName = 'App\\Models\\' . $this->searchableModel;

        $query = $modelName::query();

        $firstCondition = array_shift($this->searchableColumns);
        $query->where($firstCondition, 'LIKE', '%' . $searchTerm . '%');

        foreach ($this->searchableColumns as $column) {
            $query->orWhere($column, 'LIKE', '%' . $searchTerm . '%');
        }

        $columns = $this->searchDisplay;
        $results = $query->select($columns)->get()->map(function ($item) use ($columns) {
            return collect($columns)->mapWithKeys(function ($column) use ($item) {
                return [$column => $item->$column];
            });
        });

        return $results->toArray();
    }

    public function render()
    {
        return view('livewire.components.ke-select');
    }
}
