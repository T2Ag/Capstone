<script setup>
import { Link } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';
import { reactive, watch, onMounted, ref } from 'vue';

const page = usePage();

// Initialize submenu state
const submenuVisible = reactive({
//   logs: false,
//   clients: false,
//   users: false,
//   trainors: false
});

const profileDropdownVisible = ref(false);

// Toggles the dropdown menu
const toggleDropdown = () => {
  profileDropdownVisible.value = !profileDropdownVisible.value;
};

const isActive = (url, exact = false) => {
  return exact ? page.url === url : page.url.includes(url);
};

// Function to determine which dropdown should be open based on current route
const updateDropdownState = () => {
  const currentRoute = page.url;
  
  // Reset all dropdowns
  // Object.keys(submenuVisible).forEach(key => {
  //   submenuVisible[key] = false;
  // });
  
  // Set the appropriate dropdown based on current route
  // if (currentRoute.includes('/logs')) {
  //   submenuVisible.logs = true;
  // } else if (currentRoute.includes('/clients') || currentRoute.includes('/pending') || currentRoute.includes('/transactions')) {
  //   submenuVisible.clients = true;
  // } else if (currentRoute.includes('/users')) {
  //   submenuVisible.users = true;
  // } else if (currentRoute.includes('/coaches') || currentRoute.includes('/trainings') || currentRoute.includes('/trainingTransactions')) {
  //   submenuVisible.trainors = true;
  // }
};

// Watch for route changes
watch(() => page.url, () => {
  updateDropdownState();
});

// Initial state setup
onMounted(() => {
  updateDropdownState();
});

const dropdown = (menu) => {
  submenuVisible[menu] = !submenuVisible[menu];
};

const openSideBar = () => {
  const sidebar = document.querySelector('.sidebar');
  const topbarlogo = document.querySelector('.topbarlogo');
  const isOpen = sidebar.classList.contains('left-0');
  
  sidebar.classList.toggle('left-0', !isOpen);  
  sidebar.classList.toggle('left-[-300px]', isOpen); 
  topbarlogo.classList.toggle('text-white', !isOpen);
};
</script>

<template>
  <div class="fixed top-0 left-0 w-full text-xl border-b h-[69px] bg-white z-10">
    <div class="h-full flex justify-between items-center">
      <div class="px-3 text-gray-700 text-2xl flex items-center cursor-pointer" @click="openSideBar">
        <i class="bi bi-list"></i>
      </div>
      <h1 class="topbarlogo font-bold lg:text-white text-red-700 text-[30px] transition-all duration-300 ease-in-out">FLEX</h1>
      <div class="text-sm px-3">

        <div class="relative">
          <!-- Toggle Button -->
          <button
            @click="toggleDropdown"
            class="flex items-center px-3 py-2 text-gray-700 border rounded-md hover:bg-gray-100"
          >
            <i class="bi bi-person-circle mr-2"></i>
              <h1>{{ $page.props.auth.user.username }}</h1>
            <i class="bi bi-chevron-down ml-2"></i>
          </button>
          <!-- Dropdown Menu -->
          <div v-show="profileDropdownVisible" class="absolute right-0 w-40 mt-2 bg-white border border-gray-200 rounded-md shadow-lg" >
            <Link :href="route('editCoach')" class="block px-4 py-2 text-black hover:bg-gray-100">Profile</Link>
            <Link :href="route('changePassword')" class="block px-4 py-2 text-black hover:bg-gray-100">Change Password</Link>
            <Link  method="post" as="button" :href="route('logout')"  class="block px-4 py-2 text-start w-full text-red-700 hover:bg-gray-100" >
              Logout
            </Link>
          </div>
        </div>

      </div>
    </div>
  </div>

  <div class="sidebar fixed top-0 bottom-0 lg:left-0 left-[-300px] w-[300px] overflow-y-auto text-center bg-white border-r-2 border-gray-300 transition-all duration-300 ease-in-out z-20" >
    <div class="text-gray-500 text-xl p-2 h-[69px] items-center">
      <div class="p-2.5 mt-1 flex lg:justify-start justify-between">
        <i class="bi bi-trophy-fill rounded-md px-2"></i>
        <h1 class="font-bold text-red-700 text-[30px]">FLEX</h1>
        <i class="bi bi-list cursor-pointer lg:hidden" @click="openSideBar"></i>
      </div>
      <hr class="mx-[-1rem] text-gray-600">
    </div>

    <div class="p-2.5 mt-3 text-gray-700 flex text-[15px] font-bold rounded px-4 duration-300 cursor-pointer hover:bg-gray-100 hover:text-red-500"
    :class="{'bg-gray-100 text-red-500': isActive('/trainerDashboard')}">
      <Link :href="route('trainerDashboard')">DASHBOARD</Link>
    </div>

    <div class="p-2.5 mt-3 text-gray-700 flex text-[15px] font-bold rounded px-4 duration-300 cursor-pointer hover:bg-gray-100 hover:text-red-500"
    :class="{'bg-gray-100 text-red-500': isActive('/trainingList')}">
      <Link :href="route('trainingList')">MANAGE TRAINING</Link>
    </div>

  </div>

  <div class="mt-[69px] bg-gradient-to-br from-white to-red-300 transition-all duration-300 ease-in-out min-h-screen lg:ml-[300px] overflow-hidden" id="container">
    <slot></slot>
  </div>
</template>