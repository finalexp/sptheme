// 3rd party packages from NPM
import $ from 'jquery';
import slick from 'slick-carousel';
var _ = require('lodash');

var array = [1,2,3,4,5,6,7,8];
console.log('answer:', _.without(array, 3));

// Our modules / classes
import MobileMenu from './modules/MobileMenu';
import HeroSlider from './modules/HeroSlider';
import GoogleMap from './modules/GoogleMap';
import Search from './modules/Search';
import MyNotes from './modules/MinaAnteckningar';
import Like from './modules/Like';

// Instantiate a new object using our modules/classes
var mobileMenu = new MobileMenu();
var heroSlider = new HeroSlider();
var googleMap = new GoogleMap();
var search = new Search();
var mynotes = new MyNotes();
var like = new Like();