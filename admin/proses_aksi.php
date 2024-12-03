<?php
include('config_query.php');
$db = new database();
session_start();
$id_users = $_SESSION['id_users'];
$aksi = $_GET['aksi'];

// echo"<pre>";
// print_r($_FILES);
// echo"</pre>";
// die;

if ($aksi == "add") {
    // Cek file sudah dipilih atau belum
    if ($_FILES["header"]["name"] != '') {
        $tmp = explode('.', $_FILES["header"]["name"]); // Memecah nama file dan extension
        $ext = end($tmp); // Mengambil extension
        $filename = $tmp[0]; // Mengambil nilai nama file tanpa extension
        $allowed_ext = array("jpg", "png", "jpeg"); // Extension file yang diizinkan

        if (in_array($ext, $allowed_ext)) { // Cek validasi extension
            if ($_FILES["header"]["size"] <= 5120000) { // Cek ukuran gambar
                $name = $filename . '_' . rand() . '.' . $ext; // Rename file gambar
                $path = "../files/" . $name; // Lokasi upload file
                $uploaded = move_uploaded_file($_FILES["header"]["tmp_name"], $path); // Memindahkan file
                
                if ($uploaded) {
                    $field = $_POST["isi_artikel"];
                    $field = str_replace("</p><p>", "\n", $field);
                    $field = str_replace("<p>", "", $field);
                    $field = str_replace("</p>", "", $field);
                    $field = str_replace("</br><br>", "\n", $field);
                    $field = str_replace("<br>", "", $field);
                    $field = str_replace("</br>", "", $field);
                    $insertData = $db->tambah_data($name, $_POST["judul_artikel"], $field, $_POST["status_publish"], $id_users);
                    // Query insert data

                    if ($insertData) {
                        echo "<script>alert('Data Berhasil ditambahkan');document.location.href ='index.php';</script>";
                    } else {
                        echo "<script>alert('Data gagal ditambahkan');document.location.href ='index.php';</script>";
                    }
                } else {
                    echo "<script>alert('Upload file gagal');document.location.href ='tambah_data.php';</script>";
                }
            } else {
                echo "<script>alert('Ukuran gambar lebih dari 5Mb');document.location.href ='tambah_data.php';</script>";
            }
        } else {
            echo "<script>alert('File yg di upload bukan ekstensi diizinkan');document.location.href ='tambah_data.php';</script>";
        }
    } else {
        echo "<script>alert('Silahkan Pilih File Gambar');document.location.href = 'tambah_data.php';</script>";
    }

} elseif ($aksi == "update") {
    $id_artikel = $_POST['id_artikel'];

    if (!empty($id_artikel)) { // Cek apakah id artikel tersedia?
        if ($_FILES['header']['name'] != '') { // Cek apakah melakukan upload file?
            // Tambahkan kode untuk upload file di sini
            $data = $db->get_by_id($id_artikel);
            // operasi hapus file
            if (file_exists('../files/' . $data['header']) && $data['header']) {
                unlink('../files/' . $data['header']);
            }

            $tmp = explode('.', $_FILES["header"]["name"]); // Memecah nama file dan extension
            $ext = end($tmp); // Mengambil extension
            $filename = $tmp[0]; // Mengambil nilai nama file tanpa extension
            $allowed_ext = array("jpg", "png", "jpeg"); // Extension file yang diizinkan

            if (in_array($ext, $allowed_ext)) { // Cek validasi extension
                if ($_FILES["header"]["size"] <= 5120000) { // Cek ukuran gambar
                    $name = $filename . '_' . rand() . '.' . $ext; // Rename file gambar
                    $path = "../files/" . $name; // Lokasi upload file
                    $uploaded = move_uploaded_file($_FILES["header"]["tmp_name"], $path); // Memindahkan file

                    if ($uploaded) {
                        $updateData = $db->update_data(
                            $name, 
                            $_POST["judul_artikel"], 
                            $_POST["isi_artikel"], 
                            $_POST["status_publish"], 
                            $_POST['id_artikel'], 
                            $id_users
                        );
                        // Query update data

                        if ($updateData) {
                            echo "<script>alert('Data Berhasil diubah');document.location.href ='index.php';</script>";
                        } else {
                            echo "<script>alert('Data gagal diubah');document.location.href ='index.php';</script>";
                        }
                    } else {
                        echo "<script>alert('Upload file gagal');document.location.href ='edit.php?id=" . $id_artikel . "';</script>";
                    }
                } else {
                    echo "<script>alert('Ukuran gambar lebih dari 5Mb');document.location.href ='edit.php?id=" . $id_artikel . "';</script>";
                }
            } else {
                echo "<script>alert('File yg di upload bukan ekstensi diizinkan');document.location.href ='edit.php?id=" . $id_artikel . "';</script>";
            }
        } else {
            // Jika tidak ada file yang diupload, gunakan 'not_set' untuk file gambar
            $updateData = $db->update_data(
                'not_set',
                $_POST['judul_artikel'],
                $_POST['isi_artikel'],
                $_POST['status_publish'],
                $_POST['id_artikel'],
                $id_users
            );

            if ($updateData) {
                echo "<script>alert('Data Berhasil di Ubah!'); document.location.href = 'index.php';</script>";
            } else {
                echo "<script>alert('Data Gagal di Ubah!'); document.location.href = 'index.php';</script>";
            }
        }
    } else {
        echo "<script>alert('Anda belum memilih Artikel'); document.location.href = 'index.php';</script>";
    }
} elseif ($aksi == "delete") {
    // Disini operasi delete data
    $id_artikel = $_GET['id'];

    if (!empty($id_artikel)) {
        $data = $db->get_by_id($id_artikel);

        // Operasi Hapus File
        if (file_exists('../files/' . $data['header']) && $data['header']) {
            unlink('../files/' . $data['header']);
        }

        $deleteData = $db->delete_data($id_artikel);

        if ($deleteData) {
            echo "<script>alert('Data Berhasil di Hapus!'); document.location.href = 'index.php';</script>";
        } else {
            echo "<script>alert('Data Gagal di Hapus!'); document.location.href = 'index.php';</script>";
        }
    } else {
        echo "<script>alert('Anda belum memilih Artikel'); document.location.href = 'index.php';</script>";
    }
} else {
    echo "<script>alert('Anda tidak mendapatkan akses untuk operasi ini!');document.location.href = 'index.php';</script>";
}
?>