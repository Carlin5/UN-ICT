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

            try {
                const response = await fetch('save_message.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(formData)
                });

                const result = await response.json();

                if (response.ok) {
                    // Clear form
                    contactForm.reset();
                    alert('Thank you for your message. We will get back to you soon!');
                } else {
                    throw new Error(result.error || 'Failed to send message');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error: ' + error.message + '\nPlease try again later or contact support if the problem persists.');
            }
        });
    }
});