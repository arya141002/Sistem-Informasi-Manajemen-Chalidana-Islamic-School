@extends('layouts.layout')
@section('content')
    <style>
        .id-wrap { display: grid; grid-template-columns: 1fr 300px; gap: 1.25rem; }
        .id-panel { border: 1px solid #e4e7ec; border-radius: .9rem; background: #fff; padding: 1rem; }
        .id-branches { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: .9rem; }
        .id-branch { border: 1px solid #e4e7ec; border-radius: 1rem; padding: .9rem 1rem; cursor: pointer; background: linear-gradient(180deg, #f7f9ff, #ffffff); box-shadow: 0 2px 6px rgba(16,24,40,.06); }
        .id-branch.active { border-color: #696cff; box-shadow: 0 4px 10px rgba(105,108,255,.18); }
        .id-branch-name { font-weight: 700; }
        .id-branch-sub { color:#667085; font-size:.85rem; }

        .id-cal-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:.75rem; }
        .id-cal-title { font-weight: 700; }
        .id-cal-grid { display:grid; grid-template-columns: repeat(7, 1fr); gap: .35rem; }
        .id-cal-cell { border: 1px solid #eef1f5; border-radius: .6rem; padding: .5rem; min-height: 80px; cursor: pointer; background:#fff; transition: box-shadow .15s, border-color .15s; }
        .id-cal-cell:hover { border-color:#d0d5ff; box-shadow: 0 2px 8px rgba(105,108,255,.1); }
        .id-cal-cell.muted { background:#fafafa; color:#9aa0a6; }
        .id-cal-cell.has-event { background:#1f3b8f; border-color:#1f3b8f; color:#fff; }
        .id-cal-cell.red-date { background:#fff5f5; border-color:#fecaca; }
        .id-cal-cell.red-date.has-event { background:#fff1f2; border-color:#fb7185; color:#111827; }
        .id-cal-cell.pick { outline: 2px dashed #ffb020; outline-offset: -2px; }
        .id-mini-cal { border:1px solid #eef1f5; border-radius:.6rem; padding:.6rem; background:#fff; }
        .id-mini-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:.5rem; }
        .id-mini-grid { display:grid; grid-template-columns: repeat(7, 1fr); gap:.25rem; }
        .id-mini-cell { border:1px solid #eef1f5; border-radius:.4rem; padding:.35rem; text-align:center; cursor:pointer; font-size:.8rem; }
        .id-mini-sun { color:#e11d48; }
        .id-mini-cell.muted { color:#9aa0a6; background:#fafafa; }
        .id-mini-cell.selected { background:#1f3b8f; color:#fff; border-color:#1f3b8f; }
        .id-cal-cell.selected { border-color:#3b5cff; box-shadow: 0 0 0 2px #3b5cff22; }
        .id-cal-date { font-size:.85rem; font-weight:700; }
        .id-cal-sun { color:#e11d48; }
        .id-cal-events { margin-top:.35rem; display:flex; flex-direction:column; gap:.2rem; }
        .id-cal-event { font-size:.72rem; line-height:1.1; padding:.12rem .3rem; border-radius:.35rem; background:#eef1ff; color:#3b5cff; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
        .id-cal-more { font-size:.7rem; color:#667085; }
        .id-cal-cell.today { border-color:#ffb020; box-shadow: inset 0 0 0 1px #ffb02055; }
        .id-cal-dot { width:6px; height:6px; border-radius:50%; background:#696cff; display:inline-block; margin-top:.35rem; }
        .id-side-title { font-weight:700; margin-bottom:.5rem; }
        .id-date-card { border:1px solid #eef1f5; border-radius:.7rem; padding:.75rem .9rem; background:#fff; }
        .id-date-title { font-weight:700; }
        .id-date-sub { color:#667085; font-size:.85rem; }
        .id-event { border:1px solid #eef1f5; border-radius:.6rem; padding:.6rem .7rem; margin-bottom:.5rem; }
        .id-event-title { font-weight:600; font-size:.9rem; }
        .id-event-desc { font-size:.85rem; color:#667085; }
        @media (max-width: 992px) {
            .id-wrap { grid-template-columns: 1fr; }
            .id-branches { grid-template-columns: 1fr; }
        }
    </style>

    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light"><i class="bx bx-chevron-right bx-xs"></i></span> {{ $title }}
        </h4>

        <div class="id-panel mb-3">
            <div class="mb-2 fw-semibold">Pilih Cabang</div>
            <div class="id-branches" id="branch-list"></div>
        </div>

        <div class="id-wrap">
            <div class="id-panel">
                <div class="id-cal-header">
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="btn-prev">Prev</button>
                    <div class="id-cal-title" id="cal-title"></div>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="btn-next">Next</button>
                </div>
                <div class="id-cal-grid" id="cal-weekdays"></div>
                <div class="id-cal-grid mt-2" id="cal-grid"></div>
            </div>
            <div class="id-panel">
                <div class="id-side-title">Detail Tanggal</div>
                <div class="id-date-card mb-3" id="selected-date-card">
                    <div class="id-date-title">Pilih tanggal</div>
                    <div class="id-date-sub">Klik tanggal di kalender</div>
                </div>
                <div id="event-list"></div>
                @auth
                <button type="button" class="btn btn-primary btn-sm mt-2" id="btn-add">Add Event</button>
                @endauth
            </div>
        </div>
    </div>

    <div class="modal fade" id="eventModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-simple">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title">Add Event</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body py-3 py-md-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Tanggal (Single)</label>
                            <input type="date" class="form-control" id="event-date">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Title</label>
                            <input type="text" class="form-control" id="event-title">
                        </div>
                        <div class="col-md-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="multi-mode">
                                <label class="form-check-label" for="multi-mode">Pilih banyak tanggal dari kalender</label>
                            </div>
                            <div class="mt-2">
                                <div class="text-muted small mb-1">Tanggal terpilih</div>
                                <div id="multi-selected" class="d-flex flex-wrap gap-1"></div>
                                <button type="button" class="btn btn-xs btn-outline-secondary mt-2" id="btn-clear-multi">Clear</button>
                            </div>
                            <div class="mt-3 id-mini-cal" id="multi-calendar" style="display:none;">
                                <div class="id-mini-header">
                                    <button type="button" class="btn btn-xs btn-outline-secondary" id="mini-prev">Prev</button>
                                    <div class="small fw-semibold" id="mini-title"></div>
                                    <button type="button" class="btn btn-xs btn-outline-secondary" id="mini-next">Next</button>
                                </div>
                                <div class="id-mini-grid" id="mini-weekdays"></div>
                                <div class="id-mini-grid mt-1" id="mini-grid"></div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" id="event-desc" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="btn-save">Save</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const campuses = @json($campuses);
        const program = @json($program);
        const weekdays = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
        const calWeekdays = document.getElementById('cal-weekdays');
        const calGrid = document.getElementById('cal-grid');
        const calTitle = document.getElementById('cal-title');
        const eventList = document.getElementById('event-list');
        const selectedDateCard = document.getElementById('selected-date-card');
        const branchList = document.getElementById('branch-list');
        const btnAdd = document.getElementById('btn-add');
        const miniCal = document.getElementById('multi-calendar');
        const miniGrid = document.getElementById('mini-grid');
        const miniTitle = document.getElementById('mini-title');
        const miniWeekdays = document.getElementById('mini-weekdays');
        const miniPrev = document.getElementById('mini-prev');
        const miniNext = document.getElementById('mini-next');

        weekdays.forEach(d => {
            const el = document.createElement('div');
            el.className = 'id-cal-date text-center' + (d === 'Sun' ? ' id-cal-sun' : '');
            el.textContent = d;
            calWeekdays.appendChild(el);
        });
        weekdays.forEach(d => {
            const el = document.createElement('div');
            el.className = 'id-cal-date text-center' + (d === 'Sun' ? ' id-cal-sun' : '');
            el.textContent = d;
            miniWeekdays.appendChild(el);
        });

        let activeCampus = campuses.length ? campuses[0] : null;
        let current = new Date();
        let eventsByDate = {};
        let selectedDate = null;
        let multiSelected = new Set();
        const multiMode = document.getElementById('multi-mode');
        const multiSelectedEl = document.getElementById('multi-selected');
        const btnClearMulti = document.getElementById('btn-clear-multi');
        let miniCurrent = new Date();

        function renderBranches() {
            branchList.innerHTML = '';
            if (!campuses.length) {
                branchList.innerHTML = '<div class="text-muted">Belum ada campus untuk program ini.</div>';
                return;
            }
            campuses.forEach((c) => {
                const el = document.createElement('div');
                el.className = 'id-branch' + (activeCampus && activeCampus.id === c.id ? ' active' : '');
                el.innerHTML = `<div class="id-branch-name">${c.title}</div>`;
                el.addEventListener('click', () => {
                    activeCampus = c;
                    renderBranches();
                    loadEvents();
                });
                branchList.appendChild(el);
            });
        }

        function formatDateKey(d) {
            const y = d.getFullYear();
            const m = String(d.getMonth() + 1).padStart(2, '0');
            const day = String(d.getDate()).padStart(2, '0');
            return `${y}-${m}-${day}`;
        }

        function renderCalendar() {
            calGrid.innerHTML = '';
            const year = current.getFullYear();
            const month = current.getMonth();
            const first = new Date(year, month, 1);
            const startDay = (first.getDay() + 6) % 7;
            const daysInMonth = new Date(year, month + 1, 0).getDate();
            const prevDays = new Date(year, month, 0).getDate();

            calTitle.textContent = first.toLocaleDateString('en-US', { month: 'long', year: 'numeric' });

            for (let i = 0; i < startDay; i++) {
                const d = new Date(year, month - 1, prevDays - startDay + i + 1);
                const cell = buildCell(d, true);
                calGrid.appendChild(cell);
            }

            for (let d = 1; d <= daysInMonth; d++) {
                const date = new Date(year, month, d);
                const cell = buildCell(date, false);
                calGrid.appendChild(cell);
            }

            const cellsNeeded = 42 - calGrid.children.length;
            for (let i = 1; i <= cellsNeeded; i++) {
                const d = new Date(year, month + 1, i);
                const cell = buildCell(d, true);
                calGrid.appendChild(cell);
            }
        }

        function buildCell(date, muted) {
            const key = formatDateKey(date);
            const dayEvents = eventsByDate[key] || [];
            const hasEvent = dayEvents.length;
            const cell = document.createElement('div');
            const isSelected = selectedDate === key;
            const today = formatDateKey(new Date());
            const isPicked = multiSelected.has(key);
            const isSunday = date.getDay() === 0;
            const isRedDate = isSunday || dayEvents.some((event) => event.is_holiday);
            cell.className = 'id-cal-cell' + (muted ? ' muted' : '') + (hasEvent ? ' has-event' : '') + (isRedDate ? ' red-date' : '') + (isSelected ? ' selected' : '') + (isPicked ? ' pick' : '') + (key === today ? ' today' : '');
            cell.innerHTML = `<div class="id-cal-date${isSunday ? ' id-cal-sun' : ''}">${date.getDate()}</div>`;
            cell.addEventListener('click', () => {
                if (multiMode && multiMode.checked) {
                    if (multiSelected.has(key)) {
                        multiSelected.delete(key);
                    } else {
                        multiSelected.add(key);
                    }
                    renderMultiSelected();
                }
                selectedDate = key;
                updateSelectedCard();
                renderEventList();
                if (btnAdd) {
                    document.getElementById('event-date').value = key;
                }
                renderCalendar();
            });
            return cell;
        }

        function renderEventList() {
            const items = selectedDate ? (eventsByDate[selectedDate] || []) : [];
            eventList.innerHTML = '';
            if (!items.length) {
                eventList.innerHTML = '<div class="text-muted">No events</div>';
                return;
            }
            items.forEach((e) => {
                const el = document.createElement('div');
                el.className = 'id-event';
                el.innerHTML = `<div class="id-event-title">${e.title}</div>` +
                    (e.description ? `<div class="id-event-desc">${e.description}</div>` : '');
                eventList.appendChild(el);
            });
        }

        async function loadEvents() {
            eventsByDate = {};
            renderCalendar();
            renderEventList();
            if (!activeCampus) return;
            const year = current.getFullYear();
            const month = current.getMonth() + 1;
            const res = await fetch(`{{ route('important-dates.events') }}?campus_id=${activeCampus.id}&year=${year}&month=${month}`);
            const data = await res.json();
            data.forEach((e) => {
                const startKey = (e.start_date || e.event_date || '').toString().slice(0, 10);
                const endKey = (e.end_date || e.event_date || startKey).toString().slice(0, 10);
                if (!startKey) {
                    return;
                }

                enumerateRange(startKey, endKey).forEach((dateKey) => {
                    if (!eventsByDate[dateKey]) eventsByDate[dateKey] = [];
                    eventsByDate[dateKey].push(e);
                });
            });
            if (!selectedDate || !selectedDate.startsWith(`${year}-${String(month).padStart(2, '0')}`)) {
                const today = new Date();
                const todayKey = formatDateKey(today);
                if (today.getFullYear() === year && (today.getMonth() + 1) === month) {
                    selectedDate = todayKey;
                } else {
                    selectedDate = formatDateKey(new Date(year, month - 1, 1));
                }
                updateSelectedCard();
            }
            renderCalendar();
            renderEventList();
        }

        document.getElementById('btn-prev').addEventListener('click', () => {
            current = new Date(current.getFullYear(), current.getMonth() - 1, 1);
            loadEvents();
        });
        document.getElementById('btn-next').addEventListener('click', () => {
            current = new Date(current.getFullYear(), current.getMonth() + 1, 1);
            loadEvents();
        });

        if (btnAdd) {
            btnAdd.addEventListener('click', () => {
                if (!activeCampus || !selectedDate) return;
                const modal = new bootstrap.Modal(document.getElementById('eventModal'));
                document.getElementById('event-date').value = selectedDate;
                if (multiMode) multiMode.checked = false;
                multiSelected.clear();
                renderMultiSelected();
                if (miniCal) miniCal.style.display = 'none';
                document.getElementById('event-title').value = '';
                document.getElementById('event-desc').value = '';
                modal.show();
            });

            document.getElementById('btn-save').addEventListener('click', async () => {
                const payload = {
                    campus_id: activeCampus ? activeCampus.id : null,
                    event_date: document.getElementById('event-date').value,
                    dates: multiSelected.size ? Array.from(multiSelected) : null,
                    title: document.getElementById('event-title').value,
                    description: document.getElementById('event-desc').value
                };
                const res = await fetch('{{ route('important-dates.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(payload)
                });
                if (res.ok) {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('eventModal'));
                    modal.hide();
                    loadEvents();
                }
            });
        }

        renderBranches();
        loadEvents();

        function updateSelectedCard() {
            if (!selectedDateCard) return;
            if (!selectedDate) {
                selectedDateCard.innerHTML = '<div class="id-date-title">Pilih tanggal</div><div class="id-date-sub">Klik tanggal di kalender</div>';
                return;
            }
            const d = new Date(selectedDate + 'T00:00:00');
            const label = d.toLocaleDateString('en-US', { weekday: 'long', day: '2-digit', month: 'long', year: 'numeric' });
            const count = (eventsByDate[selectedDate] || []).length;
            const titles = (eventsByDate[selectedDate] || []).map((e) => e.title).join(', ');
            selectedDateCard.innerHTML = `<div class="id-date-title">${label}</div><div class="id-date-sub">${count} event</div>` +
                (titles ? `<div class="id-date-sub mt-1">${titles}</div>` : '');
        }

        function renderMultiSelected() {
            if (!multiSelectedEl) return;
            multiSelectedEl.innerHTML = '';
            if (!multiSelected.size) {
                multiSelectedEl.innerHTML = '<span class="text-muted small">Belum ada tanggal dipilih</span>';
                return;
            }
            Array.from(multiSelected).sort().forEach((d) => {
                const badge = document.createElement('span');
                badge.className = 'badge bg-label-primary';
                badge.textContent = d;
                multiSelectedEl.appendChild(badge);
            });
        }

        function enumerateRange(start, end) {
            const dates = [];
            const cursor = new Date(start + 'T00:00:00');
            const finalDate = new Date(end + 'T00:00:00');

            while (cursor <= finalDate) {
                dates.push(formatDateKey(cursor));
                cursor.setDate(cursor.getDate() + 1);
            }

            return dates;
        }

        if (btnClearMulti) {
            btnClearMulti.addEventListener('click', () => {
                multiSelected.clear();
                renderMultiSelected();
                renderCalendar();
            });
        }

        if (multiMode) {
            multiMode.addEventListener('change', () => {
                if (!miniCal) return;
                miniCal.style.display = multiMode.checked ? 'block' : 'none';
                if (multiMode.checked) {
                    miniCurrent = new Date(current.getFullYear(), current.getMonth(), 1);
                    renderMiniCalendar();
                }
            });
        }

        function renderMiniCalendar() {
            if (!miniGrid || !miniTitle) return;
            miniGrid.innerHTML = '';
            const year = miniCurrent.getFullYear();
            const month = miniCurrent.getMonth();
            const first = new Date(year, month, 1);
            const startDay = (first.getDay() + 6) % 7;
            const daysInMonth = new Date(year, month + 1, 0).getDate();
            const prevDays = new Date(year, month, 0).getDate();
            miniTitle.textContent = first.toLocaleDateString('en-US', { month: 'long', year: 'numeric' });

            for (let i = 0; i < startDay; i++) {
                const d = new Date(year, month - 1, prevDays - startDay + i + 1);
                miniGrid.appendChild(buildMiniCell(d, true));
            }
            for (let d = 1; d <= daysInMonth; d++) {
                miniGrid.appendChild(buildMiniCell(new Date(year, month, d), false));
            }
            const cellsNeeded = 42 - miniGrid.children.length;
            for (let i = 1; i <= cellsNeeded; i++) {
                miniGrid.appendChild(buildMiniCell(new Date(year, month + 1, i), true));
            }
        }

        function buildMiniCell(date, muted) {
            const key = formatDateKey(date);
            const cell = document.createElement('div');
            const isSunday = date.getDay() === 0;
            cell.className = 'id-mini-cell' + (muted ? ' muted' : '') + (multiSelected.has(key) ? ' selected' : '');
            cell.innerHTML = `<span class="${isSunday ? 'id-mini-sun' : ''}">${date.getDate()}</span>`;
            cell.addEventListener('click', () => {
                if (multiSelected.has(key)) {
                    multiSelected.delete(key);
                } else {
                    multiSelected.add(key);
                }
                renderMultiSelected();
                renderMiniCalendar();
                renderCalendar();
            });
            return cell;
        }

        if (miniPrev) {
            miniPrev.addEventListener('click', () => {
                miniCurrent = new Date(miniCurrent.getFullYear(), miniCurrent.getMonth() - 1, 1);
                renderMiniCalendar();
            });
        }
        if (miniNext) {
            miniNext.addEventListener('click', () => {
                miniCurrent = new Date(miniCurrent.getFullYear(), miniCurrent.getMonth() + 1, 1);
                renderMiniCalendar();
            });
        }
    </script>
@endsection
