@include('admin.layouts.header')

<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="container" style="max-width: 600px;">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="fw-bold mb-0">
                    <i class="fa fa-store me-2"></i> Create Store
                </h4>

                <a href="{{ route('stores.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fa fa-arrow-left me-1"></i> Back
                </a>
            </div>

            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body">

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>Whoops! There were some issues:</strong>
                            <ul class="mt-2 mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>- {{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('stores.store') }}" method="POST">
                        @csrf

                        <!-- Store Name -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Store Name</label>
                            <input type="text" name="name" class="form-control" required placeholder="Enter store name">
                        </div>

                        <!-- Assign User -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Assign to User (Optional)</label>
                            <select name="user_id" class="form-select">
                                <option value="">-- Select User --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">
                                        {{ ucfirst($user->name) }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-end mt-3">
                            <button class="btn btn-primary">
                                <i class="fa fa-save me-1"></i> Create Store
                            </button>

                            <a href="{{ route('stores.index') }}" class="btn btn-secondary ms-2">
                                Cancel
                            </a>
                        </div>

                    </form>

                </div>
            </div>

        </div>

    </div>
</div>

@include('admin.layouts.footer')
