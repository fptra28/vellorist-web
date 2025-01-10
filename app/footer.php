<?php
function getFooters($conn)
{
    $query = "SELECT * FROM kategori_produk LIMIT 5";

    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    return [];
}

$footer = getFooters($conn);
