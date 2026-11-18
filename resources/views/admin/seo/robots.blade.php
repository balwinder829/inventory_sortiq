@include('admin.layouts.header')

<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold py-1 mb-1">
                <span class="text-muted fw-light">SEO Management /</span> Robots.txt Controller
            </h4>
            <p class="text-muted mb-0 small">
                Instruct web crawlers and search engine robots (Googlebot, Bingbot, Baiduspider) which areas of your portal to crawl.
            </p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ url('/robots.txt') }}" target="_blank" class="btn btn-outline-primary">
                <i class="bx bx-link-external me-1"></i> View Live robots.txt
            </a>
            <a href="{{ route('admin.seo.index') }}" class="btn btn-outline-secondary">
                <i class="bx bx-arrow-back me-1"></i> SEO Pages
            </a>
        </div>
    </div>

    <!-- Alert -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bx bx-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="bx bx-file text-primary me-1"></i> Edit <code>public/robots.txt</code>
                    </h5>
                    <span class="badge bg-label-info font-monospace">UTF-8 Plain Text</span>
                </div>
                <div class="card-body pt-3">
                    <form action="{{ route('admin.seo.robots.update') }}" method="POST">
                        @csrf

                        <!-- Global Site Title Suffix -->
                        <div class="mb-4">
                            <label class="form-label small fw-bold">Global Site Title Suffix</label>
                            <input type="text" 
                                   name="site_title_suffix" 
                                   value="{{ old('site_title_suffix', $settings->site_title_suffix ?? ' | StockFlow Systems') }}" 
                                   class="form-control" 
                                   placeholder="e.g.  | StockFlow Systems">
                            <small class="text-muted">Appended automatically to meta titles across all pages.</small>
                        </div>

                        <!-- Directives Quick-Insert Toolbar -->
                        <div class="mb-2">
                            <label class="form-label small fw-bold d-block">Quick Insert Presets:</label>
                            <div class="btn-group btn-group-sm mb-2" role="group">
                                <button type="button" class="btn btn-outline-secondary" onclick="appendDirective('\nUser-agent: *\nDisallow: /admin/\nDisallow: /portal/\nAllow: /\n')">
                                    + Disallow Admin & Portal
                                </button>
                                <button type="button" class="btn btn-outline-secondary" onclick="appendDirective('\nSitemap: {{ url('/sitemap.xml') }}\n')">
                                    + Insert Sitemap URL
                                </button>
                                <button type="button" class="btn btn-outline-secondary" onclick="appendDirective('\nUser-agent: GPTBot\nDisallow: /\n\nUser-agent: CCBot\nDisallow: /\n')">
                                    + Block AI Scrapers
                                </button>
                            </div>
                        </div>

                        <!-- Robots Codearea -->
                        <div class="mb-3">
                            <textarea name="robots_txt_content" 
                                      id="robotsTextarea" 
                                      rows="12" 
                                      class="form-control font-monospace text-dark bg-light border" 
                                      style="font-size: 13px; line-height: 1.6;" 
                                      required>{{ old('robots_txt_content', $content) }}</textarea>
                            <small class="text-muted">
                                Changes are saved to database and synchronized directly to <code>public/robots.txt</code>.
                            </small>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bx bx-save me-1"></i> Save & Apply robots.txt
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Help Sidebar -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="card-title mb-0"><i class="bx bx-info-circle text-primary me-1"></i> Common Syntax Guide</h6>
                </div>
                <div class="card-body pt-3 small text-muted">
                    <p class="mb-2">
                        <strong>User-agent:</strong> specifies which bot the rule applies to (<code>*</code> means all crawlers).
                    </p>
                    <p class="mb-2">
                        <strong>Disallow:</strong> tells search engines not to visit that URL or folder.
                    </p>
                    <p class="mb-2">
                        <strong>Allow:</strong> permits crawling within an otherwise disallowed directory.
                    </p>
                    <p class="mb-3">
                        <strong>Sitemap:</strong> points crawlers directly to your XML sitemap URL.
                    </p>

                    <div class="alert alert-light border small mb-0">
                        <strong>Sample Configuration:</strong><br>
                        <code>User-agent: *</code><br>
                        <code>Disallow: /admin/</code><br>
                        <code>Disallow: /portal/</code><br>
                        <code>Disallow: /login</code><br>
                        <code>Allow: /</code><br><br>
                        <code>Sitemap: {{ url('/sitemap.xml') }}</code>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function appendDirective(text) {
        const area = document.getElementById('robotsTextarea');
        area.value = area.value.trim() + "\n" + text.trim() + "\n";
        area.focus();
    }
</script>

@include('admin.layouts.footer')
