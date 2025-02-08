const quotes = [
    '"A library is not a luxury but one of the necessities of life." – Henry Ward Beecher',
    '"Libraries store the energy that fuels the imagination." – Sidney Sheldon',
    '"A reader lives a thousand lives before he dies." – George R.R. Martin',
    '"I have always imagined that Paradise will be a kind of library." – Jorge Luis Borges',
    '"The only thing that you absolutely have to know is the location of the library." – Albert Einstein' ,
    '"Go out and get some Life man." - Ensrinum'
];

let quoteIndex = 0;

function changeQuote() {
    const quoteElement = document.getElementById("quote");
    quoteElement.style.opacity = 0;
    setTimeout(() => {
        quoteElement.innerText = quotes[quoteIndex];
        quoteElement.style.opacity = 1;
        quoteIndex = (quoteIndex + 1) % quotes.length;
    }, 500);
}

// Change quote every 5 seconds
setInterval(changeQuote, 5000);

// Initial quote change
changeQuote();

function scanQRCode() {
    const cameraInput = document.getElementById('cameraInput');
    const cameraFeed = document.getElementById('cameraFeed');
    
    if ('mediaDevices' in navigator && 'getUserMedia' in navigator.mediaDevices) {
        // Open camera
        navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } })
            .then(function(stream) {
                // Display the camera feed in the video element
                cameraFeed.srcObject = stream;
                cameraFeed.style.display = 'block'; // Show the video element
                console.log('Camera opened');
                
                // Remember to stop the stream when done
                // stream.getTracks().forEach(track => track.stop());
            })
            .catch(function(error) {
                console.error("Camera error: ", error);
                // If camera fails, open file input
                cameraInput.click();
            });
    } else {
        // If camera API is not available, open file input
        cameraInput.click();
    }
}