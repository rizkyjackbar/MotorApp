<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            padding: 20px;
        }

        form {
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <h1 class="mb-4">Admin</h1>
    <hr>

    <?php
    // koneksi ke database
    include 'koneksi.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
        $nama = $_POST['nama'];
        $alamat = $_POST['alamat'];
        $motor = $_POST['motor'];
        $status = $_POST['status'];
        // tambah data ke database
        $sql = "INSERT INTO anggota (nama_anggota, alamat, motor, status) VALUES ('$nama', '$alamat', '$motor', '$status')";

        if ($conn->query($sql) === TRUE) {
            echo '<div id="alert_success" class="alert alert-success" role="alert">Anggota berhasil ditambahkan.</div>';
        } else {
            echo '<div id="alert_error" class="alert alert-danger" role="alert">Terjadi kesalahan: ' . $conn->error . '</div>';
        }
    }

    if (isset($_POST["hapus"])) {
        // Ambil ID anggota yang akan dihapus
        $id_anggota = $_POST["id_anggota"];

        // menghapus anggota dari database
        $sql_delete = "DELETE FROM anggota WHERE id_anggota = '$id_anggota'";

        if ($conn->query($sql_delete) === TRUE) {
            echo '<div id="alert_success" class="alert alert-success" role="alert">Anggota berhasil dihapus.</div>';
        } else {
            echo '<div id="alert_error" class="alert alert-danger" role="alert">Terjadi kesalahan saat menghapus anggota: ' . $conn->error . '</div>';
        }
    }

    if (isset($_POST["simpan_perubahan"])) {
        // Ambil data dari db
        $id_anggota = $_POST['id_anggota'];
        $nama = $_POST['nama'];
        $alamat = $_POST['alamat'];
        $motor = $_POST['motor'];
        $status = $_POST['status'];

        // edit data anggota di database
        $sql_update = "UPDATE anggota SET nama_anggota='$nama', alamat='$alamat', motor='$motor', status='$status' WHERE id_anggota='$id_anggota'";

        if ($conn->query($sql_update) === TRUE) {
            echo '<div id="alert_success" class="alert alert-success" role="alert">Perubahan berhasil disimpan.</div>';
        } else {
            echo '<div id="alert_error" class="alert alert-danger" role="alert">Terjadi kesalahan saat menyimpan perubahan: ' . $conn->error . '</div>';
        }
    }
    ?>

    <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
        <h3>Tambah Anggota</h3>
        <div class="mb-3">
            <label for="nama" class="form-label">Nama:</label>
            <input type="text" class="form-control" id="nama" name="nama" required>
        </div>
        <div class="mb-3">
            <label for="alamat" class="form-label">Alamat:</label>
            <input type="text" class="form-control" id="alamat" name="alamat" required>
        </div>
        <div class="mb-3">
            <label for="motor" class="form-label">Motor:</label>
            <input type="text" class="form-control" id="motor" name="motor" required>
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Status:</label>
            <select class="form-select" id="status" name="status" required>
                <option value="Aktif">Aktif</option>
                <option value="Tidak Aktif">Tidak Aktif</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary" name="submit">Tambah Anggota</button>
        <hr>
    </form>

    <!-- menampilkan anggota -->
    <h2>Daftar Anggota</h2>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th scope="col">Nama</th>
                    <th scope="col">Alamat</th>
                    <th scope="col">Motor</th>
                    <th scope="col">Status</th>
                    <th scope="col">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Ambil data anggota dari database
                $sql = "SELECT * FROM anggota";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["nama_anggota"] . "</td>";
                        echo "<td>" . $row["alamat"] . "</td>";
                        echo "<td>" . $row["motor"] . "</td>";
                        echo "<td>" . $row["status"] . "</td>";
                        echo "<td>";
                        echo "<button type='button' class='btn btn-sm btn-warning' style='margin-right: 2px;' data-bs-toggle='modal' data-bs-target='#editModal" . $row["id_anggota"] . "'>Edit</button>";
                        echo "<form method='post' style='display: inline; margin-left: 2px;'>";
                        echo "<input type='hidden' name='id_anggota' value='" . $row["id_anggota"] . "'>";
                        echo "<button type='submit' class='btn btn-sm btn-danger' name='hapus'>Hapus</button>";
                        echo "</form>";
                        echo "</td>";

                        echo "</tr>";
                        // Modal untuk edit anggota
                        echo "<div class='modal fade' id='editModal" . $row["id_anggota"] . "' tabindex='-1' aria-labelledby='editModalLabel' aria-hidden='true'>";
                        echo "<div class='modal-dialog'>";
                        echo "<div class='modal-content'>";
                        echo "<div class='modal-header'>";
                        echo "<h5 class='modal-title' id='editModalLabel'>Edit Anggota</h5>";
                        echo "<button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>";
                        echo "</div>";
                        echo "<div class='modal-body'>";
                        echo "<form method='post'>";
                        echo "<input type='hidden' name='id_anggota' value='" . $row["id_anggota"] . "'>";
                        echo "<div class='mb-3'>";
                        echo "<label for='editNama' class='form-label'>Nama:</label>";
                        echo "<input type='text' class='form-control' id='editNama' name='nama' value='" . $row["nama_anggota"] . "' required>";
                        echo "</div>";
                        echo "<div class='mb-3'>";
                        echo "<label for='editAlamat' class='form-label'>Alamat:</label>";
                        echo "<input type='text' class='form-control' id='editAlamat' name='alamat' value='" . $row["alamat"] . "' required>";
                        echo "</div>";
                        echo "<div class='mb-3'>";
                        echo "<label for='editMotor' class='form-label'>Motor:</label>";
                        echo "<input type='text' class='form-control' id='editMotor' name='motor' value='" . $row["motor"] . "' required>";
                        echo "</div>";
                        echo "<div class='mb-3'>";
                        echo "<label for='editStatus' class='form-label'>Status:</label>";
                        echo "<select class='form-select' id='editStatus' name='status' required>";
                        echo "<option value='Aktif' " . ($row["status"] == "Aktif" ? "selected" : "") . ">Aktif</option>";
                        echo "<option value='Tidak Aktif' " . ($row["status"] == "Tidak Aktif" ? "selected" : "") . ">Tidak Aktif</option>";
                        echo "</select>";
                        echo "</div>";
                        echo "<button type='submit' class='btn btn-primary' name='simpan_perubahan'>Simpan Perubahan</button>";
                        echo "</form>";
                        echo "</div>";
                        echo "</div>";
                        echo "</div>";
                        echo "</div>";
                    }
                } else {
                    echo "<tr><td colspan='5'>Tidak ada anggota</td></tr>";
                }

                // Tutup koneksi database
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>

    <script>
        window.setTimeout(function() {
            document.getElementById("alert_success").style.display = "none";
            document.getElementById("alert_error").style.display = "none";
        }, 3000);
    </script>
</body>
</html>