<?php
include 'config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_kab_asal = $_POST['id_kab_asal'];
    $id_kab_tujuan = $_POST['id_kab_tujuan'];
    $tarif_ongkir = $_POST['tarif_ongkir'];

    // Validasi sederhana
    if (empty($id_kab_asal) || empty($id_kab_tujuan) || $tarif_ongkir === '') {
        die("Semua field wajib diisi!");
    }

    // Insert data tarif ongkir
    $insert_tarif = mysqli_query($conn, "INSERT INTO tbl_tarif (id_kab_asal, id_kab_tujuan, tarif) VALUES ('$id_kab_asal', '$id_kab_tujuan', '$tarif_ongkir')");

    if ($insert_tarif) {
        header("Location: data_tarif.php?pesan=berhasil_tambah");
        exit;
    } else {
        die("Gagal tambah tarif ongkir: " . mysqli_error($conn));
    }
} else {
    header("Location: data_tarif.php");
    exit;
}
?>