/*
Give the service worker access to Firebase Messaging.
Note that you can only use Firebase Messaging here, other Firebase libraries are not available in the service worker.
*/
importScripts('https://www.gstatic.com/firebasejs/8.1.2/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.1.2/firebase-messaging.js');

//https://www.gstatic.com/firebasejs/8.1.2/firebase-app.js
/*
Initialize the Firebase app in the service worker by passing in the messagingSenderId.
* New configuration for app@pulseservice.com
*/
firebase.initializeApp({
    apiKey: "AIzaSyBthNF87Z9103fneTpfcaIKpOku0Iy7qBw",
    authDomain: "sesadigital-fb9d1.firebaseapp.com",
    projectId: "sesadigital-fb9d1",
    storageBucket: "sesadigital-fb9d1.appspot.com",
    messagingSenderId: "955878369247",
    appId: "1:955878369247:web:af4e8fea4378461f62c66a",
    measurementId: "G-116ESNEFSS"
});

/*
Retrieve an instance of Firebase Messaging so that it can handle background messages.
*/
const messaging = firebase.messaging();
messaging.setBackgroundMessageHandler(function (payload) {
    console.log(
        "[firebase-messaging-sw.js] Received background message ",
        payload,
    );
    /* Customize notification here */
    const notificationTitle = payload.data.title;
    const notificationOptions = {
        body: payload.data.body,
        icon: "/images/small-logo.png",
    };

    return self.registration.showNotification(
        notificationTitle,
        notificationOptions,
    );
});

// Handle incoming messages. Called when:
// - a message is received while the app has focus
// - the user clicks on an app notification created by a service worker
//   `messaging.setBackgroundMessageHandler` handler.
messaging.onBackgroundMessage((payload) => {
    console.log('Message received. ', payload);
    // ...
});


// messaging.onMessageReceived(function (data) {
//     console.log(data);
// });


self.addEventListener('notificationclick', event => {
    console.log(event)
    return event;
});