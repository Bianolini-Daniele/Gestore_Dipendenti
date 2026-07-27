@extends('layouts.app')

@section('title', 'Modifica dotazione')

@section('content')
    <h1 class="mb-3">
        Modifica dotazione — {{ $anagrafica->nome_completo }}
    </h1>

    <form action="{{ route('anagrafiche.dotazioni.update', [$anagrafica, $dotazione]) }}" method="POST">
        @csrf
        @method('PUT')
        @include('dotazioni._form')
    </form>
@endsection