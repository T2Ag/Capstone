<template>
   <Layout>
      <div class=" p-3 ">
         <div class="pb-8 border-b border-gray-200">
            <h1 class="text-3xl font-bold text-gray-800 tracking-tight hover:text-gray-900 transition-colors">
               Admin Dashboard
            </h1>
         </div>

         <!-- Summary -->
         <div class="flex flex-row space-x-4 w-full py-2">
            <!-- Clients Card -->
            <div class="flex-1 bg-gradient-to-r from-cyan-500 to-cyan-400 rounded-lg shadow-lg">
                  <div class="lg:flex items-center p-6">
                     <div class="flex items-center justify-center w-12 h-12 rounded-full bg-white/20">
                        <i class="bi bi-people lg:text-2xl text-white"></i>
                     </div>
                     <div class="ml-4">
                        <p class="lg:text-3xl font-bold text-white">
                              {{ totalClients }}
                        </p>
                        <p class="text-white/90 font-bold">
                              Clients
                        </p>
                     </div>
                  </div>
            </div>

            <!-- Members Card -->
            <div class="flex-1 bg-gradient-to-r from-red-500 to-red-400 rounded-lg shadow-lg">
                  <div class="lg:flex items-center p-6">
                     <div class="flex items-center justify-center w-12 h-12 rounded-full bg-white/20">
                        <i class="bi bi-person lg:text-2xl text-white"></i>
                     </div>
                     <div class="ml-4">
                        <p class="lg:text-3xl font-bold text-white">
                              {{ totalMembers }}
                        </p>
                        <p class="text-white/90 font-bold">
                              Members
                        </p>
                     </div>
                  </div>
            </div>

            <!-- Earnings Card -->
            <div class="flex-1 bg-gradient-to-r from-green-600 to-green-400 rounded-lg shadow-lg">
               <div class="lg:flex items-center p-6">
                  <div class="flex items-center justify-center w-12 h-12 rounded-full bg-white/20">
                     <span class="lg:text-2xl text-white">₱</span>
                  </div>
                  <div class="ml-4">
                        <p class="lg:text-3xl font-bold text-white">
                           {{"₱" + totalEarnings }}
                        </p>
                        <p class="text-white/90 font-bold">
                           Total Earnings
                        </p>
                  </div>
               </div>
            </div>
            
         </div>

         <!-- Summary -->
         <div class="flex flex-row space-x-4 w-full mx-auto py-2">
            <!-- Monthly Clients Card -->
            <div class="flex-1 bg-gradient-to-r from-blue-600 to-blue-400 rounded-lg shadow-lg">
                  <div class="lg:flex items-center p-6">
                     <div class="flex items-center justify-center w-12 h-12 rounded-full bg-white/20">
                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="white" class="bi bi-person-arms-up" viewBox="0 0 16 16">
                        <path d="M8 3a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3"/>
                        <path d="m5.93 6.704-.846 8.451a.768.768 0 0 0 1.523.203l.81-4.865a.59.59 0 0 1 1.165 0l.81 4.865a.768.768 0 0 0 1.523-.203l-.845-8.451A1.5 1.5 0 0 1 10.5 5.5L13 2.284a.796.796 0 0 0-1.239-.998L9.634 3.84a.7.7 0 0 1-.33.235c-.23.074-.665.176-1.304.176-.64 0-1.074-.102-1.305-.176a.7.7 0 0 1-.329-.235L4.239 1.286a.796.796 0 0 0-1.24.998l2.5 3.216c.317.316.475.758.43 1.204Z"/>
                        </svg>
                     </div>
                     <div class="ml-4">
                        <p class="lg:text-3xl font-bold text-white">
                              {{ totalActiveMonthlyClients }}
                        </p>
                        <p class="text-white/90 font-bold">
                              Active Monthly Clients
                        </p>
                     </div>
                  </div>
            </div>

            <!-- Coaches Card -->
            <div class="flex-1 bg-gradient-to-r from-red-700 to-red-400 rounded-lg shadow-lg">
                  <div class="lg:flex items-center p-6">
                     <div class="flex items-center justify-center w-12 h-12 rounded-full bg-white/20">
                        <i class="bi bi-people text-2xl text-white"></i>
                     </div>
                     <div class="ml-4">
                        <p class="lg:text-3xl font-bold text-white">
                              {{ totalCoaches }}
                        </p>
                        <p class="text-white/90 font-bold">
                              Coaches
                        </p>
                     </div>
                  </div>
            </div>
            
         </div>

         <!-- Graph -->
         <div class="bg-white rounded-lg shadow-lg mt-[2rem]">
            <!-- Header -->
            <div class="flex justify-between px-6 py-4 border-b border-gray-200">
                  <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                     <i class="bi bi-graph-up-arrow mr-2"></i>
                     REPORTS
                  </h2>

                  <div>
                     <button
                        class="bg-blue-500 text-white px-4 py-2 rounded"
                        @click="downloadPDF"
                     >
                        Download Monthly Report
                     </button>
                  </div>
            </div>



            <div class="p-6 ">

               <!-- Title with subtle badge -->
               <div class="flex items-center  ">
                  <h3 class="text-2xl text-gray-700 font-semibold">Monthly Gym Visits Graph</h3>
                  <span class="ml-2 px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">2024</span>
               </div>

               <div class="lg:flex gap-6">

                  <!-- Chart Section -->
                  <div class="w-full">
                     <SampleChart :months="months" :totals="totals"/>
                  </div>


                  <!-- Weekly Statistics Cards -->
                  <div class="flex lg:w-[460px] gap-4 my-4">
                     
                     <!-- Visits Card -->
                     <!-- Large Screen Version -->
                     <div class="hidden lg:flex flex-1 bg-gradient-to-b from-emerald-400 to-emerald-700 rounded-xl shadow-lg overflow-hidden">
                           <div class="p-6 flex flex-col items-center justify-between h-full w-full">
                              <div class="bg-white/10 rounded-full p-4 mb-4">
                                 <i class="bi bi-people text-4xl text-white"></i>
                              </div>
                              <div class="text-center flex-grow flex flex-col justify-center">
                                 <span class="block text-4xl font-bold text-white mb-2">
                                       {{ totalLogsThisMonth }}
                                 </span>
                                 <div class="text-white/90 text-lg font-medium">
                                       Visits this month
                                 </div>
                              </div>
                           </div>
                     </div>

                     <!-- Small Screen Version -->
                     <div class="lg:hidden flex-1 bg-gradient-to-r from-emerald-400 to-emerald-700 rounded-lg shadow-lg">
                        <div class="flex items-center p-6">
                           <div class="flex items-center justify-center w-12 h-12 rounded-full bg-white/20">
                              <i class="bi bi-people text-2xl text-white"></i>
                           </div>
                           <div class="ml-4">
                              <p class=" font-bold text-white">
                                    {{ totalLogsThisMonth }}
                              </p>
                              <p class="text-white/90">
                                    Visits this month
                              </p>
                           </div>
                        </div>
                     </div>

                     <!-- Earnings Card -->
                     <!-- Large Screen Version -->
                     <div class="hidden lg:flex flex-1 bg-gradient-to-b from-blue-400 to-blue-700 rounded-xl shadow-lg overflow-hidden">
                        <div class="p-6 flex flex-col items-center justify-between h-full w-full">
                           <div class="bg-white/10 rounded-full p-4 mb-4">
                              <span class="text-4xl text-white">₱</span>
                           </div>
                           <div class="text-center flex-grow flex flex-col justify-center">
                              <span class="block text-4xl font-bold text-white mb-2">
                                    {{ "₱" + totalEarningsThisMonth.toFixed(2) }}
                              </span>
                              <div class="text-white/90 text-lg font-medium">
                                    Earnings this month
                              </div>
                           </div>
                        </div>
                     </div>

                     <!-- Small Screen Version -->
                     <div class="lg:hidden flex-1 bg-gradient-to-r from-blue-400 to-blue-700 rounded-lg shadow-lg">
                        <div class="flex items-center p-6">
                           <div class="flex items-center justify-center w-12 h-12 rounded-full bg-white/20">
                              <span class="text-2xl text-white">₱</span>
                           </div>
                           <div class="ml-4">
                              <p class=" font-bold text-white">
                                    {{ "₱" + totalEarningsThisMonth.toFixed(2) }}
                              </p>
                              <p class="text-white/90">
                                    Earnings this month
                              </p>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>

               <div class="flex justify-between p-5 w-full mx-auto border rounded shadow-md mb-5">
                     <div class="flex flex-col px-4">
                        <div class="mb-6 flex flex-row sm:flex-col items-center">
                           <h3 class="text-md text-gray-700 font-semibold">Last Weeks' Earnings: </h3>
                           <span class="ml-2 px-2 py-1 text-xl font-semibold">{{"₱ " +  totalEarningsLastWeek.toFixed(2) }}</span>
                        </div>

                        <div class=" flex flex-row sm:flex-col items-center">
                           <h3 class="text-md text-gray-700 font-semibold">Last Months Earnings: </h3>
                           <span class="ml-2 px-2 py-1 text-xl font-semibold">{{"₱ " +  totalEarningsLastMonth.toFixed(2) }}</span>
                        </div>

                     </div>

                     <div class="flex flex-col px-4">

                        <div class="mb-6 flex flex-row sm:flex-col items-center ">
                           <h3 class="text-md text-green-500 font-semibold">This Week Earnings: </h3>
                           <span class="ml-2 px-2 py-1 text-xl font-semibold">{{"₱ " +  totalEarningsThisWeek.toFixed(2) }}</span>

                           <span v-if="determinePercentageStatus(weekEarningsPercentage) === 'positive'" class="text-green-500">
                              <i class="bi bi-caret-up-fill"></i>
                              {{ weekEarningsPercentage + '%'}} increase from last week.
                              
                           </span>
                           <span v-if="determinePercentageStatus(weekEarningsPercentage) === 'negative'" class="text-red-500">
                              <i class="bi bi-caret-down-fill"></i>
                              {{ weekEarningsPercentage + '%'}} decrease from last week.
                           </span>
                           <span v-if="determinePercentageStatus(weekEarningsPercentage) === 'neutral'" class="text-gray-500">
                              {{ weekEarningsPercentage + '%'}}
                           </span>

                        </div>

                        <div class=" flex flex-row sm:flex-col items-center">
                           <h3 class="text-md text-green-500 font-semibold">This Months Earnings: </h3>
                           <span class="ml-2 px-2 py-1 text-xl font-semibold">{{"₱ " +  totalEarningsThisMonth.toFixed(2) }}</span>

                           <span v-if="determinePercentageStatus(monthEarningsPercentage) === 'positive'" class="text-green-500">
                              <i class="bi bi-caret-up-fill"></i>
                              {{ monthEarningsPercentage + '%'}} increase from last month.
                              
                           </span>
                           <span v-if="determinePercentageStatus(monthEarningsPercentage) === 'negative'" class="text-red-500">
                              <i class="bi bi-caret-down-fill"></i>
                              {{ monthEarningsPercentage + '%'}} decrease from last month.
                           </span>
                           <span v-if="determinePercentageStatus(weekEarningsPercentage) === 'neutral'" class="text-gray-500">
                              {{ monthEarningsPercentage + '%'}} 
                           </span>
                        </div>
                     </div>
                  </div>

               <div>
                  <ClientsPieChart :totalWalkInClients="totalWalkInClients" :totalMonthlyClients="totalMonthlyClients"/>
               </div>
            </div>
         </div>

      </div>

      <div class="flex space-x-4">
         <!-- First Announcement Section -->
         <div v-if="firstAnnouncement" class="bg-white mt-4 m-3 rounded-lg shadow-lg overflow-hidden flex-1">
            <div class="bg-gradient-to-r from-red-500 to-red-400 p-6">
               <div class="flex items-center mb-4">
                  <i class="bi bi-megaphone text-white text-3xl mr-4"></i>
                  <h2 class="text-2xl font-bold text-white">Latest Announcement</h2>
               </div>
               <div class="bg-white/20 rounded-lg p-4">
                  <h3 class="text-xl font-bold text-white mb-2">
                     {{ firstAnnouncement.title }}
                  </h3>
                  <p class="text-white/90 mb-4">
                     {{ firstAnnouncement.content }}
                  </p>
                  <div class="flex justify-between items-center">
                     <span class="text-sm text-white/80">
                        {{ formatDate(firstAnnouncement.created_at) }}
                     </span>
                  </div>
               </div>
            </div>
         </div>

         <!-- Active Monthly Clients Card with Table -->
         <!-- <div class="bg-white rounded-lg shadow-lg m-3 flex-1">
            <div class="p-6">
               <h2 class="text-xl font-bold mb-4">Active Monthly Clients</h2>

               <table class="min-w-full table-auto">
                  <thead>
                     <tr>
                        <th class="py-2 px-4 border-b">Client Name</th>
                     </tr>
                  </thead>
                  <tbody>
                     <tr v-for="client in activeMonthlyClients" :key="client.id">
                        <td class="py-2 px-4 border-b">
                           {{ client.first_name }} {{ client.middle_initial }} {{ client.last_name }} 
                        </td>
                     </tr>
                  </tbody>
               </table>
            </div>
         </div> -->
      </div>

      <div class="bg-white mt-4 m-3 rounded-lg shadow-lg overflow-hidden">


         <!-- <div class="px-6 py-4 bg-gradient-to-r from-gray-100 to-gray-200 border-b border-gray-300">
            <p class="text-xl text-gray-700 font-semibold flex items-center">
               <i class="bi bi-calendar-event mr-3 text-gray-600"></i>
               ANNOUNCEMENTS
            </p>
         </div> -->

         <!-- <table class="w-full">
            <thead>
               <tr class="bg-gray-50 border-b border-gray-200">
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                     Announcement Details
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                     Date
                  </th>
               </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
               <tr 
                  v-for="announcement in announcements" 
                  :key="announcement.id"
                  class="hover:bg-gray-50 transition-colors duration-200"
               >
                  <td class="px-6 py-4">
                     <div class="text-sm font-medium text-gray-900">
                        {{ announcement.title }}
                     </div>
                     <div class="text-xs text-gray-500 mt-1 line-clamp-2">
                        {{ announcement.content }}
                     </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                     <div class="text-sm text-gray-500">
                        {{ formatDate(announcement.created_at) }}
                     </div>
                  </td>
               </tr>
            </tbody>
         </table>

         <div v-if="announcements.length === 0" class="text-center py-6 text-gray-500">
            <i class="bi bi-info-circle mr-2"></i>
            No announcements at this time
         </div> -->
      </div>
   </Layout>
</template>

<script setup>
import Layout from '../../Layouts/Layout.vue';
import ClientsChart from '../../Components/Charts/ClientsChart.vue';
import SampleChart from '../../Components/Charts/SampleChart.vue';
import ClientsPieChart from '../../Components/Charts/ClientsPieChart.vue';

const props = defineProps({
   months: Array,
   totals: Array,
   totalClients: Number,
   totalMembers: Number,
   totalEarnings: Number,
   totalLogsThisMonth: Number,
   totalCoaches: Number,
   totalEarningsThisMonth: Number,
   totalActiveMonthlyClients: Number,
   announcements: Array,
   firstAnnouncement: Object,
   activeMonthlyClients: Array,

   totalEarningsThisWeek: Number,
   totalEarningsLastWeek: Number,
   weekEarningsPercentage: Number,

   totalEarningsLastMonth: Number,
   monthEarningsPercentage: Number,

   totalWalkInClients: Number,
   totalMonthlyClients: Number,

});

const formatDate = (dateString) => {
   return new Date(dateString).toLocaleDateString('en-US', {
      month: 'short', 
      day: 'numeric', 
      year: 'numeric'
   })
}

const determinePercentageStatus = (percentage) => {
  if (percentage > 0) {
    return 'positive'
  } else if (percentage < 0) {
    return 'negative'
  } else if (percentage = 0) {
    return 'neutral'
  }
}

const downloadPDF = () => {
  window.open('/export-monthly-report', '_blank');
};
</script>