// Source: https://philipwalton.com/articles/the-google-analytics-setup-i-use-on-every-site-i-build/#error-tracking
// addEventListener('error', window.__e = function f(e) {
//   f.q = f.q || [];
//   f.q.push(e)
// });

addEventListener('error', error => {
  if (!window.errorQueue) window.errorQueue = []
  window.errorQueue.push(error)
})
