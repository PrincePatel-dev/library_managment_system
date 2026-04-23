@extends('layouts.app')

@section('title', 'Issue a Book')
@section('page-title', 'Issue a Book')

@section('content')

    <div class="row justify-content-center">
        <div class="col-md-7">

            <div class="card">
                <div class="card-header fw-semibold">
                    <i class="bi bi-journal-plus"></i> Issue Book to Student
                </div>
                <div class="card-body">

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.issues.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Select Book <span class="text-danger">*</span>
                            </label>
                            <select name="book_id" class="form-select" required>
                                <option value="">-- Select a Book --</option>
                                @foreach($books as $book)
                                    <option value="{{ $book->id }}" {{ old('book_id') == $book->id ? 'selected' : '' }}>
                                        {{ $book->title }} by {{ $book->author }}
                                        (Available: {{ $book->available_quantity }})
                                    </option>
                                @endforeach
                            </select>
                            @if($books->isEmpty())
                                <small class="text-danger">No books are currently available to issue.</small>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Select Student <span class="text-danger">*</span>
                            </label>
                            <select name="user_id" class="form-select" required>
                                <option value="">-- Select a Student --</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}" {{ old('user_id') == $student->id ? 'selected' : '' }}>
                                        {{ $student->name }} ({{ $student->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Auto-calculated Info -->
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i>
                            <strong>Issue Date:</strong> Today ({{ now()->format('d M Y') }}) |
                            <strong>Due Date:</strong> {{ now()->addDays(15)->format('d M Y') }}
                            (Fixed 15 days from today)
                        </div>

                        <button type="submit" class="btn btn-success" {{ $books->isEmpty() ? 'disabled' : '' }}>
                            <i class="bi bi-journal-check"></i> Issue Book
                        </button>
                        <a href="{{ route('admin.issues.index') }}" class="btn btn-secondary ms-2">
                            <i class="bi bi-arrow-left"></i> Cancel
                        </a>

                    </form>
                </div>
            </div>

        </div>
    </div>

@endsection
