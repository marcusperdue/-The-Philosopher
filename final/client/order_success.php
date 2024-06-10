<?php include './includes/header.php'; ?>
<main>
    <div class="content-wrapper">
        <section>
            <div class="cart-section">
                <div class="cart-content">
                    <h2>Order Successful</h2>
                    <p>Your order has been placed successfully. Thank you for your purchase!</p>
                    <a href="index.php">Return to Home</a>
                </div>
            </div>
        </section>
    </div>
</main>
<?php include './includes/footer.php'; ?>




<!-- Random confetti animation I found -->
<!-- the canvas-confetti library -->
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.4.0/dist/confetti.browser.min.js"></script>
<script src="./js/script.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    function launchConfetti() {
        confetti({
            particleCount: 250,
            spread: 100,
            origin: { y: 0.6 }
        });
    }

    launchConfetti();
});
</script>
</body>
</html>
