document.addEventListener("DOMContentLoaded", () => {

    /* =========================
       PRODUCTOS / MANTENIMIENTOS
       ========================= */

    const productosContainer = document.getElementById("productos");
    const selectClase = document.getElementById("id_clase");
    const btnAgregar = document.getElementById("btnAgregarProducto");

    // Crear usa precio_total | Editar usa name="precio"
    const precioTotalInput = document.getElementById("precio_total");


    function obtenerPrecioProducto(select) {
        const option = select.options[select.selectedIndex];
        return parseFloat(
            option.dataset.precioVenta || option.dataset.precio || 0
        );
    }

    /* Normaliza botones eliminar existentes para asegurar el SVG y aria-label
       Solo aplica a elementos <button>, no a enlaces <a> para no borrar texto. */
    function ensureBtnSvg(btn) {
        if (!btn) return;
        // Solo transformar botones (no enlaces con texto)
        if (btn.tagName && btn.tagName.toLowerCase() !== 'button') return;
        if (btn.querySelector('svg')) return;
        btn.setAttribute('aria-label', 'Eliminar producto');
        btn.setAttribute('title', 'Eliminar producto');
        btn.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        `;
    }

    function calcularTotal() {
        let total = 0;

        /* -------- Precio clase -------- */
        if (selectClase && selectClase.selectedIndex >= 0) {
            const clase = selectClase.options[selectClase.selectedIndex];
            total += parseFloat(clase.dataset.precio) || 0;
        }

        /* -------- Productos -------- */
        document.querySelectorAll(".fila-producto").forEach(fila => {
            const select = fila.querySelector(".prod-select");
            const cantInput = fila.querySelector('input[name="prod_cant[]"]');
            const puInput = fila.querySelector('input[name="prod_pu[]"]');

            if (!select || !cantInput || !puInput) return;

            const precioUnit = obtenerPrecioProducto(select);
            const cantidad = parseFloat(cantInput.value) || 0;

            puInput.value = precioUnit.toFixed(2);
            total += precioUnit * cantidad;
        });

        if (precioTotalInput) {
            precioTotalInput.value = total.toFixed(2);
        }
    }

    /* -------- Agregar producto -------- */
    if (btnAgregar && productosContainer) {
        btnAgregar.addEventListener("click", () => {
            const base = productosContainer.querySelector(".fila-producto");
            if (!base) return;

            const nueva = base.cloneNode(true);

            nueva.querySelector(".prod-select").value = "";
            nueva.querySelector('input[name="prod_cant[]"]').value = 1;
            nueva.querySelector('input[name="prod_pu[]"]').value = "";

            // Normalizar: si la fila clonada ya trae un <button>, reutilizarlo;
            // en caso contrario crear uno nuevo. Evita duplicados.
            const existingButtonElement = nueva.querySelector('button');
            if (existingButtonElement) {
                // Asegurar clase y SVG
                existingButtonElement.className = 'btn-eliminar-row';
                ensureBtnSvg(existingButtonElement);
            } else {
                const btn = document.createElement("button");
                btn.type = "button";
                btn.className = "btn-eliminar-row";
                ensureBtnSvg(btn);
                nueva.appendChild(btn);
            }

            productosContainer.appendChild(nueva);
            calcularTotal();
        });
    }

    /* -------- Eliminar producto -------- */
    if (productosContainer) {
        productosContainer.addEventListener("click", e => {
            if (e.target.classList.contains("btn-eliminar-row") || e.target.closest('.btn-eliminar-row')) {
                const btn = e.target.closest('.btn-eliminar-row') || e.target;
                const filas = productosContainer.querySelectorAll(".fila-producto");
                if (filas.length > 1) {
                    btn.closest(".fila-producto").remove();
                    calcularTotal();
                }
            }
        });
    }

    /* -------- Eventos de recalculo -------- */
    document.addEventListener("change", e => {
        if (
            e.target.matches(".prod-select") ||
            e.target.matches('input[name="prod_cant[]"]') ||
            e.target === selectClase
        ) {
            calcularTotal();
        }
    });

    document.addEventListener("input", e => {
        if (e.target.matches('input[name="prod_cant[]"]')) {
            calcularTotal();
        }
    });

    calcularTotal();

    // Normalizar botones de fila existentes al cargar (solo botones)
    document.querySelectorAll('button.btn-eliminar-row').forEach(b => ensureBtnSvg(b));

    /* =========================
       ACORDEÓN
       ========================= */

    document.querySelectorAll(".accordion-header").forEach(header => {
        header.addEventListener("click", () => {
            const content = header.nextElementSibling;
            if (content) {
                content.classList.toggle("activo");
            }
        });
    });

});
function menuFuncion() {
    var x = document.getElementById("menu");
    if (x.style.display === "block") {
        x.style.display = "none";
    } else {
        x.style.display = "block";
    }
}

// Asignar tooltips (`title`) automáticos a botones y enlaces que no tengan uno.
document.addEventListener("DOMContentLoaded", () => {
    function capitalize(str) {
        if (!str) return '';
        return str.charAt(0).toUpperCase() + str.slice(1);
    }

    function generateTooltipFromText(text) {
        if (!text) return '';
        let t = text.replace(/[\n\r\t]+/g, ' ').trim();
        // quitar símbolos iniciales como +, >, •
        t = t.replace(/^[^0-9A-Za-zÁÉÍÓÚáéíóúÑñ]+/, '').trim();
        if (!t) return '';

        const lower = t.toLowerCase();
        const words = lower.split(/\s+/);

        if (lower.includes('nuevo')) {
            const idx = words.indexOf('nuevo');
            const noun = words.slice(idx + 1).join(' ') || words[words.length - 1];
            return 'Crear nuevo ' + capitalize(noun);
        }

        if (lower.includes('mantenimiento') && (text.indexOf('+') >= 0 || lower.includes('nuevo'))) {
            return 'Crear nuevo mantenimiento';
        }

        if (/(agregar|añadir|agrega)/i.test(t)) {
            const noun = t.replace(/(agregar|añadir|agrega)/i, '').trim();
            return 'Agregar ' + (noun ? capitalize(noun) : 'elemento');
        }

        if (/editar/i.test(t)) {
            const noun = t.replace(/editar/i, '').trim();
            return 'Editar ' + (noun ? capitalize(noun) : 'elemento');
        }

        if (/(eliminar|quitar|borrar)/i.test(t)) {
            const noun = t.replace(/(eliminar|quitar|borrar)/i, '').trim();
            return 'Eliminar ' + (noun ? capitalize(noun) : 'elemento');
        }

        // Fallback: devolver el texto con capitalización inicial
        return capitalize(t);
    }

    const classMap = {
        'btn-crear': 'Crear nuevo elemento',
        'btn-guardar': 'Guardar cambios',
        'btn-confirmar': 'Confirmar pedido y actualizar stock',
        'btn-eliminar-table': 'Eliminar elemento',
        'btn-eliminar-row': 'Eliminar producto',
        'btn-volver': 'Volver',
        'btn-generar-pdf': 'Generar PDF',
        'btn-editar': 'Editar elemento',
        'btn-ver': 'Ver detalles',
        'btn-secundario': 'Acción',
        'btn-cancelar': 'Cancelar',
        'btn-buscar': 'Buscar',
        'btn-limpiar': 'Limpiar filtros',
        'btn-registrarse': 'Registrarse',
        'btn-ingresar': 'Ingresar',
        'btn-pagina': 'Ir a la página'
    };

    function setTooltip(el) {
        if (!el) return;
        // no sobreescribir titles ya existentes
        if (el.getAttribute('title')) return;

        // Priorizar atributos explícitos
        if (el.getAttribute('data-tooltip')) {
            el.setAttribute('title', el.getAttribute('data-tooltip'));
            return;
        }

        if (el.getAttribute('aria-label')) {
            el.setAttribute('title', el.getAttribute('aria-label'));
            return;
        }

        // Mapear por clase si aplica
        if (el.classList && el.classList.contains('btn-pagina')) {
            const text = (el.textContent || '').toString().trim();
            const num = text || '';
            el.setAttribute('title', classMap['btn-pagina'] + (num ? ' ' + num : ''));
            return;
        }

        for (const cls in classMap) {
            if (el.classList && el.classList.contains(cls)) {
                el.setAttribute('title', classMap[cls]);
                return;
            }
        }

        // Generar a partir del texto (maneja '+ nuevo mantenimiento' -> 'Crear nuevo mantenimiento')
        const text = (el.textContent || '').toString().trim();
        const generated = generateTooltipFromText(text);
        if (generated) {
            el.setAttribute('title', generated);
            return;
        }

        // Último recurso: tipo de elemento
        const tag = el.tagName ? el.tagName.toLowerCase() : 'elemento';
        if (tag === 'a') el.setAttribute('title', 'Abrir enlace');
        else el.setAttribute('title', 'Acción');
    }

    const selectors = 'a, button, input[type="button"], input[type="submit"], [role="button"]';
    document.querySelectorAll(selectors).forEach(setTooltip);
});