// Laporan Modal - Report Preview & Download
// Handle modal interaction, AJAX preview fetch, and download triggers

let modalLaporan = null;
let currentPeriode = 30;

document.addEventListener("DOMContentLoaded", function () {
  // Initialize modal
  modalLaporan = new bootstrap.Modal(document.getElementById("modalLaporan"));

  // Button open modal
  const btnOpen = document.getElementById("btnOpenLaporan");
  if (btnOpen) {
    btnOpen.addEventListener("click", function () {
      openLaporanModal();
    });
  }

  // Periode selector
  const periodeSelect = document.getElementById("laporanPeriodeSelect");
  if (periodeSelect) {
    periodeSelect.addEventListener("change", function () {
      currentPeriode = this.value;
      // No need to reload preview, just update the periode for download
    });
  }

  // Download buttons
  const btnPDF = document.getElementById("btnDownloadPDF");
  if (btnPDF) {
    btnPDF.addEventListener("click", function () {
      downloadPDF(currentPeriode);
    });
  }

  const btnCSV = document.getElementById("btnDownloadCSV");
  if (btnCSV) {
    btnCSV.addEventListener("click", function () {
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
  document.getElementById("laporanPeriodeSelect").value = "30";
}

// Preview loading function removed - no longer needed
// User will see static explanation text in modal

/**
 * Download PDF report
 */
function downloadPDF(periode) {
  console.log(`Downloading PDF report: periode ${periode} days`);

  // Validate BASE_URL exists
  if (typeof BASE_URL === "undefined") {
    console.error("BASE_URL is not defined!");
    alert("Error: BASE_URL tidak terdefinisi. Silakan refresh halaman.");
    return;
  }

  // Show loading on button
  const btn = document.getElementById("btnDownloadPDF");
  if (!btn) {
    console.error("Button btnDownloadPDF not found!");
    return;
  }

  const originalHTML = btn.innerHTML;
  btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Membuat PDF...';
  btn.disabled = true;

  try {
    // Open download URL in new window
    const downloadURL = `${BASE_URL}laporan/downloadPDF?periode=${periode}`;
    console.log("Download URL:", downloadURL);
    window.open(downloadURL, "_blank");

    // Reset button after 2 seconds
    setTimeout(() => {
      btn.innerHTML = originalHTML;
      btn.disabled = false;
    }, 2000);
  } catch (error) {
    console.error("Download error:", error);
    btn.innerHTML = originalHTML;
    btn.disabled = false;
    alert("Gagal mengunduh PDF: " + error.message);
  }
}

/**
 * Download CSV report
 */
function downloadCSV(periode) {
  console.log(`Downloading CSV report: periode ${periode} days`);

  // Validate BASE_URL exists
  if (typeof BASE_URL === "undefined") {
    console.error("BASE_URL is not defined!");
    alert("Error: BASE_URL tidak terdefinisi. Silakan refresh halaman.");
    return;
  }

  // Show loading on button
  const btn = document.getElementById("btnDownloadCSV");
  if (!btn) {
    console.error("Button btnDownloadCSV not found!");
    return;
  }

  const originalHTML = btn.innerHTML;
  btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Membuat CSV...';
  btn.disabled = true;

  try {
    // Create download URL
    const downloadURL = `${BASE_URL}laporan/downloadCSV?periode=${periode}`;
    console.log("Download URL:", downloadURL);

    // Use direct window.location for CSV download (simpler and more reliable)
    window.location.href = downloadURL;

    // Reset button after 2 seconds
    setTimeout(() => {
      btn.innerHTML = originalHTML;
      btn.disabled = false;
    }, 2000);
  } catch (error) {
    console.error("Download error:", error);
    btn.innerHTML = originalHTML;
    btn.disabled = false;
    alert("Gagal mengunduh CSV: " + error.message);
  }
}
