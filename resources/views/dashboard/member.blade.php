@extends('layouts.app')

@section('title', 'My Dashboard')

@section('content')
    <div class="mb-4">
        <h1 class="h3 fw-bold mb-0">Welcome, {{ auth()->user()->name }}</h1>
        <p class="text-muted mb-0">Track your borrowed books and explore the catalog.</p>
    </div>

    <div class="d-flex gap-2 mb-4">
        <a href="{{ route('books.index') }}" class="btn btn-primary" style="background-color:#6f42c1;border-color:#6f42c1;"><i class="bi bi-search"></i> Browse Catalog</a>
        <a href="{{ route('borrows.index') }}" class="btn btn-outline-secondary"><i class="bi bi-journal-text"></i> All My Loans</a>
    </div>

    <div class="card mb-4">
        <div class="card-header bg-white fw-semibold">Currently Borrowed ({{ $activeBorrows->count() }})</div>
        <div class="table-responsive">
            <table class="table mb-0 align-middle">
                <thead>
                    <tr>
                        <th>Book</th>
                        <th>Borrowed</th>
                        <th>Due</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($activeBorrows as $borrow)
                        <tr>
                            <td>
                                <a href="{{ route('books.show', $borrow->book) }}" class="text-decoration-none">{{ $borrow->book->title }}</a>
                            </td>
                            <td>{{ $borrow->borrowed_at->format('M d, Y') }}</td>
                            <td>{{ $borrow->due_date->format('M d, Y') }}</td>
                            <td>
                                @if ($borrow->isOverdue())
                                    <span class="badge bg-danger badge-status">Overdue</span>
                                @else
                                    <span class="badge bg-primary badge-status">Borrowed</span>
                                @endif
                            </td>
                            <td>
                                <form method="POST" action="{{ route('borrows.return', $borrow) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-success">Return</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">You don't have any books checked out.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-white fw-semibold">Recently Returned</div>
        <div class="table-responsive">
            <table class="table mb-0 align-middle">
                <thead>
                    <tr>
                        <th>Book</th>
                        <th>Borrowed</th>
                        <th>Returned</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($borrowHistory as $borrow)
                        <tr>
                            <td>{{ $borrow->book->title }}</td>
                            <td>{{ $borrow->borrowed_at->format('M d, Y') }}</td>
                            <td>{{ $borrow->returned_at->format('M d, Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-4">No return history yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
