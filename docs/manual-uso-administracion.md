# Manual de uso administracion Que Economico

## 1. Crear un producto simple

1. Entra a **Admin > Productos > Productos**.
2. Presiona **Crear producto**.
3. Completa nombre, categoria, marca, precios y descripcion.
4. Si no completas el SKU, el sistema genera uno automatico con formato `QE-0001`.
5. Marca **Activo** y **Visible** para que aparezca en la tienda publica.
6. Sube una o varias imagenes.
7. Guarda.

Nota: si desmarcas **Visible**, el producto no aparece en la tienda publica, pero sigue existiendo en el administrador.

## 2. Crear un producto variable

1. Crea el producto con tipo **Variable**.
2. Guarda el producto.
3. Entra a **Gestionar variantes**.
4. Crea cada variante con SKU, precio, atributos e imagen si corresponde.
5. Cuando tenga al menos una variante activa, vuelve al producto y marca **Visible**.

## 3. Agregar stock a un producto

1. El producto debe existir primero.
2. Entra al producto y presiona **Agregar stock**.
3. Selecciona bodega, producto, variante si corresponde, tipo **increase**, cantidad y motivo.
4. Guarda el ajuste.
5. Si eres Super Admin o Administrador, aprueba el ajuste.

El stock no se edita directamente desde el producto. Se carga mediante **Inventario > Ajustes** para dejar Kardex.

## 4. Ver pedidos nuevos

1. Entra al Dashboard admin.
2. Si hay pedidos nuevos, veras una alerta superior.
3. Presiona **Ver pedidos**.
4. Gestiona estado, pago, preparacion y despacho desde el detalle del pedido.

## 5. Pago por transferencia

Cuando el cliente confirma un pedido con transferencia bancaria, la pagina de confirmacion muestra un boton de WhatsApp con el numero de pedido.

## 6. Importar productos

1. Prepara el CSV con el formato importable del sistema.
2. Sube productos desde el modulo de importacion masiva.
3. Revisa los productos creados.
4. Luego carga stock con ajustes de inventario.

## 7. Direcciones de clientes

El cliente puede agregar direcciones desde **Mi cuenta > Mis Direcciones**.
En checkout puede seleccionar una direccion guardada y el sistema llena los campos automaticamente.
