@extends('layouts.app')
@section('content')
    <!-- component -->

    <div class="max-w-lg mx-auto mt-4  bg-white dark:bg-gray-800 rounded-lg shadow-md px-8 py-10 flex flex-col items-center">
        <div class="w-full flex justify-between mx-5">
            <div class=""></div>
            <a href="{{ route('users') }}" class="btn bg-red-700 rounded-xl px-6 py-3 text-white">Back</a>
        </div>
        <h1 class="text-xl font-bold text-center text-gray-700 dark:text-gray-200 mb-8">Welcome to Laravel-Firebase</h1>
        <form action="{{ route('user-store') }}" method="POST" class="w-full flex flex-col gap-4">
            @csrf
            <div class="flex items-start flex-col justify-start">
                <label for="firstName" class="text-sm text-gray-700 dark:text-gray-200 mr-2">First Name:</label>
                <input placeholder="First Name" type="text" id="firstName" name="firstName"
                    class="w-full px-3 dark:text-gray-200 dark:bg-gray-900 py-2 rounded-md border border-gray-300 dark:border-gray-700 focus:outline-none focus:ring-1 focus:ring-blue-500">
            </div>
            @error('firstName')
                <div class="text-red-600">{{ $message }}</div>
            @enderror
            <div class="flex items-start flex-col justify-start">
                <label for="lastName" class="text-sm text-gray-700 dark:text-gray-200 mr-2">Last Name:</label>
                <input placeholder="Last Name" type="text" id="lastName" name="lastName"
                    class="w-full px-3 dark:text-gray-200 dark:bg-gray-900 py-2 rounded-md border border-gray-300 dark:border-gray-700 focus:outline-none focus:ring-1 focus:ring-blue-500">
            </div>
            @error('lastName')
                <div class="text-red-600">{{ $message }}</div>
            @enderror
            <div class="flex items-start flex-col justify-start">
                <label for="email" class="text-sm text-gray-700 dark:text-gray-200 mr-2">Email:</label>
                <input placeholder="anu@any.any" type="email" id="email" name="email"
                    class="w-full px-3 dark:text-gray-200 dark:bg-gray-900 py-2 rounded-md border border-gray-300 dark:border-gray-700 focus:outline-none focus:ring-1 focus:ring-blue-500">
            </div>
            @if (isset($error))
                <div class="text-red-600">{{ $error }}</div>
            @endif
            @error('email')
                <div class="text-red-600">{{ $message }}</div>
            @enderror
            <div class="flex items-start flex-col justify-start">
                <label for="password" class="text-sm text-gray-700 dark:text-gray-200 mr-2">Password:</label>
                <input placeholder="*****" type="password" id="password" name="password"
                    class="w-full px-3 dark:text-gray-200 dark:bg-gray-900 py-2 rounded-md border border-gray-300 dark:border-gray-700 focus:outline-none focus:ring-1 focus:ring-blue-500">
            </div>
            @error('password')
                <div class="text-red-600">{{ $message }}</div>
            @enderror
            <div class="flex items-start flex-col justify-start">
                <label for="password_confirmation" class="text-sm text-gray-700 dark:text-gray-200 mr-2">Confirm
                    Password:</label>
                <input placeholder="*****" type="password" id="password_confirmation" name="password_confirmation"
                    class="w-full px-3 dark:text-gray-200 dark:bg-gray-900 py-2 rounded-md border border-gray-300 dark:border-gray-700 focus:outline-none focus:ring-1 focus:ring-blue-500">
            </div>

            <button type="submit"
                class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-md shadow-sm">Save</button>
        </form>
    </div>
@endsection
