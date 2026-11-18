@include('admin.layouts.header')

<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="container">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="fw-bold mb-0">
                    <i class="fa fa-users me-2"></i> User List
                </h4>

                <!-- <a href="{{ route('user.list') }}" class="btn btn-secondary btn-sm">
                    <i class="fa fa-arrow-left me-1"></i> Back
                </a> -->
            </div>

            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body">

                    <table id="user-table" class="table table-bordered table-striped">
                        <thead class="table-light">
                            <tr>
                                <th><i class="fa fa-user"></i> Name</th>
                                <th><i class="fa fa-envelope"></i> Email</th>
                                <th><i class="fa fa-calendar"></i> Created At</th>
                                <th><i class="fa fa-toggle-on"></i> Status</th>
                                <th> Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>{{ ucfirst($user->name) }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->created_at->format('Y-m-d H:i') }}</td>
                                    <td>
                                        @if($user->is_active)
                                            <span class="badge bg-success"><i class="fa fa-check-circle me-1"></i>Active</span>
                                        @else
                                            <span class="badge bg-danger"><i class="fa fa-times-circle me-1"></i>Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                         <a href="{{ route('admin.user.edit', $user->id) }}" class="btn btn-sm btn-warning">
                                            <i class="fa fa-edit"></i> Edit
                                        </a>
                                         @if(!$user->is_superadmin)
                                            <a href="{{ route('permissions.index', ['user_id' => $user->id]) }}"
                                               class="btn btn-sm btn-primary ms-1">
                                                <i class="fa fa-lock"></i> Permissions
                                            </a>
                                         @endif
                                     
                                       
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>

        </div>

    </div>
</div>

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
<!-- <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css"> -->

@include('admin.layouts.footer')

<!-- <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script> -->
<!-- <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script> -->

<script>
    $(document).ready(function () {
        $('#user-table').DataTable({
            pageLength: 25,
            order: [[2, "desc"]], // Latest users first
        });
    });
</script>
