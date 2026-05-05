// Kích hoạt kéo chuột để cuộn ngang trên các hàng thành tựu
document.querySelectorAll('.horizontal-scroll-row').forEach(row => {
    let isDown = false;
    let startX;
    let scrollLeft;
    row.addEventListener('mousedown', (e) => {
        isDown = true;
        startX = e.pageX - row.offsetLeft;
        scrollLeft = row.scrollLeft;
    });
    row.addEventListener('mouseleave', () => { isDown = false; });
    row.addEventListener('mouseup', () => { isDown = false; });
    row.addEventListener('mousemove', (e) => {
        if (!isDown) return;
        e.preventDefault();
        const x = e.pageX - row.offsetLeft;
        const walk = (x - startX) * 1.5;
        row.scrollLeft = scrollLeft - walk;
    });
});