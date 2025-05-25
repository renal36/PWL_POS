@extends('layouts.template')

@section('content')
<div class="container mt-4">
    <h1>{{ $breadcrumb->title }}</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            @foreach ($breadcrumb->list as $item)
                <li class="breadcrumb-item">{{ $item }}</li>
            @endforeach
        </ol>
    </nav>

    <p>Selamat datang di halaman utama aplikasi kamu.</p>
</div>
@endsection
