<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Compra - Sistema Moda</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* FIX DE LAYOUT CRÍTICO: Asegura que el contenido se mueva a la derecha del sidebar fijo */
        @media (min-width: 992px) {
            .offcanvas-lg { position: fixed !important; top: 0; }
            .main-content-area { 
                margin-left: 260px; /* Ancho del sidebar */
                width: calc(100% - 260px);
            }
        }
        @media (max-width: 767px) { .offcanvas-open { overflow: hidden !important; } }
        
        /* Ajuste para el autocompletado que no se oculte detrás de otros elementos */
        #resultados { max-height: 200px; overflow-y: auto; }
    </style>
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-dark d-lg-none shadow-sm">
    <div class="container-fluid">
        <span class="navbar-brand fw-bold">Sistema Moda</span>
        <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu">
            <i class="bi bi-list"></i> Menú
        </button>
    </div>
</nav>

<?php include '../app/views/layouts/sidebar.php'; ?>

<div class="main-content-area" style="height: 100vh; overflow-y: auto;">
    <div class="container-fluid p-4">
        
        <h2 class="fw-bold mb-4"><i class="bi bi-cart-plus-fill"></i> Ingreso de Mercadería (Compras)</h2>

        <div class="row">
            
            <div class="col-lg-8 col-md-12 mb-4">
                
                <div class="card shadow-sm border-0 mb-3">
                    <div class="card-header bg-white fw-bold">Datos de la Factura</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label class="form-label small text-muted fw-bold">PROVEEDOR</label>
                                <select id="proveedor_id" class="form-select">
                                    <?php if(isset($proveedores)): ?>
                                        <?php foreach($proveedores as $prov): ?>
                                            <option value="<?= $prov['id'] ?>"><?= $prov['razon_social'] ?> (<?= $prov['ruc'] ?>)</option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="form-label small text-muted fw-bold">NRO. COMPROBANTE</label>
                                <input type="text" id="comprobante" class="form-control" placeholder="Ej: F001-4568">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0 mb-3">
                    <div class="card-body">
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                            <input type="text" id="buscador" class="form-control border-start-0" placeholder="Buscar producto para agregar..." autocomplete="off">
                        </div>
                        <div id="resultados" class="list-group mt-1 position-absolute w-100 shadow" style="z-index: 1000;"></div>
                    </div>
                </div>

                <div class="card shadow-sm border-0">
                    <div class="card-header bg-dark text-white">Detalle de Productos</div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Producto</th>
                                        <th width="15%">Cantidad</th>
                                        <th width="20%">Costo Unit.</th>
                                        <th width="15%" class="text-end">Subtotal</th>
                                        <th width="5%"></th>
                                    </tr>
                                </thead>
                                <tbody id="carritoBody"></tbody>
                            </table>
                        </div>
                        <div id="emptyCart" class="text-center p-5 text-muted">
                            <i class="bi bi-basket display-4 d-block mb-2"></i>
                            Agrega productos a la compra
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-12">
                <div class="card shadow-sm border-0 sticky-top" style="top: 20px; z-index: 1;">
                    <div class="card-body text-center p-4">
                        <h5 class="text-muted mb-3">Total a Pagar</h5>
                        <h1 class="fw-bold text-primary mb-4">S/ <span id="totalCompra">0.00</span></h1>
                        
                        <div class="d-grid gap-2">
                            <button class="btn btn-success btn-lg fw-bold" onclick="guardarCompra()">
                                <i class="bi bi-save me-2"></i> GUARDAR INGRESO
                            </button>
                            <a href="<?= BASE_URL ?>/ventas" class="btn btn-outline-secondary">
                                Cancelar
                            </a>
                        </div>
                    </div>
                    <div class="card-footer bg-light text-muted small text-center">
                        * Esto aumentará el stock y actualizará el precio de costo.
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const BASE_URL = '<?= BASE_URL ?>';
    const buscador = document.getElementById('buscador');
    const resultados = document.getElementById('resultados');
    const carritoBody = document.getElementById('carritoBody');
    const totalSpan = document.getElementById('totalCompra');
    const emptyCart = document.getElementById('emptyCart');
    
    let carrito = [];
    let timeoutBusqueda = null;

    // BUSCADOR CON RETRASO (DEBOUNCE)
    buscador.addEventListener('keyup', function() {
        clearTimeout(timeoutBusqueda);
        const termino = this.value;
        
        if(termino.length > 2) {
            timeoutBusqueda = setTimeout(() => {
                fetch(`${BASE_URL}/ventas/buscar/${termino}`)
                    .then(r => r.json())
                    .then(data => {
                        resultados.innerHTML = '';
                        if (data.length === 0) {
                             resultados.innerHTML = '<div class="list-group-item text-muted">No encontrado</div>';
                        } else {
                            data.forEach(p => {
                                resultados.innerHTML += `
                                    <button class="list-group-item list-group-item-action" onclick='agregar(${JSON.stringify(p)})'>
                                        <strong>${p.nombre}</strong> <br>
                                        <small class="text-muted">${p.talla} / ${p.color}</small>
                                    </button>`;
                            });
                        }
                    });
            }, 300);
        } else { 
            resultados.innerHTML = ''; 
        }
    });

    // AGREGAR AL CARRITO
    function agregar(prod) {
        resultados.innerHTML = '';
        buscador.value = '';
        
        // Verificar si ya está
        let existe = carrito.find(i => i.variante_id === prod.variante_id);
        if(existe) {
            Swal.fire('Producto ya agregado', 'Modifica la cantidad en la tabla si deseas sumar más.', 'warning');
            return;
        }

        carrito.push({
            variante_id: prod.variante_id,
            nombre: prod.nombre,
            talla: prod.talla,
            color: prod.color,
            cantidad: 1,
            costo: 0.00 // Costo inicial 0 para obligar a ingresar
        });
        renderizar();
    }

    // RENDERIZAR TABLA
    function renderizar() {
        carritoBody.innerHTML = '';
        let total = 0;

        if(carrito.length > 0) emptyCart.style.display = 'none';
        else emptyCart.style.display = 'block';

        carrito.forEach((item, index) => {
            let subtotal = item.cantidad * item.costo;
            total += subtotal;

            carritoBody.innerHTML += `
                <tr>
                    <td>
                        <div class="fw-bold small text-truncate" style="max-width: 200px;">${item.nombre}</div>
                        <span class="badge bg-secondary" style="font-size: 0.7rem;">${item.talla}</span> 
                        <span class="badge bg-light text-dark border" style="font-size: 0.7rem;">${item.color}</span>
                    </td>
                    <td>
                        <input type="number" class="form-control form-control-sm text-center" value="${item.cantidad}" min="1" 
                               onchange="updateItem(${index}, 'cantidad', this.value)">
                    </td>
                    <td>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">S/</span>
                            <input type="number" class="form-control fw-bold" value="${item.costo}" min="0" step="0.01" 
                                   onchange="updateItem(${index}, 'costo', this.value)">
                        </div>
                    </td>
                    <td class="text-end fw-bold">S/ ${subtotal.toFixed(2)}</td>
                    <td class="text-end"><button class="btn btn-sm btn-outline-danger border-0" onclick="eliminar(${index})"><i class="bi bi-trash"></i></button></td>
                </tr>
            `;
        });
        totalSpan.innerText = total.toFixed(2);
    }

    function updateItem(index, campo, valor) {
        valor = parseFloat(valor) || 0;
        if(campo === 'cantidad' && valor < 1) valor = 1;
        carrito[index][campo] = valor;
        renderizar();
    }

    function eliminar(index) {
        carrito.splice(index, 1);
        renderizar();
    }

    // GUARDAR COMPRA
    function guardarCompra() {
        if(carrito.length === 0) { Swal.fire('Error', 'El carrito de compras está vacío.', 'warning'); return; }
        
        // Validar costo cero
        if(carrito.some(i => i.costo <= 0)) {
            Swal.fire('Atención', 'Hay productos con Costo 0. Por favor ingresa el costo unitario real.', 'warning');
            return;
        }

        const provId = document.getElementById('proveedor_id').value;
        const comp = document.getElementById('comprobante').value;
        const total = document.getElementById('totalCompra').innerText;

        // Enviar al servidor
        fetch(`${BASE_URL}/compra/guardar`, {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({
                proveedor_id: provId,
                comprobante: comp,
                carrito: carrito,
                total: total
            })
        })
        .then(r => r.json())
        .then(data => {
            if(data.status) {
                Swal.fire({
                    title: '¡Compra Registrada!',
                    text: 'El stock ha sido actualizado correctamente.',
                    icon: 'success'
                }).then(() => {
                    // Recargar para limpiar
                    window.location.reload();
                });
            } else {
                Swal.fire('Error', data.message, 'error');
            }
        })
        .catch(error => {
            console.error(error);
            Swal.fire('Error', 'No se pudo procesar la solicitud.', 'error');
        });
    }
</script>

</body>
</html>