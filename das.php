<?php
    include 'koneksi.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekomendasi Tempat Wisata</title>
    <link rel="stylesheet" href="das.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <div class="buat-navbar">
                <ul>
                    <li><a href="#">Beranda</a></li>
                    <li><a href="login.php">Pengguna</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
            </div>
            <div>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">Action</a></li>
                    <li><a class="dropdown-item" href="#">Another action</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="#">Something else here</a></li>
                </ul>
                <form class="d-flex" role="search">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <?php
                // Koneksi ke database (pastikan Anda sudah terkoneksi dengan benar)
                include 'koneksi.php';

                // Query untuk mengambil gambar dan nama_wisata dari tabel wisata
                $sql = "SELECT gambar, nama_wisata FROM wisata";
                $result = $koneksi->query($sql);

                if ($result->num_rows > 0) {
                    $index = 0;
                    while ($row = $result->fetch_assoc()) {
                        $activeClass = $index === 0 ? 'active' : '';
                        echo '<div class="carousel-item ' . $activeClass . '">';
                        echo '<a href="dashboard.php?image=' . urlencode($row['gambar']) . '">';
                        echo '<img src="' . htmlspecialchars($row['gambar']) . '" class="d-block w-100 carousel-img" alt="Slide Image">';
                        echo '</a>';
                        echo '<div class="carousel-caption d-none d-md-block">';
                        echo '<h5>' . htmlspecialchars($row['nama_wisata']) . '</h5>';
                        echo '</div>';
                        echo '</div>';
                        $index++;
                    }
                } else {
                    echo '<div class="carousel-item">';
                    echo '<img src="placeholder.jpg" class="d-block w-100 carousel-img" alt="Placeholder Image">';
                    echo '<div class="carousel-caption d-none d-md-block">';
                    echo '<h5>No data available</h5>';
                    echo '</div>';
                    echo '</div>';
                }

                // Tutup koneksi
                $koneksi->close();
                ?>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>



    <h1>Kalender Event 2024</h1>
    <div class="calendar-container">
        <?php
        // Membuat koneksi
        include 'koneksi.php';
        
        // Memeriksa koneksi
        if (!$koneksi) {
            die("Koneksi gagal: " . mysqli_connect_error());
        }
        
        // Query to get distinct months from the 'tanggal_mulai' column
        $months_query = "SELECT DISTINCT DATE_FORMAT(tanggal_mulai, '%M') as month FROM wisata";
        $months_result = mysqli_query($koneksi, $months_query);
        
        while ($month_row = mysqli_fetch_assoc($months_result)) {
            $month = $month_row['month'];
            
            echo "<div class='calendar-month'>
                    <div class='month-header'>
                        <h2>{$month}</h2>
                    </div>
                    <div class='events'>";
            
            // Query to get events for the current month
            $events_query = "SELECT tanggal_mulai, nama_wisata FROM wisata WHERE DATE_FORMAT(tanggal_mulai, '%M') = '{$month}'";
            $events_result = mysqli_query($koneksi, $events_query);
            
            while ($event_row = mysqli_fetch_assoc($events_result)) {
                echo "<div class='event'>
                        <div class='event-icon'></div>
                        <div class='event-details'>
                            <div class='event-date'>" . date("d", strtotime($event_row['tanggal_mulai'])) . "</div>
                            <div class='event-name'>{$event_row['nama_wisata']}</div>
                        </div>
                      </div>";
            }
            
            echo "  </div>
                  </div>";
        }
        ?>
    </div>

    <section id="jelajahIndonesia">
        <h2>Jelajah Indonesia</h2>
        <ul>
            <li><a href="#tamanWisata.html">Taman Wisata</a></li>
            <li><a href="#tamanNasional.html">Taman Nasional</a></li>
        </ul>
    </section>
        
    <div class="inspirasi-section">
    <h3>Inspirasi Perjalanan</h3>
    <div class="inspirasi-container">
        <?php
        include 'koneksi.php';

        // Query untuk mengambil data dari tabel inspirasi
        $query = "SELECT * FROM inspirasi";
        $result = mysqli_query($koneksi, $query);

        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<div class="event-card">';
                echo '<img src="' . $row['gambar'] . '" alt="' . $row['nama'] . '">';
                echo '<div class="event-info">';
                echo '<h3>' . $row['nama'] . '</h3>';
                echo '<a href="detail.php?id=' . $row['nama'] . '" class="btn btn-primary">Detail</a>';
                echo '</div>';
                echo '</div>';
            }
        } else {
            echo "Query failed: " . mysqli_error($koneksi);
        }

        // Tutup koneksi
        mysqli_close($koneksi);
        ?>
    </div>
</div>
    
<div class="destinasi-section">
    <h3>Destinasi Pilihan</h3>
    <div class="destinasi-container">
        <section id="gallery">
            <div><a href="destinasi.php"><img src="asset/1.jpeg" alt="Destinasi 1"/></a></div>
            <div><a href="destinasi.php"><img src="asset/2.jpeg" alt="Destinasi 2"/></a></div>
            <div><a href="destinasi.php"><img src="asset/3.jpeg" alt="Destinasi 3"/></a></div>
            <div><a href="destinasi.php"><img src="asset/4.jpeg" alt="Destinasi 4"/></a></div>
            <div><a href="destinasi.php"><img src="asset/5.jpeg" alt="Destinasi 5"/></a></div>
            <div><a href="destinasi.php"><img src="asset/6.jpeg" alt="Destinasi 6"/></a></div>
            <div><a href="destinasi.php"><img src="asset/7.jpeg" alt="Destinasi 7"/></a></div>
            <div><a href="destinasi.php"><img src="asset/8.jpeg" alt="Destinasi 8"/></a></div>
        </section>
    </div>
</div>

    </div>
    <div class="inspirasi-section">
        <h3>Inspirasi Perjalanan</h3>
        <div class="inspirasi-container" id="inspirasi-container">

        </div>
    </div>



    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <div class="row">
                    <div class="col-xl-3 col-md-6">
                        <div class="card bg-primary text-white mb-4">
                            <div class="card-body">Promo Tiket Pesawat</div>
                            <div class="card-footer d-flex align-items-center justify-content-between">
                                <a class="small text-white stretched-link" href="#">View Details</a>
                                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card bg-warning text-white mb-4">
                            <div class="card-body">Promo Tiket Kereta</div>
                            <div class="card-footer d-flex align-items-center justify-content-between">
                                <a class="small text-white stretched-link" href="#">View Details</a>
                                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card bg-success text-white mb-4">
                            <div class="card-body">Promo Tiket</div>
                            <div class="card-footer d-flex align-items-center justify-content-between">
                                <a class="small text-white stretched-link" href="#">View Details</a>
                                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    
    <button id="myBtn">Rating</button>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
