<template>
   <TrainerLayout>
      <div class="p-3">
         <div class="pb-4">
            <p class="text-[30px] text-gray-600">DASHBOARD</p>
         </div>

         <!-- Single summary container -->
          <div class="flex flex-row space-x-4 w-full py-2">
            
            <!-- Total Trainngs Card -->
            <div class="flex-1 bg-gradient-to-r from-red-600 to-red-400 rounded-lg shadow-lg mb-4">
               <div class="flex items-center justify-center mx-auto p-6">
                  <div class="text-center">
                     <div class="flex items-center justify-center w-12 h-12 rounded-full bg-white/20 m-auto">
                        <i class="bi bi-people text-2xl text-white"></i>
                     </div>

                     <p class="lg:text-3xl font-bold text-white lg:text-center">
                           {{ totalStudents }}
                     </p>

                     <p class="text-white/90 font-bold">
                        Total Students
                     </p>
                  </div>
               </div>
            </div>
            
            <!-- Total Trainngs Card -->
            <div class="flex-1 bg-gradient-to-r from-cyan-600 to-cyan-400 rounded-lg shadow-lg mb-4">
               <div class="flex items-center justify-center mx-auto p-6">
                  <div class="text-center">
                     <div class="flex items-center justify-center w-12 h-12 rounded-full bg-white/20 m-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="white" class="bi bi-person-arms-up" viewBox="0 0 16 16">
                           <path d="M8 3a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3"/>
                           <path d="m5.93 6.704-.846 8.451a.768.768 0 0 0 1.523.203l.81-4.865a.59.59 0 0 1 1.165 0l.81 4.865a.768.768 0 0 0 1.523-.203l-.845-8.451A1.5 1.5 0 0 1 10.5 5.5L13 2.284a.796.796 0 0 0-1.239-.998L9.634 3.84a.7.7 0 0 1-.33.235c-.23.074-.665.176-1.304.176-.64 0-1.074-.102-1.305-.176a.7.7 0 0 1-.329-.235L4.239 1.286a.796.796 0 0 0-1.24.998l2.5 3.216c.317.316.475.758.43 1.204Z"/>
                        </svg>
                     </div>

                     <p class="lg:text-3xl font-bold text-white lg:text-center">
                           {{ totalTrainings }}
                     </p>

                     <p class="text-white/90 font-bold">
                        Total Trainings
                     </p>
                  </div>
               </div>
            </div>

            <!-- Total Trainngs Card -->
            <div class="flex-1 bg-gradient-to-r from-cyan-600 to-cyan-400 rounded-lg shadow-lg mb-4">
               <div class="flex items-center justify-center mx-auto p-6">
                  <div class="text-center">
                     <div class="flex items-center justify-center w-12 h-12 rounded-full bg-white/20 m-auto">
                        <span class="text-2xl text-white">₱</span>
                     </div>

                     <p class="lg:text-3xl font-bold text-white lg:text-center">
                        <span class="text-md font-normal text-white">₱</span> {{ + earnings }}
                     </p>

                     <p class="text-white/90 font-bold">
                        Total Earnings
                     </p>
                  </div>
               </div>
            </div>

          </div>


         <!-- Announcements Section -->
         <div class="bg-white mt-4 m-3">
            <div class="px-3 py-2 border-b-2 border-gray-400">
               <p class="text-[20px] text-gray-600 font-bold">ANNOUNCEMENTS</p>
            </div>

            <table class="w-full">
               <thead class="bg-gray-100">
                  <tr>
                     <th class="px-4 py-2 text-left text-gray-600">Content</th>
                     <th class="px-4 py-2 text-left text-gray-600">Date</th>

                  </tr>
               </thead>
               <tbody>
                  <tr 
                     v-for="announcement in announcements.data" 
                     :key="announcement.id"
                     class="border-b hover:bg-gray-50"
                  >
                     <td class="px-4 py-2 flex flex-col">
                        <div class="text-[20px] font-bold my-2">{{ announcement.title }}</div>
                        <div class="mx-2">{{ announcement.content }}</div>
                     </td>
                     <td class="px-4 w-[10rem]">
                        {{ formatDate(announcement.created_at) }}
                     </td>
                     
                  </tr>
               </tbody>
            </table>

            <Pagination class="flex mt-4 justify-end" :links="announcements.links" preserve-scroll/>

         </div>
      </div>
   </TrainerLayout>
</template>
<script setup>
import TrainerLayout from '../../Layouts/TrainerLayout.vue';
import Pagination from '../../Components/Pagination.vue';

const props = defineProps({
   totalStudents: Number,
   totalTrainings: Number,
   announcements: (Array, Object),
   earnings: Number
})

const formatDate = (dateString) => {
   return new Date(dateString).toLocaleDateString('en-US', {
      month: 'short', 
      day: 'numeric', 
      year: 'numeric'
   })
}
</script>