const $get = async (url, type = 'json') => {
    try {
        const context = await fetch(url, { method: "GET" });
        const response = await context[type](); return response;
    } catch (e) {
        console.error(e);
    }
}

const $post = async (url, body = [], type = 'json', headers = null) => {
    try {
        const context = await fetch(url, { method: "POST", headers: headers ?? { 'Accept': 'application/json', 'Content-Type': 'application/json' }, body: JSON.stringify(body) });
        const response = await context[type](); return response;
    } catch (e) {
        console.error(e);
    }
}

const $ = (el) => { return document.querySelector(el); }
const $$ = (els) => { return document.querySelectorAll(els); }

const Random = (min, max) => { return Math.floor(Math.random() * (max - min) + min); }