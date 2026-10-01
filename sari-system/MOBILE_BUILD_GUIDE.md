# Sari-Sari Store POS - Mobile App Build Guide

## Convert to Mobile App using Apache Cordova

This guide will help you convert the web application to a mobile app that can be installed on Android and iOS devices.

### Prerequisites

1. **Node.js & npm** - Download from https://nodejs.org/
2. **Java Development Kit (JDK)** - For Android build
3. **Android SDK** - For Android emulator and build tools
4. **Git** - For version control

### Step 1: Install Apache Cordova CLI

```bash
npm install -g cordova
```

### Step 2: Create Cordova Project

```bash
# Navigate to your projects folder
cd C:\Users\YourName\Desktop

# Create a new Cordova project
cordova create SariSariPOS com.sararistore.pos "Sari-Sari Store POS"

# Navigate into the project
cd SariSariPOS
```

### Step 3: Add Platforms

```bash
# Add Android platform
cordova platform add android

# (Optional) Add iOS platform (macOS only)
cordova platform add ios
```

### Step 4: Copy Web Files

1. Copy all files from your **sari-system** folder
2. Replace the contents of **SariSariPOS/www** folder with:
   - `index.html`
   - `app.js`
   - `style.css`
   - `cordova-app.js`

### Step 5: Update index.html

Make sure `index.html` includes:

```html
<script src="cordova.js"></script>
<script src="cordova-app.js"></script>
<script src="app.js"></script>
```

### Step 6: Install Required Plugins

```bash
cordova plugin add cordova-plugin-whitelist
cordova plugin add cordova-plugin-device
cordova plugin add cordova-plugin-dialogs
```

### Step 7: Build APK for Android

```bash
# Build for Android
cordova build android

# Release build (signed APK)
cordova build android --release
```

**Output:** `SariSariPOS\platforms\android\app\build\outputs\apk\release\app-release.apk`

### Step 8: Install on Android Device

**Option A: Using USB Cable**
```bash
# Connect Android device with USB debugging enabled
cordova run android
```

**Option B: Manual APK Installation**
1. Download the APK from the build output folder
2. Email it or transfer via USB
3. Open on Android device
4. Tap **Install**
5. Grant permissions
6. Launch the app

### Step 9: Test on Android Emulator

```bash
# List available emulators
emulator -list-avds

# Start emulator
emulator -avd <emulator_name>

# Run app on emulator
cordova run android
```

### Step 10: Configure App (Optional Customizations)

Edit `config.xml` to customize:

```xml
<name>Sari-Sari Store POS</name>
<description>Point of Sale System</description>
<preference name="orientation" value="portrait" />
<preference name="fullscreen" value="true" />
```

### Step 11: Build for iOS (macOS only)

```bash
# Build for iOS
cordova build ios

# Open Xcode project
open platforms/ios/SariSariPOS.xcworkspace/
```

### Step 12: Distribute App

**For Testing:**
- Email APK to testers
- Use Firebase App Distribution
- Upload to Google Play (beta channel)

**For Production:**
- Upload APK to Google Play Store
- Submit to Apple App Store (iOS)
- Add app icon and screenshots
- Write app description

## Troubleshooting

### Issue: "cordova command not found"
**Solution:** Restart terminal after npm installation

### Issue: "Android SDK not found"
**Solution:** 
```bash
set ANDROID_SDK_ROOT=C:\Android\sdk
set ANDROID_HOME=C:\Android\sdk
```

### Issue: Build errors
**Solution:**
```bash
cordova clean android
cordova build android
```

## Project Structure

```
SariSariPOS/
├── www/                    # Web files
│   ├── index.html
│   ├── app.js
│   ├── style.css
│   └── cordova-app.js
├── platforms/              # Platform builds
│   ├── android/
│   └── ios/
├── plugins/                # Cordova plugins
├── config.xml              # App configuration
└── package.json            # Dependencies
```

## Connect to MySQL Backend

To connect your mobile app to the PHP/MySQL backend:

1. Update API endpoints in `app.js`:

```javascript
const API_BASE = 'http://your-server.com/sari-system/';

// Replace fetch calls
fetch(API_BASE + 'api.php?action=products')
    .then(res => res.json())
    .then(data => console.log(data));
```

2. Ensure your server allows CORS requests:

```php
// Add to api.php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
```

## Next Steps

1. Test thoroughly on multiple devices
2. Add app icon: `www/res/icon/android/icon-96x96.png`
3. Add splash screen
4. Implement offline support with Service Workers
5. Add push notifications
6. Publish to app stores

## Resources

- [Apache Cordova Docs](https://cordova.apache.org/docs/en/latest/)
- [Android Studio Setup](https://developer.android.com/studio)
- [Google Play Store](https://play.google.com/console)
- [Apple App Store](https://developer.apple.com/app-store/)
