document.addEventListener('DOMContentLoaded', () => {
  const exclusive = false;

  document.querySelectorAll('.faq-question').forEach(button => {
    button.addEventListener('click', () => {
      const item = button.closest('.faq-item');
      item.classList.toggle('open');

      if (exclusive) {
        document.querySelectorAll('.faq-item').forEach(other => {
          if (other !== item) other.classList.remove('open');
        });
      }
    });
  });
});