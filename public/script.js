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