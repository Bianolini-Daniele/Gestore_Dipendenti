@extends('layouts.app')

@section('title', 'Modifica dipendente')

@section('content')
    <h1 class="mb-3">
        Modifica {{ $anagrafica->nome_completo }}
    </h1>

    <form
        action="{{ route('anagrafiche.update', $anagrafica) }}"
        method="POST"
        enctype="multipart/form-data"
    >
        @csrf
        @method('PUT')

        @include('anagrafiche._form')
    </form>
@endsection