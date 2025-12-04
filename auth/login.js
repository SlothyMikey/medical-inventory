document.addEventListener('DOMContentLoaded', () => {
    const togglePasswordBtn = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const loginForm = document.getElementById('loginForm');

    // Password Visibility Toggle
    if (togglePasswordBtn && passwordInput) {
        togglePasswordBtn.addEventListener('click', () => {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            // Update Icon
            const icon = togglePasswordBtn.querySelector('i');
            if (type === 'text') {
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    }

    // Role Selection Logic
    const roleInputs = document.querySelectorAll('input[name="role"]');
    const forgotPasswordLink = document.querySelector('.forgot-password');

    function updateForgotPasswordVisibility() {
        const selectedRole = document.querySelector('input[name="role"]:checked')?.value;

        // Update Forgot Password Visibility
        if (selectedRole === 'admin') {
            forgotPasswordLink.style.display = 'none';
        } else {
            forgotPasswordLink.style.display = 'block';
        }

        // Update Theme
        document.body.classList.remove('theme-admin', 'theme-worker');
        if (selectedRole === 'admin') {
            document.body.classList.add('theme-admin');
        } else if (selectedRole === 'worker') {
            document.body.classList.add('theme-worker');
        }
        // Nurse uses default theme (no class)
    }

    roleInputs.forEach(input => {
        input.addEventListener('change', updateForgotPasswordVisibility);
    });

    // Initial check
    updateForgotPasswordVisibility();

    // Form Submission (Demo) - REMOVED for PHP implementation
    /*
    if (loginForm) {
        loginForm.addEventListener('submit', (e) => {
            // ... demo code ...
        });
    }
    */
});
