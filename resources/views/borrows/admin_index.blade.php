@extends('layouts.app')

@section('title', 'All Loans')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h1 class="h3 fw-bold mb-0">All Loans</h1>
        <form method="GET" action="{{ route('admin.borrows.index') }}" class="d-flex gap-2">
            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">All statuses</option>
                <option value="borrowed" @selected(request('status') === 'borrowed')>Borrowed</option>
                <option value="overdue" @selected(request('status') === 'overdue')>Overdue</option>
                <option value="returned" @selected(request('status') === 'returned')>Returned</option>
            </select>
        </form>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table mb-0 align-middle">
                <thead>
                    <tr>
                        <th>Member</th>
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
                            <td>{{ $borrow->user->name }}</td>
                            <td>{{ $borrow->book->title }}</td>
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
                                        <button type="submit" class="btn btn-sm btn-outline-success">Mark Returned</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">No loan records found.</td>
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
