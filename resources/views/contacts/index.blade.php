@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Contacts List</h2>
        <a href="{{ route('contacts.create') }}" class="btn btn-success">
            <i class="fas fa-plus"></i> Add Contact
        </a>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    @endif

    <div class="table-responsive">
        <table class="table table-hover table-bordered align-middle">
            <thead class="thead-dark">
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($contacts as $contact)
                <tr @if(!$contact->is_active) class="table-secondary" @endif>
                    <td>{{ $contact->name }}</td>
                    <td>{{ $contact->email ?? '-' }}</td>
                    <td>{{ $contact->phone ?? '-' }}</td>
                    <td class="text-center">
                        @if($contact->is_active)
                        <a href="{{ route('contacts.merge.modal', $contact->id) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-compress-arrows-alt"></i> Merge
                        </a>
                        @else
                        <span class="badge badge-secondary">Merged</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center text-muted">No contacts found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        setTimeout(function() {
            $('.alert-success').fadeOut('slow');
        }, 5000);
    });
</script>
@endsection