<?php
session_start();  

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit;  
}
?>

<?php include './includes/header.php'; ?>

<main>
    
    <div class="content-wrapper"> 
      

<!-- Promo Section -->
<section class="promo">
    <div class="promo-container">
        <div class="promo-item">
        <img src="./assets/images/black-logo.svg" alt="The Philosopher Bookstore" class="logo">
            <div class="promo-text">
                <h2>New Arrivals</h2>
                <p>Discover the latest additions to our collection.</p>
                <a href="./all_books.php" class="promo-button">Explore Now</a>
            </div>
        </div>
        <div class="promo-item">
            <img src="./assets/images/NYTBestsellers.png" alt="Promo Image 2" class="promo-image">
            
        </div>
    </div>
</section> 


 
<!-- adSection -->
<section class="MalcolmGladwell-ad">
    <a href="https://mperdue.cocc-cis.com/233p/final/client/book.php?id=2">
        
        <img src="./assets/images/MalcolmGladwell.jpg" alt="Malcolm Gladwell Advertisement" class="ad-image">
    </a>
</section>
<section class="MalcolmGladwell-ad">
    <a href="#">
        <img src="./assets/images/summerad.jpg" alt="Malcolm Gladwell Advertisement" class="ad-image">
    </a>
</section>
 


 <!-- Our Stores Section -->
 <section class="stores">
    <div class="stores-container">
        <div class="stores-item">
        <img src="./assets/images/black-logo.svg" alt="The Philosopher Bookstore" class="logo">
            <div class="stores-text">
                <h2>OUR STORES</h2>
                <p>Different stores, different stories. Search over 120 stores in 19 states to find a The Philosopher Bookstore near you.</p>
                <a href="./find_store.php" class="stores-button">Find a Store</a>
            </div>
        </div>
    </div>
</section>






</div>
</main>

<script src="./js/script.js"></script>
<?php include 'includes/footer.php'; ?>

       
   

   
</body>
</html>
