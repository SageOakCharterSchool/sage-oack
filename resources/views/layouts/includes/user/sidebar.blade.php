<!-- partial:partials/_sidebar.html -->
<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link" href="/home">
                <i class="mdi mdi-home menu-icon"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>

        @if (Session::has('impersonateFrom'))
            <li class="nav-item">
                <a class="nav-link text-danger" href="/user/unimpersonate">
                    <span class="">Leave Impersonation</span>
                </a>
            </li>
        @endif
        @if (\App\Models\UsersEnabledToImpersonate::checkIfUserHasImpersonatePermissions(\Auth::user()->id))
        <li class="nav-item">
            <a class="nav-link" href="/admin/user">
                <i class="mdi mdi-account menu-icon"></i>
                <span class="menu-title">Users</span>
            </a>
        </li>
        @endif
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#auth1" aria-expanded="false" aria-controls="auth">
                <i class="mdi mdi-file-cabinet menu-icon"></i>
                <span class="menu-title">Students</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="auth1">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item protectMe"> <a class="nav-link" href="/admin/view-students"> View Students </a>
                    </li>
                </ul>
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item protectMe"> <a class="nav-link" href="/admin/chrome-tracking"> Chromebook
                            Tracking </a></li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#auth2" aria-expanded="false" aria-controls="auth">
                <i class="mdi mdi-file-cabinet menu-icon"></i>
                <span class="menu-title">Student Reports</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="auth2">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item protectMe"> <a class="nav-link" href="/admin/consolidate-view"> View Reports
                        </a></li>
                </ul>
            </div>
        </li>
        {{-- <li class="nav-item">
            <a class="nav-link" href="/admin/reports">
                <i class="mdi mdi-view-headline menu-icon"></i>
                <span class="menu-title">Reports</span>
            </a>
        </li> --}}

    </ul>
</nav>
