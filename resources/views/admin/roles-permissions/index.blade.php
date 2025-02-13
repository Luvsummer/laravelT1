<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Manage Roles & Permissions
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">

                <!-- 显示消息 -->
                @if(session('success'))
                    <div class="bg-green-500 text-white px-4 py-2 mb-4 rounded">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="bg-red-500 text-white px-4 py-2 mb-4 rounded">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- 添加角色 -->
                <h2 class="text-xl font-bold mb-2">Add Role</h2>
                <form method="POST" action="{{ route('admin.roles.store') }}" class="mb-6 flex gap-2">
                    @csrf
                    <input type="text" name="name" placeholder="Role name" required class="border px-4 py-2 rounded">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Add Role</button>
                </form>

                <!-- 显示角色列表（表格） -->
                <h3 class="text-lg font-bold mt-6 mb-2">Existing Roles</h3>
                <table class="w-full border-collapse border border-gray-500">
                    <thead>
                    <tr class="bg-gray-200">
                        <th class="border border-gray-500 px-4 py-2 text-left">Role Name</th>
                        <th class="border border-gray-500 px-4 py-2 text-center">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($roles as $role)
                        <tr class="border border-gray-500">
                            <td class="border border-gray-500 px-4 py-2">{{ $role->name }}</td>
                            <td class="border border-gray-500 px-4 py-2 text-center">
                                @if($role->name !== 'admin')
                                    <form method="POST" action="{{ route('admin.roles.delete', $role) }}" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded text-sm">Delete</button>
                                    </form>
                                @else
                                    <span class="text-gray-500">Protected</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                <hr class="my-6">

                <!-- 添加权限 -->
                <h2 class="text-xl font-bold mb-2">Add Permission</h2>
                <form method="POST" action="{{ route('admin.permissions.store') }}" class="mb-6 flex gap-2">
                    @csrf
                    <input type="text" name="name" placeholder="Permission name" required class="border px-4 py-2 rounded">
                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Add Permission</button>
                </form>

                <!-- 显示权限列表（表格） -->
                <h3 class="text-lg font-bold mt-6 mb-2">Existing Permissions</h3>
                <table class="w-full border-collapse border border-gray-500">
                    <thead>
                    <tr class="bg-gray-200">
                        <th class="border border-gray-500 px-4 py-2 text-left">Permission Name</th>
                        <th class="border border-gray-500 px-4 py-2 text-center">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($permissions as $permission)
                        <tr class="border border-gray-500">
                            <td class="border border-gray-500 px-4 py-2">{{ $permission->name }}</td>
                            <td class="border border-gray-500 px-4 py-2 text-center">
                                <form method="POST" action="{{ route('admin.permissions.delete', $permission) }}" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded text-sm">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                <!-- 返回按钮 -->
                <div class="mt-6">
                    <a href="{{ route('admin.users') }}" class="bg-gray-500 text-white px-4 py-2 rounded">
                        Back To Manage User Permissions
                    </a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
