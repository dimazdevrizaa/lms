let currentSlide = 1;
const totalSlides = 6;

function showSlide(n) {
    document.querySelectorAll('.slide').forEach(s => s.classList.remove('active'));
    const target = document.getElementById('slide-' + n);
    if (target) {
        target.classList.add('active');
        currentSlide = n;
    }
    document.getElementById('slideCounter').textContent = currentSlide + ' / ' + totalSlides;
    document.getElementById('progressFill').style.width = ((currentSlide / totalSlides) * 100) + '%';
    document.getElementById('prevBtn').disabled = currentSlide === 1;
    document.getElementById('nextBtn').disabled = currentSlide === totalSlides;
}

function changeSlide(dir) {
    const next = currentSlide + dir;
    if (next >= 1 && next <= totalSlides) showSlide(next);
}

function toggleFullscreen() {
    if (!document.fullscreenElement) {
        document.documentElement.requestFullscreen();
    } else {
        document.exitFullscreen();
    }
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'ArrowRight' || e.key === 'ArrowDown' || e.key === ' ') {
        e.preventDefault();
        changeSlide(1);
    } else if (e.key === 'ArrowLeft' || e.key === 'ArrowUp') {
        e.preventDefault();
        changeSlide(-1);
    } else if (e.key === 'f' || e.key === 'F') {
        toggleFullscreen();
    }
}); 

showSlide(1);
