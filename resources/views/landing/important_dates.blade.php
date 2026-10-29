@extends('layouts.front')

@section('landing')
    @php
        $importantDatesStrings = [
            'weekdays' => __('front.important_dates_weekdays'),
            'locale' => __('front.important_dates_js_locale'),
            'chooseDate' => __('front.important_dates_choose_date'),
            'chooseDateDesc' => __('front.important_dates_choose_date_desc'),
            'noDateSelected' => __('front.important_dates_no_date_selected'),
            'noAgendaOnDate' => __('front.important_dates_no_agenda_on_date'),
            'noAgendaForDate' => __('front.important_dates_no_agenda_for_date'),
            'totalFound' => __('front.important_dates_total_found', ['count' => '__COUNT__']),
            'moreLabel' => __('front.important_dates_more', ['count' => '__COUNT__']),
            'rangeLabel' => __('front.important_dates_range', ['start' => '__START__', 'end' => '__END__']),
            'unknownDate' => __('front.important_dates_unknown_date'),
        ];
    @endphp

    <div class="container-fluid bg-breadcrumb bg-breadcrumb-important-dates">
        <div class="container page-title-corner py-5 page-title-container text-center text-lg-start">
            <h4 class="text-white display-4 mb-4 wow fadeInDown" data-wow-delay="0.1s">{{ __('front.important_dates') }}</h4>
            <ol class="breadcrumb d-flex justify-content-center justify-content-lg-start mb-0 wow fadeInDown" data-wow-delay="0.3s">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ __('front.breadcrumb_home') }}</a></li>
                <li class="breadcrumb-item active text-primary">{{ __('front.important_dates') }}</li>
            </ol>
        </div>
    </div>

    <div class="container-fluid important-dates-page py-5">
        <div class="container py-5">
            <div class="important-dates-shell wow fadeInUp" data-wow-delay="0.2s">
                <div class="row g-4 align-items-end">
                    <div class="col-lg-8">
                        <span class="important-dates-kicker">{{ __('front.important_dates_kicker') }}</span>
                        <h1 class="display-5 mt-3 mb-3">{{ __('front.important_dates_page_title') }}</h1>
                        <p class="mb-0 important-dates-intro">{{ __('front.important_dates_page_desc') }}</p>
                    </div>
                    <div class="col-lg-4">
                        <div class="important-dates-campus-filter" id="importantDatesCampusFilter">
                            <button type="button" class="important-dates-campus-pill is-active" data-campus-id="">
                                {{ __('front.important_dates_all_campuses') }}
                            </button>
                            @foreach($campuses as $campus)
                                <button type="button" class="important-dates-campus-pill" data-campus-id="{{ $campus->id }}">
                                    {{ $campus->title }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4 mt-1">
                <div class="col-xl-8">
                    <section class="important-dates-calendar-card wow fadeInUp" data-wow-delay="0.25s">
                        <div class="important-dates-calendar-head">
                            <button type="button" class="btn btn-outline-primary rounded-pill px-4" id="importantDatesPrev">
                                <i class="fa fa-arrow-left me-2"></i>{{ __('front.important_dates_prev') }}
                            </button>
                            <div>
                                <p class="important-dates-label mb-1">{{ __('front.important_dates_monthly_calendar') }}</p>
                                <h2 class="mb-0" id="importantDatesTitle">Month Year</h2>
                            </div>
                            <button type="button" class="btn btn-outline-primary rounded-pill px-4" id="importantDatesNext">
                                {{ __('front.important_dates_next') }}<i class="fa fa-arrow-right ms-2"></i>
                            </button>
                        </div>

                        <div class="important-dates-weekdays" id="importantDatesWeekdays"></div>
                        <div class="important-dates-grid" id="importantDatesGrid"></div>
                    </section>
                </div>

                <div class="col-xl-4">
                    <aside class="important-dates-side-card wow fadeInUp" data-wow-delay="0.3s">
                        <div class="important-dates-side-head">
                            <p class="important-dates-label mb-1">{{ __('front.important_dates_date_detail') }}</p>
                            <h3 class="mb-0" id="importantDatesSelectedTitle">{{ __('front.important_dates_choose_date') }}</h3>
                        </div>
                        <p class="important-dates-side-subtitle" id="importantDatesSelectedMeta">
                            {{ __('front.important_dates_choose_date_desc') }}
                        </p>
                        <div class="important-dates-event-list" id="importantDatesEventList">
                            <div class="important-dates-empty">
                                {{ __('front.important_dates_no_date_selected') }}
                            </div>
                        </div>
                    </aside>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const copy = @json($importantDatesStrings);
            const weekdays = copy.weekdays;
            const grid = document.getElementById('importantDatesGrid');
            const weekdaysRow = document.getElementById('importantDatesWeekdays');
            const title = document.getElementById('importantDatesTitle');
            const selectedTitle = document.getElementById('importantDatesSelectedTitle');
            const selectedMeta = document.getElementById('importantDatesSelectedMeta');
            const eventList = document.getElementById('importantDatesEventList');
            const prevButton = document.getElementById('importantDatesPrev');
            const nextButton = document.getElementById('importantDatesNext');
            const campusButtons = Array.from(document.querySelectorAll('[data-campus-id]'));

            let current = new Date();
            let selectedDate = null;
            let selectedCampusId = '';
            let events = [];
            let eventsByDate = {};

            weekdays.forEach(function (day, index) {
                const el = document.createElement('div');
                el.className = 'important-dates-weekday' + (index === 6 ? ' is-red-date' : '');
                el.textContent = day;
                weekdaysRow.appendChild(el);
            });

            function formatDateKey(date) {
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                return year + '-' + month + '-' + day;
            }

            function formatLongDate(dateString) {
                const date = new Date(dateString + 'T00:00:00');
                return date.toLocaleDateString(copy.locale, {
                    weekday: 'long',
                    day: '2-digit',
                    month: 'long',
                    year: 'numeric'
                });
            }

            function formatMonthTitle(date) {
                return date.toLocaleDateString(copy.locale, {
                    month: 'long',
                    year: 'numeric'
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

            function buildEventMap() {
                eventsByDate = {};

                events.forEach(function (event) {
                    const startDate = event.start_date || event.event_date;
                    const endDate = event.end_date || event.event_date || startDate;

                    enumerateRange(startDate, endDate).forEach(function (dateKey) {
                        if (!eventsByDate[dateKey]) {
                            eventsByDate[dateKey] = [];
                        }

                        eventsByDate[dateKey].push(event);
                    });
                });
            }

            function renderSidePanel() {
                if (!selectedDate) {
                    selectedTitle.textContent = copy.chooseDate;
                    selectedMeta.textContent = copy.chooseDateDesc;
                    eventList.innerHTML = `<div class="important-dates-empty">${copy.noDateSelected}</div>`;
                    return;
                }

                const dayEvents = eventsByDate[selectedDate] || [];
                selectedTitle.textContent = formatLongDate(selectedDate);
                selectedMeta.textContent = dayEvents.length
                    ? copy.totalFound.replace('__COUNT__', dayEvents.length)
                    : copy.noAgendaOnDate;

                if (!dayEvents.length) {
                    eventList.innerHTML = `<div class="important-dates-empty">${copy.noAgendaForDate}</div>`;
                    return;
                }

                eventList.innerHTML = dayEvents.map(function (event) {
                    const dateLabel = event.start_date && event.end_date && event.start_date !== event.end_date
                        ? copy.rangeLabel.replace('__START__', event.start_date).replace('__END__', event.end_date)
                        : (event.event_date || event.start_date || copy.unknownDate);

                    const meta = [event.campus, event.branch].filter(Boolean).join(' • ');

                    return `
                        <article class="important-dates-event-item">
                            <span class="important-dates-event-date">${dateLabel}</span>
                            <h4>${event.title}</h4>
                            ${meta ? `<p class="important-dates-event-meta">${meta}</p>` : ''}
                            ${event.description ? `<p class="important-dates-event-desc">${event.description}</p>` : ''}
                        </article>
                    `;
                }).join('');
            }

            function renderCalendar() {
                grid.innerHTML = '';

                const year = current.getFullYear();
                const month = current.getMonth();
                const firstDay = new Date(year, month, 1);
                const startOffset = (firstDay.getDay() + 6) % 7;
                const daysInMonth = new Date(year, month + 1, 0).getDate();
                const prevMonthDays = new Date(year, month, 0).getDate();

                title.textContent = formatMonthTitle(firstDay);

                function appendEmptyCell() {
                    const cell = document.createElement('div');
                    cell.className = 'important-dates-cell is-empty';
                    grid.appendChild(cell);
                }

                function appendCell(date) {
                    const dateKey = formatDateKey(date);
                    const dayEvents = eventsByDate[dateKey] || [];
                    const isRedDate = date.getDay() === 0 || dayEvents.some(function (event) {
                        return event.is_holiday;
                    });
                    const cell = document.createElement('button');
                    cell.type = 'button';
                    cell.className = 'important-dates-cell'
                        + (dayEvents.length ? ' has-event' : '')
                        + (isRedDate ? ' is-red-date' : '')
                        + (selectedDate === dateKey ? ' is-selected' : '');

                    cell.innerHTML = `
                        <span class="important-dates-cell-date">${date.getDate()}</span>
                        <span class="important-dates-cell-content">
                            ${dayEvents.length ? `<span class="important-dates-cell-dot${dayEvents.some(function (event) { return event.is_holiday; }) ? ' is-holiday' : ''}"></span>` : ''}
                        </span>
                    `;

                    cell.addEventListener('click', function () {
                        selectedDate = dateKey;
                        renderCalendar();
                        renderSidePanel();
                    });

                    grid.appendChild(cell);
                }

                for (let i = 0; i < startOffset; i++) {
                    appendEmptyCell();
                }

                for (let day = 1; day <= daysInMonth; day++) {
                    appendCell(new Date(year, month, day));
                }

                const totalCells = grid.children.length;
                for (let day = 1; day <= 42 - totalCells; day++) {
                    appendEmptyCell();
                }
            }

            async function loadEvents() {
                const params = new URLSearchParams({
                    year: current.getFullYear(),
                    month: current.getMonth() + 1
                });

                if (selectedCampusId) {
                    params.append('campus_id', selectedCampusId);
                }

                const response = await fetch('{{ route('landing.important-dates.events') }}?' + params.toString());
                events = await response.json();
                buildEventMap();

                const today = new Date();
                const currentMonthKey = String(current.getMonth() + 1).padStart(2, '0');
                const todayKey = formatDateKey(today);
                const fallbackDate = `${current.getFullYear()}-${currentMonthKey}-01`;

                if (!selectedDate || !selectedDate.startsWith(`${current.getFullYear()}-${currentMonthKey}`)) {
                    selectedDate = today.getFullYear() === current.getFullYear() && today.getMonth() === current.getMonth()
                        ? todayKey
                        : fallbackDate;
                }

                renderCalendar();
                renderSidePanel();
            }

            prevButton.addEventListener('click', function () {
                current = new Date(current.getFullYear(), current.getMonth() - 1, 1);
                loadEvents();
            });

            nextButton.addEventListener('click', function () {
                current = new Date(current.getFullYear(), current.getMonth() + 1, 1);
                loadEvents();
            });

            campusButtons.forEach(function (button) {
                button.addEventListener('click', function () {
                    selectedCampusId = button.dataset.campusId;

                    campusButtons.forEach(function (item) {
                        item.classList.remove('is-active');
                    });

                    button.classList.add('is-active');
                    loadEvents();
                });
            });

            loadEvents();
        });
    </script>
@endsection
