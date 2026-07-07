@extends('layout.index')
@extends('layout.nav')
@extends('layout.sidebar')

@section('home')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-10">
                        <h1 class="m-0 d-inline">
                            <i class="fas fa-users text-success mr-2"></i> Kitchen Staff Members
                        </h1>
                    </div>
                    <div class="col-sm-2">
                        <h1 class="m-0 d-inline"><a href="{{ route('staff.create') }}" class="btn btn-success">
                            <i class="fas fa-plus mr-1"></i> Add New Staff
                        </a></h1>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-success card-outline">
                        <div class="card-body">
                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="fas fa-check-circle mr-2"></i>
                                    {{ session('success') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif

                            @if (session('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <i class="fas fa-exclamation-circle mr-2"></i>
                                    {{ session('error') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif

                            @if($staff->isEmpty())
                                <div class="alert alert-info text-center">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    No staff members found. 
                                    <a href="{{ route('staff.create') }}" class="alert-link">Add one now</a>
                                </div>
                            @else
                                <table id="staffTable" class="table table-bordered table-hover table-striped">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Status</th>
                                            <th>Added On</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($staff as $index => $member)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    <strong>{{ $member->name }}</strong>
                                                </td>
                                                <td>{{ $member->email }}</td>
                                                <td>{{ $member->phone }}</td>
                                                <td>
                                                    @if ($member->status == 'active')
                                                        <span class="badge badge-success">
                                                            <i class="fas fa-check-circle mr-1"></i>Active
                                                        </span>
                                                    @else
                                                        <span class="badge badge-danger">
                                                            <i class="fas fa-times-circle mr-1"></i>Inactive
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>{{ $member->created_at->format('d M Y') }}</td>
                                                <td class="text-center" style="white-space: nowrap;">
                                                    <a href="{{ route('staff.edit', $member->id) }}" 
                                                        class="btn btn-sm btn-info" title="Edit">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a>
                                                    <a href="{{ route('staff.destroy', $member->id) }}" 
                                                        class="btn btn-sm btn-danger" 
                                                        onclick="return confirm('Are you sure you want to delete this staff member?')" 
                                                        title="Delete">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(function () {
                $("#staffTable").DataTable({
                    "responsive": true,
                    "lengthChange": false,
                    "autoWidth": false,
                });
            });
        </script>
    @endpush
@endsection
