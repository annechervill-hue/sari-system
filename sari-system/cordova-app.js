// Cordova Mobile App specific configuration and initialization

// Wait for device ready event
document.addEventListener('deviceready', onDeviceReady, false);

function onDeviceReady() {
    console.log('Device is ready');
    // Cordova device APIs are available
    console.log('Running cordova ' + cordova.platformId + ' ' + cordova.version);
    
    // Initialize the app after Cordova is ready
    initializeApp();
}

function initializeApp() {
    // App initialization code here
    console.log('App initialized');
    
    // Handle back button for Android
    if (device.platform === 'Android') {
        document.addEventListener('backbutton', onBackKeyDown, false);
    }
}

function onBackKeyDown() {
    // Exit app on back button press
    navigator.app.exitApp();
}

// Export to global scope
window.cordovaApp = {
    onDeviceReady: onDeviceReady,
    initializeApp: initializeApp
};