/**
 * Social Sharing Functions
 * Handles all social media sharing for tracks and reviews
 */

// Get CSRF token
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

/**
 * Main share function
 * @param {string} platform - Social platform (facebook, twitter, instagram, tiktok)
 * @param {string} type - Type of content (track or review)
 * @param {int} id - ID of track or review
 */
async function shareItem(platform, type, id) {
    try {
        // Get share data from API
        const endpoint = type === 'track'
            ? `/api/tracks/${id}/share-data`
            : `/api/reviews/${id}/share-data`;

        const response = await fetch(endpoint);
        const data = await response.json();

        if (data.error) {
            alert(data.error);
            return;
        }

        // Share based on platform
        switch(platform) {
            case 'facebook':
                shareFacebook(data.public_url);
                break;
            case 'twitter':
                shareTwitter(data.public_url, data.twitter_text);
                break;
            case 'instagram':
                shareInstagram(data.instagram_caption);
                break;
            case 'tiktok':
                shareTikTok(data.tiktok_caption);
                break;
        }

        // Track the share
        trackShare(type, id, platform, data[`${platform}_text`] || '');

    } catch (error) {
        console.error('Share error:', error);
        alert('Failed to get share information. Please try again.');
    }
}

/**
 * Share on Facebook
 */
function shareFacebook(url) {
    const shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`;
    window.open(shareUrl, 'facebook-share', 'width=600,height=400,left=200,top=100');
}

/**
 * Share on Twitter
 */
function shareTwitter(url, text) {
    const shareUrl = `https://twitter.com/intent/tweet?text=${encodeURIComponent(text)}&url=${encodeURIComponent(url)}`;
    window.open(shareUrl, 'twitter-share', 'width=600,height=400,left=200,top=100');
}

/**
 * Share on Instagram (copy caption)
 */
function shareInstagram(caption) {
    navigator.clipboard.writeText(caption).then(() => {
        showModal('Instagram Sharing', `
            <p><strong>Caption copied to clipboard!</strong></p>
            <p>To share on Instagram:</p>
            <ol>
                <li>Open Instagram app</li>
                <li>Create a new post or story</li>
                <li>Paste the caption we copied for you</li>
                <li>Add your own photo/video</li>
            </ol>
            <textarea readonly style="width:100%;height:100px;margin-top:15px;padding:10px;border:1px solid #ddd;border-radius:8px;">${caption}</textarea>
        `);
    }).catch(() => {
        alert('Failed to copy to clipboard');
    });
}

/**
 * Share on TikTok (copy caption)
 */
function shareTikTok(caption) {
    navigator.clipboard.writeText(caption).then(() => {
        showModal('TikTok Sharing', `
            <p><strong>Caption copied to clipboard!</strong></p>
            <p>To share on TikTok:</p>
            <ol>
                <li>Open TikTok app</li>
                <li>Create a new video</li>
                <li>Paste the caption we copied for you</li>
                <li>Add music and publish</li>
            </ol>
            <textarea readonly style="width:100%;height:100px;margin-top:15px;padding:10px;border:1px solid #ddd;border-radius:8px;">${caption}</textarea>
        `);
    }).catch(() => {
        alert('Failed to copy to clipboard');
    });
}

/**
 * Copy share link
 */
async function copyShareLink(type, id) {
    try {
        const endpoint = type === 'track'
            ? `/api/tracks/${id}/share-data`
            : `/api/reviews/${id}/share-data`;

        const response = await fetch(endpoint);
        const data = await response.json();

        navigator.clipboard.writeText(data.public_url).then(() => {
            showNotification('✓ Link copied to clipboard!', 'success');
            trackShare(type, id, 'copy_link', data.public_url);
        });
    } catch (error) {
        alert('Failed to copy link');
    }
}

/**
 * Download review image (for Instagram/TikTok posts)
 */
function downloadReviewImage(reviewId) {
    window.location.href = `/api/reviews/${reviewId}/shareable-image`;
    trackShare('review', reviewId, 'download_image', '');
}

/**
 * Track share action (analytics)
 */
async function trackShare(type, id, platform, shareText) {
    try {
        await fetch('/api/share-tracking', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                type: type,
                id: id,
                platform: platform,
                share_text: shareText
            })
        });
    } catch (error) {
        console.error('Failed to track share:', error);
    }
}

/**
 * Show modal dialog
 */
function showModal(title, content) {
    // Create modal overlay
    const overlay = document.createElement('div');
    overlay.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.7);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        padding: 20px;
    `;

    // Create modal
    const modal = document.createElement('div');
    modal.style.cssText = `
        background: white;
        border-radius: 12px;
        max-width: 500px;
        width: 100%;
        padding: 30px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    `;

    modal.innerHTML = `
        <h3 style="margin: 0 0 20px 0; color: #1a1a1a;">${title}</h3>
        <div style="color: #666;">${content}</div>
        <button onclick="this.closest('[style*=fixed]').remove()" style="
            margin-top: 20px;
            padding: 12px 24px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            width: 100%;
        ">Close</button>
    `;

    overlay.appendChild(modal);
    document.body.appendChild(overlay);

    // Close on overlay click
    overlay.addEventListener('click', (e) => {
        if (e.target === overlay) {
            overlay.remove();
        }
    });
}

/**
 * Show notification toast
 */
function showNotification(message, type = 'info') {
    const toast = document.createElement('div');
    toast.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${type === 'success' ? '#28a745' : '#007bff'};
        color: white;
        padding: 15px 20px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        z-index: 10000;
        animation: slideIn 0.3s ease;
    `;
    toast.textContent = message;

    // Add animation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideIn {
            from { transform: translateX(400px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
    `;
    document.head.appendChild(style);

    document.body.appendChild(toast);

    setTimeout(() => {
        toast.style.animation = 'slideIn 0.3s ease reverse';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}
