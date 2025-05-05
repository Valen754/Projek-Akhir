<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Tapal Kuda</title>
<style>
  @import url('https://fonts.googleapis.com/css2?family=Inter&display=swap');
  * {
    box-sizing: border-box;
  }

  html, body {
  width: 100%;
  margin: 0;
  padding: 0;
  height: 100%;
  font-family: 'Inter', sans-serif;
  background-color: #1c2431;
}


  .container {
  display: flex;          /* Menggunakan flexbox untuk menata elemen dalam satu baris */
  height: 100vh;          /* Memastikan tinggi kontainer mengisi seluruh layar */
}
  /* Sidebar */
  .sidebar {
  background-color: #222b3a;
  width: 80px;
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 32px 0;
  gap: 40px;
  border-top-left-radius: 12px;
  border-bottom-left-radius: 12px;
  height: 100vh;          /* Mengisi seluruh tinggi layar */
  position: relative;
}

  .sidebar button {
    background: none;
    border: none;
    color: #e07b6c;
    font-size: 20px;
    cursor: pointer;
  }
  /* Main content */
  main {
  flex: 1;
  padding: 32px;
  display: flex;
  flex-direction: column;
  gap: 24px;
  height: 100vh; /* Membuat main mengambil seluruh tinggi layar */
  min-height: 100vh; /* Pastikan elemen ini minimal mengisi layar */
}

main header {
  margin-bottom: 24px;
}

main header h1 {
  color: white;
  font-weight: 600;
  font-size: 20px;
  margin: 0 0 4px 0;
}

main header p {
  font-size: 14px;
  color: #6b7280;
  margin: 0;
}

  nav.tabs {
    display: flex;
    align-items: center;
    gap: 24px;
    font-size: 14px;
    font-weight: 600;
  }
  nav.tabs button {
    background: none;
    border: none;
    color: #6b7280;
    padding-bottom: 4px;
    cursor: pointer;
  }
  nav.tabs button.active {
    color: #e07b6c;
    border-bottom: 2px solid #e07b6c;
  }
  .search-container {
    margin-left: auto;
    position: relative;
    width: 240px;
  }
  .search-container input[type="search"] {
    width: 100%;
    padding: 8px 12px 8px 36px;
    border-radius: 6px;
    border: none;
    background-color: #2a3345;
    color: #9ca3af;
    font-size: 14px;
  }
  .search-container input::placeholder {
    color: #6b7280;
  }
  .search-container .icon-search {
    position: absolute;
    left: 10px;
    top: 50%;
    transform: translateY(-50%);
    color: #6b7280;
    font-size: 14px;
    pointer-events: none;
  }
  select.dine-in {
    margin-left: 16px;
    background-color: #2a3345;
    border: none;
    border-radius: 6px;
    color: #9ca3af;
    font-size: 14px;
    padding: 8px 12px;
    cursor: pointer;
  }
  /* Choose Dishes */
  section.choose-dishes h2 {
    font-weight: 700;
    color: white;
    margin-bottom: 16px;
  }
  .dishes-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill,minmax(200px,1fr));
    gap: 24px;
  }
  .dish-card {
    background-color: #2a3345;
    border-radius: 12px;
    padding: 24px 24px 32px 24px;
    text-align: center;
    color: #9ca3af;
    display: flex;
    flex-direction: column;
    align-items: center;
  }
  .dish-card img {
    width: 96px;
    height: 96px;
    border-radius: 50%;
    object-fit: cover;
    margin-bottom: 16px;
  }
  .dish-card h3 {
    color: white;
    font-weight: 600;
    margin: 0 0 8px 0;
    font-size: 16px;
  }
  .dish-card p.price {
    margin: 0 0 4px 0;
    font-size: 14px;
  }
  .dish-card p.available {
    margin: 0;
    font-size: 12px;
    color: #6b7280;
  }
  /* Orders panel */
  aside.orders-panel {
  background-color: #2a3345;
  width: 384px;
  border-top-right-radius: 12px;
  border-bottom-right-radius: 12px;
  padding: 32px;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  height: 100vh;          /* Mengisi seluruh tinggi layar */
  position: relative;
}
  aside.orders-panel header h2 {
    color: white;
    font-weight: 600;
    font-size: 16px;
    margin: 0 0 12px 0;
  }
  aside.orders-panel header h2 span {
    color: #6b7280;
    font-weight: 400;
    font-size: 14px;
  }
  nav.order-types {
    display: flex;
    gap: 12px;
    font-size: 12px;
    font-weight: 700;
  }
  nav.order-types button {
    border-radius: 8px;
    border: none;
    padding: 6px 16px;
    cursor: pointer;
    color: #9ca3af;
    background-color: #3f4556;
  }
  nav.order-types button.active {
    background-color: #e07b6c;
    color: white;
  }
  ul.order-list {
    list-style: none;
    padding: 0;
    margin: 24px 0 0 0;
    display: flex;
    flex-direction: column;
    gap: 20px;
  }
  ul.order-list li {
    display: flex;
    gap: 12px;
    align-items: flex-start;
  }
  ul.order-list li img {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
    flex-shrink: 0;
  }
  ul.order-list li .order-info {
    flex: 1;
  }
  ul.order-list li .order-info p.name {
    margin: 0 0 4px 0;
    font-weight: 600;
    font-size: 14px;
    color: white;
  }
  ul.order-list li .order-info p.price {
    margin: 0 0 6px 0;
    font-size: 12px;
    color: #6b7280;
  }
  ul.order-list li .order-info input[type="text"] {
    width: 100%;
    background-color: #1c2431;
    border: none;
    border-radius: 6px;
    padding: 6px 12px;
    font-size: 12px;
    color: #9ca3af;
  }
  ul.order-list li .order-info input::placeholder {
    color: #6b7280;
  }
  ul.order-list li .order-qty-delete {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
  }
  ul.order-list li .order-qty-delete span.qty {
    background-color: #1c2431;
    color: #9ca3af;
    font-size: 12px;
    border-radius: 8px;
    padding: 2px 8px;
    user-select: none;
  }
  ul.order-list li .order-qty-delete button {
    background: none;
    border: none;
    color: #e07b6c;
    cursor: pointer;
    font-size: 14px;
  }
  ul.order-list li .order-qty-delete button:hover {
    color: #f28a7a;
  }
  /* Footer */
  aside.orders-panel footer {
    border-top: 1px solid #374151;
    padding-top: 24px;
  }
  aside.orders-panel footer .discount,
  aside.orders-panel footer .subtotal {
    display: flex;
    justify-content: space-between;
    font-size: 12px;
    margin-bottom: 8px;
  }
  aside.orders-panel footer .subtotal {
    font-weight: 700;
    color: white;
    font-size: 16px;
    margin-bottom: 24px;
  }
  aside.orders-panel footer button {
    width: 100%;
    background-color: #e07b6c;
    border: none;
    border-radius: 12px;
    padding: 12px 0;
    font-weight: 700;
    color: white;
    font-size: 16px;
    cursor: pointer;
  }
  aside.orders-panel footer button:hover {
    background-color: #d46a5a;
  }
  /* Responsive */
  @media (max-width: 768px) {
    .container {
      flex-direction: column;
      min-height: auto;
      border-radius: 0;
    }
    .sidebar {
      flex-direction: row;
      width: 100%;
      padding: 16px 0;
      gap: 24px;
      border-radius: 0;
      justify-content: center;
    }
    main {
      padding: 16px;
    }
    aside.orders-panel {
      width: 100%;
      border-radius: 0;
      padding: 16px;
      margin-top: 24px;
    }
    .dishes-grid {
      grid-template-columns: repeat(auto-fill,minmax(140px,1fr));
      gap: 16px;
    }
  }
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
</head>
<body>
  <div class="container" role="main">
    <aside class="sidebar" aria-label="Sidebar navigation">
      <button aria-label="Hot Dishes"><i class="fas fa-utensils"></i></button>
      <button aria-label="Home"><i class="fas fa-home"></i></button>
      <button aria-label="Settings"><i class="fas fa-cog"></i></button>
      <button aria-label="Clock"><i class="fas fa-clock"></i></button>
      <button aria-label="Mail"><i class="fas fa-envelope"></i></button>
      <button aria-label="Notification"><i class="fas fa-bell"></i></button>
      <button aria-label="User"><i class="fas fa-user"></i></button>
    </aside>
    <main>
      <header>
        <h1>Tapal Kuda</h1>
        <p>Tuesday, 29 April 2025</p>
      </header>
      <nav class="tabs" aria-label="Dish categories">
        <button class="active" type="button">Hot Dishes</button>
        <button type="button">Cold Dishes</button>
        <button type="button">Beverages</button>
        <button type="button">Dessert</button>
        <div class="search-container">
          <input type="search" placeholder="Search for food, coffe, etc.." aria-label="Search for food, coffee, etc." />
          <i class="fas fa-search icon-search" aria-hidden="true"></i>
        </div>
        <select class="dine-in" aria-label="Select dining option">
          <option>Dine In</option>
          <option>To Go</option>
          <option>Delivery</option>
        </select>
      </nav>
      <section class="choose-dishes" aria-label="Choose Dishes">
        <h2>Choose Dishes</h2>
        <div class="dishes-grid">
          <article class="dish-card">
            <img src="https://storage.googleapis.com/a1aa/image/49e0285c-95f3-422d-b3f1-b885c97899f9.jpg" alt="A cup of cappuccino coffee in a red cup on a saucer with coffee beans around" width="96" height="96" />
            <h3>Cappucino</h3>
            <p class="price">Rp. 15.000</p>
            <p class="available">30 available</p>
          </article>
          <article class="dish-card">
            <img src="https://storage.googleapis.com/a1aa/image/d58102ca-ea06-4f69-88e8-5c3af13016b9.jpg" alt="A glass of espresso coffee with coffee beans around on a table" width="96" height="96" />
            <h3>Espresso</h3>
            <p class="price">Rp. 15.000</p>
            <p class="available">25 available</p>
          </article>
          <article class="dish-card">
            <img src="https://storage.googleapis.com/a1aa/image/b3130950-f8f9-431d-e7c3-c370e2c1d6ff.jpg" alt="A glass of iced coffee with a slice of lime and coffee beans on table" width="96" height="96" />
            <h3>Japan</h3>
            <p class="price">Rp. 20.000</p>
            <p class="available">20 available</p>
          </article>
          <article class="dish-card">
            <img src="https://storage.googleapis.com/a1aa/image/0cd16336-44cd-42fa-9ab9-b95b64fe5ae0.jpg" alt="A blue cup of Kopi Tubruk Arabika coffee on saucer with coffee beans" width="96" height="96" />
            <h3>Kopi Tubruk Arabika</h3>
            <p class="price">Rp. 20.000</p>
            <p class="available">35 available</p>
          </article>
          <article class="dish-card">
            <img src="https://storage.googleapis.com/a1aa/image/7ba0a7e8-e3ca-4a85-2543-9a0bc4231536.jpg" alt="A cup of latte coffee with frothy milk on top and coffee beans" width="96" height="96" />
            <h3>Latte</h3>
            <p class="price">Rp. 15.000</p>
            <p class="available">15 available</p>
          </article>
          <article class="dish-card">
            <img src="https://storage.googleapis.com/a1aa/image/42de4c42-323a-4fc0-19f9-d8c0ea734b03.jpg" alt="A cup of black coffee with steam and a slice of lime on a white saucer" width="96" height="96" />
            <h3>Sukomon</h3>
            <p class="price">Rp. 20.000</p>
            <p class="available">20 available</p>
          </article>
        </div>
      </section>
    </main>
    <aside class="orders-panel" aria-label="Orders panel">
      <header>
        <h2>Orders <span>#001</span></h2>
        <nav class="order-types" aria-label="Order types">
          <button class="active" type="button">Dine In</button>
          <button type="button">To Go</button>
          <button type="button">Delivery</button>
        </nav>
      </header>
      <ul class="order-list">
        <li>
          <img src="https://storage.googleapis.com/a1aa/image/8294678e-e685-4081-dce2-181e85ee96b9.jpg" alt="Small image of a cup of cappuccino coffee in a red cup on a saucer with coffee beans around" width="40" height="40" />
          <div class="order-info">
            <p class="name">Cappucino</p>
            <p class="price">Rp. 15.000</p>
            <input type="text" placeholder="Less sugar aja yaa" value="Less sugar aja yaa" aria-label="Order note for Cappucino" />
          </div>
          <div class="order-qty-delete">
            <span class="qty">1</span>
            <button aria-label="Delete Cappucino order" type="button"><i class="fas fa-trash-alt"></i></button>
          </div>
        </li>
        <li>
          <img src="https://storage.googleapis.com/a1aa/image/4ef37ba9-80d1-4e0f-3f08-904ef555fbae.jpg" alt="Small image of a glass of espresso coffee with coffee beans around on a table" width="40" height="40" />
          <div class="order-info">
            <p class="name">Espresso</p>
            <p class="price">Rp. 15.000</p>
            <input type="text" placeholder="Order Note..." aria-label="Order note for Espresso" />
          </div>
          <div class="order-qty-delete">
            <span class="qty">3</span>
            <button aria-label="Delete Espresso order" type="button"><i class="fas fa-trash-alt"></i></button>
          </div>
        </li>
        <li>
          <img src="https://storage.googleapis.com/a1aa/image/2b314b52-7258-4e5d-ffb3-3c33c84c8a61.jpg" alt="Small image of a blue cup of Kopi Tubruk Arabika coffee on saucer with coffee beans" width="40" height="40" />
          <div class="order-info">
            <p class="name">Kopi Tubruk Arabica</p>
            <p class="price">Rp. 20.000</p>
            <input type="text" placeholder="Order Note..." aria-label="Order note for Kopi Tubruk Arabica" />
          </div>
          <div class="order-qty-delete">
            <span class="qty">2</span>
            <button aria-label="Delete Kopi Tubruk Arabica order" type="button"><i class="fas fa-trash-alt"></i></button>
          </div>
        </li>
        <li>
          <img src="https://storage.googleapis.com/a1aa/image/fb37ce92-8a2e-4f08-5c15-471e9ea4ac5a.jpg" alt="Small image of a cup of latte coffee with frothy milk on top and coffee beans" width="40" height="40" />
          <div class="order-info">
            <p class="name">Latte</p>
            <p class="price">Rp. 15.000</p>
            <input type="text" placeholder="Order Note..." aria-label="Order note for Latte" />
          </div>
          <div class="order-qty-delete">
            <span class="qty">1</span>
            <button aria-label="Delete Latte order" type="button"><i class="fas fa-trash-alt"></i></button>
          </div>
        </li>
        <li>
          <img src="https://storage.googleapis.com/a1aa/image/fb37ce92-8a2e-4f08-5c15-471e9ea4ac5a.jpg" alt="Small image of a cup of latte coffee with frothy milk on top and coffee beans" width="40" height="40" />
          <div class="order-info">
            <p class="name">Latte</p>
            <p class="price">Rp. 15.000</p>
            <input type="text" placeholder="Order Note..." aria-label="Order note for Latte" />
          </div>
          <div class="order-qty-delete">
            <span class="qty">1</span>
            <button aria-label="Delete Latte order" type="button"><i class="fas fa-trash-alt"></i></button>
          </div>
        </li>
      </ul>
      <footer>
        <div class="discount">
          <span>Discount</span>
          <span>Rp. 10.000</span>
        </div>
        <div class="subtotal">
          <span>Sub total</span>
          <span>Rp. 105.000</span>
        </div>
        <button type="button">Continue to Payment</button>
      </footer>
    </aside>
  </div>
</body>
</html>