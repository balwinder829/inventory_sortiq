@include('admin.layouts.header')

<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="page-body">

            <div class="container-xl">

                <div class="text-center py-5">

                    <h1 class="text-danger">403 - Unauthorized</h1>

                    <p>You are not authorized to view this page.</p>

                    <a href="{{ url()->previous() }}" class="btn btn-primary mt-3">
                        Go Back
                    </a>

                </div>

            </div>

        </div>
    </div>
</div>

@include('admin.layouts.footer')