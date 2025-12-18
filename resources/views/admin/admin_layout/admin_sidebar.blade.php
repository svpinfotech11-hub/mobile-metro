 <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="" class="brand-link">
      <img src="{{ asset('') }}admin_assets/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">Admin</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{ asset('') }}admin_assets/dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">{{ session('user')->name }}</a>
        </div>
      </div>

      <!-- SidebarSearch Form -->
      <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item menu-open">
            <a href="{{ route('app.admin-dashboard')}}" class="nav-link active">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
           
          </li>


            <li class="nav-item">
            <a href="{{route('app.admin-enquiry.index')}}" class="nav-link">
              <i class="nav-icon far fa-image"></i>
              <p>
                <!-- INVENTORY Enquiry List -->
                 Packers & Movers
              </p>
            </a>
          </li>

           <li class="nav-item">
            <a href="{{route('get-service-enquiry')}}" class="nav-link">
              <i class="nav-icon far fa-image"></i>
              <p>
                Service Enquiry List
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{route('get-other-enquiry')}}" class="nav-link">
              <i class="nav-icon far fa-image"></i>
              <p>
              Other Enquiry List
              </p>
            </a>
          </li>

          
         
          <li class="nav-item">
            <a href="{{ route('app.admin-category.create');}}" class="nav-link">
              <i class="nav-icon fas fa-copy"></i>
              <p>
               Category Master
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <!-- <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('app.admin-category.create');}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add Category</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="pages/layout/top-nav-sidebar.html" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Category List</p>
                </a>
              </li>
            </ul> -->
          </li>
          <li class="nav-item">
            <a href="{{ route('app.admin-subCategory.create');}}" class="nav-link">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>
                Sub Category
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <!-- <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('app.admin-subCategory.create');}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add Sub Category</p>
                </a>
              </li>            
            </ul> -->
          </li>
          <li class="nav-item">
            <a href="{{ route('app.admin-services.create');}}" class="nav-link">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>
              Inventory Main Category
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <!-- <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('app.admin-services.create');}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add Services</p>
                </a>
              </li>            
            </ul> -->
          </li>
           <li class="nav-item">
            <a href="{{ route('admin.product_subcategory.create');}}" class="nav-link">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>
               Inventory Sub Category
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('app.admin-product.create');}}" class="nav-link">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>
              Add New Inventory
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <!-- <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('app.admin-product.create');}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add INVENTORY </p>
                </a>
              </li>            
            </ul> -->
          </li>
          <li class="nav-item">
            <a href="{{ route('app.admin-banner.create')}}" class="nav-link">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>
               Banner
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <!-- <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('app.admin-banner.create');}}" class="nav-link">
                  <i class="far fa-box nav-icon"></i>
                  <p>Add Banner</p>
                </a>
              </li>            
            </ul> -->
          </li>
          <li class="nav-item">
            <a href="{{ route('app.admin-cftRate.create'); }}" class="nav-link">
              <i class="nav-icon fas fa-box"></i>
              <p>
               CFT Rate Master
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <!-- <ul class="nav nav-treeview">
       
              <li class="nav-item">
                 <a href="{{ route('app.admin-kmRate.create'); }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>View CFT List</p>
                </a>
              </li>
            </ul> -->
          </li>
           <li class="nav-item">
            <a href="{{ route('app.admin-kmRate.create'); }}" class="nav-link">
              <i class="nav-icon fas fa-edit"></i>
              <p>
                KM Master
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <!-- <ul class="nav nav-treeview">
          
              <li class="nav-item">
                <a href="{{ route('app.admin-kmRate.index'); }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>KM RATE LIST</p>
                </a>
              </li>
            </ul> -->
           </li>
        
           <li class="nav-item">
            <a href="{{route('admin.vendors.get-vendor')}}" class="nav-link">
              <i class="nav-icon far fa-image"></i>
              <p>
               Vendor List
              </p>
            </a>
          </li>
         
           <li class="nav-item">
            <a href="{{ route('admin.customer-list'); }}" class="nav-link">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Customer List
                <span class="badge badge-info right">2</span>
              </p>
            </a>
          </li>


        <li class="nav-item">
            <a href="{{ route('policies.create');}}" class="nav-link">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>
               Pages
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
           
          </li>
           
           <!-- <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-edit"></i>
              <p>
                Pickup Boy
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="app.admin-pickupboy.create" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add Pickup Boy</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="app.admin-pickupboy.index" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Pickup Boy List</p>
                </a>
              </li>
            </ul>
          </li> -->
        
          <!-- <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-table"></i>
              <p>
               Vehicle Master
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="pages/forms/general.html" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add Vehicle</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="pages/forms/advanced.html" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Vehicle List</p>
                </a>
              </li>
            </ul>
          </li> -->
         
                    
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>