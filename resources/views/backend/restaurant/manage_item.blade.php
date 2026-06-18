@extends('layout.index')
@extends('layout.nav')
@extends('layout.sidebar')

@section('home')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-10">
                        <h1 class="m-0 d-inline">All Items</h1>
                    </div>
                    <div class="col-sm-2">
                        <h1 class="m-0 d-inline"><a href="{{ route('items.create') }}" class="btn btn-primary">Add New</a></h1>
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
                                    <th>ITEM</th>
                                    <th>ITEM NAME</th>
                                    <th>ITEM PRICE</th>
                                    <th>ITEM DESCRIPTION</th>
                                    <th>CATEGORY</th>
                                    <th>STATUS</th>
                                    <th>ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td><img src="{{ asset('images/' . $item->image) }}" alt="Item Image"
                                                width="50"></td>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->price }}</td>
                                        <td>{{ $item->description }}</td>
                                        <td>{{ $item->category }}</td>
                                        <td>
                                            @if ($item->status == 'active')
                                                <span
                                                    class="sm bg-success px-2 py-1 rounded">{{ ucfirst($item->status) }}</span>
                                            @else
                                                <span
                                                    class="sm bg-danger px-2 py-1 rounded">{{ ucfirst($item->status) }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('items.edit', $item->id) }}"
                                                class="btn btn-info btn-sm">Edit</a>
                                            <form action="{{ route('items.destroy', $item->id) }}" method="POST"
                                                style="display:inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Are you sure you want to delete this item?')">Delete</button>
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
