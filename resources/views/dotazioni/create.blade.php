@extends('layouts.app')

@section('title', 'Nuova dotazione')

@section('content')
    <h1 class="mb-3">
        Nuova dotazione — {{ $anagrafica->nome_completo }}
    </h1>

    <form action="{{ route('anagrafiche.dotazioni.store', $anagrafica) }}" method="POST">
        @csrf
        @include('dotazioni._form')
    </form>
@endsection