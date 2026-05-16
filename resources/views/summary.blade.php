@extends('layouts.app')

@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-700">📊 Summary & Analytics</h1>
    <p class="text-sm text-gray-400 mt-1">Filter your data and see live charts</p>
</div>

@livewire('summary-charts')

@endsection
