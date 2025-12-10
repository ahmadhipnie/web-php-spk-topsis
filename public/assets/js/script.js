/**
 * Custom JavaScript for SPK Saham TOPSIS
 * =======================================
 */

// Document Ready
document.addEventListener("DOMContentLoaded", function () {
  console.log("SPK Saham TOPSIS - Ready!");

  // Auto hide alerts after 5 seconds
  const alerts = document.querySelectorAll(".alert");
  alerts.forEach((alert) => {
    setTimeout(() => {
      const bsAlert = new bootstrap.Alert(alert);
      bsAlert.close();
    }, 5000);
  });
});

/**
 * Konfirmasi hapus data
 * @param {string} message - Pesan konfirmasi
 * @returns {boolean}
 */
function confirmDelete(
  message = "Apakah Anda yakin ingin menghapus data ini?"
) {
  return confirm(message);
}

/**
 * Format number sebagai currency (IDR)
 * @param {number} amount - Jumlah yang akan diformat
 * @returns {string}
 */
function formatCurrency(amount) {
  return new Intl.NumberFormat("id-ID", {
    style: "currency",
    currency: "IDR",
    minimumFractionDigits: 0,
  }).format(amount);
}

/**
 * Format number dengan separator ribuan
 * @param {number} num - Angka yang akan diformat
 * @returns {string}
 */
function formatNumber(num) {
  return new Intl.NumberFormat("id-ID").format(num);
}

/**
 * Validasi form sebelum submit
 * @param {string} formId - ID form yang akan divalidasi
 * @returns {boolean}
 */
function validateForm(formId) {
  const form = document.getElementById(formId);
  if (!form.checkValidity()) {
    form.classList.add("was-validated");
    return false;
  }
  return true;
}

/**
 * Show loading spinner
 */
function showLoading() {
  const loadingHtml = `
        <div class="spinner-container">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    `;
  // Implementasi sesuai kebutuhan
}

/**
 * Hide loading spinner
 */
function hideLoading() {
  // Implementasi sesuai kebutuhan
}

/**
 * Search/Filter table
 * @param {string} inputId - ID input search
 * @param {string} tableId - ID table yang akan difilter
 */
function searchTable(inputId, tableId) {
  const input = document.getElementById(inputId);
  const filter = input.value.toLowerCase();
  const table = document.getElementById(tableId);
  const rows = table.getElementsByTagName("tr");

  for (let i = 1; i < rows.length; i++) {
    const row = rows[i];
    const cells = row.getElementsByTagName("td");
    let found = false;

    for (let j = 0; j < cells.length; j++) {
      const cell = cells[j];
      if (cell.textContent.toLowerCase().indexOf(filter) > -1) {
        found = true;
        break;
      }
    }

    row.style.display = found ? "" : "none";
  }
}
