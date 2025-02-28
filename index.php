<?php
require 'config.php';

// Fetch products from the database
$sql = "SELECT identificador, nombre, descripcion, precio, stock FROM productos";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Andrei | Shop</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="logo">
            <img src="logo_tienda.svg" alt="E-Shop Logo">
        </div>
        <h1>Andrei | Shop</h1>
    </header>
    <main>
        <div class="products-container">
            <?php
            if ($result->num_rows > 0) {
                // Output data of each row
                while($row = $result->fetch_assoc()) {
                    echo "<div class='product'>";
                    echo "<h2>" . $row["nombre"] . "</h2>";
                    echo "<p>" . $row["descripcion"] . "</p>";
                    echo "<p>Precio: " . $row["precio"] . " €</p>";
                    echo "<p>Stock: " . $row["stock"] . "</p>";
                    echo "<button class='buy-button' data-sotck='" . $row["stock"] . "'>Comprar ahora!</button>";
                    echo "</div>";
                }
            } else {
                echo "<p>No hay productos en Stock.</p>";
            }
            ?>
        </div>
    </main>
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        const stripe = Stripe('<?php echo STRIPE_PUBLISHABLE_KEY; ?>');

        document.querySelectorAll('.buy-button').forEach(button => {
            button.addEventListener('click', function() {
                const productId = this.getAttribute('data-product-id');
                fetch('/payment.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ productId: productId }),
                })
                .then(response => response.json())
                .then(data => {
                    stripe.redirectToCheckout({ sessionId: data.id });
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            });
        });
    </script>
    <script src="script.js"></script>
</body>
</html>