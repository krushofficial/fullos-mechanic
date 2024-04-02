<?php

use Livewire\Volt\Component;
use App\Models\Worksheet;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Url;

new class extends Component {
    public string $preview;

    #[Url]
    public string $search = "";
    #[Url]
    public bool $active_filter = false;
    public string $view = "";

    public Collection $worksheets;

    public function getWorksheets(): Collection
    {
        if (auth()->user()->role == null) {
            return new Collection();
        }

        $q = Worksheet::query();

        if ($this->preview == "true" || $this->active_filter) {
            $q->where("closed", false);
        }
        if ($this->search != "") {
            $q->where(function (Builder $q) {
                $q->whereAny([
                    "id",
                    "created_at",
                    "plate",
                    "year",
                    "type",
                    "owner_name",
                    "owner_address"
                ], "LIKE", "%".$this->search."%");
                $q->orWhereHas("advisor", function (Builder $q) {
                   $q->where("name", "LIKE", "%".$this->search."%");
                });
                $q->orWhereHas("mechanic", function (Builder $q) {
                    $q->where("name", "LIKE", "%".$this->search."%");
                });
            });
        }
        if (auth()->user()->role == "mechanic") {
            $q->where("mechanic_id", auth()->user()->id);
        }

        return ($this->worksheets = $q->orderByDesc("created_at")->get());
    }

    public function viewWorksheet(string $id): void {
        $this->view = $id;
    }

    public function createWorksheet(): void {
        $worksheet = ($this->worksheet = new Worksheet());
        $worksheet->plate = "?";
        $worksheet->year = 2000;
        $worksheet->type = "?";
        $worksheet->advisor_id = auth()->user()->id;
        $worksheet->owner_name = "?";
        $worksheet->owner_address = "?";
        $worksheet->closed = false;
        $worksheet->save();
    }
}

?>

<section class="space-y-4">
    @if($preview!="true")
        <div class="flex flex-col space-y-4 md:space-y-0 md:flex-row md:justify-between md:items-center">
            <div class="space-x-2">
                <x-text-input id="search" class="w-64 h-8" type="text" name="search" wire:model.live.debounce="search"/>
                <x-primary-button>
                    <i class="fa-regular fa-search"></i>
                </x-primary-button>
            </div>
            <x-primary-button class="w-min" wire:click="createWorksheet">
                <i class="fa-regular fa-plus font-bold"></i><span class="ml-2">Létrehozás</span>
            </x-primary-button>
            <label for="active-filter" class="items-center flex flex-row md:flex-row-reverse">
                <input wire:model.live="active_filter" id="active-filter" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" name="active-filter">
                <span class="text-sm text-gray-600 dark:text-gray-400 ml-2 md:mr-2">{{ __('Csak lezáratlan munkalapok') }}</span>
            </label>
        </div>
    @endif
    <div class="rounded-md overflow-hidden table border border-gray-300 dark:border-gray-700 w-full">
        <table class="text-center border-collapse rounded-md shadow-sm w-full text-gray-800 dark:text-gray-200">
            <colgroup>
                <col span="1" class="w-1/12">
                <col span="1" class="w-5/12">
                <col span="1" class="w-3/12">
                <col span="1" class="w-3/12">
            </colgroup>
            <thead>
            <tr class="bg-gray-50 dark:bg-gray-900">
                <th class="p-1">ID</th>
                <th class="p-1">Tulajdonos neve</th>
                <th class="p-1">Rendszám</th>
                <th class="p-1">Felvétel ideje</th>
            </tr>
            </thead>
            <tbody>
            @if(count($this->getWorksheets()) > 0)
                @foreach($this->worksheets as $worksheet)
                    <tr id="{{ $worksheet->id }}" class="border-t border-gray-300 dark:border-gray-700 hover:bg-gray-50 hover:dark:bg-gray-900 cursor-pointer" wire:click="viewWorksheet({{ $worksheet->id }})" x-on:click="$dispatch('open-modal', 'worksheet-view')">
                        <td>{{ $worksheet->id }}</td>
                        <td>{{ $worksheet->owner_name }}</td>
                        <td>{{ $worksheet->plate }}</td>
                        <td>{{ $worksheet->created_at }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="4" class="border-t border-gray-300 dark:border-gray-700 hover:bg-gray-50 hover:dark:bg-gray-900">
                        {{ __("Nincs megjeleníthető elem") }}
                    </td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>

    <livewire:worksheets.worksheet-view :worksheet_id="$this->view"/>
</section>

