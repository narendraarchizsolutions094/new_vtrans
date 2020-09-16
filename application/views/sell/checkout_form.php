<style type="text/css">


.d-flex{
  display: flex;
  flex-direction: row;
  background: #f6f6f6;
  border-radius: 0 0 5px 5px;
  padding: 25px;
}
/*form{
  flex: 4;
}*/
.Yorder{
  flex: 2;
}
.checkout-title{
  background: -webkit-gradient(linear, left top, right bottom, color-stop(0, #5195A8), color-stop(100, #70EAFF));
  background: -moz-linear-gradient(top left, #5195A8 0%, #70EAFF 100%);
  background: -ms-linear-gradient(top left, #5195A8 0%, #70EAFF 100%);
  background: -o-linear-gradient(top left, #5195A8 0%, #70EAFF 100%);
  background: linear-gradient(to bottom right, #5195A8 0%, #70EAFF 100%);
  border-radius:5px 5px 0 0 ;
  padding: 20px;
  color: #f6f6f6;
}
h2{
  margin: 0;
  padding-left: 15px; 
}
.required{
  color: red;
}

.Yorder{
  margin-top: 15px;
  height: 600px;
  padding: 20px;
  border: 1px solid #dadada;
}
table{
  margin: 0;
  padding: 0;
}
th{
  border-bottom: 1px solid #dadada;
  padding: 10px 0;
}
tr>td:nth-child(1){
  text-align: left;
  color: #2d2d2a;
}
tr>td:nth-child(2){
  text-align: right;
  color: #52ad9c;
}
td{
  border-bottom: 1px solid #dadada;
  padding: 12px 25px 12px 0;
}

p{
  display: block;
  color: #888;
  margin: 0;
  padding-left: 25px;
}
.Yorder>div{
  padding: 15px 0; 
}

.checkout-form-button{
  width: 100%;
  margin-top: 10px;
  padding: 10px;
  border: none;
  border-radius: 30px;
  background: #52ad9c;
  color: #fff;
  font-size: 15px;
  font-weight: bold;
}
.checkout-form-button:hover{
  cursor: pointer;
  background: #428a7d;
}
</style>
<form action="<?=base_url().'payment/make_payment_mojo'?>" method="post">
<div class="">
  <div class="checkout-title">
      <h2>Product Order Form</h2>
  </div>
<div class="row">
  <br>
<div class="col-md-8">
    <div class="row Yorder">
        <div class="col-md-12">
            <div class="form-group">        
                <label>Full Name <i class="required">*</i></label>
                <input type="text" name="fname" class="form-control" required value="<?=$user_row->s_display_name.' '.$user_row->last_name?>">
            </div>        
        </div>        
      <!--   <div class="col-md-12">
            <div class="form-group">        

          <label>Country <i class="required">*</i></label>
          <select name="selection" class="form-control">
            <option value="select">Select a country...</option>
            <option value="SGS">South Georgia and the South Sandwich Islands</option>       
          </select>
        </div>
        </div>  -->       
        
        <div class="col-md-12">
           <div class="form-group">        
            <label>Address <i class="required">*</i></label>
            <input type="text" name="address" placeholder="House number and street name" required class="form-control" value="<?=$user_row->add_ress?>">
          </div>          
        </div>          
        
          

       <!--  <div class="col-md-12">
            <div class="form-group">        

          <label>Town / City <i class="required">*</i></label>
          <input type="text" name="city" class="form-control"> 
        </div>
        </div>        
        <div class="col-md-12">
            <div class="form-group">        

          <label>State / County <i class="required">*</i></label>
          <input type="text" name="city" class="form-control"> 
        </div>
        </div>       -->  
        <div class="col-md-12">
          <div class="form-group">        
          <label>Postcode / ZIP <i class="required">*</i></label>
          <input type="text" name="pincode" class="form-control" required value="<?=$postal_code['postal_code']?>"> 
        </div>
        </div>        
        <div class="col-md-12">
            <div class="form-group">        

          <label>Mobile no. <i class="required">*</i></label>
          <input type="tel" name="phone" class="form-control" required value="<?=$user_row->s_phoneno?>"> 
        </div>
        </div>        
        <div class="col-md-12">
            <div class="form-group">    
              <label>Email Address <i class="required">*</i></label>
              <input type="email" name="email" class="form-control" required value="<?=$user_row->s_user_email?>"> 
              <br>
            </div>
        </div>
    </div>    
</div>
<div class="col-md-4">
  <div class="Yorder">
    <table style="width: 100%;">
      <tr>
        <th colspan="2">Your order</th>
      </tr>
      <?php
      $cartarr = $this->cart->contents();
      if (!empty($cartarr)) {
        foreach($cartarr as $ind => $cart) {
          ?>
          <tr>
            <td><?=$cart['name']?> x <?=$cart['qty']?>(Qty)</td>
            <td>₹ <?=$cart['price']*$cart['qty']?></td>
          </tr>
          <?php
        }
      }
      ?>
      <tr>
        <td>Subtotal</td>
        <td>₹ <?=$this->cart->total()?></td>
      </tr>
      <tr>
        <td>Shipping</td>
        <td>Free shipping</td>
      </tr>
    </table><br>    
   <!--  <div>
      <input type="radio" name="dbt" value="cd"> Cash on Delivery
    </div> -->
    <div>
      <input type="radio" name="dbt" value="cd" checked> Online <span>
      <img src="https://www.logolynx.com/images/logolynx/c3/c36093ca9fb6c250f74d319550acac4d.jpeg" alt="" width="50">
      </span>
    </div>
    <button type="submit" class="checkout-form-button">Place Order</button>
  </div><!-- Yorder -->
 </div>
 </div>
</div>
  </form>