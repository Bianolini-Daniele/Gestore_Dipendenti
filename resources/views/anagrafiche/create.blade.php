@extends('layouts.app')

@section('title', 'Nuovo dipendente')

@section('content')
    <h1 class="mb-3">Nuovo dipendente</h1>

    <form
        action="{{ route('anagrafiche.store') }}"
        method="POST"
        enctype="multipart/form-data"
    >
        @csrf

        @include('anagrafiche._form')
    </form>
@endsection