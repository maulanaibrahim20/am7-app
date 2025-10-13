<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="index.html" class="app-brand-link">
            <span class="app-brand-logo demo">
                <svg width="32" height="22" viewBox="0 0 32 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M0.00172773 0V6.85398C0.00172773 6.85398 -0.133178 9.01207 1.98092 10.8388L13.6912 21.9964L19.7809 21.9181L18.8042 9.88248L16.4951 7.17289L9.23799 0H0.00172773Z"
                        fill="#7367F0" />
                    <path opacity="0.06" fill-rule="evenodd" clip-rule="evenodd"
                        d="M7.69824 16.4364L12.5199 3.23696L16.5541 7.25596L7.69824 16.4364Z" fill="#161616" />
                    <path opacity="0.06" fill-rule="evenodd" clip-rule="evenodd"
                        d="M8.07751 15.9175L13.9419 4.63989L16.5849 7.28475L8.07751 15.9175Z" fill="#161616" />
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M7.77295 16.3566L23.6563 0H32V6.88383C32 6.88383 31.8262 9.17836 30.6591 10.4057L19.7824 22H13.6938L7.77295 16.3566Z"
                        fill="#7367F0" />
                </svg>
            </span>
            <span class="app-brand-text demo menu-text fw-bold">{{ config('app.name') }}</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="ti menu-toggle-icon d-none d-xl-block ti-sm align-middle"></i>
            <i class="ti ti-x d-block d-xl-none ti-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>
    <ul class="menu-inner py-1">
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Main</span>
        </li>
        <li class="menu-item {{ Request::segment(2) == 'dashboard' ? 'active' : '' }}">
            <a href="{{ route('dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-home"></i>
                <div>Dashboard</div>
            </a>
        </li>
        @role('admin')
            <li class="menu-item {{ Request::segment(2) == 'user' ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons ti ti-user"></i>
                    <div>User</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item {{ Request::segment(3) == 'admin' ? 'active' : '' }}">
                        <a href="{{ route('user.admin.index') }}" class="menu-link">
                            <div>Admin</div>
                        </a>
                    </li>
                    <li class="menu-item {{ Request::segment(3) == 'staff' ? 'active' : '' }}">
                        <a href="{{ route('user.staff.index') }}" class="menu-link">
                            <div>Staff</div>
                        </a>
                    </li>
                    <li class="menu-item {{ Request::segment(3) == 'cashier' ? 'active' : '' }}">
                        <a href="{{ route('user.cashier.index') }}" class="menu-link">
                            <div>Cashier</div>
                        </a>
                    </li>
                    <li class="menu-item {{ Request::segment(3) == 'mechanic' ? 'active' : '' }}">
                        <a href="{{ route('user.mechanic.index') }}" class="menu-link">
                            <div>Mechanic</div>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="menu-item {{ Request::segment(2) == 'product' ? 'active' : '' }}">
                <a href="{{ route('product.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-package"></i>
                    <div>Product</div>
                </a>
            </li>
            <li class="menu-item {{ Request::segment(2) == 'service' ? 'active' : '' }}">
                <a href="{{ route('service.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-server-2"></i>
                    <div>Service</div>
                </a>
            </li>
            <li class="menu-item {{ Request::segment(2) == 'customer' ? 'active' : '' }}">
                <a href="{{ route('customer.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-users"></i>
                    <div>Customer</div>
                </a>
            </li>
            <li class="menu-item {{ Request::segment(2) == 'booking' ? 'active' : '' }}">
                <a href="{{ route('booking.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-book"></i>
                    <div>Booking</div>
                </a>
            </li>
            <li class="menu-item {{ Request::segment(2) == 'cashier' ? 'active' : '' }}">
                <a href="{{ route('cashier') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-cash"></i>
                    <div>Cashier</div>
                </a>
            </li>
            <li class="menu-item {{ Request::segment(2) == 'sale' ? 'active' : '' }}">
                <a href="{{ route('sale.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-arrows-right-left"></i>
                    <div>Sale</div>
                </a>
            </li>
            <li class="menu-item {{ Request::segment(2) == 'purchase-order' ? 'active' : '' }}">
                <a href="{{ route('purchase-order.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-truck-return"></i>
                    <div>Purchase Order</div>
                </a>
            </li>
            <li class="menu-item {{ Request::segment(2) == 'inventory' ? 'active' : '' }}">
                <a href="{{ route('inventory.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-building-warehouse"></i>
                    <div>Inventory</div>
                </a>
            </li>
            <li class="menu-header small text-uppercase">
                <span class="menu-header-text">Master</span>
            </li>
            <li class="menu-item {{ Request::segment(3) == 'category' ? 'active' : '' }}">
                <a href="{{ route('master.category.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-category"></i>
                    <div>Category</div>
                </a>
            </li>
            <li class="menu-item {{ Request::segment(3) == 'supplier' ? 'active' : '' }}">
                <a href="{{ route('master.supplier.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-truck-delivery"></i>
                    <div>Supplier</div>
                </a>
            </li>
            <li class="menu-header small text-uppercase">
                <span class="menu-header-text">Settings</span>
            </li>
            <li class="menu-item {{ Request::segment(2) == 'setting' ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons ti ti-settings"></i>
                    <div>Settings</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item {{ Request::segment(3) == 'endpoint-cront-task' ? 'active' : '' }}">
                        <a href="{{ route('setting.endpoint-cront-task.index') }}" class="menu-link">
                            <div>Endpoint Cron Taks</div>
                        </a>
                    </li>
                    <li class="menu-item {{ Request::segment(3) == 'endpoint-cront-task' ? 'active' : '' }}">
                        <a href="{{ url('log-viewer') }}" target="_blank" class="menu-link">
                            <div>Laravel Log</div>
                        </a>
                    </li>
                </ul>
            </li>
        @endrole
        @role('cashier')
            <li class="menu-item {{ Request::segment(2) == 'cashier' ? 'active' : '' }}">
                <a href="{{ route('cashier') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-cash"></i>
                    <div>Cashier</div>
                </a>
            </li>
        @endrole
        @role('staff')
        @endrole
    </ul>
    <div class="menu-footer border-top py-3 px-3">
        <div class="d-flex align-items-center">
            <img src="{{ url('/template') }}/img/avatars/1.png" alt="user-avatar" class="rounded-circle me-2"
                width="36" height="36" />
            <div class="flex-grow-1">
                <div class="fw-semibold">{{ ucWords(Auth::user()->name) }}</div>
                <small class="text-muted">{{ ucWords(Auth::user()->roles->first()->name) }}</small>
            </div>

            <div class="dropdown">
                <a href="#" class="text-muted" data-bs-toggle="dropdown">
                    <i class="ti ti-dots-vertical"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="#">
                            <i class="ti ti-user me-2"></i> Profile
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#">
                            <i class="ti ti-settings me-2"></i> Settings
                        </a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <a class="dropdown-item text-danger" href="{{ route('logout') }}">
                            <i class="ti ti-logout me-2"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</aside>
