<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sari-Sari Store System</title>
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
  <div class="app-shell">
    <header class="topbar">
      <div class="brand">🏪 Sari-Sari Store System</div>
      <nav class="nav">
        <button class="nav-btn active" data-section="sales">Sales</button>
        <button class="nav-btn" data-section="inventory">Inventory</button>
        <button class="nav-btn" data-section="reports">Reports</button>
      </nav>
    </header>
    <main class="content">
      <section id="sales" class="panel active">
        <div class="sales-layout">
          <div class="card">
            <h3>Add Product</h3>
            <select id="productSelect">
              @foreach($products as $product)
                <option value="{{ $product->id }}">{{ $product->name }} - ₱{{ number_format($product->price, 2) }}</option>
              @endforeach
            </select>
            <input type="number" id="quantityInput" value="1" min="1">
            <button id="addToCartBtn">Add to Cart</button>
          </div>
          <div class="card">
            <h3>Cart</h3>
            <table id="cartItems"></table>
            <div id="subtotal"></div>
            <div id="grandTotal"></div>
            <button id="completeSaleBtn">Complete Sale</button>
          </div>
        </div>
      </section>
    </main>
  </div>
  <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>