<?php

namespace App\Livewire\Components;

use Livewire\Attributes\Modelable;
use Livewire\Attributes\On;
use Livewire\Component;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\Reactive;

class KeSelect extends Component
{
    #[Reactive] public $searchableColumns;
    #[Modelable] public $selectedOptionId;
    public $selectedOption = null;
    public $options = [];
    public $search = '';
    public $minSearchLength = 3;

    public $searchableModel;
    // public $searchableColumns;
    public $primaryDisplay;
    public $optionID;
    public $searchDisplay;
    public $modelName;

    /**
     * Prepares the search display by constructing an array of display values.
     *
     * The first record (0) in the search display array is used as the key (ID)
     * for the record while the second record (1) is used as the main title. Any
     * additional records after that are appended to the display string. The ID 
     * of the column is always included to ensure the correct record is returned.
     * 
     * Mount tries to see if optionID has been set. If not, it
     * will set it to "id". Then it will check if ID exists in the model.
     * 
     * Mount also tries to see if primaryDisplay has been set. If not, it
     * will set the first column (after optionID) as the primary display.
     * 
     * THIS ARRAY NAMES MUST EXACTLY MATCH THE COLUMNS IN THE SEARCH MODEL 
     * OTHERWISE COMPONENT WILL BREAK.
     * 
     * @return array The prepared display array containing the primary display and searchable columns.
     */
    public function mount()
    {
        // Check for required fields
        if (!$this->searchableModel) {
            dd("Required field :searchableModel is not set");
        }
        if (!$this->searchableColumns) {
            dd("Required field :searchableColumns is not set");
        }

        $this->modelName = 'App\\Models\\' . $this->searchableModel;

        // Initialize and check if optionID exists in Model
        if ($this->optionID) {
        } else {
            $this->optionID = 'id';
        }
        $exists = $this->columnExists($this->modelName, $this->optionID);
        if (!$exists) {
            dd($this->optionID . ' does not exist in this Model.');
        }

        // Format searchable columns array
        $display_array = [];
        $display_array[] = $this->optionID;
        foreach ($this->searchableColumns as $value) {
            $display_array[] = $value;
        }
        $this->searchDisplay = $display_array;

        // Check for primary display if not set default
        if ($this->primaryDisplay) {
            if (!in_array($this->primaryDisplay, $display_array)) {
                dd($this->primaryDisplay . ' is not in searchable columns');
            }
        } else {
            unset($display_array[array_search($this->optionID, $display_array)]);
            $this->primaryDisplay = $display_array[1];
        }

        // If selectedOptionId is initially set then set the selected option value.
        if ($this->selectedOptionId != null) {
            $this->setSelectedValue($this->selectedOptionId);
        }

        $this->dispatch('$refresh');
    }

    /**
     * Sets the selected value of the dropdown based on the provided key.
     *
     * @param mixed $key The ID of the selected option.
     * @return void
     */
    public function setSelectedValue($key)
    {
        $query = $this->modelName::query();
        $query->find($this->selectedOptionId);

        $columns = $this->searchDisplay;
        $results = $query->select($columns)->get()->map(function ($item) use ($columns) {
            return collect($columns)->mapWithKeys(function ($column) use ($item) {
                return [$column => $item->$column];
            });
        });

        $output = $results->toArray();

        $this->selectOption($output[0]);
    }

    /**
     * Check if a column exists in a given model's table.
     *
     * @param string $modelClass The fully qualified name of the model class.
     * @param string $columnName The name of the column to check.
     * @throws \InvalidArgumentException If the provided class is not a valid Eloquent model.
     * @return bool Returns true if the column exists in the table, false otherwise.
     */
    public function columnExists($modelClass, $columnName)
    {
        if (!class_exists($modelClass) || !is_subclass_of($modelClass, Model::class)) {
            throw new \InvalidArgumentException('The provided class ' . $modelClass . ' is not a valid Eloquent model.');
        }

        // Get the table name from the model
        $tableName = (new $modelClass)->getTable();

        // Check if the column exists in the table
        return Schema::hasColumn($tableName, $columnName);
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
     * Selects an option by setting the selected option ID and value, and then resets the options and search.
     *
     * @param int $id The ID of the selected option.
     * @param mixed $value The value of the selected option.
     * @return void
     */
    public function selectOption($option)
    {
        $this->selectedOptionId = $option['id'];
        $this->selectedOption = $option[$this->primaryDisplay];
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
        $query = $this->modelName::query();

        $searchableCols = $this->searchableColumns;

        $firstCondition = array_shift($searchableCols);
        $query->where($firstCondition, 'LIKE', '%' . $searchTerm . '%');

        foreach ($searchableCols as $column) {
            if (!empty($column)) {
                $query->orWhere($column, 'LIKE', '%' . $searchTerm . '%');
            }
        }

        $columns = $this->searchDisplay;
        $results = $query->select($columns)->get()->map(function ($item) use ($columns) {
            return collect($columns)->mapWithKeys(function ($column) use ($item) {
                return [$column => $item->$column];
            });
        });

        return $results->toArray();
    }

    public function checkSearchableColumns()
    {
        foreach ($this->searchableColumns as $column) {
            if ($this->columnExists($this->modelName, $column)) {
                dd($column . ' does not exist in this Model.');
            }
        }
    }

    public function render()
    {
        return view('livewire.components.ke-select');
    }
}
