import { Livewire, Alpine } from '../../../vendor/livewire/livewire/dist/livewire.esm';
import anchor from '@alpinejs/anchor';
import { hydrateRoughElements } from './sketch.js';
import { initPetalnodeMotion, teardownMotion } from './motion.js';

document.addEventListener('livewire:init', () => {
    Livewire.hook('request', ({ fail }) => {
        fail(({ status, preventDefault }) => {
            if (status === 419) {
                window.location.reload();
                preventDefault();
            }
        });
    });

    Livewire.hook('morph.updated', ({ el }) => {
        hydrateRoughElements(el);
        initPetalnodeMotion(el);
    });
});

Alpine.store('notifications', {
    init() {
        Livewire.on('notify', (e) => {
            Alpine.store('notifications').addNotification(e);
        });
    },
    notifications: [],
    addNotification(notification) {
        const payload = Array.isArray(notification) ? notification[0] : notification;
        if (!payload) return;
        payload.show = false;
        payload.id = Date.now() + Math.floor(Math.random() * 1000);
        this.notifications.push(payload);

        Alpine.nextTick(() => {
            this.notifications = this.notifications.map((item) => {
                if (item.id === payload.id) {
                    item.show = true;
                }
                return item;
            });
        });

        setTimeout(() => {
            this.removeNotification(payload.id);
        }, payload.timeout || 5000);
    },
    removeNotification(id) {
        this.notifications = this.notifications
            .map((notification) => {
                if (notification.id === id) {
                    notification.show = false;
                }
                return notification;
            })
            .filter((notification) => notification.show);
    },
});

Alpine.store('confirmation', {
    show: false,
    loading: false,
    title: '',
    message: '',
    confirmText: 'Confirm',
    cancelText: 'Cancel',
    callback: null,

    confirm(options) {
        this.show = true;
        this.loading = false;
        this.title = options.title;
        this.message = options.message;
        this.confirmText = options.confirmText || 'Confirm';
        this.cancelText = options.cancelText || 'Cancel';
        this.callback = options.callback;
    },

    async execute() {
        if (this.loading) return;
        this.loading = true;
        try {
            if (this.callback) {
                await this.callback();
                this.loading = false;
            }
            this.close();
        } catch (error) {
            console.error('Confirmation callback failed:', error);
            this.close();
        }
    },

    close() {
        if (this.loading) return;
        this.show = false;
        this.loading = false;
        this.callback = null;
    },
});

Alpine.store('cartDrawer', {
    open: false,
    toggle() {
        this.open = !this.open;
    },
    close() {
        this.open = false;
    },
});

Alpine.plugin(anchor);

window.pnCopy = async (text, btn) => {
    try {
        await navigator.clipboard.writeText(text);
    } catch {
        const ta = document.createElement('textarea');
        ta.value = text;
        ta.style.position = 'fixed';
        ta.style.opacity = '0';
        document.body.appendChild(ta);
        ta.select();
        try {
            document.execCommand('copy');
        } catch {
            // Ignore clipboard fallback errors
        }
        ta.remove();
    }
    if (btn) {
        const idle = btn.querySelector('.pn-copy-idle');
        const done = btn.querySelector('.pn-copy-done');
        if (idle && done) {
            idle.classList.add('hidden');
            done.classList.remove('hidden');
            setTimeout(() => {
                done.classList.add('hidden');
                idle.classList.remove('hidden');
            }, 1600);
        }
    }
};

function bootPetalnode() {
    hydrateRoughElements(document);
    initPetalnodeMotion(document);
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', bootPetalnode, { once: true });
} else {
    bootPetalnode();
}

document.addEventListener('livewire:navigating', () => {
    teardownMotion();
    Alpine.store('cartDrawer').close();
});

document.addEventListener('livewire:navigated', () => {
    bootPetalnode();
});

if ('serviceWorker' in navigator) {
    navigator.serviceWorker
        .register('/service-worker.js')
        .catch((error) => {
            console.log('Service Worker registration failed:', error);
        });

    navigator.serviceWorker.onmessage = function (event) {
        if (event.data && event.data.type === 'SHOW_NOTIFICATION') {
            Livewire.dispatch('notification-added', [event.data.notification]);
            window.dispatchEvent(new CustomEvent('new-notification'));
        }
    };
}

Livewire.start();
