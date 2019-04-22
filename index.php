<?php 
   require_once 'core/init.php';
   include 'includes/head.php';
   include 'includes/navigation.php';
   include 'includes/loginmodal.php';
   include 'includes/afternav.php';
   include 'includes/leftsidebar.php';

   $sql="SELECT * FROM book WHERE featured=1";
   $featured=mysqli_query($db,$sql);
?>

        
        
        
<div class="col-md-8">
  <h2 class="text-center">Featured Books</h2>      
  <div class="row">
    <?php while($product=mysqli_fetch_assoc($featured)): ?>
    <div class="col-sm-3 dis_pad">
       <img class="img-responsive img_size" src="<?=$product['image']; ?>" alt="<?=$product['title']; ?>"/>
        <p class="name"><strong><?=$product['title'];?></strong></p>
        <p class="name"><strong>Author: <?=$product['author'];?></strong></p>
        <p class="price">Price: <?=$product['price'];?></p>
        <button type="button" class="btn btn-sm btn-success" onclick="detailsmodal(<?=$product['id'];?>)">Details</button>
    </div>
      <?php endwhile; ?>
        </div>
  </div>

<?php
//we will create the javascript of the modal in the footer section
/*lets comment details modal for some moment*/
//include 'includes/detailsmodal.php';
include 'includes/rightsidebar.php';
include 'includes/footer.php';
?>
