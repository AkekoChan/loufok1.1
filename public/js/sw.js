self.addEventListener("push", (event) => {
    const data = JSON.parse(event.data.text());
    console.log(event.data);
    console.log(data);
    const options = {
        body: data["message"],
        icon: '/loufok/assets/icons/icon-48x48.png', // Remplacez par le chemin de votre icône
        badge: '/loufok/assets/icons/icon-48x48.png' // Remplacez par le chemin de votre badge
    };
    self.registration.showNotification(data["title"], options);
});