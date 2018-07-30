<div class="col-md-3">

    <div class="card">
        <div class="card-header">
            Backend
        </div>
        <div class="card-body">
            <ul class="nav flex-column" role="tablist">
                <li class="nav-item" role="presentation">
                    <a href="{{ route('admin.dashboard') }}">Admin Dashboard</a>
                </li>
            </ul>
        </div>
    </div>

    <br/>

    <div class="card">
        <div class="card-header">
            Users / Roles / Permissions
        </div>
        <div class="card-body">
            <ul class="nav flex-column" role="tablist">
                <li class="nav-item" role="presentation">
                    <a href="{{ route('admin.users.index') }}">All users</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a href="{{ route('admin.users.create') }}">Create user</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a href="{{ route('admin.roles.index') }}">All roles</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a href="{{ route('admin.roles.create') }}">Create role</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a href="{{ route('admin.permissions.index') }}">All permissions</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a href="{{ route('admin.permissions.create') }}">Create permission</a>
                </li>
            </ul>
        </div>
    </div>

</div>
