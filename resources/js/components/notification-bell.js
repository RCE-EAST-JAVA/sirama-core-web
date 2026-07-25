/**
 * Notifikasi real-time component untuk Alpine.js
 * Digunakan di blade template untuk notification bell dropdown
 */
document.addEventListener('alpine:init', () => {
    Alpine.data('notificationBell', () => ({
        notifications: [],
        unreadCount: 0,
        loading: false,
        open: false,
        pollingInterval: null,

        init() {
            this.fetchNotifications();
            this.startPolling();
            this.subscribeToChannel();
        },

        destroy() {
            if (this.pollingInterval) {
                clearInterval(this.pollingInterval);
            }
        },

        startPolling() {
            this.pollingInterval = setInterval(() => {
                this.fetchUnreadCount();
            }, 30000);
        },

        subscribeToChannel() {
            if (typeof window.Echo === 'undefined') return;

            const userId = document.body?.dataset?.userId;
            if (!userId) return;
            const channelName = `App.Models.User.${userId}`;

            window.Echo.private(channelName)
                .notification((notification) => {
                    this.notifications.unshift({
                        id: Date.now().toString(),
                        type: notification.type,
                        data: {
                            title: notification.title,
                            message: notification.message,
                            pengajuan_id: notification.pengajuan_id,
                            action: notification.action,
                        },
                        read_at: null,
                        created_at: new Date().toISOString(),
                    });
                    this.unreadCount++;
                    this.showToast(notification.title, notification.message);
                });
        },

        getHeaders(method = 'GET') {
            const headers = {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            };
            
            // For web dashboard, use session-based auth (cookies)
            // Only use Bearer token if explicitly provided (for mobile/API)
            const token = document.body?.dataset?.token;
            if (token && token.trim() !== '') {
                headers['Authorization'] = `Bearer ${token}`;
            }
            
            // Always include CSRF token for state-changing requests
            if (method !== 'GET') {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                if (csrfToken) {
                    headers['X-CSRF-TOKEN'] = csrfToken;
                }
            }
            return headers;
        },

        async fetchNotifications() {
            this.loading = true;
            try {
                const response = await fetch('/api/notifications?per_page=10', {
                    headers: this.getHeaders(),
                    credentials: 'same-origin',
                });
                if (!response.ok) {
                    console.error('Notifications fetch failed:', response.status, response.statusText);
                    return;
                }
                const result = await response.json();
                this.notifications = result.data || [];
                this.unreadCount = result.meta?.unread_count || 0;
            } catch (error) {
                console.error('Failed to fetch notifications:', error);
            } finally {
                this.loading = false;
            }
        },

        async fetchUnreadCount() {
            try {
                const response = await fetch('/api/notifications/unread-count', {
                    headers: this.getHeaders(),
                    credentials: 'same-origin',
                });
                if (!response.ok) {
                    console.error('Unread count fetch failed:', response.status, response.statusText);
                    return;
                }
                const result = await response.json();
                this.unreadCount = result.unread_count || 0;
            } catch (error) {
                console.error('Failed to fetch unread count:', error);
            }
        },

        async markAsRead(notificationId) {
            try {
                const res = await fetch(`/api/notifications/${notificationId}/read`, {
                    method: 'POST',
                    headers: this.getHeaders('POST'),
                    credentials: 'same-origin',
                });
                if (!res.ok) return;

                const notif = this.notifications.find(n => n.id === notificationId);
                if (notif) {
                    notif.read_at = new Date().toISOString();
                    this.unreadCount = Math.max(0, this.unreadCount - 1);
                }
            } catch (error) {
                console.error('Failed to mark as read:', error);
            }
        },

        async markAllAsRead() {
            try {
                const res = await fetch('/api/notifications/read-all', {
                    method: 'POST',
                    headers: this.getHeaders('POST'),
                    credentials: 'same-origin',
                });
                if (!res.ok) return;

                this.notifications.forEach(n => { n.read_at = new Date().toISOString(); });
                this.unreadCount = 0;
            } catch (error) {
                console.error('Failed to mark all as read:', error);
            }
        },

        async deleteNotification(notificationId) {
            try {
                const res = await fetch(`/api/notifications/${notificationId}`, {
                    method: 'DELETE',
                    headers: this.getHeaders('DELETE'),
                    credentials: 'same-origin',
                });
                if (!res.ok) return;

                this.notifications = this.notifications.filter(n => n.id !== notificationId);
            } catch (error) {
                console.error('Failed to delete notification:', error);
            }
        },

        toggleDropdown() {
            this.open = !this.open;
            if (this.open) {
                this.fetchNotifications();
            }
        },

        closeDropdown() {
            this.open = false;
        },

        handleNotificationClick(notification) {
            // Redirect to notification detail page
            window.location.href = `/notifications/${notification.id}`;
            this.closeDropdown();
        },

        formatDate(dateString) {
            if (!dateString) return '';
            const date = new Date(dateString);
            const now = new Date();
            const diff = now - date;
            const minutes = Math.floor(diff / 60000);
            const hours = Math.floor(diff / 3600000);
            const days = Math.floor(diff / 86400000);

            if (minutes < 1) return 'Baru saja';
            if (minutes < 60) return `${minutes} menit lalu`;
            if (hours < 24) return `${hours} jam lalu`;
            if (days < 7) return `${days} hari lalu`;
            return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
        },

        showToast(title, message) {
            if (typeof window.dispatchEvent !== 'undefined') {
                window.dispatchEvent(new CustomEvent('notification-toast', {
                    detail: { title, message },
                }));
            }
        },
    }));
});