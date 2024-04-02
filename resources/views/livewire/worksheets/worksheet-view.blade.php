<?php
use Livewire\Volt\Component;
use Livewire\Attributes\Reactive;
use App\Models\Worksheet;
use App\Models\User;
use App\Models\WorksheetItem;
use App\Models\AvailableItem;

new class extends Component {
    public array $itype_name = array(
        "procedure" => "Munkafolyamat",
        "material" => "Anyag",
        "part" => "Alkatrész"
    );
    public array $itype_unit = array(
        "procedure" => "óra",
        "material" => "db",
        "part" => "db",
        "null" => "db"
    );

    #[Reactive]
    public string $worksheet_id;
    public Worksheet $worksheet;

    public string $plate;
    public int $year;
    public string $type;
    public string $owner_name;
    public string $owner_address;
    public string $mechanic_id;
    public string $paid_with;
    public bool $closed;
    public array $worksheet_items;

    public string $item_type;
    public string $item_template_id;
    public int $item_q;

    public bool $not_advisor;
    public bool $not_mechanic;

    public int $price;

    public function calculatePrice(): void {
        foreach($this->worksheet_items as $item) {
            $this->price += $item->quantity * $item->item_template->price;
        }
    }

    public function getWorksheet(): void {
        if (isset($this->worksheet) && $this->worksheet->id == $this->worksheet_id) {
            return;
        }

        $worksheet = ($this->worksheet = Worksheet::find($this->worksheet_id));

        $this->plate = $worksheet->plate;
        $this->year = $worksheet->year;
        $this->type = $worksheet->type;
        $this->owner_name = $worksheet->owner_name;
        $this->owner_address = $worksheet->owner_address;
        if ($worksheet->mechanic) {
            $this->mechanic_id = $worksheet->mechanic->id;
        } else {
            $this->mechanic_id = "null";
        }
        if ($worksheet->paid_with) {
            $this->paid_with = $worksheet->paid_with;
        } else {
            $this->paid_with = "null";
        }
        $this->closed = $worksheet->closed;
        $this->worksheet_items = $worksheet->worksheet_items->all();

        $this->item_type = "null";
        $this->item_template_id = "null";
        $this->item_q = 0;

        $this->not_advisor = !(auth()->user()->role == "advisor" || auth()->user()->role == "admin");
        $this->not_mechanic = !(auth()->user()->role == "mechanic" || auth()->user()->role == "admin");

        $this->price = 0;
        $this->calculatePrice();
    }

    public function addSelectedItem(): void {
        $item = new WorksheetItem();
        $item->worksheet_id = $this->worksheet_id;
        $item->item_id = $this->item_template_id;
        $item->quantity = $this->item_q;
        $item->save();
        $this->worksheet_items[] = $item;
        $this->calculatePrice();

        $this->item_type = "null";
        $this->item_template_id = "null";
        $this->item_q = 0;
    }

    public function saveWorksheet(): void {
        $worksheet = $this->worksheet;

        $worksheet->plate = $this->plate;
        $worksheet->year = $this->year;
        $worksheet->type = $this->type;
        $worksheet->owner_name = $this->owner_name;
        $worksheet->owner_address = $this->owner_address;
        if ($this->mechanic_id != "null") {
            $worksheet->mechanic_id = $this->mechanic_id;
        }
        $worksheet->save();
    }

    public function closeWorksheet(): void {
        $worksheet = $this->worksheet;
        $worksheet->paid_with = $this->paid_with;
        $worksheet->closed = true;
        $this->saveWorksheet();
        $this->closed = true;
    }
}
?>

<x-modal name="worksheet-view" focusable>
    @if($worksheet_id)
        {{ $this->getWorksheet() }}
        <div class="p-6 text-gray-800 dark:text-gray-200 flex flex-col space-y-4">
            <h2 class="font-bold text-lg text-center text-gray-800 dark:text-gray-200">Munkalap megtekintése</h2>
            <p><span class="font-semibold">Munkalap azonosító (ID): </span>{{ $this->worksheet_id }}</p>
            <div class="space-y-2 ">
                <p><span class="font-semibold">Gépjármű rendszáma: </span><x-text-input id="plate" type="text" class="w-64 h-6" wire:model="plate" disabled="{{ $this->closed || $this->not_advisor }}"/></p>
                <p><span class="font-semibold">Gépjármű gyártmánya: </span><x-text-input id="year" type="number" min="0" class="w-64 h-6" wire:model="year" disabled="{{ $this->closed || $this->not_advisor }}"/></p>
                <p><span class="font-semibold">Gépjármű típusa: </span><x-text-input id="type" type="text" class="w-64 h-6" wire:model="type" disabled="{{ $this->closed || $this->not_advisor }}"/></p>
            </div>
            <div class="space-y-2">
                <p><span class="font-semibold">Tulajdonos neve: </span><x-text-input id="owner_name" type="text" class="w-64 h-6" wire:model="owner_name" disabled="{{ $this->closed || $this->not_advisor }}"/></p>
                <p><span class="font-semibold">Tulajdonos lakcíme: </span><x-text-input id="owner_address" type="text" class="w-64 h-6" wire:model="owner_address" disabled="{{ $this->closed || $this->not_advisor }}"/></p>
            </div>
            <div class="space-y-2">
                <p><span class="font-semibold">Munkafelvevő neve: </span>{{ $this->worksheet->advisor->name }}</p>
                <p><label class="font-semibold" for="mechanic">Hozzárendelt szerelő: </label>
                    <select id="mechanic" wire:model.live="mechanic_id" {{ $this->closed || $this->not_advisor ? 'disabled' : '' }} class="w-72 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                        <option value="null">Még nem lett hozzárendelve</option>
                        @foreach(User::where("role", "mechanic")->get() as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </p>
            </div>
            <h3 class="font-semibold text-lg text-center text-gray-800 dark:text-gray-200">Végzett munka</h3>
            <div class="border-gray-300 dark:border-gray-700 text-gray-800 dark:text-gray-200 shadow-sm">
                <div class="border-x border-t w-full h-96 rounded-t-md overflow-auto p-3">
                    <ul>
                        @foreach($this->worksheet_items as $item)
                            <li class="flex flex-row justify-between">
                                <span>{{ $itype_name[$item->item_template->type] }}</span>
                                <span>{{ $item->item_template->nice_name }}</span>
                                <span>{{ $item->quantity }} {{ $itype_unit[$item->item_template->type] }}</span>
                                <span>{{ $item->quantity * $item->item_template->price }} Ft</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="border w-full rounded-b-md p-3 flex flex-col space-y-2">
                    <div class="flex flex-row justify-between">
                        <select id="item_type" wire:model.live="item_type" {{ $this->closed || $this->not_mechanic ? 'disabled' : '' }} class="w-32 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                            <option value="null"></option>
                            <option value="procedure">{{ $itype_name["procedure"] }}</option>
                            <option value="material">{{ $itype_name["material"] }}</option>
                            <option value="part">{{ $itype_name["part"] }}</option>
                        </select>
                        <select id="item_template_id" wire:model.live="item_template_id" {{ $this->closed || $this->not_mechanic ? 'disabled' : '' }} class="w-32 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                            <option value="null"></option>
                            @if($this->item_type!="null")
                                @foreach(AvailableItem::where("type", $this->item_type)->get() as $item)
                                    <option value="{{ $item->id }}">{{ $item->nice_name }}</option>
                                @endforeach
                            @endif
                        </select>
                        <div>
                            <x-text-input id="item_q" type="number" class="w-32" wire:model.live="item_q" min="0" max="255" disabled="{{ $this->closed || $this->not_mechanic }}"/><span> {{ $itype_unit[$this->item_type] }}</span>
                        </div>
                    </div>
                    <div class="flex flex-row w-full justify-between items-center">
                        <x-primary-button class="w-min" disabled="{{ !($this->item_type != 'null' && $this->item_template_id != 'null' && $this->item_q != 0) }}" wire:click="addSelectedItem">
                            Hozzáadás
                        </x-primary-button>
                        @if($this->item_template_id!="null")
                        <p>{{ AvailableItem::find($this->item_template_id)->price * $this->item_q }} Ft</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="flex flex-row w-full justify-end">
                <p><span class="font-semibold">Összesített ár: </span>{{ $this->price }} Ft</p>
            </div>
            <div class="flex flex-row w-full justify-end space-x-2">
                <p><label class="font-semibold" for="paid_with">Fizetési mód: </label>
                    <select id="paid_with" wire:model.live="paid_with" {{ $this->closed || $this->not_advisor ? 'disabled' : '' }} class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                        <option value="null">Még nem fizetett</option>
                        <option value="cash">Készpénz</option>
                        <option value="card">Bankkártya</option>
                    </select>
                </p>
                <x-danger-button wire:click="closeWorksheet" disabled="{{ $this->closed || $this->not_advisor || $this->paid_with == 'null' }}" x-on:click="$dispatch('close-modal', 'worksheet-view')">
                    Lezárás
                </x-danger-button>
            </div>
            <div class="flex flex-row w-full justify-end space-x-2">
                <x-danger-button x-on:click="$dispatch('close-modal', 'worksheet-view')">
                    Bezárás
                </x-danger-button>
                <x-secondary-button x-on:click="window.print()" disabled="{{ !$this->closed || $this->not_advisor }}">
                    Nyomtatás
                </x-secondary-button>
                <x-primary-button wire:click="saveWorksheet" disabled="{{ $this->closed }}" x-on:click="$dispatch('close-modal', 'worksheet-view')">
                    Mentés
                </x-primary-button>
            </div>
            @if($this->closed)
            <p class="font-light text-sm text-center text-gray-800 dark:text-gray-200">Ez a munkalap már le lett zárva <i class="fa-regular fa-lock"></i></p>
            @endif
        </div>
    @endif
</x-modal>
