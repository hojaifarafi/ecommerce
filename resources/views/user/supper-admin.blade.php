@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Supper Admin Dashboard</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <input type="text" class="form-control" placeholder="Search..." id="search-input-user">
                        <div id="search-results" class="mt-3"></div>
                        <div id="action-message" class="mt-3">
                            @foreach($admins as $admin)
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span>{{ $admin->name }} - {{ $admin->email }}</span>
                                    <button class="btn btn-danger" onclick="removeAdmin('{{ $admin->id }}')">Remove admin</button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        let searchInput = document.getElementById('search-input-user');
        console.log('Search input element:', searchInput);
        searchInput.addEventListener('keyup', function(event) {
            const keyword = searchInput.value;
            if (keyword.length < 3) {
                document.getElementById('search-results').innerHTML = '';
                return;
            }
            fetch(`/admin/search-users/${keyword}`)
                .then(response => response.json())
                .then(data => {
                    let resultsHtml = '<ul class="list-group">';
                    data.forEach(item => {
                        resultsHtml += `<li class="list-group-item d-flex justify-content-between align-items-center">${item.name} - ${item.email}`;
                        // Only show the button if the user is a regular user (not an admin)
                            let buttonAdd = item.utype === 'USR' ? `<button class="btn btn-primary" onclick="createAdmin('${item.id}')">Create admin</button></li>` : `<button class="btn btn-danger" onclick="removeAdmin('${item.id}')">Remove admin</button></li>`;
                        resultsHtml += buttonAdd;
                    });
                    resultsHtml += '</ul>';
                    document.getElementById('search-results').innerHTML = resultsHtml;
                })
                .catch(error => {
                    console.error('Error fetching search results:', error);
                });
        });
        function createAdmin(userId) {
            fetch(`/admin/create-admin/${userId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                //window.location.reload();
                alert(data.message);
            })
            .catch(error => {
                console.error('Error creating admin:', error);
            });
        }
        function removeAdmin(userId) {
            fetch(`/admin/remove-admin/${userId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                //window.location.reload();
                alert(data.message);
                console.log(data);
            })
            .catch(error => {
                console.error('Error removing admin:', error);
            });
        }
    </script>
@endpush