<?php
use Livewire\Volt\Component;

new class extends Component {

}
?>

<section class="space-y-4">
    <div class="flex flex-row justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ __("Aktív munkalapok") }}</h2>
        <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('worksheets') }}" wire:navigate>
            {{__("Tovább a munkalapokhoz")}}
            <i class="fa-solid fa-arrow-right"></i>
        </a>
    </div>
    <livewire:worksheets.worksheets-list preview="true"/>
</section>
