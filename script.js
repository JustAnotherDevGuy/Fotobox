let countdownElement = document.getElementById('countdown');
let webcamElement = document.getElementById('webcam');
let canvasElement = document.getElementById('canvas');
let explanationVideoElement = document.getElementById('explanationVideo');
let videoContainerElement = document.querySelector('.video-container');
let countdown = 10;
let interval;

navigator.mediaDevices.getUserMedia({
    video: {
        width: 1920,
        height: 1080,
        facingMode: "user"
    }
})
.then(stream => {
    webcamElement.srcObject = stream;
    const track = stream.getVideoTracks()[0];
    const settings = track.getSettings();

    console.log(`Resolution: ${settings.width}x${settings.height}`);
    canvasElement.width = settings.width;
    canvasElement.height = settings.height;
})
.catch(err => {
    alert('Webcam konnte nicht geöffnet werden.');
});

function startCountdown() {
    console.log('Countdown gestartet');
    countdown = 10;
    countdownElement.textContent = `Countdown: ${countdown}`;
    interval = setInterval(() => {
        countdown--;
        if (countdown > 0) {
            countdownElement.textContent = `Countdown: ${countdown}`;
        } else {
            clearInterval(interval);
            takePhoto();
        }
    }, 1000);
}

document.addEventListener('keypress', (event) => {
    if (event.key === 's') {
        startCountdown();
    } else if (event.key === 'v') {
        playExplanationVideo();
    }
});

function takePhoto() {
    countdownElement.textContent = 'Foto wird aufgenommen...';

    let context = canvasElement.getContext('2d');
    context.drawImage(webcamElement, 0, 0, canvasElement.width, canvasElement.height);

    let dataUrl = canvasElement.toDataURL('image/jpeg', 1.0);

    console.log(`Captured Image Resolution: ${canvasElement.width}x${canvasElement.height}`);

    uploadPhoto(dataUrl);
}


function uploadPhoto(dataUrl) {
    fetch('https://YourUrl/upload.php', {
        method: 'POST',
        body: JSON.stringify({ image: dataUrl }),
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.url) {
            generateQRCode(data.url);
        } else {
            alert('Upload failed');
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

function uploadPhoto(dataUrl) {
    fetch('https://YourUrl/upload.php', {
        method: 'POST',
        body: JSON.stringify({ image: dataUrl }),
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.url) {
            generateQRCode(data.url);
        } else {
            alert('Upload failed');
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

function generateQRCode(photoUrl) {
    const downloadPageUrl = `https://YourUrl/uploaded_photos/download.html?photo=${encodeURIComponent(photoUrl)}`;
    const qrCodeUrl = `https://api.qrserver.com/v1/create-qr-code/?data=${encodeURIComponent(downloadPageUrl)}&size=450x450`;

    let qrCodeWindow = window.open('', '_blank');
    qrCodeWindow.document.write(`
    <html>
    <head>
        <link rel="stylesheet" href="style_qr.css">
    </head>
    <body>
        <div class="container">
            <img class="photo" src="${photoUrl}" alt="Geschossenes Foto">
            <h1 class="header">Dein QR-Code</h1>
            <img class="qr" src="${qrCodeUrl}" alt="QR Code">
            <p class="text">Scanne den QR-Code, um dein Foto herunterzuladen.</p>
            <p class="text">Sie werden in 30 Sekunden zur Fotobox zurückgeleitet...</p>
        </div>
        <script>
            console.log('setTimeout gestartet');
            setTimeout(() => {
                console.log('Weiterleitung zur Fotobox');
                window.location.replace('https://YourUrl');
            }, 30000);
        </script>
    </body>
    </html>
    `);

    window.close();
}

function showHelp() {
    alert(
        "Tastenkombinationen:\n" +
        "'s' - Countdown starten und Foto aufnehmen.\n" +
        "'q' - Programm beenden.\n" +
        "'c' - Verzeichnis ändern.\n" +
        "'h' - Hilfetext anzeigen.\n" +
        "'v' - Erklärungsvideo abspielen."
    );
}

function playExplanationVideo() {
    videoContainerElement.style.display = 'block';
    explanationVideoElement.play();
    explanationVideoElement.requestFullscreen();

    explanationVideoElement.onended = () => {
        document.exitFullscreen();
        videoContainerElement.style.display = 'none';
    };
}