export function noop() {} // Empty function

/**
 * Check whether a value is NaN
 */
export function isNaN(val) {
  return Number.isNaN(val);
}

export function clamp(val, min, max) {
  if (val < min) {
    return min;
  }
  if (val > max) {
    return max;
  }
  return val;
}

export function readFileDataUrl(file) {
  const reader = new FileReader();

  return new Promise((resolve, reject) => {
    reader.onload = readerEvent => resolve(readerEvent.target.result);
    reader.onerror = reject;

    reader.readAsDataURL(file);
  });
}

/**
 * Create a cached version of a pure function.
 */
export function cached(fn) {
  const cache = {};

  return function cachedFn(str) {
    const hit = cache[str];
    return hit || (cache[str] = fn(str));
  };
}

/**
 * Capitalize a string.
 */
export const capitalize = cached((str) => {
  return str.charAt(0).toUpperCase() + str.slice(1);
});

/**
 * Strict object type check. Only returns true
 * for plain JavaScript objects.
 */
export function isPlainObject(obj) {
  return Object.prototype.toString.call(obj) === '[object Object]';
}