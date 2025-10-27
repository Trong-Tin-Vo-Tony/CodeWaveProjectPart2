document.addEventListener("DOMContentLoaded", () => {
  const reveals = document.querySelectorAll(".fade-up");
  const appearOptions = { threshold: 0.15 };
  const appearOnScroll = new IntersectionObserver(function(entries, observer) {
    entries.forEach(entry => {
      if (!entry.isIntersecting) return;
      entry.target.classList.add("visible");
      observer.unobserve(entry.target);
    });
  }, appearOptions);
  reveals.forEach(r => appearOnScroll.observe(r));
});
