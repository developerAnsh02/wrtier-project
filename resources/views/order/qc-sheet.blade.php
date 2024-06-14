@extends('layouts.app')
@section('content')

<livewire:QcSheet />
<style>
    /* Custom horizontal scrollbar */
    .table-card {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch; 
    }

    .table-card::-webkit-scrollbar {
        height: 8px; 
    }

    .table-card::-webkit-scrollbar-track {
        background: #f1f1f1; 
        border-radius: 10px;
    }

    .table-card::-webkit-scrollbar-thumb {
        background: #888; 
        border-radius: 10px;
    }

    .table-card::-webkit-scrollbar-thumb:hover {
        background: #555;
    }

</style>
@endsection