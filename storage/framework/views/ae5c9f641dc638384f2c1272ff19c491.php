<?php $__env->startSection('title', 'Ficha cliente'); ?>
<?php $__env->startSection('page-title', 'Ficha cliente'); ?>

<?php $__env->startSection('content'); ?>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="h4 mb-1"><?php echo e($customer->display_name); ?></h2>
            <div class="text-secondary"><?php echo e($customer->email); ?> · <?php echo e($customer->customer_type === 'company' ? 'Empresa' : 'Particular'); ?></div>
        </div>
        <a href="<?php echo e(route('admin.customers.edit', $customer)); ?>" class="btn btn-dark">Editar cliente</a>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-3"><div class="card border-0 shadow-sm"><div class="card-body"><div class="small text-secondary">Pedidos</div><div class="fs-4 fw-bold">0</div></div></div></div>
        <div class="col-md-3"><div class="card border-0 shadow-sm"><div class="card-body"><div class="small text-secondary">Monto comprado</div><div class="fs-4 fw-bold">$0</div></div></div></div>
        <div class="col-md-3"><div class="card border-0 shadow-sm"><div class="card-body"><div class="small text-secondary">Última compra</div><div class="fs-5 fw-bold">Pendiente</div></div></div></div>
        <div class="col-md-3"><div class="card border-0 shadow-sm"><div class="card-body"><div class="small text-secondary">Puntos</div><div class="fs-4 fw-bold"><?php echo e(number_format((float) $customer->reward_points, 0, ',', '.')); ?></div></div></div></div>
    </div>

    <div class="row g-3">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-3"><div class="card-header bg-white fw-semibold">Información general</div><div class="card-body">
                <dl><dt>RUT</dt><dd><?php echo e($customer->rut ?: '-'); ?></dd><dt>Teléfono</dt><dd><?php echo e($customer->phone ?: '-'); ?></dd><dt>Lista precio</dt><dd><?php echo e($customer->preferredPriceList?->name ?: 'Sin preferencia'); ?></dd><dt>Consentimientos</dt><dd><?php echo e($customer->newsletter ? 'Newsletter' : 'Sin newsletter'); ?></dd></dl>
                <?php $__currentLoopData = $customer->tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><span class="badge text-bg-light"><?php echo e($tag->name); ?></span> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div></div>
            <div class="card border-0 shadow-sm"><div class="card-header bg-white fw-semibold">Nueva nota</div><div class="card-body">
                <form method="POST" action="<?php echo e(route('admin.customers.notes.store', $customer)); ?>"><?php echo csrf_field(); ?>
                    <textarea name="note" class="form-control mb-2" rows="3" required></textarea>
                    <label class="form-check mb-2"><input type="checkbox" name="is_private" value="1" class="form-check-input" checked> Nota privada</label>
                    <button class="btn btn-sm btn-dark">Guardar nota</button>
                </form>
            </div></div>
        </div>
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white d-flex justify-content-between"><span class="fw-semibold">Direcciones</span><a class="btn btn-sm btn-outline-primary" href="<?php echo e(route('admin.customers.addresses.create', $customer)); ?>">Agregar</a></div>
                <div class="table-responsive"><table class="table mb-0"><tbody><?php $__empty_1 = true; $__currentLoopData = $customer->addresses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $address): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><tr><td><?php echo e($address->address_type); ?> · <?php echo e($address->address_label); ?></td><td><?php echo e($address->street); ?> <?php echo e($address->number); ?>, <?php echo e($address->commune); ?></td><td><?php echo e($address->is_default ? 'Default' : ''); ?></td><td><a href="<?php echo e(route('admin.customers.addresses.edit', [$customer, $address])); ?>" class="btn btn-sm btn-outline-secondary">Editar</a></td></tr><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><tr><td class="text-secondary">Sin direcciones.</td></tr><?php endif; ?></tbody></table></div>
            </div>
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white d-flex justify-content-between"><span class="fw-semibold">Empresas</span><a class="btn btn-sm btn-outline-primary" href="<?php echo e(route('admin.customers.companies.create', $customer)); ?>">Agregar</a></div>
                <div class="table-responsive"><table class="table mb-0"><tbody><?php $__empty_1 = true; $__currentLoopData = $customer->companies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $company): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><tr><td><?php echo e($company->company_name); ?></td><td><?php echo e($company->rut); ?></td><td><?php echo e($company->business_activity); ?></td><td><a href="<?php echo e(route('admin.customers.companies.edit', [$customer, $company])); ?>" class="btn btn-sm btn-outline-secondary">Editar</a></td></tr><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><tr><td class="text-secondary">Sin empresas.</td></tr><?php endif; ?></tbody></table></div>
            </div>
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white fw-semibold">Documentos</div>
                <div class="card-body">
                    <form method="POST" action="<?php echo e(route('admin.customers.documents.store', $customer)); ?>" enctype="multipart/form-data" class="row g-2 mb-3"><?php echo csrf_field(); ?>
                        <div class="col-md-3"><select name="document_type" class="form-select"><option value="rut">RUT</option><option value="company_certificate">Certificado empresa</option><option value="tax_document">Documento tributario</option><option value="other">Otro</option></select></div>
                        <div class="col-md-4"><input type="file" name="document" class="form-control" required></div>
                        <div class="col-md-3"><input name="description" class="form-control" placeholder="Descripción"></div>
                        <div class="col-md-2"><button class="btn btn-dark w-100">Subir</button></div>
                    </form>
                    <?php $__currentLoopData = $customer->documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $document): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><div class="d-flex justify-content-between border-top py-2"><span><?php echo e($document->document_type); ?> · <?php echo e($document->description); ?></span><form method="POST" action="<?php echo e(route('admin.customers.documents.destroy', [$customer, $document])); ?>"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?><button class="btn btn-sm btn-outline-danger">Eliminar</button></form></div><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-semibold">Puntos y notas</div>
                <div class="card-body">
                    <form method="POST" action="<?php echo e(route('admin.customers.rewards.store', $customer)); ?>" class="row g-2 mb-3"><?php echo csrf_field(); ?>
                        <div class="col-md-3"><select name="type" class="form-select"><option value="earn">Sumar</option><option value="redeem">Canjear</option><option value="adjustment">Ajuste</option></select></div>
                        <div class="col-md-2"><input type="number" step="0.01" name="points" class="form-control" placeholder="Puntos" required></div>
                        <div class="col-md-5"><input name="description" class="form-control" placeholder="Descripción" required></div>
                        <div class="col-md-2"><button class="btn btn-dark w-100">Registrar</button></div>
                    </form>
                    <div class="row g-3">
                        <div class="col-md-6"><h3 class="h6">Historial puntos</h3><?php $__empty_1 = true; $__currentLoopData = $customer->rewardTransactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tx): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><div class="small border-top py-2"><?php echo e($tx->created_at?->format('d/m/Y H:i')); ?> · <?php echo e($tx->type); ?> · <?php echo e($tx->points); ?> · <?php echo e($tx->description); ?></div><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><div class="text-secondary small">Sin movimientos.</div><?php endif; ?></div>
                        <div class="col-md-6"><h3 class="h6">Notas</h3><?php $__empty_1 = true; $__currentLoopData = $customer->notes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $note): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><div class="small border-top py-2"><?php echo e($note->user?->name); ?> · <?php echo e($note->note); ?></div><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><div class="text-secondary small">Sin notas.</div><?php endif; ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\admin\customers\show.blade.php ENDPATH**/ ?>