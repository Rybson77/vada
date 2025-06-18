
  window.addEventListener('DOMContentLoaded', () => {
    const header = document.querySelector('.site-header');
    const mainContainer = document.querySelector('.site-header');
    if (header && mainContainer) {
      const headerHeight = header.offsetHeight;
      mainContainer.style.marginBottom = `${headerHeight}px`; // 10px mezera
    }
  });
