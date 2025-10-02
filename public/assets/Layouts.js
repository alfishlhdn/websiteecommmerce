// Layouts.js — versi aman dan tahan error
document.addEventListener('DOMContentLoaded', function () {
    // -------------------------
    // Slider utama (banner)
    // -------------------------
    let currentIndex = 0;
    const slider = document.getElementById("slider");
    const dots = document.querySelectorAll(".dot");

    function updateSlider(index) {
        if (!slider) return;

        // pastikan ada children
        if (!slider.children || slider.children.length === 0) return;

        const firstChild = slider.children[0];
        if (!firstChild || typeof firstChild.clientWidth !== 'number') return;

        const slideWidth = firstChild.clientWidth;
        slider.style.transform = `translateX(-${index * slideWidth}px)`;

        if (dots && dots.length) {
            dots.forEach((dot, i) => {
                dot.classList.toggle("bg-gray-800", i === index);
                dot.classList.toggle("bg-gray-400", i !== index);
            });
        }

        currentIndex = index;
    }

    function nextSlide() {
        if (!slider || !slider.children || slider.children.length === 0) return;
        const newIndex = (currentIndex + 1) % slider.children.length;
        updateSlider(newIndex);
    }

    function prevSlide() {
        if (!slider || !slider.children || slider.children.length === 0) return;
        const newIndex = (currentIndex - 1 + slider.children.length) % slider.children.length;
        updateSlider(newIndex);
    }

    function goToSlide(index) {
        updateSlider(index);
    }

    // kalau tidak ada slider, jangan setInterval
    if (slider && slider.children && slider.children.length > 0) {
        // Optional: auto slide (atur delay sesuai kebutuhan)
        setInterval(() => {
            nextSlide();
        }, 5000);
    } else {
        // debug-friendly
        // console.info('Slider tidak ditemukan atau tidak punya children — auto slide dimatikan.');
    }

    // expose fungsi jika butuh akses global
    window.nextSlideBanner = nextSlide;
    window.prevSlideBanner = prevSlide;
    window.goToSlideBanner = goToSlide;

    // -------------------------
    // Script search popup / suggestions
    // -------------------------
    const allSuggestions = [
        "Laptop ASUS",
        "Keyboard Gaming",
        'Monitor LED 24"',
        "SSD 512GB",
        "Printer Canon",
    ];

    function filterSuggestions(input) {
        const suggestionBox = document.getElementById("suggestions");
        if (!suggestionBox) return;

        const search = (input || '').trim().toLowerCase();

        // Bersihkan isi lama
        suggestionBox.innerHTML = "";

        if (search === "") {
            suggestionBox.classList.add("hidden");
            return;
        }

        const matched = allSuggestions.filter((item) =>
            item.toLowerCase().includes(search)
        );

        if (matched.length > 0) {
            matched.forEach((item) => {
                const li = document.createElement("li");
                li.textContent = item;
                li.className = "px-4 py-2 hover:bg-gray-100 cursor-pointer";
                li.onclick = () => selectSuggestion(li);
                suggestionBox.appendChild(li);
            });
        } else {
            const li = document.createElement("li");
            li.textContent = "Tidak ada hasil ditemukan";
            li.className = "px-4 py-2 text-gray-500 italic";
            suggestionBox.appendChild(li);
        }

        suggestionBox.classList.remove("hidden");
    }

    function selectSuggestion(element) {
        const inputEl = document.getElementById("searchInput");
        const suggestionBox = document.getElementById("suggestions");
        if (!inputEl || !suggestionBox || !element) return;
        inputEl.value = element.textContent;
        suggestionBox.classList.add("hidden");
    }

    // Sembunyikan dropdown jika klik di luar area (safely)
    document.addEventListener("click", function (event) {
        const input = document.getElementById("searchInput");
        const suggestions = document.getElementById("suggestions");

        // jika kedua elemen tidak ada, hentikan
        if (!input || !suggestions) return;

        // cek contains dengan aman
        const clickedInsideInput = input.contains ? input.contains(event.target) : false;
        const clickedInsideSuggestions = suggestions.contains ? suggestions.contains(event.target) : false;

        if (!clickedInsideInput && !clickedInsideSuggestions) {
            suggestions.classList.add("hidden");
        }
    });

    // -------------------------
    // Slider horizontal kecil (rekomendasi dll)
    // -------------------------
    window.geserKiri = function (id) {
        const el = document.getElementById(id);
        if (!el) return;
        el.scrollBy({
            left: -300,
            behavior: "smooth",
        });
    };

    window.geserKanan = function (id) {
        const el = document.getElementById(id);
        if (!el) return;
        el.scrollBy({
            left: 300,
            behavior: "smooth",
        });
    };

    // -------------------------
    // Popup keranjang & produk favorit
    // -------------------------
    window.togglePopup = function (id) {
        const popup = document.getElementById(id);
        if (!popup) return;

        const isHidden = popup.classList.contains("hidden");

        // Tutup semua popup lainnya dulu (cek eksistensi)
        document.querySelectorAll(".popup-panel").forEach((p) => {
            if (p && p.classList) p.classList.add("hidden");
        });

        // Toggle popup ini
        if (isHidden) {
            popup.classList.remove("hidden");
        } else {
            popup.classList.add("hidden");
        }
    };

    // Tutup popup saat klik di luar (safe checks)
    document.addEventListener("click", function (event) {
        const popups = document.querySelectorAll(".popup-panel");
        if (!popups || popups.length === 0) return;

        popups.forEach((popup) => {
            if (!popup) return;

            const triggerBtn = popup.previousElementSibling; // tombol trigger mungkin null
            const clickedInsidePopup = popup.contains ? popup.contains(event.target) : false;
            const clickedInsideTrigger = triggerBtn && triggerBtn.contains ? triggerBtn.contains(event.target) : false;

            if (!clickedInsidePopup && !clickedInsideTrigger) {
                if (popup.classList) popup.classList.add("hidden");
            }
        });
    });
});
