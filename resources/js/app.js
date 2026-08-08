// resources/js/app.js

import './bootstrap';
import Alpine from 'alpinejs';

// CSS Files ko JS ke andar call karne ka sahi tareeqa:
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap/dist/js/bootstrap.bundle.min.js'; // Bootstrap ki JS bhi zaroori hai dropdowns ke liye

window.Alpine = Alpine;
Alpine.start();
