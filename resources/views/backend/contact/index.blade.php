@extends('layout.index')
@extends('layout.nav')
@extends('layout.sidebar')

@section('home')
    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-success card-outline mt-3">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-inbox text-success mr-2"></i>Inbox Messages
                                </h3>
                            </div>

                            <div class="card-body table-responsive p-0">
                                <table class="table table-hover table-bordered table-striped text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>Sender Name</th>
                                            <th>Role</th>
                                            <th>Subject</th>
                                            <th>Message</th>
                                            <th>Received At</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($messages as $msg)
                                            <tr>
                                                <td>{{ $msg->sender->name ?? 'Unknown' }}</td>
                                                <td>
                                                    <span class="badge badge-secondary">
                                                        {{ str_replace('_', ' ', strtoupper($msg->sender->role ?? 'N/A')) }}
                                                    </span>
                                                </td>
                                                <td><strong>{{ $msg->subject }}</strong></td>
                                                <td style="max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                                    {{ $msg->message }}
                                                </td>
                                                <td>{{ $msg->created_at->diffForHumans() }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-4 text-muted">
                                                    <i class="fas fa-folder-open fa-2x mb-2 d-block"></i>
                                                    No messages found in your inbox.
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