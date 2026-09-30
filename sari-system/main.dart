import 'package:flutter/material.dart';

void main() {
  runApp(const MyApp());
}

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Sari-Sari Store',
      theme: ThemeData(
        primarySwatch: Colors.green,
      ),
      home: const POSHome(),
    );
  }
}

class POSHome extends StatefulWidget {
  const POSHome({super.key});

  @override
  State<POSHome> createState() => _POSHomeState();
}

class _POSHomeState extends State<POSHome> {
  final List<Map<String, dynamic>> products = [
    {'name': 'Rice 5kg', 'price': 220.0, 'qty': 25},
    {'name': 'Coke 1L', 'price': 65.0, 'qty': 15},
    {'name': 'Soap', 'price': 42.0, 'qty': 12},
    {'name': 'Coffee', 'price': 28.0, 'qty': 20},
  ];

  final List<Map<String, dynamic>> cart = [];
  double discount = 0;

  void addToCart(String name, double price) {
    setState(() {
      final index = cart.indexWhere((item) => item['name'] == name);
      if (index >= 0) {
        cart[index]['quantity'] += 1;
        cart[index]['total'] = cart[index]['quantity'] * price;
      } else {
        cart.add({'name': name, 'price': price, 'quantity': 1, 'total': price});
      }
    });
  }

  double get subtotal {
    return cart.fold(0.0, (sum, item) => sum + (item['total'] as double));
  }

  double get grandTotal {
    return subtotal - (subtotal * (discount / 100));
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Sari-Sari Store'),
      ),
      body: Padding(
        padding: const EdgeInsets.all(16),
        child: Row(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const Text('Products', style: TextStyle(fontSize: 24, fontWeight: FontWeight.bold)),
                  const SizedBox(height: 12),
                  ...products.map((product) => Card(
                    child: ListTile(
                      title: Text(product['name']),
                      subtitle: Text('₱${product['price']}'),
                      trailing: IconButton(
                        icon: const Icon(Icons.add_shopping_cart),
                        onPressed: () => addToCart(product['name'], product['price']),
                      ),
                    ),
                  )).toList(),
                ],
              ),
            ),
            const SizedBox(width: 20),
            Expanded(
              child: Card(
                child: Padding(
                  padding: const EdgeInsets.all(16),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      const Text('Cart', style: TextStyle(fontSize: 24, fontWeight: FontWeight.bold)),
                      const SizedBox(height: 12),
                      ...cart.map((item) => ListTile(
                        title: Text(item['name']),
                        subtitle: Text('Qty: ${item['quantity']}'),
                        trailing: Text('₱${item['total']}'),
                      )).toList(),
                      const SizedBox(height: 12),
                      TextField(
                        keyboardType: TextInputType.number,
                        decoration: const InputDecoration(labelText: 'Discount %'),
                        onChanged: (value) => setState(() {
                          discount = double.tryParse(value) ?? 0;
                        }),
                      ),
                      const SizedBox(height: 12),
                      Text('Subtotal: ₱${subtotal.toStringAsFixed(2)}'),
                      Text('Grand Total: ₱${grandTotal.toStringAsFixed(2)}'),
                    ],
                  ),
                ),
              ),
            )
          ],
        ),
      ),
    );
  }
}