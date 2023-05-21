// make translation function __() available like in Laravel
export function __(string) {
    return typeof window.i18n != 'undefined' ? get(window.i18n, string, string) : string;
}

// make config() function available like in Laravel
export function config(string) {
    return typeof window.cfg != 'undefined' ? get(window.cfg, string) : string;
}

// copy element content to clipboard
export function copyToClipboard(el) {
    el.select();
    try {
        document.execCommand('copy');
    } catch (err) {
        //
    }
    // clear selection
    document.getSelection().removeAllRanges();
    document.activeElement.blur();
}

// get nested object property by its path
export function get(obj, path, defaultValue = null) {
    for (var i=0, path = path.split('.'), len = path.length; i < len; i++) {
        if (typeof obj[path[i]] == 'undefined') {
            return defaultValue ? defaultValue : undefined;
        } else {
            obj = obj[path[i]];
        }
    }
    return obj;
}