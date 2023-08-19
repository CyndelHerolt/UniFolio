/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';

import 'bootstrap';

// start the Stimulus application
import './bootstrap';

import './js/main.js';

import hljs from 'highlight.js';// Initialize Highlight.js
document.addEventListener('DOMContentLoaded', (event) => {
    hljs.initHighlightingOnLoad();
});
import 'highlight.js/styles/atom-one-dark.css';
// assets/app.js
// import '@symfony/ux-live-component/dist/live.css';
