# UI Integration Guide - Download Report Button

## Quick Integration

Add the "Download Report" button to your track pages by including the component:

### Option 1: Include Component (Recommended)

```blade
@include('components.download-report-button', ['trackId' => $track->id])
```

### Option 2: Custom Button with Same Functionality

```blade
<button
    class="btn btn-primary"
    onclick="openReportModal({{ $track->id }})"
>
    <i class="fa fa-download"></i> Download Report
</button>

{{-- Then include the modal at the bottom of the page --}}
@include('components.download-report-button', ['trackId' => $track->id])
```

---

## Where to Add the Button

### 1. Track Detail Page

**File**: Likely `resources/views/tracks/show.blade.php` or similar

**Location**: Add near other track actions (play, download, share, etc.)

```blade
<div class="track-actions">
    <button class="btn btn-success">Play</button>
    <button class="btn btn-info">Share</button>

    {{-- ADD THIS: --}}
    @include('components.download-report-button', ['trackId' => $track->id])
</div>
```

### 2. Client Dashboard - My Tracks List

**File**: Likely `resources/views/clients/dashboard.blade.php` or `resources/views/clients/tracks.blade.php`

**Location**: Add as dropdown menu item or button for each track

```blade
@foreach($tracks as $track)
    <div class="track-row">
        <span>{{ $track->title }}</span>
        <span>{{ $track->artist }}</span>

        <div class="track-actions">
            <a href="/track/{{ $track->id }}">View</a>
            <a href="/track/{{ $track->id }}/edit">Edit</a>

            {{-- ADD THIS: --}}
            @include('components.download-report-button', [
                'trackId' => $track->id,
                'buttonText' => 'Report',
                'buttonClass' => 'btn btn-sm btn-outline-primary'
            ])
        </div>
    </div>
@endforeach
```

### 3. Track Overview/Analytics Page

**File**: Likely `resources/views/tracks/analytics.blade.php` or similar

**Location**: At the top of the analytics page

```blade
<div class="page-header">
    <h1>{{ $track->title }} - Analytics</h1>

    {{-- ADD THIS: --}}
    @include('components.download-report-button', [
        'trackId' => $track->id,
        'buttonText' => 'Download Full Report (PDF)',
        'buttonClass' => 'btn btn-lg btn-primary'
    ])
</div>
```

---

## Component Parameters

The component accepts the following parameters:

| Parameter | Type | Required | Default | Description |
|-----------|------|----------|---------|-------------|
| `trackId` | int | Yes | - | The ID of the track |
| `buttonText` | string | No | "Download Report" | Text to display on button |
| `buttonClass` | string | No | "btn btn-primary" | CSS classes for button styling |

### Examples

**Small button for table rows:**
```blade
@include('components.download-report-button', [
    'trackId' => $track->id,
    'buttonText' => 'PDF',
    'buttonClass' => 'btn btn-sm btn-secondary'
])
```

**Large prominent button:**
```blade
@include('components.download-report-button', [
    'trackId' => $track->id,
    'buttonText' => 'Download Full Analytics Report',
    'buttonClass' => 'btn btn-lg btn-success'
])
```

**Icon only (for compact layouts):**
```blade
@include('components.download-report-button', [
    'trackId' => $track->id,
    'buttonText' => '<i class="fa fa-file-pdf-o"></i>',
    'buttonClass' => 'btn btn-icon btn-outline-primary'
])
```

---

## Modal Features

The button includes a modal with the following options:

### Report Types

1. **Validation Report** - Is track ready for distribution?
2. **DJ Demand Report** - Which DJs downloaded it?
3. **Regional Reaction** - Performance by location
4. **Format Performance** - Mixshow/Club/Radio breakdown
5. **Full Analytics Report** - Everything (default)

### Formats

- **PDF** - Professional report for viewing/sharing
- **CSV** - Spreadsheet format for data analysis

### Date Ranges

- Last 7 days
- Last 30 days
- Last 90 days
- All time (default)
- Custom range

---

## Dependencies

The component requires:

- ✅ **Bootstrap** (for modal functionality)
- ✅ **jQuery** (for modal toggling)
- ✅ **Font Awesome** (for icons - optional)

If you don't use Bootstrap/jQuery, you can modify the component to use your preferred modal library.

---

## Styling

The component includes basic styling. To customize:

### Override Default Styles

```css
/* In your custom CSS file */
.download-report-btn {
    background-color: #your-color;
    border-color: #your-border-color;
}

.download-report-btn:hover {
    background-color: #your-hover-color;
}
```

### Match Your Site's Design

Update the `buttonClass` parameter to use your existing button classes:

```blade
@include('components.download-report-button', [
    'trackId' => $track->id,
    'buttonClass' => 'your-custom-button-class'
])
```

---

## Testing the Button

After adding the button:

1. **View the page** - Button should appear
2. **Click the button** - Modal should open
3. **Select report options** - Options should be interactive
4. **Click "Generate Report"** - Should trigger report generation
5. **Download should start** - PDF should download automatically
6. **Modal should close** - After successful generation

### Troubleshooting

**Button doesn't appear:**
- Check that the component file exists at `resources/views/components/download-report-button.blade.php`
- Verify `$track->id` is available in the view

**Modal doesn't open:**
- Check that jQuery and Bootstrap are loaded
- Open browser console for JavaScript errors

**Report doesn't generate:**
- Check that routes are added (see `ROUTES_TO_ADD.md`)
- Verify TrackReportController exists
- Check browser console for errors

---

## Advanced: Dropdown Menu Version

For table rows or compact layouts, use a dropdown:

```blade
<div class="dropdown">
    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" data-toggle="dropdown">
        Actions
    </button>
    <div class="dropdown-menu">
        <a class="dropdown-item" href="/track/{{ $track->id }}">View</a>
        <a class="dropdown-item" href="/track/{{ $track->id }}/edit">Edit</a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="#" onclick="openReportModal({{ $track->id }}); return false;">
            <i class="fa fa-download"></i> Download Report
        </a>
    </div>
</div>

{{-- Include modal (hidden by default) --}}
@include('components.download-report-button', ['trackId' => $track->id])
```

---

## Next Steps

1. ✅ Add button to track detail page
2. ✅ Add button to dashboard
3. ✅ Test button functionality
4. ✅ Customize styling to match your site
5. ✅ Test PDF generation
6. ✅ Test CSV export (if needed)

---

**Need help?** Check the routes documentation: `ROUTES_TO_ADD.md`
