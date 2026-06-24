@section('sidebar')
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="{{ route('dashboard.index') }}" class="brand-link">
            <img src="{{ asset('admin/dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo"
                class="brand-image img-circle elevation-3" style="opacity: .8">
            <span class="brand-text font-weight-light">Restaurant</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar user panel (optional) -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <img src="{{ asset('admin/dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2"
                        alt="User Image">
                </div>
                <div class="info">
                    <a href="#" class="d-block">Alexander Pierce</a>
                </div>
            </div>

            <!-- SidebarSearch Form -->
            <div class="form-inline">
                <div class="input-group" data-widget="sidebar-search">
                    <input class="form-control form-control-sidebar" type="search" placeholder="Search"
                        aria-label="Search">
                    <div class="input-group-append">
                        <button class="btn btn-sidebar">
                            <i class="fas fa-search fa-fw"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                    data-accordion="false">
                    <!-- Add icons to the links using the .nav-icon class
                                                                               with font-awesome or any other icon font library -->
                    <li class="nav-item menu-open">
                        <a href="{{route('dashboard.index')}}" class="nav-link active">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                Dashboard
                                {{-- <i class="right fas fa-angle-left"></i> --}}
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('users.index') }}" class="nav-link">
                            <img class="w-[50px] nav-icon" src="{{asset('icon/user.png')}}" alt="">
                            <p>All User</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('items.index') }}" class="nav-link">
                             <img class="w-[50px] nav-icon" src="{{asset('icon/item.png')}}" alt="">
                            <p>Menu Items</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('tables.index') }}" class="nav-link">
                          <img class="w-[50px] nav-icon" src="{{asset('icon/table.png')}}" alt="">
                            <p>Manage Table</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('orders.kichan') }}" class="nav-link">
                          <img class="w-[50px] nav-icon" src="{{asset('icon/kichan.png')}}" alt="">
                            <p>Manage Kitchen</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('orders.index') }}" class="nav-link">
                          <img class="w-[50px] nav-icon" src="{{asset('icon/order.png')}}" alt="">
                            <p>Manage Order</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('reports.index') }}" class="nav-link">
                          <i class="nav-icon fas fa-chart-line text-info"></i>
                            <p>Sales Report</p>
                        </a>
                    </li>

                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>

    <script>
function toggleCustomCategory(value) {
    const customGroup = document.getElementById('customCategoryGroup');
    const customInput = document.getElementById('custom_category');

    if (value === 'custom_option') {
        customGroup.style.display = 'block';
        customInput.setAttribute('required', 'required'); // Nayi category likhna lazmi kar dega
        customInput.focus();
    } else {
        customGroup.style.display = 'none';
        customInput.removeAttribute('required');
        customInput.value = ''; // Clear input if user switches back
    }
}
</script>

<script>
    function toggleCustomCategory(value) {
        const customGroup = document.getElementById('customCategoryGroup');
        const customInput = document.getElementById('custom_category');

        if (value === 'custom_option') {
            customGroup.style.display = 'block';
            customInput.setAttribute('required', 'required');
            customInput.focus();
        } else {
            customGroup.style.display = 'none';
            customInput.removeAttribute('required');
            customInput.value = ''; // Clear input
        }
    }
</script>

@endsection
