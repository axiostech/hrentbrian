<!-- Sidebar -->
<nav id="sidebar" class="sidebar-wrapper">
  <div class="sidebar-content">
    <div class="sidebar-brand" style="background-color:#F5F5F5; border:solid black 1px;">
      <a href="/" class="text-center">
        <img src="/img/icon.png" height="40" width="105">
      </a>
      <div id="close-sidebar">
        <i class="far fa-arrow-alt-circle-left"></i>
      </div>
    </div>

    @php
        $firstdir = Request::segment(1);
        $thirddir = Request::segment(3);
    @endphp

    <div class="sidebar-menu">
      <ul>

        <li class="{{ $firstdir == '' ? 'active' : '' }}">
          <a href="/">
            <i class="fas fa-tachometer-alt"></i>
            Dashboard
          </a>
        </li>

        <li class="header-menu">
          <span>Profile Management</span>
        </li>

        <li class="{{ $firstdir == 'profile' ? 'active' : '' }}">
          <a href="/profile">
            <i class="far fa-building"></i>
            Profiles
          </a>
        </li>

        <li class="sidebar-dropdown" class="{{ $firstdir == 'user' ? 'active' : '' or $firstdir == 'role' ? 'active' : '' }}">
          <a href="#">
            <i class="fas fa-users"></i>
            <span>Users Management</span>
          </a>
          <div class="sidebar-submenu">
            <ul>
              <li>
                <a href="/user">Users</a>
              </li>
              <li>
                <a href="/rolepermission">Roles & Permissions</a>
              </li>
            </ul>
          </div>
        </li>

        <li class="{{ $firstdir == 'tenant' ? 'active' : '' }}">
          <a href="/tenant">
            <i class="fas fa-users"></i>
            Tenants
          </a>
        </li>

        <li class="{{ $firstdir == 'beneficiary' ? 'active' : '' }}">
          <a href="/beneficiary">
            <i class="fas fa-address-card"></i>
            Beneficiaries
          </a>
        </li>

        <li class="header-menu">
          <span>Property Management</span>
        </li>

        <li class="{{ $firstdir == 'property' ? 'active' : ''}}">
          <a href="/property">
            <i class="fas fa-city"></i>
            Properties
          </a>
        </li>

        <li class="{{ $firstdir == 'unit' ? 'active' : ''}}">
          <a href="/unit">
            <i class="fas fa-home"></i>
            Units
          </a>
        </li>

        <li class="header-menu">
          <span>Rental Management</span>
        </li>

        <li class="{{ $firstdir == 'tenancy' ? 'active' : '' }}">
          <a href="/tenancy">
            <i class="fas fa-file-signature"></i>
            Tenancies
          </a>
        </li>

        <li class="{{ $firstdir == 'collection' ? 'active' : '' }}">
          <a href="/collection">
            <i class="fas fa-table"></i>
            ARC Collection
          </a>
        </li>

        <li class="{{ $firstdir == 'invoice' ? 'active' : '' }}">
          <a href="/invoice">
            <i class="far fa-credit-card"></i>
            Invoices
          </a>
        </li>

        <li class="header-menu">
          <span>Services</span>
        </li>
        <li class="{{ $firstdir == 'insurance' ? 'active' : '' }}">
          <a href="/insurance">
            <i class="fas fa-house-damage"></i>
            Insurances
          </a>
        </li>
        <li class="{{ $firstdir == 'utilityrecord' ? 'active' : '' }}">
          <a href="/utilityrecord">
            <i class="fas fa-tachometer-alt"></i>
            Utilities Records
          </a>
        </li>

        <li class="header-menu">
          <span>Setting</span>
        </li>

        <li class="{{ $thirddir == 'account' ? 'active' : '' }}">
          <a href="/user/{{auth()->user()->id}}/account">
            <i class="fas fa-user-circle"></i>
            User Account
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>
