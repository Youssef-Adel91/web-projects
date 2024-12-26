$(document).ready(function () {
    // Sticky Navbar
    $(window).on("scroll", function () {
        if ($(window).scrollTop() > 0) {
            $("nav").addClass("sticky");
        } else {
            $("nav").removeClass("sticky");
        }
    });
   
    // Toggle Menu
    const toggleMenu = () => {
        $(".menu").toggleClass("active");
    };
    $(".menu-btn").on("click", toggleMenu);
    $(".close-btn").on("click", toggleMenu);
    $(".menu a").on("click", toggleMenu);

    // Back to Top Button
    $("body").append('<button class="back-to-top">↑</button>');
    const $backToTop = $(".back-to-top");
    $backToTop.hide();

    $(window).on("scroll", function () {
        if ($(window).scrollTop() > 200) {
            $backToTop.fadeIn();
        } else {
            $backToTop.fadeOut();
        }
    });

    $backToTop.on("click", function () {
        $("html, body").animate({ scrollTop: 0 }, "slow");
    });

    // Dark Mode Toggle
    $("body").append('<button class="dark-mode-toggle">Dark Mode</button>');
    $(".dark-mode-toggle").on("click", function () {
        $("body").toggleClass("dark-mode");
    });

    // Form Validation
    $("form").on("submit", function (e) {
        let valid = true;
        $(this)
            .find("input, select")
            .each(function () {
                if (!$(this).val()) {  // empty value
                    valid = false;
                    $(this).css("border", "2px solid red"); // red border around the invalid field 
                } else {
                    $(this).css("border", "");
                }
            });

        if (!valid) {
            e.preventDefault();
            alert("Please fill all required fields.");
        }
    });

    // Preloader Animation
    $("body").prepend('<div class="preloader"><div class="loader"></div></div>');
    $(".preloader").fadeOut(1000);

    // ScrollReveal Animation
    const sr = ScrollReveal({
        origin: "bottom",
        distance: "40px",
        duration: 1000,
        delay: 400,
        easing: "ease-in-out",
    });

    sr.reveal(".hero-headlines", { origin: "left" });
    sr.reveal(".hero-page img", { origin: "right" });
    sr.reveal(".about");
    sr.reveal(".about h1", { delay: 500 });
    sr.reveal(".about p", { delay: 700 });
    sr.reveal(".about-info", { delay: 1000 });
    sr.reveal(".collection h1");
    sr.reveal(".collection-container", { delay: 900 });
    sr.reveal(".review h1");
    sr.reveal(".review-container", { delay: 800 });
    sr.reveal(".callout");
    sr.reveal(".contact");
});
