<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('User Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <table class="table-auto w-full border-collapse border border-gray-500 mt-4">
                        <thead>
                        <tr class="bg-gray-200">
                            <th class="border border-gray-500 px-4 py-2">Name</th>
                            <th class="border border-gray-500 px-4 py-2">Email</th>
                            <th class="border border-gray-500 px-4 py-2">Roles</th>
                            <th class="border border-gray-500 px-4 py-2">Permissions</th>
                            <th class="border border-gray-500 px-4 py-2">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $user)
                            <tr class="border border-gray-500">
                                <td class="border border-gray-500 px-4 py-2">{{ $user->name }}</td>
                                <td class="border border-gray-500 px-4 py-2">{{ $user->email }}</td>
                                <td class="border border-gray-500 px-4 py-2">{{ implode(', ', $user->getRoleNames()->toArray()) }}</td>
                                <td class="border border-gray-500 px-4 py-2">{{ implode(', ', $user->getPermissionNames()->toArray()) }}</td>
                                <td class="border border-gray-500 px-4 py-2">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="bg-blue-500 text-white px-4 py-2 rounded">Edit</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <div class="mt-4">
                        <div class="mb-4">
                            <a href="{{ route('admin.roles-permissions.index') }}" class="bg-blue-500 text-white px-4 py-2 rounded ml-2">
                                Manage Roles & Permissions
                            </a>
                            <a href="{{ route('dashboard') }}" class="bg-gray-500 text-white px-4 py-2 rounded ml-2">
                                Back to Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
