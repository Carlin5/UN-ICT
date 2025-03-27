// Mobile Menu Toggle
const hamburger = document.querySelector('.hamburger');
const navLinks = document.querySelector('.nav-links');

hamburger.addEventListener('click', () => {
    navLinks.style.display = navLinks.style.display === 'flex' ? 'none' : 'flex';
    hamburger.classList.toggle('active');
});

// Smooth Scrolling for Navigation Links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
            // Close mobile menu if open
            if (window.innerWidth <= 768) {
                navLinks.style.display = 'none';
                hamburger.classList.remove('active');
            }
        }
    });
});

// Handle form submission messages
document.addEventListener('DOMContentLoaded', function() {
    // Get URL parameters
    const urlParams = new URLSearchParams(window.location.search);
    const status = urlParams.get('status');
    const message = urlParams.get('message');

    if (status && message) {
        // Create message element
        const messageElement = document.createElement('div');
        messageElement.className = `message ${status}`;
        messageElement.textContent = decodeURIComponent(message);

        // Insert message at the top of the contact form
        const contactForm = document.querySelector('.contact-form');
        if (contactForm) {
            contactForm.insertBefore(messageElement, contactForm.firstChild);

            // Remove message after 5 seconds
            setTimeout(() => {
                messageElement.remove();
            }, 5000);

            // Clean up URL
            window.history.replaceState({}, document.title, window.location.pathname);
        }
    }
});

// Scroll Animation
const observerOptions = {
    threshold: 0.1
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('visible');
        }
    });
}, observerOptions);

// Observe all sections
document.querySelectorAll('section').forEach(section => {
    observer.observe(section);
});

// Navbar Background Change on Scroll
const navbar = document.querySelector('.navbar');
window.addEventListener('scroll', () => {
    if (window.scrollY > 50) {
        navbar.style.backgroundColor = 'rgba(255, 255, 255, 0.95)';
        navbar.style.boxShadow = '0 2px 5px rgba(0,0,0,0.1)';
    } else {
        navbar.style.backgroundColor = 'var(--white)';
        navbar.style.boxShadow = 'none';
    }
});

// Live Preview for Contact Form
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('.contact-form');
    if (form) {
        const inputs = form.querySelectorAll('input, textarea');
        inputs.forEach(input => {
            const preview = document.getElementById(`${input.id}-preview`);

            input.addEventListener('input', function() {
                if (this.value.trim()) {
                    preview.textContent = this.value;
                    preview.classList.add('active');
                } else {
                    preview.textContent = '';
                    preview.classList.remove('active');
                }
            });

            input.addEventListener('focus', function() {
                if (this.value.trim()) {
                    preview.classList.add('active');
                }
            });

            input.addEventListener('blur', function() {
                if (!this.value.trim()) {
                    preview.classList.remove('active');
                }
            });
        });
    }
});