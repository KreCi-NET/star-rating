## Rating Script

This rating script is a JavaScript/PHP component that allows users to rate an element using star icons. It provides interactive star ratings with hover and click functionality. The ratings are sent and retrieved using the `rating.php` script.

### Features

- Displays star icons that can be interacted with to rate an element.
- Updates the star colors dynamically based on the user's interaction.
- Sends the rating data to the server-side `rating.php` script for storage.
- Retrieves the initial rating from the `rating.php` script upon page load.

### Demo

Here you will find an implemented [star rating demo](https://dev.kreci.net/star-rating/vendor/kreci-net/star-rating/src/example.html). You may click on stars to submit the rating to the server and refresh the page to see average rating.

### Usage

1. Include the JavaScript file `stars.js` in your HTML file (after the rating element):

```html
<script src="rating.js"></script>
```

2. Add the following HTML structure for the rating stars:

```html
<div class="rating-stars">
    <span class="star" data-value="1">&#9733;</span>
    <span class="star" data-value="2">&#9733;</span>
    <span class="star" data-value="3">&#9733;</span>
    <span class="star" data-value="4">&#9733;</span>
    <span class="star" data-value="5">&#9733;</span>
</div>
```
Note: You can modify the number of stars by adding or removing `<span>` elements.

3. The script can rate multiple elements and stores the data in CSV file (`ratings.csv` in the example). The ID of rated element is extracted from the current URL using patterns like `"domain.com/?123,somedescription"`, `"domain.com/index.html?123"` or `"domain.com/index.php?123"`. You can modify the pattern by editing the `getID()` function in the `stars.js` file. If the ID is not found in the URL, it uses the `const failOverID` from the `stars.js` file.

### Server-side Script (RatingManager.php)

The server-side script (`RatingManager.php`) is responsible for processing and storing the rating data. It receives the rating value and the ID of the item being rated through the URL parameters and stores those with StorageInterface object (StorageCSV class to store in the file in the example).

### Example

You can find a working example in the `example.html` and `rating.php` files in this repository. The example demonstrates how to integrate the rating script to enable star-based ratings for an element.

### Browser Compatibility

This script is compatible with modern web browsers, including Chrome, Firefox, Safari, and Edge. It requires JavaScript support to function properly.

### Dependencies

This rating script has no external dependencies. It is built using native JavaScript/PHP.

### Known Issues

- None at the moment.

Feel free to customize and modify the script to fit your specific requirements.

If you have any questions or encounter any issues, please feel free to open an issue in the repository or reach out for support.

Enjoy using the rating script!

## License

This project is licensed under the MIT License.