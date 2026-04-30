document.addEventListener("DOMContentLoaded", function () {
    const input = document.getElementById("searchInput");
    const form = document.getElementById("searchForm");

    if (form && input) {
        form.addEventListener("submit", function (e) {
            e.preventDefault();

            const query = input.value.trim();

            if (query === "") {
                alert("Masukkan kata kunci pencarian");
                return;
            }

            window.location.href = "/resep-app/tfidf/search.php?q=" + encodeURIComponent(query);
        });
    }
});

function startVoice() {
    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;

    if (!SpeechRecognition) {
        alert("Gunakan Google Chrome untuk fitur voice search");
        return;
    }

    const recognition = new SpeechRecognition();

    recognition.lang = "id-ID";
    recognition.interimResults = false;
    recognition.maxAlternatives = 1;

    recognition.onstart = function () {
        const input = document.getElementById("searchInput");

        if (input) {
            input.placeholder = "Mendengarkan suara...";
        }
    };

    recognition.onresult = function (event) {
        const hasil = event.results[0][0].transcript;
        const input = document.getElementById("searchInput");

        if (input) {
            input.value = hasil;
        }

        window.location.href = "/resep-app/tfidf/search.php?q=" + encodeURIComponent(hasil);
    };

    recognition.onerror = function (event) {
        alert("Voice error: " + event.error);

        const input = document.getElementById("searchInput");

        if (input) {
            input.placeholder = "Cari resep...";
        }
    };

    recognition.onend = function () {
        const input = document.getElementById("searchInput");

        if (input) {
            input.placeholder = "Cari resep...";
        }
    };

    recognition.start();
}
