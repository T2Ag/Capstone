<template>
      <div class="fixed top-0 left-0 w-full text-xl border-b h-[69px] bg-white z-10">
         <div class="h-full flex justify-between items-center">
            <div class="px-3 text-gray-700 text-2xl flex items-center cursor-pointer" @click="openSideBar">
               <i class="bi bi-list"></i>
            </div>
            <h1 class="topbarlogo font-bold lg:text-white text-red-700 text-[30px] transition-all duration-300 ease-in-out">FLEX</h1>
            <div class="text-sm px-3">
               <Link :href="route('logout')">Logout</Link>
            </div>
         </div>
      </div>

     
      <div class="sidebar fixed top-0 bottom-0 lg:left-0 left-[-300px] w-[300px] overflow-y-auto text-center bg-white border-r-2 border-gray-300 transition-all duration-300 ease-in-out  z-20">
         
         <div class="text-gray-500 text-xl p-2 h-[69px] items-center">
            <div class="p-2.5 mt-1 flex lg:justify-start justify-between ">
               <i class="bi bi-trophy-fill rounded-md px-2"></i>
               <h1 class="font-bold text-red-700 text-[30px]">FLEX</h1>
               <i class="bi bi-list cursor-pointer lg:hidden" @click="openSideBar"></i>
            </div>
            <hr class="mx-[-1rem] text-gray-600">
         </div>
      
         <div class="p-2.5 mt-3 text-gray-700 flex text-[15px] font-bold rounded px-4 duration-300 cursor-pointer hover:bg-gray-100 hover:text-red-500">
            <Link :href="route('dashboard')">DASHBOARD</Link>
         </div>
         
         <div class="logs mt-3 text-gray-700 flex flex-col text-[15px] font-bold">
            <div class="flex justify-between w-full items-center hover:bg-gray-100 hover:text-red-500 px-4 p-2.5 duration-300 cursor-pointer rounded" @click="dropdown('logs')">
               <span>LOGS</span>
               <span class="text-md transition-transform duration-300" :class="submenuVisible.logs ? 'rotate-180' : 'rotate-0'" id="arrow-logs">
                  <i class="bi bi-chevron-down"></i>
               </span>
            </div>
      
            <div class="text-left text-sm font-bold mt-2 w-full mx-auto" :class="{'hidden': !submenuVisible.logs}" id="submenu-logs">
               <Link :href="route('logs')" class="cursor-pointer p-2 duration-300 hover:bg-gray-100 hover:text-red-500 rounded mt-1 ml-5">Scan</Link>
            </div>

            <div class="text-left text-sm font-bold mt-2 w-full mx-auto" :class="{'hidden': !submenuVisible.logs}" id="submenu-logs">
               <Link :href="route('logs.list')" class="cursor-pointer p-2 duration-300 hover:bg-gray-100 hover:text-red-500 rounded mt-1 ml-5">Logs List</Link>
            </div>
         </div>
      
         <div class="clients mt-3 text-gray-700 flex flex-col text-[15px] font-bold">
            <div class="flex justify-between w-full items-center hover:bg-gray-100 hover:text-red-500 px-4 p-2.5 duration-300 cursor-pointer rounded" @click="dropdown('clients')">
               <span>CLIENTS</span>
               <span class="text-md transition-transform duration-300" :class="submenuVisible.clients ? 'rotate-180' : 'rotate-0'" id="arrow-clients">
                  <i class="bi bi-chevron-down"></i>
               </span>
            </div>
      
            <div class="text-left text-sm font-bold mt-2 w-full mx-auto" :class="{'hidden': !submenuVisible.clients}" id="submenu-clients">
               <Link :href="route('clients')" class="cursor-pointer p-2 duration-300 hover:bg-gray-100 hover:text-red-500 rounded mt-1 ml-5">Clients List</Link>
            </div>

            <div class="text-left text-sm font-bold mt-2 w-full mx-auto" :class="{'hidden': !submenuVisible.clients}" id="submenu-clients">
               <Link :href="route('pending')" class="cursor-pointer p-2 duration-300 hover:bg-gray-100 hover:text-red-500 rounded mt-1 ml-5">Pending</Link>
            </div>
         </div>
      
         <div class="users mt-3 text-gray-700 flex flex-col text-[15px] font-bold">
            <div class="flex justify-between w-full items-center hover:bg-gray-100 hover:text-red-500 px-4 p-2.5 duration-300 cursor-pointer rounded" @click="dropdown('users')">
               <span>USERS</span>
               <span class="text-md transition-transform duration-300" :class="submenuVisible.users ? 'rotate-180' : 'rotate-0'" id="arrow-users">
                  <i class="bi bi-chevron-down"></i>
               </span>
            </div>
      
            <div class="text-left text-sm font-bold mt-2 w-full mx-auto" :class="{'hidden': !submenuVisible.users}" id="submenu-users">
               <Link :href="route('users')" class="cursor-pointer p-2 duration-300 hover:bg-gray-100 hover:text-red-500 rounded mt-1 ml-5">Manage Users</Link>
            </div>
         </div>
      </div>
   
      <div class="lg:ml-[300px] mt-[69px] bg-gray-200 min-h-screen transition-all duration-300 ease-in-out" id="container">
         <slot></slot>
      </div>
 </template>
 
 <script setup>
 import { reactive } from 'vue';
 
 const submenuVisible = reactive({
    members: false,
    users: false,
 });
 
 const dropdown = (menu) => {
    for (let key in submenuVisible) {
       if (key !== menu) {
          submenuVisible[key] = false;
       }
    }
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
 
 