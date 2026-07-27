@extends('layouts.app')

@section('title', 'Modifica documento')

@section('content')
    <h1 class="mb-3">
        Modifica documento — {{ $anagrafica->nome_completo }}
    </h1>

    <form
        action="{{ route('anagrafiche.documenti.update', [$anagrafica, $documento]) }}"
        method="POST"
        enctype="multipart/form-data"
    >
        @csrf
        @method('PUT')
        @include('documenti._form')
    </form>
@endsection