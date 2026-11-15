@extends('layouts.app')

@section('title', 'My Loans')

@section('content')
    <h1 class="h3 fw-bold mb-4">My Loans</h1>

    <div class="card">
        <div class="table-responsive">
            <table class="table mb-0 align-middle">
                <thead>
                    <tr>
                        <th>Book</th>
                        <th>Borrowed</th>
                        <th>Due</th>
                        <th>Returned</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($borrows as $borrow)
                        <tr>
                            <td>
                                <a href="{{ route('books.show', $borrow->book) }}" class="text-decoration-none">{{ $borrow->book->title }}</a>
                            </td>
                            <td>{{ $borrow->borrowed_at->format('M d, Y') }}</td>
                            <td>{{ $borrow->due_date->format('M d, Y') }}</td>
                            <td>{{ $borrow->returned_at?->format('M d, Y') ?? '—' }}</td>
                            <td>
                                @if ($borrow->status === 'returned')
                                    <span class="badge bg-success badge-status">Returned</span>
                                @elseif ($borrow->isOverdue())
                                    <span class="badge bg-danger badge-status">Overdue</span>
                                @else
                                    <span class="badge bg-primary badge-status">Borrowed</span>
                                @endif
                            </td>
                            <td>
                                @if ($borrow->status === 'borrowed')
                                    <form method="POST" action="{{ route('borrows.return', $borrow) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-success">Return</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">You haven't borrowed any books yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $borrows->links('pagination::bootstrap-5') }}
    </div>
@endsection
