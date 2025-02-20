@extends('layouts.app')
@section('content')
    <!-- component -->

    <div class="max-w-lg mx-auto mt-4  bg-white dark:bg-gray-800 rounded-lg shadow-md px-8 py-10 flex flex-col items-center">
        <div class="w-full flex justify-between mx-5">
            <div class=""></div>
            <a href="{{ route('users') }}" class="btn bg-red-700 rounded-xl px-6 py-3 text-white">Back</a>
        </div>
        <h1 class="text-xl font-bold text-center text-gray-700 dark:text-gray-200 mb-8">Edit user in Laravel-Firebase</h1>
        <form action="{{ route('user-edit',$key) }}" method="POST" class="w-full flex flex-col gap-4">
            @csrf
            @method('PUT')
            <div class="flex items-start flex-col justify-start">
                <label for="firstName" class="text-sm text-gray-700 dark:text-gray-200 mr-2">First Name:</label>
                <input value="{{ $user['firstName'] }}" placeholder="First Name" type="text" id="firstName"
                    name="firstName"
                    class="w-full px-3 dark:text-gray-200 dark:bg-gray-900 py-2 rounded-md border border-gray-300 dark:border-gray-700 focus:outline-none focus:ring-1 focus:ring-blue-500">
            </div>
            @error('firstName')
                <div class="text-red-600">{{ $message }}</div>
            @enderror
            <div class="flex items-start flex-col justify-start">
                <label for="lastName" class="text-sm text-gray-700 dark:text-gray-200 mr-2">Last Name:</label>
                <input value="{{ $user['lastName'] }}" placeholder="Last Name" type="text" id="lastName" name="lastName"
                    class="w-full px-3 dark:text-gray-200 dark:bg-gray-900 py-2 rounded-md border border-gray-300 dark:border-gray-700 focus:outline-none focus:ring-1 focus:ring-blue-500">
            </div>
            @error('lastName')
                <div class="text-red-600">{{ $message }}</div>
            @enderror
            <button type="submit"
                class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-md shadow-sm">Update</button>
        </form>
    </div>
@endsection
