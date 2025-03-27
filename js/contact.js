document.addEventListener('DOMContentLoaded', function() {
    const contactForm = document.querySelector('.contact-form');
    if (contactForm) {
        contactForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = {
                name: contactForm.querySelector('[name="name"]').value,
                email: contactForm.querySelector('[name="email"]').value,
                message: contactForm.querySelector('[name="message"]').value,
                date: new Date().toISOString()
            };

            // Store message in localStorage
            let messages = JSON.parse(localStorage.getItem('contactMessages') || '[]');
            messages.push(formData);
            localStorage.setItem('contactMessages', JSON.stringify(messages));

            // Clear form
            contactForm.reset();

            // Show success message
            alert('Thank you for your message. We will get back to you soon!');
        });
    }
});