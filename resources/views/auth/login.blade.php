<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Library Management System</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <i class="bi bi-book-half fs-1"></i>
                        <h4 class="mt-2 mb-1">Library Management System</h4>
                        <p class="text-muted mb-0">Please login to continue</p>
                    </div>

                    <!-- Error Messages -->
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-circle"></i>
                            {{ $errors->first() }}
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle"></i> {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-circle"></i> {{ session('error') }}
                        </div>
                    @endif

                    <!-- Login Form -->
                    <form action="{{ route('login.submit') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email"
                                       name="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       placeholder="Enter your email"
                                       value="{{ old('email') }}"
                                       required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password"
                                       name="password"
                                       class="form-control"
                                       placeholder="Enter your password"
                                       required>
                            </div>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" name="remember" id="remember">
                            <label class="form-check-label" for="remember">Remember Me</label>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-box-arrow-in-right"></i> Login
                        </button>
                    </form>

                    <div class="text-center mt-4">
                        <small class="text-muted">Don't have an account?</small>
                        <a href="{{ route('register') }}" class="ms-1">Register</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
