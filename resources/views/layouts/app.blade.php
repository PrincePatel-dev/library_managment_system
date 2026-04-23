<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Library Management System')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @yield('styles')
</head>
<body class="bg-light">

    <div class="container-fluid">
        <div class="row min-vh-100">
            <aside class="col-md-3 col-lg-2 bg-white border-end p-0">
                <div class="p-3 border-bottom fw-bold">
                    <i class="bi bi-book"></i> Library System
                </div>

                <div class="list-group list-group-flush rounded-0">
                    @auth
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}"
                               class="list-group-item list-group-item-action {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                                <i class="bi bi-speedometer2 me-2"></i>Dashboard
                            </a>
                            <a href="{{ route('admin.books.index') }}"
                               class="list-group-item list-group-item-action {{ request()->routeIs('admin.books.*') ? 'active' : '' }}">
                                <i class="bi bi-book me-2"></i>Books
                            </a>
                            <a href="{{ route('admin.issues.index') }}"
                               class="list-group-item list-group-item-action {{ request()->routeIs('admin.issues.index') ? 'active' : '' }}">
                                <i class="bi bi-journal-check me-2"></i>Issued Books
                            </a>
                            <a href="{{ route('admin.issues.create') }}"
                               class="list-group-item list-group-item-action {{ request()->routeIs('admin.issues.create') ? 'active' : '' }}">
                                <i class="bi bi-plus-square me-2"></i>Issue Book
                            </a>
                            <a href="{{ route('admin.issues.overdue') }}"
                               class="list-group-item list-group-item-action {{ request()->routeIs('admin.issues.overdue') ? 'active' : '' }}">
                                <i class="bi bi-exclamation-triangle me-2"></i>Overdue Books
                            </a>
                        @else
                            <a href="{{ route('student.dashboard') }}"
                               class="list-group-item list-group-item-action {{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
                                <i class="bi bi-speedometer2 me-2"></i>Dashboard
                            </a>
                            <a href="{{ route('student.books') }}"
                               class="list-group-item list-group-item-action {{ request()->routeIs('student.books') ? 'active' : '' }}">
                                <i class="bi bi-search me-2"></i>Browse Books
                            </a>
                            <a href="{{ route('student.my-books') }}"
                               class="list-group-item list-group-item-action {{ request()->routeIs('student.my-books') ? 'active' : '' }}">
                                <i class="bi bi-bookmark-check me-2"></i>My Books
                            </a>
                        @endif

                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="list-group-item list-group-item-action text-danger border-0 bg-white text-start w-100">
                                <i class="bi bi-box-arrow-left me-2"></i>Logout
                            </button>
                        </form>
                    @endauth
                </div>
            </aside>

            <main class="col-md-9 col-lg-10 p-0">
                <nav class="navbar navbar-light bg-white border-bottom px-3">
                    <span class="navbar-brand mb-0 h6">@yield('page-title', 'Library Management System')</span>
                    @auth
                        <div>
                            <i class="bi bi-person-circle"></i>
                            <strong>{{ auth()->user()->name }}</strong>
                            <span class="badge bg-{{ auth()->user()->role === 'admin' ? 'danger' : 'primary' }} ms-1">
                                {{ ucfirst(auth()->user()->role) }}
                            </span>
                        </div>
                    @endauth
                </nav>

                <div class="p-3">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-circle"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    @yield('scripts')
</body>
</html>
