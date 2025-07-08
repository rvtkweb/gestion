<?php
// Incluir configuración de base de datos
include 'config.php';

// Verificar autenticación
include 'session_secure.php';
startSecureSession();
if (!isset($_SESSION['authenticated']) && !isset($_POST['password'])) {
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Gestión Revestika</title>
        <!-- Evitar solicitud de favicon para resolver error 404 -->
        <link rel="icon" href="data:,">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
        <style>
            body {
                font-family: Arial, sans-serif;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                height: 100vh;
                display: flex;
                justify-content: center;
                align-items: center;
                margin: 0;
            }
            .login-container {
                background: white;
                padding: 40px;
                border-radius: 15px;
                box-shadow: 0 20px 40px rgba(0,0,0,0.1);
                text-align: center;
                max-width: 400px;
                width: 90%;
            }
            .logo {
                font-size: 3em;
                margin-bottom: 20px;
            }
            h2 {
                color: #333;
                margin-bottom: 30px;
            }
            input[type="password"] {
                width: 100%;
                padding: 15px;
                font-size: 16px;
                border: 2px solid #e9ecef;
                border-radius: 8px;
                margin-bottom: 20px;
                text-align: center;
                box-sizing: border-box;
            }
            input[type="password"]:focus {
                outline: none;
                border-color: #4CAF50;
            }
            .btn {
                background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
                color: white;
                padding: 15px 30px;
                border: none;
                border-radius: 8px;
                cursor: pointer;
                font-size: 16px;
                font-weight: 600;
                width: 100%;
                box-sizing: border-box;
            }
            .btn:hover {
                transform: translateY(-2px);
                transition: transform 0.2s ease;
            }
            .error {
                color: #f44336;
                margin-bottom: 20px;
                padding: 10px;
                background: #ffebee;
                border-radius: 5px;
            }
            @media print {
                * {
                    -webkit-print-color-adjust: exact !important;
                    color-adjust: exact !important;
                }
            }

            .quote-header-logo {
                font-family: 'Montserrat', sans-serif;
                font-weight: 700;
                font-size: 42px;
                letter-spacing: 2px;
                margin-bottom: 20px;
            }

            .quote-title {
                color: #222222;
                font-family: 'Montserrat', sans-serif;
                font-weight: 700;
                font-size: 28px;
                letter-spacing: 1px;
                margin: 0;
            }

            .quote-subtitle {
                color: #c9a86a;
                font-family: 'Montserrat', sans-serif;
                font-weight: 600;
                font-size: 18px;
                letter-spacing: 0.5px;
                margin: 15px 0 0 0;
            }

            .card.fiscal-current {
                background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            }

            .card.fiscal-pending {
                background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
            }

            .card.fiscal-paid {
                background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
            }

            .card.fiscal-overdue {
                background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            }

            /* Responsive para la nueva fila */
            @media (max-width: 768px) {
                .summary-cards {
                    grid-template-columns: 1fr !important;
                }
            }

            /* Estilos mejorados para botones de presupuesto */
            .quote-total .btn {
                transition: all 0.3s ease;
                border: none;
                color: white;
                font-weight: 600;
                padding: 15px 25px;
                border-radius: 8px;
                cursor: pointer;
                text-decoration: none;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
            }

            .quote-total .btn:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            }

            .quote-total .btn:active {
                transform: translateY(0);
            }

            /* Responsive para botones de presupuesto */
            @media (max-width: 768px) {
                .quote-total div[style*="display: flex"] {
                    flex-direction: column !important;
                }
                
                .quote-total .btn {
                    margin-bottom: 10px;
                }
            }
        </style>
    </head>
    <body>
        <div class="login-container">
            <div class="logo">🏗️</div>
            <h2>Gestión Revestika</h2>
            <?php if (isset($_POST['password']) && $_POST['password'] !== '79145'): ?>
                <div class="error">❌ Contraseña incorrecta</div>
            <?php endif; ?>
            <form method="POST">
                <input type="password" name="password" placeholder="Ingresa la contraseña" required autofocus>
                <button type="submit" class="btn">🔓 Ingresar</button>
            </form>
            <p style="margin-top: 20px; color: #666; font-size: 14px;">
                Acceso exclusivo para socios
            </p>
        </div>
    </body>
    </html>
    <?php
    exit;
}

// Verificar contraseña
if (isset($_POST['password'])) {
    if ($_POST['password'] === '79145') {
        $_SESSION['authenticated'] = true;
    } else {
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
}

// Si no está autenticado, mostrar login
if (!isset($_SESSION['authenticated'])) {
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión Revestika</title>
    <!-- Evitar solicitud de favicon para resolver error 404 -->
    <link rel="icon" href="data:,">
    <!-- Incluir jsPDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
            color: white;
            padding: 30px;
            text-align: center;
            position: relative;
        }

        .header h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
        }

        .logout-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            background: rgba(255,255,255,0.2);
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 12px;
            text-decoration: none;
        }

        .logout-btn:hover {
            background: rgba(255,255,255,0.3);
        }

        /* NUEVO: Botones de moneda */
        .currency-buttons {
            position: absolute;
            top: 20px;
            left: 20px;
            display: flex;
            gap: 10px;
        }

        .currency-btn {
            background: rgba(255,255,255,0.2);
            color: white;
            border: 2px solid rgba(255,255,255,0.3);
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 12px;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .currency-btn:hover {
            background: rgba(255,255,255,0.3);
            transform: translateY(-1px);
        }

        .currency-btn.active {
            background: white;
            color: #4CAF50;
            border-color: white;
        }

        .currency-loader {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(0,0,0,0.8);
            color: white;
            padding: 20px;
            border-radius: 10px;
            z-index: 9999;
            text-align: center;
        }

        .nav-tabs {
            display: flex;
            background: #f8f9fa;
            border-bottom: 2px solid #e9ecef;
            overflow-x: auto;
        }

        .nav-tab {
            flex: 1;
            min-width: 120px;
            padding: 15px 20px;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s ease;
            color: #6c757d;
            white-space: nowrap;
        }

        .nav-tab.active {
            background: white;
            color: #4CAF50;
            border-bottom: 3px solid #4CAF50;
        }

        .nav-tab:hover {
            background: #e9ecef;
        }

        .tab-content {
            display: none;
            padding: 30px;
        }

        .tab-content.active {
            display: block;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .form-row-3 {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }

        input, select, textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s ease;
            box-sizing: border-box;
        }

        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #4CAF50;
        }
        .btn {
            background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            transition: transform 0.2s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .btn-danger {
            background: linear-gradient(135deg, #f44336 0%, #d32f2f 100%);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
        }

        .btn-edit {
            background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%);
            padding: 8px 15px;
            font-size: 14px;
        }

        .btn-pdf {
            background: linear-gradient(135deg, #9c27b0 0%, #7b1fa2 100%);
            margin-left: 10px;
        }

        .summary-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        .card.cash {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        }

        .card.digital {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        }

        .card.fixed {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        }

        .card.variable {
            background: linear-gradient(135deg, #fd7e14 0%, #e55100 100%);
        }

        .card h3 {
            font-size: 1.1em;
            margin-bottom: 10px;
            opacity: 0.9;
        }

        .card .amount {
            font-size: 1.8em;
            font-weight: bold;
        }

        .transactions-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .transactions-table th {
            background: #4CAF50;
            color: white;
            padding: 15px;
            text-align: left;
            font-weight: 600;
            font-size: 12px;
        }

        .transactions-table td {
            padding: 8px 12px;
            border-bottom: 1px solid #e9ecef;
            font-size: 14px;
        }

        .transactions-table tr:hover {
            background: #f8f9fa;
        }

        .income {
            color: #4CAF50;
            font-weight: bold;
        }

        .expense {
            color: #f44336;
            font-weight: bold;
        }

        .delete-btn {
            background: #f44336;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 12px;
        }

        .filter-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .filter-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            align-items: end;
        }

        .product-item {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 10px;
            border-left: 4px solid #4CAF50;
            position: relative;
        }

        .product-item h4 {
            margin-bottom: 5px;
            color: #333;
        }

        .product-item p {
            margin: 3px 0;
            color: #666;
            font-size: 14px;
        }

        .product-actions {
            position: absolute;
            top: 15px;
            right: 15px;
            display: flex;
            gap: 5px;
        }

        .category-manager {
            background: #e3f2fd;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .category-input {
            display: flex;
            gap: 10px;
            align-items: end;
        }

        .category-list {
            margin-top: 15px;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .category-tag {
            background: #4CAF50;
            color: white;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 12px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .category-tag .remove-category {
            background: rgba(255,255,255,0.3);
            border: none;
            color: white;
            border-radius: 50%;
            width: 16px;
            height: 16px;
            cursor: pointer;
            font-size: 10px;
        }

        .edit-modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }

        .modal-content {
            background-color: white;
            margin: 5% auto;
            padding: 30px;
            border-radius: 15px;
            width: 90%;
            max-width: 600px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 15px;
        }

        .close {
            background: none;
            border: none;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            color: #999;
        }

        .close:hover {
            color: #333;
        }
        /* Estilos específicos para las tarjetas del dashboard */
        .card.income-total {
            background: linear-gradient(135deg, #4CAF50 0%, #388E3C 100%);
        }

        .card.income-banking {
            background: linear-gradient(135deg, #66BB6A 0%, #4CAF50 100%);
        }

        .card.income-cash {
            background: linear-gradient(135deg, #81C784 0%, #66BB6A 100%);
        }

        .card.income-balance {
            background: linear-gradient(135deg, #2E7D32 0%, #1B5E20 100%);
        }

        .card.expense-total {
            background: linear-gradient(135deg, #f44336 0%, #d32f2f 100%);
        }

        .card.expense-fixed {
            background: linear-gradient(135deg, #FF5722 0%, #E64A19 100%);
        }

        .card.expense-variable {
            background: linear-gradient(135deg, #FF9800 0%, #F57C00 100%);
        }

        .card.expense-investment {
            background: linear-gradient(135deg, #FF6F00 0%, #E65100 100%);
        }

        .card.balance-cash {
            background: linear-gradient(135deg, #03A9F4 0%, #0288D1 100%);
        }

        .card.balance-digital {
            background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
        }

        .card.balance-pending-income {
            background: linear-gradient(135deg, #00BCD4 0%, #0097A7 100%);
        }

        .card.balance-pending-expense {
            background: linear-gradient(135deg, #009688 0%, #00695C 100%);
        }

        /* Estilos para stock */
        .stock-item {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 15px;
            border-left: 4px solid #4CAF50;
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr auto;
            gap: 20px;
            align-items: center;
        }

        .stock-item.low-stock {
            border-left-color: #ff9800;
            background: #fff3e0;
        }

        .stock-item.out-of-stock {
            border-left-color: #f44336;
            background: #ffebee;
        }

        .stock-info h4 {
            margin: 0 0 5px 0;
            color: #333;
        }

        .stock-info p {
            margin: 2px 0;
            color: #666;
            font-size: 14px;
        }

        .stock-quantity {
            text-align: center;
            font-size: 1.5em;
            font-weight: bold;
        }

        .stock-quantity.low {
            color: #ff9800;
        }

        .stock-quantity.out {
            color: #f44336;
        }

        .stock-actions {
            display: flex;
            gap: 10px;
            flex-direction: column;
        }

        .stock-actions input {
            width: 80px;
            padding: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
            text-align: center;
        }

        .stock-actions button {
            padding: 5px 10px;
            font-size: 12px;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            color: white;
        }

        .btn-add-stock {
            background: #4CAF50;
        }

        .btn-remove-stock {
            background: #ff9800;
        }

        /* Estilos para inversiones */
        .investment-summary {
            background: linear-gradient(135deg, #6f42c1 0%, #563d7c 100%);
            color: white;
            padding: 30px;
            border-radius: 15px;
            text-align: center;
            margin-bottom: 30px;
        }

        .investment-summary h3 {
            font-size: 1.2em;
            margin-bottom: 10px;
            opacity: 0.9;
        }

        .investment-summary .amount {
            font-size: 2.5em;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .roi-analysis {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .roi-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            text-align: center;
        }

        .roi-card h4 {
            color: #333;
            margin-bottom: 10px;
        }

        .roi-card .value {
            font-size: 1.8em;
            font-weight: bold;
            color: #4CAF50;
        }

        /* Estilos para presupuestos */
        .quote-item {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .quote-total {
            background: #4CAF50;
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            margin-top: 20px;
        }

        .quote-total h3 {
            font-size: 2em;
        }

        /* Estilos para métodos de pago */
        .payment-method {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .payment-method input[type="radio"] {
            width: auto;
        }

        /* Badges */
        .badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }

        .badge-cash {
            background: #28a745;
            color: white;
        }

        .badge-digital {
            background: #007bff;
            color: white;
        }

        .badge-fixed {
            background: #dc3545;
            color: white;
        }

        .badge-variable {
            background: #fd7e14;
            color: white;
        }

        /* Print styles */
        .print-section {
            background: white;
            padding: 30px;
            margin: 20px 0;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        @media print {
            body * {
                visibility: hidden;
            }
            .print-section, .print-section * {
                visibility: visible;
            }
            .print-section {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                margin: 0;
                padding: 20px;
                box-shadow: none;
            }
            .btn {
                display: none;
            }
        }

        /* Media queries para responsive */
        @media (max-width: 768px) {
            .form-row, .form-row-3, .filter-row {
                grid-template-columns: 1fr;
            }
            
            .nav-tabs {
                overflow-x: auto;
            }
            
            .summary-cards {
                grid-template-columns: 1fr;
            }
            
            .transactions-table {
                font-size: 12px;
            }
            
            .transactions-table th,
            .transactions-table td {
                padding: 8px 5px;
            }
            
            .product-actions {
                position: static;
                justify-content: flex-start;
                margin-top: 10px;
            }

            .stock-item {
                grid-template-columns: 1fr;
                gap: 10px;
                text-align: center;
            }
            
            .stock-actions {
                flex-direction: row;
                justify-content: center;
            }
            
            .currency-buttons {
                position: static;
                justify-content: center;
                margin-bottom: 10px;
            }
        }
    </style>
</head>
<body>
    <!-- Loader para conversiones de moneda -->
    <div id="currencyLoader" class="currency-loader">
        <div>💱 Convirtiendo moneda...</div>
        <div style="margin-top: 10px; font-size: 12px;">Por favor espera</div>
    </div>

    <div class="container">
        <div class="header">
            <!-- Botones de moneda -->
            <div class="currency-buttons">
                <button id="currencyARS" class="currency-btn active" onclick="changeCurrency('ARS')">
                    💰 ARS
                </button>
                <button id="currencyUSD" class="currency-btn" onclick="changeCurrency('USD')">
                    💵 USD
                </button>
            </div>

            <a href="logout.php" class="logout-btn" 
                onclick="return confirm('¿Estás seguro de que quieres salir?')">🚪 Salir</a>
            <h1>🏗️ Gestión Revestika</h1>
            <p>Tablero de control</p>
        </div>

        <div class="nav-tabs">
            <button class="nav-tab active" onclick="showTab('dashboard')">📊 Dashboard</button>
            <button class="nav-tab" onclick="showTab('products')">📦 Productos</button>
            <button class="nav-tab" onclick="showTab('stock')">📊 Stock</button>
            <button class="nav-tab" onclick="showTab('income')">💰 Ingresos</button>
            <button class="nav-tab" onclick="showTab('expenses')">💸 Gastos</button>
            <button class="nav-tab" onclick="showTab('balances')">💳 Saldos</button>
            <button class="nav-tab" onclick="showTab('taxes')">🧾 Imp. y GF</button>
            <button class="nav-tab" onclick="showTab('investment')">💼 Inversión</button>
            <button class="nav-tab" onclick="showTab('quotes')">📋 Presupuestos</button>
            <button class="nav-tab" onclick="showTab('history')">📖 Historial</button>
        </div>

        <!-- Dashboard Tab -->
        <div id="dashboard" class="tab-content active">
            <!-- FILA 1: Ingresos totales, por método y Balance -->
            <div class="summary-cards" style="grid-template-columns: repeat(4, 1fr); margin-bottom: 20px;">
                <div class="card income-total">
                    <h3>Total Ingresos por ventas</h3>
                    <div class="amount" id="totalIncome">$0</div>
                </div>
                <div class="card income-banking">
                    <h3>💳 Ingresos Bancarios</h3>
                    <div class="amount" id="totalIncomeBanking">$0</div>
                </div>
                <div class="card income-cash">
                    <h3>💵 Ingresos Cash</h3>
                    <div class="amount" id="totalIncomeCash">$0</div>
                </div>
                <div class="card income-balance">
                    <h3>Balance Neto</h3>
                    <div class="amount" id="netBalance">$0</div>
                </div>
            </div>
            
            <!-- FILA 2: Total Gastos y por tipo -->
            <div class="summary-cards" style="grid-template-columns: repeat(4, 1fr); margin-bottom: 20px;">
                <div class="card expense-total">
                    <h3>💸 Total Gastos</h3>
                    <div class="amount" id="totalExpenses">$0</div>
                </div>
                <div class="card expense-fixed">
                    <h3>🔒 Gastos Fijos</h3>
                    <div class="amount" id="fixedExpenses">$0</div>
                </div>
                <div class="card expense-variable">
                    <h3>📈 Gastos Variables</h3>
                    <div class="amount" id="variableExpenses">$0</div>
                </div>
                <div class="card expense-investment">
                    <h3>💰 Total Inversión</h3>
                    <div class="amount" id="totalInvestmentDashboard">$0</div>
                </div>
            </div>
            
            <!-- FILA 3: Saldos y Pendientes -->
            <div class="summary-cards" style="grid-template-columns: repeat(4, 1fr); margin-bottom: 30px;">
                <div class="card balance-cash">
                    <h3>💵 Saldo Cash</h3>
                    <div class="amount" id="cashBalance">$0</div>
                </div>
                <div class="card balance-digital">
                    <h3>💳 Saldo Bancos</h3>
                    <div class="amount" id="digitalBalance">$0</div>
                </div>
                <div class="card balance-pending-income">
                    <h3>⏳ Saldo por Cobrar</h3>
                    <div class="amount" id="pendingIncome">$0</div>
                </div>
                <div class="card balance-pending-expense">
                    <h3>⏰ Gastos a Crédito</h3>
                    <div class="amount" id="pendingExpenses">$0</div>
                </div>
            </div>

            <!-- FILA 4: Control Fiscal y Facturación -->
            <div class="summary-cards" style="grid-template-columns: repeat(5, 1fr); margin-bottom: 30px;">
                <div class="card" style="background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);">
                    <h3>📅 Período Actual</h3>
                    <div class="amount" id="currentTaxPeriod">Diciembre 2024</div>
                </div>
                <div class="card" style="background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);">
                    <h3>⏳ Imp. y GF Pendientes de Pago</h3>
                    <div class="amount" id="pendingTaxCount">0</div>
                </div>
                <div class="card" style="background: linear-gradient(135deg, #27ae60 0%, #229954 100%);">
                    <h3>✅ Imp. y GF Pagados Este Mes</h3>
                    <div class="amount" id="paidThisMonthCount">0</div>
                </div>
                <div class="card" style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);">
                    <h3>🚨 Imp. y GF Impagos meses Anteriores</h3>
                    <div class="amount" id="overdueTaxCount">0</div>
                </div>
                <div class="card" style="background: linear-gradient(135deg, #8e44ad 0%, #9b59b6 100%); cursor: pointer;" 
                    onclick="showPendingInvoicesDetail()" title="Click para ver detalle">
                    <h3>📋 Ventas por Facturar</h3>
                    <div class="amount" id="pendingInvoicesCount">0</div>
                </div>
            </div>

            <!-- Filtros por período -->
            <div class="filter-section">
                <h3>Resumen por Período</h3>
                <div class="filter-row">
                    <div class="form-group">
                        <label>Desde:</label>
                        <input type="date" id="dateFrom" onchange="updateDashboard()">
                    </div>
                    <div class="form-group">
                        <label>Hasta:</label>
                        <input type="date" id="dateTo" onchange="updateDashboard()">
                    </div>
                    <div class="form-group">
                        <button class="btn" onclick="resetDateFilter()">Ver Todo</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Products Tab -->
        <div id="products" class="tab-content">
            <h2>Gestión de Productos</h2>
            
            <!-- Gestión de Categorías -->
            <div class="category-manager">
                <h3>📂 Gestionar Categorías</h3>
                <div class="category-input">
                    <div class="form-group" style="margin-bottom: 0; flex: 1;">
                        <label>Nueva Categoría:</label>
                        <input type="text" id="newCategory" placeholder="Nombre de la categoría">
                    </div>
                    <button type="button" class="btn" onclick="addCategory()">➕ Agregar</button>
                </div>
                <div class="category-list" id="categoryList">
                    <!-- Categories will be populated here -->
                </div>
            </div>
            
            <form id="productForm">
                <div class="form-row">
                    <div class="form-group">
                        <label>Nombre del Producto:</label>
                        <input type="text" id="productName" placeholder="Ej: Panel Marquina Negro SPC 3mm" required>
                    </div>
                    <div class="form-group">
                        <label>Categoría:</label>
                        <select id="productCategory" required>
                            <option value="">Seleccionar...</option>
                        </select>
                    </div>
                </div>
                <div class="form-row-3">
                    <div class="form-group">
                        <label>Dimensiones:</label>
                        <input type="text" id="productDimensions" placeholder="Ej: 1,22 x 2,44 mts" required>
                    </div>
                    <div class="form-group">
                        <label>Precio de Venta:</label>
                        <input type="number" id="productPrice" step="0.01" placeholder="0.00" required>
                    </div>
                    <div class="form-group">
                        <label>Unidad:</label>
                        <select id="productUnit" required>
                            <option value="unidad">Unidad</option>
                            <option value="m²">m²</option>
                            <option value="ml">ml</option>
                            <option value="kg">kg</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label>Descripción adicional:</label>
                    <textarea id="productDescription" rows="2" placeholder="Detalles del producto..."></textarea>
                </div>
                <button type="submit" class="btn">📦 Agregar Producto</button>
            </form>

            <h3 style="margin-top: 40px;">Productos Registrados</h3>
            <div id="productsList">
                <!-- Products will be populated here -->
            </div>
        </div>

        <!-- Stock Tab -->
        <div id="stock" class="tab-content">
            <h2>Control de Stock</h2>
            
            <div class="summary-cards">
                <div class="card">
                    <h3>Total Productos</h3>
                    <div class="amount" id="totalProducts">0</div>
                </div>
                <div class="card" style="background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%);">
                    <h3>Stock Bajo</h3>
                    <div class="amount" id="lowStockCount">0</div>
                </div>
                <div class="card" style="background: linear-gradient(135deg, #f44336 0%, #d32f2f 100%);">
                    <h3>Sin Stock</h3>
                    <div class="amount" id="outOfStockCount">0</div>
                </div>
                <div class="card">
                    <h3>Valor Total Stock</h3>
                    <div class="amount" id="totalStockValue">$0</div>
                </div>
            </div>

            <div class="filter-section">
                <h3>Gestión de Stock Inicial</h3>
                <div class="filter-row">
                    <div class="form-group">
                        <label>Producto:</label>
                        <select id="stockProductSelect">
                            <option value="">Seleccionar producto...</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Stock Inicial:</label>
                        <input type="number" id="initialStock" placeholder="Cantidad inicial" min="0">
                    </div>
                    <div class="form-group">
                        <label>Stock Mínimo:</label>
                        <input type="number" id="minimumStock" placeholder="Stock mínimo" min="0">
                    </div>
                    <div class="form-group">
                        <button class="btn" onclick="setInitialStock()">📊 Establecer Stock</button>
                    </div>
                </div>
            </div>

            <div style="margin: 20px 0; text-align: center;">
                <button class="btn" onclick="synchronizeStock()" style="background: #ff9800;">
                    🔄 Sincronizar Stock Actual con Reducciones
                </button>
                <p style="font-size: 12px; color: #666; margin-top: 5px;">
                    Corrige inconsistencias: Stock Actual = Stock Inicial - Total Reducido
                </p>
            </div>

            <h3>Estado del Stock</h3>
            <div id="stockList">
                <!-- Stock items will be populated here -->
            </div>
        </div>
        <!-- Income Tab -->
        <div id="income" class="tab-content">
            <h2>Registrar Venta</h2>

            <!-- Datos del Cliente y Venta -->
            <div style="background: #e3f2fd; padding: 20px; border-radius: 10px; margin-bottom: 20px;">
                <h3>📋 Datos de la Venta</h3>
                <form id="saleDataForm">
                    <div class="form-row">
                        <div class="form-group">
                            <label>Fecha:</label>
                            <input type="date" id="saleDate" required>
                        </div>
                        <div class="form-group">
                            <label>Cliente:</label>
                            <input type="text" id="saleCustomer" placeholder="Nombre del cliente" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Método de Pago:</label>
                            <div class="payment-method">
                                <input type="radio" id="salePaymentCash" name="salePaymentMethod" value="cash" required>
                                <label for="salePaymentCash">💵 Efectivo</label>
                                <input type="radio" id="salePaymentDigital" name="salePaymentMethod" value="digital" required>
                                <label for="salePaymentDigital">💳 Transferencia/Tarjeta</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Notas de la venta:</label>
                            <input type="text" id="saleNotes" placeholder="Notas generales de la venta">
                        </div>
                    </div>
                    <div class="form-group" id="creditDateGroup" style="display: none;">
                        <label>Fecha de Acreditación:</label>
                        <input type="date" id="saleCreditDate" min="" max="">
                        <small style="color: #666;">Solo para pagos digitales</small>
                    </div>
                </form>
            </div>

            <!-- Agregar Productos a la Venta -->
            <div style="background: #f8f9fa; padding: 20px; border-radius: 10px; margin-bottom: 20px;">
                <h3>🛒 Agregar Productos a esta Venta</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label>Producto:</label>
                        <select id="productSelectMulti">
                            <option value="">Seleccionar producto...</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Cantidad:</label>
                        <input type="number" id="quantityMulti" step="0.01" placeholder="0.00">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Precio Unitario:</label>
                        <input type="number" id="pricePerUnitMulti" step="0.01" placeholder="0.00">
                    </div>
                    <div class="form-group">
                        <label>Total del Producto:</label>
                        <input type="number" id="productTotalMulti" step="0.01" placeholder="0.00" readonly>
                    </div>
                </div>
                <button type="button" class="btn btn-secondary" onclick="addProductToSale()">➕ Agregar Producto a la Venta</button>
            </div>

            <!-- Lista de Productos en la Venta Actual -->
            <div id="saleProductsList" style="display: none;">
                <h3>📦 Productos en esta Venta</h3>
                <div id="saleProductsContainer">
                    <!-- Los productos se agregarán aquí -->
                </div>
                <div style="background: #4CAF50; color: white; padding: 20px; border-radius: 10px; text-align: center; margin-top: 20px;">
                    <h3>Total de la Venta: $<span id="saleGrandTotal">0</span></h3>
                    <button type="button" class="btn" style="background: white; color: #4CAF50; margin-top: 10px;" onclick="completeSale()">
                        💰 Completar Venta
                    </button>
                    <button type="button" class="btn btn-danger" style="margin-top: 10px; margin-left: 10px;" onclick="cancelSale()">
                        ❌ Cancelar Venta
                    </button>
                </div>
            </div>

            <!-- Filtros de Análisis de Ingresos -->
            <div class="filter-section" style="background: #e8f5e8; border-left: 4px solid #4CAF50; margin-bottom: 20px;">
                <h3>🔍 Filtros de Análisis de Ingresos</h3>
                <div class="filter-row">
                    <div class="form-group">
                        <label>Período:</label>
                        <input type="date" id="incomeFilterDateFrom" onchange="filterIncomes()">
                    </div>
                    <div class="form-group">
                        <label>Hasta:</label>
                        <input type="date" id="incomeFilterDateTo" onchange="filterIncomes()">
                    </div>
                    <div class="form-group">
                        <label>Método de Pago:</label>
                        <select id="incomeFilterPayment" onchange="filterIncomes()">
                            <option value="">Todos</option>
                            <option value="cash">💵 Solo Efectivo</option>
                            <option value="digital">💳 Solo Digital</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Producto:</label>
                        <select id="incomeFilterProduct" onchange="filterIncomes()">
                            <option value="">Todos los productos</option>
                        </select>
                    </div>
                </div>
                <div class="filter-row">
                    <div class="form-group">
                        <label>Cliente:</label>
                        <input type="text" id="incomeFilterCustomer" placeholder="Buscar cliente..." oninput="filterIncomes()">
                    </div>
                    <div class="form-group">
                        <label>Categoría:</label>
                        <select id="incomeFilterCategory" onchange="filterIncomes()">
                            <option value="">Todas</option>
                            <option value="Ventas">Ventas</option>
                            <option value="Aportes de Capital">Aportes de Capital</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-secondary" onclick="clearIncomeFilters()">🔄 Limpiar Filtros</button>
                    </div>
                    <div class="form-group">
                        <button class="btn" onclick="exportIncomeData()">📊 Exportar Filtrados</button>
                    </div>
                </div>
                
                <!-- Resumen de Filtros -->
                <div id="incomeFilterSummary" style="background: white; padding: 15px; border-radius: 8px; margin-top: 15px; display: none;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <strong>📊 Resultados:</strong> 
                            <span id="incomeFilterCount">0</span> transacciones encontradas
                        </div>
                        <div style="font-size: 1.5em; font-weight: bold; color: #4CAF50;">
                            Total: <span id="incomeFilterTotal">$0</span>
                        </div>
                    </div>
                    <div style="margin-top: 10px; font-size: 14px; color: #666;">
                        <span id="incomeFilterDetails"></span>
                    </div>
                </div>
            </div>

            <h3 style="margin-top: 40px;">Historial de Ingresos</h3>
            <table class="transactions-table" id="incomeHistoryTable">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Cliente</th>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio Unit.</th>
                        <th>Total</th>
                        <th>Pago</th>
                        <th style="text-align: center;">Facturado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="incomeHistoryBody">
                    <!-- Income history will be populated here -->
                </tbody>
            </table>
        </div>
        <!-- Expenses Tab -->
        <div id="expenses" class="tab-content">
            <h2>Registrar Gasto</h2>
            <form id="expenseForm">
                <div class="form-row">
                    <div class="form-group">
                        <label>Fecha:</label>
                        <input type="date" id="expenseDate" required>
                    </div>
                    <div class="form-group">
                        <label>Categoría:</label>
                        <select id="expenseCategory" required>
                            <option value="">Seleccionar...</option>
                            <option value="Alquiler de Depósito">Alquiler de Depósito</option>
                            <option value="Compra de Mercadería">Compra de Mercadería</option>
                            <option value="Transporte">Transporte</option>
                            <option value="Servicios">Servicios (luz, gas, etc.)</option>
                            <option value="Marketing">Marketing y Publicidad</option>
                            <option value="Impuestos">Impuestos</option>
                            <option value="Herramientas">Herramientas y Equipos</option>
                            <option value="Otros">Otros Gastos</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Descripción:</label>
                        <input type="text" id="expenseDescription" placeholder="Descripción del gasto" required>
                    </div>
                    <div class="form-group">
                        <label>Monto:</label>
                        <input type="number" id="expenseAmount" step="0.01" placeholder="0.00" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Tipo de Gasto:</label>
                        <div class="payment-method">
                            <input type="radio" id="expenseFixed" name="expenseType" value="fixed" required>
                            <label for="expenseFixed">🔒 Fijo</label>
                            <input type="radio" id="expenseVariable" name="expenseType" value="variable" required>
                            <label for="expenseVariable">📈 Variable</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Método de Pago:</label>
                        <div class="payment-method">
                            <input type="radio" id="expensePaymentCash" name="expensePaymentMethod" value="cash" required>
                            <label for="expensePaymentCash">💵 Efectivo</label>
                            <input type="radio" id="expensePaymentDigital" name="expensePaymentMethod" value="digital" required>
                            <label for="expensePaymentDigital">💳 Transferencia/Tarjeta</label>
                        </div>
                    </div>
                </div>
                <div class="form-group" id="expenseCreditDateGroup" style="display: none;">
                    <label>Fecha de Débito:</label>
                    <input type="date" id="expenseCreditDate" min="" max="">
                    <small style="color: #666;">Solo para pagos digitales</small>
                </div>
                <div class="form-group">
                    <label>Notas adicionales:</label>
                    <textarea id="expenseNotes" rows="3" placeholder="Detalles adicionales del gasto..."></textarea>
                </div>
                <button type="submit" class="btn">💸 Registrar Gasto</button>
            </form>

            <!-- Filtros de Análisis de Gastos -->
            <div class="filter-section" style="background: #ffeee8; border-left: 4px solid #ff6b35; margin-bottom: 20px;">
                <h3>🔍 Filtros de Análisis de Gastos</h3>
                <div class="filter-row">
                    <div class="form-group">
                        <label>Período:</label>
                        <input type="date" id="expenseFilterDateFrom" onchange="filterExpenses()">
                    </div>
                    <div class="form-group">
                        <label>Hasta:</label>
                        <input type="date" id="expenseFilterDateTo" onchange="filterExpenses()">
                    </div>
                    <div class="form-group">
                        <label>Método de Pago:</label>
                        <select id="expenseFilterPayment" onchange="filterExpenses()">
                            <option value="">Todos</option>
                            <option value="cash">💵 Solo Efectivo</option>
                            <option value="digital">💳 Solo Digital</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Tipo de Gasto:</label>
                        <select id="expenseFilterType" onchange="filterExpenses()">
                            <option value="">Todos</option>
                            <option value="fixed">🔒 Solo Fijos</option>
                            <option value="variable">📈 Solo Variables</option>
                        </select>
                    </div>
                </div>
                <div class="filter-row">
                    <div class="form-group">
                        <label>Categoría:</label>
                        <select id="expenseFilterCategory" onchange="filterExpenses()">
                            <option value="">Todas las categorías</option>
                            <option value="Alquiler de Depósito">Alquiler de Depósito</option>
                            <option value="Compra de Mercadería">Compra de Mercadería</option>
                            <option value="Transporte">Transporte</option>
                            <option value="Servicios">Servicios</option>
                            <option value="Marketing">Marketing</option>
                            <option value="Impuestos">Impuestos</option>
                            <option value="Herramientas">Herramientas</option>
                            <option value="Otros">Otros</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Buscar:</label>
                        <input type="text" id="expenseFilterSearch" placeholder="Buscar en descripción..." oninput="filterExpenses()">
                    </div>
                    <div class="form-group">
                        <button class="btn btn-secondary" onclick="clearExpenseFilters()">🔄 Limpiar Filtros</button>
                    </div>
                    <div class="form-group">
                        <button class="btn" onclick="exportExpenseData()">📊 Exportar Filtrados</button>
                    </div>
                </div>
                
                <!-- Resumen de Filtros -->
                <div id="expenseFilterSummary" style="background: white; padding: 15px; border-radius: 8px; margin-top: 15px; display: none;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <strong>📊 Resultados:</strong> 
                            <span id="expenseFilterCount">0</span> transacciones encontradas
                        </div>
                        <div style="font-size: 1.5em; font-weight: bold; color: #f44336;">
                            Total: <span id="expenseFilterTotal">$0</span>
                        </div>
                    </div>
                    <div style="margin-top: 10px; font-size: 14px; color: #666;">
                        <span id="expenseFilterDetails"></span>
                    </div>
                </div>
            </div>

            <h3 style="margin-top: 40px;">Historial de Gastos</h3>
            <table class="transactions-table" id="expenseHistoryTable">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Categoría</th>
                        <th>Descripción</th>
                        <th>Monto</th>
                        <th>Tipo</th>
                        <th>Pago</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="expenseHistoryBody">
                    <!-- Expense history will be populated here -->
                </tbody>
            </table>
        </div>

        <!-- Balances Tab -->
        <div id="balances" class="tab-content">
            <h2>Saldos Disponibles</h2>
            
            <div class="summary-cards">
                <div class="card cash">
                    <h3>💵 Saldo en Efectivo</h3>
                    <div class="amount" id="cashBalanceDetail">$0</div>
                    <p style="margin-top: 10px; opacity: 0.8;">Ingresos cash - Gastos cash</p>
                </div>
                <div class="card digital">
                    <h3>💳 Saldo en Bancos</h3>
                    <div class="amount" id="digitalBalanceDetail">$0</div>
                    <p style="margin-top: 10px; opacity: 0.8;">Ingresos digitales - Gastos digitales</p>
                </div>
                <div class="card">
                    <h3>💰 Saldo Total</h3>
                    <div class="amount" id="totalBalanceDetail">$0</div>
                    <p style="margin-top: 10px; opacity: 0.8;">Cash + Bancos</p>
                </div>
            </div>

            <div class="filter-section">
                <h3>Movimientos por Método de Pago</h3>
                <div class="filter-row">
                    <div class="form-group">
                        <label>Período desde:</label>
                        <input type="date" id="balanceDateFrom" onchange="updateBalances()">
                    </div>
                    <div class="form-group">
                        <label>Período hasta:</label>
                        <input type="date" id="balanceDateTo" onchange="updateBalances()">
                    </div>
                    <div class="form-group">
                        <button class="btn" onclick="resetBalanceFilter()">Ver Todo</button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Quotes Tab ACTUALIZADO -->
        <div id="quotes" class="tab-content">
            <h2>Crear Presupuesto</h2>
            
            <form id="quoteForm">
                <div class="form-row">
                    <div class="form-group">
                        <label>Cliente:</label>
                        <input type="text" id="quoteCustomer" placeholder="Nombre del cliente" required>
                    </div>
                    <div class="form-group">
                        <label>Fecha:</label>
                        <input type="date" id="quoteDate" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Teléfono:</label>
                        <input type="tel" id="quotePhone" placeholder="Teléfono del cliente">
                    </div>
                    <div class="form-group">
                        <label>Email:</label>
                        <input type="email" id="quoteEmail" placeholder="Email del cliente">
                    </div>
                </div>
                <div class="form-group">
                    <label>Dirección:</label>
                    <input type="text" id="quoteAddress" placeholder="Dirección del cliente">
                </div>
            </form>

            <h3>Productos del Presupuesto</h3>
            <div class="form-row">
                <div class="form-group">
                    <label>Producto:</label>
                    <select id="quoteProductSelect">
                        <option value="">Seleccionar producto...</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Cantidad:</label>
                    <input type="number" id="quoteQuantity" step="0.01" placeholder="0.00">
                </div>
            </div>
            <button type="button" class="btn-secondary btn" onclick="addQuoteItem()">➕ Agregar Producto</button>

            <!-- NUEVA SECCIÓN DE DESCUENTOS -->
            <div style="background: #f0f8ff; padding: 20px; border-radius: 10px; margin: 20px 0; border-left: 4px solid #007bff;">
                <h3>🏷️ Aplicar Descuento (Opcional)</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label>Porcentaje de descuento:</label>
                        <input type="number" id="quoteDiscountPercent" min="0" max="100" step="0.1" placeholder="0.0">
                        <small style="color: #666; display: block; margin-top: 5px;">
                            Ingresa un porcentaje entre 0 y 100 (ejemplo: 15.5 para 15.5%)
                        </small>
                    </div>
                    <div class="form-group" style="display: flex; align-items: end; gap: 10px;">
                        <button type="button" class="btn" onclick="addQuoteDiscount()" 
                                style="background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);">
                            🏷️ Aplicar Descuento
                        </button>
                    </div>
                </div>
                <div style="font-size: 12px; color: #666; margin-top: 10px;">
                    💡 <strong>Tip:</strong> El descuento se aplicará sobre el subtotal de todos los productos y aparecerá como una línea separada en el presupuesto.
                </div>
            </div>

            <div id="quoteItems" style="margin-top: 20px;">
                <!-- Quote items will be populated here -->
            </div>

            <div class="quote-total" id="quoteTotal" style="display: none;">
                <h3>Total Final: $<span id="quoteTotalAmount">0</span></h3>
                <div style="display: flex; gap: 15px; justify-content: center; margin-top: 20px;">
                    <button type="button" class="btn" onclick="generateRevestikaQuote()" 
                            style="background: linear-gradient(135deg, #B8860B 0%, #DAA520 100%); flex: 1;">
                        👁️ Vista Previa del Presupuesto
                    </button>
                    <button type="button" class="btn" onclick="downloadRevestikaPDF()" 
                            style="background: linear-gradient(135deg, #8e44ad 0%, #9b59b6 100%); flex: 1;">
                        📄 Descargar PDF
                    </button>
                </div>
                <p style="text-align: center; margin-top: 10px; font-size: 12px; color: #666;">
                    💡 La vista previa se abre en una nueva ventana para imprimir o guardar como PDF
                </p>
            </div>
        </div>

        <!-- Investment Tab -->
        <div id="investment" class="tab-content">
            <h2>Inversión Inicial del Negocio</h2>
    
            <div class="investment-summary">
                <h3>Total Inversión Inicial</h3>
                <div class="amount" id="totalInvestment">$0</div>
                <p style="opacity: 0.8;">Capital invertido para iniciar el negocio</p>
            </div>

            <form id="investmentForm">
                <div class="form-row">
                    <div class="form-group">
                        <label>Fecha de Inversión:</label>
                        <input type="date" id="investmentDate" required>
                    </div>
                    <div class="form-group">
                        <label>Categoría de Inversión:</label>
                        <select id="investmentCategory" required onchange="togglePaymentMethodForInvestment()">
                            <option value="">Seleccionar...</option>
                            <option value="Aporte de Socios">Aporte de Socios</option>
                            <option value="Stock Inicial">Stock Inicial de Productos</option>
                            <option value="Herramientas">Herramientas y Equipos</option>
                            <option value="Depósito">Depósito y Acondicionamiento</option>
                            <option value="Vehículo">Vehículo/Transporte</option>
                            <option value="Marketing">Marketing Inicial</option>
                            <option value="Trámites">Trámites y Habilitaciones</option>
                            <option value="Capital de Trabajo">Capital de Trabajo</option>
                            <option value="Otros">Otros</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Descripción:</label>
                        <input type="text" id="investmentDescription" placeholder="Detalle de la inversión" required>
                    </div>
                    <div class="form-group">
                        <label>Monto:</label>
                        <input type="number" id="investmentAmount" step="0.01" placeholder="0.00" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Notas adicionales:</label>
                    <textarea id="investmentNotes" rows="3" placeholder="Detalles adicionales de la inversión..."></textarea>
                </div>

                <!-- Selector para aportes de socios -->
                <div class="form-group" id="investmentPaymentMethodGroup" style="display: none;">
                    <label>Método de Aporte (solo para Aportes de Socios):</label>
                    <div class="payment-method">
                        <input type="radio" id="investmentPaymentCash" name="investmentPaymentMethod" value="cash">
                        <label for="investmentPaymentCash">💵 Efectivo</label>
                        <input type="radio" id="investmentPaymentDigital" name="investmentPaymentMethod" value="digital">
                        <label for="investmentPaymentDigital">💳 Transferencia/Depósito</label>
                    </div>
                </div>

                <button type="submit" class="btn">💼 Registrar Inversión</button>
            </form>

            <div class="roi-analysis">
                <div class="roi-card">
                    <h4>Ganancia Neta Acumulada</h4>
                    <div class="value" id="totalProfit">$0</div>
                </div>
                <div class="roi-card">
                    <h4>ROI %</h4>
                    <div class="value" id="roiPercentage">0%</div>
                </div>
                <div class="roi-card">
                    <h4>Tiempo para Recuperar</h4>
                    <div class="value" id="paybackTime">-</div>
                </div>
                <div class="roi-card">
                    <h4>Rentabilidad Mensual</h4>
                    <div class="value" id="monthlyReturn">$0</div>
                </div>
            </div>

            <h3 style="margin-top: 40px;">Detalle de Inversiones</h3>
            <table class="transactions-table">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Categoría</th>
                        <th>Descripción</th>
                        <th>Monto</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="investmentTableBody">
                    <!-- Investment items will be populated here -->
                </tbody>
            </table>
        </div>

        <!-- History Tab -->
        <div id="history" class="tab-content">
            <h2>Historial General de Transacciones (Solo Lectura)</h2>
            
            <div class="filter-section">
                <div class="filter-row">
                    <div class="form-group">
                        <label>Filtrar por tipo:</label>
                        <select id="typeFilter" onchange="filterTransactions()">
                            <option value="">Todos</option>
                            <option value="income">Solo Ingresos</option>
                            <option value="expense">Solo Gastos</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Filtrar por categoría:</label>
                        <select id="categoryFilter" onchange="filterTransactions()">
                            <option value="">Todas las categorías</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Buscar:</label>
                        <input type="text" id="searchFilter" placeholder="Buscar..." oninput="filterTransactions()">
                    </div>
                </div>
            </div>

            <div style="background: #e3f2fd; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                <p><strong>ℹ️ Información:</strong> Este es un historial de solo lectura. Para editar o eliminar transacciones, ve a la pestaña correspondiente (Ingresos, Gastos o Inversión).</p>
            </div>

            <table class="transactions-table" id="transactionsTable">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Tipo</th>
                        <th>Descripción</th>
                        <th>Categoría</th>
                        <th>Pago</th>
                        <th>Tipo Gasto</th>
                        <th>Monto</th>
                    </tr>
                </thead>
                <tbody id="transactionsBody">
                    <!-- Transactions will be populated here -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Taxes and Fixed Expenses Tab -->
    <div id="taxes" class="tab-content">
        <h2>🧾 Impuestos y Gastos Fijos</h2>

        <!-- Selector de período -->
        <div class="filter-section" style="margin-bottom: 30px;">
            <h3>🗓️ Seleccionar Período</h3>
            <div class="filter-row">
                <div class="form-group">
                    <label>Año:</label>
                    <select id="taxYear" onchange="updateTaxesDisplay()">
                        <!-- Se populará con JavaScript -->
                    </select>
                </div>
                <div class="form-group">
                    <label>Mes:</label>
                    <select id="taxMonth" onchange="updateTaxesDisplay()">
                        <option value="1">Enero</option>
                        <option value="2">Febrero</option>
                        <option value="3">Marzo</option>
                        <option value="4">Abril</option>
                        <option value="5">Mayo</option>
                        <option value="6">Junio</option>
                        <option value="7">Julio</option>
                        <option value="8">Agosto</option>
                        <option value="9">Septiembre</option>
                        <option value="10">Octubre</option>
                        <option value="11">Noviembre</option>
                        <option value="12">Diciembre</option>
                    </select>
                </div>
                <div class="form-group">
                    <button class="btn" onclick="goToCurrentPeriod()">📅 Ir al Período Actual</button>
                </div>
            </div>
        </div>

        <!-- Checklist del período seleccionado -->
        <div style="background: #f8f9fa; padding: 20px; border-radius: 10px;">
            <h3>📋 Checklist del Período</h3>
            
            <!-- Impuestos del período -->
            <div style="margin-bottom: 30px;">
                <h4 style="color: #e74c3c; border-bottom: 2px solid #e74c3c; padding-bottom: 5px;">🏛️ Impuestos</h4>
                <div id="taxChecklistItems">
                    <!-- Se populará con JavaScript -->
                </div>
            </div>

            <!-- Gastos fijos del período -->
            <div>
                <h4 style="color: #27ae60; border-bottom: 2px solid #27ae60; padding-bottom: 5px;">🔒 Gastos Fijos</h4>
                <div id="fixedExpenseChecklistItems">
                    <!-- Se populará con JavaScript -->
                </div>
            </div>
        </div>

        <!-- Gestión de Impuestos -->
        <div style="background: #ffebee; padding: 20px; border-radius: 10px; margin-bottom: 30px;">
            <h3>🏛️ Gestión de Impuestos</h3>
            
            <!-- Formulario para agregar nuevo impuesto -->
            <form id="taxItemForm" style="margin-bottom: 20px;">
                <div class="form-row">
                    <div class="form-group">
                        <label>Nombre del Impuesto:</label>
                        <input type="text" id="taxName" placeholder="Ej: IVA, Ganancias, Ingresos Brutos" required>
                    </div>
                    <div class="form-group">
                        <label>Frecuencia:</label>
                        <select id="taxFrequency" required>
                            <option value="">Seleccionar...</option>
                            <option value="monthly">📅 Mensual</option>
                            <option value="annual">📆 Anual</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Monto Estimado (opcional):</label>
                        <input type="number" id="taxAmount" step="0.01" placeholder="0.00">
                    </div>
                    <div class="form-group">
                        <label>Descripción:</label>
                        <input type="text" id="taxDescription" placeholder="Descripción del impuesto">
                    </div>
                </div>
                <button type="submit" class="btn">🏛️ Agregar Impuesto</button>
            </form>

            <!-- Lista de impuestos configurados -->
            <h4>Impuestos Configurados:</h4>
            <div id="taxItemsList">
                <!-- Se populará con JavaScript -->
            </div>
        </div>

        <!-- Gestión de Gastos Fijos -->
        <div style="background: #e8f5e8; padding: 20px; border-radius: 10px;">
            <h3>🔒 Gestión de Gastos Fijos</h3>
            
            <!-- Formulario para agregar nuevo gasto fijo -->
            <form id="fixedExpenseItemForm" style="margin-bottom: 20px;">
                <div class="form-row">
                    <div class="form-group">
                        <label>Nombre del Gasto Fijo:</label>
                        <input type="text" id="fixedExpenseName" placeholder="Ej: Alquiler, Seguro, Servicios" required>
                    </div>
                    <div class="form-group">
                        <label>Frecuencia:</label>
                        <select id="fixedExpenseFrequency" required>
                            <option value="">Seleccionar...</option>
                            <option value="monthly">📅 Mensual</option>
                            <option value="annual">📆 Anual</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Monto Estimado (opcional):</label>
                        <input type="number" id="fixedExpenseAmount" step="0.01" placeholder="0.00">
                    </div>
                    <div class="form-group">
                        <label>Descripción:</label>
                        <input type="text" id="fixedExpenseDescription" placeholder="Descripción del gasto fijo">
                    </div>
                </div>
                <button type="submit" class="btn">🔒 Agregar Gasto Fijo</button>
            </form>

            <!-- Lista de gastos fijos configurados -->
            <h4>Gastos Fijos Configurados:</h4>
            <div id="fixedExpenseItemsList">
                <!-- Se populará con JavaScript -->
            </div>
        </div>
    </div>

    <!-- Modal para editar productos -->
    <div id="editProductModal" class="edit-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>✏️ Editar Producto</h2>
                <button class="close" onclick="closeEditModal()">&times;</button>
            </div>
            <form id="editProductForm">
                <input type="hidden" id="editProductId">
                <div class="form-row">
                    <div class="form-group">
                        <label>Nombre del Producto:</label>
                        <input type="text" id="editProductName" required>
                    </div>
                    <div class="form-group">
                        <label>Categoría:</label>
                        <select id="editProductCategory" required>
                            <option value="">Seleccionar...</option>
                        </select>
                    </div>
                </div>
                <div class="form-row-3">
                    <div class="form-group">
                        <label>Dimensiones:</label>
                        <input type="text" id="editProductDimensions" required>
                    </div>
                    <div class="form-group">
                        <label>Precio de Venta:</label>
                        <input type="number" id="editProductPrice" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label>Unidad:</label>
                        <select id="editProductUnit" required>
                            <option value="unidad">Unidad</option>
                            <option value="m²">m²</option>
                            <option value="ml">ml</option>
                            <option value="kg">kg</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label>Descripción adicional:</label>
                    <textarea id="editProductDescription" rows="2"></textarea>
                </div>
                <div style="display: flex; gap: 10px; justify-content: flex-end;">
                    <button type="button" class="btn btn-secondary" onclick="closeEditModal()">Cancelar</button>
                    <button type="submit" class="btn">💾 Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // ======== VARIABLES GLOBALES - DECLARADAS PRIMERO ========
        let transactions = [];
        let products = [];
        let categories = [];
        let stockData = {};
        let investments = [];
        let filteredTransactions = [];
        let filteredIncomes = [];
        let filteredExpenses = [];
        let quoteItems = [];
        let currentQuoteHTML = '';
        let currentSaleProducts = [];
        let taxFixedItems = [];
        let taxFixedPayments = [];
        let currentTaxYear = new Date().getFullYear();
        let currentTaxMonth = new Date().getMonth() + 1;
        let quoteDiscount = 0;
        let currentCurrency = 'ARS';
        let exchangeRates = {
            current: null,
            historical: {}
        };

        // Constantes de tipos de cambio fijos para aportes específicos
        const FIXED_EXCHANGE_RATES = {
            '1er pago costa libre': 1135,
            '2do pago costa libre': 1165
        };

        // ======== FUNCIONES PRINCIPALES ========
        function showTab(tabName) {
            console.log('Mostrando pestaña:', tabName);
            
            try {
                // Ocultar todas las pestañas
                const tabs = document.querySelectorAll('.tab-content');
                tabs.forEach(tab => tab.classList.remove('active'));
                
                // Quitar clase active de todos los botones de navegación
                const navTabs = document.querySelectorAll('.nav-tab');
                navTabs.forEach(tab => tab.classList.remove('active'));
                
                // Mostrar la pestaña seleccionada
                const targetTab = document.getElementById(tabName);
                if (targetTab) {
                    targetTab.classList.add('active');
                } else {
                    console.error('Pestaña no encontrada:', tabName);
                    return;
                }
                
                // Activar el botón de navegación correspondiente
                const targetButton = document.querySelector(`[onclick*="showTab('${tabName}')"]`);
                if (targetButton) {
                    targetButton.classList.add('active');
                }
                
                // Actualizar contenido específico según la pestaña
                setTimeout(() => {
                    try {
                        if (tabName === 'dashboard') {
                            if (typeof updateDashboard === 'function') updateDashboard();
                        } else if (tabName === 'history') {
                            if (typeof updateTransactionsTable === 'function') updateTransactionsTable();
                        } else if (tabName === 'products') {
                            if (typeof updateProductsList === 'function') updateProductsList();
                            if (typeof updateCategoryList === 'function') updateCategoryList();
                        } else if (tabName === 'stock') {
                            if (typeof updateStockDisplay === 'function') updateStockDisplay();
                            if (typeof updateStockProductSelect === 'function') updateStockProductSelect();
                        } else if (tabName === 'balances') {
                            if (typeof updateBalances === 'function') updateBalances();
                        } else if (tabName === 'quotes') {
                            if (typeof updateProductSelects === 'function') updateProductSelects();
                        } else if (tabName === 'investment') {
                            if (typeof updateInvestmentDisplay === 'function') updateInvestmentDisplay();
                        } else if (tabName === 'income') {
                            if (typeof updateIncomeHistory === 'function') updateIncomeHistory();
                            if (typeof initializeFilters === 'function') initializeFilters();
                        } else if (tabName === 'expenses') {
                            if (typeof updateExpenseHistory === 'function') updateExpenseHistory();
                            if (typeof initializeFilters === 'function') initializeFilters();
                        } else if (tabName === 'taxes') {
                            if (typeof initializeTaxesTab === 'function') initializeTaxesTab();
                        }
                    } catch (error) {
                        console.error('Error actualizando contenido de pestaña:', tabName, error);
                    }
                }, 100);
                
            } catch (error) {
                console.error('Error mostrando pestaña:', error);
            }
        }

        function changeCurrency(currency) {
            console.log('💱 Cambiando moneda a:', currency);
            
            try {
                currentCurrency = currency;
                
                // Actualizar botones
                const arsBtn = document.getElementById('currencyARS');
                const usdBtn = document.getElementById('currencyUSD');
                
                if (arsBtn && usdBtn) {
                    if (currency === 'ARS') {
                        arsBtn.classList.add('active');
                        usdBtn.classList.remove('active');
                    } else {
                        arsBtn.classList.remove('active');
                        usdBtn.classList.add('active');
                    }
                }
                
                // Actualizar displays
                setTimeout(() => {
                    if (typeof updateDashboard === 'function') updateDashboard();
                    if (typeof updateBalances === 'function') updateBalances();
                }, 100);
                
                console.log('✅ Moneda cambiada a:', currency);
                
            } catch (error) {
                console.error('Error cambiando moneda:', error);
            }
        }

        // ======== HACER FUNCIONES DISPONIBLES GLOBALMENTE ========
        window.showTab = showTab;
        window.changeCurrency = changeCurrency;

        // ======== DEBUG INICIAL ========
        console.log('✅ Variables y funciones inicializadas correctamente');
        console.log('✅ Variables definidas:', {
            transactions: Array.isArray(transactions),
            products: Array.isArray(products),
            currentCurrency: currentCurrency
        });

        // ======== FUNCIONES AUXILIARES CRÍTICAS ========
        function formatNumberForDisplay(number) {
            const num = parseFloat(number);
            if (isNaN(num)) return '0';
            return Math.round(num).toLocaleString('es-AR', { useGrouping: false });
        }

        function escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // ======== FUNCIONES DE CONVERSIÓN DE MONEDA ========
        
        // Función para inicializar el sistema de moneda
        function initializeCurrencySystem() {
            try {
                // Cargar preferencia guardada
                const savedCurrency = localStorage.getItem('preferredCurrency');
                if (savedCurrency && (savedCurrency === 'ARS' || savedCurrency === 'USD')) {
                    currentCurrency = savedCurrency;
                }
                updateCurrencyButtons();
                console.log('Sistema de moneda inicializado:', currentCurrency);
            } catch (e) {
                console.warn('Error cargando preferencia de moneda:', e);
                currentCurrency = 'ARS';
            }
        }

        // Función para obtener el tipo de cambio actual desde API
        async function getCurrentExchangeRate() {
            try {
                if (exchangeRates.current) {
                    return exchangeRates.current;
                }

                // Intentar obtener desde API pública
                const response = await fetch('https://api.bluelytics.com.ar/v2/latest');
                if (response.ok) {
                    const data = await response.json();
                    const rate = data.blue?.value_avg || data.oficial?.value_avg || 1200;
                    exchangeRates.current = rate;
                    console.log('Tipo de cambio obtenido de API:', rate);
                    return rate;
                }
            } catch (error) {
                console.warn('Error obteniendo tipo de cambio de API:', error);
            }

            // Fallback: usar un valor estimado
            const fallbackRate = 1200;
            exchangeRates.current = fallbackRate;
            console.log('Usando tipo de cambio fallback:', fallbackRate);
            return fallbackRate;
        }

        // Función para detectar aportes específicos con tipos de cambio fijos
        function getFixedExchangeRate(description, amount = null) {
            if (!description) return null;
            
            const desc = description.toLowerCase();
            
            // Buscar por descripción
            for (const [key, rate] of Object.entries(FIXED_EXCHANGE_RATES)) {
                if (desc.includes(key.toLowerCase())) {
                    console.log(`Usando tipo de cambio fijo para "${key}":`, rate);
                    return rate;
                }
            }
            
            // Buscar por monto específico para mayor precisión
            if (amount) {
                const numAmount = parseFloat(amount);
                if (numAmount === 11650000) {
                    console.log('Detectado 1er pago costa libre por monto:', 1135);
                    return 1135;
                } else if (numAmount === 10151292) {
                    console.log('Detectado 2do pago costa libre por monto:', 1165);
                    return 1165;
                }
            }
            
            return null;
        }

        // Función para obtener tipo de cambio histórico
        async function getHistoricalExchangeRate(date) {
            try {
                if (exchangeRates.historical[date]) {
                    return exchangeRates.historical[date];
                }
                
                const targetDate = new Date(date);
                const now = new Date();
                
                if (!exchangeRates.current) {
                    await getCurrentExchangeRate();
                }
                
                const daysDiff = (now - targetDate) / (1000 * 60 * 60 * 24);
                const yearsDiff = daysDiff / 365;
                
                let estimatedRate;
                
                if (yearsDiff <= 0) {
                    estimatedRate = exchangeRates.current;
                } else if (yearsDiff <= 0.5) {
                    estimatedRate = exchangeRates.current / Math.pow(1.03, yearsDiff * 12);
                } else if (yearsDiff <= 1) {
                    estimatedRate = exchangeRates.current * 0.7;
                } else if (yearsDiff <= 2) {
                    estimatedRate = exchangeRates.current * 0.4;
                } else {
                    estimatedRate = exchangeRates.current * Math.pow(0.5, yearsDiff - 1);
                }
                
                estimatedRate = Math.round(estimatedRate * 100) / 100;
                exchangeRates.historical[date] = estimatedRate;
                
                return estimatedRate;
                
            } catch (error) {
                console.error('Error obteniendo tipo de cambio histórico:', error);
                
                const year = new Date(date).getFullYear();
                let fallbackRate;
                
                if (year >= 2025) fallbackRate = 1200;
                else if (year >= 2024) fallbackRate = 900;
                else if (year >= 2023) fallbackRate = 500;
                else if (year >= 2022) fallbackRate = 200;
                else fallbackRate = 100;
                
                exchangeRates.historical[date] = fallbackRate;
                return fallbackRate;
            }
        }
        // ======== FUNCIÓN DE CONVERSIÓN CORREGIDA ========
        async function convertAmountWithCriteria(amount, date = null, description = '', isBalance = false) {
            // ⭐ CRÍTICO: Si está en ARS, devolver valor original sin conversión ⭐
            if (currentCurrency === 'ARS') {
                return parseFloat(amount) || 0;
            }
            
            const numAmount = parseFloat(amount);
            if (isNaN(numAmount)) {
                return 0;
            }
            
            try {
                let exchangeRate;
                
                // REGLA 1: Verificar si es un aporte inicial específico
                const fixedRate = getFixedExchangeRate(description, numAmount);
                if (fixedRate) {
                    console.log('Aplicando REGLA 1 - Tipo fijo:', fixedRate);
                    return numAmount / fixedRate;
                }
                
                // REGLA 3: Para saldos usar tipo de cambio del día
                if (isBalance) {
                    if (!exchangeRates.current) {
                        await getCurrentExchangeRate();
                    }
                    exchangeRate = exchangeRates.current;
                    console.log('Aplicando REGLA 3 - Saldo con TC actual:', exchangeRate);
                } else {
                    // REGLA 2: Para otros aportes, ingresos y gastos usar tipo de cambio histórico
                    if (date) {
                        exchangeRate = await getHistoricalExchangeRate(date);
                        console.log('Aplicando REGLA 2 - TC histórico para', date, ':', exchangeRate);
                    } else {
                        if (!exchangeRates.current) {
                            await getCurrentExchangeRate();
                        }
                        exchangeRate = exchangeRates.current;
                        console.log('Aplicando REGLA 2 - TC actual (sin fecha):', exchangeRate);
                    }
                }
                
                const convertedAmount = numAmount / exchangeRate;
                return convertedAmount;
                
            } catch (error) {
                console.error('Error convirtiendo monto:', error);
                return numAmount / 1200; // Fallback conservador
            }
        }

        // Función de formateo de moneda síncrona (para mostrar)
        function formatCurrencySync(amount) {
            const numAmount = parseFloat(amount);
            if (isNaN(numAmount)) {
                return currentCurrency === 'USD' ? 'USD 0' : '$0';
            }
            
            if (currentCurrency === 'USD') {
                return 'USD ' + numAmount.toFixed(2);
            } else {
                return '$' + Math.round(numAmount).toLocaleString('es-AR', { useGrouping: false });
            }
        }

        // Función tradicional de formateo (mantenida para compatibilidad)
        function formatCurrency(amount) {
            const numAmount = parseFloat(amount);
            if (isNaN(numAmount)) {
                return '$0';
            }
            return '$' + Math.round(numAmount).toLocaleString('es-AR', {
                useGrouping: false
            });
        }

        // Función para cambiar moneda
        async function changeCurrency(currency) {
            if (currency === currentCurrency) return;
            
            console.log('💱 Cambiando moneda a:', currency);
            showCurrencyLoader(true);
            
            try {
                currentCurrency = currency;
                
                try {
                    localStorage.setItem('preferredCurrency', currency);
                } catch (e) {
                    console.warn('No se pudo guardar la preferencia de moneda:', e);
                }
                
                updateCurrencyButtons();
                
                if (currency === 'USD') {
                    await getCurrentExchangeRate();
                }
                
                // Actualizar todas las pantallas con los nuevos valores
                await updateAllDisplaysWithCurrency();
                
                console.log('✅ Moneda cambiada exitosamente a:', currency);
                
            } catch (error) {
                console.error('Error cambiando moneda:', error);
                alert('Error al cambiar la moneda. Por favor intenta nuevamente.');
                
                // Revertir cambio si hay error
                currentCurrency = currentCurrency === 'ARS' ? 'USD' : 'ARS';
                updateCurrencyButtons();
            } finally {
                showCurrencyLoader(false);
            }
        }

        // Función para actualizar botones de moneda
        function updateCurrencyButtons() {
            const arsBtn = document.getElementById('currencyARS');
            const usdBtn = document.getElementById('currencyUSD');
            
            if (arsBtn && usdBtn) {
                if (currentCurrency === 'ARS') {
                    arsBtn.classList.add('active');
                    usdBtn.classList.remove('active');
                } else {
                    arsBtn.classList.remove('active');
                    usdBtn.classList.add('active');
                }
            }
        }

        // Función para mostrar/ocultar loader
        function showCurrencyLoader(show) {
            const loader = document.getElementById('currencyLoader');
            if (loader) {
                loader.style.display = show ? 'block' : 'none';
            }
        }

        // ======== FUNCIONES DE BASE DE DATOS ========
        
        // Función mejorada para guardar en base de datos
        async function saveToDatabase(table, data, retries = 3) {
            for (let attempt = 1; attempt <= retries; attempt++) {
                try {
                    console.log(`Guardando en BD (intento ${attempt}):`, table, data);
                    
                    const controller = new AbortController();
                    const timeoutId = setTimeout(() => controller.abort(), 10000);
                    
                    const response = await fetch('api.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            action: 'save',
                            table: table,
                            data: data
                        }),
                        signal: controller.signal
                    });

                    clearTimeout(timeoutId);

                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }

                    const result = await response.json();
                    console.log('Resultado BD:', result);
                    if (!result.success) {
                        throw new Error(result.error || 'Error desconocido');
                    }
                    return result;
                } catch (error) {
                    console.error(`Error en intento ${attempt}:`, error);
                    
                    if (attempt === retries) {
                        alert(`Error al guardar los datos después de ${retries} intentos: ${error.message}`);
                        return { success: false, error: error.message };
                    }
                    
                    await new Promise(resolve => setTimeout(resolve, 1000 * attempt));
                }
            }
        }

        // Función para cargar desde base de datos
        async function loadFromDatabase(table) {
            try {
                console.log('Cargando de BD:', table);
                const response = await fetch('api.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        action: 'load',
                        table: table
                    })
                });
        
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
        
                const result = await response.json();
                console.log('Datos cargados:', table, result);
                return result.success ? (result.data || []) : [];
            } catch (error) {
                console.error('Error cargando de base de datos:', error);
                return [];
            }
        }

        // Función para eliminar de base de datos
        async function deleteFromDatabase(table, id) {
            try {
                const response = await fetch('api.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        action: 'delete',
                        table: table,
                        id: id
                    })
                });
        
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
        
                const result = await response.json();
                return result;
            } catch (error) {
                console.error('Error eliminando de base de datos:', error);
                alert('Error al eliminar los datos: ' + error.message);
                return { success: false, error: error.message };
            }
        }

        // ======== FUNCIONES DE UTILIDAD ========
        
        function escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function formatDate(dateString) {
            try {
                if (!dateString) return '';
                return new Date(dateString + 'T00:00:00').toLocaleDateString('es-AR', {
                    year: 'numeric',
                    month: '2-digit',
                    day: '2-digit'
                });
            } catch (error) {
                console.error('Error formateando fecha:', error);
                return dateString;
            }
        }

        function setTodayDates() {
            try {
                const today = new Date().toISOString().split('T')[0];
                const dateFields = ['incomeDate', 'expenseDate', 'quoteDate', 'investmentDate', 'saleDate'];
                
                dateFields.forEach(fieldId => {
                    const field = document.getElementById(fieldId);
                    if (field) {
                        field.value = today;
                    }
                });
            } catch (error) {
                console.error('Error configurando fechas:', error);
            }
        }
        
        

        // ======== DASHBOARD CON CONVERSIÓN DE MONEDA ========
        
        async function updateDashboard() {
            try {
                console.log('🔄 Actualizando Dashboard - Moneda:', currentCurrency);
                
                // Verificar que los elementos existen antes de usarlos
                const dateFromEl = document.getElementById('dateFrom');
                const dateToEl = document.getElementById('dateTo');
                
                const dateFrom = dateFromEl?.value;
                const dateTo = dateToEl?.value;
                const today = new Date().toISOString().split('T')[0];

                let filteredData = transactions || [];
                if (dateFrom && dateTo) {
                    filteredData = transactions.filter(t => t.date >= dateFrom && t.date <= dateTo);
                }

                // Resto del código permanece igual...
                // PERO agregar verificaciones de elementos antes de actualizar:
                
                const elements = {
                    'totalIncome': formatCurrencySync(totalIncome),
                    'totalIncomeCash': formatCurrencySync(salesCashIncome),
                    'totalIncomeBanking': formatCurrencySync(salesDigitalIncome),
                    'netBalance': formatCurrencySync(netBalance),
                    // ... etc
                };

                // ✅ VERIFICAR QUE LOS ELEMENTOS EXISTEN
                Object.keys(elements).forEach(id => {
                    const element = document.getElementById(id);
                    if (element) {
                        element.textContent = elements[id];
                    } else {
                        console.warn(`Elemento no encontrado: ${id}`);
                    }
                });

                // Resto del código...
                
            } catch (error) {
                console.error('❌ Error actualizando dashboard:', error);
                // No relanzar el error para que no rompa la ejecución
            }
        }

        // ======== BALANCES CON CONVERSIÓN ========
        
        async function updateBalances() {
            try {
                const dateFrom = document.getElementById('balanceDateFrom')?.value;
                const dateTo = document.getElementById('balanceDateTo')?.value;
                const today = new Date().toISOString().split('T')[0];

                let filteredData = transactions || [];
                if (dateFrom && dateTo) {
                    filteredData = transactions.filter(t => 
                        t.date >= dateFrom && t.date <= dateTo
                    );
                }

                // TODOS los saldos usan TC del día (REGLA 3)
                let cashIncome = 0;
                let digitalIncome = 0;
                let cashExpenses = 0;
                let digitalExpenses = 0;

                for (const transaction of filteredData.filter(t => t.type === 'income' && t.paymentMethod === 'cash')) {
                    const convertedAmount = await convertAmountWithCriteria(transaction.amount, transaction.date, transaction.description, true);
                    cashIncome += convertedAmount;
                }

                for (const transaction of filteredData.filter(t => 
                    t.type === 'income' && 
                    t.paymentMethod === 'digital' &&
                    (!t.creditDate || t.creditDate <= today)
                )) {
                    const convertedAmount = await convertAmountWithCriteria(transaction.amount, transaction.date, transaction.description, true);
                    digitalIncome += convertedAmount;
                }

                for (const transaction of filteredData.filter(t => t.type === 'expense' && t.paymentMethod === 'cash')) {
                    const convertedAmount = await convertAmountWithCriteria(transaction.amount, transaction.date, transaction.description, true);
                    cashExpenses += convertedAmount;
                }

                for (const transaction of filteredData.filter(t => 
                    t.type === 'expense' && 
                    t.paymentMethod === 'digital' &&
                    (!t.creditDate || t.creditDate <= today)
                )) {
                    const convertedAmount = await convertAmountWithCriteria(transaction.amount, transaction.date, transaction.description, true);
                    digitalExpenses += convertedAmount;
                }

                const cashBalance = cashIncome - cashExpenses;
                const digitalBalance = digitalIncome - digitalExpenses;
                const totalBalance = cashBalance + digitalBalance;

                const elements = {
                    'cashBalanceDetail': formatCurrencySync(cashBalance),
                    'digitalBalanceDetail': formatCurrencySync(digitalBalance),
                    'totalBalanceDetail': formatCurrencySync(totalBalance)
                };

                Object.keys(elements).forEach(id => {
                    const element = document.getElementById(id);
                    if (element) element.textContent = elements[id];
                });

            } catch (error) {
                console.error('❌ Error actualizando balances:', error);
            }
        }

        // ======== FUNCIÓN PARA ACTUALIZAR TODAS LAS PANTALLAS CON CONVERSIÓN ========
        
        async function updateAllDisplaysWithCurrency() {
            console.log('🔄 Actualizando todas las pantallas con conversión...');
            try {
                await updateDashboard();
                await updateBalances();
                await updateInvestmentDisplay();
                await updateIncomeHistory();
                await updateExpenseHistory();
                await updateStockDisplay();
                
                // Funciones tradicionales sin conversión
                updateTransactionsTable();
                populateCategoryFilter();
                updateProductsList();
                updateProductSelects();
                updateCategorySelects();
                updateCategoryList();
                updateStockProductSelect();
                
                console.log('✅ Todas las pantallas actualizadas con conversión');
            } catch (error) {
                console.error('❌ Error actualizando pantallas:', error);
            }
        }

        function resetDateFilter() {
            try {
                const dateFrom = document.getElementById('dateFrom');
                const dateTo = document.getElementById('dateTo');
                
                if (dateFrom) dateFrom.value = '';
                if (dateTo) dateTo.value = '';
                updateDashboard();
            } catch (error) {
                console.error('Error reseteando filtro de fecha:', error);
            }
        }

        function resetBalanceFilter() {
            try {
                const balanceDateFrom = document.getElementById('balanceDateFrom');
                const balanceDateTo = document.getElementById('balanceDateTo');
                
                if (balanceDateFrom) balanceDateFrom.value = '';
                if (balanceDateTo) balanceDateTo.value = '';
                updateBalances();
            } catch (error) {
                console.error('Error reseteando filtro de balance:', error);
            }
        }
        // ======== FUNCIONES DE GUARDADO ESPECÍFICAS ========
        
        async function saveTransactions() {
            try {
                const result = await saveToDatabase('transactions', transactions);
                if (!result.success) {
                    throw new Error(result.error || 'Error guardando transacciones');
                }
                filteredTransactions = [...transactions];
                return result;
            } catch (error) {
                console.error('Error en saveTransactions:', error);
                throw error;
            }
        }

        async function saveInvestments() {
            try {
                const result = await saveToDatabase('investments', investments);
                if (!result.success) {
                    throw new Error(result.error || 'Error guardando inversiones');
                }
                return result;
            } catch (error) {
                console.error('Error en saveInvestments:', error);
                throw error;
            }
        }

        async function saveStock() {
            try {
                const result = await saveToDatabase('stock', stockData);
                if (!result.success) {
                    throw new Error(result.error || 'Error guardando stock');
                }
                return result;
            } catch (error) {
                console.error("❌ Error guardando stock:", error);
                throw error;
            }
        }

        async function saveProducts() {
            try {
                const result = await saveToDatabase('products', products);
                if (!result.success) {
                    throw new Error(result.error || 'Error guardando productos');
                }
                return result;
            } catch (error) {
                console.error('Error en saveProducts:', error);
                throw error;
            }
        }

        async function saveCategories() {
            try {
                const result = await saveToDatabase('categories', categories);
                if (!result.success) {
                    throw new Error(result.error || 'Error guardando categorías');
                }
                return result;
            } catch (error) {
                console.error('Error en saveCategories:', error);
                throw error;
            }
        }

        // ======== FUNCIONES DE INICIALIZACIÓN ========
        
        async function initializeApp() {
            try {
                console.log('Inicializando aplicación...');

                // 1. Primero inicializar sistema de moneda
                initializeCurrencySystem();

                // 2. Cargar datos desde BD
                console.log('Cargando datos...');
                const [
                    transactionsData,
                    productsData,
                    categoriesData,
                    stockDataLoaded,
                    investmentsData,
                    taxFixedItemsData,
                    taxFixedPaymentsData
                ] = await Promise.all([
                    loadFromDatabase('transactions'),
                    loadFromDatabase('products'),
                    loadFromDatabase('categories'),
                    loadFromDatabase('stock'),
                    loadFromDatabase('investments'),
                    loadFromDatabase('tax_fixed_items'),
                    loadFromDatabase('tax_fixed_payments')
                ]);

                // 3. Asignar datos con valores por defecto
                transactions = transactionsData || [];
                products = productsData || [];
                categories = categoriesData || [];
                stockData = stockDataLoaded || {};
                investments = investmentsData || [];
                taxFixedItems = taxFixedItemsData || [];
                taxFixedPayments = taxFixedPaymentsData || [];

                console.log('Datos cargados:', {
                    transactions: transactions.length,
                    products: products.length,
                    categories: categories.length
                });

                // 4. Configurar datos por defecto
                if (categories.length === 0) {
                    categories = ["Paneles SPC", "Adhesivos", "Accesorios"];
                    await saveCategories();
                }

                if (products.length === 0) {
                    const defaultProducts = [{
                        id: Date.now(),
                        name: 'Panel Marquina Negro SPC 3mm',
                        category: 'Paneles SPC',
                        dimensions: '1,22 x 2,44 mts',
                        price: 0,
                        unit: 'unidad',
                        description: 'Panel decorativo SPC color marquina negro'
                    }];
                    products = defaultProducts;
                    await saveProducts();
                }

                filteredTransactions = [...transactions];

                // 5. Actualizar displays - SIN await para evitar bloqueos
                console.log('Actualizando displays...');
                updateDashboard(); // Sin await
                updateTransactionsTable();
                populateCategoryFilter();
                updateProductsList();
                updateProductSelects();
                updateBalances(); // Sin await
                updateCategorySelects();
                updateCategoryList();
                updateStockDisplay(); // Sin await
                updateStockProductSelect();
                updateInvestmentDisplay(); // Sin await
                updateIncomeHistory(); // Sin await
                updateExpenseHistory(); // Sin await
                initializeFilters();
                
                console.log('✅ Aplicación inicializada correctamente');

            } catch (error) {
                console.error('❌ Error inicializando aplicación:', error);
                alert('Error al cargar la aplicación: ' + error.message);
            }
        }

        function updateAllDisplays() {
            console.log('Actualizando todas las pantallas...');
            try {
                updateDashboard();
                updateTransactionsTable();
                populateCategoryFilter();
                updateProductsList();
                updateProductSelects();
                updateBalances();
                updateCategorySelects();
                updateCategoryList();
                updateStockDisplay();
                updateStockProductSelect();
                updateInvestmentDisplay();
                updateIncomeHistory();
                updateExpenseHistory();
                console.log('Pantallas actualizadas correctamente');
            } catch (error) {
                console.error('Error actualizando pantallas:', error);
            }
            initializeFilters();
            createMigrationButton();
        }

        // ======== FUNCIONES DE TRANSACCIONES ========
        
        async function deleteTransactionFromSource(id, source) {
            if (!confirm('¿Estás seguro de que quieres eliminar esta transacción?')) {
                return;
            }
            
            try {
                const originalTransactions = [...transactions];
                
                const transactionToDelete = transactions.find(t => t.id === id);
                if (!transactionToDelete) {
                    alert('Transacción no encontrada');
                    return;
                }

                // Si es una venta, restaurar el stock
                if (transactionToDelete.type === 'income' && transactionToDelete.productId && transactionToDelete.quantity) {
                    if (stockData[transactionToDelete.productId]) {
                        stockData[transactionToDelete.productId].current += parseFloat(transactionToDelete.quantity);
                        await saveStock();
                    }
                }
                
                transactions = transactions.filter(t => t.id !== id);
                filteredTransactions = transactions.filter(t => t.id !== id);
                
                await saveTransactions();
                
                updateDashboard();
                updateBalances();
                updateTransactionsTable();
                populateCategoryFilter();
                updateStockDisplay();
                
                if (source === 'income') {
                    updateIncomeHistory();
                } else if (source === 'expense') {
                    updateExpenseHistory();
                }
                
                alert('✅ Transacción eliminada correctamente');
            } catch (error) {
                console.error('Error eliminando transacción:', error);
                transactions = originalTransactions;
                filteredTransactions = [...transactions];
                alert('Error al eliminar la transacción');
            }
        }

        // ======== GESTIÓN DE CATEGORÍAS ========
        
        async function addCategory() {
            const newCategory = document.getElementById('newCategory')?.value?.trim();
            
            if (!newCategory) {
                alert('Por favor ingresa un nombre para la categoría');
                return;
            }
            
            if (categories.includes(newCategory)) {
                alert('Esta categoría ya existe');
                return;
            }
            
            try {
                categories.push(newCategory);
                await saveCategories();
                updateCategoryList();
                updateCategorySelects();
                
                document.getElementById('newCategory').value = '';
                alert('✅ Categoría agregada correctamente');
            } catch (error) {
                console.error('Error agregando categoría:', error);
                categories = categories.filter(c => c !== newCategory);
                alert('Error al agregar la categoría');
            }
        }

        async function removeCategory(categoryName) {
            if (!confirm(`¿Estás seguro de que quieres eliminar la categoría "${categoryName}"?`)) {
                return;
            }
            
            try {
                const productsUsingCategory = products.filter(p => p.category === categoryName);
                
                if (productsUsingCategory.length > 0) {
                    alert(`No se puede eliminar la categoría "${categoryName}" porque tiene ${productsUsingCategory.length} producto(s) asociado(s).`);
                    return;
                }
                
                const originalCategories = [...categories];
                categories = categories.filter(c => c !== categoryName);
                
                await saveCategories();
                updateCategoryList();
                updateCategorySelects();
                alert('✅ Categoría eliminada correctamente');
            } catch (error) {
                console.error('Error eliminando categoría:', error);
                categories = originalCategories;
                alert('Error al eliminar la categoría');
            }
        }

        function updateCategoryList() {
            const container = document.getElementById('categoryList');
            if (!container) return;
            
            try {
                container.innerHTML = '';
                
                categories.forEach(category => {
                    const tag = document.createElement('div');
                    tag.className = 'category-tag';
                    tag.innerHTML = `
                        ${category}
                        <button class="remove-category" onclick="removeCategory('${category.replace(/'/g, "\\'")}')">&times;</button>
                    `;
                    container.appendChild(tag);
                });
            } catch (error) {
                console.error('Error actualizando lista de categorías:', error);
            }
        }

        function updateCategorySelects() {
            const selects = ['productCategory', 'editProductCategory'];
            
            selects.forEach(selectId => {
                const select = document.getElementById(selectId);
                if (!select) return;
                
                try {
                    const currentValue = select.value;
                    select.innerHTML = '<option value="">Seleccionar...</option>';
                    
                    categories.forEach(category => {
                        const option = document.createElement('option');
                        option.value = category;
                        option.textContent = category;
                        select.appendChild(option);
                    });
                    
                    select.value = currentValue;
                } catch (error) {
                    console.error(`Error actualizando select ${selectId}:`, error);
                }
            });
        }
        // ======== GESTIÓN DE PRODUCTOS ========
        
        function setupProductForm() {
            const form = document.getElementById('productForm');
            if (!form) return;
            
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                try {
                    const product = {
                        id: Date.now(),
                        name: document.getElementById('productName')?.value || '',
                        category: document.getElementById('productCategory')?.value || '',
                        dimensions: document.getElementById('productDimensions')?.value || '',
                        price: parseFloat(document.getElementById('productPrice')?.value) || 0,
                        unit: document.getElementById('productUnit')?.value || 'unidad',
                        description: document.getElementById('productDescription')?.value || ''
                    };
                    
                    if (!product.name || !product.category) {
                        alert('Por favor completa los campos obligatorios');
                        return;
                    }
                    
                    products.push(product);
                    await saveProducts();
                    updateProductsList();
                    updateProductSelects();
                    updateStockProductSelect();
                    
                    this.reset();
                    alert('✅ Producto agregado correctamente');
                } catch (error) {
                    console.error('Error agregando producto:', error);
                    alert('Error al agregar el producto');
                }
            });
        }

        function setupEditProductForm() {
            const form = document.getElementById('editProductForm');
            if (!form) return;
            
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                try {
                    const productId = parseInt(document.getElementById('editProductId')?.value);
                    const productIndex = products.findIndex(p => p.id === productId);
                    
                    if (productIndex === -1) {
                        alert('Producto no encontrado');
                        return;
                    }
                    
                    const updatedProduct = {
                        ...products[productIndex],
                        name: document.getElementById('editProductName')?.value || '',
                        category: document.getElementById('editProductCategory')?.value || '',
                        dimensions: document.getElementById('editProductDimensions')?.value || '',
                        price: parseFloat(document.getElementById('editProductPrice')?.value) || 0,
                        unit: document.getElementById('editProductUnit')?.value || 'unidad',
                        description: document.getElementById('editProductDescription')?.value || ''
                    };
                    
                    if (!updatedProduct.name || !updatedProduct.category) {
                        alert('Por favor completa los campos obligatorios');
                        return;
                    }
                    
                    products[productIndex] = updatedProduct;
                    await saveProducts();
                    updateProductsList();
                    updateProductSelects();
                    closeEditModal();
                    
                    alert('✅ Producto actualizado correctamente');
                } catch (error) {
                    console.error('Error actualizando producto:', error);
                    alert('Error al actualizar el producto');
                }
            });
        }

        function updateProductsList() {
            const container = document.getElementById('productsList');
            if (!container) return;
            
            try {
                container.innerHTML = '';
                
                products.forEach(product => {
                    const div = document.createElement('div');
                    div.className = 'product-item';
                    div.innerHTML = `
                        <div class="product-actions">
                            <button class="btn-edit" onclick="editProduct(${product.id})">✏️ Editar</button>
                            <button class="delete-btn" onclick="deleteProduct(${product.id})">🗑️</button>
                        </div>
                        <h4>${escapeHtml(product.name)}</h4>
                        <p><strong>Categoría:</strong> ${escapeHtml(product.category)}</p>
                        <p><strong>Dimensiones:</strong> ${escapeHtml(product.dimensions)}</p>
                        <p><strong>Precio:</strong> ${formatCurrency(product.price)} por ${escapeHtml(product.unit)}</p>
                        <p><strong>Descripción:</strong> ${escapeHtml(product.description)}</p>
                    `;
                    container.appendChild(div);
                });
            } catch (error) {
                console.error('Error actualizando lista de productos:', error);
            }
        }

        function updateProductSelects() {
            const selects = ['productSelect', 'quoteProductSelect', 'productSelectMulti'];
            
            selects.forEach(selectId => {
                const select = document.getElementById(selectId);
                if (!select) return;
                
                try {
                    const currentValue = select.value;
                    select.innerHTML = '<option value="">Seleccionar producto...</option>';
                    
                    products.forEach(product => {
                        const option = document.createElement('option');
                        option.value = product.id;
                        option.textContent = `${product.name} - ${formatCurrency(product.price)}/${product.unit}`;
                        option.dataset.price = product.price;
                        option.dataset.unit = product.unit;
                        select.appendChild(option);
                    });
                    
                    select.value = currentValue;
                } catch (error) {
                    console.error(`Error actualizando select ${selectId}:`, error);
                }
            });
        }

        function editProduct(id) {
            try {
                const product = products.find(p => p.id === id);
                if (!product) {
                    alert('Producto no encontrado');
                    return;
                }
                
                document.getElementById('editProductId').value = product.id;
                document.getElementById('editProductName').value = product.name;
                document.getElementById('editProductDimensions').value = product.dimensions;
                document.getElementById('editProductPrice').value = product.price;
                document.getElementById('editProductUnit').value = product.unit;
                document.getElementById('editProductDescription').value = product.description;
                
                updateCategorySelects();
                document.getElementById('editProductCategory').value = product.category;
                
                document.getElementById('editProductModal').style.display = 'block';
            } catch (error) {
                console.error('Error editando producto:', error);
                alert('Error al abrir el editor de productos');
            }
        }

        function closeEditModal() {
            document.getElementById('editProductModal').style.display = 'none';
        }

        async function deleteProduct(id) {
            if (!confirm('¿Estás seguro de que quieres eliminar este producto?')) {
                return;
            }
            
            try {
                const originalProducts = [...products];
                products = products.filter(p => p.id !== id);
                
                await saveProducts();
                updateProductsList();
                updateProductSelects();
                updateStockProductSelect();
                alert('✅ Producto eliminado correctamente');
            } catch (error) {
                console.error('Error eliminando producto:', error);
                products = originalProducts;
                alert('Error al eliminar el producto');
            }
        }

        // ======== CONFIGURACIÓN DE FORMULARIOS ========
        
        function setupIncomeForm() {
            const form = document.getElementById('incomeForm');
            if (!form) return;
            
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                try {
                    const selectedProduct = document.getElementById('productSelect');
                    const paymentMethodElement = document.querySelector('input[name="paymentMethod"]:checked');
                    
                    if (!selectedProduct?.value || !paymentMethodElement) {
                        alert('Por favor selecciona un producto y método de pago');
                        return;
                    }
                    
                    const productName = selectedProduct.options[selectedProduct.selectedIndex].text;
                    const soldPrice = parseFloat(document.getElementById('pricePerUnit')?.value) || 0;
                    const quantity = parseFloat(document.getElementById('quantity')?.value) || 0;
                    const customerName = document.getElementById('customerName')?.value || '';
                    const incomeDate = document.getElementById('incomeDate')?.value || '';
                    const incomeAmount = parseFloat(document.getElementById('incomeAmount')?.value) || 0;
                    const incomeNotes = document.getElementById('incomeNotes')?.value || '';
                    
                    if (!customerName || !incomeDate || quantity <= 0 || soldPrice <= 0) {
                        alert('Por favor completa todos los campos obligatorios');
                        return;
                    }
                    
                    const transaction = {
                        id: Date.now(),
                        type: 'income',
                        date: incomeDate,
                        customer: customerName,
                        productId: selectedProduct.value,
                        productName: productName,
                        quantity: quantity,
                        pricePerUnit: soldPrice,
                        amount: incomeAmount,
                        description: `Venta a ${customerName} - ${productName}`,
                        category: 'Ventas',
                        paymentMethod: paymentMethodElement.value,
                        notes: incomeNotes,
                        saleTimestamp: Date.now()
                    };
                    
                    transactions.push(transaction);
                    
                    if (selectedProduct.value && stockData[selectedProduct.value]) {
                        stockData[selectedProduct.value].current = Math.max(0, stockData[selectedProduct.value].current - quantity);
                        await saveStock();
                    }
                    
                    await saveTransactions();
                    await updateAllDisplaysWithCurrency();
                    
                    this.reset();
                    setTodayDates();
                    alert('✅ Ingreso registrado correctamente');
                } catch (error) {
                    console.error('Error registrando ingreso:', error);
                    alert('Error al registrar el ingreso');
                }
            });
        }

        function setupExpenseForm() {
            const form = document.getElementById('expenseForm');
            if (!form) return;
            
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                try {
                    const expenseTypeElement = document.querySelector('input[name="expenseType"]:checked');
                    const paymentMethodElement = document.querySelector('input[name="expensePaymentMethod"]:checked');
                    
                    if (!expenseTypeElement || !paymentMethodElement) {
                        alert('Por favor selecciona el tipo de gasto y método de pago');
                        return;
                    }
                    
                    const creditDate = paymentMethodElement.value === 'digital' ? 
                        document.getElementById('expenseCreditDate')?.value : null;
                    
                    const transaction = {
                        id: Date.now(),
                        type: 'expense',
                        date: document.getElementById('expenseDate')?.value || '',
                        amount: parseFloat(document.getElementById('expenseAmount')?.value) || 0,
                        description: document.getElementById('expenseDescription')?.value || '',
                        category: document.getElementById('expenseCategory')?.value || '',
                        expenseType: expenseTypeElement.value,
                        paymentMethod: paymentMethodElement.value,
                        creditDate: creditDate,
                        notes: document.getElementById('expenseNotes')?.value || ''
                    };
                    
                    if (!transaction.date || !transaction.description || !transaction.category || transaction.amount <= 0) {
                        alert('Por favor completa todos los campos obligatorios');
                        return;
                    }
                    
                    transactions.push(transaction);
                    await saveTransactions();
                    await updateAllDisplaysWithCurrency();
                    
                    this.reset();
                    setTodayDates();
                    toggleCreditDateFields();
                    alert('✅ Gasto registrado correctamente');
                } catch (error) {
                    console.error('Error registrando gasto:', error);
                    alert('Error al registrar el gasto');
                }
            });
        }
        // ======== FORMULARIO DE INVERSIONES ========
        
        function setupInvestmentForm() {
            const form = document.getElementById('investmentForm');
            if (!form) return;

            form.addEventListener('submit', async function(e) {
                e.preventDefault();

                try {
                    const category = document.getElementById('investmentCategory')?.value;
                    const amount = parseFloat(document.getElementById('investmentAmount')?.value) || 0;

                    const investment = {
                        id: Date.now(),
                        date: document.getElementById('investmentDate')?.value,
                        category: category,
                        description: document.getElementById('investmentDescription')?.value,
                        amount: amount,
                        notes: document.getElementById('investmentNotes')?.value
                    };

                    investments.push(investment);
                    await saveInvestments();

                    // Si es "Aporte de Socios", crear una transacción de ingreso
                    if (category === 'Aporte de Socios') {
                        const paymentMethod = document.querySelector('input[name="investmentPaymentMethod"]:checked')?.value;
            
                        if (!paymentMethod) {
                            alert('Por favor selecciona el método de aporte (Efectivo o Digital)');
                            return;
                        }
            
                        const transaction = {
                            id: Date.now() + 1,
                            type: 'income',
                            date: document.getElementById('investmentDate')?.value,
                            amount: amount,
                            description: `Aporte de socios - ${document.getElementById('investmentDescription')?.value}`,
                            category: 'Aportes de Capital',
                            paymentMethod: paymentMethod,
                            notes: `Generado automáticamente desde inversión`
                        };
            
                        transactions.push(transaction);
                        await saveTransactions();
                        alert(`✅ Inversión registrada correctamente y añadida a saldo ${paymentMethod === 'cash' ? 'en efectivo' : 'digital'}`);
                    } else {
                        alert('✅ Inversión registrada correctamente');
                    }

                    await updateAllDisplaysWithCurrency();
                    this.reset();
                    setTodayDates();
                    togglePaymentMethodForInvestment();
                } catch (error) {
                    console.error('Error registrando inversión:', error);
                    alert('Error al registrar la inversión');
                }
            });
        }

        function togglePaymentMethodForInvestment() {
            const category = document.getElementById('investmentCategory')?.value;
            const paymentGroup = document.getElementById('investmentPaymentMethodGroup');

            if (!paymentGroup) return;

            if (category === 'Aporte de Socios') {
                paymentGroup.style.display = 'block';
                const cashRadio = document.getElementById('investmentPaymentCash');
                const digitalRadio = document.getElementById('investmentPaymentDigital');
                if (cashRadio) cashRadio.required = true;
                if (digitalRadio) digitalRadio.required = true;
            } else {
                paymentGroup.style.display = 'none';
                const cashRadio = document.getElementById('investmentPaymentCash');
                const digitalRadio = document.getElementById('investmentPaymentDigital');
                if (cashRadio) {
                    cashRadio.required = false;
                    cashRadio.checked = false;
                }
                if (digitalRadio) {
                    digitalRadio.required = false;
                    digitalRadio.checked = false;
                }
            }
        }

        // ======== FUNCIONES DE FECHAS DE ACREDITACIÓN ========
        
        function toggleCreditDateFields() {
            // Para ventas
            const salePaymentDigital = document.getElementById('salePaymentDigital');
            const creditDateGroup = document.getElementById('creditDateGroup');
            const saleCreditDate = document.getElementById('saleCreditDate');
            
            if (salePaymentDigital?.checked) {
                creditDateGroup.style.display = 'block';
                const today = new Date().toISOString().split('T')[0];
                saleCreditDate.min = today;
                saleCreditDate.value = today;
            } else {
                creditDateGroup.style.display = 'none';
            }
            
            // Para gastos
            const expensePaymentDigital = document.getElementById('expensePaymentDigital');
            const expenseCreditDateGroup = document.getElementById('expenseCreditDateGroup');
            const expenseCreditDate = document.getElementById('expenseCreditDate');
            
            if (expensePaymentDigital?.checked) {
                expenseCreditDateGroup.style.display = 'block';
                const today = new Date().toISOString().split('T')[0];
                expenseCreditDate.min = today;
                expenseCreditDate.value = today;
            } else {
                expenseCreditDateGroup.style.display = 'none';
            }
        }

        // ======== DISPLAY DE INVERSIONES CON CONVERSIÓN ========
        
        async function updateInvestmentDisplay() {
            try {
                let totalInvestment = 0;
                for (const investment of investments) {
                    const convertedAmount = await convertAmountWithCriteria(investment.amount, investment.date, investment.description, false);
                    totalInvestment += convertedAmount;
                }

                const totalInvestmentEl = document.getElementById('totalInvestment');
                if (totalInvestmentEl) {
                    totalInvestmentEl.textContent = formatCurrencySync(totalInvestment);
                }

                let totalIncome = 0;
                for (const transaction of transactions.filter(t => t.type === 'income' && t.category !== 'Aportes de Capital')) {
                    const convertedAmount = await convertAmountWithCriteria(transaction.amount, transaction.date, transaction.description, false);
                    totalIncome += convertedAmount;
                }

                let totalExpenses = 0;
                for (const transaction of transactions.filter(t => t.type === 'expense')) {
                    const convertedAmount = await convertAmountWithCriteria(transaction.amount, transaction.date, transaction.description, false);
                    totalExpenses += convertedAmount;
                }

                const totalProfit = totalIncome - totalExpenses;
                const roiPercentage = totalInvestment > 0 ? ((totalProfit / totalInvestment) * 100) : 0;

                const firstTransaction = transactions
                    .filter(t => t.type === 'income')
                    .sort((a, b) => new Date(a.date) - new Date(b.date))[0];

                let monthlyReturn = 0;
                let paybackTime = '-';

                if (firstTransaction) {
                    const startDate = new Date(firstTransaction.date);
                    const currentDate = new Date();
                    const monthsDiff = (currentDate.getFullYear() - startDate.getFullYear()) * 12 + 
                                    (currentDate.getMonth() - startDate.getMonth());

                    if (monthsDiff > 0) {
                        monthlyReturn = totalProfit / monthsDiff;
                        if (monthlyReturn > 0) {
                            const monthsToPayback = totalInvestment / monthlyReturn;
                            paybackTime = `${Math.ceil(monthsToPayback)} meses`;
                        }
                    }
                }

                const roiElements = {
                    'totalProfit': formatCurrencySync(totalProfit),
                    'roiPercentage': roiPercentage.toFixed(1) + '%',
                    'paybackTime': paybackTime,
                    'monthlyReturn': formatCurrencySync(monthlyReturn)
                };

                Object.keys(roiElements).forEach(id => {
                    const element = document.getElementById(id);
                    if (element) element.textContent = roiElements[id];
                });

                // Tabla con conversión
                const tbody = document.getElementById('investmentTableBody');
                if (tbody) {
                    tbody.innerHTML = '';
                    
                    const sortedInvestments = [...investments].sort((a, b) => 
                        new Date(b.date) - new Date(a.date)
                    );
                    
                    for (const investment of sortedInvestments) {
                        const convertedAmount = await convertAmountWithCriteria(investment.amount, investment.date, investment.description, false);
                        
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${formatDate(investment.date)}</td>
                            <td>${investment.category}</td>
                            <td>${investment.description}</td>
                            <td style="color: #f44336; font-weight: bold;">-${formatCurrencySync(convertedAmount)}</td>
                            <td>
                                <button class="delete-btn" onclick="deleteInvestment(${investment.id})">
                                    🗑️
                                </button>
                            </td>
                        `;
                        tbody.appendChild(row);
                    }
                }

            } catch (error) {
                console.error('❌ Error actualizando inversiones:', error);
            }
        }

        // ======== ELIMINAR INVERSIÓN ========
        
        async function deleteInvestment(id) {
            if (!confirm('¿Estás seguro de que quieres eliminar esta inversión?')) {
                return;
            }
            
            try {
                const originalInvestments = [...investments];
                const originalTransactions = [...transactions];
                
                const investmentToDelete = investments.find(inv => inv.id === id);
                
                if (investmentToDelete && investmentToDelete.category === 'Aporte de Socios') {
                    const relatedTransactions = transactions.filter(t => 
                        t.type === 'income' && 
                        t.category === 'Aportes de Capital' && 
                        t.date === investmentToDelete.date && 
                        parseFloat(t.amount) === parseFloat(investmentToDelete.amount)
                    );
                    
                    relatedTransactions.forEach(relatedTx => {
                        transactions = transactions.filter(t => t.id !== relatedTx.id);
                        filteredTransactions = filteredTransactions.filter(t => t.id !== relatedTx.id);
                    });
                }
                
                investments = investments.filter(inv => inv.id !== id);
                
                await saveInvestments();
                
                if (investmentToDelete && investmentToDelete.category === 'Aporte de Socios') {
                    await saveTransactions();
                    updateDashboard();
                    updateBalances();
                    updateTransactionsTable();
                    updateIncomeHistory();
                    populateCategoryFilter();
                }
                
                updateInvestmentDisplay();
                alert('✅ Inversión eliminada correctamente');
            } catch (error) {
                console.error('Error eliminando inversión:', error);
                investments = originalInvestments;
                transactions = originalTransactions;
                filteredTransactions = [...transactions];
                alert('Error al eliminar la inversión');
            }
        }

        // ======== HISTORIALES CON CONVERSIÓN ========
        
        async function updateIncomeHistory() {
            const tbody = document.getElementById('incomeHistoryBody');
            if (!tbody) return;
            
            try {
                tbody.innerHTML = '';
                
                const incomeTransactions = transactions
                    .filter(t => t.type === 'income')
                    .sort((a, b) => new Date(b.date) - new Date(a.date));
                
                for (const transaction of incomeTransactions) {
                    const convertedAmount = await convertAmountWithCriteria(transaction.amount, transaction.date, transaction.description, false);
                    const convertedPricePerUnit = transaction.pricePerUnit ? 
                        await convertAmountWithCriteria(transaction.pricePerUnit, transaction.date, transaction.description, false) : null;
                    
                    // Determinar si mostrar checkbox de facturación
                    const isDigitalSale = transaction.paymentMethod === 'digital' && transaction.category !== 'Aportes de Capital';
                    const isInvoiced = transaction.invoiced === 1 || transaction.invoiced === true;
                    
                    let invoiceColumn = '';
                    if (isDigitalSale) {
                        invoiceColumn = `
                            <label style="display: flex; align-items: center; cursor: pointer;">
                                <input type="checkbox" 
                                    ${isInvoiced ? 'checked' : ''} 
                                    data-transaction-id="${transaction.id}"
                                    onchange="updateInvoiceStatus(${transaction.id}, this.checked)"
                                    style="margin-right: 5px; transform: scale(1.2);">
                                <span style="font-size: 12px; color: ${isInvoiced ? '#27ae60' : '#e74c3c'};">
                                    ${isInvoiced ? '✅ Facturada' : '📋 Pendiente'}
                                </span>
                            </label>
                        `;
                    } else if (transaction.paymentMethod === 'cash') {
                        invoiceColumn = '<span style="color: #666; font-size: 12px;">💵 No aplica</span>';
                    } else {
                        invoiceColumn = '<span style="color: #666; font-size: 12px;">-</span>';
                    }
                    
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${formatDate(transaction.date)}</td>
                        <td>${escapeHtml(transaction.customer) || '-'}</td>
                        <td>${escapeHtml(transaction.productName) || '-'}</td>
                        <td>${transaction.quantity || '-'}</td>
                        <td>${convertedPricePerUnit ? formatCurrencySync(convertedPricePerUnit) : '-'}</td>
                        <td class="income">+${formatCurrencySync(convertedAmount)}</td>
                        <td><span class="badge badge-${transaction.paymentMethod}">
                            ${transaction.paymentMethod === 'cash' ? '💵 Cash' : '💳 Digital'}
                        </span></td>
                        <td style="text-align: center; padding: 5px;">${invoiceColumn}</td>
                        <td>
                            <button class="delete-btn" onclick="deleteTransactionFromSource(${transaction.id}, 'income')" 
                                title="Eliminar ingreso">
                                🗑️
                            </button>
                        </td>
                    `;
                    tbody.appendChild(row);
                }
            } catch (error) {
                console.error('Error actualizando historial de ingresos:', error);
            }
        }

        async function updateExpenseHistory() {
            const tbody = document.getElementById('expenseHistoryBody');
            if (!tbody) return;
            
            try {
                tbody.innerHTML = '';
                
                const expenseTransactions = transactions
                    .filter(t => t.type === 'expense')
                    .sort((a, b) => new Date(b.date) - new Date(a.date));
                
                for (const transaction of expenseTransactions) {
                    const convertedAmount = await convertAmountWithCriteria(transaction.amount, transaction.date, transaction.description, false);
                    
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${formatDate(transaction.date)}</td>
                        <td>${escapeHtml(transaction.category)}</td>
                        <td>${escapeHtml(transaction.description)}</td>
                        <td class="expense">-${formatCurrencySync(convertedAmount)}</td>
                        <td>${transaction.expenseType ? `<span class="badge badge-${transaction.expenseType}">${transaction.expenseType === 'fixed' ? '🔒 Fijo' : '📈 Variable'}</span>` : '-'}</td>
                        <td><span class="badge badge-${transaction.paymentMethod}">
                            ${transaction.paymentMethod === 'cash' ? '💵 Cash' : '💳 Digital'}
                        </span></td>
                        <td>
                            <button class="delete-btn" onclick="deleteTransactionFromSource(${transaction.id}, 'expense')" 
                                title="Eliminar gasto">
                                🗑️
                            </button>
                        </td>
                    `;
                    tbody.appendChild(row);
                }
            } catch (error) {
                console.error('Error actualizando historial de gastos:', error);
            }
        }
        // ======== GESTIÓN DE STOCK CON CONVERSIÓN ========
        
        function updateStockProductSelect() {
            const select = document.getElementById('stockProductSelect');
            if (!select) return;
            
            try {
                select.innerHTML = '<option value="">Seleccionar producto...</option>';
                
                products.forEach(product => {
                    const option = document.createElement('option');
                    option.value = product.id;
                    option.textContent = product.name;
                    select.appendChild(option);
                });
            } catch (error) {
                console.error('Error actualizando select de stock:', error);
            }
        }

        async function setInitialStock() {
            try {
                const productId = document.getElementById('stockProductSelect')?.value;
                const initialStock = parseInt(document.getElementById('initialStock')?.value);
                const minimumStock = parseInt(document.getElementById('minimumStock')?.value);
                
                if (!productId || initialStock < 0) {
                    alert('Por favor selecciona un producto y cantidad válida');
                    return;
                }
                
                stockData[productId] = {
                    initial: initialStock,
                    current: initialStock,
                    minimum: minimumStock || 5
                };
                
                await saveStock();
                updateStockDisplay();
                
                document.getElementById('stockProductSelect').value = '';
                document.getElementById('initialStock').value = '';
                document.getElementById('minimumStock').value = '';
                
                alert('✅ Stock inicial establecido correctamente');
            } catch (error) {
                console.error('Error estableciendo stock inicial:', error);
                alert('Error al establecer el stock inicial');
            }
        }

        async function adjustStock(productId, amount, type) {
            try {
                if (!stockData[productId]) {
                    alert('Este producto no tiene stock configurado');
                    return;
                }

                const inputId = type === 'add' ? `add-${productId}` : `remove-${productId}`;
                const quantity = parseInt(document.getElementById(inputId)?.value);

                if (!quantity || quantity <= 0) {
                    alert('Por favor ingresa una cantidad válida');
                    return;
                }

                if (type === 'add') {
                    stockData[productId].current += quantity;
                    stockData[productId].initial += quantity;
                } else {
                    stockData[productId].current = Math.max(0, stockData[productId].current - quantity);
                }

                await saveStock();
                updateStockDisplay();

                const messages = {
                    'add': '✅ Stock aumentado correctamente',
                    'sample': '✅ Stock reducido por muestra entregada',
                    'break': '✅ Stock reducido por rotura registrada'
                };
                alert(messages[type] || '✅ Stock actualizado correctamente');

                document.getElementById(inputId).value = '';
            } catch (error) {
                console.error('Error ajustando stock:', error);
                alert('❌ Error al actualizar el stock');
            }
        }

        async function updateStockDisplay() {
            const stockList = document.getElementById('stockList');
            if (!stockList) return;
            
            try {
                stockList.innerHTML = '';
                
                let totalProducts = 0;
                let lowStockCount = 0;
                let outOfStockCount = 0;
                let totalStockValue = 0;
                
                for (const product of products) {
                    const stock = stockData[product.id];
                    if (!stock) continue;
                    
                    totalProducts++;
                    
                    // Convertir precio según criterios
                    const convertedPrice = await convertAmountWithCriteria(product.price, null, '', false);
                    const stockValue = stock.current * convertedPrice;
                    totalStockValue += stockValue;
                    
                    let stockClass = '';
                    let stockStatus = '';
                    
                    if (stock.current === 0) {
                        stockClass = 'out-of-stock';
                        stockStatus = 'Sin Stock';
                        outOfStockCount++;
                    } else if (stock.current <= stock.minimum) {
                        stockClass = 'low-stock';
                        stockStatus = 'Stock Bajo';
                        lowStockCount++;
                    } else {
                        stockStatus = 'Stock OK';
                    }
                    
                    if (!stock.reductions) {
                        stock.reductions = { sales: 0, samples: 0, breaks: 0 };
                    }
                    
                    const totalReduced = stock.reductions.sales + stock.reductions.samples + stock.reductions.breaks;
                    
                    const div = document.createElement('div');
                    div.className = `stock-item ${stockClass}`;
                    div.innerHTML = `
                        <div class="stock-info">
                            <h4>${escapeHtml(product.name)}</h4>
                            <p><strong>Precio:</strong> ${formatCurrencySync(convertedPrice)}</p>
                            <p><strong>Estado:</strong> ${stockStatus}</p>
                            <p><strong>Valor en stock:</strong> ${formatCurrencySync(stockValue)}</p>
                            
                            <div style="margin-top: 10px; padding: 8px; background: #f0f0f0; border-radius: 5px; font-size: 12px;">
                                <strong>📊 Desglose de Reducciones:</strong><br>
                                🛒 Ventas: ${stock.reductions.sales}<br>
                                🎁 Muestras: ${stock.reductions.samples}<br>
                                💥 Roturas: ${stock.reductions.breaks}<br>
                                📉 Total reducido: ${totalReduced}
                            </div>
                        </div>
                        <div>
                            <div style="text-align: center; margin-bottom: 5px; font-size: 12px; color: #666;">Stock Actual</div>
                            <div class="stock-quantity ${stock.current === 0 ? 'out' : stock.current <= stock.minimum ? 'low' : ''}">${stock.current}</div>
                        </div>
                        <div>
                            <div style="text-align: center; margin-bottom: 5px; font-size: 12px; color: #666;">Stock Mínimo</div>
                            <div style="text-align: center; font-weight: bold; cursor: pointer; color: #4CAF50;" 
                                onclick="editMinimumStock(${product.id})" title="Click para editar">
                                ${stock.minimum} ✏️
                            </div>
                        </div>
                        <div>
                            <div style="text-align: center; margin-bottom: 5px; font-size: 12px; color: #666;">Stock Inicial</div>
                            <div style="text-align: center; font-weight: bold; cursor: pointer; color: #ff9800;" 
                                onclick="editInitialStock(${product.id})" title="Click para editar stock inicial">
                                ${stock.initial} ✏️
                            </div>
                        </div>
                        <div class="stock-actions">
                            <input type="number" id="add-${product.id}" placeholder="Cant." min="1">
                            <button class="btn-add-stock" onclick="adjustStock(${product.id}, 0, 'add')">➕ Agregar</button>
                            <input type="number" id="remove-${product.id}" placeholder="Cant." min="1">
                            <button class="btn-remove-stock" onclick="adjustStock(${product.id}, 0, 'sample')" style="margin-bottom: 5px;">🎁 Muestra</button>
                            <button class="btn-remove-stock" onclick="adjustStock(${product.id}, 0, 'break')" style="background: #dc3545;">💥 Rotura</button>
                        </div>
                    `;
                    stockList.appendChild(div);
                }
                
                const stockElements = {
                    'totalProducts': totalProducts,
                    'lowStockCount': lowStockCount,
                    'outOfStockCount': outOfStockCount,
                    'totalStockValue': formatCurrencySync(totalStockValue)
                };
                
                Object.keys(stockElements).forEach(id => {
                    const element = document.getElementById(id);
                    if (element) element.textContent = stockElements[id];
                });
            } catch (error) {
                console.error('Error actualizando display de stock:', error);
            }
        }

        async function editMinimumStock(productId) {
            if (!stockData[productId]) {
                alert('Este producto no tiene stock configurado');
                return;
            }

            const currentMinimum = stockData[productId].minimum;
            const newMinimum = prompt(`Stock mínimo actual: ${currentMinimum}\n\nIngresa el nuevo stock mínimo:`, currentMinimum);
            
            if (newMinimum === null) return;
            
            const parsedMinimum = parseInt(newMinimum);
            if (isNaN(parsedMinimum) || parsedMinimum < 0) {
                alert('Por favor ingresa un número válido mayor o igual a 0');
                return;
            }

            try {
                stockData[productId].minimum = parsedMinimum;
                await saveStock();
                updateStockDisplay();
                alert(`✅ Stock mínimo actualizado a ${parsedMinimum}`);
            } catch (error) {
                console.error('Error actualizando stock mínimo:', error);
                alert('❌ Error al actualizar el stock mínimo');
            }
        }

        async function editInitialStock(productId) {
            try {
                if (!stockData[productId]) {
                    alert('Este producto no tiene stock configurado');
                    return;
                }

                const product = products.find(p => p.id == productId);
                if (!product) {
                    alert('Producto no encontrado');
                    return;
                }

                const currentInitial = stockData[productId].initial;
                const currentCurrent = stockData[productId].current;
                const reductions = stockData[productId].reductions || { sales: 0, samples: 0, breaks: 0 };
                const totalReduced = reductions.sales + reductions.samples + reductions.breaks;

                const newInitial = prompt(
                    `🔧 EDITAR STOCK INICIAL: ${product.name}\n\n` +
                    `📦 Stock inicial actual: ${currentInitial}\n` +
                    `📊 Stock actual: ${currentCurrent}\n` +
                    `📉 Total reducido: ${totalReduced}\n\n` +
                    `¿Cuál es el nuevo stock inicial?\n` +
                    `(Esto NO afectará el stock actual ni los contadores)`,
                    currentInitial
                );

                if (newInitial === null) return;

                const parsedInitial = parseInt(newInitial);
                if (isNaN(parsedInitial) || parsedInitial < 0) {
                    alert('❌ El valor debe ser un número entero mayor o igual a 0');
                    return;
                }

                if (!confirm(`¿Confirmar cambio de stock inicial?\n\n📦 ${product.name}\n\nDe: ${currentInitial}\nA: ${parsedInitial}\n\n⚠️ El stock actual (${currentCurrent}) y contadores no se modificarán.`)) {
                    return;
                }

                stockData[productId].initial = parsedInitial;

                await saveStock();
                updateStockDisplay();

                alert(`✅ Stock inicial actualizado!\n\n📦 ${product.name}\n\n📊 Antes: ${currentInitial}\n📊 Ahora: ${parsedInitial}\n\n✅ Stock actual y contadores conservados.`);

            } catch (error) {
                console.error('Error editando stock inicial:', error);
                alert('❌ Error al editar el stock inicial: ' + error.message);
            }
        }

        async function trackSaleInStock(productId, quantity) {
            if (!stockData[productId]) return;
            
            if (!stockData[productId].reductions) {
                stockData[productId].reductions = { sales: 0, samples: 0, breaks: 0 };
            }
            
            stockData[productId].reductions.sales += quantity;
            await saveStock();
        }

        async function synchronizeStock() {
            if (!confirm('¿Sincronizar el stock actual con base en: Stock Inicial - Total Reducido?\n\nEsto corregirá las inconsistencias que ves.')) {
                return;
            }
            
            try {
                let changesCount = 0;
                
                for (const productId in stockData) {
                    const stock = stockData[productId];
                    
                    if (!stock.reductions) {
                        stock.reductions = { sales: 0, samples: 0, breaks: 0 };
                    }
                    
                    const totalReduced = stock.reductions.sales + stock.reductions.samples + stock.reductions.breaks;
                    const calculatedCurrent = stock.initial - totalReduced;
                    
                    if (stock.current !== calculatedCurrent) {
                        console.log(`🔄 Producto ${productId}: ${stock.current} → ${calculatedCurrent}`);
                        stock.current = Math.max(0, calculatedCurrent);
                        changesCount++;
                    }
                }
                
                if (changesCount > 0) {
                    await saveStock();
                    updateStockDisplay();
                    alert(`✅ Sincronización completada!\n\n${changesCount} productos corregidos.\n\nAhora el Stock Actual = Stock Inicial - Total Reducido`);
                } else {
                    alert('ℹ️ No se encontraron inconsistencias para corregir.');
                }
                
            } catch (error) {
                console.error('Error sincronizando stock:', error);
                alert('❌ Error durante la sincronización: ' + error.message);
            }
        }

        function createMigrationButton() {
            let hasTrackingData = false;
            for (const productId in stockData) {
                const stock = stockData[productId];
                if (stock.reductions && (stock.reductions.sales > 0 || stock.reductions.samples > 0 || stock.reductions.breaks > 0)) {
                    hasTrackingData = true;
                    break;
                }
            }
            
            if (hasTrackingData) {
                console.log('Ya hay datos de tracking, no se muestra botón de migración');
                return;
            }
            
            if (document.getElementById('migrationButton')) return;
            
            const stockTab = document.getElementById('stock');
            if (!stockTab) return;
            
            const button = document.createElement('button');
            button.id = 'migrationButton';
            button.className = 'btn';
            button.style.cssText = 'background: orange; margin: 20px 0; width: 100%;';
            button.innerHTML = '🔄 MIGRAR DATOS HISTÓRICOS DE VENTAS (Ejecutar solo una vez)';
            button.onclick = function() {
                if (confirm('¿Migrar datos históricos de ventas al nuevo sistema de tracking?\n\nEsto analizará tu historial de ventas y actualizará los contadores.\n\nEjecútalo solo UNA vez.')) {
                    migrateHistoricalStockData();
                    setTimeout(() => {
                        button.remove();
                    }, 5000);
                }
            };
            
            const firstElement = stockTab.children[0];
            stockTab.insertBefore(button, firstElement);
        }

        async function migrateHistoricalStockData() {
            try {
                console.log('🔄 Iniciando migración de datos históricos de stock...');
                
                let migrationCount = 0;
                
                for (const productId in stockData) {
                    const stock = stockData[productId];
                    
                    if (stock.reductions && (stock.reductions.sales > 0 || stock.reductions.samples > 0 || stock.reductions.breaks > 0)) {
                        console.log(`Producto ${productId} ya tiene datos de tracking, omitiendo...`);
                        continue;
                    }
                    
                    if (!stock.reductions) {
                        stock.reductions = { sales: 0, samples: 0, breaks: 0 };
                    }
                    
                    const productSales = transactions.filter(t => 
                        t.type === 'income' && 
                        t.productId == productId && 
                        t.quantity
                    );
                    
                    const totalSalesQuantity = productSales.reduce((sum, sale) => {
                        return sum + (parseFloat(sale.quantity) || 0);
                    }, 0);
                    
                    if (totalSalesQuantity > 0) {
                        stock.reductions.sales = totalSalesQuantity;
                        migrationCount++;
                        console.log(`✅ Producto ${productId}: ${totalSalesQuantity} ventas migradas`);
                    }
                }
                
                if (migrationCount > 0) {
                    await saveStock();
                    updateStockDisplay();
                    alert(`✅ Migración completada!\n\n${migrationCount} productos actualizados con datos históricos de ventas.\n\nNota: Las muestras y roturas empezarán a contar desde ahora.`);
                } else {
                    alert('ℹ️ No hay datos históricos para migrar o ya están actualizados.');
                }
                
                console.log('🔄 Migración completada');
                
            } catch (error) {
                console.error('❌ Error en migración:', error);
                alert('❌ Error durante la migración: ' + error.message);
            }
        }
        // ======== VENTAS MULTI-PRODUCTO ========
        
        function updateProductPriceMulti() {
            const select = document.getElementById('productSelectMulti');
            const priceInput = document.getElementById('pricePerUnitMulti');
            
            if (!select || !priceInput) return;
            
            try {
                if (select.value) {
                    const selectedOption = select.options[select.selectedIndex];
                    if (selectedOption && selectedOption.dataset.price) {
                        priceInput.value = selectedOption.dataset.price;
                        calculateProductTotalMulti();
                    }
                }
            } catch (error) {
                console.error('Error actualizando precio del producto:', error);
            }
        }

        function calculateProductTotalMulti() {
            try {
                const quantity = parseFloat(document.getElementById('quantityMulti')?.value) || 0;
                const pricePerUnit = parseFloat(document.getElementById('pricePerUnitMulti')?.value) || 0;
                const total = quantity * pricePerUnit;
                const productTotal = document.getElementById('productTotalMulti');
                
                if (productTotal) {
                    productTotal.value = total.toFixed(2);
                }
            } catch (error) {
                console.error('Error calculando total:', error);
            }
        }

        function addProductToSale() {
            try {
                const productSelect = document.getElementById('productSelectMulti');
                const quantity = parseFloat(document.getElementById('quantityMulti')?.value);
                const pricePerUnit = parseFloat(document.getElementById('pricePerUnitMulti')?.value);
                const productTotal = parseFloat(document.getElementById('productTotalMulti')?.value);
                
                if (!productSelect?.value || !quantity || !pricePerUnit) {
                    alert('Por favor completa todos los campos del producto');
                    return;
                }
                
                const product = products.find(p => p.id == productSelect.value);
                if (!product) {
                    alert('Producto no encontrado');
                    return;
                }
                
                const saleProduct = {
                    id: Date.now(),
                    productId: product.id,
                    productName: product.name,
                    quantity: quantity,
                    pricePerUnit: pricePerUnit,
                    total: productTotal,
                    unit: product.unit
                };
                
                currentSaleProducts.push(saleProduct);
                updateSaleProductsDisplay();
                
                document.getElementById('productSelectMulti').value = '';
                document.getElementById('quantityMulti').value = '';
                document.getElementById('pricePerUnitMulti').value = '';
                document.getElementById('productTotalMulti').value = '';
                
            } catch (error) {
                console.error('Error agregando producto a la venta:', error);
                alert('Error al agregar el producto a la venta');
            }
        }

        function updateSaleProductsDisplay() {
            const container = document.getElementById('saleProductsContainer');
            const listDiv = document.getElementById('saleProductsList');
            
            if (!container || !listDiv) return;
            
            try {
                container.innerHTML = '';
                
                if (currentSaleProducts.length === 0) {
                    listDiv.style.display = 'none';
                    return;
                }
                
                listDiv.style.display = 'block';
                let grandTotal = 0;
                
                currentSaleProducts.forEach((item, index) => {
                    grandTotal += item.total;
                    
                    const div = document.createElement('div');
                    div.style.cssText = 'background: white; padding: 15px; border-radius: 8px; margin-bottom: 10px; display: flex; justify-content: space-between; align-items: center; border-left: 4px solid #4CAF50;';
                    div.innerHTML = `
                        <div>
                            <strong>${escapeHtml(item.productName)}</strong><br>
                            Cantidad: ${item.quantity} ${item.unit} × ${formatCurrency(item.pricePerUnit)} = ${formatCurrency(item.total)}
                        </div>
                        <button class="delete-btn" onclick="removeSaleProduct(${index})" title="Eliminar producto">
                            🗑️
                        </button>
                    `;
                    container.appendChild(div);
                });
                
                document.getElementById('saleGrandTotal').textContent = grandTotal.toFixed(2);
                
            } catch (error) {
                console.error('Error actualizando display de productos de venta:', error);
            }
        }

        function removeSaleProduct(index) {
            try {
                currentSaleProducts.splice(index, 1);
                updateSaleProductsDisplay();
            } catch (error) {
                console.error('Error eliminando producto de la venta:', error);
            }
        }

        function cancelSale() {
            if (currentSaleProducts.length > 0) {
                if (!confirm('¿Estás seguro de que quieres cancelar esta venta? Se perderán todos los productos agregados.')) {
                    return;
                }
            }
            
            clearSale();
        }

        function clearSale() {
            currentSaleProducts = [];
            updateSaleProductsDisplay();
            
            document.getElementById('saleDataForm').reset();
            setTodayDates();
            
            toggleCreditDateFields();
        }

        async function completeSale() {
            try {
                if (currentSaleProducts.length === 0) {
                    alert('No hay productos en la venta');
                    return;
                }
                
                const saleDate = document.getElementById('saleDate')?.value;
                const saleCustomer = document.getElementById('saleCustomer')?.value;
                const paymentMethodElement = document.querySelector('input[name="salePaymentMethod"]:checked');
                const saleNotes = document.getElementById('saleNotes')?.value || '';
                
                if (!saleDate || !saleCustomer || !paymentMethodElement) {
                    alert('Por favor completa todos los datos de la venta');
                    return;
                }
                
                const creditDate = paymentMethodElement.value === 'digital' ? 
                    document.getElementById('saleCreditDate')?.value : null;
                
                const saleTimestamp = Date.now();
                
                for (let i = 0; i < currentSaleProducts.length; i++) {
                    const item = currentSaleProducts[i];
                    
                    const transaction = {
                        id: saleTimestamp + i,
                        type: 'income',
                        date: saleDate,
                        customer: saleCustomer,
                        productId: item.productId,
                        productName: item.productName,
                        quantity: item.quantity,
                        pricePerUnit: item.pricePerUnit,
                        amount: item.total,
                        description: `Venta a ${saleCustomer} - ${item.productName}`,
                        category: 'Ventas',
                        paymentMethod: paymentMethodElement.value,
                        creditDate: creditDate,
                        notes: saleNotes,
                        saleTimestamp: saleTimestamp
                    };
                    
                    transactions.push(transaction);
                    
                    if (stockData[item.productId]) {
                        stockData[item.productId].current = Math.max(0, stockData[item.productId].current - item.quantity);
                        await trackSaleInStock(item.productId, item.quantity);
                    }
                }
                
                await saveTransactions();
                await updateAllDisplaysWithCurrency();
                
                alert(`✅ Venta completada correctamente!\n${currentSaleProducts.length} productos registrados para ${saleCustomer}`);
                
                clearSale();
                
            } catch (error) {
                console.error('Error completando venta:', error);
                alert('Error al completar la venta');
            }
        }

        function setupMultiProductEvents() {
            try {
                const productSelectMulti = document.getElementById('productSelectMulti');
                const quantityMulti = document.getElementById('quantityMulti');
                const pricePerUnitMulti = document.getElementById('pricePerUnitMulti');
                
                if (productSelectMulti) {
                    productSelectMulti.addEventListener('change', updateProductPriceMulti);
                }
                if (quantityMulti) {
                    quantityMulti.addEventListener('input', calculateProductTotalMulti);
                }
                if (pricePerUnitMulti) {
                    pricePerUnitMulti.addEventListener('input', calculateProductTotalMulti);
                }
                
                const salePaymentRadios = document.querySelectorAll('input[name="salePaymentMethod"]');
                salePaymentRadios.forEach(radio => {
                    radio.addEventListener('change', toggleCreditDateFields);
                });
                
                const expensePaymentRadios = document.querySelectorAll('input[name="expensePaymentMethod"]');
                expensePaymentRadios.forEach(radio => {
                    radio.addEventListener('change', toggleCreditDateFields);
                });
                
            } catch (error) {
                console.error('Error configurando eventos multi-producto:', error);
            }
        }

        // ======== PRESUPUESTOS ========
        
        function addQuoteItem() {
            try {
                const productSelect = document.getElementById('quoteProductSelect');
                const quantity = parseFloat(document.getElementById('quoteQuantity')?.value);
                
                if (!productSelect?.value || !quantity) {
                    alert('Por favor selecciona un producto y cantidad');
                    return;
                }
                
                const product = products.find(p => p.id == productSelect.value);
                if (!product) {
                    alert('Producto no encontrado');
                    return;
                }
                
                const total = product.price * quantity;
                
                const quoteItem = {
                    productId: product.id,
                    productName: product.name,
                    quantity: quantity,
                    price: product.price,
                    unit: product.unit,
                    total: total
                };
                
                quoteItems.push(quoteItem);
                updateQuoteItemsDisplay();
                
                document.getElementById('quoteProductSelect').value = '';
                document.getElementById('quoteQuantity').value = '';
            } catch (error) {
                console.error('Error agregando item al presupuesto:', error);
                alert('Error al agregar el producto al presupuesto');
            }
        }

        function updateQuoteItemsDisplay() {
            const container = document.getElementById('quoteItems');
            if (!container) return;
            
            try {
                container.innerHTML = '';
                
                let totalAmount = 0;
                
                quoteItems.forEach((item, index) => {
                    totalAmount += item.total;
                    
                    const div = document.createElement('div');
                    div.className = 'quote-item';
                    div.innerHTML = `
                        <div>
                            <strong>${item.productName}</strong><br>
                            Cantidad: ${item.quantity} ${item.unit} × ${formatCurrency(item.price)} = ${formatCurrency(item.total)}
                        </div>
                        <button class="delete-btn" onclick="removeQuoteItem(${index})">🗑️</button>
                    `;
                    container.appendChild(div);
                });
                
                const quoteTotal = document.getElementById('quoteTotal');
                const quoteTotalAmount = document.getElementById('quoteTotalAmount');
                
                if (quoteItems.length > 0) {
                    if (quoteTotal) quoteTotal.style.display = 'block';
                    if (quoteTotalAmount) quoteTotalAmount.textContent = totalAmount.toFixed(2);
                } else {
                    if (quoteTotal) quoteTotal.style.display = 'none';
                }
            } catch (error) {
                console.error('Error actualizando display de presupuesto:', error);
            }
        }

        function removeQuoteItem(index) {
            try {
                quoteItems.splice(index, 1);
                updateQuoteItemsDisplay();
            } catch (error) {
                console.error('Error removiendo item del presupuesto:', error);
            }
        }

        function generateQuote() {
            try {
                const customer = document.getElementById('quoteCustomer')?.value;
                const date = document.getElementById('quoteDate')?.value;
                const phone = document.getElementById('quotePhone')?.value;
                const email = document.getElementById('quoteEmail')?.value;
                const address = document.getElementById('quoteAddress')?.value;
                
                if (!customer || quoteItems.length === 0) {
                    alert('Por favor completa los datos del cliente y agrega al menos un producto');
                    return;
                }
                
                const totalAmount = quoteItems.reduce((sum, item) => sum + item.total, 0);
                
                // HTML optimizado para una sola página A4
                currentQuoteHTML = `
                    <div style="
                        font-family: Arial, sans-serif; 
                        background: white !important; 
                        color: black !important;
                        padding: 15mm;
                        margin: 0;
                        width: 100%;
                        min-height: 100vh;
                        box-sizing: border-box;
                        line-height: 1.3;
                    ">
                        
                        <!-- ENCABEZADO COMPACTO -->
                        <div style="text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 15px; background: white !important;">
                            <h1 style="margin: 0; font-size: 32px; color: #222 !important; letter-spacing: 1px; background: white !important;">
                                <span style="color: #222 !important; background: white !important;">REVEST</span><span style="color: #c9a86a !important; background: white !important;">IKA</span>
                            </h1>
                            <h2 style="margin: 8px 0 0 0; font-size: 20px; color: #222 !important; background: white !important;">PRESUPUESTO</h2>
                            <p style="margin: 5px 0 0 0; font-size: 12px; color: #666 !important; background: white !important;">Fecha: ${formatDate(date)}</p>
                        </div>
                        
                        <!-- DATOS DEL CLIENTE COMPACTOS -->
                        <div style="margin-bottom: 20px; background: white !important;">
                            <h3 style="margin: 0 0 8px 0; font-size: 14px; color: #222 !important; border-bottom: 1px solid #ccc; padding-bottom: 3px; background: white !important;">DATOS DEL CLIENTE</h3>
                            <div style="font-size: 11px; line-height: 1.3; background: white !important; color: black !important; display: flex; flex-wrap: wrap; gap: 15px;">
                                <span style="background: white !important; color: black !important;"><strong>Cliente:</strong> ${escapeHtml(customer)}</span>
                                ${phone ? `<span style="background: white !important; color: black !important;"><strong>Tel:</strong> ${escapeHtml(phone)}</span>` : ''}
                                ${email ? `<span style="background: white !important; color: black !important;"><strong>Email:</strong> ${escapeHtml(email)}</span>` : ''}
                                ${address ? `<span style="background: white !important; color: black !important;"><strong>Dir:</strong> ${escapeHtml(address)}</span>` : ''}
                            </div>
                        </div>
                        
                        <!-- PRODUCTOS -->
                        <div style="margin-bottom: 20px; background: white !important;">
                            <h3 style="margin: 0 0 10px 0; font-size: 14px; color: #222 !important; border-bottom: 1px solid #ccc; padding-bottom: 3px; background: white !important;">DETALLE DE PRODUCTOS</h3>
                            <table style="width: 100%; border-collapse: collapse; font-size: 11px; background: white !important;">
                                <thead>
                                    <tr>
                                        <th style="border: 1px solid #333; padding: 8px; text-align: left; background: #333 !important; color: white !important;">Producto</th>
                                        <th style="border: 1px solid #333; padding: 8px; text-align: center; background: #333 !important; color: white !important; width: 80px;">Cantidad</th>
                                        <th style="border: 1px solid #333; padding: 8px; text-align: right; background: #333 !important; color: white !important; width: 100px;">Precio Unit.</th>
                                        <th style="border: 1px solid #333; padding: 8px; text-align: right; background: #333 !important; color: white !important; width: 100px;">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${quoteItems.map((item, index) => `
                                        <tr>
                                            <td style="border: 1px solid #ccc; padding: 6px; background: white !important; color: black !important;">${escapeHtml(item.productName)}</td>
                                            <td style="border: 1px solid #ccc; padding: 6px; text-align: center; background: white !important; color: black !important;">${item.quantity} ${item.unit}</td>
                                            <td style="border: 1px solid #ccc; padding: 6px; text-align: right; background: white !important; color: black !important;">${formatCurrency(item.price)}</td>
                                            <td style="border: 1px solid #ccc; padding: 6px; text-align: right; background: white !important; color: black !important; font-weight: bold;">${formatCurrency(item.total)}</td>
                                        </tr>
                                    `).join('')}
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" style="border: 1px solid #333; padding: 10px; text-align: right; background: #c9a86a !important; color: white !important; font-weight: bold; font-size: 14px;">TOTAL:</td>
                                        <td style="border: 1px solid #333; padding: 10px; text-align: right; background: #c9a86a !important; color: white !important; font-weight: bold; font-size: 14px;">${formatCurrency(totalAmount)}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        
                        <!-- CONDICIONES COMPACTAS -->
                        <div style="margin-bottom: 25px; padding: 10px; background: #f5f5f5 !important; border-left: 3px solid #c9a86a; font-size: 10px;">
                            <strong style="color: #222 !important; background: #f5f5f5 !important;">📋 CONDICIONES:</strong>
                            <span style="color: #555 !important; background: #f5f5f5 !important;">
                                Validez: 30 días | Pago: A convenir | Entrega: A coordinar
                            </span>
                        </div>
                        
                        <!-- PIE DE PÁGINA MÍNIMO -->
                        <div style="
                            text-align: center; 
                            border-top: 1px solid #333; 
                            padding-top: 15px;
                            background: white !important;
                            color: black !important;
                            margin-top: 30px;
                            font-size: 10px;
                        ">
                            <div style="font-size: 16px; font-weight: bold; margin-bottom: 8px; letter-spacing: 1px; background: white !important;">
                                <span style="color: #222 !important; background: white !important;">REVEST</span><span style="color: #c9a86a !important; background: white !important;">IKA</span>
                            </div>
                            <div style="color: #555 !important; line-height: 1.2; background: white !important;">
                                www.revestika.com.ar | ventas@revestika.com.ar | +54 221 317-6973
                            </div>
                            <button onclick="window.print()" style="
                                background: #333; 
                                color: white; 
                                border: none; 
                                padding: 6px 12px; 
                                border-radius: 4px; 
                                font-size: 10px; 
                                cursor: pointer; 
                                margin-top: 10px;
                            ">🖨️ Imprimir</button>
                        </div>
                    </div>
                `;
                
                const generatedQuote = document.getElementById('generatedQuote');
                if (generatedQuote) {
                    generatedQuote.innerHTML = currentQuoteHTML;
                    generatedQuote.style.display = 'block';
                    generatedQuote.style.background = 'white';
                    generatedQuote.style.backgroundColor = 'white';
                    generatedQuote.style.color = 'black';
                }
                
                const quoteForm = document.getElementById('quoteForm');
                if (quoteForm) quoteForm.reset();
                quoteItems = [];
                updateQuoteItemsDisplay();
                setTodayDates();
                
            } catch (error) {
                console.error('Error generando presupuesto:', error);
                alert('Error al generar el presupuesto');
            }
        }
        // ======== DESCARGA DE PDF DE PRESUPUESTOS ========
        
        function downloadQuotePDF() {
            if (!currentQuoteHTML) {
                alert('Primero genera un presupuesto');
                return;
            }

            try {
                if (typeof window.jspdf === 'undefined') {
                    alert('Error: Librería PDF no disponible. Usa el botón de imprimir.');
                    return;
                }

                const { jsPDF } = window.jspdf;
                
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = currentQuoteHTML;
                tempDiv.style.cssText = `
                    position: absolute;
                    left: -9999px;
                    width: 800px;
                    background: white;
                    font-family: 'Montserrat', sans-serif;
                    padding: 20px;
                `;
                
                const fontLink = document.createElement('link');
                fontLink.href = 'https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap';
                fontLink.rel = 'stylesheet';
                document.head.appendChild(fontLink);
                
                document.body.appendChild(tempDiv);

                setTimeout(() => {
                    html2canvas(tempDiv, {
                        scale: 2,
                        useCORS: true,
                        allowTaint: true,
                        backgroundColor: '#ffffff',
                        width: 800,
                        height: tempDiv.scrollHeight,
                        letterRendering: true,
                        logging: false
                    }).then(canvas => {
                        const imgData = canvas.toDataURL('image/png', 1.0);
                        
                        const pdf = new jsPDF('p', 'mm', 'a4');
                        const imgWidth = 190;
                        const pageHeight = 280;
                        const imgHeight = (canvas.height * imgWidth) / canvas.width;
                        let heightLeft = imgHeight;
                        
                        let position = 10;
                        
                        pdf.addImage(imgData, 'PNG', 10, position, imgWidth, imgHeight);
                        heightLeft -= pageHeight;
                        
                        while (heightLeft >= 0) {
                            position = heightLeft - imgHeight + 10;
                            pdf.addPage();
                            pdf.addImage(imgData, 'PNG', 10, position, imgWidth, imgHeight);
                            heightLeft -= pageHeight;
                        }
                        
                        const customer = document.getElementById('quoteCustomer')?.value || 'Cliente';
                        const today = new Date();
                        const dateStr = today.toISOString().split('T')[0];
                        const filename = `Presupuesto_Revestika_${customer.replace(/\s+/g, '_')}_${dateStr}.pdf`;
                        
                        pdf.save(filename);
                        document.body.removeChild(tempDiv);
                        document.head.removeChild(fontLink);
                        
                        alert('✅ PDF descargado correctamente');
                    }).catch(error => {
                        console.error('Error generating PDF:', error);
                        alert('❌ Error al generar el PDF. Intenta usar el botón de imprimir.');
                        document.body.removeChild(tempDiv);
                        document.head.removeChild(fontLink);
                    });
                }, 1000);
                
            } catch (error) {
                console.error('Error en downloadQuotePDF:', error);
                alert('Error al descargar el PDF. Usa el botón de imprimir.');
            }
        }

        // ======== HISTORIAL GENERAL DE TRANSACCIONES ========
        
        async function updateTransactionsTable() {
            const tbody = document.getElementById('transactionsBody');
            if (!tbody) return;
            
            try {
                tbody.innerHTML = '';
                
                const sortedTransactions = [...filteredTransactions].sort((a, b) => 
                    new Date(b.date) - new Date(a.date)
                );
                
                for (const transaction of sortedTransactions) {
                    // ⭐ APLICAR CONVERSIÓN EN HISTORIAL ⭐
                    const convertedAmount = await convertAmountWithCriteria(
                        transaction.amount, 
                        transaction.date, 
                        transaction.description, 
                        false
                    );
                    
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${formatDate(transaction.date)}</td>
                        <td><span class="${transaction.type === 'income' ? 'income' : 'expense'}">
                            ${transaction.type === 'income' ? '💰 Ingreso' : '💸 Gasto'}
                        </span></td>
                        <td>${transaction.description}</td>
                        <td>${transaction.category}</td>
                        <td><span class="badge badge-${transaction.paymentMethod}">
                            ${transaction.paymentMethod === 'cash' ? '💵 Cash' : '💳 Digital'}
                        </span></td>
                        <td>${transaction.expenseType ? `<span class="badge badge-${transaction.expenseType}">${transaction.expenseType === 'fixed' ? '🔒 Fijo' : '📈 Variable'}</span>` : '-'}</td>
                        <td class="${transaction.type === 'income' ? 'income' : 'expense'}">
                            ${transaction.type === 'income' ? '+' : '-'}${formatCurrencySync(convertedAmount)}
                        </td>
                    `;
                    tbody.appendChild(row);
                }
            } catch (error) {
                console.error('Error actualizando tabla de transacciones:', error);
            }
        }

        function populateCategoryFilter() {
            try {
                const categories = [...new Set(transactions.map(t => t.category))];
                const select = document.getElementById('categoryFilter');
                if (!select) return;
                
                select.innerHTML = '<option value="">Todas las categorías</option>';
                
                categories.forEach(category => {
                    const option = document.createElement('option');
                    option.value = category;
                    option.textContent = category;
                    select.appendChild(option);
                });
            } catch (error) {
                console.error('Error poblando filtro de categorías:', error);
            }
        }

        function filterTransactions() {
            try {
                const typeFilter = document.getElementById('typeFilter')?.value;
                const categoryFilter = document.getElementById('categoryFilter')?.value;
                const searchFilter = document.getElementById('searchFilter')?.value.toLowerCase();
                
                filteredTransactions = transactions.filter(transaction => {
                    const matchesType = !typeFilter || transaction.type === typeFilter;
                    const matchesCategory = !categoryFilter || transaction.category === categoryFilter;
                    const matchesSearch = !searchFilter || 
                        transaction.description.toLowerCase().includes(searchFilter) ||
                        transaction.category.toLowerCase().includes(searchFilter) ||
                        (transaction.customer && transaction.customer.toLowerCase().includes(searchFilter));
                    
                    return matchesType && matchesCategory && matchesSearch;
                });
                
                updateTransactionsTable();
            } catch (error) {
                console.error('Error filtrando transacciones:', error);
            }
        }

        // ======== FILTROS DE INGRESOS ========
        
        function filterIncomes() {
            try {
                const dateFrom = document.getElementById('incomeFilterDateFrom')?.value;
                const dateTo = document.getElementById('incomeFilterDateTo')?.value;
                const paymentMethod = document.getElementById('incomeFilterPayment')?.value;
                const productFilter = document.getElementById('incomeFilterProduct')?.value;
                const customerFilter = document.getElementById('incomeFilterCustomer')?.value.toLowerCase();
                const categoryFilter = document.getElementById('incomeFilterCategory')?.value;

                filteredIncomes = transactions.filter(t => {
                    if (t.type !== 'income') return false;

                    if (dateFrom && t.date < dateFrom) return false;
                    if (dateTo && t.date > dateTo) return false;

                    if (paymentMethod && t.paymentMethod !== paymentMethod) return false;

                    if (productFilter && t.productId != productFilter) return false;

                    if (customerFilter && (!t.customer || !t.customer.toLowerCase().includes(customerFilter))) return false;

                    if (categoryFilter && t.category !== categoryFilter) return false;

                    return true;
                });

                updateIncomeFilterDisplay();
                updateFilteredIncomeTable();

            } catch (error) {
                console.error('Error filtrando ingresos:', error);
            }
        }

        async function updateIncomeFilterDisplay() {
            const summary = document.getElementById('incomeFilterSummary');
            const count = document.getElementById('incomeFilterCount');
            const total = document.getElementById('incomeFilterTotal');
            const details = document.getElementById('incomeFilterDetails');

            if (!summary || !count || !total || !details) return;

            if (filteredIncomes.length > 0) {
                summary.style.display = 'block';
                
                // ⭐ APLICAR CONVERSIÓN AL TOTAL ⭐
                let totalAmount = 0;
                for (const transaction of filteredIncomes) {
                    const convertedAmount = await convertAmountWithCriteria(transaction.amount, transaction.date, transaction.description, false);
                    totalAmount += convertedAmount;
                }
                
                count.textContent = filteredIncomes.length;
                total.textContent = formatCurrencySync(totalAmount);

                // ⭐ APLICAR CONVERSIÓN A DETALLES ⭐
                let cashAmount = 0;
                let digitalAmount = 0;
                
                for (const transaction of filteredIncomes.filter(t => t.paymentMethod === 'cash')) {
                    const convertedAmount = await convertAmountWithCriteria(transaction.amount, transaction.date, transaction.description, false);
                    cashAmount += convertedAmount;
                }
                
                for (const transaction of filteredIncomes.filter(t => t.paymentMethod === 'digital')) {
                    const convertedAmount = await convertAmountWithCriteria(transaction.amount, transaction.date, transaction.description, false);
                    digitalAmount += convertedAmount;
                }
                
                details.innerHTML = `
                    💵 Efectivo: ${formatCurrencySync(cashAmount)} | 
                    💳 Digital: ${formatCurrencySync(digitalAmount)} | 
                    📅 Período mostrado: ${getFilterPeriodText('income')}
                `;
            } else {
                summary.style.display = 'none';
            }
        }

        async function updateFilteredIncomeTable() {
            const tbody = document.getElementById('incomeHistoryBody');
            if (!tbody) return;

            tbody.innerHTML = '';

            const displayData = filteredIncomes.length > 0 ? filteredIncomes : 
                transactions.filter(t => t.type === 'income');

            for (const transaction of displayData.sort((a, b) => new Date(b.date) - new Date(a.date))) {
                // ⭐ APLICAR CONVERSIÓN ⭐
                const convertedAmount = await convertAmountWithCriteria(transaction.amount, transaction.date, transaction.description, false);
                const convertedPricePerUnit = transaction.pricePerUnit ? 
                    await convertAmountWithCriteria(transaction.pricePerUnit, transaction.date, transaction.description, false) : null;
                
                const row = document.createElement('tr');
                if (filteredIncomes.length > 0) {
                    row.style.backgroundColor = '#f0f8ff';
                }
                
                row.innerHTML = `
                    <td>${formatDate(transaction.date)}</td>
                    <td>${escapeHtml(transaction.customer) || '-'}</td>
                    <td>${escapeHtml(transaction.productName) || '-'}</td>
                    <td>${transaction.quantity || '-'}</td>
                    <td>${convertedPricePerUnit ? formatCurrencySync(convertedPricePerUnit) : '-'}</td>
                    <td class="income">+${formatCurrencySync(convertedAmount)}</td>
                    <td><span class="badge badge-${transaction.paymentMethod}">
                        ${transaction.paymentMethod === 'cash' ? '💵 Cash' : '💳 Digital'}
                    </span></td>
                    <td>
                        <button class="delete-btn" onclick="deleteTransactionFromSource(${transaction.id}, 'income')" 
                            title="Eliminar ingreso">
                            🗑️
                        </button>
                    </td>
                `;
                tbody.appendChild(row);
            }
        }

        // ======== FILTROS DE GASTOS ========
        
        function filterExpenses() {
            try {
                const dateFrom = document.getElementById('expenseFilterDateFrom')?.value;
                const dateTo = document.getElementById('expenseFilterDateTo')?.value;
                const paymentMethod = document.getElementById('expenseFilterPayment')?.value;
                const expenseType = document.getElementById('expenseFilterType')?.value;
                const categoryFilter = document.getElementById('expenseFilterCategory')?.value;
                const searchFilter = document.getElementById('expenseFilterSearch')?.value.toLowerCase();

                filteredExpenses = transactions.filter(t => {
                    if (t.type !== 'expense') return false;

                    if (dateFrom && t.date < dateFrom) return false;
                    if (dateTo && t.date > dateTo) return false;

                    if (paymentMethod && t.paymentMethod !== paymentMethod) return false;

                    if (expenseType && t.expenseType !== expenseType) return false;

                    if (categoryFilter && t.category !== categoryFilter) return false;

                    if (searchFilter && (!t.description || !t.description.toLowerCase().includes(searchFilter))) return false;

                    return true;
                });

                updateExpenseFilterDisplay();
                updateFilteredExpenseTable();

            } catch (error) {
                console.error('Error filtrando gastos:', error);
            }
        }

        async function updateExpenseFilterDisplay() {
            const summary = document.getElementById('expenseFilterSummary');
            const count = document.getElementById('expenseFilterCount');
            const total = document.getElementById('expenseFilterTotal');
            const details = document.getElementById('expenseFilterDetails');

            if (!summary || !count || !total || !details) return;

            if (filteredExpenses.length > 0) {
                summary.style.display = 'block';
                
                // ⭐ APLICAR CONVERSIÓN AL TOTAL ⭐
                let totalAmount = 0;
                for (const transaction of filteredExpenses) {
                    const convertedAmount = await convertAmountWithCriteria(transaction.amount, transaction.date, transaction.description, false);
                    totalAmount += convertedAmount;
                }
                
                count.textContent = filteredExpenses.length;
                total.textContent = formatCurrencySync(totalAmount);

                // ⭐ APLICAR CONVERSIÓN A DETALLES ⭐
                let fixedAmount = 0;
                let variableAmount = 0;
                
                for (const transaction of filteredExpenses.filter(t => t.expenseType === 'fixed')) {
                    const convertedAmount = await convertAmountWithCriteria(transaction.amount, transaction.date, transaction.description, false);
                    fixedAmount += convertedAmount;
                }
                
                for (const transaction of filteredExpenses.filter(t => t.expenseType === 'variable')) {
                    const convertedAmount = await convertAmountWithCriteria(transaction.amount, transaction.date, transaction.description, false);
                    variableAmount += convertedAmount;
                }
                
                details.innerHTML = `
                    🔒 Fijos: ${formatCurrencySync(fixedAmount)} | 
                    📈 Variables: ${formatCurrencySync(variableAmount)} | 
                    📅 Período mostrado: ${getFilterPeriodText('expense')}
                `;
            } else {
                summary.style.display = 'none';
            }
        }

        async function updateFilteredExpenseTable() {
            const tbody = document.getElementById('expenseHistoryBody');
            if (!tbody) return;

            tbody.innerHTML = '';

            const displayData = filteredExpenses.length > 0 ? filteredExpenses : 
                transactions.filter(t => t.type === 'expense');

            for (const transaction of displayData.sort((a, b) => new Date(b.date) - new Date(a.date))) {
                // ⭐ APLICAR CONVERSIÓN ⭐
                const convertedAmount = await convertAmountWithCriteria(transaction.amount, transaction.date, transaction.description, false);
                
                const row = document.createElement('tr');
                if (filteredExpenses.length > 0) {
                    row.style.backgroundColor = '#fff8f0';
                }
                
                row.innerHTML = `
                    <td>${formatDate(transaction.date)}</td>
                    <td>${escapeHtml(transaction.category)}</td>
                    <td>${escapeHtml(transaction.description)}</td>
                    <td class="expense">-${formatCurrencySync(convertedAmount)}</td>
                    <td>${transaction.expenseType ? `<span class="badge badge-${transaction.expenseType}">${transaction.expenseType === 'fixed' ? '🔒 Fijo' : '📈 Variable'}</span>` : '-'}</td>
                    <td><span class="badge badge-${transaction.paymentMethod}">
                        ${transaction.paymentMethod === 'cash' ? '💵 Cash' : '💳 Digital'}
                    </span></td>
                    <td>
                        <button class="delete-btn" onclick="deleteTransactionFromSource(${transaction.id}, 'expense')" 
                            title="Eliminar gasto">
                            🗑️
                        </button>
                    </td>
                `;
                tbody.appendChild(row);
            }
        }

        // ======== FUNCIONES DE UTILIDAD PARA FILTROS ========
        
        function clearIncomeFilters() {
            document.getElementById('incomeFilterDateFrom').value = '';
            document.getElementById('incomeFilterDateTo').value = '';
            document.getElementById('incomeFilterPayment').value = '';
            document.getElementById('incomeFilterProduct').value = '';
            document.getElementById('incomeFilterCustomer').value = '';
            document.getElementById('incomeFilterCategory').value = '';
            
            filteredIncomes = [];
            document.getElementById('incomeFilterSummary').style.display = 'none';
            updateFilteredIncomeTable();
        }

        function clearExpenseFilters() {
            document.getElementById('expenseFilterDateFrom').value = '';
            document.getElementById('expenseFilterDateTo').value = '';
            document.getElementById('expenseFilterPayment').value = '';
            document.getElementById('expenseFilterType').value = '';
            document.getElementById('expenseFilterCategory').value = '';
            document.getElementById('expenseFilterSearch').value = '';
            
            filteredExpenses = [];
            document.getElementById('expenseFilterSummary').style.display = 'none';
            updateFilteredExpenseTable();
        }

        function getFilterPeriodText(type) {
            const dateFromId = type === 'income' ? 'incomeFilterDateFrom' : 'expenseFilterDateFrom';
            const dateToId = type === 'income' ? 'incomeFilterDateTo' : 'expenseFilterDateTo';
            
            const dateFrom = document.getElementById(dateFromId)?.value;
            const dateTo = document.getElementById(dateToId)?.value;
            
            if (dateFrom && dateTo) {
                return `${formatDate(dateFrom)} - ${formatDate(dateTo)}`;
            } else if (dateFrom) {
                return `Desde ${formatDate(dateFrom)}`;
            } else if (dateTo) {
                return `Hasta ${formatDate(dateTo)}`;
            }
            return 'Todo el historial';
        }

        function exportIncomeData() {
            if (filteredIncomes.length === 0) {
                alert('No hay datos filtrados para exportar');
                return;
            }
            
            const csv = generateIncomeCSV(filteredIncomes);
            downloadCSV(csv, 'ingresos_filtrados.csv');
        }

        function exportExpenseData() {
            if (filteredExpenses.length === 0) {
                alert('No hay datos filtrados para exportar');
                return;
            }
            
            const csv = generateExpenseCSV(filteredExpenses);
            downloadCSV(csv, 'gastos_filtrados.csv');
        }

        function generateIncomeCSV(data) {
            const headers = ['Fecha', 'Cliente', 'Producto', 'Cantidad', 'Precio Unit.', 'Total', 'Método Pago'];
            const rows = data.map(t => [
                t.date,
                t.customer || '',
                t.productName || '',
                t.quantity || '',
                t.pricePerUnit || '',
                t.amount,
                t.paymentMethod === 'cash' ? 'Efectivo' : 'Digital'
            ]);
            
            return [headers, ...rows].map(row => 
                row.map(cell => `"${cell}"`).join(',')
            ).join('\\n');
        }

        function generateExpenseCSV(data) {
            const headers = ['Fecha', 'Categoría', 'Descripción', 'Monto', 'Tipo', 'Método Pago'];
            const rows = data.map(t => [
                t.date,
                t.category,
                t.description,
                t.amount,
                t.expenseType === 'fixed' ? 'Fijo' : 'Variable',
                t.paymentMethod === 'cash' ? 'Efectivo' : 'Digital'
            ]);
            
            return [headers, ...rows].map(row => 
                row.map(cell => `"${cell}"`).join(',')
            ).join('\\n');
        }

        function downloadCSV(csv, filename) {
            const blob = new Blob([csv], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = filename;
            a.click();
            window.URL.revokeObjectURL(url);
        }

        function initializeFilters() {
            try {
                const productSelect = document.getElementById('incomeFilterProduct');
                if (productSelect) {
                    productSelect.innerHTML = '<option value="">Todos los productos</option>';
                    products.forEach(product => {
                        const option = document.createElement('option');
                        option.value = product.id;
                        option.textContent = product.name;
                        productSelect.appendChild(option);
                    });
                }
                
                console.log('Filtros inicializados correctamente');
            } catch (error) {
                console.error('Error inicializando filtros:', error);
            }
        }
    
        // ======== EVENTOS Y CONFIGURACIÓN ========
        
        function setupCalculationEvents() {
            try {
                const quantity = document.getElementById('quantity');
                const pricePerUnit = document.getElementById('pricePerUnit');
                
                if (quantity) {
                    quantity.addEventListener('input', calculateTotal);
                }
                if (pricePerUnit) {
                    pricePerUnit.addEventListener('input', calculateTotal);
                }
            } catch (error) {
                console.error('Error configurando eventos de cálculo:', error);
            }
        }

        function updateProductPrice() {
            const select = document.getElementById('productSelect');
            const priceInput = document.getElementById('pricePerUnit');
            
            if (!select || !priceInput) return;
            
            try {
                if (select.value) {
                    const selectedOption = select.options[select.selectedIndex];
                    if (selectedOption && selectedOption.dataset.price) {
                        priceInput.value = selectedOption.dataset.price;
                        calculateTotal();
                    }
                }
            } catch (error) {
                console.error('Error actualizando precio del producto:', error);
            }
        }

        function calculateTotal() {
            try {
                const quantity = parseFloat(document.getElementById('quantity')?.value) || 0;
                const pricePerUnit = parseFloat(document.getElementById('pricePerUnit')?.value) || 0;
                const total = quantity * pricePerUnit;
                const incomeAmount = document.getElementById('incomeAmount');
                
                if (incomeAmount) {
                    incomeAmount.value = total.toFixed(2);
                }
            } catch (error) {
                console.error('Error calculando total:', error);
            }
        }

        // ======== EVENTOS DE VENTANA ========
        
        window.onclick = function(event) {
            try {
                const editModal = document.getElementById('editProductModal');
            } catch (error) {
                console.error('Error en evento window.onclick:', error);
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            console.log('🚀 DOM cargado, iniciando aplicación...');
            
            try {
                // 1. Verificar elementos críticos
                const dashboard = document.getElementById('dashboard');
                if (!dashboard) {
                    throw new Error('Dashboard no encontrado - HTML incompleto');
                }
                
                console.log('✅ Elementos verificados');
                
                // 2. Configurar fechas INMEDIATAMENTE
                setTodayDates();
                
                // 3. Configurar eventos ANTES de inicializar datos
                console.log('Configurando eventos...');
                setupCalculationEvents();
                setupProductForm();
                setupEditProductForm();
                setupIncomeForm();
                setupExpenseForm();
                setupInvestmentForm();
                setupMultiProductEvents();
                
                // 4. Configurar event listeners para los botones de moneda
                const arsBtn = document.getElementById('currencyARS');
                const usdBtn = document.getElementById('currencyUSD');
                
                if (arsBtn) arsBtn.addEventListener('click', () => changeCurrency('ARS'));
                if (usdBtn) usdBtn.addEventListener('click', () => changeCurrency('USD'));
                
                console.log('✅ Eventos configurados');
                
                // 5. Inicializar aplicación
                initializeApp().then(() => {
                    console.log('✅ Aplicación lista');
                    // Mostrar dashboard DESPUÉS de que todo esté listo
                    showTab('dashboard');
                }).catch(error => {
                    console.error('❌ Error en initializeApp:', error);
                    alert('Error al inicializar: ' + error.message);
                });
                
            } catch (error) {
                console.error('❌ Error crítico en DOMContentLoaded:', error);
                alert('Error crítico. Verifica que el HTML esté completo: ' + error.message);
            }
        });

        // ======== FUNCIONES DE RESPALDO ========
        
        function forceRefresh() {
            console.log('Forzando actualización...');
            try {
                updateAllDisplays();
                console.log('Actualización forzada completada');
            } catch (error) {
                console.error('Error en actualización forzada:', error);
            }
        }

        // Ejecutar actualización forzada después de 2 segundos como respaldo
        setTimeout(forceRefresh, 2000);

        // ======== ALIASES PARA COMPATIBILIDAD ========
        
        // Mantener alias para evitar errores de referencia
        window.updateDashboardWithCurrency = updateDashboard;
        window.updateBalancesWithCurrency = updateBalances;
        window.updateInvestmentDisplayWithCurrency = updateInvestmentDisplay;
        window.updateIncomeHistoryWithCurrency = updateIncomeHistory;
        window.updateExpenseHistoryWithCurrency = updateExpenseHistory;

        console.log('🚀 SISTEMA DE CONVERSIÓN USD/ARS COMPLETAMENTE IMPLEMENTADO');
        console.log('✅ REGLA 1: Aportes específicos (1er/2do pago costa libre) usan TC fijos');
        console.log('✅ REGLA 2: Transacciones históricas usan TC de la fecha');
        console.log('✅ REGLA 3: Saldos usan TC del día actual');
        console.log('✅ Botones ARS/USD funcionales en header');
        console.log('✅ Conversión automática en todas las pantallas');
        console.log('✅ 100% de funcionalidades mantenidas');

        // ======== FUNCIONES DE GUARDADO ESPECÍFICAS ========

        async function saveTaxFixedItems() {
            try {
                const result = await saveToDatabase('tax_fixed_items', taxFixedItems);
                if (!result.success) {
                    throw new Error(result.error || 'Error guardando items de impuestos/gastos fijos');
                }
                return result;
            } catch (error) {
                console.error('Error en saveTaxFixedItems:', error);
                throw error;
            }
        }

        async function saveTaxFixedPayments() {
            try {
                const result = await saveToDatabase('tax_fixed_payments', taxFixedPayments);
                if (!result.success) {
                    throw new Error(result.error || 'Error guardando pagos de impuestos/gastos fijos');
                }
                return result;
            } catch (error) {
                console.error('Error en saveTaxFixedPayments:', error);
                throw error;
            }
        }

        // ======== CONFIGURACIÓN DE FORMULARIOS ========

        function setupTaxItemForm() {
            const form = document.getElementById('taxItemForm');
            if (!form) return;
            
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                try {
                    const taxItem = {
                        id: Date.now(),
                        name: document.getElementById('taxName')?.value || '',
                        type: 'tax',
                        frequency: document.getElementById('taxFrequency')?.value || '',
                        amount: parseFloat(document.getElementById('taxAmount')?.value) || 0,
                        description: document.getElementById('taxDescription')?.value || ''
                    };
                    
                    if (!taxItem.name || !taxItem.frequency) {
                        alert('Por favor completa los campos obligatorios');
                        return;
                    }
                    
                    taxFixedItems.push(taxItem);
                    await saveTaxFixedItems();
                    updateTaxItemsList();
                    updateTaxesDisplay();
                    
                    this.reset();
                    alert('✅ Impuesto agregado correctamente');
                } catch (error) {
                    console.error('Error agregando impuesto:', error);
                    alert('Error al agregar el impuesto');
                }
            });
        }

        function setupFixedExpenseItemForm() {
            const form = document.getElementById('fixedExpenseItemForm');
            if (!form) return;
            
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                try {
                    const fixedExpenseItem = {
                        id: Date.now(),
                        name: document.getElementById('fixedExpenseName')?.value || '',
                        type: 'fixed_expense',
                        frequency: document.getElementById('fixedExpenseFrequency')?.value || '',
                        amount: parseFloat(document.getElementById('fixedExpenseAmount')?.value) || 0,
                        description: document.getElementById('fixedExpenseDescription')?.value || ''
                    };
                    
                    if (!fixedExpenseItem.name || !fixedExpenseItem.frequency) {
                        alert('Por favor completa los campos obligatorios');
                        return;
                    }
                    
                    taxFixedItems.push(fixedExpenseItem);
                    await saveTaxFixedItems();
                    updateTaxItemsList();
                    updateTaxesDisplay();
                    
                    this.reset();
                    alert('✅ Gasto fijo agregado correctamente');
                } catch (error) {
                    console.error('Error agregando gasto fijo:', error);
                    alert('Error al agregar el gasto fijo');
                }
            });
        }

        // ======== FUNCIONES DE DISPLAY ========

        function updateTaxItemsList() {
            const taxContainer = document.getElementById('taxItemsList');
            const fixedExpenseContainer = document.getElementById('fixedExpenseItemsList');
            
            if (!taxContainer || !fixedExpenseContainer) return;
            
            try {
                // Limpiar contenedores
                taxContainer.innerHTML = '';
                fixedExpenseContainer.innerHTML = '';
                
                // Filtrar y mostrar impuestos
                const taxes = taxFixedItems.filter(item => item.type === 'tax');
                taxes.forEach(item => {
                    const div = document.createElement('div');
                    div.className = 'product-item';
                    div.style.borderLeftColor = '#e74c3c';
                    div.innerHTML = `
                        <div class="product-actions">
                            <button class="delete-btn" onclick="deleteTaxFixedItem(${item.id})">🗑️</button>
                        </div>
                        <h4>🏛️ ${escapeHtml(item.name)}</h4>
                        <p><strong>Frecuencia:</strong> ${item.frequency === 'monthly' ? '📅 Mensual' : '📆 Anual'}</p>
                        <p><strong>Monto estimado:</strong> ${formatCurrency(item.amount)}</p>
                        <p><strong>Descripción:</strong> ${escapeHtml(item.description)}</p>
                    `;
                    taxContainer.appendChild(div);
                });
                
                // Filtrar y mostrar gastos fijos
                const fixedExpenses = taxFixedItems.filter(item => item.type === 'fixed_expense');
                fixedExpenses.forEach(item => {
                    const div = document.createElement('div');
                    div.className = 'product-item';
                    div.style.borderLeftColor = '#27ae60';
                    div.innerHTML = `
                        <div class="product-actions">
                            <button class="delete-btn" onclick="deleteTaxFixedItem(${item.id})">🗑️</button>
                        </div>
                        <h4>🔒 ${escapeHtml(item.name)}</h4>
                        <p><strong>Frecuencia:</strong> ${item.frequency === 'monthly' ? '📅 Mensual' : '📆 Anual'}</p>
                        <p><strong>Monto estimado:</strong> ${formatCurrency(item.amount)}</p>
                        <p><strong>Descripción:</strong> ${escapeHtml(item.description)}</p>
                    `;
                    fixedExpenseContainer.appendChild(div);
                });
                
                // Mostrar mensajes si están vacíos
                if (taxes.length === 0) {
                    taxContainer.innerHTML = '<p style="color: #666; text-align: center; padding: 20px;">No hay impuestos configurados</p>';
                }
                
                if (fixedExpenses.length === 0) {
                    fixedExpenseContainer.innerHTML = '<p style="color: #666; text-align: center; padding: 20px;">No hay gastos fijos configurados</p>';
                }
                
            } catch (error) {
                console.error('Error actualizando listas de items:', error);
            }
        }

        function initializeTaxYearSelect() {
            const yearSelect = document.getElementById('taxYear');
            if (!yearSelect) return;
            
            try {
                const currentYear = new Date().getFullYear();
                yearSelect.innerHTML = '';
                
                // Agregar años (año anterior, actual y siguientes 2)
                for (let year = currentYear - 1; year <= currentYear + 2; year++) {
                    const option = document.createElement('option');
                    option.value = year;
                    option.textContent = year;
                    if (year === currentYear) option.selected = true;
                    yearSelect.appendChild(option);
                }
                
                currentTaxYear = currentYear;
                
                // Establecer mes actual
                const monthSelect = document.getElementById('taxMonth');
                if (monthSelect) {
                    monthSelect.value = new Date().getMonth() + 1;
                    currentTaxMonth = new Date().getMonth() + 1;
                }
            } catch (error) {
                console.error('Error inicializando selector de año:', error);
            }
        }

        function updateTaxesDisplay() {
            try {
                const yearSelect = document.getElementById('taxYear');
                const monthSelect = document.getElementById('taxMonth');
                
                if (!yearSelect || !monthSelect) return;
                
                currentTaxYear = parseInt(yearSelect.value);
                currentTaxMonth = parseInt(monthSelect.value);
                
                updateCurrentPeriodDisplay();
                updateTaxesChecklist();
                updateTaxesSummary();
            } catch (error) {
                console.error('Error actualizando display de impuestos:', error);
            }
        }

        function updateCurrentPeriodDisplay() {
            const display = document.getElementById('currentPeriodDisplay');
            if (!display) return;
            
            try {
                const months = [
                    'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                    'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
                ];
                
                display.textContent = `${months[currentTaxMonth - 1]} ${currentTaxYear}`;
            } catch (error) {
                console.error('Error actualizando período actual:', error);
            }
        }

        function updateTaxesChecklist() {
            const taxContainer = document.getElementById('taxChecklistItems');
            const fixedExpenseContainer = document.getElementById('fixedExpenseChecklistItems');
            
            if (!taxContainer || !fixedExpenseContainer) return;
            
            try {
                taxContainer.innerHTML = '';
                fixedExpenseContainer.innerHTML = '';
                
                // Filtrar items aplicables al período
                const taxes = taxFixedItems.filter(item => 
                    item.type === 'tax' && 
                    (item.frequency === 'monthly' || (item.frequency === 'annual' && currentTaxMonth === 12))
                );
                
                const fixedExpenses = taxFixedItems.filter(item => 
                    item.type === 'fixed_expense' && 
                    (item.frequency === 'monthly' || (item.frequency === 'annual' && currentTaxMonth === 12))
                );
                
                // Crear checklist para impuestos
                taxes.forEach(item => {
                    const payment = getPaymentStatus(item.id, currentTaxYear, item.frequency === 'monthly' ? currentTaxMonth : null);
                    const div = createChecklistItem(item, payment);
                    taxContainer.appendChild(div);
                });
                
                // Crear checklist para gastos fijos
                fixedExpenses.forEach(item => {
                    const payment = getPaymentStatus(item.id, currentTaxYear, item.frequency === 'monthly' ? currentTaxMonth : null);
                    const div = createChecklistItem(item, payment);
                    fixedExpenseContainer.appendChild(div);
                });
                
                // Mostrar mensajes si están vacíos
                if (taxes.length === 0) {
                    taxContainer.innerHTML = '<p style="color: #666; text-align: center; padding: 20px;">No hay impuestos para este período</p>';
                }
                
                if (fixedExpenses.length === 0) {
                    fixedExpenseContainer.innerHTML = '<p style="color: #666; text-align: center; padding: 20px;">No hay gastos fijos para este período</p>';
                }
                
            } catch (error) {
                console.error('Error actualizando checklist:', error);
            }
        }

        function createChecklistItem(item, payment) {
            const div = document.createElement('div');
            div.style.cssText = `
                background: white;
                padding: 15px;
                border-radius: 8px;
                margin-bottom: 10px;
                border-left: 4px solid ${item.type === 'tax' ? '#e74c3c' : '#27ae60'};
                display: flex;
                justify-content: space-between;
                align-items: center;
            `;
            
            const isChecked = payment && payment.is_paid;
            const paidInfo = payment && payment.paid_date ? `(Pagado: ${formatDate(payment.paid_date)})` : '';
            
            div.innerHTML = `
                <div>
                    <strong>${item.type === 'tax' ? '🏛️' : '🔒'} ${escapeHtml(item.name)}</strong>
                    <br>
                    <span style="color: #666; font-size: 14px;">
                        ${item.frequency === 'monthly' ? '📅 Mensual' : '📆 Anual'} 
                        ${item.amount > 0 ? `- ${formatCurrency(item.amount)}` : ''} 
                        ${paidInfo}
                    </span>
                    ${payment && payment.notes ? `<br><span style="color: #999; font-size: 12px;">📝 ${escapeHtml(payment.notes)}</span>` : ''}
                </div>
                <div style="display: flex; align-items: center; gap: 10px;">
                    <label style="display: flex; align-items: center; cursor: pointer;">
                        <input type="checkbox" 
                            ${isChecked ? 'checked' : ''} 
                            onchange="togglePaymentStatus(${item.id}, '${item.frequency}', this.checked)"
                            style="margin-right: 8px; transform: scale(1.2);">
                        <span style="font-weight: bold; color: ${isChecked ? '#27ae60' : '#e74c3c'};">
                            ${isChecked ? '✅ Pagado' : '⏳ Pendiente'}
                        </span>
                    </label>
                </div>
            `;
            
            return div;
        }

        function updateTaxesSummary() {
            try {
                const pendingCount = document.getElementById('pendingTaxesCount');
                const paidCount = document.getElementById('paidTaxesCount');
                const estimatedTotal = document.getElementById('estimatedTotalAmount');
                
                if (!pendingCount || !paidCount || !estimatedTotal) return;
                
                // Filtrar items aplicables
                const applicableItems = taxFixedItems.filter(item => 
                    item.frequency === 'monthly' || (item.frequency === 'annual' && currentTaxMonth === 12)
                );
                
                let pending = 0;
                let paid = 0;
                let total = 0;
                
                applicableItems.forEach(item => {
                    const payment = getPaymentStatus(item.id, currentTaxYear, item.frequency === 'monthly' ? currentTaxMonth : null);
                    
                    if (payment && payment.is_paid) {
                        paid++;
                    } else {
                        pending++;
                    }
                    
                    total += item.amount || 0;
                });
                
                pendingCount.textContent = pending;
                paidCount.textContent = paid;
                estimatedTotal.textContent = formatCurrency(total);
                
            } catch (error) {
                console.error('Error actualizando resumen:', error);
            }
        }

        // ======== FUNCIONES DE GESTIÓN DE PAGOS ========

        function getPaymentStatus(itemId, year, month) {
            return taxFixedPayments.find(payment => 
                payment.item_id == itemId && 
                payment.year == year && 
                payment.month == month
            );
        }

        async function togglePaymentStatus(itemId, frequency, isPaid) {
            try {
                const month = frequency === 'monthly' ? currentTaxMonth : null;
                const existingPaymentIndex = taxFixedPayments.findIndex(payment => 
                    payment.item_id == itemId && 
                    payment.year == currentTaxYear && 
                    payment.month == month
                );
                
                const paymentData = {
                    item_id: itemId,
                    year: currentTaxYear,
                    month: month,
                    is_paid: isPaid,
                    paid_date: isPaid ? new Date().toISOString().split('T')[0] : null,
                    transaction_id: null,
                    notes: isPaid ? 'Marcado manualmente como pagado' : null
                };
                
                if (existingPaymentIndex >= 0) {
                    taxFixedPayments[existingPaymentIndex] = paymentData;
                } else {
                    taxFixedPayments.push(paymentData);
                }
                
                await saveTaxFixedPayments();
                updateTaxesDisplay();
                
                // ⭐ NUEVO: Actualizar dashboard también
                await updateTaxIndicatorsInDashboard();
                
            } catch (error) {
                console.error('Error actualizando estado de pago:', error);
                alert('Error al actualizar el estado de pago');
            }
        }

        // ======== FUNCIÓN PARA MARCAR AUTOMÁTICAMENTE CUANDO SE REGISTRA UN GASTO ========

        async function checkAndMarkTaxPayments(expenseDescription, expenseCategory, expenseDate, transactionId) {
            try {
                const expenseYear = new Date(expenseDate).getFullYear();
                const expenseMonth = new Date(expenseDate).getMonth() + 1;
                
                // Buscar items que coincidan con la descripción o categoría
                const matchingItems = taxFixedItems.filter(item => {
                    const itemName = item.name.toLowerCase();
                    const description = expenseDescription.toLowerCase();
                    const category = expenseCategory.toLowerCase();
                    
                    return description.includes(itemName) || 
                        category.includes(itemName) ||
                        itemName.includes(category);
                });
                
                for (const item of matchingItems) {
                    const month = item.frequency === 'monthly' ? expenseMonth : null;
                    
                    // Verificar si ya está marcado como pagado
                    const existingPayment = getPaymentStatus(item.id, expenseYear, month);
                    
                    if (!existingPayment || !existingPayment.is_paid) {
                        const paymentData = {
                            item_id: item.id,
                            year: expenseYear,
                            month: month,
                            is_paid: true,
                            paid_date: expenseDate,
                            transaction_id: transactionId,
                            notes: `Marcado automáticamente por gasto: ${expenseDescription}`
                        };
                        
                        const existingIndex = taxFixedPayments.findIndex(payment => 
                            payment.item_id == item.id && 
                            payment.year == expenseYear && 
                            payment.month == month
                        );
                        
                        if (existingIndex >= 0) {
                            taxFixedPayments[existingIndex] = paymentData;
                        } else {
                            taxFixedPayments.push(paymentData);
                        }
                    }
                }
                
                if (matchingItems.length > 0) {
                    await saveTaxFixedPayments();
                    console.log(`✅ Marcados automáticamente ${matchingItems.length} items como pagados`);
                }
                
            } catch (error) {
                console.error('Error en marcado automático:', error);
            }
        }

        // ======== FUNCIONES DE UTILIDAD ========

        async function deleteTaxFixedItem(id) {
            if (!confirm('¿Estás seguro de que quieres eliminar este item?')) {
                return;
            }
            
            try {
                const originalItems = [...taxFixedItems];
                taxFixedItems = taxFixedItems.filter(item => item.id !== id);
                
                // También eliminar pagos relacionados
                taxFixedPayments = taxFixedPayments.filter(payment => payment.item_id != id);
                
                await saveTaxFixedItems();
                await saveTaxFixedPayments();
                updateTaxItemsList();
                updateTaxesDisplay();
                
                alert('✅ Item eliminado correctamente');
            } catch (error) {
                console.error('Error eliminando item:', error);
                taxFixedItems = originalItems;
                alert('Error al eliminar el item');
            }
        }

        function goToCurrentPeriod() {
            const yearSelect = document.getElementById('taxYear');
            const monthSelect = document.getElementById('taxMonth');
            
            if (yearSelect && monthSelect) {
                const now = new Date();
                yearSelect.value = now.getFullYear();
                monthSelect.value = now.getMonth() + 1;
                updateTaxesDisplay();
            }
        }

        // ======== INICIALIZACIÓN DE LA PESTAÑA ========

        async function initializeTaxesTab() {
            try {
                console.log('🧾 Inicializando pestaña de impuestos...');
                
                // Cargar datos desde BD
                taxFixedItems = await loadFromDatabase('tax_fixed_items') || [];
                taxFixedPayments = await loadFromDatabase('tax_fixed_payments') || [];
                
                // Configurar formularios
                setupTaxItemForm();
                setupFixedExpenseItemForm();
                
                // Inicializar displays
                initializeTaxYearSelect();
                updateTaxItemsList();
                updateTaxesDisplay();
                
                // ⭐ NUEVO: Actualizar indicadores en dashboard
                await updateTaxIndicatorsInDashboard();
                
                console.log('✅ Pestaña de impuestos inicializada correctamente');
            } catch (error) {
                console.error('❌ Error inicializando pestaña de impuestos:', error);
            }
        }


        // ======== ACTUALIZACIÓN DE LA FUNCIÓN showTab ========
                /*
        } else if (tabName === 'taxes') {
            initializeTaxesTab();
        */

        // ======== MODIFICACIÓN DEL FORMULARIO DE GASTOS ========
        // Actualizar la función setupExpenseForm para incluir el marcado automático

        // En setupExpenseForm, después de await saveTransactions(); agregar:
        /*
        // Marcar automáticamente impuestos/gastos fijos si coinciden
        await checkAndMarkTaxPayments(
            transaction.description,
            transaction.category,
            transaction.date,
            transaction.id
        );
        */

            console.log('🧾 SISTEMA DE IMPUESTOS Y GASTOS FIJOS IMPLEMENTADO');
            console.log('✅ Gestión manual de items configurables');
            console.log('✅ Checklist mensual y anual');
            console.log('✅ Marcado automático desde gastos');
            console.log('✅ Separación entre impuestos y gastos fijos');
            console.log('✅ Resumen y estadísticas del período');

        async function updateTaxIndicatorsInDashboard() {
            try {
                const now = new Date();
                const currentYear = now.getFullYear();
                const currentMonth = now.getMonth() + 1;
                
                // Período actual
                const months = [
                    'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                    'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
                ];
                
                const currentPeriodEl = document.getElementById('currentTaxPeriod');
                if (currentPeriodEl) {
                    currentPeriodEl.textContent = `${months[currentMonth - 1]} ${currentYear}`;
                }
                
                // Verificar que tenemos los datos cargados
                if (!taxFixedItems || !Array.isArray(taxFixedItems)) {
                    console.log('📊 Datos de impuestos no disponibles en dashboard');
                    return;
                }
                
                // Calcular indicadores
                let pendingCount = 0;
                let paidThisMonthCount = 0;
                let overdueCount = 0;
                
                // Items aplicables este mes
                const applicableThisMonth = taxFixedItems.filter(item => 
                    item.frequency === 'monthly' || (item.frequency === 'annual' && currentMonth === 12)
                );
                
                // Revisar estado de cada item aplicable
                applicableThisMonth.forEach(item => {
                    const payment = getPaymentStatus(item.id, currentYear, item.frequency === 'monthly' ? currentMonth : null);
                    
                    if (payment && payment.is_paid) {
                        paidThisMonthCount++;
                    } else {
                        pendingCount++;
                    }
                });
                
                // Calcular impagos de meses anteriores
                // ⭐ FECHA DE INICIO DEL NEGOCIO: Mayo 2025
                const businessStartYear = 2025;
                const businessStartMonth = 5;
                
                // Función para verificar si un período es válido (posterior al inicio del negocio)
                function isPeriodValid(year, month) {
                    if (year > businessStartYear) return true;
                    if (year === businessStartYear && month >= businessStartMonth) return true;
                    return false;
                }
                
                // Calcular impagos de meses anteriores (solo desde mayo 2025)
                for (let month = 1; month < currentMonth; month++) {
                    // Solo revisar si el período es válido
                    if (!isPeriodValid(currentYear, month)) continue;
                    
                    const applicableItems = taxFixedItems.filter(item => 
                        item.frequency === 'monthly' || (item.frequency === 'annual' && month === 12)
                    );
                    
                    applicableItems.forEach(item => {
                        const payment = getPaymentStatus(item.id, currentYear, item.frequency === 'monthly' ? month : null);
                        
                        if (!payment || !payment.is_paid) {
                            overdueCount++;
                        }
                    });
                }
                
                // También revisar años anteriores para anuales (solo desde 2025)
                for (let year = businessStartYear; year < currentYear; year++) {
                    // Para años anteriores a 2025, no revisar nada
                    if (year < businessStartYear) continue;
                    
                    const annualItems = taxFixedItems.filter(item => item.frequency === 'annual');
                    
                    annualItems.forEach(item => {
                        const payment = getPaymentStatus(item.id, year, null);
                        
                        if (!payment || !payment.is_paid) {
                            overdueCount++;
                        }
                    });
                }
                
                // Actualizar elementos del dashboard
                const pendingEl = document.getElementById('pendingTaxCount');
                const paidEl = document.getElementById('paidThisMonthCount');
                const overdueEl = document.getElementById('overdueTaxCount');
                
                if (pendingEl) pendingEl.textContent = pendingCount;
                if (paidEl) paidEl.textContent = paidThisMonthCount;
                if (overdueEl) overdueEl.textContent = overdueCount;
                
                console.log(`📊 Indicadores fiscales actualizados: ${pendingCount} pendientes, ${paidThisMonthCount} pagados, ${overdueCount} vencidos`);
                
            } catch (error) {
                console.error('❌ Error actualizando indicadores fiscales en dashboard:', error);
            }
            console.log('🎯 INDICADORES FISCALES MOVIDOS AL DASHBOARD');
            console.log('✅ Nueva cuarta fila en dashboard');
            console.log('✅ Período actual, pendientes, pagados, vencidos');
            console.log('✅ Se actualiza automáticamente');
            console.log('✅ Eliminado "total estimado"');
        }
        // ======== SISTEMA DE CONTROL DE FACTURACIÓN ========

        // Función para actualizar estado de facturación
        async function updateInvoiceStatus(transactionId, invoiced) {
            try {
                console.log(`📋 Actualizando estado de facturación: ${transactionId} -> ${invoiced}`);
                
                const response = await fetch('api.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        action: 'update_invoice_status',
                        transaction_id: transactionId,
                        invoiced: invoiced
                    })
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const result = await response.json();
                
                if (!result.success) {
                    throw new Error(result.error || 'Error actualizando estado de facturación');
                }

                // Actualizar el estado en la memoria local
                const transaction = transactions.find(t => t.id == transactionId);
                if (transaction) {
                    transaction.invoiced = invoiced ? 1 : 0;
                }

                // Actualizar displays
                await updateInvoiceDashboardIndicator();
                
                console.log(`✅ Estado de facturación actualizado: ${invoiced ? 'Facturada' : 'No facturada'}`);
                
            } catch (error) {
                console.error('❌ Error actualizando estado de facturación:', error);
                alert('Error al actualizar el estado de facturación: ' + error.message);
                
                // Revertir el checkbox si hay error
                const checkbox = document.querySelector(`input[data-transaction-id="${transactionId}"]`);
                if (checkbox) {
                    checkbox.checked = !checkbox.checked;
                }
            }
        }

        // Función para calcular ventas pendientes de facturar
        function calculatePendingInvoices() {
            try {
                // Filtrar solo ventas digitales no facturadas
                const digitalSales = transactions.filter(t => 
                    t.type === 'income' && 
                    t.paymentMethod === 'digital' && 
                    (!t.invoiced || t.invoiced === 0) &&
                    t.category !== 'Aportes de Capital' // Excluir aportes
                );

                if (digitalSales.length === 0) {
                    return 0;
                }

                // Agrupar por cliente + fecha + saleTimestamp
                const salesGroups = {};
                
                digitalSales.forEach(sale => {
                    // Crear clave única para agrupar ventas del mismo cliente/día/venta
                    const groupKey = `${sale.customer || 'sin-cliente'}_${sale.date}_${sale.saleTimestamp || sale.id}`;
                    
                    if (!salesGroups[groupKey]) {
                        salesGroups[groupKey] = {
                            customer: sale.customer,
                            date: sale.date,
                            saleTimestamp: sale.saleTimestamp,
                            items: []
                        };
                    }
                    
                    salesGroups[groupKey].items.push(sale);
                });

                // Contar grupos únicos (cada grupo = 1 venta a facturar)
                const pendingInvoicesCount = Object.keys(salesGroups).length;
                
                console.log(`📋 Ventas pendientes de facturar: ${pendingInvoicesCount} (${digitalSales.length} items)`);
                
                return pendingInvoicesCount;
                
            } catch (error) {
                console.error('❌ Error calculando ventas pendientes:', error);
                return 0;
            }
        }

        // Función para actualizar el indicador en dashboard
        async function updateInvoiceDashboardIndicator() {
            try {
                const pendingCount = calculatePendingInvoices();
                const element = document.getElementById('pendingInvoicesCount');
                
                if (element) {
                    element.textContent = pendingCount;
                }
                
                console.log(`📊 Dashboard actualizado: ${pendingCount} ventas por facturar`);
                
            } catch (error) {
                console.error('❌ Error actualizando indicador de dashboard:', error);
            }
        }

        // Función para mostrar detalle de ventas pendientes
        function showPendingInvoicesDetail() {
            try {
                const digitalSales = transactions.filter(t => 
                    t.type === 'income' && 
                    t.paymentMethod === 'digital' && 
                    (!t.invoiced || t.invoiced === 0) &&
                    t.category !== 'Aportes de Capital'
                );

                if (digitalSales.length === 0) {
                    alert('✅ No hay ventas pendientes de facturar');
                    return;
                }

                // Agrupar ventas
                const salesGroups = {};
                digitalSales.forEach(sale => {
                    const groupKey = `${sale.customer || 'sin-cliente'}_${sale.date}_${sale.saleTimestamp || sale.id}`;
                    
                    if (!salesGroups[groupKey]) {
                        salesGroups[groupKey] = {
                            customer: sale.customer,
                            date: sale.date,
                            items: [],
                            total: 0
                        };
                    }
                    
                    salesGroups[groupKey].items.push(sale);
                    salesGroups[groupKey].total += parseFloat(sale.amount || 0);
                });

                // Crear mensaje detallado
                let message = `📋 VENTAS PENDIENTES DE FACTURAR (${Object.keys(salesGroups).length}):\n\n`;
                
                Object.values(salesGroups).forEach((group, index) => {
                    message += `${index + 1}. ${group.customer || 'Sin cliente'} - ${formatDate(group.date)}\n`;
                    message += `   ${group.items.length} item(s) - Total: ${formatCurrency(group.total)}\n`;
                    group.items.forEach(item => {
                        message += `   • ${item.productName || item.description}\n`;
                    });
                    message += '\n';
                });

                alert(message);
                
            } catch (error) {
                console.error('Error mostrando detalle:', error);
                alert('Error al mostrar el detalle de ventas pendientes');
            }
        }
        console.log('📋 SISTEMA DE CONTROL DE FACTURACIÓN IMPLEMENTADO');

        // ======== NUEVO SISTEMA DE PRESUPUESTOS REVESTIKA ========

        // Función para generar número de presupuesto único
        function generateQuoteNumber() {
            const now = new Date();
            const year = now.getFullYear().toString().slice(-2);
            const month = String(now.getMonth() + 1).padStart(2, '0');
            const day = String(now.getDate()).padStart(2, '0');
            const timestamp = now.getTime().toString().slice(-6); // Últimos 6 dígitos para unicidad
            
            return `${year}${month}${day}${timestamp}`;
        }

        // ======== FUNCIÓN PARA AGREGAR DESCUENTO ========
        function addQuoteDiscount() {
            try {
                const discountInput = document.getElementById('quoteDiscountPercent');
                const discountValue = parseFloat(discountInput?.value) || 0;
                
                if (discountValue < 0 || discountValue > 100) {
                    alert('El descuento debe estar entre 0% y 100%');
                    return;
                }
                
                if (quoteItems.length === 0) {
                    alert('Primero agrega productos al presupuesto');
                    return;
                }
                
                quoteDiscount = discountValue;
                updateQuoteItemsDisplay();
                
                if (discountInput) discountInput.value = '';
                
            } catch (error) {
                console.error('Error agregando descuento:', error);
                alert('Error al agregar el descuento');
            }
        }

        function removeQuoteDiscount() {
            quoteDiscount = 0;
            updateQuoteItemsDisplay();
        }

        // ======== FUNCIÓN ACTUALIZADA PARA MOSTRAR ITEMS ========
        function updateQuoteItemsDisplay() {
            const container = document.getElementById('quoteItems');
            if (!container) return;
            
            try {
                container.innerHTML = '';
                
                let subtotalAmount = 0;
                
                // Mostrar productos
                quoteItems.forEach((item, index) => {
                    subtotalAmount += item.total;
                    
                    const div = document.createElement('div');
                    div.className = 'quote-item';
                    div.innerHTML = `
                        <div>
                            <strong>${item.productName}</strong><br>
                            Cantidad: ${item.quantity} ${item.unit} × ${formatCurrency(item.price)} = ${formatCurrency(item.total)}
                        </div>
                        <button class="delete-btn" onclick="removeQuoteItem(${index})">🗑️</button>
                    `;
                    container.appendChild(div);
                });
                
                // Mostrar descuento si existe
                if (quoteDiscount > 0) {
                    const discountAmount = subtotalAmount * (quoteDiscount / 100);
                    const div = document.createElement('div');
                    div.className = 'quote-item';
                    div.style.backgroundColor = '#fff3cd';
                    div.style.borderLeft = '4px solid #ffc107';
                    div.innerHTML = `
                        <div>
                            <strong>🏷️ Descuento ${quoteDiscount}%</strong><br>
                            Descuento aplicado: -${formatCurrency(discountAmount)}
                        </div>
                        <button class="delete-btn" onclick="removeQuoteDiscount()" title="Eliminar descuento">🗑️</button>
                    `;
                    container.appendChild(div);
                }
                
                const quoteTotal = document.getElementById('quoteTotal');
                const quoteTotalAmount = document.getElementById('quoteTotalAmount');
                
                if (quoteItems.length > 0) {
                    const discountAmount = subtotalAmount * (quoteDiscount / 100);
                    const totalAmount = subtotalAmount - discountAmount;
                    
                    if (quoteTotal) quoteTotal.style.display = 'block';
                    if (quoteTotalAmount) quoteTotalAmount.textContent = totalAmount.toFixed(2);
                } else {
                    if (quoteTotal) quoteTotal.style.display = 'none';
                    quoteDiscount = 0; // Reset descuento si no hay productos
                }
            } catch (error) {
                console.error('Error actualizando display de presupuesto:', error);
            }
        }

        // ======== HTML MEJORADO PARA PRESUPUESTOS REVESTIKA ========
        function generateRevestikaQuoteHTML(quoteData) {
            const {
                customer,
                phone = 'N/A',
                email = 'N/A', 
                address = '',
                date,
                items,
                subtotal,
                discount,
                total,
                quoteNumber
            } = quoteData;

            // Preparar filas de productos + descuento
            let productRows = '';
            items.forEach(item => {
                productRows += `
                    <tr>
                        <td>${item.quantity} ${item.unit}</td>
                        <td class="product-name">${escapeHtml(item.productName)}</td>
                        <td class="amount">$${formatNumberForDisplay(item.price)}</td>
                        <td class="amount">$${formatNumberForDisplay(item.total)}</td>
                    </tr>
                `;
            });

            // Agregar fila de descuento si existe
            if (discount > 0) {
                const discountAmount = subtotal * (discount / 100);
                productRows += `
                    <tr style="background-color: #fff8dc;">
                        <td colspan="2" style="text-align: left; font-weight: 600; color: #e67e22;">🏷️ Descuento ${discount}%</td>
                        <td></td>
                        <td class="amount" style="color: #e67e22; font-weight: 600;">-$${formatNumberForDisplay(discountAmount)}</td>
                    </tr>
                `;
            }

            return `
            <!DOCTYPE html>
            <html lang="es">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Presupuesto Revestika #${quoteNumber}</title>
                <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
                <style>
                    * {
                        margin: 0;
                        padding: 0;
                        box-sizing: border-box;
                    }
                    
                    body {
                        font-family: 'Inter', Arial, sans-serif;
                        line-height: 1.2;
                        color: #333;
                        background: white !important;
                        size: A4;
                        margin: 0;
                        padding: 0;
                        font-size: 10px; /* Reducido para mejor aprovechamiento */
                    }
                    
                    .quote-container {
                        width: 210mm;
                        height: 297mm;
                        max-height: 297mm;
                        margin: 0 auto;
                        background: white !important;
                        display: flex;
                        flex-direction: column;
                        position: relative;
                        padding: 0;
                        overflow: hidden;
                    }
                    
                    /* Header optimizado - MÁS COMPACTO */
                    .header {
                        min-height: 90px; /* Reducido de 110px */
                        max-height: 90px;
                        padding: 20px; /* Reducido de 25px */
                        display: flex;
                        justify-content: space-between;
                        align-items: flex-start;
                        border-bottom: 1px solid #e5e5e5;
                        background: white !important;
                        flex-shrink: 0;
                    }
                    
                    .logo {
                        font-size: 22px; /* Reducido de 24px */
                        font-weight: 700;
                        letter-spacing: -0.5px;
                        flex: 0 0 auto; /* No se estira */
                    }
                    
                    .logo .revest {
                        color: #333;
                    }
                    
                    .logo .ika {
                        color: #B8860B;
                    }
                    
                    /* NUEVO: Info del presupuesto MÁS A LA DERECHA */
                    .quote-info {
                        text-align: right;
                        margin-left: auto; /* Empuja hacia la derecha */
                        min-width: 180px; /* Ancho mínimo */
                        max-width: 180px; /* Ancho máximo */
                        flex-shrink: 0; /* No se comprime */
                    }
                    
                    .quote-title {
                        font-size: 13px; /* Reducido de 14px */
                        font-weight: 600;
                        color: #333;
                        margin-bottom: 4px; /* Reducido de 5px */
                    }
                    
                    .quote-number {
                        font-size: 16px; /* Reducido de 18px */
                        font-weight: 700;
                        color: #B8860B;
                        margin-bottom: 8px; /* Reducido de 10px */
                    }
                    
                    .date-box {
                        display: flex;
                        align-items: center;
                        justify-content: flex-end; /* Alineado a la derecha */
                        gap: 4px; /* Reducido de 5px */
                        font-size: 9px; /* Reducido de 10px */
                        font-weight: 500;
                    }
                    
                    .date-label {
                        background: #f5f5f5;
                        padding: 4px 6px; /* Reducido */
                        border: 1px solid #ddd;
                        font-size: 8px;
                    }
                    
                    .date-values {
                        display: flex;
                        gap: 0;
                    }
                    
                    .date-part {
                        background: white;
                        border: 1px solid #ddd;
                        padding: 4px 6px; /* Reducido */
                        min-width: 25px; /* Reducido de 30px */
                        text-align: center;
                        font-weight: 600;
                        font-size: 9px;
                    }
                    
                    /* Content - MÁS ESPACIO PARA LA TABLA */
                    .content {
                        flex: 1;
                        padding: 15px; /* Reducido de 20px */
                        overflow: hidden;
                        display: flex;
                        flex-direction: column;
                    }
                    
                    /* Client Info - MÁS COMPACTA */
                    .client-section {
                        margin-bottom: 10px; /* Reducido de 15px */
                        flex-shrink: 0;
                    }
                    
                    .client-grid {
                        display: grid;
                        grid-template-columns: 1fr 1fr;
                        gap: 6px; /* Reducido de 8px */
                    }
                    
                    .client-field {
                        display: flex;
                        align-items: center;
                        font-size: 9px; /* Reducido de 10px */
                    }
                    
                    .field-label {
                        font-weight: 600;
                        color: #333;
                        min-width: 55px; /* Reducido de 60px */
                    }
                    
                    .field-value {
                        color: #555;
                        margin-left: 5px; /* Reducido de 6px */
                    }
                    
                    /* Products Table - CELDAS OPTIMIZADAS */
                    .products-section {
                        flex: 1;
                        display: flex;
                        flex-direction: column;
                    }
                    
                    .products-table {
                        width: 100%;
                        border-collapse: collapse;
                        border: 2px solid #333;
                        font-size: 9px; /* Reducido de 10px */
                        flex: 1;
                    }
                    
                    .products-table th {
                        background: #f8f8f8;
                        border: 1px solid #333;
                        padding: 4px 3px; /* Reducido significativamente */
                        text-align: center;
                        font-weight: 600;
                        color: #333;
                        height: 20px; /* Reducido de 25px */
                        line-height: 1.1;
                        font-size: 8px; /* Más pequeño para headers */
                    }
                    
                    .products-table td {
                        border: 1px solid #333;
                        padding: 3px; /* Reducido significativamente */
                        text-align: center;
                        vertical-align: middle;
                        height: 18px; /* Reducido de 25px - CELDAS MÁS PEQUEÑAS */
                        line-height: 1.1;
                        overflow: hidden;
                        white-space: nowrap;
                        text-overflow: ellipsis;
                        font-size: 9px;
                    }
                    
                    .products-table .product-name {
                        text-align: left;
                        font-weight: 500;
                        padding-left: 4px; /* Reducido de 6px */
                        white-space: normal; /* Permitir wrap en nombres largos */
                        line-height: 1.2;
                        height: auto;
                        min-height: 18px;
                    }
                    
                    .products-table .amount {
                        text-align: right;
                        font-weight: 600;
                        padding-right: 4px; /* Reducido de 6px */
                    }
                    
                    /* Fila de total - Más compacta */
                    .total-row {
                        background: #f0f0f0;
                        font-weight: 700;
                        font-size: 10px; /* Reducido de 11px */
                        height: 22px; /* Reducido de 30px */
                    }
                    
                    .total-row td {
                        border-top: 2px solid #333;
                        height: 22px; /* Reducido de 30px */
                        padding: 4px 3px; /* Consistente con otras celdas */
                    }
                    
                    /* Footer - MÁS COMPACTO */
                    .footer {
                        min-height: 45px; /* Reducido de 55px */
                        max-height: 45px;
                        background: linear-gradient(135deg, #B8860B 0%, #DAA520 100%);
                        color: white;
                        padding: 10px 15px; /* Reducido */
                        margin-top: auto;
                        display: flex;
                        justify-content: space-between;
                        align-items: center;
                        font-size: 8px; /* Reducido de 9px */
                        flex-shrink: 0;
                    }
                    
                    .footer-item {
                        display: flex;
                        align-items: center;
                        gap: 3px; /* Reducido de 4px */
                        font-weight: 500;
                    }
                    
                    .footer-icon {
                        font-size: 9px;
                    }
                    
                    /* Estilos específicos para filas de descuento */
                    .discount-row {
                        background-color: #fff8dc !important;
                    }
                    
                    .discount-row td {
                        color: #e67e22 !important;
                        font-weight: 600 !important;
                    }
                    
                    /* Forzar que todo quepa en una página */
                    @media print {
                        body {
                            margin: 0 !important;
                            padding: 0 !important;
                            -webkit-print-color-adjust: exact !important;
                            color-adjust: exact !important;
                        }
                        
                        .quote-container {
                            width: 100% !important;
                            height: 100vh !important;
                            max-height: 100vh !important;
                            margin: 0 !important;
                            padding: 0 !important;
                            box-shadow: none !important;
                            page-break-inside: avoid !important;
                            overflow: hidden !important;
                        }
                        
                        .no-print {
                            display: none !important;
                        }
                        
                        /* Mantener alturas fijas en impresión */
                        .products-table td {
                            height: 18px !important;
                        }
                        
                        .products-table th {
                            height: 20px !important;
                        }
                        
                        .total-row td {
                            height: 22px !important;
                        }
                        
                        /* Evitar saltos de página */
                        * {
                            page-break-inside: avoid !important;
                        }
                    }
                    
                    /* Responsive adjustments */
                    @media (max-width: 768px) {
                        .quote-container {
                            width: 100%;
                            min-height: 100vh;
                        }
                        
                        .header {
                            padding: 15px;
                            flex-direction: column;
                            gap: 10px;
                            min-height: auto;
                        }
                        
                        .quote-info {
                            text-align: center;
                            margin-left: 0;
                            max-width: none;
                        }
                        
                        .content {
                            padding: 15px;
                        }
                        
                        .client-grid {
                            grid-template-columns: 1fr;
                            gap: 6px;
                        }
                        
                        .footer {
                            padding: 10px;
                            flex-direction: column;
                            gap: 6px;
                            text-align: center;
                            min-height: auto;
                        }
                    }
                </style>
            </head>
            <body>
                <div class="quote-container">
                    <!-- Header -->
                    <div class="header">
                        <div class="logo">
                            <span class="revest">REVEST</span><span class="ika">IKA</span>
                        </div>
                        <div class="quote-info">
                            <div class="quote-title">Presupuesto</div>
                            <div class="quote-number">N° ${quoteNumber}</div>
                            <div class="date-box">
                                <span class="date-label">FECHA</span>
                                <div class="date-values">
                                    <span class="date-part">${date.split('-')[2] || 'DD'}</span>
                                    <span class="date-part">${date.split('-')[1] || 'MM'}</span>
                                    <span class="date-part">${date.split('-')[0]?.slice(-2) || 'YY'}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Content -->
                    <div class="content">
                        <!-- Client Information -->
                        <div class="client-section">
                            <div class="client-grid">
                                <div class="client-field">
                                    <span class="field-label">Cliente:</span>
                                    <span class="field-value">${escapeHtml(customer)}</span>
                                </div>
                                <div class="client-field">
                                    <span class="field-label">Teléfono:</span>
                                    <span class="field-value">${escapeHtml(phone)}</span>
                                </div>
                                <div class="client-field">
                                    <span class="field-label">Dirección:</span>
                                    <span class="field-value">${escapeHtml(address)}</span>
                                </div>
                                <div class="client-field">
                                    <span class="field-label">Correo:</span>
                                    <span class="field-value">${escapeHtml(email)}</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Products Table -->
                        <div class="products-section">
                            <table class="products-table">
                                <thead>
                                    <tr>
                                        <th style="width: 15%;">Cantidad</th>
                                        <th style="width: 50%;">Producto</th>
                                        <th style="width: 17.5%;">Precio unitario</th>
                                        <th style="width: 17.5%;">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${productRows}
                                    <tr class="total-row">
                                        <td colspan="3"><strong>Total</strong></td>
                                        <td class="amount"><strong>$${formatNumberForDisplay(total)}</strong></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Footer -->
                    <div class="footer">
                        <div class="footer-item">
                            <span class="footer-icon">📍</span>
                            <span>La Plata, Pcia. de Buenos Aires.</span>
                        </div>
                        <div class="footer-item">
                            <span class="footer-icon">✉️</span>
                            <span>ventas@revestika.com.ar</span>
                        </div>
                        <div class="footer-item">
                            <span class="footer-icon">📞</span>
                            <span>(54) 221 317 6973</span>
                        </div>
                    </div>
                </div>
                
                <!-- Print Button (hidden in print) -->
                <div class="no-print" style="position: fixed; top: 20px; right: 20px; z-index: 1000;">
                    <button onclick="window.print()" style="
                        background: #B8860B;
                        color: white;
                        border: none;
                        padding: 12px 20px;
                        border-radius: 6px;
                        cursor: pointer;
                        font-weight: 600;
                        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
                    ">
                        🖨️ Imprimir / Guardar PDF
                    </button>
                </div>
            </body>
            </html>
            `;
        }

        // ======== FUNCIÓN PARA GENERAR PRESUPUESTO ACTUALIZADA ========
        function generateRevestikaQuote() {
            try {
                const customer = document.getElementById('quoteCustomer')?.value;
                const date = document.getElementById('quoteDate')?.value;
                const phone = document.getElementById('quotePhone')?.value || 'N/A';
                const email = document.getElementById('quoteEmail')?.value || 'N/A';
                const address = document.getElementById('quoteAddress')?.value || '';
                
                if (!customer || quoteItems.length === 0) {
                    alert('Por favor completa los datos del cliente y agrega al menos un producto');
                    return;
                }
                
                const subtotalAmount = quoteItems.reduce((sum, item) => sum + item.total, 0);
                const discountAmount = subtotalAmount * (quoteDiscount / 100);
                const totalAmount = subtotalAmount - discountAmount;
                const quoteNumber = generateQuoteNumber();
                
                const quoteData = {
                    customer,
                    phone,
                    email,
                    address,
                    date,
                    items: quoteItems,
                    subtotal: subtotalAmount,
                    discount: quoteDiscount,
                    total: totalAmount,
                    quoteNumber
                };
                
                // Generar HTML
                const quoteHTML = generateRevestikaQuoteHTML(quoteData);
                
                // Mostrar en nueva ventana
                const newWindow = window.open('', '_blank', 'width=800,height=1000');
                newWindow.document.write(quoteHTML);
                newWindow.document.close();
                
                // Limpiar formulario
                document.getElementById('quoteForm').reset();
                quoteItems = [];
                quoteDiscount = 0; // Reset descuento
                updateQuoteItemsDisplay();
                setTodayDates();
                
                console.log('✅ Presupuesto Revestika generado correctamente');
                
            } catch (error) {
                console.error('Error generando presupuesto Revestika:', error);
                alert('Error al generar el presupuesto: ' + error.message);
            }
        }

        // ======== FUNCIÓN PARA DESCARGAR PDF ACTUALIZADA ========
        function downloadRevestikaPDF() {
            try {
                const customer = document.getElementById('quoteCustomer')?.value;
                const date = document.getElementById('quoteDate')?.value;
                const phone = document.getElementById('quotePhone')?.value || 'N/A';
                const email = document.getElementById('quoteEmail')?.value || 'N/A';
                const address = document.getElementById('quoteAddress')?.value || '';
                
                if (!customer || quoteItems.length === 0) {
                    alert('Por favor completa los datos del cliente y agrega al menos un producto');
                    return;
                }
                
                const subtotalAmount = quoteItems.reduce((sum, item) => sum + item.total, 0);
                const discountAmount = subtotalAmount * (quoteDiscount / 100);
                const totalAmount = subtotalAmount - discountAmount;
                const quoteNumber = generateQuoteNumber();
                
                const quoteData = {
                    customer,
                    phone,
                    email,
                    address,
                    date,
                    items: quoteItems,
                    subtotal: subtotalAmount,
                    discount: quoteDiscount,
                    total: totalAmount,
                    quoteNumber
                };
                
                // Generar HTML
                const quoteHTML = generateRevestikaQuoteHTML(quoteData);
                
                // Crear elemento temporal para html2canvas
                const tempDiv = document.createElement('div');
                tempDiv.style.position = 'absolute';
                tempDiv.style.left = '-9999px';
                tempDiv.style.top = '0';
                tempDiv.innerHTML = quoteHTML;
                document.body.appendChild(tempDiv);
                
                // Esperar a que se rendericen las fuentes
                setTimeout(() => {
                    if (typeof html2canvas !== 'undefined' && typeof window.jspdf !== 'undefined') {
                        const quoteContainer = tempDiv.querySelector('.quote-container');
                        
                        html2canvas(quoteContainer, {
                            scale: 2,
                            useCORS: true,
                            allowTaint: false,
                            backgroundColor: '#ffffff',
                            width: 794, // A4 width in pixels at 96 DPI
                            height: 1123, // A4 height in pixels at 96 DPI
                            logging: false
                        }).then(canvas => {
                            const { jsPDF } = window.jspdf;
                            const pdf = new jsPDF('p', 'mm', 'a4');
                            
                            const imgData = canvas.toDataURL('image/png', 1.0);
                            const imgWidth = 210; // A4 width in mm
                            const imgHeight = (canvas.height * imgWidth) / canvas.width;
                            
                            pdf.addImage(imgData, 'PNG', 0, 0, imgWidth, imgHeight);
                            
                            const filename = `Presupuesto_Revestika_${customer.replace(/\s+/g, '_')}_${quoteNumber}.pdf`;
                            pdf.save(filename);
                            
                            // Limpiar
                            document.body.removeChild(tempDiv);
                            
                            // Limpiar formulario
                            document.getElementById('quoteForm').reset();
                            quoteItems = [];
                            quoteDiscount = 0; // Reset descuento
                            updateQuoteItemsDisplay();
                            setTodayDates();
                            
                            console.log('✅ PDF descargado correctamente');
                        }).catch(error => {
                            console.error('Error generando PDF:', error);
                            document.body.removeChild(tempDiv);
                            alert('Error al generar el PDF. Usa la opción de vista previa e imprime desde el navegador.');
                        });
                    } else {
                        // Fallback: abrir en nueva ventana para imprimir
                        document.body.removeChild(tempDiv);
                        generateRevestikaQuote();
                    }
                }, 1000);
                
            } catch (error) {
                console.error('Error en downloadRevestikaPDF:', error);
                alert('Error al generar el PDF: ' + error.message);
            }
        }

        console.log('🎨 PRESUPUESTOS REVESTIKA ACTUALIZADOS');
        console.log('✅ 1. Header reposicionado correctamente');
        console.log('✅ 2. Celdas de tabla optimizadas y más pequeñas'); 
        console.log('✅ 3. Sistema de descuentos implementado');
        console.log('✅ 4. Mantiene formato de 1 página A4');
        console.log('✅ 5. Diseño optimizado para máximo 9 filas + total');

        // ======== FUNCIONES GLOBALES PARA BOTONES ========
        // Hacer funciones disponibles globalmente para onclick
        window.showTab = showTab;
        window.changeCurrency = changeCurrency;
        window.deleteTransactionFromSource = deleteTransactionFromSource;
        window.editProduct = editProduct;
        window.deleteProduct = deleteProduct;
        window.removeCategory = removeCategory;
        window.addCategory = addCategory;
        window.setInitialStock = setInitialStock;
        window.adjustStock = adjustStock;
        window.editMinimumStock = editMinimumStock;
        window.editInitialStock = editInitialStock;
        window.synchronizeStock = synchronizeStock;
        window.updateProductPrice = updateProductPrice;
        window.updateProductPriceMulti = updateProductPriceMulti;
        window.calculateTotal = calculateTotal;
        window.calculateProductTotalMulti = calculateProductTotalMulti;
        window.addProductToSale = addProductToSale;
        window.removeSaleProduct = removeSaleProduct;
        window.completeSale = completeSale;
        window.cancelSale = cancelSale;
        window.addQuoteItem = addQuoteItem;
        window.removeQuoteItem = removeQuoteItem;
        window.addQuoteDiscount = addQuoteDiscount;
        window.removeQuoteDiscount = removeQuoteDiscount;
        window.generateRevestikaQuote = generateRevestikaQuote;
        window.downloadRevestikaPDF = downloadRevestikaPDF;
        window.deleteInvestment = deleteInvestment;
        window.closeEditModal = closeEditModal;
        window.resetDateFilter = resetDateFilter;
        window.resetBalanceFilter = resetBalanceFilter;
        window.filterTransactions = filterTransactions;
        window.filterIncomes = filterIncomes;
        window.filterExpenses = filterExpenses;
        window.clearIncomeFilters = clearIncomeFilters;
        window.clearExpenseFilters = clearExpenseFilters;
        window.exportIncomeData = exportIncomeData;
        window.exportExpenseData = exportExpenseData;
        window.updateInvoiceStatus = updateInvoiceStatus;
        window.showPendingInvoicesDetail = showPendingInvoicesDetail;
        window.updateTaxesDisplay = updateTaxesDisplay;
        window.goToCurrentPeriod = goToCurrentPeriod;
        window.togglePaymentStatus = togglePaymentStatus;
        window.deleteTaxFixedItem = deleteTaxFixedItem;
        window.togglePaymentMethodForInvestment = togglePaymentMethodForInvestment;

        console.log('✅ Funciones globales configuradas para botones');

        // ======== HACER TODAS LAS FUNCIONES DISPONIBLES GLOBALMENTE ========
        // Solo agregar las que no están ya definidas

        if (typeof deleteTransactionFromSource === 'function') window.deleteTransactionFromSource = deleteTransactionFromSource;
        if (typeof editProduct === 'function') window.editProduct = editProduct;
        if (typeof deleteProduct === 'function') window.deleteProduct = deleteProduct;
        if (typeof removeCategory === 'function') window.removeCategory = removeCategory;
        if (typeof addCategory === 'function') window.addCategory = addCategory;
        if (typeof setInitialStock === 'function') window.setInitialStock = setInitialStock;
        if (typeof adjustStock === 'function') window.adjustStock = adjustStock;
        if (typeof editMinimumStock === 'function') window.editMinimumStock = editMinimumStock;
        if (typeof editInitialStock === 'function') window.editInitialStock = editInitialStock;
        if (typeof synchronizeStock === 'function') window.synchronizeStock = synchronizeStock;
        if (typeof updateProductPrice === 'function') window.updateProductPrice = updateProductPrice;
        if (typeof updateProductPriceMulti === 'function') window.updateProductPriceMulti = updateProductPriceMulti;
        if (typeof calculateTotal === 'function') window.calculateTotal = calculateTotal;
        if (typeof calculateProductTotalMulti === 'function') window.calculateProductTotalMulti = calculateProductTotalMulti;
        if (typeof addProductToSale === 'function') window.addProductToSale = addProductToSale;
        if (typeof removeSaleProduct === 'function') window.removeSaleProduct = removeSaleProduct;
        if (typeof completeSale === 'function') window.completeSale = completeSale;
        if (typeof cancelSale === 'function') window.cancelSale = cancelSale;
        if (typeof closeEditModal === 'function') window.closeEditModal = closeEditModal;
        if (typeof resetDateFilter === 'function') window.resetDateFilter = resetDateFilter;
        if (typeof resetBalanceFilter === 'function') window.resetBalanceFilter = resetBalanceFilter;

        console.log('✅ Todas las funciones exportadas a window');
    </script>
</body>
</html>