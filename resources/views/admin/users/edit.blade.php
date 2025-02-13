<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Edit Permissions for {{ $user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <h2 class="text-xl font-bold">Edit Permissions for {{ $user->name }}</h2>

                    <form method="POST" action="{{ route('admin.users.update', $user) }}" class="mt-4">
                        @csrf

                        <div class="mb-4">
                            <label class="block font-bold mb-2">Roles:</label>
                            @foreach($roles as $role)
                            <div class="flex items-center">
                                <input type="checkbox" name="roles[]" value="{{ $role->name }}"
                                       class="mr-2" {{ $user->hasRole($role->name) ? 'checked' : '' }}
                                @if($role->name === 'admin' && !auth()->user()->hasRole('admin')) disabled @endif>
                                <span>{{ $role->name }}</span>
                            </div>
                            @endforeach
                        </div>

                        <div class="mb-4">
                            <label class="block font-bold mb-2">Permissions:</label>
                            @foreach($permissions as $permission)
                            <div class="flex items-center">
                                <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                       class="mr-2" {{ $user->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                                <span>{{ $permission->name }}</span>
                            </div>
                            @endforeach
                        </div>

                        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">
                            Save
                        </button>
                    </form>

                    <div class="mt-4">
                        <a href="{{ route('admin.users') }}" class="bg-gray-500 text-white px-4 py-2 rounded">
                            Back to Manage User Permissions
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
