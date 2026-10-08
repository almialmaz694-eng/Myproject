document.addEventListener("DOMContentLoaded", () => {
    // 1. Mobile Navigation Toggle (Slide from Right)
    const menuIcon = document.getElementById("menuIcon");
    const navMenu = document.getElementById("navMenu");

    if (menuIcon && navMenu) {
        menuIcon.addEventListener("click", () => {
            navMenu.classList.toggle("active");
            
            // Toggle menu icon character (≡ or ✕)
            if (navMenu.classList.contains("active")) {
                menuIcon.innerHTML = "&#10005;"; // Cross icon
            } else {
                menuIcon.innerHTML = "&#9776;";  // Hamburger icon
            }
        });

        // Close menu when clicking outside
        document.addEventListener("click", (event) => {
            if (!navMenu.contains(event.target) && !menuIcon.contains(event.target) && navMenu.classList.contains("active")) {
                navMenu.classList.remove("active");
                menuIcon.innerHTML = "&#9776;";
            }
        });
    }

    // 2. Client-side Form Validation
    const forms = document.querySelectorAll("form");
    forms.forEach(form => {
        form.addEventListener("submit", (e) => {
            const requiredInputs = form.querySelectorAll("[required]");
            let valid = true;

            requiredInputs.forEach(input => {
                if (!input.value.trim()) {
                    valid = false;
                    input.style.borderColor = "#e74c3c";
                } else {
                    input.style.borderColor = "#ccc";
                }
            });

            if (!valid) {
                e.preventDefault();
                alert("Please fill out all required fields before submitting.");
            }
        });
    });
});