@extends('layout.index')
@extends('layout.nav')
@extends('layout.sidebar')

@section('home')
    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show mt-2" role="alert">
                                <h5><i class="icon fas fa-check"></i> Success!</h5>
                                {{ session('success') }}
                            </div>
                        @endif

                        <div class="card card-success card-outline mt-3">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-comments text-success mr-2"></i>Conversations
                                </h3>
                            </div>

                            <div class="card-body table-responsive p-0">
                                <table class="table table-hover table-bordered table-striped text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>User</th>
                                            <th>Role</th>
                                            <th>Latest Message</th>
                                            <th>Time</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($messages as $msg)
                                            @php
                                                $otherUser = ($msg->sender_id === auth()->id()) ? $msg->receiver : $msg->sender;
                                            @endphp
                                            <tr>
                                                <td><strong>{{ $otherUser->name ?? 'Unknown User' }}</strong></td>
                                                <td>
                                                    <span class="badge badge-secondary">
                                                        {{ str_replace('_', ' ', strtoupper($otherUser->role ?? 'N/A')) }}
                                                    </span>
                                                </td>
                                                <td style="max-width: 350px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                                    @if($msg->sender_id === auth()->id())
                                                        <span class="text-muted">You: </span>
                                                    @endif
                                                    {{ $msg->message }}
                                                </td>
                                                <td>{{ $msg->created_at->diffForHumans() }}</td>
                                                <td class="text-center">
                                                    <a href="{{ route('contact.chat', $otherUser->id) }}" class="btn btn-sm btn-success">
                                                        <i class="fas fa-comment-dots mr-1"></i> Open Chat
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-4 text-muted">
                                                    <i class="fas fa-folder-open fa-2x mb-2 d-block"></i>
                                                    No conversations found.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection