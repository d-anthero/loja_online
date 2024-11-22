<?php if(!class_exists('Rain\Tpl')){exit;}?><section class="h-100">
    <div class="container h-100 py-5">
      <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col-10">
  
          <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-normal mb-0">Carrinho de Compras</h3>
          </div>
          <?php $counter1=-1;  if( isset($products) && ( is_array($products) || $products instanceof Traversable ) && sizeof($products) ) foreach( $products as $key1 => $value1 ){ $counter1++; ?>
          <div class="card rounded-3 mb-4">
            <div class="card-body p-4">
              <div class="row d-flex justify-content-between align-items-center">
                <div class="col-md-3 col-lg-3 col-xl-3">
                  <p class="lead fw-normal mb-2"><?php echo htmlspecialchars( $value1["name"], ENT_COMPAT, 'UTF-8', FALSE ); ?></p>
 
                  <small><?php echo htmlspecialchars( $value1["description"], ENT_COMPAT, 'UTF-8', FALSE ); ?></small>
                </div>
                <div class="col-md-3 col-lg-3 col-xl-2 d-flex">
                  <button data-mdb-button-init="" data-mdb-ripple-init="" class="btn btn-danger" onclick="window.location.href = '/cart/<?php echo htmlspecialchars( $value1["id"], ENT_COMPAT, 'UTF-8', FALSE ); ?>/minus'">
                    <i class="fas fa-minus">-</i>
                  </button>
                  &nbsp;
                  <input id="form1" min="0" name="quantity" value="<?php echo htmlspecialchars( $value1["quantity"], ENT_COMPAT, 'UTF-8', FALSE ); ?>" type="number" class="form-control form-control-sm">
                  &nbsp;
                  <button data-mdb-button-init="" data-mdb-ripple-init="" class="btn btn-success" onclick="window.location.href = '/cart/<?php echo htmlspecialchars( $value1["id"], ENT_COMPAT, 'UTF-8', FALSE ); ?>/addFromCart'">
                    <i class="fas fa-plus">+</i>
                  </button>
                </div>
                <div class="col-md-3 col-lg-2 col-xl-2 offset-lg-1">
                  <h5 class="mb-0">R$<?php echo htmlspecialchars( $value1["subtotal_price"], ENT_COMPAT, 'UTF-8', FALSE ); ?></h5>
                </div>
                <div class="col-md-1 col-lg-1 col-xl-1 text-end">
                  <a href="/cart/<?php echo htmlspecialchars( $value1["id"], ENT_COMPAT, 'UTF-8', FALSE ); ?>/remove" class="text-danger">Remover<i class="fas fa-trash fa-lg"></i></a>
                </div>
              </div>
            </div>
          </div>
          <?php } ?>  


          <div class="card">
            <div style="display: flex; justify-content:space-between" class="card-body">
              <button type="button" data-mdb-button-init="" data-mdb-ripple-init="" class="btn btn-warning btn-block btn-lg">Pagar</button>
              <div>
                <span>            <p>Total: <b>R$<?php echo htmlspecialchars( $total_cart_price, ENT_COMPAT, 'UTF-8', FALSE ); ?></b></p></span>
              </div>
            </div>
          </div>
 
        </div>
      </div>
    </div>
  </section>