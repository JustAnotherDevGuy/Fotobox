# Photobooth

An easy-to-use Photobooth built with HTML, CSS, PHP, and JavaScript.

## Features
- **Simple Setup**: Quickly get the Photobooth up and running with minimal configuration.
- **Customizable**: Modify the look and feel of the photobox using HTML and CSS.
- **Photo Capture**: Capture photos directly from the browser using JavaScript.
- **Server-Side Processing**: Handle photo saving and processing with PHP.
- **Keyboard-Only**: Designed to work on devices with a physical keyboard.

## Requirements
- A web server with PHP support (e.g., Apache, Nginx)
- A modern web browser (with JavaScript enabled)
- A device with a physical keyboard

## Installation
1. **Clone the Repository**: Clone the repository to your web server's root directory:
   ```bash
   git clone https://github.com/JustAnotherDevGuy/Fotobox.git
   ```
2. Ensure that the repository’s contents are directly in the web server’s root directory, not in a subdirectory.
3. **Configure PHP**: Make sure your web server has PHP enabled and is correctly configured to serve the project.
4. **Update URLs**: Replace all instances of `YourUrl` with your actual server URL in the following files:
   - `script.js`
   - `upload.php`
5. **Access the Project**: Open the project in your browser:
   ```bash
   https://YourUrl/
   ```

## Usage
- **Interact**: Use a keyboard to interact with the Photobooth.
- **Capture Photos**: Follow the on-screen instructions to capture and save photos.
- **Customize**: Edit the HTML and CSS files to customize the interface.

## Customization
To personalize the Photobooth:
- **HTML**: Modify the structure in `index.html`.
- **CSS**: Adjust the styling in `styles.css`.
- **PHP**: Customize backend processing in `upload.php`.
- **JavaScript**: Enhance functionality in `script.js`.

**Note**: Be sure to replace all occurrences of `YourUrl` with the correct URL for your server in `script.js`, `upload.php`, and any other files where it appears.

## Troubleshooting
- **PHP Configuration**: Ensure that your web server supports PHP and is correctly configured.
- **File Permissions**: Check that all file permissions are set correctly, especially for directories where images are saved.

## Contribution
Feel free to fork this repository, make changes, and submit pull requests. Contributions are welcome!

## License
This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for more details.
