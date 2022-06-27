/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

// start the Stimulus application
import './bootstrap';


var followersList = document.getElementById('followers');
var followersButton = document.getElementById('followersButton');

const toggleCache = () => {
    followersList.classList.toggle('cache');
    console.log('click');
}
followersButton.addEventListener('click', toggleCache);
