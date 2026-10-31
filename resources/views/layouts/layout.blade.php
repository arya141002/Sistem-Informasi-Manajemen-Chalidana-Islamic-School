<!DOCTYPE html>
<html
  lang="en"
  class="light-style layout-menu-fixed"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="{{ asset('assets') }}/"
  data-template="vertical-menu-template-free"
>
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />

    @php
      $seoTitle = trim(get_system('site_title', config('app.name')));
      $seoDescription = trim(get_system('site_description', 'Website resmi ' . config('app.name')));
      $seoKeywords = trim(get_system('site_keywords', config('app.name')));
      $seoImage = asset(get_system_file('site_favicon', 'assets/images/icon/CIS_Logo.webp'));
      $canonicalUrl = url()->current();
    @endphp

    <title>{{ $seoTitle }}</title>

    <meta name="description" content="{{ $seoDescription }}" />
    <meta name="keywords" content="{{ $seoKeywords }}" />
    <meta name="robots" content="index,follow" />
    <link rel="canonical" href="{{ $canonicalUrl }}" />

    <meta property="og:type" content="website" />
    <meta property="og:title" content="{{ $seoTitle }}" />
    <meta property="og:description" content="{{ $seoDescription }}" />
    <meta property="og:url" content="{{ $canonicalUrl }}" />
    <meta property="og:image" content="{{ $seoImage }}" />

    <meta name="twitter:card" content="summary" />
    <meta name="twitter:title" content="{{ $seoTitle }}" />
    <meta name="twitter:description" content="{{ $seoDescription }}" />
    <meta name="twitter:image" content="{{ $seoImage }}" />

    <!-- Favicon -->
    <link rel="icon" href="{{ get_system_file('site_favicon', 'assets/img/favicon/favicon.ico') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet"
    />

    <!-- Basic Icons -->
    <link href="https://cdn.boxicons.com/3.0.8/fonts/basic/boxicons.min.css" rel="stylesheet">
    <!-- Filled Icons -->
    <link href="https://cdn.boxicons.com/3.0.8/fonts/filled/boxicons-filled.min.css" rel="stylesheet">
    <!-- Brand Icons -->
    <link href="https://cdn.boxicons.com/3.0.8/fonts/brands/boxicons-brands.min.css" rel="stylesheet">

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/theme-default.css') }}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />

    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}" />
    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.bootstrap5.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>


    <!-- Page CSS -->
    <style>
        .admin-table-wrapper {
            overflow-x: hidden;
        }
        .admin-table-wrapper > table,
        .admin-table-wrapper .dataTables_wrapper table {
            width: 100% !important;
            table-layout: fixed;
        }
        .admin-table-wrapper table th,
        .admin-table-wrapper table td {
            max-width: 0;
            white-space: normal !important;
            overflow-wrap: anywhere;
            word-break: break-word;
            vertical-align: middle;
        }
        .admin-table-wrapper table th:last-child,
        .admin-table-wrapper table td:last-child {
            width: 96px;
            max-width: 96px;
            white-space: nowrap !important;
            word-break: normal;
            overflow-wrap: normal;
        }
        .admin-table-wrapper .dataTables_wrapper .dataTables_length,
        .admin-table-wrapper .dataTables_wrapper .dataTables_filter {
            white-space: normal;
        }
        .admin-table-wrapper .dataTables_wrapper .dataTables_filter input {
            max-width: 100%;
        }
        /* Fix z-index untuk notifikasi agar tidak tertutup navbar */
        .notifyjs-corner {
            z-index: 9999 !important;
        }
        .notifyjs-container {
            z-index: 9999 !important;
        }
        .notifyjs-wrapper {
            z-index: 9999 !important;
        }
        .rich-editor__toolbar .rich-editor__select {
            width: auto;
            min-width: 108px;
            height: 31px;
            padding: 0.25rem 1.8rem 0.25rem 0.55rem;
            font-size: 0.8rem;
            line-height: 1.3;
            color: #566a7f;
            border-color: #d9dee3;
            background-color: #fff;
        }
        .rich-editor__toolbar .rich-editor__group {
            display: inline-flex;
            gap: 0.25rem;
        }
        .rich-editor__toolbar button[data-editor-enhance] {
            min-width: 31px;
        }
    </style>

    <!-- Helpers -->
    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{ asset('assets/js/config.js') }}"></script>
  </head>

  <body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        <!-- Menu -->

        <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
          <div class="app-brand demo">
            <a href="{{ route('dashboard') }}" class="app-brand-link">
              <span class="app-brand-logo demo">
                <img src="{{ get_system_file('site_logo', 'images/icon/logo-branding-CIS-01.png') }}" alt="Chalidana Islamic School">
              </span>
            </a>

            <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
              <i class="bx bx-chevron-left bx-sm align-middle"></i>
            </a>
          </div>

          <div class="menu-inner-shadow"></div>

          <ul class="menu-inner py-1">
            @foreach ($sidebarMenus as $menu)
                {{-- Cek apakah menu punya anak (Submenu) --}}
                @php
                    $hasChildren = $menu->childrenRecursive->count() > 0;
                    $activeClass = Request::is(ltrim($menu->url, '/') . '*') ? 'active' : '';
                    $openClass = ($hasChildren && $activeClass) ? 'open' : '';
                @endphp

                <li class="menu-item {{ $activeClass }} {{ $openClass }}">
                    <a href="{{ $hasChildren ? 'javascript:void(0);' : url($menu->url) }}" 
                    class="menu-link {{ $hasChildren ? 'menu-toggle' : '' }}">
                        <i class="menu-icon tf-icons bx {{ $menu->icon ?? 'bx-circle' }}"></i>
                        <div>{{ $menu->title }}</div>
                    </a>

                    {{-- Jika ada Submenu, looping di sini --}}
                    @if($hasChildren)
                        <ul class="menu-sub">
                            @foreach($menu->childrenRecursive as $child)
                                <li class="menu-item {{ Request::is(ltrim($child->url, '/') . '*') ? 'active' : '' }}">
                                    <a href="{{ url($child->url) }}" class="menu-link">
                                        <div>{{ $child->title }}</div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </li>
            @endforeach
          </ul>
        </aside>
        <div class="layout-page">
          <!-- Navbar -->

          <nav
            class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
            id="layout-navbar"
          >
            <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
              <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                <i class="bx bx-menu bx-sm"></i>
              </a>
            </div>

            <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
              <!-- Search -->
              <div class="navbar-nav align-items-center">
                <div class="nav-item d-flex align-items-center position-relative">
                  <i class="bx bx-search fs-4 lh-0"></i>
                  <input
                    type="text"
                    class="form-control border-0 shadow-none"
                    id="global-menu-search"
                    placeholder="Search..."
                    aria-label="Search..."
                  />
                  <div id="global-search-result" class="dropdown-menu show" style="display:none; position:absolute; top:100%; left:0; right:0; max-height:320px; overflow:auto;"></div>
                </div>
              </div>
              <!-- /Search -->

              <ul class="navbar-nav flex-row align-items-center ms-auto">
                <!-- User -->
                <li class="nav-item navbar-dropdown dropdown-user dropdown">
                    <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                      @if(Auth::user() && Auth::user()->avatar)
                        <img src="{{ asset('storage/avatars/' . Auth::user()->avatar) }}" alt class="w-px-40 h-auto rounded-circle" />
                      @else
                        <img src="{{ webp_asset('assets/img/avatars/1.png') }}" alt class="w-px-40 h-auto rounded-circle" />
                      @endif
                    </div>
                  </a>
                  <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                      <a class="dropdown-item" href="#">
                        <div class="d-flex">
                          <div class="flex-shrink-0 me-3">
                            <div class="avatar avatar-online">
                              <img src="{{ webp_asset('assets/img/avatars/1.png') }}" alt class="w-px-40 h-auto rounded-circle" />
                            </div>
                          </div>
                          <div class="flex-grow-1">
                            <span class="fw-semibold d-block">{{ Auth::user()->name }}</span>
                            <small class="text-muted">Admin</small>
                          </div>
                        </div>
                      </a>
                    </li>
                    <li>
                      <div class="dropdown-divider"></div>
                    </li>
                    <li>
                      <a class="dropdown-item" href="{{ route('profile.edit') }}">
                        <i class="bx bx-user me-2"></i>
                        <span class="align-middle">My Profile</span>
                      </a>
                    </li>
                    <li>
                      <a class="dropdown-item" href="{{ route('settings.index') }}">
                        <i class="bx bx-cog me-2"></i>
                        <span class="align-middle">Settings</span>
                      </a>
                    </li>
                    <li>
                      <div class="dropdown-divider"></div>
                    </li>
                    <li>
                      <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item">
                          <i class="bx bx-power-off me-2"></i>
                          <span class="align-middle">Log Out</span>
                        </button>
                      </form>
                    </li>
                  </ul>
                </li>
                <!--/ User -->
              </ul>
            </div>
          </nav>

          <!-- / Navbar -->

          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->
            @yield('content')               
            <!-- / Content -->

            <div class="content-backdrop fade"></div>
          </div>
          <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
      </div>

      <!-- Overlay -->
      <div class="layout-overlay layout-menu-toggle"></div>
    </div>

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>

    <script src="{{ asset('assets/vendor/js/menu.js') }}"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>

    <!-- Main JS -->
    <script src="{{ asset('assets/js/main.js') }}"></script>

    <!-- Page JS -->
    <script src="{{ asset('assets/js/dashboards-analytics.js') }}"></script>
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <script>
      function formatValidationErrors(xhr, fallback) {
        const response = xhr.responseJSON || {};
        const errors = response.errors || {};
        const messages = Object.values(errors).flat();

        return messages.length ? messages.join('<br>') : (response.message || fallback);
      }

      (function () {
        const searchInput = document.getElementById('global-menu-search');
        const resultBox = document.getElementById('global-search-result');

        if (!searchInput || !resultBox) {
          return;
        }

        const menuLinks = Array.from(document.querySelectorAll('#layout-menu .menu-link[href]'))
          .filter((link) => {
            const href = link.getAttribute('href') || '';
            return href && href !== '#' && !href.startsWith('javascript:');
          })
          .map((link) => {
            const labelNode = link.querySelector('div');
            return {
              label: (labelNode ? labelNode.textContent : link.textContent).trim(),
              url: link.getAttribute('href')
            };
          })
          .filter((item, index, arr) => item.label && arr.findIndex((m) => m.url === item.url) === index);

        let activeIndex = -1;
        let currentResults = [];

        const closeResult = function () {
          resultBox.style.display = 'none';
          resultBox.innerHTML = '';
          activeIndex = -1;
          currentResults = [];
        };

        const openResult = function () {
          resultBox.style.display = 'block';
        };

        const renderResults = function (items) {
          if (!items.length) {
            resultBox.innerHTML = '<span class="dropdown-item-text text-muted">No menu found</span>';
            openResult();
            return;
          }

          resultBox.innerHTML = items
            .map((item, idx) => `<button type="button" class="dropdown-item" data-index="${idx}" data-url="${item.url}">${item.label}</button>`)
            .join('');

          openResult();
        };

        searchInput.addEventListener('input', function () {
          const keyword = this.value.trim().toLowerCase();

          if (!keyword) {
            closeResult();
            return;
          }

          currentResults = menuLinks
            .filter((item) => item.label.toLowerCase().includes(keyword))
            .slice(0, 8);
          activeIndex = -1;
          renderResults(currentResults);
        });

        searchInput.addEventListener('keydown', function (event) {
          if (!currentResults.length) {
            return;
          }

          if (event.key === 'ArrowDown') {
            event.preventDefault();
            activeIndex = (activeIndex + 1) % currentResults.length;
          } else if (event.key === 'ArrowUp') {
            event.preventDefault();
            activeIndex = (activeIndex - 1 + currentResults.length) % currentResults.length;
          } else if (event.key === 'Enter') {
            event.preventDefault();
            const target = activeIndex >= 0 ? currentResults[activeIndex] : currentResults[0];
            if (target && target.url) {
              window.location.href = target.url;
            }
            return;
          } else if (event.key === 'Escape') {
            closeResult();
            return;
          } else {
            return;
          }

          const items = resultBox.querySelectorAll('.dropdown-item');
          items.forEach((item, idx) => {
            item.classList.toggle('active', idx === activeIndex);
          });
        });

        resultBox.addEventListener('click', function (event) {
          const button = event.target.closest('.dropdown-item');
          if (!button) {
            return;
          }

          const url = button.getAttribute('data-url');
          if (url) {
            window.location.href = url;
          }
        });

        document.addEventListener('click', function (event) {
          if (!event.target.closest('.nav-item.position-relative')) {
            closeResult();
          }
        });
      })();
    </script>
    <script>
      (function () {
        const blockSelector = 'p, div, li, blockquote, h1, h2, h3, h4, h5, h6';
        const alignments = [
          { command: 'justifyLeft', title: 'Rata kiri', icon: 'bx-align-left' },
          { command: 'justifyCenter', title: 'Rata tengah', icon: 'bx-align-middle' },
          { command: 'justifyRight', title: 'Rata kanan', icon: 'bx-align-right' },
          { command: 'justifyFull', title: 'Rata kanan-kiri', icon: 'bx-align-justify' }
        ];
        const lineHeights = [
          { value: '', label: 'Line spacing' },
          { value: '1', label: '1.0' },
          { value: '1.15', label: '1.15' },
          { value: '1.5', label: '1.5' },
          { value: '1.75', label: '1.75' },
          { value: '2', label: '2.0' }
        ];

        function syncEditor(content) {
          const editor = content.closest('.rich-editor');
          const target = editor ? editor.getAttribute('data-editor-target') : null;
          const textarea = target ? document.getElementById(target) : null;

          if (textarea) {
            textarea.value = content.innerHTML.trim();
          }

          content.dispatchEvent(new Event('input', { bubbles: true }));
        }

        function getSelectedBlocks(content) {
          const selection = window.getSelection();

          if (!selection || !selection.rangeCount || !content.contains(selection.anchorNode)) {
            return [];
          }

          const range = selection.getRangeAt(0);
          const blocks = Array.from(content.querySelectorAll(blockSelector))
            .filter((node) => range.intersectsNode(node));

          if (blocks.length) {
            return blocks;
          }

          let node = selection.anchorNode;
          if (node && node.nodeType === Node.TEXT_NODE) {
            node = node.parentElement;
          }

          const closestBlock = node && node.closest ? node.closest(blockSelector) : null;
          return closestBlock && content.contains(closestBlock) ? [closestBlock] : [];
        }

        function applyLineHeight(content, value) {
          content.focus();
          let blocks = getSelectedBlocks(content);

          if (!blocks.length) {
            document.execCommand('formatBlock', false, 'p');
            blocks = getSelectedBlocks(content);
          }

          if (!blocks.length) {
            content.style.lineHeight = value || '';
          } else {
            blocks.forEach((block) => {
              block.style.lineHeight = value || '';
            });
          }

          syncEditor(content);
        }

        function enhanceRichEditor(editor) {
          const toolbar = editor.querySelector('.rich-editor__toolbar');
          const content = editor.querySelector('.rich-editor__content');

          if (!toolbar || !content || toolbar.dataset.spacingAlignEnhanced === 'true') {
            return;
          }

          toolbar.dataset.spacingAlignEnhanced = 'true';

          const alignGroup = document.createElement('span');
          alignGroup.className = 'rich-editor__group';

          alignments.forEach((item) => {
            const button = document.createElement('button');
            button.type = 'button';
            button.title = item.title;
            button.setAttribute('aria-label', item.title);
            button.setAttribute('data-editor-enhance', 'align');
            button.innerHTML = '<i class="bx ' + item.icon + '"></i>';
            button.addEventListener('click', function () {
              content.focus();
              document.execCommand(item.command, false, null);
              syncEditor(content);
            });
            alignGroup.appendChild(button);
          });

          const spacing = document.createElement('select');
          spacing.className = 'form-select rich-editor__select';
          spacing.setAttribute('aria-label', 'Line spacing');
          spacing.setAttribute('data-editor-enhance', 'line-height');
          spacing.innerHTML = lineHeights
            .map((item) => '<option value="' + item.value + '">' + item.label + '</option>')
            .join('');
          spacing.addEventListener('change', function () {
            applyLineHeight(content, this.value);
            this.value = '';
          });

          toolbar.appendChild(alignGroup);
          toolbar.appendChild(spacing);
        }

        function enhanceAllRichEditors() {
          document.querySelectorAll('.rich-editor').forEach(enhanceRichEditor);
        }

        enhanceAllRichEditors();

        const observer = new MutationObserver(enhanceAllRichEditors);
        observer.observe(document.body, { childList: true, subtree: true });
      })();
    </script>
  </body>
</html>
