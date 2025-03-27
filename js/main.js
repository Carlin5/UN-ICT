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

// Form Submission
const contactForm = document.querySelector('.contact-form');
if (contactForm) {
    contactForm.addEventListener('submit', async(e) => {
        e.preventDefault();

        const submitButton = contactForm.querySelector('.submit-button');
        const formMessage = document.getElementById('formMessage');

        // Disable form and show loading state
        submitButton.disabled = true;
        submitButton.classList.add('loading');
        formMessage.style.display = 'none';

        const formData = new FormData(contactForm);

        try {
            const response = await fetch('process_contact.php', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                formMessage.textContent = data.message;
                formMessage.className = 'form-message success';
                contactForm.reset();
            } else {
                formMessage.textContent = data.message;
                formMessage.className = 'form-message error';
            }
        } catch (error) {
            formMessage.textContent = 'There was an error sending your message. Please try again later.';
            formMessage.className = 'form-message error';
            console.error('Error:', error);
        } finally {
            // Re-enable form and hide loading state
            submitButton.disabled = false;
            submitButton.classList.remove('loading');
            formMessage.style.display = 'block';
        }
    });
}

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