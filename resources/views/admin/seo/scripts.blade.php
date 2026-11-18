@include('admin.layouts.header')

<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold py-1 mb-1">
                <span class="text-muted fw-light">SEO & Web Presence /</span> Header & Footer Tracking Scripts
            </h4>
            <p class="text-muted mb-0 small">
                Embed Google Analytics, Google Tag Manager, Meta Pixel, and live chat scripts into your frontend website.
            </p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ url('/') }}" target="_blank" class="btn btn-outline-primary">
                <i class="bx bx-link-external me-1"></i> Preview Live Website
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

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="bx bx-error me-1"></i> Please check input errors:
            <ul class="mb-0 mt-1 small">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form action="{{ route('admin.seo.scripts.update') }}" method="POST">
        @csrf

        <div class="row">
            <div class="col-lg-8">
                
                <!-- 1. Google Analytics Quick Setup Card -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-2">
                            <span class="avatar avatar-sm bg-label-warning rounded d-flex align-items-center justify-content-center">
                                <i class="bx bxl-google fs-4"></i>
                            </span>
                            <h5 class="card-title mb-0">Google Analytics (GA4) Tracking</h5>
                        </div>
                        @if(!empty($settings->google_analytics_id))
                            <span class="badge bg-label-success">Active & Tracking</span>
                        @else
                            <span class="badge bg-label-secondary">Not Configured</span>
                        @endif
                    </div>
                    <div class="card-body pt-3">
                        <p class="text-muted small mb-3">
                            Enter your Google Analytics 4 Measurement ID. StockFlow will automatically inject the official asynchronous <code>gtag.js</code> tracking container on every page.
                        </p>
                        <div class="mb-2">
                            <label class="form-label small fw-bold">Google Measurement ID</label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="bx bx-barcode"></i></span>
                                <input type="text" 
                                       name="google_analytics_id" 
                                       id="gaIdInput" 
                                       value="{{ old('google_analytics_id', $settings->google_analytics_id ?? '') }}" 
                                       class="form-control font-monospace" 
                                       placeholder="G-XXXXXXXXXX">
                            </div>
                            <small class="text-muted">Find this in your Google Analytics Admin &gt; Data Streams &gt; Measurement ID.</small>
                        </div>
                    </div>
                </div>

                <!-- 2. Header Scripts (<head>) Card -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title mb-0">
                                <i class="bx bx-code-alt text-primary me-1"></i> Header Tracking Scripts (Inside <code>&lt;head&gt;</code>)
                            </h5>
                            <small class="text-muted">Executed before page content loads</small>
                        </div>
                        <span class="badge bg-label-primary font-monospace">&lt;head&gt;</span>
                    </div>
                    <div class="card-body pt-3">
                        <p class="text-muted small mb-2">
                            Add scripts that must run early in the document lifecycle (e.g. Google Tag Manager container, Meta Pixel, Hotjar, Microsoft Clarity).
                        </p>

                        <!-- Presets Toolbar -->
                        <div class="mb-2">
                            <span class="small fw-bold text-muted d-block mb-1">Quick Snippet Presets:</span>
                            <div class="btn-group btn-group-sm mb-2" role="group">
                                <button type="button" class="btn btn-outline-secondary" onclick="insertHeaderPreset('gtm')">
                                    + Google Tag Manager
                                </button>
                                <button type="button" class="btn btn-outline-secondary" onclick="insertHeaderPreset('meta_pixel')">
                                    + Meta Pixel (Facebook)
                                </button>
                                <button type="button" class="btn btn-outline-secondary" onclick="insertHeaderPreset('clarity')">
                                    + Microsoft Clarity
                                </button>
                            </div>
                        </div>

                        <div class="mb-2">
                            <textarea name="header_scripts" 
                                      id="headerScriptsArea" 
                                      rows="9" 
                                      class="form-control font-monospace text-dark bg-light border" 
                                      style="font-size: 13px; line-height: 1.5;" 
                                      placeholder="<script>&#10;  // Your custom header scripts here&#10;</script>">{{ old('header_scripts', $settings->header_scripts ?? '') }}</textarea>
                            <small class="text-muted">Must include complete tags like <code>&lt;script&gt;...&lt;/script&gt;</code> or <code>&lt;link ...&gt;</code>.</small>
                        </div>
                    </div>
                </div>

                <!-- 3. Footer Scripts (before </body>) Card -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title mb-0">
                                <i class="bx bx-code-curly text-success me-1"></i> Footer Tracking Scripts (Before <code>&lt;/body&gt;</code>)
                            </h5>
                            <small class="text-muted">Executed after page DOM finishes loading</small>
                        </div>
                        <span class="badge bg-label-success font-monospace">&lt;/body&gt;</span>
                    </div>
                    <div class="card-body pt-3">
                        <p class="text-muted small mb-2">
                            Ideal for live chat widgets, conversion tracking pixels, or non-blocking external scripts (e.g. Tawk.to, Crisp, Intercom, GTM noscript).
                        </p>

                        <!-- Presets Toolbar -->
                        <div class="mb-2">
                            <span class="small fw-bold text-muted d-block mb-1">Quick Snippet Presets:</span>
                            <div class="btn-group btn-group-sm mb-2" role="group">
                                <button type="button" class="btn btn-outline-secondary" onclick="insertFooterPreset('gtm_noscript')">
                                    + GTM &lt;noscript&gt;
                                </button>
                                <button type="button" class="btn btn-outline-secondary" onclick="insertFooterPreset('chat')">
                                    + Live Chat Template
                                </button>
                            </div>
                        </div>

                        <div class="mb-3">
                            <textarea name="footer_scripts" 
                                      id="footerScriptsArea" 
                                      rows="9" 
                                      class="form-control font-monospace text-dark bg-light border" 
                                      style="font-size: 13px; line-height: 1.5;" 
                                      placeholder="<script>&#10;  // Your custom footer/body scripts here&#10;</script>">{{ old('footer_scripts', $settings->footer_scripts ?? '') }}</textarea>
                            <small class="text-muted">Rendered directly before the closing <code>&lt;/body&gt;</code> tag on every frontend page.</small>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary btn-lg px-4 shadow-sm">
                                <i class="bx bx-save me-1"></i> Save Tracking Scripts
                            </button>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Sidebar Info & Verification Column -->
            <div class="col-lg-4">
                
                <div class="card shadow-sm border-0 mb-4 sticky-top" style="top: 85px;">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="card-title mb-0"><i class="bx bx-shield-check text-success me-1"></i> Implementation Guide</h6>
                    </div>
                    <div class="card-body pt-3 small text-muted">
                        <h6 class="fw-bold text-dark mb-1">How Tracking Scripts Work:</h6>
                        <p class="mb-3">
                            Any code entered in these fields is stored securely in the database and injected directly into the HTML structure of the public frontend website.
                        </p>

                        <div class="p-3 bg-light rounded border mb-3">
                            <strong class="text-dark d-block mb-1">Placement Breakdown:</strong>
                            <ul class="ps-3 mb-0">
                                <li class="mb-1"><strong>Google ID:</strong> Automatically builds the official Google Analytics 4 container.</li>
                                <li class="mb-1"><strong>Header Scripts:</strong> Injected between <code>&lt;head&gt;</code> and <code>&lt;/head&gt;</code>.</li>
                                <li><strong>Footer Scripts:</strong> Injected just before <code>&lt;/body&gt;</code> for optimal performance.</li>
                            </ul>
                        </div>

                        <h6 class="fw-bold text-dark mb-1">How to Verify:</h6>
                        <ol class="ps-3 mb-0">
                            <li>Save your script in this form.</li>
                            <li>Open <a href="{{ url('/') }}" target="_blank">http://127.0.0.1:8000/</a> in your browser.</li>
                            <li>Right click anywhere and choose <strong>View Page Source</strong>.</li>
                            <li>Verify your Google Analytics / script appears in the source.</li>
                        </ol>
                    </div>
                </div>

            </div>
        </div>
    </form>
</div>

<script>
    function insertHeaderPreset(type) {
        const area = document.getElementById('headerScriptsArea');
        let code = '';
        if (type === 'gtm') {
            code = "<!-- Google Tag Manager -->\n<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':\nnew Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],\nj=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=\n'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);\n})(window,document,'script','dataLayer','GTM-XXXXXXX');<\/script>\n<!-- End Google Tag Manager -->\n";
        } else if (type === 'meta_pixel') {
            code = "<!-- Meta Pixel Code -->\n<script>\n!function(f,b,e,v,n,t,s)\n{if(f.fbq)return;n=f.fbq=function(){n.callMethod?\nn.callMethod.apply(n,arguments):n.queue.push(arguments)};\nif(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';\nn.queue=[];t=b.createElement(e);t.async=!0;\nt.src=v;s=b.getElementsByTagName(e)[0];\ns.parentNode.insertBefore(t,s)}(window, document,'script',\n'https://connect.facebook.net/en_US/fbevents.js');\nfbq('init', 'YOUR_PIXEL_ID');\nfbq('track', 'PageView');\n<\/script>\n<!-- End Meta Pixel Code -->\n";
        } else if (type === 'clarity') {
            code = "<!-- Microsoft Clarity -->\n<script type=\"text/javascript\">\n(function(c,l,a,r,i,t,y){\nc[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};\nt=l.createElement(r);t.async=1;t.src=\"https://www.clarity.ms/tag/\"+i;\ny=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);\n})(window, document, \"clarity\", \"script\", \"YOUR_CLARITY_ID\");\n<\/script>\n<!-- End Microsoft Clarity -->\n";
        }

        if (code) {
            area.value = (area.value.trim() ? area.value.trim() + "\n\n" : '') + code;
            area.focus();
        }
    }

    function insertFooterPreset(type) {
        const area = document.getElementById('footerScriptsArea');
        let code = '';
        if (type === 'gtm_noscript') {
            code = "<!-- Google Tag Manager (noscript) -->\n<noscript><iframe src=\"https://www.googletagmanager.com/ns.html?id=GTM-XXXXXXX\"\nheight=\"0\" width=\"0\" style=\"display:none;visibility:hidden\"></iframe></noscript>\n<!-- End Google Tag Manager (noscript) -->\n";
        } else if (type === 'chat') {
            code = "<!-- Live Chat Widget -->\n<script type=\"text/javascript\">\n// Paste your LiveChat / Tawk.to / Intercom embed script here\n<\/script>\n";
        }

        if (code) {
            area.value = (area.value.trim() ? area.value.trim() + "\n\n" : '') + code;
            area.focus();
        }
    }
</script>

@include('admin.layouts.footer')
