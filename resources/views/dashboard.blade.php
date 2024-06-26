<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Irányítópult') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 space-y-4">
                <h3 class="font-normal text-lg text-gray-800 dark:text-gray-200">Üdvözöljük, <span class="font-semibold">{{ auth()->user()->name }}</span>!</h3>
                <livewire:dashboard.active-worksheets/>
            </div>
        </div>
    </div>
</x-app-layout>
