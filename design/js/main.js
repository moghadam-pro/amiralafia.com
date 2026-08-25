/* ============================================================
   AMIR AL AFIA REAL ESTATE — main.js
   ============================================================ */

(function () {
  'use strict';

  /* ── Page Loader ── */
  function initLoader() {
    const loader = document.getElementById('page-loader');
    if (!loader) return;
    window.addEventListener('load', function () {
      setTimeout(function () {
        loader.classList.add('loaded');
      }, 1750);
    });
  }

  /* ── Sticky Navbar shadow ── */
  function initNavScroll() {
    var nav = document.getElementById('navbar');
    if (!nav) return;
    function onScroll() {
      if (window.scrollY > 12) {
        nav.classList.add('scrolled');
      } else {
        nav.classList.remove('scrolled');
      }
    }
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
  }

  /* ── Mobile Hamburger ── */
  function initMobileMenu() {
    var btn  = document.getElementById('nav-ham');
    var menu = document.getElementById('nav-mobile');
    if (!btn || !menu) return;
    btn.addEventListener('click', function () {
      btn.classList.toggle('open');
      menu.classList.toggle('open');
    });
    menu.querySelectorAll('a').forEach(function (a) {
      a.addEventListener('click', function () {
        btn.classList.remove('open');
        menu.classList.remove('open');
      });
    });
  }

  /* ── Scroll Reveal ── */
  function initScrollReveal() {
    var els = document.querySelectorAll('.sr');
    if (!els.length) return;
    var observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          var delay = entry.target.dataset.delay || 0;
          setTimeout(function () {
            entry.target.classList.add('visible');
          }, parseInt(delay));
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.12 });
    els.forEach(function (el) {
      // Hero elements revealed after loader
      if (!el.closest('.hero-text')) {
        observer.observe(el);
      }
    });
  }

  /* ── Hero elements revealed after loader ── */
  function revealHero() {
    var heroEls = document.querySelectorAll('.hero-text .sr, .hero-collage');
    var delay = 1800;
    heroEls.forEach(function (el, i) {
      setTimeout(function () {
        el.classList.add('visible');
      }, delay + i * 80);
    });
  }

  /* ── Filter Tabs ── */
  function initFilters() {
    document.querySelectorAll('.filter-btn').forEach(function (btn) {
      btn.addEventListener('click', function () {
        var group = this.dataset.group;
        if (group) {
          document.querySelectorAll('.filter-btn[data-group="' + group + '"]').forEach(function (b) {
            b.classList.remove('active');
          });
        }
        this.classList.add('active');
      });
    });
  }

  /* ── Contact Form ── */
  function initContactForm() {
    var form   = document.getElementById('contact-form');
    var btn    = document.getElementById('cf-submit');
    var msg    = document.getElementById('cf-success');
    if (!btn || !msg) return;
    btn.addEventListener('click', function () {
      var nameInput = document.getElementById('cf-name');
      var phoneInput = document.getElementById('cf-phone');
      if (nameInput && !nameInput.value.trim()) {
        nameInput.focus();
        nameInput.style.borderColor = '#EF4444';
        setTimeout(function () { nameInput.style.borderColor = ''; }, 2000);
        return;
      }
      btn.disabled = true;
      btn.style.opacity = '0.65';
      msg.classList.add('show');
      setTimeout(function () {
        msg.classList.remove('show');
        btn.disabled = false;
        btn.style.opacity = '1';
        if (nameInput) nameInput.value = '';
        if (phoneInput) phoneInput.value = '';
      }, 4500);
    });
  }

  /* ── Smooth active nav link on scroll ── */
  function initActiveNavLinks() {
    var sections = document.querySelectorAll('section[id], div[id]');
    var navLinks = document.querySelectorAll('.nav-links a[href^="#"]');
    if (!navLinks.length) return;
    function updateActive() {
      var scrollY = window.scrollY + 100;
      sections.forEach(function (sec) {
        var top = sec.offsetTop;
        var height = sec.offsetHeight;
        var id = sec.getAttribute('id');
        if (scrollY >= top && scrollY < top + height) {
          navLinks.forEach(function (a) {
            a.classList.remove('active');
            if (a.getAttribute('href') === '#' + id) a.classList.add('active');
          });
        }
      });
    }
    window.addEventListener('scroll', updateActive, { passive: true });
  }

  /* ── Init ── */
  document.addEventListener('DOMContentLoaded', function () {
    initLoader();
    initNavScroll();
    initMobileMenu();
    initScrollReveal();
    initFilters();
    initContactForm();
    initActiveNavLinks();
    revealHero();
  });

})();
