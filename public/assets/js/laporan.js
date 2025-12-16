// Laporan Modal - Report Preview & Download
// Handle modal interaction, AJAX preview fetch, and download triggers

let modalLaporan = null;
let currentPeriode = 30;

document.addEventListener('DOMContentLoaded', function() {
    // Initialize modal
    modalLaporan = new bootstrap.Modal(document.getElementById('modalLaporan'));
    
    // Button open modal
    const btnOpen = document.getElementById('btnOpenLaporan');
    if (btnOpen) {
        btnOpen.addEventListener('click', function() {
            openLaporanModal();
        });
    }
    
    // Periode selector
    const periodeSelect = document.getElementById('laporanPeriodeSelect');
    if (periodeSelect) {
        periodeSelect.addEventListener('change', function() {
            currentPeriode = this.value;
            // No need to reload preview, just update the periode for download
        });
    }
    
    // Download buttons
    const btnPDF = document.getElementById('btnDownloadPDF');
    if (btnPDF) {
        btnPDF.addEventListener('click', function() {
            downloadPDF(currentPeriode);
        });
    }
    
    const btnCSV = document.getElementById('btnDownloadCSV');
    if (btnCSV) {
        btnCSV.addEventListener('click', function() {
            downloadCSV(currentPeriode);
        });
    }
});

/**
 * Open modal and show info
 */
function openLaporanModal() {
    modalLaporan.show();
    
    // Reset to default periode
    currentPeriode = 30;
    document.getElementById('laporanPeriodeSelect').value = '30';
}

// Preview loading function removed - no longer needed
// User will see static explanation text in modal

/**
 * Download PDF report
 */
function downloadPDF(periode) {
    console.log(`Downloading PDF report: periode ${periode} days`);
    
    // Show loading on button
    const btn = document.getElementById('btnDownloadPDF');
    const originalHTML = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Membuat PDF...';
    btn.disabled = true;
    
    // Open download URL in new window
    const downloadURL = `${BASE_URL}laporan/downloadPDF?periode=${periode}`;
    window.open(downloadURL, '_blank');
    
    // Reset button after 2 seconds
    setTimeout(() => {
        btn.innerHTML = originalHTML;
        btn.disabled = false;
    }, 2000);
}

/**
 * Download CSV report
 */
function downloadCSV(periode) {
    console.log(`Downloading CSV report: periode ${periode} days`);
    
    // Show loading on button
    const btn = document.getElementById('btnDownloadCSV');
    const originalHTML = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Membuat CSV...';
    btn.disabled = true;
    
    // Create hidden iframe for download
    const iframe = document.createElement('iframe');
    iframe.style.display = 'none';
    iframe.src = `${BASE_URL}laporan/downloadCSV?periode=${periode}`;
    document.body.appendChild(iframe);
    
    // Reset button after 2 seconds
    setTimeout(() => {
        btn.innerHTML = originalHTML;
        btn.disabled = false;
        document.body.removeChild(iframe);
    }, 2000);
}
