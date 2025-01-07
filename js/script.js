// Menambahkan atau menghapus kelas navbar-scrolled saat scroll
window.addEventListener("scroll", function () {
  const navbar = document.getElementById("navbar"); // Ambil navbar berdasarkan ID
  if (window.scrollY > 0) {
    // Jika halaman discroll
    navbar.classList.add("navbar-scrolled"); // Tambahkan shadow
  } else {
    navbar.classList.remove("navbar-scrolled"); // Hapus shadow saat di posisi atas
  }
});

// Menambahkan shadow pada navbar ketika tombol navbar-toggler diklik
const navbarToggler = document.getElementById("navbar-toggler"); // Mengambil tombol navbar-toggler

navbarToggler.addEventListener("click", function () {
  const navbar = document.getElementById("navbar"); // Ambil navbar berdasarkan ID
  navbar.classList.add("navbar-scrolled"); // Tambahkan shadow ketika tombol diklik
});
