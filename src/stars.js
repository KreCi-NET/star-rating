const failOverID = 1; // Default product ID if not provided from URL
const stars = document.querySelectorAll('.rating-stars .star');
let currentRating = 5; // Default rating if no data for selected ID returned
let id = 0; // Default product ID if not provided from URL
let csrfToken = ''; //CSRF Token

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

// Sending the rating to local strorage
function localStorageManager(rating) {
    const storedRatings = JSON.parse(localStorage.getItem('productRatings')) || {};

    if (!storedRatings[id]) {
        storedRatings[id] = rating;
        localStorage.setItem('productRatings', JSON.stringify(storedRatings));
        return true;
    }
    return false;
}

// Retrieving the initial rating from the rating.php script.
function getInitialRating() {
id = getID();
const url = `rating.php?id=${id}&type=get`;

fetch(url)
    .then(response => response.json())
    .then(data => {
        const initialRating = data.average;
        const totalRatings = data.total;
        csrfToken = data.csrfToken;
        currentRating = initialRating;
        fillStars(initialRating);
        document.getElementById('averageSpan').textContent = initialRating;
        document.getElementById('totalSpan').textContent = totalRatings;
    })
        .catch(error => {
        console.error('Error:', error);
        fillStars(5);
    });
}

// Sending the rating to the rating.php file.
function sendRating(rating) {
    if (localStorageManager(rating)) {
        const url = `rating.php?id=${id}&type=set&rating=${rating}`;
        const headers = new Headers();
        headers.append('X-CSRF-Token', csrfToken);

        fetch(url, {
                method: 'POST',
                headers: headers
            })
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
    getInitialRating();
}

// Calling the function to retrieve the initial rating upon page load.
getInitialRating();