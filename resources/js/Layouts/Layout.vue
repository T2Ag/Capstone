<script setup>
import { Link } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';
import { reactive, watch, onMounted, ref } from 'vue';

const page = usePage();

// Initialize submenu state
const submenuVisible = reactive({
  logs: false,
  clients: false,
  users: false,
  trainors: false
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
  Object.keys(submenuVisible).forEach(key => {
    submenuVisible[key] = false;
  });
  
  // Set the appropriate dropdown based on current route
  if (currentRoute.includes('/logs')) {
    submenuVisible.logs = true;
  } else if (currentRoute.includes('/clients') || currentRoute.includes('/pending') || currentRoute.includes('/transactions')) {
    submenuVisible.clients = true;
  } else if (currentRoute.includes('/users')) {
    submenuVisible.users = true;
  } else if (currentRoute.includes('/coaches') || currentRoute.includes('/trainings') || currentRoute.includes('/trainingTransactions')) {
    submenuVisible.trainors = true;
  }
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
  
  <div v-show="profileDropdownVisible" @click="profileDropdownVisible = false" class="fixed inset-0 z-20"></div>

  <div class="fixed top-0 left-0 w-full text-xl border-b h-[69px] bg-white z-5 ">
    <div class="h-full flex justify-between items-center">
      <div class="px-3 text-gray-700 text-2xl flex items-center cursor-pointer" @click="openSideBar">
        <i class="bi bi-list"></i>
      </div>
      <h1 class="topbarlogo font-bold lg:hidden text-red-700 text-[30px] transition-all duration-300 ease-in-out">FLEX</h1>
      <div class="px-3"></div>
    </div>
  </div>

  <div class="fixed right-0 top-4 text-sm px-3 z-30">
    <div class="">
      <!-- Toggle Button -->
      <button
        @click="profileDropdownVisible = !profileDropdownVisible"
        class="flex items-center px-3 py-2 text-gray-700 border rounded-md hover:bg-gray-100"
      >
        <i class="bi bi-person-circle mr-2"></i>
          <h1>{{ $page.props.auth.user.username }}</h1>
        <i class="bi bi-chevron-down ml-2"></i>
      </button>
      <!-- Dropdown Menu -->
      <div v-show="profileDropdownVisible" class="absolute z-50 right-4 w-40 mt-2 bg-white border border-gray-200 rounded-md shadow-lg" >
        <Link :href="route('edit')" class="block px-4 py-2 text-black hover:bg-gray-100">Profile</Link>
        <Link :href="route('changePassword')" class="block px-4 py-2 text-black hover:bg-gray-100">Change Password</Link>
        <Link :href="route('logout')" class="block px-4 py-2 text-red-700 hover:bg-gray-100" >
          Logout
        </Link>
      </div>
    </div>
  </div>

  <div class="sidebar fixed top-0 bottom-0 lg:left-0 left-[-300px] w-[300px] text-center bg-white border-r-2 border-gray-300 transition-all duration-300 ease-in-out z-10" >
    <div class="text-gray-500 text-xl p-2 h-[69px] items-center border-b">
      <div class="p-2.5 my-.5 flex lg:justify-center justify-between items-center">
        <img :src="'/arm.png'" class="max-w-8 max-h-8 align-middle lg:mr-3" alt="">
        <h1 class="font-bold text-red-700 text-[30px]">FLEX</h1>
        <i class="bi bi-list cursor-pointer lg:hidden" @click="openSideBar"></i>
      </div>
      
    </div>

    <div class="p-2.5 mt-3 text-gray-700 flex text-[15px] font-bold rounded px-4 duration-300 cursor-pointer hover:bg-gray-100 hover:text-red-500"
    :class="{'bg-gray-100 text-red-500': isActive('/dashboard')}">
      <i class="bi bi-speedometer2 mr-3"></i>
      <Link :href="route('dashboard')">DASHBOARD</Link>
    </div>

    <!-- Logs Section -->
    <div class="logs mt-3 text-gray-700 flex flex-col text-[15px] font-bold">
      <div class="flex justify-between w-full items-center hover:bg-gray-100 hover:text-red-500 px-4 p-2.5 duration-300 cursor-pointer rounded " 
      :class="{'bg-gray-100 text-red-500': isActive('/logs')}" @click="dropdown('logs')">
        <div>
          <i class="bi bi-journal-text mr-3"></i>
          <span>LOGS</span>       
        </div>

        <span class="text-md transition-transform duration-300" :class="{'rotate-180': submenuVisible.logs}">
          <i class="bi bi-chevron-down"></i>
        </span>
      </div>

      <div class="mt-1" :class="{'hidden': !submenuVisible.logs}" >
        <a href="/logs" class="block p-2 hover:bg-gray-100 hover:text-red-500 ml-5" :class="{'bg-gray-100 text-red-500': isActive('/logs', true)}">Scan</a>
        <Link :href="route('logs.list')" class="block p-2 hover:bg-gray-100 hover:text-red-500 ml-5" :class="{'bg-gray-100 text-red-500': isActive('/logs/list', false)}">Logs List</Link>
      </div>
    </div>

    <!-- Clients Section -->
    <div class="clients mt-3 text-gray-700 flex flex-col text-[15px] font-bold">
      <div class="flex justify-between w-full items-center hover:bg-gray-100 hover:text-red-500 px-4 p-2.5 duration-300 cursor-pointer rounded" @click="dropdown('clients')" 
      :class="{
      'bg-gray-100 text-red-500': 
        isActive('/clients') || 
        isActive('/pending') || 
        isActive('/transactions')
      }"> 
        <div>
          <i class="bi bi-people-fill mr-3"></i>
          <span>CLIENTS</span>        
        </div>

        <span class="text-md transition-transform duration-300" :class="{'rotate-180': submenuVisible.clients}">
          <i class="bi bi-chevron-down"></i>
        </span>
      </div>

      <div class="mt-1" :class="{'hidden': !submenuVisible.clients}">
        <Link :href="route('clients')" class="block p-2 hover:bg-gray-100 hover:text-red-500 ml-5" :class="{'bg-gray-100 text-red-500': isActive('/clients', false)}">Clients List</Link>
        <Link :href="route('transactions.index')" class="block p-2 hover:bg-gray-100 hover:text-red-500 ml-5" :class="{'bg-gray-100 text-red-500': isActive('/transactions', false)}">Client Transactions</Link>
        <Link :href="route('pending')" class="block p-2 hover:bg-gray-100 hover:text-red-500 ml-5" :class="{'bg-gray-100 text-red-500': isActive('/pending', false)}">Pending</Link>
      </div>

    </div>

    <!-- Users Section -->
    <div class="users mt-3 text-gray-700 flex flex-col text-[15px] font-bold">
      <div class="flex justify-between w-full items-center hover:bg-gray-100 hover:text-red-500 px-4 p-2.5 duration-300 cursor-pointer rounded" @click="dropdown('users')"
      :class="{
      'bg-gray-100 text-red-500': 
        isActive('/users') 
      }">
        <div>
          <i class="bi bi-person-circle mr-3"></i>
          <span>USERS</span>        
        </div>

        <span class="text-md transition-transform duration-300" :class="{'rotate-180': submenuVisible.users}">
          <i class="bi bi-chevron-down"></i>
        </span>
      </div>

      <div class="mt-1" :class="{'hidden': !submenuVisible.users}">
        <Link :href="route('users')" class="block p-2 hover:bg-gray-100 hover:text-red-500 ml-5" :class="{'bg-gray-100 text-red-500': isActive('/users', false)}">Manage Users</Link>
      </div>
    </div>

    <!-- Trainors Section -->
    <div class="trainors mt-3 text-gray-700 flex flex-col text-[15px] font-bold">
      <div class="flex justify-between w-full items-center hover:bg-gray-100 hover:text-red-500 px-4 p-2.5 duration-300 cursor-pointer rounded" @click="dropdown('trainors')"
      :class="{
      'bg-gray-100 text-red-500': 
        isActive('/coaches') || 
        isActive('/trainings') || 
        isActive('/trainingTransactions')
      }">
        <div>
          <i class="bi bi-person mr-3"></i>
          <span>TRAINING</span>        
        </div>

        <span class="text-md transition-transform duration-300" :class="{'rotate-180': submenuVisible.trainors}">
          <i class="bi bi-chevron-down"></i>
        </span>
      </div>

      <div class="mt-1" :class="{'hidden': !submenuVisible.trainors}">
        <Link :href="route('coaches.index')" class="block p-2 hover:bg-gray-100 hover:text-red-500 ml-5" :class="{'bg-gray-100 text-red-500': isActive('/coaches', false)}">Manage Coaches</Link>
        <Link :href="route('trainings.index')" class="block p-2 hover:bg-gray-100 hover:text-red-500 ml-5" :class="{'bg-gray-100 text-red-500': isActive('/trainings', false)}">Manage Trainings</Link>
        <Link :href="route('trainingTransactions.index')" class="block p-2 hover:bg-gray-100 hover:text-red-500 ml-5" :class="{'bg-gray-100 text-red-500': isActive('/trainingTransactions', false)}">Manage Training Transactions</Link>
      </div>

    </div>

    <div class="p-2.5 mt-3 text-gray-700 flex text-[15px] font-bold rounded px-4 duration-300 cursor-pointer hover:bg-gray-100 hover:text-red-500"
    :class="{'bg-gray-100 text-red-500': isActive('/announcements')}">
      <i class="bi bi-megaphone-fill mr-3"></i>
      <Link :href="route('announcements.index')">ANNOUNCEMENTS</Link>
    </div>
    
  </div>


  <div class="mt-[69px] bg-gradient-to-br from-white to-red-300 transition-all duration-300 ease-in-out min-h-screen lg:ml-[300px] overflow-hidden" id="container">
      <slot></slot>
  </div>
</template>