import Alpine from 'alpinejs';
import Chart from 'chart.js/auto';
import { createIcons, icons } from 'lucide';

window.Alpine = Alpine;
window.Chart = Chart;
window.createIcons = createIcons;
window.lucideIcons = icons;

// Initialize Lucide icons on DOM loaded and after Alpine mutations
document.addEventListener('DOMContentLoaded', () => {
    createIcons({ icons });
});

window.initIcons = () => {
    createIcons({ icons });
};

Alpine.start();
