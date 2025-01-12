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

// Ambil semua opsi filter dropdown
const filterOptions = document.querySelectorAll(".filter-option");

filterOptions.forEach((option) => {
  option.addEventListener("click", function (event) {
    // Ambil rating yang dipilih
    const rating = event.target.dataset.rating;

    // Dapatkan ID produk dari URL
    const urlParams = new URLSearchParams(window.location.search);
    const productId = urlParams.get("id");

    // Bangun URL baru dengan parameter rating
    let newUrl = window.location.pathname + "?id=" + productId;
    if (rating && rating !== "all") {
      newUrl += "&rating=" + rating;
    }

    // Redirect ke URL yang baru untuk memuat ulang dengan filter rating
    window.location.href = newUrl;
  });
});

document.getElementById("paymentForm").addEventListener("submit", function (e) {
  e.preventDefault(); // Mencegah pengiriman form secara default

  // Ambil data dari form
  var formData = new FormData(this);

  // Kirim data ke backend PHP menggunakan Fetch API
  fetch("../checkout/placeOrder.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json()) // Server mengembalikan JSON
    .then((data) => {
      // Jika berhasil, dapatkan snapToken dan lakukan transaksi
      if (data.snapToken) {
        // Lakukan transaksi dengan Snap Token
        window.snap.pay(data.snapToken, {
          onSuccess: function (result) {
            /* You may add your own implementation here */
            alert("payment success!");
            console.log(result);
          },
          onPending: function (result) {
            /* You may add your own implementation here */
            alert("wating your payment!");
            console.log(result);
          },
          onError: function (result) {
            /* You may add your own implementation here */
            alert("payment failed!");
            console.log(result);
          },
          onClose: function () {
            /* You may add your own implementation here */
            alert("you closed the popup without finishing the payment");
          },
        });
      } else {
        alert("Terjadi kesalahan saat memproses pembayaran: " + data.error);
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      alert("Terjadi kesalahan jaringan");
    });
});
