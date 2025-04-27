@extends('layouts.app')

@section('content')
    <div class="max-w-xl mx-auto mt-20 p-8 bg-red-50 border border-red-300 rounded-xl shadow text-center">
        <h1 class="text-3xl font-bold text-red-700 mb-4">⚠️ Link Expired</h1>
        <p class="text-lg text-gray-700">Sorry, this booking link is no longer valid.</p>
        <p class="mt-2">It may have already been paid, deactivated, or expired.</p>
        <p class="mt-6 text-sm text-gray-500">Please contact support if you believe this is an error.</p>
    </div>
@endsection
