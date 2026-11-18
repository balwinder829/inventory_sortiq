@include('admin.layouts.header')

<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold py-1 mb-1">
                <span class="text-muted fw-light">SEO Management /</span> XML Sitemap Generator & Manager
            </h4>
            <p class="text-muted mb-0 small">
                Search engines read your XML sitemap to intelligently crawl all public pages and products.
            </p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ url('/sitemap.xml') }}" target="_blank" class="btn btn-outline-primary">
                <i class="bx bx-link-external me-1"></i> View Live sitemap.xml
            </a>
            <form action="{{ route('admin.seo.sitemap.generate') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary">
                    <i class="bx bx-refresh me-1"></i> Rebuild & Save Sitemap
                </button>
            </form>
        </div>
    </div>

    <!-- Alert -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bx bx-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Stats -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body">
                    <span class="text-muted small fw-semibold d-block">Total Indexed URLs</span>
                    <h3 class="card-title mb-0 mt-1 text-primary">{{ count($urls) }}</h3>
                    <small class="text-muted">Pages & public products</small>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body">
                    <span class="text-muted small fw-semibold d-block">File Last Generated</span>
                    <h5 class="card-title mb-0 mt-1 text-dark">{{ $lastUpdated }}</h5>
                    <small class="text-muted">Stored at <code>public/sitemap.xml</code></small>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body">
                    <span class="text-muted small fw-semibold d-block">Search Engine Protocol</span>
                    <h5 class="card-title mb-0 mt-1 text-success">Sitemaps 0.9 XML</h5>
                    <small class="text-muted">Google, Bing & Yandex compliant</small>
                </div>
            </div>
        </div>
    </div>

    <!-- URLs Table -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
            <h5 class="card-title mb-0">URLs Included in Sitemap</h5>
            <span class="badge bg-label-success">{{ count($urls) }} Entries</span>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Page / Resource</th>
                        <th>Full Location (URL)</th>
                        <th>Change Frequency</th>
                        <th>Priority</th>
                        <th>Last Modified</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($urls as $u)
                        <tr>
                            <td>
                                <strong class="text-dark">{{ $u['title'] ?? 'Page' }}</strong>
                            </td>
                            <td>
                                <a href="{{ $u['loc'] }}" target="_blank" class="font-monospace small text-primary text-decoration-none">
                                    {{ $u['loc'] }} <i class="bx bx-link-external fs-6"></i>
                                </a>
                            </td>
                            <td>
                                <span class="badge bg-label-info">{{ $u['changefreq'] }}</span>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark fw-bold">{{ $u['priority'] }}</span>
                            </td>
                            <td class="small text-muted font-monospace">
                                {{ date('Y-m-d H:i', strtotime($u['lastmod'])) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Raw XML Preview -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
            <h6 class="card-title mb-0"><i class="bx bx-code-alt text-primary me-1"></i> Generated XML Code Preview</h6>
            <button class="btn btn-sm btn-outline-secondary" onclick="navigator.clipboard.writeText(document.getElementById('xmlPreview').innerText); alert('Sitemap XML copied to clipboard!');">
                <i class="bx bx-copy me-1"></i> Copy XML
            </button>
        </div>
        <div class="card-body p-0">
            <pre class="m-0 p-3 bg-light text-dark font-monospace small" id="xmlPreview" style="max-height: 380px; overflow-y: auto;">{{ $xmlContent }}</pre>
        </div>
    </div>
</div>

@include('admin.layouts.footer')
