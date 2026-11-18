@include('admin.layouts.header')

<div class="content-wrapper">
  <!-- Content -->
  <div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">

       <div class="col-12 col-lg-12 order-2 order-md-3 order-lg-2 mb-4">
      <div class="card">
        <div class="row row-bordered g-0">

          <!-- Card Header -->
          <div class="col-md-12">
            <h5 class="card-header m-0 me-2 pb-3">Update Profile</h5>
          </div>


      <!-- Profile Update -->
      <div class="col-md-6">
        <div class="card mb-4">
          <div class="card-header">Update Profile</div>
          <div class="card-body">
            <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
              @csrf
              <div class="text-center mb-3">
                <img src="{{ $user->profile_image ? asset($user->profile_image) : asset('assets/images/default_avatar.png') }}"
                     class="rounded-circle"
                     width="100"
                     height="100"
                     alt="Profile">
              </div>
              <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" name="name" value="{{ $user->name }}" class="form-control" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Profile Image</label>
                <input type="file" name="profile_image" class="form-control">
              </div>
              <button type="submit" class="btn btn-primary">Update Profile</button>
            </form>
          </div>
        </div>
      </div>

      <!-- Password Update -->
        <div class="col-md-6">
          <div class="card mb-4">
            <div class="card-header">Change Password</div>
            <div class="card-body">
              <form action="{{ route('admin.profile.password') }}" method="POST">
                @csrf

                <div class="mb-3">
                  <label class="form-label">Current Password</label>
                  <input type="password" name="current_password" placeholder="Current Password" class="form-control" required>
                  @error('current_password')
                    <small class="text-danger">{{ $message }}</small>
                  @enderror
                </div>

                <div class="mb-3">
                  <label class="form-label">New Password</label>
                  <input type="password" name="password" class="form-control" placeholder="New Password"  required>
                  @error('password')
                    <small class="text-danger">{{ $message }}</small>
                  @enderror
                </div>

                <div class="mb-3">
                  <label class="form-label">Confirm Password</label>
                  <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm Password"  required>
                  @error('password_confirmation')
                    <small class="text-danger">{{ $message }}</small>
                  @enderror
                </div>

                <button type="submit" class="btn btn-warning">Update Password</button>
              </form>
            </div>
          </div>
        </div>

      </div>
      </div>
      </div>

    </div>
  </div>
</div>

@include('admin.layouts.footer')
