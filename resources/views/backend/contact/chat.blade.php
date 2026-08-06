@extends('layout.index')
@extends('layout.nav')
@extends('layout.sidebar')

@section('home')
    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid pt-3">
                <div class="row justify-content-center">
                    <div class="col-md-9">
                        
                        <!-- AdminLTE Direct Chat -->
                        <div class="card card-success card-outline direct-chat direct-chat-success shadow">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-comments mr-2 text-success"></i>
                                    Chat with <strong>{{ $chatUser->name }}</strong>
                                    <span class="badge badge-secondary ml-2">
                                        {{ str_replace('_', ' ', strtoupper($chatUser->role)) }}
                                    </span>
                                </h3>
                                <div class="card-tools">
                                    <a href="{{ route('contact.index') }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-arrow-left mr-1"></i> Back to Inbox
                                    </a>
                                </div>
                            </div>
                            
                            <!-- Chat Box Body -->
                            <div class="card-body">
                                <div class="direct-chat-messages" id="chatBox" style="height: 420px; overflow-y: auto;">
                                    
                                    @forelse($messages as $msg)
                                        @if($msg->sender_id === auth()->id())
                                            <!-- Mine (Sent Message) -->
                                            <div class="direct-chat-msg right mb-3">
                                                <div class="direct-chat-infos clearfix">
                                                    <span class="direct-chat-name float-right">You</span>
                                                    <span class="direct-chat-timestamp float-left text-muted small">{{ $msg->created_at->format('d M h:i A') }}</span>
                                                </div>
                                                <div class="direct-chat-text bg-success border-0 text-white float-right" style="max-width: 75%; border-radius: 12px 12px 0px 12px; padding: 10px 14px;">
                                                    {{ $msg->message }}
                                                </div>
                                            </div>
                                        @else
                                            <!-- Received Message -->
                                            <div class="direct-chat-msg mb-3">
                                                <div class="direct-chat-infos clearfix">
                                                    <span class="direct-chat-name float-left">{{ $chatUser->name }}</span>
                                                    <span class="direct-chat-timestamp float-right text-muted small">{{ $msg->created_at->format('d M h:i A') }}</span>
                                                </div>
                                                <div class="direct-chat-text bg-light float-left" style="max-width: 75%; border-radius: 12px 12px 12px 0px; padding: 10px 14px;">
                                                    {{ $msg->message }}
                                                </div>
                                            </div>
                                        @endif
                                    @empty
                                        <div class="text-center text-muted my-5">
                                            <i class="fas fa-comments fa-3x mb-2 text-secondary"></i>
                                            <p>No previous messages. Type below to start the conversation!</p>
                                        </div>
                                    @endforelse

                                </div>
                            </div>
                            
                            <!-- Chat Input Form -->
                            <div class="card-footer">
                                <form action="{{ route('contact.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="receiver_id" value="{{ $chatUser->id }}">
                                    <div class="input-group">
                                        <input type="text" name="message" placeholder="Type a message..." class="form-control" required autofocus autocomplete="off">
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-success">
                                                <i class="fas fa-paper-plane mr-1"></i> Send
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </section>
    </div>

    @push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var chatBox = document.getElementById("chatBox");
            if (chatBox) {
                chatBox.scrollTop = chatBox.scrollHeight;
            }
        });
    </script>
    @endpush
@endsection