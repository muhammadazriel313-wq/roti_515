// >>>>> GANTI NOMOR WHATSAPP ADMIN DI SINI <<<<<
let adminNumber = "62085755354846";  // Contoh: 628577xxxxxxx

document.getElementById("kirimBtn").addEventListener("click", function () {
    let nama = document.getElementById("nama").value.trim();
    let pesan = document.getElementById("pesan").value.trim();
    let botBox = document.getElementById("bot-response");

    // Validasi input
    if (nama === "" || pesan === "") {
        botBox.textContent = "⚠ Mohon isi semua kolom.";
        botBox.style.color = "red";
        return;
    }

    // Format pesan WhatsApp
let text = `Kritik & Saran Roti 515\n\nNama: ${nama}\nPesan: ${pesan}`;
let encoded = encodeURIComponent(text);

// Kirim ke WhatsApp
let url = `https://wa.me/${adminNumber}?text=${encoded}`;
window.open(url, "_blank");


    // Respon otomatis tampil di layar
    botBox.textContent = "Terima kasih! Masukan kamu sangat berarti ✨";
    botBox.style.color = "green";

    // Reset form
    document.getElementById("nama").value = "";
    document.getElementById("pesan").value = "";
});
// Image Slider dengan Efek Geser
document.addEventListener('DOMContentLoaded', function() {
    const wrapper = document.querySelector('.slider-wrapper');
    const slides = document.querySelectorAll('.image-slide');
    const dots = document.querySelectorAll('.dot');
    const prevBtn = document.querySelector('.prev-btn');
    const nextBtn = document.querySelector('.next-btn');

    const slideCount = slides.length;

    // Clone first & last slide
    const firstClone = slides[0].cloneNode(true);
    const lastClone = slides[slideCount - 1].cloneNode(true);
    wrapper.appendChild(firstClone);
    wrapper.insertBefore(lastClone, slides[0]);

    const allSlides = document.querySelectorAll('.image-slide');
    let index = 1; // mulai dari slide asli pertama
    const slideWidth = 100; // dalam %

    wrapper.style.transform = `translateX(-${index * slideWidth}%)`;
    wrapper.style.transition = 'transform 0.8s ease-in-out';

    let autoSlideTimer;

    function goToSlide(newIndex) {
        wrapper.style.transition = 'transform 0.8s ease-in-out';
        index = newIndex;
        wrapper.style.transform = `translateX(-${index * slideWidth}%)`;
        updateDots();
    }

    function updateDots() {
        let activeIndex = index - 1;
        if (activeIndex < 0) activeIndex = slideCount - 1;
        if (activeIndex >= slideCount) activeIndex = 0;
        dots.forEach((dot, i) => dot.classList.toggle('active', i === activeIndex));
    }

    wrapper.addEventListener('transitionend', () => {
        if (index === 0) {
            // jika ke clone terakhir, lompat ke slide terakhir asli
            wrapper.style.transition = 'none';
            index = slideCount;
            wrapper.style.transform = `translateX(-${index * slideWidth}%)`;
        }
        if (index === slideCount + 1) {
            // jika ke clone pertama, lompat ke slide pertama asli
            wrapper.style.transition = 'none';
            index = 1;
            wrapper.style.transform = `translateX(-${index * slideWidth}%)`;
        }
    });

    // Tombol next/prev
    nextBtn.addEventListener('click', () => {
        goToSlide(index + 1);
        resetAutoSlide();
    });

    prevBtn.addEventListener('click', () => {
        goToSlide(index - 1);
        resetAutoSlide();
    });

    // Klik dots
    dots.forEach((dot, i) => {
        dot.addEventListener('click', () => {
            goToSlide(i + 1); // +1 karena slide pertama asli mulai di index 1
            resetAutoSlide();
        });
    });

    function startAutoSlide() {
        autoSlideTimer = setInterval(() => {
            goToSlide(index + 1);
        }, 4000);
    }

    function resetAutoSlide() {
        clearInterval(autoSlideTimer);
        startAutoSlide();
    }

    startAutoSlide();

    // Pause saat hover
    document.querySelector('.image-slider').addEventListener('mouseenter', () => clearInterval(autoSlideTimer));
    document.querySelector('.image-slider').addEventListener('mouseleave', startAutoSlide);
});
let indexMobile = 0;
const slidesMobile = document.querySelectorAll(".photo-slide");

function nextMobile() {
  slidesMobile.forEach(s => s.style.display = "none");
  indexMobile++;
  if (indexMobile >= slidesMobile.length) indexMobile = 0;
  slidesMobile[indexMobile].style.display = "block";
}

if (slidesMobile.length > 0) {
  slidesMobile[0].style.display = "block";
  setInterval(nextMobile, 3000);
}
const hamburger = document.getElementById("hamburger");
const menu = document.querySelector("nav ul");

hamburger.addEventListener("click", () => {
  menu.classList.toggle("show");
  hamburger.classList.toggle("active");
});

// Tutup menu saat klik link
menu.querySelectorAll("a").forEach(link => {
  link.addEventListener("click", () => {
    menu.classList.remove("show");
    hamburger.classList.remove("active");
  });
});
