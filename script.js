// Tombol naik ke atas
const backToTop = document.getElementById("backToTop");
window.addEventListener("scroll", () => {
  backToTop.style.display = window.scrollY > 300 ? "block" : "none";
});
backToTop.addEventListener("click", () => {
  window.scrollTo({ top: 0, behavior: "smooth" });
});

// Fitur Keranjang
const plusButtons = document.querySelectorAll(".plus");
const minusButtons = document.querySelectorAll(".minus");
const totalHargaElem = document.getElementById("total-harga");
const cartCountElem = document.getElementById("cart-count");

let totalHarga = 0;
let totalItem = 0;

plusButtons.forEach(btn => {
  btn.addEventListener("click", () => {
    const card = btn.closest(".card");
    const jumlahElem = card.querySelector(".jumlah");
    const price = parseInt(card.dataset.price);

    let jumlah = parseInt(jumlahElem.textContent);
    jumlah++;
    jumlahElem.textContent = jumlah;

    totalItem++;
    totalHarga += price;

    updateCart();
  });
});

minusButtons.forEach(btn => {
  btn.addEventListener("click", () => {
    const card = btn.closest(".card");
    const jumlahElem = card.querySelector(".jumlah");
    const price = parseInt(card.dataset.price);

    let jumlah = parseInt(jumlahElem.textContent);
    if (jumlah > 0) {
      jumlah--;
      jumlahElem.textContent = jumlah;

      totalItem--;
      totalHarga -= price;

      updateCart();
    }
  });
});

function updateCart() {
  cartCountElem.textContent = totalItem;
  totalHargaElem.textContent = "Rp" + totalHarga.toLocaleString("id-ID");
}

  let currentIndex = 0;
  const imgElement = document.getElementById("aboutImage");

  if (imgElement) {
    setInterval(() => {
      currentIndex = (currentIndex + 1) % aboutImages.length;
      imgElement.style.opacity = 0;
      setTimeout(() => {
        imgElement.src = aboutImages[currentIndex];
        imgElement.style.opacity = 1;
      }, 300);
    }, 3000); // ganti setiap 3 detik
  }

// === SLIDE FOTO TENTANG KAMI ===
document.addEventListener("DOMContentLoaded", function() {
  const aboutImages = [
    "12.jpg",
    "14.jpg",
    "11.jpg"
  ];

  let currentIndex = 0;
  const imgElement = document.getElementById("aboutImage");

  if (imgElement) {
    setInterval(() => {
      currentIndex = (currentIndex + 1) % aboutImages.length;
      imgElement.style.opacity = 0;
      setTimeout(() => {
        imgElement.src = aboutImages[currentIndex];
        imgElement.style.opacity = 1;
      }, 300);
    }, 3000);
  }
});
const scrollContainer = document.querySelector('.produk-scroll');

let isDown = false;
let startX;
let scrollLeft;

scrollContainer.addEventListener('mousedown', (e) => {
  isDown = true;
  scrollContainer.classList.add('active');
  startX = e.pageX - scrollContainer.offsetLeft;
  scrollLeft = scrollContainer.scrollLeft;
});

scrollContainer.addEventListener('mouseleave', () => {
  isDown = false;
  scrollContainer.classList.remove('active');
});

scrollContainer.addEventListener('mouseup', () => {
  isDown = false;
  scrollContainer.classList.remove('active');
});

scrollContainer.addEventListener('mousemove', (e) => {
  if (!isDown) return;
  e.preventDefault();
  const x = e.pageX - scrollContainer.offsetLeft;
  const walk = (x - startX) * 1.2; // semakin besar angka, semakin cepat geser
  scrollContainer.scrollLeft = scrollLeft - walk;
});
//Respon BOT
const kirimBtn = document.getElementById('kirimBtn');
const botBox = document.getElementById('bot-box');
const botResponse = document.getElementById('bot-response');
const namaInput = document.getElementById('nama');
const pesanInput = document.getElementById('pesan');
let rating = 0;


kirimBtn.addEventListener("click", () => {
  const nama = document.getElementById("nama").value.trim();
  const pesan = document.getElementById("pesan").value.trim();


  botBox.style.display = "block";

  // Respon otomatis
  if (rating <= 2) {
    botResponse.textContent =
      '😍 Terima kasih ${nama}, Pesan dan Kritikan Anda Akan Kami Jadikan Evaluasi 😍';
  }

  // Reset form
  document.getElementById("nama").value = "";
  document.getElementById("pesan").value = "";
  stars.forEach((s) => s.classList.remove("active"));
  rating=0;
});
// === Tandai Menu Aktif Berdasarkan Halaman ===
document.addEventListener("DOMContentLoaded", () => {
  const navLinks = document.querySelectorAll(".navbar nav ul li a");
  const currentURL = window.location.href;

  navLinks.forEach(link => {
    const href = link.getAttribute("href");

    // Untuk link halaman (tentang.php, produk.php, dst)
    if (currentURL.includes(href) && href !== "#") {
      link.classList.add("active");
    }

    // Untuk link bagian dalam halaman (#home, #produk, dst)
    if (href.startsWith("#")) {
      const section = document.querySelector(href);
      if (section && section.id === "home") {
        link.classList.add("active"); // default aktif Beranda
      }
      // Tambahkan highlight saat scroll (optional)
      window.addEventListener("scroll", () => {
        let top = window.scrollY;
        if (
          section.offsetTop - 100 <= top &&
          section.offsetTop + section.offsetHeight > top
        ) {
          navLinks.forEach(l => l.classList.remove("active"));
          link.classList.add("active");
        }
      });
    }
  });
});
