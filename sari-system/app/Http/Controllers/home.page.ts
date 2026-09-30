import { Component } from '@angular/core';

@Component({
  selector: 'app-home',
  template: `
    <ion-header>
      <ion-toolbar color="primary">
        <ion-title>Sari-Sari Store</ion-title>
      </ion-toolbar>
    </ion-header>

    <ion-content class="ion-padding">
      <ion-grid>
        <ion-row>
          <ion-col size="12" sizeMd="6">
            <ion-card>
              <ion-card-header>
                <ion-card-title>Products</ion-card-title>
              </ion-card-header>
              <ion-card-content>
                <ion-list>
                  <ion-item *ngFor="let p of products">
                    <ion-label>{{ p.name }}</ion-label>
                    <ion-note>₱{{ p.price }}</ion-note>
                    <ion-button fill="outline" (click)="addToCart(p)">Add</ion-button>
                  </ion-item>
                </ion-list>
              </ion-card-content>
            </ion-card>
          </ion-col>

          <ion-col size="12" sizeMd="6">
            <ion-card>
              <ion-card-header>
                <ion-card-title>Cart</ion-card-title>
              </ion-card-header>
              <ion-card-content>
                <ion-list>
                  <ion-item *ngFor="let item of cart">
                    <ion-label>{{ item.name }} x {{ item.quantity }}</ion-label>
                    <ion-note>₱{{ item.total }}</ion-note>
                  </ion-item>
                </ion-list>

                <ion-item>
                  <ion-label>Discount %</ion-label>
                  <ion-input type="number" [(ngModel)]="discount"></ion-input>
                </ion-item>

                <p>Subtotal: ₱{{ subtotal }}</p>
                <p>Grand Total: ₱{{ grandTotal }}</p>
              </ion-card-content>
            </ion-card>
          </ion-col>
        </ion-row>
      </ion-grid>
    </ion-content>
  `
})
export class HomePage {
  products = [
    { name: 'Rice 5kg', price: 220 },
    { name: 'Coke 1L', price: 65 },
    { name: 'Soap', price: 42 },
    { name: 'Coffee', price: 28 }
  ];

  cart: any[] = [];
  discount = 0;

  addToCart(product: any) {
    const exists = this.cart.find(item => item.name === product.name);
    if (exists) {
      exists.quantity += 1;
      exists.total = exists.quantity * product.price;
    } else {
      this.cart.push({
        name: product.name,
        price: product.price,
        quantity: 1,
        total: product.price
      });
    }
  }

  get subtotal() {
    return this.cart.reduce((sum, item) => sum + item.total, 0);
  }

  get grandTotal() {
    return this.subtotal - (this.subtotal * (this.discount / 100));
  }
}