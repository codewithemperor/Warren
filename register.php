<?php include 'assets/php/checkAuthLoginRegister.php'; ?>
<!DOCTYPE html>
<html >
    
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
                                                        <a aria-current="page" href="index.php#blockchain" class="router-link-active router-link-exact-active"><span></span>Why Warren & Co</a>
                                                        <!---->
                                                    </li>
                                                    <li class="">
                                                        <a aria-current="page" href="index.php#feature" class="router-link-active router-link-exact-active"><span></span>Features</a>
                                                        <!---->
                                                    </li>
                                                    <li class="">
                                                        <a aria-current="page" href="index.php#package" class="router-link-active router-link-exact-active"><span></span>Packages</a>
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
                                                    <li class="header-login"><a href="login.php" class="btn2">LOGIN</a></li>
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
                                    <li class=""><a aria-current="page" href="index.php#blockchain" class="router-link-active router-link-exact-active">Why Warren & Co</a></li>
                                    <li class=""><a aria-current="page" href="index.php#feature" class="router-link-active router-link-exact-active">Feature</a></li>
                                    <li class=""><a aria-current="page" href="index.php#package" class="router-link-active router-link-exact-active">Package</a></li>
                                    
                                    <li class=""><a href="https://t.me/WarrenC_leading_investment" class="">Contact</a></li>
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
                
                <div class="hero-wrapper hero-2">
                    <div class="hero-bg-gradient1"></div>
                    <div class="hero-bg-gradient2"></div>
                    <div class="hero-gradient-ball alltuchtopdown"></div>
                    <div class="ripple-shape">
                        <span class="ripple-1"></span>
                        <span class="ripple-2"></span>
                        <span class="ripple-3"></span>
                        <span class="ripple-4"></span>
                        <span class="ripple-5"></span>
                    </div>
                    <section class="eg-login__area p-relative z-index-1 fix">
                        <div class="container">
                            <div class="row justify-content-center">
                                <div class="col-xl-6 col-lg-8">
                                    <div class="eg-login__wrapper">
                                        <div class="eg-login__top text-center mb-30">
                                            <h3 class="eg-login__title">Sign Up WC</h3>
                                            <p>
                                                Already have an account?
                                                <span><a href="login.php" class="">Sign In</a></span>
                                            </p>
                                        </div>
                                        <div class="eg-login__option">
                                            <form id="registrationForm" novalidate>
                                                <div class="eg-login__input-wrapper">
                                                    <!-- Name Field -->
                                                    <div class="eg-login__input-box">
                                                        <div class="eg-login__input">
                                                            <label for="full_name">Your Name</label>
                                                            <input type="text" placeholder="Enter your name" name="full_name" id="full_name" required>
                                                            <p class="form_error"></p>
                                                        </div>
                                                    </div>

                                                    <!-- Email Field -->
                                                    <div class="eg-login__input-box">
                                                        <div class="eg-login__input">
                                                            <label for="email">Your Email</label>
                                                            <input type="email" placeholder="Enter your email" name="email" id="email" required>
                                                            <p class="form_error"></p>
                                                        </div>
                                                    </div>

                                                    <!-- Password Field -->
                                                    <div class="eg-login__input-box">
                                                        <div class="eg-login__input">
                                                            <label for="password">Password</label>
                                                            <div class="eg-password-show">
                                                                <input type="password" placeholder="Min. 6 characters" name="password" id="password" required>
                                                                <div class="eg-login__input-eye">
                                                                    <span class="open-close">
                                                                        <svg width="18" height="14" viewBox="0 0 18 14" fill="current" xmlns="http://www.w3.org/2000/svg">
                                                                            <path d="M1 6.77778C1 6.77778 3.90909 1 9 1C14.0909 1 17 6.77778 17 6.77778C17 6.77778 14.0909 12.5556 9 12.5556C3.90909 12.5556 1 6.77778 1 6.77778Z" stroke="currentColor" strokeWidth="1.2" strokeLinecap="round" strokeLinejoin="round"></path>
                                                                            <path d="M9.00018 8.94466C10.2052 8.94466 11.182 7.97461 11.182 6.77799C11.182 5.58138 10.2052 4.61133 9.00018 4.61133C7.79519 4.61133 6.81836 5.58138 6.81836 6.77799C6.81836 7.97461 7.79519 8.94466 9.00018 8.94466Z" stroke="currentColor" strokeWidth="1.2" strokeLinecap="round" strokeLinejoin="round"></path>
                                                                        </svg>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <p class="form_error"></p>
                                                        </div>
                                                    </div>

                                                    <!-- Confirm Password Field -->
                                                    <div class="eg-login__input-box">
                                                        <div class="eg-login__input">
                                                            <label for="confirm_password">Confirm Password</label>
                                                            <div class="eg-password-show">
                                                                <input type="password" placeholder="Confirm password" name="confirm_password" id="confirm_password" required>
                                                                <div class="eg-login__input-eye">
                                                                    <span class="open-close">
                                                                        <svg width="18" height="14" viewBox="0 0 18 14" fill="current" xmlns="http://www.w3.org/2000/svg">
                                                                            <path d="M1 6.77778C1 6.77778 3.90909 1 9 1C14.0909 1 17 6.77778 17 6.77778C17 6.77778 14.0909 12.5556 9 12.5556C3.90909 12.5556 1 6.77778 1 6.77778Z" stroke="currentColor" strokeWidth="1.2" strokeLinecap="round" strokeLinejoin="round"></path>
                                                                            <path d="M9.00018 8.94466C10.2052 8.94466 11.182 7.97461 11.182 6.77799C11.182 5.58138 10.2052 4.61133 9.00018 4.61133C7.79519 4.61133 6.81836 5.58138 6.81836 6.77799C6.81836 7.97461 7.79519 8.94466 9.00018 8.94466Z" stroke="currentColor" strokeWidth="1.2" strokeLinecap="round" strokeLinejoin="round"></path>
                                                                        </svg>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <p class="form_error"></p>
                                                        </div>
                                                    </div>

                                                    <!-- Withdrawal Password Field -->
                                                    <div class="eg-login__input-box">
                                                        <div class="eg-login__input">
                                                            <label for="withdrawal_password">Withdrawal Password</label>
                                                            <div class="eg-password-show">
                                                                <input type="password" placeholder="Withdrawal password" name="withdrawal_password" id="withdrawal_password" required>
                                                                <div class="eg-login__input-eye">
                                                                    <span class="open-close">
                                                                        <svg width="18" height="14" viewBox="0 0 18 14" fill="current" xmlns="http://www.w3.org/2000/svg">
                                                                            <path d="M1 6.77778C1 6.77778 3.90909 1 9 1C14.0909 1 17 6.77778 17 6.77778C17 6.77778 14.0909 12.5556 9 12.5556C3.90909 12.5556 1 6.77778 1 6.77778Z" stroke="currentColor" strokeWidth="1.2" strokeLinecap="round" strokeLinejoin="round"></path>
                                                                            <path d="M9.00018 8.94466C10.2052 8.94466 11.182 7.97461 11.182 6.77799C11.182 5.58138 10.2052 4.61133 9.00018 4.61133C7.79519 4.61133 6.81836 5.58138 6.81836 6.77799C6.81836 7.97461 7.79519 8.94466 9.00018 8.94466Z" stroke="currentColor" strokeWidth="1.2" strokeLinecap="round" strokeLinejoin="round"></path>
                                                                        </svg>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <p class="form_error"></p>
                                                        </div>
                                                    </div>

                                                    <!-- Referral Code Field -->
                                                    <div class="eg-login__input-box">
                                                        <div class="eg-login__input">
                                                            <label for="referral_code">Referral Code (optional)</label>
                                                            <input type="text" placeholder="Enter referral code" name="referral_code" id="referral_code">
                                                            <p class="form_error"></p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Remember me and Submit Button -->
                                                <div class="eg-login__suggetions d-flex align-items-center justify-content-between mb-20">
                                                    <div class="eg-login__remeber">
                                                        <input id="remember" type="checkbox" name="remember">
                                                        <label for="remember">Remember me</label>
                                                    </div>
                                                    <div class="eg-login__forgot">
                                                        <a href="forgot.php" class="">Forgot Password?</a>
                                                    </div>
                                                </div>
                                                <div class="eg-login__bottom">
                                                    <button type="submit" class="eg-btn w-100" id="registerButton">
                                                        <span class="button-text">Register</span>
                                                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>


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
        
        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        
        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

        <!-- Swiper JS -->
        <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

        <!-- SweetAlert -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.17.2/dist/sweetalert2.all.min.js" integrity="sha256-lCHT/LfuZjRp+PdpWns/vKrnSn367D/g1E6Ju18wiH0=" crossorigin="anonymous"></script>

        <script src="assets/js/main.js"></script>
        <script src="assets/js/register.js" defer></script>

    </body>
</html>
