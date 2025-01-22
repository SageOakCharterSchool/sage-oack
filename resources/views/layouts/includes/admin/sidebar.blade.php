<!-- partial:partials/_sidebar.html -->
<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
      <li class="nav-item">
        <a class="nav-link" href="/admin/dashboard">
          <i class="mdi mdi-home menu-icon"></i>
          <span class="menu-title">Dashboard</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="/admin/cycle">
          <i class="mdi mdi-view-headline menu-icon"></i>
          <span class="menu-title">Cycles</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="/admin/user">
            <i class="mdi mdi-account menu-icon"></i>
            <span class="menu-title">Users</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="/admin/impersonate-list">
            <i class="mdi mdi-drama-masks menu-icon"></i>
            <span class="menu-title">Impersonate List</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="/admin/specialist-students">
            <i class="mdi mdi-card-bulleted menu-icon"></i>
            <span class="menu-title">Specialist</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="collapse" href="#auth0" aria-expanded="false" aria-controls="auth">
          <i class="mdi mdi-teach menu-icon"></i>
          <span class="menu-title">Students</span>
          <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="auth0">
          <ul class="nav flex-column sub-menu">
            <li class="nav-item protectMe"> <a class="nav-link" href="/admin/view-students"> View Students </a></li>
          </ul>
          <ul class="nav flex-column sub-menu">
            <li class="nav-item protectMe"> <a class="nav-link" href="/admin/chrome-tracking"> Chromebook Tracking </a></li>
          </ul>

        </div>
      </li>
{{-- Define Structues --}}
<li class="nav-item">
    <a class="nav-link" data-bs-toggle="collapse" href="#auth3" aria-expanded="false" aria-controls="auth">
      <i class="mdi mdi-file-document-box-search menu-icon"></i>
      <span class="menu-title">Dynamic Uploads</span>
      <i class="menu-arrow"></i>
    </a>
    <div class="collapse" id="auth3">
      <ul class="nav flex-column sub-menu">
        <li class="nav-item"> <a class="nav-link" href="/admin/table-def"> Define Tables </a></li>
      </ul>
      <ul class="nav flex-column sub-menu">
        <li class="nav-item"> <a class="nav-link" href="/admin/formulas"> Define Formulas </a></li>
      </ul>

      <ul class="nav flex-column sub-menu">
        <li class="nav-item"> <a class="nav-link" href="/admin/consolidate-mappings">Map Consolidate </a></li>
      </ul>
      <ul class="nav flex-column sub-menu">
        <li class="nav-item"> <a class="nav-link" href="/admin/build-reports"> Build Reports </a></li>
      </ul>
      <ul class="nav flex-column sub-menu">
        <li class="nav-item"> <a class="nav-link" href="/admin/table-def/clone-tables"> Clone Tables </a></li>
      </ul>
    </div>
  </li>
{{-- End Define Structures --}}

      {{-- <li class="nav-item">
        <a class="nav-link" data-bs-toggle="collapse" href="#auth1" aria-expanded="false" aria-controls="auth">
          <i class="mdi mdi-file-cabinet menu-icon"></i>
          <span class="menu-title">Student Reports</span>
          <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="auth1">
          <ul class="nav flex-column sub-menu">
            <li class="nav-item"> <a class="nav-link" href="/admin/upload"> Upload Files </a></li>
            <li class="nav-item"> <a class="nav-link" href="/admin/process-files"> Process </a></li>
            <li class="nav-item"> <a class="nav-link" href="/admin/consolidate"> Consolidate </a></li>
            <li class="nav-item protectMe"> <a class="nav-link" href="/admin/view-consolidated"> View Reports </a></li>
          </ul>
        </div>
      </li> --}}
      {{-- <li class="nav-item">
        <a class="nav-link" href="/admin/reports">
          <i class="mdi mdi-view-headline menu-icon"></i>
          <span class="menu-title">Reports</span>
        </a>
      </li> --}}

    </ul>
  </nav>
