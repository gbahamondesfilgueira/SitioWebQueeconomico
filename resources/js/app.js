import './bootstrap';
import * as bootstrap from 'bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;
window.bootstrap = bootstrap;

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

const stockLocation = document.querySelector('[data-stock-location]');

if (stockLocation) {
    const detectButton = stockLocation.querySelector('[data-location-detect]');
    const status = stockLocation.querySelector('[data-location-status]');
    const locationUrl = stockLocation.dataset.locationUrl;
    const currentLocalRegion = stockLocation.dataset.currentLocalRegion;
    let locating = false;

    const setLocationStatus = (message, isError = false) => {
        if (!status) {
            return;
        }

        status.textContent = message;
        status.classList.toggle('text-danger', isError);
        status.classList.toggle('text-secondary', !isError);
    };

    const locationErrorMessage = (error) => {
        if (error?.code === error?.PERMISSION_DENIED) {
            return 'Permiso denegado. Puedes activarlo desde la configuración del navegador.';
        }

        if (error?.code === error?.TIMEOUT) {
            return 'La ubicación tardó demasiado. Inténtalo nuevamente.';
        }

        return 'No pudimos obtener tu ubicación actual.';
    };

    const detectLocation = () => {
        if (locating) {
            return;
        }

        if (!window.isSecureContext) {
            setLocationStatus('La ubicación requiere una conexión HTTPS segura.', true);

            return;
        }

        if (!navigator.geolocation || !locationUrl) {
            setLocationStatus('Tu navegador no admite ubicación.', true);

            return;
        }

        locating = true;
        const originalText = detectButton?.textContent;
        setLocationStatus('Detectando ubicación...');

        if (detectButton) {
            detectButton.disabled = true;
            detectButton.textContent = 'Detectando...';
        }

        navigator.geolocation.getCurrentPosition(async (position) => {
            try {
                const response = await fetch(locationUrl, {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        Accept: 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: JSON.stringify({
                        latitude: position.coords.latitude,
                        longitude: position.coords.longitude,
                        accuracy: position.coords.accuracy,
                    }),
                });
                const data = await response.json().catch(() => ({}));

                if (!response.ok) {
                    throw new Error(data.message || 'No pudimos determinar tu región.');
                }

                setLocationStatus(`Ubicación detectada: ${data.region_label}.`);
                window.location.reload();
            } catch (error) {
                setLocationStatus(error.message || 'No pudimos actualizar el stock cercano.', true);
                locating = false;

                if (detectButton) {
                    detectButton.disabled = false;
                    detectButton.textContent = originalText;
                }
            }
        }, (error) => {
            setLocationStatus(locationErrorMessage(error), true);
            locating = false;

            if (detectButton) {
                detectButton.disabled = false;
                detectButton.textContent = originalText;
            }
        }, {
            enableHighAccuracy: false,
            timeout: 10000,
            maximumAge: 300000,
        });
    };

    detectButton?.addEventListener('click', detectLocation);

    if (!currentLocalRegion && navigator.permissions?.query) {
        navigator.permissions.query({ name: 'geolocation' })
            .then((permission) => {
                if (permission.state === 'granted') {
                    detectLocation();
                }
            })
            .catch(() => {});
    }
}

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
                if (response.status === 401) {
                    const authModal = document.getElementById('authModal');

                    if (authModal) {
                        bootstrap.Modal.getOrCreateInstance(authModal).show();
                    }
                }

                if (response.status === 422 && data.redirect) {
                    window.location.assign(data.redirect);

                    return;
                }

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
        const uploadInput = document.querySelector('[data-primary-upload-input]');

        if (!input || !preview) {
            return;
        }

        input.value = button.dataset.mediaPrimary || '';
        preview.innerHTML = `<img src="${button.dataset.mediaUrl}" class="img-fluid rounded" alt="Imagen principal">`;

        if (uploadInput) {
            uploadInput.value = '';
        }
    });
});

document.querySelectorAll('[data-primary-upload-input]').forEach((uploadInput) => {
    const modal = uploadInput.closest('.modal');
    const applyButton = modal?.querySelector('[data-apply-primary-upload]');
    const status = modal?.querySelector('[data-primary-upload-status]');

    uploadInput.addEventListener('change', () => {
        const file = uploadInput.files?.[0];

        if (applyButton) {
            applyButton.disabled = !file;
        }

        if (status) {
            status.textContent = file
                ? `${file.name} está lista para seleccionarse como principal.`
                : 'Selecciona un archivo y presiona "Usar como imagen principal".';
        }
    });

    applyButton?.addEventListener('click', () => {
        const file = uploadInput.files?.[0];
        const selectedPath = document.querySelector('[data-primary-media-input]');
        const preview = document.querySelector('[data-primary-media-preview]');

        if (!file || !preview) {
            return;
        }

        if (selectedPath) {
            selectedPath.value = '';
        }

        const previewUrl = URL.createObjectURL(file);
        preview.innerHTML = `<img src="${previewUrl}" class="img-fluid rounded" alt="Vista previa de imagen principal"><div class="small text-success mt-2">Se guardará como imagen principal.</div>`;

        if (modal) {
            bootstrap.Modal.getOrCreateInstance(modal).hide();
        }
    });
});

document.querySelectorAll('[data-product-purchase-form]').forEach((form) => {
    const select = form.querySelector('[data-product-variant-select]');
    const button = form.querySelector('[data-product-add-button]');
    const stockBadge = document.querySelector('[data-product-stock-badge]');
    const localStockBadge = document.querySelector('[data-product-local-stock-badge]');

    if (!select || !button) {
        return;
    }

    const syncVariantState = () => {
        const option = select.selectedOptions[0];
        const stock = Number(option?.dataset.stock || 0);
        const label = option?.dataset.stockLabel || 'Sin stock';
        const localStock = Number(option?.dataset.localStock || 0);
        const localStockLabel = option?.dataset.localStockLabel || 'Sin stock';

        button.disabled = stock < 1;
        if (stockBadge) {
            stockBadge.textContent = label;
            stockBadge.className = `badge ${stock < 1 ? 'text-bg-secondary' : (stock <= 5 ? 'text-bg-warning' : 'text-bg-success')}`;
        }
        if (localStockBadge) {
            localStockBadge.textContent = localStockLabel;
            localStockBadge.className = `badge ${localStock < 1 ? 'text-bg-secondary' : (localStock <= 5 ? 'text-bg-warning' : 'text-bg-success')}`;
        }
    };

    select.addEventListener('change', syncVariantState);
    syncVariantState();
});
