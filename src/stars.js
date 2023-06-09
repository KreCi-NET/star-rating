const failOverID = 1; // Default product ID if not provided from URL
const stars = document.querySelectorAll('.rating-stars .star');
let currentRating = 5; // Default rating if no data for selected ID
let id = 0; // Default product ID if not provided from URL

// Mouse over and click listeners
stars.forEach(star => {
    star.addEventListener('mouseover', () => {
        const ratingValue = star.getAttribute('data-value');
        fillStars(ratingValue);
    });

    star.addEventListener('mouseleave', () => {
        fillStars(currentRating);
    });

    star.addEventListener('click', () => {
        currentRating = star.getAttribute('data-value');
        sendRating(currentRating);
    });
});

// Function to get item ID from the current URL (ex. /?123,description - it will get 123 or failOverID if not found)
function getID() {
    const urlParams = new URLSearchParams(window.location.search);
    const queryString = urlParams.toString();
    const id = queryString.match(/^(\d+)/)?.[1] || failOverID;
    return id;
}


function fillStars(rating) {
    stars.forEach(star => {
        const starValue = star.getAttribute('data-value');
        if (starValue <= Math.floor(rating)) {
            starColorChange(star, 'orange');
        } else {
            starColorChange(star, 'black');
        }
    });

    const lastStarIndex = Math.ceil(rating);
    const lastStar = stars[lastStarIndex - 1];
    const decimalPart = rating % 1;

    if (lastStar && decimalPart > 0) {
        const gradient = `linear-gradient(90deg, orange ${Math.round(decimalPart * 100)}%, black ${Math.round(decimalPart * 100)}%)`;
        lastStar.style.background = gradient;
        lastStar.style.webkitBackgroundClip = 'text';
        lastStar.style.webkitTextFillColor = 'transparent';
    }
}

function starColorChange(star, color) {
    star.style.background = color;
    star.style.webkitBackgroundClip = 'text';
    star.style.webkitTextFillColor = 'transparent';       
}

// Retrieving the initial rating from the rating.php script.
function getInitialRating() {
id = getID();
const url = `rating.php?id=${id}&type=get`;

fetch(url)
    .then(response => response.json())
    .then(data => {
    const initialRating = data.average;
    currentRating = initialRating;
    fillStars(initialRating);
    })
    .catch(error => {
    console.error('Error:', error);
    fillStars(5);
    });
}

// Sending the rating to the rating.php file.
function sendRating(rating) {
const url = `rating.php?id=${id}&type=set&rating=${rating}`;

fetch(url)
    .then(response => {
    if (response.ok) {
        console.log('Rating has been sent!');
    } else {
        console.error('The error occurred while submitting the rating.');
    }
    })
    .catch(error => {
    console.error('Error:', error);
    });
}

// Calling the function to retrieve the initial rating upon page load.
getInitialRating();