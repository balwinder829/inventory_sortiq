<canvas id="bubbleCanvas" class="fixed inset-0 -z-10 pointer-events-none w-full h-full bg-[#F8FAFC]"></canvas>
<script>
(function() {
    const canvas = document.getElementById('bubbleCanvas');
    if (!canvas) return;
    const ctx = canvas.getContext('2d');
    let width = canvas.width = window.innerWidth;
    let height = canvas.height = window.innerHeight;
    const mouse = { x: null, y: null, radius: 140 };

    window.addEventListener('resize', () => {
        width = canvas.width = window.innerWidth;
        height = canvas.height = window.innerHeight;
    });

    window.addEventListener('mousemove', (e) => {
        mouse.x = e.clientX;
        mouse.y = e.clientY;
    });

    window.addEventListener('mouseleave', () => {
        mouse.x = null;
        mouse.y = null;
    });

    const labels = ["SKU", "RFID", "QTY", "BOX", "API", "GPS", "FLOW", "SAFE"];
    const particles = [];
    const count = 14;

    class Particle {
        constructor() {
            this.x = Math.random() * width;
            this.y = Math.random() * height;
            this.size = Math.random() * 18 + 10;
            this.speedX = (Math.random() - 0.5) * 0.3;
            this.speedY = (Math.random() - 0.5) * 0.3;
            this.label = labels[Math.floor(Math.random() * labels.length)];
            const isBlue = Math.random() > 0.5;
            this.gradientColor = isBlue ? ["rgba(40,116,240,0.14)", "rgba(40,116,240,0.03)"] : ["rgba(249,115,22,0.12)", "rgba(249,115,22,0.03)"];
            this.borderColor = isBlue ? "rgba(40,116,240,0.20)" : "rgba(249,115,22,0.16)";
        }
        update() {
            this.x += this.speedX;
            this.y += this.speedY;
            if (this.x < -this.size) this.x = width + this.size;
            if (this.x > width + this.size) this.x = -this.size;
            if (this.y < -this.size) this.y = height + this.size;
            if (this.y > height + this.size) this.y = -this.size;

            if (mouse.x !== null && mouse.y !== null) {
                const dx = this.x - mouse.x;
                const dy = this.y - mouse.y;
                const distance = Math.hypot(dx, dy);
                if (distance < mouse.radius) {
                    const force = (mouse.radius - distance) / mouse.radius;
                    const angle = Math.atan2(dy, dx);
                    this.x += Math.cos(angle) * force * 2;
                    this.y += Math.sin(angle) * force * 2;
                }
            }
        }
        draw() {
            const gradient = ctx.createRadialGradient(this.x, this.y, 2, this.x, this.y, this.size);
            gradient.addColorStop(0, this.gradientColor[0]);
            gradient.addColorStop(1, this.gradientColor[1]);
            ctx.beginPath();
            ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
            ctx.fillStyle = gradient;
            ctx.fill();
            ctx.lineWidth = 1;
            ctx.strokeStyle = this.borderColor;
            ctx.stroke();

            ctx.beginPath();
            ctx.arc(this.x - this.size / 4, this.y - this.size / 4, this.size / 6, 0, Math.PI * 2);
            ctx.fillStyle = "rgba(255,255,255,0.25)";
            ctx.fill();

            ctx.fillStyle = "rgba(71,85,105,0.45)";
            ctx.font = "600 8px Inter, sans-serif";
            ctx.textAlign = "center";
            ctx.textBaseline = "middle";
            ctx.fillText(this.label, this.x, this.y);
        }
    }

    for (let i = 0; i < count; i++) {
        particles.push(new Particle());
    }

    function animate() {
        ctx.clearRect(0, 0, width, height);
        particles.forEach(p => { p.update(); p.draw(); });
        requestAnimationFrame(animate);
    }
    animate();
})();
</script>
