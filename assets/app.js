/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';

// start the Stimulus application
import './bootstrap';

import m from "mithril";
var element = document.getElementById('mithril')
if (element) {
    m.render(element, "<div class=\"notification is-info\">\n" +
        "  <button class=\"delete\"></button>\n" +
        "  Hello world!!!\n" +
        "  I am a <strong>demo</strong>. Show more <a href=\"https://bulma.io/documentation\">here</a>.\n" +
        "</div>");
}
