<?php
// Membuat class dengan nama database
class database
{
    // Deklarasi variabel dengan akses private
    var $host = 'localhost';
    var $username = 'root';
    var $password = '';
    var $database = 'db_emading';
    var $koneksi;

    function __construct()
    {
        $this->koneksi = mysqli_connect($this->host, $this->username, $this->password, $this->database);
        if (mysqli_connect_errno()) {
            echo "Koneksi database Gagal : " . mysqli_connect_error();
        }
    }

    // Get Data tb_users
    public function get_data_users($username)
    {
        $username = mysqli_real_escape_string($this->koneksi, $username); // Escaping input
        $data = mysqli_query($this->koneksi, "SELECT * FROM tb_users WHERE username ='$username'");
        return $data;
    }

    // Get data tb_artikel untuk halaman landing
    //Get Data tb_artikel halaman landing
    public function tampil_data_landing()
    {
        $data = mysqli_query($this->koneksi, "SELECT id_artikel, header, judul_artikel, isi_artikel, status_publish, tba.created_at, 
       tba.updated_at, name, tba.id_users FROM tb_artikel tba join tb_users tbu on tba.id_users = tbu.id_users 
       WHERE status_publish =  'publish' ");
        if ($data) {
            if (mysqli_num_rows($data) > 0) {
                while ($row = mysqli_fetch_array($data)) {
                    $hasil[] = $row;
                }
            } else {
                $hasil = '0';
            }
        }
        return $hasil;
    }

    // Get data semua artikel
    public function tampil_data()
    {
        $query = "SELECT id_artikel, header, judul_artikel, isi_artikel, status_publish, tba.created_at, tba.updated_at, name, tba.id_users 
                  FROM tb_artikel tba 
                  JOIN tb_users tbu ON tba.id_users = tbu.id_users";

        $result = mysqli_query($this->koneksi, $query);
        $hasil = [];

        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $hasil[] = $row;
            }
        }

        return $hasil;
    }

    // Tambah data artikel baru
    public function tambah_data($header, $judul_artikel, $isi_artikel, $status_publish, $id_users)
    {
        $datetime = date('Y-m-d H:i:s');
        $header = mysqli_real_escape_string($this->koneksi, $header);
        $judul_artikel = mysqli_real_escape_string($this->koneksi, $judul_artikel);
        $isi_artikel = mysqli_real_escape_string($this->koneksi, $isi_artikel);
        $status_publish = mysqli_real_escape_string($this->koneksi, $status_publish);
        $id_users = mysqli_real_escape_string($this->koneksi, $id_users);

        $query = "INSERT INTO tb_artikel (header, judul_artikel, isi_artikel, status_publish, id_users, created_at) 
                  VALUES ('$header', '$judul_artikel', '$isi_artikel', '$status_publish', '$id_users', '$datetime')";

        return mysqli_query($this->koneksi, $query);
    }

    // Mendapatkan artikel berdasarkan ID
    public function get_by_id($id_artikel)
    {
        $id_artikel = mysqli_real_escape_string($this->koneksi, $id_artikel);
        $query = "SELECT id_artikel, header, judul_artikel, isi_artikel, status_publish, tba.created_at, tba.updated_at, name, tba.id_users 
                  FROM tb_artikel tba 
                  JOIN tb_users tbu ON tba.id_users = tbu.id_users 
                  WHERE id_artikel = '$id_artikel'";

        $result = mysqli_query($this->koneksi, $query);
        return mysqli_fetch_array($result, MYSQLI_ASSOC);
    }

    // Update data artikel
    public function update_data($header, $judul_artikel, $isi_artikel, $status_publish, $id_artikel, $id_users)
    {
        $datetime = date("Y-m-d H:i:s");
        $id_artikel = mysqli_real_escape_string($this->koneksi, $id_artikel);
        $id_users = mysqli_real_escape_string($this->koneksi, $id_users);
        $header = mysqli_real_escape_string($this->koneksi, $header);
        $judul_artikel = mysqli_real_escape_string($this->koneksi, $judul_artikel);
        $isi_artikel = mysqli_real_escape_string($this->koneksi, $isi_artikel);
        $status_publish = mysqli_real_escape_string($this->koneksi, $status_publish);

        if ($header === 'not_set') {
            $query = "UPDATE tb_artikel 
                      SET judul_artikel = '$judul_artikel', isi_artikel = '$isi_artikel', status_publish = '$status_publish', id_users = '$id_users', updated_at = '$datetime' 
                      WHERE id_artikel = '$id_artikel'";
        } else {
            $query = "UPDATE tb_artikel 
                      SET header = '$header', judul_artikel = '$judul_artikel', isi_artikel = '$isi_artikel', status_publish = '$status_publish', id_users = '$id_users', updated_at = '$datetime' 
                      WHERE id_artikel = '$id_artikel'";
        }

        return mysqli_query($this->koneksi, $query);
    }

    // Hapus artikel berdasarkan ID
    public function delete_data($id_artikel)
    {
        $id_artikel = mysqli_real_escape_string($this->koneksi, $id_artikel);
        $query = "DELETE FROM tb_artikel WHERE id_artikel = '$id_artikel'";
        return mysqli_query($this->koneksi, $query);
    }
}
