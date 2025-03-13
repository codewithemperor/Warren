document.addEventListener("DOMContentLoaded", function () {
    const mobileMenuToggler = document.querySelector(".mobile-nav-toggler");
    const mobileMenu = document.querySelector(".mobile-menu");
    const menuBackdrop = document.querySelector(".menu-backdrop");
    const closeBtn = document.querySelector(".mobile-menu .close-btn");
    const dropdownBtns = document.querySelectorAll(".dropdown-btn");
    const header = document.getElementById("sticky-header"); // Select the header
    const scrollThreshold = 100; // Height after which the class is added

    function handleScroll() {
        if (window.scrollY > scrollThreshold) {
            header.classList.add("sticky-menu");
        } else {
            header.classList.remove("sticky-menu");
        }
    }

    // Function to open mobile menu
    function openMobileMenu() {
        document.body.classList.add("mobile-menu-visible");
    }

    // Function to close mobile menu
    function closeMobileMenu() {
        document.body.classList.remove("mobile-menu-visible");
    }

    // Open menu when clicking the mobile menu toggle button
    mobileMenuToggler.addEventListener("click", openMobileMenu);

    // Close menu when clicking the close button
    closeBtn.addEventListener("click", closeMobileMenu);

    // Close menu when clicking outside (menu backdrop)
    menuBackdrop.addEventListener("click", closeMobileMenu);

    window.addEventListener("scroll", handleScroll);

    // Dropdown functionality for submenus
    dropdownBtns.forEach((btn) => {
        btn.addEventListener("click", function () {
            const submenu = this.previousElementSibling; // Get the submenu
            if (submenu.classList.contains("d-none")) {
                submenu.classList.remove("d-none"); // Show submenu
                this.classList.add("open");
            } else {
                submenu.classList.add("d-none"); // Hide submenu
                this.classList.remove("open");
            }
        });
    });
});



var swiper = new Swiper(".brand-active2", {
    slidesPerView: "auto",
    spaceBetween: 20,
    loop: true,
    autoplay: {
        delay: 5,
        disableOnInteraction: false,
    },
    speed: 3000,
    breakpoints: {
        320: { slidesPerView: 2 },
        768: { slidesPerView: 3 },
        1024: { slidesPerView: 5 },
        1200: { slidesPerView: 6 },
    },
});