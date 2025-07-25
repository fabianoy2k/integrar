@extends('layouts.vue')

@section('content')
<div>
    <h1>Teste Vue.js</h1>
    <exportador-contabil></exportador-contabil>
</div>
@endsection

@push('scripts')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
@endpush 