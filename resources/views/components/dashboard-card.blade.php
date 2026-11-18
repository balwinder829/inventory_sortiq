@props(['route','bg','icon','title','subtitle' => null])

<a href="{{ route($route) }}" class="text-decoration-none">
    <div class="card shadow-sm rounded-3 border-0 h-100 hover-card">
        <div class="card-body d-flex align-items-center">
            <span class="bg-{{ $bg }} text-white avatar avatar-lg rounded-circle me-3 d-flex align-items-center justify-content-center">
                <i class="fa-solid {{ $icon }} fa-lg"></i>
            </span>
            <div>
                <h5 class="mb-1 fw-bold text-dark">{{ $title }}</h5>

                @if($subtitle)
                    <small class="text-muted">{{ $subtitle }}</small>
                @endif
            </div>
        </div>
    </div>
</a>
