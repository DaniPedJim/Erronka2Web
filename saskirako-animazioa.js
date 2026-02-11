document.addEventListener("DOMContentLoaded", () => {

    // Aukeratu guztiak "Erosi" botoiak
    document.querySelectorAll("[data-erosi]").forEach(botoi => {

        botoi.addEventListener("click", e => {
            e.preventDefault(); // Formularioak ez du orria berriro kargatuko

            const produktua = botoi.closest(".produktu");
            if (!produktua) return;

            const irudia = produktua.querySelector(".producto-img");
            const saskiak = document.querySelectorAll(".carrito-icon");
            if (!irudia || saskiak.length === 0) return;

            // PC edo mugikorretarako ikusten dena aukeratu
            let saskia;
            for (let c of saskiak) {
                if (c.offsetParent !== null) { // ikusten dena
                    saskia = c;
                    break;
                }
            }
            if (!saskia) return;

            // Irudiaren eta saskiaren posizioak
            const irudiRect = irudia.getBoundingClientRect();
            const saskiRect = saskia.getBoundingClientRect();

            // Irudia klonatu animazioa egiteko
            const irudiKlona = irudia.cloneNode(true);
            irudiKlona.classList.add("fly-img");

            irudiKlona.style.position = "fixed";
            irudiKlona.style.left = irudiRect.left + "px";
            irudiKlona.style.top = irudiRect.top + "px";
            irudiKlona.style.width = irudiRect.width + "px";
            irudiKlona.style.transition = "all 0.8s ease";

            document.body.appendChild(irudiKlona);

            // Forzar repaint (animazioa funtziona dezan)
            irudiKlona.getBoundingClientRect();

            // Animazioa: irudia saskira joaten da
            irudiKlona.style.left = saskiRect.left + "px";
            irudiKlona.style.top = saskiRect.top + "px";
            irudiKlona.style.width = "30px";
            irudiKlona.style.opacity = "0.5";

            // 800ms gero, klona ezabatu
            setTimeout(() => {
                irudiKlona.remove();

                // Kontagailua eguneratu (aukerakoa)
                const contador = saskia.querySelector(".contador");
                if (contador) {
                    let current = parseInt(contador.textContent);
                    contador.textContent = current + 1;
                }

                // Formularioa bidali PHP-rako
                botoi.closest("form").submit();

            }, 800);

        });

    });

});
