<?php
// Notification Bell Component
// Usage: include this file in any page that needs a notification bell
// Requires: $unreadCount variable to be set before including this component
?>

<div class="relative" x-data="{ open: false }">
    <!-- Notification Bell Button -->
    <button @click="open = !open" class="relative p-2 text-gray-600 hover:text-gray-800 focus:outline-none focus:ring-2 focus:ring-red-500 rounded-full">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.5 19.5a15 15 0 01-1.44-2.1A5.5 5.5 0 014.5 19.5zM19.5 4.5a15 15 0 00-1.44 2.1A5.5 5.5 0 0119.5 4.5z"></path>
        </svg>
        <!-- Red dot for unread notifications -->
        <?php if (isset($unreadCount) && $unreadCount > 0): ?>
            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                <?php echo $unreadCount > 9 ? '9+' : $unreadCount; ?>
            </span>
        <?php endif; ?>
    </button>
    
    <!-- Notification Dropdown -->
    <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg border border-gray-200 z-50">
        <div class="p-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Notifications</h3>
                <?php if (isset($unreadCount) && $unreadCount > 0): ?>
                    <button onclick="markAllAsRead()" class="text-sm text-red-600 hover:text-red-800">Mark all as read</button>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="max-h-96 overflow-y-auto">
            <?php if (empty($notifications)): ?>
                <div class="p-4 text-center text-gray-500">
                    <svg class="w-12 h-12 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.5 19.5a15 15 0 01-1.44-2.1A5.5 5.5 0 014.5 19.5zM19.5 4.5a15 15 0 00-1.44 2.1A5.5 5.5 0 0119.5 4.5z"></path>
                    </svg>
                    <p>No notifications</p>
                </div>
            <?php else: ?>
                <?php foreach ($notifications as $notif): ?>
                    <div class="p-4 border-b border-gray-100 hover:bg-gray-50 cursor-pointer" onclick="markAsRead(<?php echo $notif['id']; ?>)">
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                <?php
                                $iconClass = '';
                                $bgClass = '';
                                switch ($notif['type']) {
                                    case 'success':
                                        $iconClass = 'text-green-600';
                                        $bgClass = 'bg-green-100';
                                        break;
                                    case 'warning':
                                        $iconClass = 'text-yellow-600';
                                        $bgClass = 'bg-yellow-100';
                                        break;
                                    case 'error':
                                        $iconClass = 'text-red-600';
                                        $bgClass = 'bg-red-100';
                                        break;
                                    default:
                                        $iconClass = 'text-blue-600';
                                        $bgClass = 'bg-blue-100';
                                }
                                ?>
                                <div class="w-8 h-8 <?php echo $bgClass; ?> rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 <?php echo $iconClass; ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($notif['title']); ?></p>
                                <p class="text-sm text-gray-500 truncate"><?php echo htmlspecialchars($notif['message']); ?></p>
                                <p class="text-xs text-gray-400 mt-1"><?php echo date('M j, Y g:i A', strtotime($notif['created_at'])); ?></p>
                            </div>
                            <?php if (!$notif['is_read']): ?>
                                <div class="w-2 h-2 bg-red-500 rounded-full flex-shrink-0"></div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <div class="p-4 border-t border-gray-200">
            <a href="notifications.php" class="block text-center text-sm text-red-600 hover:text-red-800">View all notifications</a>
        </div>
    </div>
</div>

<script>
function markAsRead(notificationId) {
    // AJAX call to mark notification as read
    fetch('ajax/mark_notification_read.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ notification_id: notificationId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update UI - remove red dot and mark as read
            location.reload(); // Simple reload for now
        }
    })
    .catch(error => console.error('Error:', error));
}

function markAllAsRead() {
    // AJAX call to mark all notifications as read
    fetch('ajax/mark_all_notifications_read.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({})
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload(); // Simple reload for now
        }
    })
    .catch(error => console.error('Error:', error));
}
</script>

<!-- Include Alpine.js for dropdown functionality -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
