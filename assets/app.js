// apz legacy
// loads the Bootstrap jQuery plugins
// import 'bootstrap-sass/assets/javascripts/bootstrap/transition.js';
// import 'bootstrap-sass/assets/javascripts/bootstrap/alert.js';
// import 'bootstrap-sass/assets/javascripts/bootstrap/collapse.js';
// import 'bootstrap-sass/assets/javascripts/bootstrap/dropdown.js';
// import 'bootstrap-sass/assets/javascripts/bootstrap/modal.js';

// loads the code syntax highlighting library
import './js/highlight.js';

// Creates links to the Symfony documentation
import './js/doclinks.js';


import './bootstrap.js'
import './js/admin.js'


// assets/app.js
import { registerReactControllerComponents } from '@symfony/ux-react';

// Registers React controller components to allow loading them from Twig
//
// React controller components are components that are meant to be rendered
// from Twig. These component then rely on other components that won't be called
// directly from Twig.
//
// By putting only controller components in `react/controllers`, you ensure that
// internal components won't be automatically included in your JS built file if
// they are not necessary.
registerReactControllerComponents(require.context('./react/controllers', true, /\.(j|t)sx?$/));
