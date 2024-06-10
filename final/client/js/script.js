// mobile nav button open and close

document.addEventListener('DOMContentLoaded', function() {
    const navToggle = document.querySelector('.nav-toggle');
    const mobileNav = document.querySelector('.mobile-nav');

    navToggle.addEventListener('click', function() {
        mobileNav.classList.toggle('active');
    });

    const closeBtn = document.querySelector('.close-btn');
    closeBtn.addEventListener('click', function() {
        mobileNav.classList.remove('active');
    });
});
