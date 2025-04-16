@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-10">
    <div class="bg-white shadow-lg rounded-2xl p-8">
        <div class="flex flex-col md:flex-row items-center md:items-start gap-6">
            {{-- Selfie --}}
            @if ($user->selfie)
                <img src="{{ asset('storage/' . $user->selfie) }}" alt="User selfie"
                    class="w-32 h-32 rounded-full object-cover ring-4 ring-gray-300">
            @else
                <div class="w-32 h-32 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 text-4xl font-bold">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
            @endif

            {{-- User Info --}}
            <div class="w-full">
                <h2 class="text-3xl font-bold text-gray-800">{{ $user->name }} {{ $user->surname }}</h2>
                <p class="text-sm text-gray-500 mb-2">{{ $user->email }}</p>
                <p class="text-sm text-gray-500">{{ $user->phone }}</p>

                <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700">
                    <div>
                        <span class="font-semibold">Country:</span> {{ $user->country }}
                    </div>
                    <div>
                        <span class="font-semibold">Gender:</span> {{ ucfirst($user->gender) }}
                    </div>
                    <div>
                        <span class="font-semibold">Joined:</span> {{ $user->created_at->format('M d, Y') }}
                    </div>
                </div>

                @if ($user->introduction)
                    <div class="mt-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-1">Introduction</h3>
                        <p class="text-gray-700">{{ $user->introduction }}</p>
                    </div>
                @endif

                <div class="mt-6 flex gap-3">
                    <a href="{{ route('users.edit', $user->id) }}"
                        class="px-4 py-2 bg-blue-500 text-white text-sm font-semibold rounded hover:bg-blue-600 transition">Edit</a>
                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="px-4 py-2 bg-red-500 text-white text-sm font-semibold rounded hover:bg-red-600 transition">
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
