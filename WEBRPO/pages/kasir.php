<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1" name="viewport" />
  <title>
    Tapal Kuda
  </title>
  <script src="https://cdn.tailwindcss.com">
  </script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&amp;display=swap" rel="stylesheet" />
  <style>
    body {
      font-family: 'Inter', sans-serif;
    }
  </style>
</head>

<body class="bg-[#1E2532] text-[#D1D5DB] min-h-screen flex items-center justify-center p-4">
  <div class="max-w-[1200px] w-full rounded-2xl bg-[#1E2532] flex flex-col md:flex-row overflow-hidden shadow-lg">
    <!-- Left Sidebar -->
    <aside class="bg-[#2B2F3A] w-16 flex flex-col items-center py-6 space-y-8 rounded-l-2xl">
      <button aria-label="Cutlery" class="text-[#E37B6A] text-xl">
        <i class="fas fa-utensils">
        </i>
      </button>
      <button aria-label="Home" class="text-[#E37B6A] text-xl">
        <i class="fas fa-home">
        </i>
      </button>
      <button aria-label="Settings" class="text-[#E37B6A] text-xl">
        <i class="fas fa-cog">
        </i>
      </button>
      <button aria-label="Clock" class="text-[#E37B6A] text-xl">
        <i class="fas fa-clock">
        </i>
      </button>
      <button aria-label="Mail" class="text-[#E37B6A] text-xl">
        <i class="fas fa-envelope">
        </i>
      </button>
      <button aria-label="Bell" class="text-[#E37B6A] text-xl">
        <i class="fas fa-bell">
        </i>
      </button>
      <button aria-label="User" class="text-[#E37B6A] text-xl">
        <i class="fas fa-user">
        </i>
      </button>
    </aside>
    <!-- Main Content -->
    <main class="flex-1 bg-[#1E2532] p-6 md:p-8 flex flex-col md:flex-row gap-6">
      <!-- Left main panel -->
      <section class="flex-1 flex flex-col">
        <header class="mb-6">
          <h1 class="text-white font-semibold text-lg leading-6">
            Tapal Kuda
          </h1>
          <p class="text-[#6B7280] text-xs mt-1">
            Tuesday, 29 April 2025
          </p>
        </header>
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4 gap-4">
          <nav class="flex space-x-4 text-xs font-semibold text-[#9CA3AF]">
            <button class="text-[]">

            </button>
            <button class="text-[]">

            </button>
            <button class="text-[#E37B6A] border-b-2 border-[#E37B6A] pb-1">
              Hot Dishes
            </button>
            <button>
              Cold Dishes
            </button>
            <button>
              Beverages
            </button>
            <button>
              Dessert
            </button>
          </nav>
          <div class="relative w-full md:w-64">
            <input
              class="w-full rounded-md bg-[#2B2F3A] text-[#9CA3AF] text-xs py-2 pl-10 pr-3 placeholder-[#9CA3AF] focus:outline-none focus:ring-1 focus:ring-[#E37B6A]"
              placeholder="Search for food, coffe, etc.." type="search" />
            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-[#9CA3AF] text-xs">
            </i>
          </div>
        </div>
        <div class="flex justify-between items-center mb-4">
          <h2 class="text-white font-semibold text-sm">
            Choose Dishes
          </h2>
          <button class="flex items-center gap-1 text-xs text-[#9CA3AF] bg-[#2B2F3A] rounded-md py-1 px-3"
            type="button">
            Dine In
            <i class="fas fa-chevron-down text-xs">
            </i>
          </button>
        </div>
        <ul class="grid grid-cols-2 sm:grid-cols-3 gap-6">
          <!-- Dish 1 -->
          <li class="bg-[#2B2F3A] rounded-xl p-4 flex flex-col items-center text-center text-[#9CA3AF] text-xs">
            <img alt="One Pot Chicken Biryani in a round bowl with yellow rice and chicken pieces"
              class="w-24 h-24 rounded-full mb-3 object-cover" height="96" src="../asset/CAPPUCINO.jpg" width="96" />
            <h3 class="text-white text-xs font-semibold mb-1">
              Cappucino
            </h3>
            <p class="mb-1">
              Rp. 15.000
            </p>
            <p class="text-[10px]">
              30 available
            </p>
          </li>
          <!-- Dish 2 -->
          <li class="bg-[#2B2F3A] rounded-xl p-4 flex flex-col items-center text-center text-[#9CA3AF] text-xs">
            <img alt="Fresh Corn Grill Tacos in a round bowl with grilled corn and toppings"
              class="w-24 h-24 rounded-full mb-3 object-cover" height="96" src="../asset/ESPRESSO.jpg" width="96" />
            <h3 class="text-white text-xs font-semibold mb-1">
              Espresso
            </h3>
            <p class="mb-1">
              Rp. 15.000
            </p>
            <p class="text-[10px]">
              25 available
            </p>
          </li>
          <!-- Dish 3 -->
          <li class="bg-[#2B2F3A] rounded-xl p-4 flex flex-col items-center text-center text-[#9CA3AF] text-xs">
            <img alt="Crispy Chicken Parmesan in a round bowl with crispy chicken and parmesan cheese"
              class="w-24 h-24 rounded-full mb-3 object-cover" height="96" src="../asset/JAPAN.jpg" width="96" />
            <h3 class="text-white text-xs font-semibold mb-1">
              Japan
            </h3>
            <p class="mb-1">
              Rp. 20.000
            </p>
            <p class="text-[10px]">
              20 available
            </p>
          </li>
          <!-- Dish 4 -->
          <li class="bg-[#2B2F3A] rounded-xl p-4 flex flex-col items-center text-center text-[#9CA3AF] text-xs">
            <img alt="Prawns Wrapped In Noodles in a round bowl with prawns and noodles"
              class="w-24 h-24 rounded-full mb-3 object-cover" height="96" src="../asset/KOPI TUBRUK ARABIKA.jpg" width="96" />
            <h3 class="text-white text-xs font-semibold mb-1">
              Kopi Tubruk Arabika
            </h3>
            <p class="mb-1">
              Rp. 20.000
            </p>
            <p class="text-[10px]">
              35 available
            </p>
          </li>
          <!-- Dish 5 -->
          <li class="bg-[#2B2F3A] rounded-xl p-4 flex flex-col items-center text-center text-[#9CA3AF] text-xs">
            <img alt="Spicy Ginger Szechuan Beef in a round bowl with beef and vegetables"
              class="w-24 h-24 rounded-full mb-3 object-cover" height="96" src="../asset/Latte.jpg" width="96" />
            <h3 class="text-white text-xs font-semibold mb-1">
              Latte
            </h3>
            <p class="mb-1">
              Rp. 15.000
            </p>
            <p class="text-[10px]">
              15 available
            </p>
          </li>
          <!-- Dish 6 -->
          <li class="bg-[#2B2F3A] rounded-xl p-4 flex flex-col items-center text-center text-[#9CA3AF] text-xs">
            <img alt="Sushi Spicy Tuna Roll in a round bowl with sushi rolls and lemon slice"
              class="w-24 h-24 rounded-full mb-3 object-cover" height="96" src="../asset/SUKOMON.jpg" width="96" />
            <h3 class="text-white text-xs font-semibold mb-1">
              Sukomon
            </h3>
            <p class="mb-1">
              Rp. 20.000
            </p>
            <p class="text-[10px]">
              20 available
            </p>
          </li>
        </ul>
      </section>
      <!-- Right panel -->
      <section class="bg-[#2B2F3A] rounded-2xl w-full md:w-[360px] p-6 flex flex-col text-xs text-[#9CA3AF]">
        <header class="flex justify-between items-center mb-6">
          <h2 class="text-white font-semibold text-sm">
            Orders
            <span class="text-[#6B7280]">
              #001
            </span>
          </h2>
          <div class="flex gap-2">
            <button class="bg-[#E37B6A] text-white rounded-md px-3 py-1 text-xs font-semibold" type="button">
              Dine In
            </button>
            <button class="bg-[#3B3F4A] rounded-md px-3 py-1 text-xs font-semibold" type="button">
              To Go
            </button>
            <button class="bg-[#3B3F4A] rounded-md px-3 py-1 text-xs font-semibold" type="button">
              Delivery
            </button>
          </div>
        </header>
        <ul class="flex flex-col gap-4 flex-1 overflow-y-auto pr-1">
          <!-- Order item 1 -->
          <li class="flex gap-3">
            <img alt="One Pot Chicken Biryani small round bowl with yellow rice and chicken pieces"
              class="w-8 h-8 rounded-full object-cover mt-1" height="32" src="CAPPUCINO.jpg" width="32" />
            <div class="flex-1">
              <p class="text-white font-semibold truncate">
                Cappucino
              </p>
              <p class="text-[#6B7280] text-[10px] mb-1">
                Rp. 15.000
              </p>
              <textarea
                class="w-full resize-none rounded-md bg-[#1E2532] text-[#9CA3AF] text-[10px] p-1 placeholder-[#6B7280] focus:outline-none"
                placeholder="Less sugar bangg" rows="1">Less sugar aja yaa</textarea>
            </div>
            <div class="flex flex-col items-center justify-center gap-2">
              <input class="w-10 text-center rounded-md bg-[#1E2532] text-white text-xs focus:outline-none" min="1"
                type="number" value="1" />
              <button aria-label="Delete One Pot Chicken Biryani" class="text-[#E37B6A] hover:text-red-500"
                type="button">
                <i class="fas fa-trash-alt">
                </i>
              </button>
            </div>
          </li>
          <!-- Order item 2 -->
          <li class="flex gap-3">
            <img alt="Sushi Spicy Tuna Roll small round bowl with sushi rolls and lemon slice"
              class="w-8 h-8 rounded-full object-cover mt-1" height="32" src="ESPRESSO1.jpg" width="32" />
            <div class="flex-1">
              <p class="text-white font-semibold truncate">
                Espresso
              </p>
              <p class="text-[#6B7280] text-[10px] mb-1">
                Rp. 15.000
              </p>
              <textarea
                class="w-full resize-none rounded-md bg-[#1E2532] text-[#9CA3AF] text-[10px] p-1 placeholder-[#6B7280] focus:outline-none"
                placeholder="Order Note..." rows="1"></textarea>
            </div>
            <div class="flex flex-col items-center justify-center gap-2">
              <input class="w-10 text-center rounded-md bg-[#1E2532] text-white text-xs focus:outline-none" min="1"
                type="number" value="3" />
              <button aria-label="Delete Sushi Spicy Tuna Roll" class="text-[#E37B6A] hover:text-red-500" type="button">
                <i class="fas fa-trash-alt">
                </i>
              </button>
            </div>
          </li>
          <!-- Order item 3 -->
          <li class="flex gap-3">
            <img alt="Crispy Chicken Parmesan small round bowl with crispy chicken and parmesan cheese"
              class="w-8 h-8 rounded-full object-cover mt-1" height="32" src="KOPI TUBRUK ARABIKA.jpg" width="32" />
            <div class="flex-1">
              <p class="text-white font-semibold truncate">
                Kopi Tubruk Arabica
              </p>
              <p class="text-[#6B7280] text-[10px] mb-1">
                Rp. 20.000
              </p>
              <textarea
                class="w-full resize-none rounded-md bg-[#1E2532] text-[#9CA3AF] text-[10px] p-1 placeholder-[#6B7280] focus:outline-none"
                placeholder="Order Note..." rows="1"></textarea>
            </div>
            <div class="flex flex-col items-center justify-center gap-2">
              <input class="w-10 text-center rounded-md bg-[#1E2532] text-white text-xs focus:outline-none" min="1"
                type="number" value="2" />
              <button aria-label="Delete Crispy Chicken Parmesan" class="text-[#E37B6A] hover:text-red-500"
                type="button">
                <i class="fas fa-trash-alt">
                </i>
              </button>
            </div>
          </li>
          <!-- Order item 4 -->
          <li class="flex gap-3">
            <img alt="Spicy Ginger Szechuan Beef small round bowl with beef and vegetables"
              class="w-8 h-8 rounded-full object-cover mt-1" height="32" src="Latte.jpg" width="32" />
            <div class="flex-1">
              <p class="text-white font-semibold truncate">
                Latte
              </p>
              <p class="text-[#6B7280] text-[10px] mb-1">
                Rp. 15.000
              </p>
              <textarea
                class="w-full resize-none rounded-md bg-[#1E2532] text-[#9CA3AF] text-[10px] p-1 placeholder-[#6B7280] focus:outline-none"
                placeholder="Order Note..." rows="1"></textarea>
            </div>
            <div class="flex flex-col items-center justify-center gap-2">
              <input class="w-10 text-center rounded-md bg-[#1E2532] text-white text-xs focus:outline-none" min="1"
                type="number" value="1" />
              <button aria-label="Delete Spicy Ginger Szechuan Beef" class="text-[#E37B6A] hover:text-red-500"
                type="button">
                <i class="fas fa-trash-alt">
                </i>
              </button>
            </div>
          </li>
        </ul>
        <div class="mt-6 border-t border-[#3B3F4A] pt-4">
          <div class="flex justify-between mb-2">
            <span>
              Discount
            </span>
            <span>
              Rp. 10.000
            </span>
          </div>
          <div class="flex justify-between font-semibold text-white text-sm mb-6">
            <span>
              Sub total
            </span>
            <span>
              Rp. 105.000
            </span>
          </div>
          <button
            class="w-full bg-[#E37B6A] text-white rounded-lg py-2 text-sm font-semibold hover:bg-[#d66a5a] transition-colors"
            type="button">
            Continue to Payment
          </button>
        </div>
      </section>
    </main>
  </div>
</body>

</html>