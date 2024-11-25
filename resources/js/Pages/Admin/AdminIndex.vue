<template>
   <Layout>
      <div class=" p-3 ">
         <div class="pb-8 border-b border-gray-200">
            <h1 class="text-3xl font-bold text-gray-800 tracking-tight hover:text-gray-900 transition-colors">
               Admin Dashboard
            </h1>
         </div>

         <!-- Summary -->
         <div class="flex flex-row space-x-4 w-full  py-2">
            <!-- Clients Card -->
            <div class="flex-1 bg-gradient-to-r from-cyan-500 to-cyan-400 rounded-lg shadow-lg">
                  <div class="lg:flex items-center p-6">
                     <div class="flex items-center justify-center w-12 h-12 rounded-full bg-white/20">
                        <i class="bi bi-people text-2xl text-white"></i>
                     </div>
                     <div class="ml-4">
                        <p class="lg:text-3xl font-bold text-white">
                              {{ totalClients }}
                        </p>
                        <p class="text-white/90">
                              Clients
                        </p>
                     </div>
                  </div>
            </div>

            <!-- Members Card -->
            <div class="flex-1 bg-gradient-to-r from-red-500 to-red-400 rounded-lg shadow-lg">
                  <div class="lg:flex items-center p-6">
                     <div class="flex items-center justify-center w-12 h-12 rounded-full bg-white/20">
                        <i class="bi bi-person text-2xl text-white"></i>
                     </div>
                     <div class="ml-4">
                        <p class="lg:text-3xl font-bold text-white">
                              {{ totalMembers }}
                        </p>
                        <p class="text-white/90">
                              Members
                        </p>
                     </div>
                  </div>
            </div>

            <!-- Earnings Card -->
            <div class="flex-1 bg-gradient-to-r from-green-500 to-green-400 rounded-lg shadow-lg">
               <div class="lg:flex items-center p-6">
                  <div class="flex items-center justify-center w-12 h-12 rounded-full bg-white/20">
                     <span class="text-2xl text-white">₱</span>
                  </div>
                  <div class="ml-4">
                        <p class="lg:text-3xl font-bold text-white">
                           {{ totalEarnings }}
                        </p>
                        <p class="text-white/90">
                           Total Earnings
                        </p>
                  </div>
               </div>
            </div>
            
         </div>

         <!-- Graph -->
         <div class="bg-white rounded-lg shadow-lg mt-[2rem]">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200">
                  <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                     <i class="bi bi-graph-up-arrow mr-2"></i>
                     REPORTS
                  </h2>
            </div>

            <div class="p-6">
                  <!-- Title with subtle badge -->
                  <div class="mb-6 flex items-center">
                     <h3 class="text-lg text-gray-700 font-semibold">Monthly Gym Visits Graph</h3>
                     <span class="ml-2 px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">2024</span>
                  </div>

                  <div class="lg:flex gap-6">
                     <!-- Chart Section -->
                     <div class="flex-1 mb-6 lg:mb-0">
                        <ClientsChart :months="months" :totals="totals" class="h-[400px]"/>
                     </div>

                     <!-- Weekly Statistics Cards -->
                     <div class="flex lg:w-[460px] gap-4">
                        <!-- Visits Card -->
                        <!-- Large Screen Version -->
                        <div class="hidden lg:flex flex-1 bg-gradient-to-b from-emerald-400 to-emerald-700 rounded-xl shadow-lg overflow-hidden">
                              <div class="p-6 flex flex-col items-center justify-between h-full w-full">
                                 <div class="bg-white/10 rounded-full p-4 mb-4">
                                    <i class="bi bi-people text-4xl text-white"></i>
                                 </div>
                                 <div class="text-center flex-grow flex flex-col justify-center">
                                    <span class="block text-4xl font-bold text-white mb-2">
                                          {{ totalLogsThisWeek }}
                                    </span>
                                    <div class="text-white/90 text-lg font-medium">
                                          Visits this week
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
                                    <p class="text-3xl font-bold text-white">
                                          {{ totalLogsThisWeek }}
                                    </p>
                                    <p class="text-white/90">
                                          Visits this week
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
                                          {{ totalEarningsThisWeek }}
                                    </span>
                                    <div class="text-white/90 text-lg font-medium">
                                          Earnings this week
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
                                    <p class="text-3xl font-bold text-white">
                                          {{ totalEarningsThisWeek }}
                                    </p>
                                    <p class="text-white/90">
                                          Earnings this week
                                    </p>
                                 </div>
                              </div>
                        </div>
                     </div>
                  </div>
            </div>
         </div>

      </div>

      <!-- First Announcement Section -->
      <div v-if="firstAnnouncement" class="bg-white mt-4 m-3 rounded-lg shadow-lg overflow-hidden">
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

      <!-- Announcements Section -->
      <div class="bg-white mt-4 m-3 rounded-lg shadow-lg overflow-hidden">
         <div class="px-6 py-4 bg-gradient-to-r from-gray-100 to-gray-200 border-b border-gray-300">
            <p class="text-xl text-gray-700 font-semibold flex items-center">
               <i class="bi bi-calendar-event mr-3 text-gray-600"></i>
               ANNOUNCEMENTS
            </p>
         </div>

         <table class="w-full">
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

         <!-- No announcements state -->
         <div v-if="announcements.length === 0" class="text-center py-6 text-gray-500">
            <i class="bi bi-info-circle mr-2"></i>
            No announcements at this time
         </div>
      </div>
   </Layout>
</template>

<script setup>
import Layout from '../../Layouts/Layout.vue';
import ClientsChart from '../../Components/Charts/ClientsChart.vue';

const props = defineProps({
   months: Array,
   totals: Array,
   totalClients: Number,
   totalMembers: Number,
   totalEarnings: Number,
   totalLogsThisWeek: Number,
   totalEarningsThisWeek: Number,
   announcements: Array,
   firstAnnouncement: Object
});

const formatDate = (dateString) => {
   return new Date(dateString).toLocaleDateString('en-US', {
      month: 'short', 
      day: 'numeric', 
      year: 'numeric'
   })
}
</script>