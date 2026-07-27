@extends('layouts.app')

@section('title', 'Nuovo documento')

@section('content')
    <h1 class="mb-3">
        Nuovo documento — {{ $anagrafica->nome_completo }}
    </h1>

    <form
        action="{{ route('anagrafiche.documenti.store', $anagrafica) }}"
        method="POST"
        enctype="multipart/form-data"
    >
        @csrf
        @include('documenti._form')
    </form>
@endsection