<?php 
   require_once 'core/init.php';
   include 'includes/head.php';
   include 'includes/navigation.php';
   include 'includes/loginmodal.php';
   include 'includes/afternav.php';
   include 'includes/leftsidebar.php';

   if(isset($_GET['cat'])){
       $cat_id=sanitize($_GET['cat']);
   }else{
       $cat_id='';
   }
   
   $cat_sql="SELECT * FROM category WHERE parent='$cat_id'";
   $cat_query=mysqli_query($db,$cat_sql);
   
   //this part sql is to show the category name insted of the featured product
   $cat_name="SELECT * FROM category WHERE id='$cat_id'";
   $cat_name_query=mysqli_query($db,$cat_name);
   $cat_name_result=mysqli_fetch_assoc($cat_name_query);
?>

        
        
        
<div class="col-md-8">
  <h2 class="text-center"><?=$cat_name_result['category'];?></h2>      
  <div class="row">
    <!--Here we have used nested loops, first category will find the name of the category by parents then it will grab all the relevant id then in second while loop it will search for that relevant id and match one by one, suppose Novel/Sotry etc. has 8 sub category so the first loop will execute 8 times and 8 times the relevant id will be matched in the book table-->
    <?php while($parent=mysqli_fetch_assoc($cat_query)):?>
     <?php 
            $id=$parent['id'];
            $book_sql="SELECT * FROM book WHERE category='$id'";
            $book_query=mysqli_query($db,$book_sql);
            
     ?>
    <?php while($book_result=mysqli_fetch_assoc($book_query)):?>
    <?php if($parent['id']==$book_result['category']):?>
    <div class="col-sm-3 dis_pad">
       <img class="img-responsive img_size" src="<?=$book_result['image']; ?>" alt="<?=$book_result['title']; ?>"/>
        <p class="name"><strong><?=$book_result['title'];?></strong></p>
        <p class="name"><strong>Author: <?=$book_result['author'];?></strong></p>
        <p class="price">Price: <?=$book_result['price'];?></p>
        <button type="button" class="btn btn-sm btn-success" onclick="detailsmodal(<?=$book_result['id'];?>)">Details</button>
    </div>
    <?php endif;?>
    <?php endwhile;?>
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