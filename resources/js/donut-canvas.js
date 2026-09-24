/**
 * donut-canvas.js
 * Renders all .donut-chart elements using HTML Canvas so that
 * html2canvas and the browser print engine can capture them correctly.
 * CSS conic-gradient is NOT supported by html2canvas.
 */

function drawDonutChart(el) {
    // Read values from CSS custom properties on the element's style attribute
    const style = el.getAttribute('style') || '';

    // Parse --donut-percent
    const percentMatch = style.match(/--donut-percent:\s*([\d.]+)/);
    const percent = percentMatch ? parseFloat(percentMatch[1]) : 0;

    // Parse --donut-color
    const colorMatch = style.match(/--donut-color:\s*([^;]+)/);
    const color = colorMatch ? colorMatch[1].trim() : '#3b82f6';

    // Parse --donut-track
    const trackMatch = style.match(/--donut-track:\s*([^;]+)/);
    const track = trackMatch ? trackMatch[1].trim() : '#e2e8f0';

    // Remove existing canvas if re-drawing
    const existing = el.querySelector('canvas.donut-canvas-overlay');
    if (existing) existing.remove();

    // Create canvas sized to the element
    const size = el.offsetWidth || 125;
    const canvas = document.createElement('canvas');
    canvas.className = 'donut-canvas-overlay';
    canvas.width  = size * 2; // HiDPI
    canvas.height = size * 2;
    canvas.style.cssText = `
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        border-radius: 50%;
        pointer-events: none;
    `;

    const ctx = canvas.getContext('2d');
    const cx  = canvas.width  / 2;
    const cy  = canvas.height / 2;
    const R   = (canvas.width  / 2) - 4;   // outer radius
    const r   = R * 0.78;                   // inner radius (hole)

    const startAngle = -Math.PI / 2;        // 12 o'clock
    const filled = (percent / 100) * 2 * Math.PI;

    // --- Track (background arc) ---
    ctx.beginPath();
    ctx.arc(cx, cy, R, 0, 2 * Math.PI);
    ctx.fillStyle = '#ffffff';
    ctx.fill();

    ctx.beginPath();
    ctx.moveTo(cx, cy);
    ctx.arc(cx, cy, R, 0, 2 * Math.PI);
    ctx.arc(cx, cy, r, 2 * Math.PI, 0, true);
    ctx.closePath();
    ctx.fillStyle = track;
    ctx.fill();

    // --- Filled arc ---
    if (percent > 0) {
        ctx.beginPath();
        ctx.moveTo(cx, cy);
        ctx.arc(cx, cy, R, startAngle, startAngle + filled);
        ctx.arc(cx, cy, r, startAngle + filled, startAngle, true);
        ctx.closePath();
        ctx.fillStyle = color;
        ctx.fill();
    }

    // --- White inner hole ---
    ctx.beginPath();
    ctx.arc(cx, cy, r, 0, 2 * Math.PI);
    ctx.fillStyle = '#ffffff';
    ctx.fill();

    el.appendChild(canvas);
}

function initAllDonuts(root) {
    const target = root || document;
    target.querySelectorAll('.donut-chart').forEach(el => {
        drawDonutChart(el);
    });
}

// Initial paint
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => initAllDonuts());
} else {
    initAllDonuts();
}

// Re-paint when pop-summary is loaded dynamically (fetch → innerHTML)
const resultBox = document.getElementById('resultBox');
if (resultBox) {
    const observer = new MutationObserver(() => {
        initAllDonuts(resultBox);
    });
    observer.observe(resultBox, { childList: true, subtree: true });
}

// Expose globally so export.js can call it before capturing
window.initAllDonuts = initAllDonuts;
