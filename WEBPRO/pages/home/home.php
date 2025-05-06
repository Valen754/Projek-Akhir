<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/home.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <title>Document</title>
</head>

<body>
    <header class="header">
        <a href="#home" class="brand">Tapal<span>Kuda</span></a>
        <i class='bx bx-menu' id="menu-icon"></i>
        <nav class="navbar">
            <a href="#home">Home</a>
            <a href="aboutus.html">About</a>
            <a href="menulist.html">Menu</a>
            <a href="berita.html">News</a>
            <a href="referensi.html">Reservation</a>
            <a href="contact.html">Contact</a>
            <div class="navbar-icons">
                <a href="keranjang.html" class="cart-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor"
                        class="bi bi-cart2" viewBox="0 0 16 16">
                        <path
                            d="M0 2.5A.5.5 0 0 1 .5 2H2a.5.5 0 0 1 .485.379L2.89 4H14.5a.5.5 0 0 1 .485.621l-1.5 6A.5.5 0 0 1 13 11H4a.5.5 0 0 1-.485-.379L1.61 3H.5a.5.5 0 0 1-.5-.5M3.14 5l1.25 5h8.22l1.25-5zM5 13a1 1 0 1 0 0 2 1 1 0 0 0 0-2m-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0m9-1a1 1 0 1 0 0 2 1 1 0 0 0 0-2m-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0" />
                    </svg>
                </a>
                <a>
                    <div class="dropdown">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor"
                            class="bi bi-person" viewBox="0 0 16 16">
                            <path
                                d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z" />
                        </svg>
                        <div class="dropdown-content">
                            <a href="login.html">Sign In</a>
                            <a href="register.html">Sign Up</a>
                        </div>
                    </div>
                </a>
            </div>
        </nav>
    </header>


    <section class="home" id="home">
        <div class="home-content">
            <div class="subtitle" data-aos="zoom-in">
                <h3>Welcome to</h3>
                <h1>Tapal<span>Kuda</span></h1>
                <p>
                    Nikmati kenikmatan kopi Tapal Kuda yang diambil dari biji kopi asli dan suasana yang asri.
                </p>
                <a href="menu.html" class="btn">Order Now</a>
            </div>
        </div>
    </section>

    <section class="new-menu">
        <div class="title-new-menu" data-aos="fade-down">New <span>Menu</span></div>
        <div class="container-new-menu">
            <div class="new-menu-item" data-aos="fade-right">
                <img src="https://placehold.co/300x300"
                    alt="Two glasses of frappe blend with whipped cream on top, placed on a wooden tray with avocado slices.">
                <div class="new-menu-item-content">
                    <h3>RED AND BLACK MOCKTAIL COFFEE</h3>
                    <p>Minuman mocktail yang menyegarkan ini memadukan kopi dengan sentuhan rasa manis dan asam dari
                        buah beri.</p>
                </div>
            </div>
            <div class="new-menu-item" data-aos="fade-left">
                <img src="https://placehold.co/300x300" alt="A cup of espresso-based coffee with latte art.">
                <div class="new-menu-item-content">
                    <h3>GREEN CARDA MOCTAIL COFFE</h3>
                    <p>Mocktail kopi ini mengusung cita rasa eksotis dengan sentuhan rempah dari kapulaga hijau.</p>
                </div>
            </div>
            <div class="new-menu-item" data-aos="fade-up" data-aos-duration="0">
                <img src="https://placehold.co/300x300" alt="A glass of traditional coffee with a flower decoration.">
                <div class="new-menu-item-content">
                    <h3>RISOLES</h3>
                    <p>Camilan yang terbuat dari kulit lumpia yang diisi dengan kombinasi bahan seperti daging ayam,
                        sayuran</p>
                </div>
            </div>
            <div class="new-menu-item" data-aos="fade-up" data-aos-duration="0">
                <img src="https://placehold.co/300x300" alt="A carafe of manual brew coffee with two glasses.">
                <div class="new-menu-item-content">
                    <h3>KAROKET</h3>
                    <p>camilan gurih dengan isian daging ayam atau daging sapi cincang yang dibalut dengan tepung roti
                        dan digoreng hingga renyah.g</p>
                </div>
            </div>
        </div>
    </section>



    <section class="menu-section">
        <div class="container-menu">
            <div class="header-menu" data-aos="fade-down">
                <h1>Our <span>Category</span></h1>
                <p>Discover the flavors crafted to perfection, just for you.</p>
            </div>
            <div class="menu-grid">
                <!-- Coffee -->
                <div class="menu-item" data-aos="flip-down">
                    <img src="https://placehold.co/300x200" alt="Coffee">
                    <div class="menu-info">
                        <h3>Coffee</h3>
                        <p>Dari minunan tradisional berbasis espresso sampai berbagai minuman racikan kopi terkini.</p>
                    </div>
                </div>
                <!-- Non-Coffee -->
                <div class="menu-item" data-aos="flip-down">
                    <img src="https://placehold.co/300x200" alt="Non-Coffee">
                    <div class="menu-info">
                        <h3>Non-Coffee</h3>
                        <p>Kami juga memiliki menu non-coffee untuk kamu yang ingin pilihan lain selain kopi dan untuk
                            anak - anak.</p>
                    </div>
                </div>
                <!-- Food & Snack -->
                <div class="menu-item" data-aos="flip-down">
                    <img src="https://placehold.co/300x200" alt="Food & Snack">
                    <div class="menu-info">
                        <h3>Food & Snack</h3>
                        <p>Berbagai macam makanan ringan sampai makanan utama siap menemani secangkir kopimu.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section class="gallery-section">
        <div class="container-gallery" data-aos="fade-up">
            <div class="header-gallery">
                <h1>Our <span>Gallery</span></h1>
                <p>Click on the images to view them in full size.</p>
            </div>
            <div class="gallery-grid">
                <div class="gallery-item">
                    <a href="ss2.jpg" data-lightbox="gallery" data-title="Cozy Interior">
                        <img src="https://placehold.co/300x250">
                    </a>
                </div>
                <div class="gallery-item">
                    <a href="images/gallery2.jpg" data-lightbox="gallery" data-title="Latte Art">
                        <img src="https://placehold.co/300x250">
                    </a>
                </div>
                <div class="gallery-item">
                    <a href="images/gallery3.jpg" data-lightbox="gallery" data-title="Freshly Brewed Coffee">
                        <img src="https://placehold.co/300x250">
                    </a>
                </div>
                <div class="gallery-item">
                    <a href="images/gallery4.jpg" data-lightbox="gallery" data-title="Relaxing Area">
                        <img src="https://placehold.co/300x250">
                    </a>
                </div>
                <div class="gallery-item">
                    <a href="images/gallery5.jpg" data-lightbox="gallery" data-title="Artistic Cups">
                        <img src="https://placehold.co/300x250">
                    </a>
                </div>
                <div class="gallery-item">
                    <a href="images/gallery5.jpg" data-lightbox="gallery" data-title="Artistic Cups">
                        <img src="https://placehold.co/300x250">
                    </a>
                </div>
                <div class="gallery-item">
                    <a href="images/gallery5.jpg" data-lightbox="gallery" data-title="Artistic Cups">
                        <img src="https://placehold.co/300x250">
                    </a>
                </div>
                <div class="gallery-item">
                    <a href="images/gallery5.jpg" data-lightbox="gallery" data-title="Artistic Cups">
                        <img src="https://placehold.co/300x250">
                    </a>
                </div>
                <div class="gallery-item">
                    <a href="images/gallery5.jpg" data-lightbox="gallery" data-title="Artistic Cups">
                        <img src="https://placehold.co/300x250">
                    </a>
                </div>
                <div class="gallery-item">
                    <a href="images/gallery6.jpg" data-lightbox="gallery" data-title="Delicious Desserts">
                        <img src="https://placehold.co/300x250">
                    </a>
                </div>
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="footer-container">
            <div class="footer-about">
                <h2 class="logo"><span>Tapal</span>Kuda</h2>
                <p>
                    Tempat terbaik untuk menikmati kopi berkualitas tinggi dan suasana yang nyaman. Kami hadir untuk
                    memberikan pengalaman yang tak terlupakan.
                </p>
            </div>

            <div class="footer-links">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="newhomepage1.html">Home</a></li>
                    <li><a href="aboutus.html">About Us</a></li>
                    <li><a href="menulist.html">Menu</a></li>
                    <li><a href="referensi.html">Reservation</a></li>
                    <li><a href="contact.html">Contact</a></li>
                </ul>
            </div>

            <div class="footer-contact">
                <h3>Contact Us</h3>
                <p><i class='bx bx-map'></i> Pasanggrahan Baru,Kec.Sumedang Sel.,Kab.Sumedang,Jawa Barat</p>
                <p><i class='bx bx-phone'></i> +62 8531 1514 1920</p>
                <p><i class='bx bx-envelope'></i> info@tapalkuda.com</p>
            </div>

            <div class="footer-social">
                <h3>Follow Us</h3>
                <div class="social-icons">
                    <a href="https://www.facebook.com/KedaiTapalKuda?mibextid=ZbWKwL"><i
                            class='bx bxl-facebook'></i></a>
                    <a href="https://x.com/kedaitapalkuda?s=21"><i class='bx bxl-instagram'></i></a>
                    <a href="https://instagram.com/kedaitapalkuda"><i class='bx bxl-twitter'></i></a>
                    <a href="http://www.youtube.com/@kedaikopitapalkuda2143"><i class='bx bxl-youtube'></i></a>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; 2024 TapalKuda. All Rights Reserved.</p>
        </div>
    </footer>

    <script>
        AOS.init();
    </script>


</body>

</html>