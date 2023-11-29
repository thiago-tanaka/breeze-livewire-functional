<?php

use App\Models\User;
use App\Models\Product;
use App\Providers\RouteServiceProvider;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use function Livewire\Volt\layout;

use function Livewire\Volt\state;

layout('layouts.app');

state([
    'name' => fn() => '',
    'products' => auth()->user()->products()->get(['id', 'name'])
]);

$createProduct = function () {
    $validated = $this->validate([
        'name' => ['required', 'string', 'max:255', 'min:3'],
    ]);

    Product::create($validated + ['user_id' => Auth::id()]);
    $this->products = auth()->user()->products()->get(['id', 'name']);

    $this->name = '';

    $this->dispatch('product-created');
};

$deleteProduct = function (Product $product) {
    $product->delete();
    $this->products = auth()->user()->products()->get(['id', 'name']);
};

?>

<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('Dashboard') }}
    </h2>
</x-slot>

<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 pt-0">
            <form wire:submit="createProduct" class="border-b border-gray-600 mb-6 mt-6 space-y-6 pb-6 md:w-1/3">
                <p class="text-gray-500 dark:text-gray-400">Add new product</p>
                <div>
                    <x-input-label class="block text-sm font-medium text-gray-700" for="name"
                                   :value="__('Product Name')"/>
                    <x-text-input wire:model="name" id="name" name="name" type="text" class="mt-1 block w-full" required
                                  autofocus autocomplete="name"/>
                    <x-input-error class="mt-2" :messages="$errors->get('name')"/>
                </div>

                <div class="flex items-center gap-4">
                    <x-primary-button>{{ __('Save') }}</x-primary-button>

                    <x-action-message class="me-3" on="product-created">
                        {{ __('Saved.') }}
                    </x-action-message>
                </div>
            </form>

            <div class="rounded-lg">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="py-3 px-6">Product Name</th>
                        <th scope="col" class="py-3 px-6">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($products as $product)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <td class="py-4 px-6">{{ $product->name }}</td>
                            <td>
                                <button wire:click="deleteProduct({{ $product->id }})"
                                        class="px-4 py-2 bg-red-500 text-white text-xs font-semibold rounded hover:bg-red-600">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
