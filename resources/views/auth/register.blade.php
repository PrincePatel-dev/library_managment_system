<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Library Management System</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <i class="bi bi-person-plus fs-1"></i>
                        <h4 class="mt-2 mb-1">Create Account</h4>
                        <p class="text-muted mb-0">Register to access the library system</p>
                    </div>

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-circle"></i>
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form action="{{ route('register.submit') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text"
                                   name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   placeholder="Enter your full name"
                                   value="{{ old('name') }}"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email"
                                   name="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   placeholder="Enter your email"
                                   value="{{ old('email') }}"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password"
                                   name="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   placeholder="Create a password"
                                   required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Confirm Password</label>
                            <input type="password"
                                   name="password_confirmation"
                                   class="form-control"
                                   placeholder="Confirm your password"
                                   required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-check-circle"></i> Register
                        </button>
                    </form>

                    <div class="text-center mt-4">
                        <small class="text-muted">Already have an account?</small>
                        <a href="{{ route('login') }}" class="ms-1">Login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
