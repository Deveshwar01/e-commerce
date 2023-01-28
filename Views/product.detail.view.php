<?php @session_start();
require_once  'Views/navbar.view.php';
global $id;
global $data;
global $cart;
if($_SERVER['REQUEST_METHOD'] === 'POST'){
   if(isset($_POST['add-to-cart'])){

       $data = [
         "name"=>htmlspecialchars(strip_tags($_POST['productname'])),
         "sizes"=>htmlspecialchars(strip_tags($_POST['sizes'])),
         "quatity"=>htmlspecialchars(strip_tags($_POST['number'])),
           "productid"=>htmlspecialchars(strip_tags($_POST['productid'])),
           "ownerid"=>2356,
       ];
       if(\Datainterface\Insertion::insertRow('cart', $data)){
           echo '<META HTTP-EQUIV="Refresh" Content="1; URL=homepage">';
       }
       $data = \Datainterface\Selection::selectById('products', ["id"=>intval($_POST['productid'])]);
   }
}else{
   $params = \GlobalsFunctions\Globals::params();
   if(!empty($params)){
       $id = htmlspecialchars(strip_tags($params['id']));
       $data = \Datainterface\Selection::selectById('products', ["id"=>intval($id)]);
       //$cart = \Datainterface\Selection::selectById('cart', ['ownerid'=>2355]);
   }

}
?>
<section id="prodetails" class=" section-p1">
    <div class="single-pro-image">
        <img src="images/rbu-t-shirt.jpg" width="400px" id="MainImg" alt="">
        <div class="small-img-group">
            <div class="small-img-col">
                <img src="images/WhatsApp Image 2023-01-24 at 15.08.25.jpg" width="100%" class="small-img" alt="">
            </div>
            <div class="small-img-col">
                <img src="images/WhatsApp Image 2023-01-27 at 02.27.42.jpg" width="100%" class="small-img" alt="">
            </div>
            <div class="small-img-col">
                <img src="images/rbu-t-shirt.jpg" width="100%" class="small-img" alt="">
            </div>
            <div class="small-img-col">
                <img src="images/WhatsApp Image 2023-01-27 at 02.34.37.jpg" width="100%" class="small-img" alt="">
            </div>
        </div>
    </div>
    <div class="single-pro-details">
        <h6> home / t-shirt</h6>
        <h4><?php echo $data[0]['name']; ?></h4>
        <h2>139</h2>
        <form action="<?php echo $_SESSION['public_data']['view']['view_url']; ?>" method="POST">
            <select name="sizes">
                <option>select size</option>
                <option value="x">x</option>
                <option value="xl">xl</option>
                <option value="xxl">xxl</option>
                <option value="xxxl">xxxl</option>
            </select>
            <input type="number " name="number" value="1">
            <input type="hidden" name="productid" value="<?php echo $id; ?>">
            <input type="hidden" name="productname" value="<?php echo $data[0]['name']; ?>">
            <input type="hidden" name="productdescription" value="<?php echo $data[0]['description']; ?>">
            <button class="normal" name="add-to-cart" id="button-cart">Add to Cart</button>
        </form>
        <h4> product details</h4>
        <span>
          <?php echo $data[0]['description']; ?>
        </span>
    </div>
</section>