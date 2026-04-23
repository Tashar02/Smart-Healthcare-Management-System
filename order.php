<!DOCTYPE html>
<html>
  <head>
    <title>Payment Page</title>
    <style>
.container{
width:360px;
margin:50px auto;
font:Cambria, "Hoefler Text", "Liberation Serif", Times, "Times New Roman", serif;
border-radius:10px;
border:2px solid #ccc;
padding:10px 40px 25px;
margin-top:70px; 
}
input[type=text], input[type=password]{
width:99%;
padding:10px;
margin-top:8px;
border:1px solid #ccc;
padding-left:5px;
font-size:16px;
font-family:Cambria, "Hoefler Text", "Liberation Serif", Times, "Times New Roman", serif; 
}
input[type=submit]{
width:100%;
background-color:#009;
color:#fff;
border:2px solid #06F;
padding:10px;
font-size:20px;
cursor:pointer;
border-radius:5px;
margin-bottom:15px; 
}
</style>
  </head>
  <body>
    <div class="container">
      <div class="row col-md-6 col-md-offset-3">
        <div class="panel panel-primary">
          <div class="panel-heading text-center">
            <h1>Payment</h1>
          </div>
          <div class="panel-body">
            <form action="connect.php" method="post">
              <div class="form-group">
                <label for="name">Name</label>
                <input
                  type="text"
                  class="form-control"
                  id="name"
                  name="name"
                />
              </div>
              <div class="form-group">
                <label for="address">Address</label>
                <input
                  type="text"
                  class="form-control"
                  id="address"
                  name="address"
                />
              </div>
	      <div class="form-group">
                <label for="number">Phone Number</label>
                <input
                  type="text"
                  class="form-control"
                  id="number"
                  name="number"
                />
              </div>
		<div class="form-group">
                <label for="orderitem">Order Item</label>
                <input
                  type="text"
                  class="form-control"
                  id="orderitem"
                  name="orderitem"
                />
              </div>
              <div class="form-group">
                <label for="quantity">Quantity</label>
                <input
                  type="text"
                  class="form-control"
                  id="quantity"
                  name="quantity"
                />
              </div>
              <div class="form-group">
                <label for="paymentmethod">Payment Method<h6>(if you want to pay in bKash then at first send the ammount in 01925221970 and then provide Transection ID into the Transection ID setion or Put Transection ID = 0)</h6></label>
                <div>
                  <label for="bkash" class="radio-inline"
                    ><input
                      type="radio"
                      name="paymentmethod"
                      value="bkash"
                      id="cash"
                    />cash</label
                  >
                  <label for="bkash" class="radio-inline"
                    ><input
                      type="radio"
                      name="paymentmethod"
                      value="bkash"
                      id="bkash"
                    />bKash</label>
                </div>
              </div>
		<div class="form-group">
                <label for="tarnsectionid">Transection ID</label>
                <input
                  type="text"
                  class="form-control"
                  id="tarnsectionid"
                  name="tarnsectionid"
                />
		</div>
              <div class="form-group">
                <label for="price">Total Price</label>
                <input
                  type="text"
                  class="form-control"
                  id="price"
                  name="price"
                />
              </div>
              <input type="Submit" value="Pay Now" class="btn btn-primary" />
            </form>
          </div>
          <div class="panel-footer text-right">
            <small>&copy; Online Restaurent</small>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
