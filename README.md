# deka_rumahweb

Persyaratan:
1. PHP 8.23
2. Laravel versi 8.83
   
Instalasi:
1. Download File. 
2. Masuk ke directory test-project dengan (cd test-project)
3. Kemudian composer update.
4. Setelah itu jalankan program menggunakan (php artisan serve).

Kemudian Untuk Code
1. Saya menggunakan Laravel dengan menggunakan Javascript untuk menampilkan tablenya.
2. Pertama Saya membuat Controller dengan nama User Controller.
Codenya seperti ini
<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        // Ambil data dari API JSONPlaceholder
        $response = Http::get('https://jsonplaceholder.typicode.com/users');

        // Decode JSON response
        $users = $response->json();

        // Tampilkan di view
        return view('index', ['users' => $users]);
    }
}

Code diatas digunakan untuk mendapatkan tabel dari API tersebut, kemudian di return ke dalam view index yang didalamnya sudah ada struktur htmlnya beserta javascriptm menggunakan datatable.
3. Setelah itu Controller dihubungkan ke dalam web.php, didalamnya berisi route untuk menampilkan website urlnya. Menggunakan syntax seperti berikut.
Route::get('/users', [UserController::class, 'index'])->name('users');

4. Kemudian setelah itu, untuk mengecek json, dapat memanggilnya dengan mengunakan route seperti ini
Route::get('/api/users', function () {
    $response = Http::get('https://jsonplaceholder.typicode.com/users');
    return response()->json($response->json());
});

Route diatas digunakan untuk melihat dan mendapatkan api dari http request, kemudian file tersebut diolah dan ditampilkan menggunakan datatable.

5. Membuat view menggunakan table beserta javascriptnya.
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

Pada Code diatas, dapat dijelaskan bahwa Pada bagian Body merupakan Struktur Table. 
Tabel tersebut diisi menggunakan Script dari Javascript menggunakan Datatable.
Datatable tersebut dibuat menggunakan ajax yang di request melalui Route yang sudah dibuat. 
Kemudian untuk Searchnya dapat dilihat pada Tag "Search Value By Name", metode tersebut digunakan untuk search berdasarkan nama saja.
Kemudian untuk menampilkan detail dari setiap isi Tabel, dilakukan dengan cara mengklik nama user dari kolom name, kemudian akan muncul pop-up modal yang terlihat untuk melihat isi detail dari setiap namanya.
