<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel DataTables</title>

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
            <div class="card">
                <div class="card-body">
                    <h1 class="mb-4">Data User</h1>
                    <!-- Tabel DataTables -->
                    <table id="usersTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Address</th>
                                <th>Phone</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
    </div>

    <!-- Modal Detail Pengguna -->
    <div class="modal fade" id="userDetailModal" tabindex="-1" aria-labelledby="userDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userDetailModalLabel">Detail Pengguna</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="userDetailContent">
                    <!-- Konten Detail Pengguna -->
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Script DataTables -->
    <script>
        $(document).ready(function() {
            var table = $('#usersTable').DataTable({
                processing: true,
                serverSide: false,
                ajax: {
                    url: '{{ url('/api/users') }}',
                    dataSrc: ''
                },
                columns: [
                    { data: 'id' },
                    {
                        data: 'name',
                        render: function(data, type, row) {
                            return `<a href="#" class="user-detail" data-user='${JSON.stringify(row)}'>${data}</a>`;
                        }
                    },
                    { data: 'email' },
                    {
                    data: 'address',
                    render: function(data) {
                        return data.street + ', ' + data.zipcode + ', ' + data.city + ', ' + data.zipcode ;
                    }
                },
                    { data: 'phone' }
                ]
            });
// Search Value By NAME
            $('#usersTable_filter input').on('keyup', function() {
                table.column(1).search(this.value).draw();
            });

            $('#usersTable').on('click', '.user-detail', function(e) {
                e.preventDefault();

                var user = $(this).data('user');

                var userDetailHtml = `
                    <p><strong>ID:</strong> ${user.id}</p>
                    <p><strong>Name:</strong> ${user.name}</p>
                    <p><strong>Username:</strong> ${user.username}</p>
                    <p><strong>Email:</strong> ${user.email}</p>
                    <p><strong>Address:</strong></p>
                    <ul>
                        <li><strong>Street:</strong> ${user.address.street}</li>
                        <li><strong>Suite:</strong> ${user.address.suite}</li>
                        <li><strong>City:</strong> ${user.address.city}</li>
                        <li><strong>Zip Code:</strong> ${user.address.zipcode}</li>
                        <li><strong>Geo Location:</strong> Lat: ${user.address.geo.lat}, Lng: ${user.address.geo.lng}</li>

                    </ul>
                    <p><strong>Phone:</strong> ${user.phone}</p>
                    <p><strong>Website:</strong> ${user.website}</p>
                    <p><strong>Company:</strong></p>
                    <ul>
                        <li><strong>Name:</strong> ${user.company.name}</li>
                        <li><strong>Catch Phrase:</strong> ${user.company.catchPhrase}</li>
                        <li><strong>BS:</strong> ${user.company.bs}</li>
                    </ul>
                    `
                    ;

                $('#userDetailContent').html(userDetailHtml);
                $('#userDetailModal').modal('show');
            });
        });
    </script>
</body>
</html>
