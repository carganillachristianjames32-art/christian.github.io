(function () {
  var KEY = 'portfolio_projects';
  var seed = [
    { title: 'Recipe Finder', description: 'A searchable recipe app with ingredient filters and saved favorites.', tech: 'PHP, MySQL, JavaScript', url: '' },
    { title: 'Task Board', description: 'A kanban-style task tracker with user accounts and drag-and-drop ordering.', tech: 'PHP, MySQL, CSS', url: '' },
    { title: 'Weather Dashboard', description: 'Shows a five-day forecast for any city using a public weather API.', tech: 'PHP, cURL, Chart.js', url: '' }
  ];

  function load() {
    try { var raw = localStorage.getItem(KEY); if (raw) return JSON.parse(raw); } catch (e) {}
    return seed.slice();
  }
  function save(list) { try { localStorage.setItem(KEY, JSON.stringify(list)); } catch (e) {} }
  function el(tag, cls, text) {
    var n = document.createElement(tag);
    if (cls) n.className = cls;
    if (text !== undefined) n.textContent = text;
    return n;
  }

  var page = document.body.getAttribute('data-page');

  if (page === 'index') {
    var form = document.getElementById('search-form');
    var input = document.getElementById('q');
    var listEl = document.getElementById('projects');
    var emptyEl = document.getElementById('empty');
    var countEl = document.getElementById('result-count');
    var clearEl = document.getElementById('clear');

    function render() {
      var q = input.value.trim().toLowerCase();
      var items = load().filter(function (p) {
        return !q || (p.title + ' ' + p.description + ' ' + p.tech).toLowerCase().indexOf(q) !== -1;
      });
      listEl.innerHTML = '';
      items.forEach(function (p) {
        var li = el('li');
        li.appendChild(el('h2', '', p.title));
        li.appendChild(el('p', '', p.description));
        li.appendChild(el('p', 'tech', p.tech));
        if (p.url && /^https?:\/\//i.test(p.url)) {
          var a = el('a', '', 'View project');
          a.href = p.url; a.target = '_blank'; a.rel = 'noopener noreferrer';
          li.appendChild(a);
        }
        listEl.appendChild(li);
      });
      emptyEl.hidden = items.length > 0;
      clearEl.hidden = !q;
      countEl.hidden = !q;
      countEl.textContent = items.length + ' result' + (items.length === 1 ? '' : 's') + ' for \u201C' + input.value.trim() + '\u201D';
    }
    form.addEventListener('submit', function (e) { e.preventDefault(); render(); });
    input.addEventListener('input', render);
    clearEl.addEventListener('click', function (e) { e.preventDefault(); input.value = ''; render(); });
    render();
  }

  if (page === 'admin') {
    var addForm = document.getElementById('add-form');
    var manage = document.getElementById('manage-list');
    var none = document.getElementById('none');
    var msg = document.getElementById('msg');

    function show(text, cls) { msg.className = cls; msg.textContent = text; msg.hidden = false; }

    function renderAdmin() {
      var items = load();
      manage.innerHTML = '';
      none.hidden = items.length > 0;
      items.forEach(function (p, i) {
        var li = el('li');
        var span = el('span');
        span.appendChild(el('strong', '', p.title));
        span.appendChild(document.createElement('br'));
        span.appendChild(el('small', '', p.tech));
        var btn = el('button', 'danger', 'Delete');
        btn.type = 'button';
        btn.addEventListener('click', function () {
          if (!confirm('Delete this project?')) return;
          var list = load(); list.splice(i, 1); save(list);
          renderAdmin(); show('Project deleted.', 'notice');
        });
        li.appendChild(span); li.appendChild(btn);
        manage.appendChild(li);
      });
    }

    addForm.addEventListener('submit', function (e) {
      e.preventDefault();
      var f = addForm.elements;
      var t = f.title.value.trim(), d = f.description.value.trim(), url = f.url.value.trim();
      if (!t || !d) return show('Title and description are required.', 'error');
      if (url && !/^https?:\/\//i.test(url)) return show('The link must start with http:// or https://', 'error');
      var list = load();
      list.unshift({ title: t, description: d, tech: f.tech.value.trim(), url: url });
      save(list); addForm.reset(); renderAdmin(); show('Project added.', 'notice');
    });
    renderAdmin();
  }
})();
