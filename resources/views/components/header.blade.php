<header class="bg-blue-900 text-white p-4" x-data="{ open: false}">
    <div class="container mx-auto flex justify-between items-center">
      <h1 class="text-3xl font-semibold">
        <a href="{{ url('/') }}">Workopia</a>
      </h1>
      <nav class="hidden md:flex items-center space-x-4">
        <x-nav-link url="/" title="Home page" :active="request()->is('/')">Home</x-nav-link>
        <x-nav-link url="/jobs" title="Jobs page" :active="request()->is('jobs')">All Jobs</x-nav-link>
        @auth
        <x-nav-link url="/jobs/saved" title="Saved jobs page" :active="request()->is('jobs/saved')">Saved Jobs</x-nav-link>
        <x-nav-link url="/dashboard" title="Dashboard page" :active="request()->is('dashboard')" icon="gauge">Dashboard</x-nav-link>
        <x-logout-button />
        <x-button-link url="/jobs/create" icon="edit">Create Job</x-button-link>
        @else
        <x-nav-link url="/login" title="Login page" :active="request()->is('login')">Login</x-nav-link>
        <x-nav-link url="/register" title="Registration page" :active="request()->is('register')">Register</x-nav-link>
        @endauth
      </nav>
      <button @click="open = !open" id="hamburger" class="text-white md:hidden flex items-center">
        <i class="fa fa-bars text-2xl"></i>
      </button>
    </div>
    <!-- Mobile Menu -->
    <nav
      @click.away="open = false"
      x-show="open"
      id="mobile-menu"
      class="md:hidden bg-blue-900 text-white mt-5 pb-4 space-y-2"
    >
      <x-nav-link url="/jobs" title="Jobs page" :active="request()->is('jobs')" :mobile="true">All Jobs</x-nav-link>
      <x-nav-link url="/jobs/saved" title="Jobs page" :active="request()->is('jobs/saved')" :mobile="true">Saved Jobs</x-nav-link>
      @auth
      <x-nav-link url="/dashboard" title="Dashboard page" :active="request()->is('dashboard')" :mobile="true">Dashboard</x-nav-link>
      <x-logout-button />
      <div class="pt-2"></div>
      <x-button-link url="/jobs/create" icon="edit" :block="true">Create Job</x-button-link>
      @else
      <x-nav-link url="/login" title="Login page" :active="request()->is('login')" :mobile="true">Login</x-nav-link>
      <x-nav-link url="/register" title="Register page" :active="request()->is('register')" :mobile="true">Register</x-nav-link>
      @endauth
    </nav>
  </header>