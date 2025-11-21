@props(['type' => 'track', 'id' => null, 'showTitle' => true])

<div class="share-buttons-component" data-type="{{ $type }}" data-id="{{ $id }}">
    @if($showTitle)
    <h4 class="share-title">
        <i class="fas fa-share-alt"></i> Share{{ $type === 'review' ? ' This Review' : ' This Track' }}
    </h4>
    @endif

    <div class="button-group">
        <button onclick="shareItem('facebook', '{{ $type }}', {{ $id }})" class="share-btn btn-facebook" title="Share on Facebook">
            <i class="fab fa-facebook-f"></i>
            <span>Facebook</span>
        </button>

        <button onclick="shareItem('twitter', '{{ $type }}', {{ $id }})" class="share-btn btn-twitter" title="Share on Twitter">
            <i class="fab fa-twitter"></i>
            <span>Twitter</span>
        </button>

        <button onclick="shareItem('instagram', '{{ $type }}', {{ $id }})" class="share-btn btn-instagram" title="Copy for Instagram">
            <i class="fab fa-instagram"></i>
            <span>Instagram</span>
        </button>

        <button onclick="shareItem('tiktok', '{{ $type }}', {{ $id }})" class="share-btn btn-tiktok" title="Copy for TikTok">
            <i class="fab fa-tiktok"></i>
            <span>TikTok</span>
        </button>

        <button onclick="copyShareLink('{{ $type }}', {{ $id }})" class="share-btn btn-copy" title="Copy Link">
            <i class="fas fa-link"></i>
            <span>Copy Link</span>
        </button>

        @if($type === 'review')
        <button onclick="downloadReviewImage({{ $id }})" class="share-btn btn-download" title="Download Image">
            <i class="fas fa-image"></i>
            <span>Download</span>
        </button>
        @endif
    </div>
</div>

<style>
.share-buttons-component {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 12px;
    margin: 20px 0;
}

.share-title {
    font-size: 16px;
    color: #1a1a1a;
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.button-group {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
    gap: 10px;
}

.share-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 12px 16px;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.2s;
    color: white;
}

.share-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}

.share-btn i {
    font-size: 16px;
}

.btn-facebook {
    background: #1877f2;
}

.btn-twitter {
    background: #1da1f2;
}

.btn-instagram {
    background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%);
}

.btn-tiktok {
    background: #000000;
}

.btn-copy {
    background: #6c757d;
}

.btn-download {
    background: #28a745;
}

@media (max-width: 768px) {
    .button-group {
        grid-template-columns: repeat(2, 1fr);
    }

    .share-btn span {
        font-size: 12px;
    }
}
</style>
