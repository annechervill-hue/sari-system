import { useEffect, useState } from 'react';

const initialProducts = [
  { id: 1, name: 'Rice 5kg', category: 'Groceries', quantity: 25, price: 220, reorderLevel: 8 },
  { id: 2, name: 'Coke 1L', category: 'Beverage', quantity: 15, price: 65, reorderLevel: 5 },
  { id: 3, name: 'Instant Noodles', category: 'Snacks', quantity: 35, price: 18, reorderLevel: 10 },
  { id: 4, name: 'Soap', category: 'Household', quantity: 12, price: 42, reorderLevel: 5 }
];

const formatCurrency = (value) =>
  new Intl.NumberFormat('en-PH', { style: 'currency', currency: 'PHP' }).format(value || 0);

export default function App() {
  const [products, setProducts] = useState(initialProducts);
  const [cart, setCart] = useState([]);
  const [discount, setDiscount] = useState(0);

  const addToCart = (productId) => {
    const product = products.find(p => p.id === productId);
    if (!product) return;

    const existing = cart.find(item => item.id === productId);
    if (existing) {
      setCart(cart.map(item =>
        item.id === productId
          ? { ...item, quantity: item.quantity + 1, total: (item.quantity + 1) * item.price }
          : item
      ));
      return;
    }

    setCart([...cart, { id: product.id, name: product.name, quantity: 1, price: product.price, total: product.price }]);
  };

  const subtotal = cart.reduce((sum, item) => sum + item.total, 0);
  const discountAmount = subtotal * (discount / 100);
  const grandTotal = subtotal - discountAmount;

  return (
    <div style={{ padding: 24 }}>
      <h1>Sari-Sari Store System</h1>

      <div style={{ display: 'grid', gridTemplateColumns: '1fr 1.5fr', gap: 24 }}>
        <div>
          <h3>Products</h3>
          {products.map(product => (
            <div key={product.id} style={{ display: 'flex', justifyContent: 'space-between', marginBottom: 12 }}>
              <span>{product.name}</span>
              <button onClick={() => addToCart(product.id)}>Add</button>
            </div>
          ))}
        </div>

        <div>
          <h3>Cart</h3>
          <table width="100%">
            <thead>
              <tr>
                <th>Item</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Total</th>
              </tr>
            </thead>
            <tbody>
              {cart.map(item => (
                <tr key={item.id}>
                  <td>{item.name}</td>
                  <td>{item.quantity}</td>
                  <td>{formatCurrency(item.price)}</td>
                  <td>{formatCurrency(item.total)}</td>
                </tr>
              ))}
            </tbody>
          </table>

          <div style={{ marginTop: 16 }}>
            <label>Discount %</label>
            <input type="number" min="0" max="100" value={discount} onChange={(e) => setDiscount(Number(e.target.value))} />
          </div>

          <h4>Subtotal: {formatCurrency(subtotal)}</h4>
          <h4>Discount: {formatCurrency(discountAmount)}</h4>
          <h4>Grand Total: {formatCurrency(grandTotal)}</h4>
        </div>
      </div>
    </div>
  );
}