@extends('layout.index')
@extends('layout.nav')
@extends('layout.sidebar')

@section('home')
    <div class="content-wrapper">
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Small boxes (Stat box) -->
                <div class="row">
                    <div class="col-md-12">
                        <!-- general form elements -->
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Add Item</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <form action="{{ route('items.store') }}" method="POST" autocomplete="off" enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Item Name</label>
                                        <input type="text" class="form-control" name="name" id="exampleInputEmail1"
                                            placeholder="Enter item name">
                                        @error('name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Item Description</label>
                                        <input type="text" class="form-control" name="description"
                                            id="exampleInputEmail1" placeholder="Enter item description">
                                        @error('description')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Item Price</label>
                                        <input type="text" class="form-control" name="price" id="exampleInputPassword1"
                                            placeholder="Enter item price">
                                        @error('price')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Status</label>
                                        <select class="form-control" name="status" aria-label="Default select example">
                                            <option selected disabled>-- Select Status --</option>
                                            <option value="active">Active</option>
                                            <option value="inactive">Inactive</option>
                                        </select>
                                        @error('status')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Item Image</label>
                                        <input type="file" class="form-control" name="item_image"
                                            id="exampleInputPassword1" placeholder="Enter item image path">
                                        @error('item_image')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

<div class="form-group">
    <label for="categorySelect">Category</label>
    <select class="form-control" name="category" id="categorySelect" onchange="toggleCustomCategory(this.value)">
        <option selected disabled>-- Select Category --</option>
        
        {{-- Standard Pre-defined Categories --}}
        <option value="Fast Food" {{ old('category') == 'Fast Food' ? 'selected' : '' }}>🍔 Fast Food</option>
        <option value="BBQ" {{ old('category') == 'BBQ' ? 'selected' : '' }}>🔥 BBQ</option>
        <option value="Traditional" {{ old('category') == 'Traditional' ? 'selected' : '' }}>🍲 Traditional (Desi)</option>
        <option value="Chinese" {{ old('category') == 'Chinese' ? 'selected' : '' }}>🥢 Chinese</option>
        <option value="Desserts" {{ old('category') == 'Desserts' ? 'selected' : '' }}>🍰 Desserts</option>
        <option value="Beverages" {{ old('category') == 'Beverages' ? 'selected' : '' }}>🍹 Beverages</option>

        {{-- ➕ Option to add a completely new category --}}
        <option value="custom_option" {{ old('category') == 'custom_option' ? 'selected' : '' }} style="font-weight: bold; color: #007bff;">➕ Add New Category</option>
    </select>
    
    @error('category')
        <span class="text-danger">{{ $message }}</span>
    @enderror
</div>

{{-- 💡 Hidden Input Field: Yeh sirf tab dikhega jab user "Add New Category" select karega --}}
<div class="form-group" id="customCategoryGroup" style="display: {{ old('category') == 'custom_option' ? 'block' : 'none' }}; margin-top: 15px;">
    <label for="custom_category">Enter New Category Name</label>
    <input type="text" class="form-control" name="custom_category" id="custom_category" 
           value="{{ old('custom_category') }}" placeholder="e.g. Pizza, Continental, Italian">
    @error('custom_category')
        <span class="text-danger">{{ $message }}</span>
    @enderror
</div>
                                </div>
                                <!-- /.card-body -->

                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form>
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
@endsection
