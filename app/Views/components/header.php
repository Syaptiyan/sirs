<header class="bg-base-100 shadow-sm border-b border-base-200 sticky top-0 z-30" x-data="{ notificationsOpen: false }">
    <div class="flex items-center justify-between h-16 px-4">
        <div class="flex items-center gap-4">
            <button @click="sidebarOpen = !sidebarOpen" class="btn btn-ghost btn-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16" />
                </svg>
            </button>

            <div class="form-control hidden sm:block">
                <div class="flex items-center gap-2">
                    <input
                        type="text"
                        placeholder="Cari..."
                        class="input input-bordered input-sm w-48 lg:w-64"
                        x-data
                        @keyup.enter="window.location.href='/search?q=' + $el.value"
                    />
                    <button class="btn btn-sm btn-square">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <div class="dropdown dropdown-end" x-data="notificationDropdown()">
                <label tabindex="0" class="btn btn-ghost btn-sm btn-circle indicator" @click="toggle()">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <span x-show="unreadCount > 0" class="badge badge-xs badge-primary indicator-item" x-text="unreadCount"></span>
                </label>
                
                <div 
                    tabindex="0" 
                    class="dropdown-content z-[1] card card-compact w-80 p-0 shadow-lg bg-base-100 mt-2"
                    x-show="open"
                    x-transition
                    @click.outside="open = false"
                >
                    <div class="card-body p-0">
                        <div class="flex items-center justify-between p-3 border-b border-base-200">
                            <h3 class="font-bold text-sm">Notifikasi</h3>
                            <button 
                                class="btn btn-ghost btn-xs"
                                @click="markAllRead()"
                                x-show="unreadCount > 0"
                            >
                                Tandai Dibaca
                            </button>
                        </div>
                        
                        <div class="max-h-80 overflow-y-auto">
                            <template x-if="loading">
                                <div class="flex justify-center p-4">
                                    <span class="loading loading-spinner loading-sm"></span>
                                </div>
                            </template>
                            
                            <template x-if="!loading && notifications.length === 0">
                                <div class="p-4 text-center text-base-content/50">
                                    Tidak ada notifikasi
                                </div>
                            </template>
                            
                            <template x-for="notif in notifications" :key="notif.id">
                                <a 
                                    :href="notif.link || '#'" 
                                    class="flex gap-3 p-3 hover:bg-base-200 border-b border-base-100 transition-colors"
                                    :class="{ 'bg-primary/5': !notif.is_read }"
                                    @click="markAsRead(notif.id)"
                                >
                                    <div class="flex-shrink-0 mt-1">
                                        <div 
                                            class="w-2 h-2 rounded-full"
                                            :class="notif.is_read ? 'bg-transparent' : 'bg-primary'"
                                        ></div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium truncate" x-text="notif.title"></p>
                                        <p class="text-xs text-base-content/60 line-clamp-2" x-text="notif.message"></p>
                                        <p class="text-xs text-base-content/40 mt-1" x-text="notif.time_ago"></p>
                                    </div>
                                </a>
                            </template>
                        </div>
                        
                        <div class="p-2 border-t border-base-200">
                            <a href="/notifications" class="btn btn-ghost btn-sm btn-block text-primary">
                                Lihat Semua Notifikasi
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div x-data="{ darkMode: localStorage.getItem('theme') === 'dark' }" class="flex items-center">
                <button
                    class="btn btn-ghost btn-sm btn-circle"
                    @click="
                        darkMode = !darkMode;
                        document.documentElement.setAttribute('data-theme', darkMode ? 'dark' : 'light');
                        localStorage.setItem('theme', darkMode ? 'dark' : 'light');
                    "
                >
                    <svg x-show="!darkMode" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                    <svg x-show="darkMode" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </button>
            </div>

            <div class="dropdown dropdown-end" x-data="{ open: false }">
                <label tabindex="0" class="btn btn-ghost btn-sm gap-2" @click="open = !open" @click.outside="open = false">
                    <div class="avatar placeholder">
                        <div class="bg-primary text-primary-content rounded-full w-8">
                            <span class="text-sm"><?= strtoupper(substr(session()->get('name') ?? 'U', 0, 1)) ?></span>
                        </div>
                    </div>
                    <span class="hidden md:inline text-sm"><?= session()->get('name') ?? 'User' ?></span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </label>
                <ul
                    tabindex="0"
                    class="dropdown-content z-[1] menu p-2 shadow-lg bg-base-100 rounded-box w-52 mt-2"
                    x-show="open"
                    x-transition
                >
                    <li class="menu-title">
                        <span><?= session()->get('name') ?? 'User' ?></span>
                        <span class="text-xs text-base-content/50"><?= session()->get('email') ?? '' ?></span>
                    </li>
                    <li>
                        <a href="/profile">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Profil
                        </a>
                    </li>
                    <li>
                        <a href="/settings">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Pengaturan
                        </a>
                    </li>
                    <div class="divider my-0"></div>
                    <li>
                        <a href="/logout" class="text-error" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <form id="logout-form" action="/logout" method="POST" class="hidden">
        <?= csrf_field() ?>
    </form>
</header>

<script>
function notificationDropdown() {
    return {
        open: false,
        loading: false,
        notifications: [],
        unreadCount: 0,
        
        init() {
            this.fetchNotifications();
            setInterval(() => this.fetchNotifications(), 60000);
        },
        
        toggle() {
            this.open = !this.open;
            if (this.open && this.notifications.length === 0) {
                this.fetchNotifications();
            }
        },
        
        async fetchNotifications() {
            this.loading = true;
            try {
                const response = await fetch('/api/notifications?limit=10');
                const data = await response.json();
                this.notifications = data.data || [];
                this.unreadCount = data.unread_count || 0;
            } catch (error) {
                console.error('Fetch notifications error:', error);
            } finally {
                this.loading = false;
            }
        },
        
        async markAsRead(id) {
            try {
                await fetch(`/api/notifications/${id}/read`, { method: 'POST' });
                const notif = this.notifications.find(n => n.id === id);
                if (notif) notif.is_read = true;
                this.unreadCount = Math.max(0, this.unreadCount - 1);
            } catch (error) {
                console.error('Mark as read error:', error);
            }
        },
        
        async markAllRead() {
            try {
                await fetch('/api/notifications/read-all', { method: 'POST' });
                this.notifications.forEach(n => n.is_read = true);
                this.unreadCount = 0;
            } catch (error) {
                console.error('Mark all read error:', error);
            }
        }
    };
}
</script>
