<template>
   <Layout>
      <div class=" p-3 ">

         <div class="pb-4">
            <p class="text-[30px] text-gray-600">ADMIN DASHBOARD</p>
         </div>

         <!-- Summary -->
         <div class="flex justify-between pb-4">

            <!-- Single summary container -->
            <div class="bg-[#2dd1d4] w-[28rem] h-[7rem] flex items-center justify-start px-4">
               <!-- Icon container -->
               <div class="w-[4rem] h-[4rem] flex justify-center items-center">
                  <i class="bi bi-person text-white text-[60px] mt-[-1rem]"></i>
               </div>
                  <!-- Title and Data -->
               <div class="flex flex-col px-4">
                     <p class="text-3xl text-white font-bold">
                        {{ totalClients }}
                     </p>
                     <div class="text-white ">
                        Clients
                     </div>
               </div>
            </div>

            <!-- Single summary container -->
            <div class="bg-[#dc4747] w-[28rem] h-[7rem] flex items-center justify-start px-4">
               <!-- Icon container -->
               <div class="w-[4rem] h-[4rem] flex justify-center items-center">
                  <i class="bi bi-person text-white text-[60px] mt-[-1rem]"></i>
               </div>
                  <!-- Title and Data -->
               <div class="flex flex-col px-4">
                     <p class="text-3xl text-white font-bold">
                        {{ totalMembers }}
                     </p>
                     <div class="text-white ">
                        Members
                     </div>
               </div>
            </div>

            <!-- Single summary container -->
            <div class="bg-[#2dd45f] w-[28rem] h-[7rem] flex items-center justify-start px-4">
               <!-- Icon container -->
               <div class="w-[4rem] h-[4rem] flex justify-center items-center">
                  <i class="bi bi-person text-white text-[60px] mt-[-1rem]"></i>
               </div>
                  <!-- Title and Data -->
               <div class="flex flex-col px-4">
                     <p class="text-3xl text-white font-bold">
                        {{ totalEarnings }}
                     </p>
                     <div class="text-white ">
                        Total Earnings
                     </div>
               </div>
            </div>
         </div>

         <!-- Graph -->
         <div class="bg-white ">
            <div class="px-3 py-2 border-b-2 border-gray-400">
               <p class="text-[20px] text-gray-600 font-bold">REPORTS</p>
            </div>

            <div class="px-5 py-4 flex flex-col">
               <div>
                  <p class="text-[18px] text-gray-500">Monthly Gym Visits Graph</p>
               </div>
               <div class="flex">
                  <ClientsChart :months="months" :totals="totals" class="mr-[4rem]"/>

                  <!-- Weekly summary -->
                  <div class="flex">
                     <!-- Single summary container -->
                     <div class="bg-[#2dd45f] w-[10rem] h-[25rem] flex flex-col items-center justify-center px-4 mx-4">
                        <!-- Icon container -->
                        <div class="w-[4rem] h-[4rem] flex justify-center items-center py-4">
                           <i class="bi bi-person text-white text-[60px] mt-[-1rem]"></i>
                        </div>

                        <div class="py-4">
                           <p class="text-3xl text-white font-bold">
                              {{ totalLogsThisWeek }}
                           </p>
                        </div>

                        <div class="py-4">
                           <div class="text-lg text-white text-center">
                              Visits this week
                           </div>
                        </div>
                     </div>

                     <!-- Single summary container -->
                     <div class="bg-[#2dd45f] w-[10rem] h-[25rem] flex flex-col items-center justify-center px-4">
                        <!-- Icon container -->
                        <div class="w-[4rem] h-[4rem] flex justify-center items-center py-4">
                           <i class="bi bi-person text-white text-[60px] mt-[-1rem]"></i>
                        </div>

                        <div class="py-4">
                           <p class="text-3xl text-white font-bold">
                              {{ totalEarningsThisWeek }}
                           </p>
                        </div>

                        <div class="py-4">
                           <div class="text-lg text-white text-center">
                              Earnings this week
                           </div>
                        </div>
                     </div>

                  </div>

               </div>
            </div>
         </div>
      </div>

      <!-- <div>
         <p>Another Permission</p>
      </div> -->

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
});

console.log('Props in Home.vue:', props);
</script>