<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Categories') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <a href="{{ route('categories.create') }}">
                <button type="button"
                        class="focus:outline-none text-white bg-green-600 hover:bg-green-500 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2">
                    {{ __('Create') }}
                </button>
            </a>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-2">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex flex-col">
                        <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                            <div class="py-2 inline-block min-w-full sm:px-6 lg:px-8">
                                <div class="overflow-hidden">
                                    <table class="min-w-full">
                                        <thead class="border-b bg-gray-800">
                                        <tr>
                                            <th scope="col"
                                                class="text-sm font-medium text-white px-6 py-4 text-left">
                                                {{ __('id') }}
                                            </th>
                                            <th scope="col"
                                                class="text-sm font-medium text-white px-6 py-4 text-left">
                                                {{ __('name') }}
                                            </th>
                                            <th scope="col"
                                                class="text-sm font-medium text-white px-6 py-4 text-left">
                                                {{ __('action') }}
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($categories as $category)
                                            <tr class="bg-white border-b transition duration-300 ease-in-out hover:bg-gray-100">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    {{ ($categories->currentpage()-1) * $categories->perpage() + $loop->index + 1 }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    {{ $category->name }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    <a href="{{ route('categories.edit', $category) }}"
                                                       style="display: inline-flex">
                                                        <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                             viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                  stroke-width="2"
                                                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                        </svg>
                                                    </a>
                                                    <form method="POST"
                                                          action="{{ route('categories.destroy', $category) }}"
                                                          style="display: inline-flex">
                                                        @method('DELETE')
                                                        @csrf
                                                        <button type="submit" onclick="return confirm('Точно?')">
                                                            <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                                 viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                      stroke-width="2"
                                                                      d="M12 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2M3 12l6.414 6.414a2 2 0 001.414.586H19a2 2 0 002-2V7a2 2 0 00-2-2h-8.172a2 2 0 00-1.414.586L3 12z"></path>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr class="bg-white border transition duration-300 ease-in-out hover:bg-gray-100">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-center" colspan="4">
                                                    {{ __('DB is empty D:') }}
                                                </td>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                    <div class="mt-4">
                                        {{ $categories->links() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
