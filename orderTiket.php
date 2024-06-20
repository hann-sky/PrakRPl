<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = htmlspecialchars($_POST['nama']);
    $destinasi = htmlspecialchars($_POST['destinasi']);
    $jumlah_tiket = (int)$_POST['jumlah_tiket'];
    $tanggal_pemesanan = htmlspecialchars($_POST['tanggal_pemesanan']);

    $data = [
        'nama' => $nama,
        'destinasi' => $destinasi,
        'jumlah_tiket' => $jumlah_tiket,
        'tanggal_pemesanan' => $tanggal_pemesanan
    ];

    echo json_encode($data);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemesanan Tiket</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }
        .content {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        header {
            margin-bottom: 20px;
        }
        header h2 {
            font-size: 24px;
            color: #333;
        }
        form {
            background-color: #ffffff;
            padding: 25px;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-group input {
            width: calc(100% - 20px); /* Penyesuaian untuk border */
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }
        .submit-button, .back-button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            display: inline-block;
            margin-top: 10px;
        }
        .submit-button:hover, .back-button:hover {
            background-color: #0056b3;
        }
        .back-button a {
            color: white;
            text-decoration: none;
        }
        .popup {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
        }
        .popup-content {
            background-color: #fefefe;
            margin: auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 300px;
            border-radius: 10px;
            text-align: center;
        }
        .popup-content .close-btn {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        .popup-content .close-btn:hover,
        .popup-content .close-btn:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
        .popup-message {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        .popup-message .checkmark {
            font-size: 50px;
            color: green;
        }
        .popup-message p {
            font-size: 18px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="content">
        <header>
            <h2>Pemesanan Tiket</h2>
        </header>
        <main>
            <form id="order-form">
                <div class="form-group">
                    <label for="nama">Nama:</label>
                    <input type="text" id="nama" name="nama" required>
                </div>
                <div class="form-group">
                    <label for="destinasi">Destinasi:</label>
                    <input type="text" id="destinasi" name="destinasi" required>
                </div>
                <div class="form-group">
                    <label for="jumlah_tiket">Jumlah Tiket:</label>
                    <input type="number" id="jumlah_tiket" name="jumlah_tiket" required>
                </div>
                <div class="form-group">
                    <label for="tanggal_pemesanan">Tanggal Pemesanan:</label>
                    <input type="date" id="tanggal_pemesanan" name="tanggal_pemesanan" required>
                </div>
                <button type="submit" id="submit-btn" class="submit-button">Pesan Tiket</button>
                <button type="button" class="back-button"><a href="akunUser.php">Kembali</a></button>
            </form>
        </main>
    </div>

    <!-- Popup -->
    <div id="popup" class="popup">
        <div class="popup-content">
            <span class="close-btn">&times;</span>
            <div class="popup-message">
                <span class="checkmark">&#10003;</span>
                <p>Pesanan Diterima</p>
                <button id="printInvoiceBtn">Cetak Tiket</button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const submitBtn = document.getElementById('submit-btn');
            const printInvoiceBtn = document.getElementById('printInvoiceBtn');
            const popup = document.getElementById('popup');
            const closeBtn = document.querySelector('.close-btn');

            closeBtn.addEventListener('click', function () {
                popup.style.display = 'none';
            });

            document.getElementById('order-form').addEventListener('submit', function (event) {
                event.preventDefault(); // Mencegah pengiriman form secara default

                // Ambil data dari form
                const formData = new FormData(this);

                // Kirim data menggunakan fetch
                fetch('orderTiket.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    // Simpan data form yang diterima dari server di local storage untuk digunakan saat mencetak
                    localStorage.setItem('formData', JSON.stringify(data));
                    // Tampilkan popup
                    popup.style.display = 'flex';
                })
                .catch(error => {
                    console.error('Error:', error);
                    // Handle error jika ada
                });
            });

            printInvoiceBtn.addEventListener('click', function () {
                // Ambil data form dari local storage
                const formData = JSON.parse(localStorage.getItem('formData'));

                // Kirim data form ke generate_invoice.php untuk membuat PDF
                fetch('generate_invoice.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(formData)
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.blob();
                })
                .then(blob => {
                    // Buat URL untuk file blob
                    const url = window.URL.createObjectURL(blob);

                    // Buat elemen anchor untuk mengunduh file
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = 'invoice.pdf';
                    document.body.appendChild(a);
                    a.click();

                    // Hapus elemen anchor setelah selesai
                    document.body.removeChild(a);
                })
                .catch(error => {
                    console.error('Error:', error);
                    // Handle error jika ada
                });
            });
        });
    </script>
</body>
</html>
