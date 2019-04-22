<?php
require_once 'core/init.php';
include 'includes/head.php';
include 'includes/navigation.php';
if($cart_id!=''){
    $cart_query=mysqli_query($db,"SELECT * FROM cart WHERE id='$cart_id'");
    $cart_result=mysqli_fetch_assoc($cart_query);
    //we will first grab the item but it is json encoded so we need to decode it first
    $item=json_decode($cart_result['items'],true);//json returns basically objects so what this true does is to force json_decode to return as associative array
    //var_dump($item);
    $iterator=1;//this iterator will show the different product as a numbered cart we obviously have one product at least if we have our $cart_id set
    $sub_total=0;//this will count out the total bill so far
    $item_count=0;//this will keep track of in total different books
}
?>
<div class="col-md-12">
    <div class="row">
        <h2 class="text-center">My Shopping Cart</h2><hr>
        <?php if($cart_id==''):?>
        <div class="bg-danger">
            <p class="text-center text-danger">Your Cart is empty</p>
        </div>
        <?php else :?>
        <table class="table table-bordered table-condensed table striped">
            <thead><th>#</th><th>Book</th><th>Price</th><th>Quantity</th><th>Sub Total</th></thead>
            <tbody>
                <!--Now we will traverse through the array which we have grabbed into the $item-->
            <?php 
                foreach($item as $identify){//remeber we are just traveling through the items colums string in cart table
                      $book_id=$identify["id"];
                     //we will grab the id and will grab the related book from the book table
                      $book_query=mysqli_query($db,"SELECT * FROM book WHERE id='{$book_id}'");
                      $book=mysqli_fetch_assoc($book_query);
                      

                ?>
                <!--Here we will break out from our php for a while, we are still inside our main foreach loop-->
            <tr>
                <td><?=$iterator;?></td>
                <td><?=$book['title'];?></td>
                <td><?=money($book['price']);?></td>
                <td>
                    <!--Here we will give the user opportunity to add or remove the item quantity from the cart-->
                    <!--First we will send the mode parameter and then the book id in which to remove -->
                    <button class="btn btn-xs btn-default" onclick="update_cart('removeone','<?=$book['id'];?>');">-</button>
                    <?=$identify["quantity"];?>
                    <button class="btn btn-xs btn-default" onclick="update_cart('addone','<?=$book['id'];?>');">+</button>
                </td>
                <td><?=money($identify["quantity"]*$book['price']);?></td>
            </tr>
            <?php 
                $iterator++;
                $item_count+=$identify["quantity"];//after each loop we are adding the total item in $item_count
                $sub_total+=($book['price']*$identify["quantity"]);//it will add into it the sub_total each time it go thorugh and we will find the grand total
                    
            }    
            $delivery_charge=DELIVERY_CHARGE;//we have set the delivery charge in the config file
            //$delivery_charge=number_format($delivery_charge,2);//we will show the delivery charge into two decimal number format
            $grand_total=DELIVERY_CHARGE+$sub_total;
            ?>
            </tbody>
        </table>
        <!--Here we will create another table to show the grand total and the final section-->
        <table class="table table-bordered table-condensed text-right">
            <legend>Totals In General:</legend>
            <thead class="totals-table-header"><th>Total Items</th><th>Sub Total</th><th>Delivery Charge</th><th>Grand Total</th></thead>
            <tbody>
                <tr>
                    <td><?=$item_count;?></td>
                    <td><?=money($sub_total);?></td>
                    <td><?=money($delivery_charge);?></td>
                    <td class="bg-success"><?=money($grand_total);?></td>
                </tr>
            </tbody>
        </table>
<!--We are still inside of our if statement-->
<!--We have copied this from the next line to the comment section check out modal end from the getbootstrap.com, we went to that site and selected the version 3.3.7 and then clicked the javascript and then modal we have copied it from there-->       
<!-- Check Out Button-->
<button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#checkoutModal">
  <span class="glyphicon glyphicon-shopping-cart"></span> Check Out>>
</button>

<!-- Modal -->
<div class="modal fade" id="checkoutModal" tabindex="-1" role="dialog" aria-labelledby="checkoutLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="checkoutLabel">Shipping Address</h4>
      </div>
      <div class="modal-body">
        <div class="row">
        <form action="thankYou.php" method="post" id="payment-form">
            <!--in this span we will show our errors if required address field is not satisfied we will do that part in check_address.php file-->
            <span class="bg-danger" id="payment-errors"></span>
            <!--We will create two step form one for address and another one is for the payment details-->
            <div id="step1" style="display:block;">
                <div class="form-group col-md-6">
                    <label for="full_name">Full Name*:</label>
                    <input class="form-control" id="full_name" name="full_name" type="text">
                </div>
                <div class="form-group col-md-6">
                    <label for="email">Email*:</label>
                    <input class="form-control" id="email" name="email" type="email">
                </div>
                <div class="form-group col-md-6">
                    <label for="phone">Phone*:</label>
                    <input class="form-control" id="phone" name="phone" type="text">
                </div>
                <div class="form-group col-md-6">
                    <label for="city">City*:</label>
                    <select class="form-control" id="city" name="city">
                        <option value=""></option>
                        <option value="Dhaka">Dhaka</option>
                        <option value="Chittagong">Chittagong</option>
                        <option value="Comilla">Comilla</option>
                        <option value="Mymensingh">Mymensingh</option>
                        <option value="Rajshahi">Rajshahi</option>
                        <option value="Khulna">Khulna</option>
                        <option value="Barisal">Barisal</option>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="address">Address*:</label>
                    <input class="form-control" id="address" name="address" type="text">
                </div>
                <div class="form-group col-md-6">
                    <label for="additional">Additional Address Details(Not required):</label>
                    <input class="form-control" id="additional" name="additional" type="text">
                </div>
            </div>
            <!--this div is for card details-->
            <!--initially we will not show that div that's why we have set display none in style-->
            <div id="step2" style="display:none;">
            <!--in this div we will take our card details,initally it won't show up but after clicking the next it will show up-->
                <div class="form-group col-md-3">
                    <label for="name">Exact Name on Card:</label>
                    <input type="text" id="name" class="form-control">
                </div>
                <div class="form-group col-md-3">
                    <label for="number">Card Number:</label>
                    <input type="text" id="number" class="form-control">
                </div>
                <div class="form-group col-md-2">
                    <label for="cvc">CVC:</label>
                    <input type="text" id="cvc" class="form-control">
                </div>
                <div class="form-group col-md-2">
                    <label for="">Expire month:</label>
                    <select id="exp-month" class="form-control">
                        <option value=""></option>
                        <!--here we are gonna create a loop to indicate 1 to 12 months-->
                        <?php for($i=1;$i<13;$i++): ?>
                        <option value="<?=$i;?>"><?=$i;?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="form-group col-md-2">
                    <label for="exp-year">Expire Year:</label>
                    <select id="exp-year" class="form-control">
                        <option value=""></option>
                        <?php $year = date("Y");//here we will grab the current year from php built in date function?>
                        <!--we will keep adding 1 to the current year and make it work like for 10 years,you can increase more if you want-->
                        <?php for($i = 0; $i < 11; $i++): ?>
                        <option value="<?= $year + $i ?>"><?= $year + $i ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>
      </div>
    </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="check_address();" id="next_button">Next >></button>
        <!--initially we won't show these two buttons before credit payment so we have given inline style display none-->
        <button type="button" class="btn btn-primary" onclick="back_address();"id="back_button" style="display:none;"><< Back</button>
        <button type="submit" class="btn btn-primary" id="checkout_button" style="display:none;">Final Checkout >></button>
    </form>
      </div>
    </div>
  </div>
</div>
<!--check out modal end-->        
        <?php endif;?>
    </div>
</div>
<script>
    //now we need to handle our back button so we will create another function to go back, it is done after the check_address() function
    function back_address(){
        //in back everything will go back to same so we need to do opposite of what we did in the passed section of check_address
        jQuery('#payment-errors').html("");
        jQuery('#step1').css("display","block");
        jQuery('#step2').css("display","none");
        jQuery('#next_button').css("display","inline-block");
        jQuery('#back_button').css("display","none");
        jQuery('#checkout_button').css("display","none");
        jQuery('#checkoutLabel').html("Shipping Address");
                           
    }
    //here we will get the address details information and we will plug that into the file check_address.php and see if there is any error
    function check_address(){
        //here we will declare a data object and we will do get element by id by that object from the form
        var data = {
            'full_name' : jQuery('#full_name').val(),
            'email' : jQuery('#email').val(),
            'phone' : jQuery('#phone').val(),
            'city' : jQuery('#city').val(),
            'address' : jQuery('#address').val(),
            'additional' : jQuery('#additional').val(),
        };
        //now we will make an ajax call to our check_address.php file and we will do some checking there
        jQuery.ajax({
            url : '/Nilkhet/admin/parsers/check_address.php',
            method : 'POST',
            data : data,
            success : function(data){//in this anonymous function the parameter 'data' is not same as above data,it is the data that is coming on success of the parsers file which is check_address.php
            //now we will check the returned value from the check_address.php which is returned at data varialbe in the parameter
                if(data != 'passed'){
                jQuery('#payment-errors').html(data);//we have set an id of payment-errors in an span tag in the modal, we are targeting that
                }
                if(data == 'passed'){
                    //suppose we made a mistake at first place and now we made corrected it so first on success we will clear up the erros on the span and that's why we need to put an empty string at first
                    jQuery('#payment-errors').html("");
                    //after passing step1 we will direct to step 2 on successful passing so we have to display none to step1 and forward to step2 div
                    jQuery('#step1').css("display","none");//disabling step1
                    //then we will enable step2
                    jQuery('#step2').css("display","block");
                    jQuery('#next_button').css("display","none");
                    jQuery('#back_button').css("display","inline-block");
                    jQuery('#checkout_button').css("display","inline-block");
                    //now we will change the header to Shipping address to Enter Your Card Details so we will need to target the whole div which is named by checkoutLabel
                    jQuery('#checkoutLabel').html("Enter Yout Card Details");
                }
            },
            error :function(){alert("Something went wrong!")},
        })
    }
</script>

<?php include 'includes/footer.php';?>