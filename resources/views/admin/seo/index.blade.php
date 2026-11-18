@include('admin.layouts.header')

<div class="container-xxl flex-grow-1 container-p-y">
    
    <!-- Top Breadcrumb & Actions Bar -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h4 class="fw-bold py-1 mb-1">
                <span class="text-muted fw-light">SEO Management /</span> Page Meta & Search Directives
            </h4>
            <p class="text-muted mb-0 small">
                Select any frontend page to configure its Google search title, meta description, keywords, and sitemap settings.
            </p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.seo.robots') }}" class="btn btn-outline-secondary">
                <i class="bx bx-bot me-1"></i> Robots.txt Editor
            </a>
            <a href="{{ route('admin.seo.sitemap') }}" class="btn btn-outline-primary">
                <i class="bx bx-sitemap me-1"></i> XML Sitemap
            </a>
            <button type="button" class="btn btn-outline-dark" data-bs-toggle="collapse" data-bs-target="#addCustomPageCollapse">
                <i class="bx bx-plus me-1"></i> Add Custom Page
            </button>
        </div>
    </div>

    <!-- Flash Notifications -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bx bx-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="bx bx-error me-1"></i> Please check the form errors:
            <ul class="mb-0 mt-1 small">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Add Custom Page Modal/Collapse -->
    <div class="collapse mb-4" id="addCustomPageCollapse">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-light py-3">
                <h5 class="card-title mb-0"><i class="bx bx-plus-circle text-primary me-1"></i> Register New Custom URL for SEO</h5>
            </div>
            <div class="card-body pt-3">
                <form action="{{ route('admin.seo.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Page Identifier (Key) <span class="text-danger">*</span></label>
                            <input type="text" name="page_key" class="form-control" placeholder="e.g. wholesale_deals" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Display Title <span class="text-danger">*</span></label>
                            <input type="text" name="page_name" class="form-control" placeholder="e.g. Wholesale Deals" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Route Path <span class="text-danger">*</span></label>
                            <input type="text" name="path" class="form-control font-monospace" placeholder="e.g. /deals" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Robots</label>
                            <select name="robots" class="form-select">
                                <option value="index, follow">index, follow</option>
                                <option value="noindex, follow">noindex, follow</option>
                                <option value="noindex, nofollow">noindex, nofollow</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Meta Title</label>
                            <input type="text" name="meta_title" class="form-control" placeholder="Max 60 characters recommended">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Focus Keywords</label>
                            <input type="text" name="meta_keywords" class="form-control" placeholder="Comma separated keywords">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold">Meta Description</label>
                            <textarea name="meta_description" rows="2" class="form-control" placeholder="150-160 characters summary"></textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Frequency</label>
                            <select name="changefreq" class="form-select">
                                <option value="daily">daily</option>
                                <option value="weekly" selected>weekly</option>
                                <option value="monthly">monthly</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Priority (0.0 to 1.0)</label>
                            <input type="number" step="0.1" min="0.0" max="1.0" name="priority" value="0.8" class="form-control">
                        </div>
                        <div class="col-md-4 d-flex align-items-center pt-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_in_sitemap" value="1" id="customInSitemapCheck" checked>
                                <label class="form-check-label fw-bold small" for="customInSitemapCheck">Include in Sitemap</label>
                            </div>
                        </div>
                        <div class="col-12 text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-check me-1"></i> Register Custom Page
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Interactive Master-Detail Studio -->
    <div class="row g-4">
        
        <!-- Left Column: Pages Directory & Selector -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 sticky-top" style="top: 85px;">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0 fw-bold">Frontend Pages</h6>
                        <small class="text-muted">Click any page to edit SEO</small>
                    </div>
                    <span class="badge bg-label-primary font-monospace">{{ $pages->count() }} Pages</span>
                </div>

                <!-- Search Filter Input -->
                <div class="p-3 border-bottom bg-light">
                    <div class="input-group input-group-merge">
                        <span class="input-group-text"><i class="bx bx-search"></i></span>
                        <input type="text" id="pageFilterInput" class="form-control" placeholder="Search page name or path...">
                    </div>
                </div>

                <!-- Pages List Navigation -->
                <div class="list-group list-group-flush" id="pagesNavList" style="max-height: 640px; overflow-y: auto;">
                    @foreach($pages as $p)
                        <button type="button" 
                                onclick="selectPage({{ $p->id }})" 
                                id="page-nav-{{ $p->id }}" 
                                class="list-group-item list-group-item-action page-nav-item py-3 px-3 d-flex justify-content-between align-items-center border-bottom {{ $selectedPage && $selectedPage->id == $p->id ? 'active bg-label-primary border-primary fw-bold' : '' }}">
                            <div class="d-flex flex-column text-start me-2">
                                <span class="fw-bold page-nav-name text-truncate" style="max-width: 200px;">
                                    {{ $p->page_name }}
                                </span>
                                <span class="small font-monospace text-muted page-nav-path">
                                    {{ $p->path }}
                                </span>
                            </div>
                            <div class="text-end shrink-0">
                                @if(str_contains($p->robots, 'noindex'))
                                    <span class="badge bg-label-warning text-uppercase" style="font-size: 9px;">noindex</span>
                                @else
                                    <span class="badge bg-label-success text-uppercase" style="font-size: 9px;">index</span>
                                @endif
                            </div>
                        </button>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Right Column: SEO Configuration & Google SERP Simulator -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-2">
                        <span class="avatar avatar-sm bg-label-primary rounded d-flex align-items-center justify-content-center">
                            <i class="bx bx-slider fs-4"></i>
                        </span>
                        <div>
                            <h5 class="card-title mb-0" id="editorHeaderTitle">
                                Editing SEO for: <span class="text-primary" id="editorPageNameTitle">{{ $selectedPage->page_name ?? 'Select Page' }}</span>
                            </h5>
                            <span class="small text-muted font-monospace" id="editorPathSubtitle">
                                {{ $selectedPage->path ?? '/' }}
                            </span>
                        </div>
                    </div>
                    <a href="{{ url($selectedPage->path ?? '/') }}" target="_blank" id="viewLivePageBtn" class="btn btn-sm btn-outline-secondary">
                        <i class="bx bx-link-external me-1"></i> Preview Page
                    </a>
                </div>

                <div class="card-body pt-4">
                    <form id="seoEditForm" action="{{ route('admin.seo.update', $selectedPage->id ?? 1) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <input type="hidden" name="page_name" id="formPageName" value="{{ $selectedPage->page_name ?? '' }}">
                        <input type="hidden" name="path" id="formPath" value="{{ $selectedPage->path ?? '' }}">

                        <!-- 1. Meta Title -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label class="form-label fw-bold mb-0 text-dark">
                                    Meta Title <span class="text-danger">*</span>
                                </label>
                                <span id="titleCounterBadge" class="badge bg-label-secondary font-monospace">0 / 60 characters</span>
                            </div>
                            <input type="text" 
                                   name="meta_title" 
                                   id="metaTitleField" 
                                   value="{{ $selectedPage->meta_title ?? '' }}" 
                                   class="form-control form-control-lg" 
                                   placeholder="e.g. StockFlow Systems - Premium Corporate Inventory Portal" 
                                   required>
                            <div class="d-flex justify-content-between mt-1">
                                <small class="text-muted">Appends brand suffix: <code>{{ $settings->site_title_suffix ?? ' | StockFlow Systems' }}</code></small>
                                <small class="text-muted">Ideal length: 50 - 60 chars</small>
                            </div>
                        </div>

                        <!-- 2. Meta Description -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label class="form-label fw-bold mb-0 text-dark">
                                    Meta Description <span class="text-danger">*</span>
                                </label>
                                <span id="descCounterBadge" class="badge bg-label-secondary font-monospace">0 / 160 characters</span>
                            </div>
                            <textarea name="meta_description" 
                                      id="metaDescField" 
                                      rows="3" 
                                      class="form-control" 
                                      placeholder="Write a compelling search engine summary that describes this page and drives clicks..." 
                                      required>{{ $selectedPage->meta_description ?? '' }}</textarea>
                            <div class="d-flex justify-content-between mt-1">
                                <small class="text-muted">Displayed under the title in search engine result pages.</small>
                                <small class="text-muted">Ideal length: 140 - 160 chars</small>
                            </div>
                        </div>

                        <!-- 3. Meta Keywords -->
                        <div class="mb-4">
                            <label class="form-label fw-bold mb-1 text-dark">
                                Focus Meta Keywords
                            </label>
                            <input type="text" 
                                   name="meta_keywords" 
                                   id="metaKeywordsField" 
                                   value="{{ $selectedPage->meta_keywords ?? '' }}" 
                                   class="form-control" 
                                   placeholder="e.g. inventory management, warehouse distribution, stock tracking, b2b sourcing">
                            <small class="text-muted">Comma-separated key phrases relevant to this page content.</small>
                        </div>

                        <!-- 4. Live Google SERP Preview Box -->
                        <div class="mb-4 p-3 bg-light rounded border">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="small fw-bold text-dark text-uppercase d-flex align-items-center gap-1">
                                    <i class="bx bxl-google text-danger fs-5"></i> Live Google Search Result Preview
                                </span>
                                <span class="badge bg-white text-muted border">Real-time Preview</span>
                            </div>

                            <div class="p-3 bg-white rounded border shadow-xs" style="font-family: Arial, sans-serif;">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center fw-bold text-primary" style="width: 22px; height: 22px; font-size: 11px;">
                                        S
                                    </div>
                                    <div class="text-truncate">
                                        <span class="d-block fw-semibold text-dark" style="font-size: 13px; line-height: 1.2;">StockFlow Systems</span>
                                        <span class="d-block text-muted text-truncate font-monospace" id="serpDisplayUrl" style="font-size: 11px; line-height: 1.2;">
                                            {{ url($selectedPage->path ?? '/') }}
                                        </span>
                                    </div>
                                </div>
                                <h6 class="mb-1 text-primary text-truncate" id="serpDisplayTitle" style="color: #1a0dab !important; font-size: 18px; line-height: 1.3; font-weight: 500; cursor: pointer;">
                                    {{ $selectedPage->meta_title ?? 'StockFlow Systems - Page Title' }}
                                </h6>
                                <p class="mb-0 text-muted" id="serpDisplayDesc" style="color: #4d5156 !important; font-size: 13px; line-height: 1.45; word-wrap: break-word;">
                                    {{ $selectedPage->meta_description ?? 'Your page summary will be presented here in Google and search engines.' }}
                                </p>
                            </div>
                        </div>

                        <!-- Accordion for Advanced Directives (Robots, Sitemap, Open Graph) -->
                        <div class="accordion mb-4" id="advancedSeoAccordion">
                            <div class="accordion-item border shadow-none">
                                <h2 class="accordion-header" id="headingAdv">
                                    <button class="accordion-button collapsed fw-bold small text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAdv">
                                        <i class="bx bx-cog me-2 text-primary"></i> Advanced Directives: Robots, XML Sitemap & Social Cards
                                    </button>
                                </h2>
                                <div id="collapseAdv" class="accordion-collapse collapse" data-bs-parent="#advancedSeoAccordion">
                                    <div class="accordion-body pt-3">
                                        <div class="row g-3">
                                            <!-- Robots -->
                                            <div class="col-md-6">
                                                <label class="form-label small fw-bold">Robots Meta Directive</label>
                                                <select name="robots" id="robotsField" class="form-select">
                                                    <option value="index, follow">index, follow (Standard Public)</option>
                                                    <option value="noindex, follow">noindex, follow (Hide, follow links)</option>
                                                    <option value="noindex, nofollow">noindex, nofollow (Private/Hidden)</option>
                                                    <option value="index, nofollow">index, nofollow</option>
                                                </select>
                                            </div>

                                            <!-- Canonical -->
                                            <div class="col-md-6">
                                                <label class="form-label small fw-bold">Canonical URL (Optional)</label>
                                                <input type="url" name="canonical_url" id="canonicalField" value="{{ $selectedPage->canonical_url ?? '' }}" class="form-control font-monospace" placeholder="https://...">
                                            </div>

                                            <!-- Frequency -->
                                            <div class="col-md-4">
                                                <label class="form-label small fw-bold">Sitemap Frequency</label>
                                                <select name="changefreq" id="changefreqField" class="form-select">
                                                    <option value="always">always</option>
                                                    <option value="hourly">hourly</option>
                                                    <option value="daily">daily</option>
                                                    <option value="weekly">weekly</option>
                                                    <option value="monthly">monthly</option>
                                                </select>
                                            </div>

                                            <!-- Priority -->
                                            <div class="col-md-4">
                                                <label class="form-label small fw-bold">Sitemap Priority</label>
                                                <input type="number" step="0.1" min="0.0" max="1.0" name="priority" id="priorityField" value="{{ number_format($selectedPage->priority ?? 0.8, 1) }}" class="form-control">
                                            </div>

                                            <!-- In Sitemap Toggle -->
                                            <div class="col-md-4 d-flex align-items-center pt-3">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" name="is_in_sitemap" value="1" id="inSitemapField" {{ !empty($selectedPage->is_in_sitemap) ? 'checked' : '' }}>
                                                    <label class="form-check-label small fw-bold" for="inSitemapField">Include in sitemap.xml</label>
                                                </div>
                                            </div>

                                            <!-- Open Graph Social Fields -->
                                            <div class="col-md-6">
                                                <label class="form-label small fw-bold">Open Graph Title (og:title)</label>
                                                <input type="text" name="og_title" id="ogTitleField" value="{{ $selectedPage->og_title ?? '' }}" class="form-control" placeholder="Defaults to Meta Title">
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label small fw-bold">Open Graph Image (og:image)</label>
                                                <input type="text" name="og_image" id="ogImageField" value="{{ $selectedPage->og_image ?? '' }}" class="form-control" placeholder="/assets/inventory/navbarlogo.png">
                                            </div>

                                            <div class="col-12">
                                                <label class="form-label small fw-bold">Open Graph Description (og:description)</label>
                                                <textarea name="og_description" id="ogDescField" rows="2" class="form-control" placeholder="Defaults to Meta Description">{{ $selectedPage->og_description ?? '' }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Bar -->
                        <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                            <span class="small text-muted">
                                Changes are saved instantly to the database and will reflect across the site.
                            </span>
                            <button type="submit" class="btn btn-primary btn-lg px-4 shadow-sm">
                                <i class="bx bx-save me-1"></i> Save Page SEO Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Embedded JSON Dataset for Fast In-Page Selection -->
<script>
    const allPagesData = @json($pages);
    const baseUrl = "{{ url('/') }}";
    const siteSuffix = "{{ $settings->site_title_suffix ?? ' | StockFlow Systems' }}";

    function selectPage(pageId) {
        const page = allPagesData.find(p => p.id == pageId);
        if (!page) return;

        // 1. Update left navigation active states
        document.querySelectorAll('.page-nav-item').forEach(el => {
            el.classList.remove('active', 'bg-label-primary', 'border-primary', 'fw-bold');
        });
        const activeNav = document.getElementById('page-nav-' + pageId);
        if (activeNav) {
            activeNav.classList.add('active', 'bg-label-primary', 'border-primary', 'fw-bold');
        }

        // 2. Update form action URL
        const form = document.getElementById('seoEditForm');
        form.action = "{{ url('admin/seo') }}/" + page.id;

        // 3. Update Titles & Subtitles
        document.getElementById('editorPageNameTitle').textContent = page.page_name;
        document.getElementById('editorPathSubtitle').textContent = page.path;
        document.getElementById('formPageName').value = page.page_name;
        document.getElementById('formPath').value = page.path;

        // 4. Update preview link
        const targetPath = page.path === '/' ? '' : page.path.replace(/^\//, '');
        const fullUrl = baseUrl + (targetPath ? '/' + targetPath : '');
        document.getElementById('viewLivePageBtn').href = fullUrl;
        document.getElementById('serpDisplayUrl').textContent = fullUrl;

        // 5. Populate Form Fields
        document.getElementById('metaTitleField').value = page.meta_title || '';
        document.getElementById('metaDescField').value = page.meta_description || '';
        document.getElementById('metaKeywordsField').value = page.meta_keywords || '';
        document.getElementById('canonicalField').value = page.canonical_url || '';
        document.getElementById('robotsField').value = page.robots || 'index, follow';
        document.getElementById('changefreqField').value = page.changefreq || 'weekly';
        document.getElementById('priorityField').value = parseFloat(page.priority || 0.8).toFixed(1);
        document.getElementById('inSitemapField').checked = Boolean(page.is_in_sitemap);
        document.getElementById('ogTitleField').value = page.og_title || '';
        document.getElementById('ogDescField').value = page.og_description || '';
        document.getElementById('ogImageField').value = page.og_image || '';

        // 6. Update Real-time Counters & SERP simulator
        refreshCountersAndPreview();
    }

    function refreshCountersAndPreview() {
        const titleVal = document.getElementById('metaTitleField').value.trim();
        const descVal = document.getElementById('metaDescField').value.trim();

        // Counters
        const tLen = titleVal.length;
        const dLen = descVal.length;
        const titleBadge = document.getElementById('titleCounterBadge');
        const descBadge = document.getElementById('descCounterBadge');

        titleBadge.textContent = tLen + ' / 60 characters';
        titleBadge.className = tLen > 60 ? 'badge bg-label-warning font-monospace' : 'badge bg-label-secondary font-monospace';

        descBadge.textContent = dLen + ' / 160 characters';
        descBadge.className = dLen > 160 ? 'badge bg-label-warning font-monospace' : 'badge bg-label-secondary font-monospace';

        // Google SERP Display
        const fullTitle = titleVal ? (titleVal + siteSuffix) : 'StockFlow Systems - Page Title';
        document.getElementById('serpDisplayTitle').textContent = fullTitle;
        document.getElementById('serpDisplayDesc').textContent = descVal || 'Your page summary will be presented here in Google and search engines.';
    }

    document.addEventListener('DOMContentLoaded', function () {
        // Real-time input listeners
        document.getElementById('metaTitleField').addEventListener('input', refreshCountersAndPreview);
        document.getElementById('metaDescField').addEventListener('input', refreshCountersAndPreview);

        // Instant Filter for Left Navigation
        const filterInput = document.getElementById('pageFilterInput');
        if (filterInput) {
            filterInput.addEventListener('input', function () {
                const term = this.value.toLowerCase().trim();
                document.querySelectorAll('.page-nav-item').forEach(item => {
                    const name = item.querySelector('.page-nav-name').textContent.toLowerCase();
                    const path = item.querySelector('.page-nav-path').textContent.toLowerCase();
                    if (name.includes(term) || path.includes(term)) {
                        item.classList.remove('d-none');
                        item.classList.add('d-flex');
                    } else {
                        item.classList.add('d-none');
                        item.classList.remove('d-flex');
                    }
                });
            });
        }

        // Initialize counters for selected page
        refreshCountersAndPreview();
    });
</script>

@include('admin.layouts.footer')
