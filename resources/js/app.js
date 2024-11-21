import "./bootstrap";

document.addEventListener("DOMContentLoaded", function () {
    // Menggunakan Bootstrap 5 Alert dismiss
    const alertList = document.querySelectorAll(".alert");
    alertList.forEach(function (alert) {
        new bootstrap.Alert(alert);
    });
});

document.addEventListener("DOMContentLoaded", function () {
    // Auto close alert after 5 seconds
    if (document.getElementById("alert-success")) {
        setTimeout(function () {
            document.getElementById("alert-success").remove();
        }, 5000);
    }
});
