<?php
    session_start();
    require('inc/config.php');
    require('inc/essentials.php');

    $menu_items = $conn->query("SELECT item, price, image FROM cafemenu");
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=divice-width, initial-scale=1.0">
        <title>Cafe - Menu</title>
        <?php require('inc/link.php')?>

        <style>
            body {
                background-image: url('https://static.vecteezy.com/system/resources/previews/031/616/655/non_2x/inside-clean-kitchen-of-a-modern-restaurant-or-mini-cafe-with-cooking-utensils-and-small-bar-counter-concept-by-ai-generated-free-photo.jpg');
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;
                background-attachment: fixed;
                font-family: Arial, sans-serif;
            }

            .pop:hover{
                border-top-color: var(--teal) !important;
                transform: scale(1.03);
                transition: all 0.3s;
            }

        </style>
    </head>

    <body>
        <script>
            function changeQuantity(button, delta) {
                const container = button.closest('.input-group');
                const display = container.querySelector('.quantity-display');
                let current = parseInt(display.textContent) || 0;
                let updated = current + delta;
                if (updated < 0) updated = 0;
                display.textContent = updated;
                updateBottomButton();
            }

            function updateBottomButton() {
                const displays = document.querySelectorAll('.quantity-display');
                let total = 0;
                displays.forEach(display => {
                    total += parseInt(display.textContent) || 0;
                });
                const bottomBtn = document.getElementById('comfirmBtn');
                bottomBtn.style.display = total > 0 ? 'block' : 'none';
            }

            function confirmOrder() {
                const items = document.querySelectorAll('.menu-card');
                const orderData = [];
                const tableNumber = <?= isset($_SESSION['tablenumber']) ? (int)$_SESSION['tablenumber'] : 0 ?>;

                items.forEach(card => {
                    const quantity = parseInt(card.querySelector('.quantity-display').textContent);
                    if (quantity > 0) {
                        const name = card.querySelector('.item-name').value;
                        const price = parseFloat(card.querySelector('.item-price').value);
                        orderData.push({
                            item: name,
                            amount: quantity,
                            price: price,
                            subtotal: (price * quantity).toFixed(2)
                        });
                    }
                });

                if (orderData.length === 0) {
                    alert("No items selected.");
                    return;
                }

                fetch('submit_order.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ table: tableNumber, items: orderData })
                })
                .then(res => res.text())
                .then(response => {
                    alert(response);
                    window.location.href = "lobby.php";
                })
                .catch(err => {
                    console.error(err);
                    alert("Order failed.");
                });
            }

            function showCart() {
                const items = document.querySelectorAll('.menu-card');
                const cartItems = [];
                let total = 0;

                items.forEach(card => {
                    const quantity = parseInt(card.querySelector('.quantity-display').textContent);
                    if (quantity > 0) {
                        const name = card.querySelector('.item-name').value;
                        const price = parseFloat(card.querySelector('.item-price').value);
                        const subtotal = price * quantity;
                        total += subtotal;

                        cartItems.push(`<div class="d-flex justify-content-between mb-2">
                            <span>${name} x ${quantity}</span>
                            <span>€${subtotal.toFixed(2)}</span>
                        </div>`);
                    }
                });

                if (cartItems.length === 0) {
                    alert("Your cart is empty.");
                    return;
                }

                document.getElementById('cartItems').innerHTML = cartItems.join('');
                document.getElementById('cartTotal').textContent = `€${total.toFixed(2)}`;
                document.getElementById('cartPanel').style.display = 'block';
                document.getElementById('cartOverlay').style.display = 'block';
            }

            function hideCart() {
                document.getElementById('cartPanel').style.display = 'none';
                document.getElementById('cartOverlay').style.display = 'none';
            }
        </script>

        <div class="container my-5 px-4 bg-white shadow rounded p-2">
            <h2 class="fw-bold h-font text-center">
                The Menu - Table <?= isset($_SESSION['tablenumber']) ? str_pad($_SESSION['tablenumber'], 2, '0', STR_PAD_LEFT) : '00'; ?>
            </h2>
            <hr>
            <p class="text-center mt-3">Enjoy yourself from our selection.</p>
        </div>

        <div class="container bg-white shadow rounded p-4">
            <div class="row">
                <?php while ($row = $menu_items->fetch_assoc()): ?>
                    <div class="col-lg-6 col-md-6 mb-5 px-4 menu-card">
                        <div class="bg-white rounded shadow p-4 border-top border-4 pop">
                            <div class="d-flex align-items-center mb-2">
                                <img src="<?= htmlspecialchars($row['image']); ?>" class="img-fluid rounded" style="width: 100%; height: auto;">
                            </div>
                            <div class="d-flex flex-column align-items-start">
                                <h4 class="mb-1"><?= htmlspecialchars($row['item']) ?></h4>
                                <p class="text-muted">€ <?= number_format($row['price'], 2) ?></p>
                            </div>

                            <div class="input-group mb-2" style="width: 120px;">
                                <button type="button" class="btn custom-bg" onclick="changeQuantity(this, -1)">−</button>
                                <div class="text-center p-2 fw-bold quantity-display">0</div>
                                <button type="button" class="btn custom-bg" onclick="changeQuantity(this, 1)">+</button>
                            </div>

                            <input type="hidden" class="item-name" value="<?= htmlspecialchars($row['item']) ?>">
                            <input type="hidden" class="item-price" value="<?= $row['price'] ?>">
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>

        <div id="comfirmBtn" class="position-fixed bottom-0 start-0 end-0 bg-dark text-white text-center py-3" style="display: none; z-index: 1050;">
            <button type="button" class="btn text-white custom-bg form-control shadow-none text-center fs-4" onclick="showCart()">Check Cart</button>
        </div>

        <div id="cartPanel" class="position-fixed top-50 start-50 translate-middle bg-white p-4 rounded shadow" style="display: none; z-index: 2000; width: 90%; max-width: 500px;">
            <h4 class="mb-3">Check Orders</h4>
            <div id="cartItems"></div>
            <hr>
            <div class="d-flex justify-content-between fw-bold">
                <span>Total:</span>
                <span id="cartTotal">€0.00</span>
            </div>
            <div class="text-end mt-3">
                <button class="btn btn-secondary me-2" onclick="hideCart()">Close</button>
                <button class="btn btn-success" onclick="confirmOrder()">Confirm Order</button>
            </div>
        </div>

        <div id="cartOverlay" style="display:none; position: fixed; top:0; left:0; width:100%; height:100%; background-color: rgba(0,0,0,0.5); z-index: 1500;" onclick="hideCart()"></div>

        <?php require('inc/script.php') ?>
    </body>
</html>