@extends('layout.index')
@extends('layout.nav')
@extends('layout.sidebar')

@section('home')
    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-info card-outline">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-user-edit text-info mr-2"></i>Edit Staff Member
                                </h3>
                            </div>

                            <form action="{{ route('staff.update', $staff->id) }}" method="POST" autocomplete="off">
                                @csrf
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="name">Full Name</label>
                                        <input type="text" 
                                            class="form-control @error('name') is-invalid @enderror" 
                                            name="name" 
                                            id="name"
                                            placeholder="Enter staff member name"
                                            value="{{ old('name', $staff->name) }}">
                                        @error('name')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="email">Email Address</label>
                                        <input type="email" 
                                            class="form-control @error('email') is-invalid @enderror" 
                                            name="email" 
                                            id="email"
                                            placeholder="Enter email address"
                                            value="{{ old('email', $staff->email) }}">
                                        @error('email')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                        <small class="form-text text-muted">
                                            This email must be unique
                                        </small>
                                    </div>

                                    <div class="form-group">
                                        <label for="phone">Mobile Number</label>
                                        <input type="text" 
                                            class="form-control @error('phone') is-invalid @enderror" 
                                            name="phone" 
                                            id="phone"
                                            placeholder="Enter mobile number"
                                            value="{{ old('phone', $staff->phone) }}">
                                        @error('phone')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                        <small class="form-text text-muted">
                                            This phone number must be unique
                                        </small>
                                    </div>

                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle mr-2"></i>
                                        <strong>Note:</strong> Staff member's password cannot be changed from here. 
                                        Use the password reset feature if needed.
                                    </div>
                                </div>

                                <div class="card-footer">
                                    <button type="submit" class="btn btn-info">
                                        <i class="fas fa-save mr-1"></i>Update Staff Member
                                    </button>
                                    <a href="{{ route('staff.index') }}" class="btn btn-secondary ml-2">
                                        <i class="fas fa-times mr-1"></i>Cancel
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
