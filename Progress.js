/* ==================================================================
   BahasaKu - Shared Progress & Achievement System
   Menyimpan skor, mencatat kesalahan, dan membuka lencana
   menggunakan localStorage. Di-include oleh index.html, level2.html,
   level3.html, dan level4.html sehingga datanya konsisten.
   ================================================================== */

const BK_STORAGE_KEY = "bahasaku_progress";

const BK_BADGES = [
  {
    id: "first_step",
    name: "First Step",
    icon: "🥇",
    desc: "Selesaikan Level 1",
  },
  {
    id: "flawless",
    name: "Flawless",
    icon: "💎",
    desc: "Selesaikan satu level tanpa kesalahan sama sekali",
  },
  {
    id: "sharp_ear",
    name: "Sharp Ear",
    icon: "🎧",
    desc: "Nilai sempurna di Level 3 (Listening)",
  },
  {
    id: "movie_buff",
    name: "Movie Buff",
    icon: "🍿",
    desc: "Selesaikan Level 4 (Video)",
  },
  {
    id: "master_all",
    name: "Master",
    icon: "👑",
    desc: "Selesaikan Semua Level (1, 2, 3, dan 4)",
  },
];

function bkGetData() {
  try {
    const raw = localStorage.getItem(BK_STORAGE_KEY);
    if (!raw)
      return {
        level1: null,
        level2: null,
        level3: null,
        level4: null,
        badges: [],
      };
    const parsed = JSON.parse(raw);
    return {
      level1: parsed.level1 || null,
      level2: parsed.level2 || null,
      level3: parsed.level3 || null,
      level4: parsed.level4 || null,
      badges: parsed.badges || [],
    };
  } catch (e) {
    console.warn("BahasaKu: gagal membaca localStorage", e);
    return {
      level1: null,
      level2: null,
      level3: null,
      level4: null,
      badges: [],
    };
  }
}

function bkSaveData(data) {
  try {
    localStorage.setItem(BK_STORAGE_KEY, JSON.stringify(data));
  } catch (e) {
    console.warn("BahasaKu: gagal menyimpan localStorage", e);
  }
}

/**
 * Catat satu jawaban. Dipanggil dari checkAnswer() / handleTimeout()
 * di setiap level. Jika jawaban salah, ID soal disimpan ke daftar
 * "mistakes" agar bisa diulang lewat Dashboard. Jika sekarang dijawab
 * benar (misalnya saat mode retry), ID tsb dihapus dari daftar salah.
 */
function bkRecordAnswer(level, questionId, isCorrect) {
  const data = bkGetData();
  if (!data[level])
    data[level] = { score: null, mistakes: [], completed: false };
  if (!data[level].mistakes) data[level].mistakes = [];

  const idx = data[level].mistakes.indexOf(questionId);
  if (isCorrect) {
    if (idx > -1) data[level].mistakes.splice(idx, 1);
  } else {
    if (idx === -1) data[level].mistakes.push(questionId);
  }
  bkSaveData(data);
}

/**
 * Catat penyelesaian sebuah level (skor akhir + rekor terbaik),
 * lalu evaluasi ulang lencana yang berhak dibuka.
 */
function bkRecordLevelComplete(level, correct, total) {
  const data = bkGetData();
  if (!data[level])
    data[level] = { score: null, mistakes: [], completed: false };

  const prevBest = data[level].score ? data[level].score.best : 0;
  data[level].score = {
    correct,
    total,
    best: Math.max(correct, prevBest),
    date: new Date().toISOString(),
  };
  data[level].completed = true;

  bkCheckBadges(data);
  bkSaveData(data);
}

function bkCheckBadges(data) {
  const unlocked = new Set(data.badges || []);

  // Badge Level 1
  if (data.level1 && data.level1.completed) unlocked.add("first_step");

  // Badge Flawless (Cek semua level dari 1 sampai 4)
  ["level1", "level2", "level3", "level4"].forEach((lv) => {
    const d = data[lv];
    if (d && d.completed && d.score && d.score.correct === d.score.total) {
      unlocked.add("flawless");
    }
  });

  // Badge Level 3 (Sempurna di Listening)
  if (
    data.level3 &&
    data.level3.completed &&
    data.level3.score &&
    data.level3.score.correct === data.level3.score.total
  ) {
    unlocked.add("sharp_ear");
  }

  // Badge Level 4 (Tamat Video)
  if (data.level4 && data.level4.completed) {
    unlocked.add("movie_buff");
  }

  // Badge Master (Tamat semua level)
  if (
    data.level1 &&
    data.level1.completed &&
    data.level2 &&
    data.level2.completed &&
    data.level3 &&
    data.level3.completed &&
    data.level4 &&
    data.level4.completed
  ) {
    unlocked.add("master_all");
  }

  data.badges = Array.from(unlocked);
  return data;
}

function bkGetMistakes(level) {
  const data = bkGetData();
  return data[level] && data[level].mistakes ? data[level].mistakes : [];
}

function bkResetProgress() {
  if (
    confirm(
      "Hapus semua skor, kesalahan, dan lencana? Tindakan ini tidak bisa dibatalkan.",
    )
  ) {
    localStorage.removeItem(BK_STORAGE_KEY);
    bkRenderDashboard();
  }
}

/* ==================================================================
   RENDER DASHBOARD
   Dipanggil setiap kali layar #screen-dashboard dibuka (lihat
   goToScreen() di masing-masing level). Hanya berefek jika elemen
   dashboard ada di halaman (yaitu di index.html).
   ================================================================== */
function bkRenderDashboard() {
  const data = bkGetData();
  const levels = [
    {
      key: "level1",
      label: "Level 1 — Basic Vocabulary",
      color: "#2D9CDB",
      file: "index.html",
    },
    {
      key: "level2",
      label: "Level 2 — Grammar",
      color: "#9b51e0",
      file: "level2.html",
    },
    {
      key: "level3",
      label: "Level 3 — Listening",
      color: "#0D9488",
      file: "level3.html",
    },
    {
      key: "level4",
      label: "Level 4 — Video",
      color: "#F2994A",
      file: "level4.html",
    },
  ];

  // --- Score board ---
  const scoreBoard = document.getElementById("dashboard-scoreboard");
  if (scoreBoard) {
    scoreBoard.innerHTML = levels
      .map((lv) => {
        const d = data[lv.key];
        const scoreText =
          d && d.score
            ? `${d.score.correct} / ${d.score.total}`
            : "Belum dimainkan";
        const bestText =
          d && d.score
            ? `Rekor terbaik: ${d.score.best} / ${d.score.total}`
            : "";
        const statusIcon = d && d.completed ? "✅" : "—";
        return `
                <div class="score-card" style="border-left: 6px solid ${lv.color};">
                    <div class="score-card-top">
                        <span class="score-card-title">${lv.label}</span>
                        <span class="score-card-status">${statusIcon}</span>
                    </div>
                    <div class="score-card-value">${scoreText}</div>
                    ${bestText ? `<div class="score-card-best">${bestText}</div>` : ""}
                </div>`;
      })
      .join("");
  }

  // --- Mistake review ---
  const retryContainer = document.getElementById("dashboard-retry");
  if (retryContainer) {
    const withMistakes = levels.filter(
      (lv) =>
        data[lv.key] &&
        data[lv.key].mistakes &&
        data[lv.key].mistakes.length > 0,
    );
    if (withMistakes.length === 0) {
      retryContainer.innerHTML = `<p class="dashboard-empty">Tidak ada soal yang salah saat ini. Kerja bagus! 🎉</p>`;
    } else {
      retryContainer.innerHTML = withMistakes
        .map((lv) => {
          const mistakes = data[lv.key].mistakes;
          return `
                    <div class="retry-row">
                        <div>
                            <strong>${lv.label}</strong>
                            <span class="retry-count">${mistakes.length} soal salah</span>
                        </div>
                        <button class="btn-retry" onclick="window.location.href='${lv.file}?retry=${mistakes.join(",")}'">
                            Ulangi Soal yang Salah ↻
                        </button>
                    </div>`;
        })
        .join("");
    }
  }

  // --- Badge gallery ---
  const badgeGallery = document.getElementById("dashboard-badges");
  if (badgeGallery) {
    badgeGallery.innerHTML = BK_BADGES.map((b) => {
      const earned = data.badges.includes(b.id);
      return `
                <div class="badge-item ${earned ? "earned" : "locked"}" title="${b.desc}">
                    <div class="badge-icon">${b.icon}</div>
                    <div class="badge-name">${b.name}</div>
                    <div class="badge-desc">${b.desc}</div>
                </div>`;
    }).join("");
  }
}
