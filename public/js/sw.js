(async () => {
    if ("serviceWorker" in navigator) {
        const registration = await navigator.serviceWorker.register("/loufok/js/sw.js");
        console.log(registration);

        if(window.localStorage.getItem('hasSubcribed') === null) {
            document.addEventListener('click', f);
        } else {
            return self.addEventListener("push", (event) => {
                console.log("push event", event);
                const data = event.data.json();
                console.log(data);
                event.waitUntil(self.registration.showNotification("Hello world", data));
            });
        }

        async function f () {
            document.removeEventListener('click', f);
            let subscription = await registration.pushManager.getSubscription();

            subscription = await registration.pushManager.subscribe({
                userVisibleOnly: true
                // applicationServerKey: 'BHpS58XK5JwhUXEXEnQxknmS8SMDnuI9oz5Ow0WrBsXTjFIMA3SwVlI3OEz953K6c36VCAeF7zjWZ-sayn2Whmg'
            });

            window.localStorage.setItem('hasSubcribed', JSON.stringify(await $post("/loufok/subscribe", subscription)));

            return self.addEventListener("push", (event) => {
                console.log("push event", event);
                const data = event.data.json();
                console.log(data);
                event.waitUntil(self.registration.showNotification("Hello world", data));
            });
        }
    }
})();
