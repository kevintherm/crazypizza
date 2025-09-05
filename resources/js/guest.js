// JS bundle for guest

import Alpine from 'alpinejs';
import mask from '@alpinejs/mask';
import focus from '@alpinejs/focus';
import collapse from '@alpinejs/collapse';
import grow from 'alpinejs-textarea-grow';
import anchor from '@alpinejs/anchor';
import intersect from '@alpinejs/intersect'

import { prefs } from './stores';

Alpine.plugin(intersect)
Alpine.plugin(grow);
Alpine.plugin(anchor);
Alpine.plugin(collapse);
Alpine.plugin(focus);
Alpine.plugin(mask);

Alpine.store('prefs', prefs);

Alpine.start();
