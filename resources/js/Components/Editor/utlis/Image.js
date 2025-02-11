/**
 * @typedef {Object} ImageResult
 * @property {boolean} complete
 * @property {number} width
 * @property {number} height
 * @property {string} src
 */

/**
 * @typedef {Object.<string, ImageResult>} ImageCache
 */

/** @type {ImageCache} */
const IMAGE_CACHE = {};

/**
 * @param {string} src
 * @returns {Promise<ImageResult>}
 */
function resolveImg(src) {
  return new Promise((resolve, reject) => {
    const result = {
      complete: false,
      width: 0,
      height: 0,
      src,
    };

    if (!src) {
      reject(result);
      return;
    }

    if (IMAGE_CACHE[src]) {
      resolve({ ...IMAGE_CACHE[src] });
      return;
    }

    const img = new Image();

    img.onload = () => {
      result.width = img.width;
      result.height = img.height;
      result.complete = true;

      IMAGE_CACHE[src] = { ...result };
      resolve(result);
    };

    img.onerror = () => {
      reject(result);
    };

    img.src = src;
  });
}

/** @enum {string} */
const ImageDisplay = {
  INLINE: 'inline',
  BREAK_TEXT: 'block',
  FLOAT_LEFT: 'left',
  FLOAT_RIGHT: 'right',
};
