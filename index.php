<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fotobox</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="container" role="main">
    <div class="accessibility-menu">
        <button id="accessibilityButton" aria-haspopup="true" aria-expanded="false">Barrierefreiheit</button>
        <ul id="accessibilityOptions" aria-label="Barrierefreiheit Optionen">
            <li><button id="highContrastButton">Hoher Kontrast</button></li>
            <li><button id="increaseFontButton">Schriftgröße erhöhen</button></li>
            <li><button id="decreaseFontButton">Schriftgröße verringern</button></li>
            <li><button id="grayscaleButton">Graustufen</button></li>
            <li><button id="underlineLinksButton">Links unterstreichen</button></li>
            <li><button id="readingModeButton">Lesemodus</button></li>
            <li><button id="darkModeButton">Dark Mode</button></li>
        </ul>
    </div>
    <div class="webcam-container" aria-label="Webcam feed">
        <video id="webcam" autoplay></video>
        <canvas id="canvas" style="display:none;"></canvas>
    </div>
    <div class="info-container">
        <div id="countdown" aria-live="polite">Countdown: --</div><br>
        <div id="instructions">
            Willkommen zur Fotobox!<br>
            <p class="start_instructions">Drücke 's', um ein Foto zu machen.</p>
        </div>
    </div>
    <div class="video-container" style="display:none;">
        <video id="explanationVideo" controls>
            <source src="video.mp4" type="video/mp4">
            Ihr Browser unterstützt das Video-Tag nicht.
        </video>
    </div>
</div>
<script src="script.js"></script>
<script src="menu.js"></script>
<script src="dark.js"></script>
</body>
</html>