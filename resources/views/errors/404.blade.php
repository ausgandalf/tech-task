@extends('layouts.app')

@section('content')
<div class="flex flex-col items-center justify-center px-6 py-20">
    <div class="text-center">
        <h1 class="ml-4 text-9xl text-gray-500 uppercase font-extrabold">404</h1>
        <p class="text-2xl mt-4 font-semibold text-gray-700">Oops! Page not found.</p>
        <p class="mt-2 text-gray-500">The page you are looking for doesn’t exist or has been moved.</p>

        <div class="mt-6">
            <a href="{{ route('users.index') }}" class="text-blue-600 hover:underline">&larr; Back to user list</a>
        </div>
    </div>
</div>
@endsection
