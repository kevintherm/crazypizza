// JS bundle for guest

import Alpine from 'alpinejs';
import mask from '@alpinejs/mask';
import focus from '@alpinejs/focus';
import collapse from '@alpinejs/collapse';
import grow from 'alpinejs-textarea-grow';
import anchor from '@alpinejs/anchor';
import intersect from '@alpinejs/intersect'

import { prefs } from './stores';
import Lenis from 'lenis';

Alpine.plugin(intersect)
Alpine.plugin(grow);
Alpine.plugin(anchor);
Alpine.plugin(collapse);
Alpine.plugin(focus);
Alpine.plugin(mask);

Alpine.store('prefs', prefs);

Alpine.start();

const lenis = new Lenis();

// Use requestAnimationFrame to continuously update the scroll
function raf(time) {
    lenis.raf(time);
    requestAnimationFrame(raf);
}

requestAnimationFrame(raf);

document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', e => {
        e.preventDefault()
        const target = document.querySelector(anchor.getAttribute('href'))
        if (target) {
            lenis.scrollTo(target, { offset: -100 })
        }
    })
})
