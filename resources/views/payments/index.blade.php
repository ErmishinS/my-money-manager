<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Wallet') }}
        </h2>
    </x-slot>

    <div class="py-5">
        <div class="relative md:grid md:grid-cols-2 md:gap-4 w-full sm:px-6 lg:px-8">
            <div class="md:grid md:grid-rows-4 md:grid-flow-row md:gap-2 md:h-auto ">
                <div class="row-span-1 flex flex-col bg-white rounded-md shadow-md p-4">
                    <div class="text-center text-xl font-semibold mb-2">
                        Balance
                    </div>
                    <div class="text-xl md:text-3xl text-center flex justify-around items-center h-full">
                        <div class="flex flex-col justify-between rounded-md bg-yellow-100 w-full h-full p-2">
                        <span class="flex justify-center items-center h-full">
                            {{ $cash + $non_cash }}
                        </span>
                            <span class="text-sm md:text-2xl">
                            Total amount
                        </span>
                        </div>
                        <div class="flex flex-col justify-between rounded-md bg-red-100 w-full h-full p-2 mx-4">
                        <span class="flex justify-center items-center h-full">
                            {{ $cash }}
                        </span>
                            <span class="text-sm md:text-2xl">
                            Cash
                        </span>
                        </div>
                        <div class="flex flex-col justify-between rounded-md bg-blue-100 w-full h-full p-2">
                        <span class="flex justify-center items-center h-full">
                            {{ $non_cash }}
                        </span>
                            <span class="text-sm md:text-2xl">
                            Non-cash
                        </span>
                        </div>
                    </div>
                </div>
                <div class="row-span-3 bg-white rounded-lg shadow-md mt-2 p-4">
                    <div class="text-center text-xl font-semibold mb-2">
                        Payments
                    </div>
                    <a class="flex justify-end" href="{{ route('payments.create') }}">
                        <x-primary-button>
                            {{ __('Create payment') }}
                        </x-primary-button>
                    </a>
                    @forelse($payments as $payment)
                        <div class="flex justify-between items-center py-7 px-5 bg-gray-100 rounded-md border-b transition duration-300 ease-in-out hover:bg-gray-200 my-2">
                            <div class="text-center text-green-600 @if($payment->amount < 0) text-red-600 @endif" >
                                {{ $payment->amount }}
                            </div>
                            <div class="">
                                {{ $payment->money_type->name }}
                            </div>
                            <div class="">
                                {{ $payment->category->name }}
                            </div>
                            <div class="" title="{{ $payment->created_at->format('d.m.Y H:i') }}">
                                {{ $payment->created_at->diffForHumans() }}
                            </div>
                            <div class="flex items-center">
                                <a href="{{ route('payments.edit', $payment) }}"
                                   style="display: inline-flex">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                         viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              stroke-width="2"
                                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                                <form method="POST"
                                      action="{{ route('payments.destroy', $payment) }}"
                                      style="display: inline-flex">
                                    @method('DELETE')
                                    @csrf
                                    <button type="submit" onclick="return confirm('Are you sure?')">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                             viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  stroke-width="2"
                                                  d="M12 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2M3 12l6.414 6.414a2 2 0 001.414.586H19a2 2 0 002-2V7a2 2 0 00-2-2h-8.172a2 2 0 00-1.414.586L3 12z"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <tr class="bg-white border transition duration-300 ease-in-out hover:bg-gray-100">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-center" colspan="6">
                                {{ __('No payments yet D:') }}
                            </td>
                        </tr>
                    @endforelse
                    <div class="">
                        {{ $payments->links() }}
                    </div>
                </div>
            </div>
            <div class="relative rounded-lg bg-white shadow-md h-auto p-4 mt-3 md:m-0">
                <div class="text-center text-xl font-semibold mb-2">
                    Statistics
                </div>
                <div class="grid grid-cols-2 gap-2 flex justify-center items-center">
                    <div class="col-span-1">
                        <span class="flex justify-center items-center w-full font-medium text-red-500">
                            {!! $chart1->options['chart_title'] !!}
                        </span>
                        <div class="flex">
                            {!! $chart1->renderHtml() !!}
                        </div>
                        {!! $chart1->renderChartJsLibrary() !!}
                        {!! $chart1->renderJs() !!}
                    </div>

                    <div class="col-span-1">
                        <span class="flex justify-center items-center w-full font-medium text-green-400">
                            {!! $chart2->options['chart_title'] !!}
                        </span>
                        <div class="flex">
                            {!! $chart2->renderHtml() !!}
                        </div>
                        {!! $chart2->renderJs() !!}
                    </div>
                </div>
                <div>
                    <span class="flex justify-center items-center w-full font-medium my-2">
                        {!! $chart3->options['chart_title'] !!}
                    </span>
                    {!! $chart3->renderHtml() !!}
                    {!! $chart3->renderJs() !!}
                </div>
                <div class="mt-3">
                    <span class="flex justify-center items-center w-full font-medium">
                        {!! $chart4->options['chart_title'] !!}
                    </span>
                    {!! $chart4->renderHtml() !!}
                    {!! $chart4->renderJs() !!}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
