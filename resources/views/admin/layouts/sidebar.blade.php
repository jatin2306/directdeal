<!-- resources/views/admin/layouts/sidebar.blade.php -->
<aside class="sidebar bg-dark text-white position-fixed h-100" style="width: 240px;">
  <div class="p-4">
    <a href="{{ route('admin.dashboard') }}">
      <img src="{{ asset('images/logo.jpg') }}" alt="Admin Logo" class="img-fluid mb-4">
    </a>

    <ul class="nav flex-column">
      <li class="nav-item mb-2">
        <a class="nav-link text-white {{ request()->routeIs('admin.dashboard') ? 'active bg-primary' : '' }}" href="{{ route('admin.dashboard') }}">
          <i class="fa fa-tachometer-alt me-2"></i> Dashboard
        </a>
      </li>

      <li class="nav-item mb-2">
      <a class="nav-link text-white {{ request()->routeIs('admin.property-list') ? 'active bg-primary' : '' }}" href="{{ route('admin.property-list') }}">
          <i class="fa fa-home me-2"></i> Properties
        </a>
      </li>

      <li class="nav-item mb-2">
        <a class="nav-link text-white {{ request()->routeIs('admin.amenities.index') ? 'active bg-primary' : '' }}" href="{{ route('admin.amenities.index') }}">
            <i class="fa fa-cogs me-2"></i> Amenities
        </a>
      </li>

      <li class="nav-item mb-2">
          <a class="nav-link text-white {{ request()->routeIs('admin.transactions.index') ? 'active bg-primary' : '' }}"
            href="{{ route('admin.transactions.index') }}">
              <i class="fa fa-exchange-alt me-2"></i> Transactions
          </a>
      </li>

      <li class="nav-item mb-2">
          <a class="nav-link text-white {{ request()->routeIs('admin.users.*') ? 'active bg-primary' : '' }}"
            href="{{ route('admin.users.index') }}">
              <i class="fa fa-users me-2"></i> Users
          </a>
      </li>

      <li class="nav-item mb-2">
          <a class="nav-link text-white {{ request()->routeIs('admin.notifications') ? 'active bg-primary' : '' }}"
            href="{{ route('admin.notifications') }}">
              <i class="fa fa-bell me-2"></i> User Notifications
          </a>
      </li>


     
    </ul>

    <form action="{{ route('admin.logout') }}" method="POST" class="mt-5">
      @csrf
      <button type="submit" class="btn btn-danger w-100"><i class="fa fa-sign-out-alt me-2"></i> Logout</button>
    </form>
  </div>
</aside>
