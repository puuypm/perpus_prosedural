  // Fungsi skrip untuk menampilkan atau menyembunyikan input jumlah kolom
  function toggleColumnInput(show) {
    var columnInput = document.getElementById('columnInput');
    var numColumns = document.getElementById('num_columns');
    columnInput.style.display = show ? 'block' : 'none';
    if (!show) {
        numColumns.value = 0;
        clearColumnInputs();
    }
}

// Fungsi untuk membersihkan input kolom
function clearColumnInputs() {
    var columnInputsContainer = document.getElementById('columnInputsContainer');
    columnInputsContainer.innerHTML = '';
}

// Fungsi untuk membuat input bertype number sesuai dengan jumlah kolom yang dimasukkan
function generateColumnInputs() {
    var numColumns = document.getElementById('num_columns').value;
    var columnInputsContainer = document.getElementById('columnInputsContainer');

    // Bersihkan input yang ada sebelumnya
    columnInputsContainer.innerHTML = '';

    for (var i = 0; i < numColumns; i++) {
        var inputWrapper = document.createElement('div');
        inputWrapper.className = 'column-input-wrapper';
        inputWrapper.innerHTML = `
            <label for="column${i + 1}">Column ${i + 1}</label>
            <input type="text" id="column${i + 1}" name="column${i + 1}" class="form-control" required>
        `;
        columnInputsContainer.appendChild(inputWrapper);
    }
}

document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('num_columns').addEventListener('input', generateColumnInputs);
});