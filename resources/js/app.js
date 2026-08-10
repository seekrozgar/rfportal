// resources/js/app.js

import './bootstrap';
import Alpine from 'alpinejs';

// CSS Files ko JS ke andar call karne ka sahi tareeqa:
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap/dist/js/bootstrap.bundle.min.js'; // Bootstrap ki JS bhi zaroori hai dropdowns ke liye

import '@fontsource/open-sans/300.css';
import '@fontsource/open-sans/400.css';
import '@fontsource/open-sans/600.css';
import '@fontsource/open-sans/700.css';
import '@fontsource/open-sans/800.css';

window.Alpine = Alpine;
Alpine.start();
