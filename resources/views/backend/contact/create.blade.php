@extends('layout.index')
@extends('layout.nav')
@extends('layout.sidebar')

@section('home')
    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        {{-- Success Notification --}}
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show mt-2" role="alert">
                                <h5><i class="icon fas fa-check"></i> Success!</h5>
                                {{ session('success') }}
                            </div>
                        @endif

                        <div class="card card-success card-outline mt-3">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-envelope text-success mr-2"></i>Contact Admin
                                </h3>
                            </div>

                            <form action="{{ route('contact.store') }}" method="POST" autocomplete="off">
                                @csrf
                                <div class="card-body">
                                    
                                    <div class="form-group">
                                        <label for="subject">Subject</label>
                                        <input type="text" 
                                            class="form-control @error('subject') is-invalid @enderror" 
                                            name="subject" 
                                            id="subject"
                                            placeholder="Enter message subject"
                                            value="{{ old('subject') }}">
                                        @error('subject')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="message">Message</label>
                                        <textarea 
                                            class="form-control @error('message') is-invalid @enderror" 
                                            name="message" 
                                            id="message" 
                                            rows="6" 
                                            placeholder="Type your message here...">{{ old('message') }}</textarea>
                                        @error('message')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                </div>

                                <div class="card-footer">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-paper-plane mr-1"></i>Send Message
                                    </button>
                                    <a href="{{ route('dashboard.index') }}" class="btn btn-secondary ml-2">
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