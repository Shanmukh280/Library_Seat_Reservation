function toggleUserDetails() {
    const profileCard = document.getElementById('profileCard');
    const quoteContainer = document.querySelector('.quote-container');
    const featureCards = document.querySelector('.cards');
    const profilePic = profileCard.querySelector(".profile-pic");

    profileCard.classList.toggle('active');
    
    if (profileCard.classList.contains('active')) {
        featureCards.style.transform = "translateX(180px)";
        quoteContainer.style.transform = "translateX(180px)";
        profileCard.style.position = "fixed";
        profileCard.style.top = "0";
        profileCard.style.left = "0";
        profileCard.style.width = "300px";
        profileCard.style.height = "100vh";
        
        profilePic.style.width = "100%";
        profilePic.style.height = "50%";
        profilePic.style.borderRadius = "50%";
    } else {
        featureCards.style.transform = "translateX(0)";
        quoteContainer.style.transform = "translateX(0)";
        profileCard.style.position = "relative";
        profileCard.style.width = "auto";
        profileCard.style.height = "auto";
        
        profilePic.style.width = "70px";
        profilePic.style.height = "70px";
        profilePic.style.borderRadius = "50%";
    }
}

// Edit profile details and upload picture
function editProfile(event) {
    event.stopPropagation(); // Prevent the toggle of user details
    document.querySelector('.user-details').contentEditable = true;
    document.getElementById('fileUpload').style.display = 'block';
}

// Upload profile picture
function uploadProfilePic(event) {
    const file = event.target.files[0];
    const reader = new FileReader();
    reader.onload = function(e) {
        document.getElementById('profilePic').src = e.target.result;
    };
    reader.readAsDataURL(file);
}

function navigateTo(page) {
    window.location.href = page;
}

// Rotating Library Quotes
const quotes = [
    '"A library is not a luxury but one of the necessities of life." – Henry Ward Beecher',
    '"Libraries store the energy that fuels the imagination." – Sidney Sheldon',
    '"A reader lives a thousand lives before he dies." – George R.R. Martin',
    '"I have always imagined that Paradise will be a kind of library." – Jorge Luis Borges',
    '"The only thing that you absolutely have to know is the location of the library." – Albert Einstein'
];

let quoteIndex = 0;
function changeQuote() {
    document.getElementById("quote").innerText = quotes[quoteIndex];
    quoteIndex = (quoteIndex + 1) % quotes.length;
}
setInterval(changeQuote, 5000);