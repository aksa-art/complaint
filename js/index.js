// Simple grid background
window.addEventListener('resize', resizeGrid);
resizeGrid();
function resizeGrid() {
    const canvas = document.querySelector('.grid-bg');
    if (!canvas) return;
    const ctx = canvas.getContext('2d');
    const width = canvas.width;
    const height = canvas.height;
    const gridSize = 40;

    ctx.clearRect(0, 0, width, height);

    ctx.strokeStyle = 'rgba(0, 230, 255, 0.1)';
    ctx.lineWidth = 1;

    for (let x = 0; x < width; x += gridSize) {
        ctx.beginPath();
        ctx.moveTo(x, 0);
        ctx.lineTo(x, height);
        ctx.stroke();
    }

    for (let y = 0; y < height; y += gridSize) {
        ctx.beginPath();
        ctx.moveTo(0, y);
        ctx.lineTo(width, y);
        ctx.stroke();
    }
}
