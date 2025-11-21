{{--
    Download Report Button Component

    Usage in Blade templates:
    @include('components.download-report-button', ['trackId' => $track->id])

    Or if using Blade components:
    <x-download-report-button :trackId="$track->id" />
--}}

@props(['trackId', 'buttonText' => 'Download Report', 'buttonClass' => 'btn btn-primary'])

<div class="download-report-wrapper">
    <button
        type="button"
        class="{{ $buttonClass }} download-report-btn"
        data-track-id="{{ $trackId }}"
        onclick="openReportModal({{ $trackId }})"
    >
        <i class="fa fa-download"></i> {{ $buttonText }}
    </button>
</div>

{{-- Report Options Modal --}}
<div id="reportModal{{ $trackId }}" class="modal fade" tabindex="-1" role="dialog" style="display: none;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Generate Track Report</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="reportForm{{ $trackId }}">
                    <div class="form-group">
                        <label><strong>Report Type:</strong></label>
                        <div class="radio">
                            <label>
                                <input type="radio" name="report_type" value="validation" checked>
                                <strong>Validation Report</strong> - Is your track ready for distribution?
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                <input type="radio" name="report_type" value="demand">
                                <strong>DJ Demand Report</strong> - Which DJs downloaded your track?
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                <input type="radio" name="report_type" value="regional">
                                <strong>Regional Reaction</strong> - Performance by city/country
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                <input type="radio" name="report_type" value="format">
                                <strong>Format Performance</strong> - Mixshow vs Club vs Radio
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                <input type="radio" name="report_type" value="full">
                                <strong>Full Analytics Report</strong> - Everything (recommended)
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label><strong>Format:</strong></label>
                        <div class="radio">
                            <label>
                                <input type="radio" name="format" value="pdf" checked>
                                PDF (recommended for viewing)
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                <input type="radio" name="format" value="csv">
                                CSV (for spreadsheet analysis)
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label><strong>Date Range:</strong></label>
                        <select name="date_range" class="form-control">
                            <option value="7">Last 7 days</option>
                            <option value="30">Last 30 days</option>
                            <option value="90">Last 90 days</option>
                            <option value="all" selected>All time</option>
                            <option value="custom">Custom range...</option>
                        </select>
                    </div>

                    <div id="customDateRange{{ $trackId }}" style="display: none;" class="form-group">
                        <label>Start Date:</label>
                        <input type="date" name="start_date" class="form-control">
                        <label>End Date:</label>
                        <input type="date" name="end_date" class="form-control">
                    </div>
                </form>

                <div id="reportProgress{{ $trackId }}" style="display: none;">
                    <div class="progress">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 100%">
                            Generating your report...
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="generateReport({{ $trackId }})">
                    Generate Report
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function openReportModal(trackId) {
    $('#reportModal' + trackId).modal('show');
}

function generateReport(trackId) {
    const form = document.getElementById('reportForm' + trackId);
    const formData = new FormData(form);

    // Show progress bar
    document.getElementById('reportProgress' + trackId).style.display = 'block';

    // Build query parameters
    const reportType = formData.get('report_type');
    const format = formData.get('format');
    const dateRange = formData.get('date_range');
    const startDate = formData.get('start_date');
    const endDate = formData.get('end_date');

    let url = '/track/' + trackId + '/generate-report?type=' + reportType + '&format=' + format;

    if (dateRange === 'custom') {
        url += '&start_date=' + startDate + '&end_date=' + endDate;
    } else if (dateRange !== 'all') {
        const endDateCalc = new Date();
        const startDateCalc = new Date();
        startDateCalc.setDate(endDateCalc.getDate() - parseInt(dateRange));
        url += '&start_date=' + startDateCalc.toISOString().split('T')[0];
        url += '&end_date=' + endDateCalc.toISOString().split('T')[0];
    }

    // Generate report
    fetch(url, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Download the report
            window.location.href = data.download_url;

            // Close modal
            $('#reportModal' + trackId).modal('hide');

            // Reset form
            form.reset();
            document.getElementById('reportProgress' + trackId).style.display = 'none';

            // Show success message
            alert('Report generated successfully! Download starting...');
        } else {
            alert('Error generating report: ' + (data.message || 'Unknown error'));
            document.getElementById('reportProgress' + trackId).style.display = 'none';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error generating report. Please try again.');
        document.getElementById('reportProgress' + trackId).style.display = 'none';
    });
}

// Show/hide custom date range
document.querySelectorAll('select[name="date_range"]').forEach(select => {
    select.addEventListener('change', function() {
        const trackId = this.closest('.modal').id.replace('reportModal', '');
        const customDiv = document.getElementById('customDateRange' + trackId);
        if (this.value === 'custom') {
            customDiv.style.display = 'block';
        } else {
            customDiv.style.display = 'none';
        }
    });
});
</script>

<style>
.download-report-btn {
    margin: 10px 0;
    padding: 10px 20px;
    font-weight: bold;
}

.download-report-btn i {
    margin-right: 5px;
}

.radio {
    margin: 10px 0;
}

.radio label {
    font-weight: normal;
    cursor: pointer;
}

.radio label strong {
    font-weight: bold;
}

#reportProgress {
    margin-top: 20px;
}
</style>
