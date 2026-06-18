@extends('layout.index')
@extends('layout.nav')
@extends('layout.sidebar')

@section('home')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-10">
                        <h1 class="m-0 d-inline">All Tables</h1>
                    </div>
                    <div class="col-sm-2">
                        <h1 class="m-0 d-inline"><a href="{{ route('tables.create') }}" class="btn btn-primary">Add New</a>
                        </h1>
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
                                    <th>TABLE NO</th>
                                    <th>QR CODE</th>
                                    <th>ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tables as $index => $table)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $table->table_number }}</td>
                                        <td>{!! QrCode::size(200)->generate(url('/menu/' . $table->qr_token)) !!}</td>
                                        <td>
                                            <a href="{{ url('/menu/' . $table->qr_token) }}" target="_blank"
                                                class="btn btn-sm btn-info">View Menu</a>
                                            <a href="{{ route('tables.edit', $table->id) }}"
                                                class="btn btn-sm btn-primary">Edit</a>
                                            <form action="{{ route('tables.destroy', $table->id) }}" method="POST"
                                                style="display: inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Are you sure you want to delete this table?')">Delete</button>
                                            </form>
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
