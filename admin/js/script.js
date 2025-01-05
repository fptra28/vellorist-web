document.querySelectorAll(".delete-link").forEach((link) => {
  link.addEventListener("click", function (event) {
    const confirmation = confirm("Apakah Anda yakin ingin menghapus kategori ini?");
    if (!confirmation) {
      event.preventDefault(); // Batalkan penghapusan jika user memilih "Batal"
    }
  });
});

function previewImage(event) {
  const preview = document.getElementById("preview");
  const file = event.target.files[0];

  if (file) {
    const reader = new FileReader();
    reader.onload = function () {
      preview.src = reader.result; // Menampilkan gambar yang diunggah
    };
    reader.readAsDataURL(file); // Membaca file gambar
  }
}
