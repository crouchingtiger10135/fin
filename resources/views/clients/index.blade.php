@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Client Compliance Status</h1>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addClientModal">
            + Add Client
        </button>
    </div>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Total number of compliant clients <span class="text-muted">the last week</span></h5>
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3>Total Clients</h3>
                    <p>{{ $totalClients }} <span class="text-success"><i class="fas fa-arrow-up"></i> {{ number_format($percentageIncrease, 2) }}%</span></p>
                    <p class="text-muted">Was {{ $totalClients - ($totalClients * $percentageIncrease / 100) }} - 1 week ago</p>
                </div>
                <div>
                    <canvas id="clientsChart" width="200" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-white">
            <h4>Clients</h4>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Clients</th>
                        <th>Checks</th>
                        <th>Identity Verified</th>
                        <th>708 Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($clients as $client)
                        <tr>
                            <td>{{ $client->name }}</td>
                            <td>{{ $client->checks_count }}</td>
                            <td>
                                @if($client->identity_verified)
                                    <span class="text-success status-dot">●</span>
                                @else
                                    <span class="text-danger status-dot">●</span>
                                @endif
                            </td>
                            <td>
                                @if($client->documents->isNotEmpty())
                                    <span class="text-success status-dot">●</span>
                                @else
                                    <span class="text-danger status-dot">●</span>
                                @endif
                            </td>
                            <td>
                                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#editClientModal{{ $client->id }}">
                                    Actions
                                </button>
                                <!-- Edit Client Modal -->
                                <div class="modal fade" id="editClientModal{{ $client->id }}" tabindex="-1" role="dialog" aria-labelledby="editClientModalLabel{{ $client->id }}" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editClientModalLabel{{ $client->id }}">Edit Client</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ route('clients.update', $client->id) }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="form-group">
                                                        <label for="name">Name</label>
                                                        <input type="text" class="form-control" id="name" name="name" value="{{ $client->name }}" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="email">Email</label>
                                                        <input type="email" class="form-control" id="email" name="email" value="{{ $client->email }}" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="document">Upload Document</label>
                                                        <input type="file" class="form-control" id="document" name="document">
                                                    </div>
                                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                                </form>
                                                <hr>
                                                <h5>Documents</h5>
                                                <ul class="list-group">
                                                    @foreach($client->documents as $document)
                                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                                            <a href="{{ Storage::url($document->document_path) }}" target="_blank">{{ $document->document_name }}</a>
                                                            <form action="{{ route('documents.destroy', $document->id) }}" method="POST" style="display:inline;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                                                            </form>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                                <form action="{{ route('clients.verify', $client->id) }}" method="POST" style="margin-top: 20px;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-warning">Verify Identity</button>
                                                </form>
                                                <hr>
                                                <form action="{{ route('clients.destroy', $client->id) }}" method="POST" style="margin-top: 20px;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Delete Client</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Client Modal -->
    <div class="modal fade" id="addClientModal" tabindex="-1" role="dialog" aria-labelledby="addClientModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addClientModalLabel">Add Client</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('clients.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="document">Upload Document</label>
                            <input type="file" class="form-control" id="document" name="document">
                        </div>
                        <button type="submit" class="btn btn-primary">Add Client</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    var ctx = document.getElementById('clientsChart').getContext('2d');
    var clientsChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Approved Clients', 'Unapproved Clients', 'Identity Verification', '708 Status'],
            datasets: [{
                data: [70, 30, 40, 60], // Example data
                backgroundColor: ['#28a745', '#dc3545', '#6f42c1', '#fd7e14'],
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
        }
    });
</script>
@endsection
