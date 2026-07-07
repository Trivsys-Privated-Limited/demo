@extends('layout.index')
@extends('layout.nav')
@extends('layout.sidebar')

@section('home')
    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-success card-outline">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-user-plus text-success mr-2"></i>Add Kitchen Staff Member
                                </h3>
                            </div>

                            <form action="{{ route('staff.store') }}" method="POST" autocomplete="off">
                                @csrf
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="name">Full Name</label>
                                        <input type="text" 
                                            class="form-control @error('name') is-invalid @enderror" 
                                            name="name" 
                                            id="name"
                                            placeholder="Enter staff member name"
                                            value="{{ old('name') }}">
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
                                            value="{{ old('email') }}">
                                        @error('email')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="phone">Mobile Number</label>
                                        <input type="text" 
                                            class="form-control @error('phone') is-invalid @enderror" 
                                            name="phone" 
                                            id="phone"
                                            placeholder="Enter mobile number"
                                            value="{{ old('phone') }}">
                                        @error('phone')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <input type="password" 
                                            class="form-control @error('password') is-invalid @enderror" 
                                            name="password" 
                                            id="password"
                                            placeholder="Enter password (minimum 6 characters)"
                                            autocomplete="new-password">
                                        @error('password')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                        <small class="form-text text-muted">
                                            Staff will use this to login to kitchen interface
                                        </small>
                                    </div>
                                </div>

                                <div class="card-footer">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-save mr-1"></i>Add Staff Member
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
