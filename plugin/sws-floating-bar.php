<!-- Speak Woo Search Floating Bar -->
<button id="sa-toggle-btn" style="position: fixed; bottom: 80px; right: 20px; background: #ff6f91; color: white; border: none; border-radius: 50%; width: 50px; height: 50px; font-size: 24px; cursor: pointer; box-shadow: 0 4px 12px rgba(0,0,0,0.2); z-index: 9999;">🔍</button>

<div id="sa-float-bar" style="position: fixed; bottom: -200px; left: 0; right: 0; background: #fffaf7; padding: 10px 15px; box-shadow: 0 -2px 10px rgba(0,0,0,0.1); display: flex; flex-direction: column; align-items: center; z-index: 9998; transition: bottom 0.4s ease;">
  <h3 style="margin: 5px 0; font-family: sans-serif; color: #ff6f91; font-size: 16px;">Find Your Baby's Perfect Product</h3>
  <div style="display: flex; justify-content: center; align-items: center; gap: 6px; flex-wrap: wrap; width: 100%;">
    <input type="text" id="sa-global-search" placeholder="Speak or type product name" style="flex: 1 1 auto; padding: 10px; font-size: 14px; border: 1px solid #ddd; border-radius: 5px; min-width: 120px;">
    <button id="sa-global-voice" style="padding: 10px; font-size: 16px; border: none; background: #ff6f91; color: white; border-radius: 5px; cursor: pointer;">🎤</button>
    <button onclick="saGlobalSearchNow()" style="padding: 10px; font-size: 18px; border: none; background: #ffcc70; color: #333; border-radius: 5px; cursor: pointer;">🔍</button>
  </div>
  <div id="sa-prompt" style="display: none; margin-top: 5px; background: #ffcc70; color: #333; padding: 5px 12px; border-radius: 5px; font-size: 13px; animation: sa-blink 1s infinite;">Listening...</div>
</div>

<style>
@keyframes sa-blink {
  0% { opacity: 1; }
  50% { opacity: 0.5; }
  100% { opacity: 1; }
}
@media (max-width: 480px) {
  #sa-float-bar h3 {
    font-size: 14px;
  }
  #sa-float-bar input {
    font-size: 12px;
  }
}
</style>

<script>
  const globalVoiceBtn = document.getElementById('sa-global-voice');
  const globalInput = document.getElementById('sa-global-search');
  const promptDiv = document.getElementById('sa-prompt');
  const toggleBtn = document.getElementById('sa-toggle-btn');
  const floatBar = document.getElementById('sa-float-bar');

  // Toggle behavior
  let isVisible = false;
  toggleBtn.addEventListener('click', () => {
    if (isVisible) {
      floatBar.style.bottom = '-200px';
      toggleBtn.textContent = '🔍';
      isVisible = false;
    } else {
      floatBar.style.bottom = '0';
      toggleBtn.textContent = '❌';
      isVisible = true;
    }
  });

  // Voice functionality
  if ('webkitSpeechRecognition' in window) {
    const recognition = new webkitSpeechRecognition();
    recognition.lang = 'en-IN';
    recognition.continuous = false;

    const audio = new Audio("https://sweetangels.in/wp-content/uploads/2025/06/Please-Say-what-you-.mp3");

    globalVoiceBtn.addEventListener('click', () => {
      promptDiv.textContent = "Listening...";
      promptDiv.style.display = 'block';

      audio.play();

      audio.onended = () => {
        setTimeout(() => {
          recognition.start();
        }, 200);
      };
    });

    recognition.onresult = (event) => {
      const transcript = event.results[0][0].transcript;
      globalInput.value = transcript;
      promptDiv.style.display = 'none';
      saGlobalSearchNow();
    };

    recognition.onend = () => {
      promptDiv.style.display = 'none';
    };
  } else {
    globalVoiceBtn.style.display = 'none';
  }

  function saGlobalSearchNow() {
    const query = globalInput.value.trim();
    if (query !== '') {

      // ✅ Save keyword locally on your server
      const formData = new FormData();
      formData.append("query", query);
      formData.append("source", "Voice/Type");

      fetch("/log-search.php", {
        method: "POST",
        body: formData
      });

      // ✅ Redirect to search results
      window.location.href = '/?s=' + encodeURIComponent(query);
    }
  }

  globalInput.addEventListener("keyup", function(event) {
    if (event.key === "Enter") {
      saGlobalSearchNow();
    }
  });
</script>
