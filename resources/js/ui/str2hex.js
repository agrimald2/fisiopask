// Thanks:
// https://stackoverflow.com/questions/3426404/create-a-hexadecimal-colour-based-on-a-string-with-javascript

function hashCode(str) {
    // java String#hashCode
    var hash = 0;
    for (var i = 0; i < str.length; i++) {
        hash = str.charCodeAt(i) + ((hash << 5) - hash);
    }
    return hash;
}

function intToRGB(i) {
    var c = (i & 0x00ffffff).toString(16).toUpperCase();

    return "00000".substring(0, 6 - c.length) + c;
}

export default function (text) {
    return "#" + intToRGB(hashCode(text));
}
