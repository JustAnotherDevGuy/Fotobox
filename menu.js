document.getElementById('accessibilityButton').addEventListener('click', function() {
    const menu = document.getElementById('accessibilityOptions');
    const expanded = this.getAttribute('aria-expanded') === 'true' || false;
    this.setAttribute('aria-expanded', !expanded);
    menu.style.display = expanded ? 'none' : 'block';
});

document.getElementById('highContrastButton').addEventListener('click', function() {
    document.body.classList.toggle('high-contrast');
});

document.getElementById('increaseFontButton').addEventListener('click', function() {
    adjustFontSize(1.2);
});

document.getElementById('decreaseFontButton').addEventListener('click', function() {
    adjustFontSize(0.8);
});

function adjustFontSize(factor) {
    const elements = document.querySelectorAll('body, #instructions, .start_instructions, #countdown');
    elements.forEach(element => {
        const currentSize = window.getComputedStyle(element).fontSize;
        const newSize = parseFloat(currentSize) * factor + 'px';
        element.style.fontSize = newSize;
    });
}

document.getElementById('grayscaleButton').addEventListener('click', function() {
    document.body.classList.toggle('grayscale');
});

document.getElementById('underlineLinksButton').addEventListener('click', function() {
    document.body.classList.toggle('underline-links');
});

document.getElementById('readingModeButton').addEventListener('click', function() {
    document.body.classList.toggle('reading-mode');
});

document.getElementById('darkModeButton').addEventListener('click', function() {
    document.body.classList.toggle('dark-mode');
});