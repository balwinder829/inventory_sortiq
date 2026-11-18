@include('admin.layouts.header')

<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Breadcrumb & Title -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold py-1 mb-1">
                <span class="text-muted fw-light">SEO Management /</span> Edit Page Metadata: {{ $page->page_name }}
            </h4>
            <p class="text-muted mb-0 small">
                Configure Google search appearance, indexing directives, and social share cards for <code>{{ $page->path }}</code>.
            </p>
        </div>
        <a href="{{ route('admin.seo.index') }}" class="btn btn-outline-secondary">
            <i class="bx bx-arrow-back me-1"></i> Back to Directory
        </a>
    </div>

    <!-- Error Alert -->
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="bx bx-error me-1"></i> Please check the form errors below.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <!-- Main Form Column -->
        <div class="col-lg-8">
            <form action="{{ route('admin.seo.update', $page->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- 1. Search Engine Appearance Card -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h5 class="card-title mb-0">
                            <i class="bx bx-search-alt text-primary me-1"></i> Search Engine Optimization (Google SERP)
                        </h5>
                    </div>
                    <div class="card-body pt-3 space-y-4">
                        
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Page Reference Name</label>
                                <input type="text" name="page_name" value="{{ old('page_name', $page->page_name) }}" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Target Route / URL Path</label>
                                <input type="text" name="path" id="seoPath" value="{{ old('path', $page->path) }}" class="form-control font-monospace" required>
                            </div>
                        </div>

                        <!-- Meta Title Input -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label class="form-label small fw-bold mb-0">
                                    Meta Title <span class="text-muted fw-normal">(Recommended 50-60 characters)</span>
                                </label>
                                <span id="titleCounter" class="badge bg-label-secondary">0 / 60</span>
                            </div>
                            <input type="text" 
                                   name="meta_title" 
                                   id="metaTitleInput" 
                                   value="{{ old('meta_title', $page->meta_title) }}" 
                                   class="form-control" 
                                   placeholder="e.g. StockFlow Systems - Enterprise Inventory Sourcing Portal" 
                                   maxlength="255">
                            <small class="text-muted">
                                Appends site suffix: <code>{{ $settings->site_title_suffix ?? ' | StockFlow Systems' }}</code>
                            </small>
                        </div>

                        <!-- Meta Description Input -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label class="form-label small fw-bold mb-0">
                                    Meta Description <span class="text-muted fw-normal">(Recommended 150-160 characters)</span>
                                </label>
                                <span id="descCounter" class="badge bg-label-secondary">0 / 160</span>
                            </div>
                            <textarea name="meta_description" 
                                      id="metaDescInput" 
                                      rows="3" 
                                      class="form-control" 
                                      placeholder="Write a clear, compelling summary of this page for search results..." 
                                      maxlength="1000">{{ old('meta_description', $page->meta_description) }}</textarea>
                        </div>

                        <!-- Focus Keywords -->
                        <div class="mb-3">
                            <label class="form-label small fw-bold mb-1">Focus Meta Keywords</label>
                            <input type="text" 
                                   name="meta_keywords" 
                                   value="{{ old('meta_keywords', $page->meta_keywords) }}" 
                                   class="form-control" 
                                   placeholder="e.g. inventory management, warehouse distribution, stock tracking">
                            <small class="text-muted">Comma-separated key phrases relevant to this page.</small>
                        </div>

                        <!-- Canonical URL -->
                        <div class="mb-3">
                            <label class="form-label small fw-bold mb-1">Canonical URL Override</label>
                            <input type="url" 
                                   name="canonical_url" 
                                   value="{{ old('canonical_url', $page->canonical_url) }}" 
                                   class="form-control font-monospace" 
                                   placeholder="Leave blank to use default page URL ({{ url($page->path === '/' ? '' : $page->path) }})">
                        </div>

                    </div>
                </div>

                <!-- 2. Social Media & Open Graph Card -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h5 class="card-title mb-0">
                            <i class="bx bxl-facebook-circle text-info me-1"></i> Social Media & Open Graph (Facebook, Twitter, LinkedIn)
                        </h5>
                    </div>
                    <div class="card-body pt-3">
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Open Graph Title (og:title)</label>
                            <input type="text" 
                                   name="og_title" 
                                   value="{{ old('og_title', $page->og_title) }}" 
                                   class="form-control" 
                                   placeholder="Defaults to Meta Title if left blank">
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold">Open Graph Description (og:description)</label>
                            <textarea name="og_description" 
                                      rows="2" 
                                      class="form-control" 
                                      placeholder="Defaults to Meta Description if left blank">{{ old('og_description', $page->og_description) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold">Social Share Image URL (og:image)</label>
                            <input type="text" 
                                   name="og_image" 
                                   value="{{ old('og_image', $page->og_image) }}" 
                                   class="form-control" 
                                   placeholder="/assets/inventory/navbarlogo.png">
                            <small class="text-muted">Relative path or complete HTTPS image link (recommended 1200x630px).</small>
                        </div>
                    </div>
                </div>

                <!-- 3. Indexing & XML Sitemap Card -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h5 class="card-title mb-0">
                            <i class="bx bx-sitemap text-success me-1"></i> Crawling Directives & XML Sitemap
                        </h5>
                    </div>
                    <div class="card-body pt-3">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Robots Meta Directive</label>
                                <select name="robots" class="form-select">
                                    <option value="index, follow" {{ old('robots', $page->robots) == 'index, follow' ? 'selected' : '' }}>index, follow (Standard Public)</option>
                                    <option value="noindex, follow" {{ old('robots', $page->robots) == 'noindex, follow' ? 'selected' : '' }}>noindex, follow (Hide from search, follow links)</option>
                                    <option value="noindex, nofollow" {{ old('robots', $page->robots) == 'noindex, nofollow' ? 'selected' : '' }}>noindex, nofollow (Strict Private)</option>
                                    <option value="index, nofollow" {{ old('robots', $page->robots) == 'index, nofollow' ? 'selected' : '' }}>index, nofollow</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Sitemap Change Frequency</label>
                                <select name="changefreq" class="form-select">
                                    <option value="always" {{ old('changefreq', $page->changefreq) == 'always' ? 'selected' : '' }}>always</option>
                                    <option value="hourly" {{ old('changefreq', $page->changefreq) == 'hourly' ? 'selected' : '' }}>hourly</option>
                                    <option value="daily" {{ old('changefreq', $page->changefreq) == 'daily' ? 'selected' : '' }}>daily</option>
                                    <option value="weekly" {{ old('changefreq', $page->changefreq) == 'weekly' ? 'selected' : '' }}>weekly</option>
                                    <option value="monthly" {{ old('changefreq', $page->changefreq) == 'monthly' ? 'selected' : '' }}>monthly</option>
                                    <option value="yearly" {{ old('changefreq', $page->changefreq) == 'yearly' ? 'selected' : '' }}>yearly</option>
                                    <option value="never" {{ old('changefreq', $page->changefreq) == 'never' ? 'selected' : '' }}>never</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Sitemap Priority (0.0 to 1.0)</label>
                                <input type="number" 
                                       step="0.1" 
                                       min="0.0" 
                                       max="1.0" 
                                       name="priority" 
                                       value="{{ old('priority', number_format($page->priority, 1)) }}" 
                                       class="form-control" 
                                       required>
                                <small class="text-muted">1.0 = Highest (e.g. Home), 0.8 = Core Page, 0.5 = Standard</small>
                            </div>

                            <div class="col-md-6 d-flex align-items-center pt-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           name="is_in_sitemap" 
                                           value="1" 
                                           id="inSitemapToggle" 
                                           {{ old('is_in_sitemap', $page->is_in_sitemap) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold small ms-2" for="inSitemapToggle">
                                        Include this page in <code>sitemap.xml</code>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button Bar -->
                <div class="d-flex justify-content-between align-items-center mb-5">
                    <a href="{{ route('admin.seo.index') }}" class="btn btn-outline-secondary">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bx bx-check me-1"></i> Save Page SEO Settings
                    </button>
                </div>
            </form>
        </div>

        <!-- Sidebar Preview Column (Google SERP Simulator) -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 sticky-top" style="top: 90px;">
                <div class="card-header bg-white py-3 border-bottom d-flex align-items-center gap-2">
                    <i class="bx bxl-google text-danger fs-4"></i>
                    <h6 class="card-title mb-0">Live Google Search Preview</h6>
                </div>
                <div class="card-body pt-3">
                    <p class="text-muted small mb-3">
                        How your page snippet will appear in Google and search engine rankings:
                    </p>

                    <!-- Realistic Google SERP Card -->
                    <div class="p-3 bg-white rounded border shadow-xs" style="max-width: 100%; font-family: arial, sans-serif;">
                        <!-- Favicon + Display URL -->
                        <div class="d-flex align-items-center gap-2 mb-1" style="font-size: 12px; color: #202124;">
                            <span class="rounded-circle bg-light d-flex align-items-center justify-content-center" style="width: 24px; height: 24px; font-weight: bold; color: #2874F0;">
                                S
                            </span>
                            <div class="text-truncate">
                                <span class="fw-semibold d-block text-dark" style="line-height: 1.2;">StockFlow Systems</span>
                                <span class="text-muted text-truncate d-block" id="previewUrl" style="line-height: 1.2; font-size: 11px;">
                                    {{ url($page->path === '/' ? '' : $page->path) }}
                                </span>
                            </div>
                        </div>

                        <!-- Snippet Title -->
                        <h6 class="mb-1 text-primary text-truncate" id="previewTitle" style="color: #1a0dab !important; font-size: 17px; line-height: 1.3; font-weight: 500; cursor: pointer;">
                            {{ $page->meta_title ?: 'StockFlow Systems - Premium Corporate Inventory' }}
                        </h6>

                        <!-- Snippet Description -->
                        <p class="mb-0 text-muted" id="previewDesc" style="color: #4d5156 !important; font-size: 13px; line-height: 1.45; word-wrap: break-word;">
                            {{ $page->meta_description ?: 'Real-time multi-warehouse stock intelligence, automated procurement workflows, and enterprise inventory logistics.' }}
                        </p>
                    </div>

                    <!-- Guidelines -->
                    <div class="mt-4 pt-3 border-top">
                        <h6 class="fw-bold small mb-2 text-dark"><i class="bx bx-bulb text-warning me-1"></i> Best Practice Guidelines:</h6>
                        <ul class="text-muted small ps-3 mb-0 space-y-1">
                            <li>Keep title under <strong>60 characters</strong> to prevent ellipsis truncation.</li>
                            <li>Meta description should be between <strong>140 and 160 characters</strong> with your main keyword.</li>
                            <li>Use unique titles for each page to avoid search cannibalization.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const titleInput = document.getElementById('metaTitleInput');
        const descInput = document.getElementById('metaDescInput');
        const titleCounter = document.getElementById('titleCounter');
        const descCounter = document.getElementById('descCounter');
        const previewTitle = document.getElementById('previewTitle');
        const previewDesc = document.getElementById('previewDesc');
        const siteSuffix = "{{ $settings->site_title_suffix ?? ' | StockFlow Systems' }}";

        function updateTitle() {
            const val = titleInput.value.trim();
            const len = val.length;
            titleCounter.textContent = len + ' / 60';
            if (len > 60) {
                titleCounter.className = 'badge bg-label-warning';
            } else {
                titleCounter.className = 'badge bg-label-secondary';
            }
            previewTitle.textContent = val ? (val + siteSuffix) : 'StockFlow Systems - Page Title';
        }

        function updateDesc() {
            const val = descInput.value.trim();
            const len = val.length;
            descCounter.textContent = len + ' / 160';
            if (len > 160) {
                descCounter.className = 'badge bg-label-warning';
            } else {
                descCounter.className = 'badge bg-label-secondary';
            }
            previewDesc.textContent = val || 'Your page description will appear here in Google search engine results...';
        }

        titleInput.addEventListener('input', updateTitle);
        descInput.addEventListener('input', updateDesc);

        updateTitle();
        updateDesc();
    });
</script>

@include('admin.layouts.footer')
