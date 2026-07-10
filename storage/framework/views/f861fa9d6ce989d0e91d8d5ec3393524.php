<form method="POST" action="<?php echo e(route('pos.barcode.add')); ?>" class="row g-2 mb-3">
    <?php echo csrf_field(); ?>
    <div class="col-md-9">
        <input name="barcode" class="form-control form-control-lg" placeholder="Escanear código de barras o escribir SKU">
    </div>
    <div class="col-md-2"><input name="quantity" type="number" min="1" value="1" class="form-control form-control-lg"></div>
    <div class="col-md-1 d-grid"><button class="btn btn-primary btn-lg">+</button></div>
</form>
<form method="GET" action="<?php echo e(route('pos.sale.create')); ?>" class="mb-3">
    <input id="posSearch" class="form-control" placeholder="Buscar por nombre, SKU, barcode, marca o categoría">
</form>
<div id="posSearchResults" class="list-group mb-3"></div>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const input = document.getElementById('posSearch');
    const results = document.getElementById('posSearchResults');
    let timer;
    input?.addEventListener('input', () => {
        clearTimeout(timer);
        timer = setTimeout(async () => {
            const q = input.value.trim();
            if (q.length < 2) { results.innerHTML = ''; return; }
            const response = await fetch(`<?php echo e(route('pos.search')); ?>?q=${encodeURIComponent(q)}`);
            const rows = await response.json();
            results.innerHTML = rows.map(row => `
                <form method="POST" action="<?php echo e(route('pos.cart.add')); ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                    <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
                    <input type="hidden" name="product_id" value="${row.product_id ?? ''}">
                    <input type="hidden" name="variant_id" value="${row.variant_id ?? ''}">
                    <input type="hidden" name="pack_id" value="${row.pack_id ?? ''}">
                    <input type="hidden" name="quantity" value="1">
                    <span><strong>${row.name}</strong> ${row.variant_name ? `<span class="text-muted">${row.variant_name}</span>` : ''}<br><small>${row.sku ?? ''} · Stock: ${row.stock}</small></span>
                    <span class="d-flex gap-2 align-items-center"><strong>$${Number(row.price).toLocaleString('es-CL')}</strong><button class="btn btn-sm btn-primary">Agregar</button></span>
                </form>`).join('');
        }, 250);
    });
});
</script>
<?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\pos\components\product-search.blade.php ENDPATH**/ ?>