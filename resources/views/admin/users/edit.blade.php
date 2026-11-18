@include('admin.layouts.header')

<div class="content-wrapper">
    <div class="container-xxl container-p-y">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold">
                <i class="fa fa-user-edit me-2"></i> Edit User
            </h4>

            <a href="{{ route('user.list') }}" class="btn btn-secondary">
                <i class="fa fa-arrow-left"></i> Back
            </a>
        </div>

        <div class="container" style="max-width: 600px;">
            <div class="card shadow-sm">
                <div class="card-body">

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.user.update', $user->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control"
                                   value="{{ old('name', $user->name) }}" required>
                        </div>

                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control"
                                   value="{{ old('email', $user->email) }}" required>
                        </div>

                        <div class="mb-3">
                            <label>New Password (optional)</label>
                            <input type="password" name="password" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label>Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control">
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Status</label>
                            @if($user->id === auth()->id())
                                <select name="is_active" class="form-select" disabled>
                                    <option value="1" selected>Active (You cannot deactivate yourself)</option>
                                </select>
                                <input type="hidden" name="is_active" value="1">
                            @else
                                <select name="is_active" class="form-select" required>
                                    <option value="1" {{ old('is_active', $user->is_active) == '1' ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ old('is_active', $user->is_active) == '0' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            @endif
                        </div>

                        <!-- <div class="form-check mb-3">
                            <input type="checkbox" name="is_superadmin" value="1"
                                   class="form-check-input"
                                   {{ $user->is_superadmin ? 'checked' : '' }}>
                            <label class="form-check-label">Super Admin</label>
                        </div> -->

                        <button class="btn btn-primary w-100">
                            <i class="fa fa-save"></i> Update User
                        </button>

                    </form>

                </div>
            </div>
        </div>

    </div>
</div>

@include('admin.layouts.footer')