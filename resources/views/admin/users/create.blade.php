@include('admin.layouts.header')

<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold mb-0">
                <i class="fa fa-user-plus me-2"></i> Create User
            </h4>

            <a href="{{ route('user.list') }}" class="btn btn-secondary">
                <i class="fa fa-arrow-left me-1"></i> Back
            </a>
        </div>

        <div class="container" style="max-width: 600px;">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body p-4">

                    <h5 class="fw-bold mb-3 text-center">Add New User</h5>
                    <p class="text-muted text-center mb-4">Fill the details to create a new user</p>

                    <!-- Success Message -->
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <!-- Validation Errors -->
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>There were some problems:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.users') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <i class="fa fa-user me-1"></i> Name
                            </label>
                            <input 
                                type="text" 
                                name="name" 
                                class="form-control"
                                placeholder="Enter Name"
                                value="{{ old('name') }}"
                                required
                            >
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <i class="fa fa-envelope me-1"></i> Email
                            </label>
                            <input 
                                type="email" 
                                name="email" 
                                class="form-control"
                                placeholder="Enter Email"
                                value="{{ old('email') }}"
                                required
                            >
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <i class="fa fa-lock me-1"></i> Password
                            </label>
                            <input 
                                type="password" 
                                name="password" 
                                class="form-control"
                                placeholder="Enter Password"
                                required
                            >
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <i class="fa fa-lock me-1"></i> Confirm Password
                            </label>
                            <input 
                                type="password" 
                                name="password_confirmation" 
                                class="form-control"
                                placeholder="Confirm Password"
                                required
                            >
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                <i class="fa fa-toggle-on me-1"></i> Status
                            </label>
                            <select name="is_active" class="form-select" required>
                                <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>

                        <button class="btn btn-primary w-100">
                            <i class="fa fa-check me-1"></i> Create User
                        </button>

                    </form>

                </div>
            </div>
        </div>

    </div>
</div>

@include('admin.layouts.footer')
