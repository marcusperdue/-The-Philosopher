<footer class="footer">
    <div class="footer-container">
        <div class="footer-section">
            <h4>About Us</h4>
            <p>Discover the journey of The Philosopher Bookstore and our commitment to bringing you a vast collection of books.</p>
        </div>
        <div class="footer-section">
            <h4>Contact</h4>
            <p>Email: contact@philosopherbooks.com</p>
            <p>Phone: (123) 456-7890</p>
        </div>
        <div class="footer-section">
            <h4>Follow Us</h4>
            <div class="desktop-social-icons">
                <a href="#"><img src="./assets/images/social/facebook-white.svg" alt="Facebook" class="desktop-social-icon"></a> 
                <a href="#"><img src="./assets/images/social/x-white.svg" alt="X" class="desktop-social-icon"></a> 
                <a href="#"><img src="./assets/images/social/instagram-white.svg" alt="Instagram" class="desktop-social-icon"></a>
            </div>
        </div>
        <div class="footer-section">
            <h4>Legal</h4>
            <a href="javascript:void(0);" id="privacy-policy-link" class="privacy-policy-link">Privacy Policy</a>
            <?php include 'privacy_policy_modal.php'; ?>
        </div>
        <div class="footer-section">
            <h4>FAQ</h4>
            <a href="faq.php" class="faq-link">Frequently Asked Questions</a>
        </div>
        <div class="footer-section">
    <h3>Sign Up for Emails</h3>
    <p>And get a coupon for your first purchase. Score!</p>
    <form id="emailSignupForm">
        <input type="email" placeholder="Your Email" required>
        <button type="submit" id="signupButton">Sign Up</button>
    </form>
</div>

<script>
    document.getElementById('emailSignupForm').addEventListener('submit', function(event) {
        event.preventDefault();  
        document.getElementById('signupButton').textContent = 'Thanks for Submitting!';
    });
</script>
    </div>
    <div class="footer-bottom">
        <p>&copy; 1972-2024 The Philosopher Bookstore, Inc. All rights reserved.</p>
    </div>
</footer>


<script>

    function redirectToLoginPage() {
        window.location.href = "login.php";
    }
    
// Privacy Policy Modal functionality
    document.addEventListener('DOMContentLoaded', function() {
        const privacyPolicyLink = document.getElementById('privacy-policy-link');
        const privacyPolicyModal = document.getElementById('privacyPolicyModal');
        const closeBtn = document.querySelector('.close');

        privacyPolicyLink.addEventListener('click', function() {
            privacyPolicyModal.style.display = 'block';
        });

        closeBtn.addEventListener('click', function() {
            privacyPolicyModal.style.display = 'none';
        });

        window.addEventListener('click', function(event) {
            if (event.target === privacyPolicyModal) {
                privacyPolicyModal.style.display = 'none';
            }
        });
    });
</script>
</body>
</html>
