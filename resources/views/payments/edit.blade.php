<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit payments') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <x-auth-validation-errors class="mb-4" :errors="$errors"/>
                    <form method="POST" action="{{ route('payments.update', $payment) }}">
                        @method('PUT')
                        @csrf
                        {{ __('Amount of the payment') }}:
                        <input class="appearance-none block w-1/3 bg-white text-gray-700 border border-solid border-gray-300 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white" id="grid-first-name" type="text" name="amount" placeholder="Amount of payment" value="{{ old('amount', $payment->amount) }}">
                        {{ __('Money type') }}:
                        <div class="mb-3 w-1/3">
                            <select name="money_type_id" class="form-select appearance-none block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding bg-no-repeat border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none" >
                                @foreach($money_types as $money_type)
                                    <option value="{{ $money_type->id }}"
                                            @if($money_type->id == $payment->money_type_id) selected @endif>{{ $money_type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        {{ __('Payment type') }}:
                        <div class="mb-3 w-1/3">
                            <select name="payment_type_id" class="form-select appearance-none block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding bg-no-repeat border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none" >
                                @foreach($payment_types as $payment_type)
                                    <option value="{{ $payment_type->id }}"
                                            @if($payment_type->id == $payment->payment_type_id) selected @endif>{{ $payment_type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        {{ __('Category') }}:
                        <div class="mb-3 w-1/3">
                            <select name="category_id" class="form-select appearance-none block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding bg-no-repeat border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none" >
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}"
                                            @if($category->id == $payment->category_id) selected @endif>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <x-primary-button>
                            {{ __('Save') }}
                        </x-primary-button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
