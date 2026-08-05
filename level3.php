<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BahasaKu - Level 3</title>
    <style>
        /* === CSS / STYLING === */
        :root {
            --primary: #0D9488; /* Warna Teal untuk level 3 */
            --success: #7CBA41;
            --success-light: #E7F5DD;
            --danger: #EB5757;
            --danger-light: #FDECEC;
            --warning: #F2C94C;
            --bg-color: #e0e5ec;
            --text-dark: #333333;
            --text-gray: #828282;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: var(--bg-color);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        #app {
            background-color: white;
            width: 100%;
            display: flex;
            flex-direction: column;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        }

        .screen {
            display: none;
            flex-direction: column;
            height: 100%;
            animation: fadeIn 0.4s ease;
        }
        .screen.active {
            display: flex;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        h1, h2, h3 { color: var(--text-dark); text-align: center; }
        p { color: var(--text-gray); text-align: center; margin-bottom: 20px; }

        .progress-container {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 30px;
            width: 100%;
        }
        .back-btn { background: none; border: none; font-size: 24px; cursor: pointer; color: var(--text-dark); }
        .progress-bar { flex: 1; display: flex; gap: 8px; }
        .progress-step { height: 8px; flex: 1; background-color: #E0E0E0; border-radius: 4px; transition: 0.3s; }
        .progress-step.active { background-color: var(--primary); }
        
        .timer-badge {
            background-color: var(--warning); color: white; padding: 8px 15px;
            border-radius: 20px; font-weight: bold; font-size: 14px;
            display: flex; align-items: center; gap: 5px;
            box-shadow: 0 4px 10px rgba(242, 201, 76, 0.4);
        }
        .timer-badge.danger { background-color: var(--danger); box-shadow: 0 4px 10px rgba(235, 87, 87, 0.4); animation: pulse 1s infinite; }

        @keyframes pulse { 0% { transform: scale(1); } 50% { transform: scale(1.05); } 100% { transform: scale(1); } }

        .btn-primary {
            background-color: var(--primary); color: white; border: none;
            padding: 16px; border-radius: 30px; font-size: 18px; font-weight: bold;
            cursor: pointer; width: 100%; margin-top: auto; transition: 0.2s;
        }
        .btn-primary:hover { background-color: #0b7a70; }
        .btn-primary:active { transform: scale(0.98); }
        .btn-primary:disabled { background-color: #cccccc; cursor: not-allowed; }

        .question-text { font-size: 24px; font-weight: bold; text-align: center; margin: 40px 0; color: var(--primary); padding: 20px; background: #E0F2F1; border-radius: 15px;}
        .grid-options { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; width: 100%; }
        .list-options { display: flex; flex-direction: column; gap: 15px; width: 100%; }
        .btn-option { background-color: var(--primary); color: white; border: none; padding: 16px; border-radius: 12px; font-size: 16px; font-weight: bold; cursor: pointer; transition: 0.2s; text-align: center; }
        .btn-option:hover { background-color: #0b7a70; }

        #feedback-popup { position: absolute; bottom: -100%; left: 0; width: 100%; padding: 30px; border-radius: 25px 25px 0 0; transition: bottom 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); display: flex; flex-direction: column; gap: 15px; box-shadow: 0 -5px 20px rgba(0,0,0,0.1); z-index: 10; }
        #feedback-popup.show { bottom: 0; }
        .feedback-success { background-color: var(--success-light); color: var(--success); }
        .feedback-success .btn-next { background-color: var(--success); }
        .feedback-error { background-color: var(--danger-light); color: var(--danger); }
        .feedback-error .btn-next { background-color: var(--danger); }
        .btn-next { color: white; border: none; padding: 16px; border-radius: 30px; font-size: 18px; font-weight: bold; cursor: pointer; width: 100%; }

        .icon-large { font-size: 100px; text-align: center; margin: 30px 0; }
        .medal-icon { font-size: 120px; text-align: center; margin: 50px 0 20px; }

        @media (max-width: 767px) { #app { height: 100vh; max-width: 100vw; border-radius: 0; } .screen { padding: 25px 20px; } .btn-primary { margin-top: 30px; } }
        @media (min-width: 768px) {
            #app { max-width: 100vw; height: 100vh; border-radius: 0; }
            .screen { padding: 40px 20px; align-items: center; }
            .progress-container, h2, p, .grid-options, .list-options, .question-text { max-width: 800px; }
            h2 { font-size: 32px; margin-bottom: 20px;} 
            .question-text { font-size: 32px; margin: 50px 0; }
            .grid-options { gap: 20px; } .btn-option { padding: 20px; font-size: 18px; }
            .btn-primary { max-width: 400px; margin-top: 40px; }
            #feedback-popup { padding: 40px 0; align-items: center; }
            #feedback-title, #feedback-answer, .btn-next, #feedback-popup p { width: 100%; max-width: 800px; padding: 0 20px; }
            .btn-next { max-width: 400px; padding: 20px; margin-top: 10px; }
        }
    </style>
</head>
<body>

    <div id="app">
        <!-- SCREEN: HOME LEVEL 3 -->
        <div id="screen-home" class="screen active" style="background-color: var(--primary); color: white; justify-content: center; align-items: center;">
            <div style="background: white; border-radius: 25px; width: 120px; height: 120px; display: flex; justify-content: center; align-items: center; margin-bottom: 25px; box-shadow: 0 10px 20px rgba(0,0,0,0.1);">
                <h1 style="color: var(--text-dark); font-size: 60px; margin:0;">3</h1>
            </div>
            <h1 style="color: white; font-size: 32px; margin-bottom: 10px;">BahasaKu - Level 3</h1>
            <p style="color: #E0F2F1; font-size: 18px;">Listening Comprehension</p>
            <p style="color: #FFD54F; font-size: 14px; font-weight: bold; background: rgba(0,0,0,0.2); padding: 10px; border-radius: 10px;">⚠️ Peringatan: Audio hanya diputar SATU KALI.</p>
            <button class="btn-primary" style="background: white; color: var(--primary); margin-top: 60px;" onclick="goToScreen('screen-listen')">Mulai Level 3 ➔</button>
        </div>

        <!-- SCREEN: LISTENING AUDIO (Dimainkan hanya 1x) -->
        <div id="screen-listen" class="screen" style="justify-content: center; align-items: center;">
            <h2 style="margin-top: 40px;">Listen to the Conversation</h2>
            <p>Dengarkan percakapan ini baik-baik. Anda tidak bisa mengulangnya.</p>
            <div class="icon-large">🎧</div>
            
            <button id="btn-play-audio" class="btn-primary" style="margin-top: 20px; padding: 25px; font-size: 20px;" onclick="playConversation()">
                ▶️ Putar Audio Sekarang
            </button>
            
            <button id="btn-start-quiz" class="btn-primary" style="display: none; background-color: var(--warning); color: var(--text-dark); margin-top: 20px;" onclick="goToScreen('screen-q1')">
                Mulai Mengerjakan Soal ➔
            </button>
        </div>

        <!-- SCREEN: TEST 1 -->
        <div id="screen-q1" class="screen">
            <div class="progress-container">
                <div class="progress-bar">
                    <div class="progress-step active"></div>
                    <div class="progress-step"></div>
                    <div class="progress-step"></div>
                </div>
                <div class="timer-badge">⏱️ <span class="time-text">20</span>s</div>
            </div>
            <h2>Question 1</h2>
            <div class="question-text">What time did Alex and Maria originally plan to meet?</div> 
            <div class="grid-options">
                <button class="btn-option" onclick="checkAnswer('q1', '6 PM')">6 PM</button>
                <button class="btn-option" onclick="checkAnswer('q1', '7 PM')">7 PM</button>
                <button class="btn-option" onclick="checkAnswer('q1', '8 PM')">8 PM</button>
                <button class="btn-option" onclick="checkAnswer('q1', '9 PM')">9 PM</button>
            </div>
        </div>

        <!-- SCREEN: TEST 2 -->
        <div id="screen-q2" class="screen">
            <div class="progress-container">
                <div class="progress-bar">
                    <div class="progress-step active"></div><div class="progress-step active"></div>
                    <div class="progress-step"></div>
                </div>
                <div class="timer-badge">⏱️ <span class="time-text">20</span>s</div>
            </div>
            <h2>Question 2</h2>
            <div class="question-text">Why did Maria want to change the meeting time?</div> 
            <div class="list-options">
                <button class="btn-option" onclick="checkAnswer('q2', 'She was sick')">A. She was sick</button>
                <button class="btn-option" onclick="checkAnswer('q2', 'She had to work late')">B. She had to work late</button>
                <button class="btn-option" onclick="checkAnswer('q2', 'She had to finish her homework')">C. She had to finish her homework</button>
                <button class="btn-option" onclick="checkAnswer('q2', 'She forgot the plan')">D. She forgot the plan</button>
            </div>
        </div>

        <!-- SCREEN: TEST 3 -->
        <div id="screen-q3" class="screen">
            <div class="progress-container">
                <div class="progress-bar">
                    <div class="progress-step active"></div><div class="progress-step active"></div><div class="progress-step active"></div>
                </div>
                <div class="timer-badge">⏱️ <span class="time-text">20</span>s</div>
            </div>
            <h2>Question 3</h2>
            <div class="question-text">What kind of movie are they going to watch?</div> 
            <div class="grid-options">
                <button class="btn-option" onclick="checkAnswer('q3', 'Comedy')">Comedy</button>
                <button class="btn-option" onclick="checkAnswer('q3', 'Horror')">Horror</button>
                <button class="btn-option" onclick="checkAnswer('q3', 'Romance')">Romance</button>
                <button class="btn-option" onclick="checkAnswer('q3', 'Action')">Action</button>
            </div>
        </div>

        <!-- SCREEN: CONGRATULATIONS LEVEL 3 -->
        <div id="screen-result" class="screen" style="background-color: #042F2E; justify-content: center;">
            <div class="medal-icon">🎓</div>
            <h1 style="color: white; font-size: 36px;">Level 3 Cleared!</h1>
            <p style="color: #E0F2F1; font-size: 18px; margin-top: 10px;">Pendengaranmu sangat tajam. Kerja bagus!</p>
            <button class="btn-primary" style="background: white; color: #042F2E; margin-top: 50px;" onclick="location.href='index.html'">Selesaikan Kursus</button>
        </div>

        <!-- FEEDBACK POPUP -->
        <div id="feedback-popup">
            <h3 id="feedback-title" style="text-align: left; font-size: 24px; margin-bottom: 5px;">Amazing!</h3>
            <p style="text-align: left; margin-bottom: 15px; font-size: 16px;">Answer : <strong id="feedback-answer"></strong></p>
            <button class="btn-next" id="btn-next-screen">Next Question</button>
        </div>

    </div>

    <!-- === JAVASCRIPT / LOGIKA LEVEL 3 === -->
    <script>
        let nextScreenId = '';
        let timerInterval;
        const TIME_LIMIT = 20; 

        // Kunci Jawaban
        const answers = {
            q1: "7 PM",
            q2: "She had to finish her homework",
            q3: "Action"
        };

        // Logika Audio Listening (Satu Kali Play)
        function playConversation() {
            const playBtn = document.getElementById('btn-play-audio');
            const nextBtn = document.getElementById('btn-start-quiz');
            
            // Disable tombol agar tidak bisa diklik lagi
            playBtn.disabled = true;
            playBtn.innerText = "🔊 Sedang memutar percakapan...";

            // Teks percakapan naratif yang akan dibaca oleh Text-to-Speech
            const conversationText = "Listen to the conversation carefully. Alex says: Hey, Maria. Are we still going to the cinema at 7 PM? Maria replies: Hi, Alex. Actually, I have to finish my homework first. Can we meet at 8 PM instead? Alex says: Sure, 8 PM works for me. What movie are we watching again? Maria replies: We are going to watch the new action movie, The Last Hero. Alex says: Awesome. I will buy the tickets online. See you at 8!";

            if ('speechSynthesis' in window) {
                window.speechSynthesis.cancel();
                let utterance = new SpeechSynthesisUtterance(conversationText);
                utterance.lang = 'en-US';
                utterance.rate = 0.9; // Kecepatan sedikit diperlambat agar mudah didengar
                
                // Event ketika audio selesai diputar
                utterance.onend = function() {
                    playBtn.innerText = "✅ Audio Selesai Diputar";
                    nextBtn.style.display = "block"; // Munculkan tombol mulai kuis
                };
                
                window.speechSynthesis.speak(utterance);
            } else {
                alert("Browser kamu tidak mendukung fitur suara. Soal akan langsung dimulai.");
                nextBtn.style.display = "block";
            }
        }

        function startTimer(questionId) {
            clearInterval(timerInterval);
            let timeLeft = TIME_LIMIT;
            const timeDisplays = document.querySelectorAll('.time-text');
            const timerBadges = document.querySelectorAll('.timer-badge');
            
            timeDisplays.forEach(el => el.innerText = timeLeft);
            timerBadges.forEach(badge => badge.classList.remove('danger'));

            timerInterval = setInterval(() => {
                timeLeft--;
                timeDisplays.forEach(el => el.innerText = timeLeft);
                if(timeLeft <= 5) timerBadges.forEach(badge => badge.classList.add('danger'));
                if (timeLeft <= 0) {
                    clearInterval(timerInterval);
                    handleTimeout(questionId);
                }
            }, 1000);
        }

        function handleTimeout(questionId) {
            let correctAnswerText = answers[questionId];
            
            const order = ['q1', 'q2', 'q3']; 
            const currentIndex = order.indexOf(questionId);
            nextScreenId = (currentIndex === order.length - 1) ? 'screen-result' : `screen-${order[currentIndex + 1]}`;
            
            showFeedback(false, correctAnswerText, "Waktu Habis! ⏰");
        }

        function goToScreen(screenId) {
            document.querySelectorAll('.screen').forEach(s => s.classList.remove('active'));
            document.getElementById(screenId).classList.add('active');
            document.getElementById('feedback-popup').classList.remove('show');
            
            clearInterval(timerInterval);
            // Hanya mulai timer jika yang dibuka adalah layar pertanyaan (bukan home/listen)
            if (screenId.startsWith('screen-q')) {
                startTimer(screenId.replace('screen-', ''));
            }
        }

        function checkAnswer(questionId, selectedAnswer) {
            clearInterval(timerInterval);
            let isCorrect = false;
            let correctAnswerText = answers[questionId];

            const order = ['q1', 'q2', 'q3']; 
            const currentIndex = order.indexOf(questionId);
            nextScreenId = (currentIndex === order.length - 1) ? 'screen-result' : `screen-${order[currentIndex + 1]}`;

            if (selectedAnswer === answers[questionId]) {
                isCorrect = true;
            }

            showFeedback(isCorrect, correctAnswerText);
        }

        function showFeedback(isCorrect, correctAnswer, customTitle = null) {
            const popup = document.getElementById('feedback-popup');
            const title = document.getElementById('feedback-title');
            const answerText = document.getElementById('feedback-answer');
            const nextBtn = document.getElementById('btn-next-screen');

            popup.classList.remove('feedback-success', 'feedback-error');

            if (isCorrect) {
                popup.classList.add('feedback-success');
                title.innerText = customTitle ? customTitle : "Amazing! 🌟";
                playBeep(800, 150); 
            } else {
                popup.classList.add('feedback-error');
                title.innerText = customTitle ? customTitle : "Ups.. that's wrong 😕";
                playBeep(300, 300);
            }

            answerText.innerText = correctAnswer;
            nextBtn.onclick = () => goToScreen(nextScreenId);
            popup.classList.add('show');
        }

        function playBeep(frequency, duration) {
            try {
                const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                const oscillator = audioCtx.createOscillator();
                const gainNode = audioCtx.createGain();
                oscillator.type = 'sine';
                oscillator.frequency.value = frequency;
                oscillator.connect(gainNode);
                gainNode.connect(audioCtx.destination);
                oscillator.start();
                setTimeout(() => oscillator.stop(), duration);
            } catch (e) { console.log("Audio not supported"); }
        }
    </script>
</body>
</html>