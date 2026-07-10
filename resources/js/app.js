import './bootstrap';
import 'bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

const sidebarToggle = document.querySelector('[data-sidebar-toggle]');

if (sidebarToggle) {
    const storageKey = 'qe-admin-sidebar-collapsed';
    const applyState = () => {
        document.body.classList.toggle('admin-sidebar-collapsed', localStorage.getItem(storageKey) === '1');
    };

    applyState();

    sidebarToggle.addEventListener('click', () => {
        const nextState = document.body.classList.contains('admin-sidebar-collapsed') ? '0' : '1';
        localStorage.setItem(storageKey, nextState);
        applyState();
    });
}

const catalogOffcanvas = document.getElementById('catalogOffcanvas');

if (catalogOffcanvas) {
    catalogOffcanvas.addEventListener('shown.bs.offcanvas', () => {
        document.body.classList.add('catalog-open-active');
    });

    catalogOffcanvas.addEventListener('hidden.bs.offcanvas', () => {
        document.body.classList.remove('catalog-open-active');
    });
}

document.querySelectorAll('[data-catalog-shell]').forEach((catalogShell) => {
    const triggers = catalogShell.querySelectorAll('[data-catalog-panel-trigger]');
    const panels = catalogShell.querySelectorAll('[data-catalog-panel]');

    const activatePanel = (panelKey) => {
        triggers.forEach((trigger) => {
            trigger.classList.toggle('active', trigger.dataset.catalogPanelTrigger === panelKey);
        });

        panels.forEach((panel) => {
            panel.classList.toggle('active', panel.dataset.catalogPanel === panelKey);
        });
    };

    triggers.forEach((trigger) => {
        trigger.addEventListener('mouseenter', () => activatePanel(trigger.dataset.catalogPanelTrigger));
        trigger.addEventListener('focus', () => activatePanel(trigger.dataset.catalogPanelTrigger));
        trigger.addEventListener('click', (event) => {
            event.preventDefault();
            activatePanel(trigger.dataset.catalogPanelTrigger);
            triggers.forEach((item) => item.setAttribute('aria-expanded', item === trigger ? 'true' : 'false'));
        });
    });
});

const cartToast = document.querySelector('[data-cart-toast]');
const cartCountBadge = document.querySelector('[data-cart-count]');
let cartToastTimer;

const showCartToast = (message, isError = false) => {
    if (!cartToast) {
        return;
    }

    cartToast.textContent = message;
    cartToast.classList.toggle('is-error', isError);
    cartToast.hidden = false;

    clearTimeout(cartToastTimer);
    cartToastTimer = setTimeout(() => {
        cartToast.hidden = true;
    }, 2800);
};

const updateCartCount = (count) => {
    if (!cartCountBadge) {
        return;
    }

    const value = Number(count || 0);
    cartCountBadge.textContent = value;
    cartCountBadge.hidden = value < 1;
};

document.querySelectorAll('[data-cart-add-form]').forEach((form) => {
    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        const button = form.querySelector('button[type="submit"], button:not([type])');
        const originalText = button?.textContent;

        if (button) {
            button.disabled = true;
            button.textContent = 'Agregando...';
        }

        try {
            const response = await fetch(form.action, {
                method: form.method || 'POST',
                headers: {
                    Accept: 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: new FormData(form),
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'No se pudo agregar el producto.');
            }

            updateCartCount(data.cart_count);
            showCartToast(data.message || 'Producto agregado al carrito.');
        } catch (error) {
            showCartToast(error.message || 'No se pudo agregar el producto.', true);
        } finally {
            if (button) {
                button.disabled = false;
                button.textContent = originalText;
            }
        }
    });
});

document.querySelectorAll('[data-address-select]').forEach((select) => {
    const form = select.closest('form');
    const fillAddress = () => {
        const option = select.selectedOptions[0];
        if (!form || !option || !option.dataset.address) {
            return;
        }

        const address = JSON.parse(option.dataset.address);
        Object.entries(address).forEach(([field, value]) => {
            const input = form.querySelector(`[name="${field}"]`);
            if (input) {
                input.value = value || '';
            }
        });
    };

    select.addEventListener('change', fillAddress);
});

document.querySelectorAll('[data-same-as-shipping]').forEach((checkbox) => {
    checkbox.addEventListener('change', () => {
        const billingForm = checkbox.closest('form');
        const shippingForm = document.querySelector('[data-checkout-shipping-form]');

        if (!billingForm || !shippingForm || !checkbox.checked) {
            return;
        }

        ['contact_name', 'phone', 'email', 'country', 'region', 'commune', 'city', 'street', 'number', 'apartment', 'postal_code', 'reference'].forEach((field) => {
            const source = shippingForm.querySelector(`[name="${field}"]`);
            const target = billingForm.querySelector(`[name="${field}"]`);

            if (source && target) {
                target.value = source.value;
            }
        });
    });
});

document.querySelectorAll('[data-media-primary]').forEach((button) => {
    button.addEventListener('click', () => {
        const input = document.querySelector('[data-primary-media-input]');
        const preview = document.querySelector('[data-primary-media-preview]');

        if (!input || !preview) {
            return;
        }

        input.value = button.dataset.mediaPrimary || '';
        preview.innerHTML = `<img src="${button.dataset.mediaUrl}" class="img-fluid rounded" alt="Imagen principal">`;
    });
});

document.querySelectorAll('[data-product-purchase-form]').forEach((form) => {
    const select = form.querySelector('[data-product-variant-select]');
    const button = form.querySelector('[data-product-add-button]');
    const stockBadge = document.querySelector('[data-product-stock-badge]');

    if (!select || !button) {
        return;
    }

    const syncVariantState = () => {
        const option = select.selectedOptions[0];
        const stock = Number(option?.dataset.stock || 0);
        const label = option?.dataset.stockLabel || 'Sin stock';

        button.disabled = stock < 1;
        if (stockBadge) {
            stockBadge.textContent = label;
            stockBadge.className = `badge ${stock < 1 ? 'text-bg-secondary' : (stock <= 5 ? 'text-bg-warning' : 'text-bg-success')}`;
        }
    };

    select.addEventListener('change', syncVariantState);
    syncVariantState();
});
