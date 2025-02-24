@include('partials.head')
<body>
  <div class="loading" id="loading_full_screen" style="display: none">
    <svg width="64px" height="48px">
        <polyline points="0.157 23.954, 14 23.954, 21.843 48, 43 0, 50 24, 64 24" id="back"></polyline>
      <polyline points="0.157 23.954, 14 23.954, 21.843 48, 43 0, 50 24, 64 24" id="front"></polyline>
    </svg>
  </div>
    @include('partials.header')

    @include('partials.sidebar')

  <main id="main" class="main">

    <div class="dot-spinner" id="spinner_full_screen" style="display: none">
      <div class="dot-spinner__dot"></div>
      <div class="dot-spinner__dot"></div>
      <div class="dot-spinner__dot"></div>
      <div class="dot-spinner__dot"></div>
      <div class="dot-spinner__dot"></div>
      <div class="dot-spinner__dot"></div>
      <div class="dot-spinner__dot"></div>
      <div class="dot-spinner__dot"></div>
    </div>

    <div class="pagetitle my-1">
      @yield('section-title')
    </div><!-- End Page Title -->

    <section class="section dashboard">
      @yield('content')
    </section>

  </main><!-- End #main -->

  @include('partials.footer')

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  @include('partials.links_js')

</body>

</html>