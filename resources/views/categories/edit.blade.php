<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Categories') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <x-auth-validation-errors class="mb-4" :errors="$errors"/>
                    <form method="POST" action="{{ route('categories.update', $category) }}">
                        @method('PUT')
                        @csrf
                        {{ __('Name of the category') }}:
                        <input class="appearance-none block w-1/3 bg-white text-gray-700 border border-solid border-gray-300 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white" id="grid-first-name" type="text" name="name" placeholder="Category name" value="{{ old('name', $category->name) }}">
                        {{ __('Payment type') }}:
                        <div class="mb-3 w-1/3">
                            <select name="payment_type_id" class="form-select appearance-none block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding bg-no-repeat border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none" >
                                @foreach($payment_types as $payment_type)
                                    <option value="{{ $payment_type->id }}"
                                            @if($payment_type->id == $category->payment_type_id) selected @endif>{{ $payment_type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit"
                                class="focus:outline-none text-white bg-green-600 hover:bg-green-500 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2">
                            {{ __('Save') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
