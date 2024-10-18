<template>

   <Layout>
      <div class="px-2 py-2">
         <p class="text-[30px] text-gray-600">TRANSACTIONS LIST</p>
      </div>

      <div class="px-2 py-2">
         <div class="bg-white shadow-md rounded overflow-hidden p-3">
            <table class="w-full text-left text-gray-500 bg-white">
               <thead class="text-l text-700 uppercase bg-gray-100">
                  <tr class="text-center lg:text-[15px] text-[7px]">
                     <th scope="col" class="lg:px-5 px-3 py-3">Transaction ID</th>
                     <th scope="col" class="lg:px-5 px-3 py-3">Client Name</th>
                     <th scope="col" class="lg:px-5 px-3 py-3">Payment Type</th>
                     <th scope="col" class="lg:px-5 px-3 py-3">Duration</th>
                     <th scope="col" class="lg:px-5 px-3 py-3">Transaction Date</th>
                     <th scope="col" class="lg:px-5 px-3 py-3">Total</th>
                  </tr>
               </thead>
               <tbody>
                  <tr v-for="transaction in transactions" :key="transaction.id" class="text-center lg:text-[15px] text-[7px]">
                     <td>{{ transaction.id }}</td>
                     <td>{{ transaction.client.first_name }} {{ transaction.client.last_name }}</td>
                     <!-- Conditional logic to display "Monthly" or "Walk-in" -->
                     <td>
                        <span v-if="transaction.start_date && transaction.end_date">Monthly</span>
                        <span v-else>Walk-in</span>
                     </td>
                     <td>
                        <span v-if="transaction.start_date && transaction.end_date">{{ formatDate(transaction.start_date) }} - {{ formatDate(transaction.end_date) }}</span>
                        <span v-else>-</span>
                     </td>
                     <td>{{ formatDate(transaction.transaction_date) }} {{ formatTime(transaction.transaction_date) }}</td>
                     <td>{{ transaction.total_amount }}</td>
                  </tr>
               </tbody>
            </table>
         </div>
      </div>

   </Layout>

</template>



<script setup>

import Layout from '@/Layouts/Layout.vue';

const props = defineProps({
   transactions: Array
})

function formatDate(dateString) {
   const options = { year: 'numeric', month: 'long', day: 'numeric' };
   return new Date(dateString).toLocaleDateString(undefined, options);
}
const formatTime = (dateString) => {
  const date = new Date(dateString);
  return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', hour12: true }); 
};
</script>