<div x-data="{
        isOpen: false,
        search: $wire.entangle('search').live,
        minSearchLength: $wire.entangle('minSearchLength'),
        selectedOption: $wire.entangle('selectedOption').live,
        selectedOptionId: $wire.entangle('selectedOptionId').live,
        primaryDisplay: $wire.entangle('primaryDisplay').live,
        options: $wire.entangle('options').live,
        selectOption(option) {
            $wire.selectOption(option);
            this.isOpen = false;
        }
    }" class="relative">
    <div class="relative p-2 rounded border-[1px] border-slate-300 dark:border-slate-600 flex items-center bg-white dark:bg-slate-800">
        <template x-if="selectedOption">
            <div class="flex bg-indigo-500 text-white dark:bg-indigo-500 p-0 items-center rounded-sm">
                <span class="px-2" x-text="selectedOption"></span>
                <button @click="selectedOption = null, selectedOptionId = null, $wire.removeSelectedOption()" class="px-2 rounded-r-sm bg-red-700 border-l border-l-slate-400 text-white">
                    &times;
                </button>
            </div>
        </template>
        <input type="text" placeholder="Start typing..." class="flex-grow text-slate-900 dark:text-slate-300 p-0 bg-white dark:bg-slate-800 border-none outline-none border-transparent focus:border-transparent focus:ring-0" 
            x-model="search"
            x-show="!selectedOption"  
            @input="isOpen = search.length >= minSearchLength"
            >
        <div class="absolute right-4 animate-spin" wire:loading wire:target="search">  
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
            </svg>                         
        </div>
    </div>

    <div x-show="isOpen">
        <div class="absolute z-10 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 mt-1 rounded w-full">
            <div x-show="options.length > 0">
                <template x-for="option in options" :key="option.id">
                    <div @click="selectOption(option)" class="p-2 hover:bg-slate-100 dark:hover:bg-slate-700 cursor-pointer">
                        <template x-for="(value, index) in option" :key="index + 1">
                            <template x-if="index === primaryDisplay">
                                <p x-text="value"></p>.
                            </template>
                        </template>  
                        <template x-for="(value, index) in option" :key="index + 2">
                            <template x-if="index !== 'id' && index !== primaryDisplay">
                                <p x-text="value" class="text-slate-500 text-xs"></p>.
                            </template>
                        </template>  
                    </div>
                </template>
            </div>
        </div>
    </div>

    @if (count($options) === 0 && strlen($search) >= $minSearchLength)
        <div class="absolute z-10 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 mt-1 rounded w-full">
            <div class="p-2 text-gray-500">  
                No results found.                      
            </div> 
        </div> 
    @endif
</div>
