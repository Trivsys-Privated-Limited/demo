@extends('layout.index')
@extends('layout.nav')
@extends('layout.sidebar')

@section('home')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-10">
                        <h1 class="m-0 d-inline">All Users</h1>
                    </div>
                    <div class="col-sm-2">
                        <h1 class="m-0 d-inline"><a href="{{ route('users.create') }}" class="btn btn-primary">Add New</a></h1>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped">
                            @if (session('success'))
                                <div class="alert alert-success text-center" role="alert">
                                    {{ session('success') }}
                                </div>
                            @endif
                            <thead>
                                <tr>
                                    <th>SL.No</th>
                                    <th>USER NAME</th>
                                    <th>USER EMAIL</th>
                                    <th>USER PHONE</th>
                                    <th>BUSSINESS NAME</th>
                                    <th>ROLE</th>
                                    <th>STATUS</th>
                                    <th>ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($allusers as $index => $user)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->phone }}</td>
                                        <td>{{ $user->bussiness_name }}</td>
                                        <td>
                                            @if ($user->role == 'restaurant')
                                                Restaurant User
                                            @elseif($user->role == 'admin')
                                                Admin User
                                            @endif
                                        </td>
                                        <td>
                                            @if ($user->status == 'active')
                                                <span class="sm bg-success px-2 py-1 rounded">Active</span>
                                            @else
                                                <span class="sm bg-danger px-2 py-1 rounded">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('users.edit', $user->id) }}"
                                                class="btn btn-sm btn-primary">Update Password</a>
                                            <a href="{{ route('users.edit', $user->id) }}"
                                                class="btn btn-sm btn-info">Edit</a>
                                            <a href="{{ route('users.destroy', $user->id) }}" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Are You Sure You Want To Delete This Recode')">Delete</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
