// JS bundle for guest

import './bootstrap';

import Alpine from 'alpinejs';
import mask from '@alpinejs/mask';
import focus from '@alpinejs/focus';
import collapse from '@alpinejs/collapse';
import grow from 'alpinejs-textarea-grow';
import anchor from '@alpinejs/anchor';
import intersect from '@alpinejs/intersect';
import persist from '@alpinejs/persist';

import { prefs } from './stores';
import Lenis from 'lenis';

import { alpineToast as toast, alpineModal as modal } from './notifiers';

Alpine.plugin(persist);
Alpine.plugin(intersect);
Alpine.plugin(grow);
Alpine.plugin(anchor);
Alpine.plugin(collapse);
Alpine.plugin(focus);
Alpine.plugin(mask);

Alpine.store('prefs', prefs);

Alpine.store('notifiers', { toast, modal });

Alpine.start();

if (window.location.pathname == '/' || true) {

    const lenis = new Lenis();

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

}

delete Alpine.version;
window.$ = Alpine;
