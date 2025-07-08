<?php
// api.php - API para manejar las operaciones de base de datos
include 'session_secure.php';
if (!startSecureSession()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Sesión expirada']);
    exit;
}

// Verificar autenticación
if (!isset($_SESSION['authenticated'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'No autenticado']);
    exit;
}

// Incluir configuración de base de datos
include 'config.php';

// Obtener datos JSON del request
$input = json_decode(file_get_contents('php://input'), true);
$action = $input['action'] ?? '';
$table = $input['table'] ?? '';
$data = $input['data'] ?? [];
$id = $input['id'] ?? '';

// Log para debugging
error_log("API Call - Action: $action, Table: $table, Data: " . json_encode($data));

try {
    switch ($action) {
        case 'save':
            $result = saveData($pdo, $table, $data);
            echo json_encode(['success' => true, 'result' => $result]);
            break;
            
        case 'load':
            $result = loadData($pdo, $table);
            echo json_encode(['success' => true, 'data' => $result]);
            break;
            
        case 'delete':
            $result = deleteData($pdo, $table, $id);
            echo json_encode(['success' => true, 'result' => $result]);
            break;

        // ⭐ NUEVA ACCIÓN: Actualizar estado de facturación
        case 'update_invoice_status':
            $transactionId = $input['transaction_id'] ?? '';
            $invoiced = $input['invoiced'] ?? false;
            $result = updateInvoiceStatus($pdo, $transactionId, $invoiced);
            echo json_encode(['success' => true, 'result' => $result]);
            break;

        case 'reset':
            $password = $input['password'] ?? '';
            if ($password !== '79145') {
                throw new Exception('Contraseña incorrecta');
            }
            $result = resetDatabase($pdo);
            echo json_encode(['success' => true, 'result' => $result]);
            break;
        case 'verify_reset':
            $result = verifyReset($pdo);
            echo json_encode(['success' => true, 'counts' => $result]);
            break;    
            
        default:
            echo json_encode(['success' => false, 'error' => 'Acción no válida']);
    }
} catch (Exception $e) {
    error_log("API Error: " . $e->getMessage());
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

// ⭐ NUEVA FUNCIÓN: Actualizar estado de facturación
function updateInvoiceStatus($pdo, $transactionId, $invoiced) {
    try {
        $stmt = $pdo->prepare("UPDATE transactions SET invoiced = ? WHERE id = ?");
        $result = $stmt->execute([$invoiced ? 1 : 0, $transactionId]);
        
        if ($result) {
            error_log("✅ INVOICE STATUS: Transacción $transactionId marcada como " . ($invoiced ? 'facturada' : 'no facturada'));
            return true;
        } else {
            throw new Exception('Error actualizando estado de facturación');
        }
    } catch (Exception $e) {
        error_log("❌ INVOICE STATUS ERROR: " . $e->getMessage());
        throw new Exception('Error actualizando estado de facturación: ' . $e->getMessage());
    }
}

function resetDatabase($pdo) {
    try {
        error_log("🔄 RESET: Iniciando reset de base de datos");
        
        // ✅ CONTAR registros ANTES del delete
        $beforeCounts = [];
        $tables = ['stock', 'transactions', 'investments', 'products', 'categories'];
        
        foreach ($tables as $table) {
            $stmt = $pdo->query("SELECT COUNT(*) as count FROM $table");
            $beforeCounts[$table] = $stmt->fetch()['count'];
        }
        error_log("🔄 RESET: Registros ANTES - " . json_encode($beforeCounts));
        
        // ✅ DESHABILITAR FOREIGN KEY CHECKS
        $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
        error_log("🔄 RESET: Foreign keys deshabilitados");
        
        // ✅ ELIMINAR todos los datos (SIN TRANSACCIÓN para evitar problemas con ALTER TABLE)
        $pdo->exec("DELETE FROM stock");
        error_log("🔄 RESET: Stock eliminado");
        
        $pdo->exec("DELETE FROM transactions"); 
        error_log("🔄 RESET: Transacciones eliminadas");
        
        $pdo->exec("DELETE FROM investments");
        error_log("🔄 RESET: Inversiones eliminadas");
        
        $pdo->exec("DELETE FROM products");
        error_log("🔄 RESET: Productos eliminados");
        
        $pdo->exec("DELETE FROM categories");
        error_log("🔄 RESET: Categorías eliminadas");
        
        // ✅ RESETEAR AUTO_INCREMENT (fuera de transacción)
        $pdo->exec("ALTER TABLE stock AUTO_INCREMENT = 1");
        $pdo->exec("ALTER TABLE categories AUTO_INCREMENT = 1");
        error_log("🔄 RESET: Auto increment reseteado");
        
        // ✅ AHORA SÍ USAR TRANSACCIÓN PARA LOS INSERTS
        $pdo->beginTransaction();
        error_log("🔄 RESET: Transacción iniciada para inserts");
        
        // ✅ REINSERTAR categorías por defecto
        $stmt = $pdo->prepare("INSERT INTO categories (name, created_at) VALUES (?, NOW())");
        $defaultCategories = ['Paneles SPC', 'Adhesivos', 'Accesorios'];
        foreach ($defaultCategories as $category) {
            $stmt->execute([$category]);
        }
        error_log("🔄 RESET: Categorías por defecto insertadas");
        
        // ✅ REINSERTAR producto por defecto
        $stmt = $pdo->prepare("
            INSERT INTO products (id, name, category, dimensions, price, unit, description, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
        ");
        $productId = time() * 1000; // ID único basado en timestamp
        $stmt->execute([
            $productId,
            'Panel Marquina Negro SPC 3mm',
            'Paneles SPC',
            '1,22 x 2,44 mts',
            0,
            'unidad',
            'Panel decorativo SPC color marquina negro'
        ]);
        error_log("🔄 RESET: Producto por defecto insertado con ID: " . $productId);
        
        // ✅ AGREGAR stock inicial para el producto por defecto
        $stmt = $pdo->prepare("
            INSERT INTO stock (product_id, initial_quantity, current_quantity, minimum_quantity, updated_at) 
            VALUES (?, ?, ?, ?, NOW())
        ");
        $stmt->execute([$productId, 0, 0, 5]);
        error_log("🔄 RESET: Stock por defecto insertado");
        
        // ✅ CONFIRMAR TRANSACCIÓN DE INSERTS
        $pdo->commit();
        error_log("🔄 RESET: Transacción de inserts confirmada");
        
        // ✅ REHABILITAR FOREIGN KEY CHECKS
        $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
        error_log("🔄 RESET: Foreign keys rehabilitados");
        
        // ✅ VERIFICAR registros DESPUÉS del reset
        $afterCounts = [];
        foreach ($tables as $table) {
            $stmt = $pdo->query("SELECT COUNT(*) as count FROM $table");
            $afterCounts[$table] = $stmt->fetch()['count'];
        }
        error_log("🔄 RESET: Registros DESPUÉS - " . json_encode($afterCounts));
        
        error_log("🔄 RESET: Reset completado exitosamente");
        
        return 'Base de datos reseteada correctamente. Antes: ' . json_encode($beforeCounts) . ' | Después: ' . json_encode($afterCounts);
        
    } catch (Exception $e) {
        error_log("❌ RESET ERROR: " . $e->getMessage());
        
        // ✅ REHABILITAR FK si hubo error
        try {
            $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
        } catch (Exception $fkError) {
            error_log("❌ RESET ERROR FK: " . $fkError->getMessage());
        }
        
        // ✅ HACER ROLLBACK si hay transacción activa
        try {
            $pdo->rollback();
        } catch (Exception $rollbackError) {
            // Ignorar si no hay transacción activa
            error_log("🔄 RESET: No hay transacción activa para rollback");
        }
        
        throw new Exception('Error reseteando base de datos: ' . $e->getMessage());
    }
}

function verifyReset($pdo) {
    try {
        $result = [];
        
        // Contar registros en cada tabla
        $tables = ['transactions', 'products', 'categories', 'stock', 'investments'];
        
        foreach ($tables as $table) {
            $stmt = $pdo->query("SELECT COUNT(*) as count FROM $table");
            $count = $stmt->fetch()['count'];
            $result[$table] = $count;
        }
        
        error_log("🔍 VERIFY RESET: " . json_encode($result));
        return $result;
    } catch (Exception $e) {
        error_log("❌ VERIFY RESET ERROR: " . $e->getMessage());
        throw new Exception('Error verificando reset: ' . $e->getMessage());
    }
}

function saveData($pdo, $table, $data) {
    switch ($table) {
        case 'products':
            return saveProducts($pdo, $data);
        case 'transactions':
            return saveTransactions($pdo, $data);
        case 'categories':
            return saveCategories($pdo, $data);
        case 'stock':
            return saveStock($pdo, $data);
        case 'investments':
            return saveInvestments($pdo, $data);
        case 'tax_fixed_items':
            return saveTaxFixedItems($pdo, $data);
        case 'tax_fixed_payments':
            return saveTaxFixedPayments($pdo, $data);
        default:
            throw new Exception('Tabla no válida');
    }
}

function loadData($pdo, $table) {
    switch ($table) {
        case 'products':
            return loadProducts($pdo);
        case 'transactions':
            return loadTransactions($pdo);
        case 'categories':
            return loadCategories($pdo);
        case 'stock':
            return loadStock($pdo);
        case 'investments':
            return loadInvestments($pdo);
        case 'tax_fixed_items':
            return loadTaxFixedItems($pdo);
        case 'tax_fixed_payments':
            return loadTaxFixedPayments($pdo);   
        default:
            throw new Exception('Tabla no válida');
    }
}

function deleteData($pdo, $table, $id) {
    switch ($table) {
        case 'products':
            $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
            return $stmt->execute([$id]);
        case 'transactions':
            $stmt = $pdo->prepare("DELETE FROM transactions WHERE id = ?");
            return $stmt->execute([$id]);
        case 'investments':
            $stmt = $pdo->prepare("DELETE FROM investments WHERE id = ?");
            return $stmt->execute([$id]);
        default:
            throw new Exception('Tabla no válida para eliminar');
    }
}

// Funciones específicas para cada tabla
function saveProducts($pdo, $products) {
    if (empty($products)) {
        return true;
    }
    
    try {
        // Iniciar transacción
        $pdo->beginTransaction();
        
        // Limpiar tabla y reinsertar todos los productos
        $pdo->exec("DELETE FROM products");
        
        $stmt = $pdo->prepare("
            INSERT INTO products (id, name, category, dimensions, price, unit, description, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
        ");
        
        foreach ($products as $product) {
            $stmt->execute([
                $product['id'],
                $product['name'],
                $product['category'],
                $product['dimensions'],
                $product['price'],
                $product['unit'],
                $product['description']
            ]);
        }
        
        // Confirmar transacción
        $pdo->commit();
        return true;
    } catch (Exception $e) {
        // Revertir cambios si hay error
        $pdo->rollback();
        throw new Exception('Error guardando productos: ' . $e->getMessage());
    }
}

function loadProducts($pdo) {
    $stmt = $pdo->query("SELECT id, name, category, dimensions, price, unit, description FROM products ORDER BY id");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function saveTransactions($pdo, $transactions) {
    if (empty($transactions)) {
        return true;
    }
    
    try {
        // Iniciar transacción
        $pdo->beginTransaction();
        
        // Limpiar tabla y reinsertar todas las transacciones
        $pdo->exec("DELETE FROM transactions");
        
        // ⭐ ACTUALIZAR SQL PARA INCLUIR invoiced ⭐
        $stmt = $pdo->prepare("
            INSERT INTO transactions (
                id, type, date, customer, product_id, product_name, quantity, 
                price_per_unit, amount, description, category, payment_method, 
                expense_type, notes, sale_timestamp, credit_date, invoiced, created_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
        ");
        
        foreach ($transactions as $transaction) {
            $stmt->execute([
                $transaction['id'],
                $transaction['type'],
                $transaction['date'],
                $transaction['customer'] ?? null,
                $transaction['productId'] ?? null,
                $transaction['productName'] ?? null,
                $transaction['quantity'] ?? null,
                $transaction['pricePerUnit'] ?? null,
                $transaction['amount'],
                $transaction['description'],
                $transaction['category'],
                $transaction['paymentMethod'],
                $transaction['expenseType'] ?? null,
                $transaction['notes'] ?? null,
                $transaction['saleTimestamp'] ?? null,
                $transaction['creditDate'] ?? null,
                $transaction['invoiced'] ?? 0  // ⭐ NUEVO CAMPO ⭐
            ]);
        }
        
        // Confirmar transacción
        $pdo->commit();
        return true;
    } catch (Exception $e) {
        // Revertir cambios si hay error
        $pdo->rollback();
        throw new Exception('Error guardando transacciones: ' . $e->getMessage());
    }
}

function loadTransactions($pdo) {
    $stmt = $pdo->query("
        SELECT id, type, date, customer, product_id as productId, product_name as productName, 
               quantity, price_per_unit as pricePerUnit, amount, description, category, 
               payment_method as paymentMethod, expense_type as expenseType, notes, 
               sale_timestamp as saleTimestamp, credit_date as creditDate, 
               COALESCE(invoiced, 0) as invoiced
        FROM transactions ORDER BY date DESC, id DESC
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function saveCategories($pdo, $categories) {
    if (empty($categories)) {
        return true;
    }
    
    // Limpiar tabla y reinsertar todas las categorías
    $pdo->exec("DELETE FROM categories");
    
    $stmt = $pdo->prepare("INSERT INTO categories (name, created_at) VALUES (?, NOW())");
    
    foreach ($categories as $category) {
        $stmt->execute([$category]);
    }
    return true;
}

function loadCategories($pdo) {
    $stmt = $pdo->query("SELECT name FROM categories ORDER BY name");
    $result = $stmt->fetchAll(PDO::FETCH_COLUMN);
    return $result;
}

function saveStock($pdo, $stockData) {
    if (empty($stockData)) {
        return true;
    }
    
    try {
        // Iniciar transacción
        $pdo->beginTransaction();
        
        // Limpiar tabla y reinsertar todo el stock
        $pdo->exec("DELETE FROM stock");
        
        $stmt = $pdo->prepare("
            INSERT INTO stock (product_id, initial_quantity, current_quantity, minimum_quantity, 
                             sales_count, samples_count, breaks_count, updated_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
        ");
        
        // Manejar tanto arrays como objetos
        foreach ($stockData as $productId => $stock) {
            // Saltar elementos vacíos o inválidos
            if (empty($stock) || !is_array($stock)) {
                continue;
            }
            
            // Obtener valores de reductions o usar 0 por defecto
            $reductions = $stock['reductions'] ?? ['sales' => 0, 'samples' => 0, 'breaks' => 0];
            
            $stmt->execute([
                $productId,
                $stock['initial'] ?? 0,
                $stock['current'] ?? 0,
                $stock['minimum'] ?? 0,
                $reductions['sales'] ?? 0,
                $reductions['samples'] ?? 0,
                $reductions['breaks'] ?? 0
            ]);
        }
        
        // Confirmar transacción
        $pdo->commit();
        return true;
    } catch (Exception $e) {
        // Revertir cambios si hay error
        $pdo->rollback();
        throw new Exception('Error guardando stock: ' . $e->getMessage());
    }
}

function loadStock($pdo) {
    $stmt = $pdo->query("
        SELECT product_id, initial_quantity, current_quantity, minimum_quantity,
               sales_count, samples_count, breaks_count
        FROM stock
    ");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $stockData = [];
    foreach ($rows as $row) {
        $stockData[$row['product_id']] = [
            'initial' => (int)$row['initial_quantity'],
            'current' => (int)$row['current_quantity'],
            'minimum' => (int)$row['minimum_quantity'],
            'reductions' => [
                'sales' => (int)($row['sales_count'] ?? 0),
                'samples' => (int)($row['samples_count'] ?? 0),
                'breaks' => (int)($row['breaks_count'] ?? 0)
            ]
        ];
    }
    return $stockData;
}

function saveInvestments($pdo, $investments) {
    if (empty($investments)) {
        return true;
    }
    
    // Limpiar tabla y reinsertar todas las inversiones
    $pdo->exec("DELETE FROM investments");
    
    $stmt = $pdo->prepare("
        INSERT INTO investments (id, date, category, description, amount, notes, created_at) 
        VALUES (?, ?, ?, ?, ?, ?, NOW())
    ");
    
    foreach ($investments as $investment) {
        $stmt->execute([
            $investment['id'],
            $investment['date'],
            $investment['category'],
            $investment['description'],
            $investment['amount'],
            $investment['notes'] ?? null
        ]);
    }
    return true;
}

function loadInvestments($pdo) {
    $stmt = $pdo->query("SELECT id, date, category, description, amount, notes FROM investments ORDER BY date DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function saveTaxFixedItems($pdo, $items) {
    if (empty($items)) {
        return true;
    }
    
    try {
        $pdo->beginTransaction();
        
        $pdo->exec("DELETE FROM tax_fixed_items");
        
        $stmt = $pdo->prepare("
            INSERT INTO tax_fixed_items (id, name, type, frequency, amount, description, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, NOW())
        ");
        
        foreach ($items as $item) {
            $stmt->execute([
                $item['id'],
                $item['name'],
                $item['type'],
                $item['frequency'],
                $item['amount'] ?? 0,
                $item['description'] ?? ''
            ]);
        }
        
        $pdo->commit();
        return true;
    } catch (Exception $e) {
        $pdo->rollback();
        throw new Exception('Error guardando items de impuestos/gastos fijos: ' . $e->getMessage());
    }
}

function loadTaxFixedItems($pdo) {
    $stmt = $pdo->query("SELECT id, name, type, frequency, amount, description FROM tax_fixed_items ORDER BY type, name");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function saveTaxFixedPayments($pdo, $payments) {
    if (empty($payments)) {
        return true;
    }
    
    try {
        $pdo->beginTransaction();
        
        $stmt = $pdo->prepare("
            INSERT INTO tax_fixed_payments (item_id, year, month, is_paid, paid_date, transaction_id, notes) 
            VALUES (?, ?, ?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE 
            is_paid = VALUES(is_paid), 
            paid_date = VALUES(paid_date), 
            transaction_id = VALUES(transaction_id),
            notes = VALUES(notes)
        ");
        
        foreach ($payments as $payment) {
            $stmt->execute([
                $payment['item_id'],
                $payment['year'],
                $payment['month'] ?? null,
                $payment['is_paid'] ? 1 : 0,
                $payment['paid_date'] ?? null,
                $payment['transaction_id'] ?? null,
                $payment['notes'] ?? null
            ]);
        }
        
        $pdo->commit();
        return true;
    } catch (Exception $e) {
        $pdo->rollback();
        throw new Exception('Error guardando pagos de impuestos/gastos fijos: ' . $e->getMessage());
    }
}

function loadTaxFixedPayments($pdo) {
    $stmt = $pdo->query("
        SELECT item_id, year, month, is_paid, paid_date, transaction_id, notes 
        FROM tax_fixed_payments 
        ORDER BY year DESC, month DESC
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>