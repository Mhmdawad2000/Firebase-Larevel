@extends('layouts.app')
@section('content')
    <!-- component -->
    <div class="flex justify-center flex-col m-4">
        <div class="rounded-lg flex justify-between mx-4 container-md bg-gray-100 p-6">
            <h1 class="text-black text-2xl">User List From Firebase</h1>
            <a href="{{ route('user-create') }}" class="hovre:bg-blue-800 rounded-xl btn bg-blue-600 px-6 py-3">Add User</a>
        </div>

        @if (session('msg'))
            <div class="rounded-lg  mx-4 container-md bg-gray-100 p-6">
                <div class="alert alert-info text-green-600">{{ session('msg') }}</div>
            </div>
        @endif
        @if (session('error'))
            <div class="rounded-lg  mx-4 container-md bg-gray-100 p-6">
                <div class="alert alert-danger text-red-600">{{ session('error') }}</div>
            </div>
        @endif



        <div class="flex justify-center shadow-md">
            <table class="pt-2 w-[60%] bg-white text-left text-sm text-gray-500 mt-10">
                <thead class="bg-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-4 font-medium text-gray-900">ID</th>
                        <th scope="col" class="px-6 py-4 font-medium text-gray-900">Name</th>
                        <th scope="col" class="px-6 py-4 font-medium text-gray-900">Email</th>
                        <th scope="col" class="px-6 py-4 font-medium text-gray-900">Password</th>
                        <th scope="col" class="px-6 py-4 font-medium text-gray-900">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 border-t border-gray-100">
                    @foreach ($users as $key => $user)
                        <tr>
                            {{-- ID --}}
                            <th class="px-6 py-4">
                                <div class="text-sm">
                                    <div class="font-medium text-gray-700">{{ $key }}</div>
                                </div>
                            </th>
                            {{-- name --}}
                            <th class="px-6 py-4">
                                <div class="text-sm">
                                    <div class="font-medium text-gray-700">
                                        {{ $user['firstName'] . ' ' . $user['lastName'] }}
                                    </div>
                                </div>
                            </th>
                            {{-- email --}}
                            <td class="px-6 py-4">
                                <div class="text-gray-400">{{ $user['email'] }}</div>
                            </td>
                            {{-- password --}}
                            <td class="px-6 py-4">{{ $user['password'] }}</td>
                            {{-- action --}}
                            <td class="px-6 py-4">
                                <div class="flex justify-end gap-4">
                                    <a href="{{ route('user-delete', $key) }}" class="text-red-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="h-6 w-6" x-tooltip="tooltip">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                        </svg>
                                    </a>
                                    <a href="{{ route('user-update', $key) }}" class="text-green-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="h-6 w-6" x-tooltip="tooltip">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
