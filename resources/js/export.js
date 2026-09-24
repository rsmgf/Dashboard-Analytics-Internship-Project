import html2canvas from 'html2canvas';
import { jsPDF } from 'jspdf';

/* ─────────────────────────────────────────────────────────────────────────────
 * HELPERS
 * ─────────────────────────────────────────────────────────────────────────── */

/**
 * Convert every <canvas.donut-canvas-overlay> inside `root` to an <img>.
 * Must be called on the ORIGINAL element BEFORE cloning, so we can read
 * the canvas pixel data (cross-origin canvas would throw after clone).
 *
 * @param {HTMLElement} originalRoot  – the live DOM element
 * @param {HTMLElement} cloneRoot     – its cloneNode(true) counterpart
 */
function replaceCanvasesWithImages(originalRoot, cloneRoot) {
    const origCanvases  = [...originalRoot.querySelectorAll('canvas.donut-canvas-overlay')];
    const cloneCanvases = [...cloneRoot.querySelectorAll('canvas.donut-canvas-overlay')];

    origCanvases.forEach((origCanvas, idx) => {
        const cloneCanvas = cloneCanvases[idx];
        if (!cloneCanvas) return;

        const img = document.createElement('img');
        try {
            img.src = origCanvas.toDataURL('image/png');
        } catch (_) {
            return; // tainted canvas – skip
        }
        img.style.cssText = `
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            pointer-events: none;
        `;
        cloneCanvas.replaceWith(img);
    });
}

/**
 * Re-draw donut charts on a cloned element (for html2canvas onclone).
 * Reads CSS custom properties from the `style` attribute.
 *
 * @param {HTMLElement} cloneRoot
 */
function redrawDonuts(cloneRoot) {
    cloneRoot.querySelectorAll('.donut-chart').forEach(el => {
        const style = el.getAttribute('style') || '';

        const percentMatch = style.match(/--donut-percent:\s*([\d.]+)/);
        const percent      = percentMatch ? parseFloat(percentMatch[1]) : 0;

        const colorMatch = style.match(/--donut-color:\s*([^;]+)/);
        const color      = colorMatch ? colorMatch[1].trim() : '#3b82f6';

        const trackMatch = style.match(/--donut-track:\s*([^;]+)/);
        const track      = trackMatch ? trackMatch[1].trim() : '#e2e8f0';

        // Remove existing canvas overlay
        el.querySelectorAll('canvas.donut-canvas-overlay').forEach(c => c.remove());

        const size   = 250;
        const canvas = document.createElement('canvas');
        canvas.width  = size;
        canvas.height = size;
        canvas.style.cssText = `
            position: absolute; inset: 0;
            width: 100%; height: 100%;
            border-radius: 50%; pointer-events: none;
        `;

        const ctx  = canvas.getContext('2d');
        const cx   = size / 2;
        const cy   = size / 2;
        const R    = size / 2 - 4;
        const r    = R * 0.78;
        const start  = -Math.PI / 2;
        const filled = (percent / 100) * 2 * Math.PI;

        // White base
        ctx.beginPath();
        ctx.arc(cx, cy, R, 0, 2 * Math.PI);
        ctx.fillStyle = '#ffffff';
        ctx.fill();

        // Track arc
        ctx.beginPath();
        ctx.moveTo(cx, cy);
        ctx.arc(cx, cy, R, 0, 2 * Math.PI);
        ctx.arc(cx, cy, r, 2 * Math.PI, 0, true);
        ctx.closePath();
        ctx.fillStyle = track;
        ctx.fill();

        // Filled arc
        if (percent > 0) {
            ctx.beginPath();
            ctx.moveTo(cx, cy);
            ctx.arc(cx, cy, R, start, start + filled);
            ctx.arc(cx, cy, r, start + filled, start, true);
            ctx.closePath();
            ctx.fillStyle = color;
            ctx.fill();
        }

        // White hole
        ctx.beginPath();
        ctx.arc(cx, cy, r, 0, 2 * Math.PI);
        ctx.fillStyle = '#ffffff';
        ctx.fill();

        el.appendChild(canvas);
    });
}

/**
 * Flatten all carousel tracks in a CLONED element so every card is visible
 * (no overflow hidden, no scroll). Adds a neat flex-wrap grid layout.
 * Also removes carousel arrows, dots, and export menus.
 *
 * @param {HTMLElement} clone
 */
function flattenCarousels(clone) {
    // Remove UI chrome that should not appear in exports
    clone.querySelectorAll(
        '.export-menu-wrapper, .export-dropdown, ' +
        '.dashboard-carousel-arrow, .dashboard-carousel-dots, ' +
        '.donut-detail-btn'
    ).forEach(el => el.remove());

    // Flatten every carousel wrapper → remove scroll/overflow constraints
    clone.querySelectorAll('.dashboard-carousel-wrapper').forEach(wrapper => {
        wrapper.style.cssText = `
            overflow: visible !important;
            display: block !important;
            position: relative !important;
        `;
    });

    // Flatten every carousel track → flex-wrap so cards wrap to next line
    clone.querySelectorAll('.dashboard-carousel-track').forEach(track => {
        track.style.cssText = `
            display: flex !important;
            flex-wrap: wrap !important;
            overflow: visible !important;
            width: 100% !important;
            height: auto !important;
            transform: none !important;
            scroll-snap-type: none !important;
            gap: 16px !important;
            padding: 8px 0 !important;
        `;
    });

    // Make device cards consistent width for wrap layout
    clone.querySelectorAll('.donut-device-card, .battery-bank-card').forEach(card => {
        card.style.cssText = (card.style.cssText || '') + `
            display: block !important;
            opacity: 1 !important;
            visibility: visible !important;
            position: relative !important;
            flex-shrink: 0 !important;
            min-width: calc(50% - 8px) !important;
            max-width: calc(50% - 8px) !important;
            flex: 1 1 calc(50% - 8px) !important;
        `;
    });

    // Make rectifier group slides take full width so inner carousels wrap properly
    clone.querySelectorAll('.rectifier-group-slide').forEach(slide => {
        slide.style.cssText = (slide.style.cssText || '') + `
            display: block !important;
            opacity: 1 !important;
            visibility: visible !important;
            position: relative !important;
            width: 100% !important;
            max-width: 100% !important;
            flex: 0 0 100% !important;
            margin-bottom: 24px !important;
            padding: 0 !important;
        `;
    });

    // Ensure accordion tables hidden by Alpine (x-show) remain hidden
    clone.querySelectorAll('[x-show]').forEach(el => {
        if (el.style.display === 'none') {
            el.style.setProperty('display', 'none', 'important');
        }
    });
}

/**
 * Collect all stylesheet <link> and inline <style> tags from the current document.
 * Returns a string of HTML tags ready to inject into a print window.
 */
function collectStylesheets() {
    const links = [...document.querySelectorAll('link[rel="stylesheet"]')]
        .map(l => `<link rel="stylesheet" href="${l.href}">`)
        .join('\n');

    const styles = [...document.querySelectorAll('style')]
        .map(s => `<style>${s.innerHTML}</style>`)
        .join('\n');

    return links + '\n' + styles;
}

/* ─────────────────────────────────────────────────────────────────────────────
 * PUBLIC API
 * ─────────────────────────────────────────────────────────────────────────── */

/**
 * Export an element as a PNG or JPEG image download.
 * All carousel slides are flattened so hidden cards appear.
 */
window.exportImage = async function (elementId, format = 'png', filename = 'chart') {
    const element = document.getElementById(elementId);
    if (!element) return;

    element.classList.add('exporting-mode');

    // Make sure all donut charts are drawn on the live element first
    if (window.initAllDonuts) window.initAllDonuts(element);
    await new Promise(r => setTimeout(r, 100));

    try {
        const canvas = await html2canvas(element, {
            scale: 2,
            useCORS: true,
            allowTaint: true,
            backgroundColor: '#ffffff',
            logging: false,
            scrollX: 0,
            scrollY: 0,
            onclone: (_doc, clone) => {
                // 1. Flatten carousels so all cards are visible
                flattenCarousels(clone);
                // 2. Re-draw donuts in cloned doc (CSS vars not inherited by html2canvas)
                redrawDonuts(clone);
            },
        });

        const image = canvas.toDataURL(`image/${format}`, 1.0);
        const link  = document.createElement('a');
        link.href     = image;
        link.download = `${filename}.${format}`;
        link.click();
    } catch (error) {
        console.error('Export Image failed', error);
        alert('Gagal mengekspor gambar. Silakan coba lagi.');
    } finally {
        element.classList.remove('exporting-mode');
    }
};

/**
 * Export an element as a PDF download.
 * All carousel slides are flattened so hidden cards appear.
 * Long content is split across multiple PDF pages automatically.
 */
window.exportPDF = async function (elementId, filename = 'document') {
    const element = document.getElementById(elementId);
    if (!element) return;

    element.classList.add('exporting-mode');
    if (window.initAllDonuts) window.initAllDonuts(element);
    await new Promise(r => setTimeout(r, 100));

    try {
        const isLandscape = false;
        const pdf = new jsPDF(isLandscape ? 'l' : 'p', 'mm', 'a4');
        const pdfW = pdf.internal.pageSize.getWidth();
        const pdfH = pdf.internal.pageSize.getHeight();

        const isFullPage = elementId === 'pop-summary-page' || elementId === 'dashboard-content';
        const isSection = elementId.startsWith('section-');

        if (isFullPage || isSection) {
            // --- Paginated Export for Sections/Pages ---
            const clone = element.cloneNode(true);
            flattenCarousels(clone);
            redrawDonuts(clone);
            replaceCanvasesWithImages(element, clone);

            // Create an offscreen container
            const container = document.createElement('div');
            container.style.cssText = `
                position: fixed;
                top: 0;
                left: -9999px;
                width: 1100px; /* Fixed width to ensure 2x2 grid looks good */
                background: #ffffff;
                z-index: -1000;
                padding: 20px;
            `;
            container.appendChild(clone);
            document.body.appendChild(container);

            const views = [];
            const sections = isFullPage ? Array.from(clone.querySelectorAll('.dashboard-module-section')) : [clone];

            sections.forEach(sec => {
                const isBattery = sec.id === 'section-battery' || sec.querySelector('.rectifier-group-slide');
                
                if (isBattery) {
                    const slides = Array.from(sec.querySelectorAll('.rectifier-group-slide'));
                    if (slides.length === 0) {
                        views.push(() => {
                            sections.forEach(s => s.style.display = 'none');
                            sec.style.display = 'block';
                        });
                        return;
                    }

                    slides.forEach(slide => {
                        const cards = Array.from(slide.querySelectorAll('.battery-bank-card'));
                        if (cards.length === 0) return;
                        
                        // Chunk by max 4 cards per page
                        const chunks = [];
                        for (let i = 0; i < cards.length; i += 4) {
                            chunks.push(cards.slice(i, i + 4));
                        }
                        
                        chunks.forEach(chunk => {
                            views.push(() => {
                                sections.forEach(s => s.style.display = 'none');
                                sec.style.display = 'block';
                                
                                slides.forEach(sl => sl.style.display = 'none');
                                slide.style.display = 'block';
                                
                                cards.forEach(c => c.style.display = 'none');
                                chunk.forEach(c => c.style.display = 'block');
                            });
                        });
                    });
                } else {
                    const cards = Array.from(sec.querySelectorAll('.donut-device-card:not(.battery-bank-card)'));
                    if (cards.length === 0) {
                        views.push(() => {
                            sections.forEach(s => s.style.display = 'none');
                            sec.style.display = 'block';
                        });
                        return;
                    }

                    const chunks = [];
                    for (let i = 0; i < cards.length; i += 4) {
                        chunks.push(cards.slice(i, i + 4));
                    }
                    
                    chunks.forEach(chunk => {
                        views.push(() => {
                            sections.forEach(s => s.style.display = 'none');
                            sec.style.display = 'block';
                            
                            cards.forEach(c => c.style.display = 'none');
                            chunk.forEach(c => c.style.display = 'block');
                        });
                    });
                }
            });

            if (views.length === 0) {
                views.push(() => {}); // Fallback
            }

            for (let i = 0; i < views.length; i++) {
                views[i](); // Apply visibility

                const canvas = await html2canvas(container, {
                    scale: 2,
                    useCORS: true,
                    allowTaint: true,
                    backgroundColor: '#ffffff',
                    logging: false
                });

                const imgData = canvas.toDataURL('image/png');
                const imgW = pdfW;
                const imgH = (canvas.height * pdfW) / canvas.width;

                if (i > 0) pdf.addPage();
                pdf.addImage(imgData, 'PNG', 0, 0, imgW, imgH);
            }

            document.body.removeChild(container);
            pdf.save(`${filename}.pdf`);

        } else {
            // --- Single Card Export ---
            const canvas = await html2canvas(element, {
                scale: 2,
                useCORS: true,
                allowTaint: true,
                backgroundColor: '#ffffff',
                logging: false,
                scrollX: 0,
                scrollY: 0,
                onclone: (_doc, cloneDoc) => {
                    flattenCarousels(cloneDoc);
                    redrawDonuts(cloneDoc);
                },
            });

            const imgData = canvas.toDataURL('image/png');
            const imgW  = pdfW;
            const imgH  = (canvas.height * pdfW) / canvas.width;

            if (imgH <= pdfH) {
                pdf.addImage(imgData, 'PNG', 0, 0, imgW, imgH);
            } else {
                const pageCount = Math.ceil(imgH / pdfH);
                for (let i = 0; i < pageCount; i++) {
                    if (i > 0) pdf.addPage();
                    pdf.addImage(imgData, 'PNG', 0, -(i * pdfH), imgW, imgH);
                }
            }

            pdf.save(`${filename}.pdf`);
        }
    } catch (error) {
        console.error('Export PDF failed', error);
        alert('Gagal mengekspor PDF. Silakan coba lagi.');
    } finally {
        element.classList.remove('exporting-mode');
    }
};

/**
 * Print a specific element.
 * Uses window.open() popup (more reliable than an off-screen iframe).
 * Carousels are flattened so every card is visible in the print output.
 */
window.printArea = function (elementId) {
    const element = document.getElementById(elementId);
    if (!element) return;

    // Ensure donuts are drawn before cloning
    if (window.initAllDonuts) window.initAllDonuts(element);

    // ── Clone & prepare ────────────────────────────────────────────────────
    const clone = element.cloneNode(true);

    // Flatten carousels (removes arrows/dots/export buttons too)
    flattenCarousels(clone);

    // Convert live canvas elements → <img> (canvas pixels don't survive cloning)
    replaceCanvasesWithImages(element, clone);

    // ── Build print HTML ───────────────────────────────────────────────────
    const stylesheets = collectStylesheets();

    const html = `<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Print – ${elementId}</title>
    ${stylesheets}
    <style>
        /* ── Print-specific overrides ── */
        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        body {
            margin: 0;
            padding: 16px;
            background: #ffffff;
            font-family: 'Poppins', system-ui, sans-serif;
        }

        /* Flatten carousels for print */
        .dashboard-carousel-wrapper {
            overflow: visible !important;
            display: block !important;
        }
        .dashboard-carousel-track {
            display: flex !important;
            flex-wrap: wrap !important;
            overflow: visible !important;
            width: auto !important;
            height: auto !important;
            transform: none !important;
            gap: 16px !important;
            padding: 8px 0 !important;
        }
        .donut-device-card,
        .battery-bank-card,
        .rectifier-group-slide {
            display: block !important;
            opacity: 1 !important;
            visibility: visible !important;
            min-width: 260px !important;
            max-width: 340px !important;
            flex-shrink: 0 !important;
            box-shadow: none !important;
            border: 1px solid #e2e8f0 !important;
            break-inside: avoid !important;
            page-break-inside: avoid !important;
        }

        /* Hide UI chrome */
        .export-menu-wrapper,
        .export-dropdown,
        .dashboard-carousel-arrow,
        .dashboard-carousel-dots,
        .donut-detail-btn {
            display: none !important;
        }

        /* Donut chart sizing for print */
        .donut-chart {
            position: relative !important;
        }
        .donut-chart img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            border-radius: 50%;
        }

        @media print {
            body { padding: 8px; }
            .dashboard-module-section { margin-bottom: 24px; }
        }
    </style>
</head>
<body>
    ${clone.outerHTML}
    <script>
        window.addEventListener('load', function () {
            // Small delay so images/fonts are fully loaded
            setTimeout(function () {
                window.print();
                setTimeout(function () { window.close(); }, 1000);
            }, 400);
        });
    <\/script>
</body>
</html>`;

    // ── Open popup & write HTML ────────────────────────────────────────────
    const printWin = window.open('', '_blank', 'width=1200,height=900,scrollbars=yes');
    if (!printWin) {
        alert('Popup diblokir oleh browser. Harap izinkan popup untuk fitur print.');
        return;
    }

    printWin.document.open();
    printWin.document.write(html);
    printWin.document.close();
};
