<?php
include 'init.php';
$queryKurir = mysqli_query($conn, "SELECT * FROM tbl_user WHERE role = 'kurir'");
?>

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'config/koneksi.php';

// Cek login
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<?php include 'head.php'; ?>

<body id="page-top">
    <div id="wrapper">

        <?php include 'sidebar.php'; ?>

        <div id="content-wrapper" class="d-flex flex-column">

            <div id="content">
                <?php include 'navbar.php'; ?>


                <!-- data_wilayah.php -->
                <div class="container-fluid">
                    <h1 class="h3 mb-4 text-gray-800">Wilayah Pengiriman dan Tarif</h1>

                    <div class="card shadow mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold text-primary">Data Wilayah & Tarif Ongkir</h6>

                            <?php if (isset($_GET['pesan']) && $_GET['pesan'] == 'berhasil_tambah') : ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert"
                                id="alert-success">
                                Data tarif ongkir berhasil ditambahkan!
                            </div>
                            <?php endif; ?>

                            <?php if (isset($_SESSION['level']) && $_SESSION['level'] !== 'kurir'): ?>
                            <button class="btn btn-primary btn-sm" data-toggle="modal"
                                data-target="#modalTambahWilayah">
                                <i class="fas fa-plus"></i> Tambah Tarif
                            </button>
                            <?php endif; ?>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered" id="dataTable">
                                <thead>
                                    <tr>
                                        <th>ID Wilayah</th>
                                        <th>Kabupaten Asal</th>
                                        <th>Kabupaten Tujuan</th>
                                        <th>Tarif Ongkir</th>
                                        <?php if (isset($_SESSION['level']) && $_SESSION['level'] !== 'kurir'): ?>
                                        <th>Aksi</th>
                                        <?php endif; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Query untuk mengambil data dari tabel tarif_ongkir
                                   $query = "SELECT 
    t.id_tarif,
    kab_asal.nama_kabupaten AS kabupaten_asal,
    kab_tujuan.nama_kabupaten AS kabupaten_tujuan,
    t.tarif
FROM 
    tbl_tarif t
JOIN 
    tbl_kabupaten kab_asal ON t.id_kab_asal = kab_asal.id_kab
JOIN 
    tbl_kabupaten kab_tujuan ON t.id_kab_tujuan = kab_tujuan.id_kab";

                                    $result = mysqli_query($conn, $query);
                                    
                                    // Menampilkan data
                                    while ($row = mysqli_fetch_assoc($result)) {
                                    ?>
                                    <tr>
                                        <td><?php echo $row['id_tarif']; ?></td>
                                        <td><?php echo $row['kabupaten_asal']; ?></td>
                                        <td><?php echo $row['kabupaten_tujuan']; ?></td>
                                        <td>Rp <?php echo number_format($row['tarif'], 0, ',', '.'); ?></td>
                                        <?php if (isset($_SESSION['level']) && $_SESSION['level'] !== 'kurir'): ?>
                                        <td>
                                            <a href="edit_tarif.php?id=<?php echo $row['id_tarif']; ?>"
                                                class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <a href="hapus_tarif.php?id=<?php echo $row['id_tarif']; ?>"
                                                onclick="return confirm('Yakin ingin menghapus data ini?')"
                                                class="btn btn-outline-danger btn-sm">
                                                <i class="fas fa-trash-alt"></i> Hapus
                                            </a>
                                        </td>
                                        <?php endif; ?>
                                    </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <?php include 'footer.php'; ?>
        </div>

    </div>

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <script>
    $(document).ready(function() {
        $('#dataTable').DataTable();
    });
    </script>
</body>


<?php if (isset($_SESSION['level']) && $_SESSION['level'] !== 'kurir'): ?>
<!-- Modal Tambah Wilayah -->
<div class="modal fade" id="modalTambahWilayah" tabindex="-1" role="dialog" aria-labelledby="modalLabelWilayah"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="add_tarif.php" method="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Tarif Pengiriman</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Kabupaten Asal</label>
                        <select name="id_kab_asal" class="form-control" required>
                            <option value="">-- Pilih Kabupaten Asal --</option>
                            <?php
                            $kabupaten_list = mysqli_query($conn, "SELECT * FROM tbl_kabupaten ORDER BY nama_kabupaten");
                            while ($kab = mysqli_fetch_assoc($kabupaten_list)) {
                                echo "<option value='{$kab['id_kab']}'>{$kab['nama_kabupaten']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Kabupaten Tujuan</label>
                        <select name="id_kab_tujuan" class="form-control" required>
                            <option value="">-- Pilih Kabupaten Tujuan --</option>
                            <?php
                            $kabupaten_list2 = mysqli_query($conn, "SELECT * FROM tbl_kabupaten ORDER BY nama_kabupaten");
                            while ($kab = mysqli_fetch_assoc($kabupaten_list2)) {
                                echo "<option value='{$kab['id_kab']}'>{$kab['nama_kabupaten']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Tarif Ongkir (Rp)</label>
                        <input type="number" name="tarif_ongkir" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>

</html>


<script>
// Hilangkan alert otomatis setelah 3 detik
setTimeout(function() {
    var alert = document.getElementById('alert-success');
    if (alert) {
        alert.classList.remove('show');
        alert.classList.add('fade');
        setTimeout(() => alert.remove(), 300); // hapus dari DOM setelah animasi selesai
    }
}, 3000);
</script>