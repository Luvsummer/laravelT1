<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("You're logged in!") }}

                    @if(auth()->check() && auth()->user()->hasRole('admin'))
                        <div class="mt-4">
                            <a href="{{ route('admin.users') }}" class="bg-blue-500 text-white px-4 py-2 rounded ml-2">
                                Manage User Permissions
                            </a>
                        </div>
                    @endif

                    @if(auth()->check() && auth()->user()->can('admin'))
                        <div class="mt-4">
                            <a href="{{ route('admin.dev') }}" class="bg-indigo-500 text-white px-4 py-2 rounded ml-2">
                                Dev
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
