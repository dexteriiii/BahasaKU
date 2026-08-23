/* ==================================================================
   BahasaKu - Backsound Music Controller
   Menambahkan musik latar yang otomatis lanjut mainkan saat pindah
   halaman (index.html, level2.html, level3.html, level4.html),
   lengkap dengan tombol mute/unmute mengambang di pojok kiri bawah.
   Di-include di SEMUA halaman, persis seperti progress.js.
   ================================================================== */

(function () {
  // GANTI path ini sesuai lokasi file musikmu (mp3/ogg).
  const BK_BGM_SRC = "./audio/backsound.mp3";
  const BK_BGM_VOLUME = 0.35;
  const MUTE_KEY = "bahasaku_bgm_muted";
  const TIME_KEY = "bahasaku_bgm_time";

  // --- Buat elemen audio ---
  const audio = document.createElement("audio");
  audio.id = "bk-bgm";
  audio.src = BK_BGM_SRC;
  audio.loop = true;
  audio.volume = BK_BGM_VOLUME;
  audio.preload = "auto";
  document.body.appendChild(audio);

  // --- Pulihkan status mute & posisi waktu dari kunjungan/halaman sebelumnya ---
  const wasMuted = localStorage.getItem(MUTE_KEY) === "true";
  audio.muted = wasMuted;

  const savedTime = parseFloat(localStorage.getItem(TIME_KEY));
  if (!isNaN(savedTime)) {
    audio.addEventListener(
      "loadedmetadata",
      () => {
        if (savedTime < audio.duration) {
          audio.currentTime = savedTime;
        }
      },
      { once: true }
    );
  }

  // --- Tombol toggle mengambang ---
  const btn = document.createElement("button");
  btn.id = "bk-bgm-toggle";
  btn.type = "button";
  btn.innerText = wasMuted ? "🔇" : "🎵";
  btn.title = "Musik latar on/off";
  Object.assign(btn.style, {
    position: "fixed",
    bottom: "20px",
    left: "20px",
    width: "46px",
    height: "46px",
    borderRadius: "50%",
    border: "none",
    background: "rgba(0,0,0,0.55)",
    color: "white",
    fontSize: "20px",
    cursor: "pointer",
    zIndex: "9999",
    boxShadow: "0 4px 10px rgba(0,0,0,0.25)",
    display: "flex",
    alignItems: "center",
    justifyContent: "center",
    transition: "0.2s",
  });
  btn.addEventListener("mouseenter", () => (btn.style.transform = "scale(1.08)"));
  btn.addEventListener("mouseleave", () => (btn.style.transform = "scale(1)"));

  btn.addEventListener("click", () => {
    audio.muted = !audio.muted;
    localStorage.setItem(MUTE_KEY, audio.muted ? "true" : "false");
    btn.innerText = audio.muted ? "🔇" : "🎵";
    if (!audio.muted && audio.paused) {
      audio.play().catch(() => {});
    }
  });

  document.body.appendChild(btn);

  // --- Coba mainkan otomatis. Banyak browser modern memblokir autoplay
  //     bersuara sebelum ada interaksi pengguna, jadi kita siapkan
  //     fallback: mulai memutar begitu pengguna klik/tap di mana saja. ---
  function tryPlay() {
    const playPromise = audio.play();
    if (playPromise !== undefined) {
      playPromise.catch(() => {
        const startOnInteraction = () => {
          audio.play().catch(() => {});
          document.removeEventListener("click", startOnInteraction);
          document.removeEventListener("touchstart", startOnInteraction);
        };
        document.addEventListener("click", startOnInteraction, { once: true });
        document.addEventListener("touchstart", startOnInteraction, { once: true });
      });
    }
  }
  tryPlay();

  // --- Simpan posisi waktu musik secara berkala, supaya saat pindah
  //     halaman (index -> level2 -> dst) musiknya terasa menyambung,
  //     bukan mengulang dari awal setiap kali ganti halaman. ---
  setInterval(() => {
    if (!audio.paused) {
      localStorage.setItem(TIME_KEY, audio.currentTime.toString());
    }
  }, 1000);

  window.addEventListener("beforeunload", () => {
    localStorage.setItem(TIME_KEY, audio.currentTime.toString());
  });
})();