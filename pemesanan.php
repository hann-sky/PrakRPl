<?php
include 'koneksi.php';

$sql = "SELECT id, nama, destinasi, jumlah_tiket, tanggal_pemesanan FROM pemesanan";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo '<table border="1">';
    echo '<tr><th>ID</th><th>Nama</th><th>Destinasi</th><th>Jumlah Tiket</th><th>Tanggal Pemesanan</th></tr>';
    while($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . $row["id"] . '</td>';
        echo '<td>' . $row["nama"] . '</td>';
        echo '<td>' . $row["destinasi"] . '</td>';
        echo '<td>' . $row["jumlah_tiket"] . '</td>';
        echo '<td>' . $row["tanggal_pemesanan"] . '</td>';
        echo '</tr>';
    }
    echo '</table>';
} else {
    echo "0 results";
}

$conn->close();
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pemesanan Tiket</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="content">
        <header>
            <h2>Daftar Pemesanan Tiket</h2>
        </header>
        <main>
        <?php
            include 'koneksi.php';

            $sql = "SELECT id, nama, destinasi, jumlah_tiket, tanggal_pemesanan FROM pemesanan";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo '<table border="1">';
                echo '<tr><th>ID</th><th>Nama</th><th>Destinasi</th><th>Jumlah Tiket</th><th>Tanggal Pemesanan</th></tr>';
                while($row = $result->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>' . $row["id"] . '</td>';
                    echo '<td>' . $row["nama"] . '</td>';
                    echo '<td>' . $row["destinasi"] . '</td>';
                    echo '<td>' . $row["jumlah_tiket"] . '</td>';
                    echo '<td>' . $row["tanggal_pemesanan"] . '</td>';
                    echo '</tr>';
                }
                echo '</table>';
            } else {
                echo "0 results";
            }

            $conn->close();
            ?>

        </main>
    </div>
    <script>
              document.addEventListener('DOMContentLoaded', function () {
    fetch('get_active_tickets.php')
        .then(response => response.json())
        .then(data => {
            const activeTicketsContainer = document.getElementById('active-tickets');
            data.active_tickets.forEach(ticket => {
                const ticketElement = createTicketElement(ticket, true);
                activeTicketsContainer.appendChild(ticketElement);
            });
        });

    fetch('get_purchase_history.php')
        .then(response => response.json())
        .then(data => {
            const purchaseHistoryContainer = document.getElementById('purchase-history');
            data.purchase_history.forEach(ticket => {
                const ticketElement = createTicketElement(ticket, false);
                purchaseHistoryContainer.appendChild(ticketElement);
            });
        });
});

function createTicketElement(ticket, isActive) {
    const ticketDiv = document.createElement('div');
    ticketDiv.classList.add('ticket');

    const img = document.createElement('img');
    img.src = ticket.image || 'default_image.png';
    img.alt = 'Gambar Wisata';

    const infoDiv = document.createElement('div');
    infoDiv.classList.add('ticket-info');

    const title = document.createElement('h4');
    title.textContent = ticket.title;

    const date = document.createElement('p');
    date.textContent = ticket.date;

    const countPrice = document.createElement('p');
    countPrice.textContent = `${ticket.count}x Tiket Rp. ${ticket.price}`;

    infoDiv.appendChild(title);
    infoDiv.appendChild(date);
    infoDiv.appendChild(countPrice);

    const button = document.createElement('button');
    button.textContent = isActive ? 'Cetak E - tiket' : 'Lihat E - tiket';
    button.addEventListener('click', () => generatePDF(ticket.invoice));

    ticketDiv.appendChild(img);
    ticketDiv.appendChild(infoDiv);
    ticketDiv.appendChild(button);

    return ticketDiv;
}

function generatePDF(invoiceId) {
    window.location.href = `invoice.html?invoice=${invoiceId}`;
}

    </script>

</body>
</html>