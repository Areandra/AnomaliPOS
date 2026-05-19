import ReactDOM from "react-dom/client";
import React from "react";
import DragLayout from "./components/DragLayout";
import QRCode from "qrcode";

window.QRCode = QRCode;

if (window.location.href.includes("tables")) {
    window.DragLayout = DragLayout;

    // Jalankan setelah DOM sepenuhnya loaded
    document.addEventListener("DOMContentLoaded", () => {
        const el = document.getElementById("table-map-react");

        if (el) {
            // Ambil props dari dataset blade
            const props = JSON.parse(el.dataset.props || "{}");
            const root = ReactDOM.createRoot(el);

            window.__tableMapRoot = root;

            root.render(
                <React.StrictMode>
                    <DragLayout {...props} />
                </React.StrictMode>,
            );
        } else {
            console.error("Element #table-map-react tidak ditemukan!");
        }
    });
}
