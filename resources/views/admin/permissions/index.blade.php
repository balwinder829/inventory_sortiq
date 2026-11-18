@include('admin.layouts.header')

<div class="content-wrapper">
<div class="container-xxl flex-grow-1 container-p-y">
<div class="page-body">
<div class="container container-xl">

<h4>User Permissions</h4>

<form method="GET" class="mb-3">
    <select name="user_id" class="form-select" onchange="this.form.submit()">
        <option>Select User</option>
        @foreach($users as $u)
            <option value="{{ $u->id }}"
                {{ request('user_id') == $u->id ? 'selected' : '' }}>
                {{ $u->name }}
            </option>
        @endforeach
    </select>
</form>

@if($selectedUser)

<form method="POST" action="{{ url('/admin/permissions') }}">
@csrf
<input type="hidden" name="user_id" value="{{ $selectedUser->id }}">

{{-- ========================= --}}
{{-- CUSTOM TABS --}}
{{-- ========================= --}}
<div class="mb-3 d-flex flex-wrap gap-2">

@foreach($permissions as $group => $perms)
@php $groupKey = \Str::slug($group); @endphp

<button type="button"
        class="perm-tab-btn {{ $loop->first ? 'active' : '' }}"
        data-tab="{{ $groupKey }}">
    {{ $group }}
</button>

@endforeach

</div>

{{-- ========================= --}}
{{-- TAB CONTENT --}}
{{-- ========================= --}}
@foreach($permissions as $group => $perms)

@php
    $groupKey = \Str::slug($group);
    $menuItems = $perms->groupBy('menu_item');
@endphp

<div class="perm-tab-content"
     id="tab-{{ $groupKey }}"
     style="{{ $loop->first ? '' : 'display:none;' }}">

    {{-- SELECT ALL --}}
    <div class="mb-2">
        <input type="checkbox" class="select-group" data-group="{{ $groupKey }}">
        <strong>Select All {{ $group }}</strong>
    </div>

    {{-- MENU CARDS --}}
    @foreach($menuItems as $menu => $actions)

    @php $menuKey = \Str::slug($menu); @endphp

    <div class="card mb-2 shadow-sm">

        {{-- HEADER --}}
        <div class="card-header d-flex justify-content-between align-items-center"
             style="cursor:pointer"
             onclick="toggleMenu('{{ $groupKey }}-{{ $menuKey }}')">

            <label class="mb-0">
                <input type="checkbox"
                       class="menu-checkbox me-2"
                       data-menu="{{ $groupKey }}-{{ $menuKey }}"
                       data-group="{{ $groupKey }}"
                       onclick="event.stopPropagation();">

                {{ ucfirst($menu) }}
            </label>

            <span id="icon-{{ $groupKey }}-{{ $menuKey }}">⌄</span>
        </div>

        {{-- BODY --}}
        <div id="body-{{ $groupKey }}-{{ $menuKey }}"
             style="display:none"
             class="card-body">

            <div class="row">

            @foreach($actions as $perm)

            <div class="col-md-3 mb-2">
                <label>
                    <input type="checkbox"
                           name="permissions[]"
                           class="perm-checkbox group-{{ $groupKey }} menu-{{ $groupKey }}-{{ $menuKey }}"
                           value="{{ $perm->name }}"
                           {{ $selectedUser->can($perm->name) ? 'checked' : '' }}>

                    {{ explode('.', $perm->name)[1] }}
                </label>
            </div>

            @endforeach

            </div>

        </div>

    </div>

    @endforeach

</div>

@endforeach

<button class="btn btn-success mt-3">Save Permissions</button>

</form>

@endif

</div>
</div>
</div>
</div>

@include('admin.layouts.footer')

{{-- ========================= --}}
{{-- STYLE --}}
{{-- ========================= --}}
<style>
.perm-tab-btn {
    padding: 6px 14px;
    border: 1px solid #ddd;
    background: #f5f5f5;
    border-radius: 6px;
    cursor: pointer;
}

.perm-tab-btn.active {
    background: #fff;
    border-bottom: 2px solid #696cff;
    font-weight: 600;
}

.card-header {
    background: #eaf2ff;
}
</style>

{{-- ========================= --}}
{{-- SCRIPT --}}
{{-- ========================= --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    // =========================
    // TAB SWITCH
    // =========================
    document.querySelectorAll('.perm-tab-btn').forEach(btn => {
        btn.addEventListener('click', function () {

            let tab = this.dataset.tab;

            document.querySelectorAll('.perm-tab-btn')
                .forEach(b => b.classList.remove('active'));

            document.querySelectorAll('.perm-tab-content')
                .forEach(c => c.style.display = 'none');

            this.classList.add('active');

            let current = document.getElementById('tab-' + tab);
            if (current) current.style.display = 'block';
        });
    });

    // =========================
    // GROUP SELECT
    // =========================
    document.querySelectorAll('.select-group').forEach(groupCheckbox => {

        groupCheckbox.addEventListener('change', function () {

            let group = this.dataset.group;

            document.querySelectorAll('.group-' + group)
                .forEach(el => el.checked = this.checked);

            document.querySelectorAll('.menu-checkbox[data-group="' + group + '"]')
                .forEach(el => el.checked = this.checked);
        });
    });

    // =========================
    // MENU SELECT
    // =========================
    document.querySelectorAll('.menu-checkbox').forEach(menuCheckbox => {

        menuCheckbox.addEventListener('change', function () {

            let menu = this.dataset.menu;

            document.querySelectorAll('.menu-' + menu)
                .forEach(el => el.checked = this.checked);

            updateParentChecks();
        });
    });

    // =========================
    // INDIVIDUAL CHECKBOX
    // =========================
    document.querySelectorAll('.perm-checkbox').forEach(cb => {
        cb.addEventListener('change', updateParentChecks);
    });

    // =========================
    // UPDATE STATES
    // =========================
    function updateParentChecks() {

        // MENU LEVEL
        document.querySelectorAll('.menu-checkbox').forEach(menu => {

            let perms = document.querySelectorAll('.menu-' + menu.dataset.menu);
            let checked = [...perms].filter(p => p.checked).length;

            menu.checked = (checked === perms.length);
        });

        // GROUP LEVEL
        document.querySelectorAll('.select-group').forEach(group => {

            let perms = document.querySelectorAll('.group-' + group.dataset.group);
            let checked = [...perms].filter(p => p.checked).length;

            group.checked = (checked === perms.length);
        });
    }

    // =========================
    // AUTO OPEN CHECKED MENUS
    // =========================
    document.querySelectorAll('.perm-checkbox:checked').forEach(cb => {

        let classes = cb.classList;

        let menuClass = [...classes].find(c => c.startsWith('menu-'));
        if (!menuClass) return;

        let menuId = menuClass.replace('menu-', '');

        let body = document.getElementById('body-' + menuId);
        let icon = document.getElementById('icon-' + menuId);

        if (body) {
            body.style.display = 'block';
        }

        if (icon) {
            icon.innerHTML = '⌃';
        }
    });

    // =========================
    // INITIAL CHECK SYNC
    // =========================
    updateParentChecks();

});

// =========================
// ACCORDION TOGGLE
// =========================
function toggleMenu(id) {
    let body = document.getElementById('body-' + id);
    let icon = document.getElementById('icon-' + id);

    if (body.style.display === 'none') {
        body.style.display = 'block';
        icon.innerHTML = '⌃';
    } else {
        body.style.display = 'none';
        icon.innerHTML = '⌄';
    }
}
</script>
<!-- <script>
document.addEventListener('DOMContentLoaded', function () {

    // TAB SWITCH (NO RELOAD)
    document.querySelectorAll('.perm-tab-btn').forEach(btn => {

        btn.addEventListener('click', function () {

            let tab = this.dataset.tab;

            document.querySelectorAll('.perm-tab-btn')
                .forEach(b => b.classList.remove('active'));

            document.querySelectorAll('.perm-tab-content')
                .forEach(c => c.style.display = 'none');

            this.classList.add('active');

            let current = document.getElementById('tab-' + tab);
            if (current) current.style.display = 'block';

        });

    });

    // GROUP SELECT
    document.querySelectorAll('.select-group').forEach(groupCheckbox => {

        groupCheckbox.addEventListener('change', function () {

            let group = this.dataset.group;

            document.querySelectorAll('.group-' + group)
                .forEach(el => el.checked = this.checked);

            document.querySelectorAll('.menu-checkbox[data-group="' + group + '"]')
                .forEach(el => el.checked = this.checked);
        });
    });

    // MENU SELECT
    document.querySelectorAll('.menu-checkbox').forEach(menuCheckbox => {

        menuCheckbox.addEventListener('change', function () {

            let menu = this.dataset.menu;

            document.querySelectorAll('.menu-' + menu)
                .forEach(el => el.checked = this.checked);
        });
    });

});

// ACCORDION
function toggleMenu(id) {
    let body = document.getElementById('body-' + id);
    let icon = document.getElementById('icon-' + id);

    if (body.style.display === 'none') {
        body.style.display = 'block';
        icon.innerHTML = '⌃';
    } else {
        body.style.display = 'none';
        icon.innerHTML = '⌄';
    }
}
</script> -->