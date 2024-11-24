<template>
   <UserLayout>
      <div class="p-3">
         <div class="pb-4">
            <p class="text-[30px] text-gray-600">DASHBOARD</p>
         </div>

         <!-- Single summary container -->
         <div class="bg-[#2dd1d4] h-[7rem] flex flex-col items-center justify-center px-4 m-3">
            <div class="text-[25px] font-bold text-white">{{ totalGymVisits }}</div>
            <div class="text-[20px] text-white">Your Gym visits this week</div>
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
   </UserLayout>
</template>
   
<script setup>
import UserLayout from '../../Layouts/UserLayout.vue';
import Pagination from '../../Components/Pagination.vue';

const props = defineProps({
   user: Object,
   totalGymVisits: Number,
   announcements: (Array, Object)
})


const formatDate = (dateString) => {
return new Date(dateString).toLocaleDateString('en-US', {
   month: 'short', 
   day: 'numeric', 
   year: 'numeric'
})
}
</script>