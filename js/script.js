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

function copyCouponCode() {
  // Ambil elemen dengan ID 'coupon-code' yang berisi kode voucher
  const couponCode = document.getElementById("coupon-code").innerText;

  // Buat elemen input tersembunyi untuk menyalin kode
  const tempInput = document.createElement("input");
  document.body.appendChild(tempInput);

  // Set value input dengan kode voucher
  tempInput.value = couponCode;
  tempInput.select();
  tempInput.setSelectionRange(0, 99999); // Untuk perangkat mobile

  // Salin teks ke clipboard
  document.execCommand("copy");

  // Hapus elemen input setelah disalin
  document.body.removeChild(tempInput);

  // Notifikasi atau alert bahwa kode telah disalin
  alert("Kode kupon telah disalin: " + couponCode);
}

function copyResi() {
  // Ambil elemen dengan ID 'coupon-code' yang berisi kode voucher
  const couponCode = document.getElementById("resi-code").innerText;

  // Buat elemen input tersembunyi untuk menyalin kode
  const tempInput = document.createElement("input");
  document.body.appendChild(tempInput);

  // Set value input dengan kode voucher
  tempInput.value = couponCode;
  tempInput.select();
  tempInput.setSelectionRange(0, 99999); // Untuk perangkat mobile

  // Salin teks ke clipboard
  document.execCommand("copy");

  // Hapus elemen input setelah disalin
  document.body.removeChild(tempInput);

  // Notifikasi atau alert bahwa kode telah disalin
  alert("Kode kupon telah disalin: " + couponCode);
}
