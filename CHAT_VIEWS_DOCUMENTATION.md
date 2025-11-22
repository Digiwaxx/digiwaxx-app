# Chat Views Documentation

## 📋 Overview

This document provides UI templates and styling for the enhanced chat system. These snippets should be integrated into your existing blade templates.

---

## 🎨 Header Chat Icon with Badge

Add to your global header/navigation (both Member and Client areas):

```html
<!-- Chat Icon with Unread Badge -->
<li class="nav-item chat-nav-item">
    <a href="{{ route('member.messages') }}" class="nav-link chat-link">
        <i class="fas fa-comments"></i>
        <span class="chat-unread-badge" style="display: none;">0</span>
        <span class="sr-only">Messages</span>
    </a>
</li>

<style>
.chat-nav-item {
    position: relative;
}

.chat-link {
    position: relative;
    font-size: 1.2rem;
}

.chat-unread-badge {
    position: absolute;
    top: -5px;
    right: -10px;
    background-color: #ff4444;
    color: white;
    border-radius: 10px;
    padding: 2px 6px;
    font-size: 0.7rem;
    font-weight: bold;
    min-width: 18px;
    text-align: center;
}
</style>
```

---

## 💬 Message List View

Template for messages inbox (update existing views):

```html
<!-- Messages List -->
<div class="messages-container">
    <div class="messages-header">
        <h2>Messages</h2>

        <!-- Search Box -->
        <div class="message-search">
            <input type="text"
                   id="chat-search-input"
                   placeholder="Search messages..."
                   class="form-control">
        </div>

        <!-- Filter Tabs -->
        <ul class="message-tabs nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link active" href="{{ route('member.messages') }}">
                    All
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('member.messages.unread') }}">
                    Unread <span class="badge">{{ $unreadCount ?? 0 }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('member.messages.starred') }}">
                    Starred
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('member.messages.archived') }}">
                    Archived
                </a>
            </li>
        </ul>
    </div>

    <!-- Message List -->
    <div class="message-list">
        @forelse($messages as $message)
            <div class="conversation-item {{ $message->unread == 0 ? 'unread' : '' }}"
                 data-partner-id="{{ $message->senderId }}"
                 data-partner-type="{{ $message->senderType }}"
                 onclick="window.location='{{ route('member.conversation', $message->senderId) }}'">

                <!-- User Avatar/Status -->
                <div class="conversation-avatar">
                    <img src="{{ $message->avatar ?? '/images/default-avatar.png' }}"
                         alt="{{ $message->senderName }}">
                    <span class="user-status {{ $message->isOnline ? 'online' : 'offline' }}"
                          data-user-id="{{ $message->senderId }}"
                          data-user-type="{{ $message->senderType }}"
                          title="{{ $message->isOnline ? 'Online' : 'Offline' }}">
                    </span>
                </div>

                <!-- Message Preview -->
                <div class="conversation-content">
                    <div class="conversation-header">
                        <h4 class="sender-name">{{ $message->senderName }}</h4>
                        <span class="message-time">{{ formatTime($message->dateTime) }}</span>
                    </div>

                    <div class="message-preview">
                        @if($message->attachment_type)
                            <span class="attachment-icon">
                                @if($message->attachment_type == 'image') 📷
                                @elseif($message->attachment_type == 'audio') 🎵
                                @elseif($message->attachment_type == 'video') 🎬
                                @elseif($message->attachment_type == 'document') 📄
                                @else 📎
                                @endif
                            </span>
                        @endif
                        {{ Str::limit($message->message, 60) }}
                    </div>
                </div>

                <!-- Unread Indicator -->
                @if($message->unread == 0)
                    <div class="unread-indicator">
                        <span class="badge badge-primary">New</span>
                    </div>
                @endif
            </div>
        @empty
            <div class="no-messages">
                <p>No messages yet</p>
            </div>
        @endforelse
    </div>

    <!-- Search Results (hidden by default) -->
    <div id="chat-search-results" class="search-results" style="display: none;"></div>
</div>

<style>
.messages-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.message-list {
    border: 1px solid #ddd;
    border-radius: 8px;
    overflow: hidden;
}

.conversation-item {
    display: flex;
    align-items: center;
    padding: 15px;
    border-bottom: 1px solid #eee;
    cursor: pointer;
    transition: background-color 0.2s;
}

.conversation-item:hover {
    background-color: #f5f5f5;
}

.conversation-item.unread {
    background-color: #e3f2fd;
    font-weight: 600;
}

.conversation-avatar {
    position: relative;
    margin-right: 15px;
}

.conversation-avatar img {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
}

.user-status {
    position: absolute;
    bottom: 0;
    right: 0;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid white;
}

.user-status.online {
    background-color: #4caf50;
}

.user-status.offline {
    background-color: #999;
}

.conversation-content {
    flex: 1;
}

.conversation-header {
    display: flex;
    justify-content: space-between;
    margin-bottom: 5px;
}

.sender-name {
    font-size: 1rem;
    margin: 0;
}

.message-time {
    color: #999;
    font-size: 0.85rem;
}

.message-preview {
    color: #666;
    font-size: 0.9rem;
}

.attachment-icon {
    margin-right: 5px;
}

.unread-indicator {
    margin-left: 10px;
}

.no-messages {
    padding: 40px;
    text-align: center;
    color: #999;
}
</style>
```

---

## 💬 Conversation View

Template for 1-on-1 conversation (update existing conversation views):

```html
<div class="conversation-container">
    <!-- Conversation Header -->
    <div class="conversation-header">
        <div class="partner-info">
            <img src="{{ $partner->avatar ?? '/images/default-avatar.png' }}"
                 alt="{{ $partner->name }}"
                 class="partner-avatar">
            <div class="partner-details">
                <h3>{{ $partner->name }}</h3>
                <span class="user-status-text {{ $partner->isOnline ? 'online' : 'offline' }}"
                      data-user-id="{{ $partner->id }}"
                      data-user-type="{{ $partner->type }}">
                    {{ $partner->isOnline ? 'Online' : 'Last seen ' . $partner->lastSeenAt }}
                </span>
            </div>
        </div>

        <div class="conversation-actions">
            <button type="button" class="btn btn-sm" onclick="searchInConversation()">
                <i class="fas fa-search"></i>
            </button>
            <button type="button" class="btn btn-sm" onclick="archiveConversation()">
                <i class="fas fa-archive"></i>
            </button>
            <a href="{{ route('member.messages') }}" class="btn btn-sm">
                <i class="fas fa-times"></i>
            </a>
        </div>
    </div>

    <!-- Messages Area -->
    <div class="chat-messages" id="chat-messages-container">
        @foreach($messages as $message)
            @php
                $isSent = $message->senderId == $currentUserId && $message->senderType == $currentUserType;
                $isRead = $message->unread == 1;
            @endphp

            <div class="chat-message {{ $isSent ? 'sent' : 'received' }} {{ !$isSent && !$isRead ? 'unread' : '' }}"
                 data-message-id="{{ $message->messageId }}">

                @if(!$isSent)
                    <img src="{{ $message->senderAvatar ?? '/images/default-avatar.png' }}"
                         alt="{{ $message->senderName }}"
                         class="message-avatar">
                @endif

                <div class="message-bubble">
                    <!-- Message Content -->
                    <div class="message-content">
                        {{ $message->message }}
                    </div>

                    <!-- Attachment -->
                    @if($message->attachment_path)
                        <div class="message-attachment">
                            @if($message->attachment_type == 'image')
                                <img src="{{ Storage::url($message->attachment_path) }}"
                                     alt="{{ $message->attachment_name }}"
                                     class="attachment-image"
                                     onclick="openImageModal(this.src)">
                            @else
                                <a href="{{ route('member.message.download', $message->messageId) }}"
                                   class="attachment-link"
                                   download="{{ $message->attachment_name }}">
                                    @if($message->attachment_type == 'audio') 🎵
                                    @elseif($message->attachment_type == 'video') 🎬
                                    @elseif($message->attachment_type == 'document') 📄
                                    @else 📎
                                    @endif
                                    {{ $message->attachment_name }}
                                    <span class="file-size">({{ formatFileSize($message->attachment_size) }})</span>
                                </a>
                            @endif
                        </div>
                    @endif

                    <!-- Message Meta -->
                    <div class="message-meta">
                        <span class="message-time">
                            {{ date('g:i A', strtotime($message->dateTime)) }}
                        </span>

                        @if($isSent)
                            <span class="read-receipt {{ $isRead ? 'read' : 'delivered' }}">
                                {{ $isRead ? '✓✓' : '✓' }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach

        <!-- Typing Indicator (hidden by default) -->
        <div id="typing-indicator" class="typing-indicator" style="display: none;">
            <em>User is typing...</em>
        </div>
    </div>

    <!-- Message Input -->
    <div class="message-input-container">
        <form id="chat-message-form" enctype="multipart/form-data">
            @csrf

            <!-- Attachment Preview (dynamically added) -->

            <div class="input-wrapper">
                <!-- Attachment Button -->
                <label for="attachment-input" class="btn-attachment" title="Attach file">
                    <i class="fas fa-paperclip"></i>
                    <input type="file"
                           name="attachment"
                           id="attachment-input"
                           accept="image/*,audio/*,video/*,.pdf,.doc,.docx"
                           style="display: none;">
                </label>

                <!-- Message Textarea -->
                <textarea name="message"
                          class="form-control message-input"
                          placeholder="Type a message..."
                          rows="1"
                          maxlength="5000"></textarea>

                <!-- Send Button -->
                <button type="submit" class="btn btn-primary btn-send" title="Send">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<style>
.conversation-container {
    display: flex;
    flex-direction: column;
    height: calc(100vh - 100px);
    max-width: 1200px;
    margin: 0 auto;
    border: 1px solid #ddd;
    border-radius: 8px;
    overflow: hidden;
}

.conversation-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px;
    border-bottom: 1px solid #ddd;
    background-color: #f9f9f9;
}

.partner-info {
    display: flex;
    align-items: center;
}

.partner-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    margin-right: 10px;
}

.partner-details h3 {
    margin: 0;
    font-size: 1.1rem;
}

.user-status-text {
    font-size: 0.85rem;
    color: #999;
}

.user-status-text.online {
    color: #4caf50;
}

.chat-messages {
    flex: 1;
    padding: 20px;
    overflow-y: auto;
    background-color: #f5f5f5;
}

.chat-message {
    display: flex;
    margin-bottom: 15px;
    align-items: flex-end;
}

.chat-message.sent {
    justify-content: flex-end;
}

.chat-message.received {
    justify-content: flex-start;
}

.message-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    margin-right: 8px;
}

.message-bubble {
    max-width: 60%;
    background-color: white;
    padding: 10px 15px;
    border-radius: 18px;
    box-shadow: 0 1px 2px rgba(0,0,0,0.1);
}

.chat-message.sent .message-bubble {
    background-color: #007bff;
    color: white;
}

.message-content {
    word-wrap: break-word;
    white-space: pre-wrap;
}

.message-attachment {
    margin-top: 10px;
}

.attachment-image {
    max-width: 100%;
    border-radius: 8px;
    cursor: pointer;
}

.attachment-link {
    display: inline-block;
    padding: 8px 12px;
    background-color: rgba(0,0,0,0.05);
    border-radius: 8px;
    text-decoration: none;
    color: inherit;
}

.message-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 5px;
    font-size: 0.75rem;
    color: #999;
}

.chat-message.sent .message-meta {
    color: rgba(255,255,255,0.7);
}

.read-receipt {
    margin-left: 5px;
}

.read-receipt.delivered {
    color: #999;
}

.read-receipt.read {
    color: #2196f3;
}

.typing-indicator {
    padding: 10px;
    font-style: italic;
    color: #666;
}

.message-input-container {
    border-top: 1px solid #ddd;
    padding: 15px;
    background-color: white;
}

.input-wrapper {
    display: flex;
    align-items: center;
    gap: 10px;
}

.btn-attachment {
    cursor: pointer;
    padding: 8px;
    color: #007bff;
    font-size: 1.2rem;
}

.message-input {
    flex: 1;
    resize: none;
    border-radius: 20px;
    padding: 10px 15px;
    border: 1px solid #ddd;
}

.btn-send {
    border-radius: 50%;
    width: 40px;
    height: 40px;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
}

.attachment-preview {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px;
    background-color: #f0f0f0;
    border-radius: 8px;
    margin-bottom: 10px;
}

.attachment-preview .file-name {
    font-weight: 500;
}

.attachment-preview .file-size {
    color: #666;
    margin-left: 10px;
}

.remove-attachment {
    background: none;
    border: none;
    font-size: 1.5rem;
    color: #999;
    cursor: pointer;
    padding: 0 5px;
}
</style>

<script>
// Auto-expand textarea
$('.message-input').on('input', function() {
    this.style.height = 'auto';
    this.style.height = (this.scrollHeight) + 'px';
});

// Image modal
function openImageModal(src) {
    // Implement image lightbox/modal
    alert('Opening image: ' + src);
}

// Archive conversation
function archiveConversation() {
    if (confirm('Archive this conversation?')) {
        // Implement archive logic
    }
}

// Search in conversation
function searchInConversation() {
    const keyword = prompt('Search in conversation:');
    if (keyword) {
        window.chatInstance.searchMessages(keyword).done(function(response) {
            console.log('Search results:', response);
            // Display results
        });
    }
}
</script>
```

---

## 🔔 Browser Notification Permission Request

Add to dashboard on first load:

```html
<script>
// Request notification permission
if ('Notification' in window && Notification.permission === 'default') {
    Notification.requestPermission().then(function(permission) {
        console.log('Notification permission:', permission);
    });
}
</script>
```

---

## 📱 Responsive Styles

```css
/* Mobile Responsive */
@media (max-width: 768px) {
    .conversation-container {
        height: calc(100vh - 60px);
        border-radius: 0;
    }

    .message-bubble {
        max-width: 80%;
    }

    .conversation-header {
        padding: 10px;
    }

    .partner-avatar {
        width: 32px;
        height: 32px;
    }

    .chat-messages {
        padding: 10px;
    }
}
```

---

## ✅ Integration Checklist

- [ ] Add chat icon to header with unread badge
- [ ] Update message list view with online indicators
- [ ] Update conversation view with typing indicators
- [ ] Add file attachment UI
- [ ] Add read receipt indicators
- [ ] Request browser notification permission
- [ ] Test responsive design on mobile
- [ ] Add loading states for AJAX operations
- [ ] Add error handling UI

---

## 📚 Related Documentation

- **CHAT_JAVASCRIPT_DOCUMENTATION.md** - JavaScript integration
- **CHAT_CONTROLLER_ENHANCEMENTS.md** - Backend methods
- **CHAT_ROUTES_DOCUMENTATION.md** - API routes
- **CHAT_ENHANCEMENT_IMPLEMENTATION_GUIDE.md** - Main guide

---

**Implementation Complete!** Follow the main implementation guide to deploy all components.
