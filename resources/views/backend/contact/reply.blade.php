@extends('layout.index')
@extends('layout.nav')
@extends('layout.sidebar')

@section('home')
    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-primary card-outline mt-3">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-reply text-primary mr-2"></i>Reply to {{ $originalMessage->sender->name }}
                                </h3>
                            </div>

                            <form action="{{ route('contact.store') }}" method="POST" autocomplete="off">
                                @csrf
                                {{-- HIDDEN FIELD: Taa ke controller ko pata chale ke kisko reply karna hai --}}
                                <input type="hidden" name="receiver_id" value="{{ $originalMessage->sender_id }}">

                                <div class="card-body">
                                    
                                    {{-- Original Message ki Detail dikhane ke liye --}}
                                    <div class="callout callout-info mb-4">
                                        <h5>Original Message: {{ $originalMessage->subject }}</h5>
                                        <p class="text-muted">{{ $originalMessage->message }}</p>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="subject">Subject</label>
                                        {{-- Subject mein automatically "Re: " laga diya hai --}}
                                        <input type="text" 
                                            class="form-control @error('subject') is-invalid @enderror" 
                                            name="subject" 
                                            id="subject"
                                            value="{{ old('subject', 'Re: ' . $originalMessage->subject) }}">
                                        @error('subject')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="message">Your Reply</label>
                                        <textarea 
                                            class="form-control @error('message') is-invalid @enderror" 
                                            name="message" 
                                            id="message" 
                                            rows="6" 
                                            placeholder="Type your reply here..." autofocus>{{ old('message') }}</textarea>
                                        @error('message')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                </div>

                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-paper-plane mr-1"></i>Send Reply
                                    </button>
                                    <a href="{{ route('contact.index') }}" class="btn btn-secondary ml-2">
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