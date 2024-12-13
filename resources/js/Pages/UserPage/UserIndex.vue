<template>
   <UserLayout>
      <div class="p-3">
         <div class="pb-8 border-b border-gray-200">
            <h1 class="text-3xl font-bold text-gray-800">
               Dashboard
            </h1>
         </div>

         <!-- Clients Card -->
         <div class="flex-1 bg-gradient-to-r from-cyan-600 to-cyan-400 rounded-lg shadow-lg mb-4">
            <div class="flex items-center justify-center mx-auto p-6">
               <div class="text-center">
                  <div class="flex items-center justify-center w-12 h-12 rounded-full bg-white/20 m-auto">
                     <i class="bi bi-people text-2xl text-white"></i>
                  </div>

                  <p class="lg:text-3xl font-bold text-white lg:text-center">
                        {{ totalGymVisits }}
                  </p>

                  <p class="text-white/90 font-bold">
                     Your Gym visits
                  </p>
               </div>
            </div>
         </div>

         <!-- Expirations Card -->
         <div class="flex-1  bg-gradient-to-r from-cyan-600 to-cyan-400 rounded-lg shadow-lg">
            <div class="lg:flex items-center justify-center mx-auto p-6">
               <div class="ml-4">

                  <div v-if="paymentMethod && paymentMethod.type === 'monthly' && isInDateRange" >
                     <div class="text-center">
                        <p class="text-white text-2xl font-bold">
                           Your Monthly Plan is still active!
                        </p>
                        <p class="text-white font-semibold">
                           This will expire on: {{ latestMonthlyTransaction && latestMonthlyTransaction.end_date ? latestMonthlyTransaction.end_date : '-'}}
                        </p>
                     </div>
                  </div>

                  <div v-if="paymentMethod && paymentMethod.type === 'monthly' && !isInDateRange" >
                     <div class="text-center">
                        <p class="text-white text-2xl font-bold">
                           Your Monthly Plan is Expired!
                        </p>
                        <p class="text-white font-semibold">
                           Expired on: {{ latestMonthlyTransaction && latestMonthlyTransaction.end_date ? latestMonthlyTransaction.end_date : '-'}}
                        </p>
                     </div>
                  </div>

                  <div v-if="paymentMethod && paymentMethod.type === 'walk-in'" >
                     <div class="text-center">
                        <p class="text-white text-2xl font-bold">
                           You're on Session payment.
                        </p>
                        <p class="text-white font-semibold">
                           Upgrade to Monthly now!
                        </p>
                     </div>
                  </div>

               </div>
            </div>
         </div>

         <!-- Trainor Section -->
         <div class="bg-white mt-6 rounded-lg shadow-lg">
            <div class="px-6 py-4 bg-gradient-to-r from-blue-500 to-blue-400 border-b border-blue-300">
               <p class="text-xl text-white font-semibold flex items-center">
                  <i class="bi bi-person-badge-fill mr-3"></i>
                  TRAINING DETAILS
               </p>
            </div>

            <div class="p-6">
               <div class="flex items-center mb-4">
                  <i class="bi bi-person-circle text-blue-500 text-3xl mr-4"></i>
                  <h2 class="text-2xl font-bold text-gray-800">Meet Your Trainor</h2>
               </div>

               <p class="text-gray-600" v-if="training">
                  Stay connected and motivated with personalized training sessions.
               </p>

               <!-- If there's no training -->
               <div v-else>
                  <p class="text-gray-600">
                     You're not currently enrolled in any training. Contact us to start your personalized training sessions!
                  </p>
               </div>

               <ul v-if="training" class="mt-4 space-y-3">
                  <li class="flex items-center">
                     <i class="bi bi-check-circle text-blue-500 mr-3"></i>
                     <span class="text-gray-700">Trainor Name: {{ training.coach.first_name }} {{ training.coach.middle_initial ? training.coach.middle_initial + '.' : '' }} {{ training.coach.last_name }}</span>
                  </li>

                  <li class="flex items-center">
                     <i class="bi bi-check-circle text-blue-500 mr-3"></i>
                     <span class="text-gray-700">Training Name: {{ training.name }}</span>
                  </li>
               </ul>
            </div>
         </div>

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

         <!-- Announcements Section -->
         <div class="bg-white mt-4 m-3">
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
                     v-for="announcement in announcements.data" 
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
            </div>

            <Pagination class="flex mt-4 justify-end" :links="announcements.links" preserve-scroll/>

         </div>
      </div>
   </UserLayout>
</template>
   
<script setup>
import UserLayout from '../../Layouts/UserLayout.vue';
import Pagination from '../../Components/Pagination.vue';
import { ref } from 'vue';

const props = defineProps({
   user: Object,
   totalGymVisits: Number,
   announcements: (Array, Object),
   firstAnnouncement: Object,
   paymentMethod: Object,
   latestMonthlyTransaction: Object,
   training: Object
})

// Function to check if today's date is between start and end dates
const isDateInRange = (startDate, endDate) => {
  const today = new Date(); // Today's date
  const start = new Date(startDate);
  const end = new Date(endDate);
  return today >= start && today <= end;
}

// Check if latestMonthlyTransaction exists and if its date range includes today
const isInDateRange = ref(false);

if (props.latestMonthlyTransaction) {
  const { start_date, end_date } = props.latestMonthlyTransaction;
  isInDateRange.value = isDateInRange(start_date, end_date);
}

const formatDate = (dateString) => {
   return new Date(dateString).toLocaleDateString('en-US', {
      month: 'short', 
      day: 'numeric', 
      year: 'numeric'
   })
}
</script>