@extends('layouts.app')

@section('title', 'Add Book')

@section('content')
    <a href="{{ route('books.index') }}" class="text-decoration-none small d-inline-block mb-3">
        <i class="bi bi-arrow-left"></i> Back to catalog
    </a>

    <div class="card">
        <div class="card-header bg-white fw-semibold">Add a New Book</div>
        <div class="card-body p-4">
            <form method="POST" action="{{ route('books.store') }}">
                @csrf
                @include('books._form')
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary" style="background-color:#6f42c1;border-color:#6f42c1;">
                        <i class="bi bi-check-lg"></i> Save Book
                    </button>
                    <a href="{{ route('books.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
