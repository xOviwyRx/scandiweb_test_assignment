<?php
$title = "Product Add";
$form_id = "product_form";
include_once './header.php';
?>
            
                    <button class="mr-4 btn btn-outline-dark" form ="product_form" id="submit" type="submit" name="save" class="mr-3">Save</button>
                    <button class="mr-4 btn btn-outline-dark" onclick="window.location.href='index.php'" class="mr-2">Cancel</button>
                </div> 
            </div>
            <hr>
            
            <form class="mt-4" style="width:500px" method = "post" action="submit.php" id = "product_form">
                <div class="alert alert-danger display-error" style="display: none" id="error-valid">
                </div>
                <div class="row mb-3">
                    <label class="col-form-label col-sm-3" for="sku">SKU</label>
                    <div class="col-sm-9">
                        <input class="form-control" name="sku" id="sku" required
                          />
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-form-label col-sm-3" for="name">Name</label>
                    <div class="col-sm-9">
                        <input name="name" class="form-control" id="name" required
                          />
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-form-label col-sm-3" for="price">Price ($)</label>
                    <div class="col-sm-9">
                        <input placeholder="0.00" name="price" class="form-control input_number" id="price"
                              required
                               />
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-form-label col-sm-4" for="typeswitcher" id="typeswitcher">Type Switcher</label>
                    <div class="col-sm-3 select_box">
                        <select class="form-control" name = "typeswitcher" id="productType">
                            <option value = "DVD" id="DVD" selected>DVD</option>
                            <option value = "Book" id="Book">Book</option>
                            <option value = "Furniture" id="Furniture">Furniture</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-3" id ="noDVD">
                    <label class="col-form-label col-sm-3" for="size">Size (MB)</label>
                    <div class="col-sm-9">
                        <input placeholder="0.0" class="form-control" name="size"
                              id="size" required/>
                        <p class="mt-3">Please, provide size</p>
                    </div>
                </div>
                <div class="row mb-3" id="noBook">
                    <label class="col-form-label col-sm-3" for="weight">Weight (KG)</label>
                    <div class="col-sm-9">    
                        <input class="form-control" placeholder="0.0" name="weight" id="weight"/>
                        <p class="mt-3">Please, provide weight</p>
                    </div>
                </div>
                <div id="noFurniture">
                    <div class="row mb-3">
                        <label class="col-form-label col-sm-3" for="height">Height (CM)</label>
                        <div class="col-sm-9">
                            <input class="form-control" placeholder="0.0"  name="height" id ="height" />
                        
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-form-label col-sm-3" for="width">Width (CM)</label>
                        <div class="col-sm-9">
                            <input class="form-control" placeholder="0.0" name="width" id ="width"/>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-form-label col-sm-3" for="length">Length (CM)</label>
                        <div class="col-sm-9">
                            <input class="form-control" name="length" id ="length" placeholder="0.0"/>
                            <p class="mt-3">Please, provide dimensions</p>
                        </div>
                        
                    </div> 
                </div>
        </form>
            
            </section>
                <?php 
       include "./footer.html";
       ?>
       </div>  
       
       
       
        
    </body>
    <script src="js/main.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {

            $('#submit').click(function(e){
              e.preventDefault();
              var form = $("#product_form");
              
              $.ajax({
                  type: "POST",
                  dataType: "json",
                  data: form.serialize(),
                  url: form.attr('action'),
                  success: function(data){
                      if (data.code === "404"){
                           $("#error-valid").html("<p>"+data.msg+"</p>");
                           $("#error-valid").css("display","block");
                      }
                      else
                      {
                          location.href = "index.php";  
                      }      
                  }
                  
              });
            });
           return false;
        });
    </script>
</html>
