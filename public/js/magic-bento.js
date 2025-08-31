// Magic Bento - Laravel Version
const DEFAULT_GLOW_COLOR = "132, 0, 255";

class MagicBento {
    constructor(container, options = {}) {
        this.container = container;
        this.options = {
            enableSpotlight: true,
            enableBorderGlow: true,
            enableTilt: true,
            enableMagnetism: true,
            clickEffect: true,
            glowColor: DEFAULT_GLOW_COLOR,
            ...options
        };
        
        this.isMobile = window.innerWidth <= 768;
        this.shouldDisableAnimations = this.isMobile;
        this.spotlight = null;
        
        this.init();
    }
    
    init() {
        this.setupStyles();
        this.setupSpotlight();
        this.setupCards();
    }
    
    setupStyles() {
        const style = document.createElement('style');
        style.textContent = `
            .bento-section {
                --glow-x: 50%;
                --glow-y: 50%;
                --glow-intensity: 0;
                --glow-radius: 200px;
                --glow-color: ${this.options.glowColor};
                --border-color: #392e4e;
                --background-dark: #060010;
            }
            
            .card--border-glow::after {
                content: '';
                position: absolute;
                inset: 0;
                padding: 6px;
                background: radial-gradient(var(--glow-radius) circle at var(--glow-x) var(--glow-y),
                    rgba(${this.options.glowColor}, calc(var(--glow-intensity) * 0.8)) 0%,
                    rgba(${this.options.glowColor}, calc(var(--glow-intensity) * 0.4)) 30%,
                    transparent 60%);
                border-radius: inherit;
                mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
                mask-composite: subtract;
                -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
                -webkit-mask-composite: xor;
                pointer-events: none;
                transition: opacity 0.3s ease;
                z-index: 1;
            }
            
            .card--border-glow:hover::after {
                opacity: 1;
            }
            
            .card--border-glow:hover {
                box-shadow: 0 4px 20px rgba(46, 24, 78, 0.4), 0 0 30px rgba(${this.options.glowColor}, 0.2);
            }
        `;
        document.head.appendChild(style);
    }
    
    setupSpotlight() {
        if (!this.options.enableSpotlight || this.shouldDisableAnimations) return;
        
        this.spotlight = document.createElement("div");
        this.spotlight.className = "global-spotlight";
        this.spotlight.style.cssText = `
            position: fixed;
            width: 800px;
            height: 800px;
            border-radius: 50%;
            pointer-events: none;
            background: radial-gradient(circle,
                rgba(${this.options.glowColor}, 0.15) 0%,
                rgba(${this.options.glowColor}, 0.08) 15%,
                rgba(${this.options.glowColor}, 0.04) 25%,
                rgba(${this.options.glowColor}, 0.02) 40%,
                rgba(${this.options.glowColor}, 0.01) 65%,
                transparent 70%
            );
            z-index: 200;
            opacity: 0;
            transform: translate(-50%, -50%);
            mix-blend-mode: screen;
        `;
        document.body.appendChild(this.spotlight);
        
        this.setupSpotlightEvents();
    }
    
    setupSpotlightEvents() {
        const handleMouseMove = (e) => {
            if (!this.spotlight || !this.container) return;
            
            const rect = this.container.getBoundingClientRect();
            const mouseInside = 
                e.clientX >= rect.left &&
                e.clientX <= rect.right &&
                e.clientY >= rect.top &&
                e.clientY <= rect.bottom;
            
            const cards = this.container.querySelectorAll(".card");
            
            if (!mouseInside) {
                gsap.to(this.spotlight, {
                    opacity: 0,
                    duration: 0.3,
                    ease: "power2.out",
                });
                cards.forEach((card) => {
                    card.style.setProperty("--glow-intensity", "0");
                });
                return;
            }
            
            const proximity = 150;
            const fadeDistance = 225;
            let minDistance = Infinity;
            
            cards.forEach((card) => {
                const cardRect = card.getBoundingClientRect();
                const centerX = cardRect.left + cardRect.width / 2;
                const centerY = cardRect.top + cardRect.height / 2;
                const distance = Math.hypot(e.clientX - centerX, e.clientY - centerY) - 
                               Math.max(cardRect.width, cardRect.height) / 2;
                const effectiveDistance = Math.max(0, distance);
                
                minDistance = Math.min(minDistance, effectiveDistance);
                
                let glowIntensity = 0;
                if (effectiveDistance <= proximity) {
                    glowIntensity = 1;
                } else if (effectiveDistance <= fadeDistance) {
                    glowIntensity = (fadeDistance - effectiveDistance) / (fadeDistance - proximity);
                }
                
                this.updateCardGlowProperties(card, e.clientX, e.clientY, glowIntensity);
            });
            
            gsap.to(this.spotlight, {
                left: e.clientX,
                top: e.clientY,
                duration: 0.1,
                ease: "power2.out",
            });
            
            const targetOpacity = minDistance <= proximity ? 0.8 : 
                                minDistance <= fadeDistance ? 
                                ((fadeDistance - minDistance) / (fadeDistance - proximity)) * 0.8 : 0;
            
            gsap.to(this.spotlight, {
                opacity: targetOpacity,
                duration: targetOpacity > 0 ? 0.2 : 0.5,
                ease: "power2.out",
            });
        };
        
        document.addEventListener("mousemove", handleMouseMove);
    }
    
    setupCards() {
        const cards = this.container.querySelectorAll('.card');
        cards.forEach(card => this.setupCard(card));
    }
    
    setupCard(card) {
        if (this.shouldDisableAnimations) return;
        
        const handleMouseEnter = () => {
            if (this.options.enableTilt) {
                gsap.to(card, {
                    rotateX: 5,
                    rotateY: 5,
                    duration: 0.3,
                    ease: "power2.out",
                    transformPerspective: 1000,
                });
            }
        };
        
        const handleMouseLeave = () => {
            if (this.options.enableTilt) {
                gsap.to(card, {
                    rotateX: 0,
                    rotateY: 0,
                    duration: 0.3,
                    ease: "power2.out",
                });
            }
            
            if (this.options.enableMagnetism) {
                gsap.to(card, {
                    x: 0,
                    y: 0,
                    duration: 0.3,
                    ease: "power2.out",
                });
            }
        };
        
        const handleMouseMove = (e) => {
            if (!this.options.enableTilt && !this.options.enableMagnetism) return;
            
            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            const centerX = rect.width / 2;
            const centerY = rect.height / 2;
            
            if (this.options.enableTilt) {
                const rotateX = ((y - centerY) / centerY) * -10;
                const rotateY = ((x - centerX) / centerX) * 10;
                
                gsap.to(card, {
                    rotateX,
                    rotateY,
                    duration: 0.1,
                    ease: "power2.out",
                    transformPerspective: 1000,
                });
            }
            
            if (this.options.enableMagnetism) {
                const magnetX = (x - centerX) * 0.05;
                const magnetY = (y - centerY) * 0.05;
                
                gsap.to(card, {
                    x: magnetX,
                    y: magnetY,
                    duration: 0.3,
                    ease: "power2.out",
                });
            }
        };
        
        const handleClick = (e) => {
            if (!this.options.clickEffect) return;
            
            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            
            const maxDistance = Math.max(
                Math.hypot(x, y),
                Math.hypot(x - rect.width, y),
                Math.hypot(x, y - rect.height),
                Math.hypot(x - rect.width, y - rect.height)
            );
            
            const ripple = document.createElement("div");
            ripple.style.cssText = `
                position: absolute;
                width: ${maxDistance * 2}px;
                height: ${maxDistance * 2}px;
                border-radius: 50%;
                background: radial-gradient(circle, rgba(${this.options.glowColor}, 0.4) 0%, rgba(${this.options.glowColor}, 0.2) 30%, transparent 70%);
                left: ${x - maxDistance}px;
                top: ${y - maxDistance}px;
                pointer-events: none;
                z-index: 1000;
            `;
            
            card.appendChild(ripple);
            
            gsap.fromTo(
                ripple,
                { scale: 0, opacity: 1 },
                {
                    scale: 1,
                    opacity: 0,
                    duration: 0.8,
                    ease: "power2.out",
                    onComplete: () => ripple.remove(),
                }
            );
        };
        
        card.addEventListener("mouseenter", handleMouseEnter);
        card.addEventListener("mouseleave", handleMouseLeave);
        card.addEventListener("mousemove", handleMouseMove);
        card.addEventListener("click", handleClick);
    }
    
    updateCardGlowProperties(card, mouseX, mouseY, glow) {
        const rect = card.getBoundingClientRect();
        const relativeX = ((mouseX - rect.left) / rect.width) * 100;
        const relativeY = ((mouseY - rect.top) / rect.height) * 100;
        
        card.style.setProperty("--glow-x", `${relativeX}%`);
        card.style.setProperty("--glow-y", `${relativeY}%`);
        card.style.setProperty("--glow-intensity", glow.toString());
        card.style.setProperty("--glow-radius", "200px");
    }
}

// Global function to initialize Magic Bento
window.initMagicBento = function(containerSelector, options = {}) {
    const container = document.querySelector(containerSelector);
    if (!container) {
        console.error(`Container ${containerSelector} not found`);
        return null;
    }
    
    return new MagicBento(container, options);
};
