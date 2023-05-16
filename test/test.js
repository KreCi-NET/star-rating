const assert = require('assert');
const script = require('../src/stars.js');

describe('Script Tests', () => {
  it('should fill stars correctly', () => {
    const rating = 3.7;
    const expectedStars = [true, true, true, false, false];

    script.fillStars(rating);
    const actualStars = Array.from(document.querySelectorAll('.rating-stars .star')).map(star => star.classList.contains('filled'));

    assert.deepStrictEqual(actualStars, expectedStars);
  });

  it('should send rating correctly', () => {
    const rating = 4.5;
    const expectedResponse = 'Rating has been sent!';

    const consoleSpy = jest.spyOn(console, 'log').mockImplementation();
    script.sendRating(rating);

    expect(consoleSpy).toHaveBeenCalledWith(expectedResponse);

    consoleSpy.mockRestore();
  });
});
