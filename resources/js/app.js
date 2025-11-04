import './bootstrap';

import Alpine from 'alpinejs';
import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import Lenis from '@studio-freight/lenis';

// Register GSAP plugins
gsap.registerPlugin(ScrollTrigger);

// Make GSAP available globally
window.gsap = gsap;
window.ScrollTrigger = ScrollTrigger;

window.Alpine = Alpine;
Alpine.start();

// Initialize Lenis Smooth Scroll
const lenis = new Lenis({
    duration: 1,
    easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
    direction: 'vertical',
    gestureDirection: 'vertical',
    smooth: true,
    mouseMultiplier: 1,
    smoothTouch: false,
    touchMultiplier: 2,
    infinite: false,
});

// Make lenis available globally
window.lenis = lenis;

// Lenis animation frame
function raf(time) {
    lenis.raf(time);
    requestAnimationFrame(raf);
}

requestAnimationFrame(raf);

// Connect Lenis with GSAP ScrollTrigger - PROPER INTEGRATION
lenis.on('scroll', (e) => {
    ScrollTrigger.update();
});

// Update ScrollTrigger on Lenis scroll
gsap.ticker.add((time) => {
    lenis.raf(time * 1000);
});

// Disable lag smoothing for better sync
gsap.ticker.lagSmoothing(0);

// Tell ScrollTrigger to use Lenis scroller
ScrollTrigger.defaults({
    scroller: "body"
});

// Initialize animations after everything is loaded
window.addEventListener('load', () => {
    // Wait for images and fonts to load
    setTimeout(() => {
        initGSAPAnimations();
        ScrollTrigger.refresh();
    }, 200);
});

// Also refresh on resize
window.addEventListener('resize', () => {
    ScrollTrigger.refresh();
});

function initGSAPAnimations() {
    // Check if elements exist before animating
    const heroBadge = document.querySelector('.hero-badge');
    const heroTitle = document.querySelector('.hero-title');
    const heroDescription = document.querySelector('.hero-description');
    const heroButtons = document.querySelectorAll('.hero-buttons .btn-primary, .hero-buttons .btn-outline');
    const decorationCircles = document.querySelectorAll('.decoration-circle');

    // Hero Section Animations
    if (heroBadge || heroTitle || heroDescription) {
        const heroTimeline = gsap.timeline({ 
            defaults: { ease: 'power3.out' },
            delay: 0.2
        });
        
        if (heroBadge) {
            heroTimeline.from('.hero-badge', {
                y: -30,
                opacity: 0,
                duration: 0.8,
            });
        }
        
        if (heroTitle) {
            heroTimeline.from('.hero-title', {
                y: 50,
                opacity: 0,
                duration: 1,
            }, '-=0.4');
        }
        
        if (heroDescription) {
            heroTimeline.from('.hero-description', {
                y: 30,
                opacity: 0,
                duration: 0.8
            }, '-=0.6');
        }
        
        if (heroButtons.length > 0) {
            heroTimeline.from('.hero-buttons .btn-primary, .hero-buttons .btn-outline', {
                y: 20,
                opacity: 0,
                duration: 0.6,
                stagger: 0.2
            }, '-=0.4');
        }
        
        if (decorationCircles.length > 0) {
            heroTimeline.from('.decoration-circle', {
                scale: 0,
                opacity: 0,
                duration: 1.2,
                stagger: 0.3
            }, '-=0.8');
        }
    }

    // Feature Cards Animation
    const featureCards = document.querySelectorAll('.feature-card');
    if (featureCards.length > 0) {
        featureCards.forEach((card, index) => {
            gsap.from(card, {
                scrollTrigger: {
                    trigger: card,
                    start: 'top 90%',
                    end: 'bottom 10%',
                    toggleActions: 'play none none reverse',
                    // markers: true, // Uncomment for debugging
                },
                y: 60,
                opacity: 0,
                duration: 0.8,
                delay: index * 0.15,
                ease: 'power3.out'
            });
        });
    }

    // Section Headers Animation
    const sectionHeaders = document.querySelectorAll('.section-header');
    sectionHeaders.forEach((header, index) => {
        gsap.from(header, {
            scrollTrigger: {
                trigger: header,
                start: 'top 90%',
                end: 'bottom 10%',
                toggleActions: 'play none none reverse',
                // markers: true, // Uncomment for debugging
            },
            y: 50,
            opacity: 0,
            duration: 1,
            ease: 'power3.out'
        });
    });

    // Product Cards Animation - Individual triggers for better performance
    const productCards = document.querySelectorAll('.product-card');
    if (productCards.length > 0) {
        productCards.forEach((card, index) => {
            gsap.from(card, {
                scrollTrigger: {
                    trigger: card,
                    start: 'top 95%',
                    end: 'bottom 5%',
                    toggleActions: 'play none none reverse',
                    // markers: true, // Uncomment for debugging
                },
                y: 50,
                opacity: 0,
                scale: 0.95,
                duration: 0.6,
                ease: 'power2.out'
            });
        });
    }

    // Contact Cards Animation
    const contactCards = document.querySelectorAll('.contact-card');
    if (contactCards.length > 0) {
        contactCards.forEach((card, index) => {
            gsap.from(card, {
                scrollTrigger: {
                    trigger: card,
                    start: 'top 90%',
                    end: 'bottom 10%',
                    toggleActions: 'play none none reverse',
                    // markers: true, // Uncomment for debugging
                },
                y: 50,
                opacity: 0,
                scale: 0.9,
                duration: 0.8,
                delay: index * 0.2,
                ease: 'back.out(1.4)'
            });
        });
    }

    // Floating Animation for Hero Elements
    if (decorationCircles.length > 0) {
        const circle1 = document.querySelector('.decoration-circle:first-child');
        const circle2 = document.querySelector('.decoration-circle:last-child');
        
        if (circle1) {
            gsap.to(circle1, {
                y: 30,
                x: -20,
                duration: 4,
                repeat: -1,
                yoyo: true,
                ease: 'sine.inOut'
            });
        }
        
        if (circle2) {
            gsap.to(circle2, {
                y: -30,
                x: 20,
                duration: 3.5,
                repeat: -1,
                yoyo: true,
                ease: 'sine.inOut'
            });
        }
    }

    // Parallax effect for hero background
    const heroBackground = document.querySelector('.hero-background-image');
    if (heroBackground) {
        gsap.to('.hero-background-image', {
            scrollTrigger: {
                trigger: '.hero-section',
                start: 'top top',
                end: 'bottom top',
                scrub: 1
            },
            y: '30%',
            ease: 'none'
        });
    }

    // Smooth fade-in for category sections
    const categorySections = document.querySelectorAll('.category-section');
    categorySections.forEach((section, index) => {
        gsap.from(section, {
            scrollTrigger: {
                trigger: section,
                start: 'top 90%',
                end: 'bottom 10%',
                toggleActions: 'play none none reverse',
                // markers: true, // Uncomment for debugging
            },
            opacity: 0,
            y: 40,
            duration: 1,
            ease: 'power2.out'
        });
    });

    // Animated SVG Path Drawing - Menu Section
    const animatedPaths = document.querySelectorAll('.animated-path');
    if (animatedPaths.length > 0) {
        animatedPaths.forEach((path, index) => {
            gsap.fromTo(path, 
                {
                    strokeDashoffset: 1000,
                    opacity: 0
                },
                {
                    scrollTrigger: {
                        trigger: '.menu-section',
                        start: 'top 80%',
                        end: 'top 30%',
                        scrub: 1,
                        // markers: true, // Uncomment for debugging
                    },
                    strokeDashoffset: 0,
                    opacity: 0.6,
                    duration: 2,
                    delay: index * 0.2,
                    ease: 'none'
                }
            );
        });
    }

    // Menu Header Animation
    const menuHeaderSection = document.querySelector('.menu-header-section');
    if (menuHeaderSection) {
        gsap.from('.menu-header-section .section-label', {
            scrollTrigger: {
                trigger: menuHeaderSection,
                start: 'top 85%',
                toggleActions: 'play none none reverse',
            },
            y: 30,
            opacity: 0,
            duration: 0.8,
            ease: 'power3.out'
        });

        gsap.from('.menu-header-section .menu-main-title', {
            scrollTrigger: {
                trigger: menuHeaderSection,
                start: 'top 85%',
                toggleActions: 'play none none reverse',
            },
            y: 50,
            opacity: 0,
            duration: 1,
            delay: 0.2,
            ease: 'power3.out'
        });

        gsap.from('.menu-header-section .menu-main-description', {
            scrollTrigger: {
                trigger: menuHeaderSection,
                start: 'top 85%',
                toggleActions: 'play none none reverse',
            },
            y: 30,
            opacity: 0,
            duration: 0.8,
            delay: 0.4,
            ease: 'power3.out'
        });
    }

    // Category Header Modern Animation
    const categoryHeaders = document.querySelectorAll('.category-header-modern');
    if (categoryHeaders.length > 0) {
        categoryHeaders.forEach((header, index) => {
            gsap.from(header, {
                scrollTrigger: {
                    trigger: header,
                    start: 'top 92%',
                    toggleActions: 'play none none reverse',
                },
                x: -50,
                opacity: 0,
                duration: 0.8,
                ease: 'power2.out'
            });
        });
    }

    // Floating SVG Circles Animation
    const floatingCircles = document.querySelectorAll('.floating-circle');
    if (floatingCircles.length > 0) {
        floatingCircles.forEach((circle, index) => {
            gsap.to(circle, {
                y: '+=30',
                x: '+=20',
                duration: 4 + index,
                repeat: -1,
                yoyo: true,
                ease: 'sine.inOut',
                delay: index * 0.5
            });
        });
    }

    // Navbar blur effect on scroll
    const navbar = document.querySelector('nav');
    if (navbar) {
        ScrollTrigger.create({
            start: 'top -100',
            end: 'max',
            onUpdate: (self) => {
                const blur = 20 + Math.min(self.progress * 10, 10);
                navbar.style.backdropFilter = `blur(${blur}px)`;
            }
        });
    }

    // Force ScrollTrigger refresh after all animations are set
    setTimeout(() => {
        ScrollTrigger.refresh(true);
    }, 100);

    // Log for debugging
    console.log('✨ GSAP Animations Initialized');
    console.log('📊 ScrollTriggers:', ScrollTrigger.getAll().length);
}

// Handle anchor link smooth scrolling with Lenis
document.addEventListener('click', (e) => {
    const target = e.target.closest('a[href^="#"]');
    if (target) {
        e.preventDefault();
        const targetId = target.getAttribute('href');
        const targetElement = document.querySelector(targetId);
        if (targetElement) {
            lenis.scrollTo(targetElement, {
                offset: -100,
                duration: 1.5,
                easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
                onComplete: () => {
                    // Refresh ScrollTrigger after scroll
                    ScrollTrigger.refresh();
                }
            });
        }
    }
});

// Expose refresh function globally for debugging
window.refreshAnimations = () => {
    ScrollTrigger.refresh(true);
    console.log('🔄 ScrollTriggers refreshed');
};
