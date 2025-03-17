<?php include 'assets/php/checkAuthDashboardWithdrawal.php'; ?>
<!DOCTYPE html>
<html>
    
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Warren & Co - Leading Investment Company</title>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Outfit:wght@100;200;300;400;500;600;700&amp;family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&amp;display=swap"/>

         <!-- Bootstrap CSS -->
         <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        
         <link rel="shortcut icon" href="assets/img/logo/favicon.png" type="image/x-icon">
        
        <!-- Animate.css -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
       
         <!-- FontAwesome -->
         <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        
        <!-- Swiper CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">

        <!-- SweetAlert2 -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.17.2/dist/sweetalert2.min.css" integrity="sha256-SCRy3fXoOamBaidKByHs9iJVLYJ65R/v6ycZNN4JhmE=" crossorigin="anonymous">

        <!-- Toastify css -->
         <link rel="stylesheet" href="assets/css/toastify.css">

        <!-- Custom CSS -->
        <link rel="stylesheet" href="assets/css/style.css">
        <link rel="stylesheet" href="assets/css/responsive.css">
     

    </head>

    <body>
        <div id="__nuxt">
            <!--[-->
            <main>
                <!--[-->
                <div class="home-purple-gradient">
                    <!--[-->
                    <header id="header" class="header-layout1">
                        <div id="sticky-header" class="menu-area transparent-header">
                            <div class="container custom-container">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="menu-wrap">
                                            <nav class="menu-nav">
                                                <div class="logo">
                                                    <a href="index.php" class=""><img src="assets/img/logo/logo.png" alt="Logo" /></a>
                                                </div>
                                                <div class="navbar-wrap main-menu d-none d-lg-flex">
                                                    <ul class="navigation">
                                                        <!--[-->
                                                        <li class="">
                                                            <a aria-current="page" href="index.php" class="router-link-active router-link-exact-active"><span></span>Home</a>
                                                            <!---->
                                                        </li>
                                                        <li class="">
                                                            <a aria-current="page" href="dashboard.php" class="router-link-active router-link-exact-active"><span></span>Dashboard</a>
                                                            <!---->
                                                        </li>
                                                        <li class="">
                                                            <a aria-current="page" href="index.php#blockchain" class="router-link-active router-link-exact-active"><span></span>Why Warren & Co</a>
                                                            <!---->
                                                        </li>
                                                        <li class="">
                                                            <a aria-current="page" href="withdrawal.php" class="router-link-active router-link-exact-active"><span></span>Withdrawal</a>
                                                            <!---->
                                                        </li>
                                                        <li class="">
                                                            <a aria-current="page" href="index.php#feature" class="router-link-active router-link-exact-active"><span></span>Features</a>
                                                            <!---->
                                                        </li>
                                                        <li class="">
                                                            <a href="https://t.me/WarrenC_leading_investment" class=""><span></span>Contact</a>
                                                            <!---->
                                                        </li>
                                                        <!--]-->
                                                    </ul>
                                                </div>
                                                <div class="header-action">
                                                    <ul class="list-wrap">
                                                        <li class="header-login"><a href="assets/php/logout.php" class="btn2">LOGOUT</a></li>
                                                    </ul>
                                                </div>
                                                <div class="mobile-nav-toggler"><i class="fas fa-bars"></i></div>
                                            </nav>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </header>

                    <div class="">
                        <div class="mobile-menu">
                            <nav class="menu-box">
                                <div class="close-btn"><i class="fas fa-times"></i></div>
                                <div class="nav-logo">
                                    <a href="index.php" class=""><img src="assets/img/logo/logo.png" alt="Logo" /></a>
                                </div>
                                <div class="menu-outer">
                                    <ul class="navigation">
                                        <!--[-->
                                        <li class=""><a aria-current="page" href="index.php" class="router-link-active router-link-exact-active">Home</a></li>
                                        <li class=""><a aria-current="page" href="dashboard.php" class="router-link-active router-link-exact-active">Dashboard</a></li>
                                        <li class=""><a aria-current="page" href="index.php#blockchain" class="router-link-active router-link-exact-active">Why Warren & Co</a></li>
                                        <li class=""><a aria-current="page" href="withdrawal.php" class="router-link-active router-link-exact-active">Withdrawal</a></li>
                                        <li class=""><a aria-current="page" href="index.php#feature" class="router-link-active router-link-exact-active">Feature</a></li>
                                        
                                        <li class=""><a href="https://t.me/WarrenC_leading_investment" class="">Contact</a></li>
                                        <li class="header-login"><a href="assets/php/logout.php" class="btn2">LOGOUT</a></li>
                                        <!--]-->
                                    </ul>
                                </div>
                                
                                <div class="social-links">
                                    <ul class="clearfix list-wrap">
                                        <li>
                                            <a href="#"><i class="fab fa-facebook-f"></i></a>
                                        </li>
                                        <li>
                                            <a href="#"><i class="fab fa-twitter"></i></a>
                                        </li>
                                        <li>
                                            <a href="#"><i class="fab fa-instagram"></i></a>
                                        </li>
                                        <li>
                                            <a href="#"><i class="fab fa-linkedin-in"></i></a>
                                        </li>
                                        <li>
                                            <a href="#"><i class="fab fa-youtube"></i></a>
                                        </li>
                                    </ul>
                                </div>
                            </nav>
                        </div>
                        <div class="menu-backdrop"></div>
                    </div>
                    <!--]-->

                    <?php include 'assets/php/dashboard_section.php'; ?>

                    <section class="p-3">
                        <div class="row justify-content-center">
                            <div class="col-xl-6 col-lg-8">
                                <div class="section-title text-center mb-50 position-relative">
                                    <span class="sub-title">DAILY TASKS</span>
                                    <h2 class="title style2">Available Daily Tasks</h2>
                                    <p class="sec-text mt-0">Complete simple tasks daily to earn rewards and grow your earnings with Warren & Co.</p>
                                </div>
                            </div>
                        </div>

                        <div class="row row-cols-1 row-cols-md-2" id="taskContainer">
                            <!-- <div class="col blog-post-wrap">
                                <div class="blog-standard-post">
                                    <div class="blog-standard-thumb">
                                        <a href="blog-details/3.php" class="">
                                            <img src="assets/img/blog/blog_standard01.jpg" alt/>
                                        </a>
                                    </div>
                                    <div class="blog-standard-content">
                                        <h2 class="title">
                                            <a href="blog-details/3.php" class="">Maximizing Potential discover the Benefits of Our ICO Investment</a>
                                        </h2>
                                        <p>Our ICO is not just about financial gains; it&#39;s about being part of a movement that is shaping the future. By investing in our ICO, you become an integral part of a community that believes in the power..</p>
                                        <div class="read-more-btn">
                                            <a href="blog-details/3.php" class="">
                                                Rate($5.00)
                                                <i class="fas fa-arrow-right"></i>
                                            </a>
                                        </div>
                                    </div>
                                  </div>
                            </div>

                            <div class="col blog-post-wrap">
                                <div class="blog-standard-post">
                                    <div class="blog-standard-thumb">
                                        <a href="blog-details/3.php" class="">
                                            <img src="assets/img/blog/blog_standard01.jpg" alt/>
                                        </a>
                                    </div>
                                    <div class="blog-standard-content">
                                        <h2 class="title">
                                            <a href="blog-details/3.php" class="">Maximizing Potential discover the Benefits of Our ICO Investment</a>
                                        </h2>
                                        <p>Our ICO is not just about financial gains; it&#39;s about being part of a movement that is shaping the future. By investing in our ICO, you become an integral part of a community that believes in the power..</p>
                                        <div class="read-more-btn">
                                            <a href="blog-details/3.php" class="">
                                                Rate($5.00)
                                                <i class="fas fa-arrow-right"></i>
                                            </a>
                                        </div>
                                    </div>
                                  </div>
                            </div>

                            <div class="col blog-post-wrap">
                                <div class="blog-standard-post">
                                    <div class="blog-standard-thumb">
                                        <a href="blog-details/3.php" class="">
                                            <img src="assets/img/blog/blog_standard01.jpg" alt/>
                                        </a>
                                    </div>
                                    <div class="blog-standard-content">
                                        <h2 class="title">
                                            <a href="blog-details/3.php" class="">Maximizing Potential discover the Benefits of Our ICO Investment</a>
                                        </h2>
                                        <p>Our ICO is not just about financial gains; it&#39;s about being part of a movement that is shaping the future. By investing in our ICO, you become an integral part of a community that believes in the power..</p>
                                        <div class="read-more-btn">
                                            <a href="blog-details/3.php" class="">
                                                Rate($5.00)
                                                <i class="fas fa-arrow-right"></i>
                                            </a>
                                        </div>
                                    </div>
                                  </div>
                            </div> -->

                        </div>
                    </section>

                    <div class="brand-area3">
                        <div class="container">
                            <div class="row g-0">
                                <div class="col-lg-12">
                                    <div class="brand-title2 text-center">
                                        <h6 class="title">Backed by leading investors and partners</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="brand-item-wrap3">
                                <div class="swiper brand-active2">
                                    <div class="swiper-wrapper">
                                        <div class="swiper-slide">
                                            <div class="brand-item"><img src="assets/img/update/client/client-2-1.svg" alt="Brand Image" /></div>
                                        </div>
                                        <div class="swiper-slide">
                                            <div class="brand-item"><img src="assets/img/update/client/client-2-2.svg" alt="Brand Image" /></div>
                                        </div>
                                        <div class="swiper-slide">
                                            <div class="brand-item"><img src="assets/img/update/client/client-2-3.svg" alt="Brand Image" /></div>
                                        </div>
                                        <div class="swiper-slide">
                                            <div class="brand-item"><img src="assets/img/update/client/client-2-4.svg" alt="Brand Image" /></div>
                                        </div>
                                        <div class="swiper-slide">
                                            <div class="brand-item"><img src="assets/img/update/client/client-2-5.svg" alt="Brand Image" /></div>
                                        </div>
                                        <div class="swiper-slide">
                                            <div class="brand-item"><img src="assets/img/update/client/client-2-1.svg" alt="Brand Image" /></div>
                                        </div>
                                        <div class="swiper-slide">
                                            <div class="brand-item"><img src="assets/img/update/client/client-2-2.svg" alt="Brand Image" /></div>
                                        </div>
                                        <div class="swiper-slide">
                                            <div class="brand-item"><img src="assets/img/update/client/client-2-3.svg" alt="Brand Image" /></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>




                    
                    <footer class="footer-wrapper footer-layout2 pb-50" id="footer">
                        <div class="container">
                            <div class="row justify-content-between">
                                <div class="col-xl-auto col-lg-6 order-xl-1">
                                    <div class="widget footer-widget">
                                        <div class="widget-about">
                                            <div class="footer-logo">
                                                <a href="index.php" class=""><img src="assets/img/logo/logo.png" style="height: 35px;" alt="Warren & Co" /></a>
                                            </div>
                                            <p class="about-text">Warren & Co is a cutting-edge blockchain technology company at the forefront of innovation in the decentralized ledger space. Established in 2025</p>
                                            <div class="social-btn style2">
                                                <a href="https://facebook.com/" rel="noopener noreferrer"><i class="fab fa-facebook-f"></i></a>
                                                <a href="#">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                        <path
                                                            d="M10.0596 7.34522L15.8879 0.570312H14.5068L9.44607 6.45287L5.40411 0.570312H0.742188L6.85442 9.46578L0.742188 16.5703H2.12338L7.4676 10.3581L11.7362 16.5703H16.3981L10.0593 7.34522H10.0596ZM8.16787 9.54415L7.54857 8.65836L2.62104 1.61005H4.74248L8.71905 7.29827L9.33834 8.18405L14.5074 15.5779H12.386L8.16787 9.54449V9.54415Z"
                                                            fill="currentColor"
                                                        ></path>
                                                    </svg>
                                                </a>
                                                <a href="https://www.instagram.com/" rel="noopener noreferrer"><i class="fab fa-instagram"></i></a>
                                                <a href="https://www.linkedin.com/" rel="noopener noreferrer"><i class="fab fa-linkedin"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-auto col-lg-6 order-xl-3">
                                    <div class="footer-widget widget-newsletter">
                                        <h3 class="fw-title">SIGN UP FOR EMAIL UPDATES</h3>
                                        <p class="newsletter-text">Sign up with your email address to receive news and updates</p>
                                        <form class="newsletter-form">
                                            <div class="form-group"><input class="form-control" type="email" placeholder="Your Email Address" /></div>
                                            <button type="submit" disabled class="eg-btn btn5">Subscribe</button>
                                        </form>
                                    </div>
                                </div>
                                <div class="col-xl-auto col-lg-6 order-xl-2">
                                    <div class="footer-widget widget-contact">
                                        <h3 class="fw-title">CONTACT US</h3>
                                        <p class="contact-info-text">202 Helga Springs Rd, Crawford, TN 38554</p>
                                        <div class="contact-info-link">Call Us: <a href="tel:8002758777" rel="noopener noreferrer">800.275.8777</a></div>
                                        <div class="contact-info-link"><a href="mailto:Warren & Co@company.com" rel="noopener noreferrer">Warren & Co@company.com</a></div>
                                        <p class="copyright-text">Copyright © 2025 <a href="#">Warren & Co.</a> All rights reserved.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </footer>
                </div>
                <!--]-->
            </main>
            <button class="scroll-top scroll-to-target" data-target="html" id="back_to_top"><i class="fas fa-angle-up"></i></button>
            <!--]-->
        </div>
        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        
        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

        <!-- Swiper JS -->
        <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

        <!-- SweetAlert -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.17.2/dist/sweetalert2.all.min.js" integrity="sha256-lCHT/LfuZjRp+PdpWns/vKrnSn367D/g1E6Ju18wiH0=" crossorigin="anonymous"></script>

        <script src="assets/js/main.js"></script>
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                const planContainer = document.getElementById("taskContainer");

                // Fetch tasks from the server
                fetch('https://warrencol.com/assets/php/getTasks.php')
                    .then(response => response.json())
                    .then(tasks => {
                        tasks.forEach((task) => {
                            const taskHTML = `
                                <div class="col blog-post-wrap">
                                    <div class="blog-standard-post">
                                        <div class="blog-standard-thumb">
                                            <a href="blog-details/3.php" class="">
                                                <img src="${task.task_image}" alt/>
                                            </a>
                                        </div>
                                        <div class="blog-standard-content">
                                            <h2 class="title">
                                                <a href="blog-details/3.php" class="">${task.task_title}</a>
                                            </h2>
                                            <p>${task.task_description}</p>
                                            <div class="read-more-btn">
                                                <a href="#" class="task-link" data-task-id="${task.id}" data-task-url="${task.task_url}">
                                                    Complete Task
                                                    <i class="fas fa-arrow-right"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                            planContainer.insertAdjacentHTML("beforeend", taskHTML);
                        });

                        // Add click event listeners to task links
                        const taskLinks = document.querySelectorAll('.task-link');
                        taskLinks.forEach(link => {
                            link.addEventListener('click', function (e) {
                                e.preventDefault(); // Prevent default link behavior

                                const taskId = this.getAttribute('data-task-id');
                                const taskUrl = this.getAttribute('data-task-url');

                                // Save the task to the database
                                fetch('https://warrencol.com/assets/php/saveTask.php', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json'
                                    },
                                    body: JSON.stringify({ task_id: taskId })
                                })
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.error) {
                                            // Show error message
                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Error',
                                                text: data.error,
                                                timer: 3000,
                                                showConfirmButton: false
                                            });
                                        } else {
                                            Swal.fire({
                                                icon: 'success',
                                                title: 'Daily Task Earned!',
                                                text: 'You have successfully completed today’s task. Come back tomorrow for more rewards!',
                                                timer: 1500,
                                                showConfirmButton: false
                                            });
                                        }
                                    })
                                    .catch(error => {
                                        console.error('Error saving task:', error);
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Error',
                                            text: 'Failed to save the task. Please try again.',
                                            timer: 3000,
                                            showConfirmButton: false
                                        });
                                    });
                            });
                        });
                    })
                    .catch(error => {
                        console.error('Error fetching tasks:', error);
                    });
            });
        </script>
    </body>
</html>
